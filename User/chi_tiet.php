<?php
// --- 1. KẾT NỐI CƠ SỞ DỮ LIỆU ---
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qltp"; 

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// --- 2. LẤY ID SẢN PHẨM TỪ URL VÀ TRUY VẤN CSDL ---
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;

if ($product_id > 0) {
    // Truy vấn JOIN để lấy thông tin chi tiết sản phẩm
    $sql = "SELECT sp.ten_san_pham, sp.gia, sp.hinh_anh, dm.mo_ta, dm.ten_danh_muc 
            FROM san_pham sp 
            JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc 
            WHERE sp.id_san_pham = $product_id";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();
    }
}

// Nếu không tìm thấy sản phẩm, chuyển hướng hoặc hiển thị lỗi
if (!$product) {
    header("Location: danhsachsanpham.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product ? htmlspecialchars($product['ten_san_pham']) : 'Sản phẩm không tồn tại'; ?></title>
    <link rel="stylesheet" href="css/menu_chitiet.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="header-placeholder">
    </div>

<?php if ($product): ?>
<div class="product-detail-container">
    <div class="product-image-section">
        <img src="<?php echo htmlspecialchars($product['hinh_anh']); ?>" alt="<?php echo htmlspecialchars($product['ten_san_pham']); ?>" class="main-product-img">
        
        <div class="product-thumbnails">
            <img src="<?php echo htmlspecialchars($product['hinh_anh']); ?>" alt="Thumbnail 1">
            <img src="<?php echo htmlspecialchars($product['hinh_anh']); ?>" alt="Thumbnail 2">
            </div>
    </div>
    
    <div class="product-info-section">
        <h1><?php echo htmlspecialchars($product['ten_san_pham']); ?></h1>
        
        <div class="rating">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="far fa-star"></i>
        </div>
        
        <p class="description">Mô tả: <?php echo htmlspecialchars($product['mo_ta']); ?></p>
        
        <div class="options-group">
            <label>Size:</label>
            <div class="radio-options">
                <input type="radio" id="size-m" name="size" checked>
                <label for="size-m">M</label>
                <input type="radio" id="size-l" name="size">
                <label for="size-l">L</label>
            </div>
        </div>

        <div class="options-group">
            <label>Màu sắc:</label>
            <div class="checkbox-options">
                <input type="checkbox" id="color-default" checked>
                <label for="color-default"><i class="fas fa-check"></i></label>
            </div>
        </div>

        <div class="options-group">
            <label>Ghi chú</label>
            <input type="text" placeholder="Thêm ghi chú món này" class="notes-input">
        </div>

        <div class="options-group quantity-control">
            <label>Số lượng:</label>
            <div class="quantity-input-group">
                <button type="button" class="quantity-btn" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">-</button>
                <input type="number" name="quantity" value="1" min="1" max="99" class="quantity-input">
                <button type="button" class="quantity-btn" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">+</button>
            </div>
        </div>
        
        <div class="action-buttons">
            <button class="add-to-cart-btn">Thêm vào giỏ hàng</button>
            <button class="order-now-btn">Đặt hàng ngay</button>
        </div>

        <div class="promo-box">
            <p>Nhập "YEUQUAN"</p>
            <p>Giảm 10k, đơn tối thiểu 80k</p>
        </div>
        <div class="promo-box">
            <p>Nhập "FREESHIP"</p>
            <p>Freeship tối 3km, đơn tối thiểu 100k</p>
        </div>

    </div>
</div>
<?php else: ?>
    <div style="text-align: center; padding: 50px;">
        <h2>Sản phẩm không tìm thấy.</h2>
        <p><a href="danhsachsanpham.php">Quay lại Menu</a></p>
    </div>
<?php endif; ?>

<?php $conn->close(); ?>
</body>
</html>