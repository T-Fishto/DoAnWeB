<?php
    // !!! QUAN TRỌNG: Bắt đầu SESSION trước mọi nội dung HTML/PHP khác
    session_start();
    
    function ThongBaoLoi($thongbao = "")
	{
		echo "<h3>Lỗi</h3><p class='ThongBaoLoi'>$thongbao</p>";
	}
	
	function ThongBao($thongbao = "")
	{
		echo "<h3>Hoàn thành</h3><p class='ThongBao'>$thongbao</p>";
	}
?>
<style>
.ThongBaoLoi {
    padding: 0;
    margin: 0;
    color: #ff0000;
}

.ThongBao {
    padding: 0;
    margin: 0;
    color: #0000ff;
}
</style>

<?php

    $servername = "localhost"; // Thường là "localhost"
    $username_db = "root"; // Tên đăng nhập CSDL, XAMPP mặc định là "root"
    $password_db = ""; // Mật khẩu CSDL, XAMPP mặc định là rỗng
    $dbname = "qltp"; // !!! THAY BẰNG TÊN DATABASE BẠN ĐÃ IMPORT SQL VÀO

    $connect = new mysqli($servername, $username_db, $password_db, $dbname);

    $connect->set_charset("utf8mb4");

    if ($connect->connect_error) {
        die("Kết nối CSDL thất bại: " . $connect->connect_error);
    }
	$TenDangNhap = $_POST['username'];
	$MatKhau = $_POST['password'];
	
	// Kiểm tra
	if (trim($TenDangNhap) == "")
		ThongBaoLoi("Tên đăng nhập không được bỏ trống!");
	elseif (trim($MatKhau) == "")
		ThongBaoLoi("Mật khẩu không được bỏ trống!");
	else
	{		
		// Kiểm tra người dùng có tồn tại không
		$sql_kiemtra = "SELECT * FROM nguoi_dung WHERE ten_dang_nhap = '$TenDangNhap' AND mat_khau = '$MatKhau'";	
		
		$danhsach = $connect->query($sql_kiemtra);
		//Nếu kết quả kết nối không được thì xuất báo lỗi và thoát
		if (!$danhsach) {
			die("Không thể thực hiện câu lệnh SQL: " . $connect->connect_error);
			exit();
		}
		
		$dong = $danhsach->fetch_array(MYSQLI_ASSOC);
		if($dong)
		{
			if($dong['Khoa'] == 0)
			{
				// Đăngký  SESSION
				$_SESSION['MaNguoiDung'] = $dong['id_nguoi_dung'];
				$_SESSION['HoVaTen'] = $dong['ho_ten'];
				$_SESSION['VaiTro'] = $dong['vai_tro'];
				
				// Chuyển hướng về trang index.php
				header("Location: index.php");
			}
			else
			{
				ThongBaoLoi("Người dùng đã bị khóa tài khoản!");
			}	
		}
		else
		{
			ThongBaoLoi("Tên đăng nhập hoặc mật khẩu không chính xác!");
		}
	}
?>