-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 02, 2026 at 09:25 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gestion-pedagogique`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('room','equipment') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('available','in_use','in_repair') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `name`, `description`, `image`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Amphithéâtre Pasteur', 'Grand amphi en gradins d’une capacité de 250 places assises.', 'assets/o6BOCfzo5Sf03aJOko42ZLAjmjL1wAaIuatr5aGk.jpg', 'room', 'available', '2026-01-16 11:08:09', '2026-02-02 14:46:48'),
(2, 'Salle de Conférence', 'Espace de 100 m² avec une configuration de 60 places en style théâtre.', 'assets/kOwacwJGcP4g1egOLFWdzkeI0aeZJKYn12IswWCv.jpg', 'room', 'available', '2026-01-16 11:08:09', '2026-02-02 20:24:03'),
(3, 'Laboratoire Alpha', 'Espace de travaux pratiques spacieux avec 24 paillasses individuelles.', 'assets/FCRV69r1Z2jXJvdIbafi5WlrVyJnbtZ95sYd2vz0.jpg', 'room', 'available', '2026-01-16 11:08:09', '2026-02-02 21:20:56'),
(4, 'Laboratoire Bêta', 'Salle de manipulation technique équipée de 20 postes de travail fixes.', 'assets/td4TySnXIkChzy7Cjp7Piu8RW6ib7WfwhegHDtIw.jpg', 'room', 'in_repair', '2026-01-16 11:08:09', '2026-02-02 20:24:33'),
(5, 'Laboratoire Gamma', 'Grand plateau technique de 120 m² pouvant accueillir 30 étudiants.', 'assets/0Tq6xI3N12AXrgdHdBabcVuIbgp8xFp3KQqdTEMg.jpg', 'room', 'available', '2026-01-16 11:08:09', '2026-02-02 20:24:00'),
(6, 'Salle de Cours 101', 'Salle de cours magistral standard avec une capacité de 80 places.', 'assets/sSzwSXK00X3tMlskEgmvKrbjzKhta1b9FpwqbkUN.jpg', 'room', 'available', '2026-01-16 11:08:09', '2026-02-02 19:00:07'),
(7, 'Salle de Cours 102', 'Salle de cours de taille moyenne offrant 50 places assises.', 'assets/FNLS5O59MAhbOxjAls7hT9mpHtzszUTSsSaG7X0H.webp', 'room', 'available', '2026-01-16 11:08:09', '2026-02-02 21:21:00'),
(8, 'Salle de TD 201', 'Petite salle pour travaux dirigés avec une capacité de 25 places.', 'assets/7jxpHtCxyYvAtAPEjI2YFaOWvhiNmnN37XZuaENf.jpg', 'room', 'available', '2026-01-16 11:08:09', '2026-02-02 20:23:54'),
(9, 'Salle de TD 202', 'Salle de TD modulaire de 40 m² prévue pour 20 étudiants.', 'assets/ii78q0It50cGIUhpY15j88iCxvT75zaOkFWncZkC.jpg', 'room', 'available', '2026-01-16 11:08:09', '2026-01-20 04:40:37'),
(10, 'Salle de TD 203', 'Espace de travail en sous-groupe d’une capacité maximale de 15 places.', 'assets/m3yUS9hAtwRGpXqlnov9KZyHhhVHoLEWSgst1N9l.jpg', 'room', 'available', '2026-01-16 11:08:09', '2026-02-02 20:23:48'),
(11, 'Salle de TD 204', 'Salle de tutorat optimisée pour des groupes de 20 personnes.', 'assets/CWdO0GmmrtVSU0fkS9IXT2OIJNwMrT0BwaJGprv5.jpg', 'room', 'available', '2026-01-16 11:08:09', '2026-02-02 20:23:51'),
(12, 'Microphone HF Sans Fil', 'Système de micro-cravate professionnel pour l’Amphithéâtre Pasteur.', 'assets/9LpaMNWqPbLumAb8nn037Ov8rNaUyPNEBR2GLeiJ.jpg', 'equipment', 'available', '2026-01-16 11:08:09', '2026-01-20 04:50:23'),
(13, 'Oscilloscope Numérique', 'Appareil de mesure 2 voies haute précision pour le Laboratoire de Physique.', 'assets/cRAkrfx85E4rcd02ooDTN9bYSX2lK832YP3tAp0f.jpg', 'equipment', 'available', '2026-01-16 11:08:09', '2026-02-02 20:23:32'),
(15, 'Projecteur Intelligent Borrego T7 – WiFi & LED', 'haute luminosité et connectivité intelligente', 'assets/9AQwm5Bq4klkFPAtL91X5licqdQABqRTlHDhRpLJ.jpg', 'equipment', 'available', '2026-01-16 11:08:09', '2026-02-02 20:24:06'),
(16, 'Vidéoprojecteur Mobile', 'Unité portable haute luminosité pour les salles de TD non équipées.', 'assets/0ZzTuppA7Z9oO1HzLWBv2lrSGfgRN5JbEUjE7TWJ.jpg', 'equipment', 'available', '2026-01-16 11:08:09', '2026-02-02 14:46:48'),
(17, 'Station de Soudage Weller', 'Poste de soudure de précision pour les circuits du Labo Réseaux & Cyber.', 'assets/og1dFgBNyEYBbkhv8Uu0dCVKl654ozF38cA8Q0l7.jpg', 'equipment', 'available', '2026-01-16 11:08:09', '2026-02-02 14:46:48'),
(18, 'Tablette Graphique Wacom', 'Outil d’annotation numérique pour les présentations en Salle de Conférence.', 'assets/L3kfz9EwHOTCWmq4irBgoe83PcosBFQnlC7Ola71.jpg', 'equipment', 'available', '2026-01-16 11:08:09', '2026-02-02 14:46:48'),
(19, 'Valise de Robotique', 'Kit complet comprenant moteurs et micro-contrôleurs pour les projets de TD.', 'assets/5iQFJocXGyG8kqPL4GAgPAWdps3JzMTAJzlkVIEG.webp', 'equipment', 'available', '2026-01-16 11:08:09', '2026-02-02 14:40:33');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `asset_id` bigint UNSIGNED NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('active','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `asset_id`, `start_time`, `end_time`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 10, '2026-12-06 12:00:00', '2026-12-06 20:00:00', 'cancelled', '2026-01-16 18:02:12', '2026-01-18 08:14:38'),
(2, 2, 12, '2026-12-06 12:00:00', '2026-12-06 20:00:00', 'cancelled', '2026-01-16 18:02:12', '2026-01-18 08:14:38'),
(3, 2, 13, '2026-12-06 12:00:00', '2026-12-06 20:00:00', 'cancelled', '2026-01-16 18:02:12', '2026-01-18 08:14:38'),
(5, 2, 15, '2026-12-06 12:00:00', '2026-12-06 20:00:00', 'cancelled', '2026-01-16 18:02:12', '2026-01-18 08:14:38'),
(6, 2, 9, '2026-01-16 08:04:00', '2026-01-16 20:00:00', 'active', '2026-01-16 18:05:05', '2026-01-16 18:05:05'),
(7, 2, 17, '2026-01-16 08:04:00', '2026-01-16 20:00:00', 'active', '2026-01-16 18:05:05', '2026-01-16 18:05:05'),
(8, 2, 18, '2026-01-16 08:04:00', '2026-01-16 20:00:00', 'active', '2026-01-16 18:05:05', '2026-01-16 18:05:05'),
(9, 2, 19, '2026-01-16 08:04:00', '2026-01-16 20:00:00', 'active', '2026-01-16 18:05:05', '2026-01-16 18:05:05'),
(10, 2, 1, '2026-01-17 10:03:00', '2026-01-17 11:04:00', 'active', '2026-01-17 08:05:02', '2026-01-17 08:05:02'),
(11, 2, 12, '2026-01-17 10:03:00', '2026-01-17 11:04:00', 'active', '2026-01-17 08:05:02', '2026-01-17 08:05:02'),
(12, 2, 13, '2026-01-17 10:03:00', '2026-01-17 11:04:00', 'active', '2026-01-17 08:05:02', '2026-01-17 08:05:02'),
(14, 2, 15, '2026-01-17 10:03:00', '2026-01-17 11:04:00', 'active', '2026-01-17 08:05:02', '2026-01-17 08:05:02'),
(15, 2, 16, '2026-01-17 10:03:00', '2026-01-17 11:04:00', 'active', '2026-01-17 08:05:02', '2026-01-17 08:05:02'),
(16, 2, 7, '2026-01-18 08:47:00', '2026-01-18 20:00:00', 'cancelled', '2026-01-18 06:49:00', '2026-01-18 08:14:58'),
(17, 2, 13, '2026-01-18 08:47:00', '2026-01-18 20:00:00', 'cancelled', '2026-01-18 06:49:00', '2026-01-18 08:14:58'),
(19, 2, 15, '2026-01-18 08:47:00', '2026-01-18 20:00:00', 'cancelled', '2026-01-18 06:49:00', '2026-01-18 08:14:58'),
(20, 2, 16, '2026-01-18 08:47:00', '2026-01-18 20:00:00', 'cancelled', '2026-01-18 06:49:00', '2026-01-18 08:14:58'),
(21, 2, 17, '2026-01-18 08:47:00', '2026-01-18 20:00:00', 'cancelled', '2026-01-18 06:49:00', '2026-01-18 08:14:58'),
(22, 2, 10, '2026-01-19 12:10:00', '2026-01-19 14:10:00', 'cancelled', '2026-01-18 07:11:23', '2026-01-18 08:14:50'),
(23, 2, 18, '2026-01-19 12:10:00', '2026-01-19 14:10:00', 'cancelled', '2026-01-18 07:11:23', '2026-01-18 08:14:50'),
(24, 2, 8, '2026-01-18 09:11:00', '2026-01-18 10:11:00', 'cancelled', '2026-01-18 07:12:08', '2026-01-18 07:36:33'),
(25, 2, 12, '2026-01-18 09:11:00', '2026-01-18 10:11:00', 'cancelled', '2026-01-18 07:12:08', '2026-01-18 08:14:46'),
(26, 1, 1, '2026-01-30 11:23:00', '2026-01-30 15:23:00', 'cancelled', '2026-01-18 07:24:09', '2026-01-18 08:14:43'),
(27, 2, 1, '2026-01-21 07:00:00', '2026-01-21 11:00:00', 'cancelled', '2026-01-18 09:26:00', '2026-01-18 09:30:19'),
(28, 2, 12, '2026-01-21 07:00:00', '2026-01-21 11:00:00', 'cancelled', '2026-01-18 09:26:00', '2026-01-18 09:30:19'),
(29, 2, 13, '2026-01-21 07:00:00', '2026-01-21 11:00:00', 'cancelled', '2026-01-18 09:26:00', '2026-01-18 09:30:19'),
(30, 7, 10, '2026-01-18 07:00:00', '2026-01-18 10:00:00', 'cancelled', '2026-01-18 09:47:51', '2026-01-18 12:32:43'),
(31, 7, 12, '2026-01-18 07:00:00', '2026-01-18 10:00:00', 'cancelled', '2026-01-18 09:47:51', '2026-01-18 12:32:43'),
(32, 7, 13, '2026-01-18 07:00:00', '2026-01-18 10:00:00', 'cancelled', '2026-01-18 09:47:51', '2026-01-18 12:32:43'),
(34, 7, 15, '2026-01-18 07:00:00', '2026-01-18 10:00:00', 'cancelled', '2026-01-18 09:47:51', '2026-01-18 12:32:43'),
(35, 7, 17, '2026-01-18 07:00:00', '2026-01-18 10:00:00', 'cancelled', '2026-01-18 09:47:51', '2026-01-18 12:32:43'),
(36, 7, 1, '2026-01-18 14:00:00', '2026-01-18 16:00:00', 'cancelled', '2026-01-18 12:31:17', '2026-01-18 19:28:21'),
(37, 7, 12, '2026-01-18 14:00:00', '2026-01-18 16:00:00', 'cancelled', '2026-01-18 12:31:17', '2026-01-18 19:28:21'),
(38, 7, 4, '2026-01-19 07:00:00', '2026-01-19 07:30:00', 'cancelled', '2026-01-18 13:08:34', '2026-01-18 13:09:31'),
(40, 7, 4, '2026-01-18 15:00:00', '2026-01-18 15:30:00', 'cancelled', '2026-01-18 13:10:04', '2026-01-18 14:53:21'),
(41, 7, 2, '2026-01-18 15:00:00', '2026-01-18 15:30:00', 'cancelled', '2026-01-18 14:25:41', '2026-01-18 14:53:21'),
(43, 2, 7, '2026-01-18 16:00:00', '2026-01-18 16:30:00', 'cancelled', '2026-01-18 15:07:06', '2026-01-18 15:54:43'),
(44, 2, 13, '2026-01-18 16:00:00', '2026-01-18 16:30:00', 'cancelled', '2026-01-18 15:07:06', '2026-01-18 15:54:43'),
(45, 2, 9, '2026-01-19 11:30:00', '2026-01-19 12:00:00', 'cancelled', '2026-01-18 19:32:17', '2026-01-18 19:33:38'),
(46, 2, 10, '2026-01-19 11:00:00', '2026-01-19 11:30:00', 'cancelled', '2026-01-18 19:37:57', '2026-01-18 20:16:12'),
(47, 1, 10, '2026-01-19 10:30:00', '2026-01-19 12:00:00', 'cancelled', '2026-01-18 20:16:37', '2026-01-18 20:30:39'),
(48, 2, 11, '2026-01-20 11:30:00', '2026-01-20 12:00:00', 'cancelled', '2026-01-18 20:25:42', '2026-01-19 08:15:42'),
(49, 1, 1, '2026-01-19 14:30:00', '2026-01-19 15:30:00', 'cancelled', '2026-01-18 21:32:41', '2026-01-19 08:15:39'),
(50, 1, 15, '2026-01-19 14:30:00', '2026-01-19 15:30:00', 'cancelled', '2026-01-18 21:32:41', '2026-01-19 08:15:39'),
(51, 2, 12, '2026-01-18 12:40:00', '2026-01-18 13:39:00', 'active', '2026-01-18 22:00:54', '2026-01-18 22:00:54'),
(52, 1, 11, '2026-01-19 07:00:00', '2026-01-19 07:30:00', 'active', '2026-01-19 07:14:30', '2026-01-19 07:14:30'),
(53, 1, 9, '2026-01-19 08:15:00', '2026-01-19 08:16:00', 'active', '2026-01-19 07:15:27', '2026-01-19 07:15:27'),
(54, 1, 12, '2026-01-19 08:50:00', '2026-01-19 08:51:00', 'active', '2026-01-19 07:50:15', '2026-01-19 07:50:15'),
(55, 1, 7, '2026-01-19 08:50:00', '2026-01-19 08:51:00', 'active', '2026-01-19 07:50:45', '2026-01-19 07:50:45'),
(56, 1, 13, '2026-01-19 08:50:00', '2026-01-19 08:51:00', 'active', '2026-01-19 07:50:45', '2026-01-19 07:50:45'),
(57, 1, 12, '2026-01-19 09:02:00', '2026-01-19 09:03:00', 'active', '2026-01-19 08:03:00', '2026-01-19 08:03:00'),
(58, 1, 11, '2026-01-19 09:04:00', '2026-01-19 09:05:00', 'active', '2026-01-19 08:04:20', '2026-01-19 08:04:20'),
(59, 1, 12, '2026-01-19 09:04:00', '2026-01-19 09:05:00', 'active', '2026-01-19 08:04:20', '2026-01-19 08:04:20'),
(60, 1, 2, '2026-01-19 09:13:00', '2026-01-19 09:14:00', 'active', '2026-01-19 08:13:15', '2026-01-19 08:13:15'),
(61, 1, 9, '2026-01-19 09:28:00', '2026-01-19 09:30:00', 'active', '2026-01-19 08:29:16', '2026-01-19 08:29:16'),
(62, 1, 12, '2026-01-19 09:28:00', '2026-01-19 09:30:00', 'active', '2026-01-19 08:29:16', '2026-01-19 08:29:16'),
(63, 1, 13, '2026-01-19 09:28:00', '2026-01-19 09:30:00', 'active', '2026-01-19 08:29:16', '2026-01-19 08:29:16'),
(65, 1, 2, '2026-01-19 08:50:00', '2026-01-19 08:51:00', 'active', '2026-01-19 08:29:47', '2026-01-19 08:29:47'),
(66, 2, 6, '2026-01-19 09:12:00', '2026-01-19 09:14:00', 'active', '2026-01-19 08:46:57', '2026-01-19 08:46:57'),
(67, 2, 6, '2026-01-19 09:03:00', '2026-01-19 09:04:00', 'active', '2026-01-19 08:47:10', '2026-01-19 08:47:10'),
(68, 2, 11, '2026-01-19 08:50:00', '2026-01-19 08:51:00', 'active', '2026-01-19 08:47:23', '2026-01-19 08:47:23'),
(70, 1, 1, '2026-01-20 09:03:00', '2026-01-20 09:15:00', 'active', '2026-01-20 20:29:56', '2026-01-20 20:29:56'),
(71, 1, 16, '2026-01-20 09:03:00', '2026-01-20 09:15:00', 'active', '2026-01-20 20:29:56', '2026-01-20 20:29:56'),
(72, 1, 11, '2026-01-20 08:50:00', '2026-01-20 09:13:00', 'active', '2026-01-20 20:32:14', '2026-01-20 20:32:14'),
(73, 1, 4, '2026-01-20 09:03:00', '2026-01-20 09:05:00', 'active', '2026-01-20 20:55:30', '2026-01-20 20:55:30'),
(74, 1, 12, '2026-01-20 09:03:00', '2026-01-20 09:05:00', 'active', '2026-01-20 20:55:30', '2026-01-20 20:55:30'),
(75, 1, 13, '2026-01-20 09:03:00', '2026-01-20 09:05:00', 'active', '2026-01-20 20:55:30', '2026-01-20 20:55:30'),
(77, 1, 8, '2026-01-24 07:00:00', '2026-01-24 13:39:00', 'active', '2026-01-24 19:50:40', '2026-01-24 19:50:40'),
(78, 1, 12, '2026-01-24 07:00:00', '2026-01-24 13:39:00', 'active', '2026-01-24 19:50:40', '2026-01-24 19:50:40'),
(79, 1, 13, '2026-01-24 07:00:00', '2026-01-24 13:39:00', 'active', '2026-01-24 19:50:40', '2026-01-24 19:50:40'),
(80, 2, 17, '2026-01-31 07:00:00', '2026-01-31 08:00:00', 'active', '2026-01-24 20:19:15', '2026-01-24 20:19:15'),
(83, 1, 10, '2026-02-02 07:00:00', '2026-02-02 13:39:00', 'active', '2026-02-02 14:36:20', '2026-02-02 14:36:20'),
(84, 1, 12, '2026-02-02 07:00:00', '2026-02-02 13:39:00', 'active', '2026-02-02 14:36:20', '2026-02-02 14:36:20'),
(85, 1, 13, '2026-02-02 07:00:00', '2026-02-02 13:39:00', 'active', '2026-02-02 14:36:20', '2026-02-02 14:36:20'),
(87, 1, 16, '2026-02-02 07:00:00', '2026-02-02 13:39:00', 'active', '2026-02-02 14:36:20', '2026-02-02 14:36:20'),
(88, 1, 19, '2026-02-02 12:40:00', '2026-02-02 13:39:00', 'active', '2026-02-02 14:43:32', '2026-02-02 14:43:32'),
(89, 1, 7, '2026-02-02 14:20:00', '2026-02-02 16:11:00', 'active', '2026-02-02 14:45:02', '2026-02-02 14:45:02'),
(90, 1, 1, '2026-02-02 14:00:00', '2026-02-02 16:11:00', 'cancelled', '2026-02-02 14:46:02', '2026-02-02 14:46:48'),
(92, 1, 16, '2026-02-02 14:00:00', '2026-02-02 16:11:00', 'cancelled', '2026-02-02 14:46:02', '2026-02-02 14:46:48'),
(93, 1, 17, '2026-02-02 14:00:00', '2026-02-02 16:11:00', 'cancelled', '2026-02-02 14:46:02', '2026-02-02 14:46:48'),
(94, 1, 18, '2026-02-02 14:00:00', '2026-02-02 16:11:00', 'cancelled', '2026-02-02 14:46:02', '2026-02-02 14:46:48'),
(95, 2, 8, '2026-02-03 12:40:00', '2026-02-03 16:11:00', 'cancelled', '2026-02-02 18:07:10', '2026-02-02 19:36:54'),
(97, 2, 6, '2026-02-02 19:11:00', '2026-02-02 20:00:00', 'active', '2026-02-02 18:27:37', '2026-02-02 18:27:37');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_16_110204_create_assets_table', 1),
(5, '2026_01_16_110332_create_bookings_table', 1),
(6, '2026_01_16_110419_create_reports_table', 1),
(7, '2026_01_16_121503_create_notifications_table', 2),
(8, '2026_01_17_000000_create_sessions_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `asset_id` bigint UNSIGNED NOT NULL,
  `problem_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `possible_cause` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','resolved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('cjhBJBrLqZ33py0sDd4cbMTlDP7Nlwi1eOwhfAa3', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiN3hPNEpDbE53SWxSVnFQMDgwcDVPOHJLWWY3UWdqakRsV2JCMTZGUyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ub3RpZmljYXRpb25zIjtzOjU6InJvdXRlIjtzOjE5OiJub3RpZmljYXRpb25zLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1770064204),
