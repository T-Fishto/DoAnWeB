<?php
// --- 1. KẾT NỐI CƠ SỞ DỮ LIỆU ---
$servername = "localhost"; // Máy chủ XAMPP
$username = "root";
$password = "";            // Mật khẩu CSDL mặc định
$dbname = "qltp"; // [CHỈNH SỬA] Tên database mới

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
// Đặt encoding là UTF-8 để hiển thị tiếng Việt
$conn->set_charset("utf8");

// --- ĐẦU FILE danhsachsanpham.php (trước khi HTML bắt đầu) ---
session_start(); // **BỔ SUNG NẾU CHƯA CÓ**
// ... (Kết nối CSDL) ...

// --- XỬ LÝ XÓA SẢN PHẨM (Chỉ Admin) ---
// Kiểm tra quyền Admin
$is_admin = isset($_SESSION['VaiTro']) && $_SESSION['VaiTro'] == 1;

if ($is_admin && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_san_pham_to_delete = $conn->real_escape_string($_GET['id']);
    
    // 1. Lấy thông tin ảnh để xóa file vật lý (Tùy chọn)
    $sql_img = "SELECT hinh_anh FROM san_pham WHERE id_san_pham = '$id_san_pham_to_delete'";
    $result_img = $conn->query($sql_img);
    if ($result_img->num_rows > 0) {
        $row_img = $result_img->fetch_assoc();
        $file_path = $row_img['hinh_anh'];
        // Xóa file ảnh (kiểm tra để tránh xóa file hệ thống)
        if (!empty($file_path) && file_exists($file_path) && strpos($file_path, 'images/sanpham/') !== false) {
             unlink($file_path);
        }
    }
    
    // 2. Thực hiện xóa trong CSDL
    $sql_delete = "DELETE FROM san_pham WHERE id_san_pham = '$id_san_pham_to_delete'";
    if ($conn->query($sql_delete) === TRUE) {
        // Chuyển hướng lại trang để làm sạch URL và hiển thị danh sách mới
        header("Location: danhsachsanpham.php?msg=deleted");
        exit();
    } else {
        // Có thể hiển thị lỗi
        // echo "Lỗi khi xóa sản phẩm: " . $conn->error;
    }
}
// ------------------------------------------
// ... (Các hàm display_products và các code PHP khác)

