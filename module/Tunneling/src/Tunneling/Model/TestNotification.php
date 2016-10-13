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

class TestNotification{
	
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
		
		$sql2="SELECT tblcountry.currency as currency,tblcountry.distance_unit as measure,tblsubpackage.Sub_Package_Id as configPackageNo,tbluser.gcm_id,tbluser.Latitude as lat,tbluser.Longtitude1 as lon, booking_stack.*,tblcabbooking.* FROM booking_stack 
		INNER JOIN tblcabbooking ON booking_stack.booking_id = tblcabbooking.ID 
		JOIN tbluser ON tblcabbooking.pickup=tbluser.id
		INNER JOIN tbldriver ON tbldriver.UID = tblcabbooking.pickup
		INNER JOIN tblcablistmgmt ON tbldriver.vehicleId = tblcablistmgmt.id
		INNER JOIN tblbookingbill ON tblcablistmgmt.CabType = tblbookingbill.CabType AND tblcablistmgmt.BookingType = tblbookingbill.BookingTypeId
		INNER JOIN tblcity ON tblbookingbill.CityId = tblcity.id
		INNER JOIN tblstates ON tblcity.state = tblstates.id
		INNER JOIN tblcountry On tblcountry.ID = tblstates.country_code
		INNER JOIN tblmasterpackage ON tblcabbooking.BookingType = tblmasterpackage.Package_Id 
		INNER JOIN tblsubpackage ON tblmasterpackage.Sub_Package_Id = tblsubpackage.Sub_Package_Id WHERE booking_stack.status=''
		and TIMESTAMPDIFF(HOUR,NOW(),CONCAT(tblcabbooking.PickupDate,' ',PickupTime))<1 
		and CONCAT(tblcabbooking.PickupDate,' ',PickupTime)>NOW() and pickup!=0"; 
		
		
		$booking_query2 = mysqli_query($this->con, $sql2) or die(mysqli_error($this->con));
		while($data=$booking_query2->fetch_array()){
			$booking_id=$data['ID'];
			$sqltemp1="UPDATE booking_stack SET last_try=NOW(),status='W' WHERE booking_id='$booking_id'";
			mysqli_query($this->con, $sqltemp1) or die(mysqli_error($this->con));
			//print_r($data);
			$datam['name']=$data['UserName'];
			$datam['booking_id']=$data['ID'];
			$datam['booking_type']=$data['BookingType'];
			$datam['trip_charges_per_hour']=$data['tripCharge_per_minute'];
			$datam['pickup_address']=$data['PickupAddress'];
			$datam['pickup_location']=$data['PickupArea'];
			$datam['drop_address']=$data['DropAddress'];
			$datam['drop_location']=$data['DropArea'];
			$datam['pickup_latitude']=$data['PickupLatitude'];
			$datam['pickup_longitude']=$data['PickupLongtitude'];
			$datam['drop_latitude']=$data['DestinationLatitude'];
			$datam['drop_longitude']=$data['DestinationLongtitude'];
			$datam['pickup_time']=$data['PickupTime'];
			$datam['per_distance_charge']=$data['approx_distance_charge'];
			$datam['mobile_no']=$data['MobileNo'];
			$datam['CabFor']=$data['CabFor'];
			$datam['estimated_price']=$data['estimated_price'];
			$datam['configPackageNo']=$data['Sub_Package_Id'];
			$datam['drop_distance']=$data['EstimatedDistance'];
			$datam['currency']=$data['currency'];
			$datam['measure']=$data['measure'];
			$datam['ajay']='jjjjjjjjjjjjj';
			$id=array();
			$id[]=$data['gcm_id'];
			$data= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$data['lat'].",".$data['lon']."&destination=".$datam['pickup_latitude'].','.$datam['pickup_longitude']."&sensor=false");
			//echo $data;
			$enc=json_decode($data);
			$enc2=$enc->routes[0];
			$datam['pickup_distance']=round((($enc2->legs[0]->distance->value)/1000),1);
			//$datam = calculateBillForBooking($datam['drop_distance'],$data['ID'],$data['CarType'],$datam);
			//echo $bookingCarType;
			$sqltmp2="UPDATE tblcabbooking SET estimated_price='".$datam['estimaedTotalBill']."' WHERE id ='".$datam['booking_id']."'";
			mysqli_query($this->con, $sqltemp2) or die(mysqli_error($this->con));
			$sqltmp3="UPDATE tblcabbooking SET EstimatedTime='".$datam['estimatedTime']."' WHERE id ='".$datam['booking_id']."'";
			mysqli_query($this->con, $sqltemp3) or die(mysqli_error($this->con));
			$datam['estimatedTime']=$datam['pickup_time'];
			$datam['send_time']="".date("Y-m-d H:i:s")."ddd";
			print_r($datam);
			send_notification($id,$datam);	
		}
		
		//For Automatic Booking
		 $sql3="SELECT booking_stack.*,tblcabbooking.CarType FROM booking_stack 
		INNER JOIN tblcabbooking ON booking_stack.booking_id = tblcabbooking.ID 
		WHERE booking_stack.status='' and TIMESTAMPDIFF(HOUR,NOW(),CONCAT(tblcabbooking.PickupDate,' ',PickupTime))<1 and CONCAT(tblcabbooking.PickupDate,' ',PickupTime)>NOW() and pickup=0 and is_updation_allow!='FALSE'"; 
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
		}
		
		
		
		foreach($booking as $bookings){
			$bookingCarType = $bookings['CarType'];
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
				$sql8="select tblsubpackage.Sub_Package_Id ,tblcabbooking.* from tblcabbooking INNER JOIN tblmasterpackage 
				ON tblcabbooking.BookingType = tblmasterpackage.Package_Id INNER JOIN tblsubpackage 
				ON tblmasterpackage.Sub_Package_Id = tblsubpackage.Sub_Package_Id where tblcabbooking.ID = $booking_id";
				//die;
				$rees=mysqli_query($this->con, $sql8) or die(mysqli_error($this->con));
				while($data=$rees->fetch_assoc()){
					$datam['name']=$data['UserName'];
					$datam['booking_id']=$data['ID'];
					$datam['booking_type']=$data['BookingType'];
					$datam['pickup_address']=$data['PickupAddress'];
					$datam['pickup_location']=$data['PickupArea'];
					$datam['drop_address']=$data['DropAddress'];
					$datam['drop_location']=$data['DropArea'];
					$datam['pickup_latitude']=$data['PickupLatitude'];
					$datam['pickup_longitude']=$data['PickupLongtitude'];
					$datam['drop_latitude']=$data['DestinationLatitude'];
					$datam['drop_longitude']=$data['DestinationLongtitude'];
					$datam['amount']=($data["estimated_price"]*5)/100;
					$datam['pickup_time']=$data['PickupTime'];
					$datam['per_distance_charge']=$data['approx_distance_charge'];
					$datam['mobile_no']=$data['MobileNo'];
					$datam['CabFor']=$data['CabFor'];
					$datam['estimated_price']=$data['estimated_price'];
					$datam['configPackageNo']=$data['Sub_Package_Id'];
					$datam['drop_distance']="";
					$datam['shyamfuture']='ggggggggggggggggg';
					if($data['EstimatedDistance']==0){
						$data2= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$datam['pickup_latitude'].','.$datam['pickup_longitude']."&destination=".$datam['drop_latitude'].','.$datam['drop_longitude']."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584");
						$enc=json_decode($data2);
						$enc2=$enc->routes[0];
						$datam['drop_distance']=round((($enc2->legs[0]->distance->value)/1000),1);
						$sql9="UPDATE tblcabbooking SET EstimatedDistance='".$datam['drop_distance']."' WHERE id ='".$datam['booking_id']."'";
						mysqli_query($this->con, $sql9) or die(mysqli_error($this->con));
					}else{
						$datam['drop_distance']=$data['EstimatedDistance'];
					}		 
				}
				// print_r($datam);
				$point=array();
				foreach($driver_id as $driver_ids){
					$driverId = $driver_ids['UID'];
					$security=$driver_ids['SecurityAmt'];
					$book_new=$datam['booking_id'];
					$sql10="SELECT * FROM tblbookingregister WHERE tblbookingregister.bookingid='$book_new' and tblbookingregister.driverId='$driverId' and tblbookingregister.status='R'"; 
					$new_query=mysqli_query($this->con, $sql10) or die(mysqli_error($this->con));
					
					$count=$new_query->num_rows;
					if($count<1){
						$driverCarType = $driver_ids['TypeOfvehicle'];
						$vehicleId = $driver_ids['vehicleId'];
						if($security>$datam['amount']){
							$sql11="CALL calculate_point($driverId,'22.999999','77.66666')"; 
							$res=mysqli_query($this->con, $sql11) or die(mysqli_error($this->con));
							while($row=$res->fetch_assoc()){
								$point[]=array('id'=>$driverId,'vehicleId'=>$vehicleId,'point'=>$row['point']);	 
							}
							$res->close();

							mysqli_next_result($this->con);
						}
					}
				}	
				 //print_r($point); die;
				rsort($point);
				$sorted=array();
				$id=array();
				for($i=0;$i<count($point);$i++){
					if($i<8){ 
						$drv=$point[$i]['id'];
						echo $drv."\n";
						$vehiclId = $point[$i]['vehicleId'];
						$book_id=$datam['booking_id'];
						$sql12="SELECT gcm_id,latitude,longtitude1 FROM tbluser WHERE id=$drv";
						$driver_gcm_id=mysqli_query($this->con, $sql12) or die(mysqli_error($this->con));
						
						$sql13="INSERT INTO tblbookingregister(bookingid,driverid,updateon) VALUES('$book_id','$drv',NOW())";
						
						mysqli_query($this->con, $sql13) or die(mysqli_error($this->con));
						while($resul=$driver_gcm_id->fetch_array()){
							print_r($resul);
							$data= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$resul[1].",".$resul[2]."&destination=".$datam['pickup_latitude'].','.$datam['pickup_longitude']."&sensor=false&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584");
							//AIzaSyBcsz_BY5Yg03KnXCbhDwXgOlnwch9kGXE
							//echo $data;
							$enc=json_decode($data);
							$enc2=$enc->routes[0];
							$datam['pickup_distance']=round((($enc2->legs[0]->distance->value)/1000),1);
							$id=array(); 
							$id[]=$resul[0];
						} 

					//$datam = $this->calculateBillForBooking($datam['drop_distance'],$book_id,$vehiclId,$datam);
			$datam = $this->calculateBillForBooking($datam['drop_distance'],$book_id,$vehiclId,$bookingCarType,$datam['booking_type'],$datam);
						echo $bookingCarType;
						$sql14="UPDATE tblcabbooking SET estimated_price='".$datam['estimaedTotalBill']."', EstimatedTime='".$datam['estimatedTime']."' WHERE ID ='".$datam['booking_id']."'"; 
						mysqli_query($this->con, $sql14) or die(mysqli_error($this->con));
						$datam['estimatedTime']=$datam['pickup_time'];
						$datam['send_time']="".date("Y-m-d H:i:s")."ddd";
						echo "<pre>";print_r($datam); //die;
						$this->send_notification($id,$datam);	
					}
				}
				//print_r($id);
			}
		}
		
	//return array('aa'=>'Mohit');
		
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
	
	
	
	
	
	
	
	public function Test2(){
		echo $sql1="CALL book_fetch_info(6572,101)"; die;
		$result =mysqli_query($this->con, $sql1) or die(mysqli_error($this->con));
		$data = $result->fetch_array();
		print_r($data);
		//return array('aa'=>'Mohit');
		
	}
	
	public function Test3(){
		$sql2="SELECT BookingType FROM tblcabbooking WHERE id='6613'";
		$res =mysqli_query($this->con, $sql2) or die(mysqli_error($this->con));
		$data1 = $res->fetch_array();
		$BookingType=$data1['BookingType']; 
		
		$sql1="CALL wp_estimatedBillNew('',181,6613,'',$BookingType)"; //die;
		$result =mysqli_query($this->con, $sql1) or die(mysqli_error($this->con));
		$data = $result->fetch_array();
		//print_r($data);
		return array('data'=>$data);
		
	}
	
	public function Test4(){
					$pickup_date='2015-11-19';
					$pickup_time='19:53:00';
					$pickupDateTime = $pickup_date." ".$pickup_time;
					$currentDateTime = date('Y-m-d H:i:s'); //die;
					$pickupDateTimeUnix = strtotime($pickupDateTime);
					$currentDateTimeUnix = strtotime($currentDateTime);
					echo 'aa'.$time_diff = $pickupDateTimeUnix - $currentDateTimeUnix;
	
		
					//if($time_diff <=3600 && $pickupDateTimeUnix >= $currentDateTimeUnix){
						//return array('pickup_date'=>$pickup_date);
		
	}
	
}

