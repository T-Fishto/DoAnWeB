<?php
session_start();

$error_message = "";
if (isset($_SESSION['login_error'])) {
    $error_message = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}

$redirect_url = isset($_GET['redirect_url']) ? htmlspecialchars($_GET['redirect_url']) : '';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - NB Coffee</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/dangnhap.css">
</head>
<body>
    <div class="login-card-wrapper">
        <div class="left-panel">
            <div class="left-panel-content">
                <h2>Hương Vị <br> Cuộc Sống</h2>
                <p>Đắm mình theo hương vị phong phú và trải nghiệm từng ngụm, mỗi tách cà phê là một hành trình khám phá.</p>
                <a href="#" class="learn-more-btn">Tìm hiểu thêm</a>
            </div>
        </div>

        <div class="right-panel">
            <form action="dangnhap_submit.php" method="POST">
                <h1>Đăng nhập</h1>
                <p class="greeting">Chào mừng trở lại! Hãy đăng nhập để tiếp tục.</p>

                <?php if (!empty($error_message)): ?>
                    <div class="error-message">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <input type="hidden" name="redirect_url" value="<?php echo $redirect_url; ?>">

                <div class="form-group">
                    <label for="username">Email của bạn</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="login-button">Đăng nhập</button>
                
                <p class="signup-link">
                    Chưa có tài khoản? <a href="dangki.php">Đăng ký ngay!</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>