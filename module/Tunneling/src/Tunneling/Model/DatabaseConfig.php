<?php

namespace Tunneling\Model;


class DatabaseConfig {
	
	public  $con;
		
	public function getDatabaseConfig(){
		$con = mysqli_connect("10.0.0.35", "root", "root", "hello42_new" );
		return $con;
	}
		
}
