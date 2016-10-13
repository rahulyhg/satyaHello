<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class CallcentreController extends AbstractActionController{
	
	public $con = '';
	private $data = array();
	
	public function __construct(){
		//error_reporting(E_ALL);
		$this->db = $this->con=mysqli_connect("10.0.0.35","root","Travel@(4242)","hello42_new");
	}
	
	public function indexAction(){
		ini_set("display_errors", 1);
		
		$c=1;
		$sql = "SELECT * FROM login";
		$qry = mysqli_query($this->con,$sql);
		while($row = mysqli_fetch_assoc($qry)){
			if($row['status']=="Active")
				$status="Active";
			else
				$status="Inactive";
			$this->data [] = array(
									$c++, 
									$row['Full_Name'],
									$row['username'],
									$status, 
									date('d M Y',strtotime($row['Created_date'])),
									//date('d M Y',strtotime($row['modified_date'])),
									'<a href="user/reset?id='.$row['id'].'">Reset Password</a>',
									'<a href="user/edit?id='.$row['id'].'">Edit</a> | <a href="user/delete?id='.$row['id'].'" onclick="return confirm('."'Are you sure want to delete this record'".');" >Delete</a>');
		}
		$this->data = json_encode(array('data'=>$this->data));
		return new viewModel(array('data'=>$this->data));
	}
	
	public function addAction(){	
		$StatusList = "<option value=''>Select Status</option>";
		$sql = "SELECT * FROM tblstatus";
		$qry = mysqli_query($this->con, $sql) or die(mysqli_error());
		while($row = mysqli_fetch_object($qry)){
			$StatusList .= "<option value='".$row->id."'>".$row->status."</option>";
		}
		$this->data = array(
			'StatusList'=>$StatusList
		);
		return new viewModel(array('data'=>$this->data));
	}
	
	public function saveAction(){
		$fields = '';
		$values = '';
		$password	=	md5($_REQUEST['password']);
		$data = array(
			'full_name'=>$_REQUEST['full_name'],
			'password'=>$password,
			'username'=>$_REQUEST['username'],
			'email'=>$_REQUEST['email'],
			'role_id'=>$_REQUEST['role_id'],
			'IsActive'=>$_REQUEST['IsActive'],
			'created_date'=>date('Y-m-d H:i:s'),
		);
		foreach($data as $key=>$val){
			$fields .= "`$key`,";
			$values .= "'$val',";
		}
		$fields = substr($fields,0,-1);
		$values = substr($values,0,-1);
		$sql = "INSERT INTO web_admin ($fields) VALUES ($values)";
		$qry = mysqli_query($this->con,$sql);
		header('location: '.site_url.'/admin/user');
		exit();
	}
	
	public function editAction(){
				
		$sql = "SELECT * FROM web_admin WHERE id = '".$_REQUEST['id']."'";
		$qry = mysqli_query($this->con, $sql);
		$Rec = mysqli_fetch_object($qry);
		
		$RoleList = "<option value=''>Select Role</option>";
		$sql_role = "SELECT * FROM tbluserrole WHERE IsActive='1' and Role_ID not in(1,2,3)";
		$qry_role = mysqli_query($this->con, $sql_role) or die(mysqli_error());
		while($row_role = mysqli_fetch_object($qry_role)){
			if($Rec->role_id==$row_role->Role_ID)
			$sel="selected=selected";
			else
			$sel="";
			$RoleList .= "<option value='".$row_role->Role_ID."' $sel>".$row_role->RoleName."</option>";
		}
		
		$StatusList = "<option value=''>Select Status</option>";
		$sql = "SELECT * FROM tblstatus";
		$qry = mysqli_query($this->con, $sql) or die(mysqli_error());
		while($row = mysqli_fetch_object($qry)){
			if($Rec->IsActive==$row->id)
			$sel="selected=selected";
			else
			$sel="";
			$StatusList .= "<option value='".$row->id."' $sel>".$row->status."</option>";
		}
		$this->data = array(
			'StatusList'=>$StatusList,
			'Rec'=>$Rec,
			'RoleList'=>$RoleList
		);
				
		return new viewModel(array('data'=>$this->data));
	}
	
	public function updateAction(){
		$recs = '';
		$data = array(
			'full_name'=>$_REQUEST['full_name'],
			'username'=>$_REQUEST['username'],
			'email'=>$_REQUEST['email'],
			'role_id'=>$_REQUEST['role_id'],
			'IsActive'=>$_REQUEST['IsActive'],
		);
		foreach($data as $key=>$val){
			$recs .= "`$key`='$val',";
		}
		$recs = substr($recs,0,-1);
		$sql = "UPDATE web_admin SET $recs WHERE id = '".$_REQUEST['id']."'";
		$qry = mysqli_query($this->con,$sql);
		header('location: '.site_url.'/admin/user');
		exit();
	}
	
	public function deleteAction(){
		//$sql = "DELETE FROM web_admin WHERE id = '".$_REQUEST['id']."'";
		$sql = "UPDATE web_admin SET IsActive='2' WHERE id = '".$_REQUEST['id']."'";
		$qry = mysqli_query($this->con, $sql);
		header('location: '.site_url.'/admin/user');
		exit();
	}
	
	public function resetAction(){	
		$sql = "SELECT * FROM web_admin WHERE id = '".$_REQUEST['id']."'";
		$qry = mysqli_query($this->con, $sql);
		$Rec = mysqli_fetch_object($qry);
			
		$this->data = array(
			'Rec'=>$Rec
		);
				
		return new viewModel(array('data'=>$this->data));
	}
	
	public function resetpasswordAction(){
		$recs = '';
		$password	=	$_REQUEST['password'];
		$data = array(
			'password'=>$password,
		);
		foreach($data as $key=>$val){
			$recs .= "`$key`='$val',";
		}
		$recs = substr($recs,0,-1);
		$sql = "UPDATE web_admin SET $recs WHERE id = '".$_REQUEST['id']."'";
		$qry = mysqli_query($this->con,$sql);
		header('location: '.site_url.'/admin/user');
		exit();
	}
	
}
