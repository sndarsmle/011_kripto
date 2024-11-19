-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Nov 2024 pada 01.58
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_akhir_kripto`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `encrypted_file_path` varchar(255) NOT NULL,
  `upload_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `files`
--

INSERT INTO `files` (`id`, `user_id`, `filename`, `encrypted_file_path`, `upload_time`) VALUES
(10, 8, 'sapi limosin_ngudi makmur farm.pdf', 'encrypted_files/encrypted_sapi limosin_ngudi makmur farm.pdf', '2024-11-18 21:05:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `timestamp`) VALUES
(14, 6, 9, 'Jg8UBxwdD0UGDRVZV0dVcBUIQw0UAQdFFB8UE1wSQWoBDxQbAh0HRR0YDg1GQFV4HUUbEwcLQQEVGAlZVlRGZgNFGhMQAxYVFRhafDQ3NgU=', '2024-11-18 21:33:04'),
(15, 9, 6, 'NBESHx9FEwMLR2hzQ1RLIBQLGxNSFAcWHxMfCl0SQm4eAxpVGwtBDh0aD1lVVEBsUxwJBBQIQR0VDwNZS0FfbBUIQxEUA0EMGRgJH0IePgp+bxATAANBDh0aD0MRSlV1HUUSGwARGQsANBYVS1tdIAEDEwcIEkEOFRwXV0RbWA15FRkGGgtBX1MTAwxfEktqHgMaeH9obGh+Zmh0PD8+DQ==', '2024-11-18 21:35:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `steganography`
--

CREATE TABLE `steganography` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cover_image_path` varchar(255) NOT NULL,
  `secret_message` text NOT NULL,
  `steganography_image_path` varchar(255) NOT NULL,
  `upload_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `steganography`
--

INSERT INTO `steganography` (`id`, `user_id`, `cover_image_path`, `secret_message`, `steganography_image_path`, `upload_time`) VALUES
(10, 8, 'uploads/cover_images/673bb10b19d4a_YSZeoeVmor3v1WAyYMcB.jpeg', '/P7obcIzUVShIhNcYuaqJKtsFzXYWBn6a8tIbAOG/1wDPnQX/NDlB1QaO7MTgIVi0CRWqu7gQFstvETzRWbZPoPVXljxDEW1I3AVpe+QNV2kfnYydfwlG3IeSCumv9oSgvlSuyr9GS5PkqGUWNMBJ/GzPS5ISs7i7GuDTrQRyhE=', 'uploads/stegano_images/673bb10b200f4_stegano.png', '2024-11-18 21:26:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`) VALUES
(4, 'pembeli 1', '$2y$10$spscriEFwzgPN5uyoxhtpe.2w4uWA6I4TRJJBMq861LBzIOD/OWHW'),
(5, 'pedagang 1', '$2y$10$vsPsRg1Zk/rvomR5yItl8ewOtRRJ2RpX5bPWVWoH03FdMdxPe257e'),
(6, 'pembeli 2', '$2y$10$IbFiM/tBsNs7BomHIRFehOghi4r5FpNxmSDIfFlbTsKRZ1rfUoSs6'),
(7, 'pembeli 3', '$2y$10$nvZTuM1dyipYeCVvzjtiWegRZn1Qy2VZj4l3iQY.htpYj7tu0RGlG'),
(8, 'penjual 2', '$2y$10$Ca9mQIHwXyuKf0hGY9n5NOWuRQmxutziJXFzoSbmtj4YrO5GrM5Nu'),
(9, 'penjual 3', '$2y$10$3489RS.zRknSrzJb.leE3OiUziBQMPrAqdX6Dwy2rZ2XItnd/u5n.');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indeks untuk tabel `steganography`
--
ALTER TABLE `steganography`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `steganography`
--
ALTER TABLE `steganography`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `steganography`
--
ALTER TABLE `steganography`
  ADD CONSTRAINT `steganography_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
