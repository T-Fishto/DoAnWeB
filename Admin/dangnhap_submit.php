<?php
    session_start();

    require_once 'cauhinh.php';

    $TenDangNhap = $_POST['username'];
    $MatKhau = $_POST['password'];
    $redirect_url = isset($_POST['redirect_url']) ? $_POST['redirect_url'] : '';

    if (trim($TenDangNhap) == "") {
        $_SESSION['login_error'] = "Tên đăng nhập không được bỏ trống!";
        header("Location: dangnhap.php");
        exit;
    } elseif (trim($MatKhau) == "") {
        $_SESSION['login_error'] = "Mật khẩu không được bỏ trống!";
        header("Location: dangnhap.php");
        exit;
    } else {
        $sql_kiemtra = "SELECT * FROM `nguoi_dung` WHERE `ten_dang_nhap` = ?";
        
        $stmt = $connect->prepare($sql_kiemtra);
        // tạo 1 biến gửi đến sql để thành cái khuôn thông báo cho nó trước
        $stmt->bind_param("s", $TenDangNhap);
        //Điền thông tin chỉ là chuỗi ko phải mã lệnh để tránh 
        $stmt->execute();
        //thực thi câu lệnh sql

        $danhsach = $stmt->get_result();
        //Lấy toàn bộ gán vào biến danh sách

        
        if ($danhsach->num_rows == 1) {
            $dong = $danhsach->fetch_assoc();
            // bốc dữ liệu từ danh sách gán vào mảng dong
            $mat_khau_bam_tu_db = $dong['mat_khau'];
            //lấy mật khẩu đã mã hóa từ db gán vào biến

            // Bắt đầu so sánh
            if (password_verify($MatKhau, $mat_khau_bam_tu_db)) { 
                
                if ($dong['Khoa'] == 0) {
                    $_SESSION['MaNguoiDung'] = $dong['id_nguoi_dung'];
                    $_SESSION['HoVaTen'] = $dong['ho_ten'];
                    $_SESSION['VaiTro'] = $dong['vai_tro'];
            
                    if (!empty($redirect_url) && strpos($redirect_url, 'chi_tiet.php') !== false) {
                        header("Location: ../TrangWeb/" . $redirect_url);
                        exit;
                    }

                    if ($dong['vai_tro'] == 1) {
                        header("Location: indexnguoidung.php");
                    } else {
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
        
        $stmt->close();
        $connect->close();
        header("Location: dangnhap.php");
        exit;
    }
?>