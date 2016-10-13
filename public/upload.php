<?php
error_reporting(0);

// A list of permitted file extensions
session_start();
  $name = $_GET['formname'];
//mkdir('dfsd');		
      
$allowed = array('png', 'jpg', 'gif','zip','pdf','docx','doc','pdf','xlsx','jpeg');
 //print_r($_FILES["$name"]);

if(isset($_FILES["$name"]) && $_FILES["$name"]['error'] == 0){

  $file = 'tmp/upload/';
 $filepath = $file.$_SESSION['id'];
 $unque =uniqid();
$fileCreate = mkdir($filepath, 0777,true);


	$extension = pathinfo($_FILES["$name"]['name'], PATHINFO_EXTENSION);

	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error"}';
		exit;
	}

	if(move_uploaded_file($_FILES["$name"]['tmp_name'], $filepath.'/'.$name.'_'.$unque.'.'.$extension)){
	
	$path = $filepath.'/'.$name.'_'.$unque.'.'.$extension;
	//if(move_uploaded_file($_FILES["$name"]['tmp_name'], $filepath.'/'.$_FILES["$name"]['name'].'_'.$name.'.'.$extension)){
		$pathdata = array("url"=>$path,"name"=>$name);
		
		echo '{"status":"success","path":'.json_encode($pathdata).'}';
		exit;
	}
}

echo '{"status":"error"}';
exit;
?>