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

class Settings extends  CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this -> data['title'] = 'Settings';

		if ($this -> Admin -> IsAdmin() == FALSE)
		{
			die("You do not have permission to access this page");
		}

		$this -> data['settings'] = $this -> Admin -> GetAllSettings();
		$this -> data['isadmin'] = $this -> Admin -> IsAdmin();
		$this->data['staff'] = $this->Admin->GetStaffDetails($this->session->userdata('staffid'));
	}

	function index()
	{
		//$this->data['sidebar'] = 'settings_sidebar' ;
		$this -> load -> admin_template('settings', $this -> data);
	}

	function save()
	{
		// Save settings

		$this -> form_validation -> set_rules('companyname', 'Company Name', 'trim|required|xss_clean');
		$this -> form_validation -> set_rules('smtp_pass', 'SMTP Password', 'trim|xss_clean');

		if ($this -> form_validation -> run() == FALSE)
		{
			$this -> load -> admin_template('settings', $this -> data);
		}
		else
		{

			$insert = array(
				'companyname' => $this -> input -> post('companyname'),
				'smtp_host' => $this -> input -> post('smtp_host'),
				'smtp_pass' => $this -> encrypt -> encode($this -> input -> post('smtp_pass')),
				'smtp_user' => $this -> input -> post('smtp_user'),
				'smtp_port' => $this -> input -> post('smtp_port'),
				'email' => $this -> input -> post('email'),
				'mailprotocol' => $this -> input -> post('mailprotocol'),
				'message' => $this -> input -> post('message'),
			);

			foreach ($insert as $key => $value)

			{
				/**
				 * Check whether the key already exists in tblsettings table. If
				 * exists, we update the value, else create the key(insert
				 * value).
				 *
				 */

				$this -> db -> where("setting", $key) -> get('tblsettings');
				if ($this -> db -> affected_rows() == 1)
				{
					// The key already present. So we just update it.
					$this -> db -> where('setting', $key);
					$update = array('value' => $value);
					$this -> db -> update('tblsettings', $update);

				}
				else
				{
					$insert_data = array(
						'setting' => $key,
						'value' => $value
					);
					
					
					$this -> db -> insert('tblsettings', $insert_data);
				}

				$this -> data['success'] = 'Settings have been updated';

			}

			$this -> data['settings'] = $this -> Admin -> GetAllSettings();
			// Refresh the values
			$this -> index();

		}
	}

}
