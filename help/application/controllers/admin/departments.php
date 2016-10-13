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

class Departments extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		if ($this -> Admin -> IsAdmin() == FALSE)
		{
			die("You do not have permission to access this page");
		}
		$this -> data['title'] = 'Departments';
		$this -> data['departments'] = $this -> Admin -> ListDepartments();
		$this -> data['isadmin'] = $this -> Admin -> IsAdmin();
		$this->data['staff'] = $this->Admin->GetStaffDetails($this->session->userdata('staffid'));

	}

	function index()
	{
		$this -> load -> admin_template('departments', $this -> data);
	}

	function add()
	{
		$this -> data['action'] = 'post';
		// This is for the jquery toggle function

		$this -> form_validation -> set_rules('deptname', 'Department Name', 'required|trim|xss_clean');

		if ($this -> form_validation -> run() == FALSE)
		{
			$status['status'] = 0;
			$status['statusmsg'] = validation_errors();
			echo json_encode($status);

		}
		else
		{
			$data['deptname'] = $this -> input -> post('deptname');
			$this -> Admin -> AddDepartment($data);
			$status['status'] = 1;
			echo json_encode($status);

		}

	}

	function update()
	{
		$this -> form_validation -> set_rules('deptname', 'Department Name', 'required|trim|xss_clean');
		if ($this -> form_validation -> run() == FALSE)
		{
			$this -> load -> admin_template('departments', $this -> data);
		}
		else
		{
			// Form validation success.

			$data['deptname'] = $this -> input -> post('deptname');
			$data['deptid'] = $this -> input -> post('deptid');
			$this -> Admin -> EditDepartment($data);

			$status['status'] = 1;
			echo json_encode($status);

		}

	}

	function delete()
	{
		$deptid = $this -> uri -> segment('4');
		$this -> Admin -> DeleteDepartment($deptid);
		$this -> data['departments'] = $this -> Admin -> ListDepartments();
		$this -> data['success'] = "Department has been deleted";
		$this -> load -> admin_template('departments', $this -> data);
	}

	function fields($deptid = '')
	{
		if (!$deptid)
			$deptid = $this -> uri -> segment('4');
		$this -> data['deptid'] = $deptid;

		$this -> session -> set_userdata('deptid', $deptid);

		$this -> data['fields'] = $this -> Admin -> ListAllFields($deptid);
		$this -> load -> admin_template('fields', $this -> data);

	}

	function addfield()
	{
		$this -> form_validation -> set_rules('name', 'Field Name', 'required|trim|xss_clean');
		if ($this -> form_validation -> run() == FALSE)
		{
			$status['status'] = 0;
			$status['statusmsg'] = validation_errors();
			echo json_encode($status);
		}
		else
		{
			$name = $this -> input -> post('name');
			$type = $this -> input -> post('type');
			$deptid = $this -> input -> post('deptid');

			$data = array(
				'name' => $name,
				'type' => $type,
				'deptid' => $deptid,
				'uniqid' => $this -> Admin -> GenerateUniqueFieldValue(),
			);

			if ($this -> Admin -> AddField($data) == TRUE)
			{
				$status['status'] = 1;
				$status['statusmsg'] = 'Field Added';
				echo json_encode($status);
			}
			else
			{
				$status['status'] = 0;
				$status['statusmsg'] = 'Unable to add field';
				echo json_encode($status);
			}
		}

	}

	function deletefield()
	{
		$field = $this -> uri -> segment('4');
		$this -> Admin -> DeleteField($field);
		$this -> fields($this -> session -> userdata('deptid'));
	}

	function updatefield()
	{
		$this -> form_validation -> set_rules('name', 'Field Name', 'required|trim|xss_clean');
		if ($this -> form_validation -> run() == FALSE)
		{
			$status['status'] = 0;
			$status['statusmsg'] = validation_errors();
			echo json_encode($status);
		}
		else
		{
			$name = $this -> input -> post('name');
			$type = $this -> input -> post('type');
			$id = $this -> input -> post('id');

			$data = array(
				'name' => $name,
				'type' => $type,
				'id' => $id
			);
			if ($this -> Admin -> EditField($data) == TRUE)
			{
				$status['status'] = 1;
				$status['statusmsg'] = 'Field Edited';
				echo json_encode($status);
			}
			else
			{
				$status['status'] = 0;
				$status['statusmsg'] = 'Not saved. Nothing modified!';
				echo json_encode($status);
			}
		}
	}

}
