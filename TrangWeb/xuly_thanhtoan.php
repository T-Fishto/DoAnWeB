<?php
session_start();

// --- 1. KIỂM TRA BẢO MẬT ---
if (!isset($_SESSION['MaNguoiDung'])) {
    die("Lỗi: Bạn cần đăng nhập để thanh toán.");
}
// ★★★ SỬA LỖI 1: KIỂM TRA XEM CÓ MÓN HÀNG NÀO ĐƯỢC CHỌN KHÔNG ★★★
if (!isset($_POST['selected_items']) || empty($_POST['selected_items'])) {
    // Nếu không chọn món nào mà bấm thanh toán, chỉ cần quay lại
    header("Location: giohang.php");
    exit;
}
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Lỗi: Giỏ hàng của bạn đang trống.");
}

// --- 2. KẾT NỐI CSDL ---
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qltp";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Kết nối thất bại: " . $conn->connect_error); }
$conn->set_charset("utf8");

// Bắt đầu Transaction
$conn->begin_transaction();

try {
    // --- 3. LỌC RA CÁC SẢN PHẨM ĐƯỢC CHỌN ---
    $selected_ids = $_POST['selected_items']; // Đây là mảng ID (vd: ["1_M", "3_L"])
    $items_to_process = []; // Mảng mới chỉ chứa các món được tick
    
    foreach ($selected_ids as $cart_item_id) {
        if (isset($_SESSION['cart'][$cart_item_id])) {
            $items_to_process[$cart_item_id] = $_SESSION['cart'][$cart_item_id];
        }
    }
    
    if (empty($items_to_process)) {
        throw new Exception("Không có sản phẩm hợp lệ nào được chọn.");
    }

    // --- 4. LẤY THÔNG TIN NGƯỜI DÙNG ---
    $id_nguoi_dung = (int)$_SESSION['MaNguoiDung'];
    $sql_user = "SELECT ho_ten, dia_chi FROM nguoi_dung WHERE id_nguoi_dung = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("i", $id_nguoi_dung);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $user_data = $result_user->fetch_assoc();
    $stmt_user->close();
    
    if (!$user_data) {
        throw new Exception("Không tìm thấy thông tin người dùng.");
    }
    
    $ten_nguoi_nhan = $user_data['ho_ten'];
    $dia_chi_giao_hang = $user_data['dia_chi'];

    // --- 5. TÍNH TOÁN LẠI TỔNG TIỀN (CHỈ CÁC MÓN ĐƯỢC CHỌN) ---
    $tong_tien = 0;
    $phi_van_chuyen = 25000; // Phí cố định
    
    // ★★★ SỬA LỖI 2: Chỉ tính tiền các món trong $items_to_process ★★★
    foreach ($items_to_process as $item) {
        $tong_tien += (float)$item['price'] * (int)$item['quantity'];
    }
    $tong_tien += $phi_van_chuyen; // Cộng phí ship

    // --- 6. LƯU VÀO BẢNG `don_hang` ---
    $sql_donhang = "INSERT INTO don_hang (id_nguoi_dung, ngay_dat_hang, tong_tien, ten_nguoi_nhan, dia_chi_giao_hang, trang_thai_don_hang) 
                    VALUES (?, NOW(), ?, ?, ?, 'Mới')";
    $stmt_donhang = $conn->prepare($sql_donhang);
    $stmt_donhang->bind_param("idss", $id_nguoi_dung, $tong_tien, $ten_nguoi_nhan, $dia_chi_giao_hang);
    $stmt_donhang->execute();
    
    $id_don_hang = $conn->insert_id;
    $stmt_donhang->close();

    // --- 7. LƯU VÀO BẢNG `chi_tiet_don_hang` ---
    $sql_chitiet = "INSERT INTO chi_tiet_don_hang (id_don_hang, id_san_pham, so_luong, gia_tai_thoi_diem_dat) 
                    VALUES (?, ?, ?, ?)";
    $stmt_chitiet = $conn->prepare($sql_chitiet);

    // ★★★ SỬA LỖI 3: Chỉ lặp qua $items_to_process ★★★
    foreach ($items_to_process as $item) {
        $id_sp = (int)$item['id'];
        $so_luong = (int)$item['quantity'];
        $gia_sp = (float)$item['price'];
        
        $stmt_chitiet->bind_param("iiid", $id_don_hang, $id_sp, $so_luong, $gia_sp);
        $stmt_chitiet->execute();
    }
    $stmt_chitiet->close();

    // --- 8. HOÀN TẤT ---
    $conn->commit();
    
    // ★★★ SỬA LỖI 4: Chỉ XÓA các món đã thanh toán khỏi giỏ hàng ★★★
    foreach ($selected_ids as $cart_item_id) {
        if (isset($_SESSION['cart'][$cart_item_id])) {
            unset($_SESSION['cart'][$cart_item_id]);
        }
    }
    
    // Chuyển hướng về trang giỏ hàng với thông báo thành công
    header("Location: giohang.php?payment=success");
    exit;

} catch (Exception $e) {
    // Nếu có lỗi, hủy bỏ (rollback) mọi thay đổi
    $conn->rollback();
    die("Thanh toán thất bại: " . $e->getMessage());
}

$conn->close();
?>