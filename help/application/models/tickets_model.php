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

class Tickets_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this -> load -> model('client_model', 'Clients');
	}

	function ListTickets($status = '',$admin='')
	{
		$admin=$this -> session -> userdata('department');
		
		if ($status)
		{
			//$this -> db -> where("status", $status);
		}

		$select = array(
			'tbltickets.id',
			'LEFT(subject,30) AS subject',
			'name',
			'tblclients.email AS email',
			'travel_date',
			'duration',
			'tbltickets.department AS department',
			'priority',
			'status',
			'updated_by',
			'modified',
			'due_date',
			'mobileno',
			'clientid',
			
			'created',
			'deptname',
			'tblstaffs.firstname AS firstname',
			'tblstaffs.lastname AS lastname',
			
		);
   
   
		
		$this -> db -> select($select);

		$this -> db -> order_by('tbltickets.id', 'desc');
		$this -> db -> join('tbldepartments', 'tbldepartments.deptid = tbltickets.department');
		$this -> db -> join('tblclients', 'tbltickets.clientid = tblclients.id');
		$this -> db -> join('tblstaffs', 'tbltickets.created_by = tblstaffs.id');
		if($this -> session -> userdata('admin') == 2)
   {
		$this->db->where('tbltickets.department',$admin);
   }
   	if($this -> session -> userdata('admin') == 0)
   {
		$this->db->where('tbltickets.updated_by',$this->session->userdata('staffid'));
		$this->db->or_where('tbltickets.created_by',$this->session->userdata('staffid'));
   }
		$result = $this -> db -> get('tbltickets');
		$data=$result -> result_array();
	foreach($data as $key=>$val)
		{
	$this->db->select(array('firstname','lastname'));
	$this->db->where("id",$data[$key]['updated_by']);
	$result2 = $this -> db -> get('tblstaffs');
	$result3=$result2->result_array();
	$updated_by="";
	if(isset($result3[0]))
	{
	$updated_by=$result3[0]['firstname']." ".$result3[0]['lastname'];
	}
$data[$key]['updated_by']=$updated_by;
	//echo $updated_by;
		}
		//print_r($data);
		return $data;

	}
	function ListCsv($status = '')
	{
		if ($status)
		{
			$this -> db -> where("status", $status);
		}

		$select = array(
			'tbltickets.id',
			'LEFT(subject,30) AS subject',
			'name',
			'email',
			'travel_date',
			'duration',
		    'deptname',
			'priority',
			'status',
			'created',
			
			
			
			
			
			
			
			
		);
		$this -> db -> select($select);

		$this -> db -> order_by('tbltickets.id', 'desc');
		$this -> db -> join('tbldepartments', 'tbldepartments.deptid = tbltickets.department');
		$this -> db -> join('tblclients', 'tbltickets.clientid = tblclients.id');
		$result = $this -> db -> get('tbltickets');
		return $result -> result_array();

	}
	

	function GetTicketDetails($ticketid)
	{
		$this -> db -> join('tbldepartments', 'tbldepartments.deptid = tbltickets.department');
		$this -> db -> join('tblclients', 'tbltickets.clientid = tblclients.id');
		$result = $this -> db -> where('tbltickets.id', $ticketid) -> get("tbltickets");
		$array = $result -> row_array();

		$array['body'] = $this -> MakeClickableURL($array['body']);
		return $array;
	}
	function GetLogs($ticketid)
	{
		$this -> db -> join('tblstaffs', 'tblstaffs.id = tblticketlogs.related');
		//$this -> db -> join('tblclients', 'tbltickets.clientid = tblclients.id');
		$result = $this -> db -> where('tblticketlogs.ticketid', $ticketid) -> get("tblticketlogs");
		$array = $result -> result_array();

		//$array['body'] = $this -> MakeClickableURL($array['body']);
		return $array;
	}

	function GetTicketReplies($ticketid)
	{
		$result = $this -> db -> order_by('id', 'asc') -> where('ticketid', $ticketid) -> get('tblticketreplies');
		$rows = $result -> result_array();

		for ($i = 0; $i < sizeof($rows); $i++)
		{
			$body = $rows[$i]['body'] ;
			
			
			$body = $this->MakeClickableURL($body) ;
			$rows[$i]['body'] = $body ;
				
			$replierid = $rows[$i]['replierid'];

			if ($rows[$i]['replier'] == 'client')
			{
				$row = $this -> Clients -> GetClientData($replierid);
				$rows[$i]['email'] = $row['email'];
			}
			else
			{
				$row = $this -> Admin -> GetStaffDetails($replierid);
				$rows[$i]['firstname'] = $row['firstname'];
				$rows[$i]['lastname'] = $row['lastname'];
			}
		}

		return $rows;
	}
    
	function GetStatuses()
	{
		$result = $this -> db -> get('tblstatus');
		return $result -> result_array();
	}
    function updateAsign($id,$assign){
	$this -> db -> where('id', $id) -> update('tbltickets',array('updated_by'=>$assign,'modified'=>date('Y-m-d h:i:s'),'status'=>'reassign'));	
	$this->db->insert('tblticketlogs',array('ticketid'=>$id,'status'=>'reassign','related'=>$assign,'time'=>date("Y-m-d h:i:s")));
		
	}
	function AddReply($data, $status)
	{
		$this -> db -> where('ticketid', $data['ticketid']);
		$this -> db -> insert('tblticketreplies', $data);
		$this -> db -> where('id', $data['ticketid']) -> update('tbltickets', array('status' => $status,'updated_by'=>$data['replierid'],'modified'=>date('Y-m-d h:i:s')));
		$this->db->insert('tblticketlogs',array('ticketid'=>$data['ticketid'],'status'=>$status,'related'=>$data['replierid'],'time'=>date("Y-m-d h:i:s")));
	}

	function GetAdditionaFields($ticketid)
	{
		$result = $this -> db -> where('id', $ticketid) -> get('tbltickets');
		$row = $result -> row_array();

		$additional = json_decode($row['additional'], true);

		if (is_array($additional))
			foreach ($additional as $key => $value)
			{
				$result = $this -> db -> where('uniqid', $key) -> get('tblfields');
				$row = $result -> row_array();

				$array[] = array(
					'name' => $row['name'],
					'value' => $this -> encrypt -> decode($value)
				);

			}
		if (is_array($additional))
			return @$array;

	}

	function GetAllAdditionalFieldsofTicket($ticketid)
	{
		$result = $this -> db -> where('id', $ticketid) -> get('tbltickets');
		$row = $result -> row_array();
		$department = $row['department'];

		$fields = $this -> Admin -> ListAllFields($department);

		return $fields;

	}

	function CloseTicket($ticketid)
	{
		$data = array(
			'status' => 'closed',
			'additional' => ''
		);
		$this -> db -> where('id', $ticketid) -> update('tbltickets', $data);
	}

	function DeleteTicket($ticketid)
	{
		$this -> db -> where('id', $ticketid) -> delete('tbltickets');
		$this -> db -> where('ticketid', $ticketid) -> delete('tblticketreplies');
	}

	function MakeClickableURL($text)
	{
		$text = preg_replace('/(((f|ht){1}tp(s?):\/\/)[-a-zA-Z0-9@:%_\+.~#?&;\/\/=]+)/', '<a href="\\1" target="_blank">\\1</a>', $text);
		$text = preg_replace("/(^|[ \\n\\r\\t])(www\.([a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+)(\/[^\/ \\n\\r]*)*)/", '\\1<a href="http://\\2" target="_blank">\\2</a>', $text);
		$text = preg_replace("/(^|[ \\n\\r\\t])([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4})/", '\\1<a href="mailto:\\2" target="_blank">\\2</a>', $text);
		return $text;
	}

}
