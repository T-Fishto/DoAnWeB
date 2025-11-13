<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "qltp";	

	$connect = new mysqli($servername, $username, $password, $dbname);
	$connect->set_charset("utf8mb4");	

	if ($connect->connect_error) {
		    die("Không kết nối :" . $conn->connect_error);
		    exit();
	}	
?>