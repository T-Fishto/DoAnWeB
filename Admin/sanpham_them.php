<?php
    session_start();

    require_once 'cauhinh.php';

    if (!isset($_SESSION['VaiTro']) || $_SESSION['VaiTro'] != 1) 
    {
        header("Location: index.php");
        exit();
    }

    $message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $ten_san_pham = $_POST['ten_san_pham'];
        $gia = $_POST['gia'];
        $id_danh_muc = $_POST['id_danh_muc'];
        $hinh_anh = null;
        $upload_dir = '../images/sanpham/';

        if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) 
        {
            $file_name = uniqid('sp_') . '_' . basename($_FILES['hinh_anh']['name']);
            $target_file = $upload_dir . $file_name;
            
            if (!is_dir($upload_dir)) 
            {
                mkdir($upload_dir, 0777, true);
            }

            if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $target_file)) 
            {
                $hinh_anh = $target_file;
            } 
            else 
            {
                $message = "Lỗi khi upload ảnh.";
            }
        }

        if (empty($message)) 
        {
            $stmt = $connect->prepare("INSERT INTO san_pham (id_danh_muc, ten_san_pham, gia, hinh_anh, trang_thai) VALUES (?, ?, ?, ?, 1)");
            $stmt->bind_param("isds", $id_danh_muc, $ten_san_pham, $gia, $hinh_anh);

            if ($stmt->execute()) 
            {
                $message = "Thêm sản phẩm **thành công**!";
            } 
            else 
            {
                $message = "Lỗi: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    $sql_dm = "SELECT id_danh_muc, ten_danh_muc FROM danh_muc ORDER BY id_danh_muc";
    $result_dm = $connect->query($sql_dm);
    $danh_muc_options = "";
    if ($result_dm->num_rows > 0) 
    {
        while ($row = $result_dm->fetch_assoc()) 
        {
            $danh_muc_options .= '<option value="' . $row['id_danh_muc'] . '">' . htmlspecialchars($row['ten_danh_muc']) . '</option>';
        }
    }

    $connect->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/footer.css"> 
        <link rel="stylesheet" href="css/sanpham_them.css"> 
    <title>Thêm Sản Phẩm</title>
    <link rel="stylesheet" href="css/menu.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
<body>
    <div class="admin-form">
        <h2><i class="fas fa-plus-circle"></i> THÊM SẢN PHẨM MỚI</h2>
        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'thành công') !== false ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="sanpham_them.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="ten_san_pham">Tên Sản Phẩm:</label>
                <input type="text" id="ten_san_pham" name="ten_san_pham" required>
            </div>

            <div class="form-group">
                <label for="gia">Giá (VNĐ):</label>
                <input type="number" id="gia" name="gia" min="0" step="1000" required>
            </div>

            <div class="form-group">
                <label for="id_danh_muc">Danh Mục:</label>
                <select id="id_danh_muc" name="id_danh_muc" required>
                    <?php echo $danh_muc_options; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="hinh_anh">Hình Ảnh:</label>
                <input type="file" id="hinh_anh" name="hinh_anh" accept="image/*" required>
            </div>
            
            <div class="form-group">
                <button type="submit">Thêm Sản Phẩm</button>
            </div>
        </form>
        <div style="clear: both;"></div>
        <p style="text-align: center; margin-top: 20px;"><a href="danhsachsanpham.php"><i class="fas fa-arrow-left"></i> Quay lại Menu</a></p>
    </div>
</body>
</html>