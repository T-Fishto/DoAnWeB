<?php
    session_start();

    if (!isset($_POST['selected_items']) || empty($_POST['selected_items'])) 
    {
        header("Location: giohang.php");
    exit;
    }
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) 
    {
        die("Lỗi: Giỏ hàng của bạn đang trống.");
    }

    require_once 'cauhinh.php';

   $connect->begin_transaction();

try 
{
    $selected_ids = $_POST['selected_items'];
    $items_to_process = [];
    
    foreach ($selected_ids as $cart_item_id) 
    {
        if (isset($_SESSION['cart'][$cart_item_id])) 
        {
            $items_to_process[$cart_item_id] = $_SESSION['cart'][$cart_item_id];
        }
    }
    
    if (empty($items_to_process)) 
    {
        throw new Exception("Không có sản phẩm hợp lệ nào được chọn.");
    }

    $id_nguoi_dung = (int)$_SESSION['MaNguoiDung'];
    $sql_user = "SELECT ho_ten, dia_chi FROM nguoi_dung WHERE id_nguoi_dung = ?";
    $stmt_user = $connect->prepare($sql_user);
    $stmt_user->bind_param("i", $id_nguoi_dung);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $user_data = $result_user->fetch_assoc();
    $stmt_user->close();
    
    if (!$user_data) 
    {
        throw new Exception("Không tìm thấy thông tin người dùng.");
    }
    
    $ten_nguoi_nhan = $user_data['ho_ten'];
    $dia_chi_giao_hang = $user_data['dia_chi'];

    $tong_tien = 0;
    $phi_van_chuyen = 25000;
    
    foreach ($items_to_process as $item) 
    {
        $tong_tien += (float)$item['price'] * (int)$item['quantity'];
    }

    $tong_tien += $phi_van_chuyen;

    $sql_donhang = "INSERT INTO don_hang (id_nguoi_dung, ngay_dat_hang, tong_tien, ten_nguoi_nhan, dia_chi_giao_hang, trang_thai_don_hang) 
                    VALUES (?, NOW(), ?, ?, ?, 'Mới')";
    $stmt_donhang = $connect->prepare($sql_donhang);
    $stmt_donhang->bind_param("idss", $id_nguoi_dung, $tong_tien, $ten_nguoi_nhan, $dia_chi_giao_hang);
    $stmt_donhang->execute();
    
    $id_don_hang = $connect->insert_id;
    $stmt_donhang->close();

    $sql_chitiet = "INSERT INTO chi_tiet_don_hang (id_don_hang, id_san_pham, so_luong, gia_tai_thoi_diem_dat) 
                    VALUES (?, ?, ?, ?)";
    $stmt_chitiet = $connect->prepare($sql_chitiet);

    foreach ($items_to_process as $item) 
    {
        $id_sp = (int)$item['id'];
        $so_luong = (int)$item['quantity'];
        $gia_sp = (float)$item['price'];
        
        $stmt_chitiet->bind_param("iiid", $id_don_hang, $id_sp, $so_luong, $gia_sp);
        $stmt_chitiet->execute();
    }
    $stmt_chitiet->close();
    $connect->commit();
    
    foreach ($selected_ids as $cart_item_id) 
    {
        if (isset($_SESSION['cart'][$cart_item_id])) 
        {
            unset($_SESSION['cart'][$cart_item_id]);
        }
    }
    
    header("Location: giohang.php?payment=success");
    exit;
}catch (Exception $e) 
    {
        $connect->rollback();
        die("Thanh toán thất bại: " . $e->getMessage());
    }
    $connect->close();
?>