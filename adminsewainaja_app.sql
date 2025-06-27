-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Jun 2025 pada 15.50
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
-- Struktur dari tabel `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(255) NOT NULL,
  `price_per_day` decimal(10,2) NOT NULL,
  `status` enum('tersedia','disewa') NOT NULL DEFAULT 'tersedia',
  `photo` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `items`
--

INSERT INTO `items` (`id`, `admin_id`, `name`, `description`, `category`, `price_per_day`, `status`, `photo`, `video`, `created_at`, `updated_at`) VALUES
(1, 1, 'blender', 'oren', 'elektronik', 130000.00, 'tersedia', 'uploads/685563a38c724_blender.png', '', '2025-06-13 10:01:15', '2025-06-20 08:35:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `item_availabilities`
--

CREATE TABLE `item_availabilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `status` enum('tersedia','disewa') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `item_availabilities`
--

INSERT INTO `item_availabilities` (`id`, `item_id`, `date`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-06-13', 'tersedia', '2025-06-13 10:01:15', '2025-06-13 10:01:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('jatuh tempo','konfirmasi pengiriman','pengingat pengembalian') NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rental_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` datetime NOT NULL,
  `status` enum('lunas','pending','gagal') NOT NULL DEFAULT 'pending',
  `notified_due_date` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rentals`
--

CREATE TABLE `rentals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `penyewa_id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('menunggu pembayaran','disewa','selesai','terlambat') NOT NULL DEFAULT 'menunggu pembayaran',
  `condition_before` varchar(255) DEFAULT NULL,
  `condition_after` varchar(255) DEFAULT NULL,
  `damage_report` text DEFAULT NULL,
  `fine_amount` decimal(10,2) DEFAULT NULL,
  `confirmed_by_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rental_history`
--

CREATE TABLE `rental_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `penyewa_id` bigint(20) UNSIGNED NOT NULL,
  `rental_id` bigint(20) UNSIGNED NOT NULL,
  `summary` text NOT NULL,
  `total_paid` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','penyewa') NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `identity_type` varchar(255) DEFAULT NULL COMMENT 'KTP/SIM',
  `identity_file` varchar(255) DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `address`, `identity_type`, `identity_file`, `verified`, `created_at`, `updated_at`) VALUES
(1, 'Admin Utama', 'admin@example.com', '$2y$12$EZnb44KnxKLFKo.5UUuCc.fPYgC2XkqQuccOPeLoIdaFOl06nQbOK', 'admin', '08123456789', 'Jl. Admin No.1', 'KTP', 'ktp_admin.jpg', 1, '2025-06-13 10:00:42', '2025-06-13 10:00:42');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `items_admin_id_foreign` (`admin_id`);

--
-- Indeks untuk tabel `item_availabilities`
--
ALTER TABLE `item_availabilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_availabilities_item_id_foreign` (`item_id`);

--
-- Indeks untuk tabel `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`receiver_id`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_rental_id_foreign` (`rental_id`);

--
-- Indeks untuk tabel `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rentals_item_id_foreign` (`item_id`),
  ADD KEY `rentals_penyewa_id_foreign` (`penyewa_id`),
  ADD KEY `rentals_admin_id_foreign` (`admin_id`);

--
-- Indeks untuk tabel `rental_history`
--
ALTER TABLE `rental_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rental_history_penyewa_id_foreign` (`penyewa_id`),
  ADD KEY `rental_history_rental_id_foreign` (`rental_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `item_availabilities`
--
ALTER TABLE `item_availabilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rentals`
--
ALTER TABLE `rentals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rental_history`
--
ALTER TABLE `rental_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `item_availabilities`
--
ALTER TABLE `item_availabilities`
  ADD CONSTRAINT `item_availabilities_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_rental_id_foreign` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rentals_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rentals_penyewa_id_foreign` FOREIGN KEY (`penyewa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rental_history`
--
ALTER TABLE `rental_history`
  ADD CONSTRAINT `rental_history_penyewa_id_foreign` FOREIGN KEY (`penyewa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rental_history_rental_id_foreign` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
