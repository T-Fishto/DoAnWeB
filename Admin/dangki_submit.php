<?php
    require_once 'thu_vien.php';
    require_once 'cauhinh.php';

    function exitWithError($message, $connect) 
    {
        if ($connect && is_a($connect, 'mysqli') && $connect->ping()) 
        {
            $connect->close();
        }

        ThongBaoLoi($message);
        exit(); 
    }

    if ($_SERVER["REQUEST_METHOD"] != "POST" || empty($_POST)) 
    {
        exitWithError("Truy cập không hợp lệ. Vui lòng sử dụng form đăng ký.", $connect ?? null);
    }

    //trim(): xóa khoảng trắng thừa ở đầu và cuối chuỗi
    //?? '' để gán giá trị mặc định là chuỗi rỗng
    $hoTen = trim($_POST['fullName'] ?? '');
    $soDienThoai = trim($_POST['phone'] ?? '');
    $diaChi = trim($_POST['address'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tenDangNhap = trim($_POST['username'] ?? '');
    $matKhau = $_POST['password'] ?? '';
    $xacNhanMatKhau = $_POST['confirmPassword'] ?? '';
    
    if (!isset($connect)) 
    {
        die("Lỗi: Biến kết nối CSDL (\$connect) không tồn tại sau khi nhúng 'cauhinh.php'.");
    }

    if (empty($hoTen)) exitWithError("Họ và tên không được bỏ trống!", $connect);
    if (empty($soDienThoai)) exitWithError("Số điện thoại không được bỏ trống!", $connect);
    if (empty($diaChi)) exitWithError("Địa chỉ không được bỏ trống!", $connect);
    if (empty($email)) exitWithError("Email không được bỏ trống!", $connect);
    if (empty($tenDangNhap)) exitWithError("Tên đăng nhập không được bỏ trống!", $connect);
    if (empty($matKhau)) exitWithError("Mật khẩu không được bỏ trống!", $connect);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) exitWithError("Email không hợp lệ!", $connect);//Kiểm tra định dạng email
    if ($matKhau != $xacNhanMatKhau) exitWithError("Lỗi: Mật khẩu và Xác nhận mật khẩu không khớp!", $connect);

    // 1. Chuẩn bị SQL kiểm tra
    $sql_kiemtra = "SELECT ten_dang_nhap, email FROM nguoi_dung WHERE ten_dang_nhap = ? OR email = ?"; 
    if (!($stmt_kiemtra = $connect->prepare($sql_kiemtra))) 
    {
        exitWithError("Lỗi chuẩn bị SQL kiểm tra: " . $connect->error, $connect);
    }

    // 2. Điền 2 tham số: Username và Email
    $stmt_kiemtra->bind_param("ss", $tenDangNhap, $email);
    $stmt_kiemtra->execute();
    $ketQuaKiemTra = $stmt_kiemtra->get_result();
    
    // 3. Nếu tìm thấy dữ liệu trùng
    if($ketQuaKiemTra->num_rows > 0) //Đã có tài khoản sử dụng tên đăng nhập hoặc email
    {
        $row = $ketQuaKiemTra->fetch_assoc();
        $stmt_kiemtra->close(); 

        // Kiểm tra cụ thể xem trùng cái gì để báo lỗi cho đúng
        if ($row['ten_dang_nhap'] === $tenDangNhap) 
        {
            exitWithError("Tên đăng nhập **$tenDangNhap** đã tồn tại. Vui lòng chọn tên khác!", $connect);
        } 
        else 
        {
            exitWithError("Email **$email** đã được sử dụng. Vui lòng dùng email khác!", $connect);
        }
    }
    
    $matKhauAnToan = password_hash($matKhau, PASSWORD_DEFAULT);

    $sql_them = "INSERT INTO nguoi_dung (ho_ten, so_dien_thoai, dia_chi, email, ten_dang_nhap, mat_khau, vai_tro, Khoa)
                VALUES (?, ?, ?, ?, ?, ?, 0, 0)";

    if (!($stmt_them = $connect->prepare($sql_them))) 
    {
        exitWithError("Lỗi chuẩn bị SQL thêm: " . $connect->error, $connect);
    }

    //Gán giá trị
    $stmt_them->bind_param("ssssss", $hoTen, $soDienThoai, $diaChi, $email, $tenDangNhap, $matKhauAnToan);

    if($stmt_them->execute()) 
    {
        ThongBao("Đăng ký tài khoản **$tenDangNhap** thành công. <a href='dangnhap.php'>Đăng nhập ngay!</a>");
    } 
    else 
    {
        ThongBaoLoi("Lỗi khi thêm người dùng: " . $stmt_them->error);
    }

    $stmt_them->close();
    $connect->close();
?>