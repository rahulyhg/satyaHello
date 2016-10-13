<?php

//require('gcm.php');

namespace Tunneling\Model;
use Zend\Mail;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Session\Container;

class TestNotificationNew{
	
	protected $con;
	private $data = array();
	private $row = array();
	
	public function __construct(){
		date_default_timezone_set("Asia/Kolkata");
		$key = '';
		//$this->con=mysqli_connect("10.0.0.101:3306","root","root","hello42_new");
		//$this->con=mysqli_connect("166.62.35.117","root","Travel@(4242)","hello42_new");
		$this->con=mysqli_connect("10.0.0.35","root","root","hello42_new");
		//$this->con=mysqli_connect("localhost","root","","hello42_new");
		//$this->con=mysqli_connect("10.0.0.24","","","hello42_new");
		foreach($_POST as $s=>$v){
			$_POST[$s]=$this->removeSqlInjection($v);
		}
		if(isset($_REQUEST['key'])){
	
	$key=$_REQUEST['key'];
		}
		$result=mysqli_query($this->con,"CALL sp_w_auth('$key')")or die(mysqli_error($this->con));
		$data=mysqli_fetch_array($result);
		if($data['check_auth']==0){
			// echo json_encode(array('error'=>'Api key is invalid'));
			//exit();
		}
		mysqli_free_result($result);   
		mysqli_next_result($this->con);
		
	}
	
	
	public function Test(){
		
		$sql1="UPDATE booking_stack SET status='' WHERE TIMESTAMPDIFF(SECOND,last_try,NOW())>30 and status='W'";
		$result = mysqli_query($this->con, $sql1) or die(mysqli_error($this->con));
		
		$sql2="SELECT tblcabbooking.ID as ID,tblcabbooking.BookingType as BookingType,tblcabbooking.pickup as pickup,booking_stack.* from booking_stack INNER JOIN tblcabbooking ON booking_stack.booking_id = tblcabbooking.ID WHERE booking_stack.status='' and TIMESTAMPDIFF(HOUR,NOW(),CONCAT(tblcabbooking.PickupDate,' ',tblcabbooking.PickupTime))<1 and CONCAT(tblcabbooking.PickupDate,' ',tblcabbooking.PickupTime)>NOW() and tblcabbooking.pickup!=0"; 
		$result2 = mysqli_query($this->con, $sql2) or die(mysqli_error($this->con));
		while($data22=$result2->fetch_array()){
			$booking_id=$data22['ID'];
			$id=$data22['pickup'];
			$BookingType=$data22['BookingType'];
			$IsMatchedCabType=$this->IsCabtypeMatched($id,$booking_id);
			if($IsMatchedCabType){
				$SQL="CALL `book_fetch_info_notification`($booking_id,$BookingType,1)";//exit;
			}else{
				$SQL="CALL `book_fetch_info_notification`($booking_id,$BookingType,0)";//exit;
			}
			$hour=mysqli_query($this->con,$SQL) or die(mysqli_error($this->con));
			$row=mysqli_fetch_array($hour);
			//echo "<pre>";print_r($row); die;
			$datam['name']=$row['UserName'];
			$datam['booking_id']=$row['ID'];
			$datam['booking_type']=$row['BookingType'];
			$datam['pickup_address']=$row['PickupAddress'];
			$datam['pickup_location']=$row['PickupArea'];
			$datam['drop_address']=$row['DropAddress'];
			$datam['drop_location']=$row['DropArea'];//
			$datam['pickup_latitude']=$row['PickupLatitude'];
			$datam['pickup_longitude']=$row['PickupLongtitude'];
			$datam['drop_latitude']=$row['DestinationLatitude'];
			$datam['drop_longitude']=$row['DestinationLongtitude'];
			$datam['pickup_time']=$row['PickupTime'];
			$datam['per_distance_charge']=$row['approx_distance_charge'];
			$datam['mobile_no']=$row['MobileNo'];
			$datam['CabFor']=$row['CabFor'];
			$datam['estimated_price']=$row['estimated_price'];
			$datam['configPackageNo']=$row['Sub_Package_Id'];
			$datam['drop_distance']=$row['EstimatedDistance'];
			
			
			$bookingCarType=$row['CarType'];	
			$MinimumChrage = $row['MinimumCharge'];
			$WaitingCharge = $row['WaitingCharge_per_minute'];//
			$tripCharge_per_minute = $row['tripCharge_per_minute'];
			$Per_Km_Charge = $row['Per_Km_Charge'];
			$NightCharges = $row['NightCharges'];
			$speed_per_km = $row['speed_per_km'];//
			$Waitning_minutes = $row['Waitning_minutes'];//
			$Min_Distance = $row['Min_Distance'];
			$configPackageNo = $row['Sub_Package_Id'];
			$timeTakenHr = $distance / $speed_per_km;
			$timeTakenMin = $timeTakenHr * 60;	
			$datam['min_distance']=$row['Min_Distance'];
			$night_charges=explode(' ',$row['NightCharges']);
			$datam['NightCharges']=$night_charges[0];
			$waiting_fees = explode('_',$row['waiting_fees']);
			$datam['WaitingFreeMin'] = $waiting_fees[0];
			$datam['WaitingBiforeCharge'] = $waiting_fees[1];
			$datam['WaitingAfterCharge'] = $waiting_fees[2];		
			$datam['WaitingCharge_per_minute']=$row['WaitingCharge_per_minute'];//
			$datam['MinimumCharge']=$row['MinimumCharge'];
			$datam['Waitning_minutes']=$row['Waitning_minutes'];
			$datam['night_rate_begins']=$row['night_rate_begins'];
			$datam['night_rate_ends']=$row['night_rate_ends'];
			$datam['trip_charges_per_hour']=$row['tripCharge_per_minute'];
			$datam['currency']=$row['currency'];
			$datam['measure']=$row['measure'];
			$datam['tax']=$row['basic_tax'];
			if($BookingType==101){	
			$datam['local_subpackage']=$row['local_subpackage'];
			$datam['MinimumCharge']=$row['Price'];
			//$datam['Economy_Price']=$row['Economy_Price'];
			//$datam['Sidan_Price']=$row['Sidan_Price'];
			//$datam['Prime_Price']=$row['Prime_Price'];
			/*switch($bookingCarType)
			{
			case 1:
			$datam['MinimumCharge']=$row['Economy_Price'];
			break;
			case 2:
			$datam['MinimumCharge']=$row['Sidan_Price'];
			break;
			case 3:
			$datam['MinimumCharge']=$row['Prime_Price'];
			break;
			}*/
			}

			if($distance < $Min_Distance){
				$TripCharge = $MinimumChrage;
			}else{
				$TripPerKmCharge = ($distance - $Min_Distance) * $Per_Km_Charge;
				$TripCharge = $TripPerKmCharge + $MinimumChrage;
			}
			$hourlyCharge = $timeTakenMin * $tripCharge_per_minute;	
			if($configPackageNo  == 2){
				$hourlyCharge = 0;
			}else if($configPackageNo  == 3){
				$TripCharge = 0;
			}
			//$datam['pack_type']==0;
			$TotalBill = $TripCharge;
			if($data['book_type']=="101"){
				 $data['book_type'];
				if($data['cab_for']=="0"){
					$TotalBill=$MinimumChrage;
				}else{
					$TotalBill=$data['cab_for']*$tripCharge_per_minute;    
				}
			}
			$datam['estimaedTotalBill'] = round($TotalBill);
			$datam['estimatedTime'] = round($timeTakenHr);
				if($BookingType==102){	
				if($row['EstimatedDistance']==0){
							$data2= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$datam['pickup_latitude'].','.$datam['pickup_longitude']."&destination=".$datam['drop_latitude'].','.$datam['drop_longitude']."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584");
							$enc=json_decode($data2);
							$enc2=$enc->routes[0];
							$datam['drop_distance']=round((($enc2->legs[0]->distance->value)/1000),1);
							$sql9="UPDATE tblcabbooking SET EstimatedDistance='".$datam['drop_distance']."' WHERE id ='".$datam['booking_id']."'";
							mysqli_query($this->con, $sql9) or die(mysqli_error($this->con));
						}else{
							$datam['drop_distance']=$row['EstimatedDistance'];
						}	
				}
			$id=array();
			$id[]=$row['gcm_id'];
			//print_r($datam);
			$data11= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$row['lat'].",".$row['lon']."&destination=".$datam['pickup_latitude'].','.$datam['pickup_longitude']."&sensor=false");
			//echo $data;
			$enc=json_decode($data11);
			$enc2=$enc->routes[0];
			$datam['pickup_distance']=round((($enc2->legs[0]->distance->value)/1000),1);

