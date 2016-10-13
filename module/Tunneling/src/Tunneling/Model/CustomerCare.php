<?php
namespace Tunneling\Model;
use Zend\Mail;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Session\Container;
use Tunneling\Model\Menu;
use Tunneling\Model\DatabaseConfig;

class CustomerCare{	
	protected $con;
	private $data = array();
	private $row = array();	
	public function __construct(){		
		date_default_timezone_set("Asia/Kolkata");
		$db=new DatabaseConfig();
		$this->con=$db->getDatabaseConfig();
		$key = '';
		//$this->con=mysqli_connect("10.0.0.35","root","root","hello42_new");
		foreach($_POST as $s=>$v){
			$_POST[$s]=$this->removeSqlInjection($v);
		}
		if(isset($_REQUEST['key'])){
			$key=$_REQUEST['key'];
		}
		$result=mysqli_query($this->con,"CALL sp_w_auth('$key')")or die(mysqli_error($this->con));
		$data=mysqli_fetch_array($result);
		if($data['check_auth']==0){
		}
		mysqli_free_result($result);   
		mysqli_next_result($this->con);		
	}
	public function removeSqlInjection($data){
		 $arr = array("%","'","=","*");
		 $res = str_replace($arr,"/",$data);
		 return $res;
	}

public function munesh()
	{

	echo $sql='SELECT * FROM `login`'; die;
	$result = mysqli_query($this->$con,$sql);
	//return $result;
	return array("status"=>$status,"data"=>$result);

	}





