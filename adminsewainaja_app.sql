-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Jul 2025 pada 16.56
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
-- Struktur dari tabel `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `address`) VALUES
(1, 2, 'Jl. Merdeka No. 123, Jakarta Pusat'),
(2, 3, 'Jl. Sudirman Kav. 22, Jakarta Selatan'),
(3, 4, 'Komplek Permata Hijau Blok A1/5, Jakarta Barat'),
(4, 5, 'Apartemen Taman Anggrek Tower B Lantai 10, Jakarta Barat'),
(5, 13, 'melati');

-- --------------------------------------------------------

--
-- Struktur dari tabel `identity_verifications`
--

CREATE TABLE `identity_verifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `identity_type` enum('KTP','SIM') NOT NULL,
  `identity_file` varchar(255) NOT NULL,
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `identity_verifications`
--

INSERT INTO `identity_verifications` (`id`, `user_id`, `nik`, `identity_type`, `identity_file`, `status`, `verified_at`, `created_at`) VALUES
(1, 2, '3275010203040001', 'KTP', 'ktp_budi.jpg', 'verified', '2025-01-15 07:30:00', '2025-07-08 14:15:15'),
(2, 3, '3275020304050002', 'KTP', 'ktp_siti.jpg', 'verified', '2025-02-20 03:15:00', '2025-07-08 14:15:15'),
(3, 4, '3275030405060003', 'KTP', 'ktp_ahmad.jpg', 'rejected', NULL, '2025-07-08 14:15:15'),
(4, 5, '3275040506070004', 'SIM', 'sim_dewi.jpg', 'pending', NULL, '2025-07-08 14:15:15'),
(7, 13, '1234567890123456', 'SIM', 'uploads/identity/identity_13_1752388362.png', 'pending', NULL, '2025-07-13 06:32:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `is_used` tinyint(1) DEFAULT 0,
  `expired_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `otp_codes`
--

INSERT INTO `otp_codes` (`id`, `user_id`, `phone`, `otp_code`, `is_used`, `expired_at`, `created_at`) VALUES
(1, 2, '081298765432', 'A3B9C2', 1, '2025-07-08 03:05:00', '2025-07-08 14:15:15'),
(2, 3, '085678912345', 'D4E8F1', 0, '2025-07-08 04:15:00', '2025-07-08 14:15:15'),
(3, 4, '087812345679', 'G7H2J5', 0, '2025-07-07 02:00:00', '2025-07-08 14:15:15'),
(4, 5, '089612345678', 'K9L3M8', 1, '2025-07-06 07:30:00', '2025-07-08 14:15:15'),
(5, 13, '081234567890', '510567', 1, '2025-07-13 06:28:43', '2025-07-13 06:28:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `transaction_id` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_proof` varchar(255) NOT NULL,
  `status` enum('pending','diterima','ditolak') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `payments`
--

INSERT INTO `payments` (`id`, `transaction_id`, `amount`, `payment_method`, `payment_proof`, `status`, `created_at`) VALUES
(1, 'TRX001', 1250000.00, 'Transfer Bank', 'bukti_bayar_trx1.jpg', 'diterima', '2025-07-08 14:15:15'),
(2, 'TRX002', 700000.00, 'E-Wallet', 'bukti_bayar_trx2.jpg', 'diterima', '2025-07-08 14:15:15'),
(3, 'TRX003', 400000.00, 'Transfer Bank', 'bukti_bayar_trx3.jpg', 'pending', '2025-07-08 14:15:15'),
(4, 'TRX004', 150000.00, 'E-Wallet', 'bukti_bayar_trx4.jpg', 'ditolak', '2025-07-08 14:15:15');

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
(1, 'Canon EOS R5', 'Kamera mirrorless full-frame 45MP', 250000.00, 'hobi', 'canon_eos_r5.jpg', 1, '2025-07-08 14:15:15', '2025-07-10 08:42:05', 'available'),
(2, 'Sony A7 III', 'Kamera mirrorless 24MP dengan stabilisasi', 200000.00, 'hobi', 'sony_a7iii.jpg', 1, '2025-07-08 14:15:15', '2025-07-10 08:42:17', 'available'),
(3, 'DJI Mavic 3', 'Drone 4K dengan zoom 28x', 350000.00, 'hobi', 'dji_mavic3.jpg', 1, '2025-07-08 14:15:15', '2025-07-10 08:42:30', 'available'),
(4, 'Godox SL60W', 'Lampu LED continuous 60W', 75000.00, 'hobi', 'godox_sl60w.jpg', 1, '2025-07-08 14:15:15', '2025-07-10 08:42:45', 'available'),
(5, 'Rode VideoMic Pro+', 'Mic shotgun dengan high-pass filter', 50000.00, 'hobi', 'rode_videomic.jpg', 1, '2025-07-08 14:15:15', '2025-07-10 08:43:00', 'available');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rentals`
--

CREATE TABLE `rentals` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` enum('pending','approved','rejected','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `rentals`
--

INSERT INTO `rentals` (`id`, `product_id`, `user_id`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, 1, 7, '2025-07-11 10:39:00', '2025-07-12 10:39:00', 'pending', '2025-07-10 22:40:03'),
(2, 1, 13, '2025-07-13 00:00:00', '2025-07-15 00:00:00', 'pending', '2025-07-13 09:19:22'),
(3, 1, 13, '2025-07-13 00:00:00', '2025-07-15 00:00:00', 'pending', '2025-07-13 09:25:48'),
(4, 1, 13, '2025-07-13 00:00:00', '2025-07-15 00:00:00', 'pending', '2025-07-13 09:29:36'),
(5, 1, 13, '2025-07-13 00:00:00', '2025-07-15 00:00:00', 'pending', '2025-07-13 09:29:45'),
(6, 1, 13, '2025-07-13 00:00:00', '2025-07-15 00:00:00', 'pending', '2025-07-13 09:36:02'),
(7, 1, 13, '2025-07-13 00:00:00', '2025-07-15 00:00:00', 'pending', '2025-07-13 09:46:58'),
(8, 1, 13, '2025-07-13 00:00:00', '2025-07-15 00:00:00', 'pending', '2025-07-13 09:49:13'),
(9, 1, 13, '2025-07-13 00:00:00', '2025-07-15 00:00:00', 'pending', '2025-07-13 12:01:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','diproses','disewa','selesai','dibatalkan') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `product_id`, `start_date`, `end_date`, `total_price`, `status`, `created_at`) VALUES
('TRX001', 2, 1, '2025-07-10 08:00:00', '2025-07-15 20:00:00', 1250000.00, 'disewa', '2025-07-08 14:15:15'),
('TRX002', 3, 3, '2025-07-12 10:00:00', '2025-07-14 18:00:00', 700000.00, 'selesai', '2025-07-08 14:15:15'),
('TRX003', 5, 2, '2025-07-11 09:00:00', '2025-07-13 17:00:00', 400000.00, 'diproses', '2025-07-08 14:15:15'),
('TRX004', 2, 4, '2025-07-20 14:00:00', '2025-07-22 12:00:00', 150000.00, 'pending', '2025-07-08 14:15:15'),
('TRX005', 3, 1, '2025-07-25 08:00:00', '2025-07-30 20:00:00', 1250000.00, 'dibatalkan', '2025-07-08 14:15:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','penyewa') NOT NULL DEFAULT 'penyewa',
  `phone` varchar(20) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `is_verified`, `created_at`, `updated_at`) VALUES
