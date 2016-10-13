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
use Tunneling\Model\CustomerCare;
use Tunneling\Model\DatabaseConfig;

class admin{
	
	protected $con;
		
	public function __construct()
	{
		
		error_reporting(false);
            date_default_timezone_set("Asia/Kolkata");
		$db=new DatabaseConfig();
		$this->con=$db->getDatabaseConfig();
		//$this->con=mysqli_connect("10.0.0.35","root","root","hello42_new");
		//$this->con=mysqli_connect("localhost","root","","hello42_new");
					// Check connection
				 //mysqli_select_db($con, "hello42_new");
                //                  file_put_contents('gmail.txt',print_r($_POST,true));   
                                  
            foreach($_POST as $s=>$v)
            {
                $_POST[$s]=$this->removeSqlInjection($v);
                
            }
		//file_put_contents('gmail.txt',  print_r($_POST,true));			
                if (mysqli_connect_errno())
					  {
					  // "Failed to connect to MySQL: " . mysqli_connect_error();
					  return array("Status" => "Failed to connect to MySQL");
					  }
					  return array("Status" => "ram");
		
	}
	
	public function track_lat(){
		$data=array();
		$data['DriverInfo'] = array();
		$data_wait = array();
		$id=$_REQUEST['data'];
//		$time=mysqli_fetch_array(mysqli_query($this->con,"SELECT updateOn FROM tblbookingregister WHERE bookingid='$id' and `status`='A'")or die(mysqli_error($this->con)));
//		$time2=$time[0];
//		$sql = "SELECT tbluser.*,tblcabbooking.PickupAddress AS p,tblcabbooking.DropArea as d,tblcabbooking.PickupLatitude as lat ,tblcabbooking.PickupLongtitude as lon,tblcabbooking.DestinationLatitude as dlat,tblcabbooking.DestinationLongtitude as dlon  FROM tbluser JOIN tblcabbooking ON tblcabbooking.pickup=tbluser.id WHERE tblcabbooking.id='$id'";
		$sql = "SELECT tblcabbooking.ID,
						 tblcabbooking.booking_reference,
						 tblcabbooking.ClientID,
						 tblcabbooking.pickup,
						 tblcabstatus.status as cabstatus,
						 tblcabbooking.PickupAddress AS p,
						 tblcabbooking.PickupLatitude as lat ,
						 tblcabbooking.PickupLongtitude as lon,
						 tblcabbooking.DropAddress as d,
						 tblcabbooking.final_lat as dlat,
						 tblcabbooking.final_long as dlon,
						 CONCAT(tbluserinfo.FirstName,' ',tbluserinfo.LastName) ClientName,
						 tbluser.UserNo AS ClientMobileNumber,
						 tblcabbooking.`Status` AS BookingStatus
				FROM tblcabbooking  
				INNER JOIN tbluser ON tblcabbooking.ClientID = tbluser.ID
				INNER JOIN tbluserinfo ON tbluser.ID = tbluserinfo.UID
				INNER JOIN tblcabstatus ON tblcabbooking.`Status` = tblcabstatus.status_id
				WHERE tblcabbooking.id='$id' and tblcabstatus.`type`='cab' or tblcabbooking.booking_reference = '$id' and tblcabstatus.`type`='cab'";

		$query=mysqli_query($this->con,$sql)or die(mysqli_error($this->con));
		$data=mysqli_fetch_assoc($query);
		
		$data['DriverName'] = '';
		$data['DriverMobileNumber'] = '';
		$data['CabRegistrationNumber'] = '';
		
		if($data['pickup']){
			$sql = "SELECT CONCAT(tbluserinfo.FirstName,' ',tbluserinfo.LastName) AS DriverName,
					 tbluser.UserNo AS DriverMobileNumber,
					 tblcabmaster.CabRegistrationNumber
					FROM tbluser
					INNER JOIN tbluserinfo on tbluser.ID = tbluserinfo.UID
					INNER JOIN tbldriver on tbluser.ID = tbldriver.UID
					INNER JOIN tblcabmaster on tbldriver.vehicleId = tblcabmaster.CabId 
					WHERE tbluser.ID = '".$data['pickup']."'";
					
			$qry = mysqli_query($this->con,$sql);
			$data_driver = mysqli_fetch_assoc($qry);
			
			$data['DriverName'] = $data_driver['DriverName'];
			$data['DriverMobileNumber'] = $data_driver['DriverMobileNumber'];
			$data['CabRegistrationNumber'] = $data_driver['CabRegistrationNumber'];
			
			$sql = "select * from tbldriverlocation
where tbldriverlocation.user_id = '".$data['pickup']."' and tbldriverlocation.booking_id = '".$data['ID']."'
order by tbldriverlocation.id DESC LIMIT 1";
			$qry = mysqli_query($this->con, $sql);
			$row = mysqli_fetch_assoc($qry);
			$data['DriverLat'] = $row['lat'];
			$data['DriverLon'] = $row['longi'];
			
			$sql = "SELECT * FROM tblbookingdistance WHERE WaitingTime > 0 AND booking_id = '".$data['ID']."'";
			$qry = mysqli_query($this->con,$sql);
			while($row = mysqli_fetch_assoc($qry)){
				$from = explode(',',$row['from']);
				$row['WaitLat'] = $from[0];
				$row['WaitLon'] = $from[0];
				array_push($data_wait, $row);
			}	
			
			$data['Waiting'] = $data_wait;		
		}
		return array("data"=>$data);  
	}
        
	public function full_lat(){
		$id=$_REQUEST['data'];
		$time=mysqli_fetch_array(mysqli_query($this->con,"SELECT updateOn FROM tblbookingregister WHERE bookingid='$id' and `status`='A'"));
		$time_e=mysqli_fetch_array(mysqli_query($this->con,"SELECT `time` FROM tblbookinglogs WHERE bookingid='$id' and `status`=8"));
		$time2=$time[0];
		$time_e2=$time_e[0];
		$query=mysqli_query($this->con,"SELECT tbldriverlocation.* FROM tbldriverlocation JOIN tblcabbooking WHERE `tbldriverlocation`.datetime>'$time2' GROUP BY(tbldriverlocation.lat)")or die(mysqli_error());
		$data=array();
		while($row=mysqli_fetch_assoc($query)){
			$data[]=$row;
		}
		return array("data"=>$data);    
	}
        
	public function changetime(){
		$id=$_POST['id'];
		$time=explode(' ',$_POST['time']);
	  
		$date1=explode('/',$time[0]);
		$date=$date1[2].'-'.$date1[0].'-'.$date1[1];
		$time=$time[1].":00";
		
		mysqli_query($this->con,"UPDATE tblcabbooking SET `pickupdate`='$date',`pickuptime`='$time' WHERE id='$id'");
		return array("status"=>"true");
	}
	  public function cancelbooking(){
		$id=$_POST['id'];
		
		mysqli_query($this->con,"UPDATE tblcabbooking SET `status`='16' WHERE id='$id'");
		return array("status"=>"true");
	}
        
        public function removeSqlInjection($data)
        {

         //$arr = array("%","'","=","-","*str_r");
         $res = addslashes($data);

         return $res;
        }
        public function login()
        {
			 $con=$this->con;
			 $username=$_POST['username'];
			 $password=md5($_POST['password']);
		
			 //$ipaddress=$_SERVER['REMOTE_ADDR'];
			 if (!empty($_SERVER['HTTP_CLIENT_IP'])){
			  $ip=$_SERVER['HTTP_CLIENT_IP'];
			//Is it a proxy address
			}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
			}else{
			  $ip=$_SERVER['REMOTE_ADDR'];
			}
			//The value of $ip at this point would look something like: "192.0.34.166"
			//$ip = ip2long($ip);
			 
