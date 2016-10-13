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

class BookingCabFare{	
	protected $con;
	private $data = array();
	private $row = array();	
	public function __construct(){		
		date_default_timezone_set("Asia/Kolkata");
		$db=new DatabaseConfig();
		$this->con=$db->getDatabaseConfig();
		$key = '';
		//$this->con=mysqli_connect("10.0.0.35","root","root","hello42_new");
		//$this->con=mysqli_connect("103.228.152.14","hello42c_hello","hello(42)","hello42c_new");
		//$this->con=mysqli_connect("103.228.152.14","hello42c_hello","hello(42)","hello42c_db_hello42");
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

	//for testing purpose
	public function munesh()
	{
	$qry='SELECT * FROM login';
	$result = mysqli_query($this->con, $qry);
	
	return $result;


	}
	// for testing purpose





	public function mohit(){
		//echo "Mohit"; //die;
		//return "mohit";
		echo $pickupTime='18:00:00';
		echo ' Pt<br>';
		echo $nightRateBegins='14:00:00';
		echo ' NRB<br>';
		echo $nigthRateEnds='04:00:00';
		echo ' NRE<br>';
		echo $nightChargeUnit='Rs';
		echo ' NCU<br>';
		echo $NightCharges='10';
		echo ' NC<br>';
		
	
		
		if(strtotime($pickupTime) <= strtotime($nightRateBegins) && strtotime($pickupTime) < strtotime($nigthRateEnds)){
		 $nightRateEnds=strtotime($nigthRateEnds);
		 	if(strtotime($pickupTime)< $nightRateEnds){
			 	if($nightChargeUnit == 'Rs'){
					$NightCharges1 = $NightCharges;
				}else{
					$NightCharges1 = ($totalbill * $NightCharges)/100;
				}
		 	}
		 }elseif(strtotime($pickupTime) >= strtotime($nightRateBegins) && strtotime($pickupTime) > strtotime($nigthRateEnds)){
		 	$nightRateEnds = strtotime($nigthRateEnds)+60*60*24;
		 	if($nightRateEnds>strtotime($pickupTime)){
		 	if($nightChargeUnit == 'Rs'){
					$NightCharges1 = $NightCharges;
				}else{
					$NightCharges1 = ($totalbill * $NightCharges)/100;
				}
		 		
		 	}
			 	
		 	
		 }
		
		
		echo $NightCharges1;
		
		
	}
	
	
	/// API to show package For CALL CENTER Starts Here /////
	
	public function ShowNewpackege(){
		//CALL sp_package_show_new('$CityCode')		
		$stateName = str_replace('_',' ',$_REQUEST['CityCode']);
		$sql1="select id from tblstates where state='$stateName' limit 1";
		$result = mysqli_query($this->con, $sql1);
		$val = mysqli_fetch_object($result);
		$id=$val->id;
		
		$qry="SELECT distinct t1.master_package_ref as Package,t1.Package_Id as master_package from tblmasterpackage t1 INNER JOIN tbl_package_city_code t2 ON t2.package_code=t1.Package_Id WHERE t2.city_code='$id' and t2.status=1 order by t1.master_package_ref"; 
		$result1 = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result1);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		
		while($info = mysqli_fetch_object($result1))
		{
			$record[]=$info;		  
		}
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}
	
	/// API to show package For CALL CENTER Ends Here /////
	
	/// API to show Local Package Details FOR CALL CENTER Starts Here /////
	
	public function FetchLocalSub_Package1(){
		/*Testing URL
		http://10.0.0.26/hello42/tunnel/BookingCabFare/FetchLocalSub_Package1?Package=101&state=Delhi&CabType=1
		*/
		$Package = str_replace('_','/',$_REQUEST['Package']);
		$Package = str_replace('$',' ',$Package);
		$state = str_replace('_',' ',$_REQUEST['state']);
		$CabType =  $_REQUEST['CabType'];			
		 $qry = "SELECT  distinct t1.Package,t1.Hrs,t1.Km,t1.Price as Price FROM `tbllocalpackage` t1
		 INNER JOIN tblbookingbill t2 ON t1.City_ID=t2.CityId
		 INNER JOIN tblstates t3 ON t1.City_ID=t3.id
		 INNER JOIN tblmasterpackage t4 ON t4.Sub_Package_Id=t1.Sub_Package_Id AND t1.masterPKG_ID=t4.Package_Id
		 WHERE t1.masterPKG_ID='$Package' and t3.state='$state' 
		 and t2.BookingTypeId='$Package' and t2.CabType='$CabType' and t1.CabType='$CabType' and t1.Status=1 order by t1.id"; //die;
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
			$status="false";
		$record=array();
		while($info = mysqli_fetch_assoc($result)){
				/* if($CabType==1){
					$price=$info["Economy"];
				}
				elseif($CabType==2){
					$price=$info["Sidan"];
				}
				elseif($CabType==3){
					$price=$info["Prime"];
				} */
				//$price=$info["Price"];
			    $data["sub_package"]=$info["Package"];
				$data["Hrs"]=$info["Hrs"];
				$data["Km"]=$info["Km"];
				$data["price"]=$info["Price"];
				//$data["price"]=$info["Price"].'_'.$info["Km"].'_'.$price;
				$record[]=$data;
		}
		
		return array("status"=>$status,"record"=>$record);
	}
	
	/// API to show Local Package Details FOR CALL CENTER Ends Here /////
	
	/// API to show Airport Package Details FOR CALL CENTER Starts Here /////
	
	public function FetchAirportPkgDetail(){		
		$Package = str_replace('_','/',$_REQUEST['Package']);
		$Package = str_replace('$',' ',$Package);
		$state = str_replace('_',' ',$_REQUEST['state']);
		if($Package=='103'){
			/*$qry = "SELECT t1.pkgName as Address,t1.pkgName as Area,t1.zone,t1.lat as latitude,t1.long as longitude,t3.name as CityName FROM `tblairportpackage` t1 INNER JOIN tblstates t2 ON t1.package_city_code=t2.id INNER JOIN tblcity t3 ON t3.state=t2.id WHERE  t1.masterPKG_ID='$Package' and t2.state='$state' and t1.Status=1 order by t1.pkgName";*/
			$qry = "SELECT t1.pkgName as Address,t1.pkgName as Area,t1.zone,t1.lat as latitude,t1.long as longitude,t2.state as CityName FROM `tblairportpackage` t1 INNER JOIN tblstates t2 ON t1.package_city_code=t2.id WHERE  t1.masterPKG_ID='$Package' and t2.state='$state' and t1.Status=1 order by t1.pkgName";
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
	
	/// API to show Airport Package Details FOR CALL CENTER Ends Here /////	
	
	/// API to Fetch Local Package Details FOR (ANDRIOD, WEB, CALL CENTER) Starts Here /////

	public function FetchLocalPackageData($booking,$cablocalfor,$stateId,$carTypes){ /// Used in Live
	/// Used in Testing Starts Here ///
	//public function FetchLocalPackageData(){ /// Used in Testing Starts Here ///
		//$booking is used for Booking Code(eg- 101)
		//$cablocalfor is used for Local Package (eg- 4hrs-40km)
		//$stateId is used for city code(eg -2 (new delhi) )
		//$booking='101';
		//$cablocalfor='10Hrs';
		//$stateId='2';
		//$carTypes='1';
	/// Used in Testing Ends Here ///	
		
		
		$datam1=array();
		
		//// MAKE INNER JOIN FROM TBL MASTERPACKAGE TO CALCULATE EXTRA FARE ////
		
		$sql1 = "select Sub_Package_Id from tblmasterpackage WHERE Package_Id='$booking' AND state_id='$stateId'";
		$row1 = mysqli_query($this->con,$sql1);		
		$res = mysqli_fetch_array($row1);
		$Sub_Package_Id	=	$res['Sub_Package_Id'];
		
		$sql = "SELECT * FROM `tbllocalpackage` WHERE Package='$cablocalfor' AND masterPKG_ID='$booking' AND Sub_Package_Id='$Sub_Package_Id' AND City_ID='$stateId' AND cabType='$carTypes'"; //die;
		$row = mysqli_query($this->con,$sql);		
		$date = mysqli_fetch_object($row);
		$num_rows = mysqli_num_rows($row);
		
		$status=0;
		$Min_Pkg_KM='';
		$Min_Pkg_Hrs='';
		if($num_rows == 0)
		{
		$status=1;
		$ignore_hrs=0;
		$ignore_km=0;
		$minimumCharge=0;	
		}
		
		else{
			
		//$datam1['Min_Pkg_KM'] = $date->Km;
		//$datam1['Min_Pkg_Hrs'] = $date->Hrs;
		$Min_Pkg_KM=$date->Km;
		$Min_Pkg_Hrs=$date->Hrs;
		$totalbill=$date->Price;
		$ignore_hrs=$date->Hrs;
		$ignore_km=$date->Km;
		$minimumCharge=$totalbill;
			
		}
		
		$val=$this->FetchCalculationType($booking,$carTypes,$stateId,$distance,$minimumCharge,$ignore_hrs,$ignore_km,$status);
		//echo"<pre>";print_r($val); die;
		
		$datam1=$val;
		if($totalbill>$datam1['totalbill']){
		$datam1['totalbill']=$totalbill;	
		}else{
		$datam1['totalbill']=$datam1['totalbill'];	
		}
		
		if($datam1['Min_Pkg_KM']!=""){
		$Min_Pkg_KM=$datam1['Min_Pkg_KM'];
		}else{
		$Min_Pkg_KM=$Min_Pkg_KM;
		}
		
		if($datam1['Min_Pkg_Hrs']!=""){
		$Min_Pkg_Hrs=$datam1['Min_Pkg_Hrs'];
		}else{
		$Min_Pkg_Hrs=$Min_Pkg_KM;
		}
		
		$datam1['Min_Pkg_KM'] = $Min_Pkg_KM;
		$datam1['Min_Pkg_Hrs'] = $Min_Pkg_Hrs;
		
		//$val=$this->FetchBookingBill($booking,$carTypes,$stateId);
		/* $datam1['Per_Km_Charge']=$val["Per_Km_Charge"];
		$datam1['Min_Distance']=$val["Min_Distance"];
		$datam1['WaitingCharge_per_minute']=$val["WaitingCharge_per_minute"];
		$datam1['Waitning_minutes']=$val["Waitning_minutes"];
		
		$datam1['MinimumCharge']=$val["MinimumCharge"];
		$datam1['rate_upto_distance']=$val["rate_upto_distance"];
		$datam1['waitingfee_upto_minutes']=$val["waitingfee_upto_minutes"];
		$datam1['NightCharges']=$val["NightCharges"];
		$datam1['nightCharge_unit']=$val["nightCharge_unit"];
		$datam1['night_rate_begins']=$val["night_rate_begins"];
		$datam1['night_rate_ends']=$val["night_rate_ends"];
		$datam1['extras']=$val["extras"]; */
		
		return $datam1;
	}
	
	/// API to Fetch Local Package Details FOR (ANDRIOD, WEB, CALL CENTER) Ends Here /////
	
	/// API to Fetch Base Fare Details FOR (ANDRIOD, WEB, CALL CENTER) Starts Here /////
	
	public function FetchBookingBill($booking,$carTypes,$stateId){
		/* $query1 = "SELECT MinimumCharge,Per_Km_Charge,Min_Distance,WaitingCharge_per_minute,Waitning_minutes,rate_upto_distance,waitingfee_upto_minutes,NightCharges,night_rate_begins,night_rate_ends FROM `tblbookingbill` WHERE BookingTypeId='$booking' AND CabType='$carTypes' AND CityId='$stateId'"; */
		$query1 = "SELECT * FROM `tblbookingbill` WHERE BookingTypeId='$booking' AND CabType='$carTypes' AND CityId='$stateId'";
		$ReCorD =mysqli_fetch_assoc(mysqli_query($this->con,$query1));
		return $ReCorD;
	}
	
	/// API to Fetch Base Fare Details FOR (ANDRIOD, WEB, CALL CENTER) Ends Here /////
	
	/// API to Intercity Fare and Distance Details Starts Here /////
	
	public function FetchIntercityDistanceData($routeId,$carType){
		
		$query1 = "SELECT economy_rate,sedan_rate,prime_rate,fix_km FROM `tbl_inter_city_route_package` WHERE route_id='$routeId' AND status='1'";
		$res=mysqli_query($this->con,$query1);
		$val =mysqli_fetch_assoc($res);
		if($carType==1){
			$ReCorD['total_amt']=$val['economy_rate'];
		}elseif($carType==2){
			$ReCorD['total_amt']=$val['sedan_rate'];
		}elseif($carType==3){
			$ReCorD['total_amt']=$val['prime_rate'];
		}
		$ReCorD['fix_km']=$val['fix_km'];
		return $ReCorD;
	}
	
	/// API to Intercity Fare and Distance Details Ends Here /////
	
	/// API to Fetch POINT TO POINT Details FOR (ANDRIOD, WEB, CALL CENTER) STARTS Here /////
	
	public function FetchPointToPointData($bookingType,$carType,$cityId,$distance,$cablocalfor){
		
		$datam1=array();
		
		//// MAKE INNER JOIN FROM TBL MASTERPACKAGE TO CALCULATE EXTRA FARE ////
		
		$sql1 = "select Sub_Package_Id from tblmasterpackage WHERE Package_Id='$bookingType' AND state_id='$cityId'";
		$row1 = mysqli_query($this->con,$sql1);		
		$res = mysqli_fetch_array($row1);
		$Sub_Package_Id	=	$res['Sub_Package_Id'];
		
		if($cablocalfor!=""){		
		$sql = "SELECT * FROM `tbllocalpackage` WHERE Package='$cablocalfor' AND masterPKG_ID='$bookingType' AND Sub_Package_Id='$Sub_Package_Id' AND City_ID='$stateId' AND cabType='$carTypes'"; //die;
		$row = mysqli_query($this->con,$sql);		
		$date = mysqli_fetch_object($row);
		$num_rows = mysqli_num_rows($row);
		
		$status=0;
		$Min_Pkg_KM='';
		$Min_Pkg_Hrs='';
		if($num_rows > 0)
		{	
		$Min_Pkg_KM=$date->Km;
		$Min_Pkg_Hrs=$date->Hrs;
		$totalbill=$date->Price;
		$ignore_hrs=$date->Hrs;
		$ignore_km=$date->Km;
		$minimumCharge=$totalbill;
		}}
		else{
		$status=1;
		$ignore_hrs=0;
		$ignore_km=0;
		$minimumCharge=0;		
		}

		$val=$this->FetchCalculationType($bookingType,$carType,$cityId,$distance,$minimumCharge,$ignore_hrs,$ignore_km,$status);
		//echo"<pre>";print_r($val); die;
		
		
		$datam1=$val;
		if($totalbill>$datam1['totalbill']){
		$datam1['totalbill']=$totalbill;	
		}else{
		$datam1['totalbill']=$datam1['totalbill'];	
		}
		
		if($datam1['Min_Pkg_KM']!=""){
		$Min_Pkg_KM=$datam1['Min_Pkg_KM'];
		}else{
		$Min_Pkg_KM=$Min_Pkg_KM;
		}
		
		if($datam1['Min_Pkg_Hrs']!=""){
		$Min_Pkg_Hrs=$datam1['Min_Pkg_Hrs'];
		}else{
		$Min_Pkg_Hrs=$Min_Pkg_KM;
		}
		
		$datam1['Min_Pkg_KM'] = $Min_Pkg_KM;
		$datam1['Min_Pkg_Hrs'] = $Min_Pkg_Hrs;
		
		return $datam1;
		
	}
	
	/// API to Fetch POINT TO POINT Details FOR (ANDRIOD, WEB, CALL CENTER) ENDS Here /////
	
	/// API to Fetch Point To Point Fare Details Starts Here /////
	
	/* public function FetchPointToPointData($bookingType,$carType,$cityId,$distance){
	$datam1=array();
	$data =$this->FetchBookingBill($bookingType,$carType,$cityId);			       

	
	$sqlite = "SELECT * FROM `tblmasterpackage` where Package_Id='$bookingType' and state_id='$cityId'"; 
	$rowlite = mysqli_query($this->con,$sqlite);
	$subpackage = mysqli_fetch_assoc($rowlite);
	$permntavr=40/60;
	$totalmint=round($distance/$permntavr);
	
	if($subpackage['Sub_Package_Id'] == 1){
	if($distance > $data['Min_Distance']){
	
	$ExtraKM=$distance - $data['Min_Distance'];
	$ExtraFare = $ExtraKM*$data["Per_Km_Charge"];
	$EstimatedPrice = $ExtraFare + $data["MinimumCharge"];	
	}else{
	$EstimatedPrice = $data["MinimumCharge"];								
	}
	$datam1['Per_Km_Charge']=$data["Per_Km_Charge"];
	$datam1['Min_Distance']=$data["Min_Distance"];
	$datam1['MinimumCharge']=$data["MinimumCharge"];
	}

	
	elseif($subpackage['Sub_Package_Id'] == 2){
	$ignore_first_hours=$data["ignore_first_hours"]*60;
	if($totalmint > $ignore_first_hours){
	$rest_min=$totalmint-$ignore_first_hours;
	$ExtraFare=($rest_min/60)*$data["tripCharge_per_minute"];
	$EstimatedPrice = $ExtraFare + $data["minimum_hourly_Charge"];
	}else{
	$EstimatedPrice = $data["minimum_hourly_Charge"];
	}
	$datam1['Per_Km_Charge']=$data["tripCharge_per_minute"]/40;
	$datam1['Min_Distance']=$data["ignore_first_hours"]*40;
	$datam1['MinimumCharge']=$data["minimum_hourly_Charge"];
	}
	
	elseif($subpackage['Sub_Package_Id'] == 3){
	if($distance > $data['minimum_distance_dh']){	
	$ExtraKM=$distance - $data['minimum_distance_dh'];
	$EstimatedPrice_PerKm = $ExtraKM*$data["rate_per_km_dh"];
	$EstimatedPrice_PerHr = $totalmint*$data["rate_per_hour_dh"];
	if($EstimatedPrice_PerKm > $EstimatedPrice_PerHr){
	$EstimatedPrice = $EstimatedPrice_PerKm;
	$datam1['Per_Km_Charge']=$data["rate_per_km_dh"];
	}else{
	$EstimatedPrice = $EstimatedPrice_PerHr;
	$datam1['Per_Km_Charge']=$data["rate_per_hour_dh"]/40;
	}
	}else{
	$EstimatedPrice = $data["minimum_fare_dh"];	
	$datam1['Per_Km_Charge']=$data["rate_per_km_dh"];
	}
	$datam1['Min_Distance']=$data["minimum_distance_dh"];
	$datam1['MinimumCharge']=$data["minimum_fare_dh"];
	}
		
	
	elseif($subpackage['Sub_Package_Id'] == 4){
	if($distance > $data['minimum_distance_dw']){
	$ExtraKM=$distance - $data['minimum_distance_dw'];
	$ExtraFare = $ExtraKM*$data["rate_per_km_dw"];
	$EstimatedPrice = $ExtraFare + $data["minimum_fare_dw"];
	}
	else{
	$EstimatedPrice = $data["minimum_fare_dw"];								
	}
	$datam1['Per_Km_Charge']=$data["rate_per_km_dw"];
	$datam1['Min_Distance']=$data["minimum_distance_dw"];
	$datam1['MinimumCharge']=$data["minimum_fare_dw"];
	}
	
					
	$query = "SELECT * FROM `tblpeaktime` WHERE ('$pickupTime' BETWEEN timeFrom AND timeTo)";
	$fetch = mysqli_query($this->con,$query);
	if(mysqli_num_rows($fetch)>0){
	$vaLue=mysqli_fetch_assoc($fetch);
	$PeakChargPercent=$vaLue["peakCharges"];
	$PeakFare=$EstimatedPrice/$PeakChargPercent;
	$EstimatedPrice=$EstimatedPrice+$PeakFare;
	}					

	$BasicTax = ($EstimatedPrice*$data['basic_tax'])/100;				
	$totalbill = $EstimatedPrice + $BasicTax;


	
	
	$datam1['totalbill']=$totalbill;
	$datam1['WaitingCharge_per_minute']=$data["WaitingCharge_per_minute"];
	$datam1['Waitning_minutes']=$data["Waitning_minutes"];

	$datam1['rate_upto_distance']=$data["rate_upto_distance"];
	$datam1['waitingfee_upto_minutes']=$data["waitingfee_upto_minutes"];
	if($data["nightCharge_unit"]=='Rs'){
	$datam1['NightCharges']=$data["NightCharges"];
	}else{
	$datam1['NightCharges']=($totalbill*$data["NightCharges"])/100;	
	}
	$datam1['night_rate_begins']=$data["night_rate_begins"];
	$datam1['night_rate_ends']=$data["night_rate_ends"];
	
	return $datam1;
		
	} */
	
	/// API to Fetch Point To Point Fare Details Ends Here /////
	
	public function FetchDriverCompanyShare($totalbill){
	$datam1=array();
	$sql = "SELECT * FROM `tblclient` where id='1'"; 
	$row = mysqli_query($this->con,$sql);
	$res = mysqli_fetch_array($row);
	$driver_share	= 	$res['driver_share'];
	$partner_share	=	$res['partner_share'];
	$company_share	=	$res['company_share'];
	$datam1['driver_share_amt']		=	($totalbill/100)*$driver_share;
	$datam1['partner_share_amt']	=	($totalbill/100)*$partner_share;
	$datam1['company_share_amt']	=	($totalbill/100)*$company_share;
	return $datam1;
	}
	
	/// API to Fetch Airport Fare Details Starts Here /////
	
	/* public function FetchAirportDistanceFare($distance){
		$datam1=array();
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
			$datam1['min_distance'] = $info['MinKm'];
			$datam1['totalbill'] = $info['MinFare'];
		}
		else
		{
			$datam1['min_distance'] = $info['MaxKm'];
			$datam1['totalbill'] = $info['MaxFare'];
		}
		
		return $datam1;
	} */
	
	
	/// API to Fetch AIRPORT Details FOR (ANDRIOD, WEB, CALL CENTER) STARTS Here /////
		
	public function FetchAirportDistanceFare($bookingType,$aircartypes,$stateId,$distance,$cabairportfor,$Address){ /// Used in Live
	/// Used in Testing Starts Here ///
	//public function FetchLocalPackageData(){ /// Used in Testing Starts Here ///
		//$booking is used for Booking Code(eg- 101)
		//$cablocalfor is used for Local Package (eg- 4hrs-40km)
		//$stateId is used for city code(eg -2 (new delhi) )
		//$booking='101';
		//$cablocalfor='10Hrs';
		//$stateId='2';
		//$carTypes='1';
	/// Used in Testing Ends Here ///	
		
		
		$datam1=array();
		$dist = round($distance);
		//// MAKE INNER JOIN FROM TBL MASTERPACKAGE TO CALCULATE EXTRA FARE ////
		
		$sql1 = "select Sub_Package_Id from tblmasterpackage WHERE Package_Id='$bookingType' AND state_id='$stateId'";
		$row1 = mysqli_query($this->con,$sql1);		
		$res = mysqli_fetch_array($row1);
		$Sub_Package_Id	=	$res['Sub_Package_Id'];
		
		//$sql = "SELECT * FROM `tblairportaddress` WHERE Address='$Address' AND Fix_Point='$cabairportfor' AND masterPKG_ID='$bookingType' AND Sub_Package_Id='$Sub_Package_Id' AND cityId='$stateId' AND cabType='$aircartypes'"; //die;
		$sql = "SELECT * FROM `tblairportaddress` WHERE '$Address' LIKE concat('%',`Address`,'%') AND '$cabairportfor' like concat('%',`transfer_type`,'%') AND masterPKG_ID='$bookingType' AND Sub_Package_Id='$Sub_Package_Id' AND cityId='$stateId' AND cabType='$aircartypes' LIMIT 1";
		$row = mysqli_query($this->con,$sql);		
		$date = mysqli_fetch_object($row);
		$num_rows = mysqli_num_rows($row);
		
		$status=0;
		$Min_Pkg_KM='';
		$Min_Pkg_Hrs='';
		if($num_rows == 0)
		{
		$status=1;
		$ignore_hrs=0;
		$ignore_km=0;
		$minimumCharge=0;	
		}
		
		else{			
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
		//$datam1['min_distance'] = $info['MinKm'];
		//$datam1['totalbill'] = $info['MinFare'];
		$Min_Pkg_KM=$info['MinKm'];
		$Min_Pkg_Hrs=0;
		$totalbill= $info['MinFare'];
		$ignore_hrs=0;
		$ignore_km=$info['MinKm'];
		$minimumCharge=$totalbill;		
		}
		else
		{
		//$datam1['min_distance'] = $info['MaxKm'];
		//$datam1['totalbill'] = $info['MaxFare'];
		$Min_Pkg_KM=$info['MaxKm'];
		$Min_Pkg_Hrs=0;
		$totalbill=$info['MaxFare'];
		$ignore_hrs=0;
		$ignore_km=$info['MaxKm'];
		$minimumCharge=$totalbill;
		
		}	
		}
		//echo $distance.','.$minimumCharge.','.$ignore_hrs.','.$ignore_km;
		//die;

		$val=$this->FetchCalculationType($bookingType,$aircartypes,$stateId,$dist,$minimumCharge,$ignore_hrs,$ignore_km,$status);
		//echo"<pre>";print_r($val); die;
		
		$datam1=$val;
		if($totalbill>$datam1['totalbill']){
		$datam1['totalbill']=$totalbill;	
		}else{
		$datam1['totalbill']=$datam1['totalbill'];	
		}
		
		if($datam1['Min_Pkg_KM']>$dist){
		$Min_Pkg_KM=$datam1['Min_Pkg_KM'];
		}else{
		$Min_Pkg_KM=$dist;
		}
		
		if($datam1['Min_Pkg_Hrs']!=""){
		$Min_Pkg_Hrs=$datam1['Min_Pkg_Hrs'];
		}else{
		$Min_Pkg_Hrs=$Min_Pkg_KM;
		}
		
		$datam1['Min_Pkg_KM'] = $Min_Pkg_KM;
		$datam1['Min_Pkg_Hrs'] = $Min_Pkg_Hrs;

		
		return $datam1;
	}
		
	/// API to Fetch AIRPORT Details FOR (ANDRIOD, WEB, CALL CENTER) ENDS Here /////
	
	//////////// API TO fetch User Data FOR (ANDRIOD, WEB) Starts Here///////////////////
	
	public function FetchUserId($emailIds,$mobileNo,$userNames){
		$sql="SELECT * FROM tbluser WHERE LoginName='$emailIds' LIMIT 0,1";
		$no_of_rows=mysqli_query($this->con,$sql);
		if(mysqli_num_rows($no_of_rows)==0){
			mysqli_query($this->con,"INSERT INTO tbluser (`LoginName`,`UserNo`) VALUES('$emailIds','$mobileNo')");
			$user_id=mysqli_insert_id($this->con);
			mysqli_query($this->con,"INSERT INTO tbluserinfo(`FirstName`,`UID`,`MobNo`,`Email`) VALUES('$userNames','$user_id','$mobileNo','$emailIds')"); 
		}else{
			$result_new=mysqli_fetch_array($no_of_rows);
			$user_id=$result_new['ID'];
		}
		return $user_id;
	}
	//////////// API TO fetch User Data FOR (ANDRIOD, WEB) Ends Here///////////////////
	
	
	//////////// API TO fetch User Data FOR (CALL CENTER) Starts Here///////////////////
	
	public function FetchCallCenterUserId($EmailID,$CallerID,$UserType,$CallerName,$AlternateNumber){
		if($EmailID==""){
		$sql_sel="SELECT * FROM tbluser WHERE UserNo='$CallerID' LIMIT 0,1";	
		}else{
		$sql_sel="SELECT * FROM tbluser WHERE LoginName='$EmailID' or UserNo='$CallerID' LIMIT 0,1";	
		}
		 
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
		return $user_id;
	}
	
	//////////// API TO fetch User Data FOR (CALL CENTER) Ends Here///////////////////
	
	//////////// API TO CHECK COUPAN CODE FOR(ANDROID, WEB, CALL CENTER) Starts Here///////////////////
	
	public function CheckCoupanCode($local_coupan_id,$localPhone,$localEmail){
		$coupan_id = $local_coupan_id;
		$userno = $localPhone;
		$email = $localEmail;
		$sql = "select * from tbluser tu inner join tblcouponmaster tcm on tcm.userid=tu.ID
where userno ='$userno' and loginname='$email' and tcm.CouponID='$coupan_id'";
		$result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
			if(mysqli_num_rows($result)>0){
				$status = "true";
				//echo "this coupan is already used. please change the Coupan code";
			}else{
			    $status = "false";
				
			}	
			return $status;
	}
	
	//////////// API TO CHECK COUPAN CODE FOR(ANDROID, WEB, CALL CENTER) Ends Here///////////////////
	
	//////////// API for Local Booking used in ANDRIOD and WEB only Starts Here/////////////////////
	
	public function localBooking(){					
		$UserId = 0;
		file_put_contents('local.txt',print_r($_REQUEST,true));		
			@$token=$_REQUEST['token'];
			
			//$this->NewCheckWebCallAnd($new,'HA');
		$localPhone = $_REQUEST['phone'];
		$localEmail =$_REQUEST['email'];
		$PromoName=$_REQUEST['PromotionName'];
		$local_coupan_id=$_REQUEST['local_coupan_id'];
		$local_DiscountType=$_REQUEST['local_DiscountType'];
		$local_coupanDisount=$_REQUEST['local_coupanDisount'];
		$local_MinimumBookingAmount=$_REQUEST['local_MinimumBookingAmount'];
		$Coupon_Name=$_REQUEST['CouponName'];
		if($local_coupanDisount=='' or $local_coupanDisount==NULL)
		{
			 $PromotionName = "	";
			 $CouponName = "";
		}
		else
		{
		     $PromotionName = $PromoName;
			 $CouponName = $Coupon_Name;
		}
		if($local_coupan_id!="")
		{
			$coupan_status = $this->CheckCoupanCode($local_coupan_id,$localPhone,$localEmail);
			
		}
		if($coupan_status == "true")
		{
			 return array('Status'=>'false', 'msg'=>'this coupan is already used. please change the Coupan code');
		}
		else{
			//echo "hii";
		//}die;
			
			
	if(isset($_POST['token'])){
			$token=$_POST['token'];		
    $sql = "SELECT tbluser.ID,tbluser.LoginName as Email,tbluser.UserNo,tbluserinfo.FirstName FROM `tbluser` JOIN `tbluserinfo` ON tbluser.ID=tbluserinfo.UID WHERE `token`='$token'";
		$result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
			if(mysqli_num_rows($result)>0){
				$row = mysqli_fetch_array($result);
				$UserId = $row['ID'];
				$localname = $row['FirstName'];
				$localPhone = $row['UserNo'];
				$localEmail = $row['Email'] ;      
			}}else{
				$localname = $_REQUEST['name'];
				$localPhone = $_REQUEST['phone'];
				$localEmail =$_REQUEST['email'];
			}	
		
		$DeviceType = $_REQUEST['DeviceType'];
		$peakTimeServices = $_POST['DeviceType']=="ANDROID" ? $_POST['peakTimeServices'] : "false";
		//$subBookingType = $_REQUEST['subBookingType'];
		$pickupTimestae = $_REQUEST['picktime'];
		$carTypes = $_REQUEST['carTypes'];
		$booking = $_REQUEST['bookingType'];
		$cablocalin=$_REQUEST['localCabInData'];
		$cablocalfor=$_REQUEST['localCabForData'];
		$cablocalnationality=$_REQUEST['localNationalitydata'];
		$cablocaladults=$_REQUEST['localAdultsDat'];
		$cablocalchild=$_REQUEST['localChildsData'];
		$cablocalluggages=$_REQUEST['localLuggagesData'];
		$cabpickuparea = $_REQUEST['pickup'];
		$cablocaladdress=$_REQUEST['localAddressData'];
		$pickuplatlng = explode(',', $_REQUEST['picklatlong']);
		@$picklat = $pickuplatlng[0];
		@$picklang = $pickuplatlng[1];
		$cablocaldate=$_REQUEST['datepickerData'];
		$cablocaltimeH=$_REQUEST['localTimeH'];
		$localTimeS=$_REQUEST['localTimeS'];
		$localNoCars=$_REQUEST['localNoCars'];
		
		file_put_contents("local.txt",print_r($_REQUEST,true));
		$distance = '';
		$find_address=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($cablocaladdress));
		$enc3=json_decode($find_address);
		$picklat=$enc3->results[0]->geometry->location->lat;
		$picklang=$enc3->results[0]->geometry->location->lng;
		$is_pickup=mysqli_num_rows(mysqli_query($this->con,"SELECT * FROM rt_locations WHERE area='$cabpickuparea'"));							        
		if($is_pickup==0){
			$find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($cabpickuparea));
			file_put_contents("json.txt",$find_pickup); 					
			$enc=json_decode($find_pickup);
			$lat=$enc->results[0]->geometry->location->lat;
			$long=$enc->results[0]->geometry->location->lng;			                        
			foreach($enc->results[0]->address_components as $v){
				if($v->types[0]=="locality"){							
					$city=$v->long_name;
				}
				if($v->types[0]=="administrative_area_level_2"){							
					$zone=$v->long_name;
				}        
				if($v->types[0]=="country"){							
					$country=$v->long_name;
				}       
				if($v->types[0]=="administrative_area_level_1"){							
					$state=$v->long_name;
				}
			}
			mysqli_free_result($result);   
			mysqli_next_result($this->con);
			mysqli_query($this->con,"INSERT INTO rt_locations(`area`,`city`,`lat`,`lon`,`zone`,`country`,`state`)"
                                        . " VALUES('$cabpickuparea','$city','$lat','$long','$zone','$country','$state')");
		}		
		$date = date("Y-m-d H:i:s");
		$user_agent=$_SERVER['HTTP_USER_AGENT']; 
		if($pickupTimestae=="Now"){
			$date1= date("Y-m-d H:i", strtotime("+30 minutes"));
			//$newdate =date("d-m-Y H:i", strtotime("+30 minutes"));
			$newdate = explode(" ",$date1);
			$cablocaldate = $newdate[0];
			
			$pickupTime=$newdate[1];
			$date_print=$date1;
			
			$result = mysqli_query($this->con,"CALL wp_localbooking('$DeviceType', '$booking','$cablocalin','$cablocalfor','$carTypes','$cablocalnationality','$cablocaladults',
			'$cablocalchild','$cablocalluggages','$cabpickuparea','$cablocaladdress','$picklat','$picklang','$cablocaldate','$pickupTime','$date','$localname','$localPhone',
			'$localEmail','$user_agent','$peakTimeServices')")  ;  
		}else{
			$cablocaldate=date("Y-m-d", strtotime($_REQUEST['datepickerData'])); 
			$cablocaltimeH=trim($_REQUEST['localTimeH']);
			$localTimeS=trim($_REQUEST['localTimeS']);
			$pickupTime = "$cablocaltimeH".":$localTimeS:00";
			$date_print=$cablocaldate." ".$pickupTime;
			$newdate=date("d-m-Y H:i",strtotime($date_print));
			$result = mysqli_query($this->con,"CALL wp_localbooking('$DeviceType','$booking','$cablocalin','$cablocalfor','$carTypes','$cablocalnationality','$cablocaladults',
			'$cablocalchild','$cablocalluggages','$cabpickuparea','$cablocaladdress','$picklat','$picklang',
			'$cablocaldate','$pickupTime','$date','$localname','$localPhone','$localEmail','$user_agent','$peakTimeServices')");
		}
		$data = mysqli_fetch_assoc($result);
		$new = $data['userId'];
		$userNewId = $data['userNewId'];
		$userExists = $data['userExists'];
		$normailUserId = $data['normailUserId'];
		mysqli_free_result($result);   
		mysqli_next_result($this->con);
		
		if($userExists == '1')
		{
			$client_id=$normailUserId;
		}
		else
		{
			$client_id=$userNewId;
		}
		if($local_coupan_id=='')
		{
			$coupanLastId="";
		}
		else
		{
		$com_query = "INSERT into tblcouponmaster (CouponID,userId,DeviceType) values('$local_coupan_id','$client_id','$DeviceType')";
		$com_result = mysqli_query($this->con,$com_query);
		mysqli_free_result($com_result);
		mysqli_next_result($this->con);
		
		$query_lastid = "SELECT LAST_INSERT_ID() as coupanLastId FROM tblcouponmaster";
		 $Lastid =mysqli_fetch_assoc(mysqli_query($this->con,$query_lastid));	
		 $coupanLastId=$Lastid["coupanLastId"];
		 if($coupanLastId==""){
		 $coupanLastId=0;
		 }
		//SELECT LAST_INSERT_ID() FROM subscription
		}
		
		if($_POST['DeviceType']=="ANDROID"){
			$booking_ref=$this->NewCheckWebCallAnd($new,'HA');
		
		}else{
			$booking_ref=$this->NewCheckWebCallAnd($new,'HW');
		}		 
		//********************************GET subpackage Info***********************////////////////////////////////******
		 $query = "SELECT * FROM `tblcity` WHERE name='$cablocalin'";
		 $rowDATA =mysqli_fetch_assoc(mysqli_query($this->con,$query));	
		 $stateId=$rowDATA["state"];
		 
		 $query1 = "SELECT state FROM `tblstates` WHERE id='$stateId'";
		 $rowDATA1 =mysqli_fetch_assoc(mysqli_query($this->con,$query1));	
		 $stateName=$rowDATA1["state"];
				
		 $datam2=array();
		 $datam2=$this->FetchLocalPackageData($booking,$cablocalfor,$stateId,$carTypes);
		 
		 $totalbill=$datam2['totalbill'];
		 
		 if($local_MinimumBookingAmount!=""){
		 if($totalbill>$local_MinimumBookingAmount)
		 {
			 if($local_DiscountType=='RS')
			 {
				$coupon_amt = $totalbill-$local_coupanDisount;
			 }
			 elseif($local_DiscountType=='%')
			 {
				$coupon_amt = $totalbill-($totalbill*$local_coupanDisount/100);
			 }
		 }
		 //$totalbill=$coupon_amt;
		 }else{
		 $totalbill=$totalbill;
		 }
		 
		$PeakFare	=	$this->calculatePeakTimeCharges($totalbill,$pickupTime);
		$totalbill	=	$totalbill + $PeakFare['peakcharge'];
		
		 /// Check it
		
		$NightCharges=$this->calculateNigthCharges($pickupTime,$datam2['night_rate_begins'],$datam2['night_rate_ends'],$datam2['nightCharge_unit'],$datam2['NightCharges'],$totalbill);
		 		 
		/*if(strtotime($pickupTime) >= strtotime($datam2['night_rate_begins']) and strtotime($pickupTime) < strtotime($datam2['night_rate_ends'])){
			if($datam2['nightCharge_unit'] == 'Rs'){
				$NightCharges = $datam2['NightCharges'];
			}else{
				$NightCharges = ($totalbill * $datam2['NightCharges'])/100;
			}
		}*/
				
		//// Calculation for NightCharges Ends here /////
		//////// IF NIGHT CHARGES IS INCLUDED IN TOTAL BILL THEN Start HERE ///////
		
		$totalbill	=	$totalbill + $NightCharges;
		$extraPrice	=	$this->calculateExtraCharges($totalbill,$datam2['extras']);
		$totalbill	=	$totalbill + $extraPrice;
		
		
		$BasicTax = (($totalbill) * $datam2['basic_tax'])/100;
		$totalbill = $totalbill + $BasicTax;
		$totalbill	=	round($totalbill);
		
		
		//////// IF NIGHT CHARGES IS INCLUDED IN TOTAL BILL THEN END HERE /////// 
		 
		 $Min_Pkg_Hrs=$datam2['Min_Pkg_Hrs'];
		 $Min_Pkg_KM=$datam2['Min_Pkg_KM'];
		 $per_km_charge=$datam2["Per_Km_Charge"];
		 $min_distance=$datam2["Min_Distance"];
		 $wait_charge=$datam2["WaitingCharge_per_minute"];
		 $waiting_min=$datam2["Waitning_minutes"];
		 $rate_per_hr=$datam2["Per_Hr_Charge"]; /// Later Update in database after checking ///
		 
		 
		 $basic_tax				=	$datam2['basic_tax'];
		 $basic_tax_type		=	$datam2['basic_tax_type'];
		 $basic_tax_price		=	round($BasicTax);
		 $rounding				=	$datam2['rounding'];
		 $level					=	$datam2['level'];
		 $direction				=	$datam2['direction'];
		 $nightcharge_unit		=	$datam2['nightCharge_unit'];
		 $nightcharge			=	$datam2['NightCharges'];
		 $nightcharge_price		=	round($NightCharges);
		 $night_rate_begins		=	$datam2['night_rate_begins'];
		 $night_rate_ends		=	$datam2['night_rate_ends'];
		 $premiums				=	$datam2['premiums'];
		 $premiums_unit			=	$datam2['premiums_unit'];
		 $extraPrice			=	round($extraPrice);
		 $peakTimePrice			=	round($PeakFare['peakcharge']);
		 $peaktimeFrom			=	$PeakFare['peaktimeFrom'];
		 $peaktimeTo			=	$PeakFare['peaktimeTo'];
		 if($PeakFare['peakpercentage']==0){
		 $peaktimepercentage	=	0;	
		 }else{
		 $peaktimepercentage	=	$PeakFare['peakpercentage'];
		 }
		 $extras				=	str_replace(array('[',']'),'',$datam2['extras']);
		 	
		$SQL = "update tblcabbooking SET CabFor='$Min_Pkg_Hrs', 
										 estimated_price='$totalbill',
										 approx_distance_charge='$per_km_charge',
										 approx_after_km='$min_distance',
										 approx_waiting_charge='$wait_charge',
										 appox_waiting_minute='$waiting_min',
										 min_Distance='$Min_Pkg_KM',
										 local_subpackage='$cablocalfor',
										 No_of_taxies='$localNoCars',
										 coupan_id='$coupanLastId',
										 PrometionalCode='$CouponName',
										 PrometionalName='$PromotionName',
										 Local_package_Name='$cablocalfor',
										 PickupCity='$cablocalin',
										 EstimatedDistance='$Min_Pkg_KM',
										 Package_State='$stateName',										 
										 basic_tax='$basic_tax',
										 basic_tax_type='$basic_tax_type',
										 basic_tax_price='$basic_tax_price',
										 rounding='$rounding',
										 level='$level',
										 direction='$direction',
										 nightcharge_unit='$nightcharge_unit',
										 nightcharge='$nightcharge',
										 nightcharge_price='$nightcharge_price',
										 night_rate_begins='$night_rate_begins',
										 night_rate_ends='$night_rate_ends',
										 premiums='$premiums',
										 premiums_unit='$premiums_unit',
										 extras='$extras',
										 extraPrice='$extraPrice',
										 peakTimePrice='$peakTimePrice',
										 peaktimeFrom='$peaktimeFrom',
										 peaktimeTo='$peaktimeTo',
										 peaktimepercentage='$peaktimepercentage',
										 coupon_amt='$coupon_amt'
										 WHERE ID='$new'"; 
		mysqli_query($this->con,$SQL);
		//******************end subpackage info**************/
			/*if(isset($_REQUEST["isTravellerDetails"])){
			$Traveller_Name=$_REQUEST['Traveller_Name'];
			$Traveller_Mobile=$_REQUEST['Traveller_Mobile'];
			$Traveller_Note=$_REQUEST['Traveller_Note'];
			$this->BookForThirdPerson($Traveller_Name,$Traveller_Mobile,$Traveller_Note,$UserId,$carTypes,$booking,$new);			
		    } */			
			$this->send_sms_new($new);
		if($UserId>0){
           if($pickupTimestae=="Now"){			
			return array("Status"=>'true', "msg" => "User already exits","id"=>$UserId,"code"=>"001","ref"=>$booking_ref['generated'],"price"=>$totalbill,"pickupTime"=> $pickupTime,"per_km_charge"=>$per_km_charge,"succMess"=>"Your booking has been confirmed.");
	       }else{
			   return array("Status"=>'true', "msg" => "User already exits","id"=>$UserId,"code"=>"001","ref"=>$booking_ref['generated'],"price"=>$totalbill,"pickupTime"=> $pickupTime,"per_km_charge"=>$per_km_charge,"succMess"=>"Your booking has been confirmed.");
		    }
		}else{			
			if($pickupTimestae=="Now"){			
			   return array("Status"=>'true', "msg" => "New User","code"=>"002","ref"=>$booking_ref['generated'],"price"=>$totalbill,"pickupTime"=> $pickupTime,"per_km_charge"=>$per_km_charge,"succMess"=>"Your booking has been confirmed."); 
	       }else{
			   return array("Status"=>'true', "msg" => "New User","code"=>"002","ref"=>$booking_ref['generated'],"price"=>$totalbill,"pickupTime"=> $pickupTime,"per_km_charge"=>$per_km_charge,"succMess"=>"Your booking has been confirmed."); 
		    }			
		}
	}
	}

