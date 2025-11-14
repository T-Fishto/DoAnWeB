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

        $sqlct = "DELETE FROM chi_tiet_don_hang WHERE id_san_pham = ?";
        $stmtct = $connect->prepare($sqlct);
        if ($stmtct) 
        {
            $stmtct->bind_param("i", $product_id_to_delete);

            $stmtct->execute();

            $stmtct->close();
        }

        $sqlsp = "DELETE FROM san_pham  WHERE id_san_pham = ?";
        $stmtsp = $connect->prepare($sqlsp);
        if ($stmtsp) 
        {
            $stmtsp->bind_param("i", $product_id_to_delete);

            $stmtsp->execute();

            $stmtsp->close();
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