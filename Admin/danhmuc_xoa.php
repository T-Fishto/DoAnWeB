<?php
    if (isset($_GET['id'])) 
    {
        $id = (int)$_GET['id'];
        
        // [BƯỚC 1: XÓA TẤT CẢ SẢN PHẨM THUỘC VỀ DANH MỤC NÀY TRƯỚC]
        $sql_sp = "DELETE FROM `san_pham` WHERE `id_danh_muc` = ?";
        
        if ($stmt_sp = $connect->prepare($sql_sp)) 
        {
            $stmt_sp->bind_param("i", $id);
            $stmt_sp->execute();
            $stmt_sp->close();
        }

        // [BƯỚC 2: SAU ĐÓ MỚI XÓA DANH MỤC]
        $sql_dm = "DELETE FROM `danh_muc` WHERE `id_danh_muc` = ?";
        
        if ($stmt_dm = $connect->prepare($sql_dm)) 
        {
            $stmt_dm->bind_param("i", $id);
            $stmt_dm->execute();
            $stmt_dm->close();
        }
        
    }
    // Chuyển hướng về trang danh mục sau khi hoàn thành
    header("Location: TrangAdmin.php?do=danhmuc");
    exit();
?>