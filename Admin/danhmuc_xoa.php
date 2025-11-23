<?php
    if (isset($_GET['id'])) 
    {
        $id = (int)$_GET['id'];
        
        // Lưu ý: Nếu database có ràng buộc khóa ngoại (Foreign Key), bạn có thể cần xóa sản phẩm thuộc danh mục này trước.
        // Ở đây mình viết lệnh xóa danh mục cơ bản:
        $sql = "DELETE FROM `danh_muc` WHERE `id_danh_muc` = ?";
        
        if ($stmt = $connect->prepare($sql)) 
        {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        } 
    }
    header("Location: indexnguoidung.php?do=danhmuc");
    exit();
?>