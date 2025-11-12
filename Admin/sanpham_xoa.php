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
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "qltp";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) 
        {
            die("Kết nối thất bại: " . $conn->connect_error);
        }

        $conn->set_charset("utf8");
        $sql = "DELETE FROM san_pham WHERE id_san_pham = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) 
        {
            $stmt->bind_param("i", $product_id_to_delete);

            $stmt->execute();

            $stmt->close();
        }
        
        $conn->close();

        header("Location: danhsachsanpham.php");
        exit;

    } 
    else 
    {
        header("Location: danhsachsanpham.php");
        exit;
    }
?>