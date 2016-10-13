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
use Tunneling\Model\Admin;

class UserController extends AbstractActionController{
	
	public $con = '';
	private $data = array();
	
	public function __construct(){
		//error_reporting(E_ALL);
		$this->db = $this->con=mysqli_connect("10.0.0.35","root","root","hello42_new");
	}
	
	public function indexAction(){
		$c=1;
		$sql = "SELECT * FROM web_admin";
		$qry = mysqli_query($this->con,$sql);
		while($row = mysqli_fetch_assoc($qry)){
			if($row['IsActive']==1)
				$status="Active";
			else
				$status="Inactive";
				$sql_role_type = "SELECT name FROM tbl_role_type WHERE id='$row[role_type]'";
				$qry_role_type = mysqli_query($this->con, $sql_role_type);
				$row_role_type = mysqli_fetch_assoc($qry_role_type);


				$sql_role = "SELECT * FROM tbluserrole WHERE Role_ID='$row[role_id]'";
				$qry_role = mysqli_query($this->con, $sql_role);
				$row_role = mysqli_fetch_assoc($qry_role);		

			$this->data [] = array(
									$c++, 
									$row['full_name'],
									//$row['username'],
									$row['email'],
									$row_role_type['name'],
									$row_role['RoleName'],
									$status, 
									date('d M Y',strtotime($row['created_date'])),
									//date('d M Y',strtotime($row['modified_date'])),
									'<a href="user/role?id='.$row['id'].'">Edit Role</a>',
									'<a href="user/reset?id='.$row['id'].'">Reset Password</a>',
									'<a href="user/edit?id='.$row['id'].'">Edit</a> | <a href="user/delete?id='.$row['id'].'" onclick="return confirm('."'Are you sure want to delete this record'".');" >Delete</a>');
		}
		$this->data = json_encode(array('data'=>$this->data));
		// print_r($this->data);die;
		return new viewModel(array('data'=>$this->data));
	}
	