//////////// API for Local Booking used in ANDRIOD and WEB only Ends Here/////////////////////

	public function BookForThirdPerson($Traveller_Name,$Traveller_Mobile,$Traveller_Note,$UserID,$carType,$bookingType,$booking_id){
		mysqli_query($this->con,"INSERT INTO `tbltraveller` set UID='$UserID',Booking_Id='$booking_id',Name='$Traveller_Name',Mobile='$Traveller_Mobile',
		Note='$Traveller_Note',CarType='$carType',BookingType='$bookingType'");
			$last_id=mysqli_insert_id($this->con);
			return true;
	}
	
	public function send_sms_new($booking=0){
		$booking_id="";
		if($booking==0){
			$booking_id=$_REQUEST['booking_id'];
		}else{
			$booking_id=$booking;
		}
		$result=mysqli_query($this->con,"SELECT t3.Sub_Package_Id ,t2.estimated_price, t2.ClientID as uid,t1.CabName as cabname,
t1.MinimumCharge as minimum,t1.Min_Distance as min_distance, t1.Per_Km_Charge as charge,
t1.WaitingCharge_per_minute, t1.minimum_hourly_Charge, t1.ignore_first_hrs,t1.tripCharge_per_minute,
t1.minimum_fare_dh, t1.minimum_distance_dh, t1.rate_per_km_dh,t1.minimum_fare_dw, t1.minimum_distance_dw, 
t1.rate_per_km_dw, t2.* FROM tblcabbooking t2 JOIN tblbookingbill t1 ON t2.BookingType=t1.BookingTypeId 
and t2.CarType=t1.CabType inner join tblcity on t2.CabIn=tblcity.name and 
tblcity.id = t1.CityId inner join tblmasterpackage t3 on t2.BookingType = t3.Package_Id WHERE t2.id='$booking_id'");
		$fetch=mysqli_fetch_assoc($result);
		$Sub_Package_Id=$fetch['Sub_Package_Id'];
		$mobile=$fetch['MobileNo'];
		$email=$fetch['EmailId'];
		$booking_ref=$fetch['booking_reference'];
		$client=$fetch['UserName'];
		$bookingdate=$fetch['BookingDate'];
		$cabname=$fetch['cabname'];
		if($Sub_Package_Id==1)
		{
		$minimum_charge=$fetch['minimum'];
		$minimum_distance=$fetch['min_distance'];
		$charge=$fetch['charge'];
		$waiting_charge=0;
		}
		elseif($Sub_Package_Id==2)
		{
		$minimum_charge=$fetch['minimum_hourly_Charge'];
		$minimum_distance=$fetch['ignore_first_hrs'];
		$charge=$fetch['tripCharge_per_minute'];
		$waiting_charge=$fetch['WaitingCharge_per_minute'];
		}
		elseif($Sub_Package_Id==3)
		{
		$minimum_charge=$fetch['minimum_fare_dh'];
		$minimum_distance=$fetch['minimum_distance_dh'];
		$charge=$fetch['rate_per_km_dh'];
		$waiting_charge=$fetch['WaitingCharge_per_minute'];
		}
		elseif($Sub_Package_Id==4)
		{
		$minimum_charge=$fetch['minimum_fare_dw'];
		$minimum_distance=$fetch['minimum_distance_dw'];
		$charge=$fetch['rate_per_km_dw'];
		$waiting_charge=0;
		}
		
		$distance=$fetch['EstimatedDistance'];
		$pickup_time=$fetch['PickupDate']." ".$fetch['PickupTime'];
		$pick=$fetch['PickupLocation'];
		$uid=$fetch['uid'];
/* 		$fair="";
		if($distance<$minimum_distance){
			$fair=$minimum_charge;
		}else{
			$fair=$minimum_charge+($distance-$minimum_distance)*$charge;
		} */
		if($Sub_Package_Id==2)
		{
		$msg_query=mysqli_fetch_array(mysqli_query($this->con,"SELECT * FROM tbl_sms_template WHERE msg_sku='booking_hrs'"));
		}
		else
		{
			$msg_query=mysqli_fetch_array(mysqli_query($this->con,"SELECT * FROM tbl_sms_template WHERE msg_sku='booking'"));
		}
		$array=explode('<variable>',$msg_query['message']);
		$array[0]=$array[0].$client;
		$array[1]=$array[1].$booking_ref;
		$array[2]=$array[2].$pickup_time;
		$array[3]=$array[3].$fetch['estimated_price'];
		$array[4]=$array[4].$minimum_charge;
		$array[5]=$array[5].$minimum_distance;
		$array[6]=$array[6].$charge;
		$array[7]=$array[7].$waiting_charge;
		$text=  urlencode(implode("",$array));	
		file_put_contents("mssg.txt",$text);
		$url="http://push3.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$mobile&from=Helocb&dlrreq=true&text=$text&alert=1";
		
		file_get_contents($url);
		mysqli_query($this->con,"INSERT INTO `tblsmsstatus`(`UID`,`mesg`,`ContactNo`,`status`) VALUES('$uid','Thankyou for choosing hello42 cabs','$mobile','1')");
		
		$message='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Hello42cab</title>
		<style>
		*{margin:0;
		padding:0;
		}
		</style>
		</head>
		
		<body style="font-family:Verdana, Geneva, sans-serif">
		<div align="center">
		<table cellpadding="0px" cellspacing="0px" style="border:#fab600 solid 30px" width="650px" >
		<tr>
		<td>
		<table cellpadding="0px" cellspacing="0px" width="650" style="padding:0px 10px 0px 10px">
		<tr><td style="font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold" width="300px" valign="middle" align="left">Confirmed CRN '.$booking_ref.'</td>
		<td width="200px" valign="middle" align="right"><img src="images/hello-42-logo.png" width="100px" height="60px" /></td></tr>
		</table>
		<table cellpadding="10px" cellspacing="0px" width="650" style="padding:0px 10px 0px 10px"><tr><td style="font-family:Verdana, Geneva, sans-serif"><p>Hi'.$client.'<br />Thank you for using Hello42 Cab! Your Cab booking is confirmed.<br /><br /><span style="font-size:12px">BOOKING DETAILS</span><br />........................................................................................................</p></td></tr>
		
		</table>
		<table cellpadding="0px" cellspacing="0px" width="650" style="padding:0px 10px 0px 10px; font-family:Verdana, Geneva, sans-serif; font-size:14px">
		<tr><td style="padding:10px 0px 0px 80px">Booking time</td><td valign="top" style="padding:10px 0px 0px 50px">'.$bookingdate.'</td></tr>
		<tr><td style="padding:0px 0px 0px 80px">Pickup time</td><td valign="top" style="padding:0px 0px 0px 50px">'.$pickup_time.'</td></tr>
		<tr><td style="padding:0px 0px 0px 80px">Pickup address</td><td valign="top" style="padding:0px 0px 0px 50px">'.$pick.'</td></tr>
		<tr><td style="padding:0px 0px 10px 80px">Car type</td><td valign="top" style="padding:0px 0px 10px 50px">'.$cabname.'</td></tr></table>
		
		<table cellpadding="0px" cellspacing="0px" width="650" style="padding:0px 10px 0px 10px"><tr><td style="font-family:Verdana, Geneva, sans-serif"><span style="font-size:12px">FARE DETAIL</span><br />........................................................................................................</td></tr>
		
		</table>
		
		<table cellpadding="0px" cellspacing="0px" width="650" style="padding:0px 10px 0px 10px; font-family:Verdana, Geneva, sans-serif; font-size:14px">
		<tr><td style="padding:10px 0px 0px 80px">Minimum bill</td><td valign="top" style="padding:10px 0px 0px 50px">'.$fair.'</td></tr>
		<!--<tr><td style="padding:0px 0px 0px 80px">After 8 Km</td><td valign="top" style="padding:0px 0px 0px 50px">Rs 18 per Km</td></tr>
		<tr><td style="padding:0px 0px 10px 80px">After 10 minutes</td><td valign="top" style="padding:0px 0px 10px 50px">Rs 2 per minute</td></tr>--></table>
		
		<table cellpadding="10px" cellspacing="0px" width="650" style="padding:0px 10px 10px 10px"><tr><td style="font-family:Verdana, Geneva, sans-serif; text-align:center; font-size:12px; font-weight:bold"><p>*Parking and toll charges extra. Waiting charges applicable for in-trip waiting time also</p></td></tr>
		
		</table>
		
		<table cellpadding="10px" cellspacing="0px" width="650" style="padding:0px 10px 10px 10px"><tr><td style="font-family:Verdana, Geneva, sans-serif; text-align:center; font-size:12px; font-weight:bold"><p>Please refer your CRN for all communication about this booking.</p></td></tr>
		</table>
		
		<table cellpadding="0px" cellspacing="0px" width="650" style="background-color:#000; color:#FFF; font-size:12px; text-align:center; padding:15px 0px 15px 0px" >
		<tr>
		<td valign="middle"><p><img src="images/mobile-48.png" height="32px" width="32px" /><br />Go Mobile! <br />Book with one touch</p></td>
		<td valign="middle"><p><img src="images/1419443343_phone-32.png" /><br />Get in touch <br />Call on (011) 42424242</p></td>
		<td valign="middle"><p><img src="images/facebook.png" /><img src="images/twitter.png" /><br />Connect  <br />On Twitter/Facebook</p></td>
		<td valign="middle"><p><img src="images/hello-42-logo.png" height="40px" width="55px"/><br />Learn what\'s new  <br />And more on our Blog</p></td>
		</tr>
		</table>
		</td></tr>
		</table>
		</div>
		</body>
		</html>';
		  mysqli_query($this->con,"INSERT INTO `tblemailhostory`(`UID`,`mesg`,`ContactNo`,`status`) VALUES('$uid','Thankyou for choosing Hello42 cabs','$mobile','1')");	
		//$this->mailing_new($email,$message,"Congragtulation","Hello42@cab.com");
		return array('status'=>true);
	}
	
	///// API used for Call Center Fare Calculate Starts Here /////////////
	
    public function FareCalculate(){
		$isPickup 			=  $_REQUEST['isPickup']; 
		$pickupTime 		=  $_REQUEST['pickupTime']; 
		$CarType 			=  str_replace('_',' ',$_REQUEST['CarType']);
		//eco=1/prime=3/sedan=2	
		$StateName 			=  str_replace('_',' ',$_REQUEST['StateName']);
		//delhi		
		$PackageCode 		=  str_replace('_',' ',$_REQUEST['PackageCode']);
		//101/103/		
		$PickCityName 		=  str_replace('_',' ',$_REQUEST['PickCityName']);
		//new delhi
		$DropCityName 		=  str_replace('_',' ',$_REQUEST['DropCityName']);
        //new delhi
		$PickAreaName 		=  str_replace('_',' ',$_REQUEST['PickAreaName']);
		$DropAreaName 		=  str_replace('_',' ',$_REQUEST['DropAreaName']);
		$PickAddress 		=  str_replace('_',' ',$_REQUEST['PickAddress']);
		$DropAddress 		=  str_replace('_',' ',$_REQUEST['DropAddress']);
		$dropLocalPackage 	=  str_replace('_',' ',$_REQUEST['dropLocalPackage']);
		$cablocalfor="";
		$sql1="select id from tblstates where state='$StateName' limit 1";
		$result = mysqli_query($this->con, $sql1);
		$val = mysqli_fetch_object($result);
		$stateId=$val->id;
		$info=array();
        //4 hr - 40 km
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
				
				
		//$Full_Address=$PickAddress." ".$PickAreaName." ".$PickCityName; 
		$Full_Address=$PickAreaName." ".$PickCityName; 
		$find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($Full_Address));
			file_put_contents("json.txt",$find_pickup); 					
			$enc=json_decode($find_pickup);
			if($enc->status == 'OK'){
				$lat=$enc->results[0]->geometry->location->lat;
				$long=$enc->results[0]->geometry->location->lng;				
		
			}	
		//$Full_Address1=$DropAddress." ".$DropAreaName." ".$DropCityName;
		$Full_Address1=$DropAreaName." ".$DropCityName;
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
			}
			//echo $distance."<br/>";
			
			if($isPickup=='true'){
			$sql_address="SELECT `Km` FROM `tblairportaddress` WHERE `Address`='$DropAreaName' and `Fix_Point`='$PickAreaName' LIMIT 1";
			$result8 = mysqli_query($this->con,$sql_address);
			}else{
			$sql_address="SELECT `Km` FROM `tblairportaddress` WHERE `Address`='$PickAreaName' and `Fix_Point`='$DropAreaName' LIMIT 1";
			$result8 = mysqli_query($this->con,$sql_address);		
			}
			//die;
			$row8 = mysqli_fetch_assoc($result8);

			$distanceAirport=$row8['Km'];
			//echo $distanceAirport.'<br>';
			//echo $distance.'<br>'; 
			if($distance<$distanceAirport){
			$dist=round($distanceAirport);
			}else{
			$dist=round($distance);
			}
			
			$cabairportfor=$fixpoint;
			/// For Getting Fare Details According to Distance ///////
			
			$info=$this->FetchAirportDistanceFare($PackageCode,$CarType,$stateId,$dist,$cabairportfor,$address);
			$data["travel_distance"]="$dist";
			$data["Min_Pkg_KM"]		="$dist";
			$data["Min_Pkg_Hrs"]	=	$info['Min_Pkg_Hrs'];
			}

			
			elseif($PackageCode=='101'){
			$info=$this->FetchLocalPackageData($PackageCode,$dropLocalPackage,$stateId,$CarType);		
			$data["travel_distance"]			=	round($info['Min_Distance']);
			$data["Min_Pkg_KM"]					=	$info['Min_Pkg_KM'];
			$data["Min_Pkg_Hrs"]				=	$info['Min_Pkg_Hrs'];
			}
			
			
			elseif($PackageCode=='102'){
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
			$dist = round($distance); 
			}
			
			$info=$this->FetchPointToPointData($PackageCode,$CarType,$stateId,$dist,$cablocalfor);
			$data["travel_distance"]			=	round($dist);
			$data["Min_Pkg_KM"]					=	round($dist);		
			$data["Min_Pkg_Hrs"]				=	"0";
			}
			
			if($info>0)
				$status="true";
			else
				$status="false";
		
			$totalbill	=	$info['totalbill'];
			$estimated_charge	=	$info['totalbill'];
			
			$PeakFare	=	$this->calculatePeakTimeCharges($totalbill,$pickupTime);
			$totalbill	=	$totalbill + $PeakFare['peakcharge'];
			
			//// Calculation for NightCharges Starts here /////
			/*if(strtotime($pickupTime) >= strtotime($info['night_rate_begins']) and strtotime($pickupTime) < strtotime($info['night_rate_ends'])){
			if($info['nightCharge_unit'] == 'Rs'){
			$NightCharges = $info['NightCharges'];
			}else{
			$NightCharges = ($totalbill * $info['NightCharges'])/100;
			}
			}*/
			$NightCharges=$this->calculateNigthCharges($pickupTime,$info['night_rate_begins'],$info['night_rate_ends'],$info['nightCharge_unit'],$info['NightCharges'],$totalbill);
			//// Calculation for NightCharges Ends here /////
			
			//////// IF NIGHT CHARGES IS INCLUDED IN TOTAL BILL THEN Start HERE ///////

			$totalbill	=	$totalbill + $NightCharges;
			$extraPrice	=	$this->calculateExtraCharges($totalbill,$info['extras']);
			$totalbill	=	$totalbill + $extraPrice;
				
			$BasicTax = (($totalbill) * $info['basic_tax'])/100;
			$totalbill = $totalbill + $BasicTax; 
			
			$record=array();

			//// Common For All 101,102,103 Starts Here /////
			
			$data["totalbill"]					=	round($totalbill);
			$data["estimated_charge"]			=	$estimated_charge;
			$data["Per_Km_Charge"]				=	round($info['Per_Km_Charge']);
			$data["Min_Distance"]				=	round($info['Min_Distance']);
			$data["MinimumCharge"]				=	round($info['MinimumCharge']);
			$data["rounding"]					=	$info['rounding'];
			$data["level"]						=	$info['level'];
			$data["direction"]					=	$info['direction'];
			$data["premiums"]					=	$info['premiums'];
			$data["premiums_unit"]				=	$info['premiums_unit'];
			$data["premiums_price"]				=	"";
			$data["WaitingCharge_per_minute"] 	=	$info['WaitingCharge_per_minute'];
			$data["Waitning_minutes"]			=	$info['Waitning_minutes'];
			$data["rate_upto_distance"] 		=	str_replace(array('[',']'),'',$info['rate_upto_distance']);
			$data["waitingfee_upto_minutes"] 	=	str_replace(array('[',']'),'',$info['waitingfee_upto_minutes']);
			$data["nightcharge_unit"]			=	$info['nightCharge_unit'];
			$data["nightcharge"] 				=	$info['NightCharges'];
			$data["night_rate_begins"]			=	$info['night_rate_begins'];
			$data["night_rate_ends"]			=	$info['night_rate_ends'];
			$data["nightcharge_price"] 			=	round($NightCharges);
			$data["extras"]						=	str_replace(array('[',']'),'',$info['extras']);
			$data["extraPrice"]					=	round($extraPrice);
			if($PeakFare['peakpercentage']==""){
			$data["peakTimePercent"]			=	"0";
			}else{
			$data["peakTimePercent"]			=	$PeakFare['peakpercentage'];
			}
			$data["peakTimePrice"]				=	round($PeakFare['peakcharge']);
			if($PeakFare['peaktimeFrom']==""){
			$data["peaktimeFrom"]				=	"00:00:00";
			}else{
			$data["peaktimeFrom"]				=	$PeakFare['peaktimeFrom'];
			}
			if($PeakFare['peaktimeTo']==""){
			$data["peaktimeTo"]					=	"00:00:00";
			}else{
			$data["peaktimeTo"]					=	$PeakFare['peaktimeTo'];
			}
			$data["basic_tax"]					=	$info['basic_tax'];
			$data["basic_tax_type"]				=	$info['basic_tax_type'];
			$data["basic_tax_price"]			=	round($BasicTax);
			
			//// Common For All 101,102,103 Ends Here /////
			
			$record[]=$data;		
			$arra=array("status"=>"$status","record"=>$record);
			return $arra; 

	}
	
	///// API used for Call Centre Fare Calculate Ends Here /////////////
	
	///// API used for Call Centre Booking Starts Here /////////////
	
	public function CabBookingService(){
	$jsonstring = trim($_REQUEST['jsonstring']);
	$jsonstring =json_decode($jsonstring,true);
	//var_dump($jsonstring);  die;
			 //return array("Message"=>$jsonstring); exit();

	$booking_type		=	$jsonstring['PackageCode'];
	$Email				=	$jsonstring['Email'];
	$CallerID			=	$jsonstring['CallerID'];
	$UserType			=	$jsonstring['UserType'];
	$CallerName			=	$jsonstring['CallerName'];
	$AlternateNumber	=	$jsonstring['AlternateNumber'];
	$CustomerType		=	$jsonstring['CustomerType'];
	$RefrenceNo			=	$jsonstring['RefrenceNo'];
	$CompanyName		=	$jsonstring['CompanyName'];
	$NoOfTaxi			=	$jsonstring['NoOfTaxi'];
	$NoOfAdult			=	$jsonstring['NoOfAdult'];
	$NoOfChild			=	$jsonstring['NoOfChild'];
	$NoOfLuggage		=	$jsonstring['NoOfLuggage'];
	$PackageName		=	$jsonstring['PackageName'];
	$PickUpTime			=	$jsonstring['PickUpTime'];
	$PickUpDate			=	$jsonstring['PickUpDate'];
	$Priority			=	$jsonstring['Priority'];
	$ISReturn			=	$jsonstring['ISReturn'];
	$PrmationalName		=	$jsonstring['PrmationalName'];
	$PromotionalCode	=	$jsonstring['PromotionalCode'];
	$PackageState		=	$jsonstring['PackageState'];
	$LocalPackageName	=	$jsonstring['LocalPackageName'];
	$PickupCountry		=	$jsonstring['PickupCountry'];
	$PickStatename		=	$jsonstring['PickStatename'];
	$PickUpZone			=	$jsonstring['PickUpZone'];
	$PickUpCity			=	$jsonstring['PickUpCity'];
	$PickUpArea			=	$jsonstring['PickUpArea'];
	$PickUpAdderss		=	$jsonstring['PickUpAdderss'];
	$PickAddressWrong	=	$jsonstring['PickAddressWrong'];
	$Pick_or_Drop		=	$jsonstring['Pick_or_Drop'];
	$PickUpLatitude		=	$jsonstring['PickUpLatitude'];
	$PickUpLongitude	=	$jsonstring['PickUpLongitude'];
	$DropCountry		=	$jsonstring['DropCountry'];
	$DropStateName		=	$jsonstring['DropStateName'];
	$DropZone			=	$jsonstring['DropZone'];
	$DropCity			=	$jsonstring['DropCity'];
	$DropArea			=	$jsonstring['DropArea'];
	$DropAddress		=	$jsonstring['DropAddress'];
	$DropAddressWrong	=	$jsonstring['DropAddressWrong'];
	$DropLatitude		=	$jsonstring['DropLatitude'];
	$DropLongitude		=	$jsonstring['DropLongitude'];
	$OneWayDistance		=	$jsonstring['OneWayDistance'];
	$Fare				=	$jsonstring['Fare'];
	$ClientNotes		=	$jsonstring['ClientNotes'];
	$CarType			=	$jsonstring['CarType'];
	$EmailID			=	$jsonstring['EmailID'];
	$EmailBill			=	$jsonstring['EmailBill'];
	$PaymnetType		=	$jsonstring['PaymnetType'];
	if($PaymnetType=='Cash'){
	$PaymnetType=1;
	}elseif($PaymnetType=='Credit'){
	$PaymnetType=2;
	}
	$BookingStatus		=	$jsonstring['BookingStatus'];
	$AgentID			=	$jsonstring['AgentID'];
	$BookingDate		=	date("Y-m-d H:i:s");
	$BookingBy			=	$jsonstring['BookingBy'];
	$PartnerType		=	$jsonstring['PartnerType'];
	$Cabfor				=	$jsonstring['Cabfor'];
	$approx_distance_charge	=	$jsonstring['approx_distance_charge'];
	$approx_afterkm			=	$jsonstring['approx_afterkm'];
	$approx_waiting_charge	=	$jsonstring['approx_waiting_charge'];
	$appox_waiting_minut	=	$jsonstring['appox_waiting_minut'];
	$local_subpackage		=	$jsonstring['LocalPackageName'];
	
	
	$basic_tax			=	$jsonstring['basic_tax'];
	$basic_tax_type		=	$jsonstring['basic_tax_type'];
	$basic_tax_price	=	$jsonstring['basic_tax_price'];
	$rounding			=	$jsonstring['rounding'];
	$level				=	$jsonstring['level'];
	$direction			=	$jsonstring['direction'];
	$nightcharge_unit	=	$jsonstring['nightcharge_unit'];	
	$nightcharge		=	$jsonstring['nightcharge'];
	$nightcharge_price	=	$jsonstring['nightcharge_price'];
	$night_rate_begins	=	$jsonstring['night_rate_begins'];
	$night_rate_ends	=	$jsonstring['night_rate_ends'];
	$premiums			=	$jsonstring['premiums'];
	$premiums_unit		=	$jsonstring['premiums_unit'];
	$extraPrice			=	$jsonstring['extraPrice'];
	$extras				=	$jsonstring['extras'];
	
	//// using for peak time calculation ////
	
	$peakTimePrice		=	$jsonstring['peakTimePrice'];
	$peakTimePercent	=	$jsonstring['peakTimePercent'];
	$peaktimeFrom		=	$jsonstring['peaktimeFrom'];
	$peaktimeTo			=	$jsonstring['peaktimeTo'];
	
	////// IF PickupLatitude And PickUpLongitude is empty then it will hit on the GEO Location Service /////
	
	if($PickUpLatitude=="" || $PickUpLongitude==""){
		$Full_Address=$PickUpArea." ".$PickUpCity; 
		$find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($Full_Address));
		file_put_contents("json.txt",$find_pickup); 					
		$enc=json_decode($find_pickup);
		if($enc->status == 'OK'){
		$lat=$enc->results[0]->geometry->location->lat;
		$long=$enc->results[0]->geometry->location->lng;
		$PickUpLatitude=$lat;
		$PickUpLongitude=$long;
	}	
	}
			/**********Check User Exist or Not************/
			$user_id	=	$this->FetchCallCenterUserId($EmailID,$CallerID,$UserType,$CallerName,$AlternateNumber);
				
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
				if($booking_type=='101'){
					$sql1="select id from tblstates where state='$PackageState' limit 1";
					$result = mysqli_query($this->con, $sql1);
					$val = mysqli_fetch_object($result);
					$stateId=$val->id;			
					
					$dataVal	=	$this->FetchBookingBill($booking_type,$CarType,$stateId);
					$approx_distance_charge	=	$dataVal['Per_Km_Charge'];
					$approx_afterkm			=	$dataVal['Min_Distance'];
					$approx_waiting_charge	=	$dataVal['WaitingCharge_per_minute'];
					$appox_waiting_minut	=	$dataVal['Waitning_minutes'];
				}
				//********Insert Booking Date in tblcabbooking ************No_of_taxies