			$procredure= "CALL `sp_w_adminlogin`('$username','$password','$ip')";     
			$res=mysqli_query($con,$procredure);
			 $result=$res->fetch_assoc();      
			if($result['check_login']==0)
			{
				return array('status'=>'false','code'=>'1002');

			}else{
				 $user_session = new Container('admin');
				 foreach($result as $u=>$v)$user_session->offsetSet($u,$v);
				 $user_session->offsetSet("adminuser",$result['email']);
				
				return array('status'=>'true','code'=>'1001');
			} 
        }
		
		public function test_ip()
		{
			echo $_SERVER['SERVER_ADDR']; die;
		}
		function get_client_ip_env() {
			$ipaddress = '';
			if (getenv('HTTP_CLIENT_IP'))
				$ipaddress = getenv('HTTP_CLIENT_IP');
			else if(getenv('HTTP_X_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
			else if(getenv('HTTP_X_FORWARDED'))
				$ipaddress = getenv('HTTP_X_FORWARDED');
			else if(getenv('HTTP_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_FORWARDED_FOR');
			else if(getenv('HTTP_FORWARDED'))
				$ipaddress = getenv('HTTP_FORWARDED');
			else if(getenv('REMOTE_ADDR'))
				$ipaddress = getenv('REMOTE_ADDR');
			else
				$ipaddress = 'UNKNOWN';
		 
			echo  $ipaddress;
		}
		
        public function admin_text(){
            $id=$_REQUEST['id'];
            $key=mysqli_query($this->con,"CALL `sp_w_admin_text`('$id',2)") or die(mysqli_error($this->con));
               $key_cal=array();
             while($row=mysqli_fetch_array($key))
           {
               $key_cal[addslashes($row['field'])]=$row['text'];
           }
            
            return array('key'=>$key_cal);
        } 
        
        
        public function admin_text_module(){
            $module=$_REQUEST['module'];
            $key=mysqli_query($this->con,"CALL `sp_w_admin_text_module`('$module',2)") or die(mysqli_error($this->con));
               $key_cal=array();
             while($row=mysqli_fetch_array($key))
           {
               $key_cal[addslashes($row['field'])]=$row['text'];
           }
            
            return array('key'=>$key_cal);
        } 
        
	public function client_list(){
		$key=mysqli_query($this->con,"CALL `sp_w_client_list`()") or die(mysqli_error($this->con));
		$key_cal=array();
		while($row=mysqli_fetch_array($key)){
		$key_cal[]=array('<input type="checkbox" value="" name="MobileNumber[]" class="client_check">','<a class="myclient">'.$row['id'].'</a>',$row['name'],$row['ContactNumber'],$row['Email'],$row['booking'],$row['create_date']);
		}
		// print_r($key_cal);die;
		//return array('data'=>$key_cal);
		if(mysqli_num_rows($key) >0 ){
            return array("status"=>"true","data"=>$key_cal);
        }else{
            return array("status"=>"false","data"=>"");
        }
		
	}
		
        public function city_list(){
                header("Access-Control-Allow-Origin: *");
            $query=mysqli_query($this->con,"SELECT name FROM tblcity WHERE ncr=1");
            $data=array();
            while($result=mysqli_fetch_assoc($query))
            {
                $data[]=$result['name'];
                
            }
             return array("status"=>"true","data"=>$data);
        }
        
        public function client_profile(){
            $id=$_REQUEST['id'];
         
            $key=mysqli_query($this->con,"CALL `sp_w_client_info`('$id')") or die(mysqli_error($this->con));
               $key_cal=mysqli_fetch_array($key);

   $val=array(
              "id"=>$key_cal['ID'],
			  "referralKey"=>$key_cal['referralKey'],
              'first'=>$key_cal['FirstName'],
              'last'=>$key_cal['LastName'],
              'username'=>$key_cal['LoginName'],
              'contact'=>$key_cal['MobNo'],
			  'lang'=>$key_cal['language'],
			  'img'=>$key_cal['image'],
              'date'=>$key_cal['create_date'],
			  'AltEmail'=>$key_cal['AltEmail'],
              'AlternateContactNo'=>$key_cal['AlternateContactNo'],
              'Country'=>$key_cal['Country'],
              'State'=>$key_cal['State'],
			  'City'=>$key_cal['City'],
			  'Address1'=>$key_cal['Address1'],
			  'Address2'=>$key_cal['Address2'],
              'PinCode'=>$key_cal['PinCode']
              ); 
			 // print_r($val);die;
           return array("data"=>$val);
        }
		
		public function updateClientProfileImage(){
				if(isset($_FILES['avatar_file']) and $_FILES['avatar_file'] != ''){
					$id=$_REQUEST['client_id']; 
					$ImgName=uniqid().'_'.basename($_FILES['avatar_file']["name"]);
					$TempName=$_FILES['avatar_file']['tmp_name'];
					$target_path = "public/userimage/$id/";
                    if (!file_exists($target_path)) 
					{
						mkdir($target_path, 0777, true);
					}
		            $server_ip = gethostbyname(gethostname());
			      $target = $target_path.$ImgName;				 
			     //$file_upload_url = 'http://' . $server_ip . '/' . 'hello42' . '/' . $target;// localhost 				 
					if ($mess=!move_uploaded_file($TempName, $target)) {
						return(array("status"=>false,"Message"=>"Error in Uploading Picture"));					
					}else{							
						$query="UPDATE tbluserinfo SET image='$target' WHERE UID ='$id'";
						$result =  mysqli_query($this->con,$query) or die(mysqli_error($this->con));
						return(array("status"=>true,"Message"=>"Profile Picture Updated Successfully"));	
					}
				}
				return(array("status"=>false,"Message"=>"Un-Authorize Access"));          	
	}
		
        public function dashboard(){

            $id=2;
            $val=mysqli_query($this->con,"CALL `sp_w_admin_driver`('$id')") or die(mysqli_error($this->con));
           $val_cal=mysqli_fetch_assoc($val);
            
            mysqli_free_result($val);   
           mysqli_next_result($this->con);
           
           $key=mysqli_query($this->con,"CALL `sp_w_admin_text`(1,2)") or die(mysqli_error($this->con));
           $key_cal=array();
           $date_arr=array();
           $date_new=date('Y-m-d');
           for($i=0;$i<15;$i++)
           {
               $date_arr[$i]=date('j-M',strtotime($date_new. '+'.$i.'days'));
           }
           while($row=mysqli_fetch_array($key))
           {
               $key_cal[addslashes($row['field'])]=$row['text'];
           }
           mysqli_free_result($key);   
           mysqli_next_result($this->con);
           
           $date=mysqli_query($this->con,"CALL `sp_w_list_date_booking`('$date_new')") or die(mysqli_error($this->con));
           $date_list=array();
            while($row=mysqli_fetch_array($date))
           {
               $date_list[$row['date']]=$row['no_of'];
           }
             mysqli_free_result($date);   
           mysqli_next_result($this->con);
           
           $hour=mysqli_query($this->con,"CALL `sp_w_list_hour_booking`('$date_new','0')") or die(mysqli_error($this->con));
           $hour_list=array();
            while($row=mysqli_fetch_array($hour))
           {
               $hour_list["hour_".$row['hour']]=$row['no_of'];
           }
           // print_r($hour_list);die;
           mysqli_free_result($hour);   
           mysqli_next_result($this->con);
           
           $hour_wise=mysqli_query($this->con,"CALL `sp_w_list_booking`('$date_new')") or die(mysqli_error($this->con));
           $hour_wise_list=array();
            while($row=mysqli_fetch_assoc($hour_wise))
           {
              //array_push($hour_wise_list,array('id'=>$row[id],'hour'=>$row['hour'],'clientname'=>$row['clientname'],'contact'=>$row['ContactNo']));
          array_push($hour_wise_list,$row);
              }
           //echo '<pre>';
           //print_r($key_cal);
          
      
            return array('date'=>$date_arr,'key'=>$key_cal,'value'=>$val_cal,'date_a'=>$date_list,'hour'=>$hour_list,'hour_wise'=>$hour_wise_list);
            
            
        }
        
    
	public function driver_lat(){
		///*tblcabbooking.`Status` as cabStatus,*/
		header("Access-Control-Allow-Origin: *");
		$sql = "SELECT 
					CONCAT(tbluserinfo.FirstName,' ',tbluserinfo.LastName) as name, 
					tbluser.UserNo AS ContactNo,
					tblcabmaster.CabRegistrationNumber AS VehicleRegistrationNo,
					tblcabmaster.CabManufacturer AS MakeOfVehicle,
					tbluser.id, 
					tbluser.LoginName,
					tbluser.Latitude,
					tbluser.Longtitude1,
					IFNULL(tbldriver.`status`,0) as driverStatus ,
					tbluser.loginStatus,
					IFNULL(tblcabbooking.`Status`,0) as cabStatus,
					tblcabmaster.CabName as vehicleName 
				FROM tbluser
				INNER JOIN tbluserinfo ON tbluser.ID = tbluserinfo.UID
				INNER JOIN tbldriver ON tbluser.ID = tbldriver.UID
				INNER JOIN tblcabmaster ON tbldriver.vehicleId = tblcabmaster.CabId
				LEFT JOIN tblcabbooking ON tbluser.ID = tblcabbooking.pickup
				WHERE tbluser.UserType = 3 AND tbluser.isVerified = 1 AND tbluser.Latitude != '' AND tbluser.Longtitude1 != ''
				GROUP BY tbluser.ID
				ORDER BY tblcabbooking.ID DESC";		
		$query=mysqli_query($this->con,$sql);
		$data=array();
		while($row=mysqli_fetch_assoc($query)){
			//echo $row['driverStatus'];
			//echo $row['cabStatus'];
			//die;
			if(($row['driverStatus'] == 0 and $row['loginStatus'] == 1)){
				$row['map']="Free";
			}elseif($row['driverStatus']== 1 and $row['loginStatus'] == 1 and $row['cabStatus'] == 2){
				$row['map']="OnCall";
			}elseif($row['driverStatus']== 1 and $row['loginStatus'] == 1 and $row['cabStatus'] == 3){
				$row['map']="Accepted";
			}elseif($row['driverStatus']== 1 and $row['loginStatus'] == 1 and $row['cabStatus'] == 5){
				$row['map']="Reported";
			}elseif($row['driverStatus']== 1 and $row['loginStatus'] == 1 and $row['cabStatus'] == 7){
				$row['map']="hired";
			}elseif($row['driverStatus']== 0 and $row['loginStatus'] == 0){
				$row['map']="logout";
			}
			//if($row['cabStatus']=='null')
			$data[]=$row;
		}
		return array('data'=>$data);
	}
	
	public function getAllCabStatus(){
		
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
		$data['available_driver'] = $Free;
		$data['on_call'] = $OnCall;
		$data['accepted'] = $Accepted;
		$data['Located'] = $Located;
		$data['reported'] = $Reported;
		$data['hired'] = $Hired;
		$data['NotMoving'] = $NotMoving;
		$data['NotMovingDriverIds'] = implode(',',$NotMovingDriverIds);
		$data['log_out'] = $Logout;
		$data['total_driver'] = $Total;
		$data['free_moving'] = $Total;
		$data['logout_moving'] = $Total;
		return array('data'=>$data);
	}
	
	public function getNotMoving(){
		$row = array();
		$data = array();
		$DriverIds = $_REQUEST['NotMovingDriverIds'];
		$sql = "select concat(tbluserinfo.FirstName,' ',tbluserinfo.LastName) AS DriverName,
				tbluser.UserNo as MobileNumber,
				tblcabmaster.CabRegistrationNumber,
				tblcabtype.CabType,
				(select WaitingTime from tbldriverlocation where user_id = tbluser.ID order by id desc limit 1) as WaitingTime
				from tbluser
				left join tbluserinfo on tbluser.ID = tbluserinfo.UID
				left join tbldriver on tbluser.ID = tbldriver.UID
				left join tblcabmaster on tbldriver.vehicleId = tblcabmaster.CabId
				left join tblcabtype on tblcabmaster.CabType = tblcabtype.Id where tbluser.ID in ($DriverIds)";
		$qry = mysqli_query($this->con,$sql);
		while($row = mysqli_fetch_assoc($qry)){
			$data[] = $row;
		}
		return array('data'=>$data);
	}
	
	public function tracking(){
		
		$data = array();
		$row = array();
		$Sr = 1;
		
		$sql = "SELECT tbluser.ID, 
						 CONCAT(tbluserinfo.FirstName,' ',tbluserinfo.LastName) AS DriverName,
						 tblcabmaster.CabRegistrationNumber,
						 tblignitiontype.CabIgnitionType,
						 tbluser.UserNo as ContactNumber,
						 tbluser.loginStatus,
						 tbldriver.`status` AS DriverStatus,
						 (SELECT tblcabbooking.`Status` FROM tblcabbooking WHERE tblcabbooking.pickup = tbluser.ID ORDER BY tblcabbooking.ID DESC LIMIT 1) AS BookingStatus
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
				
		$qry = mysqli_query($this->con,$sql);

		while($row = mysqli_fetch_object($qry)){
			$Treck = $this->getLocation($row->ID);	
			$status = 'Unknown';	
			if($row->loginStatus == 0){
				$status = 'Logout';
			}elseif($row->loginStatus == 1 and $row->DriverStatus == 0){
				$status = 'Free';
			}elseif($row->loginStatus == 1 and $row->DriverStatus == 1 and $row->BookingStatus == 2){
				$status = 'On Call';
			}elseif($row->loginStatus == 1 and $row->DriverStatus == 1 and $row->BookingStatus == 3){
				$status = 'Accepted';
			}elseif($row->loginStatus == 1 and $row->DriverStatus == 1 and $row->BookingStatus == 4){
				$status = 'Located';
			}elseif($row->loginStatus == 1 and $row->DriverStatus == 1 and $row->BookingStatus == 5){
				$status = 'Reported';
			}elseif($row->loginStatus == 1 and $row->DriverStatus == 1 and $row->BookingStatus == 10){
				$status = 'Canceled';
			}elseif($row->loginStatus == 1 and $row->DriverStatus == 1 and $row->BookingStatus == 7){
				$status = 'Hired';
			}
			//$status = $status.'-'.$row->loginStatus.'-'.$row->DriverStatus.'-'.$row->BookingStatus;
			$data[] = array("$Sr",$row->CabRegistrationNumber,'',$row->DriverName,$row->ContactNumber,$Treck['Date_Time'],'',$row->CabIgnitionType,'<acronym title="'.$Treck['PickupAddress'].'">'.substr($Treck['PickupAddress'],0,25).'</acronym>','<acronym title="'.$Treck['Address'].'">'.substr($Treck['Address'],0,25).'</acronym>',$status);
			$Sr++;
		}
		return array("data"=>$data);
		
//		$result=mysqli_query($this->con,"CALL `sp_driver_tracking_list`()") or die(mysqli_error($this->con));
//		$a=1;     
//		$data_list=array();
//		while($data=mysqli_fetch_assoc($result)){
//			$destination="";
//			if($data['loginStatus']==1 && $data['status']==1){
//				$status="Hired";
//				$destination=$data['DropArea'];
//			}else{
//				if($data['loginStatus']==1 && $data['status']==0 ){
//					$status="Free";  
//				}else if($data['loginStatus']==0 && $data['status']==0){
//					$status="Logout";  
//				}
//			}
//			$Sr = $a++;
//			$data_list[]=array('',"$Sr",$data['vehicleNumber'],'',$data['name'],$data['ContactNo'],'','','',$destination,'',$status);	
//		}
//		return array("data"=>$data_list);
	}

        
	public function getCabInfo(){
		
		$cond = '';
		if($_REQUEST['SearchKey']){
			$cond .= "AND (tbluser.ID = '".$_REQUEST['SearchKey']."' OR tbluser.UserNo = '".$_REQUEST['SearchKey']."' OR tblcabmaster.CabRegistrationNumber = '".$_REQUEST['SearchKey']."' OR CONCAT(tbluserinfo.FirstName,' ',tbluserinfo.LastName) LIKE '%".$_REQUEST['SearchKey']."%')";
		}
		
		if($_REQUEST['CabStatus'] == 'Free'){
			$cond .= "AND loginStatus = '1' AND driverStatus = 0";
		}elseif($_REQUEST['CabStatus'] == 'Accepted'){
			$cond .= "AND loginStatus = '1' AND driverStatus = 1 AND cabStatus = 3";
		}elseif($_REQUEST['CabStatus'] == 'Located'){
			$cond .= "AND loginStatus = '1' AND driverStatus = 1 AND cabStatus = 4";
		}elseif($_REQUEST['CabStatus'] == 'OnCall'){
			$cond .= "AND loginStatus = '1' AND driverStatus = 1 AND cabStatus = 2";
		}elseif($_REQUEST['CabStatus'] == 'Reported'){
			$cond .= "AND loginStatus = '1' AND driverStatus = 1 AND cabStatus = 5";
		}elseif($_REQUEST['CabStatus'] == 'Hired'){
			$cond .= "AND loginStatus = '1' AND driverStatus = 1 AND cabStatus = 7";
		}elseif($_REQUEST['CabStatus'] == 'Logout'){
			$cond .= "AND loginStatus = '0'";
		}elseif($_REQUEST['CabStatus'] == 'FreeMoving'){
			$cond .= '';
		}elseif($_REQUEST['CabStatus'] == 'LogoutMOving'){
			$cond .= '';
		}else{
			$cond .= '';
		}
		
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
					tbluser.Latitude AS Latitude,
					tbluser.Longtitude1 AS Longtitude1,
					tbldriver.`status` as driverStatus ,
					tbluser.loginStatus,
					(SELECT tblcabbooking.`Status` FROM tblcabbooking WHERE tblcabbooking.pickup = tbluser.ID ORDER BY tblcabbooking.ID DESC LIMIT 1) as cabStatus,
					tblcabmaster.CabName as vehicleName, 
					tbluser.UserType AS UserType,
					tbluser.isVerified AS IsVerified
				FROM tbluser
				INNER JOIN tbluserinfo ON tbluser.ID = tbluserinfo.UID
				INNER JOIN tbldriver ON tbluser.ID = tbldriver.UID
				INNER JOIN tblcabmaster ON tbldriver.vehicleId = tblcabmaster.CabId
				INNER JOIN tblignitiontype ON tblcabmaster.CabIgnitionTypeId = tblignitiontype.id
				INNER JOIN tblcabtype ON tblcabmaster.CabType = tblcabtype.Id
				HAVING UserType = 3 
				AND isVerified = 1 
				AND Latitude != '' 
				AND Longtitude1 != '' $cond";
				

		$query=mysqli_query($this->con,$sql);
		$data=array();
		while($row=mysqli_fetch_assoc($query)){
			
			$CabLocation = array();
			
			$map = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.$row['Latitude'].','.$row['Longtitude1'].'&sensor=true');
			$map = json_decode($map);
			$FullAddress = explode(',',$map->results[0]->formatted_address);
			for($a=0;$a<(count($FullAddress) - 3);$a++){
				array_push($CabLocation,$FullAddress[$a]);
			}
			$row['Location'] = implode(',',$CabLocation); 

			if(($row['driverStatus'] == 0 and $row['loginStatus'] == 1)){
				$row['CabStatus']="Free";
			}elseif($row['driverStatus']== 1 and $row['loginStatus'] == 1 and $row['cabStatus'] == 2){
				$row['CabStatus']="OnCall";
			}elseif($row['driverStatus']== 1 and $row['loginStatus'] == 1 and $row['cabStatus'] == 3){
				$row['CabStatus']="Accepted";
			}elseif($row['driverStatus']== 1 and $row['loginStatus'] == 1 and $row['cabStatus'] == 4){
				$row['CabStatus']="Located";
			}elseif($row['driverStatus']== 1 and $row['loginStatus'] == 1 and $row['cabStatus'] == 5){
				$row['CabStatus']="Reported";
			}elseif($row['driverStatus']== 1 and $row['loginStatus'] == 1 and $row['cabStatus'] == 7){
				$row['CabStatus']="Hired";
			}elseif($row['driverStatus']== 0 and $row['loginStatus'] == 0){
				$row['CabStatus']="Logout";
			}
			$data[]=$row;
		}
		return array('data'=>$data);
	}
        
        
        
        
        
        
        
        
        public function driver_lat1(){
            
             
            $query=mysqli_query($this->con,"SELECT distinct(concat(tbldriver.FirstName,' ',tbldriver.LastName)) as name, tbldriver.ContactNo, tbldriver.VehicleRegistrationNo, tbldriver.MakeOfVehicle, tbluser.id, tbluser.LoginName,tbluser.Latitude,tbluser.Longtitude1,tbldriver.`status`,tbluser.loginStatus,tblcabbooking.`Status` FROM tbluser JOIN tbldriver ON tbluser.ID=tbldriver.UID JOIN tblcabbooking ON tbldriver.UID=tblcabbooking.pickup;");
            $data=array();
while($row=mysqli_fetch_assoc($query))
{
    if($row['Status']==5 && $row['loginStatus']==1)
    {
        
       $row['map']="Reported";
        
        
    } 
    else if($row['Status']==3 && $row['loginStatus']==1 && $row['status']==0 )
    {
      $row['map']="Accepted";  
        
    }
    else if($row['Status']==2 && $row['loginStatus']==1)
    {
       $row['map']="OnCall"; 
    }
        
        
	$data[]=$row;
}
            return array('data'=>$data);
            
        }
        
        
        
        
        
        
        public function booking_list(){
			
            $time=$_REQUEST['hour'];
            if(!isset($_REQUEST['date']))
            {
			 $sql="CALL `sp_w_booking_details`()";
             $hour=mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
            }else{
                $date=$_REQUEST['date'];
				$sql="CALL `sp_w_booking_details_date`('$date','$time')";
                $hour=mysqli_query($this->con,$sql) or die(mysqli_error($this->con)); 
                
            }
             $booking=array();
			
            while($row=mysqli_fetch_array($hour))
           {
			   mysqli_next_result($this->con);
			 if($row['emp']!=0){
			    $csr_id=$row['emp'];
				$agentSql="SELECT username FROM login WHERE id ='$csr_id'";
				$agentRes=mysqli_query($this->con,$agentSql) or die(mysqli_error($this->con)); 
			    $agentResult=mysqli_fetch_array($agentRes);
				$agentName=$agentResult['username'];
			   }else{
			     $agentName="";
			   }
                //print_r($row);
				if($row['totalBill']!="")
				{
					$row['totalBill']="INR ".$row['totalBill'];
					
				}
				if($row['reg']!=0)
				{
					$row['reg']="(".$row['reg'].")";
					
				}
				$complaint="";
				$comp_col="";
				if($row['com_id']!="")
				{
                 $complaint="<br><br><small>Complaint: ".$row['com_status']."</small>";
				 $comp_col="<div style='width:10px;height:10px;border-radius:100%;background:red'></div>";

				}
				
///// Code for calculate forcely bill starts here ///
					
/*if($row['status']!="Paid"){
$calc_bill="<br><small style='color:#08c; cursor:pointer;' class='calc_bill' booking_id='".$row['id']."' driver_id='".$row['driver_id']."'>Generate Bill</small>";					
}else{
$calc_bill="";
}*/
	
//// Code for calculate forcely bill ends here ////
					
		$find=substr($row['ref'],0,2);
		if($find=='HC'){
		$voice_file="<button class='voice_mail btn1 btn-red defaultbtnsearch' data='".$row['ref']."'>Record File</button>";
		}else{
		$voice_file="";
		}
               $hour_list[]=array('<label data="'.$row['id'].'" rel="'.$row['driver_id'].'" class="clickme" style="padding: 0px 10px 0px 0;color:#08c; cursor:pointer;">'.$row['ref'].'</label>','<label alt="'.$row['ordertime'].'">'.date("d-M-y H:i",strtotime($row['ordertime']))."<br><small style='color:gray'>".'</small>',"<div data='".$row['id']."'  style='cursor:pointer;' class='driver_click'>".$row['vehicle'].'<br/><small style="color:gray;text-transform:capitalize"  class="driver_click">'.$row['driver_name'].'</small><br><small>'.$row['reg'].'</small>'.$comp_col."</div>",$row['booking_type'].'<br>'.'<img style="margin-left:10px" src="'.$row['iconImage'].'" alt="">',$row['partner'].'<br>'.ucfirst($agentName),'<label class="client" data="'.$row['client_id'].'">'.$row['clientname'].'</label><br/><small style="color:gray">'.$row['mob_no'].'</small>',ucfirst($row['departure']),ucfirst($row['drop_area']),$row['status'].'<br/><small style="color:gray;text-transform:capitalize">'.$row['totalBill'].'</small>'.$complaint,$row['cshare']."%<br><small>(INR ".$row['cAmount'].")</small>",$row['dshare']."%<br><small>(INR ".$row['dAmount'].")</small>",$row['pshare']."%<br><small>(INR ".$row['pAmount'].")</small>","<label style='padding: 0px 10px 0px 0;color:#08c; cursor:pointer;' class='action' data='".$row['id']."'>Action</label><br/><button class='delay btn1 btn-red defaultbtnsearch' data='".$row['id']."'>Delay</button>$voice_file");
               
           }
            //return array("data"=>$hour_list);
			if(mysqli_num_rows($hour)>0)
            {
                return array("status"=>"true","data"=>$hour_list);
            }
            else
            {
                return array("status"=>"false","data"=>"");
            }
            
            
        }
        // previously used 
		// public function reserve_booking(){
		// 	$id=$_REQUEST['booking_id'];
		// 	$driver_id=$_REQUEST['driver_id'];
		// 	mysqli_query($this->con,"UPDATE tblcabbooking SET pickup='$driver_id',`status`='13' WHERE id='$id'");
			
		// 	return array("data"=>"true");   
		// }

        //
		public function reserve_booking(){
			$_REQUEST['CabType1'] = $_GET['bokref'].";".$_GET['driver_id'].";".$_GET['agent_id'];

			$cust = new CustomerCare();
			$resp = $cust->SwapTaxi($_REQUEST['CabType1']);

			if($resp){
				$_REQUEST['booking_id'] = substr($_GET['bokref'], 4).':accepted';
				$sendsms = new Menu();
				$smss =	$sendsms->send_sms($_REQUEST['booking_id']);
				 $response = array("status"=>1,"message"=>"Booking assigned successfully");
				
			}
			else $response = array("status"=>0,"message"=>"Some internal error occured. Sorry for inconvinience");

			header("ContentType:application/json");
			echo json_encode($response);
			die;
			// return array("data"=>"true");   
		}
		
		 public function booking_filter(){
            
		 	$driver=$_REQUEST['driver'];
			$partner=$_REQUEST['partner'];
			$ref=$_REQUEST['ref'];
			$type=$_REQUEST['type'];
			$vehicle_no=$_REQUEST['vehicle'];
            $createdSince = $_REQUEST['created_since'];
		    $createdTo = $_REQUEST['created_to'];
			$client_first = $_REQUEST['client_first'];
			$client_last = $_REQUEST['client_last'];
			$client_mob = $_REQUEST['client_mob'];
			$client_email = $_REQUEST['client_email'];
			$driver_first = $_REQUEST['driver_first'];
			$driver_last = $_REQUEST['driver_last'];
			$driver_ref = $_REQUEST['driver_ref'];
			$driver_email = $_REQUEST['driver_email'];
			$driver_mob = $_REQUEST['driver_mob'];
			$driver_ids = $_REQUEST['driver_ids'];
                 
                 
//		$closedSince = $_REQUEST['closed_since'];
//		$closedTo = $_REQUEST['closed_to'];
//		$complaintStatus_Text = $_REQUEST['complaint_Status_Text'];
		
		if($createdSince != "" && $createdTo == ""){
			$createdTo = date('Y-m-d');
		}elseif($createdSince == "" && $createdTo != ""){
			$createdSince = '0000-00-00';
		}elseif($createdSince == "" && $createdTo == ""){
			$createdSince = '0000-00-00';
			$createdTo = date('Y-m-d');
		}
			
			//file_put_contents("get.txt",print_r($_REQUEST,true));
             $hour=mysqli_query($this->con,"CALL `sp_booking_filter`('$driver','$partner','$ref','$type','$vehicle_no','$createdSince','$createdTo','$client_first','$client_last','$client_mob','$client_email','$driver_first','$driver_last','$driver_ids','$driver_ref','$driver_email','$driver_mob')") or die(mysqli_error($this->con));
           $booking=array();
		   
             while($row=mysqli_fetch_array($hour))
           {
			//echo $row['emp'];
			//mysqli_free_result($hour);   
			mysqli_next_result($this->con);

			if($row['emp']!=0){
			$csr_id=$row['emp'];
			$agentSql="SELECT username FROM login WHERE id ='$csr_id'";
			$agentRes=mysqli_query($this->con,$agentSql) or die(mysqli_error($this->con)); 
			$agentResult=mysqli_fetch_array($agentRes);
			$agentName=$agentResult['username'];
			}else{
			$agentName="";
			}
			
			 if($row['totalBill']!="")
				{
					$row['totalBill']="INR ".$row['totalBill'];
					
				}
				if($row['reg']!=0)
				{
					$row['reg']="(".$row['reg'].")";
					
				}
				$complaint="";
				$comp_col="";
				if($row['com_id']!="")
				{
                 $complaint="<br><br><small>Complaint: ".$row['com_status']."</small>";
				 $comp_col="<div style='width:10px;height:10px;border-radius:100%;background:red'></div>";

				}

				///// Code for calculate forcely bill starts here ///
								
				/*if($row['status']!="Paid"){
				$calc_bill="<br><small style='color:#08c; cursor:pointer;' class='calc_bill' booking_id='".$row['id']."' driver_id='".$row['driver_id']."'>Generate Bill</small>";					
				}else{
				$calc_bill="";
				}*/
				
				//// Code for calculate forcely bill ends here ////
					
               $hour_list[]=array('<label data="'.$row['id'].'" rel="'.$row['driver_id'].'" class="clickme" style="padding: 0px 10px 0px 0;color:#08c; cursor:pointer;">'.$row['ref'].'</label>',date("d-M-y H:i",strtotime($row['ordertime']))."<br><small style='color:gray'>".'</small>',"<div data='".$row['id']."'  style='cursor:pointer;' class='driver_click'>".$row['vehicle'].'<br/><small style="color:gray;text-transform:capitalize"  class="driver_click">'.$row['driver_name'].'</small><br><small>'.$row['reg'].'</small>'.$comp_col."</div>",$row['booking_type'],$row['partner'].'<br>'.ucfirst($agentName),'<label class="client" data="'.$row['client_id'].'">'.$row['clientname'].'</label><br/><small style="color:gray">'.$row['mob_no'].'</small>',ucfirst($row['departure']),ucfirst($row['drop_area']),$row['status'].'<br/><small style="color:gray;text-transform:capitalize">'.$row['totalBill'].'</small>'.$complaint,"C-Share:".$row['cshare']."%<br><small>(INR ".$row['cAmount'].")</small>","D-Share:".$row['dshare']."%<br><small>(INR ".$row['dAmount'].")</small>","P-Share:".$row['pshare']."%<br><small>(INR ".$row['pAmount'].")</small>","<label style='padding: 0px 10px 0px 0;color:#08c; cursor:pointer;' class='action' data='".$row['id']."'>Action</label>");
//               $hour_list[]=array('<label data="'.$row['id'].'" class="clickme" style="padding: 0px 10px 0px 0;color:#08c; cursor:pointer;">'.$row['ref'].'</label>',date("M-j-y H:i",strtotime($row['ordertime']))."<br><small style='color:gray'>".'</small>',"<div data='".$row['id']."'  style='cursor:pointer;' class='driver_click'>".$row['vehicle'].'<br/><small style="color:gray;text-transform:capitalize"  class="driver_click">'.$row['driver_name'].'</small><br><small>'.$row['reg'].'</small>'.$comp_col."</div>",$row['booking_type'],$row['partner'].'<br>'.ucfirst($row['emp']),'<label class="client" data="'.$row['client_id'].'">'.$row['clientname'].'</label><br/><small style="color:gray">'.$row['mob_no'].'</small>',ucfirst($row['departure']),ucfirst($row['drop_area']),$row['status'].'<br/><small style="color:gray;text-transform:capitalize">'.$row['totalBill'].'</small>'.$complaint,"<label style='padding: 0px 10px 0px 0;color:#08c; cursor:pointer;' class='action' data='".$row['id']."'>Action</label>");
               
           }
           if(mysqli_num_rows($hour)>0)
            {
                return array("status"=>"true","data"=>$hour_list);
            }
            else
            {
                return array("status"=>"false","data"=>"");
            }
            
            
        }
        public function booking_filter1(){
                 $booking_status=$_REQUEST['status'];
				// $booking_status=13;
				 if(($booking_status>=13 && $booking_status<=17)  || $booking_status==50 || $booking_status==51 || $booking_status==53 || $booking_status==55){
				$sql1="SELECT tblcabstatus.status from tblcabstatus INNER JOIN tblcabbooking ON tblcabbooking.StatusC=tblcabstatus.status_id WHERE tblcabbooking.StatusC='$booking_status' LIMIT 0,1";
				$res=mysqli_query($this->con,$sql1) or die(mysqli_error($this->con));
				$val=mysqli_fetch_array($res);
				$status=$val['status'];
				$status=str_replace(' ','_',$status);
			//for LIVE Purpose			
			//$sql="CALL `sp_booking_filter_2`('$booking_status',1,'$status')";
			//for Testing Purpose			
			$sql="CALL `New_filter_search`('$booking_status',1,'$status')";
				
			}else{
			$status=1;
			
			//for testing Purpose
			$sql="CALL `New_filter_search`('$booking_status',0,'$status')";
			//for LIVE Purpose							
			//$sql="CALL `sp_booking_filter_2`('$booking_status',0,'$status')";

			//file_put_contents("get.txt",print_r($_REQUEST,true));
            // 
			//$hour=mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
           
			 }
			 //echo $sql;
			$hour=mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
			//$row=mysqli_fetch_array($hour);
			//print_r($hour); die;
           $booking=array();
		   
             while($row=mysqli_fetch_array($hour))
           {
			 if($row['totalBill']!="")
				{
					$row['totalBill']="INR ".$row['totalBill'];
					
				}
				if($row['reg']!=0)
				{
					$row['reg']="(".$row['reg'].")";
					
				}
				$complaint="";
				$comp_col="";
				if($row['com_id']!="")
				{
                 $complaint="<br><br><small>Complaint: ".$row['com_status']."</small>";
				 $comp_col="<div style='width:10px;height:10px;border-radius:100%;background:red'></div>";

				}			
					
               $hour_list[]=array('<label data="'.$row['id'].'" rel="'.$row['driver_id'].'" class="clickme" style="padding: 0px 10px 0px 0;color:#08c; cursor:pointer;">'.$row['ref'].'</label>',date("d-M-y H:i",strtotime($row['ordertime']))."<br><small style='color:gray'>".'</small>',"<div data='".$row['id']."'  style='cursor:pointer;' class='driver_click'>".$row['vehicle'].'<br/><small style="color:gray;text-transform:capitalize"  class="driver_click">'.$row['driver_name'].'</small><br><small>'.$row['reg'].'</small>'.$comp_col."</div>",$row['booking_type'],$row['partner'].'<br>'.ucfirst($row['emp']),'<label class="client" data="'.$row['client_id'].'">'.$row['clientname'].'</label><br/><small style="color:gray">'.$row['mob_no'].'</small>',ucfirst($row['departure']),ucfirst($row['drop_area']),$row['status'].'<br/><small style="color:gray;text-transform:capitalize">'.$row['totalBill'].'</small>'.$complaint,"C-Share:".$row['cshare']."%<br><small>(INR ".$row['cAmount'].")</small>","D-Share:".$row['dshare']."%<br><small>(INR ".$row['dAmount'].")</small>","P-Share:".$row['pshare']."%<br><small>(INR ".$row['pAmount'].")</small>","<label style='padding: 0px 10px 0px 0;color:#08c; cursor:pointer;' class='action' data='".$row['id']."'>Action</label>");
//               $hour_list[]=array('<label data="'.$row['id'].'" class="clickme" style="padding: 0px 10px 0px 0;color:#08c; cursor:pointer;">'.$row['ref'].'</label>',date("M-j-y H:i",strtotime($row['ordertime']))."<br><small style='color:gray'>".'</small>',"<div data='".$row['id']."'  style='cursor:pointer;' class='driver_click'>".$row['vehicle'].'<br/><small style="color:gray;text-transform:capitalize"  class="driver_click">'.$row['driver_name'].'</small><br><small>'.$row['reg'].'</small>'.$comp_col."</div>",$row['booking_type'],$row['partner'].'<br>'.ucfirst($row['emp']),'<label class="client" data="'.$row['client_id'].'">'.$row['clientname'].'</label><br/><small style="color:gray">'.$row['mob_no'].'</small>',ucfirst($row['departure']),ucfirst($row['drop_area']),$row['status'].'<br/><small style="color:gray;text-transform:capitalize">'.$row['totalBill'].'</small>'.$complaint,"<label style='padding: 0px 10px 0px 0;color:#08c; cursor:pointer;' class='action' data='".$row['id']."'>Action</label>");
               
           }
           if(mysqli_num_rows($hour)>0)
            {
                return array("status"=>"true","data"=>$hour_list);
            }
            else
            {
                return array("status"=>"false","data"=>"");
            }
            
            
        }
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
		public function humanTiming ($time)
{

         $time = time() - $time; // to get the time since that moment

          $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

}
         public function latest_booking(){
             $booking=$_REQUEST['no_of_booking'];
            $no_of_rows=mysqli_query($this->con,"CALL `sp_w_booking_details`()") or die(mysqli_error($this->con));
            $rows=  mysqli_num_rows($no_of_rows);
             mysqli_free_result($no_of_rows);   
           mysqli_next_result($this->con);
           $remain=$rows-$booking;
           
             $hour=mysqli_query($this->con,"CALL `sp_w_latest_booking`($remain)") or die(mysqli_error($this->con));
           //$booking=array();
            while($row=mysqli_fetch_array($hour))
           {
                //print_r($row);
               $hour_list[]=array('<label data="'.$row['id'].'" class="clickme" style="padding: 0px 10px 0px 0;color:#08c; cursor:pointer;">'.$row['id'].'</label>',$row['ordertime'],$row[2],'',$row[3],$row[4],$row[5]);
               
           }
            return array("data"=>$hour_list);
            
            
        }
        public function booking_information(){
            $id=$_REQUEST['booking_id'];
             $hour=mysqli_query($this->con,"CALL `sp_w_booking_information`($id)") or die(mysqli_error($this->con));
           $booking=array();
            while($row=mysqli_fetch_array($hour))
           {
                //print_r($row);
               $booking[]=array('time'=>date('d-M-Y H:i:s',strtotime($row['time'])),'status'=>$row['status'],'message'=>$row['message']);
               
           }
            return array("data"=>$booking);
            
            
        }
		public function driver_info(){
			$id=$_REQUEST['id'];
			$hour=mysqli_query($this->con,"CALL `sp_driver_info`($id)") or die(mysqli_error($this->con));
			$driver=array();
			while($row=mysqli_fetch_assoc($hour))
			{
				if($row['status']==1){
					$row['status']="Busy";
				}
				else{	
					$row['status']="Free";
				}
				if($row['v_type']==1){
					$row['v_type']="Economy";
				}elseif($row['v_type']==2){
					$row['v_type']="Sedan";
				}elseif($row['v_type']==3){
					$row['v_type']="Prime";
				}
				foreach($row as $v=>$s){
					if($row[$v]==null){
						$row[$v]="";
					}
				}
				$driver=$row;
			}
			return array("status"=>"true","data"=>$driver);
		}
		
		    public function driver_alloc(){
            $id=$_REQUEST['booking_id'];
             $hour=mysqli_query($this->con,"CALL `sp_driver_alloc`($id)") or die(mysqli_error($this->con));
           $booking=array();
            while($row=mysqli_fetch_array($hour))
           {
			   $status="";
                //print_r($row);
				if($row['status']=="M")
				{
					$status="Missed";
					
					
			    }
				if($row['status']=="A")
				{
					$status="Accept";
					
					
			    }
				
				if($row['status']=="R")
				{
					$status="Reject";
					
					
			    }
               $booking[]=array('driver_name'=>$row['driver_name'],'vehicle'=>$row['vehicle'],'created'=>date('d-M-y H:i:s',strtotime($row['created'])),'status'=>$status);
               
           }
            return array("data"=>$booking);
            
            
        }
        public function client_information(){
            $id=$_REQUEST['client_id'];
            $hour=mysqli_query($this->con,"CALL `sp_w_client_information`($id)") or die(mysqli_error($this->con));
           $booking=array();
            while($row=mysqli_fetch_array($hour))
           {
                //print_r($row);
               $booking[]=array('clientname'=>$row['FirstName'].' '.$row['LastName'],'contact'=>$row['ContactNo'],'email'=>$row['Email'],'altemail'=>$row['AltEmail']);
               
           }
            return array("data"=>$booking); 
            
            
        }
        

         
          public function payment_information(){
            $id=$_REQUEST['booking_id'];
			  $id=1060;
             $hour=mysqli_query($this->con,"CALL `sp_w_payment_information`($id)") or die(mysqli_error($this->con));
           $booking=array();
            while($row=mysqli_fetch_array($hour))
           {
                //print_r($row);
                switch($row['BookingType'])
                {
                    case 101:
                        $booking_type="Local Hire";
                        break;
                    case 102:
                        $booking_type="Point to Point";
                        break;
                    case 103:
                        $booking_type="Airport Transfer";
                        break;
                    case 104:
                        $booking_type="Outstation";
                        break;
                    default:
                        $booking_type="Point to Point";
                        break;
                    
                    
                }
                $is_corporate_booking="";
                if($row['is_corportate_booking']==1)
                {
                    $is_corporate_booking="TRUE";
                }else{
                    
                    $is_corporate_booking="FALSE";
                }
                $is_paid='';
                if($row['is_paid']==1)
                {
                    $is_paid="True";
                }else{
                    
                    
                    $is_paid="False";
                }
                
                foreach($row as $v=>$k)
                {
                    if($row[$v]=='0' or $row[$v]=='')
                    {
                        $row[$v]="N/A";
                        
                    }
                    
                }
                    
               $booking[]=array(
                   'book_ref'=>$row['booking_reference'],
                   'distance_rate'=>"INR ".$row['starting_rate']." Per Km",
                   'created_at'=>date("d-M-y H:i:s",strtotime($row['addedtime'])),'is_paid'=>$is_paid,'paid_at'=>$row['paid_at'],'currency'=>"INR",'invoice_number'=>$row['invoice_number'],'payment_type'=>$row['payment_type'],'fees'=>$row['fees'],'total_price'=>'INR '.$row['total_price'],'total_tax_price'=>'INR '.$row['total_tax_price'],'duration_rate'=>$row['duration_rate'],'starting_rate'=>"INR ".$row['starting_rate']." Per Km",'base_price'=>$row['base_price'],'tax_price'=>$row['tax_price'],'duration_rate'=>$row['duration_rate'],'duration_charge'=>$row['duration_charge'],'distance_charge'=>"INR ".$row['distance_charge'],'minimum_price'=>"INR ".$row['minimum_price'],'starting_charge'=>"INR ".$row['starting_rate'],'cancellation_price'=>$row['cancellation_price'],'waiting'=>$row['waitingCharge'],'totalbill'=>$row['totalBill'],'tripCharge'=>$row['tripCharge'],'pickuparea'=>$row['PickupArea'],'droparea'=>$row['DropArea'],'pickupaddress'=>$row['PickupAddress'],'dropaddress'=>$row['DropAddress'],'estimated_distance'=>$row['EstimatedDistance']."Km",'estimated_time'=>round(($row['EstimatedTime']))."Hrs",'booking_id'=>$row['ID'],'useragent'=>$row['useragent'],'bookingtype'=>$booking_type,'bookingdate'=>date("d-M-y H:i:s",strtotime($row['BookingDate'])),'pickupdate'=>date('d-M-y H:i:s',strtotime($row['PickupDate'].' '.$row['PickupTime'])),'driver_note'=>$row['driver_note'],'client_note'=>$row['client_note'],'features'=>$row['features'],'is_corporate_booking'=>$is_corporate_booking,'is_account_booking'=>$row['is_account_booking'],'voucher_id'=>$row['voucher_id'],'voucher_type'=>$row['voucher_type'],'arrival_time_pre'=>$row['arrival_time_pre'],'arrival_time_post'=>$row['arrival_time_post'],'arrival_time_actual'=>$row['arrival_time_actual'],'expiration_time'=>$row['expiration_time'],'actual_driven_distance'=>$row['actual_driven_distance'],'actual_waiting_distance'=>$row['actual_waiting_distance'],'actual_distance'=>$row['actual_distance'],'estimated_price'=>$row['estimated_price'],'driver_rating'=>$row['driver_rating'],'client_rating'=>$row['client_rating'],'actual_driven_duration'=>$row['actual_driven_duration'],'cab_name'=>$row['cab_name']);
               
           }
            return array("data"=>$booking);
            
            
        }         

public function days_wise(){
             $date_new=$_REQUEST['date'];
            $hour_data=$_REQUEST['hour'];
           //echo $date_new.$hour_data;
//die;
           $hour=mysqli_query($this->con,"CALL `sp_w_list_hour_booking`('$date_new','$hour_data')") or die(mysqli_error($this->con));
           $hour_list=array();
            while($row=mysqli_fetch_array($hour))
           {
               $hour_list["hour_".$row['hour']]=$row['no_of'];
               
           }
             mysqli_free_result($hour);   
           mysqli_next_result($this->con);
           
           $hour_wise=mysqli_query($this->con,"CALL `sp_w_list_booking`('$date_new')") or die(mysqli_error($this->con));
           $hour_wise_list=array();
            while($row=mysqli_fetch_assoc($hour_wise))
           {
              //array_push($hour_wise_list,array('id'=>$row[id],'hour'=>$row['hour'],'clientname'=>$row['clientname'],'contact'=>$row['ContactNo']));
          array_push($hour_wise_list,$row);
              }
              
               mysqli_free_result($hour_wise);   
           mysqli_next_result($this->con);
           $que='';
           if(isset($_REQUEST['hour']))
           {
            
//             $que=mysqli_query($this->con,"SELECT COUNT(*) as no,bookingtype FROM tblcabbooking  WHERE pickupdate='$date_new' GROUP BY (tblcabbooking.BookingType)")or die(mysqli_error($this->con));
             $que=mysqli_query($this->con,"SELECT COUNT(*) as no,bookingtype FROM tblcabbooking  WHERE pickupdate='$date_new' and hour(`pickuptime`)='$hour_data' GROUP BY (tblcabbooking.BookingType)")or die(mysqli_error($this->con));
          
           }else{
              $que=mysqli_query($this->con,"SELECT COUNT(*) as no,bookingtype FROM tblcabbooking  WHERE pickupdate='$date_new'  GROUP BY (tblcabbooking.BookingType)")or die(mysqli_error($this->con)); 
              
           }
          
                 $booking_type=array();
              while($result=  mysqli_fetch_assoc($que))
              {
                  $booking_type[]=$result;
                  
              }  
              
              
           //echo '<pre>';
           //print_r($key_cal);
          
      
            return array('hour'=>$hour_list,'hour_wise'=>$hour_wise_list,'booking_type'=>$booking_type);
             
             
             
             
         }
		 
        /*
         public function driver_list(){
            
          $result=mysqli_query($this->con,"CALL `wp_a_driverData`()") or die(mysqli_error($this->con));
              
           $a=1;     
               
            while($data=mysqli_fetch_assoc($result))
           {
                
				if($data['is_active'] == 1){
						$onlineStatus = "<small style='width: 15px;height: 15px;background: green;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
                                }else if($data['is_active']==2){
						$onlineStatus = "<small style='width: 15px;height: 15px;background: rgb(251, 186, 0);padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
                                }else if($data['is_active']==3){
						$onlineStatus ="<small style='width: 15px;height: 15px;background: black;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
				}else if($data['is_active']==4){
						$onlineStatus ="<small style='width: 15px;height: 15px;background: blue;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
				}else{
                                                $onlineStatus ="<small style='width: 15px;height: 15px;background: #B64242;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
				}
				
              //file_put_contents('yoyo.txt', print_r($data['is_active'],TRUE));
              
                $data_list[] = array("<input name='drives_id[]' type=checkbox value='".$data['UID']."'>",$a++,"<a href='#' class='mydrivers' data='".$data['UID']."'>".$data['UID']."</a>",$data['FirstName'],"<a href='#'>".$data['name']."</a> <img src='../public/image/vehicle.png' style='width:30px'>",$data['VehicleRegistrationNo'],'Subscription',$data['vehicleName'],$data['DrivingLicenceNo'],$data['ContactNo']."<br>"."<small style='color:gray'>".$data['Email']."</small>",$data['created_date']."<br><small style='color:gray'>".$data['booking']." bookings<small>".$onlineStatus);
               
           }
           //file_put_contents('yoyo.txt', print_r($data_list,TRUE));
            return array("data"=>$data_list);
            
            
        }
        */
         
        
        public function driver_list(){
          $result=mysqli_query($this->con,"CALL `wp_a_driver_list`()") or die(mysqli_error($this->con));
          $a=1;     
              
            while($data=mysqli_fetch_assoc($result))
           {
           	
				if($data['LoginStatus']==0 && $data['IsActive']==0){
				$driverLoginStatus=	"<a class='changeLoginStatus btn1' style='width:15px;height:15px;background-color:red;border-radius:50%' rel='logOff' title='click to Logged-In' data='".$data['UID']."'></a>";
				}else{
				$driverLoginStatus=	"<a class='changeLoginStatus btn1' style='width:15px;height:15px;background-color: 	#90EE90;border-radius:50%' rel='logIn' title='click to Logged-Off' data='".$data['UID']."'></a>";
				}
				if($data['is_active'] == 1){
						$onlineStatus = "<small style='float:right;width: 15px;height: 15px;background: green;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
                                }else if($data['is_active']==2){
						$onlineStatus = "<small style='float:right;width: 15px;height: 15px;background: rgb(251, 186, 0);padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
                                }else if($data['is_active']==3){
						$onlineStatus ="<small style='float:right;width: 15px;height: 15px;background: black;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
				}else if($data['is_active']==4){
						$onlineStatus ="<small style='float:right;width: 15px;height: 15px;background: blue;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
				}else{
						$onlineStatus ="<small style='float:right;width: 15px;height: 15px;background: #B64242;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
				}
				
              //file_put_contents('yoyo.txt', print_r($data['is_active'],TRUE));
              
                $data_list[] = array("<input class='driver_check' name='drives_id[]' type=checkbox value='".$data['ContactNo']."'>",$a++,"<a href='#' class='mydrivers' data='".$data['UID']."'>".$data['UID']."</a>",$data['FirstName'],"<a href='#'>".$data['vehicleName']."</a> <img src='../public/image/vehicle.png' style='width:30px'>",$data['VehicleRegistrationNo'],$data['CabIgnitionType'],'Subscription',$data['approvedStatus'],$data['DrivingLicenceNo'],$driverLoginStatus,$data['ContactNo']."<br>"."<small style='color:gray'>".$data['Email']."</small>",$data['created_date']."<br><small style='color:gray'>".$data['booking']." bookings<small>".$onlineStatus);
               
           }
           //file_put_contents('yoyo.txt', print_r($data_list,TRUE));
            return array("data"=>$data_list);
        }
		
		
		public function driver_vehicle_list(){
          $result=mysqli_query($this->con,"CALL `wp_a_driver_list`()") or die(mysqli_error($this->con));
           $a=1;     
               
            while($data=mysqli_fetch_assoc($result))
           {                
				if($data['is_active'] == 1){
						$onlineStatus = "<small style='float:right;width: 15px;height: 15px;background: green;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
                                }else if($data['is_active']==2){
						$onlineStatus = "<small style='float:right;width: 15px;height: 15px;background: rgb(251, 186, 0);padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
                                }else if($data['is_active']==3){
						$onlineStatus ="<small style='float:right;width: 15px;height: 15px;background: black;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
				}else if($data['is_active']==4){
						$onlineStatus ="<small style='float:right;width: 15px;height: 15px;background: blue;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
				}else{
						$onlineStatus ="<small style='float:right;width: 15px;height: 15px;background: #B64242;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
				}
				
              //file_put_contents('yoyo.txt', print_r($data['is_active'],TRUE));
              
                $data_list[] = array("<input class='driver_check' name='drives_id[]' type=checkbox value='".$data['ContactNo']."'>",$a++,"<a href='#' class='mydrivers' data='".$data['UID']."'>".$data['UID']."</a>",$data['FirstName'],"<a href='#'>".$data['vehicleName']."</a> <img src='../public/image/vehicle.png' style='width:30px'>",$data['VehicleRegistrationNo'],$data['CabIgnitionType'],'Subscription',$data['approvedStatus'],$data['DrivingLicenceNo'],$data['ContactNo']."<br>"."<small style='color:gray'>".$data['Email']."</small>",$data['created_date']."<br><small style='color:gray'>".$data['booking']." bookings<small>".$onlineStatus);
               
           }
           //file_put_contents('yoyo.txt', print_r($data_list,TRUE));
            return array("data"=>$data_list);
        }
        
		
        public function apiSendSMS(){
            $_REQUEST['msg_content'] = 'Hi Bhagendra Singh, Your booking with ref. no. HW15075393 for 2015-07-10 14:46:00. Total apprx cost Rs.1465. Our tarrif is 175for first 5 Km, after that Rs.15 per KM. waiting charges Rs.1 /minute. For more details, contact 01142424242 or download mobile app variable8. Other conditions apply.';
			$_REQUEST['driver_id'] = 9990771758;
            $msgContent = urlencode($_REQUEST['msg_content']);
            $driverID = $_REQUEST['driver_id'];
            $contactNo = explode(",",$driverID);
            $totalContact = count($contactNo);
            for($i=0;$i<$totalContact;$i++){
                //$url = "http://push1.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$contactNo[$i]&from=Helocb&dlrreq=true&text=$msgContent&alert=1";
				
				$url="http://push1.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$contactNo[$i]&from=Helocb&dlrreq=true&text=$msgContent&alert=1";
				print $url;die();
                $data[] = file_get_contents($url);
            }
			
			print_r($data);
        }
         public function delaySMS(){
            
         
         $id = $_REQUEST['id'];
         $query=mysqli_query($this->con,"SELECT * FROM tbl_sms_template WHERE msg_sku='delay'")or die(mysqli_error($this->con));
         $result=mysqli_fetch_assoc($query);
         $explode=explode('<variable>',$result['message']);
         $query2=mysqli_query($this->con,"SELECT tblcabbooking.MobileNo,tblcabbooking.UserName,tblcabbooking.booking_reference,CONCAT(tblcabbooking.PickupDate,' ',tblcabbooking.PickupTime) as dat,CONCAT(tbldriver.FirstName,' ',tbldriver.LastName) as driver,tbldriver.ContactNo FROM tblcabbooking LEFT JOIN tbldriver ON tblcabbooking.Pickup=tbldriver.UID WHERE tblcabbooking.ID='$id'");
         $result2=  mysqli_fetch_assoc($query2);
         $explode[0]=$explode[0].$result2['UserName'];
         $explode[1]=$explode[1].$result2['booking_reference'];
         $explode[2]=$explode[2].$result2['dat'];
         $explode[3]=$explode[3].$result2['driver'];
         $explode[4]=$explode[4].$result2['ContactNo'];
         $mobile=$result2['MobileNo'];
         $text=  urlencode(implode("",$explode));	
			//file_put_contents("mssg.txt",$text);
			$url="http://push3.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$mobile&from=Helocb&dlrreq=true&text=$text&alert=1";
			file_get_contents($url);
                return array('id'=>$id);
           
        }
		
	public function getLocation($DriverId=0){
		$sql = "SELECT tblbookingtracker.Latitutude, tblbookingtracker.Logitude, tblbookingtracker.Date_Time, tblcabbooking.PickupAddress FROM tblbookingtracker INNER JOIN tblcabbooking ON tblbookingtracker.BookingID = tblcabbooking.ID WHERE CabStatus = 4 AND DriverId = $DriverId ORDER BY tblbookingtracker.Id DESC Limit 0,1";
		$qry = mysqli_query($this->con, $sql);
		$row = mysqli_fetch_object($qry);
		$map = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.$row->Latitutude.','.$row->Logitude.'&sensor=true');
		$map = json_decode($map);
		return array('PickupAddress'=>$row->PickupAddress,'Address'=>$map->results[0]->formatted_address, 'Date_Time'=>$row->Date_Time);
	}
	
        
        
        
	public function tracking1(){
		$Sr = 1;
		$id=$_REQUEST['status'];
		$result=mysqli_query($this->con,"CALL `sp_driver_tracking_list`()") or die(mysqli_error($this->con));
		$data_list=array();
		while($data=mysqli_fetch_assoc($result)){
			$destination="";
			if($id==1){
				if($data['loginStatus']==1 && $data['status']==0){
					$status="Free";
					$data_list[]=array("$Sr",$data['vehicleNumber'],'',$data['name'],$data['ContactNo'],'','','',$destination,'',$status);	    
				}
			}elseif($id==2){
				if($data['loginStatus']==1 && $data['status']==1){
					$status="Hired";
					$destination=$data['DropArea'];
					$data_list[]=array("$Sr",$data['vehicleNumber'],'',$data['name'],$data['ContactNo'],'','','',$destination,'',$status);	    
				}   
			}elseif($id==3){
				if($data['loginStatus']==0 && $data['status']==0){
					$status="Logout";
					$data_list[]=array("$Sr",$data['vehicleNumber'],'',$data['name'],$data['ContactNo'],'','','',$destination,'',$status);	    
				}   
			} 
			$Sr++;
		}
		return array("data"=>$data_list);
	}
        
        
        public function tracking2()
                
        {
              $id=$_REQUEST['status'];
          $data_list=array();   
          $result1=mysqli_query($this->con,"CALL `sp_vehicle_filter`('$id')") or die(mysqli_error($this->con)); 
             $destination=""; 
              while($data=mysqli_fetch_assoc($result1))
           {
                    
                   if($id==5)
                 $status="Reported";
                   else if($id==3)
                    $status="Duty Accepted"; 
                   else if($id==2)
                       $status="On Call";
                 $destination=$data['DropArea'];
                    
                $data_list[]=array('','',$data['vehicleNumber'],'',$data['name'],$data['ContactNo'],'','','',$destination,'',$status);	
                
                
           }
           
           return array("data"=>$data_list);
        }

	public function driver_track(){
		$result=mysqli_query($this->con,"CALL `sp_driver_tracking`()") or die(mysqli_error($this->con));
		$data=mysqli_fetch_assoc($result);
		return array("data"=>$data);
	}
        
    
        
//   public function deleteDriver(){
//       $count =0;
//       $drivesdata= $_POST['driverIds'];
//       $driverid =explode(',',$drivesdata) ;
//       $driverLength = sizeof($driverid);
//      
//       for($i=0;$i<$driverLength;$i++){
//      
//           
//       $result =  mysqli_query($this->con,"CALL `wp_a_driverDelete`('$driverid[$i]]')") or die(mysqli_error($this->con));
//       if($result == 1)
//           {
//           $count++;
//           
//           }
//       mysqli_free_result($result); 
//       //$count++;
//           mysqli_next_result($this->con);
//          
//       }
//       //$data = mysqli_fetch_assoc($result);
//       //$data = mysqli_fetch_assoc($result);
//      //$update = $data['affectedrow'];
//      //file_put_contents('d.txt', $count);
//       if($count > 0){
//           
//           return array("status"=>"true");
//           
//       }
//       else{
//           return array("status"=>"false");
//       }
//       
//   }
   
        public function sendSmsDriver(){
            $count =0;
       $drivesdata= $_POST['driverIds'];
      
       $driverid =explode(',',$drivesdata) ;
       $driverLength = sizeof($driverid);
      
       for($i=0;$i<$driverLength;$i++){
           
           
     
           
       $result =  mysqli_query($this->con,"CALL `wp_a_drivercontacts`('$driverid[$i]]')") or die(mysqli_error($this->con));
       if($result == 1)
           {
           $count++;
           
           }
       $data = mysqli_fetch_assoc($result);
       mysqli_free_result($result); 
       //$count++;
           mysqli_next_result($this->con);
           
           
          $url = "http://push1.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91".$data['ContactNo']."&from=Helocb&dlrreq=true&text=Thanks+for+choosing+HELLO42+CABS.+Bkg+Ref+No+foris.+We+will+revert+with+ur+bkg+confirmation.+Cnt+".$act."+or+Online+bkg+www.hello42cabs.com+from+Hello42&alert=1";
                        $getmap = file_get_contents($url); 
                        
                        $no = $data['ContactNo'];
                         $datasms ="test for mesg";
                        file_put_contents('google.txt', print_r($no,TRUE));
   $result =  mysqli_query($this->con,"CALL `wp_a_driverSms`('$driverid[$i]','$datasms','$no')") or die(mysqli_error($this->con));        mysqli_free_result($result); 
       //$count++;
           mysqli_next_result($this->con);          
                        
       }
       //$data = mysqli_fetch_assoc($result);
       //$data = mysqli_fetch_assoc($result);
      //$update = $data['affectedrow'];
      //file_put_contents('d.txt', $count);
       if($count > 0){
           
           return array("status"=>"true");
           
       }
       else{
           return array("status"=>"false");
       } 
            
        }

		public function Getfeature()
		{
			//$record=array();
			$qry = "select id,featurename from tblbookingfeature";
			$result =  mysqli_query($this->con,$qry) or die(mysqli_error($this->con));
			
			while($data = mysqli_fetch_assoc($result)){
                 
        		 $record[]=$data;
             }
			 
			return array("data" =>$record);
		}


        public function profileDriver(){
        
        $driverId= $_REQUEST['driverProfileId'];
       
        $result =  mysqli_query($this->con,"CALL `wp_a_driverEditProfile`('$driverId')") or die(mysqli_error($this->con));
		 
        $data = mysqli_fetch_assoc($result);
        //$driverData = array();
        if($data['account_no'] == ""){
			$account = "No";
		}else{
			$account = "Yes";
		}
       // while($data = mysqli_fetch_assoc($result)){
             if(!file_exists($data['vehicleimg'])){
                 $data['vehicleimg']="public/image/vehicle.png"; 
             }
             if(!file_exists($data['image'])){
                 $data['image']="public/image/default_user.png"; 
             }
             
            $driverData = array(
                                "idsDriver"=>$data['UID'],
								"Unique_ref_Key"=>$data['ref_Key'],
                                "name"=>$data['FirstName'],
                                "lastname"=>$data['LastName'],
                                "fathername"=>$data['FatherName'],
                                "refn"=>$data['refname'],
                                "city"=>$data['cn'],
                                "account"=>"$account",
                                 "createDate"=>$data['created_date'],
                                 "email"=>$data["Email"],
                                 "db"=>$data['dateofbirth'],
                                 "gender" =>$data['gender'],
                                 "address"=>$data["Address"],
								 "cabName"=>$data["CabName"],
								 "CabIgnitionType"=>$data["CabIgnitionType"],
								 "address"=>$data["Address"],
                                 "ofcadds"=>$data['OfcAddress'],
                                 "phone"=>$data['ContactNo'],
                                 "verify"=>$data['isVerifY'],
                                 "userName"=>$data['userName'],
                                 "fleet"=>$data['TotalFleetNo'],
                                 "vehicleName"=>$data['name'],
                                 "panNo"=>$data['PanCardNo'],
                                 "modelV"=>$data['ModelOfVehicle'],
                                 "lstate"=>$data['licence_state'],
                                 "licence"=>$data['DrivingLicenceNo'],
                                 "vehicle"=>$data['VehicleRegistrationNo'],
                                 "amount"=>$data['SecurityAmt'],
                                 "active"=>$data['is_active'],
                                 "block"=>$data['is_block'],
                                 "barred"=>$data['is_barred'],
                                 "zone"=>$data['zone'],
                                 "route"=>$data['route_know'],
                                 "prflocation"=>$data['pref_location'],
                                 "weekoff"=>$data['week_off'],
								 "weekoffday"=>$data['weekoff_day'],
                                 "iemi"=>$data['imei'],
                                 "gps"=>$data['gps'],								 
                                 "eyetest"=>$data['Eyetest'],
                                 "write"=>$data['lang_write'],
                                 "speak"=>$data['lang_speak'],
                                 "timing"=>$data['pref_timing'],
                                 "dutyprf"=>$data['ReciveAirPortTrns'],
                                 "cash"=>$data['AcceptCash'],
                                 "status_driver"=>$data['driverStatus'],
                                 "signup_comts"=>$data['signup_comment'],
                                 "internal_comts"=>$data['internal_comment'],
                                 "photo"=>$data['photo_verify'],
                                 "vendorName" => $data['vendor_name'],
                                 "feature" => $data['driver_feature'],
                                 "prof_img" => $data['image'],
                                 "veh_img" => $data['vehicleimg'],
            					 "ac_nonac" => $data['ac_nonac'],
            					 "operational_company_name" => $data['operational_company_name'],
            					 "vehicle_owned" => $data['vehicle_owned'],
            					 "vehicle_owner_name" => $data['vehicle_owner_name'],
            					 "vehicle_owner_cnt_no" => $data['vehicle_owner_cnt_no'],
            					 "vehicle_owner_alt_cnt_no" => $data['vehicle_owner_alt_cnt_no'],
            					 "license_validity" => $data['license_validity'],
            					 "insurance_validity" => $data['insurance_validity'],
								  "company_pincode" => $data['company_pincode'],
								   "rc_no" => $data['rc_no']
                                );
           
                                //file_put_contents('ree.txt', print_r($driverData,TRUE));
       //echo"<pre>";print_r($driverData);echo"</pre>";
	   mysqli_free_result($result); 
	   mysqli_next_result($this->con);
	   
	   //Query to fetch the driver status starts here /////
	   $driverStatus=array();
	   $sql="select status_id,status from tblcabstatus where type='driverS'";
	   $result1 =  mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
       while($data1 = mysqli_fetch_array($result1)){
			$status_id[]=$data1['status_id'];
		    $status[]=$data1['status'];

	   }
			$driverData['status_id']	=	$status_id;
			$driverData['status']		=	$status;   
	   //Query to fetch the driver status ends here /////
	   
	   $sqlmasterPackage="SELECT master_package_ref,Package_Id FROM `tblmasterpackage` WHERE `state_id`='2'";
	   $resultmasterPackage = mysqli_query($this->con, $sqlmasterPackage);
		while($datamasterpackage = mysqli_fetch_assoc($resultmasterPackage)){
			$master_package_ref[]=$datamasterpackage['master_package_ref'];
		    $Package_Id[]=$datamasterpackage['Package_Id'];
		}
			$driverData['master_package_ref']	=	$master_package_ref;
			$driverData['Package_Id']		=	$Package_Id;   
	   
        return array("data"=>$driverData);
   }
   
   public function driverbank(){
   
		$driverId= $_POST['driverProfileId'];
       
        $result =  mysqli_query($this->con,"CALL `wp_a_driverEditProfile`('$driverId')") or die(mysqli_error($this->con));
        $data = mysqli_fetch_assoc($result);
		
							 $driverData = array(
													"bankname"=>$data['bank_account_name'],
													"accholder"=>$data['acount_holder'],
													"bankadds"=>$data['bank_adds'],
													"bankAccNo"=>$data['account_no'],
													"bankneft"=>$data['rtgs_neft'],
													"bankibn"=>$data['ibn']
													);
		
							return array("data"=>$driverData);
			}
      
   public function profileUpdateDriver(){
       session_start();
	   
       
                                        $driver_id = $_POST['driverIds'];
                                        $driver_name = $_POST['driver_firstName'];
                                        $driver_lname = $_POST['driver_lastName'];
                                        $driver_father = $_POST['driver_father'];
                                        $driver_city = $_POST['city_driver'];
                                        $driver_email = $_POST['email_driver'];
                                        $driver_evarify = $_POST['emailVerify'];
                                        $driver_uname = $_POST['driver_username'];
                                        $driver_ads = $_POST['driver_adds'];
                                        $driver_oadds = $_POST['driver_addofc'];
                                        $driver_phone = $_POST['driver_phone'];
                                        $driver_pverify = $_POST['verify'];
                                        $driver_panNos = $_POST['driver_panNos'];
                                        $driver_licence = $_POST['driver_licence'];
                                        $driver_vno = $_POST['driver_vehicleNo'];
                                        $driver_fleet = $_POST['driver_fleetNo'];
                                        $driver_modely = $_POST['driver_modely'];
                                        $driver_lstate = $_POST['driver_lstate'];
                                        $specs_active = $_POST['specs_active'];
                                        $driver_zone = $_POST['driver_zone'];
                                        $driver_route = $_POST['driver_route'];
                                        $driver_pref = $_POST['driver_pref'];
                                        $driver_weekoff = $_POST['driver_weekoff'];
                                        $driveimei= $_POST['driveimei'];
                                        $drivergps= $_POST['drivergps'];
										$ac_nonac=$_POST['ac_nonac'];																				
										$operational_company_name=$_POST['operational_company_name'];
										$vehicle_owned=$_POST['vehicle_owned'];
										if($vehicle_owned=="vehicle_attached"){
										$vehicle_owner_name=$_POST['vehicle_owner_name'];
										$vehicle_owner_cnt_no=$_POST['vehicle_owner_cnt_no'];
										$vehicle_owner_alt_cnt_no=$_POST['vehicle_owner_alt_cnt_no'];
										}else{
										$vehicle_owner_name="";
										$vehicle_owner_cnt_no="";
										$vehicle_owner_alt_cnt_no="";										
										}										
										$license_validity=$_POST['license_validity'];
										$insurance_validity=$_POST['insurance_validity'];
										$com_pincode=$_POST['com_pincode'];
										$rc_no=$_POST['rc_no'];
										
                                        //$driver_write = implode(",",$_POST['writelang']);
        $driver_write = implode(",",array($_POST['writelang'],$_POST['writelang1'],$_POST['writelang2'],$_POST['writelang3']));
										//file_put_contents('lolo.txt',print_r($_POST,TRUE));
   $driver_speak = implode(",",array($_POST['speak'],$_POST['speak1'],$_POST['speak2'],$_POST['speak3']));
   $driver_timing = implode(",",array($_POST['logtime'],$_POST['logtime1'],$_POST['logtime2']));
                                        $driver_samount = $_POST['driver_amount'];
  $driver_active = $_POST['driver_isActive'];
  file_put_contents('lolo.txt',$driver_active);
  $driver_air	=	$_POST['chkdata']; //die;
// $driver_air = implode(",",array($_POST['dAir1'],$_POST['dAir2'],$_POST['dAir3'],$_POST['dAir4'],$_POST['dAir5']));
/*if(isset($_POST['dAir'])){
$driver_air	=	$_POST['dAirAll'];
}else{
$driver_air= implode(",",$_POST['dAir']);	
}
echo $driver_air; die;*/
/*$driverAll	=	$_POST['dAirAll'];
if($driverAll!=""){
$driver_air=	$driverAll;
}else{
$driver_air= implode(",",$_POST['dAir']);
}*/

// $driver_cash = implode(",",array($_POST['dCash1'],$_POST['dCash2'],$_POST['dCash3']));
  $driver_cash	=	$_POST['chkdata1']; 
                                        
                                        $driver_signup_c = $_POST['driver_signup_comts'];
                                        $driver_internal_c = $_POST['driver_internal_comts'];
										$driver_dob = $_POST['driverBirth'];

$id = $driver_id;

$token =  uniqid();
$tmp_driver_profile=str_replace('http://hello42cab.com/','',$_POST['profile_pic_update_val']);
$tokenVeh =  uniqid();
$tmp_driver_vehicle=str_replace('http://hello42cab.com/','',$_POST['vehicle_pic_update_val']);
$check_img="";
//$check_img2="";
if(file_exists($tmp_driver_profile))
{
    $check_img=1;
    $image="public/userimage/$id/$token.jpeg";
    //file_put_contents("dfdfdfdf.txt", $image);
    mkdir("public/userimage/$id/",0777,true);
    rename($tmp_driver_profile,$image );
    mysqli_query($this->con,"UPDATE `tbluserinfo` SET image = '$image' where UID = '$id'") or die(mysqli_error($this->con));
}

if(file_exists($tmp_driver_vehicle))
{
    $check_img=1;
    $imageVeh="public/userimage/$id/$tokenVeh"."v.jpeg";
    //file_put_contents("dfdfdfdf.txt", $image);
    //mkdir("public/userimage/$id/",0777,true);
    rename($tmp_driver_vehicle,$imageVeh );
    mysqli_query($this->con,"UPDATE `tbluserinfo` SET vehicleimg = '$imageVeh' where UID = '$id'") or die(mysqli_error($this->con));
}
       
 $result =  mysqli_query($this->con,"CALL `wp_a_driverUpdateProfile`('$driver_id','$driver_name','$driver_lname','$driver_father','$driver_city','$driver_email','$driver_evarify','$driver_uname','$driver_ads','$driver_oadds','$driver_phone','$driver_pverify','$driver_panNos','$driver_licence','$driver_vno','$driver_fleet','$driver_modely','$driver_lstate','$specs_active','$driver_zone','$driver_samount','$driver_route','$driver_pref','$driver_weekoff','$driveimei','$drivergps','$driver_write','$driver_speak','$driver_timing','$driver_active','$driver_air','$driver_cash','$driver_signup_c','$driver_internal_c','$driver_dob','$ac_nonac','$operational_company_name','$vehicle_owned','$vehicle_owner_name','$vehicle_owner_cnt_no','$vehicle_owner_alt_cnt_no','$license_validity','$insurance_validity','$rc_no','$com_pincode')") or die(mysqli_error($this->con));                             

                
                $data = mysqli_fetch_assoc($result);
              
               $dataFordriver =$data['tablecount'];
                //file_put_contents('lop.txt', $dataFordriver);  
               $dataUser = $data['tablecountuser'];
               // file_put_contents('lop1.txt', $dataUser);  
                if($dataFordriver == 1 || $dataUser == 1 || $check_img==1){
                    
                    return array("status"=>"true");
                }
               
                else{
                    return array("status"=>"false");
                }
    
    
    
   }
   public function driverunameCheck(){
       
       $uname = $_POST['userName'];
      // $uid = $_POST['userId'];
       
       $result =  mysqli_query($this->con,"CALL `wp_a_usernameCheck`('$uname')") or die(mysqli_error($this->con));
       
       $data = mysqli_fetch_assoc($result);
       $count = $data['uCount'];
       if($count > 1){
         return array("response"=>"001");  
       }
       else if($count < 1 ){
            return array("response"=>"002");  
       }
       else{
            return array("response"=>"003");  
       }
   }
   
   
   
   
   
   
   
   public function driversignup(){
                                                                           
 
				$driverName = $_POST['dName'];
				
				$name=explode(" ",$driverName);
				 $firstname = $name[0];
				$secondname = $name[1];
                                $fathername = $_POST['dfName'];
				$city = $_POST['city'];
				 $driver_email = $_POST['driver_email'];
                               //$driver_pass = $_POST['driver_pass'];
                                $refrence = $_POST['drefName'];
                                $driverNumber = $_POST['dNo'];
                               
                                
				$AdriverNumber = $_POST['dAno'];
				$driverAddress = $_POST['dAd'];
				$driverOfficeAddress = $_POST['dofc'];
				$driverPan = $_POST['dpan'];
				$driverFleets = $_POST['dfleet'];
				$driverAmount = $_POST['driverAmount'];
				//file_put_contents('amount.txt',$driverAmount);
				$driverAir = $_POST['dAir'];
                                
				$driverCash = $_POST['dcash'];
				//$driverCredit = $_POST['dCredit'];
				$drivermake = $_POST['drivermake'];
				$driverBoth = $_POST['dboth'];
				$driverModel = $_POST['driverModel'];
                                
				$vehicle_id = $_POST['vechile_name_value'];
             // file_put_contents('amousdsnt.txt',print_r($_POST,TRUE));
				$driverTypeVech = $_POST['driverTypeVech'];
                                
                                $driverMakeOfVech = $_POST['driverMakeVec'];
                                $driverBadgeLic= $_POST['driverBadgeLic'];
                                $driverLicState= $_POST['driverLicState'];
                                $driverVechNo = $_POST['driverVechNo'];
                                //$driverSpec = $_POST['driverSpec']
                                $driverZone= $_POST['driverZone'];
                                 $driverRouteK= $_POST['driverRknow'];
                $driverPrefL= $_POST['driverPrefL'];
                $driverWoff= $_POST['driverWoff'];
				$driveimei= $_POST['driveimei'];
                $drivergps= $_POST['drivergps'];
                $driverlangw= implode(",",$_POST['write']);
                $driverlangs= implode(",",$_POST['speak']);
                $driverlogint= implode(",",$_POST['logtime']);
                $dutyAir = implode(",",$_POST['dAir']);
                $dutyOut = $_POST['dOut'];
				$dCash = implode(",",$_POST['dCash']);
				$userpass = md5($_POST['driver_pass']);
				//$userEmailIdLog = $_POST['driver_email'];
                                
                                
				$userRole = 3;
                                
                                 $vDdate = date("Y-m-d H:i:s");
                                /////////vendor ////////////////
                                // $vname = $_POST['vName'];
                                 // $vemail = $_POST['vEmail'];
                                 // $vgroup = $_POST['vGroup'];
                                 // $formLength = $_POST['flength'];
                                //////////end of vendor////////////////
                                
                               // $UserNos='';
		 
//	        if($userEmailIdLog==$driverNumber)				
//		{
//              $UserNos=$driver_email;
//                }
//		if($userEmailIdLog==$driver_email)
//                {
//               $UserNos=$driverNumber;
               file_put_contents('all.txt', print_r($_POST,TRUE)); 
//                }
		$sql = "SELECT `LoginName`,`UserNo` FROM `tbluser` WHERE `LoginName` ='$driver_email'  AND  UserNo='$driverNumber'";
                     
        $sqlcheck=$sql;
		$result = mysqli_query($this->con,$sql);
		$count = mysqli_num_rows($result);
		
				if($count <1)
				{
										
									
					$email = $_POST['driver_email'];
					$act= md5($email.time());
					
            $sql = "INSERT INTO `tbluser`(`LoginName`,`Password`,`UserType`,`UserNo`,`create_date`) VALUES ('$driver_email','$userpass','$userRole','$driverNumber',NOW())";
					$result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));			
							
					if(mysqli_insert_id($this->con) >0 ){
				
					$id = mysqli_insert_id($this->con); 
                                         $token=  uniqid();
				$image="public/userimage/$id/$token.jpeg";
                               
			 $sql= "INSERT INTO `tbluserinfo`(`UID`,`FirstName`,`LastName`,`City`,`Address1`,`Email`,`AltEmail`,`MobNo`,`LandNo`,`image`) 
						VALUES ('$id','$firstname','$secondname','$city','$driverAddress','$driver_email','$driver_email','$driverNumber','$driverNumber','$image')";
                                 mkdir("public/userimage/$id/",0777,true);	 
                                
                                 rename($_POST['image_path'],$image );
						$result = mysqli_query($this->con,$sql);
					//echo mysqli_insert_id($this->con);
							if(mysqli_insert_id($this->con) > 0){
                                                            
                             $sql ="SELECT `CabType` FROM `tblcablistmgmt` WHERE `id`='$vehicle_id'";
                                 //file_put_contents('type.txt', $sql);
                                   $result=mysqli_query($this->con,$sql);
                                   
                                   $cardata = mysqli_fetch_assoc($result);                               
                                  $driverTypeVech =$cardata[CabType]; 
                                   //file_put_contents('type.txt',$driverTypeVech);
									//$id = mysqli_insert_id($this->con);
                                                                        
                                 
                                  
                                  
									$data  = array(
                                                    'UID'=>"$id",
                                                    'FirstName' => "$firstname",
                                                    'LastName' => "$secondname",
                                                     'FatherName' => "$fathername",
                                                    'Email' => "$driver_email",
                                                    'refname' => "$refrence",
                                                    'Country'=>"India",
                                                    'State'=>"New Delhi",
                                                    'City'=>"$city",
                                                    'Address'=>"$driverAddress",
                                                    'OfcAddress'=>"$driverOfficeAddress",
                                                    'PinCode'=>"43265",
                                                    'ContactNo'=>"$driverNumber",
                                                    'AlternateContactNo'=>"$AdriverNumber",
                                                    'CompanyID'=>"123",
                                                    'RoleID'=>"3",
                                                    'DrivingLicenceNo'=>"$driverBadgeLic",
                                                    'licence_state'=>"$driverLicState",
                                                    'userimage'=>"123",
                                                    'FatherName'=>"sdhf",
                                                    'PanCardNo'=>"$driverPan",
                                                    'HavingTaxi'=>"$driverFleets",
                                                    'TotalFleetNo'=>"$driverFleets",
                                                    'TypeOfvehicle'=>"$driverTypeVech",
                                                    'vehicleId'=>"$vehicle_id",
                                                    'ModelOfVehicle'=>"$driverModel",
                                                    'MakeOfVehicle'=>"$driverMakeOfVech",
                                                    'VehicleRegistrationNo'=>"$driverVechNo",
                                                    'SecurityAmt'=>"$driverAmount",
                                             'bank_account_name'=>$_POST['driverBank'],
                                                                'acount_holder'=>$_POST['driverholdern'],
                                                                'bank_adds'=>$_POST['driverBaddess'],
                                                                'account_no'=>$_POST['driverAcNo'],
                                                                'rtgs_neft'=>$_POST['driverrtgs'],
                                                                'ibn'=>$_POST['driveribn'],
                                                                'zone'=>"$driverZone",
                                                                'route_know'=>"$driverRouteK",
                                                                'pref_location'=>"$driverPrefL",
                                                                'week_off'=>"$driverWoff",
																'imei'=>"$driveimei",
                                                                'gps'=>"$drivergps",
                                                                'lang_write'=>"$driverlangw",
                                                                'lang_speak'=>"$driverlangs",
                                                                'pref_timing'=>"$driverlogint",
            //'ReciveAirPortTrns'=>implode(',',$_POST['dair'][$i]),
            'ReciveAirPortTrns'=>"$dutyAir",
                                                                // 'ReciveAirPortTrns'=>"$driverAir",
                                                                // 'ReciveOutStatTrns'=>"$driverOut",
                                                                // 'RecieveLocalPkgTrns'=>"$driverLocal",
                                                                // 'RecievePointToPoint'=>"$driverPoints",
                                                                // 'AcceptCash'=>"$driverCash",
           // 'AcceptCash'=>implode(',',$_POST['dcash'][$i]),
            'AcceptCash'=>"$dCash",
                                                                'AcceptCreditCard'=>"$driverCredit",
                                                                'Eyetest'=>"1",

            'created_date'=>"$vDdate",
                                                                'is_delete'=>"1",

								
											);
                                                                        
 
                                     //}
                                                                        
                                                                        
									$tableName = 'tbldriver';
		  
		
											  $query = "INSERT INTO `$tableName` SET";
											   $subQuery = '';
											   foreach($data as $columnName=>$colValue) {
											      $subQuery  .= "`$columnName`='$colValue',";
											   }
											    $subQuery =  rtrim($subQuery,", ");
											   $query .= $subQuery;
										//return array('message'=>implode(',',$_POST['driver_name2']));	   
											 $result = mysqli_query($this->con,$query);
                                                                                         //file_put_contents('query.txt',$query);
											 if(mysqli_insert_id($this->con) > 0){
											 
                                                                                        
											
						 $sql = "INSERT INTO `tblactivation` (`UID`,`Verification_code`) VALUES ('$id','$act')";
											$result = mysqli_query($this->con,$sql);
											
										if(mysqli_insert_id($this->con)>0){
										//file_put_contents('gmail.txt',$driver_email);
							
												 $mail = $this->mailing($driver_email,$act);
					
						
							//if($mail == true){
									//$act= md5($email.time());
									$url = "http://push1.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91".$driverNumber."&from=Helocb&dlrreq=true&text=Thanks+for+choosing+HELLO42+CABS.+Bkg+Ref+No+foris.+We+will+revert+with+ur+bkg+confirmation.+Cnt+".$act."+or+Online+bkg+www.hello42cabs.com+from+Hello42&alert=1";
									$getmap = file_get_contents($url);
									
									$meta=array("message"=>"Success");	
									return array("status" =>$meta,"Code"=>"001");
											 				
										}
                                                                                         }
										else{
											return array("status" => "unSuccess");
										}			
											 		
											 	
											 }
												else{
													return array("status" => "unSuccess");
												} 
											  // return array("message"=>"Success");	
		  	
									
									
								
								
										}
											else{
												
											}
										
										
								}
                                                    else{
                                                    return array("status" => "unSuccess");	
                                                    }	
								
                       }
              
		public function driver_pay_information(){
            
			$qry = "select tbluserinfo.FirstName,tbluserinfo.LastName,tbldriverpayment.id as pay_id, tbldriverpayment.* from tbldriverpayment inner join tbluserinfo on tbldriverpayment.driver_id=tbluserinfo.UID where tbldriverpayment.audit_status='0'";
             $result =  mysqli_query($this->con,$qry) or die(mysqli_error($this->con));
             
             $a = 1;
             while($data = mysqli_fetch_assoc($result)){
                 
                 $devicedata[] = array($a++,$data['payment_ref_no'],$data['FirstName']." ".$data['LastName'],$data['deposit_date'],$data['transaction_mode'],$data['amount'],$data['deposit_bank'],$data['deposited_branch'],$data['status'],"<a href='#'  class='approve_driver_payment1' data='".$data['pay_id']."'>Audit</a>");
             }
            
             return array("data" => $devicedata);
        } 
		
        public function driver_payment_info(){
            
			$qry = "select tbluserinfo.FirstName,tbluserinfo.LastName,tbldriverpayment.id as pay_id, tbldriverpayment.* from tbldriverpayment inner join tbluserinfo on tbldriverpayment.driver_id=tbluserinfo.UID where tbldriverpayment.`status`='0'";
             $result =  mysqli_query($this->con,$qry) or die(mysqli_error($this->con));
             
             $a = 1;
             while($data = mysqli_fetch_assoc($result)){
                 
                 $devicedata[] = array($a++,$data['payment_ref_no'],$data['FirstName']." ".$data['LastName'],$data['deposit_date'],$data['transaction_mode'],$data['amount'],$data['deposit_bank'],$data['deposited_branch'],"<a href='#'  class='approve_driver_payment' data='".$data['pay_id']."'>Pending</a>");
             }
            
             return array("data" => $devicedata);
        } 
		
		
		public function showDriverPayment1(){
           
           $driver_pay_id = $_POST['driver_pay_id'];
           $qry = "select web_admin.full_name, tbldriverpayment.* from tbldriverpayment inner join web_admin on tbldriverpayment.user_account = web_admin.id where tbldriverpayment.id='$driver_pay_id'";
         $result =  mysqli_query($this->con,$qry) or die(mysqli_error($this->con));  
         $num_rows = mysqli_num_rows($result);
         $data = mysqli_fetch_assoc($result);
         if($num_rows>0)
		 {
         $deviceData = array(
		                        "id" =>$data['id'],
								"payment_ref_no" =>$data['payment_ref_no'],
								"driver_id" =>$data['driver_id'],
								"user_account" =>$data['full_name'],
                                "deposit_date" =>$data['deposit_date'],
                                "transaction_mode" =>$data['transaction_mode'],
								"partner_bank" =>$data['partner_bank'],
                                "amount" =>$data['amount'],
                                "deposit_bank" =>$data['deposit_bank'],
                                "deposited_branch" =>$data['deposited_branch'],
                                "remark"=>$data['remark']
                                );
         $status="true";
          return array("status" => $status,"data"=>$deviceData);
		 }
		 else
		 {
			 
		 }
       } 
	   public function showDriverPayment(){
           
           $driver_pay_id = $_POST['driver_pay_id'];
           $qry = "select * from tbldriverpayment  where id='$driver_pay_id'";
         $result =  mysqli_query($this->con,$qry) or die(mysqli_error($this->con));  
         
         $data = mysqli_fetch_assoc($result);
         
         $deviceData = array(
		                        "id" =>$data['id'],
								"payment_ref_no" =>$data['payment_ref_no'],
                                "deposit_date" =>$data['deposit_date'],
                                "transaction_mode" =>$data['transaction_mode'],
								"partner_bank" =>$data['partner_bank'],
                                "amount" =>$data['amount'],
                                "deposit_bank" =>$data['deposit_bank'],
                                "deposited_branch" =>$data['deposited_branch'],
                                "remark"=>$data['remark']
                                );
       
                return array("data"=>$deviceData);
           
       }
                       
        public function device_mang(){
            
             $result =  mysqli_query($this->con,"CALL `wp_a_deviceList`()") or die(mysqli_error($this->con));
             
             $a = 1;
             while($data = mysqli_fetch_assoc($result)){
                 
                 $devicedata[] = array("<input name='drives_id[]' type=checkbox value='".$data['unit_no']."'>",$a++,$data['unit_no'],$data['purchase_date'],$data['activation_date'],$data['sim_no'],$data['port'],$data['vehicleNumber'],"<a href='#'  class='deviceedit' data='".$data['unit_no']."'>EDIT</a>");
             }
            
             return array("data" => $devicedata);
        }
        
        public function device_add(){
            
            
                $dunit = $_POST['device_unitno'];
                $dpdate = $_POST['device_pdate'];
                $dadate = $_POST['device_adate'];
                $dsim = $_POST['device_sim'];
                $dport = $_POST['device_portno'];
                $dtaxino = $_POST['device_taxi'];
                //file_put_contents('ho.txt', print_r($_POST,TRUE));
                
            $result =  mysqli_query($this->con,"CALL `wp_a_addDevice`('$dunit','$dpdate','$dadate','$dsim','$dport','$dtaxino')") or die(mysqli_error($this->con));
            
            $data = mysqli_fetch_assoc($result);
            
            if($data['untid']>=1){
                return array("data" => "001");
            }
            else if($data['devicesId']>1){
                
                 return array("data" => "true");
            }
            else{
                return array("data" => "false");
            }
        }
       
      public function deleteDevice(){
          
          $count =0;
       $devicedata= $_POST['deviceIds'];
       $deviceid =explode(',',$devicedata) ;
       $deviceLength = sizeof($deviceid);
      
       for($i=0;$i<$deviceLength;$i++){
      
           
       $result =  mysqli_query($this->con,"CALL `wp_a_deviceDelete`('$deviceid[$i]]')") or die(mysqli_error($this->con));
       if($result == 1)
           {
           $count++;
           
           }
       mysqli_free_result($result); 
       //$count++;
           mysqli_next_result($this->con);
          
       }
       //$data = mysqli_fetch_assoc($result);
       //$data = mysqli_fetch_assoc($result);
      //$update = $data['affectedrow'];
      //file_put_contents('d.txt', $count);
       if($count > 0){
           
           return array("status"=>"true");
           
       }
       else{
           return array("status"=>"false");
       }
      }

      
       public function editDevice(){
           
           $deviceUnit = $_POST['deviceIds'];
           
         $result =  mysqli_query($this->con,"CALL `wp_a_deviceEdit`('$deviceUnit')") or die(mysqli_error($this->con));  
         
         $data = mysqli_fetch_assoc($result);
         
         $deviceData = array(
                                "unit" =>$data['unit_no'],
                                "pdate" =>$data['purchase_date'],
                                "adate" =>$data['activation_date'],
                                "sim" =>$data['sim_no'],
                               "port" =>$data['port'],
                               "taxi"=>$data['vehicleNumber']
                                );
           
                return array("data"=>$deviceData);
           
       } 
       
       public function updateDevice(){
           
                    $unitno = $_POST['device_unitno_update'];
                    $dpdate = $_POST['device_pdate'];
                    $dadate = $_POST['device_adate'];
                    $dsim = $_POST['device_sim'];
                    $dport = $_POST['device_portno'];
                    $dtaxi =$_POST['device_taxiUpdate'];
                    
                    file_put_contents('dewe.txt', print_r($_POST,TRUE));
       $result =  mysqli_query($this->con,"CALL `wp_a_deviceUpdate`('$unitno','$dpdate','$dadate','$dsim','$dport','$dtaxi')") or die(mysqli_error($this->con)); 
            
            $data = mysqli_fetch_assoc($result);
            if($data['ids'] == 1){
                
                return array("response"=>"true");
            }
            else{
                return array("response"=>"false");
            }
            
           
       }
	    public function payment_audit(){
           
                  $DPaymentid = $_POST['DPaymentid']; 
				  $credit_date = $_POST['credit_date'];
				  if($_POST['credit_amount']!=""){
				  $credit_amount = $_POST['credit_amount'];
				  }else{
					  $credit_amount = $_POST['amount'];
				  }
				  $drivid = $_POST['drivid'];
				  if($_POST['admin_remarks']!="")
				  {
					$admin_remarks = $_POST['admin_remarks']; 
				  }else{
					  $admin_remarks = $_POST['remark']; 
				  }
				  
                  $qry = "update tbldriverpayment set credit_date = '$credit_date',credit_amount = '$credit_amount', admin_remarks = '$admin_remarks',audit_status = '1' where id = '$DPaymentid'";
          $result =  mysqli_query($this->con,$qry) or die(mysqli_error($this->con)); 
            
            //$data = mysqli_fetch_assoc($result);
            if($result>0){
				$qur2 = "select SecurityAmt,FirstName,LastName,ContactNo from tbldriver where UID= '$drivid'";
				$result2 =  mysqli_query($this->con,$qur2) or die(mysqli_error($this->con)); 
				$res = mysqli_fetch_assoc($result2);
				
				//$time = date("Y-m-d H:m:s");
				
				if($result>0){
					$SecuAmt = $res['SecurityAmt']+$credit_amount;
					$qry3 = "update tbldriver set SecurityAmt = '$SecuAmt' where UID = '$drivid'";
					$result =  mysqli_query($this->con,$qry3) or die(mysqli_error($this->con)); 
					
					 $qur4 = "INSERT INTO `tbl_driver_transaction` (`user_id`, `amount`, `currentbalance`, `status`, `reason`) VALUES('$drivid','$credit_amount','$SecuAmt','Credit','$admin_remarks')";
					$result4 =  mysqli_query($this->con,$qur4) or die(mysqli_error($this->con));
				}
				
			///// Code to send SMS Starts Here ////
			
			$driverName=$res['FirstName'].' '.$res['LastName'];
			$date=date('d-m-Y h:i:s');
			$query=mysqli_query($this->con,"SELECT * FROM tbl_sms_template WHERE msg_sku='driver_acc_bal2'")or die(mysqli_error($this->con));
			$result1=mysqli_fetch_assoc($query);
			$explode=explode('<variable>',$result1['message']);
			$explode[0]=$explode[0].$driverName;
			$explode[1]=$explode[1].$credit_amount;
			$explode[2]=$explode[2].$res['ContactNo'];
			$explode[3]=$explode[3].$date;
			$explode[4]=$explode[4].$SecuAmt;
			$mobile=$res['ContactNo'];
			$text=  urlencode(implode("",$explode));	
			//file_put_contents("mssg.txt",$text);
			$url="http://push3.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$mobile&from=Helocb&dlrreq=true&text=$text&alert=1";
			file_get_contents($url);

			///// Code to send SMS Ends Here                
                return array("response"=>"true");
            }
            else{
                return array("response"=>"false");
            }
       }
	   
	   public function payment_approved(){
           
                   $DPaymentid = $_POST['DPaymentid'];
                   $user_id = $_POST['user_id'];				   
                    $qry = "update tbldriverpayment set status = '1',user_account='$user_id' where id = '$DPaymentid'";
          $result =  mysqli_query($this->con,$qry) or die(mysqli_error($this->con)); 
            
            //$data = mysqli_fetch_assoc($result);
            if($result>0){
                
                return array("response"=>"true");
            }
            else{
                return array("response"=>"false");
            }
            
           
       }
	   
       public function alltaxiesNo(){
            $data = $_REQUEST['term'];
           
        $result =  mysqli_query($this->con,"CALL `wp_a_deviceTaxiNos`('$data')") or die(mysqli_error($this->con));   
        
        
        
        while($data = mysqli_fetch_assoc($result)){
          
            $taxiesNo[] = array("value"=>$data['id'],"label"=>$data['vehicleNumber']); 
            
            }
                echo json_encode($taxiesNo);
                
                return $taxiesNo;
        
        
       } 
       
	/*public function assign_booking(){
		return array("status"=>"true");
		$driver_id=$_REQUEST['driver_id'];
		$booking_ref=$_REQUEST['ref'];
		$result =  mysqli_query($this->con,"CALL `assign_booking`('$driver_id','$booking_ref')") or die(mysqli_error($this->con));   
		$error=0;
		$msg="";
		while($data = mysqli_fetch_assoc($result)){
			if($data['bno']==0 or $data['dno']==0 or $data['lno']==0){
					$error=1;
				if($data['bno']==0){
					$msg="Already Bidded";
				}else{
					if($data['lno']==0){
						$msg="Driver Logged Out";
					}else{
						$msg="Driver already Hired";
					}
				}
			}
		}
		if($error==0){
			return array("status"=>"true");
		}else{
			return array("status"=>"false","msg"=>$msg);
		}
	}*/
        
		
		public function bookingref(){
            $data = $_REQUEST['term'];
           
        $result =  mysqli_query($this->con,"SELECT tblcabbooking.booking_reference FROM tblcabbooking WHERE tblcabbooking.booking_reference LIKE '%$data%' AND pickup=0 AND .CONCAT(pickupdate,' ',pickuptime)>NOW()");   
        
        
        
        while($data = mysqli_fetch_assoc($result)){
          
            $taxiesNo[] = array("value"=>$data['booking_reference'],"label"=>$data['booking_reference']); 
            
            }
                echo json_encode($taxiesNo);
                
                return $taxiesNo;
        
        
       } 
        public function alldriver(){
            $data = $_REQUEST['term'];
           
        $result =  mysqli_query($this->con,"CALL `wp_a_driver_search`('$data')") or die(mysqli_error($this->con));   
        
        
        
        while($data = mysqli_fetch_assoc($result)){
          
            $taxiesNo[] = array("value"=>$data['UID'],"label"=>$data['name']); 
            
            }
                echo json_encode($taxiesNo);
                
                return $taxiesNo;
        
        
       }
       
	

		 
		 // fair management and vehicle management
		 
  public function reverse_geocode() {    
    $addlat = $_POST['currentLat'];
    $addlong = $_POST['currentlong'];
    $address = $addlat." ".$addlong;    
    ///file_put_contents('loca.txt', $address);

    $address = str_replace(" ", "+", "$address");

    $url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false";

    $result = file_get_contents("$url");

    $json = json_decode($result);

    foreach ($json->results as $result)

    {

        foreach($result->address_components as $addressPart) {

            if((in_array('locality', $addressPart->types)) && (in_array('political', $addressPart->types)))

                $city = $addressPart->long_name;

            else if((in_array('administrative_area_level_1', $addressPart->types)) && (in_array('political', $addressPart->types)))

                $state = $addressPart->long_name;

            else if((in_array('country', $addressPart->types)) && (in_array('political', $addressPart->types)))

                $country = $addressPart->long_name;

        }

    }
    if(($city != '') && ($state != '') && ($country != ''))

        $address = $city.', '.$state.', '.$country;

    else if(($city != '') && ($state != ''))

        $address = $city.', '.$state;

    else if(($state != '') && ($country != ''))

        $address = $state.', '.$country;

    else if($country != '')

        $address = $country;

         

    // return $address;
    $locationcurrent_state = "$state";
     $locationcurrent_country = "$country";
   // file_put_contents('loc.txt', $locationcurrent);

    return array("location"=>$locationcurrent_state,"country"=>$locationcurrent_country,"city"=>$city);
    
    }
	
	public function getAllCabType(){
		$city = $_REQUEST['city'];
		$state = $_REQUEST['state'];
		$precedure="CALL `wp_a_getallCabType`('$city','$state')";
		$dataCab = mysqli_query($this->con,$precedure) or die(mysqli_error($this->con));
		$i = 1;
		$nan = '<p class="text-danger">NA</p>';
		while($row=mysqli_fetch_assoc($dataCab)){
			if($row['RateCatId'] == 1){
				$CalculatedBy = 'Distance';
				$RatePerKm = $row['Per_Km_Charge'];
				$RatePerHr = $nan;
				$MinimumCharge = $row['MinimumCharge'];
			}elseif($row['RateCatId'] == 2){
				$CalculatedBy = 'Hour';
				$RatePerKm = $nan;
				$RatePerHr = $row['tripCharge_per_minute'];
				$MinimumCharge = $nan;
			}elseif($row['RateCatId'] == 3){
				$CalculatedBy = 'Distance + Hour';
				$RatePerKm = $row['rate_per_km_dh'];
				$RatePerHr = $row['rate_per_hour_dh'];
				$MinimumCharge = $row['minimum_fare_dh'];
			}elseif($row['RateCatId'] == 4){
				$CalculatedBy = 'Distance + Waiting';
				$RatePerKm = $row['rate_per_km_dw'];
				$RatePerHr = $nan;
				$MinimumCharge = $row['minimum_fare_dw'];
			}else{
				$CalculatedBy = 'Not Defined';
				$RatePerKm = $nan;
				$RatePerHr = $nan;
				$MinimumCharge = $nan;
			}
		$rowCab[]=array($i++,$row['FormName'],$CalculatedBy,'<a data = "'.$row['BookingTypeId'].','.$row['CabTypeId'].'" class="clickCabType" href="javascript:void(0)" color:#08c; cursor:pointer;">'.$row['CabName'].'</a>',$row['CountryName'],$row['state'],$row['name'],$RatePerKm,$RatePerHr,$MinimumCharge,'<button class="btn btn-primary manageCabTypeFair" id="cab_'.$row['Id'].'_'.$row['CabTypeId'].'_'.$row['BookingTypeId'].'_'.$row['CityId'].'">Manage Fare</button>');
		}
		return array("data"=>$rowCab);
	}
	
	
	public function searchCabType()
	{
		//$space = array(" ");
		$city = $_REQUEST['city'];
		$state = $_REQUEST['state'];
		$country = $_REQUEST['country'];
		$vehicle_no = $_REQUEST['vehicle_no'];
		$vehicle_model = $_REQUEST['vehicle_model'];
		$since = $_REQUEST['since'];
		$to = $_REQUEST['to'];
		
		if($since != "" && $to == ""){
			$to = date("Y-m-d");
		}elseif($since == "" && $to != ""){
			$since = '0000-00-00';
		}elseif($since == "" && $to == ""){
			$since = '0000-00-00';
			$to = date("Y-m-d");
		}
		
		$dataCab = mysqli_query($this->con,"CALL `wp_a_searchCabType`('$city','$state','$country','$vehicle_no','$vehicle_model','$since','$to')") or die(mysqli_error($this->con));
        $i = 1;
		
        while($row=mysqli_fetch_assoc($dataCab))
           {
             
			   //$rowCab[]=array($i++,'<a data = "'.$row['CabType'].'" class="clickCabType" href="javascript:void(0)" color:#08c; cursor:pointer;">'.$row['CabName'].'</a>',$row['CountryName'],$row['state'],$row['name'],$row['Per_Km_Charge'],$row['tripCharge_per_minute'],$row['MinimumCharge'],'<button class="btn btn-primary manageCabTypeFair" id="cab_'.$row['Id'].'">Manage Fair</button>');
               $rowCab[]=array($i++,$row['vehicleNumber'],$row['CabType'],$row['name'],$row['Manufacturer'],$row['VehicleModel'],$row['Registration_Date'],$row['Per_Km_Charge'],$row['tripCharge_per_minute'],$row['MinimumCharge'],'<button class="btn btn-primary manageVehicleFair" id="vehicle_'.$row['Id'].'">Manage Fair</button>');
           }
		
		if(mysqli_num_rows($dataCab)> 0){
			return array("status"=>"true","data"=>$rowCab);
		}else{
			return array("status"=>"false","data"=>"");
		}
	
}

