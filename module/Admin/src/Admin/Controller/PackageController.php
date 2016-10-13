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

class PackageController extends AbstractActionController{
	//echo "Mohit"; die;
	public $con = '';
	private $data = array();
	
	public function __construct(){
		//error_reporting(E_ALL);
		$this->db = $this->con=mysqli_connect("10.0.0.35","root","Travel@(4242)","hello42_new");
	}
	
	public function localAction(){
		$c=1;
		$sql = "SELECT * FROM tbllocalpackage";
		$qry = mysqli_query($this->con,$sql);
		while($row = mysqli_fetch_assoc($qry)){
			if($row['Status']==1)
				$status="Active";
			else
				$status="Inactive";
			if($row['cabType']==1)
				$cabType="Economy";
			elseif($row['cabType']==2)
				$cabType="Inactive";
			elseif($row['cabType']==3)
				$cabType="Prime";
			if($row['Sub_Package_Id']==1)
				$packType="Distance";
			elseif($row['Sub_Package_Id']==2)
				$packType="Hour";
			elseif($row['Sub_Package_Id']==3)
				$packType="Distance+Hour";
			elseif($row['Sub_Package_Id']==4)
				$packType="Distance+Waiting";
			$this->data [] = array(
									$c++, 
									$row['Package'],
									$packType,
									$row['Hrs'],
									$row['Km'],
									$row['Price'],
									$cabType,
									$status, 
									date('d M Y',strtotime($row['created_date'])),
									date('d M Y',strtotime($row['ModifiedDate'])),
									/*'<a href="role/role?id='.$row['Role_ID'].'">Manage Role</a>',
									'<a href="package/edit?id='.$row['id'].'">Edit</a> | <a href="package/delete?id='.$row['id'].'" onclick="return confirm('."'Are you sure want to delete this record'".');" >Delete</a>'*/);
		}
		$this->data = json_encode(array('data'=>$this->data));
		return new viewModel(array('data'=>$this->data));
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
			$this->data [] = array(
									$c++, 
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
		$data = array(
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
			'Rec'=>$Rec
		);
				
		return new viewModel(array('data'=>$this->data));
	}
	
	public function updateAction(){
		$recs = '';
		$data = array(
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
