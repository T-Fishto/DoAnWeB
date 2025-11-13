<?php
session_start();

// Kiểm tra hành động (action)
if (isset($_GET['action'])) {
    
    // --- HÀNH ĐỘNG XÓA (REMOVE) ---
    if ($_GET['action'] == 'remove' && isset($_GET['id'])) {
        $cart_item_id_to_remove = $_GET['id'];
        
        // Kiểm tra xem item có tồn tại trong giỏ hàng không
        if (isset($_SESSION['cart'][$cart_item_id_to_remove])) {
            // Xóa item khỏi giỏ hàng
            unset($_SESSION['cart'][$cart_item_id_to_remove]);
        }
    }
}
// Quay trở lại trang giỏ hàng
header('Location: giohang.php');
exit;
?>