public function searchEnquiryList()
{
		//$space = array(" ");
		$createdSince = $_REQUEST['created_since'];
		$createdTo = $_REQUEST['created_to'];
		$closedSince = $_REQUEST['closed_since'];
		$closedTo = $_REQUEST['closed_to'];
		$enquiryType_Text = $_REQUEST['enquiry_Type_Text'];
		$enquiryStatus_Text = $_REQUEST['enquiry_Status_Text'];
		
		if($createdSince != "" && $createdTo == ""){
			$createdTo = date('Y-m-d');
		}elseif($createdSince == "" && $createdTo != ""){
			$createdSince = '0000-00-00';
		}elseif($createdSince == "" && $createdTo == ""){
			$createdSince = '0000-00-00';
			$createdTo = date('Y-m-d');
		}
		
		if($closedSince != "" && $closedTo == ""){
			$closedTo = date('Y-m-d');
		}elseif($closedSince == "" && $closedTo != ""){
			$closedSince = '0000-00-00';
		}elseif($closedSince == "" && $closedTo == ""){
			$closedSince = '0000-00-00';
			$closedTo = date('Y-m-d');
		}
		
		$dataCab = mysqli_query($this->con,"CALL `wp_a_searchEnquiryList`('$createdSince','$createdTo','$closedSince','$closedTo','$enquiryType_Text','$enquiryStatus_Text')") or die(mysqli_error($this->con));
        
            while($row=mysqli_fetch_assoc($dataCab))
           {
               $rowCab[]=array('<a data = "'.$row['Id'].'" class="clickComplaintType" href="javascript:void(0)" color:#08c; crsor:pointer;">'.$row['Id'].'</a>',$row['CallerName'],$row['EmailId'],$row['ContactNo'],$row['CSRID'],$row['Message'],$row['EnqType'],$row['EnquiryDate'],$row['Status'],$row['updated_by'],$row['ClosedDate']);
           }
		
		if(mysqli_num_rows($dataCab)> 0){
			return array("status"=>"true","data"=>$rowCab);
		}else{
			return array("status"=>"false","data"=>"");
		}
	
}


