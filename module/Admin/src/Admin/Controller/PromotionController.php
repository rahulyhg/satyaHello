<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class PromotionController extends AbstractActionController{
	
	public $con = '';
	private $data = array();
	
	public function __construct(){
		//error_reporting(E_ALL);
		 $this->con=mysqli_connect("10.0.0.35","root","root","hello42_new");
	}
	
	public function indexAction(){
		$c=1;
		$sql = "SELECT * FROM tblpromotion";
		$qry = mysqli_query($this->con,$sql);
		while($row = mysqli_fetch_assoc($qry)){
			$days = '';
			if($row['WeekDays']){
				$days = '<ul>';
				foreach(explode(',',$row['WeekDays']) as $val){
					$days .= "<li>$val</li>";
				}
				$days .= "</ul>";
			}
			$this->data [] = array(
									$c++, 
									$row['PromotionName'], 
									date('d M Y',strtotime($row['ValidDateFrom'])), 
									date('d M Y',strtotime($row['ValidDateTo'])), 
									date('h:i a',strtotime($row['ValidTimeFrom'])), 
									date('h:i a',strtotime($row['ValidTimeTo'])), 
									$days, 
									$row['MinimumBookingAmount'], 
									$row['Discount'], 
									'<a href="promotion/edit?id='.$row['id'].'">Edit</a> | <a href="promotion/delete?id='.$row['id'].'" onclick="return confirm('."'Are you sure want to delete this record'".');" >Delete</a>');
		}
		$this->data = json_encode(array('data'=>$this->data));
		return new viewModel(array('data'=>$this->data));
	}
	
	public function addAction(){
		$BookingTypeList = "<option value=''>Select Booking Type</option>";
		$TimeList = "<option value=''>Select Time</option>";
		for($a=0;$a<=23;$a++){
			for($b=0;$b<2;$b++){
				if($b%2==1){
					$min = 30;
				}else{
					$min = 0;
				}
				$time = mktime($a,$min,0,0,0,0);
				$TimeList .= "<option value='".date('h:i A',$time)."'>".date('h:i A',$time)."</option>";
			}
		}
		$sql = "SELECT Package_Id, Master_Package FROM tblmasterpackage";
		$qry = mysqli_query($this->con, $sql) or die(mysqli_error());
		while($row = mysqli_fetch_object($qry)){
			$BookingTypeList .= "<option value='".$row->Package_Id."'>".$row->Master_Package."</option>";
		}
		$this->data = array(
			'TimeList'=>$TimeList,
			'BookingTypeList'=>$BookingTypeList
		);
		return new viewModel(array('data'=>$this->data));
	}
	
	public function saveAction(){
		$fields = '';
		$values = '';
		$data = array(
			'PromotionName'=>$_REQUEST['PromotionName'],
			'PromotionDescription'=>$_REQUEST['PromotionDescription'],
			'CouponType'=>$_REQUEST['CouponType'],
			'ValidDateFrom'=>date('Y-m-d',strtotime($_REQUEST['ValidDateFrom'])),
			'ValidDateTo'=>date('Y-m-d',strtotime($_REQUEST['ValidDateTo'])),
			'ValidTimeFrom'=>date('H:i',strtotime($_REQUEST['ValidTimeFrom'])),
			'ValidTimeTo'=>date('H:i',strtotime($_REQUEST['ValidTimeTo'])),
			'WeekDays'=>implode(',', $_REQUEST['WeekDays']),
			'BookingTypeId'=>$_REQUEST['BookingTypeId'],
			'MinimumBookingAmount'=>$_REQUEST['MinimumBookingAmount'],
			'DiscountType'=>$_REQUEST['DiscountType'],
			'Discount'=>$_REQUEST['Discount']
		);
		foreach($data as $key=>$val){
			$fields .= "`$key`,";
			$values .= "'$val',";
		}
		$fields = substr($fields,0,-1);
		$values = substr($values,0,-1);
		$sql = "INSERT INTO tblpromotion ($fields) VALUES ($values)";
		$qry = mysqli_query($this->con,$sql);
		header('location: '.site_url.'/admin/promotion');
		exit();
	}
	
	public function editAction(){
				
		$sql = "SELECT * FROM tblpromotion WHERE id = '".$_REQUEST['id']."'";
		$qry = mysqli_query($this->con, $sql);
		$Rec = mysqli_fetch_object($qry);
		
		$BookingTypeList = "<option value=''>Select Booking Type</option>";
		$TimeList = "<option value=''>Select Time</option>";
		for($a=0;$a<=23;$a++){
			for($b=0;$b<2;$b++){
				if($b%2==1){
					$min = 30;
				}else{
					$min = 0;
				}
				$time = mktime($a,$min,0,0,0,0);
				$TimeList .= "<option selected='selected' value='".date('h:i A',$time)."'>".date('h:i A',$time)."</option>";
			}
		}
		$sql = "SELECT Package_Id, Master_Package FROM tblmasterpackage";
		$qry = mysqli_query($this->con, $sql) or die(mysqli_error());
		while($row = mysqli_fetch_object($qry)){
			$BookingTypeList .= "<option value='".$row->Package_Id."'>".$row->Master_Package."</option>";
		}
		$this->data = array(
			'TimeList'=>$TimeList,
			'BookingTypeList'=>$BookingTypeList,
			'Rec'=>$Rec
		);
				
		return new viewModel(array('data'=>$this->data));
	}
	
	public function updateAction(){
		$recs = '';
		$data = array(
			'PromotionName'=>$_REQUEST['PromotionName'],
			'PromotionDescription'=>$_REQUEST['PromotionDescription'],
			'CouponType'=>$_REQUEST['CouponType'],
			'ValidDateFrom'=>date('Y-m-d',strtotime($_REQUEST['ValidDateFrom'])),
			'ValidDateTo'=>date('Y-m-d',strtotime($_REQUEST['ValidDateTo'])),
			'ValidTimeFrom'=>date('H:i',strtotime($_REQUEST['ValidTimeFrom'])),
			'ValidTimeTo'=>date('H:i',strtotime($_REQUEST['ValidTimeTo'])),
			'WeekDays'=>implode(',', $_REQUEST['WeekDays']),
			'BookingTypeId'=>$_REQUEST['BookingTypeId'],
			'MinimumBookingAmount'=>$_REQUEST['MinimumBookingAmount'],
			'DiscountType'=>$_REQUEST['DiscountType'],
			'Discount'=>$_REQUEST['Discount']
		);
		foreach($data as $key=>$val){
			$recs .= "`$key`='$val',";
		}
		$recs = substr($recs,0,-1);
		$sql = "UPDATE tblpromotion SET $recs WHERE id = '".$_REQUEST['id']."'";
		$qry = mysqli_query($this->con,$sql);
		header('location: '.site_url.'/admin/promotion');
		exit();
	}
	
	public function deleteAction(){
		$sql = "DELETE FROM tblpromotion WHERE id = '".$_REQUEST['id']."'";
		$qry = mysqli_query($this->con, $sql);
		header('location: '.site_url.'/admin/promotion');
		exit();
	}
}
