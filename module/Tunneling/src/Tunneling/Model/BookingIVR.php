<?php
namespace Tunneling\Model;
use Zend\Mail;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Session\Container;
use Tunneling\Model\DatabaseConfig;

class BookingIVR{	
	protected $con;
	private $data = array();
	private $row = array();	
	public function __construct(){		
		date_default_timezone_set("Asia/Kolkata");
		$db=new DatabaseConfig();
		$this->con=$db->getDatabaseConfig();
		$key = '';
		//$ip='10.0.0.101:82';
		//$this->con=mysqli_connect($ip,"root","root","hello42_new");
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
	// Show the city name from PackageCity table
	public function ShowCity()
	{
		//CALL sp_City_show()
		  $qry = "select state as CityName from tblstates where Status='1'"; 
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}	
	//Find the Agent Booking details. login id ,Month and year wise.
	public function AgentBooking()
	{
		//13;8;2015
		//CALL Sp_GetAgentBooking('$LoginId','$AMonth','$AYear')
		$Value = $_REQUEST['Value'];
		$val = explode(';',$Value);
		$LoginId = $val[0];
		$AMonth = $val[1];
		$AYear = $val[2];
		$sql="SELECT * from agent_booking_detail where Agent_Id='$LoginId' and Booking_month='$AMonth' and Booking_year='$AYear'";
		$result = mysqli_query($this->con, $sql);
		$num_rows = mysqli_num_rows($result);
		if($num_rows==0){
		$qry ="insert into agent_booking_detail (Agent_Id,Target_Booking,Booking_Month,Booking_year) VALUES ('$LoginId','200','$AMonth','$AYear')";
		$fetch1=mysqli_query($this->con,$qry); 
		}
		$qry = "select Agent_Id,Target_Booking ,Total_Booking,Booking_operated,Enquiry_Booking, Booking_rejection,Complaint_booking,Rescehdule_booking, Cancel_Booking, Login_Time , Break_Time,Booking_Month,Booking_year, (select count(*)  from tbluser where UserType=3 and isVerified=1 and is_Active =1 )as Available_cab from agent_booking_detail where Agent_Id='$LoginId' and Booking_month='$AMonth' and Booking_year='$AYear'";
		$result1 = mysqli_query($this->con, $qry);
		$num_rows1 = mysqli_num_rows($result1);
		if($num_rows1>0)
			$status="true";
		else
			$status="false";
		$record=array();
		while($info = mysqli_fetch_object($result1))
		{
		   $record[]=$info;
		  //$i++;
		}
		return array("status"=>"$status","record"=>$record);
	}
	// Show the Company name, call id and Alter id wise from tblcompany table ,
	public function ShowCompany()
	{
		//CALL sp_company_show('$CallId','$AlterID')
		$CallId = $_REQUEST['CallId'];
	    $AlterID = $_REQUEST['AlterID'];
		if($AlterID==""){
			$qry = "Select distinct UCASE(CompanyName) as CompanyName from tblcompany where ComMob = '$CallId' Order by CompanyName";
		}else if($CallId==""){
			$qry = "Select distinct UCASE(CompanyName) as CompanyName from tblcompany where ComMob = '$AlterID' Order by CompanyName";
		}else{
			$qry = "Select distinct UCASE(CompanyName) as CompanyName from tblcompany where ComMob = '$CallId' OR ComMob= '$AlterID' Order by CompanyName";
		}
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		while($info = mysqli_fetch_object($result))
		{
			$record[] = $info;
	    	//print_r($info);exit;
		}
		return array("status"=>"$status","record"=>$record);
	}
	// Show the Sub Package from tblbillconfigpackage table.
	public function ShowPackage()				
	{
		//CALL sp_package_show()
		$qry = "Select  Sub_package from tblbillconfigpackage";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}	
	
	public function ShowCaller()				
	{
		//CALL sp_callershow('$Callerid')
		$Callerid = $_REQUEST['Callerid'];
		$qry = "Select tbldriver.FirstName,tbldriver.VehicleRegistrationNo,tblcabbooking.* ,tblcabstatus.`status` as `status`,
tblcabstatus.`status` as `newstatus`,(select MobNo from tbluserinfo where  MobNo='$Callerid' order by ID desc Limit 1) as CustMobile ,
(select AlternateContactNo from tbluserinfo where  MobNo='$Callerid' order by ID desc Limit 1) as CustAlternateNo

 FROM `tblcabbooking` JOIN tblcabstatus ON tblcabstatus.status_id=tblcabbooking.`status`
  LEFT JOIN tbldriver ON tblcabbooking.pickup=tbldriver.uid
  WHERE tblcabstatus.`type`='cab' and MobileNo='$Callerid' order by ID desc Limit 10";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}	
	public function ShowCaller_onBooking()				
	{
		//CALL sp_callershow('$Callerid')
		$Callerid = $_REQUEST['Callerid'];
		$qry = "Select EmailId, BookingType,No_Of_Luggages,No_Of_Childs,No_Of_Adults,CustType,Company,No_of_taxies,Priority,refno,CarType,Local_package_Name,Package_State,PickupCountry,PickupZone,PickupState,PickupCity,PickupArea,PickupAddress,PickupLatitude,PickupLongtitude,DestinationCountry,DestinationState,DestinationZone,DestinationCity,DropArea,DropAddress,DestinationLongtitude,DestinationLatitude,EstimatedDistance,estimated_price,local_subpackage,(select MobNo from tbluserinfo where  MobNo='$Callerid' order by ID desc Limit 1) as CustMobile ,(select AlternateContactNo from tbluserinfo where  MobNo='$Callerid' order by ID desc Limit 1) as CustAlternateNo, UserName as CustomerName FROM `tblcabbooking` JOIN tblcabstatus ON tblcabstatus.status_id=tblcabbooking.`status` LEFT JOIN tbldriver ON tblcabbooking.pickup=tbldriver.uid WHERE tblcabstatus.`type`='cab' and MobileNo='$Callerid' order by tblcabbooking.ID desc Limit 1";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}	
	public function FareRevised()				
	{
		//CALL sp_farerevised('$Cab')
		$Cab = $_REQUEST['Cab'];
		$qry = "select MinimumCharge,Per_Km_Charge,Min_Distance,tripCharge_per_minute,Min_Distance,WaitingCharge_per_minute from tblbookingbill where CabType ='$Cab'";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		
		while($info = mysqli_fetch_object($result))
		{
			$searchArray=array("[","]");
			//$info->rate_upto_distance=str_replace($searchArray,"",$info->rate_upto_distance);
			//$info->waitingfee_upto_minutes=str_replace($searchArray,"",$info->waitingfee_upto_minutes);
			//$info->extras=str_replace($searchArray,"",$info->extras);
			//$info->postcode_to_postcode_fair=str_replace($searchArray,"",$info->postcode_to_postcode_fair);
			$record[]=$info;			
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}
    public function PackageAddress()				
	{
		//CALL sp_package_address('$packagename')
		 $packagename = str_replace('_','/',$_REQUEST['packagename']);
		 $packagename = str_replace('$',' ',$packagename);
		//$qry = "SELECT * FROM `tblbillconfigpackage` WHERE  Sub_package='$packagename'";		
		$qry = "SELECT tblbillconfigpackage.Address,tblbillconfigpackage.sub_package as Area,tblstates.state,tblbillconfigpackage.Zone  FROM `tblbillconfigpackage`
		INNER JOIN tblstates ON tblbillconfigpackage.PackageCityCode=tblstates.id
		WHERE  Package='$packagename'";
		$result = mysqli_query($this->con, $qry);
		//header('Content-type:text/html;charset=utf-8');
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}		
	// Find the text and text id from from tblregiontext table.
	public function HomepageText()				
	{
		$qry = "CALL sp_homepage_text()";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}	
	public function BillMail()				
	{
		//CALL sp_bill_mail('$Callerid')
		$Callerid = $_REQUEST['Callerid'];
		$qry = "Select * from tblcabbooking where MobileNo='$Callerid' order by ID desc Limit 1";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}	
	public function NewCheck()				
	{
		//5380;HC
		$Value = $_REQUEST['Value'];
		$val1 = explode(';',$Value);
		$val = $val1[0];
		 $initial = $val1[1];
		
		//// Code for year Starts Here ///
		$dateYear=date('y');
		$dateYear=64+$dateYear;
		$dateYear=chr($dateYear);
		//// Code for year Ends Here ///
		
		//// Code for Month Starts Here ////////
		$dateMonth=date('m');
		$dateMonth=64+$dateMonth;
		$dateMonth=chr($dateMonth);
		//// Code for Month Ends Here///////
		
		$final1=str_pad($val,4,0,STR_PAD_LEFT);
		$record=array();
		
		if($val>=10000){
		$divide=floor($val/10000);
		$next=$val-($divide*10000);
		$aa=64+$divide;
		$neww=chr($aa);
		$final=str_pad($next,4,0,STR_PAD_LEFT);
		$id=$initial.''.$dateYear.''.$dateMonth.''.$neww.''.$final;
		$record[]['generated']=$id;
		$sql="UPDATE tblcabbooking SET booking_reference='$id' WHERE id='$val'";
		$result = mysqli_query($this->con, $qry);
		$status="true";
		}else{
		$id=$initial.''.$dateYear.''.$dateMonth.''.$final1;
		$record[]['generated']=$id;
		$sql="UPDATE tblcabbooking SET booking_reference='$id' WHERE id='$val'";
		$result = mysqli_query($this->con, $sql);
		$status="true";
		}
		
		
		/////////////////////
		
		
		
		/*$qry = "CALL new_check('$val','$initial')";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}*/
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}	
	public function ReturnMailbill()				
	{
		//CALL sp_bill_mail_return('$Callerid')
		$Callerid = $_REQUEST['Callerid'];
		$qry = "Select * from tblcabbooking where booking_reference='$Callerid' order by ID desc Limit 1";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}	
	public function ShowCabbooking()				
	{
		//CALL Sp_cabbookingshow()
		$qry = "SELECT tblcabbooking.ID,booking_reference,EmailId,MobileNo,PickupArea,DropArea,PickupDate,BookingDate,CarType,PickupAddress,DestinationAddress, Concat(UserName,' (',count(*),')') as Name,tblcabstatus.`status` as `status`,tblcabstatus.`status` as `newstatus` FROM `tblcabbooking` JOIN tblcabstatus ON tblcabstatus.status_id=tblcabbooking.`status` WHERE tblcabstatus.`type`='cab' Group by MobileNo Order By ID desc limit 10";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}	
	
	public function CallerBalance()				
	{
		////CALL sp_callerbalance('$caller')
		$caller = $_REQUEST['caller'];
		$qry = "select amount from tbluser where UserNo = '$caller' order by ID desc limit 1;";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}	
	public function ShowFill()				
	{
		//CALL sp_FillShow('$BookingId')
		$BookingId = $_REQUEST['BookingId'];
		$qry = "Select tbldriver.FirstName,
(select AlternateContactNo from tbluserinfo inner join tblcabbooking on tblcabbooking.MobileNo=tbluserinfo.MobNo where  tblcabbooking.ID='$BookingId' order by tblcabbooking.ID 
desc Limit 1) as custMobile,(select AlternateContactNo from tbluserinfo inner join tblcabbooking on tblcabbooking.MobileNo=tbluserinfo.MobNo where  tblcabbooking.ID='$BookingId' order by tblcabbooking.ID 
desc Limit 1) as custAltno, tbldriver.VehicleRegistrationNo,tblcabbooking.* ,tblcabstatus.`status` as `status`,tblcabstatus.`status` as `newstatus` FROM `tblcabbooking` JOIN tblcabstatus ON tblcabstatus.status_id=tblcabbooking.`status` LEFT JOIN tbldriver ON tblcabbooking.pickup=tbldriver.uid WHERE tblcabstatus.`type`='cab' and tblcabbooking.ID='$BookingId' ";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}	
	
   public function PackageCity()				
	{
		//CALL sp_PackageCity('$cityNa')
	      $cityNa = str_replace('_',' ',$_REQUEST['cityNa']);
		 $PackageNa = str_replace('_','/',$_REQUEST['PackageNa']);
		  $PackageNa = str_replace('$',' ',$PackageNa);
		  if($PackageNa==103){
			  $qry = "select tblbillconfigpackage.* from tblbillconfigpackage 
		INNER JOIN tblstates ON tblbillconfigpackage.PackageCityCode=tblstates.id
		where tblstates.state='$cityNa' and tblbillconfigpackage.Package='$PackageNa'";
		  }else{
			   $qry = "select distinct city from rt_locations";
		  }
		
		$result = mysqli_query($this->con, $qry);
		//header('Content-type: text/html; charset=utf-8');
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		
		while($info = mysqli_fetch_object($result))
		{
			
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}		
	public function ShowNewpackege()				
	{
		//CALL sp_package_show_new('$CityCode')		
		$stateName = str_replace('_',' ',$_REQUEST['CityCode']);
		//$qry = "select distinct Package from tblbillconfigpackage where PackageCityCode='$CityCode'";
		$qry = "select distinct Package,master_package from tblbillconfigpackage where PackageCityCode=(select id from tblstates where state='$stateName')";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}	
	public function CityList()				
	{
		//CALL Sp_cityList()
		$qry = "select distinct city from rt_locations where City is not null";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}
	public function ZoneList()				
	{
		//CALL SP_ZoneList('$cityValue')
		$cityValue = str_replace('_',' ',$_REQUEST['cityValue']);
		$qry = "select distinct zone from rt_locations where  city='$cityValue' and Zone is not null";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}	
	public function GetAreaDetail()				
	{
		//CALL sp_GetAreaDetail('$CityNameValue','$CityZone')
		$CityNameValue = str_replace('_',' ',$_REQUEST['CityNameValue']);
		//$CityZone = str_replace('_',' ',$_REQUEST['CityZone']);
		
		$qry = "select area from rt_locations where city='$CityNameValue' and city is not null";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}	
	public function GetLandMarkAddress()				
	{
		//CALL GetLandMarkAddress('$PiCity','$PiZone','$PiArea')
		$PiCity = str_replace('_',' ',$_REQUEST['PiCity']);
		//$PiZone = str_replace('_',' ',$_REQUEST['PiZone']);
		$PiArea = str_replace('_',' ',$_REQUEST['PiArea']);
		
		$qry = "select distinct Address from rt_locations where city='$PiCity' and area='$PiArea' order by id desc limit 1";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}	
	public function GetLatLon()				
	{
		//CALL sp_getLatLon('$CityName','$ZoneName','$AreaName')
		$CityName = str_replace('_',' ',$_REQUEST['CityName']);
		$ZoneName = str_replace('_',' ',$_REQUEST['ZoneName']);
		$AreaName = str_replace('_',' ',$_REQUEST['AreaName']);
		
		$qry = "select  Lat,Lon  from rt_locations where city='$CityName' and Zone='$ZoneName' and area='$AreaName'  and city is not null and zone is not null  and area is not null";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}	
	public function GetLatLotAddress()				
	{
		//CALL GetLatLotAddress('$PiCity','$PiZone','$PiArea','$PiAddress')
		$PiCity = $_REQUEST['PiCity'];
		$PiZone = $_REQUEST['PiZone'];
		$PiArea = $_REQUEST['PiArea'];
		$PiAddress = $_REQUEST['PiAddress'];
		
		$qry = "select lat ,lon  from rt_locations where city='$PiCity' and zone='$PiZone' and area='$PiArea' and Address='$PiAddress' order by id desc limit 1";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}	
	
	public function UpdateAgentBooking()
	{	
	    // string strval =     "1;"   +  "0;"    +"0;"     +"1;"     +"0;"      + "0;"   +"0;" +CSIR + ";"+month +";"+year ;
	    //string value = "BookTotal; BookOpe; BookEnq; BookRej; BookComp; BookResec; BookCan; LoginId; AMonth; AYear";
		/* $Value = $_REQUEST['Value'];
		 $val	=	explode(';',$Value);
	    //CALL Sp_UpdateAgentBooking()
		$BookTotal= $val[0];
		$BookOpe = $val[1];
		$BookEnq = $val[2];	
		$BookRej = $val[3];
		$BookComp= $val[4];
		$BookResec = $val[5];
		$BookCan= $val[6];
		$LoginId = $val[7];
		$AMonth= $val[8];
		$AYear = $val[9]; */
		$BookTotal= $_REQUEST['BookTotal'];
		$BookOpe =$_REQUEST['BookOpe'];
		$BookEnq = $_REQUEST['BookEnq'];
		$BookRej = $_REQUEST['BookRej'];
		$BookComp= $_REQUEST['BookComp'];
		$BookResec = $_REQUEST['BookResec'];
		$BookCan=  $_REQUEST['BookCan'];
		$LoginId = $_REQUEST['LoginId'];
		$AMonth= $_REQUEST['AMonth'];
		$AYear = $_REQUEST['AYear'];
		
		$qry ="insert into agent_Booking_detail (Agent_Id,Target_Booking,Total_Booking,Booking_operated,Enquiry_Booking,Booking_rejection,Complaint_booking,Rescehdule_booking, Cancel_Booking, Login_Time , Break_Time,Booking_Month,Booking_year) select LoginId,200 ,0 ,0,0, 0,0,0,0, 0,0,AMonth,AYear from agent_Booking_detail   where Booking_month='$AMonth' and Booking_year='$AYear' and  Agent_Id='$LoginId' having Count(Agent_Id) = 0";
     $fetch1=mysqli_query($this->con,$qry);
		
		 $query="update  agent_Booking_detail set  Total_Booking = Total_Booking + '$BookTotal' ,Booking_operated  = Booking_operated + '$BookOpe',Enquiry_Booking  = Enquiry_Booking + '$BookEnq' , Booking_rejection  = Booking_rejection + '$BookRej',Complaint_booking  = Complaint_booking + '$BookComp',Rescehdule_booking  = Rescehdule_booking + '$BookResec',Cancel_Booking  = Cancel_Booking + '$BookCan' where Agent_Id='$LoginId' and Booking_month='$AMonth' and Booking_year='$AYear'";
		$fetch=mysqli_query($this->con,$query);
		if($fetch==1)
			$status="true";
		else
			$status="false";
		
			return array("status"=>$status);
	}
	public function Homesc()
	{	   
		//string value = "BookTotal; BookOpe; BookEnq; BookRej; BookComp; BookResec; BookCan; LoginId; AMonth; AYear";
		
		 /*CallerId; TaxiType1 ;CallerName; Email;CustType;Company ;Alt_No;No_of_taxi;PickupDate; PickupTime; Priority ;Pcountry ;Pstate; Pcity;Pzone ;Paddress ;PLandmark ;
		 Dcountry; Dstate; Dcity; Dzone; Daddress ;Dlandmark;Reason;;Remark; TaxiType ;PrmotionalMessage;BookingDate;PickupLatitude;PickupLongtitude;DestinationLatitude; 
		 DestinationLongtitude;EstimatedDistance;useragent;parterner ;User_Type ;adult ;child ;luggage ;CSR_ID;BookingType ;status;CabFor; Return1 ;ReturnDate; ReturnTime,
		 refnoo,approx_distance_charg,approx_aftek,approx_waiting_charg,appox_waiting_minut,faretotal,PromoCode,PromeName
7533060985;0;KKKKKKKK;kumarkuldeep130@gmail.com;Individual;None;8888888888;1;2015-09-25;12:18;High ;India;Delhi-NCR;Noida;Gautam Buddh Nagar;sector 62;fortice;matrostation;
India;Delhi-NCR;Gurgaon;Gurgaon;iffco chowk;matrostation;blank;None;0;hhhhhhhhhhh;9/25/2015 11:18:27 AM;28.6367043;77.2869368;28.6367043;77.2869368;15;Call Center App;3;1;1;
1;2;13;102;50;0;0;0;0;hc3333;0;10;1;10;None;None*/
		$Value = $_REQUEST['Value'];
		$val	=	explode(';',$Value);
	    //CALL Sp_homesc()
		$CallerId				=	$val[0];
		$TaxiType1				=	$val[1];
		$CallerName 			=	$val[2];
		$Email					=	$val[3];
		$CustType 				=	$val[4];	
		$Company				=	$val[5];
		$Alt_No					=	$val[6];
		$No_of_taxi				=	$val[7];
		$PickupDate				=	$val[8];
		$PickupTime 			=	$val[9];
		$Priority				=	$val[10];
		$Pcountry 				=	$val[11];
		$Pstate					= 	$val[12];
		$Pcity					=	$val[13];
		$Pzone					=	$val[14];	
		$Paddress				=	$val[15];
		$PLandmark				= 	$val[16];
		$Dcountry				=	$val[17];		
		$Dstate					=	$val[18];
		$Dcity					=	$val[19];
		$Dzone					=	$val[20];
		$Daddress				=	$val[21];
		$Dlandmark				=	$val[22];
		//$Dlandmark1				=	$val[23];
		$Reason					=	$val[23];
		$Remark					=	$val[24];	
		$TaxiType				=	$val[25];
		$PrmotionalMessage		=	$val[26];
		$BookingDate 			=	$val[27];
		$PickupLatitude			=	$val[28];
		$PickupLongtitude 		=	$val[29];
		$DestinationLatitude	=	$val[30];
		$DestinationLongtitude	=	$val[31];
		$EstimatedDistance		=	$val[32];	
		$useragent				=	$val[33];
		$parterner				=	$val[34];
		$User_Type				=	$val[35];
		$adult					=	$val[36];
		$child					=	$val[37];
		$luggage				=	$val[38];
		$CSR_ID					=	$val[39];		
		$BookingType			=	$val[40];
		$status					=	$val[41];
		$CabFor 				=	$val[42];
		
		$Return1				=	$val[43];
		$ReturnDate 			=	$val[44];
		$ReturnTime				= 	$val[45];	
		
		$refnoo 				=	$val[46];
		$approx_distance_charg	=	$val[47];
		$approx_aftek			=	$val[48];
		$approx_waiting_charg	=	$val[49];
		$appox_waiting_minut 	=	$val[50];
		$faretotal				= 	$val[51];
		$PromoCode				= 	$val[52];
		$PromeName				=	$val[53];
		
////////////// Query to check the user Exist or not Starts Here ///////////
		$sql_sel="SELECT * FROM tbluser WHERE LoginName='$Email' LIMIT 0,1"; 
		$no_of_rows=mysqli_query($this->con,$sql_sel); 
		//echo mysqli_num_rows($no_of_rows); die;
		if(mysqli_num_rows($no_of_rows)==0){
			$sql_ins="INSERT INTO tbluser (`LoginName`,`UserNo`,`UserType`) VALUES('$Email','$CallerId',`$User_Type`)";
			mysqli_query($this->con,$sql_ins);
			$user_id=mysqli_insert_id($this->con);
			$sql_usrinfo="INSERT INTO tbluserinfo(`FirstName`,`UID`,`MobNo`,`AlternateContactNo`,`Email`) VALUES('$CallerName','$user_id','$CallerId','$Alt_No','$Email')";
			mysqli_query($this->con,$sql_usrinfo); 
		}else{
			$result_new=mysqli_fetch_array($no_of_rows);
			$user_id=$result_new['ID'];
		}
		
////////////// Query to check the user Exist or not Ends Here ///////////

$query="insert into tblcabbooking (ClientID,CarType,UserName,EmailId,
CustType,Company,MobileNo,No_of_taxies,PickupDate,PickupTime,Priority,PickupCountry,PickupState,PickupCity,PickupZone,PickupArea,PickupAddress,
PickupLatitude,PickupLongtitude,DestinationCountry,DestinationState,DestinationCity,DestinationZone,DropArea,DestinationAddress,DropAddress,
DestinationLatitude,DestinationLongtitude,Reason,Remark,client_note,TaxiType1,BookingDate,
EstimatedDistance,useragent,partner,No_Of_Adults,No_Of_Childs,No_Of_Luggages,CSR_ID,BookingType,
StatusC,CabFor,ReturnDate,ReturnTime,approx_distance_charge,approx_after_km,approx_waiting_charge,appox_waiting_minute,refno,estimated_price,PrometionalCode,PrometionalName)
Values
 ($user_id,'$TaxiType1','$CallerName','$Email','$CustType','$Company','$CallerId','$No_of_taxi','$PickupDate','$PickupTime','$Priority','$Pcountry','$Pstate','$Pcity',
 '$Pzone','$Paddress','$PLandmark','$PickupLatitude','$PickupLongtitude','$Dcountry','$Dstate','$Dcity','$Dzone','$Daddress','$Dlandmark','$Dlandmark','$DestinationLatitude',
 '$DestinationLongtitude','$Reason','$Remark','$PrmotionalMessage','$TaxiType1','$BookingDate','$EstimatedDistance','$useragent','$parterner','$adult','$child','$luggage',
 '$CSR_ID','$BookingType','$status','$CabFor','$ReturnDate','$ReturnTime','$approx_distance_charg','$approx_aftek','$approx_waiting_charg','$appox_waiting_minut','$refnoo','$faretotal','$PromoCode',
 '$PromeName')"; 
		$fetch=mysqli_query($this->con,$query);
		$record=array();
		if($fetch==1){
			$status="true";
			$sql="select ID as pickid,ID as returnbookingid from tblcabbooking where MobileNo='$CallerId' order by ID desc limit 1";
			$result = mysqli_query($this->con, $sql);
			$info = mysqli_fetch_object($result);
			$record[]=$info;
		}else{
			$status="false";
			$record[]="";
		}
		return array("status"=>$status,"record"=>$record);
	}
	
	public function getLatLong()
	{	
		//CALL GetLatLotAddress()
		$city= $_REQUEST['city']; 
		$zone = $_REQUEST['zone'];
		$area= $_REQUEST['area'];
		$address = $_REQUEST['address'];
		$record=array();
		$query="select lat ,lon  from rt_locations where city='$city' and zone='$zone' and area='$area' and Address='$address' order by id desc limit 1";
		$fetch=mysqli_query($this->con,$query);
		$info = mysqli_fetch_object($fetch);
		if($info==1){
		$status="true";
		$record[]=$info;
		} else {
		$status="false";
		$record[]="";
		}
		return array("status"=>$status,"record"=>$record);
	}
	
	public function BookingDetails()
	{	
		//CALL sp_w_booking_details()
		$qry = "SELECT tblmasterpackage.iconImage,tblbookingcharges.totalBill AS amount,(tblbookingcharges.totalBill*tblbookingcharges.companyshare/100) 
AS cAmount,(tblbookingcharges.totalBill*tblbookingcharges.drivershare/100) AS dAmount,
(tblbookingcharges.totalBill*tblbookingcharges.partnershare/100) AS pAmount,
tblbookingcharges.companyshare AS cshare,tblbookingcharges.drivershare AS dshare,
tblbookingcharges.partnershare AS pshare,tblcomplaint.ID as com_id,
tblcomplaint.StatusID as com_status, tblcabbooking.CSR_ID as emp,
CONCAT(tblcabbooking.DropAddress,tblcabbooking.DropArea) as drop_area,tblcabbooking.ID as id,
tblbookingcharges.totalBill, tblcabbooking.booking_reference as ref,
tblmasterpackage.Master_Package as booking_type,
CONCAT(tblcabbooking.PickupDate,' ',tblcabbooking.PickupTime) as ordertime,
concat(tblclient.clientName,'(',tblappid.`type`,')') as partner,
REPLACE(rt_cabmanager_cabs.name,'·','') as vehicle,
CONCAT(tbluserinfo.FirstName,' ',tbluserinfo.LastName) as clientname,
CONCAT(tbldriver.FirstName,' ',tbldriver.LastName) as driver_name, 
tblcabbooking.MobileNo as mob_no,tbluserinfo.UID as client_id,tbldriver.UID as driver_id,
CONCAT(tblcabbooking.PickupAddress,' ',tblcabbooking.PickupArea) as departure,
tbldriver.VehicleRegistrationNo as reg,tblcabstatus.`status` as `status`
 FROM tblcabbooking JOIN tblcabstatus ON tblcabbooking.`status`=tblcabstatus.`status_id` 
 JOIN tbluserinfo ON tbluserinfo.UID=tblcabbooking.ClientID 
 JOIN tblmasterpackage ON tblcabbooking.BookingType=tblmasterpackage.Package_Id 
 LEFT JOIN tbldriver ON tblcabbooking.pickup=tbldriver.uid 
 LEFT JOIN rt_cabmanager_cabs ON tbldriver.TypeOfvehicle=rt_cabmanager_cabs.id 
 JOIN tblappid ON tblappid.id=tblcabbooking.partner 
 JOIN tblclient ON tblappid.clientId=tblclient.id 
 LEFT JOIN tblbookingcharges ON tblcabbooking.ID=tblbookingcharges.BookingID 
 LEFT JOIN tblcomplaint ON tblcabbooking.booking_reference=tblcomplaint.BookingID 
 WHERE tblcabstatus.`type`='cab' and tblcabbooking.`pickupdate`>=CURDATE() and tblcabbooking.`status` <=8 order by tblcabbooking.ID desc";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra; 
	}
	
	//////////////// CHANGE AFTER KULDEEP BY MUNNESH //////////////////////////
	
	
	public function DropCityText()
	{	
		//CALL sp_dropCityText()
		$CityName = str_replace('_',' ',$_REQUEST['CityName']);
		$qry = "select distinct city from rt_locations where city like '$CityName%'";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra; 
	}
	public function localPickCityText()
	{	
		//CALL sp_dropCityText()
		$CityName = str_replace('_',' ',$_REQUEST['CityName']);
		$StateName = str_replace('_',' ',$_REQUEST['StateName']);
		$qry = "select distinct city from rt_locations where state='$StateName' and city like '$CityName%'";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra; 
	}
	
	public function PickupCityName()
	{	
		//CALL Sp_CityName()
		$StateName = str_replace('_',' ',$_REQUEST['StateName']);
		if($StateName=='Delhi-NCR')
		$tbl="tbl_Delhi_ncr_cityName";
		else
		$tbl="rt_locations where state='$StateName'"; 	
		$qry = "select distinct City from $tbl";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra; 
	}
	
	
	
	
	public function AddressName()
	{	
		//CALL Sp_AddressName()
		$CityName = str_replace('_',' ',$_REQUEST['CityName']);
		$AreaName = str_replace('_',' ',$_REQUEST['AreaName']);
		$AddressName = str_replace('_',' ',$_REQUEST['AddressName']);
	   $qry = "select distinct Address from rt_locations where city='$CityName' and area='$AreaName' and  address like '$AddressName%'";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra; 
	}
	
	
	public function FetchAreaName()
	{	
		$CityName = str_replace('_',' ',$_REQUEST['CityName']);
		$AreaName = str_replace('_',' ',$_REQUEST['AreaName']);
		 $qry = "select distinct Area from rt_locations where city='$CityName' and  area like '$AreaName%'";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra; 
	}
	
	public function FetchAreaLatLot()
	{	
		$CityName = str_replace('_',' ',$_REQUEST['CityName']);
		$AreaName = str_replace('_',' ',$_REQUEST['AreaName']);
		$AddressName = str_replace('_',' ',$_REQUEST['AddressName']);
		$Full_Address=$AddressName." ".$AreaName." ".$CityName;
		$find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($Full_Address));
			file_put_contents("json.txt",$find_pickup); 					
			$enc=json_decode($find_pickup);
			if($enc->status == 'OK'){
				$lat=$enc->results[0]->geometry->location->lat;
				$long=$enc->results[0]->geometry->location->lng;				
			foreach($enc->results[0]->address_components as $v){
					if($v->types[0]=="locality")						
						{							
							$city=$v->long_name;
						}
					if($v->types[0]=="administrative_area_level_2")						
						{							
							$zone=$v->long_name;
						}        
				    if($v->types[0]=="country")						
						{							
							$country=$v->long_name;
						}       
					if($v->types[0]=="administrative_area_level_1")						
						{							
							$state=$v->long_name;
						}
					if($v->types[0]=="postal_code")						
					{							
						$postal_code=$v->long_name;
					}
				}				
				$data["latitude"]=$lat;
				$data["longitude"]=$long;
				$data["city"]=$city;
				$data["state"]=$state;
				$data["country"]=$country;
				$data["zone"]=$zone;
				$data["postal_code"]=$postal_code;
				$datanew[]=$data;
			    return array("status"=>true,"data"=>$datanew);
			}else{
				return array("status"=>false,"message"=>"Invalid Address");
			}
				
	}
   public function FetchDistance()
	{	
		$Origin_lat = trim($_REQUEST['Origin_lat']);
		$Origin_long = trim($_REQUEST['Origin_long']);
		$Desti_lat = trim($_REQUEST['Desti_lat']);
		$Desti_long = trim($_REQUEST['Desti_long']);		
		$deeeta= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$Origin_lat.','.$Origin_long."&destination=".$Desti_lat.','.$Desti_long."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584");
			$enc=json_decode($deeeta);			
			if($enc->status == 'OK')
			{
				$enc2=$enc->routes[0];
				$distance=round((($enc2->legs[0]->distance->value)/1000),1);				
				$data["distance"]=$distance;				
				$datanew[]=$data;
			    return array("status"=>true,"data"=>$datanew);
			}
			else
			{
				return array("status"=>false,"message"=>"Invalid Address");
			}
				
	}
     public function FetchMapDataBySearching()
	{	
		$Package = str_replace('_','/',$_REQUEST['Package']);
		$Package = str_replace('$',' ',$Package);
		$sub_package = str_replace('_',' ',$_REQUEST['sub_package']);
		$PackagecityCode = $_REQUEST['PackagecityCode'];
		 $qry = "SELECT sub_package FROM `tblbillconfigpackage` WHERE  Package='$Package' and PackagecityCode='$PackagecityCode' and sub_package like '$sub_package%'";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra; 
	}
	public function FetchMapDatafull()
	{	
		$Package = str_replace('_','/',$_REQUEST['Package']);
		$Package = str_replace('$',' ',$Package);
		$sub_package = str_replace('_',' ',$_REQUEST['sub_package']);
		$PackagecityCode = $_REQUEST['PackagecityCode'];
		 $qry = "SELECT tblbillconfigpackage.Address,tblbillconfigpackage.sub_package as Area,tblstates.state ,tblbillconfigpackage.zone FROM `tblbillconfigpackage`
		 INNER JOIN tblstates ON tblbillconfigpackage.PackageCityCode=tblstates.id
		 WHERE  Package='$Package' and PackagecityCode='$PackagecityCode' and sub_package ='$sub_package'";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra; 
	}
	public function FetchAirportPkgDetail()
	{		
		$Package = str_replace('_','/',$_REQUEST['Package']);
		$Package = str_replace('$',' ',$Package);
		$state = str_replace('_',' ',$_REQUEST['state']);
		if($Package=='103'){
			$qry = "SELECT tblbillconfigpackage.Address,tblbillconfigpackage.Area as Area,tblstates.state as CityName ,tblbillconfigpackage.zone,tblbillconfigpackage.latitude,tblbillconfigpackage.longitude FROM `tblbillconfigpackage`
		 INNER JOIN tblstates ON tblbillconfigpackage.PackageCityCode=tblstates.id
		 WHERE  tblbillconfigpackage.master_package='$Package' and tblstates.state='$state'";
		}else{
			if($state=="Delhi NCR"){
				$qry = "SELECT name as City from tblcity where state=(select id from tblstates where state='$state')";
			}else{
				$qry = "SELECT distinct city from rt_locations where state='$state'";
			}		
		}        
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra; 
	}
	 public function FetchFullAddressBySearching()
	{	
		$Package_id =  $_REQUEST['Package_id'];
		$stateName = str_replace('_',' ',$_REQUEST['stateName']);
		$Area = str_replace('_',' ',$_REQUEST['Area']);
		$address = $_REQUEST['address'];		
		$qry = "SELECT tblbillconfigpackage.* FROM `tblbillconfigpackage` 
		INNER JOIN tblstates ON tblbillconfigpackage.PackageCityCode=tblstates.id 
		 WHERE  tblbillconfigpackage.master_package='$Package_id' and tblbillconfigpackage.Address like'$address%' and tblbillconfigpackage.Area='$Area' and tblstates.state='$stateName'";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra; 
	}
	public function FetchLocalSub_Package()
	{		
		$Package = str_replace('_','/',$_REQUEST['Package']);
		$Package = str_replace('$',' ',$Package);
		$state = str_replace('_',' ',$_REQUEST['state']);
		$CabType =  $_REQUEST['CabType'];			
		$qry = "SELECT  distinct tblbookingbill.package_info, tblbillconfigpackage.sub_package FROM `tblbillconfigpackage`
		 INNER JOIN tblbookingbill ON tblbillconfigpackage.PackageCityCode=tblbookingbill.CityId
		 INNER JOIN tblstates ON tblbillconfigpackage.PackageCityCode=tblstates.id
		 WHERE tblbillconfigpackage.master_package='$Package' and tblstates.state='$state' 
		 and tblbookingbill.BookingTypeId='$Package' and tblbookingbill.CabType='$CabType'";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		$eachRE=array();
		$boDATA=array();
		if($Package==101){
				$i=1;
			while($info = mysqli_fetch_assoc($result)){ 
			    $record[$i]["sub_package"]=$info["sub_package"];
				$pakcage_info=$info["package_info"];
				 $exactStr=substr($pakcage_info,1,-1); 
				//$newArra=explode(',',$exactStr);
				$record[$i]["data"]=str_replace('/','',str_replace('"','',$exactStr));				
				$i++;
		    } 			
		}else{			 
			while($info = mysqli_fetch_assoc($result)){			   
				$record[]["sub_package"]=$info["sub_package"];
			}
		}		
		//hours,km,charge
		$arra=array("status"=>"$status","record"=>$record);
		return $arra; 
	}
	/* {		
		$Package = str_replace('_','/',$_REQUEST['Package']);
		$Package = str_replace('$',' ',$Package);
		$state = str_replace('_',' ',$_REQUEST['state']);
		$CabType =  $_REQUEST['CabType'];			
		$qry = "SELECT  distinct tblbookingbill.package_info, tblbillconfigpackage.sub_package FROM `tblbillconfigpackage`
		 INNER JOIN tblbookingbill ON tblbillconfigpackage.PackageCityCode=tblbookingbill.CityId
		 INNER JOIN tblstates ON tblbillconfigpackage.PackageCityCode=tblstates.id
		 WHERE tblbillconfigpackage.master_package='$Package' and tblstates.state='$state' 
		 and tblbookingbill.BookingTypeId='$Package' and tblbookingbill.CabType='$CabType'";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		$eachRE=array();
		$boDATA=array();
		if($Package==101){
			while($info = mysqli_fetch_assoc($result)){ 
			       $record[]["sub_package"]=$info["sub_package"];
                	$b = $info["sub_package"];	
                 $int = filter_var($info["sub_package"], FILTER_SANITIZE_NUMBER_INT);
                  $val=explode('-',$int);
                  	 $sub_pack1=$val[0];
                 	 $sub_pack2=$val[1];				// die;
			    // $sub_pack = $info["sub_package"];
				//  $sub_pack1=substr($sub_pack,0,-13);
				 //$sub_pack2=substr($sub_pack,9,-2);
				 // $sub_pack = $sub_pack1."_".$sub_pack2."<br/>";
			     $pakcage_info=$info["package_info"]; 
			    //$exactStr=substr($pakcage_info,1,-1);
				//echo $abc = substr($exactStr,1,-31);
			 	$exactStr=substr($pakcage_info,1,-1);
			    $pkArray=explode(',',$exactStr);
				for($i=0;$i<count($pkArray);$i++){
					 $eachRE[]=explode('_',str_replace('"','',$pkArray[$i]));
					 //print_r($eachRE[]);
					//$boDATA[]["hours"]=$eachRE[$i][0];
					//$boDATA[]["km"]=$eachRE[$i][1];
					//$boDATA[]["charge"]=$eachRE[$i][2];
					//echo "<br/>";
					 $sub_pack1;
					//echo $eachRE[$i][0];
					//$a=intval($eachRE[$i][0]);
					if($sub_pack1 == intval($eachRE[$i][0]) and $sub_pack2 == intval($eachRE[$i][1]) )
					{
						//echo "Mohit";
						$record[]["hours"]=$eachRE[$i][0];
						$record[]["km"]=$eachRE[$i][1];
						$record[]["charge"]=$eachRE[$i][2];
					}
				} 
					//$record[]["sub_package"][]=$boDATA;
                    // echo $record[]["sub_package"];				
				//die;
		    }
                								
		}else{			 
			while($info = mysqli_fetch_assoc($result)){			   
				$record[]["sub_package"]=$info["sub_package"];			 
			}
		}	
		   $a=1;
		   $mainArr=array();
		   $mainArr= array_chunk($record, 4);
		  //$newJson=$mainArr;
		//hours,km,charge
		$arra=array("status"=>"$status","record"=>$mainArr);
		return $arra; 
	} */
public function CabBookingService()
	{
	$jsonstring = trim($_REQUEST['jsonstring']);
	$jsonstring =json_decode($jsonstring,true);
	//var_dump($jsonstring);  die;
			 //return array("Message"=>$jsonstring); exit();

$booking_type = $jsonstring['PackageCode'];
$Email=$jsonstring['Email'];
$CallerID=$jsonstring['CallerID'];
$UserType=$jsonstring['UserType'];
$CallerName=$jsonstring['CallerName'];
$AlternateNumber=$jsonstring['AlternateNumber'];
$CustomerType=$jsonstring['CustomerType'];
$RefrenceNo=$jsonstring['RefrenceNo'];
$CompanyName=$jsonstring['CompanyName'];
$NoOfTaxi=$jsonstring['NoOfTaxi'];
$NoOfAdult=$jsonstring['NoOfAdult'];
$NoOfChild=$jsonstring['NoOfChild'];
$NoOfLuggage=$jsonstring['NoOfLuggage'];
$PackageName=$jsonstring['PackageName'];
$PickUpTime=$jsonstring['PickUpTime'];
$PickUpDate=$jsonstring['PickUpDate'];
$Priority=$jsonstring['Priority'];
$ISReturn=$jsonstring['ISReturn'];
$PrmationalName=$jsonstring['PrmationalName'];
$PromotionalCode=$jsonstring['PromotionalCode'];
$PackageState=$jsonstring['PackageState'];
$LocalPackageName=$jsonstring['LocalPackageName'];
$PickupCountry=$jsonstring['PickupCountry'];
$PickStatename=$jsonstring['PickStatename'];
$PickUpZone=$jsonstring['PickUpZone'];
$PickUpCity=$jsonstring['PickUpCity'];
$PickUpArea=$jsonstring['PickUpArea'];
$PickUpAdderss=$jsonstring['PickUpAdderss'];
$PickAddressWrong=$jsonstring['PickAddressWrong'];
$Pick_or_Drop=$jsonstring['Pick_or_Drop'];
$PickUpLatitude=$jsonstring['PickUpLatitude'];
$PickUpLongitude=$jsonstring['PickUpLongitude'];
$DropCountry=$jsonstring['DropCountry'];
$DropStateName=$jsonstring['DropStateName'];
$DropZone=$jsonstring['DropZone'];
$DropCity=$jsonstring['DropCity'];
$DropArea=$jsonstring['DropArea'];
$DropAddress=$jsonstring['DropAddress'];
$DropAddressWrong=$jsonstring['DropAddressWrong'];
$DropLatitude=$jsonstring['DropLatitude'];
$DropLongitude=$jsonstring['DropLongitude'];
$OneWayDistance=$jsonstring['OneWayDistance'];
$Fare=$jsonstring['Fare'];
$ClientNotes=$jsonstring['ClientNotes'];
$CarType=$jsonstring['CarType'];
$EmailID=$jsonstring['EmailID'];
$EmailBill=$jsonstring['EmailBill'];
$PaymnetType=$jsonstring['PaymnetType'];
$BookingStatus=$jsonstring['BookingStatus'];
$AgentID=$jsonstring['AgentID'];
$BookingDate= date("Y-m-d H:i:s");
$BookingBy=$jsonstring['BookingBy'];
$PartnerType=$jsonstring['PartnerType'];
$Cabfor=$jsonstring['Cabfor'];
$approx_distance_charge=$jsonstring['approx_distance_charge'];
$approx_afterkm=$jsonstring['approx_afterkm'];
$approx_waiting_charge=$jsonstring['approx_waiting_charge'];
$appox_waiting_minut=$jsonstring['appox_waiting_minut'];
$local_subpackage=$jsonstring['LocalPackageName'];

					
			/**********Check User Exist or Not************/
				$sql_sel="SELECT * FROM tbluser WHERE LoginName='$EmailID' or UserNo='$CallerID' LIMIT 0,1"; 
		      $no_of_rows=mysqli_query($this->con,$sql_sel); 		     
				if(mysqli_num_rows($no_of_rows)==0){
					$sql_ins="INSERT INTO tbluser (`LoginName`,`UserNo`,`UserType`,`last_booking`) VALUES('$EmailID','$CallerID','$UserType','NOW()')";
					mysqli_query($this->con,$sql_ins);
					$user_id=mysqli_insert_id($this->con); 
					$sql_usrinfo="INSERT INTO tbluserinfo(`FirstName`,`UID`,`MobNo`,`AlternateContactNo`,`Email`) VALUES('$CallerName','$user_id','$CallerID','$AlternateNumber','$EmailID')";
					mysqli_query($this->con,$sql_usrinfo); 
				}else{
				
					$result_new=mysqli_fetch_array($no_of_rows);
					$user_id=$result_new['ID']; //
					$num_length = strlen((string)$AlternateNumber);
					if($num_length>7 and $num_length<13)
					{
						$sql_usrinfo="UPDATE  tbluserinfo set AlternateContactNo = '$AlternateNumber' where UID = '$user_id'";
					    mysqli_query($this->con,$sql_usrinfo);
					}
					
				}
				
				/**********Check Return True or Not************/
				if($ISReturn!="0"){
					 $ReturnDate=trim($_REQUEST["ReturnDate"]);
				     $ReturnTime=trim($_REQUEST["ReturnTime"]);
				}else{
					$ReturnDate='';
				    $ReturnTime='';
				}
				/********Check PickUp Address wrong or right********/
				if($PickAddressWrong=="true"){
					$sql_usrinfo="INSERT INTO `tblwrongaddress`(`address`,`area`,`zone`,`city`,`state`,`country`,`latitude`,`longitude`) VALUES
					('$PickUpAdderss','$PickUpArea','$PickUpZone','$PickUpCity','$PickStatename','$PickupCountry','$PickUpLatitude','$PickUpLongitude')";
					mysqli_query($this->con,$sql_usrinfo);
				}
				/********Check Drop Address wrong or right********/
				if($DropAddressWrong=="true"){
					 $sql_usrinfo="INSERT INTO `tblwrongaddress`(`address`,`area`,`zone`,`city`,`state`,`country`,`latitude`,`longitude`) VALUES
					('$DropAddress','$DropArea','$DropZone','$DropCity','$DropStateName','$DropCountry','$DropLatitude','$DropLongitude')";
					mysqli_query($this->con,$sql_usrinfo);
				}	
						
				//********Insert Booking Date in tblcabbooking ************No_of_taxies
				 $query="insert into tblcabbooking (ClientID,CarType,UserName,EmailId,
					CustType,Company,MobileNo,No_of_taxies,PickupDate,PickupTime,Priority,PickupCountry,PickupState,PickupCity,PickupZone,PickupArea,PickupAddress,
					PickupLatitude,PickupLongtitude,DestinationCountry,DestinationState,DestinationCity,DestinationZone,DropArea,DestinationAddress,DropAddress,
					DestinationLatitude,DestinationLongtitude,Reason,Remark,client_note,TaxiType1,BookingDate,
					EstimatedDistance,useragent,partner,No_Of_Adults,No_Of_Childs,No_Of_Luggages,CSR_ID,BookingType,
					StatusC,CabFor,ReturnDate,ReturnTime,approx_distance_charge,approx_after_km,approx_waiting_charge,appox_waiting_minute,refno,estimated_price,
					PrometionalCode,PrometionalName,charge_type,Local_package_Name,Package_State,DeviceType,local_subpackage,min_Distance,CabIn)
					Values 
					 ('$user_id','$CarType','$CallerName','$EmailID','$CustomerType','$CompanyName','$CallerID','$NoOfTaxi','$PickUpDate','$PickUpTime','$Priority','$PickupCountry','$PickStatename','$PickUpCity',
					 '$PickUpZone','$PickUpArea','$PickUpAdderss','$PickUpLatitude','$PickUpLongitude','$DropCountry','$DropStateName','$DropCity','$DropZone','$DropArea','$DropAddress','$DropAddress','$DropLatitude',
					 '$DropLongitude','$Reason','$Remark','$ClientNotes','$CarType','$BookingDate','$OneWayDistance','$BookingBy','$PartnerType','$NoOfAdult','$NoOfChild','$NoOfLuggage',
					 '$AgentID','$booking_type','$BookingStatus','$Cabfor','$ReturnDate','$ReturnTime','$approx_distance_charge','$approx_afterkm','$approx_waiting_charge',
					 '$appox_waiting_minut','$RefrenceNo','$Fare','$PromotionalCode',
					 '$PrmationalName','$PaymnetType','$LocalPackageName','$PackageState','CALLCENTER','$local_subpackage','$OneWayDistance','$PickUpCity')"; 
					 $insertSql=mysqli_query($this->con,$query);
					 if($insertSql>0){
						 $lastid=mysqli_insert_id($this->con);
						 $data=array();
						 //$return_booking_id='return_booking_id=""';
						 $data[]["booking_id"]=$lastid;
						 //$data[]["return_booking_id"]='';
						 $return=array("Status"=>true,"Data"=>$data);
					 }else{
						 $return=array("Status"=>false,"Data"=>'');
					 }
		   return $return; 	
	}
	
	
	////// API From Mohit Jain Starts Here ///////////////
	public function FetchLocalSub_Package1()
	{	
		$Package = str_replace('_','/',$_REQUEST['Package']);
		$Package = str_replace('$',' ',$Package);
		$state = str_replace('_',' ',$_REQUEST['state']);
		$CabType =  $_REQUEST['CabType'];			
		$qry = "SELECT  distinct tblbillconfigpackage.sub_package FROM `tblbillconfigpackage`
		 INNER JOIN tblbookingbill ON tblbillconfigpackage.PackageCityCode=tblbookingbill.CityId
		 INNER JOIN tblstates ON tblbillconfigpackage.PackageCityCode=tblstates.id
		 WHERE tblbillconfigpackage.master_package='$Package' and tblstates.state='$state' 
		 and tblbookingbill.BookingTypeId='$Package' and tblbookingbill.CabType='$CabType'";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		while($info = mysqli_fetch_assoc($result)){ 
			    $record[]["sub_package"]=$info["sub_package"];
		}
		
		return array("status"=>$status,"record"=>$record);
	}
	
