-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 02, 2023 at 02:10 AM
-- Server version: 5.5.20
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `qltp`
--
CREATE DATABASE `qltp` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `qltp`;

-- Table structure for table `nguoi_dung`

DROP TABLE IF EXISTS `nguoi_dung`;
CREATE TABLE IF NOT EXISTS `nguoi_dung` (
  id_nguoi_dung INT(11) NOT NULL AUTO_INCREMENT,
  ten_dang_nhap VARCHAR(100) NOT NULL UNIQUE,
  mat_khau VARCHAR(255) NOT NULL, -- Cần lưu mật khẩu đã HASH
  ho_ten VARCHAR(255) COLLATE utf8_unicode_ci,
  email VARCHAR(255) UNIQUE,
  dia_chi TEXT,
  so_dien_thoai VARCHAR(15),
  vai_tro TINYINT(1) DEFAULT 0, -- 0: Khách hàng, 1: Quản trị
  PRIMARY KEY (id_nguoi_dung)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- Table structure for table `danh_muc`

DROP TABLE IF EXISTS `danh_muc`;
CREATE TABLE IF NOT EXISTS `danh_muc` (
  id_danh_muc INT(11) NOT NULL AUTO_INCREMENT,
  ten_danh_muc VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL,
  mo_ta TEXT,
  PRIMARY KEY (id_danh_muc)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- Table structure for table `san_pham`

DROP TABLE IF EXISTS `san_pham`;
CREATE TABLE IF NOT EXISTS `san_pham` (
  id_san_pham INT(11) NOT NULL AUTO_INCREMENT,
  id_danh_muc INT(11) NOT NULL,
  ten_san_pham VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  mo_ta TEXT,
  gia DECIMAL(10, 2) NOT NULL,
  hinh_anh VARCHAR(255),
  so_luong_ton INT(11) DEFAULT 0,
  trang_thai TINYINT(1) DEFAULT 1, -- 1: Hiển thị, 0: Ẩn
  PRIMARY KEY (id_san_pham),
  FOREIGN KEY (id_danh_muc) REFERENCES danh_muc(id_danh_muc)
      ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- Table structure for table `don_hang`

DROP TABLE IF EXISTS `don_hang`;
CREATE TABLE IF NOT EXISTS `don_hang` (
  id_don_hang INT(11) NOT NULL AUTO_INCREMENT,
  id_nguoi_dung INT(11) NOT NULL,
  ngay_dat_hang DATETIME NOT NULL,
  tong_tien DECIMAL(10, 2) NOT NULL,
  ten_nguoi_nhan VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  dia_chi_giao_hang TEXT NOT NULL,
  trang_thai_don_hang VARCHAR(50) DEFAULT 'Mới',
  PRIMARY KEY (id_don_hang),
  FOREIGN KEY (id_nguoi_dung) REFERENCES nguoi_dung(id_nguoi_dung)
      ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- Table structure for table `chi_tiet_don_hang`

DROP TABLE IF EXISTS `chi_tiet_don_hang`;
CREATE TABLE IF NOT EXISTS `chi_tiet_don_hang` (
    id_chi_tiet INT(11) NOT NULL AUTO_INCREMENT,
    id_don_hang INT(11) NOT NULL,
    id_san_pham INT(11) NOT NULL,
    so_luong INT(11) NOT NULL,
    gia_tai_thoi_diem_dat DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (id_chi_tiet),
    FOREIGN KEY (id_don_hang) REFERENCES don_hang(id_don_hang)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_san_pham) REFERENCES san_pham(id_san_pham)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;
