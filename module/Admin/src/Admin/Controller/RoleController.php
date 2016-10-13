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

class RoleController extends AbstractActionController{
	
	public $con = '';
	private $data = array();
	
	public function __construct(){
		//error_reporting(E_ALL);
		$this->db = $this->con=mysqli_connect("10.0.0.35","root","root","hello42_new");
	}
	
	public function indexAction(){
		$c=1;
		$sql = "SELECT * FROM tbluserrole where Role_ID NOT in(1,2,3)";
		$qry = mysqli_query($this->con,$sql);
		while($row = mysqli_fetch_assoc($qry)){
			if($row['IsActive']==1)
				$status="Active";
			else
				$status="Inactive";
			$sql_role_type = "SELECT name FROM tbl_role_type WHERE id='$row[role_type]'";
			$qry_role_type = mysqli_query($this->con, $sql_role_type);
			$row_role_type = mysqli_fetch_assoc($qry_role_type);
		
			$this->data [] = array(
									$c++, 
									$row_role_type['name'],
									$row['RoleName'],
									$row['RoleDescription'],
									$status, 
									date('d M Y',strtotime($row['created_date'])),
									date('d M Y',strtotime($row['ModifiedDate'])),
									//'<a href="role/role?id='.$row['Role_ID'].'">Manage Role</a>',
									'<a href="role/edit?id='.$row['Role_ID'].'">Edit</a> | <a href="role/delete?id='.$row['Role_ID'].'" onclick="return confirm('."'Are you sure want to delete this record'".');" >Delete</a>');
		}
		$this->data = json_encode(array('data'=>$this->data));
		return new viewModel(array('data'=>$this->data));
	}
	
	public function addAction(){
	
		$RollTypeList = "<option value=''>Select User Type</option>";
		$sqlRollType = "SELECT * FROM tbl_role_type order by name ASC";
		$qryRollType= mysqli_query($this->con, $sqlRollType) or die(mysqli_error());
		while($row1 = mysqli_fetch_object($qryRollType)){
			$RollTypeList .= "<option value='".$row1->id."'>".$row1->name."</option>";
		}
	
		$StatusList = "<option value=''>Select Status</option>";
		$sql = "SELECT * FROM tblstatus";
		$qry = mysqli_query($this->con, $sql) or die(mysqli_error());
		while($row = mysqli_fetch_object($qry)){
			$StatusList .= "<option value='".$row->id."'>".$row->status."</option>";
		}
		$this->data = array(
			'StatusList'=>$StatusList,
			'RollTypeList'=>$RollTypeList
		);
		return new viewModel(array('data'=>$this->data));
	}
	
	public function saveAction(){
		$fields = '';
		$values = '';
		$data = array(
			'role_type'=>$_REQUEST['role_type'],
			'RoleName'=>$_REQUEST['RoleName'],
			'RoleDescription'=>$_REQUEST['RoleDescription'],
			'IsActive'=>$_REQUEST['IsActive'],
			'created_date'=>date('Y-m-d h:i:s'),
		);
		foreach($data as $key=>$val){
			$fields .= "`$key`,";
			$values .= "'$val',";
		}
		$fields = substr($fields,0,-1);
		$values = substr($values,0,-1);
		$sql = "INSERT INTO tbluserrole ($fields) VALUES ($values)";
		$qry = mysqli_query($this->con,$sql);
		header('location: '.site_url.'/admin/role');
		exit();
	}
	
	public function editAction(){
				
		$sql = "SELECT * FROM tbluserrole WHERE Role_ID = '".$_REQUEST['id']."'";
		$qry = mysqli_query($this->con, $sql);
		$Rec = mysqli_fetch_object($qry);
		//echo $status=$Rec->status; die;
		
		$RollTypeList = "<option value=''>Select User Type</option>";
		$sqlRollType = "SELECT * FROM tbl_role_type order by name ASC";
		$qryRollType= mysqli_query($this->con, $sqlRollType) or die(mysqli_error());
		while($row1 = mysqli_fetch_object($qryRollType)){
			if($Rec->role_type==$row1->id)
			$sel1="selected=selected";
			else
			$sel1="";
			$RollTypeList .= "<option value='".$row1->id."' $sel1>".$row1->name."</option>";
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
			'RollTypeList'=>$RollTypeList,
			'Rec'=>$Rec
		);
				
		return new viewModel(array('data'=>$this->data));
	}
	
	public function updateAction(){
		$recs = '';
		$data = array(
			'role_type'=>$_REQUEST['role_type'],
			'RoleName'=>$_REQUEST['RoleName'],
			'RoleDescription'=>$_REQUEST['RoleDescription'],
			'IsActive'=>$_REQUEST['IsActive'],
		);
		foreach($data as $key=>$val){
			$recs .= "`$key`='$val',";
		}
		$recs = substr($recs,0,-1);
		$sql = "UPDATE tbluserrole SET $recs WHERE Role_ID = '".$_REQUEST['id']."'";
		$qry = mysqli_query($this->con,$sql);
		header('location: '.site_url.'/admin/role');
		exit();
	}
	
	public function deleteAction(){
		//$sql = "DELETE FROM tbluserrole WHERE Role_ID = '".$_REQUEST['id']."'";
		$sql = "UPDATE tbluserrole SET IsActive='2' WHERE Role_ID = '".$_REQUEST['id']."'";
		$qry = mysqli_query($this->con, $sql);
		header('location: '.site_url.'/admin/role');
		exit();
	}
	
	public function roleAction(){	
		$sql1 = "SELECT Role_ID,RoleName FROM tbluserrole WHERE Role_ID = '".$_REQUEST['id']."'";
		$qry1 = mysqli_query($this->con, $sql1);
		$Rec1 = mysqli_fetch_object($qry1);
		
	
		$sql = "SELECT id,module_name,submodule_name FROM tbl_admin_module WHERE IsActive=1";
		$qry = mysqli_query($this->con, $sql);
		$record=array();
		while($row = mysqli_fetch_array($qry)){
			$record[]=array("submodule_id"=>$row['id'],"module_name"=>$row['module_name'],"submodule_name"=>$row['submodule_name']);
		}
			
		/*$this->data = array(
			'Rec'=>$record
		);*/
				
		return new viewModel(array('data'=>$record,'Rec1'=>$Rec1));
	}
}
