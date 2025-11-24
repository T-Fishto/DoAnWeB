<?php
    require_once 'cauhinh.php';

    session_start();
    // Hàm hiển thị sản phẩm (Đã tối ưu lấy quyền từ Session)
    function display_products($result)
    {
        // Lấy quyền trực tiếp từ Session
        $is_admin = isset($_SESSION['VaiTro']) && $_SESSION['VaiTro'] == 1;
        $is_login = isset($_SESSION['MaNguoiDung']);

        if ($result && $result->num_rows > 0) 
        {
            while ($row = $result->fetch_assoc()) 
            {
                $product_id = $row["id_san_pham"];
                $detail_url = "chi_tiet.php?id=" . $product_id;
                $login_url = "../Admin/dangnhap.php";

                echo '<div class="menu-item">'; 
                
                // --- 1. ẢNH SẢN PHẨM ---
                if ($is_admin) {
                    echo '  <img src="' . htmlspecialchars($row["hinh_anh"]) . '" alt="' . htmlspecialchars($row["ten_san_pham"]) . '">';
                } else {
                     if ($is_login) {
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
                echo '          <p>' . htmlspecialchars($row["mo_ta"] ?? '') . '</p>'; 
                echo '      </div>';
                echo '      <div class="item-price-add">';
                
                if ($row["gia"] > 0) {
                    echo '      <span class="price">' . number_format($row["gia"], 0, '.', '.') . 'đ</span>'; 
                } else {
                    echo '      <span class="price"></span>';
                }

                // --- 2. XỬ LÝ NÚT XÓA VÀ SỬA ---
                if ($is_admin) { 
                    $delete_url = "sanpham_xoa.php?id=" . $product_id;
                    $edit_url = "sanpham_sua.php?id=" . $product_id;
                    
                    echo '      <button class="add-btn" onclick="if(confirm(\'Bạn có chắc chắn muốn xóa món ' . htmlspecialchars($row["ten_san_pham"]) . '?\')) { window.location.href=\'' . htmlspecialchars($delete_url) . '\'; } return false;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>';

                    echo '      <button class="add-btn" onclick="window.location.href=\'' . htmlspecialchars($edit_url) . '\'; return false;">
                                    <i class="fa-solid fa-screwdriver-wrench"></i>
                                </button>';
                } else {
                    // Nút cho khách (bắt đăng nhập)
                    echo '      <button class="add-btn" onclick="if(confirm(\'Bạn cần đăng nhập trước khi xóa/sửa. Bạn có muốn đến trang đăng nhập không?\')) { window.location.href=\'' . htmlspecialchars($login_url) . '\'; } return false;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>';
                    echo '      <button class="add-btn" onclick="if(confirm(\'Bạn cần đăng nhập trước khi xóa/sửa. Bạn có muốn đến trang đăng nhập không?\')) { window.location.href=\'' . htmlspecialchars($login_url) . '\'; } return false;">
                                    <i class="fa-solid fa-screwdriver-wrench"></i>
                                </button>';
                }
                echo '      </div>';
                echo '  </div>';
                echo '</div>';
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
                    <?php
                        // Lấy toàn bộ danh mục từ bảng danh mục và sắp xep theo id_danh_muc
                        $sql_menu = "SELECT * FROM danh_muc ORDER BY id_danh_muc ASC";
                        $result_menu = $connect->query($sql_menu);
                        
                        if ($result_menu->num_rows > 0) {
                            while ($dm_menu = $result_menu->fetch_assoc()) {
                                // Tạo link neo dạng #dm-1, #dm-2... để liên kết với các section bên main giúp điều hướng nhanh
                                echo '<li>
                                        <a href="#dm-' . $dm_menu['id_danh_muc'] . '">
                                            <i class="fas fa-utensils"></i> ' . htmlspecialchars($dm_menu['ten_danh_muc']) . '
                                        </a>
                                      </li>';
                            }
                        }
                    ?>
                    
                   <?php if (isset($_SESSION['VaiTro']) && $_SESSION['VaiTro'] == 1): ?>
                        <li><a href="sanpham_them.php" style="color: #ffffffff;"><i class="fa-solid fa-plus"></i> THÊM SẢN PHẨM</a></li>
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
            <?php
                // 1. Lấy lại danh sách danh mục để tạo từng Section
                // (Phải chạy query lại vì result_menu ở trên đã dùng hết rồi)
                $sql_sections = "SELECT * FROM danh_muc ORDER BY id_danh_muc ASC";
                $result_sections = $connect->query($sql_sections);

                if ($result_sections->num_rows > 0) 
                {
                    // Vòng lặp: Chạy qua từng danh mục có trong hệ thống
                    while ($dm = $result_sections->fetch_assoc()) 
                    {
                        $current_dm_id = $dm['id_danh_muc'];
                        
                        // Tạo Section với ID khớp với Menu (#dm-1...)
                        echo '<section id="dm-' . $current_dm_id . '" class="category-section">';
                        echo '    <h2><i class="fas fa-utensils"></i> ' . htmlspecialchars($dm['ten_danh_muc']) . '</h2>';
                        echo '    <div class="item-list">';

                        // 2. Với mỗi danh mục, lấy sản phẩm thuộc về nó
                        // Lưu ý: Dùng id_danh_muc để tìm chính xác
                       $sql_sp = "SELECT sp.*, dm.mo_ta 
                           FROM san_pham sp
                           JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc
                           WHERE sp.id_danh_muc = $current_dm_id";
                        $result_sp = $connect->query($sql_sp);
                        
                        // 3. Gọi hàm hiển thị sản phẩm
                        display_products($result_sp);

                        echo '    </div>';
                        echo '</section>';
                    }
                } 
                else 
                {
                    echo "<p>Chưa có danh mục nào trong hệ thống.</p>";
                }
            ?>
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