<?php
    if (!$connect) 
    {
        die("Lỗi kết nối CSDL.");
    }

    if (isset($_GET['id'])) 
    {
        $id = (int)$_GET['id'];
        $sql = "DELETE FROM `nguoi_dung` WHERE `id_nguoi_dung` = ?";
        
        if ($stmt = $connect->prepare($sql)) 
        {
            $stmt->bind_param("i", $id);
            
            if (!$stmt->execute()) 
            {
                die("Lỗi khi xóa người dùng: " . $stmt->error);
            }
            $stmt->close();
            
        } 
        else 
        {
            die("Lỗi SQL: " . $connect->error);
        }
    }
    header("Location: indexnguoidung.php?do=nguoidung");
    exit();
?>