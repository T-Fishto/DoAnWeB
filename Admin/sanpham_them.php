<?php
// BẮT ĐẦU SESSION và KẾT NỐI CƠ SỞ DỮ LIỆU (Tương tự như danhsachsanpham.php và index.php)
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qltp";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// KIỂM TRA QUYỀN ADMIN
if (!isset($_SESSION['VaiTro']) || $_SESSION['VaiTro'] != 1) {
    header("Location: index.php"); // Chuyển hướng nếu không phải Admin
    exit();
}

$message = "";

// XỬ LÝ KHI FORM ĐƯỢC GỬI (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten_san_pham = $_POST['ten_san_pham'];
    $gia = $_POST['gia'];
    $id_danh_muc = $_POST['id_danh_muc'];
    $hinh_anh = null;
    $upload_dir = '../images/sanpham/'; // Thư mục lưu ảnh

    // Xử lý Upload Ảnh
    if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) {
        $file_name = uniqid('sp_') . '_' . basename($_FILES['hinh_anh']['name']);
        $target_file = $upload_dir . $file_name;
        
        // Đảm bảo thư mục tồn tại (bạn cần tự tạo thư mục 'images/sanpham' trên server/XAMPP)
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $target_file)) {
            $hinh_anh = $target_file; // Lưu đường dẫn tương đối vào CSDL
        } else {
            $message = "Lỗi khi upload ảnh.";
        }
    }

    if (empty($message)) {
        // Chuẩn bị câu lệnh SQL INSERT
        $stmt = $conn->prepare("INSERT INTO san_pham (id_danh_muc, ten_san_pham, gia, hinh_anh, trang_thai) VALUES (?, ?, ?, ?, 1)");
        $stmt->bind_param("isds", $id_danh_muc, $ten_san_pham, $gia, $hinh_anh);

        if ($stmt->execute()) {
            $message = "Thêm sản phẩm **thành công**!";
        } else {
            $message = "Lỗi: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Lấy danh sách danh mục để hiển thị trong Select Box của Form
$sql_dm = "SELECT id_danh_muc, ten_danh_muc FROM danh_muc ORDER BY id_danh_muc";
$result_dm = $conn->query($sql_dm);
$danh_muc_options = "";
if ($result_dm->num_rows > 0) {
    while ($row = $result_dm->fetch_assoc()) {
        $danh_muc_options .= '<option value="' . $row['id_danh_muc'] . '">' . htmlspecialchars($row['ten_danh_muc']) . '</option>';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Sản Phẩm</title>
    <link rel="stylesheet" href="css/menu.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* CSS đơn giản cho Form quản lý */
        .admin-form { max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; background-color: #f9f9f9; }
        .admin-form h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input[type="text"], 
        .form-group input[type="number"], 
        .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .form-group input[type="file"] { padding: 5px; }
        .form-group button { 
            background-color: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; float: right; 
        }
        .message { padding: 10px; margin-bottom: 15px; border-radius: 4px; font-weight: bold; text-align: center; }
        .success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
    </style>
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