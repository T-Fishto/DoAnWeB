<?php
    require_once 'cauhinh.php';

    session_start();
    //kiểm tra xem biến Session VaiTro có tồn tại và có bằng 1
    $is_admin = isset($_SESSION['VaiTro']) && $_SESSION['VaiTro'] == 1;  
   function display_products($result)
    {
        global $is_admin; // Lấy biến kiểm tra Admin
        
        // Kiểm tra đăng nhập cơ bản (để phục vụ nút xem chi tiết/mua)
        $is_logged_in = isset($_SESSION['MaNguoiDung']);

        if ($result->num_rows > 0) 
        {
            while ($row = $result->fetch_assoc()) 
            {
                $product_id = $row["id_san_pham"];
                
                // Các đường dẫn
                $detail_url = "chi_tiet.php?id=" . $product_id;
                // Đường dẫn đăng nhập (chuyển hướng về trang Admin login)
                $login_url = "../Admin/dangnhap.php";

                echo '<div class="menu-item">'; 
                
                // --- 1. ẢNH SẢN PHẨM ---
                if ($is_admin) {
                    //htmlspecialchars biến đổi các ký tự đặc biệt thành dạng mã an toàn trước khi in ra trình duyệt.
                    echo '  <img src="' . htmlspecialchars($row["hinh_anh"]) . '" alt="' . htmlspecialchars($row["ten_san_pham"]) . '">';
                } else {
                     if ($is_logged_in) {
                        echo '<a href="' . htmlspecialchars($detail_url) . '">';
                    } else {
                        echo '<a href="#" onclick="if(confirm(\'Bạn cần đăng nhập để xem chi tiết. Đăng nhập ngay?\')) { window.location.href=\'' . htmlspecialchars($login_url) . '\'; } return false;">';
                    }
                    echo '  <img src="' . htmlspecialchars($row["hinh_anh"]) . '" alt="' . htmlspecialchars($row["ten_san_pham"]) . '">';
                    echo '</a>';
                }

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

                // --- 2. XỬ LÝ NÚT XÓA VÀ SỬA ) ---
                
                if ($is_admin) { 
                    $delete_url = "sanpham_xoa.php?id=" . $product_id;
                    echo '      <button class="add-btn" 
                                onclick="if(confirm(\'Bạn có chắc chắn muốn xóa món ' . htmlspecialchars($row["ten_san_pham"]) . '?\')) { window.location.href=\'' . htmlspecialchars($delete_url) . '\'; } return false;">
                                <i class="fa-solid fa-trash"></i>
                                </button>';

                    $edit_url = "sanpham_sua.php?id=" . $product_id;
                    echo '      <button class="add-btn" 
                                onclick="window.location.href=\'' . htmlspecialchars($edit_url) . '\'; return false;">
                                <i class="fa-solid fa-screwdriver-wrench"></i>
                                </button>';
                } else {
                    echo '      <button class="add-btn" 
                                onclick="if(confirm(\'Bạn cần đăng nhập trước khi xóa. Bạn có muốn đến trang đăng nhập không?\')) { window.location.href=\'' . htmlspecialchars($login_url) . '\'; } return false;">
                                <i class="fa-solid fa-trash"></i>
                                </button>';
                    echo '      <button class="add-btn" 
                                onclick="if(confirm(\'Bạn cần đăng nhập trước khi sửa. Bạn có muốn đến trang đăng nhập không?\')) { window.location.href=\'' . htmlspecialchars($login_url) . '\'; } return false;">
                                <i class="fa-solid fa-screwdriver-wrench"></i>
                                </button>';
                }
                echo '      </div>';
                echo '  </div>';
                echo '</div>';
            }
        } else 
        {
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
    <link rel="stylesheet" href="css/footer.css">
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
                   <?php if ($is_admin): ?>
                        <li><a href="sanpham_them.php"><i class="fa-solid fa-plus"></i> THÊM SẢN PHẨM</a></li>
                   <?php endif; ?>
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
                        $sql_mon_an = "SELECT sp.id_san_pham, sp.ten_san_pham, sp.gia, sp.hinh_anh, dm.mo_ta 
                                    FROM san_pham sp 
                                    JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc 
                                    WHERE dm.ten_danh_muc = 'Món ăn'";
                        $result_mon_an = $connect->query($sql_mon_an);
                        display_products($result_mon_an);
                    ?>
                </div>
            </section>

            <section id="nuoc-giai-khat" class="category-section">
                <h2><i class="fas fa-cocktail"></i> NƯỚC GIẢI KHÁT</h2>
                <div class="item-list">
                    <?php
                        $sql_nuoc = "SELECT sp.id_san_pham, sp.ten_san_pham, sp.gia, sp.hinh_anh, dm.mo_ta 
                                    FROM san_pham sp 
                                    JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc 
                                    WHERE dm.ten_danh_muc = 'Nước giải khát'";
                        $result_nuoc = $connect->query($sql_nuoc);
                        display_products($result_nuoc);
                    ?>
                </div>
            </section>

            <section id="do-an-vat" class="category-section">
                <h2><i class="fas fa-cookie-bite"></i> ĐỒ ĂN VẶT</h2>
                <div class="item-list">
                    <?php
                        $sql_an_vat = "SELECT sp.id_san_pham, sp.ten_san_pham, sp.gia, sp.hinh_anh, dm.mo_ta 
                                    FROM san_pham sp 
                                    JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc 
                                    WHERE dm.ten_danh_muc = 'Đồ ăn vặt'";
                        $result_an_vat = $connect->query($sql_an_vat);
                        display_products($result_an_vat);
                        ?>
                </div>
            </section>
        </main>
    </div>
    <?php
    $connect->close();
    ?>

    <?php 
        require_once '../Footer/footer.php'; 
    ?>

</body>
</html>