$query="insert into tblcabbooking (ClientID,CarType,UserName,EmailId,
CustType,Company,MobileNo,No_of_taxies,PickupDate,PickupTime,Priority,PickupCountry,PickupState,PickupCity,PickupZone,PickupArea,PickupAddress,PickupLatitude,PickupLongtitude,DestinationCountry,DestinationState,DestinationCity,DestinationZone,DropArea,DestinationAddress,DropAddress,DestinationLatitude,DestinationLongtitude,Reason,Remark,client_note,TaxiType1,BookingDate,EstimatedDistance,useragent,partner,No_Of_Adults,No_Of_Childs,No_Of_Luggages,CSR_ID,BookingType,StatusC,CabFor,ReturnDate,ReturnTime,approx_distance_charge,approx_after_km,approx_waiting_charge,appox_waiting_minute,refno,estimated_price,PrometionalCode,PrometionalName,charge_type,Local_package_Name,Package_State,DeviceType,local_subpackage,min_Distance,CabIn,basic_tax,basic_tax_type,basic_tax_price,rounding,level,direction,nightcharge_unit,nightcharge,nightcharge_price,night_rate_begins,night_rate_ends,premiums,premiums_unit,extras,extraPrice,peakTimePrice,peaktimeFrom,peaktimeTo,peaktimepercentage)Values('$user_id','$CarType','$CallerName','$EmailID','$CustomerType','$CompanyName','$CallerID','$NoOfTaxi','$PickUpDate','$PickUpTime','$Priority','$PickupCountry','$PickStatename','$PickUpCity','$PickUpZone','$PickUpArea','$PickUpAdderss','$PickUpLatitude','$PickUpLongitude','$DropCountry','$DropStateName','$DropCity','$DropZone','$DropArea','$DropAddress','$DropAddress','$DropLatitude','$DropLongitude','$Reason','$Remark','$ClientNotes','$CarType','$BookingDate','$OneWayDistance','$BookingBy','$PartnerType','$NoOfAdult','$NoOfChild','$NoOfLuggage','$AgentID','$booking_type','$BookingStatus','$Cabfor','$ReturnDate','$ReturnTime','$approx_distance_charge','$approx_afterkm','$approx_waiting_charge',
'$appox_waiting_minut','$RefrenceNo','$Fare','$PromotionalCode','$PrmationalName','$PaymnetType','$LocalPackageName',
'$PackageState','CALLCENTER','$local_subpackage','$OneWayDistance','$PickUpCity','$basic_tax','$basic_tax_type',
'$basic_tax_price','$rounding','$level','$direction','$nightcharge_unit','$nightcharge','$nightcharge_price',
'$night_rate_begins','$night_rate_ends','$premiums','$premiums_unit','$extras','$extraPrice','$peakTimePrice','$peaktimeFrom','$peaktimeTo','$peakTimePercent')"; 
					 $insertSql=mysqli_query($this->con,$query);
					 if($insertSql>0){
						 $lastid=mysqli_insert_id($this->con);
						 $data=array();
						 //$return_booking_id='return_booking_id=""';
						 $data[]["booking_id"]=$lastid;
						 //$data[]["return_booking_id"]='';
						 
						$pickuplatlat=$PickUpLatitude;
						$pickuplatlng=$PickUpLongitude;

						$this->LogStackTrackerData($lastid,$pickuplatlat,$pickuplatlng);
						 
						 $return=array("Status"=>true,"Data"=>$data);
					 }else{
						 $return=array("Status"=>false,"Data"=>'');
					 }
		   return $return; 	
	}
	
	///// API used for Call Center Booking Ends Here /////////////
	
	///// API used for Call Center Send SMS in All Module Starts Here /////////////
	
	public function send_sms($booking=0){
		$booking_id="";
		$book_id= $_REQUEST['booking_id']; //die;
		 //echo $booking_id='6738';
		 //echo $msg_sku='booking'; //die;
		$val=explode(':',$book_id);
		$booking=$val[0];
		$msg_sku=$val[1]; //die;
		if($booking==0){
			$booking_id=$book_id;
		}else{
			$booking_id=$booking;
			if(empty($msg_sku) || ($msg_sku=="")){
			$msg_sku='booking';
		}
		}
		
		/// Sitting Driver Idle Booking Status Starts Here /////
		
		if($msg_sku=='cancel'){
			$booking_status=0;
			$total_booking_operated=0;
			$prev_booking_done=date('Y-m-d H:i:s');
		}
		
		/// Driver Idle Booking Status Ends Here /////
		
		
		if($msg_sku=="complaint")
		{
			$sql="select * from tblcomplaint where ID = '$booking'";
			$result=mysqli_query($this->con,$sql);
			$fetch=mysqli_fetch_assoc($result);
			$complaint_ref=$fetch['Complaint_ref'];
			$mobile=$fetch['ClientID'];
			//$mobile="9650465338";
			
		}
		else if($msg_sku=="enquiry")
		{
			$sql="select * from tblenquiry where ID = '$booking'";
			$result=mysqli_query($this->con,$sql);
			$fetch=mysqli_fetch_assoc($result);
			$CallerName=$fetch['CallerName'];
			$Enquiry_ref=$fetch['Enquiry_ref'];
			$mobile=$fetch['CallerId'];
			//$mobile="9650465338";
		}
		else if($msg_sku=="reassign" or $msg_sku=="resechdule")
		{
			$sql="select t1.id, t1.UserName,t1.MobileNo,t1.booking_reference, t2.booking_id,t2.*,t3.UID, t3.FirstName as old_firstname,t3.LastName as old_lastName,t3.ContactNo as old_ContactNo
from tblcabbooking t1 inner join tbl_booking_Resec_Reassign t2 on t1.ID= t2.booking_id 
inner join tbldriver t3 on t2.old_driver_id = t3.UID where t1.ID='$booking' order by t2.Index desc limit 1;";
			$result=mysqli_query($this->con,$sql);
			$fetch=mysqli_fetch_assoc($result);
			$UserName=$fetch['UserName'];
			$mobile=$fetch['MobileNo'];
			$booking_reference=$fetch['booking_reference'];
			$old_pick_time=$fetch['old_pick_time'];
			$new_pick_time=$fetch['new_pick_time'];
			$old_DriverName=$fetch['old_firstname'].' '.$fetch['old_lastName'];
			$old_ContactNo=$fetch['old_ContactNo'];
			//$mobile="9643683373";
			
			$sql1="select t1.id, t1.UserName,t1.booking_reference, t2.booking_id,t2.*,t3.UID, t3.FirstName as new_firstname,t3.LastName as new_lastName,t3.ContactNo as new_ContactNo
from tblcabbooking t1 inner join tbl_booking_Resec_Reassign t2 on t1.ID= t2.booking_id 
inner join tbldriver t3 on t2.new_driver_id = t3.UID where t1.ID='$booking' order by t2.Index desc limit 1;";
			$result1=mysqli_query($this->con,$sql1);
			$fetch1=mysqli_fetch_assoc($result1);
			$new_DriverName=$fetch1['new_firstname'].' '.$fetch1['new_lastName'];
			$new_ContactNo=$fetch1['new_ContactNo'];
			
		}
		else
		{
			 $sql="SELECT tblcabbooking.estimated_price, tblcabbooking.ClientID as uid,tblbookingbill.CabName as cabname,
	tblbookingbill.MinimumCharge as minimum,tblbookingbill.Min_Distance as min_distance,
	tblbookingbill.Per_Km_Charge as charge,tblbookingbill.WaitingCharge_per_minute,tblCablistmgmt.vehicleNumber, count(tblCablistmgmt.cabid) as count_result,tbldriver.FirstName, tbldriver.LastName, tbldriver.ContactNo, tblcabbooking.* 
	FROM tblcabbooking JOIN tblbookingbill ON tblbookingbill.BookingTypeId AND tblcabbooking.CarType=tblbookingbill.Id 
	inner join  tbldriver   on tblcabbooking.pickup=tbldriver.UID 
	inner join  tblCablistmgmt  on tbldriver.vehicleId = tblCablistmgmt.cabid  WHERE tblcabbooking.id='$booking_id' limit 1";
			$result=mysqli_query($this->con,$sql);
			$fetch=mysqli_fetch_assoc($result);
			$mobile=$fetch['MobileNo'];
			//$mobile='9650465338';
			$email=$fetch['EmailId'];
			$booking_ref=$fetch['booking_reference'];
			$client=$fetch['UserName'];
			$bookingdate=$fetch['BookingDate'];
			$cabname=$fetch['cabname'];
			$minimum_charge=$fetch['minimum'];
			$minimum_distance=$fetch['min_distance'];
			$charge=$fetch['charge'];
			$distance=$fetch['EstimatedDistance'];
			$pickup_time=$fetch['PickupDate']." ".$fetch['PickupTime'];
			$pick=$fetch['PickupLocation'];
			$uid=$fetch['uid'];
			$vehicleNumber=$fetch['vehicleNumber'];
			$driverName=$fetch['FirstName']." ".$fetch['LastName'];
			$Driver_ContactNo=$fetch['ContactNo'];
			$count_result=$fetch['count_result'];
			$fair="";
			if($distance<$minimum_distance){
				$fair=$minimum_charge;
			}else{
				$fair=$minimum_charge+($distance-$minimum_distance)*$charge;
			}
		}
		//echo $msg_sku; die;
		$sql2="SELECT * FROM tbl_sms_template WHERE msg_sku='$msg_sku'";
		$msg_query=mysqli_fetch_array(mysqli_query($this->con,$sql2));
		
		$array=explode('<variable>',$msg_query['message']);
		//print_r($array); die;
		if($msg_sku=="complaint")
		{
		$array[0]=$array[0].$complaint_ref;
		$text=  urlencode(implode("",$array));	
		}
		elseif($msg_sku=="enquiry")
		{
		$array[0]=$array[0].$CallerName;
		$array[1]=$array[1].$Enquiry_ref;
		$text=  urlencode(implode("",$array));	
		}
		elseif($msg_sku=="cancel")
		{
		$array[0]=$array[0].$client;
		$array[1]=$array[1].$booking_ref;
		$text=  urlencode(implode("",$array));	
		}
		elseif($msg_sku=="resechdule")
		{
		$array[0]=$array[0].$UserName;
		$array[1]=$array[1].$booking_reference;
		$array[2]=$array[2].$old_pick_time;
		$array[3]=$array[3].$new_pick_time;
		$text=  urlencode(implode("",$array));	
		}
		elseif($msg_sku=="reassign")
		{
		$array[0]=$array[0].$UserName;
		$array[1]=$array[1].$booking_reference;
		$array[2]=$array[2].$old_DriverName;
		$array[3]=$array[3].$old_ContactNo;
		$array[4]=$array[4].$new_DriverName;
		$array[5]=$array[5].$new_ContactNo;
		$text=  urlencode(implode("",$array));	
		}
		elseif($msg_sku=="booking")
		{
		    $this->send_sms_new($booking_id);
			//echo "Mohit"; die;
		/* $array[0]=$array[0].$client;
		$array[1]=$array[1].$booking_ref;
		$array[2]=$array[2].$pickup_time;
		$array[3]=$array[3].$fetch['estimated_price'];
		$array[4]=$array[4].$fetch['minimum'];
		$array[5]=$array[5].$charge;
		$array[6]=$array[6].$fetch['WaitingCharge_per_minute'];
		$text=  urlencode(implode("",$array)); */	
		}

		
		
		//file_put_contents("mssg.txt",$text);
		$url="http://push1.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$mobile&from=Helocb&dlrreq=true&text=$text&alert=1";
		
		file_get_contents($url);
		mysqli_query($this->con,"INSERT INTO `tblsmsstatus`(`UID`,`mesg`,`ContactNo`,`status`) VALUES('$uid','Thankyou for choosing hello42 cabs','$mobile','1')");
		
		$message='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Hello42cab</title>
		<style>
		*{margin:0;
		padding:0;
		}
		</style>
		</head>
		
		<body style="font-family:Verdana, Geneva, sans-serif">
		<div align="center">
		<table cellpadding="0px" cellspacing="0px" style="border:#fab600 solid 30px" width="650px" >
		<tr>
		<td>
		<table cellpadding="0px" cellspacing="0px" width="650" style="padding:0px 10px 0px 10px">
		<tr><td style="font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold" width="300px" valign="middle" align="left">Confirmed CRN '.$booking_ref.'</td>
		<td width="200px" valign="middle" align="right"><img src="images/hello-42-logo.png" width="100px" height="60px" /></td></tr>
		</table>
		<table cellpadding="10px" cellspacing="0px" width="650" style="padding:0px 10px 0px 10px"><tr><td style="font-family:Verdana, Geneva, sans-serif"><p>Hi'.$client.'<br />Thank you for using Hello42 Cab! Your Cab booking is confirmed.<br /><br /><span style="font-size:12px">BOOKING DETAILS</span><br />........................................................................................................</p></td></tr>
		
		</table>
		<table cellpadding="0px" cellspacing="0px" width="650" style="padding:0px 10px 0px 10px; font-family:Verdana, Geneva, sans-serif; font-size:14px">
		<tr><td style="padding:10px 0px 0px 80px">Booking time</td><td valign="top" style="padding:10px 0px 0px 50px">'.$bookingdate.'</td></tr>
		<tr><td style="padding:0px 0px 0px 80px">Pickup time</td><td valign="top" style="padding:0px 0px 0px 50px">'.$pickup_time.'</td></tr>
		<tr><td style="padding:0px 0px 0px 80px">Pickup address</td><td valign="top" style="padding:0px 0px 0px 50px">'.$pick.'</td></tr>
		<tr><td style="padding:0px 0px 10px 80px">Car type</td><td valign="top" style="padding:0px 0px 10px 50px">'.$cabname.'</td></tr></table>
		
		<table cellpadding="0px" cellspacing="0px" width="650" style="padding:0px 10px 0px 10px"><tr><td style="font-family:Verdana, Geneva, sans-serif"><span style="font-size:12px">FARE DETAIL</span><br />........................................................................................................</td></tr>
		
		</table>
		
		<table cellpadding="0px" cellspacing="0px" width="650" style="padding:0px 10px 0px 10px; font-family:Verdana, Geneva, sans-serif; font-size:14px">
		<tr><td style="padding:10px 0px 0px 80px">Minimum bill</td><td valign="top" style="padding:10px 0px 0px 50px">'.$fair.'</td></tr>
		<!--<tr><td style="padding:0px 0px 0px 80px">After 8 Km</td><td valign="top" style="padding:0px 0px 0px 50px">Rs 18 per Km</td></tr>
		<tr><td style="padding:0px 0px 10px 80px">After 10 minutes</td><td valign="top" style="padding:0px 0px 10px 50px">Rs 2 per minute</td></tr>--></table>
		
		<table cellpadding="10px" cellspacing="0px" width="650" style="padding:0px 10px 10px 10px"><tr><td style="font-family:Verdana, Geneva, sans-serif; text-align:center; font-size:12px; font-weight:bold"><p>*Parking and toll charges extra. Waiting charges applicable for in-trip waiting time also</p></td></tr>
		
		</table>
		
		<table cellpadding="10px" cellspacing="0px" width="650" style="padding:0px 10px 10px 10px"><tr><td style="font-family:Verdana, Geneva, sans-serif; text-align:center; font-size:12px; font-weight:bold"><p>Please refer your CRN for all communication about this booking.</p></td></tr>
		</table>
		
		<table cellpadding="0px" cellspacing="0px" width="650" style="background-color:#000; color:#FFF; font-size:12px; text-align:center; padding:15px 0px 15px 0px" >
		<tr>
		<td valign="middle"><p><img src="images/mobile-48.png" height="32px" width="32px" /><br />Go Mobile! <br />Book with one touch</p></td>
		<td valign="middle"><p><img src="images/1419443343_phone-32.png" /><br />Get in touch <br />Call on (011) 42424242</p></td>
		<td valign="middle"><p><img src="images/facebook.png" /><img src="images/twitter.png" /><br />Connect  <br />On Twitter/Facebook</p></td>
		<td valign="middle"><p><img src="images/hello-42-logo.png" height="40px" width="55px"/><br />Learn what\'s new  <br />And more on our Blog</p></td>
		</tr>
		</table>
		</td></tr>
		</table>
		</div>
		</body>
		</html>';
		  mysqli_query($this->con,"INSERT INTO `tblemailhostory`(`UID`,`mesg`,`ContactNo`,`status`) VALUES('$uid','Thankyou for choosing Hello42 cabs','$mobile','1')");
		  
		$sql_update="UPDATE tbl_driver_points SET booking_status='$booking_status', total_booking_operated=total_booking_operated+'$total_booking_operated', prev_booking_done='$prev_booking_done' WHERE UID='$driverId'"; 
		mysqli_query($this->con,$sql_update) or die(mysqli_error($this->con));
		//$this->mailing_new($email,$message,"Congragtulation","Hello42@cab.com");
		return array('status'=>true);
	}
	
	///// API used for Call centre Send SMS in All Module Ends Here /////////////
	
	///// API used for (ANDROID, WEB, CALL CENTER) create Booking Reference Starts Here /////////////
	
	public function NewCheck(){
		//5380;HC
		$Value = $_REQUEST['Value'];
		$val1 = explode(';',$Value);
		$val = $val1[0];
		$initial = $val1[1];
		
		$record=array();
		$record=NewCheckWebCallAnd($val,$initial);
		if($record!=''){
			$status="true";
		}else{
			$status="false";
			$record="0";
		}
		
		$arra=array("status"=>"$status","record"=>$record);
		return $arra;
	}
	///// API used for (ANDROID, WEB, CALL CENTER) create Booking Reference Ends Here /////////////
	
	///// API used for (ANDROID, WEB, CALL CENTER) create Booking Reference Starts Here /////////////
	
	public function NewCheckWebCallAnd($val,$initial){
		//5380;HC
		
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
		$datam1=array();
		
		if($val>=10000){
		$divide=floor($val/10000);
		$next=$val-($divide*10000);
		$aa=64+$divide;
		$neww=chr($aa);
		$final=str_pad($next,4,0,STR_PAD_LEFT);
		$id=$initial.''.$dateYear.''.$dateMonth.''.$neww.''.$final;
		$generated=$id;
		$sql="UPDATE tblcabbooking SET booking_reference='$id' WHERE id='$val'";
		$result = mysqli_query($this->con, $qry);
		$status="true";
		}else{
		$id=$initial.''.$dateYear.''.$dateMonth.''.$final1;
		$generated=$id;
		$sql="UPDATE tblcabbooking SET booking_reference='$id' WHERE id='$val'";
		$result = mysqli_query($this->con, $sql);
		$status="true";
		}
		
		$datam1['generated']=$generated;
		return $datam1;
	}
	///// API used for (ANDROID, WEB, CALL CENTER) create Booking Reference Ends Here /////////////
	
	///// API used for Web,Android Point To Point Booking Starts Here /////////////
	
	public function pointBooking(){	

        $mobileNo = $_POST['mobileNumbers'];
		$emailIds =$_POST['emailId'];
		$point_coupan_id=$_REQUEST['point_coupan_id'];
		$point_DiscountType=$_REQUEST['point_DiscountType'];
		$Promo_Name=$_POST['PromotionName'];
		//$PromotionName="";
		$Cou_Name=$_POST['CouponName'];
		$point_coupanDisount=$_POST['point_coupanDisount'];
		$point_MinimumBookingAmount=$_POST['point_MinimumBookingAmount'];
		if($point_coupanDisount=='' or $point_coupanDisount==NULL)
		{
			 $CouponName = "";
			 $PromotionName="";
		}
		else
		{
			$CouponName = $Cou_Name;
			$PromotionName = $Promo_Name;
		}
		if($point_coupan_id!="")
		{
			$coupan_status = $this->CheckCoupanCode($point_coupan_id,$mobileNo,$emailIds);
			
		}
		if($coupan_status == "true")
		{
			 return array('Status'=>'false', 'msg'=>'this coupan is already used. please change the Coupan code');
		}
		else{
	
		if(isset($_POST['token'])){
			$token=$_POST['token'];
			 $sql = "SELECT tbluser.ID,tbluser.LoginName as Email,tbluser.UserNo,tbluserinfo.FirstName FROM `tbluser` JOIN `tbluserinfo` ON tbluser.ID=tbluserinfo.UID WHERE `token`='$token'";
			$result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
			if(mysqli_num_rows($result)>0){
			$row = mysqli_fetch_array($result);
			$userNames = $row['FirstName'];
			$mobileNo = $row['UserNo'];
			$emailIds = $row['Email'] ;
			$UserID = $row['ID'] ;			
			}else{
			$userNames = $_POST['userName'];
			$mobileNo = $_POST['mobileNumbers'];
			$emailIds =$_POST['emailId'];
			}
		}else{
			$userNames = $_POST['userName'];
			$mobileNo = $_POST['mobileNumbers'];
			$emailIds =$_POST['emailId'];
		}		
		$user_id="";             
		$bookingType = $_POST['bookingType'];
		$DeviceType= $_POST['DeviceType'];
		$peakTimeServices = $DeviceType=="ANDROID" ? $_POST['peakTimeServices'] : "false";
		$pointCabin=$_POST['pointcabindata'];
		$carType=$_POST['carType'];
		$pointNationality=$_POST['pointnationalitydata'];
		$pointAdults=$_POST['pointadultsdata'];
		$pointChilds=$_POST['pointchildsdata'];
		$pointLuggages=$_POST['pointluggagesdata'];
		$pointPickuparea=$_POST['pointpickupareadata'];                        
		$is_pickup=mysqli_num_rows(mysqli_query($this->con,"SELECT * FROM rt_locations WHERE area='$pointPickuparea'"));
		$pointDroparea=$_POST['pointdropareadata'];
		$is_drop=mysqli_num_rows(mysqli_query($this->con,"SELECT * FROM rt_locations WHERE area='$pointDroparea'"));
		$pointAddress=$_POST['pointaddressdata'];
		$pointdate=$_POST['pointdate'];
		$PointNoCars=$_POST['PointNoCars'];
		$cablocalfor="";
		
		
		$newdate = date("d-m-Y",strtotime($pointdate));		
		if($pointdate==""){
			$pointdate= date("Y-m-d H:i:s", strtotime("+30 minutes"));
			$pickup = explode(" ", $pointdate);
			$pointdate =  $pickup[0];
			$pickupTime = $pickup[1];
			$newdate = date("d-m-Y",strtotime("+30 minutes"));
		}
		if($_POST['pointH']==""){
		}else{
			$pointdate= date("Y-m-d", strtotime($_POST['pointdate']));
			$pointH = trim($_POST['pointH']);
			$pointT = trim($_POST['pointS']);
			$pickupTime = $pointH.":".$pointT.":00";
		}
		$dateOfBooking= date("Y-m-d H:i:s");
		$origin=explode(',',$_POST['origin']);
		$destiny=explode(',',$_POST['destiny']); 
		
		$find_address=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($pointAddress));
		$enc3=json_decode($find_address);
		if($enc3->status == 'OK'){
			$origin[0]=$enc3->results[0]->geometry->location->lat;
			$origin[1]=$enc3->results[0]->geometry->location->lng;
			$lat1 = $origin[0];
			$long1 = $origin[1];
		}else{
			return array('Status'=>'false', 'msg'=>'Please enter valid Pickup Address');
		}
		if($is_pickup==0){
			$find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($pointPickuparea));
			file_put_contents("json.txt",$find_pickup); 					
			$enc=json_decode($find_pickup);
			if($enc->status == 'OK'){
				$lat=$enc->results[0]->geometry->location->lat;
				$long=$enc->results[0]->geometry->location->lng;
				$area="";
				foreach($enc->results[0]->address_components as $v){
					if($v->types[0]=="locality")						
						{							
							$area=$v->long_name;
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
				}
			mysqli_query($this->con,"INSERT INTO rt_locations(`area`,`city`,`lat`,`lon`,`zone`,`country`,`state`)"
                                        . " VALUES('$pointPickuparea','$area','$lat','$long','$zone','$country','$state')");
			}else{
				return array('Status'=>'false', 'msg'=>'Please enter valid Pickup Area');
			}
		}
		if($is_drop==0){
			$find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($pointDroparea));
			$enc2=json_decode($find_pickup);
			if($enc2->status == 'OK'){
				$lat=$enc2->results[0]->geometry->location->lat;
				$long=$enc2->results[0]->geometry->location->lng;
				$destiny[0]=$lat;
				$destiny[1]=$long;
				$area="";                                
				foreach($enc2->results[0]->address_components as $v){
					if($v->types[0]=="locality")						
						{							
							$area=$v->long_name;
						}
                                        if($v->types[0]=="administrative_area_level_2")						
						{							
							@$zone=$v->long_name;
						}        
                                         if($v->types[0]=="country")						
						{							
							$country=$v->long_name;
						}       
					if($v->types[0]=="administrative_area_level_1")						
						{							
							$state=$v->long_name;
						}
				}
				mysqli_query($this->con,"INSERT INTO rt_locations(`area`,`city`,`lat`,`lon`,`zone`,`country`,`state`)"
                                        . " VALUES('$pointDroparea','$area','$lat','$long','$zone','$country','$state')");
			}else{
				return array('Status'=>'false', 'msg'=>'Please enter valid Drop Area');
			}
		}
		
		$user_id	=	$this->FetchUserId($emailIds,$mobileNo,$userNames);
		
		if($point_coupan_id=='')
		{
			$coupanLastId="";
		}
		else
		{
		$com_query = "INSERT into tblcouponmaster (CouponID,userId,DeviceType) values('$point_coupan_id','$user_id','$DeviceType')";
		$com_result = mysqli_query($this->con,$com_query);
		mysqli_free_result($com_result);
		mysqli_next_result($this->con);
		
		$query_lastid = "SELECT LAST_INSERT_ID() as coupanLastId FROM tblcouponmaster";
		 $Lastid =mysqli_fetch_assoc(mysqli_query($this->con,$query_lastid));	
		 $coupanLastId=$Lastid["coupanLastId"];
		//SELECT LAST_INSERT_ID() FROM subscription
		}
		
		//$API_MAP = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$origin[0].",".$origin[1]."&destinations=".$destiny[0].",".$destiny[1]."&mode=driving&language=en-US&sensor=false";
		$API_MAP = "https://maps.googleapis.com/maps/api/directions/json?origin=".$origin[0].','.$origin[1]."&destination=".$destiny[0].','.$destiny[1]."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584";
		$data= file_get_contents($API_MAP);
