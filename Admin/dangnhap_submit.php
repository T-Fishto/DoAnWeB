<?php
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
    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "qltp";

    $connect = new mysqli($servername, $username_db, $password_db, $dbname);
    $connect->set_charset("utf8mb4");

    if ($connect->connect_error) {
        die("Kết nối CSDL thất bại: " . $connect->connect_error);
    }
    
    $TenDangNhap = $_POST['username'];
    $MatKhau = $_POST['password'];
    
    if (trim($TenDangNhap) == "") {
        ThongBaoLoi("Tên đăng nhập không được bỏ trống!");
    } elseif (trim($MatKhau) == "") {
        ThongBaoLoi("Mật khẩu không được bỏ trống!");
    } else {
        
        $sql_kiemtra = "SELECT * FROM `nguoi_dung` WHERE `ten_dang_nhap` = ?";
        
        if (!($stmt = $connect->prepare($sql_kiemtra))) {
            die("Lỗi chuẩn bị SQL: " . $connect->error);
        }
        
        $stmt->bind_param("s", $TenDangNhap);
        
        if (!$stmt->execute()) {
             die("Lỗi thực thi: " . $stmt->error);
        }
        
        $danhsach = $stmt->get_result();
        
        if ($danhsach->num_rows == 1) {
            
            $dong = $danhsach->fetch_assoc();
            $mat_khau_bam_tu_db = $dong['mat_khau'];

            if (password_verify($MatKhau, $mat_khau_bam_tu_db)) {                          
                // Kiểm tra tài khoản có bị khóa không
                if($dong['Khoa'] == 0) {
                    $_SESSION['MaNguoiDung'] = $dong['id_nguoi_dung'];
                    $_SESSION['HoVaTen'] = $dong['ho_ten'];
                    $_SESSION['VaiTro'] = $dong['vai_tro'];
                    if ($dong['vai_tro'] == 1) {
                        header("Location: ../Admin/indexnguoidung.php"); 
                    } else {
                        header("Location: ../TrangWeb/index.php"); 
                    } 
                    exit();
                } else {
                    ThongBaoLoi("Người dùng đã bị khóa tài khoản!");
                }
            } else {
                ThongBaoLoi("Tên đăng nhập hoặc mật khẩu không chính xác!");
            }
        } else {
            ThongBaoLoi("Tên đăng nhập hoặc mật khẩu không chính xác!");
        }
        $stmt->close();
    }
    $connect->close();
?>