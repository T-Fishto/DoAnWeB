<?php
	// Hủy SESSION
    unset($_SESSION['id_nguoi_dung']);
	unset($_SESSION['ho_ten']);
    unset($_SESSION['vai_tro']);
	// Chuyển hướng về trang index.php
	header("Location: index.php");
?>