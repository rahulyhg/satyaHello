<?php
/*
 * Fuse
 *
 * A simple open source ticket management system.
 *
 * @package		Fuse
 * @author		Vivek. V
 * @link		http://getvivekv.bitbucket.org/fuse
 * @link		http://www.vivekv.com
 */

class Admin extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	function AdminLogin($email, $password, $remember = FALSE)
	{
		$array = array('email' => $email, );

		$query = $this -> db -> where($array) -> get('tblstaffs');

		if ($this -> db -> affected_rows() == 1)
		{
			$row = $query -> row_array();
			$password_on_db = $this -> encrypt -> decode($row['password']);

			if ($password == $password_on_db)
			{
				$this -> session -> set_userdata('staffid', $row['id']);
				$this -> session -> set_userdata('staffpassword', $row['password']);
				$this -> session -> set_userdata('department', $row['department']);

				if ($remember)
				{
					setcookie("staffid", $row['id'], time()+3600*720, '/');
					setcookie("staffpassword", $row['password'], time()+3600*720, '/');
					
				}

				if ($row['admin'] == 1)
				{
					$this -> session -> set_userdata('admin', 1);
				}
				return TRUE;
			}
		}

	}

	function IsAdmin()
	{
		if ($this -> session -> userdata('admin') == 1 or $this -> session -> userdata('admin') == 2 )
			return TRUE;
	}
    function IsSuperAdmin()
	{
		if ($this -> session -> userdata('admin') == 1)
			return TRUE;
	}
	function _CheckLogin()
	{
		// Check whether 'remember me' is set

		if (isset($_COOKIE['staffid']))
		{
			$staffid = $_COOKIE['staffid'];
			$staffpassword = $_COOKIE['staffpassword'];
			$this -> session -> set_userdata('staffid', $staffid);
			$this -> session -> set_userdata('staffpassword', $staffpassword);
		}

		$where = array(
			'id' => $this -> session -> userdata('staffid'),
			'password' => $this -> session -> userdata('staffpassword')
		);

		$query = $this -> db -> where($where) -> get('tblstaffs');
		if ($this -> db -> affected_rows() > 0)
		{
			$row = $query -> row_array();
			if ($row['admin'])
			{
				$this -> session -> set_userdata('admin', $row['admin']);
			}
			else
			{
				$this -> session -> set_userdata('admin', 0);
			}
            $this ->session -> set_userdata('department',$row['department']);
			return TRUE;
		}
		else
		{
			$this -> session -> sess_destroy();
			redirect('admin/login/', 'refresh');
			die();
		}

	}

	/**
	 * Gets all the settings and its values
	 *
	 * @return An array
	 */
	function GetAllSettings()
	{

		$query = $this -> db -> get("tblsettings");
		$result = $query -> result_array();

		foreach ($result as $array)
		{
			if ($array['setting'] == 'smtp_pass')
			{
				$data[$array['setting']] = $this -> encrypt -> decode($array['value']);
			}
			else
			{
				$data[$array['setting']] = $array['value'];
			}

		}
		if (is_array($data))
			return $data;
	}

	function AddDepartment($data)
	{
		$this -> db -> insert('tbldepartments', $data);
		return $this -> db -> insert_id();

	}

	function ListDepartments()
	{
		$query = $this -> db -> get('tbldepartments');
		return $query -> result_array();
	}

	function GetDepartmentDetails($id)
	{
		$query = $this -> db -> where("deptid", $id) -> get('tbldepartments');
		return $query -> row_array();
	}

	function EditDepartment($data)
	{
		$this -> db -> where("deptid", $data['deptid']);
		$this -> db -> update('tbldepartments', $data);
	}

	function DeleteDepartment($deptid)
	{
		$this -> db -> where('deptid', $deptid) -> delete('tbldepartments');
	}

	function ListStaffs()
	{     
if($this->session->userdata('admin')==0)
{
	$this->db->where('admin',0);
}
if($this->session->userdata('admin')==2)
{
	$this->db->where('admin',0);
	$this->db->where('department',$this->session->userdata('department'));
}
	$query = $this -> db -> get('tblstaffs');

		return $query -> result_array();
	}
