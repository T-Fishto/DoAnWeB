<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "";	
	
	
	$connect = new mysqli($servername, $username, $password, $dbname);	

	//Nếu kết nối bị lỗi thì xuất báo lỗi và thoát.
	if ($connect->connect_error) {
		    die("Không kết nối :" . $conn->connect_error);
		    exit();
	}	
		
	
	
?>