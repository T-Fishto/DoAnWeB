<?php
    session_start();
    
    //hủy bỏ các biến Session
    //Bằng cách xóa các biến này, hệ thống không còn nhận diện người dùng là đã đăng nhập nữa.
    unset($_SESSION['MaNguoiDung']);
	unset($_SESSION['HoVaTen']);
    unset($_SESSION['VaiTro']);

	header("Location: ../TrangWeb/index.php");
    exit();
?>