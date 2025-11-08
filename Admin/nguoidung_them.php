<?php
// (Dùng biến $connect từ file indexnguoidung.php)
$error_message = "";

// 1. XỬ LÝ KHI NGƯỜI DÙNG NHẤN NÚT "THÊM" (PHƯƠNG THỨC POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Lấy dữ liệu từ form
    $ten_dang_nhap = $_POST['ten_dang_nhap'];
    $mat_khau = $_POST['mat_khau'];
    $ho_ten = $_POST['ho_ten'];
    $email = $_POST['email'];
    $dia_chi = $_POST['dia_chi'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    
    // Kiểm tra các trường bắt buộc
    if (empty($ten_dang_nhap) || empty($mat_khau) || empty($ho_ten) || empty($email)) {
        $error_message = "Vui lòng điền đầy đủ các trường bắt buộc (*).";
    } else {
        
        // Kiểm tra xem Tên đăng nhập hoặc Email đã tồn tại chưa
        $sql_check = "SELECT * FROM `nguoi_dung` WHERE `ten_dang_nhap` = ? OR `email` = ?";
        $stmt_check = $connect->prepare($sql_check);
        $stmt_check->bind_param("ss", $ten_dang_nhap, $email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows > 0) {
            $error_message = "Tên đăng nhập hoặc Email đã tồn tại. Vui lòng chọn tên khác.";
        } else {
            // --- Nếu chưa tồn tại, tiến hành thêm mới ---
            
            // 2. BĂM MẬT KHẨU  
            $mat_khau = md5($mat_khau);
            // $mat_khau_hashed = password_hash($mat_khau, PASSWORD_DEFAULT);
            
            // 3. Chuẩn bị câu lệnh INSERT
            // Mặc định vai_tro = 0 (Thành viên) và Khoa = 0 (Không khóa)
            $sql_insert = "INSERT INTO `nguoi_dung` (ten_dang_nhap, mat_khau, ho_ten, email, dia_chi, so_dien_thoai, vai_tro, Khoa) 
                           VALUES (?, ?, ?, ?, ?, ?, 0, 0)";
            
            if ($stmt = $connect->prepare($sql_insert)) {
                // "ssssss" = 6 tham số đều là string
                $stmt->bind_param("ssssss", 
                    $ten_dang_nhap, 
                    $mat_khau, 
                    $ho_ten, 
                    $email, 
                    $dia_chi, 
                    $so_dien_thoai
                );
                
                // 4. Thực thi
                if ($stmt->execute()) {
                    // Thêm thành công, chuyển về trang danh sách
                    header("Location: indexnguoidung.php?do=nguoidung");
                    exit();
                } else {
                    $error_message = "Lỗi khi thêm người dùng: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error_message = "Lỗi SQL: " . $connect->error;
            }
        }
        $stmt_check->close();
    }
}
?>

<div class="form-container">
    <h3>Thêm người dùng mới</h3>
    
    <?php
    // Nếu có lỗi, hiển thị ở đây
    if (!empty($error_message)) {
        // Dùng htmlspecialchars để hiển thị lỗi an toàn
        echo '<div class="error-box">' . htmlspecialchars($error_message) . '</div>';
    }
    ?>
    
    <form action="indexnguoidung.php?do=dangky" method="POST">
        
        <div class="form-group">
            <label for="ten_dang_nhap">Tên đăng nhập <span style="color: red;">*</span></label>
            <input type="text" id="ten_dang_nhap" name="ten_dang_nhap" required>
        </div>
        
        <div class="form-group">
            <label for="mat_khau">Mật khẩu <span style="color: red;">*</span></label>
            <input type="password" id="mat_khau" name="mat_khau" required>
        </div>
        
        <div class="form-group">
            <label for="ho_ten">Họ và tên <span style="color: red;">*</span></label>
            <input type="text" id="ho_ten" name="ho_ten" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email <span style="color: red;">*</span></label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="dia_chi">Địa chỉ</label>
            <textarea id="dia_chi" name="dia_chi"></textarea>
        </div>
        
        <div class="form-group">
            <label for="so_dien_thoai">Số điện thoại</label>
            <input type="text" id="so_dien_thoai" name="so_dien_thoai">
        </div>

        <button type="submit" class="btn-submit">Thêm Người Dùng</button>
    </form>
</div>