	public function Bookingshowcaller()
	{
		//CALL sp_booking_show_by_caller('".$mob."')
		$mob=$_REQUEST['mob'];
		$query="SELECT tbldriver.firstname,tbldriver.VehicleRegistrationNo,tblcabbooking.ID,booking_reference,EmailId,UserName,MobileNo,PickupArea,DropArea,
PickupDate,BookingDate,CarType,PickupAddress,DestinationAddress,pickup ,tblcabbooking.*,  tblcabstatus.`status`  as `status1`  FROM `tblcabbooking` 
JOIN tblcabstatus ON tblcabbooking.`status`=tblcabstatus.status_id LEFT JOIN tbldriver ON tblcabbooking.pickup=tbldriver.uid WHERE 
tblcabstatus.type='cab' and tblcabbooking.MobileNo='$mob' order by ID desc limit 10;";
		$fetch=mysqli_query($this->con,$query);
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
			return array("data"=>$record);
	}
	public function CustomerAll()
	{
        //CALL sp_customerall()		
		$query="select * from tblcabbooking;";
		$fetch=mysqli_query($this->con,$query);
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
			return array("data"=>$record);
	}
	public function CabbookingShow()
	{	
         //CALL Sp_cabbookingshow()	
		$query="SELECT tblcabbooking.ID,booking_reference,EmailId,MobileNo,PickupArea,DropArea,PickupDate,BookingDate,CarType,PickupAddress,DestinationAddress, Concat(UserName,' (',count(*),')') as Name,tblcabstatus.`status` as `status`,tblcabstatus.`status` as `newstatus` FROM `tblcabbooking` JOIN tblcabstatus ON tblcabstatus.status_id=tblcabbooking.`status` WHERE tblcabstatus.`type`='cab' Group by MobileNo Order By ID desc limit 10";
		$fetch=mysqli_query($this->con,$query);
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
		return array("data"=>$record);			
	}
	public function customercaretext(){		
		$query="CALL sp_customer_care_text()";
		$fetch=mysqli_query($this->con,$query);
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
			return array("data"=>$record);
	}
	public function ShowbyBookref(){	
		$booking_ref=$_REQUEST['booking_ref'];
		//
		//$query="CALL sp_show_caller_by_bookingref('".$booking_ref."')";
		$query="SELECT tblbookingbill.CabName,CONCAT(tbldriver.FirstName,' ',tbldriver.LastName,' (',tbldriver.ContactNo,')') as driver_name,CONCAT(tbldriver.DrivingLicenceNo,' ',tblcablistmgmt.name) as driver_details,cancellation_price,waitingCharge,tripCharge,total_tax_price,totalBill,is_paid,paid_at,currency,invoice_number,payment_type,fees,total_price,total_tax_price,duration_rate,starting_rate,base_price,tax_price,starting_charge,distance_charge,duration_charge,minimum_price,tblbookingcharges.minimumCharge,tblbookingcharges.minimum_distance,tblbookingcharges.distance_rate,tblcabbooking.*
 FROM tblcabbooking JOIN tbldriver ON tblcabbooking.pickup=tbldriver.UID JOIN tblcablistmgmt ON tblcablistmgmt.id=tbldriver.vehicleId 
JOiN tblbookingbill ON tblbookingbill.id=tblcabbooking.CarType 
LEFT JOIN tblbookingcharges ON tblcabbooking.id=tblbookingcharges.bookingid 
WHERE tblcabbooking.booking_reference='$booking_ref' order by ID desc limit 1";
		$fetch=mysqli_query($this->con,$query);
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;			
			
		}
			return array("data"=>$record);
	}
	public function bookingbyfilter(){	       //***Not Tested***
		$driver_name=$_REQUEST['driver_name'];
		$partner_i=$_REQUEST['partner_i'];
		$book_ref=$_REQUEST['book_ref'];
		$book_type=$_REQUEST['book_type'];
		$vehicle_no=$_REQUEST['vehicle_no'];
		$pickup_since=$_REQUEST['pickup_since'];
		$pickup_to=$_REQUEST['pickup_to'];
		$client_first_name=$_REQUEST['client_first_name'];
		$client_last_name=$_REQUEST['client_last_name'];
		$client_mob=$_REQUEST['client_mob'];
		$client_email=$_REQUEST['client_email'];
		$driver_first=$_REQUEST['driver_first'];
		$driver_last=$_REQUEST['driver_last'];
		$driver_ids=$_REQUEST['driver_ids'];
		$driver_ref=$_REQUEST['driver_ref'];
		$driver_email=$_REQUEST['driver_email'];
		$driver_mob=$_REQUEST['driver_mob'];		
		$query="CALL sp_booking_filter('".$driver_name."','".$partner_i."','".$book_ref."','".$book_type."','".$vehicle_no."','".$pickup_since."',
		'".$pickup_to."','".$client_first_name."','".$client_last_name."','".$client_mob."','".$client_email."','".$driver_first."','".$driver_last."',
		'".$driver_ids."','".$driver_ref."','".$driver_email."','".$driver_mob."')";
		$fetch=mysqli_query($this->con,$query);
		$record=array();
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
			return array("data"=>$record);
	}
	
	public function DriverTracking()
	{	
	
		/*$query="CALL sp_driver_tracking_new()";
		$fetch=mysqli_query($this->con,$query);
		$num_rows = mysqli_num_rows($fetch);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
		$record[]=$row;
		}
		return array("status"=>$status,"data"=>$record);
	
		while($row=mysqli_fetch_object($fetch))
		{	
			$record[]=$row;			
			
		}
			return array("data"=>$record);*/
	
		$DriverIds = array();
		$NotMovingDriverIds = array();
		
		$OnDuty = 0;
		$Free = 0;
		$OnCall = 0;
		$Accepted = 0;
		$Located = 0;
		$Reported = 0;
		$Hired = 0;
		$Logout = 0;
		$Total = 0;
		$NotMoving = 0;

		$sql = "SELECT 
					CONCAT(tbluserinfo.FirstName,' ',tbluserinfo.LastName) as DriverName, 
					tbluser.UserNo AS MobileNumber,
					tblcabmaster.CabName,
					tblcabmaster.CabRegistrationNumber,
					tblcabmaster.CabManufacturer,
					tblcabmaster.CabModel,
					tblcabtype.CabType,
					tbluser.id, 
					tbluser.LoginName,
					tbluser.Latitude,
					tbluser.Longtitude1,
					tbldriver.`status` as driverStatus ,
					tbluser.loginStatus,
					(SELECT tblcabbooking.`Status` FROM tblcabbooking WHERE tblcabbooking.pickup = tbluser.ID ORDER BY tblcabbooking.ID DESC LIMIT 1) as cabStatus,
					tblcabmaster.CabName as vehicleName 
				FROM tbluser
				INNER JOIN tbluserinfo ON tbluser.ID = tbluserinfo.UID
				INNER JOIN tbldriver ON tbluser.ID = tbldriver.UID
				INNER JOIN tblcabmaster ON tbldriver.vehicleId = tblcabmaster.CabId
				INNER JOIN tblignitiontype ON tblcabmaster.CabIgnitionTypeId = tblignitiontype.id
				INNER JOIN tblcabtype ON tblcabmaster.CabType = tblcabtype.Id
				WHERE tbluser.UserType = 3 
				AND tbluser.isVerified = 1 
				AND tbluser.Latitude != '' 
				AND tbluser.Longtitude1 != ''";
		$query=mysqli_query($this->con,$sql);
		$data=array();
		while($row=mysqli_fetch_assoc($query)){
			if($row['loginStatus'] == 1){
				$OnDuty++;
			}
			if($row['driverStatus'] == 0 and $row['loginStatus'] == 1){
				$Free++;
				$DriverIds[] = $row['id'];
			}
			if($row['driverStatus']== 1 and $row['loginStatus'] == 1 and $row['cabStatus'] == 2){
				$OnCall++;
			}
			if($row['driverStatus']== 1 and $row['loginStatus'] == 1 and $row['cabStatus'] == 3){
				$Accepted++;
			}
			if($row['driverStatus']== 1 and $row['loginStatus'] == 1 and $row['cabStatus'] == 4){
				$Located++;
			}
			if($row['driverStatus']== 1 and $row['loginStatus'] == 1 and $row['cabStatus'] == 5){
				$Reported++;
			}
			if($row['driverStatus']== 1 and $row['loginStatus'] == 1 and $row['cabStatus'] == 7){
				$Hired++;
			}
			if($row['loginStatus'] == 0){
				$Logout++;
			}
			$Total++;
		}
		
		foreach($DriverIds as $val){
			$sql = "select * from tbldriverlocation where user_id = $val order by id desc limit 1";
			$qry = mysqli_query($this->con, $sql);
			$row = mysqli_fetch_assoc($qry);
			if($row['WaitingTime'] > 5){
				$NotMoving++;
				$NotMovingDriverIds[] = $row['user_id'];
			}
		}
		
		$data['OnDuty'] = $OnDuty;
		$data['available_driver'] = $Free;//
		$data['on_call'] = $OnCall; //
		$data['accepted'] = $Accepted; //
		$data['Located'] = $Located;
		$data['reported'] = $Reported; //
		$data['hired'] = $Hired; //
		$data['NotMoving'] = $NotMoving;
		$data['NotMovingDriverIds'] = implode(',',$NotMovingDriverIds);
		$data['log_out'] = $Logout; //
		$data['total_driver'] = $Total; //
		$data['free_moving'] = $Total; //
		$data['logout_moving'] = $Total; //
		$record[]=$data;
		return array('data'=>$record);
			
	}
	public function DriverTrackingList()
	{	
		//CALL sp_driver_tracking_list()
		$query="SELECT DISTINCT(tbldriver.ID),
		 tbldriver.uid,
		 tblcabbooking.booking_reference,
		 tblcabbooking.DropArea,
		 tblcabmaster.CabRegistrationNumber AS vehicleNumber,
		 CONCAT(tbldriver.FirstName,' ',tbldriver.LastName) as name,
		 tbldriver.ContactNo,
		 tbldriver.`status`,
		 tbluser.loginStatus,
		 tblcabbooking.`Status` 
FROM tbldriver 
JOIN tblcabmaster ON tbldriver.vehicleId = tblcabmaster.CabId
JOIN tbluser ON tbldriver.UID=tbluser.ID 
LEFT join tblcabbooking ON tbldriver.UID=tblcabbooking.pickup 
WHERE tbluser.isVerified = 1
GROUP BY tbldriver.UID 
ORDER BY tbldriver.ID desc";
		$fetch=mysqli_query($this->con,$query);
		$record=array();		
		while($row=mysqli_fetch_object($fetch))
		{	
			$record[]=$row;			
			
		}
			return array("data"=>$record);
	}
	public function Showendriverlat()
	{	
	    //CALL Sp_showendriverlat_new()
		$BookingNo= $_REQUEST['BookingNo'];
		$CallerID = $_REQUEST['CallerID'];
		$ClientName=$_REQUEST['ClientName'];
		$DriverName=$_REQUEST['DriverName'];
		
		if($BookingNo!="None" )
			$BookingNoCondition = " and tbc.Booking_reference='$BookingNo'";
		else
			$BookingNoCondition = "";
		
		if($CallerID!="None")
			$CallerIDCondition = "and tbc.MobileNo='$CallerID'";
		else
			$CallerIDCondition = "";
		
		if($ClientName!= "None")
            $ClientNameCondition = "and tui.FirstName='$ClientName'";
		else
			$ClientNameCondition = "";
		
		if($DriverName!= "None")
            $DriverNameCondition = "and td.FirstName='$DriverName'";
		else
			$DriverNameCondition = "";
			
			
		$query="select tbc.pickup,tui.FirstName from tblcabbooking tbc inner join tbluserinfo tui on tbc.ClientID=tui.UID where tbc.ID!='' $BookingNoCondition $CallerIDCondition $ClientNameCondition order by tbc.id desc limit 1";
		$fetch=mysqli_query($this->con,$query);
		$data=array();
	
		$row=mysqli_fetch_object($fetch);
		$PickUp=$row->pickup;
		   if(intval($PickUp)==0)
		   {
			   $query="select tbc.MobileNo,tui.FirstName,tbc.PickupLatitude as Latitude, tbc.PickupLongtitude as Longtitude1 from tblcabbooking tbc inner join tbluserinfo tui on tbc.ClientID=tui.UID
				where tbc.ID!='' $BookingNoCondition $CallerIDCondition $ClientNameCondition order by tbc.id desc limit 1"; //die;
				$fetch1=mysqli_query($this->con,$query);		
				$row1=mysqli_fetch_object($fetch1);
				$data['pickup']=0;
				$data['clientName']=$row1->FirstName;
				$data['clientMobileNo']=$row1->MobileNo;
				$data['clientLat']=$row1->Latitude;
				$data['clientLong']=$row1->Longtitude1;
				$data['driverName']="";
				$data['driverMobileNo']="";
				$data['driverLat']="";
				$data['driverLong']="";
		   }
		   else
		   {
				$query1="select tbc.MobileNo,tui.FirstName,tbc.PickupLatitude as Latitude, tbc.PickupLongtitude as Longtitude1 from tblcabbooking tbc inner join tbluserinfo tui on tbc.ClientID=tui.UID
				where tbc.ID!='' $BookingNoCondition $CallerIDCondition $ClientNameCondition order by tbc.id desc limit 1"; 
				$fetch1=mysqli_query($this->con,$query1);
				$row1=mysqli_fetch_object($fetch1);
				$data['pickup']=1;
				$data['clientName']=$row1->FirstName;
				$data['clientMobileNo']=$row1->MobileNo;
				$data['clientLat']=$row1->Latitude;
				$data['clientLong']=$row1->Longtitude1;
				
			   $query="select td.ContactNo as MobileNo, td.FirstName , tdl.lat as Latitude, tdl.longi as Longtitude1 from tbldriver td inner join tbldriverlocation tdl on tdl.user_id=td.UID
				where tdl.id!='' and tdl.user_id='$PickUp' order by tdl.id desc limit 1";
				$fetch2=mysqli_query($this->con,$query);
				$row2=mysqli_fetch_object($fetch2);
				$data['driverName']=$row2->FirstName;
				$data['driverMobileNo']=$row2->MobileNo;
				$data['driverLat']=$row2->Latitude;
				$data['driverLong']=$row2->Longtitude1;
		   }
				$record[]=$data;				
				return array("data"=>$record);
	}
	
	public function BookingReshedule()
	{	//callerid;OrderNo;CallerName;ReschuduleDate;ReschuduleTime;Paddress;plat;plong;Daddress;Reason;Remark;dlat;dlong;ReasonText;RemarkText
		//$Value="9891735121;naveenKumar;15:07/2015;16:07/2015;HC15075805;Picktext;Droptext;Resontext;null or text;Plat;plong;dlat;dlong;resontext ;reason remarks";
		$Value = $_REQUEST['Value'];
		$val	=	explode(';',$Value);
	    //CALL sp_booking_reshedule()
		$CallerId= $val[0];
		$OrderNo = $val[1];
		$CallerName = $val[2];	
		$ReschuduleDate = $val[3];
		$ReschuduleTime= $val[4];
		$Paddress = $val[5];
		$plat= $val[6];
		$plong = $val[7];
		$Daddress= $val[8];
		$Reason = $val[9];
		$Remark = $val[10];	
		$dlat = $val[11];
		$dlong= $val[12];
		$ReasonText = $val[13];
		$RemarkText = $val[14];
		///// using get booking detail for reshedule history
		//$data=array();
		$data = $this->GetBookingDetail($OrderNo);		// order is making as booking reference  
		$id=$data['id'];
		$BookingType=$data['BookingType'];
		$Pickup=$data['Pickup'];
		$old_cabstatus=$data['cabstatus'];
		$old_BookingDate=$data['BookingDate'];
		$old_PickupDate=$data['PickupDate'];
		$old_PickupTime=$data['PickupTime'];
		//$new_bookingDate= NOW();
		$new_PickupDate=$ReschuduleDate;
		$new_PickupTime=$ReschuduleTime;
		//$new_cabstatus=$ReschuduleTime;
		
		$qry1 ="insert into  tbl_booking_resec_reassign (booking_id,booking_type,old_driver_id,old_pick_time,old_pick_date,new_pick_time,new_pick_date,old_booking_date,new_booking_date)
  values ('$id','$BookingType' ,'$Pickup','$old_PickupTime','$old_PickupDate' ,'$new_PickupTime','$new_PickupDate','$old_BookingDate' ,NOW())";
     $fetch2=mysqli_query($this->con,$qry1);
	
	 
		//select id,BookingType,Pickup,status as cabstatus,BookingDate,PickupDate,PickupTime from tblcabbooking where booking_reference='$booking_ref'
		
		$qry ="insert into  tblagent_work_history (AgentID,CallerID,BookingID,Actiontype,tblagent_work_history.Date,ActionDesc)
  values ((select CSR_ID from tblcabbooking where booking_reference='$OrderNo'),'$CallerID' ,'$OrderNo','Reschedule',Now(),'Booking Reschedule done by Call Center agent' )";
     $fetch1=mysqli_query($this->con,$qry);
	 
	 
		
		 $query="update  tblcabbooking set UserName='$CallerName', PickupDate='$ReschuduleDate', PickupTime='$ReschuduleTime', PickupArea='$Paddress', PickupLatitude='$plat',
 PickupLongtitude='$plong', DropArea='$Daddress', DestinationLatitude='$dlat', DestinationLongtitude='$dlong', Remark='$RemarkText', StatusC =55, Reason='$ReasonText' 
 where booking_reference='$OrderNo'";
		$fetch=mysqli_query($this->con,$query);
		if($fetch==1)
			$status="true";
		else
			$status="false";
		
		$query1="select booking_reference as booking_id from tblcabbooking where booking_reference='$OrderNo'";
		$fetch1=mysqli_query($this->con,$query1);
		$record=array();		
		while($row=mysqli_fetch_object($fetch1)){	
			$record[]=$row;			
		}
		
		$this->LogStackTrackerData($id,$plat,$plong);
		
		return array("status"=>$status,"record"=>$record);
	}
	
	///// API used for Web,Android Call center Booking Stack Logs Tracker Starts Here /////////////
	
	public function LogStackTrackerData($booking_id,$pickuplatlat,$pickuplatlng){
			
	$sql1="INSERT INTO tblbookinglogs(bookingid,`status`,message,`time`) VALUES('$booking_id',1,'Requesting car',NOW())";
	mysqli_query($this->con,$sql1) or die(mysqli_error($this->con));
	
	$sql2="INSERT INTO `booking_stack`(`booking_id`,`lat`,`long`,`status`) VALUES('$booking_id','$pickuplatlat','$pickuplatlng','')";
	mysqli_query($this->con,$sql2) or die(mysqli_error($this->con));

	$sql3="INSERT INTO `tblbookingtracker` (`BookingID`,`Latitutude`,`Logitude`,`Date_Time`,`CabStatus`) 
	VALUES('$booking_id','$pickuplatlat','$pickuplatlng',NOW(),'1')";
	mysqli_query($this->con,$sql3) or die(mysqli_error($this->con));
		
		return true;
	}
	
	///// API used for Web,Android Call center Booking Stack Logs Tracker Ends Here /////////////
	
	/// Function to cancel the booking from admin Starts Here By Mohit Jain //////
	
	public function CancelCabbkngfrmbackend()
	{
			$admin = new Container('admin');
			$adminid = $admin->offsetGet('id'); 
			
			$agent_id=$_POST['agent_id'];
			
			$bookinid = $_POST['id'];
			$booksql = "select Status,MobileNo,booking_reference from tblcabbooking where ID=$bookinid";
			$mobres = $this->con->query($booksql)->fetch_object(); 


			if(($mobres->Status<=5) && !in_array($mobres->Status, array(6,7,8,9,10,11,12,13,14,15))){
		
			$ReasonText = $_POST['reason'];
			$RemarkText = "Cancelled by admin";

			$_REQUEST['Value'] = $mobres->MobileNo.";".$mobres->booking_reference.";".$ReasonText.";".$RemarkText.";16;".$agent_id;
				
			$prevcabstatus = $mobres->Status;

			$res = $this->CancelCabbooking($_REQUEST['Value']);
			$res['bokid'] = $bookinid;
			// $res['status'] = "true";
		    $res['text'] = $this->con->query("select status from tblcabstatus where status_id='16'")->fetch_assoc()['status'];
		 // $result = $this->BookingReshedule($_REQUEST['Value']);
		if($res['status'] == true){
				
			$_REQUEST['booking_id'] = $bookinid.':cancel:'.$prevcabstatus;
			$sendsms = new Menu();
			$smss =	$sendsms->send_sms($_REQUEST['booking_id']);
			$response = array("status"=>1,"message"=>"Bookiing Cancelled Successfully","bokid"=>$bookinid,"text"=>$res['text']);
		}
		else $response = array("status"=>0,"message"=>"booking con not be cancelled beacuse of some internal error");
	}
	else {

		if(in_array($mobres->Status, array(6,7,8)))
		$response = array("status"=>0,"message"=>"Booking trip has been started ,So can not be cancelled");
		else if($mobres->Status ==11)
		$response = array("status"=>0,"message"=>"Booking has been paid , So can not be cancelled .");
		else
		$response = array("status"=>0,"message"=>"This Booking can not be cancelled .");
		
	} 
		
			header("ContentType: application/json");
			print_r(json_encode($response));
				die;
	}	

	/// Function to cancel the booking from admin Ends Here By Mohit Jain //////
	
		public function chkprevcabstatus($bookinid='')
		{
			 $status = $this->con->query("select Status from tblcabbooking where ID=$bookinid")->fetch_assoc()['Status'];
			 
			 return $status;
		}

	/// Function to cancel the booking from admin Starts Here By Mohit Jain //////
	
	public function CancelCabbooking()
	{	
	    // string value = "CallerId;bookref;ReasonText;RemarkText";
		// $Value="9891735121;naveenKumar;reason text;reason remarks;HC15075805";
		

		$Value = $_REQUEST['Value'];
		$val	=	explode(';',$Value);
		// return 	$val;
	    //CALL sp_cancelcabbooking()
		$CallerID= $val[0];
		$bookref= $val[1];
		$ReasonText = $val[2];
		$RemarkText = $val[3];
		$agent_id=$val[5];

		$bckstatus = ($val[4] == 16)?$val[4]:17;
		
		if($agent_id!=""){	
		$AgentId=$agent_id;
		}
		else{
		$sql="select CSR_ID from tblcabbooking where booking_reference='$bookref'";
		$result1=mysqli_query($this->con,$sql);
		$res=mysql_fetch_array($result1);
		$AgentId=$res['CSR_ID'];
		}
				
		$qry ="insert into  tblagent_work_history (AgentID,CallerID,BookingID,Actiontype,tblagent_work_history.Date,ActionDesc) values ('$AgentId','$CallerID' ,'$bookref', 'Cancellation',Now(),'$ReasonText')";
		$fetch1=mysqli_query($this->con,$qry);
		
		 $query="update tblcabbooking SET tblcabbooking.`Status`=$bckstatus ,StatusC =53,Reason ='$ReasonText',Remark ='$RemarkText' where booking_reference='$bookref' and MobileNo='$CallerID' ";
		 $fetch=mysqli_query($this->con,$query);
		if($fetch==1)
			$status="true";
		else
			$status="false";
		
		$query1="Select ID as booking_id,pickup from tblcabbooking where booking_reference='$bookref'";
		$fetch2=$this->con->query($query1)->fetch_object();
		//$num_rows = mysqli_num_rows($fetch2);
		//if($num_rows>0)
		//	$status="true";
		//else
		//	$status="false";

		$record=array("booking_id"=>$fetch2->booking_id,"DriverId"=>$fetch2->pickup);		
		// while($row=mysqli_fetch_object($fetch2)){	
			// $record[]=$row;
		// }
		$this->con->query("Update tbldriver set status=0 where uid='".$fetch2->pickup."'");
		
			return array("status"=>$status,"data"=>$record);
			
			// return $_REQUEST['Value'];
	}
	
	/// Function to Cancel the booking from admin Ends Here By Mohit Jain //////
	
	public function DisposeCabbooking()
	{	
	    //string value = "CallerId;bookref;ReasonText;RemarkText";
		//$Value="9891735121;naveenKumar;reason text;reason remarks;HC15075805";
		$Value = $_REQUEST['Value'];
		$val	=	explode(';',$Value);
	    //CALL sp_Disposecabbooking()
		$CallerID= $val[0];
		$bookref= $val[1];
		$ReasonText = $val[2];
		$RemarkText = $val[3];
				
		 $qry ="insert into  tblagent_work_history (AgentID,CallerID,BookingID,Actiontype,tblagent_work_history.Date,ActionDesc) 
values ((select CSR_ID from tblcabbooking where booking_reference='$bookref'),'$CallerID' ,'$bookref','Dispose Booking',Now(),'Booking Dispose done by Call Center agent ' )";
     $fetch1=mysqli_query($this->con,$qry);
		
		$query="update tblcabbooking SET tblcabbooking.`Status`=16 ,StatusC =53,Reason ='$ReasonText',Remark ='$RemarkText' where booking_reference='$bookref' and MobileNo='$CallerID'"; 
		$fetch=mysqli_query($this->con,$query);
		if($fetch==1)
			$status="true";
		else
			$status="false";
		
			return array("status"=>$status);
	}
	public function CabbookingshowCustomer()
	{	
		//CALL Sp_cabbookingshow_customer()
		$query="SELECT tblcabbooking.ID,booking_reference,EmailId,MobileNo,PickupArea,DropArea,PickupDate,BookingDate,CarType,PickupAddress,DestinationAddress,UserName as Name,tblcabstatus.`status` as `status`,tblcabstatus.`status` as `newstatus` FROM `tblcabbooking` JOIN tblcabstatus ON tblcabstatus.status_id=tblcabbooking.`status` WHERE tblcabstatus.`type`='cab' Order By ID desc limit 10";
		$fetch=mysqli_query($this->con,$query);
		$record=array();		
		while($row=mysqli_fetch_object($fetch))
		{	
			$record[]=$row;			
			
		}
			return array("data"=>$record);
	}
	public function FillShowCustomerCare()
	{	
		$BookingId = $_REQUEST['BookingId'];
		//CALL sp_FillShowCustomerCare()
		
		$query="Select tbldriver.FirstName,tbldriver.VehicleRegistrationNo,tblcabbooking.* ,tblcabstatus.`status` as `status`,tblcabstatus.`status` as `newstatus` FROM 
		`tblcabbooking` JOIN tblcabstatus ON tblcabstatus.status_id=tblcabbooking.`status` LEFT JOIN tbldriver ON tblcabbooking.pickup=tbldriver.uid , 
		(select MobNo from tbluserinfo inner join tblcabbooking on tblcabbooking.MobileNo=tbluserinfo.MobNo where  tblcabbooking.ID='$BookingId' 
		order by tblcabbooking.ID desc Limit 1) as CustMob , 
		(select AlternateContactNo from tbluserinfo inner join tblcabbooking on tblcabbooking.MobileNo=tbluserinfo.MobNo where  tblcabbooking.ID='$BookingId' 
		order by tblcabbooking.ID desc Limit 1) as CustAlternateContactNo WHERE tblcabstatus.`type`='cab' and tblcabbooking.ID='$BookingId'";
		$fetch=mysqli_query($this->con,$query);
		$num_rows = mysqli_num_rows($fetch);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();		
		while($row=mysqli_fetch_object($fetch))
		{	
			$record[]=$row;			
			
		}
			return array("status"=>$status,"data"=>$record);
	}
	
	
	
		////// API For the Complain cab by Mohit Jain Starts Here ////
	
	public function showSubComplaint(){	
		////CALL sp_show_subcomplaint()	
		$query="Select sub_complaint from tblsubcomplaint where complaint_type=19";
		$fetch=mysqli_query($this->con,$query);
		$num_rows = mysqli_num_rows($fetch);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
			return array("status"=>$status,"data"=>$record);
	}
	
	public function showDriverComplaints(){	
		////CALL sp_show_driver_complaints()	
		$query="Select sub_complaint from tblsubcomplaint where complaint_type=18";
		$fetch=mysqli_query($this->con,$query);
		$num_rows = mysqli_num_rows($fetch);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
			return array("status"=>$status,"data"=>$record);
	}
	
	public function showFairComplaints(){	
		////CALL sp_show_fare_complaints()	
		$query="Select sub_complaint from tblsubcomplaint where complaint_type=20";
		$fetch=mysqli_query($this->con,$query);
		$num_rows = mysqli_num_rows($fetch);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
			return array("status"=>$status,"data"=>$record);
	}
	
	public function bookingComplaints(){	
		////CALL sp_booking_complaint()	
		$query="SELECT tblcabbooking.ID,booking_reference,EmailId,MobileNo,PickupArea,DropArea,PickupDate,BookingDate,CarType,PickupAddress,DestinationAddress,
				UserName,tblcabstatus.`status` as `status` FROM `tblcabbooking` JOIN tblcabstatus ON tblcabstatus.status_id=tblcabbooking.`status` 
				INNER JOIN tblcomplaint on tblcomplaint.BookingID=tblcabbooking.booking_reference  WHERE tblcabstatus.`type`='cab'  order by ID desc limit 10";
		$fetch=mysqli_query($this->con,$query);
		$num_rows = mysqli_num_rows($fetch);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
			return array("status"=>$status,"data"=>$record);
	}
	
	public function complaintByCallerID(){	
		////CALL sp_complaintByCallerID()
		$callerId=$_REQUEST['CallerId'];
		$query="SELECT tblcomplaint.ID,tblcomplaint.Complaint_ref,booking_reference,EmailId,MobileNo,PickupArea,DropArea,PickupDate,BookingDate,CarType,PickupAddress,DestinationAddress,
				UserName,tblcabstatus.`status` as `status` FROM `tblcabbooking` JOIN tblcabstatus ON tblcabstatus.status_id=tblcabbooking.`status`  
				INNER JOIN tblcomplaint on tblcomplaint.BookingID=tblcabbooking.booking_reference WHERE tblcabstatus.`type`='cab' and tblcabbooking.MobileNo='$callerId' 
				order by tblcomplaint.ID desc";
		$fetch=mysqli_query($this->con,$query);
		$num_rows = mysqli_num_rows($fetch);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
			return array("status"=>$status,"data"=>$record);
	}
		
	public function complaintsList(){	
		////CALL Sp_complaintshow()	
		$query="SELECT * FROM tblcomplaint order by ID desc";
		$fetch=mysqli_query($this->con,$query);
		$num_rows = mysqli_num_rows($fetch);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
			return array("status"=>$status,"data"=>$record);
	}
	
	public function complainInsert(){	
		////CALL Sp_complain()	
		//$value=$_REQUEST['complain_str'];
		//$value="9891735121;munesh;calling;complaqin;economy;1456;economy;toyta;tata;munesh;delpermit;15/07/2015;ptext;dtext;true;1234567890;compliandesc;english;closwure;16/07/2015;13;hc15075678";
		$jsonstring = trim($_REQUEST['jsonstring']);
	$jsonstring =json_decode($jsonstring,true);
	//var_dump($jsonstring);  die;
			 //return array("Message"=>$jsonstring); exit();

          $CallerId = $jsonstring['CallerId'];
          $CallerName=$jsonstring['CallerName'];
          $Jobno=$jsonstring['Jobno'];
          $Area=$jsonstring['Area'];
		  $Complainid = $jsonstring['Complainid'];
          $ComplainType=$jsonstring['ComplainType'];
          $Taxitype=$jsonstring['Taxitype'];
          $Taxino=$jsonstring['Taxino'];
		  $Franchise = $jsonstring['Franchise'];
          $Model=$jsonstring['Model'];
          $Drivername=$jsonstring['Drivername'];
          $PermitNo=$jsonstring['PermitNo'];
		  $DateT = $jsonstring['DateT'];
          $PLocation=$jsonstring['PLocation'];
          $Dlocation=$jsonstring['Dlocation'];
          $StatusID=$jsonstring['StatusID'];
		  $Alternateno = $jsonstring['Alternateno'];
          $ComplaintDescription=$jsonstring['ComplaintDescription'];
          $Modeofcomm=$jsonstring['Modeofcomm'];
          $ClosureDescription=$jsonstring['ClosureDescription'];
		  $ComplaintDate = $jsonstring['ComplaintDate'];
          $CSRID=$jsonstring['CSRID'];
          $bookingref=$jsonstring['bookingref'];
		  
		$query="Insert into tblcomplaint(ClientID,CallerName,Area,ComplainId,ComplaintType,TaxiType,Taxino,Franchise,Model,DriverName,PermitNo,DateT,PickLocation,
		DropLocation,StatusID,Alternateno,ComplaintDescription,Modeofcomm, ClosureDescription,ComplaintDate,CSRID,BookingID ) 
		Values ('$CallerId','$CallerName','$Area','$Complainid','$ComplainType','$Taxitype','$Taxino','$Franchise','$Model','$Drivername','$PermitNo',NOW(),'$PLocation',
		'$Dlocation','$StatusID','$Alternateno','$ComplaintDescription','$Modeofcomm','$ClosureDescription',NOW(),'$CSRID','$bookingref')";
		$sqlInsert=mysqli_query($this->con,$query);
		
		$queryGetId="SELECT ID FROM tblcomplaint where  BookingID='$bookingref'";
		$fetchGetId=mysqli_query($this->con,$queryGetId);
		$rowGetId=mysqli_fetch_object($fetchGetId);
		$Complaintid = $rowGetId->ID; 
		
		$Complaint_ref = "HCCO".$Complaintid;
		$queryRef="UPDATE tblcomplaint set Complaint_ref = '$Complaint_ref' where ID = '$Complaintid'";
		$sqlInsertRef=mysqli_query($this->con,$queryRef);
		
		$queryGetIdRef="select ID as ComplaintId,Complaint_ref as ref_number from tblcomplaint where ID='$Complaintid' order by ID desc limit 1";
		$fetchGetIdRef=mysqli_query($this->con,$queryGetIdRef);
		$record=array();		
		while($rowRef=mysqli_fetch_object($fetchGetIdRef)){	
			$record[]=$rowRef;
		} 
		
		$queryAgent="Insert into tblagent_work_history(AgentID,CallerID,BookingID,ActionType,Date,ActionDesc) 
				Values ('$CSRID','$CallerId','$bookingref','Complain', Now(),'Complain Done By Call center Agent');";
		$sqlInsertAgent=mysqli_query($this->con,$queryAgent);
		
		if($sqlInsert==1)
			$status="true";			
		else
			$status="false";
		/* $query="SELECT ID FROM tblcabbooking where  booking_reference='$bookingref'";
		$fetch=mysqli_query($this->con,$query);
		$record=array();		
		while($row=mysqli_fetch_object($fetch))
		{	
			$record[]=$row;
		} */
			return array("status"=>$status,"data"=>$record);
			//return array("status"=>$status);
         
		/* $val=explode(';',$value);
		$CallerId=$val[0];
		$CallerName=$val[1];
		$Jobno=$val[2];
		$Area=$val[3];
		$Complainid=$val[4];
		$ComplainType=$val[5];
		$Taxitype=$val[6];
		$Taxino=$val[7];
		$Franchise=$val[8];
		$Model=$val[9];
		$Drivername=$val[10];
		$PermitNo=$val[11];
		$DateT=$val[12];
		$PLocation=$val[13];
		$Dlocation=$val[14];
		$StatusID=$val[15];
		$Alternateno=$val[16];
		$ComplaintDescription=$val[17];
		$Modeofcomm=$val[18];
		$ClosureDescription=$val[19];
		$ComplaintDate=$val[20];
		$CSRID=$val[21];
		$bookingref=$val[22]; */
		
	}
		
	public function complaintDetail(){	
		////CALL sp_complaintdetail()
		$bookingId=$_REQUEST['bookingid'];
		$query="SELECT tblcomplaint.ID, tblcabbooking.booking_reference, tblcabbooking.EmailId, tblcabbooking.MobileNo, tblcabbooking.PickupArea, tblcabbooking.DropArea, 
				tblcabbooking.PickupDate, tblcabbooking.PickupTime, tblcabbooking.BookingDate, tblcabbooking.CarType,tblcabbooking.PickupAddress, 
				tblcabbooking.DestinationAddress, tblcabbooking.UserName,tblcomplaint.StatusID, tblcomplaint.ComplaintDate, tblcomplaint.ComplaintType, 
				tblcomplaint.ClosureDescription,tblcomplaint.ComplaintDescription, tblcomplaint.ComplaintType, tblcomplaint.statusId,tblcomplaint.JobNo,
				tblcomplaint.ComplainID,tblcomplaint.CallerName,tblcomplaint.TaxiType,tblcomplaint.Taxino,tblcomplaint.Franchise,tblcomplaint.DriverName,
				tblcomplaint.permitNo,tblcomplaint.Modeofcomm FROM tblcabbooking JOIN tblcomplaint on tblcomplaint.BookingID=tblcabbooking.booking_reference 
				WHERE tblcomplaint.ID='$bookingId' order by ID desc limit 1";
		$fetch=mysqli_query($this->con,$query);
		$num_rows = mysqli_num_rows($fetch);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
			return array("status"=>$status,"data"=>$record);
	}
	
	public function callerShowBybooking(){	
		////CALL sp_callershowbybooking()
		$bookingId=$_REQUEST['bookingid'];
		$query="Select (select ComplaintDescription from tblcomplaint where BookingID='$bookingId' order by id desc limit 1 )as compDesc,
		(select AlternateContactNo from tbluserinfo where  MobNo=(select MobileNo from tblcabbooking where Booking_reference='$bookingId' ) order by ID desc Limit 1)as custAlt,
		tbldriver.FirstName,tbldriver.VehicleRegistrationNo,tblcabbooking.* ,tblcabstatus.`status` as `status`,tblcabstatus.`status` as `newstatus` FROM `tblcabbooking` 
		JOIN tblcabstatus ON tblcabstatus.status_id=tblcabbooking.`status` LEFT JOIN tbldriver ON tblcabbooking.pickup=tbldriver.uid WHERE tblcabstatus.`type`='cab' 
		and booking_reference='$bookingId' order by ID desc Limit 1";
		$fetch=mysqli_query($this->con,$query);
		$num_rows = mysqli_num_rows($fetch);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
			return array("status"=>$status,"data"=>$record);
	}
	

	////// API For the Complain cab by Mohit Jain Ends Here ////
	
	
	////// API For the Enquiry Cab by Mohit Jain Starts Here ////
	
	public function showEnquiry(){	
		////CALL Sp_showenquiry()
		$Callrd=$_REQUEST['Callrd'];
		$query="select * from tblenquiry where CallerId='$Callrd' order by ID desc;";
		$fetch=mysqli_query($this->con,$query);
		$num_rows = mysqli_num_rows($fetch);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
			return array("status"=>$status,"data"=>$record);
	}
	
	public function showEnquiryy(){	
		////CALL Sp_showenquiryy()
		$id=$_REQUEST['id'];
		$query="select * from tblenquiry where ID='$id'";
		$fetch=mysqli_query($this->con,$query);
		$num_rows = mysqli_num_rows($fetch);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
			return array("status"=>$status,"data"=>$record);
	}
	
	
	public function enquiryInsert(){	
	////CALL Sp_complain()	
	$value=$_REQUEST['enquiry_str'];
	//string val = " CallerId;Mohit Kumar ;Contactno ;Email ; City;Locality ;Enqtype ;Enqtype1 ;Address ; Message;Contacted ; csrid; EnquiryDate";
	$val=explode(';',$value);
	$CallerId=$val[0];
	$CallerName=$val[1];
	$Contactno=$val[2];
	$Email=$val[3];
	$City=$val[4];
	$Locality=$val[5];
	$Enqtype=$val[6];
	$Enqtype1=$val[7];
	$Address=$val[8];
	$Message=$val[9];
	$Contacted=$val[10];
	$csrid=$val[11];
	//$csrid=$val[11];
	$EnquiryDate=$val[12];

	$query="Insert into tblenquiry (CallerId,CallerName,ContactNo,EmailId,City,Locality,EnqType,Enqtype1,Address,Message,Contracted,CSRID,EnquiryDate) 
			Values ('$CallerId','$CallerName','$Contactno','$Email','$City','$Locality','$Enqtype','$Enqtype1','$Address','$Message','$Contacted','$csrid',NOW())";
	$sqlInsert=mysqli_query($this->con,$query);
	
	 $queryGetId="select Id from tblenquiry where CallerId='$CallerId' order by Id desc limit 1";
	$fetchGetId=mysqli_query($this->con,$queryGetId);
	$rowGetId=mysqli_fetch_object($fetchGetId);
	$enquiryid = $rowGetId->Id; 
	
	$Enquiry_ref = "HCEN".$enquiryid;
	$queryRef="UPDATE tblenquiry set Enquiry_ref = '$Enquiry_ref' where Id = '$enquiryid'";
	$sqlInsertRef=mysqli_query($this->con,$queryRef);
	
	$queryGetIdRef="select Id,Enquiry_ref as ref_number from tblenquiry where Id='$enquiryid' order by Id desc limit 1";
	$fetchGetIdRef=mysqli_query($this->con,$queryGetIdRef);
	$record=array();		
	while($rowRef=mysqli_fetch_object($fetchGetIdRef)){	
			$record[]=$rowRef;
		} 

	$queryAgent="Insert into tblagent_work_history ( AgentID , CallerID ,  ActionType , Date , ActionDesc ) 
	values ('$csrid','$CallerId','Enquiry', Now(),'Enquiry Done By Call center Agent ')";
	$sqlInsertAgent=mysqli_query($this->con,$queryAgent);

	if($sqlInsert==1)
		$status="true";			
	else
		$status="false";
	
	
	
		return array("status"=>$status,"data"=>$record);
	}
	
	
	////// API For the Enquiry cab by Mohit Jain Ends Here ////
	
	
	////// API For the Monitor Job by Mohit Jain Starts Here ////
	
	public function bookingData(){	
		////CALL sp_booking_10()
		$limit_check=$_REQUEST['limit_check'];
		$limite_off=$_REQUEST['limite_off'];
		$query="SELECT CONCAT(tblcabstatus.`status`,' ',IFNULL(CONCAT('(INR ',tblbookingcharges.totalBill,')'),'')) as `status`,tblcomplaint.ID as com_id,
				tblcomplaint.StatusID as com_status, tblcabbooking.CSR_ID as emp,CONCAT(tblcabbooking.DropAddress,tblcabbooking.DropArea) as drop_area,
				tblcabbooking.ID as id,tblbookingcharges.totalBill, tblcabbooking.booking_reference as ref,tblmasterpackage.Master_Package as booking_type,
				CONCAT(tblcabbooking.PickupDate,' ',tblcabbooking.PickupTime) as ordertime,concat(tblclient.clientName,' ',tblappid.`type`) as partner,
				CONCAT(tblcablistmgmt.name,' ',tbldriver.FirstName,'(',tbldriver.ContactNo,')') as vehicle,
				CONCAT(tbluserinfo.FirstName,'(',tbluserinfo.MobNo,')') as clientname,CONCAT(tbldriver.FirstName,' ',tbldriver.ContactNo) as driver_name,
				tblcabbooking.MobileNo as mob_no,tbluserinfo.UID as client_id,tbldriver.UID as driver_id,CONCAT(tblcabbooking.PickupAddress,' ',tblcabbooking.PickupArea) as departure,
				tbldriver.VehicleRegistrationNo as reg FROM tblcabbooking JOIN tblcabstatus ON tblcabbooking.`status`=tblcabstatus.`status_id` 
				JOIN tbluserinfo ON tbluserinfo.UID=tblcabbooking.ClientID JOIN tblmasterpackage ON tblcabbooking.BookingType=tblmasterpackage.Package_Id 
				LEFT JOIN tbldriver ON tblcabbooking.pickup=tbldriver.uid LEFT JOIN tblcablistmgmt ON tbldriver.TypeOfvehicle=tblcablistmgmt.id 
				JOIN tblappid ON tblappid.id=tblcabbooking.partner JOIN tblclient ON tblappid.clientId=tblclient.id 
				LEFT JOIN tblbookingcharges ON tblcabbooking.ID=tblbookingcharges.BookingID LEFT JOIN tblcomplaint ON tblcabbooking.ID=tblcomplaint.BookingID 
				WHERE tblcabstatus.`type`='cab' order by ID desc limit $limit_check,$limite_off";
		$fetch=mysqli_query($this->con,$query);
		$num_rows = mysqli_num_rows($fetch);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
			return array("status"=>$status,"data"=>$record);
	}
	
	public function getMonitorDetails(){	
		////CALL sp_GetMoniterDetail()
		$BookingRowID=$_REQUEST['BookingRowID'];
		/* $query="select tcb.ID,tcb.`Status`,tcb.BookingType,tcb.CarType ,tcb.PickUp  ,tcb.ClientID ,tcb.No_Of_Adults,tcb.No_Of_Childs,tcb.No_Of_Luggages,tcb.No_of_taxies,
				tcb.username,tcb.EmailId,tcb.mobileNo,tcb.custType , tcb.company,tcb.pickupCity ,tcb.PickupZone ,tcb.PickupAddress, tcb.PickupArea ,
				tcb.DestinationZone,tcb.DestinationCity,tcb.DropAddress as DestinationAddress,tcb.DropArea,tcb.PickupTime,tcb.PickupDate, tcb.EstimatedDistance,tcb.estimated_price,
				tcb.approx_distance_charge,tcb.approx_after_km,tcb.appox_waiting_minute,tcb.approx_waiting_charge from tblcabbooking tcb where ID='$BookingRowID' order by id desc limit 1";
		$fetch=mysqli_query($this->con,$query);
		
		$query2="select td.UID,td.firstName,td.lastname,td.ContactNo,td.AlternateContactNo,td.Email ,td.drivinglicenceNo,td.PanCardNo,td.dateofbirth,td.lang_write, 
				td.week_Off,td.gender,td.acceptCash,td.pref_timing,td.address,td.OfcAddress,td.city,td.state,td.Country,td.pinCode  from tblDriver td 
				where UID=(select Pickup from tblcabbooking where ID='$BookingRowID') order by id desc limit 1";
		$fetch2=mysqli_query($this->con,$query2);
		
		$query3="select tcm.Cabid,tcm.cabname,tcm.CabType,tcm.CabManufacturer,tcm.CabModel,tcm.CabRegistrationNumber,td.ModelOfVehicle,td.VehicleRegistrationNo, 
				td.licence_state,td.zone,td.route_know,td.imei,td.pref_location,td.gps from tblcabmaster tcm  inner join tbldriver td on tcm.cabid=td.vehicleId 
				where td.UID=(select Pickup from tblcabbooking where ID='$BookingRowID') order by id desc limit 1";
		$fetch3=mysqli_query($this->con,$query3);
		
		$query4="select tui.id, tui.mobNo,tui.AlternateContactNo from tbluserinfo tui inner join tblcabbooking tcb on tui.UID=tcb.ClientID where tcb.ID='$BookingRowID 
				order by id desc limit 1";
		$fetch4=mysqli_query($this->con,$query4);
		
		$record=array();
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}		
		while($row2=mysqli_fetch_object($fetch2)){	
			$record[]=$row2;
		}
		while($row3=mysqli_fetch_object($fetch3)){	
			$record[]=$row3;
		}
		while($row4=mysqli_fetch_object($fetch4)){	
			$record[]=$row4;
		} */
		
		$qry = "select pickup from tblcabbooking where ID='$BookingRowID' order by id desc limit 1";
				$fetch=mysqli_query($this->con,$qry);
				$row=mysqli_fetch_object($fetch);
			    if($row->pickup!=0)
				{
					$qry1 = "select tcb.ID,tcb.`Status`,tcb.BookingType,tcb.CarType ,tcb.PickUp  ,tcb.ClientID ,tcb.No_Of_Adults,tcb.No_Of_Childs,tcb.No_Of_Luggages,tcb.No_of_taxies,
					tcb.username,tcb.EmailId,tcb.mobileNo as customerBookingMobileNo,tcb.custType , tcb.company,tcb.pickupCity ,tcb.PickupZone ,tcb.PickupAddress, tcb.PickupArea ,
					tcb.DestinationZone,tcb.DestinationCity,tcb.DropAddress as DestinationAddress,tcb.DropArea,tcb.PickupTime,tcb.PickupDate, tcb.EstimatedDistance,tcb.estimated_price,
					tcb.approx_distance_charge,tcb.approx_after_km,tcb.appox_waiting_minute,tcb.approx_waiting_charge,td.UID,td.firstName,td.lastname,td.ContactNo,td.AlternateContactNo as driverAltNo,td.Email ,td.drivinglicenceNo,td.PanCardNo,td.dateofbirth,td.lang_write, 
					td.week_Off,td.gender,td.acceptCash,td.pref_timing,td.address,td.OfcAddress,td.city,td.state,td.Country,td.pinCode,
					tcm.Cabid,tcm.cabname,tcm.CabType,tcm.CabManufacturer,tcm.CabModel,tcm.CabRegistrationNumber,td.ModelOfVehicle,td.VehicleRegistrationNo, 
					td.licence_state,td.zone,td.route_know,td.imei,td.pref_location,td.gps, tui.id, tui.mobNo as customerMobileNo,tui.AlternateContactNo as customerAltNo
					from tblcabbooking tcb inner join tbldriver td on  tcb.pickup = td.UID 
					inner join  tblcabmaster tcm   on tcm.cabid=td.vehicleId  
					inner join tbluserinfo tui on tui.UID=tcb.ClientID where 
					tcb.ID='$BookingRowID'";
					$fetch1=mysqli_query($this->con,$qry1);
					$row1=mysqli_fetch_object($fetch1);
					$record[]=$row1;
				}
				else
				{
					//echo "hell";
				
				   $qry1 = "select  tcb.ID,tcb.`Status`,tcb.BookingType,tcb.CarType ,tcb.PickUp  ,tcb.ClientID ,tcb.No_Of_Adults,tcb.No_Of_Childs,tcb.No_Of_Luggages,tcb.No_of_taxies,
					tcb.username,tcb.EmailId,tcb.mobileNo as customerBookingMobileNo,tcb.custType , tcb.company,tcb.pickupCity ,tcb.PickupZone ,tcb.PickupAddress, tcb.PickupArea ,
					tcb.DestinationZone,tcb.DestinationCity,tcb.DropAddress as DestinationAddress,tcb.DropArea,tcb.PickupTime,tcb.PickupDate, tcb.EstimatedDistance,tcb.estimated_price,
					tcb.approx_distance_charge,tcb.approx_after_km,tcb.appox_waiting_minute,tcb.approx_waiting_charge, tui.id, tui.AlternateContactNo as customerAltNo, tui.mobNo as customerMobileNo
					from tblcabbooking tcb
					inner join tbluserinfo tui on tui.UID=tcb.ClientID where 
					tcb.ID = '$BookingRowID'"; 
					$fetch1=mysqli_query($this->con,$qry1);
					$row1=mysqli_fetch_object($fetch1);
					$record[]=$row1;
					//$record[] = "NA";
				}
			        //$record[]=$row;
		        
		
		        /* $qry = "select tcb.ID,tcb.`Status`,tcb.BookingType,tcb.CarType ,tcb.PickUp  ,tcb.ClientID ,tcb.No_Of_Adults,tcb.No_Of_Childs,tcb.No_Of_Luggages,tcb.No_of_taxies,
				tcb.username,tcb.EmailId,tcb.mobileNo,tcb.custType , tcb.company,tcb.pickupCity ,tcb.PickupZone ,tcb.PickupAddress, tcb.PickupArea ,
				tcb.DestinationZone,tcb.DestinationCity,tcb.DropAddress as DestinationAddress,tcb.DropArea,tcb.PickupTime,tcb.PickupDate, tcb.EstimatedDistance,tcb.estimated_price,
				tcb.approx_distance_charge,tcb.approx_after_km,tcb.appox_waiting_minute,tcb.approx_waiting_charge,td.UID,td.firstName,td.lastname,td.ContactNo,td.AlternateContactNo,td.Email ,td.drivinglicenceNo,td.PanCardNo,td.dateofbirth,td.lang_write, 
				td.week_Off,td.gender,td.acceptCash,td.pref_timing,td.address,td.OfcAddress,td.city,td.state,td.Country,td.pinCode,
				tcm.Cabid,tcm.cabname,tcm.CabType,tcm.CabManufacturer,tcm.CabModel,tcm.CabRegistrationNumber,td.ModelOfVehicle,td.VehicleRegistrationNo, 
				td.licence_state,td.zone,td.route_know,td.imei,td.pref_location,td.gps, tui.id, tui.mobNo,tui.AlternateContactNo
				 from tblcabbooking tcb inner join tblDriver td on  tcb.pickup = td.UID 
				 inner join  tblcabmaster tcm   on tcm.cabid=td.vehicleId  
				 inner join tbluserinfo tui on tui.UID=tcb.ClientID where 
				 tcb.ID='$BookingRowID'";
				 $fetch=mysqli_query($this->con,$qry);
				 while($row=mysqli_fetch_object($fetch))
				 {	
			        $record[]=$row;
		         } */
			return array("status"=>true,"data"=>$record);
	}
	
		public function searchMonitor(){	
		//$value=$_REQUEST['monitor_str'];
		//$value="None;None;None;None;All Booking;2/25/2015 12:00:00 AM;8/25/2015 12:00:00 AM";
		//$value="9891735121;HC15075805;DYL456;Shyam;All Booking;6/25/2015 12:00:00 AM;8/25/2015 12:00:00 AM";
		/* 
		$value=str_replace('_',' ',$value);
		//echo $value; die;
		$val=explode(';',$value);
		$CallerId=$val[0];
		$BookingId=$val[1];
		$TaxiNumber=$val[2];
		$DriverName=$val[3];
		$BookingType=$val[4];
		$FromDate=$val[5];
		$ToDate=$val[6]; */
		$jsonstring = trim($_REQUEST['monitor_str']);
	    $jsonstring =json_decode($jsonstring,true);
	    //var_dump($jsonstring);  die;
		//return array("Message"=>$jsonstring); exit();

		$CallerId = $jsonstring['CallerId'];
		$BookingId=$jsonstring['BookingId'];
		$TaxiNumber=$jsonstring['TaxiNumber'];
		$DriverName=$jsonstring['DriverName'];
		$BookingType=$jsonstring['BookingType'];
		$FromDate=$jsonstring['FromDate'].' 00:00:01';
		$ToDate=$jsonstring['ToDate'].' 23:59:59';

		if($CallerId!="None" )
			$CallerIdCondition = "and tbc.MobileNo='$CallerId'";
		else
			$CallerIdCondition = "";
		
		if($BookingId!="None")
			$BookingIdCondition = "and tbc.Booking_reference='$BookingId'";
		else
			$BookingIdCondition = "";
		
		if($TaxiNumber!= "None")
            $TaxiNumberCondition = "and td.VehicleRegistrationNo='$TaxiNumber'";
		else
			$TaxiNumberCondition = "";
		
		if($DriverName!= "None")
            $DriverNameCondition = "and td.FirstName='$DriverName'";
		else
			$DriverNameCondition = "";
		if($BookingType== "Android Booking")
            $BookingTypeCondition = "and tbc.DeviceType='ANDROID'";
		elseif($BookingType== "Web Booking")
			$BookingTypeCondition = "and tbc.DeviceType='WEB'";
		elseif($BookingType== "Call Center Booking")
			$BookingTypeCondition = "and tbc.DeviceType='CALLCENTER'";
		else
			$BookingTypeCondition = "";
		
		 /*$query="select  tbc.ID,tbc.Booking_reference as ref, concat(tbc.PickupDate , ' ',tbc.pickuptime) as ordertime ,(select master_package from tblmasterpackage 
				where package_id =tbc.BookingType) as booking_type,concat('Hello42 ', tbc.DeviceType) as Partner,concat( tbc.UserName ,' ( ', tbc.MobileNo,' )' ) as ClientName,
				tbc.DropArea as drop_area, tbc.PickupArea as departure, tblcabstatus.`status`, concat(td.vehicleRegistrationNo,' ( ',td.FirstName,' )') as vehicle  from tblcabbooking tbc inner join  tbldriver td on tbc.pickup=td.uid 
				inner join tblmasterpackage tmp on tmp.package_id=tbc.BookingType inner join tblcabstatus on tblcabstatus.status_id = tbc.Status where tbc.Bookingdate >= '$FromDate' 
				AND tbc.Bookingdate <= '$ToDate' and tblcabstatus.type = 'cab' $CallerIdCondition $BookingIdCondition $TaxiNumberCondition $DriverNameCondition $BookingTypeCondition group by tbc.ID order by tbc.ID DESC limit 0,30";*/
		
		$query="select  tbc.ID,tbc.Booking_reference as ref, concat(tbc.PickupDate , ' ',tbc.pickuptime) as ordertime ,(select master_package from tblmasterpackage 
				where package_id =tbc.BookingType) as booking_type,concat('Hello42 ', tbc.DeviceType) as Partner,concat( tbc.UserName ,' ( ', tbc.MobileNo,' )' ) as ClientName,
				tbc.DropArea as drop_area, tbc.PickupArea as departure, tblcabstatus.`status`, IFNULL(tbc.pickup,0) as vehicle  from tblcabbooking tbc inner join tblmasterpackage tmp on tmp.package_id=tbc.BookingType inner join tblcabstatus on tblcabstatus.status_id = tbc.Status where tbc.Bookingdate >= '$FromDate' 
				AND tbc.Bookingdate <= '$ToDate' and tblcabstatus.type = 'cab' $CallerIdCondition $BookingIdCondition $TaxiNumberCondition $DriverNameCondition $BookingTypeCondition group by tbc.ID order by tbc.ID DESC limit 0,20";
		//die;
		$fetch=mysqli_query($this->con,$query);
		$num_rows = mysqli_num_rows($fetch);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){
			/*$pickup=$row->vehicle;
			if($pickup!=0){
			echo "Mohit";
			$sql="SELECT concat(vehicleRegistrationNo,' ( ',FirstName,' )') as vehicle1 FROM `tbldriver` WHERE uid='$pickup'";
			$fetch1=mysqli_query($this->con,$sql);	
			$row1=mysqli_fetch_array($fetch1);
			$driverName	=	$row1['vehicle1'];
			$record['vehicle']=$driverName;
			}else{
			$driverName	="Not Alloted";	
			$record['vehicle']=$driverName;
			echo "Jain";
			}*/
			
			$record[]=$row;
		}
		//die;
			return array("status"=>$status,"data"=>$record);
	}
	
	////// API For the Monitor Job by Mohit Jain Ends Here ////
	
	public function PaymentInformation(){	
		////CALL sp_w_payment_information()
		$booking_id=$_REQUEST['booking_id'];
		$query="SELECT tblbookingbill.CabName as cab_name,addedtime,cancellation_price,waitingCharge,tripCharge,total_tax_price,totalBill,is_paid,paid_at,currency,invoice_number,payment_type,fees,total_price,total_tax_price,duration_rate,starting_rate, 
		base_price,tax_price,starting_charge,distance_charge,duration_charge,minimum_price,tblcabbooking.* FROM tblcabbooking
		LEFT JOIN tblbookingcharges ON tblcabbooking.id=tblbookingcharges.bookingid
		LEFT JOIN tblbookingbill ON tblcabbooking.CarType=tblbookingbill.Id WHERE tblcabbooking.id='$booking_id'";
		$fetch=mysqli_query($this->con,$query);
		$num_rows = mysqli_num_rows($fetch);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
			return array("status"=>$status,"data"=>$record);
	}
	public function BookingInformation(){	
		////CALL sp_w_booking_information()
		$booking_id=$_REQUEST['booking_id'];
		echo $query="SELECT tblcabstatus.`status` as `status`,`time`,`message` FROM tblbookinglogs JOIN tblcabstatus ON tblbookinglogs.`Status`=tblcabstatus.`status_id` WHERE tblbookinglogs.bookingid='$booking_id' and tblcabstatus.`type`='cab'"; die;
		$fetch=mysqli_query($this->con,$query);
		$num_rows = mysqli_num_rows($fetch);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
			return array("status"=>$status,"data"=>$record);
	}
	
	public function NewCompany(){	
	////CALL  sp_new_company()	
	$value=$_REQUEST['value'];
	//string val = "companyuserName;cities;cities ;adds ; userPins;userMobile ;UserLandline ;userEmails ;altUserEmails ; Message;Contacted ; csrid; EnquiryDate";
	$val=explode(';',$value);
	$companyuserName=$val[0];
	$cities=$val[1];
	$adds=$val[2];
	$userPins=$val[3];
	$userMobile=$val[4];
	$UserLandline=$val[5];
	$userEmails=$val[6];
	$altUserEmails=$val[7];

	$query="INSERT INTO tblcompany (CompanyName,CompanyCity,CompanyAddress,CompanyPIN,ComMob,CompLand,Email,AltEmail) VALUES ('$companyuserName','$cities','$adds','$userPins','$userMobile','$UserLandline','$userEmails','$altUserEmails')";

	$sqlInsert=mysqli_query($this->con,$query);

	if($sqlInsert==1)
		$status="true";			
	else
		$status="false";
		return array("status"=>$status);
	}
	public function PromotionName(){	
		////CALL sp_PromotionName()
		//$booking_id=$_REQUEST['booking_id'];
		$query="select  ID,PromotionName,PromotionDateFrom,PromotionDateTo from tblpromotionmaster";
		$fetch=mysqli_query($this->con,$query);
		$num_rows = mysqli_num_rows($fetch);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
			return array("status"=>$status,"data"=>$record);
	}
	public function FreeTaxi(){	
		////CALL  sp_freetaxi()
		$CabType1=$_REQUEST['CabType1'];
		$query="select concat(td.UID ,' ( ' , td.FirstName,' )') as Name  from tbluser
inner join tbluserinfo tu on tbluser.ID=tu.UID
inner join tbldriver td on td.Uid=tbluser.ID
inner join tblcabmaster tc on td.vehicleId=tc.CabId
where tbluser.UserType = 3 AND tbluser.isVerified = 1 and  td.`status`=0 and tbluser.loginStatus=1 and tc.CabType='$CabType1'";
		$fetch=mysqli_query($this->con,$query);
		$num_rows = mysqli_num_rows($fetch);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
			return array("status"=>$status,"data"=>$record);
	}
	public function CheckBookingID(){	
		//CALL   SP_check_bookingID()
		$BookIDVAL=$_REQUEST['BookIDVAL'];
		$query="select ID,tblcabbooking.`Status` from tblcabbooking where Booking_reference='$BookIDVAL'";
		$fetch=mysqli_query($this->con,$query);
		$num_rows = mysqli_num_rows($fetch);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
		$record[]=$row;
		}
		return array("status"=>$status,"data"=>$record);
	}
	
	
	
	public function AdminLogin(){

	//CALL  Sp_AdminLogin()	
	$value=$_REQUEST['value'];
	//string val = "user;userPass";
	$val=explode(';',$value);
	$user=$val[0];
	$userPass=$val[1];
	$record=array();
	//$query1="update agent_login_history set LogoutTime=now() , LogOutBy='By Agent LogOut' where id =value and agentID=agent";
	//$update_agent=mysqli_query($this->con,$query1);
	
	
	//$AdminIVRLoginArray	=	$this->AdminIVRLogin($user,$userPass);
	//echo $AdminIVRLoginArray['status']; die;
	//if($AdminIVRLoginArray['status']=="true"){
	
	$query="update login set status='Active' where username = '$user' and password='$userPass'";
	$sqlupdate=mysqli_query($this->con,$query);
	mysqli_free_result($sqlupdate);   
    mysqli_next_result($this->con); 
		
    $queryinsert="insert into agent_login_history  (AgentID, LoginTime,  LoginBy) values ((select login.id from Login where username = '$user' and password='$userpass'),now(),'By Agent Login')";
	$sqlinsert=mysqli_query($this->con,$queryinsert);
	mysqli_free_result($sqlinsert);   
    mysqli_next_result($this->con); 
	
	$query_sel="select * from login where username = '$user' and password='$userPass'";
	$sqlSelect=mysqli_query($this->con,$query_sel);
	$record[]=mysqli_fetch_object($sqlSelect);
			
	//if($sqlupdate==1)
	if(mysqli_num_rows($sqlSelect)>0)
		$status="true";			
	else
		$status="false";
		return array("status"=>$status,"data"=>$record);
	/*}else{
		$status="false";
		return array("status"=>$status,"data"=>"You are Not Connected to IVR Database");
	}*/
	}
	
	
	
	public function Logout(){	
	//CALL   sp_logout()	
	$value=$_REQUEST['value'];
	//string val = "user;userPass";
	$val=explode(';',$value);
	$uname=$val[0];
	$pass=$val[1];
	
	//$query1="update agent_login_history set LogoutTime=now() , LogOutBy='By Agent LogOut' where id =value and agentID=agent";
	//$update_agent=mysqli_query($this->con,$query1);
	$qry_logout="select id from agent_login_history where AgentId=(select id into agent from Login where username = '$uname' and password='$pass')  order by id desc limit 1";
	$fetch_logout=mysqli_query($this->con,$qry_logout);
	while($row=mysqli_fetch_object($fetch_logout))
	{
	  $value1 = $row['id'];
	}
	
	$query="update agent_login_history set LogoutTime=now() , LogOutBy='By Agent LogOut' where id ='$value1' and agentID=(select id from Login where username = '$uname' and password='$pass')";
	$sqlupdate=mysqli_query($this->con,$query);
		
    $queryinsert="update login set status='Inactive' where username = '$uname' and password='$pass'";
	$sqlinsert=mysqli_query($this->con,$queryinsert);
		
	if($sqlinsert==1)
		$status="true";			
	else
		$status="false";
		return array("status"=>$status);
	
	}
	
	public function AgentLogout(){	
	//CALL   SP_AgentLogOut()	
	$AgID=$_REQUEST['AgID'];
	//die;
	//select id into value from agent_login_history where AgentId=AgID  order by id desc limit 1;
//update agent_login_history set LogoutTime=now() , LogOutBy='By Agent LogOut' where id =value and agentID=AgID;

	$sql1="select id from agent_login_history where AgentId='$AgID' order by id desc limit 1";
	$res=mysqli_query($this->con,$sql1);
	$row=mysqli_fetch_array($res);
	$val=$row['id'];
	
	$query="update agent_login_history set LogoutTime=now() , LogOutBy='By Agent LogOut' where id ='$val' and agentID='$AgID'";
	$sqlupdate=mysqli_query($this->con,$query);
				
	if($sqlupdate==1)
		$status="true";			
	else
		$status="false";
		return array("status"=>$status);
	
	}
	
	public function Bookingshoworder()
	{
		//$mob=$_REQUEST['mob'];
		$book_ref=$_REQUEST['book_ref'];
 $query="SELECT tbldriver.firstname, tbldriver.VehicleRegistrationNo, tblcabbooking.ID, booking_reference, EmailId, UserName, MobileNo, PickupArea,DropArea,
PickupDate,BookingDate,CarType,PickupAddress,DestinationAddress,pickup ,tblcabbooking.*,  tblcabstatus.`status`  as `status1`  FROM `tblcabbooking` 
JOIN tblcabstatus ON tblcabbooking.`status`=tblcabstatus.status_id LEFT JOIN tbldriver ON tblcabbooking.pickup=tbldriver.uid WHERE 
tblcabstatus.type='cab' and tblcabbooking.booking_reference='$book_ref' order by ID desc limit 1;";
		$fetch=mysqli_query($this->con,$query);
		$record=array();		
		while($row=mysqli_fetch_object($fetch)){	
			$record[]=$row;
		}
			return array("data"=>$record);
	}
	
	
	public function Forgetpassword()
	{
	//$jsonstring='[{"Full_Name": "Sanjay Kumar","Mobile_No": "9313788493","Password": "123456"}]';
	$jsonstring = trim($_REQUEST['jsonstring']); 
	$jsonstring =json_decode($jsonstring,true); 
	//echo "<pre>";print_r($jsonstring); die;
	
	$Full_Name=$jsonstring['username']; 
	$Mobile=$jsonstring['MobileNo'];
	$UserID=$jsonstring['UserID'];
	$password=$jsonstring['Password'];
	
	$query="select * from login where Full_Name='$Full_Name' and Mobile='$Mobile' and username = '$UserID'"; 
	$fetch=mysqli_query($this->con,$query);		
	$row=mysqli_fetch_object($fetch);	
	
	$noRows=mysqli_num_rows($fetch);
	if($noRows>0){
		$change	=	$this->ForgetPasswordIVRLogin($password,$UserID);
	if($change=="true"){
		$sql="UPDATE login SET password='$password' where Full_Name='$Full_Name' and Mobile='$Mobile' and username = '$UserID'"; 
		$result = mysqli_query($this->con,$sql);        
	 	$res = mysqli_query($this->con,"SELECT * FROM tbl_sms_template WHERE msg_sku='forgot_password'");
	    $msg_query=mysqli_fetch_array($res); 
		$array=explode('<variable>',$msg_query['message']);
		$array[0]=$array[0].$Full_Name;
		$text=  urlencode(implode("",$array));	
		//file_put_contents("mssg.txt",$text);
		//$url="http://push3.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$Mobile&from=Helocb&dlrreq=true&text=$text&alert=1";
		file_get_contents($url);
		mysqli_free_result($res);   
		mysqli_next_result($this->con);	
	if($result>0){	
		$message="Password Update Successfully";
		$status="true";
	}	
	else{
		
		$message="Invalid User";
		$status="false";
		}
	}
	}
	
		return array("status"=>$status,"message"=>$message);
	}
	
	public function RecordingDetail()
	{	
	   $value=$_REQUEST['value'];
	   //$value='{"CustMobile":" 9891735121","CustRecordFileName":"151108161028 I0322","CustomerBookingRefrence":" HCCO116","callTime":"03:21:03"}';
	   $value=json_decode($value,true);
		//print_r($value);exit;
		 $CustMobile=$value['CustMobile'];
		 $CustRecordFileName=$value['CustRecordFileName'];
		 $CustCallRefrence=$value['CustomerBookingRefrence'];
		 $callTime=$value['callTime'];
		 $AgentRemarks=$value['AgentRemarks'];
		 $CallTransferToAgent=$value['CallTransferToAgent'];
		 $CallFreeResponse=$value['CallFreeResponse'];
		 if($callTime==''){
			 $callTime="00:00:00";
		 }
		$queryinsert="insert into tbl_ivr_recordingdetail (CustMobile,CustRecordFileName,CustCallRefrence,custDatetime,callTime,AgentRemarks,CallTransferToAgent,CallFreeResponse) 
		values ('$CustMobile','$CustRecordFileName','$CustCallRefrence',NOW(),'$callTime','$AgentRemarks','$CallTransferToAgent','$CallFreeResponse')";
		$sqlinsert=mysqli_query($this->con,$queryinsert);		
		if($sqlinsert==1)
			$status="true";			
		else
			$status="false";
		return array("status"=>$status); 
	}
	
	public function GetBookingDetail($booking_ref)
	{
		//$data=array();
		//$booking_id=$_REQUEST['booking_id'];
		//$booking_ref=$_REQUEST['booking_ref'];
		$query="select id,BookingType,Pickup,status as cabstatus,BookingDate,PickupDate,PickupTime from tblcabbooking where booking_reference='$booking_ref'";
		$fetch=mysqli_query($this->con,$query);
		$record=array();		
		$row=mysqli_fetch_assoc($fetch);	
	    return $row;
	}
	public function SwapTaxi()
	{
		//$data=array();
		$value=$_REQUEST['CabType1'];
		$val = explode(';',$value);
		$booking_ref = $val[0];
		$driver_id = $val[1];
		$agent_id = $val[2];
		
		
		///// using get booking detail for reshedule history
		//$data=array();
		$data = $this->GetBookingDetail($booking_ref);		// order is making as booking reference  
		$id=$data['id'];
		$BookingType=$data['BookingType'];
		$old_Pickup=$data['Pickup'];
		$new_Pickup=$driver_id;
		$old_cabstatus=$data['cabstatus'];
		$old_BookingDate=$data['BookingDate'];
		$old_PickupDate=$data['PickupDate'];
		$old_PickupTime=$data['PickupTime'];
		//$new_bookingDate= NOW();
		//$new_PickupDate=$ReschuduleDate;
		$new_PickupTime=date("h:i:s",strtotime(date("h:i:s"))+600 );
		$new_PickupDate=date("Y-m-d");
		//$new_cabstatus=$ReschuduleTime;
		//if driver is assigned then status must be set to "accepted"
		$qry = "update tblcabbooking set Status='3' , Pickup = '$new_Pickup', BookingDate = NOW(),StatusC='13'  where booking_reference='$booking_ref'";
		$result=mysqli_query($this->con,$qry);
		
		$qry1 ="insert into tbl_booking_Resec_Reassign (booking_id,booking_type,old_driver_id,new_driver_id,old_pick_time,old_pick_date,new_pick_time,new_pick_date,old_booking_date,new_booking_date)
		values ('$id','$BookingType' ,'$old_Pickup','$new_Pickup','$old_PickupTime','$old_PickupDate' ,'$new_PickupTime','$new_PickupDate','$old_BookingDate' ,NOW())";
		$fetch2=mysqli_query($this->con,$qry1);
		if($agent_id!=""){	
		$AgentId=$agent_id;
		$ActionDesc="Booking Reassign by Admin";
		}
		else{
		$sql="select CSR_ID from tblcabbooking where booking_reference='$booking_ref'";
		$result1=mysqli_query($this->con,$sql);
		$res=mysql_fetch_array($result1);
		$AgentId=$res['CSR_ID'];
		$ActionDesc="Booking Reassign done by Call Center agent";
		}
		$qry3 ="insert into tblagent_work_history (AgentID,CallerID,BookingID,Actiontype,tblagent_work_history.Date,ActionDesc)
		values ('$AgentId',(select MobileNo from tblcabbooking where booking_reference='$booking_ref'),'$booking_ref','Reassign',Now(),'$ActionDesc' )";
		$fetch3=mysqli_query($this->con,$qry3);
		
		$status = "true";
		return array("status"=>$status);
	}
	
	
	
