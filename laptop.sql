-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 05, 2024 lúc 03:51 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `laptop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `MaTaiKhoan` int(11) NOT NULL,
  `TenTaiKhoan` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `MatKhau` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`MaTaiKhoan`, `TenTaiKhoan`, `MatKhau`) VALUES
(1, 'ntrung', '0605');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietgiohang`
--

CREATE TABLE `chitietgiohang` (
  `MaGioHang` int(11) NOT NULL,
  `MaSanPham` int(11) NOT NULL,
  `SoLuong` int(11) NOT NULL,
  `ThanhTien` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietgiohang`
--

INSERT INTO `chitietgiohang` (`MaGioHang`, `MaSanPham`, `SoLuong`, `ThanhTien`) VALUES
(1, 1, 10, 377900000),
(1, 7, 11, 351890000),
(3, 7, 1, 31990000),
(3, 13, 1, 89990000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitiethoadon`
--

CREATE TABLE `chitiethoadon` (
  `MaHoaDon` int(11) NOT NULL,
  `MaSanPham` int(11) NOT NULL,
  `SoLuong` int(11) NOT NULL,
  `ThanhTien` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitiethoadon`
--

INSERT INTO `chitiethoadon` (`MaHoaDon`, `MaSanPham`, `SoLuong`, `ThanhTien`) VALUES
(3, 7, 2, 20000000),
(5, 1, 1, 20000000),
(5, 6, 12, 12000000),
(7, 1, 1, 37790000),
(7, 2, 1, 44990000),
(7, 3, 1, 23990000),
(7, 4, 1, 64990000),
(7, 7, 1, 31990000),
(7, 11, 2, 97180000),
(7, 12, 1, 89900000),
(7, 13, 1, 89990000),
(8, 3, 1, 23990000),
(9, 2, 1, 44990000),
(9, 3, 1, 23990000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giohang`
--

CREATE TABLE `giohang` (
  `MaGioHang` int(11) NOT NULL,
  `MaTaiKhoan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `giohang`
--

INSERT INTO `giohang` (`MaGioHang`, `MaTaiKhoan`) VALUES
(1, 1),
(2, 3),
(3, 4),
(4, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hang`
--

CREATE TABLE `hang` (
  `MaHang` int(11) NOT NULL,
  `TenHang` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Logo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hang`
--

INSERT INTO `hang` (`MaHang`, `TenHang`, `Logo`) VALUES
(1, 'MSI', 'logomsi.png'),
(2, 'LENOVO', 'logolenovo.png\r\n'),
(3, 'ASUS', 'logoasus.png'),
(4, 'DELL', 'logodell.png'),
(5, 'HP', 'logohp.png'),
(6, 'ACER', 'logoacer.png'),
(22, 'Apple', 'logoapple.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoadon`
--

CREATE TABLE `hoadon` (
  `MaHoaDon` int(11) NOT NULL,
  `MaTaiKhoan` int(11) NOT NULL,
  `NgayMua` date NOT NULL,
  `DiaChi` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `SDT` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `HinhThucThanhToan` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `TongTien` double NOT NULL,
  `TrangThai` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Chưa xác nhận'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hoadon`
--

INSERT INTO `hoadon` (`MaHoaDon`, `MaTaiKhoan`, `NgayMua`, `DiaChi`, `SDT`, `HinhThucThanhToan`, `TongTien`, `TrangThai`) VALUES
(3, 2, '2024-06-01', 'Bình Thuận', '', 'Thẻ ATM', 10000000, 'Đã xác nhận'),
(5, 1, '2024-06-01', 'BG', '', 'TM', 10000000, 'Đã xác nhận'),
(6, 2, '2024-05-09', 'Bắc Bình', '0957348576', 'Thẻ ATM', 300000000, 'Đã xác nhận'),
(7, 3, '2024-06-04', 'Bắc Bình - Bình Thuận', '0986565379', 'Chuyển khoản ngân hàng', 480920000, 'Đã xác nhận'),
(8, 3, '2024-06-04', 'Bắc Bình - Bình Thuận', '0856534756', 'Thanh toán khi nhận hàng', 24090000, 'Đã xác nhận'),
(9, 5, '2024-06-05', 'Nha Trang', '0983746373', 'Chuyển khoản ngân hàng', 69080000, 'Đã xác nhận');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
--

CREATE TABLE `sanpham` (
  `MaSanPham` int(11) NOT NULL,
  `TenSanPham` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `MoTa` varchar(9999) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `HinhAnh` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `GiaBan` double NOT NULL,
  `CPU` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Ram` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `OCung` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ManHinh` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `VGA` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `HeDieuHanh` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `TrongLuong` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Pin` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `SoLuong` int(11) NOT NULL,
  `MaHang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sanpham`
--

INSERT INTO `sanpham` (`MaSanPham`, `TenSanPham`, `MoTa`, `HinhAnh`, `GiaBan`, `CPU`, `Ram`, `OCung`, `ManHinh`, `VGA`, `HeDieuHanh`, `TrongLuong`, `Pin`, `SoLuong`, `MaHang`) VALUES
(1, 'Laptop gaming Dell G15 5530 i7H165W11GR4060', 'Laptop gaming Dell G15 5530 i7H165W11GR4060 là sản phẩm nằm trong phân khúc trên laptop gaming cao cấp trên 30 triệu và là thế hệ chơi game tiếp theo của Dell. Vốn nổi tiếng với những chiếc laptop văn phòng thì nay Dell mang đến cho game thủ một lựa chọn mới đầy tính năng, cấu hình mạnh mẽ và đương đầu với vô số tựa game dễ dàng.', 'LaptopgamingDellG15 5530i7H165W11GR4060.webp', 37790000, 'Intel Core i7-13650HX 24 MB cache, 14 core, up to 4.90 GHz', '16GB (2x8GB) DDR5 4800MHz (2x SO-DIMM socket, up to 32GB SDRAM)', '512GB SSD M.2 PCIe NVMe', '15.6 FHD (1920x1080) 165Hz, 3ms, sRGB-100%, ComfortViewPlus, NVIDIA G-SYNC+DDS Display', 'NVIDIA® GeForce RTX™ 4060 8GB GDDR6', 'Windows 11 Home + Office Home & Student', '2.81 kg', '6 Cell 86WHrs', 29, 4),
(2, 'Laptop gaming Dell Alienware M15 R6 P109F001CBL', 'Một trong những dòng laptop chơi game cao cấp được yêu thích nhất hiện nay với vẻ ngoài đặc biệt cùng cấu hình mạnh mẽ, đây cũng là đứa con của thương hiệu Dell nổi tiếng với dòng laptop văn phòng, đó chính là Alienware. Hôm nay GEARVN sẽ đem đến cho bạn chiếc laptop Dell Alienware M15 R6 P109F001CBL, hãy cùng xem “người ngoài hành tinh” sẽ được trang bị những gì cho game thủ chúng ta nhé!', 'LaptopgamingDellAlienwareM15R6P109F001CBL.webp', 44990000, 'Intel Core i7-11800H 2.3GHz up to 4.6GHz 24MB', '32GB (16x2) DDR4 3200MHz (2x SO-DIMM socket, up to 64GB SDRAM)', '1TB SSD M.2 PCIe', '15.6 inch QHD (2560 x 1440) 240Hz, 2ms, with ComfortView plus, NVIDIA G-SYNC and Advanced Optimus, WVA Display', 'NVIDIA GeForce RTX 3060 6GB GDDR6', 'Windows 11 Home + Office Home & Student', '2.69 kg', '6 Cell 86WHrs', 28, 4),
(3, 'Laptop gaming HP VICTUS 16-s0078AX 8C5N7PA', 'Ngon bổ rẻ.', 'LaptopgamingHPVICTUS16-s0078AX8C5N7PA.webp', 23990000, 'AMD Ryzen™ 5 7640HS (up to 5.0 GHz max boost clock, 16 MB L3 cache, 6 cores, 12 threads)', '16GB (2x8GB) DDR5 4800MHz (2x SO-DIMM socket, up to 32GB SDRAM)', '512 GB PCIe® Gen4 NVMe™ TLC M.2 SSD (1 slot)', '16.1 FHD (1920 x 1080), 144 Hz, IPS, micro-edge, anti-glare, 250 nits, 45% NTSC', 'NVIDIA® GeForce RTX™ 4060 8GB GDDR6', 'Windows 11 Home', '2.31 kg', '4 Cell 70WHr', 7, 5),
(4, 'Laptop gaming Lenovo Legion 7 16IRX9 83FD006JVN', 'Ngon bổ rẻ.', 'LaptopgamingLenovoLegion716IRX9 83FD006JVN.webp', 64990000, 'Intel® Core™ i9-14900HX, 24C (8P + 16E) / 32T, P-core 2.2 / 5.8GHz, E-core 1.6 / 4.1GHz, 36MB', '32GB (2x16GB) SO-DIMM DDR5 5600MHz (2 slots, nâng cấp tối đa 32GB)', '1TB SSD M.2 2280 PCIe® 4.0x4 NVMe® (2 slots M.2 2280 PCIe® 4.0 x4)', '16 3.2K (3200x2000) IPS 430nits Anti-glare, 100% DCI-P3, 165Hz, Dolby® Vision®, G-SYNC®, Low Blue Light', 'NVIDIA® GeForce RTX™ 4070 8GB GDDR6, Boost Clock 2175MHz, TGP 115W', 'Windows® 11 Home Single Language, English', '2.62 kg', 'Integrated 99.9Wh', 4, 2),
(5, 'Laptop gaming Dell G15 5530 i7H165W11GR4050', 'Laptop gaming Dell G15 5530 i7H165W11GR4050 là sản phẩm nằm trong phân khúc trên laptop gaming cao cấp 30 triệu và là thế hệ chơi game tiếp theo của Dell. Vốn nổi tiếng với những chiếc laptop văn phòng thì nay Dell mang đến cho game thủ một lựa chọn mới đầy tính năng, cấu hình mạnh mẽ và đương đầu với vô số tựa game dễ dàng.', 'LaptopgamingDellG15 5530i7H165W11GR4060.webp', 37990000, 'Intel Core i7-13650HX 24 MB cache, 14 core, up to 4.90 GHz', '16GB (2x8GB) DDR5 4800MHz (2x SO-DIMM socket, up to 32GB SDRAM)', '512GB SSD M.2 PCIe PCIE G4X4', '15.6 FHD (1920x1080) 165Hz, 3ms, sRGB-100%, ComfortViewPlus, NVIDIA G-SYNC+DDS Display', 'NVIDIA® GeForce RTX™ 4050 6GB GDDR6', 'Windows 11 Home + Office Home & Student', '2.81 kg', '3 Cell 56WHrs', 20, 4),
(6, 'Laptop gaming HP OMEN 16 wf0129TX 8W943PA', 'HP, tập đoàn công nghệ nổi tiếng với những thiết bị văn phòng nay đã chính thức bước vào con đường phục vụ game thủ với những sản phẩm laptop gaming như Victus, OMEN. Sự thành công của những phiên bản tiền nhiệm đã thúc đẩy cho HP tiếp tục cho ra mắt sản phẩm hiện đại hơn và mạnh mẽ, và đây sẽ là chính bài đánh giá về chiếc HP OMEN 16 wf0129TX 8W943PA.', 'LaptopgamingHPOMEN16wf0129TX 8W943PA.webp', 64990000, 'Intel® Core™ i9-13900HX 2.2GHz up to 5.4GHz 36MB', '32GB (16x2) DDR5 5600MHz (2x SO-DIMM socket, up to 32GB SDRAM)', '1 TB PCIe® Gen4 NVMe™ TLC M.2 SSD', '16.1', 'NVIDIA® GeForce RTX™ 4070 8GB GDDR6', 'Windows 11 Home', '2.35 kg', '6 Cell 83WHr', 5, 5),
(7, 'Laptop ASUS Zenbook 14 OLED UX3405MA PP152W', 'ASUS Zenbook 14 OLED UX3405MA được xem là một laptop văn phòng có thể cân mọi task khi có sự hỗ trợ của Chip Intel Core I5. Bạn có thể thoải mái chạy các task đồ hoạ cơ bản vì CPU này đã hỗ trợ bạn đẩy nhanh quá trình render hình ảnh và edit video. Không nhưng thế việc mở nhiều tab để làm việc không phải là nỗi lo của laptop vì ASUS Zenbook 14 OLED UX3405MA có Ram lên tới 32GB. Làm việc linh hoạt hơn chỉ là chuyện trong tầm tay của bạn. ', 'zenbook_14_oled_ux3405ma.png', 31990000, 'Intel® Core™ Ultra 7 Processor 155H 1.4 GHz (24MB Cache, up to 4.8 GHz, 16 cores, 22 Threads); Intel® AI Boost NPU', '32GB LPDDR5X on board Dual-channel (Không nâng cấp)', '1TB M.2 NVMe™ PCIe® 4.0 SSD', '14.0-inch 3K (2880 x 1800) OLED 16:10, LED Backlit, 0.2ms, 120Hz, 400nits, 600nits HDR peak brightness, 100% DCI-P3, Glossy display, Tỷ lệ màn hình trên thân máy 87%', 'Intel® Arc™ Graphics', 'Windows 11 Home', '1.20 kg', '75WHrs, 2S2P, 4-cell Li-ion', 11, 3),
(11, 'Macbook Pro 14 M2 Pro', 'Macbook Pro 14 M2 Pro 10CPU 16GPU 16GB 512GB Silver - MPHH3SA/A mang đến một siêu phẩm laptop học tập và làm việc thế hệ mới. Thừa hưởng vẻ đẹp sang trọng từ thương hiệu Apple cùng hiệu năng vượt trội được xử lý từ con chip M2 mạnh mẽ. Hứa hẹn đây sẽ là một trong những chiếc laptop làm mưa làm gió trên thị trường. Cùng GEARVN tìm hiểu xem Macbook Pro 14 M2 Pro này có gì đáng mong đợi nhé.', 'MacbookPro14M2Pro10CPU16GPU.webp', 48590000, 'M2 PRO 10CPU 16GPU', '16GB', 'SSD 512GB', '14-inch Liquid Retina XDR display', '16GPU', 'MacOS', '1.60 kg', 'Longer battery life, up to 18 hours', 10, 22),
(12, 'Laptop gaming MSI Vector GP78HX 13VI 476VN', 'MSI Vector GP78HX 13VI 476VN là laptop chơi game cao cấp được trang bị bộ vi xử lý Intel Core i9-13980HX và card đồ họa NVIDIA GeForce RTX 4090. Máy được trang bị RAM DDR5 32GB và ổ SSD NVMe 2TB. Màn hình trang bị tấm nền QHD+ IPS 17,3 inch với độ phân giải 240Hz.', 'LaptopgamingMSIVectorGP78HX13VI476VN.webp', 89900000, 'Intel Core i9-13980HX (Up to 5.6GHz) 24 Cores 32 Threads', '32GB (16x2) DDR5 5600MHz (2x SO-DIMM socket, up to 64GB SDRAM)', '2TB NVMe PCIe Gen4x4 SSD (2 slot)', '17” QHD+ (2560x1600), 240Hz, IPS, 100% DCI-P3', 'NVIDIA® GeForce RTX™ 4090 16GB GDDR6 + MUX switch', 'Windows 11 Home', '3 kg', '4 cell, 90Whr', 13, 1),
(13, 'Laptop ASUS Zenbook 17 Fold OLED UX9702AA MD014W', 'ASUS Zenbook 17 Fold OLED UX9702AA MD014W như một làn gió tươi mới đến từ thương hiệu Asus nổi tiếng. Bắt mắt từ thiết kế ngoại hình độc lạ với khả năng gập đôi màn hình và bàn phím rời. Cấu hình mạnh mẽ đáp ứng nhiều nhu cầu học tập, làm việc và giải trí hằng ngày từ con chip Intel thế hệ 12. Đây sẽ là người bạn đồng hành tuyệt vời trong phân khúc laptop cao cấp.', 'enbook-17-fold-oled-ux9702aa-md014w-2_7.webp', 89990000, 'CPU Intel® Core™ i7-1250U 1.1 GHz (12M Cache, lên đến 4.7 GHz, 2P+8E nhân)', '16GB LPDDR5 4800MHz Onboard', '1TB M.2 NVMe™ PCIe® 4.0 Performance SSD (1 slot)', '17.3', 'Intel® Iris Xe Graphics', 'Windows 11 Home', '1.50 kg', '	75WHrs, 4S1P, 4-cell Li-ion', 14, 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `MaTaiKhoan` int(11) NOT NULL,
  `TenKhachHang` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `TenTaiKhoan` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `MatKhau` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Email` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`MaTaiKhoan`, `TenKhachHang`, `TenTaiKhoan`, `MatKhau`, `Email`) VALUES
(1, 'Lê Minh Tuấn', 'tuanmk', '123456', 'luuducvinh1392@gmail.com'),
(2, 'Nguyễn Thành Trung', 'ngtrung203', '0605', 'trungnguyen6503@gmail.com'),
(3, 'Nguyen Thanh Trung', 'ntrung060503', '0605', 'nguyntrung203@gmail.com'),
(4, 'Nguyen Thanh Trung', 'trung', '12345', 'nguyntrung203@gmail.com'),
(5, 'Minh Mập', 'minhmap', '1234', 'qminh3011@gmail.com');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`MaTaiKhoan`);

--
-- Chỉ mục cho bảng `chitietgiohang`
--
ALTER TABLE `chitietgiohang`
  ADD PRIMARY KEY (`MaGioHang`,`MaSanPham`),
  ADD KEY `MaGioHang` (`MaGioHang`,`MaSanPham`),
  ADD KEY `fk_chitietgiohang_sanpham` (`MaSanPham`);

--
-- Chỉ mục cho bảng `chitiethoadon`
--
ALTER TABLE `chitiethoadon`
  ADD PRIMARY KEY (`MaHoaDon`,`MaSanPham`) USING BTREE,
  ADD KEY `MaHoaDon` (`MaHoaDon`,`MaSanPham`),
  ADD KEY `fk_chitiethoadon_sanpham` (`MaSanPham`);

--
-- Chỉ mục cho bảng `giohang`
--
ALTER TABLE `giohang`
  ADD PRIMARY KEY (`MaGioHang`),
  ADD KEY `MaTaiKhoan` (`MaTaiKhoan`);

--
-- Chỉ mục cho bảng `hang`
--
ALTER TABLE `hang`
  ADD PRIMARY KEY (`MaHang`);

--
-- Chỉ mục cho bảng `hoadon`
--
ALTER TABLE `hoadon`
  ADD PRIMARY KEY (`MaHoaDon`),
  ADD KEY `MaTaiKhoan` (`MaTaiKhoan`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`MaSanPham`),
  ADD KEY `MaHang` (`MaHang`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`MaTaiKhoan`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin`
--
ALTER TABLE `admin`
  MODIFY `MaTaiKhoan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `giohang`
--
ALTER TABLE `giohang`
  MODIFY `MaGioHang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `hang`
--
ALTER TABLE `hang`
  MODIFY `MaHang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `hoadon`
--
ALTER TABLE `hoadon`
  MODIFY `MaHoaDon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `MaSanPham` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `MaTaiKhoan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chitietgiohang`
--
ALTER TABLE `chitietgiohang`
  ADD CONSTRAINT `fk_chitietgiohang_giohang` FOREIGN KEY (`MaGioHang`) REFERENCES `giohang` (`MaGioHang`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_chitietgiohang_sanpham` FOREIGN KEY (`MaSanPham`) REFERENCES `sanpham` (`MaSanPham`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `chitiethoadon`
--
ALTER TABLE `chitiethoadon`
  ADD CONSTRAINT `fk_chitiethoadon_hoadon` FOREIGN KEY (`MaHoaDon`) REFERENCES `hoadon` (`MaHoaDon`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_chitiethoadon_sanpham` FOREIGN KEY (`MaSanPham`) REFERENCES `sanpham` (`MaSanPham`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `giohang`
--
ALTER TABLE `giohang`
  ADD CONSTRAINT `fk_giohang_user` FOREIGN KEY (`MaTaiKhoan`) REFERENCES `user` (`MaTaiKhoan`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `hoadon`
--
ALTER TABLE `hoadon`
  ADD CONSTRAINT `hoadon_ibfk_1` FOREIGN KEY (`MaTaiKhoan`) REFERENCES `user` (`MaTaiKhoan`);

--
-- Các ràng buộc cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `fk_sanpham_hang` FOREIGN KEY (`MaHang`) REFERENCES `hang` (`MaHang`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
