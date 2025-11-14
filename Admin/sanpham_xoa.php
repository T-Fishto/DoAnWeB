<?php
   session_start();

    if (!isset($_SESSION['MaNguoiDung']) || $_SESSION['VaiTro'] != 1) 
    {
        header("Location: dangnhap.php");
        exit;
    }

    if (isset($_GET['id'])) 
    {

        $product_id_to_delete = $_GET['id'];
        require_once 'cauhinh.php';

        $sql = "DELETE FROM san_pham WHERE id_san_pham = ?";
        $stmt = $connect->prepare($sql);

        if ($stmt) 
        {
            $stmt->bind_param("i", $product_id_to_delete);

            $stmt->execute();

            $stmt->close();
        }
        
        $connect->close();

        header("Location: danhsachsanpham.php");
        exit;

    } 
    else 
    {
        header("Location: danhsachsanpham.php");
        exit;
    }
?>