	public function addAction(){
		$RoleList = "<option value=''>Select Role</option>";
		$sql_role = "SELECT * FROM tbluserrole WHERE IsActive='1' and Role_ID not in(1,2,3)";
		$qry_role = mysqli_query($this->con, $sql_role) or die(mysqli_error());
		while($row_role = mysqli_fetch_object($qry_role)){
			$RoleList .= "<option value='".$row_role->Role_ID."'>".$row_role->RoleName."</option>";
		}
		
		
		$StatusList = "<option value=''>Select Status</option>";
		$sql = "SELECT * FROM tblstatus";
		$qry = mysqli_query($this->con, $sql) or die(mysqli_error());
		while($row = mysqli_fetch_object($qry)){
			$StatusList .= "<option value='".$row->id."'>".$row->status."</option>";
		}
		$this->data = array(
			'StatusList'=>$StatusList,
			'RoleList'=>$RoleList
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
			'mobile_no'=>$_REQUEST['mobile_no'],
			'email'=>$_REQUEST['email'],			
			'address'=>$_REQUEST['user_address'],
			'country'=>$_REQUEST['user_country'],
			'state'=>$_REQUEST['user_state'],
			'city'=>$_REQUEST['user_city'],
			'pincode'=>$_REQUEST['pincode'],			
			'role_type'=>$_REQUEST['user_role_type'],
			'role_id'=>$_REQUEST['role_id'],
			'IsActive'=>$_REQUEST['IsActive'],
			'created_date'=>date('Y-m-d H:i:s'),
			
			'manage_jobs'=>$_REQUEST['manage_jobs'],
			'manage_client'=>$_REQUEST['manage_client'],
			'manage_driver'=>$_REQUEST['manage_driver'],
			'manage_driver_groups'=>$_REQUEST['manage_driver_groups'],
			'manage_tracking'=>$_REQUEST['manage_tracking'],
			'manage_complaint'=>$_REQUEST['manage_complaint'],
			'manage_fair'=>$_REQUEST['manage_fair'],
			'manage_reports'=>$_REQUEST['manage_reports'],
			'manage_promotion'=>$_REQUEST['manage_promotion'],			
			'manage_device'=>$_REQUEST['manage_device'],
			'manage_vehicle'=>$_REQUEST['manage_vehicle'],
			'manage_enquiry'=>$_REQUEST['manage_enquiry'],
			'manage_role'=>$_REQUEST['manage_role'],
			'manage_user'=>$_REQUEST['manage_user'],
			'mange_driver_payment'=>$_REQUEST['mange_driver_payment'],			
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
		
		$role_type=$Rec->role_type;
		$user_country=$Rec->country;
		$user_state=$Rec->state;
		$user_city=$Rec->city;
		
		$CountryList = "<option value=''>Select Country</option>";
		$sqlCountryList = "SELECT * FROM tblcountry";
		$qryCountryList= mysqli_query($this->con, $sqlCountryList) or die(mysqli_error());
		while($rowCountry = mysqli_fetch_object($qryCountryList)){
			if($user_country==$rowCountry->ID)
			$sel="selected=selected";
			else
			$sel="";
			$CountryList .= "<option value='".$rowCountry->ID."' $sel>".$rowCountry->CountryName."</option>";
		}
		
		$StateList = "<option value=''>Select State</option>";
		$sqlStateList =  "SELECT * FROM tblstates WHERE country_code='$user_country'";
		$qryStateList= mysqli_query($this->con, $sqlStateList) or die(mysqli_error());
		while($rowState= mysqli_fetch_object($qryStateList)){
			if($user_state==$rowState->id)
			$sel="selected=selected";
			else
			$sel="";
			$StateList .= "<option value='".$rowState->id."' $sel>".$rowState->state."</option>";
		}
		
		$CityList = "<option value=''>Select City</option>";
		$sqlCityList =  "SELECT * FROM tblcity WHERE state='$user_state'";
		$qryCityList= mysqli_query($this->con, $sqlCityList) or die(mysqli_error());
		while($rowCity= mysqli_fetch_object($qryCityList)){
			if($user_city==$rowCity->id)
			$sel="selected=selected";
			else
			$sel="";
			$CityList .= "<option value='".$rowCity->id."' $sel>".$rowCity->name."</option>";
		}
		
		
		
		$RollTypeList = "<option value=''>Select User Type</option>";
		$sqlRollType = "SELECT * FROM tbl_role_type order by name ASC";
		$qryRollType= mysqli_query($this->con, $sqlRollType) or die(mysqli_error());
		while($row1 = mysqli_fetch_object($qryRollType)){
			if($Rec->role_type==$row1->id)
			$sel="selected=selected";
			else
			$sel="";
			$RollTypeList .= "<option value='".$row1->id."' $sel>".$row1->name."</option>";
		}
		
		
		
		$RoleList = "<option value=''>Select Designation</option>";
		$sql_role = "SELECT * FROM tbluserrole WHERE IsActive='1' and role_type='$role_type' and Role_ID not in(1,2,3)";
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
			'RoleList'=>$RoleList,
			'RollTypeList'=>$RollTypeList,
			'CountryList'=>$CountryList,
			'StateList'=>$StateList,
			'CityList'=>$CityList
		);
				
		return new viewModel(array('data'=>$this->data));
	}
	
