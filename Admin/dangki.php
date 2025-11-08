<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - NB Coffee</title>
</head>
<style>
body {
    font-family: Arial, sans-serif;
    background-image: url("../Admin/images/Font/baner.jpg");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    /* background: linear-gradient(to right, #6a11cb, #2575fc); Gradient màu nền giống ảnh */
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh; /* Đảm bảo form căn giữa theo chiều dọc */
    margin: 0;
    padding: 50px 0;
}

.reristration-container {
    background-color: #fff;
    padding: 30px 40px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Đổ bóng nhẹ */
    width: 100%;
    max-width: 380px; /* Chiều rộng tối đa của form */
    text-align: center;
}

.reristration-container h1 {
    color: #333;
    margin-bottom: 25px;
    font-size: 28px;
    font-weight: bold;
}

.error-message {
    color: #e74c3c; /* Màu đỏ cho thông báo lỗi */
    margin-bottom: 15px;
    font-size: 14px;
}

.form-group {
    margin-bottom: 20px;
    text-align: left;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #555;
    font-size: 15px;
    font-weight: 600;
}

.form-group input[type="address"],
.form-group input[type="tel"],
.form-group input[type="email"],
.form-group input[type="text"],
.form-group input[type="password"] {
    width: calc(100% - 20px); /* Trừ padding để input vừa khung */
    padding: 12px 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

.form-group input[type="address"]:focus,
.form-group input[type="tel"]:focus,
.form-group input[type="email"]:focus,
.form-group input[type="text"]:focus,
.form-group input[type="password"]:focus {
    border-color: #6a11cb; /* Màu border khi focus */
    outline: none; /* Bỏ outline mặc định */
}

.reristration-button {
    width: 100%;
    padding: 13px;
    border: none;
    border-radius: 5px;
    background: linear-gradient(to right, #6a11cb, #2575fc); /* Gradient màu nút */
    color: white;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s ease;
}

.reristration-button:hover {
    background: linear-gradient(to right, #5a0eae, #1e5fc7); /* Đổi màu khi hover */
}

.signup-link {
    margin-top: 25px;
    font-size: 15px;
    color: #777;
}

.signup-link a {
    color: #2575fc; /* Màu link Đăng ký */
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s ease;
}

.signup-link a:hover {
    color: #6a11cb;
    text-decoration: underline;
}


</style>
 <body>
    <div class="reristration-container">
        <form class="reristration-form" action="dangki_submit.php" method="POST">
            <h1>Đăng ký Tài khoản</h1>
            <div class="form-group">
                <label for="fullName">Họ và tên</label>
                <input type="text" id="fullName" name="fullName" placeholder="Họ và tên" required>
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="tel" id="phone" name="phone" placeholder="Số điện thoại" required>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <input type="address" id="address" name="address" placeholder="Địa chỉ" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" placeholder="Tên đăng nhập" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Mật khẩu" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Xác nhận mật khẩu</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Xác nhận mật khẩu" required>
            </div>
            
            <button type="submit" class="reristration-button">Đăng ký</button>
            
            <p class="signup-link">
                Bạn đã có tài khoản? <a href="dangnhap.php">Đăng nhập ngay.</a> 
            </p>
        </form>
    </div>

    <script>
        // Hàm kiểm tra xác nhận mật khẩu trước khi gửi form
        function validateForm() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const errorElement = document.getElementById('errorMessage');

            if (password !== confirmPassword) {
                errorElement.textContent = "Lỗi: Mật khẩu và Xác nhận mật khẩu không khớp!";
                errorElement.classList.remove('hidden');
                return false; // Ngăn chặn việc gửi form
            } else {
                errorElement.classList.add('hidden');
                
                // Ở môi trường thực tế, bạn sẽ gửi dữ liệu đi ở đây.
                // Ở đây, tôi chỉ in ra console và ngăn chặn việc gửi thực tế (vì không có backend).
                console.log('Dữ liệu hợp lệ. Form sẵn sàng để gửi.');
                
                // Thay thế bằng fetch() API gửi dữ liệu đến server của bạn.
                // alert("Đăng ký thành công (Giả lập)! Vui lòng kiểm tra console.");
                
                return false; // Giữ form không reload trang trong môi trường này
            }
        }
    </script>
</body>
</html>