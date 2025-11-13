<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dangki.css">
    <title>Đăng ký - NB Coffee</title>
</head>

<body>
    <div class="registration-container"> 
        <form id="registrationForm" class="registration-form" 
              action="dangki_submit.php" method="POST"
              onsubmit="return validateForm()"> 
            <h1>Đăng ký Tài khoản</h1>
            
            <div id="errorMessage" class="error-message hidden" role="alert"></div>

            <div class="form-group">
                <label for="fullName">Họ và tên</label>
                <input type="text" id="fullName" name="fullName" placeholder="Họ và tên" minlength="3" required>
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="tel" id="phone" name="phone" placeholder="Số điện thoại" 
                       pattern="[0-9]{10,11}" 
                       title="Số điện thoại phải có 10 hoặc 11 chữ số" 
                       required>
            </div>

            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <input type="text" id="address" name="address" placeholder="Địa chỉ" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>

            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" placeholder="Tên đăng nhập" minlength="5" required>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Mật khẩu" minlength="6" required>
            </div>

            <div class="form-group">
                <label for="confirmPassword">Xác nhận mật khẩu</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Xác nhận mật khẩu" required>
            </div>
            
            <button type="submit" class="registration-button">Đăng ký</button>
            
            <p class="signup-link">
                Bạn đã có tài khoản? <a href="dangnhap.php">Đăng nhập ngay.</a> 
            </p>
        </form>
    </div>

    <script src="/js/dangki.js" defer></script> 
    
</body>
</html>