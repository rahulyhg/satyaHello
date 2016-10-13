<?php
	
	ini_set('memory_limit', '-1');
	date_default_timezone_set('Asia/Kolkata');
	//$con=new mysqli("166.62.35.117","root","Hello(42)","hello42_new");
	$con=new mysqli("10.0.0.35","root","root","hello42_new");

	
	function send_push_notification($message,$reg) {
		$regId = 'APA91bFwSigEj0YUUfJYpGO-PMQvfFXCGlu7tVAjgVO5_ittttrXWlP8IZlcgfsDPJZ93O9BsYBRag9JzlaCVFDJwG3F7EYHkmVm3r_W31eCmWr_6tpM0izaptXXiyuB_xqxEcL8olPpVVgLrvraQV2LqYJHI_2H-1w5ORasfey_f3lOTXF6Flo';
		//$message = 'Heloo';
		// $message = "hfsj";
		// include_once './GCM.php';
		//$gcm = new GCM();
		//$registatoin_ids = array($regId);
		//$message = array("price" => $message);
		$result = $this->send_notification($reg, $message);
		// echo '$result'.$result;
	}
	
	
	function send_notification($registatoin_ids, $message) {
		// include config
		//include_once './config.php';
		//define("GOOGLE_API_KEY", "AIzaSyCOpOWLJG7G2PGHDb9uCl0eALEHYrPApVw");
		// Set POST variables
		//define("GOOGLE_API_KEY", "AIzaSyBcsz_BY5Yg03KnXCbhDwXgOlnwch9kGXE");
		define("GOOGLE_API_KEY", "AIzaSyAFE4jcZwQsi2LIx_2TfczrM3DlSTRpNYM");
		//define("GOOGLE_API_KEY", "AIzaSyAEwtX7eapPG7t6767tdzawNBFSkK2MNAk");
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
	
	function calculateBillForBooking($distance,$BookingId_i,$vehicleId,$datam){
	//$con=new mysqli("166.62.35.117","root","Hello(42)","hello42_new");
	$con=new mysqli("10.0.0.35","root","root","hello42_new");
		$result = $con->query("CALL wp_estimatedBill($vehicleId,$BookingId_i)") or die($con->error);
		$data = $result->fetch_assoc();
		$MinimumChrage = $data['MinimumCharge'];
		$WaitingCharge = $data['WaitingCharge_per_minute'];
		$tripCharge_per_minute = $data['tripCharge_per_minute'];
		$Per_Km_Charge = $data['Per_Km_Charge'];
		$NightCharges = $data['NightCharges'];
		$speed_per_km = $data['speed_per_km'];
		$Waitning_minutes = $data['Waitning_minutes'];
		$Min_Distance = $data['Min_Distance'];
		$configPackageNo = $data['configPackageNo'];
		$timeTakenHr = $distance / $speed_per_km;
		$timeTakenMin = $timeTakenHr * 60;	
		$datam['min_distance']=$data['Min_Distance'];
		$night_charges=explode(' ',$data['NightCharges']);
		$datam['NightCharges']=$night_charges[0];
		$datam['WaitingCharge_per_minute']=$data['WaitingCharge_per_minute'];
		$datam['MinimumCharge']=$data['MinimumCharge'];
		$datam['Waitning_minutes']=$data['Waitning_minutes'];
		$datam['night_rate_begins']=$data['night_rate_begins'];
		$datam['night_rate_ends']=$data['night_rate_ends'];
		$datam['trip_charges_per_hour']=$data['tripCharge_per_minute'];
		$datam['currency']=$data['currency'];
		$datam['measure']=$data['measure'];
		$datam['tax']=$data['basic_tax'];
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
			echo $data['book_type'];
			if($data['cab_for']=="0"){
				$TotalBill=$MinimumChrage;
			}else{
				$TotalBill=$data['cab_for']*$tripCharge_per_minute;    
			}
		}
		$datam['estimaedTotalBill'] = round($TotalBill);
		$datam['estimatedTime'] = round($timeTakenHr)." HR";
		return $datam;
	}
	//Find New Booking
	for(;;){
		sleep(2);
		//For unassigned Booking 
		$con->query("UPDATE booking_stack SET status='' WHERE TIMESTAMPDIFF(SECOND,last_try,NOW())>30 and status='W'") or die($con->error);
		$booking_query2=$con->query("SELECT tblcountry.currency as currency,tblcountry.distance_unit as measure,tblsubpackage.Sub_Package_Id as configPackageNo,tbluser.gcm_id,tbluser.Latitude as lat,tbluser.Longtitude1 as lon, booking_stack.*,tblcabbooking.* FROM booking_stack 
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
		and CONCAT(tblcabbooking.PickupDate,' ',PickupTime)>NOW() and pickup!=0");
		while($data=$booking_query2->fetch_array()){
			$booking_id=$data['ID'];
			$con->query("UPDATE booking_stack SET last_try=NOW(),status='W' WHERE booking_id='$booking_id'");
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
			$con->query("UPDATE tblcabbooking SET estimated_price='".$datam['estimaedTotalBill']."' WHERE id ='".$datam['booking_id']."'");
			$con->query("UPDATE tblcabbooking SET EstimatedTime='".$datam['estimatedTime']."' WHERE id ='".$datam['booking_id']."'");
			$datam['estimatedTime']=$datam['pickup_time'];
			$datam['send_time']="".date("Y-m-d H:i:s")."ddd";
			print_r($datam);
			send_notification($id,$datam);	
		}



		//For Automatic Booking
		$booking_query=$con->query("SELECT booking_stack.*,tblcabbooking.CarType FROM booking_stack 
		INNER JOIN tblcabbooking ON booking_stack.booking_id = tblcabbooking.ID 
		WHERE booking_stack.status='' and TIMESTAMPDIFF(HOUR,NOW(),CONCAT(tblcabbooking.PickupDate,' ',PickupTime))<1 and 
		CONCAT(tblcabbooking.PickupDate,' ',PickupTime)>NOW() and pickup=0 and is_updation_allow!='FALSE'");   
		$booking=array();
		while($row=$booking_query->fetch_array()){
			$booking[]=$row;
		}
		
		$driver_query=$con->query("SELECT tbldriver.SecurityAmt,tbldriver.UID,tbldriver.TypeOfvehicle,tbldriver.vehicleId FROM tbldriver JOIN tbluser ON tbldriver.uid=tbluser.id WHERE tbldriver.status=0 AND tbluser.loginstatus=1 and tbluser.is_active=1 and SecurityAmt>0") or die($con->error);
		$driver_id=array();
		while($driver=$driver_query->fetch_assoc()){
			$driver_id[]=$driver;
		}
		
		foreach($booking as $bookings){
			$bookingCarType = $bookings['CarType'];
			if($bookings['status']==''){
				$booking_id=$bookings['booking_id'];
				$con->query("UPDATE booking_stack SET last_try=NOW(),status='W' WHERE booking_id='$booking_id'");
				$con->query("UPDATE tblcabbooking SET `status`=2 WHERE id='$booking_id'");
				if($con->affected_rows>0){
					$con->query("INSERT INTO tblbookinglogs (`bookingid`,`status`,`message`,`time`) VALUES('$booking_id',2,'Executed',NOW())");
				}	  
				
				$datam=array();
				//$rees=$con->query("SELECT * FROM tblcabbooking WHERE ID=$booking_id") or die($con->error);
				$rees=$con->query("select tblsubpackage.Sub_Package_Id ,tblcabbooking.* from tblcabbooking INNER JOIN tblmasterpackage 
				ON tblcabbooking.BookingType = tblmasterpackage.Package_Id INNER JOIN tblsubpackage 
				ON tblmasterpackage.Sub_Package_Id = tblsubpackage.Sub_Package_Id where tblcabbooking.ID = $booking_id") or die($con->error);
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
						$con->query("UPDATE tblcabbooking SET EstimatedDistance='".$datam['drop_distance']."' WHERE id ='".$datam['booking_id']."'");
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
					$new_query=$con->query("SELECT * FROM tblbookingregister WHERE tblbookingregister.bookingid='$book_new' and tblbookingregister.driverId='$driverId' and tblbookingregister.status='R'") or die($con->error);	
					
					$count=$new_query->num_rows;
					if($count<1){
						$driverCarType = $driver_ids['TypeOfvehicle'];
						$vehicleId = $driver_ids['vehicleId'];
						if($security>$datam['amount']){
							$res=$con->query("CALL calculate_point($driverId,'22.999999','77.66666')") or die($con->error);
							while($row=$res->fetch_assoc()){
								$point[]=array('id'=>$driverId,'vehicleId'=>$vehicleId,'point'=>$row['point']);	 
							}
							$res->close();
							$con->next_result();
						}
					}
				}	
				// print_r($point);
				rsort($point);
				$sorted=array();
				$id=array();
				for($i=0;$i<count($point);$i++){
					if($i<8){ 
						$drv=$point[$i]['id'];
						echo $drv."\n";
						$vehiclId = $point[$i]['vehicleId'];
						$book_id=$datam['booking_id'];
						$driver_gcm_id=$con->query("SELECT gcm_id,latitude,longtitude1 FROM tbluser WHERE id=$drv");
						$con->query("INSERT INTO tblbookingregister(bookingid,driverid,updateon) VALUES('$book_id','$drv',NOW())");
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
						$datam = calculateBillForBooking($datam['drop_distance'],$book_id,$bookingCarType,$datam);
						echo $bookingCarType;
						$con->query("UPDATE tblcabbooking SET estimated_price='".$datam['estimaedTotalBill']."' WHERE id ='".$datam['booking_id']."'");
						$con->query("UPDATE tblcabbooking SET EstimatedTime='".$datam['estimatedTime']."' WHERE id ='".$datam['booking_id']."'");
						$datam['estimatedTime']=$datam['pickup_time'];
						$datam['send_time']="".date("Y-m-d H:i:s")."ddd";
						print_r($datam);
						send_notification($id,$datam);	
					}
				}
				//print_r($id);
			}
		}
	}
	
?>
