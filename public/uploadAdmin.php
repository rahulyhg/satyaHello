<?php
error_reporting(0);

// A list of permitted file extensions
session_start();
$con=mysqli_connect("10.0.0.35","root","root","hello42_new");

 $name = $_GET['formname'];
 $id = $_GET['ids'];
 //echo "SELECT `residential_proof` FROM `tbldriver` WHERE `UID`='$id'";
 //die;
 $sql = mysqli_query($con,"SELECT `residential_proof` FROM `tbldriver` WHERE `UID`='$id'")or die(mysqli_error($con));
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

  $file = 'tmp/upload/';
 $filepath = $file.$folderpath;
$fileCreate = mkdir($filepath, 0777,true);


	$extension = pathinfo($_FILES["$name"]['name'], PATHINFO_EXTENSION);

	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error"}';
		exit;
	}
        $urlpath = $filepath.'/'.$name.'_'.uniqid().'.'.$extension;

	if(move_uploaded_file($_FILES["$name"]['tmp_name'], $urlpath)){
	
	 $path = $urlpath;
	//if(move_uploaded_file($_FILES["$name"]['tmp_name'], $filepath.'/'.$_FILES["$name"]['name'].'_'.$name.'.'.$extension)){
		$pathdata = array("url"=>$path,"name"=>$name);
		
		echo '{"status":"success","path":'.json_encode($pathdata).'}';
		//exit;
	}
}

//echo '{"status":"error"}';
//exit;
?>