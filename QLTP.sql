SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qltp`
--
CREATE DATABASE IF NOT EXISTS `qltp` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `qltp`;

-- --------------------------------------------------------

--
-- Table structure for table `nguoi_dung`
--
DROP TABLE IF EXISTS `nguoi_dung`;
CREATE TABLE IF NOT EXISTS `nguoi_dung` (
  `id_nguoi_dung` INT(11) NOT NULL AUTO_INCREMENT,
  `ten_dang_nhap` VARCHAR(100) NOT NULL UNIQUE,
  `mat_khau` VARCHAR(255) NOT NULL,
  `ho_ten` VARCHAR(255) COLLATE utf8mb4_unicode_ci,
  `email` VARCHAR(255) UNIQUE,
  `dia_chi` TEXT COLLATE utf8mb4_unicode_ci,
  `so_dien_thoai` VARCHAR(15),
  `vai_tro` TINYINT(1) DEFAULT 0,
  `Khoa` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_nguoi_dung`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `danh_muc`
--
DROP TABLE IF EXISTS `danh_muc`;
CREATE TABLE IF NOT EXISTS `danh_muc` (
  `id_danh_muc` INT(11) NOT NULL AUTO_INCREMENT,
  `ten_danh_muc` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mo_ta` TEXT COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id_danh_muc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `san_pham`
--
DROP TABLE IF EXISTS `san_pham`;
CREATE TABLE IF NOT EXISTS `san_pham` (
  `id_san_pham` INT(11) NOT NULL AUTO_INCREMENT,
  `id_danh_muc` INT(11) NOT NULL,
  `ten_san_pham` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gia` DECIMAL(10, 2) NOT NULL,
  `hinh_anh` VARCHAR(255),
  `trang_thai` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id_san_pham`),
  KEY `id_danh_muc` (`id_danh_muc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `don_hang`
--
DROP TABLE IF EXISTS `don_hang`;
CREATE TABLE IF NOT EXISTS `don_hang` (
  `id_don_hang` INT(11) NOT NULL AUTO_INCREMENT,
  `id_nguoi_dung` INT(11) NOT NULL,
  `ngay_dat_hang` DATETIME NOT NULL,
  `tong_tien` DECIMAL(10, 2) NOT NULL,
  `ten_nguoi_nhan` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dia_chi_giao_hang` TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
  `trang_thai_don_hang` VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Mới',
  PRIMARY KEY (`id_don_hang`),
  KEY `id_nguoi_dung` (`id_nguoi_dung`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_don_hang`
--
DROP TABLE IF EXISTS `chi_tiet_don_hang`;
CREATE TABLE IF NOT EXISTS `chi_tiet_don_hang` (
  `id_chi_tiet` INT(11) NOT NULL AUTO_INCREMENT,
  `id_don_hang` INT(11) NOT NULL,
  `id_san_pham` INT(11) NOT NULL,
  `so_luong` INT(11) NOT NULL,
  `gia_tai_thoi_diem_dat` DECIMAL(10, 2) NOT NULL,
  PRIMARY KEY (`id_chi_tiet`),
  KEY `id_don_hang` (`id_don_hang`),
  KEY `id_san_pham` (`id_san_pham`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `quang_cao`
--
DROP TABLE IF EXISTS `quang_cao`;
CREATE TABLE IF NOT EXISTS `quang_cao` (
  `id_quang_cao` INT(11) NOT NULL AUTO_INCREMENT,
  `tieu_de` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hinh_anh_banner` VARCHAR(255) NOT NULL,
  `ten_mon` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `so_sao` DECIMAL(3, 1) DEFAULT 0.0,
  `ngay` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tag` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duong_dan_lien_ket` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id_quang_cao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `quang_cao`
--
INSERT INTO `quang_cao` (`tieu_de`, `hinh_anh_banner`, `ten_mon`, `so_sao`, `ngay`, `tag`, `duong_dan_lien_ket`) VALUES
('Bánh Tráng Trứng Cút', 'images/ads/banh_trang.jpg', 'Ăn trong hôm nay,siêu ngon', 4.0, '35 phút', 'NB ưu đãi bánh tráng', 'danhsachsanpham.php?id=1'),
('Bún Bò Huế - Đặc Sản Huế', 'images/ads/bun_bo_hue.jpg', 'Món Nước ngon tuyệt vời', 4.5, '30 phút', 'Ưu đãi chào mừng đến NB', 'danhsachsanpham.php?id=2'),
('Cơm Cháy Chà Bông', 'images/ads/com_chay.jpg', 'Món ăn vặt giòn tan', 4.2, '15 phút', '🔥 Mua 3 Tặng 1 🔥', 'danhsachsanpham.php?id=3'),
('Gỏi Heo Tai Mũi (Gỏi Leo)', 'images/ads/goi_leo.jpg', 'Gỏi trộn chua ngọt', 4.6, '25 phút', '🎉 Món mới khao 15% 🎉', 'danhsachsanpham.php?id=4'),
('Thịt Xiên Que Nướng', 'images/ads/thit_xien_que.jpg', 'Thơm lừng, nóng hổi', 4.3, '20 phút', '🍢 Mua 10 Tặng 2 🍢', 'danhsachsanpham.php?id=5'),
('Nước Cam Vắt Tươi', 'images/ads/nuoc_camjpg.jpg', 'Vitamin C giải nhiệt', 4.7, '10 phút', '🍊 Tươi ngon mỗi ngày 🍊', 'danhsachsanpham.php?id=6'),
('Matcha Latte', 'images/ads/matcha.jpg', 'Trà xanh Nhật Bản', 4.8, '20 phút', '💚 Đồng giá 39k 💚', 'danhsachsanpham.php?id=7'),
('Trà Sữa Trân Châu', 'images/ads/tra_sua.jpg', 'Trà sữa truyền thống', 4.5, '25 phút', '🎁 Tặng topping trân châu 🎁', 'danhsachsanpham.php?id=8'),
('Trà Trái Cây Nhiệt Đới', 'images/ads/tra_trai_cay.jpg', 'Giải khát mùa hè', 4.6, '20 phút', '☀️ Mua 2 Tính Tiền 1 ☀️', 'danhsachsanpham.php?id=9'),
('Trà Sữa Cam Vàng', 'images/ads/tra-sua-cam.jpg', 'Hương vị mới lạ', 4.4, '25 phút', '🧡 Thử ngay vị mới 🧡', 'danhsachsanpham.php?id=10');

--
-- Constraints for dumped tables
--

--
-- Dumping data for table `danh_muc`
--
TRUNCATE TABLE `danh_muc`;
INSERT INTO `danh_muc` (`id_danh_muc`, `ten_danh_muc`, `mo_ta`) VALUES
(1, 'Món ăn', 'Các món ăn chính, no bụng.'),
(2, 'Nước giải khát', 'Các loại nước uống giải khát, tăng lực.'),
(3, 'Đồ ăn vặt', 'Các món ăn nhẹ, ăn chơi.');

--
-- Dumping data for table `san_pham`
--
TRUNCATE TABLE `san_pham`;
INSERT INTO `san_pham` (`id_danh_muc`, `ten_san_pham`, `gia`, `hinh_anh`)
VALUES
(1, 'Mì cay hải sản', 55000.00, 'images/ads/hinhanh1.png'),
(1, 'Mì cay bò ', 55000.00, 'images/ads/hinhanh2.png'),
(1, 'Bún bò Huế', 25000.00, 'images/ads/hinhanh3.png'),
(1, 'Mì Quảng', 30000.00, 'images/ads/hinhanh4.png'),
(1, 'Phở Hà Nội', 30000.00, 'images/ads/hinhanh5.png'),
(1, 'Cơm gà xối mỡ', 45000.00, 'images/ads/hinhanh6.png'),
(2, 'Trà me muối ớt', 30000.00, 'images/ads/hinhanh11.png'),
(2, 'Trà sữa truyền thống', 30000.00, 'images/ads/hinhanh12.png'),
(2, 'Hồng trà bí đao', 25000.00, 'images/ads/hinhanh13.png'),
(2, 'Ca cao muối', 25000.00, 'images/ads/hinhanh14.png'),
(2, 'Matcha latte', 30000.00, 'images/ads/hinhanh15.png'),
(2, 'Trà nhiệt đới', 25000.00, 'images/ads/hinhanh16.png'),
(2, 'Hồng trà trân châu', 25000.00, 'images/ads/hinhanh17.png'),
(3, 'Bánh tráng trộn', 20000.00, 'images/ads/hinhanh7.png'),
(3, 'Bánh tráng da heo tốp mỡ', 20000.00, 'images/ads/hinhanh9.png'),
(3, 'Cơm cháy Chà Bông', 20000.00, 'images/ads/hinhanh8.png'),
(3, 'Đồ chiên', 5000.00 / 1, 'images/ads/hinhanh10.png');

--
-- Dumping data for table `nguoi_dung`
--
INSERT INTO `nguoi_dung` (`ten_dang_nhap`, `mat_khau`, `ho_ten`, `email`, `dia_chi`, `so_dien_thoai`, `vai_tro`, `Khoa`) VALUES
('admin', 'mk123', 'Lệnh Hồ Xung', 'admin@NBCoffee.com', '123 Đường Admin, TP. HCM', '0901234567', 1, 0),
('nguoidung1', 'mknd123', 'Nguyễn Văn Thắng', 'nguyenvanthang@gmail.com', '456 Đường Lập Trình, An Giang', '0912345678', 0, 0),
('nguoidung2', 'mknd123', 'Diệp Hoàng Thành', 'diephoangthanh@gmail.com', '789 Đường Thiết Kế, Jack', '0987654321', 0, 0);

--
-- Constraints for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `san_pham_ibfk_1` FOREIGN KEY (`id_danh_muc`) REFERENCES `danh_muc` (`id_danh_muc`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `don_hang_ibfk_1` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id_nguoi_dung`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_1` FOREIGN KEY (`id_don_hang`) REFERENCES `don_hang` (`id_don_hang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_2` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id_san_pham`) ON DELETE RESTRICT ON UPDATE CASCADE;

SET FOREIGN_KEY_CHECKS=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;