public function searchComplaintType(){

		//$space = array(" ");
		$bookingID = $_REQUEST['booking_id'];
		$complaintTypeText = $_REQUEST['complaint_TypeText'];
		$createdSince = $_REQUEST['created_since'];
		$createdTo = $_REQUEST['created_to'];
		$closedSince = $_REQUEST['closed_since'];
		$closedTo = $_REQUEST['closed_to'];
		$complaintStatus_Text = $_REQUEST['complaint_Status_Text'];
		
		if($createdSince != "" && $createdTo == ""){
			$createdTo = date('Y-m-d');
		}elseif($createdSince == "" && $createdTo != ""){
			$createdSince = '0000-00-00';
		}elseif($createdSince == "" && $createdTo == ""){
			$createdSince = '0000-00-00';
			$createdTo = date('Y-m-d');
		}
		
		if($closedSince != "" && $closedTo == ""){
			$closedTo = date('Y-m-d');
		}elseif($closedSince == "" && $closedTo != ""){
			$closedSince = '0000-00-00';
		}elseif($closedSince == "" && $closedTo == ""){
			$closedSince = '0000-00-00';
			$closedTo = date('Y-m-d');
		}
		
		$data = mysqli_query($this->con,"CALL `wp_a_searchComplaintType`('$bookingID','$complaintTypeText','$createdSince','$createdTo','$closedSince','$closedTo','$complaintStatus_Text')") or die(mysqli_error($this->con));
        
        $complaintbookings=array();
		while($row=mysqli_fetch_array($data))
		{
			//print_r($row);
			if($row['totalBill']!="")
			{
				$row['totalBill']="INR ".$row['totalBill'];
				
			}
			if($row['reg']!=0)
			{
				$row['reg']="(".$row['reg'].")";
				
			}
			$complaint="";
			$comp_col="";
			if($row['com_id']!="")
			{
			 $complaint="<br><br><small>Complaint: ".$row['com_status']."</small>";
			 $comp_col="<div style='width:10px;height:10px;border-radius:100%;background:red'></div>";

			}		
				
			$complaintbookings[]=array('<label data="'.$row['id'].'" class="clickme" style="padding: 0px 10px 0px 0;color:#08c; cursor:pointer;">'.$row['ref'].'</label>',date("M-j-y H:i",strtotime($row['ordertime']))."<br><small style='color:gray'>".'</small>',"<div data='".$row['id']."'  style='cursor:pointer;' class='driver_click'>".$row['vehicle'].'<br/><small style="color:gray;text-transform:capitalize"  class="driver_click">'.$row['driver_name'].'</small><br><small>'.$row['reg'].'</small>'.$comp_col."</div>",$row['booking_type'],$row['partner'].'<br>'.ucfirst($row['emp']),'<label class="client" data="'.$row['client_id'].'">'.$row['clientname'].'</label><br/><small style="color:gray">'.$row['mob_no'].'</small>',ucfirst($row['departure']),ucfirst($row['drop_area']),$row['status'].'<br/><small style="color:gray;text-transform:capitalize">'.$row['totalBill'].'</small>'.$complaint,"<label style='padding: 0px 10px 0px 0;color:#08c; cursor:pointer;' class='action' data='".$row['id']."'>Action</label>");
		}
		
		if(mysqli_num_rows($data)> 0){
			return array("status"=>"true","data"=>$complaintbookings);
		}else{
			return array("status"=>"false","data"=>"");
		}
		
	
}

public function searchVehicleType()
{
		//$space = array(" ");
		$city = $_REQUEST['city'];
		$state = $_REQUEST['state'];
		$country = $_REQUEST['country'];
		$vehicle_no = $_REQUEST['vehicle_no'];
		$vehicle_owner = $_REQUEST['vehicle_owner'];
		$cab_type = $_REQUEST['cab_type'];
		
		$dataCab = mysqli_query($this->con,"CALL `wp_a_searchVehicleType`('$vehicle_no','$cab_type','$city','$state','$country','$vehicle_owner')") or die(mysqli_error($this->con));
       
                while($row=mysqli_fetch_assoc($dataCab))
		{
			$rowCab[]=array($row['id'],$row['vehicleNumber'],$row['name'],$row['VehicleModel'],$row['CabName'],$row['Manufacturer'],$row['capacity'],$row['fuelType'],$row['colour'],$row['Registration_Date'],'<button data="'.$row['id'].'" class="btn btn-primary editVehicle">Edit Vehicle</button>');
		}
		
		if(mysqli_num_rows($dataCab)> 0){
			return array("status"=>"true","data"=>$rowCab);
		}else{
			return array("status"=>"false","data"=>"");
		}
	
}

public function searchDriverGroup()
{
		//$space = array(" ");
		$grp_id = $_REQUEST['group_Id'];
		$grp_name = $_REQUEST['group_Name'];
		$veh_reg_no = $_REQUEST['group_Vehicle'];
		$veh_ext_ref = $_REQUEST['group_Ext'];
		$driver_name = $_REQUEST['group_Dname'];
		$driver_ext_ref = $_REQUEST['group_DExt'];
		
		$data = mysqli_query($this->con,"CALL `wp_a_searchDriverGroup`('$grp_id','$grp_name','$veh_reg_no','$veh_ext_ref','$driver_name','$driver_ext_ref')") or die(mysqli_error($this->con));
                $a=1;
                while($row=mysqli_fetch_assoc($data))
		{
			$group[] = array($a++,"<a href='#' class='clickgroup' data='".$row['UID']."'>".$row['UID']."</a>",$row['group_name'],"<a href='#'>".$row['vendor_name']."</a>");
		}
		
		if(mysqli_num_rows($data)> 0){
			return array("status"=>"true","data"=>$group);
		}else{
			return array("status"=>"false","data"=>"");
		}
	
}


public function searchGroupByDriver()
{
		//$space = array(" ");
		$grp_id = $_REQUEST['driver_Id'];
		$grp_name = $_REQUEST['driver_Name'];
		$veh_reg_no = $_REQUEST['driver_Vehicle'];
		$veh_ext_ref = $_REQUEST['driver_Ext'];
		$driver_name = $_REQUEST['driver_Dname'];
		$driver_ext_ref = $_REQUEST['driver_DExt'];
		
		$data = mysqli_query($this->con,"CALL `wp_a_searchGroupDriver`('$grp_id','$grp_name','$veh_reg_no','$veh_ext_ref','$driver_name','$driver_ext_ref')") or die(mysqli_error($this->con));
                $a=1;
                while($row=mysqli_fetch_assoc($data))
		{
			$group[] = array($a++,"<a href='#' class='clickgroup' data='".$row['UID']."'>".$row['UID']."</a>",$row['group_name'],"<a href='#'>".$row['vendor_name']."</a>");
		}
		
		if(mysqli_num_rows($data)> 0){
			return array("status"=>"true","data"=>$group);
		}else{
			return array("status"=>"false","data"=>"");
		}
	
}

