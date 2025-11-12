<?php
    if (!$connect) 
    {
        die("Lỗi kết nối CSDL.");
    }

    if (isset($_GET['quyen']) && isset($_GET['id'])) 
    {
        $quyen = (int)$_GET['quyen'];
        $id = (int)$_GET['id'];
        $sql = "UPDATE `nguoi_dung` SET `vai_tro` = ? WHERE `id_nguoi_dung` = ?";
        if ($stmt = $connect->prepare($sql)) 
        {
            $stmt->bind_param("ii", $quyen, $id);
            if (!$stmt->execute()) 
            {
                die("Lỗi khi cập nhật quyền: " . $stmt->error);
            }
            $stmt->close();
        } 
        else 
        {
            die("Lỗi SQL: " . $connect->error);
        }

    } 
    elseif (isset($_GET['khoa']) && isset($_GET['id'])) 
    {
        $khoa = (int)$_GET['khoa'];
        $id = (int)$_GET['id'];
        $sql = "UPDATE `nguoi_dung` SET `Khoa` = ? WHERE `id_nguoi_dung` = ?";
        if ($stmt = $connect->prepare($sql)) 
        {
            $stmt->bind_param("ii", $khoa, $id);
            if (!$stmt->execute()) 
            {
                die("Lỗi khi cập nhật khóa: " . $stmt->error);
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