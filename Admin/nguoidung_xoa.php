<?php
// (Dùng biến $connect từ file indexnguoidung.php)

// 1. Kiểm tra xem $connect có tồn tại không
if (!$connect) {
    die("Lỗi kết nối CSDL.");
}

// 2. Kiểm tra xem ID có được gửi lên không
if (isset($_GET['id'])) {
    
    $id = (int)$_GET['id'];
    $sql = "DELETE FROM `nguoi_dung` WHERE `id_nguoi_dung` = ?";
    
    if ($stmt = $connect->prepare($sql)) {
        $stmt->bind_param("i", $id);
        
        if (!$stmt->execute()) {
            die("Lỗi khi xóa người dùng: " . $stmt->error);
        }
        $stmt->close();
        
    } else {
        die("Lỗi SQL: " . $connect->error);
    }
}

// === SỬA LỖI: Link đã trỏ về indexnguoidung.php ===
header("Location: indexnguoidung.php?do=nguoidung");
exit();
?>