/*echo "<pre>";		
print_r($data);
echo "</pre>";
*/
		$enc=json_decode($data);
		//echo $enc;
		if($enc->status == 'OK'){
			$enc2=$enc->routes[0];
			$distance=round((($enc2->legs[0]->distance->value)/1000),1);
			$distance=round($distance);
		}else{
			return array('Status'=>'false', 'msg'=>'Please enter valid Address ');
		} 
		$data  = array(
			'DeviceType'=>"$DeviceType",
			'BookingType'=>"$bookingType",
			'CabIn' => "$pointCabin",
			'CarType' => "$carType",
			'TaxiType1'=>"$carType",
			'UserName' => "$userNames",
			'useragent'=>$_SERVER['HTTP_USER_AGENT'],
			'clientid'=>"$user_id",
			'EmailId' => "$emailIds",
			'MobileNo' => "$mobileNo",
			'Nationality' => "$pointNationality",							
			'No_Of_Adults' => "$pointAdults",
			'No_Of_Childs' => "$pointChilds",
			'No_Of_Luggages' => "$pointLuggages",
			'PickupArea' => "$pointPickuparea",
			'PickupLocation' => "$pointPickuparea",
			'PickupLandmark'=>"$pointAddress",
			'DropArea' => "$pointDroparea",
			'DropAddress' => "$pointDroparea",
			'DestinationAddress'=>"$pointDroparea",
			'DropLocation'=>"$pointDroparea",
			'PickupState'=>"$pointCabin",
			'PickupAddress' => "$pointAddress",
			'PickupDate' => "$pointdate",
			'PickupTime' => "$pickupTime",
			'BookingDate' => "$dateOfBooking",
			'EstimatedDistance'=>"$distance",
			'PickupLatitude'=>$origin[0], 		
			'PickupLongtitude'=>$origin[1],
			'DestinationLatitude'=>$destiny[0], 		
			'DestinationLongtitude'=>$destiny[1],
			'PickupCity' => "$pointCabin",
			'DestinationCity' => "$pointCabin",
			'partner'=>1,
			'status'=>1,
			'peakTime'=>$peakTimeServices
		);
		$tableName = 'tblcabbooking';
		$query = "INSERT INTO `$tableName` SET";
		$subQuery = '';
		foreach($data as $columnName=>$colValue) {
			$subQuery  .= "`$columnName`='$colValue',";
		}
		$subQuery =  rtrim($subQuery,", ");
		$query .= $subQuery;
		
		$result = mysqli_query($this->con,$query);
		if(mysqli_insert_id($this->con) > 0){
			$booking_id=mysqli_insert_id($this->con);
			if($DeviceType=="ANDROID"){
				$booking_ref=$this->NewCheckWebCallAnd($booking_id,'HA');
			}else{
				$booking_ref=$this->NewCheckWebCallAnd($booking_id,'HW');
			}			
			$data= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$origin[0].','.$origin[1]."&destination=".$destiny[0].','.$destiny[1]."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584");
			$enc=json_decode($data);			
			if($enc->status == 'OK'){
				$enc2=$enc->routes[0];
				$distance2=round((($enc2->legs[0]->distance->value)/1000),1);
				$distance=round($distance2);
				$query = "SELECT * FROM `tblcity` WHERE name='$pointCabin'";
		        $rowDATA =mysqli_fetch_assoc(mysqli_query($this->con,$query));	
		        $cityId=$rowDATA["state"];
				
				$query1 = "SELECT state FROM `tblstates` WHERE id='$cityId'";
		        $rowDATA1 =mysqli_fetch_assoc(mysqli_query($this->con,$query1));	
		        $stateName=$rowDATA1["state"];
				 //echo 'Distance:'.$distance.'Km';
				$datam2=$this->FetchPointToPointData($bookingType,$carType,$cityId,$distance,$cablocalfor);
					//print_r($datam2); //die;
				//$pickupTime=str_replace("th","00",$pickupTime);
					
				if($datam2!=''){
				
				$totalbill=$datam2['totalbill'];
								
				if($point_MinimumBookingAmount!=""){
				if($totalbill>$point_MinimumBookingAmount)
				{
				 if($point_DiscountType=='RS')
				 {
				    $coupon_amt = $totalbill-$point_coupanDisount;
				 }
				 elseif($point_DiscountType=='%')
				 {
				$coupon_amt = $totalbill-($totalbill*$point_coupanDisount/100);
				 }
				}
				}else{
				 $totalbill=$totalbill;
				 }
				
				$PeakFare	=	$this->calculatePeakTimeCharges($totalbill,$pickupTime);
				$totalbill	=	$totalbill + $PeakFare['peakcharge'];
				
				/// Check it
				//// Calculation for NightCharges Starts here /////
				/*if(strtotime($pickupTime) >= strtotime($datam2['night_rate_begins']) and strtotime($pickupTime) < strtotime($datam2['night_rate_ends'])){
				if($datam2['nightCharge_unit'] == 'Rs'){
				$NightCharges = $datam2['NightCharges'];
				}else{
				$NightCharges = ($totalbill * $datam2['NightCharges'])/100;
				}
				}*/

				

				$NightCharges=$this->calculateNigthCharges($pickupTime,$datam2['night_rate_begins'],$datam2['night_rate_ends'],$datam2['nightCharge_unit'],$datam2['NightCharges'],$totalbill);

				//// Calculation for NightCharges Ends here /////
				
				//////// IF NIGHT CHARGES IS INCLUDED IN TOTAL BILL THEN Start HERE ///////
				
				$totalbill	=	$totalbill + $NightCharges;
				$extraPrice	=	$this->calculateExtraCharges($totalbill,$datam2['extras']);
				$totalbill	=	$totalbill + $extraPrice;
				
				$BasicTax = (($totalbill) * $datam2['basic_tax'])/100;
				$totalbill = $totalbill + $BasicTax; 
				
				$totalbill	=	round($totalbill);


				//////// IF NIGHT CHARGES IS INCLUDED IN TOTAL BILL THEN END HERE ///////

				$Min_Pkg_Hrs=$datam2['Min_Pkg_Hrs'];
				$Min_Pkg_KM=$datam2['Min_Pkg_KM'];
				$per_km_charge=$datam2["Per_Km_Charge"];
				$min_distance=$datam2["Min_Distance"];
				$wait_charge=$datam2["WaitingCharge_per_minute"];
				$waiting_min=$datam2["Waitning_minutes"];

				$basic_tax				=	$datam2['basic_tax'];
				$basic_tax_type			=	$datam2['basic_tax_type'];
				$basic_tax_price		=	round($BasicTax);
				$rounding				=	$datam2['rounding'];
				$level					=	$datam2['level'];
				$direction				=	$datam2['direction'];
				$nightcharge_unit		=	$datam2['nightCharge_unit'];
				$nightcharge			=	$datam2['NightCharges'];
				$nightcharge_price		=	round($NightCharges);
				$night_rate_begins		=	$datam2['night_rate_begins'];
				$night_rate_ends		=	$datam2['night_rate_ends'];
				$premiums				=	$datam2['premiums'];
				$premiums_unit			=	$datam2['premiums_unit'];
				$extraPrice				=	round($extraPrice);
				$peakTimePrice			=	round($PeakFare['peakcharge']);
				$peaktimeFrom			=	$PeakFare['peaktimeFrom'];
				$peaktimeTo				=	$PeakFare['peaktimeTo'];
				$peaktimepercentage		=	$PeakFare['peakpercentage'];
				$extras					=	str_replace(array('[',']'),'',$datam2['extras']);

				}
			}else{
				return array('Status'=>'false', 'msg'=>'Please enter valid Address');
			}

			
		//echo $totalbill; die;
			
		 if($bookingType=='103')
		 {
			 /// Need to be work ///
			/*  $distanceAirport=0;
		if($distance<$distanceAirport){
		$dataAirport=$this->FetchAirportDistanceFare($distanceAirport);
		}else{
		$dataAirport=$this->FetchAirportDistanceFare($distance);	
		} */
			 $dataAirport=$this->FetchAirportDistanceFare($distance);
			 $min_distance = $dataAirport['min_distance'];
			 $totalbill = $dataAirport['totalbill'];
			 $dataAir2 =$this->FetchBookingBill($bookingType,$carType,$cityId);
			 
			$per_km_charge=$dataAir2["Per_Km_Charge"];
			$wait_charge=$dataAir2["WaitingCharge_per_minute"];
			$waiting_min=$dataAir2["Waitning_minutes"];
		 }
				
			mysqli_query($this->con,"update tblcabbooking SET estimated_price='$totalbill',approx_distance_charge='$per_km_charge',`approx_after_km`='$min_distance',`approx_waiting_charge`='$wait_charge',appox_waiting_minute='$waiting_min',min_Distance='$min_distance',PrometionalCode='$CouponName',PrometionalName='$PromotionName',No_of_taxies='$PointNoCars',Package_State='$stateName',coupan_id='$coupanLastId',basic_tax='$basic_tax',basic_tax_type='$basic_tax_type', basic_tax_price='$basic_tax_price', rounding='$rounding',level='$level',direction='$direction',nightcharge_unit='$nightcharge_unit',nightcharge='$nightcharge',nightcharge_price='$nightcharge_price',night_rate_begins='$night_rate_begins',night_rate_ends='$night_rate_ends',premiums='$premiums',premiums_unit='$premiums_unit',extras='$extras',extraPrice='$extraPrice',peakTimePrice='$peakTimePrice',peaktimeFrom='$peaktimeFrom',peaktimeTo='$peaktimeTo',peaktimepercentage='$peaktimepercentage',coupon_amt='$coupon_amt' WHERE ID='$booking_id'");
			
			$pickuplatlat=$origin[0];
			$pickuplatlng=$origin[1];
			
			$this->LogStackTrackerData($booking_id,$pickuplatlat,$pickuplatlng);
						
			
			$this->send_sms_new($booking_id);   
			$retrurn =  array("Status" => "Success","per_km_charge"=>$per_km_charge, "ref"=>$booking_ref['generated'],"price"=>$totalbill,"pickupTime"=>$pickupTime,"Pickupdate"=>$newdate,"succMess"=>"Your booking has been confirmed.");
		}else{
			$retrurn = array("Status" => "false", 'succMess'=>'Record not saved successfully.');
		}
		return $retrurn;
		}
	}
	
	///// API used for Web,Android Point To Point Booking Ends Here /////////////
	
	///// API used for Web Airport Booking Starts Here /////////////

	public function airportBookingto(){
		//echo "Mohit"; die;
	/// For going to Airport ///
	$status = 0;
	if($_POST['airDropLocation']!=''){
	/// For coming from Airport ///
	$status = 1;
	}
	//echo $status; die;
	
	    $mobileNo = $_POST['mobileNumbers'];
		$emailIds =$_POST['emailId'];
		$airport_coupan_id=$_REQUEST['airport_coupan_id'];
		$airport_DiscountType=$_REQUEST['airport_DiscountType'];
		$airport_coupan_code=$_POST['airport_coupan_code'];
		$airport_coupan_disount=$_POST['airport_coupan_disount'];
		$airport_MinimumBookingAmount=$_POST['airport_MinimumBookingAmount'];
		
		$airport_Promo_Name=$_POST['airport_Promo_Name'];
		if($airport_coupan_disount=='' or $airport_coupan_disount==NULL)
		{
			 $CouponName = "";
			 $PromotionName = "";
		}
		else
		{
			$CouponName = $airport_coupan_code;
			$PromotionName = $airport_Promo_Name;
		}
		if($airport_coupan_id!="")
		{
			$coupan_status = $this->CheckCoupanCode($airport_coupan_id,$mobileNo,$emailIds);
			
		}
		if($coupan_status == "true")
		{
			 return array('Status'=>'false', 'msg'=>'this coupan is already used. please change the Coupan code');
		}
		else
		{
	
	if(isset($_POST['token'])){
			$token=$_POST['token'];
			 $sql = "SELECT tbluser.ID,tbluser.LoginName as Email,tbluser.UserNo,tbluserinfo.FirstName FROM `tbluser` JOIN `tbluserinfo` ON tbluser.ID=tbluserinfo.UID WHERE `token`='$token'";
			$result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
			if(mysqli_num_rows($result)>0){
			$row = mysqli_fetch_array($result);
			$userNames = $row['FirstName'];
			$mobileNo = $row['UserNo'];
			$emailIds = $row['Email'] ;
			$UserID = $row['ID'] ;			
			}else{
			$userNames = $_POST['userName'];
			$mobileNo = $_POST['mobileNumbers'];
			$emailIds =$_POST['emailId'];
			}
		}else{
			$userNames = $_POST['userName'];
			$mobileNo = $_POST['mobileNumbers'];
			$emailIds =$_POST['emailId'];
		}
	
	
	
	$user_id	=	$this->FetchUserId($emailIds,$mobileNo,$userNames);
	$DeviceType= $_POST['DeviceType'];
	if($airport_coupan_id=='')
		{
			$coupanLastId="";
		}
		else
		{
		$com_query = "INSERT into tblcouponmaster (CouponID,userId,DeviceType) values('$airport_coupan_id','$user_id','$DeviceType')";
		$com_result = mysqli_query($this->con,$com_query);
		mysqli_free_result($com_result);
		mysqli_next_result($this->con);
		
		$query_lastid = "SELECT LAST_INSERT_ID() as coupanLastId FROM tblcouponmaster";
		 $Lastid =mysqli_fetch_assoc(mysqli_query($this->con,$query_lastid));	
		 $coupanLastId=$Lastid["coupanLastId"];
		//SELECT LAST_INSERT_ID() FROM subscription
		}
	
	$bookingType = $_POST['bookingType'];
	
	$airNationalityData=$_POST['airnationality'];
	$airAdultsData=$_POST['airadults'];
	$airChildsData=$_POST['airchilds'];
	$airLuggagesData=$_POST['airluggages'];
	$airFlightNoData=$_POST['airflightno'];
	$airAirportData1=explode('-',$_POST['airairport']);
	$airAirportData=$airAirportData1[0];
	$StateName=$airAirportData1[1];
	$airPickuplocationData=$_POST['airpickuplocation'];
	$airDropLocation=$_POST['airDropLocation'];
	$airLandmarkData=$_POST['airlandmark'];
	$airpickupAddressData=$_POST['airpickupaddress'];
	$airDropAddress=$_POST['airDropAddress'];
	$airPickupDate=$_POST['airpickupdate'];
	$airpickuptimeH=$_POST['airpickuptimeH'];
	$airpickuptimeS=$_POST['airpickuptimeS'];
	$airportNoCars=$_POST['airportNoCars'];
	
	$aircartypes=$_POST['aircartypes'];
	
	
	$newdate = date("d-m-Y",strtotime($airPickupDate));	
		if($_POST['airpickuptimeH']==""){
		}else{
			$airPickupDate= date("Y-m-d", strtotime($_POST['airpickupdate']));
			$airpickuptimeH = trim($_POST['airpickuptimeH']);
			$airpickuptimeT = trim($_POST['airpickuptimeS']);
			$pickupTime = $airpickuptimeH.":".$airpickuptimeT.":00";
		}
		if($airPickupDate==""){
			$airPickupDate= date("Y-m-d H:i:s", strtotime("+30 minutes"));
			$pickup = explode(" ", $airPickupDate);
			$airPickupDate =  $pickup[0];
			$pickupTime = $pickup[1];			
			$newdate = date("d-m-Y",strtotime("+30 minutes"));
		}
		
	 $dateOfBooking= date("Y-m-d H:i:s");
		
	 $data  = array(
	'DeviceType'=>"$DeviceType",
	'BookingType'=>"$bookingType",
	'carType'=>"$aircartypes",
	'UserName' => "$userNames",
	'useragent'=>$_SERVER['HTTP_USER_AGENT'],
	'clientid'=>"$user_id",
	'EmailId' => "$emailIds",
	'MobileNo' => "$mobileNo",
	'Nationality' => "$airNationalityData",
	'No_Of_Adults' => "$airAdultsData",
	'No_Of_Childs' => "$airChildsData",
	'No_Of_Luggages' => "$airLuggagesData",
	'FlightNumber' => "$airFlightNoData",
	'Airport' => "$airAirportData",
	'PickupLocation' => "$airPickuplocationData",
	'DropLocation' => "$airDropLocation",
	'Landmark' => "$airLandmarkData",
	'PickupAddress' => "$airpickupAddressData",
	'DropAddress' => "$airDropAddress",
	'PickupDate' => "$airPickupDate",
	'PickupTime' => "$pickupTime",
	'BookingDate'=> "$dateOfBooking",
	'partner'=>1,
	'status'=>1
	);
	//print_r($data);die;
	if($status==0){
	
	/////$airpickupAddressData=121 DB Gupta Road Karol Bagh new Delhi ///
	$sql6="SELECT * FROM rt_locations where `area` ='$airpickupAddressData'"; //die;
	$result6 = mysqli_query($this->con,$sql6);
	$row6 = mysqli_fetch_assoc($result6);
	$num6 = mysqli_num_rows($result6);
	if($num6 ==0){
	
		$find_address=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($airpickupAddressData));
		$enc3=json_decode($find_address);
		$origin0=$enc3->results[0]->geometry->location->lat;
		$origin1=$enc3->results[0]->geometry->location->lng;
	
		foreach($enc3->results[0]->address_components as $v)
		{
		if($v->types[0]=="locality")						
		{							
		$area=$v->long_name;
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
		}
		
		mysqli_query($this->con,"INSERT INTO rt_locations(`area`,`city`,`lat`,`lon`,`zone`,`country`,`state`)"
	. " VALUES('$airpickupAddressData','$area','$origin0','$origin1','$zone','$country','$state')");
	}else{
		$origin0=$row6['lat'];
		$origin1=$row6['lon'];
		$area=$row6['city'];
	}
	
	$sql2="SELECT lat,tblairportpackage.`long` FROM tblairportpackage where `pkgName` ='$airAirportData'"; //die;	
	$result1 = mysqli_query($this->con,$sql2);
	$row1 = mysqli_fetch_assoc($result1);
	$num1 = mysqli_num_rows($result1);
	
	$destiny0=$row1['lat'];
	$destiny1=$row1['long']; //die;
	
	}
	elseif($status==1){
		
	/////$airDropAddress=121 DB Gupta Road Karol Bagh new Delhi ///
	$sql8="SELECT * FROM rt_locations where `area` ='$airDropAddress'";
	$result7 = mysqli_query($this->con,$sql8);
	$row7 = mysqli_fetch_assoc($result7);
	$num7 = mysqli_num_rows($result7);
	if($num7 ==0){
	
	$find_address=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($airDropAddress));
	$enc4=json_decode($find_address);
	$origin0=$enc4->results[0]->geometry->location->lat;
	$origin1=$enc4->results[0]->geometry->location->lng;
	
	foreach($enc4->results[0]->address_components as $v)
	{
	if($v->types[0]=="locality")						
	{							
	$area=$v->long_name;
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
	}
	mysqli_query($this->con,"INSERT INTO rt_locations(`area`,`city`,`lat`,`lon`,`zone`,`country`,`state`)"
	. " VALUES('$airDropAddress','$area','$origin0','$origin1','$zone','$country','$state')");
	}else{
	$origin0=$row7['lat'];
	$origin1=$row7['lon'];
	$area=$row7['city']; 
	}
	
	$sql9="SELECT lat,tblairportpackage.`long` FROM tblairportpackage where `pkgName` ='$airAirportData'";	
	$result2 = mysqli_query($this->con,$sql9);
	$row2 = mysqli_fetch_assoc($result2);
	$num2 = mysqli_num_rows($result2);
	
	$destiny0=$row2['lat'];
	$destiny1=$row2['long'];
	
	}
	
	///////////// Calculate Distance Between Source and desination using Latitude and Longitude ///
	
	$API_MAP = "https://maps.googleapis.com/maps/api/directions/json?origin=".$origin0.','.$origin1."&destination=".$destiny0.','.$destiny1."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584";
	$data1= file_get_contents($API_MAP);	 
	$enc=json_decode($data1);
	if($enc->status == 'OK'){
	$enc2=$enc->routes[0];
	$distance=round((($enc2->legs[0]->distance->value)/1000),1);
	}

	//Calculate Distance Between Source and desination using Latitude and Longitude From tblairport Address//
	
	if($status==0){
	$sql_address="SELECT `Km` FROM `tblairportaddress` WHERE `Address`='$airPickuplocationData' and `Fix_Point`='$airAirportData' LIMIT 1"; 
	$result8 = mysqli_query($this->con,$sql_address);
	$PickupArea=$airPickuplocationData;
	$PickupLocation=$airPickuplocationData;
	$DropLocation=$airAirportData;
	$DropArea=$airAirportData;
	$DropAddress=$airAirportData;
	$PickupAddress=$airpickupAddressData;
	$PickupCity=$area;
	$DestinationCity=$StateName;
	$PickupLatitude= $origin0;		
	$PickupLongtitude=$origin1;
	$DestinationLatitude=$destiny0;
	$DestinationLongtitude=$destiny1; //die;
	$Address=$airPickuplocationData;
	}else if($status==1){
	$sql_address="SELECT `Km` FROM `tblairportaddress` WHERE `Address`='$airDropLocation' and `Fix_Point`='$airAirportData' LIMIT 1"; 
	$result8 = mysqli_query($this->con,$sql_address);
	$PickupArea=$airAirportData;
	$PickupLocation=$airAirportData;
	$DropLocation=$airDropLocation;
	$DropArea=$airDropLocation;
	$DropAddress=$airDropAddress;
	$PickupAddress=$airAirportData;
	$PickupCity=$StateName;
	$DestinationCity=$area;
	$PickupLatitude= $destiny0;		
	$PickupLongtitude=$destiny1;
	$DestinationLatitude=$origin0;
	$DestinationLongtitude=$origin1;
	$Address=$airDropLocation;
	}
	//die;
	$row8 = mysqli_fetch_assoc($result8);

	$distanceAirport=$row8['Km'];
	//echo $distanceAirport.'<br>';
	//echo $distance.'<br>'; 

	
	$sql1="select id,state from tblstates where state='$StateName' limit 1";
	$result = mysqli_query($this->con, $sql1);
	$val = mysqli_fetch_object($result);
	$stateId=$val->id;
	$stateName=$val->state;	
	//echo $distanceAirport.'<br>';
	//echo $distance; die;
	$cabairportfor=$airAirportData;
	
	if($distance<$distanceAirport){
	//$dataAirport=$this->FetchAirportDistanceFare($distanceAirport);
	$distance=$distanceAirport;
	//$datam2=$this->FetchAirportDistanceFare($bookingType,$aircartypes,$stateId,$distanceAirport,$cabairportfor);
	}else{
	//$dataAirport=$this->FetchAirportDistanceFare($distance);	
	$distance=$distance;
	}
	
	$datam2=$this->FetchAirportDistanceFare($bookingType,$aircartypes,$stateId,$distance,$cabairportfor,$Address);
	
	//print_r($datam2); die;
	//$min_distance = $datam2['min_distance'];
	$Min_Pkg_Hrs=$datam2['Min_Pkg_Hrs'];
	$Min_Pkg_KM=$datam2['Min_Pkg_KM'];
	$min_distance = $datam2['Min_Distance'];
	$totalbill = $datam2['totalbill']; 
	
	if($airport_MinimumBookingAmount!=""){
	if($totalbill>$airport_MinimumBookingAmount)
	{
	if($airport_DiscountType=='RS')
	 {
	    $coupon_amt = $totalbill-$airport_coupan_disount;
	 }
	 elseif($airport_DiscountType=='%')
	 {
	$coupon_amt = $totalbill-($totalbill*$airport_coupan_disount/100);
	 }
	}
	}else{
	 $totalbill=$totalbill;
	 }
	
	
	
	//$datam2 =$this->FetchBookingBill($bookingType,$aircartypes,$stateId);
	//print_r($dataAir2); die;
	
	
	//$pickupTime=str_replace("nd","00",$pickupTime);
	//echo $pickupTime; die;
	
	$PeakFare	=	$this->calculatePeakTimeCharges($totalbill,$pickupTime);
	$totalbill	=	$totalbill + $PeakFare['peakcharge'];
	
	/// Check it
	//// Calculation for NightCharges Starts here /////
	/*if(strtotime($pickupTime) >= strtotime($datam2['night_rate_begins']) and strtotime($pickupTime) < strtotime($datam2['night_rate_ends'])){
	if($datam2['nightCharge_unit'] == 'Rs'){
	$NightCharges = $datam2['NightCharges'];
	}else{
	$NightCharges = ($totalbill * $datam2['NightCharges'])/100;
	}
	}*/
	$NightCharges=$this->calculateNigthCharges($pickupTime,$datam2['night_rate_begins'],$datam2['night_rate_ends'],$datam2['nightCharge_unit'],$datam2['NightCharges'],$totalbill);
	//echo $NightCharges; die;
	//// Calculation for NightCharges Ends here /////

	//////// IF NIGHT CHARGES IS INCLUDED IN TOTAL BILL THEN Start HERE ///////

	$totalbill	=	$totalbill + $NightCharges;
	$extraPrice	=	$this->calculateExtraCharges($totalbill,$datam2['extras']);
	$totalbill	=	$totalbill + $extraPrice;

	$BasicTax 	= 	(($totalbill) * $datam2['basic_tax'])/100;
	$totalbill 	= 	$totalbill + $BasicTax; 
	
	$totalbill	=	round($totalbill);

	//////// IF NIGHT CHARGES IS INCLUDED IN TOTAL BILL THEN END HERE ///////

	$per_km_charge=$datam2["Per_Km_Charge"];
	$wait_charge=$datam2["WaitingCharge_per_minute"];
	$waiting_min=$datam2["Waitning_minutes"];	

	$basic_tax				=	$datam2['basic_tax'];
	$basic_tax_type			=	$datam2['basic_tax_type'];
	$basic_tax_price		=	round($BasicTax);
	$rounding				=	$datam2['rounding'];
	$level					=	$datam2['level'];
	$direction				=	$datam2['direction'];
	$nightcharge_unit		=	$datam2['nightCharge_unit'];
	$nightcharge			=	$datam2['NightCharges'];
	$nightcharge_price		=	round($NightCharges);
	$night_rate_begins		=	$datam2['night_rate_begins'];
	$night_rate_ends		=	$datam2['night_rate_ends'];
	$premiums				=	$datam2['premiums'];
	$premiums_unit			=	$datam2['premiums_unit'];
	$extraPrice				=	round($extraPrice);
	$extras					=	str_replace(array('[',']'),'',$datam2['extras']);
	$peakTimePrice			=	round($PeakFare['peakcharge']);
	$peaktimeFrom			=	$PeakFare['peaktimeFrom'];
	$peaktimeTo				=	$PeakFare['peaktimeTo'];
	$peaktimepercentage		=	$PeakFare['peakpercentage'];
	
	$tableName = 'tblcabbooking';
	$query = "INSERT INTO `$tableName` SET";
	$subQuery = '';
	foreach($data as $columnName=>$colValue) {
	$subQuery  .= "`$columnName`='$colValue',";
	}
	$subQuery =  rtrim($subQuery,", ");
	$query .= $subQuery;
	//echo $query; die;
	$result = mysqli_query($this->con,$query);
	
	

	if(mysqli_insert_id($this->con) > 0){
	
	$booking_id=mysqli_insert_id($this->con);
	if($DeviceType=="ANDROID"){
	$booking_ref=$this->NewCheckWebCallAnd($booking_id,'HA');
	}else{
	$booking_ref=$this->NewCheckWebCallAnd($booking_id,'HW');
	}
		
	//mysqli_next_result($this->con);	
	file_put_contents('pointbill.txt',$totalbill);
		
	$sql_Update="update tblcabbooking SET estimated_price='$totalbill',approx_distance_charge='$min_distance',`approx_after_km`='$per_km_charge',`approx_waiting_charge`='$wait_charge',appox_waiting_minute='$waiting_min',min_Distance='$Min_Pkg_KM',EstimatedDistance='$Min_Pkg_KM',Package_State='$stateName',PickupArea='$PickupArea',DropArea='$DropArea',DropAddress='$DropAddress',PickupAddress='$PickupAddress',PickupCity='$PickupCity',DestinationCity='$DestinationCity',PickupLatitude='$PickupLatitude', PickupLongtitude='$PickupLongtitude',DestinationLatitude='$DestinationLatitude',DestinationLongtitude='$DestinationLongtitude',PickupLocation='$PickupLocation',DropLocation='$DropLocation',CabIn='$PickupCity',PrometionalCode= '$CouponName',PrometionalName='$PromotionName',No_of_taxies='$airportNoCars',coupan_id='$coupanLastId',basic_tax='$basic_tax',basic_tax_type='$basic_tax_type', basic_tax_price='$basic_tax_price', rounding='$rounding',level='$level',direction='$direction',nightcharge_unit='$nightcharge_unit',nightcharge='$nightcharge',nightcharge_price='$nightcharge_price',night_rate_begins='$night_rate_begins',night_rate_ends='$night_rate_ends',premiums='$premiums',premiums_unit='$premiums_unit',extras='$extras',extraPrice='$extraPrice',peakTimePrice='$peakTimePrice',peaktimeFrom='$peaktimeFrom',peaktimeTo='$peaktimeTo',peaktimepercentage='$peaktimepercentage',coupon_amt='$coupon_amt' WHERE ID='$booking_id'"; 
	mysqli_query($this->con,$sql_Update);
	
	$pickuplatlat=$origin0;
	$pickuplatlng=$origin1;

	$this->LogStackTrackerData($booking_id,$pickuplatlat,$pickuplatlng);
	$this->send_sms_new($booking_id);


	return array("Status" => "Success", "ref"=>$booking_ref['generated'],"price"=>$totalbill,"pickupTime"=>$pickupTime,"Pickupdate"=>$newdate);
	}else{
	return array("Status" => "Unsuccess");
	}
	}

	}
	
	///// API used for Web Airport Booking Ends Here /////////////
	
	
	
	///// API used for Web,Android Call center Booking Stack Logs Tracker Starts Here /////////////
	
	public function LogStackTrackerData($booking_id,$pickuplatlat,$pickuplatlng){
			
	$sql1="INSERT INTO tblbookinglogs(bookingid,`status`,message,`time`) VALUES('$booking_id',1,'Requesting car',NOW())";
	mysqli_query($this->con,$sql1) or die(mysqli_error($this->con));
	
	$sql2="INSERT INTO `booking_stack`(`booking_id`,`lat`,`long`,`status`) VALUES('$booking_id','$pickuplatlat','$pickuplatlng','')";
	mysqli_query($this->con,$sql2) or die(mysqli_error($this->con));

	$sql3="INSERT INTO `tblbookingtracker` (`BookingID`,`Latitutude`,`Logitude`,`Date_Time`,`CabStatus`) 
	VALUES('$booking_id','$pickuplatlat','$pickuplatlng',NOW(),'1')";
	mysqli_query($this->con,$sql3) or die(mysqli_error($this->con));
		
	}
	
	///// API used for Web,Android Call center Booking Stack Logs Tracker Ends Here /////////////
	
	
	
	
	
	
	///////// Create Booking ,Fare Calculate as a peak time booking////////////////
	
	 public function RideEstimate(){
		  $booking_type=trim($_REQUEST["bookType"]);
		 $cab_type = trim($_REQUEST['carType']);
		 $distance = trim($_REQUEST['distance']);
		 $subpackage_name=trim($_REQUEST["subBookType"]); 
		 $cityName=trim($_REQUEST["city"]);
		 $address =  trim($_REQUEST['Address']);
		 $fixpoint =  trim($_REQUEST['fixedAddress']);
		 $query = "SELECT * FROM `tblcity` WHERE name='$cityName'";
	//return array("status"=>true,"booking_type"=>$booking_type,"cab_type"=>$cab_type,"distance"=>$distance,"subpackage_name"=>$subpackage_name,"cityName"=>$cityName,"address"=>$address,"fixpoint"=>$fixpoint);
		 $rowDATA =mysqli_fetch_assoc(mysqli_query($this->con,$query));	
		 $cityId=$rowDATA["state"];
			switch ($booking_type){
				case 101:
					 $sql = "SELECT * FROM `tbllocalpackage` WHERE Package='$subpackage_name' AND masterPKG_ID='$booking_type' AND City_ID='$cityId'";
					 $row = mysqli_query($this->con,$sql);		
					  $date = mysqli_fetch_object($row);
					  $Km = $date->Km;
					  $Economy_Price = $date->Economy_Price;
					  $Sidan_Price = $date->Sidan_Price;
					  $Prime_Price = $date->Prime_Price;
					  /// 40 Km divided by 60 minute ///////
					   $permntavr=40/60;
					   $totalmint=round($distance/$permntavr);
					   if($totalmint>60){
						   $hors=intval($totalmint/60);
						   $leftMinut=$totalmint%60;
						   $Est_Time=$hors." Hours ".$leftMinut." Minutes";
					   }else{
						   $Est_Time="0 Hours ".$totalmint." Minutes";
					   }
					  if($distance > $Km){
						 $sql = "SELECT * FROM `tblbookingbill` where BookingTypeId='$booking_type' AND CabType='$cab_type' AND CityId='$cityId'"; 
						  $row = mysqli_query($this->con,$sql);		
						  $date = mysqli_fetch_object($row);
						  $Per_Km_Charge = $date->Per_Km_Charge;
							 $extraKm=$distance-$Km;
							 $ExtraFare=$extraKm*$Per_Km_Charge;
							 if($cab_type==1){
								 $TotalFare=$ExtraFare+$Economy_Price;
							 }elseif($cab_type==2){
								 $TotalFare=$ExtraFare+$Sidan_Price;
							 }elseif($cab_type==3){
								 $TotalFare=$ExtraFare+$Prime_Price;    
							 }
							 /// What is this $ext
							 $ext=($TotalFare*10)/100;
							 $maxiTotalFare=$TotalFare+$ext;
							  return array("status"=>true,"minTotalFare"=>round($TotalFare),"maxiTotalFare"=>$maxiTotalFare,"TotalTime"=>$Est_Time);
						 }else{
							if($cab_type==1){
								 $TotalFare=$Economy_Price;
							 }elseif($cab_type==2){
								 $TotalFare=$Sidan_Price;
							 }elseif($cab_type==3){
								 $TotalFare=$Prime_Price;
							 }
							 $ext=($TotalFare*10)/100;
							 $maxiTotalFare=$TotalFare+$ext;
							 return array("status"=>true,"minTotalFare"=>round($TotalFare),"maxiTotalFare"=>$maxiTotalFare,"TotalTime"=>$Est_Time);
					   }
				break;
				case 102:
				  $sql = "SELECT * FROM `tblbookingbill` where BookingTypeId='$booking_type' AND CabType='$cab_type' AND CityId='$cityId'";
						  $row = mysqli_query($this->con,$sql);		
						  $data = mysqli_fetch_assoc($row);
				/***********************************Calculate Fare*********/
				    $sqlite = "SELECT * FROM `tblmasterpackage` where Package_Id='102'"; 
						  $rowlite = mysqli_query($this->con,$sqlite);
                          $subpackage = mysqli_fetch_assoc($rowlite);
						  $permntavr=40/60;
					   $totalmint=round($distance/$permntavr);
						if($subpackage['Sub_Package_Id'] == 1){
							if($distance > $data['Min_Distance']){
								$ExtraKM=$distance - $data['Min_Distance'];
								$ExtraFare = $ExtraKM*$data["Per_Km_Charge"];
								$EstimatedPrice = $ExtraFare + $data["MinimumCharge"];
							}else{
								$EstimatedPrice = $data["MinimumCharge"];								
							}
						}
						elseif($subpackage['Sub_Package_Id'] == 2)
						{
							$EstimatedPrice = $totalmint*$data["tripCharge_per_minute"];
						}
						elseif($subpackage['Sub_Package_Id'] == 3)
						{
							$EstimatedPrice_PerKm = $distance*$data["Per_Km_Charge"];
							$EstimatedPrice_PerHr = $totalmint*$data["tripCharge_per_minute"];
							if($EstimatedPrice_PerKm > $EstimatedPrice_PerHr){
								$EstimatedPrice = $EstimatedPrice_PerKm;
							}else{
								$EstimatedPrice = $EstimatedPrice_PerHr;
							}
							if($data["MinimumCharge"] > $EstimatedPrice){
								$EstimatedPrice = $data["MinimumCharge"];
							}
						}
						elseif($subpackage['Sub_Package_Id'] == 4)
						{
							$EstimatedPrice = $data['Kms'] * $data['Per_Km_Charge'];
							if($data['MinimumCharge'] > $EstimatedPrice)
							{
								$EstimatedPrice = $data['MinimumCharge'];
							}
						}
						if($data["MinimumCharge"] > $EstimatedPrice){
								$EstimatedPrice = $data["MinimumCharge"];
							}
				/*********************Calculate Trip Time***********************/					   
					   if($totalmint>60){
						   $hors=intval($totalmint/60);
						   $leftMinut=$totalmint%60;
						   $Est_Time=$hors." Hours ".$leftMinut." Minutes";
					   }else{
						   $Est_Time="0 Hours ".$totalmint." Minutes";
					   }
				/*********************Calculate Extra Charges*****************************************/	
					//****Basic Tax
				   $BasicTax = ($EstimatedPrice*$data['basic_tax'])/100;				
				   $EstimatedPrice = $EstimatedPrice + $BasicTax;
				    //**** 10% Extra
					$ext=($EstimatedPrice*10)/100;
				    $maxiTotalFare=$EstimatedPrice+$ext;
		        /***********************************************************************/				 
				  return array("status"=>true,"minTotalFare"=>round($EstimatedPrice),"maxiTotalFare"=>round($maxiTotalFare),"TotalTime"=>$Est_Time);						
/*********/		break;
				case 103:
				$sql = "SELECT * FROM `tblbookingbill` where BookingTypeId='$booking_type' AND CabType='$cab_type' AND CityId='$cityId'"; //die;
						  $row = mysqli_query($this->con,$sql);		
						  $data = mysqli_fetch_assoc($row);
			    $sql1 = "SELECT * FROM `tblairportaddress` where Address ='$address' AND Fix_Point='$fixpoint'"; 
						  $row1 = mysqli_query($this->con,$sql1);		
						  $data1 = mysqli_fetch_assoc($row1);
						  $num_rows = mysqli_num_rows($row1);
				/***********************************Calculate Fare*********/
				    $sqlite = "SELECT * FROM `tblmasterpackage` where Package_Id='103'"; 
						  $rowlite = mysqli_query($this->con,$sqlite);
                          $subpackage = mysqli_fetch_assoc($rowlite);
						  $permntavr=40/60;
					   $totalmint=round($distance/$permntavr);
					if($num_rows>0)
					{
						 $EstimatedDistance = $data1['Km'];
						 $EstimatedPrice = $data1['Fare'];	
					}
				    else
				    {
						$dist = round($distance); 
						 $qry = "select (select distinct Km from tblairportaddress where Km<='$dist' order by Km desc limit 1) as MinKm,
						(select Fare from tblairportaddress where Km<='$dist' order by Km desc limit 1) as MinFare,
						(select distinct Km from tblairportaddress where Km>='$dist' order by Km Asc limit 1) as MaxKm,
						(select Fare from tblairportaddress where Km>='$dist' order by Km Asc limit 1) as MaxFare from tblairportaddress limit 1";
						$result = mysqli_query($this->con, $qry);
						$num_rows1 = mysqli_num_rows($result);
						$info = mysqli_fetch_assoc($result);
				    if($num_rows1>0)
					{
						$min_diff = $distance - $info['MinKm'];
						$max_diff =$info['MaxKm'] - $distance;
						$diff_distance = $info['MaxKm']-$info['MinKm'];
						if(($diff_distance/2) < $max_diff)
						{
							  $EstimatedDistance = $info['MinKm'];
							 $EstimatedPrice = $info['MinFare'];
						}
						else
						{
						        $EstimatedDistance = $info['MaxKm'];
							    $EstimatedPrice = $info['MaxFare'];
						}
					}
					else
					    {
								if($subpackage['Sub_Package_Id'] == 1)
								{
									if($distance > $data['Min_Distance'])
									{
										$ExtraKM=$distance - $data['Min_Distance'];
										$ExtraFare = $ExtraKM*$data["Per_Km_Charge"];
										$EstimatedPrice = $ExtraFare + $data["MinimumCharge"];
									}
									else
									{
										$EstimatedPrice = $data["MinimumCharge"];								
									}
								}
								elseif($subpackage['Sub_Package_Id'] == 2)
								{
									$EstimatedPrice = $totalmint*$data["tripCharge_per_minute"];
								}
								elseif($subpackage['Sub_Package_Id'] == 3)
								{
									$EstimatedPrice_PerKm = $distance*$data["Per_Km_Charge"];
									$EstimatedPrice_PerHr = $totalmint*$data["tripCharge_per_minute"];
									if($EstimatedPrice_PerKm > $EstimatedPrice_PerHr){
										$EstimatedPrice = $EstimatedPrice_PerKm;
									}else{
										$EstimatedPrice = $EstimatedPrice_PerHr;
									}
									if($data["MinimumCharge"] > $EstimatedPrice){
										$EstimatedPrice = $data["MinimumCharge"];
									}
								}
								elseif($subpackage['Sub_Package_Id'] == 4)
								{
									$EstimatedPrice = $data['Kms'] * $data['Per_Km_Charge'];
									if($data['MinimumCharge'] > $EstimatedPrice)
									{
										$EstimatedPrice = $data['MinimumCharge'];
									}
								}
								if($data["MinimumCharge"] > $EstimatedPrice)
								{
									$EstimatedPrice = $data["MinimumCharge"];
								}
					    }
				   }
				/*********************Calculate Trip Time***********************/					   
					   if($totalmint>60){
						   $hors=intval($totalmint/60);
						   $leftMinut=$totalmint%60;
						   $Est_Time=$hors." Hours ".$leftMinut." Minutes";
					   }else{
						   $Est_Time="0 Hours ".$totalmint." Minutes";
					   }
				/*********************Calculate Extra Charges*****************************************/	
					//****Basic Tax
				   $BasicTax = ($EstimatedPrice*$data['basic_tax'])/100;				
				   $EstimatedPrice = $EstimatedPrice + $BasicTax;
				    //**** 10% Extra
					$ext=($EstimatedPrice*10)/100;
				    $maxiTotalFare=$EstimatedPrice+$ext;
		        /***********************************************************************/				 
				  return array("status"=>true,"EstimatedDistance"=>round($EstimatedDistance),"minTotalFare"=>round($EstimatedPrice),"maxiTotalFare"=>round($maxiTotalFare),"TotalTime"=>$Est_Time);
				break;
				case 104:
				break;
				case 105:
				break;
			}
	  }
	  
	  ///////////// Used in User Andriod Application /////
	  
	  function RateCardByCity_UserAndroid(){
		$stateName = trim($_REQUEST['city']);
		$dldld = mysqli_query($this->con,"SELECT * FROM `tblstates` WHERE state='$stateName'") or die(mysqli_error($this->con));
		$djkdjkjk = mysqli_fetch_object($dldld);
		$cityId= $djkdjkjk->id;
		$country = $_REQUEST['country'];
		$booking_type = $_REQUEST['booking_type'];
		$car_type = $_REQUEST['car_type'];
		$query="SELECT * FROM `tblbookingbill` WHERE CabType='$car_type' AND BookingTypeId='$booking_type' AND CityId='$cityId'";
		$data = mysqli_query($this->con,$query) or die(mysqli_error($this->con));
		
		$data2 = mysqli_query($this->con,"SELECT distinct name FROM `tblcablistmgmt` WHERE CabType='$car_type' AND BookingType='$booking_type' AND drivingInCity='$cityId'") or die(mysqli_error($this->con));
			
			$cabName='';
			while($feted=mysqli_fetch_assoc($data2)){
				if($feted["name"]!='')
				{
					$cabName.=$feted["name"].", ";		
				}
						
			}
			$cabName=rtrim($cabName, ', ');
		if(mysqli_num_rows($data) > 0){
				while($row = mysqli_fetch_assoc($data)){
				$waitingfee_upto_minutes = $row['waitingfee_upto_minutes'];
				$waitingfee_upto_minutesArr = json_decode($waitingfee_upto_minutes);
				$waitningfeeKey = array();
				$waitingfeeValue = array();
				for($i=0;$i<count($waitingfee_upto_minutesArr);$i++){
					$wtngFee = explode("_",$waitingfee_upto_minutesArr[$i]);
					$waitningfeeKey[$wtngFee[0]] = $wtngFee[1];
				}
				$MinimumCharge = $row['MinimumCharge'];
				$Min_Distance =  $row['Min_Distance'];
				 if($booking_type==101){
					$sub_booking_type = trim($_REQUEST['sub_booking_type']);
					 $sql = "SELECT * FROM `tbllocalpackage` WHERE Package='$sub_booking_type' AND masterPKG_ID='$booking_type' AND City_ID='$cityId'";
					 $row123 = mysqli_query($this->con,$sql);		
					  $date = mysqli_fetch_object($row123);					 
					  $Min_Distance = $date->Km;
					  if($car_type==1){
						  $MinimumCharge = $date->Economy_Price;
					  }elseif($car_type==2){
						  $MinimumCharge  = $date->Sidan_Price;
					  }elseif($car_type==3){						  
						  $MinimumCharge = $date->Prime_Price;
					  }					  
				}
				$Per_Km_Charge = $row['Per_Km_Charge'];
				$waiting_fees = explode('_',$row['waiting_fees']);				
				$rowCab[]= array("WaitingFreeMin"=>$waiting_fees[0],"WaitingBiforeCharge"=>$waiting_fees[1],"WaitingAfterCharge"=>$waiting_fees[2],"MinimumCharge"=>$MinimumCharge,"Min_Distance"=>$Min_Distance,"Per_Km_Charge"=>$Per_Km_Charge,"WaitingFee"=>$waitningfeeKey,"UniqueCabs"=>$cabName);
			   }			  
		    return array("status"=>true,"data"=>$rowCab);
		}else{
			return array("status"=>false,"message"=>"No rate card for this type of booking");
		}		
	}
	
	/////Function Using in Driver Login Points Starts Here ////////////
	public function DriverLoginPoints(){
		$sql="SELECT UID,login_time FROM `tbl_driver_points` WHERE login_status='1' and valid_status='1'"; 
		$res = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
		while($result=mysqli_fetch_array($res)){
			$id				=		$result['UID']; 
			$login_time		=		$result['login_time']; 
			$current_time	= 		date('Y-m-d H:i:s');
			$diff = strtotime($current_time) - strtotime($login_time);
			$total_hours = round(($diff)/ (60*60)); //die;
			if($total_hours>=0 && $total_hours<=3){
				$point=1;
			}elseif($total_hours>3 && $total_hours<=6){
				$point=2;
			}elseif($total_hours>6 && $total_hours<=9){
				$point=3;
			}elseif($total_hours>9 && $total_hours<=12){
				$point=4;
			}elseif($total_hours>12){
				$point=5;
			}
			$sql_update="UPDATE tbl_driver_points SET login_point='$point' WHERE UID='$id'";
			mysqli_query($this->con,$sql_update) or die(mysqli_error($this->con));						
			}
	}
	/////Function Using in Driver Login Points Ends Here ////////////
	
	/////Function Using in Driver Amount and Free Driver Points Starts Here ////////////
	public function FreeDriverAmountPoints(){
		$sql="SELECT a.UID,b.SecurityAmt as amount FROM `tbl_driver_points` a inner join tbldriver b on a.UID= b.UID WHERE a.login_status='1' and a.booking_status='0' and valid_status='1'"; 
		$res = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
		while($result=mysqli_fetch_array($res)){
			$id							=		$result['UID']; 
			$amount						=		$result['amount']; 
			//// Calculate point on commission based ////////////
			$amount_commision			=		$amount*5;
			if($amount_commision<=1000){
				$point=0;
			}elseif($amount_commision<=2000){
				$point=1;
			}elseif($amount_commision<=3000){
				$point=2;
			}elseif($amount_commision<=4000){
				$point=3;
			}elseif($amount_commision<=5000){
				$point=4;
			}else{
				$point=5;
			}
		$sql_update="UPDATE tbl_driver_points SET free_credit_point='$point',amount='$amount' WHERE UID='$id'"; 
		mysqli_query($this->con,$sql_update) or die(mysqli_error($this->con));						
			} 
	}
	/////Function Using in Driver Amount and Free Driver Points Ends Here ////////////
	
	/////Function Using in Driver IDLE Points Starts Here ////////////
	public function DriverIdlePoints(){
		$sql="SELECT UID,prev_booking_done FROM `tbl_driver_points` WHERE login_status='1' and booking_status='0' and valid_status='1'"; 
		$res = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
		while($result=mysqli_fetch_array($res)){
			$id						=		$result['UID']; 
			$prev_booking_done		=		$result['prev_booking_done']; 
			$current_time		= 		date('Y-m-d H:i:s');
			$diff = strtotime($current_time) - strtotime($prev_booking_done);
			
			$years = floor($diff / (365*60*60*24)); 
			$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
			$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

			$total_trip_hours = ($diff)/ (60*60);
			$minuts = round(($total_trip_hours*60));
			
			if($minuts<=30){
				$point=0;
			}elseif($minuts<=60){
				$point=1;
			}elseif($minuts<=90){
				$point=2;
			}elseif($minuts<=120){
				$point=3;
			}elseif($minuts<=150){
				$point=4;
			}else{
				$point=5;
			}
		$sql_update="UPDATE tbl_driver_points SET idle_point='$point' WHERE UID='$id'"; 
		mysqli_query($this->con,$sql_update) or die(mysqli_error($this->con));						
			} 
	}
	/////Function Using in Driver IDLE Points Ends Here ////////////
	
	/////Function Using in Driver Operated less Bookings Points Starts Here ////////////
	public function DriverOperatedLessBookingPoints(){
		$sql="SELECT UID,total_booking_operated FROM `tbl_driver_points` WHERE login_status='1' and valid_status='1'"; 
		$res = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
		while($result=mysqli_fetch_array($res)){
			$id						=		$result['UID']; 
			$total_booking_operated	=	$result['total_booking_operated']; 
			///// Assume 10 bookings in a day //////
			$fixed_booking	=	10;
			$percent_bookings_operated=(($fixed_booking*100)/$total_booking_operated);
			if($percent_bookings_operated<=20){
				$point=5;
			}elseif($percent_bookings_operated<=40){
				$point=4;
			}elseif($percent_bookings_operated<=60){
				$point=3;
			}elseif($percent_bookings_operated<=80){
				$point=2;
			}elseif($percent_bookings_operated<=100){
				$point=1;
			}else{
				$point=0;
			}
		$sql_update="UPDATE tbl_driver_points SET booking_operated_point='$point' WHERE UID='$id'"; 
		mysqli_query($this->con,$sql_update) or die(mysqli_error($this->con));						
			} 
	}
	/////Function Using in Driver Operated less Bookings Points Ends Here ////////////
	
	/////Function Using in Driver Based on Distance Points Starts Here ////////////
	
	public function DriverDistanceWisePoints(){
		//$booking_id=$_REQUEST['booking_id'];
		$booking_id='6888';
		$sql="SELECT PickupLatitude,PickupLongtitude FROM `tblcabbooking` WHERE ID='$booking_id'"; 
		$res = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
		$result=mysqli_fetch_array($res);
		
		$sql1="SELECT UID FROM `tbl_driver_points` WHERE login_status='1' and valid_status='1'"; 
		$res1 = mysqli_query($this->con,$sql1) or die(mysqli_error($this->con));
				
		while($result1=mysqli_fetch_array($res1)){
		$id=$result1['UID'];
		$sql3="SELECT `lat`,`longi` FROM `tbldriverlocation` WHERE `user_id`='$id' ORDER by id DESC LIMIT 0,1";
		$res3 = mysqli_query($this->con,$sql3) or die(mysqli_error($this->con));
		$num_rows = mysqli_num_rows($res3);
		$result3=mysqli_fetch_array($res3);
		
		if($num_rows>0){
		$dist=$this->getDistance($result['PickupLatitude'], $result['PickupLongtitude'], $result3['lat'], $result3['longi'], "K"); 
		$dist=round($dist*1000);//die;
		}
		
		//// Matching in meters /////////
			if($dist>0 && $dist<=1000){
				$point=5;
			}elseif($dist<=2000){
				$point=4;
			}elseif($dist<=3000){
				$point=3;
			}elseif($dist<=4000){
				$point=2;
			}elseif($dist<=5000){
				$point=1;
			}else{
				$point=0;
			}
		$sql_update="UPDATE tbl_driver_points SET distance_point='$point' WHERE UID='$id'"; 
		mysqli_query($this->con,$sql_update) or die(mysqli_error($this->con));	
		}
		
	}
	/////Function Using in Driver Based on Distance Points Ends Here ////////////
	
	/////Function To Calculate Distance Starts Here ////////////
	
	public function getDistance($lat1, $lon1, $lat2, $lon2, $unit) {
		
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);
		if ($unit == "K") {
			return ($miles * 1.609344);
		} else if ($unit == "N") {
			return ($miles * 0.8684);
		} else {
			return $miles;
		}
	}
	
	/////Function To Calculate Distance Ends Here ////////////
	
	/* for(;;){
		sleep(2);
		$this->DriverLoginPoints();
		$this->FreeDriverAmountPoints();
	} */
	
	
	//////////// Function for Intercity Booking Starts Here/////////////////////
	
	public function intercityBooking(){	

		if(isset($_POST['token'])){
			$token=$_POST['token'];
			$result = mysqli_query($this->con,"SELECT `UID`,`Email`,`UserNo`,`FirstName` FROM `tbluser` JOIN `tbluserinfo` ON tbluser.ID=tbluserinfo.UID  
			WHERE `token` = '$token'") or die(mysqli_error($this->con));
			if(mysqli_num_rows($result)>0){
			$row = mysqli_fetch_array($result);
			$userId	= $row['UID'];
			$userNames = $row['FirstName'];
			$mobileNo = $row['UserNo'];
			$emailIds = $row['Email'] ;      
			}else{
			$userNames = $_POST['userName'];
			$mobileNo = $_POST['mobileNumbers'];
			$emailIds =$_POST['emailId'];
			$userId	= $_POST['userId'];
			}
		}else{
			$userNames = $_POST['userName'];
			$mobileNo = $_POST['mobileNumbers'];
			$emailIds =$_POST['emailId'];
			$userId	= $_POST['userId'];
		}
		
		$user_id="";             
		$bookingType = $_POST['bookingType'];
		$carPrice=$_POST['carPrice'];
		$carFixKm=$_POST['carFixKm'];
		$carType=$_POST['carType'];
		$routeId=$_POST['routeId'];
		$intercityNationalityData=$_POST['intercityNationality'];
		$intercityAdultsData=$_POST['intercityAdults'];
		$intercityChildsData=$_POST['intercityChilds'];
		$intercityLuggagesData=$_POST['intercityLuggages'];
		$intercityPickupH=trim($_POST['intercityPickupH']);
		$intercityPickupM=$_POST['intercityPickupM'];
		$intercityPickuparea=$_POST['intercityfrom'];
		$intercityDropuparea=$_POST['intercityto'];
		$intercityPickupAddress=$_POST['intercityPickupAddress'];
		$intercityDropAddress=$_POST['intercityDropAddress'];		
		$is_pickup=mysqli_num_rows(mysqli_query($this->con,"SELECT * FROM rt_locations WHERE area='$intercityPickupAddress'"));
		$is_drop=mysqli_num_rows(mysqli_query($this->con,"SELECT * FROM rt_locations WHERE area='$intercityDropAddress'"));
		
		$pointdate=$_POST['intercityDepdate'];
		$newdate = date("d-m-Y",strtotime($pointdate));		
		if($pointdate==""){
			$pointdate= date("Y-m-d H:i:S", strtotime("+30 minutes"));
			$pickup = explode(" ", $pointdate);
			$pickupTime = $pickup[1]; 
			$newdate = date("d-m-Y",strtotime("+30 minutes"));
		}
		if($intercityPickupH==""){			
		}else{
			$intercityPickupH = $intercityPickupH;
			$intercityPickupM = trim($_POST['intercityPickupM']);
			$pickupTime = $intercityPickupH.":".$intercityPickupM.":00";
		}
		$dateOfBooking= date("Y-m-d H:i:s");
		//$origin=explode(',',$_POST['origin']);
		//$destiny=explode(',',$_POST['destiny']);
		
		$find_address=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($intercityPickupAddress));
		$enc3=json_decode($find_address);
		if($enc3->status == 'OK'){
			$origin[0]=$enc3->results[0]->geometry->location->lat;
			$origin[1]=$enc3->results[0]->geometry->location->lng;
			$lat1 = $origin[0];
			$long1 = $origin[1];
		}else{
			return array('Status'=>'false', 'msg'=>'Please enter valid Pickup Address');
		}
		
		$find_address1=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($intercityDropAddress));
		$enc3_1=json_decode($find_address1);
		if($enc3_1->status == 'OK'){
			$destiny[0]=$enc3_1->results[0]->geometry->location->lat;
			$destiny[1]=$enc3_1->results[0]->geometry->location->lng;
		}else{
			return array('Status'=>'false', 'msg'=>'Please enter valid Drop Address hello 6');
		}
		
		if($is_pickup==0){
			$find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($intercityPickupAddress));
			file_put_contents("json.txt",$find_pickup); 					
			$enc=json_decode($find_pickup);
			if($enc->status == 'OK'){
				$lat=$enc->results[0]->geometry->location->lat;
				$long=$enc->results[0]->geometry->location->lng;
				$area="";
				foreach($enc->results[0]->address_components as $v){
					if($v->types[0]=="locality")						
						{							
							$area=$v->long_name;
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
				}
				mysqli_query($this->con,"INSERT INTO rt_locations(`area`,`city`,`lat`,`lon`,`zone`,`country`,`state`)"
                                        . " VALUES('$intercityPickupAddress','$area','$lat','$long','$zone','$country','$state')");
			}else{
				return array('Status'=>'false', 'msg'=>'Please enter valid Pickup Area');
			}
		}
		if($is_drop==0){
			$find_dropup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($intercityDropAddress));
			$enc2=json_decode($find_dropup);
			if($enc2->status == 'OK'){
				$lat=$enc2->results[0]->geometry->location->lat;
				$long=$enc2->results[0]->geometry->location->lng;
				$area="";                                
				foreach($enc2->results[0]->address_components as $v){
					if($v->types[0]=="locality")						
						{							
							$area=$v->long_name;
						}
                                        if($v->types[0]=="administrative_area_level_2")						
						{							
							@$zone=$v->long_name;
						}        
                                         if($v->types[0]=="country")						
						{							
							$country=$v->long_name;
						}       
					if($v->types[0]=="administrative_area_level_1")						
						{							
							$state=$v->long_name;
						}
				}
				mysqli_query($this->con,"INSERT INTO rt_locations(`area`,`city`,`lat`,`lon`,`zone`,`country`,`state`)"
                                        . " VALUES('$intercityDropAddress','$area','$lat','$long','$zone','$country','$state')");
			}else{
				return array('Status'=>'false', 'msg'=>'Please enter valid Drop Area');
			}
		}
		
		$user_id	=	$this->FetchUserId($emailIds,$mobileNo,$userNames);
		
		/// Google API Distance No Need /////////
		
		/* $API_MAP = "https://maps.googleapis.com/maps/api/directions/json?origin=".$origin[0].','.$origin[1]."&destination=".$destiny[0].','.$destiny[1]."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584";
		$data= file_get_contents($API_MAP);	 
		$enc=json_decode($data);
		if($enc->status == 'OK'){
			$enc2=$enc->routes[0];
			$distance=round((($enc2->legs[0]->distance->value)/1000),1);
		}else{
			return array('Status'=>'false', 'msg'=>'Please enter valid Address');
		} */
		
		$data  = array(
			'DeviceType'=>$_POST['DeviceType'],
			'BookingType'=>"$bookingType",
			'CarType' => "$carType",
			'UserName' => "$userNames",
			'useragent'=>$_SERVER['HTTP_USER_AGENT'],
			'clientid'=>"$user_id",
			'EmailId' => "$emailIds",
			'MobileNo' => "$mobileNo",
			'Nationality' => "$intercityNationalityData",							
			'No_Of_Adults' => "$intercityAdultsData",
			'No_Of_Childs' => "$intercityChildsData",
			'No_Of_Luggages' => "$intercityLuggagesData",
			'PickupArea' => "$intercityPickuparea",
			'DropArea' => "$intercityDropuparea",                                                               
			'PickupAddress' => "$intercityPickupAddress",
			'DropAddress' => "$intercityDropAddress",
			'PickupDate' => "$pointdate",
			'PickupTime' => "$intercityPickupH".":"."$intercityPickupM",
			'BookingDate' => "$dateOfBooking",
			/// Google API Distance No Need //////
			//'EstimatedDistance'=>"$distance",	
			'EstimatedDistance'=>"$carFixKm",
			/// Google API Distance No Need //////
						
			'PickupLatitude'=>$origin[0], 		
			'PickupLongtitude'=>$origin[1],
			'DestinationLatitude'=>$destiny[0], 		
			'DestinationLongtitude'=>$destiny[1], 
			'partner'=>1,
			'status'=>1,
			'routeId'=>$routeId
		);
		$tableName = 'tblcabbooking';
		$query = "INSERT INTO `$tableName` SET";
		$subQuery = '';
		foreach($data as $columnName=>$colValue) {
			$subQuery  .= "`$columnName`='$colValue',";
		}
		$subQuery =  rtrim($subQuery,", ");
		$query .= $subQuery;
		$result = mysqli_query($this->con,$query);
		if(mysqli_insert_id($this->con) > 0){
			$booking_id=mysqli_insert_id($this->con);
			$booking_ref=$this->NewCheckWebCallAnd($booking_id,'HW');
			
			$dataValue=$this->FetchIntercityDistanceData($routeId,$carType);
			
			if($dataValue['total_amt']>$carPrice){
				$totalbill=$dataValue['total_amt'];
				$totalkm=$dataValue['fix_km'];
			}else{
				$totalbill=$carPrice;
				$totalkm=$carFixKm;
			}
			
			$query = "SELECT state FROM `tblcity` WHERE name='$intercityPickuparea'";
			$rowDATA =mysqli_fetch_assoc(mysqli_query($this->con,$query));	
			$cityId=$rowDATA["state"];

			$dataValue1=$this->FetchPointToPointData($bookingType,$carType,$cityId,$totalkm);
			if($dataValue1!=''){
			$per_km_charge=$dataValue1["Per_Km_Charge"];
			$min_distance=$dataValue1["Min_Distance"];
			$wait_charge=$dataValue1["WaitingCharge_per_minute"];
			$waiting_min=$dataValue1["Waitning_minutes"];
			}
				
			file_put_contents('pointbill.txt',$totalbill);
			mysqli_query($this->con,"update tblcabbooking SET estimated_price='$totalbill',approx_distance_charge='$per_km_charge',
			`approx_after_km`='$min_distance',`approx_waiting_charge`='$wait_charge',appox_waiting_minute='$waiting_min'  WHERE ID='$booking_id'");
			
			$pickuplatlat=$origin[0];
			$pickuplatlng=$origin[1];
			
			$this->LogStackTrackerData($booking_id,$pickuplatlat,$pickuplatlng);
			
			$this->send_sms_new($booking_id);   
			//$retrurn =  array("Status" => "Success","per_km_charge"=>$per_km_charge, "ref"=>$booking_ref['generated'],"price"=>$totalbill,"pickupTime"=>$pickupTime,"Pickupdate"=>$newdate);
			return array("Status" => "Success","id"=>$user_id,"code"=>"001","ref"=>$booking_ref['generated'],"pickupTime"=>$pickupTime,"Pickupdate"=>$newdate,"price"=>$totalbill,"total_km"=>$totalkm);
		}else{
			return array("Status" => "Unsuccess");
		}

	}
	
	//////////// Function for Intercity Booking Ends Here/////////////////////
	
	//////////// Function for Local and Airport Starts Here/////////////////////
	//FetchCalculationType($booking,$carTypes,$stateId,$distance,$travel_dis,$minimumCharge,$ignore_hrs,$ignore_km);
	public function FetchCalculationType($bookingType,$carType,$cityId,$distance,$minimumCharge,$ignore_hrs,$ignore_km,$status){
	 
	//public function FetchCalculationType(){
	
	$datam1=array();
	$data =$this->FetchBookingBill($bookingType,$carType,$cityId);
	$datam1=$data;
		
	//print_r($data);
	/***********************************Calculate Fare*********/
	
	$sqlite = "SELECT * FROM `tblmasterpackage` where Package_Id='$bookingType' and state_id='$cityId'"; 
	$rowlite = mysqli_query($this->con,$sqlite);
	$subpackage = mysqli_fetch_assoc($rowlite);
	$permntavr=40/60;
		//echo "<pre>";print_r($data);
		if($status==1){
			if($subpackage['Sub_Package_Id'] == 1){
				$ignore_hrs=0;
				$ignore_km=$data['Min_Distance'];
				$minimumCharge=$data["MinimumCharge"];
			}elseif($subpackage['Sub_Package_Id'] == 2){
				$ignore_hrs=$data["ignore_first_hrs"]; 
				$ignore_km=0;
				$minimumCharge=$data["minimum_hourly_Charge"];
			}elseif($subpackage['Sub_Package_Id'] == 3){
				$ignore_hrs=$data['ignore_first_hrs_dh'];
				$ignore_km=$data['minimum_distance_dh'];
				$minimumCharge=$data["minimum_fare_dh"];
			}elseif($subpackage['Sub_Package_Id'] == 4){
				$ignore_hrs=0;
				$ignore_km=$data['minimum_distance_dw'];
				$minimumCharge=$data["minimum_fare_dw"];
			}
		}
		
	if($bookingType=="101"){
		if($ignore_km==0){
		$distance=$ignore_hrs.' hrs';
		}else{
		$distance=$ignore_km;
		}	
		$travel_dis=$ignore_km;
		$travel_hrs=$ignore_hrs;
		//$distance="12 hrs";
		if (strpos($distance,'hrs') !== false) {
		$distance=explode(" ",$distance);
		//$totalmint=($distance[0]*60)."hrs";
		$totalmint=$distance[0]*60;
		}else{
		//$totalmint=(round($distance/$permntavr))."kms";
		$totalmint=round($distance/$permntavr);
		}
	}elseif($bookingType=="102"){
		if($distance==0){
		$distance=$ignore_km;
		}else{
		$distance=$distance;
		}
	}	
		

	//echo $totalmint; die;
	
	//$minimumCharge,$ignore_hrs,$ignore_km
	
	//echo $distance.'<br>';
	//echo $ignore_km;
	//die;
	
	if($subpackage['Sub_Package_Id'] == 1){
	if($distance > $ignore_km){	
	$ExtraKM=$distance - $ignore_km;
	$ExtraFare = $ExtraKM*$data["Per_Km_Charge"];
	$EstimatedPrice = $ExtraFare + $minimumCharge;
	}else{
	$EstimatedPrice = $minimumCharge;								
	}
	
	$datam1['Per_Km_Charge']=$data["Per_Km_Charge"];
	$datam1['Min_Distance']=$ignore_km;
	$datam1['MinimumCharge']=$minimumCharge;
	
	}
	
	
	elseif($subpackage['Sub_Package_Id'] == 2){
	$ignore_first_hours=$ignore_hrs*60; //die;
	if($totalmint > $ignore_first_hours){
	$rest_min=$totalmint-$ignore_first_hours;
	$ExtraFare=($rest_min/60)*$data["tripCharge_per_minute"];
	$EstimatedPrice = $ExtraFare + $minimumCharge;
	}else{
	$EstimatedPrice = $minimumCharge;
	}
	//// In Case per Hourly Charge 120 Rs and If car is running 40 Km Per hrs then per km charge is 120/40 is 3 Rs per Km Charge
	$datam1['Per_Km_Charge']=$data["tripCharge_per_minute"];
	$datam1['Min_Distance']=$ignore_hrs*40;
	$datam1['MinimumCharge']=$minimumCharge;
	
	}
	
	/*elseif($subpackage['Sub_Package_Id'] == 3){
		$totalmint=$travel_hrs;
	if($distance > $ignore_km){
	$ExtraKM=$distance - $ignore_km;
	$EstimatedPrice_PerKm = ($ExtraKM*$data["rate_per_km_dh"]) + $minimumCharge;
	$EstimatedPrice_PerHr = ($totalmint*$data["rate_per_hour_dh"]) + $minimumCharge;
	
		if($EstimatedPrice_PerKm > $EstimatedPrice_PerHr){
		$EstimatedPrice = $EstimatedPrice_PerKm;
		$datam1['Per_Km_Charge']=$data["rate_per_km_dh"];
		}else{
		$EstimatedPrice = $EstimatedPrice_PerHr;
		$datam1['Per_Km_Charge']=$data["rate_per_hour_dh"]/40;
		}
	}else{
	$EstimatedPrice = $minimumCharge;
	$datam1['Per_Km_Charge']=$data["rate_per_km_dh"];
	}
	$datam1['Min_Distance']=$ignore_km;
	$datam1['MinimumCharge']=$minimumCharge;
	
	}*/
	
	elseif($subpackage['Sub_Package_Id'] == 3){
	$totalmint=$travel_hrs;
	if($distance < $ignore_km){
	$distanceRate=0;
	}
	else{
		$distanceRate= ($distance - $ignore_km)*$data["rate_per_km_dh"];
	}
	if($travel_hrs > $ignore_hrs){
		$hourlyRate=0;		
	}else{
		$hourlyRate=$travel_hrs-$ignore_hrs;
		$rate_per_min=$data["rate_per_hour_dh"]/60;
		$hourlyRate=$hourlyRate*$rate_per_min;
	}
	$EstimatedPrice = $distanceRate+$hourlyRate+$minimumCharge;
	$datam1['Min_Distance']=$ignore_km;
	$datam1['MinimumCharge']=$minimumCharge;
	$datam1['Per_Km_Charge']=$data["rate_per_km_dh"];
	$datam1['Per_Hr_Charge']=$data["rate_per_hour_dh"];	
	}
		
	
	elseif($subpackage['Sub_Package_Id'] == 4){		
	if($distance > $ignore_km){
	$ExtraKM=$distance - $ignore_km;
	$ExtraFare = $ExtraKM*$data["rate_per_km_dw"];
	$EstimatedPrice = $ExtraFare + $minimumCharge;
	}
	else{
	$EstimatedPrice = $minimumCharge;								
	}
	$datam1['Per_Km_Charge']=$data["rate_per_km_dw"];
	$datam1['Min_Distance']=$ignore_km;
	$datam1['MinimumCharge']=$minimumCharge;	
	}
	
	//***************CheckPeakTime charges****************						
	/* $query = "SELECT * FROM `tblpeaktime` WHERE ('$pickupTime' BETWEEN timeFrom AND timeTo)";
	$fetch = mysqli_query($this->con,$query);
	if(mysqli_num_rows($fetch)>0){
	$vaLue=mysqli_fetch_assoc($fetch);
	$PeakChargPercent=$vaLue["peakCharges"];
	$PeakFare=$EstimatedPrice/$PeakChargPercent;
	$EstimatedPrice=$EstimatedPrice+$PeakFare;
	}	 */				
	/*********************Calculate Extra Charges*****************************************/	
	//****Basic Tax
	//$BasicTax = ($EstimatedPrice*$data['basic_tax'])/100;				
	//$totalbill = $EstimatedPrice + $BasicTax;

	////////////// Driver Share and company share calculation ////
	
	/***********************************************************************/
	
	$datam1['totalbill']=$EstimatedPrice;
	$datam1['Min_Pkg_Hrs']=$ignore_hrs;
	$datam1['Min_Pkg_KM']=$ignore_km;
	/*$datam1
	 $datam1['WaitingCharge_per_minute']=$data["WaitingCharge_per_minute"];
	$datam1['Waitning_minutes']=$data["Waitning_minutes"];
	$datam1['rate_upto_distance']=$data["rate_upto_distance"];
	$datam1['waitingfee_upto_minutes']=$data["waitingfee_upto_minutes"];
	$datam1['NightCharges']=$data["NightCharges"];
	$datam1['nightCharge_unit']=$data["nightCharge_unit"];
	$datam1['night_rate_begins']=$data["night_rate_begins"];
	$datam1['night_rate_ends']=$data["night_rate_ends"];
	$datam1['cancellation_fees']=$data['cancellation_fees'];
	$datam1['accumulated_instance']=$data['accumulated_instance'];
	$datam1['premiums']=$data['premiums'];
	$datam1['premiums_unit']=$data['premiums_unit'];
	$datam1['rounding']=$data['rounding'];
	$datam1['level']=$data['level'];
	$datam1['direction']=$data['direction'];
	$datam1['basic_tax']=$data['basic_tax'];	
	$datam1['basic_tax_type']=$data['basic_tax_type'];
	$datam1['extras']=$data['extras']; */
	
	/* if($data["nightCharge_unit"]=='Rs'){
	$datam1['NightCharges']=$data["NightCharges"];
	}else{
	$datam1['NightCharges']=($totalbill*$data["NightCharges"])/100;	
	} */
	//print_r($datam1); die;
	
	return $datam1;	
	}
	//////////// Function for Local and Airport Ends Here/////////////////////
	
	public function calculateExtraCharges($totalbill,$extras){
	//public function calculateExtraCharges(){
		//$totalbill=200;
		//$extras='["Airport Entry Fee_10_%","Airport Parking_100_Rs"]';
		//$extras='["Airport Entry Fee_20_%","Airport Entry Fee_30_%"]';
		//$extras='["Airport Entry Fee_20_Rs","Airport Entry Fee_30_Rs"]';
		$extrasArr = json_decode($extras);
		//echo "<pre>";print_r($extrasArr); //die;
		$totalbillValue1=0;
		for($i=0;$i<count($extrasArr);$i++){
			$totalbillValue=0;
		$extrasArr_key = explode("_",$extrasArr[$i]);
			 if($extrasArr_key[2]=="Rs"){
				 $totalbillValue=$extrasArr_key[1];				
			 }elseif($extrasArr_key[2]=="%"){
				 $totalbillValue = ($totalbill * $extrasArr_key[1])/100;
			 }
			  $totalbillValue1=$totalbillValue1+$totalbillValue;
		}
			 // $totalbill=$totalbill+$totalbillValue1;
		return $totalbillValue1; 
		
	}
	
	
	//////////// Function to Calculate PeakTime Charges Starts Here/////////////////////
	
	public function calculatePeakTimeCharges($totalbill,$pickupTime){
		//public function calculatePeakTimeCharges(){
		//$pickupTime="18:10:00";
		//$totalbill=100;
		$Fare=0;
		$query = "SELECT * FROM `tblpeaktime` WHERE ('$pickupTime' BETWEEN timeFrom AND timeTo)"; 
		$fetch = mysqli_query($this->con,$query);
		if(mysqli_num_rows($fetch)>0){
		$vaLue=mysqli_fetch_assoc($fetch);
		$PeakChargPercent=$vaLue["peakCharges"];
		//$PeakFare=$totalbill/$PeakChargPercent;
		$Fare=($totalbill * $PeakChargPercent)/100;
		}
		$PeakFare=array();
		$PeakFare['peakcharge']=$Fare;
		$PeakFare['peaktimeFrom']=$vaLue["timeFrom"];
		$PeakFare['peaktimeTo']=$vaLue["timeTo"];
		$PeakFare['peakpercentage']=$vaLue["peakCharges"];
		return $PeakFare;	
	}
	
	//////////// Function to Calculate PeakTime Charges Ends Here/////////////////////
	
	/////////// Function to Calculate Night Charges Starts Here/////////////////////
	public function calculateNigthCharges($pickupTime,$nightRateBegins,$nigthRateEnds,$nightChargeUnit,$NightCharges,$totalbill){
	//$NightCharges=$this->calculateNigthCharges($pickupTime,$datam2['night_rate_begins'],$datam2['night_rate_ends'],$datam2['nightCharge_unit'],$datam2['NightCharges'],$totalbill);
		//echo "Mohit"; //die;
		//return "mohit";
		/*echo $pickupTime='14:14:00';
		echo ' Pt<br>';
		echo $nightRateBegins='22:00:00';
		echo ' NRB<br>';
		echo $nigthRateEnds='23:00:00';
		echo ' NRE<br>';
		echo $nightChargeUnit='Rs';
		echo ' NCU<br>';
		echo $NightCharges='1000';
		echo ' NC<br>';
		echo $totalbill='10';
		echo ' TB<br>';*/
	
		
		if(strtotime($pickupTime) <= strtotime($nightRateBegins) && strtotime($pickupTime) < strtotime($nigthRateEnds)){
		 $nightRateEnds=strtotime($nigthRateEnds);
		 	if(strtotime($pickupTime)< $nightRateEnds){
			 	if($nightChargeUnit == 'Rs'){
					$Night_Charges = $NightCharges;
				}else{
					$Night_Charges = ($totalbill * $NightCharges)/100;
				}
		 	}
		 }elseif(strtotime($pickupTime) >= strtotime($nightRateBegins) && strtotime($pickupTime) > strtotime($nigthRateEnds)){
		 	$nightRateEnds = strtotime($nigthRateEnds)+60*60*24;
		 	if($nightRateEnds>strtotime($pickupTime)){
		 	if($nightChargeUnit == 'Rs'){
					$Night_Charges = $NightCharges;
				}else{
					$Night_Charges = ($totalbill * $NightCharges)/100;
				}
		 		
		 	}
			 	
		 	
		 }
		
		if($Night_Charges=="")
		{
			$Night_Charges=0;
		}
		return $Night_Charges;
	}
	
	//////////// Function to Calculate Night Charges Ends Here/////////////////////
	
	//////////// API for Local Booking used in WEB only Starts Here/////////////////////
	
	public function localBookingFare(){

		$booking = $_REQUEST['bookingType'];
		$cablocalin=$_REQUEST['localCabInData'];
		$cablocalfor=$_REQUEST['localCabForData'];
		$cabpickuparea = $_REQUEST['pickup'];
		$pickupTimestae = $_REQUEST['picktime'];
		$cablocaladdress=$_REQUEST['localAddressData'];
		$pickuplatlng = explode(',', $_REQUEST['picklatlong']);
		@$picklat = $pickuplatlng[0];
		@$picklang = $pickuplatlng[1];
				
		
		file_put_contents("local.txt",print_r($_REQUEST,true));
		$distance = '';
		$find_address=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($cablocaladdress));
		$enc3=json_decode($find_address);
		$picklat=$enc3->results[0]->geometry->location->lat;
		$picklang=$enc3->results[0]->geometry->location->lng;
		$is_pickup=mysqli_num_rows(mysqli_query($this->con,"SELECT * FROM rt_locations WHERE area='$cabpickuparea'"));							        
		if($is_pickup==0){
			$find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($cabpickuparea));
			file_put_contents("json.txt",$find_pickup); 					
			$enc=json_decode($find_pickup);
			$lat=$enc->results[0]->geometry->location->lat;
			$long=$enc->results[0]->geometry->location->lng;			                        
			foreach($enc->results[0]->address_components as $v){
				if($v->types[0]=="locality"){							
					$city=$v->long_name;
				}
				if($v->types[0]=="administrative_area_level_2"){							
					$zone=$v->long_name;
				}        
				if($v->types[0]=="country"){							
					$country=$v->long_name;
				}       
				if($v->types[0]=="administrative_area_level_1"){							
					$state=$v->long_name;
				}
			}
			mysqli_free_result($result);   
			mysqli_next_result($this->con);
			mysqli_query($this->con,"INSERT INTO rt_locations(`area`,`city`,`lat`,`lon`,`zone`,`country`,`state`)"
                                        . " VALUES('$cabpickuparea','$city','$lat','$long','$zone','$country','$state')");
		}		
		$date = date("Y-m-d H:i:s");
		if($pickupTimestae=="Now"){
			$date1= date("Y-m-d H:i", strtotime("+30 minutes"));
			//$newdate =date("d-m-Y H:i", strtotime("+30 minutes"));
			$newdate = explode(" ",$date1);
			$cablocaldate = $newdate[0];
			
			$pickupTime=$newdate[1];
			$date_print=$date1;
			
			
		}else{
			$cablocaldate=$_REQUEST['datepickerData'];
			$cablocaltimeH=trim($_REQUEST['localTimeH']);
			$localTimeS=trim($_REQUEST['localTimeS']);
			$pickupTime = "$cablocaltimeH".":$localTimeS:00";
			$date_print=$cablocaldate." ".$pickupTime;
			$newdate=date("d-m-Y H:i",strtotime($date_print));
			
		}
			 
		//********************************GET subpackage Info***********************////////////////////////////////******
		 $query = "SELECT * FROM `tblcity` WHERE name='$cablocalin'";
		 $rowDATA =mysqli_fetch_assoc(mysqli_query($this->con,$query));	
		 $stateId=$rowDATA["state"];
		 
		 $query1 = "SELECT state FROM `tblstates` WHERE id='$stateId'";
		 $rowDATA1 =mysqli_fetch_assoc(mysqli_query($this->con,$query1));	
		 $stateName=$rowDATA1["state"];
		 //echo $pickupTime; die;
		 $datam2=array();
		 for($i=1;$i<=3;$i++){
		 $datam2=$this->FetchLocalPackageData($booking,$cablocalfor,$stateId,$i);
		 
		 $totalbill=$datam2['totalbill'];
		 
		$PeakFare	=	$this->calculatePeakTimeCharges($totalbill,$pickupTime);
		$totalbill	=	$totalbill + $PeakFare['peakcharge'];
		 
		 //// Calculation for NightCharges Starts here /////
		 /*if(strtotime($pickupTime) >= strtotime($datam2['night_rate_begins']) and strtotime($pickupTime) < strtotime($datam2['night_rate_ends'])){
			if($datam2['nightCharge_unit'] == 'Rs'){
				$NightCharges = $datam2['NightCharges'];
			}else{
				$NightCharges = ($totalbill * $datam2['NightCharges'])/100;
			}
		}*/
		
		$NightCharges=$this->calculateNigthCharges($pickupTime,$datam2['night_rate_begins'],$datam2['night_rate_ends'],$datam2['nightCharge_unit'],$datam2['NightCharges'],$totalbill);
		
		
		//// Calculation for NightCharges Ends here /////
		//////// IF NIGHT CHARGES IS INCLUDED IN TOTAL BILL THEN Start HERE ///////
		
		$totalbill	=	$totalbill + $NightCharges;
		$extraPrice	=	$this->calculateExtraCharges($totalbill,$datam2['extras']);
		$totalbill	=	$totalbill + $extraPrice;
		
		
		$BasicTax = (($totalbill) * $datam2['basic_tax'])/100;
		$totalbill = $totalbill + $BasicTax; 
		$totalbill	=	round($totalbill);
		
		if($i==1){
			$billTypeEconomy	= "Economy";
			$totalbillEconomy	=	$totalbill;
		}
		elseif($i==2){
			$billTypeSedan	= "Sedan";
			$totalbillSedan	=	$totalbill;
		}
		elseif($i==3){
			$billTypePrime	= "Prime";
			$totalbillPrime	=	$totalbill;
		}
		
		}
		
		

		return array("status"=>'true',"billTypeEconomy"=>$billTypeEconomy,"totalbillEconomy"=>$totalbillEconomy,"billTypeSedan"=>$billTypeSedan,"totalbillSedan"=>$totalbillSedan,"billTypePrime"=>$billTypePrime,"totalbillPrime"=>$totalbillPrime); 

	}

