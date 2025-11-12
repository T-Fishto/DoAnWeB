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

//XỬ LÝ TÌM KIẾM ---
$search_keyword = '';
$search_condition = '';

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    // Làm sạch từ khóa tìm kiếm
    $search_keyword = $conn->real_escape_string(trim($_GET['search']));
    // Tạo điều kiện tìm kiếm: Tìm trong tên sản phẩm HOẶC mô tả
    $search_condition = " AND (sp.ten_san_pham LIKE '%$search_keyword%' OR dm.mo_ta LIKE '%$search_keyword%')";
}

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
                </ul>
            </nav>

            <form action="danhsachsanpham.php" method="GET" class="search-form" style="padding: 15px 0 15px 0; background: none; border: none; margin-bottom: 0;">
                <input type="text" name="search" placeholder="Nhập tên món hoặc mô tả..." value="<?php echo htmlspecialchars($search_keyword); ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
                <?php if (!empty($search_keyword)): ?>
                    <a href="danhsachsanpham.php" class="clear-search-btn" style="display: block; text-align: center; margin-top: 5px;"><i class="fas fa-times"></i> Xóa tìm kiếm</a>
                <?php endif; ?>
            </form>

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
            <?php if (!empty($search_keyword)): ?>
                <section id="search-results" class="category-section">
                    <h2><i class="fas fa-clipboard-list"></i> Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($search_keyword); ?>"</h2>
                    <div class="item-list">
                        </div>
                </section>
            <?php endif; ?>

            <section id="mon-an" class="category-section">
                <h2><i class="fas fa-hamburger"></i> MÓN ĂN</h2>
                <div class="item-list">
                    <?php
                    // [ĐÃ SỬA] Áp dụng $search_condition VÀ KHÔNG ẩn danh mục khi tìm kiếm
                    $sql_mon_an = "SELECT sp.id_san_pham, sp.ten_san_pham, sp.gia, sp.hinh_anh, dm.mo_ta 
                                   FROM san_pham sp 
                                   JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc 
                                   WHERE dm.ten_danh_muc = 'Món ăn' " . $search_condition;
                    $result_mon_an = $conn->query($sql_mon_an);
                    display_products($result_mon_an);

                    // BỔ SUNG: Hiển thị thông báo nếu tìm kiếm không có kết quả trong danh mục này
                    if (!empty($search_keyword) && $result_mon_an->num_rows == 0) {
                        echo "<p style='padding: 10px; color: #777;'>Không tìm thấy món ăn nào khớp với từ khóa.</p>";
                    }
                    ?>
                </div>
            </section>

            <section id="nuoc-giai-khat" class="category-section">
                <h2><i class="fas fa-cocktail"></i> NƯỚC GIẢI KHÁT</h2>
                <div class="item-list">
                    <?php
                    // [ĐÃ SỬA] Áp dụng $search_condition
                    $sql_nuoc = "SELECT sp.id_san_pham, sp.ten_san_pham, sp.gia, sp.hinh_anh, dm.mo_ta 
                                 FROM san_pham sp 
                                 JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc 
                                 WHERE dm.ten_danh_muc = 'Nước giải khát' " . $search_condition;
                    $result_nuoc = $conn->query($sql_nuoc);
                    display_products($result_nuoc);
                    
                    if (!empty($search_keyword) && $result_nuoc->num_rows == 0) {
                        echo "<p style='padding: 10px; color: #777;'>Không tìm thấy nước giải khát nào khớp với từ khóa.</p>";
                    }
                    ?>
                </div>
            </section>

            <section id="do-an-vat" class="category-section">
                <h2><i class="fas fa-cookie-bite"></i> ĐỒ ĂN VẶT</h2>
                <div class="item-list">
                    <?php
                    // [ĐÃ SỬA] Áp dụng $search_condition
                    $sql_an_vat = "SELECT sp.id_san_pham, sp.ten_san_pham, sp.gia, sp.hinh_anh, dm.mo_ta 
                                   FROM san_pham sp 
                                   JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc 
                                   WHERE dm.ten_danh_muc = 'Đồ ăn vặt' " . $search_condition;
                    $result_an_vat = $conn->query($sql_an_vat);
                    display_products($result_an_vat);
                    
                    if (!empty($search_keyword) && $result_an_vat->num_rows == 0) {
                        echo "<p style='padding: 10px; color: #777;'>Không tìm thấy đồ ăn vặt nào khớp với từ khóa.</p>";
                    }
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