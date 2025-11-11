<?php
// BẮT ĐẦU SESSION và KẾT NỐI CƠ SỞ DỮ LIỆU
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

// KIỂM TRA QUYỀN ADMIN VÀ ID SẢN PHẨM
if (!isset($_SESSION['VaiTro']) || $_SESSION['VaiTro'] != 1 || !isset($_GET['id'])) {
    header("Location: danhsachsanpham.php");
    exit();
}

$product_id = $conn->real_escape_string($_GET['id']);
$message = "";

// --- XỬ LÝ KHI FORM ĐƯỢC GỬI (POST) ĐỂ CẬP NHẬT ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten_san_pham = $_POST['ten_san_pham'];
    $gia = $_POST['gia'];
    $id_danh_muc = $_POST['id_danh_muc'];
    $hinh_anh_old = $_POST['hinh_anh_old'];
    $hinh_anh_new = $hinh_anh_old; // Mặc định giữ ảnh cũ
    $upload_dir = 'images/sanpham/'; 

    // Xử lý Upload Ảnh Mới
    if (isset($_FILES['hinh_anh_new']) && $_FILES['hinh_anh_new']['error'] == 0) {
        $file_name = uniqid('sp_') . '_' . basename($_FILES['hinh_anh_new']['name']);
        $target_file = $upload_dir . $file_name;
        
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES['hinh_anh_new']['tmp_name'], $target_file)) {
            $hinh_anh_new = $target_file; 
            // Xóa ảnh cũ nếu nó tồn tại và là ảnh sản phẩm
            if (!empty($hinh_anh_old) && file_exists($hinh_anh_old) && strpos($hinh_anh_old, 'images/sanpham/') !== false) {
                 unlink($hinh_anh_old);
            }
        } else {
            $message = "Lỗi khi upload ảnh mới.";
        }
    }

    if (empty($message)) {
        // Chuẩn bị câu lệnh SQL UPDATE
        $stmt = $conn->prepare("UPDATE san_pham SET id_danh_muc = ?, ten_san_pham = ?, gia = ?, hinh_anh = ? WHERE id_san_pham = ?");
        $stmt->bind_param("isdsi", $id_danh_muc, $ten_san_pham, $gia, $hinh_anh_new, $product_id);

        if ($stmt->execute()) {
            $message = "Cập nhật sản phẩm **thành công**!";
        } else {
            $message = "Lỗi: " . $stmt->error;
        }
        $stmt->close();
    }
}

// --- TẢI DỮ LIỆU SẢN PHẨM HIỆN TẠI ---
$sql_sp = "SELECT * FROM san_pham WHERE id_san_pham = '$product_id'";
$result_sp = $conn->query($sql_sp);

if ($result_sp->num_rows == 0) {
    die("Không tìm thấy sản phẩm.");
}
$product_data = $result_sp->fetch_assoc();


// Lấy danh sách danh mục để hiển thị trong Select Box
$sql_dm = "SELECT id_danh_muc, ten_danh_muc FROM danh_muc ORDER BY id_danh_muc";
$result_dm = $conn->query($sql_dm);
$danh_muc_options = "";
if ($result_dm->num_rows > 0) {
    while ($row = $result_dm->fetch_assoc()) {
        $selected = ($row['id_danh_muc'] == $product_data['id_danh_muc']) ? 'selected' : '';
        $danh_muc_options .= '<option value="' . $row['id_danh_muc'] . '" ' . $selected . '>' . htmlspecialchars($row['ten_danh_muc']) . '</option>';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Sản Phẩm</title>
    <link rel="stylesheet" href="css/menu.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .admin-form { max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; background-color: #f9f9f9; }
        .admin-form h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input[type="text"], 
        .form-group input[type="number"], 
        .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .form-group input[type="file"] { padding: 5px; }
        .form-group button { 
            background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; float: right; 
        }
        .message { padding: 10px; margin-bottom: 15px; border-radius: 4px; font-weight: bold; text-align: center; }
        .success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .current-image { text-align: center; margin-bottom: 15px; }
        .current-image img { max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="admin-form">
        <h2><i class="fas fa-edit"></i> SỬA SẢN PHẨM: <?php echo htmlspecialchars($product_data['ten_san_pham']); ?></h2>
        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'thành công') !== false ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="sanpham_sua.php?id=<?php echo $product_id; ?>" enctype="multipart/form-data">
            <input type="hidden" name="hinh_anh_old" value="<?php echo htmlspecialchars($product_data['hinh_anh']); ?>">
            
            <div class="form-group">
                <label for="ten_san_pham">Tên Sản Phẩm:</label>
                <input type="text" id="ten_san_pham" name="ten_san_pham" value="<?php echo htmlspecialchars($product_data['ten_san_pham']); ?>" required>
            </div>
            <div class="form-group">
                <label for="gia">Giá (VNĐ):</label>
                <input type="number" id="gia" name="gia" min="0" step="1000" value="<?php echo htmlspecialchars($product_data['gia']); ?>" required>
            </div>
            <div class="form-group">
                <label for="id_danh_muc">Danh Mục:</label>
                <select id="id_danh_muc" name="id_danh_muc" required>
                    <?php echo $danh_muc_options; ?>
                </select>
            </div>
            
            <div class="form-group current-image">
                <label>Ảnh Hiện Tại:</label>
                <?php if (!empty($product_data['hinh_anh'])): ?>
                    <img src="<?php echo htmlspecialchars($product_data['hinh_anh']); ?>" alt="Ảnh hiện tại" width="150">
                <?php else: ?>
                    <p>Không có ảnh.</p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="hinh_anh_new">Chọn Ảnh Mới (Bỏ qua nếu không đổi):</label>
                <input type="file" id="hinh_anh_new" name="hinh_anh_new" accept="image/*">
            </div>
            
            <div class="form-group">
                <button type="submit">Cập Nhật Sản Phẩm</button>
            </div>
        </form>
        <div style="clear: both;"></div>
        <p style="text-align: center; margin-top: 20px;"><a href="danhsachsanpham.php"><i class="fas fa-arrow-left"></i> Quay lại Menu</a></p>
    </div>
</body>
</html>