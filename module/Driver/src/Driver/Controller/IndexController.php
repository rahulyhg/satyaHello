<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Driver\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;
class IndexController extends AbstractActionController {
	
	protected $con;
	
	public function __construct(){
		date_default_timezone_set("Asia/Kolkata");
		$key = '';
		$this->con=mysqli_connect("10.0.0.35","root","Travel@(4242)","hello42_new");
	
	}
	
	public function indexAction(){
		$user_session = new Container('user');
		$this->layout()->setTemplate('layout/layout');
		
		return new ViewModel(array('id'=>$user_session->username));
	}
     public function logoutAction()
    {
         $user_session = new Container('user');
         $user_session->username="";
           return $this->redirect()->toRoute('home', array( 
                        'controller' => 'Index', 
                        'action' =>  'index' 
                    ));
    }
    
    public function forgetpasswordAction(){
        
        return new ViewModel();
        
    }
    public function signupAction(){
        
		print_r("dfsgdsgdsg");die;
        return new ViewModel();
        
    }
	   public function accountbalanceAction(){
        
        return new ViewModel();
        
    }
	public function changepasswordAction(){
        
        return new ViewModel();
        
    }
	public function editprofileAction(){
        
        return new ViewModel();
        
    }
	
	public function carbookingreviewAction(){
        
        return new ViewModel();
        
    }
	public function carbookingreview1Action(){
        
        return new ViewModel();
        
    }
	
	public function myaccountAction(){
        
        return new ViewModel();
        
    }
	
	public function travellersAction(){
        
        return new ViewModel();
        
    }
	
	public function driverorderhistoryAction(){
        
        return new ViewModel();
        
    }
	
	public function userorderhistoryAction(){
        
        return new ViewModel();
        
    }
	public function credithistoryAction(){
        
        return new ViewModel();
        
    }
	public function drivereditprofileAction(){
        
        return new ViewModel();
        
    }
    public function resetpasswordAction($token=null){
        echo $token;
        return new ViewModel();
        
    }
    
    
    public function carreerAction()
	{
	return new ViewModel();
	}
	/*protected $collectionOption = array('GET','POST');
	protected $resourceOptions = array('GET','PUT','DELETE');
	
	protected function _getOptions()
	{
		if($this->params->formRoute('id',false)){
			return $this->resourceOptions;
		}
		return $this->collectionOption;
	}
	
	public function options()
	{
		$response = $this->getResponse();
		$response->getHeader()
				 ->addHeaderLine('Allow',implode(',',$this->_getOptions()));
		return $response;
	
	}
	public function setEventManager(EventManagerInterface $events){
		
		$this->events = $events;
		$events->attach('dispatch', array($this,'checkOptions'),10);
	}
	public function checkOptions($e)
	{
		if(in_array($e->getRequest()->getMethod(), $this->_getOptions())){
		
			return;
		}
		$response = $this->getResponse();
		$response->setStatusCode(405);
		return $response;
	}
	public function create($data){
	
		$userAPIService = $this->$getServicelLocator()->get('userAPIService');
		$result = $userAPIService->create($data);
		$response->setStatusCode(201);
		return new JsonModel($result);
	}*/