(1, 'Admin SewainAja', 'admin@sewainaja.com', 'password', 'admin', NULL, 0, '2025-07-08 14:10:20', '2025-07-08 14:10:20'),
(2, 'Admin Utama', 'admin@sewain.com', 'password', 'admin', '081234567890', 1, '2025-07-08 14:15:15', '2025-07-08 14:15:47'),
(3, 'Budi Santoso', 'budi@mail.com', 'password', 'penyewa', '081298765432', 1, '2025-07-08 14:15:15', '2025-07-08 14:16:03'),
(4, 'Siti Rahayu', 'siti@mail.com', 'password', 'penyewa', '085678912345', 1, '2025-07-08 14:15:15', '2025-07-08 14:16:13'),
(5, 'Ahmad Fauzi', 'ahmad@mail.com', 'password\r\n', 'penyewa', '087812345679', 0, '2025-07-08 14:15:15', '2025-07-08 14:16:44'),
(6, 'Dewi Anggraini', 'dewi@mail.com', 'password\r\n', 'penyewa', '089612345678', 1, '2025-07-08 14:15:15', '2025-07-08 14:17:00'),
(7, 'admin', 'admmin@example.com', '$2y$10$4vm8hC2X6BoQv7kXlEXGheW74Q1Mtt1/NPzbzCw96bEv9cXhxqa6.', 'penyewa', NULL, 0, '2025-07-11 03:38:04', '2025-07-11 03:38:04'),
(13, 'jeni', 'jeni@example.com', '$2y$10$Zhb3b1Jz6pc96IPURKPPEuFyLYfJTZ7ygYZgEhzO76BgEM4/NEtze', 'penyewa', '081234567890', 1, '2025-07-13 06:28:25', '2025-07-13 06:28:43');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `identity_verifications`
--
ALTER TABLE `identity_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indeks untuk tabel `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `identity_verifications`
--
ALTER TABLE `identity_verifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `rentals`
--
ALTER TABLE `rentals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `identity_verifications`
--
ALTER TABLE `identity_verifications`
  ADD CONSTRAINT `identity_verifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD CONSTRAINT `otp_codes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `rentals_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
