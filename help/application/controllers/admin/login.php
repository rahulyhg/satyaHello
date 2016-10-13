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

class Login extends CI_Controller
{

	var $template = 'default';
	var $data = array(
		'title',
		'Reset Password'
	);

	function __construct()
	{
		parent::__construct();
		$this->load->model("Emails");
	}

	function index()
	{
		$this -> load -> view("$this->template/admin/login", $this -> data);
	}

	function validate()
	{

		$this -> form_validation -> set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
		$this -> form_validation -> set_rules('password', 'Password', 'trim|required|xss_clean');

		if ($this -> form_validation -> run() == FALSE)
		{
			$this -> load -> view("$this->template/admin/login");
		}
		else
		{

			$email = $this -> input -> post('email');
			$password = $this -> input -> post('password');
			$remember = $this -> input -> post('remember');

			$result = $this -> Admin -> AdminLogin($email, $password, $remember);

			if ($result == TRUE)
			{
				redirect('admin/index');
			}
			else
			{
				$this -> data['error'] = "Invalid email/password combination";
				$this -> load -> view("$this->template/admin/login", $this -> data);
			}

		}

	}

	function reset()
	{
		$this -> load -> view("$this->template/admin/reset");
	}

	function resetpwd()

	{
		$email = $this -> input -> post('email');

		if ($this -> Admin -> IsStaffEmail($email) === TRUE)
		{
			$this -> load -> model('Emails');
			$hash = $this -> Admin -> MakeStaffForgotPasswordHash($email);

			$companyname = $this -> Emails -> GetSetting('companyname');
			$link = site_url() . '/admin/login/confirm/' . $hash;

			$subject = "Password Reset";

			$body = "
			Dear Staff,<br><br>
			
			Your password reset request has been received. Click on the link below to confirm the request. If you did not request as password reset of your account, simply delete this email.<br><br>
			
			$link<br><br> 
			
			Regards<br>
			$companyname
			";

			$this -> Emails -> SendMail($email, $subject, $body);
			$this -> data['success'] = "A verification mail has been sent to your email address. Please check it to reset the password. You may close this page now.";
			$this -> load -> view("$this->template/admin/reset", $this -> data);

		}
		else
		{
			$this -> data['error'] = "Invalid email address";
			$this -> load -> view("$this->template/admin/reset", $this -> data);
		}

	}

	function confirm()
	{
		$hash = $this -> uri -> segment('4');

		if ($hash)
		{
			$result = $this -> Admin -> ResetPassword($hash);
		}

		if ($result != FALSE)
		{
			
			$subject = "New Password";
			$link = site_url() .'/admin';
			$newpassword = $result['newpassword'];
			$email = $result['email'] ;
			
			
			$companyname = $this->Emails->GetSetting('companyname');
			
			$body ="
			Dear Staff, <br><br>
			
			Your password has been reset. Here is the new login<br><br>
			
			URL: $link<br>
			Email: $email<br>
			Password: $newpassword<br><br>
			
			Please change your password immediately after logging in.<br><br>
			
			Regards<br>
			$companyname;
			
			";
			
			$this->Emails->SendMail($email, $subject, $body );
			
			
			$this -> data['success'] = "Password has been reset. Please check your email for new password";
			$this -> index();
		}

	}

}
