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
use Zend\View\Model\ViewModel;
use Tunneling\Model\BookingCabFare;
use Tunneling\Model\DatabaseConfig;

class Menu{
	
	protected $con;
	private $data = array();
	private $row = array();
	
	public function __construct(){
		date_default_timezone_set("Asia/Kolkata");
		$db=new DatabaseConfig();
		$this->con=$db->getDatabaseConfig();
		$key = '';
		//$this->con=mysqli_connect("10.0.0.101:3306","root","root","hello42_new");
		//$this->con=mysqli_connect("166.62.35.117","root","Travel@(4242)","hello42_new");
		//$this->con=mysqli_connect("10.0.0.35","root","root","hello42_new");
		//$this->con=mysqli_connect("103.228.152.14","hello42c_hello","hello(42)","hello42c_db_hello42");
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
	
	public function mohit(){
	/*$address= '103, Desh Bandhu Gupta Rd, New Delhi, Block 57, Karol Bagh,India';
	$address=urlencode($address);				
	$url='https://maps.googleapis.com/maps/api/geocode/json?address='.$address;
	echo $url;*/

	$pickupTime = '2016-04-18 15:13:00'; 
	echo $pickupTime	= strtotime($pickupTime);
	echo '<br>';
	$currentTime = '2016-04-18 15:14:54';
	echo $currentTime = strtotime($currentTime);
	if($currentTime>$pickupTime)
	{
		echo "Post";
	}else{
		echo "Pre";
	}
		
	}
	
	public function SentEmail($to, $from, $subject, $body){
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		// Create email headers
		$headers .= 'From: '.$from."\r\n".
		mail($to,$subject,$body,$headers);
	}
  
    public function testsms(){
		$Mobile = '9650465338';
		$sql="SELECT * FROM tbl_sms_template WHERE msg_sku='message'";
	    $res = mysqli_query($this->con,$sql);
		    $msg_query=mysqli_fetch_array($res); 
			//echo $msg_query['message']; die;
			$array=explode('<variable>',$msg_query['message']);
			$array[0]=$array[0]."Naveen";
			$array[1]=$array[1]."bookingcabs.com"; 
			$text=  urlencode(implode(" ",$array));	
			//echo $text; 
			//$text="Hello";
			//file_put_contents("mssg.txt",$text);
			$url="http://push1.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$Mobile&from=Helocb&dlrreq=true&text=$text&alert=1";
			file_get_contents($url);
			return true;
	
	}
  
    public function signupValidation(){
         $userEmailId = $_REQUEST['userName'];
		 //$BookingId = $userEmailId;
		 file_put_contents('ggg.txt',$userEmailId);
         $result = mysqli_query($this->con,"CALL wp_validation('$userEmailId')");
         $data = mysqli_fetch_assoc($result);
         mysqli_free_result($result);
		 mysqli_next_result($this->con);
         //echo $data['checkusers'];
         if($data['checkusers'] <1 ){
         	// $this->send_sms_new2($BookingId,$flag="welcome_msg");
			if (strpos($userEmailId, '@') !== false) {
    		$body="Hi ".$userEmailId.",Welcome to Hello42,Thanks for Registeration, Please complete the Registeration Formalities log on to http://bookingcabs.com/" 
    		."Or Call 42424242 <br><br>Best Regards,<br>Hello42 Cab Team";
			$from='info@hello42cab.com';
			$subject='Welcome to Hello42';
			//$this->send_mail($userEmailId,$from,$subject,$body);
			}else{
				
			/// Code for SMS Going to the particular user Starts here ////
			$sql="SELECT * FROM tbl_sms_template WHERE msg_sku='welcome_msg'"; //die;
         	$res = mysqli_query($this->con,$sql);
		    $msg_query=mysqli_fetch_array($res); 
			$array=explode('<variable>',$msg_query['message']);
			$array[0]=$array[0].$userEmailId;
			$array[1]=$array[1]."http://hello42cab.com/";
			$text=  urlencode(implode("",$array));

			file_put_contents("mssg.txt",$text);
			//echo $text;die;	
			 $url="http://push3.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=$userEmailId&from=Helocb&dlrreq=true&text=$text&alert=1";
			file_get_contents($url); 
			// /// Code for SMS Going to the particular user Ends here ////
			
			}
			//die;      	
			/* $res = mysqli_query($this->con,"SELECT * FROM tbl_sms_template WHERE msg_sku='forgot_password'");
		    $msg_query=mysqli_fetch_array($res); 
			$array=explode('<variable>',$msg_query['message']);
			$array[0]=$array[0]."Naveen";
			$text=  urlencode(implode("",$array));	
			//file_put_contents("mssg.txt",$text);
			$url="http://push1.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=919650465338&from=Helocb&dlrreq=true&text=$text&alert=1";
			file_get_contents($url); */
			return array("Status" => "001");
		}else{
			return array("Status" => "002","ErrorCode"=>'102');
		}
    }
	
	public function signupTest(){		
		
			/*$str = "Hi! Welcome to !  activate account enter the verification code on verification page of Hello42. or contact Hello 42cabs.com/app";
			echo strlen($str);
			$strr = urlencode($str);
			
			$mobileNo = "9821454150";
			
			 $url="http://push1.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$mobileNo&from=Helocb&dlrreq=true&text=$strr&alert=1";

			file_get_contents($url);*/
			
			$query=mysqli_query($this->con,"SELECT * FROM tbl_sms_template WHERE msg_sku='otp'");
			       $msg_query=mysqli_fetch_array($query);
					$array=explode('<variable>',$msg_query['message']);
							$array[0]=$array[0].$otp;							
							$array[1]=$array[1]."http://bookingcabs.com/";
							$text=  urlencode(implode("",$array));
							
							$UserNo = "9821454150";
							//echo $text;exit;
						$url = "http://push1.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$UserNo&from=Helocb&dlrreq=true&text=$text&alert=1";	
				file_get_contents($url);
			    mysqli_free_result($query);   
				mysqli_next_result($this->con);	
	
	}

	public function signup(){		
		$userEmailIdLog = $_REQUEST['userEmailIdLog'];
		$username = $_POST['userName'];
		$mobileNo = $_REQUEST['mobileNo'];
		$landNo = $_POST['landNo'];
		$city = $_POST['city'];
		$address = $_POST['adds'];
		$email = $_REQUEST['emailId'];
		$altEmail = $_POST['altEmails'];
		$compName = $_POST['companyName'];
		$userMob = $_POST['userMobile'];
		$UserLan = $_POST['UserLandline'];
		$userEamil = $_POST['userEmails'];
		$altUserEmail = $_POST['altUserEmails'];
		$userAdds = $_POST['userAddress'];
		$userCity = $_POST['usersCity'];
		$userPin = $_POST['userPins'];
		$image_path=$_POST['image'];
		$userpass = md5($_POST['userpassword']);
		$userRole = $_REQUEST['userRoles'];

        $country = $_POST['country'];
		$mob_isd = $_POST['mob_isd'];
		$altmob_no = $_POST['altmob_no'];
		$land_std = $_POST['land_std'];
		$pincode = $_POST['pincode'];
		$cmpany_country=$_POST['cmpany_country'];
		$cmp_mob_isd = md5($_POST['cmp_mob_isd']);
		$cmp_lan_std = $_POST['cmp_lan_std'];
		$referBy = $_POST['ReferralKey'];
		$userLang = $_POST['userLang'];
		
		$UserNo=$mobileNo;
		//$act=md5($email.time());
		$alphabet = '1234567890';
		$pcode = array(); //remember to declare $pcode as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
			for ($i = 0; $i < 5; $i++) {
				$n = rand(0, $alphaLength);
				$pcode[] = $alphabet[$n];
			}
		$act= implode($pcode); //turn the array into a string
		if($userEmailIdLog==$mobileNo){
			$UserNo=$email;
		}
		if($userEmailIdLog==$email){
			$UserNo=$mobileNo;
		}
		
		$sql = "CALL wp_signup('$email','$username','$mobileNo','$landNo','$city','$address','$email','$altEmail','$compName','$userMob',
		'$UserLan','$userEamil','$altUserEmail','$userAdds','$userCity','$userPin','$image_path','$userpass','$userRole','$UserNo','$act','$userLang')";
		//die;
		$result = mysqli_query($this->con, $sql) or die(mysqli_error($this->con));
		$data = mysqli_fetch_assoc($result);
		  mysqli_free_result($result);
		  mysqli_next_result($this->con);
		$data2=$data;
		if($data2['afectactive']>0){
		   $id = $data2['lastusersid'];
           $com_id = $data2['lastcompanyid'];			
			$referKey=substr($username,0,3).$id;
			$sql2 = "update tbluser set referralKey='$referKey' where ID='$id'";
		    $result3 = mysqli_query($this->con, $sql2) or die(mysqli_error($this->con));
			if($referBy!=''){
						$query = "SELECT `installation_amount_user`,installation_amount_referer FROM `tbl_referral_discount_amount`
						INNER JOIN `tbluser` on `tbluser`.UserType=`tbl_referral_discount_amount`.user_type 
						WHERE tbluser.referralKey ='$referBy'";
						$result9 = mysqli_query($this->con,$query);						
						if(mysqli_num_rows($result9)>0){
							$row=mysqli_fetch_assoc($result9);
						    $userAmount=$row["installation_amount_user"];
						    $referer_amount=$row["installation_amount_referer"];
							$query = "INSERT INTO tbl_referral_signup_history SET user_id='$id',referer_id='$referBy',user_amount='$userAmount',referer_amount='$referer_amount'";
							$result = mysqli_query($this->con,$query);
							mysqli_free_result($result);
							mysqli_next_result($this->con);
							$query1 = "UPDATE tbluser set amount='$userAmount',refer_By='$referBy' where id='$id'";
							$result1 = mysqli_query($this->con,$query1);
							mysqli_free_result($result1);
							mysqli_next_result($this->con);
							$query2 = "select amount from tbluser where referralKey='$referBy'";
							$result2 = mysqli_query($this->con,$query2);
							$row1=mysqli_fetch_assoc($result2);
						    $previosAmount=$row1["amount"];
							$updated_Amount=$previosAmount+$referer_amount;
							mysqli_free_result($result2);
							mysqli_next_result($this->con);
							$query3 = "UPDATE tbluser set amount='$updated_Amount' where referralKey='$referBy'";
							$result3 = mysqli_query($this->con,$query3);
							mysqli_free_result($result3);
							mysqli_next_result($this->con);							
						}
					} 
				$com_query = "UPDATE tbluserinfo set CompanyID='$com_id' where UID='$id'";
				$com_result = mysqli_query($this->con,$com_query);
				mysqli_free_result($com_result);
				mysqli_next_result($this->con);
			//$mail = $this->mailing($email,$act);
			$activation='test';
			$from='info@hello42cab.com';
			$subject='Hello42 Activation Code';
			$body = 'Hi User,'.'<br><br>'.
			'Activation Code = '.$act.'<br><br>'.
			'Please click the link below to activate your accout and Enter the above code to activate.'.'<br><br>'.'Best Regards,<br><br><a href="'.site_url.'/verify">'.site_url.'/verify</a><br><br>Hello42 Cab Team<br><br>';
			//$body ='Hi User,'.'<br><br>'.'Please enter the below given code in the browser for account verification'.'<br><br>'.$activation.'<br><br>'.'Best Regards,<br><br>Hello42 Cab Team';

			//$this->send_mail($email,$from,$subject,$body);
			//$url = "http://push1.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$mobileNo&from=Helo42cab&dlrreq=true&text=Thanks+for+choosing+HELLO42+CABS.+Your+Verification+Code+for+Activation+".$act."+or+For+Online+bkg+www.hello42cabs.com+from+Hello42cab&alert=1";

			
			$res = mysqli_query($this->con,"SELECT * FROM tbl_sms_template WHERE msg_sku='user_regis'");
		    $msg_query=mysqli_fetch_array($res); 
			$array=explode('<variable>',$msg_query['message']);
			$array[0]=$array[0].$username;
			$array[1]=$array[1].$act;
			$text=  urlencode(implode("",$array));	
			file_put_contents("mssg.txt",$text);
			$url="http://push3.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$mobileNo&from=Helocb&dlrreq=true&text=$text&alert=1";
			file_get_contents($url);
			mysqli_free_result($res);   
			mysqli_next_result($this->con);
			
			$getmap = file_get_contents($url);
			mysqli_free_result($result);   
			mysqli_next_result($this->con);
			/*     =======================hello Money query===============================*/
			$result = mysqli_query($this->con,"select * FROM tblstates JOIN tbl_hellomoney ON tblstates.id=tbl_hellomoney.stateId;") or die(mysqli_error($this->con));
			$data = mysqli_fetch_array($result);
			$Amount = $data['reg_money'];			
			mkdir("public/userimage/$id/",0777,true);
			$result = mysqli_query($this->con,"INSERT INTO tbl_user_transaction VALUES('$id',NOW(),'','$Amount','','credited','','register' )") or die(mysqli_error($this->con));
			$meta=array("message"=>"Success");	
			return array("Status" => $meta,"Code"=>"001");
		}else{
			$meta=array("message"=>"UnSuccess");	
			return array("Status" => $meta,"Code"=>"002","data"=>$data2);   
		}
	}
        
	public function login(){
		
		if($_REQUEST['DeviceType']=="Android" && $_REQUEST['UserType'] != ''){
			$UserType = $_REQUEST['UserType'];
			$username = $_REQUEST['userName'];		    
			$token=md5(uniqid().$username);
		   $userpass = md5($_REQUEST['userPass']);
		   $reg_id=isset($_POST['gcm_id'])?$_REQUEST['gcm_id']:''; 
		   $query=("SELECT * FROM tbluser WHERE (`LoginName`='$username' OR `UserNo`='$username') AND UserType='$UserType'"); //die;
		   $fetch = mysqli_query($this->con,$query)or die(mysqli_error($this->con));
		   $countrow= mysqli_num_rows($fetch);
		}elseif($_REQUEST['DeviceType']=="Web"){
			$username = $_REQUEST['userName'];					
			$token=md5(uniqid().$username);
			$userpass = md5($_REQUEST['userPass']);
			$reg_id=isset($_POST['gcm_id'])?$_REQUEST['gcm_id']:''; 
			$query=("SELECT * FROM tbluser WHERE `LoginName`='$username' OR `UserNo`='$username'");
			$fetch = mysqli_query($this->con,$query)or die(mysqli_error($this->con));
			$countrow= mysqli_num_rows($fetch);
		}
		if($countrow >0)
		{
			$query22=("SELECT * FROM tbluser WHERE (`LoginName`='$username' OR `UserNo`='$username') AND `isVerified`='1'" );
			$fetch22 = mysqli_query($this->con,$query22)or die(mysqli_error($this->con));			
			$countrow22= mysqli_num_rows($fetch22);	
			$userDT= mysqli_fetch_assoc($fetch);
			if($countrow22 >0){			
			if($userDT["Password"]==$userpass){
				$sql = "CALL wp_login('$username','$userpass','$reg_id','$token')";
				$result = mysqli_query($this->con,$sql)or die(mysqli_error($this->con));
				$data= mysqli_fetch_assoc($result);
				//print_r($data);//exit;
				//if($data['checklogin'] == 1 and $data['login_state']==0 ){
					if($data['checklogin'] == 1){
					//echo 'Mohit'; 
					//echo $id = $data['ID']; die;
			/// This code is using job assigned driver ////
			/// Comment using on update in table tbl_driver_points Starts Here ///
				//$sql_driver_points="UPDATE tbl_driver_points SET `login_status`='1',login_time=NOW() WHERE `UID`='$id'";
				//mysqli_query($this->con,$sql_driver_points)or die(mysqli_error($this->con));
			/// Comment using on update in table tbl_driver_points Ends Here ///
					$user_session = new Container('user');
                    foreach($data as $u=>$v)$user_session->offsetSet($u,$v);					
					return array("UserData" => array($data),"status" => "true");
				  }else{
					if($data['checklogin'] == 1 and $data['login_state']==1)
					{
						return array("status" => "Unsuccess","data"=>"Already Logged In".$data['login_state'],"ErrorCode"=>'102');
					}else{
						return array("status" => "Unsuccess","data"=>"Invalid Email or Password","ErrorCode"=>'101');
						} 
				   }				
			}else{
				return array("status" => "Unsuccess","data"=>"Invalid Password","ErrorCode"=>'104');
			}
		}else{
			$UID=$userDT['ID'];
			$mobile_no=$userDT['UserNo'];
			$sql1="SELECT Verification_code FROM tblactivation WHERE UID='$UID' AND isUsed=0";
			$result1 = mysqli_query($this->con,$sql1);
			$res1=mysqli_fetch_object($result1);
			$otp=$res1->Verification_code; //die;

			$sql2="SELECT FirstName,LastName FROM tbluserinfo WHERE UID='$UID'";
			$result2 = mysqli_query($this->con,$sql2);
			$res2=mysqli_fetch_object($result2);
			$driverName=$res2->FirstName.' '.$res2->LastName;

			$code="SELECT * FROM tbl_sms_template WHERE msg_sku='acc_verify_code'";
			$res_code = mysqli_query($this->con,$code);
			$msg_query_code=mysqli_fetch_array($res_code); 
			$array1=explode('<variable>',$msg_query_code['message']);
			$array1[0]=$array1[0].$driverName;
			$array1[1]=$array1[1].$otp;
			$array1[2]='http://hello42cab.com';
			$text=  urlencode(implode("",$array1));	
			$url="http://push3.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$mobile_no&from=Helocb&dlrreq=true&text=$text&alert=1";
			file_get_contents($url);
			//mysqli_free_result($res_code);   
			//mysqli_next_result($this->con);
			return array("status" => "Unsuccess","data"=>"Account not Verified","mobile_no"=>$mobile_no,"ErrorCode"=>'105');
		}
		}else{
			return array("status" => "Unsuccess","data"=>"Invalid Email or Mobile No.","ErrorCode"=>'103');
		}
	}	
	
	public function forgetpassword(){
		$email=$_REQUEST['email']; 
		//$email="9015966232";
		$token = md5(uniqid(rand(),1));
		$result = mysqli_query($this->con,"CALL wp_forgetpassword('$email','$token')");
		$data = mysqli_fetch_assoc($result);
		mysqli_free_result($result);
		 mysqli_next_result($this->con);
		if($data['id_count']>0){
			
			if (strpos($email, '@') !== false) {			
			$body="Hi User,<br><br>Please click the below link to reset the password<br><br>"
			. "<a href='http://" . $_SERVER['HTTP_HOST']. $_SERVER['PHP_SELF']."login/index/resetpassword?id=$token'>Click Here</a>"
			. "<br><br>Best Regards,<br>Hello42 Cab Team";
			$body=str_replace('index.php','',$body);
			$from='info@hello42cab.com';
			$subject='Recover Password';
			//$this->send_mail($email,$from,$subject,$body);
			}else{
			$link="http://" . $_SERVER['HTTP_HOST']. $_SERVER['PHP_SELF']."login/index/resetpassword?id=$token";	
			/// Code for SMS Going to the particular user For Forget Password Starts here ////
			$sql="SELECT * FROM tbl_sms_template WHERE msg_sku='forgot_password'";
         	$res = mysqli_query($this->con,$sql);
		   	$msg_query=mysqli_fetch_array($res); 
			$array=explode('<variable>',$msg_query['message']);
			$array[0]=$array[0].$email;
			$array[1]=$array[1].$link;
			$text=  rawurlencode(implode("",$array));	
			file_put_contents("mssg.txt",$text);
			$url="http://push3.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=$email&from=Helocb&dlrreq=true&text=$text&alert=1";
			file_get_contents($url); 
			/// Code for SMS Going to the particular user For Forget Password Ends here ////
			
			}
			return array("status"=>"true");
		}
		else{
			return array("status"=>"false","ErrorCode"=>'105');   
		}
	}

	public function resetpassword(){
		$newpass=md5($_POST['newpass']);
		$cpass=md5($_POST['cpass']);
		if((strlen($_POST['newpass'])>=6) and ($newpass==$cpass) ){
			$code=$_POST['code'];  
			$result = mysqli_query($this->con,"CALL wp_resetpassword('$newpass','$cpass','$code')");
			$data = mysqli_fetch_array($result);
			if($data['id_user']==0){
				return array('status'=>'false',"ErrorCode"=>"1");
			}else{
				return array('status'=>'true',"ErrorCode"=>"0");
			}
		}else{
			return array('status'=>'false',"ErrorCode"=>'104');
		}
	}
	
	public function changepassword(){
		
		$user_token = $_REQUEST['token'];
		
		$sqlquery="SELECT `ID` from `tbluser` WHERE `token` = '$user_token' ";
		$data=mysqli_fetch_array(mysqli_query($this->con,$sqlquery));
		$id = $data['ID'];
		
		$old=md5($_REQUEST['oldpass']);
		$new=md5($_REQUEST['newpass']);
		$new2=$_REQUEST['newpass'];
		$cpass=md5($_REQUEST['cpass']);
		
		$sql="SELECT Password from tbluser WHERE id='$id' and Password='$old' ";
		$result=mysqli_num_rows(mysqli_query($this->con,$sql));
		if($result==1)
		{
			if(strlen($new2)>5)
			{
				if($new==$cpass)
				{
					$query="UPDATE tbluser SET Password='$new' WHERE id='$id'";
					mysqli_query($this->con, $query);
					if(mysqli_affected_rows($this->con)>0)
					{
						return array("status"=>"true","message"=>"Successfully Updated","ErrorCode"=>"0");
					}else
					{
						return array("status"=>"false","message"=>"Old Password and New Password are Same","ErrorCode"=>"1");
					}
				 
				}else
				{
					return array("status"=>"false","message"=>"Password Not Match","ErrorCode"=>"2");
				}
				
			}else
			{
				return array("status"=>"false","message"=>"Password must be atleast 6 character long","ErrorCode"=>"3"); 
			}
		}else
		{
			return array("status"=>"false","message"=>"Old Password is Invalid","ErrorCode"=>"4");
		}
		
	}
	 public function CreateOTP($length = 8, $chars = '0123456789') 
		{ 
		$chars_length = (strlen($chars) - 1); 
		$string = $chars{rand(0, $chars_length)}; 
		for ($i = 1; $i < $length; $i = strlen($string)) 
			{ 
			$r = $chars{rand(0, $chars_length)}; 
			if ($r != $string{$i - 1}) $string .= $r; 
			} 
		return $string;
		} 

	public function SendOTPUserAndroid()
	{  
		//  $user_token = trim($_REQUEST['token']); 
			$user_token = "369c34a0f8921f44029bb09b95bd5a46";
		  $sqlquery="SELECT * from `tbluser` WHERE `token` = '$user_token'";
		  //return array("query"=>$sqlquery);
		  $data=mysqli_num_rows(mysqli_query($this->con,$sqlquery));
		  if($data > 0){
		   $rowed=mysqli_fetch_object(mysqli_query($this->con,$sqlquery));
		   $userId=$rowed->ID;
		   $userEmail=$rowed->LoginName;
		   $UserNo=$rowed->UserNo;
		   $UserNo = "9015230173";
		   $otp=$this->CreateOTP();
		   $QUERY2="UPDATE `tbluser` SET OTP='$otp' WHERE `token` = '$user_token' AND ID='$userId'";
			   $updated=mysqli_query($this->con,$QUERY2);
			if($updated)
			{
			 $body="Hi User,<br><br>Here is Your One Time Password to reset the password.<br><br>"
			 . "<strong>" . $otp."</strong><br><br> Please Enter this Password to change your password.<br><br>"
			 . "<br><br>Best Regards,<br>Hello42 Cab Team";     
			 $from='info@hello42cab.com';
			 $subject='One Time Password';
			 if($this->send_mail($userEmail,$from,$subject,$body) == true){
				 echo "RR";
					$query=mysqli_query($this->con,"SELECT * FROM tbl_sms_template WHERE msg_sku='registration'");
			       $msg_query=mysqli_fetch_array($query);		
					$array=explode('<variable>',$msg_query['message']);
							$array[0]=$array[0].$otp;							
							$array[1]=$array[1]."http://bookingcabs.com/";
							$text=  urlencode(implode("",$array));
				$url= "http://push1.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$UserNo&from=Helocb&dlrreq=true&text=$text&alert=1";
				file_get_contents($url);
				mysqli_free_result($query);   
				mysqli_next_result($this->con);		      
			  return array("status"=>true,"message"=>"OTP Sent Successfully on Email");
			   exit;
			 }else{
				  $query=mysqli_query($this->con,"SELECT * FROM tbl_sms_template WHERE msg_sku='registration'");
			       $msg_query=mysqli_fetch_array($query);
					$array=explode('<variable>',$msg_query['message']);
							$array[0]=$array[0].$otp;							
							$array[1]=$array[1]."http://bookingcabs.com/";
							$text=  urlencode(implode("",$array));
							//echo $text;exit;
						$url = "http://push1.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$UserNo&from=Helocb&dlrreq=true&text=$text&alert=1";	
				file_get_contents($url);
			    mysqli_free_result($query);   
				mysqli_next_result($this->con);	
			    //$this->SendSMS($UserNo,$otp);
			  return array("status"=>true,"message"=>"OTP Sent Successfully on Mobile");
			  exit;
			 }
			}else{
			 return array("status"=>false,"message"=>"Invalid Token update");
			}
		  }else{
		   return array("status"=>false,"message"=>"Invalid Token");
		  }
	}
	public function ChangePasswordUserAndroid()
	{		
		$user_token = trim($_REQUEST['token']);
         $cpOTP = trim($_REQUEST['cpOTP']);		
		$sqlquery="SELECT * from `tbluser` WHERE `token` = '$user_token' ";
		$data=mysqli_fetch_array(mysqli_query($this->con,$sqlquery));
		$id = $data['ID'];
		$userEmail=$data['LoginName'];
		$dbOTP = $data['OTP'];
		if($cpOTP == $dbOTP)
		{			
			$new=md5($_REQUEST['newpass']);
			$new2=$_REQUEST['newpass'];
			$cpass=md5($_REQUEST['cpass']);
				if(strlen($new2)>5)
				{
					if($new==$cpass)
					{
						$query="UPDATE tbluser SET Password='$new',OTP='' WHERE `ID`='$id' AND `token` = '$user_token'";
						mysqli_query($this->con, $query);
						if(mysqli_affected_rows($this->con)>0)
						{
							$body="Hi User,<br><br>Your password has been changed successfully.<br><br>"
							."Here is Your new Password.<br><br>"
							. "<strong>" . $new2."</strong> Please Enter this Password to Login Your Account.<br><br>"
							. "<br><br>Best Regards,<br>Hello42 Cab Team";					
							$from='info@hello42cab.com';
							$subject='New Password';
							$this->send_mail($userEmail,$from,$subject,$body);
							return array("status"=>"true","message"=>"Successfully Updated","ErrorCode"=>"0");
						}else
						{
							return array("status"=>"false","message"=>"Old Password and New Password are Same","ErrorCode"=>"1");
						}				 
					}else   
					{
						return array("status"=>"false","message"=>"New Password And Confirm Password Not Matched","ErrorCode"=>"2");
					}				
				}else
				{
					return array("status"=>"false","message"=>"Password must be atleast 6 character long","ErrorCode"=>"3"); 
				}			
		}else
		{
			return array("status"=>"false","message"=>"Your OTP is Invalid","ErrorCode"=>"4");
		}				
	}		
	function booking_location(){
		$data= $_REQUEST['data'];
		$data_new=json_decode($data,true);
		foreach($data_new['upload'] as $v=>$s){
			$total_time=$data_new['upload'][$v]['total_time'];
			$id=$data_new['upload'][$v]['id'];
			$distance=$data_new['upload'][$v]['distance'];
			$from=$data_new['upload'][$v]['from_latitude'].",".$data_new['upload'][$v]['from_longitude'];
			$to=$data_new['upload'][$v]['to_latitude'].",".$data_new['upload'][$v]['to_longitude'];
			$booking_id=$data_new['upload'][$v]['booking_id'];
			$current=$data_new['upload'][$v]['current_time'];			
			$query=  mysqli_query($this->con,"CALL sp_booking_distance('$id','$booking_id','$from','$to','$current','$distance','$WaitingTime')");
			//mysqli_free_result($this->con,$query);   
			mysqli_next_result($this->con);
		}
		return array("status"=>"true");
	}

	function calculateBillForBooking($distance,$BookingId_i,$vehicleId,$datam){
		$con=new mysqli("10.0.0.35","root","Travel@(4242)","hello42_new");	
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
        
	public function nearest_distance(){
		$lat = $_REQUEST['lat'];
		$lang = $_REQUEST['lang'];
		$result = mysqli_query($this->con,"CALL wp_user_distance()") or die(mysqli_error($this->con));
		$data = mysqli_fetch_assoc($result);
		$latlong = $this->distanceuserdiver($lat,$lang,$data['Latitude'],$data['Longtitude1'],"K");
		return array("data"=>$latlong,"status"=>'false');
	}
        
        
	public function distanceuserdiver($lat1, $lon1, $lat2, $lon2, $unit) {
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);
		if($unit == "K") {
			return ($miles * 1.609344);
		}elseif ($unit == "N") {
			return ($miles * 0.8684);
		}else{
			return $miles;
		}
	}
      
	public function me2(){
		echo $_SESSION['id'];
	}
        
	public function removeSqlInjection($data){
		 $arr = array("%","'","=","*");
		 $res = str_replace($arr,"/",$data);
		 return $res;
	}
		
	public function start_trip(){
		$id=$_REQUEST['id'];
		$book_id=$_REQUEST['booking_id'];	
        $BookingId=$book_id;		
		$query=mysqli_query($this->con,"CALL wp_start_trip('$book_id','$id')") or die(mysqli_error($this->con));			
		mysqli_free_result($query);
		mysqli_next_result($this->con);
		$this->send_sms_new2($BookingId,$flag="trip_start");
		
		/* $sql_res = "select t1.booking_reference, t2.FirstName from tblcabbooking t1 inner join tbluserinfo t2 on t1.ClientID = t2.UID where t1.ID='$book_id';";
		$qry_res = mysqli_query($this->con,$sql_res);
		$row_res = mysqli_fetch_object($qry_res);
		$booking_reference = $row_res->booking_reference;
		$FirstName = $row_res->FirstName;
		mysqli_free_result($qry_res);
		mysqli_next_result($this->con);
		
		
		$res = mysqli_query($this->con,"SELECT * FROM tbl_sms_template WHERE msg_sku='trip_start'");
		$msg_query=mysqli_fetch_array($res); 
		$array=explode('<variable>',$msg_query['message']);
		$array[0]=$array[0].$FirstName;
		$array[1]=$array[1].$booking_reference;
		$array[2]=$array[2]."http://bookingcabs.com/";
		$text=  urlencode(implode("",$array));	
		//file_put_contents("mssg.txt",$text);
		$url="http://push1.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$mobileNo&from=Helocb&dlrreq=true&text=$text&alert=1";
		file_get_contents($url);
		mysqli_free_result($res);   
		mysqli_next_result($this->con); */
		
		return array("status"=>"true");		
	}
	
	// Reject booking by driver app
	public function reject_booking(){
		$id=$_REQUEST['id'];
		$booking_id=$_REQUEST['booking_id'];
		mysqli_query($this->con,"CALL wp_reject_booking('$id','$booking_id')");
		return array("status"=>"true");
	}
		
	public function send_msg(){
		//$id=$_REQUEST['id'];
		//$booking=$_REQUEST['booking_id'];
		//$msg=$_REQUEST['msg'];
		
		$id = 1411;
		$booking = 4824;
		$msg = 'Hello Testing msg' ;
		
		$result=mysqli_query($this->con,"CALL wp_send_message('$id','$booking','$msg')") or die(mysqli_error($this->con));
		$result2=mysqli_fetch_array($result[0]);
		$this->send_notification(array($result2),array("msg"=>$msg,"type"=>"msg"));
	}
	
	// Cancel Booking by driver app after accept booking	
	public function cancel_booking_driver(){
		$driver_id=$_REQUEST['id'];
		$booking_id=$_POST['booking_id'];
		$BookingId = $booking_id;
		$reason=$_REQUEST['reason'];
		mysqli_query($this->con,"UPDATE tblcabbooking SET status='10' WHERE id='$booking_id' and pickup='$driver_id' ")or mysqli_error($this->con);
		mysqli_query($this->con,"UPDATE tbldriver SET status='0' WHERE UID='$driver_id' ")or mysqli_error($this->con);
		$sql = "SELECT type FROM tblbookingregister WHERE bookingid = '$booking_id' and type!=''";
		$qry = mysqli_query($this->con,$sql);
		$row = mysqli_fetch_object($qry);
		$qry96=mysqli_query($this->con,"INSERT INTO tblbookingregister(bookingid,driverId,updateOn,reason,`status`,`type`) VALUES('$booking_id','$driver_id',NOW(),'$reason','C','".$row->type."')");
		mysqli_free_result($qry96);
		mysqli_next_result($this->con);
		$this->send_sms_new2($BookingId,$flag="cancel");
		
		/* $sql_res = "select t1.booking_reference, t1.UserName from tblcabbooking t1 inner join tbluserinfo t2 on t1.ClientID = t2.UID where t1.ID='$booking_id';";
		$qry_res = mysqli_query($this->con,$sql_res);
		$row_res = mysqli_fetch_object($qry_res);
		$booking_reference = $row_res->booking_reference;
		$UserName = $row_res->UserName;
		mysqli_free_result($qry_res);
		mysqli_next_result($this->con);
		
		
		$res = mysqli_query($this->con,"SELECT * FROM tbl_sms_template WHERE msg_sku='cancel'");
		$msg_query=mysqli_fetch_array($res); 
		$array=explode('<variable>',$msg_query['message']);
		$array[0]=$array[0].$UserName;
		$array[1]=$array[1].$booking_reference;
		$array[2]=$array[2]."http://bookingcabs.com/";
		$text=  urlencode(implode("",$array));	
		//file_put_contents("mssg.txt",$text);
		$url="http://push1.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$mobileNo&from=Helocb&dlrreq=true&text=$text&alert=1";
		file_get_contents($url);
		mysqli_free_result($res);   
		mysqli_next_result($this->con); */
		
		return array("data"=>array("status"=>"success"));	
	}

	public function logout(){
		$sql = "CALL sp_mohit_logout('".$_REQUEST['login_id']."','".$_REQUEST['token']."')";// die;
		$qry = mysqli_query($this->con,$sql)or die(mysqli_error($this->con));
		if($qry){	
			/// This code is using job assigned driver Starts here ////
			$id=$_REQUEST['login_id'];
			
			/// Comment using on update in table tbl_driver_points Starts Here ///
			
			//$sql_driver_points="UPDATE tbl_driver_points SET `login_status`=0,logout_time=NOW() WHERE `UID`='$id'";
			//mysqli_query($this->con,$sql_driver_points)or die(mysqli_error($this->con));
			
			/// Comment using on update in table tbl_driver_points Ends Here ///
			
			/// This code is using job assigned driver Ends Here ////	
			$user_session = new Container('user');
            $user_session->getManager()->getStorage()->clear('user');
				   if (isset($_SERVER['HTTP_COOKIE']))
				   {
						$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
						foreach($cookies as $cookie) {
							$parts = explode('=', $cookie);
							$name = trim($parts[0]);
							setcookie($name, '', time()-1000);
							setcookie($name, '', time()-1000, '/');
						}
				   }				    
			return array('status'=>"true");
		}
		return array('status'=>"false");
	}
	public function logout_user_App_Uninstall(){
		$username=$_REQUEST["login_id"];
		 $query="SELECT * FROM tbluser WHERE (`LoginName`='$username' OR `UserNo`='$username')"; //die;
		   $fetch = mysqli_query($this->con,$query)or die(mysqli_error($this->con));
		   $countrow= mysqli_num_rows($fetch);
		   if($countrow>0){
			   $userData= mysqli_fetch_assoc($fetch);
			   $sql = "CALL sp_mohit_logout('".$userData['ID']."','".$userData['token']."')";// die;
				$qry = mysqli_query($this->con,$sql)or die(mysqli_error($this->con));
				if($qry){		    
					return array('status'=>"true");
				}else{
					return array('status'=>"false","msg"=>"Query Failed");
				}				
		   }else{
			   return array('status'=>"false","msg"=>"Invalid Email or Mobile No");
		   }		
	}
	
	public function UserRatingfrmbackend()
	{	
		//$adminsess = new Container('admin');
		//$adminId = $adminsess->offsetGet('id');
		$adminId	=	$_POST['agent_id'];
		$BookingId = $_POST['BookingId'];
		$UserRating = $_POST['UserRating'];
		$created_by = 'admin';

		$_REQUEST = array("BookingId"=>$BookingId,"UserRating"=>$UserRating,"adminId"=>$adminId,"created_by"=>$created_by);
		// $res = $this->UserRating($BookingId,$UserRating,$adminId,$created_by);
		$res = $this->DriverRatingService($_REQUEST);
		print_r($res);
	}

	public function UserRating($BookingId='',$UserRating='',$adminId='',$created_by=''){
		$sql = "SELECT * FROM tblcabbooking WHERE ID = '$BookingId'";
		$qry = mysqli_query($this->con,$sql);
		$row = mysqli_fetch_object($qry);

		$row->pickup = ($adminId !='')? $adminId :$row->pickup; 
		$created_by = ($created_by !='')? "admin" : "user"; 
	   $sql = "INSERT INTO tbluserrating (BookingId, DriverId, UserId, Rating ,created_by) VALUES 
		('".$row->ID."', '".$row->pickup."', '".$row->ClientID."', '".$UserRating."','".$created_by."')";
		$qry = mysqli_query($this->con, $sql);
		return true;
	}	
	
	
	/* public function chal(){
			
			$data = 'Bhagendra Singh';
			include('./template/test.php');
			ob_start();
			$body = ob_get_contents();
			print $body; 
			ob_clean();
			die();

	} */
	
	public function cash_button_old(){
		//if($_REQUEST['user_rating']!='' && $_REQUEST['booking_id']!=''){
			//exit($_REQUEST['booking_id']."-----".$_REQUEST['user_rating']);
			//$this->UserRating($_REQUEST['booking_id'],$_REQUEST['user_rating']);
		//}
		/* $Driverid=$_REQUEST['id'];             
		$type=$_REQUEST['type'];    
		$booking=$_REQUEST['booking_id'];     
		$road_tax=$_REQUEST['road_tax'];
		$toll_tax=$_REQUEST['toolTax'];                              
		$amount=$_REQUEST['totalAmount'];     
		$other_tax=$_REQUEST['other_tax']; */
		
		$Driverid='2130';             
		$type='cash';   
		$booking='7694';     
		$road_tax='10';
		$toll_tax='20';                              
		$amount='200';     
		$other_tax='10';
		
		file_put_contents("amount.txt", $amount);
		$row=mysqli_query($this->con,"CALL wp_driver_cash('$booking','$Driverid','$type','$amount','$toll_tax','$road_tax','$other_tax')")or die(mysqli_error($this->con));
		$result=mysqli_fetch_assoc($row);		
		/*****************payment mail to user start***********/
		//**********Fetch Booking Details******	
	   $sql = "SELECT * FROM tblcabbooking WHERE ID = '$booking'";
		 $qry = mysqli_query($this->con,$sql);
		$row = mysqli_fetch_object($qry);
		$user_ID=$row->ClientID;          
		$Drvr_ID=$row->pickup;                    
		$EstimatedDistance=$row->EstimatedDistance;
		$EmailId=$row->EmailId;           
		$UserName=$row->UserName;                 
		$EstimatedTime=$row->EstimatedTime;
		$MobileNo=$row->MobileNo;         
		$PickupAddress=$row->PickupAddress;       
		$ReturnDate=$row->ReturnDate; 
		$DropAddress=$row->DropAddress;   
		$BookingDate=$row->BookingDate;           
		$PickupCity=$row->PickupCity;
		$PickupDate=$row->PickupDate;     
		$PickupTime=$row->PickupTime;             
		$DestinationCity=$row->DestinationCity;
		$waiting_charge=$row->approx_waiting_charge; 				
		$paid_amount=$amount;
		$due_balance=$amount-$paid_amount;
		//*********Fetch Driver Info***********
		$query = "SELECT * FROM tbluserinfo WHERE UID = '$Drvr_ID'";
		$qry11 = mysqli_query($this->con,$query);
		$row111 = mysqli_fetch_object($qry11);
		$Driveremail=$row111->Email;
		$DriverFirstName=$row111->FirstName;
		$DriverLastName=$row111->LastName;
		$DriverMobNo=$row111->MobNo;
		//*********Fetch Vehicle Details*****************
		$fetch = "select tblcabmaster.* from tblcabbooking
		inner join tbldriver on tblcabbooking.pickup = tbldriver.UID
		inner join tblcabmaster on tbldriver.vehicleId = tblcabmaster.CabId
		where tblcabbooking.ID = '$booking'";
		$fetched = mysqli_query($this->con,$fetch);
		$result123 = mysqli_fetch_object($fetched);
		$vehicle_number=$result123->CabRegistrationNumber;
		$vehicle_name=$result123->CabName;
		$CabType=$result123->CabType;
		$quer = "SELECT * FROM tblcabtype WHERE Id = '$CabType'";
		$qry6 = mysqli_query($this->con,$quer);
		$cabyu = mysqli_fetch_object($qry6);
		$vechile_type=$cabyu->CabType;
		//*********Fetch User Info***********
		$query1 = "SELECT * FROM tbluserinfo WHERE UID = '$user_ID'";
		$qry96 = mysqli_query($this->con,$query1);
		$rowpp = mysqli_fetch_object($qry96);
		$userEml=$rowpp->Email;
		$usrFirstN=$rowpp->FirstName;
		$urLastName=$rowpp->LastName;
		$usrMobNo=$rowpp->MobNo;
		$usrCoty=$rowpp->Country;
		$userState=$rowpp->State;
		$userCity=$rowpp->City;
		$usAddrs1=$rowpp->Address1;
		$userAdd2=$rowpp->Address2;
		$usrPine=$rowpp->PinCode;
		mysqli_free_result($qry96);
		mysqli_next_result($this->con);
		//$this->send_sms_new1($booking,$flag="F");		
		//***********Mail****************************	
		$night_charges='';
		/* $search=array('{user_name}','{user_address}','{user_city}','{user_state}','{country}','{user_email}','{user_mobile}','{driver_name}',
		'{vehicle_number}','{driver_mobile}','{vehicle_name}','{driver_email}','{vehicle_type}','{booking_date}','{return_date}',
		'{pickup_address}','{pickup_date}','{pickup_time}','{pickup_city}','{drop_address}','{drop_city}','{estimated_distance}',
		'{estimated_time}','{waiting_charges}','{night_charges}','{road_tax}','{tool_tax}','{other_charges}','{total_fare}','{amount_paid}','{due_amount}');
		$replace=array($UserName,$usAddrs1.$userAdd2,$userCity,$userState,$usrCoty,$userEml,$usrMobNo,$DriverFirstName.$DriverLastName,
		$vehicle_number,$DriverMobNo,$vehicle_name,$Driveremail,$vechile_type,$BookingDate,$ReturnDate,
		$PickupAddress,$PickupDate,$PickupTime,$PickupCity,$DropAddress,$DestinationCity,$EstimatedDistance,
		$EstimatedTime,$waiting_charge,$night_charges,$road_tax,$toll_tax,$other_tax,$amount,$amount,$amount);	
		$template=str_replace($search,$replace,$body);  */			
		//$UserName = 'Maninder';		
		$template = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xmlns='http://www.w3.org/1999/xhtml'>
  <head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
  </head>
  <body style='font-style: normal; font-variant: normal; font-weight: normal; font-size: 14px; line-height: 1.4; font-family: Georgia, serif;'><style type='text/css'>
textarea:hover { background-color: #EEFF88 !important; }
textarea:focus { background-color: #EEFF88 !important; }
.itemsqqq td.total-value textarea:hover { background-color: #EEFF88 !important; }
.itemsqqq td.total-value textarea:focus { background-color: #EEFF88 !important; }
.delete:hover { background-color: #EEFF88 !important; }
&gt;</style>&#13;
	<div style='width: 800px; background-color: lightgray; margin: 0 auto;'>&#13;
		<div readonly='readonly' style='text-align: center; height: 15px; width: 100%; color: white; text-decoration: uppercase; letter-spacing: 20px; font-style: normal; font-variant: normal; font-weight: bold; font-size: 15px; line-height: normal; font-family: Helvetica, Sans-Serif; background-color: #222; margin: 20px 0; padding: 8px 0px;' align='center'>INVOICE</div>		&#13;
		<div>		&#13;
            <span style='width: 275px; height: 80px; float: left;'>&#13;
			<strong> $UserName</strong><br />&#13;
			 $usAddrs1.$userAdd2, $userCity<br />			&#13;
			 $userState, $usrCoty<br />&#13;
			 $userEml, $usrMobNo			&#13;
			</span>&#13;
            <div style='text-align: right; float: right; position: relative; margin-right: 85px; max-width: 540px; max-height: 100px; overflow: hidden;' align='right'>&#13;
              <img src='http://166.62.35.117/hello42/public/image/logo.png' alt='logo' /></div>&#13;
		</div>&#13;
		<div style='clear: both;'></div>&#13;
		<span style='font-weight: bold; font-size: 14px; padding-left: 335px;'>Vehicle Details</span>&#13;
		<div style='overflow: hidden;'>           &#13;
            <table style='border-collapse: collapse; margin-top: 1px; width: 799px;'><tr><td style='text-align: left; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Driver Name</td>&#13;
                    <td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $DriverFirstName.$DriverLastName </td>&#13;
					<td style='text-align: left; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Vehicle Number</td>&#13;
                    <td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $vehicle_number</td>&#13;
                </tr><tr><td style='text-align: left; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Driver Mobile No.</td>&#13;
                    <td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $DriverMobNo  </td>                    &#13;
					 <td style='text-align: left; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Vehicle Name</td>&#13;
                    <td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $vehicle_name  </td>&#13;
                </tr><tr><td style='text-align: left; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Driver Email</td>&#13;
                    <td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'>  $Driveremail </td> &#13;
					<td style='text-align: left; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Vehicle Type</td>&#13;
                    <td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $vechile_type </td>&#13;
                </tr></table></div>	&#13;
        <span style='font-weight: bold; font-size: 14px; padding-left: 335px;'>Booking Details</span>&#13;
		<div style='width: 800px;'>&#13;
			<table style='border-collapse: collapse; margin-top: 1px; width: 399px; float: left;'><tr><td style='text-align: left; width: 50%; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Booking Date</td>&#13;
							<td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $BookingDate </td>					&#13;
						</tr><tr><td style='text-align: left; width: 50%; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Return Date</td>&#13;
							<td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $ReturnDate </td>&#13;
						</tr><tr><td style='text-align: left; width: 50%; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Pickup Address</td>&#13;
							<td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $PickupAddress </td>&#13;
						</tr><tr><td style='text-align: left; width: 50%; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Pickup Date</td>&#13;
							<td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $PickupDate </td>&#13;
						</tr><tr><td style='text-align: left; width: 50%; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Pickup Time</td>&#13;
							<td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $PickupTime </td>&#13;
						</tr><tr><td style='text-align: left; width: 50%; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Pickup City</td>&#13;
							<td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $PickupCity </td>&#13;
						</tr><tr><td style='text-align: left; width: 50%; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Drop Address</td>&#13;
							<td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $DropAddress </td>					&#13;
						</tr><tr><td style='text-align: left; width: 50%; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Drop City</td>&#13;
							<td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $DestinationCity </td>					&#13;
						</tr><tr><td style='text-align: left; width: 50%; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Estimated Distance</td>&#13;
							<td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $EstimatedDistance </td>					&#13;
						</tr><tr><td style='text-align: left; width: 50%; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Estimated Time</td>&#13;
							<td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $EstimatedTime </td>&#13;
						</tr></table><table style='border-collapse: collapse; width: 397px; margin-left: 2px; float: left; border: 1px solid black;'><tr><td style='text-align: right; padding: 5px; border-color: black; border-style: solid; border-width: 1px 0 1px 1px;' align='right'>Waiting Charges : </td>&#13;
						  <td style='padding: 10px; border-color: black; border-style: solid; border-width: 1px 1px 1px 0;'><div><i></i>  $waiting_charge </div></td>&#13;
					  </tr><tr><td style='text-align: right; padding: 5px; border-color: black; border-style: solid; border-width: 1px 0 1px 1px;' align='right'>Night Charges : </td>&#13;
						  <td style='padding: 10px; border-color: black; border-style: solid; border-width: 1px 1px 1px 0;'><div><i></i>   $night_charges </div></td>&#13;
					  </tr><tr></tr><tr><td style='text-align: right; padding: 5px; border-color: black; border-style: solid; border-width: 1px 0 1px 1px;' align='right'>Road Tax : </td>&#13;
						  <td style='padding: 10px; border-color: black; border-style: solid; border-width: 1px 1px 1px 0;'><div><i></i>  $road_tax </div></td>&#13;
					  </tr><tr><td style='text-align: right; padding: 5px; border-color: black; border-style: solid; border-width: 1px 0 1px 1px;' align='right'>Toll Tax : </td>&#13;
						  <td style='padding: 10px; border-color: black; border-style: solid; border-width: 1px 1px 1px 0;'><div><i></i>   $toll_tax </div></td>&#13;
					  </tr><tr></tr><tr><td style='text-align: right; padding: 5px; border-color: black; border-style: solid; border-width: 1px 0 1px 1px;' align='right'>Other Charges : </td>&#13;
						  <td style='padding: 10px; border-color: black; border-style: solid; border-width: 1px 1px 1px 0;'><div><i></i>  $other_tax</div></td>&#13;
					  </tr><tr><td style='text-align: right; padding: 5px; border-color: black; border-style: solid; border-width: 1px 0 1px 1px;' align='right'><strong>Total Fare : </strong></td>&#13;
						  <td style='padding: 10px; border-color: black; border-style: solid; border-width: 1px 1px 1px 0;'><div><strong><i></i> $amount</strong></div></td>&#13;
					  </tr><tr><td style='text-align: right; padding: 5px; border-color: black; border-style: solid; border-width: 1px 0 1px 1px;' align='right'><strong>Amount Paid : </strong></td>&#13;
						  <td style='padding: 10px; border-color: black; border-style: solid; border-width: 1px 1px 1px 0;'><span><strong><i></i> $paid_amount</strong></span></td>&#13;
					  </tr><tr><td style='text-align: right; background-color: #eee; padding: 5px; border-color: black; border-style: solid; border-width: 1px 0 1px 1px;' align='right' bgcolor='#eee'><strong>Balance Due : </strong></td>&#13;
						  <td style='background-color: #eee; padding: 10px; border-color: black; border-style: solid; border-width: 1px 1px 1px 0;' bgcolor='#eee'><div><strong><i></i> $due_balance</strong></div></td>&#13;
					  </tr></table></div>&#13;
		<div style='text-align: center; width: 800px; margin-top: 20px;' align='center'>&#13;
			  <h5 style='text-transform: uppercase; font-style: normal; font-weight: normal; padding: 0 0 8px; margin: 0 0 8px; font-family: Helvetica, Sans-Serif; line-height: normal; font-size: 13px; font-variant: normal; letter-spacing: 10px; border-bottom-color: black; border-bottom-style: solid; border-bottom-width: 1px;'> </h5>&#13;
			  &#13;
			  <span><strong>Terms : </strong><small>NET 30 Days. Finance Charge of 1.5% will be made on unpaid balances after 30 days.</small></span>&#13;
		</div>	&#13;
	</div>	&#13;
</body>
</html>";			
		$from='info@hello42cab.com';
		$subject='Hello42 Booking Invoice';
		$body = 'Dear '.$UserName.',<br><br>Here is your payment Invoice.<br/>';	
		$body=$body.$template;			
		//$this->send_mail($EmailId,$from,$subject,$body);
		/*****************End payment mail to user**************/
		return array('status'=>'true',"data"=>array($result));
	}

	public function chat_list(){
		$id=$_REQUEST['id'];
		$result=mysqli_query($this->con,"CALL wp_app_chat_list('$id')")or die(mysqli_error($this->con));
		$data=array();
		while($row=mysqli_fetch_array($result)){
			$data[]=array("bookingid"=>$row['bookingid'],"userid"=>$row["userid"],"name"=>$row["name"]);
		}
		return array('status'=>'true',"data"=>$data);
	}
	
	public function booking_info(){
		$id=$_REQUEST['booking_id'];
		$sql="CALL `wp_booking_info`($id)";
		$hour=mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
		$booking=array();
		while($row=mysqli_fetch_assoc($hour)){
			switch($row['BookingType']){
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
				case 105:
				$booking_type="Intercity";
				break;
				default:
				$booking_type="Point to Point";
				break;
			}
			
			switch($row['CarType']){
				case 1:
				$car_type="Economy";
				break;
				case 2:
				$car_type="Sedan";
				break;
				case 3:
				$car_type="Prime";
				break;
			}
			
			$is_corporate_booking="";
			if($row['is_corportate_booking']==1){
				$is_corporate_booking="True";
			}else{
				$is_corporate_booking="False";
			}
			
			$address_is_added='false';			
		$result=mysqli_query($this->con,"SELECT * FROM tbl_favorite_addresses WHERE Bookign_ID='$id'");
		if(mysqli_num_rows($result)>0){
			$address_is_added='true';
		}else{
			$address_is_added='false';
		}		
			$is_paid='';
			if($row['is_paid']==1){
				$is_paid="True";
			}else{
				$is_paid="False";
			}
			foreach($row as $v=>$k){
				if($row[$v]=='0' or $row[$v]==''){
					$row[$v]="";
				}
			}
			if($row['minimumCharge']==""){
				$row['minimumCharge']=$row['estimated_price'];   
			}
			if($row['actual_distance']==0){
				$row['actual_distance']="0";
			}
			if($row['minimum_distance']==0){
				$row['minimum_distance']=$row['approx_after_km'];
			}
			if($row['distance_rate']==""){
				$row['distance_rate']=$row['approx_distance_charge'];
			}
			$booking[]=array(
				'measure'=>$row['distance_n'],
				'currency'=>$row['currency_n'],
				'waiting_type'=>'Minute',
				'total_time'=>$row['actual_time'],
				'fair_text'=>$row['minimum_distance'],
				'distance_rate'=>$row['distance_rate'],
				'cabtype'=>$car_type,
				'client_name'=>$row['UserName'],
				'driver_name'=>$row['driver_name'],
				'driver_details'=>$row['driver_details'],
				// To be R&D in future ///
				//'created_at'=>$row['addedtime'],
				'is_paid'=>$is_paid,
				'paid_at'=>$row['paid_at'],
				// 'currency'=>$row['currency'],
				'invoice_number'=>$row['invoice_number'],
				'payment_type'=>$row['payment_type'],
				'fees'=>$row['fees'],
				'total_price'=>$row['total_price'],
				'total_tax_price'=>$row['total_tax_price'],
				'duration_rate'=>$row['duration_rate'],
				'starting_rate'=>$row['starting_rate'],
				'base_price'=>$row['base_price'],
				'tax_price'=>$row['tax_price'],
				'duration_rate'=>$row['duration_rate'],
				'duration_charge'=>$row['duration_charge'],
				'distance_charge'=>$row['distance_charge'],
				'minimum_price'=>$row['minimumCharge'],
				'starting_charge'=>$row['starting_charge'],
				'cancellation_price'=>$row['cancellation_price'],
				'waiting_charge'=>$row['approx_waiting_charge'],
				'waiting_minute'=>$row['appox_waiting_minute'],
				'totalbill'=>$row['totalBill'],
				'tripCharge'=>$row['tripCharge'],
				'pickuparea'=>$row['PickupArea'],
				'droparea'=>$row['DropArea'],
				'pickupaddress'=>$row['PickupAddress'],
				'dropaddress'=>$row['DropAddress']." ".$row['DropArea'],
				'estimated_distance'=>$row['EstimatedDistance'],
				'estimated_time'=>round(($row['EstimatedTime']/60)),
				'booking_id'=>$row['booking_reference'],
				'useragent'=>$row['useragent'],
				'bookingtype'=>$booking_type,
				'bookingdate'=>$row['BookingDate'],
				'pickupdate'=>$row['PickupDate'].' '.$row['PickupTime'],
				'driver_note'=>$row['driver_note'],
				'client_note'=>$row['client_note'],
				'features'=>$row['features'],
				'is_corporate_booking'=>$is_corporate_booking,
				'is_account_booking'=>$row['is_account_booking'],
				'voucher_id'=>$row['voucher_id'],'voucher_type'=>$row['voucher_type'],
				'arrival_time_pre'=>$row['arrival_time_pre'],
				'arrival_time_post'=>$row['arrival_time_post'],
				'arrival_time_actual'=>$row['arrival_time_actual'],
				'expiration_time'=>$row['expiration_time'],
				'actual_driven_distance'=>$row['actual_driven_distance'],
				'actual_waiting_distance'=>$row['actual_waiting_distance'],
				'actual_distance'=>$row['actual_distance'],
				'estimated_price'=>$row['estimated_price'],
				'driver_rating'=>$row['driver_rating'],
				'client_rating'=>$row['client_rating'],
				'actual_driven_duration'=>$row['actual_time'],
				'PickupLatitude'=>$row['PickupLatitude'],
				'PickupLongtitude'=>$row['PickupLongtitude'],
				'address_is_added'=>$address_is_added
			);
		}
		return array("data"=>$booking);
	}       
            
        public function book_fetch_info(){
            $id=$_REQUEST['booking_id'];
            $booking=array();
            $hour=mysqli_query($this->con,"CALL `book_fetch_info`($id)") or die(mysqli_error($this->con));
            $row = mysqli_fetch_array($hour);
            $booking[] = $row;
            if($row['tripCharges_per_minute']==null)
            {
                $tripCharges_per_minute="0";
            }
            $new_data = array("measure"=>$row['measure'],"currency"=>$row['currency'],"booking_reference"=>$row['booking_reference'],
            "name"=>$row['UserName'],"booking_id"=>$row['ID'],"booking_type"=>$row['BookingType'],"pickup_area"=>$row['PickupArea'],
            "pickup_address"=>$row['PickupAddress'],"pickup_location"=>$row['pickup_location'],"drop_address"=>$row['DropAddress'],
            "drop_location"=>$row['drop_location'],"pickup_latitude"=>$row['PickupLatitude'],"pickup_longitude"=>$row['PickupLongtitude'],
            "drop_latitude"=>$row['DestinationLatitude'],"drop_longitude"=>$row['DestinationLongtitude'],"pickup_date"=>$row['PickupDate'],
            "pickup_time"=>$row['PickupTime'],"per_distance_charge"=>$row['approx_distance_charge'],"mobile_no"=>$row['MobileNo'],
            "drop_distance"=>$row['EstimatedDistance'],"pickup_distance"=>"","estimatedTotalBill"=>$row['estimated_price'],
            "estimatedTime"=>$row['PickupTime'],"send_time"=>$row['send_time'],"basicTax"=>$row['basic_tax'],
                "tripCharges_per_minute"=>$tripCharges_per_minute);
            
            
            
            $pickupDateTime = $new_data['pickup_date']." ".$new_data['pickup_time'];
            $currentDateTime = $new_data['send_time'];
            
            $pickupDateTimeUnix = strtotime($pickupDateTime);
            $currentDateTimeUnix = strtotime($currentDateTime);
            
            $time_diff = $pickupDateTimeUnix - $currentDateTimeUnix;
            
            if($time_diff <=3600 && ($pickupDateTimeUnix >= $currentDateTimeUnix)){
                return array("status"=>"true","data"=>$booking,"new_data"=>$new_data);
            }else{
                return array("status"=>"true","data"=>$booking,"new_data"=>"");
            }
               
        }	
    
   ///// Session On Off Service Done by Mohit Jain Starts Here ////////////
   
    public function on_off(){
		$id=$_REQUEST['id'];
		$activeStatus=$_REQUEST['diverSession_Status'];	
		
		/*$sql="SELECT is_active as is_on FROM tbluser WHERE id='$id' and loginstatus='1'"; 
		$result=mysqli_query($this->con,$sql);
		$res=mysqli_fetch_array($result);
		if($res['is_on']==1){
		$activeStatus=0;
		}else{
		$activeStatus=1;
		}*/
		$sql_update="UPDATE tbluser SET is_active='$activeStatus' WHERE id='$id'"; 
		$res_update=mysqli_query($this->con,$sql_update);
		$sql1="SELECT is_active FROM tbluser WHERE id='$id'"; 
		$result1=mysqli_query($this->con,$sql1);
		$res1=mysqli_fetch_array($result1);
		if($res1['is_active']==1){
		$flag="true";
		}else{
		$flag="false";
		}
		return array('status'=>'true','flag'=>$flag);	
	}
	
 		/*public function on_off(){
		$id=$_REQUEST['id'];
		$result=mysqli_query($this->con,"CALL sp_driver_on_off('$id')") or die(mysqli_error($this->con));
		$flag=mysqli_fetch_assoc($result);
		return array('status'=>'true','flag'=>$flag);	
		
		
		}*/
		
///// Session On Off Service Done by Mohit Jain Ends Here ////////////
		
	public function login_logs(){
		$id=$_REQUEST['id'];
		ini_set('max_execution_time', 300);
		mysqli_query($this->con,"DELETE from driver_login_logs where uid='$id'") or die(mysqli_error($this->con)); 
		$result1=$this->Temp_time($id);
		if($result1['status']=='true'){
			$result=mysqli_query($this->con,"SELECT login_time,logout_time, login_date as `date`,login_diff as diff from driver_login_logs WHERE uid='$id' ORDER by id DESC") or die(mysqli_error($this->con));   
		}
		$logs=array();
		$diff="00:00:00";
		$k=0;
		while($row=mysqli_fetch_array($result)){
			if(array_key_exists($row['date'],$logs)){
				$time=$this->addiontionOfTime($logs[$row['date']]['hours'], $row['diff']);     //call function  
				$logs[$row['date']]['hours']=$time['time_sum'];
			if($row['login_time']!=""&&$k>1){
				$logs[$row['date']]['login_time']=$row['login_time']; 
			}
			if($logs[$row['date']]['logout_time']==""&&$k>1){
				$logs[$row['date']]['logout_time']=$row['logout_time']; 
			}
			}else{
				$k++;
				$logs[$row['date']]['hours']=$row['diff'];
				$logs[$row['date']]['login_time']=$row['login_time'];
				$logs[$row['date']]['logout_time']=$row['logout_time'];
			}
		}
		$date=array();
		foreach($logs as $log=>$v){
			$date[]=array('date'=>$log,'hours'=>$logs[$log]['hours'],'login_time'=>$logs[$log]['login_time'],'logout_time'=>$logs[$log]['logout_time'] );
		}
		return array('status'=>'true','data'=>$date);
	}
		
		
		
	public function get(){
		//$regId2 =$_POST['registatoin_ids'];
		$regId2 ='APA91bFYStm_KCxglXM-egKlDOpJ4fQMV01IvK3NXYT2kRoGxyRy8f_yLGm6odXynlxLiNKJNg_fg43eyRJWHEhjqol-Vkk5CdwdtqRSznT_Md_RDnk1Lg6FH3TUBahB_                         1sZ8kqVbk91Vy3U4yBsxYtWNseNpTRE9g';
		$regId=array($regId2);
		$message = array("mesg" => "Tesing Push Notification");
		$result = $this->send_notification($regId, $message);
		return array("respone" => $result);
	}
	
	
	
	
	public function add($name, $value){
        return array("name" => $name, "value" => $value);
    }
	
    public function login_logs_2(){
				  
		$id=$_REQUEST['id'];
                $date=$_REQUEST['date'];           
                
               $result=mysqli_query($this->con,"SELECT login_time,logout_time, login_date as `date`,login_diff as diff from driver_login_logs WHERE uid='$id' and login_date='$date' ORDER by id DESC") or die(mysqli_error($this->con));   
                $logs=array();
                $diff=0;
                while($row=mysqli_fetch_assoc($result))
                {
                    $diff =strtotime($diff +$row["diff"]);
                   
                    $logs[]=$row;
                    
                }
                $diff = date("H:i:s",$diff);
    
		return array('status'=>'true','data'=>$logs);
		
		
		}	
        
	public function driver_transaction(){
		$id=$_REQUEST['id'];
		$sql="SELECT * FROM tbl_driver_transaction WHERE user_id='$id' ORDER BY id desc";
		$result=mysqli_query($this->con,$sql);
		$data=array();
		while($row=mysqli_fetch_assoc($result)){
			$data[]=$row;
		}
		return array("status"=>"true","data"=>$data);
	}
        
	public function getuserprofile(){
		$id=$_REQUEST['id']; 
		$appType=$_REQUEST['appType'];
		//$id=2130;
		//$appType='DriverApp';
		$row=array();
		if($appType=="DriverApp"){
			$sql="SELECT * FROM tbluserinfo WHERE UID='$id'";
			$result = mysqli_query($this->con,$sql);
			$data = mysqli_fetch_assoc($result);
			
			$sql1="SELECT * FROM tbldriver WHERE UID='$id'";
			$result1= mysqli_query($this->con,$sql1);
			$data1 = mysqli_fetch_assoc($result1);
			$vehicleId=$data1['vehicleId'];
			
			$sql2="SELECT distinct vehicleNumber,tblignitiontype.CabIgnitionType as fuelType FROM tblcablistmgmt left join tblignitiontype on tblcablistmgmt.fuelType = tblignitiontype.id WHERE CabId='$vehicleId'";
			$result2= mysqli_query($this->con,$sql2);
			$data2 = mysqli_fetch_assoc($result2);
			
			if($data1['TypeOfvehicle']==1){
				$data1['TypeOfvehicle']="Economy";
			}elseif($data1['TypeOfvehicle']==2){
				$data1['TypeOfvehicle']="Sedan";
			}elseif($data1['TypeOfvehicle']==3){
				$data1['TypeOfvehicle']="Prime";
			}
			$row['name']=$data['FirstName'];
			$row['email']=$data['Email'];
			$row['mobile']=$data['MobNo'];
			$row['img_url']=$data['image'];
			$row['vehicleimg']=$data['vehicleimg'];
			$row['Address']=$data['Address1'];
			$row['TypeOfvehicle']=$data1['TypeOfvehicle'];
			$row['fuelType']=$data2['fuelType'];
			$row['vehicleNumber']=$data2['vehicleNumber'];
		}else{
			$result = mysqli_query($this->con,"CALL wp_getuserprofile('$id')");
			$data = mysqli_fetch_assoc($result);
			$row['name']=$data['FirstName'];
			$row['email']=$data['Email'];
			$row['mobile']=$data['MobNo'];
			$row['img_url']=$data['image'];
			$row['vehicleimg']=$data['vehicleimg'];
		}
		return array("status"=>"true","data"=>array($row));
		
		
	}

	public function edituserprofile(){		
		if(isset($_REQUEST['id'])){
			$id=$_REQUEST['id'];
			echo $id;
		}else{	
			$user_session = new Container('user');
			$id=$user_session->username;
		}
		$username=$_POST['userName'];
		$contact_no=$_POST['mobileNo'];
		$file=$_POST['image'];	  
		$imageData = base64_decode($file);
		$source = imagecreatefromstring($imageData);
		$angle = 90;
		$imageName = "hello1.png";
		$imageSave = imagejpeg($rotate,$imageName,100);
		imagedestroy($source);
		if($_POST['image']!=''){
			$query="SELECT image FROM tbluserinfo WHERE UID=$id";
			$data=  mysqli_fetch_array(mysqli_query($this->con, $query));
			$image_a=explode('/',$file);
			rename('public/tmp/image/'.$image_a[count($image_a)-1],$data['image']);
			file_put_contents('query.txt',$_POST['image'].$data['image'].$query);
		}
		$sql="UPDATE tbluserinfo SET FirstName='$username',MobNo='$contact_no' WHERE UID='$id'";
		mysqli_query($this->con, $sql)or die(mysqli_error($this->con));
		if(mysqli_affected_rows($this->con)>0){
			return array("success"=>"true","data"=>$username.$contact_no);
		}else{			
			return array("success"=>"false","data"=>$username.$contact_no."yahoosdf");
		}
	}
	public function edit_android_driver_profile(){		
		if($_REQUEST['token'] != ''){			
			$token=$_REQUEST['token'];
			$contact_no=$_REQUEST['mobile'];
			$emailid=$_REQUEST['email'];
			$DeviceType=$_REQUEST['DeviceType'];			
		$query="SELECT * FROM tbluser WHERE token='$token'";
		$result=mysqli_query($this->con, $query)or die(mysqli_error($this->con));		
			if(mysqli_num_rows($result) > 0){				
				$userData=mysqli_fetch_object($result);
				 $userId=$userData->ID;
				$sql="UPDATE tbluserinfo SET Email='$emailid',MobNo='$contact_no' WHERE UID='$userId'";
				$sql2="UPDATE tbluser SET LoginName='$emailid',UserNo='$contact_no' WHERE ID='$userId'";
				$sql3="UPDATE tbldriver SET Email='$emailid',ContactNo='$contact_no' WHERE UID='$userId'";
			mysqli_query($this->con, $sql)or die(mysqli_error($this->con));
			mysqli_query($this->con, $sql2)or die(mysqli_error($this->con));
			mysqli_query($this->con, $sql3)or die(mysqli_error($this->con));
				if(mysqli_affected_rows($this->con)>0){
					return array("status"=>"true","message"=>"Updated Successfully","data"=>$emailid.",".$contact_no);
				}else{			
					return array("status"=>"false","message"=>"Not Updated! Try Again","data"=>$emailid.",".$contact_no.",".$token);
				}
			}else{
				return array("status"=>"false","message"=>"Invalid Token! User Not Exist","data"=>$emailid.",".$contact_no.",".$token);
			}
			
		}else{
			return array("status"=>"false","message"=>"Invalid Token");
		}
		
	}
	
//	public function fetch_unassigned_jobs(){
//		$data = array();
//		$SQL = "SELECT * FROM tblcabbooking 
//				WHERE Status = 1 
//				AND CONCAT(PickupDate,'',PickupTime) > ".date("Y-m-d h:i:s", strtotime("-1800 seconds"))."  
//				ORDER BY PickupDate DESC, PickupTime DESC";
//		$QRY=mysqli_query($this->con,$SQL)or die(mysqli_error($this->con));
//		while($row=mysqli_fetch_assoc($QRY)){
//			$data = array_push($data, $row);
//		}
//		print_r($data);
//		return array("status"=>"true","data"=>$data);
//	}
        
		
	public function fetch_unassigned_jobs(){
		$array = array();
		$id=$_REQUEST['id'];
		//$id=2130;
		/* $query="SELECT * FROM tbldriver WHERE UID='$id'";
		$get=mysqli_query($this->con,$query)or die(mysqli_error($this->con));
		$res=mysqli_fetch_object($get);
		$Security_Amount=$res->SecurityAmt;
		if($Security_Amount >= 50){ */
		$query="SELECT tblcabmaster.CabType,tbldriver.ReciveAirPortTrns,tbldriver.AcceptCash FROM tbldriver INNER JOIN tblcabmaster on tbldriver.vehicleId = tblcabmaster.CabId  WHERE tbldriver.UID = $id";
		$get=mysqli_query($this->con,$query)or die(mysqli_error($this->con));
		$res=mysqli_fetch_object($get);
		$CabType=$res->CabType;
		$BookingType=$res->ReciveAirPortTrns;
		$PaymentType=$res->AcceptCash;
		//die;
		switch($CabType)
		{
		//// Need to be Optimized ////
		case 1:
		$sql = "SELECT * FROM tblcabbooking WHERE tblcabbooking.CarType = (SELECT tblcabmaster.CabType FROM tbldriver
		INNER JOIN tblcabmaster on tbldriver.vehicleId = tblcabmaster.CabId WHERE tbldriver.UID = $id) AND BookingType IN ($BookingType)  AND charge_type IN($PaymentType) AND pickup = 0 AND is_updation_allow!='FALSE' AND CONCAT(PickupDate,' ',PickupTime) >  NOW() ORDER BY CONCAT(PickupDate,'',PickupTime) DESC";
		break;
		case 2:
		$sql = "SELECT * FROM tblcabbooking WHERE (tblcabbooking.CarType ='1' OR tblcabbooking.CarType ='2') AND BookingType IN ($BookingType) AND charge_type IN($PaymentType) AND pickup = 0 AND is_updation_allow!='FALSE' AND CONCAT(PickupDate,' ',PickupTime) >  NOW() ORDER BY CONCAT(PickupDate,'',PickupTime) DESC";
		break;
		case 3:
		$sql = "SELECT * FROM tblcabbooking WHERE BookingType IN ($BookingType) AND charge_type IN($PaymentType) AND pickup = 0 AND is_updation_allow!='FALSE' AND CONCAT(PickupDate,' ',PickupTime) >  NOW() ORDER BY CONCAT(PickupDate,'',PickupTime) DESC";
		break;
		}
		//echo $sql; die;
			/* $sql = "SELECT * FROM tblcabbooking 
				WHERE tblcabbooking.CarType = (SELECT tblcabmaster.CabType 
								 FROM tbldriver
								 INNER JOIN tblcabmaster on tbldriver.vehicleId = tblcabmaster.CabId
								 WHERE tbldriver.UID = $id) 
				 AND pickup = 0 AND is_updation_allow!='FALSE' AND CONCAT(PickupDate,' ',PickupTime) >  NOW()  
				ORDER BY CONCAT(PickupDate,'',PickupTime) DESC"; */
			$result=mysqli_query($this->con,$sql)or die(mysqli_error($this->con));//'".date("Y-m-d h:i:s", strtotime("-1800 seconds"))."'
			
			//// Need to be Optimized ////
			
			$data = array();
			$array1=array();
			$array2=array();
			while($row=mysqli_fetch_assoc($result)){
				array_push($data, $row);
				$row['show']=1;
				if($row['pickup']==0){  
					$row['testtime']=$row['PickupDate']." ".$row['PickupTime'];
					$row['PickupDate']=date("d-M-Y",strtotime($row['PickupDate']));
					$row['PickupTime']=date("H:i",strtotime($row['PickupTime']));
					$array1[]=$row; 
				}else{
					$row['testtime']=$row['PickupDate']." ".$row['PickupTime'];
					$array2[]=$row; 
				}
				
				foreach($array2 as $k=>$v){
					foreach($array1 as $z=>$y){
						$time1=strtotime($array2[$k]["testtime"]);
						$time2=strtotime($array1[$z]["testtime"]);
						$diff=$time1-$time2;
						$diff2=$time1-$time2;
						if(abs($diff)<"null"){
							$array1[$z]["show"]=0;
						}
					}
				}
			}
			foreach($array1 as $k=>$v){
				if($array1[$k]["show"]==1){
					$array[]=$array1[$k];
				}
			}
			//print_r($array)
		    return array("status"=>"true","data"=>$array);
		/* }else{
			return array("status"=>"Failed","message"=>"Security Amount is Less then Rs. 50");
		} */
		
	}

	// Need TO BE OPTIMIZED AND CHECK/////////
	
	public function CheckDriverBalance($DriverId, $BookingId){
		$sql="SELECT tbluser.ID,tbluser.LoginName,tbluser.UserNo,CONCAT(tbluserinfo.FirstName,' ',tbluserinfo.LastName) AS DriverName,tbldriver.SecurityAmt,tblcabbooking.estimated_price 
			  FROM tblcabbooking, tbluser
			  INNER JOIN tbluserinfo ON tbluser.ID = tbluserinfo.UID 
			  INNER JOIN tbldriver ON tbluser.ID = tbldriver.UID 
			  WHERE tbluser.UserType = 3 AND tbluser.ID = $DriverId  AND tblcabbooking.ID = $BookingId";
		$qry = mysqli_query($this->con, $sql);
		$data = mysqli_fetch_object($qry);
		//echo $data->UserNo; die;
		
		// Check security amount as per the slabs
		if($data->SecurityAmt <= 1000 and $data->SecurityAmt >500){
			$msg = "Hi ".$data->DriverName.", Your Total account balance of your <variable2> as of <variable3> is Rs. <variable4>. Please recharge in order to avoid the blocking.";
			$this->SendSMS($data->UserNo, $msg);
			// Change 100 to 200 It should be from setting file and MSG Should be change As per discussion by Mohit /////
		}elseif($data->SecurityAmt <= 500 and $data->SecurityAmt >100){
			$msg = "Hi ".$data->DriverName.",  Please recharge in order to avoid the blocking on your ".$data->LoginName.". Your current total account balance as of ".date('d M Y h:i:s a')." is Rs. ".$data->SecurityAmt.".";
			$this->SendSMS($data->UserNo, $msg);
		}elseif($data->SecurityAmt <= 100){
			$msg = "Hi ".$data->DriverName.",  Duties for your ".$data->SecurityAmt." have been blocked due to nil or minus balance. Recharge immediately to get the booking.";
			$this->SendSMS($data->UserNo, $msg);
			return false;
		}
		
		///// Change Driver Share to Company Share By Mohit /// 
		// Fetch & Calculate driver share
		$sql = "SELECT tblclient.driver_share FROM tblclient";
		$qry = mysqli_query($this->con, $sql);
		$ShareData = mysqli_fetch_object($qry);
		
		// Calculate driver share amount
		$amt = ($data->estimated_price * $ShareData->driver_share)/100;
		
		// Validate share amount on security amount
		if($data->SecurityAmt < $amt){
			return false;
		}
		return true;
	}
	public function CheckCabType($DriverId,$BookingId){
		
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
		if($DriverVehicle == "Prime"){			
			return true;
		}elseif($DriverVehicle == "Sidan" && $BookingVehicle == "Sidan" || $BookingVehicle == "Economy"){			
			return true;
		}elseif($DriverVehicle == "Economy" && $BookingVehicle == "Economy"){			
			return true;
		}else{			
			return false;
		}				
	}
	/* Comment By Kanika : Need optimization in IsCabtypeMatched method
	 * Currently : separate hit for getting cab type for booking & driver
     * Expected : Single hit with left outer join 	 
	 */
	public function IsCabtypeMatched($DriverId,$BookingId){
		//$BookingId=$_REQUEST['booking_id'];
		//$DriverId=$_REQUEST['DriverId'];
		  $sql="SELECT tblcabtype.CabType FROM tblcabtype 
			  INNER JOIN tblcabbooking ON tblcabtype.Id = tblcabbooking.CarType 
			  WHERE tblcabbooking.ID = '$BookingId'"; //die;
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
	
	public function accept_fetch_job(){
		//error_reporting(0);
		$id=$_REQUEST['id'];
		$booking_id=$_REQUEST['booking_id'];
		$BookingId=$booking_id;
		//$id='2130';
		//$booking_id='7351';
		//$BookingId='7351';
		
		//echo $this->CheckDriverBalance($id, $booking_id); die;
		
		if($this->CheckDriverBalance($id, $booking_id)){
			//if($this->CheckCabType($id, $booking_id)){
			
			/* Comment by Kanika : Need optimization,
			   Currently : 2 separate hits are there on tblcabbooking, one from sp & other in further code,
			   Expected : Single hit in procedure only and get booking type from procedure itself
            */			   
				$query="CALL sp_w_accept_fetch_job('$booking_id','$id')"; 
				$result=mysqli_query($this->con,$query);
				$data=  mysqli_fetch_assoc($result);
				//echo $data['is_stack']; die;
				if($data['is_stack']==1){
				//if(1==1){				
					mysqli_free_result($result); 
				    mysqli_next_result($this->con);	
					
				    //This hit is done unnecessary as booking type can be fetched from procedure above
   				    /*$sql2="SELECT BookingType FROM tblcabbooking WHERE id='$booking_id'"; 
					$res =mysqli_query($this->con, $sql2) or die(mysqli_error($this->con));
					$data1 = $res->fetch_array();
					$BookingType=$data1['BookingType'];*/
				   $BookingType=$data['BookingTypeVal'];
				   //$BookingType=101;
				   // Check if driver's cab matches with booking cab
  				   $IsMatchedCabType=$this->IsCabtypeMatched($id,$booking_id);
					//echo $IsMatchedCabType; die;

					if($IsMatchedCabType){
						$calculationType=1;
					}else{
						$calculationType=0;
					}
				$SQL="CALL `book_fetch_info`($booking_id,$BookingType,$calculationType)";//exit;
					//echo $SQL;die;
				//$SQL="CALL `book_fetch_info`($booking_id,$BookingType)";//exit;
				$book_fetch_info=mysqli_query($this->con,$SQL) or die(mysqli_error($this->con));
				$booking=array();
				
				// Loop the data retrieved from book_fetch_info SP 
				$row=mysqli_fetch_assoc($book_fetch_info);
				//while($row=mysqli_fetch_assoc($book_fetch_info)){
					//echo "<pre>";print_r($row); die;
					
					if($row["BookingType"]==103){						
						$row["Price"]="0";
					}
					$waitingfee_upto_minutes = $row['waitingfee_upto_minutes'];
					$waitingfee_upto_minutesArr = json_decode($waitingfee_upto_minutes);
					$waitningfeeKey = array();
					$waitingfeeValue = array();
					for($i=0;$i<count($waitingfee_upto_minutesArr);$i++){
						$wtngFee = explode("_",$waitingfee_upto_minutesArr[$i]);
						$waitningfeeKey[$wtngFee[0]] = $wtngFee[1];
					}
	
					if($row['tripCharge_per_minute']==null){
						$tripCharges_per_minute="0";
					}else{
						$tripCharges_per_minute=$row['tripCharge_per_minute'];
					}
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
		
					$row['configPackageNo'] = $row['Sub_Package_Id'];
					if($row["BookingType"]==101){						
					$MinimumCharges=$row['Price'];
					}
					switch($row['CarType'])
					{
					case 1:
					$row['CarTypeValue'] = 'Economy';
					break;
					case 2:
					$row['CarTypeValue'] = 'Sedan';
					break;
					case 3:
					$row['CarTypeValue'] = 'Prime';
					break;
					}
					
					$row['MinimumCharge'] = $MinimumCharges;
					if($row["BookingType"]==101){						
					$row["Min_Distance"]=$row["CabForKm"];
					}else{
					$row['Min_Distance'] = $MinimumDistance;
					}
					$row['PerKmCharges'] = $PerKmCharges;
					$row['PerHrCharges'] = $PerHrCharges;
					$row['NightCharges'] = $row['NightCharges'];
					$row['NightChargesBy'] = $row['nightCharge_unit'];
					
					$waiting_fees = explode('_',$row['waiting_fees']);
					$row['WaitingFreeMin'] = $waiting_fees[0];
					$row['WaitingBiforeCharge'] = $waiting_fees[1];
					$row['WaitingAfterCharge'] = $waiting_fees[2];
	
					$booking[]=$row;
					$nightPrice = $row['NightCharges'];
					$new_data = array(
						"measure"=>$row['measure'],
						"currency"=>$row['currency'],
						"booking_reference"=>$row['booking_reference'],
						"name"=>$row['UserName'],
						"booking_id"=>$row['ID'],
						"booking_type"=>$row['BookingType'],
						"configPackageNo"=>$row['configPackageNo'],
						"pickup_area"=>$row['PickupArea'],
						"booking_hours"=>$row['CabFor'],
						"pickup_address"=>$row['PickupAddress'],
						"pickup_location"=>$row['pickup_location'],
						"drop_address"=>$row['DropAddress'],
						"drop_location"=>$row['drop_location'],
						"pickup_latitude"=>$row['PickupLatitude'],
						"pickup_longitude"=>$row['PickupLongtitude'],
						"drop_latitude"=>$row['DestinationLatitude'],
						"drop_longitude"=>$row['DestinationLongtitude'],
						"pickup_date"=>$row['PickupDate'],
						"pickup_time"=>$row['PickupTime'],
						"per_distance_charge"=>$row['approx_distance_charge'],
						"mobile_no"=>$row['MobileNo'],
						"drop_distance"=>$row['EstimatedDistance'],
						"pickup_distance"=>"",
						"estimatedTotalBill"=>$row['estimated_price'],
						"estimatedTime"=>$row['PickupTime'],
						"send_time"=>$row['send_time'],
						"min_distance"=>$row['Min_Distance'],
						"NightCharges"=>$nightPrice[0],
						"WaitingCharge_per_minute"=>$waitningfeeKey,
						"MinimumCharge"=>$row['MinimumCharge'],
						"night_rate_begins"=>$row['night_rate_begins'],
						"night_rate_ends"=>$row['night_rate_ends'],
						"basicTax"=>$row['basic_tax'],
						"Waitning_minutes"=>"",
						"tripCharges_per_minute"=>$tripCharges_per_minute
					);
					$pickupDateTime = $new_data['pickup_date']." ".$new_data['pickup_time'];
					$currentDateTime = $new_data['send_time'];
					//echo "Pickup Date & Current Date time".$pickupDateTime."and".$currentDateTime;
					$pickupDateTimeUnix = strtotime($pickupDateTime);
					$currentDateTimeUnix = strtotime($currentDateTime);
					$time_diff = $pickupDateTimeUnix - $currentDateTimeUnix;
	
					//}	
					mysqli_free_result($book_fetch_info); 
					mysqli_next_result($this->con);
				$this->send_sms_new2($BookingId,$flag="accept");
					if($time_diff <=3600 && $pickupDateTimeUnix >= $currentDateTimeUnix){
						//echo $pickupDateTimeUnix."and".$currentDateTimeUnix;
						$booking[0]['Is_Futuristic'] = false;						          
						mysqli_query($this->con,"update booking_stack SET booking_stack.`status`='A' WHERE booking_id='$booking_id'");
						mysqli_query($this->con,"INSERT INTO `tblbookingregister`(`bookingid`,`driverId`,`updateOn`,`status`,`type`) VALUES('$booking_id','$id',Now(),'A','By Un-Assigned Booking')");
						mysqli_query($this->con,"update tbldriver SET tbldriver.`status`='1' WHERE UID='$id'");
						$data_new[]=$new_data;
						return array("status"=>"true","data"=>$booking);
					}else{
						$booking[0]['Is_Futuristic'] = true;
						$new_data1=array();
						return array("status"=>"true","data"=>$booking);
					}								
				}else{
					$msg = "This booking is Already accepted";
					return array("status"=>"false", 'msg'=>$msg);
				}
			/*}else{
				$msg = "Cab Not Matched";
				return array("status"=>"false", 'msg'=>$msg);
				}*/
		}else{
			 $msg = "Your balance amount is low so you can not accept Booking";
			return array('status'=>false, 'msg'=>$msg);
		}
	} 


	public function accept_booking(){
		$booking_id=$_POST['booking_id'];
		$BookingId = $booking_id;
		$user_id=$_POST['user_id'];
		$check_booked=mysqli_query($this->con,"CALL sp_check_booking('$booking_id','$user_id')") OR die(mysqli_error($this->con));
		$result=mysqli_fetch_assoc($check_booked);
		$new=uniqid();
		if($result['checked']=="1"){
			file_put_contents($new."w.txt","");
			mysqli_free_result($check_booked); 
			mysqli_next_result($this->con); 	
			$this->send_sms_new2($BookingId,$flag="accept");
			$msg_query=mysqli_fetch_array(mysqli_query($this->con,"SELECT * FROM tbl_sms_template WHERE msg_sku='accept'"));
			$array=explode('<variable>',$msg_query['message']);
			$array[0]=$array[0].$result['cname'];
			$array[1]=$array[1].$result['driver'];
			$array[2]=$array[2].$result['vehicle'];
			$array[3]=$array[3].$result['ref'];
			$array[4]=$array[4].$result['pdate'];
			$text=  urlencode(implode("",$array));	
			file_put_contents("mssg.txt",$text);
			$url="http://push3.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$mobile&from=Helocb&dlrreq=true&text=$text&alert=1";
			file_get_contents($url); 
			
			mysqli_query($this->con,"INSERT INTO `tblsmsstatus`(`UID`,`mesg`,`ContactNo`,`status`) VALUES('$uid','Thankyou for choosing Hello42 cabs','$mobile','1')");
			return array("status"=>"true","booking_id"=>$booking_id,"user_id"=>$user_id,"check"=>$result['checked']);
		}else{
			file_put_contents($new."n.txt","");
			return array("status"=>'false',"booking_id"=>$booking_id,"user_id"=>$user_id,"check"=>$result['checked']);
		}          
	}


	public function locate_booking(){
		$booking_id=$_REQUEST['booking_id'];
		$driver_id=$_REQUEST['driver_id'];
		$lat = $_REQUEST['lat'];
		$lng = $_REQUEST['lng'];
		$aqueyt="CALL sp_locate_booking('$booking_id','$driver_id','$lat','$lng')";
		$query=mysqli_query($this->con,$aqueyt);
		$data = mysqli_fetch_assoc($query);
		$status = 'false';
		if($data['lastid']>0){	
			$status = 'true';		
		}else{
			$status = 'false';
		}  
		mysqli_free_result($query);
		mysqli_next_result($this->con);
		//$this->send_sms_new1($booking_id,$flag="L");
		return array("status"=>$status);
		exit;
	}
	

	public function testmail(){
		return 'hello';
		$to = "bhagendra.singh82@gmail.com";
		$subject = "My subject";
		$txt = "Hello world!";
		$headers = "From: webmaster@example.com";
		if(mail($to,$subject,$txt,$headers)){
			$data = '1';
		}else{
			$data = '2';
		}
		return $data;
	}

    public function mailing($mail='bhagendra.singh82@gmail.com',$activation='test'){	
		$msg='Hi User,'.'<br><br>'.'Please enter the below given code in the browser for account verification'.'<br><br>'.$activation.'<br><br>'.'Best Regards,<br><br>Hello42 Cab Team';
		$html = new MimePart($msg);
		$html->type = "text/html"; 
		$body = new MimeMessage();
		$body->setParts(array($html));
		$message = new Message();
		$message->addTo("$mail")
		//$message->addTo("seo.udaikr@gmail.com")
		->addFrom('Hello42@cab.com')
		->setSubject('Activation Mail')
		->setBody($body);
		$transport = new SmtpTransport();
		$options = new Mail\Transport\SmtpOptions(array(  
		'name' => 'localhost',  
		'host' => 'p3plcpnl0269.prod.phx3.secureserver.net',  
		'port'=> 587,  
		'connection_class' => 'login',  
		'connection_config' => array(  
		'username' => 'feedback@hello42cab.com',   // set Gamil email id
		'password' => '123456',   // gamil password
		'ssl'=> 'tls'  // connection type
		)  
		));  
		
		$transport = new Mail\Transport\Smtp($options);  
		$transport->send($message);
		//return array("Status" => "Success");
		return true;
    }
    
    
    
     public function forget_mail($mail,$activation){	
		$html = new MimePart($activation);
		$html->type = "text/html"; 
		$body = new MimeMessage();
		$body->setParts(array($html));
		$message = new Message();
		$message->addTo("$mail")
		->addFrom('Hello42@cab.com')
		->setSubject('Forgot Password')
		->setBody($body);
		$transport = new SmtpTransport();
		$options = new Mail\Transport\SmtpOptions(array(  
			'name' => 'localhost',  
			'host' => 'p3plcpnl0269.prod.phx3.secureserver.net',  
			'port'=> 587,  
			'connection_class' => 'login',  
			'connection_config' => array(  
				'username' => 'feedback@hello42cab.com',   // set Gamil email id
				'password' => '12345',   // gamil password
				'ssl'=> 'tls',  // connection type
			),  
		));  
		$transport = new Mail\Transport\Smtp($options);  
		$transport->send($message);
		return true;
    }
    
//      public function mail_send($mai'bhagendra.singh82@gmail.com',$activation='HelloTest'){	
//		$html = new MimePart($activation);
//		$html->type = "text/html"; 
//		$body = new MimeMessage();
//		$body->setParts(array($html));
//		$message = new Message();
//		$message->addTo("$mail")
//		->addFrom('Hello42@cab.com')
//		->setSubject('Forgot Password')
//		->setBody($body);
//		$transport = new SmtpTransport();
//		$options = new Mail\Transport\SmtpOptions(array(  
//			'name' => 'localhost',  
//			'host' => 'p3plcpnl0269.prod.phx3.secureserver.net',  
//			'port'=> 587,  
//			'connection_class' => 'login',  
//			'connection_config' => array(  
//				'username' => 'feedback@hello42cab.com',   // set Gamil email id
//				'password' => '12345',   // gamil password
//				'ssl'=> 'tls',  // connection type
//			),  
//		));  
//		$transport = new Mail\Transport\Smtp($options);  
//		$transport->send($message);
//		return true;
//    }
    
    


    
	public function mailing_new($mail,$message,$subject,$from){	
		if($mail!=''){
			//$email = $_POST['emailId'];
			//$act= md5($email.time());
			//$emailId = mysqli_real_escape_string($connection,$_POST['email']);
			$html = new MimePart($message);
			$html->type = "text/html"; 
			$body = new MimeMessage();
			$body->setParts(array($html));
			$message = new Message();
			$message->addTo("$mail")
			//$message->addTo("seo.udaikr@gmail.com")
			->addFrom($from)
			->setSubject($subject)
			->setBody($body);
			$transport = new SmtpTransport();
			$options = new Mail\Transport\SmtpOptions(array(  
					'name' => 'localhost',  
					'host' => 'p3plcpnl0269.prod.phx3.secureserver.net',  
					'port'=> 587,  
					'connection_class' => 'login',  
					'connection_config' => array(  
					'username' => 'feedback@hello42cab.com',   // set Gamil email id
					'password' => '12345',   // gamil password
					'ssl'=> 'tls',  // connection type
				),  
			));  
			
			$transport = new Mail\Transport\Smtp($options);  
			$transport->send($message);
			//return array("Status" => "Success");
			return true;
		}
    }
	

	public function activation(){
		$verify = $_POST['userVerfy'];
		$token=md5(uniqid().$username);
		$token1 = $token;
		$result = mysqli_query($this->con,"CALL wp_activation_user('$verify','$token')");
		$data = mysqli_fetch_assoc($result);
		if($data['verifyTotal']==1){                   
			$_COOKIE['token'] = $token1;
			$id= $data['uerrid'];
			return array("Status" => "Success");  
		}else{
			return array("Status" => "False","ErrorCode"=>'103');  
		}
	}

	public function patch(){
		
//		$sql = "select * from tblcablistmgmt where CabId != '158'";
//		print $sql.'<br>';
//		$qry = mysqli_query($this->con, $sql) or die(mysqli_error());
//		while($row = mysqli_fetch_assoc($qry)){
//			$data = array(101,102,103,104);
//			foreach($data as $val){
//				$sql = "insert into tblcablistmgmt set BookingType = '".$val."', CabType = '".$row['CabType']."', CabId = '".$row['CabId']."' ";
//				print $sql.'<br>';
//				mysqli_query($this->con, $sql) or die(mysqli_error());
//			}
//			$sql = "delete from tblcablistmgmt where id = '".$row['id']."'";
//			print $sql.'<br>';
//			mysqli_query($this->con, $sql) or die(mysqli_error());
//		}
		
		
		
		
		
		
		/*
		$data = array();
		$keys = "(`CabId`, `CabName`, `CabImage`, `CabType`, `CabManufacturer`, `CabModel`, `CabRegistrationNumber`)";
		$values = '';
		$sql = "select tblcablistmgmt.id, tblcablistmgmt.CabType, tblcablistmgmt.name, tblcablistmgmt.image, tblcablistmgmt.VehicleModel, tblcablistmgmt.vehicleNumber, tblcablistmgmt.Manufacturer from tblcablistmgmt order by id asc";// where tblcablistmgmt.id = 142
		$qry = mysqli_query($this->con, $sql);
		while($row = mysqli_fetch_assoc($qry)){
			$values .= "('".$row['id']."', '".$row['name']."', '".$row['image']."', '".$row['CabType']."', '".$row['Manufacturer']."', '".$row['VehicleModel']."', '".$row['vehicleNumber']."'),";
		}
		$sql = "insert into tblcabmaster $keys values ".substr($values,0,-1);
		print $sql;
		//mysqli_query($sql) or die(mysqli_error());
		*/
	}
	
	public function driversignup(){
		session_start();
		$unique_driver_id = "";
		$driverName = $_POST['dName'];
		$name=explode(" ",$driverName);
		$firstname = $name[0];
		$secondname = $name[1];
		$fathername = $_POST['dfName'];		
		$driver_email = $_POST['driver_email'];
		$refrence = $_POST['drefName'];
		$dateofbirth = $_POST['dreDateOfBirth'];
		$gender = $_POST['dregender'];
		$driverNumber = $_POST['dNo'];
		$AdriverNumber = $_POST['dAno'];
		$driverAddress = $_POST['dAd'];
		$driverOfficeAddress = $_POST['dofc'];
		$driverPan = $_POST['dpan'];
		$driverFleets = $_POST['dfleet'];
		//$driverAmount = $_POST['driverAmount'];
		$driver_mob_isd = $_POST['driver_mob_isd'];
		$igniton_type = $_POST['igniton_type'];
		$permit_date = $_POST['permit_date'];
       		$country = $_POST['driver_country'];
		$state = $_POST['driver_state'];
		$cityid = $_POST['city'];
		$driver_opration_country = $_POST['driver_opration_country'];
		$driver_opration_state = $_POST['driver_opration_state'];
		$driver_opration_city = $_POST['driver_opration_city'];
		$driver_opration_Company = $_POST['driver_opration_Company'];
		$driver_pref_city = $_POST['driver_pref_city'];
		$DriveroffDay = $_POST['DriveroffDay'];	
		$rc_no= $_POST['rc_no'];
		$com_pincode=$_POST['com_pincode'];
		
		$find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($driverAddress));
		file_put_contents("json.txt",$find_pickup); 					
		$enc=json_decode($find_pickup);
		$lat=$enc->results[0]->geometry->location->lat;
		$long=$enc->results[0]->geometry->location->lng;
		/* $state="";
		$country ="";
		foreach($enc->results[0]->address_components as $v){
			if($v->types[0]=="administrative_area_level_1"){
				$state=$v->long_name;
			}
			 if($v->types[0]=="country"){
				$country=$v->long_name;
			}
			if($v->types[0]=="locality"){
				$city=$v->long_name;
			} 
		} */
		//  file_put_contents('addsss123.txt',print_r($area,TRUE)); 
		//  file_put_contents('addsss12333333.txt',print_r($country,TRUE)); 
		$driverAir = $_POST['dAir'];
		$driverCash = $_POST['dcash'];
		//$driverCredit = $_POST['dCredit'];
		$drivermake = $_POST['drivermake'];
		$driverBoth = $_POST['dboth'];
		$vnamedriver = $_POST['vechile_name'];
		$cartypes = $_POST['driver_cartypes'];
		//$driverMakeOfVech = $_POST['driverMakeVec'];
		$driverModel = $_POST['driverModel'];		
		$driverVechNo = $_POST['driverVechNoChar'].$_POST['driverVechNo'];
		$driverTypeVech = $_POST['driverTypeVech'];             
		$driverBadgeLic= $_POST['driverBadgeLic'];
		$driverLicState= $_POST['driverLicState'];
		$driverSpec = $_POST['driverSpec'];
		$driverZone= $_POST['driverZone'];
		$driverRouteK= $_POST['driverRknow'];
		$driverPrefL= $_POST['driverPrefL'];
		$driverWoff= $_POST['driverWoff'];
		$driveimei= $_POST['driveimei'];
		$drivergps= $_POST['drivergps'];
		$driverRAc_NonAC= $_POST['driverRAc_NonAC'];
		$driverlangw= implode(",",$_POST['write']);
		$driverlangs= implode(",",$_POST['speak']);
		$driverlogint= implode(",",$_POST['logtime']);
		//$dutyAir = implode(",",$_POST['dAir']);
		$dutyAir	=	$_POST['chkdata'];
		
		$operational_company_name=$_POST['operational_company_name'];
		$license_validity= $_POST['license_validity'];
		$service_tax_reg_no= $_POST['service_tax_reg_no'];
		$vehicle_owned = $_POST['vehicle_owned'];
		
		if($vehicle_owned=="vehicle_attached"){
		$vehicle_owner_name	= $_POST['vehicle_owner_name'];
		$vehicle_owner_cnt_no = $_POST['vehicle_owner_cnt_no'];
		$vehicle_owner_alt_cnt_no = $_POST['vehicle_owner_alt_cnt_no'];
		}else{
		$vehicle_owner_name="";
		$vehicle_owner_cnt_no="";
		$vehicle_owner_alt_cnt_no="";									
		}	
		
		$insurance_validity= $_POST['insurance_validity'];
		
		
		//file_put_contents('re.txt',print_r($_POST,true));
		$dutyOut = $_POST['dOut'];
		$dCash = implode(",",$_POST['dCash']);
		//$second = implode(",",$_POST['write2']);
		//file_put_contents('re.txt',print_r($dCash,true));
		//$userpass = md5($_COOKIE['FirstPass']);
		$userpass = md5($_POST['driver_passwd']);
		//$userEmailIdLog = $_COOKIE['Email'];		
		$userEmailIdLog = $_POST['driver_email'];
		$userRole = 3;
		$vDdate = date("Y-m-d H:i:s");
		/////////vendor ////////////////
		$vname = $_POST['vName'];
		$vemail = $_POST['vEmail'];
		$vgroup = $_POST['vGroup'];
		$formLength = $_POST['flength'];
		//////////end of vendor////////////////
		$folder=$_SESSION['id'];
		$UserNos='';
		if($userEmailIdLog==$driverNumber){
			$UserNos=$driver_email;
		}
		if($userEmailIdLog==$driver_email){
			$UserNos=$driverNumber;
			// file_put_contents('all.txt', print_r($_POST,TRUE)); 
		}
		$sql = "SELECT `LoginName`,`UserNo` FROM `tbluser` WHERE `LoginName` ='$userEmailIdLog'  or LoginName='$UserNos' or 
		UserNo='$userEmailIdLog' or UserNo='$UserNos'";
		$result = mysqli_query($this->con,$sql);
		if(!mysqli_num_rows($result)){
			
			//$sql = "SELECT `id`,`name`,`CabType` FROM `tblcablistmgmt` WHERE `vehicleNumber`='$driverVechNo'";
			
			$sql = "SELECT * FROM tblcabmaster WHERE CabRegistrationNumber = '$driverVechNo'";
			$result = mysqli_query($this->con,$sql);
			if(!mysqli_num_rows($result)){
				
				/* $sqlcity = "SELECT `id` FROM `tblcity` WHERE `name` = '$city'";
				$resultcity = mysqli_query($this->con,$sqlcity);
				$cityall = mysqli_fetch_assoc($resultcity);
				if($cityall>0){
					$cityid = $cityall['id'];
				}else{
					$sqlinsertcity = "INSERT INTO `tblcity` (`name`,`created_date`) VALUES ('$city',NOW())";
					$resultcity = mysqli_query($this->con,$sqlinsertcity);
					$cityall = mysqli_insert_id($this->con);
					$cityid = "$cityall";
				} */
					// Insert cab inof
					
					$sql = "INSERT INTO tblcabmaster (`CabName`, `CabType`, `CabModel`, `CabRegistrationNumber`,`CabIgnitionTypeId`,`CabPermitExpDate`,`CabColor`) 
					VALUES ('".$_POST['vechile_name']."', '".$_POST['driver_cartypes']."','".$_POST['driverModel']."', '".$driverVechNo."','"
					.$_POST['igniton_type']."','".$_POST['permit_date']."','".$_POST['CabColor']."')";
					mysqli_query($this->con,$sql);
					$CabId = mysqli_insert_id($this->con);
				// end cab info	
				$BookingType = array(101, 102, 103, 104, 105);
				foreach($BookingType as $val){
				//$sqlcab = "select * from `tblbookingbill` where `CabType` = '$cartypes' LIMIT 0,1 ";
				$sqlcab = "select * from `tblbookingbill` where `CabType` = '$cartypes' AND BookingTypeId='$val' AND CityId='$driver_pref_city'";
				$cabresult = mysqli_query($this->con,$sqlcab);
				$cabinfo = mysqli_fetch_assoc($cabresult);
					$sql = "INSERT INTO `tblcablistmgmt`(`BookingType`, `CabId`,  `name`,`CabType`,`cityId`,`VehicleModel`,
					`vehicleNumber`,`fuelType`,`MinimumCharge`,`tripCharge_per_minute`,`Per_Km_Charge`,`first_km_rate`,`rate_upto_distance`,
					`round_up_km`,`waitingfee_upto_minutes`,`NightCharges`,`night_rate_begins`,`night_rate_ends`,`cancellation_fees`,
					`accumulated_instance`,`premiums`,`speed_per_km`,`Waitning_minutes`,`Min_Distance`,`rounding`,`configPackage`,`level`,
					`direction`,`basic_tax`,`basic_tax_type`,`extras`,`frequent_location`,`postcode_to_postcode_fair`,`is_active`,
					`rate_per_km_dh`,`minimum_distance_dh`,`minimum_fare_dh`,`rate_per_hour_dh`,`rate_per_km_dw`,`minimum_distance_dw`,
					`minimum_fare_dw`,`waiting_fees`,`drivingInCity`) VALUES ('".$val."', '".$CabId."', '".$vnamedriver."','".$cartypes."','".$cityid."',
					'".$driverModel."','".$driverVechNo."','".$_POST['igniton_type']."','".$cabinfo['MinimumCharge']."','".
					$cabinfo['tripCharge_per_minute']."','".$cabinfo['Per_Km_Charge']."','".$cabinfo['first_km_rate']."','".
					$cabinfo['rate_upto_distance']."','".$cabinfo['round_up_km']."','".$cabinfo['waitingfee_upto_minutes']."','".
					$cabinfo['NightCharges']."','".$cabinfo['night_rate_begins']."','".$cabinfo['night_rate_ends']."','".
					$cabinfo['cancellation_fees']."','".$cabinfo['accumulated_instance']."','".$cabinfo['premiums']."','".
					$cabinfo['speed_per_km']."','".$cabinfo['Waitning_minutes']."','".$cabinfo['Min_Distance']."','".
					$cabinfo['rounding']."','".$cabinfo['configPackage']."','".$cabinfo['level']."','".$cabinfo['direction']."','".
					$cabinfo['basic_tax']."','".$cabinfo['basic_tax_type']."','".$cabinfo['extras']."','".$cabinfo['frequent_location'].
					"','".$cabinfo['postcode_to_postcode_fair']."','','".$cabinfo['rate_per_km_dh']."','".$cabinfo['minimum_distance_dh'].
					"','".$cabinfo['minimum_fare_dh']."','".$cabinfo['rate_per_hour_dh']."','".$cabinfo['rate_per_km_dw']."','".
					$cabinfo['minimum_distance_dw']."','".$cabinfo['minimum_fare_dw']."','".$cabinfo['waiting_fees']."','".$driver_pref_city."')";
					$result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
				}
				$vehicletypeid = $_POST['driver_cartypes'];
				$email = $_POST['driver_email'];
				//$act= md5($email.time());
				$alphabet = '1234567890';
				$pcode = array(); //remember to declare $pcode as an array
				$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
					for ($i = 0; $i < 5; $i++) {
						$n = rand(0, $alphaLength);
						$pcode[] = $alphabet[$n];
					}
				$act= implode($pcode); //turn the array into a string
				$sql = "INSERT INTO `tbluser`(`LoginName`,`Password`,`UserType`,`UserNo`,`create_date`) VALUES ('$driver_email',
				'$userpass','$userRole','$driverNumber',NOW())";
				$result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));			
				if(mysqli_insert_id($this->con)){					
					$id = mysqli_insert_id($this->con); 
					$unique_driver_id = $id;
					$token=  uniqid();
					$referKey=substr($firstname,0,3).$unique_driver_id;
				    $sql2 = "update tbluser set referralKey='$referKey' where ID='$unique_driver_id'";
					$result3 = mysqli_query($this->con, $sql2) or die(mysqli_error($this->con));					 
					$image  ="public/userimage/$id/$token.jpeg";
					$vehicleimg="public/userimage/$id/v$token.jpeg";
					$sql= "INSERT INTO `tbluserinfo`(`UID`,`FirstName`,`LastName`,`City`,`Address1`,`Email`,`AltEmail`,`MobNo`,`LandNo`,
					`image`,`vehicleimg`) 
					VALUES ('$id','$firstname','$secondname','$cityid','$driverAddress','$driver_email','$driver_email','$driverNumber',
					'$driverNumber','$image','$vehicleimg')";
					mkdir("public/userimage/$id/",0777,true);	 
					rename("public/tmp/image/".$_SESSION['id']."/0.jpeg",$image );
					rename("public/tmp/image/".$_SESSION['id']."/0v.jpeg",$vehicleimg);
					$result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
					if(mysqli_insert_id($this->con) > 0){
						$data  = array(
						'UID'=>"$id",
						'FirstName' => "$firstname",
						'LastName' => "$secondname",
						'FatherName' => "$fathername",
						'Email' => "$driver_email",
						'refname' => "$refrence",
						'dateofbirth' => "$dateofbirth",
						'gender' => "$gender",
						'Country'=>"$country",
						'State'=>"$state",
						'City'=>"$cityid",
						'Address'=>"$driverAddress",
						'OfcAddress'=>"$driverOfficeAddress",
						'PinCode'=>$_POST['dpincode'],
						'ContactNo'=>"$driverNumber",
						'AlternateContactNo'=>"$AdriverNumber",
						'RoleID'=>"3",
						'DrivingLicenceNo'=>"$driverBadgeLic",
						'licence_state'=>"$driverLicState",
						//'userimage'=>"123",
						'PanCardNo'=>"$driverPan",
						'HavingTaxi'=>"$driverFleets",
						'TotalFleetNo'=>"$driverFleets",
						'TypeOfvehicle'=>"$vehicletypeid",
						'vehicleId'=>"$CabId",
						'ModelOfVehicle'=>"$driverModel",
						//'MakeOfVehicle'=>"$driverMakeOfVech",
						'VehicleRegistrationNo'=>"$driverVechNo",
						//'SecurityAmt'=>"$driverAmount",
						'bank_account_name'=>$_POST['driverBank'],
						'acount_holder'=>$_POST['driverholdern'],
						'bank_adds'=>$_POST['driverBaddess'],
						'account_no'=>$_POST['driverAcNo'],
						'rtgs_neft'=>$_POST['driverrtgs'],
						'ibn'=>$_POST['driveribn'],
						'zone'=>"$state",
						'route_know'=>"$driverRouteK",
						'pref_location'=>"$driverPrefL",
						'week_off'=>"$driverWoff",
						'imei'=>"$driveimei",
						'gps'=>"$drivergps",
						'ac_nonac'=>"$driverRAc_NonAC",
						'lang_write'=>"$driverlangw",
						'lang_speak'=>"$driverlangs",
						'pref_timing'=>"$driverlogint",
						'ReciveAirPortTrns'=>"$dutyAir",
						'AcceptCash'=>"$dCash",
						'AcceptCreditCard'=>"$driverCredit",
						'Eyetest'=>"1",
						'residential_proof'=>$_POST['driver_addsH'],
						'office_proof'=>$_POST['driver_oaddsH'],
						'pancard_proof'=>$_POST['driver_panH'],
						'vehicle_proof'=>$_POST['file_vehicleH'],
						'license_proof'=>$_POST['file_badgeH'],
						'police_proof'=>$_POST['file_policeH'],
						'audit_proof'=>$_POST['file_auditH'],
						'insurance_proof'=>$_POST['file_insuranceH'],
						'rc_proof'=>$_POST['file_rcproofH'],
						'created_date'=>"$vDdate",
						'is_delete'=>"1",
						'op_country'=>"$driver_opration_country",
						'op_state'=>"$driver_opration_state",
						'op_city'=>"$driver_opration_city",
						'CompanyID'=>"$driver_opration_Company",
						'pref_city'=>"$driver_pref_city",
						'weekoff_day'=>"$DriveroffDay",
						
						'license_validity'=>"$license_validity",
						'service_tax_reg_no'=>"$service_tax_reg_no",
						'vehicle_owned'=>"$vehicle_owned",
						'vehicle_owner_name'=>"$vehicle_owner_name",
						'vehicle_owner_cnt_no'=>"$vehicle_owner_cnt_no",
						'vehicle_owner_alt_cnt_no'=>"$vehicle_owner_alt_cnt_no",
						'insurance_validity'=>"$insurance_validity",
						'operational_company_name'=>"$operational_company_name",
						'rc_no'=>"$rc_no",
						'company_pincode'=>"$com_pincode"
						);
						if($formLength >= 1){
							$sqlvendor = "SELECT `group_name` FROM `tblvendor` WHERE `group_name`='$vgroup'";
							$result2 = mysqli_query($this->con,$sqlvendor);
							$numrows = mysqli_num_rows($result2);
							if($numrows >0){
								return array("vdata"=>'false');
							}else{
							
							$sqlvn = "INSERT INTO `tblvendor` (`UID`,`vendor_name`,`vendor_email`,`group_name`,`status`) VALUES ('$id',
							'$vname','$vemail','$vgroup','1')" ;
							$result = mysqli_query($this->con,$sqlvn);
							$vid = mysqli_insert_id($this->con);
							}
							
						}             
					}
					$tableName = 'tbldriver';
					$query = "INSERT INTO `$tableName` SET";
					$subQuery = '';
					foreach($data as $columnName=>$colValue) {
						$subQuery  .= "`$columnName`='$colValue',";
					}
					$subQuery =  rtrim($subQuery,", ");
					$query .= $subQuery;
					//return array('message'=>implode(',',$_POST['driver_name2']));	   
					$result = mysqli_query($this->con,$query) or die(mysqli_error($this->con));
					//file_put_contents('query.txt',$query);
					if(mysqli_insert_id($this->con) > 0){
						$last_id = mysqli_insert_id($this->con);                         
						/* $driver_amount =  mysqli_query($this->con,"CALL `sp_driver_recharge`('$unique_driver_id','$driverAmount',
						'Driver Registration')") or die(mysqli_error($this->con));
						mysqli_free_result($driver_amount);   
						mysqli_next_result($this->con); */ 
						if($formLength >= 1){                              
							$sqlvid = "UPDATE `tbldriver` SET `vendor_id`='$vid' WHERE `UID` = '$id'";
							$result = mysqli_query($this->con, $sqlvid);                                   
						}                     
						$sql = "INSERT INTO `tblactivation` (`UID`,`Verification_code`) VALUES ('$id','$act')";
						$result = mysqli_query($this->con,$sql);
						if(mysqli_insert_id($this->con)>0){
							//file_put_contents('gmail.txt',$driver_email);
							//$mail = $this->send_mail($driver_email,$act);
							//$mail = $this->SentEmail($driver_email, 'nobody@ip-166-62-35-117.secureserver.net', 'Activation Code', $body);
							$from='info@hello42cab.com';
							$subject='Activation Code';
							$body = 'Hi User,'.'<br><br>'.'Please enter the below given code in the browser for account verification'.'<br><br>'.$act.'<br><br>'.'Best Regards,<br><br><a href="'.site_url.'/verify">'.site_url.'/verify</a><br><br>Hello42 Cab Team<br><br>';
							//$mail=$this->send_mail($driver_email,$from,$subject,$body);
							
							//if($mail == true){
							$sql = "INSERT INTO `tblemailhostory` (`UID`,`mesg`,`status`,`time`) VALUES ('$unique_driver_id','$act','1',NOW())";
							$result =mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
							//}
							/* $url = "http://push1.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91".$driverNumber."&from=Helocab42&dlrreq=true&text=Thanks+for+choosing+HELLO42+CABS.+Your+Verificatiob+Code+ for+Activation.".$act."+or+For+Online+bkg+www.hello42cabs.com+from+Hello42cab&alert=1"; */
							
							$res = mysqli_query($this->con,"SELECT * FROM tbl_sms_template WHERE msg_sku='acc_verify_code'");
							$msg_query=mysqli_fetch_array($res); 
							$array=explode('<variable>',$msg_query['message']);
							$array[0]=$array[0].$driverName;
							$array[1]=$array[1].$act;
							$array[2]='http://hello42cab.com/verify';
							$text=  urlencode(implode("",$array));	
							//file_put_contents("mssg.txt",$text);
							$url="http://push3.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$driverNumber&from=Helocb&dlrreq=true&text=$text&alert=1";
							file_get_contents($url);
							mysqli_free_result($res);   
							mysqli_next_result($this->con);
							
							//$getmap = file_get_contents($url);
							$meta=array("message"=>"Success");	
							return array("Status" =>$meta,"Code"=>"001");
						}else{
							return array("Status" => "Unsuccess");
						}			
					}else{
						return array("Status" => "Unsuccess");
					} 
				}
			}else{
				return array("Status" => "Unsuccess","error"=>"0","msg"=>"This vehicle no already exist");
			}
		}else{
			return array("Status" => "Unsuccess","error"=>"1" ,"msg"=>"This mobile no already exist");
		}	
	}
	
		 
	public function localBooking(){					
		$UserId = 0;
		file_put_contents('local.txt',print_r($_REQUEST,true));		
			@$token=$_REQUEST['token'];
    $sql = "SELECT tbluser.ID,tbluser.LoginName as Email,tbluser.UserNo,tbluserinfo.FirstName FROM `tbluser` JOIN `tbluserinfo` ON tbluser.ID=tbluserinfo.UID WHERE `token`='$token'";
		$result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
			if(mysqli_num_rows($result)>0){
				$row = mysqli_fetch_array($result);
				$UserId = $row['ID'];
				$localname = $row['FirstName'];
				$localPhone = $row['UserNo'];
				$localEmail = $row['Email'] ;      
			}else{
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
		$PromotionName=$_REQUEST['PromotionName'];
		$CouponName=$_REQUEST['CouponName'];
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
			$cablocaldate=$_REQUEST['datepickerData'];
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
		mysqli_free_result(	$result);   
		mysqli_next_result($this->con);
		if($_POST['DeviceType']=="ANDROID"){
			$sqlpro="CALL `new_check`('$new', 'HA')";
		}else{
			$sqlpro="CALL `new_check`('$new', 'HW')";
		}		 
		$resulddd=mysqli_query($this->con,$sqlpro);
		$booking_ref=mysqli_fetch_assoc($resulddd);	
		mysqli_free_result($resulddd);   
		mysqli_next_result($this->con);
		//********************************GET subpackage Info***********************////////////////////////////////******
		 $query = "SELECT * FROM `tblcity` WHERE name='$cablocalin'";
		 $rowDATA =mysqli_fetch_assoc(mysqli_query($this->con,$query));	
		 $stateId=$rowDATA["state"];			
					 $sql = "SELECT * FROM `tbllocalpackage` WHERE Package='$cablocalfor' AND masterPKG_ID='$booking' AND City_ID='$stateId'"; 
					 $row = mysqli_query($this->con,$sql);		
					  $date = mysqli_fetch_object($row);
					
					  $Min_Pkg_KM = $date->Km;
					  $Min_Pkg_Hrs = $date->Hrs;
					  $Economy_Price = $date->Economy_Price;
					  $Sidan_Price = $date->Sidan_Price;
					  $Prime_Price = $date->Prime_Price;
							 if($carTypes==1){
								 $totalbill=$Economy_Price;
							 }elseif($carTypes==2){
								 $totalbill=$Sidan_Price;
							 }elseif($carTypes==3){
								 $totalbill=$Prime_Price;    
							 }
					//***************CheckPeakTime charges****************						
					/*    $query = "SELECT * FROM `tblpeaktime` WHERE ('$pickupTime' BETWEEN timeFrom AND timeTo)";
						   $fetch = mysqli_query($this->con,$query);
						   if(mysqli_num_rows($fetch)>0){
							   $vaLue=mysqli_fetch_assoc($fetch);
								  $PeakChargPercent=$vaLue["peakCharges"];
								  $PeakFare=$totalbill/$PeakChargPercent;
								  $totalbill=$totalbill+$PeakFare;
						   } */
					//****************************************************
				 $query1 = "SELECT * FROM `tblbookingbill` WHERE BookingTypeId='$booking' AND CabType='$carTypes' AND CityId='$stateId'";
		 $ReCorD =mysqli_fetch_assoc(mysqli_query($this->con,$query1));
		 $per_km_charge=$ReCorD["Per_Km_Charge"];
		 $min_distance=$ReCorD["Min_Distance"];
		 $wait_charge=$ReCorD["WaitingCharge_per_minute"];
		 $waiting_min=$ReCorD["Waitning_minutes"];
		 $SQL = "update tblcabbooking SET CabFor='$Min_Pkg_Hrs', 
										 estimated_price='$totalbill',
										 approx_distance_charge='$per_km_charge',
										 `approx_after_km`='$min_distance',
										 `approx_waiting_charge`='$wait_charge',
										 appox_waiting_minute='$waiting_min',
										 min_Distance='$Min_Pkg_KM',
										 local_subpackage='$cablocalfor',
										 PrometionalCode='$CouponName',
										 PrometionalName='$PromotionName'
										 
				WHERE ID='$new'";
		mysqli_query($this->con,$SQL);
		//******************end subpackage info**************/
		/* $data=$this->Estimated_Price($distance, $new);     //call function
		$totalbill=$data['tripInfo']['totalbill'];
		$min_distance=$data['tripInfo']['min_distance'];
		$per_km_charge=$data['tripInfo']['per_km_chg'];
		$waiting_min=$data['tripInfo']['waiting_minutes'];
		$wait_charge=$data['tripInfo']['wait_charge'];
		file_put_contents('pointbill.txt',$totalbill);
		$SQL = "update tblcabbooking SET estimated_price='$totalbill',
										 approx_distance_charge='$per_km_charge',
										 `approx_after_km`='$min_distance',
										 `approx_waiting_charge`='$wait_charge',
										 appox_waiting_minute='$waiting_min'  
				WHERE ID='$new'";
		mysqli_query($this->con,$SQL); */
			if(isset($_REQUEST["isTravellerDetails"])){
			$Traveller_Name=$_REQUEST['Traveller_Name'];
			$Traveller_Mobile=$_REQUEST['Traveller_Mobile'];
			$Traveller_Note=$_REQUEST['Traveller_Note'];
			$this->BookForThirdPerson($Traveller_Name,$Traveller_Mobile,$Traveller_Note,$UserId,$carTypes,$booking,$new);			
		    } 
            /*  $flag="booking";
		    $booking_id=$new.':'.$flag;
		    $this->send_sms($booking_id);  */			
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
	
               
	public function pointBooking(){		
        /* $token='b2c9c919aa82042433fe9b50ee257af1';
        if(isset($token)){
			//$token=$_POST['token'];
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
			$userNames = "shyam";
			$mobileNo = $_POST['mobileNumbers'];
			$emailIds =$_POST['emailId'];
		}	
		$DeviceType="ANDROID";
		$user_id="";             
		$bookingType = 102;
		$peakTimeServices = "ANDROID"=="ANDROID" ? "false" : "false";
		$pointCabin="New Delhi";
		$carType=1;
		$pointNationality="India";
		$pointAdults="";
		$pointChilds="";
		$pointLuggages="";
		$pointPickuparea="T2 Airport";                        
		$is_pickup=mysqli_num_rows(mysqli_query($this->con,"SELECT * FROM rt_locations WHERE area='$pointPickuparea'"));
		$pointDroparea="1 Guru Ravi Das Marg New Delhi India";
		$is_drop=mysqli_num_rows(mysqli_query($this->con,"SELECT * FROM rt_locations WHERE area='$pointDroparea'"));
		$pointAddress="T2 Airport New Delhi India";	
		$origin=explode(',',"28.5549868,77.084482999");
		$destiny=explode(',',"28.6582372,77.1894085");
		$pointdate=''; */
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
		$PromotionName=$_POST['PromotionName'];
		$CouponName=$_POST['CouponName'];
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
		$no_of_rows=mysqli_query($this->con,"SELECT * FROM tbluser WHERE LoginName='$emailIds' LIMIT 0,1");
		if(mysqli_num_rows($no_of_rows)==0){
			mysqli_query($this->con,"INSERT INTO tbluser (`LoginName`,`UserNo`) VALUES('$emailIds','$mobileNo')");
			$user_id=mysqli_insert_id($this->con);
			mysqli_query($this->con,"INSERT INTO tbluserinfo(`FirstName`,`UID`,`MobNo`,`Email`) VALUES('$userNames','$user_id','$mobileNo','$emailIds')"); 
		}else{
			$result_new=mysqli_fetch_array($no_of_rows);
			$user_id=$result_new['ID'];
		}
		//$API_MAP = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$origin[0].",".$origin[1]."&destinations=".$destiny[0].",".$destiny[1]."&mode=driving&language=en-US&sensor=false";
		$API_MAP = "https://maps.googleapis.com/maps/api/directions/json?origin=".$origin[0].','.$origin[1]."&destination=".$destiny[0].','.$destiny[1]."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584";
		$data= file_get_contents($API_MAP);	 
		$enc=json_decode($data);
		if($enc->status == 'OK'){
			$enc2=$enc->routes[0];
			$distance=round((($enc2->legs[0]->distance->value)/1000),1);
		}else{
			return array('Status'=>'false', 'msg'=>'Please enter valid Address');
		}
		$data  = array(
			'DeviceType'=>"$DeviceType",
			'BookingType'=>"$bookingType",
			'CabIn' => "$pointCabin",
			'CarType' => "$carType",
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
			'DropArea' => "$pointDroparea",
			'PickupAddress' => "$pointAddress",
			'PickupDate' => "$pointdate",
			'PickupTime' => "$pickupTime",
			'BookingDate' => "$dateOfBooking",
			'EstimatedDistance'=>"$distance",
			'PickupLatitude'=>$origin[0], 		
			'PickupLongtitude'=>$origin[1],
			'DestinationLatitude'=>$destiny[0], 		
			'DestinationLongtitude'=>$destiny[1], 
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
				$resulddd=mysqli_query($this->con,"CALL `new_check`('$booking_id', 'HA')");
			}else{
				$resulddd=mysqli_query($this->con,"CALL `new_check`('$booking_id', 'HW')");
			}			
			$booking_ref=mysqli_fetch_assoc($resulddd);
			mysqli_free_result($resulddd); 
			mysqli_next_result($this->con);
			$data= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$origin[0].','.$origin[1]."&destination=".$destiny[0].','.$destiny[1]."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584");
			$enc=json_decode($data);			
			if($enc->status == 'OK'){
				$enc2=$enc->routes[0];
				$distance2=round((($enc2->legs[0]->distance->value)/1000),1);
				$distance=$distance2;
				$query = "SELECT * FROM `tblcity` WHERE name='$pointCabin'";
		        $rowDATA =mysqli_fetch_assoc(mysqli_query($this->con,$query));	
		         $cityId=$rowDATA["state"];
				//$data=$this->Estimated_Price($distance, $booking_id);
				$query1 = "SELECT * FROM `tblbookingbill` WHERE BookingTypeId='$bookingType' AND CabType='$carType' AND CityId='$cityId'";
		      $data =mysqli_fetch_assoc(mysqli_query($this->con,$query1));			       
				/***********************************Calculate Fare*********/
				    $sqlite = "SELECT * FROM `tblmasterpackage` where Package_Id='$bookingType'"; 
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
						}elseif($subpackage['Sub_Package_Id'] == 2){
							$EstimatedPrice = $totalmint*$data["tripCharge_per_minute"];
						}elseif($subpackage['Sub_Package_Id'] == 3){
							$EstimatedPrice_PerKm = $distance*$data["rate_per_km_dh"];
							$EstimatedPrice_PerHr = $totalmint*$data["rate_per_hour_dh"];
							if($EstimatedPrice_PerKm > $EstimatedPrice_PerHr){
								$EstimatedPrice = $EstimatedPrice_PerKm;
							}else{
								$EstimatedPrice = $EstimatedPrice_PerHr;
							}
							if($data["minimum_fare_dh"] > $EstimatedPrice){
								$EstimatedPrice = $data["minimum_fare_dh"];
							}
						}elseif($subpackage['Sub_Package_Id'] == 4){
							$EstimatedPrice = $distance * $data['rate_per_km_dw'];
							if($data['minimum_fare_dw'] > $EstimatedPrice){
								$EstimatedPrice = $data['minimum_fare_dw'];
							}
						}
						if($data["MinimumCharge"] > $EstimatedPrice){
								$EstimatedPrice = $data["MinimumCharge"];
							}
					//***************CheckPeakTime charges****************						
					   $query = "SELECT * FROM `tblpeaktime` WHERE ('$pickupTime' BETWEEN timeFrom AND timeTo)";
						   $fetch = mysqli_query($this->con,$query);
						   if(mysqli_num_rows($fetch)>0){
							   $vaLue=mysqli_fetch_assoc($fetch);
								  $PeakChargPercent=$vaLue["peakCharges"];
								  $PeakFare=$EstimatedPrice/$PeakChargPercent;
								  $EstimatedPrice=$EstimatedPrice+$PeakFare;
						   }					
				/*********************Calculate Extra Charges*****************************************/	
					//****Basic Tax
				   $BasicTax = ($EstimatedPrice*$data['basic_tax'])/100;				
				   $totalbill = $EstimatedPrice + $BasicTax;				   
		        /***********************************************************************/	
			}else{
				return array('Status'=>'false', 'msg'=>'Please enter valid Address');
			}
			/* $totalbill=$data['tripInfo']['totalbill'];
			$min_distance=$data['tripInfo']['min_distance'];
			$per_km_charge=$data['tripInfo']['per_km_chg'];
			$waiting_min=$data['tripInfo']['waiting_minutes'];
			$wait_charge=$data['tripInfo']['wait_charge'];
			file_put_contents('pointbill.txt',$totalbill); */
			
		 $per_km_charge=$data["Per_Km_Charge"];
		 $min_distance=$data["Min_Distance"];
		 $wait_charge=$data["WaitingCharge_per_minute"];
		 if($bookingType == '103')
		 {
			// echo $distance;
			// $totalbill = "hello";
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
				$min_distance = $info['MinKm'];
			    $totalbill = $info['MinFare'];
			}
			else
			{
				$min_distance = $info['MaxKm'];
			    $totalbill = $info['MaxFare'];
			}
			
		 }
		
		 
		 $waiting_min=$data["Waitning_minutes"];		
			mysqli_query($this->con,"update tblcabbooking SET estimated_price='$totalbill',approx_distance_charge='$per_km_charge',
			`approx_after_km`='$min_distance',`approx_waiting_charge`='$wait_charge',appox_waiting_minute='$waiting_min',
			min_Distance='$min_distance',PrometionalCode='$CouponName',PrometionalName='$PromotionName' WHERE ID='$booking_id'");
			mysqli_query($this->con,"INSERT INTO tblbookinglogs(bookingid,`status`,message,`time`) VALUES('$booking_id',1,'Requesting car',NOW())");
			mysqli_query($this->con,"INSERT INTO `booking_stack`(`booking_id`,`lat`,`long`,`status`) VALUES('$booking_id','".$origin[0]."','".$origin[0]."','')") or die(mysqli_error($this->con)); 
			mysqli_query($this->con,"INSERT INTO `tblbookingtracker` (`BookingID`,`Latitutude`,`Logitude`,`Date_Time`,`CabStatus`) 
			VALUES('$booking_id','".$origin[0]."','".$origin[0]."',NOW(),'1')");
			
			/* $flag="booking";
		    $booking_id=$booking_id.':'.$flag;
		    $this->send_sms($booking_id); */
			$this->send_sms_new($booking_id);   
			$retrurn =  array("Status" => "Success","per_km_charge"=>$per_km_charge, "ref"=>$booking_ref['generated'],"price"=>$totalbill,"pickupTime"=>$pickupTime,"Pickupdate"=>$newdate,"succMess"=>"Your booking has been confirmed.");
		}else{
			$retrurn = array("Status" => "false", 'succMess'=>'Record not saved successfully.');
		}
		return $retrurn;
	}
	
	 public function BookForThirdPerson($Traveller_Name,$Traveller_Mobile,$Traveller_Note,$UserID,$carType,$bookingType,$booking_id){
		mysqli_query($this->con,"INSERT INTO `tbltraveller` set UID='$UserID',Booking_Id='$booking_id',Name='$Traveller_Name',Mobile='$Traveller_Mobile',
		Note='$Traveller_Note',CarType='$carType',BookingType='$bookingType'");
			$last_id=mysqli_insert_id($this->con);
			return true;
	} 

	public function tr(){
		$pointPickuparea="pratap vihar ghaziabad";
		$find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($pointPickuparea));
		file_put_contents("json.txt",$find_pickup); 					
		$enc=json_decode($find_pickup);
		$lat=$enc->results[0]->geometry->location->lat;
		$long=$enc->results[0]->geometry->location->long;
		echo $lat;
		echo $long;
	}

	public function SendSMS($mobile,$msg){
		/* echo "hello";
		$mobile='9650465338';
		$msg='HELLO'; */
	    $msg = urlencode($msg);
		//file_get_contents("http://push1.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$MobileNumber&from=Helocb&dlrreq=true&text=$msg&alert=1");
		file_get_contents("http://push3.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$mobile&from=Helocb&dlrreq=true&text=$msg&alert=1");
		return true;		
	}
	
	
	
public function send_sms(){
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
		$this->send_sms_new2($booking_id,$msg_sku);
		
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
			$sql="select * from tblenquiry where ID = '$booking'"; //die;
			$result=mysqli_query($this->con,$sql);
			$fetch=mysqli_fetch_assoc($result);
			$CallerName=$fetch['CallerName'];
			$Enquiry_ref=$fetch['Enquiry_ref'];
			$mobile=$fetch['CallerId'];
			//$mobile="9313504429";
		}
		else
		{
			 $sql="SELECT tblcabbooking.estimated_price,tbldriver.ContactNo as drvrcntct, tblcabbooking.ClientID as uid,tblbookingbill.CabName as cabname,
	tblbookingbill.MinimumCharge as minimum,tblbookingbill.Min_Distance as min_distance,
	tblbookingbill.Per_Km_Charge as charge,tblbookingbill.WaitingCharge_per_minute,tblcablistmgmt.vehicleNumber, count(tblcablistmgmt.cabid) as count_result,tbldriver.FirstName, tbldriver.LastName, tbldriver.ContactNo, tblcabbooking.* 
	FROM tblcabbooking JOIN tblbookingbill ON tblbookingbill.BookingTypeId AND tblcabbooking.CarType=tblbookingbill.Id 
	inner join  tbldriver   on tblcabbooking.pickup=tbldriver.UID 
	inner join  tblcablistmgmt  on tbldriver.vehicleId = tblcablistmgmt.cabid  WHERE tblcabbooking.id='$booking_id' limit 1";
			$result=mysqli_query($this->con,$sql);
			$fetch=mysqli_fetch_assoc($result);
			$mobile=$fetch['MobileNo'];

			// $bokstatus=$fetch['Status'];
			$bokstatus=($val[2]!="")?$val[2]:0;

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
			$Driver_ContactNo=$fetch['drvrcntct'];
			$Driver_pickup=$fetch['pickup'];
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
		$array[2]=$array[2]."http://www.hello42cab.com/";
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
		$array[0]=$array[0].$client;
		$array[1]=$array[1].$booking_ref;
		$array[2]=$array[2].$pickup_time;
		$array[4]=$array[4].$pickup_time;
		$text=  urlencode(implode("",$array));	
		}
		elseif($msg_sku=="reassign")
		{
		$array[0]=$array[0].$client;
		$array[1]=$array[1].$booking_ref;
		$array[2]=$array[2].$driverName;
		$array[4]=$array[4].$Driver_ContactNo;
		$array[2]=$array[2].$driverName;
		$array[4]=$array[4].$Driver_ContactNo;
		$text=  urlencode(implode("",$array));	
		}
		elseif($msg_sku=="booking")
		{
			//echo "Mohit"; die;
		$array[0]=$array[0].$client;
		$array[1]=$array[1].$booking_ref;
		$array[2]=$array[2].$pickup_time;
		$array[3]=$array[3].$fetch['estimated_price'];
		$array[4]=$array[4].$fetch['minimum'];
		$array[5]=$array[5].$charge;
		$array[6]=$array[6].$fetch['WaitingCharge_per_minute'];
		$text=  urlencode(implode("",$array));	
		}

		
		
		file_put_contents("mssg.txt",$text);
		$url="http://push3.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$mobile&from=Helocb&dlrreq=true&text=$text&alert=1";
		
		file_get_contents($url);
		
		//for driver cancel message notification . Notification should be sent if booking status is Accepted,Located,Reported, 

if($Driver_pickup > 0 && ( $bokstatus==3 || $bokstatus==4 || $bokstatus==5 ))
		{
		$array[0]=$array[0].$driverName;
		$array[1]=$array[1].$booking_ref;
		$text=  urlencode(implode("",$array));	
		
		file_put_contents("mssg.txt",$text);

		$url1="http://push3.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$Driver_ContactNo&from=Helocb&dlrreq=true&text=$text&alert=1";
		file_get_contents($url1);
// mysqli_query($this->con,"INSERT INTO `tblsmsstatus`(`UID`,`mesg`,`ContactNo`,`status`) VALUES('$uid','Thankyou for choosing hello42 cabs','$mobile','1')");
		}

//end here

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
		//$this->mailing_new($email,$message,"Congratulation","Hello42@cab.com");
		return array('status'=>true);
	}
	
	public function send_sms_new2($BookingId,$flag){
        //$BookingId=$_REQUEST["BookingId"];
		//$BookingId = "6702";
		//$flag = "cancel";
		//$flag=$_REQUEST["flag"];
		
		/// Sitting Driver Idle Booking Status Starts Here /////
		if($flag=='accept' || $flag=='reassign' || $flag=='intercity_booking'){
			$booking_status=1;
		}elseif($flag=='invoice' || $flag=='reassign_cancel'){
			$booking_status=0;
		}
		/// Sitting Driver Idle Booking Status Ends Here /////
		$result=mysqli_query($this->con,"SELECT tblbookingcharges.total_price,tblcabbooking.estimated_price, tblcabbooking.ClientID as uid,tblbookingbill.CabName as cabname,
tblbookingbill.MinimumCharge as minimum,tblbookingbill.Min_Distance as min_distance,
tblbookingbill.Per_Km_Charge as charge,tblbookingbill.WaitingCharge_per_minute,tblcablistmgmt.vehicleNumber, count(tblcablistmgmt.cabid) as count_result,tbldriver.FirstName, tbldriver.LastName, tbldriver.ContactNo, tblcabbooking.* 
FROM tblcabbooking JOIN tblbookingbill ON tblbookingbill.BookingTypeId AND tblcabbooking.CarType=tblbookingbill.Id 
inner join  tbldriver   on tblcabbooking.pickup=tbldriver.UID 
inner join  tblcablistmgmt  on tbldriver.vehicleId = tblcablistmgmt.cabid 
LEFT join tblbookingcharges on tblcabbooking.ID=tblbookingcharges.BookingID  WHERE tblcabbooking.id='$BookingId' order by tblbookingcharges.id desc limit 1");
		
		$fetch=mysqli_fetch_assoc($result);
		$invoice_total_price=$fetch['total_price'];
		$mobile=$fetch['MobileNo'];
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
		$msg_query=mysqli_fetch_array(mysqli_query($this->con,"SELECT * FROM tbl_sms_template WHERE msg_sku='$flag'"));
		
		$array=explode('<variable>',$msg_query['message']);
		
		if($flag == "reporting")
		{
			if($count_result == '0')
			{
				$array[0]=$array[0].$client;
				$array[1]=$array[1]."No Assigned Cab";
				$array[2]=$array[2]."http://www.hello42cab.com/";
				$text=  urlencode(implode("",$array));
			}
			else{
				$array[0]=$array[0].$client;
				$array[1]=$array[1].$vehicleNumber;
				$array[2]=$array[2]."http://www.hello42cab.com/";
				$text=  urlencode(implode("",$array));
			}
		}
		else if($flag == "accept")
		{
			if($count_result == '0')
			{
				$array[0]=$array[0].$client;
				$array[1]=$array[1]."NULL";
				$array[2]=$array[2]."NULL";
				$array[3]=$array[3]."No Assigned Cab";
				$array[4]=$array[4].$booking_ref;
				$array[5]=$array[5].$pickup_time;
				$text=  urlencode(implode("",$array));
			}
			else 
			{
				$array[0]=$array[0].$client;
				$array[1]=$array[1].$driverName;
				$array[2]=$array[2].$Driver_ContactNo;
				$array[3]=$array[3].$vehicleNumber;
				$array[4]=$array[4].$booking_ref;
				$array[5]=$array[5].$pickup_time;
				$text=  urlencode(implode("",$array));
			}
		}
		else if($flag == "invoice")
		{
		$array[0]=$array[0].$client;
		$array[1]=$array[1].$booking_ref;
		$array[2]=$array[2].$invoice_total_price;
		$text=  urlencode(implode("",$array));	
		}
		///// start_trip,
		else
		{
		$array[0]=$array[0].$client;
		$array[1]=$array[1].$booking_ref;
		$array[2]=$array[2]."http://www.hello42cab.com/";
		$text=  urlencode(implode("",$array));	
		}
		
		//file_put_contents("mssg.txt",$text);
		$url="http://push3.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$mobile&from=Helocb&dlrreq=true&text=$text&alert=1";
		
		file_get_contents($url);
		return true;		
	}
	
	
	public function send_sms_new($booking=0){
		$booking_id="";
		if($booking==0){
			$booking_id=$_REQUEST['booking_id'];
		}else{
			$booking_id=$booking;
		}
		$result=mysqli_query($this->con,"SELECT tblcabbooking.estimated_price, tblcabbooking.ClientID as uid,tblbookingbill.CabName as cabname,tblbookingbill.MinimumCharge as minimum,tblbookingbill.Min_Distance as min_distance,tblbookingbill.Per_Km_Charge as charge,tblbookingbill.WaitingCharge_per_minute,tblcabbooking.* FROM tblcabbooking JOIN tblbookingbill ON tblbookingbill.BookingTypeId AND tblcabbooking.CarType=tblbookingbill.Id  WHERE tblcabbooking.id='$booking_id
		'");
		$fetch=mysqli_fetch_assoc($result);
		$mobile=$fetch['MobileNo'];
		$mobile="9015230173";
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
		$fair="";
		if($distance<$minimum_distance){
			$fair=$minimum_charge;
		}else{
			$fair=$minimum_charge+($distance-$minimum_distance)*$charge;
		}
		$msg_query=mysqli_fetch_array(mysqli_query($this->con,"SELECT * FROM tbl_sms_template WHERE msg_sku='booking'"));
		
		$array=explode('<variable>',$msg_query['message']);
		$array[0]=$array[0].$client;
		$array[1]=$array[1].$booking_ref;
		$array[2]=$array[2].$pickup_time;
		$array[3]=$array[3].$fetch['estimated_price'];
		$array[4]=$array[4].$fetch['minimum'];
		$array[5]=$array[5].$charge;
		$array[6]=$array[6].$fetch['WaitingCharge_per_minute'];
		$text=  urlencode(implode("",$array));	
		file_put_contents("mssg.txt",$text);
		//$url="http://push1.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$mobile&from=Helocb&dlrreq=true&text=$text&alert=1";
		
		$url="http://push1.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=919015230173&from=Helocb&dlrreq=true&text=AjaydataTest&alert=1";
		
		//echo $url; die;
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
	
	public function cancelBookingByUser(){
		$BookingId = 0;
		if(isset($_REQUEST['BookingId'])){
			$BookingId = $_REQUEST['BookingId'];
		}
		if($BookingId){
			$sql = "UPDATE tblcabbooking set `BookingStatus` = '0' WHERE ID = '".$BookingId."' AND Status < 5";
			$qry = mysqli_query($this->con, $sql) or die(mysqli_error($this->con));
			if($qry){
				$Status = true;
				$msg = 'Booking successfully canceled.';
			}else{
				$Status = false;
				$msg = 'You can not cancel this booking.';
			}
		}else{
			$Status = false;
			$msg = 'Oops ! Booking Id is blank.';
		}
		$data = array('Status'=>$Status, 'msg'=>$msg);
		return $data;
	}

	
	public function try_me()
	{
		  $msg_query=mysqli_fetch_array(mysqli_query($this->con,"SELECT * FROM tbl_sms_template WHERE msg_sku='booking'"));
        $array=explode('<variable>',$msg_query['message']);
        $array[0]=$array[0]."hello42";
        $array[1]=$array[1]."hello42";
        $array[2]=$array[2]."hello42";
        $array[3]=$array[3]."hello42";
        $array[4]=$array[4]."hello42";
        $array[5]=$array[5]."hello42";
        $array[6]=$array[6]."hello42";
		echo "<pre>";
                print_r($array);
                echo "</pre>";
                echo implode("",$array);
		
	}
	
	
	public function airportBookingto(){
                $status = 0;
                if(isset($_POST['airDropLocation'])){
                                            $status = 1;
                                        }
                                         if(isset($_POST['token'])){
                                        $result =mysqli_query($this->con,"SELECT `LoginName`,`UserNo` FROM `tbluserinfo` WHERE `token` = '$token'") or die(mysqli_error($this->con));
                                            $row = mysqli_fetch_array($result);
                                            $localname = $row['FirstName'].$row['LastName'];
                                            $localPhone = $row['MobNo'];
                                            $localEmail = $row['Email'] ;      
                                        }
                                        
					$bookingType = $_POST['bookingType'];
					$airNationalityData=$_POST['airnationality'];
					$airAdultsData=$_POST['airadults'];
					$airChildsData=$_POST['airchilds'];
					$airLuggagesData=$_POST['airluggages'];
					$airFlightNoData=$_POST['airflightno'];
				        $airAirportData=$_POST['airairport'];
					$airPickuplocationData=$_POST['airpickuplocation'];
					$airDropLocation=$_POST['airDropLocation'];
					$airLandmarkData=$_POST['airlandmark'];
					$airpickupAddressData=$_POST['airpickupaddress'];
					$airDropAddress=$_POST['airDropAddress'];
					$airPickupDate=$_POST['airpickupdate'];
					$airpickuptimeH=$_POST['airpickuptimeH'];
					$airpickuptimeS=$_POST['airpickuptimeS'];
	
	
			$data  = array(
							
							'BookingType'=>"$bookingType",
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
							'PickupTime' => "$airpickuptimeH".":"."$airpickuptimeS",
							'partner'=>1,
							'status'=>1
							
		
						);
                        
                        
                        if($status ==0){
                                    $find_address=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($airpickupAddressData));
						$enc3=json_decode($find_address);
						$origin0=$enc3->results[0]->geometry->location->lat;
						$origin1=$enc3->results[0]->geometry->location->lng;
						
                        
                                $result = mysqli_query($this->con,"SELECT * FROM rt_locations where `area` ='$airPickuplocationData')");
                                $row = mysqli_fetch_assoc($result);
                                $num = mysqli_num_rows($result);
                            if($num ==0){
                                 
                                   $find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($airPickuplocationData));
                                 file_put_contents("json.txt",$find_pickup); 					
					$enc=json_decode($find_pickup);
				
				$lat=$enc->results[0]->geometry->location->lat;
				$long=$enc->results[0]->geometry->location->lng;
                                foreach($enc2->results[0]->address_components as $v)
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
                                        . " VALUES('$airPickuplocationData','$area','$lat','$long','$zone','$country','$state')");
                            
                           }
                           
                             $result = mysqli_query($this->con,"SELECT * FROM rt_locations where `area` ='$airAirportData')");
                                $row = mysqli_fetch_assoc($result);
                                $num = mysqli_num_rows($result);
                               if($num ==0){
                           $find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($airAirportData));
                       			$enc2=json_decode($find_pickup);
					
				$lat=$enc2->results[0]->geometry->location->lat;
				$long=$enc2->results[0]->geometry->location->lng;
				$destiny0=$lat;
				$destiny1=$long;
                               }else{
                                   $destiny0=$row['lat'];
				   $destiny1=$row['lon'];
                               }
                           
                        }else{
                                 $find_address=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($airAirportData));
						$enc3=json_decode($find_address);
						$origin0=$enc3->results[0]->geometry->location->lat;
						$origin1=$enc3->results[0]->geometry->location->lng;
						
                        
                                $result = mysqli_query($this->con,"SELECT * FROM rt_locations where `area` ='$airDropLocation')");
                                $row = mysqli_fetch_assoc($result);
                                $num = mysqli_num_rows($result);
                            if($num ==0){
                                 
                                   $find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($airDropLocation));
                                 file_put_contents("json.txt",$find_pickup); 					
					$enc=json_decode($find_pickup);
				
				$lat=$enc->results[0]->geometry->location->lat;
				$long=$enc->results[0]->geometry->location->lng;
                                foreach($enc2->results[0]->address_components as $v)
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
                                        . " VALUES('$airDropLocation','$area','$lat','$long','$zone','$country','$state')");
                            
                           }
                            
                        
                    
                             $result = mysqli_query($this->con,"SELECT * FROM rt_locations where `area` ='$airDropAddress')");
                                $row = mysqli_fetch_assoc($result);
                                $num = mysqli_num_rows($result);
                               if($num ==0){
                           
                                $find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($airDropAddress));
                       			$enc2=json_decode($find_pickup);
					
				$lat=$enc2->results[0]->geometry->location->lat;
				$long=$enc2->results[0]->geometry->location->lng;
				$destiny0=$lat;
				$destiny1=$long;                               
                               
				$area="";
				//print_r($enc->results[0]->address_components);
				foreach($enc2->results[0]->address_components as $v)
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
                                        . " VALUES('$airDropAddress','$area','$lat','$long','$zone','$country','$state')");
                               }else{
                                   $destiny0=$row['lat'];
				    $destiny1=$row['lon'];
                               
                               }
                           
			}		
                                         
	if(mysqli_insert_id($this->con) > 0){
                                              $resulddd=mysqli_query($this->con,"CALL `new_check`('$new', 'HW')");
                                              $booking_ref=mysqli_fetch_assoc($resulddd);
                                              mysqli_free_result($resulddd); 
                                          
				           $tableName = 'tblcabbooking';


		}

		if(mysqli_insert_id($this->con) > 0){
			$resulddd=mysqli_query($this->con,"CALL `new_check`('$new', 'HW')");
			$booking_ref=mysqli_fetch_assoc($resulddd);
			mysqli_free_result($resulddd);

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




				mysqli_next_result($this->con);
				$data=$this->estimatedprice($distance, $booking_id);

				$totalbill=$data['tripInfo']['totalbill'];
				$min_distance=$data['tripInfo']['min_distance'];
				$per_km_charge=$data['tripInfo']['per_km_chg'];
				$waiting_min=$data['tripInfo']['waiting_minutes'];
				$wait_charge=$data['tripInfo']['wait_charge'];
				file_put_contents('pointbill.txt',$totalbill);

//             if(isset($_POST['token'])){
//             $row = mysqli_query($this->con,"select * from tbluser JOIN tbluserinfo ON tbluser.ID=tbluserinfo.UID WHERE `token`='$token'");
//             $data = mysqli_fetch_array($row);
//             
//             }

				mysqli_query($this->con,"update tblcabbooking SET estimated_price='$totalbill',approx_distance_charge='$per_km_charge',`approx_after_km`='$min_distance',`approx_waiting_charge`='$wait_charge',appox_waiting_minute='$waiting_min'  WHERE ID='$booking_id'");
				mysqli_query($this->con,"INSERT INTO tblbookinglogs(bookingid,`status`,message,`time`) VALUES('$booking_id',1,'Requesting car',NOW())");
				//  echo "INSERT INTO booking_stack(booking_id,lat,long,status) VALUES('$booking_id','".$origin[0]."','".$origin[0]."','')";


				mysqli_query($this->con,"INSERT INTO `booking_stack`(`booking_id`,`lat`,`long`,`status`) VALUES('$booking_id','".$origin[0]."','".$origin[0]."','')") or die(mysqli_error($this->con));

				mysqli_query($this->con,"INSERT INTO `tblbookingtracker` (`BookingID`,`DriverId`,`Latitutude`,`Logitude`,`Date_Time`,`CabStatus`)
      VALUES('$booking_id','121','".$origin[0]."','".$origin[0]."',NOW(),'1')");
				$this->send_sms($booking_id);


				return array("Status" => "Success", "ref"=>$booking_ref['generated'],"price"=>$totalbill,"pickupTime"=>$data['PickupTime'],"Pickupdate"=>$airPickupDate);
			}else{
				return array("Status" => "Unsuccess");
			}

		}


	}
	

	public function outstationTrip(){
		$DeviceType = $_POST['DeviceType'];
		$carType = $_POST['carType'];
		$bookingType = $_POST['bookingType'];
		$outfromData=$_POST['outfrom'];
		$outtoData=$_POST['outto'];
		$outDepdateData=$_POST['outDepdate'];
		$outRdateData=$_POST['outRdate'];
		$outNationalityData=$_POST['outNationality'];
		$outAdultsData=$_POST['outAdults'];
		$outChildsData=$_POST['outChilds'];
		$outLuggagesData=$_POST['outLuggages'];
		$outPickupAddressData=$_POST['outPickupAddress'];
		$outPickupH=$_POST['outPickupH'];
		$outPickupM=$_POST['outPickupM'];

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
		$data  = array(

			'BookingType' =>"$bookingType",
			'carType' =>"$carType",
			'PickupArea' => "$outfromData",
			'DropArea' => "$outtoData",
			'PickupDate' => "$outDepdateData",
			'ReturnDate' => "$outRdateData",
			'Nationality' => "$outNationalityData",
			'No_Of_Adults' => "$outAdultsData",
			'No_Of_Childs' => "$outChildsData",
			'No_Of_Luggages' => "$outLuggagesData",
			'PickupAddress' => "$outPickupAddressData",
			'PickupTime' => "$outPickupH".":"."$outPickupM",
			'UserName' => "$userNames",
			'MobileNo' => "$mobileNo",
			'EmailId' => "$emailIds",
			'DeviceType' => "$DeviceType",
			'ClientID'	=> "$userId",
			'status'=>1,
			'partner'=>1


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
		$new=mysqli_insert_id($this->con);
		if(mysqli_insert_id($this->con) > 0){
			$resulddd=mysqli_query($this->con,"CALL `new_check`('$new', 'HW')");
			$booking_ref=mysqli_fetch_assoc($resulddd);

			mysqli_free_result($resulddd);
			return array("Status" => "Success","id"=>$userId,"code"=>"001","ref"=>$booking_ref['generated'],"pickupTime"=>$data['PickupTime'],"Pickupdate"=>$outDepdateData,"Returndate"=>$outRdateData);
		}else{
			return array("Status" => "Unsuccess");
		}

	}

	
	///////////////////////////////
	
	public function setlocation1(){

		$user_id= $_REQUEST['driverId'];
		$booking= $_REQUEST['book_id'];
		$lat= $_REQUEST['latitude'];
		$long= $_REQUEST['longitude'];
		$time= $_REQUEST['current_time'];
		$pre_Waiting_time= $_REQUEST['pre_Waiting_time'];
		$tripRunnStatus= $_REQUEST['tripRunnStatus'];
		$estimated_distance= $_REQUEST['Estim_Dist'];
		$estimated_time= $_REQUEST['Estim_Time'];
			
		// $user_id= 123;
		// $booking= 5555;
		// $lat= 88.3242334;
		// $long= 55.4242334;
		// $time= date("h:i:s");
		// $pre_Waiting_time= "00:00:35";
		// $tripRunnStatus= "Reported";
			

		// return array("Status"=>$status);

		$sql="select * from tbldriverlocation where user_id = '$user_id' order by id desc limit 1";
		$qry = mysqli_query($this->con,$sql);
		$data = mysqli_fetch_assoc($qry);			
		$distance = $this->getDistance($data[0]['lat'], $data[0]['longi'], $lat, $long, 'K');
		$WaitingTime = 0;
		if($distance == 0){
			$WaitingTime = $data[0]['WaitingTime'] + 5;
		}
$queryaa="INSERT INTO tbldriverlocation(`user_id`,`lat`,`longi`,`datetime`,`booking_id`,`distance`,`WaitingTime`,`pre_Waiting_time`,`tripRunnStatus`,`estimated_dist`,`estimated_time`) 
   VALUES('$user_id','$lat','$long','$time','$booking','$distance','$WaitingTime','$pre_Waiting_time','$tripRunnStatus','$estimated_distance','$estimated_time')"; 
$affected_rows=mysqli_query($this->con,$queryaa);
		if($affected_rows>0){
		$status="Success";
		}else{
		$status="False";
		}

		mysqli_query($this->con,"UPDATE tbluser SET Latitude='$lat',Longtitude1='$long' WHERE id='$user_id'");
			
			echo $lat;die;
		return array("Status"=>$status);
	}
	
	
	////////////////////////////////
	
	
	
	
	
	
	
	
	
	
	public function setlocation(){

//        $result=mysqli_query("SELECT Latitude as lat,Longitude1 as longi FROM tbluser WHERE id='$id'");
//        $data=mysqli_fetch_array($result);
//        $lat1=$data['lat'];
//        $long2=$data['longi'];
//                               mysqli_query($this->con,"UPDATE tbluser SET Latitude='$lat',Longtitude1='$long', loginTime=NOW() WHERE id='$id'");
//                               $distance=$this->distance($lat1,$long2 , $lat, $long, "K");
//							mysqli_query($this->con,"INSERT INTO tbldriverlocation(user_id,lat,longi,distance,booking_id) VALUES('$id','$lat','$long','$distance','$booking_id')");

	 	/* $DummyData = '{
    "upload": [
        {
            "driverId": "1770",
            "book_id": "",
            "tripRunnStatus": "aaa",
            "latitude": 28.6519328,
            "longitude": 77.1942326,
            "current_time": "2015-10-13 17:16:57"			
        },
		{
            "driverId": "1770",
            "book_id": "",
            "tripRunnStatus": "bbb",
            "latitude": 31.6519328,
            "longitude": 44.1942326,
            "current_time": "2015-10-13 17:16:10"
        }
    ]
}'; */
		$data= $_REQUEST['locData'];
		//file_put_contents('location.txt', $data);
		$data_new=json_decode($data,true);	
			$i=0;
       $status="False";		  
		foreach($data_new as $data_newsss)
		{			
			$user_id=$data_newsss[$i]['driverId'];
			$booking=$data_newsss[$i]['book_id'];
			$lat=$data_newsss[$i]['latitude'];
			$long=$data_newsss[$i]['longitude'];
			$time=$data_newsss[$i]['current_time'];
			$pre_Waiting_time=$data_newsss[$i]['pre_Waiting_time'];
            $tripRunnStatus=$data_newsss[$i]['tripRunnStatus']; 
			$sql="select * from tbldriverlocation where user_id = '$user_id' order by id desc limit 1";
			$qry = mysqli_query($this->con,$sql);
			$data = mysqli_fetch_assoc($qry);			
			$distance = $this->getDistance($data[0]['lat'], $data[0]['longi'], $lat, $long, 'K');
		/*$API_MAP = "https://maps.googleapis.com/maps/api/directions/json?origin=".$data[0]['lat'].','.$data[0]['longi']."&destination=".$lat.','.$long."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584";
		$data= file_get_contents($API_MAP);
		$enc=json_decode($data);
		if($enc->status == 'OK'){
			$enc2=$enc->routes[0];
			$distance=round((($enc2->legs[0]->distance->value)/1000),1);
			$distance=round($distance);
		}*/
			
			
			$WaitingTime = 0;
			if($distance == 0){
				$WaitingTime = $data[0]['WaitingTime'] + 5;
			}
           $queryaa="INSERT INTO tbldriverlocation(`user_id`,`lat`,`longi`,`datetime`,`booking_id`,`distance`,`WaitingTime`,`pre_Waiting_time`,`tripRunnStatus`) 
		   VALUES('$user_id','$lat','$long','$time','$booking','$distance','$WaitingTime','$pre_Waiting_time','$tripRunnStatus')"; 
		   $affected_rows=mysqli_query($this->con,$queryaa);
             if($affected_rows>0){
				 $status="Success";
			 }else{
				 $status="False";
			 }
			$i++;
		}
		mysqli_query($this->con,"UPDATE tbluser SET Latitude='$lat',Longtitude1='$long' WHERE id='$user_id'");

		return array("Status"=>$status);
	}

	public function getlocation(){
		$sql="SELECT * FROM tbluser WHERE loginStatus='1'";
		$query=mysqli_query($this->con,$sql);
		$array=array();
		$i=0;
		while($result= mysqli_fetch_assoc($query))
		{
			$name=mysqli_fetch_assoc(mysqli_query($this->con,"SELECT FirstName FROM tbluserinfo WHERE UID='".$result['ID']."'"));
			array_push($array,array('id'=>$result['ID'],'name'=>$name['FirstName'],'latitude'=>$result['Latitude'],'longitude'=>$result['Longtitude1']));


			//return $result;
			$i++;
		}
		//print_r($array);
		//print_r($array);
		if($i==0)
		{
			return array('status'=>'false');
		}else{
			return array('data'=>$array,'status'=>'true');
		}
	}

	public function outstationmulticity(){


		$multicityfromData=$_POST['multicityfrom'];
		$multicitytoData=$_POST['multicityto'];
		$multicityDepData=$_POST['multicityDep'];
		$multicityfrom1Data=$_POST['multicityfrom1'];
		$multicityto1Data=$_POST['multicityto1'];
		$multicityDep1Data=$_POST['multicityDep1'];



		$outNationalityData=$_POST['outNationality'];
		$outAdultsData=$_POST['outAdults'];
		$outChildsData=$_POST['outChilds'];
		$outLuggagesData=$_POST['outLuggages'];
		$outPickupAddressData=$_POST['outPickupAddress'];
		$outPickupH=$_POST['outPickupH'];
		$outPickupM=$_POST['outPickupM'];



		$data  = array(


			'PickupArea' => "$outfromData",
			'DropArea' => "$outtoData",
			'PickupDate' => "$outDepdateData",
			//'ReturnDate' => "$outRdateData",
			'Nationality' => "$outNationalityData",
			'No_Of_Adults' => "$outAdultsData",
			'No_Of_Childs' => "$outChildsData",
			'No_Of_Luggages' => "$outLuggagesData",
			'PickupAddress' => "$outPickupAddressData",
			'PickupTime' => "$outPickupH".":"."$outPickupM",


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
			return array("Status" => "Success");
		}else{
			return array("Status" => "Unsuccess");
		}

	}


		
		public function outstationmulticitySecond(){
						//echo "hi";
					$count = $_POST['countData'];
					$national = $_POST['nationalData'];
					$adults = $_POST['adultsdata'];
					$child= $_POST['childData'];
					$luggages = $_POST['luggagesData'];
                                        $cityFrom =  str_replace(array('[',']','/','"','\\'), "", $_POST['cityfromData']) ;
					//print_r($cityFrom);
					$cityDataF = explode(",", $cityFrom);
					$data= json_decode($cityFrom);
					file_put_contents('gmail.txt',print_r($cityFrom,true)."yfsh");
					$cityTo = $_POST['citytoData'];
					$address = $_POST['addsdata'];
					
					//$departure =  $_POST['cityDatedata'];
					
					$departure =  str_replace(array('[',']','"','\\'), "", $_POST['cityDatedata']);
					$date = explode(",", $departure);
					$hours = $_POST['timeHData'];
					$minute = $_POST['timeMData'];
					$bookdate = date('Y-m-d H:i:s');
					
	
					 for($i = 0; $i<$count;$i++){
					  $query ="INSERT INTO `tblcabbooking` (`Nationality`,`No_Of_Adults`,`No_Of_Childs`,`No_Of_Luggages`,`PickupCity`,`PickupAddress`,`DestinationCity`,`PickupTime`,`Departure`,`BookingDate`)
					         	VALUES ('$national','$adults','$child','$luggages','$cityDataF[$i]','$address','$cityDataT[$i]','$hours.$minute','$date[$i]','$bookdate')";
					         
					 $result = mysqli_query($this->con,$query);
					
					
					}
					 if(mysqli_insert_id($this->con) > 0){
					 	
					 return array("Status" => "Success");
						 
					 }else{
					 	
				return array("Status" => "Unsuccess");
				
				}
				
	}
	
	
	
	
	public function send_push_notification($message,$reg) {


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



	public function send_notification($registatoin_ids, $message) {
		// include config
		//include_once './config.php';
		//define("GOOGLE_API_KEY", "AIzaSyCOpOWLJG7G2PGHDb9uCl0eALEHYrPApVw");
		// Set POST variables
		define("GOOGLE_API_KEY", "AIzaSyBcsz_BY5Yg03KnXCbhDwXgOlnwch9kGXE");
		$url = 'https://android.googleapis.com/gcm/send';

		$fields = array(
			'registration_ids' => $registatoin_ids,
			'data' => $message,
		);

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
		echo $result;
	}
	
	
		



	public function allcities(){
		$data = array();
		$sql = "SELECT * FROM rt_locations WHERE `area` LIKE '%".$_REQUEST['term']."%' ORDER BY (CASE WHEN area = '%".$_REQUEST['term']."' THEN 1 WHEN area LIKE '".$_REQUEST['term']."%' THEN 2 ELSE 3 END),`area` LIMIT 0,10";
		$qry = mysqli_query($this->con, $sql);
		while($row = mysqli_fetch_assoc($qry)){
			$data[]=array(
				'id'=>$row['id'],
				"label"=>$row['area'],
				"value"=>$row['lat'].','.$row['lon'],
				'Country'=>$row['country'],
				'State'=>$row['state'],
				'City'=>$row['city'],
				'Zone'=>$row['zone']
			); 
		}
		echo json_encode($data);
		exit;
		//return $data;
	}
		
		
	public function allcities2(){
		$data = array();		
		$sql = "SELECT * FROM `rt_locations` WHERE `area` LIKE '%".$_REQUEST['term']."%' LIMIT 0,10";
		$qry = mysqli_query($this->con, $sql);
		while($row = mysqli_fetch_assoc($qry)){
			$data[]=array(
				'id'=>$row['id'],
				"label"=>$row['area'],
				"value"=>$row['lat'].','.$row['lon'],
				'Country'=>$row['country'],
				'State'=>$row['state'],
				'City'=>$row['city'],
				'Zone'=>$row['zone']
			); 
		}
		echo json_encode($data);
		exit;
		//return array($data);
	}
	
	public function outStationCities2(){
		$data = array();		
		$sql = "SELECT * FROM `tbloutstationcities` WHERE `cities` LIKE '%".$_REQUEST['term']."%' ORDER BY (CASE WHEN cities = '%".$_REQUEST['term']."' THEN 1 WHEN cities LIKE '".$_REQUEST['term']."%' THEN 2 ELSE 3 END),`cities` ";
		
		//$sql = "SELECT * FROM rt_locations WHERE `area` LIKE '%".$_REQUEST['term']."%' ORDER BY (CASE WHEN cities = '%".$_REQUEST['term']."' THEN 1 WHEN cities LIKE '".$_REQUEST['term']."%' THEN 2 ELSE 3 END),`cities` LIMIT 0,10";
		
		$qry = mysqli_query($this->con, $sql);
		while($row = mysqli_fetch_assoc($qry)){
			$data[]=array(
				'id'=>$row['id'],
				"label"=>$row['area'],
				"value"=>$row['cities'],
				'Country'=>$row['country'],
				'State'=>$row['state'],
				'City'=>$row['cities'],
				'Zone'=>$row['zone']
			); 
		}
		
		echo json_encode($data);
		exit;
		//return array($data);
	}
	
	public function favoriteAddressAndroid()
	{
		$token=trim($_REQUEST["token"]);
		$bookingId=trim($_REQUEST["bookingId"]);
	}
	public function calculatedistance($destiny=null,$list){

		foreach($list as $list)
		{
			$data= file_get_contents("http://maps.googleapis.com/maps/api/directions/json?origin=".$destiny."&destination=".$list['lat'].','.$list['long']."&sensor=false");
			//$data="http://maps.googleapis.com/maps/api/directions/json?origin=".$destiny."&destination=".$list['lat'].','.$list['long']."&sensor=false;
			$enc=json_decode($data);

			$enc2=$enc->routes[0];
			echo $enc2->legs[0]->duration->value.'<br>';

		}
		return $data;



	}
	public function find(){
		$latitude = "28.5641";  //your current lat
		$longitude = "77.3449"; //your current long


		$sql = 'SELECT ( 3959 * acos( cos( radians( '.$latitude.' ) ) * cos( radians( Latitude ) ) *
 cos( radians( Longitude1 ) - radians( '.$longitude.' ) ) + sin( radians( '.$latitude.' )
 ) * sin( radians( Latitude ) ) ) ) AS distance,LoginName,ID,Latitude,Longitude1 from tbluser WHERE userType=3 ORDER BY distance ASC';

		$result = mysqli_query($this->con, $sql);
		$list=array();
		while($data=  mysqli_fetch_array($result))
		{
			$list[]=array('id'=>$data['ID'],'lat'=>$data['Latitude'],'long'=>$data['Longitude1']);
		}
		$this->calculatedistance($latitude.",".$longitude,$list);

		return $data;

	}



	public function reverse_geocode() {

		$addlat = $_POST['currentLat'];
		$addlong = $_POST['currentlong'];
		$address = $addlat." ".$addlong;
		//$adds = $_REQUEST['adds'];

		///file_put_contents('loca.txt', $address);
		// $ad1 = str_replace(" ", "+", "$address");
		//$ad2 = str_replace(" ", "+", "$adds");

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

		return array("location"=>$locationcurrent_state,"country"=>$locationcurrent_country);

	}

	public function farestructure(){
	$cabFordata=array();
	$cityId='2';	
	$sql="SELECT tmp.Package_Id, tmp.Sub_Package_id, tmp.state_id, tmp.master_Package_ref , tbb.CabType, tbb.per_km_charge as DPKCharge,
	tbb.Min_distance as DIDistance ,tbb.MinimumCharge as DMCharge , tbb.tripCharge_per_minute as HPKCharge,tbb.ignore_first_hrs as HIDistance,
	tbb.minimum_hourly_Charge as HMCharge,tbb.rate_per_km_dw as DwPKCharge,tbb.minimum_distance_dw as DwIDistance,tbb.minimum_fare_dw as DwMCharge,
	tbb.rate_per_hour_dh as DhPKCharge,tbb.ignore_first_hrs_dh  as DhIDistance,tbb.minimum_fare_dh as DhMCharge FROM `tblmasterpackage` tmp 
	inner join `tblbookingbill` tbb on tmp.Package_id = tbb.BookingTypeId where tbb.cityId='$cityId' and tbb.CabType in (1,2,3) and tbb.BookingTypeId in (101 ,102 , 103, 104) order by tbb.BookingTypeId asc";
	$result = mysqli_query($this->con, $sql);
	$dataCabFor = mysqli_fetch_assoc($result);
	//print_r($dataCabFor);
	//echo $Sub_Package_Id=$dataCabFor->Sub_Package_id; die;
	//// Query to Fetch LocalPackage Data Starts Here /////
	
	$sql1 = "select Sub_Package_Id from tblmasterpackage WHERE Package_Id='101' AND state_id='$cityId'";
	$row1 = mysqli_query($this->con,$sql1);		
	$res = mysqli_fetch_array($row1);
	echo $Sub_Package_Id	=	$res['Sub_Package_Id'];
	
	
	$sql2 = "SELECT Package,cabType,Price FROM `tbllocalpackage` WHERE masterPKG_ID='101' AND Sub_Package_Id='$Sub_Package_Id' AND City_ID='$cityId' AND cabType in (1,2,3)"; //die;
	$row2 = mysqli_query($this->con,$sql2);		
	$num_rows = mysqli_num_rows($row2);
	if($num_rows>0){
	while($dataCabFor2 = mysqli_fetch_assoc($row2)){
	$cabFordata[] = $dataCabFor2;
	}
	
	
	}
		
		
	//// Query To Fetch LocalPackage Data Ends Here //////
	$package_type=""; //// local,PTP,Airport etc ...
	$package_cab_type=""; /// ECONOMY,SEDAN,PRIME
	
	$package_name=""; /// Package Title ....
	//local use
	$package_price="";
	$package_extrakm="";
	$package_extrahr="";
	//ptp use
	$rateupt_km="";
	$ratebey_km="";
	$charge_pm="";
	// Airport Use
	$upto_km="";
	// Outstaion Use
	$minimum_km="";
	$driver_allowance="";
		
	
	while($dataCabFor = mysqli_fetch_assoc($result)){
	$cabFordata[] = $dataCabFor;
	if($dataCabFor['Package_Id']=='101'){
		//$
	}
	}
	$data = array("cabFor"=>$cabFordata);
	return $data;
	}

	public function localfare(){
	$cityId='2';	
	$sql="SELECT tmp.Package_Id, tmp.Sub_Package_id, tmp.state_id, tmp.master_Package_ref , tbb.CabType, tbb.per_km_charge as DPKCharge,
	tbb.Min_distance as DIDistance ,tbb.MinimumCharge as DMCharge , tbb.tripCharge_per_minute as HPKCharge,tbb.ignore_first_hrs as HIDistance,
	tbb.minimum_hourly_Charge as HMCharge,tbb.rate_per_km_dw as DwPKCharge,tbb.minimum_distance_dw as DwIDistance,tbb.minimum_fare_dw as DwMCharge,
	tbb.rate_per_hour_dh as DhPKCharge,tbb.ignore_first_hrs_dh  as DhIDistance,tbb.minimum_fare_dh as DhMCharge FROM `tblmasterpackage` tmp 
	inner join `tblbookingbill` tbb on tmp.Package_id = tbb.BookingTypeId where tbb.cityId='$cityId' and tbb.CabType in (1,2,3) and tbb.BookingTypeId in (101 ,102 , 103, 104) order by tbb.BookingTypeId asc";
	$result = mysqli_query($this->con, $sql);
	$dataCabFor = mysqli_fetch_assoc($result);
	
	}

	public function alltext(){
		
		$state = $_REQUEST['stateId'];
		$country = $_REQUEST['countryId'];
		$city=$_REQUEST['cityId'];
		$sqlqry = mysqli_query($this->con,"SELECT * FROM tblcity where name='$city' and ncr=1");
		$rowDATA1 =mysqli_fetch_assoc($sqlqry);	
		 $city_id=$rowDATA1["id"];
		 $state_id=$rowDATA1["state"];
		
		$ncr=mysqli_num_rows($sqlqry);
		if($ncr==1 or $state=='Delhi'){
			$state="Delhi NCR";
			$sqlCab = "SELECT name as selected_region FROM `tblcity`  WHERE ncr='1'";
			$resultCab = mysqli_query($this->con, $sqlCab);
			$dataNoCab = mysqli_num_rows($resultCab);
			while($dataCab = mysqli_fetch_assoc($resultCab)){
				$citydataCab[] = $dataCab;
			}
		}else{
			$sqlCab = "SELECT a.name as selected_region FROM `tblcity` a INNER JOIN `tblstates` b ON a.state = b.id INNER JOIN `tblcountry` c ON b.country_code=c.id WHERE b.`state`='$state' AND c.CountryName='$country' and a.ncr=0";
			$resultCab = mysqli_query($this->con, $sqlCab);
			$dataNoCab = mysqli_num_rows($resultCab);
			while($dataCab = mysqli_fetch_assoc($resultCab)){
				$citydataCab[] = $dataCab;
			}
		}
		
		$sql = "SELECT a.text,a.text_id FROM `tblregiontext` a INNER JOIN `tblstates` b ON a.lang=b.language INNER JOIN `tbllanguage` c ON b.language= c.id INNER JOIN `tblcountry` d ON c.country_code=d.id  WHERE `state`='$state' AND d.`country_code`='$country' and page_title='home'";
		$sqlCabFor= "SELECT a.id, a.PackageName FROM `tblcabfor` a INNER JOIN `tblstates` b ON a.`language` = b.language INNER JOIN `tbllanguage` c ON b.language=c.id INNER JOIN `tblcountry` d ON c.country_code=d.id WHERE b.state='$state' AND d.country_code='$country'";
		$sqlnationality= "SELECT a.user_nationality FROM `tblnationality` a INNER JOIN `tblstates` b ON a.lang = b.language INNER JOIN `"."tbllanguage` c ON b.language=c.id INNER JOIN `tblcountry` d ON c.country_code=d.id WHERE `state`='$state' AND d.country_code"."='$country'";
		$sqlnos="SELECT a.nos FROM `tbldropdownofnos` a INNER JOIN `tblstates` b ON a.lang = b.language INNER JOIN `tbllanguage` c ON b.language=c.id "." INNER JOIN `tblcountry` d ON c.country_code=d.id WHERE `state`='$state' AND d.country_code='$country'";
		$sqlhours="SELECT a.hours FROM `tblhours` a INNER JOIN `tblstates` b ON a.lang = b.language INNER JOIN `tbllanguage` c ON b.language=c.id "."INNER JOIN `tblcountry` d ON c.country_code=d.id WHERE `state`='$state' AND d.country_code='$country'";
		$sqlmins="SELECT a.Min FROM `tblmins` a INNER JOIN `tblstates` b ON a.lang = b.language INNER JOIN `tbllanguage` c ON b.language=c.id INNER JOIN "."`tblcountry` d ON c.country_code=d.id WHERE `state`='$state' AND d.country_code='$country'";
		// Code to get Duty Type to show on registration page starts here /////
		$sqlmasterPackage="SELECT Master_Package FROM `tblmasterpackage` WHERE `state_id`='$state_id'";
		// Code to get Duty Type to show on registration page Ends here /////
		if(mysqli_num_rows(mysqli_query($this->con,$sql))==0){
			$state='Delhi';
			$sql = "SELECT a.text,a.text_id FROM `tblregiontext` a INNER JOIN `tblstates` b ON a.lang=b.language INNER JOIN `tbllanguage` c ON b.language= c.id INNER JOIN `tblcountry` d ON c.country_code=d.id  WHERE `state`='$state' AND d.`country_code`='$country' and page_title='home'";
		    /*echo $sqlCabFor = "SELECT a.id, a.PackageName FROM `tblcabfor` a INNER JOIN `tblstates` b ON a.`language` = b.language INNER JOIN `tbllanguage` c ON b.language=c.id INNER JOIN `tblcountry` d ON c.country_code=d.id WHERE b.state='$state' AND d.country_code='$country'";*/
			
		 $sqlCabFor = "SELECT  distinct t1.Package as PackageName,t1.Hrs,t1.Km,t1.Price as Price,t1.id FROM `tbllocalpackage` t1
		 INNER JOIN tblstates t3 ON t1.City_ID=(SELECT t3.`id` FROM `tblstates` WHERE state='$state')
		 INNER JOIN tblmasterpackage t4 ON t4.Sub_Package_Id=t1.Sub_Package_Id AND t1.masterPKG_ID=t4.Package_Id
		 WHERE t1.masterPKG_ID='101' and t3.state='$state' 
		 and t1.CabType='1' and t1.Status=1 order by t1.id";			
			
			$sqlnationality= "SELECT a.user_nationality FROM `tblnationality` a INNER JOIN `tblstates` b ON a.lang = b.language INNER JOIN `"."tbllanguage` c ON b.language=c.id INNER JOIN `tblcountry` d ON c.country_code=d.id WHERE `state`='$state' AND d.country_code"."='$country'";
			$sqlnos="SELECT a.nos FROM `tbldropdownofnos` a INNER JOIN `tblstates` b ON a.lang = b.language INNER JOIN `tbllanguage` c ON b.language=c.id "." INNER JOIN `tblcountry` d ON c.country_code=d.id WHERE `state`='$state' AND d.country_code='$country'";
			$sqlhours="SELECT a.hours FROM `tblhours` a INNER JOIN `tblstates` b ON a.lang = b.language INNER JOIN `tbllanguage` c ON b.language=c.id "."INNER JOIN `tblcountry` d ON c.country_code=d.id WHERE `state`='$state' AND d.country_code='$country'";
			$sqlmins="SELECT a.Min FROM `tblmins` a INNER JOIN `tblstates` b ON a.lang = b.language INNER JOIN `tbllanguage` c ON b.language=c.id INNER JOIN "."`tblcountry` d ON c.country_code=d.id WHERE `state`='$state' AND d.country_code='$country'";
			// Code to get Duty Type to show on registration page starts here By Mohit Jain /////
			$sqlmasterPackage="SELECT master_package_ref,Package_Id FROM `tblmasterpackage` WHERE `state_id`='$state_id'";
			// Code to get Duty Type to show on registration page Ends here /////
		}
		//$sql = "SELECT a.text,a.text_id,b.selected_region FROM `tblregiontext` a INNER JOIN `tblcabin` b ON a.lang =b.language INNER JOIN `tblstates` c ON b.language=c.language INNER JOIN `tbllanguage` d ON c.language=d.lang INNER JOIN `tblcountry` d.country_code=e.id WHERE `state`='$state' AND d.`country_code`='$country'";
		$result = mysqli_query($this->con, $sql)or die(mysqli_error($this->con));
		$dataNos = mysqli_num_rows($result);
		$text_ids = array('local_cabIn', 'local_pickup_location', 'local_pickup_address');
		while($data = mysqli_fetch_assoc($result)){
			if(in_array($data['text_id'], $text_ids)){
				$data['text'] = $data['text'].' <span style="color:#F00">*</span>';
			}
			$citydata[] = $data;
		} 
		// cab In
		//cab in end//
		//cab for//
		$resultCabFor = mysqli_query($this->con, $sqlCabFor);
		//$dataNos = mysqli_num_rows($resultCabFor);
		while($dataCabFor = mysqli_fetch_assoc($resultCabFor)){
			$cabFordata[] = $dataCabFor;
		}
		// return array("data"=>$cabFordata,"Nos"=>$dataNos);
		//end of cab for//
		//nationality//////
		$resultnationality = mysqli_query($this->con, $sqlnationality);
		//$dataNos = mysqli_num_rows($resultnationality);
		while($datanationality = mysqli_fetch_assoc($resultnationality)){
			$nationalitydata[] = $datanationality;
		}
		file_put_contents('asdasda.txt', $sqlnos);
		$resultnos = mysqli_query($this->con, $sqlnos);
		// $dataNos = mysqli_num_rows($resultnos);
		while($datanos = mysqli_fetch_assoc($resultnos)){
			$nosdata[] = $datanos;
		}
		//return array("data"=>$citydata,"Nos"=>$dataNos);
		//end nationality//////   
		///hours//
		//$sql= "SELECT a.user_nationality FROM `tblnationality` a INNER JOIN `tblstates` b ON a.lang = b.language INNER JOIN `tblcountry` c ON b.country_code=c.country_code WHERE `state`='$stateId' AND c.country_code='$countryId'";
		$resulthours = mysqli_query($this->con, $sqlhours);
		// $dataNos = mysqli_num_rows($result);
		while($datahours = mysqli_fetch_assoc($resulthours)){
			$hoursdata[] = $datahours;
		}
		//end hours//
		//min //
		//$sql= "SELECT a.user_nationality FROM `tblnationality` a INNER JOIN `tblstates` b ON a.lang = b.language INNER JOIN `tblcountry` c ON b.country_code=c.country_code WHERE `state`='$stateId' AND c.country_code='$countryId'";
		$resultmin = mysqli_query($this->con, $sqlmins);
		// $dataNos = mysqli_num_rows($resultmin);
		while($datamin = mysqli_fetch_assoc($resultmin)){
			$citydatamin[] = $datamin;
		}
		file_put_contents('adasddas.txt', print_r($citydatamin,TRUE));
		
		$resultmasterPackage = mysqli_query($this->con, $sqlmasterPackage);
		while($datamasterpackage = mysqli_fetch_assoc($resultmasterPackage)){
			$masterpackagedata[] = $datamasterpackage;
		}
		//end min// 
			
	  $sql = "SELECT tblmasterpackage.Package_Id, tblmasterpackage.Sub_Package_Id, tblbookingbill.CabType, tblbookingbill.MinimumCharge, tblbookingbill.package_info_distance,tblbookingbill.package_info_hourly,tblbookingbill.package_info_distance_waiting,tblbookingbill.tripCharge_per_minute,tblbookingbill.ignore_first_hrs,tblbookingbill.minimum_hourly_Charge,tblbookingbill.package_info_distance_hourly, tblbookingbill.Per_Km_Charge,tblbookingbill.minimum_fare_dw, tblbookingbill.NightCharges, tblbookingbill.rate_upto_distance, tblbookingbill.Min_Distance,tblbookingbill.rate_per_km_dh ,tblbookingbill.rate_per_km_dw,tblbookingbill.minimum_distance_dh,tblbookingbill.minimum_distance_dw, tblbookingbill.minimum_fare_dh, tblbookingbill.rate_per_hour_dh, tblbookingbill.ignore_first_hrs_dh  FROM tblbookingbill INNER JOIN tblmasterpackage ON tblbookingbill.BookingTypeId = tblmasterpackage.Package_Id WHERE tblbookingbill.BookingTypeId IN (101,102,103,104) and tblbookingbill.cityId='$city_id'"; 
		$qry = mysqli_query($this->con,$sql);
		
		$EconomyLocalFare = array();
		$PTP_forFront = array();
		$EconomyOutStationF = array();
		$EconomyAirPortFare = array();
		
		while($row = mysqli_fetch_object($qry)){
			
			//$row->Sub_Package_Id = 1;
			if($row->Package_Id == 101){
				//print_r($pack_hour=$row->package_info_distance);
				/*$pack_hour=$row->package_info_distance;	

				 	print_r($pack_hour=$row->package_info_distance);
					
				$fare	=	str_replace(array('[',']'),'',$pack_hour);
				$fare1 = explode(',',$fare);
				// 4- 40 km
				$Efdaa = str_replace('"','',$fare1[0]);
				$fda	=explode('_',$Efdaa);
				
				// 8- 80 KM
				$sdaa = str_replace('"','',$fare1[1]);
				$sda	=explode('_',$sdaa);
				
				// 12- 120 KM
				$tdaa = str_replace('"','',$fare1[2]);
				$tda	=explode('_',$tdaa);
				
				// 16- 160 KM
				$slaa = str_replace('"','',$fare1[3]);
				$lda	=explode('_',$slaa); */
				
				//print_r($fare1);
				
				
				if($row->Sub_Package_Id == 1){
					
				$pack_hour=$row->package_info_distance;			
				
				$fare	=	str_replace(array('[',']'),'',$pack_hour);
				$fare1 = explode(',',$fare);
				// 4- 40 km
				$Efdaa = str_replace('"','',$fare1[0]);
				$fda	=explode('_',$Efdaa);
				
				// 8- 80 KM
				$sdaa = str_replace('"','',$fare1[1]);
				$sda	=explode('_',$sdaa);
				
				// 12- 120 KM
				$tdaa = str_replace('"','',$fare1[2]);
				$tda	=explode('_',$tdaa);
				
				// 16- 160 KM
				$slaa = str_replace('"','',$fare1[3]);
				$lda	=explode('_',$slaa);
					
					
					if($row->CabType == 1){
						$EconomyMinimumCharges = $row->MinimumCharge;
						// Open For Distance fare (Economy)
						$row->rate_per_hour_dh = "0";
						$EconomyLocalFare['Economy4'] = array('e1'=>$fda[0],'e2'=>$fda[2],'e3'=>$row->Per_Km_Charge,'e4'=>$row->rate_per_hour_dh);
						$EconomyLocalFare['Economy8'] = array('e1'=>$sda[0],'e2'=>$sda[2],'e3'=>$row->Per_Km_Charge,'e4'=>$row->rate_per_hour_dh);
						$EconomyLocalFare['Economy12'] = array('e1'=>$tda[0],'e2'=>$tda[2],'e3'=>$row->Per_Km_Charge,'e4'=>$row->rate_per_hour_dh);
						$EconomyLocalFare['Economy16'] = array('e1'=>$lda[0],'e2'=>$lda[2],'e3'=>$row->Per_Km_Charge,'e4'=>$row->rate_per_hour_dh);
						
						// Close For Distance fare (Economy)
					}elseif($row->CabType == 2){
						$SedanMinimumCharges = $row->MinimumCharge;
						
						// Open For Distance fare (Sedan)
						$row->rate_per_hour_dh = "0";
						$EconomyLocalFare['Sedan4'] = array('s1'=>$fda[0],'s2'=>$fda[2],'s3'=>$row->Per_Km_Charge,'s4'=>$row->rate_per_hour_dh);
						$EconomyLocalFare['Sedan8'] = array('s1'=>$sda[0],'s2'=>$sda[2],'s3'=>$row->Per_Km_Charge,'s4'=>$row->rate_per_hour_dh);
						$EconomyLocalFare['Sedan12'] = array('s1'=>$tda[0],'s2'=>$tda[2],'s3'=>$row->Per_Km_Charge,'s4'=>$row->rate_per_hour_dh);
						$EconomyLocalFare['Sedan16'] = array('s1'=>$lda[0],'s2'=>$lda[2],'s3'=>$row->Per_Km_Charge,'s4'=>$row->rate_per_hour_dh);
						
						// Close For Distance fare (Sedan)
						
					}elseif($row->CabType == 3){
						$PrimeMinimumCharges = $row->MinimumCharge;
						
						// Open For Distance fare (Prime)
						$row->rate_per_hour_dh = "0";
						$EconomyLocalFare['Prime4'] = array('Prime1'=>$fda[0],'Prime2'=>$fda[2],'Prime3'=>$row->Per_Km_Charge,'Prime4'=>$row->rate_per_hour_dh);
						$EconomyLocalFare['Prime8'] = array('Prime1'=>$sda[0],'Prime2'=>$sda[2],'Prime3'=>$row->Per_Km_Charge,'Prime4'=>$row->rate_per_hour_dh);
						$EconomyLocalFare['Prime12'] = array('Prime1'=>$tda[0],'Prime2'=>$tda[2],'Prime3'=>$row->Per_Km_Charge,'Prime4'=>$row->rate_per_hour_dh);
						$EconomyLocalFare['Prime16'] = array('Prime1'=>$lda[0],'Prime2'=>$lda[2],'Prime3'=>$row->Per_Km_Charge,'Prime4'=>$row->rate_per_hour_dh);
						
						// Close For Distance fare (Sedan)
					}
				}elseif($row->Sub_Package_Id == 2){
					
					$pack_hour=$row->package_info_hourly;			
				
					$fare	=	str_replace(array('[',']'),'',$pack_hour);
					$fare1 = explode(',',$fare);
					// 4- 40 km
					$Efdaa = str_replace('"','',$fare1[0]);
					$fda	=explode('_',$Efdaa);
					
					// 8- 80 KM
					$sdaa = str_replace('"','',$fare1[1]);
					$sda	=explode('_',$sdaa);
					
					// 12- 120 KM
					$tdaa = str_replace('"','',$fare1[2]);
					$tda	=explode('_',$tdaa);
					
					// 16- 160 KM
					$slaa = str_replace('"','',$fare1[3]);
					$lda	=explode('_',$slaa);
					
					if($row->CabType == 1){
						$EconomyMinimumCharges = 0;
						
						// Open For Hourly fare (Economy)
						$row->Per_Km_Charge = "0";
						$EconomyLocalFare['Economy4'] = array('e1'=>$fda[0],'e2'=>$fda[2],'e3'=>$row->Per_Km_Charge,'e4'=>$row->tripCharge_per_minute);
						$EconomyLocalFare['Economy8'] = array('e1'=>$sda[0],'e2'=>$sda[2],'e3'=>$row->Per_Km_Charge,'e4'=>$row->tripCharge_per_minute);
						$EconomyLocalFare['Economy12'] = array('e1'=>$tda[0],'e2'=>$tda[2],'e3'=>$row->Per_Km_Charge,'e4'=>$row->tripCharge_per_minute);
						$EconomyLocalFare['Economy16'] = array('e1'=>$lda[0],'e2'=>$lda[2],'e3'=>$row->Per_Km_Charge,'e4'=>$row->tripCharge_per_minute);
						
					}elseif($row->CabType == 2){
						$SedanMinimumCharges = 0;
						
						// Open For Hourly fare (Sedan)
						$row->Per_Km_Charge = "0";
						$EconomyLocalFare['Sedan4'] = array('s1'=>$fda[0],'s2'=>$fda[2],'s3'=>$row->Per_Km_Charge,'s4'=>$row->tripCharge_per_minute);
						$EconomyLocalFare['Sedan8'] = array('s1'=>$sda[0],'s2'=>$sda[2],'s3'=>$row->Per_Km_Charge,'s4'=>$row->tripCharge_per_minute);
						$EconomyLocalFare['Sedan12'] = array('s1'=>$tda[0],'s2'=>$tda[2],'s3'=>$row->Per_Km_Charge,'s4'=>$row->tripCharge_per_minute);
						$EconomyLocalFare['Sedan16'] = array('s1'=>$lda[0],'s2'=>$lda[2],'s3'=>$row->Per_Km_Charge,'s4'=>$row->tripCharge_per_minute);
						
						// Close For Hourly fare (Sedan)
						
					}elseif($row->CabType == 3){
						$PrimeMinimumCharges = 0;
						
						// Open For Distance fare (Prime)
						$row->Per_Km_Charge = "0";
						$EconomyLocalFare['Prime4'] = array('Prime1'=>$fda[0],'Prime2'=>$fda[2],'Prime3'=>$row->Per_Km_Charge,'Prime4'=>$row->tripCharge_per_minute);
						$EconomyLocalFare['Prime8'] = array('Prime1'=>$sda[0],'Prime2'=>$sda[2],'Prime3'=>$row->Per_Km_Charge,'Prime4'=>$row->tripCharge_per_minute);
						$EconomyLocalFare['Prime12'] = array('Prime1'=>$tda[0],'Prime2'=>$tda[2],'Prime3'=>$row->Per_Km_Charge,'Prime4'=>$row->tripCharge_per_minute);
						$EconomyLocalFare['Prime16'] = array('Prime1'=>$lda[0],'Prime2'=>$lda[2],'Prime3'=>$row->Per_Km_Charge,'Prime4'=>$row->tripCharge_per_minute);
						
						// Close For Distance fare (Sedan)
						
					}
				}elseif($row->Sub_Package_Id == 3){
					
								$pack_hour=$row->package_info_distance_hourly;			
								$fare	=	str_replace(array('[',']'),'',$pack_hour);
								$fare1 = explode(',',$fare);
								// 4- 40 km
								$Efdaa = str_replace('"','',$fare1[0]);
								$fda	=explode('_',$Efdaa);
								
								// 8- 80 KM
								$sdaa = str_replace('"','',$fare1[1]);
								$sda	=explode('_',$sdaa);
								
								// 12- 120 KM
								$tdaa = str_replace('"','',$fare1[2]);
								$tda	=explode('_',$tdaa);
								
								// 16- 160 KM
								$slaa = str_replace('"','',$fare1[3]);
								$lda	=explode('_',$slaa);
					
					if($row->CabType == 1){
						$EconomyMinimumCharges = $row->minimum_fare_dh;
						$EconomyLocalFare['Economy4'] = array('e1'=>$fda[0],'e2'=>$fda[3],'e3'=>$row->rate_per_km_dh,'e4'=>$row->rate_per_hour_dh);
						$EconomyLocalFare['Economy8'] = array('e1'=>$sda[0],'e2'=>$sda[3],'e3'=>$row->rate_per_km_dh,'e4'=>$row->rate_per_hour_dh);
						$EconomyLocalFare['Economy12'] = array('e1'=>$tda[0],'e2'=>$tda[3],'e3'=>$row->rate_per_km_dh,'e4'=>$row->rate_per_hour_dh);
						$EconomyLocalFare['Economy16'] = array('e1'=>$lda[0],'e2'=>$lda[3],'e3'=>$row->rate_per_km_dh,'e4'=>$row->rate_per_hour_dh);
					}elseif($row->CabType == 2){
						$SedanMinimumCharges = $row->minimum_fare_dh;
						$EconomyLocalFare['Sedan4'] = array('s1'=>$fda[0],'s2'=>$fda[3],'s3'=>$row->rate_per_km_dh,'s4'=>$row->rate_per_hour_dh);
						$EconomyLocalFare['Sedan8'] = array('s1'=>$sda[0],'s2'=>$sda[3],'s3'=>$row->rate_per_km_dh,'s4'=>$row->rate_per_hour_dh);
						$EconomyLocalFare['Sedan12'] = array('s1'=>$tda[0],'s2'=>$tda[3],'s3'=>$row->rate_per_km_dh,'s4'=>$row->rate_per_hour_dh);
						$EconomyLocalFare['Sedan16'] = array('s1'=>$lda[0],'s2'=>$lda[3],'s3'=>$row->rate_per_km_dh,'s4'=>$row->rate_per_hour_dh);
					}elseif($row->CabType == 3){
						$PrimeMinimumCharges = $row->minimum_fare_dh;
						$EconomyLocalFare['Prime4'] = array('Prime1'=>$fda[0],'Prime2'=>$fda[3],'Prime3'=>$row->rate_per_km_dh,'Prime4'=>$row->rate_per_hour_dh);
						$EconomyLocalFare['Prime8'] = array('Prime1'=>$sda[0],'Prime2'=>$sda[3],'Prime3'=>$row->rate_per_km_dh,'Prime4'=>$row->rate_per_hour_dh);
						$EconomyLocalFare['Prime12'] = array('Prime1'=>$tda[0],'Prime2'=>$tda[3],'Prime3'=>$row->rate_per_km_dh,'Prime4'=>$row->rate_per_hour_dh);
						$EconomyLocalFare['Prime16'] = array('Prime1'=>$lda[0],'Prime2'=>$lda[3],'Prime3'=>$row->rate_per_km_dh,'Prime4'=>$row->rate_per_hour_dh);
					}
				}elseif($row->Sub_Package_Id == 4){
					
							$pack_hour=$row->package_info_distance_waiting;			
								$fare	=	str_replace(array('[',']'),'',$pack_hour);	
								$fare1 = explode(',',$fare);
								// 4- 40 km
								$Efdaa = str_replace('"','',$fare1[0]);
								$fda	=explode('_',$Efdaa);
								
								// 8- 80 KM
								$sdaa = str_replace('"','',$fare1[1]);
								$sda	=explode('_',$sdaa);
								
								// 12- 120 KM
								$tdaa = str_replace('"','',$fare1[2]);
								$tda	=explode('_',$tdaa);
								
								// 16- 160 KM
								$slaa = str_replace('"','',$fare1[3]);
								$lda	=explode('_',$slaa);
					
					if($row->CabType == 1){
						$EconomyMinimumCharges = $row->minimum_fare_dw;
						// Open For Distance waiting fare(Economy) 
						$row->tripCharge_per_minute = "0";
						$EconomyLocalFare['Economy4'] = array('e1'=>$fda[0],'e2'=>$fda[2],'e3'=>$row->rate_per_km_dw,'e4'=>$row->tripCharge_per_minute);
						$EconomyLocalFare['Economy8'] = array('e1'=>$sda[0],'e2'=>$sda[2],'e3'=>$row->rate_per_km_dw,'e4'=>$row->tripCharge_per_minute);
						$EconomyLocalFare['Economy12'] = array('e1'=>$tda[0],'e2'=>$tda[2],'e3'=>$row->rate_per_km_dw,'e4'=>$row->tripCharge_per_minute);
						$EconomyLocalFare['Economy16'] = array('e1'=>$lda[0],'e2'=>$lda[2],'e3'=>$row->rate_per_km_dw,'e4'=>$row->tripCharge_per_minute);
						// Close For Distance waiting fare  (Economy)
						
					}elseif($row->CabType == 2){
						$SedanMinimumCharges = $row->minimum_fare_dw;
						
						// Open For Distance waiting fare(Sedan)
						$row->tripCharge_per_minute = "0";
						$EconomyLocalFare['Sedan4'] = array('s1'=>$fda[0],'s2'=>$fda[2],'s3'=>$row->rate_per_km_dw,'s4'=>$row->tripCharge_per_minute);
						$EconomyLocalFare['Sedan8'] = array('s1'=>$sda[0],'s2'=>$sda[2],'s3'=>$row->rate_per_km_dw,'s4'=>$row->tripCharge_per_minute);
						$EconomyLocalFare['Sedan12'] = array('s1'=>$tda[0],'s2'=>$tda[2],'s3'=>$row->rate_per_km_dw,'s4'=>$row->tripCharge_per_minute);
						$EconomyLocalFare['Sedan16'] = array('s1'=>$lda[0],'s2'=>$lda[2],'s3'=>$row->rate_per_km_dw,'s4'=>$row->tripCharge_per_minute);
						
						// Close For Distance waiting fare  (Sedan)
						
					}elseif($row->CabType == 3){
						$PrimeMinimumCharges = $row->minimum_fare_dw;
						
						// Open For Distance waiting fare (Prime)
						$row->tripCharge_per_minute = "0";
						$EconomyLocalFare['Prime4'] = array('Prime1'=>$fda[0],'Prime2'=>$fda[2],'Prime3'=>$row->rate_per_km_dw,'Prime4'=>$row->tripCharge_per_minute);
						$EconomyLocalFare['Prime8'] = array('Prime1'=>$sda[0],'Prime2'=>$sda[2],'Prime3'=>$row->rate_per_km_dw,'Prime4'=>$row->tripCharge_per_minute);
						$EconomyLocalFare['Prime12'] = array('Prime1'=>$tda[0],'Prime2'=>$tda[2],'Prime3'=>$row->rate_per_km_dw,'Prime4'=>$row->tripCharge_per_minute);
						$EconomyLocalFare['Prime16'] = array('Prime1'=>$lda[0],'Prime2'=>$lda[2],'Prime3'=>$row->rate_per_km_dw,'Prime4'=>$row->tripCharge_per_minute);
						
						// Close For Distance waiting fare (Prime)
						
					}
				}
			}
			
			
			///////////// Fare Structure for POINT TO POINT Starts Here ////////////////////////				
							
			
			
			elseif($row->Package_Id == 102){
				
			//// Fare Structure For Distance Calculation Starts Here ///
				
				if($row->Sub_Package_Id == 1){
					// Distance 
					$row->rate_per_hour_dh="0";
					
					if($row->CabType == 1){
						$PTP_EconomyMinimumCharges = $row->MinimumCharge;
						
						$PTP_forFront['Economy'] = array('ptpe1'=>$row->Per_Km_Charge, 'ptpe2'=>$row->Min_Distance , 'ptpe3'=>$row->MinimumCharge , 'ptpe4'=>$row->rate_per_hour_dh ,'ptpe5'=>$row->ignore_first_hrs_dh );
						
					}elseif($row->CabType == 2){
						$PTP_SedanMinimumCharges = $row->MinimumCharge;
						
						$PTP_forFront['Sedan'] = array('ptps1'=>$row->Per_Km_Charge, 'ptps2'=>$row->Min_Distance , 'ptps3'=>$row->MinimumCharge , 'ptps4'=>$row->rate_per_hour_dh ,'ptps5'=>$row->ignore_first_hrs_dh );
						
					}elseif($row->CabType == 3){
						$PTP_PrimeMinimumCharges = $row->MinimumCharge;
						
						$PTP_forFront['Prime'] = array('ptppr1'=>$row->Per_Km_Charge, 'ptppr2'=>$row->Min_Distance , 'ptppr3'=>$row->MinimumCharge , 'ptppr4'=>$row->rate_per_hour_dh ,'ptppr5'=>$row->ignore_first_hrs_dh );
						
					}
				}
				
			//// Fare Structure For Distance Calculation Ends Here ///	
			
			//// Fare Structure For Hourly Calculation Starts Here ///
				
				elseif($row->Sub_Package_Id == 2){
					//  hours
					$row->rate_per_hour_dh="0";
					$row->ignore_first_hrs_dh="0";
					if($row->CabType == 1){
						//$PTP_EconomyMinimumCharges = 0;
						$PTP_EconomyMinimumCharges = $row->minimum_hourly_Charge;
						$PTP_forFront['Economy'] = array('ptpe1'=>$row->tripCharge_per_minute, 'ptpe2'=>$row->ignore_first_hrs, 'ptpe3'=>$row->minimum_hourly_Charge , 'ptpe4'=>$row->rate_per_hour_dh ,'ptpe5'=>$row->ignore_first_hrs_dh);
					}elseif($row->CabType == 2){
						//$PTP_SedanMinimumCharges = 0;
						$PTP_EconomyMinimumCharges = $row->minimum_hourly_Charge;
						$PTP_forFront['Sedan'] = array('ptps1'=>$row->tripCharge_per_minute, 'ptps2'=>$row->ignore_first_hrs, 'ptps3'=>$row->minimum_hourly_Charge , 'ptps4'=>$row->rate_per_hour_dh ,'ptps5'=>$row->ignore_first_hrs_dh);
					}elseif($row->CabType == 3){
						//$PTP_PrimeMinimumCharges = 0;
						$PTP_EconomyMinimumCharges = $row->minimum_hourly_Charge;
						$PTP_forFront['Prime'] = array('ptppr1'=>$row->tripCharge_per_minute, 'ptppr2'=>$row->ignore_first_hrs, 'ptppr3'=>$row->minimum_hourly_Charge , 'ptppr4'=>$row->rate_per_hour_dh ,'ptppr5'=>$row->ignore_first_hrs_dh);
					}
				}
				
		  //// Fare Structure For Hourly Calculation ENDS Here ///
		  
		  //// Fare Structure For Distance+Hourly Calculation Starts Here ///
		  
				
				elseif($row->Sub_Package_Id == 3){
					
					if($row->CabType == 1){
						// Distance + hours
						$PTP_EconomyMinimumCharges = $row->minimum_fare_dh;
						
						$PTP_forFront['Economy'] = array('ptpe1'=>$row->rate_per_km_dh, 'ptpe2'=>$row->minimum_distance_dh , 'ptpe3'=>$row->minimum_fare_dh , 'ptpe4'=>$row->rate_per_hour_dh ,'ptpe5'=>$row->ignore_first_hrs_dh );
						
					}elseif($row->CabType == 2){
						$PTP_SedanMinimumCharges = $row->minimum_fare_dh;
						
						$PTP_forFront['Sedan'] = array('ptps1'=>$row->rate_per_km_dh, 'ptps2'=>$row->minimum_distance_dh , 'ptps3'=>$row->minimum_fare_dh , 'ptps4'=>$row->rate_per_hour_dh ,'ptps5'=>$row->ignore_first_hrs_dh );
						
					}elseif($row->CabType == 3){
						$PTP_PrimeMinimumCharges = $row->minimum_fare_dh;
						
						$PTP_forFront['Prime'] = array('ptppr1'=>$row->rate_per_km_dh, 'ptppr2'=>$row->minimum_distance_dh , 'ptppr3'=>$row->minimum_fare_dh , 'ptppr4'=>$row->rate_per_hour_dh ,'ptppr5'=>$row->ignore_first_hrs_dh );
						
					}
				}
				
		 //// Fare Structure For Distance+Hourly Calculation Ends Here ///
		
		//// Fare Structure For Distance+Waiting Calculation Starts Here ///
		
				elseif($row->Sub_Package_Id == 4){ 
				
					// Distance + Waiting
				
					if($row->CabType == 1){
						$PTP_EconomyMinimumCharges = $row->minimum_fare_dw;
						
						$PTP_forFront['Economy'] = array('ptpe1'=>$row->rate_per_km_dw, 'ptpe2'=>$row->minimum_distance_dw , 'ptpe3'=>$row->minimum_fare_dw , 'ptpe4'=>$row->rate_per_hour_dh ,'ptpe5'=>$row->ignore_first_hrs_dh );
						
					}elseif($row->CabType == 2){
						$PTP_SedanMinimumCharges = $row->minimum_fare_dw;
						
						$PTP_forFront['Sedan'] = array('ptps1'=>$row->rate_per_km_dw, 'ptps2'=>$row->minimum_distance_dw , 'ptps3'=>$row->minimum_fare_dw , 'ptps4'=>$row->rate_per_hour_dh ,'ptps5'=>$row->ignore_first_hrs_dh );
						
					}elseif($row->CabType == 3){
						$PTP_PrimeMinimumCharges = $row->minimum_fare_dw;
						
						$PTP_forFront['Prime'] = array('ptppr1'=>$row->rate_per_km_dw, 'ptppr2'=>$row->minimum_distance_dw , 'ptppr3'=>$row->minimum_fare_dw , 'ptppr4'=>$row->rate_per_hour_dh ,'ptppr5'=>$row->ignore_first_hrs_dh );
						
					}
				}
				
		//// Fare Structure For Distance+Waiting Calculation Ends Here ///
				
			}
			
			
			///////////// Fare Structure for POINT TO POINT ENDS Here ////////////////////////
			
			
			
			elseif($row->Package_Id == 103){
				// Airport Fair
				if($row->Sub_Package_Id == 1){
					// For Distance
				$pack_hour=$row->rate_upto_distance;			
				$fare	=	str_replace(array('[',']'),'',$pack_hour);
				$fare1 = explode(',',$fare);
				
				// 10 km
				$Efdaa = str_replace('"','',$fare1[0]);
				$fda	=explode('_',$Efdaa);
				
				// 15 KM
				$sdaa = str_replace('"','',$fare1[1]);
				$sda	=explode('_',$sdaa);
				
				// 20 KM
				$tdaa = str_replace('"','',$fare1[2]);
				$tda	=explode('_',$tdaa);
				
				// 30 KM
				$slaa = str_replace('"','',$fare1[3]);
				$lda	=explode('_',$slaa);
				
				if($row->CabType == 1){
						$EconomyMinimumCharges = $row->minimum_fare_dh;
						$EconomyAirPortFare['Economy10'] = array('e1'=>$fda[0],'e2'=>$fda[1],'e3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Economy15'] = array('e1'=>$sda[0],'e2'=>$sda[1],'e3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Economy20'] = array('e1'=>$tda[0],'e2'=>$tda[1],'e3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Economy30'] = array('e1'=>$lda[0],'e2'=>$lda[1],'e3'=>$row->Per_Km_Charge);
					}elseif($row->CabType == 2){
						$SedanMinimumCharges = $row->minimum_fare_dh;
						$EconomyAirPortFare['Sedan10'] = array('s1'=>$fda[0],'s2'=>$fda[1],'s3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Sedan15'] = array('s1'=>$sda[0],'s2'=>$sda[1],'s3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Sedan20'] = array('s1'=>$tda[0],'s2'=>$tda[1],'s3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Sedan30'] = array('s1'=>$lda[0],'s2'=>$lda[1],'s3'=>$row->Per_Km_Charge);
					}elseif($row->CabType == 3){
						$PrimeMinimumCharges = $row->minimum_fare_dh;
						$EconomyAirPortFare['Prime10'] = array('Prime1'=>$fda[0],'Prime2'=>$fda[1],'Prime3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Prime15'] = array('Prime1'=>$sda[0],'Prime2'=>$sda[1],'Prime3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Prime20'] = array('Prime1'=>$tda[0],'Prime2'=>$tda[1],'Prime3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Prime30'] = array('Prime1'=>$lda[0],'Prime2'=>$lda[1],'Prime3'=>$row->Per_Km_Charge);
					}
				}elseif($row->Sub_Package_Id == 2){
					// For Hourly
				$pack_hour=$row->rate_upto_distance;			
				$fare	=	str_replace(array('[',']'),'',$pack_hour);
				$fare1 = explode(',',$fare);
				
				// 10 km
				$Efdaa = str_replace('"','',$fare1[0]);
				$fda	=explode('_',$Efdaa);
				
				// 15 KM
				$sdaa = str_replace('"','',$fare1[1]);
				$sda	=explode('_',$sdaa);
				
				// 20 KM
				$tdaa = str_replace('"','',$fare1[2]);
				$tda	=explode('_',$tdaa);
				
				// 30 KM
				$slaa = str_replace('"','',$fare1[3]);
				$lda	=explode('_',$slaa);
					
					if($row->CabType == 1){
						$EconomyMinimumCharges = $row->minimum_fare_dh;
						$EconomyAirPortFare['Economy10'] = array('e1'=>$fda[0],'e2'=>$fda[1],'e3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Economy15'] = array('e1'=>$sda[0],'e2'=>$sda[1],'e3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Economy20'] = array('e1'=>$tda[0],'e2'=>$tda[1],'e3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Economy30'] = array('e1'=>$lda[0],'e2'=>$lda[1],'e3'=>$row->Per_Km_Charge);
					}elseif($row->CabType == 2){
						$SedanMinimumCharges = $row->minimum_fare_dh;
						$EconomyAirPortFare['Sedan10'] = array('s1'=>$fda[0],'s2'=>$fda[1],'s3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Sedan15'] = array('s1'=>$sda[0],'s2'=>$sda[1],'s3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Sedan20'] = array('s1'=>$tda[0],'s2'=>$tda[1],'s3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Sedan30'] = array('s1'=>$lda[0],'s2'=>$lda[1],'s3'=>$row->Per_Km_Charge);
					}elseif($row->CabType == 3){
						$PrimeMinimumCharges = $row->minimum_fare_dh;
						$EconomyAirPortFare['Prime10'] = array('Prime1'=>$fda[0],'Prime2'=>$fda[1],'Prime3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Prime15'] = array('Prime1'=>$sda[0],'Prime2'=>$sda[1],'Prime3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Prime20'] = array('Prime1'=>$tda[0],'Prime2'=>$tda[1],'Prime3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Prime30'] = array('Prime1'=>$lda[0],'Prime2'=>$lda[1],'Prime3'=>$row->Per_Km_Charge);
					}
					
				}elseif($row->Sub_Package_Id == 3){
					// For Distance Hourly
						// For Hourly
				$pack_hour=$row->rate_upto_distance;			
				$fare	=	str_replace(array('[',']'),'',$pack_hour);
				$fare1 = explode(',',$fare);
				
				// 10 km
				$Efdaa = str_replace('"','',$fare1[0]);
				$fda	=explode('_',$Efdaa);
				
				// 15 KM
				$sdaa = str_replace('"','',$fare1[1]);
				$sda	=explode('_',$sdaa);
				
				// 20 KM
				$tdaa = str_replace('"','',$fare1[2]);
				$tda	=explode('_',$tdaa);
				
				// 30 KM
				$slaa = str_replace('"','',$fare1[3]);
				$lda	=explode('_',$slaa);

    				if($row->CabType == 1){
						$EconomyMinimumCharges = $row->minimum_fare_dh;
						$EconomyAirPortFare['Economy10'] = array('e1'=>$fda[0],'e2'=>$fda[1],'e3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Economy15'] = array('e1'=>$sda[0],'e2'=>$sda[1],'e3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Economy20'] = array('e1'=>$tda[0],'e2'=>$tda[1],'e3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Economy30'] = array('e1'=>$lda[0],'e2'=>$lda[1],'e3'=>$row->Per_Km_Charge);
					}elseif($row->CabType == 2){
						$SedanMinimumCharges = $row->minimum_fare_dh;
						$EconomyAirPortFare['Sedan10'] = array('s1'=>$fda[0],'s2'=>$fda[1],'s3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Sedan15'] = array('s1'=>$sda[0],'s2'=>$sda[1],'s3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Sedan20'] = array('s1'=>$tda[0],'s2'=>$tda[1],'s3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Sedan30'] = array('s1'=>$lda[0],'s2'=>$lda[1],'s3'=>$row->Per_Km_Charge);
					}elseif($row->CabType == 3){
						$PrimeMinimumCharges = $row->minimum_fare_dh;
						$EconomyAirPortFare['Prime10'] = array('Prime1'=>$fda[0],'Prime2'=>$fda[1],'Prime3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Prime15'] = array('Prime1'=>$sda[0],'Prime2'=>$sda[1],'Prime3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Prime20'] = array('Prime1'=>$tda[0],'Prime2'=>$tda[1],'Prime3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Prime30'] = array('Prime1'=>$lda[0],'Prime2'=>$lda[1],'Prime3'=>$row->Per_Km_Charge);
					}
					
				}elseif($row->Sub_Package_Id == 4){
					// For Distance Waiting
					$pack_hour=$row->rate_upto_distance;			
				$fare	=	str_replace(array('[',']'),'',$pack_hour);
				$fare1 = explode(',',$fare);
				
				// 10 km
				$Efdaa = str_replace('"','',$fare1[0]);
				$fda	=explode('_',$Efdaa);
				
				// 15 KM
				$sdaa = str_replace('"','',$fare1[1]);
				$sda	=explode('_',$sdaa);
				
				// 20 KM
				$tdaa = str_replace('"','',$fare1[2]);
				$tda	=explode('_',$tdaa);
				
				// 30 KM
				$slaa = str_replace('"','',$fare1[3]);
				$lda	=explode('_',$slaa);
					
						if($row->CabType == 1){
						$EconomyMinimumCharges = $row->minimum_fare_dh;
						$EconomyAirPortFare['Economy10'] = array('e1'=>$fda[0],'e2'=>$fda[1],'e3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Economy15'] = array('e1'=>$sda[0],'e2'=>$sda[1],'e3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Economy20'] = array('e1'=>$tda[0],'e2'=>$tda[1],'e3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Economy30'] = array('e1'=>$lda[0],'e2'=>$lda[1],'e3'=>$row->Per_Km_Charge);
					}elseif($row->CabType == 2){
						$SedanMinimumCharges = $row->minimum_fare_dh;
						$EconomyAirPortFare['Sedan10'] = array('s1'=>$fda[0],'s2'=>$fda[1],'s3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Sedan15'] = array('s1'=>$sda[0],'s2'=>$sda[1],'s3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Sedan20'] = array('s1'=>$tda[0],'s2'=>$tda[1],'s3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Sedan30'] = array('s1'=>$lda[0],'s2'=>$lda[1],'s3'=>$row->Per_Km_Charge);
					}elseif($row->CabType == 3){
						$PrimeMinimumCharges = $row->minimum_fare_dh;
						$EconomyAirPortFare['Prime10'] = array('Prime1'=>$fda[0],'Prime2'=>$fda[1],'Prime3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Prime15'] = array('Prime1'=>$sda[0],'Prime2'=>$sda[1],'Prime3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Prime20'] = array('Prime1'=>$tda[0],'Prime2'=>$tda[1],'Prime3'=>$row->Per_Km_Charge);
						$EconomyAirPortFare['Prime30'] = array('Prime1'=>$lda[0],'Prime2'=>$lda[1],'Prime3'=>$row->Per_Km_Charge);
					}
					
				}
				
			}
			elseif($row->Package_Id == 104){
				// Out Station Fair
				
				
				if($row->Sub_Package_Id == 1){
					if($row->CabType == 1){
					$EconomyOutStationF['Economy'] = array('eo1'=>$row->rate_per_km_dh,'eo2'=>$row->minimum_distance_dh, 'eo3'=>$row->NightCharges,'eo4'=>$row->minimum_fare_dh);
					}elseif($row->CabType == 2){

					$EconomyOutStationF['Sedan'] = array('so1'=>$row->rate_per_km_dh,'so2'=>$row->minimum_distance_dh, 'so3'=>$row->NightCharges,'so4'=>$row->minimum_fare_dh);
					}elseif($row->CabType == 3){
						
						$EconomyOutStationF['Prime'] = array('po1'=>$row->rate_per_km_dh,'po2'=>$row->minimum_distance_dh, 'po3'=>$row->NightCharges,'po4'=>$row->minimum_fare_dh);
					}
				}elseif($row->Sub_Package_Id == 2){
					if($row->CabType == 1){
					$EconomyOutStationF['Economy'] = array('eo1'=>$row->rate_per_km_dh,'eo2'=>$row->minimum_distance_dh, 'eo3'=>$row->NightCharges,'eo4'=>$row->minimum_fare_dh);
					}elseif($row->CabType == 2){

					$EconomyOutStationF['Sedan'] = array('so1'=>$row->rate_per_km_dh,'so2'=>$row->minimum_distance_dh, 'so3'=>$row->NightCharges,'so4'=>$row->minimum_fare_dh);
					}elseif($row->CabType == 3){
						
						$EconomyOutStationF['Prime'] = array('po1'=>$row->rate_per_km_dh,'po2'=>$row->minimum_distance_dh, 'po3'=>$row->NightCharges,'po4'=>$row->minimum_fare_dh);
					}
				}elseif($row->Sub_Package_Id == 3){
					if($row->CabType == 1){
					$EconomyOutStationF['Economy'] = array('eo1'=>$row->rate_per_km_dh,'eo2'=>$row->minimum_distance_dh, 'eo3'=>$row->NightCharges,'eo4'=>$row->minimum_fare_dh);
					}elseif($row->CabType == 2){

					$EconomyOutStationF['Sedan'] = array('so1'=>$row->rate_per_km_dh,'so2'=>$row->minimum_distance_dh, 'so3'=>$row->NightCharges,'so4'=>$row->minimum_fare_dh);
					}elseif($row->CabType == 3){
						
						$EconomyOutStationF['Prime'] = array('po1'=>$row->rate_per_km_dh,'po2'=>$row->minimum_distance_dh, 'po3'=>$row->NightCharges,'po4'=>$row->minimum_fare_dh);
					}
				}elseif($row->Sub_Package_Id == 4){
					if($row->CabType == 1){
					$EconomyOutStationF['Economy'] = array('eo1'=>$row->rate_per_km_dh,'eo2'=>$row->minimum_distance_dh, 'eo3'=>$row->NightCharges,'eo4'=>$row->minimum_fare_dh);
					}elseif($row->CabType == 2){

					$EconomyOutStationF['Sedan'] = array('so1'=>$row->rate_per_km_dh,'so2'=>$row->minimum_distance_dh, 'so3'=>$row->NightCharges,'so4'=>$row->minimum_fare_dh);
					}elseif($row->CabType == 3){
						
						$EconomyOutStationF['Prime'] = array('po1'=>$row->rate_per_km_dh,'po2'=>$row->minimum_distance_dh, 'po3'=>$row->NightCharges,'po4'=>$row->minimum_fare_dh);
					}
				}
			}
		}

		
		
		$data = array(
			"Data" =>$citydata,
			"cabName"=>$citydataCab,
			"cabFor"=>$cabFordata,
			"Nationality"=>$nationalitydata,
			"Masterpackage"=>$masterpackagedata,
			"Number"=>$nosdata,
			"Nos"=>$dataNos,
			"Hours"=>$hoursdata,
			"Min"=>$citydatamin,
			"economy" => $EconomyMinimumCharges,
			"sidan" => $SedanMinimumCharges ,
			"prime" => $PrimeMinimumCharges, 
			'PTP_EconomyMinimumCharges'=>$PTP_EconomyMinimumCharges, 
			'PTP_SedanMinimumCharges'=>$PTP_SedanMinimumCharges,
			'PTP_PrimeMinimumCharges'=>$PTP_PrimeMinimumCharges,
			'localfare' => $EconomyLocalFare,
			'PTP_forFront' =>$PTP_forFront,
			'EconomyOutStationF' => $EconomyOutStationF,
			'EconomyAirPortFare' => $EconomyAirPortFare
		);
		return $data;
	}
	
	public function dropdowncity(){
		//$sql= "SELECT tblcity.name as city FROM `tblcity`  
		//INNER JOIN tblstates ON tblcity.state=tblstates.id 
		//where tblstates.Status='1'";
		$sql= "SELECT  state as city FROM `tblstates`  
		where Status='1'";
		$result = mysqli_query($this->con, $sql);
		$dataNos = mysqli_num_rows($result);
		while($data = mysqli_fetch_assoc($result)){
			$citydata[] = $data;
		} 
		return array("Data" =>$citydata,"Nos"=>$dataNos);
	}
    
    public function terminals(){
         $stateId = $_POST['state'];
         $countryId= $_POST['country'];
         /*$sql="SELECT a.terminals FROM `tblairportterminals` a INNER JOIN `tblstates` b ON a.lang = b.language INNER JOIN `tbllanguage` c ON b.language=c.id INNER JOIN `tblcountry` d ON c.country_code=d.id WHERE `state`='$stateId' AND d.country_code='$countryId'";*/
		  /*$sql="SELECT concat(t1.`pkgName`, '-', t1.state) as terminals FROM `tblairportpackage` t1 INNER JOIN tblstates t2 ON t1.package_city_code=t2.id
INNER JOIN tblcountry t3 ON t3.ID=t2.country_code
WHERE t1.`pkgName` LIKE '%Airport%' AND t2.`state`='$stateId' AND t3.country_code='$countryId'"; */
$sql="SELECT concat(t1.`pkgName`, '-', t1.state) as terminals FROM `tblairportpackage` t1 INNER JOIN tblstates t2 ON t1.package_city_code=t2.id INNER JOIN tblcountry t3 ON t3.ID=t2.country_code WHERE t2.`state`='$stateId' AND t3.country_code='$countryId'";
         //$sql= "SELECT a.user_nationality FROM `tblnationality` a INNER JOIN `tblstates` b ON a.lang = b.language INNER JOIN `tblcountry` c ON b.country_code=c.country_code WHERE `state`='$stateId' AND c.country_code='$countryId'";
        $result = mysqli_query($this->con, $sql);
        $dataNos = mysqli_num_rows($result);
        while($data = mysqli_fetch_assoc($result)){
			
			
            $citydata[] = $data;
        }
        return array("data"=>$citydata,"Nos"=>$dataNos);  
        
    } 
	
	public function UserBookingHistory(){
		$UserId=$_REQUEST['UserId'];
		$Status=$_REQUEST['Status'];
		$data = array();
		if($Status == 'ALL'){
			$cond = "";
		}elseif($Status == 'UPCOMING'){
			//$COND = "AND Status != 10 AND CONCAT(tblcabbooking.PickupDate,' ',tblcabbooking.PickupTime)>NOW()";
			$COND = "AND Status != 10 and pickup = 0 and BookingStatus = 1 AND CONCAT(tblcabbooking.PickupDate,' ',tblcabbooking.PickupTime)>NOW()";
		}elseif($Status == 'COMPLETED'){
			$COND = "AND Status > 3 and Status != 10";
		}elseif($Status == 'CANCELED'){
			$COND = "AND Status = 10 OR `BookingStatus` = '0'";
		}
		$SQL = "SELECT * FROM tblcabbooking WHERE ClientID = $UserId $COND ORDER BY ID DESC";
		$QRY = mysqli_query($this->con, $SQL);
		if($QRY){
			while($row = mysqli_fetch_assoc($QRY)){
				$date="";
				if($row['PickupDate']==date("Y-m-d")){
					$date="Today";
				}
				$nextday=date("Y-m-d",strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . " +1 day"));
				if($row['PickupDate']==$nextday){
					$date="Tomorrow";
				}
				if($date==""){
					$date=$row['PickupDate'];
				}
				$time=date("h:i a",strtotime($row['PickupTime']));
				$data[] = array('time1'=>$row['PickupTime'],'time'=>$time,'id'=>$row['ID'],'date'=>$date,'from'=>$row['PickupAddress']);
			}
			return array('Status'=>'true', 'data'=>$data);
		}
		return array('Status'=>'false');
	}

    
	public function driver_pending(){
		$id=$_REQUEST['id'];
		$type=$_REQUEST['type'];
		$result=mysqli_query($this->con,"CALL sp_w_driver_confirm('$id','$type')");
		$info=array();
		while($row=mysqli_fetch_array($result)){
			$date="";
			if($row['PickupDate']==date("Y-m-d")){
				$date="Today";
			}
			$nextday=date("Y-m-d",strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . " +1 day"));
			if($row['PickupDate']==$nextday){
				$date="Tomorrow";
			}
			if($date==""){
				$date=$row['PickupDate'];
			}
			$da=date("Y-m-d");
			$time=date("h:i a",strtotime($row['PickupTime']));
			$info[]=array('time1'=>$row['PickupTime'],'time'=>$time,'id'=>$row['ID'],'date'=>$date,'from'=>$row['PickupAddress']);
		}  
		$status="";
		if($info==""){
			$status="false";
		}else{
			$status="true";
		}
		return array("data"=>$info,"status"=>$status);
	}
    
    
    
    public function outstationCities(){
         $stateId = $_POST['state'];
         $countryId= $_POST['country'];
         $sql="SELECT a.cities FROM `tbloutstationcities` a INNER JOIN `tblstates` b ON a.lang = b.language INNER JOIN `tbllanguage` c ON b.language=c.id INNER JOIN `tblcountry` d ON c.country_code=d.id WHERE `state`='$stateId' AND d.country_code='$countryId' order by a.cities ASC";
         //$sql= "SELECT a.user_nationality FROM `tblnationality` a INNER JOIN `tblstates` b ON a.lang = b.language INNER JOIN `tblcountry` c ON b.country_code=c.country_code WHERE `state`='$stateId' AND c.country_code='$countryId'";
        $result = mysqli_query($this->con, $sql);
        $dataNos = mysqli_num_rows($result);
        while($data = mysqli_fetch_assoc($result)){
            $citydata[] = $data;
        }
        return array("data"=>$citydata,"Nos"=>$dataNos);  
        
    } 

	
	
	public function cabBilling()
	{
		
		/*
		$distance = $_REQUEST['distance'];
		$BookingId_i = $_REQUEST['bookingId'];
		$strtTime = $_REQUEST['strtTime'];
		$endTime = $_REQUEST['endTime'];
		*/
		
		$distance = $_POST['distance'];
		$BookingId_i = $_POST['bookingId'];
		$strtTime = $_POST['strtTime'];
		$endTime = $_POST['endTime']; 
		
		
		
		$result = mysqli_query($this->con,"CALL wp_cabBookingBill('$BookingId_i')") or die(mysqli_error($this->con));
        $data = mysqli_fetch_assoc($result);
				
		 
		$MinimumChrage = $data['MinimumCharge'];
		$WaitingCharge = $data['WaitingCharge_per_minute'];
		
		$tripCharge_per_minute = $data['tripCharge_per_minute'];
		
		$Per_Km_Charge = $data['Per_Km_Charge'];
		$NightCharges = $data['NightCharges'];
		$speed_per_km = $data['speed_per_km'];
		$Waitning_minutes = $data['Waitning_minutes'];
		
		$located_time = $data['Date_Timei'];
		
		$PickupTime = $data['PickTime'];
		$pickUpdate = $data['PickDate'];
		
		$Min_Distance = $data['Min_Distance'];
		$configPackageNo = $data['configPackageNo'];
		$DropPlace = $data['DropPlace'];
		$userEmailId = $data['userEmailId'];
		$cabfor = $data['cabfor'];
		
			
	    $pickUpdateTime = $pickUpdate." ".$PickupTime; // 2014-11-20 	00:00:00
		
		$nightTime = '20:00';
		$nightPickupTime = $pickUpdate." ".$nightTime;	
		
		$pickUpTimeUnix = strtotime($pickUpdateTime);
		$strtTimeUnix = strtotime($strtTime);
		$endTimeUnix = strtotime($endTime);
		
		$located_timeUnix = strtotime($located_time);
		
		$actualTimeTaken = $endTimeUnix - $strtTimeUnix;
		  
		if($located_timeUnix > $pickUpTimeUnix ){
			$newTimestamp = strtotime('+ ' . $Waitning_minutes . ' minutes', $located_timeUnix);
			$pickUpTimeUnix = $located_timeUnix;
			
		}else{
			$newTimestamp = strtotime('+ ' . $Waitning_minutes . ' minutes', $pickUpTimeUnix);
			
		}
		
		$waitingCh = 0;
		$actualTimeTakenMin = $actualTimeTaken / 60;
		
		if($strtTimeUnix > $newTimestamp){
			$waitingTimeStamp = $strtTimeUnix - $newTimestamp;
			$waitngMinutes = $waitingTimeStamp / 60;
			$waitingCh = $waitngMinutes * $WaitingCharge;
			
		}
		
		if($distance<=$Min_Distance){
			$TripCharge = $MinimumChrage;
		}else{
			$TripPerKmCharge = ($distance - $Min_Distance) * $Per_Km_Charge;
			$TripCharge = $TripPerKmCharge + $MinimumChrage;
		}
		
		
		$hourlyCharge = $actualTimeTakenMin * $tripCharge_per_minute;	
		
		
		/*if($configPackageNo  == 1){
				$TotalBill = $hourlycharge;
		}else {
			$TotalBill = $TripCharge;
		}*/
		
		
		$TotalBill=$TripCharge;
		$waitingCh=0;
		
		   mysqli_free_result($result);   
           mysqli_next_result($this->con); 
		   
		$result = mysqli_query($this->con,"CALL wp_totalPaymentCharges('$TripCharge','$waitingCh','$TotalBill','$MinimumChrage','$BookingId_i','$distance')") OR DIE(mysqli_error($this->con));
		
		$current_Time = date("Y-m-d G:i:s");
		
		$tripInfo = array("distance"=>$distance,"time"=>$current_Time,"address"=>$DropPlace,"emailId"=>$userEmailId,"totalbill"=>round($TotalBill)); 
		 $this->mailing($userEmailId,
		 "<table>
		 <tr>
		 <td>BookingId</td><td>$BookingId_i</td>
		 </tr>
		  <tr>
		 <td>Minimum Charge</td><td>$MinimumCharge</td>
		 </tr>
		  <tr>
		 <td>Per Km Charge</td><td>$Per_Km_Charge</td>
		 </tr>
		  <tr>
		 <td>Drop Place</td><td>$DropPlace</td>
		 </tr>
		  <tr>
		 <td>Minimum Distance</td><td>$Min_Distance</td>
		 </tr>
		  <tr>
		 <td>Total Distance</td><td>$distance</td>
		 </tr>
		  <tr>
		 <td>Total Bill</td><td>$TotalBill</td>
		 </tr>
		 </table>");
		return array("status"=>'true',"tripInfo"=>$tripInfo);
		
	}
	
	
	public function cabBillingCompleteMohit(){

		/* $distance = '77';
		$BookingId_i = '7198';
		$strtTime = '2016-01-25 17:20:22';
		$endTime = '2016-01-25 17:27:51';
		$address= 'Desh Bandhu Gupta Rd, New Delhi, India';
		$lat='28.652476666666665';
		$long='77.19444';
		$delay_time='22';
		$current_time='2016-01-26 17:29:20';
		$total_amount='1515';
		$total_time='00:00:04';
		$isMatching ='true'; */
		

		$distance = $_REQUEST['distance'];
		$BookingId_i = $_REQUEST['bookingId'];
		$BookingId = $BookingId_i;
		$strtTime = $_REQUEST['strtTime'];
		$endTime = $_REQUEST['endTime'];
		$address=$_REQUEST['address'];
		$lat=$_REQUEST['lat'];
		$long=$_REQUEST['lon'];
		$delay_time=$_REQUEST['delay_time'];
		$current_time=$_REQUEST['currentTime'];
		$total_amount=$_REQUEST['totalAmount'];
		$total_time=$_REQUEST['totalTime'];
		$isMatching=$_REQUEST['isMatching'];
		
$diff = abs(strtotime($endTime) - strtotime($strtTime));
$years = floor($diff / (365*60*60*24)); 
$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
$total_trip_hours = floor(($diff)/ (60*60));
$minuts = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $total_trip_hours*60*60)/ 60);
if($minuts>0){
	$total_trip_hours=$total_trip_hours+1;	
}
//echo $total_trip_hours;
		
		
		file_put_contents("amount1.txt", $distance);
		$sql2="SELECT BookingType,CarType,pickup FROM tblcabbooking WHERE ID='$BookingId_i'"; 
		$res =mysqli_query($this->con, $sql2) or die(mysqli_error($this->con));
		$data1 = $res->fetch_array();
		$BookingType=$data1['BookingType']; 
		$CarType=$data1['CarType'];
		$pickupid=$data1['pickup'];
		
		$IsMatchedCabType=$this->IsCabtypeMatched($pickupid,$BookingId_i);
		//echo $IsMatchedCabType; die;
		
		
		
		if($IsMatchedCabType){
		$sql3="CALL wp_cabBookingCompleteBillNew('$BookingId_i','$BookingType')";
		}else{
		$sql3="CALL wp_cabBookingCompleteBillNotMatched('$BookingId_i','$BookingType')";
		}
		//echo $sql3; die;
		//$sql3="CALL wp_cabBookingCompleteBillNew('$BookingId_i','$BookingType')"; 
		$result = mysqli_query($this->con, $sql3) or die(mysqli_error($this->con));
		$data = mysqli_fetch_assoc($result);
		//echo "<pre>";print_r($data); die;
		$MinimumChrage = $data['minimum_fare_dw']; 
		$book_ref = $data['booking_reference'];
		$WaitingCharge = $data['WaitingCharge_per_minute'];
		$tripCharge_per_hour = $data['tripCharge_per_minute'];
		$Per_Km_Charge = $data['rate_per_km_dw']; 
		//$NightCharges = explode(" ", $NightCharges);
		$NightChargesAmount = $data['NightCharges']; 
		$NightChargesUnit = $data['nightCharge_unit'];
		$speed_per_km = $data['speed_per_km'];
		$located_time = $data['Date_Timei'];
		$PickupTime = $data['PickTime'];
		$pickUpdate = $data['PickDate'];
		$Min_Distance = $data['minimum_distance_dw']; 
		$configPackageNo = $data['configPackageNo'];
		$DropPlace = $data['DropPlace'];
		$userEmailId = $data['userEmailId'];
		$cabfor = $data['CabFor'];
		//$BookingType = $data['BookingType'];
		$basic_tax = $data['basic_tax'];
		$night_rate_begins = $data['night_rate_begins'];
		$night_rate_ends = $data['night_rate_ends'];
		$waiting_fees = explode('_',$data['waiting_fees']);
		$Waitning_minutes = $waiting_fees[0];
		$WaitingBiforeCharge = $waiting_fees[1];
		$WaitingAfterCharge = $waiting_fees[2];

		$NightEndDate = date('Y-m-d', strtotime($pickUpdate . " +1 day"));
		$nightStartUpdateTime = $pickUpdate." ".$night_rate_begins; // 2014-11-20 	00:00:00
		$nightEndUpdateTime = $NightEndDate." ".$night_rate_ends; // 2014-11-20 	00:00:00
		$startNightTimeUnix = strtotime($nightStartUpdateTime);
		$endNightTimeUnix = strtotime($nightEndUpdateTime);
		$strtTimeUnix = strtotime($strtTime);
		$endTimeUnix = strtotime($endTime);
		$located_timeUnix = strtotime($located_time);
		
		if($BookingType==101){		
		$MinimumChrage=$data['Price'];
		/*switch($CarType)
		{
		case 1:
		$MinimumChrage=$data['Economy_Price'];
		break;
		case 2:
		$MinimumChrage=$data['Sidan_Price'];
		break;
		case 3:
		$MinimumChrage=$data['Prime_Price'];
		break;
		} */
		}
		
		//echo $isMatching; 
		if($isMatching == 'true'){
			//$data['configPackageNo']='4';
			//echo $data['configPackageNo'];
			if($data['configPackageNo'] == 1){
			$Min_Distance = $data['Min_Distance'];
			$Per_Km_Charge = $data['Per_Km_Charge'];
			$MinimumChrage = $data['MinimumCharge'];
			if($distance > $Min_Distance){
			$ExtraKM=$distance - $Min_Distance;
			$ExtraFare = $ExtraKM*$Per_Km_Charge;
			$TotalBill = $ExtraFare + $MinimumChrage;
			}else{
			$TotalBill = $MinimumChrage;								
			}
			}
			
			elseif($data['configPackageNo'] == 2)
			{
				
				if($total_trip_hours>$cabfor){
					$extra_hours=$total_trip_hours-$cabfor;
					
					$TotalBill = $cabfor*$tripCharge_per_hour+$extra_hours*$tripCharge_per_hour;
				}else{
					$TotalBill = $cabfor*$tripCharge_per_hour;
				}
			
			}
			
			elseif($data['configPackageNo'] == 3)
			{
			$Min_Distance = $data['minimum_distance_dh'];
			$Per_Km_Charge = $data['rate_per_hour_dh'];
			$MinimumChrage = $data['minimum_fare_dh'];
			if($distance > $Min_Distance){
			$ExtraKM=$distance - $Min_Distance;
			$ExtraFare = $ExtraKM*$Per_Km_Charge;
			$TotalKMBill = $ExtraFare + $MinimumChrage;
			}else{
			$TotalKMBill = $MinimumChrage;								
			}
			
				if($total_trip_hours>$cabfor){
					$extra_hours=$total_trip_hours-$cabfor;
					
					$TotalHourBill = $extra_hours*$tripCharge_per_hour;
				}
					$TotalBill = $TotalKMBill+$TotalHourBill;	
			}
			
			
			
			elseif($data['configPackageNo'] == 4)
			{
			
			$Min_Distance = $data['minimum_distance_dw'];
			$Per_Km_Charge = $data['rate_per_km_dw'];
			$MinimumChrage = $data['minimum_fare_dw'];
			
				if($distance > $Min_Distance){
			$ExtraKM=$distance - $Min_Distance;
			$ExtraFare = $ExtraKM*$Per_Km_Charge;
			$TotalKMBill = $ExtraFare + $MinimumChrage;
			}else{
			$TotalKMBill = $MinimumChrage;								
			}
			$waiting_charges='0';
			if($delay_time > $Waitning_minutes){
			$exactMinute = $delay_time - $Waitning_minutes;
			if($exactMinute < 10){
			$waiting_charges = $exactMinute*$WaitingBiforeCharge;
			}else{
			 $waiting_charges = $exactMinute*$WaitingAfterCharge;
			}
			}
			
			$TotalBill = $TotalKMBill+$waiting_charges;
			}
			
			file_put_contents("amount1.txt", $TotalBill);
			mysqli_free_result($result);
			mysqli_next_result($this->con);
			
			$NightCharges = "";
			if ((($strtTimeUnix >= $startNightTimeUnix) && ($strtTimeUnix <= $endNightTimeUnix)) || (($endTimeUnix >= $startNightTimeUnix) && ($endTimeUnix <= $endNightTimeUnix))){
				if($NightChargesUnit == "Rs"){
					$NightCharges = $NightChargesAmount;
				}
				else{
					$NightCharges = ($TotalBill * $NightChargesAmount) / 100;
				}
			}
			
			$TotalBill=$TotalBill+$NightCharges;
			$basic_tax = (($TotalBill * $basic_tax) / 100);
			$TotalBill=$TotalBill+$basic_tax;		
			
			$TotalBill=$TotalBill>$total_amount?$TotalBill:$total_amount; 
			$result = mysqli_query($this->con,"CALL wp_totalPaymentCharges('$TripCharge','$waiting_charges','$TotalBill','$MinimumChrage','$BookingId_i','$distance','$Per_Km_Charge','$Min_Distance','$basic_tax','','$address','$lat','$long','$total_time')") or die(mysqli_error($this->con));
			$current_Time = date("Y-m-d G:i:s");
			$tripInfo = array("totalbill"=>round($TotalBill),"totalTax"=>$basic_tax);
			mysqli_free_result($result);
			mysqli_next_result($this->con);
			$this->send_sms_new2($BookingId,$flag="invoice");
			return array("status"=>'true',"tripInfo"=>$tripInfo);
			exit;
		}else{
			$TotalBill = $total_amount;
			file_put_contents("amount1.txt", $TotalBill);
			mysqli_free_result($result);
			mysqli_next_result($this->con);
			//echo "CALL wp_totalPaymentCharges('0','0','$TotalBill','$MinimumChrage','$BookingId_i','$distance','$Per_Km_Charge','$Min_Distance','$basic_tax','','$address','$lat','$long','$total_time')"; die;
			$result = mysqli_query($this->con,"CALL wp_totalPaymentCharges('0','0','$TotalBill','$MinimumChrage','$BookingId_i','$distance','$Per_Km_Charge','$Min_Distance','$basic_tax','','$address','$lat','$long','$total_time')") OR DIE(mysqli_error($this->con));
			$current_Time = date("Y-m-d G:i:s");
			$tripInfo = array("totalbill"=>round($TotalBill),"totalTax"=>$basic_tax);
			mysqli_free_result($result);
			mysqli_next_result($this->con);
			$this->send_sms_new2($BookingId,$flag="invoice");
			return array("status"=>'true',"tripInfo"=>$tripInfo);
			exit;
		}
	}
	
        public function get_booking_disp_for_web($distance,$BookingId_i)
        {
		$result = mysqli_query($this->con,"CALL wp_estimated_price('$BookingId_i')") or die(mysqli_error($this->con));
                $data = mysqli_fetch_assoc($result);
				
		 
		$MinimumChrage = $data['MinimumCharge'];
		$Per_Km_Charge = $data['Per_Km_Charge'];
		$Waitning_minutes = $data['Waitning_minutes'];
		$Min_Distance = $data['Min_Distance'];
		$configPackageNo = $data['configPackageNo'];
		$cabfor = $data['CabFor'];
		$BookingType = $data['BookingType'];
                $tripCharge_per_hour = $data['tripCharge_per_minute'];
			
		$charge_type="";
		if($BookingType==101){
			if($cabfor!=0){
				$charge_type="hour";
                                $hourlyCharge=$cabfor*$tripCharge_per_hour;
				$Bill = $hourlyCharge;
			}
			else{
				$charge_type="distance";
                                $TripCharge = $MinimumChrage;
				$Bill = $TripCharge;
			}
		}
		else{
			if($configPackageNo  == 1){
                            
                            if($distance < $Min_Distance){
                                $TripCharge = $MinimumChrage;
                            }else{
                                $TripPerKmCharge = ($distance - $Min_Distance) * $Per_Km_Charge;
                                $TripCharge = $TripPerKmCharge + $MinimumChrage;
                            }
				$charge_type="distance";
					$Bill = $TripCharge;
			}elseif($configPackageNo  == 2) {
				$charge_type="hour";
                                 $hourlyCharge=$cabfor*$tripCharge_per_hour;
				$Bill = $hourlyCharge;
			}
		}
		   mysqli_free_result($result);   
           mysqli_next_result($this->con); 
		   
		
		
               $TotalBill=$Bill;
		
		$tripInfo = array("totalbill"=>round($TotalBill),'min_distance'=>$Min_Distance,'per_km_chg'=>$Per_Km_Charge,'waiting_minutes'=>$data['Waitning_minutes'],'wait_charge'=>$data['WaitingCharge_per_minute']); 
		 
		return array("status"=>'true',"tripInfo"=>$tripInfo);   
            
          }
		  
		  
	public function Estimated_Price($Distance=0, $BookingId=0){
		$EstimatedPrice = 0;
		$NightCharges = 0;
		$BasicTax = 0;
		$sql = "SELECT * FROM tblcabbooking INNER JOIN tblbookingbill ON tblcabbooking.BookingType = tblbookingbill.BookingTypeId AND tblcabbooking.CarType = tblbookingbill.CabType LEFT JOIN tblcabfor ON tblcabbooking.CabFor = tblcabfor.id INNER JOIN tblmasterpackage ON tblcabbooking.BookingType = tblmasterpackage.Package_Id INNER JOIN tblsubpackage ON tblmasterpackage.Sub_Package_Id = tblsubpackage.Sub_Package_Id WHERE tblcabbooking.ID = $BookingId";
		$qry = mysqli_query($this->con, $sql) or die(mysqli_error());
		$data = mysqli_fetch_assoc($qry) or die(mysqli_error());
		
		if($data['BookingType'] == 101){
			if($data['Sub_Package_Id'] == 1){
				$EstimatedPrice = $data['Kms'] * $data['Per_Km_Charge'];
				if($data['MinimumCharge'] > $EstimatedPrice){
					$EstimatedPrice = $data['MinimumCharge'];
				}
			}elseif($data['Sub_Package_Id'] == 2){
				$EstimatedPrice = $data['Hrs'] * $data['tripCharge_per_minute'];
			}elseif($data['Sub_Package_Id'] == 3){
				 $EstimatedPrice_PerKm = $data['Kms'] * $data['rate_per_km_dh'];
				 $EstimatedPrice_PerHr = $data['Hrs'] * $data['rate_per_hour_dh'];
				if($EstimatedPrice_PerKm > $EstimatedPrice_PerHr){
					$EstimatedPrice = $EstimatedPrice_PerKm;
				}else{
					$EstimatedPrice = $EstimatedPrice_PerHr;
				}
				if($data['minimum_fare_dh'] > $EstimatedPrice){
					$EstimatedPrice = $data['minimum_fare_dh'];
				}
			}elseif($data['Sub_Package_Id'] == 4){
				$EstimatedPrice = $data['Kms'] * $data['rate_per_km_dw'];
				if($data['minimum_fare_dw'] > $EstimatedPrice){
					$EstimatedPrice = $data['minimum_fare_dw'];
				}
			}
		}elseif($data['BookingType'] == 102){
			if($data['Sub_Package_Id'] == 1){
				$EstimatedPrice = $data['EstimatedDistance'] * $data['Per_Km_Charge'];
				if($data['MinimumCharge'] > $EstimatedPrice){
					$EstimatedPrice = $data['MinimumCharge'];
				}
			}elseif($data['Sub_Package_Id'] == 2){
				$EstimatedPrice = $data['EstimatedTime'] * $data['tripCharge_per_minute'];
			}elseif($data['Sub_Package_Id'] == 3){
				$EstimatedPrice_PerKm = $data['EstimatedDistance'] * $data['rate_per_km_dh'];
				$EstimatedPrice_PerHr = $data['EstimatedTime'] * $data['rate_per_hour_dh'];
				if($EstimatedPrice_PerKm > $EstimatedPrice_PerHr){
					$EstimatedPrice = $EstimatedPrice_PerKm;
				}else{
					$EstimatedPrice = $EstimatedPrice_PerHr;
				}
				if($data['minimum_fare_dh'] > $EstimatedPrice){
					$EstimatedPrice = $data['minimum_fare_dh'];
				}
			}elseif($data['Sub_Package_Id'] == 4){
				$EstimatedPrice = $data['Kms'] * $data['rate_per_km_dw'];
				if($data['minimum_fare_dw'] > $EstimatedPrice){
					$EstimatedPrice = $data['minimum_fare_dw'];
				}
			}
		}
		elseif($data['BookingType'] == 105){
			if($data['Sub_Package_Id'] == 1){
				$EstimatedPrice = $data['EstimatedDistance'] * $data['Per_Km_Charge'];
				if($data['MinimumCharge'] > $EstimatedPrice){
					$EstimatedPrice = $data['MinimumCharge'];
				}
			}elseif($data['Sub_Package_Id'] == 2){
				$EstimatedPrice = $data['EstimatedTime'] * $data['tripCharge_per_minute'];
			}elseif($data['Sub_Package_Id'] == 3){
				$EstimatedPrice_PerKm = $data['EstimatedDistance'] * $data['rate_per_km_dh'];
				$EstimatedPrice_PerHr = $data['EstimatedTime'] * $data['rate_per_hour_dh'];
				if($EstimatedPrice_PerKm > $EstimatedPrice_PerHr){
					$EstimatedPrice = $EstimatedPrice_PerKm;
				}else{
					$EstimatedPrice = $EstimatedPrice_PerHr;
				}
				if($data['minimum_fare_dh'] > $EstimatedPrice){
					$EstimatedPrice = $data['minimum_fare_dh'];
				}
			}elseif($data['Sub_Package_Id'] == 4){
				$EstimatedPrice = $data['Kms'] * $data['rate_per_km_dw'];
				if($data['minimum_fare_dw'] > $EstimatedPrice){
					$EstimatedPrice = $data['minimum_fare_dw'];
				}
			}
		}
		if(strtotime($data['PickupTime']) >= strtotime($data['night_rate_begins']) and strtotime($data['PickupTime']) < strtotime($data['night_rate_ends'])){
			if($data['nightCharge_unit'] == 'Rs'){
				$NightCharges = $data['NightCharges'];
			}else{
				$NightCharges = ($EstimatedPrice * $data['NightCharges'])/100;
			}
		}
		
		$BasicTax = (($EstimatedPrice + $NightCharges) * $data['basic_tax'])/100;
		
		$EstimatedPrice = $EstimatedPrice + $NightCharges + $BasicTax;

		$tripInfo = array('totalbill'=>round($EstimatedPrice),
			'min_distance'=>$data['Min_Distance'],
			'per_km_chg'=>$data['Per_Km_Charge'],
			'waiting_minutes'=>$data['Waitning_minutes'],
			'wait_charge'=>$data['WaitingCharge_per_minute']
		);
		return array("status"=>'true',"tripInfo"=>$tripInfo);
	}

	public function estimatedprice($distance,$BookingId_i){
		
		$result = mysqli_query($this->con,"CALL wp_estimated_price('$BookingId_i')") or die(mysqli_error($this->con));
		$data = mysqli_fetch_assoc($result);
				 
		$MinimumChrage = $data['MinimumCharge'];
		$WaitingCharge = $data['WaitingCharge_per_minute'];
		$tripCharge_per_hour = $data['tripCharge_per_minute'];
		$Per_Km_Charge = $data['Per_Km_Charge'];
		
		$NightCharges = $data['NightCharges'];
		$NightCharges = explode(" ", $NightCharges);
		$NightChargesAmount = $NightCharges[0];
		$NightChargesUnit = '';
		if(isset($NightCharges[1])){
			$NightChargesUnit = $NightCharges[1];
		}
		
		$speed_per_km = $data['speed_per_km'];
		$Waitning_minutes = $data['Waitning_minutes'];
		
		$located_time = $data['Date_Timei'];
		
		$PickupTime = $data['PickTime'];
		$pickUpdate = $data['PickDate'];
		
		$Min_Distance = $data['Min_Distance'];
		$configPackageNo = $data['configPackageNo'];
		$DropPlace = $data['DropPlace'];
		$userEmailId = $data['userEmailId'];
		$cabfor = $data['CabFor'];
		$BookingType = $data['BookingType'];
		$basic_tax = $data['basic_tax'];
		$night_rate_begins = $data['night_rate_begins'];
		$night_rate_ends = $data['night_rate_ends'];
			
	    
		//$strtTimeUnix = $pickUpdate." ".$strtTime;
		//$endTimeUnix = $pickUpdate." ".$endTime;
		$NightEndDate = date('Y-m-d', strtotime($pickUpdate . " +1 day"));
		$nightStartUpdateTime = $pickUpdate." ".$night_rate_begins; // 2014-11-20 	00:00:00
		$nightEndUpdateTime = $NightEndDate." ".$night_rate_ends; // 2014-11-20 	00:00:00
		
		
		$startNightTimeUnix = strtotime($nightStartUpdateTime);
		$endNightTimeUnix = strtotime($nightEndUpdateTime);
		$strtTimeUnix = strtotime($strtTime);
		$endTimeUnix = strtotime($endTime);
		
		$located_timeUnix = strtotime($located_time);
		
		$actualTimeTaken = $endTimeUnix - $strtTimeUnix;
		  
		if($located_timeUnix > $pickUpTimeUnix ){
			$newTimestamp = strtotime('+ ' . $Waitning_minutes . ' minutes', $located_timeUnix);
			$pickUpTimeUnix = $located_timeUnix;
		}else{
			$newTimestamp = strtotime('+ ' . $Waitning_minutes . ' minutes', $pickUpTimeUnix);
		}
		
		$waitingCh = 0;
		
		$actualTimeTakenHour = (int)(($actualTimeTaken/60)/60);
		$actualTimeTakenHourRemainder = $actualTimeTaken % 3600;
		if($actualTimeTakenHourRemainder!=0){
			$actualTimeTakenHour=$actualTimeTakenHour+1;
		}
		
		if($strtTimeUnix > $newTimestamp){
			$waitingTimeStamp = $strtTimeUnix - $newTimestamp;
			$waitngMinutes = $waitingTimeStamp / 60;
			$waitingCh = $waitngMinutes * $WaitingCharge;
			
		}
		
		if($distance < $Min_Distance){
			$TripCharge = $MinimumChrage;
		}else{
			$TripPerKmCharge = ($distance - $Min_Distance) * $Per_Km_Charge;
			$TripCharge = $TripPerKmCharge + $MinimumChrage;
		}
		
		
		//$hourlyCharge = $actualTimeTakenHour * $tripCharge_per_hour;	
		$extraTripCharge = "";
		if($actualTimeTakenHour>$cabfor){
			$extraTripHours = $actualTimeTakenHour - $cabfor;
			$extraTripCharge = $extraTripHours * $tripCharge_per_hour;
		}
		
		$hourlyCharge = ($cabfor * $tripCharge_per_hour) + $extraTripCharge;
		$minimum_hourly_charge=$cabfor * $tripCharge_per_hour;
		$charge_type="";
		
		
		
		if($BookingType==101){
			if($cabfor!=0){
				$charge_type="hour";
				$Bill = $hourlyCharge;
			}
			else{
				$charge_type="distance";
				$Bill = $TripCharge;
			}
		}
		else{
			if($configPackageNo  == 1){
				$charge_type="distance";
					$Bill = $TripCharge;
			}elseif($configPackageNo  == 2) {
				$charge_type="hour";
				$Bill = $hourlyCharge;
			}
		}
		
		
		
		$NightCharges = "";
		$basic_tax = ($Bill * $basic_tax) / 100;
		
		if ((($strtTimeUnix >= $startNightTimeUnix) && ($strtTimeUnix <= $endNightTimeUnix)) || (($endTimeUnix >= $startNightTimeUnix) && ($endTimeUnix <= $endNightTimeUnix))){
			if($NightChargesUnit == "Rs"){
				$NightCharges = $NightChargesAmount;
			}
			else{
				$NightCharges = ($Bill * $NightChargesAmount) / 100;
			}
		}
		else{
		}
		//echo $Bill.'<br>';
		//echo $NightCharges.'<br>';
		//echo $basic_tax;
		$TotalBill = $Bill + $NightCharges;
		
		
		$waitingCh=0;
		
		   mysqli_free_result($result);   
           mysqli_next_result($this->con); 
		   if($charge_type=="distance")
		   {
		//$result = mysqli_query($this->con,"CALL wp_totalPaymentCharges('$TripCharge','$waitingCh','$TotalBill','$MinimumChrage','$BookingId_i','$distance','$Per_Km_Charge','$Min_Distance','$basic_tax','')") OR DIE(mysqli_error($this->con));
		   }else{
			   
			   
		//$result = mysqli_query($this->con,"CALL wp_totalPaymentCharges('$hourlyCharge','$waitingCh','$TotalBill','$minimum_hourly_charge','$BookingId_i','$distance','','','$basic_tax','$tripCharge_per_hour')") OR DIE(mysqli_error($this->con));
		   
			   
			   
		   }
		
		$current_Time = date("Y-m-d G:i:s");
		
		$tripInfo = array("distance"=>$distance,"time"=>$current_Time,"address"=>$DropPlace,"emailId"=>$userEmailId,"totalbill"=>round($TotalBill),"starttime"=>$strtTime,"endtime"=>$endTime,"tax"=>$basic_tax,"bill"=>$Bill,'min_distance'=>$Min_Distance,'per_km_chg'=>$Per_Km_Charge,'waiting_minutes'=>$data['Waitning_minutes'],'wait_charge'=>$data['WaitingCharge_per_minute']); 
		return array("status"=>'true',"tripInfo"=>$tripInfo);
		
	}
	public function distance($lat1, $lon1, $lat2, $lon2, $unit) {

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
        
	public function livemeter()
	{
		
		
		$distance = $_REQUEST['distance'];
		$BookingId_i = $_REQUEST['bookingId'];
		$strtTime = $_REQUEST['strtTime'];
		$endTime = $_REQUEST['endTime'];
		//$endTime=date("Y-m-d H:i:s");
		/*
		$distance = $_POST['distance'];
		$BookingId_i = $_POST['bookingId'];
		$strtTime = $_POST['strtTime'];
		$endTime = $_POST['endTime']; 
		
		*/
		
		$result = mysqli_query($this->con,"CALL wp_cabBookingCompleteBill('$BookingId_i')") or die(mysqli_error($this->con));
        $data = mysqli_fetch_assoc($result);
				
		 
		$MinimumChrage = $data['MinimumCharge'];
		$WaitingCharge = $data['WaitingCharge_per_minute'];
		$tripCharge_per_hour = $data['tripCharge_per_minute'];
		$Per_Km_Charge = $data['Per_Km_Charge'];
		
		$NightCharges = $data['NightCharges'];
		$NightCharges = explode(" ", $NightCharges);
		$NightChargesAmount = $NightCharges[0];
		$NightChargesUnit = $NightCharges[1];
		
		$speed_per_km = $data['speed_per_km'];
		$Waitning_minutes = $data['Waitning_minutes'];
		
		$located_time = $data['Date_Timei'];
		
		$PickupTime = $data['PickTime'];
		$pickUpdate = $data['PickDate'];
		
		$Min_Distance = $data['Min_Distance'];
		$configPackageNo = $data['configPackageNo'];
		$DropPlace = $data['DropPlace'];
		$userEmailId = $data['userEmailId'];
		$cabfor = $data['CabFor'];
		$BookingType = $data['BookingType'];
		$basic_tax = $data['basic_tax'];
		$night_rate_begins = $data['night_rate_begins'];
		$night_rate_ends = $data['night_rate_ends'];
			
	    
		//$strtTimeUnix = $pickUpdate." ".$strtTime;
		//$endTimeUnix = $pickUpdate." ".$endTime;
		$NightEndDate = date('Y-m-d', strtotime($pickUpdate . " +1 day"));
		$nightStartUpdateTime = $pickUpdate." ".$night_rate_begins; // 2014-11-20 	00:00:00
		$nightEndUpdateTime = $NightEndDate." ".$night_rate_ends; // 2014-11-20 	00:00:00
		
		
		$startNightTimeUnix = strtotime($nightStartUpdateTime);
		$endNightTimeUnix = strtotime($nightEndUpdateTime);
		$strtTimeUnix = strtotime($strtTime);
		$endTimeUnix = strtotime($endTime);
		
		$located_timeUnix = strtotime($located_time);
		
		$actualTimeTaken = $endTimeUnix - $strtTimeUnix;
		  
		if($located_timeUnix > $pickUpTimeUnix ){
			$newTimestamp = strtotime('+ ' . $Waitning_minutes . ' minutes', $located_timeUnix);
			$pickUpTimeUnix = $located_timeUnix;
			
		}else{
			$newTimestamp = strtotime('+ ' . $Waitning_minutes . ' minutes', $pickUpTimeUnix);			
		}
		
		$waitingCh = 0;
		
		$actualTimeTakenHour = (int)(($actualTimeTaken/60)/60);
		$actualTimeTakenHourRemainder = $actualTimeTaken % 3600;
		if($actualTimeTakenHourRemainder!=0){
			$actualTimeTakenHour=$actualTimeTakenHour+1;
		}
		
		if($strtTimeUnix > $newTimestamp){
			$waitingTimeStamp = $strtTimeUnix - $newTimestamp;
			$waitngMinutes = $waitingTimeStamp / 60;
			$waitingCh = $waitngMinutes * $WaitingCharge;
			
		}
		
		if($distance < $Min_Distance){
			$TripCharge = $MinimumChrage;
		}else{
			$TripPerKmCharge = ($distance - $Min_Distance) * $Per_Km_Charge;
			$TripCharge = $TripPerKmCharge + $MinimumChrage;
		}
		
		
		//$hourlyCharge = $actualTimeTakenHour * $tripCharge_per_hour;	
		$extraTripCharge = "";
		if($actualTimeTakenHour>$cabfor){
			$extraTripHours = $actualTimeTakenHour - $cabfor;
			$extraTripCharge = $extraTripHours * $tripCharge_per_hour;
		}
		
		$hourlyCharge = ($cabfor * $tripCharge_per_hour) + $extraTripCharge;
		$minimum_hourly_charge=$cabfor * $tripCharge_per_hour;
		$charge_type="";
		if($BookingType==101){
			if($cabfor!=0){
				$charge_type="hour";
				$Bill = $hourlyCharge;
			}
			else{
				$charge_type="distance";
				$Bill = $TripCharge;
			}
		}
		else{
			if($configPackageNo  == 1){
				$charge_type="distance";
					$Bill = $TripCharge;
			}elseif($configPackageNo  == 2) {
				$charge_type="hour";
				$Bill = $hourlyCharge;
			}
		}
		$NightCharges = "";
		$basic_tax = ($Bill * $basic_tax) / 100;
		
		if ((($strtTimeUnix >= $startNightTimeUnix) && ($strtTimeUnix <= $endNightTimeUnix)) || (($endTimeUnix >= $startNightTimeUnix) && ($endTimeUnix <= $endNightTimeUnix))){
			if($NightChargesUnit == "Rs"){
				$NightCharges = $NightChargesAmount;
			}
			else{
				$NightCharges = ($Bill * $NightChargesAmount) / 100;
			}
		}
		else{
		}
		//echo $Bill.'<br>';
		//echo $NightCharges.'<br>';
		//echo $basic_tax;
		$TotalBill = $Bill + $NightCharges;
		
		
		$waitingCh=0;
		
		   mysqli_free_result($result);   
           mysqli_next_result($this->con); 
		   if($charge_type=="distance")
		   {
		//$result = mysqli_query($this->con,"CALL wp_totalPaymentCharges('$TripCharge','$waitingCh','$TotalBill','$MinimumChrage','$BookingId_i','$distance','$Per_Km_Charge','$Min_Distance','$basic_tax','')") OR DIE(mysqli_error($this->con));
		   }else{
			   
			   
		//$result = mysqli_query($this->con,"CALL wp_totalPaymentCharges('$hourlyCharge','$waitingCh','$TotalBill','$minimum_hourly_charge','$BookingId_i','$distance','','','$basic_tax','$tripCharge_per_hour')") OR DIE(mysqli_error($this->con));
		   
			   
			   
		   }
		
		$current_Time = date("Y-m-d G:i:s");
		
		$tripInfo = array("distance"=>$distance,"time"=>$current_Time,"address"=>$DropPlace,"emailId"=>$userEmailId,"totalbill"=>round($TotalBill),"starttime"=>$strtTime,"endtime"=>$endTime,"tax"=>$basic_tax,"bill"=>$Bill); 
		return array("status"=>'true',"tripInfo"=>$tripInfo);
		
	}
	
	
	public function cabbilling_new()
	{
		
		$distance = $_POST['distance'];
		$BookingId_i = $_POST['bookingId'];
		$strtTime = $_POST['strtTime'];
		$endTime = $_POST['endTime']; 
		
		
		$result = mysqli_query($this->con,"CALL wp_cabBookingBill('$BookingId_i')") or die(mysqli_error($this->con));
        $data = mysqli_fetch_assoc($result);
				
		 
		$MinimumChrage = $data['MinimumCharge'];
		$WaitingCharge = $data['WaitingCharge_per_minute'];
		
		$tripCharge_per_minute = $data['tripCharge_per_minute'];
		
		$Per_Km_Charge = $data['Per_Km_Charge'];
		$NightCharges = $data['NightCharges'];
		$speed_per_km = $data['speed_per_km'];
		$Waitning_minutes = $data['Waitning_minutes'];
		
		$located_time = $data['Date_Timei'];
		
		$PickupTime = $data['PickTime'];
		$pickUpdate = $data['PickDate'];
		
		$Min_Distance = $data['Min_Distance'];
		$configPackageNo = $data['configPackageNo'];
		$DropPlace = $data['DropPlace'];
		$userEmailId = $data['userEmailId'];
		
			
	    $pickUpdateTime = $pickUpdate." ".$PickupTime; // 2014-11-20 	00:00:00
		
		$nightTime = '20:00';
		$nightPickupTime = $pickUpdate." ".$nightTime;	
		
		$pickUpTimeUnix = strtotime($pickUpdateTime);
		$strtTimeUnix = strtotime($strtTime);
		$endTimeUnix = strtotime($endTime);
		
		$located_timeUnix = strtotime($located_time);
		
		$actualTimeTaken = $endTimeUnix - $strtTimeUnix;
		  
		if($located_timeUnix > $pickUpTimeUnix ){
			$newTimestamp = strtotime('+ ' . $Waitning_minutes . ' minutes', $located_timeUnix);
			$pickUpTimeUnix = $located_timeUnix;
			
		}else{
			$newTimestamp = strtotime('+ ' . $Waitning_minutes . ' minutes', $pickUpTimeUnix);
			
		}
		
		$waitingCh = 0;
		$actualTimeTakenMin = $actualTimeTaken / 60;
		
		if($strtTimeUnix > $newTimestamp){
			$waitingTimeStamp = $strtTimeUnix - $newTimestamp;
			$waitngMinutes = $waitingTimeStamp / 60;
			$waitingCh = $waitngMinutes * $WaitingCharge;
			
		}
		
		if($distance < $Min_Distance){
			$TripCharge = $MinimumChrage;
		}else{
			$TripPerKmCharge = ($distance - $Min_Distance) * $Per_Km_Charge;
			$TripCharge = $TripPerKmCharge + $MinimumChrage;
		}
		
		
		$hourlyCharge = $actualTimeTakenMin * $tripCharge_per_minute;	
		
		
		if($configPackageNo  == 2){
			$hourlyCharge = 0;
		}else if($configPackageNo  == 3){
			$TripCharge = 0;
		}
		
		
				
		$TotalBill = $TripCharge;
		
		$waitingCh=0;
		
		   mysqli_free_result($result);   
           mysqli_next_result($this->con); 
		   
		$result = mysqli_query($this->con,"CALL wp_totalPaymentCharges('$TripCharge','$waitingCh','$TotalBill','$MinimumChrage','$BookingId_i')") OR DIE(mysqli_error($this->con));
		
		$current_Time = date("Y-m-d G:i:s");
		
		$tripInfo = array("distance"=>$distance,"time"=>$current_Time,"address"=>$DropPlace,"emailId"=>$userEmailId,"totalbill"=>round($TotalBill)); 
		
		return array("status"=>'true',"tripInfo"=>$tripInfo);
	}
	
	// start cab billling 


	public function cabBillingPerDistance()
	{
		
		$distance = $_POST['distane'];
		$BookingId = $_POST['bookingId'];
		$strtTime = $_POST['strtTime'];
				
		/*
		$distance = $_REQUEST['distane'];
		$BookingId = $_REQUEST['bookingId'];
		$strtTime = $_REQUEST['strtTime'];
		*/
				
		$result = mysqli_query($this->con,"CALL wp_cabBookingBill('$BookingId')");
        $data = mysqli_fetch_assoc($result);
		 
		$MinimumChrage = $data['MinimumCharge'];
		$WaitingCharge = $data['WaitingCharge'];
		$Per_Km_Charge = $data['Per_Km_Charge'];
		$NightCharges = $data['NightCharges'];
		$speed_per_km = $data['speed_per_km'];
		$Waitning_minutes = $data['Waitning_minutes'];
		
		$located_time = $data['Date_Timei'];
		
		$PickupTime = $data['PickTime'];
		$pickUpdate = $data['PickDate'];
		
		$Min_Distance = $data['Min_Distance'];
		
		 
		$pickUpdateTime = $pickUpdate." ".$PickupTime; // 2014-11-20 	00:00:00
		
		
		$nightTime = '20:00';
		$nightPickupTime = $pickUpdate." ".$nightTime;	
		
		$pickUpTimeUnix = strtotime($pickUpdateTime);
		$strtTimeUnix = strtotime($strtTime);
		
		
		$located_timeUnix = strtotime($located_time);
		  
		if($located_timeUnix > $pickUpTimeUnix ){
			$newTimestamp = strtotime('+ ' . $Waitning_minutes . ' minutes', $located_timeUnix);
			$pickUpTimeUnix = $located_timeUnix;
			
			
		}else{
			$newTimestamp = strtotime('+ ' . $Waitning_minutes . ' minutes', $pickUpTimeUnix);
			
		}
		
		$waitingCh = 0;
		$waitingTimeStamp = 0; 
		$Waitning_minutesN = 0;
		$extraTime = 0;
		
		$approxTime = $distance / $speed_per_km;
		$approxTimeSec = $approxTime * 60 * 60;
		
		
		if($strtTimeUnix > $newTimestamp){
			$waitingTimeStamp = $strtTimeUnix - $newTimestamp;
			$waitngMinutes = (int)$waitingTimeStamp / 60;
			$waitingCh = $waitngMinutes * $WaitingCharge;
		
			
		}else{
			
			if($strtTimeUnix > $pickUpTimeUnix){
				$waitingTimeStamp = $strtTimeUnix - $pickUpTimeUnix;
				$restTimeSec = ($Waitning_minutes * 60) - $waitingTimeStamp;
				$waitingChr = $restTimeSec / 60;
				
				$waitingCh = $waitingChr * $WaitingCharge;
			}
			
		}
		
		if($distance < $Min_Distance){
			$TripCharge = $MinimumChrage;
		}else{
			$TripPerKmCharge = ($distance - $Min_Distance) * $Per_Km_Charge;
			$TripCharge = $TripPerKmCharge + $MinimumChrage;
		}
		
		$TotalBill = $TripCharge + $waitingCh;
		
		   mysqli_free_result($result);   
           mysqli_next_result($this->con); 
		   
				
		 return array("respone" => $TotalBill);
	}
	
	public function additional_payment(){
		$toll=$_REQUEST['TollTax'];
		$road=$_REQUEST['RoadTax'];
		$other=$_REQUEST['Othertax'];
		file_put_contents("get".uniqid().".txt",print_r($_REQUEST,true));
		$total=$toll+$road+$other;
		$bookingid=$_REQUEST['booking_id'];
		mysqli_query($this->con,"UPDATE tblbookingcharges SET `totalBill`=(`totalBill`+$total) WHERE BookingID='$bookingid'");
		
		$result=mysqli_query($this->con,"SELECT `totalBill` FROM tblbookingcharges WHERE BookingID='$bookingid'");
		
		$data=mysqli_fetch_assoc($result);
		
		return array("status"=>"true","data"=>$data);
		
	}
	
        
        
        public function vehicles(){
            
             $data = $_REQUEST['term'];
             
             $result = mysqli_query($this->con,"CALL wp_vehicles('$data')");
            
             $dataAll =array();
            
            while($dataList = mysqli_fetch_assoc($result)){
                  
            

        
	
	 $dataAll[]=array("label"=>$dataList['name'],
						    "value"=>$dataList['name']); 
                                        
                         }

                    echo json_encode($dataAll);
                    
                    return $dataAll;
        }
        
	public function cab_reporting(){
		$currentTime = $_REQUEST['driver_time'];
		$BookingId = $_REQUEST['booking_id'];
		$driverlat = $_REQUEST['lat'];
		$driver_long = $_REQUEST['long'];
		$driver_Id = $_REQUEST['driverId'];
		$result = mysqli_query($this->con,"CALL wp_reporting('$BookingId','$driverlat','$driver_long','$driver_Id')");
		$data =  mysqli_fetch_assoc($result);
		$date = $data['pickdatebook'];
		$time = $data['picktimebook'];
		$pickupTime = "$date"." $time"; 
		$pickupTime	= strtotime($pickupTime);
		$currentTime = $currentTime;
		$actual_time=explode(" ",$currentTime);
		$currentTime = strtotime($currentTime);
		if($currentTime>$pickupTime){
		$arrival_time_pre="00:00:00";
		$arrival_time_post=$actual_time[1];
		}else{
		$arrival_time_pre=$actual_time[1];
		$arrival_time_post="00:00:00";
		}
		mysqli_free_result($result);   
		mysqli_next_result($this->con);		
		$query1="UPDATE tblcabbooking SET arrival_time_pre = '$arrival_time_pre', arrival_time_post='$arrival_time_post' where ID= '".$BookingId."'";
		$result1 =  mysqli_query($this->con,$query1) or die(mysqli_error($this->con));			
		$this->send_sms_new2($BookingId,$flag="reporting");
		$clientNumber = $data['usernumber'];
		return array("responce"=>"true");
	}
        
        
        public function driverVehicles(){
            
           $result = mysqli_query($this->con,"CALL wp_listOfVehicles()") or die(mysqli_error($this->con)); 
           $nos = mysqli_num_rows($result);
           while($data = mysqli_fetch_assoc($result)){
               
               $vehicles[] = array($data['name']);
               
           }
            return array("data"=>$vehicles);
        }
      
	  
	public function user_booking_history(){
		$user_id = $_REQUEST['userid'];
		$user_history = $_REQUEST['hId'];
		if($user_history == 1 ){
			$result = mysqli_query($this->con,"CALL wp_user_history_all('$user_id')") or die(mysqli_error($this->con));  
			while($data = mysqli_fetch_assoc($result)){
				if($data['PickupDate'] == date('Y-m-d')){
					$day="";
					$date = "Today";
				}else{
					$day=$data['PickupDate'];
					$date="";
				}
				$conformned[] = array("date"=>$day.'   '.$data['PickupTime']." from ".$data['PickupArea'].' '.$date,"ids"=>$data['ID']);    
			}
			return array("data"=>$conformned,"status"=>'true');
		}elseif($user_history == 2 ){
			$date = date('Y-m-d H:i:s');
			$date1 = str_replace('-', '/', $date);
			$tomorrow = date('Y-m-d H:i:s',strtotime($date1 . "+1 days"));
			$tomorrow2 = date('Y-m-d',strtotime($date1 . "+1 days"));
			$result = mysqli_query($this->con,"CALL wp_user_history_pending('$user_id','$tomorrow')") or die(mysqli_error($this->con));  
			while($data = mysqli_fetch_assoc($result)){
				if($data['PickupDate'] == $tomorrow2){
					$day="";
					$date = "Tomorrow";
				}else{
					$day=$data['PickupDate'];
					$date="";
			}
			$conformned[] = array("date"=>$day.'   '.$data['PickupTime']." from ".$data['PickupArea'].' '.$date,"ids"=>$data['ID']);    				
			}
			return array("data"=>$conformned,"status"=>'true');
		}elseif($user_history == 3 ){
		$result = mysqli_query($this->con,"CALL wp_user_history_completed('$user_id')") or die(mysqli_error($this->con));  
		while($data = mysqli_fetch_assoc($result)){
			$conformned[] = array("date"=>$data['PickupDate'].'   '.$data['PickupTime']." from ".$data['PickupArea'],"ids"=>$data['ID']);    
			}
			return array("data"=>$conformned,"status"=>'true');
		}else{
			return array("status"=>'false');
		}
	}

	public function user_booking_view_pages(){

		$booking_id= $_POST['booking_ids'];
		$date = date('Y-m-d');
		$date1 = str_replace('-', '/', $date);
		$tomorrow = date('Y-m-d',strtotime($date1 . "+1 days"));

		$result = mysqli_query($this->con,"CALL wp_user_booking_details('$booking_id')") or die(mysqli_error($this->con));
		$data = mysqli_fetch_assoc($result);

		if($data['PickupDate']>=$tomorrow){
			return array("name"=>$data['UserName'],"refno"=>$data['booking_reference'],"btime"=>$data['BookingDate'],"picktime"=>$data['PickupTime'],"adds"=>$data['PickupArea'],"cartype"=>$data['CarType'],"dname"=>$data['FirstName'],"status"=>"true");

		}else {



			return array("name"=>$data['UserName'],"refno"=>$data['booking_reference'],"btime"=>$data['BookingDate'],"picktime"=>$data['PickupTime'],"adds"=>$data['PickupArea'],"cartype"=>$data['CarType'],"dname"=>$data['FirstName'],"minimumbe"=>$data['minimumCharge'],"mimdis"=>$data['minimum_distance'],"waiting"=>$data['appox_waiting_minute'],"mindischarges"=>$data['distance_charge'],"minwaitcharges"=>$data['duration_charge'],"totaldis"=>$data['actual_distance'],"totaltime"=>$data['actual_driven_duration'],"totalbill"=>$data['totalBill'],"status"=>"true");
		}


	}

	public function hellomoney(){
		$userid= $_POST['userid'];
		$recVia=$_POST['recvia'];
		$result = mysqli_query($this->con,"CALL wp_user_helo_money('$userid','$recVia')") or die(mysqli_error($this->con)); 
		$data = mysqli_fetch_assoc($result);
		if($data['counter']==1){
			return array("status"=>'true');
		}else{
			return array("status"=>'false');
		}
	}
        
       
        
	function rateCard_old(){
		$booking_id = $_REQUEST['bookingId'];
		$data = mysqli_query($this->con,"CALL `wp_a_rate_card`('$booking_id')") or die(mysqli_error($this->con));
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
			$Per_Km_Charge = $row['Per_Km_Charge'];
			$rowCab[]= array("MinimumCharge"=>$MinimumCharge,"Min_Distance"=>$Min_Distance,"Per_Km_Charge"=>$Per_Km_Charge,"WaitingFee"=>$waitningfeeKey);
		}		
		return array("data"=>$rowCab);
	}

	public function cab_tracking(){
		$query=mysqli_query($this->con,"SELECT tbluser.id, tbluser.LoginName,tbluser.Latitude,tbluser.Longtitude1,tbldriver.`status`,tbluser.loginStatus FROM tbluser JOIN tbldriver ON tbluser.ID=tbldriver.UID and tbluser.loginstatus=1;");
		$data=array();
		while($row=mysqli_fetch_assoc($query))
		{
			if($row['status']==0 && $row['loginStatus']==1)
			{
				$row['map']="available";

			}else{
				if($row['status']==0 && $row['loginStatus']==0)
				{
					$row['map']="logout";

				}else{

					$row['map']="hired";
				}


			}
			$data[]=$row;
		}
		return array('data'=>$data);

	}

	public function driver_tracking(){

		$bookingID = $_REQUEST['booking_id'];

		$query = mysqli_query($this->con,"CALL `wp_a_driver_tracking`('$bookingID')") or die(mysqli_error($this->con));

		$row = mysqli_fetch_array($query);

		$data[] = array("Latitude"=>$row['Latitude'],"Longitude"=>$row['Longtitude1']);

		if($query == 1){
			return array("data"=>$data,"status"=>"true");
		}else{
			return array("status"=>"false");
		}
	}

	public function driver_rating(){

		$userid= $_REQUEST['userid'];
		$driverid = $_REQUEST['driverid'];
		$rating = $_REQUEST['rating'];
		$bookingID = $_REQUEST['booking_id'];
		$result = mysqli_query($this->con,"CALL wp_driver_rating('$driverid','$userid','$rating','$bookingID')") or die(mysqli_error($this->con));
		$data = mysqli_fetch_assoc($result);

		if($data['avgamount']>0){
			return array("status"=>'true');

		}else{
			return array("status"=>'false');
		}

	}



	public function user_feedback(){
		$bookingID = $_REQUEST['booking_id'];
		$user_feedback = $_REQUEST['feedback'];
		$data = mysqli_query($this->con,"CALL `wp_a_user_feedback`('$bookingID','$user_feedback')") or die(mysqli_error($this->con));
		if($data == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
		}
	} 
	
	public function FeedbackForAndroidApp(){
		if(isset($_REQUEST)){
			$data = array(
				'UserId'=>$_REQUEST['UserId'],
				'Helpful'=>$_REQUEST['Helpful'],
				'EasyToUse'=>$_REQUEST['EasyToUse'],
				'Design'=>$_REQUEST['Design'],
				'Feedback'=>$_REQUEST['Feedback'],
				'AppType'=>$_REQUEST['AppType'],
			);
			$SQL = "INSERT INTO tbl_feedback_for_user_app (`UserId`, `Helpful`, `EasyToUse`, `Design`, `Feedback`, `AppType`) VALUES ('".$data['UserId']."', '".$data['Helpful']."', '".$data['EasyToUse']."', '".$data['Design']."', '".$data['Feedback']."', '".$data['AppType']."') ";
			$QRY = mysqli_query($this->con, $SQL);
			if($QRY){
				return array('Status'=>true, 'msg'=>'Thanks for feedback to us.');
			}
			return array('Status'=>false, 'msg'=>'Oops ! Something went wrong.');
		}
		return array('Status'=>false, 'msg'=>'Oops ! Something went wrong.');
	}


	public function user_TotalBill(){

		$bookingID = $_REQUEST['booking_id'];

		$query = mysqli_query($this->con,"CALL `sp_a_user_total_bill`('$bookingID')") or die(mysqli_error($this->con));

		$row = mysqli_fetch_array($query);

		$data[] = array("Total Bill"=>$row['totalBill'],"Total Distance"=>$row['actual_distance']);

		if($query == 1){
			return array("data"=>$data,"status"=>"true");
		}else{
			return array("status"=>"false");
		}
	}



	public function nearest_driver(){

		$lat = $_REQUEST['lat'];
		$lang = $_REQUEST['long'];

		$result = mysqli_query($this->con,"SELECT tbldriver.TypeOfvehicle,`Latitude`,`Longtitude1` FROM tbluser inner join tbldriver on tbldriver.UID = tbluser.ID  WHERE `UserType`=3 and tbldriver.TypeOfvehicle = 1") or die(mysqli_error($this->con));
		$result2 = mysqli_query($this->con,"SELECT tbldriver.TypeOfvehicle,`Latitude`,`Longtitude1` FROM tbluser inner join tbldriver on tbldriver.UID = tbluser.ID  WHERE `UserType`=3 and tbldriver.TypeOfvehicle = 2") or die(mysqli_error($this->con));
		$result3 = mysqli_query($this->con,"SELECT tbldriver.TypeOfvehicle,`Latitude`,`Longtitude1` FROM tbluser inner join tbldriver on tbldriver.UID = tbluser.ID  WHERE `UserType`=3 and tbldriver.TypeOfvehicle = 3") or die(mysqli_error($this->con));
		$query = mysqli_query($this->con,"CALL `wp_user_distance`()") or die(mysqli_error($this->con));
		while($data = mysqli_fetch_assoc($result))
		{
			$distance[] = $this->distanceuserdiver($lat,$lang,$data['Latitude'],$data['Longtitude1'],"K");
			$latLong[] = array($data['Latitude'],$data['Longtitude1']);
		}
		asort($distance);

		foreach($distance as $dist=>$v)
		{
			$key=$dist;
			break;
		}
		$econ_latlon=$latLong[$key];
		$econ_distan=$distance[$key];

		while($data1 = mysqli_fetch_assoc($result2))
		{
			$distance1[] = $this->distanceuserdiver($lat,$lang,$data1['Latitude'],$data1['Longtitude1'],"K");
			$latLong1[] = array($data1['Latitude'],$data1['Longtitude1']);
		}
		asort($distance1);

		foreach($distance1 as $dist1=>$v1)
		{
			$key1=$dist1;
			break;
		}
		$sidan_latlon=$latLong1[$key1];
		$sidan_distan=$distance1[$key1];

		while($data2 = mysqli_fetch_assoc($result3))

		{
			$distance2[] = $this->distanceuserdiver($lat,$lang,$data2['Latitude'],$data2['Longtitude1'],"K");
			$latLong2[] = array($data2['Latitude'],$data2['Longtitude1']);
		}
		asort($distance2);

		foreach($distance2 as $dist2=>$v2)
		{
			$key2=$dist2;
			break;
		}
		$prime_latlon=$latLong2[$key2];
		$prime_distan=$distance2[$key2];

		while($value = mysqli_fetch_assoc($query))
		{
			$all_distance[] = $this->distanceuserdiver($lat,$lang,$value['Latitude'],$value['Longtitude1'],"K");
			$all_latLong[] = array($value['Latitude'],$value['Longtitude1']);
		}
		asort($all_distance);

		foreach($all_distance as $distt=>$vv)
		{
			$keyy=$distt;
			break;
		}
		$latlon=$all_latLong[$keyy];
		$distan=$all_distance[$keyy];

		return array("economyDistance"=>$econ_distan,"economyLatLong"=>$econ_latlon,"sidanDistance"=>$sidan_distan,"sidanLatLong"=>$sidan_latlon,"primeDistance"=>$prime_distan,"primeLatLong"=>$prime_latlon,"nearestDistance"=>$econ_distan,"nearestLatLong"=>$econ_latlon,"status"=>'true');
	}

	public function rate_app(){
		$userID = $_REQUEST['user_id'];
		$rating_point = $_REQUEST['rating_pt'];
		$data = mysqli_query($this->con,"CALL `sp_a_app_rating`('$rating_point','$userID')") or die(mysqli_error($this->con));
		if($data == 1){
			return array("status"=>"true");
		}else{
			return array("status"=>"false");
	}
            
        }
        public function cabtooltip(){

      $result = mysqli_query($this->con,"SELECT MinimumCharge FROM `tblbookingbill` WHERE `Id` = 1");
      $row1 = mysqli_fetch_assoc($result);

      $result = mysqli_query($this->con,"SELECT MinimumCharge FROM `tblbookingbill` WHERE `Id` = 2");
      $row2 = mysqli_fetch_assoc($result);

      $result = mysqli_query($this->con,"SELECT MinimumCharge FROM `tblbookingbill` WHERE `Id` = 3");
      $row3 = mysqli_fetch_assoc($result);

      return array("local" => $row1['MinimumCharge'],"economy" => $row2['MinimumCharge'],"sedan" => $row3['MinimumCharge']);
     }
     
    public function fetchDriverId(){
         
        $token = $_REQUEST['token'];
        $result = mysqli_query($this->con,"select * from `tbluser` where `token` = '$token'") or die(mysqli_error($this->con));
		$count = mysqli_num_rows($result);
        $data = mysqli_fetch_array($result);
        
        if($count == 1){
			return array("status"=>"true","driverId"=>$data['ID'],"userType"=>$data['UserType'],"password"=>$data['Password']);
		}else{
			return array("status"=>"false");
        }
    }
    
	public function fetchUserId(){
		$sql = "select 
		tblcompany.CompanyName, 
		tbluser.amount as currentbalance, 
		tbluserinfo.UID, 
		tbluser.UserType,
		tbluserinfo.FirstName,
		tbluserinfo.LastName,
		tbluserinfo.MobNo,
		tbluserinfo.LandNo,
		tbluserinfo.Email,
		tbluserinfo.image,
		tbluserinfo.AltEmail,
		tbluserinfo.City,
		tbluserinfo.Address1,
        tbluserinfo.Address2		
		FROM tbluser 
		LEFT JOIN tbluserinfo ON tbluser.ID = tbluserinfo.UID
		LEFT JOIN tblcompany ON tbluserinfo.CompanyID = tblcompany.ID 		 
		WHERE token = '".$_REQUEST['token']."'";
		$qry = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
		$row = mysqli_fetch_assoc($qry);
		if($qry){
			return array("status"=>"true","company"=>$row['CompanyName'],"currentbalance"=>$row['currentbalance'],"id"=>$row['UID'],
			"user_type"=>$row['UserType'],"fname"=>$row['FirstName'],"lname"=>$row['LastName'],"city"=>$row['City'], "address1"=>$row['Address1'], "address2"=>$row['Address2'],
			"mobileNo"=>$row['MobNo'],"landline"=>$row['LandNo'],"email"=>$row['Email'],"alt_email"=>$row['AltEmail'],"prof_img"=>$row['image']);
		}
		return array("status"=>"false");
	}
     
    
      public function profileDriver(){
        
      	$token = $_REQUEST['token'];
		//$token=	"0d61819c56d706d7deb2228f6d90f711"
		$SQL = "select * from `tbluser` where `token` = '$token'";
        $result = mysqli_query($this->con,$SQL) or die(mysqli_error($this->con));
        $data = mysqli_fetch_array($result);  
          $driverId= $data['ID'];       
        $result =  mysqli_query($this->con,"CALL `wp_a_driverEditProfile`('$driverId')") or die(mysqli_error($this->con));
        $data = mysqli_fetch_assoc($result);
        mysqli_free_result($result);   
        mysqli_next_result($this->con);
        
        $result1 =  mysqli_query($this->con,"select currentbalance from tbl_driver_transaction where user_Id = '$driverId' order by time desc") or die(mysqli_error($this->con));
        $data1 = mysqli_fetch_array($result1);
        //$driverData = array();
        if($data['account_no'] == ""){
			$account = "No";
		}else{
			$account = "Yes";
		}
       // while($data = mysqli_fetch_assoc($result)){
//             if(!file_exists($this->base_url.$data['vehicleimg'])){
//                 $data['vehicleimg']="public/image/vehicle.png"; 
//             }
//             if(!file_exists($data['image'])){
//                 $data['image']="public/image/default_user.png"; 
//             }
             if($data['group_name'] == ""){
                 $group = "No Company";
             }else{
                 $group = $data['group_name'];
             }
             //$addr = str_replace(',', ',<br>', $data["Address"]);
             /* $addr="";
             $addr2 = explode(',',$data["Address"]);
             for($i=0;$i<=count($addr2);$i++)
             {
                 if($i==0){
                  $addr=$addr2[$i];   
                 }else{
                 
                 if(($i%2)==0 and $i!=0)
                 {
                 $addr =$addr.",<br>".$addr2[$i];
                 }else{
                      $addr =$addr.",".$addr2[$i];
                 }
                 }
             } 
             $v_addr="";
             $v_addr2 = explode(',',$data["v_address"]);
             for($i=0;$i<=count($v_addr2);$i++)
             {
                 if($i==0){
                  $v_addr=$v_addr2[$i];   
                 }else{
                 
                    if(($i%2)==0 and $i!=0)
                    {
                    $v_addr =$v_addr.",<br>".$v_addr2[$i];
                    }else{
                         $v_addr =$v_addr.",".$v_addr2[$i];
                    }
                 }
             }*/
			 //echo"<pre>";
			//print_r($data);exit;
            $driverData = array(
								"ID_tbluserinfo"=>$data['ID_tbluserinfo'],
								"ID_tbldriver"=>$data['ID'],
                                "ID_tbluser"=>$data['UID'],
                                "name"=>$data['FirstName'],
                                "lastname"=>$data['LastName'],
                                "fathername"=>$data['FatherName'],
                                "refn"=>$data['refname'],
                                "city"=>$data['City'],
                                "state"=>$data['State'],
                                "country"=>$data['Country'],
                                "pin"=>$data['PinCode'],
                                "account"=>"$account",
                                 "createDate"=>$data['created_date'],
                                 "email"=>$data["Email"],
                                 "db"=>$data['dateofbirth'],
                                 "gender" =>$data['gender'],
                                 "address"=>$data["Address"],
                                 "ofcadds"=>$data['OfcAddress'],
                                 //"addr"=>$addr,
                                 "offc_addr"=>$data["OfcAddress"],
                                 "phone"=>$data['ContactNo'],
                                 "verify"=>$data['isVerifY'],
                                 "userName"=>$data['userName'],
                                 "fleet"=>$data['TotalFleetNo'],
                                 "vehicleName"=>$data['CabName'],
								 "expiryDate"=>$data['expiry'],
								 "Cartype"=>$data['CabType'],
								 "CabModel"=>$data['CabModel'],
								 "CabRegistrationNumber"=>$data['CabRegistrationNumber'],
								 "licence_state"=>$data['licence_state'],
								 "cn"=>$data['cn'],
								 "sn"=>$data['sn'],
								 "countryName"=>$data['countryName'],
								 "ignitionType"=>$data['CabIgnitionType'],
								 "panNo"=>$data['PanCardNo'],
                                 "modelV"=>$data['ModelOfVehicle'],
                                 "lstate"=>$data['licence_state'],
                                 "licence"=>$data['DrivingLicenceNo'],
                                 "vehicle"=>$data['VehicleRegistrationNo'],
                                 "amount"=>$data['SecurityAmt'],
                                 "active"=>$data['is_active'],
                                // "block"=>$data['is_block'],
                                 //"barred"=>$data['is_barred'],
                                 "zone"=>$data['zone'],
                                 "route"=>$data['route_know'],
                                 "prflocation"=>$data['pref_location'],
                                 "weekoff"=>$data['week_off'],
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
                                 "user_type" => $data['UserType'],
                                "company" => $group,
                                "v_id"=>$data["v_id"],
                                "v_name"=>$data["vendor_name"],
                                "v_email"=>$data["vendor_email"],
                                "v_city"=>$data["v_city"],
                                "v_state"=>$data["v_state"],
                                "v_country"=>$data["v_country"],
                                "v_pin"=>$data["v_pin"],
                                "v_mob"=>$data["v_mob"],
                                //"v_address"=>$v_addr,
                                "bank_account_name"=>$data["bank_account_name"],
								"acount_holder"=>$data["acount_holder"],
								"bank_adds"=>$data["bank_adds"],
								"account_no"=>$data["account_no"],
								"rtgs_neft"=>$data["rtgs_neft"],
								"ac_nonac"=>$data["ac_nonac"],
								"rc_no"=>$data["rc_no"],
                                );
          // echo"<pre>";
           //print_r($driverData);
          // echo"</pre>";
                                //file_put_contents('ree.txt', print_r($driverData,TRUE));
       //echo"<pre>";print_r($driverData);echo"</pre>";
        return array("data"=>$driverData);
   }
   
	public function userOrderHistory(){
		$token = $_REQUEST['token'];
		$result =  mysqli_query($this->con,"SELECT * FROM tbluser WHERE `token`='$token'") or die(mysqli_error($this->con));
		$row = mysqli_fetch_array($result);
		$id = $row['ID'];
		$hour=mysqli_query($this->con,"CALL `sp_a_userOrderHistory`('$id')") or die(mysqli_error($this->con));
		$data=array();
		while($row=mysqli_fetch_assoc($hour)){
			$data[]=array($row['book_ref'],$row['ordertime'],"",$row['booking_type'],$row['partner'],$row['clientname'],$row['departure'],$row['drop_area'],$row['status']);
		}
		if(mysqli_num_rows($hour) >0 ){
			return array("status"=>"true","data"=>$data);
		}else{
			return array("status"=>"false","data"=>"");
		}
	}	
	
   
	public function userOrderHistory_new(){
		$data = array();
		$status = 'false';
		if(isset($_REQUEST['token'])){
			$sql = "SELECT 
					tbluser.ID as UserID, 
					tbluser.token as TokenNumber, 
					concat(tbluserinfo.FirstName, tbluserinfo.LastName) as UserName, 
					tbluser.LoginName as Email, 
					tbluser.UserNo as MobileNumber, 
					tblcabbooking.ID as BookingID,
					tblcabbooking.booking_reference as book_ref, 
					CONCAT(tblcabbooking.PickupDate,' ',tblcabbooking.PickupTime) as ordertime, 
					tblcablistmgmt.name as veh_name, 
					tblmasterpackage.Master_Package as booking_type, 
					concat(tblclient.clientName,'<br>(',tblappid.`type`,')') as partner, 
					CONCAT(tbluserinfo.FirstName,' ',tbluserinfo.LastName) as clientname, 
					CONCAT(tblcabbooking.PickupAddress,' ',tblcabbooking.PickupArea) as departure, 
					tblcabstatus.`status` as `status`
					FROM tblcabbooking 
					LEFT JOIN tbluser ON tblcabbooking.ClientID = tbluser.ID 
					LEFT JOIN tbluserinfo on tbluser.ID = tbluserinfo.UID
					LEFT JOIN tbldriver ON tblcabbooking.pickup=tbldriver.uid 
					LEFT JOIN tblcablistmgmt ON tblcablistmgmt.id = tbldriver.vehicleId 
					INNER JOIN tblmasterpackage ON tblcabbooking.BookingType=tblmasterpackage.Package_Id
					INNER JOIN tblappid ON tblappid.id=tblcabbooking.partner 
					INNER JOIN tblclient ON tblappid.clientId=tblclient.id 
					JOIN tblcabstatus ON tblcabbooking.`status`=tblcabstatus.`status_id`
					WHERE tblcabstatus.type='cab' AND tbluser.token = '".$_REQUEST['token']."'";			
			$qry = mysqli_query($this->con, $sql) or die(mysqli_error($this->con));
			if(mysqli_num_rows($qry)){
				while($row = mysqli_fetch_assoc($qry)){
					$data[] = $data[]=array($row['book_ref'],$row['ordertime'],"",$row['booking_type'],$row['partner'],$row['clientname'],$row['departure'],$row['drop_area'],$row['status']);
				}
				$status = 'true';
			}
		}
		//print'<pre>';
		//print_r($data);
		return array('status'=>$status, 'data'=>$data);
	}	



	public function driverOrderHistory(){
		$BookingID =$_REQUEST['BookingID'];
		$ids = $_REQUEST['ids'];
		$Driverid =$_REQUEST['Did'];
		$Trantype = $_REQUEST['type'];
		$createdSince = $_REQUEST['from'];
		$createdTo = $_REQUEST['to'];

		if($createdSince != "" && $createdTo == ""){
			$createdTo = date('Y-m-d');
		}elseif($createdSince == "" && $createdTo != ""){
			$createdSince = '0000-00-00';
		}elseif($createdSince == "" && $createdTo == ""){
			$createdSince = '0000-00-00';
			$createdTo = date('Y-m-d');
		}
		if($Trantype != ''){
			if($BookingID != ''){
				$hour=mysqli_query($this->con,"SELECT CONCAT(tblcabbooking.DropAddress,' ',tblcabbooking.DropArea) as drop_area,tblcabbooking.ID as id,
			tblcabbooking.booking_reference as book_ref,tblcabbooking.BookingDate as booking_date,tblcablistmgmt.name,tblbookingcharges.totalBill,
tbluserinfo.vehicleimg,tblmasterpackage.Master_Package as booking_type,tblcabtype.CabType as category,CONCAT(tblcabbooking.PickupDate,' ',tblcabbooking.PickupTime) as ordertime,
concat(tblclient.clientName,'<br>(',tblappid.`type`,')') as partner,
CONCAT(tbluserinfo.FirstName,' ',tbluserinfo.LastName) as clientname,
CONCAT(tblcabbooking.PickupAddress,' ',tblcabbooking.PickupArea) as departure,tbldriver.VehicleRegistrationNo as reg,tblcabstatus.`status` as `status`
 FROM tblcabbooking JOIN tblcabstatus ON tblcabbooking.`status`=tblcabstatus.`status_id` 
 INNER JOIN tbluserinfo ON tbluserinfo.UID=tblcabbooking.pickup 
 JOIN tblmasterpackage ON tblcabbooking.BookingType=tblmasterpackage.Package_Id
JOIN tblcabtype ON tblcabbooking.CarType=tblcabtype.Id 
 INNER JOIN tbldriver ON tblcabbooking.pickup=tbldriver.uid 
  JOIN tblappid ON tblappid.id=tblcabbooking.partner 
  INNER JOIN tbluser ON tblcabbooking.pickup = tbluser.ID
 JOIN tblclient ON tblappid.clientId=tblclient.id 
 INNER JOIN tblbookingcharges ON tblcabbooking.ID=tblbookingcharges.BookingID 
 LEFT JOIN tblcomplaint ON tblcabbooking.booking_reference=tblcomplaint.BookingID 
 INNER JOIN tblcablistmgmt ON tblcablistmgmt.id = tbldriver.vehicleId
 INNER JOIN tblbookingregister ON (tblbookingregister.bookingid=tblcabbooking.ID and tblbookingregister.driverId=tblcabbooking.pickup) 
 WHERE  tblcabstatus.`type`='cab' AND tbluser.token = '$ids' AND tblcabbooking.pickup='$Driverid' AND tbldriver.UID='$Driverid'  
 AND tblbookingregister.driverId='$Driverid' AND tblcabbooking.booking_reference='$BookingID';") or die(mysqli_error($this->con));
			}elseif($Trantype == 'All'){
				$hour=mysqli_query($this->con,"SELECT CONCAT(tblcabbooking.DropAddress,' ',tblcabbooking.DropArea) as drop_area,tblcabbooking.ID as id,
			tblcabbooking.booking_reference as book_ref,tblcabbooking.BookingDate as booking_date,tblcablistmgmt.name,tblbookingcharges.totalBill,
tbluserinfo.vehicleimg,tblmasterpackage.Master_Package as booking_type,tblcabtype.CabType as category,CONCAT(tblcabbooking.PickupDate,' ',tblcabbooking.PickupTime) as ordertime,
concat(tblclient.clientName,'<br>(',tblappid.`type`,')') as partner,
CONCAT(tbluserinfo.FirstName,' ',tbluserinfo.LastName) as clientname,
CONCAT(tblcabbooking.PickupAddress,' ',tblcabbooking.PickupArea) as departure,tbldriver.VehicleRegistrationNo as reg,tblcabstatus.`status` as `status`
 FROM tblcabbooking JOIN tblcabstatus ON tblcabbooking.`status`=tblcabstatus.`status_id` 
 INNER JOIN tbluserinfo ON tbluserinfo.UID=tblcabbooking.pickup 
 JOIN tblmasterpackage ON tblcabbooking.BookingType=tblmasterpackage.Package_Id
JOIN tblcabtype ON tblcabbooking.CarType=tblcabtype.Id 
 INNER JOIN tbldriver ON tblcabbooking.pickup=tbldriver.uid 
  JOIN tblappid ON tblappid.id=tblcabbooking.partner 
  INNER JOIN tbluser ON tblcabbooking.pickup = tbluser.ID
 JOIN tblclient ON tblappid.clientId=tblclient.id 
 INNER JOIN tblbookingcharges ON tblcabbooking.ID=tblbookingcharges.BookingID 
 LEFT JOIN tblcomplaint ON tblcabbooking.booking_reference=tblcomplaint.BookingID 
 INNER JOIN tblcablistmgmt ON tblcablistmgmt.id = tbldriver.vehicleId
 INNER JOIN tblbookingregister ON (tblbookingregister.bookingid=tblcabbooking.ID and tblbookingregister.driverId=tblcabbooking.pickup) 
 WHERE  tblcabstatus.`type`='cab' AND tbluser.token = '$ids' AND tblcabbooking.pickup='$Driverid' AND tbldriver.UID='$Driverid'  
 AND tblbookingregister.driverId='$Driverid' AND DATE(tblcabbooking.BookingDate) 
 BETWEEN '$createdSince' and '$createdTo' order by tblcabbooking.ID desc;") or die(mysqli_error($this->con));
			}else{
				$hour=mysqli_query($this->con,"SELECT CONCAT(tblcabbooking.DropAddress,' ',tblcabbooking.DropArea) as drop_area,tblcabbooking.ID as id,
			tblcabbooking.booking_reference as book_ref,tblcabbooking.BookingDate as booking_date,tblcablistmgmt.name,tblbookingcharges.totalBill,
tbluserinfo.vehicleimg,tblmasterpackage.Master_Package as booking_type,tblcabtype.CabType as category,CONCAT(tblcabbooking.PickupDate,' ',tblcabbooking.PickupTime) as ordertime,
concat(tblclient.clientName,'<br>(',tblappid.`type`,')') as partner,
CONCAT(tbluserinfo.FirstName,' ',tbluserinfo.LastName) as clientname,
CONCAT(tblcabbooking.PickupAddress,' ',tblcabbooking.PickupArea) as departure,tbldriver.VehicleRegistrationNo as reg,tblcabstatus.`status` as `status`
 FROM tblcabbooking JOIN tblcabstatus ON tblcabbooking.`status`=tblcabstatus.`status_id` 
 INNER JOIN tbluserinfo ON tbluserinfo.UID=tblcabbooking.pickup 
 JOIN tblmasterpackage ON tblcabbooking.BookingType=tblmasterpackage.Package_Id
JOIN tblcabtype ON tblcabbooking.CarType=tblcabtype.Id 
 INNER JOIN tbldriver ON tblcabbooking.pickup=tbldriver.uid 
  JOIN tblappid ON tblappid.id=tblcabbooking.partner 
  INNER JOIN tbluser ON tblcabbooking.pickup = tbluser.ID
 JOIN tblclient ON tblappid.clientId=tblclient.id 
 INNER JOIN tblbookingcharges ON tblcabbooking.ID=tblbookingcharges.BookingID 
 LEFT JOIN tblcomplaint ON tblcabbooking.booking_reference=tblcomplaint.BookingID 
 INNER JOIN tblcablistmgmt ON tblcablistmgmt.id = tbldriver.vehicleId
 INNER JOIN tblbookingregister ON (tblbookingregister.bookingid=tblcabbooking.ID and tblbookingregister.driverId=tblcabbooking.pickup) 
 WHERE  tblcabstatus.`type`='cab' AND tbluser.token = '$ids' AND tblbookingregister.status='$Trantype' AND tblcabbooking.pickup='$Driverid' AND tbldriver.UID='$Driverid'  
 AND tblbookingregister.driverId='$Driverid' AND DATE(tblcabbooking.BookingDate) 
 BETWEEN '$createdSince' and '$createdTo' order by tblcabbooking.ID desc;") or die(mysqli_error($this->con));
			}			
		}else{ 
			$hour=mysqli_query($this->con,"CALL `sp_a_driverOrderHistory`('$ids','$createdSince','$createdTo')") or die(mysqli_error($this->con));
		}
		

		while($row=mysqli_fetch_array($hour))
		{

			if($row['vehicleimg'] != ''){
				$image = $_SERVER['REQUEST_URI'];
				$image=explode('/',$image);
				//print_r($image);
				$image1='/'.$image[1].'/'.$row['vehicleimg'];
			}
			$orderTime = $row['ordertime'];
			$splitTimeStamp = explode(" ",$orderTime);
			$Pickdate = $splitTimeStamp[0];
			$Picktime = $splitTimeStamp[1];
			$booking_date = $row['booking_date'];
			$BOOKINGTimeStamp = explode(" ",$booking_date);
			$Bookdate = $BOOKINGTimeStamp[0];
			$Booktime = $BOOKINGTimeStamp[1];
			//"<img src='$image1' style='width:50%'>"
			//$row['partner']
			//$row['book_amount'],
			$hour_list[]=array($row['id'],$Bookdate,'<label data="'.$row['id'].'" class="clickme" style="padding: 0px 10px 0px 0;color:#08c; cursor:pointer;">'.$row['book_ref'].
			'</label>',$row['booking_type'],$row['category'],$row['clientname'],$Pickdate.'<br/>'.$Picktime,$row['departure'],
			$row['drop_area'],$row['status']);

		}
		if(mysqli_num_rows($hour) >0 ){
			return array("status"=>"true","data"=>$hour_list);
		}else{
			return array("status"=>"false","data"=>"");
		}


	}





	function userCreditHistory(){
		$token = $_REQUEST['token'];
		$result = mysqli_query($this->con,"select * from `tbluser` where `token` = '$token'") or die(mysqli_error($this->con));
		$data1 = mysqli_fetch_array($result);
		$id = $data1['ID'];
		$result =  mysqli_query($this->con,"SELECT * FROM tbl_user_transaction inner JOIN tblcabbooking ON tbl_user_transaction.booking_ref=tblcabbooking.booking_reference where tbl_user_transaction.user_id='$id' ORDER BY time DESC;") or die(mysqli_error($this->con));
		$data = array();
		while($row= mysqli_fetch_assoc($result)){
			$data[] =$row; 
		}
		return array("status"=>"true","data"=>$data);
	}
   
   
   
   public function driverAccountBalance()
	{
		    $token = $_REQUEST['token'];
                    $result = mysqli_query($this->con,"select * from `tbluser` where `token` = '$token'") or die(mysqli_error($this->con));
                    $data1 = mysqli_fetch_array($result);
                    $id = $data1['ID'];

                   $result =  mysqli_query($this->con,"CALL `sp_driver_acc_balance`('$id')") or die(mysqli_error($this->con));
                   $data = array();
                   $i=1;
                   while($row= mysqli_fetch_assoc($result)){
                            $data[] =array($i++,$row["reason"],$row["Master_Package"],$row["status"],$row["date"],$row["currentbalance"]); 
                     }
         
         return array("status"=>"true","data"=>$data);
	}
                
	public function contentManager(){
		$page_id=$_REQUEST['page_id'];
		$result =  mysqli_query($this->con,"select * from tblregiontext where text_id='$page_id';") or die(mysqli_error($this->con));
		$data = mysqli_fetch_assoc($result);
		if(mysqli_num_rows($result)>0){
			return array("status"=>"true","data"=> utf8_encode(stripslashes($data['text'])));
		}
	}         
                
   
	public function booking_information(){
		$id=$_REQUEST['booking_id'];
		$token = $_REQUEST['token'];
		$hour=mysqli_query($this->con,"CALL `sp_w_a_booking_information`('$id','$token')") or die(mysqli_error($this->con));
		$booking=array();
		while($row=mysqli_fetch_array($hour)){
			$booking[]=array('time'=>date('d-M-Y H:i:s',strtotime($row['time'])),'status'=>$row['status'],'message'=>$row['message']);
		}
		return array("data"=>$booking);
	}


	public function payment_information(){
		$id=$_REQUEST['booking_id'];
		$token = $_REQUEST['token'];
		$hour=mysqli_query($this->con,"CALL `sp_w_a_payment_information`('$id','$token')") or die(mysqli_error($this->con));
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
				'created_at'=>date("d-M-y H:i:s",strtotime($row['addedtime'])),
				'is_paid'=>$is_paid,
				'paid_at'=>$row['paid_at'],
				'currency'=>"INR",
				'invoice_number'=>$row['invoice_number'],
				'payment_type'=>$row['payment_type'],
				'fees'=>$row['fees'],
				'total_price'=>'INR '.$row['total_price'],
				'total_tax_price'=>'INR '.$row['total_tax_price'],
				'duration_rate'=>$row['duration_rate'],
				'starting_rate'=>"INR ".$row['starting_rate']." Per Km",
				'base_price'=>$row['base_price'],
				'tax_price'=>$row['tax_price'],
				'duration_rate'=>$row['duration_rate'],
				'duration_charge'=>$row['duration_charge'],
				'distance_charge'=>"INR ".$row['distance_charge'],
				'minimum_price'=>"INR ".$row['minimum_price'],
				'starting_charge'=>"INR ".$row['starting_rate'],
				'cancellation_price'=>$row['cancellation_price'],
				'waiting'=>$row['waitingCharge'],
				'totalbill'=>$row['totalBill'],
				'tripCharge'=>$row['tripCharge'],
				'pickuparea'=>$row['PickupArea'],
				'droparea'=>$row['DropArea'],
				'pickupaddress'=>$row['PickupAddress'],
				'dropaddress'=>$row['DropAddress'],
				'estimated_distance'=>$row['EstimatedDistance']."Km",
				'estimated_time'=>round(($row['EstimatedTime']))."Hrs",
				'booking_id'=>$row['ID'],
				'useragent'=>$row['useragent'],
				'bookingtype'=>$booking_type,
				'bookingdate'=>date("d-M-y H:i:s",strtotime($row['BookingDate'])),
				'pickupdate'=>date('d-M-y H:i:s',strtotime($row['PickupDate'].' '.$row['PickupTime'])),
				'driver_note'=>$row['driver_note'],
				'client_note'=>$row['client_note'],
				'features'=>$row['features'],
				'is_corporate_booking'=>$is_corporate_booking,
				'is_account_booking'=>$row['is_account_booking'],
				'voucher_id'=>$row['voucher_id'],
				'voucher_type'=>$row['voucher_type'],
				'arrival_time_pre'=>$row['arrival_time_pre'],
				'arrival_time_post'=>$row['arrival_time_post'],
				'arrival_time_actual'=>$row['arrival_time_actual'],
				'expiration_time'=>$row['expiration_time'],
				'actual_driven_distance'=>$row['actual_driven_distance'],
				'actual_waiting_distance'=>$row['actual_waiting_distance'],
				'actual_distance'=>$row['actual_distance'],
				'estimated_price'=>$row['estimated_price'],
				'driver_rating'=>$row['driver_rating'],
				'client_rating'=>$row['client_rating'],
				'actual_driven_duration'=>$row['actual_time'],
				'cab_name'=>$row['cab_name']);

		}
		return array("data"=>$booking);
	}

	public function packageCity(){




		$result =  mysqli_query($this->con,"select distinct(tc.id),tc.name from tbl_sightseeing ts,tblcity tc where ts.city=tc.id order by tc.id ASC ;") or die(mysqli_error($this->con));
		$result2 =  mysqli_query($this->con,"select distinct(tc.id),tc.name from tbl_sightseeing ts,tblcity tc where ts.city=tc.id order by tc.id ASC ;") or die(mysqli_error($this->con));
		$data=array();

		$row2=mysqli_fetch_assoc($result2);
		while($row=mysqli_fetch_assoc($result)){

			$data[]=$row;

		}

		$id=$row2['id'];

		$result1 =  mysqli_query($this->con,"select ts.id,ts.name,ts.individual_cost,ts.duration,ts.tax,ts.image,ts.package_default from tbl_sightseeing ts where ts.city='$id' and ts.package=0 order by ts.name ASC ;") or die(mysqli_error($this->con));
		$data1=array();
		while($row1=mysqli_fetch_assoc($result1)){

			$data1[]=$row1;

		}


		$packages =  mysqli_query($this->con,"select distinct(tp.id),tp.package_name,tp.is_active from tbl_sightseeing ts,tblpackages tp where ts.package=tp.id and ts.city='$id';") or die(mysqli_error($this->con));
		$data3=array();
		while($row3=mysqli_fetch_assoc($packages)){

			$data3[]=$row3;

		}




		return array("status"=>"true","data"=>$data,"data1"=>$data1,"data3"=>$data3);



	}



	public function sightSeeing(){


		$id=$_REQUEST['id'];

		$result1 =  mysqli_query($this->con,"select ts.id,ts.name,ts.individual_cost,ts.duration,ts.tax,ts.image,ts.package_default from tbl_sightseeing ts where ts.city='$id' and ts.package=0 order by ts.name ASC ;") or die(mysqli_error($this->con));
		$data1=array();
		while($row1=mysqli_fetch_assoc($result1)){

			$data1[]=$row1;

		}



		$packages =  mysqli_query($this->con,"select distinct(tp.id),tp.package_name,tp.is_active from tbl_sightseeing ts,tblpackages tp where ts.package=tp.id and ts.city='$id';") or die(mysqli_error($this->con));
		$data3=array();
		while($row3=mysqli_fetch_assoc($packages)){

			$data3[]=$row3;

		}


		return array("status"=>"true","data1"=>$data1,"data3"=>$data3);




	}



	public function cliclListDetails(){


		$id=$_REQUEST['id'];

		$result1 =  mysqli_query($this->con,"select ts.id,ts.name,ts.individual_cost,ts.duration,ts.tax,ts.image,ts.package_default from tbl_sightseeing ts where ts.package='$id' order by ts.name ASC ;") or die(mysqli_error($this->con));
		$data1=array();
		while($row1=mysqli_fetch_assoc($result1)){

			$data1[]=$row1;

		}

		return array("status"=>"true","data1"=>$data1);
	}

	public function addtocart(){


		$id=$_REQUEST['id'];

		$result1 =  mysqli_query($this->con,"select ts.id,ts.name,ts.individual_cost,ts.duration,ts.tax,ts.image,ts.package_default from tbl_sightseeing ts where ts.id='$id' order by ts.name ASC ;") or die(mysqli_error($this->con));
		$data1=mysqli_fetch_assoc($result1);
		return array("status"=>"true","data1"=>$data1);

	}





	public function log_count()
	{
		$token = $_REQUEST['token'];
		$result =  mysqli_query($this->con,"SELECT * FROM tbluser WHERE `token`='$token'") or die(mysqli_error($this->con));
		$row = mysqli_fetch_array($result);
		$id = $row['ID'];


		$total_count=mysqli_query($this->con,"SELECT count(*) FROM tblcabbooking WHERE  tblcabbooking.pickup = '$id'") or die(mysqli_error($this->con));
		$total_row = mysqli_fetch_array($total_count);

		$complete_count=mysqli_query($this->con,"SELECT count(*) FROM tblcabbooking WHERE tblcabbooking.`Status` = 8 AND tblcabbooking.pickup = '$id'") or die(mysqli_error($this->con));
		$complete_row = mysqli_fetch_array($complete_count);

		$pending_count=mysqli_query($this->con,"SELECT count(*) FROM tblcabbooking WHERE tblcabbooking.`Status` BETWEEN 3 AND 7 AND tblcabbooking.pickup = '$id'") or die(mysqli_error($this->con));
		$pending_row = mysqli_fetch_array($pending_count);

		$cancel_count=mysqli_query($this->con,"SELECT count(*) FROM tblcabbooking WHERE tblcabbooking.`Status` = 10 AND tblcabbooking.pickup = '$id'") or die(mysqli_error($this->con));
		$cancel_row = mysqli_fetch_array($cancel_count);


		$date= date('Y-m-d');
		$week_date= date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 week" ) );
		$month_date= date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) );

		$daily_log=mysqli_query($this->con,"CALL sp_w_login_logs_2('$id','$date')") or die(mysqli_error($this->con));
		$count=array();
		$day= "";
		while($dailylog_row = mysqli_fetch_array($daily_log))
		{
			$day = $day + $dailylog_row['diff'] ;
		}
		$day = round($day / 3600);
		$day_logout = 24 - $day;

		mysqli_free_result($daily_log);
		mysqli_next_result($this->con);

		$week_log=mysqli_query($this->con,"CALL `sp_w_login_logs_week`('$id','$week_date','$date')") or die(mysqli_error($this->con));
		$week= "";
		while($weeklog_row = mysqli_fetch_array($week_log))
		{
			$week = $week + $weeklog_row['diff'] ;
		}
		$week = round($week / 3600);
		$week_logout = (24*7) - $week;

		mysqli_free_result($week_log);
		mysqli_next_result($this->con);

		$month_log=mysqli_query($this->con,"CALL `sp_w_login_logs_week`('$id','$month_date','$date')") or die(mysqli_error($this->con));
		$month= "";
		while($monthlog_row = mysqli_fetch_array($month_log))
		{
			$month = $month + $monthlog_row['diff'] ;
		}
		$month = round($month / 3600);
		$month_logout = (24*31) - $month;

		$count=array();

		$count[]=array("total"=>$total_row['count(*)'],"complete"=>$complete_row['count(*)'],"pending"=>$pending_row['count(*)'],"cancel"=>$cancel_row['count(*)'],"day_log"=>$day,"week_log"=>$week,"month_log"=>$month,"day_logout"=>$day_logout,"week_logout"=>$week_logout,"month_logout"=>$month_logout);


		return array("data"=>$count);
	}
	function check_booking(){
		$city=$_REQUEST['city'];
		$type=$_REQUEST['type'];
		$result= mysql_query($this->con,"SELECT * FROM tblcity WHERE $type=1 and name='$city'");
		if(myqli_num_rows($result)==0)
		{
			return array("status"=>"false");

		}else{

			return array("status"=>"true");
		}

	}

	public function edit_user_profile(){
		$token = $_REQUEST["token"];
		$query="SELECT `ID` FROM tbluser WHERE `token`='$token'";
		$result =  mysqli_query($this->con,$query) or die(mysqli_error($this->con));
		$row = mysqli_fetch_assoc($result);		
		$id = $row['ID'];
		//file_put_contents('edit.txt', print_r($row,true));
		$name = explode(" ",$_REQUEST["name"]);
		$f_name = $name[0];
		@$l_name = $name[1];
		$mobile = $_REQUEST["mobile"];
		$landline = $_REQUEST["landline"];
		$email = $_REQUEST["email"];
		$altEmail = $_REQUEST["altEmail"];
		$altMobile = $_REQUEST["altMobile"];
		$company = $_REQUEST["company"];
		$addr = $_REQUEST["addr"];
		$resAddr = $_REQUEST["resAddr"];
		
		if(isset($_REQUEST['image']) and $_REQUEST['image'] != ''){
			$filename = $_REQUEST['name'].'_'.$id.'.jpg';
			$this->SaveUserImageAndroid($_REQUEST['image'], $filename);
		}
		
		$msg="";
		$sql = "SELECT tbluser.ID,tbluser.LoginName, tbluser.UserNo,tbluserinfo.Email,tbluserinfo.MobNo from tbluser INNER JOIN tbluserinfo ON tbluserinfo.UID = tbluser.ID where tbluser.LoginName = '$email' or tbluserinfo.Email = '$email' or tbluser.UserNo = '$mobile' or tbluserinfo.MobNo = '$mobile'";
		$quer = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
		$res = mysqli_num_rows($quer);
		if($res == 0){
			$data=mysqli_query($this->con,"CALL `sp_w_userEditProfile`('$f_name','$l_name','$mobile','$landline','$email','$altEmail','$company','$addr','$resAddr','$id','$altMobile')") or die(mysqli_error($this->con));
			$row=mysqli_fetch_array($data);
			$msg="Profile updated";
			return array("Message"=>$msg,"status"=>"true");
		}else{
			while($rowdata = mysqli_fetch_array($quer)){
				if($rowdata["ID"] == $id){
				}else{
					$msg="false";
					return array("Message"=>"Email or Mobile already exists.","status"=>"false");
				}
			}
		}
		if($msg!='false'){
			$sql = "CALL `sp_w_userEditProfile`('$f_name','$l_name','$mobile','$landline','$email','$altEmail','$company','$addr','$resAddr','$id','$altMobile')";
			$data=mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
			$row=mysqli_fetch_assoc($data);
			$com_id = $row["com_id"];
			mysqli_free_result($data);
			mysqli_next_result($this->con);
		    //echo $row["com_id"];
			if($row["com_id"]>0){
				file_put_contents('edit.txt', print_r($row["com_id"],true));
			     $query1="UPDATE tblcompany SET CompanyName = '$company' where tblcompany.ID = '".$com_id."'";
				$result1 =  mysqli_query($this->con,$query1) or die(mysqli_error($this->con));
				mysqli_free_result($result1);
				mysqli_next_result($this->con);
$msg="Profile updated";
				return array("Message"=>$msg,"status"=>"true");
			}else{				
					$msg="Profile updated3";
					return array("Message"=>$msg,"status"=>"true");				
			}
		}else{
			$msg="Email or Mobile already exists.";
			return array("Message"=>$msg,"status"=>"false");
		}
	}
	public function edit_user_profile_android(){
		$token = $_REQUEST["token"];
		$query="SELECT `ID` FROM tbluser WHERE `token`='$token'";
		$result =  mysqli_query($this->con,$query) or die(mysqli_error($this->con));
		$row = mysqli_fetch_assoc($result);		
		$id = $row['ID'];	
		$name = explode(" ",$_REQUEST["name"]);
		$f_name = $name[0];
		@$l_name = $name[1];
		$mobile = $_REQUEST["mobile"];
		$landline = $_REQUEST["landline"];
		$email = $_REQUEST["email"];
		$altEmail = $_REQUEST["altEmail"];
		$altMobile = $_REQUEST["altMobile"];
		$company = $_REQUEST["company"];
		$addr = $_REQUEST["addr"];
		$resAddr = $_REQUEST["resAddr"];
		if($id !=0 && $id!=''){
			if(isset($_FILES['image']) and $_FILES['image'] != ''){
			$ImgName=$id.'_'.basename($_FILES['image']["name"]);
			$TempName=$_FILES['image']['tmp_name'];			
			$response=$this->uploadUserIMGbyAndroid($ImgName,$TempName,$id,$token);
			}else{
				$response=array("status"=>false,"Message"=>"Image Not Received");
			}
			if($email != "" && $mobile != ""){
				$data=mysqli_query($this->con,"CALL `sp_w_userEditProfile`('$f_name','$l_name','$mobile','$landline','$email','$altEmail','$company',
				'$addr','$resAddr','$id','$altMobile')") or die(mysqli_error($this->con));
				$row=mysqli_fetch_array($data);
				$msg="Profile updated";
				return array("Message"=>$msg,"status"=>"true","ImageResponse"=>$response);
			}else{
				return array("status"=>false,"Message"=>"Email and Mobile No. is Required","ImageResponse"=>$response);
			}
		}else
		{
			return array("status"=>false,"Message"=>"Invalid Token");	
		}		
	}
	public function uploadUserIMGbyAndroid($ImgName,$TempName,$id,$token){
				$target_path = "public/userimage/$id/";
				$response = array();
		      // $server_ip = gethostbyname(gethostname());
			   $target = $target_path . $ImgName;				
				 // $file_upload_url = 'http://' . $server_ip . '/' . 'hello42' . '/' . $target;// localhost
					try {
						if (!move_uploaded_file($TempName, $target)) {
							$response['error'] = true;
							$response['message'] = 'Could not move the file!';
						}else{							
							$query="UPDATE tbluserinfo SET image='$target' WHERE UID ='$id'";
							$result =  mysqli_query($this->con,$query) or die(mysqli_error($this->con));
							$response['error'] = false;
							$response['message'] = 'File uploaded successfully!';							
							$response['file_path'] = $target;
						}
						
					} catch (Exception $e) {
						$response['error'] = true;
						$response['message'] = $e->getMessage();
					}				
				 return $response;	
	}
	public function updateUserProfileImage(){
				if(isset($_FILES['avatar_file']) and $_FILES['avatar_file'] != ''){
					 $id=$_COOKIE['login_id'];
					$ImgName=uniqid().'_'.basename($_FILES['avatar_file']["name"]);
					$TempName=$_FILES['avatar_file']['tmp_name'];
					$target_path = "public/userimage/$id/";					
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
	
	public function updateDriverProfileImage(){
				if(isset($_FILES['avatar_file']) and $_FILES['avatar_file'] != ''){
					 $id=$_COOKIE['login_id'];
					$ImgName=uniqid().'_'.basename($_FILES['avatar_file']["name"]);
					$TempName=$_FILES['avatar_file']['tmp_name'];
					$target_path = "public/userimage/$id/";					
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
	
	
	public function updateDriverVehicleImage(){
				if(isset($_FILES['avatar_file']) and $_FILES['avatar_file'] != ''){
					 $id=$_COOKIE['login_id'];
					$ImgName=uniqid().'_'.basename($_FILES['avatar_file']["name"]);
					$TempName=$_FILES['avatar_file']['tmp_name'];
					$target_path = "public/userimage/$id/";					
		            $server_ip = gethostbyname(gethostname());
			      $target = $target_path.$ImgName;				 
			     //$file_upload_url = 'http://' . $server_ip . '/' . 'hello42' . '/' . $target;// localhost 			
				 //echo $target_path;	 
					if ($mess=!move_uploaded_file($TempName, $target)) {
						return(array("status"=>false,"Message"=>"Error in Uploading Picture"));					
					}else{							
						$query="UPDATE tbluserinfo SET vehicleimg='$target' WHERE UID ='$id'";
						$result =  mysqli_query($this->con,$query) or die(mysqli_error($this->con));
						return(array("status"=>true,"Message"=>"Vehicle Picture Updated Successfully"));	
					}
				}
				return(array("status"=>false,"Message"=>"Un-Authorize Access"));          	
	}
	
	public function HelloMoneyService(){
		if(isset($_REQUEST["token"])){
			$token = $_REQUEST["token"];
			$query="SELECT * FROM tbluser WHERE `token`='$token'";
			$result =  mysqli_query($this->con,$query) or die(mysqli_error($this->con));
			if(mysqli_num_rows($result) > 0){
				$row = mysqli_fetch_object($result);				
				return array("status"=>true,"amount"=>$row->amount);
			  }else{
				return array("status"=>false,"amount"=>"0");
			  }
		}else{
			return array("status"=>false,"Message"=>"Invalid Token");
		}
	}        
	public function userAccountBalance(){
		$token = $_REQUEST['token'];
		$UserId = $_REQUEST['UserId'];
		$result = mysqli_query($this->con,"select * from `tbluser` where `token` = '$token' AND ID='$UserId'") or die(mysqli_error($this->con));
		$data1 = mysqli_fetch_array($result);
		$id = $data1['ID'];
		$result =  mysqli_query($this->con,"CALL `sp_user_acc_bal`('$id')") or die(mysqli_error($this->con));
		$data = array();
		$i=1;
		while($row= mysqli_fetch_assoc($result)){
			$data[] =array($i++,$row["reason"],$row["Master_Package"],$row["status"],$row["date"],$row["currentbalance"]); 
		}
		return array("status"=>"true","data"=>$data);
	}



	public function map_distance()
	{
		$datam1=28.5846875;
		$datam2=77.3159296;
		$datam3=28.597062;
		$datam4=77.3482352;
		$data= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$datam1.','.$datam2."&destination=".$datam3.','.$datam4."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584");

		//$data=file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=28.5846875,77.3159296&destination=28.597062,77.3482352&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584");


		// https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$origin[0].",".$origin[1]."&destinations=".$destiny[0].",".$destiny[1]."&mode=driving&language=en-US&sensor=false;


		// $data= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origins=".$datam1.','.$datam2."&destination=".$datam3.','.$datam4."&sensor=false&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584");

		$enc=json_decode($data);
		$enc2=$enc->routes[0];
		$distance=round((($enc2->legs[0]->distance->value)/1000),1);
		return array("status"=>"true","data"=>$distance);
	}

	public function aa()
	{

		$date = "04-15-2013";
		$date1 = str_replace('-', '/', $date);
		$tomorrow = date('m-d-Y',strtotime($date1 . "+1 days"));

		echo $tomorrow;


	}



	public function addiontionOfTime($time1, $time2)
	{
		$times = array($time1, $time2);
		$seconds = 0;
		foreach ($times as $time)
		{
			list($hour,$minute,$second) = explode(':', $time);
			$seconds += $hour*3600;
			$seconds += $minute*60;
			$seconds += $second;
		  }
		  $hours = floor($seconds/3600);
		  $seconds -= $hours*3600;
		  $minutes  = floor($seconds/60);
		  $seconds -= $minutes*60;
		  if($seconds < 9)
		  {
		  $seconds = "0".$seconds;
		  }
		  if($minutes < 9)
		  {
		  $minutes = "0".$minutes;
		  }
			if($hours < 9)
		  {
		  $hours = "0".$hours;
		  }
		  //echo $hours."::".$minutes."::".$seconds;
		  $sum_array=array($hours,$minutes,$seconds);  
		  $sum_time=implode(':',$sum_array);   
		  // echo $sum_time;  
		  return array("status"=>'true',"time_sum"=>$sum_time);
  } 
  
	public function diif_time($time1,$time2){
		$date1 = strtotime($time1);
		$date2 = strtotime($time2);
		$interval = $date2 - $date1;
		$seconds = $interval % 60;
		$minutes = floor(($interval % 3600) / 60);
		$hours = floor($interval / 3600);
		$diff_time_array=array($hours,$minutes,$seconds);
		$diff_time=implode(':',$diff_time_array);
		return array("status"=>'true',"time_diff"=>$diff_time);
	}
	public function days($logindate,$logoutdate){
		$date1=date_create($logindate);
		$date2=date_create($logoutdate);
		$diff=date_diff($date1,$date2);

		$days=$diff->format("%a");
		return array("status"=>'true',"days"=>$days);
	}
	public function textDays(){
		$date1=date_create("2015-03-30 15:00:00");
		$date2=date_create("2015-04-03 09:00:00");
		$diff=date_diff($date1,$date2);
		$days=$diff->format("%a");
		return array("status"=>'true',"days"=>$days);
	}

	public function incrementOneDay($date1){
		$date = date_create($date1);
		date_add($date, date_interval_create_from_date_string('1 days'));
		$inc_date=date_format($date, 'Y-m-d');
		return array("status"=>'true',"inc_day"=>$inc_date);
	}

	public function Temp_time($id){
		$log_data=array();
		$result =  mysqli_query($this->con,"select login_time,logout_time,DATE(tblloginlog.login_time) as `login_date`,DATE(tblloginlog.logout_time) as `logout_date` from tblloginlog where userID='".$id."' and login_time > (NOW() - INTERVAL 1 MONTH);") or die(mysqli_error($this->con));
		$data = array();
		$num_rows = mysql_num_rows($result);
		$j=0;
		while ($arrayData = mysqli_fetch_assoc($result)) {
			$temp[$j]= $arrayData;
			$j++;
		}
		$sizeArr = sizeof($temp);
		for ($a = 0; $a < $sizeArr; $a++){ //2nd for loop
			$singleArray= array();
			$multiDimArray= array();
			$row = $temp[$a];
			$login_time=$row['login_time'];
			$logout_time=$row['logout_time'];
			$login_date=$row['login_date'];
			$logout_date=$row['logout_date'];
			if($login_date==$logout_date){
				$time=$this->diif_time($login_time,$logout_time);
				$singleArray = $time['time_diff'];
				$multiDimArray = $singleArray;
				$login_date1=date("y-m-d", strtotime($login_time)); 
				$query="INSERT INTO driver_login_logs SET uid='$id',login_time='$login_time',logout_time='$logout_time',login_date='$login_date1',logout_date='$logout_date',login_diff='$singleArray',logout_diff=''"; 
				mysqli_query($this->con, $query);
			}elseif($login_date!=$logout_date && $logout_time!="0000-00-00 00:00:00"){
				$days=$this->days($login_time,$logout_time); 
				$isExecute = true;
				if($days['days']>0){
					$tempNewStrDate = $login_date;
					for($i=0; $i<$days['days']; $i++){
						if($i==0){
							$tempStrDate = $tempNewStrDate." 23:59:59";
							$time1=$this->diif_time($login_time,$tempStrDate);
							$singleArray = $time1['time_diff'];
							$login_date1=date("y-m-d", strtotime($login_time)); 
							$query="INSERT INTO driver_login_logs SET uid='$id',login_time='$login_time',logout_time='',login_date='$login_date1',logout_date='$logout_date',login_diff='$singleArray',logout_diff=''";        
							mysqli_query($this->con, $query);
						}else{
							if($tempNewStrDate == $logout_date){
								$isExecute = false;
								$tempStrDate = $tempNewStrDate." 00:00:02";
								$time1=$this->diif_time($tempStrDate,$logout_time);
								$singleArray = $time1['time_diff'];
								$login_date1=date("y-m-d", strtotime($tempStrDate)); 
								$query="INSERT INTO driver_login_logs SET uid='$id',login_time='$tempStrDate',logout_time='$logout_time',login_date='$login_date1',logout_date='$logout_date',login_diff='$singleArray',logout_diff=''";       
								mysqli_query($this->con, $query);
							}else{
								$time1 = "23:59:59";
								$tempStrDate = $tempNewStrDate." 00:00:02";
								$logout_time1 = $tempNewStrDate." 23:59:59";
								$login_date1=date("y-m-d", strtotime($tempStrDate)); 
								$query="INSERT INTO driver_login_logs SET uid='$id',login_time='',logout_time='',login_date='$login_date1',logout_date='$logout_date',login_diff='$time1',logout_diff=''";       
								mysqli_query($this->con, $query);           
							}
						}
						$temp_inc_day = $this->incrementOneDay($tempNewStrDate);
						$tempNewStrDate=$temp_inc_day['inc_day'];
					}
					if($isExecute){
						$tempStrDate = $tempNewStrDate." 00:00:00";
						$tempStrDate;
						$logout_time;
						$login_time;
						$time1=$this->diif_time($tempStrDate,$logout_time);
						$singleArray = $time1['time_diff'];
						$login_date1=date("y-m-d", strtotime($tempStrDate)); 
						$query="INSERT INTO driver_login_logs SET uid='$id',login_time='',logout_time='$logout_time',login_date='$login_date1',logout_date='$logout_date',login_diff='$singleArray',logout_diff=''"; 
						mysqli_query($this->con, $query);       
					}
					$multiDimArray = $singleArray;                          
				}else{
					$logout_time1=$login_date." 23:59:59"; 
					$time1=$this->diif_time($login_time,$logout_time1);
					$singleArray = $time1['time_diff'];
					$multiDimArray = $singleArray;
					$login_date1=date("y-m-d", strtotime($login_time)); 
					$query="INSERT INTO driver_login_logs SET uid='$id',login_time='$login_time',logout_time='',login_date='$login_date1',logout_date='$logout_date',login_diff='$singleArray',logout_diff=''";     
					mysqli_query($this->con, $query);
					$logout_time2=$logout_date." 00:00:00"; 
					$time1=$this->diif_time($logout_time2,$logout_time);
					$single2Array = Array();
					$single2Array = $time1['time_diff'];
					$multiDimArray = $single2Array;
					$login_date2=date("y-m-d", strtotime($logout_time)); 
					$query="INSERT INTO driver_login_logs SET uid='$id',login_time='',logout_time='$logout_time',login_date='$login_date2',logout_date='$logout_date',login_diff='$single2Array',logout_diff=''";       
					mysqli_query($this->con, $query);
				}
			}else{
				$c=$sizeArr-2;
				if($a <= $c){
					$b = $a+1;
					$row2 = $temp[$b];
					$nextDateTime =$row2['login_time'];
					$time1=$this->diif_time($login_time,$nextDateTime);
					$singleArray = $time1['time_diff'];
					$login_date1=date("y-m-d", strtotime($login_time)); 
					$query="INSERT INTO driver_login_logs SET uid='$id',login_time='$login_time',logout_time='$logout_time',login_date='$login_date1',logout_date='$logout_date',login_diff='$singleArray',logout_diff=''"; 
					mysqli_query($this->con, $query);
				}else{
					$logout_time1=date("H:i:s");
					$time1=$this->diif_time($login_time,$logout_time1);
					$singleArray = $time1['time_diff'];
					$login_date1=date("y-m-d", strtotime($login_time)); 
					$query="INSERT INTO driver_login_logs SET uid='$id',login_time='$login_time',logout_time='$logout_time',login_date='$login_date1',logout_date='$logout_date',login_diff='$singleArray',logout_diff=''"; 
					mysqli_query($this->con, $query);
				}
				$multiDimArray = $singleArray;
			}
			$log_data[$a]['loginTime']=$login_time;
			$log_data[$a]['logoutTime']=$logout_time;
			$log_data[$a]['loginDate']=$login_date;
			$log_data[$a]['logoutDate']=$logout_date;
			$log_data[$a]['login_hour']=$singleArray;
			$log_data[$a]['logout_hour']=$single2Array;
		}
		return array("status"=>'true',"driver_log_data"=>$log_data);
	} 
	public function PriceList(){
		$PackageName = $_REQUEST['PackageName'];
		$data = array(
			'80hr'=>array('Km'=>80, 'Hr'=>8, 'Rate'=>800, 'PerKm'=>20, 'PerHr'=>100),
			'16hr'=>array('Km'=>160, 'Hr'=>16, 'Rate'=>1600, 'PerKm'=>20, 'PerHr'=>100),			
		);
		if($PackageName == '80hr'){
			return $data['80hr'];
		}elseif($PackageName == '16hr'){
			return $data['16hr'];
		}else{
			return false;
		}
	}
	
	public function FarePackage_old(){
		$data = array('Status'=>'false', 'data'=>'');
		if(isset($_REQUEST)){
			$FormType = $_REQUEST['FormType'];
			$CabType = $_REQUEST['CabType'];
			$Package = $_REQUEST['Package'];
			$Vehicles = array();
			$sql = "select * from tblcablistmgmt INNER JOIN tblcabtype ON tblcabtype.Id = tblcablistmgmt.CabType WHERE tblcabtype.Id = $CabType";
			$qry = mysqli_query($this->con, $sql);
			while($row = mysqli_fetch_object($qry)){
				array_push($Vehicles, $row->name); 
			}
			if($FormType == 101){
				if($CabType == 1){
					if($Package == 1){
						$data = array('Status'=>'true', 'Fare'=>100, 'PerKm'=>10, 'PerHr'=>50);
					}elseif($Package == 2){
						$data = array('Status'=>'true', 'Fare'=>200, 'PerKm'=>10, 'PerHr'=>50);
					}elseif($Package == 3){
						$data = array('Status'=>'true', 'Fare'=>300, 'PerKm'=>10, 'PerHr'=>50);
					}elseif($Package == 4){
						$data = array('Status'=>'true', 'Fare'=>500, 'PerKm'=>10, 'PerHr'=>50);
					}
				}elseif($CabType == 2){
					if($Package == 1){
						$data = array('Status'=>'true', 'Fare'=>200, 'PerKm'=>20, 'PerHr'=>100);
					}elseif($Package == 2){
						$data = array('Status'=>'true', 'Fare'=>400, 'PerKm'=>20, 'PerHr'=>100);
					}elseif($Package == 3){
						$data = array('Status'=>'true', 'Fare'=>600, 'PerKm'=>20, 'PerHr'=>100);
					}elseif($Package == 4){
						$data = array('Status'=>'true', 'Fare'=>1000, 'PerKm'=>20, 'PerHr'=>100);
					}
	
				}elseif($CabType == 3){
					if($Package == 1){
						$data = array('Status'=>'true', 'Fare'=>400, 'PerKm'=>50, 'PerHr'=>150);
					}elseif($Package == 2){
						$data = array('Status'=>'true', 'Fare'=>800, 'PerKm'=>50, 'PerHr'=>150);
					}elseif($Package == 3){
						$data = array('Status'=>'true', 'Fare'=>1200, 'PerKm'=>50, 'PerHr'=>150);
					}elseif($Package == 4){
						$data = array('Status'=>'true', 'Fare'=>2000, 'PerKm'=>50, 'PerHr'=>150);
					}
				}
			}elseif($FormType == 102){
				if($CabType == 1){
					$data = array('Status'=>'true', 'Fare'=>500, 'PerKm'=>50, 'PerHr'=>150);
				}elseif($CabType == 2){
					$data = array('Status'=>'true', 'Fare'=>1000, 'PerKm'=>100, 'PerHr'=>200);
				}elseif($CabType == 3){
					$data = array('Status'=>'true', 'Fare'=>2000, 'PerKm'=>150, 'PerHr'=>300);
				}
			}elseif($FormType == 103){
			}elseif($FormType == 104){
			}
			$data['Vehicle'] = $Vehicles;
		}
		return $data;
	}
	
	public function packageList(){
		$row = array();
		$data = array();
		$SQL = "SELECT id, Sub_package, Address, Area, Zone, State, FareEconomy,FareSedan,FarePrime,Distance FROM tblbillconfigpackage WHERE Status = 1";
		$QRY = mysqli_query($this->con, $SQL) or die(mysqli_error());
		while($row = mysqli_fetch_object($QRY)){
			array_push($data, $row);
		}
		print json_encode($data);
		//return $data;
	}
	
	
	
	public function FarePackage(){
		$data = array('Status'=>'false', 'data'=>array(), 'Vehicle'=>array());
		if(isset($_REQUEST)){
			$FormType = $_REQUEST['FormType'];
			$CabType = $_REQUEST['CabType'];
			$Vehicles = array();
			$SQL = "SELECT tblbookingbill.Id, 
						   tblmasterpackage.Master_Package AS FormName, 
						   tblmasterpackage.Sub_Package_Id AS RateCatId, 
						   tblsubpackage.Sub_Package_Name AS CalculateBy, 
						   tblcabtype.CabType AS CabType,
						   tblcountry.CountryName,
						   tblstates.state AS StateName,
						   tblcity.name AS CityName,
							
						   tblbookingbill.Per_Km_Charge,
						   tblbookingbill.rate_per_km_dh,
						   tblbookingbill.rate_per_km_dw,
							
						   tblbookingbill.tripCharge_per_minute,
						   tblbookingbill.rate_per_hour_dh, 
							
						   tblbookingbill.MinimumCharge,
						   tblbookingbill.minimum_fare_dh,
						   tblbookingbill.minimum_fare_dw
					
					FROM tblbookingbill 
					INNER JOIN tblmasterpackage ON tblbookingbill.BookingTypeId = tblmasterpackage.Package_Id
					INNER JOIN tblsubpackage ON tblmasterpackage.Sub_Package_Id = tblsubpackage.id 
					INNER JOIN tblcity ON tblbookingbill.CityId = tblcity.id 
					INNER JOIN tblstates ON tblcity.state = tblstates.id 
					INNER JOIN tblcountry ON tblcountry.id = tblstates.country_code 
					INNER JOIN tblcabtype ON tblbookingbill.CabType = tblcabtype.Id 
					WHERE tblbookingbill.BookingTypeId = $FormType AND tblbookingbill.CabType = $CabType";
			$qry = mysqli_query($this->con, $SQL);
			$FareData = mysqli_fetch_assoc($qry);
			if(!empty($FareData)){
				$nan = 'NA';
				if($FareData['RateCatId'] == 1){
					$CalculatedBy = 'Distance';
					$RatePerKm = $FareData['Per_Km_Charge'];
					$RatePerHr = $nan;
					$MinimumFare = $FareData['MinimumCharge'];
				}elseif($FareData['RateCatId'] == 2){
					$CalculatedBy = 'Hour';
					$RatePerKm = $nan;
					$RatePerHr = $FareData['tripCharge_per_minute'];
					$MinimumFare = $nan;
				}elseif($FareData['RateCatId'] == 3){
					$CalculatedBy = 'Distance + Hour';
					$RatePerKm = $FareData['rate_per_km_dh'];
					$RatePerHr = $FareData['rate_per_hour_dh'];
					$MinimumFare = $FareData['minimum_fare_dh'];
				}elseif($FareData['RateCatId'] == 4){
					$CalculatedBy = 'Distance + Waiting';
					$RatePerKm = $FareData['rate_per_km_dw'];
					$RatePerHr = $nan;
					$MinimumFare = $FareData['minimum_fare_dw'];
				}else{
					$CalculatedBy = 'Not Defined';
					$RatePerKm = $nan;
					$RatePerHr = $nan;
					$MinimumFare = $nan;
				}

				$FareData = array(
					'CountryName'=>$FareData['CountryName'],
					'StateName'=>$FareData['StateName'],
					'CityName'=>$FareData['CityName'],
					'FormName'=>$FareData['FormName'],
					'CabType'=>$FareData['CabType'],
					'CalculatedBy'=>$CalculatedBy,
					'MinimumFare'=>$MinimumFare,
					'PerKm'=>$RatePerKm,
					'PerHr'=>$RatePerHr
				);
				$sql = "select * from tblcablistmgmt INNER JOIN tblcabtype ON tblcabtype.Id = tblcablistmgmt.CabType WHERE tblcabtype.Id = $CabType";
				$qry = mysqli_query($this->con, $sql);
				while($row = mysqli_fetch_object($qry)){
					array_push($Vehicles, $row->name); 
				}
				$data = array('Status'=>'true', $FareData, 'Vehicle'=>$Vehicles);
			}
		}
		return $data;
	}

/*	public function FarePackage_dum(){
		$data = array('Status'=>'false', 'data'=>array(), 'Vehicle'=>array());
		if(isset($_REQUEST)){
			$FormType = $_REQUEST['FormType'];
			$CabType = $_REQUEST['CabType'];
			$Vehicles = array();
			$SQL = "SELECT tblbookingbill.Id,
						   tblcountry.CountryName,
						   tblstates.state AS StateName, 
						   tblcity.name AS CityName,
						   tblmasterpackage.Master_Package AS FormName, 
						   tblcabtype.CabType AS CabName,
						   tblbookingbill.MinimumCharge AS Fare,
						   tblbookingbill.Per_Km_Charge AS PerKm,
						   tblbookingbill.tripCharge_per_minute AS PerHr 
					FROM tblbookingbill 
					INNER JOIN tblmasterpackage ON tblbookingbill.FormTypeId = tblmasterpackage.Package_Id 
					INNER JOIN tblcity ON tblbookingbill.CityId = tblcity.id 
					INNER JOIN tblstates ON tblcity.state = tblstates.id 
					INNER JOIN tblcountry ON tblcountry.id = tblstates.country_code 
					INNER JOIN tblcabtype ON tblbookingbill.CabType = tblcabtype.Id 
					WHERE tblbookingbill.FormTypeId = $FormType AND tblbookingbill.CabType = $CabType";
			$qry = mysqli_query($this->con, $SQL);
			$FareList = mysqli_fetch_assoc($qry);
			if(!empty($FareList)){
				$sql = "select * from tblcablistmgmt INNER JOIN tblcabtype ON tblcabtype.Id = tblcablistmgmt.CabType WHERE tblcabtype.Id = $CabType";
				$qry = mysqli_query($this->con, $sql);
				while($row = mysqli_fetch_object($qry)){
					array_push($Vehicles, $row->name); 
				}
				$data = array('Status'=>'true', $FareList, 'Vehicle'=>$Vehicles);
			}
		}
		return $data;
	}

	public function Fare_dum(){
		$data = array('Status'=>'false', 'data'=>'');
		$Vehicles = array();
		if(isset($_REQUEST)){
			
			$FormType = $_REQUEST['FormType'];
			$CabType = $_REQUEST['CabType'];
			
			// Get Fare Charges
			$sql = "SELECT * FROM tblbookingbill WHERE CabType = $CabType";
			$qry = mysqli_query($this->con,$sql);
			$data = array('Status'=>'true', mysqli_fetch_assoc($qry));
			
			// Get Vehicles
			$sql = "select * from tblcablistmgmt INNER JOIN tblcabtype ON tblcabtype.Id = tblcablistmgmt.CabType WHERE tblcabtype.Id = $CabType";
			$qry = mysqli_query($this->con, $sql);
			while($row = mysqli_fetch_object($qry)){
				array_push($Vehicles, $row->name); 
			}
			$data['Vehicle'] = $Vehicles;
		
		}	
		return $data;	
	}
*/	
	
	public function SaveUserImageAndroid($ImageEncode='', $filename=''){
		if($ImageEncode){
			$binary=base64_decode($ImageEncode);
			header('Content-Type: bitmap; charset=utf-8');
			$ImagePath = 'public/userimage/1426/'.$filename;
			$file = fopen($ImagePath, 'wb');
			fwrite($file, $binary);
			fclose($file);
			$SQL = "UPDATE tbluserinfo SET image = '$ImagePath' where";
			mysqli_query($this->con, $SQL);
		}
	}
	
	public function info(){
		phpinfo();
	}
	public function getCabTypes(){
		$tblcabtype = mysqli_query($this->con,"SELECT * FROM tblcabtype");
	     $count = mysqli_num_rows($tblcabtype);
		 if($count < 1 ){
			return array("Status" => "Failed");
		}else{
			 $j=0;
			$cabArray=array();
			while($Cabdata=mysqli_fetch_array($tblcabtype)){
				$cabArray[$j]["id"]=$Cabdata["Id"];
				$cabArray[$j]["CabType"]=$Cabdata["CabType"];
			$j++;	
			}
		}	
	    return array("Status" => "Success","CabData"=>$cabArray);
	 }
	 public function GetCabByCategory(){
		$CabType=$_REQUEST["CabId"] ;
		$tblcabtype = mysqli_query($this->con,"SELECT * FROM `tblcabmodelmaster` WHERE `CabTypeId`='$CabType'");
	     $count = mysqli_num_rows($tblcabtype);
		 if($count < 1 ){
			return array("Status" => "Failed");
		}else{
			 $j=0;
			$cabArray=array();
			while($Cabdata=mysqli_fetch_array($tblcabtype)){
				$cabArray[$j]["id"]=$Cabdata["id"];
				$cabArray[$j]["CabName"]=$Cabdata["ModelName"];
			$j++;	
			}
		}	
	    return array("Status" => "Success","CabData"=>$cabArray);
	 }
	public function getCountry(){
	$result = mysqli_query($this->con,"SELECT * FROM tblcountry");	 
	 if(mysqli_num_rows($result) < 1 ){
		return array("Status" => "Failed");
	}else{		
		$i=0;
		$record=array();
		while($res=mysqli_fetch_array($result)){
			$record[$i]["id"]=$res["ID"];
			$record[$i]["country_name"]=$res["CountryName"];
		$i++;	
		}		
	}	
	return array("Status" => "Success","record"=>$record);
	}
	public function getState(){
		$Country_id=$_REQUEST["Country_id"];
	   $result = mysqli_query($this->con,"SELECT * FROM tblstates WHERE country_code='$Country_id'");	 
	 if(mysqli_num_rows($result) < 1 ){
		return array("Status" => "Failed");
	}else{		
		$i=0;
		$record=array();
		while($res=mysqli_fetch_array($result)){
			$record[$i]["id"]=$res["id"];
			$record[$i]["state_name"]=$res["state"];
		$i++;	
		}		
	}
    $sql="SELECT * FROM tblcountry WHERE ID='$Country_id'";		
		$resu = mysqli_query($this->con,$sql);
			$res111=mysqli_fetch_object($resu);			
				 $isdcode=$res111->DialCode;
	return array("Status" => "Success","record"=>$record,"isdcode"=>$isdcode);
	}
	
	public function getStateLic(){
	   $Country_id='1';
	   $result = mysqli_query($this->con,"SELECT * FROM tblstates WHERE country_code='$Country_id'");	 
	 if(mysqli_num_rows($result) < 1 ){
		return array("Status" => "Failed");
	}else{		
		$i=0;
		$record=array();
		while($res=mysqli_fetch_array($result)){
			$record[$i]["id"]=$res["id"];
			$record[$i]["state_name"]=$res["state"];
		$i++;	
		}		
	}
    $sql="SELECT * FROM tblcountry WHERE ID='$Country_id'";		
		$resu = mysqli_query($this->con,$sql);
			$res111=mysqli_fetch_object($resu);			
				 $isdcode=$res111->DialCode;
	return array("Status" => "Success","record"=>$record,"isdcode"=>$isdcode);
	}
	
	public function getCity(){
		$State_id=$_REQUEST["State_id"];
	   $result = mysqli_query($this->con,"SELECT * FROM tblcity WHERE state='$State_id'");	 
	 if(mysqli_num_rows($result) < 1 ){
		return array("Status" => "Failed");
	}else{		
		$i=0;
		$record=array();
		while($res=mysqli_fetch_array($result)){
			$record[$i]["id"]=$res["id"];
			$record[$i]["city_name"]=$res["name"];
		$i++;	
		}		
	}    
	return array("Status" => "Success","record"=>$record);
	}
	public function getCountryIsd(){
		 $country=$_POST["count_id"];
	    $sql="SELECT * FROM tblcountry  WHERE ID='$country'";		
		$result = mysqli_query($this->con,$sql);
	 $count = mysqli_num_rows($result);	 
	 if($count < 1 ){
		return array("Status" => "Failed");
	}else{		
			$res=mysqli_fetch_object($result);			
				 $record=$res->DialCode;				
	     }	
	return array("Status" => "Success","isdcode"=>$record);
	}
	
	//for live
	// public function send_mail($to, $from, $subject, $body){
	
	//for testing
	public function send_mail(){

		$to = "funstartswithyou15@gmail.com";
		$from = "php1@hello42cab.com";
		$subject = "funstartswithyou15";
		$body = "test mail";

		if($to != '' and $from != ''){			
			$bodyPart = new \Zend\Mime\Message(); 
			$bodyMessage = new \Zend\Mime\Part($body);
			$bodyMessage->type = 'text/html';
			$bodyPart->setParts(array($bodyMessage));
			$objmail= new Mail\Message();
			$objmail->setBody($bodyPart);
			$objmail->setFrom($from);
			$objmail->addTo($to);
			$objmail->setSubject($subject);
			$objmail->setEncoding('UTF-8');
			$transportObj = new Mail\Transport\Sendmail();
			if($transportObj->send($objmail)){
				return true;
			}else{
				return false;
			}			
		}
		return false;
	}

	public function sendinvoicemailworking()
	{
		 $booking = substr($_POST['bokid'],4);
		//$booking = 8646;
		//$booking = 8666; 
		
		$data = $this->fetchinvoicedetails($booking);
		
		// print_r($data); exit;
		 extract($data);
		 // echo $UserName;
		 ob_clean();
		//include("pdfgenerator/invoice.phtml");
		$template = ob_get_clean();
		 
	

		include("pdfgenerator/genpdf.phtml");
		// $from = "funstartswithyou15@gmail.com"; 
		// $fromname= "funnyguy";
  //   // $to= "neeraj@regencytours.in";
		// $to= "php1@hello42cab.com";

		// $name= "phpguy";
		// $subject = "invoice mail";
		// $message= "this is an invoice mail from hello42cab.com";
		// $file = root_folder.$filepath;
		// // echo $file;die;
		// $altbody = "alternate";
		// include("testmail/invoiceattach.php");
		//die;
		
	}
	
	
	public function sendinvoicemail()
	{
		$bookingId=$_POST['bokid'];
		$booking = substr($_POST['bokid'],4);
		//$booking = 619; 

		$data = $this->fetchinvoicedetails($booking);
		
		$_SESSION['abc']=serialize($data);
		$_SESSION['bookingId']=serialize($bookingId);
		$res=include("html2pdf/index.php");

		if($res['status']=='true'){
		$file=$res['filename'];
		}
		
	$from = "funstartswithyou15@gmail.com"; 
	$fromname= "funnyguy";
	//$to	=	$data['uemail']; 
	$to= "php@hello42cab.com";
	$name= "phpguy";
	$subject = "invoice mail";
	$message= "this is an invoice mail from hello42cab.com";
	$file = $filepath;
	$altbody = "alternate";
	$response= include("testmail/invoiceattach.php");
	echo json_encode($response); die;
	}
	
	public function sendinvoicemail_old()
	{
		 $booking = substr($_POST['bokid'],4);
		//$booking = 8646;
		//$booking = 8666; 
		
		$data = $this->fetchinvoicedetails($booking);
		
		// print_r($data); exit;
		 
		ob_start();
    			require("pdfgenerator/invoice.phtml");
    			$abc = ob_get_clean();

    			// echo $abc;die;
				// include('faretemp.phtml');
				// echo $TotalBill;die;

			// 	// $ret = array($Min_Distance,$TotalBill,
			// 	// $extraPrice,$waiting_charges,$basic_tax_price,$totalPrewaitingValue);
				$response = array("status"=>'true');

				header("contentType:application/json");
				echo json_encode($response); die;

		
	}

	public function fetchinvoicedetails($booking='')
	{
		 
			$sql = "SELECT tcb.EstimatedDistance,tcb.actual_distance,tcb.EstimatedTime,tcb.UserName,tcb.BookingDate,tcb.ReturnDate,tcb.PickupAddress,tcb.PickupDate,
			tcb.PickupTime,tcb.PickupCity,tcb.DropAddress,tcb.DestinationCity,tcb.BookingType,tcb.CabIn,td.FirstName,td.LastName,
			td.ContactNo,td.Email,td.VehicleRegistrationNo,td.TypeOfvehicle,tct.CabType,tui.Address1,
			tui.Address2,tui.Country as ucontry,tui.State as ustate,tui.City as ucity,tui.Email as uemail,tui.Firstname as uFirstname,tui.LastName as uLastname,
			tui.MobNo as umob,tcm.CabName as CabName,tmp.Master_Package as masterpackageName
			FROM tblcabbooking as tcb left join tbldriver as td on tcb.pickup = td.UID  
			inner join tbluserinfo as tui on tcb.ClientID = tui.UID  
			inner join tblcabtype as tct on td.TypeOfvehicle = tct.id
			inner join tblcabmaster as tcm on td.vehicleId = tcm.CabId
			inner join tblmasterpackage as tmp on tcb.BookingType = tmp.Package_Id
			 WHERE tcb.ID ='$booking'";
			$row = $this->con->query($sql)->fetch_assoc();
			//print_r($row);
		 	
			$sql1="select * from tblbookingcharges where BookingID='$booking' order by id desc limit 1";
		 	$row1 = $this->con->query($sql1)->fetch_assoc();
		 	//print_r($row1);
		 	// $result = array_unique(array_merge($row,$row1), SORT_REGULAR); 
		 	$result = array_merge($row, $row1); 
		 	// array($row,$row1); 
		 	//echo $result;
			// print_r($result);

		// die;

		// $user_ID=$row->ClientID;          
		// $Drvr_ID=$row->pickup;                    
		// $EstimatedDistance=$row->EstimatedDistance;
		// $EmailId=$row->EmailId;           
		// $UserName=$row->UserName;                 
		// $EstimatedTime=$row->EstimatedTime;
		// $MobileNo=$row->MobileNo;         
		// $PickupAddress=$row->PickupAddress;       
		// $ReturnDate=$row->ReturnDate; 
		// $DropAddress=$row->DropAddress;   
		// $BookingDate=$row->BookingDate;           
		// $PickupCity=$row->PickupCity;
		// $PickupDate=$row->PickupDate;     
		// $PickupTime=$row->PickupTime;             
		// $DestinationCity=$row->DestinationCity;
		// $waiting_charge=$row->approx_waiting_charge; 				
		// $paid_amount=$amount;
		// $due_balance=$amount-$paid_amount;
		// //*********Fetch Driver Info***********
		// $query = "SELECT * FROM tbluserinfo WHERE UID ='$Drvr_ID'";
		// $qry11 = mysqli_query($this->con,$query);
		// $row111 = mysqli_fetch_object($qry11);
		// $Driveremail=$row111->Email;
		// $DriverFirstName=$row111->FirstName;
		// $DriverLastName=$row111->LastName;
		// $DriverMobNo=$row111->MobNo;
		// //*********Fetch Vehicle Details*****************
		// $fetch = "select tblcabmaster.* from tblcabbooking inner join tbldriver on tblcabbooking.pickup = tbldriver.UID
		// inner join tblcabmaster on tbldriver.vehicleId = tblcabmaster.CabId	where tblcabbooking.ID = '$booking'";
		// $fetched = mysqli_query($this->con,$fetch);
		// $result123 = mysqli_fetch_object($fetched);
		// $vehicle_number=$result123->CabRegistrationNumber;
		// $vehicle_name=$result123->CabName;
		// $CabType=$result123->CabType;
		// $quer = "SELECT * FROM tblcabtype WHERE Id = '$CabType'";
		// $qry6 = mysqli_query($this->con,$quer);
		// $cabyu = mysqli_fetch_object($qry6);
		// $vechile_type=$cabyu->CabType;
		// //*********Fetch User Info***********
		// $query1 = "SELECT * FROM tbluserinfo WHERE UID = '$user_ID'";
		// $qry96 = mysqli_query($this->con,$query1);
		// $rowpp = mysqli_fetch_object($qry96);
		// $userEml=$rowpp->Email;
		// $usrFirstN=$rowpp->FirstName;
		// $urLastName=$rowpp->LastName;
		// $usrMobNo=$rowpp->MobNo;
		// $usrCoty=$rowpp->Country;
		// $userState=$rowpp->State;
		// $userCity=$rowpp->City;
		// $usAddrs1=$rowpp->Address1;
		// $userAdd2=$rowpp->Address2;
		// $usrPine=$rowpp->PinCode;
		// mysqli_free_result($qry96);
		// mysqli_next_result($this->con);
		// //$this->send_sms_new1($booking,$flag="F");		
		// //***********Mail****************************	
		// $night_charges='';
		// //  $search=array('{user_name}','{user_address}','{user_city}','{user_state}','{country}','{user_email}','{user_mobile}','{driver_name}',
		// // '{vehicle_number}','{driver_mobile}','{vehicle_name}','{driver_email}','{vehicle_type}','{booking_date}','{return_date}',
		// // '{pickup_address}','{pickup_date}','{pickup_time}','{pickup_city}','{drop_address}','{drop_city}','{estimated_distance}',
		// // '{estimated_time}','{waiting_charges}','{night_charges}','{road_tax}','{tool_tax}','{other_charges}','{total_fare}','{amount_paid}','{due_amount}');
		// $replace=array($UserName,$usAddrs1.$userAdd2,$userCity,$userState,$usrCoty,$userEml,$usrMobNo,$DriverFirstName.$DriverLastName,
		// $vehicle_number,$DriverMobNo,$vehicle_name,$Driveremail,$vechile_type,$BookingDate,$ReturnDate,
		// $PickupAddress,$PickupDate,$PickupTime,$PickupCity,$DropAddress,$DestinationCity,$EstimatedDistance,
		// $EstimatedTime,$waiting_charge,$night_charges,$road_tax,$toll_tax,$other_tax,$amount,$amount,$amount);	
		// $template=str_replace($search,$replace,$body);  		
		// //$UserName = 'Maninder';	

		return $result;
	}

	public function LocationScreenshots()
	{

	 $bok_id = $_REQUEST['bookingid'];
	$bok_status = $_REQUEST['bookingstatus'];
	$clientlatlng = explode(",",$_REQUEST['clientlatlng']);
	$destlatlng = explode(",", $_REQUEST['destlatlng']);
	$driverlatlng = explode(",", $_REQUEST['driverlatlng']);
	$image = $_REQUEST['image'];
	$ip = $_SERVER['REMOTE_ADDR'];

	// 	$data = file_get_contents("/var/www/html/hello42/image/tiger.bmp");

	// $img = base64_encode($data);

	// 	$bok_id = 8646;
	// $bok_status = 3;
	// $clientlatlng = array('23423423','234234234');
	// $destlatlng = array('23423423','234234234');
	// $driverlatlng = array('23423423','234234234');
	// $image = $img;
	// $ip = $_SERVER['REMOTE_ADDR'];

	$res = $this->con->query("INSERT INTO `tbl_location_screenshots`(`booking_id`, `booking_status`, 
	`location_image`, `clnt_lat`, `clnt_lng`, `drvr_lat`, `drvr_lng`, `dest_lat`, `dest_lng`,
	`created_date`, `ip`) VALUES ('$bok_id','$bok_status','$image','$clientlatlng[0]','$clientlatlng[1]',
	'$driverlatlng[0]','$driverlatlng[1]','$destlatlng[0]','$destlatlng[1]',NOW(),'$ip')");

			// if($res) $response = array("status"=>1,"message"=>"Request done successfully");
			// else $response = array("status"=>0,"message"=>"some internal error occured while completing your request");

	if($res) $response = true;
			else $response = false;

			return $response;

	}

	public function sendImageByDefault(){
				$target_path = "uploads/";
				$response = array();	
		       $server_ip = gethostbyname(gethostname());
				$file_upload_url = 'http://' . $server_ip . '/' . 'hello42' . '/' . $target_path;
				if (isset($_FILES['image']['name'])) {
					$ImgName=basename($_FILES['image']['name']);
					$target = $target_path . $ImgName;
					$response['file_name'] = $ImgName;
					/***************Get Token and user info**************/
					  $token=$_POST["token"];
						$sql="SELECT * FROM tbluserinfo WHERE token='$token'";		
						$result = mysqli_query($this->con,$sql);
					 $count = mysqli_num_rows($result);
					 if($count < 1 ){
						return array("Status" => "Failed");
					}else{		
							$res=mysqli_fetch_object($result);			
							  $record=$res->ISD_Code;				
						 }
						 /***********end******************/
					try {
						if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
							$response['error'] = true;
							$response['message'] = 'Could not move the file!';
						}
						$response['message'] = 'File uploaded successfully!';
						$response['error'] = false;
						$response['file_path'] = $file_upload_url . $ImgName;
					} catch (Exception $e) {
						$response['error'] = true;
						$response['message'] = $e->getMessage();
					}
				} else {
					$response['error'] = true;
					$response['message'] = 'Not received any file!F';
				}
				echo json_encode($response);	
	}
	public function paymentMailUser(){
		
	}
	
	///////////Function for Logs////////
	
	
	public function login_logs_mohit(){
		$id=$_REQUEST['id'];
		$is_search=$_REQUEST['is_search'];
		$from_date=$_REQUEST['from_date'];
		$to_date=$_REQUEST['to_date'];
		if($is_search=="true"){
		$result=mysqli_query($this->con,"SELECT login_time,logout_time, login_date as `date`,login_diff as diff from tblloginlog WHERE login_date BETWEEN '$from_date' AND '$to_date' and userId='$id' ORDER by id DESC") or die(mysqli_error($this->con));   	
		$sql_total_hrs="SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(login_diff))) as totalHrs from tblloginlog WHERE login_date BETWEEN '$from_date' AND '$to_date' and userId='$id'";
		}else{
		$result=mysqli_query($this->con,"SELECT login_time,logout_time, login_date as `date`,login_diff as diff from tblloginlog WHERE userId='$id' ORDER by id DESC") or die(mysqli_error($this->con));   
		$sql_total_hrs="SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(login_diff))) as totalHrs from tblloginlog WHERE userId='$id'";
		}	
		$result_total_hrs=mysqli_query($this->con,$sql_total_hrs) or die(mysqli_error($this->con));
		$total = mysqli_fetch_assoc($result_total_hrs);
		$totalHrs = $total['totalHrs'];
		
		$logs=array();
		$diff="00:00:00";
		$k=0;
		while($row=mysqli_fetch_array($result)){
			if(array_key_exists($row['date'],$logs)){
				$time=$this->addiontionOfTime($logs[$row['date']]['hours'], $row['diff']);     //call function  
				$logs[$row['date']]['hours']=$time['time_sum'];
			if($row['login_time']!=""&&$k>1){
				$logs[$row['date']]['login_time']=$row['login_time']; 
			}
			if($logs[$row['date']]['logout_time']==""&&$k>1){
				$logs[$row['date']]['logout_time']=$row['logout_time']; 
			}
			}else{
				$k++;
				$logs[$row['date']]['hours']=$row['diff'];
				$logs[$row['date']]['login_time']=$row['login_time'];
				$logs[$row['date']]['logout_time']=$row['logout_time'];
			}
		}
		$date=array();
		foreach($logs as $log=>$v){
			$date[]=array('date'=>$log,'hours'=>$logs[$log]['hours'],'login_time'=>$logs[$log]['login_time'],'logout_time'=>$logs[$log]['logout_time'] );
		}
		//return array('data'=>$date);
		return array('status'=>'true','data'=>$date,"totalHrs"=>$totalHrs);
	}
	
	
	    public function login_logs_2_mohit(){
				  
				$id=$_REQUEST['id'];
                $date=$_REQUEST['date'];           
                
               $result=mysqli_query($this->con,"SELECT login_time,logout_time, login_date as `date`,login_diff as diff from tblloginlog WHERE userId='$id' and login_date='$date' ORDER by id DESC") or die(mysqli_error($this->con));   
                $logs=array();
                $diff=0;
                while($row=mysqli_fetch_assoc($result))
                {
                    $diff =strtotime($diff +$row["diff"]);
                   
                    $logs[]=$row;
                    
                }
                $diff = date("H:i:s",$diff);
		//return array('data'=>$logs);
		return array('status'=>'true','data'=>$logs);
		
		
		}
		public function getstatecity(){
		 $driverAddress=$_POST["Geoaddress"];
		$find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($driverAddress));
		file_put_contents("json.txt",$find_pickup); 					
		$enc=json_decode($find_pickup);		
		$state="";
        $city="";		
		foreach($enc->results[0]->address_components as $v){
			if($v->types[0]=="administrative_area_level_1"){
				$state=$v->long_name;
			}
			if($v->types[0]=="locality"){
				$city=$v->long_name;
			}
			if($v->types[0]=="country")						
			{							
				$country=$v->long_name;
			}
		}	 
		 if($state == '' && $city == ''  && $country == ''){
			return array("Status" => "Failed");
		}
		else{
			    $sql="SELECT * FROM tblcountry WHERE CountryName LIKE '%$country'";
				$result = mysqli_query($this->con,$sql);
			   $count = mysqli_num_rows($result);
					$res=mysqli_fetch_object($result);			
				    $ISD_Code=$res->DialCode;
				return array("Status" => "Success","state"=>$state,"city"=>$city,"COUNTRY_NAME"=>$country,"ISD"=>$ISD_Code);				
			 }	
	}
	public function driverEdit_Profile(){		
		extract($_REQUEST);
		$driverlangw= implode(",",$_POST['writelang']);
		$DRIVERspeak= implode(",",$_POST['speak']);
		/*$driverAll	=	$_POST['dAirAll'];
		if($driverAll!=""){
		$ReciveAirPortTrns=	$driverAll;
		}else{
		$ReciveAirPortTrns= implode(",",$_POST['dAir']);
		}*/
		$ReciveAirPortTrns	=	$_POST['chkdata'];
		$AcceptCash			=	$_POST['chkpaytype'];
		
		if($email_driver != '' && $driver_firstName !='' && $id_tbldriver != '' && $id_tbluser !=''){
$sql="UPDATE tbldriver SET `FirstName`='$driver_firstName',`LastName`='$driver_lastName',`FatherName`='$driver_father',`refname`='$externalRef',`Email`='$email_driver',`dateofbirth`='$driverBirth',`gender`='$driverGender',`City`='$city_driver',`Address`='$driver_adds',`OfcAddress`='$driver_addofc',`ContactNo`='$driver_phone',`DrivingLicenceNo`='$driver_licence',`licence_state`='$driver_lstate',`PanCardNo`='$driver_panNos',`VehicleRegistrationNo`='$driver_vehicleNo',`ModelOfVehicle`='$driver_modely',`SecurityAmt`='$driver_amount',`route_know`='$driver_route',`pref_location`='$driver_pref',`week_off`='$driver_weekoff',`weekoff_day`='$driver_weekoffday',`imei`='$driveimei',`gps`='$drivergps',`lang_write`='$driverlangw',`lang_speak`='$DRIVERspeak',`ReciveAirPortTrns`='$ReciveAirPortTrns',`AcceptCash`='$AcceptCash',`Eyetest`='$specs_active',`status`='$driver_isActive',`ac_nonac`='$driverRAc_NonAC'  WHERE ID='$id_tbldriver' AND UID='$id_tbluser'";		 
				$result = mysqli_query($this->con,$sql);
			   if($result){
					return(array("status"=>"Success"));
				}else{
					return(array("status"=>"Query Failed"));
				}
		}else{
			return(array("status"=>"Failed","errorCode"=>"101"));
		}
	}
	public function driverTransactionHistory(){
		$tranID = $_REQUEST['tID'];
		$ids = $_REQUEST['ids'];
		$Driverid =$_REQUEST['Did'];
		$Trantype = $_REQUEST['type'];
		$createdSince = $_REQUEST['from'];
		$createdTo = $_REQUEST['to'];

		if($createdSince != "" && $createdTo == ""){
			$createdTo = date('Y-m-d');
		}elseif($createdSince == "" && $createdTo != ""){
			$createdSince = '0000-00-00';
		}elseif($createdSince == "" && $createdTo == ""){
			$createdSince = '0000-00-00';
			$createdTo = date('Y-m-d');
		}
		if($Trantype != ''){
			if($Trantype == 'All'){
				$hour=mysqli_query($this->con,"SELECT tbl_driver_transaction.id as paymentID,tbl_driver_transaction.amount as payAmount,
				tbl_driver_transaction.currentbalance as Balance,tbl_driver_transaction.status as Payment_status, CONCAT(tblcabbooking.DropAddress,' ',tblcabbooking.DropArea) as drop_area,tblcabbooking.ID as id,
			tblcabbooking.booking_reference as book_ref,tblcabbooking.BookingDate as booking_date,tblcablistmgmt.name,tblbookingcharges.totalBill,
	tbluserinfo.vehicleimg,tblmasterpackage.Master_Package as booking_type,tblcabtype.CabType as category,CONCAT(tblcabbooking.PickupDate,' ',tblcabbooking.PickupTime) as ordertime,
	concat(tblclient.clientName,'<br>(',tblappid.`type`,')') as partner,
	CONCAT(tbluserinfo.FirstName,' ',tbluserinfo.LastName) as clientname,
	CONCAT(tblcabbooking.PickupAddress,' ',tblcabbooking.PickupArea) as departure,tbldriver.VehicleRegistrationNo as reg,tblcabstatus.`status` as `status`
	 FROM tblcabbooking JOIN tblcabstatus ON tblcabbooking.`status`=tblcabstatus.`status_id` 
	 INNER JOIN tbluserinfo ON tbluserinfo.UID=tblcabbooking.pickup 
	 JOIN tblmasterpackage ON tblcabbooking.BookingType=tblmasterpackage.Package_Id
	JOIN tblcabtype ON tblcabbooking.CarType=tblcabtype.Id 
	INNER JOIN tbl_driver_transaction ON tblcabbooking.pickup=tbl_driver_transaction.user_id 
	 INNER JOIN tbldriver ON tblcabbooking.pickup=tbldriver.uid 
	  JOIN tblappid ON tblappid.id=tblcabbooking.partner 
	  INNER JOIN tbluser ON tblcabbooking.pickup = tbluser.ID
	 JOIN tblclient ON tblappid.clientId=tblclient.id 
	 INNER JOIN tblbookingcharges ON tblcabbooking.ID=tblbookingcharges.BookingID 
	 LEFT JOIN tblcomplaint ON tblcabbooking.booking_reference=tblcomplaint.BookingID 
	 INNER JOIN tblcablistmgmt ON tblcablistmgmt.id = tbldriver.vehicleId	  
	 WHERE  tblcabstatus.`type`='cab' AND tbluser.token = '$ids' AND tblcabbooking.pickup='$Driverid' AND tbldriver.UID='$Driverid'  
	 AND DATE(tblcabbooking.BookingDate) 
	 BETWEEN '$createdSince' and '$createdTo' order by tblcabbooking.ID desc;") or die(mysqli_error($this->con));
				}elseif($tranID != ''){
				$hour=mysqli_query($this->con,"SELECT tbl_driver_transaction.id as paymentID,tbl_driver_transaction.amount as payAmount,
				tbl_driver_transaction.currentbalance as Balance,tbl_driver_transaction.status as Payment_status, CONCAT(tblcabbooking.DropAddress,' ',tblcabbooking.DropArea) as drop_area,tblcabbooking.ID as id,
			tblcabbooking.booking_reference as book_ref,tblcabbooking.BookingDate as booking_date,tblcablistmgmt.name,tblbookingcharges.totalBill,
	tbluserinfo.vehicleimg,tblmasterpackage.Master_Package as booking_type,tblcabtype.CabType as category,CONCAT(tblcabbooking.PickupDate,' ',tblcabbooking.PickupTime) as ordertime,
	concat(tblclient.clientName,'<br>(',tblappid.`type`,')') as partner,
	CONCAT(tbluserinfo.FirstName,' ',tbluserinfo.LastName) as clientname,
	CONCAT(tblcabbooking.PickupAddress,' ',tblcabbooking.PickupArea) as departure,tbldriver.VehicleRegistrationNo as reg,tblcabstatus.`status` as `status`
	 FROM tblcabbooking JOIN tblcabstatus ON tblcabbooking.`status`=tblcabstatus.`status_id` 
	 INNER JOIN tbluserinfo ON tbluserinfo.UID=tblcabbooking.pickup 
	 JOIN tblmasterpackage ON tblcabbooking.BookingType=tblmasterpackage.Package_Id
	JOIN tblcabtype ON tblcabbooking.CarType=tblcabtype.Id 
	INNER JOIN tbl_driver_transaction ON tblcabbooking.pickup=tbl_driver_transaction.user_id 
	 INNER JOIN tbldriver ON tblcabbooking.pickup=tbldriver.uid 
	  JOIN tblappid ON tblappid.id=tblcabbooking.partner 
	  INNER JOIN tbluser ON tblcabbooking.pickup = tbluser.ID
	 JOIN tblclient ON tblappid.clientId=tblclient.id 
	 INNER JOIN tblbookingcharges ON tblcabbooking.ID=tblbookingcharges.BookingID 
	 LEFT JOIN tblcomplaint ON tblcabbooking.booking_reference=tblcomplaint.BookingID 
	 INNER JOIN tblcablistmgmt ON tblcablistmgmt.id = tbldriver.vehicleId	  
	 WHERE  tblcabstatus.`type`='cab' AND tbluser.token = '$ids' AND tblcabbooking.pickup='$Driverid' AND tbldriver.UID='$Driverid'  
	 AND tbl_driver_transaction.id ='$tranID' order by tblcabbooking.ID desc;") or die(mysqli_error($this->con));
				}else{
					$hour=mysqli_query($this->con,"SELECT tbl_driver_transaction.id as paymentID,tbl_driver_transaction.amount as payAmount,
				tbl_driver_transaction.currentbalance as Balance,tbl_driver_transaction.status as Payment_status, CONCAT(tblcabbooking.DropAddress,' ',tblcabbooking.DropArea) as drop_area,tblcabbooking.ID as id,
			tblcabbooking.booking_reference as book_ref,tblcabbooking.BookingDate as booking_date,tblcablistmgmt.name,tblbookingcharges.totalBill,
	tbluserinfo.vehicleimg,tblmasterpackage.Master_Package as booking_type,tblcabtype.CabType as category,CONCAT(tblcabbooking.PickupDate,' ',tblcabbooking.PickupTime) as ordertime,
	concat(tblclient.clientName,'<br>(',tblappid.`type`,')') as partner,
	CONCAT(tbluserinfo.FirstName,' ',tbluserinfo.LastName) as clientname,
	CONCAT(tblcabbooking.PickupAddress,' ',tblcabbooking.PickupArea) as departure,tbldriver.VehicleRegistrationNo as reg,tblcabstatus.`status` as `status`
	 FROM tblcabbooking JOIN tblcabstatus ON tblcabbooking.`status`=tblcabstatus.`status_id` 
	 INNER JOIN tbluserinfo ON tbluserinfo.UID=tblcabbooking.pickup 
	 JOIN tblmasterpackage ON tblcabbooking.BookingType=tblmasterpackage.Package_Id
	JOIN tblcabtype ON tblcabbooking.CarType=tblcabtype.Id 
	INNER JOIN tbl_driver_transaction ON tblcabbooking.pickup=tbl_driver_transaction.user_id 
	 INNER JOIN tbldriver ON tblcabbooking.pickup=tbldriver.uid 
	  JOIN tblappid ON tblappid.id=tblcabbooking.partner 
	  INNER JOIN tbluser ON tblcabbooking.pickup = tbluser.ID
	 JOIN tblclient ON tblappid.clientId=tblclient.id 
	 INNER JOIN tblbookingcharges ON tblcabbooking.ID=tblbookingcharges.BookingID 
	 LEFT JOIN tblcomplaint ON tblcabbooking.booking_reference=tblcomplaint.BookingID 
	 INNER JOIN tblcablistmgmt ON tblcablistmgmt.id = tbldriver.vehicleId	  
	 WHERE  tblcabstatus.`type`='cab' AND tbluser.token = '$ids' AND tblcabbooking.pickup='$Driverid' AND tbldriver.UID='$Driverid'  
	 AND DATE(tblcabbooking.BookingDate) 
	 BETWEEN '$createdSince' and '$createdTo' order by tblcabbooking.ID desc;") or die(mysqli_error($this->con));
			}			
		}else{ 
			$hour=mysqli_query($this->con,"CALL `sp_a_driverOrderHistory`('$ids','$createdSince','$createdTo')") or die(mysqli_error($this->con));
		}
		

		while($row=mysqli_fetch_array($hour))
		{

			if($row['vehicleimg'] != ''){
				$image = $_SERVER['REQUEST_URI'];
				$image=explode('/',$image);
				//print_r($image);
				$image1='/'.$image[1].'/'.$row['vehicleimg'];
			}
			$orderTime = $row['ordertime'];
			$splitTimeStamp = explode(" ",$orderTime);
			$Pickdate = $splitTimeStamp[0];
			$Picktime = $splitTimeStamp[1];
			$booking_date = $row['booking_date'];
			$BOOKINGTimeStamp = explode(" ",$booking_date);
			$Bookdate = $BOOKINGTimeStamp[0];
			$Booktime = $BOOKINGTimeStamp[1];
			//"<img src='$image1' style='width:50%'>"
			//$row['partner']
			//$row['book_amount']
			$hour_list[]=array($row['id'],$Bookdate,$row['paymentID'],'<label data="'.$row['id'].'" class="clickme" style="padding: 0px 10px 0px 0;color:#08c; cursor:pointer;">'.$row['book_ref'].
			'</label>',$row['booking_type'],$row['category'],$row['clientname'],$Pickdate.'<br/>'.$Picktime,$row['departure'],
			$row['drop_area'],$row['status']);

		}
		if(mysqli_num_rows($hour) >0 ){
			return array("status"=>"true","data"=>$hour_list);
		}else{
			return array("status"=>"false","data"=>"");
		}

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
	public function getCurrentBookingList(){
		$save=mysqli_query($this->con,"SELECT `tblcabbooking`.*, tbldriver.Email as driver_email, CONCAT(tbldriver.FirstName,' ',tbldriver.LastName) 
		as driver_name, tbldriver.ContactNo as driver_mobile FROM `tblcabbooking` INNER JOIN tbldriver on tblcabbooking.pickup=tbldriver.UID WHERE BookingDate > DATE_SUB(NOW(),INTERVAL 60 MINUTE) AND tblcabbooking.Status NOT IN(1,10,11)");
		$data=array();
		if(mysqli_num_rows($save)>0){
			while($record=mysqli_fetch_assoc($save)){
				$data[]=$record;
			}			
			return array("status"=>true,"data"=>$data);
		}else{
			return array("status"=>false,"message"=>"No Current Booking Now.");
		}
	}
	public function AndroidTermsConditionLink(){
		$link="http://bookingcabs.com/index/page?id=terms_condition";
		return array("status"=>true,"link"=>$link);
	}
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
	  public function FetchAllFavoriteAddress(){
		  $userId = trim($_REQUEST['userId']);
		$userDT = mysqli_query($this->con,"SELECT * FROM `tbl_favorite_addresses` WHERE ClientId='$userId'") or die(mysqli_error($this->con));
		if(mysqli_num_rows($userDT) > 0){			
			$DataArray=array();
			$i=0;
			while($suerData = mysqli_fetch_object($userDT)){
				$address=array();
				$result = mysqli_query($this->con,"SELECT * FROM `rt_locations` WHERE id='$suerData->Address_ID'") or die(mysqli_error($this->con));
					$userData = mysqli_fetch_object($result);
					foreach($userData as $key=>$value):
					  $address[$key]=$value;
					endforeach;
					$address["title"]=$suerData->Ref_Name;
					$address["Fav_id"]=$suerData->id;
					$DataArray[$i]=$address;
				$i++;	
			}
          return array("status"=>true,"Data"=>$DataArray);			
		}else{
			return array("status"=>false,"message"=>"No Favorite address added by this user");
		}		
	  }
	 public function MakeAddressFavorite(){
		 $userId = trim($_REQUEST['userId']);
		 $booking_id=$_REQUEST['booking_id'];
		 $Ref_Name=$_REQUEST['Ref_Name'];//tbl_favorite_addresses
		 $query="SELECT * FROM `tblcabbooking` WHERE ID='$booking_id'";
		$data = mysqli_query($this->con, $query) or die(mysqli_error($this->con));
			if(mysqli_num_rows($data) > 0){
				$record=mysqli_fetch_object($data);
				$Bookign_ID=$record->id;
				$PickupArea=$record->PickupArea;
				$PickupAddress=$record->PickupAddress;
				$fullAddress=$PickupAddress.", ".$PickupArea;
				$ClientID=$record->ClientID;								
				 $pickup="SELECT * FROM `rt_locations` WHERE area='$PickupArea'";
				 $is_pickup = mysqli_query($this->con, $pickup) or die(mysqli_error($this->con));
				 if(mysqli_num_rows($is_pickup) > 0){
					 $dataww=mysqli_fetch_object($is_pickup);				
				     $address_id=$dataww->id;	
					mysqli_query($this->con,"INSERT INTO `tbl_favorite_addresses` set Ref_Name='$Ref_Name',Bookign_ID='$Bookign_ID',ClientId='$ClientID',Address_ID='$address_id'");
					 /***************Fetch user Favorite Addresses*/
							 $userDT = mysqli_query($this->con,"SELECT * FROM `tbl_favorite_addresses` WHERE ClientId='$userId'") or die(mysqli_error($this->con));
							if(mysqli_num_rows($userDT) > 0){			
								$DataArray=array();
								$i=0;
								while($suerData = mysqli_fetch_object($userDT)){
									$address=array();
									$result = mysqli_query($this->con,"SELECT * FROM `rt_locations` WHERE id='$suerData->Address_ID'") or die(mysqli_error($this->con));
										$userData = mysqli_fetch_object($result);
										foreach($userData as $key=>$value):
										  $address[$key]=$value;
										endforeach;
										$address["title"]=$suerData->Ref_Name;
										$address["Fav_id"]=$suerData->id;
										$DataArray[$i]=$address;
									$i++;	
								}
							}
					 /******************/
					return array('status'=>true, 'message'=>'Address added Successfully',"Data"=>$DataArray);
				 }else{
					 $find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($fullAddress));
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
								if($v->types[0]=="country")						
									{							
										$country=$v->long_name;
									}       
								if($v->types[0]=="administrative_area_level_1")						
									{							
										$state=$v->long_name;
									}
								if($v->types[0]=="administrative_area_level_2")						
									{							
										$zone=$v->long_name;
									}        
								
								if($v->types[0]=="sublocality_level_1")						
									{							
										$area=$v->long_name;
									}
						}
						mysqli_query($this->con,"INSERT INTO rt_locations(`area`,`city`,`lat`,`lon`,`zone`,`country`,`state`)"
											. " VALUES('$area','$city','$lat','$long','$zone','$country','$state')");
						   $lastid=mysqli_insert_id($this->con);
						   
						mysqli_query($this->con,"INSERT INTO `tbl_favorite_addresses` set Ref_Name='$Ref_Name',Bookign_ID='$Bookign_ID',ClientId='$ClientID',Address_ID='$lastid'");
						 /***************Fetch user Favorite Addresses*/
							 $userDT = mysqli_query($this->con,"SELECT * FROM `tbl_favorite_addresses` WHERE ClientId='$userId'") or die(mysqli_error($this->con));
							if(mysqli_num_rows($userDT) > 0){			
								$DataArray=array();
								$i=0;
								while($suerData = mysqli_fetch_object($userDT)){
									$address=array();
									$result = mysqli_query($this->con,"SELECT * FROM `rt_locations` WHERE id='$suerData->Address_ID'") or die(mysqli_error($this->con));
										$userData = mysqli_fetch_object($result);
										foreach($userData as $key=>$value):
										  $address[$key]=$value;
										endforeach;
										$address["title"]=$suerData->Ref_Name;
										$address["Fav_id"]=$suerData->id;
										$DataArray[$i]=$address;
									$i++;	
								}
							}
					 /******************/
						return array('status'=>true, 'message'=>'Address added Successfully',"Data"=>$DataArray);
					 }else{
						 return array('status'=>false, 'message'=>'Invalid Address');
					 }
				 }
				
			}else{
				return array('status'=>false, 'message'=>'Invalid Booking id');
			}				 
	 }
	 public function AddFavoriteAddress(){
		 $Ref_Name=$_REQUEST['Ref_Name'];
		 $ClientID = trim($_REQUEST['userId']);		
		 $PickupArea=$_REQUEST['PickupArea'];		
		 $pickup="SELECT * FROM `rt_locations` WHERE area='$PickupArea'";
				 $is_pickup = mysqli_query($this->con, $pickup) or die(mysqli_error($this->con));
				 if(mysqli_num_rows($is_pickup) > 0){
					 $dataww=mysqli_fetch_object($is_pickup);				
				     $address_id=$dataww->id;	
					mysqli_query($this->con,"INSERT INTO `tbl_favorite_addresses` set Ref_Name='$Ref_Name',ClientId='$ClientID',Address_ID='$address_id'");
					/***************Fetch user Favorite Addresses*/
						 $userDT = mysqli_query($this->con,"SELECT * FROM `tbl_favorite_addresses` WHERE ClientId='$ClientID'") or die(mysqli_error($this->con));
						if(mysqli_num_rows($userDT) > 0){			
							$DataArray=array();
							$i=0;
							while($suerData = mysqli_fetch_object($userDT)){
								$address=array();
								$result = mysqli_query($this->con,"SELECT * FROM `rt_locations` WHERE id='$suerData->Address_ID'") or die(mysqli_error($this->con));
									$userData = mysqli_fetch_object($result);
									foreach($userData as $key=>$value):
									  $address[$key]=$value;
									endforeach;
									$address["title"]=$suerData->Ref_Name;
									$address["Fav_id"]=$suerData->id;
									$DataArray[$i]=$address;
								$i++;	
							}
						}
				 /******************/
					return array('status'=>true, 'message'=>'Address added Successfully',"Data"=>$DataArray);
				 }else{
					 $find_pickup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($PickupArea));
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
								if($v->types[0]=="country")						
									{							
										$country=$v->long_name;
									}       
								if($v->types[0]=="administrative_area_level_1")						
									{							
										$state=$v->long_name;
									}
								if($v->types[0]=="administrative_area_level_2")						
									{							
										$zone=$v->long_name;
									}        
								
								if($v->types[0]=="sublocality_level_1")						
									{							
										$area=$v->long_name;
									}
						}
						mysqli_query($this->con,"INSERT INTO rt_locations(`area`,`city`,`lat`,`lon`,`zone`,`country`,`state`)"
											. " VALUES('$PickupArea','$city','$lat','$long','$zone','$country','$state')");
						   $lastid=mysqli_insert_id($this->con);
						   
						mysqli_query($this->con,"INSERT INTO `tbl_favorite_addresses` set Ref_Name='$Ref_Name',ClientId='$ClientID',Address_ID='$lastid'");
						/***************Fetch user Favorite Addresses*/
							 $userDT = mysqli_query($this->con,"SELECT * FROM `tbl_favorite_addresses` WHERE ClientId='$ClientID'") or die(mysqli_error($this->con));
							if(mysqli_num_rows($userDT) > 0){			
								$DataArray=array();
								$i=0;
								while($suerData = mysqli_fetch_object($userDT)){
									$address=array();
									$result = mysqli_query($this->con,"SELECT * FROM `rt_locations` WHERE id='$suerData->Address_ID'") or die(mysqli_error($this->con));
										$userData = mysqli_fetch_object($result);
										foreach($userData as $key=>$value):
										  $address[$key]=$value;
										endforeach;
										$address["title"]=$suerData->Ref_Name;
										$address["Fav_id"]=$suerData->id;
										$DataArray[$i]=$address;
									$i++;	
								}
							}
					 /******************/
						return array('status'=>true, 'message'=>'Address added Successfully',"Data"=>$DataArray);
					 }else{
						 return array('status'=>false, 'message'=>'Invalid Address');
					 }
				 }
	 }
	 public function EditFavoriteAddress(){
		 $userId = trim($_REQUEST['userId']);
		  $id = trim($_REQUEST['id']);
		  $Ref_Name = trim($_REQUEST['Ref_Name']);
		mysqli_query($this->con,"UPDATE `tbl_favorite_addresses` set Ref_Name='$Ref_Name' WHERE id='$id'");
		if(mysqli_affected_rows($this->con)>0){	
			/***************Fetch user Favorite Addresses*/
					 $userDT = mysqli_query($this->con,"SELECT * FROM `tbl_favorite_addresses` WHERE ClientId='$userId'") or die(mysqli_error($this->con));
					if(mysqli_num_rows($userDT) > 0){			
						$DataArray=array();
						$i=0;
						while($suerData = mysqli_fetch_object($userDT)){
							$address=array();
							$result = mysqli_query($this->con,"SELECT * FROM `rt_locations` WHERE id='$suerData->Address_ID'") or die(mysqli_error($this->con));
								$userData = mysqli_fetch_object($result);
								foreach($userData as $key=>$value):
								  $address[$key]=$value;
								endforeach;
								$address["title"]=$suerData->Ref_Name;
								$address["Fav_id"]=$suerData->id;
								$DataArray[$i]=$address;
							$i++;	
						}
					}
			 /******************/
			$message="Updated Successfully";
            return array("status"=>true,"message"=>$message,"Data"=>$DataArray);			
		}else{
			$message="Not Updated ! Invalid Address id";
			return array("status"=>false,"message"=>$message);
		}		
	  }
	   public function DeleteFavoriteAddress(){
		   $userId = trim($_REQUEST['userId']);
		  $id = trim($_REQUEST['id']);		  
		mysqli_query($this->con,"DELETE FROM `tbl_favorite_addresses` WHERE id='$id'");
		if(mysqli_affected_rows($this->con)>0){	
			/***************Fetch user Favorite Addresses*/
					 $userDT = mysqli_query($this->con,"SELECT * FROM `tbl_favorite_addresses` WHERE ClientId='$userId'") or die(mysqli_error($this->con));
					if(mysqli_num_rows($userDT) > 0){			
						$DataArray=array();
						$i=0;
						while($suerData = mysqli_fetch_object($userDT)){
							$address=array();
							$result = mysqli_query($this->con,"SELECT * FROM `rt_locations` WHERE id='$suerData->Address_ID'") or die(mysqli_error($this->con));
								$userData = mysqli_fetch_object($result);
								foreach($userData as $key=>$value):
								  $address[$key]=$value;
								endforeach;
								$address["title"]=$suerData->Ref_Name;
								$address["Fav_id"]=$suerData->id;
								$DataArray[$i]=$address;
							$i++;	
						}
					}else{
						$DataArray=array();
					}
			 /******************/
			$message="Deleted Successfully";
            return array("status"=>true,"message"=>$message,"Data"=>$DataArray);			
		}else{
			$message="Not Deleted ! Invalid Address id";
			return array("status"=>false,"message"=>$message);
		}		
	  }

	///// Function for Driver Rating from admin to driver Starts Here By Mohit Jain //////////
	
	 public function DriverRatingService(){	
		$BookingId=$_REQUEST["BookingId"];
		$driverRating=$_REQUEST["UserRating"];
		$adminId=$_REQUEST["adminId"];
		$created_by=$_REQUEST["created_by"];
		
		$sql = "SELECT * FROM tblcabbooking WHERE ID = '$BookingId'"; 
		$qry = mysqli_query($this->con,$sql);
		$row = mysqli_fetch_object($qry);		 

		$row->ClientID = ($adminId !='')? $adminId :$row->ClientID; 
		$created_by = ($created_by !='')? "admin" : "user";

	    $sql = "INSERT INTO tbldriverrating (BookingId, DID, UID, rating,created_by) VALUES 
		('".$row->ID."', '".$row->pickup."', '".$adminId."', '".$driverRating."','".$created_by."')";
		$qry = mysqli_query($this->con, $sql);
		if(mysqli_affected_rows($this->con)>0){
			return array("Status"=>true);
		}else{
			return array("Status"=>false);
		}			
	 }
	 
	 ///// Function for Driver Rating from admin to driver Ends Here By Mohit Jain //////////
	 
	 public function getDriverPrefcity(){
		$result = mysqli_query($this->con,"SELECT id,state as CityName FROM `tblstates` where Status='1'");
		 $data = mysqli_num_rows($result);		 
		 if($data < 1){
			return array("Status" => "Failed");
		}else{			
			$record=array();
			$i=0;
			while($res=mysqli_fetch_array($result)){
				$record[$i]["id"]=$res["id"];
				$record[$i]["CityName"]=$res["CityName"];
			$i++;	
			}		
		}	
		return array("Status" => "Success","record"=>$record);
	 }
	 public function getDriverSupportedCities(){
		$tblcabtype = mysqli_query($this->con,"SELECT id,state as CityName FROM `tblstates` where Status='1'");
	     $count = mysqli_num_rows($tblcabtype);
		 if($count < 1 ){
			return array("Status" => "Failed");
		}else{
			 $j=0;
			$cabArray=array();
			while($Cabdata=mysqli_fetch_array($tblcabtype)){
				$cabArray[$j]["id"]=$Cabdata["id"];
				$cabArray[$j]["CityName"]=$Cabdata["CityName"];
			$j++;	
			}
		}	
	    return array("Status" => "Success","CityData"=>$cabArray);
	 }
	 public function getsubPackageServiceAndroid(){
		 /*Fetch all Master Package*/
		 $tblcabtype = mysqli_query($this->con,"SELECT id, Master_Package,Package_Id,Sub_Package_Id FROM `tblmasterpackage`");		 
		 $master=array();
		 $k=0;
		 while($Cabdat=mysqli_fetch_array($tblcabtype)){
				$master[$k]["id"]=$Cabdat["id"];
				$master[$k]["PackageName"]=$Cabdat["Master_Package"];
				$master[$k]["Package_Id"]=$Cabdat["Package_Id"];
				$master[$k]["Sub_Package_Id"]=$Cabdat["Sub_Package_Id"];
			$k++;	
			}
		$Sub_Package_Id=$master[0]["Sub_Package_Id"]; 
		/*Fetch all Local Subpackage*/
		$tblcabt = mysqli_query($this->con,"SELECT Package,id,masterPKG_ID FROM `tbllocalpackage` WHERE Sub_Package_Id='$Sub_Package_Id' group by Package order by id asc");
			 $j=0;
			$localPKG=array();
			while($Cabdata=mysqli_fetch_array($tblcabt)){
				$localPKG[$j]["id"]=$Cabdata["id"];
				$localPKG[$j]["PackageName"]=$Cabdata["Package"];
				$localPKG[$j]["Master Package_Id"]=$Cabdata["masterPKG_ID"];
			$j++;	
			}
		/*Fetch all Airport Subpackage*/
		$tblcabtww = mysqli_query($this->con,"SELECT * FROM `tblairportpackage` where masterPKG_ID='103' order by id asc");
			 $i=0;
			$AirportPKG=array();
			while($Airdata=mysqli_fetch_array($tblcabtww)){
				$AirportPKG[$i]["id"]=$Airdata["id"];
				$AirportPKG[$i]["PackageName"]=$Airdata["pkgName"];
				$AirportPKG[$i]["Master Package_Id"]=$Airdata["masterPKG_ID"];
				$AirportPKG[$i]["Latitude"]=$Airdata["lat"];
				$AirportPKG[$i]["Longitude"]=$Airdata["long"];
				$AirportPKG[$i]["city"]=$Airdata["city"];
				$AirportPKG[$i]["state"]=$Airdata["state"];
				$AirportPKG[$i]["country"]=$Airdata["country"];
				$AirportPKG[$i]["zone"]=$Airdata["zone"];
			$i++;	
			}
			/*Fetch all Outstation Subpackage*/
		$tbout = mysqli_query($this->con,"SELECT * FROM `tbloutstationpackage` where masterPKG_ID='104' order by id asc");
			 $p=0;
			$OutstainPKG=array();
			while($Outdata=mysqli_fetch_array($tbout)){
				$OutstainPKG[$p]["id"]=$Outdata["id"];
				$OutstainPKG[$p]["PackageName"]=$Outdata["pkgName"];
				$OutstainPKG[$p]["Master Package_Id"]=$Outdata["masterPKG_ID"];
			$p++;	
			}
	    return array("Master Package"=>$master,"Local Subpackage"=>$localPKG,"Airport Subpackage"=>$AirportPKG,"OutStationSubPackage"=>$OutstainPKG);
	 }
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
	 public function displayPromotionAndroid(){		
		 $date=date("Y-m-d h:i:s");
		 $datetime=explode(' ',$date);
		 $currentDate=$datetime[0];
		 $currentTime=$datetime[1];
		  $sql = "SELECT * FROM `tblpromotion` WHERE ValidDateTo>='$currentDate'";
		 $row = mysqli_query($this->con,$sql);
		 if(mysqli_num_rows($row)>0){
			 $i=1;
			 $data=array();
			 $datarow=array();
			 while($record=mysqli_fetch_assoc($row)){
			       $data["id"]=$record["id"];
				   $data["PromotionName"]=$record["PromotionName"];
				   $data["PromotionDescription"]=$record["PromotionDescription"];
				   $data["ValidDateFrom"]=$record["ValidDateFrom"];
                   $data["ValidDateTo"]=$record["ValidDateTo"];
				   $data["ValidTimeFrom"]=$record["ValidTimeFrom"];
				   $data["ValidTimeTo"]=$record["ValidTimeTo"];
				   $data["WeekDays"]=$record["WeekDays"];
				   $data["DiscountType"]=$record["DiscountType"];
				   $data["Discount"]=$record["Discount"];
				   $data["PromotionImageURL"]=$record["PromotionImage"];				  
				   $data["TermsCondition"]=$record["TermsCondition"];
				$datarow[]=$data;		   
			 }
			  return array("status"=>true,"data"=>$datarow);
		 }else{
			  return array("status"=>false,"message"=>"No Promotions Available in these days");
		 }
	 }
	 public function ApplyCoupon(){
		 $CouponName=trim($_REQUEST["couponCode"]);
		// return $CouponName;
		 $userId = trim($_REQUEST['userId']);
		 $DeviceType=trim($_REQUEST["DeviceType"]);
		 $AppType = trim($_REQUEST['AppType']);
		 $bookingId = trim($_REQUEST['bookingId']);
		 $sql = "SELECT * FROM `tblpromotion` WHERE PromotionName='$CouponName'";
		 $row = mysqli_query($this->con,$sql);
		 if(mysqli_num_rows($row)>0){
			 $date = mysqli_fetch_object($row);
			 $CouponType = $date->CouponType;
			 $couponId = $date->id;		
			   $query = "SELECT * FROM tblcouponmaster WHERE PromotionID='$CouponType' AND CouponID='$couponId' AND UserID='$userId'";
		       $fetch = mysqli_query($this->con,$query);
			   
				$sql_promotion = "SELECT * FROM `tblpromotionmaster` WHERE id='$CouponType'";
				$res = mysqli_query($this->con,$sql_promotion);
				$data_res = mysqli_fetch_object($res);
				$PromotionName = $data_res->PromotionName;
			   
			   if($bookingId!='')
			   {
				$query_update = "UPDATE `tblcabbooking` SET PrometionalCode='$CouponName',PrometionalName='$PromotionName' WHERE ID='$bookingId'"; 
				mysqli_query($this->con,$query_update);	
			   }
			   
			   if(mysqli_num_rows($fetch)>0){
				   $record = mysqli_fetch_object($fetch);
				   $id=$record->id;
				   $is_Used=$record->is_Used;
				   $is_Applied=$record->is_Applied;
				   if($is_Used >=1){
					   return array("status"=>false,"message"=>"You have already used this coupon");
				   }else{
					   $is_Applied=$is_Applied+1;
					   $query11 = "UPDATE `tblcouponmaster` SET is_Applied='$is_Applied' WHERE id='$id' AND PromotionID='$CouponType' AND CouponID='$couponId' AND UserID='$userId'" ;
						mysqli_query($this->con,$query11);					
						if(mysqli_affected_rows($this->con) > 0){
							return array("status"=>true,"message"=>"Coupon Applied Successfully","PromotionName"=>$PromotionName,"CouponName"=>$CouponName);
						}else{
							return array("status"=>false,"message"=>"Problem in Query");
						}
				   }
				   					
			   }else{
				   $query11 = "INSERT INTO `tblcouponmaster` SET PromotionID='$CouponType',CouponID='$couponId',UserID='$userId',is_Applied='1',DeviceType='$DeviceType',AppType='$AppType'";
					mysqli_query($this->con,$query11);
					$lastid=mysqli_insert_id($this->con);
					if($lastid > 0){
						return array("status"=>true,"message"=>"Coupon Applied Successfully","PromotionName"=>$PromotionName,"CouponName"=>$CouponName);
					}else{
						return array("status"=>false,"message"=>"Problem in Query");
					}
			   }
			 
		 }else{
			 return array("status"=>false,"message"=>"Invalid Coupon Code");
		 }		
	 }
	 public function checkPeakTimeCurrentBooking(){		
		   $nowtime = date("H:i:s",strtotime("+30 minutes"));            
		   $data=array();
		   $query = "SELECT * FROM `tblpeaktime` WHERE ('$nowtime' BETWEEN timeFrom AND timeTo)";
		       $fetch = mysqli_query($this->con,$query);
			   if(mysqli_num_rows($fetch)>0){
				   foreach(mysqli_fetch_assoc($fetch) as $key=>$value):
				      $data[$key]=$value;
				   endforeach;
				   $return=array("status"=>"success","PeakTime"=>true,"peakCharges"=>$data["peakCharges"]);
			   }else{
				   $return=array("status"=>"success","PeakTime"=>false);
			   }
			   return $return;			   
	 }
	 public function CheckBookingAcceptance(){
		 $Booking_Ref=trim($_REQUEST["Booking_Ref"]);
		        $SQL = "UPDATE `tblcabbooking` SET is_updation_allow='FALSE' WHERE booking_reference='$Booking_Ref'";
		                mysqli_query($this->con,$SQL);
				$query = "SELECT * FROM `tblcabbooking` WHERE booking_reference='$Booking_Ref'";
		       $fetch = mysqli_query($this->con,$query);
			   if(mysqli_num_rows($fetch)>0){
				   $record = mysqli_fetch_object($fetch);
				   $is_updation_allow=$record->is_updation_allow;
				   $Booking_id=$record->ID;
				   $Driver_id=$record->pickup;
				   if($Driver_id!=0){					   
					   return array("status"=>true,"is_Accepted"=>"true","succMess"=>"Your booking has been confirmed. Driver will pickup in 30 minutes.");
				   }else{
					    return array("status"=>false,"is_Accepted"=>"false");
				   }
			   }else{
				   return array("status"=>false,"message"=>"Invalid Booking Reference");
			   }
	 }
	 public function reAcceptBooking(){
		 $Booking_Ref=trim($_REQUEST["Booking_Ref"]);
		 $carTypes = $_REQUEST['carTypes'];
		 $bookingType = $_REQUEST['bookingType'];
		 $PickUpDate=$_REQUEST['datepickerData'];
		 $cablocaltimeH=trim($_REQUEST['localTimeH']);
		 $localTimeS=trim($_REQUEST['localTimeS']);
		 $pickupTime = "$cablocaltimeH".":$localTimeS:00";
			if($PickUpDate !='' && $cablocaltimeH !='' && $localTimeS !=''){
				$SQL = "UPDATE `tblcabbooking` SET PickupDate='$PickUpDate',PickupTime='$pickupTime',is_updation_allow='TRUE' WHERE booking_reference='$Booking_Ref'";
		           mysqli_query($this->con,$SQL);
				   if(mysqli_affected_rows($this->con) > 0){
					  return array("status"=>'true',"succMess"=>"Your booking has been confirmed.","ref"=>$Booking_Ref);
				   }else{
					  return array("status"=>'false',"succMess"=>"booking time not updated","ref"=>$Booking_Ref);
				   }
			}else{
				$SQL = "UPDATE `tblcabbooking` SET CarType='$carTypes',is_updation_allow='TRUE' WHERE booking_reference='$Booking_Ref'";
		           mysqli_query($this->con,$SQL);
				   if(mysqli_affected_rows($this->con) > 0){
					  return array("status"=>'true',"succMess"=>"Your booking has been confirmed.","ref"=>$Booking_Ref);
				   }else{
					  return array("status"=>'false',"succMess"=>"booking vehicle not updated","ref"=>$Booking_Ref);
				   }
			}
	 }	 
	/////////////Function for Notification written by Mohit Jain Starts Here ////////////////
	
	
	public function getMohitNotification(){ 
	
	//Find New Booking
	for(;;){
		//sleep(2);
		//For unassigned Booking 
		/*$con->query("UPDATE booking_stack SET status='' WHERE TIMESTAMPDIFF(SECOND,last_try,NOW())>30 and status='W'") or die($con->error);
		$booking_query2=$con->query("SELECT tblcountry.currency as currency,tblcountry.distance_unit as measure,tblsubpackage.Sub_Package_Id as configPackageNo,tbluser.gcm_id,tbluser.Latitude as lat,tbluser.Longtitude1 as lon, booking_stack.*,tblcabbooking.*,tblcablistmgmt.waiting_fees FROM booking_stack 
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
		}*/


		//For Automatic Booking
		$sql_auto_book="SELECT booking_stack.*,tblcabbooking.CarType FROM booking_stack 
		INNER JOIN tblcabbooking ON booking_stack.booking_id = tblcabbooking.ID 
		WHERE booking_stack.status='' and TIMESTAMPDIFF(HOUR,NOW(),CONCAT(tblcabbooking.PickupDate,' ',PickupTime))<1 and 
		CONCAT(tblcabbooking.PickupDate,' ',PickupTime)>NOW() and pickup=0"; 
		//$booking_query=$con->query();
		$booking_query = mysqli_query($this->con, $sql_auto_book) or die(mysqli_error($this->con));
		$booking=array();
		while($row=$booking_query->fetch_array()){
			$booking[]=$row;
		}
		
		$sql_fetch_driver="SELECT tbldriver.SecurityAmt,tbldriver.UID,tbldriver.TypeOfvehicle,tbldriver.vehicleId FROM tbldriver 
						   JOIN tbluser ON tbldriver.uid=tbluser.id WHERE tbldriver.status=0 AND tbluser.loginstatus=1 and tbluser.is_active=1 and SecurityAmt>0";
		//$driver_query=$con->query() or die($con->error);
		echo $driver_query = mysqli_query($this->con, $sql_fetch_driver) or die(mysqli_error($this->con));
		$driver_id=array();
		while($driver=$driver_query->fetch_assoc()){
			$driver_id[]=$driver;
		}
		die;
		foreach($booking as $bookings){
			$bookingCarType = $bookings['CarType'];
			if($bookings['status']==''){
				$booking_id=$bookings['booking_id'];
				$con->query("UPDATE booking_stack SET last_try=NOW(),status='W' WHERE booking_id='$booking_id'");
				$con->query("UPDATE tblcabbooking SET `status`=2 WHERE id='$booking_id'");
				if($con->affected_rows>0){
					$con->query("INSERT INTO `tblbookinglogs` (`bookingid`,`status`,`message`,`time`) VALUES('$booking_id',2,'Executed',NOW())");
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
						//send_notification($id,$datam); // Uncomment it	
					}
				}
				//print_r($id);
			}
		}
	}

	}
	/////////////Function for Notification written by Mohit Jain Ends Here////////////////
	
	
	//////////// Function for Intercity Booking by Mohit Jain Starts Here/////////////////////



	public function intercityTrip(){
		$DeviceType = $_POST['DeviceType'];
		$carType = $_POST['carType'];
		$bookingType = $_POST['bookingType'];
		$intercityfromData=$_POST['intercityfrom'];
		$intercitytoData=$_POST['intercityto'];
		$intercityDepdateData=$_POST['intercityDepdate'];
		//$outRdateData=$_POST['outRdate'];
		$intercityNationalityData=$_POST['intercityNationality'];
		$intercityAdultsData=$_POST['intercityAdults'];
		$intercityChildsData=$_POST['intercityChilds'];
		$intercityLuggagesData=$_POST['intercityLuggages'];
		$intercityPickupAddressData=$_POST['intercityPickupAddress'];
		$intercityDropAddressData=$_POST['intercityDropAddress'];
		$intercityPickupH=$_POST['intercityPickupH'];
		$intercityPickupM=$_POST['intercityPickupM'];

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
		$data  = array(

			'BookingType' =>"$bookingType",
			'carType' =>"$carType",
			'PickupArea' => "$intercityfromData",
			'DropArea' => "$intercitytoData",
			'PickupDate' => "$intercityDepdateData",
			//'ReturnDate' => "$outRdateData",
			'Nationality' => "$intercityNationalityData",
			'No_Of_Adults' => "$intercityAdultsData",
			'No_Of_Childs' => "$intercityChildsData",
			'No_Of_Luggages' => "$intercityLuggagesData",
			'PickupAddress' => "$intercityPickupAddressData",
			'DropAddress' => "$intercityDropAddressData",
			'PickupTime' => "$intercityPickupH".":"."$intercityPickupM",
			'UserName' => "$userNames",
			'MobileNo' => "$mobileNo",
			'EmailId' => "$emailIds",
			'DeviceType' => "$DeviceType",
			'ClientID'	=> "$userId",
			'status'=>1,
			'partner'=>1


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
		$new	=	mysqli_insert_id($this->con);
		if(mysqli_insert_id($this->con) > 0){
			$resulddd=mysqli_query($this->con,"CALL `new_check`('$new', 'HW')");
			$booking_ref=mysqli_fetch_assoc($resulddd);

			mysqli_free_result($resulddd);
			return array("Status" => "Success","id"=>$userId,"code"=>"001","ref"=>$booking_ref['generated'],"pickupTime"=>$data['PickupTime'],"Pickupdate"=>$outDepdateData,"Returndate"=>$outRdateData);
		}else{
			return array("Status" => "Unsuccess");
		}

	}
	
	
	//////////// Function for Intercity Booking by Mohit Jain Ends Here/////////////////////
	
	//////////// Function for Intercity Booking SMS by Mohit Jain Starts Here/////////////////////
	
	public function send_intercity_booking_sms(){
		$booking_id="";
		if($booking==0){
			$booking_id=$_REQUEST['booking_id'];
		}else{
			$booking_id=$booking;
		}
		$booking_id=$_REQUEST['booking_id']; 
		$sql="SELECT tblcabbooking.MobileNo,tblcabbooking.EmailId,tblcabbooking.booking_reference,tblcabbooking.UserName,tblcabbooking.BookingDate,
			  tblcabbooking.PickupDate,tblcabbooking.PickupTime,tblcabbooking.PickupAddress,tblcabbooking.ClientID as uid,tblcabbooking.routeId,tblcabbooking.carType,
			  tblcabbooking.ClientID as uid,tbl_inter_city_route_package.* FROM tblcabbooking JOIN tbl_inter_city_route_package ON 
			  tblcabbooking.routeId=tbl_inter_city_route_package.route_id WHERE tblcabbooking.ID='$booking_id'";
		/*$sql="SELECT tblcabbooking.estimated_price, tblcabbooking.ClientID as uid,tblbookingbill.CabName as cabname,
		tblbookingbill.MinimumCharge as minimum,tblbookingbill.Min_Distance as min_distance,tblbookingbill.Per_Km_Charge as charge,
		tblbookingbill.WaitingCharge_per_minute,tblcabbooking.* FROM tblcabbooking JOIN tblbookingbill ON tblbookingbill.BookingTypeId 
		AND tblcabbooking.CarType=tblbookingbill.Id  WHERE tblcabbooking.id='$booking_id'";*/
		$result=mysqli_query($this->con,$sql);
		$fetch=mysqli_fetch_assoc($result);
		if($fetch['carType']==1){
		$cabname="Economy";
		$price=$fetch['economy_rate'];
		} elseif($fetch['carType']==2) {
		$cabname="Sedan";
		$price=$fetch['sedan_rate'];
		} elseif($fetch['carType']==3) {
		$cabname="Prime";
		$price=$fetch['prime_rate'];
		}
		
		$mobile=$fetch['MobileNo'];
		$email=$fetch['EmailId'];
		$booking_ref=$fetch['booking_reference'];
		$client=$fetch['UserName'];
		$bookingdate=$fetch['BookingDate'];
		//$cabname=$fetch['cabname'];
		//$minimum_charge=$fetch['minimum'];
		//$minimum_distance=$fetch['min_distance'];
		//$charge=$fetch['charge'];
		//$distance=$fetch['EstimatedDistance'];
		$pickup_time=$fetch['PickupDate']." ".$fetch['PickupTime'];
		$pick=$fetch['PickupAddress'];
		$uid=$fetch['uid'];
		$fixkm=$fetch['fix_km'];
		/*$fair="";
		if($distance<$minimum_distance){
			$fair=$minimum_charge;
		}else{
			$fair=$minimum_charge+($distance-$minimum_distance)*$charge;
		}*/
		$sqlSms="SELECT * FROM tbl_sms_template WHERE msg_sku='intercity_booking'";
		$msg_query=mysqli_fetch_array(mysqli_query($this->con,$sqlSms));
		
		$array=explode('<variable>',$msg_query['message']);
		$array[0]=$array[0].$client;
		$array[1]=$array[1].$booking_ref;
		$array[2]=$array[2].$pickup_time;
		$array[3]=$array[3].$price;
		$array[4]=$array[4].$fixkm;
		$text=  urlencode(implode("",$array));	
		file_put_contents("mssg.txt",$text); 
		$url="http://push1.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$mobile&from=Helocb&dlrreq=true&text=$text&alert=1";
		//die;
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
		<tr><td style="padding:10px 0px 0px 80px">Minimum bill</td><td valign="top" style="padding:10px 0px 0px 50px">Rs.'.$price.'</td></tr>
		<tr><td style="padding:0px 0px 0px 80px">After 8 Km</td><td valign="top" style="padding:0px 0px 0px 50px">Rs.'.$fixkm.'</td></tr>
		<!--<tr><td style="padding:0px 0px 10px 80px">After 10 minutes</td><td valign="top" style="padding:0px 0px 10px 50px">Rs 2 per minute</td></tr>--></table>
		
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
	
	//////////// Function for Intercity Booking SMS by Mohit Jain Ends Here/////////////////////
	
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

	
		/*if(isset($_POST['token'])){
			$token=$_POST['token'];
			echo $sql_user="SELECT `ID`,`Email`,`UserNo`,`FirstName` FROM `tbluser` JOIN `tbluserinfo` ON tbluser.ID=tbluserinfo.UID  
			WHERE `token` = '$token'";
			$result = mysqli_query($this->con,$sql_user) or die(mysqli_error($this->con));
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
		}*/

		
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
			return array('Status'=>'false', 'msg'=>'Please enter valid Drop Address');
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
		$no_of_rows=mysqli_query($this->con,"SELECT * FROM tbluser WHERE LoginName='$emailIds' LIMIT 0,1");
		if(mysqli_num_rows($no_of_rows)==0){
			mysqli_query($this->con,"INSERT INTO tbluser (`LoginName`,`UserNo`) VALUES('$emailIds','$mobileNo')");
			$user_id=mysqli_insert_id($this->con);
			mysqli_query($this->con,"INSERT INTO tbluserinfo(`FirstName`,`UID`,`MobNo`,`Email`) VALUES('$userNames','$user_id','$mobileNo','$emailIds')"); 
		}else{
			$result_new=mysqli_fetch_array($no_of_rows);
			$user_id=$result_new['ID'];
		}
		//$API_MAP = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$origin[0].",".$origin[1]."&destinations=".$destiny[0].",".$destiny[1]."&mode=driving&language=en-US&sensor=false";
		$API_MAP = "https://maps.googleapis.com/maps/api/directions/json?origin=".$origin[0].','.$origin[1]."&destination=".$destiny[0].','.$destiny[1]."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584";
		$data= file_get_contents($API_MAP);	 
		$enc=json_decode($data);
		if($enc->status == 'OK'){
			$enc2=$enc->routes[0];
			$distance=round((($enc2->legs[0]->distance->value)/1000),1);
		}else{
			return array('Status'=>'false', 'msg'=>'Please enter valid Address');
		}
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
			'EstimatedDistance'=>"$distance",
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
			  /*if(isset($_REQUEST["isTravellerDetails"])){
			$Traveller_Name=$_REQUEST['Traveller_Name'];
			$Traveller_Mobile=$_REQUEST['Traveller_Mobile'];
			$Traveller_Note=$_REQUEST['Traveller_Note'];
			$this->BookForThirdPerson($Traveller_Name,$Traveller_Mobile,$Traveller_Note,$UserID,$carType,$bookingType,$booking_id);			
		    } */ 
			$resulddd=mysqli_query($this->con,"CALL `new_check`('$booking_id', 'HW')");
			$booking_ref=mysqli_fetch_assoc($resulddd);
			mysqli_free_result($resulddd); 
			mysqli_next_result($this->con);
			/*$data= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$origin[0].','.$origin[1]."&destination=".$destiny[0].','.$destiny[1]."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584");
			$enc=json_decode($data);			
			if($enc->status == 'OK'){
				$enc2=$enc->routes[0];
				$distance2=round((($enc2->legs[0]->distance->value)/1000),1);
				$distance=$distance2;
				$data=$this->Estimated_Price($distance, $booking_id);
			}else{
				return array('Status'=>'false', 'msg'=>'Please enter valid Address');
			}*/
			$totalbill=$carPrice;
			$totalkm=$carFixKm;
			/*$totalbill=$data['tripInfo']['totalbill'];
			$min_distance=$data['tripInfo']['min_distance'];
			$per_km_charge=$data['tripInfo']['per_km_chg'];
			$waiting_min=$data['tripInfo']['waiting_minutes'];
			$wait_charge=$data['tripInfo']['wait_charge'];*/
			file_put_contents('pointbill.txt',$totalbill);
			/*mysqli_query($this->con,"update tblcabbooking SET estimated_price='$totalbill',approx_distance_charge='$per_km_charge',
			`approx_after_km`='$min_distance',`approx_waiting_charge`='$wait_charge',appox_waiting_minute='$waiting_min'  WHERE ID='$booking_id'");*/
			mysqli_query($this->con,"INSERT INTO tblbookinglogs(bookingid,`status`,message,`time`) VALUES('$booking_id',1,'Requesting car',NOW())");
			mysqli_query($this->con,"INSERT INTO `booking_stack`(`booking_id`,`lat`,`long`,`status`) VALUES('$booking_id','".$origin[0]."','".$origin[0]."','')") or die(mysqli_error($this->con)); 
			//mysqli_query($this->con,"INSERT INTO `tblbookingtracker` (`BookingID`,`DriverId`,`Latitutude`,`Logitude`,`Date_Time`,`CabStatus`) 
			//VALUES('$booking_id','121','".$origin[0]."','".$origin[0]."',NOW(),'1')");
	     $this->send_sms_new($booking_id);   
			//$retrurn =  array("Status" => "Success","per_km_charge"=>$per_km_charge, "ref"=>$booking_ref['generated'],"price"=>$totalbill,"pickupTime"=>$pickupTime,"Pickupdate"=>$newdate);
			return array("Status" => "Success","id"=>$user_id,"code"=>"001","ref"=>$booking_ref['generated'],"pickupTime"=>$pickupTime,"Pickupdate"=>$newdate,"price"=>$totalbill,"total_km"=>$totalkm);
		}else{
			return array("Status" => "Unsuccess");
		}

	}
	
	//////////// Function for Intercity Booking by Mohit Jain Ends Here/////////////////////
	
	//////////// Function to generate Reference Booking No by Mohit Jain Starts Here/////////////////////
	
	public function getReference(){
	//HW15096077
	$val="99999";
	$initial="HW";
	$dateYear=date('y');
	//$dateYear=27;
	if($dateYear>26){
		$dateYear=$dateYear-26;
		$dateYear=64+$dateYear;
		$dateYear=chr($dateYear);
		echo $dateYear="A".$dateYear;
	}
	else{
		$dateYear=64+$dateYear;
		$dateYear=chr($dateYear);
	}
	
	$dateMonth=date('m');
	//$dateMonth=12;
	$dateMonth=64+$dateMonth;
	$dateMonth=chr($dateMonth);
	$final1=str_pad($val,4,0,STR_PAD_LEFT);
	//die;
	
		if($val>=10000){
		$divide=floor($val/10000);
		$next=$val-($divide*10000);
		$aa=64+$divide;
		$neww=chr($aa);
		$final=str_pad($next,4,0,STR_PAD_LEFT);
		//SELECT neww,lpad(next,4,0), CONCAT(initial,DATE_FORMAT(NOW(), '%y') ,DATE_FORMAT(NOW(), '%m'),neww,lpad(next,4,0)) into generated;
		echo '<br>'.$generated=$initial.''.$dateYear.''.$dateMonth.''.$neww.''.$final;
		}else{
		//SELECT CONCAT(initial,DATE_FORMAT(NOW(), '%y') ,DATE_FORMAT(NOW(), '%m'),lpad(val,4,0)) into generated;
		echo '<br>'.$generated=$initial.''.$dateYear.''.$dateMonth.''.$final1;	
		}
	
	}
	
	//////////// Function to generate Reference Booking No by Mohit Jain Ends Here/////////////////////
	
	public function coupancode()
	{
		  //date_default_timezone_set("Asia/Kolkata");
		  $date = date('Y-m-d');
		  $time = date('H:i');
		  $day = date('l', strtotime($date));
	      $coupan_code = $_POST['val'];
		  $bookingType_id = $_POST['bookingType_id'];
		  $qry = "select tblpromotionmaster.promotionName as Promo_Name,tblpromotion.id as cou_id, tblpromotion.* from tblpromotion inner join tblpromotionmaster on tblpromotion.CouponType = tblpromotionmaster.id
 where tblpromotion.promotionName = '$coupan_code' and tblpromotion.BookingTypeId = '$bookingType_id' and ('$date' BETWEEN tblpromotion.ValidDateFrom AND tblpromotion.ValidDateTo) and ('$time' BETWEEN tblpromotion.ValidTimeFrom AND tblpromotion.ValidTimeTo) and ('$date' BETWEEN tblpromotionmaster.PromotionDateFrom AND tblpromotionmaster.PromotionDateTo)";
		  $result = mysqli_query($this->con, $qry);
		  $num_rows = mysqli_num_rows($result);
		  $info = mysqli_fetch_assoc($result);
		  
		  if($num_rows>0)
		  {
			$WeekDays = $info['WeekDays'];
			$val = explode(',',$WeekDays);
			if (in_array($day, $val)) 
			{
				$coupan_id = $info['cou_id'];
				$DiscountType = $info['DiscountType'];
				$Discount = $info['Discount'];
				$Promo_Name = $info['Promo_Name'];
				$MinimumBookingAmount = $info['MinimumBookingAmount'];
				$status="true";
			}
			else
			  {
				$status="false";
				$DiscountType = "";
				$Discount = "";
				$Promo_Name="";
				$coupan_id="";
				$MinimumBookingAmount="";
			  }
		  }
		  else
		  {
			  $status="false";
			  $DiscountType = "";
			  $Discount = "";
			  $Promo_Name="";
			  $coupan_id="";
			  $MinimumBookingAmount="";
		  }
		  return array("status" => $status,"Discount" => $Discount,"Promo_Name" => $Promo_Name,"coupan_id" => $coupan_id,"DiscountType" => $DiscountType,"MinimumBookingAmount" => $MinimumBookingAmount);
		  
	}
	
	public function weekTest()
	{
		$abc ='e';
		$WeekDays = 'a,b,c,d';
	    echo $val = explode(',',$WeekDays);
		if (in_array($abc, $val)) {
         echo "Got Irix";
         }
	}
	
	Public function findAirportaddress()
	{
		$AreaName =  str_replace('_',' ',$_REQUEST['AreaName']);
		$fixpoint =  str_replace('_',' ',$_REQUEST['fixpoint']);
		// tblairportaddress.Km, tblairportaddress.Fare
		$qry = "select t1.Address,t1.Latitude,t1.Longitude,t1.Km,t1.Fix_Point, t2.name as cityname, t3.state, t4.CountryName 
				from  tblairportaddress t1 LEFT JOIN tblcity t2 on  t1.cityId = t2.id LEFT JOIN tblstates t3 on t1.StateId = t3.id 
				LEFT JOIN tblcountry t4 on t3.country_code = t4.id where Fix_Point = '$fixpoint' and t1.Address Like '$AreaName%'";		
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
	
	public function abc()
	{
		// echo $distance;
			$totalbill = 50;
			$dist = 500; 
			$qry = "select (select distinct Km from tblairportaddress where Km<='$dist' order by Km desc limit 1) as MinKm,
					(select Fare from tblairportaddress where Km<='$dist' order by Km desc limit 1) as MinFare,
					(select distinct Km from tblairportaddress where Km>='$dist' order by Km Asc limit 1) as MaxKm,
					(select Fare from tblairportaddress where Km>='$dist' order by Km Asc limit 1) as MaxFare, 
					MAX(Km) as MaximumKm from tblairportaddress limit 1";
			$result = mysqli_query($this->con, $qry);
			$info = mysqli_fetch_assoc($result);
			if($info['MaximumKm']>$dist)
			{
			    $min_diff = $distance - $info['MinKm'];
				$max_diff =$info['MaxKm'] - $distance;
				$diff_distance = $info['MaxKm']-$info['MinKm'];
				if(($diff_distance/2) < $max_diff)
				{
					echo "min".$min_distance = $info['MinKm'];
					echo "fare".$totalbill = $info['MinFare'];
				}
				else
				{
					echo $min_distance = $info['MaxKm'];
					echo $totalbill = $info['MaxFare'];
				}
			}
			else
			{
				echo $totalbill;
			}
			
			die;
	}
	
///////////API Starts by Mohit Jain ////////////////////
	
	/////// API To get ReferalKey starts here ///////
	
	public function getUserRelatedData()
	{
	$token= $_REQUEST['token'];
	//$token="bb169118f4fe498d8b70d45df68a735e"; //die;
	$qry = "SELECT ID,amount,referralKey FROM tbluser where token='$token'"; 
	$result = mysqli_query($this->con, $qry);
	$info = mysqli_fetch_array($result);
	$cilentId	=	$info['ID'];

	$qry1 = "SELECT count(*) as total_trips FROM tblcabbooking where ClientID='$cilentId'"; 
	$result1 = mysqli_query($this->con, $qry1);
	$info1 = mysqli_fetch_array($result1);

	$num_rows = mysqli_num_rows($result);
	if($num_rows>0)
	$status="true";
	else
	$status="false";

	/*$record=array();
	$record['referralKey']=$info['referralKey'];
	$record['wallet_balance']=$info['amount'];
	$record['total_trips']=$info1['total_trips'];
	$record['points_earned']='0';*/

	$arra=array("status"=>"$status","referralKey"=>$info['referralKey'],"wallet_balance"=>$info['amount'],"total_trips"=>$info1['total_trips'],"points_earned"=>0);
	return $arra; 
	}
	
	/////// API To get ReferalKey Ends here ///////
	
	/////// API To Upload Security Image Starts here ///////
	
	public function insert_security_img_android(){
		$token = $_REQUEST["token"];
		$query="SELECT `ID` FROM tbluser WHERE `token`='$token'";
		$result =  mysqli_query($this->con,$query) or die(mysqli_error($this->con));
		$row = mysqli_fetch_assoc($result);		
		$id = $row['ID'];	
		if($id !=0 && $id!=''){
			if(isset($_FILES['image']) and $_FILES['image'] != ''){
			$ImgName=$id.'_'.basename($_FILES['image']["name"]);
			$TempName=$_FILES['image']['tmp_name'];			
			$response=$this->uploadUserSecurityIMGbyAndroid($ImgName,$TempName,$id,$token);
			}else{
				$response=array("status"=>false,"Message"=>"Image Not Received");
			}
		}else
		{
			$response=array("status"=>false,"Message"=>"Invalid Token");	
		}	

			return $response;
	}
	public function uploadUserSecurityIMGbyAndroid($ImgName,$TempName,$id,$token){
				$target_path = "public/userimage/$id/";
				$response = array();
				$target = $target_path . $ImgName;				
					try {
						if (!move_uploaded_file($TempName, $target)) {
							$response['error'] = true;
							$response['status'] = false;
							$response['message'] = 'Could not move the file!';
						}else{							
							$date=date('Y-m-d H:i:s');
			$query="INSERT INTO tbl_camera_security_image(user_id,image,created_date) VALUES('$id','$target','$date')";
		//return	array("query"=>$query);
							$result =  mysqli_query($this->con,$query) or die(mysqli_error($this->con));
							$response['error'] = false;
							$response['status'] = true;
							$response['message'] = 'File uploaded successfully!';							
							$response['file_path'] = $target;
						}
						
					} catch (Exception $e) {
						$response['error'] = true;
						$response['status'] = false;
						$response['message'] = $e->getMessage();
					}				
				 return $response;	
	}	
	
	/////// API To Upload Security Image Ends here ///////
	
   /*********Api to Authenticate user login Android App*************/ 
	public function randomPassword() {
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
			for ($i = 0; $i < 8; $i++) {
				$n = rand(0, $alphaLength);
				$pass[] = $alphabet[$n];
			}
		return implode($pass); //turn the array into a string
		}
		public function LoginUserByWeb(){		 
		$emailId=$_REQUEST["email"];
		$no_of_rows=mysqli_query($this->con,"SELECT * FROM tbluser WHERE LoginName='$emailId'");
		if(mysqli_num_rows($no_of_rows)==0){			
			$firstName=$_REQUEST["firstName"];
			//$_SESSION['FirstName']= $firstName;
			$lastName=$_REQUEST["lastName"];
			$gender=$_REQUEST["gender"];
			$password=$this->randomPassword();	
			$hashPass=md5($password);
			$token=md5(uniqid().$emailId);
			$LoginTime= date("Y-m-d H:i:s");
			$LoginDate= date("Y-m-d");
			mysqli_query($this->con,"INSERT INTO tbluser (`LoginName`,`UserType`,`Password`,`isVerified`,`loginStatus`,`is_active`,`loginTime`,`token`) 
			VALUES('$emailId','1','$hashPass','1','1','1','$LoginTime','$token')");
			$user_id=mysqli_insert_id($this->con);
			mysqli_query($this->con,"INSERT INTO tbluserinfo(`FirstName`,`UID`,`LastName`,`Email`) VALUES('$firstName','$user_id','$lastName','$emailId')"); 
			mysqli_query($this->con,"INSERT INTO tblloginlog(`userId`,`login_time`,`login_date`,`status`) 
			VALUES ('$user_id','$LoginTime','$LoginDate','$token')");
			$sql = "SELECT token,loginStatus as login_state,UserType as type_data,is_active as checklogin,b.ID, a.FirstName,a.City,a.MobNo,a.Email FROM tbluserinfo a INNER JOIN tbluser b ON a.UID = b.ID WHERE b.ID='$user_id'";
			$result = mysqli_query($this->con,$sql)or die(mysqli_error($this->con));
			$data= mysqli_fetch_assoc($result);
			/* $body="Hi $emailId,<br><br>You have successfully registered with <strong>Hello42.Com</strong><br><br>"
			. "Here is your default generated password and you can login with the same password in future or change your new password after login."
			. "<br><br>Best Regards,<br>Hello42 Cab Team";
			$body=str_replace('index.php','',$body);
			$from='info@hello42cab.com';
			$subject='New Registration';
			$this->send_mail($emailId,$from,$subject,$body); */
			return array("UserData" =>array($data),"status"=>"true");
		}else{
			$result_new=mysqli_fetch_array($no_of_rows);
			$user_id=$result_new['ID'];
			$token=md5(uniqid().$emailId);
			$LoginTime= date("Y-m-d H:i:s");
			$LoginDate= date("Y-m-d");
			mysqli_query($this->con,"UPDATE tbluser SET `loginStatus`='1',`is_active`='1',`loginTime`='$LoginTime',`token`='$token' WHERE `LoginName`='$emailId' AND `ID`='$user_id'");
			mysqli_query($this->con,"INSERT INTO tblloginlog(`userId`,`login_time`,`login_date`,`status`) 
			VALUES ('$user_id','$LoginTime','$LoginDate','$token')");
			$sql = "SELECT token,loginStatus as login_state,UserType as type_data,is_active as checklogin,b.ID, a.FirstName,a.City,a.MobNo,a.Email FROM tbluserinfo a INNER JOIN tbluser b ON a.UID = b.ID WHERE b.ID='$user_id'";
			$result = mysqli_query($this->con,$sql)or die(mysqli_error($this->con));
			$data= mysqli_fetch_assoc($result);
			$user_session = new Container('user');
			foreach($data as $u=>$v)$user_session->offsetSet($u,$v);
			return array("UserData" =>array($data),"status"=>"true");
		}
	}

	public function AuthenticateUserByAndroid($UserData){
		//print_r($UserData);exit;
		$emailId=$UserData["email"];
		$no_of_rows=mysqli_query($this->con,"SELECT * FROM tbluser WHERE LoginName='$emailId'");
		if(mysqli_num_rows($no_of_rows)==0){			
			$firstName=$UserData["firstName"];
			$lastName=$UserData["lastName"];
			$gender=$UserData["gender"];
			$password=$this->randomPassword();
			$hashPass=md5($password);
			$token=md5(uniqid().$emailId);
			$LoginTime= date("Y-m-d H:i:s");
			$LoginDate= date("Y-m-d");
			mysqli_query($this->con,"INSERT INTO tbluser (`LoginName`,`UserType`,`Password`,`isVerified`,`loginStatus`,`is_active`,`loginTime`,`token`) 
			VALUES('$emailId','1','$hashPass','1','1','1','$LoginTime','$token')");
			$user_id=mysqli_insert_id($this->con);
			mysqli_query($this->con,"INSERT INTO tbluserinfo(`FirstName`,`UID`,`LastName`,`Email`) VALUES('$firstName','$user_id','$lastName','$emailId')"); 
			mysqli_query($this->con,"INSERT INTO tblloginlog(`userId`,`login_time`,`login_date`,`status`) 
			VALUES ('$user_id','$LoginTime','$LoginDate','$token')");
			$sql = "SELECT token,loginStatus as login_state,UserType as type_data,is_active as checklogin,b.ID, a.FirstName,a.City,a.MobNo,a.Email FROM tbluserinfo a INNER JOIN tbluser b ON a.UID = b.ID WHERE b.ID='$user_id'";
			$result = mysqli_query($this->con,$sql)or die(mysqli_error($this->con));
			$data= mysqli_fetch_assoc($result);
			/* $body="Hi $emailId,<br><br>You have successfully registered with <strong>Hello42.Com</strong><br><br>"
			. "Here is your default generated password and you can login with the same password in future or change your new password after login."
			. "<br><br>Best Regards,<br>Hello42 Cab Team";
			$body=str_replace('index.php','',$body);
			$from='info@hello42cab.com';
			$subject='New Registration';
			$this->send_mail($emailId,$from,$subject,$body); */
			return array("UserData" =>array($data),"status"=>"true");
		}else{
			$result_new=mysqli_fetch_array($no_of_rows);
			$user_id=$result_new['ID'];
			$token=md5(uniqid().$emailId);
			$LoginTime= date("Y-m-d H:i:s");
			$LoginDate= date("Y-m-d");
			mysqli_query($this->con,"UPDATE tbluser SET `loginStatus`='1',`is_active`='1',`loginTime`='$LoginTime',`token`='$token' WHERE `LoginName`='$emailId' AND `ID`='$user_id'");
			mysqli_query($this->con,"INSERT INTO tblloginlog(`userId`,`login_time`,`login_date`,`status`) 
			VALUES ('$user_id','$LoginTime','$LoginDate','$token')");
			$sql = "SELECT token,loginStatus as login_state,UserType as type_data,is_active as checklogin,b.ID, a.FirstName,a.City,a.MobNo,a.Email FROM tbluserinfo a INNER JOIN tbluser b ON a.UID = b.ID WHERE b.ID='$user_id'";
			$result = mysqli_query($this->con,$sql)or die(mysqli_error($this->con));
			$data= mysqli_fetch_assoc($result);
			return array("UserData" =>array($data),"status"=>"true");
		}
	}
    public function AndroidLoginWithApi(){
		$LoginType=$_REQUEST["LoginType"];		
		if($LoginType=="1"){
				//$access_token=trim($_REQUEST["access_token"]);
			    //$email=trim($_REQUEST["email"]);
			$access_token="CAAHgjL0tt8sBAMlbeSo3r2aEj0VouWeZA2UKhjzEQlxYV7QZBVyCvfdZBlhAYl3GvEd3LZASVEVy0xgs2AEyIw1GssLLHRZCJ6LAMKvMABT5j4LZC4SGDVBqXRqelZBV62Qqx4N53HZCNp62ZCttBOAchUD2HjBZAZCd3R9c6ls9nEjeZAwA60ZABxZBhjGBpvfwN1ZApcitoWPjToaQhVUIdBO4EmMYMgNDiOhJrxFH9cXhuXBpAZDZD";
		$graph_url="https://graph.facebook.com/me?access_token=$access_token";	
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $graph_url);
				curl_setopt($ch, CURLOPT_HEADER, 0);				
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				$response = curl_exec($ch);				
				$response=json_decode($response,true);
				$data=array();
				$data["email"]=$response["email"];
				$data["firstName"]=$response["first_name"];
				$data["lastName"]=$response["last_name"];
				$data["gender"]=$response["gender"];
				$resDate=$this->AuthenticateUserByAndroid($data);				
				curl_close($ch);				
				return $resDate;
		}elseif($LoginType=="2"){
				$access_token=trim($_REQUEST["access_token"]);
			    $email=trim($_REQUEST["email"]);
			 //$access_token="ya29.HQLJb9Xh0Z6lBUmeDYXNtvRaF7uqr6cSJRTUQAe7z4KT42Fz3azofaSre86FYQoK12EH4qoGkcLSwtxfJQzPDfOX1AWrbmAZ5nnNMzuymm1mcRn5I7VupJPrLKsX";
		        $graph_url="https://www.googleapis.com/oauth2/v1/userinfo?access_token=$access_token";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $graph_url);
				curl_setopt($ch, CURLOPT_HEADER, 0);				
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				$response = curl_exec($ch);	
				$response=json_decode($response,true);
				$data=array();
				$data["email"]=$email;
				$data["firstName"]=$response["first_name"];
				$data["lastName"]=$response["last_name"];
				$data["gender"]=$response["gender"];
				$resDate=$this->AuthenticateUserByAndroid($data);				
				curl_close($ch);				
				return $resDate;
		}elseif($LoginType=="3"){ 
			$token = "3301223185-snF0fEqAzmp0LiwiwgQIQ3YVZEmV2kUIqLlIOjd";
			$token_secret = "il4fx3wGldQ0MS98QHP7N4IUs7Eqv4irSgehoXsiHsB7H";
			$consumer_key = "Q86oeNJ9T2Lal7rWs6rY3uMAs";
			$consumer_secret = "O8KVGlxxbXLlVJJtKYaW9RRMkiyaSIG63Zu2xTA5RTvnzox2hn";			 
			$host = 'api.twitter.com';
			$method = 'GET';
			$path = '/1.1/statuses/user_timeline.json'; // api call path
			$query = array( // query parameters include_entities=true&page=2
				'screen_name' => 'twitterapi',
				'count' => '5',
				'include_entities'=>'true',
				'page'=>2
			);
			$oauth = array(
				'oauth_consumer_key' => $consumer_key,
				'oauth_token' => $token,
				'oauth_nonce' => (string)mt_rand(), // a stronger nonce is recommended
				'oauth_timestamp' => time(),
				'oauth_signature_method' => 'HMAC-SHA1',
				'oauth_version' => '1.1'
			);
			$oauth = array_map("rawurlencode", $oauth); // must be encoded before sorting
			$query = array_map("rawurlencode", $query);
			$arr = array_merge($oauth, $query); // combine the values THEN sort
			asort($arr); // secondary sort (value)
			ksort($arr); // primary sort (key)
			// http_build_query automatically encodes, but our parameters
			// are already encoded, and must be by this point, so we undo
			// the encoding step
			$querystring = urldecode(http_build_query($arr, '', '&'));
			$url = "https://$host$path";
			// mash everything together for the text to hash
			$base_string = $method."&".rawurlencode($url)."&".rawurlencode($querystring);
			// same with the key
			$key = rawurlencode($consumer_secret)."&".rawurlencode($token_secret);
			// generate the hash
			$signature = rawurlencode(base64_encode(hash_hmac('sha1', $base_string, $key, true)));
			// this time we're using a normal GET query, and we're only encoding the query params
			// (without the oauth params)
			$url .= "?".http_build_query($query);
			$url=str_replace("&amp;","&",$url); //Patch by @Frewuill
			$oauth['oauth_signature'] = $signature; // don't want to abandon all that work!
			ksort($oauth); // probably not necessary, but twitter's demo does it
			// also not necessary, but twitter's demo does this too
			function add_quotes($str) { return '"'.$str.'"'; }
			$oauth = array_map("add_quotes", $oauth);
			// this is the full value of the Authorization line
			$auth = "OAuth " . urldecode(http_build_query($oauth, '', ', '));
			// if you're doing post, you need to skip the GET building above
			// and instead supply query parameters to CURLOPT_POSTFIELDS
			$options = array( CURLOPT_HTTPHEADER => array("Authorization: $auth"),
							  //CURLOPT_POSTFIELDS => $postfields,
							  CURLOPT_HEADER => false,
							  CURLOPT_URL => $url,
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_SSL_VERIFYPEER => false);
			// do our business
			$feed = curl_init();
			curl_setopt_array($feed, $options);
			$json = curl_exec($feed);
			print_r($json);exit;
			curl_close($feed);
			$twitter_data = json_decode($json);
			foreach ($twitter_data as &$value) {
			   $tweetout .= preg_replace("/(http:\/\/|(www\.))(([^\s<]{4,68})[^\s<]*)/", '<a href="http://$2$3" target="_blank">$1$2$4</a>', $value->text);
			   $tweetout = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $tweetout);
			   $tweetout = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $tweetout);
			}
			echo $tweetout;	exit;		
		}elseif($LoginType=="4"){
			$access_token=trim($_REQUEST["access_token"]);
			//$email=trim($_REQUEST["email"]);		    
			//$access_token="AQU_SARmMeNa0FaOiM3StYszww3khCtUm9hnnyJxp91NhNymFtc2e4H9gDFzxh9upu19rzITzh46WQaEtg71Y24nZqVvKJ2zIwomZfOjL4dbMlaz8JM_7vh5i-zrx5EYdVPRStovI4uq2_USWUZZiKGwPY4HqdUR7EP_la0kidOPCWGQOJ4";
		      $graph_url="https://api.linkedin.com/v1/people/~:(id,emailAddress,firstName,lastName,pictureUrl,dateOfBirth,location)?oauth2_access_token=$access_token&format=json";
		     	$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $graph_url);
				curl_setopt($ch, CURLOPT_HEADER, 0);				
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				$response = curl_exec($ch);
				//return $response;
				$response=json_decode($response,true);
				$data=array();				
				$data["email"]=$response["emailAddress"];
				$data["firstName"]=$response["firstName"];
				$data["lastName"]=$response["lastName"];
				$data["gender"]=$response["gender"];
				$resDate=$this->AuthenticateUserByAndroid($data);				
				curl_close($ch);				
				return $resDate;				
		}			
	}
   /////// API to authenticate user Ends here ///////	 
	/*public function tw(){ 			
			$cfg = [
				'twitter_oauth_access_token'  => 'snF0fEqAzmp0LiwiwgQIQ3YVZEmV2kUIqLlIOjd',
				'twitter_access_token_secret' => 'il4fx3wGldQ0MS98QHP7N4IUs7Eqv4irSgehoXsiHsB7H',
				'twitter_consumer_key'        => 'Q86oeNJ9T2Lal7rWs6rY3uMAs',
				'twitter_consumer_secret'     => 'O8KVGlxxbXLlVJJtKYaW9RRMkiyaSIG63Zu2xTA5RTvnzox2hn',
			];			
			function twitter_oauth($url){
				global $cfg;
				$oauth = [
					'oauth_consumer_key'     => $cfg['twitter_consumer_key'],
					'oauth_nonce'            => time(),
					'oauth_signature_method' => 'HMAC-SHA1',
					'oauth_timestamp'        => time(),
					'oauth_token'            => $cfg['twitter_oauth_access_token'],
					'oauth_version'          => '1.1'
				];
				$data = [];
				$header = [];
				foreach($oauth as $k => $v){
					$v = rawurlencode($v);
					$data[] = $k.'='.$v;
					$header[] = $k.'="'.$v.'"';
				}
				$data = 'GET&'.rawurlencode($url).'&'.rawurlencode(implode('&', $data));
				$key = rawurlencode($cfg['twitter_consumer_secret']).'&'.rawurlencode($cfg['twitter_access_token_secret']);
				$signature = base64_encode(hash_hmac('sha1', $data, $key, true));
				$header = 'Authorization: OAuth '.implode(', ', $header).', oauth_signature="'.rawurlencode($signature).'"';
				$options = [
					CURLOPT_HTTPHEADER     => [$header, 'Expect:'],
					CURLOPT_HEADER         => false,
					CURLOPT_URL            => $url,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false
				];
				$request = curl_init();
				curl_setopt_array($request, $options);
				$response = curl_exec($request);
				curl_close($request);
				return $response;
			}			
			function utf8toHTML($utf8, $encode = false){
				$html = '';
				for($i = 0; $i < strlen($utf8); $i++){
					$ascii = ord($utf8[$i]);
					// one-byte character
					if($ascii < 128){
						$html .= ($encode) ? htmlentities($utf8[$i]) : $utf8[$i];
					}					
					else if($ascii < 224){
						$html .= htmlentities(substr($utf8, $i, 2), ENT_QUOTES, 'UTF-8');
						$i++;
					}					
					else if($ascii < 240){
						$html .= '&#'.((15&$ascii)*4096+(63&ord($utf8[$i+1]))*64+(63&ord($utf8[$i+2]))).';';
						$i += 2;
					}
					else if($ascii < 248){
						$html .= '&#'.((15&$ascii)*262144+(63&ord($utf8[$i+1]))*4096+(63&ord($utf8[$i+2]))*64+(63&ord($utf8[$i+3]))).';';
						$i += 3;
					}
				}
				return $html;
			}
			$api = 'https://api.twitter.com/1.1/';			
			$json = twitter_oauth($api.'statuses/user_timeline.json');			
			$json = json_decode($json, true);
			echo '<pre>'.print_r($json, true).'</pre>';exit;
	} */
	//************************************************************
	
		public function setUserLocation1(){
		$status="False";
		//$data= $_REQUEST['locData'];
		$data='[{"userId":"1470","latitude":"28.6518726","longitude":"77.1943858","current_time":"2015-11-05 17:01:04"},{"userId":"1470","latitude":"28.6518683","longitude":"77.1943745","current_time":"2015-11-05 17:02:04"},{"userId":"1470","latitude":"28.6518757","longitude":"77.1943324","current_time":"2015-11-05 17:03:04"},{"userId":"1470","latitude":28.6518617,"longitude":77.1943737,"current_time":"2015-11-05 17:04:04"}]';
		//echo $data; die;
		$data_new=json_decode($data,true);	
		//echo"<pre>";print_r($data_new); 
		//echo count($data_new['upload']);die;
		$lengthTotal = count($data_new);
		//echo $lengthTotal;
		
		if($lengthTotal >0 && $lengthTotal == 1){
			$user_id=$data_new['upload'][0][userId];
			$sql="select * from tbluserlocation where user_id ='$user_id' order by id desc limit 1";
			$qry = mysqli_query($this->con,$sql);
			$data = mysqli_fetch_assoc($qry);
			$num_rows = mysqli_num_rows($qry);
			$distance = 0;	
			$lat	=	$data_new['upload'][0]['latitude'];
			$long	=	$data_new['upload'][0]['longitude'];
			$time	=	$data_new['upload'][0]['current_time'];
			if($num_rows>0){
				$distance = $this->getDistanceMohit($lat, $long, $data['lat'], $data['longi'], 'K');	
			}
			
			$queryaa="INSERT INTO tbluserlocation(`user_id`,`lat`,`longi`,`datetime`,`distance`) VALUES('$user_id','$lat','$long','$time','$distance')"; 
			$affected_rows=mysqli_query($this->con,$queryaa);
			if($affected_rows>0){
			$status="Success";
			}else{
			$status="False";
			}
			
		}else if($lengthTotal >0){	
		$i=0;
		 foreach($data_new as $data_newsss)
		{		
			$Pre_lat=$data_newsss['latitude'];
			$Pre_long=$data_newsss['longitude'];
			
			if($i==0){
			$user_id=$data_newsss['userId'];
			$lat=$data_newsss['latitude'];
			$long=$data_newsss['longitude'];
			$time=$data_newsss['current_time'];
			
			$Pre_lat=$data_newsss['latitude'];
			$Pre_long=$data_newsss['longitude'];
			
			$sql="select * from tbluserlocation where user_id ='$user_id' order by id desc limit 1";
			$qry = mysqli_query($this->con,$sql);
			$data = mysqli_fetch_assoc($qry);
			$num_rows = mysqli_num_rows($qry);
			$distance = 0;	
			
			if($num_rows>0){
				$distance = $this->getDistanceMohit($lat, $long, $data['lat'], $data['longi'], 'K');	
			}
			$queryaa="INSERT INTO tbluserlocation(`user_id`,`lat`,`longi`,`datetime`,`distance`) VALUES('$user_id','$lat','$long','$time','$distance')"; 
			$affected_rows=mysqli_query($this->con,$queryaa);
				
			}else{
				
			$user_id=$data_newsss['userId'];
			$lat=$data_newsss['latitude'];
			$long=$data_newsss['longitude'];
			$time=$data_newsss['current_time'];
			$distance = $this->getDistanceMohit($Pre_lat, $Pre_long, $lat, $long, 'K');			
			
			$Pre_lat=$data_newsss['latitude'];
			$Pre_long=$data_newsss['longitude'];
			
			$queryaa="INSERT INTO tbluserlocation(`user_id`,`lat`,`longi`,`datetime`,`distance`) 
			VALUES('$user_id','$lat','$long','$time','$distance')"; 
			$affected_rows=mysqli_query($this->con,$queryaa);
			if($affected_rows>0){
			$status="Success";
			}else{
			$status="False";
			}	
			}
		$i++;
		} 
			
			
		}
		mysqli_query($this->con,"UPDATE tbluser SET Latitude='$Pre_lat',Longtitude1='$Pre_long' WHERE id='$user_id'");
		return array("Status"=>$status);
	}
	
	public function getDistanceMohit($lat1, $lon1, $lat2, $lon2, $unit) {
		$a=0;
		if($lat1!='' && $lon1!='' && $lat2!='' && $lon2!=''){
		/* $lat1=28.651758;
		$lon1=77.1945474;
		$lat2='';
		$lon2='';
		$unit='K'; */
		$distance=0;
		/*$data= file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$lat1.','.$lon1."&destination=".$lat2.','.$lon2."&key=AIzaSyCOltTlLALVdO79OezFhXkmPrfQSImo584"); 
			$enc=json_decode($data);			
			if($enc->status == 'OK'){
				$enc2=$enc->routes[0];
				echo $distance=round((($enc2->legs[0]->distance->value)/1000),1);
			}
			die;
		return $distance;*/
		
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);
		if ($unit == "K") {
			$a = ($miles * 1.609344);
		} else if ($unit == "N") {
			$a=($miles * 0.8684);
		}
		}
		else {
			 $a;
		}
		return $a;
	}
	
	
	
	//// API to get all alternate no list for user in Tracking Starts here ////	
	
	public function FetchAllUserAltnoList(){
		$token = $_REQUEST['token'];
		//$token = 'd0ae17a2ec612535a506b6954fff33d4';
		$qry = "SELECT ID from tbluser WHERE token='$token'";
		$result = mysqli_query($this->con, $qry);
		$info = mysqli_fetch_assoc($result);
		$user_id=$info['ID']; 
		
		$qry1 = "SELECT AlternateContactNo,status from tbluserinfo WHERE UID='$user_id'";
		$result1 = mysqli_query($this->con, $qry1);
		$info1 = mysqli_fetch_assoc($result1);
		
		$sql1="SELECT * from tbl_user_altno_tracking WHERE user_id='$user_id'";
		$userDT = mysqli_query($this->con,$sql1) or die(mysqli_error($this->con));
		if(mysqli_num_rows($userDT) > 0){	
			$record=array();
			$i=0;
			while($suerData = mysqli_fetch_assoc($userDT)){
			$data["id"]		=	$suerData['id'];
			$data["alt_no"]	=	$suerData['alt_no'];
			$data["is_active"]	=	$suerData['status'];
			$record[]=$data;
			}
			
          return array("status"=>true,"Data"=>$record,"alt_no"=>$info1['AlternateContactNo'],"is_active"=>$info1['status']);			
		}else{
			return array("status"=>false,"message"=>"No Alternate No added by this user");
		}		
	  }
	  
	//// API to get all alternate no list for user in Tracking Ends here ////	
	
	//// API to add Alternate no for user in Tracking Starts here ////
	
	public function AddAlternateNumber(){
		$token = $_REQUEST['token'];
		//$token = 'd0ae17a2ec612535a506b6954fff33d4';
		$alt_no = $_REQUEST['alt_no'];
		$qry = "SELECT ID from tbluser WHERE token='$token'";
		$result = mysqli_query($this->con, $qry);
		$info = mysqli_fetch_assoc($result);
		$user_id=$info['ID']; 
		
		$qry1="INSERT INTO `tbl_user_altno_tracking`(user_id,alt_no,status,created_date) VALUES ('$user_id','$alt_no','false',Now())";
		mysqli_query($this->con,$qry1);
		
		if(mysqli_affected_rows($this->con) > 0){
		$sql1="SELECT * from tbl_user_altno_tracking WHERE user_id='$user_id'";
		$userDT = mysqli_query($this->con,$sql1) or die(mysqli_error($this->con));
		$record=array();
		while($suerData = mysqli_fetch_assoc($userDT)){
		$data["id"]		=	$suerData['id'];
		$data["alt_no"]	=	$suerData['alt_no'];
		$data["is_active"]	=	$suerData['status'];
		$record[]=$data;
		}
		return array('status'=>true, 'message'=>'Alternate No added Successfully',"Data"=>$record);
		}else{
		return array("status"=>false,"message"=>"No Duplicate Entry Allowed");
		}

		
	 }
	
	//// API to add Alternate no for user in Tracking Ends here ////
	
	//// API to Edit Alternate no for user in Tracking Starts here ////
	
	public function EditAlternateNumber(){
		$token = $_REQUEST['token'];
		$id = trim($_REQUEST['id']);
		$alt_no = $_REQUEST['alt_no'];
		
		$qry = "SELECT ID from tbluser WHERE token='$token'";
		$result = mysqli_query($this->con, $qry);
		$info = mysqli_fetch_assoc($result);
		$user_id=$info['ID']; 
		
		$sql1="UPDATE `tbl_user_altno_tracking` set alt_no='$alt_no' WHERE user_id='$user_id' and id='$id'";
		mysqli_query($this->con,$sql1);
		if(mysqli_affected_rows($this->con)>0){	
		$sql2="SELECT * from tbl_user_altno_tracking WHERE user_id='$user_id'";
		$userDT = mysqli_query($this->con,$sql2) or die(mysqli_error($this->con));
		$record=array();
		while($suerData = mysqli_fetch_assoc($userDT)){
		$data["id"]		=	$suerData['id'];
		$data["alt_no"]	=	$suerData['alt_no'];
		$data["is_active"]	=	$suerData['status'];
		$record[]=$data;
		}
		$message="Alternate No Updated Successfully";
		return array("status"=>true,"message"=>$message,"Data"=>$record);			
		}else{
		$message="No Duplicate Entry Allowed";
		return array("status"=>false,"message"=>$message,"id"=>$id);
		}		
	  }
	 
	//// API to Edit Alternate no for user in Tracking Ends here ////
	
	//// API to Delete Alternate no for user in Tracking Starts here ////
	
	public function DeleteAlternateNumber(){
		$token = $_REQUEST['token'];
		$id = trim($_REQUEST['id']);

		$qry = "SELECT ID from tbluser WHERE token='$token'";
		$result = mysqli_query($this->con, $qry);
		$info = mysqli_fetch_assoc($result);
		$user_id=$info['ID']; 
		
		$sql1="DELETE FROM `tbl_user_altno_tracking` WHERE id='$id'";		
		mysqli_query($this->con,$sql1);
		if(mysqli_affected_rows($this->con)>0){	
		$sql2="SELECT * from tbl_user_altno_tracking WHERE user_id='$user_id'";
		$userDT = mysqli_query($this->con,$sql2) or die(mysqli_error($this->con));
		$record=array();
		while($suerData = mysqli_fetch_assoc($userDT)){
		$data["id"]		=	$suerData['id'];
		$data["alt_no"]	=	$suerData['alt_no'];
		$data["is_active"]	=	$suerData['status'];
		$record[]=$data;
		}
			$message="Alternate No Deleted Successfully";
            return array("status"=>true,"message"=>$message,"Data"=>$record);			
		}else{
			$message="Not Deleted ! Invalid Alternate No";
			return array("status"=>false,"message"=>$message);
		}		
	  }
	  
	//// API to Delete Alternate no for user in Tracking Ends here ////
	
	//// API to Allow / Deny for user in Tracking Starts here ////
	  
	  public function allowSelfuserTracking(){
		$token = $_REQUEST['token'];
		$id = trim($_REQUEST['id']);
		$is_active = trim($_REQUEST['isChecked']);
		
		$qry = "SELECT ID from tbluser WHERE token='$token'";
		$result = mysqli_query($this->con, $qry);
		$info = mysqli_fetch_assoc($result);
		$user_id=$info['ID']; 

		$sql="UPDATE tbl_user_altno_tracking SET status='$is_active' where user_id='$user_id' and id='$id'";
		mysqli_query($this->con,$sql);

		if(mysqli_affected_rows($this->con)>0){	
		$sql2="SELECT * from tbl_user_altno_tracking WHERE user_id='$user_id'";
		$userDT = mysqli_query($this->con,$sql2) or die(mysqli_error($this->con));
		$record=array();
		while($suerData = mysqli_fetch_assoc($userDT)){
		$data["id"]		=	$suerData['id'];
		$data["alt_no"]	=	$suerData['alt_no'];
		$data["is_active"]	=	$suerData['status'];
		$record[]=$data;
		}
			$message="Status Update Successfully";
            return array("status"=>true,"message"=>$message,"Data"=>$record);			
		}else{
			$message="Invalid Alternate No";
			return array("status"=>false,"message"=>$message);
		}
	}
	
	//// API to Allow / Deny for user in Tracking Ends here ////
	
	//// API to Allow / Deny for user in Tracking Starts here ////
	  
	  public function allowSelfuserTrackingSpecificAltno(){
		$token = $_REQUEST['token'];
		$is_active = trim($_REQUEST['isChecked']);
		
		$qry = "SELECT ID from tbluser WHERE token='$token'";
		$result = mysqli_query($this->con, $qry);
		$info = mysqli_fetch_assoc($result);
		$user_id=$info['ID']; 

		$sql="UPDATE tbluserinfo SET status='$is_active' where UID='$user_id'";
		mysqli_query($this->con,$sql);

		if(mysqli_affected_rows($this->con)>0){	
			$message="Status Update Successfully";
            return array("status"=>true,"message"=>$message);			
		}else{
			$message="Invalid Alternate No";
			return array("status"=>false,"message"=>$message);
		}
	}
	
	
	public function fetchFareStructure($masterPkg, $subPkg, $cabType, $hrs)
	{
			echo $sql = "select * from  tbllocalpackage where masterPKG_ID=$masterPkg and Sub_Package_Id=$subPkg and cabType=$cabType and Hrs=$hrs ";
		
		
	}
	//// API to Allow / Deny for user in Tracking Ends here ////
	
	//// API to get Tracking End User List Starts here ////	
	
	public function FetchAllEndUserTrackingList(){
		$token = $_REQUEST['token'];
		//$token = '1372bbf7d4ff4e54a19e870b91937e17';
		$qry = "SELECT ID from tbluser WHERE token='$token'";
		$result = mysqli_query($this->con, $qry);
		$info = mysqli_fetch_assoc($result);
		$user_id=$info['ID'];
		
		$qry1 = "SELECT AlternateContactNo from tbluserinfo WHERE UID='$user_id'";
		$result1 = mysqli_query($this->con, $qry1);
		$info1 = mysqli_fetch_assoc($result1);
		$alt_no=$info1['AlternateContactNo'];
		
		$qry2 = "SELECT a.UID,a.MobNo,CONCAT(a.FirstName,' ',a.LastName) as name,a.status from tbluserinfo as a JOIN tbluser as b on b.ID = a.UID WHERE a.MobNo='$alt_no' and a.status='true' and b.isVerified='1'"; //die;
		$result2 = mysqli_query($this->con, $qry2); 
		
		$qry3 = "SELECT a.alt_no,a.status,b.UID,CONCAT(b.FirstName,' ',b.LastName) as name from tbl_user_altno_tracking as a JOIN tbluserinfo as b on a.alt_no=b.MobNo JOIN tbluser as c on b.MobNo=c.UserNo WHERE a.user_id='$user_id' and  a.status='true' and c.isVerified='1'"; //die;
		$result3 = mysqli_query($this->con, $qry3); 
		
		$record=array();
		if(mysqli_num_rows($result2) > 0){	
			
			while($info2 = mysqli_fetch_assoc($result2)){
			$data["id"]		=	$info2['UID'];
			$data["alt_no"]		=	$info2['MobNo'];
			$data["name"]		=	$info2['name'];
			$data["is_active"]	=	$info2['status'];		
			$record[]=$data;
			}
			if(mysqli_num_rows($result3) > 0){	
			while($info3 = mysqli_fetch_assoc($result3)){
			$data["id"]		=	$info3['UID'];
			$data["alt_no"]		=	$info3['alt_no'];
			$data["name"]		=	$info3['name'];
			$data["is_active"]	=	$info3['status'];		
			$record[]=$data;
			}
			        		
			}
			return array("status"=>true,"Data"=>$record);			
		}else{
			if(mysqli_num_rows($result3) > 0){	
			while($info3 = mysqli_fetch_assoc($result3)){
			$data["id"]		=	$info3['UID'];
			$data["alt_no"]		=	$info3['alt_no'];
			$data["name"]		=	$info3['name'];
			$data["is_active"]	=	$info3['status'];		
			$record[]=$data;
			}
			 return array("status"=>true,"Data"=>$record);		    		
			}else{
				return array("status"=>false,"message"=>"No Data Available");
			}
			
		}
		
	  }
	  
	//// API to get Tracking End User List Ends here ////	
	
	public function Test(){
		//return array('aa'=>'Mohit');
		$s = 'Naveen123ku';
		echo $result = preg_replace("/[^a-zA-Z]+/", "", $s);
		//echo $result = preg_replace("/[^0-9]+/", "", $s);
		
	}
	public function checkNull(){
		$x="";
		if(!$x){
			echo"NO";exit;
		}else{
			echo"Yes";exit;
		}
	}
	
	public function DriverPaymentUpload(){
				
	/* if (!empty($_FILES["uploadedimage"]["name"])) {
	$file_name=$_FILES["uploadedimage"]["name"];
	$temp_name=$_FILES["uploadedimage"]["tmp_name"];
	//$imgtype=$_FILES["uploadedimage"]["type"];
	//$ext= GetImageExtension($imgtype);
	$imagename=date("d-m-Y")."-".time().$ext;
	$target_path = "images/".$imagename;
	if(move_uploaded_file($temp_name, $target_path)) {
	$query_upload="INSERT into 'images_tbl' ('images_path','submission_date') VALUES
	('".$target_path."','".date("Y-m-d")."')";
	mysql_query($query_upload) or die("error in $query_upload == ----> ".mysql_error()); 
	}else{
	exit("Error While uploading image on the server");
	}
	} */
	
		
		$deposit_date =date('Y-m-d',strtotime($_REQUEST['deposit_date']));
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
		
		$qry1="INSERT INTO `tbldriverpayment`(driver_id,deposit_date,transaction_mode,fileupload,trans,chequeno,partner_bank,amount,deposit_bank,deposited_branch,remark,status) VALUES ('$id_tbluser','$deposit_date','$PaymentMode','$target_path','$trans','$chequeno','$partner_bank','$amount','$deposit_bank','$deposited_branch','$remark','0')";
		$result = mysqli_query($this->con,$qry1);
		
		if($result>0){
			$status="true";
			}else{
			$status="False";
			}
		return array("status" => $status);
		
	}
	
	
	
	//////////// Cab Billing New Service Starts By Mohit Jain //////
	
	public function cabBillingComplete(){
		//echo "Mohit"; die;

		/*$distance = '0.06';
		$BookingId_i = '8622';
		$BookingId = $BookingId_i;
		$strtTime = '2016-05-02 13:06:30';
		$strtTimePeak=explode(" ",$strtTime);
		$endTime = '2016-05-02 13:07:51';
		$address= '103, Desh Bandhu Gupta Rd, New Delhi, Block 57, Karol Bagh,India';
		$lat='28.6520483';
		$long='77.19434';
		$delay_time='00:00:00';
		//$delay_time=$_REQUEST['delay_time'];
		$delay_time=explode(':',$delay_time);
		$delay_time0=round($delay_time[0]*60);
		$delay_time1=round($delay_time[2]/60);
		$delay_time=$delay_time0+$delay_time1+$delay_time[1];
		
		
		$current_time='2016-05-02 01:07:51';
		$total_amount='237';
		$total_time='00:01:20';
		$isMatching ='true';
		$pre_Waiting_time	='00:00:00';
		$pre_Waiting_time=explode(':',$pre_Waiting_time);
		$pre_Waiting_time0=round($pre_Waiting_time[0]*60);
		$pre_Waiting_time1=round($pre_Waiting_time[2]/60);
		$preWaitingtime=$pre_Waiting_time0+$pre_Waiting_time1+$pre_Waiting_time[1];*/
		
		//// New Parameters should be added //
		
		$distance = $_REQUEST['distance'];		
		$BookingId_i = $_REQUEST['bookingId'];
		$BookingId = $BookingId_i;
		$strtTime = $_REQUEST['strtTime'];
		$strtTimePeak=explode(" ",$strtTime);
		$endTime = $_REQUEST['endTime'];
		$address=$_REQUEST['address'];
		$lat=$_REQUEST['lat'];
		$long=$_REQUEST['lon'];
		$delay_time=$_REQUEST['delay_time'];
		$delay_time=explode(':',$delay_time);
		$delay_time0=round($delay_time[0]*60);
		$delay_time1=round($delay_time[2]/60);
		$delay_time=$delay_time0+$delay_time1+$delay_time[1];		
		
		$current_time=$_REQUEST['currentTime'];
		$total_amount=$_REQUEST['totalAmount']; //exit();
		$total_time=$_REQUEST['totalTime'];
		$isMatching=$_REQUEST['isMatching']; 
		$pre_Waiting_time=$_REQUEST['pre_Waiting_time'];
		$pre_Waiting_time=explode(':',$pre_Waiting_time);
		$pre_Waiting_time0=round($pre_Waiting_time[0]*60);
		$pre_Waiting_time1=round($pre_Waiting_time[2]/60);
		$preWaitingtime=$pre_Waiting_time0+$pre_Waiting_time1+$pre_Waiting_time[1];

$diff = abs(strtotime($endTime) - strtotime($strtTime));
$years = floor($diff / (365*60*60*24)); 
$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
$total_trip_hours = floor(($diff)/ (60*60));
//$minuts = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $total_trip_hours*60*60)/ 60);
$minuts=$diff/60;
$total_trip_minutes=round($minuts);
$total_trip_minutes2=round($minuts);
if($total_trip_minutes2>$minuts)
{
   $total_trip_minutes2=$total_trip_minutes2;
}else
{
	 $total_trip_minutes2=$total_trip_minutes2+1;
}
if($minuts>0){
	$total_trip_hours=$total_trip_hours+1;	
}
	//echo $total_trip_minutes2; die;	
	/*$sql_test="INSERT INTO `tbl_test` (`distance`, `bookingId`, `strtTime`, `strtTimePeak`, `endTime`, `address`, `lat`, `long`, `delay_time`, `current_time`, `total_amount`, `total_time`, `isMatching`,`pre_Waiting_time`) VALUES ('$distance', '$BookingId', '$strtTime', '$strtTimePeak[1]', '$endTime', '$address', '$lat', '$long', '$delay_time', '$current_time', '$total_amount', '$total_time', '$isMatching','$preWaiting_time')";
	mysqli_query($this->con,$sql_test) or die(mysqli_error($this->con));*/
		
	file_put_contents("amount1.txt", $distance);
	$sql2="SELECT BookingType,CarType,pickup,local_subpackage,PickUpArea,DropArea FROM tblcabbooking WHERE ID='$BookingId_i'"; 
	$res =mysqli_query($this->con, $sql2) or die(mysqli_error($this->con));
	$data1 = $res->fetch_array();
	$BookingType=$data1['BookingType']; 
	$CarType=$data1['CarType'];
	$pickupid=$data1['pickup'];
	//$local_subpackage=$data1['local_subpackage'];
	
	$IsMatchedCabType=$this->IsCabtypeMatched($pickupid,$BookingId_i);
	//echo $IsMatchedCabType; die;
	
	if($IsMatchedCabType){
	$sql3="CALL wp_cabBookingCompleteBillNew('$BookingId_i','$BookingType')";
	}else{
	$sql3="CALL wp_cabBookingCompleteBillNotMatched('$BookingId_i','$BookingType')";
	}
	//echo $sql3; die;
	//$sql3="CALL wp_cabBookingCompleteBillNew('$BookingId_i','$BookingType')"; 
	$result = mysqli_query($this->con, $sql3) or die(mysqli_error($this->con));
	$data = mysqli_fetch_assoc($result);
	//echo "<pre>";print_r($data); //die;
	$stateId	=	$data['state'];
	
	mysqli_free_result($result);   
	mysqli_next_result($this->con);
	
	$sql1 = "select Sub_Package_Id from tblmasterpackage WHERE Package_Id='$BookingType' AND state_id='$stateId'";
	$row1 = mysqli_query($this->con,$sql1) or die(mysqli_error($this->con));
	$res1 = mysqli_fetch_array($row1);
	$Sub_Package_Id	=	$res1['Sub_Package_Id'];
	
	//// Code to Fetch Data for Local(101) && Point to Point(102) Starts Here /////
	
	if($BookingType==101 || $BookingType==102){
	$cablocalfor	=	$data1['local_subpackage'];
	if($cablocalfor!=""){
	$sql = "SELECT * FROM `tbllocalpackage` WHERE Package='$cablocalfor' AND masterPKG_ID='$BookingType' AND Sub_Package_Id='$Sub_Package_Id' AND City_ID='$stateId' AND cabType='$CarType'"; //die;
	$row = mysqli_query($this->con,$sql);		
	$date = mysqli_fetch_object($row);
	$num_rows = mysqli_num_rows($row);
		//echo $Sub_Package_Id;	
	if($num_rows > 0){
		$cabfor	=	$date->Hrs;
		$Min_Distance = $date->Km;
		$MinimumChrage=$date->Price;		
	}}else{	
		if($Sub_Package_Id == 1){
		$Min_Distance = $data['Min_Distance'];
		$MinimumChrage = $data['MinimumCharge'];
		}else if($Sub_Package_Id == 2){
		$cabfor	=	$data['CabFor'];
		$MinimumChrage = $data['minimum_hourly_Charge'];
		}else if($Sub_Package_Id == 3){
		$cabfor	=	$data['ignore_first_hrs_dh'];
		$Min_Distance = $data['minimum_distance_dh'];
		$MinimumChrage = $data['minimum_fare_dh'];
		}else if($Sub_Package_Id == 4){	
		$Min_Distance = $data['minimum_distance_dw'];
		$MinimumChrage = $data['minimum_fare_dw'];
		}
	}
	
		
	
	}
	//echo $MinimumChrage; die;
	//// Code to Fetch Data for Local(101) && Point to Point(102) Ends Here /////
	
	//// Code to Fetch Data for Airport(103) Starts Here /////
	if($BookingType==103){		
	$PickAreaName		=	$data1['PickUpArea']; 
	$DropAreaName		=	$data1['DropArea'];
	$sqlAir	="SELECT * FROM `tblairportaddress` WHERE `Fix_Point`='$PickAreaName' LIMIT 1"; 
	$sqlAir	=mysqli_query($this->con,$sqlAir);	
	$row=mysqli_fetch_assoc($sqlAir);
	$num_rows1 = mysqli_num_rows($sqlAir);
	if($num_rows1>0){
	$fixpoint		=	$PickAreaName;
	}
	else{
	 $fixpoint		=	$DropAreaName;
	}	
	
	//$sql = "SELECT * FROM `tblairportaddress` WHERE Address='$address' AND Fix_Point='$fixpoint' AND masterPKG_ID='$BookingType' AND Sub_Package_Id='$Sub_Package_Id' AND cityId='$stateId' AND cabType='$CarType'"; //die;
	$sql = "SELECT * FROM `tblairportaddress` WHERE '$address' LIKE concat('%',`Address`,'%') AND '$fixpoint' like concat('%',`transfer_type`,'%') AND masterPKG_ID='$BookingType' AND Sub_Package_Id='$Sub_Package_Id' AND cityId='$stateId' AND cabType='$CarType' LIMIT 1"; 
	$row1 = mysqli_query($this->con,$sql);		
	$date = mysqli_fetch_object($row1);
	$num_rows1 = mysqli_num_rows($row1);
	
	/////////////////////////////
	
if($num_rows1 == 0){
if($Sub_Package_Id == 1){
$Min_Distance = $data['Min_Distance'];
$MinimumChrage = $data['MinimumCharge'];
}else if($Sub_Package_Id == 2){
$cabfor	=	$data['CabFor'];
$MinimumChrage = $data['minimum_hourly_Charge'];
}else if($Sub_Package_Id == 3){
$cabfor	=	$data['ignore_first_hrs_dh'];
$Min_Distance = $data['minimum_distance_dh'];
$MinimumChrage = $data['minimum_fare_dh'];
}else if($Sub_Package_Id == 4){	
$Min_Distance = $data['minimum_distance_dw'];
$MinimumChrage = $data['minimum_fare_dw'];
}
}else{			
$qry = "select (select distinct Km from tblairportaddress where Km<='$distance' order by Km desc limit 1) as MinKm,
(select Fare from tblairportaddress where Km<='$distance' order by Km desc limit 1) as MinFare,
(select distinct Km from tblairportaddress where Km>='$distance' order by Km Asc limit 1) as MaxKm,
(select Fare from tblairportaddress where Km>='$distance' order by Km Asc limit 1) as MaxFare from tblairportaddress limit 1";
$result1 = mysqli_query($this->con, $qry);
$info = mysqli_fetch_assoc($result1);
$min_diff = $distance - $info['MinKm'];
$max_diff =$info['MaxKm'] - $distance;
$diff_distance = $info['MaxKm']-$info['MinKm'];
if(($diff_distance/2) < $max_diff)
{
$cabfor	=	0;
$Min_Distance = $info['MinKm'];
$MinimumChrage=$info['MinFare'];	
}
else
{
$cabfor	=	0;
$Min_Distance = $info['MaxKm'];
$MinimumChrage=$info['MaxFare'];
}	
}
}
	//// Code to Fetch Data for Airport(103) Ends Here /////
	
	
	
	//echo $MinimumChrage; die;
	//echo "<pre>";print_r($data);//die;
	//$MinimumChrage = $data['minimum_fare_dw']; 
	$book_ref = $data['booking_reference'];
	$WaitingCharge = $data['WaitingCharge_per_minute'];
	$tripCharge_per_hour = $data['tripCharge_per_minute'];
	//$Per_Km_Charge = $data['rate_per_km_dw']; 
	//$NightCharges = explode(" ", $NightCharges);
	$NightChargesAmount = $data['NightCharges']; 
	$NightChargesUnit = $data['nightCharge_unit'];
	$speed_per_km = $data['speed_per_km'];
	$located_time = $data['Date_Timei'];
	$PickupTime = $data['PickTime'];
	$pickUpdate = $data['PickDate'];
	//$Min_Distance = $data['minimum_distance_dw']; 
	 $configPackageNo = $data['configPackageNo'];
	$DropPlace = $data['DropPlace'];
	$userEmailId = $data['userEmailId'];
	//$cabfor = $data['CabFor'];
	//$BookingType = $data['BookingType'];
	$basic_tax = $data['basic_tax'];
	$night_rate_begins = $data['night_rate_begins'];
	$night_rate_ends = $data['night_rate_ends'];
	$waiting_fees = explode('_',$data['waiting_fees']); //30_0_10
	$Waitning_minutes = $waiting_fees[0];//30
	$WaitingBiforeCharge = $waiting_fees[1]; //0
	$WaitingAfterCharge = $waiting_fees[2]; //10

	$NightEndDate = date('Y-m-d', strtotime($pickUpdate . " +1 day"));
	$nightStartUpdateTime = $pickUpdate." ".$night_rate_begins; // 2014-11-20 	00:00:00
	$nightEndUpdateTime = $NightEndDate." ".$night_rate_ends; // 2014-11-20 	00:00:00
	$startNightTimeUnix = strtotime($nightStartUpdateTime);
	$endNightTimeUnix = strtotime($nightEndUpdateTime);
	$strtTimeUnix = strtotime($strtTime);
	$endTimeUnix = strtotime($endTime);
	$located_timeUnix = strtotime($located_time);
	
	
	//echo $isMatching; 
	if($isMatching == 'true'){
		//echo $data['configPackageNo'];
		//$data['configPackageNo']=4;
		
		///IF calculation Type is Distance-wise Starts here ///
		
		if($data['configPackageNo']==1){
		$Min_Distance = $Min_Distance;
		$Per_Km_Charge = $data['Per_Km_Charge'];
		$MinimumChrage = $MinimumChrage;
		if($distance > $Min_Distance){
		$ExtraKM=$distance - $Min_Distance;
		$ExtraFare = $ExtraKM*$Per_Km_Charge;
		$TotalBill = $ExtraFare + $MinimumChrage;
		}else{
		$TotalBill = $MinimumChrage;		 								
		}
		$TripCharge = $TotalBill;
		}
		//die;
		//echo $TotalBill; die;
		///IF calculation Type is Distance-wise Ends here ///
		
		///IF calculation Type is Hourly Starts here ///
		
		elseif($data['configPackageNo'] == 2){
		//$totalmint=$distance*60/40;
		//$total_trip_minutes2=250;
		$totalmint=$total_trip_minutes2;
		$ignore_first_hours=$cabfor*60; //die;
		$MinimumChrage = $MinimumChrage;
		if($totalmint > $ignore_first_hours){
		$rest_min=$totalmint-$ignore_first_hours;
		$ExtraFare=($rest_min/60)*$data["tripCharge_per_minute"];
		$ExtraFareRound=round($ExtraFare);
		if($ExtraFareRound>$ExtraFare){
			$ExtraFare=$ExtraFareRound;
		}else{
			$ExtraFare=$ExtraFareRound+1;
		}
		$TotalBill = $ExtraFare + $MinimumChrage;
		}else{
		$TotalBill = $MinimumChrage;
		}
		$TripCharge = $TotalBill;
		$Per_Km_Charge=round($data["tripCharge_per_minute"]/60);
		$Per_Hr_Charge=$data["tripCharge_per_minute"];
		}
		
		///IF calculation Type is Hourly Ends here ///	
		
		///IF calculation Type is Distance+Hour Starts here ///
		
		elseif($data['configPackageNo'] == 3){
		$ignore_hrs		=	$cabfor*60;
		$Min_Distance 	=	$Min_Distance;
		$MinimumChrage 	=	$MinimumChrage;
		if($distance < $Min_Distance){
		$distanceRate=0;
		}
		else{
		$distanceRate= ($distance - $Min_Distance)*$data["rate_per_km_dh"];
		}
		if($total_trip_minutes < $ignore_hrs){
		$hourlyRate=0;		
		}else{					
		$hourlyRate=$total_trip_minutes-$ignore_hrs.'<br>';
		$rate_per_min=$data["rate_per_hour_dh"]/60;
		$hourlyRate=$hourlyRate*$rate_per_min;
		
		}	
		$TotalBill = $distanceRate+$hourlyRate+$MinimumChrage;
		$Per_Km_Charge=$data["rate_per_km_dh"];
		$Per_Hr_Charge=$data["rate_per_hour_dh"];
		$TripCharge = $TotalBill;			
		}
		
		//
		
		///IF calculation Type is Distance+Hour Ends here ///
		
		///IF calculation Type is Distance+Waiting Starts here ///
		
		elseif($data['configPackageNo'] == 4)
		{		
		$Min_Distance = $Min_Distance;
		$Per_Km_Charge = $data['rate_per_km_dw'];
		$MinimumChrage = $MinimumChrage;
		if($distance > $Min_Distance){
		$ExtraKM=$distance - $Min_Distance;
		$ExtraFare = $ExtraKM*$Per_Km_Charge;
		$TotalKMBill = $ExtraFare + $MinimumChrage;
		}else{
		$TotalKMBill = $MinimumChrage;								
		}
		
		$waiting_charges='0';
		if($delay_time > $Waitning_minutes){
		$waiting_charges = ($delay_time - $Waitning_minutes)*$WaitingAfterCharge;
		/*if($exactMinute < 10){
		$waiting_charges = $exactMinute*$WaitingBiforeCharge;
		}else{
		 $waiting_charges = $exactMinute*$WaitingAfterCharge;
		}*/
		}else{
		$waiting_charges = 0;	
		}
		
		$TotalBill = $TotalKMBill+$waiting_charges;
		$TripCharge = $TotalBill;
		}
		//die;
		//echo $TotalBill; die;
		///IF calculation Type is Distance+Waiting Ends here ///

		///// PeakTime Charges //////
		$PeakFare	=	$this->calculatePeakTimeCharges($TotalBill,$strtTimePeak[1]);
		$TotalBill	=	$TotalBill + $PeakFare['peakcharge'];
		///// PeakTime Charges //////
		
		///// Night Charges //////
		$NightCharges = "";	
		if ((($strtTimeUnix >= $startNightTimeUnix) && ($strtTimeUnix <= $endNightTimeUnix)) || (($endTimeUnix >= $startNightTimeUnix) && ($endTimeUnix <= $endNightTimeUnix))){
			if($NightChargesUnit == "Rs"){
				$NightCharges = $NightChargesAmount;
			}
			else{
				$NightCharges = ($TotalBill * $NightChargesAmount) / 100;
			}
		}
		$TotalBill=$TotalBill+$NightCharges;
		///// Night Charges //////
		
		///// Waiting Fees //////
		/*This will not used in admin side /// it comes from hello42/admin/fairmanage-> basic tab (Waiting Time Fees 
		all fields are not used any where in admin as welll as booking)*/
		
		/*if($delay_time!=0){
		$waitingfee_upto_minutes = $data['waitingfee_upto_minutes'];
		$totalwaitingValue	=	$this->calculatePreWaitCharges($delay_time,$waitingfee_upto_minutes);
		$TotalBill=$TotalBill+$totalwaitingValue;
		}*/
		
		///// Waiting Fees //////
		//echo $preWaitingtime; die;
		///// Pre Waiting Fees //////
		if($preWaitingtime!=0){
		$prewaitingfee_upto_minutes = $data['prewaitingfee_upto_minutes'];
		$totalPrewaitingValue	=	$this->calculatePreWaitCharges($preWaitingtime,$prewaitingfee_upto_minutes);
		if($totalPrewaitingValue==""){
		$totalPrewaitingValue	=	0;
		}else{
		$totalPrewaitingValue	=	$totalPrewaitingValue;	
		}
		$TotalBill=$TotalBill+$totalPrewaitingValue; 
		}
		//die;
		///// Pre Waiting Fees //////
		
		///// Extra Charges //////
		$extraPrice	=	$this->calculateExtraCharges($TotalBill,$data['extras']);
		$TotalBill	=	$TotalBill + $extraPrice;
		///// Extra Charges //////			
		
		$basic_tax = (($TotalBill * $basic_tax) / 100);
		$basic_tax_price = round($basic_tax);
		$TotalBill=$TotalBill+$basic_tax_price;
		//$total_tax_price=$TotalBill;
		//
		$TotalBill=round($TotalBill);
		//echo $TotalBill; die;
		$TotalBill=$TotalBill>$total_amount?$TotalBill:$total_amount;
		
		$TotalBill			=	$TotalBill;
		$tripCharge			=	$TripCharge; 
		if($waiting_charges==""){
		$waiting_charges	=	0;
		}else{
		$waiting_charges	=	$waiting_charges;	
		}
		//echo $TotalBill; die;
	}else{
		 $TotalBill 		=	$total_amount;
		 $tripCharge		=	0;
		 $waiting_charges	=	0;
	}
		//file_put_contents("amount1.txt", $TotalBill);
		//mysqli_free_result($result);
		//mysqli_next_result($this->con);
		$TotalBill				=	$TotalBill;
		$nightcharge_unit		=	$data['nightCharge_unit'];
		$nightcharge			=	$data['NightCharges'];
		$nightcharge_price		=	$NightCharges;
		$night_rate_begins		=	$data['night_rate_begins'];
		$night_rate_ends		=	$data['night_rate_ends'];
		$extras					=	str_replace(array('[',']'),'',$data['extras']);
		$extraPrice				=	round($extraPrice);
		$peakTimePrice			=	round($PeakFare['peakcharge']);
		$peaktimeFrom			=	$PeakFare['peaktimeFrom'];
		$peaktimeTo				=	$PeakFare['peaktimeTo'];
		$peaktimepercentage		=	$PeakFare['peakpercentage'];
		$basic_tax				=	$data['basic_tax'];
		$basic_tax_type			=	$data['basic_tax_type'];
		$pre_waiting_time		=	$preWaitingtime;
		if($totalPrewaitingValue==""){
		$pre_waiting_charge		=	0;
		}else{
		$pre_waiting_charge		=	$totalPrewaitingValue;
		}
		$waiting_time			=	$delay_time;
		$waiting_charge			=	$totalwaitingValue;
		//die;
	/////1.Query to select booking Reference no Starts here /////
	
	$sqlbookRef = "SELECT tblcabbooking.booking_reference as book_ref FROM tblcabbooking WHERE id='$BookingId_i'";
	$rowbookRef = mysqli_query($this->con,$sqlbookRef) or die(mysqli_error($this->con));
	$resbookRef = mysqli_fetch_array($rowbookRef);
	$book_ref	= $resbookRef['book_ref'];
	$book_ref	= "IN-".$book_ref;
	
	/////1.Query to select booking Reference no Ends here /////
	
	/////2.Query to Insert in Bookinglogs table Starts here /////

 	$sqlbookingCharges="INSERT INTO tblbookingcharges (tripCharge,waitingCharge,minimumCharge,BookingID,AddedTime,distance_rate,distance_charge,minimum_distance,total_tax_price,currency,invoice_number,total_price,tax_price,starting_rate,starting_charge,duration_charge,minimum_price,nightcharge_unit,nightcharge,nightcharge_price,night_rate_begins,night_rate_ends,extras,extraPrice,peakTimePrice,peaktimeFrom,peaktimeTo,peaktimepercentage,basic_tax,basic_tax_type,basic_tax_price,pre_waiting_time,pre_waiting_charge,waiting_time,waiting_charge)VALUES('$tripCharge','$waiting_charges','$MinimumChrage','$BookingId_i',NOW(),'$Per_Km_Charge','$tripCharge','$Min_Distance','$basic_tax_price','RS','$book_ref','$TotalBill','$basic_tax_price','$Per_Km_Charge','$MinimumChrage','$Min_Distance','$MinimumChrage','$nightcharge_unit','$nightcharge','$nightcharge_price','$night_rate_begins','$night_rate_ends','$extras','$extraPrice','$peakTimePrice','$peaktimeFrom','$peaktimeTo','$peaktimepercentage','$basic_tax','$basic_tax_type','$basic_tax_price','$pre_waiting_time','$pre_waiting_charge','$waiting_time','$waiting_charge')";
	mysqli_query($this->con,$sqlbookingCharges) or die(mysqli_error($this->con));
	
	/////2.Query to Insert in Bookinglogs table Ends here /////	
		
	/////3.Query to Insert in Bookinglogs table Starts here ///

	$sqlbookinglogs = "INSERT INTO tblbookinglogs(bookingid,`status`,message,`time`)VALUES('$BookingId_i',8,'Completed',NOW())";
	mysqli_query($this->con,$sqlbookinglogs) or die(mysqli_error($this->con));
	
	/////3.Query to Insert in Bookinglogs table Ends here ///
	
	/////4.Query to Update in tblcabbooking table Starts here ///
	
	$sqlcabbooking ="UPDATE tblcabbooking SET `status`='8',DropAddress='$address',final_lat='$lat',final_long='$long',actual_distance='$distance',actual_time='$total_time',
					actual_driven_distance='$distance',actual_driven_duration='$total_time',expiration_time=NOW() WHERE id='$BookingId_i'"; 
	mysqli_query($this->con,$sqlcabbooking) or die(mysqli_error($this->con));
	
	/////4.Query to Update in tblcabbooking table Ends here ///

	$tripInfo = array("totalbill"=>round($TotalBill),"totalTax"=>$basic_tax_price,"preWaitingTimeCharge"=>$pre_waiting_charge);
	$this->send_sms_new2($BookingId,$flag="invoice"); // Uncomment it after complete
	return array("status"=>'true',"tripInfo"=>$tripInfo);
	}
	
	//////////// Cab Billing New Service Ends By Mohit Jain //////	
	
	
	
	//////////// Cab Billing New Service for Automatic Bill Calculation Starts By Mohit Jain //////
	
	public function cabBillingCompleteAdmin(){
		
		$BookingId_i = 8533;
		$user_id	 = 2130;
		
		// $BookingId_i = $_REQUEST['booking_id'];
		// $user_id	 = $_REQUEST['driver_id'];
		
		$sql="select distance,datetime,WaitingTime,pre_Waiting_time from tbldriverlocation where booking_id='$BookingId_i' and user_id = '$user_id' order by id DESC limit 1";
		$qry = mysqli_query($this->con,$sql);
		$data = mysqli_fetch_array($qry);
		if(mysqli_num_rows($qry)>0){	
		$distance			=	$data['distance']/1000;
		$endTime			= 	$data['datetime'];
		$delay_time			=	$data['WaitingTime'];
		$pre_Waiting_time	=	$data['pre_Waiting_time'];
		
		$sql1="select datetime from tbldriverlocation where booking_id='$BookingId_i' and user_id = '$user_id' order by id ASC limit 1";
		$qry1 = mysqli_query($this->con,$sql1);
		$data1 = mysqli_fetch_array($qry1);		
		$strtTime = $data1['datetime'];
		
		$strtTimePeak=explode(" ",$strtTime);
		$delay_time=explode(':',$delay_time);
		$delay_time0=round($delay_time[0]*60);
		$delay_time1=round($delay_time[2]/60);
		$delay_time=$delay_time0+$delay_time1+$delay_time[1];		
		
		$pre_Waiting_time=explode(':',$pre_Waiting_time);
		$pre_Waiting_time0=round($pre_Waiting_time[0]*60);
		$pre_Waiting_time1=round($pre_Waiting_time[2]/60);
		$preWaitingtime=$pre_Waiting_time0+$pre_Waiting_time1+$pre_Waiting_time[1];

$diff = abs(strtotime($endTime) - strtotime($strtTime));
$years = floor($diff / (365*60*60*24)); 
$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
$total_trip_hours = floor(($diff)/ (60*60));
//$minuts = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $total_trip_hours*60*60)/ 60);
$minuts=$diff/60;
$total_trip_minutes=round($minuts); 
$total_trip_minutes2=round($minuts);
if($total_trip_minutes2>$minuts)
{
   $total_trip_minutes2=$total_trip_minutes2;
}else
{
	 $total_trip_minutes2=$total_trip_minutes2+1;
}
if($minuts>0){
	$total_trip_hours=$total_trip_hours+1;	
}
		

	$sql2="SELECT BookingType,CarType,pickup,local_subpackage,PickUpArea,DropArea FROM tblcabbooking WHERE ID='$BookingId_i'"; 
	$res =mysqli_query($this->con, $sql2) or die(mysqli_error($this->con));
	$data1 = $res->fetch_array();
	$BookingType=$data1['BookingType']; 
	$CarType=$data1['CarType'];
	$pickupid=$data1['pickup'];
	
	$IsMatchedCabType=$this->IsCabtypeMatched($pickupid,$BookingId_i);
	
	if($IsMatchedCabType){
	$sql3="CALL wp_cabBookingCompleteBillNew('$BookingId_i','$BookingType')";
	}else{
	$sql3="CALL wp_cabBookingCompleteBillNotMatched('$BookingId_i','$BookingType')";
	}
	//echo $sql3; die;
	//$sql3="CALL wp_cabBookingCompleteBillNew('$BookingId_i','$BookingType')"; 
	$result = mysqli_query($this->con, $sql3) or die(mysqli_error($this->con));
	$data = mysqli_fetch_assoc($result);
	//echo "<pre>";print_r($data); //die;
	$stateId	=	$data['state'];
	
	mysqli_free_result($result);   
	mysqli_next_result($this->con);
	
	$sql1 = "select Sub_Package_Id from tblmasterpackage WHERE Package_Id='$BookingType' AND state_id='$stateId'";
	$row1 = mysqli_query($this->con,$sql1) or die(mysqli_error($this->con));
	$res1 = mysqli_fetch_array($row1);
	$Sub_Package_Id	=	$res1['Sub_Package_Id'];
	
	//// Code to Fetch Data for Local(101) && Point to Point(102) Starts Here /////
	
	if($BookingType==101 || $BookingType==102){
	$cablocalfor	=	$data1['local_subpackage'];
	if($cablocalfor!=""){
	$sql = "SELECT * FROM `tbllocalpackage` WHERE Package='$cablocalfor' AND masterPKG_ID='$BookingType' AND Sub_Package_Id='$Sub_Package_Id' AND City_ID='$stateId' AND cabType='$CarType'"; //die;
	$row = mysqli_query($this->con,$sql);		
	$date = mysqli_fetch_object($row);
	$num_rows = mysqli_num_rows($row);
		//echo $Sub_Package_Id;	
	if($num_rows > 0){
		$cabfor	=	$date->Hrs;
		$Min_Distance = $date->Km;
		$MinimumChrage=$date->Price;		
	}}else{	
		if($Sub_Package_Id == 1){
		$Min_Distance = $data['Min_Distance'];
		$MinimumChrage = $data['MinimumCharge'];
		}else if($Sub_Package_Id == 2){
		$cabfor	=	$data['CabFor'];
		$MinimumChrage = $data['minimum_hourly_Charge'];
		}else if($Sub_Package_Id == 3){
		$cabfor	=	$data['ignore_first_hrs_dh'];
		$Min_Distance = $data['minimum_distance_dh'];
		$MinimumChrage = $data['minimum_fare_dh'];
		}else if($Sub_Package_Id == 4){	
		$Min_Distance = $data['minimum_distance_dw'];
		$MinimumChrage = $data['minimum_fare_dw'];
		}
	}
	}
	
	//// Code to Fetch Data for Local(101) && Point to Point(102) Ends Here /////
	
	//// Code to Fetch Data for Airport(103) Starts Here /////
	if($BookingType==103){		
	$PickAreaName		=	$data1['PickUpArea']; 
	$DropAreaName		=	$data1['DropArea'];
	$sqlAir	="SELECT * FROM `tblairportaddress` WHERE `Fix_Point`='$PickAreaName' LIMIT 1"; 
	$sqlAir	=mysqli_query($this->con,$sqlAir);	
	$row=mysqli_fetch_assoc($sqlAir);
	$num_rows1 = mysqli_num_rows($sqlAir);
	if($num_rows1>0){
	$fixpoint		=	$PickAreaName;
	}
	else{
	 $fixpoint		=	$DropAreaName;
	}	
	
	//$sql = "SELECT * FROM `tblairportaddress` WHERE Address='$address' AND Fix_Point='$fixpoint' AND masterPKG_ID='$BookingType' AND Sub_Package_Id='$Sub_Package_Id' AND cityId='$stateId' AND cabType='$CarType'"; //die;
	$sql = "SELECT * FROM `tblairportaddress` WHERE '$address' LIKE concat('%',`Address`,'%') AND '$fixpoint' like concat('%',`transfer_type`,'%') AND masterPKG_ID='$BookingType' AND Sub_Package_Id='$Sub_Package_Id' AND cityId='$stateId' AND cabType='$CarType' LIMIT 1"; 
	$row1 = mysqli_query($this->con,$sql);		
	$date = mysqli_fetch_object($row1);
	$num_rows1 = mysqli_num_rows($row1);
	
	/////////////////////////////
	
if($num_rows1 == 0){
if($Sub_Package_Id == 1){
$Min_Distance = $data['Min_Distance'];
$MinimumChrage = $data['MinimumCharge'];
}else if($Sub_Package_Id == 2){
$cabfor	=	$data['CabFor'];
$MinimumChrage = $data['minimum_hourly_Charge'];
}else if($Sub_Package_Id == 3){
$cabfor	=	$data['ignore_first_hrs_dh'];
$Min_Distance = $data['minimum_distance_dh'];
$MinimumChrage = $data['minimum_fare_dh'];
}else if($Sub_Package_Id == 4){	
$Min_Distance = $data['minimum_distance_dw'];
$MinimumChrage = $data['minimum_fare_dw'];
}
}else{			
$qry = "select (select distinct Km from tblairportaddress where Km<='$distance' order by Km desc limit 1) as MinKm,
(select Fare from tblairportaddress where Km<='$distance' order by Km desc limit 1) as MinFare,
(select distinct Km from tblairportaddress where Km>='$distance' order by Km Asc limit 1) as MaxKm,
(select Fare from tblairportaddress where Km>='$distance' order by Km Asc limit 1) as MaxFare from tblairportaddress limit 1";
$result1 = mysqli_query($this->con, $qry);
$info = mysqli_fetch_assoc($result1);
$min_diff = $distance - $info['MinKm'];
$max_diff =$info['MaxKm'] - $distance;
$diff_distance = $info['MaxKm']-$info['MinKm'];
if(($diff_distance/2) < $max_diff)
{
$cabfor	=	0;
$Min_Distance = $info['MinKm'];
$MinimumChrage=$info['MinFare'];	
}
else
{
$cabfor	=	0;
$Min_Distance = $info['MaxKm'];
$MinimumChrage=$info['MaxFare'];
}	
}
}
	//// Code to Fetch Data for Airport(103) Ends Here /////
	
	
	
	//echo $MinimumChrage; die;
	//echo "<pre>";print_r($data);//die;
	//$MinimumChrage = $data['minimum_fare_dw']; 
	$book_ref = $data['booking_reference'];
	$WaitingCharge = $data['WaitingCharge_per_minute'];
	$tripCharge_per_hour = $data['tripCharge_per_minute'];
	//$Per_Km_Charge = $data['rate_per_km_dw']; 
	//$NightCharges = explode(" ", $NightCharges);
	$NightChargesAmount = $data['NightCharges']; 
	$NightChargesUnit = $data['nightCharge_unit'];
	$speed_per_km = $data['speed_per_km'];
	$located_time = $data['Date_Timei'];
	$PickupTime = $data['PickTime'];
	$pickUpdate = $data['PickDate'];
	//$Min_Distance = $data['minimum_distance_dw']; 
	 $configPackageNo = $data['configPackageNo'];
	$DropPlace = $data['DropPlace'];
	$userEmailId = $data['userEmailId'];
	//$cabfor = $data['CabFor'];
	//$BookingType = $data['BookingType'];
	$basic_tax = $data['basic_tax'];
	$night_rate_begins = $data['night_rate_begins'];
	$night_rate_ends = $data['night_rate_ends'];
	$waiting_fees = explode('_',$data['waiting_fees']); //30_0_10
	$Waitning_minutes = $waiting_fees[0];//30
	$WaitingBiforeCharge = $waiting_fees[1]; //0
	$WaitingAfterCharge = $waiting_fees[2]; //10

	$NightEndDate = date('Y-m-d', strtotime($pickUpdate . " +1 day"));
	$nightStartUpdateTime = $pickUpdate." ".$night_rate_begins; // 2014-11-20 	00:00:00
	$nightEndUpdateTime = $NightEndDate." ".$night_rate_ends; // 2014-11-20 	00:00:00
	$startNightTimeUnix = strtotime($nightStartUpdateTime);
	$endNightTimeUnix = strtotime($nightEndUpdateTime);
	$strtTimeUnix = strtotime($strtTime);
	$endTimeUnix = strtotime($endTime);
	$located_timeUnix = strtotime($located_time);

		///IF calculation Type is Distance-wise Starts here ///
		
		if($data['configPackageNo']==1){
		$Min_Distance = $Min_Distance;
		$Per_Km_Charge = $data['Per_Km_Charge'];
		$MinimumChrage = $MinimumChrage;
		if($distance > $Min_Distance){
		$ExtraKM=$distance - $Min_Distance;
		$ExtraFare = $ExtraKM*$Per_Km_Charge;
		$TotalBill = $ExtraFare + $MinimumChrage;
		}else{
		$TotalBill = $MinimumChrage;		 								
		}
		$TripCharge = $TotalBill;
		}
		//die;
		//echo $TotalBill; die;
		///IF calculation Type is Distance-wise Ends here ///
		
		///IF calculation Type is Hourly Starts here ///
		
		elseif($data['configPackageNo'] == 2){
		//$totalmint=$distance*60/40;
		//$total_trip_minutes2=250;
		$totalmint=$total_trip_minutes2;
		$ignore_first_hours=$cabfor*60; //die;
		$MinimumChrage = $MinimumChrage;
		if($totalmint > $ignore_first_hours){
		$rest_min=$totalmint-$ignore_first_hours;
		$ExtraFare=($rest_min/60)*$data["tripCharge_per_minute"];
		$ExtraFareRound=round($ExtraFare);
		if($ExtraFareRound>$ExtraFare){
			$ExtraFare=$ExtraFareRound;
		}else{
			$ExtraFare=$ExtraFareRound+1;
		}
		$TotalBill = $ExtraFare + $MinimumChrage;
		}else{
		$TotalBill = $MinimumChrage;
		}
		$TripCharge = $TotalBill;
		$Per_Km_Charge=round($data["tripCharge_per_minute"]/60);
		$Per_Hr_Charge=$data["tripCharge_per_minute"];
		}
		
		///IF calculation Type is Hourly Ends here ///	
		
		///IF calculation Type is Distance+Hour Starts here ///
		
		elseif($data['configPackageNo'] == 3){
		$ignore_hrs		=	$cabfor*60;
		$Min_Distance 	=	$Min_Distance;
		$MinimumChrage 	=	$MinimumChrage;
		if($distance < $Min_Distance){
		$distanceRate=0;
		}
		else{
		$distanceRate= ($distance - $Min_Distance)*$data["rate_per_km_dh"];
		}
		if($total_trip_minutes < $ignore_hrs){
		$hourlyRate=0;		
		}else{					
		$hourlyRate=$total_trip_minutes-$ignore_hrs;
		$rate_per_min=$data["rate_per_hour_dh"]/60;
		$hourlyRate=$hourlyRate*$rate_per_min;
		
		}	
		$TotalBill = $distanceRate+$hourlyRate+$MinimumChrage;
		$Per_Km_Charge=$data["rate_per_km_dh"];
		$Per_Hr_Charge=$data["rate_per_hour_dh"];
		$TripCharge = $TotalBill;			
		}
		
		//
		
		///IF calculation Type is Distance+Hour Ends here ///
		
		///IF calculation Type is Distance+Waiting Starts here ///
		
		elseif($data['configPackageNo'] == 4)
		{		
		$Min_Distance = $Min_Distance;
		$Per_Km_Charge = $data['rate_per_km_dw'];
		$MinimumChrage = $MinimumChrage;
		if($distance > $Min_Distance){
		$ExtraKM=$distance - $Min_Distance;
		$ExtraFare = $ExtraKM*$Per_Km_Charge;
		$TotalKMBill = $ExtraFare + $MinimumChrage;
		}else{
		$TotalKMBill = $MinimumChrage;								
		}
		
		$waiting_charges='0';
		if($delay_time > $Waitning_minutes){
		$waiting_charges = ($delay_time - $Waitning_minutes)*$WaitingAfterCharge;
		/*if($exactMinute < 10){
		$waiting_charges = $exactMinute*$WaitingBiforeCharge;
		}else{
		 $waiting_charges = $exactMinute*$WaitingAfterCharge;
		}*/
		}else{
		$waiting_charges = 0;	
		}
		
		$TotalBill = $TotalKMBill+$waiting_charges;
		$TripCharge = $TotalBill;
		}
		//die;
		//echo $TotalBill; die;
		///IF calculation Type is Distance+Waiting Ends here ///

		///// PeakTime Charges //////
		$PeakFare	=	$this->calculatePeakTimeCharges($TotalBill,$strtTimePeak[1]);
		$TotalBill	=	$TotalBill + $PeakFare['peakcharge'];
		///// PeakTime Charges //////
		
		///// Night Charges //////
		$NightCharges = "";	
		if ((($strtTimeUnix >= $startNightTimeUnix) && ($strtTimeUnix <= $endNightTimeUnix)) || (($endTimeUnix >= $startNightTimeUnix) && ($endTimeUnix <= $endNightTimeUnix))){
			if($NightChargesUnit == "Rs"){
				$NightCharges = $NightChargesAmount;
			}
			else{
				$NightCharges = ($TotalBill * $NightChargesAmount) / 100;
			}
		}
		$TotalBill=$TotalBill+$NightCharges;
		///// Night Charges //////
		
		///// Waiting Fees //////
		/*This will not used in admin side /// it comes from hello42/admin/fairmanage-> basic tab (Waiting Time Fees 
		all fields are not used any where in admin as welll as booking)*/
		
		/*if($delay_time!=0){
		$waitingfee_upto_minutes = $data['waitingfee_upto_minutes'];
		$totalwaitingValue	=	$this->calculatePreWaitCharges($delay_time,$waitingfee_upto_minutes);
		$TotalBill=$TotalBill+$totalwaitingValue;
		}*/
		
		///// Waiting Fees //////
		//echo $preWaitingtime; die;
		///// Pre Waiting Fees //////
		if($preWaitingtime!=0){
		$prewaitingfee_upto_minutes = $data['prewaitingfee_upto_minutes'];
		$totalPrewaitingValue	=	$this->calculatePreWaitCharges($preWaitingtime,$prewaitingfee_upto_minutes);
		if($totalPrewaitingValue==""){
		$totalPrewaitingValue	=	0;
		}else{
		$totalPrewaitingValue	=	$totalPrewaitingValue;	
		}
		$TotalBill=$TotalBill+$totalPrewaitingValue; 
		}
		//die;
		///// Pre Waiting Fees //////
		
		///// Extra Charges //////
		$extraPrice	=	$this->calculateExtraCharges($TotalBill,$data['extras']);
		$TotalBill	=	$TotalBill + $extraPrice;
		///// Extra Charges //////			
		
		$basic_tax = (($TotalBill * $basic_tax) / 100);
		$basic_tax_price = round($basic_tax);
		$TotalBill=$TotalBill+$basic_tax_price;
		$TotalBill=round($TotalBill);
		$TotalBill=$TotalBill>$total_amount?$TotalBill:$total_amount;
		
		$TotalBill			=	$TotalBill;
		$tripCharge			=	$TripCharge;
		if($waiting_charges==""){
		$waiting_charges	=	0;
		}else{
		$waiting_charges	=	$waiting_charges;	
		}
		//echo $TotalBill; die;
		$TotalBill				=	round($TotalBill);
		if($TotalBill!=""){
		$TotalBill=$TotalBill;
		}else{
		$TotalBill=0;
		}
		}else{
		$TotalBill=0;
		}


		// $ret = array($Min_Distance,$TotalBill,
		// 	$extraPrice,$waiting_charges,$basic_tax_price,$totalPrewaitingValue);
		
		//echo $TotalBill;
		//return array("status"=>true,"totalBill"=>$TotalBill);
		//$tripInfo = array("totalbill"=>round($TotalBill));
	return array("status"=>'true',"totalbill"=>$TotalBill);
	}
	
	//////////// Cab Billing New Service for Automatic Bill Calculation Ends By Mohit Jain //////	
	
	//////// Function to Calculate Extra Charges Starts Here ////
	
	public function calculateExtraCharges($totalbill,$extras){
		// public function calculateExtraCharges(){
// $totalbill=1000;
// $extras='"LuggageCharges_100_Rs","ExtraCharge2_0_Rs","ExtraCharge3_0_Rs","ExtraCharge4_0_Rs","ExtraCharge5_0_Rs","ParkingCharge_50_Rs","TollTax_55_Rs","OtherCharge_6_Rs"';

// $extras='"LuggageCharges_100_Rs","ExtraCharge2_0_Rs","ExtraCharge3_0_Rs","ExtraCharge4_0_Rs","ExtraCharge5_0_Rs","ParkingCharge_50_Rs","TollTax_5_Rs","OtherCharge_6_Rs"';

		// return $extras;
		$extras = str_replace('"','' ,$extras);
		$extrasArr = explode(",",$extras);
		$totalbillValue1=0;
		for($i=0;$i<count($extrasArr);$i++){
			// $totalbillValue=0;
		$extrasArr_key = explode("_",$extrasArr[$i]);
			 if($extrasArr_key[2]=="Rs"){
				// echo "a";	
				  $totalbillValue=$extrasArr_key[1];	
				 		
			 }
			 elseif($extrasArr_key[2]=="%"){
				 // echo "b";
				  $totalbillValue = ($totalbill * $extrasArr_key[1])/100;
				
			 }
			 // echo "c";
			  $totalbillValue1=$totalbillValue1+$totalbillValue;
		}
		// die;
		return $totalbillValue1; 
	}
	
	//////// Function to Calculate Extra Charges Ends Here ////
	
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
	
	//////////// Function to Calculate Waiting and PreWaiting Charges Starts Here/////////////////////
	
	/*public function calculatePreWaitCharges($delay_time,$waitingfee_upto_minutes){
			$delay_time_calc=$delay_time;
			$wtngFee=array();
			$waitingfee_upto_minutesArr = json_decode($waitingfee_upto_minutes);
			$totalwaitingValue1=0;
			$totalwaitingValue2=0;
			$totalwaitingValue3=0;
			$per_minute=0;
			$lengthCount=count($waitingfee_upto_minutesArr);
		  for($j=0;$j<count($waitingfee_upto_minutesArr);$j++){
				$totalwaitingValue=0;
				$wtngFee[$j] = $waitingfee_upto_minutesArr[$j];
			if($j<=0){
				$wtngMinutes = explode("_",$wtngFee[$j]);
				$delay_time_calc=$delay_time_calc-$wtngMinutes[0];
				$totalwaitingValue2=$wtngMinutes[1];
				$delay_time=$delay_time_calc;
				if($delay_time_calc<=0){
					break;
				}
			}else{
				$wtngMinutes = explode("_",$wtngFee[$j]);
				if($wtngMinutes[0] >= $delay_time){				
				$totalwaitingValue3=$wtngMinutes[1];
				$delay_time_calc=0;
				}elseif(($wtngMinutes[0] <= $delay_time) && ($j==$lengthCount-1)){
					$per_minute=($wtngMinutes[1]/$wtngMinutes[0])*$delay_time;					
				}
				
				if($delay_time_calc==0){
					break;
				}
			}			
		  }
		   $totalwaitingValue=$totalwaitingValue3 + $totalwaitingValue2 + round($per_minute);
		return $totalwaitingValue;	
	}*/
	
	
	public function calculatePreWaitCharges($delay_time,$waitingfee_upto_minutes){
		$wtngFee=array();
		$waitingfee_upto_minutesArr = json_decode($waitingfee_upto_minutes); 
		$totalwaitingValue1=0;
		$totalwaitingValue2=0;
		$totalwaitingValue3=0;
		$per_minute=0;
		$lengthCount=count($waitingfee_upto_minutesArr);
		for($j=0;$j<count($waitingfee_upto_minutesArr);$j++){
		$totalwaitingValue=0;
		$wtngFee[$j] = $waitingfee_upto_minutesArr[$j];
		$wtngMinutes = explode("_",$wtngFee[$j]);
			if($wtngMinutes[0] >= $delay_time){
			$totalwaitingValue2=$wtngMinutes[1];
			break;	
			}else if($j==$lengthCount-1){
			$totalwaitingValue2=$wtngMinutes[1];
			$delay_time=$delay_time-$wtngMinutes[0];
			if($delay_time>0){
			$totalwaitingValue3=($delay_time/$wtngMinutes[0])*$wtngMinutes[1];
			$totalwaitingValue2=$totalwaitingValue2+$totalwaitingValue3;
			}
			}
		}
		return  $totalwaitingValue2;
	}
	
	//////////// Function to Calculate Waiting and PreWaiting Charges Ends Here/////////////////////
	//// Service for test ///
	
	 public function cabBillingComplete1(){

		/*$distance = '0';
		$BookingId_i = '7616';
		$BookingId = $BookingId_i;
		$strtTime = '2016-02-29 11:39:00';
		$strtTimePeak=explode(" ",$strtTime);
		$endTime = '2016-02-29 11:39:59';
		$address= '1321, Desh Bandhu Gupta Rd, New Delhi, India';
		$lat='28.6523516';
		$long='77.1945016';
		$delay_time='0';
		$current_time='2016-02-29 11:35:05';
		$total_amount='640';
		$total_time='00:00:23';
		$isMatching ='true';
		$preWaiting_time	='0';*/
		
		//// New Parameters should be added //
				

		$distance = $_REQUEST['distance'];
		$BookingId_i = $_REQUEST['bookingId'];
		$BookingId = $BookingId_i;
		$strtTime = $_REQUEST['strtTime'];
		$strtTimePeak=explode(" ",$strtTime);
		$endTime = $_REQUEST['endTime'];
		$address=$_REQUEST['address'];
		$lat=$_REQUEST['lat'];
		$long=$_REQUEST['lon'];
		$delay_time=$_REQUEST['delay_time'];
		$current_time=$_REQUEST['currentTime'];
		$total_amount=$_REQUEST['totalAmount']; //exit();
		$total_time=$_REQUEST['totalTime'];
		$isMatching=$_REQUEST['isMatching']; 
		$preWaiting_time=$_REQUEST['pre_Waiting_time'];
		//$preWaiting_time	='0';
		
		$sql="INSERT INTO `tbl_test` (`distance`, `bookingId`, `strtTime`, `strtTimePeak`, `endTime`, `address`, `lat`, `long`, `delay_time`, `current_time`, `total_amount`, `total_time`, `isMatching`,`pre_Waiting_time`) VALUES ('$distance', '$BookingId', '$strtTime', '$strtTimePeak[1]', '$endTime', '$address', '$lat', '$long', '$delay_time', '$current_time', '$total_amount', '$total_time', '$isMatching','$preWaiting_time')";
	mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
		 //die;
		 
		 return array("status"=>true);
	} 

	 public function cash_button1(){

		/*$distance = '0';
		$BookingId_i = '7616';
		$BookingId = $BookingId_i;
		$strtTime = '2016-02-29 11:39:00';
		$strtTimePeak=explode(" ",$strtTime);
		$endTime = '2016-02-29 11:39:59';
		$address= '1321, Desh Bandhu Gupta Rd, New Delhi, India';
		$lat='28.6523516';
		$long='77.1945016';
		$delay_time='0';
		$current_time='2016-02-29 11:35:05';
		$total_amount='640';
		$total_time='00:00:23';
		$isMatching ='true';
		$preWaiting_time	='0';*/
		
		//// New Parameters should be added //
				
		$user_rating=$_REQUEST['user_rating'];
		$driver_id=$_REQUEST['id'];             
		$type=$_REQUEST['type'];    
		$booking_id=$_REQUEST['booking_id'];     
		$road_tax=$_REQUEST['road_tax'];
		$toolTax=$_REQUEST['toolTax'];                              
		$totalAmount=$_REQUEST['totalAmount'];     
		$other_tax=$_REQUEST['other_tax'];
		
		$sql="INSERT INTO `tbl_test1` (`user_rating`, `driver_id`, `type`, `booking_id`, `road_tax`, `toolTax`, `totalAmount`, `other_tax`) VALUES ('$user_rating', '$driver_id', '$type', '$booking_id', '$road_tax', '$toolTax', '$totalAmount','$other_tax')";
	mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
		 //die;
		 
		 return array("status"=>true);
	} 


	//execute payment in Admin by Mohit
		
		public function ExePymntByAdmin()
		{

//for Live purpose
			// print_r($_POST);die;
			
		$book_ref = $_POST['bokref'];
		$booking = substr($book_ref, 4);
		$type=$_POST['payment_type'];
			
//for testing purpose
			// $book_ref = "HWPE8634";
			// $booking = substr($book_ref, 4);
			// // $type="Cash";
			// $type="Credit";
		
		$data = $this->con->query("select MobileNo,pickup from tblcabbooking where ID=$booking")->fetch_assoc();
		$Driverid= $data['pickup'];
		$CallerID = $data['MobileNo'];
		
		$sql4="SELECT SecurityAmt as security_amt_i FROM tbldriver WHERE UID='$Driverid'";
		$qry4 = mysqli_query($this->con,$sql4);
		$row4 = mysqli_fetch_object($qry4);
		$security_amt_i=$row4->security_amt_i; 
		
		
		$sql3="SELECT company_share as comp_share,driver_share as share_deducted,partner_share as partner_share_i FROM tblclient WHERE id=1;";
		$qry3 = mysqli_query($this->con,$sql3);
		$row3 = mysqli_fetch_object($qry3);
		$comp_share=$row3->comp_share;
		$share_deducted=$row3->share_deducted;
		$partner_share_i=$row3->partner_share_i;
		
		$sql1="SELECT total_price,total_tax_price as basic_tax_price,extraPrice FROM tblbookingcharges WHERE BookingID='$booking' order by id desc limit 1";
		$qry1 = mysqli_query($this->con,$sql1);
		$row1 = mysqli_fetch_object($qry1);
		$basic_tax_price=$row1->basic_tax_price; 
		$amount=$row1->total_price; 
		$extra=$row1->extraPrice; 
		//echo $amount;
		

		if($type=="Cash"){
		$payment_type="CASH";
		$booking_status=11;			
		$bill_deducted=((($amount-($extra+$basic_tax_price))*(100-$share_deducted))/100);		
		$totalAmount=$bill_deducted+$basic_tax_price;
		$Amount		=	$security_amt_i-$totalAmount;
		$status="Debit";	
		$reason="Deducted from Booking by admin";
		}

		else{
		$payment_type="CREDIT";
		$booking_status=12;	    
	    $bill_deducted=((($amount-($extra+$basic_tax_price))*(100-$comp_share))/100);		
		// $totalAmount=$bill_deducted+$basic_tax_price;
		$totalAmount = $bill_deducted ;
		$Amount		=	$security_amt_i + $totalAmount;
		$status="Credit";
		$reason="Added amount from Booking by admin";
		}
		
		
		$sqlDriverTrans1="SELECT * FROM tbl_driver_transaction WHERE booking_ref='$book_ref' order by id desc limit 1";
		$sqlDriverTrans1qry1 = mysqli_query($this->con,$sqlDriverTrans1);
		$sqlDriverTrans1qry1row1 = mysqli_fetch_object($sqlDriverTrans1qry1);
		if(mysqli_num_rows($sqlDriverTrans1qry1)>0){
		$amt=$sqlDriverTrans1qry1row1->amount + $sqlDriverTrans1qry1row1->basic_tax_amount;
		$bill_deducted=$bill_deducted-$amt;
		//$new_tax= $basic_tax_price.'<br>';
		//$driver_amt=$security_amt_i;
		if($type=="Cash"){
		$Amount=$security_amt_i-($bill_deducted+$basic_tax_price);
		$reason="Booking Adjusted against Booking Ref ".$book_ref;
		}else{
		$Amount=$security_amt_i+($bill_deducted+$basic_tax_price);
		$reason="Booking Adjusted against Booking Ref ".$book_ref;
		}
		}
		
		//die;
		$sqlDriverTrans = "INSERT INTO tbl_driver_transaction (`user_id`,`amount`,`basic_tax_amount`,`currentbalance`,`status`,`booking_ref`,`reason`) VALUES ('$Driverid','$bill_deducted','$basic_tax_price','$Amount','$status','$book_ref','$reason')"; //die;
		mysqli_query($this->con,$sqlDriverTrans) or die(mysqli_error($this->con));	

		$sqldriver1 ="UPDATE tbldriver SET `SecurityAmt`='$Amount' , `status`=0 WHERE UID='$Driverid'";
		mysqli_query($this->con,$sqldriver1) or die(mysqli_error($this->con));
		
		$sqlcabbooking ="UPDATE tblcabbooking SET `status`='$booking_status' WHERE id='$booking'";
		mysqli_query($this->con,$sqlcabbooking) or die(mysqli_error($this->con));

		/////3.Query to Insert in Bookinglogs table Starts here ///
		$sqlbookinglogs = "INSERT INTO tblbookinglogs(`bookingid`,`status`,`time`)VALUES('$booking','$booking_status',NOW())";
		mysqli_query($this->con,$sqlbookinglogs) or die(mysqli_error($this->con));
		/////3.Query to Insert in Bookinglogs table Ends here ///
		
		/////4.Query to Update in tblbookingCharges table Starts here ///
		$sqlbookingcharges ="UPDATE tblbookingcharges SET is_paid=1,paid_at=NOW(),payment_type='$payment_type', totalBill='$amount' , 
		partnershare='$partner_share_i',companyshare='$comp_share',drivershare='$share_deducted' WHERE BookingID='$booking'"; 
		mysqli_query($this->con,$sqlbookingcharges) or die(mysqli_error($this->con));
		/////4.Query to Update in tblbookingCharges table Ends here ///
			$bookRef = substr($book_ref, 0,2);
			if($bookRef=="HC"){
			$this->con->query("insert into  tblagent_work_history (AgentID,CallerID,BookingID,Actiontype,tblagent_work_history.Date,ActionDesc) values ((select CSR_ID from tblcabbooking where booking_reference='$book_ref'),'$CallerID' ,'$book_ref', 'Execute Payment',Now(),'Execute Payment by admin for $book_ref')");
			}
			header("contentType:application/json");
			echo json_encode(array("status"=>1,"message"=>"Payment executed successfully"));
		die;
		}

		//execute payment in Admin by Mohit



	////////////////////// Cash Button Service Code Done By Mohit Jain Starts Here/////
	
		public function cash_button(){
		if($_REQUEST['user_rating']!='' && $_REQUEST['booking_id']!=''){
			$this->UserRating($_REQUEST['booking_id'],$_REQUEST['user_rating']);
		} 
		 
		$Driverid=$_REQUEST['id'];             
		$type=$_REQUEST['type'];    
		$booking=$_REQUEST['booking_id'];     
		$road_tax=$_REQUEST['road_tax'];
		$toll_tax=$_REQUEST['toolTax'];                              
		$amount=$_REQUEST['totalAmount'];     
		$other_tax=$_REQUEST['other_tax'];
		
		/*$Driverid='2134';             
		$type='cash';   
		$booking='8329';     
		$road_tax='0';
		$toll_tax='0';                              
		$amount='127';     
		$other_tax='0';*/	
		
		$extra=$toll_tax+$road_tax+$other_tax; //die;
		//used for testing purpose
		$sql_insert="INSERT INTO `tbl_test1` (`user_rating`, `driver_id`, `type`, `booking_id`, `road_tax`, `toolTax`, `totalAmount`, `other_tax`) VALUES ('$user_rating', '$Driverid', '$type', '$booking', '$road_tax', '$toll_tax', '$amount','$other_tax')";
	mysqli_query($this->con,$sql_insert) or die(mysqli_error($this->con));
		
		file_put_contents("amount.txt", $amount);
		/*$row=mysqli_query($this->con,"CALL wp_driver_cash('$booking','$Driverid','$type','$amount','$toll_tax','$road_tax','$other_tax')")or die(mysqli_error($this->con));
		$result=mysqli_fetch_assoc($row);*/
		/// Code Done By Mohit Starts Here //
		
		$sql4="SELECT SecurityAmt as security_amt_i FROM tbldriver WHERE UID='$Driverid'";
		$qry4 = mysqli_query($this->con,$sql4);
		$row4 = mysqli_fetch_object($qry4);
		$security_amt_i=$row4->security_amt_i; 
		
		
		$sql1="SELECT total_tax_price as basic_tax_price FROM tblbookingcharges WHERE BookingID='$booking'";
		$qry1 = mysqli_query($this->con,$sql1);
		$row1 = mysqli_fetch_object($qry1);
		$basic_tax_price=$row1->basic_tax_price; 
		

		$sql2="SELECT tblcabbooking.booking_reference as book_ref FROM tblcabbooking WHERE id='$booking'";
		$qry2 = mysqli_query($this->con,$sql2);
		$row2 = mysqli_fetch_object($qry2);
		$book_ref=$row2->book_ref;
		
		$sql3="SELECT company_share as comp_share,driver_share as share_deducted,partner_share as partner_share_i FROM tblclient WHERE id=1;";
		$qry3 = mysqli_query($this->con,$sql3);
		$row3 = mysqli_fetch_object($qry3);
		$comp_share=$row3->comp_share;
		$share_deducted=$row3->share_deducted;
		$partner_share_i=$row3->partner_share_i;
		
		
		if($type=="cash"){
		$payment_type="CASH";
		$booking_status=11;
		$status_text="Debit";
		$reason_text="Deducted from Booking";
		
		$bill_deducted=((($amount-($extra+$basic_tax_price))*(100-$share_deducted))/100);		
		$totalAmount=$bill_deducted+$basic_tax_price;
		$Amount		=	$security_amt_i-$totalAmount;
			
		}else{
		$payment_type="CREDIT";
		$booking_status=12;
		$status_text="Credit";
		$reason_text="Added amount from Booking";
		
		$bill_deducted=((($amount-($extra+$basic_tax_price))*(100-$comp_share))/100);		
		// $totalAmount=$bill_deducted+$basic_tax_price;
		$totalAmount = $bill_deducted ;
		$Amount		=	$security_amt_i + $totalAmount;
		}
		
		/////1.Query to Update in tbldriver table Starts here ///
		$sqldriver ="UPDATE tbldriver SET  `SecurityAmt`='$Amount', `status`=0 WHERE UID='$Driverid'";
		mysqli_query($this->con,$sqldriver) or die(mysqli_error($this->con));
		/////1.Query to Update in tbldriver table Ends here ///
		
		/////2.Query to Update in tblcabbooking table Starts here ///
		$sqlcabbooking ="UPDATE tblcabbooking SET `status`='$booking_status' WHERE id='$booking'";
		mysqli_query($this->con,$sqlcabbooking) or die(mysqli_error($this->con));
		/////2.Query to Update in tblcabbooking table Ends here ///

		/////3.Query to Insert in Bookinglogs table Starts here ///
		$sqlbookinglogs = "INSERT INTO tblbookinglogs(`bookingid`,`status`,`time`)VALUES('$booking','$booking_status',NOW())";
		mysqli_query($this->con,$sqlbookinglogs) or die(mysqli_error($this->con));
		/////3.Query to Insert in Bookinglogs table Ends here ///
		
		/////4.Query to Update in tblbookingCharges table Starts here ///
		$sqlbookingcharges ="UPDATE tblbookingcharges SET is_paid=1,paid_at=NOW(),payment_type='$payment_type',totalBill='$amount' , partnershare='$partner_share_i', companyshare='$comp_share',drivershare='$share_deducted', extracharges='$extra'  WHERE BookingID='$booking'"; 
		mysqli_query($this->con,$sqlbookingcharges) or die(mysqli_error($this->con));
		/////4.Query to Update in tblbookingCharges table Ends here ///
		
		$sqlDriverTrans = "INSERT INTO tbl_driver_transaction (`user_id`,`amount`,`basic_tax_amount`,`currentbalance`,`status`,`booking_ref`,`reason`) VALUES ('$Driverid','$bill_deducted','$basic_tax_price','$Amount','$status_text','$book_ref','$reason_text')";
		mysqli_query($this->con,$sqlDriverTrans) or die(mysqli_error($this->con));
		
		
		$sql5="SELECT SecurityAmt FROM tbldriver WHERE UID='$Driverid'";
		$qry5 = mysqli_query($this->con,$sql5);
		$row5 = mysqli_fetch_object($qry5);
		$securityAmt=$row5->SecurityAmt;
		
		$result = array("securityAmt"=>$securityAmt,"totalbill_i"=>$amount,"security_amt_i"=>$security_amt_i);
		
		
		/// Code Done By Mohit Ends Here /////
		/*****************payment mail to user start***********/
		//**********Fetch Booking Details******	
		$sql = "SELECT * FROM tblcabbooking WHERE ID = '$booking'";
		$qry = mysqli_query($this->con,$sql);
		$row = mysqli_fetch_object($qry);
		$user_ID=$row->ClientID;          
		$Drvr_ID=$row->pickup;                    
		$EstimatedDistance=$row->EstimatedDistance;
		$EmailId=$row->EmailId;           
		$UserName=$row->UserName;                 
		$EstimatedTime=$row->EstimatedTime;
		$MobileNo=$row->MobileNo;         
		$PickupAddress=$row->PickupAddress;       
		$ReturnDate=$row->ReturnDate; 
		$DropAddress=$row->DropAddress;   
		$BookingDate=$row->BookingDate;           
		$PickupCity=$row->PickupCity;
		$PickupDate=$row->PickupDate;     
		$PickupTime=$row->PickupTime;             
		$DestinationCity=$row->DestinationCity;
		$waiting_charge=$row->approx_waiting_charge; 				
		$paid_amount=$amount;
		$due_balance=$amount-$paid_amount;
		//*********Fetch Driver Info***********
		$query = "SELECT * FROM tbluserinfo WHERE UID ='$Drvr_ID'";
		$qry11 = mysqli_query($this->con,$query);
		$row111 = mysqli_fetch_object($qry11);
		$Driveremail=$row111->Email;
		$DriverFirstName=$row111->FirstName;
		$DriverLastName=$row111->LastName;
		$DriverMobNo=$row111->MobNo;
		//*********Fetch Vehicle Details*****************
		$fetch = "select tblcabmaster.* from tblcabbooking inner join tbldriver on tblcabbooking.pickup = tbldriver.UID
		inner join tblcabmaster on tbldriver.vehicleId = tblcabmaster.CabId	where tblcabbooking.ID = '$booking'";
		$fetched = mysqli_query($this->con,$fetch);
		$result123 = mysqli_fetch_object($fetched);
		$vehicle_number=$result123->CabRegistrationNumber;
		$vehicle_name=$result123->CabName;
		$CabType=$result123->CabType;
		$quer = "SELECT * FROM tblcabtype WHERE Id = '$CabType'";
		$qry6 = mysqli_query($this->con,$quer);
		$cabyu = mysqli_fetch_object($qry6);
		$vechile_type=$cabyu->CabType;
		//*********Fetch User Info***********
		$query1 = "SELECT * FROM tbluserinfo WHERE UID = '$user_ID'";
		$qry96 = mysqli_query($this->con,$query1);
		$rowpp = mysqli_fetch_object($qry96);
		$userEml=$rowpp->Email;
		$usrFirstN=$rowpp->FirstName;
		$urLastName=$rowpp->LastName;
		$usrMobNo=$rowpp->MobNo;
		$usrCoty=$rowpp->Country;
		$userState=$rowpp->State;
		$userCity=$rowpp->City;
		$usAddrs1=$rowpp->Address1;
		$userAdd2=$rowpp->Address2;
		$usrPine=$rowpp->PinCode;
		mysqli_free_result($qry96);
		mysqli_next_result($this->con);
		//$this->send_sms_new1($booking,$flag="F");		
		//***********Mail****************************	
		$night_charges='';
		/* $search=array('{user_name}','{user_address}','{user_city}','{user_state}','{country}','{user_email}','{user_mobile}','{driver_name}',
		'{vehicle_number}','{driver_mobile}','{vehicle_name}','{driver_email}','{vehicle_type}','{booking_date}','{return_date}',
		'{pickup_address}','{pickup_date}','{pickup_time}','{pickup_city}','{drop_address}','{drop_city}','{estimated_distance}',
		'{estimated_time}','{waiting_charges}','{night_charges}','{road_tax}','{tool_tax}','{other_charges}','{total_fare}','{amount_paid}','{due_amount}');
		$replace=array($UserName,$usAddrs1.$userAdd2,$userCity,$userState,$usrCoty,$userEml,$usrMobNo,$DriverFirstName.$DriverLastName,
		$vehicle_number,$DriverMobNo,$vehicle_name,$Driveremail,$vechile_type,$BookingDate,$ReturnDate,
		$PickupAddress,$PickupDate,$PickupTime,$PickupCity,$DropAddress,$DestinationCity,$EstimatedDistance,
		$EstimatedTime,$waiting_charge,$night_charges,$road_tax,$toll_tax,$other_tax,$amount,$amount,$amount);	
		$template=str_replace($search,$replace,$body);  */			
		//$UserName = 'Maninder';		
		$template = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xmlns='http://www.w3.org/1999/xhtml'>
  <head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
  </head>
  <body style='font-style: normal; font-variant: normal; font-weight: normal; font-size: 14px; line-height: 1.4; font-family: Georgia, serif;'><style type='text/css'>
textarea:hover { background-color: #EEFF88 !important; }
textarea:focus { background-color: #EEFF88 !important; }
.itemsqqq td.total-value textarea:hover { background-color: #EEFF88 !important; }
.itemsqqq td.total-value textarea:focus { background-color: #EEFF88 !important; }
.delete:hover { background-color: #EEFF88 !important; }
&gt;</style>&#13;
	<div style='width: 800px; background-color: lightgray; margin: 0 auto;'>&#13;
		<div readonly='readonly' style='text-align: center; height: 15px; width: 100%; color: white; text-decoration: uppercase; letter-spacing: 20px; font-style: normal; font-variant: normal; font-weight: bold; font-size: 15px; line-height: normal; font-family: Helvetica, Sans-Serif; background-color: #222; margin: 20px 0; padding: 8px 0px;' align='center'>INVOICE</div>		&#13;
		<div>		&#13;
            <span style='width: 275px; height: 80px; float: left;'>&#13;
			<strong> $UserName</strong><br />&#13;
			 $usAddrs1.$userAdd2, $userCity<br />			&#13;
			 $userState, $usrCoty<br />&#13;
			 $userEml, $usrMobNo			&#13;
			</span>&#13;
            <div style='text-align: right; float: right; position: relative; margin-right: 85px; max-width: 540px; max-height: 100px; overflow: hidden;' align='right'>&#13;
              <img src='http://166.62.35.117/hello42/public/image/logo.png' alt='logo' /></div>&#13;
		</div>&#13;
		<div style='clear: both;'></div>&#13;
		<span style='font-weight: bold; font-size: 14px; padding-left: 335px;'>Vehicle Details</span>&#13;
		<div style='overflow: hidden;'>           &#13;
            <table style='border-collapse: collapse; margin-top: 1px; width: 799px;'><tr><td style='text-align: left; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Driver Name</td>&#13;
                    <td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $DriverFirstName.$DriverLastName </td>&#13;
					<td style='text-align: left; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Vehicle Number</td>&#13;
                    <td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $vehicle_number</td>&#13;
                </tr><tr><td style='text-align: left; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Driver Mobile No.</td>&#13;
                    <td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $DriverMobNo  </td>                    &#13;
					 <td style='text-align: left; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Vehicle Name</td>&#13;
                    <td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $vehicle_name  </td>&#13;
                </tr><tr><td style='text-align: left; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Driver Email</td>&#13;
                    <td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'>  $Driveremail </td> &#13;
					<td style='text-align: left; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Vehicle Type</td>&#13;
                    <td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $vechile_type </td>&#13;
                </tr></table></div>	&#13;
        <span style='font-weight: bold; font-size: 14px; padding-left: 335px;'>Booking Details</span>&#13;
		<div style='width: 800px;'>&#13;
			<table style='border-collapse: collapse; margin-top: 1px; width: 399px; float: left;'><tr><td style='text-align: left; width: 50%; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Booking Date</td>&#13;
							<td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $BookingDate </td>					&#13;
						</tr><tr><td style='text-align: left; width: 50%; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Return Date</td>&#13;
							<td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $ReturnDate </td>&#13;
						</tr><tr><td style='text-align: left; width: 50%; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Pickup Address</td>&#13;
							<td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $PickupAddress </td>&#13;
						</tr><tr><td style='text-align: left; width: 50%; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Pickup Date</td>&#13;
							<td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $PickupDate </td>&#13;
						</tr><tr><td style='text-align: left; width: 50%; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Pickup Time</td>&#13;
							<td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $PickupTime </td>&#13;
						</tr><tr><td style='text-align: left; width: 50%; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Pickup City</td>&#13;
							<td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $PickupCity </td>&#13;
						</tr><tr><td style='text-align: left; width: 50%; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Drop Address</td>&#13;
							<td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $DropAddress </td>					&#13;
						</tr><tr><td style='text-align: left; width: 50%; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Drop City</td>&#13;
							<td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $DestinationCity </td>					&#13;
						</tr><tr><td style='text-align: left; width: 50%; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Estimated Distance</td>&#13;
							<td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $EstimatedDistance </td>					&#13;
						</tr><tr><td style='text-align: left; width: 50%; background-color: #eee; padding: 5px; border: 1px solid black;' align='left' bgcolor='#eee'>Estimated Time</td>&#13;
							<td style='text-align: right; padding: 5px; border: 1px solid black;' align='right'> $EstimatedTime </td>&#13;
						</tr></table><table style='border-collapse: collapse; width: 397px; margin-left: 2px; float: left; border: 1px solid black;'><tr><td style='text-align: right; padding: 5px; border-color: black; border-style: solid; border-width: 1px 0 1px 1px;' align='right'>Waiting Charges : </td>&#13;
						  <td style='padding: 10px; border-color: black; border-style: solid; border-width: 1px 1px 1px 0;'><div><i></i>  $waiting_charge </div></td>&#13;
					  </tr><tr><td style='text-align: right; padding: 5px; border-color: black; border-style: solid; border-width: 1px 0 1px 1px;' align='right'>Night Charges : </td>&#13;
						  <td style='padding: 10px; border-color: black; border-style: solid; border-width: 1px 1px 1px 0;'><div><i></i>   $night_charges </div></td>&#13;
					  </tr><tr></tr><tr><td style='text-align: right; padding: 5px; border-color: black; border-style: solid; border-width: 1px 0 1px 1px;' align='right'>Road Tax : </td>&#13;
						  <td style='padding: 10px; border-color: black; border-style: solid; border-width: 1px 1px 1px 0;'><div><i></i>  $road_tax </div></td>&#13;
					  </tr><tr><td style='text-align: right; padding: 5px; border-color: black; border-style: solid; border-width: 1px 0 1px 1px;' align='right'>Toll Tax : </td>&#13;
						  <td style='padding: 10px; border-color: black; border-style: solid; border-width: 1px 1px 1px 0;'><div><i></i>   $toll_tax </div></td>&#13;
					  </tr><tr></tr><tr><td style='text-align: right; padding: 5px; border-color: black; border-style: solid; border-width: 1px 0 1px 1px;' align='right'>Other Charges : </td>&#13;
						  <td style='padding: 10px; border-color: black; border-style: solid; border-width: 1px 1px 1px 0;'><div><i></i>  $other_tax</div></td>&#13;
					  </tr><tr><td style='text-align: right; padding: 5px; border-color: black; border-style: solid; border-width: 1px 0 1px 1px;' align='right'><strong>Total Fare : </strong></td>&#13;
						  <td style='padding: 10px; border-color: black; border-style: solid; border-width: 1px 1px 1px 0;'><div><strong><i></i> $amount</strong></div></td>&#13;
					  </tr><tr><td style='text-align: right; padding: 5px; border-color: black; border-style: solid; border-width: 1px 0 1px 1px;' align='right'><strong>Amount Paid : </strong></td>&#13;
						  <td style='padding: 10px; border-color: black; border-style: solid; border-width: 1px 1px 1px 0;'><span><strong><i></i> $paid_amount</strong></span></td>&#13;
					  </tr><tr><td style='text-align: right; background-color: #eee; padding: 5px; border-color: black; border-style: solid; border-width: 1px 0 1px 1px;' align='right' bgcolor='#eee'><strong>Balance Due : </strong></td>&#13;
						  <td style='background-color: #eee; padding: 10px; border-color: black; border-style: solid; border-width: 1px 1px 1px 0;' bgcolor='#eee'><div><strong><i></i> $due_balance</strong></div></td>&#13;
					  </tr></table></div>&#13;
		<div style='text-align: center; width: 800px; margin-top: 20px;' align='center'>&#13;
			  <h5 style='text-transform: uppercase; font-style: normal; font-weight: normal; padding: 0 0 8px; margin: 0 0 8px; font-family: Helvetica, Sans-Serif; line-height: normal; font-size: 13px; font-variant: normal; letter-spacing: 10px; border-bottom-color: black; border-bottom-style: solid; border-bottom-width: 1px;'> </h5>&#13;
			  &#13;
			  <span><strong>Terms : </strong><small>NET 30 Days. Finance Charge of 1.5% will be made on unpaid balances after 30 days.</small></span>&#13;
		</div>	&#13;
	</div>	&#13;
</body>
</html>";			
		$from='info@hello42cab.com';
		$subject='Hello42 Booking Invoice';
		$body = 'Dear '.$UserName.',<br><br>Here is your payment Invoice.<br/>';	
		$body=$body.$template;			
		//$this->send_mail($EmailId,$from,$subject,$body); ///// Un-comment this ////
		/*****************End payment mail to user**************/
		return array('status'=>'true',"data"=>$result);
	}
	
	////////////////////// Cash Button Service Code Done By Mohit Jain Ends Here/////
	
	////////////////////// Update Security Amount Service Code Done By Mohit Jain Starts Here/////
	
	public function updateSecurityAmt(){
	$id=$_REQUEST['id'];
	//$id=2130;
	$sql="SELECT SecurityAmt as security_amt_i FROM tbldriver WHERE UID='$id'";
	$qry = mysqli_query($this->con,$sql);
	$row = mysqli_fetch_object($qry);
	
	if(mysqli_num_rows($qry)>0){
		$status="true";
		$securityAmt=$row->security_amt_i;
	}else{
		$status="false";
		$securityAmt=0;
	}
	return array("status"=>"true","securityAmt"=>$securityAmt);
	}
	
	////////////////////// Update Security Amount Service Code Done By Mohit Jain Ends Here/////

	////////////////// To check the SMS Templates Service Code Done By Mohit Jain Starts Here/////
	
	public function testSmsMohit(){
	$sql="SELECT * FROM tbl_sms_template WHERE msg_sku='accept'";	
	//echo $sql="CALL QuickLogin(12)";
	$res = mysqli_query($this->con,$sql);
	$msg_query=mysqli_fetch_array($res); 
	//print_r($msg_query); exit;
	echo $text=  urlencode($msg_query['message']);
	$mobile="9313504429";	
	file_put_contents("mssg.txt",$text);
	$url="http://push3.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$mobile&from=Helocb&dlrreq=true&text=$text&alert=1";
	$send=file_get_contents($url);
	}

	////////////////// To check the SMS Templates Service Code Done By Mohit Jain Ends Here/////
	
	function sendTestnotification() {
		$gcm_id="APA91bHRGpa1xoNv7_vGIIwagwxEAfOWLc88hqpHLt1YjvdkWUs6wqHSo6J8ofaE8YlfbDlt8kMekNmW8rcs42GWBtiMBLPPAZUhSwmzznHCV8_yo633TPs";
		//$msg="Hello";
		$registatoin_ids=array();
		$message=array();
		
		$registatoin_ids[]=$gcm_id;
		
		$message[]=$msg;
		// include config
		//include_once './config.php';
		//define("GOOGLE_API_KEY", "AIzaSyCOpOWLJG7G2PGHDb9uCl0eALEHYrPApVw");
		// Set POST variables
		//define("GOOGLE_API_KEY", "AIzaSyBcsz_BY5Yg03KnXCbhDwXgOlnwch9kGXE");
		define("GOOGLE_API_KEY", "AIzaSyAFE4jcZwQsi2LIx_2TfczrM3DlSTRpNYM");
		echo $url = 'https://android.googleapis.com/gcm/send';
		$fields = array('registration_ids' => $registatoin_ids, 'data' => array("message" => $message),);
		//echo "<pre>";print_r($fields);
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
	
	public function ctravelgeoloc($BookingId_i,$user_id)
	{		

	// for testing
	// public function ctravelgeoloc()
	// {		
	 	// $dis = new Admin();
		// for testing
		// $BookingId_i = 8532;
			// $user_id = 2130;


			$sql="select lat,longi,distance,datetime,( select SEC_TO_TIME( SUM( TIME_TO_SEC( `pre_Waiting_time` ) ) )  from tbldriverlocation where booking_id='$BookingId_i') as pwtym ,( select SEC_TO_TIME( SUM( TIME_TO_SEC( `WaitingTime` ) ) )  from tbldriverlocation where booking_id='$BookingId_i') as wtym   from tbldriverlocation where booking_id='$BookingId_i' order by id asc";
			$qry = $this->con->query($sql);
			$prewaiting = "";
			$waiting = "";

			while ($row = $qry->fetch_assoc()) {
				$rows[] = $row;
				$geoloc[] = $row['lat'].",".$row['longi'];
				// echo $row['pre_Waiting_time']."<br>";
				// $prewaiting = $prewaiting + strtotime($row['pre_Waiting_time']);
				// $waiting = $waiting + strtotime($row['WaitingTime']);
				// echo $prewaiting."<br>";
				$prewaiting = $row['pwtym'];
				$waiting = $row['wtym'];
			}

			$strtime = $rows[0];
			$strtime = $strtime['datetime'];

			$endtime = end($rows);
			$endTime = $endtime['datetime'];

			// print_r($endtime['datetime']);die;
			$prewaitym = $prewaiting;
			$waitingtym = $waiting;

			$dis1=0;
			for ($i=0; $i < count($geoloc)-1; $i++) { 
					$k = $i;
					$k = $k + 1;
					$geo1 = explode(",", $geoloc[$i]);
					$geo2 = explode(",", $geoloc[$k]);
	 			$dis = $this->getDistance($geo1[0],$geo1[1],$geo2[0],$geo2[1],"K"); 
	 			$dis1 = $dis1 + $dis;
			}
 			
 			$distance = round($dis1);			 
			$rqrdata = array(
					"waitingtym"=>$waitingtym,
					"prewaitym"=>$prewaitym,
					"strtTime" => $strtime,
					"endTime" => $endTime,
					"distance" => $distance,
				);
			return $rqrdata;
	}

	 

	public function extrchrgindvdl($data='')
	{
		$updextras = $data;
			$pos1 = strrpos($updextras, "ParkingCharge");
			if($pos1){ 
				$extraAmts = substr($updextras, $pos1);
						$arrex0 = explode(",",$extraAmts);
						foreach ($arrex0 as $key0 => $value0) {
							$val0 = explode("_", $value0);
							$str0[]= $val0[1];
						}
			}
			else {
				$str0 = array(0,0,0); 
			}			
			return $str0;
	}
	// calling edit fare in admin panel in action button 
	
	public function livebillgenadmin()
	{
			//print_r($_REQUEST['booking_id']);
			// for live	
			//$_REQUEST['booking_id'] = substr($_REQUEST['booking_id'], 4);
			
			if($_REQUEST['btnval']=="ReGenerate" || $_REQUEST['btnval']=="Save"){
			$BookingId_i = $_REQUEST['booking_id'];
			}else{
			$BookingId_i = substr($_REQUEST['booking_id'], 4);
			}
			$user_id  = $_REQUEST['driver_id'];
			$hour = $_REQUEST['hour'];
			$km = $_REQUEST['km'];
			$pre_wait = $_REQUEST['pre_wait'];
			$wait =  $_REQUEST['wait'];

			// print_r($_POST);die;
			// for testing
			// $BookingId_i = 8532;
			// $user_id = 2130;
			// $hour = 0;
			// $km = 0;
			// $pre_wait = 0;
			// $wait = 0;

			if($hour==0 && $km ==0 && $pre_wait ==0 &&  $wait ==0 )
			{
				//echo "Mohit";
				//echo "Jain";
				//for live
				//echo $BookingId_i;
				//echo $user_id;
				$rqdata = $this->ctravelgeoloc($BookingId_i,$user_id);
				
			// 	// for testing
			// 	// $rqdata = $this->ctravelgeoloc();
				extract($rqdata);

				$strtTime 			= 	$strtTime;
				$endTime			=	$endTime;
				$distance			=	$distance;
				
				// for live
				$delay_time			=	$waitingtym;
				$pre_Waiting_time	=	$prewaitym;

				// print_r($delay_time);die;

 			}
			else
			{
				//echo "Jain";
				// $rqdata = $this->ctravelgeoloc($BookingId_i,$user_id,"true");
				$sql="select ( select datetime from tbldriverlocation where booking_id='$BookingId_i' order by id asc limit 1) as strtTime ,
				( select datetime from tbldriverlocation where booking_id='$BookingId_i' order by id desc limit 1) as endTime   
				from tbldriverlocation where booking_id='$BookingId_i' and user_id = '$user_id' order by id asc limit 1";
			   $qry = $this->con->query($sql)->fetch_object();

				$strtTime 			= 	$qry->strtTime;
				$endTime			=	$qry->endTime;
				$distance			=	$km;
				$delay_time			=	$waitingtym =	$wait;
				$pre_Waiting_time	=	$prewaitym =   $pre_wait;
			}


			// print_r($strtTime);die;

			date_default_timezone_set('Asia/Kolkata');

 			$strtTimePeak=explode(" ",$strtTime);
			$delay_time=explode(':',$delay_time);
			$delay_time0=round($delay_time[0]*60);
			$delay_time1=round($delay_time[2]/60);
			$delay_time=$delay_time0+$delay_time1+$delay_time[1];		

			$pre_Waiting_time=explode(':',$pre_Waiting_time);
			$pre_Waiting_time0=round($pre_Waiting_time[0]*60);
			$pre_Waiting_time1=round($pre_Waiting_time[2]/60);
			$preWaitingtime=$pre_Waiting_time0+$pre_Waiting_time1+$pre_Waiting_time[1];

			$diff = strtotime($endTime) - strtotime($strtTime);
			$years = floor($diff / (365*60*60*24)); 
			$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
			$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			$total_trip_hours = floor(($diff)/ (60*60));

			$triphours = gmdate("H:i:s",$diff);
				// echo $triphours;die;
					

			$minuts=$diff/60;
			$total_trip_minutes=round($minuts); 
			$total_trip_minutes2=round($minuts);
			if($total_trip_minutes2>$minuts)
			{
			$total_trip_minutes2=$total_trip_minutes2;
			}else
			{
			$total_trip_minutes2=$total_trip_minutes2+1;
			}
			if($minuts>0){
			$total_trip_hours=$total_trip_hours+1;	
			}


			$sql2="SELECT MobileNo,BookingType,extras,BookingDate,CarType,pickup,booking_reference,local_subpackage,PickUpArea,DropArea FROM tblcabbooking WHERE ID='$BookingId_i'"; 
			$res =mysqli_query($this->con, $sql2) or die(mysqli_error($this->con));
			$data1 = $res->fetch_array();
			$BookingType=$data1['BookingType']; 
			$CarType=$data1['CarType'];
			$pickupid=$data1['pickup'];
			$BookingDate=$data1['BookingDate'];
			$booking_ref=$data1['booking_reference'];
			$CallerID = $data1['MobileNo'];



				//for extra charge calculations
			// if($_REQUEST['othrchrgs']==1){
			// // print_r($_POST);die;
			// 		$appndstr = ',"ParkingCharge_'.$_POST["Parking_Charge"].'_Rs","TollTax_'.$_POST["Toll_Taxe"].'_Rs","OtherCharge_'.$_POST["Other_Charge"].'_Rs"';
			// 		$updextras = $data1['extras'];
				
			// 	if(strrpos($updextras, "ParkingCharge")){
			// 		$pos1 = strrpos($updextras, "ParkingCharge");

			// 		$str = substr($updextras, 0,$pos1-2);
					
			// 	}
			// 	else {
			// 		// echo "nahi mila";

			// 		$str = $updextras;
			// 	}
				

			// 	$rqrdstrng = $str.$appndstr;

			// 	$updatextrachrgs = $this->con->query("update tblcabbooking set extras='$rqrdstrng' where ID=$BookingId_i");

			// 	// echo $rqrdstrng;
			// 	// die;
			// }


				$IsMatchedCabType=$this->IsCabtypeMatched($pickupid,$BookingId_i);
				if($IsMatchedCabType){
				$sql3="CALL wp_cabBookingCompleteBillNew('$BookingId_i','$BookingType')";
				}else{
				$sql3="CALL wp_cabBookingCompleteBillNotMatched('$BookingId_i','$BookingType')";
				}			
				$result = mysqli_query($this->con, $sql3) or die(mysqli_error($this->con));
				$data = mysqli_fetch_assoc($result);
				// echo "<pre>";print_r($result); 
				// die;
				$stateId	=	$data['state'];
				mysqli_free_result($result);   
				mysqli_next_result($this->con);

				$sql1 = "select Sub_Package_Id from tblmasterpackage WHERE Package_Id='$BookingType' AND state_id='$stateId'";
				$row1 = mysqli_query($this->con,$sql1) or die(mysqli_error($this->con));
				$res1 = mysqli_fetch_array($row1);
				$Sub_Package_Id	=	$res1['Sub_Package_Id'];

				//// Code to Fetch Data for Local(101) && Point to Point(102) Starts Here /////

				if($BookingType==101 || $BookingType==102){
				$cablocalfor	=	$data1['local_subpackage'];
				if($cablocalfor!=""){
				$sql = "SELECT * FROM `tbllocalpackage` WHERE Package='$cablocalfor' AND masterPKG_ID='$BookingType' AND Sub_Package_Id='$Sub_Package_Id' AND City_ID='$stateId' AND cabType='$CarType'"; //die;
				$row = mysqli_query($this->con,$sql);		
				$date = mysqli_fetch_object($row);
				$num_rows = mysqli_num_rows($row);
				//echo $Sub_Package_Id;	
				if($num_rows > 0){
				$cabfor	=	$date->Hrs;
				$Min_Distance = $date->Km;
				$MinimumChrage=$date->Price;		
				}}else{	
				if($Sub_Package_Id == 1){
				$Min_Distance = $data['Min_Distance'];
				$MinimumChrage = $data['MinimumCharge'];
				}else if($Sub_Package_Id == 2){
				$cabfor	=	$data['CabFor'];
				$MinimumChrage = $data['minimum_hourly_Charge'];
				}else if($Sub_Package_Id == 3){
				$cabfor	=	$data['ignore_first_hrs_dh'];
				$Min_Distance = $data['minimum_distance_dh'];
				$MinimumChrage = $data['minimum_fare_dh'];
				}else if($Sub_Package_Id == 4){	
				$Min_Distance = $data['minimum_distance_dw'];
				$MinimumChrage = $data['minimum_fare_dw'];
				}
				}
				}

				//// Code to Fetch Data for Local(101) && Point to Point(102) Ends Here /////

				//// Code to Fetch Data for Airport(103) Starts Here /////
				if($BookingType==103){		
				$PickAreaName		=	$data1['PickUpArea']; 
				$DropAreaName		=	$data1['DropArea'];
				$sqlAir	="SELECT * FROM `tblairportaddress` WHERE `Fix_Point`='$PickAreaName' LIMIT 1"; 
				$sqlAir	=mysqli_query($this->con,$sqlAir);	
				$row=mysqli_fetch_assoc($sqlAir);
				$num_rows1 = mysqli_num_rows($sqlAir);
				if($num_rows1>0){
				$fixpoint		=	$PickAreaName;
				}
				else{
				$fixpoint		=	$DropAreaName;
				}	

				//$sql = "SELECT * FROM `tblairportaddress` WHERE Address='$address' AND Fix_Point='$fixpoint' AND masterPKG_ID='$BookingType' AND Sub_Package_Id='$Sub_Package_Id' AND cityId='$stateId' AND cabType='$CarType'"; //die;
				$sql = "SELECT * FROM `tblairportaddress` WHERE '$address' LIKE concat('%',`Address`,'%') AND '$fixpoint' like concat('%',`transfer_type`,'%') AND masterPKG_ID='$BookingType' AND Sub_Package_Id='$Sub_Package_Id' AND cityId='$stateId' AND cabType='$CarType' LIMIT 1"; 
				$row1 = mysqli_query($this->con,$sql);		
				$date = mysqli_fetch_object($row1);
				$num_rows1 = mysqli_num_rows($row1);

				/////////////////////////////

				if($num_rows1 == 0){
				if($Sub_Package_Id == 1){
				$Min_Distance = $data['Min_Distance'];
				$MinimumChrage = $data['MinimumCharge'];
				}else if($Sub_Package_Id == 2){
				$cabfor	=	$data['CabFor'];
				$MinimumChrage = $data['minimum_hourly_Charge'];
				}else if($Sub_Package_Id == 3){
				$cabfor	=	$data['ignore_first_hrs_dh'];
				$Min_Distance = $data['minimum_distance_dh'];
				$MinimumChrage = $data['minimum_fare_dh'];
				}else if($Sub_Package_Id == 4){	
				$Min_Distance = $data['minimum_distance_dw'];
				$MinimumChrage = $data['minimum_fare_dw'];
				}
				}else{			
				$qry = "select (select distinct Km from tblairportaddress where Km<='$distance' order by Km desc limit 1) as MinKm,
				(select Fare from tblairportaddress where Km<='$distance' order by Km desc limit 1) as MinFare,
				(select distinct Km from tblairportaddress where Km>='$distance' order by Km Asc limit 1) as MaxKm,
				(select Fare from tblairportaddress where Km>='$distance' order by Km Asc limit 1) as MaxFare from tblairportaddress limit 1";
				$result1 = mysqli_query($this->con, $qry);
				$info = mysqli_fetch_assoc($result1);
				$min_diff = $distance - $info['MinKm'];
				$max_diff =$info['MaxKm'] - $distance;
				$diff_distance = $info['MaxKm']-$info['MinKm'];
				if(($diff_distance/2) < $max_diff)
				{
				$cabfor	=	0;
				$Min_Distance = $info['MinKm'];
				$MinimumChrage=$info['MinFare'];	
				}
				else
				{
				$cabfor	=	0;
				$Min_Distance = $info['MaxKm'];
				$MinimumChrage=$info['MaxFare'];
				}	
				}
				}
				//// Code to Fetch Data for Airport(103) Ends Here /////



				//echo $MinimumChrage; die;
				//echo "<pre>";print_r($data);//die;
				//$MinimumChrage = $data['minimum_fare_dw']; 
				$book_ref = $data['booking_reference'];
				$book_ref	= "IN-".$book_ref;
				$WaitingCharge = $data['WaitingCharge_per_minute'];
				$tripCharge_per_hour = $data['tripCharge_per_minute'];
				//$Per_Km_Charge = $data['rate_per_km_dw']; 
				//$NightCharges = explode(" ", $NightCharges);
				$NightChargesAmount = $data['NightCharges']; 
				$NightChargesUnit = $data['nightCharge_unit'];
				$speed_per_km = $data['speed_per_km'];
				$located_time = $data['Date_Timei'];
				$PickupTime = $data['PickTime'];
				$pickUpdate = $data['PickDate'];
				//$Min_Distance = $data['minimum_distance_dw']; 
				$configPackageNo = $data['configPackageNo'];
				$DropPlace = $data['DropPlace'];
				$userEmailId = $data['userEmailId'];
				//$cabfor = $data['CabFor'];
					
				//$BookingType = $data['BookingType'];
				$basic_tax = $data['basic_tax'];
				$basic_tax_type			=	$data['basic_tax_type'];
				$night_rate_begins = $data['night_rate_begins'];
				$night_rate_ends = $data['night_rate_ends'];
				$waiting_fees = explode('_',$data['waiting_fees']); //30_0_10
				$Waitning_minutes = $waiting_fees[0];//30
				$WaitingBiforeCharge = $waiting_fees[1]; //0
				$WaitingAfterCharge = $waiting_fees[2]; //10

				$NightEndDate = date('Y-m-d', strtotime($pickUpdate . " +1 day"));
				$nightStartUpdateTime = $pickUpdate." ".$night_rate_begins; // 2014-11-20 	00:00:00
				$nightEndUpdateTime = $NightEndDate." ".$night_rate_ends; // 2014-11-20 	00:00:00
				$startNightTimeUnix = strtotime($nightStartUpdateTime);
				$endNightTimeUnix = strtotime($nightEndUpdateTime);
				$strtTimeUnix = strtotime($strtTime);
				$endTimeUnix = strtotime($endTime);
				$located_timeUnix = strtotime($located_time);
				// echo date("h:i:s",$strtTimeUnix);die;
				///IF calculation Type is Distance-wise Starts here ///
				// echo  $MinimumChrage."dfgdfg";die;

				if($data['configPackageNo']==1){
				$Min_Distance = $Min_Distance;
				$Per_Km_Charge = $data['Per_Km_Charge'];
				$MinimumChrage = $MinimumChrage;
				if($distance > $Min_Distance){
				$ExtraKM=$distance - $Min_Distance;
				$ExtraFare = $ExtraKM*$Per_Km_Charge;
				$TotalBill = $ExtraFare + $MinimumChrage;
				}else{
				$TotalBill = $MinimumChrage;		 								
				}
				$TripCharge = $TotalBill;
				}
				//die;
				//echo $TotalBill; die;
				///IF calculation Type is Distance-wise Ends here ///

				///IF calculation Type is Hourly Starts here ///

				elseif($data['configPackageNo'] == 2){
				//$totalmint=$distance*60/40;
				//$total_trip_minutes2=250;
				$totalmint=$total_trip_minutes2;
				$ignore_first_hours=$cabfor*60; //die;
				$MinimumChrage = $MinimumChrage;
				if($totalmint > $ignore_first_hours){
				$rest_min=$totalmint-$ignore_first_hours;
				$ExtraFare=($rest_min/60)*$data["tripCharge_per_minute"];
				$ExtraFareRound=round($ExtraFare);
				if($ExtraFareRound>$ExtraFare){
				$ExtraFare=$ExtraFareRound;
				}else{
				$ExtraFare=$ExtraFareRound+1;
				}
				$TotalBill = $ExtraFare + $MinimumChrage;
				}else{
				$TotalBill = $MinimumChrage;
				}
				$TripCharge = $TotalBill;
				$Per_Km_Charge=round($data["tripCharge_per_minute"]/60);
				$Per_Hr_Charge=$data["tripCharge_per_minute"];
				}

				///IF calculation Type is Hourly Ends here ///	

				///IF calculation Type is Distance+Hour Starts here ///

				elseif($data['configPackageNo'] == 3){
				$ignore_hrs		=	$cabfor*60;
				$Min_Distance 	=	$Min_Distance;
				$MinimumChrage 	=	$MinimumChrage;
				if($distance < $Min_Distance){
				$distanceRate=0;
				}
				else{
				$distanceRate= ($distance - $Min_Distance)*$data["rate_per_km_dh"];
				}
				if($total_trip_minutes < $ignore_hrs){
				$hourlyRate=0;		
				}else{					
				$hourlyRate=$total_trip_minutes-$ignore_hrs;
				$rate_per_min=$data["rate_per_hour_dh"]/60;
				$hourlyRate=$hourlyRate*$rate_per_min;

				}	
				$TotalBill = $distanceRate+$hourlyRate+$MinimumChrage;
				$Per_Km_Charge=$data["rate_per_km_dh"];
				$Per_Hr_Charge=$data["rate_per_hour_dh"];
				$TripCharge = $TotalBill;			
				}

				//

				///IF calculation Type is Distance+Hour Ends here ///

				///IF calculation Type is Distance+Waiting Starts here ///

				elseif($data['configPackageNo'] == 4)
				{		
				$Min_Distance = $Min_Distance;
				$Per_Km_Charge = $data['rate_per_km_dw'];
				$MinimumChrage = $MinimumChrage;
				if($distance > $Min_Distance){
				$ExtraKM=$distance - $Min_Distance;
				$ExtraFare = $ExtraKM*$Per_Km_Charge;
				$TotalKMBill = $ExtraFare + $MinimumChrage;
				}else{
				$TotalKMBill = $MinimumChrage;								
				}

				$waiting_charges='0';
				if($delay_time > $Waitning_minutes){
				$waiting_charges = ($delay_time - $Waitning_minutes)*$WaitingAfterCharge;
				/*if($exactMinute < 10){
				$waiting_charges = $exactMinute*$WaitingBiforeCharge;
				}else{
				$waiting_charges = $exactMinute*$WaitingAfterCharge;
				}*/
				}else{
				$waiting_charges = 0;	
				}

				$TotalBill = $TotalKMBill+$waiting_charges;
				$TripCharge = $TotalBill;
				}
				//die;
				//echo $TotalBill; die;
				///IF calculation Type is Distance+Waiting Ends here ///

				///// PeakTime Charges //////
				$runningbill = $TotalBill;	
				$PeakFare	=	$this->calculatePeakTimeCharges($TotalBill,$strtTimePeak[1]);
				$TotalBill	=	$TotalBill + $PeakFare['peakcharge'];
				
				$peakTimePrice			=	round($PeakFare['peakcharge']);
				$peaktimeFrom			=	$PeakFare['peaktimeFrom'];
				$peaktimeTo				=	$PeakFare['peaktimeTo'];
				$peaktimepercentage		=	$PeakFare['peakpercentage'];
				///// PeakTime Charges //////

				///// Night Charges //////

				$menuobj = new BookingCabFare();
				$NightCharges = $menuobj->calculateNigthCharges($PickupTime,$night_rate_begins,$night_rate_ends,$NightChargesUnit,$NightChargesAmount,$TotalBill);
				// echo $nightchrgmenu;die;
				$TotalBill=$TotalBill+$NightCharges;
				///// Night Charges //////

				///// Waiting Fees //////
				/*This will not used in admin side /// it comes from hello42/admin/fairmanage-> basic tab (Waiting Time Fees 
				all fields are not used any where in admin as welll as booking)*/

				/*if($delay_time!=0){
				$waitingfee_upto_minutes = $data['waitingfee_upto_minutes'];
				$totalwaitingValue	=	$this->calculatePreWaitCharges($delay_time,$waitingfee_upto_minutes);
				$TotalBill=$TotalBill+$totalwaitingValue;
				}*/

				///// Waiting Fees //////
				//echo $preWaitingtime; die;
				///// Pre Waiting Fees //////
				if($preWaitingtime!=0){
				$prewaitingfee_upto_minutes = $data['prewaitingfee_upto_minutes'];
				$totalPrewaitingValue	=	$this->calculatePreWaitCharges($preWaitingtime,$prewaitingfee_upto_minutes);
				if($totalPrewaitingValue==""){
				$totalPrewaitingValue	=	0;
				}else{
				$totalPrewaitingValue	=	$totalPrewaitingValue;	
				}
				$TotalBill=$TotalBill+$totalPrewaitingValue; 
				}
				// echo $data['extras'];
				// for testing
				// print_r(expression)
				if($_POST['extrachrgs']!="0"){
				// 	$data['extras'] = $this->con->query("select extras from tblcabbooking where ID=$BookingId_i")->fetch_assoc()['extras'];
				// 	// echo "b";
					
				// }
				// else {
					$prevstr = array("\r\n"," ","[","]","=",'""','Rs','%');
					$newstr = array("","","","","_",'","','_Rs','_%');
					// echo ;
					$extrchrgstr = str_replace($prevstr,$newstr,$_REQUEST['extrachrgs']);
					// $extrchrgstr = str_replace(' ','_',str_replace(' = ','_',str_replace('""','","',trim($_REQUEST['extrachrgs']," "))));
					$data['extras'] = $extrchrgstr;

					// echo $data['extras'];
					// die;
					}
					
				$str0 = $this->extrchrgindvdl($data['extras']);

				// print_r(str_replace(" ","_",str_replace(" = ","_",trim($_REQUEST['extrachrgs']," "))));
					// $arrs = str_replace(' ','_',str_replace(' = ','_',str_replace('""','","',trim($_REQUEST['extrachrgs']," "))));
				// print_r(rtrim($arrs,"_"));
				// die;
				// $arrex = explode(",",json_decode($data['extras']));
				$arrex = explode(",",$data['extras']);
				foreach ($arrex as $key => $value) {
						$val = explode("_", $value);
						$str1 .= $val[0]." = ".$val[1]." ".$val[2];
				}
				$extrachrgText = $str1;
					// print_r( $extrachrgText);	die;
				///// Pre Waiting Fees //////
				
				///// Extra Charges //////
				$extraPrice	=	$this->calculateExtraCharges($TotalBill,$data['extras']);
					
				// echo $extraPrice;die;
				$TotalBill	=	$TotalBill + $extraPrice;
				
				$extras					=	str_replace(array('[',']'),'',$data['extras']);
				$extraPrice				=	round($extraPrice);
				///// Extra Charges //////			

				$Totalchargesbill= $TotalBill;

				$basic_tax_price = (($TotalBill * $basic_tax) / 100);
				$basic_tax_price = round($basic_tax_price);
				$TotalBill=$TotalBill+$basic_tax_price;
				$TotalBill=round($TotalBill);
				$TotalBill=$TotalBill>$total_amount?$TotalBill:$total_amount;

				$TotalBill			=	$TotalBill;
				$tripCharge			=	$TripCharge;
				if($waiting_charges==""){
				$waiting_charges	=	0;
				}else{
				$waiting_charges	=	$waiting_charges;	
				}
				//echo $TotalBill; die;
				$TotalBill				=	round($TotalBill);
				if($TotalBill!=""){
				$TotalBill=$TotalBill;
				}else{
				$TotalBill=0;
				}
				
				if($_POST['btnval'] == "Save"){
				$sql="Select * from tblbookingcharges WHERE BookingID='$BookingId_i'";	
				$sql_res =	mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
				if(mysqli_num_rows($sql_res)>0){
				$result=mysqli_fetch_array($sql_res);
				$BookingID_old					=	$result['BookingID'];
				$waitingCharge_old			=	$result['waitingCharge'];
				$tripCharge_old					=	$result['tripCharge'];				
				$minimumCharge_old		=	$result['minimumCharge'];				
				$totalBill_old						=	$result['totalBill'];			
				$AddedTime_old				=	$result['AddedTime'];				
				$is_paid_old						=	$result['is_paid'];
				$paid_at_old						=	$result['paid_at'];
				$currency_old					=	$result['currency'];
				$invoice_number_old			=	$result['invoice_number'];
				$payment_type_old			=	$result['payment_type'];
				$fees_old							=	$result['fees'];
				$total_price_old					=	$result['total_price'];
				$total_tax_price_old			=	$result['total_tax_price'];				
				$distance_rate_old				=	$result['distance_rate'];
				$duration_rate_old				=	$result['duration_rate'];
				$starting_rate_old				=	$result['starting_rate'];
				$base_price_old				=	$result['base_price'];
				$tax_price_old					=	$result['tax_price'];
				$starting_charge_old			=	$result['starting_charge'];				
				$distance_charge_old		=	$result['distance_charge'];
				$duration_charge_old		=	$result['duration_charge'];	
				$minimum_distance_old		=	$result['minimum_distance'];				
				$cancellation_price_old		=	$result['cancellation_price'];
				$companyshare_old			=	$result['companyshare'];
				$drivershare_old				=	$result['drivershare'];
				$partnershare_old				=	$result['partnershare'];
				$minimum_price_old			=	$result['minimum_price'];
				$extracharges_old				=	$result['extracharges'];				
				$nightcharge_unit_old		=	$result['nightcharge_unit'];
				$nightcharge_old				=	$result['nightcharge'];
				$nightcharge_price_old		=	$result['nightcharge_price'];
				$night_rate_begins_old		=	$result['night_rate_begins'];
				$night_rate_ends_old			=	$result['night_rate_ends'];
				$extras_old						=	$result['extras'];
				$extraPrice_old					=	$result['extraPrice'];
				$peakTimePrice_old			=	$result['peakTimePrice'];
				$peaktimeFrom_old			=	$result['peaktimeFrom'];
				$peaktimeTo_old				=	$result['peaktimeTo'];				
				$peaktimepercentage_old	=	$result['peaktimepercentage'];
				$basic_tax_old					=	$result['basic_tax'];
				$basic_tax_type_old			=	$result['basic_tax_type'];
				$basic_tax_price_old			=	$result['basic_tax_price'];
				$pre_waiting_time_old		=	$result['pre_waiting_time'];
				$pre_waiting_charge_old	=	$result['pre_waiting_charge'];
				$waiting_time_old				=	$result['waiting_time'];
				$waiting_charge_old			=	$result['waiting_charge'];
				
				$sqlbookingChargeshistory="INSERT INTO tblbookingchargeshistory (BookingID, waitingCharge, tripCharge, minimumCharge, totalBill, AddedTime, is_paid, paid_at, 
currency, invoice_number, payment_type, fees, total_price, total_tax_price, distance_rate, duration_rate, starting_rate, base_price, tax_price, 
starting_charge, distance_charge, duration_charge, minimum_distance, cancellation_price, companyshare, drivershare, partnershare, minimum_price, 
extracharges, nightcharge_unit, nightcharge, nightcharge_price, night_rate_begins, night_rate_ends, extras, extraPrice, peakTimePrice, peaktimeFrom, 
peaktimeTo, peaktimepercentage, basic_tax, basic_tax_type, basic_tax_price, pre_waiting_time, pre_waiting_charge, waiting_time, waiting_charge)VALUES('$BookingID_old',
'$waitingCharge_old','$tripCharge_old','$minimumCharge_old','$totalBill_old','$AddedTime_old','$is_paid_old','$paid_at_old','$currency_old',
'$invoice_number_old','$payment_type_old','$fees_old','$total_price_old','$total_tax_price_old','$distance_rate_old','$duration_rate_old','$starting_rate_old',
'$base_price_old','$tax_price_old','$starting_charge_old','$distance_charge_old','$duration_charge_old','$minimum_distance_old','$cancellation_price_old',
'$companyshare_old','$drivershare_old','$partnershare_old','$minimum_price_old','$extracharges_old','$nightcharge_unit_old','$nightcharge_old','$nightcharge_price_old',
'$night_rate_begins_old','$night_rate_ends_old','$extras_old','$extraPrice_old','$peakTimePrice_old','$peaktimeFrom_old','$peaktimeTo_old','$peaktimepercentage_old',
'$basic_tax_old','$basic_tax_type_old','$basic_tax_price_old','$pre_waiting_time_old','$pre_waiting_charge_old','$waiting_time_old','$waiting_charge_old')";
				mysqli_query($this->con,$sqlbookingChargeshistory) or die(mysqli_error($this->con));

				// Update QUERY will EXECUTE ///
				$sqlbookingCharges="UPDATE tblbookingcharges SET  tripCharge='$tripCharge', waitingCharge='$waiting_charges',minimumCharge='$MinimumChrage', 
				AddedTime=NOW(), distance_rate='$Per_Km_Charge', distance_charge='$tripCharge', minimum_distance='$Min_Distance', 
				total_tax_price='$basic_tax_price', currency='RS', invoice_number='$book_ref', total_price= '$TotalBill', tax_price='$basic_tax_price', starting_rate='$Per_Km_Charge',
				starting_charge='$MinimumChrage', duration_charge='$Min_Distance', minimum_price='$MinimumChrage', nightcharge_unit='$NightChargesUnit',
				nightcharge='$NightCharges', nightcharge_price='$NightChargesAmount', night_rate_begins='$night_rate_begins', night_rate_ends='$night_rate_ends',
				extras='$extras', extraPrice='$extraPrice', peakTimePrice='$peakTimePrice', peaktimeFrom='$peaktimeFrom',peaktimeTo='$peaktimeTo',
				peaktimepercentage='$peaktimepercentage', basic_tax='$basic_tax', basic_tax_type='$basic_tax_type', basic_tax_price='$basic_tax_price',
				pre_waiting_time='$prewaitym', pre_waiting_charge='$pre_waiting_charge', waiting_time='$delay_time', waiting_charge='$WaitingCharge'  
				WHERE BookingID='$BookingId_i'";
				$bokchrgsst = mysqli_query($this->con,$sqlbookingCharges) or die(mysqli_error($this->con));
				}else{
				
				// Insert QUERY will EXECUTE ///
				
				$sqlbookingCharges="INSERT INTO tblbookingcharges (tripCharge,waitingCharge,minimumCharge,BookingID,AddedTime,distance_rate,distance_charge,
				minimum_distance,total_tax_price,currency,invoice_number,total_price,tax_price,starting_rate,starting_charge,duration_charge,minimum_price,nightcharge_unit,
				nightcharge,nightcharge_price,night_rate_begins,night_rate_ends,extras,extraPrice,peakTimePrice,peaktimeFrom,peaktimeTo,peaktimepercentage,basic_tax,
				basic_tax_type,basic_tax_price,pre_waiting_time,pre_waiting_charge,waiting_time,waiting_charge)VALUES('$tripCharge','$waiting_charges',
				'$MinimumChrage','$BookingId_i',NOW(),'$Per_Km_Charge','$tripCharge','$Min_Distance','$basic_tax_price','RS','$book_ref','$TotalBill',
				'$basic_tax_price','$Per_Km_Charge','$MinimumChrage','$Min_Distance','$MinimumChrage','$NightChargesUnit','$NightCharges','$NightChargesAmount',
				'$night_rate_begins','$night_rate_ends','$extras','$extraPrice','$peakTimePrice','$peaktimeFrom','$peaktimeTo','$peaktimepercentage','$basic_tax',
				'$basic_tax_type','$basic_tax_price','$prewaitym','$pre_waiting_charge','$delay_time','$WaitingCharge')";
				$bokchrgsst = mysqli_query($this->con,$sqlbookingCharges) or die(mysqli_error($this->con));	
				}

			if($bokchrgsst){
			
				$sqlbookinglogs = "INSERT INTO tblbookinglogs(bookingid,`status`,message,`time`)VALUES('$BookingId_i',8,'Completed',NOW())";
				mysqli_query($this->con,$sqlbookinglogs) or die(mysqli_error($this->con));
				
				$sqlcabbooking ="UPDATE tblcabbooking SET `status`='8',actual_distance='$distance',actual_driven_distance='$distance',
				expiration_time=NOW() WHERE id='$BookingId_i'"; 
				mysqli_query($this->con,$sqlcabbooking) or die(mysqli_error($this->con));				
				$nexstep = 1;
				$msg = 'Changes Successfully saved';
				$bookRef = substr($data['booking_reference'], 0,2);
				if($bookRef=="HC"){
				$book_ref=$data['booking_reference'];
				$ReasonText = "Bill has been generated from admin";
				$this->con->query("insert into  tblagent_work_history (AgentID,CallerID,BookingID,Actiontype,tblagent_work_history.Date,ActionDesc) values ((select CSR_ID from tblcabbooking where booking_reference='$book_ref'),'$CallerID' ,'$book_ref', 'BillGenerated',Now(),'$ReasonText')");
				}
			}
			else $msg = 'Some internal error occured while saving your changes.Try again later';	
			}
			else {
				$msg = 0;
				$nexstep = 0;
			}
				ob_start();
    			require("faretemp.phtml");
    			$abc = ob_get_clean();

				$response = array("status"=>'true',"totalbill"=>$abc,"message"=>$msg,"nexstep"=>$nexstep);

				header("contentType:application/json");
				echo json_encode($response); die;

	}
	
	//// FUnction to update IMEI NOo in tbl_driver at the time of login starts by Mohit Jain
	
	public function updateimei(){
		$id=$_GET['ID'];
		$imei=$_GET['imei'];
		if($id!=""){
			$sql = "update tbldriver set imei='$imei' where UID='$id'";
		    $result = mysqli_query($this->con, $sql) or die(mysqli_error($this->con));
			if($result>0){
				return array('status'=>'true');
			}else{
				return array('status'=>'false');
			}
		}else{
			return array('status'=>'false');
		}
		
	}
	
	//// FUnction to update IMEI NOo in tbl_driver at the time of login starts by Mohit Jain
	
	/// Function to fetch the User Role Type Starts Here By Mohit Jain
	
	public function getUserRoleType(){
	$result = mysqli_query($this->con,"SELECT * FROM tbl_role_type order by name ASC");	 
	 if(mysqli_num_rows($result) < 1 ){
		return array("Status" => "Failed");
	}else{		
		$i=0;
		$record=array();
		while($res=mysqli_fetch_array($result)){
			$record[$i]["id"]=$res["id"];
			$record[$i]["name"]=$res["name"];
		$i++;	
		}		
	}	
	return array("Status" => "Success","record"=>$record);
	}
	
	/// Function to fetch the User Role Type Ends Here By Mohit Jain
	
	/// Function to fetch the User Role Starts Here By Mohit Jain
	
	public function getUserRole(){
		$usertype_id=$_REQUEST["usertype_id"];
	   $result = mysqli_query($this->con,"SELECT * FROM tbluserrole WHERE role_type='$usertype_id' AND IsActive=1 order by RoleName ASC");	 
	 if(mysqli_num_rows($result) < 1 ){
		return array("Status" => "Failed");
	}else{		
		$i=0;
		$record=array();
		while($res=mysqli_fetch_array($result)){
			$record[$i]["id"]=$res["Role_ID"];
			$record[$i]["role_name"]=$res["RoleName"];
		$i++;	
		}		
	}    
	return array("Status" => "Success","record"=>$record);
	}
	
	/// Function to fetch the User Role Ends Here By Mohit Jain
	
	// Function to Regenerate OTP sent on Mobile Starts Here By Mohit Jain 
	
	public function resendOTP(){
	$mobile_no=$_REQUEST["mobile_no"];
	$otp=array();
	if($mobile_no!=""){
	$sql="SELECT ID FROM tbluser WHERE UserNo='$mobile_no' AND isVerified!=1 AND UserType=3";
	$result = mysqli_query($this->con,$sql);	 
	if(mysqli_num_rows($result) < 1 ){
	$status="false";
	$otp="";
	}else{
	$res=mysqli_fetch_array($result);
	$status="true";
	$UID=$res['ID'];
	
	$sql1="SELECT Verification_code FROM tblactivation WHERE UID='$UID' AND isUsed=0";
	$result1 = mysqli_query($this->con,$sql1);
	$res1=mysqli_fetch_object($result1);
	$otp=$res1->Verification_code;
	
	$sql2="SELECT FirstName,LastName FROM tbluserinfo WHERE UID='$UID'";
	$result2 = mysqli_query($this->con,$sql2);
	$res2=mysqli_fetch_object($result2);
	$driverName=$res2->FirstName.' '.$res2->LastName;
	
	$code="SELECT * FROM tbl_sms_template WHERE msg_sku='acc_verify_code'";
	$res_code = mysqli_query($this->con,$code);
	$msg_query_code=mysqli_fetch_array($res_code); 
	$array1=explode('<variable>',$msg_query_code['message']);
	$array1[0]=$array1[0].$driverName;
	$array1[1]=$array1[1].$otp;
	$array1[2]='http://hello42cab.com';
	$text=  urlencode(implode("",$array1));	
	$url="http://push3.maccesssmspush.com/servlet/com.aclwireless.pushconnectivity.listeners.TextListener?userId=helloalt&pass=helloalt&appid=helloalt&subappid=helloalt&msgtype=1&contenttype=1&selfid=true&to=91$mobile_no&from=Helocb&dlrreq=true&text=$text&alert=1";
	file_get_contents($url);
	mysqli_free_result($res_code);   
	mysqli_next_result($this->con);
	
	}
	}
	
	return array("status" => $status,"otp"=>$otp);
	} 

	// Function to Regenerate OTP sent on Mobile Ends Here By Mohit Jain 
	
	// Function to send the status of driver whether he is Hired or Free Starts Here By Mohit Jain //
	
	function checkDriverStatus() {	
	$id=$_REQUEST['id'];
	$sql="SELECT status as driverStatus FROM tbldriver WHERE UID='$id'"; 
	$result=mysqli_query($this->con,$sql);
	$res=mysqli_fetch_array($result);
	if($res['driverStatus']==1){
	$driverStatus="true";
	$msg="hired";
	}else{
	$driverStatus="false";
	$msg="free";
	}
	return array('status'=>$driverStatus,'msg'=>$msg);
	}
	
	// Function to send the status of driver whether he is Hired or Free Ends Here By Mohit Jain //
	
	
	function sendTestMail(){
	$message = new \Zend\Mail\Message();
	$message->setBody('This is the body');
	$message->setFrom('support@hello42cab.com');
	$message->addTo('php@hello42cab.com');
	$message->setSubject('Test mail1');

	$smtpOptions = new \Zend\Mail\Transport\SmtpOptions();  
	$smtpOptions->setHost('regency.rapidns.com')
	->setConnectionClass('login')
	->setName('regency.rapidns.com')
	->setConnectionConfig(array(
	'username' => 'support@hello42cab.com',
	'password' => 'support@987',
	//'ssl' => 'tls',
	'port'=> 465,
	));

	$transport = new \Zend\Mail\Transport\Smtp($smtpOptions);
	$transport->send($message);
	}
}
