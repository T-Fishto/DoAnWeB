<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - NB Coffee</title>
    </head>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-image: url("../images/Font/baner.jpg");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    /* background: linear-gradient(to right, #6a11cb, #2575fc); Gradient màu nền giống ảnh */
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh; /* Đảm bảo form căn giữa theo chiều dọc */
    margin: 0;
}

.login-container {
    background-color: #fff;
    padding: 30px 40px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Đổ bóng nhẹ */
    width: 100%;
    max-width: 380px; /* Chiều rộng tối đa của form */
    text-align: center;
}

.login-container h1 {
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

.form-group input[type="text"],
.form-group input[type="password"] {
    width: calc(100% - 20px); /* Trừ padding để input vừa khung */
    padding: 12px 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

.form-group input[type="text"]:focus,
.form-group input[type="password"]:focus {
    border-color: #6a11cb; /* Màu border khi focus */
    outline: none; /* Bỏ outline mặc định */
}

.login-button {
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

.login-button:hover {
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
    <div class="login-container">
        <form class="login-form" action="process_login.php" method="POST">
            <h1>Đăng nhập</h1>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
            </div>
            
            <button type="submit" class="login-button">Đăng nhập</button>
            
            <p class="signup-link">
                Bạn chưa có tài khoản? <a href="register.php">Đăng ký Tại đây</a>
            </p>
        </form>
    </div>
</body>
</html>