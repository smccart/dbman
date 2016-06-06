<?php

class db {
	
	private $conn;
	
	function db($server, $user, $pass, $name) {
		//create db connection
		$conn = new mysqli($server, $user, $pass, $name);
		
		if($conn->connect_errno > 0){
		    die('Unable to connect to database [' . $conn->connect_error . ']');
		}
		
		$this->conn = $conn;
		
	}
	
	public function query($sql) {
		$conn = $this->conn;
		$result = $conn->query($sql);
		return $result;
	}
	
	
	
}

?>