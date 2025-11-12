<?php
session_start();

$payment_success = isset($_GET['payment']) && $_GET['payment'] == 'success';

$cart_items = [];
$tong_phu = 0;
$phi_van_chuyen = 25000; 

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cart_items = $_SESSION['cart'];
    // Tính tổng ban đầu (giả định tất cả được check)
    foreach ($cart_items as $item) {
        $thanh_tien_item = (float)$item['price'] * (int)$item['quantity'];
        $tong_phu += $thanh_tien_item;
    }
}
$tong_cong = $tong_phu + $phi_van_chuyen;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng của bạn</title>
    <link rel="stylesheet" href="css/gio_hang.css"> 
    <script src="js/giohang.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .summary-line.total-line { border-top: 1px dashed #ccc; padding-top: 10px; margin-top: 10px; }
        .summary-line.total-line .summary-total { font-size: 1.2em; font-weight: 700; color: var(--orange); }
    </style>
</head>
<body>

    <div class="cart-container">
        <div class="cart-header">
            <h2 class="cart-title">Giỏ hàng của bạn</h2>
            <a href="danhsachsanpham.php" class="continue-shopping">
                &larr; Tiếp tục mua sắm
            </a>
        </div>

        <form action="xuly_thanhtoan.php" method="POST">
        
            <div class="cart-body">
                <div class="cart-table-header">
                    <span class="header-mua">Mua</span>
                    <span class="header-sanpham">Sản phẩm</span>
                    <span class="header-soluong">Số lượng</span>
                    <span class="header-thanhtien">Thành tiền</span>
                    <span class="header-xoa">Xoá</span>
                </div>

                <div class="product-list">
                    <?php if (empty($cart_items)): ?>
                        <p style="text-align: center; padding: 30px; color: #888;">
                            <?php 
                            if ($payment_success) echo "Cảm ơn bạn đã đặt hàng!";
                            else echo "Giỏ hàng của bạn đang trống.";
                            ?>
                        </p>
                    <?php else: ?>
                        <?php foreach ($cart_items as $cart_item_id => $item): ?>
                            <?php $thanh_tien_item = (float)$item['price'] * (int)$item['quantity']; ?>
                            <div class="product-item" 
                                 data-price="<?php echo (float)$item['price']; ?>" 
                                 data-quantity="<?php echo (int)$item['quantity']; ?>">
                                
                                <div class="product-mua">
                                    <input type="checkbox" 
                                           class="product-checkbox" 
                                           name="selected_items[]" 
                                           value="<?php echo $cart_item_id; ?>" 
                                           checked> 
                                </div>
                                <div class="product-sanpham">
                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    <div class="product-info">
                                        <span class="product-name"><?php echo htmlspecialchars($item['name']); ?></span>
                                        <span class="product-desc"><?php echo htmlspecialchars($item['desc']); ?></span>
                                    </div>
                                </div>
                                <div class="product-soluong">
                                    <span><?php echo $item['quantity']; ?></span>
                                </div>
                                <div class="product-thanhtien">
                                    <?php echo number_format($thanh_tien_item, 0, '.', '.'); ?>đ
                                </div>
                                <div class="product-xoa">
                                    <a href="xoa_giohang.php?action=remove&id=<?php echo $cart_item_id; ?>" title="Xóa sản phẩm">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="cart-summary">
                <h3>Tóm tắt đơn hàng</h3>
                <div class="summary-line">
                    <span>Tổng phụ:</span>
                    <span class="summary-subtotal" id="cart-subtotal">
                        <?php echo number_format($tong_phu, 0, '.', '.'); ?>đ
                    </span>
                </div>
                <div class="summary-line">
                    <span>Phí vận chuyển:</span>
                    <span id="cart-shipping" data-shipping-fee="<?php echo $phi_van_chuyen; ?>">
                        <?php echo number_format($phi_van_chuyen, 0, '.', '.'); ?>đ
                    </span>
                </div>
                <div class="summary-line total-line">
                    <span>Tổng cộng:</span>
                    <span class="summary-total" id="cart-total">
                        <?php echo number_format($tong_cong, 0, '.', '.'); ?>đ
                    </span>
                </div>
            </div>

            <div class="cart-footer">
                <button type="submit" class="btn-checkout">Tiến hành thanh toán</button>
            </div>
            
        </form> </div>

    <?php if ($payment_success): ?>
    <script>
        setTimeout(function() {
            if (confirm("Thanh toán thành công! Bạn có muốn quay lại trang Thực Đơn không?")) {
                window.location.href = 'danhsachsanpham.php';
            }
        }, 500);
    </script>
    <?php endif; ?>

</body>
</html>