// --- 2. HÀM ĐỂ HIỂN THỊ SẢN PHẨM ---
function display_products($result)
{
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Lấy ID sản phẩm để tạo link
            $product_id = $row["id_san_pham"];
            // Đặt tên trang chi tiết là chi_tiet.php
            $detail_url = "chi_tiet.php?id=" . $product_id;          
            // BẮT ĐẦU: Bọc toàn bộ menu item trong thẻ <a>
            echo '<a href="' . htmlspecialchars($detail_url) . '" style="text-decoration: none; color: inherit;">'; 
            // Sử dụng ECHO để in HTML và dữ liệu từ CSDL
            echo '<div class="menu-item">';      
            // ... (Hình ảnh, nội dung thông tin)
            echo '  <img src="' . htmlspecialchars($row["hinh_anh"]) . '" alt="' . htmlspecialchars($row["ten_san_pham"]) . '">';
            echo '  <div class="item-content">';
            echo '      <div class="item-info">';
            echo '          <h3>' . htmlspecialchars($row["ten_san_pham"]) . '</h3>'; 
            echo '          <p>' . htmlspecialchars($row["mo_ta"]) . '</p>'; 
            echo '      </div>';
            echo '      <div class="item-price-add">';
            if ($row["gia"] > 0) {
                 echo '      <span class="price">' . number_format($row["gia"], 0, '.', '.') . 'đ</span>'; 
            } else {
                 echo '      <span class="price"></span>';
            }
            // SỬA LẠI: Thêm JavaScript confirm() và trỏ đến file xoa_sanpham.php

           $delete_url = "sanpham_xoa.php?id=" . $product_id;

            echo '          <button class="add-btn" 
                               onclick="if(confirm(\'Bạn có chắc chắn muốn xóa món ' . htmlspecialchars($row["ten_san_pham"]) . '?\')) { window.location.href=\'' . htmlspecialchars($delete_url) . '\'; } return false;">
                               <i class="fa-solid fa-trash"></i>
                            </button>';
           // Sửa lại dòng code cho nút SỬA (fa-screwdriver-wrench)
           $edit_url = "sanpham_sua.php?id=" . $product_id;
            echo '      <button class="add-btn" 
                           onclick="window.location.href=\'' . htmlspecialchars($edit_url) . '\'; return false;">
                           <i class="fa-solid fa-screwdriver-wrench"></i>
                         </button>';
            // Nút +: Thêm sự kiện onclick để đảm bảo click vào nút vẫn chuyển trang
            echo '          <button class="add-btn" onclick="window.location.href=\'' . htmlspecialchars($detail_url) . '\'; return false;"><i class="fas fa-plus"></i></button>'; 
            
            echo '      </div>';
            echo '  </div>';
            echo '</div>';
            
            echo '</a>'; // KẾT THÚC: Đóng thẻ <a>
        }
    } else {
        echo "<p>Chưa có món ăn nào trong danh mục này.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Thực Đơn</title>
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="menu-header">
        <div class="trangchu">
            <i class="fa-solid fa-house"></i> 
            <span><a href="index.php">TRANG CHỦ</a></span>
           
        </div>
        <div class="menu">
            <i class="fas fa-utensils"></i> 
            <span>MENU THỰC ĐƠN</span>
        </div>
    </header>
    <div class="container">
        <aside class="sidebar">
            <nav class="category-menu">
                <h3><i class="fas fa-bars"></i> Danh mục</h3>
                <ul>
                    <li><a href="#mon-an"><i class="fas fa-hamburger"></i> MÓN ĂN</a></li>
                    <li><a href="#nuoc-giai-khat"><i class="fas fa-cocktail"></i> NƯỚC GIẢI KHÁT</a></li>
                    <li><a href="#do-an-vat"><i class="fas fa-cookie-bite"></i> ĐỒ ĂN VẶT</a></li>
                    <li><a href="sanpham_them.php"><i class="fa-solid fa-plus"></i> THÊM SẢN PHẨM</a></li>
                </ul>
            </nav>
            <div class="notes">
                <h4>Lưu ý</h4>
                <ul>
                    <li>Sau khi đặt hàng sẽ có nhân viên liên lạc xác nhận đơn hàng.</li>
                    <li>Tùy số lượng đơn hàng thời gian chuẩn bị sẽ khác nhau.</li>
                    <li>Quý khách vui lòng kiểm tra sản phẩm trước khi nhận hàng.</li>
                </ul>
            </div>
        </aside>
        <main class="main-content">
            <section id="mon-an" class="category-section">
                <h2><i class="fas fa-hamburger"></i> MÓN ĂN</h2>
                <div class="item-list">
                    <?php
                    // [CẬP NHẬT SQL] THÊM sp.id_san_pham
                    $sql_mon_an = "SELECT sp.id_san_pham, sp.ten_san_pham, sp.gia, sp.hinh_anh, dm.mo_ta 
                                   FROM san_pham sp 
                                   JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc 
                                   WHERE dm.ten_danh_muc = 'Món ăn'";
                    $result_mon_an = $conn->query($sql_mon_an);
                    display_products($result_mon_an);
                    ?>
                </div>
            </section>
            <section id="nuoc-giai-khat" class="category-section">
                <h2><i class="fas fa-cocktail"></i> NƯỚC GIẢI KHÁT</h2>
                <div class="item-list">
                    <?php
                    // [CẬP NHẬT SQL] THÊM sp.id_san_pham
                    $sql_nuoc = "SELECT sp.id_san_pham, sp.ten_san_pham, sp.gia, sp.hinh_anh, dm.mo_ta 
                                 FROM san_pham sp 
                                 JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc 
                                 WHERE dm.ten_danh_muc = 'Nước giải khát'";
                    $result_nuoc = $conn->query($sql_nuoc);
                    display_products($result_nuoc);
                    ?>
                </div>
            </section>
            <section id="do-an-vat" class="category-section">
                <h2><i class="fas fa-cookie-bite"></i> ĐỒ ĂN VẶT</h2>
                <div class="item-list">
                    <?php
                    // [CẬP NHẬT SQL] THÊM sp.id_san_pham
                    $sql_an_vat = "SELECT sp.id_san_pham, sp.ten_san_pham, sp.gia, sp.hinh_anh, dm.mo_ta 
                                   FROM san_pham sp 
                                   JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc 
                                   WHERE dm.ten_danh_muc = 'Đồ ăn vặt'";
                    $result_an_vat = $conn->query($sql_an_vat);
                    display_products($result_an_vat);
                    ?>
                </div>
            </section>
        </main>
    </div>
    <?php
    // Đóng kết nối CSDL
    $conn->close();
    ?>
</body>
</html>