	public function updateAction(){
		$recs = '';
		$data = array(
			'full_name'=>$_REQUEST['full_name'],
			'username'=>$_REQUEST['username'],
			'mobile_no'=>$_REQUEST['mobile_no'],
			'email'=>$_REQUEST['email'],			
			'address'=>$_REQUEST['user_address'],
			'country'=>$_REQUEST['user_country'],
			'state'=>$_REQUEST['user_state'],
			'city'=>$_REQUEST['user_city'],
			'pincode'=>$_REQUEST['pincode'],			
			'role_type'=>$_REQUEST['user_role_type'],
			'role_id'=>$_REQUEST['role_id'],
			'IsActive'=>$_REQUEST['IsActive'],
			'manage_jobs'=>$_REQUEST['manage_jobs'],
			'manage_client'=>$_REQUEST['manage_client'],
			'manage_driver'=>$_REQUEST['manage_driver'],
			'manage_driver_groups'=>$_REQUEST['manage_driver_groups'],
			'manage_tracking'=>$_REQUEST['manage_tracking'],
			'manage_complaint'=>$_REQUEST['manage_complaint'],
			'manage_fair'=>$_REQUEST['manage_fair'],
			'manage_reports'=>$_REQUEST['manage_reports'],
			'manage_promotion'=>$_REQUEST['manage_promotion'],			
			'manage_device'=>$_REQUEST['manage_device'],
			'manage_vehicle'=>$_REQUEST['manage_vehicle'],
			'manage_enquiry'=>$_REQUEST['manage_enquiry'],
			'manage_role'=>$_REQUEST['manage_role'],
			'manage_user'=>$_REQUEST['manage_user'],
			'mange_driver_payment'=>$_REQUEST['mange_driver_payment']
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
	
		public function paymentupdateAction(){
		//$sql = "DELETE FROM web_admin WHERE id = '".$_REQUEST['id']."'";
		//$sql = "UPDATE web_admin SET IsActive='2' WHERE id = '".$_REQUEST['id']."'";
		//$qry = mysqli_query($this->con, $sql);
		//header('location: '.site_url.'/admin/user');
		//exit();
		
		
	}
	
	public function resetAction(){	
		 

		$getme = $this->params()->fromQuery('getme');
		if($getme ==1){
			echo "<script>
    		alert('Password could not update. Please contact admin');
    		</script>";
		}

		$sql = "SELECT * FROM web_admin WHERE id = '".$_REQUEST['id']."'";
		$qry = mysqli_query($this->con, $sql);
		$Rec = mysqli_fetch_object($qry);
			
		$this->data = array(
			'Rec'=>$Rec
		);
				
		return new viewModel(array('data'=>$this->data,'updfailed'=>$updfailed));
	}
	
	public function resetpasswordAction(){
		$recs = '';
		$password	=	md5($_REQUEST['password']);
		$data = array(
			'password'=>$password,
		);
		foreach($data as $key=>$val){
			$recs .= "`$key`='$val',";
		}
		$recs = substr($recs,0,-1);
		$sql = "UPDATE web_admin SET $recs WHERE id = '".$_REQUEST['id']."'";
		$qry = mysqli_query($this->con,$sql);
		/*if($qry){

			$adminlog = new Admin();

			$resullogout = $adminlog->admin_logout($_REQUEST['id'],1);
			if($resullogout['status'] == 'true'){
				$resullogout = "Your password has been changed please login again to confirm";
			header('location: '.site_url.'/admin/index/index?msglgOt="'.$resullogout.'"');
			}
		}
		else {	
    		header('location: '.site_url.'/admin/user/reset?id='.$_REQUEST['id'].'&getme=1');	
		}*/		
		header('location: '.site_url.'/admin/user');
		exit();
	}
	
	public function roleAction(){	
		$sql = "SELECT * FROM web_admin WHERE id = '".$_REQUEST['id']."'";
		$qry = mysqli_query($this->con, $sql);
		$Rec = mysqli_fetch_object($qry);
		
		$sql1 = "SELECT * FROM tbluserrole WHERE Role_ID = '".$Rec->role_id."'";
		$qry1 = mysqli_query($this->con, $sql1);
		$Rec1 = mysqli_fetch_object($qry1);
			
		$this->data = array(
			'Rec'=>$Rec,
			'Rec1'=>$Rec1
		);
				
		return new viewModel(array('data'=>$this->data));
	}
	
	public function rolemgmtAction(){
		$recs = '';
		$data = array(
			'manage_jobs'=>$_REQUEST['manage_jobs'],
			'manage_client'=>$_REQUEST['manage_client'],
			'manage_driver'=>$_REQUEST['manage_driver'],
			'manage_driver_groups'=>$_REQUEST['manage_driver_groups'],
			'manage_tracking'=>$_REQUEST['manage_tracking'],
			'manage_complaint'=>$_REQUEST['manage_complaint'],
			'manage_fair'=>$_REQUEST['manage_fair'],
			'manage_reports'=>$_REQUEST['manage_reports'],
			'manage_promotion'=>$_REQUEST['manage_promotion'],			
			'manage_device'=>$_REQUEST['manage_device'],
			'manage_vehicle'=>$_REQUEST['manage_vehicle'],
			'manage_enquiry'=>$_REQUEST['manage_enquiry'],
			'manage_role'=>$_REQUEST['manage_role'],
			'manage_user'=>$_REQUEST['manage_user'],
			'mange_driver_payment'=>$_REQUEST['mange_driver_payment']
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
