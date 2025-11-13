<?php
session_start();

// --- 1. KẾT NỐI CSDL ---
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "qltp";

$connect = new mysqli($servername, $username_db, $password_db, $dbname);
$connect->set_charset("utf8mb4");

if ($connect->connect_error) {
    die("Kết nối CSDL thất bại: " . $connect->connect_error);
}

// --- 2. LẤY DỮ LIỆU TỪ FORM ---
$TenDangNhap = $_POST['username'];
$MatKhau = $_POST['password'];
$redirect_url = isset($_POST['redirect_url']) ? $_POST['redirect_url'] : '';

// --- 3. KIỂM TRA ĐẦU VÀO ---
if (trim($TenDangNhap) == "") {
    $_SESSION['login_error'] = "Tên đăng nhập không được bỏ trống!";
    header("Location: dangnhap.php");
    exit;
} elseif (trim($MatKhau) == "") {
    $_SESSION['login_error'] = "Mật khẩu không được bỏ trống!";
    header("Location: dangnhap.php");
    exit;
} else {
    // --- 4. TRUY VẤN CSDL (AN TOÀN) ---
    $sql_kiemtra = "SELECT * FROM `nguoi_dung` WHERE `ten_dang_nhap` = ?";
    
    $stmt = $connect->prepare($sql_kiemtra);
    $stmt->bind_param("s", $TenDangNhap);
    $stmt->execute();
    $danhsach = $stmt->get_result();
    
    if ($danhsach->num_rows == 1) {
        $dong = $danhsach->fetch_assoc();
        $mat_khau_bam_tu_db = $dong['mat_khau']; // Đây là mật khẩu ĐÃ BĂM

        // Khôi phục lại password_verify() để so sánh mật khẩu băm
        if (password_verify($MatKhau, $mat_khau_bam_tu_db)) { 
            
            // Kiểm tra tài khoản có bị khóa không
            if ($dong['Khoa'] == 0) {
                // Đăng nhập thành công
                $_SESSION['MaNguoiDung'] = $dong['id_nguoi_dung'];
                $_SESSION['HoVaTen'] = $dong['ho_ten'];
                $_SESSION['VaiTro'] = $dong['vai_tro'];

                // --- 6. CHUYỂN HƯỚNG ---
                
                // Ưu tiên 1: Quay lại trang (giỏ hàng/chi tiết) nếu có
                if (!empty($redirect_url) && strpos($redirect_url, 'chi_tiet.php') !== false) {
                    // SỬA LẠI ĐƯỜNG DẪN: Trỏ về thư mục TrangWeb
                    header("Location: ../TrangWeb/" . $redirect_url);
                    exit;
                }

                // Ưu tiên 2: Đi đến trang Admin hoặc User
                if ($dong['vai_tro'] == 1) {
                    header("Location: indexnguoidung.php"); // (Trang Admin)
                } else {
                    // SỬA LẠI ĐƯỜNG DẪN: Trỏ về thư mục TrangWeb
                    header("Location: ../TrangWeb/index.php"); 
                } 
                exit();
                
            } else {
                $_SESSION['login_error'] = "Tài khoản này đã bị khóa!";
            }
        } else {
            $_SESSION['login_error'] = "Tên đăng nhập hoặc mật khẩu không chính xác!";
        }
    } else {
        $_SESSION['login_error'] = "Tên đăng nhập hoặc mật khẩu không chính xác!";
    }
    
    // Nếu có lỗi, quay lại trang đăng nhập
    $stmt->close();
    $connect->close();
    header("Location: dangnhap.php");
    exit;
}
?>