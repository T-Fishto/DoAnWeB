<?php
    session_start();
    require_once 'cauhinh.php';

    $TenDangNhap = $_POST['username'];
    $MatKhau = $_POST['password'];
    //Đưa trở lại trang ban đầu trước khi thực hiện đăng nhập
    $redirect_url = isset($_POST['redirect_url']) ? $_POST['redirect_url'] : '';

    //Kiểm tra và thông báo lỗi sau đó chuyển hướng quay lại trang đăng nhập.
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
        // tạo 1 biến gửi đến sql để thành cái khuôn thông báo cho nó trước
        $stmt = $connect->prepare($sql_kiemtra);
        //Điền thông tin chỉ là chuỗi ko phải mã lệnh để tránh 
        $stmt->bind_param("s", $TenDangNhap);
        
        $stmt->execute();
        //Lấy toàn bộ gán vào biến danh sách
        $danhsach = $stmt->get_result();
        if ($danhsach->num_rows == 1)//tìm thấy chính xác một người dùng với Tên đăng nhập đó
        {
            $dong = $danhsach->fetch_assoc();

            $mat_khau_bam_tu_db = $dong['mat_khau'];
            /*nhận mật khẩu thô mà người dùng nhập ($MatKhau) và chuỗi băm được lưu trong CSDL ($mat_khau_bam_tu_db).
            băm lại $MatKhau và so sánh kết quả với chuỗi băm từ CSDL
            */

            if (password_verify($MatKhau, $mat_khau_bam_tu_db)) { 
                
                if ($dong['Khoa'] == 0) {
                    /*các thông tin quan trọng của người dùng (ID, Tên, Vai trò) được lưu vào biến $_SESSION
                    nhận diện người dùng đã đăng nhập ở các trang tiếp theo. */
                    $_SESSION['MaNguoiDung'] = $dong['id_nguoi_dung'];
                    $_SESSION['HoVaTen'] = $dong['ho_ten'];
                    $_SESSION['VaiTro'] = $dong['vai_tro'];
            
                    //Chuyển hướng theo url
                    if (!empty($redirect_url) && strpos($redirect_url, 'chi_tiet.php') !== false) {
                        header("Location: ../TrangWeb/" . $redirect_url);
                        exit;
                    }

                    //Chuyển hướng theo vai trò
                    if ($dong['vai_tro'] == 1) {
                        header("Location: indexnguoidung.php");
                    } else {
                        header("Location: ../TrangWeb/index.php"); 
                    } 
                    exit();
                    
                } else {
                    //$dong['Khoa'] == 1
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