<?php 
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
    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "qltp";

    $connect = new mysqli($servername, $username_db, $password_db, $dbname);
    $connect->set_charset("utf8mb4");

    if ($connect->connect_error) {
        die("Kết nối CSDL thất bại: " . $connect->connect_error);
    }
    
    $hoTen = $_POST['fullName'];
    $soDienThoai = $_POST['phone'];
    $diaChi = $_POST['address'];
    $email = $_POST['email'];
    $tenDangNhap = $_POST['username'];
    $matKhau = $_POST['password'];
    $xacNhanMatKhau = $_POST['confirmPassword'];

    if(trim($hoTen) == "")
		ThongBaoLoi("Họ và tên không được bỏ trống!");
	elseif(trim($soDienThoai) == "")
		ThongBaoLoi("Số điện thoại không được bỏ trống!");
    if(trim($diaChi) == "")
		ThongBaoLoi("Địa chỉ không được bỏ trống!");
	elseif(trim($email) == "")
		ThongBaoLoi("Email không được bỏ trống!");
    elseif(trim($tenDangNhap) == "")
		ThongBaoLoi("Tên đăng nhập không được bỏ trống!");
    elseif(trim($matKhau) == "")
		ThongBaoLoi("Mật khẩu không được bỏ trống!");
	elseif($matKhau != $xacNhanMatKhau)
		ThongBaoLoi("Lỗi: Mật khẩu và Xác nhận mật khẩu không khớp!");
    else
    {
        $sql_kiemtra = "SELECT id_nguoi_dung FROM nguoi_dung WHERE ten_dang_nhap = ?";
        
        if (!($stmt_kiemtra = $connect->prepare($sql_kiemtra))) {
            ThongBaoLoi("Lỗi chuẩn bị SQL kiểm tra: " . $connect->error);
            exit();
        }
        
        $stmt_kiemtra->bind_param("s", $tenDangNhap);
        $stmt_kiemtra->execute();
        $ketQuaKiemTra = $stmt_kiemtra->get_result();
        
        if($ketQuaKiemTra->num_rows > 0)
        {
            ThongBaoLoi("Tên đăng nhập **$tenDangNhap** đã tồn tại. Vui lòng chọn tên khác!");
            $stmt_kiemtra->close();
        }
        else
        {
            $stmt_kiemtra->close();

            $matKhauAnToan = password_hash($matKhau, PASSWORD_DEFAULT);

            $sql_them = "INSERT INTO nguoi_dung (ho_ten, so_dien_thoai, dia_chi, email, ten_dang_nhap, mat_khau, vai_tro, Khoa)
                        VALUES (?, ?, ?, ?, ?, ?, 0, 0)";

            if (!($stmt_them = $connect->prepare($sql_them))) {
                ThongBaoLoi("Lỗi chuẩn bị SQL thêm: " . $connect->error);
                exit();
            }

            $stmt_them->bind_param("ssssss", $hoTen, $soDienThoai, $diaChi, $email, $tenDangNhap, $matKhauAnToan);

            if($stmt_them->execute()) {
                ThongBao("Đăng ký tài khoản **$tenDangNhap** thành công. <a href='dangnhap.php'>Đăng nhập ngay!</a>");
            }
            else {
                ThongBaoLoi("Lỗi khi thêm người dùng: " . $stmt_them->error);
            }
            
            $stmt_them->close();
        }
    }
    $connect->close();
?>