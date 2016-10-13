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


class Emails extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	function GetSetting($setting)
	{
		$this -> db -> where('setting', $setting);
		$result = $this -> db -> get('tblsettings');
		$row = $result -> row_array();
		return $row['value'];
	}

	function SendMail($to, $subject, $body)
	{
		$mailprotocol = $this -> GetSetting('mailprotocol');
		$this -> load -> library('email');

		$smtp_host = $this -> GetSetting('smtp_host');
		$smtp_port = $this -> GetSetting('smtp_port');
		$smtp_user = $this -> GetSetting('smtp_user');
		$smtp_pass = $this -> GetSetting('smtp_pass');

		$config = array(
			'useragent' => "XTicket",
			'protocol' => $mailprotocol,
			'smtp_host' => $smtp_host,
			'smtp_user' => $smtp_user,
			'smtp_pass' => $this->encrypt->decode($smtp_pass),
			'smtp_port' => $smtp_port,
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'newline' => "\r\n",
			'wordwrap' => TRUE,
		);
		$this -> email -> initialize($config);

		$email = $this -> GetSetting('email');
		$companyname = $this -> GetSetting('companyname');
		
		
		$this -> email -> from($email, $companyname);
		$this -> email -> to($to);
		$this -> email -> subject($subject);
		$this -> email -> message($body);
		if ($this -> email -> send())
		{
			return TRUE;
		}
		else
		{
			echo 'Unable to send mail';
			//return $this -> email -> print_debugger();
		}

	}

}