			$sqltmp2="UPDATE tblcabbooking SET estimated_price='".$datam['estimaedTotalBill']."',EstimatedTime='".$datam['estimatedTime']."' WHERE id ='".$datam['booking_id']."'"; 
			mysqli_query($this->con, $sqltmp2) or die(mysqli_error($this->con));
			$datam['estimatedTime']=$datam['pickup_time'];
			$datam['send_time']="".date("Y-m-d H:i:s");
			print_r($datam);
			$this->send_notification($id,$datam);		
		}
		
		//die;		
	}
	
	
	public function Test1(){
		//For Automatic Booking
		/*$sql3="SELECT booking_stack.*,tblcabbooking.CarType,tblcabbooking.BookingType FROM booking_stack 
		INNER JOIN tblcabbooking ON booking_stack.booking_id = tblcabbooking.ID 
		WHERE booking_stack.status='' and TIMESTAMPDIFF(HOUR,NOW(),CONCAT(tblcabbooking.PickupDate,' ',PickupTime))<1 and CONCAT(tblcabbooking.PickupDate,' ',PickupTime)>NOW() and pickup=0 and is_updation_allow!='FALSE'";*/
		$sql3="SELECT booking_stack.*,tblcabbooking.CarType,tblcabbooking.BookingType FROM booking_stack 
		INNER JOIN tblcabbooking ON booking_stack.booking_id = tblcabbooking.ID 
		WHERE booking_stack.status='' and CONCAT(tblcabbooking.PickupDate,' ',PickupTime)>NOW() and pickup=0 and is_updation_allow!='FALSE'";
		$booking_query = mysqli_query($this->con, $sql3) or die(mysqli_error($this->con));
		$booking=array();
		while($row=$booking_query->fetch_array()){
			 $booking[]=$row;
		}
		
		$sql4="SELECT tbldriver.SecurityAmt,tbldriver.UID,tbldriver.TypeOfvehicle,tbldriver.vehicleId FROM tbldriver JOIN tbluser ON tbldriver.uid=tbluser.id WHERE tbldriver.status=0 AND tbluser.loginstatus=1 and tbluser.is_active=1 and SecurityAmt>0"; 
		$driver_query = mysqli_query($this->con, $sql4) or die(mysqli_error($this->con));
		$driver_id=array();
		while($driver=$driver_query->fetch_assoc()){
			$driver_id[]=$driver;
			$driver_type[]=$driver;
		}
		
		
		foreach($booking as $bookings){
			$bookingCarType = $bookings['CarType'];
			$BookingType = $bookings['BookingType']; 
			if($bookings['status']==''){
				$booking_id=$bookings['booking_id'];
				$sql5="UPDATE booking_stack SET last_try=NOW(),status='W' WHERE booking_id='$booking_id'";
				mysqli_query($this->con, $sql5) or die(mysqli_error($this->con));
				$sql6="UPDATE tblcabbooking SET `status`=2 WHERE id='$booking_id'";
				mysqli_query($this->con, $sql6) or die(mysqli_error($this->con));
				if(mysqli_affected_rows($this->con)>0){
					$sql7="INSERT INTO tblbookinglogs (`bookingid`,`status`,`message`,`time`) VALUES('$booking_id',2,'Executed',NOW())";
					mysqli_query($this->con, $sql7) or die(mysqli_error($this->con));
				}
				
				$datam=array();
				//$rees=$con->query("SELECT * FROM tblcabbooking WHERE ID=$booking_id") or die($con->error);
				
				//// Mine //////////
				switch($bookingCarType)
				{
				case 1:
				foreach($driver_type as $driver_types){
					$driverType = $driver_types['TypeOfvehicle'];
					$driverId = $driver_types['UID'];
					$security=$driver_types['SecurityAmt'];		
					$sql10="SELECT * FROM tblbookingregister WHERE tblbookingregister.bookingid='$booking_id' and tblbookingregister.driverId='$driverId' and tblbookingregister.status='R'"; 
					$new_query=mysqli_query($this->con, $sql10) or die(mysqli_error($this->con));
					$count=$new_query->num_rows;
					if($count<1 && $security>0){
							$this->callNoti($driverId,$booking_id,$BookingType);
					}
					
				}
				break;
				case 2:
				foreach($driver_type as $driver_types){
					$driverType = $driver_types['TypeOfvehicle'];
					$driverId = $driver_types['UID'];
					$security=$driver_types['SecurityAmt'];		
					$sql10="SELECT * FROM tblbookingregister WHERE tblbookingregister.bookingid='$booking_id' and tblbookingregister.driverId='$driverId' and tblbookingregister.status='R'"; 
					$new_query=mysqli_query($this->con, $sql10) or die(mysqli_error($this->con));
					$count=$new_query->num_rows;
					if($count<1 && $security>0 && $driverType != 1){
							$this->callNoti($driverId,$booking_id,$BookingType);
					}
				}
				break;
				case 3:
				foreach($driver_type as $driver_types){
					$driverType = $driver_types['TypeOfvehicle'];
					$driverId = $driver_types['UID'];
					$security=$driver_types['SecurityAmt'];		
					$sql10="SELECT * FROM tblbookingregister WHERE tblbookingregister.bookingid='$booking_id' and tblbookingregister.driverId='$driverId' and tblbookingregister.status='R'"; 
					$new_query=mysqli_query($this->con, $sql10) or die(mysqli_error($this->con));
					$count=$new_query->num_rows;
					if($count<1 && $security>0 && $driverType==3){
							$this->callNoti($driverId,$booking_id,$BookingType);
					}
					
				}
				break;
				}
				
				//// Mine ///////////////
				
				//die;
			}
		}
	
	
	}
	
	
	function send_notification($registatoin_ids, $message) {
		//echo 'aza'.$registatoin_ids; die;
		// include config
		//include_once './config.php';
		//define("GOOGLE_API_KEY", "AIzaSyCOpOWLJG7G2PGHDb9uCl0eALEHYrPApVw");
		// Set POST variables
		//define("GOOGLE_API_KEY", "AIzaSyBcsz_BY5Yg03KnXCbhDwXgOlnwch9kGXE");
		define("GOOGLE_API_KEY", "AIzaSyAFE4jcZwQsi2LIx_2TfczrM3DlSTRpNYM");
		$url = 'https://android.googleapis.com/gcm/send';
		$fields = array('registration_ids' => $registatoin_ids, 'data' => $message,);
		$headers = array('Authorization: key=' . GOOGLE_API_KEY, 'Content-Type: application/json');
		// Open connection
		$ch = curl_init();
		// Set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Disabling SSL Certificate support temporarly
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		// Execute post
		$result = curl_exec($ch);
		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}
		// Close connection
		curl_close($ch);
		echo $result;
	}


