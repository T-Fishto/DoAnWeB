<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        $id = (int)$_POST['id_nguoi_dung'];
        $ho_ten = $_POST['ho_ten'];
        $email = $_POST['email'];
        $dia_chi = $_POST['dia_chi'];
        $so_dien_thoai = $_POST['so_dien_thoai'];
        
        if (!empty($_POST['mat_khau'])) 
        {
            $mat_khau_hashed = password_hash($_POST['mat_khau'], PASSWORD_DEFAULT);
            $sql_update = "UPDATE `nguoi_dung` SET `ho_ten` = ?, `email` = ?, `dia_chi` = ?, `so_dien_thoai` = ?, `mat_khau` = ? WHERE `id_nguoi_dung` = ?";
            if ($stmt = $connect->prepare($sql_update)) 
            {
                $stmt->bind_param("sssssi", $ho_ten, $email, $dia_chi, $so_dien_thoai, $mat_khau_hashed, $id);
            }
        } 
        else 
        {
            $sql_update = "UPDATE `nguoi_dung` SET `ho_ten` = ?, `email` = ?, `dia_chi` = ?, `so_dien_thoai` = ? WHERE `id_nguoi_dung` = ?";
            if ($stmt = $connect->prepare($sql_update)) 
            {
                $stmt->bind_param("ssssi", $ho_ten, $email, $dia_chi, $so_dien_thoai, $id);
            }
        }
        
        if ($stmt && $stmt->execute()) 
        {
            header("Location: indexnguoidung.php?do=nguoidung");
            exit();
        } 
        else 
        {
            die("Lỗi khi cập nhật: " . $stmt->error);
        }
        $stmt->close();
    }

    if (!isset($_GET['id'])) 
    {
        header("Location: indexnguoidung.php?do=nguoidung");
        exit();
    }

    $id = (int)$_GET['id'];
    $sql_select = "SELECT * FROM `nguoi_dung` WHERE `id_nguoi_dung` = ?";
    $stmt_select = $connect->prepare($sql_select);
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();

    if ($result->num_rows === 0) 
    {
        header("Location: indexnguoidung.php?do=nguoidung");
        exit();
    }
    $dong = $result->fetch_assoc();
    $stmt_select->close();
?>

<div class="form-container">
    <h3>Sửa thông tin người dùng</h3>
    <form action="indexnguoidung.php?do=nguoidung_sua&id=<?php echo $dong['id_nguoi_dung']; ?>" method="POST">
        <input type="hidden" name="id_nguoi_dung" value="<?php echo $dong['id_nguoi_dung']; ?>">

        <div class="form-group">
            <label for="ten_dang_nhap">Tên đăng nhập (Không thể đổi)</label>
            <input type="text" id="ten_dang_nhap" name="ten_dang_nhap" value="<?php echo htmlspecialchars($dong['ten_dang_nhap']); ?>" readonly>
        </div>
        
        <div class="form-group">
            <label for="ho_ten">Họ và tên</label>
            <input type="text" id="ho_ten" name="ho_ten" value="<?php echo htmlspecialchars($dong['ho_ten']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($dong['email']); ?>" required>
        </div>

        <div class="form-group">
            <label for="dia_chi">Địa chỉ</label>
            <textarea id="dia_chi" name="dia_chi"><?php echo htmlspecialchars($dong['dia_chi']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="so_dien_thoai">Số điện thoại</label>
            <input type="text" id="so_dien_thoai" name="so_dien_thoai" value="<?php echo htmlspecialchars($dong['so_dien_thoai']); ?>">
        </div>
        
        <div class="form-group">
            <label for="mat_khau">Mật khẩu mới</label>
            <input type="password" id="mat_khau" name="mat_khau" placeholder="Để trống nếu không muốn đổi">
            <p class="password-note">Bỏ trống ô này để giữ nguyên mật khẩu cũ.</p>
        </div>

        <button type="submit" class="btn-submit">Lưu thay đổi</button>
    </form>
</div>