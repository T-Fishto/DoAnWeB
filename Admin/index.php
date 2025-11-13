<?php
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "qltp";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $conn->set_charset("utf8mb4");
    
    if ($conn->connect_error) 
    {
        die("Kết nối CSDL thất bại: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM quang_cao LIMIT 10";
    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Food & Drink</title>

    <link rel="stylesheet" href="css/giaodien.css">
    <link rel="stylesheet" href="css/footer.css">

    <link rel="stylesheet" href="images/Font/themify-icons/themify-icons.css" referrerpolicy="no-referrer" />
    <script src="js/giaodien.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <section class="top">
        <div class="container">
            <div class="row justify-content">	
                <div class="logo">
                    <img src="images/Font/logoNB.jpg" alt="">
                </div>     

                <div class="menu_bar">
                    <span></span> 
                </div>

                <div class="menu-Items">
                    <li class="menu-items1">
                        <span>Coffee NB</span> 
                        <i class="fa-solid fa-mug-hot"></i>
                    </li>        
                    <li class="menu-items">
                        <i class="fa-solid fa-circle-info ic"></i>
                        <a href="">Thông Tin</a>
                    </li>
                    <?php
                    if (isset($_SESSION['MaNguoiDung'])) 
                    {                      
                        if ($_SESSION['VaiTro'] == 1) 
                        {
                            echo '<li class="menu-items">';
                            echo '    <i class="ti-user ic"></i>';
                            echo '    <a href="indexnguoidung.php">Người Dùng</a>';
                            echo '</li>';
                        } 

                        echo '<li class="menu-items">';
                        echo '    <i class="fa-solid fa-bowl-rice ic"></i>';
                        echo '    <a href="danhsachsanpham.php">Sản Phẩm</a>';
                        echo '</li>';

                        echo '<li class="menu-items">';
                        echo '    <i class="fa-solid fa-chart-simple ic"></i>';
                        echo '    <a href="">Thống kê</a>';
                        echo '</li>';

                        echo '<li class="menu-items">';
                        echo '    <i class="ti-share ic"></i>';
                        echo '    <a href="dangxuat.php">Đăng Xuất</a>';
                        echo '</li>';

                    }
                     else 
                    {
                        echo '<li class="menu-items">';
                        echo '    <i class="ti-user ic"></i>';
                        echo '    <a href="dangnhap.php">Đăng Nhập</a>';
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

    <div class="phangiua">
        <?php
        if ($result->num_rows > 0) 
        {           
            while($row = $result->fetch_assoc()) 
            {
            echo '<div class="item">';          
            echo '<a href="' . $row["duong_dan_lien_ket"] . '">';
            echo '    <img width="200" height="150" src="' . $row["hinh_anh_banner"] . '">';
            echo '</a>';           
            echo '<h4>' . $row["tieu_de"] . '</text-algin=h4>';
            echo '<p>' . $row["ten_mon"] . '</p>';
            echo '<p>⭐ ' . $row["so_sao"] . ' | 🕒 ' . $row["ngay"] . '</p>';
            echo '<p><strong>' . $row["tag"] . '</strong></p>';          
            echo '</div>';            
            }
        }
        else
        {
            echo "<p>Không có sản phẩm nào để hiển thị.</p>";
        }       
        $conn->close();
        ?>
    </div>

    <section class="about-us">
        <div class="about-us-container">
            <div class="about-us-image"></div>

            <div class="about-us-content">
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
    
    <?php 
        require_once '../Footer/footer.php'; 
    ?>

</body>
</html>