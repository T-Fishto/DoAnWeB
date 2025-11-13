<?php
    session_start();

    require_once 'cauhinh.php';

    if (!isset($_SESSION['VaiTro']) || $_SESSION['VaiTro'] != 1 || !isset($_GET['id'])) 
    {
        header("Location: danhsachsanpham.php");
        exit();
    }

    $product_id = $connect->real_escape_string($_GET['id']);
    $message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $ten_san_pham = $_POST['ten_san_pham'];
        $gia = $_POST['gia'];
        $id_danh_muc = $_POST['id_danh_muc'];
        $hinh_anh_old = $_POST['hinh_anh_old'];
        $hinh_anh_new = $hinh_anh_old;
        $upload_dir = '../images/sanpham/'; 

        if (isset($_FILES['hinh_anh_new']) && $_FILES['hinh_anh_new']['error'] == 0) 
        {
            $file_name = uniqid('sp_') . '_' . basename($_FILES['hinh_anh_new']['name']);
            $target_file = $upload_dir . $file_name;
            
            if (!is_dir($upload_dir)) 
            {
                mkdir($upload_dir, 0777, true);
            }

            if (move_uploaded_file($_FILES['hinh_anh_new']['tmp_name'], $target_file)) 
            {
                $hinh_anh_new = $target_file; 
                // Xóa ảnh cũ
                if (!empty($hinh_anh_old) && file_exists($hinh_anh_old)) 
                {
                    unlink($hinh_anh_old);
                }
            } 
            else 
            {
                $message = "Lỗi khi upload ảnh mới.";
            }
        }

        if (empty($message)) 
        {
            $stmt = $connect->prepare("UPDATE san_pham SET id_danh_muc = ?, ten_san_pham = ?, gia = ?, hinh_anh = ? WHERE id_san_pham = ?");
            $stmt->bind_param("isdsi", $id_danh_muc, $ten_san_pham, $gia, $hinh_anh_new, $product_id);

            if ($stmt->execute()) 
            {
                $message = "Cập nhật sản phẩm **thành công**!";
            } 
            else 
            {
                $message = "Lỗi: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    $sql_sp = "SELECT * FROM san_pham WHERE id_san_pham = ?";
    $stmt_sp = $connect->prepare($sql_sp);
    $stmt_sp->bind_param("i", $product_id);
    $stmt_sp->execute();
    $result_sp = $stmt_sp->get_result();

    if ($result_sp->num_rows == 0) 
    {
        die("Không tìm thấy sản phẩm.");
    }
    $product_data = $result_sp->fetch_assoc();
    $stmt_sp->close();

    $sql_dm = "SELECT id_danh_muc, ten_danh_muc FROM danh_muc ORDER BY id_danh_muc";
    $result_dm = $connect->query($sql_dm);
    $danh_muc_options = "";
    if ($result_dm->num_rows > 0) 
    {
        while ($row = $result_dm->fetch_assoc()) 
        {
            $selected = ($row['id_danh_muc'] == $product_data['id_danh_muc']) ? 'selected' : '';
            $danh_muc_options .= '<option value="' . $row['id_danh_muc'] . '" ' . $selected . '>' . htmlspecialchars($row['ten_danh_muc']) . '</option>';
        }
    }
    $connect->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Sản Phẩm</title>
    <link rel="stylesheet" href="css/menu.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        .admin-form { 
            max-width: 600px; 
            margin: 50px auto; 
            padding: 20px; 
            border: 1px solid #ccc; 
            border-radius: 8px; 
            background-color: #f9f9f9; 
        }

        .admin-form h2 { 
            text-align: center; 
            color: #333; 
        }

        .form-group { 
            margin-bottom: 15px; 
        }

        .form-group label { 
            display: block; 
            margin-bottom: 5px; 
            font-weight: bold; 
        }

        .form-group input[type="text"], 
        .form-group input[type="number"], 
        .form-group select { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
            box-sizing: border-box; 
        }

        .form-group input[type="file"] { 
            padding: 5px; 
        }

        .form-group button { 
            background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; float: right; 
            transition: background-color 0.3s ease;
        }
        
        .form-group button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .message { 
            padding: 10px; 
            margin-bottom: 15px; 
            border-radius: 4px; 
            font-weight: bold; 
            text-align: center; 
        }

        .success { 
            background-color: #d4edda; 
            color: #155724; 
            border-color: #c3e6cb; 
        }

        .error { 
            background-color: #f8d7da; 
            color: #721c24; 
            border-color: #f5c6cb; 
        }

        .current-image { 
            text-align: center; 
            margin-bottom: 15px; 
        }

        .current-image img { 
            max-width: 100%; 
            height: auto; 
            border: 1px solid #ddd; 
            padding: 5px; 
            border-radius: 4px; 
        }
    </style>
</head>
<body>
    <div class="admin-form">
        <h2><i class="fas fa-edit"></i> SỬA SẢN PHẨM: <?php echo htmlspecialchars($product_data['ten_san_pham']); ?></h2>
        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'thành công') !== false ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="sanpham_sua.php?id=<?php echo $product_id; ?>" enctype="multipart/form-data">
            <input type="hidden" name="hinh_anh_old" value="<?php echo htmlspecialchars($product_data['hinh_anh']); ?>">
            
            <div class="form-group">
                <label for="ten_san_pham">Tên Sản Phẩm:</label>
                <input type="text" id="ten_san_pham" name="ten_san_pham" class="form-control" value="<?php echo htmlspecialchars($product_data['ten_san_pham']); ?>" required>
            </div>

            <div class="form-group">
                <label for="gia">Giá (VNĐ):</label>
                <input type="number" id="gia" name="gia" min="0" step="1000" class="form-control" value="<?php echo htmlspecialchars($product_data['gia']); ?>" required>
            </div>

            <div class="form-group">
                <label for="id_danh_muc">Danh Mục:</label>
                <select id="id_danh_muc" name="id_danh_muc" class="form-control" required>
                    <?php echo $danh_muc_options; ?>
                </select>
            </div>
            
            <div class="form-group current-image">
                <label>Ảnh Hiện Tại:</label>
                <?php if (!empty($product_data['hinh_anh'])): ?>
                    <img src="<?php echo htmlspecialchars($product_data['hinh_anh']); ?>" alt="Ảnh hiện tại" width="150">
                <?php else: ?>
                    <p>Không có ảnh.</p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="hinh_anh_new">Chọn Ảnh Mới (Bỏ qua nếu không đổi):</label>
                <input type="file" id="hinh_anh_new" name="hinh_anh_new" class="form-control" accept="image/*">
            </div>
            
            <div class="form-group">
                <button type="submit" id="update-btn" disabled>Cập Nhật Sản Phẩm</button>
            </div>
        </form>
        <div style="clear: both;"></div>
        <p style="text-align: center; margin-top: 20px;"><a href="danhsachsanpham.php"><i class="fas fa-arrow-left"></i> Quay lại Menu</a></p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() 
        { 
            const updateButton = document.getElementById('update-btn');         
            const formInputs = document.querySelectorAll('.form-control');
            function enableButton() 
            {
                updateButton.disabled = false;
            }
            
            formInputs.forEach(function(input) 
            {
                if (input.type === 'file') 
                {
                    input.addEventListener('change', enableButton);
                } 
                else 
                {
                    input.addEventListener('input', enableButton);
                }
            });
        });
    </script>
</body>
</html>