	public function carlistAction()
    {	
	$con=mysqli_connect("10.0.0.35","root","Travel@(4242)","hello42_new");
	$res=array();
	$request=$this->getRequest();
	if($request->isPost()){
		$res['intercityName']=			$request->getPost('intercityName');
		$res['intercityMobile']=		$request->getPost('intercityMobile');
		$res['intercityEmail']=			$request->getPost('intercityEmail');
		$res['intercitytripfrom']=		$request->getPost('intercitytripfrom');
		$res['intercitytripto']=		$request->getPost('intercitytripto');
		$res['intercity_Ddate']=		$request->getPost('intercity_Ddate');																								
		$res['intercityNationality']=	$request->getPost('intercityNationality');
		$res['intercityAdults']=		$request->getPost('intercityAdults');
		$res['intercityChilds']=		$request->getPost('intercityChilds');
		$res['intercityLuggages']=		$request->getPost('intercityLuggages');
		$res['cartypesintercity']=      $request->getPost('cartypesintercity');
		$res['intercityPickupAddress']= $request->getPost('intercityPickupAddress');
		$res['intercityDropAddress']=	$request->getPost('intercityDropAddress');
		$res['intercityPickupTimeH']=	$request->getPost('intercityPickupTimeH');
		$res['intercityPickupTimeM']=	$request->getPost('intercityPickupTimeM');
		$res['BookingType']=			$request->getPost('BookingType');
		$BookingType=$res['BookingType'];
		$carType=$res['cartypesintercity'];
		$from_city=$res['intercitytripfrom'];
		$to_city=$res['intercitytripto'];
	}	
		//$sql = "SELECT name,capacity FROM tblcablistmgmt WHERE BookingType = '$BookingType' and cabType='$carType' and name!=''";
		/*$sql="SELECT t1.*, t2.* FROM `tbl_inter_out_price` t1 left join tblcab_inter_out_city t2 ON t1.veh_id=t2.CabId 
			  WHERE t1.`booking_type`='$BookingType' and t1.`veh_type`='$carType' and t1.`status`='1' and t1.`from_city`='$from_city' and t1.`to_city`='$to_city'";*/
		$sql="SELECT * FROM tblcablistmgmt WHERE BookingType='$BookingType'";
		$result=mysqli_query($this->con,$sql)or die(mysqli_error($this->con));
		$carDetails=array();
		while($row=mysqli_fetch_array($result)){
			$cab_type	=	$row["CabType"];
			//$sql2="SELECT * FROM tbl_inter_city_route_package WHERE route_vehicle_type='$cab_type' and from_city='$from_city' and route_city_path LIKE '%$to_city%'";
			//$sql2="SELECT * FROM tbl_inter_city_route_package WHERE from_city='$from_city' and route_city_path LIKE '%$to_city%'";
			$sql2="SELECT * FROM tbl_inter_city_route_package WHERE from_city='$from_city' and to_city='$to_city'"; 
			$result2=mysqli_query($this->con,$sql2)or die(mysqli_error($this->con));
			$row2=mysqli_fetch_array($result2);
			if($cab_type==1){
				$tmp_fare_rate=$row2['economy_rate'];				
			}
			elseif($cab_type==2){
				$tmp_fare_rate=$row2['sedan_rate'];				
			}
			if($cab_type==3){
				$tmp_fare_rate=$row2['prime_rate'];				
			}
			$carDetails[]=array("routeId"=>$row2['route_id'],"name"=>$row['name'],"capacity"=>$row["capacity"],"CabDesc"=>$row["CabDesc"],"cabImg"=>$row["image"],"cabType"=>$row["CabType"],
								"CabModel"=>$row["VehicleModel"],
								"MinimumCharge"=>$tmp_fare_rate,
								"Per_Km_Charge"=>$row['Per_Km_Charge'],
								"NightCharges"=>$row['NightCharges'],
								"night_rate_begins"=>$row['night_rate_begins'],
								"Min_Distance"=>$row2['fix_km'],
								"fix_hour"=>$row["fix_hour"],
								"extra_km_charges"=>$row["extra_km_charges"],"extra_hour_charges"=>$row["extra_hour_charges"]);
		} //echo"<pre>";print_r($carDetails); die;
		
        return new ViewModel(array('data'=>$res,'carDetails'=>$carDetails));
    }
	
