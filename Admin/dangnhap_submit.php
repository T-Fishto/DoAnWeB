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
    // 1. KẾT NỐI CSDL
    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "qltp";

    $connect = new mysqli($servername, $username_db, $password_db, $dbname);
    $connect->set_charset("utf8mb4");

    if ($connect->connect_error) {
        die("Kết nối CSDL thất bại: " . $connect->connect_error);
    }
    
    // 2. LẤY DỮ LIỆU TỪ FORM
    $TenDangNhap = $_POST['username'];
    $MatKhau = $_POST['password']; // Đây là mật khẩu GÕ VÀO (vd: 'mk123')
    
    // 3. KIỂM TRA ĐẦU VÀO (Làm đầu tiên)
    if (trim($TenDangNhap) == "") {
        ThongBaoLoi("Tên đăng nhập không được bỏ trống!");
    } elseif (trim($MatKhau) == "") {
        ThongBaoLoi("Mật khẩu không được bỏ trống!");
    } else {
        
        // 4. LẤY NGƯỜI DÙNG BẰNG PREPARED STATEMENT (Chống SQL Injection)
        // **Chỉ tìm theo ten_dang_nhap**
        $sql_kiemtra = "SELECT * FROM `nguoi_dung` WHERE `ten_dang_nhap` = ?";
        
        if (!($stmt = $connect->prepare($sql_kiemtra))) {
            die("Lỗi chuẩn bị SQL: " . $connect->error);
        }
        
        // Gắn biến $TenDangNhap vào dấu ?
        $stmt->bind_param("s", $TenDangNhap);
        
        if (!$stmt->execute()) {
             die("Lỗi thực thi: " . $stmt->error);
        }
        
        $danhsach = $stmt->get_result();
        
        // 5. KIỂM TRA XEM CÓ TÌM THẤY NGƯỜI DÙNG KHÔNG
        if ($danhsach->num_rows == 1) {
            
            // 6. TÌM THẤY! LẤY DỮ LIỆU CỦA HỌ
            $dong = $danhsach->fetch_assoc();
            
            // Lấy mật khẩu ĐÃ BĂM từ CSDL
            $mat_khau_bam_tu_db = $dong['mat_khau']; // Đây là '$2y$10$oq6Ik...'

            // 7. "ẢO THUẬT" Ở ĐÂY: SO SÁNH MẬT KHẨU
            if (password_verify($MatKhau, $mat_khau_bam_tu_db)) {
                
                // MẬT KHẨU KHỚP!
                
                // 8. Kiểm tra tài khoản có bị khóa không
                if($dong['Khoa'] == 0) {
                    // Đăng ký SESSION
                    $_SESSION['MaNguoiDung'] = $dong['id_nguoi_dung'];
                    $_SESSION['HoVaTen'] = $dong['ho_ten'];
                    $_SESSION['VaiTro'] = $dong['vai_tro'];
                    
                    // Chuyển hướng về trang quản trị
                    // (Giả sử trang admin của bạn là indexnguoidung.php)
                    header("Location: index.php");
                    exit(); // Phải exit() sau khi chuyển hướng
                } else {
                    ThongBaoLoi("Người dùng đã bị khóa tài khoản!");
                }
            } else {
                // MẬT KHẨU KHÔNG KHỚP!
                ThongBaoLoi("Tên đăng nhập hoặc mật khẩu không chính xác!");
            }
        } else {
            // KHÔNG TÌM THẤY TÊN ĐĂNG NHẬP
            ThongBaoLoi("Tên đăng nhập hoặc mật khẩu không chính xác!");
        }
        
        $stmt->close();
    }
    
    $connect->close();
?>