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


class Client_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	function AddClient($data)
	{
		$this -> db -> where('email', $data['email']);
		$result = $this -> db -> get('tblclients');
		if ($this -> db -> affected_rows() > 0)
		{
			// A user with the same email already exists. So return the client id
			$row = $result -> row_array();
			return $row['id'];
		}
		else
		{
			// No such user. So we create it
			$data['hash'] = md5(uniqid());
			$this -> db -> insert('tblclients', $data);
			return $this -> db -> insert_id();
		}
	}

	function GetClientData($clientid)
	{
		$result = $this -> db -> where('id', $clientid) -> get('tblclients');
		return $result -> row_array();
	}

	function GetClientIDFromHash($clienthash)
	{
		$result = $this -> db -> where('hash', $clienthash) -> get('tblclients');
		if ($this -> db -> affected_rows() > 0)
		{
			$row = $result -> row_array();
			return $row['id'];
		}
		else
			return FALSE;

	}
	
	function GetTicketsOfClient($clientid)
	{
		$result = $this->db->where('clientid', $clientid)->get('tbltickets') ;
		return $result->result_array();
		
	}

}
