<?php
    session_start();

    require_once 'cauhinh.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['add_to_cart']) || isset($_POST['order_now']))) 
    {
        $product_id_to_add = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_image = $_POST['product_image'];
        $quantity = (int)$_POST['quantity'];
        $size = $_POST['size'];
        $notes = $_POST['notes'];

        $desc = "Size " . $size; 
        $cart_item_id = $product_id_to_add . '_' . $size;

        $cart_item = [
            'id' => $product_id_to_add,
            'name' => $product_name,
            'price' => $product_price,
            'image' => $product_image,
            'quantity' => $quantity,
            'size' => $size,
            'desc' => $desc,
            'notes' => $notes 
        ];

        if (!isset($_SESSION['cart'])) 
        {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$cart_item_id])) 
        {
            $_SESSION['cart'][$cart_item_id]['quantity'] += $quantity;
        } 
        else
        {
            $_SESSION['cart'][$cart_item_id] = $cart_item;
        }

        if (isset($_POST['order_now'])) 
        {
            header("Location: giohang.php");
            exit;
            
        } 
        else 
        {
            $_SESSION['flash_message'] = "Đã thêm '" . $product_name . " (" . $desc . ")' vào giỏ hàng!";
            header("Location: chi_tiet.php?id=" . $product_id_to_add);
            exit;
        }
    }
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;

if (!isset($_SESSION['MaNguoiDung'])) 
{
    $_SESSION['flash_message'] = "Bạn cần đăng nhập để xem chi tiết sản phẩm!";
    $redirect_url = "chi_tiet.php?" . $_SERVER['QUERY_STRING'];
    header("Location: ../Admin/dangnhap.php?redirect_url=" . urlencode($redirect_url));
    exit;
}

if ($product_id > 0) 
{
    $sql = "SELECT sp.ten_san_pham, sp.gia, sp.hinh_anh, dm.mo_ta, dm.ten_danh_muc 
            FROM san_pham sp 
            JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc 
            WHERE sp.id_san_pham = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) 
    {
        $product = $result->fetch_assoc();
    }
    $stmt->close();
}
if (!$product) 
{
    header("Location: danhsachsanpham.php"); 
    exit();
}
$flash_message = "";
if (isset($_SESSION['flash_message'])) 
{
    $flash_message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['ten_san_pham']); ?></title>
    <link rel="stylesheet" href="css/menu_chitiet.css"> 
    <link rel="stylesheet" href="css/footer.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/thongbao_giohang.css"> </head>
<body>

<a href="giohang.php" class="cart-icon-link">
    <i class="fa-solid fa-cart-shopping"></i>
    <?php
    $cart_count = 0;
    if (isset($_SESSION['cart'])) 
    {
        $cart_count = count($_SESSION['cart']);
    }
    if ($cart_count > 0) 
    {
        echo '<span class="cart-item-count">' . $cart_count . '</span>';
    }
    ?>
</a>
<?php if (!empty($flash_message)): ?>
    <div class="flash-message">
        <span><?php echo $flash_message; ?></span>
        <a href="danhsachsanpham.php" class="back-to-menu-link">
            <i class="fas fa-arrow-left"></i> Quay lại Menu
        </a>
    </div>
<?php endif; ?>

<?php if ($product): ?>

<form method="POST" action="chi_tiet.php?id=<?php echo $product_id; ?>">

    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['ten_san_pham']); ?>">
    <input type="hidden" name="product_price" value="<?php echo $product['gia']; ?>">
    <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($product['hinh_anh']); ?>">

    <div class="product-detail-container">
        
        <div class="product-image-section">
            <img src="<?php echo htmlspecialchars($product['hinh_anh']); ?>" alt="<?php echo htmlspecialchars($product['ten_san_pham']); ?>" class="main-product-img">
            <div class="product-thumbnails">
                <img src="<?php echo htmlspecialchars($product['hinh_anh']); ?>" alt="Thumbnail 1">
            </div>
        </div>
        
        <div class="product-info-section">
            <h1><?php echo htmlspecialchars($product['ten_san_pham']); ?></h1>
            
            <div class="rating">
                <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="far fa-star"></i>
            </div>
            
            <p class="description">Mô tả: <?php echo htmlspecialchars($product['mo_ta']); ?></p>
            
            <div class="options-group">
                <label>Size:</label>
                <div class="radio-options">
                    <input type="radio" id="size-m" name="size" value="M" checked>
                    <label for="size-m">M</label>
                    <input type="radio" id="size-l" name="size" value="L">
                    <label for="size-l">L</label>
                </div>
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
                <button type="submit" name="add_to_cart" class="add-to-cart-btn">Thêm vào giỏ hàng</button>
                
                <button type="submit" name="order_now" class="order-now-btn">Đặt hàng ngay</button>
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
</form>

<?php else: ?>
    <div style="text-align: center; padding: 50px;">
        <h2>Sản phẩm không tìm thấy.</h2>
        <p><a href="danhsachsanpham.php">Quay lại Menu</a></p>
    </div>
<?php endif; ?>
<?php $connect->close(); ?>

<?php 
    require_once '../Footer/footer.php'; 
?>

</body>
</html>