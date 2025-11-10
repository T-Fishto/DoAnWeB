<?php
    // !!! BẮT BUỘC: Khởi động session để có thể thao tác với biến $_SESSION
    session_start();
    
	// 1. Hủy các biến SESSION đã đăng ký (Đã sửa để khớp với dangnhap_submit.php)
    // Dùng: MaNguoiDung, HoVaTen, VaiTro
    unset($_SESSION['MaNguoiDung']);
	unset($_SESSION['HoVaTen']);
    unset($_SESSION['VaiTro']);

    // (Tùy chọn: Hủy hoàn toàn session và xóa session cookie - nên dùng cho logout hoàn toàn)
    // session_destroy(); 

	// 2. Chuyển hướng người dùng về trang chủ
	header("Location: ../TrangWeb/index.php");
    exit(); // Luôn thêm exit() sau header() để đảm bảo chuyển hướng thực thi ngay lập tức
?>