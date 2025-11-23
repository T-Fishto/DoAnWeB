<?php
    if (!isset($_GET['id'])) {
        header("Location: indexnguoidung.php?do=danhmuc");
        exit();
    }
    $id = (int)$_GET['id'];

    // Xử lý cập nhật khi bấm nút Lưu
    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        $ten_danh_muc = trim($_POST['ten_danh_muc']);
        $mo_ta = trim($_POST['mo_ta']);

        $sql_update = "UPDATE `danh_muc` SET `ten_danh_muc` = ?, `mo_ta` = ? WHERE `id_danh_muc` = ?";
        if ($stmt = $connect->prepare($sql_update)) {
            $stmt->bind_param("ssi", $ten_danh_muc, $mo_ta, $id);
            if ($stmt->execute()) {
                header("Location: indexnguoidung.php?do=danhmuc");
                exit();
            } else {
                die("Lỗi cập nhật: " . $stmt->error);
            }
        }
    }

    // Lấy dữ liệu cũ để hiện lên form
    $sql_get = "SELECT * FROM `danh_muc` WHERE `id_danh_muc` = ?";
    $stmt = $connect->prepare($sql_get);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $dong = $result->fetch_assoc();
?>

<div class="form-container">
    <h3>Sửa Danh Mục</h3>
    <form action="indexnguoidung.php?do=danhmuc_sua&id=<?php echo $id; ?>" method="POST">
        <div class="form-group">
            <label>Tên Danh Mục</label>
            <input type="text" name="ten_danh_muc" value="<?php echo htmlspecialchars($dong['ten_danh_muc']); ?>" required>
        </div>
        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="mo_ta" rows="4"><?php echo htmlspecialchars($dong['mo_ta']); ?></textarea>
        </div>
        <button type="submit" class="btn-submit">Lưu Thay Đổi</button>
    </form>
</div>