public function MssqlConnectivity2(){
$server = '10.0.0.102';
$username = 'QuickCall';
$password = 'QuickCall';
$database = 'QuickCall';
$connection = mssql_connect($server, $username, $password);
 
if($connection != FALSE)
{
echo "Connected to the database server OK<br />";
}
else
{
die("Couldn't connect");
}
 
if(mssql_select_db($database, $connection))
{
echo "Selected $database ok<br />";
}
else
{
die('Failed to select DB');
}
 
$query_result = mssql_query('SELECT AgentID, Password, Name FROM AgentMst');

while($row = mssql_fetch_array($query_result))  
{  
     echo "Col1: ".$row[0]."\n";  
     echo "Col2: ".$row[1]."\n";  
     echo "Col3: ".$row[2]."<br>\n";  
     echo "-----------------<br>\n";  
}  
 
mssql_free_result($query_result);
mssql_close($connection);
	
	}
	
//// Function to connect the MS-SQL Database Connection Starts Here ///
		public function MssqlConnectivity(){
			$server = '10.0.0.102';
			$username = 'QuickCall';
			$password = 'QuickCall';
			$database = 'QuickCall';
			$connection = mssql_connect($server, $username, $password);
			 
			if($connection != FALSE)
			{
			$msg="Connected to the database server OK<br />";
			$status="true";
			}
			else
			{
			die("Couldn't connect");
			}
			 
			if(mssql_select_db($database, $connection))
			{
			$msg="Selected $database ok<br />";
			$status="true";
			}
			else
			{
			die('Failed to select DB');
			}
			 
			return array("status"=>$status);
			
			}