	public function FetchLocalSub_Package2()
	{	
		$Package = str_replace('_','/',$_REQUEST['Package']);
		$Package = str_replace('$',' ',$Package);
		$state = str_replace('_',' ',$_REQUEST['state']);
		$CabType =  $_REQUEST['CabType'];			
		$qry = "SELECT  distinct tblbookingbill.package_info FROM `tblbillconfigpackage`
		 INNER JOIN tblbookingbill ON tblbillconfigpackage.PackageCityCode=tblbookingbill.CityId
		 INNER JOIN tblstates ON tblbillconfigpackage.PackageCityCode=tblstates.id
		 WHERE tblbillconfigpackage.master_package='$Package' and tblstates.state='$state' 
		 and tblbookingbill.BookingTypeId='$Package' and tblbookingbill.CabType='$CabType'";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		while($info = mysqli_fetch_assoc($result)){		
		$searchArray=array("[","]",'"');
		$info["package_info"]=str_replace($searchArray,"",$info["package_info"]);
		$record[]["package_info"]=$info["package_info"];
		}
		
		return array("status"=>$status,"record"=>$record);
	}
	////// API From Mohit Jain Ends Here ///////////////
    public function FareCalculate()
	{
		// URLLink = "http://bookingcabs.com/hello42/tunnel/BookingIVR/FareCalculate?CarType=" + cartype + "&StateName=" + staName + "&PackageCode=" + PacCode + "
		//&PickCityName=" + PickCiName + "&DropCityName=" + DropCiName + "&PickAreaName=" + arName + "&PickDropName=" + drName + "dropLocalPackage" + dropLocal;
		$isPickup =  $_REQUEST['isPickup']; //$_REQUEST['isPickup'];
		$CarType =  str_replace('_',' ',$_REQUEST['CarType']);
		//eco=1/prime=3/sedan=2	
		$StateName =  str_replace('_',' ',$_REQUEST['StateName']);
		//delhi		
		$PackageCode =  str_replace('_',' ',$_REQUEST['PackageCode']);
		//101/103/		
		$PickCityName =  str_replace('_',' ',$_REQUEST['PickCityName']);
		//new delhi
		$DropCityName =  str_replace('_',' ',$_REQUEST['DropCityName']);
        //new delhi
		$PickAreaName =  str_replace('_',' ',$_REQUEST['PickAreaName']);
		$DropAreaName =  str_replace('_',' ',$_REQUEST['DropAreaName']);
		$PickAddress =  str_replace('_',' ',$_REQUEST['PickAddress']);
		$DropAddress =  str_replace('_',' ',$_REQUEST['DropAddress']);
		$dropLocalPackage =  str_replace('_',' ',$_REQUEST['dropLocalPackage']);
        //4 hr - 40 km
		
			/* result = select * from tblairport where fic_point= fixpoint & address = address;
			if(result >0){
				
			}else{
				select 8 from tb
			} */
			
		if($PackageCode=='103' )
		{
			if($isPickup=='true')
				{
					//echo "hello";
					$fixpoint = $PickAreaName;
					$address = $DropAreaName;
					$city_name_value=$DropCityName;
				}
				else
				{
					$address = $PickAreaName;
					$fixpoint = $DropAreaName;
					$city_name_value=$PickCityName;
				}
				$record=array();
				$record1=array();
				
				
					 $Full_Address=$PickAddress." ".$PickAreaName." ".$PickCityName; 
		$find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($Full_Address));
			file_put_contents("json.txt",$find_pickup); 					
			$enc=json_decode($find_pickup);
			if($enc->status == 'OK'){
				$lat=$enc->results[0]->geometry->location->lat;
				$long=$enc->results[0]->geometry->location->lng;				
		
			}	
		$Full_Address1=$DropAddress." ".$DropAreaName." ".$DropCityName;
		$find_pickup1=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($Full_Address1));
			file_put_contents("json.txt",$find_pickup1); 					
			$enc1=json_decode($find_pickup1);
			if($enc1->status == 'OK'){
				$lat1=$enc1->results[0]->geometry->location->lat;
				$long1=$enc1->results[0]->geometry->location->lng;				
			}
		$deeeta= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$lat.','.$long."&destination=".$lat1.','.$long1."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584");
			$enc=json_decode($deeeta);			
			if($enc->status == 'OK')
			{
				$enc2=$enc->routes[0];
			 $distance=round((($enc2->legs[0]->distance->value)/1000),1);				
				/* $data["distance"]=$distance;				
				$datanew[]=$data;
			    return array("status"=>true,"data"=>$datanew);
			}
			else
			{
				return array("status"=>false,"message"=>"Invalid Address"); */
			}
			//echo $distance."<br/>";
		    $dist = round($distance); 
			 $qry = "select (select distinct Km from tblairportaddress where Km<='$dist' order by Km desc limit 1) as MinKm,
					(select Fare from tblairportaddress where Km<='$dist' order by Km desc limit 1) as MinFare,
					(select distinct Km from tblairportaddress where Km>='$dist' order by Km Asc limit 1) as MaxKm,
					(select Fare from tblairportaddress where Km>='$dist' order by Km Asc limit 1) as MaxFare from tblairportaddress limit 1"; 
			$result = mysqli_query($this->con, $qry);
			$info = mysqli_fetch_assoc($result);
		    $min_diff = $distance - $info['MinKm'];
			$max_diff =$info['MaxKm'] - $distance;
			$diff_distance = $info['MaxKm']-$info['MinKm'];
			if(($diff_distance/2) < $max_diff)
			{
				$data["MinKm"] = $info['MinKm'];
			    $data["MinFare"] = $info['MinFare'];
			}
			else
			{
				$data["MaxKm"] = $info['MaxKm'];
			    $data["MaxFare"] = $info['MaxFare'];
			}
			$data["Actual_distance"]="$distance";
					
					
			 $CallerIdCondition = "t1.BookingTypeId = '$PackageCode' and t1.CabType = '$CarType' and t3.state = '$StateName' and t4.name = '$city_name_value' and t5.Fix_point = '$fixpoint' and t5.Address = '$address'";
			 $qry2 = "select t1.Per_Km_Charge, t1.WaitingCharge_per_minute, t1.rate_upto_distance, t1.waitingfee_upto_minutes, 
		    t1.NightCharges, t1.night_rate_begins, t1.night_rate_ends, t1.Waitning_minutes, t3.state, t4.name, t5.Address, t5.Km as Min_Distance, t5.Fare as MinimumCharge
		    from tblbookingbill t1 inner join  tblstates t3 on t1.CityId = t3.id 
		    inner join tblcity t4 on t1.CityId = t4.state inner join tblairportaddress t5 on t3.id = t5.StateId where $CallerIdCondition";
		    $result2 = mysqli_query($this->con, $qry2);
			$num_rows = mysqli_num_rows($result2);
			if($num_rows>0)
			{
				   $status="true";
		
				while($info2 = mysqli_fetch_assoc($result2))
				{
					$data["Per_Km_Charge"] = $info2['Per_Km_Charge'];
					$data["Min_Distance"] = $info2['Min_Distance'];
					$data["MinimumCharge"] = $info2['MinimumCharge'];
					$data["WaitingCharge_per_minute"] = $info2['WaitingCharge_per_minute'];
					$data["rate_upto_distance"] = str_replace(array('[',']'),'',$info2['rate_upto_distance']);
				$data["waitingfee_upto_minutes"] = str_replace(array('[',']'),'',$info2['waitingfee_upto_minutes']);
					$data["NightCharges"] = $info2['NightCharges'];
					$data["night_rate_begins"] = $info2['night_rate_begins'];
					$data["night_rate_ends"] = $info2['night_rate_ends'];
					$data["Waitning_minutes"] = $info2['Waitning_minutes'];
					//$record[]=$info2;		  
				}
				$record[]=$data;
				$arra=array("status"=>"$status","record"=>$record);
				return $arra;
			}
			else
			{
				//echo "hello";
				
				 $CallerIdCondition = "t1.BookingTypeId = '$PackageCode' and t1.CabType = '$CarType' and t3.state = '$StateName' and t4.name = '$city_name_value'";
			$qry1 = "select t1.MinimumCharge, t1.Min_Distance, t1.Per_Km_Charge, t1.WaitingCharge_per_minute, t1.rate_upto_distance, t1.waitingfee_upto_minutes, 
		    t1.NightCharges, t1.night_rate_begins, t1.night_rate_ends, t1.Waitning_minutes, t3.state, t4.name 
		    from tblbookingbill t1 inner join  tblstates t3 on t1.CityId = t3.id 
		    inner join tblcity t4 on t1.CityId = t4.state where $CallerIdCondition"; 
				$result1 = mysqli_query($this->con, $qry1);
				$num_rows1 = mysqli_num_rows($result1);
			    if($num_rows1>0)
					$status="true";
				else 
				    $status="false";
				$record=array();
				while($info2 = mysqli_fetch_object($result1))
				{
					$data["Per_Km_Charge"] = $info2['Per_Km_Charge'];
					$data["Min_Distance"] = $info2['Min_Distance'];
					$data["MinimumCharge"] = $info2['MinimumCharge'];
					$data["WaitingCharge_per_minute"] = $info2['WaitingCharge_per_minute'];
					$data["rate_upto_distance"] = str_replace(array('[',']'),'',$info2['rate_upto_distance']);
				$data["waitingfee_upto_minutes"] = str_replace(array('[',']'),'',$info2['waitingfee_upto_minutes']);
					$data["NightCharges"] = $info2['NightCharges'];
					$data["night_rate_begins"] = $info2['night_rate_begins'];
					$data["night_rate_ends"] = $info2['night_rate_ends'];
					$data["Waitning_minutes"] = $info2['Waitning_minutes'];
					//$record[]=$info2;		  
				}
				$record[]=$data;
				$arra=array("status"=>"$status","record"=>$record);
				return $arra; 
			}
		}
        else
        {		
			if($PackageCode=='101')
				$CallerIdCondition = "t1.BookingTypeId = '$PackageCode' and t1.CabType = '$CarType' and t3.state = '$StateName' and t2.Sub_package = '$dropLocalPackage'"; 
			else
				$CallerIdCondition = "t1.BookingTypeId = '$PackageCode' and t1.CabType = '$CarType' and t3.state = '$StateName'";
			
			$qry = "select t1.MinimumCharge, t1.Min_Distance, t1.Per_Km_Charge, t1.WaitingCharge_per_minute, t1.rate_upto_distance, t1.waitingfee_upto_minutes, 
			 t1.NightCharges, t1.night_rate_begins, t1.night_rate_ends, t1.Waitning_minutes, t2.Sub_package, t3.state, t4.name 
			 from tblbookingbill t1 inner join tblbillconfigpackage t2 on t1.CityId = t2.PackageCityCode inner join tblstates t3 on t1.CityId = t3.id 
			 inner join tblcity t4 on t1.CityId = t4.state where $CallerIdCondition";
			
			$result = mysqli_query($this->con, $qry);
			$num_rows = mysqli_num_rows($result);
			if($num_rows>0)
				$status="true";
			else
				$status="false";
			$record=array();
			while($info = mysqli_fetch_object($result))
			{
				$record[]=$info;		  
			}
			$arra=array("status"=>"$status","record"=>$record);
			return $arra; 
		}
	}
	
	Public function getCity()
	{
		$StateName =  str_replace('_',' ',$_REQUEST['StateName']);
		$CityName =  str_replace('_',' ',$_REQUEST['CityName']);		
		$PackageCode =  $_REQUEST['PackageCode'];
		$qry = "select tblcity.name from tblstates inner join tblcity on tblstates.id = tblcity.state where tblstates.state = '$StateName' and  tblcity.name Like '$CityName%'";		
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra; 
	}	
	
	Public function findArea()
	{
		$CityName =  str_replace('_',' ',$_REQUEST['CityName']);	
		$StateName =  str_replace('_',' ',$_REQUEST['StateName']);	
		$PackageCode =  $_REQUEST['PackageCode'];	
		$AreaName =  str_replace('_',' ',$_REQUEST['AreaName']);
		// tblairportaddress.Km, tblairportaddress.Fare
		$qry = "select tblairportaddress.Address, tblairportaddress.Km, tblairportaddress.Fare from tblstates inner join tblcity on tblstates.id = tblcity.state inner join tblairportaddress on 
		tblairportaddress.StateId = tblstates.id  where tblstates.state = '$StateName' and tblcity.name = '$CityName' and tblairportaddress.Address Like '$AreaName%'";		
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		while($info = mysqli_fetch_object($result))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra; 
	}
	
	Public function DisposCall()
	{
		//sp_DisposCall
		$AgID =  $_REQUEST['AgID'];
        $CallID =  $_REQUEST['CallID'];
        $CallName =  $_REQUEST['CallName'];
		$CallTime =  $_REQUEST['CallTime'];
		$CustType =  $_REQUEST['CustType'];
		$AlterNo =  $_REQUEST['AlterNo'];
		$NoTaxi =  $_REQUEST['NoTaxi'];
		$NoPax =  $_REQUEST['NoPax'];
		$PickTime =  $_REQUEST['PickTime'];
		$CarType =  $_REQUEST['CarType'];
		$Reason =  $_REQUEST['Reason'];
		$Remarks =  $_REQUEST['Remarks'];
		
		$sql_usrinfo="insert into tbldispose_call( AgentID, CallerID, CallerName, CallerTime, CustType, AlternateNo, NoOfTaxi, NoOfPax, PickTime, CarType, Reason, Remarks,UpdateDateTime)
        values ('$AgID','$CallID','$CallName','$CallTime','$CustType','$AlterNo','$NoTaxi','$NoPax','$PickTime','$CarType','$Reason','$Remarks',Now());";
		$sqlInsert = mysqli_query($this->con,$sql_usrinfo);
		if($sqlInsert==1)
		$status="true";			
	    else
		$status="false";
		return array("status"=>$status);
	    
	}
	public function FetchDistance1()
	{	
		$isPickup =  'true'; //$_REQUEST['isPickup'];
		$CarType =  $_REQUEST['CarType'];
		//eco=1/prime=3/sedan=2	
		$StateName =  $_REQUEST['StateName'];
		//delhi		
		$PackageCode =  $_REQUEST['PackageCode'];
		//101/103/		
		$PickCityName =  $_REQUEST['PickCityName'];
		//new delhi
		$DropCityName =  $_REQUEST['DropCityName'];
        //new delhi
		$PickAreaName =  $_REQUEST['PickAreaName'];
		$DropAreaName =  $_REQUEST['DropAreaName'];
		$PickAddress =  $_REQUEST['PickAddress'];
		$DropAddress =  $_REQUEST['DropAddress'];
		$dropLocalPackage =  $_REQUEST['dropLocalPackage'];
		
		$Full_Address=$PickAddress." ".$PickAreaName." ".$PickCityName;;
		$find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($Full_Address));
			file_put_contents("json.txt",$find_pickup); 					
			$enc=json_decode($find_pickup);
			if($enc->status == 'OK'){
				$lat=$enc->results[0]->geometry->location->lat;
				$long=$enc->results[0]->geometry->location->lng;				
		
				//echo $data["latitude"]=$lat;
				//echo $data["longitude"]=$long; //die;
			}	
		$Full_Address1=$DropAddress." ".$DropAreaName." ".$DropCityName;;
		$find_pickup1=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($Full_Address1));
			file_put_contents("json.txt",$find_pickup1); 					
			$enc1=json_decode($find_pickup1);
			if($enc1->status == 'OK'){
				$lat1=$enc1->results[0]->geometry->location->lat;
				$long1=$enc1->results[0]->geometry->location->lng;				
		
				//echo $data["latitude1"]=$lat1;
				//echo $data["longitude1"]=$long1; //die;
			}
		$deeeta= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$lat.','.$long."&destination=".$lat1.','.$long1."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584");
			$enc=json_decode($deeeta);			
			if($enc->status == 'OK')
			{
				$enc2=$enc->routes[0];
			 $distance=round((($enc2->legs[0]->distance->value)/1000),1);				
				/* $data["distance"]=$distance;				
				$datanew[]=$data;
			    return array("status"=>true,"data"=>$datanew);
			}
			else
			{
				return array("status"=>false,"message"=>"Invalid Address"); */
			}
			echo $distance."<br/>";
		    $dist = round($distance); 
			$qry = "select (select distinct Km from tblairportaddress where Km<='$dist' order by Km desc limit 1) as MinKm,
					(select Fare from tblairportaddress where Km<='$dist' order by Km desc limit 1) as MinFare,
					(select distinct Km from tblairportaddress where Km>='$dist' order by Km Asc limit 1) as MaxKm,
					(select Fare from tblairportaddress where Km>='$dist' order by Km Asc limit 1) as MaxFare from tblairportaddress";
			$result = mysqli_query($this->con, $qry);
			$info = mysqli_fetch_array($result);
		    $min_diff = $distance - $info['MinKm'];
			 $max_diff =$info['MaxKm'] - $distance;
			 $diff_distance = $info['MaxKm']-$info['MinKm'];
			if(($diff_distance/2) < $max_diff)
			{
				$data["MinKm"] = $info['MinKm'];
			     $data["MinFare"] = $info['MinFare'];
			}
			else
			{
				 $data["MaxKm"] = $info['MaxKm'];
			    $data["MaxFare"] = $info['MaxFare'];
			}
			$data["distance"]=$distance;				
				$datanew[]=$data;
			    return array("status"=>true,"data"=>$datanew);
			//die;	
	}
	
	
	////////// API Services In Call Centre for Outstations Booking Starts here by Mohit Jain /////////////////
	
	public function FetchOutstationCities()
	{		
		$city = $_REQUEST['city'];			
		$qry = "SELECT distinct source_city from tbl_city_distance_list WHERE source_city Like '$city%'";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		while($info = mysqli_fetch_assoc($result)){ 
			    $record[]["O_city"]=$info["source_city"];
		}
		
		return array("status"=>$status,"record"=>$record);
	}
	
	public function FetchOutstationCitiesFare()
	{		
		$from_city = $_REQUEST['from_city'];	
		$to_city = $_REQUEST['to_city'];
		$cab_type = $_REQUEST['cab_type'];
		
		//////////////////
		
		$sourceCityAddress=$from_city."India";
		$find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($sourceCityAddress));
			file_put_contents("json.txt",$find_pickup); 					
			$enc=json_decode($find_pickup);
			if($enc->status == 'OK'){
				$source_city_lat=$enc->results[0]->geometry->location->lat;
				$source_city_long=$enc->results[0]->geometry->location->lng;				
		
			}	
			
		$destCityAddress=$to_city."India";
		$find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($destCityAddress));
			file_put_contents("json.txt",$find_pickup); 					
			$enc=json_decode($find_pickup);
			if($enc->status == 'OK'){
				$dest_city_lat=$enc->results[0]->geometry->location->lat;
				$dest_city_long=$enc->results[0]->geometry->location->lng;				
		
			}
		
		////////////
				
		$qry = "SELECT * from tbl_city_distance_list WHERE source_city='$from_city' and destination_city='$to_city'";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		$info = mysqli_fetch_assoc($result); 
		
		$qryBookingBill = "SELECT MinimumCharge, Min_Distance, Per_Km_Charge, first_km_rate, WaitingCharge_per_minute, tripCharge_per_minute, NightCharges, night_rate_begins, IFNULL(0,night_rate_ends) as night_rate_ends , IFNULL(0,cancellation_fees) as cancellation_fees, speed_per_km, Waitning_minutes from tblbookingbill WHERE BookingTypeId='104' and CabType='$cab_type' order by id desc limit 1";
		$resultBookingBill = mysqli_query($this->con, $qryBookingBill);
		$infoBookingBill = mysqli_fetch_assoc($resultBookingBill); 
		
		$total_dist		=	$info['distance_km']-$infoBookingBill['Min_Distance'];
		$charge	=	$total_dist*$infoBookingBill['Per_Km_Charge'];
		$MinimumCharge=$infoBookingBill['MinimumCharge']+$charge;
		
		$driver_allowance='250';
		$driver_allowance_km_limit='200';
		
		
		$data['km']							=	$info['distance_km'];
		$data['source_city_lat']			=	$source_city_lat;
		$data['source_city_long']			=	$source_city_long;
		$data['destination_city_lat']		=	$dest_city_lat;
		$data['destination_city_long']		=	$dest_city_long;
		$data['MinimumCharge']				=	$MinimumCharge;
		$data['Per_Km_Charge']				=	$infoBookingBill['Per_Km_Charge'];
		$data['first_km_rate']				=	$infoBookingBill['first_km_rate'];
		$data['WaitingCharge_per_minute']	=	$infoBookingBill['WaitingCharge_per_minute'];
		$data['tripCharge_per_minute']		=	$infoBookingBill['tripCharge_per_minute'];
		$data['NightCharges']				=	$infoBookingBill['NightCharges'];
		$data['night_rate_begins']			=	$infoBookingBill['night_rate_begins'];
		$data['night_rate_ends']			=	$infoBookingBill['night_rate_ends'];
		$data['cancellation_fees']			=	$infoBookingBill['cancellation_fees'];
		$data['speed_per_km']				=	$infoBookingBill['speed_per_km'];
		$data['Waitning_minutes']			=	$infoBookingBill['Waitning_minutes'];
		$data['driver_allowance']			=	$driver_allowance;
		$data['driver_allowance_km_limit']	=	$driver_allowance_km_limit;
		$record[]=$data;
		return array("status"=>$status,"record"=>$record);
	}
	
	public function InsertOutstationBookingDetails()
	{
		$jsonstring = trim($_REQUEST['jsonstring']); 
		$jsonstring =json_decode($jsonstring,true); 
		//echo "<pre>";print_r($jsonstring); 
		$booking_id = trim($_REQUEST['booking_id']); 
		foreach($jsonstring as $v)
		{
			$via_id=$v['via_id'];
			//$booking_id=$v['booking_id'];
			$outstation_type=$v['outstation_type'];
			$from_city=$v['FromCity'];
			$to_city=$v['to_city'];
			$km=$v['km'];
			$fare=$v['fare'];
			$booking_date=date('Y-m-d',strtotime($v['booking_date']));
			$booking_time=$v['booking_time'];
			$pickcitylatitude=$v['PickCityLatitude'];
			$pickcitylongitude=$v['PickCityLongitude'];
			$dropcitylatitude=$v['DropCityLatitude'];
			$dropcitylongitude=$v['DropCityLongitude'];
			
			$sql="INSERT into tbl_outstation_booking_details(via_id,booking_id,outstation_type,from_city,to_city,km,fare, created_date,booking_date,booking_time,pickcitylatitude,pickcitylongitude,dropcitylatitude,dropcitylongitude)
			values ('$via_id','$booking_id','$outstation_type','$from_city','$to_city','$km','$fare',Now(),'$booking_date','$booking_time','$pickcitylatitude','$pickcitylongitude','$dropcitylatitude','$dropcitylongitude');";
			$sqlInsert = mysqli_query($this->con,$sql);
		}
		//die;
		
		if($sqlInsert==1)
			$status="true";			
	    else
			$status="false";
		return array("status"=>$status);	
	}
	
	////////// API Services In Call Centre for Outstations Booking Ends here by Mohit Jain /////////////////
	
	
}
?>