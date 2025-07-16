-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Jul 2025 pada 10.53
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
(2, 3, 'Jl. Sudirman Kav. 22, Jakarta Selatan');

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
(2, 3, '3275020304050002', 'KTP', 'ktp_siti.jpg', 'verified', '2025-02-20 03:15:00', '2025-07-08 14:15:15');

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
(2, 3, '085678912345', 'D4E8F1', 0, '2025-07-08 04:15:00', '2025-07-08 14:15:15');

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
(6, 'tenda lipat besi hitam', 'atap warna biru  ukuran 5x3 meter', 100000.00, 'Alat Pesta & Acara', 'uploads/images/687748cc1ac0d_tenda_lipat_hitam.jpg\r\n', 1, '2025-07-14 16:03:59', '2025-07-16 06:45:39', 'available'),
(7, 'Set Kursi Plastik 100 Buah', 'Kursi plastik lipat warna putih dengan kualitas tinggi, termasuk dalam 1 set 100 buah.', 200000.00, 'Alat Pesta & Acara', 'uploads/images/6875624d21457_kursi_plastik_set.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 20:02:21', 'available'),
(8, 'Sound System Portable 2000W', 'Sound system lengkap dengan 2 speaker aktif, mixer, dan mic wireless.', 500000.00, 'Alat Pesta & Acara', 'sound_system_party.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(9, 'Stroller Premium', 'Stroller bayi dengan bahan waterproof dan sistem guncangan yang halus.', 75000.00, 'Perlengkapan Bayi & Anak', 'stroller_premium.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(10, 'Baby Bouncer', 'Tempat duduk bayi dengan ayunan otomatis dan musik.', 50000.00, 'Perlengkapan Bayi & Anak', 'baby_bouncer.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(11, 'Mainan Edukasi Anak', 'Set mainan edukasi untuk anak usia 1-3 tahun berbahan aman.', 30000.00, 'Perlengkapan Bayi & Anak', 'mainan_edukasi.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(12, 'Mesin Bor Listrik', 'Mesin bor 650W dengan berbagai mata bor termasuk.', 80000.00, 'Alat Konstruksi & Perkakas', 'mesin_bor.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(13, 'Gerinda Tangan', 'Gerinda tangan 4-inch 570W dengan pengaturan kecepatan.', 70000.00, 'Alat Konstruksi & Perkakas', 'gerinda_tangan.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(14, 'Kompresor Angin Portable', 'Kompresor angin 2HP dengan kapasitas tanki 24 liter.', 120000.00, 'Alat Konstruksi & Perkakas', 'kompresor_angin.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(15, 'Tenda Camping 4 Orang', 'Tenda waterproof dengan 2 ruangan, cocok untuk 4 orang.', 100000.00, 'Perlengkapan Outdoor & Camping', 'tenda_camping.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(16, 'Kompor Portable', 'Kompor gas portable dengan sistem piezo ignition.', 30000.00, 'Perlengkapan Outdoor & Camping', 'kompor_portable.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(17, 'Sleeping Bag -5°C', 'Sleeping bag tahan hingga suhu -5°C dengan bahan waterproof.', 40000.00, 'Perlengkapan Outdoor & Camping', 'sleeping_bag.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(18, 'Drone DJI Mavic Mini', 'Drone dengan kamera 4K, berat <250g, jangkauan 4km.', 250000.00, 'Elektronik Khusus', 'drone_dji.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(19, 'VR Headset Oculus Quest 2', 'Virtual reality headset dengan resolusi 1832x1920 per mata.', 150000.00, 'Elektronik Khusus', 'vr_headset.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(20, 'Projector HD 1080p', 'Projector portable dengan resolusi full HD 1080p.', 120000.00, 'Elektronik Khusus', 'projector_hd.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(21, 'Sepeda Gunung', 'Sepeda gunung 21-speed dengan suspensi depan.', 100000.00, 'Peralatan Olahraga', 'sepeda_gunung.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(22, 'Treadmill Elektrik', 'Treadmill dengan motor 2.5HP dan kecepatan hingga 14km/jam.', 150000.00, 'Peralatan Olahraga', 'treadmill.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(23, 'Set Alat Fitness Rumahan', 'Set lengkap alat fitness termasuk dumbell, matras, dan resistance band.', 70000.00, 'Peralatan Olahraga', 'alat_fitness.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(24, 'Meja Lipat Portable', 'Meja lipat ukuran 120x60cm, mudah dibawa dan disimpan.', 30000.00, 'Perabot Rumah Sementara', 'meja_lipat.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(25, 'Sofa Bed', 'Sofa yang bisa dibuka menjadi tempat tidur single.', 80000.00, 'Perabot Rumah Sementara', 'sofa_bed.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(26, 'Lemari Portable', 'Lemari baju portable dengan rangka besi dan penutup kain.', 40000.00, 'Perabot Rumah Sementara', 'lemari_portable.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(27, 'Vacuum Cleaner Robot', 'Robot pembersih lantai dengan sistem pemetaan otomatis.', 90000.00, 'Alat Kebersihan & Perawatan', 'vacuum_robot.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(28, 'Mesin Steam Cuci Mobil', 'Alat steam tekanan tinggi untuk mencuci kendaraan.', 100000.00, 'Alat Kebersihan & Perawatan', 'steam_mobil.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available'),
(29, 'High Pressure Washer', 'Pembersih tekanan tinggi 1300W dengan berbagai nozzle.', 80000.00, 'Alat Kebersihan & Perawatan', 'pressure_washer.jpg', 1, '2025-07-14 16:03:59', '2025-07-14 16:03:59', 'available');

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
(1, 'Admin ', 'admin@example.com', '$2y$10$2B5hZgJkkYsAKIOk57PbTeafStOZkqwiyfObc.kqiNMFLXyJ3//8.', 'admin', NULL, 1, '2025-07-08 14:10:20', '2025-07-14 17:08:47'),
(3, 'penyewa', 'penyewa@example.com', 'password', 'penyewa', '081298765432', 1, '2025-07-08 14:15:15', '2025-07-14 15:50:16');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

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
-- Ketidakleluasaan untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