//// Function to connect the MS-Sql Database Connection Ends Here /// 

//// Function to Login with the IVR Server Starts Here //////

	public function AdminIVRLogin($user,$userPass){
			// http://localhost/hello42/tunnel/CustomerCare/AdminIVRLogin?value=test1;1234	
			//$value=$_REQUEST['value'];
			//string val = "user;userPass";
			//$val=explode(';',$value);
			//$user=$val[0];
			//$userPass=$val[1];
			$record=array();	
			$con=$this->MssqlConnectivity();
			$query_sel="SELECT AgentID, Password, Name FROM AgentMst where AgentID = '$user' and Password='$userPass'";
			$sqlSelect=mssql_query($query_sel);
			$sqlnum_rows=mssql_num_rows($sqlSelect);
			$record[]=mssql_fetch_object($sqlSelect);
					
			if($sqlnum_rows>0)
				$status="true";			
			else
				$status="false";
				return array("status"=>$status,"data"=>$record);
		}
		
//// Function to Login with the IVR Server Ends Here //////	

/// Function to ForgetPassword with the IVR Server Starts Here ////

public function ForgetPasswordIVRLogin($password,$UserID){
	$con1=$this->MssqlConnectivity();	
	
	 $query_sel="SELECT * FROM AgentMst where AgentID = '$UserID'";
			$sqlSelect=mssql_query($query_sel);
			$sqlnum_rows=mssql_num_rows($sqlSelect);
			$record[]=mssql_fetch_object($sqlSelect);
	if($sqlnum_rows>0){
		//echo "Mohit"; die;
		$query_update="UPDATE AgentMst SET Password='$password' where AgentID='$UserID'";
		$sqlUpdate=mssql_query($query_update);
				
		if($sqlUpdate>0){
			$status="true";	
			/*$query_sel="SELECT * FROM AgentMst where AgentID = '$UserID'";
			$sqlSelect=mssql_query($query_sel);
			$sqlnum_rows=mssql_num_rows($sqlSelect);
			$record=mssql_fetch_object($sqlSelect);		
			echo "<pre>";print_r($record); die;*/
		}else{
			$status="false";
		}
	}
	else{
			$status="false";
	}
	
			return $status;
	}
	

/// Function to ForgetPassword with the IVR Server Ends Here ////
	
}
?>
