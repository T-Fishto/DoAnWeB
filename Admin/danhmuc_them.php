<?php
    $message = "";
    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        $ten_danh_muc = trim($_POST['ten_danh_muc']);
        $mo_ta = trim($_POST['mo_ta']);

        if (empty($ten_danh_muc)) {
            $message = "Tên danh mục không được để trống!";
        } else {
            $sql = "INSERT INTO `danh_muc` (ten_danh_muc, mo_ta) VALUES (?, ?)";
            if ($stmt = $connect->prepare($sql)) {
                $stmt->bind_param("ss", $ten_danh_muc, $mo_ta);
                if ($stmt->execute()) {
                    header("Location: TrangAdmin.php?do=danhmuc");
                    exit();
                } else {
                    $message = "Lỗi: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
?>

<div class="form-container">
    <h3>Thêm Danh Mục Mới</h3>
    <?php if (!empty($message)) echo "<div class='error-box'>$message</div>"; ?>
    
    <form action="TrangAdmin.php?do=danhmuc_them" method="POST">
        <div class="form-group">
            <label>Tên Danh Mục <span style="color:red">*</span></label>
            <input type="text" name="ten_danh_muc" required>
        </div>
        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="mo_ta" rows="4"></textarea>
        </div>
        <button type="submit" class="btn-submit">Thêm Mới</button>
    </form>
</div>