-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2024 at 02:46 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `nik` varchar(255) DEFAULT NULL,
  `tanggal_lahir` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `timestamp` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama_lengkap`, `email`, `nik`, `tanggal_lahir`, `password`, `role`, `timestamp`, `status`) VALUES
(1, 'John Doe', 'admin1@gmail.com', '3276010202001122', '2 Januari 1999', 'abc1231', 'admin', 1718614162, 1),
(2, 'Jane Doe', 'admin2@gmail.com', '3276010202001123', '2 Januari 1999', 'abc123', 'admin', 1716874139, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `title`, `description`, `status`, `timestamp`) VALUES
(1, 'Iphone 15', 'Kategori Iphone 15', 1, 1718702536),
(2, 'Iphone 14', 'Kategori Iphone 14', 1, 1717942407),
(3, 'Iphone 13', 'Kategori Iphone 13', 1, 1717942407);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `nomor_telpon` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `nama_perusahaan` varchar(255) DEFAULT NULL,
  `status` int(2) NOT NULL DEFAULT 1,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `nama_lengkap`, `email`, `nomor_telpon`, `alamat`, `nama_perusahaan`, `status`, `timestamp`) VALUES
(1, 'Bagas Pratama', 'bagaspratama@gmail.com', '085712341234', 'Jalan Raya, Kebayoran Baru, Jakarta Selatan, Jakarta', '-', 1, 1718557378),
(2, 'Farhan Surya', 'farhansurya12@hotmail.com', '082212341234', 'Jalan Raya, Tebet, Jakarta Selatan, Jakarta', 'PT. Angin Ribut', 1, 1718557378),
(3, 'Risma Wijayanti', 'risma@yahoo.co.id', '089211221122', 'Jalan Raya, Cawang, Jakarta Selatan, Jakarta', '-', 1, 1718557378),
(4, 'Rita Rosalia', 'ritarosalia@gmail.com', '085811221122', 'Jalan Raya, Manggarai, Jakarta Selatan, Jakarta', 'PT. Rosalia', 1, 1718557378);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `no_invoice` varchar(255) DEFAULT NULL,
  `id_customer` int(11) DEFAULT NULL,
  `nama_lengkap` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `nomor_telpon` varchar(20) DEFAULT NULL,
  `subtotal` float DEFAULT NULL,
  `shipping_cost` float DEFAULT NULL,
  `additional_cost` float DEFAULT NULL,
  `discount` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `no_invoice`, `id_customer`, `nama_lengkap`, `email`, `alamat`, `nomor_telpon`, `subtotal`, `shipping_cost`, `additional_cost`, `discount`, `total`, `status`, `notes`, `timestamp`) VALUES
(1, 'INV-18062024-0001', 1, 'Bagas Pratama', 'bagaspratama@gmail.com', 'Jalan Raya, Kebayoran Baru, Jakarta Selatan, Jakarta', '085712341234', 44000000, 100000, 50000, 5000000, 39150000, 'Waiting for Payment', 'Belum dibayar', 1718705158),
(2, 'INV-18062024-0002', 2, 'Farhan Surya', 'farhansurya12@hotmail.com', 'Jalan Raya, Kebayoran Baru, Jakarta Selatan, Jakarta', '085712341235', 50000000, 200000, 300000, 1000000, 49500000, 'Processed', 'acc', 1718710450);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `id_order` int(11) DEFAULT NULL,
  `id_product` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `id_order`, `id_product`, `name`, `quantity`, `price`) VALUES
(1, 1, 1, 'iPhone 15 Pro Max', 1, 23000000),
(2, 1, 2, 'iPhone 15 Pro', 1, 21000000),
(4, 2, 2, 'iPhone 15 Pro', 1, 24000000),
(5, 2, 1, 'iPhone 15 Pro Max', 1, 26000000),
(6, 2, 2, 'iPhone 15 Pro', 1, 24000000);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(255) NOT NULL,
  `thumbnail` longtext DEFAULT 'default.png',
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `buy_price` float NOT NULL,
  `sell_price` float NOT NULL,
  `stock` int(11) DEFAULT NULL,
  `id_category` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `thumbnail`, `name`, `description`, `buy_price`, `sell_price`, `stock`, `id_category`, `status`, `timestamp`) VALUES
(1, 'iphone15promax.jpg', 'iPhone 15 Pro Max', 'iPhone 15 menghadirkan Dynamic Island, kamera Utama 48 MP, dan USB-C — semuanya dalam desain aluminium dan kaca berwarna yang tangguh.', 23000000, 26000000, 10, 1, 1, 1718557896),
(2, 'iphone15pro.jpg', 'iPhone 15 Pro', 'iPhone 15 menghadirkan Dynamic Island, kamera Utama 48 MP, dan USB-C — semuanya dalam desain aluminium dan kaca berwarna yang tangguh.', 21000000, 24000000, 10, 1, 1, 1718557896),
(3, 'iphone15.jpg', 'iPhone 15', 'iPhone 15 menghadirkan Dynamic Island, kamera Utama 48 MP, dan USB-C — semuanya dalam desain aluminium dan kaca berwarna yang tangguh.', 19000000, 21000000, 10, 1, 1, 1718557896),
(4, 'iphone14promax.jpg', 'iPhone 14 Pro Max', 'iPhone 14 Pro Max. Memotret detail menakjubkan dengan kamera Utama 48 MP. Nikmati iPhone dalam cara yang sepenuhnya baru dengan layar yang Selalu Aktif dan Dynamic Island. Deteksi Tabrakan, sebuah fitur keselamatan baru, memanggil bantuan saat Anda tak bisa.', 19000000, 22000000, 10, 2, 1, 1718557896),
(5, 'iphone14pro.jpg', 'iPhone 14 Pro', 'iPhone 14 Pro. Memotret detail menakjubkan dengan kamera Utama 48 MP. Nikmati iPhone dalam cara yang sepenuhnya baru dengan layar yang Selalu Aktif dan Dynamic Island. Deteksi Tabrakan, sebuah fitur keselamatan baru, memanggil bantuan saat Anda tak bisa.', 17500000, 19000000, 10, 2, 1, 1718557896),
(6, 'iphone14.jpg', 'iPhone 14', 'iPhone 14. Memotret detail menakjubkan dengan kamera Utama 48 MP. Nikmati iPhone dalam cara yang sepenuhnya baru dengan layar yang Selalu Aktif dan Dynamic Island. Deteksi Tabrakan, sebuah fitur keselamatan baru, memanggil bantuan saat Anda tak bisa.', 15000000, 16000000, 10, 2, 1, 1718557896),
(7, 'iphone13promax.jpg', 'iPhone 13 Pro Max', 'iPhone 13 Pro Max. Sistem kamera ganda paling canggih yang pernah ada di iPhone. Chip A15 Bionic yang secepat kilat. Lompatan besar dalam kekuatan baterai. Desain kokoh. Dan layar Super Retina XDR yang lebih cerah.', 15000000, 16000000, 10, 3, 1, 1718557896),
(8, 'iphone13pro.jpg', 'iPhone 13 Pro', 'iPhone 13 Pro. Sistem kamera ganda paling canggih yang pernah ada di iPhone. Chip A15 Bionic yang secepat kilat. Lompatan besar dalam kekuatan baterai. Desain kokoh. Dan layar Super Retina XDR yang lebih cerah.', 13000000, 15000000, 10, 3, 1, 1718557896),
(9, 'iphone13.jpg', 'iPhone 13', 'iPhone 13. Sistem kamera ganda paling canggih yang pernah ada di iPhone. Chip A15 Bionic yang secepat kilat. Lompatan besar dalam kekuatan baterai. Desain kokoh. Dan layar Super Retina XDR yang lebih cerah.', 11000000, 13000000, 10, 3, 1, 1718557896);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