	public function carlistoutstationAction()
    {
    session_start();		
	$con=mysqli_connect("10.0.0.35","root","Travel@(4242)","hello42_new");
	$res=array();
	$request=$this->getRequest();
	//echo "<pre>";print_r($request); die;
	if($request->isPost()){
	//	print_r($request->isPost()); die;
		 $_SESSION['optionsInlineRadios']=$request->getPost('optionsInlineRadios');
		 $res['outstationName']=$request->getPost('outstationName');
		 $_SESSION['outstationName']= $res['outstationName'];
		 $_SESSION['outstationMobile']=$request->getPost('outstationMobile');
		 $_SESSION['outstationMobile2']=$request->getPost('outstationMobile2');
		 $_SESSION['outstationEmail']=$request->getPost('outstationEmail');
		 $_SESSION['outRoundtripfrom']=$request->getPost('outRoundtripfrom');
		 $_SESSION['outRoundtripto']=$request->getPost('outRoundtripto');
		 $_SESSION['outstation_Ddate']=$request->getPost('outstation_Ddate');
		 $_SESSION['outPickupTimeH']=$request->getPost('outPickupTimeH');
		 $_SESSION['outPickupTimeM']=$request->getPost('outPickupTimeM');
		 $_SESSION['outstation_Rdate']=$request->getPost('outstation_Rdate');
		 $_SESSION['outReturnTimeH']=$request->getPost('outReturnTimeH');
		 $_SESSION['outReturnTimeM']=$request->getPost('outReturnTimeM');
		 $_SESSION['NoOfday']=$request->getPost('NoOfday');
		// $_SESSION['NightCharges']=$request->getPost('NightCharges');
		
		 $_SESSION['outstationNationality']=$request->getPost('outstationNationality');
		 $_SESSION['outstationAdults']=$request->getPost('outstationAdults');
		 $_SESSION['outstationChilds']=$request->getPost('outstationChilds');
		 
		echo  $totalNoPeople = trim($request->getPost('outstationAdults')+ $request->getPost('outstationChilds'));
		
		 $_SESSION['outstationLuggages']=$request->getPost('outstationLuggages');
		 $_SESSION['NoOfCar_outstation']=$request->getPost('NoOfCar_outstation');
		 $_SESSION['coupan_code_outstation']=$request->getPost('coupan_code_outstation');
		 $_SESSION['flightmode']=$request->getPost('flightmode');
		 $_SESSION['flight_no']=$request->getPost('flight_no');
		 $_SESSION['outPickupAddress']=$request->getPost('outPickupAddress');
		//die;
		$no_days = $_SESSION['NoOfday'];
		$OutstationCategory = $_SESSION['optionsInlineRadios'];
		     $_SESSION['cartype']=$request->getPost('cartype');
		//echo $res['intercityPickupTimeM']=$request->getPost('intercityPickupTimeM');
	    $_SESSION['BookingType']=$request->getPost('BookingType');
	    $BookingType=$_SESSION['BookingType'];
	    $carType=$request->getPost('cartype');
	    $from_city=$_SESSION['outRoundtripfrom'];
		//$from_city = 2;
		$to_city=$_SESSION['outRoundtripto'];
		
				
		$qry = "SELECT * from tbl_city_distance_list WHERE source_city='$from_city' and destination_city='$to_city'";
		$result = mysqli_query($this->con, $qry);
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0)
			$status="true";
		else
		{
			
			$Full_Address=$from_city.", India";
		$find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($Full_Address));
			file_put_contents("json.txt",$find_pickup); 					
			$enc=json_decode($find_pickup);
			if($enc->status == 'OK'){
				$lat=$enc->results[0]->geometry->location->lat;
				$long=$enc->results[0]->geometry->location->lng;				
		
			}	
		 $Full_Address1=$to_city.", India";
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
			 $data["distance_km"]=$distance;				
				
			}
		