//////////// API for Local Booking used in WEB only Ends Here/////////////////////


/////// API for Point TO Point Booking used in WEB only Starts Here /////////////////
	
	public function pointBookingFare(){	
           
		$bookingType = $_POST['bookingType'];
		$pointCabin=$_POST['pointcabindata'];
		$pointPickuparea=$_POST['pointpickupareadata'];                        
		$is_pickup=mysqli_num_rows(mysqli_query($this->con,"SELECT * FROM rt_locations WHERE area='$pointPickuparea'"));
		$pointDroparea=$_POST['pointdropareadata'];
		$is_drop=mysqli_num_rows(mysqli_query($this->con,"SELECT * FROM rt_locations WHERE area='$pointDroparea'"));
		$pointAddress=$_POST['pointaddressdata'];
		$pointdate=$_POST['pointdate'];
		$cablocalfor="";	
		$newdate = date("d-m-Y",strtotime($pointdate));		
		if($pointdate==""){
			$pointdate= date("Y-m-d H:i:S", strtotime("+30 minutes"));
			$pickup = explode(" ", $pointdate);
			$pointdate =  $pickup[0];
			$pickupTime = $pickup[1];
			$newdate = date("d-m-Y",strtotime("+30 minutes"));
		}
		if($_POST['pointH']==""){
		}else{
			$pointH = trim($_POST['pointH']);
			$pointT = trim($_POST['pointS']);
			$pickupTime = $pointH.":".$pointT.":00";
		}

		$origin=explode(',',$_POST['origin']);
		$destiny=explode(',',$_POST['destiny']); 
		
		$find_address=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($pointAddress));
		$enc3=json_decode($find_address);
		if($enc3->status == 'OK'){
			$origin[0]=$enc3->results[0]->geometry->location->lat;
			$origin[1]=$enc3->results[0]->geometry->location->lng;
			$lat1 = $origin[0];
			$long1 = $origin[1];
		}else{
			return array('Status'=>'false', 'msg'=>'Please enter valid Pickup Address ');
		}
		if($is_pickup==0){
			$find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($pointPickuparea));
			file_put_contents("json.txt",$find_pickup); 					
			$enc=json_decode($find_pickup);
			if($enc->status == 'OK'){
				$lat=$enc->results[0]->geometry->location->lat;
				$long=$enc->results[0]->geometry->location->lng;
				$area="";
				foreach($enc->results[0]->address_components as $v){
					if($v->types[0]=="locality")						
						{							
							$area=$v->long_name;
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
				}
			mysqli_query($this->con,"INSERT INTO rt_locations(`area`,`city`,`lat`,`lon`,`zone`,`country`,`state`)"
                                        . " VALUES('$pointPickuparea','$area','$lat','$long','$zone','$country','$state')");
			}else{
				return array('Status'=>'false', 'msg'=>'Please enter valid Pickup Area');
			}
		}
		if($is_drop==0){
			$find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($pointDroparea));
			$enc2=json_decode($find_pickup);
			if($enc2->status == 'OK'){
				$lat=$enc2->results[0]->geometry->location->lat;
				$long=$enc2->results[0]->geometry->location->lng;
				$destiny[0]=$lat;
				$destiny[1]=$long;
				$area="";                                
				foreach($enc2->results[0]->address_components as $v){
					if($v->types[0]=="locality")						
						{							
							$area=$v->long_name;
						}
                                        if($v->types[0]=="administrative_area_level_2")						
						{							
							@$zone=$v->long_name;
						}        
                                         if($v->types[0]=="country")						
						{							
							$country=$v->long_name;
						}       
					if($v->types[0]=="administrative_area_level_1")						
						{							
							$state=$v->long_name;
						}
				}
				mysqli_query($this->con,"INSERT INTO rt_locations(`area`,`city`,`lat`,`lon`,`zone`,`country`,`state`)"
                                        . " VALUES('$pointDroparea','$area','$lat','$long','$zone','$country','$state')");
			}else{
				return array('Status'=>'false', 'msg'=>'Please enter valid Drop Area');
			}
		}
		
		/* $API_MAP = "https://maps.googleapis.com/maps/api/directions/json?origin=".$origin[0].','.$origin[1]."&destination=".$destiny[0].','.$destiny[1]."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584";
		$data= file_get_contents($API_MAP);
		$enc=json_decode($data);
		if($enc->status == 'OK'){
			$enc2=$enc->routes[0];
			$distance=round((($enc2->legs[0]->distance->value)/1000),1);
			$distance=round($distance);
		}else{
			return array('Status'=>'false', 'msg'=>'Please enter valid Address');
		} */

	
	$data= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$origin[0].','.$origin[1]."&destination=".$destiny[0].','.$destiny[1]."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584");
	$enc=json_decode($data);			
	if($enc->status == 'OK'){
		$enc2=$enc->routes[0];
		$distance2=round((($enc2->legs[0]->distance->value)/1000),1);
		$distance=round($distance2);
		$query = "SELECT * FROM `tblcity` WHERE name='$pointCabin'";
		$rowDATA =mysqli_fetch_assoc(mysqli_query($this->con,$query));	
		$cityId=$rowDATA["state"];

		$pickupTime=str_replace("th","00",$pickupTime);		
				
		$datam2=array();
		 for($i=1;$i<=3;$i++){
		 $datam2=$this->FetchPointToPointData($bookingType,$i,$cityId,$distance,$cablocalfor);
		 
		 $totalbill=$datam2['totalbill'];
		 
		$PeakFare	=	$this->calculatePeakTimeCharges($totalbill,$pickupTime);
		$totalbill	=	$totalbill + $PeakFare['peakcharge'];
		 
		 //// Calculation for NightCharges Starts here /////
		/*if(strtotime($pickupTime) >= strtotime($datam2['night_rate_begins']) and strtotime($pickupTime) < strtotime($datam2['night_rate_ends'])){
			if($datam2['nightCharge_unit'] == 'Rs'){
				$NightCharges = $datam2['NightCharges'];
			}else{
				$NightCharges = ($totalbill * $datam2['NightCharges'])/100;
			}
		}*/
		
		$NightCharges=$this->calculateNigthCharges($pickupTime,$datam2['night_rate_begins'],$datam2['night_rate_ends'],$datam2['nightCharge_unit'],$datam2['NightCharges'],$totalbill);
		
		
		
		//// Calculation for NightCharges Ends here /////
		//////// IF NIGHT CHARGES IS INCLUDED IN TOTAL BILL THEN Start HERE ///////
		
		$totalbill	=	$totalbill + $NightCharges;
		$extraPrice	=	$this->calculateExtraCharges($totalbill,$datam2['extras']);
		$totalbill	=	$totalbill + $extraPrice;
		
		
		$BasicTax = (($totalbill) * $datam2['basic_tax'])/100;
		$totalbill = $totalbill + $BasicTax; 
		$totalbill	=	round($totalbill);
		
		if($i==1){
			$pointbillTypeEconomy	= "Economy";
			$pointtotalbillEconomy	=	$totalbill;
		}
		elseif($i==2){
			$pointbillTypeSedan	= "Sedan";
			$pointtotalbillSedan	=	$totalbill;
		}
		elseif($i==3){
			$pointbillTypePrime	= "Prime";
			$pointtotalbillPrime	=	$totalbill;
		}
		
		}

		return array("status"=>'true',"pointbillTypeEconomy"=>$pointbillTypeEconomy,"pointtotalbillEconomy"=>$pointtotalbillEconomy,"pointbillTypeSedan"=>$pointbillTypeSedan,"pointtotalbillSedan"=>$pointtotalbillSedan,"pointbillTypePrime"=>$pointbillTypePrime,"pointtotalbillPrime"=>$pointtotalbillPrime); 
				
			}else{
				return array('Status'=>'false', 'msg'=>'Please enter valid Address');
			}
		
	}
	