function calculateBillForBooking($distance,$BookingId_i,$vehicleId,$bookingCarType,$BookingType,$datam){
			
		/*$sql2="SELECT BookingType FROM tblcabbooking WHERE id='$BookingId_i'";
		$res =mysqli_query($this->con, $sql2) or die(mysqli_error($this->con));
		$data1 = $res->fetch_array();
		$BookingType=$data1['BookingType'];*/ 
		$sql1="CALL wp_estimatedBillNew('',$vehicleId,$BookingId_i,'',$BookingType)"; 
		//$sql1="CALL wp_estimatedBillNew($bookingCarType,$vehicleId,$BookingId_i)";
		$result =mysqli_query($this->con, $sql1) or die(mysqli_error($this->con));
		$data = $result->fetch_assoc();
		mysqli_free_result($result); 
		mysqli_next_result($this->con);
		$MinimumChrage = $data['MinimumCharge'];
		$WaitingCharge = $data['WaitingCharge_per_minute'];
		$tripCharge_per_minute = $data['tripCharge_per_minute'];
		$Per_Km_Charge = $data['Per_Km_Charge'];
		$NightCharges = $data['NightCharges'];
		$speed_per_km = $data['speed_per_km'];//
		$Waitning_minutes = $data['Waitning_minutes'];
		$Min_Distance = $data['Min_Distance'];
		$configPackageNo = $data['configPackageNo'];//
		$timeTakenHr = $distance / $speed_per_km;
		$timeTakenMin = $timeTakenHr * 60;	
		$datam['min_distance']=$data['Min_Distance'];
		$night_charges=explode(' ',$data['NightCharges']);
		$datam['NightCharges']=$night_charges[0];
		$waiting_fees = explode('_',$data['waiting_fees']);
		$datam['WaitingFreeMin'] = $waiting_fees[0];
		$datam['WaitingBiforeCharge'] = $waiting_fees[1];
		$datam['WaitingAfterCharge'] = $waiting_fees[2];		
		$datam['WaitingCharge_per_minute']=$data['WaitingCharge_per_minute'];//
		$datam['MinimumCharge']=$data['MinimumCharge'];
		$datam['Waitning_minutes']=$data['Waitning_minutes'];
		$datam['night_rate_begins']=$data['night_rate_begins'];
		$datam['night_rate_ends']=$data['night_rate_ends'];
		$datam['trip_charges_per_hour']=$data['tripCharge_per_minute'];
		$datam['currency']=$data['currency'];
		$datam['measure']=$data['distance_unit'];
		$datam['tax']=$data['basic_tax'];
		//echo 'aa'.$BookingType; die;
		if($BookingType==101){	
		$datam['local_subpackage']=$data['local_subpackage'];
		$datam['Economy_Price']=$data['Economy_Price'];
		$datam['Sidan_Price']=$data['Sidan_Price'];
		$datam['Prime_Price']=$data['Prime_Price'];
		switch($bookingCarType)
		{
		case 1:
		$datam['MinimumCharge']=$data['Economy_Price'];
		break;
		case 2:
		$datam['MinimumCharge']=$data['Sidan_Price'];
		break;
		case 3:
		$datam['MinimumCharge']=$data['Prime_Price'];
		break;
		}
		}

		
		
		if($distance < $Min_Distance){
			$TripCharge = $MinimumChrage;
		}else{
			$TripPerKmCharge = ($distance - $Min_Distance) * $Per_Km_Charge;
			$TripCharge = $TripPerKmCharge + $MinimumChrage;
		}
		$hourlyCharge = $timeTakenMin * $tripCharge_per_minute;	
		if($configPackageNo  == 2){
			$hourlyCharge = 0;
		}else if($configPackageNo  == 3){
			$TripCharge = 0;
		}
		//$datam['pack_type']==0;
		$TotalBill = $TripCharge;
		if($data['book_type']=="101"){
			 $data['book_type'];
			if($data['cab_for']=="0"){
				$TotalBill=$MinimumChrage;
			}else{
				$TotalBill=$data['cab_for']*$tripCharge_per_minute;    
			}
		}
		$datam['estimaedTotalBill'] = round($TotalBill);
		$datam['estimatedTime'] = round($timeTakenHr);
		//print_r($datam); die;
		return $datam;
	}
	
	
	
	
	
	
	
	
	
	
	
	public function IsCabtypeMatched($DriverId,$BookingId){
		//$BookingId=$_REQUEST['booking_id'];
		//$DriverId=$_REQUEST['DriverId'];
		  $sql="SELECT tblcabtype.CabType FROM tblcabtype 
			  INNER JOIN tblcabbooking ON tblcabtype.Id = tblcabbooking.CarType 
			  WHERE tblcabbooking.ID = '$BookingId'";
		$qry = mysqli_query($this->con, $sql);
		$vehiclefetch= mysqli_fetch_object($qry);
		$BookingVehicle=$vehiclefetch->CabType;
		mysqli_free_result($qry); 
	    mysqli_next_result($this->con);			
		 $query="SELECT tblcabtype.CabType FROM tblcabtype 
			  INNER JOIN tbldriver ON tblcabtype.Id = tbldriver.TypeOfvehicle 
			  WHERE tbldriver.UID = '$DriverId'";
		$Fetch = mysqli_query($this->con, $query);
		$DrriverFetch = mysqli_fetch_object($Fetch);
		$DriverVehicle=$DrriverFetch->CabType;
		if($DriverVehicle == $BookingVehicle){			
			return true;
		}else{			
			return false;
		}				
	}
	
	
	public function callNoti($driverId,$booking_id,$BookingType){
			//echo "aa"; die;
			$IsMatchedCabType=$this->IsCabtypeMatched($driverId,$booking_id); 
			if($IsMatchedCabType){
				$SQL="CALL `book_fetch_info_current_notification`($booking_id,$BookingType,1,$driverId)";//exit;
			}else{
				$SQL="CALL `book_fetch_info_current_notification`($booking_id,$BookingType,0,'')";//exit;
			}//die;
			$hour=mysqli_query($this->con,$SQL) or die(mysqli_error($this->con));
			$row=mysqli_fetch_array($hour);
			mysqli_free_result($hour); 
			mysqli_next_result($this->con);
			//print_r($row); die;
			$datam['name']=$row['UserName'];
			$datam['booking_id']=$row['ID'];
			$datam['CarType']=$row['CarType'];
			$datam['booking_type']=$row['BookingType'];
			$datam['pickup_address']=$row['PickupAddress'];
			$datam['pickup_location']=$row['PickupArea'];
			$datam['drop_address']=$row['DropAddress'];
			$datam['drop_location']=$row['DropArea'];//
			$datam['pickup_latitude']=$row['PickupLatitude'];
			$datam['pickup_longitude']=$row['PickupLongtitude'];
			$datam['drop_latitude']=$row['DestinationLatitude'];
			$datam['drop_longitude']=$row['DestinationLongtitude'];
			$datam['pickup_time']=$row['PickupTime'];
			$datam['per_distance_charge']=$row['approx_distance_charge'];
			$datam['mobile_no']=$row['MobileNo'];
			$datam['CabFor']=$row['CabFor'];
			$datam['estimated_price']=$row['estimated_price'];
			$datam['configPackageNo']=$row['Sub_Package_Id'];
			$datam['drop_distance']=$row['EstimatedDistance'];
			
			
			$bookingCarType=$row['CarType'];	
			$MinimumChrage = $row['MinimumCharge'];
			$WaitingCharge = $row['WaitingCharge_per_minute'];//
			$tripCharge_per_minute = $row['tripCharge_per_minute'];
			$Per_Km_Charge = $row['Per_Km_Charge'];
			$NightCharges = $row['NightCharges'];
			$speed_per_km = $row['speed_per_km'];//
			$Waitning_minutes = $row['Waitning_minutes'];//
			$Min_Distance = $row['Min_Distance'];
			$configPackageNo = $row['Sub_Package_Id'];
			$timeTakenHr = $distance / $speed_per_km;
			$timeTakenMin = $timeTakenHr * 60;	
			$datam['min_distance']=$row['Min_Distance'];
			$night_charges=explode(' ',$row['NightCharges']);
			$datam['NightCharges']=$night_charges[0];
			$waiting_fees = explode('_',$row['waiting_fees']);
			$datam['WaitingFreeMin'] = $waiting_fees[0];
			$datam['WaitingBiforeCharge'] = $waiting_fees[1];
			$datam['WaitingAfterCharge'] = $waiting_fees[2];		
			$datam['WaitingCharge_per_minute']=$row['WaitingCharge_per_minute'];//
			$datam['MinimumCharge']=$row['MinimumCharge'];
			$datam['Waitning_minutes']=$row['Waitning_minutes'];
			$datam['night_rate_begins']=$row['night_rate_begins'];
			$datam['night_rate_ends']=$row['night_rate_ends'];
			$datam['trip_charges_per_hour']=$row['tripCharge_per_minute'];
			$datam['currency']=$row['currency'];
			$datam['measure']=$row['measure'];
			$datam['tax']=$row['basic_tax'];
			if($BookingType==101){	
			$datam['local_subpackage']=$row['local_subpackage'];
			//$datam['Economy_Price']=$row['Economy_Price'];
			//$datam['Sidan_Price']=$row['Sidan_Price'];
			//$datam['Prime_Price']=$row['Prime_Price'];
			
			$datam['MinimumCharge']=$row['Price'];
			/*switch($bookingCarType)
			{
			case 1:
			$datam['MinimumCharge']=$row['Economy_Price'];
			break;
			case 2:
			$datam['MinimumCharge']=$row['Sidan_Price'];
			break;
			case 3:
			$datam['MinimumCharge']=$row['Prime_Price'];
			break;
			}*/
			}
			//die;
			if($distance < $Min_Distance){
				$TripCharge = $MinimumChrage;
			}else{
				$TripPerKmCharge = ($distance - $Min_Distance) * $Per_Km_Charge;
				$TripCharge = $TripPerKmCharge + $MinimumChrage;
			}
			$hourlyCharge = $timeTakenMin * $tripCharge_per_minute;	
			if($configPackageNo  == 2){
				$hourlyCharge = 0;
			}else if($configPackageNo  == 3){
				$TripCharge = 0;
			}
			//$datam['pack_type']==0;
			$TotalBill = $TripCharge;
			if($data['book_type']=="101"){
				 $data['book_type'];
				if($data['cab_for']=="0"){
					$TotalBill=$MinimumChrage;
				}else{
					$TotalBill=$data['cab_for']*$tripCharge_per_minute;    
				}
			}
			$datam['estimaedTotalBill'] = round($TotalBill);
			$datam['estimatedTime'] = round($timeTakenHr);
				if($BookingType==102){	
				if($row['EstimatedDistance']==0){
							$data2= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$datam['pickup_latitude'].','.$datam['pickup_longitude']."&destination=".$datam['drop_latitude'].','.$datam['drop_longitude']."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584");
							$enc=json_decode($data2);
							$enc2=$enc->routes[0];
							$datam['drop_distance']=round((($enc2->legs[0]->distance->value)/1000),1);
							$sql9="UPDATE tblcabbooking SET EstimatedDistance='".$datam['drop_distance']."' WHERE id ='".$datam['booking_id']."'";
							mysqli_query($this->con, $sql9) or die(mysqli_error($this->con));
						}else{
							$datam['drop_distance']=$row['EstimatedDistance'];
						}	
				}
			
			$sql12="SELECT gcm_id,latitude,longtitude1 FROM tbluser WHERE id='$driverId'"; //die;
			$driver_gcm_id=mysqli_query($this->con, $sql12) or die(mysqli_error($this->con));
			$resul=$driver_gcm_id->fetch_array();
			print_r($resul);
			mysqli_free_result($driver_gcm_id); 
			mysqli_next_result($this->con);
			//print_r($datam);
			
			$data11= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$resul[1].",".$resul[2]."&destination=".$datam['pickup_latitude'].','.$datam['pickup_longitude']."&sensor=false&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584");
			//AIzaSyBcsz_BY5Yg03KnXCbhDwXgOlnwch9kGXE
			//echo $data;
			$enc=json_decode($data11);
			$enc2=$enc->routes[0];
			$datam['pickup_distance']=round((($enc2->legs[0]->distance->value)/1000),1);
			$id=array(); 
			$id[]=$resul[0];
							
			$sqltmp2="UPDATE tblcabbooking SET estimated_price='".$datam['estimaedTotalBill']."',EstimatedTime='".$datam['estimatedTime']."' WHERE id ='".$datam['booking_id']."'"; 
			$ss=mysqli_query($this->con, $sqltmp2) or die(mysqli_error($this->con));
			mysqli_free_result($ss); 
			mysqli_next_result($this->con);
			$datam['estimatedTime']=$datam['pickup_time'];
			$datam['send_time']="".date("Y-m-d H:i:s");
			print_r($datam);
			
			$sql1gg="INSERT INTO tblbookingregister(bookingid,driverid,updateon) VALUES('$booking_id','$driverId',NOW())";
			mysqli_query($this->con, $sql1gg) or die(mysqli_error($this->con));
			
			
			$this->send_notification($id,$datam);
		
		
	}
	
}

