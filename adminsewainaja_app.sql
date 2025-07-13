-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Jul 2025 pada 05.23
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `adminsewainaja_app`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price_per_day` decimal(10,2) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('available','rented') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price_per_day`, `category`, `image`, `owner_id`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Canon EOS R5', 'Kamera mirrorless full-frame 45MP', 250000.00, 'kamera', 'canon_eos_r5.jpg', 1, '2025-07-08 14:15:15', '2025-07-08 14:15:15', 'available'),
(2, 'Sony A7 III', 'Kamera mirrorless 24MP dengan stabilisasi', 200000.00, 'kamera', 'sony_a7iii.jpg', 1, '2025-07-08 14:15:15', '2025-07-08 14:15:15', 'available'),
(3, 'DJI Mavic 3', 'Drone 4K dengan zoom 28x', 350000.00, 'drone', 'dji_mavic3.jpg', 1, '2025-07-08 14:15:15', '2025-07-08 14:15:15', 'available'),
(4, 'Godox SL60W', 'Lampu LED continuous 60W', 75000.00, 'lighting', 'godox_sl60w.jpg', 1, '2025-07-08 14:15:15', '2025-07-08 14:15:15', 'available'),
(5, 'Rode VideoMic Pro+', 'Mic shotgun dengan high-pass filter', 50000.00, 'audio', 'rode_videomic.jpg', 1, '2025-07-08 14:15:15', '2025-07-08 14:15:15', 'available');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