function ListStaffs_admin()
	{    
	
	    
		$this->db->where('admin',2);
	    
		$query = $this -> db -> get('tblstaffs');
		
		return $query -> result_array();
	}
	function AddStaff($data)
	{
		// Make sure the email id does not exists

		$this -> db -> where("email", $data['email']) -> get('tblstaffs');
		if ($this -> db -> affected_rows() > 0)
		{
			return FALSE;
		}
		else
		{
			$this -> db -> insert('tblstaffs', $data);
			return $this -> db -> insert_id();
		}

	}

	function GetStaffDetails($id)
	{
		$query = $this -> db -> where("id", $id) -> get('tblstaffs');
		return $query -> row_array();
	}

	function EditStaff($data)
	{
		// Check whether if someone else is using the same email address

		$where = array(
			'email' => $data['email'],
			'id !=' => $data['id']
		);

		$this -> db -> where($where) -> get('tblstaffs');
		if ($this -> db -> affected_rows() > 0)
		{
			return 'A staff with the same email address alreadye exists';
		}

		$update = array(
			'firstname' => $data['firstname'],
			'lastname' => $data['lastname'],
			'email' => $data['email'],
		);

		if (isset($data['password']))
			$update['password'] = $data['password'];

		$this -> db -> where('id', $data['id']);
		$this -> db -> update('tblstaffs', $update);
		return TRUE;

	}

	function DeleteStaff($id)
	{
		$this -> db -> delete('tblstaffs', array('id' => $id));
	}

	function MakeStaffForgotPasswordHash($email)
	{
		$data['hash'] = md5(uniqid());
		$this -> db -> where('email', $email) -> update('tblstaffs', $data);
		return $data['hash'];
	}

	function IsStaffEmail($email)
	{
		$this -> db -> where('email', $email) -> get('tblstaffs');
		if ($this -> db -> affected_rows() > 0)
			return TRUE;
		else
			return FALSE;
	}

	function ResetPassword($hash)
	{
		$newpassword = uniqid();

		$result = $this -> db -> where('hash', $hash) -> get('tblstaffs');
		$row = $result -> row_array();
		$email = $row['email'];

		$data['password'] = $this -> encrypt -> encode($newpassword);
		$data['hash'] = '';

		$this -> db -> where('hash', $hash) -> update('tblstaffs', $data);

		if ($this -> db -> affected_rows() > 0)
		{
			$return['email'] = $email;
			$return['newpassword'] = $newpassword;

			return $return;
		}
		else
			return FALSE;
	}

	function ListPriorities()
	{
		$query = $this -> db -> get("tblpriorities");
		return $query -> result_array();

	}

	function GetAdditionalFields($department)
	{
		$this -> db -> where("deptid", $department);
		$result = $this -> db -> get("tblfields");
		return $result -> result_array();
	}

	function AddField($data)
	{
		$this -> db -> insert('tblfields', $data);
		if ($this -> db -> affected_rows() > 0)
			return TRUE;
	}

	function ListAllFields($deptid)
	{
		$result = $this -> db -> where('deptid', $deptid) -> get('tblfields');
		return $result -> result_array();
	}

	function DeleteField($field)
	{
		$this -> db -> where('id', $field) -> delete('tblfields');
	}

	function EditField($data)
	{
		$this -> db -> where('id', $data['id']) -> update('tblfields', $data);
		if ($this -> db -> affected_rows() > 0)
			return TRUE;
	}

	function GenerateUniqueFieldValue()
	{
		$uniqid = uniqid('f');
		// Id should start with an character other than digit

		$this -> db -> where('uniqid', $uniqid) -> get('tblfields');

		if ($this -> db -> affected_rows() > 0)
		{
			$this -> GetUniqueFieldValue();
			// Recursion
		}
		else
		{
			return $uniqid;
		}

	}

	function ListStatuses()
	{
		$result = $this -> db -> get('tblstatus');
		foreach ($result->result_array() as $result)
		{
			$data[] = $result['status'];
		}

		return $data;
	}

	function GetTicketsCount()
	{
		$statuses = $this -> ListStatuses();
	if($this -> session -> userdata('admin') == 1)
	{
		foreach ($statuses as $status)
		{
			$this -> db -> where('status', $status) -> get('tbltickets');
			$data[$status] = $this -> db -> affected_rows();
		}
	}
	if($this -> session -> userdata('admin') == 2)
	{
		foreach ($statuses as $status)
		{   $this->db->where('department',$this -> session -> userdata('department'));
			$this -> db -> where('status', $status) -> get('tbltickets');
			$data[$status] = $this -> db -> affected_rows();
		}
	}
    if($this -> session -> userdata('admin') == 0)
	{
		foreach ($statuses as $status)
		{   $this->db->where('updated_by',$this->session->userdata('staffid'));
		//$this->db->or_where('created_by',$this->session->userdata('staffid'));
			$this -> db -> where('status', $status) -> get('tbltickets');
			$data[$status] = $this -> db -> affected_rows();
		}
	}

		return $data;
	}

	function LogOut()
	{

		setcookie("staffid", "", time() - 3600, '/');
		setcookie("staffpassword", "", time() - 3600, '/');
		$this -> session -> sess_destroy();
		$location = site_url() . '/admin';
		header("location: $location");
		exit ;
	}
	
	function GetNotificationMessage()
	{
		$result = $this->db->where('setting', 'message')->get('tblsettings') ;
		$row = $result->row_array();
		return @$row['value'] ;
		
	}

	
}
