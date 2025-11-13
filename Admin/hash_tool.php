<?php
// CÔNG CỤ BĂM MẬT KHẨU (CHẠY 1 LẦN)

$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "qltp";

$connect = new mysqli($servername, $username_db, $password_db, $dbname);
if ($connect->connect_error) {
    die("Kết nối CSDL thất bại: " . $connect->connect_error);
}
$connect->set_charset("utf8mb4");

// 1. Mật khẩu trần từ CSDL của bạn
$pass_admin = 'mk123';
$pass_user = 'mknd123';

// 2. Băm mật khẩu
$hash_admin = password_hash($pass_admin, PASSWORD_DEFAULT);
$hash_user = password_hash($pass_user, PASSWORD_DEFAULT);

echo "Mật khẩu băm cho 'admin': " . $hash_admin . "<br>";
echo "Mật khẩu băm cho 'user': " . $hash_user . "<br>";

// 3. Cập nhật vào CSDL
$sql_admin = "UPDATE nguoi_dung SET mat_khau = ? WHERE ten_dang_nhap = 'admin'";
$stmt_admin = $connect->prepare($sql_admin);
$stmt_admin->bind_param("s", $hash_admin);
$stmt_admin->execute();
echo "-> Đã cập nhật mật khẩu cho 'admin'.<br>";

$sql_user1 = "UPDATE nguoi_dung SET mat_khau = ? WHERE ten_dang_nhap = 'nguoidung1'";
$stmt_user1 = $connect->prepare($sql_user1);
$stmt_user1->bind_param("s", $hash_user);
$stmt_user1->execute();
echo "-> Đã cập nhật mật khẩu cho 'nguoidung1'.<br>";

$sql_user2 = "UPDATE nguoi_dung SET mat_khau = ? WHERE ten_dang_nhap = 'nguoidung2'";
$stmt_user2 = $connect->prepare($sql_user2);
$stmt_user2->bind_param("s", $hash_user);
$stmt_user2->execute();
echo "-> Đã cập nhật mật khẩu cho 'nguoidung2'.<br>";

echo "--- HOÀN TẤT! ---";

$stmt_admin->close();
$stmt_user1->close();
$stmt_user2->close();
$connect->close();
?>