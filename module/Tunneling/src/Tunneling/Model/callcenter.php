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

class Callcenter{
	
	protected $con;
	private $data = array();
	private $row = array();
	public $base_url = '';
	
	public function __construct(){
		//error_reporting(E_ALL);
		date_default_timezone_set("Asia/Kolkata");
		$key = '';
		$this->con=mysqli_connect("10.0.0.35","root","root","hello42_new");
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
		define('base_url', site_url.'/');
		
		$this->base_url = "http://".$_SERVER['HTTP_HOST'].'/hello42/';
		
	}
	
	public function test(){
		
		$sql = "CALL sp_getBookingFilterNew('9891735121', 'null', '5/3/2015 3:47:56', '7/3/2015 3:47:56', 'null', 'null')";
		$qry = mysqli_query($this->con, $sql) or die(mysqli_error());
		while($row as mysqli_fetch_object($qry)){
			array_push($data, $row);
		}
		return $data;
	}
	
}