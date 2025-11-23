<?php
//chỉ giữ lại mấy cái mấy cái trong body để cái cái header bên mấy trang khác đc sử dụng
    ob_start();
    require_once 'cauhinh.php';
    
    $connect->set_charset("utf8mb4");

    $do = $_GET['do'] ?? 'trangchu';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/giaodiennguoidung.css">
    <link rel="stylesheet" href="css/footer_quanly.css">
    <title>Trang Quản Trị</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <h1><i class="fa-solid fa-utensils"></i> Trang Quản Trị</h1>
        <nav>
            <a href="indexnguoidung.php?do=trangchu">Trang Chủ</a>
            <a href="indexnguoidung.php?do=nguoidung">Quản Lý Người Dùng</a>
            <a href="indexnguoidung.php?do=danhmuc">Quản Lý Danh Mục</a>

        </nav>
    </header>

    <main>
        <?php        
        switch ($do) 
        {
            case 'nguoidung':
                include 'nguoidung_danhsach.php'; 
                break;
                
            case 'nguoidung_sua':
                include 'nguoidung_sua.php';
                break;
                    
            case 'nguoidung_xoa':
                include 'nguoidung_xoa.php';
                break;
                    
            case 'nguoidung_kichhoat':
                include 'nguoidung_kichhoat.php';
                break;
                
            case 'dangky':
                include 'nguoidung_them.php'; 
                break;
            case 'danhmuc':
                include 'danhmuc_danhsach.php';
                break;
            case 'danhmuc_them':
                include 'danhmuc_them.php';
                break;
            case 'danhmuc_sua':
                include 'danhmuc_sua.php';
                break;
            case 'danhmuc_xoa':
                include 'danhmuc_xoa.php';
                break;

            case 'trangchu':
            default:
        ?>
                <div class="dashboard-welcome">
                    <h2>Chào mừng đến với Trang Quản Trị!</h2>
                    <p>Đây là trung tâm điều khiển của website. Vui lòng chọn một chức năng bên dưới để bắt đầu.</p>
                </div>
                
                <div class="dashboard-grid">
                    <a href="indexnguoidung.php?do=nguoidung" class="dashboard-card">
                        <i class="fa-solid fa-users-cog"></i>
                        <h3>Quản Lý Người Dùng</h3>
                        <p>Thêm, xóa, sửa và phân quyền tài khoản.</p>
                    </a>

                    <a href="index.php" class="dashboard-card">
                        <i class="fa-solid fa-eye"></i>
                        <h3>Xem Trang Web</h3>
                        <p>Xem trang web với tư cách khách truy cập.</p>
                    </a>
                </div>
        <?php
                break;
        }
        ?>
    </main>

    <?php
        $connect->close();
        ob_end_flush();
    ?>

    <?php
        require_once '../Footer/footer.php'; 
    ?>

</body>
</html>