('KyC59UbRT5KA8Nnjui65oaoj7eJLA0zJL0Db5hVb', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNXljMmJndjZuUGJwUkFIcjlDVkNBNUdFVVFiWXpmNThkeGZSQ2ZmRCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ub3RpZmljYXRpb25zIjtzOjU6InJvdXRlIjtzOjE5OiJub3RpZmljYXRpb25zLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1770067526),
('ndnGLhOLesrUtnJr3qFp8khF11Z9HzlabQQJ68Qa', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUlM4RjFCNTJFZ21MaGhuMUlERFBKMWVyWm1RaXV4V0NrVWpydHN0NiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1770060832);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','teacher') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'teacher',
  `profile_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `profile_picture`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Dr. Mourad Mezache', 'admin@institute.dz', NULL, '$2y$12$8DF2ki.E5Xfm/8cTAs1Q7.BXHj9B34kByoDTf6NCoHH6Mkqso.g1e', 'admin', NULL, NULL, '2026-01-16 11:08:09', '2026-01-16 11:08:09'),
(2, 'Prof. Lydia Idir', 'lydia.idir@institute.dz', NULL, '$2y$12$NtR0pqDXU4K.M/41s712sedkSS2IzCUDO65B28BJVGH5fY8HiMlIe', 'teacher', NULL, 'qfYY6L0OQGCAwvxANjZXzmvp0Sq3IRi2n3wqUxsE2maMXg7o0BrYxZuQ0C1V', '2026-01-16 11:08:09', '2026-01-16 11:08:09'),
(3, 'Prof. Bachir Saaidia', 'bachir.saaidia@institute.dz', NULL, '$2y$12$XFj1CEI7rY5ujm.MnFdl8uxNqWgXyCxrPTfBs4ZfbJu1.JV6YKEUW', 'teacher', NULL, NULL, '2026-01-16 11:08:09', '2026-01-16 11:08:09'),
(4, 'Prof. Amel Afia', 'amel.afia@institute.dz', NULL, '$2y$12$DjwaK6n.s9q3mRyTDT3qneg4QyHRzm6V6/G7Jv7fkMk7a6m09u8mu', 'teacher', NULL, NULL, '2026-01-16 11:08:09', '2026-01-16 11:08:09'),
(5, 'Prof. Sihem Aimeur', 'sihem.aimeur@institute.dz', NULL, '$2y$12$YGXDhiZMJq.TAvbGbwpxQeCCTuGf2Ks7eqoY5fOliVQ4yWEfI.KuO', 'teacher', NULL, NULL, '2026-01-16 11:08:09', '2026-01-16 11:08:09'),
(6, 'Prof. Karim Benali', 'karim.benali@institute.dz', NULL, '$2y$12$xgUbcMXn/Ckyr/3StVAc4.onmPGe707HgzKGc.HqmBB2OKpfNYYWa', 'teacher', NULL, NULL, '2026-01-16 11:08:09', '2026-01-16 11:08:09'),
(7, 'Prof. Fatima Zohra', 'fatima.z@institute.dz', NULL, '$2y$12$JpSYNxCznAsl7KcOLvUrveY06AGaBLUn/1je/IEL/qq1efipAdUtq', 'teacher', NULL, NULL, '2026-01-16 11:08:09', '2026-01-16 11:08:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assets_status_index` (`status`),
  ADD KEY `assets_type_index` (`type`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_asset_id_start_time_end_time_index` (`asset_id`,`start_time`,`end_time`),
  ADD KEY `bookings_user_id_index` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_user_id_foreign` (`user_id`),
  ADD KEY `reports_asset_id_foreign` (`asset_id`),
  ADD KEY `reports_status_index` (`status`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
