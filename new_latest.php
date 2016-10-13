<?php
	
	ini_set('memory_limit', '-1');
	date_default_timezone_set('Asia/Kolkata');
	//$con=new mysqli("10.0.0.35","root","root","hello42_new");
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
		//print_r($message);
		//exit;
		// include config
		//include_once './config.php';
		//define("GOOGLE_API_KEY", "AIzaSyCOpOWLJG7G2PGHDb9uCl0eALEHYrPApVw");
		// Set POST variables
		//define("GOOGLE_API_KEY", "AIzaSyBcsz_BY5Yg03KnXCbhDwXgOlnwch9kGXE");
		define("GOOGLE_API_KEY", "AIzaSyAFE4jcZwQsi2LIx_2TfczrM3DlSTRpNYM");
		$url = 'https://android.googleapis.com/gcm/send';
		$fields = array('registration_ids' => $registatoin_ids, 'data' => json_encode($message));
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
		$con=new mysqli("10.0.0.35","root","root","hello42_new");
		$result = $con->query("CALL wp_estimatedBillNew('',$vehicleId,$BookingId_i,'',$BookingType)") or die($con->error);
		$data = $result->fetch_assoc();
		mysqli_free_result($result); 
		//mysqli_next_result($this->con);
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
	
	function IsCabtypeMatched($DriverId,$BookingId){
	$con=new mysqli("10.0.0.35","root","root","hello42_new");
	$qry = $con->query("SELECT tblcabtype.CabType FROM tblcabtype INNER JOIN tblcabbooking ON tblcabtype.Id = tblcabbooking.CarType WHERE tblcabbooking.ID = '$BookingId'") or die($con->error);
		$vehiclefetch= mysqli_fetch_object($qry);
		$BookingVehicle=$vehiclefetch->CabType;
		mysqli_free_result($qry); 
	    //mysqli_next_result($this->con);
	$Fetch = $con->query("SELECT tblcabtype.CabType FROM tblcabtype INNER JOIN tbldriver ON tblcabtype.Id = tbldriver.TypeOfvehicle WHERE tbldriver.UID = '$DriverId'") or die($con->error);

		$DrriverFetch = mysqli_fetch_object($Fetch);
		$DriverVehicle=$DrriverFetch->CabType;
		if($DriverVehicle == $BookingVehicle){			
			return true;
		}else{			
			return false;
		}				
	}
	
	
	function callNoti($driverId,$booking_id,$BookingType){
		$con=new mysqli("10.0.0.35","root","root","hello42_new");
		$IsMatchedCabType=IsCabtypeMatched($driverId,$booking_id); 
		//echo $ReciveAirPortTrns; die;
		//$PackageType	=	explode(',',$ReciveAirPortTrns);
		if($IsMatchedCabType){
		$hour = $con->query("CALL `book_fetch_info_current_notification`($booking_id,$BookingType,1,$driverId)") or die($con->error);
		}else{
		$hour = $con->query("CALL `book_fetch_info_current_notification`($booking_id,$BookingType,0,'')") or die($con->error);
		}
		//die;
		$row=mysqli_fetch_array($hour);
		mysqli_free_result($hour); 
		mysqli_next_result($con);
		//echo"<pre>";print_r($row); die;
		$datam['name']=$row['UserName'];
		$datam['EmailId']=$row['EmailId'];
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
		
		$datam['peakTimePrice']=$row['peakTimePrice'];
		$datam['peaktimeFrom']=$row['peaktimeFrom'];
		$datam['peaktimeTo']=$row['peaktimeTo'];
		$datam['peaktimepercentage']=$row['peaktimepercentage'];
		$datam['extraPrice']=$row['extraPrice'];
		$datam['nightCharge_unit']=$row['nightCharge_unit'];
				
		/// Package Code Logic Starts Here ///
		
		if($row['Sub_Package_Id'] == 1){
		$PerKmCharges = $row['Per_Km_Charge'];
		$PerHrCharges = "0";		
		$MinimumCharges = $row['MinimumCharge'];
		$MinimumDistance = $row['Min_Distance'];
		}elseif($row['Sub_Package_Id'] == 2){
		$PerKmCharges = "0";
		$PerHrCharges = $row['tripCharge_per_minute'];
		$MinimumCharges = $row['MinimumCharge'];
		$MinimumDistance = $row['Min_Distance'];
		}elseif($row['Sub_Package_Id'] == 3){
		$PerKmCharges = $row['rate_per_km_dh'];
		$PerHrCharges = $row['rate_per_hour_dh'];
		$MinimumCharges = $row['minimum_fare_dh'];
		$MinimumDistance = $row['minimum_distance_dh'];
		}elseif($row['Sub_Package_Id'] == 4){
		$PerKmCharges = $row['rate_per_km_dw'];
		$PerHrCharges = "0";
		$MinimumCharges = $row['minimum_fare_dw'];
		$MinimumDistance = $row['minimum_distance_dw'];
		}
		
		/// Package Code Logic Ends Here ///
		
				
		if($BookingType==101){	
		$datam['local_subpackage']=$row['local_subpackage'];
		$datam['MinimumCharge']=$row['Price'];
		/*$datam['Economy_Price']=$row['Economy_Price'];
		$datam['Sidan_Price']=$row['Sidan_Price'];
		$datam['Prime_Price']=$row['Prime_Price'];
		switch($bookingCarType)
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
		}else{
		$datam['MinimumCharge'] = $MinimumCharges;
		}
		
		$datam['Min_Distance'] = $MinimumDistance;
		$datam['PerKmCharges'] = $PerKmCharges;
		$datam['trip_charges_per_hour'] = $PerHrCharges;
		
		
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
		//$datam['estimaedTotalBill'] = round($TotalBill);
		$datam['estimaedTotalBill'] = $row['estimated_price'];
		$datam['estimatedTime'] = round($timeTakenHr);
			if($BookingType==102){	
			if($row['EstimatedDistance']==0){
						$data2= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$datam['pickup_latitude'].','.$datam['pickup_longitude']."&destination=".$datam['drop_latitude'].','.$datam['drop_longitude']."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584");
						$enc=json_decode($data2);
						$enc2=$enc->routes[0];
						$datam['drop_distance']=round((($enc2->legs[0]->distance->value)/1000),1);
						$con->query("UPDATE tblcabbooking SET EstimatedDistance='".$datam['drop_distance']."' WHERE id ='".$datam['booking_id']."'") or die($con->error);
					}else{
						$datam['drop_distance']=$row['EstimatedDistance'];
					}	
			}
			
		$driver_gcm_id=$con->query("SELECT gcm_id,latitude,longtitude1 FROM tbluser WHERE id='$driverId'")or die($con->error);
		$resul=$driver_gcm_id->fetch_array();
		//echo $resul=mysqli_fetch_array($driver_gcm_id); die;
		print_r($resul);//die;
		//mysqli_free_result($driver_gcm_id); 
		//mysqli_next_result($this->con);
		//print_r($datam);
		
		$data11= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$resul[1].",".$resul[2]."&destination=".$datam['pickup_latitude'].','.$datam['pickup_longitude']."&sensor=false&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584");
		//AIzaSyBcsz_BY5Yg03KnXCbhDwXgOlnwch9kGXE
		//echo $data;
		$enc=json_decode($data11);
		$enc2=$enc->routes[0];
		$datam['pickup_distance']=round((($enc2->legs[0]->distance->value)/1000),1);
		$id=array(); 
		$id[]=$resul[0];
		
		$ss=$con->query("UPDATE tblcabbooking SET estimated_price='".$datam['estimaedTotalBill']."',EstimatedTime='".$datam['estimatedTime']."' WHERE id ='".$datam['booking_id']."'") or die($con->error);
		mysqli_free_result($ss); 
		//mysqli_next_result($this->con);
		$datam['estimatedTime']=$datam['pickup_time'];
		$datam['send_time']="".date("Y-m-d H:i:s");
		print_r($datam);
		$con->query("INSERT INTO tblbookingregister(bookingid,driverid,updateon) VALUES('$booking_id','$driverId',NOW())");
		send_notification($id,$datam);		
	}
	
	
	//Find New Booking
	for(;;){
		sleep(1);
		//For unassigned Booking 
		$con->query("UPDATE booking_stack SET status='' WHERE TIMESTAMPDIFF(SECOND,last_try,NOW())>30 and status='W'") or die($con->error);
		
		$result2=$con->query("SELECT tblcabbooking.ID as ID,tblcabbooking.BookingType as BookingType,tblcabbooking.pickup as pickup,booking_stack.* from booking_stack INNER JOIN tblcabbooking ON booking_stack.booking_id = tblcabbooking.ID WHERE booking_stack.status='' and TIMESTAMPDIFF(HOUR,NOW(),CONCAT(tblcabbooking.PickupDate,' ',tblcabbooking.PickupTime))<1 and CONCAT(tblcabbooking.PickupDate,' ',tblcabbooking.PickupTime)>NOW() and tblcabbooking.pickup!=0") or die($con->error);
		while($data22=$result2->fetch_array()){
			$booking_id=$data22['ID'];
			$id=$data22['pickup'];
			$BookingType=$data22['BookingType'];
			$IsMatchedCabType=IsCabtypeMatched($id,$booking_id);
			if($IsMatchedCabType){
			$hour=$con->query("CALL `book_fetch_info_notification`($booking_id,$BookingType,1)") or die($con->error);
			}else{
			$hour=$con->query("CALL `book_fetch_info_notification`($booking_id,$BookingType,0)") or die($con->error);
			}
			$row=mysqli_fetch_array($hour);
			$datam['name']=$row['UserName'];
			$datam['EmailId']=$row['EmailId'];
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
			
			$datam['peakTimePrice']=$row['peakTimePrice'];
			$datam['peaktimeFrom']=$row['peaktimeFrom'];
			$datam['peaktimeTo']=$row['peaktimeTo'];
			$datam['peaktimepercentage']=$row['peaktimepercentage'];
			$datam['extraPrice']=$row['extraPrice'];
			$datam['nightCharge_unit']=$row['nightCharge_unit'];		
			
			/// Package Code Logic Starts Here ///
			
			if($row['Sub_Package_Id'] == 1){
			$PerKmCharges = $row['Per_Km_Charge'];
			$PerHrCharges = "0";		
			$MinimumCharges = $row['MinimumCharge'];
			$MinimumDistance = $row['Min_Distance'];
			}elseif($row['Sub_Package_Id'] == 2){
			$PerKmCharges = "0";
			$PerHrCharges = $row['tripCharge_per_minute'];
			$MinimumCharges = $row['MinimumCharge'];
			$MinimumDistance = $row['Min_Distance'];
			}elseif($row['Sub_Package_Id'] == 3){
			$PerKmCharges = $row['rate_per_km_dh'];
			$PerHrCharges = $row['rate_per_hour_dh'];
			$MinimumCharges = $row['minimum_fare_dh'];
			$MinimumDistance = $row['minimum_distance_dh'];
			}elseif($row['Sub_Package_Id'] == 4){
			$PerKmCharges = $row['rate_per_km_dw'];
			$PerHrCharges = "0";
			$MinimumCharges = $row['minimum_fare_dw'];
			$MinimumDistance = $row['minimum_distance_dw'];
			}
			
			/// Package Code Logic Ends Here ///
			
			if($BookingType==101){	
			$datam['local_subpackage']=$row['local_subpackage'];
			$datam['MinimumCharge']=$row['Price'];
			/*$datam['Economy_Price']=$row['Economy_Price'];
			$datam['Sidan_Price']=$row['Sidan_Price'];
			$datam['Prime_Price']=$row['Prime_Price'];
			switch($bookingCarType)
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
			
			$datam['MinimumCharge'] = $MinimumCharges;
			$datam['Min_Distance'] = $MinimumDistance;
			$datam['PerKmCharges'] = $PerKmCharges;
			$datam['trip_charges_per_hour'] = $PerHrCharges;

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
			//$datam['estimaedTotalBill'] = round($TotalBill);
			$datam['estimaedTotalBill'] = $row['estimated_price'];
			$datam['estimatedTime'] = round($timeTakenHr);
				if($BookingType==102){	
				if($row['EstimatedDistance']==0){
							$data2= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$datam['pickup_latitude'].','.$datam['pickup_longitude']."&destination=".$datam['drop_latitude'].','.$datam['drop_longitude']."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584");
							$enc=json_decode($data2);
							$enc2=$enc->routes[0];
							$datam['drop_distance']=round((($enc2->legs[0]->distance->value)/1000),1);
							$con->query("UPDATE tblcabbooking SET EstimatedDistance='".$datam['drop_distance']."' WHERE id ='".$datam['booking_id']."'");
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
			$con->query("UPDATE tblcabbooking SET estimated_price='".$datam['estimaedTotalBill']."',EstimatedTime='".$datam['estimatedTime']."' WHERE id ='".$datam['booking_id']."'") or die($con->error);
			$datam['estimatedTime']=$datam['pickup_time'];
			$datam['send_time']="".date("Y-m-d H:i:s");
			print_r($datam);
			send_notification($id,$datam);		
		}
		

		//For Automatic Booking
		$booking_query=$con->query("SELECT booking_stack.*,tblcabbooking.CarType,tblcabbooking.BookingType FROM booking_stack INNER JOIN tblcabbooking ON booking_stack.booking_id = tblcabbooking.ID WHERE booking_stack.status='' and TIMESTAMPDIFF(HOUR,NOW(),CONCAT(tblcabbooking.PickupDate,' ',PickupTime))<1 and CONCAT(tblcabbooking.PickupDate,' ',PickupTime)>NOW() and pickup=0 and is_updation_allow!='FALSE'");
		$booking=array();
		while($row=$booking_query->fetch_array()){
			 $booking[]=$row;
		}
		
		
		$driver_query=$con->query("SELECT tbldriver.SecurityAmt,tbldriver.ReciveAirPortTrns,tbldriver.UID,tbldriver.TypeOfvehicle,tbldriver.vehicleId FROM tbldriver JOIN tbluser ON tbldriver.uid=tbluser.id WHERE tbldriver.status=0 AND tbluser.loginstatus=1 and tbluser.is_active=1 and SecurityAmt>0") or die($con->error);

		$driver_id=array();
		while($driver=$driver_query->fetch_assoc()){
			$driver_id[]=$driver;
		}
		
		
		foreach($booking as $bookings){
			$bookingCarType = $bookings['CarType'];
			$BookingType = $bookings['BookingType']; 
			if($bookings['status']==''){
				$booking_id=$bookings['booking_id'];
				
				$con->query("UPDATE booking_stack SET last_try=NOW(),status='W' WHERE booking_id='$booking_id'");
				$con->query("UPDATE tblcabbooking SET `status`=2 WHERE id='$booking_id'");
				if($con->affected_rows>0){
					$con->query("INSERT INTO tblbookinglogs (`bookingid`,`status`,`message`,`time`) VALUES('$booking_id',2,'Executed',NOW())");
				}
				
				$datam=array();
				//$rees=$con->query("SELECT * FROM tblcabbooking WHERE ID=$booking_id") or die($con->error);
				
				//// Mine //////////
				switch($bookingCarType)
				{
				case 1:
				foreach($driver_id as $driver_ids){
					
					$driverType	= $driver_ids['TypeOfvehicle']; // 1 or 2 or 3
					$driverId = $driver_ids['UID'];
					$security=$driver_ids['SecurityAmt'];
					$ReciveAirPortTrns=$driver_ids['ReciveAirPortTrns']; //// 101,102,103
					if (strpos($ReciveAirPortTrns, $BookingType) !== false) {	
					$new_query=$con->query("SELECT * FROM tblbookingregister WHERE tblbookingregister.bookingid='$booking_id' and tblbookingregister.driverId='$driverId' and tblbookingregister.status='R'");					
					$count=$new_query->num_rows;
					if($count<1 && $security>0){
							callNoti($driverId,$booking_id,$BookingType);
					}
					}
					
				}
				break;
				case 2:
				foreach($driver_id as $driver_ids){
					$driverType = $driver_ids['TypeOfvehicle'];
					$driverId = $driver_ids['UID'];
					$security=$driver_ids['SecurityAmt'];
					$ReciveAirPortTrns=$driver_ids['ReciveAirPortTrns'];
					if (strpos($ReciveAirPortTrns, $BookingType) !== false) {		
					$new_query=$con->query("SELECT * FROM tblbookingregister WHERE tblbookingregister.bookingid='$booking_id' and tblbookingregister.driverId='$driverId' and tblbookingregister.status='R'");					
					$count=$new_query->num_rows;
					if($count<1 && $security>0 && $driverType != 1){
							callNoti($driverId,$booking_id,$BookingType);
					}
					}
				}
				break;
				case 3:
				foreach($driver_id as $driver_ids){
					$driverType = $driver_ids['TypeOfvehicle'];
					$driverId = $driver_ids['UID'];
					$security=$driver_ids['SecurityAmt'];
					$ReciveAirPortTrns=$driver_ids['ReciveAirPortTrns'];	
					if (strpos($ReciveAirPortTrns, $BookingType) !== true) {		
					$new_query=$con->query("SELECT * FROM tblbookingregister WHERE tblbookingregister.bookingid='$booking_id' and tblbookingregister.driverId='$driverId' and tblbookingregister.status='R'");	
					$count=$new_query->num_rows;
					if($count<1 && $security>0 && $driverType==3){
							callNoti($driverId,$booking_id,$BookingType);
					}
					}
					
				}
				break;
				}
				
				//// Mine ///////////////
				
				//die;
			}
		}
	}
	
?>