		 //$status="false";
		}
		
		$carDetails=array();
		$info = mysqli_fetch_assoc($result); 
		
		$qry_radius = "Select city_radius from tblcity where name = '$to_city' or name='$from_city'";
		$result_radius = mysqli_query($this->con, $qry_radius);
		$radius =0;
		while($info_radius = mysqli_fetch_assoc($result_radius))
		{
	    $city_radius = $info_radius['city_radius'];
		$radius = $radius + $city_radius;
		}
		$radius = $radius/2;
		
		$qryBookingBill = "SELECT t1.BookingTypeId,t1.Cabtype,t1.MinimumCharge,t1.Min_Distance, t1.Per_Km_Charge, t1.first_km_rate, t1.WaitingCharge_per_minute, t1.tripCharge_per_minute, t1.NightCharges,t1.speed_per_km,t2.Id,t2.CabType as CabCategory,t2.Cabcapacity
from tblbookingbill t1 inner join tblcabtype t2 on t1.CabType = t2.Id  WHERE BookingTypeId='104' and t1.CityId = '2' and t2.Cabcapacity>=' ".$totalNoPeople."' ";
		$resultBookingBill = mysqli_query($this->con, $qryBookingBill);
		while($infoBookingBill = mysqli_fetch_assoc($resultBookingBill))
		{			
		//echo $infoBookingBill['MinimumCharge']; die;
		//$MinimumCharge	=	$info['distance_km']*$infoBookingBill['MinimumCharge'];
		$info['distance_km'];
	    $data['km']							=	$info['distance_km'];
	    $data['MinimumCharge']				=	$infoBookingBill['MinimumCharge'];
		$data['Min_Distance']				=	$infoBookingBill['Min_Distance']; 		
		$data['Per_Km_Charge']				=	$infoBookingBill['Per_Km_Charge'];
		$data['first_km_rate']				=	$infoBookingBill['first_km_rate'];
		$data['WaitingCharge_per_minute']	=	$infoBookingBill['WaitingCharge_per_minute'];
		$data['tripCharge_per_minute']		=	$infoBookingBill['tripCharge_per_minute'];
		$data['NightCharges']				=	$infoBookingBill['NightCharges'];
		//$carDetails[] = $data
	    $infoBookingBill['CabCategory'];
		$infoBookingBill['Cabcapacity'];
		//$infoBookingBill['CabCategory'];
		$infoBookingBill['speed_per_km'];
		
		
		if($OutstationCategory == '1')
		{
			 $distance1 = ($distance*2)."\t";
			 $min_dist = ($infoBookingBill['Min_Distance']*$no_days)."\t";
			if($distance1 > $min_dist)
			{
				 $cab_charge = $distance1 * $infoBookingBill['Per_Km_Charge'];
			 //$rem_distance = $distance - $infoBookingBill['Min_Distance'];
			 //$cab_charge_Oneway = $infoBookingBill['MinimumCharge'] + ($rem_distance*$infoBookingBill['Per_Km_Charge'])+$radius;
			 //echo  $cab_charge = ($cab_charge_Oneway*2) + ($infoBookingBill['NightCharges']*$no_days); 
			}
			else
			{
				 $cab_charge = $no_days * $infoBookingBill['MinimumCharge'];
			 //$cab_charge_Oneway = $infoBookingBill['MinimumCharge']+$radius;
			 //echo $cab_charge = ($cab_charge_Oneway*2) + ($infoBookingBill['NightCharges']*$no_days); 
			}
			
		}
		else if($OutstationCategory == '2')
		{
			if($distance > $infoBookingBill['Min_Distance'])
			{
			 $rem_distance = $distance - $infoBookingBill['Min_Distance'];
			 $cab_charge = $infoBookingBill['MinimumCharge'] + ($rem_distance*$infoBookingBill['Per_Km_Charge']);
			 //$cab_charge = $cab_charge_Oneway 
			}
			else
			{
			 $cab_charge = $infoBookingBill['MinimumCharge'];
			}
		}
		$dayWise_cabcharge = $no_days* $cab_charge;
		
		
		$_SESSION['cab_charge']=intval($cab_charge);
		$_SESSION['dayWise_cabcharge']=$dayWise_cabcharge;
		
		/* $sql="SELECT * FROM tblcablistmgmt WHERE BookingType='$BookingType' and cabType = '$carType'";
		$result=mysqli_query($this->con,$sql)or die(mysqli_error($this->con));
		
		while($row=mysqli_fetch_array($result)){ */
			 //$cab_type	=	$row["CabType"];
			 $carDetails[]=array("Cabtype"=>$infoBookingBill['Cabtype'],"CabCategory"=>$infoBookingBill['CabCategory'],
								"MinimumCharge"=>$infoBookingBill["MinimumCharge"],
								"Cabcapacity"=>$infoBookingBill["Cabcapacity"],
								"speed_per_km"=>$infoBookingBill["speed_per_km"],
								"cab_charge"=>$cab_charge,
								"radius"=>$radius,
								"distance"=>$distance,
								"dayWise_cabcharge"=>$dayWise_cabcharge);
						
		    }
			//die;
		$_SESSION['carDetails'] = $carDetails; 
		
		
		/* $sql="SELECT * FROM tblcablistmgmt WHERE BookingType='$BookingType'";
		$result=mysqli_query($this->con,$sql)or die(mysqli_error($this->con));
		$carDetails=array();
		while($row=mysqli_fetch_array($result)){
			 $cab_type	=	$row["CabType"]; */
			//$sql2="SELECT * FROM tbl_inter_city_route_package WHERE route_vehicle_type='$cab_type' and from_city='$from_city' and route_city_path LIKE '%$to_city%'";
			//$sql2="SELECT * FROM tbl_inter_city_route_package WHERE from_city='$from_city' and route_city_path LIKE '%$to_city%'";
			/* $sql2="SELECT * FROM tblbookingbill WHERE cityId='$from_city'"; 
			$result2=mysqli_query($this->con,$sql2)or die(mysqli_error($this->con));
			$row2=mysqli_fetch_array($result2); */
			/* $row2['economy_rate'];	
			if($cab_type==1){
				 $tmp_fare_rate=$row2['economy_rate'];				
			}
			elseif($cab_type==2){
				$tmp_fare_rate=$row2['sedan_rate'];				
			}
			if($cab_type==3){
				$tmp_fare_rate=$row2['prime_rate'];				
			} */
			/* $carDetails[]=array("routeId"=>$row2['route_id'],"name"=>$row['name'],"capacity"=>$row["capacity"],"CabDesc"=>$row["CabDesc"],"cabImg"=>$row["image"],"cabType"=>$row["CabType"],
								"CabModel"=>$row["VehicleModel"],
								"MinimumCharge"=>$tmp_fare_rate,
								"Per_Km_Charge"=>$row['Per_Km_Charge'],
								"NightCharges"=>$row['NightCharges'],
								"night_rate_begins"=>$row['night_rate_begins'],
								"Min_Distance"=>$row2['fix_km'],
								"fix_hour"=>$row["fix_hour"],
								"extra_km_charges"=>$row["extra_km_charges"],"extra_hour_charges"=>$row["extra_hour_charges"]);
		} */ //echo"<pre>";print_r($carDetails); die;
		
		//$_SESSION['carDetails'] = $carDetails;  
		//print_r($_SESSION['carDetails'][8]); die;
        //return new ViewModel(array('data'=>$res,'carDetails'=>$carDetails));
	}	
	 
	//print_r($_SESSION);
		
    }
	
	public function bookinDataAction(){
		session_start();		
	   $_SESSION['vehicleId']=$_REQUEST['vehicleId'];
		return new JsonModel(array("Status"=>"true"));
	}
	
	public function bookingoutstationAction()
    {
		session_start();
		$con=mysqli_connect("10.0.0.35","root","Travel@(4242)","hello42_new");	
         $vehicleId = $_SESSION['vehicleId'];	
		 $_SESSION['outstationName'];
		 $_SESSION['outstationMobile'];
		 $_SESSION['outstationMobile2'];
		 $_SESSION['outstationEmail'];
		 $_SESSION['outRoundtripfrom'];
		 $_SESSION['outRoundtripto'];
		 $_SESSION['outstation_Ddate'];
		 $_SESSION['outPickupTimeH'];
		 $_SESSION['outPickupTimeM'];
		 $_SESSION['outstation_Rdate'];
		 $_SESSION['outReturnTimeH'];
		 $_SESSION['outReturnTimeM'];
		 $_SESSION['NoOfday'];
	     $nationality = $_SESSION['outstationNationality'];
		 $_SESSION['outstationAdults'];
		 $_SESSION['outstationChilds'];
		 $_SESSION['outstationLuggages'];
		 $_SESSION['NoOfCar_outstation'];
		 $_SESSION['coupan_code_outstation'];
		 $_SESSION['flightmode'];
		 $_SESSION['flight_no'];
		 $_SESSION['outPickupAddress'];  
        
	    $sql_cab="SELECT name as cabname FROM tblcablistmgmt WHERE id='$vehicleId'";
		$result_cab=mysqli_query($this->con,$sql_cab)or die(mysqli_error($this->con));
		$info = mysqli_fetch_array($result_cab);
		$_SESSION['cabname'] = $info['cabname']; 
		$_SESSION['VehicleModel'] = $info['VehicleModel'];
		$_SESSION['distance'];
		$_SESSION['cab_charge'];
		
		$sql="SELECT * FROM tbl_sightseeing WHERE city_id='1'";
		$result=mysqli_query($this->con,$sql)or die(mysqli_error($this->con));
	    $SightseeingDetails=array();
		while($row=mysqli_fetch_array($result))
		{
			$s_id =$row['s_id'];
			
		    $sql1="SELECT * FROM tbl_sightseeingfair WHERE s_id='$s_id' and nationality='$nationality'";
		    $result1=mysqli_query($this->con,$sql1)or die(mysqli_error($this->con));
		    $row1=mysqli_fetch_array($result1);
			//echo "S. fare - ".$row1['fair']."<br/>";
			  $row1['nationality']."<br/>";
			 
			 $sql3="SELECT * FROM tbl_guidefare WHERE city_id='1' and s_id='$s_id' and nationality = 'Indian' and language = 'hindi'";
			$result3=mysqli_query($this->con,$sql3)or die(mysqli_error($this->con));
		    $row3=mysqli_fetch_array($result3);
			//echo "Guide Fare - ".$row3['guide_charge'];
			
			 $SightseeingDetails[]=array("s_id"=>$row['s_id'],"name"=>$row['name'],"image"=>$row["image"],"coverage_km"=>$row["coverage_km"],"description"=>$row["description"],"guide_available"=>$row["guide_available"],"status"=>$row["status"],"fare"=>$row1["fair"],"nationality"=>$row1["nationality"],"guide_charge"=>$row3["guide_charge"]);
		}
		
		$_SESSION['SightseeingDetails'] = $SightseeingDetails; 
			
		 $sql_shows="SELECT * FROM tbl_sightseeing WHERE city_id='2' and ShowsActivityStatus='1'";
		$result_shows=mysqli_query($this->con,$sql_shows)or die(mysqli_error($this->con));
	    $ShowsDetails=array();
		while($row_shows=mysqli_fetch_array($result_shows))
		{
			  $s_id =$row_shows['s_id'];
			
		    $sql1_shows="SELECT * FROM tbl_sightseeingfair WHERE s_id='$s_id'";
		    $result1_shows=mysqli_query($this->con,$sql1_shows)or die(mysqli_error($this->con));
		    $row1_shows=mysqli_fetch_array($result1_shows);
			//echo "S. fare - ".$row1['fair']."<br/>";
			 $row1_shows['nationality']."<br/>";
			
			//echo "Guide Fare - ".$row3['guide_charge'];
	
			  $ShowsDetails[]=array("s_id"=>$row_shows['s_id'],"name"=>$row_shows['name'],"image"=>$row_shows["image"],"coverage_km"=>$row_shows["coverage_km"],"description"=>$row_shows["description"],"guide_available"=>$row_shows["guide_available"],"status"=>$row_shows["status"],"fare"=>$row1_shows["fair"],"nationality"=>$row1_shows["nationality"]);
		}
		 
		$_SESSION['ShowsDetails'] = $ShowsDetails; 
		
	    $sql2="SELECT * FROM tbl_guidedetails WHERE City='New Delhi'";
		$result2=mysqli_query($this->con,$sql2)or die(mysqli_error($this->con));
		$guideDetails=array();
		while($row2=mysqli_fetch_array($result2))
		{
			//echo $row2['FirstName']."\t";
			$guideDetails[]=array("FirstName"=>$row2['FirstName']);
			
		}
		$_SESSION['guideDetails'] = $guideDetails;

		$sql_guidefare="SELECT * FROM tbl_guidefare WHERE city_id='1' or city_id='2'";
		$result_guidefare=mysqli_query($this->con,$sql_guidefare)or die(mysqli_error($this->con));
		$guide_fare=array();
		while($row_guidefare=mysqli_fetch_array($result_guidefare))
		{
			//echo $row_guidefare['nationality']."\t";
			//echo $row_guidefare['language']."\t";
			//echo $row_guidefare['guide_charge']."\t";
			$guide_fare[]=array("nationality"=>$row_guidefare['nationality'],"language"=>$row_guidefare['language'],"guide_charge"=>$row_guidefare['guide_charge']);
			
		}
		$_SESSION['guide_fare'] = $guide_fare; 
		//echo  $_SESSION['dayWise_cabcharge'];
		 
	
	/* $res=array();
	$request=$this->getRequest();
	if($request->isPost()){
		//$res['optionsInlineRadios']=$request->getPost('optionsInlineRadios');
		//$res['intercityMobile']=$request->getPost('intercityMobile');
		//$res['intercityEmail']=$request->getPost('intercityEmail');
		 $res['outRoundtripfrom']=$request->getPost('outRoundtripfrom');
		 $res['outRoundtripto']=$request->getPost('outRoundtripto');
		 $res['outstation_Ddate']=$request->getPost('outstation_Ddate');
		 $res['outstation_Rdate']=$request->getPost('outstation_Rdate');
		 $res['outstationNationality']=$request->getPost('outstationNationality');
		 $res['outstationAdults']=$request->getPost('outstationAdults');
		 $res['outstationChilds']=$request->getPost('outstationChilds');
		 $res['outstationLuggages']=$request->getPost('outstationLuggages');
		 $res['outPickupAddress']=$request->getPost('outPickupAddress');
		$res['outPickupTimeH']=$request->getPost('outPickupTimeH');
		$res['outPickupTimeM']=$request->getPost('outPickupTimeM');
		 $res['cartype']=$request->getPost('cartype')."<br/>";
		//echo $res['intercityPickupTimeM']=$request->getPost('intercityPickupTimeM');
		$res['BookingType']=$request->getPost('BookingType');
		$BookingType=$res['BookingType'];
	    $carType=$res['cartype'];
		$from_city=$res['outRoundtripfrom'];
		$to_city=$res['outRoundtripto']; 
		$nationality = $res['outstationNationality'];
		
		 $res['cartype']=$request->getPost('cartype')."<br/>";
		 $res['capacity']=$request->getPost('capacity')."<br/>";
		 $res['cabname']=$request->getPost('cabname')."<br/>";
		 $res['CabModel']=$request->getPost('CabModel')."<br/>";
		 $res['MinimumCharge']=$request->getPost('MinimumCharge')."<br/>";
		 $res['outstation_car']=$request->getPost('outstation_car')."<br/>";
		
		
		
		return new ViewModel(array('data'=>$res,'SightseeingDetails'=>$SightseeingDetails,'guideDetails'=>$guideDetails));
	}		 */
    }
}
