
<?php
    session_start();
    $servername = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $dbname = "qltp"; 
    //Tạo kết nối
    $conn = new mysqli($servername, $username, $password, $dbname);
    //Đặt charset là utf8mb4 để hiển thị tiếng Việt chính xác
    $conn->set_charset("utf8mb4");
    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối CSDL thất bại: " . $conn->connect_error);
    }
    //Viết câu truy vấn SQL lấy 10 sản phẩm
    $sql = "SELECT * FROM quang_cao LIMIT 10";
    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Food & Drink</title>
    <link rel="stylesheet" href="css/trangchu.css">
    <link rel="stylesheet" href="images/Font/themify-icons/themify-icons.css" referrerpolicy="no-referrer" />
    <script src="js/giaodien.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
 <section class="top">
    <div class="container">
        <div class="row justify-content"> 
            
            <div class="top-left-group">
                
                <div class="logo">
                    <img src="images/Font/logoNB.jpg" alt="">
                </div>
                
                <?php
                // Kiểm tra xem người dùng đã đăng nhập VÀ có VaiTro = 0 hay chưa
                if (isset($_SESSION['VaiTro']) && $_SESSION['VaiTro'] == 0) {
                    //Xin Chào
                    echo '<div class="user-welcome-header">';
                    echo '  <span class="welcome-text">Xin chào,</span>';
                    echo '  <span class="user-name">' . htmlspecialchars($_SESSION['HoVaTen']) . '</span>';
                    echo '</div>';
                    // Hiện giỏ hàng
                    echo '<li class="top_login">';
                    echo '    <a href="giohang.php">';
                    echo '        <i class="fa-solid fa-cart-shopping"></i>';
                    echo '    </a>';
                    echo '</li>';
                } else {
                    // ---- CHƯA ĐĂNG NHẬP ----
                    echo '<li class="top_login">';
                    echo '    <a href="../Admin/dangnhap.php">';
                    echo '        <i class="ti-user ic"></i>';
                    echo '        <span>Đăng Nhập</span>'; 
                    echo '    </a>';
                    echo '</li>';
                }
                ?>
            </div> <div class="top_right">
                <div class="menu_bar"> 
                    <span></span> 
                </div> 
            </div> 
            <div class="menu-Items">
                <li class="menu-items1">
                    <span>Coffee NB</span> <i class="fa-solid fa-mug-hot"></i>
                </li>
                <li class="menu-items">
                    <i class="fa-solid fa-bowl-rice ic"></i>
                    <a href="danhsachsanpham.php">Thực Đơn</a>
                </li>
                <li class="menu-items">
                    <i class="fa-solid fa-thumbs-up ic"></i>
                    <a href="#footer">Liên Hệ</a>
                </li>
                <li class="menu-items">
                    <i class="fa-solid fa-child-reaching ic"></i>
                    <a href="#about">Về Chúng Tôi</a>
                </li>
                <?php
                // Thêm nút Đăng Xuất vào ĐÂY nếu đã đăng nhập
                if (isset($_SESSION['VaiTro']) && $_SESSION['VaiTro'] == 0) {
                    echo '<li class="menu-items">'; // Dùng class .menu-items cho đồng bộ
                    echo '    <i class="ti-share ic"></i>';
                    echo '    <a href="../Admin/dangxuat.php">Đăng Xuất</a>';
                    echo '</li>';
                }
                ?>
            </div>
        </div>
    </div>
    </section>
    <section class="big-image">
        <div class="big-content">
            <h2>Food & Drink</h2>   
            <p>Chào mừng bạn đến với thế giới ẩm thực</p>
            <a href="danhsachsanpham.php"><button class="big-content-btn btn">Menu</button></a>
        </div>
    </section>   
    <ul>
        <li>
          <i class="fa-solid fa-fire"></i>  
          <span id="span">Các món ăn nổi bậc</span>               
        </li>
    </ul>     
    <!-- Giữa Trang --> 
    <div class="phangiua">
        <?php
        // Kiểm tra xem có dữ liệu trả về không
        if ($result->num_rows > 0) {           
            while($row = $result->fetch_assoc()) {
            echo '<div class="item">';       
            // In ra dữ liệu trực tiếp từ $row
            echo '<a href="' . $row["duong_dan_lien_ket"] . '">';
            echo '    <img width="200" height="150" src="' . $row["hinh_anh_banner"] . '">';
            echo '</a>'; 
            echo '<h4>' . $row["tieu_de"] . '</text-algin=h4>';
            echo '<p>' . $row["ten_mon"] . '</p>';
            echo '<p>⭐ ' . $row["so_sao"] . ' | 🕒 ' . $row["ngay"] . '</p>';
            echo '<p><strong>' . $row["tag"] . '</strong></p>';
            // Kết thúc một "item"
            echo '</div>';  
            } 
        }
        else {
            echo "<p>Không có sản phẩm nào để hiển thị.</p>";
        }
        $conn->close();
        ?>
    </div>
       <!-- Về menu và nhà hàng -->
    <section class="about-us">
        <div class="about-us-container">
            <div class="about-us-image">
                </div>
            
            <div id="about" class="about-us-content">
                <h2>NB FOOD VIETNAM</h2>
                <span class="decorator-line"></span>
                <p>
                    NB Food VN cung cấp các phần ăn lành mạnh hàng tuần giúp bạn duy trì
                    một lối sống khỏe. Chúng tôi tập trung vào chế độ ăn cân bằng được thiết 
                    kế chuyên biệt để hỗ trợ bạn kiểm soát cân nặng một cách hiệu quả nhất.
                </p>
                <p>
                    Nếu bạn đang tìm kiếm những bữa ăn ngon và tốt cho sức khỏe được 
                    chuẩn bị sẵn ở Saigon thì NB Food là một lựa chọn tối ưu. Thực đơn đa 
                    dạng với hơn 100 món của chúng tôi có thể giúp bạn thưởng thức mà 
                    không ngán trong hơn 1 tháng.
                </p>
                <p>
                    Cảm ơn các bạn đã đọc! Chúc mọi người một ngày tốt lành
                </p>
            </div>
        </div>

    </section> 
    <!-- Giới thiệu cách hoạt động -->
    <section class="how-to-order">
        <h2>CÁCH ĐẶT HÀNG</h2>
        <span class="decorator-line"></span>

        <div class="how-to-order-container">
            <div class="order-step-card">
                <i class="fa-solid fa-file-invoice step-icon"></i>
                <h3>Chọn Gói Ăn</h3>
                <p>Chọn gói ăn phù hợp với nhu cầu của bạn và điền đầy đủ thông tin giao hàng</p>
            </div>

            <div class="order-step-card">
                <i class="fa-solid fa-fire-burner step-icon"></i>
                <h3>NB Food nấu</h3>
                <p>Chúng tôi lựa chọn những nguyên liệu tốt nhất, và nấu trong bếp công nghiệp hiện đại</p>
            </div>

            <div class="order-step-card">
                <i class="fa-solid fa-truck-fast step-icon"></i>
                <h3>Giao hàng</h3>
                <p>Đội ngũ giao hàng của NB Food sẽ giao tận nơi các phần ăn cho bạn mỗi ngày</p>
            </div>

            <div class="order-step-card">
                <i class="fa-solid fa-utensils step-icon"></i>
                <h3>Thưởng thức</h3>
                <p>Không cần suy nghĩ, shopping hay nấu nướng dầu mỡ, chỉ cần hâm và thưởng thức!</p>
            </div>
        </div>
    </section>
    <!-- Chân trang -->
   <footer id="footer"  class="footer">
        <div class="footer-container">
            <div class="footer-column-left">
                <h3>Về Food & Drink</h3>
                <ul>
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Coffee NB, 55 2 Phường Mỹ Xuyên,Thành Phố Long Xuyên,An Giang</span>
                    </li>
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <a href="tel:0123456789">0123456789</a>
                    </li>
                </ul>
            </div>
            <div class="footer-column-center">
                <h3>Liên hệ Email</h3>
                <ul>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <a href="https://mail.google.com/ " target="_blank">thang_dpm235479@student.agu.edu.vn</a>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <a href="https://mail.google.com/" target="_blank">thanh_dpm235480@student.agu.edu.vn</a>
                    </li>
                </ul>
            </div>
            <div class="footer-column-right social-column"> <h3>Theo dõi chúng tôi</h3>
                <ul class="footer-social-list">
                    <li>
                        <a href="https://www.facebook.com/" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                            <span>Facebook</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/" target="_blank">
                            <i class="fab fa-instagram"></i>
                            <span>Instagram</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://github.com/" target="_blank">
                            <i class="fa-solid fa-cat"></i>
                            <span>GitHub</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            Bản Quyền Bởi © 2025 - Website Food & Drink
        </div>
    </footer>
</body>
</html>