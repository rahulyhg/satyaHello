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

class Tickets extends CI_Controller {

	function __construct() {
		parent::__construct();
		// Validate login
		$this -> Admin -> _CheckLogin();
		$this -> data['title'] = 'Tickets';
		$this -> load -> model("Tickets_model", 'Tickets');
		$this -> load -> model("Emails");
		$this -> data['isadmin'] = $this -> Admin -> IsAdmin();
		$this -> data['staff'] = $this -> Admin -> GetStaffDetails($this -> session -> userdata('staffid'));
        $this -> data['ticketcount'] = $this -> Admin -> GetTicketsCount();
		$this -> data['staffs'] = $this -> Admin -> ListStaffs();
	}

	function index() {
		$status = urldecode($this -> uri -> segment(4));
		
		
		$this -> data['tickets'] = $this -> Tickets -> ListTickets($status);

		// If there is only 1 ticket, view that ticket content instead of listing.
		// This will help the staff to see the ticket ( eg, open ticket ) without listing
		// the ticket.
		if (sizeof($this -> data['tickets']) == 1) {
			$ticketid = $this -> data['tickets'][0]['id'];
			$this -> view($ticketid);

		} else
			$this -> load -> admin_template('tickets', $this -> data);
	}

	function view($ticketid = '') {
		if (!$ticketid)
			$ticketid = $this -> uri -> segment('4');

		$this -> data['ticketid'] = $ticketid;
		$this->data['logs']=$this -> Tickets -> GetLogs($ticketid);
		//echo '<pre>';
		//print_r($this->data['logs']);
		//echo '</pre>';
		$this -> data['ticket'] = $this -> Tickets -> GetTicketDetails($ticketid);
		$this -> data['replies'] = $this -> Tickets -> GetTicketReplies($ticketid);
		$this -> data['statuses'] = $this -> Tickets -> GetStatuses();
		$this -> data['additional'] = $this -> Tickets -> GetAdditionaFields($ticketid);
		$this -> load -> admin_template('ticket_view', $this -> data);
	}

	function reply() {
		$ticketid = $this -> input -> post('ticketid');
		$status = $this -> input -> post('status');
       
		if (!$status)
			$status = 'close';

		$this -> form_validation -> set_rules('reply', 'Reply', 'trim|required');

		$reply = $this -> input -> post('reply');
		if ($this -> form_validation -> run() == FALSE) {
			$this -> view($ticketid);
		} else {
			$data['ticketid'] = $ticketid;
			$data['body'] = $reply;
			$data['replier'] = 'staff ';
			$data['replierid'] = $this -> session -> userdata('staffid');
			$this -> Tickets -> AddReply($data, $status);
			$this -> data['success'] = "Ticket reply has been added";

			// Send mail to the customer about ticket udpate
			$result = $this -> Tickets -> GetTicketDetails($ticketid);
			$clienthash = $result['hash'];
			$email = $result['email'];
			$subject = "Ticket #$ticketid Responded";
			$companyname = $this -> Emails -> GetSetting('companyname');
			$status = ucfirst($status);

			$link = site_url() . '/tickets/viewticket/' . $clienthash . '/' . $ticketid;

			$body = "
					Dear $email,<br><br>
					
					Our staff has replied to your ticket.<br><br> 
					
					Ticket : #$ticketid <br>
					Status : $status<br><br>
					
					To see the response and post additional comments, click on the link below.<br><br>
					
					<a href=\"$link\">$link</a> <br><br>
					
					Note: Do not reply to this email as this email is not monitored.<br><br>
					Regards<br>
					$companyname
					";

			$this -> Emails -> SendMail($email, $subject, $body);
			$this -> view($ticketid);
		}

	}

	function close() {
		$referer = $_SERVER["HTTP_REFERER"];
		$ticketid = $this -> uri -> segment('4');
		$this -> Tickets -> CloseTicket($ticketid);
		redirect($referer);
	}
    function get_csv() {
		$referer = $_SERVER["HTTP_REFERER"];
		$data=array('Ticket,Subject,Customer,Email,Travel Date,Duration,Department,Priority,Status,Created');
		foreach($this -> Tickets -> ListCsv('') as $key=>$val)
		{
		$val_new=str_replace(array(',','\n'),' ',$val);
		$data[]=implode(',',$val_new);
		}
		$data_new=implode("\n",$data);
		file_put_contents('download.csv',$data_new);
		redirect('../download.csv');
		redirect($referer);
	}
	function delete() {
		$referer = $_SERVER["HTTP_REFERER"];
		$ticketid = $this -> uri -> segment('4');
		$this -> Tickets -> DeleteTicket($ticketid);
		redirect($referer);
	}

	function viewattachment() {
		$name = $this -> uri -> segment('4');

		$file = "./attachments/$name";

		if (file_exists($file)) {
			header("Content-Description: File Transfer");
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"$name\"");
			readfile($file);
		}
		else {
			echo "Error downloading file. File not found!" ;
		}

	}

}
