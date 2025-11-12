<?php
    session_start();
    
    unset($_SESSION['MaNguoiDung']);
	unset($_SESSION['HoVaTen']);
    unset($_SESSION['VaiTro']);

	header("Location: ../TrangWeb/index.php");
    exit();
?>