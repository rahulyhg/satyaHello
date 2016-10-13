<?php
	error_reporting(false);
	date_default_timezone_set("Asia/Kolkata");
	define('RUNNING_FROM_ROOT', true);
	if($_SERVER['HTTP_HOST'] == '166.62.35.117'){
		$site_url = 'http://166.62.35.117/hello42';
	}else{
		//for local
		$site_url = 'http://'.$_SERVER['HTTP_HOST'].'/hello42';

		//for live
		// $site_url = 'http://'.$_SERVER['HTTP_HOST'];
	}

	$rootfolder = substr(__FILE__,0,strpos(__FILE__,"index.php"));

	define("root_folder",$rootfolder);
	define('site_url', $site_url);
	include 'public/index.php';