<?php
   require_once 'cauhinh.php'; // Đảm bảo bạn có file cauhinh.php trong thư mục TrangWeb
   session_start();

   // 1. XỬ LÝ TÌM KIẾM
   $search_keyword = '';
   $search_condition = ''; 

   if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
        $search_keyword = $connect->real_escape_string(trim($_GET['search']));
        // Tìm trong tên sản phẩm HOẶC mô tả
        $search_condition = " AND (sp.ten_san_pham LIKE '%$search_keyword%' OR sp.mo_ta LIKE '%$search_keyword%')";
   }

   // 2. HÀM HIỂN THỊ SẢN PHẨM (Tự động)
   function display_products($result)
   {
        // Lấy quyền từ Session
        $is_admin = isset($_SESSION['VaiTro']) && $_SESSION['VaiTro'] == 1;
        $is_logged_in = isset($_SESSION['MaNguoiDung']);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $product_id = $row["id_san_pham"];
                $detail_url = "chi_tiet.php?id=" . $product_id;
                $login_url = "../Admin/dangnhap.php"; // Đường dẫn sang Admin để đăng nhập

                echo '<a href="' . htmlspecialchars($detail_url) . '" style="text-decoration: none; color: inherit;">'; 
                echo '<div class="menu-item">'; 
                
                // ẢNH
                echo '  <img src="' . htmlspecialchars($row["hinh_anh"]) . '" alt="' . htmlspecialchars($row["ten_san_pham"]) . '">';
                
                // NỘI DUNG
                echo '  <div class="item-content">';
                echo '      <div class="item-info">';
                echo '          <h3>' . htmlspecialchars($row["ten_san_pham"]) . '</h3>'; 
                echo '          <p>' . htmlspecialchars($row["mo_ta"] ?? '') . '</p>'; 
                echo '      </div>';
                
                // GIÁ VÀ NÚT
                echo '      <div class="item-price-add">';
                if ($row["gia"] > 0) {
                    echo '      <span class="price">' . number_format($row["gia"], 0, '.', '.') . 'đ</span>'; 
                } else {
                    echo '      <span class="price"></span>';
                }    
                
                // Nút thêm (Dành cho khách)
                if ($is_logged_in) {
                    echo '      <button class="add-btn" onclick="window.location.href=\'' . htmlspecialchars($detail_url) . '\'; return false;"><i class="fas fa-plus"></i></button>';
                } else {
                    echo '      <button class="add-btn" onclick="if(confirm(\'Đăng nhập để đặt hàng?\')) { window.location.href=\'' . htmlspecialchars($login_url) . '\'; } return false;"><i class="fas fa-plus"></i></button>';
                }

                echo '      </div>';
                echo '  </div>';
                echo '</div>';   
                echo '</a>'; 
            }
        } else {
            echo "<p style='color:#888; font-style:italic; padding:10px;'>Không có món nào.</p>";
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
                        // Lệnh này lấy TOÀN BỘ danh mục bạn vừa thêm bên Admin
                        $sql_menu = "SELECT * FROM danh_muc ORDER BY id_danh_muc ASC";
                        $result_menu = $connect->query($sql_menu);
                        
                        if ($result_menu && $result_menu->num_rows > 0) {
                            while ($dm_menu = $result_menu->fetch_assoc()) {
                                // Tự động tạo link
                                echo '<li>
                                        <a href="#dm-' . $dm_menu['id_danh_muc'] . '">
                                            <i class="fas fa-utensils"></i> ' . htmlspecialchars($dm_menu['ten_danh_muc']) . '
                                        </a>
                                      </li>';
                            }
                        }
                    ?>
                </ul>
            </nav>

            <form action="danhsachsanpham.php" method="GET" class="search-form" style="padding: 15px 0 15px 0; background: none; border: none; margin-bottom: 0;">
                <input type="text" name="search" placeholder="Nhập tên món..." value="<?php echo htmlspecialchars($search_keyword); ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
                <?php if (!empty($search_keyword)): ?>
                    <a href="danhsachsanpham.php" class="clear-search-btn" style="display: block; text-align: center; margin-top: 5px; color: red;"><i class="fas fa-times"></i> Xóa tìm kiếm</a>
                <?php endif; ?>
            </form>

            <div class="notes">
                <h4>Lưu ý</h4>
                <ul>
                    <li>Sau khi đặt hàng sẽ có nhân viên liên lạc xác nhận đơn hàng.</li>
                    <li>Tùy số lượng đơn hàng thời gian chuẩn bị sẽ khác nhau.</li>
                </ul>
            </div>
        </aside>

        <main class="main-content">
            <?php if (!empty($search_keyword)): ?>
                <div style="background: #e3f2fd; padding: 10px 15px; margin-bottom: 20px; border-radius: 5px; color: #0d47a1;">
                    <i class="fas fa-info-circle"></i> Kết quả tìm kiếm cho: "<strong><?php echo htmlspecialchars($search_keyword); ?></strong>"
                </div>
            <?php endif; ?>

            <?php
                // 1. Lấy lại danh sách danh mục để tạo từng Section
                $sql_sections = "SELECT * FROM danh_muc ORDER BY id_danh_muc ASC";
                $result_sections = $connect->query($sql_sections);

                if ($result_sections && $result_sections->num_rows > 0) 
                {
                    // Vòng lặp: Chạy qua từng danh mục có trong Database
                    while ($dm = $result_sections->fetch_assoc()) 
                    {
                        $current_dm_id = $dm['id_danh_muc'];
                        
                        // 2. Tìm sản phẩm thuộc danh mục này
                        $sql_sp = "SELECT * FROM san_pham WHERE id_danh_muc = $current_dm_id" . $search_condition;
                        $result_sp = $connect->query($sql_sp);
                        
                        // Logic hiển thị: Luôn hiện tên danh mục, nếu có món thì hiện món
                        $show_section = true;
                        // Nếu đang tìm kiếm mà danh mục này ko có món nào khớp -> Ẩn đi cho gọn
                        if (!empty($search_keyword) && $result_sp->num_rows == 0) {
                            $show_section = false; 
                        }

                        if ($show_section) {
                            // Tạo Section với ID khớp với Menu (#dm-ID)
                            echo '<section id="dm-' . $current_dm_id . '" class="category-section">';
                            echo '    <h2><i class="fas fa-utensils"></i> ' . htmlspecialchars($dm['ten_danh_muc']) . '</h2>';
                            echo '    <div class="item-list">';
                            
                            display_products($result_sp); // Gọi hàm hiển thị sản phẩm

                            echo '    </div>';
                            echo '</section>';
                        }
                    }
                } 
                else 
                {
                    echo "<p>Chưa có danh mục nào trong hệ thống.</p>";
                }
            ?>
        </main>
    </div>

    <?php $connect->close(); ?>

    <?php 
        // Lưu ý đường dẫn Footer: Nếu file này nằm trong TrangWeb thì đường dẫn này có thể cần sửa lại
        // Kiểm tra xem footer.php của bạn nằm ở đâu. Thường là ../Footer/footer.php
        require_once '../Footer/footer.php'; 
    ?>

</body>
</html>