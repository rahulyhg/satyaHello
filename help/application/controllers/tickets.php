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

class Tickets extends CI_Controller
{

	var $data = array();

	function __construct()
	{
		parent::__construct();
		$this -> load -> model('Tickets_model', 'Tickets');
		$this -> load -> model('Client_model', 'Clients');
		$this -> load -> model("Emails");
        $this -> data['staffs'] = $this -> Admin -> ListStaffs();
		

	}

	function index()
	 
	{  
		$this -> load -> template("openticket", $this -> data);
	}

	function viewticket($ticketid = '')
	{

		if ($this -> uri -> segment('4') != '')
		{
			$ticketid = $this -> uri -> segment('4');
		}
		if ($this -> uri -> segment('3') != '')
		{
			$clienthash = $this -> uri -> segment('3');
			$this -> session -> set_userdata('clienthash', $clienthash);
		}
		else
		{
			$clienthash = $this -> session -> userdata('clienthash');
		}

		$this -> data['clienthash'] = $clienthash;
		
		
		if (!isset($ticketid))
			$ticketid = $this -> uri -> segment('4');

		// Make sure the client has access to this ticket.
		$this -> data['ticket'] = $this -> Tickets -> GetTicketDetails($ticketid);



		// Get additional field details for this ticket

		$this -> data['additional'] = $this -> Tickets -> GetAllAdditionalFieldsofTicket($ticketid);

		if ($this -> data['clienthash'] != $this -> data['ticket']['hash'])
		{
			die('Invalid client id provided');
			

		}

		$this -> data['ticketid'] = $ticketid;
		$this -> data['title'] = "View Ticket";

		$this -> data['replies'] = $this -> Tickets -> GetTicketReplies($ticketid);
		$this -> data['not_admin']=1; 

		$this -> load -> template('viewticket', $this -> data);

	}

	function reply()
	{
		$ticketid = $this -> input -> post('ticketid');
		$clienthash = $this -> input -> post('clienthash');
		$this -> form_validation -> set_rules('reply', 'Reply', 'trim|required');
		$reply = $this -> input -> post('reply');
		if ($this -> form_validation -> run() == FALSE)
		{
			$this -> viewticket($ticketid);
		}
		else
		{
			$clientid = $this -> Clients -> GetClientIDFromHash($clienthash);
			$data = array(
				'replier' => 'client',
				'ticketid' => $ticketid,
				'body' => $reply,
				'replierid' => $clientid,
			);

			$status = 'customer reply';
			$this -> Tickets -> AddReply($data, $status);
			$this -> data['success'] = "Ticket reply has been added";

			// Send mail to staffs

			$staffs = $this -> Admin -> ListStaffs();
			$link = site_url() . '/admin/tickets/view/' . $ticketid;
			$subject = "Ticket #$ticketid Replied By Client";
			$companyname = $this -> Emails -> GetSetting('companyname');
			$body = "
		
		Ticket #$ticketid has been responded by the client. You may view the ticket by clicking on the following link <br><br>
		
		<a href=\"$link\">$link</a> <br><br>
		
		Regards<br>
		$companyname
		";

			foreach ($staffs as $staff)
			{
				$this -> Emails -> SendMail($staff['email'], $subject, $body);
			}

			$this -> viewticket($ticketid);
		}
	}
    function assignee(){
	$id=$this->input->post('id');
	$assign=$this->input->post('staff');
	$this->Tickets->updateAsign($id,$assign);	
	redirect('admin/tickets', 'refresh');
	}
	function updatefield()
	{
		$ticketid = $this -> input -> post('ticketid');
		$this -> data['additional'] = $this -> Tickets -> GetAllAdditionalFieldsofTicket($ticketid);

		if (is_array($this -> data['additional']))
			foreach ($this->data['additional'] as $additional)
			{
				// We create these vales as an array
				$name = $additional['uniqid'];

				// Update the value only if the value is posted
				if ($this -> input -> post($name))
					$additional_fields[$name] = $this -> encrypt -> encode($this -> input -> post($name));
			}
			
		$this -> data['ticket'] = $this -> Tickets -> GetTicketDetails($ticketid);
		$additional = $this->data['ticket']['additional'] ;
		$current = json_decode($additional, true) ;
		
		
		if(!is_array($additional_fields))
		{
			// Nothing was posted, so no processing required.
			$status['status'] = 0; 
			echo json_encode($status); 
			die();
		}
		
		// Marge. Update if required
		foreach($additional_fields as $key => $value )
		{
			$current[$key] = $value ;
		}
		
		$json = json_encode($current) ;
		
		$update = array('additional' => $json) ;
		$this->db->where('id', $ticketid)->update('tbltickets', $update);
		
		if($this->db->affected_rows() > 0 )
		{
			$status['status'] = 1; 
			echo json_encode($status);
		}
	}


}