public function searchGroupByVehicle()
{
		//$space = array(" ");
		$vehPlate = $_REQUEST['veh_plate'];
		$vehMake = $_REQUEST['veh_make'];
		$vehModel = $_REQUEST['veh_model'];
		$veh_ext_ref = $_REQUEST['veh_Ext'];
		
		$data = mysqli_query($this->con,"CALL `wp_a_searchVehicleGroup`('$vehPlate','$vehMake','$vehModel','$veh_ext_ref')") or die(mysqli_error($this->con));
                $a=1;
                while($row=mysqli_fetch_assoc($data))
		{
			$group[] = array($a++,"<a href='#' class='clickgroup' data='".$row['UID']."'>".$row['UID']."</a>",$row['group_name'],"<a href='#'>".$row['vendor_name']."</a>");
		}
		
		if(mysqli_num_rows($data)> 0){
			return array("status"=>"true","data"=>$group);
		}else{
			return array("status"=>"false","data"=>"");
		}
	
}
	
	
	function vehicle_getAllCabType(){
		$city = $_REQUEST['city'];
		$state = $_REQUEST['state'];
		
		
		$dataCab = mysqli_query($this->con,"CALL `wp_a_getallCabType`('$city','$state')") or die(mysqli_error($this->con));
        $i = 1;
        while($row=mysqli_fetch_assoc($dataCab))
           {
             
			   $rowCab[]=array($i++,'<a data = "'.$row['CabType'].'" class="clickCabType" href="javascript:void(0)" color:#08c; cursor:pointer;">'.$row['CabName'].'</a>',$row['CountryName'],$row['state'],$row['name'],'<button data="'.$row['Id'].'" class="btn btn-primary editCabType">Edit Cab Type</button>');
               
           }
		
		return array("data"=>$rowCab);
	
	}

	
	
	function vehicleInfo(){
		$cabId = $_REQUEST['cabId'];
		$city = $_REQUEST['city'];
		$BookingTypeId = $_REQUEST['BookingTypeId'];
		$sql = "CALL `wp_a_getVehicleInfo`('$BookingTypeId','$cabId','$city')";
		$dataCab = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
		while($row = mysqli_fetch_assoc($dataCab)){
			
			$nan = '<p class="text-danger">NA</p>';
			if($row['RateCatId'] == 1){
				$CalculatedBy = 'Distance';
				$RatePerKm = $row['Per_Km_Charge'];
				$RatePerHr = $nan;
				$MinimumCharge = $row['MinimumCharge'];
			}elseif($row['RateCatId'] == 2){
				$CalculatedBy = 'Hour';
				$RatePerKm = $nan;
				$RatePerHr = $row['tripCharge_per_minute'];
				$MinimumCharge = $nan;
			}elseif($row['RateCatId'] == 3){
				$CalculatedBy = 'Distance + Hour';
				$RatePerKm = $row['rate_per_km_dh'];
				$RatePerHr = $row['rate_per_hour_dh'];
				$MinimumCharge = $row['minimum_fare_dh'];
			}elseif($row['RateCatId'] == 4){
				$CalculatedBy = 'Distance + Waiting';
				$RatePerKm = $row['rate_per_km_dw'];
				$RatePerHr = $nan;
				$MinimumCharge = $row['minimum_fare_dw'];
			}else{
				$CalculatedBy = 'Not Defined';
				$RatePerKm = $nan;
				$RatePerHr = $nan;
				$MinimumCharge = $nan;
			}
			$row['RatePerKm'] = $RatePerKm;
			$row['RatePerHr'] = $RatePerHr;
			$row['MinimumCharge'] = $MinimumCharge;
			$rowCab[]= $row;
		}
		return array("data"=>$rowCab);
	}
	
	function vehicleInfoById(){
		$vehicleId = $_REQUEST['vehicleId'];
				
		$dataCab = mysqli_query($this->con,"CALL `wp_a_getVehicleInfoById`('$vehicleId')") or die(mysqli_error($this->con));
        
        while($row = mysqli_fetch_assoc($dataCab))
           {
			   $rate_upto_distance = $row['rate_upto_distance'];
			   $rate_upto_distanceArr = json_decode($rate_upto_distance);
			   
			   $package_info_distance = $row['package_info_distance'];
			   $package_info_distanceArr = json_decode($package_info_distance);
			   
			   $package_info_hourly = $row['package_info_hourly'];
			   $package_info_hourlyArr = json_decode($package_info_hourly);
			   
			   $package_info_distance_hourly = $row['package_info_distance_hourly'];
			   $package_info_distance_hourlyArr = json_decode($package_info_distance_hourly);
			   
			   $package_info_distance_waiting = $row['package_info_distance_waiting'];
			   $package_info_distance_waitingArr = json_decode($package_info_distance_waiting);
			   
			   $waitingfee_upto_minutes = $row['waitingfee_upto_minutes'];
			   $waitingfee_upto_minutesArr = json_decode($waitingfee_upto_minutes);
			   
			   $waitningfeeKey = array();
			   $waitingfeeValue = array();
			   
			   for($i=0;$i<count($waitingfee_upto_minutesArr);$i++){
				  
				  $wtngFee = explode("_",$waitingfee_upto_minutesArr[$i]);
				   array_push($waitningfeeKey,$wtngFee[0]);
				   array_push($waitingfeeValue,$wtngFee[1]);	
			  }
			   
			   $postcode_to_postcode_fair = $row['postcode_to_postcode_fair'];
			   $postcode_to_postcode_fairArr = json_decode($postcode_to_postcode_fair);
			   
			   $postCodePickup = array();
			   $postCodeDrop = array();
			   $postCodeRs = array();
			   
			   for($i=0;$i<count($postcode_to_postcode_fairArr);$i++){
				  
				  $postCodearr = explode("_",$postcode_to_postcode_fairArr[$i]);
				   array_push($postCodePickup,$postCodearr[0]);
				   array_push($postCodeDrop,$postCodearr[1]);
				   array_push($postCodeRs,$postCodearr[2]);				   
			  }
			   
			   $row['postCodePickup'] = $postCodePickup;
			   $row['postCodeDrop'] = $postCodeDrop;
			   $row['postCodeRs'] = $postCodeRs;
			   
			   
			   
			   $tripCharge_per_minute = $row['tripCharge_per_minute'];
			   
			   $tripCharge_per_hour = $tripCharge_per_minute ;
			   $extras = $row['extras'];
			   $extrasArr = json_decode($extras);
			   
			   $extraType = array();
			   $extraRate = array();
			   $extraRateUnit = array();
			   
			   for($i=0;$i<count($extrasArr);$i++){
				   $extrakey = explode("_",$extrasArr[$i]);
				   array_push($extraType,$extrakey[0]);
				   array_push($extraRate,$extrakey[1]);
				   array_push($extraRateUnit,$extrakey[2]);	
			   }
			   
			   $rateDistanceKey = array();
			   $rateDistanceValue = array();
			   
			   for($i=0;$i<count($rate_upto_distanceArr);$i++){
				  
				  $ratekey = explode("_",$rate_upto_distanceArr[$i]);
				   array_push($rateDistanceKey,$ratekey[0]);
				   array_push($rateDistanceValue,$ratekey[1]);	
			  }
				
				
			    ///// Calculation for Distance wise package Starts Here ////
			  
			  $packageinfoDistancepackageName = array();
			  $packageinfoDistanceignorekm = array();
			  $packageinfoDistanceAmount = array();
			   
			   for($i=0;$i<count($package_info_distanceArr);$i++){
				  
				  $packageinfodistance_key = explode("_",$package_info_distanceArr[$i]);
				   array_push($packageinfoDistancepackageName,$packageinfodistance_key[0]);
				   array_push($packageinfoDistanceignorekm,$packageinfodistance_key[1]);
				   array_push($packageinfoDistanceAmount,$packageinfodistance_key[2]);
			  }
			  
			  ///// Calculation for Distance wise package Ends Here ////
			  
			  
			  ///// Calculation for Hourly wise package Starts Here ////
			  
			  $packageinfoHourlypackageName = array();
			  $packageinfoHourlyignorehr = array();
			  $packageinfoHourlyAmount = array();
			   
			   for($i=0;$i<count($package_info_hourlyArr);$i++){
				  
				  $packageinfohourly_key = explode("_",$package_info_hourlyArr[$i]);
				   array_push($packageinfoHourlypackageName,$packageinfohourly_key[0]);
				   array_push($packageinfoHourlyignorehr,$packageinfohourly_key[1]);
				   array_push($packageinfoHourlyAmount,$packageinfohourly_key[2]);
			  }
			  
			  ///// Calculation for Hourly wise package Ends Here ////
			  
			  
			  //// Calculation for Distance+Hourly wise Package Starts Here ////
			  
			  $packageinfoDistHourlypackageName = array();
			  $packageinfoDistHourlyignorehr = array();
			  $packageinfoDistHourlyignorekm = array();
			  $packageinfoDistHourlyAmount = array();
			   
			   for($i=0;$i<count($package_info_distance_hourlyArr);$i++){
				  
				  $packageinfodisthourly_key = explode("_",$package_info_distance_hourlyArr[$i]);
				   array_push($packageinfoDistHourlypackageName,$packageinfodisthourly_key[0]);
				   array_push($packageinfoDistHourlyignorehr,$packageinfodisthourly_key[1]);
				   array_push($packageinfoDistHourlyignorekm,$packageinfodisthourly_key[2]);
				   array_push($packageinfoDistHourlyAmount,$packageinfodisthourly_key[3]);
			  }
			  
			   //// Calculation for Distance+Hourly wise Package Ends Here ////
			   
			 //// Calculation for Distance+Waiting wise Package Starts Here ////
			  
			  $packageinfoDistWaitingpackageName = array();
			  $packageinfoDistWaitingignorekm = array();
			  $packageinfoDistWaitingAmount = array();
			   
			   for($i=0;$i<count($package_info_distance_waitingArr);$i++){
				  
				  $packageinfodistwait_key = explode("_",$package_info_distance_waitingArr[$i]);
				   array_push($packageinfoDistWaitingpackageName,$packageinfodistwait_key[0]);
				   array_push($packageinfoDistWaitingignorekm,$packageinfodistwait_key[1]);
				   array_push($packageinfoDistWaitingAmount,$packageinfodistwait_key[2]);
			  }
			 
			  //// Calculation for Distance+Waiting wise Package Ends Here ////
			  
    		   $nightPremium = $row['NightCharges'];
			   $premiums = $row['premiums'];
			   $cancellationFee = $row['cancellation_fees'];
			   $cancellation_feesArr = explode(" ",$cancellationFee);			   
			   
			   $nightPremiumArr  =  explode(" ",$nightPremium);
			   $premiumsArr  =  explode(" ",$premiums);
			   
			   $row['cancellation_fees'] = $cancellation_feesArr[0];
			   $row['cancellation_feesUnit'] = $cancellation_feesArr[1];
			   
			   $row['premiums'] = $premiumsArr[0];
			   $row['premiumsUnit'] = $premiumsArr[1];
			   
			   $row['NightCharges'] = $nightPremiumArr[0];
			   $row['NightChargesUnit'] = $nightPremiumArr[1];
			   
			   $row['rateDistanceKm'] = $rateDistanceKey;
			   $row['rateDistanceRate'] = $rateDistanceValue;
			   
			   
				$row['packageinfoDistancepackageName'] 		= $packageinfoDistancepackageName;
				$row['packageinfoDistanceignorekm'] 		= $packageinfoDistanceignorekm;
				$row['packageinfoDistanceAmount'] 			= $packageinfoDistanceAmount;

				$row['packageinfoHourlypackageName'] 		= $packageinfoHourlypackageName;
				$row['packageinfoHourlyignorehr'] 			= $packageinfoHourlyignorehr;
				$row['packageinfoHourlyAmount'] 			= $packageinfoHourlyAmount;

				$row['packageinfoDistHourlypackageName'] 	= $packageinfoDistHourlypackageName;
				$row['packageinfoDistHourlyignorehr'] 		= $packageinfoDistHourlyignorehr;
				$row['packageinfoDistHourlyignorekm'] 		= $packageinfoDistHourlyignorekm;
				$row['packageinfoDistHourlyAmount'] 		= $packageinfoDistHourlyAmount;

				$row['packageinfoDistWaitingpackageName'] 	= $packageinfoDistWaitingpackageName;
				$row['packageinfoDistWaitingignorekm'] 		= $packageinfoDistWaitingignorekm;
				$row['packageinfoDistWaitingAmount'] 		= $packageinfoDistWaitingAmount;
			   
			   
			   $row['waitningfeeMinutes'] = $waitningfeeKey;
			   $row['waitingfeeValue'] = $waitingfeeValue;
			   
			   $row['extraType'] = $extraType;
			   $row['extraRate'] = $extraRate;
			   $row['extraRateUnit'] = $extraRateUnit;
			   $row['tripCharge_per_hour'] = $tripCharge_per_hour;
			   
			   
			   $rowCab[]= $row;
			   
			  
			   
               
           }
		
		//print_r($rowCab);
		
		return array("data"=>$rowCab);
	
	}
	
	function cabTypeInfoById(){
		$Id = $_REQUEST['Id'];
		$sql="CALL `wp_a_getCabTypeInfoById`('$Id')";		
		$dataCab = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
        
		
        while($row = mysqli_fetch_assoc($dataCab))
           {
			  
			   $rate_upto_distance = $row['rate_upto_distance'];
			   $rate_upto_distanceArr = json_decode($rate_upto_distance);
			   
			   $package_info_distance = $row['package_info_distance'];
			   $package_info_distanceArr = json_decode($package_info_distance);
			   
			   $package_info_hourly = $row['package_info_hourly'];
			   $package_info_hourlyArr = json_decode($package_info_hourly);
			   
			   $package_info_distance_hourly = $row['package_info_distance_hourly'];
			   $package_info_distance_hourlyArr = json_decode($package_info_distance_hourly);
			   
			   $package_info_distance_waiting = $row['package_info_distance_waiting'];
			   $package_info_distance_waitingArr = json_decode($package_info_distance_waiting);
			   
			   $waitingfee_upto_minutes = $row['waitingfee_upto_minutes'];
			   $waitingfee_upto_minutesArr = json_decode($waitingfee_upto_minutes);
			   
			   $prewaitingfee_upto_minutes = $row['prewaitingfee_upto_minutes'];
			   $prewaitingfee_upto_minutesArr = json_decode($prewaitingfee_upto_minutes);
			   
			   $waitningfeeKey = array();
			   $waitingfeeValue = array();
			   
			   for($i=0;$i<count($waitingfee_upto_minutesArr);$i++){
				  
				  $wtngFee = explode("_",$waitingfee_upto_minutesArr[$i]);
				   array_push($waitningfeeKey,$wtngFee[0]);
				   array_push($waitingfeeValue,$wtngFee[1]);	
			  }
			  
			  $prewaitningfeeKey = array();
			  $prewaitingfeeValue = array();
			   
			   for($i=0;$i<count($prewaitingfee_upto_minutesArr);$i++){
				  
				  $prewtngFee = explode("_",$prewaitingfee_upto_minutesArr[$i]);
				   array_push($prewaitningfeeKey,$prewtngFee[0]);
				   array_push($prewaitingfeeValue,$prewtngFee[1]);	
			  }
			  
			   $postcode_to_postcode_fair = $row['postcode_to_postcode_fair'];
			   $postcode_to_postcode_fairArr = json_decode($postcode_to_postcode_fair);
			   
			   $postCodePickup = array();
			   $postCodeDrop = array();
			   $postCodeRs = array();
			   
			   for($i=0;$i<count($postcode_to_postcode_fairArr);$i++){
				  
				  $postCodearr = explode("_",$postcode_to_postcode_fairArr[$i]);
				   array_push($postCodePickup,$postCodearr[0]);
				   array_push($postCodeDrop,$postCodearr[1]);
				   array_push($postCodeRs,$postCodearr[2]);				   
			  }
			   
			   $row['postCodePickup'] = $postCodePickup;
			   $row['postCodeDrop'] = $postCodeDrop;
			   $row['postCodeRs'] = $postCodeRs;
			   
			   $tripCharge_per_minute = $row['tripCharge_per_minute'];
			   
			   $tripCharge_per_hour = $tripCharge_per_minute;
			   
			   $extras = $row['extras'];
			   //$extras=explode(",",$extras);
			   $extrasArr = json_decode($extras);
			   $extraType = array();
			   $extraRate = array();
			   $extraRateUnit = array();
			   
			   for($i=0;$i<count($extrasArr);$i++){
				   $extrakey = explode("_",$extrasArr[$i]);
				   array_push($extraType,$extrakey[0]);
				   array_push($extraRate,$extrakey[1]);
				   array_push($extraRateUnit,$extrakey[2]);	
			   }
			   $rateDistanceKey = array();
			   $rateDistanceValue = array();
			   
			   for($i=0;$i<count($rate_upto_distanceArr);$i++){
				  
				  $ratekey = explode("_",$rate_upto_distanceArr[$i]);
				   array_push($rateDistanceKey,$ratekey[0]);
				   array_push($rateDistanceValue,$ratekey[1]);	
			  }
			  
			  ///// Calculation for Distance wise package Starts Here ////
			  
			  $packageinfoDistancepackageName = array();
			  $packageinfoDistanceignorekm = array();
			  $packageinfoDistanceAmount = array();
			   
			   for($i=0;$i<count($package_info_distanceArr);$i++){
				  
				  $packageinfodistance_key = explode("_",$package_info_distanceArr[$i]);
				   array_push($packageinfoDistancepackageName,$packageinfodistance_key[0]);
				   array_push($packageinfoDistanceignorekm,$packageinfodistance_key[1]);
				   array_push($packageinfoDistanceAmount,$packageinfodistance_key[2]);
			  }
			  
			  ///// Calculation for Distance wise package Ends Here ////
			  
			  
			  ///// Calculation for Hourly wise package Starts Here ////
			  
			  $packageinfoHourlypackageName = array();
			  $packageinfoHourlyignorehr = array();
			  $packageinfoHourlyAmount = array();
			   
			   for($i=0;$i<count($package_info_hourlyArr);$i++){
				  
				  $packageinfohourly_key = explode("_",$package_info_hourlyArr[$i]);
				   array_push($packageinfoHourlypackageName,$packageinfohourly_key[0]);
				   array_push($packageinfoHourlyignorehr,$packageinfohourly_key[1]);
				   array_push($packageinfoHourlyAmount,$packageinfohourly_key[2]);
			  }
			  
			  ///// Calculation for Hourly wise package Ends Here ////
			  
			  
			  //// Calculation for Distance+Hourly wise Package Starts Here ////
			  
			  $packageinfoDistHourlypackageName = array();
			  $packageinfoDistHourlyignorehr = array();
			  $packageinfoDistHourlyignorekm = array();
			  $packageinfoDistHourlyAmount = array();
			   
			   for($i=0;$i<count($package_info_distance_hourlyArr);$i++){
				  
				  $packageinfodisthourly_key = explode("_",$package_info_distance_hourlyArr[$i]);
				   array_push($packageinfoDistHourlypackageName,$packageinfodisthourly_key[0]);
				   array_push($packageinfoDistHourlyignorehr,$packageinfodisthourly_key[1]);
				   array_push($packageinfoDistHourlyignorekm,$packageinfodisthourly_key[2]);
				   array_push($packageinfoDistHourlyAmount,$packageinfodisthourly_key[3]);
			  }
			  
			   //// Calculation for Distance+Hourly wise Package Ends Here ////
			   
			 //// Calculation for Distance+Waiting wise Package Starts Here ////
			  
			  $packageinfoDistWaitingpackageName = array();
			  $packageinfoDistWaitingignorekm = array();
			  $packageinfoDistWaitingAmount = array();
			   
			   for($i=0;$i<count($package_info_distance_waitingArr);$i++){
				  
				  $packageinfodistwait_key = explode("_",$package_info_distance_waitingArr[$i]);
				   array_push($packageinfoDistWaitingpackageName,$packageinfodistwait_key[0]);
				   array_push($packageinfoDistWaitingignorekm,$packageinfodistwait_key[1]);
				   array_push($packageinfoDistWaitingAmount,$packageinfodistwait_key[2]);
			  }
			 
			  //// Calculation for Distance+Waiting wise Package Ends Here ////
			  
			  
			  
    		   //$nightPremium = $row['NightCharges'];
			   //$premiums = $row['premiums'];
			   $cancellationFee = $row['cancellation_fees'];
			   $cancellation_feesArr = explode(" ",$cancellationFee);			   
			   
			   //$nightPremiumArr  =  explode(" ",$nightPremium);
			   //$premiumsArr  =  explode(" ",$premiums);
			   
			   $row['cancellation_fees'] = $cancellation_feesArr[0];
			   $row['cancellation_feesUnit'] = $cancellation_feesArr[1];
			   
			   //$row['premiums'] = $premiumsArr[0];
			   //$row['premiumsUnit'] = $premiumsArr[1];
			   
			   //$row['NightCharges'] = $nightPremiumArr[0];
			   //$row['NightChargesUnit'] = $nightPremiumArr[1];
			   
				$row['rateDistanceKm'] = $rateDistanceKey;
				$row['rateDistanceRate'] = $rateDistanceValue;

				$row['packageinfoDistancepackageName'] 		= $packageinfoDistancepackageName;
				$row['packageinfoDistanceignorekm'] 		= $packageinfoDistanceignorekm;
				$row['packageinfoDistanceAmount'] 			= $packageinfoDistanceAmount;

				$row['packageinfoHourlypackageName'] 		= $packageinfoHourlypackageName;
				$row['packageinfoHourlyignorehr'] 			= $packageinfoHourlyignorehr;
				$row['packageinfoHourlyAmount'] 			= $packageinfoHourlyAmount;
				
				$row['packageinfoDistHourlypackageName'] 	= $packageinfoDistHourlypackageName;
				$row['packageinfoDistHourlyignorehr'] 		= $packageinfoDistHourlyignorehr;
				$row['packageinfoDistHourlyignorekm'] 		= $packageinfoDistHourlyignorekm;
				$row['packageinfoDistHourlyAmount'] 		= $packageinfoDistHourlyAmount;
				
				$row['packageinfoDistWaitingpackageName'] 	= $packageinfoDistWaitingpackageName;
				$row['packageinfoDistWaitingignorekm'] 		= $packageinfoDistWaitingignorekm;
				$row['packageinfoDistWaitingAmount'] 		= $packageinfoDistWaitingAmount;

				$row['waitningfeeMinutes'] = $waitningfeeKey;
				$row['waitingfeeValue'] = $waitingfeeValue;

				$row['prewaitningfeeMinutes'] = $prewaitningfeeKey;
				$row['prewaitingfeeValue'] = $prewaitingfeeValue;
				
				$row['extraType']=$extraType;
				$row['extraRate'] = $extraRate;
				$row['extraRateUnit'] = $extraRateUnit;
				$row['tripCharge_per_hour'] = $tripCharge_per_hour;
			   
				$rate_per_km_dw = $row['rate_per_km_dw'];
				$minimum_distance_dw = $row['minimum_distance_dw'];
				$minimum_fare_dw = $row['minimum_fare_dw'];

				$waiting_fees = $row['waiting_fees'];
				/*$waiting_fee = explode(" ", $waiting_fees);
				$waiting_fees_upto_min = $waiting_fee[0];
				$waiting_upto_min_charge = $waiting_fee[1];
				$waiting_after_min_charge = $waiting_fee[2];
				*/
				$rowCab[]= $row;
           }
		
			////// Code to fetch PeakTime Charges Starts Here //////
			
			mysqli_free_result($dataCab);
			mysqli_next_result($this->con);
			$query = "SELECT * FROM `tblpeaktime`"; 
			$fetch = mysqli_query($this->con,$query) or die(mysqli_error($this->con));
			$peakTimeChargesArr=mysqli_num_rows($query);
			
			while($row1 = mysqli_fetch_array($fetch))
			{
				for($i = 0; $i <=count($peakTimeChargesArr); $i++ ){
					$timeFrom[]				=	$row1['timeFrom'];
					$timeTo[]				=	$row1['timeTo'];
					$peakCharges[]			=	$row1['peakCharges'];
					$peakTimeCharges_key 	=	$peakTimeChargesArr;
					array_push($timeFrom);
					array_push($timeTo);
					array_push($peakCharges);
				}
			}
			
			$rowCab['timeFrom']		=	$timeFrom;
			$rowCab['timeTo']		=	$timeTo;
			$rowCab['peakCharges']	=	$peakCharges;
			
			////// Code to fetch PeakTime Charges Ends Here //////
		
		return array("data"=>$rowCab);
	}
	
	
	
	
	function getAllState(){
		$countryName = $_REQUEST['country'];
		
		$dataState = mysqli_query($this->con,"CALL `wp_a_getAllState`('$countryName')") or die(mysqli_error($this->con));
       
        while($row = mysqli_fetch_assoc($dataState))
           {
               
			  $rowCab[]= $row;
               
           }
		
		return array("data"=>$rowCab);
	
	
	}
    
	function getAllCity()
	{
		$stateId = $_REQUEST['state'];
		
		$dataCity = mysqli_query($this->con,"CALL `wp_a_getAllCities`('$stateId')") or die(mysqli_error($this->con));
       
        while($row = mysqli_fetch_assoc($dataCity))
           {
               
			  $rowCab[]= $row;
               
           }
		
		return array("data"=>$rowCab);
	
	}
    
	function getAllCountry()
	{
		
		$dataCountry = mysqli_query($this->con,"CALL `wp_a_getAllCountry`()") or die(mysqli_error($this->con));
       
        while($row = mysqli_fetch_assoc($dataCountry))
           {
               
			  $rowCab[]= $row;
               
           }
		
		return array("data"=>$rowCab);
	
	}
	
	function saveFairManagement()
{	
	$taxText = $_REQUEST['cab_vehicle_taxText'];
	$rounding = $_REQUEST['rounding'];
	$level = $_REQUEST['level'];
	$Direction = $_REQUEST['Direction'];
	$rate_per_km = $_REQUEST['rate_per_km'];
	$first_km_rate = $_REQUEST['first_km_rate'];
	$vehicle_minmum_distance = $_REQUEST['vehicle_minmum_distance'];
	$vehicle_minmum_fare = $_REQUEST['vehicle_minmum_fare'];
	$CancelFee = $_REQUEST['CancelFee'];
		$kmUpTo = $_REQUEST['kmUpTo'];
		$ratePerKm = $_REQUEST['ratePerKm'];
	$nightChrge = $_REQUEST['nightChrge'];
	$nightChStrtTime = $_REQUEST['nightChStrtTime'];
	$nightChEndTime = $_REQUEST['nightChEndTime'];
		$extrasIn = $_REQUEST['extrasIn'];
		$extrasUnit = $_REQUEST['extrasUnit'];
	$vehicle_rate_hour = $_REQUEST['vehicle_rate_hour'];
		$minuteUpTo = $_REQUEST['minuteUpTo'];
		$fee_per_minutes = $_REQUEST['fee_per_minutes'];
	$premium = $_REQUEST['premium'];
	$vehicleId = $_REQUEST['vehicleId'];
	$frequent_location = $_REQUEST['frequent_location'];
		
		$pickupPostCodeArr = $_REQUEST['pickupPostCodeArr'];
		$dropPostCodeArr = $_REQUEST['dropPostCodeArr'];
		$rsPostCodeArr = $_REQUEST['rsPostCodeArr'];
		
		$pickupPostCodeArr1 = explode("_",$pickupPostCodeArr);
		$dropPostCodeArr1 = explode("_",$dropPostCodeArr);
		$rsPostCodeArr1 = explode("_",$rsPostCodeArr);
		
		
		$postCodeArr = array();
		for($n=0;$n < count($pickupPostCodeArr1); $n++){
			
				$postCodeArr[$n] = $pickupPostCodeArr1[$n]."_".$dropPostCodeArr1[$n]."_".$rsPostCodeArr1[$n];
		}
		
	$postCodeArr = json_encode($postCodeArr);
		
	
	//$vehicle_rate_hour = $vehicle_rate_hour/60;
		$rate_upto_distance = array();
		
		$kmUpToArr = explode("_",$kmUpTo);
		$ratePerKmArr = explode("_",$ratePerKm);
		
		for($i = 0; $i <count($kmUpToArr); $i++ ){
				$rate_upto_distance[$i] = $kmUpToArr[$i]."_".$ratePerKmArr[$i];
		}
	
	$rate_upto_distance = json_encode($rate_upto_distance);
	
		$extraArray = array();
		
		$extArr = explode("_",$extrasIn);
		$extUnitArr = explode("_",$extrasUnit);
		
		for($i = 0; $i <count($extArr); $i++ ){
				$extraArray[$i] = $extArr[$i]." ".$extUnitArr[$i];
		}
	
	$extraArray = json_encode($extraArray);
	
	
		$hourlyRate = array();
		
		$minutUptoArr = explode("_",$minuteUpTo);
		$feePerMinutArr = explode("_",$fee_per_minutes);
		
		for($i = 0; $i <count($minutUptoArr); $i++ ){
				$hourlyRate[$i] = $minutUptoArr[$i]."_".$feePerMinutArr[$i];
		}
	
	$hourlyRate = json_encode($hourlyRate);
	

	$dataFair = mysqli_query($this->con,"CALL `wp_a_saveFairManage`('$taxText','$rounding','$level','$Direction','$rate_per_km','$first_km_rate','$vehicle_minmum_distance','$vehicle_minmum_fare','$CancelFee','$nightChrge','$nightChStrtTime','$nightChEndTime','$vehicle_rate_hour','$rate_upto_distance','$extraArray','$hourlyRate','$vehicleId','$premium','$frequent_location','$postCodeArr')") or die(mysqli_error($this->con));
	
	if($dataFair == 1){
		return array("status"=>"true");
	}else{
		return array("status"=>"false");
	}

	
	
}

	function saveFairManageBasicVehicle(){
		$vehicleId = $_REQUEST['vehicleId'];
		$taxText = $_REQUEST['cab_vehicle_taxText'];
		$taxType = $_REQUEST['chkIdTax'];
		$rounding = $_REQUEST['rounding'];
		$leveli = $_REQUEST['leveli'];
		$Direction = $_REQUEST['Direction'];
		$dataFair = mysqli_query($this->con,"CALL `wp_a_fairManageForBasicVehicle`('$taxText','$taxType','$rounding','$leveli','$Direction','$vehicleId')") or die(mysqli_error($this->con));
		if($dataFair == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
	}

function saveFairManageDistanceVehicle(){

		$rate_per_km = $_REQUEST['rate_per_km'];
		$first_km_rate = $_REQUEST['first_km_rate'];
		$vehicle_minmum_distance = $_REQUEST['vehicle_minmum_distance'];
		$vehicle_minmum_fare = $_REQUEST['vehicle_minmum_fare'];
		$CancelFee = $_REQUEST['CancelFee'];
		$kmUpTo = $_REQUEST['kmUpTo'];
		$ratePerKm = $_REQUEST['ratePerKm'];
		$vehicleId = $_REQUEST['vehicleId'];
		$roundupkm_val = $_REQUEST['roundupkm_val'];
		$accum_instance_val = $_REQUEST['accum_instance_val'];
		$kmUpToArr = explode("_",$kmUpTo);
		$ratePerKmArr = explode("_",$ratePerKm);
		
		for($i = 0; $i <count($kmUpToArr); $i++ ){
				$rate_upto_distance[$i] = $kmUpToArr[$i]."_".$ratePerKmArr[$i];
		}
	
		$rate_upto_distance = json_encode($rate_upto_distance);
		
		$dataFair = mysqli_query($this->con,"CALL `wp_a_fairManageForDistanceVehicle`('$rate_per_km','$first_km_rate','$vehicle_minmum_distance','$vehicle_minmum_fare','$CancelFee','$rate_upto_distance','$vehicleId','$roundupkm_val','$accum_instance_val')") or die(mysqli_error($this->con));
	
		if($dataFair == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}


}

function saveFairManagePremiumVehicle(){

		$nightChrge = $_REQUEST['nightChrge'];
		$nightChStrtTime = $_REQUEST['nightChStrtTime'];
		$nightChEndTime = $_REQUEST['nightChEndTime'];
		$premium = $_REQUEST['premium'];
		$extrastype = $_REQUEST['extrasType'];
		$extrasIn = $_REQUEST['extrasIn'];
		$extrasUnit = $_REQUEST['extrasUnit'];
                $nightChrgeUnit = $_REQUEST['nightPremiumUnit'];
		
		$extraArray = array();
		
		$extraTypeArray = explode("_",$extrastype);
		$extArr = explode("_",$extrasIn);
		$extUnitArr = explode("_",$extrasUnit);
		
		for($i = 0; $i <count($extArr); $i++ ){
				$extraArray[$i] = $extraTypeArray[$i]."_".$extArr[$i]."_".$extUnitArr[$i];
		}
	
		$extraArray = json_encode($extraArray);
		$vehicleId = $_REQUEST['vehicleId'];
		
		$dataFair = mysqli_query($this->con,"CALL `wp_a_fairManageForPremiumVehicle`('$nightChrge','$nightChStrtTime','$nightChEndTime','$extraArray','$premium','$vehicleId','$nightChrgeUnit')") or die(mysqli_error($this->con));
	
		if($dataFair == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}


}

	function saveFairManageFixedRouteVehicle(){
		$frequent_location = $_REQUEST['frequent_location'];
		$pickupPostCodeArr = $_REQUEST['pickupPostCodeArr'];
		$dropPostCodeArr = $_REQUEST['dropPostCodeArr'];
		$rsPostCodeArr = $_REQUEST['rsPostCodeArr'];
		$pickupPostCodeArr1 = explode("_",$pickupPostCodeArr);
		$dropPostCodeArr1 = explode("_",$dropPostCodeArr);
		$rsPostCodeArr1 = explode("_",$rsPostCodeArr);
		$postCodeArr = array();
		for($n=0;$n < count($pickupPostCodeArr1); $n++){
			$postCodeArr[$n] = $pickupPostCodeArr1[$n]."_".$dropPostCodeArr1[$n]."_".$rsPostCodeArr1[$n];
		}
		$postCodeArr = json_encode($postCodeArr);
		$vehicleId = $_REQUEST['vehicleId'];
		$dataFair = mysqli_query($this->con,"CALL `wp_a_fairManageForFixedRouteVehicle`('$frequent_location','$postCodeArr','$vehicleId')") or die(mysqli_error($this->con));
		if($dataFair == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
	}


	function saveFairManagementOfCab(){
		$RecId = $_REQUEST['RecId'];
		$BookingTypeId = $_REQUEST['BookingTypeId'];
		$cabTypeId = $_REQUEST['CabTypeId'];
		$CityId = $_REQUEST['CityId'];
		$taxText = $_REQUEST['cab_vehicle_taxText'];
		$rounding = $_REQUEST['rounding'];
		$leveli = $_REQUEST['leveli'];
		$Direction = $_REQUEST['Direction'];
		$rate_per_km = $_REQUEST['rate_per_km'];
		$first_km_rate = $_REQUEST['first_km_rate'];
		$vehicle_minmum_distance = $_REQUEST['vehicle_minmum_distance'];
		$vehicle_minmum_fare = $_REQUEST['vehicle_minmum_fare'];
		$CancelFee = $_REQUEST['CancelFee'];
		$kmUpTo = $_REQUEST['kmUpTo'];
		$ratePerKm = $_REQUEST['ratePerKm'];
		
		$pkg_nameHourly = $_REQUEST['pkg_nameHourly'];
		$pkg_ignoreHrHourly = $_REQUEST['pkg_ignoreHrHourly'];
		$pkg_amountHourly = $_REQUEST['pkg_amountHourly'];
		
		
		$nightChrge = $_REQUEST['nightChrge'];
		$nightChStrtTime = $_REQUEST['nightChStrtTime'];
		$nightChEndTime = $_REQUEST['nightChEndTime'];
		$extrasIn = $_REQUEST['extrasIn'];
		$extrasUnit = $_REQUEST['extrasUnit'];
		$vehicle_rate_hour = $_REQUEST['vehicle_rate_hour'];
		$vehicle_ignore_hour = $_REQUEST['vehicle_ignore_hour'];
		$vehicle_hour_minimum_fare = $_REQUEST['vehicle_hour_minimum_fare'];
		$minuteUpTo = $_REQUEST['minuteUpTo'];
		$fee_per_minutes = $_REQUEST['fee_per_minutes'];
		$premium = $_REQUEST['premium'];
		$frequent_location = $_REQUEST['frequent_location'];
		$pickupPostCodeArr = $_REQUEST['pickupPostCodeArr'];
		$dropPostCodeArr = $_REQUEST['dropPostCodeArr'];
		$rsPostCodeArr = $_REQUEST['rsPostCodeArr'];
		$pickupPostCodeArr1 = explode("_",$pickupPostCodeArr);
		$dropPostCodeArr1 = explode("_",$dropPostCodeArr);
		$rsPostCodeArr1 = explode("_",$rsPostCodeArr);
		$postCodeArr = array();
		for($n=0;$n < count($pickupPostCodeArr1); $n++){
			
				$postCodeArr[$n] = $pickupPostCodeArr1[$n]."_".$dropPostCodeArr1[$n]."_".$rsPostCodeArr1[$n];
		}
		$postCodeArr = json_encode($postCodeArr);
		$rate_upto_distance = array();
		$kmUpToArr = explode("_",$kmUpTo);
		$ratePerKmArr = explode("_",$ratePerKm);
		
		$pkg_namehourlyArr		=	explode("_",$pkg_nameHourly);
		$pkg_ignoreHrHourlyArr	=	explode("_",$pkg_ignoreHrHourly);
		$pkg_amountHourlyArr		=	explode("_",$pkg_amountHourly);
		
		$Sub_Package_Id=2;
				
		for($i = 0; $i <count($kmUpToArr); $i++ ){
				$rate_upto_distance[$i] = $kmUpToArr[$i]."_".$ratePerKmArr[$i];
		}
		
		$queryDel ="DELETE from tbllocalpackage where masterPKG_ID='".$BookingTypeId."' AND Sub_Package_Id='".$Sub_Package_Id."' AND City_ID='".$CityId."' AND cabType='".$cabTypeId."'";
		mysqli_query($this->con, $queryDel);
		
		
		for($j = 0; $j <count($pkg_namehourlyArr); $j++ ){
			$package_info_hourly[$j] = $pkg_namehourlyArr[$j]."_".$pkg_ignoreHrHourlyArr[$j]."_".$pkg_amountHourlyArr[$j];
			if($pkg_namehourlyArr[$j]!="" && $pkg_ignoreHrHourlyArr[$j]!="" && $pkg_amountHourlyArr[$j]!=""){
			$query = "INSERT INTO tbllocalpackage (Package,masterPKG_ID,Sub_Package_Id,City_ID,cabType,Hrs,Price,Status) VALUES ('".$pkg_namehourlyArr[$j]."','".$BookingTypeId."','".$Sub_Package_Id."','".$CityId."','".$cabTypeId."','".$pkg_ignoreHrHourlyArr[$j]."','".$pkg_amountHourlyArr[$j]."','1')";
			$query1 = mysqli_query($this->con, $query);
			}
		}
		
		$rate_upto_distance				=	json_encode($rate_upto_distance);
		$package_info_hourly	=	json_encode($package_info_hourly);
		$extraArray = array();
		$extArr = explode("_",$extrasIn);
		$extUnitArr = explode("_",$extrasUnit);
		for($i = 0; $i <count($extArr); $i++ ){
				$extraArray[$i] = $extArr[$i]." ".$extUnitArr[$i];
		}
		$extraArray = json_encode($extraArray);
		$hourlyRate = array();
		$minutUptoArr = explode("_",$minuteUpTo);
		$feePerMinutArr = explode("_",$fee_per_minutes);
		for($i = 0; $i <count($minutUptoArr); $i++ ){
				$hourlyRate[$i] = $minutUptoArr[$i]."_".$feePerMinutArr[$i];
		}
		$hourlyRate = json_encode($hourlyRate);
		if($_REQUEST['UpdatedStatus'] == 1){
		$sql = "CALL `wp_a_fairManageForCabType`('$taxText','$rounding','$leveli','$Direction','$rate_per_km','$first_km_rate','$vehicle_minmum_distance','$vehicle_minmum_fare','$CancelFee','$nightChrge','$nightChStrtTime','$nightChEndTime','$vehicle_rate_hour','$rate_upto_distance','$extraArray','$hourlyRate','$RecId', '$BookingTypeId', '$cabTypeId','$premium','$frequent_location','$postCodeArr','$vehicle_ignore_hour','$vehicle_hour_minimum_fare','$package_info_hourly')";
		}else{
		$sql = "CALL `wp_a_fairManageForCabTypeStatus`('$taxText','$rounding','$leveli','$Direction','$rate_per_km','$first_km_rate','$vehicle_minmum_distance','$vehicle_minmum_fare','$CancelFee','$nightChrge','$nightChStrtTime','$nightChEndTime','$vehicle_rate_hour','$rate_upto_distance','$extraArray','$hourlyRate','$RecId', '$BookingTypeId', '$cabTypeId','$premium','$frequent_location','$postCodeArr','$vehicle_ignore_hour','$vehicle_hour_minimum_fare','$package_info_hourly')";

		}
		$dataFair = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
		
		
		mysqli_free_result($dataFair);   
        mysqli_next_result($this->con);
        /*$queryDel ="DELETE from tbllocalpackage where masterPKG_ID='".$BookingTypeId."' AND Sub_Package_Id='".$Sub_Package_Id."' AND City_ID='".$CityId."' AND cabType='".$cabTypeId."'";
		mysqli_query($this->con, $queryDel);	
		for($k = 0; $k < count($pkg_namehourlyArr); $k++ ){
			$query = "INSERT INTO tbllocalpackage (Package,masterPKG_ID,Sub_Package_Id,City_ID,cabType,Hrs,Price,Status) VALUES ('".$pkg_namehourlyArr[$k]."','".$BookingTypeId."','".$Sub_Package_Id."','".$CityId."','".$cabTypeId."','".$pkg_ignoreHrHourlyArr[$k]."','".$pkg_amountHourlyArr[$k]."','1')";
			$query1 = mysqli_query($this->con, $query);
		}*/

		
		if($dataFair == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
	}

	function saveFairManageBasic(){
		$BookingTypeId = $_REQUEST['BookingTypeId'];
		$cabTypeId = $_REQUEST['CabTypeId'];
		$RecId = $_REQUEST['RecId'];
		$taxText = $_REQUEST['cab_vehicle_taxText'];
		$taxType = $_REQUEST['chkIdTax'];
		$rounding = $_REQUEST['rounding'];
		$leveli = $_REQUEST['leveli'];
		$Direction = $_REQUEST['Direction'];
		$CancelFee = $_REQUEST['CancelFee'];
		$minuteUpTo = $_REQUEST['minuteUpTo'];
		$fee_per_minutes = $_REQUEST['fee_per_minutes'];
		
		$pre_waiting_minutes = $_REQUEST['pre_waiting_minutes'];
		$pre_waiting_fees = $_REQUEST['pre_waiting_fees'];
		
		$hourlyRate = array();
		$minutUptoArr = explode("_",$minuteUpTo);
		$feePerMinutArr = explode("_",$fee_per_minutes);
		for($i = 0; $i <count($minutUptoArr); $i++ ){
		$hourlyRate[$i] = $minutUptoArr[$i]."_".$feePerMinutArr[$i];
		}
		$hourlyRate = json_encode($hourlyRate);
		
		
		$prewaitingRate = array();
		$pre_waiting_minutesArr = explode("_",$pre_waiting_minutes);
		$pre_waiting_feesArr = explode("_",$pre_waiting_fees);
		for($i = 0; $i <count($pre_waiting_minutesArr); $i++ ){
		$prewaitingRate[$i] = $pre_waiting_minutesArr[$i]."_".$pre_waiting_feesArr[$i];
		}
		$prewaitingRate = json_encode($prewaitingRate);
		
		if($_REQUEST['UpdatedStatus'] == 1){
			$sql = "CALL `wp_a_fairManageForBasic`('$taxText','$taxType','$rounding','$leveli','$Direction','$cabTypeId','$BookingTypeId', '$RecId','$CancelFee','$hourlyRate','$prewaitingRate')";
		}else{
			$sql = "CALL `wp_a_fairManageForBasicStatus`('$taxText','$taxType','$rounding','$leveli','$Direction','$cabTypeId','$BookingTypeId', '$RecId','$CancelFee','$hourlyRate','$prewaitingRate')";
		}
		$dataFair = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
		if($dataFair == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
}

	function saveFairManageDistance(){
		$rate_per_km = $_REQUEST['rate_per_km'];
		$first_km_rate = $_REQUEST['first_km_rate'];
		$vehicle_minmum_distance = $_REQUEST['vehicle_minmum_distance'];
		$vehicle_minmum_fare = $_REQUEST['vehicle_minmum_fare'];
		$roundupkm = $_REQUEST['roundupkm_val'];
		$accum_instance = $_REQUEST['accum_instance_val'];
		
		$kmUpTo = $_REQUEST['kmUpTo'];
		$ratePerKm = $_REQUEST['ratePerKm'];
		
		$pkg_nameDistance = $_REQUEST['pkg_nameDistance'];
		$pkg_ignoreKmDistance = $_REQUEST['pkg_ignoreKmDistance'];
		$pkg_amountDistance = $_REQUEST['pkg_amountDistance'];
		
		$RecId = $_REQUEST['RecId'];
		$BookingTypeId = $_REQUEST['BookingTypeId'];
		$cabTypeId = $_REQUEST['CabTypeId'];
		$CityId = $_REQUEST['CityId'];
		$kmUpToArr = explode("_",$kmUpTo);
		$ratePerKmArr = explode("_",$ratePerKm);
		
		$pkg_nameDistanceArr		=	explode("_",$pkg_nameDistance);
		$pkg_ignoreKmDistanceArr	=	explode("_",$pkg_ignoreKmDistance);
		$pkg_amountDistanceArr		=	explode("_",$pkg_amountDistance);
		
		$Sub_Package_Id=1;
		
		for($i = 0; $i <count($kmUpToArr); $i++ ){
				$rate_upto_distance[$i] = $kmUpToArr[$i]."_".$ratePerKmArr[$i];
		}
		
		$queryDel ="DELETE from tbllocalpackage where masterPKG_ID='".$BookingTypeId."' AND Sub_Package_Id='".$Sub_Package_Id."' AND City_ID='".$CityId."' AND cabType='".$cabTypeId."'";
		mysqli_query($this->con, $queryDel);
		
		for($j = 0; $j <count($pkg_nameDistanceArr); $j++ ){
			$package_info_distance[$j] = $pkg_nameDistanceArr[$j]."_".$pkg_ignoreKmDistanceArr[$j]."_".$pkg_amountDistanceArr[$j];
			if($pkg_nameDistanceArr[$j]!="" && $pkg_ignoreKmDistanceArr[$j]!="" && $pkg_amountDistanceArr[$j]!=""){
			$query = "INSERT INTO tbllocalpackage (Package,masterPKG_ID,Sub_Package_Id,City_ID,cabType,Km,Price,Status) VALUES ('".$pkg_nameDistanceArr[$j]."','".$BookingTypeId."','".$Sub_Package_Id."','".$CityId."','".$cabTypeId."','".$pkg_ignoreKmDistanceArr[$j]."','".$pkg_amountDistanceArr[$j]."','1')";
			$query1 = mysqli_query($this->con, $query);
			}
			
			
		}
		
		$rate_upto_distance = json_encode($rate_upto_distance);
		$package_info_distance = json_encode($package_info_distance);
		//print_r($package_info_distance); die;
		
		if($_REQUEST['UpdatedStatus'] == 1){
			$sql = "CALL `wp_a_fairManageForDistance`('$rate_per_km','$first_km_rate','$vehicle_minmum_distance','$vehicle_minmum_fare','$CancelFee','$rate_upto_distance','$RecId', '$BookingTypeId', '$cabTypeId','$roundupkm','$accum_instance','$package_info_distance')";
		}else{
			$sql = "CALL `wp_a_fairManageForDistanceStatus`('$rate_per_km','$first_km_rate','$vehicle_minmum_distance','$vehicle_minmum_fare','$CancelFee','$rate_upto_distance','$RecId', '$BookingTypeId', '$cabTypeId','$roundupkm','$accum_instance','$package_info_distance')";
		}
		$dataFair = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
		mysqli_free_result($dataFair);   
        mysqli_next_result($this->con);
		
		
				
		/*$queryDel ="DELETE from tbllocalpackage where masterPKG_ID='".$BookingTypeId."' AND Sub_Package_Id='".$Sub_Package_Id."' AND City_ID='".$CityId."' AND cabType='".$cabTypeId."'";
		mysqli_query($this->con, $queryDel);
		
		for($k = 0; $k < count($pkg_nameDistanceArr); $k++ ){
			$query = "INSERT INTO tbllocalpackage (Package,masterPKG_ID,Sub_Package_Id,City_ID,cabType,Km,Price,Status) VALUES ('".$pkg_nameDistanceArr[$k]."','".$BookingTypeId."','".$Sub_Package_Id."','".$CityId."','".$cabTypeId."','".$pkg_ignoreKmDistanceArr[$k]."','".$pkg_amountDistanceArr[$k]."','1')";
			$query1 = mysqli_query($this->con, $query);
		}*/
		
		
		if($dataFair == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
	}


	function saveFairManagePremium(){
	
		$nightChrge = $_REQUEST['nightChrge'];
		$nightChStrtTime = $_REQUEST['nightChStrtTime'];
		$nightChEndTime = $_REQUEST['nightChEndTime'];
		//$premium = $_REQUEST['premium'];
		$premiumofVehicle = $_REQUEST['premiumofVehicle'];
		$premiumofVehicleUnit = $_REQUEST['premiumofVehicleUnit'];
		$extrastype = $_REQUEST['extrasType'];
		$extrasIn = $_REQUEST['extrasIn'];
		$extrasUnit = $_REQUEST['extrasUnit'];
		$nightChrgeUnit = $_REQUEST['nightPremiumUnit'];
		
		$peakTimeChargesFrom 	= $_REQUEST['peakTimeChargesFrom'];
		$peakTimeChargesTo 		= $_REQUEST['peakTimeChargesTo'];
		$peakTimeChargesPercent = $_REQUEST['peakTimeChargesPercent'];
		
		$peakTimeChargesFromArr		=	explode("_",$peakTimeChargesFrom);
		$peakTimeChargesToArr		=	explode("_",$peakTimeChargesTo);
		$peakTimeChargesPercentArr	=	explode("_",$peakTimeChargesPercent);
		
		$extra=$extratype;
		$extraArray = array();
		$extUnitArray = explode("_",$extrastype);
		$extArr = explode("_",$extrasIn);
		$extUnitArr = explode("_",$extrasUnit);
		
		for($i = 0; $i <count($extArr); $i++ ){
				$extraArray[$i] = $extUnitArray[$i]."_".$extArr[$i]."_".$extUnitArr[$i];
		}
	
		$extraArray = json_encode($extraArray);
		
		$RecId = $_REQUEST['RecId'];
		$BookingTypeId = $_REQUEST['BookingTypeId'];
		$cabTypeId = $_REQUEST['CabTypeId'];
		
		if($_REQUEST['UpdatedStatus'] == 1){
			$sql = "CALL `wp_a_fairManageForPremium`('$nightChrge','$nightChStrtTime','$nightChEndTime','$extraArray','$premiumofVehicle','$RecId', '$BookingTypeId', '$cabTypeId','$nightChrgeUnit','$premiumofVehicleUnit')";
		}else{
			$sql = "CALL `wp_a_fairManageForPremiumStatus`('$nightChrge','$nightChStrtTime','$nightChEndTime','$extraArray','$premiumofVehicle','$RecId', '$BookingTypeId', '$cabTypeId','$nightChrgeUnit','$premiumofVehicleUnit')";
		}
		$dataFair = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
		
		
		mysqli_free_result($dataFair);   
        mysqli_next_result($this->con);
		
		$queryDel ="DELETE from tblpeaktime";
		mysqli_query($this->con, $queryDel);
		
		for($k = 0; $k < count($peakTimeChargesFromArr); $k++ ){
			$query = "INSERT INTO tblpeaktime(timeFrom,timeTo,peakCharges) VALUES ('".$peakTimeChargesFromArr[$k]."','".$peakTimeChargesToArr[$k]."','".$peakTimeChargesPercentArr[$k]."')";
			$query1 = mysqli_query($this->con, $query);
		}
		
		if($dataFair == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
	
	
	}

	function saveFairManageFixedRoute(){
		$frequent_location = $_REQUEST['frequent_location'];
		$pickupPostCodeArr = $_REQUEST['pickupPostCodeArr'];
		$dropPostCodeArr = $_REQUEST['dropPostCodeArr'];
		$rsPostCodeArr = $_REQUEST['rsPostCodeArr'];
		$pickupPostCodeArr1 = explode("_",$pickupPostCodeArr);
		$dropPostCodeArr1 = explode("_",$dropPostCodeArr);
		$rsPostCodeArr1 = explode("_",$rsPostCodeArr);
		$postCodeArr = array();
		for($n=0;$n < count($pickupPostCodeArr1); $n++){
			$postCodeArr[$n] = $pickupPostCodeArr1[$n]."_".$dropPostCodeArr1[$n]."_".$rsPostCodeArr1[$n];
		}
		$postCodeArr = json_encode($postCodeArr);
		$RecId = $_REQUEST['RecId'];
		$BookingTypeId = $_REQUEST['BookingTypeId'];
		$cabTypeId = $_REQUEST['CabTypeId'];
		if($_REQUEST['UpdatedStatus'] == 1){
		$sql = "CALL `wp_a_fairManageForFixedRoute`('$frequent_location','$postCodeArr','$RecId', '$BookingTypeId', '$cabTypeId')";
		}else{
			$sql = "CALL `wp_a_fairManageForFixedRouteStatus`('$frequent_location','$postCodeArr','$RecId', '$BookingTypeId', '$cabTypeId')";
		}
		$dataFair = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
		if($dataFair == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
	}

	function saveDistanceWaiting(){
		$dw_rate_per_km = $_REQUEST['rate_per_km_dw'];
		$dw_minmum_distance = $_REQUEST['minmum_distance_dw'];
		$dw_minmum_fare = $_REQUEST['minmum_fare_dw'];
		$fair_waiting_minutes11 = $_REQUEST['fair_waiting_minutes11'];
		$fair_waiting_fees11 = $_REQUEST['fair_waiting_fees11'];
		$fair_waiting_fees22 = $_REQUEST['fair_waiting_fees22'];
		$waiting_fees = $fair_waiting_minutes11."_".$fair_waiting_fees11."_".$fair_waiting_fees22;
		$BookingTypeId = $_REQUEST['BookingTypeId'];
		$RecId = $_REQUEST['RecId'];
		$cabTypeId = $_REQUEST['CabTypeId'];		
		$CityId = $_REQUEST['CityId'];
		
		$pkg_nameDistanceWaiting		=	$_REQUEST['pkg_nameDistanceWaiting'];
		$pkg_ignoreKmDistanceWaiting	=	$_REQUEST['pkg_ignoreKmDistanceWaiting'];
		$pkg_amountDistanceWaiting		=	$_REQUEST['pkg_amountDistanceWaiting'];
		
		
		$pkg_nameDistanceWaitingArr			=	explode("_",$pkg_nameDistanceWaiting);
		$pkg_ignoreKmDistanceWaitingArr		=	explode("_",$pkg_ignoreKmDistanceWaiting);
		$pkg_amountDistanceWaitingArr		=	explode("_",$pkg_amountDistanceWaiting);
		
		$Sub_Package_Id=4;
		
		$queryDel ="DELETE from tbllocalpackage where masterPKG_ID='".$BookingTypeId."' AND Sub_Package_Id='".$Sub_Package_Id."' AND City_ID='".$CityId."' AND cabType='".$cabTypeId."'";
		mysqli_query($this->con, $queryDel);

		for($j = 0; $j <count($pkg_nameDistanceWaitingArr); $j++ ){
			$package_info_distance_waiting[$j] = $pkg_nameDistanceWaitingArr[$j]."_".$pkg_ignoreKmDistanceWaitingArr[$j]."_".$pkg_amountDistanceWaitingArr[$j];
			if($pkg_nameDistanceWaitingArr[$j]!="" && $pkg_ignoreKmDistanceWaitingArr[$j]!="" && $pkg_amountDistanceWaitingArr[$j]!=""){
			$query = "INSERT INTO tbllocalpackage (Package,masterPKG_ID,Sub_Package_Id,City_ID,cabType,Km,Price,Status) VALUES ('".$pkg_nameDistanceWaitingArr[$j]."','".$BookingTypeId."','".$Sub_Package_Id."','".$CityId."','".$cabTypeId."','".$pkg_ignoreKmDistanceWaitingArr[$j]."','".$pkg_amountDistanceWaitingArr[$j]."','1')";
			$query1 = mysqli_query($this->con, $query);
			}
		}
		
		$package_info_distance_waiting	=	json_encode($package_info_distance_waiting);		
		
		if($_REQUEST['UpdatedStatus'] == 1){
			$sql = "CALL `wp_a_saveDistanceWaiting`('$dw_rate_per_km','$dw_minmum_distance','$dw_minmum_fare','$waiting_fees','$RecId', '$BookingTypeId', '$cabTypeId', '$package_info_distance_waiting')";
		}else{
			$sql = "CALL `wp_a_saveDistanceWaitingStatus`('$dw_rate_per_km','$dw_minmum_distance','$dw_minmum_fare','$waiting_fees','$RecId', '$BookingTypeId', '$cabTypeId', '$package_info_distance_waiting')";
		}
		$dataFair = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
		
		mysqli_free_result($dataFair);   
        mysqli_next_result($this->con);
		
		/*$queryDel ="DELETE from tbllocalpackage where masterPKG_ID='".$BookingTypeId."' AND Sub_Package_Id='".$Sub_Package_Id."' AND City_ID='".$CityId."' AND cabType='".$cabTypeId."'";
		mysqli_query($this->con, $queryDel);
		
		for($k = 0; $k < count($pkg_nameDistanceWaitingArr); $k++ ){			
			$query = "INSERT INTO tbllocalpackage (Package,masterPKG_ID,Sub_Package_Id,City_ID,cabType,Km,Price,Status) VALUES ('".$pkg_nameDistanceWaitingArr[$k]."','".$BookingTypeId."','".$Sub_Package_Id."','".$CityId."','".$cabTypeId."','".$pkg_ignoreKmDistanceWaitingArr[$k]."','".$pkg_amountDistanceWaitingArr[$k]."','1')";
			$query1 = mysqli_query($this->con, $query);
		}*/

		if($dataFair == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
	}

function saveDistanceWaitingVehicle(){

		$dw_rate_per_km = $_REQUEST['rate_per_km_dw'];
		$dw_minmum_distance = $_REQUEST['minmum_distance_dw'];
		$dw_minmum_fare = $_REQUEST['minmum_fare_dw'];
		
		$fair_waiting_minutes11 = $_REQUEST['fair_waiting_minutes11'];
		$fair_waiting_fees11 = $_REQUEST['fair_waiting_fees11'];
		$fair_waiting_fees22 = $_REQUEST['fair_waiting_fees22'];
		
		$waiting_fees = $fair_waiting_minutes11."_".$fair_waiting_fees11."_".$fair_waiting_fees22;
		
		$vehicleId = $_REQUEST['vehicleId'];
		
		
		$dataFair = mysqli_query($this->con,"CALL `wp_a_saveDistanceWaitingVehicle`('$dw_rate_per_km','$dw_minmum_distance','$dw_minmum_fare','$waiting_fees','$vehicleId')") or die(mysqli_error($this->con));
	
		if($dataFair == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
}

	function saveDistanceHour(){
		$dh_rate_per_km = $_REQUEST['rate_per_km_dh'];
		$dh_minmum_distance = $_REQUEST['minmum_distance_dh'];
		$dh_minmum_fare = $_REQUEST['minmum_fare_dh'];
		$dh_rate_per_hour = $_REQUEST['rate_per_hour_dh'];
		$dh_ignore_first_hrs = $_REQUEST['ignore_first_hrs_dh'];
		$cabTypeId = $_REQUEST['CabTypeId'];
		$BookingTypeId = $_REQUEST['BookingTypeId'];
		$RecId = $_REQUEST['RecId'];
		$CityId = $_REQUEST['CityId'];
		
		$pkg_nameDist_Hourly		=	$_REQUEST['pkg_nameDist_Hourly'];
		$pkg_ignorHR_DistHourly		=	$_REQUEST['pkg_ignorHR_DistHourly'];
		$pkg_ignoreKM_DistHourly	=	$_REQUEST['pkg_ignoreKM_DistHourly'];		
		$pkg_amount_Dist_Hourly		=	$_REQUEST['pkg_amount_Dist_Hourly'];
		
		
		$pkg_nameDist_HourlyArr		=	explode("_",$pkg_nameDist_Hourly);
		$pkg_ignorHR_DistHourlyArr		=	explode("_",$pkg_ignorHR_DistHourly);
		$pkg_ignoreKM_DistHourlyArr	=	explode("_",$pkg_ignoreKM_DistHourly);
		$pkg_amount_Dist_HourlyArr		=	explode("_",$pkg_amount_Dist_Hourly);
		
		$Sub_Package_Id=3;
		
		$queryDel ="DELETE from tbllocalpackage where masterPKG_ID='".$BookingTypeId."' AND Sub_Package_Id='".$Sub_Package_Id."' AND City_ID='".$CityId."' AND cabType='".$cabTypeId."'";
		mysqli_query($this->con, $queryDel);

		for($j = 0; $j <count($pkg_nameDist_HourlyArr); $j++ ){
			$package_info_distance_hourly[$j] = $pkg_nameDist_HourlyArr[$j]."_".$pkg_ignorHR_DistHourlyArr[$j]."_".$pkg_ignoreKM_DistHourlyArr[$j]."_".$pkg_amount_Dist_HourlyArr[$j];
			if($pkg_nameDist_HourlyArr[$j]!="" && $pkg_ignorHR_DistHourlyArr[$j]!="" && $pkg_ignoreKM_DistHourlyArr[$j]!="" && $pkg_amount_Dist_HourlyArr[$j]!=""){
			$query = "INSERT INTO tbllocalpackage (Package,masterPKG_ID,Sub_Package_Id,City_ID,cabType,Hrs,Km,Price,Status) VALUES ('".$pkg_nameDist_HourlyArr[$j]."','".$BookingTypeId."','".$Sub_Package_Id."','".$CityId."','".$cabTypeId."','".$pkg_ignorHR_DistHourlyArr[$j]."','".$pkg_ignoreKM_DistHourlyArr[$j]."','".$pkg_amount_Dist_HourlyArr[$j]."','1')";
			$query1 = mysqli_query($this->con, $query);
			}
		}
		
		$package_info_distance_hourly	=	json_encode($package_info_distance_hourly);
		
		if($_REQUEST['UpdatedStatus'] == 1){
			$sql = "CALL `wp_a_saveDistanceHour`('$dh_rate_per_km','$dh_minmum_distance','$dh_minmum_fare','$dh_rate_per_hour','$RecId', '$BookingTypeId','$cabTypeId','$dh_ignore_first_hrs','$package_info_distance_hourly')";
		}else{
			$sql = "CALL `wp_a_saveDistanceHourStatus`('$dh_rate_per_km','$dh_minmum_distance','$dh_minmum_fare','$dh_rate_per_hour','$RecId', '$BookingTypeId','$cabTypeId','$dh_ignore_first_hrs','$package_info_distance_hourly')";
		}
		$dataFair = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
		
		
		mysqli_free_result($dataFair);   
        mysqli_next_result($this->con);
		
		/*$queryDel ="DELETE from tbllocalpackage where masterPKG_ID='".$BookingTypeId."' AND Sub_Package_Id='".$Sub_Package_Id."' AND City_ID='".$CityId."' AND cabType='".$cabTypeId."'";
		mysqli_query($this->con, $queryDel);
			
		for($k = 0; $k < count($pkg_nameDist_HourlyArr); $k++ ){
			$query = "INSERT INTO tbllocalpackage (Package,masterPKG_ID,Sub_Package_Id,City_ID,cabType,Hrs,Km,Price,Status) VALUES ('".$pkg_nameDist_HourlyArr[$k]."','".$BookingTypeId."','".$Sub_Package_Id."','".$CityId."','".$cabTypeId."','".$pkg_ignorHR_DistHourlyArr[$k]."','".$pkg_ignoreKM_DistHourlyArr[$k]."','".$pkg_amount_Dist_HourlyArr[$k]."','1')";
			$query1 = mysqli_query($this->con, $query);
		}*/
		
		if($dataFair == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
	}

function shareCalculation(){

		$company_share = $_REQUEST['companyShare'];
		$partner_share = $_REQUEST['partnerShare'];
		$driver_share = $_REQUEST['driverShare'];
		
		
		$dataShare = mysqli_query($this->con,"CALL `wp_a_shareCalculation`('$company_share','$partner_share','$driver_share')") or die(mysqli_error($this->con));
	
		if($dataShare == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
}

function saveDistanceHourVehicle(){

		$dh_rate_per_km = $_REQUEST['rate_per_km_dh'];
		$dh_minmum_distance = $_REQUEST['minmum_distance_dh'];
		$dh_minmum_fare = $_REQUEST['minmum_fare_dh'];
		$dh_rate_per_hour = $_REQUEST['rate_per_hour_dh'];
		
		$vehicleId = $_REQUEST['vehicleId'];
		
		
		$dataFair = mysqli_query($this->con,"CALL `wp_a_saveDistanceHourVehicle`('$dh_rate_per_km','$dh_minmum_distance','$dh_minmum_fare','$dh_rate_per_hour','$vehicleId')") or die(mysqli_error($this->con));
	
		if($dataFair == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
}


function fetchCabPackage(){
		
		$dataFair = mysqli_query($this->con,"CALL `wp_a_fetchCabPackage`()") or die(mysqli_error($this->con));
	
		$data = mysqli_fetch_assoc($dataFair);
		
		$deviceData = array("localPackage" =>$data['localPackage'],"Point_To_Point_Package" =>$data['pointToPointPackage'],"Airport_Package" =>$data['airportPackage'],"Outstation_Package" =>$data['outstationPackage'],"company_Share" =>$data['companyShare'],"partner_Share" =>$data['partnerShare'],"driver_Share" =>$data['driverShare']);
           
        return array("data"=>$deviceData);
		
		if($dataFair == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
}

	function cabPackageCalculation(){
		$local_package = $_REQUEST['local'];
		$point_to_point_package = $_REQUEST['point_to_point'];
		$airport_package = $_REQUEST['airport'];
		$outstation_package = $_REQUEST['outstation'];
		$SQL = "CALL `wp_a_cabPackageCalculation`('$local_package','$point_to_point_package','$airport_package','$outstation_package')";
		$dataFair = mysqli_query($this->con,$SQL) or die(mysqli_error($this->con));
		if($dataFair == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
	}
	
public function getAllBookingComplaintList(){
            
        $data=mysqli_query($this->con,"CALL `wp_a_booking_complaint_list`()") or die(mysqli_error($this->con));
        $complaintbookings=array();
		while($row=mysqli_fetch_array($data))
		{
			//print_r($row);
			if($row['totalBill']!="")
			{
				$row['totalBill']="INR ".$row['totalBill'];
				
			}
			if($row['reg']!=0)
			{
				$row['reg']="(".$row['reg'].")";
				
			}
			$complaint="";
			$comp_col="";
			if($row['com_id']!="")
			{
			 $complaint="<br><br><small>Complaint: ".$row['com_status']."</small>";
			 $comp_col="<div style='width:10px;height:10px;border-radius:100%;background:red'></div>";

			}		
				
			$complaintbookings[]=array('<label data="'.$row['id'].'" class="clickme" style="padding: 0px 10px 0px 0;color:#08c; cursor:pointer;">'.$row['ref'].'</label>',date("M-j-y H:i",strtotime($row['ordertime']))."<br><small style='color:gray'>".'</small>',"<div data='".$row['id']."'  style='cursor:pointer;' class='driver_click'>".$row['vehicle'].'<br/><small style="color:gray;text-transform:capitalize"  class="driver_click">'.$row['driver_name'].'</small><br><small>'.$row['reg'].'</small>'.$comp_col."</div>",$row['booking_type'],$row['partner'].'<br>'.ucfirst($row['emp']),'<label class="client" data="'.$row['client_id'].'">'.$row['clientname'].'</label><br/><small style="color:gray">'.$row['mob_no'].'</small>',ucfirst($row['departure']),ucfirst($row['drop_area']),$row['status'].'<br/><small style="color:gray;text-transform:capitalize">'.$row['totalBill'].'</small>'.$complaint,"<label style='padding: 0px 10px 0px 0;color:#08c; cursor:pointer;' class='action' data='".$row['id']."'>Action</label>");
		}
		
		return array("data"=>$complaintbookings);
		
		if($data == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
}

function getAllComplaint(){

		$dataComplaint = mysqli_query($this->con,"CALL `wp_a_getAllComplaint`()") or die(mysqli_error($this->con));
		while($row = mysqli_fetch_assoc($dataComplaint))
		{
	   
			$rowComplaint[]=array($row['id'],$row['enquiry_type'],$row['sub_complaint'],$row['created_by'],$row['date'],$row['updated_by'],$row['updated_on'],'<button class="btn btn-primary manage_ComplaintType" id="ComplaintType_'.$row['id'].'">Manage Complaint</button>');
		   
		}
		
		return array("data"=>$rowComplaint);
		
		if($dataComplaint == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
}

function SubComplaintType(){

		$complaintType = $_REQUEST['complaint_type'];
		$subComplaint = $_REQUEST['sub_complaint'];
		$created_by = 'Rajeev';
		
		$data = mysqli_query($this->con,"CALL `wp_a_SubComplaintType`('$complaintType','$subComplaint','$created_by')") or die(mysqli_error($this->con));
		$row=mysqli_fetch_array($data);
		if($row[0]==TRUE){
		  $msg="Sub Complaint already exists";
			return array("Message"=>$msg,"status"=>"false");
			}
			else{
			 $msg="Sub Complaint inserted";
			 return array("Message"=>$msg,"status"=>"true");
			}
		
}

function getAllStatus(){

		$dataEnquiry = mysqli_query($this->con,"CALL `wp_a_getStatus`()") or die(mysqli_error($this->con));
		$rowEnquiry=array();
		while($row = mysqli_fetch_assoc($dataEnquiry))
           {
		  
			  //$rowEnquiry[]=array('<a data = "'.$row['ID'].'" class="clickComplaintType" href="javascript:void(0)" color:#08c; crsor:pointer;">'.$row['ID'].'</a>',$row['CallerName'],$row['ComplaintDescription'],$row['ComplaintType'],$row['DateT'],$row['StatusID']);
			  $rowEnquiry[] = $row;
               
           }
		
		return array("data"=>$rowEnquiry);
		
		if($dataEnquiry == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
}

function getAllEnquiry(){

		$dataEnquiry = mysqli_query($this->con,"CALL `wp_a_getAllEnquiry`()") or die(mysqli_error($this->con));
		$rowEnquiry=array();
		while($row = mysqli_fetch_assoc($dataEnquiry))
           {
		  
			  $rowEnquiry[]=array('<a data = "'.$row['Id'].'" class="clickComplaintType" href="javascript:void(0)" color:#08c; crsor:pointer;">'.$row['Id'].'</a>',$row['CallerName'],$row['EmailId'],$row['ContactNo'],$row['CSRID'],$row['Message'],$row['EnqType'],$row['EnquiryDate'],$row['Status'],$row['updated_by'],$row['ClosedDate']);
               
           }
		
		return array("data"=>$rowEnquiry);
		
		if($dataEnquiry == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
}

function getAllEnquiryById(){
		
		$complaint_id = $_REQUEST['complaint_id'];
		
		$dataComplaint = mysqli_query($this->con,"CALL `Sp_getAllEnquiryById`('$complaint_id')") or die(mysqli_error($this->con));
		
		while($row = mysqli_fetch_assoc($dataComplaint))
           {
		   
			  $rowComplaint[]= $row;
               
           }
		
		return array("data"=>$rowComplaint);
		
		if($dataComplaint == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
}

function getAllComplaintById(){
		
		$complaint_id = $_REQUEST['complaint_id'];
		
		$dataComplaint = mysqli_query($this->con,"CALL `Sp_getAllComplaintById`('$complaint_id')") or die(mysqli_error($this->con));
		
		while($row = mysqli_fetch_assoc($dataComplaint))
           {
		   
			  $rowComplaint[]= $row;
               
           }
		
		return array("data"=>$rowComplaint);
		
		if($dataComplaint == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
}

function updateEnquiryStatus(){

		$enquiry_Text = $_REQUEST['enquiryText'];
		$EnquiryId = $_REQUEST['Enquiry_Id'];
		$updated_by = 'Rajeev';
		
		$data = mysqli_query($this->con,"CALL `wp_a_updateEnquiryStatus`('$enquiry_Text','$EnquiryId','$updated_by')") or die(mysqli_error($this->con));
		
		if($data == 1){
			$msg="Status updated";
			return array("Message"=>$msg,"status"=>"true");
		}
}

function updateComplaintStatus(){

		$complaint_Text = $_REQUEST['complaintText'];
		$ComplaintId = $_REQUEST['Complaint_Id'];
		$updated_by = 'Rajeev';
		
		$data = mysqli_query($this->con,"CALL `wp_a_updateComplaintStatus`('$complaint_Text','$ComplaintId','$updated_by')") or die(mysqli_error($this->con));
		
		if($data == 1){
			$msg="Status updated";
			return array("Message"=>$msg,"status"=>"true");
		}
}

function getAllEnquiryType(){

		$dataEnquiry = mysqli_query($this->con,"CALL `wp_a_getAllEnquiryType`()") or die(mysqli_error($this->con));
		$rowEnquiry=array();
		while($row = mysqli_fetch_assoc($dataEnquiry))
           {
		  
			  $rowEnquiry[]=array($row['id'],$row['enquiry_type'],$row['created_by'],$row['created_on'],$row['updated_by'],$row['updated_on'],$row['status'],'<button class="btn btn-primary manageEnquiryType" id="enquiryType_'.$row['id'].'">Manage Enquiry</button>');
               
           }
		
		return array("data"=>$rowEnquiry);
		
		if($dataEnquiry == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
}

function getAllActionType(){

		$dataEnquiry = mysqli_query($this->con,"CALL `wp_a_getAllActionType`()") or die(mysqli_error($this->con));
		$rowEnquiry=array();
		while($row = mysqli_fetch_assoc($dataEnquiry))
           {
		  
			  $rowEnquiry[]=array($row['id'],$row['action'],$row['created_by'],$row['created_on'],$row['updated_by'],$row['updated_on'],$row['status'],'<button class="btn btn-primary manageActionType" id="actionType_'.$row['id'].'">Manage Action Type</button>');
               
           }
		
		return array("data"=>$rowEnquiry);
		
		if($dataEnquiry == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
}

function getAllComplaintType(){

		$dataEnquiry = mysqli_query($this->con,"CALL `wp_a_getAllComplaintType`()") or die(mysqli_error($this->con));
		$rowEnquiry=array();
		while($row = mysqli_fetch_assoc($dataEnquiry))
           {
		  
			  $rowEnquiry[]=array($row['id'],$row['enquiry_type'],$row['created_by'],$row['created_on'],$row['status']);
               
           }
		
		return array("data"=>$rowEnquiry);
		
		if($dataEnquiry == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
}

function insertEnquiryType(){

		$enquiry_type = $_REQUEST['enquiry_type'];
		$enquiry_type_status = $_REQUEST['enquiry_type_status'];
		$enquiry_type_created_by = 'Rajeev';
		
		$data = mysqli_query($this->con,"CALL `wp_a_insertEnquiryType`('$enquiry_type','$enquiry_type_status','$enquiry_type_created_by')") or die(mysqli_error($this->con));
		$row=mysqli_fetch_array($data);
		if($row[0]==TRUE){
		  $msg="Enquiry Type already exists";
			return array("Message"=>$msg,"status"=>"false");
			}
			else{
			 $msg="Enquiry Type inserted";
			 return array("Message"=>$msg,"status"=>"true");
			}
}

function insertActionType(){

		$action_type = $_REQUEST['action_type'];
		$action_type_status = $_REQUEST['action_type_status'];
		$action_type_created_by = 'Rajeev';
		
		$data = mysqli_query($this->con,"CALL `wp_a_insertActionType`('$action_type','$action_type_status','$action_type_created_by')") or die(mysqli_error($this->con));
		$row=mysqli_fetch_array($data);
		if($row[0]==TRUE){
		  $msg="Action Type already exists";
			return array("Message"=>$msg,"status"=>"false");
			}
			else{
			 $msg="Action Type inserted";
			 return array("Message"=>$msg,"status"=>"true");
			}
}

function enquiryTypeInfoById(){
		
		$enquiry_id = $_REQUEST['enquiryId'];
		
		$data = mysqli_query($this->con,"CALL `wp_a_enquiryTypeInfoById`('$enquiry_id')") or die(mysqli_error($this->con));
		
		while($row = mysqli_fetch_assoc($data))
		{

			$rowEnquiry[]= $row;
		   
		}
		
		return array("data"=>$rowEnquiry);
		
		if($data == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
}

function actionTypeInfoById(){
		
		$action_Id = $_REQUEST['actionId'];
		$data = mysqli_query($this->con,"CALL `wp_a_actionTypeInfoById`('$action_Id')") or die(mysqli_error($this->con));
		while($row = mysqli_fetch_assoc($data))
		{
			$rowAction[]= $row;  
		}
		return array("data"=>$rowAction);
		if($data == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
}

function complaint_TypeInfoById(){
		
		$complaint_id = $_REQUEST['complaintId'];
		
		$data = mysqli_query($this->con,"CALL `wp_a_complaint_TypeInfoById`('$complaint_id')") or die(mysqli_error($this->con));
		
		while($row = mysqli_fetch_assoc($data))
		{

			$rowEnquiry[]= $row;
		   
		}
		
		return array("data"=>$rowEnquiry);
		
		if($data == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
}


function updateEnquiryType(){
			
		$enquiry_id=$_REQUEST['enquiryId'];
		$manage_enquery=mysqli_real_escape_string($this->con,$_REQUEST['manageEnquery']);
		$enquiryType_UpdatedBy = 'Rajeev';
		$Enquiry_Status=$_REQUEST['EnquiryStatus'];

		$result=mysqli_query($this->con,"CALL `wp_a_updateEnquiryType`('$enquiry_id','$manage_enquery','$enquiryType_UpdatedBy','$Enquiry_Status')") or die(mysqli_error($this->con));
		$row=mysqli_fetch_array($result);
		if($row[0]==TRUE){
			$msg="Enquiry Type already exists";
			return array("Message"=>$msg,"status"=>"false");
			}
		else{
			 $msg="Enquiry Type updated";
			 return array("Message"=>$msg,"status"=>"true");
		}   
}

function updateActionType(){
			
		$action_id=$_REQUEST['ActionId'];
		$manage_action=mysqli_real_escape_string($this->con,$_REQUEST['manageAction']);
		$actionType_UpdatedBy = 'Rajeev';
		$action_Status=$_REQUEST['ActionStatus'];

		$result=mysqli_query($this->con,"CALL `wp_a_updateActionType`('$action_id','$manage_action','$actionType_UpdatedBy','$action_Status')") or die(mysqli_error($this->con));
		$row=mysqli_fetch_array($result);
		if($row[0]==TRUE){
			$msg="Action Type already exists";
			return array("Message"=>$msg,"status"=>"false");
			}
		else{
			 $msg="Action Type updated";
			 return array("Message"=>$msg,"status"=>"true");
		}   
}

function updateComplaintType(){
			
		$complaint_id=$_REQUEST['complaintId'];
		$complaintType_UpdatedBy = 'Rajeev';
		$Complaint_Descrptn=$_REQUEST['ComplaintDescrptn'];
		$Complaint_Descrptn = mysqli_real_escape_string($this->con,$Complaint_Descrptn);
		$result=mysqli_query($this->con,"CALL `wp_a_updateComplaintType`('$complaint_id','$complaintType_UpdatedBy','$Complaint_Descrptn')") or die(mysqli_error($this->con));
		$row=mysqli_fetch_array($result);
		if($row[0]==TRUE){
			$msg="Complaint Type already exists";
			return array("Message"=>$msg,"status"=>"false");
			}
		else{
			 $msg="Complaint Type updated";
			 return array("Message"=>$msg,"status"=>"true");
		}   
}

function deleteEnquiryType(){
			
		$enquiry_id=$_REQUEST['enquiryId'];

		$result=mysqli_query($this->con,"CALL `wp_a_deleteEnquiryType`('$enquiry_id')") or die(mysqli_error($this->con));
		$row=mysqli_fetch_array($result);
		if($row[0]==TRUE){
			return array("status"=>"false");
			}
		else{
			 $msg="Enquiry Type deleted";
			 return array("Message"=>$msg,"status"=>"true");
		}   
}

function deleteActionType(){
			
		$action_id=$_REQUEST['actionId'];

		$result=mysqli_query($this->con,"CALL `wp_a_deleteActionType`('$action_id')") or die(mysqli_error($this->con));
		$row=mysqli_fetch_array($result);
		if($row[0]==TRUE){
			return array("status"=>"false");
			}
		else{
			 $msg="Action Type deleted";
			 return array("Message"=>$msg,"status"=>"true");
		}   
}

function deleteComplaintType(){
			
		$action_id=$_REQUEST['actionId'];

		$result=mysqli_query($this->con,"CALL `wp_a_deleteComplaintType`('$action_id')") or die(mysqli_error($this->con));
		$row=mysqli_fetch_array($result);
		if($row[0]==TRUE){
			return array("status"=>"false");
			}
		else{
			 $msg="Complaint Type deleted";
			 return array("Message"=>$msg,"status"=>"true");
		}   
}

public function addcabtype(){
   
		  $city_id=$_REQUEST['city_id'];
		  $cabname=$_REQUEST['cabType'];
		  if(($cabname=='')or ($city_id=='')){
		  $msg="Blank entry";
			return array("Message"=>$msg,"status"=>"false");
		  }
		  else{
		  $result=mysqli_query($this->con,"CALL wp_a_addcabtype('$cabname','$city_id')")or die(mysqli_error($this->con));
		  $row=mysqli_fetch_array($result);
		  if($row[0]==TRUE){
		  $msg="Cab Type already exists";
			return array("Message"=>$msg,"status"=>"false");
			}
			else{
			 $msg="Cab Type inserted";
			 return array("Message"=>$msg,"status"=>"true");
			}
		}
}
		
		
		public function addvehicle(){
   
		  $vehicle_type=$_REQUEST['vehicle_type'];
		  $vehicle_model=$_REQUEST['vehicle_model'];
		  $vehicle_externalref=$_REQUEST['vehicle_externalref'];
		  $vehicle_year=$_REQUEST['vehicle_year'];
		  $vehicle_licenseplate=$_REQUEST['vehicle_licenseplate'];
		  $vehicle_color=$_REQUEST['vehicle_color'];
		  $vehicle_engine_no=$_REQUEST['vehicle_engine_no'];
		  $vehicle_fuel_type=$_REQUEST['vehicle_fuel_type'];
		  $vehicle_capacity=$_REQUEST['vehicle_capacity'];
		  $vehicle_is_active=$_REQUEST['vehicle_is_active'];
		  if(($vehicle_model=='') or ($vehicle_externalref=='') or ($vehicle_year=='') or ($vehicle_licenseplate=='') or ($vehicle_engine_no=='') or ($vehicle_fuel_type=='')){
		  $msg="Blank entry";
			return array("Message"=>$msg,"status"=>"false");
		  }
		  else{
		  $result=mysqli_query($this->con,"CALL wp_a_addvehicle('$vehicle_type','$vehicle_model','$vehicle_externalref','$vehicle_year','$vehicle_licenseplate','$vehicle_color','$vehicle_engine_no','$vehicle_fuel_type','$vehicle_capacity','$vehicle_is_active')")or die(mysqli_error($this->con));
		  $row=mysqli_fetch_array($result);
		  if($row[0]==TRUE){
		  $msg="Vehicle already exists";
			return array("Message"=>$msg,"status"=>"false");
			}
			else{
			 $msg="Vehicle inserted";
			 return array("Message"=>$msg,"status"=>"true");
			}
		   }
		}
		
		public function editvehicle(){
			
		  $vehicle_type=$_REQUEST['vehicle_type'];
		  $vehicle_model=$_REQUEST['vehicle_model'];
		  $vehicle_externalref=$_REQUEST['vehicle_externalref'];
		  $vehicle_year=$_REQUEST['vehicle_year'];
		  $vehicle_licenseplate=$_REQUEST['vehicle_licenseplate'];
		  $vehicle_color=$_REQUEST['vehicle_color'];
		  $vehicle_engine_no=$_REQUEST['vehicle_engine_no'];
		  $vehicle_fuel_type=$_REQUEST['vehicle_fuel_type'];
		  $vehicle_capacity=$_REQUEST['vehicle_capacity'];
		  $vehicle_is_active=$_REQUEST['vehicle_is_active'];
		  $vehicleId = $_REQUEST['vehicleId'];
		  
		  $result=mysqli_query($this->con,"CALL `wp_a_editvehicle`('$vehicle_type','$vehicle_model','$vehicle_externalref','$vehicle_year','$vehicle_licenseplate','$vehicle_color','$vehicle_engine_no','$vehicle_fuel_type','$vehicle_capacity','$vehicle_is_active','$vehicleId')") or die(mysqli_error($this->con));
		 		  
		  return array("status"=>"true");
		   
		}
		
	public function editCabType(){
		
	$cabTypeName=$_REQUEST['cabTypeName'];
	$cityId=$_REQUEST['cityId'];
	$cabTypeId=$_REQUEST['cabTypeId'];
	
	$result=mysqli_query($this->con,"CALL `wp_a_editCabType`('$cabTypeName','$cityId','$cabTypeId')") or die(mysqli_error($this->con));
	
	if($row[0]==TRUE)
	{
		$msg="Vehicle Type already exists";
		return array("Message"=>$msg,"status"=>"false");
	}
	else
	{
		$msg="Vehicle Type updated";
		return array("Message"=>$msg,"status"=>"true");
	}
}
		    
       
	public function group_mang(){
           
            $result=mysqli_query($this->con,"CALL `wp_a_group`()") or die(mysqli_error($this->con));
            $a = 1;
            while($data  = mysqli_fetch_assoc($result)){
                
                
                $group[] = array('<input type="checkbox" value="" name="MobileNumber[]" class="group_check">',"<a href='#' class='clickgroup' data='".$data['UID']."'>".$data['UID']."</a>",$data['group_name'],"<a href='#'>".$data['vendor_name']."</a>");
                 
            }
            if(mysqli_num_rows($result)>0){
                return array("data"=>$group);
            }
            else{
                return array("data"=>"");
            }
            
            
            
        }
        
      public function editGroupdata(){
          
          $groupid = $_POST['groupIds'];
          
         
          
          $result=mysqli_query($this->con,"CALL `wp_a_groupedit`('$groupid')") or die(mysqli_error($this->con));
          
          $data = mysqli_fetch_assoc($result);
          
          $groupdata = array(
                                "id"=>$data['UID'],
                                "vname"=>$data['vendor_name'],
                                "vgroup"=>$data['group_name'],
                                ) ;
          
          return array("data"=>$groupdata);
          
      }
      
      public function groupUpdate(){
            
                                            $group_ids = $_POST['group_ids'];
                                            $group_name = $_POST['group_name'];
                                            $group_boss = $_POST['group_boss'];
                                            file_put_contents('g.txt', print_r($_POST,TRUE));
                                            
          $result=mysqli_query($this->con,"CALL `wp_a_groupUpdate`('$group_ids','$group_name','$group_boss')") or die(mysqli_error($this->con)); 
          
          $data = mysqli_fetch_assoc($result);
                  if($data['aftrow'] == 1){
                    return array("data"=>true);  
                  }  
                  else{
                     return array("data"=>false);   
                  }
          
      }
      
      public function groupDelete(){
          $group_ids = $_POST['ids'];
          
          $result=mysqli_query($this->con,"CALL `wp_a_groupDelete`('$group_ids')") or die(mysqli_error($this->con)); 
          
          $data = mysqli_fetch_assoc($result);
          if($data['del'] == 1){
          return array("data"=>true);      
          }
          else{
                     return array("data"=>false);   
                  }
      }
      public function group_drivers(){
          
          $ids = $_REQUEST['uid'];
           //file_put_contents('id.txt',$ids);
            $result=mysqli_query($this->con,"CALL `wp_a_driverGroupList`('$ids')") or die(mysqli_error($this->con)); 
           $a = 1; 
           while($data  = mysqli_fetch_assoc($result)){
               $login = $data['loginStatus'];
               if( $login == 0){
                 $status = "Offline";  
               }
               else{
                   $status = "Login";  
               }
               
            
            $driverGroupList[] = array($a++,"<a href='#' class='driverdata' data='".$data['UID']."'>".$data['FirstName']."</a>",$data['ContactNo'],$data['Email'],$status,$data['name'],$data['status']);
           }
           return array("data"=>$driverGroupList);
      }
      
      
      public function group_drivers_vehicle(){
          $ids = $_REQUEST['uid'];
           //file_put_contents('id.txt',$ids);
            $result=mysqli_query($this->con,"CALL `wp_a_driverVehicle`('$ids')") or die(mysqli_error($this->con)); 
           $a = 1; 
           while($data  = mysqli_fetch_assoc($result)){
            
            $driverGroupList[] = array($a++,"<a href='#' class='vehicledata' vdata='".$data['id']."'>".$data['license_plate']."</a>",$data['name'],"TAXI");
           }
           return array("data"=>$driverGroupList);
          
      }
      
      public function smsData(){
          $driverids= $_GET['ids'];
       $result=mysqli_query($this->con,"CALL `wp_a_driversmsHistory`('$driverids')") or die(mysqli_error($this->con));    
       $a=1;   
       while($data  = mysqli_fetch_assoc($result)){
            
           if($data['status']==1){
               $status="SEND";
           }
           else{
             $status="NOT SEND";  
           }
                 
                $driverSmsList[] = array($data['sent_time'],$data['UID'],$data['mesg'],$data['cost'],$status);
        }
           
        if(mysqli_num_rows($result) >0 ){
            return array("status"=>"true","data"=>$driverSmsList);
        }else{
            return array("status"=>"false","data"=>"");
        }
        
          
      }
      
	  
	  public function vehicleEdit(){
	  
	$vehicleIds = $_POST['ids'];
	$result=mysqli_query($this->con,"CALL `wp_a_vehicleEdit`('$vehicleIds')") or die(mysqli_error($this->con));

		$data = mysqli_fetch_assoc($result);
	  
			$vehicledata = array(
                                                        "ids"=>$data['id'],
                                                        "uids"=>$data['uniqueId'],
                                                        "vehicle_type"=>"TEXI",
                                                        "model"=>$data['VehicleModel'],
                                                        "eref"=>$data['external_reference'],
                                                        "year"=>$data['year'],
                                                        "lplate"=>$data['license_plate'],
                                                        "colorv"=>$data['colour'],
                                                        "actv"=>$data['is_active']
												); 
												
				return array("data"=>$vehicledata);
	  
	  }
	  public function booking_action(){
		  $id=$_REQUEST['id'];
		  $result=mysqli_query($this->con,"CALL `sp_booking_action`('$id')") or die(mysqli_error($this->con));
		  $data=mysqli_fetch_assoc($result);
		  $booking_date = date('d-m-Y H:i:s',strtotime($data['date']));
		  
		  return array("data"=>array("ref"=>$data['ref'],"date"=>$booking_date,"stat"=>$data['stat'],"client"=>$data['client'],'pickup'=>$data['pickup']));
		  
	  }
	  public function vehicleUpdate(){
										
								$vidsecond = $_POST['vidsecond'];		
								$vtype = $_POST['vtype'];		
								$vmodel = $_POST['vmodel'];		
								$vEref = $_POST['vEref'];		
								$vyear = $_POST['vyear'];		
								$vlplate = $_POST['vlplate'];		
								$vcolor = $_POST['vcolor'];		
								$vactive = $_POST['vactive'];		
										
 $result=mysqli_query($this->con,"CALL `wp_a_vehicleupdate`('$vidsecond','$vtype','$vmodel','$vEref','$vyear','$vlplate','$vcolor','$vactive')") or die(mysqli_error($this->con));
	  
	  
	  $data = mysqli_fetch_assoc($result);
	  
				if($data['count'] == 1){
				return array("data"=>"true");
				}else{
				return array("data"=>"true");
				}
	  }
      
	  public function DriverPaymentUpload(){
		$deposit_date = date('Y-m-d',strtotime($_REQUEST['deposit_date']));
		$PaymentMode = $_REQUEST['cph_main_drpPaymentMode'];
		$fileupload = $_REQUEST['fileupload'];
		$trans = $_REQUEST['trans'];
		$chequeno = $_REQUEST['chequeno'];
		$partner_bank = $_REQUEST['partner_bank'];
		$amount = $_REQUEST['amount'];
		$deposit_bank = $_REQUEST['deposit_bank'];
		$deposited_branch = $_REQUEST['deposited_branch'];
		$id_tbluser = $_REQUEST['id_tbluser'];
		$id_tbldriver = $_REQUEST['id_tbldriver'];
		$remark = $_REQUEST['remark'];
				
		$qry1="INSERT INTO `tbldriverpayment`(driver_id,deposit_date,transaction_mode,fileupload,trans,chequeno,partner_bank,amount,deposit_bank,deposited_branch,remark,status) VALUES ('$id_tbldriver','$deposit_date','$PaymentMode','$fileupload','$trans','$chequeno','$partner_bank','$amount','$deposit_bank','$deposited_branch','$remark','0')";
		$result = mysqli_query($this->con,$qry1);
		
		if(mysqli_insert_id($this->con) > 0){
		$payment_ref_id=mysqli_insert_id($this->con);
		$payment_ref_no=$this->GenPaymentRefNo($payment_ref_id,'HV');
		$payment_ref_no=$payment_ref_no['generated'];		
		$sql="UPDATE tbldriverpayment SET payment_ref_no='$payment_ref_no' WHERE id='$payment_ref_id'"; //die;
		mysqli_query($this->con, $sql);
		$status="true";
		}
		else{
		$status="False";
		}
		
		return array("status" => $status);
		
	}
	
	//// Function to Generate Payment Ref No Starts Here By Mohit Jain /////////////
	
	public function GenPaymentRefNo($val,$initial){
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
		}else{
		$id=$initial.''.$dateYear.''.$dateMonth.''.$final1;
		}
		
		$generated=$id;		
		$status="true";
		$datam1['generated']=$generated;
		return $datam1;
	}
	  
	//// Function to Generate Payment Ref No Ends Here By Mohit Jain /////////////
	 
	  public function driverbankupdate(){
	  
				$ids = $_POST['driverProfileId']; 
				$bName = $_POST['bankName']; 
				$bHname = $_POST['bankAccHolder']; 
				$bAdds = $_POST['bankAdds']; 
				$bAno = $_POST['bankAccNo']; 
				$bNeft = $_POST['bankNeft']; 
				$bIbn = $_POST['bankIbn']; 
				
	  $result=mysqli_query($this->con,"CALL `wp_a_bankupdate`('$ids','$bName','$bHname','$bAdds','$bAno','$bNeft','$bIbn')") or die(mysqli_error($this->con));
	  
	  $data  = mysqli_fetch_assoc($result);
	  if($data['count'] == 1){
	  
		return array("data"=>true);
		
			  }else{
			  
			  return array("data"=>false);
			  }
	  
	}
	
	public function emamilData(){
					
					$ids = $_REQUEST['ids'];
	$result=mysqli_query($this->con,"CALL `wp_a_driveremailhistory`('$ids')") or die(mysqli_error($this->con));	

				
				while($data = mysqli_fetch_assoc($result)){
				
				if($data['status']==1){
					$status = "SENT";
				}else{
					$status = "NOT SENT";
				}
				
				
					$emails[] = array($data['time'],$data['UID'],$data['mesg'],$status);
					
				}
				if(mysqli_num_rows($result) >0 ){
            return array("status"=>"true","data"=>$emails);
        }else{
            return array("status"=>"false","data"=>"");
        }
			//return array("data"=>$emails);
	
	}
	
	public function statusData(){
	
				$id=$_REQUEST['ids'];
				$result=mysqli_query($this->con,"CALL sp_w_login_logs('$id')") or die(mysqli_error($this->con));
                $array=array();
                while($row=mysqli_fetch_array($result))
                { 
                     $start_date =new \DateTime($row['login_time']);
					 $end_date = new \DateTime(date("Y-m-d h:i:s"));
                     $interval = $start_date->diff($end_date);
                     
                     if($row['status']=='login'){
                         
                         $status ="You logged in";
                     }else{
                         $status ="You logged off";
                     }
//                     if($row['status']=='login')
//                     {
//                    $array[]=array($row['login_time'].'('.$interval->d.'d'.$interval->h.'h'.' ago)',"You logged in");
//                     }else 
//					 if($row['status']=='logout'){
//                         
//                        $array[]=array($row['logout_time'].'('.$interval->d.'d'.$interval->h.'h'.' ago)',"You logged off"); 
//                     }
              $array[]=array($row['login_time'],$row['logout_time'],$row['login_diff'],$status);         
                     }
                 
   
    
		//return array('status'=>'true','data'=>$array);
				
			return array("data"=>$array);

	
	}

///////////////////////////Code to show Login Logs History by Mohit Jain /////////
	
	
public function statusData1(){

$id=$_REQUEST['ids'];
$from_date_logs=$_REQUEST['from_date_logs'];
$to_date_logs=$_REQUEST['to_date_logs'];
if($from_date_logs=="" || $to_date_logs==""){
$sql="SELECT login_time,logout_time,login_date as `date`,SEC_TO_TIME(SUM(TIME_TO_SEC(login_diff)))as total_hrs from tblloginlog WHERE userId='$id'	 group by date ORDER by id DESC";
$sql_total_hrs="SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(login_diff))) as totalHrs from tblloginlog WHERE userId='$id'";
}else{
$sql="SELECT login_time,logout_time,login_date as `date`,SEC_TO_TIME(SUM(TIME_TO_SEC(login_diff)))as total_hrs from tblloginlog WHERE login_time BETWEEN '$from_date_logs' AND '$to_date_logs' and userId='$id' group by date ORDER by id DESC";
$sql_total_hrs="SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(login_diff))) as totalHrs from tblloginlog WHERE login_time BETWEEN '$from_date_logs' AND '$to_date_logs' and userId='$id'";
}

$result=mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
$result_total_hrs=mysqli_query($this->con,$sql_total_hrs) or die(mysqli_error($this->con));
$total = mysqli_fetch_assoc($result_total_hrs);
$totalHrs = $total['totalHrs'];

$array=array();
	$i=1;
	while($row=mysqli_fetch_array($result))
	{ 
	 if($row['total_hrs']=='')
	 $row['total_hrs']="Logged In";
	 $array[]=array($i,'<label rel="'.$row['date'].'" class="show_log" style="margin-bottom:0px; cursor:pointer;"><a>'.$row['date'].'</a></label>',$row['login_time'],$row['logout_time'],$row['total_hrs']);
	 $i++;
	 }
//return array('status'=>'true','data'=>$array);
  /*echo "<script>
  jQuery.cookie('totalHrs','".$totalHrs."',{ expires : 3});
  </script>";*/		
//setcookie("totalHrs",$totalHrs);	
	return array("data"=>$array,"totalHrs"=>$totalHrs);
}



////////////////////Code to show Login Logs History by clicking on particular Date /////////

public function statusDataDateSearch(){

$id=$_REQUEST['ids'];
$date=$_REQUEST['date'];
$sql="SELECT login_time,logout_time,login_date as `date`,login_diff as total_hrs from tblloginlog WHERE login_date BETWEEN '$date' AND '$date' and userId='$id'";
$result=mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
$array=array();
	$i=1;
	while($row=mysqli_fetch_array($result))
	{ 
	$array[]=array("i"=>$i,"login_time"=>$row['login_time'],"logout_time"=>$row['logout_time'],"total_hrs"=>$row['total_hrs']);
	$i++;
	}
	return array('status'=>'true','data'=>$array);			
	//return array("data"=>$array,"id"=>$id);
	
}


	
	  
	public function allocationData_old(){
	
				$ids = $_REQUEST['ids'];
				
	$result=mysqli_query($this->con,"CALL `wp_a_driverAhistory`('$ids')") or die(mysqli_error($this->con));	
	$a=1;
	while($data = mysqli_fetch_assoc($result)){
				
				if($data['status'] == 'M'){
						
						$dstatus = "Missed";
				
				}else if($data['status'] == 'R'){
						
						$dstatus = "Rejected";
				
				}
				else if($data['status'] == 'C'){
						
						$dstatus = "Cancelled";
				
				}else if($data['status'] == 'A'){
						
						$dstatus = "Accepted";
				
				}
				
				
					$statusdata[] = array($a++,$data['updateOn'],$dstatus);
					
				}
				
	if(mysqli_num_rows($result) >0 ){
            return array("status"=>"true","data"=>$statusdata);
        }else{
            return array("status"=>"false","data"=>"");
        }
                          

	
	}

////////// Function to retrieve Allocation history starts here by Mohit Jain/////////////	
	
public function allocationData(){
$ids = $_REQUEST['ids'];
$from_date_allocation=$_REQUEST['from_date_allocation'];
$to_date_allocation=$_REQUEST['to_date_allocation'];

if($from_date_allocation=="" || $to_date_allocation=="")
 $sql="SELECT DATE(updateOn) as `date` FROM `tblbookingregister` WHERE `driverId`='$ids' group by date order by date DESC"; 
else
$sql="SELECT DATE(updateOn) as `date` FROM `tblbookingregister` WHERE updateOn BETWEEN '$from_date_allocation' AND '$to_date_allocation' and `driverId`='$ids' group by date order by date DESC";
$result=mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
$a=1;
$dstatus='';
$dataAccepted='';
$dataCancelled='';
$dataMissed='';
$dataRejected='';
while($data = mysqli_fetch_assoc($result)){
$date=$data['date'];

$sql_accepted="SELECT status,count(*) as total_accepted FROM `tblbookingregister` WHERE `driverId`='$ids' and updateOn Like '%$date%' and status='A'";

$result_accepted=mysqli_query($this->con,$sql_accepted) or die(mysqli_error($this->con));
$data_accepted = mysqli_fetch_assoc($result_accepted);

$sql_cancel="SELECT status,count(*) as total_canceled FROM `tblbookingregister` WHERE `driverId`='$ids' and updateOn Like '%$date%' and status='C'";
$result_cancelled=mysqli_query($this->con,$sql_cancel) or die(mysqli_error($this->con));
$data_cancelled = mysqli_fetch_assoc($result_cancelled);

$sql_missed="SELECT status,count(*) as total_missed FROM `tblbookingregister` WHERE `driverId`='$ids' and updateOn Like '%$date%' and status='M'";
$result_missed=mysqli_query($this->con,$sql_missed) or die(mysqli_error($this->con));
$data_missed = mysqli_fetch_assoc($result_missed);

$sql_rejected="SELECT status,count(*) as total_rejected FROM `tblbookingregister` WHERE `driverId`='$ids' and updateOn Like '%$date%' and status='R'";
$result_rejected=mysqli_query($this->con,$sql_rejected) or die(mysqli_error($this->con));
$data_rejected = mysqli_fetch_assoc($result_rejected);

if(mysqli_num_rows($result_accepted) >0 ){
	$dataAccepted = $data_accepted['total_accepted'];
}

if(mysqli_num_rows($result_cancelled) >0 ){
	$dataCancelled = $data_cancelled['total_canceled'];
}
if(mysqli_num_rows($result_missed) >0 ){
	$dataMissed = $data_missed['total_missed'];
}

if(mysqli_num_rows($result_rejected) >0 ){
	$dataRejected .= $data_rejected['total_rejected'];
}

$statusdata[] = array($a,$data['date'],$dataAccepted,$dataCancelled,$dataMissed,$dataRejected);
$a++;
$dstatus='';
$dataAccepted='';
$dataCancelled='';
$dataMissed='';
$dataRejected='';
}

if(mysqli_num_rows($result) >0 ){
return array("status"=>"true","data"=>$statusdata);
}else{
return array("status"=>"false","data"=>"");
}
	
}
	
////////// Function to retrieve Allocation history ends here by Mohit Jain/////////////	


public function ledgerData(){
	
	$ids = $_REQUEST['ids'];
	$from_date_ledger=$_REQUEST['from_date_ledger'];
	$to_date_ledger=$_REQUEST['to_date_ledger'];
	
	           
            $sqlDriver ="SELECT status FROM tbl_driver_transaction WHERE user_id='$ids'";
			$resultDriver = mysqli_query($this->con, $sqlDriver) or die(mysqli_error($this->con));
			$nos = mysqli_num_rows($resultDriver); 
			if($nos > 0){
				$newdate = date('Y-m-d H:i:s');
               $newdata = array();
			   
			  $sql3 ="SELECT time,reason,amount, currentbalance,status  FROM tbl_driver_transaction WHERE  time BETWEEN '$from_date_ledger' AND '$to_date_ledger' and   user_id='$ids' ORDER BY time ASC ";
			   
			   $result = mysqli_query($this->con, $sql3) or die(mysqli_error($this->con));
			   
			   while($data = mysqli_fetch_assoc($result)){
						//$dataAll[] = $data;
						
						if($data['status']=="Debit")
						{
							$data['time2']     		= $data['time'];
							$data['reason2']	  		= $data['reason'];
							$data['status2']  		= "----";
							$data['amount2']  		= $data['amount'];
							$data['currentbalance2']	= $data['currentbalance'];
						}
						
						if($data['status']=="Credit")
						{
							$data['time2']     		= $data['time'];
							$data['reason2']	  	= $data['reason'];
							$data['status2']  		= $data['amount'];
							$data['amount2']  		= "----";
							
							$data['currentbalance2']	= $data['currentbalance'];
						}
						
						
					$newdata[]= array($data['time2'],$data['reason2'],$data['amount2'],$data['status2'],$data['currentbalance2']);	
                         }

				return array("status"=>"true","data"=>$newdata);
				
			}else{
				return array("status"=>"false","data"=>"");
			}   
			
			/* if(mysqli_num_rows($result) >0 ){
            return array("status"=>"true","data"=>$driverSmsList);
        }else{
            return array("status"=>"false","data"=>"");
        } */


	
}

///////////Function to show Allocation History of particular date by clicking on Status /////////

public function allocationDetails(){

$id=$_REQUEST['ids'];
$date=$_REQUEST['date'];
$status=$_REQUEST['status'];
$sql="SELECT count(*) as total,tblbookingregister.* FROM `tblbookingregister` WHERE `driverId`='$id' and updateOn Like '%$date%' and status='$status' group by bookingid";
$result=mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
$array=array();
	$i=1;
	while($data=mysqli_fetch_array($result))
	{ 
	$booking_id=$data['bookingid'];
	/*$sql_booking="SELECT tblcabbooking.ID as id, tblcabbooking.booking_reference as ref,tblcabbooking.MobileNo as mob_no,CONCAT(tblcabbooking.PickupAddress,' ',
	tblcabbooking.PickupArea) as departure,CONCAT(tbluserinfo.FirstName," ",tbluserinfo.LastName) as clientname,tblmasterpackage.Master_Package as booking_type 
	from tblcabbooking JOIN tbluserinfo ON tbluserinfo.UID=tblcabbooking.ClientID JOIN tblmasterpackage ON tblcabbooking.BookingType=tblmasterpackage.Package_Id 
	where tblcabbooking.ID='$booking_id'";
	$result_booking=mysqli_query($this->con,$sql_booking) or die(mysqli_error($this->con));
	$data_booking=mysqli_fetch_array($result_booking);*/

	$sql_booking="SELECT tblcabbooking.booking_reference as ref,tblcabbooking.MobileNo as mob_no,tblcabbooking.PickupAddress as booking_address,
	CONCAT(tbluserinfo.FirstName,' ',tbluserinfo.LastName) as clientname from tblcabbooking JOIN tbluserinfo ON tbluserinfo.UID=tblcabbooking.ClientID where tblcabbooking.ID='$booking_id'";
	$result_booking=mysqli_query($this->con,$sql_booking) or die(mysqli_error($this->con));
	$data_booking=mysqli_fetch_array($result_booking);
	$ref_no=$data_booking['ref']." (".$data['total'].")";

	if($data['status'] == 'M'){
	$dstatus = "Missed";
	$type = "By Notification";
	}else if($data['status'] == 'R'){
	$dstatus = "Rejected";
	$type = "By Notification";
	}
	else if($data['status'] == 'C'){
	$dstatus = "Cancelled";
	$type = $data['type'];
	}else if($data['status'] == 'A'){
	$dstatus = "Accepted";
	$type = $data['type'];
	}
	$array[]=array("i"=>$i,"time"=>$data['updateOn'],"ref_no"=>$ref_no,"booking_address"=>$data_booking['booking_address'],"clientname"=>$data_booking['clientname'],"mob_no"=>$data_booking['mob_no'],"status"=>$dstatus,"type"=>$type);
	$i++;
	}
	return array('status'=>'true','data'=>$array);				
}
	
	public function bookingHistroyData(){
	
	$ids = $_REQUEST['ids'];
	//echo "CALL `wp_a_driverBhistory`('$ids')";
	$hour=mysqli_query($this->con,"CALL `wp_a_driverBhistory`('$ids')") or die(mysqli_error($this->con));
        
            while($row=mysqli_fetch_array($hour))
           {
			   
                 //print_r($row);//die;
				if($row['totalBill']!="")
				{
					$row['totalBill']="INR ".$row['totalBill'];
					
				}
				if($row['reg']!=0)
				{
					$row['reg']="(".$row['reg'].")";
					
				}
				$complaint="";
				$comp_col="";
				if($row['com_id']!="")
				{
                $complaint="<br><br><small>Complaint: ".$row['com_status']."</small>";
                                    $comp_col="<div style='width:10px;height:10px;border-radius:100%;background:red'></div>";

				}			
				$Coupon= "0";	
               
			   
			   $hour_list[]=array('<label data="'.$row['id'].'" class="clickme" style="padding: 0px 10px 0px 0;color:#08c; cursor:pointer;">'.$row['ref'].'</label>',date("M-j-y H:i",strtotime($row['ordertime']))."<br><small style='color:gray'>".'</small>',"<div data='".$row['id']."'  style='cursor:pointer;' class='driver_click'>".$row['CabName'].'<br/><br>'."</div>",$row['booking_type'],$row['partner'].'<br>'.ucfirst($row['emp']),'<label class="client" data="'.$row['client_id'].'">'.$row['clientname'].'</label><br/><small style="color:gray">'.$row['mob_no'].'</small>',ucfirst($row['departure']),ucfirst($row['drop_area']),$row['status'].'<br/><small style="color:gray;text-transform:capitalize">'.$row['totalBill'].'</small>'.$complaint,$Coupon,$row['basic_tax_price'],"C-Share:".$row['cshare']."%<br><small>(INR ".$row['cAmount'].")</small>","D-Share:".$row['dshare']."%<br><small>(INR ".$row['dAmount'].")</small>","P-Share:".$row['pshare']."%<br><small>(INR ".$row['pAmount'].")</small>");
               
			   /*
			   $hour_list[]=array('<label data="'.$row['id'].'"class="clickme" style="padding: 0px 10px 0px 0;color:#08c; cursor:pointer;">'.$row['ref'].'</label>'.date("M-j-y H:i",strtotime($row['ordertime']))."<br><small style='color:gray'>".'</small>'."<div data='".$row['id']."'  style='cursor:pointer;' class='driver_click'>".$row['vehicle'].'<br/><small style="color:gray;text-transform:capitalize"  class="driver_click">'.$row['driver_name'].'</small><br><small>'.$row['reg'].'</small>'.$comp_col."</div>".$row['booking_type'].$row['partner'].'<br>'.ucfirst($row['emp']).'<label class="client" data="'.$row['client_id'].'">'.$row['clientname'].'</label><br/><small style="color:gray">'.$row['mob_no'].'</small>'.ucfirst($row['departure']).ucfirst($row['drop_area']).$row['status'].'<br/><small style="color:gray;text-transform:capitalize">'.$row['totalBill'].'</small>'.$complaint."C-Share:".$row['cshare']."%<br><small>(INR ".$row['cAmount'].")</small>"."D-Share:".$row['dshare']."%<br><small>(INR ".$row['dAmount'].")</small>"."P-Share:".$row['pshare']."%<br><small>(INR ".$row['pAmount'].")</small>");
			   */
           }
        if(mysqli_num_rows($hour) >0 ){
            return array("status"=>"true","data"=>$hour_list);
        }else{
            return array("status"=>"false","data"=>"");
        }

	
	} 

public function clientBookingHistory(){
	
				$ids = $_REQUEST['ids'];
	$hour=mysqli_query($this->con,"CALL `sp_a_clientBooking`('$ids')") or die(mysqli_error($this->con));
           $booking=array();
            while($row=mysqli_fetch_array($hour))
           {
			   
                //print_r($row);
				if($row['totalBill']!="")
				{
					$row['totalBill']="INR ".$row['totalBill'];
					
				}
				if($row['reg']!=0)
				{
					$row['reg']="(".$row['reg'].")";
					
				}
				$complaint="";
				$comp_col="";
				if($row['com_id']!="")
				{
                 $complaint="<br><br><small>Complaint: ".$row['com_status']."</small>";
				 $comp_col="<div style='width:10px;height:10px;border-radius:100%;background:red'></div>";

				}	
					/* $hour_list[]=array('<label data="'.$row['id'].'" class="clickme" style="padding: 0px 10px 0px 0;color:#08c; cursor:pointer;">'.$row['ref'].'</label>',
		date("M-j-y H:i",
		strtotime($row['ordertime']))."<br><small style='color:gray'>".'</small>',
		"<div data='".$row['id']."'  style='cursor:pointer;' class='driver_click'>".$row['vehicle'].'<br/><small style="color:gray;text-transform:capitalize"  class="driver_click">'.$row['driver_name'].'</small><br><small>'.$row['reg'].'</small>'.$comp_col."</div>",
		$row['booking_type'],
		"HELLO42".'<br>'.ucfirst($row['emp']),
		'<label class="client" data="'.$row['client_id'].'">'.$row['clientname'].'</label><br/><small style="color:gray">'.$row['mob_no'].'</small>',
		ucfirst($row['departure']),
		ucfirst($row['drop_area']),
		$row['status'].'<br/><small style="color:gray;text-transform:capitalize">'.$row['totalBill'].'</small>'.$complaint,
		"C-Share:".$row['cshare']."%<br><small>(INR ".$row['cAmount'].")</small>",
		"D-Share:".$row['dshare']."%<br><small>(INR ".$row['dAmount'].")</small>",
		"P-Share:".$row['pshare']."%<br><small>(INR ".$row['pAmount'].")</small>"); */
					
		$hour_list[]=array('<label data="'.$row['id'].'" class="clickme" style="padding: 0px 10px 0px 0;color:#08c; cursor:pointer;">'.$row['ref'].'</label>',
		date("M-j-y H:i",strtotime($row['ordertime']))."<br><small style='color:gray'>".'</small>',
		"<div data='".$row['id']."'  style='cursor:pointer;' class='driver_click'>".$row['vehicle'].'<br/><small style="color:gray;text-transform:capitalize"  class="driver_click">'.$row['driver_name'].'</small><br><small>'.$row['reg'].'</small>'.$comp_col."</div>",
		$row['booking_type'],
		"HELLO42".'<br>'.ucfirst($row['emp']),
		'<label class="client" data="'.$row['client_id'].'">'.$row['clientname'].'</label><br/><small style="color:gray">'.$row['mob_no'].'</small>',
		ucfirst($row['departure']),
		ucfirst($row['drop_area']),
		$row['status'].'<br/><small style="color:gray;text-transform:capitalize">'.$row['totalBill'].'</small>'.$complaint,
		"C-Share:".$row['cshare']."%<br><small>(INR ".$row['cAmount'].")</small>",
		"D-Share:".$row['dshare']."%<br><small>(INR ".$row['dAmount'].")</small>",
		"P-Share:".$row['pshare']."%<br><small>(INR ".$row['pAmount'].")</small>");
               
           }
		   
		   if(mysqli_num_rows($hour) >0 ){
			   
			    return array("data"=>$hour_list, "id"=>$row['id'],"cab"=>$row['vehicle'],"category"=>$row['vehicle'],"bill"=>$row['totalBill'], "orderTime"=>$row['ordertime']);
            //return array("status"=>"true","data"=>$emails);
        }else{
            return array("status"=>"false","data"=>"");
        }
		   
           

	
	} 	
	
	function driverdownloadfiles(){
	
			$ids = $_REQUEST['driverProfileId'];
			$result=mysqli_query($this->con,"CALL `wp_a_driverCerDown`('$ids')") or die(mysqli_error($this->con));
			
			$data = mysqli_fetch_assoc($result);

			$file = array(
								"resident"=>$data['residential_proof'],
								"office"=>$data['office_proof'],
								"pan"=>$data['pancard_proof'],
								"vehicle"=>$data['vehicle_proof'],
								"license"=>$data['license_proof'],
								"police"=>$data['police_proof'],
								"audit"=>$data['audit_proof'],
								"rc_proof"=>$data['rc_proof']
								);
                        
                                        
                     
                        
				return array("data"=>$file);
                        
	
	}
	
	
		function drivercertificates(){
	
			$ids = $_REQUEST['driverProfileId'];
			$result=mysqli_query($this->con,"CALL `wp_a_driverCerDown`('$ids')") or die(mysqli_error($this->con));
			
			$data = mysqli_fetch_assoc($result);

			$file = array(
								"resident"=>$data['residential_proof'],
								"office"=>$data['office_proof'],
							"pan"=>$data['pancard_proof'],
							"vehicle"=>$data['vehicle_proof'],
							"license"=>$data['license_proof'],
								"police"=>$data['police_proof'],
							"audit"=>$data['audit_proof'],
							"rc_proof"=>$data['rc_proof']
								);
                        
                                        
                     
                        
				return array("data"=>$file);
                        
	
	}
        
       public function updateCer(){
           
           
		$ids = $_POST['dids'];
		if($_POST['driver_addsH']!="")
			$resAdds = $_POST['driver_addsH'];
		else
			$resAdds = $_POST['residential_proof_h'];
		if($_POST['driver_oaddsH']!="")
			$ofcAdds = $_POST['driver_oaddsH'];
		else
			$ofcAdds = $_POST['office_proof_h'];
		if($_POST['driver_panH']!="")
			$panProof = $_POST['driver_panH'];
		else
			$panProof = $_POST['pan_card_h'];
		if($_POST['file_vehicleH']!="")
			$vehcleProof = $_POST['file_vehicleH'];
		else
			$vehcleProof = $_POST['vehicle_image_h'];
		if($_POST['file_badgeH']!="")
			$badge = $_POST['file_badgeH'];
		else
			$badge = $_POST['licence_img_h'];
		if($_POST['file_policeH']!="")
			$police = $_POST['file_policeH'];
		else
			$police = $_POST['police_verification_h'];
		if($_POST['file_auditH']!="")
			$audit = $_POST['file_auditH'];
		else
			$audit = $_POST['audit_verification_h'];
		if($_POST['file_rcproofH']!="")
			$rc_proof = $_POST['file_rcproofH'];
		else
			$rc_proof = $_POST['rcproof_verification_h'];
           
           
           $result=mysqli_query($this->con,"CALL `wp_a_driverCerUpdate`('$ids','$resAdds','$ofcAdds','$panProof','$vehcleProof','$badge','$police','$audit','$rc_proof')") or die(mysqli_error($this->con));
			
           $data = mysqli_fetch_assoc($result);
           
           if($data['count'] == 1){
               
               return array("data"=>"success");
               
           }
           else{
               
                return array("data"=>"unsuccess");
           }
           
           
           
       }
       
       public function vsearchOfdriver(){
           
//           $platelicence = $_REQUEST['vlPlate'];
//           $vmake = $_REQUEST['vmake'];
//           $vmodel = $_REQUEST['vmodel'];
//           $vextref = $_REQUEST['vextref'];
//          //driver
//           $id = $_REQUEST['vid'];
//           $dvsearch = $_REQUEST['dvsearch'];
//           $dfirstdname = $_REQUEST['dfirstdname'];
//           $dexts = $_REQUEST['dext'];
//           $ddname = $_REQUEST['ddname'];
//            $dEdext = $_REQUEST['ddext'];
           //group
            
            $gid = $_REQUEST['gid'];
           $gvsearch = $_REQUEST['gvsearch'];
           $gname = $_REQUEST['gname'];
           $groupext = $_REQUEST['groupext'];
           $gdname = $_REQUEST['gdname'];
            $gDext = $_REQUEST['gDext'];
           
         //  file_put_contents('search.txt', print_r($_REQUEST,TRUE));
   $result=mysqli_query($this->con,"CALL `wp_a_vehicleSearchDriver`('$gid','$gvsearch','$gname','$groupext','$gdname','$gDext')") or die(mysqli_error($this->con));
        
               $a =1; 
                while($data = mysqli_fetch_assoc($result)){
                    
                    
                    if($data['is_active'] == 1){
			$onlineStatus = "<small style='width: 15px;height: 15px;background: green;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
                    }else if($data['is_active']==2){
                        $onlineStatus = "<small style='width: 15px;height: 15px;background: rgb(251, 186, 0);padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
                    }else if($data['is_active']==3){
			$onlineStatus ="<small style='width: 15px;height: 15px;background: black;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
                    }else if($data['is_active']==4){
                        $onlineStatus ="<small style='width: 15px;height: 15px;background: blue;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
                    }else{
                        $onlineStatus ="<small style='width: 15px;height: 15px;background: #B64242;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
                    }
				
              //file_put_contents('yoyo.txt', print_r($data['is_active'],TRUE));
              
                $data_list[] = array("<input name='drives_id[]' type=checkbox value='".$data['UID']."'>",$a++,"<a href='#' class='mydrivers' data='".$data['UID']."'>".$data['UID']."</a>",$data['FirstName'],"<a href='#'>".$data['vehicleName']."</a> <img src='../public/image/vehicle.png' style='width:30px'>",$data['VehicleRegistrationNo'],'Subscription',$data['approvedStatus'],$data['DrivingLicenceNo'],$data['ContactNo']."<br>"."<small style='color:gray'>".$data['Email']."</small>",$data['created_date']."<br><small style='color:gray'>".$data['booking']." bookings<small>".$onlineStatus);
               
           }
           //file_put_contents('yoyo.txt', print_r($data_list,TRUE));
            
            if(mysqli_num_rows($result)>0)
            {
                return array("status"=>"true","data"=>$data_list);
            }
            else
            {
                return array("status"=>"false","data"=>"");
            }
       
      
           
       }
       
       
       public function driverSearch(){
          
//          //driver
           $id = trim($_REQUEST['vid']);
           $dvsearch = trim($_REQUEST['dvsearch']);
           $dfirstdname = trim($_REQUEST['dfirstdname']);
           $dexts = trim($_REQUEST['dext']);
           $ddname = trim($_REQUEST['ddname']);
           $dEdext = trim($_REQUEST['ddext']);
           
            //print_r($_REQUEST); 
         
           
         //  file_put_contents('search.txt', print_r($_REQUEST,TRUE));
		// echo "CALL `wp_a_searchDriver`('$id','$dvsearch','$dfirstdname','$dexts','$ddname','$dEdext')"; die;
   $result=mysqli_query($this->con,"CALL `wp_a_searchDriver`('$id','$dvsearch','$dfirstdname','$dexts','$ddname','$dEdext')") or die(mysqli_error($this->con));
        
                $a =1; 
                while($data = mysqli_fetch_assoc($result)){
                    
                    
                    if($data['is_active'] == 1){
						$onlineStatus = "<small style='width: 15px;height: 15px;background: green;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
					}else if($data['is_active']==2){
						$onlineStatus = "<small style='width: 15px;height: 15px;background: rgb(251, 186, 0);padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
                    }else if($data['is_active']==3){
						$onlineStatus ="<small style='width: 15px;height: 15px;background: black;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
				   }
				   else if($data['is_active']==4){
						$onlineStatus ="<small style='width: 15px;height: 15px;background: blue;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
				   }
				   else {
						$onlineStatus ="<small style='width: 15px;height: 15px;background: #B64242;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
				   }
				
              //file_put_contents('yoyo.txt', print_r($data['is_active'],TRUE));
              
                $data_list[] = array("<input name='drives_id[]' type=checkbox value='".$data['UID']."'>",$a++,"<a href='#' class='mydrivers' data='".$data['UID']."'>".$data['UID']."</a>",$data['FirstName'],"<a href='#'>".$data['vehicleName']."</a> <img src='../public/image/vehicle.png' style='width:30px'>",$data['VehicleRegistrationNo'],$data['CabIgnitionType'],'Subscription',$data['approvedStatus'],$data['DrivingLicenceNo'],$data['ContactNo']."<br>"."<small style='color:gray'>".$data['Email']."</small>",$data['created_date']."<br><small style='color:gray'>".$data['booking']." bookings<small>".$onlineStatus);
               
           }
           //file_put_contents('yoyo.txt', print_r($data_list,TRUE));
            if(mysqli_num_rows($result)>0)
            {
                return array("status"=>"true","data"=>$data_list);
            }
            else
            {
                return array("status"=>"false","data"=>"");
            }
       
      
           
       }
       public function drivervehicleSearch(){
           
           $platelicence = $_REQUEST['vlPlate'];
           $vmake = $_REQUEST['vmake'];
           $vmodel = $_REQUEST['vmodel'];
           $vextref = $_REQUEST['vextref'];
//          //driver
        
            
         
           
         //  file_put_contents('search.txt', print_r($_REQUEST,TRUE));
   $result=mysqli_query($this->con,"CALL `wp_a_searchvehicleDriver`('$platelicence','$vmake','$vmodel','$vextref')") or die(mysqli_error($this->con));
        
               $a =1; 
                while($data = mysqli_fetch_assoc($result)){
                    
                    
                if($data['is_active'] == 1){
			$onlineStatus = "<small style='width: 15px;height: 15px;background: green;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
		}else if($data['is_active']==2){
						$onlineStatus = "<small style='width: 15px;height: 15px;background: rgb(251, 186, 0);padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
                }else if($data['is_active']==3){
						$onlineStatus ="<small style='width: 15px;height: 15px;background: black;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
		}else if($data['is_active']==4){
						$onlineStatus ="<small style='width: 15px;height: 15px;background: blue;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
		}else {
						$onlineStatus ="<small style='width: 15px;height: 15px;background: #B64242;padding: 5px;border-radius: 180px;display: inline-block;margin: 2px 0 -4px 10px;'></small>";
		}
				
              //file_put_contents('yoyo.txt', print_r($data['is_active'],TRUE));
              
                $data_list[] = array("<input name='drives_id[]' type=checkbox value='".$data['UID']."'>",$a++,"<a href='#' class='mydrivers' data='".$data['UID']."'>".$data['UID']."</a>",$data['FirstName'],"<a href='#'>".$data['name']."</a> <img src='../public/image/vehicle.png' style='width:30px'>",$data['VehicleRegistrationNo'],'Subscription',$data['vehicleName'],$data['DrivingLicenceNo'],$data['ContactNo']."<br>"."<small style='color:gray'>".$data['Email']."</small>",$data['created_date']."<br><small style='color:gray'>".$data['booking']." bookings<small>".$onlineStatus);
               
           }
           //file_put_contents('yoyo.txt', print_r($data_list,TRUE));
            if(mysqli_num_rows($result)>0)
            {
                return array("status"=>"true","data"=>$data_list);
            }
            else
            {
                return array("status"=>"false","data"=>"");
            }
       
      
           
       }
       
       public function driverPhotoVerification(){
           
           $photo = $_POST['photo_verify'];
           $ids = $_POST['dids'];
           
           //file_put_contents('search1.txt', print_r($_POST,TRUE));
		   //echo "CALL `wp_a_driverimageVerf`('$photo','$ids')";
       $result=mysqli_query($this->con,"CALL `wp_a_driverimageVerf`('$photo','$ids')") or die(mysqli_error($this->con));
            
                    $data = mysqli_fetch_assoc($result);
                   // echo $ph = $data['img'];
                   //  file_put_contents('search.txt', print_r($data,TRUE));
        if($data['img'] == 1){
             return array("data"=>true);
        }
           
           
       
       else {
           return array("data"=>false);
       }
       
       
       
       } 
	   
	          
       public function feature_update(){
           $features = $_POST['driver_features'];
           $ids = $_POST['dids']; //die;
           
           file_put_contents('search1.txt', print_r($_POST,TRUE));
       $result=mysqli_query($this->con,"CALL `wp_a_driverfeatureUpdate`('$features','$ids')") or die(mysqli_error($this->con));
            
                    $data = mysqli_fetch_assoc($result);
                   // echo $ph = $data['img'];
                   //  file_put_contents('search.txt', print_r($data,TRUE));
        if($data['features'] == 1){
             return array("data"=>true);
        }
           
           
       
       else {
           return array("data"=>false);
       }
           
           
       }
       
       public function allgroups(){
			$datasearch= $_REQUEST['term'];
		    $dataAll=array();
			$data=array();
			$result=mysqli_query($this->con,"CALL `wp_a_driverGroupsremove`('$datasearch')") or die(mysqli_error($this->con));                  
                    while($data = mysqli_fetch_assoc($result)){ 
                        $dataAll[]=array("label"=>$data['group_name'],
						    "value"=>$data['id']); 
                         }
                    echo json_encode($dataAll);
                    return $dataAll;
		}
       
       public function driverEditGroup(){
           
           $gid = $_POST['drivergroupid'];
           $did = $_POST['dids'];
           $result=mysqli_query($this->con,"CALL `wp_a_driverEditGroup`(' $gid','$did')") or die(mysqli_error($this->con));
           $data = mysqli_fetch_assoc($result);
                  
        if($data['gedit'] == 1){
             return array("data"=>true);
        }
           
           
       
       else {
           return array("data"=>false);
       }
           
       }
       
       
       public function driverGroupName(){
           
           $did = $_POST['dids'];
            $result=mysqli_query($this->con,"CALL `wp_a_driverGroupName`('$did')") or die(mysqli_error($this->con));
           $data = mysqli_fetch_assoc($result);
           return array("data"=>$data['group_name']);
           
       }
       
       public function driverRemoveGroup(){
            $gname = $_REQUEST['drivergroupName'];
            $result=mysqli_query($this->con,"CALL `wp_a_driverRemoveGroup`('$gname')") or die(mysqli_error($this->con));
            $row=mysqli_fetch_array($result);
            if($row[0]==TRUE){
                $msg="Group deleted";
                return array("Message"=>$msg,"status"=>"true");
            }
            else{
                $msg="Group not deleted";
                return array("Message"=>$msg,"status"=>"false");
            } 
           
    }
       
	          public function driverledgers_pre(){           
           $ids = $_REQUEST['ids'];           
           $sqlDriver ="SELECT * FROM tbl_driver_transaction WHERE user_id='$ids'";
			$resultDriver = mysqli_query($this->con, $sqlDriver) or die(mysqli_error($this->con));
			$nos = mysqli_num_rows($resultDriver); 
			if($nos > 0){
				$newdate = date('Y-m-d H:i:s');
               $newdata = array();
              for($i = 0; $i<$nos;$i++){                  
              $sql ="SELECT DATE_SUB('$newdate',INTERVAL 7 DAY) AS lastdate FROM tbl_driver_transaction WHERE user_id='$ids' ORDER BY time DESC";
                $result = mysqli_query($this->con, $sql) or die(mysqli_error($this->con));
             /*  $sql2 ="SELECT SUM(amount) AS total,currentbalance FROM tbl_driver_transaction WHERE user_id='$ids' AND status='Deducted' AND time BETWEEN DATE_SUB('$newdate',INTERVAL 7 DAY) AND '$newdate' ORDER BY time DESC"; */
			  
			  $sql2 ="SELECT SUM(amount) AS total,currentbalance FROM tbl_driver_transaction WHERE user_id='$ids' AND status='Debit' group by time LIMIT 0,8";
                $result2 = mysqli_query($this->con, $sql2) or die(mysqli_error($this->con));                
     /* $sql3 ="SELECT SUM(amount) AS total FROM tbl_driver_transaction WHERE user_id='$ids' AND status='Recharge' AND time BETWEEN DATE_SUB('$newdate',INTERVAL 7 DAY) AND '$newdate' ORDER BY time DESC"; */
	 
	 $sql3 ="SELECT SUM(amount) AS total FROM tbl_driver_transaction WHERE user_id='$ids' AND status='Credit' group by time LIMIT 0,8";
	 
	 
                $result3 = mysqli_query($this->con, $sql3) or die(mysqli_error($this->con));
                $data = mysqli_fetch_assoc($result);
                 $data2 = mysqli_fetch_assoc($result2);
                 $data3 = mysqli_fetch_assoc($result3);                
                $newdate = $data['lastdate'];                
              //echo $data2['total'];  
					if($data2['total'] == ""){
						$total212 =  "----";
					}
					if($data3['total'] == ''){
						 $total313= "----";
					}
					if($data2['currentbalance'] == ''){
						 $currentbalance =  "----";                
					} 
						$newdata[]= array($data['lastdate'],$data2['total'].$total212,$data3['total'].$total313,$data2['currentbalance'].$currentbalance);
			    }
				return array("status"=>"true","data"=>$newdata);
				
			}else{
				return array("status"=>"false","data"=>"");
			}   
			
			/* if(mysqli_num_rows($result) >0 ){
            return array("status"=>"true","data"=>$driverSmsList);
        }else{
            return array("status"=>"false","data"=>"");
        } */


			
       }
	   
      public function driverledgers(){           
           $ids = $_REQUEST['ids'];           
           $sqlDriver ="SELECT * FROM tbl_driver_transaction WHERE user_id='$ids'";
			$resultDriver = mysqli_query($this->con, $sqlDriver) or die(mysqli_error($this->con));
			$nos = mysqli_num_rows($resultDriver); 
			if($nos > 0){
				$newdate = date('Y-m-d H:i:s');
               $newdata = array();
			   
			  $sql3 ="SELECT tr.time,tr.reason,tr.amount,tr.basic_tax_amount, tr.currentbalance,tr.status,tr.booking_ref,us.FirstName,us.LastName FROM tbl_driver_transaction tr
			  left join tblcabbooking cb on cb.booking_reference=tr.booking_ref
              left join tbluserinfo us on us.UID = cb.ClientID
              WHERE user_id='$ids' ORDER BY time DESC";
			   
			   $result = mysqli_query($this->con, $sql3) or die(mysqli_error($this->con));
			   
			   while($data = mysqli_fetch_assoc($result)){
						//$dataAll[] = $data;
						
						if($data['status']=="Debit")
						{
							$data['time2']     			= $data['time'];
							$data['reason2']	  		= $data['booking_ref'].'  - '.$data['FirstName']." ".$data['LastName'];
							$data['status2']  			= "----";
							$data['amount2']  			= $data['amount'];
							$data['currentbalance2']	= $data['currentbalance'];
						}
						
						if($data['status']=="Credit")
						{
							$data['time2']     			= $data['time'];
							$data['reason2']	  		= $data['reason'];
							$data['status2']  			= $data['amount'];
							$data['amount2']  			= "----";
							$data['currentbalance2']	= $data['currentbalance'];
						}
						
						
					$newdata[]= array($data['time2'],$data['reason2'],$data['basic_tax_amount'],$data['amount2'],$data['status2'],$data['currentbalance2']);	
                         }

				return array("status"=>"true","data"=>$newdata);
				
			}else{
				return array("status"=>"false","data"=>"");
			}   
			
			/* if(mysqli_num_rows($result) >0 ){
            return array("status"=>"true","data"=>$driverSmsList);
        }else{
            return array("status"=>"false","data"=>"");
        } */


			
    }
	   
/////////////////// Function to show the ledger of a particular driver by Mohit Jain///


public function driverledgers_mohit(){    
$ids = $_REQUEST['ids'];
//$newdate = date('Y-m-d H:i:s');
echo $sqlDriver ="SELECT time as last_date FROM tbl_driver_transaction WHERE time BETWEEN DATE_SUB(NOW(), INTERVAL 2 DAY) AND NOW() and user_id='$ids' ORDER BY time DESC";
$resultDriver = mysqli_query($this->con, $sqlDriver) or die(mysqli_error($this->con));

$newdata = array();
while($data = mysqli_fetch_array($resultDriver))
{
$sql2 ="SELECT SUM(amount) AS total,currentbalance FROM tbl_driver_transaction WHERE user_id='$ids' AND status='Deducted' AND time BETWEEN DATE_SUB(NOW(),INTERVAL 2 DAY) AND NOW() ORDER BY time DESC";
$result2 = mysqli_query($this->con, $sql2) or die(mysqli_error($this->con));
$sql3 ="SELECT SUM(amount) AS total FROM tbl_driver_transaction WHERE user_id='$ids' AND status='Recharge' AND time BETWEEN DATE_SUB(NOW(),INTERVAL 2 DAY) AND NOW() ORDER BY time DESC";
$result3 = mysqli_query($this->con, $sql3) or die(mysqli_error($this->con));
$data2 = mysqli_fetch_assoc($result2);
$data3 = mysqli_fetch_assoc($result3);
//$newdate = $data['lastdate'];
//echo $data2['total'];  
if($data2['total'] == ""){
$total212 =  "----";
}
if($data3['total'] == ''){
$total313= "----";
}
if($data2['currentbalance'] == ''){
$currentbalance =  "----";

}

$newdata[]= array($data['lastdate'],$data2['total'].$total212,$data3['total'].$total313,$data2['currentbalance'].$currentbalance);
}
return array("data"=>$newdata);
} 
      
           
           
           public function driverdashboard(){
              
               $newdate = date('Y-m-d');
              
               $newdata = array();
              for($i = 0; $i<8;$i++){ 
                 
               $sql ="SELECT DATE_SUB('$newdate',INTERVAL 7 DAY) AS lastdate FROM tblbookingtracker ORDER BY DATE_TIME DESC";
                $result = mysqli_query($this->con, $sql) or die(mysqli_error($this->con));
              $data = mysqli_fetch_assoc($result);
              
              
                $sql2 = "SELECT SUM(totalBill) AS gross FROM `tblbookingcharges` WHERE paid_at BETWEEN DATE_SUB('$newdate',INTERVAL 7 DAY) AND '$newdate' ORDER BY paid_at DESC";
                $result2 = mysqli_query($this->con, $sql2) or die(mysqli_error($this->con));
                
                 $data2 = mysqli_fetch_assoc($result2);
                 
                 
                 $sql3 = "SELECT AVG(totalBill) AS grossAvg FROM `tblbookingcharges` WHERE paid_at BETWEEN DATE_SUB('$newdate',INTERVAL 7 DAY) AND '$newdate' ORDER BY paid_at DESC";
                $result3 = mysqli_query($this->con, $sql3) or die(mysqli_error($this->con));
                
                 $data3 = mysqli_fetch_assoc($result3);
                    
                 
                 
                 $sql4 = "SELECT COUNT(ID) AS requiestedbooking FROM `tblcabbooking` WHERE BookingDate BETWEEN DATE_SUB('$newdate',INTERVAL 7 DAY) AND '$newdate' ORDER BY BookingDate DESC";
                $result4 = mysqli_query($this->con, $sql4) or die(mysqli_error($this->con));
                
                 $data4 = mysqli_fetch_assoc($result4);
                 
                 $sql5 = "SELECT COUNT(ID) AS acceptedbooking FROM `tblcabbooking` WHERE status>2 AND BookingDate BETWEEN DATE_SUB('$newdate',INTERVAL 7 DAY) AND '$newdate' ORDER BY BookingDate DESC";
                $result5 = mysqli_query($this->con, $sql5) or die(mysqli_error($this->con));
                
                 $data5 = mysqli_fetch_assoc($result5);
                 
                  $sql6 = "SELECT COUNT(id) AS paidbooking FROM `tblbookingcharges` WHERE  paid_at BETWEEN DATE_SUB('$newdate',INTERVAL 7 DAY) AND '$newdate' ORDER BY paid_at DESC";
                $result6 = mysqli_query($this->con, $sql6) or die(mysqli_error($this->con));
                
                 $data6 = mysqli_fetch_assoc($result6);
                 
                 
                 $sql7 = "SELECT COUNT(id) AS cancelledbooking FROM `tblcabbooking` WHERE  BookingDate BETWEEN DATE_SUB('$newdate',INTERVAL 7 DAY) AND '$newdate' ORDER BY BookingDate DESC";
                $result7 = mysqli_query($this->con, $sql7) or die(mysqli_error($this->con));
                
                 $data7 = mysqli_fetch_assoc($result7);
                 
                 
                 $sql8 = "SELECT SUM(extracharges) AS extracharge FROM `tblbookingcharges` WHERE  paid_at BETWEEN DATE_SUB('$newdate',INTERVAL 7 DAY) AND '$newdate' ORDER BY paid_at DESC";
                $result8 = mysqli_query($this->con, $sql8) or die(mysqli_error($this->con));
                
                 $data8 = mysqli_fetch_assoc($result8);
                 
                 
                 $sql9 = "SELECT COUNT(status) AS non FROM `tblcabbooking` WHERE status=14 AND BookingDate BETWEEN DATE_SUB('$newdate',INTERVAL 7 DAY) AND '$newdate' ORDER BY BookingDate DESC";
                $result9 = mysqli_query($this->con, $sql9) or die(mysqli_error($this->con));
                
                 $data9 = mysqli_fetch_assoc($result9);
                 
                
                $newdate = $data['lastdate'];
                
                $nextdatem =  explode('-',$data['lastdate']);
                //$nextdated =  explode('-',$data['lastdate']);
             
               $newdatam[]=$nextdatem[1].'-'.$nextdatem[2];
              $gross[] = $data2['gross'];
              $grossAvg[] = round($data3['grossAvg']);
              $totalrbooking[] = $data4['requiestedbooking'];
              $totalabooking[] = $data5['acceptedbooking'];
              $totalPbooking[] = $data6['paidbooking'];
              $totalCbooking[] = $data7['cancelledbooking'];
              $extraCharges[] = $data8['extracharge'];
              $noshowing[] = $data9['non'];
              }
              //$text = array($newdatam);
              return array("data"=>$newdatam,"gross"=>$gross,"avg"=>$grossAvg,"rBooking"=>$totalrbooking,"aBooking"=>$totalabooking,"paidBooking"=>$totalPbooking,"cancelled"=>$totalCbooking,"extra"=>$extraCharges,"noshowing"=>$noshowing);
           }
           
           
           
           
           public function drivergsearch(){
               
               $groupname = $_POST['vgroup'];
               
                $drivername = $_POST['name']; 
               
               $taxino = $_POST['vno'];
               
               $clients = $_POST['clients'];
               
       if($groupname != ""){        
      $vgroup = "SELECT a.UID as driverid FROM `tbldriver` a INNER JOIN `tblvendor` b ON a.vendor_id=b.id WHERE b.group_name='$groupname'";
      $vresult = mysqli_query($this->con, $vgroup) or die(mysqli_error($this->con));
      $did = mysqli_fetch_assoc($vresult);
      $driverid = $did['driverid'];
       }
      else if($drivername != ""){
           
      $driverId = "SELECT `UID` FROM `tbldriver` WHERE `FirstName`='$drivername'";
      $dresult = mysqli_query($this->con, $driverId) or die(mysqli_error($this->con));
      $did = mysqli_fetch_assoc($dresult);
      $driverid = $did['UID'];   
           
       } 
       
      else if($taxino != ""){
         $taxiId = "SELECT `UID` FROM `tbldriver` WHERE `VehicleRegistrationNo`='$taxino'";
      $vresult = mysqli_query($this->con, $taxiId) or die(mysqli_error($this->con));
      $did = mysqli_fetch_assoc($vresult);
        $driverid = $did['UID'];      
       }
       
       
       else if($clients != ""){
           $client = "SELECT `ID` FROM `tbluser` WHERE `LoginName`='$clients'";
           $cresult = mysqli_query($this->con, $client) or die(mysqli_error($this->con));
           
       }
       
              $newdate = date('Y-m-d');
              
               $newdata = array();
               
             //echo $driverid;  
              for($i = 0; $i<8;$i++){ 
                 
               $sql ="SELECT DATE_SUB('$newdate',INTERVAL 7 DAY) AS lastdate FROM tblbookingtracker ORDER BY DATE_TIME DESC";
                $result = mysqli_query($this->con, $sql) or die(mysqli_error($this->con));
              $data = mysqli_fetch_assoc($result);
              
              
                $sql2 = "SELECT SUM(a.totalBill) AS gross FROM `tblbookingcharges` a INNER JOIN `tblcabbooking` b ON a.BookingID=b.ID WHERE b.pickup='$driverid' AND paid_at BETWEEN DATE_SUB('$newdate',INTERVAL 7 DAY) AND '$newdate' ORDER BY paid_at DESC";
                $result2 = mysqli_query($this->con, $sql2) or die(mysqli_error($this->con));
                
                 $data2 = mysqli_fetch_assoc($result2);
                 
                 
                 $sql3 = "SELECT AVG(a.totalBill) AS grossAvg FROM `tblbookingcharges` a INNER JOIN `tblcabbooking` b ON a.BookingID=b.ID WHERE b.pickup='$driverid' AND paid_at BETWEEN DATE_SUB('$newdate',INTERVAL 7 DAY) AND '$newdate' ORDER BY paid_at DESC";
                $result3 = mysqli_query($this->con, $sql3) or die(mysqli_error($this->con));
                
                 $data3 = mysqli_fetch_assoc($result3);
                    
                 
                 
                 $sql4 = "SELECT COUNT(ID) AS requiestedbooking FROM `tblcabbooking` WHERE pickup='$driverid' AND BookingDate BETWEEN DATE_SUB('$newdate',INTERVAL 7 DAY) AND '$newdate' ORDER BY BookingDate DESC";
                $result4 = mysqli_query($this->con, $sql4) or die(mysqli_error($this->con));
                
                 $data4 = mysqli_fetch_assoc($result4);
                 
                 $sql5 = "SELECT COUNT(ID) AS acceptedbooking FROM `tblcabbooking` WHERE pickup='$driverid' AND status>2 AND BookingDate BETWEEN DATE_SUB('$newdate',INTERVAL 7 DAY) AND '$newdate' ORDER BY BookingDate DESC";
                $result5 = mysqli_query($this->con, $sql5) or die(mysqli_error($this->con));
                
                 $data5 = mysqli_fetch_assoc($result5);
                 
                  $sql6 = "SELECT COUNT(a.id) AS paidbooking FROM `tblbookingcharges` a INNER JOIN `tblcabbooking` b ON a.BookingID=b.ID WHERE b.pickup='$driverid' AND paid_at BETWEEN DATE_SUB('$newdate',INTERVAL 7 DAY) AND '$newdate' ORDER BY paid_at DESC";
                $result6 = mysqli_query($this->con, $sql6) or die(mysqli_error($this->con));
                
                 $data6 = mysqli_fetch_assoc($result6);
                 
                 
                 $sql7 = "SELECT COUNT(id) AS cancelledbooking FROM `tblcabbooking` WHERE  BookingDate BETWEEN DATE_SUB('$newdate',INTERVAL 7 DAY) AND '$newdate' ORDER BY BookingDate DESC";
                $result7 = mysqli_query($this->con, $sql7) or die(mysqli_error($this->con));
                
                 $data7 = mysqli_fetch_assoc($result7);
                 
                 
                 $sql8 = "SELECT SUM(extracharges) AS extracharge FROM `tblbookingcharges` WHERE  paid_at BETWEEN DATE_SUB('$newdate',INTERVAL 7 DAY) AND '$newdate' ORDER BY paid_at DESC";
                $result8 = mysqli_query($this->con, $sql8) or die(mysqli_error($this->con));
                
                 $data8 = mysqli_fetch_assoc($result8);
                 
                 
                 $sql9 = "SELECT COUNT(status) AS non FROM `tblcabbooking` WHERE pickup='$driverid' AND status='14' AND BookingDate BETWEEN DATE_SUB('$newdate',INTERVAL 7 DAY) AND '$newdate' ORDER BY BookingDate DESC";
                $result9 = mysqli_query($this->con, $sql9) or die(mysqli_error($this->con));
                
                 $data9 = mysqli_fetch_assoc($result9);
                 
                
                $newdate = $data['lastdate'];
                
                $nextdatem =  explode('-',$data['lastdate']);
                //$nextdated =  explode('-',$data['lastdate']);
             
               $newdatam[]=$nextdatem[1].'-'.$nextdatem[2];
              $gross[] = $data2['gross'];
              $grossAvg[] = round($data3['grossAvg']);
              $totalrbooking[] = $data4['requiestedbooking'];
              $totalabooking[] = $data5['acceptedbooking'];
              $totalPbooking[] = $data6['paidbooking'];
              $totalCbooking[] = $data7['cancelledbooking'];
              $extraCharges[] = $data8['extracharge'];
              $noshowing[] = $data9['non'];
              }
              //$text = array($newdatam);
              return array("data"=>$newdatam,"gross"=>$gross,"avg"=>$grossAvg,"rBooking"=>$totalrbooking,"aBooking"=>$totalabooking,"paidBooking"=>$totalPbooking,"cancelled"=>$totalCbooking,"extra"=>$extraCharges,"noshowing"=>$noshowing);
           }
           
           public function fetch_bookingFilter_dropDown(){
               
               $sql ="select * from `tblcabstatus` where type= 'rating' order by status_id asc";
               $result = mysqli_query($this->con, $sql) or die(mysqli_error($this->con));
               while($row = mysqli_fetch_array($result))
               {
                   $rating[]=array($row['id'],$row['type'],$row['status_id'],$row['status']);
               }
               
               $sql1 ="select * from `tblcabstatus` where type= 'cab' and status_id between 10 and 17 order by id desc;";
               $result1 = mysqli_query($this->con, $sql1) or die(mysqli_error($this->con));
               while($row1 = mysqli_fetch_array($result1))
               {
                   $status[]=array($row1['id'],$row1['type'],$row1['status_id'],$row1['status']);
               }
			   
			   
		//$sql11 ="select * from `tblcabstatus` where type= 'cab' and status_id between 0 and 9 order by id ASC;";
		$sql11 ="select * from `tblcabstatus` where type= 'cab' and status_id!=18 and status_id!=15 order by status ASC;";
               $result11 = mysqli_query($this->con, $sql11) or die(mysqli_error($this->con));
               while($row11 = mysqli_fetch_array($result11))
               {
                   $status1[]=array($row11['id'],$row11['type'],$row11['status_id'],$row11['status']);
               }
               
               $sql1 ="select * from `tblcabstatus` where type= 'paymentType' order by id desc";
               $result1 = mysqli_query($this->con, $sql1) or die(mysqli_error($this->con));
               while($row1 = mysqli_fetch_array($result1))
               {
                   $payment_type[]=array($row1['id'],$row1['type'],$row1['status_id'],$row1['status']);
               }
               
               $sql2 ="select * from `tblcabstatus` where type= 'is_paid' order by status_id desc";
               $result2 = mysqli_query($this->con, $sql2) or die(mysqli_error($this->con));
               while($row = mysqli_fetch_array($result2))
               {
                   $isPaid[]=array($row['id'],$row['type'],$row['status_id'],$row['status']);
               }
               
               $sql3 ="select * from `tblcabstatus` where type= 'orderTime' order by status_id asc";
               $result3 = mysqli_query($this->con, $sql3) or die(mysqli_error($this->con));
               while($row = mysqli_fetch_array($result3))
               {
                   $orderTime[]=array($row['id'],$row['type'],$row['status_id'],$row['status']);
               }
                
               return array("rating_point"=>$rating,"booking_status"=>$status,"booking_status1"=>$status1,"paymentType"=>$payment_type,"isPaid"=>$isPaid,"orderTime"=>$orderTime,"status"=>"true");
               
        }
        
        
        
        
        
        
               
            public function fetch_cabType(){

            $sql ="select `CabType`,`CabName` from `tblbookingbill`";
            $result = mysqli_query($this->con, $sql) or die(mysqli_error($this->con));
            while($row = mysqli_fetch_array($result))
            {
                $cabtype[]=array($row['CabName'],$row['CabType']);
            }

            return array("data"=>$cabtype,"status"=>"true");

        }
		
		public function getUnassignedBooking(){
			$row = array();
			$data = array();
			$sql = "SELECT tbluser.ID AS DriverId, tblcabbooking.ID AS BookingId, tblcabbooking.booking_reference, tbluser.Latitude AS Dlat, tbluser.Longtitude1 AS Dlon, tblcabbooking.PickupLatitude AS Blat, tblcabbooking.PickupLongtitude AS Blon FROM tblcabbooking, tbluser WHERE tblcabbooking.pickup = 0 AND tblcabbooking.is_updation_allow!='FALSE' AND CONCAT(pickupdate,' ',pickuptime)>NOW() AND tbluser.ID = '".$_REQUEST['DriverId']."' ORDER BY tblcabbooking.ID DESC";
			$qry = mysqli_query($this->con, $sql) or die(mysqli_error());
			while($row = mysqli_fetch_assoc($qry)){
				$Distance = round($this->getDistance($row['Dlat'], $row['Dlon'], $row['Blat'], $row['Blon'], 'K'));
				$row['Distance'] = $Distance." - ".$_REQUEST['BookingRange'];
				if($_REQUEST['BookingRange'] != '' and $_REQUEST['BookingRange'] != 0 and $_REQUEST['BookingRange'] != 'undefined'){
					if($Distance <= $_REQUEST['BookingRange']){
						$data[] = array($row);
					}
				}else{
					$data[] = array($row);
				}
			}
			return array('data'=>$data);
		}
		
		
		
	function send_notification($registatoin_ids='APA91bFu_STr-AWvFNkevf1t5hX9TkKtT3uBXRtkb2ZZ-wPVxGg45hBiyic5lenpsHHf_qIYmL02DsGpG24tEn33UOp_UwuU35hy_wRDR2ZnFBuiuTTGX-WxdqP-sFVwrsUqJHfeOGlg', $message='Hello') {
		// include config
		//include_once './config.php';
		//define("GOOGLE_API_KEY", "AIzaSyCOpOWLJG7G2PGHDb9uCl0eALEHYrPApVw");
		// Set POST variables
		define("GOOGLE_API_KEY", "AIzaSyBcsz_BY5Yg03KnXCbhDwXgOlnwch9kGXE");
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
	
	public function testdis(){
		//echo $this->getDistance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
		echo round($this->getDistance(28.5549868, 77.084482999, 28.6526756, 77.1931225, "K")) . " Kilometers<br>";
		//echo $this->getDistance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";
	}
	
	
	//////////// Function to Forcefully Notification Starts Here /////////////
	
	public function send_forcefully_notification($registatoin_ids, $message) {
		define("GOOGLE_API_KEY", "AIzaSyAFE4jcZwQsi2LIx_2TfczrM3DlSTRpNYM");
		$url = 'https://android.googleapis.com/gcm/send';
		$fields = array('registration_ids' => $registatoin_ids, 'data' => $message,);
		$headers = array(
			'Authorization: key=' . GOOGLE_API_KEY,
			'Content-Type: application/json'
		);
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
		return $result;
	}
	
	//////////// Function to Forcefully Notification Ends Here /////////////
	
	//////////// Function to calculate Bill for Forcefully Notification Starts Here /////////////
	
	
	function calculateForceBillForBooking($distance,$BookingId_i,$vehicleId,$datamArray){
		$estimated_bill="CALL wp_estimatedBill($vehicleId,$BookingId_i)";
		$result = mysqli_query($this->con,$estimated_bill) or die(mysqli_error($this->con));
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
		$datamArray['min_distance']=$data['Min_Distance'];
		$night_charges=explode(' ',$data['NightCharges']);
		$datamArray['NightCharges']=$night_charges[0];
		$waiting_fees = explode('_',$data['waiting_fees']);
		$datamArray['WaitingFreeMin'] = $waiting_fees[0];
		$datamArray['WaitingBiforeCharge'] = $waiting_fees[1];
		$datamArray['WaitingAfterCharge'] = $waiting_fees[2];
		$datamArray['WaitingCharge_per_minute']=$data['WaitingCharge_per_minute'];
		$datamArray['MinimumCharge']=$data['MinimumCharge'];
		$datamArray['Waitning_minutes']=$data['Waitning_minutes'];
		$datamArray['night_rate_begins']=$data['night_rate_begins'];
		$datamArray['night_rate_ends']=$data['night_rate_ends'];
		$datamArray['trip_charges_per_hour']=$data['tripCharge_per_minute'];
		$datamArray['currency']=$data['currency'];
		$datamArray['measure']=$data['measure'];
		$datamArray['tax']=$data['basic_tax'];
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
			//echo $data['book_type'];
			if($data['cab_for']=="0"){
				$TotalBill=$MinimumChrage;
			}else{
				$TotalBill=$data['cab_for']*$tripCharge_per_minute;    
			}
		}
		$datamArray['estimaedTotalBill'] = round($TotalBill);
		$datamArray['estimatedTime'] = round($timeTakenHr)." HR";
		return $datamArray;
	}	
	
	//////////// Function to calculate Bill for Forcefully Notification Ends Here /////////////
	
	//////////// Function for Sending Forcefully Notification Starts Here /////////////
	
		public function assign_booking() {
		$datamArray=array();
		$id=array();
		//$driverId='1770';
		//$bookingId='5955';
		$driverId=$_REQUEST['driver_id'];
		$bookingId=$_REQUEST['ref'];
		
		//////////////// Query to select Booking Stack Details Starts Here ///////////////////

		$sql_booking_stack="SELECT booking_stack.*,tblcabbooking.CarType FROM booking_stack 
		LEFT JOIN tblcabbooking ON tblcabbooking.ID = '$bookingId' WHERE booking_stack.status='' and booking_stack.booking_id='$bookingId'"; 
		$bookingstack_details = mysqli_query($this->con, $sql_booking_stack) or die(mysqli_error($this->con));
		$row=$bookingstack_details->fetch_array();
		$bookingCarType = $row['CarType']; 
		
		//////////////// Query to select Booking Stack Details Ends Here ///////////////////
		
		//////////////// Query to Update Booking Stack Details Starts Here ///////////////////
		
		//$sql_update_stack="UPDATE booking_stack SET last_try=NOW(),status='W' WHERE booking_id='$bookingId'";
		//$sql_update = mysqli_query($this->con, $sql_update_stack) or die(mysqli_error($this->con));
		//$sql_update_cabbooking="UPDATE tblcabbooking SET status='2' WHERE ID='$bookingId'";
		//$sql_update = mysqli_query($this->con, $sql_update_cabbooking) or die(mysqli_error($this->con));

		//if($sql_update!=''){
		$sql_update_booking_logs="INSERT INTO tblbookinglogs (`bookingid`,`status`,`message`,`time`) VALUES('$bookingId',2,'Executed',NOW())";
		mysqli_query($this->con, $sql_update_booking_logs) or die(mysqli_error($this->con));
		//}
				
		//////////////// Query to Update Booking Stack Details Ends Here ///////////////////
		
		//////////////// Query to select Booking Details Starts Here ///////////////////
		$sql_booking="select tblsubpackage.Sub_Package_Id ,tblcabbooking.* from tblcabbooking INNER JOIN tblmasterpackage 
				ON tblcabbooking.BookingType = tblmasterpackage.Package_Id INNER JOIN tblsubpackage 
				ON tblmasterpackage.Sub_Package_Id = tblsubpackage.Sub_Package_Id where tblcabbooking.ID = '$bookingId'";
		$booking_details = mysqli_query($this->con, $sql_booking) or die(mysqli_error($this->con));
		$data=$booking_details->fetch_assoc();
		
		$datamArray['name']=$data['UserName'];
		$datamArray['booking_id']=$data['ID'];
		$datamArray['booking_type']=$data['BookingType'];
		$datamArray['pickup_address']=$data['PickupAddress'];
		$datamArray['pickup_location']=$data['PickupArea'];
		$datamArray['drop_address']=$data['DropAddress'];
		$datamArray['drop_location']=$data['DropArea'];
		$datamArray['pickup_latitude']=$data['PickupLatitude'];
		$datamArray['pickup_longitude']=$data['PickupLongtitude'];
		$datamArray['drop_latitude']=$data['DestinationLatitude'];
		$datamArray['drop_longitude']=$data['DestinationLongtitude'];
		$datamArray['amount']=($data["estimated_price"]*5)/100;
		$datamArray['pickup_time']=$data['PickupTime'];
		$datamArray['per_distance_charge']=$data['approx_distance_charge'];
		$datamArray['mobile_no']=$data['MobileNo'];
		$datamArray['CabFor']=$data['CabFor'];
		$datamArray['estimated_price']=$data['estimated_price'];
		$datamArray['configPackageNo']=$data['Sub_Package_Id'];
		$datamArray['drop_distance']="";
		if($data['EstimatedDistance']==0){
		$data2= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$datamArray['pickup_latitude'].','.$datamArray['pickup_longitude']."&destination=".$datamArray['drop_latitude'].','.$datamArray['drop_longitude']."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584");
		$enc=json_decode($data2);
		$enc2=$enc->routes[0];
		$datamArray['drop_distance']=round((($enc2->legs[0]->distance->value)/1000),1);
		$update_cabbooking="UPDATE tblcabbooking SET EstimatedDistance='".$datamArray['drop_distance']."' WHERE id ='".$datamArray['booking_id']."'";
		$sqlUpdate=mysqli_query($this->con, $update_cabbooking) or die(mysqli_error($this->con));
		}else{
			$datamArray['drop_distance']=$data['EstimatedDistance'];
		}	
		
		//////////////// Query to select Booking Details Ends Here ///////////////////
		
		//////////////// Query to select Driver Details Starts Here ///////////////////
		
		$book_id=$datamArray['booking_id'];
		
		$sql_driver="SELECT gcm_id,latitude,longtitude1 FROM tbluser WHERE id='$driverId'";
		$driver_details = mysqli_query($this->con, $sql_driver) or die(mysqli_error($this->con));
		$result=$driver_details->fetch_array();
		$id[]=$result[0];
		
		$data= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$result[1].",".$result[2]."&destination=".$datamArray['pickup_latitude'].','.$datamArray['pickup_longitude']."&sensor=false&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584");
		$enc=json_decode($data);
		$enc2=$enc->routes[0];
		$datamArray['pickup_distance']=round((($enc2->legs[0]->distance->value)/1000),1);
				

		$datamArray = $this->calculateForceBillForBooking($datamArray['drop_distance'],$book_id,$bookingCarType,$datamArray);
		$sql_updateCabbooking="UPDATE tblcabbooking SET estimated_price='".$datamArray['estimaedTotalBill']."',EstimatedTime='".$datamArray['estimatedTime']."' WHERE ID ='".$datamArray['booking_id']."'";
		$cab_update=mysqli_query($this->con, $sql_updateCabbooking);

		$datamArray['estimatedTime']=$datamArray['pickup_time'];
		$datamArray['send_time']="".date("Y-m-d H:i:s");
		
		$datamArray['forcefully_assign']="Please accept this Booking Urgent Basis";
		$datamArray['expiry_timeout_secs']="60";
		//////////////// Query to select Driver Details Ends Here ///////////////////

		//echo "<pre>";print_r($datamArray); die;
		$res=$this->send_forcefully_notification($id,$datamArray);
		return array("status"=>"true","result"=>$res);
		}	
		
	//////////// Function for Sending Forcefully Notification Ends Here /////////////
	
	/*****admin logout starts here******/
	public function admin_logout($val='',$reset=''){

		if($reset == 1){
	    	$admin_id = $val;

		}
		else $admin_id = $_POST['val'];
		/* $sql="SELECT * from tbladminlog WHERE adminId='$admin_id' order by id desc limit 1";
		details = mysqli_query($this->con, $sql) or die(mysqli_error($this->con));
		$result=details->fetch_array();
		echo $login_time=$result['login_time']; */
		
	$sql1 = "UPDATE tbladminlog set `logout_time` = NOW(), logout_date = CURDATE() WHERE adminId = '$admin_id' order by id desc limit 1";
		$qry = mysqli_query($this->con, $sql1) or die(mysqli_error($this->con));
		$user_session = new Container('admin');
        $user_session->getManager()->getStorage()->clear('admin');
		return array("status"=>"true","message"=>"Admin Logout Successfully");
	}
	/*****admin logout ends here******/   

	/// Function to fetch recording file starts here ///
	
	public function fetch_recording(){
			$id=$_REQUEST['booking_id'];
			//$id="192.168.0.1/Recording"
			$sql ="select `CustRecordFileName`,`status` from `tbl_ivr_recordingdetail` WHERE CustCallRefrence='$id'";
            $result = mysqli_query($this->con, $sql) or die(mysqli_error($this->con));
            while($row = mysqli_fetch_array($result))
            {
				if($row['status']==1){
					$url[]="192.168.0.1/hello42/public/recordings/".$row['CustRecordFileName'].'.mp3';
				}
				//echo $recordingDetail=$row['CustRecordFileName'];
                //$cabtype[]=array($row['CabName'],$row['CabType']);
            }
			//die;
           return array("data"=>$url,"status"=>"true");
			//return array("data"=>"true");   
			
			
			
		}
	
	/// Function to fetch recording file Ends here ///
		
	/// Function to fetch recording file starts here ///
	
	public function changeLoginStatus(){
			$uid=$_REQUEST['uid'];
			$status=$_REQUEST['status'];
			if($status=="logOff"){
				$loginStatus=1;
				$IsActive=1;
			}else{
				$loginStatus=0;
				$IsActive=0;
			}
			$sql ="UPDATE tbluser SET loginStatus='$loginStatus',is_active='$IsActive' WHERE ID='$uid'";
            $result = mysqli_query($this->con, $sql) or die(mysqli_error($this->con));
			if(mysql_affected_rows($result)>0){
				$status="true";
			}else{
				$status="false";
			}
           return array("status"=>$status);	
		}
	
	/// Function to fetch recording file Ends here ///
	
		
	/// Function to fetch Way Points Starts here ///
	
	public function waypoint_info(){
	            $booking_id=$_REQUEST['booking_id'];
	            $driver_id=$_REQUEST['driver_id'];
	            ////////
	           	$sql1="SELECT PickupLatitude,PickupLongtitude FROM tblcabbooking WHERE ID='".$booking_id."'";
	           	$res=mysqli_query($this->con,$sql1) or die(mysqli_error($this->con));
	           	$result=mysqli_fetch_array($res);
	           	$pickup_lat=$result['PickupLatitude'];
	           	$pickup_lng=$result['PickupLongtitude'];
	            ////////
	            $sql="SELECT lat,longi FROM tbldriverlocation WHERE user_id='".$driver_id."' and booking_id='".$booking_id."'";
	           $way_points=mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
	           $val=array();
	           $num_rows	=	mysqli_num_rows($way_points);
	           if($num_rows>0){
	           $status="true";
	           $num_rows1=1;
	            while($row=mysqli_fetch_array($way_points))
	           {
	           	  if($num_rows1==1){
	           	  $start_lat=floatval($row['lat']);
	           	  $start_long=floatval($row['longi']);
	           	  }elseif($num_rows1==$num_rows){
	           	  $end_lat=floatval($row['lat']);
	           	  $end_long=floatval($row['longi']);	
	           	  }
	              $val[]=array('lat'=>floatval($row['lat']),'lng'=>floatval($row['longi'])); 
	              $num_rows1++; 
	           }
	           }else{
	           $status="false";
	           $val="";
	           }
	            return array("status"=>$status,"data"=>$val,"start_lat"=>$start_lat,"start_long"=>$start_long,"end_lat"=>$end_lat,"end_long"=>$end_long,"pickup_lat"=>floatval($pickup_lat),"pickup_lng"=>floatval($pickup_lng));   
	        }
	        
	 /// Function to fetch Way Points Ends here ///
	 
	 /// Function to Upload the files from admin Starts here By Mohit Jain ///
	 
	 public function upload_files(){
 $name = $_GET['formname'];
 $id = $_GET['ids'];
 $query="SELECT `residential_proof` FROM `tbldriver` WHERE `UID`='$id'";
 $sql = mysqli_query($this->con,$query) or die(mysqli_error($this->con));
 $result = mysqli_fetch_array($sql);
 //file_put_contents('dids.txt', print_r($result,TRUE)) ;
 $folder = explode("/",$result[0]);
 $folderpath = $folder[2];
 //print_r($n);
//file_put_contents('dids.txt', print_r($n,TRUE)) ;	
      
$allowed = array('png', 'jpg', 'gif','zip','pdf','docx','doc','xlsx','jpeg');
//print_r($_FILES["$name"]) ;
//echo $_FILES["$name"]['error'];

if(isset($_FILES["$name"]) && $_FILES["$name"]['error'] == 0){

  $file = 'public/tmp/upload/';
 $filepath = $file.$folderpath;
$fileCreate = mkdir($filepath, 0777,true);


	$extension = pathinfo($_FILES["$name"]['name'], PATHINFO_EXTENSION);

	if(!in_array(strtolower($extension), $allowed)){
		//echo '{"status":"error"}';
		return array("status"=>"error"); 
		exit;
	}
        $urlpath = $filepath.'/'.$name.'_'.uniqid().'.'.$extension;

	if(move_uploaded_file($_FILES["$name"]['tmp_name'], $urlpath)){
	
	 //$path = $urlpath;
	 
	$path= str_replace('public/', '', $urlpath);

	//if(move_uploaded_file($_FILES["$name"]['tmp_name'], $filepath.'/'.$_FILES["$name"]['name'].'_'.$name.'.'.$extension)){
		$pathdata = array("url"=>$path,"name"=>$name);
		
		//echo '{"status":"success","path":'.json_encode($pathdata).'}';
		return array("status"=>"success","path"=>$pathdata); 
		//exit;
	}
}	 
	 } 
	 
	/// Function to Upload the files from admin Ends here By Mohit Jain ///
	
/////////////////// Function to show the Driver Payment History of a particular driver Starts Here by Mohit Jain///

	      public function driverpayHistory(){           
           $ids = $_REQUEST['ids'];           
           $sqlDriver ="SELECT * FROM tbldriverpayment WHERE driver_id='$ids'";
			$resultDriver = mysqli_query($this->con, $sqlDriver) or die(mysqli_error($this->con));
			$nos = mysqli_num_rows($resultDriver); 
			if($nos > 0){
				$newdate = date('Y-m-d H:i:s');
               $newdata = array();
			   
			   while($data = mysqli_fetch_assoc($resultDriver)){
					
					if($data['status']==0){
					$payStatus="Not Approved";
					}else{
					$payStatus="Approved";
					}
					
						
					$newdata[]= array($data['deposit_date'],$data['payment_ref_no'],$data['transaction_mode'],$data['amount'],$data['deposit_bank'],$data['deposited_branch'],$data['remark'],$payStatus);	
                         }

				return array("status"=>"true","data"=>$newdata);
				
			}else{
				return array("status"=>"false","data"=>"");
			}   
    }
	   
/////////////////// Function to show the Driver Payment History of a particular driver Ends Here by Mohit Jain///
	
	
	 
}
