<?php
   session_start(); // Bắt đầu session để kiểm tra đăng nhập

/*
 * [QUAN TRỌNG] Kiểm tra xem Admin đã đăng nhập chưa
 * Chúng ta dùng logic giống hệt file Admin index.php của bạn
 */
if (!isset($_SESSION['MaNguoiDung']) || $_SESSION['VaiTro'] != 1) {
    // Nếu chưa đăng nhập hoặc không phải Admin, đuổi về trang đăng nhập
    header("Location: dangnhap.php");
    exit; // Dừng thực thi ngay lập tức
}

// 1. Kiểm tra xem ID sản phẩm có được gửi qua URL không
if (isset($_GET['id'])) {
    $product_id_to_delete = $_GET['id'];

    // --- 2. KẾT NỐI CƠ SỞ DỮ LIỆU ---
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "qltp"; // Tên database của bạn

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");

    /*
     * --- 3. VIẾT CÂU LỆNH DELETE (DÙNG PREPARED STATEMENT ĐỂ BẢO MẬT) ---
     * Tuyệt đối không viết: "DELETE FROM san_pham WHERE id_san_pham = $product_id_to_delete"
     * Cách đó rất nguy hiểm (SQL Injection)
     */

    // Chuẩn bị câu lệnh
    $sql = "DELETE FROM san_pham WHERE id_san_pham = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // "i" nghĩa là $product_id_to_delete là kiểu Integer (số)
        $stmt->bind_param("i", $product_id_to_delete);

        // Thực thi câu lệnh
        $stmt->execute();

        // Đóng statement
        $stmt->close();
    }
    
    // Đóng kết nối
    $conn->close();

    /*
     * --- 4. QUAY LẠI TRANG DANH SÁCH SẢN PHẨM ---
     * Sau khi xóa xong, tự động chuyển người dùng về trang danh sách
     */
    header("Location: danhsachsanpham.php");
    exit;

} else {
    // Nếu không có ID, chỉ cần quay về trang danh sách
    header("Location: danhsachsanpham.php");
    exit;
}
?>