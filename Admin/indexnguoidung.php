<?php
    ob_start();
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "qltp"; 
    
    $connect = new mysqli($servername, $username, $password, $dbname); 

    if ($connect->connect_error) {
        die("Không kết nối :" . $connect->connect_error);
    } 
    
    $connect->set_charset("utf8mb4");

    $do = $_GET['do'] ?? 'trangchu';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Nunito', Arial, sans-serif; 
            background-color: #f8f9fa;
            margin: 0; 
            padding: 0; 
        }
        header, footer { 
            background-color: #4CAF50;
            color: white; 
            padding: 15px 20px; 
            text-align: center; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        header h1 { 
            margin: 0; 
            font-weight: 700;
        }
        header nav a { 
            color: white; 
            margin: 0 10px; 
            text-decoration: none; 
            font-weight: 600;
            transition: opacity 0.2s;
        }
        header nav a:hover {
            opacity: 0.8;
        }
        main { min-height: 60vh; }
        footer {
            margin-top: 30px;
            box-shadow: none;
            border-top: 1px solid #e0e0e0;
        }

        .user-container { 
            width: 95%; 
            margin: 20px auto; 
            background-color: #ffffff; 
            padding: 20px; 
            border-radius: 8px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.1); 
        }

        .user-container h3 { 
            color: #333; 
            border-bottom: 2px solid #f0f0f0; 
            padding-bottom: 10px; 
        }

        .user-table { 
            width: 100%; 
            border-collapse: collapse; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
        }

        .user-table th, .user-table td { 
            padding: 12px 15px; 
            border: 1px solid #ddd; 
            text-align: left; 
            vertical-align: middle; 
        }

        .user-table th { 
            background-color: #4CAF50; 
            color: white; 
            font-weight: 700; 
        } 

        .user-table tbody tr:hover { 
            background-color: #f5f5f5; 
        }

        .user-table .role-link { 
            text-decoration: none; 
            font-weight: bold; 
        }

        .user-table .role-link.lower-role { 
            color: #e74c3c; 
        }

        .user-table .role-link.raise-role { 
            color: #27ae60; 
        }

        .user-table .action-links { 
            text-align: center; 
        }

        .user-table .action-links a { 
            text-decoration: none; 
            margin: 0 5px; 
            font-size: 1.1em;
            width: 25px; 
            display: inline-block; 
        }

        .icon-unlock { 
            color: #27ae60; 
        }

        .icon-lock { 
            color: #e74c3c; 
        }
        
        .icon-edit { 
            color: #007bff; 
        }
        
        .icon-delete { 
            color: #dc3545; 
        }

        .user-table .action-links a:hover i { 
            opacity: 0.7; 
        }
        
        .btn-add-user { 
            display: inline-block; 
            margin-top: 20px; 
            padding: 10px 20px; 
            background-color: #FF9800;
            color: white; 
            text-decoration: none; 
            border-radius: 5px; 
            font-weight: 700;
            transition: background-color 0.2s;
        }
        .btn-add-user:hover { 
            background-color: #FB8C00;
        }

        .form-container { 
            width: 90%; 
            max-width: 600px; 
            margin: 20px auto; 
            padding: 20px; 
            background-color: #f9f9f9; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
        }

        .form-container h3 { 
            color: #333; 
            border-bottom: 2px solid #4CAF50; 
            padding-bottom: 10px; 
        }

        .form-group { 
            margin-bottom: 15px; 
        }

        .form-group label { 
            display: block; 
            margin-bottom: 5px; 
            font-weight: 600; 
            color: #555; 
        }

        .form-group input[type="text"], .form-group input[type="email"], .form-group input[type="password"], 
        .form-group textarea { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            box-sizing: border-box; 
            font-family: 'Nunito', Arial, sans-serif; 
        }

        .form-group input[readonly] { background-color: #eee; cursor: not-allowed; }

        .form-group textarea { 
            resize: vertical; 
            min-height: 80px; 
        }

        .password-note { 
            font-size: 0.9em; 
            color: #777; 
        }

        .btn-submit { 
            display: inline-block; 
            padding: 10px 20px; 
            background-color: #4CAF50; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            font-weight: 700; 
            cursor: pointer; 
            font-family: 'Nunito', Arial, sans-serif; 
            transition: background-color 0.2s; 
        }

        .btn-submit:hover { 
            background-color: #45a049; 
        }

        .error-box { 
            background-color: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
            padding: 10px 15px; 
            border-radius: 4px; 
            margin-bottom: 15px; 
        }

        .dashboard-welcome {
            text-align: center;
            margin: 30px auto;
            max-width: 800px;
        }

        .dashboard-welcome h2 {
            color: #333;
            font-weight: 700;
            font-size: 2em;
        }

        .dashboard-welcome p {
            color: #666;
            font-size: 1.1em;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
            gap: 20px;
            padding: 0 5%;
            margin: 20px auto;
            max-width: 1200px;
        }

        .dashboard-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            padding: 25px;
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        .dashboard-card i {
            font-size: 3em;
            color: #4CAF50; 
        }

        .dashboard-card h3 {
            font-size: 1.4em;
            font-weight: 700;
            margin: 15px 0 10px 0;
        }

        .dashboard-card p {
            font-size: 0.95em;
            color: #777;
            line-height: 1.5;
        }
    </style>
</head>

<body>
    <header>
        <h1><i class="fa-solid fa-utensils"></i> Trang Quản Trị</h1>
        <nav>
            <a href="indexnguoidung.php?do=trangchu">Trang Chủ</a>
            <a href="indexnguoidung.php?do=nguoidung">Quản Lý Người Dùng</a>
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

    <footer>
        <p>Bản quyền &copy; <?php echo date("Y"); ?>.</p>
    </footer>

    <?php
        $connect->close();
        ob_end_flush();
    ?>
</body>
</html>