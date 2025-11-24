<?php
    $error_message = "";
    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        $ten_dang_nhap = trim($_POST['ten_dang_nhap']);
        $mat_khau = $_POST['mat_khau'];
        $ho_ten = trim($_POST['ho_ten']);
        $email = trim($_POST['email']);
        $dia_chi = trim($_POST['dia_chi']);
        $so_dien_thoai = trim($_POST['so_dien_thoai']);
        
        // 1. Kiểm tra rỗng
        if (empty($ten_dang_nhap) || empty($mat_khau) || empty($ho_ten) || empty($email)) 
        {
            $error_message = "Vui lòng điền đầy đủ các trường bắt buộc (*).";
        } 
        // 2. Kiểm tra định dạng số điện thoại (nếu có nhập)
        elseif (!empty($so_dien_thoai) && !preg_match('/^[0-9]{10,11}$/', $so_dien_thoai)) 
        {
            $error_message = "Số điện thoại không hợp lệ (phải là 10 hoặc 11 chữ số).";
        }
        else 
        {
            // 3. Kiểm tra trùng lặp
            $sql_check = "SELECT * FROM `nguoi_dung` WHERE `ten_dang_nhap` = ? OR `email` = ?";
            $stmt_check = $connect->prepare($sql_check);
            $stmt_check->bind_param("ss", $ten_dang_nhap, $email);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();
            
            if ($result_check->num_rows > 0) 
            {
                $error_message = "Tên đăng nhập hoặc Email đã tồn tại. Vui lòng chọn tên khác.";
            } 
            else 
            {                
                $mat_khau_hash = password_hash($mat_khau, PASSWORD_DEFAULT);

                $sql_insert = "INSERT INTO `nguoi_dung` (ten_dang_nhap, mat_khau, ho_ten, email, dia_chi, so_dien_thoai, vai_tro, Khoa) 
                            VALUES (?, ?, ?, ?, ?, ?, 0, 0)";
                
                if ($stmt = $connect->prepare($sql_insert)) 
                {
                    $stmt->bind_param("ssssss", 
                        $ten_dang_nhap, 
                        $mat_khau_hash, // Dùng biến đã mã hóa chuẩn
                        $ho_ten, 
                        $email, 
                        $dia_chi, 
                        $so_dien_thoai);
                    
                    if ($stmt->execute()) 
                    {
                        // Chuyển hướng về danh sách sau khi thêm thành công
                        header("Location: TrangAdmin.php?do=nguoidung");
                        exit();
                    } 
                    else 
                    {
                        $error_message = "Lỗi khi thêm người dùng: " . $stmt->error;
                    }
                    $stmt->close();
                } 
                else 
                {
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
        if (!empty($error_message)) 
        {
            echo '<div class="error-box">' . htmlspecialchars($error_message) . '</div>';
        }
    ?>
    
    <form action="TrangAdmin.php?do=dangky" method="POST"> 
        <div class="form-group">
            <label for="ten_dang_nhap">Tên đăng nhập <span style="color: red;">*</span></label>
            <input type="text" id="ten_dang_nhap" name="ten_dang_nhap" required value="<?php echo isset($_POST['ten_dang_nhap']) ? htmlspecialchars($_POST['ten_dang_nhap']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="mat_khau">Mật khẩu <span style="color: red;">*</span></label>
            <input type="password" id="mat_khau" name="mat_khau" required>
        </div>
        
        <div class="form-group">
            <label for="ho_ten">Họ và tên <span style="color: red;">*</span></label>
            <input type="text" id="ho_ten" name="ho_ten" required value="<?php echo isset($_POST['ho_ten']) ? htmlspecialchars($_POST['ho_ten']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="email">Email <span style="color: red;">*</span></label>
            <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="dia_chi">Địa chỉ</label>
            <textarea id="dia_chi" name="dia_chi"><?php echo isset($_POST['dia_chi']) ? htmlspecialchars($_POST['dia_chi']) : ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="so_dien_thoai">Số điện thoại</label>
            <input type="text" id="so_dien_thoai" name="so_dien_thoai" value="<?php echo isset($_POST['so_dien_thoai']) ? htmlspecialchars($_POST['so_dien_thoai']) : ''; ?>">
        </div>

        <button type="submit" class="btn-submit">Thêm Người Dùng</button>
    </form>
</div>