/////// API for Point TO Point Booking used in WEB only Ends Here /////////////////

	///// API used for Web Airport Booking Starts Here /////////////

	public function airportBookingFare(){
	/// For going to Airport ///
	$status = 0;
	if($_POST['airDropLocation']!=''){
	/// For coming from Airport ///
	$status = 1;
	}
	$bookingType = $_POST['bookingType'];
	$airAirportData1=explode('-',$_POST['airairport']);
	$airAirportData=$airAirportData1[0];
	$StateName=$airAirportData1[1];
	$airPickuplocationData=$_POST['airpickuplocation'];
	$airDropLocation=$_POST['airDropLocation'];
	$airLandmarkData=$_POST['airlandmark'];
	$airpickupAddressData=$_POST['airpickupaddress'];
	$airDropAddress=$_POST['airDropAddress'];
	$airPickupDate=$_POST['airpickupdate'];
	$airpickuptimeH=$_POST['airpickuptimeH'];
	$airpickuptimeS=$_POST['airpickuptimeS'];
	
	$newdate = date("d-m-Y",strtotime($airPickupDate));	
		if($_POST['airpickuptimeH']==""){
		}else{
			$airpickuptimeH = trim($_POST['airpickuptimeH']);
			$airpickuptimeT = trim($_POST['airpickuptimeS']);
			$pickupTime = $airpickuptimeH.":".$airpickuptimeT.":00";
		}
		if($airPickupDate==""){
			$airPickupDate= date("Y-m-d H:i:S", strtotime("+30 minutes"));
			$pickup = explode(" ", $airPickupDate);
			$airPickupDate =  $pickup[0];
			$pickupTime = $pickup[1];			
			$newdate = date("d-m-Y",strtotime("+30 minutes"));
		}
		

	if($status==0){
	/////$airpickupAddressData=121 DB Gupta Road Karol Bagh new Delhi ///
	$sql6="SELECT * FROM rt_locations where `area` ='$airpickupAddressData'"; //die;
	$result6 = mysqli_query($this->con,$sql6);
	$row6 = mysqli_fetch_assoc($result6);
	$num6 = mysqli_num_rows($result6);
	if($num6 ==0){
	
		$find_address=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($airpickupAddressData));
		$enc3=json_decode($find_address);
		$origin0=$enc3->results[0]->geometry->location->lat;
		$origin1=$enc3->results[0]->geometry->location->lng;
	
		foreach($enc3->results[0]->address_components as $v)
		{
		if($v->types[0]=="locality")						
		{							
		$area=$v->long_name;
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
		}
		
		mysqli_query($this->con,"INSERT INTO rt_locations(`area`,`city`,`lat`,`lon`,`zone`,`country`,`state`)"
	. " VALUES('$airpickupAddressData','$area','$origin0','$origin1','$zone','$country','$state')");
	}else{
		$origin0=$row6['lat'];
		$origin1=$row6['lon'];
		$area=$row6['city'];
	}
	
	$sql2="SELECT lat,tblairportpackage.`long` FROM tblairportpackage where `pkgName` ='$airAirportData'"; //die;	
	$result1 = mysqli_query($this->con,$sql2);
	$row1 = mysqli_fetch_assoc($result1);
	$num1 = mysqli_num_rows($result1);
	
	$destiny0=$row1['lat'];
	$destiny1=$row1['long']; //die;
	
	}
	elseif($status==1){
		
	/////$airDropAddress=121 DB Gupta Road Karol Bagh new Delhi ///
	$sql8="SELECT * FROM rt_locations where `area` ='$airDropAddress'";
	$result7 = mysqli_query($this->con,$sql8);
	$row7 = mysqli_fetch_assoc($result7);
	$num7 = mysqli_num_rows($result7);
	if($num7 ==0){
	
	$find_address=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($airDropAddress));
	$enc4=json_decode($find_address);
	$origin0=$enc4->results[0]->geometry->location->lat;
	$origin1=$enc4->results[0]->geometry->location->lng;
	
	foreach($enc4->results[0]->address_components as $v)
	{
	if($v->types[0]=="locality")						
	{							
	$area=$v->long_name;
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
	}
	mysqli_query($this->con,"INSERT INTO rt_locations(`area`,`city`,`lat`,`lon`,`zone`,`country`,`state`)"
	. " VALUES('$airDropAddress','$area','$origin0','$origin1','$zone','$country','$state')");
	}else{
	$origin0=$row7['lat'];
	$origin1=$row7['lon'];
	$area=$row7['city']; 
	}
	
	$sql9="SELECT lat,tblairportpackage.`long` FROM tblairportpackage where `pkgName` ='$airAirportData'";	
	$result2 = mysqli_query($this->con,$sql9);
	$row2 = mysqli_fetch_assoc($result2);
	$num2 = mysqli_num_rows($result2);
	
	$destiny0=$row2['lat'];
	$destiny1=$row2['long'];
	
	}
	
	///////////// Calculate Distance Between Source and desination using Latitude and Longitude ///
	
	$API_MAP = "https://maps.googleapis.com/maps/api/directions/json?origin=".$origin0.','.$origin1."&destination=".$destiny0.','.$destiny1."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584";
	$data1= file_get_contents($API_MAP);	 
	$enc=json_decode($data1);
	if($enc->status == 'OK'){
	$enc2=$enc->routes[0];
	$distance=round((($enc2->legs[0]->distance->value)/1000),1);
	}

	//Calculate Distance Between Source and desination using Latitude and Longitude From tblairport Address//
	
	if($status==0){
	$sql_address="SELECT `Km` FROM `tblairportaddress` WHERE `Address`='$airPickuplocationData' and `Fix_Point`='$airAirportData' LIMIT 1"; 
	$result8 = mysqli_query($this->con,$sql_address);
	$Address=$airPickuplocationData;
	}else if($status==1){
	$sql_address="SELECT `Km` FROM `tblairportaddress` WHERE `Address`='$airDropLocation' and `Fix_Point`='$airAirportData' LIMIT 1"; 
	$result8 = mysqli_query($this->con,$sql_address);
	$Address=$airDropLocation;
	}
	$row8 = mysqli_fetch_assoc($result8);
	$distanceAirport=$row8['Km'];
	$sql1="select id,state from tblstates where state='$StateName' limit 1";
	$result = mysqli_query($this->con, $sql1);
	$val = mysqli_fetch_object($result);
	$stateId=$val->id;
	$cabairportfor=$airAirportData;
	
	if($distance<$distanceAirport){
	$distance=round($distanceAirport);
	}else{
	$distance=round($distance);
	}
	
	$pickupTime=str_replace("th","00",$pickupTime);
	
	$datam2=array();
	//echo $distance;
	for($i=1;$i<=3;$i++){
	$datam2=$this->FetchAirportDistanceFare($bookingType,$i,$stateId,$distance,$cabairportfor,$Address);

	$totalbill=$datam2['totalbill'];
	
	$PeakFare	=	$this->calculatePeakTimeCharges($totalbill,$pickupTime);
	$totalbill	=	$totalbill + $PeakFare['peakcharge'];

	//// Calculation for NightCharges Starts here /////
	/*if(strtotime($pickupTime) >= strtotime($datam2['night_rate_begins']) and strtotime($pickupTime) < strtotime($datam2['night_rate_ends'])){
	if($datam2['nightCharge_unit'] == 'Rs'){
	$NightCharges = $datam2['NightCharges'];
	}else{
	$NightCharges = ($totalbill * $datam2['NightCharges'])/100;
	}
	}*/
	
	$NightCharges=$this->calculateNigthCharges($pickupTime,$datam2['night_rate_begins'],$datam2['night_rate_ends'],$datam2['nightCharge_unit'],$datam2['NightCharges'],$totalbill);
	
	//// Calculation for NightCharges Ends here /////
	//////// IF NIGHT CHARGES IS INCLUDED IN TOTAL BILL THEN Start HERE ///////

	$totalbill	=	$totalbill + $NightCharges;
	$extraPrice	=	$this->calculateExtraCharges($totalbill,$datam2['extras']);
	$totalbill	=	$totalbill + $extraPrice;

	
	$BasicTax = (($totalbill) * $datam2['basic_tax'])/100;
	$totalbill = $totalbill + $BasicTax; 
	$totalbill	=	round($totalbill);

	if($i==1){
	$airbillTypeEconomy	= "Economy";
	$airtotalbillEconomy	=	$totalbill;
	}
	elseif($i==2){
	$airbillTypeSedan	= "Sedan";
	$airtotalbillSedan	=	$totalbill;
	}
	elseif($i==3){
	$airbillTypePrime	= "Prime";
	$airtotalbillPrime	=	$totalbill;
	}

	}

	return array("status"=>'true',"airbillTypeEconomy"=>$airbillTypeEconomy,"airtotalbillEconomy"=>$airtotalbillEconomy,"airbillTypeSedan"=>$airbillTypeSedan,"airtotalbillSedan"=>$airtotalbillSedan,"airbillTypePrime"=>$airbillTypePrime,"airtotalbillPrime"=>$airtotalbillPrime); 
	}
	
	///// API used for Web Airport Booking Ends Here /////////////

	///// API Booking Reschedule used for Call Center Starts Here //////////
	
	
	public function BookingReshedule()
	{	//callerid;OrderNo;CallerName;ReschuduleDate;ReschuduleTime;Paddress;plat;plong;Daddress;Reason;Remark;dlat;dlong;ReasonText;RemarkText
	//$Value="9891735121;naveenKumar;15:07/2015;16:07/2015;HC15075805;Picktext;Droptext;Resontext;null or text;Plat;plong;dlat;dlong;resontext ;reason remarks";
	


	$Value 			=	$_REQUEST['Value'];
	$val			=	explode(';',$Value);
	//CALL sp_booking_reshedule()
	$CallerId		=	$val[0];
	$OrderNo		=	$val[1];
	$CallerName		=	$val[2];	
	$ReschuduleDate =	$val[3];
	$ReschuduleTime	=	$val[4];
	$Paddress		=	$val[5];
	$plat			=	$val[6];
	$plong			=	$val[7];
	$Daddress		=	$val[8];
	$Reason			=	$val[9];
	$Remark			=	$val[10];	
	$dlat			=	$val[11];
	$dlong			=	$val[12];
	$ReasonText		=	$val[13];
	$RemarkText		=	$val[14];
	///// using get booking detail for reshedule history
	//$data=array();
	$data			=	$this->GetBookingDetail($OrderNo);		// order is making as booking reference  
	//print_r($data); die;
	$id				=	$data['id'];
	$BookingType	=	$data['BookingType'];
	$Pickup			=	$data['Pickup'];
	$old_cabstatus	=	$data['cabstatus'];
	$old_BookingDate=	$data['BookingDate'];
	$old_PickupDate	=	$data['PickupDate'];
	$old_PickupTime	=	$data['PickupTime'];
	$distance		=	$data['EstimatedDistance'];
	//$new_bookingDate= NOW();
	$new_PickupDate	=	$ReschuduleDate;
	$new_PickupTime	=	$ReschuduleTime;
	//$new_cabstatus=$ReschuduleTime;
	
	//// New Variables ////
	$estimated_price	=	$data['estimated_price'];
	$Package_State		=	$data['Package_State'];
	$carType			=	$data['carType'];
	
	$sql1="select id from tblstates where state='$Package_State' limit 1";
	$result = mysqli_query($this->con, $sql1);
	$val = mysqli_fetch_object($result);
	$stateId=$val->id;
	
	//// New Variables ////
	////// For Local Booking Starts Here //////
	
	if($BookingType=="101"){
	$cablocalfor	=	$data['local_subpackage'];
	$datam2			=	$this->FetchLocalPackageData($BookingType,$cablocalfor,$stateId,$carType);
	}
	////// For Local Booking Ends Here //////
	
	/////// For Point To Point Booking Starts Here ///////
	elseif($BookingType=="102"){
	$cablocalfor	=	"";
	$datam2			=	$this->FetchPointToPointData($BookingType,$carType,$stateId,$distance,$cablocalfor);
	}
	/////// For Point To Point Booking Ends Here ///////
	
	/////// For Point To Point Booking Starts Here ///////
	elseif($BookingType=="103"){
	$PickAreaName		=	$data['PickUpArea']; 
	$DropAreaName		=	$data['DropArea'];
	$sqlAir	="SELECT * FROM `tblairportaddress` WHERE `Fix_Point`='$PickAreaName' LIMIT 1";
	$sqlAir	=mysqli_query($this->con,$sqlAir);	
	$row=mysqli_fetch_assoc($sqlAir);
	$num_rows = mysqli_num_rows($sqlAir);
	if($num_rows>0){
	$fixpoint		=	$PickAreaName;
	$address		=	$DropAreaName;
	}
	else{
	$address		=	$PickAreaName;
	$fixpoint		=	$DropAreaName;
	}
	$datam2			=	$this->FetchAirportDistanceFare($BookingType,$carType,$stateId,$distance,$fixpoint,$address);
	}
	/////// For Point To Point Booking Ends Here ///////
	
	//echo "<pre>";print_r($datam2); die;
	
	$totalbill=round($datam2['totalbill']);
	
	$PeakFare			=	$this->calculatePeakTimeCharges($totalbill,$new_PickupTime);
	$totalbill			=	$totalbill + $PeakFare['peakcharge'];
		 
	 /// Check it
	 //// Calculation for NightCharges Starts here /////
	 if(strtotime($new_PickupTime) >= strtotime($datam2['night_rate_begins']) and strtotime($new_PickupTime) < strtotime($datam2['night_rate_ends'])){
		if($datam2['nightCharge_unit'] == 'Rs'){
			$NightCharges = $datam2['NightCharges'];
		}else{
			$NightCharges = ($totalbill * $datam2['NightCharges'])/100;
		}
	}
		
	//// Calculation for NightCharges Ends here /////
	
	$totalbill			=	$totalbill + $NightCharges;
	$extraPrice			=	$this->calculateExtraCharges($totalbill,$datam2['extras']);
	$totalbill			=	$totalbill + $extraPrice;

	
	$BasicTax			=	(($totalbill) * $datam2['basic_tax'])/100;
	$totalbill			=	$totalbill + $BasicTax; 
	$totalbill			=	round($totalbill);

	$Min_Pkg_Hrs		=	$datam2['Min_Pkg_Hrs'];
	$Min_Pkg_KM			=	$datam2['Min_Pkg_KM'];
	$per_km_charge		=	$datam2["Per_Km_Charge"];
	$min_distance		=	$datam2["Min_Distance"];
	$wait_charge		=	$datam2["WaitingCharge_per_minute"];
	$waiting_min		=	$datam2["Waitning_minutes"];
	
	$basic_tax			=	$datam2['basic_tax'];
	$basic_tax_type		=	$datam2['basic_tax_type'];
	$basic_tax_price	=	round($BasicTax);
	$rounding			=	$datam2['rounding'];
	$level				=	$datam2['level'];
	$direction			=	$datam2['direction'];
	$nightcharge_unit	=	$datam2['nightCharge_unit'];
	$nightcharge		=	$datam2['NightCharges'];
	$nightcharge_price	=	round($NightCharges);
	$night_rate_begins	=	$datam2['night_rate_begins'];
	$night_rate_ends	=	$datam2['night_rate_ends'];
	$premiums			=	$datam2['premiums'];
	$premiums_unit		=	$datam2['premiums_unit'];
	$extraPrice			=	round($extraPrice);
	$peakTimePrice		=	round($PeakFare['peakcharge']);
	$peaktimeFrom		=	$PeakFare['peaktimeFrom'];
	$peaktimeTo			=	$PeakFare['peaktimeTo'];
	$peaktimepercentage	=	$PeakFare['peakpercentage'];
	$extras				=	str_replace(array('[',']'),'',$datam2['extras']);
		
	///////////////////////////
	$qry1 ="insert into  tbl_booking_resec_reassign (booking_id,booking_type,old_driver_id,old_pick_time,old_pick_date,new_pick_time,new_pick_date,old_booking_date,new_booking_date,old_estimated_price,new_estimated_price)
	values ('$id','$BookingType' ,'$Pickup','$old_PickupTime','$old_PickupDate' ,'$new_PickupTime','$new_PickupDate','$old_BookingDate',NOW(),'$estimated_price','$totalbill')";
	$fetch2=mysqli_query($this->con,$qry1);

	$qry ="insert into  tblagent_work_history (AgentID,CallerID,BookingID,Actiontype,tblagent_work_history.Date,ActionDesc)
	values ((select CSR_ID from tblcabbooking where booking_reference='$OrderNo'),'$CallerId' ,'$OrderNo','Reschedule',Now(),'Booking Reschedule done by Call Center agent' )";
	$fetch1=mysqli_query($this->con,$qry);

	$query="update tblcabbooking set UserName='$CallerName', PickupDate='$ReschuduleDate', PickupTime='$ReschuduleTime', PickupArea='$Paddress', PickupLatitude='$plat',PickupLongtitude='$plong', DropArea='$Daddress', DestinationLatitude='$dlat', DestinationLongtitude='$dlong', Remark='$RemarkText', StatusC =55, Reason='$ReasonText', CabFor='$Min_Pkg_Hrs', estimated_price='$totalbill', approx_distance_charge='$per_km_charge', approx_after_km='$min_distance', approx_waiting_charge='$wait_charge',appox_waiting_minute='$waiting_min', min_Distance='$Min_Pkg_KM', EstimatedDistance='$Min_Pkg_KM', basic_tax='$basic_tax',basic_tax_type='$basic_tax_type', basic_tax_price='$basic_tax_price', rounding='$rounding', level='$level',direction='$direction', nightcharge_unit='$nightcharge_unit', nightcharge='$nightcharge', nightcharge_price='$nightcharge_price', night_rate_begins='$night_rate_begins', night_rate_ends='$night_rate_ends', premiums='$premiums',premiums_unit='$premiums_unit', extras='$extras', extraPrice='$extraPrice',peakTimePrice='$peakTimePrice',peaktimeFrom='$peaktimeFrom',peaktimeTo='$peaktimeTo',peaktimepercentage='$peaktimepercentage'  where booking_reference='$OrderNo'";
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
	
	
	public function send_sms_global($msg_sku='',$data='',$mobile='')
	{
		  
			$msg_query=mysqli_fetch_array(mysqli_query($this->con,"SELECT * FROM tbl_sms_template WHERE msg_sku='$msg_sku'"));
		 
		$array=explode('<variable>',$msg_query['message']);
		// $array[0]=$array[0].$client;
		// $array[1]=$array[1].$booking_ref;
		// $array[2]=$array[2].$pickup_time;
		// $array[3]=$array[3].$fetch['estimated_price'];
		// $array[4]=$array[4].$minimum_charge;
		// $array[5]=$array[5].$minimum_distance;
		// $array[6]=$array[6].$charge;
		// $array[7]=$array[7].$fetch['WaitingCharge_per_minute'];

		for ($i=0; $i <=count($data); $i++) { 
			 $array[$i]=$array[$i].$data[$i];
		}

		$text=  urlencode(implode("",$array));	
		// file_put_contents("mssg.txt",$text);
		$url="http://push3.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$mobile&from=Helocb&dlrreq=true&text=$text&alert=1";
		return $url;
		// file_get_contents($url);
	}
	
	public function reschdbkingjobs()
	{
		  
		$newdate = date("Y-m-d",strtotime($_POST['time']));
		$newtime = date("H:i:s",strtotime($_POST['time']));
		$bookinid = $_POST['id'];
		$booksql = "select Status,MobileNo,booking_reference,UserName,PickUpDate,PickUpTime,PickupAddress,PickupLatitude,PickupLongtitude,DropAddress,DestinationLatitude,DestinationLatitude,DestinationLongtitude from tblcabbooking where ID=$bookinid";
 	
		$bookres = $this->con->query($booksql)->fetch_object(); 
 	if(($bookres->Status<=5) && !in_array($bookres->Status, array(6,7,8,9,10,11,12,13,14,15))){
		
		unset($bookres->Status);
		$bookres->PickUpDate = $newdate;
		$bookres->PickUpTime = $newtime;

		foreach ($bookres as $value) {

			$res[] = $value;
		}

		array_splice($res, 9, 0, array('Rescheduled By Admin',''));

		$res[13] ='Rescheduled By Admin';
		$res[14] ='';

		foreach ($res as $val) {

			$string.= $val.";";
		}
		// $keys2 = array_splice($keys, $insertAfter);
		$_REQUEST['Value'] = $string;

		$result = $this->BookingReshedule($_REQUEST['Value']);
		if($result['status'] == true){
			$response = array("status"=>1,"message"=>"Booking Rescheduled successfully");
		}
		else $response = array("status"=>1,"message"=>"booking con not be rescheduled beacuse of some internal error");
	}
	else {

		if(in_array($bookres->Status, array(6,7,8)))
		$response = array("status"=>0,"message"=>"Booking trip has been started ,So can not be rescheduled");
		else if($bookres->Status ==11)
		$response = array("status"=>0,"message"=>"Booking has been paid , So can not be rescheduled .");
		else
		$response = array("status"=>0,"message"=>"This Booking can not be rescheduled .");
		
	} 
		 header('Content-Type: application/json');
		 print_r(json_encode($response));
		 exit();
	}
	

	///// API Booking Reschedule used for Call Centre Ends Here //////////
	
	///// API Get Booking Details used for Call Centre Starts Here //////////
	
	public function GetBookingDetail($booking_ref)
	{
	$query="select id,BookingType,carType,Pickup,status as cabstatus,BookingDate,PickupDate,PickupTime,estimated_price,local_subpackage,Package_State,EstimatedDistance,PickUpArea,DropArea from tblcabbooking where booking_reference='$booking_ref' order by ID DESC limit 1";
	$fetch=mysqli_query($this->con,$query);
	$record=array();		
	$row=mysqli_fetch_assoc($fetch);	
	return $row;
	}
	
	///// API Get Booking Details used for Call Centre Ends Here //////////
	
	//// Function for Execute No Show button Starts Here By Mohit Jain /////
	
	public function ExecuteNoShow()
	{	
		$BookId = $_POST['BookingId'];
		$agent_id = $_POST['agent_id'];

		$booksql = "select Status,MobileNo,booking_reference,pickup from tblcabbooking where ID=$BookId";
		$bookres = $this->con->query($booksql)->fetch_object(); 

		$smssql = "select status,status_id from tblcabstatus where status_id=14";
		$smsres = $this->con->query($smssql)->fetch_object(); 

		if(in_array($bookres->Status, array(3,4,5,6))){

			$data["CallerID"]= $bookres->MobileNo;
			$data["drvrId"]= $bookres->pickup;
			$data["bookref"]= $bookres->booking_reference;
			$data["ReasonText"]= "Client is  not traceable";
			$data["RemarkText"]= $smsres->status;
			$data["agent_id"]= $agent_id;
			
			 $hstrystats = $this->UpdComnFeatr($data);

			 if($hstrystats == true){
			 	$res3 = $this->con->query("Update tblcabbooking set Status=14,Reason='".$data["ReasonText"]."',Remark='".$data["RemarkText"]."' where ID='$BookId'");
			 		if($res3){

			 			// Message template required here for execute no show
			 			// $_REQUEST['booking_id'] = $BookId.':resechdule';
						// $sendsms = new Menu();
						// $smss =	$sendsms->send_sms($_REQUEST['booking_id']);
						$response = array("status"=>1,"message"=>"Request Done !");
			 		}
			 		else $response = array("status"=>0,"message"=>"Some Internal error occured while Processing");

			 }
			 		else $response = array("status"=>0,"message"=>"Some Internal error occured while Processing");
			

		}
		else $response = array("status"=>0,"message"=>"This request Can only be accepted if driver has Accepted,Located,Reported or in waiting of Customer's Ride!");
		header("ContentType: application/json");
		print_r(json_encode($response));die;
	}
	
	//// Function for Execute No Show button  Ends Here By Mohit Jain /////
	
	//// Function for ReDispatch button  Starts Here By Mohit Jain /////
	
	public function ReDispatch()
	{
		$BookId = $_POST['BookingId'];
		$agent_id = $_POST['agent_id'];
		$booksql = "select Status,MobileNo,booking_reference,pickup,PickupLatitude,PickupLongtitude from tblcabbooking where ID=$BookId";
		$bookres = $this->con->query($booksql)->fetch_object(); 

		if(in_array($bookres->Status, array(2,3,4,5,6,7))){

			$data["CallerID"]= $bookres->MobileNo;
			$data["drvrId"]= $bookres->pickup;
			$data["bookref"]= $bookres->booking_reference;
			$data["ReasonText"]= "Redispatched because of some Processing error";
			$data["RemarkText"]= "Redispatched";
			$data["agent_id"]= $agent_id;
			
			 // $hstrystats = $this->UpdComnFeatr($data);
				
			 	$res3 = $this->con->query("Update tblcabbooking set Status=1,Reason='".$data["ReasonText"]."',Remark='".$data["RemarkText"]."' where ID='$BookId'");
			 		if($res3){

			 			// $RegrtrdNotify = new CustomerCare();

			 			//$RegrtrdNotifyres = $this->LogStackTrackerData($BookId,$bookres->PickupLatitude,$bookres->PickupLongtitude);
						
						$sql1="INSERT INTO tblbookinglogs(bookingid,`status`,message,`time`) VALUES('$booking_id',1,'Requesting car',NOW())";
						mysqli_query($this->con,$sql1) or die(mysqli_error($this->con));

						$sql2="INSERT INTO `booking_stack`(`booking_id`,`lat`,`long`,`status`) VALUES('$booking_id','$pickuplatlat','$pickuplatlng','')";
						mysqli_query($this->con,$sql2) or die(mysqli_error($this->con));

						$sql3="INSERT INTO `tblbookingtracker` (`BookingID`,`Latitutude`,`Logitude`,`Date_Time`,`CabStatus`) 
						VALUES('$booking_id','$pickuplatlat','$pickuplatlng',NOW(),'1')";
						mysqli_query($this->con,$sql3) or die(mysqli_error($this->con));
			 				
			 			//if($RegrtrdNotifyres){

							 $hstrystats = $this->UpdComnFeatr($data);

							 if($hstrystats){

							 	// Message template required here for Redispatching
			 						// $_REQUEST['booking_id'] = $BookId.':resechdule';
									// $sendsms = new Menu();
									// $smss =	$sendsms->send_sms($_REQUEST['booking_id']);


								$response = array("status"=>1,"message"=>"Request Done !");
							}
							
			 				else $response = array("status"=>0,"message"=>"Some Internal error occured while Processing");

			 			
			 			//}
						
			 			//else $response = array("status"=>0,"message"=>"Some Internal error occured while Processing");

			 			// Message template required here for execute no show
			 			// $_REQUEST['booking_id'] = $BookId.':resechdule';
						// $sendsms = new Menu();
						// $smss =	$sendsms->send_sms($_REQUEST['booking_id']);
			 		} 
			 		else $response = array("status"=>0,"message"=>"Some Internal error occured while Processing");

			 // }
			 		// else $response = array("status"=>0,"message"=>"Some Internal error occured while Processing");
			

		}
		else $response = array("status"=>0,"message"=>"This request Can only be accepted if driver has Accepted,Located,Reported or in waiting of Customer's Ride!");
		header("ContentType: application/json");
		print_r(json_encode($response)); die;
	}
	
	//// Function for ReDispatch button  Ends Here By Mohit Jain /////
	
	//// Function for Trip Done button  Starts Here By Mohit Jain /////
	
	public function tripdoneadmin()
	{
		$bokId = substr($_REQUEST['bokref'], 4);

		$res = $this->con->query("update tblcabbooking set Status=11,StatusC=13 where ID=$bokId");
		if($res){

				$data = $this->con->query("select MobileNo,pickup from tblcabbooking where ID=$bokId")->fetch_assoc();
				$data['ReasonText'] = "TripDone from Admin side";
				$data['drvrId'] = $data['pickup'];
				$data['bookref'] = $_REQUEST['bokref'];
				$data['CallerID'] = $data['MobileNo'];
				$res1 = $this->UpdComnFeatr($data); 
				if($res1){
					// Message template required here for execute no show
			 			// $_REQUEST['booking_id'] = $BookId.':resechdule';
						// $sendsms = new Menu();
						// $smss =	$sendsms->send_sms($_REQUEST['booking_id']);
					$response = array("status"=>1,"message"=>"Trip Done successfully updated");
				}
			else $response = array("status"=>0,"message"=>"some internal error occured");
		}
		else $response = array("status"=>0,"message"=>"some internal error occured");
			
			header("ContentType:application/json");
			echo json_encode($response);
			// print_r($_POST); 
			//die;
	}
	
	public function UpdComnFeatr($data='')
	{
		extract($data);
		$agent_id=$agent_id;
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
     	$res1=$this->con->query($qry);

		$res2 =$this->con->query("Update tbldriver set status=0 where uid='".$drvrId."'");

		if($res1 && $res2)
			return true;
		else return false;
	}
	
	//// Function for Trip Done button  Ends Here By Mohit Jain /////
	
	//// Function for car Types Starts Here By Mohit Jain /////
	
	public function CarTypes()
	{
	 
	 $res = $this->con->query("select Id,CabType,color from tblcabtype");

	 while ($row = $res->fetch_assoc()) {
		$rows[] = $row;
	 }

	$res1 = $this->con->query("select PickupLatitude as lat,PickupLongtitude as lng from tblcabbooking where ID=".$_POST['bokid']."")->fetch_assoc();
	$res2 = $this->con->query("SELECT `UID`,td.VehicleRegistrationNo as vehicle ,td.FirstName as name ,td.ContactNo as contact,tct.color,tu.Latitude as lat,tu.Longtitude1 as lng FROM `tbldriver` td 
	JOIN tbluser tu ON td.UID=tu.id 
	JOIN tblcabtype tct ON td.TypeOfvehicle=tct.Id  
	WHERE td.`status`=0 and tu.loginStatus=1 and tu.UserType=3");

	 while ($row2 = $res2->fetch_assoc()) {
		// $drvsgeoloc[$row2['UID']] =$row2['lat'].','.$row2['lng'];
		$distance = $this->getDistance($res1['lat'],$res1['lng'],$row2['lat'],$row2['lng'],"K");
		$row2['distance'] = ceil($distance);
		if($distance<=5)
			$rows2[] = $row2;
		else continue;
		// $rows2[]= ($distance<=5)?$row2:continue;
		// $rows2[] = $distance;
			
		// $rows2[] = $row2;
	 }
	 
	 header("ContentType:application/json");
		print_r(json_encode(array($rows,$rows2))); die;
		//echo json_encode(array($rows,$rows2)); die;
		// print_r(json_encode($res1));
	//	die;
	}
	
	//// Function for car Types Ends Here By Mohit Jain /////
	
	// Function for calling travel geo location for booking Starts Here By Mohit Jain ///
	
	public function ctravelgeoloc($BookingId_i,$user_id)
	{		

	// for testing
	// public function ctravelgeoloc()
	// {		
	 	// $dis = new Admin();
		// for testing
		// $BookingId_i = 8533;
		// 	$user_id = 2130;


			$sql="select lat,longi,distance,datetime,WaitingTime,pre_Waiting_time from tbldriverlocation where booking_id='$BookingId_i' and user_id = '$user_id' order by id asc ";
			$qry = $this->con->query($sql);

			$prewaiting = "";
			$waiting = "";

			while ($row = $qry->fetch_assoc()) {

				$rows[] = $row;
				$geoloc[] = $row['lat'].",".$row['longi'];
				// echo $row['pre_Waiting_time']."<br>";
				$prewaiting = $prewaiting + strtotime($row['pre_Waiting_time']);
				$waiting = $waiting + strtotime($row['WaitingTime']);
				// echo $prewaiting."<br>";
				
			}

			$strtime = $rows[0];
			$strtime = $strtime['datetime'];

			$endtime = end($rows);
			$endTime = $endtime['datetime'];

			// print_r($endtime['datetime']);die;
			$prewaitym = date("h:i:s",$prewaiting);
			$waitingtym = date("h:i:s",$waiting);

				$dis1=0;
			for ($i=0; $i < count($geoloc)-1; $i++) { 
					$k = $i;
					$k = $k + 1;
					$geo1 = explode(",", $geoloc[$i]);
					$geo2 = explode(",", $geoloc[$k]);

	 			$dis = $this->getDistance($geo1[0],$geo1[1],$geo2[0],$geo2[1],"K"); 
	 			
	 			$dis1 = $dis1 + ($dis);

			}
 			
 			$distance = round($dis1*1000);


			$rqrdata = array(
					"waitingtym"=>$waitingtym,
					"prewaitym"=>$prewaitym,
					"strtTime" => $strtime,
					"endTime" => $endTime,
					"distance" => $distance,
				);
			return $rqrdata;
	}
	
	// Function for calling travel geo location for booking Ends Here By Mohit Jain ///
	
	
		///// API Booking Reschedule used for Call Center Starts Here //////////
	
	
	public function BookingReshedule2()
	{	//callerid;OrderNo;CallerName;ReschuduleDate;ReschuduleTime;Paddress;plat;plong;Daddress;Reason;Remark;dlat;dlong;ReasonText;RemarkText
	//$Value="9891735121;naveenKumar;15:07/2015;16:07/2015;HC15075805;Picktext;Droptext;Resontext;null or text;Plat;plong;dlat;dlong;resontext ;reason remarks";
	
		$jsonstring = trim($_REQUEST['Value']);
	$val =json_decode($jsonstring,true);

	//$Value 			=	$_REQUEST['Value'];
	//$val			=	explode(';',$Value);
	//CALL sp_booking_reshedule()
	$CallerId		=	$val['CallerId'];
	$OrderNo		=	$val['book_ref'];
	$CallerName		=	$val['CallerName'];	
	$ReschuduleDate =	$val['ReschuduleDate'];
	$ReschuduleTime	=	$val['ReschuduleTime'];
	
	$PCity=$val['PCity'];
	$PArea=$val['PArea'];
	
	$Paddress		=	$val['PAddress'];
	$plat			=	$val['PLatitude'];
	$plong			=	$val['PLongitude'];
	
	$DCity			=	$val['DCity'];
	$DArea=$val['DArea'];
	
	$Daddress		=	$val['DAddress'];
	$Reason			=	$val['Reason'];
	$Remark			=	$val['Remark'];
	$dlat					=	$val['DLatitude'];
	$dlong				=	$val['DLognitude'];
	$ReasonText		=	$val['Reason'];
	$RemarkText		=	$val['Remark'];
	///// using get booking detail for reshedule history
	//$data=array();
	$data			=	$this->GetBookingDetail($OrderNo);		// order is making as booking reference  
	//print_r($data); die;
	$id				=	$data['id'];
	$BookingType	=	$data['BookingType'];
	$Pickup			=	$data['Pickup'];
	$old_cabstatus	=	$data['cabstatus'];
	$old_BookingDate=	$data['BookingDate'];
	$old_PickupDate	=	$data['PickupDate'];
	$old_PickupTime	=	$data['PickupTime'];
	$distance		=	$data['EstimatedDistance'];
	//$new_bookingDate= NOW();
	$new_PickupDate	=	$ReschuduleDate;
	$new_PickupTime	=	$ReschuduleTime;
	//$new_cabstatus=$ReschuduleTime;
	
	//// New Variables ////
	$estimated_price	=	$data['estimated_price'];
	$Package_State		=	$data['Package_State'];
	$carType			=	$data['carType'];
	
	$sql1="select id from tblstates where state='$Package_State' limit 1";
	$result = mysqli_query($this->con, $sql1);
	$val = mysqli_fetch_object($result);
	$stateId=$val->id;
	
	//// New Variables ////
	////// For Local Booking Starts Here //////
	
	if($BookingType=="101"){
	$cablocalfor	=	$data['local_subpackage'];
	$datam2			=	$this->FetchLocalPackageData($BookingType,$cablocalfor,$stateId,$carType);
	}
	////// For Local Booking Ends Here //////
	
	/////// For Point To Point Booking Starts Here ///////
	elseif($BookingType=="102"){
	$cablocalfor	=	"";
	$datam2			=	$this->FetchPointToPointData($BookingType,$carType,$stateId,$distance,$cablocalfor);
	}
	/////// For Point To Point Booking Ends Here ///////
	
	/////// For Point To Point Booking Starts Here ///////
	elseif($BookingType=="103"){
	$PickAreaName		=	$data['PickUpArea']; 
	$DropAreaName		=	$data['DropArea'];
	$sqlAir	="SELECT * FROM `tblairportaddress` WHERE `Fix_Point`='$PickAreaName' LIMIT 1";
	$sqlAir	=mysqli_query($this->con,$sqlAir);	
	$row=mysqli_fetch_assoc($sqlAir);
	$num_rows = mysqli_num_rows($sqlAir);
	if($num_rows>0){
	$fixpoint		=	$PickAreaName;
	$address		=	$DropAreaName;
	}
	else{
	$address		=	$PickAreaName;
	$fixpoint		=	$DropAreaName;
	}
	$datam2			=	$this->FetchAirportDistanceFare($BookingType,$carType,$stateId,$distance,$fixpoint,$address);
	}
	/////// For Point To Point Booking Ends Here ///////
	
	//echo "<pre>";print_r($datam2); die;
	
	$totalbill=round($datam2['totalbill']);
	
	$PeakFare			=	$this->calculatePeakTimeCharges($totalbill,$new_PickupTime);
	$totalbill			=	$totalbill + $PeakFare['peakcharge'];
		 
	 /// Check it
	 //// Calculation for NightCharges Starts here /////
	 if(strtotime($new_PickupTime) >= strtotime($datam2['night_rate_begins']) and strtotime($new_PickupTime) < strtotime($datam2['night_rate_ends'])){
		if($datam2['nightCharge_unit'] == 'Rs'){
			$NightCharges = $datam2['NightCharges'];
		}else{
			$NightCharges = ($totalbill * $datam2['NightCharges'])/100;
		}
	}
		
	//// Calculation for NightCharges Ends here /////
	
	$totalbill			=	$totalbill + $NightCharges;
	$extraPrice			=	$this->calculateExtraCharges($totalbill,$datam2['extras']);
	$totalbill			=	$totalbill + $extraPrice;

	
	$BasicTax			=	(($totalbill) * $datam2['basic_tax'])/100;
	$totalbill			=	$totalbill + $BasicTax; 
	$totalbill			=	round($totalbill);

	$Min_Pkg_Hrs		=	$datam2['Min_Pkg_Hrs'];
	$Min_Pkg_KM			=	$datam2['Min_Pkg_KM'];
	$per_km_charge		=	$datam2["Per_Km_Charge"];
	$min_distance		=	$datam2["Min_Distance"];
	$wait_charge		=	$datam2["WaitingCharge_per_minute"];
	$waiting_min		=	$datam2["Waitning_minutes"];
	
	$basic_tax			=	$datam2['basic_tax'];
	$basic_tax_type		=	$datam2['basic_tax_type'];
	$basic_tax_price	=	round($BasicTax);
	$rounding			=	$datam2['rounding'];
	$level				=	$datam2['level'];
	$direction			=	$datam2['direction'];
	$nightcharge_unit	=	$datam2['nightCharge_unit'];
	$nightcharge		=	$datam2['NightCharges'];
	$nightcharge_price	=	round($NightCharges);
	$night_rate_begins	=	$datam2['night_rate_begins'];
	$night_rate_ends	=	$datam2['night_rate_ends'];
	$premiums			=	$datam2['premiums'];
	$premiums_unit		=	$datam2['premiums_unit'];
	$extraPrice			=	round($extraPrice);
	$peakTimePrice		=	round($PeakFare['peakcharge']);
	$peaktimeFrom		=	$PeakFare['peaktimeFrom'];
	$peaktimeTo			=	$PeakFare['peaktimeTo'];
	$peaktimepercentage	=	$PeakFare['peakpercentage'];
	$extras				=	str_replace(array('[',']'),'',$datam2['extras']);
		
	///////////////////////////
	$qry1 ="insert into  tbl_booking_resec_reassign (booking_id,booking_type,old_driver_id,old_pick_time,old_pick_date,new_pick_time,new_pick_date,old_booking_date,new_booking_date,old_estimated_price,new_estimated_price)
	values ('$id','$BookingType' ,'$Pickup','$old_PickupTime','$old_PickupDate' ,'$new_PickupTime','$new_PickupDate','$old_BookingDate',NOW(),'$estimated_price','$totalbill')";
	$fetch2=mysqli_query($this->con,$qry1);

	$qry ="insert into  tblagent_work_history (AgentID,CallerID,BookingID,Actiontype,tblagent_work_history.Date,ActionDesc)
	values ((select CSR_ID from tblcabbooking where booking_reference='$OrderNo'),'$CallerId' ,'$OrderNo','Reschedule',Now(),'Booking Reschedule done by Call Center agent' )";
	$fetch1=mysqli_query($this->con,$qry);

	$query="update tblcabbooking set UserName='$CallerName', PickupLatitude='$plat', PickupLongtitude='$plong', DestinationLatitude='$dlat', DestinationLongtitude='$dlong', PickupDate='$ReschuduleDate', PickupTime='$ReschuduleTime', PickupCity='$PCity', PickupLandmark='$Paddress', DestinationCity='$DCity', PickupLocation='$PArea', PickupArea='$PArea', PickupAddress='$Paddress', PickupLatitude='$plat',PickupLongtitude='$plong', DropArea='$DArea', DropLocation='$DArea', DropAddress='$Daddress', DestinationLatitude='$dlat', DestinationLongtitude='$dlong', Remark='$RemarkText', StatusC =55, Reason='$ReasonText', CabFor='$Min_Pkg_Hrs', estimated_price='$totalbill', approx_distance_charge='$per_km_charge', approx_after_km='$min_distance', approx_waiting_charge='$wait_charge',appox_waiting_minute='$waiting_min', min_Distance='$Min_Pkg_KM', EstimatedDistance='$Min_Pkg_KM', basic_tax='$basic_tax',basic_tax_type='$basic_tax_type', basic_tax_price='$basic_tax_price', rounding='$rounding', level='$level',direction='$direction', nightcharge_unit='$nightcharge_unit', nightcharge='$nightcharge', nightcharge_price='$nightcharge_price', night_rate_begins='$night_rate_begins', night_rate_ends='$night_rate_ends', premiums='$premiums',premiums_unit='$premiums_unit', extras='$extras', extraPrice='$extraPrice',peakTimePrice='$peakTimePrice',peaktimeFrom='$peaktimeFrom',peaktimeTo='$peaktimeTo',peaktimepercentage='$peaktimepercentage'  where booking_reference='$OrderNo'";
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
	
}
?>
