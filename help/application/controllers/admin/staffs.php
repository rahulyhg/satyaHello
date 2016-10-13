<?php
/*
 * Fuse
 *
 * A simple open source ticket management system. 
 *
 * @package		Fuse
 * @author		Vivek. V
 * @link		http://getvivekv.bitbucket.org/Fuse
 * @link		http://www.vivekv.com
 */


class Staffs extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		if ($this -> Admin -> IsAdmin() == FALSE)
		{
			die("You do not have permission to access this page");
		}
		$this -> data['title'] = 'Staffs';
		$this -> data['staffs'] = $this -> Admin -> ListStaffs();
		$this -> data['department'] = $this -> Admin -> ListDepartments();
		if($this -> Admin -> IsSuperAdmin()==TRUE)
		{
		$this -> data['staffs_admin'] = $this -> Admin -> ListStaffs_admin();
		}
		$this -> data['isadmin'] = $this -> Admin -> IsAdmin();
		$this->data['staff'] = $this->Admin->GetStaffDetails($this->session->userdata('staffid'));
	}

	function index()
	{
		$this -> data['action'] = 'add';
		$this -> load -> admin_template('staffs', $this -> data);
	}

	function add()
	{
        $this -> form_validation -> set_rules('department', 'Department', 'required|trim|xss_clean');
		$this -> form_validation -> set_rules('firstname', 'Staff First Name', 'required|trim|xss_clean');
		$this -> form_validation -> set_rules('lastname', 'Staff Last Name', 'required|trim|xss_clean');
		$this -> form_validation -> set_rules('email', 'Staff Email', 'required|valid_email|trim|xss_clean');
		$this -> form_validation -> set_rules('password', 'Password', 'required|trim|xss_clean');

		if ($this -> form_validation -> run() == FALSE)
		{

			$status['status'] = 0;
			$status['statusmsg'] = validation_errors();
			echo json_encode($status);
		}
		else
		{
			$data['firstname'] = $this -> input -> post('firstname');
			$data['lastname'] = $this -> input -> post('lastname');
			$data['email'] = $this -> input -> post('email');
			$data['password'] = $this->encrypt->encode($this -> input -> post('password'));
            $data['department'] = $this -> input -> post('department');
			    $data['admin'] = $this -> input -> post('admin');
			$result = $this -> Admin -> AddStaff($data);
			if ($result == FALSE)
			{
				$status['statusmsg'] = "A user with the email already exists";
				$status['status'] = 0;
				echo json_encode($status);
			}
			else
			{
				$status['status'] = 1;
				echo json_encode($status);
			}

		}

	}

	function update()
	{

		$this -> form_validation -> set_rules('firstname', 'Staff First Name', 'required|trim|xss_clean');
		$this -> form_validation -> set_rules('lastname', 'Staff Last Name', 'required|trim|xss_clean');
		$this -> form_validation -> set_rules('email', 'Staff Email', 'required|valid_email|trim|xss_clean');
		$this -> form_validation -> set_rules('password', 'Password', 'trim|xss_clean');

		if ($this -> form_validation -> run() == FALSE)
		{
			$status['status'] = 0;
			$status['statusmsg'] = validation_errors();
			echo json_encode($status);
		}
		else
		{
			// Form validation success.

			$data['firstname'] = $this -> input -> post('firstname');
			$data['lastname'] = $this -> input -> post('lastname');
			$data['email'] = $this -> input -> post('email');
			$data['id'] = $this -> input -> post('id');
			
			if($this->input->post('password'))
				$data['password'] = $this->encrypt->encode($this -> input -> post('password'));

			$result = $this -> Admin -> EditStaff($data);

			if ($result == FALSE)
			{
				$status['statusmsg'] = "A user with the email already exists";
				$status['status'] = 0;
				echo json_encode($status);
			}
			else
			{
				$status['status'] = 1;
				echo json_encode($status);

			}

		}

	}

	function delete()
	{
		$staffid = $this -> uri -> segment(4);
		if ($this -> session -> userdata('staffid') == $staffid)
		{
			$this -> data['error'] = 'You can not delete your own account!';
		}
		else
		{
			$this -> Admin -> DeleteStaff($staffid);
			$this -> data['staffs'] = $this -> Admin -> ListStaffs();
			$this -> data['staffs_admin'] = $this -> Admin -> ListStaffs_admin();
		}
		$this -> load -> admin_template('staffs', $this -> data);

	}

}
