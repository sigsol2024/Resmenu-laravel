-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 07, 2026 at 10:17 AM
-- Server version: 10.6.28-MariaDB
-- PHP Version: 8.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sigsolmenu_laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `actor_type` varchar(20) NOT NULL,
  `actor_id` int(11) DEFAULT NULL,
  `restaurant_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `subject_type` varchar(50) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(512) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `actor_type`, `actor_id`, `restaurant_id`, `action`, `subject_type`, `subject_id`, `old_values`, `new_values`, `ip`, `user_agent`, `created_at`) VALUES
(1, 'admin', 3, 27, 'subscription.status_changed', 'subscription', 36, '{\"status\":\"trial\"}', '{\"status\":\"expired\"}', '98.97.76.189', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 16:44:42'),
(2, 'admin', 3, 27, 'subscription.status_changed', 'subscription', 36, '{\"status\":\"active\"}', '{\"status\":\"expired\"}', '102.93.9.83', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:20:32'),
(3, 'admin', 3, 27, 'subscription.plan_changed', 'subscription', 36, '{\"plan_id\":1}', '{\"plan_id\":2}', '102.93.9.83', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:21:44'),
(4, 'admin', 3, 27, 'subscription.status_changed', 'subscription', 36, '{\"status\":\"expired\"}', '{\"status\":\"active\"}', '102.93.9.83', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:21:52'),
(5, 'admin', 3, 27, 'subscription.status_changed', 'subscription', 36, '{\"status\":\"active\"}', '{\"status\":\"expired\"}', '102.93.9.83', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-01 23:22:45'),
(6, 'admin', 3, 20, 'subscription.status_changed', 'subscription', 32, '{\"status\":\"trial\"}', '{\"status\":\"expired\"}', '98.97.79.25', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-08 13:43:31'),
(7, 'admin', 3, 13, 'subscription.status_changed', 'subscription', 33, '{\"status\":\"active\"}', '{\"status\":\"expired\"}', '102.93.10.244', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-09 18:18:40'),
(8, 'admin', 3, 4, 'subscription.extended', 'subscription', 29, '{\"status\":\"expired\",\"trial_ends_at\":\"2026-06-12T00:14:50+00:00\",\"current_period_end\":null}', '{\"status\":\"trial\",\"trial_ends_at\":\"2026-06-19T13:32:21+00:00\",\"current_period_end\":null,\"days\":7}', '102.89.76.29', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-12 13:32:21'),
(9, 'admin', 3, 13, 'subscription.extended', 'subscription', 33, '{\"status\":\"expired\",\"trial_ends_at\":\"2026-05-18T23:11:27+00:00\",\"current_period_end\":\"2026-08-12T13:29:58+00:00\"}', '{\"status\":\"active\",\"trial_ends_at\":\"2026-05-18T23:11:27+00:00\",\"current_period_end\":\"2027-08-12T13:29:58+00:00\",\"days\":365}', '102.88.112.242', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-17 14:45:03'),
(10, 'admin', 3, 4, 'subscription.extended', 'subscription', 29, '{\"status\":\"trial\",\"trial_ends_at\":\"2026-06-19T13:32:21+00:00\",\"current_period_end\":null}', '{\"status\":\"trial\",\"trial_ends_at\":\"2026-06-26T13:32:21+00:00\",\"current_period_end\":null,\"days\":7}', '102.89.82.122', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-19 00:05:34'),
(11, 'admin', 3, 3, 'subscription.extended', 'subscription', 35, '{\"status\":\"expired\",\"trial_ends_at\":\"2026-02-20T08:57:39+00:00\",\"current_period_end\":\"2026-06-18T14:37:57+00:00\"}', '{\"status\":\"active\",\"trial_ends_at\":\"2026-02-20T08:57:39+00:00\",\"current_period_end\":\"2026-07-20T14:54:00+00:00\",\"days\":30}', '102.89.84.183', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 14:54:00'),
(12, 'admin', 3, 27, 'subscription.plan_changed', 'subscription', 36, '{\"plan_id\":1}', '{\"plan_id\":3}', '102.89.84.183', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-20 14:54:16'),
(13, 'admin', 3, 4, 'subscription.extended', 'subscription', 29, '{\"status\":\"trial\",\"trial_ends_at\":\"2026-06-26T13:32:21+00:00\",\"current_period_end\":null}', '{\"status\":\"trial\",\"trial_ends_at\":\"2027-06-26T13:32:21+00:00\",\"current_period_end\":null,\"days\":365}', '102.89.68.121', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-22 16:39:10'),
(14, 'admin', 3, 25, 'subscription.extended', 'subscription', 34, '{\"status\":\"expired\",\"trial_ends_at\":\"2026-06-21T17:49:51+00:00\",\"current_period_end\":null}', '{\"status\":\"trial\",\"trial_ends_at\":\"2026-06-30T20:17:57+00:00\",\"current_period_end\":null,\"days\":7}', '102.89.68.157', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-23 20:17:57'),
(15, 'admin', 3, 25, 'payment.status_changed', 'payment', 26, '{\"status\":\"pending\"}', '{\"status\":\"success\",\"note\":\"Manual admin confirmation of payment #26\"}', '102.89.85.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-07-01 21:14:03'),
(16, 'admin', 3, 21, 'subscription.extended', 'subscription', 27, '{\"status\":\"expired\",\"trial_ends_at\":\"2026-06-18T14:46:09+00:00\",\"current_period_end\":null}', '{\"status\":\"trial\",\"trial_ends_at\":\"2027-07-13T17:58:59+00:00\",\"current_period_end\":null,\"days\":365}', '98.97.79.209', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 17:58:59'),
(17, 'admin', 3, 25, 'subscription.extended', 'subscription', 34, '{\"status\":\"expired\",\"trial_ends_at\":null,\"current_period_end\":\"2026-08-01T21:14:03+00:00\"}', '{\"status\":\"active\",\"trial_ends_at\":null,\"current_period_end\":\"2026-08-17T15:46:27+00:00\",\"days\":7}', '129.222.206.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-10 15:46:27'),
(18, 'admin', 3, 25, 'subscription.extended', 'subscription', 34, '{\"status\":\"active\",\"trial_ends_at\":null,\"current_period_end\":\"2026-08-17T15:46:27+00:00\"}', '{\"status\":\"active\",\"trial_ends_at\":null,\"current_period_end\":\"2027-08-17T15:46:27+00:00\",\"days\":365}', '129.222.206.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-10 15:48:52'),
(19, 'admin', 3, 32, 'restaurant.created', 'restaurant', 32, NULL, '{\"name\":\"Ibile Moinmoin and Akara limited\",\"slug\":\"ibile-moinmoin-and-akara-limited\",\"plan_id\":null}', '102.88.113.150', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-14 15:00:05'),
(20, 'admin', 3, 32, 'subscription.trial_assigned', 'subscription', 41, NULL, '{\"plan_id\":1,\"status\":\"trial\"}', '102.88.113.150', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-14 16:53:31'),
(21, 'admin', 3, 31, 'payment.status_changed', 'payment', 28, '{\"status\":\"pending\"}', '{\"status\":\"failed\",\"note\":null}', '102.89.69.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-19 14:59:53'),
(22, 'admin', 3, 31, 'payment.status_changed', 'payment', 27, '{\"status\":\"pending\"}', '{\"status\":\"failed\",\"note\":null}', '102.89.69.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-19 15:00:00'),
(23, 'admin', 3, 4, 'payment.status_changed', 'payment', 24, '{\"status\":\"pending\"}', '{\"status\":\"failed\",\"note\":null}', '102.89.69.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-19 15:00:20'),
(24, 'admin', 3, 4, 'payment.status_changed', 'payment', 25, '{\"status\":\"pending\"}', '{\"status\":\"success\",\"note\":\"Manual admin confirmation of payment #25\"}', '102.89.69.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-19 15:00:30');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password_hash`, `created_at`, `updated_at`) VALUES
(3, 'sigsol2024', 'sigsol2024@gmail.com', '$2y$10$rGSkGNyikjhRyBx5ASECrO8zDSU4/fv7HqgIS5kXWTF9kx.zolyHe', '2026-03-07 23:33:20', '2026-03-07 23:33:20'),
(5, 'brain', 'info@signature-solutions.com', '$2y$10$mV8ZSWBCB0mpgpFbMvEp1OgyBE/vngqutCrGYtipzxuugEjhCEzRG', '2026-05-19 14:47:45', '2026-05-19 14:47:45');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `restaurant_id`, `section_id`, `name`, `slug`, `image`, `description`, `display_order`, `is_active`, `created_at`, `updated_at`) VALUES
(303, 21, 36, 'Brandy', 'b', NULL, '', 9, 1, '2026-05-19 17:09:57', '2026-05-19 21:52:42'),
(304, 21, 36, 'Whisky', 'w', NULL, '', 10, 1, '2026-05-19 17:23:54', '2026-05-19 21:52:42'),
(305, 21, 36, 'Champagne', 'c', NULL, '', 11, 1, '2026-05-19 17:37:33', '2026-05-19 21:52:42'),
(306, 21, 36, 'Mocktails', 'm', NULL, '', 12, 1, '2026-05-19 17:38:17', '2026-05-19 21:52:42'),
(307, 21, 36, 'Cocktails', 'cocktails', NULL, '', 13, 1, '2026-05-19 18:08:15', '2026-05-19 21:52:42'),
(308, 21, 36, 'Liquor', 'l', NULL, '', 14, 1, '2026-05-19 18:09:01', '2026-05-19 21:52:42'),
(309, 21, 36, 'Vodka/Gin', 'v', NULL, '', 15, 1, '2026-05-19 18:09:45', '2026-05-19 21:52:42'),
(310, 21, 36, 'Wines', 'wine', NULL, '', 16, 1, '2026-05-19 18:10:49', '2026-05-19 21:52:42'),
(311, 21, 36, 'Beers', 'beers', NULL, '', 17, 1, '2026-05-19 18:12:23', '2026-05-19 21:52:42'),
(312, 21, 36, 'Juice', 'j', NULL, '', 18, 1, '2026-05-19 18:15:26', '2026-05-19 21:52:42'),
(313, 21, 36, 'Energy Drink', 'e', NULL, '', 19, 1, '2026-05-19 18:16:24', '2026-05-19 21:52:42'),
(314, 21, 36, 'Soft Drinks', 's', NULL, '', 20, 1, '2026-05-19 18:17:00', '2026-05-19 21:52:42'),
(315, 21, 36, 'Tots/Shots', 't', NULL, '', 21, 1, '2026-05-19 18:28:00', '2026-05-19 21:52:42'),
(316, 21, 36, 'Tequilas', 'tequilas', NULL, NULL, 22, 1, '2026-05-19 20:56:44', '2026-08-12 19:42:05'),
(317, 21, 35, 'Natives', 'n', NULL, '', 1, 1, '2026-05-19 21:41:42', '2026-05-19 21:41:42'),
(318, 21, 35, 'Grills', 'g', NULL, '', 2, 1, '2026-05-19 21:46:17', '2026-05-19 21:46:17'),
(319, 21, 35, 'Peppered Protein', 'p', NULL, '', 3, 1, '2026-05-19 21:47:35', '2026-05-19 21:47:35'),
(320, 2, 38, 'Appetizer', 'appetizers', '6945d9626bc44.jpg', 'Start your meal with our delicious appetizers', 1, 1, '2025-12-19 18:43:07', '2026-03-13 01:58:22'),
(321, 2, 38, 'Side Orders', 'side-orders', '6945d97eb3a2f.webp', 'Perfect sides to complement your meal', 2, 1, '2025-12-19 18:43:07', '2026-03-13 01:58:22'),
(322, 2, 38, 'Desserts', 'desserts', '6945d9f81c699.jpg', 'Sweet endings to your meal', 3, 1, '2025-12-19 18:43:07', '2026-03-13 01:58:22'),
(323, 2, 38, 'Champagne', 'champagne', '6945da0b5f74f.jpg', 'Premium champagne selection', 4, 1, '2025-12-19 18:43:07', '2026-03-13 01:58:22'),
(324, 2, 38, 'Tequila', 'tequila', '6945da1a42b4c.jpg', 'Premium tequila collection', 5, 1, '2025-12-19 18:43:07', '2026-03-13 01:58:22'),
(325, 2, 38, 'Cognac', 'cognac', '6945da2ba75b9.jpg', 'Fine cognac selection', 6, 1, '2025-12-19 18:43:07', '2026-03-13 01:58:22'),
(326, 2, 38, 'Whiskey', 'whiskey', '6945da3b4758d.jpg', 'Premium whiskey collection', 7, 1, '2025-12-19 18:43:07', '2026-03-13 01:58:22'),
(327, 2, 38, 'Shisha', 'shisha', '6945da4e4f519.jpg', 'Flavored shisha selection', 8, 1, '2025-12-19 18:43:07', '2026-03-13 01:58:22'),
(328, 4, 39, 'Starters', 'food-starters', '6a045de08b74d.webp', '', 1, 1, '2026-05-13 01:27:51', '2026-05-13 11:17:52'),
(329, 4, 39, 'Main Course', 'food-main-course', '6a046fe792f15.webp', '', 5, 1, '2026-05-13 01:27:51', '2026-05-13 12:34:47'),
(330, 4, 39, 'Platters', 'food-platters', NULL, NULL, 9, 1, '2026-05-13 01:27:51', '2026-05-13 01:28:09'),
(331, 4, 39, 'Salads', 'food-salads', NULL, NULL, 12, 1, '2026-05-13 01:27:51', '2026-05-13 01:28:09'),
(332, 4, 39, 'Sides', 'food-sides', '6a045e4fe0a49.webp', '', 14, 1, '2026-05-13 01:27:51', '2026-05-13 11:19:43'),
(333, 4, 39, 'Desserts', 'food-desserts', NULL, NULL, 16, 1, '2026-05-13 01:27:51', '2026-05-13 01:28:09'),
(334, 4, 40, 'Champagne', 'drink-champagne', '6a046f7a33272.webp', '', 2, 1, '2026-05-13 01:27:51', '2026-05-13 12:32:58'),
(335, 4, 40, 'Cognac', 'drink-cognac', NULL, NULL, 6, 1, '2026-05-13 01:27:51', '2026-05-13 01:28:09'),
(336, 4, 40, 'Whisky', 'drink-whisky', NULL, NULL, 10, 1, '2026-05-13 01:27:51', '2026-05-13 01:28:09'),
(337, 4, 40, 'Tequila', 'drink-tequila', NULL, NULL, 13, 1, '2026-05-13 01:27:51', '2026-05-13 01:28:09'),
(338, 4, 40, 'Gin', 'drink-gin', NULL, NULL, 15, 1, '2026-05-13 01:27:51', '2026-05-13 01:28:09'),
(339, 4, 40, 'Creams', 'drink-creams', NULL, NULL, 17, 1, '2026-05-13 01:27:51', '2026-05-13 01:28:09'),
(340, 4, 40, 'Bitters', 'drink-bitters', NULL, NULL, 18, 1, '2026-05-13 01:27:51', '2026-05-13 01:28:09'),
(341, 4, 40, 'Rum', 'drink-rum', '6a045fbc01a78.webp', '', 19, 1, '2026-05-13 01:27:51', '2026-05-13 11:25:48'),
(342, 4, 40, 'Red Wine', 'drink-red-wine', '6a046fb6c2a83.webp', '', 20, 1, '2026-05-13 01:27:51', '2026-05-13 12:33:58'),
(343, 4, 40, 'White Wine', 'drink-white-wine', NULL, NULL, 21, 1, '2026-05-13 01:27:51', '2026-05-13 01:28:09'),
(344, 4, 40, 'Cocktails', 'drink-cocktails', NULL, NULL, 22, 1, '2026-05-13 01:27:51', '2026-05-13 01:28:09'),
(345, 4, 40, 'Virgin Cocktails', 'drink-virgin-cocktails', NULL, NULL, 23, 1, '2026-05-13 01:27:51', '2026-05-13 01:28:09'),
(346, 4, 40, 'Beers', 'drink-beers', '6a045ebf2fc29.webp', '', 24, 1, '2026-05-13 01:27:51', '2026-05-13 11:21:35'),
(347, 4, 40, 'Energy Drinks', 'drink-energy-drinks', NULL, NULL, 25, 1, '2026-05-13 01:27:51', '2026-05-13 01:28:09'),
(348, 4, 40, 'Soft Drinks', 'drink-soft-drinks', NULL, NULL, 26, 1, '2026-05-13 01:27:51', '2026-05-13 01:28:09'),
(349, 4, 40, 'Milkshakes', 'drink-milkshakes', NULL, NULL, 27, 1, '2026-05-13 01:27:51', '2026-05-13 01:28:09'),
(350, 4, 41, 'Breakfast Specials', 'brunch-breakfast-specials', NULL, NULL, 3, 1, '2026-05-13 01:27:51', '2026-05-13 01:28:09'),
(351, 4, 41, 'Brunch Sides', 'brunch-sides', NULL, NULL, 7, 1, '2026-05-13 01:27:51', '2026-05-13 01:28:09'),
(352, 4, 41, 'Brunch Desserts', 'brunch-desserts', NULL, NULL, 11, 1, '2026-05-13 01:27:51', '2026-05-13 01:28:09'),
(353, 4, 42, 'Shisha Flavours', 'shisha-flavours', NULL, NULL, 4, 1, '2026-05-13 01:27:51', '2026-05-13 01:28:09'),
(354, 4, 42, 'Extras', 'shisha-extras', NULL, NULL, 8, 1, '2026-05-13 01:27:51', '2026-05-13 01:28:09'),
(355, 19, 43, 'BREAKFAST', 'b', NULL, 'Breakfast is served with a choice of tea, Americano coffee or fresh juice', 43, 1, '2026-05-06 00:55:32', '2026-05-27 19:48:33'),
(356, 19, 43, 'PANCAKES', 'pancakes', NULL, '', 41, 1, '2026-05-06 01:02:51', '2026-05-27 19:48:33'),
(357, 19, 43, 'WAFFLES', 'waffles', NULL, '', 38, 1, '2026-05-06 01:41:17', '2026-05-27 19:48:33'),
(358, 19, 43, 'EGGS', 'eggs', NULL, 'Eggs are served with fresh vegetables, brioche toast and butter', 35, 1, '2026-05-06 01:42:19', '2026-05-27 19:48:33'),
(359, 19, 43, 'APPETIZERS', 'appetizers', NULL, '', 32, 1, '2026-05-06 01:42:53', '2026-05-27 19:48:33'),
(360, 19, 43, 'Salads', 'fm-salads', '6a0bbcbe1625d.webp', '', 3, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(361, 19, 43, 'Appetizers', 'fm-appetizers', NULL, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(362, 19, 43, 'Burgers', 'fm-burgers', NULL, NULL, 9, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(363, 19, 43, 'Sides', 'fm-sides', NULL, NULL, 12, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(364, 19, 43, 'Bao', 'fm-bao', NULL, NULL, 15, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(365, 19, 43, 'Main Course', 'fm-main-course', NULL, NULL, 18, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(366, 19, 43, 'Pastas', 'fm-pastas', '6a0bbc9e9f175.webp', '', 21, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(367, 19, 43, 'Pizza', 'fm-pizza', '6a0bb01b19290.webp', '', 24, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(368, 19, 43, 'Meats', 'fm-meats', '6a0baff4947b2.webp', '', 27, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(369, 19, 43, 'Opal Platter', 'fm-opal-platter', NULL, NULL, 30, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(370, 19, 43, 'Tacos Menu', 'fm-tacos-menu', NULL, NULL, 33, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(371, 19, 43, 'Dessert', 'fm-dessert', NULL, NULL, 36, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(372, 19, 43, 'Karaoke Food Menu', 'fm-karaoke-food', NULL, NULL, 39, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(373, 19, 44, 'Champagne', 'dm-champagne', '6a0bbdc7d693f.webp', '', 4, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(374, 19, 44, 'Tequila', 'dm-tequila', NULL, NULL, 7, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(375, 19, 44, 'Cognac', 'dm-cognac', '6a0bbdb24d0ea.png', '', 10, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(376, 19, 44, 'Vodka', 'dm-vodka', '6a0bbd9231925.webp', '', 13, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(377, 19, 44, 'Rum', 'dm-rum', NULL, NULL, 16, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(378, 19, 44, 'Gin', 'dm-gin', '6a0bbd76bb821.webp', '', 19, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(379, 19, 44, 'Whisky', 'dm-whisky', NULL, NULL, 22, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(380, 19, 44, 'Red Wine', 'dm-red-wine', NULL, NULL, 25, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(381, 19, 44, 'Rose Wine', 'dm-rose-wine', NULL, NULL, 28, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(382, 19, 44, 'White Wine', 'dm-white-wine', NULL, NULL, 31, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(383, 19, 44, 'Beer', 'dm-beer', '6a0bbd6438a02.png', '', 34, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(384, 19, 44, 'Non Alcohol Drinks', 'dm-non-alcohol', '6a0bbd34b0601.webp', '', 37, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(385, 19, 44, 'Juice Pitcher', 'dm-juice-pitcher', NULL, NULL, 40, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(386, 19, 44, 'Hot Beverages', 'dm-hot-beverages', NULL, NULL, 42, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(387, 19, 44, 'Classic Cocktails', 'dm-classic-cocktails', '6a0bbd15bf7c1.webp', '', 44, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(388, 19, 44, 'Signature Cocktails', 'dm-signature-cocktails', NULL, NULL, 45, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(389, 19, 44, 'Shots', 'dm-shots', NULL, NULL, 46, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(390, 19, 44, 'Special Shots', 'dm-special-shots', NULL, NULL, 47, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(391, 19, 44, 'Shisha Menu', 'dm-shisha-menu', NULL, NULL, 48, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(392, 19, 44, 'Karaoke Drink Menu', 'dm-karaoke-drinks', NULL, NULL, 49, 1, '2026-05-19 00:02:52', '2026-05-27 19:48:33'),
(393, 19, 45, 'Café appetizer', 'c', NULL, '', 1, 1, '2026-05-27 19:48:33', '2026-05-27 19:48:33'),
(394, 26, 46, 'Salads', 'fm-salads', NULL, NULL, 1, 1, '2026-05-17 21:36:14', '2026-05-17 21:36:14'),
(395, 26, 46, 'Appetizers', 'fm-appetizers', NULL, NULL, 3, 1, '2026-05-17 21:36:14', '2026-05-17 21:52:41'),
(396, 26, 46, 'Sandwiches', 'fm-sandwiches', NULL, NULL, 5, 1, '2026-05-17 21:36:14', '2026-05-17 21:52:41'),
(397, 26, 46, 'Burgers', 'fm-burgers', NULL, NULL, 7, 1, '2026-05-17 21:36:14', '2026-05-17 21:52:41'),
(398, 26, 46, 'Fried Chicken', 'fm-fried-chicken', NULL, NULL, 9, 1, '2026-05-17 21:36:14', '2026-05-17 21:52:41'),
(399, 26, 46, 'Mains', 'fm-mains', NULL, NULL, 11, 1, '2026-05-17 21:36:14', '2026-05-17 21:52:41'),
(400, 26, 46, 'Sides', 'fm-sides', NULL, NULL, 13, 1, '2026-05-17 21:36:14', '2026-05-17 21:52:41'),
(401, 26, 46, 'Pasta', 'fm-pasta', NULL, NULL, 15, 1, '2026-05-17 21:36:14', '2026-05-17 21:52:41'),
(402, 26, 46, 'Pizza', 'fm-pizza', NULL, NULL, 17, 1, '2026-05-17 21:36:14', '2026-05-17 21:52:41'),
(403, 26, 46, 'Nigerian Section', 'fm-nigerian', NULL, NULL, 19, 1, '2026-05-17 21:36:14', '2026-05-17 21:52:41'),
(404, 26, 46, 'Dessert', 'fm-dessert', NULL, NULL, 21, 1, '2026-05-17 21:36:14', '2026-05-17 21:52:41'),
(405, 26, 47, 'Hot Drinks', 'dm-hot-drinks', NULL, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:52:41'),
(406, 26, 47, 'Soft drinks', 'dm-soft-drinks', NULL, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:52:41'),
(407, 26, 47, 'Fresh Juices', 'dm-fresh-juices', NULL, NULL, 6, 1, '2026-05-17 21:36:15', '2026-05-17 21:52:41'),
(408, 26, 47, 'Milkshakes', 'dm-milkshakes', NULL, NULL, 8, 1, '2026-05-17 21:36:15', '2026-05-17 21:52:41'),
(409, 26, 47, 'Mocktails', 'dm-mocktails', NULL, NULL, 10, 1, '2026-05-17 21:36:15', '2026-05-17 21:52:41'),
(410, 26, 47, 'Signature Cocktail', 'dm-signature-cocktail', NULL, NULL, 12, 1, '2026-05-17 21:36:15', '2026-05-17 21:52:41'),
(411, 26, 47, 'Cocktails', 'dm-cocktails', NULL, NULL, 14, 1, '2026-05-17 21:36:15', '2026-05-17 21:52:41'),
(412, 26, 47, 'Beer', 'dm-beer', NULL, NULL, 16, 1, '2026-05-17 21:36:15', '2026-05-17 21:52:41'),
(413, 26, 47, 'Ready to Drink', 'dm-ready-to-drink', NULL, NULL, 18, 1, '2026-05-17 21:36:15', '2026-05-17 21:52:41'),
(414, 26, 47, 'Vodka', 'dm-vodka', NULL, NULL, 20, 1, '2026-05-17 21:36:15', '2026-05-17 21:52:41'),
(415, 26, 47, 'Tequila', 'dm-tequila', NULL, NULL, 22, 1, '2026-05-17 21:36:15', '2026-05-17 21:52:41'),
(416, 26, 47, 'Rum', 'dm-rum', NULL, NULL, 23, 1, '2026-05-17 21:36:15', '2026-05-17 21:52:41'),
(417, 26, 47, 'Gin', 'dm-gin', NULL, NULL, 24, 1, '2026-05-17 21:36:15', '2026-05-17 21:52:41'),
(418, 26, 47, 'Whiskey', 'dm-whiskey', NULL, NULL, 25, 1, '2026-05-17 21:36:15', '2026-05-17 21:52:41'),
(419, 26, 47, 'Cognac', 'dm-cognac', NULL, NULL, 26, 1, '2026-05-17 21:36:15', '2026-05-17 21:52:41'),
(420, 26, 47, 'Liqueur', 'dm-liqueur', NULL, NULL, 27, 1, '2026-05-17 21:36:15', '2026-05-17 21:52:41'),
(421, 26, 47, 'Wine', 'dm-wine', NULL, NULL, 28, 1, '2026-05-17 21:36:15', '2026-05-17 21:52:41'),
(422, 26, 47, 'Champagne', 'dm-champagne', NULL, NULL, 29, 1, '2026-05-17 21:36:15', '2026-05-17 21:52:41'),
(423, 20, 48, 'SWISS CAFÉ MENU', 'fm-swiss-caf-menu', '6a0c513f8f747.webp', '', 1, 1, '2026-05-19 01:47:10', '2026-05-19 12:02:07'),
(424, 20, 48, 'SANDWICHES & MORE', 'fm-sandwiches-and-more', '6a0c5155b587c.webp', '', 3, 1, '2026-05-19 01:47:10', '2026-05-19 12:02:29'),
(425, 20, 48, 'SMALL CHOPS', 'fm-small-chops', '6a0c516b30b1e.webp', '', 5, 1, '2026-05-19 01:47:10', '2026-05-19 12:02:51'),
(426, 20, 48, 'DEEP FRIED SERVED WITH A DIP SAUCE OF YOUR CHOICE', 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice', NULL, NULL, 7, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:31'),
(427, 20, 48, 'PEPPERED PROTEINS', 'fm-peppered-proteins', '6a0c518bb0603.png', '', 9, 1, '2026-05-19 01:47:10', '2026-05-19 12:03:23'),
(428, 20, 48, 'HOT STARTER ( NIGERIAN PEPPER SOUP )', 'fm-hot-starter-nigerian-pepper-soup', NULL, NULL, 11, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:31'),
(429, 20, 48, 'SALAD', 'fm-salad', '6a0c51a938b77.webp', '', 13, 1, '2026-05-19 01:47:10', '2026-05-19 12:03:53'),
(430, 20, 48, 'PASTA AND PIZZA', 'fm-pasta-and-pizza', '6a0c51c62bfac.webp', '', 15, 1, '2026-05-19 01:47:10', '2026-05-19 12:04:22'),
(431, 20, 48, 'NIGERIAN DISHES', 'fm-nigerian-dishes', '6a0c51d897ca6.webp', '', 17, 1, '2026-05-19 01:47:10', '2026-05-19 12:04:40'),
(432, 20, 48, 'ASIAN FUSION', 'fm-asian-fusion', NULL, NULL, 19, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:31'),
(433, 20, 48, 'SPECIAL PROTEIN', 'fm-special-protein', NULL, NULL, 21, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:31'),
(434, 20, 48, 'MAMA AFRICA ( NIGERIAN SOUPS )', 'fm-mama-africa-nigerian-soups', NULL, NULL, 23, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:31'),
(435, 20, 48, 'ALL DAY LONG A CHOICE OF DESSERTS OF THE DAY HANDMADE', 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade', NULL, NULL, 25, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:31'),
(436, 20, 48, 'STARTERS & DELIGHTS', 'fm-starters-and-delights', NULL, NULL, 27, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:31'),
(437, 20, 49, 'SOFT DRINKS', 'dm-soft-drinks', '6a0c5206ba164.webp', '', 2, 1, '2026-05-19 01:47:11', '2026-05-19 12:05:26'),
(438, 20, 49, 'SIGNATURE COCKTAILS', 'dm-signature-cocktails', '6a0c521d5382e.webp', '', 4, 1, '2026-05-19 01:47:11', '2026-05-19 12:05:49'),
(439, 20, 49, 'MOCKTAILS', 'dm-mocktails', '6a0c523321bb6.webp', '', 6, 1, '2026-05-19 01:47:11', '2026-05-19 12:06:11'),
(440, 20, 49, 'BEER & MIXES', 'dm-beer-and-mixes', '6a0c5253ab189.png', '', 8, 1, '2026-05-19 01:47:11', '2026-05-19 12:06:43'),
(441, 20, 49, 'WINE SELECTION', 'dm-wine-selection', NULL, NULL, 10, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:31'),
(442, 20, 49, 'SWEET & SPARKLING', 'dm-sweet-and-sparkling', '6a0c527e409a3.webp', '', 12, 1, '2026-05-19 01:47:11', '2026-05-19 12:07:26'),
(443, 20, 49, 'WHISKEY', 'dm-whiskey', NULL, NULL, 14, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:31'),
(444, 20, 49, 'COGNAC & BRANDY', 'dm-cognac-and-brandy', NULL, NULL, 16, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:31'),
(445, 20, 49, 'VODKA', 'dm-vodka', '6a0c529944dc4.webp', '', 18, 1, '2026-05-19 01:47:11', '2026-05-19 12:07:53'),
(446, 20, 49, 'GIN', 'dm-gin', '6a0c52acc83dc.png', '', 20, 1, '2026-05-19 01:47:11', '2026-05-19 12:08:12'),
(447, 20, 49, 'TEQUILA & SHOOTERS', 'dm-tequila-and-shooters', NULL, NULL, 22, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:31'),
(448, 20, 49, 'RUM', 'dm-rum', '6a0c52d44641c.webp', '', 24, 1, '2026-05-19 01:47:11', '2026-05-19 12:08:52'),
(449, 20, 49, 'VERMOUTH & LIQUEURS', 'dm-vermouth-and-liqueurs', '6a0c52c5db8c5.webp', '', 26, 1, '2026-05-19 01:47:11', '2026-05-19 12:08:37'),
(450, 20, 49, 'SMOOTHIES & FRESH JUICES', 'dm-smoothies-and-fresh-juices', NULL, NULL, 28, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:31'),
(451, 13, 50, 'BREAKFAST', 'breakfast', NULL, 'Breakfast is served Monday – Sunday | 6:00 AM – 11:00 AM. To place your order, dial Ext: 000.', 24, 1, '2026-03-13 09:42:21', '2026-05-02 18:46:27'),
(452, 13, 50, 'Breakfast Sides', 'breakfast-sides', NULL, NULL, 27, 1, '2026-03-13 09:42:21', '2026-05-02 18:46:27'),
(453, 13, 50, 'Breakfast Combos', 'breakfast-combos', NULL, NULL, 31, 1, '2026-03-13 09:42:21', '2026-05-02 18:46:27'),
(454, 13, 51, 'AUTHENTIC NIGERIAN CUISINE', 'authentic-nigerian-cuisine', NULL, '', 17, 1, '2026-03-13 09:42:21', '2026-05-02 18:46:27'),
(455, 13, 50, 'Extra Side Dishes', 'extra-side-dishes', NULL, NULL, 40, 1, '2026-03-13 09:42:21', '2026-05-02 18:46:27'),
(456, 13, 50, 'PIZZA & PASTA', 'pizza-pasta', NULL, NULL, 44, 1, '2026-03-13 09:42:21', '2026-05-02 18:46:27'),
(457, 13, 50, 'Appetizers & Salads', 'appetizers-salads', NULL, NULL, 48, 1, '2026-03-13 09:42:21', '2026-05-02 18:46:27'),
(458, 13, 51, 'Soups', 'soups', 'FSMZhvzQ49IB.jpg', NULL, 13, 1, '2026-03-13 09:42:21', '2026-06-21 01:17:13'),
(459, 13, 50, 'Vegetarian Cuisine', 'vegetarian-cuisine', NULL, NULL, 55, 1, '2026-03-13 09:42:21', '2026-05-02 18:46:27'),
(460, 13, 50, 'In A Bun (Burgers)', 'in-a-bun-burgers', NULL, NULL, 59, 1, '2026-03-13 09:42:21', '2026-05-02 18:46:27'),
(461, 13, 51, 'FROM THE GRILL', 'from-the-grill', NULL, '', 18, 1, '2026-03-13 09:42:21', '2026-05-02 18:46:27'),
(462, 13, 50, 'Triple Stack Sandwiches', 'triple-stack-sandwiches', NULL, NULL, 66, 1, '2026-03-13 09:42:21', '2026-05-02 18:46:27'),
(463, 13, 50, 'Kids Menu', 'kids-menu', NULL, NULL, 67, 1, '2026-03-13 09:42:21', '2026-05-02 18:46:27'),
(464, 13, 50, 'Desserts', 'desserts', NULL, NULL, 68, 1, '2026-03-13 09:42:21', '2026-05-02 18:46:27'),
(465, 13, 51, 'Organic Salads & Appetizers', 'organic-salads-appetizers', 'OqEr4xbtGh22.jpg', NULL, 14, 1, '2026-03-13 10:13:02', '2026-06-21 01:14:52'),
(466, 13, 51, 'Vegetarian', 'vegetarian', NULL, NULL, 29, 1, '2026-03-13 10:13:02', '2026-05-02 18:46:27'),
(467, 13, 51, 'Pasta Dishes', 'pasta-dishes', 'E0pjPIutK1VL.jpg', NULL, 15, 1, '2026-03-13 10:13:02', '2026-06-21 01:18:29'),
(468, 13, 51, 'Medium Crust Pizzas', 'medium-crust-pizzas', NULL, NULL, 42, 1, '2026-03-13 10:13:02', '2026-05-02 18:46:27'),
(469, 13, 51, 'Main Courses', 'main-courses', NULL, NULL, 46, 1, '2026-03-13 10:13:02', '2026-05-02 18:46:27'),
(470, 13, 51, 'Poultry', 'poultry', NULL, NULL, 50, 1, '2026-03-13 10:13:02', '2026-05-02 18:46:27'),
(471, 13, 51, 'Seafood & Fish', 'seafood-fish', NULL, '', 21, 1, '2026-03-13 10:13:02', '2026-05-02 18:46:27'),
(472, 13, 51, 'Desserts', 'desserts-a-la-carte', NULL, '', 22, 1, '2026-03-13 10:13:02', '2026-05-02 18:46:27'),
(473, 13, 52, 'Soft Drinks / Water', 'soft-drinks-water', NULL, '', 25, 1, '2026-03-14 17:42:54', '2026-05-02 18:46:27'),
(474, 13, 52, 'Juices', 'juices', NULL, '', 26, 1, '2026-03-14 17:42:54', '2026-05-02 18:46:27'),
(475, 13, 52, 'Energy Drinks', 'energy-drinks', NULL, '', 28, 1, '2026-03-14 17:42:54', '2026-05-02 18:46:27'),
(476, 13, 52, 'Beers', 'beers', NULL, '', 30, 1, '2026-03-14 17:42:54', '2026-05-02 18:46:27'),
(477, 13, 52, 'Aperitif', 'aperitif', NULL, '', 32, 1, '2026-03-14 17:42:54', '2026-05-02 18:46:27'),
(478, 13, 52, 'Gin', 'gin', NULL, '', 34, 1, '2026-03-14 17:42:54', '2026-05-02 18:46:27'),
(479, 13, 52, 'Whisky Single Malt', 'whisky-single-malt', NULL, '', 39, 1, '2026-03-14 17:42:54', '2026-05-02 18:46:27'),
(480, 13, 52, 'Whisky Premium Blend', 'whisky-premium-blend', NULL, '', 41, 1, '2026-03-14 17:42:54', '2026-05-02 18:46:27'),
(481, 13, 52, 'Whisky American Irish', 'whisky-american-irish', NULL, '', 43, 1, '2026-03-14 17:42:54', '2026-05-02 18:46:27'),
(482, 13, 52, 'Vodka', 'vodka', NULL, '', 45, 1, '2026-03-14 17:42:54', '2026-05-02 18:46:27'),
(483, 13, 52, 'Rum', 'rum', NULL, '', 47, 1, '2026-03-14 17:42:54', '2026-05-02 18:46:27'),
(484, 13, 52, 'Cognac', 'cognac', NULL, '', 49, 1, '2026-03-14 17:42:54', '2026-05-02 18:46:27'),
(485, 13, 52, 'Tequila', 'tequila', NULL, '', 51, 1, '2026-03-14 17:42:54', '2026-05-02 18:46:27'),
(486, 13, 52, 'Liquor', 'liquor', NULL, '', 52, 1, '2026-03-14 17:42:54', '2026-05-02 18:46:27'),
(487, 13, 52, 'Hot Beverages', 'hot-beverages', NULL, '', 54, 1, '2026-03-14 17:42:54', '2026-05-02 18:46:27'),
(488, 13, 52, 'White Wine', 'white-wine', NULL, '', 56, 1, '2026-03-14 17:42:54', '2026-05-02 18:46:27'),
(489, 13, 52, 'Red Wine', 'red-wine', NULL, '', 58, 1, '2026-03-14 17:42:54', '2026-05-02 18:46:27'),
(490, 13, 52, 'Rosé Wine', 'rose-wine', NULL, '', 62, 1, '2026-03-14 17:42:54', '2026-05-02 18:46:27'),
(491, 13, 52, 'Champagne', 'champagne', NULL, '', 63, 1, '2026-03-14 17:42:54', '2026-05-02 18:46:27'),
(492, 13, 51, 'Pizza', 'pizza', 'GCboYDr2pz65.jpg', NULL, 16, 1, '2026-03-23 13:54:19', '2026-06-21 01:20:39'),
(493, 13, 51, 'SANDWICH', 's', NULL, '', 19, 1, '2026-03-26 09:39:18', '2026-05-02 18:46:27'),
(494, 13, 51, 'BURGER', 'b', NULL, '', 20, 1, '2026-03-26 09:41:28', '2026-05-02 18:46:27'),
(495, 13, 52, 'HERBAL TEA', 'h', NULL, '', 53, 1, '2026-04-02 13:51:30', '2026-05-02 18:46:27'),
(496, 13, 52, 'Classic Cocktail', 'c', NULL, '', 64, 1, '2026-04-22 14:35:50', '2026-05-02 18:46:27'),
(497, 13, 52, 'Classic Mocktail', 'x', NULL, '', 65, 1, '2026-04-22 16:01:06', '2026-05-02 18:46:27'),
(498, 13, 52, 'Non Alcoholic Wine', 'n', NULL, '', 57, 1, '2026-04-25 12:47:45', '2026-05-02 18:46:27'),
(499, 25, 53, 'Lord of the Wings', 'wm-lord', '6a09d433c5bf6.webp', '', 1, 1, '2026-05-14 22:48:10', '2026-05-17 14:44:03'),
(500, 25, 53, 'Waffle combos', 'wm-waffle', '6a09dbf7ccb81.webp', '', 4, 1, '2026-05-14 22:48:10', '2026-05-17 15:17:11'),
(501, 25, 53, 'Wings on fire challenge (poppers)', 'wm-poppers', NULL, NULL, 7, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(502, 25, 53, 'Choose your flavor', 'wm-flavors', NULL, NULL, 10, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(503, 25, 53, 'Choose your dip', 'wm-dips', NULL, NULL, 13, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(504, 25, 53, 'Sides', 'wm-sides', NULL, NULL, 17, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(505, 25, 53, 'Combo deals', 'wm-combo', NULL, NULL, 20, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(506, 25, 53, 'Kids zone meals', 'wm-kids', NULL, NULL, 23, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(507, 25, 53, 'Sweet treats', 'wm-sweets', NULL, NULL, 26, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(508, 25, 53, 'Wings on fire challenge', 'wm-challenge', NULL, NULL, 28, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(509, 25, 54, 'Breakfast', 'mb-breakfast', NULL, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(510, 25, 54, 'Starter', 'mb-starters', NULL, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(511, 25, 54, 'Soups', 'mb-soups', NULL, NULL, 8, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(512, 25, 54, 'Main dish', 'mb-mains', NULL, NULL, 11, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(513, 25, 54, 'Champagne & wines', 'mb-wines', NULL, NULL, 14, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(514, 25, 55, 'Munchies & plates', 'hm-munch', NULL, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(515, 25, 55, 'Fish & rice mains', 'hm-fish', NULL, NULL, 6, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(516, 25, 55, 'Sides', 'hm-sides', NULL, NULL, 9, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(517, 25, 55, 'Sweets', 'hm-sweets', NULL, NULL, 12, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(518, 25, 55, 'Soft drinks (shared)', 'shared-soft-drinks', NULL, NULL, 15, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(519, 25, 55, 'Juices (shared)', 'shared-juices', NULL, NULL, 18, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(520, 25, 55, 'Milkshakes (shared)', 'shared-milkshakes', NULL, NULL, 21, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(521, 25, 55, 'Smoothies (shared)', 'shared-smoothies', NULL, NULL, 24, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(522, 25, 55, 'Champagne', 'hm-champagne', NULL, NULL, 16, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(523, 25, 55, 'Whiskey', 'hm-whiskey', NULL, NULL, 19, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(524, 25, 55, 'Cognac', 'hm-cognac', NULL, NULL, 22, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(525, 25, 55, 'Gin', 'hm-gin', NULL, NULL, 25, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(526, 25, 55, 'Vodka', 'hm-vodka', NULL, NULL, 27, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(527, 25, 55, 'Tequila', 'hm-tequila', NULL, NULL, 29, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(528, 25, 55, 'Beer', 'hm-beer', NULL, NULL, 30, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(529, 25, 55, 'White wine', 'hm-white', NULL, NULL, 31, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(530, 25, 55, 'Rosé wine', 'hm-rose', NULL, NULL, 32, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(531, 25, 55, 'Red wine', 'hm-red', NULL, NULL, 33, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(532, 25, 55, 'Cocktails', 'hm-cocktails', NULL, NULL, 34, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(533, 25, 55, 'Mocktails', 'hm-mocktails', NULL, NULL, 35, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(534, 25, 55, 'Smoothies', 'hm-smooth', NULL, NULL, 36, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:34'),
(535, 3, 58, 'Soft Drinks & Non-Alcoholic', 'soft-drinks-non-alcoholic', NULL, 'Refreshing non-alcoholic beverages', 16, 1, '2026-02-13 09:52:41', '2026-03-13 02:33:07'),
(536, 3, 58, 'Beer & Cider', 'beer-cider', NULL, 'Local and imported beers and ciders', 17, 1, '2026-02-13 09:52:41', '2026-03-13 02:32:57'),
(537, 3, 58, 'Brandy & Cognac', 'brandy-cognac', NULL, 'Premium brandy and cognac selection', 18, 1, '2026-02-13 09:52:41', '2026-03-13 02:32:48'),
(538, 3, 58, 'Whiskey', 'whiskey', NULL, 'Fine whiskey collection', 19, 1, '2026-02-13 09:52:41', '2026-03-13 02:32:38'),
(539, 3, 58, 'Rum', 'rum', NULL, 'Rum selection', 20, 1, '2026-02-13 09:52:41', '2026-03-13 02:32:29'),
(540, 3, 58, 'Vodka', 'vodka', NULL, 'Vodka selection', 21, 1, '2026-02-13 09:52:41', '2026-03-13 02:32:20'),
(541, 3, 58, 'Gin', 'gin', NULL, 'Premium gin selection', 22, 1, '2026-02-13 09:52:41', '2026-03-13 02:32:09'),
(542, 3, 58, 'Tequila', 'tequila', NULL, 'Tequila selection', 23, 1, '2026-02-13 09:52:41', '2026-03-13 02:31:57'),
(543, 3, 58, 'Liqueurs', 'liqueurs', NULL, 'Sweet liqueurs and digestifs', 24, 1, '2026-02-13 09:52:41', '2026-03-13 02:31:46'),
(544, 3, 58, 'Aperitifs & Bitters', 'aperitifs-bitters', NULL, 'Aperitifs and bitters', 25, 1, '2026-02-13 09:52:41', '2026-03-13 02:31:36'),
(545, 3, 58, 'Champagne', 'champagne', NULL, 'Premium champagne selection', 26, 1, '2026-02-13 09:52:41', '2026-03-13 02:31:26'),
(546, 3, 58, 'Mocktails', 'mocktails', NULL, 'Alcohol-free cocktails', 27, 1, '2026-02-13 09:52:41', '2026-03-13 02:31:16'),
(547, 3, 58, 'Cocktails', 'cocktails', NULL, 'Classic and signature cocktails', 28, 1, '2026-02-13 09:52:41', '2026-03-13 02:31:06'),
(548, 3, 58, 'White Wines', 'white-wines', NULL, 'White wine selection', 29, 1, '2026-02-13 09:52:41', '2026-03-13 02:30:54'),
(549, 3, 58, 'Red Wines', 'red-wines', NULL, 'Red wine selection', 30, 1, '2026-02-13 09:52:41', '2026-03-13 02:30:31'),
(550, 3, 58, 'Coffee', 'coffee', NULL, 'Hot coffee drinks', 31, 1, '2026-02-13 09:52:41', '2026-03-13 02:30:42'),
(551, 3, 58, 'Smoothies', 'smoothies', NULL, 'Fresh fruit smoothies', 32, 1, '2026-02-13 09:52:41', '2026-03-13 02:29:56'),
(552, 3, 58, 'Fresh Juices', 'fresh-juices', NULL, 'Freshly squeezed juices', 33, 1, '2026-02-13 09:52:41', '2026-03-13 02:29:40'),
(553, 3, 57, 'Breakfast Trays (48-Hour Pre-Order)', 'breakfast-trays-48hr', NULL, 'Premium breakfast trays for pre-order', 1, 1, '2026-02-13 10:57:52', '2026-03-13 02:27:49'),
(554, 3, 57, 'Breakfast', 'breakfast', NULL, 'Morning meals', 2, 1, '2026-02-13 10:57:52', '2026-03-13 02:28:00'),
(555, 3, 57, 'Salads', 'salads', NULL, 'Fresh salads', 3, 1, '2026-02-13 10:57:52', '2026-03-13 02:28:09'),
(556, 3, 57, 'Pepper Soups & Continental Soups', 'pepper-soups-continental-soups', NULL, 'Served with fresh bread rolls', 4, 1, '2026-02-13 10:57:52', '2026-03-13 02:28:18'),
(557, 3, 57, 'Finger Foods & Small Chops', 'finger-foods-small-chops', NULL, 'Appetizers and small bites', 5, 1, '2026-02-13 10:57:52', '2026-03-13 02:28:33'),
(558, 3, 57, 'Sandwiches & Burgers', 'sandwiches-burgers', NULL, 'Sandwiches and burgers', 6, 1, '2026-02-13 10:57:52', '2026-03-13 02:28:43'),
(559, 3, 57, 'Chicken Entrées', 'chicken-entrees', NULL, 'Served with choice of fries, roast potatoes, sweet potato fries, or yam fries', 7, 1, '2026-02-13 10:57:52', '2026-03-13 02:28:52'),
(560, 3, 57, 'GRILLS', 'seafood', NULL, 'Fresh seafood dishes', 8, 1, '2026-02-13 10:57:52', '2026-05-20 14:53:50'),
(561, 3, 57, 'Steaks, Ribs & Chops', 'steaks-ribs-chops', NULL, 'South African cuts — served with side of choice', 9, 1, '2026-02-13 10:57:52', '2026-03-13 02:29:11'),
(562, 3, 57, 'Grills', 'grills', NULL, 'Grilled specialties', 10, 1, '2026-02-13 10:57:52', '2026-03-13 02:29:22'),
(563, 3, 57, 'Platters', 'platters', NULL, 'Sharing platters', 11, 1, '2026-02-13 10:57:52', '2026-03-13 02:33:58'),
(564, 3, 57, 'Pasta', 'pasta', NULL, 'Pasta dishes', 12, 1, '2026-02-13 10:57:52', '2026-03-13 02:33:49'),
(565, 3, 57, 'Naija Soups', 'naija-soups', NULL, 'Served with semovita, eba, or pounded yam — protein choice included', 13, 1, '2026-02-13 10:57:52', '2026-03-13 02:33:36'),
(566, 3, 57, 'Naija Specialties', 'naija-specialties', NULL, 'Nigerian specialties', 14, 1, '2026-02-13 10:57:52', '2026-03-13 02:33:26'),
(567, 3, 57, 'Sides', 'sides', NULL, 'Side dishes', 15, 1, '2026-02-13 10:57:52', '2026-03-13 02:33:18'),
(568, 27, 59, 'SOUPS', 'mm-soups', NULL, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(569, 27, 59, 'APPETIZERS', 'mm-appetizers', NULL, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(570, 27, 59, 'SALADS', 'mm-salads', NULL, NULL, 7, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(571, 27, 59, 'PASTA', 'mm-pasta', NULL, NULL, 10, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(572, 27, 59, 'FISH AND SEAFOOD', 'mm-fish-and-seafood', NULL, NULL, 13, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(573, 27, 59, 'MEAT', 'mm-meat', NULL, NULL, 16, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(574, 27, 59, 'CHICKEN', 'mm-chicken', NULL, NULL, 19, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(575, 27, 59, 'BURGER', 'mm-burger', NULL, NULL, 22, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(576, 27, 59, 'PIZZA', 'mm-pizza', NULL, NULL, 25, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(577, 27, 59, 'DESSERT', 'mm-dessert', NULL, NULL, 28, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(578, 27, 60, 'BREAKFAST', 'ac-breakfast', NULL, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(579, 27, 60, 'SOUPS', 'ac-soups', NULL, NULL, 5, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(580, 27, 60, 'BEEF DISHES', 'ac-beef-dishes', NULL, NULL, 8, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(581, 27, 60, 'SEAFOOD DISHES', 'ac-seafood-dishes', NULL, NULL, 11, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(582, 27, 60, 'CHICKEN DISHES', 'ac-chicken-dishes', NULL, NULL, 14, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(583, 27, 60, 'SANDWICHES', 'ac-sandwiches', NULL, NULL, 17, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(584, 27, 60, 'SIDE ORDERS', 'ac-side-orders', NULL, NULL, 20, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(585, 27, 60, 'SWALLOWS', 'ac-swallows', NULL, NULL, 23, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(586, 27, 60, 'PANCAKES', 'ac-pancakes', NULL, NULL, 26, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(587, 27, 60, 'WAFFLES', 'ac-waffles', NULL, NULL, 29, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(588, 27, 60, 'NIGERIAN PASTRIES', 'ac-nigerian-pastries', NULL, NULL, 31, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(589, 27, 60, 'COLD SANDWICHES', 'ac-cold-sandwiches', NULL, NULL, 33, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(590, 27, 60, 'PANINI', 'ac-panini', NULL, NULL, 35, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(591, 27, 60, 'HOT SANDWICHES', 'ac-hot-sandwiches', NULL, NULL, 37, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(592, 27, 60, 'APPETIZERS', 'ac-appetizers', NULL, NULL, 39, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(593, 27, 60, 'SALADS', 'ac-salads', NULL, NULL, 41, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(594, 27, 60, 'DESSERT', 'ac-dessert', NULL, NULL, 42, 1, '2026-05-19 13:18:05', '2026-05-19 13:47:16'),
(595, 27, 61, 'CHAMPAGNE', 'tb-champagne', NULL, NULL, 3, 1, '2026-05-19 13:18:06', '2026-05-19 13:47:16'),
(596, 27, 61, 'TEQUILA', 'tb-tequila', NULL, NULL, 6, 1, '2026-05-19 13:18:06', '2026-05-19 13:47:16'),
(597, 27, 61, 'COGNAC', 'tb-cognac', NULL, NULL, 9, 1, '2026-05-19 13:18:06', '2026-05-19 13:47:16'),
(598, 27, 61, 'VODKA', 'tb-vodka', NULL, NULL, 12, 1, '2026-05-19 13:18:06', '2026-05-19 13:47:16'),
(599, 27, 61, 'GIN', 'tb-gin', NULL, NULL, 15, 1, '2026-05-19 13:18:06', '2026-05-19 13:47:16'),
(600, 27, 61, 'RUM', 'tb-rum', NULL, NULL, 18, 1, '2026-05-19 13:18:06', '2026-05-19 13:47:16'),
(601, 27, 61, 'WHISKY', 'tb-whisky', NULL, NULL, 21, 1, '2026-05-19 13:18:06', '2026-05-19 13:47:16'),
(602, 27, 61, 'BEER', 'tb-beer', NULL, NULL, 24, 1, '2026-05-19 13:18:06', '2026-05-19 13:47:16'),
(603, 27, 61, 'Non Alcohol drinks', 'tb-non-alcohol-drinks', NULL, NULL, 27, 1, '2026-05-19 13:18:06', '2026-05-19 13:47:16'),
(604, 27, 61, 'Juice Pitcher', 'tb-juice-pitcher', NULL, NULL, 30, 1, '2026-05-19 13:18:06', '2026-05-19 13:47:16'),
(605, 27, 61, 'Hot Beverages', 'tb-hot-beverages', NULL, NULL, 32, 1, '2026-05-19 13:18:06', '2026-05-19 13:47:16'),
(606, 27, 61, 'CLASSIC COCKTAILS', 'tb-classic-cocktails', NULL, NULL, 34, 1, '2026-05-19 13:18:06', '2026-05-19 13:47:16'),
(607, 27, 61, 'SIGNATURE COCKTAILS', 'tb-signature-cocktails', NULL, NULL, 36, 1, '2026-05-19 13:18:06', '2026-05-19 13:47:16'),
(608, 27, 61, 'SHOTS', 'tb-shots', NULL, NULL, 38, 1, '2026-05-19 13:18:06', '2026-05-19 13:47:16'),
(609, 27, 61, 'SPECIAL SHOTS', 'tb-special-shots', NULL, NULL, 40, 1, '2026-05-19 13:18:06', '2026-05-19 13:47:16'),
(610, 21, 35, 'Extra', 'extra', NULL, NULL, 23, 1, '2026-07-18 16:30:18', '2026-07-18 16:30:36'),
(611, 21, 35, 'Noodles/Pasta', 'noodlespasta', NULL, NULL, 24, 1, '2026-07-18 16:34:55', '2026-07-30 00:36:04'),
(612, 21, 37, 'Shawarma', 'shawarma', NULL, NULL, 25, 1, '2026-07-18 16:44:00', '2026-07-18 16:54:42'),
(613, 21, 35, 'Platters', 'platters', NULL, NULL, 26, 1, '2026-07-18 16:55:32', '2026-07-30 01:32:15'),
(614, 30, 62, 'Burger', 'burger', NULL, NULL, 1, 1, '2026-07-28 20:51:26', '2026-07-28 20:51:26'),
(615, 30, 62, 'Burger', 'burger-nXRm', NULL, NULL, 1, 1, '2026-07-28 20:51:27', '2026-07-28 20:51:27'),
(616, 21, 35, 'Breakfast', 'breakfast', NULL, NULL, 27, 1, '2026-07-29 19:06:55', '2026-07-29 19:06:55'),
(617, 21, 35, 'Light Wakes Breakfast', 'light-wakes-breakfast', NULL, NULL, 28, 1, '2026-07-29 19:42:09', '2026-07-29 19:51:22'),
(618, 21, 37, 'Starter', 'starter', NULL, NULL, 29, 1, '2026-07-29 20:02:53', '2026-07-29 20:02:53'),
(619, 21, 35, 'Ellipse Pepper Soup Specials', 'ellipse-pepper-soup-specials', NULL, NULL, 30, 1, '2026-07-29 21:07:49', '2026-07-29 21:07:49'),
(620, 21, 35, 'Choice of Rice', 'choice-of-rice', NULL, NULL, 31, 1, '2026-07-29 22:33:50', '2026-07-29 22:33:50'),
(621, 21, 35, 'Nigerian Soup', 'nigerian-soup', NULL, NULL, 32, 1, '2026-07-30 00:06:13', '2026-07-30 00:06:13'),
(622, 21, 35, 'Nigerian Dishes', 'nigerian-dishes', NULL, NULL, 33, 1, '2026-07-30 00:27:22', '2026-07-30 00:27:22'),
(623, 21, 37, 'Continental Salad and Pastries', 'continental-salad-and-pastries', NULL, NULL, 34, 1, '2026-07-30 01:11:02', '2026-07-30 01:23:44'),
(624, 32, 63, 'Food', 'food', NULL, NULL, 0, 1, '2026-08-14 16:55:14', '2026-08-14 16:55:14'),
(625, 34, 66, 'Main Dish', 'main-dish', NULL, NULL, 1, 1, '2026-08-20 17:05:19', '2026-08-20 17:05:19'),
(626, 34, 65, 'Grills', 'grills', NULL, NULL, 2, 1, '2026-08-20 17:06:33', '2026-08-20 17:06:33');

-- --------------------------------------------------------

--
-- Table structure for table `category_secondary_sections`
--

CREATE TABLE `category_secondary_sections` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_secondary_sections`
--

INSERT INTO `category_secondary_sections` (`id`, `category_id`, `section_id`, `is_active`, `created_at`, `updated_at`) VALUES
(48, 473, 51, 1, '2026-03-20 13:07:23', '2026-03-20 13:07:23'),
(49, 473, 50, 1, '2026-03-20 13:07:23', '2026-03-20 13:07:23'),
(50, 474, 51, 1, '2026-03-20 13:20:34', '2026-03-20 13:20:34'),
(51, 474, 50, 1, '2026-03-20 13:20:34', '2026-03-20 13:20:34'),
(52, 475, 51, 1, '2026-03-20 13:20:47', '2026-03-20 13:20:47'),
(53, 475, 50, 1, '2026-03-20 13:20:47', '2026-03-20 13:20:47'),
(54, 476, 51, 1, '2026-03-20 13:20:58', '2026-03-20 13:20:58'),
(55, 476, 50, 1, '2026-03-20 13:20:58', '2026-03-20 13:20:58'),
(56, 477, 51, 1, '2026-03-20 13:21:10', '2026-03-20 13:21:10'),
(57, 477, 50, 1, '2026-03-20 13:21:10', '2026-03-20 13:21:10'),
(58, 478, 51, 1, '2026-03-20 13:21:22', '2026-03-20 13:21:22'),
(59, 478, 50, 1, '2026-03-20 13:21:22', '2026-03-20 13:21:22'),
(60, 479, 51, 1, '2026-03-20 13:22:00', '2026-03-20 13:22:00'),
(61, 479, 50, 1, '2026-03-20 13:22:00', '2026-03-20 13:22:00'),
(62, 480, 51, 1, '2026-03-20 13:22:12', '2026-03-20 13:22:12'),
(63, 480, 50, 1, '2026-03-20 13:22:12', '2026-03-20 13:22:12'),
(64, 481, 51, 1, '2026-03-20 13:22:59', '2026-03-20 13:22:59'),
(65, 481, 50, 1, '2026-03-20 13:22:59', '2026-03-20 13:22:59'),
(66, 482, 51, 1, '2026-03-20 13:23:12', '2026-03-20 13:23:12'),
(67, 482, 50, 1, '2026-03-20 13:23:12', '2026-03-20 13:23:12'),
(68, 483, 51, 1, '2026-03-20 13:23:25', '2026-03-20 13:23:25'),
(69, 483, 50, 1, '2026-03-20 13:23:25', '2026-03-20 13:23:25'),
(70, 484, 51, 1, '2026-03-20 13:23:35', '2026-03-20 13:23:35'),
(71, 484, 50, 1, '2026-03-20 13:23:35', '2026-03-20 13:23:35'),
(72, 485, 51, 1, '2026-03-20 13:23:49', '2026-03-20 13:23:49'),
(73, 485, 50, 1, '2026-03-20 13:23:49', '2026-03-20 13:23:49'),
(74, 486, 51, 1, '2026-03-20 13:24:01', '2026-03-20 13:24:01'),
(75, 486, 50, 1, '2026-03-20 13:24:01', '2026-03-20 13:24:01'),
(76, 487, 51, 1, '2026-03-20 13:24:13', '2026-03-20 13:24:13'),
(77, 487, 50, 1, '2026-03-20 13:24:13', '2026-03-20 13:24:13'),
(78, 488, 51, 1, '2026-03-20 13:24:24', '2026-03-20 13:24:24'),
(79, 488, 50, 1, '2026-03-20 13:24:24', '2026-03-20 13:24:24'),
(80, 489, 51, 1, '2026-03-20 13:24:40', '2026-03-20 13:24:40'),
(81, 489, 50, 1, '2026-03-20 13:24:40', '2026-03-20 13:24:40'),
(82, 491, 51, 1, '2026-03-20 13:24:55', '2026-03-20 13:24:55'),
(83, 491, 50, 1, '2026-03-20 13:24:55', '2026-03-20 13:24:55'),
(84, 490, 51, 1, '2026-03-20 13:25:06', '2026-03-20 13:25:06'),
(85, 490, 50, 1, '2026-03-20 13:25:06', '2026-03-20 13:25:06'),
(86, 454, 50, 1, '2026-03-27 13:25:06', '2026-03-27 13:25:06'),
(87, 461, 50, 1, '2026-03-27 13:25:39', '2026-03-27 13:25:39'),
(88, 518, 53, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(89, 519, 53, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(90, 520, 53, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(91, 521, 53, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(93, 612, 35, 1, '2026-07-18 16:54:42', '2026-07-18 16:54:42');

-- --------------------------------------------------------

--
-- Table structure for table `customization_settings`
--

CREATE TABLE `customization_settings` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL DEFAULT 1,
  `menu_title_color` varchar(7) DEFAULT '#000000',
  `menu_title_size` int(11) DEFAULT 24,
  `menu_title_font` varchar(100) DEFAULT 'Inter',
  `price_color` varchar(7) DEFAULT '#000000',
  `price_size` int(11) DEFAULT 18,
  `price_font` varchar(100) DEFAULT 'Inter',
  `description_color` varchar(7) DEFAULT '#666666',
  `description_size` int(11) DEFAULT 14,
  `description_font` varchar(100) DEFAULT 'Inter',
  `category_title_color` varchar(7) DEFAULT '#000000',
  `category_title_size` int(11) DEFAULT 20,
  `category_title_font` varchar(100) DEFAULT 'Inter',
  `background_color` varchar(7) DEFAULT '#FFFFFF',
  `header_background_color` varchar(7) DEFAULT '#FFFFFF',
  `primary_color` varchar(7) DEFAULT '#111111',
  `secondary_color` varchar(7) DEFAULT '#FFFFFF',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customization_settings`
--

INSERT INTO `customization_settings` (`id`, `restaurant_id`, `template_id`, `menu_title_color`, `menu_title_size`, `menu_title_font`, `price_color`, `price_size`, `price_font`, `description_color`, `description_size`, `description_font`, `category_title_color`, `category_title_size`, `category_title_font`, `background_color`, `header_background_color`, `primary_color`, `secondary_color`, `created_at`, `updated_at`) VALUES
(31, 21, 1, '#000000', 24, 'Inter', '#000000', 18, 'Inter', '#666666', 14, 'Inter', '#000000', 20, 'Inter', '#FFFFFF', '#FFFFFF', '#111111', '#FFFFFF', '2026-05-09 17:25:57', '2026-05-09 17:25:57'),
(32, 2, 18, '#000000', 24, 'Inter', '#000000', 18, 'Inter', '#666666', 14, 'Inter', '#000000', 20, 'Inter', '#FFFFFF', '#FFFFFF', '#111111', '#FFFFFF', '2025-12-19 18:43:25', '2026-03-13 01:58:22'),
(33, 4, 1, '#000000', 24, 'Inter', '#000000', 18, 'Inter', '#666666', 14, 'Inter', '#000000', 20, 'Inter', '#FFFFFF', '#FFFFFF', '#111111', '#FFFFFF', '2026-03-03 23:30:50', '2026-03-03 23:30:50'),
(34, 19, 1, '#000000', 24, 'Inter', '#000000', 18, 'Inter', '#666666', 14, 'Inter', '#000000', 20, 'Inter', '#FFFFFF', '#FFFFFF', '#111111', '#FFFFFF', '2026-04-06 17:01:14', '2026-04-06 17:01:14'),
(35, 19, 4, '#121212', 24, 'Inter', '#0e0a66', 18, 'Inter', '#666666', 14, 'Inter', '#ffffff', 20, 'Inter', '#f8f5f5', '#121212', '#0e0a66', '#ffffff', '2026-05-19 00:12:53', '2026-05-19 00:46:43'),
(36, 26, 1, '#000000', 24, 'Inter', '#000000', 18, 'Inter', '#666666', 14, 'Inter', '#000000', 20, 'Inter', '#FFFFFF', '#FFFFFF', '#111111', '#FFFFFF', '2026-05-17 21:04:00', '2026-05-17 21:04:00'),
(37, 20, 1, '#000000', 24, 'Inter', '#000000', 18, 'Inter', '#666666', 14, 'Inter', '#000000', 20, 'Inter', '#FFFFFF', '#FFFFFF', '#111111', '#FFFFFF', '2026-04-28 20:07:22', '2026-04-28 20:07:22'),
(38, 20, 4, '#121212', 24, 'Inter', '#9a3a3c', 18, 'Inter', '#666666', 14, 'Inter', '#ffffff', 20, 'Inter', '#f8f5f5', '#121212', '#9a3a3c', '#ffffff', '2026-05-19 12:00:11', '2026-05-19 12:00:28'),
(39, 13, 1, '#000000', 24, 'Inter', '#000000', 18, 'Inter', '#666666', 14, 'Inter', '#000000', 20, 'Inter', '#FFFFFF', '#FFFFFF', '#111111', '#FFFFFF', '2026-03-12 23:11:27', '2026-03-12 23:11:27'),
(40, 25, 1, '#000000', 24, 'Inter', '#000000', 18, 'Inter', '#666666', 14, 'Inter', '#000000', 20, 'Inter', '#FFFFFF', '#FFFFFF', '#111111', '#FFFFFF', '2026-05-14 14:20:15', '2026-05-14 14:20:15'),
(41, 3, 6, '#121212', 24, 'Inter', '#1c1c1c', 18, 'Inter', '#666666', 14, 'Inter', '#121212', 20, 'Inter', '#121212', '#121212', '#84ab3e', '#ffffff', '2026-02-13 09:12:56', '2026-03-13 09:42:21'),
(42, 27, 1, '#000000', 24, 'Inter', '#000000', 18, 'Inter', '#666666', 14, 'Inter', '#000000', 20, 'Inter', '#FFFFFF', '#FFFFFF', '#111111', '#FFFFFF', '2026-05-19 12:18:11', '2026-05-19 12:18:11'),
(43, 3, 4, '#121212', 24, 'Inter', '#f20d0d', 18, 'Inter', '#666666', 14, 'Inter', '#ffffff', 20, 'Inter', '#f8f5f5', '#121212', '#f20d0d', '#ffffff', '2026-06-09 19:11:25', '2026-06-09 19:11:25'),
(44, 13, 4, '#121212', 24, 'Inter', '#f20d0d', 18, 'Inter', '#666666', 14, 'Inter', '#ffffff', 20, 'Inter', '#f8f5f5', '#121212', '#f20d0d', '#ffffff', '2026-06-17 14:49:09', '2026-06-17 14:49:09');

-- --------------------------------------------------------

--
-- Table structure for table `email_delivery_suppressions`
--

CREATE TABLE `email_delivery_suppressions` (
  `id` int(11) NOT NULL,
  `email_sha256` char(64) NOT NULL,
  `reason` varchar(64) NOT NULL DEFAULT 'hard_bounce',
  `source` varchar(64) NOT NULL DEFAULT 'manual',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `identifier` varchar(255) NOT NULL DEFAULT '',
  `attempted_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `ip_address`, `identifier`, `attempted_at`) VALUES
(54, '98.97.79.11', 'ellipsehotelslagos@gmail.com', '2026-05-15 09:30:02');

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`id`, `username`, `email`, `password_hash`, `restaurant_id`, `created_at`, `updated_at`) VALUES
(2, 'lava_manager', 'jamesamaila07@gmail.com', '$2y$10$h0RdJU4tRyPL1Gi9vi6slOR6UT6G4pbO8JjCGP7z/11CeK6AzzdDK', 2, '2025-12-19 18:43:07', '2025-12-24 03:18:32'),
(3, 'heviewotelekki_manager', 'reservations@theviewlekki.com', '$2y$10$it3gLTDg5Xs66JtBM9XPs./c.WWAdxbEfbYOkKStFAe2RrdJEeCwa', 3, '2026-02-13 08:57:39', '2026-02-13 08:57:39'),
(4, 'Nostalgia', 'admin@nostalgia.our-menu.online', '$2y$10$6RzEqDr3dsF//RAixtQfTu.pwixF38Miqt/bf1FNp9db8YnSPKkRy', 4, '2026-03-03 23:30:50', '2026-05-12 23:35:50'),
(13, 'heussoestaurant_manager', 'restaurant@lussohotelsabuja.com', '$2y$10$Fh0gC2vv/1u0mPAm9AAO6OAQ1xc3vrLNArRu1ZSbt7b376rcyCQby', 13, '2026-03-12 23:11:27', '2026-03-12 23:11:27'),
(19, 'restaurant_manager', 'opallagos1@gmail.com', '$2y$10$Xphcs0RXSvyVRDfGe9Gdv.4lDgdgVqZLPks/Y8c50y2HouJmT9bOG', 19, '2026-04-06 17:01:14', '2026-05-19 00:01:02'),
(20, 'wissheistana_manager', 'it.vistana@swissinternationalhotels.com', '$2y$10$RcwHYqJDbOIucFoQoFlGMO.gyzuQ0WCnxzO.pC4qRO1gudZMKZivq', 20, '2026-04-28 20:07:22', '2026-05-19 01:47:56'),
(21, 'Ellipse_Hotels', 'ellipsehotelslagos@gmail.com', '$2y$10$qf44.BDvK7sAzPEg2tdeUegT3H/4xcDCCBgLw96z1AGtK6bxftR3i', 21, '2026-05-09 17:25:57', '2026-05-15 09:34:18'),
(25, 'heaniaouse_manager', 'admin@maniahouse.our-menu.online', '$2y$10$vJ/8ehXrESneVUwPZ8gFwOdp1J/pJCSnQRzdxlUlUkykm8WDXGfuC', 25, '2026-05-14 14:20:15', '2026-05-14 14:20:15'),
(26, 'altndocial_manager', 'admin@saltandsocial.our-menu.online', '$2y$10$vWJ4NB1SF5adtaMK9ZAjy.2YAA49kB2UN2GPB1W2FKsnRsY/KxPym', 26, '2026-05-17 21:04:00', '2026-05-17 21:04:00'),
(27, 'endomeafesenu_manager', 'admin@vendomecafe.our-menu.online', '$2y$10$Fb87bH29PEgcKxMEN9zmeulJXZEZNGeMEYXUgEhe6Q5SbwlEPTfS6', 27, '2026-05-19 12:18:11', '2026-05-19 12:18:11'),
(40, 'AloAlo', 'reservations@vcphotels.com', '$2y$12$gzK6H3OTWH6FjJGHBVa1IeoI2OlH2A6RtFQUmDoBU51ZKdDvphUmm', 28, '2026-06-08 12:22:12', '2026-06-08 12:22:12'),
(41, 'Hello92', 'marketingbyjoshua@gmail.com', '$2y$12$1N6HIRAFAvvfFTjSBjG8lOFpC/a6Rt9k3BHYmMfi6NtUbYxgTpd72', 29, '2026-06-23 04:52:31', '2026-06-23 04:52:31'),
(42, 'Faisal', 'hmdfamily96@gmail.com', '$2y$12$uTmf0IhCyJTDoP43v63VY.FThF/XiQlz1HTFLMcWmyMQN1/oYWs5a', 30, '2026-07-28 20:30:53', '2026-07-28 20:30:53'),
(43, 'Ore', 'funbites.byore@gmail.com', '$2y$12$59AYlIOs0VGdO95DHHPMgO0l8zKjl8shcxU0NcefeItbxPA.7H0Ua', 31, '2026-08-11 21:46:21', '2026-08-11 21:46:21'),
(44, 'Ibile', 'IbileMoinmoin@gmail.com', '$2y$12$p1Y/Vz83HB/2warvSuwA/euuElKvSak0A7sVUn6Q3bRWsXnbl/REq', 32, '2026-08-14 15:00:05', '2026-08-14 15:00:05'),
(45, 'nzcorner', 'zaqzaq244341@gmail.com', '$2y$12$HQZ5g3/AVRjXv6CDfcp2mOAAvfmRhs.gqKK0NgDVcacEwxo3S35xS', 33, '2026-08-16 06:27:56', '2026-08-16 06:27:56'),
(46, 'Eddie007', 'eddyjohnny007@gmail.com', '$2y$12$Zxwkwlt/li5XJs9F9GIZd.uBxGBnIfwrALuPQ8ZQVfhDgBAuZqE02', 34, '2026-08-17 14:03:42', '2026-08-17 14:03:42'),
(47, 'KayLex Spot', 'kaylexspot1@gmail.com', '$2y$12$Fku1iOd4fAK/4JNRe5DYZu7svK7QJJYfcZam4i89Aafl.MpKMEdLO', 35, '2026-08-19 02:10:12', '2026-08-19 02:10:12'),
(48, 'Ayobistro', 'ayodhee61@gmail.com', '$2y$12$9lNmbaSX.xVw82lYlx1gHOru3CRioOTlR0MDZrhqzyRcyCURW08BK', 36, '2026-09-06 04:45:56', '2026-09-06 04:45:56'),
(49, 'Gibson', 'gibsonemordi2020@gmail.com', '$2y$12$wg1sDmV4HMPI5h96XpuVMuEh9vg7wxEtW2zoq8iOeJwAVRdgKjlge', 37, '2026-09-07 09:53:51', '2026-09-07 09:53:51');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `restaurant_id`, `category_id`, `name`, `slug`, `description`, `price`, `image`, `display_order`, `is_available`, `created_at`, `updated_at`) VALUES
(2405, 21, 303, 'Hennessy VSOP', 'hennessy-vsop', NULL, 170000.00, '6a10a23130657.webp', 1, 0, '2026-05-19 17:14:14', '2026-08-14 16:22:59'),
(2406, 21, 303, 'Hennessy VS Big', 'h-2', '', 105000.00, '6a10a2575ac6d.jpg', 2, 1, '2026-05-19 17:15:16', '2026-05-22 18:37:11'),
(2407, 21, 303, 'Remy Martin VSOP', 'r', '', 150000.00, '6a10a2b422f9f.jpeg', 3, 1, '2026-05-19 17:16:45', '2026-05-22 18:38:44'),
(2408, 21, 303, 'Martel VS', 'm', '', 100000.00, '6a10a2f50373c.jpeg', 4, 1, '2026-05-19 17:20:23', '2026-05-22 18:39:49'),
(2409, 21, 303, 'Martel Blue Swift', 'm-2', '', 170000.00, '6a10a335e03f9.jpeg', 5, 1, '2026-05-19 17:21:34', '2026-05-22 18:40:53'),
(2410, 21, 304, 'Glenfiddich 18', 'glenfiddich-18', NULL, 250000.00, '6a10a42d2d31b.jpeg', 1, 1, '2026-05-19 17:25:05', '2026-08-09 01:09:40'),
(2411, 21, 304, 'Glenfiddich 12', 'glenfiddich-12', NULL, 105000.00, '6a10a450eeb6f.jpg', 2, 1, '2026-05-19 17:25:57', '2026-08-09 01:10:00'),
(2412, 21, 304, 'Jameson', 'jameson', NULL, 45000.00, '6a10a48738497.jpg', 3, 1, '2026-05-19 17:26:58', '2026-08-09 01:10:20'),
(2413, 21, 304, 'Jameson Black Barrel', 'jameson-black-barrel', NULL, 80000.00, '6a10a4af806f2.webp', 4, 1, '2026-05-19 17:27:51', '2026-08-09 01:10:39'),
(2414, 21, 304, 'Red Label', 'red-label', NULL, 40000.00, '6a10a4f55d33e.jpeg', 5, 1, '2026-05-19 17:28:34', '2026-08-09 01:11:08'),
(2415, 21, 304, 'Black Label', 'black-label', NULL, 75000.00, '6a10a533bfbc3.jpeg', 6, 1, '2026-05-19 17:29:13', '2026-08-09 01:11:29'),
(2416, 21, 304, 'Jack Daniel', 'jack-daniel', NULL, 65000.00, '6a10a582bf671.jpeg', 7, 1, '2026-05-19 17:31:03', '2026-08-09 01:11:53'),
(2417, 21, 304, 'Glenfiddich 15', 'glenfiddich-15', NULL, 150000.00, '6a10a5a8b9f4d.jpeg', 8, 1, '2026-05-19 17:33:47', '2026-08-09 01:12:12'),
(2418, 21, 304, 'Best Whisky Small', 'b-2', '', 4000.00, '6a10a5f561363.jpeg', 9, 1, '2026-05-19 17:35:08', '2026-05-22 18:52:37'),
(2419, 21, 304, 'Blue Label', 'blue-label', NULL, 450000.00, '6a10a629beec1.jpeg', 10, 1, '2026-05-19 17:35:56', '2026-08-13 00:06:16'),
(2420, 21, 304, 'William Lawson', 'w', '', 35000.00, '6a10a66146960.png', 11, 1, '2026-05-19 17:36:47', '2026-05-22 18:54:25'),
(2421, 21, 305, 'Moet Rose', 'm', '', 220000.00, '6a10cb60333ce.jpg', 1, 1, '2026-05-19 17:40:41', '2026-05-22 21:32:16'),
(2422, 21, 305, 'Moet Brut', 'm-2', '', 200000.00, '6a10cb839d359.jpg', 2, 1, '2026-05-19 17:41:19', '2026-05-22 21:32:51'),
(2423, 21, 305, 'Veuve Cliequnt', 'v', '', 250000.00, '6a10cbf246ee6.png', 3, 1, '2026-05-19 17:42:08', '2026-05-22 21:34:42'),
(2424, 21, 305, 'Andre Brut', 'a', '', 28000.00, '6a10cc1f70694.jpeg', 4, 1, '2026-05-19 17:43:33', '2026-05-22 21:35:27'),
(2425, 21, 305, 'Belaire Rose', 'b', '', 95000.00, '6a10cc922418a.jpeg', 5, 1, '2026-05-19 17:44:18', '2026-05-22 21:37:22'),
(2426, 21, 306, 'Fruit Punch', 'f', '', 7000.00, '6a10d3eca3113.jpeg', 1, 1, '2026-05-19 17:44:50', '2026-05-22 22:08:44'),
(2427, 21, 306, 'Chapman', 'c', '', 7000.00, '6a10d41453077.jpeg', 2, 1, '2026-05-19 17:45:28', '2026-05-22 22:09:24'),
(2428, 21, 306, 'Vanilla Milkshake', 'v', '', 8000.00, '6a10d52162028.jpeg', 3, 1, '2026-05-19 17:46:19', '2026-05-22 22:13:53'),
(2429, 21, 306, 'Strawberry Milkshake', 's', '', 8000.00, '6a10d54f32a82.jpg', 4, 1, '2026-05-19 17:47:02', '2026-05-22 22:14:39'),
(2430, 21, 306, 'Chocolate Milkshake', 'c-2', '', 8000.00, '6a10d576b5d9b.jpg', 5, 1, '2026-05-19 17:59:46', '2026-05-22 22:15:18'),
(2431, 21, 307, 'Long Island', 'l', '', 8000.00, '6a11b249499dc.webp', 1, 1, '2026-05-19 18:29:24', '2026-05-23 13:57:29'),
(2432, 21, 307, 'Pina Colada', 'p', '', 8000.00, '6a11b294f235d.jpg', 2, 1, '2026-05-19 18:30:01', '2026-05-23 13:58:44'),
(2433, 21, 307, 'Espresso Martini', 'e', '', 8000.00, '6a11b2e51472e.jpeg', 3, 1, '2026-05-19 18:33:33', '2026-05-23 14:00:05'),
(2434, 21, 307, 'Tequila Sunrise', 't', '', 8000.00, '6a11b3348afcf.jpeg', 4, 1, '2026-05-19 18:36:29', '2026-05-23 14:01:24'),
(2435, 21, 307, 'Pornstar Martini', 'p-2', '', 8000.00, '6a11b3c004dfd.jpeg', 5, 1, '2026-05-19 18:37:39', '2026-05-23 14:03:44'),
(2436, 21, 307, 'Screaming Organism', 's', '', 8000.00, '6a11b4166981f.jpeg', 6, 1, '2026-05-19 18:39:15', '2026-05-23 14:05:10'),
(2437, 21, 307, 'Martai', 'm', '', 8000.00, '6a11b4f04b707.jpeg', 7, 1, '2026-05-19 18:39:42', '2026-05-23 14:08:48'),
(2438, 21, 307, 'Mojito', 'm-2', '', 8000.00, '6a11b47390e2b.jpg', 8, 1, '2026-05-19 18:40:46', '2026-05-23 14:06:43'),
(2439, 21, 307, '7Steps', '7', '', 10000.00, '6a11b54853067.jpeg', 9, 1, '2026-05-19 18:42:39', '2026-05-23 14:10:16'),
(2440, 21, 307, 'Sex on the Beach', 's-2', '', 8000.00, '6a11b5a3d47e2.jpg', 10, 1, '2026-05-19 18:43:52', '2026-05-23 14:11:47'),
(2441, 21, 307, 'Adio Motherfucker', 'a', '', 8000.00, '6a11b5fed7ae5.jpeg', 11, 1, '2026-05-19 18:45:16', '2026-05-23 14:13:18'),
(2442, 21, 307, 'Chapman (alcoholic)', 'c', '', 8000.00, '6a10d5bd6597e.jpeg', 12, 1, '2026-05-19 18:46:09', '2026-05-22 22:16:29'),
(2443, 21, 308, 'Baileys', 'b', '', 40000.00, '6a10c55f94ce6.png', 1, 1, '2026-05-19 18:47:22', '2026-05-22 21:06:39'),
(2444, 21, 308, 'Best Cream Big', 'b-2', '', 20000.00, '6a10c582c7a5e.jpg', 2, 1, '2026-05-19 18:48:01', '2026-05-22 21:07:14'),
(2445, 21, 308, 'Best Cream Small', 'b-3', '', 5000.00, '6a10c5a223000.jpeg', 3, 1, '2026-05-19 18:48:32', '2026-05-22 21:07:46'),
(2446, 21, 308, 'Campari Big', 'c', '', 50000.00, '6a10c5c4171de.jpeg', 4, 1, '2026-05-19 18:49:03', '2026-05-22 21:08:20'),
(2447, 21, 308, 'Campari Small', 'c-2', '', 12000.00, '6a10c5f0311c5.jpeg', 5, 1, '2026-05-19 18:50:15', '2026-05-22 21:09:04'),
(2448, 21, 308, 'Bacardi', 'b-4', '', 35000.00, '6a10c6200dfd9.jpg', 6, 1, '2026-05-19 18:50:43', '2026-05-22 21:09:52'),
(2449, 21, 309, 'Absolute Vodka Big', 'a', '', 35000.00, '6a10a1316a887.webp', 1, 1, '2026-05-19 18:51:34', '2026-05-22 18:32:17'),
(2450, 21, 309, 'Absolute Vodka Small', 'absolute-vodka-small', NULL, 10000.00, '6a10a15ad6a86.jpg', 2, 0, '2026-05-19 18:52:09', '2026-08-17 16:00:01'),
(2451, 21, 309, 'Click Vodka Big', 'c', '', 20000.00, NULL, 3, 1, '2026-05-19 18:52:55', '2026-05-19 18:52:55'),
(2452, 21, 309, 'Click Vodka Small', 'c-2', '', 5000.00, '6a10a1bf5fd2d.jpeg', 4, 1, '2026-05-19 18:53:18', '2026-05-22 18:34:39'),
(2453, 21, 309, 'Gordons', 'g', '', 16000.00, '6a10a1ef40e10.jpg', 5, 1, '2026-05-19 18:53:55', '2026-05-22 18:35:27'),
(2454, 21, 310, 'Escudo Rojo', 'escudo-rojo', NULL, 35000.00, '6a10c6723b9f5.jpeg', 1, 1, '2026-05-19 18:54:39', '2026-08-09 01:13:39'),
(2455, 21, 310, 'Chambercino', 'chambercino', NULL, 30000.00, '6a10c6a31ccbf.jpeg', 2, 1, '2026-05-19 18:56:36', '2026-08-12 23:39:07'),
(2456, 21, 310, 'Silk & Spice', 's', '', 30000.00, '6a10c6d7ed129.jpeg', 3, 1, '2026-05-19 18:58:00', '2026-05-22 21:12:55'),
(2457, 21, 310, 'Carlo Rossi', 'carlo-rossi', NULL, 25000.00, '6a10c70092a13.jpg', 4, 1, '2026-05-19 18:58:36', '2026-08-09 01:14:21'),
(2458, 21, 310, 'Four Cousins', 'four-cousins', NULL, 20000.00, '6a10c84751db4.jpg', 6, 1, '2026-05-19 18:59:35', '2026-08-09 01:15:18'),
(2459, 21, 310, '4th Street', '4th-street', NULL, 20000.00, '6a10c72e847bd.jpeg', 5, 1, '2026-05-19 19:02:33', '2026-08-09 01:14:55'),
(2460, 21, 310, 'Agor Wine', 'agor-wine', NULL, 20000.00, '6a10c86f8825a.jpeg', 7, 1, '2026-05-19 19:05:15', '2026-08-09 01:15:34'),
(2461, 21, 310, 'Declan Red', 'd', '', 20000.00, '6a10c88e6a359.jpeg', 8, 1, '2026-05-19 19:05:54', '2026-05-22 21:20:14'),
(2462, 21, 310, 'Drevni Donati', 'd-2', '', 20000.00, '6a10cae5080a4.jpeg', 9, 1, '2026-05-19 19:06:32', '2026-05-22 21:30:13'),
(2463, 21, 310, 'Eva Wine', 'eva-wine', NULL, 15000.00, '6a10c924010c1.jpg', 10, 1, '2026-05-19 19:07:29', '2026-08-09 01:15:56'),
(2464, 21, 311, 'Heineken Big', 'h', '', 2500.00, '6a11a895222eb.jpeg', 1, 1, '2026-05-19 19:08:36', '2026-05-23 13:16:05'),
(2465, 21, 311, 'Heineken Small', 'h-2', '', 2000.00, '6a11a8b5e43fe.jpg', 2, 1, '2026-05-19 19:09:27', '2026-05-23 13:16:37'),
(2466, 21, 311, 'Big Stout', 'b', '', 2500.00, '6a11a8e0955fe.jpeg', 3, 1, '2026-05-19 19:09:59', '2026-05-23 13:17:20'),
(2467, 21, 311, 'Small Stout', 's', '', 2000.00, '6a11a8febb2ba.jpeg', 4, 1, '2026-05-19 19:10:42', '2026-05-23 13:17:50'),
(2468, 21, 311, 'Smirnoff Ice Big', 's-2', '', 2500.00, '6a11ae3271fed.jpeg', 5, 1, '2026-05-19 19:12:26', '2026-05-23 13:40:02'),
(2469, 21, 311, 'Smirnoff Ice Small', 's-3', '', 2000.00, '6a11ae5046df3.jpeg', 6, 1, '2026-05-19 19:12:59', '2026-05-23 13:40:32'),
(2470, 21, 311, 'Smirnoff Ice Double Black', 's-4', '', 2000.00, '6a11af093e2bc.jpeg', 7, 1, '2026-05-19 19:14:44', '2026-05-23 13:43:37'),
(2471, 21, 311, 'Budweiser', 'b-2', '', 2500.00, '6a11af347b0c4.png', 8, 1, '2026-05-19 19:15:46', '2026-05-23 13:44:20'),
(2472, 21, 311, 'Budweiser Royale', 'b-3', '', 2500.00, '6a11af5bee979.webp', 9, 1, '2026-05-19 19:16:40', '2026-05-23 13:44:59'),
(2473, 21, 311, 'Goldberg', 'g', '', 2000.00, '6a11af7e56b8b.jpeg', 10, 1, '2026-05-19 19:17:38', '2026-05-23 13:45:34'),
(2474, 21, 311, 'Goldberg Black', 'g-2', '', 2000.00, '6a11afb99baeb.jpeg', 11, 1, '2026-05-19 19:18:27', '2026-05-23 13:46:33'),
(2475, 21, 311, 'Legend', 'l', '', 2500.00, '6a11afd560191.jpeg', 12, 1, '2026-05-19 19:19:25', '2026-05-23 13:47:01'),
(2476, 21, 311, 'Desprado', 'd', '', 2000.00, '6a11afff2c676.jpg', 13, 1, '2026-05-19 19:38:29', '2026-05-23 13:47:43'),
(2477, 21, 311, 'Gulder', 'g-3', '', 2000.00, '6a11b01cc2c0b.jpeg', 14, 1, '2026-05-19 19:39:14', '2026-05-23 13:48:12'),
(2478, 21, 311, 'Origin Beer', 'o', '', 2000.00, '6a11b03a93971.jpeg', 15, 1, '2026-05-19 19:40:36', '2026-05-23 13:48:42'),
(2479, 21, 311, 'Tiger', 't', '', 2000.00, '6a11b05ea1de6.webp', 16, 1, '2026-05-19 19:41:51', '2026-05-23 13:49:18'),
(2480, 21, 311, '33 Export', '3', '', 2000.00, '6a11b08002c9c.jpg', 17, 1, '2026-05-19 19:42:54', '2026-05-23 13:49:52'),
(2481, 21, 311, 'Trophy', 't-2', '', 2000.00, '6a11b0bf0a7ff.jpg', 18, 1, '2026-05-19 19:43:21', '2026-05-23 13:50:55'),
(2482, 21, 311, 'Star Raddler', 's-5', '', 2000.00, '6a11b0d6bb61b.jpeg', 19, 1, '2026-05-19 19:55:24', '2026-05-23 13:51:18'),
(2483, 21, 311, 'Castle Light', 'c', '', 2000.00, '6a11b0f396173.png', 20, 1, '2026-05-19 19:56:03', '2026-05-23 13:51:47'),
(2484, 21, 311, 'Flying Fish', 'f', '', 2000.00, '6a11b112b256b.jpeg', 21, 1, '2026-05-19 19:56:45', '2026-05-23 13:52:18'),
(2485, 21, 312, 'Chivita', 'c', '', 3500.00, '6a11a6b67edb2.jpeg', 1, 1, '2026-05-19 19:57:38', '2026-05-23 13:08:06'),
(2486, 21, 311, 'Star', 's-6', '', 2000.00, '6a11b1483bab8.webp', 22, 1, '2026-05-19 20:15:33', '2026-05-23 13:53:12'),
(2487, 21, 312, 'Chi Exotic', 'c-2', '', 3500.00, '6a11a6d3724f9.png', 2, 1, '2026-05-19 20:22:09', '2026-05-23 13:08:35'),
(2488, 21, 312, 'Hollandia Yoghurt', 'h', '', 4000.00, '6a11a775b9196.jpeg', 3, 1, '2026-05-19 20:33:02', '2026-05-23 13:11:17'),
(2489, 21, 312, 'Sosa Cranberry', 'sosa-cranberry', NULL, 3000.00, '6a11a79919fb2.jpeg', 4, 1, '2026-05-19 20:33:41', '2026-08-12 23:53:54'),
(2490, 21, 312, 'Berry Blast', 'b', '', 3000.00, '6a11a7ba185cd.jpg', 5, 1, '2026-05-19 20:34:38', '2026-05-23 13:12:26'),
(2491, 21, 313, 'Action Bitter', 'action-bitter', NULL, 3000.00, '6a11b9e41bcff.jpg', 1, 0, '2026-05-19 20:38:05', '2026-08-17 15:59:22'),
(2492, 21, 313, 'Origin Bitters (Plastic)', 'origin-bitters-plastic', NULL, 4000.00, '6a11ba00ad840.jpeg', 2, 1, '2026-05-19 20:38:56', '2026-08-12 23:39:48'),
(2493, 21, 313, 'Power Horse', 'power-horse', NULL, 3500.00, '6a11ba1b95a30.jpeg', 3, 1, '2026-05-19 20:39:41', '2026-08-21 19:08:34'),
(2494, 21, 313, 'Monster', 'm', '', 3000.00, '6a11ba7505b82.webp', 4, 1, '2026-05-19 20:40:14', '2026-05-23 14:32:21'),
(2495, 21, 313, 'Red Bull', 'r', '', 3000.00, '6a11ba9d90a4c.jpeg', 5, 1, '2026-05-19 20:41:30', '2026-05-23 14:33:01'),
(2496, 21, 313, 'Black Bullet', 'b', '', 3000.00, '6a11badb6421b.png', 6, 1, '2026-05-19 20:43:01', '2026-05-23 14:34:03'),
(2497, 21, 314, 'Water', 'w', '', 1000.00, '6a11b64e1fee6.jpeg', 1, 1, '2026-05-19 20:46:02', '2026-05-23 14:14:38'),
(2498, 21, 314, 'Pet Drinks', 'p', '', 1200.00, '6a11b687d4712.jpeg', 2, 1, '2026-05-19 20:46:39', '2026-05-23 14:15:35'),
(2499, 21, 314, 'Malt', 'm', '', 1500.00, '6a11b6adbf237.jpg', 3, 1, '2026-05-19 20:47:09', '2026-05-23 14:16:13'),
(2500, 21, 314, 'Fayrous', 'f', '', 1200.00, '6a11b6e10f1c4.jpg', 4, 1, '2026-05-19 20:47:44', '2026-05-23 14:17:05'),
(2501, 21, 314, 'Vita Milk', 'vita-milk', NULL, 3000.00, '6a11b709bdb34.webp', 5, 1, '2026-05-19 20:48:15', '2026-08-03 20:23:01'),
(2502, 21, 315, 'Tot-Absolute Vodka', 't', '', 4000.00, '6a11be0876a7a.jpeg', 1, 1, '2026-05-19 20:49:25', '2026-05-23 14:47:36'),
(2503, 21, 315, 'Tot-Gordons', 't-3', '', 4000.00, '6a11be43b22c6.jpeg', 2, 1, '2026-05-19 20:50:09', '2026-05-23 14:48:35'),
(2504, 21, 315, 'Tot-Bacardi', 't-4', '', 4000.00, '6a11be842c79b.jpeg', 3, 1, '2026-05-19 20:52:25', '2026-05-23 14:49:40'),
(2505, 21, 315, 'Tot-Sierra', 't-5', '', 4000.00, '6a11bea044b52.jpeg', 4, 1, '2026-05-19 20:53:00', '2026-05-23 14:50:08'),
(2506, 21, 315, 'Tot-Olmeca', 't-6', '', 4500.00, '6a11beca0382f.jpeg', 5, 1, '2026-05-19 20:53:48', '2026-05-23 14:50:50'),
(2507, 21, 316, 'Sierra Tequila', 's', '', 40000.00, '6a11bef91099a.png', 1, 1, '2026-05-19 20:57:28', '2026-05-23 14:51:37'),
(2508, 21, 316, 'Olmeca Tequila', 'o', '', 70000.00, '6a11bf1627c5c.jpg', 2, 1, '2026-05-19 20:58:14', '2026-05-23 14:52:06'),
(2509, 21, 316, 'Casamigos', 'c', '', 250000.00, '6a11bf48aa08b.jpeg', 3, 1, '2026-05-19 20:58:54', '2026-05-23 14:52:56'),
(2510, 21, 317, 'Isiewu', 'isiewu-R5Qp', NULL, 15000.00, NULL, 1, 1, '2026-05-19 21:42:57', '2026-07-30 01:49:08'),
(2511, 21, 317, 'Nkwobi', 'nkwobi-hxeH', NULL, 8000.00, NULL, 2, 1, '2026-05-19 21:45:01', '2026-07-30 01:49:27'),
(2512, 21, 317, 'Vegetable Snail', 'vegetable-snail', NULL, 15000.00, NULL, 3, 1, '2026-05-19 21:45:31', '2026-07-30 01:49:43'),
(2513, 21, 318, 'Catfish Barbeque', 'c', '', 20000.00, NULL, 1, 1, '2026-05-19 21:53:49', '2026-05-19 21:53:49'),
(2514, 21, 318, 'Croaker Fish Barbeque', 'croaker-fish-barbeque', NULL, 25000.00, NULL, 2, 1, '2026-05-19 21:54:38', '2026-07-18 15:37:43'),
(2515, 21, 318, 'Live Chicken', 'l', '', 25000.00, NULL, 3, 1, '2026-05-19 21:55:08', '2026-05-19 21:55:08'),
(2516, 21, 318, 'Guinea Fowl', 'g', '', 25000.00, NULL, 4, 1, '2026-05-19 21:56:05', '2026-05-19 21:56:05'),
(2517, 21, 318, 'Ellipse Special Croaker Fish Barbeque', 'ellipse-special-croaker-fish-barbeque', NULL, 35000.00, NULL, 5, 1, '2026-05-19 21:56:53', '2026-07-30 01:06:26'),
(2518, 21, 318, 'Steak	250g', 'steak-250g', NULL, 25000.00, NULL, 6, 1, '2026-05-19 22:00:15', '2026-07-30 01:01:49'),
(2519, 21, 318, 'Full Chicken Ngwongwo/Vegetables', 'full-chicken-ngwongwovegetables', NULL, 30000.00, NULL, 7, 1, '2026-05-19 22:09:29', '2026-07-30 01:04:04'),
(2520, 21, 318, 'Stir Fry Prawns', 'stir-fry-prawns', NULL, 25000.00, NULL, 8, 1, '2026-05-19 22:11:06', '2026-07-30 01:00:36'),
(2521, 21, 318, 'Chicken and Chips', 'c-5', '', 10000.00, NULL, 9, 1, '2026-05-19 22:11:58', '2026-05-19 22:11:58'),
(2522, 21, 318, 'Turkey and Chips', 't', '', 10000.00, NULL, 10, 1, '2026-05-19 22:12:42', '2026-05-19 22:12:42'),
(2523, 21, 319, 'Peppered Goat Meat', 'peppered-goat-meat', NULL, 8000.00, NULL, 1, 1, '2026-05-19 22:15:42', '2026-07-29 23:19:39'),
(2524, 21, 319, 'Peppered Beef', 'peppered-beef-LYyY', NULL, 8000.00, NULL, 2, 1, '2026-05-19 22:16:24', '2026-07-29 23:19:00'),
(2525, 21, 319, 'Peppered Snail', 'peppered-snail-ynvX', NULL, 15000.00, NULL, 4, 1, '2026-05-19 22:17:45', '2026-07-29 23:18:37'),
(2526, 21, 319, 'Peppered Turkey', 'p-4', '', 8000.00, NULL, 3, 1, '2026-05-19 22:19:21', '2026-05-19 22:19:21'),
(2527, 21, 319, 'Catfish Pepper Soup', 'c', '', 20000.00, NULL, 5, 1, '2026-05-19 22:20:35', '2026-05-19 22:20:35'),
(2528, 21, 319, 'Goat Meat Pepper Soup', 'goat-meat-pepper-soup-7BSa', NULL, 10000.00, NULL, 6, 1, '2026-05-19 22:35:06', '2026-08-09 01:40:34'),
(2529, 21, 310, 'Pure Heaven', 'pure-heaven', NULL, 15000.00, '6a10c987bc3c7.jpg', 11, 1, '2026-05-22 21:24:23', '2026-08-09 01:16:13'),
(2530, 21, 310, 'Chamdor', 'chamdor', NULL, 15000.00, '6a10ca7980468.jpg', 12, 1, '2026-05-22 21:28:25', '2026-08-09 01:16:36'),
(2531, 21, 310, 'Cooper & Thief', 'c-4', '', 80000.00, '6a10cb22927ff.jpeg', 13, 1, '2026-05-22 21:31:14', '2026-05-22 21:31:14'),
(2532, 21, 304, 'American Honey', 'a', '', 60000.00, '6a10cce628fbb.jpg', 12, 1, '2026-05-22 21:38:46', '2026-05-22 21:38:46'),
(2533, 21, 309, 'Ciroc Vodka', 'c-3', '', 80000.00, '6a10cdb6107c6.webp', 6, 1, '2026-05-22 21:42:14', '2026-05-22 21:42:14'),
(2534, 21, 303, 'Hennessy VS Small', 'h-3', '', 60000.00, '6a10d03d2bca3.jpg', 6, 1, '2026-05-22 21:53:01', '2026-05-22 21:53:01'),
(2535, 21, 312, 'Smoothies', 's', '', 5000.00, '6a11a86a0159c.jpg', 6, 1, '2026-05-23 13:15:22', '2026-05-23 13:15:22'),
(2536, 2, 320, 'Chicken Spring Rolls', 'chicken-spring-rolls', 'Chicken stuffed rolls with mixed bell peppers and cabbage served with plum sauce', 200.00, '6945dee5937fd.jpg', 1, 1, '2025-12-19 18:43:07', '2026-03-09 12:51:35'),
(2537, 2, 320, 'Grilled Chicken Wings', 'grilled-chicken-wings', 'Grilled Marinated Chicken Wings, Served With Homemade Chili Sauce.', 23000.00, '6945df24c6e77.jpg', 2, 1, '2025-12-19 18:43:07', '2025-12-19 23:26:28'),
(2538, 2, 320, 'Lollipop Chicken', 'lollipop-chicken', 'Half boneless fried wings with your choice of spicy BBQ or honey mustard sauce.', 25000.00, '6945e61603f0b.jpg', 3, 1, '2025-12-19 18:43:07', '2025-12-19 23:56:06'),
(2539, 2, 320, 'Caesar Chicken Sliders', 'caesar-chicken-sliders', 'marinated grilled chicken, Caesar sauce, lettuce, tomato, dill pickles, parmesan cheese.', 19000.00, '6945e63143e76.jpg', 4, 1, '2025-12-19 18:43:07', '2025-12-19 23:56:33'),
(2540, 2, 320, 'Grilled Pettit Prawns', 'grilled-pettit-prawns', 'Grilled Medium Prawns Seasoned In Herb Sauce Served With Fresh Red Onions And Side Salad.', 35000.00, '6945e67a43210.jpg', 5, 1, '2025-12-19 18:43:07', '2025-12-19 23:57:46'),
(2541, 2, 320, 'Dynamite Shrimp', 'dynamite-shrimp', 'Crispy, Fried Shrimps Coated In A Spicy Mayonnaise Dressing.', 25000.00, '6945e6919fc7f.jpg', 6, 1, '2025-12-19 18:43:07', '2025-12-19 23:58:09'),
(2542, 2, 320, 'Dynamite Chicken', 'dynamite-chicken', 'Crispy, Golden-Brown Fried Chicken Served With Dynamite Sauce.', 25000.00, '6945e6daee3be.jpeg', 7, 1, '2025-12-19 18:43:07', '2025-12-19 23:59:22'),
(2543, 2, 320, 'Opal Signature Snails', 'opal-signature-snails', 'Sauteed Snails With Mixed Bell Pepper In Nigerian Spice Served With Plantain Fingers.', 32000.00, '6945e735a03f8.jpg', 8, 1, '2025-12-19 18:43:07', '2025-12-20 00:00:53'),
(2544, 2, 320, 'Goat Meat', 'goat-meat', 'Tender Goat Meat Sautéed With Nigerian Spices.', 28400.00, '6945e77099e77.jpg', 9, 1, '2025-12-19 18:43:07', '2025-12-20 00:01:52'),
(2545, 2, 320, 'Peppered Shrimps', 'peppered-shrimps', 'Shrimps Toasted In Chili Peppered Sauce Green Pepper And Onions.', 38000.00, '6945e78c75af7.jpg', 10, 1, '2025-12-19 18:43:07', '2025-12-20 00:02:20'),
(2546, 2, 320, 'Peppered Assorted Meat', 'peppered-assorted-meat', 'Peppered Nigerian Shaki, Gizzards, Tender Beef And Chicken Breast Served With Tomato And Red Onions.', 31700.00, '6945e7f2cac67.jpg', 11, 1, '2025-12-19 18:43:07', '2025-12-20 00:04:02'),
(2547, 2, 320, 'Coconut Popcorn Shrimps', 'coconut-popcorn-shrimps', 'Breaded Shrimps, Deep Fried Served With Tartar And Cocktail Sauce', 35200.00, NULL, 12, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2548, 2, 320, 'Chicken Tender', 'chicken-tender', 'Deep-Fried Breaded Chicken Breast Served With Honey Mustard Sauce.', 30700.00, NULL, 13, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2549, 2, 321, 'Sweet Fried Potatoes', 'sweet-fried-potatoes', '', 8900.00, NULL, 1, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2550, 2, 321, 'French Fries', 'french-fries', '', 9600.00, NULL, 2, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2551, 2, 321, 'Chinese Fried Rice', 'chinese-fried-rice', '', 15200.00, NULL, 3, 1, '2025-12-19 18:43:07', '2025-12-20 13:19:16'),
(2552, 2, 321, 'Steamed Rice', 'steamed-rice', '', 7000.00, NULL, 4, 1, '2025-12-19 18:43:07', '2025-12-20 13:21:01'),
(2553, 2, 321, 'Plantain Fingers', 'plantain-fingers', '', 8500.00, NULL, 5, 1, '2025-12-19 18:43:07', '2025-12-20 13:22:04'),
(2554, 2, 321, 'Yam Fingers', 'yam-fingers', '', 6500.00, NULL, 6, 1, '2025-12-19 18:43:07', '2025-12-20 13:22:52'),
(2555, 2, 321, 'Ice Cream Cake', 'ice-cream-cake', '', 15200.00, NULL, 7, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2556, 2, 322, 'Fruit Salad & Chocolate Ice Cream', 'fruit-salad-chocolate-ice-cream', '', 14000.00, NULL, 1, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2557, 2, 322, 'Ice Cream Scoop', 'ice-cream-scoop', 'Chocolate Vanilla and Extra', 12000.00, NULL, 2, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2558, 2, 323, 'Don P Brut', 'don-p-brut', '', 1400000.00, NULL, 1, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2559, 2, 323, 'Don P Rose', 'don-p-rose', '', 1550000.00, NULL, 2, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2560, 2, 323, 'Ace of Spade', 'ace-of-spade', '', 1500000.00, NULL, 3, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2561, 2, 323, 'Cristal', 'cristal', '', 1300000.00, NULL, 4, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2562, 2, 324, 'Don Julio Magnum', 'don-julio-magnum', '', 2100000.00, NULL, 1, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2563, 2, 324, 'Don Julio 1942', 'don-julio-1942', '', 1200000.00, NULL, 2, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2564, 2, 324, 'Avion', 'avion', '', 900000.00, NULL, 3, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2565, 2, 324, 'Clase Azul', 'clase-azul', '', 1000000.00, NULL, 4, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2566, 2, 324, 'Don Julio 1942 Magnum', 'don-julio-1942-magnum', '', 2100000.00, NULL, 5, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2567, 2, 324, 'Casamigos 1L', 'casamigos-1l', '', 1200000.00, NULL, 6, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2568, 2, 324, 'Casamigos M', 'casamigos-m', '', 1250000.00, NULL, 7, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2569, 2, 324, 'Adiccion Reposado', 'adiccion-reposado', '', 1000000.00, NULL, 8, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2570, 2, 325, 'Hennessy XO', 'hennessy-xo', '', 1000000.00, NULL, 1, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2571, 2, 325, 'Martel XO', 'martel-xo', '', 950000.00, NULL, 2, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2572, 2, 326, 'Glen Fiddich 21', 'glen-fiddich-21', '', 1000000.00, NULL, 1, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2573, 2, 326, 'Glen Fiddich 23', 'glen-fiddich-23', '', 1300000.00, NULL, 2, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2574, 2, 326, 'Glen Fiddich 26', 'glen-fiddich-26', '', 2300000.00, NULL, 3, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2575, 2, 326, 'Glen Livet 21', 'glen-livet-21', '', 950000.00, NULL, 4, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2576, 2, 327, 'Iced Gum', 'iced-gum', '', 50000.00, NULL, 1, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2577, 2, 327, 'Magic Love', 'magic-love', '', 50000.00, NULL, 2, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2578, 2, 327, 'Love 66', 'love-66', '', 60000.00, NULL, 3, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2579, 2, 327, 'Strawberry', 'strawberry', '', 50000.00, NULL, 4, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2580, 2, 327, 'Strawberry and Mint', 'strawberry-and-mint', '', 50000.00, NULL, 5, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2581, 2, 327, 'Mixed Fruit', 'mixed-fruit', '', 50000.00, NULL, 6, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2582, 2, 327, 'Gum and Mint', 'gum-and-mint', '', 50000.00, NULL, 7, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2583, 2, 327, 'Gum', 'gum', '', 50000.00, NULL, 8, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2584, 2, 327, 'Lemon and Mint', 'lemon-and-mint', '', 50000.00, NULL, 9, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2585, 2, 327, 'Mint and Cream', 'mint-and-cream', '', 50000.00, NULL, 10, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2586, 2, 327, 'Grape and Mint', 'grape-and-mint', '', 50000.00, NULL, 11, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2587, 2, 327, 'Grape', 'grape', '', 50000.00, NULL, 12, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2588, 2, 327, 'Two Apple', 'two-apple', '', 50000.00, NULL, 13, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2589, 2, 327, 'Mint', 'mint', '', 50000.00, NULL, 14, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2590, 2, 327, 'Peach', 'peach', '', 50000.00, NULL, 15, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2591, 2, 327, 'Blueberry', 'blueberry', '', 50000.00, NULL, 16, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2592, 2, 327, 'Blueberry and Mint', 'blueberry-and-mint', '', 50000.00, NULL, 17, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2593, 2, 327, 'Mango', 'mango', '', 50000.00, NULL, 18, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2594, 2, 327, 'Watermelon', 'watermelon', '', 50000.00, NULL, 19, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2595, 2, 327, 'Watermelon and Mint', 'watermelon-and-mint', '', 50000.00, NULL, 20, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2596, 2, 327, 'Lady Killer', 'lady-killer', '', 50000.00, NULL, 21, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2597, 2, 327, 'Apple', 'apple', '', 50000.00, NULL, 22, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2598, 2, 327, 'Pineapple Fruit', 'pineapple-fruit', '', 50000.00, NULL, 23, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2599, 2, 327, 'Apple Fruit', 'apple-fruit', '', 50000.00, NULL, 24, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2600, 2, 327, 'Orange Fruit', 'orange-fruit', '', 50000.00, NULL, 25, 1, '2025-12-19 18:43:07', '2025-12-19 18:43:07'),
(2601, 4, 328, 'Hunter\'s Soup', 'hunters-soup', 'Goat Meat Chunks, Herbs, Yam Balls', 18500.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-08-25 17:42:22'),
(2602, 4, 328, 'Seafood Skillet', 'seafood-skillet', 'Mixed Seafood, Eggs, Bell Peppers, Mozzarella Cheese, Marinara Sauce, Agege French Toast', 13500.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2603, 4, 328, 'Beef Sliders', 'beef-sliders', 'Bun, Beef Patties, Lettuce, Tomatoes, Caramelized Onions, Mozzarella Cheese', 15000.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2604, 4, 328, 'Spicy Glazed Lamb Ribs', 'spicy-glazed-lamb-ribs', 'Braised Lamb Ribs, Pickled Shombo, Hoisin Sauce, Sesame Seeds, Petite Salad', 19900.00, NULL, 4, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2605, 4, 328, 'Damgonama Rolls', 'damgonama-rolls', 'Dried Pulled Beef, Cabbage, Bell Peppers, Chili Flakes, Peppered Jam', 14900.00, NULL, 5, 1, '2026-05-13 01:27:51', '2026-08-25 18:01:03'),
(2606, 4, 328, 'Peanut Crusted Suya', 'peanut-crusted-suya', 'Torzo, Chicken Breast, Crushed Peanuts, Yaji, Tomatoes, Onions', 12900.00, NULL, 6, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2607, 4, 328, 'Stuffed Chicken Caesar Salad', 'stuffed-chicken-caesar-salad', 'Chicken Breast, Parmesan Croutons, Iceberg Lettuce, Cherry Tomatoes, Salad Dressing', 19900.00, NULL, 7, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2608, 4, 328, 'Spicy Snails Basket', 'spicy-snails-basket', 'Peppered Snails, Paprika Boli, Iyamase Sauce', 25000.00, NULL, 8, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2609, 4, 328, 'Mixed Meat Taco', 'mixed-meat-taco', 'Brisket, Tortilla, Tomato Salsa, Cucumber Yoghurt Drizzle', 16900.00, NULL, 9, 1, '2026-05-13 01:27:51', '2026-08-25 18:07:49'),
(2610, 4, 328, 'Let\'s Wings It... Chicken Lollipops', 'lets-wings-it-chicken-lollipops', 'Tomato Base, Mushrooms, Caramelized Onions, Cheese. Choice of Sauce: Orange Cumin • Honey Mustard • BBQ Peanut • Lemon Garlic Parmesan • Chilli Sauce • Plain', 14900.00, NULL, 10, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2611, 4, 329, 'Brown Butter Salmon', 'brown-butter-salmon', 'Grilled Salmon, Sweet Potato Fingers, Seasonal Vegetables, Beurre Blanc', 49000.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2612, 4, 329, 'Seafood Pasta', 'seafood-pasta', 'Creamy Seafood Mix, Linguine, Tomatoes, Herbs, Pesto Baguette', 45750.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-08-25 18:44:44'),
(2613, 4, 329, 'Beef Burger', 'beef-burger', 'Beef Pattie, Lettuce, Fried Egg, Caramelized Onions, Spicy Burger Sauce', 22500.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2614, 4, 329, 'Smoked Short Rib Pasta', 'smoked-short-rib-pasta', 'Short Ribs, Rigatoni, Ragu, Herbs, Mushrooms', 34990.00, NULL, 4, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2615, 4, 329, 'Smoked Chicken Tagliatelle', 'smoked-chicken-tagliatelle', 'Chicken, Macon, Tagliatelle Pasta, Cream, Kale, Peas, Sundried Tomatoes', 29900.00, NULL, 5, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2616, 4, 329, 'Grilled Baby Chicken', 'grilled-baby-chicken', 'Baby Chicken, Oxtail Fried Rice, Potato Crisps, Caramelized Onions, Chili Sauce', 39000.00, NULL, 6, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2617, 4, 329, '700G Grilled Tomahawk Steak (Feeds 2)', '700g-grilled-tomahawk-steak', 'Tomahawk Steak, Oxtail Fried Rice, Seasonal Vegetables, Carrot Puree, Peppercorn Sauce', 90000.00, NULL, 7, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2618, 4, 329, 'Sirloin with Marrow Butter', 'sirloin-with-marrow-butter', '250g Sirloin, Mash Potatoes, Seasonal Vegetables, Carrot Puree, Marrow Butter', 52500.00, NULL, 8, 1, '2026-05-13 01:27:51', '2026-08-25 18:45:15'),
(2619, 4, 329, 'Herb Crusted Lamb Chops', 'herb-crusted-lamb-chops', '450g French Trimmed Cutlets, Oxtail Rice, Seasonal Vegetables. Options: Grilled / Herb-Crusted', 58500.00, NULL, 9, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2620, 4, 329, 'Pan Seared Chicken', 'pan-seared-chicken', 'Grilled Chicken, Sauteed Potatoes, Creamed Corn, Chicken Gravy', 29900.00, NULL, 10, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2621, 4, 329, 'Peri-Peri Jumbo Prawns', 'peri-peri-jumbo-prawns', 'Chargrilled Jumbo Prawns, Shredded Beef, Salsa, Kelewele, Jollof Rice', 48750.00, NULL, 11, 1, '2026-05-13 01:27:51', '2026-08-25 18:45:39'),
(2622, 4, 330, 'Meat Nostalgia', 'meat-nostalgia', 'Braised Short Ribs, Pineapple Chicken Drumsticks, Pulled Brisket Taquitos, Whisky Beef Sliders, Mixed Tubers, Oxtail Fried Rice', 69000.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2623, 4, 330, 'Nostalgia Seafood Sail', 'nostalgia-seafood-sail', 'XXL Croaker, Jumbo Prawns, Baby Calamari, Shrimp Rolls, Curry Snails, Kelewele, Fries', 79500.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2624, 4, 330, 'Smoked Guinea Fowl', 'smoked-guinea-fowl', 'Smoked Guinea Fowl, Sweet Potato Wedges, Corn Ribs, Green Salad', 42000.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2625, 4, 331, 'Chicken & Prawns Caesar Salad', 'chicken-prawns-caesar-salad', 'Jumbo Prawns, Chicken Breast, Iceberg Lettuce, Croutons, Egg, Parmesan Cheese, Caesar Dressing', 33900.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2626, 4, 331, 'Italian Chopped Salad', 'italian-chopped-salad', 'Turkey Ham, Iceberg Lettuce, Feta Cheese, Cherry Tomatoes, Sweet Corn, Olives', 15000.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2627, 4, 331, 'Prawns Salad', 'prawns-salad', 'Jumbo Prawns, Iceberg Lettuce, Croutons, Egg, Parmesan Cheese, Caesar Dressing', 29900.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2628, 4, 331, 'Chicken Caesar Salad', 'chicken-caesar-salad', 'Chicken Breast, Iceberg Lettuce, Croutons, Egg, Parmesan Cheese, Caesar Dressing', 24900.00, NULL, 4, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2629, 4, 332, 'Jollof Rice', 'jollof-rice', NULL, 8000.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-08-25 18:40:40'),
(2630, 4, 332, 'Oxtail Fried Rice', 'oxtail-fried-rice', NULL, 9500.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-08-25 18:40:52'),
(2631, 4, 332, 'Kelewele', 'kelewele', NULL, 5500.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-08-25 18:39:39'),
(2632, 4, 332, 'Mixed Tubers', 'mixed-tubers', NULL, 6500.00, NULL, 4, 1, '2026-05-13 01:27:51', '2026-08-25 18:36:50'),
(2633, 4, 332, 'Mash Potatoes', 'mash-potatoes', NULL, 5500.00, NULL, 5, 1, '2026-05-13 01:27:51', '2026-08-25 18:38:36'),
(2634, 4, 332, 'French Fries', 'french-fries', NULL, 5500.00, NULL, 6, 1, '2026-05-13 01:27:51', '2026-08-25 18:37:51'),
(2635, 4, 332, 'Seasonal Vegetables', 'seasonal-vegetables', NULL, 3500.00, NULL, 7, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2636, 4, 332, 'Prawns', 'prawns-side', NULL, 26800.00, NULL, 8, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2637, 4, 333, 'Bailey Brownie', 'bailey-brownie', 'Baileys Irish Liqueur, Espresso Powder, Truffle Chocolate Cake', 9500.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2638, 4, 333, 'Apple Pie', 'apple-pie', 'Baked Apples, Lemon Butter, Short Crust Pie', 9500.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2639, 4, 333, 'Jamaican Rum Cake', 'jamaican-rum-cake', 'Candied Fruit, Rum, Moist Brown Sponge', 9500.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2640, 4, 334, 'Cristal Louis Roederer', 'cristal-louis-roederer', NULL, 1159200.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2641, 4, 334, 'Cristal Magnum', 'cristal-magnum', NULL, 2600000.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2642, 4, 334, 'Dom Pérignon Brut', 'dom-perignon-brut', NULL, 1034000.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2643, 4, 334, 'Ace of Spades Brut', 'ace-of-spades-brut', NULL, 1140000.00, NULL, 4, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2644, 4, 334, 'Moët Brut Imperial', 'moet-brut-imperial', NULL, 300000.00, NULL, 5, 1, '2026-05-13 01:27:51', '2026-07-01 20:36:49'),
(2645, 4, 334, 'Moët Nectar Imperial Rosé', 'moet-nectar-imperial-rose', NULL, 350000.00, NULL, 6, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2646, 4, 334, 'Veuve Clicquot Brut', 'veuve-clicquot-brut', NULL, 325000.00, NULL, 7, 1, '2026-05-13 01:27:51', '2026-07-01 20:38:06'),
(2647, 4, 334, 'Veuve Clicquot Rich', 'veuve-clicquot-rich', NULL, 450000.00, NULL, 8, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2648, 4, 335, 'Hennessy Paradis', 'hennessy-paradis', NULL, 2875000.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2649, 4, 335, 'Hennessy XO', 'hennessy-xo', NULL, 990000.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2650, 4, 335, 'Hennessy VSOP', 'hennessy-vsop', NULL, 325000.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-07-01 20:39:51'),
(2651, 4, 335, 'Martell Blue Swift', 'martell-blue-swift', NULL, 325000.00, NULL, 4, 1, '2026-05-13 01:27:51', '2026-07-01 20:41:18'),
(2652, 4, 335, 'Martell XO', 'martell-xo', NULL, 790000.00, NULL, 5, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2653, 4, 335, 'Cognac Shot', 'cognac-shot', NULL, 7000.00, NULL, 6, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2654, 4, 336, 'Glenfiddich 18', 'glenfiddich-18', NULL, 400000.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-07-01 20:42:33'),
(2655, 4, 336, 'Glenfiddich 21', 'glenfiddich-21', NULL, 1050000.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-07-01 20:43:11'),
(2656, 4, 336, 'Glenfiddich 23', 'glenfiddich-23', NULL, 1250000.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2657, 4, 336, 'Glenfiddich 26', 'glenfiddich-26', NULL, 1825000.00, NULL, 4, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2658, 4, 336, 'Glenfiddich 30', 'glenfiddich-30', NULL, 7000000.00, NULL, 5, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2659, 4, 336, 'Macallan Rare Cask', 'macallan-rare-cask', NULL, 1150000.00, NULL, 6, 1, '2026-05-13 01:27:51', '2026-07-18 22:39:48'),
(2660, 4, 336, 'Jameson Black Barrel', 'jameson-black-barrel', NULL, 199000.00, NULL, 7, 1, '2026-05-13 01:27:51', '2026-07-11 13:35:57'),
(2661, 4, 336, 'Whiskey Shot', 'whiskey-shot', NULL, 7000.00, NULL, 8, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2662, 4, 337, 'Don Julio 1942', 'don-julio-1942', NULL, 1100000.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-07-01 20:44:39'),
(2663, 4, 337, 'Clase Azul Reposado', 'clase-azul-reposado', NULL, 950000.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2664, 4, 337, 'Patron Silver', 'patron-silver', NULL, 250000.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-07-04 00:08:44'),
(2665, 4, 337, 'Adictivo Tequila Extra Añejo', 'adictivo-tequila-extra-anejo', NULL, 750000.00, NULL, 4, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2666, 4, 337, 'Casamigos Reposado', 'casamigos-reposado', NULL, 525000.00, NULL, 5, 1, '2026-05-13 01:27:51', '2026-07-01 20:46:17'),
(2667, 4, 337, 'Tequila Shot', 'tequila-shot', NULL, 6500.00, NULL, 6, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2668, 4, 338, 'Hendrick\'s', 'hendricks', NULL, 200000.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-07-01 20:46:55'),
(2669, 4, 338, 'Bombay Sapphire', 'bombay-sapphire', NULL, 100000.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2670, 4, 338, 'Tonino Lamborghini Gin', 'tonino-lamborghini-gin', NULL, 189000.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2671, 4, 338, 'Gin Shot', 'gin-shot', NULL, 7000.00, NULL, 4, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2672, 4, 339, 'Baileys', 'baileys', NULL, 150000.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-07-01 20:48:19'),
(2673, 4, 340, 'Campari', 'campari', NULL, 95000.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2674, 4, 341, 'Bumbu', 'bumbu', NULL, 85500.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2675, 4, 342, 'Château Vartely Sweet Red Wine', 'chateau-vartely-sweet-red-wine', NULL, 90000.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2676, 4, 342, 'Amabile Di Rosa Sweet White', 'amabile-di-rosa-sweet-white-red', NULL, 85000.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2677, 4, 342, 'Shannon Mount Bullet Merlot', 'shannon-mount-bullet-merlot', NULL, 230900.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2678, 4, 342, 'Santa Rita 3 Medallas', 'santa-rita-3-medallas-red', NULL, 51000.00, NULL, 4, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2679, 4, 342, 'Darling Cellar Sweet Rosé', 'darling-cellar-sweet-rose-red', NULL, 85000.00, NULL, 5, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2680, 4, 342, 'Darling Cellars Sweet Red', 'darling-cellars-sweet-red', NULL, 85000.00, NULL, 6, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2681, 4, 342, 'Dona Paula Estate Malbec', 'dona-paula-estate-malbec', NULL, 85000.00, NULL, 7, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2682, 4, 342, 'Ermelinda Vinho Tinto Apostle', 'ermelinda-vinho-tinto-apostle', NULL, 75000.00, NULL, 8, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2683, 4, 342, 'BLABLA Cabernet Sauvignon', 'blabla-cabernet-sauvignon', NULL, 75000.00, NULL, 9, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2684, 4, 342, 'Leopard Leaps', 'leopard-leaps', NULL, 75000.00, NULL, 10, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2685, 4, 343, 'Santa Rita 3 Medallas', 'santa-rita-3-medallas-white', NULL, 65000.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2686, 4, 343, 'Cloudy Bay Sauvignon Blanc', 'cloudy-bay-sauvignon-blanc', NULL, 85500.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2687, 4, 343, 'Fantinel Sun Goddess Pinot', 'fantinel-sun-goddess-pinot', NULL, 94999.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2688, 4, 343, 'Santa Rita 120 Reserva Especial Chardonnay', 'santa-rita-120-reserva-especial-chardonnay', NULL, 75000.00, NULL, 4, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2689, 4, 343, 'Amabile Di Rosa Sweet White', 'amabile-di-rosa-sweet-white-white', NULL, 85000.00, NULL, 5, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2690, 4, 343, 'Château d\'Esclans Whispering Angel Rosé', 'chateau-desclans-whispering-angel-rose', NULL, 75000.00, NULL, 6, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2691, 4, 343, 'Amabile Di Rosa Sweet Rosé', 'amabile-di-rosa-sweet-rose-white', NULL, 75000.00, NULL, 7, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2692, 4, 344, 'Manhattan', 'manhattan', NULL, 13600.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2693, 4, 344, 'Old Fashioned', 'old-fashioned', NULL, 13600.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2694, 4, 344, 'Whiskey Sour', 'whiskey-sour', NULL, 13600.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2695, 4, 344, 'Negroni', 'negroni', NULL, 13600.00, NULL, 4, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2696, 4, 344, 'Gin Basil', 'gin-basil', NULL, 13600.00, NULL, 5, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2697, 4, 344, 'Gin Fizz', 'gin-fizz', NULL, 13600.00, NULL, 6, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2698, 4, 344, 'Margarita', 'margarita', NULL, 13600.00, NULL, 7, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2699, 4, 344, 'Cosmopolitan', 'cosmopolitan', NULL, 13600.00, NULL, 8, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2700, 4, 344, 'Screw Driver', 'screw-driver', NULL, 13600.00, NULL, 9, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2701, 4, 344, 'Mojito', 'mojito', NULL, 13600.00, NULL, 10, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2702, 4, 344, 'Rum Sour', 'rum-sour', NULL, 13600.00, NULL, 11, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2703, 4, 344, 'Daiquiri', 'daiquiri', NULL, 13600.00, NULL, 12, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2704, 4, 344, 'Long Island', 'long-island', NULL, 13600.00, NULL, 13, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2705, 4, 344, 'Pornstar Martini', 'pornstar-martini', NULL, 13600.00, NULL, 14, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2706, 4, 344, 'Mind Twister', 'mind-twister', '(Tequila Based)', 17900.00, NULL, 15, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2707, 4, 344, 'Cody Funk', 'cody-funk', '(Vodka Based)', 14500.00, NULL, 16, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2708, 4, 344, 'Standing Nipple', 'standing-nipple', NULL, 14500.00, NULL, 17, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2709, 4, 345, 'Chapman', 'chapman', NULL, 12500.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-07-11 13:17:35'),
(2710, 4, 345, 'Virgin Margarita', 'virgin-margarita', NULL, 12500.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-07-11 13:24:47'),
(2711, 4, 345, 'Virgin Daiquiri', 'virgin-daiquiri', NULL, 12500.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-07-11 13:25:12'),
(2712, 4, 345, 'Virgin Colada', 'virgin-colada', NULL, 12500.00, NULL, 4, 1, '2026-05-13 01:27:51', '2026-07-11 13:25:39'),
(2713, 4, 345, 'Virgin Mojito', 'virgin-mojito', NULL, 12500.00, NULL, 5, 1, '2026-05-13 01:27:51', '2026-07-11 13:26:06'),
(2714, 4, 346, 'Stout', 'stout', NULL, 4500.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2715, 4, 346, 'Orijin Can', 'orijin-can', NULL, 4500.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2716, 4, 346, 'Gulder', 'gulder', NULL, 4500.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2717, 4, 346, 'Budweiser', 'budweiser', NULL, 4500.00, NULL, 4, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2718, 4, 346, 'Heineken', 'heineken', NULL, 4500.00, NULL, 5, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2719, 4, 347, 'Power Horse', 'power-horse', NULL, 4500.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2720, 4, 348, 'Malta Can', 'malta-can', NULL, 4500.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-07-01 20:49:22'),
(2721, 4, 348, 'Pet Coke', 'pet-coke', NULL, 4500.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-07-01 20:49:34'),
(2722, 4, 348, 'Pet Sprite', 'pet-sprite', NULL, 4500.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-07-01 20:49:43'),
(2723, 4, 348, 'Pet Fanta', 'pet-fanta', NULL, 4500.00, NULL, 4, 1, '2026-05-13 01:27:51', '2026-07-01 20:50:10'),
(2724, 4, 348, 'Can Mojito', 'can-mojito', NULL, 3000.00, NULL, 5, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2725, 4, 348, 'Water', 'water', NULL, 2000.00, NULL, 6, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2726, 4, 348, 'Tonic Water', 'tonic-water', NULL, 4500.00, NULL, 7, 1, '2026-05-13 01:27:51', '2026-07-01 20:49:55'),
(2727, 4, 348, 'Cranberry Juice', 'cranberry-juice', NULL, 18000.00, NULL, 8, 1, '2026-05-13 01:27:51', '2026-07-10 21:52:21'),
(2728, 4, 349, 'Vanilla Milkshake', 'vanilla-milkshake', NULL, 12500.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-07-11 12:31:43'),
(2729, 4, 349, 'Strawberry Milkshake', 'strawberry-milkshake', NULL, 12500.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-07-11 12:32:01'),
(2730, 4, 349, 'Chocolate Milkshake', 'chocolate-milkshake', NULL, 12500.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-07-11 12:32:20'),
(2731, 4, 349, 'Chocy Teaser', 'chocy-teaser', NULL, 15000.00, NULL, 4, 1, '2026-05-13 01:27:51', '2026-07-11 12:32:50'),
(2732, 4, 349, 'Oreo and Waffle', 'oreo-and-waffle', NULL, 15000.00, NULL, 5, 1, '2026-05-13 01:27:51', '2026-07-11 12:33:00'),
(2733, 4, 349, 'Strawberry Gummy', 'strawberry-gummy', NULL, 15000.00, NULL, 6, 1, '2026-05-13 01:27:51', '2026-07-11 12:34:48'),
(2734, 4, 350, 'English Breakfast', 'english-breakfast', 'Eggs, Bacon, Sausages, Baked Beans, Grilled Tomatoes, Toast', 14900.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2735, 4, 350, 'Waffles Delight', 'waffles-delight', 'Crispy Chicken, Waffles, Fried Egg, Green Salad, Syrup', 13900.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2736, 4, 350, 'Buttermilk Blueberry Pancakes', 'buttermilk-blueberry-pancakes', 'Fresh Blueberries, Flour, Eggs, Vanilla Extract. Choice of: Sausages • Bacon • Plain with Syrup', 17500.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2737, 4, 350, 'Popcorn Chicken with French Toast', 'popcorn-chicken-with-french-toast', 'Diced Chicken, French Toast, Fruit Bowl, Whipped Cream', 14900.00, NULL, 4, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2738, 4, 350, 'Turfed Chicken Caesar Salad', 'turfed-chicken-caesar-salad', 'Chicken Breast, Parmesan Croutons, Iceberg Lettuce, Cherry Tomatoes, Salad Dressing', 30500.00, NULL, 5, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2739, 4, 350, 'Philly Omelet', 'philly-omelet', 'Eggs, Pulled Beef, Caramelized Onions, Bell Peppers, Cheese, Chili Sauce. Choice of: Fried Yam • Fried Plantain', 10000.00, NULL, 6, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2740, 4, 350, 'Salmon Skillet', 'salmon-skillet', 'Salmon, Eggs, Bell Peppers, Mozzarella Cheese, Marinara Sauce, Agege French Toast', 25000.00, NULL, 7, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2741, 4, 350, 'Nostalgia Breakfast Burrito', 'nostalgia-breakfast-burrito', 'Tortilla, Scrambled Eggs, Sausages, Bacon, Pulled Beef, Breakfast Potatoes, Lettuce, Caramelized Onions, Cheese, Peppercorn Aioli', 11000.00, NULL, 8, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2742, 4, 350, 'Beef Burger', 'beef-burger-brunch', 'Beef Pattie, Lettuce, Cheese, Tomato Chutney, Caramelized Onions', 15000.00, NULL, 9, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2743, 4, 350, 'Balsamic Braised Lamb Shanks', 'balsamic-braised-lamb-shanks', 'Lamb Shank, Mash Potatoes, Seasonal Vegetables, Balsamic Reduction', 22900.00, NULL, 10, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2744, 4, 350, 'Peri-Peri Jumbo Prawns', 'peri-peri-jumbo-prawns-brunch', 'Chargrilled Jumbo Prawns, Shredded Beef, Salsa, Kelewele, Jollof Rice', 29900.00, NULL, 11, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2745, 4, 350, 'Seafood Pasta', 'seafood-pasta-brunch', 'Creamy Seafood Mix, Linguine, Tomatoes, Herbs, Pesto Baguette', 27900.00, NULL, 12, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2746, 4, 351, 'French Toast', 'french-toast-brunch', NULL, 4000.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2747, 4, 351, 'Waffles', 'waffles-brunch', NULL, 4000.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2748, 4, 351, 'Blueberry Pancakes', 'blueberry-pancakes-brunch', NULL, 4500.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2749, 4, 351, 'Mash Potatoes', 'mash-potatoes-brunch', NULL, 3500.00, NULL, 4, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2750, 4, 351, 'Jollof Rice', 'jollof-rice-brunch', NULL, 3000.00, NULL, 5, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2751, 4, 351, 'French Fries', 'french-fries-brunch', NULL, 3500.00, NULL, 6, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2752, 4, 351, 'Seasonal Vegetables', 'seasonal-vegetables-brunch', NULL, 3500.00, NULL, 7, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2753, 4, 351, 'Kelewele', 'kelewele-brunch', NULL, 3500.00, NULL, 8, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2754, 4, 351, 'Breakfast Extras', 'breakfast-extras', NULL, 2500.00, NULL, 9, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2755, 4, 352, 'Bailey Brownie', 'bailey-brownie-brunch', 'Baileys Irish Liqueur, Espresso Powder, Truffle Chocolate Cake', 7000.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2756, 4, 352, 'Apple Pie', 'apple-pie-brunch', 'Baked Apples, Lemon Butter, Short Crust Pie', 6500.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2757, 4, 352, 'Sticky Toffee Pudding', 'sticky-toffee-pudding', NULL, 6500.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2758, 4, 353, 'Blueberry', 'blueberry', NULL, 39900.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-07-01 21:07:18'),
(2759, 4, 353, 'Chocolate', 'chocolate', NULL, 39900.00, NULL, 2, 1, '2026-05-13 01:27:51', '2026-07-01 21:07:28'),
(2760, 4, 353, 'Cream & Mint', 'cream-mint', NULL, 39900.00, NULL, 3, 1, '2026-05-13 01:27:51', '2026-07-01 21:07:36'),
(2761, 4, 353, 'Double Apple', 'double-apple', NULL, 39900.00, NULL, 4, 1, '2026-05-13 01:27:51', '2026-07-01 21:07:45'),
(2762, 4, 353, 'Grape Flavour', 'grape-flavour', NULL, 39900.00, NULL, 5, 1, '2026-05-13 01:27:51', '2026-07-01 21:07:52');
INSERT INTO `menu_items` (`id`, `restaurant_id`, `category_id`, `name`, `slug`, `description`, `price`, `image`, `display_order`, `is_available`, `created_at`, `updated_at`) VALUES
(2763, 4, 353, 'Lemon & Mint', 'lemon-mint', NULL, 39900.00, NULL, 6, 1, '2026-05-13 01:27:51', '2026-07-01 21:08:02'),
(2764, 4, 353, 'Strawberry', 'strawberry', NULL, 39900.00, NULL, 7, 1, '2026-05-13 01:27:51', '2026-07-01 21:08:10'),
(2765, 4, 353, 'Love 66', 'love-66', NULL, 39900.00, NULL, 8, 1, '2026-05-13 01:27:51', '2026-07-01 21:08:19'),
(2766, 4, 353, 'Watermelon', 'watermelon', NULL, 39900.00, NULL, 9, 1, '2026-05-13 01:27:51', '2026-07-01 21:08:27'),
(2767, 4, 354, 'Extra Flavour', 'extra-flavour', NULL, 12500.00, NULL, 1, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(2768, 19, 355, 'ENGLISH BREAKFAST', 'english-breakfast', 'Eggs (scrambled, fried, boiled or omelet), chicken sausage, Pork bacon, hash brown potato, grilled tomato, baked beans, mushrooms, tomatoes, & brioche toast', 26000.00, NULL, 0, 1, '2026-05-06 00:57:05', '2026-05-06 00:57:35'),
(2769, 19, 355, 'NIGERIAN BREAKFAST', 'nigerian-breakfast', 'Egg sauce cooked your way, Boiled yam or Plantain basket with Nigerian style beans cooked in a tomato sauce and served with a side of chicken sausage', 19400.00, NULL, 0, 1, '2026-05-06 00:58:54', '2026-05-06 00:58:54'),
(2770, 19, 355, 'ENERGY BREAKFAST', 'energy-breakfast', 'Fresh yogurt, an assortment of berries, a banana, and dry almonds served with honey', 13000.00, NULL, 0, 1, '2026-05-06 01:00:38', '2026-05-06 01:00:38'),
(2771, 19, 356, 'MAPLE SYRUP', 'maple-syrup', '3 buttermilk pancakes, maple syrup, fruits, and caramel sauce.', 8600.00, NULL, 0, 1, '2026-05-06 01:04:08', '2026-05-06 01:04:08'),
(2772, 19, 356, 'CHOCOLATE BANANA', 'chocolate-banana', '3 Buttermilk pancake, Nutella chocolate spread, banana, roasted hazelnuts.', 13500.00, NULL, 0, 1, '2026-05-06 01:06:41', '2026-05-06 01:06:41'),
(2773, 19, 356, 'BREAKFAST SANDWISH', 'breakfast-sandwish', '3 buttermilk pancake, Chicken sausage, cheddar cheese, bacon, scrambled egg.', 16000.00, NULL, 0, 1, '2026-05-06 01:38:39', '2026-05-06 01:38:39'),
(2774, 19, 356, 'LITE BUTTERMILK', 'lite-buttermilk', '1 buttermilk pancake, chicken sausage, bacon, scrambled egg, mixed cheese, side salad served with balsamic vinegar sauce & maple syrup', 17000.00, NULL, 0, 1, '2026-05-06 01:40:31', '2026-05-06 01:40:31'),
(2775, 19, 357, 'PLAIN', 'plain', 'Crispy waffle dusted with icing sugar, served with caramel', 5900.00, NULL, 0, 1, '2026-05-06 01:56:04', '2026-05-06 01:56:04'),
(2776, 19, 357, 'BERRIES AND VANILLA ICE CREAM', 'berries-and-vanilla-ice-cream', 'Homemade crispy waffle, assorted berries served with a scoop of vanilla ice cream and chocolate sauce,', 15000.00, NULL, 0, 1, '2026-05-06 01:56:41', '2026-05-06 01:56:41'),
(2777, 19, 357, 'CHOCOLATE BANANA', 'chocolate-banana', 'Homemade crispy waffle, banana, Nutella chocolate, whipped cream, and caramel', 13500.00, NULL, 0, 1, '2026-05-06 01:57:40', '2026-05-06 01:57:40'),
(2778, 19, 357, 'CHICKEN AND WAFFLE SANDWISH', 'chicken-and-waffle-sandwish', 'Homemade crispy waffle topped with crispy fried chicken breast served with secret sauce', 12000.00, NULL, 0, 1, '2026-05-06 01:58:26', '2026-05-06 01:58:26'),
(2779, 19, 357, 'Waffle and egg', 'waffle-and-egg', 'Homemade crispy waffle topped with scrambled eggs', 14900.00, NULL, 0, 1, '2026-05-06 01:59:24', '2026-05-06 01:59:24'),
(2780, 19, 358, 'CLASSIC BENEDICT', 'classic-benedict', 'Served with hollandaise sauce', 10000.00, NULL, 0, 1, '2026-05-06 02:00:32', '2026-05-06 02:00:32'),
(2781, 19, 358, 'SPANISH OMELET', 'spanish-omelet', 'Onions, tomato, green pepper, spring onions, brioche toast, and butter', 13500.00, NULL, 0, 1, '2026-05-06 02:02:16', '2026-05-06 02:02:16'),
(2782, 19, 358, 'OMELET NATURE', 'omelet-nature', '', 9000.00, NULL, 0, 1, '2026-05-06 02:02:51', '2026-05-06 02:02:51'),
(2783, 19, 358, 'SUNNY SIDE UP', 'sunny-side-up', '', 9000.00, NULL, 0, 1, '2026-05-06 02:03:11', '2026-05-06 02:03:11'),
(2784, 19, 358, 'SCRAMBLED', 'scrambled', '', 9000.00, NULL, 0, 1, '2026-05-06 02:04:33', '2026-05-06 02:04:33'),
(2785, 19, 359, 'CRISPY CHICKEN WINGS', 'crispy-chicken-wings', 'Seasoned deep-fried chicken wings, fried and served with your choice of BBQ or chili sauce.', 17000.00, NULL, 0, 1, '2026-05-06 02:06:18', '2026-05-06 02:06:18'),
(2786, 19, 360, 'Beetroot Salad', 'fm-salads-beetroot-salad', 'Poached beetroot, goat cheese, caramelized walnuts, mixed greens, and Roman lettuce served with orange vinaigrette sauce', 18000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2787, 19, 360, 'Caeser Salad Chicken', 'fm-salads-caeser-salad-chicken', 'Romaine lettuce, Parmesan cheese & garlic croutons tossed in our Caesar sauce.', 16300.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2788, 19, 360, 'Salmon Salad', 'fm-salads-salmon-salad', 'Roman lettuce & mix mesclun, cherry tomato, edamame, walnuts, radish, avocado, mango, sesame seeds & sweet chili sauce.', 18900.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2789, 19, 360, 'Cobb Salad', 'fm-salads-cobb-salad', 'Iceberg, avocado, tomato, and cucumber topped with chicken tender strips served with ranch sauce.', 19200.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2790, 19, 360, 'Seafood Salad', 'fm-salads-seafood-salad', 'Tender shrimp, calamari, smoked salmon, potatoes, olives, tomatoes, and lettuce served with lemon mustard.', 23400.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2791, 19, 360, 'Greek Salad', 'fm-salads-greek-salad', 'Roman lettuce, cucumber, green pepper, tomato, red onions, oregano, feta cheese, and rocket leaves served with lemon mustard sauce.', 18900.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2792, 19, 361, 'Hummus With Meat', 'fm-appetizers-hummus-with-meat', 'Chickpea puree with tahini sauce, served with crispy pitta bread', 14000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2793, 19, 361, 'Calamari Ring', 'fm-appetizers-calamari-ring', 'Deep-fried calamari rings, served in tartar sauce', 18000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2794, 19, 361, 'Shrimp Spring Rolls', 'fm-appetizers-shrimp-spring-rolls', 'Marinated shrimp wrapped and fried in crispy rolls served with tartar & cocktail sauce', 15000.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2795, 19, 361, 'Chicken Wings Your Way', 'fm-appetizers-chicken-wings-your-way', 'Deep fried chicken wings with choice of sauce: chili sauce, BBQ sauce, Suya spice', 14500.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2796, 19, 361, 'B.B.Q Chicken Skewers', 'fm-appetizers-b-b-q-chicken-skewers', 'Marinated chicken strips, grilled, glazed with BBQ sauce.', 14000.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2797, 19, 361, 'Imperial Fried Spring Roll', 'fm-appetizers-imperial-fried-spring-roll', 'Rolled in rice paper, deep fried, minced beef meat, rice vermicelli, carrot, and shitake mushrooms, served with homemade Asian dip.', 20300.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2798, 19, 361, 'Butterfly Shrimp', 'fm-appetizers-butterfly-shrimp', 'Breaded deep-fried shrimps served with homemade cocktail dip and tartar sauce', 18000.00, NULL, 7, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2799, 19, 361, 'Chicken Taco', 'fm-appetizers-chicken-taco', 'Marinated chicken, avocado, fried onions & iceberg.', 16500.00, NULL, 8, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2800, 19, 361, 'Seafood Platter', 'fm-appetizers-seafood-platter', 'Fried calamari ring, shrimp spring roll, fish finger & prawns suya, served with tartar sauce, sweet chili', 37400.00, NULL, 9, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2801, 19, 361, 'Nigerian Platter', 'fm-appetizers-nigerian-platter', 'Spicy gizzards & snails, chicken suya, yam fingers & plantain, fresh tomato, red crispy onions served with chili sauce.', 33000.00, NULL, 10, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2802, 19, 361, 'Appetizer Plater', 'fm-appetizers-appetizer-plater', 'Chicken tender, fried calamari, chicken wings, yam finger, fried spring rolls, fried panzerotti, served with tartar sauce, cocktail, sweet chili, honey mustard, Thai dips.', 37400.00, NULL, 11, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2803, 19, 361, 'Suya', 'fm-appetizers-suya', 'Your choice: beef, shrimp, or chicken. Coated with suya pepper and served with tomato and onions.', 15700.00, NULL, 12, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2804, 19, 361, 'Asun', 'fm-appetizers-asun', 'Goat meat served with sautéed green pepper, onions & tomato chili sauce', 15700.00, NULL, 13, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2805, 19, 362, 'Chicken Burger', 'fm-burgers-chicken-burger', 'Marinated grilled chicken breast with cheddar cheese, Roman lettuce, pickles, tomato, grilled onions, aioli & garlic mayo sauce. Served with french fries and coleslaw.', 20400.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2806, 19, 362, 'Classic Beef Burger', 'fm-burgers-classic-beef-burger', 'Premium beef with cheddar cheese, Roman lettuce, pickles, jalapenos, grilled fresh mushroom & onions, avocado served with sriracha cocktail sauce. Served with french fries and coleslaw.', 22600.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2807, 19, 362, 'Opal Beef Burger', 'fm-burgers-opal-beef-burger', 'Premium beef 170g with cheddar cheese, onion rings, crispy beef bacon, lettuce, and caramelized onion served with classic & sweet chili sauce. Served with french fries and coleslaw.', 24300.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2808, 19, 363, 'Creamy Potato Mash', 'fm-sides-creamy-potato-mash', NULL, 6500.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2809, 19, 363, 'French Fries', 'fm-sides-french-fries', NULL, 5000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2810, 19, 363, 'Steam Rice', 'fm-sides-steam-rice', NULL, 4500.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2811, 19, 363, 'Seafood Fried Rice', 'fm-sides-seafood-fried-rice', NULL, 12000.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2812, 19, 363, 'Wedges Potato', 'fm-sides-wedges-potato', NULL, 5000.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2813, 19, 363, 'Sauteed Vegetables', 'fm-sides-sauteed-vegetables', NULL, 6000.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2814, 19, 363, 'Singaporean Fried Rice', 'fm-sides-singaporean-fried-rice', NULL, 6500.00, NULL, 7, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2815, 19, 363, 'Yam Fries', 'fm-sides-yam-fries', NULL, 7000.00, NULL, 8, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2816, 19, 363, 'Fried Plantain', 'fm-sides-fried-plantain', NULL, 7000.00, NULL, 9, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2817, 19, 364, 'Tozo Bao', 'fm-bao-tozo-bao', 'Pulled BBQ tozo beef, fluffy steamed bao buns, Roman lettuce, chopped spring onions, and toasted sesame seeds.', 15600.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2818, 19, 364, 'Duck Bao', 'fm-bao-duck-bao', 'Smoked pulled duck in an orange cocktail, fluffy steamed bao buns, chopped spring onions, pickle carrot, sesame seed.', 16000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2819, 19, 364, 'Shrimp Bao', 'fm-bao-shrimp-bao', 'Crispy battered deep-fried shrimp, fluffy steamed bao, sriracha spicy cocktail, cucumber pickles, spring onion, sesame seed', 18000.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2820, 19, 365, 'Whole Grill Fish', 'fm-main-course-whole-grill-fish', 'Grilled whole fish marinated in African sauce, sautéed vegetables served with fried yam and plantain', 32300.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2821, 19, 365, 'Grill Chicken', 'fm-main-course-grill-chicken', 'African-style marinated half-grilled chicken, fried plantain rice, spicy tomato sauce African way', 27000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2822, 19, 365, 'Seafood Skewer', 'fm-main-course-seafood-skewer', 'Prawns, fresh salmon, fish filet, bell pepper served with steamed vegetables & grilled potato.', 45100.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2823, 19, 365, 'Jumbo Prawns', 'fm-main-course-jumbo-prawns', 'Marinated grilled jumbo prawns, served with yam chips, steamed vegetables & martini sauce.', 45200.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2824, 19, 365, 'Baked Salmon', 'fm-main-course-baked-salmon', 'Marinated salmon filet served with spinach, edamame, asparagus, mashed potato & martini sauce', 53700.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2825, 19, 365, 'Chicken Roulade', 'fm-main-course-chicken-roulade', 'Crispy fried chicken stuffed with mozzarella cheese and turkey ham, grilled vegetables served with mushroom mustard sauce', 30800.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2826, 19, 365, 'Artichoke Escalope', 'fm-main-course-artichoke-escalope', 'Deep fried breaded chicken breast topped with creamy sauce and mozzarella cheese served with red sauce', 28400.00, NULL, 7, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2827, 19, 365, 'Seabass', 'fm-main-course-seabass', 'Steamed fish served with spinach, mashed potato & lemon grass sauce.', 55700.00, NULL, 8, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2828, 19, 366, 'Carbonara', 'fm-pastas-carbonara', 'Spaghetti pasta, creamy sauce, your choice of bacon or smoked turkey, parmesan cheese', 24800.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2829, 19, 366, 'Tagliatelle Alfredo', 'fm-pastas-tagliatelle-alfredo', 'Fettuccini, creamy sauce, chicken breast, mixed mushrooms, parmesan cheese', 22600.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2830, 19, 366, 'Creamy Cajun Penne', 'fm-pastas-creamy-cajun-penne', 'Penne pasta, tomato sauce, creamy sauce, chicken breast, mushroom, Cajun spice, parmesan cheese', 29300.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2831, 19, 366, 'Penne Arrabbiata', 'fm-pastas-penne-arrabbiata', 'Penne pasta, spicy tomato sauce, black olive, basil, cherry tomato & parmesan cheese', 18700.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2832, 19, 366, 'Shrimp Linguini', 'fm-pastas-shrimp-linguini', 'Linguini pasta, tomato sauce, cherry tomato, basil, bisque sauce & parmesan cheese.', 30400.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2833, 19, 366, 'Seafood Pastahroom', 'fm-pastas-seafood-pastahroom', 'Mix marinated prawns, calamari, mussels, basil, cherry tomato, Parmesan cheese and homemade tomato sauce.', 36700.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2834, 19, 367, 'Margherita', 'fm-pizza-margherita', 'Homemade tomato sauce, mozzarella cheese, fresh basil', 13000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2835, 19, 367, 'Chicken Fungi Pizza', 'fm-pizza-chicken-fungi-pizza', 'Homemade tomato sauce topped with mixed mushrooms and cheese, grilled chicken breast, truffle oil & basil.', 18000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2836, 19, 367, 'Vegetarian Pizza', 'fm-pizza-vegetarian-pizza', 'Homemade tomato sauce topped with mixed mushrooms and cheese, grilled vegetables, truffle oil & basil', 15400.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2837, 19, 367, 'Pepperoni Pizza', 'fm-pizza-pepperoni-pizza', 'Beef pepperoni, homemade tomato sauce, mixed cheese', 17900.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2838, 19, 367, 'Seafood Pizza', 'fm-pizza-seafood-pizza', 'BBQ sauce, calamari, shrimp, octopus, and fresh pineapple topped with mixed cheese, basil', 23600.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2839, 19, 367, 'Smocked Salmon Pizza', 'fm-pizza-smocked-salmon-pizza', 'Cream cheese topped with smoked salmon and mixed cheese, rocket leaves, and capers.', 29500.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2840, 19, 368, 'Grilled T-Bone Steak', 'fm-meats-grilled-t-bone-steak', 'Grilled T-bone steak, grilled vegetables, wedges, and potatoes served with mushroom sauce', 40000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2841, 19, 368, 'Mongolian Beef', 'fm-meats-mongolian-beef', 'Sliced imported beef filet, sautéed in homemade Mongolian sauce served with Singaporean fried rice.', 42000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2842, 19, 368, 'Ribb Eye Steak', 'fm-meats-ribb-eye-steak', 'Grilled imported rib eye, sautéed vegetables, and sweet mashed potato', 40000.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2843, 19, 368, 'Grilled Tozo', 'fm-meats-grilled-tozo', 'Marinated smoked beef cut served with leeks garlic creamy sauce and mashed potato', 26000.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2844, 19, 368, 'Short Ribs', 'fm-meats-short-ribs', 'Slow braised beef short ribs, sticky buffalo glazed, served with creamy mash potato & glaze spicy carrot', 52000.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2845, 19, 368, 'Braised Lamb Shank', 'fm-meats-braised-lamb-shank', 'Braised and marinated to perfection served with green beans, mashed potato & pepper sauce', 49000.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2846, 19, 368, 'Lamb Chops', 'fm-meats-lamb-chops', 'Grilled imported lamb chops topped with garlic herbs sauce served with sautéed vegetables, jollof rice & pepper sauce.', 55900.00, NULL, 7, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2847, 19, 369, 'Opal Platter', 'fm-opal-platter-opal-platter', 'Spicy BBQ wings, yaji prawn skewer, grilled beef slider, peppered snails, plantain slices, french fries', 85000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2848, 19, 369, 'South American Style Grilled Platter', 'fm-opal-platter-south-american-style-grilled-platter', 'Serve up to 4: rib eye, lamb chops, duck breast, chicken breast, and grilled vegetables served with chimichurri, teriyaki, and BBQ sauces.', 150000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2849, 19, 370, 'Chicken Tacos', 'fm-tacos-menu-chicken-tacos', 'Choice of hard or soft shell served with salsa and lemon', 18000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2850, 19, 370, 'Minced Beef Tacos', 'fm-tacos-menu-minced-beef-tacos', 'Choice of hard or soft shell served with salsa and lemon', 17000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2851, 19, 370, 'Shrimps Tacos', 'fm-tacos-menu-shrimps-tacos', 'Choice of hard or soft shell served with salsa and lemon', 23500.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2852, 19, 370, 'Hawaian Fish Tacos', 'fm-tacos-menu-hawaian-fish-tacos', 'Choice of hard or soft shell served with salsa and lemon', 20500.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2853, 19, 370, 'Tacos Platter', 'fm-tacos-menu-tacos-platter', 'Choice of hard or soft shell served with salsa and lemon', 45000.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2854, 19, 370, 'Chiken Tacos Salad Bowl', 'fm-tacos-menu-chiken-tacos-salad-bowl', 'Crispy lettuce, salsa, guacamole, mixed cheese and jalapeno, served with BBQ sauce', 13800.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2855, 19, 370, 'Shrimps Tacos Salad Bowl', 'fm-tacos-menu-shrimps-tacos-salad-bowl', 'Crispy lettuce, salsa, guacamole, mixed cheese and jalapeno, served with BBQ sauce', 18200.00, NULL, 7, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2856, 19, 370, 'Dynamite Shrimp', 'fm-tacos-menu-dynamite-shrimp', 'Crispy fried shrimp coated in a spicy mayonnaise dressing', 18500.00, NULL, 8, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2857, 19, 370, 'Crispy Chicken Wings', 'fm-tacos-menu-crispy-chicken-wings', 'Marinated chicken wings served with BBQ and sweet chili dip', 18500.00, NULL, 9, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2858, 19, 370, 'Fully Loaded Nachos', 'fm-tacos-menu-fully-loaded-nachos', 'Served with salsa, guacamole, sour cream, and melted cheddar cheese topped with jalapeño', 15600.00, NULL, 10, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2859, 19, 370, 'Chicken Quesadillas', 'fm-tacos-menu-chicken-quesadillas', 'Served with salsa, guacamole, and sour cream', 16000.00, NULL, 11, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2860, 19, 370, 'Beef Quesadillas', 'fm-tacos-menu-beef-quesadillas', 'Served with salsa, guacamole, and sour cream', 18000.00, NULL, 12, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2861, 19, 370, 'Shrimps Quesadillas', 'fm-tacos-menu-shrimps-quesadillas', 'Served with salsa, guacamole, and sour cream', 30000.00, NULL, 13, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2862, 19, 370, 'Chicken Fajita Wrap', 'fm-tacos-menu-chicken-fajita-wrap', 'Served with salsa and french fries', 18500.00, NULL, 14, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2863, 19, 370, 'Beef Fajita Wrap', 'fm-tacos-menu-beef-fajita-wrap', 'Served with salsa and french fries', 18500.00, NULL, 15, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2864, 19, 370, 'Shrimps Fajita Wrap', 'fm-tacos-menu-shrimps-fajita-wrap', 'Served with salsa and french fries', 27400.00, NULL, 16, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2865, 19, 370, 'Dynamite Chicken', 'fm-tacos-menu-dynamite-chicken', 'Crispy golden brown fried chicken served with dynamite sauce', 16800.00, NULL, 17, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2866, 19, 370, 'Nachos', 'fm-tacos-menu-nachos', 'Served with salsa and guacamole topped with jalapeño', 14000.00, NULL, 18, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2867, 19, 370, 'French Fries', 'fm-tacos-menu-french-fries', 'Tacos menu portion', 6000.00, NULL, 19, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2868, 19, 371, 'Chocolate Fondant', 'fm-dessert-chocolate-fondant', 'Served with vanilla ice cream.', 9000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2869, 19, 371, 'Ice Cream Scoop', 'fm-dessert-ice-cream-scoop', 'Chocolate, vanilla and extra', 7000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2870, 19, 371, 'Strawberry Cheese Cake', 'fm-dessert-strawberry-cheese-cake', NULL, 14000.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2871, 19, 371, 'Mixed Fruit & Vanille Ice Cream', 'fm-dessert-mixed-fruit-and-vanille-ice-cream', NULL, 9000.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2872, 19, 372, 'The Spotlight', 'fm-karaoke-food-the-spotlight', '3–5 people: one bottle of wine (red or white), complimentary juice, royalty platter (chicken wings, spicy gizzard & snail, mini slider, french fries)', 100000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2873, 19, 372, 'The Headliner', 'fm-karaoke-food-the-headliner', '3–7 people: one bottle of wine (red or white), complimentary juice, stove hok platter (chicken wings, spicy gizzard & snail, mini slider, suya, yam & plantain fries)', 150000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2874, 19, 372, 'The Legend', 'fm-karaoke-food-the-legend', 'Max 10 people: two bottles of wine (red or white), complimentary juice, tropical platter (chicken wings, spicy gizzard & snail, mini slider, spring rolls, yam & plantain fries)', 200000.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2875, 19, 373, 'Ace of Spades Brut', 'dm-champagne-ace-of-spades-brut', NULL, 1200000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2876, 19, 373, 'Dom Perignon Brut', 'dm-champagne-dom-perignon-brut', NULL, 1100000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2877, 19, 373, 'Dom Perignon Rose', 'dm-champagne-dom-perignon-rose', NULL, 1300000.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2878, 19, 373, 'Laurent Perrier Demi Sec', 'dm-champagne-laurent-perrier-demi-sec', NULL, 260000.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2879, 19, 373, 'Laurent Perrier Brut', 'dm-champagne-laurent-perrier-brut', NULL, 240000.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2880, 19, 373, 'Laurent Perrier Cuvee Rose', 'dm-champagne-laurent-perrier-cuvee-rose', NULL, 350000.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2881, 19, 373, 'Moet Brut', 'dm-champagne-moet-brut', NULL, 250000.00, NULL, 7, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2882, 19, 373, 'Moet Imperial Ice', 'dm-champagne-moet-imperial-ice', NULL, 400000.00, NULL, 8, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2883, 19, 373, 'Moet Nectar Rose', 'dm-champagne-moet-nectar-rose', NULL, 360000.00, NULL, 9, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2884, 19, 373, 'Moet Nectar Imperial', 'dm-champagne-moet-nectar-imperial', NULL, 380000.00, NULL, 10, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2885, 19, 373, 'Ruinart Blanc de Blanc', 'dm-champagne-ruinart-blanc-de-blanc', NULL, 400000.00, NULL, 11, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2886, 19, 373, 'Ruinart Brut', 'dm-champagne-ruinart-brut', NULL, 210000.00, NULL, 12, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2887, 19, 373, 'Veuve Cliquot Brut', 'dm-champagne-veuve-cliquot-brut', NULL, 320000.00, NULL, 13, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2888, 19, 373, 'Veuve Cliquot Rich', 'dm-champagne-veuve-cliquot-rich', NULL, 450000.00, NULL, 14, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2889, 19, 373, 'Veuve Cliquot Rose', 'dm-champagne-veuve-cliquot-rose', NULL, 370000.00, NULL, 15, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2890, 19, 373, 'Pol Roger Brut', 'dm-champagne-pol-roger-brut', NULL, 400000.00, NULL, 16, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2891, 19, 373, 'Pol Roger Rose', 'dm-champagne-pol-roger-rose', NULL, 450000.00, NULL, 17, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2892, 19, 373, 'Crystal', 'dm-champagne-crystal', NULL, 1000000.00, NULL, 18, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2893, 19, 373, 'Luc Belaire Rose Fantome', 'dm-champagne-luc-belaire-rose-fantome', NULL, 150000.00, NULL, 19, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2894, 19, 373, 'Luc Belaire Rose', 'dm-champagne-luc-belaire-rose', NULL, 150000.00, NULL, 20, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2895, 19, 373, 'Carnard Duchene Demi Sec', 'dm-champagne-carnard-duchene-demi-sec', NULL, 160000.00, NULL, 21, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2896, 19, 373, 'Carnard Duchene Brut', 'dm-champagne-carnard-duchene-brut', NULL, 190000.00, NULL, 22, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2897, 19, 373, 'Carnard Duchene Rose', 'dm-champagne-carnard-duchene-rose', NULL, 250000.00, NULL, 23, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2898, 19, 374, 'Casamigos Magnum', 'dm-tequila-casamigos-magnum', NULL, 1000000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2899, 19, 374, 'Casamigos Reposado', 'dm-tequila-casamigos-reposado', NULL, 500000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2900, 19, 374, 'Casamigos Reposado 1LTR', 'dm-tequila-casamigos-reposado-1ltr', NULL, 700000.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2901, 19, 374, 'Casamigos Anejo', 'dm-tequila-casamigos-anejo', NULL, 400000.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2902, 19, 374, 'Clase Azul Reposado', 'dm-tequila-clase-azul-reposado', NULL, 800000.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2903, 19, 374, 'Clase Azul Plata Jalisco', 'dm-tequila-clase-azul-plata-jalisco', NULL, 420000.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2904, 19, 374, 'Magnum Don Julio 1942', 'dm-tequila-magnum-don-julio-1942', NULL, 2000000.00, NULL, 7, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2905, 19, 374, 'Don Julio 1942', 'dm-tequila-don-julio-1942', NULL, 1000000.00, NULL, 8, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2906, 19, 374, 'Don Julio Reposado', 'dm-tequila-don-julio-reposado', NULL, 500000.00, NULL, 9, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2907, 19, 374, 'Volcan Anejo', 'dm-tequila-volcan-anejo', NULL, 300000.00, NULL, 10, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2908, 19, 374, 'Avion Tequila', 'dm-tequila-avion-tequila', NULL, 780000.00, NULL, 11, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2909, 19, 374, 'Volcan Cristalino', 'dm-tequila-volcan-cristalino', NULL, 350000.00, NULL, 12, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2910, 19, 374, 'Casa Maestri', 'dm-tequila-casa-maestri', NULL, 350000.00, NULL, 13, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2911, 19, 374, '1800 Silver', 'dm-tequila-1800-silver', NULL, 250000.00, NULL, 14, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2912, 19, 374, '1800 Reposado', 'dm-tequila-1800-reposado', NULL, 300000.00, NULL, 15, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2913, 19, 374, '1800 Anejo', 'dm-tequila-1800-anejo', NULL, 350000.00, NULL, 16, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2914, 19, 374, 'Adiccion Reposado', 'dm-tequila-adiccion-reposado', NULL, 750000.00, NULL, 17, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2915, 19, 374, 'Adiccion Anejo', 'dm-tequila-adiccion-anejo', NULL, 950000.00, NULL, 18, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2916, 19, 375, 'Hennessy VSOP', 'dm-cognac-hennessy-vsop', NULL, 350000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2917, 19, 375, 'Hennessy X.O', 'dm-cognac-hennessy-x-o', NULL, 1000000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2918, 19, 375, 'Martel Bleu Swift', 'dm-cognac-martel-bleu-swift', NULL, 320000.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2919, 19, 375, 'Martel XO', 'dm-cognac-martel-xo', NULL, 850000.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2920, 19, 375, 'Remy Martin VS', 'dm-cognac-remy-martin-vs', NULL, 290000.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2921, 19, 375, 'Remy Martin VSOP', 'dm-cognac-remy-martin-vsop', NULL, 290000.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2922, 19, 375, 'Remy Martin 1738', 'dm-cognac-remy-martin-1738', NULL, 320000.00, NULL, 7, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2923, 19, 375, 'Remy Martin XO', 'dm-cognac-remy-martin-xo', NULL, 810000.00, NULL, 8, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2924, 19, 375, 'Remy Martin XO Night', 'dm-cognac-remy-martin-xo-night', NULL, 575000.00, NULL, 9, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2925, 19, 376, 'Gin Mare', 'dm-vodka-gin-mare', NULL, 130000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2926, 19, 376, 'Hendricks', 'dm-vodka-hendricks', NULL, 150000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2927, 19, 376, 'Pedro', 'dm-vodka-pedro', NULL, 170000.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2928, 19, 377, 'Beluga Gold Line', 'dm-rum-beluga-gold-line', NULL, 166000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2929, 19, 377, 'Belverde', 'dm-rum-belverde', NULL, 200000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2930, 19, 377, 'Grey Goose', 'dm-rum-grey-goose', NULL, 130000.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2931, 19, 377, 'Eminente Rum', 'dm-rum-eminente-rum', NULL, 210000.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2932, 19, 378, 'Baccardi White', 'dm-gin-baccardi-white', NULL, 90000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2933, 19, 378, 'Coro Coro Spiced', 'dm-gin-coro-coro-spiced', NULL, 100000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2934, 19, 379, 'Chivas 15', 'dm-whisky-chivas-15', NULL, 200000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2935, 19, 379, 'Chivas 18', 'dm-whisky-chivas-18', NULL, 270000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2936, 19, 379, 'Dalmore 12', 'dm-whisky-dalmore-12', NULL, 250000.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2937, 19, 379, 'Glenfiddich 12', 'dm-whisky-glenfiddich-12', NULL, 200000.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2938, 19, 379, 'Glenfiddich 15', 'dm-whisky-glenfiddich-15', NULL, 280000.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2939, 19, 379, 'Glenfiddich 18', 'dm-whisky-glenfiddich-18', NULL, 350000.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2940, 19, 379, 'Glenfiddich 21', 'dm-whisky-glenfiddich-21', NULL, 950000.00, NULL, 7, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2941, 19, 379, 'Glenfiddich 23', 'dm-whisky-glenfiddich-23', NULL, 1150000.00, NULL, 8, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2942, 19, 379, 'Glenfiddich 26', 'dm-whisky-glenfiddich-26', NULL, 2100000.00, NULL, 9, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2943, 19, 379, 'Glenmorangie 10 Original', 'dm-whisky-glenmorangie-10-original', NULL, 190000.00, NULL, 10, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2944, 19, 379, 'Glenmorangie 18 Yrs Extreme', 'dm-whisky-glenmorangie-18-yrs-extreme', NULL, 300000.00, NULL, 11, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2945, 19, 379, 'Glenmorangie Signet', 'dm-whisky-glenmorangie-signet', NULL, 700000.00, NULL, 12, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2946, 19, 379, 'Glenlivet 18yrs', 'dm-whisky-glenlivet-18yrs', NULL, 320000.00, NULL, 13, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2947, 19, 379, 'Glenlivet 15yrs', 'dm-whisky-glenlivet-15yrs', NULL, 280000.00, NULL, 14, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2948, 19, 379, 'Johnny Walker Blue Label', 'dm-whisky-johnny-walker-blue-label', NULL, 860000.00, NULL, 15, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2949, 19, 379, 'Johnnie Walker 18 Years', 'dm-whisky-johnnie-walker-18-years', NULL, 360000.00, NULL, 16, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2950, 19, 379, 'Macallan Double', 'dm-whisky-macallan-double', NULL, 468000.00, NULL, 17, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2951, 19, 379, 'Macallan Rare Cask', 'dm-whisky-macallan-rare-cask', NULL, 750000.00, NULL, 18, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2952, 19, 379, 'Macallan 12 Yrs', 'dm-whisky-macallan-12-yrs', NULL, 320000.00, NULL, 19, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2953, 19, 379, 'Macallan 15 Yrs', 'dm-whisky-macallan-15-yrs', NULL, 500000.00, NULL, 20, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2954, 19, 379, 'Macallan 18 Yrs', 'dm-whisky-macallan-18-yrs', NULL, 780000.00, NULL, 21, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2955, 19, 379, 'Monkey Shoulder', 'dm-whisky-monkey-shoulder', NULL, 150000.00, NULL, 22, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2956, 19, 379, 'Smokey Monkey', 'dm-whisky-smokey-monkey', NULL, 150000.00, NULL, 23, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2957, 19, 380, 'Chateau du Pape', 'dm-red-wine-chateau-du-pape', NULL, 100000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2958, 19, 380, 'Chateau Giscours 2014', 'dm-red-wine-chateau-giscours-2014', NULL, 95500.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2959, 19, 380, 'Chateau Giscours Cazauviel 2017', 'dm-red-wine-chateau-giscours-cazauviel-2017', NULL, 90500.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2960, 19, 380, 'Chocolate Block 2019', 'dm-red-wine-chocolate-block-2019', NULL, 95500.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2961, 19, 380, 'Clarendelle Red 2015', 'dm-red-wine-clarendelle-red-2015', NULL, 80000.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2962, 19, 380, 'Mounton Cardet', 'dm-red-wine-mounton-cardet', NULL, 95000.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2963, 19, 380, 'Pascal Jolivet Sancerre Pinot Noir 2019', 'dm-red-wine-pascal-jolivet-sancerre-pinot-noir-2019', NULL, 110000.00, NULL, 7, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2964, 19, 380, 'Saint Emmilion 2017', 'dm-red-wine-saint-emmilion-2017', NULL, 100000.00, NULL, 8, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2965, 19, 380, 'Georges Duboeuf Cote du Rhones', 'dm-red-wine-georges-duboeuf-cote-du-rhones', NULL, 60000.00, NULL, 9, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2966, 19, 380, 'Santa Christina Maestrelle', 'dm-red-wine-santa-christina-maestrelle', NULL, 90000.00, NULL, 10, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2967, 19, 380, 'Terrazaz Mailec 2018', 'dm-red-wine-terrazaz-mailec-2018', NULL, 80000.00, NULL, 11, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2968, 19, 380, 'Tignanello di Anti 2017', 'dm-red-wine-tignanello-di-anti-2017', NULL, 290000.00, NULL, 12, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2969, 19, 381, 'Diemersdal Sauvignon Blanc', 'dm-rose-wine-diemersdal-sauvignon-blanc', NULL, 80000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2970, 19, 381, 'Georges Duboeuf Cote Du Rhones Sauvignon Blanc', 'dm-rose-wine-georges-duboeuf-cote-du-rhones-sauvignon-blanc', NULL, 65000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2971, 19, 381, 'Henri Bourgeois Les Baronnes Sancerre', 'dm-rose-wine-henri-bourgeois-les-baronnes-sancerre', NULL, 110000.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2972, 19, 381, 'Cigalus Blanc 2019', 'dm-rose-wine-cigalus-blanc-2019', NULL, 200000.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2973, 19, 381, 'Clarendelle White 2019', 'dm-rose-wine-clarendelle-white-2019', NULL, 85000.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2974, 19, 381, 'Escodorojo', 'dm-rose-wine-escodorojo', NULL, 85000.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2975, 19, 381, 'Pascal Jolivet Sauvage', 'dm-rose-wine-pascal-jolivet-sauvage', NULL, 110000.00, NULL, 7, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2976, 19, 381, 'Pascal Jolivet Polly Fume', 'dm-rose-wine-pascal-jolivet-polly-fume', NULL, 110000.00, NULL, 8, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2977, 19, 381, 'Thomas Barton Graves Blanc', 'dm-rose-wine-thomas-barton-graves-blanc', NULL, 65000.00, NULL, 9, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2978, 19, 382, 'Clarendelle Rose', 'dm-white-wine-clarendelle-rose', NULL, 75000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2979, 19, 382, 'Whispering Angel', 'dm-white-wine-whispering-angel', NULL, 95000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2980, 19, 382, 'Pascal Jolivet Sauvage', 'dm-white-wine-pascal-jolivet-sauvage-white', NULL, 110000.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2981, 19, 383, 'Heineken', 'dm-beer-heineken', NULL, 6000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2982, 19, 383, 'Guinness', 'dm-beer-guinness', NULL, 6000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2983, 19, 384, 'Water Large', 'dm-non-alcohol-water-large', NULL, 4500.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2984, 19, 384, 'Water Small', 'dm-non-alcohol-water-small', NULL, 2800.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2985, 19, 384, 'Coke', 'dm-non-alcohol-coke', NULL, 3800.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2986, 19, 384, 'Fanta', 'dm-non-alcohol-fanta', NULL, 3800.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2987, 19, 384, 'Sprite', 'dm-non-alcohol-sprite', NULL, 3800.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2988, 19, 384, 'Ginger Ale', 'dm-non-alcohol-ginger-ale', NULL, 5500.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2989, 19, 384, 'Perrier', 'dm-non-alcohol-perrier', NULL, 5500.00, NULL, 7, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2990, 19, 384, 'Red Bull', 'dm-non-alcohol-red-bull', NULL, 7000.00, NULL, 8, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2991, 19, 384, 'Soda', 'dm-non-alcohol-soda', NULL, 3800.00, NULL, 9, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2992, 19, 384, 'Tonic', 'dm-non-alcohol-tonic', NULL, 3800.00, NULL, 10, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2993, 19, 385, 'Cranberry Pitcher', 'dm-juice-pitcher-cranberry-pitcher', NULL, 18000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2994, 19, 385, 'Orange', 'dm-juice-pitcher-orange', NULL, 8500.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2995, 19, 385, 'Apple', 'dm-juice-pitcher-apple', NULL, 8500.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2996, 19, 385, 'Pineapple', 'dm-juice-pitcher-pineapple', NULL, 8500.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2997, 19, 386, 'Single Espresso', 'dm-hot-beverages-single-espresso', NULL, 4500.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2998, 19, 386, 'Double Espresso', 'dm-hot-beverages-double-espresso', NULL, 5500.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(2999, 19, 386, 'Tea Selection', 'dm-hot-beverages-tea-selection', NULL, 4500.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3000, 19, 386, 'Cappuccino', 'dm-hot-beverages-cappuccino', NULL, 5500.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3001, 19, 386, 'Americano', 'dm-hot-beverages-americano', NULL, 5500.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3002, 19, 386, 'Cafe Late', 'dm-hot-beverages-cafe-late', NULL, 5500.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3003, 19, 387, 'Mojito', 'dm-classic-cocktails-mojito', NULL, 12000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3004, 19, 387, 'Flavored Mojito', 'dm-classic-cocktails-flavored-mojito', NULL, 12000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3005, 19, 387, 'Tequila Sunrise', 'dm-classic-cocktails-tequila-sunrise', NULL, 12000.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3006, 19, 387, 'Whisky Sour', 'dm-classic-cocktails-whisky-sour', NULL, 12000.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3007, 19, 387, 'Strawberry Daiquiri', 'dm-classic-cocktails-strawberry-daiquiri', NULL, 12000.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3008, 19, 387, 'Gin Tonic', 'dm-classic-cocktails-gin-tonic', NULL, 12000.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3009, 19, 387, 'Moscow Mule', 'dm-classic-cocktails-moscow-mule', NULL, 12000.00, NULL, 7, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3010, 19, 387, 'Basilicum', 'dm-classic-cocktails-basilicum', NULL, 12000.00, NULL, 8, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3011, 19, 387, 'Long Island', 'dm-classic-cocktails-long-island', NULL, 12000.00, NULL, 9, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3012, 19, 387, 'Pinacolada', 'dm-classic-cocktails-pinacolada', NULL, 12000.00, NULL, 10, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3013, 19, 387, 'Amaretto Whiskey Sour', 'dm-classic-cocktails-amaretto-whiskey-sour', NULL, 12000.00, NULL, 11, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3014, 19, 387, 'Cosmopolitan', 'dm-classic-cocktails-cosmopolitan', NULL, 12000.00, NULL, 12, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3015, 19, 387, 'Manhattan', 'dm-classic-cocktails-manhattan', NULL, 12000.00, NULL, 13, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3016, 19, 387, 'Margarita', 'dm-classic-cocktails-margarita', NULL, 12000.00, NULL, 14, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3017, 19, 387, 'Flavored Margarita', 'dm-classic-cocktails-flavored-margarita', NULL, 12000.00, NULL, 15, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3018, 19, 387, 'Sex on the Beach', 'dm-classic-cocktails-sex-on-the-beach', NULL, 12000.00, NULL, 16, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3019, 19, 387, 'Porn Star Martini', 'dm-classic-cocktails-porn-star-martini', NULL, 12000.00, NULL, 17, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3020, 19, 388, 'Level Up', 'dm-signature-cocktails-level-up', NULL, 15000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3021, 19, 388, 'Sweet In the Middle', 'dm-signature-cocktails-sweet-in-the-middle', NULL, 15000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3022, 19, 388, 'Smoky Opal', 'dm-signature-cocktails-smoky-opal', NULL, 15000.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3023, 19, 388, 'First Impression', 'dm-signature-cocktails-first-impression', NULL, 15000.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3024, 19, 388, 'Opal Free Flow', 'dm-signature-cocktails-opal-free-flow', NULL, 15000.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3025, 19, 388, 'Apple Minded', 'dm-signature-cocktails-apple-minded', NULL, 15000.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3026, 19, 388, 'Don\'t Get Wet', 'dm-signature-cocktails-dont-get-wet', NULL, 15000.00, NULL, 7, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3027, 19, 389, 'Tequila', 'dm-shots-tequila', NULL, 5900.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3028, 19, 389, 'Tequila Gold', 'dm-shots-tequila-gold', NULL, 5500.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3029, 19, 389, 'Vodka', 'dm-shots-vodka', NULL, 5000.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3030, 19, 389, 'Gin', 'dm-shots-gin', NULL, 4500.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3031, 19, 389, 'Whiskey', 'dm-shots-whiskey', NULL, 5000.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3032, 19, 389, 'Hennessey VSOP', 'dm-shots-hennessey-vsop', NULL, 14500.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3033, 19, 389, 'Rum', 'dm-shots-rum', NULL, 5000.00, NULL, 7, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3034, 19, 389, 'Jägermeister', 'dm-shots-j-germeister', NULL, 5000.00, NULL, 8, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3035, 19, 389, 'Baileys', 'dm-shots-baileys', NULL, 4500.00, NULL, 9, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3036, 19, 389, 'Campari', 'dm-shots-campari', NULL, 4500.00, NULL, 10, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3037, 19, 389, 'Aperol', 'dm-shots-aperol', NULL, 4500.00, NULL, 11, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3038, 19, 390, 'Brain Hemorrhage', 'dm-special-shots-brain-hemorrhage', NULL, 5500.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3039, 19, 390, 'Condom Shot', 'dm-special-shots-condom-shot', NULL, 5500.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3040, 19, 390, 'Death of Jellyfish', 'dm-special-shots-death-of-jellyfish', NULL, 5500.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3041, 19, 390, 'Doo Doo', 'dm-special-shots-doo-doo', NULL, 5500.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3042, 19, 390, 'Liquid Cocaine', 'dm-special-shots-liquid-cocaine', NULL, 5500.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3043, 19, 390, 'Absent Without Leave', 'dm-special-shots-absent-without-leave', NULL, 5500.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3044, 19, 390, 'Fire Shots', 'dm-special-shots-fire-shots', NULL, 5500.00, NULL, 7, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3045, 19, 390, 'Vendome Shot', 'dm-special-shots-vendome-shot', NULL, 5500.00, NULL, 8, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3046, 19, 390, 'Blowjob', 'dm-special-shots-blowjob', NULL, 5600.00, NULL, 9, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3047, 19, 390, 'Vendome Tower Fire Show', 'dm-special-shots-vendome-tower-fire-show', NULL, 25000.00, NULL, 10, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3048, 19, 391, 'Iced Gum', 'dm-shisha-menu-iced-gum', NULL, 30000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3049, 19, 391, 'Magic Love', 'dm-shisha-menu-magic-love', NULL, 30000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3050, 19, 391, 'Love 66', 'dm-shisha-menu-love-66', NULL, 36000.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3051, 19, 391, 'Strawberry', 'dm-shisha-menu-strawberry', NULL, 30000.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3052, 19, 391, 'Strawberry and Mint', 'dm-shisha-menu-strawberry-and-mint', NULL, 30000.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3053, 19, 391, 'Mixed Fruit', 'dm-shisha-menu-mixed-fruit', NULL, 30000.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3054, 19, 391, 'Gum and Mint', 'dm-shisha-menu-gum-and-mint', NULL, 30000.00, NULL, 7, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3055, 19, 391, 'Gum', 'dm-shisha-menu-gum', NULL, 30000.00, NULL, 8, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3056, 19, 391, 'Lemon and Mint', 'dm-shisha-menu-lemon-and-mint', NULL, 30000.00, NULL, 9, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3057, 19, 391, 'Mint and Cream', 'dm-shisha-menu-mint-and-cream', NULL, 30000.00, NULL, 10, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3058, 19, 391, 'Grape and Mint', 'dm-shisha-menu-grape-and-mint', NULL, 30000.00, NULL, 11, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3059, 19, 391, 'Grape', 'dm-shisha-menu-grape', NULL, 30000.00, NULL, 12, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3060, 19, 391, 'Two Apple', 'dm-shisha-menu-two-apple', NULL, 30000.00, NULL, 13, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3061, 19, 391, 'Mint', 'dm-shisha-menu-mint', NULL, 30000.00, NULL, 14, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3062, 19, 391, 'Peach', 'dm-shisha-menu-peach', NULL, 30000.00, NULL, 15, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52');
INSERT INTO `menu_items` (`id`, `restaurant_id`, `category_id`, `name`, `slug`, `description`, `price`, `image`, `display_order`, `is_available`, `created_at`, `updated_at`) VALUES
(3063, 19, 391, 'Blueberry', 'dm-shisha-menu-blueberry', NULL, 30000.00, NULL, 16, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3064, 19, 391, 'Blueberry and Mint', 'dm-shisha-menu-blueberry-and-mint', NULL, 30000.00, NULL, 17, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3065, 19, 391, 'Mango', 'dm-shisha-menu-mango', NULL, 30000.00, NULL, 18, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3066, 19, 391, 'Watermelon', 'dm-shisha-menu-watermelon', NULL, 30000.00, NULL, 19, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3067, 19, 391, 'Watermelon and Mint', 'dm-shisha-menu-watermelon-and-mint', NULL, 30000.00, NULL, 20, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3068, 19, 391, 'Lady Killer', 'dm-shisha-menu-lady-killer', NULL, 36000.00, NULL, 21, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3069, 19, 391, 'Two Apple (Duplicate listing)', 'dm-shisha-menu-two-apple-2', NULL, 30000.00, NULL, 22, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3070, 19, 391, 'Apple', 'dm-shisha-menu-apple-shisha', NULL, 25000.00, NULL, 23, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3071, 19, 391, 'Pineapple Fruit', 'dm-shisha-menu-pineapple-fruit', NULL, 40000.00, NULL, 24, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3072, 19, 391, 'Apple Fruit', 'dm-shisha-menu-apple-fruit', NULL, 40000.00, NULL, 25, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3073, 19, 391, 'Orange Fruit', 'dm-shisha-menu-orange-fruit', NULL, 40000.00, NULL, 26, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3074, 19, 392, 'First Impression', 'dm-karaoke-drinks-first-impression', NULL, 15000.00, NULL, 1, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3075, 19, 392, 'Opal Free Flow', 'dm-karaoke-drinks-opal-free-flow', 'Vodka, lemon juice, coconut syrup, strawberry syrup, watermelon chunks. Garnish: watermelon wedge.', 15000.00, NULL, 2, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3076, 19, 392, 'Apple Minded', 'dm-karaoke-drinks-apple-minded', 'Whiskey, simple syrup, apple juice. Garnish: dehydrated orange and apple.', 15000.00, NULL, 3, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3077, 19, 392, 'Level Up', 'dm-karaoke-drinks-level-up', 'Cognac, lemon juice, simple syrup, topped with ginger beer. Garnish: lemon wedge and cherry.', 15000.00, NULL, 4, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3078, 19, 392, 'Don\'t Get Wet', 'dm-karaoke-drinks-dont-get-wet', 'Gin, blue curaçao, grenadine syrup, orange juice. Garnish: dehydrated orange and cherry.', 15000.00, NULL, 5, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3079, 19, 392, 'Mojito (Virgin)', 'dm-karaoke-drinks-mojito-virgin', NULL, 12000.00, NULL, 6, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3080, 19, 392, 'Chapman', 'dm-karaoke-drinks-chapman', NULL, 12000.00, NULL, 7, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3081, 19, 392, 'Mint It Up', 'dm-karaoke-drinks-mint-it-up', NULL, 12000.00, NULL, 8, 1, '2026-05-19 00:02:52', '2026-05-19 00:02:52'),
(3082, 19, 393, 'Beans', 'beans', 'sfsfsf', 5000.00, NULL, 0, 1, '2026-05-27 19:49:26', '2026-07-11 00:33:21'),
(3083, 26, 394, 'Caesar Salad', 'fm-salads-caesar-salad', 'Lettuce, croutons, parmesan, grilled chicken, caesar dressing', 19500.00, NULL, 1, 1, '2026-05-17 21:36:14', '2026-05-17 21:36:14'),
(3084, 26, 394, 'Beetroot salad', 'fm-salads-beetroot-salad', 'Mixed leaves, Cherry tomatoes, Beetroot, Mushrooms, sweet corn, feta cheese, vinegar dressing', 18500.00, NULL, 2, 1, '2026-05-17 21:36:14', '2026-05-17 21:36:14'),
(3085, 26, 394, 'Shrimp Salad', 'fm-salads-shrimp-salad', 'Shrimps, mixed bell peppers, Mushrooms, mixed leaves and lemon mustard sauce', 22500.00, NULL, 3, 1, '2026-05-17 21:36:14', '2026-05-17 21:36:14'),
(3086, 26, 395, 'Loaded Fries — Boneless chicken', 'fm-appetizers-loaded-fries-boneless-chicken', 'Fries, chicken, mixed cheese, jalapeños, spicy mayo barbecue sauce', 15000.00, NULL, 1, 1, '2026-05-17 21:36:14', '2026-05-17 21:36:14'),
(3087, 26, 395, 'Loaded Fries — Chicken suya', 'fm-appetizers-loaded-fries-chicken-suya', 'Fries, chicken suya, onions, spicy suya mayo sauce', 15000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3088, 26, 395, 'Loaded Fries — Beef suya', 'fm-appetizers-loaded-fries-beef-suya', 'Fries, beef suya, onions, spicy suya mayo sauce', 15000.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3089, 26, 395, 'Chicken wings', 'fm-appetizers-chicken-wings', 'Provençal, Barbecue or Nigerian mix', 10000.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3090, 26, 395, 'Mini Burgers Sliders', 'fm-appetizers-mini-burgers-sliders', 'A set of 3 mini burgers: chicken, classic and smash burger.', 15000.00, NULL, 5, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3091, 26, 395, 'Mini Corn dog', 'fm-appetizers-mini-corn-dog', 'Breaded fried mini hotdog', 8000.00, NULL, 6, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3092, 26, 395, 'Chicken pops', 'fm-appetizers-chicken-pops', 'Battered fried chicken with sweet chili sauce', 11000.00, NULL, 7, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3093, 26, 395, 'Shrimp pops', 'fm-appetizers-shrimp-pops', 'Battered fried shrimps with sweet chili sauce', 13500.00, NULL, 8, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3094, 26, 395, 'Beef tacos', 'fm-appetizers-beef-tacos', NULL, 12500.00, NULL, 9, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3095, 26, 395, 'Chicken tacos', 'fm-appetizers-chicken-tacos', NULL, 12500.00, NULL, 10, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3096, 26, 395, 'Shrimp tacos', 'fm-appetizers-shrimp-tacos', NULL, 14500.00, NULL, 11, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3097, 26, 395, 'Chicken Suya Quesadilla', 'fm-appetizers-chicken-suya-quesadilla', NULL, 17000.00, NULL, 12, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3098, 26, 395, 'Avocado Bruschetta', 'fm-appetizers-avocado-bruschetta', NULL, 9500.00, NULL, 13, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3099, 26, 395, 'Tomato Bruschetta', 'fm-appetizers-tomato-bruschetta', NULL, 8000.00, NULL, 14, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3100, 26, 396, 'Chicken avocado sandwich', 'fm-sandwiches-chicken-avocado-sandwich', 'Grilled chicken, lettuce, tomato, mayo, parmesan cheese guacamole sauce', 17000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3101, 26, 396, 'Fajita', 'fm-sandwiches-fajita', 'Chicken, grilled mixed vegetables, mozzarella cheese', 13500.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3102, 26, 396, 'Philly Cheese steak', 'fm-sandwiches-philly-cheese-steak', 'Shredded beef, bell peppers, mushrooms, mozzarella cheese', 17000.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3103, 26, 396, 'Classic hotdog', 'fm-sandwiches-classic-hotdog', 'Hot dog, crispy matchsticks fries, onions, mustard, ketchup', 10000.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3104, 26, 396, 'Steak Sandwich Tuscan', 'fm-sandwiches-steak-sandwich-tuscan', 'Imported beef fillet, caramelised onions and mushrooms, sundried tomatoes, parmesan cheese', 28000.00, NULL, 5, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3105, 26, 397, 'U F O', 'fm-burgers-u-f-o', 'Beef patty, tomatoes, lettuce, onions, pickles, cheddar sauce', 15000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3106, 26, 397, 'Classic burger', 'fm-burgers-classic-burger', 'Beef patty, tomatoes, lettuce, pickles, spicy mayo sauce', 13500.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3107, 26, 397, 'Smash burger', 'fm-burgers-smash-burger', 'Double smashed beef, cheddar cheese, pickles, lettuce, burger sauce', 16000.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3108, 26, 397, 'S&S Burger', 'fm-burgers-sands-burger', 'Beef patty, caramelised onions and mushrooms, mozzarella, cheddar sauce', 20000.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3109, 26, 397, 'Chicken suya burger', 'fm-burgers-chicken-suya-burger', 'Chicken suya, tomatoes, onions, lettuce, cucumber and suya mayo sauce', 16500.00, NULL, 5, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3110, 26, 397, 'Nashville Chicken Burger', 'fm-burgers-nashville-chicken-burger', 'Breaded fried chicken dipped sweet chilli sauce, tomato, pickles and honey mustard sauce.', 18000.00, NULL, 6, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3111, 26, 398, 'Whole chicken', 'fm-fried-chicken-whole-chicken', 'Comes with coleslaw, jollof rice and French fries. 10 pcs.', 33500.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3112, 26, 398, 'Half chicken', 'fm-fried-chicken-half-chicken', 'Comes with coleslaw, jollof rice and French fries. 5 pcs.', 19000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3113, 26, 399, 'Grilled Chicken Breast', 'fm-mains-grilled-chicken-breast', NULL, 22000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3114, 26, 399, 'Ribeye', 'fm-mains-ribeye', NULL, 46000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3115, 26, 399, 'Lamb chops', 'fm-mains-lamb-chops', NULL, 39000.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3116, 26, 399, 'Fish and chips', 'fm-mains-fish-and-chips', NULL, 24000.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3117, 26, 399, 'Grilled Jumbo Prawns', 'fm-mains-grilled-jumbo-prawns', NULL, 33000.00, NULL, 5, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3118, 26, 399, 'Grilled Salmon', 'fm-mains-grilled-salmon', NULL, 31000.00, NULL, 6, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3119, 26, 399, 'Grilled Chicken Lap', 'fm-mains-grilled-chicken-lap', NULL, 18000.00, NULL, 7, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3120, 26, 400, 'French fries', 'fm-sides-french-fries', NULL, 5000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3121, 26, 400, 'Fried yam', 'fm-sides-fried-yam', NULL, 5000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3122, 26, 400, 'Fried plantain', 'fm-sides-fried-plantain', NULL, 5000.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3123, 26, 400, 'Jollof rice', 'fm-sides-jollof-rice', NULL, 6500.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3124, 26, 400, 'Mashed potatoes', 'fm-sides-mashed-potatoes', NULL, 6000.00, NULL, 5, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3125, 26, 401, 'Penne Arrabbiata', 'fm-pasta-penne-arrabbiata', 'Penne pasta, arrabbiata sauce, parmesan cheese.', 16000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3126, 26, 401, 'Rigatoni chicken Alfredo', 'fm-pasta-rigatoni-chicken-alfredo', 'White sauce, chicken, parmesan cheese, mushrooms', 19500.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3127, 26, 401, 'Spaghetti Bolognese', 'fm-pasta-spaghetti-bolognese', 'Spaghetti, tomato sauce, minced beef', 18000.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3128, 26, 401, 'Prawn Linguine', 'fm-pasta-prawn-linguine', 'Linguine pasta, prawns and tomato chili sauce', 25000.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3129, 26, 401, 'Mac and cheese', 'fm-pasta-mac-and-cheese', 'Macaroni, cheddar sauce and mozzarella cheese', 14000.00, NULL, 5, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3130, 26, 402, 'Margherita', 'fm-pizza-margherita', 'Tomato sauce, mozzarella cheese', 16500.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3131, 26, 402, 'Pepperoni', 'fm-pizza-pepperoni', 'Tomato sauce, mozzarella cheese, beef pepperoni', 21000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3132, 26, 402, 'Vegetarian', 'fm-pizza-vegetarian', 'Tomato sauce, mozzarella cheese, mixed vegetables', 19000.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3133, 26, 402, 'Naija', 'fm-pizza-naija', 'Tomato sauce, mozzarella cheese, Chicken suya, and suya mayo sauce', 22000.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3134, 26, 403, 'Chicken Suya', 'fm-nigerian-chicken-suya', NULL, 8500.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3135, 26, 403, 'Beef Suya', 'fm-nigerian-beef-suya', NULL, 10000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3136, 26, 403, 'Goat Asun', 'fm-nigerian-goat-asun', 'Assorted goat meat with mixed bell pepper, onions and chili sauce', 14000.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3137, 26, 403, 'Pepper Snails', 'fm-nigerian-pepper-snails', 'Pan fried snail with mixed bell pepper and chili sauce', 19500.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3138, 26, 403, 'Grilled Croacker Fish', 'fm-nigerian-grilled-croacker-fish', 'Grilled croaker fish with your choice of side', 25000.00, NULL, 5, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3139, 26, 403, 'Pepper Steak', 'fm-nigerian-pepper-steak', 'Sliced grilled beef with our special pepper sauce. Comes with mashed potatoes.', 25000.00, NULL, 6, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3140, 26, 403, 'Nigerian Poke Bowl', 'fm-nigerian-nigerian-poke-bowl', 'Bowl of jollof rice, suya (beef or chicken), plantain, chopped onions and tomatoes', 18500.00, NULL, 7, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3141, 26, 403, 'Nigerian Platter', 'fm-nigerian-nigerian-platter', 'Beef suya, chicken suya, chicken wings, jollof rice, plantain, and fried yam', 38000.00, NULL, 8, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3142, 26, 403, 'Seafood Platter', 'fm-nigerian-seafood-platter', 'Fried calamari, shrimp pops, Nigerian shrimp mix, battered fried fish, plantain, French fries and jollof', 48000.00, NULL, 9, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3143, 26, 404, 'French toast', 'fm-dessert-french-toast', NULL, 11500.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3144, 26, 404, 'Churros', 'fm-dessert-churros', NULL, 9000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3145, 26, 404, 'UFO SPLASH', 'fm-dessert-ufo-splash', NULL, 12000.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3146, 26, 404, 'Ice cream Delight', 'fm-dessert-ice-cream-delight', NULL, 7500.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3147, 26, 405, 'Espresso', 'dm-hot-drinks-espresso', NULL, 4000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3148, 26, 405, 'Americano', 'dm-hot-drinks-americano', NULL, 5000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3149, 26, 405, 'Cappuccino', 'dm-hot-drinks-cappuccino', NULL, 6500.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3150, 26, 405, 'Café Latte', 'dm-hot-drinks-caf-latte', NULL, 6500.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3151, 26, 405, 'Tea selection', 'dm-hot-drinks-tea-selection', NULL, 4500.00, NULL, 5, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3152, 26, 406, 'Soft drinks', 'dm-soft-drinks-soft-drinks', NULL, 2000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3153, 26, 406, 'Water Small', 'dm-soft-drinks-water-small', NULL, 1500.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3154, 26, 406, 'Water Big', 'dm-soft-drinks-water-big', NULL, 2500.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3155, 26, 406, 'Red Bull', 'dm-soft-drinks-red-bull', NULL, 4000.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3156, 26, 406, 'Tonic', 'dm-soft-drinks-tonic', NULL, 2000.00, NULL, 5, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3157, 26, 406, 'Soda Water', 'dm-soft-drinks-soda-water', NULL, 2000.00, NULL, 6, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3158, 26, 406, 'Sparkling water small', 'dm-soft-drinks-sparkling-water-small', NULL, 4500.00, NULL, 7, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3159, 26, 406, 'Sparkling water big', 'dm-soft-drinks-sparkling-water-big', NULL, 8000.00, NULL, 8, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3160, 26, 407, 'Watermelon', 'dm-fresh-juices-watermelon', NULL, 5000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3161, 26, 407, 'Pineapple', 'dm-fresh-juices-pineapple', NULL, 5000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3162, 26, 407, 'Mango', 'dm-fresh-juices-mango', NULL, 6500.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3163, 26, 407, 'Carrot', 'dm-fresh-juices-carrot', NULL, 5500.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3164, 26, 408, 'Oreo milkshake', 'dm-milkshakes-oreo-milkshake', NULL, 7000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3165, 26, 408, 'Chocolate', 'dm-milkshakes-chocolate', NULL, 7000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3166, 26, 408, 'Vanilla', 'dm-milkshakes-vanilla', NULL, 7000.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3167, 26, 408, 'Strawberry', 'dm-milkshakes-strawberry', NULL, 7000.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3168, 26, 409, 'Tropical dream', 'dm-mocktails-tropical-dream', NULL, 10500.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3169, 26, 409, 'Date night', 'dm-mocktails-date-night', NULL, 10500.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3170, 26, 409, 'Fruity iceberg', 'dm-mocktails-fruity-iceberg', NULL, 10500.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3171, 26, 409, 'Twilght', 'dm-mocktails-twilght', NULL, 10500.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3172, 26, 410, 'Gold Mine', 'dm-signature-cocktail-gold-mine', NULL, 16500.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3173, 26, 410, 'Sweet sensation', 'dm-signature-cocktail-sweet-sensation', NULL, 16500.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3174, 26, 410, 'Green Land', 'dm-signature-cocktail-green-land', NULL, 16500.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3175, 26, 410, 'Margua Crusta', 'dm-signature-cocktail-margua-crusta', NULL, 16500.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3176, 26, 410, 'Smoked Sazerac', 'dm-signature-cocktail-smoked-sazerac', NULL, 16500.00, NULL, 5, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3177, 26, 410, 'Lychee Blossom', 'dm-signature-cocktail-lychee-blossom', NULL, 16500.00, NULL, 6, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3178, 26, 410, 'Social island', 'dm-signature-cocktail-social-island', NULL, 16500.00, NULL, 7, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3179, 26, 411, 'Porn star martini', 'dm-cocktails-porn-star-martini', NULL, 15000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3180, 26, 411, 'Old Fashioned', 'dm-cocktails-old-fashioned', NULL, 13500.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3181, 26, 411, 'Negroni', 'dm-cocktails-negroni', NULL, 13500.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3182, 26, 411, 'Long Island', 'dm-cocktails-long-island', NULL, 15000.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3183, 26, 411, 'Mojito', 'dm-cocktails-mojito', NULL, 13500.00, NULL, 5, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3184, 26, 411, 'Margarita', 'dm-cocktails-margarita', NULL, 13500.00, NULL, 6, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3185, 26, 411, 'Gin Basil', 'dm-cocktails-gin-basil', NULL, 13500.00, NULL, 7, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3186, 26, 411, 'Mimosa', 'dm-cocktails-mimosa', NULL, 13500.00, NULL, 8, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3187, 26, 411, 'Aperol spritz', 'dm-cocktails-aperol-spritz', NULL, 13500.00, NULL, 9, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3188, 26, 411, 'Dry Martini', 'dm-cocktails-dry-martini', NULL, 13500.00, NULL, 10, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3189, 26, 411, 'Espresso Martini', 'dm-cocktails-espresso-martini', NULL, 13500.00, NULL, 11, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3190, 26, 411, 'Whiskey sour', 'dm-cocktails-whiskey-sour', NULL, 13500.00, NULL, 12, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3191, 26, 411, 'Cosmopolitan', 'dm-cocktails-cosmopolitan', NULL, 13500.00, NULL, 13, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3192, 26, 411, 'Colada', 'dm-cocktails-colada', NULL, 13500.00, NULL, 14, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3193, 26, 412, 'Heineken Draught', 'dm-beer-heineken-draught', NULL, 5000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3194, 26, 412, 'Tiger Draught', 'dm-beer-tiger-draught', NULL, 4000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3195, 26, 412, 'Guinness Draught', 'dm-beer-guinness-draught', NULL, 5000.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3196, 26, 412, 'Budweiser', 'dm-beer-budweiser', NULL, 5000.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3197, 26, 412, 'Castle Lite', 'dm-beer-castle-lite', NULL, 5000.00, NULL, 5, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3198, 26, 412, 'Gulder', 'dm-beer-gulder', NULL, 5000.00, NULL, 6, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3199, 26, 412, 'Desperado', 'dm-beer-desperado', NULL, 5000.00, NULL, 7, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3200, 26, 413, 'Smirnoff Ice Original', 'dm-ready-to-drink-smirnoff-ice-original', NULL, 5000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3201, 26, 413, 'Smirnoff Ice Double Black Can', 'dm-ready-to-drink-smirnoff-ice-double-black-can', NULL, 5000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3202, 26, 414, 'Belvedere', 'dm-vodka-belvedere', 'Shot = ₦8,000', 165000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3203, 26, 414, 'Grey Goose', 'dm-vodka-grey-goose', 'Shot = ₦5,000', 115000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3204, 26, 414, 'Beluga', 'dm-vodka-beluga', 'Shot = ₦5,000', 110000.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3205, 26, 414, 'Skyy', 'dm-vodka-skyy', 'Shot = ₦3,500', 70000.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3206, 26, 415, 'Azul Reposado', 'dm-tequila-azul-reposado', NULL, 700000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3207, 26, 415, 'Don Julio 1942', 'dm-tequila-don-julio-1942', NULL, 750000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3208, 26, 415, 'Casamigos Reposado', 'dm-tequila-casamigos-reposado', NULL, 290000.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3209, 26, 415, 'Casamigos Añejo', 'dm-tequila-casamigos-a-ejo', NULL, 390000.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3210, 26, 415, 'Teremana Reposado', 'dm-tequila-teremana-reposado', NULL, 240000.00, NULL, 5, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3211, 26, 415, 'El Mayór Blanco', 'dm-tequila-el-may-r-blanco', NULL, 140000.00, NULL, 6, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3212, 26, 415, 'El Mayór Reposado', 'dm-tequila-el-may-r-reposado', NULL, 230000.00, NULL, 7, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3213, 26, 415, 'El Mayór Añejo', 'dm-tequila-el-may-r-a-ejo', NULL, 280000.00, NULL, 8, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3214, 26, 415, 'Patrón Blanco', 'dm-tequila-patr-n-blanco', 'Shot = ₦6,500', 135000.00, NULL, 9, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3215, 26, 415, 'Patrón Reposado', 'dm-tequila-patr-n-reposado', 'Shot = ₦8,000', 170000.00, NULL, 10, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3216, 26, 415, 'Patrón Añejo', 'dm-tequila-patr-n-a-ejo', 'Shot = ₦9,000', 210000.00, NULL, 11, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3217, 26, 415, 'Mijenta Blanco', 'dm-tequila-mijenta-blanco', 'Shot = ₦7,000', 145000.00, NULL, 12, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3218, 26, 415, 'Mijenta Reposado', 'dm-tequila-mijenta-reposado', NULL, 280000.00, NULL, 13, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3219, 26, 415, 'Jose Cuervo Blanco', 'dm-tequila-jose-cuervo-blanco', 'Shot = ₦3,500', 80000.00, NULL, 14, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3220, 26, 416, 'Peaky Blinders', 'dm-rum-peaky-blinders', 'Shot = ₦4,000', 80000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3221, 26, 416, 'Bacardí White', 'dm-rum-bacard-white', 'Shot = ₦3,500', 70000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3222, 26, 416, 'Bacardí Oak Heart', 'dm-rum-bacard-oak-heart', 'Shot = ₦3,500', 70000.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3223, 26, 416, 'Diplomático', 'dm-rum-diplom-tico', NULL, 180000.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3224, 26, 416, 'Captain Morgan', 'dm-rum-captain-morgan', NULL, 55000.00, NULL, 5, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3225, 26, 417, 'Hendrick\'s', 'dm-gin-hendrick-s', 'Shot = ₦6,500', 140000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3226, 26, 417, 'Bombay Sapphire', 'dm-gin-bombay-sapphire', 'Shot = ₦3,500', 85000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3227, 26, 417, 'Tanqueray N.10', 'dm-gin-tanqueray-n-10', 'Shot = ₦5,000', 100000.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3228, 26, 417, 'Gin Mare', 'dm-gin-gin-mare', 'Shot = ₦5,500', 110000.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3229, 26, 417, 'Three Brothers', 'dm-gin-three-brothers', NULL, 165000.00, NULL, 5, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3230, 26, 417, 'Pedro\'s Ogogoro Gin', 'dm-gin-pedro-s-ogogoro-gin', NULL, 80000.00, NULL, 6, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3231, 26, 417, 'Gordon\'s Pink Berry', 'dm-gin-gordon-s-pink-berry', NULL, 60000.00, NULL, 7, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3232, 26, 418, 'Singleton 12', 'dm-whiskey-singleton-12', 'Glass = ₦11,500', 160000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3233, 26, 418, 'Singleton 15', 'dm-whiskey-singleton-15', 'Glass = ₦14,500', 200000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3234, 26, 418, 'Glenfiddich 12', 'dm-whiskey-glenfiddich-12', 'Shot = ₦8,000', 180000.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3235, 26, 418, 'Glenfiddich 15', 'dm-whiskey-glenfiddich-15', 'Shot = ₦10,000', 225000.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3236, 26, 418, 'Glenfiddich 18', 'dm-whiskey-glenfiddich-18', 'Shot = ₦14,000', 300000.00, NULL, 5, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3237, 26, 418, 'Monkey Shoulder', 'dm-whiskey-monkey-shoulder', 'Shot = ₦5,000', 100000.00, NULL, 6, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3238, 26, 418, 'Kilchoman', 'dm-whiskey-kilchoman', NULL, 280000.00, NULL, 7, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3239, 26, 418, 'Jack Daniel\'s', 'dm-whiskey-jack-daniel-s', 'Shots = ₦4,000', 80000.00, NULL, 8, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3240, 26, 418, 'Jameson', 'dm-whiskey-jameson', 'Shot = ₦4,000', 85000.00, NULL, 9, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3241, 26, 418, 'Jameson Black Barrel', 'dm-whiskey-jameson-black-barrel', 'Shot = ₦5,000', 100000.00, NULL, 10, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3242, 26, 418, 'Johnnie Walker Gold Label', 'dm-whiskey-johnnie-walker-gold-label', NULL, 185000.00, NULL, 11, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3243, 26, 418, 'Johnnie Walker Black Label', 'dm-whiskey-johnnie-walker-black-label', 'Shot = ₦5,000', 105000.00, NULL, 12, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3244, 26, 418, 'Akashi', 'dm-whiskey-akashi', NULL, 170000.00, NULL, 13, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3245, 26, 418, 'Kavalan', 'dm-whiskey-kavalan', NULL, 280000.00, NULL, 14, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3246, 26, 419, 'Rémy Martin VS', 'dm-cognac-r-my-martin-vs', 'Shot = ₦7,000', 175000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3247, 26, 419, 'Rémy Martin VSOP', 'dm-cognac-r-my-martin-vsop', NULL, 240000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3248, 26, 419, 'Rémy Martin 1738', 'dm-cognac-r-my-martin-1738', 'Shot = ₦19,000', 280000.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3249, 26, 419, 'Rémy Martin XO', 'dm-cognac-r-my-martin-xo', NULL, 700000.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3250, 26, 419, 'Hennessy VSOP', 'dm-cognac-hennessy-vsop', NULL, 280000.00, NULL, 5, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3251, 26, 419, 'Hennessy VS', 'dm-cognac-hennessy-vs', 'Shot = ₦7,000', 175000.00, NULL, 6, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3252, 26, 419, 'Martell XO', 'dm-cognac-martell-xo', NULL, 800000.00, NULL, 7, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3253, 26, 419, 'Martell Blue Swift', 'dm-cognac-martell-blue-swift', NULL, 250000.00, NULL, 8, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3254, 26, 419, 'Martell VS', 'dm-cognac-martell-vs', 'Shot = ₦7,000', 150000.00, NULL, 9, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3255, 26, 420, 'Jägermeister', 'dm-liqueur-j-germeister', 'Shot = ₦3,500', 80000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3256, 26, 420, 'Arak', 'dm-liqueur-arak', 'Shot = ₦3,500', 70000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3257, 26, 420, 'Campari', 'dm-liqueur-campari', 'Shot = ₦3,000', 60000.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3258, 26, 421, 'Kopke white', 'dm-wine-kopke-white', 'Porto sweet white wine', 70000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3259, 26, 421, 'Kopke red', 'dm-wine-kopke-red', 'Porto sweet red wine', 70000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3260, 26, 421, 'Kopke 2018', 'dm-wine-kopke-2018', 'Porto sweet red wine', 115000.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3261, 26, 421, 'Alma De Vega Honesty', 'dm-wine-alma-de-vega-honesty', 'Dry white wine', 45000.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3262, 26, 421, 'Alma De Vega Love', 'dm-wine-alma-de-vega-love', 'Semi-sweet white wine', 45000.00, NULL, 5, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3263, 26, 421, 'Alma De Vega Sensibility', 'dm-wine-alma-de-vega-sensibility', 'Sweet rosé wine', 45000.00, NULL, 6, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3264, 26, 421, 'Alma De Vega Respect', 'dm-wine-alma-de-vega-respect', 'Dry red wine', 45000.00, NULL, 7, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3265, 26, 421, 'Wine glass', 'dm-wine-wine-glass', NULL, 8000.00, NULL, 8, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3266, 26, 421, 'Alma de Vega Sparkling Rosé', 'dm-wine-alma-de-vega-sparkling-ros', NULL, 60000.00, NULL, 9, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3267, 26, 421, 'Amabile Di Rosa', 'dm-wine-amabile-di-rosa', 'Sweet red, white and rosé', 45000.00, NULL, 10, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3268, 26, 422, 'Moët Brut', 'dm-champagne-mo-t-brut', NULL, 225000.00, NULL, 1, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3269, 26, 422, 'Moët Nectar Rosé', 'dm-champagne-mo-t-nectar-ros', NULL, 290000.00, NULL, 2, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3270, 26, 422, 'Laurent Perrier Brut', 'dm-champagne-laurent-perrier-brut', NULL, 260000.00, NULL, 3, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3271, 26, 422, 'Laurent Perrier Demi Sec', 'dm-champagne-laurent-perrier-demi-sec', NULL, 270000.00, NULL, 4, 1, '2026-05-17 21:36:15', '2026-05-17 21:36:15'),
(3272, 20, 423, 'SWISS CAFE EARLY BIRD', 'fm-swiss-caf-menu-swiss-cafe-early-bird', '2 FRIED EGGS, TOMATO, SAUSAGES, BAKED BEANS & TOASTS WITH COFFEE OR TEA', 12000.00, NULL, 1, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3273, 20, 423, 'SUNDAY BRUNCH', 'fm-swiss-caf-menu-sunday-brunch-2', NULL, 30000.00, NULL, 2, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3274, 20, 423, 'BREAKFAST BUFFET', 'fm-swiss-caf-menu-breakfast-buffet-3', NULL, 12000.00, NULL, 3, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3275, 20, 423, 'DINNER BUFFET', 'fm-swiss-caf-menu-dinner-buffet-4', NULL, 25000.00, NULL, 4, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3276, 20, 423, 'KIDDIES', 'fm-swiss-caf-menu-kiddies-5', NULL, 6000.00, NULL, 5, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3277, 20, 423, 'ENGLISH BREAKFAST', 'fm-swiss-caf-menu-english-breakfast-6', '2 SCRAMBLE EGGS, TOMATO, SAUSAGES, BAKED BEANS & TOASTS WITH COFFEE OR TEA', 12000.00, NULL, 6, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3278, 20, 423, 'AMERICAN BREAKFAST', 'fm-swiss-caf-menu-american-breakfast-7', '2 CROISSANTS, JAM, SAUSAGES, BAKED BEANS & WITH COFFEE OR TEA', 18000.00, NULL, 7, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3279, 20, 423, 'SWISS CAFE CONTINENTAL BREAKFAST', 'fm-swiss-caf-menu-swiss-cafe-continental-breakfast-8', 'FRESH JUICE, TOAST, OVEN FRESH BREADROLL, BUTTER & JAM, CEREALS, CHOICE OF COFFEE OR TEA', 13500.00, NULL, 8, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3280, 20, 423, 'NIGERIAN BREAKFAST', 'fm-swiss-caf-menu-nigerian-breakfast-9', 'TOAST, EGG SAUCE or KIDNEY SAUCE, PLANTAIN or YAM, COFFEE OR TEA', 12000.00, NULL, 9, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3281, 20, 423, 'BIRCHER MUESLI', 'fm-swiss-caf-menu-bircher-muesli-10', 'THE CLASSICAL SWISS VITAMIN SHOT WITH OAK FLAKES, YOGHURT, AND SEASONAL FRUIT', 6500.00, NULL, 10, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3282, 20, 423, 'FRESH FRUIT SALAD', 'fm-swiss-caf-menu-fresh-fruit-salad-11', NULL, 6000.00, NULL, 11, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3283, 20, 423, 'EGGS AND NOODLES', 'fm-swiss-caf-menu-eggs-and-noodles-12', 'DUO OF FRIED OR BOILED EGGS WITH NOODLES', 8500.00, NULL, 12, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3284, 20, 423, 'TWO EGGS OMELETTE', 'fm-swiss-caf-menu-two-eggs-omelette-13', NULL, 4500.00, NULL, 13, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3285, 20, 423, 'TWO BOILED EGGS', 'fm-swiss-caf-menu-two-boiled-eggs-14', NULL, 3500.00, NULL, 14, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3286, 20, 423, 'SPANISH OMELETTE', 'fm-swiss-caf-menu-spanish-omelette-15', 'TWO EGGS OMELETTE WITH ONIONS, TOMATOES, AND GREEN PEPPER.', 4000.00, NULL, 15, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3287, 20, 423, 'OATMEAL', 'fm-swiss-caf-menu-oatmeal-16', NULL, 3500.00, NULL, 16, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3288, 20, 423, 'TOAST BREAD AND EGGS', 'fm-swiss-caf-menu-toast-bread-and-eggs-17', NULL, 6000.00, NULL, 17, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3289, 20, 423, 'BOILD YAM AND EGG', 'fm-swiss-caf-menu-boild-yam-and-egg-18', NULL, 10000.00, NULL, 18, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3290, 20, 424, 'CLUB SANDWICH', 'fm-sandwiches-and-more-club-sandwich', 'WE FOLLOW THE ORIGINAL RECIPE FOR THIS FAMOUS SANDWICH MADE WITH THE BEST FRESH VEGETABLES, SHREDDED CHICKEN, MAYONNAISE, BOILED EGG, AND BACON ON THE REQUEST. SERVED WITH FRENCH FRIES.', 9000.00, NULL, 1, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3291, 20, 424, 'CHICKEN CLUB SANDWICH', 'fm-sandwiches-and-more-chicken-club-sandwich-2', NULL, 10000.00, NULL, 2, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3292, 20, 424, 'PRAWNS CLUB SANDWICH', 'fm-sandwiches-and-more-prawns-club-sandwich-3', 'A CHANGE FROM THE ORIGINAL RECIPE MADE WITH CURRY-BOILED PRAWNS. SERVED WITH FRENCH FRIES', 20000.00, NULL, 3, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3293, 20, 424, 'CROQUE MONSIEUR', 'fm-sandwiches-and-more-croque-monsieur-4', 'FROM FRANCE THIS GOLDEN BROWN TOASTED HAM AND CHEESE SANDWICH IS SERVED WITH FRENCH FRIES AND COLESLAW.', 12000.00, NULL, 4, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3294, 20, 424, 'HAMBURGER', 'fm-sandwiches-and-more-hamburger-5', 'HAMBURGER, A 150 GR. GROUND MEAT PATTY, LETTUCE, CUCUMBER, GRILLED ONION, AND A SECRET HOMEMADE SAUCE BETWEEN TWO SLICES OF BREAD, WAS FIRST CREATED IN AMERICA IN 1900 BY LOUIS LASSEN, A DANISH IMMIGRANT AND WE SERVE IT FOLLOWING THE SAME RECIPE BUT WITH OUR SECRET HOMEMADE SAUCE. SERVED WITH FRENCH FRIES.', 12500.00, NULL, 5, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3295, 20, 424, 'BEEF BURGER', 'fm-sandwiches-and-more-beef-burger-6', NULL, 11000.00, NULL, 6, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3296, 20, 424, 'CHEESEBURGER', 'fm-sandwiches-and-more-cheeseburger-7', NULL, 12000.00, NULL, 7, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3297, 20, 424, 'CHICKEN BURGER', 'fm-sandwiches-and-more-chicken-burger-8', NULL, 15000.00, NULL, 8, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3298, 20, 424, 'FRESH SPRING ROLLS', 'fm-sandwiches-and-more-fresh-spring-rolls-9', '(4 PIECES) WITH DIPPING SAUCE', 6000.00, NULL, 9, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3299, 20, 424, 'FRESH SAMOSA', 'fm-sandwiches-and-more-fresh-samosa-10', '(4 PIECES) WITH DIPPING SAUCE', 4500.00, NULL, 10, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3300, 20, 424, 'CHICKEN WRAP', 'fm-sandwiches-and-more-chicken-wrap-11', 'COMES WITH FRANCH FRIES', 11000.00, NULL, 11, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3301, 20, 424, 'ITALIAN BRUSCHETTA', 'fm-sandwiches-and-more-italian-bruschetta-12', '2 SLICES OF TOASTED BREAD GENTLY BRUSHED WITH GARLIC AND TOPPED WITH OLIVE OIL, FRESH TOMATO, OREGANO', 12000.00, NULL, 12, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3302, 20, 425, '1 STANDARD MEAT PIE', 'fm-small-chops-1-standard-meat-pie', NULL, 3000.00, NULL, 1, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3303, 20, 425, '1 STANDARD CHICKEN PIE', 'fm-small-chops-1-standard-chicken-pie-2', NULL, 3000.00, NULL, 2, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3304, 20, 425, '1 STANDARD FISH PIE', 'fm-small-chops-1-standard-fish-pie-3', NULL, 3000.00, NULL, 3, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3305, 20, 425, 'PORTION CAKE', 'fm-small-chops-portion-cake-4', NULL, 6000.00, NULL, 4, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3306, 20, 425, 'DOUGHNUTS', 'fm-small-chops-doughnuts-5', NULL, 3000.00, NULL, 5, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3307, 20, 425, '2 CHEESE ROLLS', 'fm-small-chops-2-cheese-rolls-6', NULL, 3000.00, NULL, 6, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3308, 20, 425, '1 PACK OF CHIN-CHIN', 'fm-small-chops-1-pack-of-chin-chin-7', NULL, 3000.00, NULL, 7, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3309, 20, 425, '2 DANISH', 'fm-small-chops-2-danish-8', NULL, 3500.00, NULL, 8, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3310, 20, 425, '1 PACK OF POP CORN', 'fm-small-chops-1-pack-of-pop-corn-9', NULL, 2000.00, NULL, 9, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3311, 20, 425, 'CAKE', 'fm-small-chops-cake-10', NULL, 10000.00, NULL, 10, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3312, 20, 425, 'EGG ROLL', 'fm-small-chops-egg-roll-11', NULL, 3000.00, NULL, 11, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3313, 20, 425, 'SAUSAGE ROLL', 'fm-small-chops-sausage-roll-12', NULL, 2500.00, NULL, 12, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3314, 20, 425, 'SPRING ROLL', 'fm-small-chops-spring-roll-13', NULL, 2000.00, NULL, 13, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3315, 20, 426, 'FISH FINGERS', 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice-fish-fingers', NULL, 6500.00, NULL, 1, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3316, 20, 426, 'ONION RINGS', 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice-onion-rings-2', NULL, 3000.00, NULL, 2, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3317, 20, 426, 'FRENCH FRIES', 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice-french-fries-3', NULL, 5000.00, NULL, 3, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3318, 20, 426, 'FINGER YAM', 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice-finger-yam-4', NULL, 5000.00, NULL, 4, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3319, 20, 426, 'SWEET POTATO CHIPS', 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice-sweet-potato-chips-5', NULL, 5000.00, NULL, 5, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3320, 20, 426, 'FRIED PLANTAIN', 'fm-deep-fried-served-with-a-dip-sauce-of-your-choice-fried-plantain-6', NULL, 6000.00, NULL, 6, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3321, 20, 427, 'SNAILS', 'fm-peppered-proteins-snails', NULL, 28000.00, NULL, 1, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3322, 20, 427, 'BEEF', 'fm-peppered-proteins-beef-2', NULL, 13000.00, NULL, 2, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3323, 20, 427, 'PEPPERED GOAT MEAT', 'fm-peppered-proteins-peppered-goat-meat-3', NULL, 15000.00, NULL, 3, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3324, 20, 427, 'CHICKEN NKWOBI', 'fm-peppered-proteins-chicken-nkwobi-4', NULL, 15000.00, NULL, 4, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3325, 20, 427, 'GIZZARDS', 'fm-peppered-proteins-gizzards-5', NULL, 13500.00, NULL, 5, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3326, 20, 427, 'CHICKEN', 'fm-peppered-proteins-chicken-6', NULL, 13000.00, NULL, 6, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3327, 20, 427, 'COW LEG', 'fm-peppered-proteins-cow-leg-7', NULL, 13000.00, NULL, 7, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3328, 20, 427, 'CROAKER FISH PT', 'fm-peppered-proteins-croaker-fish-pt-8', NULL, 12000.00, NULL, 8, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3329, 20, 427, 'PEPPER PRAWNS', 'fm-peppered-proteins-pepper-prawns-9', NULL, 25000.00, NULL, 9, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3330, 20, 427, 'SHRIMPS', 'fm-peppered-proteins-shrimps-10', NULL, 6000.00, NULL, 10, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3331, 20, 427, 'ROCK FISH PORTION', 'fm-peppered-proteins-rock-fish-portion-11', NULL, 17000.00, NULL, 11, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3332, 20, 427, 'TURKEY', 'fm-peppered-proteins-turkey-12', NULL, 15000.00, NULL, 12, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3333, 20, 427, 'TITUS FISH', 'fm-peppered-proteins-titus-fish-13', NULL, 15000.00, NULL, 13, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3334, 20, 427, 'GOAT MEAT', 'fm-peppered-proteins-goat-meat-14', NULL, 16000.00, NULL, 14, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3335, 20, 427, 'CHICKEN LAP', 'fm-peppered-proteins-chicken-lap-15', NULL, 13000.00, NULL, 15, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3336, 20, 427, 'ROCK FISH FULL', 'fm-peppered-proteins-rock-fish-full-16', NULL, 35000.00, NULL, 16, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3337, 20, 427, 'DRY FISH (DRIED FISH)', 'fm-peppered-proteins-dry-fish-dried-fish-17', NULL, 17000.00, NULL, 17, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3338, 20, 427, 'CATFISH', 'fm-peppered-proteins-catfish-18', NULL, 15000.00, NULL, 18, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3339, 20, 427, 'BBQ CHICKEN', 'fm-peppered-proteins-bbq-chicken-19', NULL, 13000.00, NULL, 19, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3340, 20, 427, 'CHICKEN DRUM STICK', 'fm-peppered-proteins-chicken-drum-stick-20', NULL, 13000.00, NULL, 20, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3341, 20, 427, 'PEPPERED CHICKEN WINGS', 'fm-peppered-proteins-peppered-chicken-wings-21', NULL, 13000.00, NULL, 21, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3342, 20, 428, 'ROCK FISH PEPPER SOUP', 'fm-hot-starter-nigerian-pepper-soup-rock-fish-pepper-soup', NULL, 17000.00, NULL, 1, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3343, 20, 428, 'ROCK FISH SOUP', 'fm-hot-starter-nigerian-pepper-soup-rock-fish-soup-2', NULL, 18000.00, NULL, 2, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3344, 20, 428, 'CATFISH PEPPER SOUP', 'fm-hot-starter-nigerian-pepper-soup-catfish-pepper-soup-3', NULL, 25000.00, NULL, 3, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3345, 20, 428, 'FRENCH ONION SOUP', 'fm-hot-starter-nigerian-pepper-soup-french-onion-soup-4', 'TOPPED WITH GRATINATED TOAST', 5000.00, NULL, 4, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3346, 20, 428, 'BEEF PEPPER SOUP', 'fm-hot-starter-nigerian-pepper-soup-beef-pepper-soup-5', NULL, 13000.00, NULL, 5, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3347, 20, 428, 'CREAM OF MIXED VEGETABLES', 'fm-hot-starter-nigerian-pepper-soup-cream-of-mixed-vegetables-6', 'A MIX OF SEASONAL FRESH VEGETABLES BLENDED AND THICKENED WITH WHIPPED CREAM', 6000.00, NULL, 6, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3348, 20, 428, 'CREAM OF SWEET CORN SOUP', 'fm-hot-starter-nigerian-pepper-soup-cream-of-sweet-corn-soup-7', 'WE BLEND THE SWEET CORN AND MIX IT WITH STOCK AND SPICES THICKENING IT WITH WHIPPED CREAM.', 13000.00, NULL, 7, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3349, 20, 428, 'CATFISH PEPPER SOUP PORTION', 'fm-hot-starter-nigerian-pepper-soup-catfish-pepper-soup-portion-8', NULL, 15000.00, NULL, 8, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3350, 20, 428, 'COW PEPPER SOUP', 'fm-hot-starter-nigerian-pepper-soup-cow-pepper-soup-9', NULL, 13000.00, NULL, 9, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3351, 20, 428, 'BEEF SOUP', 'fm-hot-starter-nigerian-pepper-soup-beef-soup-10', NULL, 13000.00, NULL, 10, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3352, 20, 428, 'COW LEG SOUP', 'fm-hot-starter-nigerian-pepper-soup-cow-leg-soup-11', NULL, 17000.00, NULL, 11, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3353, 20, 428, 'GOAT MEAT SOUP', 'fm-hot-starter-nigerian-pepper-soup-goat-meat-soup-12', NULL, 15000.00, NULL, 12, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3354, 20, 428, 'TURKEY PEPPER SOUP', 'fm-hot-starter-nigerian-pepper-soup-turkey-pepper-soup-13', NULL, 15000.00, NULL, 13, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3355, 20, 429, 'STARTERS & DELIGHTS WARM SHREDDED STIR FRY CHICKEN SALAD', 'fm-salad-starters-and-delights-warm-shredded-stir-fry-chicken-salad', 'An original recipe from the mountains of Thailand composed of fresh lettuce, carrot, and spring onion topped with shredded fried chicken with cashew nuts and served with a dip of soy sauce and green curry.', 12000.00, NULL, 1, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3356, 20, 429, 'CAESAR SALAD', 'fm-salad-caesar-salad-2', 'We respect the tradition and we serve you fresh lettuce leaves topped with flakes of Italian Parmesan cheese and golden croutons served with the original homemade Caesar sauce on the side.', 12000.00, NULL, 2, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3357, 20, 429, 'CHICKEN CAESAR SALAD', 'fm-salad-chicken-caesar-salad-3', 'We respect the tradition and we serve you fresh lettuce leaves topped with flakes of Italian Parmesan cheese and golden croutons served with the original homemade Caesar sauce on the side.', 14000.00, NULL, 3, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3358, 20, 429, 'MIXED VEGETABLE SALAD', 'fm-salad-mixed-vegetable-salad-4', 'A healthy choice of a mix of seasonal vegetables served with Italian dressing.', 8500.00, NULL, 4, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3359, 20, 429, 'COLE SLAW', 'fm-salad-cole-slaw-5', 'A healthy choice for a mix of seasonal vegetables served with simple dressing.', 5000.00, NULL, 5, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3360, 20, 429, 'FRESH FRUIT SALAD', 'fm-salad-fresh-fruit-salad-6', 'A healthy choice for a mix of seasonal vegetables served with simple dressing.', 6000.00, NULL, 6, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3361, 20, 429, 'SWISS SALAD', 'fm-salad-swiss-salad-7', 'Created from the mind of our chef, an original recipe inspired by the land where our hotel was born. We mix for you fresh lettuce leaves with slices of green apple topped with small cubes of Gruyere cheese and served with French vinaigrette.', 15000.00, NULL, 7, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3362, 20, 429, 'GREEK SALAD', 'fm-salad-greek-salad-8', 'In the nest of democracy, they created this mix of flavour that we need to give you. On a bed of fresh lettuce leaves topped with fresh slices of tomatoes, cucumber, and green pepper, we garnish with Kalamata black olives and the famous feta cheese, served with garlic dressing.', 15000.00, NULL, 8, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3363, 20, 429, 'CONCORD SALAD', 'fm-salad-concord-salad-9', 'With a combination of green beans, peas, celery, green pepper, and onions on a bed of cabbage, we mix on top the chopped chicken with mayonnaise and garnish with boiled egg and croutons.', 8500.00, NULL, 9, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3364, 20, 429, 'SHRIMPS COCKTAIL', 'fm-salad-shrimps-cocktail-10', 'With our local shrimps, we serve you one of the most famous starters on a bed of grated carrots with cucumber and served with cocktail sauce.', 17000.00, NULL, 10, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3365, 20, 430, 'SPAGHETTI BOLOGNAISE', 'fm-pasta-and-pizza-spaghetti-bolognaise', 'THE CLASSICAL PASTA DISH WITH TOMATO AND GROUND BEEF, GRATINATED WITH PARMESAN CHEES AND SERVED WITH FRESH GREENS', 15000.00, NULL, 1, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3366, 20, 430, 'SPAGHETTI AND JUMBO PRAWNS', 'fm-pasta-and-pizza-spaghetti-and-jumbo-prawns-2', 'TO COOK THIS FAMOUS DISH OF THE ITALIAN TRADITION WE PAN FRY THE LOCAL PRAWNS IN OLIVE OIL WITH GARLIC, DRY PEPPER AND SPICES THICKENED WITH CREAM', 35000.00, NULL, 2, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3367, 20, 430, 'MAC AND CHEESE', 'fm-pasta-and-pizza-mac-and-cheese-3', 'MACARONI, COOKING CREAM, BUTTER WITH CHEDDAR CHEESE', 18000.00, NULL, 3, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10');
INSERT INTO `menu_items` (`id`, `restaurant_id`, `category_id`, `name`, `slug`, `description`, `price`, `image`, `display_order`, `is_available`, `created_at`, `updated_at`) VALUES
(3368, 20, 430, 'SPAGHETTI CABONARA', 'fm-pasta-and-pizza-spaghetti-cabonara-4', 'WE FOLLOW THE ORIGINAL RECIPE AND WE PAN-FRY BACON IN OLIVE OIL WITH GARLIC MAKING SAUCE WITH SCRAMBLED EGGS AND SPICES. TOPPED WITH PARMESAN CHEESE', 15000.00, NULL, 4, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3369, 20, 430, 'SEAFOOD SPAGHETTI', 'fm-pasta-and-pizza-seafood-spaghetti-5', 'A DISH OF THE NEAPOLITAN TRADITION COOKED WITH THE LOCAL SEAFOOD PAN-FRIED IN OLIVE OIL WITH SLICED TOMATOES, GARLIC, AND SPICES THICKENED WITH BLENDED SHRIMP SAUCE.', 28000.00, NULL, 5, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3370, 20, 430, 'JOLLOF SPAGHETTI', 'fm-pasta-and-pizza-jollof-spaghetti-6', 'A DISH OF THE NEAPOLITAN TRADITION COOKED IN TOMATO SAUCE.', 7000.00, NULL, 6, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3371, 20, 430, 'GARDEN VEGETABLE SPAGHETTI', 'fm-pasta-and-pizza-garden-vegetable-spaghetti-7', 'A BOUNTY FROM THE GARDEN-FRESH SEASONAL MIX OF VEGETABLES, TOMATOES, AND SPICES--MAKES A DELICIOUS PASTA TOPPING.', 12000.00, NULL, 7, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3372, 20, 430, 'SINGAPORE NOODLES', 'fm-pasta-and-pizza-singapore-noodles-8', 'FROM THE BORDER OF CHINA, A TRADITIONAL DISH MADE WITH FRIED CURRY NOODLES SALTED WITH SLICED FRESH VEGETABLES, BABY SHRIMPS, AND SHREDDED BEEF ACCOMPANIED WITH CHILI SAUCE', 13000.00, NULL, 8, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3373, 20, 430, 'PIZZA MARGHERITA', 'fm-pasta-and-pizza-pizza-margherita-9', 'WITH ALL THE FLAVOURS OF THE SPRINGTIME HILLS WITH TOMATO SAUCE, GREEN PEPPER, ONIONS, MUSHROOMS, BLACK OLIVES, FRESH TOMATO, SWEET CORN, MOZZARELLA CHEESE, AND OREGANO', 12000.00, NULL, 9, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3374, 20, 430, 'CHICKEN PIZZA', 'fm-pasta-and-pizza-chicken-pizza-10', NULL, 12000.00, NULL, 10, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3375, 20, 430, 'SEAFOOD PIZZA', 'fm-pasta-and-pizza-seafood-pizza-11', 'WITH ALL FLAVOURS FROM THE SEA, WE SERVE YOU IT WITH TOMATO, MIXED SEAFOOD, MOZZARELLA CHEESE, AND BASIL LEAVES', 22000.00, NULL, 11, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3376, 20, 430, 'PANZEROTTI', 'fm-pasta-and-pizza-panzerotti-12', '2 SHAPED SOUTH-ITALIAN FRIED PIZZA STUFFED WITH SPICY TOMATO SAUCE, MOZZARELLA CHEESE, AND BASIL LEAVES', 10000.00, NULL, 12, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3377, 20, 430, 'SEAFOOD PLATTER', 'fm-pasta-and-pizza-seafood-platter-13', 'A COMBINATION OF SEA-FOOD WITH CHOICE OF FRIES, YAM/SWEET POTATOES', 80000.00, NULL, 13, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3378, 20, 430, 'SPAGHETTI MEAT BALL', 'fm-pasta-and-pizza-spaghetti-meat-ball-14', 'A COMBO OF SEAFOODS WITH THE OPTION OF FRIES, YAM/SWEET POTATOES', 12000.00, NULL, 14, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3379, 20, 430, 'BEEF BOURGUIGNON', 'fm-pasta-and-pizza-beef-bourguignon-15', 'A COMBO OF SEAFOODS WITH THE OPTION OF FRIES, YAM/SWEET POTATOES', 9000.00, NULL, 15, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3380, 20, 430, 'VEGETABLE PASTA', 'fm-pasta-and-pizza-vegetable-pasta-16', NULL, 8000.00, NULL, 16, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3381, 20, 430, 'SPAGHETTI ARIABIATA', 'fm-pasta-and-pizza-spaghetti-ariabiata-17', NULL, 15000.00, NULL, 17, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3382, 20, 431, 'JOLLOF RICE', 'fm-nigerian-dishes-jollof-rice', 'A TRADITIONAL RECIPE THAT YOU CAN MEET IN EVERY EVENT COOKED BY OUR NATIONAL CHEF WITH FRESH TOMATO STEW, FRESH PEPPER, AND GARNISHED WITH PEAS', 8500.00, NULL, 1, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3383, 20, 431, 'COCONUT RICE', 'fm-nigerian-dishes-coconut-rice-2', 'ALL THE FLAVOURS OF NIGERIA IN THIS ORIGINAL RECIPE ARE COOKED WITH COCONUT MILK, COCONUT POWDER, AND DRY PEPPER', 7000.00, NULL, 2, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3384, 20, 431, 'BASMATI FRIED RICE', 'fm-nigerian-dishes-basmati-fried-rice-3', 'BASMATI RICE IS SALTED IN SPICY VEGETABLE OIL WITH CHOPPED ONIONS, CARROTS, AND GREEN BEANS.', 7000.00, NULL, 3, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3385, 20, 431, 'CHINESE FRIED RICE', 'fm-nigerian-dishes-chinese-fried-rice-4', 'BASMATI RICE SALTED IN SPICY VEGETABLE OIL WITH CRUMBLED EGGS, GREEN BEANS, PEAS, SHRIMP, CARROTS, AND CHOPPED ONIONS', 10000.00, NULL, 4, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3386, 20, 431, 'JAMBALAYA RICE', 'fm-nigerian-dishes-jambalaya-rice-5', 'BASMATI RICE WITH GARLIC, CHICKEN BREAST, SAUSAGE AND PLANTAIN IN TOMATO SAUCE', 13000.00, NULL, 5, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3387, 20, 431, 'BASMATI RICE', 'fm-nigerian-dishes-basmati-rice-6', NULL, 5000.00, NULL, 6, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3388, 20, 431, 'OFADA RICE AND SAUCE', 'fm-nigerian-dishes-ofada-rice-and-sauce-7', 'OFADA RICE, OFADA SAUCE AND FRIED PLANTAIN', 12000.00, NULL, 7, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3389, 20, 431, 'WHITE RICE', 'fm-nigerian-dishes-white-rice-8', NULL, 3000.00, NULL, 8, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3390, 20, 431, 'PORTAGE BEANS', 'fm-nigerian-dishes-portage-beans-9', NULL, 8000.00, NULL, 9, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3391, 20, 431, 'EBA', 'fm-nigerian-dishes-eba-10', NULL, 2500.00, NULL, 10, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3392, 20, 431, 'PLANTAIN FLOUR', 'fm-nigerian-dishes-plantain-flour-11', NULL, 3000.00, NULL, 11, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3393, 20, 431, 'WHEAT MEAL', 'fm-nigerian-dishes-wheat-meal-12', NULL, 2500.00, NULL, 12, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3394, 20, 431, 'SEMOVITA', 'fm-nigerian-dishes-semovita-13', NULL, 2500.00, NULL, 13, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3395, 20, 431, 'POUNDO YAM', 'fm-nigerian-dishes-poundo-yam-14', NULL, 3000.00, NULL, 14, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3396, 20, 431, 'VEGETABLE FRIED RICE', 'fm-nigerian-dishes-vegetable-fried-rice-15', NULL, 8500.00, NULL, 15, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3397, 20, 431, 'WHITE BEANS', 'fm-nigerian-dishes-white-beans-16', NULL, 4000.00, NULL, 16, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3398, 20, 432, 'CHICKEN YAKITORI', 'fm-asian-fusion-chicken-yakitori', '3 STICKS OF CHICKEN, ONIONS, AND GREEN PEPPER DIPPED IN A HOMEMADE SAUCE OF SOY SAUCE, GINGER AND GARLIC PASTE, SESAME OIL, SUGAR, AND SPRING ONION SERVED WITH FRIES', 12000.00, NULL, 1, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3399, 20, 432, 'SWEET AND SOUR CHICKEN', 'fm-asian-fusion-sweet-and-sour-chicken-2', 'MARINATED DICED MEAT COOKED IN A MEDIUM CHILLI TOMATO SAUCE, VINEGAR, HONEY, AND LIME WITH SPRING ONION, GREEN PEPPER, AND PINEAPPLE SERVED WITH STEAM BASMATI RICE', 15000.00, NULL, 2, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3400, 20, 432, 'CHICKEN CURRY', 'fm-asian-fusion-chicken-curry-3', 'ALL FLAVOURS OF THE INCREDIBLE INDIA FOR THIS BONELESS CHICKEN COOKED IN A MIX OF SPICES AND VEGETABLES THICKENED WITH CORNFLOWER AND SERVED WITH STEAMED BASMATI RICE OR HOMEMADE CHAPATI BREAD', 12000.00, NULL, 3, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3401, 20, 432, 'FRIED RICE', 'fm-asian-fusion-fried-rice-4', 'NIGERIAN RICE IS SALTED IN SPICY VEGETABLE OIL WITH CHOPPED ONIONS, CARROTS, AND GREEN BEANS.', 5000.00, NULL, 4, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3402, 20, 432, 'SPECIAL FRIED RICE', 'fm-asian-fusion-special-fried-rice-5', NULL, 12000.00, NULL, 5, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3403, 20, 433, 'SPECIAL OF THE DAY', 'fm-special-protein-special-of-the-day', 'SERVED WITH FRENCH FRIES AND FRESH SALADS', 12000.00, NULL, 1, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3404, 20, 433, 'FILLET OF FISH', 'fm-special-protein-fillet-of-fish-2', 'PAN FRIED FILLET OF RED SNAPPER or SHINY NOSE FROM OUR SEA GENTLY MARINATED AND SERVED WITH A CHOICE OF SAUCE, FRESH VEGETABLES, RICE, OR FRENCH FRIES', 15000.00, NULL, 2, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3405, 20, 433, 'CHICKEN BREAST', 'fm-special-protein-chicken-breast-3', NULL, 13000.00, NULL, 3, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3406, 20, 433, 'GRILLED JUMBO PRAWNS', 'fm-special-protein-grilled-jumbo-prawns-4', '2 JUMBO PRAWNS FROM THE BAY OF GUINEA GENTLY MARINATED AND SERVED WITH A CHOICE OF SAUCE, SAUTÉED VEGETABLES, AND RICE OF YOUR CHOICE OR CHIPS', 30000.00, NULL, 4, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3407, 20, 433, 'BUTTERFLY JUMBO PRAWNS', 'fm-special-protein-butterfly-jumbo-prawns-5', '2 JUMBO PRAWNS MARINATED AND COATES IN YOKES AND BREAD CRUMB, DEEP-FRIED AND SERVED WITH FRENCH FRIES AND TARTAR SAUCE.', 30000.00, NULL, 5, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3408, 20, 433, 'VEGETABLES SAUTÉ', 'fm-special-protein-vegetables-saut-6', 'SELECTED FRESH GARDEN VEGGIES COOKED TO ORDER ON MEDIUM HEAT SERVED WITH GRILLED CHICKEN BREAST.', 8000.00, NULL, 6, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3409, 20, 433, 'FILET STEAK', 'fm-special-protein-filet-steak-7', 'MEDAILLONS OF QUALITY BEEF GRILLED RARE, MEDIUM, OR WELL-DONE, SERVED WITH CREAM OF YOUR CHOICE, FRENCH FRIES, VEGETABLE SALAD/ SEASONAL VEGETABLES', 15000.00, NULL, 7, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3410, 20, 433, 'BEEF STROGANOFF', 'fm-special-protein-beef-stroganoff-8', 'TENDER STRIPS OF BEEF IN A MUSHROOM CREAM SAUCE SERVED WITH PASTA OR RICE OF YOUR CHOICE', 12000.00, NULL, 8, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3411, 20, 433, 'T-BONE STEAK', 'fm-special-protein-t-bone-steak-9', 'A MARINATED LOCAL STEAK GRILLED AND COOKED TO YOUR CHOICE SERVED WITH FRENCH FRIES.', 45000.00, NULL, 9, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3412, 20, 433, 'CHICKEN WINGS', 'fm-special-protein-chicken-wings-10', '5 PIECES OF HOT SAUCE MARINATED WINGS DEEP FRIED AND SERVED WITH NIGERIAN PEPPER SAUCE', 13000.00, NULL, 10, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3413, 20, 433, 'CHICKEN STIR FRY', 'fm-special-protein-chicken-stir-fry-11', NULL, 12000.00, NULL, 11, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3414, 20, 434, 'EXTRA BALL PONDO', 'fm-mama-africa-nigerian-soups-extra-ball-pondo', NULL, 4000.00, NULL, 1, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3415, 20, 434, 'ISI EWU', 'fm-mama-africa-nigerian-soups-isi-ewu-2', 'THE FAMOUS RECIPE FROM THE NIGERIAN TRADITION WITH A CHOPPED GOAT HEAD DRESSED WITH PEPPERED AND SPICY GRAVY AND SERVED ON A WOOD PLATE.', 25000.00, NULL, 2, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3416, 20, 434, 'NKWOBI', 'fm-mama-africa-nigerian-soups-nkwobi-3', 'THE FAMOUS RECIPE FROM THE NIGERIAN TRADITION WITH A CHOPPED COW LEG DRESSED WITH PEPPERED AND SPICY GRAVY AND SERVED ON A WOOD PLATE.', 15000.00, NULL, 3, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3417, 20, 434, 'FISHERMAN SOUP', 'fm-mama-africa-nigerian-soups-fisherman-soup-4', NULL, 30000.00, NULL, 4, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3418, 20, 434, 'SEAFOOD OKRO', 'fm-mama-africa-nigerian-soups-seafood-okro-5', 'lorem insput dolor sit amet adipicicing alit,sed do elusmod tempor incididunt ut labor at dolore magna aliqua.Ut enim ad minim veniam,ques nostrud.', 30000.00, NULL, 5, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(3419, 20, 434, 'VEGETABLE SOUP', 'fm-mama-africa-nigerian-soups-vegetable-soup-6', NULL, 10000.00, NULL, 6, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3420, 20, 434, 'EGUSI SOUP', 'fm-mama-africa-nigerian-soups-egusi-soup-7', NULL, 10000.00, NULL, 7, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3421, 20, 434, 'AFANG SOUP', 'fm-mama-africa-nigerian-soups-afang-soup-8', NULL, 13000.00, NULL, 8, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3422, 20, 434, 'CHICKEN NKWOBI', 'fm-mama-africa-nigerian-soups-chicken-nkwobi-9', NULL, 15000.00, NULL, 9, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3423, 20, 435, 'FOR OUR PASTRY CHEFS', 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-for-our-pastry-chefs', NULL, 5000.00, NULL, 1, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3424, 20, 435, 'SLICED FRUIT PLATTER', 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-sliced-fruit-platter-2', 'SEASONAL FRESH FRUIT FROM THE LOCAL FRUIT GARDEN MARKET', 6000.00, NULL, 2, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3425, 20, 435, 'MINI CAKE', 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-mini-cake-3', 'CHOICE OF CAKE', 5000.00, NULL, 3, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3426, 20, 435, 'PORTIONCAKE', 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-portioncake-4', 'CHOICE OF CAKE', 6000.00, NULL, 4, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3427, 20, 435, 'RED VELVET CAKE', 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-red-velvet-cake-5', 'CHOICE OF CAKE', 3500.00, NULL, 5, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3428, 20, 435, 'WHOLE RED VELVET CAKE', 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-whole-red-velvet-cake-6', 'CHOICE OF CAKE', 15000.00, NULL, 6, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3429, 20, 435, 'FRUIT SALAD', 'fm-all-day-long-a-choice-of-desserts-of-the-day-handmade-fruit-salad-7', 'DICED SEASONAL FRUIT FROM THE LOCAL FRUIT GARDEN MARKET DIPPED IN ORANGE JUICE AND SUGAR.', 6000.00, NULL, 7, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3430, 20, 436, 'CHICKEN & CHIPS', 'fm-starters-and-delights-chicken-and-chips', NULL, 17000.00, NULL, 1, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3431, 20, 436, 'BUFFALO WINGS', 'fm-starters-and-delights-buffalo-wings-2', NULL, 10000.00, NULL, 2, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3432, 20, 436, 'CAJUN SHRIMP TACOS', 'fm-starters-and-delights-cajun-shrimp-tacos-3', NULL, 15000.00, NULL, 3, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3433, 20, 436, 'CHICKEN PIZZA', 'fm-starters-and-delights-chicken-pizza-4', NULL, 12000.00, NULL, 4, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3434, 20, 436, 'MARGARITA', 'fm-starters-and-delights-margarita-5', NULL, 10000.00, NULL, 5, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3435, 20, 436, 'GRILLED WHOLE CAT FISH', 'fm-starters-and-delights-grilled-whole-cat-fish-6', NULL, 28000.00, NULL, 6, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3436, 20, 436, 'BEEF STIR FRY', 'fm-starters-and-delights-beef-stir-fry-7', NULL, 15000.00, NULL, 7, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3437, 20, 437, 'Medium Water (75cl)', 'dm-soft-drinks-medium-water-75cl', NULL, 1500.00, NULL, 1, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3438, 20, 437, 'Assorted Soft Drinks', 'dm-soft-drinks-assorted-soft-drinks-2', NULL, 2500.00, NULL, 2, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3439, 20, 437, 'Maltina', 'dm-soft-drinks-maltina-3', NULL, 3000.00, NULL, 3, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3440, 20, 437, 'Amstel - Guinness', 'dm-soft-drinks-amstel-guinness-4', NULL, 3000.00, NULL, 4, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3441, 20, 437, 'Power Horse', 'dm-soft-drinks-power-horse-5', NULL, 6000.00, NULL, 5, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3442, 20, 437, 'Red Bull', 'dm-soft-drinks-red-bull-6', NULL, 6000.00, NULL, 6, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3443, 20, 437, 'Bullet', 'dm-soft-drinks-bullet-7', NULL, 6000.00, NULL, 7, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3444, 20, 437, 'Eviron', 'dm-soft-drinks-eviron-8', NULL, 6000.00, NULL, 8, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3445, 20, 437, 'Monster', 'dm-soft-drinks-monster-9', NULL, 7000.00, NULL, 9, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3446, 20, 437, 'Daravit Health Drink', 'dm-soft-drinks-daravit-health-drink-10', NULL, 7000.00, NULL, 10, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3447, 20, 437, 'Chivita', 'dm-soft-drinks-chivita-11', NULL, 8500.00, NULL, 11, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3448, 20, 437, 'Fresh Juice', 'dm-soft-drinks-fresh-juice-12', NULL, 7.00, NULL, 12, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3449, 20, 438, 'TED & CO Mojito', 'dm-signature-cocktails-ted-and-co-mojito', 'Rum, Mint, Lime, Sugar & Soda', 10000.00, NULL, 1, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3450, 20, 438, 'Blue Margarita', 'dm-signature-cocktails-blue-margarita-2', 'Blue Curacao, Triple Sec, Tequila & Lemon Juice', 8000.00, NULL, 2, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3451, 20, 438, 'Cosmopolitan', 'dm-signature-cocktails-cosmopolitan-3', 'Vodka, Cointreau, Cranberry & Lime Juice', 8000.00, NULL, 3, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3452, 20, 438, 'Long Island', 'dm-signature-cocktails-long-island-4', 'Rum, Vodka, Gin, Tequila, Triple Sex, Coke', 12000.00, NULL, 4, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3453, 20, 438, 'Manhattan', 'dm-signature-cocktails-manhattan-5', 'Rum, Vodka, Gin, Tequila, Cointreau, Lime & Coke Ice, Jack Daniel, Vermouth (Martini dry) Angostura bitters and Cherry', 10000.00, NULL, 5, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3454, 20, 438, 'Bloody Mary', 'dm-signature-cocktails-bloody-mary-6', 'Salt, Black pepper, Vodka, Tomato juice, Lemon juice, Worcestershire, Hot Chilli', 10000.00, NULL, 6, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3455, 20, 438, 'Sex on the Beach', 'dm-signature-cocktails-sex-on-the-beach-7', 'Peach liqueur, pineapple Juice, Vodka, Cranberry Juice', 10000.00, NULL, 7, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3456, 20, 438, 'Tequila Sunrise', 'dm-signature-cocktails-tequila-sunrise-8', 'Tequila,Orange Juice, Grenadine orange wedge', 8000.00, NULL, 8, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3457, 20, 438, 'Screwdriver', 'dm-signature-cocktails-screwdriver-9', 'Vodka, Orange juice', 10000.00, NULL, 9, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3458, 20, 438, 'Melon Berry', 'dm-signature-cocktails-melon-berry-10', 'Melon Liqueur,Vodka, Orange juice', 10000.00, NULL, 10, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3459, 20, 438, 'Pinacolada', 'dm-signature-cocktails-pinacolada-11', 'Coconut rum, Coconut cream, Pineaple Juice..', 10000.00, NULL, 11, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3460, 20, 438, 'Mojito', 'dm-signature-cocktails-mojito-12', 'White Rum, Mint Leaf (5or6), Lime Juice, Soda Water, Garnished with lime wedge.', 10000.00, NULL, 12, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3461, 20, 439, 'Virgin Mojito', 'dm-mocktails-virgin-mojito', NULL, 6000.00, NULL, 1, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3462, 20, 439, 'Virgin Colada', 'dm-mocktails-virgin-colada-2', NULL, 7000.00, NULL, 2, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3463, 20, 439, 'Melon Berry', 'dm-mocktails-melon-berry-3', NULL, 5000.00, NULL, 3, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3464, 20, 439, 'Chapman', 'dm-mocktails-chapman-4', NULL, 6000.00, NULL, 4, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3465, 20, 439, 'Rock Shandy', 'dm-mocktails-rock-shandy-5', NULL, 5000.00, NULL, 5, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3466, 20, 439, 'Jamaica', 'dm-mocktails-jamaica-6', NULL, 4000.00, NULL, 6, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3467, 20, 439, 'Sun-Shine Delight', 'dm-mocktails-sun-shine-delight-7', NULL, 4000.00, NULL, 7, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3468, 20, 439, 'Milkshake', 'dm-mocktails-milkshake-8', NULL, 12000.00, NULL, 8, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3469, 20, 440, 'Heineken 600ml', 'dm-beer-and-mixes-heineken-600ml', NULL, 4000.00, NULL, 1, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3470, 20, 440, 'Budweiser 600ml', 'dm-beer-and-mixes-budweiser-600ml-2', NULL, 3500.00, NULL, 2, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3471, 20, 440, 'Guiness Stout Big 600ml', 'dm-beer-and-mixes-guiness-stout-big-600ml-3', NULL, 4500.00, NULL, 3, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3472, 20, 440, 'Star Radler 600ml', 'dm-beer-and-mixes-star-radler-600ml-4', NULL, 3000.00, NULL, 4, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3473, 20, 440, 'Gulder 600ml', 'dm-beer-and-mixes-gulder-600ml-5', NULL, 3500.00, NULL, 5, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3474, 20, 440, 'Goldberg 600ml', 'dm-beer-and-mixes-goldberg-600ml-6', NULL, 3500.00, NULL, 6, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3475, 20, 440, 'Desperados 600ml', 'dm-beer-and-mixes-desperados-600ml-7', NULL, 3500.00, NULL, 7, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3476, 20, 440, 'Orijin 600ml', 'dm-beer-and-mixes-orijin-600ml-8', NULL, 3500.00, NULL, 8, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3477, 20, 440, 'Trophy 600ml', 'dm-beer-and-mixes-trophy-600ml-9', NULL, 3500.00, NULL, 9, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3478, 20, 440, 'Trophy Stout 600ml', 'dm-beer-and-mixes-trophy-stout-600ml-10', NULL, 3000.00, NULL, 10, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3479, 20, 440, '33 Export 600ml', 'dm-beer-and-mixes-33-export-600ml-11', NULL, 3500.00, NULL, 11, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3480, 20, 440, 'Hero 600ml', 'dm-beer-and-mixes-hero-600ml-12', NULL, 3500.00, NULL, 12, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3481, 20, 440, 'Smirnoff Ice 250ml', 'dm-beer-and-mixes-smirnoff-ice-250ml-13', NULL, 3500.00, NULL, 13, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3482, 20, 440, 'Snap 250ml', 'dm-beer-and-mixes-snap-250ml-14', NULL, 2500.00, NULL, 14, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3483, 20, 440, 'Star 250ml', 'dm-beer-and-mixes-star-250ml-15', NULL, 3500.00, NULL, 15, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3484, 20, 440, 'Small Stout 325ml', 'dm-beer-and-mixes-small-stout-325ml-16', NULL, 3500.00, NULL, 16, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3485, 20, 440, 'Medium Stout', 'dm-beer-and-mixes-medium-stout-17', NULL, 4000.00, NULL, 17, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3486, 20, 440, 'Legend Beer 600ml', 'dm-beer-and-mixes-legend-beer-600ml-18', NULL, 4000.00, NULL, 18, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3487, 20, 440, 'Smirnoff Ice Big 600ml', 'dm-beer-and-mixes-smirnoff-ice-big-600ml-19', NULL, 4500.00, NULL, 19, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3488, 20, 440, 'Hunter 325ml', 'dm-beer-and-mixes-hunter-325ml-20', NULL, 3500.00, NULL, 20, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3489, 20, 440, 'Black bullet 325ml', 'dm-beer-and-mixes-black-bullet-325ml-21', NULL, 6000.00, NULL, 21, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3490, 20, 441, 'Nederburg Chardonnay', 'dm-wine-selection-nederburg-chardonnay', NULL, 70000.00, NULL, 1, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3491, 20, 441, 'Patagonia', 'dm-wine-selection-patagonia-2', NULL, 47000.00, NULL, 2, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3492, 20, 441, 'Massino', 'dm-wine-selection-massino-3', NULL, 40000.00, NULL, 3, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3493, 20, 441, 'Carlo Rossi', 'dm-wine-selection-carlo-rossi-4', NULL, 50000.00, NULL, 4, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3494, 20, 441, 'Mouton Cadet Bordeaux', 'dm-wine-selection-mouton-cadet-bordeaux-5', NULL, 80000.00, NULL, 5, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3495, 20, 441, 'Drostdy-Hof Claret Select', 'dm-wine-selection-drostdy-hof-claret-select-6', NULL, 40000.00, NULL, 6, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3496, 20, 441, 'Escudo Rojo', 'dm-wine-selection-escudo-rojo-7', NULL, 80000.00, NULL, 7, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3497, 20, 441, 'Chateauneuf-de-Pape', 'dm-wine-selection-chateauneuf-de-pape-8', NULL, 60000.00, NULL, 8, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3498, 20, 441, 'Two Oceans', 'dm-wine-selection-two-oceans-9', NULL, 47000.00, NULL, 9, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3499, 20, 441, 'Mouton Cadet Sauvignon Blanc', 'dm-wine-selection-mouton-cadet-sauvignon-blanc-10', NULL, 90000.00, NULL, 10, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3500, 20, 441, 'Four Cousins Red', 'dm-wine-selection-four-cousins-red-11', NULL, 48000.00, NULL, 11, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3501, 20, 441, 'Four Cousins White', 'dm-wine-selection-four-cousins-white-12', NULL, 48000.00, NULL, 12, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3502, 20, 441, 'Andre Rose', 'dm-wine-selection-andre-rose-13', NULL, 55000.00, NULL, 13, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3503, 20, 441, 'Andre Brut', 'dm-wine-selection-andre-brut-14', NULL, 50000.00, NULL, 14, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3504, 20, 441, 'Frontera', 'dm-wine-selection-frontera-15', NULL, 50000.00, NULL, 15, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3505, 20, 441, 'Declan', 'dm-wine-selection-declan-16', NULL, 48000.00, NULL, 16, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3506, 20, 441, 'Hardys Cabernet Sauvignon', 'dm-wine-selection-hardys-cabernet-sauvignon-17', NULL, 30000.00, NULL, 17, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3507, 20, 441, 'Podere', 'dm-wine-selection-podere-18', NULL, 25000.00, NULL, 18, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3508, 20, 441, '4th Street Red', 'dm-wine-selection-4th-street-red-19', NULL, 40000.00, NULL, 19, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3509, 20, 441, 'Castleflorit', 'dm-wine-selection-castleflorit-20', NULL, 30000.00, NULL, 20, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3510, 20, 441, 'Mini Drosdty Hof', 'dm-wine-selection-mini-drosdty-hof-21', NULL, 5000.00, NULL, 21, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3511, 20, 441, 'Fox Brook Merlot', 'dm-wine-selection-fox-brook-merlot-22', NULL, 35000.00, NULL, 22, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3512, 20, 441, 'RiUnite Lamrusco', 'dm-wine-selection-riunite-lamrusco-23', NULL, 38000.00, NULL, 23, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3513, 20, 441, 'Fox Brook Carbonet Sarvinon', 'dm-wine-selection-fox-brook-carbonet-sarvinon-24', NULL, 35000.00, NULL, 24, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3514, 20, 441, 'Moscato', 'dm-wine-selection-moscato-25', NULL, 20000.00, NULL, 25, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3515, 20, 441, 'Sweet Red', 'dm-wine-selection-sweet-red-26', NULL, 20000.00, NULL, 26, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3516, 20, 441, 'Nederburg Cabernet', 'dm-wine-selection-nederburg-cabernet-27', NULL, 70000.00, NULL, 27, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3517, 20, 441, 'Nederburg Sauvignon', 'dm-wine-selection-nederburg-sauvignon-28', NULL, 70000.00, NULL, 28, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3518, 20, 441, 'Glass Of Four Cousins', 'dm-wine-selection-glass-of-four-cousins-29', NULL, 10000.00, NULL, 29, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3519, 20, 441, 'Castillo Grande Red', 'dm-wine-selection-castillo-grande-red-30', NULL, 15000.00, NULL, 30, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3520, 20, 441, 'Castillo Grande White', 'dm-wine-selection-castillo-grande-white-31', NULL, 15000.00, NULL, 31, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3521, 20, 441, 'Castillo Grande Rose', 'dm-wine-selection-castillo-grande-rose-32', NULL, 15000.00, NULL, 32, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3522, 20, 441, 'Santa Alba Sweet', 'dm-wine-selection-santa-alba-sweet-33', NULL, 28000.00, NULL, 33, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3523, 20, 441, 'Cavalo Branco', 'dm-wine-selection-cavalo-branco-34', NULL, 30000.00, NULL, 34, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3524, 20, 441, 'Santa Sauvignon', 'dm-wine-selection-santa-sauvignon-35', NULL, 30000.00, NULL, 35, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3525, 20, 441, 'Santa-Alba', 'dm-wine-selection-santa-alba-36', NULL, 28000.00, NULL, 36, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3526, 20, 441, '4th Street White', 'dm-wine-selection-4th-street-white-37', NULL, 40000.00, NULL, 37, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3527, 20, 441, '13 Secret', 'dm-wine-selection-13-secret-38', NULL, 48000.00, NULL, 38, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3528, 20, 441, 'Sandana', 'dm-wine-selection-sandana-39', NULL, 25000.00, NULL, 39, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3529, 20, 441, 'Majesty', 'dm-wine-selection-majesty-40', NULL, 30000.00, NULL, 40, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3530, 20, 441, 'Beacon Hill', 'dm-wine-selection-beacon-hill-41', NULL, 30000.00, NULL, 41, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3531, 20, 441, 'Feliza', 'dm-wine-selection-feliza-42', NULL, 30000.00, NULL, 42, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3532, 20, 441, 'Stones', 'dm-wine-selection-stones-43', NULL, 30000.00, NULL, 43, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3533, 20, 441, 'Rubis', 'dm-wine-selection-rubis-44', NULL, 60000.00, NULL, 44, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3534, 20, 442, 'Gold Label', 'dm-sweet-and-sparkling-gold-label', 'Shots ₦4,500', 126000.00, NULL, 1, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3535, 20, 442, 'Green Label', 'dm-sweet-and-sparkling-green-label-2', 'Shots ₦4,000', 90000.00, NULL, 2, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3536, 20, 442, 'Blue Label', 'dm-sweet-and-sparkling-blue-label-3', NULL, 90000.00, NULL, 3, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3537, 20, 442, 'Black Label', 'dm-sweet-and-sparkling-black-label-4', 'Shots ₦5,000', 120000.00, NULL, 4, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3538, 20, 442, 'Black Barrel', 'dm-sweet-and-sparkling-black-barrel-5', 'Shots ₦4,000', 140000.00, NULL, 5, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3539, 20, 442, 'Observatory', 'dm-sweet-and-sparkling-observatory-6', NULL, 180000.00, NULL, 6, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3540, 20, 442, 'Red Label', 'dm-sweet-and-sparkling-red-label-7', 'Shots ₦3,000', 80000.00, NULL, 7, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3541, 20, 442, 'Jack Daniels', 'dm-sweet-and-sparkling-jack-daniels-8', 'Shots ₦4,000', 110000.00, NULL, 8, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3542, 20, 442, 'Gentleman Jack', 'dm-sweet-and-sparkling-gentleman-jack-9', 'Shots ₦2,500', 157500.00, NULL, 9, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3543, 20, 442, 'Chivas Regal 12 Years', 'dm-sweet-and-sparkling-chivas-regal-12-years-10', 'Shots ₦2,500', 40000.00, NULL, 10, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3544, 20, 442, 'Chivas Regal 18 Years', 'dm-sweet-and-sparkling-chivas-regal-18-years-11', NULL, 80000.00, NULL, 11, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3545, 20, 442, 'White Horse', 'dm-sweet-and-sparkling-white-horse-12', 'Shots ₦1,500', 15000.00, NULL, 12, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3546, 20, 442, 'Grants 12 Years', 'dm-sweet-and-sparkling-grants-12-years-13', 'Shots ₦2,500', 12000.00, NULL, 13, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3547, 20, 442, 'Glenfiddich 12 Years', 'dm-sweet-and-sparkling-glenfiddich-12-years-14', 'Shots ₦6,000', 130000.00, NULL, 14, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3548, 20, 442, 'Glenfiddich 18 Years', 'dm-sweet-and-sparkling-glenfiddich-18-years-15', NULL, 400000.00, NULL, 15, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3549, 20, 442, 'Glenfiddich 15 years', 'dm-sweet-and-sparkling-glenfiddich-15-years-16', NULL, 300000.00, NULL, 16, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3550, 20, 442, 'Jameson Black', 'dm-sweet-and-sparkling-jameson-black-17', NULL, 100000.00, NULL, 17, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3551, 20, 442, 'Jameson Irish', 'dm-sweet-and-sparkling-jameson-irish-18', 'Shots ₦3,500', 85000.00, NULL, 18, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3552, 20, 442, 'Glenfiddich 21Years', 'dm-sweet-and-sparkling-glenfiddich-21years-19', NULL, 500000.00, NULL, 19, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3553, 20, 442, 'Olmeca Tequila', 'dm-sweet-and-sparkling-olmeca-tequila-20', 'Shots ₦4,000', 80000.00, NULL, 20, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3554, 20, 442, 'Camino Tequila', 'dm-sweet-and-sparkling-camino-tequila-21', 'Shots ₦3,000', 75000.00, NULL, 21, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3555, 20, 442, 'Sierra Tequila', 'dm-sweet-and-sparkling-sierra-tequila-22', 'Shots ₦3,000', 75000.00, NULL, 22, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3556, 20, 443, 'Agor Sweet Red Wine', 'dm-whiskey-agor-sweet-red-wine', NULL, 48000.00, NULL, 1, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3557, 20, 443, 'Rialto (Espana)', 'dm-whiskey-rialto-espana-2', NULL, 8500.00, NULL, 2, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3558, 20, 443, 'Motivo', 'dm-whiskey-motivo-3', NULL, 10000.00, NULL, 3, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3559, 20, 443, 'Martini', 'dm-whiskey-martini-4', NULL, 30000.00, NULL, 4, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3560, 20, 443, 'Andre Brut', 'dm-whiskey-andre-brut-5', NULL, 50000.00, NULL, 5, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3561, 20, 443, 'Moet & Chandon Imperial Brut', 'dm-whiskey-moet-and-chandon-imperial-brut-6', NULL, 350000.00, NULL, 6, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3562, 20, 443, 'Moet & Chandon Imperia Rose', 'dm-whiskey-moet-and-chandon-imperia-rose-7', NULL, 380000.00, NULL, 7, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3563, 20, 443, 'Romeo Brut', 'dm-whiskey-romeo-brut-8', NULL, 200000.00, NULL, 8, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3564, 20, 443, 'Veuve Du Vernay (Ice)', 'dm-whiskey-veuve-du-vernay-ice-9', NULL, 25000.00, NULL, 9, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3565, 20, 443, 'Veuve Du Vernay (Rose)', 'dm-whiskey-veuve-du-vernay-rose-10', NULL, 40000.00, NULL, 10, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3566, 20, 443, 'Veuve Clicquot (Rose)', 'dm-whiskey-veuve-clicquot-rose-11', NULL, 380000.00, NULL, 11, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3567, 20, 443, 'Veuve Clicquot (Brut)', 'dm-whiskey-veuve-clicquot-brut-12', NULL, 350000.00, NULL, 12, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3568, 20, 443, 'Don Perignon', 'dm-whiskey-don-perignon-13', NULL, 1500000.00, NULL, 13, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3569, 20, 443, 'Crystal', 'dm-whiskey-crystal-14', NULL, 400000.00, NULL, 14, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3570, 20, 443, 'Monkey shoulder', 'dm-whiskey-monkey-shoulder-15', NULL, 90000.00, NULL, 15, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3571, 20, 443, 'Mateus Rose', 'dm-whiskey-mateus-rose-16', NULL, 45000.00, NULL, 16, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3572, 20, 443, 'Mateus Brut', 'dm-whiskey-mateus-brut-17', NULL, 45000.00, NULL, 17, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3573, 20, 443, 'B and G Cuvee Speciale', 'dm-whiskey-b-and-g-cuvee-speciale-18', NULL, 35000.00, NULL, 18, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3574, 20, 443, 'Domino', 'dm-whiskey-domino-19', NULL, 32000.00, NULL, 19, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3575, 20, 443, 'Feliza', 'dm-whiskey-feliza-20', NULL, 30000.00, NULL, 20, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3576, 20, 443, 'Castillo Grande', 'dm-whiskey-castillo-grande-21', NULL, 30000.00, NULL, 21, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3577, 20, 443, 'Robertson Winery', 'dm-whiskey-robertson-winery-22', NULL, 32000.00, NULL, 22, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3578, 20, 444, 'Hennessy VSOP', 'dm-cognac-and-brandy-hennessy-vsop', 'Shots ₦8,500', 240000.00, NULL, 1, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3579, 20, 444, 'Hennessy XO', 'dm-cognac-and-brandy-hennessy-xo-2', NULL, 0.00, NULL, 2, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3580, 20, 444, 'Hennessy VS Big', 'dm-cognac-and-brandy-hennessy-vs-big-3', 'Shots ₦7,000', 170000.00, NULL, 3, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3581, 20, 444, 'Hennessy VS Small', 'dm-cognac-and-brandy-hennessy-vs-small-4', NULL, 100000.00, NULL, 4, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3582, 20, 444, 'Remy Martin VSOP', 'dm-cognac-and-brandy-remy-martin-vsop-5', 'Shots ₦10,500', 240000.00, NULL, 5, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3583, 20, 444, 'Remy Martin XO', 'dm-cognac-and-brandy-remy-martin-xo-6', NULL, 0.00, NULL, 6, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3584, 20, 444, 'Martell VSOP', 'dm-cognac-and-brandy-martell-vsop-7', 'Shots ₦4,000', 120000.00, NULL, 7, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3585, 20, 444, 'Martel Blue Swift', 'dm-cognac-and-brandy-martel-blue-swift-8', NULL, 240000.00, NULL, 8, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3586, 20, 444, 'Martel Vs', 'dm-cognac-and-brandy-martel-vs-9', 'Shots ₦4,000', 120000.00, NULL, 9, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3587, 20, 444, 'Orijin Bitters', 'dm-cognac-and-brandy-orijin-bitters-10', NULL, 3500.00, NULL, 10, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3588, 20, 444, 'Odogwu', 'dm-cognac-and-brandy-odogwu-11', NULL, 3500.00, NULL, 11, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3589, 20, 444, 'Martinellis', 'dm-cognac-and-brandy-martinellis-12', NULL, 18000.00, NULL, 12, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3590, 20, 445, 'Absolut Blue', 'dm-vodka-absolut-blue', 'Shots ₦2,000', 40000.00, NULL, 1, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3591, 20, 445, 'Absolute Mandarin', 'dm-vodka-absolute-mandarin-2', 'Shots ₦1,500', 40000.00, NULL, 2, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3592, 20, 445, 'Smirn-Off', 'dm-vodka-smirn-off-3', 'Shots ₦1,500', 50000.00, NULL, 3, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3593, 20, 445, 'Ciroc Flavoured', 'dm-vodka-ciroc-flavoured-4', 'Shots ₦5,500', 160000.00, NULL, 4, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3594, 20, 445, 'Vodka Extract', 'dm-vodka-vodka-extract-5', 'Shots ₦2,500', 25000.00, NULL, 5, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3595, 20, 445, 'Flirt Vodka', 'dm-vodka-flirt-vodka-6', NULL, 35000.00, NULL, 6, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3596, 20, 445, 'Sky Vodka', 'dm-vodka-sky-vodka-7', NULL, 48000.00, NULL, 7, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3597, 20, 445, 'Absolute Vodka', 'dm-vodka-absolute-vodka-8', 'Shots ₦3,000', 40000.00, NULL, 8, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3598, 20, 445, 'Olmeca (Hot Chocolate)', 'dm-vodka-olmeca-hot-chocolate-9', NULL, 75000.00, NULL, 9, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3599, 20, 446, 'Gordon', 'dm-gin-gordon', 'Shots ₦2,500', 35000.00, NULL, 1, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3600, 20, 446, 'Bombay Sapphire', 'dm-gin-bombay-sapphire-2', 'Shots ₦4,000', 97125.00, NULL, 2, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3601, 20, 447, 'Bacardi Gold', 'dm-tequila-and-shooters-bacardi-gold', 'Shots ₦2,500', 55000.00, NULL, 1, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3602, 20, 447, 'Bacardi Superior', 'dm-tequila-and-shooters-bacardi-superior-2', 'Shots ₦2,500', 40000.00, NULL, 2, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3603, 20, 447, 'St James', 'dm-tequila-and-shooters-st-james-3', 'Shots ₦1,500', 15000.00, NULL, 3, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3604, 20, 447, 'Elliot', 'dm-tequila-and-shooters-elliot-4', 'Shots ₦1,500', 35000.00, NULL, 4, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3605, 20, 447, 'Captain Morgan', 'dm-tequila-and-shooters-captain-morgan-5', 'Shots ₦2,000', 60000.00, NULL, 5, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3606, 20, 447, 'Captain Morgan', 'dm-tequila-and-shooters-captain-morgan-6', NULL, 40000.00, NULL, 6, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3607, 20, 448, 'Tequila Gold Camino', 'dm-rum-tequila-gold-camino', 'Shots ₦2,500', 75000.00, NULL, 1, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3608, 20, 448, 'Tequila Blanco White', 'dm-rum-tequila-blanco-white-2', 'Shots ₦4,000', 80000.00, NULL, 2, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3609, 20, 448, 'Siera Tequila', 'dm-rum-siera-tequila-3', 'Shots ₦2,500', 75000.00, NULL, 3, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3610, 20, 449, 'Campari', 'dm-vermouth-and-liqueurs-campari', 'Shots ₦1,500', 15000.00, NULL, 1, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3611, 20, 449, 'Martini Rosso', 'dm-vermouth-and-liqueurs-martini-rosso-2', 'Shots ₦3,000', 53000.00, NULL, 2, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3612, 20, 449, 'Martini Bianco', 'dm-vermouth-and-liqueurs-martini-bianco-3', 'Shots ₦7,000', 47000.00, NULL, 3, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3613, 20, 449, 'Pastis', 'dm-vermouth-and-liqueurs-pastis-4', 'Shots ₦1,500', 42000.00, NULL, 4, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3614, 20, 449, 'Ricard', 'dm-vermouth-and-liqueurs-ricard-5', 'Shots ₦1,500', 12000.00, NULL, 5, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3615, 20, 449, 'Baileys', 'dm-vermouth-and-liqueurs-baileys-6', 'Shots ₦3,000', 65000.00, NULL, 6, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3616, 20, 449, 'Cointreau', 'dm-vermouth-and-liqueurs-cointreau-7', 'Shots ₦2,000', 65000.00, NULL, 7, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3617, 20, 449, 'Amarula', 'dm-vermouth-and-liqueurs-amarula-8', 'Shots ₦1,500', 15000.00, NULL, 8, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3618, 20, 449, 'Tia Maria', 'dm-vermouth-and-liqueurs-tia-maria-9', 'Shots ₦1,500', 11500.00, NULL, 9, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3619, 20, 449, 'Kahlua', 'dm-vermouth-and-liqueurs-kahlua-10', 'Shots ₦1,500', 10500.00, NULL, 10, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3620, 20, 449, 'Drambuie', 'dm-vermouth-and-liqueurs-drambuie-11', 'Shots ₦3,000', 20500.00, NULL, 11, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3621, 20, 449, 'Drambuie', 'dm-vermouth-and-liqueurs-drambuie-12', 'Amaretto', 30000.00, NULL, 12, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3622, 20, 449, 'Southern Comfort', 'dm-vermouth-and-liqueurs-southern-comfort-13', 'Amaretto', 106000.00, NULL, 13, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3623, 20, 449, 'Mini Campari', 'dm-vermouth-and-liqueurs-mini-campari-14', 'Amaretto', 15000.00, NULL, 14, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3624, 20, 449, 'Crema Turron', 'dm-vermouth-and-liqueurs-crema-turron-15', NULL, 49000.00, NULL, 15, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3625, 20, 449, 'The Nines', 'dm-vermouth-and-liqueurs-the-nines-16', NULL, 112000.00, NULL, 16, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3626, 20, 449, 'Malibu', 'dm-vermouth-and-liqueurs-malibu-17', NULL, 115500.00, NULL, 17, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3627, 20, 449, 'Cream De Cafe', 'dm-vermouth-and-liqueurs-cream-de-cafe-18', NULL, 35000.00, NULL, 18, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3628, 20, 450, 'Power Smoothie', 'dm-smoothies-and-fresh-juices-power-smoothie', NULL, 7000.00, NULL, 1, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3629, 20, 450, 'Mixed Fruit Smoothie', 'dm-smoothies-and-fresh-juices-mixed-fruit-smoothie-2', NULL, 7000.00, NULL, 2, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3630, 20, 450, 'Pineapple Smoothie', 'dm-smoothies-and-fresh-juices-pineapple-smoothie-3', NULL, 7000.00, NULL, 3, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3631, 20, 450, 'Watermelon Smoothie', 'dm-smoothies-and-fresh-juices-watermelon-smoothie-4', NULL, 7000.00, NULL, 4, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3632, 20, 450, 'Cranberry Juice', 'dm-smoothies-and-fresh-juices-cranberry-juice-5', NULL, 12000.00, NULL, 5, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3633, 20, 450, 'Tea and Coffee', 'dm-smoothies-and-fresh-juices-tea-and-coffee-6', NULL, 2000.00, NULL, 6, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3634, 20, 450, 'Pineapple Juice', 'dm-smoothies-and-fresh-juices-pineapple-juice-7', NULL, 7000.00, NULL, 7, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3635, 20, 450, 'Watermelon Juice', 'dm-smoothies-and-fresh-juices-watermelon-juice-8', NULL, 7000.00, NULL, 8, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3636, 20, 450, 'Orange Juice', 'dm-smoothies-and-fresh-juices-orange-juice-9', NULL, 6000.00, NULL, 9, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3637, 20, 450, 'Mixed Juice', 'dm-smoothies-and-fresh-juices-mixed-juice-10', NULL, 6000.00, NULL, 10, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3638, 20, 450, 'Parfait Juice', 'dm-smoothies-and-fresh-juices-parfait-juice-11', NULL, 8000.00, NULL, 11, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3639, 20, 450, 'Fresh Juice', 'dm-smoothies-and-fresh-juices-fresh-juice-12', NULL, 7000.00, NULL, 12, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3640, 20, 450, 'Fresh Smoothie', 'dm-smoothies-and-fresh-juices-fresh-smoothie-13', NULL, 7000.00, NULL, 13, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3641, 20, 450, 'Chivita Juice', 'dm-smoothies-and-fresh-juices-chivita-juice-14', NULL, 8000.00, NULL, 14, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3642, 20, 450, 'HollandiaYoghurt', 'dm-smoothies-and-fresh-juices-hollandiayoghurt-15', NULL, 8000.00, NULL, 15, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3643, 20, 450, 'Healthy Drink', 'dm-smoothies-and-fresh-juices-healthy-drink-16', NULL, 3000.00, NULL, 16, 1, '2026-05-19 01:47:11', '2026-05-19 01:47:11'),
(3644, 13, 451, 'Veggie Omelet', 'veggie-omelet', 'Three egg omelet cooked with diced \r\ntomatoes, onion\r\ngreen pepper, side vegetables and \r\noptional cheese served with toast bread \r\nand butter', 18000.00, NULL, 1, 1, '2026-03-13 09:42:21', '2026-06-17 14:52:25'),
(3645, 13, 451, 'Eggs Your Way (D)(N)(V)', 'eggs-your-way', 'Three eggs your way – plain or cheese \r\nomelet, fried or scrambled, portion of \r\nwhole pan-fried button mushrooms\r\ngrilled tomatoes, accompanied by toast \r\nbread and butter', 15000.00, NULL, 2, 1, '2026-03-13 09:42:21', '2026-03-21 09:47:12'),
(3646, 13, 451, 'Lusso Style Pancakes (D)(V)', 'lusso-style-pancakes', 'Triple stack pancakes with quenelle \r\ncream cheese, chantelle cream, jam, \r\nnuts, fried plantain or banana drizzled \r\nwith pure maple syrup or honey', 20000.00, NULL, 3, 1, '2026-03-13 09:42:21', '2026-03-21 09:48:32'),
(3647, 13, 452, 'Smoked Salmon', 'smoked-salmon', NULL, 15000.00, NULL, 1, 1, '2026-03-13 09:42:21', '2026-03-13 09:42:21'),
(3648, 13, 452, 'Chicken Sausage', 'chicken-sausage', NULL, 10000.00, NULL, 2, 1, '2026-03-13 09:42:21', '2026-03-13 09:42:21'),
(3649, 13, 452, 'Bacon', 'bacon', NULL, 10000.00, NULL, 3, 1, '2026-03-13 09:42:21', '2026-03-13 09:42:21'),
(3650, 13, 452, 'Hash Brown Potatoes (V)', 'hash-brown-potatoes', NULL, 8000.00, NULL, 4, 1, '2026-03-13 09:42:21', '2026-03-13 09:42:21'),
(3651, 13, 452, 'Baked Beans (V)', 'baked-beans', NULL, 8000.00, NULL, 5, 1, '2026-03-13 09:42:21', '2026-03-13 09:42:21'),
(3652, 13, 452, 'Beans Pottage (V)', 'nigerian-stewed-beans', '', 8000.00, NULL, 6, 1, '2026-03-13 09:42:21', '2026-04-02 13:45:52'),
(3653, 13, 452, 'Dodo – Fried Plantain (V)', 'dodo-fried-plantain', NULL, 8000.00, NULL, 7, 1, '2026-03-13 09:42:21', '2026-03-13 09:42:21'),
(3654, 13, 452, 'Moi-Moi (V)', 'moi-moi', NULL, 10000.00, NULL, 8, 1, '2026-03-13 09:42:21', '2026-03-13 09:42:21'),
(3655, 13, 452, 'Akara (V)', 'akara', NULL, 10000.00, NULL, 9, 1, '2026-03-13 09:42:21', '2026-03-13 09:42:21'),
(3656, 13, 452, 'Baker’s Basket', 'bakers-basket', NULL, 15000.00, NULL, 10, 1, '2026-03-13 09:42:21', '2026-03-13 09:42:21'),
(3657, 13, 452, 'Grilled Mushrooms', 'grilled-mushrooms', NULL, 8000.00, NULL, 11, 1, '2026-03-13 09:42:21', '2026-03-13 09:42:21'),
(3658, 13, 452, 'Fruit Salad (V)', 'fruit-salad', NULL, 10000.00, NULL, 12, 1, '2026-03-13 09:42:21', '2026-03-13 09:42:21'),
(3659, 13, 453, 'Mini Continental Breakfast', 'mini-continental-breakfast', 'Glass of freshly squeezed juice, coffee or tea with \r\nbaker’s basket, butter and jams', 20000.00, NULL, 1, 1, '2026-03-13 09:42:21', '2026-06-17 14:52:51'),
(3660, 13, 453, 'English Breakfast (D)(N)', 'english-breakfast', 'Baker\'s basket\r\nMorning rolls, croissant, pain au chocolate raisin whirl \r\nand toast, served with butter, selection of jams and honey\r\nYour style of eggs \r\nOmelette, scrambled, or fried served with chicken sausages, sauté potatoes, bacon, \r\nbutton mushrooms and grilled tomato\r\nRefreshing fresh pineapple or orange or watermelon \r\njuice, seasonal fruits slices\r\nFreshly brewed coffee, regular or decaffeinated tea \r\nor hot chocolate', 38000.00, NULL, 2, 1, '2026-03-13 09:42:21', '2026-03-21 09:54:53'),
(3661, 13, 453, 'Rising Sun Continental Breakfast (D)(N)', 'rising-sun-continental-breakfast', 'Baker\'s basket\r\nMorning rolls, croissant, pain au chocolate raisin whirl \r\nand toast, served with butter, selection of jams and honey\r\nRefreshing fresh juice - pineapple or orange or watermelon\r\nSeasonal fruit slices, plain or flavoured yoghurt\r\nCereals\r\nChoice of muesli, cornflakes, rice crispy or fruit n fiber\r\nFreshly brewed coffee, regular or decaffeinated tea \r\nor hot chocolate', 35000.00, NULL, 3, 1, '2026-03-13 09:42:21', '2026-03-21 09:56:28');
INSERT INTO `menu_items` (`id`, `restaurant_id`, `category_id`, `name`, `slug`, `description`, `price`, `image`, `display_order`, `is_available`, `created_at`, `updated_at`) VALUES
(3662, 13, 453, 'Good Morning Lusso – Nigerian Breakfast (D)(N)', 'good-morning-lusso-nigerian-breakfast', 'Nigerian Breakfast\r\nRefreshing fresh juice - pineapple or orange or watermelon\r\nSeasonal fruit slices\r\nYour choice of ogi, oatmeal porridge or cereal of your choice \r\nServed with skimmed, full cream or soya milk\r\nTraditional Nigerian egg sauce garnished with fried plantain, \r\nMoi-moi or yam served with stew of the day\r\nFreshly brewed coffee, regular or decaffeinated tea or hot chocolate', 35000.00, NULL, 4, 1, '2026-03-13 09:42:21', '2026-03-21 09:57:41'),
(3663, 13, 454, 'Nigerian Stew of the Day', 'nigerian-stew-of-the-day', 'Please enquire from your service attendant about our \r\nlovingly prepared stew of the day, served with your \r\npreferred starch- yam chips, white or traditional rice of \r\nthe day, dodo and coleslaw', 25000.00, NULL, 1, 1, '2026-03-13 09:42:21', '2026-03-23 13:23:35'),
(3664, 13, 454, 'Nigerian Soup of the Day (N)', 'nigerian-soup-of-the-day', 'Please enquire from your service attendant about our \r\nauthentic traditional soups of the day, served with your \r\nchoice of freshly preferred swallow', 25000.00, NULL, 2, 1, '2026-03-13 09:42:21', '2026-03-21 10:16:10'),
(3665, 13, 454, 'Pepper Snails (D)(N)(S)', 'pepper-snails', 'Stewed giant African snails, braised in African chili \r\nsauce, onion and local hot pepper, accompanied by \r\nyour choice of fried plantain or rice of the day \r\nor French fries', 40000.00, NULL, 3, 1, '2026-03-13 09:42:21', '2026-03-21 10:17:33'),
(3666, 13, 454, 'Spicy Half BBQ Chicken (D)(N)', 'spicy-half-bbq-chicken', 'Half BBQ chicken, roasted to perfection, served with \r\nside of Fried plantain or rice of the day of French fries', 22000.00, NULL, 4, 1, '2026-03-13 09:42:21', '2026-03-21 10:18:50'),
(3667, 13, 455, 'Swallow of the Day', 'swallow-of-the-day', NULL, 7000.00, NULL, 1, 1, '2026-03-13 09:42:21', '2026-06-17 14:53:05'),
(3668, 13, 455, 'Rice of the Day (V)', 'rice-of-the-day', NULL, 7000.00, NULL, 2, 1, '2026-03-13 09:42:21', '2026-03-13 09:42:21'),
(3669, 13, 455, 'Basmati Rice (V)', 'basmati-rice', NULL, 8000.00, NULL, 3, 1, '2026-03-13 09:42:21', '2026-03-13 09:42:21'),
(3670, 13, 455, 'Fried Plantain', 'fried-plantain', NULL, 7000.00, NULL, 5, 1, '2026-03-13 09:42:21', '2026-03-13 09:42:21'),
(3671, 13, 457, 'Smoked Salmon Rosette', 'smoked-salmon-rosette', 'Smoked salmon rosette with coddled egg, garden salad, \r\nsmooth cream cheese, French dressing capers and \r\nred onion with a hint of lemon', 28000.00, NULL, 3, 1, '2026-03-13 09:42:21', '2026-03-27 13:17:15'),
(3672, 13, 457, 'Greek Village Salad (D)(N)(V)', 'greek-village-salad', 'Greek delicacy of feta cheese combined with fresh organic lettuce, \r\nTomato, cucumber, olives, and red onion, dressed with French vinaigrette', 17000.00, NULL, 2, 1, '2026-03-13 09:42:21', '2026-03-21 10:00:21'),
(3673, 13, 457, 'Chicken Caesar Salad (D)(N)(S)', 'chicken-caesar-salad', '220g BBQ chicken fillet grilled to perfection, on a bed of iceberg \r\nlocal lettuce with flavorsome homemade garlic croutons, \r\nfreshly grated Italian parmesan cheese, accompanied \r\nby our Chef’s Caesar dressing and a hint of anchovies', 22000.00, NULL, 3, 1, '2026-03-13 09:42:21', '2026-03-21 10:01:34'),
(3674, 13, 459, 'Curried Veggie Delight', 'curried-veggie-delight', 'Chickpeas, potato & lentil dahl masala with basmati rice \r\naccompaniment of plain yoghurt, served with chapatti & hot chili pepper \r\nsauce', 18000.00, NULL, 1, 1, '2026-03-13 09:42:21', '2026-06-17 14:53:18'),
(3675, 13, 459, 'Spicy Penne Arrabiata (D)(N)', 'spicy-penne-arrabiata', 'Penne pasta with black olive, mixed bell peppers, onion & garlic tossed \r\nin chili pepper sauce, served with gratinated French bread & parmesan \r\ncheese', 20000.00, NULL, 2, 1, '2026-03-13 09:42:21', '2026-03-21 10:10:11'),
(3676, 13, 460, 'Succulent Beef Burger', 'succulent-beef-burger', '250g pure ground beef BBQ patty, grilled to perfection \r\nwith gratinated mozzarella cheese', 25000.00, NULL, 1, 1, '2026-03-13 09:42:21', '2026-06-17 14:53:34'),
(3677, 13, 460, 'Chicken Burger (D)(N)', 'chicken-burger', '220g Chef’s secret breaded butterflied chicken, \r\nfilled with gratinated mozzarella cheese', 24000.00, NULL, 2, 1, '2026-03-13 09:42:21', '2026-03-21 10:14:04'),
(3678, 13, 461, 'Fillet Mignon (D)', 'fillet-mignon', '300g cut of the finest grass-fed cattle, fillet of beef, \r\ntenderly grilled, topped with whole garlic butter \r\nmushrooms & aromatic black peppers, sea salt', 55000.00, NULL, 3, 1, '2026-03-13 09:42:21', '2026-04-25 13:11:52'),
(3679, 13, 461, 'Suya Style (D)(N)', 'suya-style', 'Nigerian most popular delicacy, a combination of two \r\nsticks of chicken and two sticks of beef suya, specially \r\nspiced, char grilled served with fresh onion, tomatoes, \r\nfries or fried plantain', 25000.00, NULL, 5, 1, '2026-03-13 09:42:21', '2026-03-23 13:31:11'),
(3680, 13, 462, 'Lusso Club Sandwich', 'lusso-club-sandwich-aGrB', 'Double decker with chicken & turkey ham, fried eggs, lettuce \r\ntomato, pickles, mayonnaise spread & mozzarella cheese \r\naccompanied with fries and homemade coleslaw', 18000.00, NULL, 1, 1, '2026-03-13 09:42:21', '2026-06-17 14:54:36'),
(3681, 13, 462, 'Perinaise Chicken Wrap (D)(N)', 'perinaise-chicken-wrap', 'Delicious, coated chicken strips in a flour tortilla filled with \r\nsmooth cream cheese, chiffonade of lettuce & diced onion with our \r\nsecret Nigerian perinaise sauce accompanied \r\nby fries and homemade coleslaw', 20000.00, NULL, 2, 1, '2026-03-13 09:42:21', '2026-03-21 10:29:53'),
(3682, 13, 463, 'Kids Omelet', 'kids-omelet', 'Served with fresh seasonal steamed vegetables', 8000.00, NULL, 1, 1, '2026-03-13 09:42:21', '2026-06-17 14:54:00'),
(3683, 13, 463, 'Spaghetti (D)(V)', 'spaghetti-with-tomato-sauce', 'Served with tomato sauce', 10000.00, NULL, 2, 1, '2026-03-13 09:42:21', '2026-03-21 10:42:24'),
(3684, 13, 463, 'Cocktail Beef & Cheese Slider (D)(N)', 'cocktail-beef-cheese-slider', 'Served with French fries', 15000.00, NULL, 3, 1, '2026-03-13 09:42:21', '2026-03-21 10:43:07'),
(3685, 13, 463, 'Corn Fried Chicken Nuggets (D)(N)', 'corn-fried-chicken-nuggets', 'Accompanied with French fries', 15000.00, NULL, 4, 1, '2026-03-13 09:42:21', '2026-03-21 10:43:53'),
(3686, 13, 463, 'Mini Margherita Pizza (D)(V)', 'mini-margherita-pizza', NULL, 10000.00, NULL, 5, 1, '2026-03-13 09:42:21', '2026-03-13 09:42:21'),
(3687, 13, 464, 'Crème Brûlée (D)', 'creme-brulee', NULL, 12000.00, NULL, 1, 1, '2026-03-13 09:42:21', '2026-03-13 09:42:21'),
(3688, 13, 464, 'Chocolate Brownie with Vanilla Ice Cream (D)(N)', 'chocolate-brownie-with-vanilla-ice-cream', NULL, 18000.00, NULL, 2, 1, '2026-03-13 09:42:21', '2026-03-13 09:42:21'),
(3689, 13, 464, 'Exotic Fruit Platter (V)', 'exotic-fruit-platter', NULL, 10000.00, NULL, 3, 1, '2026-03-13 09:42:21', '2026-03-13 09:42:21'),
(3690, 13, 464, 'Vanilla Ice Cream & Cookies (D)(N)', 'vanilla-ice-cream-cookies', NULL, 16000.00, NULL, 4, 1, '2026-03-13 09:42:21', '2026-03-13 09:42:21'),
(3691, 13, 465, 'Greek Village Salad', 'greek-village-salad-fAKx', 'Rocket leaves, grilled peppers, marinated olives and feta cheese finished with olive oil and balsamic reduction.', 18000.00, 'w4A01UhhVtzR.jpg', 1, 1, '2026-03-13 10:13:02', '2026-06-21 01:22:54'),
(3692, 13, 465, 'Asian Prawn Salad 🦐🌰🌶️', 'asian-prawn-salad', 'Lemon and garlic marinated prawns served on crisp Asian slaw with green apple slices, toasted sesame seeds and sweet chili dressing.', 25000.00, NULL, 2, 1, '2026-03-13 10:13:02', '2026-03-13 10:13:02'),
(3693, 13, 465, 'Local Papaya Salad 🥛', 'local-papaya-salad', 'Lettuce, pawpaw, tomato, watermelon, pineapple and feta cheese with lime ranch dressing.', 17000.00, NULL, 4, 1, '2026-03-13 10:13:02', '2026-03-27 13:20:08'),
(3694, 13, 466, 'Spiced Halloumi Wrap 🌾🥛', 'spiced-halloumi-wrap', 'Grilled halloumi cheese wrapped with lettuce in tortilla bread.', 15000.00, NULL, 1, 1, '2026-03-13 10:13:02', '2026-03-13 10:13:02'),
(3695, 13, 467, 'Aglio Olio Prawn Pasta 🦐🌾🍷🥛', 'aglio-olio-prawn-pasta', 'Pasta tossed in garlic and herb infusion with capsicum and white wine, finished with prawns and seafood, served with gratinated capsicum and cheese bruschetta.', 30000.00, NULL, 1, 1, '2026-03-13 10:13:02', '2026-03-23 14:13:07'),
(3696, 13, 467, 'Turkey Ham Carbonara 🌾🥛🥚', 'turkey-ham-carbonara', 'Turkey ham in rich creamy carbonara sauce with egg yolk and freshly grated parmesan, served with gratinated capsicum and cheese bruschetta.', 30000.00, NULL, 2, 1, '2026-03-13 10:13:02', '2026-03-23 14:13:42'),
(3697, 13, 468, 'Caprese Margherita (V) 🌾🥛', 'caprese-margherita', 'Tomato basil sauce, mozzarella, plum tomatoes and olive oil.', 16000.00, NULL, 1, 1, '2026-03-13 10:13:02', '2026-03-13 10:13:02'),
(3698, 13, 468, 'Seafood Alforno 🦐🐟🌾🥛', 'seafood-alforno', 'Shrimps, calamari, octopus, basil, peppers and mozzarella.', 25000.00, NULL, 2, 1, '2026-03-13 10:13:02', '2026-03-13 10:13:02'),
(3699, 13, 468, 'Dodo & Chicken Pizza 🌾🥛', 'dodo-chicken-pizza', 'Plantain with grilled chicken, peppers, basil and mozzarella.', 22000.00, NULL, 3, 1, '2026-03-13 10:13:02', '2026-03-13 10:13:02'),
(3700, 13, 469, 'Herb-Crusted Rack of Lamb 🥛', 'herb-crusted-rack-of-lamb', 'Oven roasted rack of lamb with mint jelly crust, char-grilled ratatouille, creamy cheddar mashed potatoes and garlic herb jus.', 49000.00, NULL, 1, 1, '2026-03-13 10:13:02', '2026-03-13 10:13:02'),
(3701, 13, 469, 'Crown of Beef 🌾🥛🍷', 'crown-of-beef', 'Flame grilled beef fillet medallion wrapped in puff pastry, served with baby vegetables, crispy pommes allumettes, gratinated fondant potatoes and cream onion truffle wine sauce.', 35000.00, NULL, 2, 1, '2026-03-13 10:13:02', '2026-03-13 10:13:02'),
(3702, 13, 470, 'Semi-Bone-Out Half Chicken 🌾🌶️', 'semi-bone-out-half-chicken', 'Grilled and oven finished half chicken seasoned with Peri-Peri or Lemon & Herb, served with crispy potato wedges, micro leaf salad, lentil onion bread stuffing and sauce of choice.', 32000.00, NULL, 1, 1, '2026-03-13 10:13:02', '2026-03-26 09:44:19'),
(3703, 13, 471, 'Parmesan Mussel & Herb–Encrusted Croaker 🐟🦐🥛', 'parmesan-mussel-herb-encrusted-croaker', 'Grilled croaker encrusted with parmesan, herbs and mussels, served with buttered baby vegetables and rustic potatoes finished with fish velouté.', 35000.00, NULL, 1, 1, '2026-03-13 10:13:02', '2026-03-13 10:13:02'),
(3704, 13, 471, 'Teriyaki Salmon 🐟🌰🍷', 'teriyaki-salmon', 'Grilled salmon glazed with soy, honey, chili, sesame oil, garlic and pickled ginger, served with julienne vegetables and wok egg noodles.', 49000.00, NULL, 2, 1, '2026-03-13 10:13:02', '2026-03-13 10:13:02'),
(3705, 13, 472, 'Crème Brûlée with Chocolate Chip Biscuit 🥛🥚🌾', 'creme-brulee-chocolate-chip-biscuit', 'Light baked custard with caramelized crust served with berry coulis and chocolate chip biscuit.', 12000.00, NULL, 1, 1, '2026-03-13 10:13:02', '2026-03-26 09:49:30'),
(3706, 13, 472, 'Ice Cream 🥛🥚', 'ice-cream', 'Ask your waiter for today’s flavor, topped with chocolate sprinkles and butter biscuit.', 15000.00, NULL, 2, 1, '2026-03-13 10:13:02', '2026-03-13 10:13:02'),
(3707, 13, 472, 'Freshly Cut Fruit Salad', 'freshly-cut-fruit-salad', 'Seasonal fresh fruit medley.', 9000.00, NULL, 3, 1, '2026-03-13 10:13:02', '2026-03-26 09:48:37'),
(3708, 13, 473, 'Still Water Large', 'still-water-large', NULL, 4000.00, NULL, 1, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3709, 13, 473, 'Still Water Small', 'still-water-small', NULL, 3000.00, NULL, 2, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3710, 13, 473, 'Perrier Sparkling Water Large', 'perrier-sparkling-water-large', '', 20000.00, NULL, 3, 1, '2026-03-14 17:42:54', '2026-03-26 10:09:15'),
(3711, 13, 473, 'Perrier Sparkling Water Small', 'perrier-sparkling-water-small', '', 8000.00, NULL, 4, 1, '2026-03-14 17:42:54', '2026-03-26 10:09:50'),
(3712, 13, 473, 'Soft Drinks (Coca Cola, Sprite, Tonic, Bitter Lemon, Soda Water, Fanta, Pepsi, Mirinda)', 'soft-drinks-mix', NULL, 3500.00, NULL, 5, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3713, 13, 473, 'Diet Coke', 'diet-coke', NULL, 0.00, NULL, 6, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3714, 13, 473, 'Maltina', 'maltina', NULL, 4500.00, NULL, 7, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3715, 13, 473, 'Amstel Malta', 'amstel-malta', NULL, 4500.00, NULL, 8, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3716, 13, 473, 'Malta Guinness', 'malta-guinness', NULL, 4500.00, NULL, 9, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3717, 13, 473, 'Fayrous', 'fayrous', NULL, 4500.00, NULL, 10, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3718, 13, 474, 'Fresh Juice Large', 'fresh-juice-large', NULL, 7000.00, NULL, 1, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3719, 13, 474, 'Fresh Juice Small', 'fresh-juice-small', NULL, 5000.00, NULL, 2, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3720, 13, 474, 'Fresh Fruit Punch Large', 'fresh-fruit-punch-large', NULL, 7000.00, NULL, 3, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3721, 13, 474, 'Fresh Fruit Punch Small', 'fresh-fruit-punch-small', NULL, 5500.00, NULL, 4, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3722, 13, 474, 'Packet Juice Large', 'packet-juice-large', NULL, 4500.00, NULL, 5, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3723, 13, 474, 'Packet Juice Small', 'packet-juice-small', NULL, 4000.00, NULL, 6, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3724, 13, 474, 'Juice Packet', 'juice-packet', NULL, 12000.00, NULL, 7, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3725, 13, 474, 'Cranberry Packet', 'cranberry-packet', NULL, 25000.00, NULL, 8, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3726, 13, 474, 'Cranberry Glass', 'cranberry-glass', NULL, 9000.00, NULL, 9, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3727, 13, 475, 'Power Horse', 'power-horse', NULL, 6000.00, NULL, 1, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3728, 13, 475, 'Red Bull', 'red-bull', NULL, 6500.00, NULL, 2, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3729, 13, 475, 'Climax', 'climax', NULL, 6000.00, NULL, 3, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3730, 13, 476, 'Star', 'star', NULL, 5000.00, NULL, 1, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3731, 13, 476, 'Heineken 60CL', 'heineken', '', 6500.00, NULL, 2, 1, '2026-03-14 17:42:54', '2026-04-22 11:57:32'),
(3732, 13, 476, 'Heineken Draught Large', 'heineken-draught-large', '', 6500.00, NULL, 3, 1, '2026-03-14 17:42:54', '2026-04-22 11:58:25'),
(3733, 13, 476, 'Heineken Draught Small', 'heineken-draught-small', '', 5500.00, NULL, 4, 1, '2026-03-14 17:42:54', '2026-04-22 11:59:06'),
(3734, 13, 476, 'Budweiser', 'budweiser', '', 6500.00, NULL, 5, 1, '2026-03-14 17:42:54', '2026-04-22 12:03:12'),
(3735, 13, 476, 'Guinness Extra Smooth', 'guinness-extra-smooth', NULL, 5000.00, NULL, 6, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3736, 13, 476, 'Guinness Stout 60cl', 'guinness-stout-60cl', '', 6500.00, NULL, 7, 1, '2026-03-14 17:42:54', '2026-04-22 12:04:00'),
(3737, 13, 476, 'Guinness Stout Medium', 'guinness-stout-medium', '', 5500.00, NULL, 8, 1, '2026-03-14 17:42:54', '2026-04-22 12:06:07'),
(3738, 13, 476, 'Star Radler Citrus', 'star-radler-citrus', NULL, 4500.00, NULL, 9, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3739, 13, 476, 'Trophy', 'trophy', '', 5000.00, NULL, 10, 1, '2026-03-14 17:42:54', '2026-04-22 12:01:47'),
(3740, 13, 476, '33 Export', '33-export', '', 5000.00, NULL, 11, 1, '2026-03-14 17:42:54', '2026-04-22 12:07:23'),
(3741, 13, 476, 'Life Beer', 'life-beer', '', 5000.00, NULL, 12, 1, '2026-03-14 17:42:54', '2026-04-22 12:08:06'),
(3742, 13, 476, 'Hero Beer', 'hero-beer', '', 5000.00, NULL, 13, 1, '2026-03-14 17:42:54', '2026-04-22 12:08:56'),
(3743, 13, 476, 'Gulder', 'gulder', NULL, 5000.00, NULL, 14, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3744, 13, 476, 'Goldberg', 'goldberg', '', 5000.00, NULL, 15, 1, '2026-03-14 17:42:54', '2026-04-22 12:09:38'),
(3745, 13, 476, 'Legend', 'legend', '', 5500.00, NULL, 16, 1, '2026-03-14 17:42:54', '2026-04-22 12:10:27'),
(3746, 13, 476, 'Tiger Beer', 'tiger-beer', '', 5000.00, NULL, 17, 1, '2026-03-14 17:42:54', '2026-04-22 12:13:10'),
(3747, 13, 476, 'Origin Beer', 'origin-beer', NULL, 5000.00, NULL, 18, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3748, 13, 476, 'Smirnoff Double Black', 'smirnoff-double-black', '', 5000.00, NULL, 19, 1, '2026-03-14 17:42:54', '2026-04-22 12:00:23'),
(3749, 13, 476, 'Smirnoff Ice 60cl', 'smirnoff-ice-60cl', NULL, 6000.00, NULL, 20, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3750, 13, 476, 'Smirnoff Ice 35cl', 'smirnoff-ice-35cl', NULL, 4500.00, NULL, 21, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3751, 13, 476, 'Desperado', 'desperado', NULL, 5000.00, NULL, 22, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3752, 13, 476, 'Flying Fish', 'flying-fish', NULL, 4500.00, NULL, 23, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3753, 13, 476, 'Castle Lite', 'castle-lite', NULL, 5000.00, NULL, 24, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3754, 13, 477, 'Martini Bianco', 'martini-bianco', 'Tot  N5,000', 50000.00, NULL, 1, 1, '2026-03-14 17:42:54', '2026-03-19 11:36:07'),
(3755, 13, 477, 'Martini Rosso', 'martini-rorro', 'Tot  N6,000', 60000.00, NULL, 2, 1, '2026-03-14 17:42:54', '2026-03-19 11:37:23'),
(3756, 13, 477, 'Martini Extra Dry', 'martini-extra-dry', 'Tot  5,000', 60000.00, NULL, 3, 1, '2026-03-14 17:42:54', '2026-03-19 11:43:28'),
(3757, 13, 477, 'Aperol Aperitivo', 'aperol-aperitivo', 'Tot  8,000', 80000.00, NULL, 4, 1, '2026-03-14 17:42:54', '2026-03-19 11:44:00'),
(3758, 13, 477, 'Campari', 'campari', 'Tot  9,000', 80000.00, NULL, 5, 1, '2026-03-14 17:42:54', '2026-03-26 10:12:52'),
(3759, 13, 478, 'Gordon', 'gordon', 'Tot  5,000', 40000.00, NULL, 1, 1, '2026-03-14 17:42:54', '2026-03-26 10:15:02'),
(3760, 13, 478, 'Beefeater', 'beefeater', 'Tot  5,500', 45000.00, NULL, 2, 1, '2026-03-14 17:42:54', '2026-03-26 10:15:43'),
(3761, 13, 478, 'Bombay Sapphire', 'bombay-sapphire', 'Tot  8,000', 80000.00, NULL, 3, 1, '2026-03-14 17:42:54', '2026-03-19 11:46:54'),
(3762, 13, 478, 'Hendrick', 'hendrick', 'Tot  10,000', 140000.00, NULL, 4, 1, '2026-03-14 17:42:54', '2026-03-19 15:24:59'),
(3763, 13, 478, 'Tanqueray 10', 'tanqueray-10', 'Tot   15,000', 140000.00, NULL, 5, 1, '2026-03-14 17:42:54', '2026-03-26 10:16:29'),
(3764, 13, 478, 'Monkey 47', 'monkey-47', 'Tot  10,000', 120000.00, NULL, 6, 1, '2026-03-14 17:42:54', '2026-03-26 10:17:01'),
(3765, 13, 479, 'Macallan 12 years', 'macallan-12-years', 'Tot  15,000', 250000.00, NULL, 1, 1, '2026-03-14 17:42:54', '2026-03-19 12:05:38'),
(3766, 13, 479, 'Macallan 15 years', 'macallan-15-years', 'Tot  28,000', 600000.00, NULL, 2, 1, '2026-03-14 17:42:54', '2026-03-19 12:06:15'),
(3767, 13, 479, 'Macallan 18 years', 'macallan-18-years', 'Tot 100,000', 1600000.00, NULL, 3, 1, '2026-03-14 17:42:54', '2026-03-26 10:29:07'),
(3768, 13, 479, 'Glenfiddich 12 years', 'glenfiddich-12-years', 'Tot  14,000', 190000.00, NULL, 4, 1, '2026-03-14 17:42:54', '2026-03-26 10:30:13'),
(3769, 13, 479, 'Glenfiddich 15 years', 'glenfiddich-15-years', 'Tot  25,000', 300000.00, NULL, 5, 1, '2026-03-14 17:42:54', '2026-04-28 11:39:19'),
(3770, 13, 479, 'Glenfiddich 18 years', 'glenfiddich-18-years', 'Tot  30,000', 380000.00, NULL, 6, 1, '2026-03-14 17:42:54', '2026-03-26 10:32:03'),
(3771, 13, 479, 'Singleton 12 years', 'singleton-12-years', 'Tot  14,000', 200000.00, NULL, 7, 1, '2026-03-14 17:42:54', '2026-03-19 12:10:12'),
(3772, 13, 479, 'Singleton 15 years', 'singleton-15-years', 'Tot   25,000', 260000.00, NULL, 8, 1, '2026-03-14 17:42:54', '2026-03-26 10:33:29'),
(3773, 13, 479, 'Singleton 18 years', 'singleton-18-years', 'Tot  45,000', 650000.00, NULL, 9, 1, '2026-03-14 17:42:54', '2026-03-19 12:11:58'),
(3774, 13, 480, 'Chivas Regal 12 years', 'chivas-regal-12-years', 'Tot  7,500', 120000.00, NULL, 1, 1, '2026-03-14 17:42:54', '2026-03-19 11:52:07'),
(3775, 13, 480, 'Chivas Regal 18 years', 'chivas-regal-18-years', 'Tot 12,500', 310000.00, NULL, 2, 1, '2026-03-14 17:42:54', '2026-03-19 11:53:01'),
(3776, 13, 480, 'Smokey Monkey', 'smokey-monkey', 'Tot  12,000', 200000.00, NULL, 3, 1, '2026-03-14 17:42:54', '2026-03-19 11:54:54'),
(3777, 13, 480, 'Chivas Regal 25 years', 'chivas-regal-25-years', 'Tot  45,000', 800000.00, NULL, 4, 1, '2026-03-14 17:42:54', '2026-03-19 11:55:49'),
(3778, 13, 480, 'Johnnie Walker Black Label', 'johnnie-walker-black-label', 'Tot  9,000', 120000.00, NULL, 6, 1, '2026-03-14 17:42:54', '2026-03-19 11:57:48'),
(3779, 13, 480, 'Johnnie Walker Gold Label', 'johnnie-walker-gold-label', 'Tot  20,000', 240000.00, NULL, 7, 1, '2026-03-14 17:42:54', '2026-03-19 11:58:44'),
(3780, 13, 480, 'Johnnie Walker Platinum Label', 'johnnie-walker-platinum-label', 'Tot  28,000', 450000.00, NULL, 8, 1, '2026-03-14 17:42:54', '2026-03-19 12:00:44'),
(3781, 13, 480, 'Johnnie Walker Blue Label', 'johnnie-walker-blue-label', 'Tot  90,000', 1100000.00, NULL, 9, 1, '2026-03-14 17:42:54', '2026-04-28 11:35:15'),
(3782, 13, 481, 'Jameson', 'jameson', 'Tot  8,000', 60000.00, NULL, 1, 1, '2026-03-14 17:42:54', '2026-03-26 10:35:44'),
(3783, 13, 481, 'Jameson Black Barrel', 'jameson-black-barrel', 'Tot  12,,000', 130000.00, NULL, 2, 1, '2026-03-14 17:42:54', '2026-03-26 10:36:24'),
(3784, 13, 481, 'Jack Daniel', 'jack-daniel', 'Tot  8,000', 90000.00, NULL, 3, 1, '2026-03-14 17:42:54', '2026-04-28 11:32:15'),
(3785, 13, 481, 'Jack Daniel Gentleman Jack', 'jack-daniel-gentleman-jack', 'Tot  10,000', 140000.00, NULL, 4, 1, '2026-03-14 17:42:54', '2026-03-26 10:38:01'),
(3786, 13, 481, 'Jack Daniel Honey', 'jack-daniel-honey', 'Tot   9,000', 80000.00, NULL, 5, 1, '2026-03-14 17:42:54', '2026-03-26 10:39:16'),
(3787, 13, 481, 'Jack Daniel Single Barrel Select', 'jack-daniel-single-barrel-select', 'Tot  15,000', 140000.00, NULL, 6, 1, '2026-03-14 17:42:54', '2026-03-26 10:40:14'),
(3788, 13, 481, 'Jack Daniel Apple', 'jack-daniel-apple', 'Tot  9,000', 90000.00, NULL, 7, 1, '2026-03-14 17:42:54', '2026-04-28 11:36:56'),
(3789, 13, 481, 'Woodford Reserve', 'woodford-reserve', 'Tot 13,000', 130000.00, NULL, 8, 1, '2026-03-14 17:42:54', '2026-03-26 10:41:27'),
(3790, 13, 481, 'Wild Turkey', 'wild-turkey', 'Tot  8,000', 80000.00, NULL, 9, 1, '2026-03-14 17:42:54', '2026-04-28 12:16:28'),
(3791, 13, 482, 'Smirnoff Red', 'smirnoff-red', 'Tot  6,000', 70000.00, NULL, 1, 1, '2026-03-14 17:42:54', '2026-03-19 13:27:36'),
(3792, 13, 482, 'Smirnoff Blue', 'smirnoff-blue', 'Tot  6,000', 75000.00, NULL, 2, 1, '2026-03-14 17:42:54', '2026-03-19 13:28:19'),
(3793, 13, 482, 'Ciroc', 'ciroc', 'Tot  10,000', 120000.00, NULL, 3, 1, '2026-03-14 17:42:54', '2026-03-26 10:44:10'),
(3794, 13, 482, 'Neft Vodka', 'neft-vodka', 'Tot  10,000', 130000.00, NULL, 4, 1, '2026-03-14 17:42:54', '2026-03-19 13:31:23'),
(3795, 13, 482, 'Absolut Blue', 'absolut-blue', 'Tot  5,500', 65000.00, NULL, 5, 1, '2026-03-14 17:42:54', '2026-03-19 13:31:58'),
(3796, 13, 482, 'Grey Goose', 'grey-goose', 'Tot  12,000', 150000.00, NULL, 6, 1, '2026-03-14 17:42:54', '2026-03-26 10:44:57'),
(3797, 13, 483, 'Bacardi', 'bacardi', 'Tot  5,000', 60000.00, NULL, 1, 1, '2026-03-14 17:42:54', '2026-03-19 13:33:28'),
(3798, 13, 483, 'Captain Morgan', 'captain-morgan', 'Tot  5,000', 50000.00, NULL, 2, 1, '2026-03-14 17:42:54', '2026-03-19 13:34:11'),
(3799, 13, 483, 'St James', 'st-james', 'Tot  8,000', 99000.00, NULL, 3, 1, '2026-03-14 17:42:54', '2026-03-19 13:34:56'),
(3800, 13, 483, 'Malibu', 'malibu', 'Tot  5,000', 60000.00, NULL, 4, 1, '2026-03-14 17:42:54', '2026-03-19 13:35:31'),
(3801, 13, 484, 'Remy Martin XO', 'remy-martin-xo', 'Tot  75,000', 1100000.00, NULL, 1, 1, '2026-03-14 17:42:54', '2026-03-26 10:53:20'),
(3802, 13, 484, 'Remy Martin VSOP', 'remy-martin-vsop', 'Tot 25,000', 300000.00, NULL, 2, 1, '2026-03-14 17:42:54', '2026-03-26 10:54:01'),
(3803, 13, 484, 'Hennessy XO', 'hennessy-xo', 'Tot  75,000', 1200000.00, NULL, 3, 1, '2026-03-14 17:42:54', '2026-03-19 13:48:11'),
(3804, 13, 484, 'Hennessy VSOP', 'hennessy-vsop', 'Tot  25,000', 320000.00, NULL, 4, 1, '2026-03-14 17:42:54', '2026-04-28 11:45:46'),
(3805, 13, 484, 'Hennessy VS', 'hennessy-vs', 'Tot 20,000', 160000.00, NULL, 5, 1, '2026-03-14 17:42:54', '2026-03-26 10:55:53'),
(3806, 13, 484, 'Martel VS', 'martel-vs', 'Tot 14,000', 150000.00, NULL, 6, 1, '2026-03-14 17:42:54', '2026-03-19 13:52:13'),
(3807, 13, 484, 'Martel Blue Swift', 'martel-blue-swift', 'Tot  20,000', 260000.00, NULL, 7, 1, '2026-03-14 17:42:54', '2026-04-28 11:59:28'),
(3808, 13, 484, 'Martel XO', 'martel-xo', 'Tot  65,000', 1000000.00, NULL, 8, 1, '2026-03-14 17:42:54', '2026-03-26 11:02:10'),
(3809, 13, 484, 'Remy Martin 1738', 'remy-martin-1738', 'Tot  30,000', 350000.00, NULL, 9, 1, '2026-03-14 17:42:54', '2026-03-26 11:02:57'),
(3810, 13, 485, 'Olmeca Gold', 'olmeca-gold', 'Tot  8,000', 80000.00, NULL, 1, 1, '2026-03-14 17:42:54', '2026-03-19 13:57:01'),
(3811, 13, 485, 'El Padrino', 'el-padrino', 'Tot  14,000', 230000.00, NULL, 2, 1, '2026-03-14 17:42:54', '2026-04-22 12:22:19'),
(3812, 13, 485, 'Sierra Gold', 'sierra-gold', 'Tot  7,000', 70000.00, NULL, 3, 1, '2026-03-14 17:42:54', '2026-03-19 14:01:52'),
(3813, 13, 485, 'Sierra White', 'sierra-white', 'Tot  7,000', 70000.00, NULL, 4, 1, '2026-03-14 17:42:54', '2026-03-19 14:03:14'),
(3814, 13, 485, 'Cazcabel Reposado', 'cazcabel-reposado', 'Tot  12,000', 160000.00, NULL, 5, 1, '2026-03-14 17:42:54', '2026-03-19 14:04:48'),
(3815, 13, 486, 'Cointreau', 'cointreau', 'Tot  8,000', 90000.00, NULL, 3, 1, '2026-03-14 17:42:54', '2026-03-19 14:37:29'),
(3816, 13, 486, 'Baileys Irish Cream', 'baileys-irish-cream', 'Tot  8,000', 80000.00, NULL, 4, 1, '2026-03-14 17:42:54', '2026-03-19 14:42:18'),
(3817, 13, 486, 'Amarula', 'amarula', 'Tot  8,000', 80000.00, NULL, 5, 1, '2026-03-14 17:42:54', '2026-03-19 14:43:24'),
(3818, 13, 486, 'Amaretto', 'amaretto', 'Tot  8,000', 80000.00, NULL, 6, 1, '2026-03-14 17:42:54', '2026-03-19 14:44:23'),
(3819, 13, 486, 'Tia Maria', 'tia-maria', 'Tot  8,000', 80000.00, NULL, 7, 1, '2026-03-14 17:42:54', '2026-03-19 14:45:22'),
(3820, 13, 486, 'Sambuca', 'sambuca', 'Tot  7,000', 75000.00, NULL, 8, 1, '2026-03-14 17:42:54', '2026-03-19 14:46:51'),
(3821, 13, 486, 'Drambuie', 'drambuie', 'Tot  7,000', 75000.00, NULL, 9, 1, '2026-03-14 17:42:54', '2026-03-19 14:48:07'),
(3822, 13, 486, 'Kahlua', 'kahlua', 'Tot  9,000', 90000.00, NULL, 10, 1, '2026-03-14 17:42:54', '2026-03-19 14:49:35'),
(3823, 13, 486, 'Grappa Nonino', 'grappa-nonino', 'Tot  9,000', 95000.00, NULL, 11, 1, '2026-03-14 17:42:54', '2026-03-19 14:51:16'),
(3824, 13, 487, 'Americano', 'americano', NULL, 6000.00, NULL, 1, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3825, 13, 487, 'Cappuccino', 'cappuccino', NULL, 6500.00, NULL, 2, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3826, 13, 487, 'Espresso', 'espresso', NULL, 6000.00, NULL, 3, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3827, 13, 487, 'Double Espresso', 'double-espresso', NULL, 6500.00, NULL, 4, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3828, 13, 487, 'Café Latte', 'cafe-latte', NULL, 6500.00, NULL, 5, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3829, 13, 487, 'Macchiato', 'macchiato', NULL, 6000.00, NULL, 6, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3830, 13, 487, 'Hot Chocolate', 'hot-chocolate', NULL, 6500.00, NULL, 7, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3831, 13, 487, 'Assorted Tea', 'assorted-tea', '', 5500.00, NULL, 8, 1, '2026-03-14 17:42:54', '2026-03-27 08:16:52'),
(3832, 13, 487, 'Caramel Frappe', 'caramel-frappe', NULL, 6500.00, NULL, 9, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3833, 13, 487, 'Strawberry Frappe', 'strawberry-frappe', NULL, 6500.00, NULL, 10, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3834, 13, 487, 'Banana Frappe', 'banana-frappe', NULL, 6500.00, NULL, 11, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3835, 13, 488, 'Man Sauvignon Blanc South Africa', 'man-sauvignon-blanc-south-africa', NULL, 80000.00, NULL, 1, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3836, 13, 488, 'Maison Castel', 'maison-castel', '', 70000.00, NULL, 3, 1, '2026-03-14 17:42:54', '2026-05-02 20:15:47'),
(3837, 13, 488, 'Riunite Moscato', 'riunite-moscato', '', 60000.00, NULL, 4, 1, '2026-03-14 17:42:54', '2026-03-26 15:50:54'),
(3838, 13, 488, 'Protea Pinot Grigio', 'protea-pinot-grigio', NULL, 80000.00, NULL, 5, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3839, 13, 488, 'Bosio Moscato Vino Spumante Dolce', 'bosio-moscato-vino-spumante-dolce', '', 70000.00, NULL, 6, 1, '2026-03-14 17:42:54', '2026-03-26 15:52:14'),
(3840, 13, 488, 'Klein Constantia Estate Sauvignon', 'klein-constantia-estate-sauvignon', NULL, 80000.00, NULL, 7, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3841, 13, 488, 'Protea Sauvignon Blanc', 'protea-sauvignon-blanc', NULL, 70000.00, NULL, 9, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3842, 13, 488, 'Protea Chardonnay', 'protea-chardonnay', NULL, 70000.00, NULL, 10, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3843, 13, 488, 'Clarington Unwood Chardonnay South Africa', 'clarington-unwood-chardonnay-south-africa', NULL, 90000.00, NULL, 11, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3844, 13, 488, 'Paul Cluver Riesling South Africa', 'paul-cluver-riesling-south-africa', NULL, 90000.00, NULL, 13, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3845, 13, 488, 'Vodeling Sweet Carolyn', 'vodeling-sweet-carolyn', '', 120000.00, NULL, 14, 1, '2026-03-14 17:42:54', '2026-05-02 20:21:30'),
(3846, 13, 489, 'Man Cabernet Sauvignon South Africa', 'man-cabernet-sauvignon-south-africa', NULL, 70000.00, NULL, 1, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3847, 13, 489, 'Escudo Rojo', 'escudo-rojo', '', 80000.00, NULL, 2, 1, '2026-03-14 17:42:54', '2026-03-27 07:17:35'),
(3848, 13, 489, 'Cooper & Thief', 'cooper-thief', '', 145000.00, NULL, 3, 1, '2026-03-14 17:42:54', '2026-03-27 07:15:18'),
(3849, 13, 489, 'Penfolds Father Grand Tawny 10', 'penfolds-father-grand-tawny-10', '', 80000.00, NULL, 4, 1, '2026-03-14 17:42:54', '2026-03-27 07:21:12'),
(3850, 13, 489, 'Protea Cabernet Sauvignon', 'protea-cabernet-sauvignon', NULL, 80000.00, NULL, 8, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3851, 13, 489, 'Jordan The Prospector South Africa', 'jordan-the-prospector-south-africa', NULL, 150000.00, NULL, 9, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3852, 13, 489, 'Chateau Pouyanne France', 'chateau-pouyanne-france', NULL, 10000.00, NULL, 10, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3853, 13, 489, 'Saumur Champigny Cabernet Franc France', 'saumur-champigny-cabernet-franc-france', NULL, 70000.00, NULL, 12, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3854, 13, 490, 'Painted Wolf The Den Dry Rosé', 'painted-wolf-the-den-dry-rose', NULL, 130000.00, NULL, 2, 1, '2026-03-14 17:42:54', '2026-03-14 17:42:54'),
(3855, 13, 491, 'Moet et Chandon Brut Imperial', 'moet-et-chandon-brut-imperial', '', 330000.00, NULL, 1, 1, '2026-03-14 17:42:54', '2026-04-25 12:36:43'),
(3856, 13, 491, 'Moet et Chandon Nectar Imperial Rosé', 'moet-et-chandon-nectar-imperial-rose', '', 350000.00, NULL, 2, 1, '2026-03-14 17:42:54', '2026-04-25 12:37:41'),
(3857, 13, 491, 'Moet Chandon Ice Imperial', 'moet-chandon-ice-imperial', '', 550000.00, NULL, 3, 1, '2026-03-14 17:42:54', '2026-03-19 15:19:00'),
(3858, 13, 491, 'Dom Perignon Vintage Brut', 'dom-perignon-vintage-brut', '', 1400000.00, NULL, 4, 1, '2026-03-14 17:42:54', '2026-04-25 12:39:28'),
(3859, 13, 491, 'Dom Perignon Vintage Rosé', 'dom-perignon-vintage-rose', '', 1500000.00, NULL, 5, 1, '2026-03-14 17:42:54', '2026-04-25 12:40:14'),
(3860, 13, 491, 'Veuve Clicquot Brut', 'veuve-clicquot-brut', '', 310000.00, NULL, 6, 1, '2026-03-14 17:42:54', '2026-04-28 10:42:29'),
(3861, 13, 491, 'Veuve Clicquot Rich', 'veuve-clicquot-rich', '', 445000.00, NULL, 7, 1, '2026-03-14 17:42:54', '2026-04-28 10:43:36'),
(3862, 13, 476, 'Heineken 45CL', 'h', '', 5000.00, NULL, 2, 1, '2026-03-18 12:57:01', '2026-03-18 12:57:01'),
(3863, 13, 479, 'Glenfiddich 21 year', 'g', 'Tot 90,000', 1000000.00, NULL, 0, 1, '2026-03-18 13:06:58', '2026-03-26 10:18:37'),
(3864, 13, 479, 'Glenfiddich grand cortes  XXII', 'glenfiddich-grand-cortes-xxii', 'Tot 95,000', 1300000.00, NULL, 0, 1, '2026-03-18 13:08:30', '2026-03-30 11:32:07'),
(3865, 13, 483, 'Embargo  Anejo Bianco', 'e', 'Tot 6,000', 70000.00, NULL, 0, 1, '2026-03-19 13:37:35', '2026-03-26 10:48:46'),
(3866, 13, 483, 'Embargo Anejo Extra', 'embargo-anejo-extra', 'Tot 12,000', 100000.00, NULL, 0, 1, '2026-03-19 13:42:48', '2026-03-26 10:49:33'),
(3867, 13, 485, 'Olmeca Bianco', 'o', 'Tot 7500', 75000.00, NULL, 0, 1, '2026-03-19 13:59:21', '2026-03-30 11:43:06'),
(3868, 13, 485, 'Casamigo Reposado', 'c', 'Tot  25,000', 500000.00, NULL, 0, 1, '2026-03-19 14:14:25', '2026-04-25 12:27:30'),
(3869, 13, 485, 'Aman Rosa Blanco', 'a', 'Tot  28,000', 420000.00, NULL, 0, 1, '2026-03-19 14:24:28', '2026-04-25 12:29:20'),
(3870, 13, 485, 'Don Julio 1942', 'd', 'Tot  70,000', 1000000.00, NULL, 0, 1, '2026-03-19 14:26:31', '2026-03-26 11:35:08'),
(3871, 13, 487, 'Coffee', 'c', '', 6000.00, NULL, 0, 1, '2026-03-19 14:53:12', '2026-03-19 14:53:12'),
(3872, 13, 488, 'Santa Cristina pinot grigio', 's', '', 70000.00, NULL, 0, 1, '2026-03-19 14:56:19', '2026-03-26 15:56:48'),
(3873, 13, 488, 'Diemersdal Cape sauvignon', 'd', '', 70000.00, NULL, 0, 1, '2026-03-19 14:57:50', '2026-03-26 15:59:06'),
(3874, 13, 488, 'Pamille Perrin La Vielle Ferme Blanc', 'p', '', 70000.00, NULL, 0, 1, '2026-03-19 14:59:13', '2026-03-26 15:58:18'),
(3875, 13, 489, 'Santa Cristina Fattoria Le Maestrelle Toscana', 's', '', 80000.00, NULL, 0, 1, '2026-03-19 15:02:28', '2026-03-27 07:14:03'),
(3876, 13, 489, 'Darling Cellar Sweet Red', 'd', '', 70000.00, NULL, 0, 1, '2026-03-19 15:03:17', '2026-03-19 15:03:17'),
(3877, 13, 489, 'Chateau Beausejour Hostens', 'c', '', 80000.00, NULL, 0, 1, '2026-03-19 15:13:10', '2026-03-27 07:10:27'),
(3878, 13, 489, 'Dona Paula Blue Edition', 'dona-paula-blue-edition', 'Dona Paula Blue Edition', 120000.00, NULL, 0, 1, '2026-03-19 16:18:50', '2026-03-19 16:19:59'),
(3879, 13, 489, 'Dona Paula Malbec', 'dona-paula-malbec', '', 120000.00, NULL, 0, 1, '2026-03-19 16:22:04', '2026-03-19 16:22:04'),
(3880, 13, 485, 'Casamigo Anejo', 'casamigo-anejo', 'Tot 30,000', 440000.00, NULL, 0, 1, '2026-03-19 16:23:25', '2026-04-25 12:25:54'),
(3881, 13, 489, 'Swartland Serengeti Sweet Red', 's-2', '', 70000.00, NULL, 0, 1, '2026-03-19 17:04:07', '2026-03-19 17:04:07'),
(3882, 13, 472, 'Chocolate brownie with vanilla ice cream', 'chocolate-brownie-with-vanilla-ice-cream-22VE', 'Tender homemade brownie, made with premium Belgian chocolate, \r\naccompanied with vanilla ice cream drizzled and chocolate sauce', 18000.00, NULL, 0, 1, '2026-03-21 10:40:28', '2026-06-17 14:51:47'),
(3883, 13, 454, 'Smokey Jollof Rice', 's', 'Authentic smoky Jollof rice cooked in a rich tomato and pepper base, served with your choice of protein, crisp coleslaw, and \r\ngolden fried plantain.', 26000.00, NULL, 0, 1, '2026-03-23 13:29:32', '2026-03-23 13:29:32'),
(3884, 13, 461, 'Full Roasted Cat Fish', 'f', 'Whole fresh catfish, expertly marinated and charcoal-roasted to perfection.\r\nServed with fragrant steamed rice, rich atarodo tomato pepper sauce, and your choice of suya-spiced sweet potato or classic \r\nIrish potatoes.', 32000.00, NULL, 0, 1, '2026-03-23 13:33:57', '2026-03-23 13:33:57'),
(3885, 13, 461, 'Mixed Platter', 'm', 'A bold selection of flame - grilled favorites —succulent turkey cuts, whole roasted catfish, grilled tilapia, jumbo prawns, and \r\nspiced beef suya , served with suya - dusted sweet potato wedges or crispy yam chips, accompanied by vibrant atarodo pepper sauce.', 190000.00, NULL, 0, 1, '2026-03-23 13:38:52', '2026-03-23 13:38:52'),
(3886, 13, 471, 'Fish Fillet', 'f', 'Grilled  croaker, served with mashed potatoes and side salad, finished with a lemon butter cream sauce.', 35000.00, NULL, 0, 1, '2026-03-23 13:42:08', '2026-03-23 13:42:08'),
(3887, 13, 471, 'Newburg Croaker with Prawn', 'newburg-croaker-with-prawn-atlantic-seared-croaker-fillet-3-00g-topped-wi', 'Atlantic seared croaker fillet - 3 00g topped with jumbo prawn & matched with cream parmesan mustard sauce mashed potato and seasonal vegetables', 46000.00, NULL, 0, 1, '2026-03-23 13:47:58', '2026-03-23 13:47:58'),
(3888, 13, 471, 'Coriander & Black Pepper Salmon', 'coriander-black-pepper-salmon', '300g  freshwater coriander and black pepper coated grilled Salmon, accompanied by light fish veloute sauce,  mashed potato and seasonal vegetables', 55000.00, NULL, 0, 1, '2026-03-23 13:49:56', '2026-03-23 13:49:56'),
(3889, 13, 471, 'Grilled Prawns', 'g', 'Grilled tiger prawns served with sauté potato and spicy vegetable sauce', 45000.00, NULL, 0, 1, '2026-03-23 13:51:38', '2026-03-23 13:51:38'),
(3890, 13, 492, 'Magarita Pizza', 'magarita-pizza', 'Medium base pizza, topped with delicious pizziola sauce, gratinated mozzarella cheese and oregano', 21000.00, NULL, 0, 1, '2026-03-23 13:55:53', '2026-03-23 13:55:53'),
(3891, 13, 492, 'Quatro Chicken Supreme Pizza', 'quatro-chicken-supreme-pizza', 'BBQ chicken, mixed bell peppers, mushrooms, gratinated mozzarella & feta cheese with oregano', 23500.00, NULL, 0, 1, '2026-03-23 13:57:19', '2026-03-23 13:57:19'),
(3892, 13, 492, 'Cheesy Regina Melt', 'ch-eesy-regina-melt', 'Smoked turkey ham, mushrooms, gratinated mozzarella & gouda cheese with a hint of oregano', 24500.00, NULL, 0, 1, '2026-03-23 13:58:52', '2026-03-23 13:58:52'),
(3893, 13, 492, 'Seafood Alforno Pizza', 'seafood-alforn-o-pizza', 'Shrimps, calamari, octopus, basil, peppers & gratinated mozzarella & gouda cheese with a hint of oregano', 24500.00, NULL, 0, 1, '2026-03-23 14:00:18', '2026-03-23 14:00:18'),
(3894, 13, 467, 'Spicy Penne Arabiatta', 'spicy-penne-arabiatta', 'Penne, mixed bell peppers, onion & garlic tossed \r\nin chili pepper sauce, served with gratinated French bread & parmesan cheese', 28500.00, NULL, 0, 1, '2026-03-23 14:09:28', '2026-03-23 14:09:28'),
(3895, 13, 467, 'Spaghetti Con Ragout', 'spaghetti-con-ragout', 'Bolognaise sauce infused spaghetti with gratinated French bread & parmesan cheese', 28500.00, NULL, 0, 1, '2026-03-23 14:11:02', '2026-03-23 14:11:02'),
(3896, 13, 467, 'Creamy Cheesy Chicken Alforno', 'creamy-cheesy-chicken-alforno', 'Your choice of penne, spaghetti or farfalle, tender sautéed chicken, cooked in creamy cheesy garlic sauce, gratinated with French bread & parmesan cheese', 30000.00, NULL, 0, 1, '2026-03-23 14:12:14', '2026-03-23 14:12:14'),
(3897, 13, 458, 'Pepper Soup', 'pepper-soup', 'Goat meat,  Mixed meat, chicken or croaker ,  pepper soup of the day, served with cocktail roll with butter', 15000.00, NULL, 0, 1, '2026-03-23 14:19:00', '2026-03-23 14:19:00'),
(3898, 13, 458, 'Continental Soup of the Day', 'continental-soup-of-the-day-2', 'Pumpkin soup, served with cocktail roll with butter', 15000.00, NULL, 1, 1, '2026-03-23 14:22:24', '2026-03-26 09:27:44'),
(3899, 13, 465, 'Smoked Salmon Rosette', 'sm-oked-salmon-rosette', 'Smoked salmon rosette with coddled egg, garden salad, smooth cream cheese, French dressing capers and red onion with a hint of lemon', 28000.00, NULL, 3, 1, '2026-03-23 14:25:05', '2026-03-27 13:19:48'),
(3900, 13, 465, 'Spicy Chicken Wings', 'spicy-chicken-wings', 'Coated fried spicy chicken wings served with sweet potato.', 20000.00, NULL, 5, 1, '2026-03-23 14:26:14', '2026-03-27 13:19:11'),
(3901, 13, 458, 'Whole Cat Fish Pepper Soup', 'w', 'Whole fresh catfish cooked to perfection served with fragrant steamed rice, \r\nrich atarodo tomato pepper sauce, and your choice of suya-spiced sweet potato or classic Irish potatoes', 32000.00, NULL, 2, 1, '2026-03-26 09:22:54', '2026-03-26 09:28:31'),
(3902, 13, 493, 'Lusso Club Sandwich', 'lusso-club-sandwich', 'Double decker with chicken & turkey ham, fried eggs, lettuce tomato, pickles, mayonnaise spread & mozzarella cheese \r\naccompanied with fries and homemade coleslaw', 28000.00, NULL, 0, 1, '2026-03-26 09:40:56', '2026-03-26 09:40:56'),
(3903, 13, 494, 'The Giant Burger', 'the-giant-burger', 'Signature beef patty with back bacon, lettuce, tomato, coated onions, gherkins, mustard mayo, gratinated mozzarella &\r\nsesame bun.', 32000.00, '69e27433b76cc.webp', 0, 1, '2026-03-26 09:43:02', '2026-04-17 17:56:03'),
(3904, 13, 473, 'Voss', 'v', '', 10000.00, NULL, 1, 1, '2026-03-26 10:08:34', '2026-03-26 10:08:34'),
(3905, 13, 482, 'Sky Vodka Infusion Raspberry', 'sky-vodka-infusion-raspberry', 'Tot  6000', 70000.00, NULL, 0, 1, '2026-03-26 10:47:07', '2026-03-26 10:47:07'),
(3906, 13, 485, 'Don Julio Reposado', 'don-julio-reposado', 'Tot  30000', 550000.00, NULL, 0, 1, '2026-03-26 11:22:58', '2026-03-26 11:22:58'),
(3907, 13, 489, 'La Vielle Ferme', 'l', '', 80000.00, NULL, 0, 1, '2026-03-27 07:29:14', '2026-03-27 07:29:14'),
(3908, 13, 489, 'Darling Cellar Sweet Red', 'd-2', '', 70000.00, NULL, 0, 1, '2026-03-27 12:48:38', '2026-03-27 12:48:38'),
(3909, 13, 485, 'El Padrino De Ni Tierra Anejo', 'e', 'Tot  28000', 400000.00, NULL, 0, 1, '2026-03-30 11:59:04', '2026-03-30 11:59:04'),
(3910, 13, 495, 'Arabian Tea Large', 'a', 'Small = 16,500', 23500.00, NULL, 0, 1, '2026-04-02 13:52:25', '2026-05-02 19:59:04'),
(3911, 13, 495, 'Moringa Tea', 'm', '', 8000.00, NULL, 0, 1, '2026-04-02 13:53:13', '2026-04-02 13:53:13'),
(3912, 13, 495, 'Hibiscus Tea', 'h', '', 15000.00, NULL, 0, 1, '2026-04-02 13:53:38', '2026-05-02 20:02:45'),
(3913, 13, 495, 'Hibiscus & Moringa Tea', 'h-2', '', 15000.00, NULL, 0, 1, '2026-04-02 13:54:28', '2026-05-02 20:01:54'),
(3914, 13, 495, 'Herbal Infused Tea', 'h-3', '', 15000.00, NULL, 0, 1, '2026-04-02 13:56:12', '2026-05-02 20:01:09'),
(3915, 13, 471, 'Jumbo Prawns', 'j', '', 55000.00, NULL, 0, 1, '2026-04-22 11:19:09', '2026-04-22 11:19:09'),
(3916, 13, 489, 'Nederburg Merlot', 'n', '', 90000.00, NULL, 0, 1, '2026-04-22 11:24:50', '2026-04-22 11:24:50'),
(3917, 13, 489, 'Nederburg Cab Sauvignon', 'n-2', '', 90000.00, NULL, 0, 1, '2026-04-22 11:43:40', '2026-04-22 11:43:40'),
(3918, 13, 489, 'La Fiole Chateanuf Du Pape', 'l-2', '', 160000.00, NULL, 0, 1, '2026-04-22 11:45:45', '2026-04-22 11:45:45'),
(3919, 13, 489, 'Sand Stone', 's-3', '', 90000.00, NULL, 0, 1, '2026-04-22 11:52:20', '2026-04-22 11:52:20'),
(3920, 13, 496, 'Cosmopolitan', 'c', 'vodka,, tripple sec, fresh squeeze lime juice, cranberry juice', 22500.00, NULL, 0, 1, '2026-04-22 14:39:25', '2026-05-02 20:04:13'),
(3921, 13, 496, 'Planter\'s Island', 'p', 'dark rum, orange juice, pineapple juice, lemon, grenadine syrup', 18500.00, NULL, 0, 1, '2026-04-22 14:41:25', '2026-05-02 20:07:46'),
(3922, 13, 496, 'Pina colada', 'p-2', 'white rum, coconut cream, fresh pineapple juice, coconut rum', 22500.00, NULL, 0, 1, '2026-04-22 15:17:52', '2026-05-02 20:06:33'),
(3923, 13, 496, 'Mojito', 'm', 'white rum,  mint leaves, fresh squeeze lime juice, simple syrup, soda water', 22500.00, NULL, 0, 1, '2026-04-22 15:20:01', '2026-05-02 20:06:04'),
(3924, 13, 496, 'Long Island Ice Tea', 'l', 'tequila, gin, bacardi , vodka, tripple sec, coke', 23000.00, NULL, 0, 1, '2026-04-22 15:23:01', '2026-05-02 20:05:28'),
(3925, 13, 496, 'Whiskey Sour', 'w', 'bourbon whiskey, egg white( optional),sugar syrup, lemon juice', 18500.00, NULL, 0, 1, '2026-04-22 15:37:48', '2026-05-02 20:07:11'),
(3926, 13, 496, 'White Russian', 'w-2', 'vodka, kahlua, cream', 18500.00, NULL, 0, 1, '2026-04-22 15:58:44', '2026-05-02 20:08:51'),
(3927, 13, 497, 'Chapman', 'c', 'fanta, sprite, orange juice, bitter lemon, grenadine , angostura', 6500.00, NULL, 0, 1, '2026-04-22 16:03:58', '2026-04-22 16:03:58'),
(3928, 13, 497, 'Virgin Colada', 'v', 'fresh pineapple juice, coconut cream, whip cream', 6500.00, NULL, 0, 1, '2026-04-22 17:03:54', '2026-04-22 17:03:54'),
(3929, 13, 497, 'Virgin Mojito', 'v-2', 'mint leaves, soda water, simple syrup, sprite , lime', 6500.00, NULL, 0, 1, '2026-04-22 17:05:26', '2026-04-22 17:05:26'),
(3930, 13, 497, 'Blue Sky', 'b', 'vanilla ice cream, sprite, blue curacao, egg white optional', 6500.00, NULL, 0, 1, '2026-04-22 17:06:43', '2026-04-22 17:06:43'),
(3931, 13, 497, 'couples Delight', 'c-2', 'pineapple juice, apple juice, orange juice , passion fruit', 6500.00, NULL, 0, 1, '2026-04-22 17:09:38', '2026-04-22 17:09:38'),
(3932, 13, 497, 'Cranberry Cooler', 'c-3', 'cranberry juice, grenadine , cream', 6500.00, NULL, 0, 1, '2026-04-22 17:11:24', '2026-04-22 17:11:24'),
(3933, 13, 485, 'Casamigo Reposado 100cl', 'c-2', '', 600000.00, NULL, 0, 1, '2026-04-25 12:24:39', '2026-04-25 12:24:39'),
(3934, 13, 485, 'Aman Anejo', 'a-2', '', 750000.00, NULL, 0, 1, '2026-04-25 12:30:29', '2026-04-25 12:30:29'),
(3935, 13, 491, 'Don Perignon Luminous', 'd', '', 1500000.00, NULL, 0, 1, '2026-04-25 12:43:22', '2026-04-25 12:43:22'),
(3936, 13, 491, 'Louis  Roederer Cristal', 'l', '', 1500000.00, NULL, 0, 1, '2026-04-25 12:45:58', '2026-04-25 12:45:58'),
(3937, 13, 498, 'Martinellis', 'm', '', 50000.00, NULL, 0, 1, '2026-04-25 13:05:23', '2026-04-25 13:05:23'),
(3938, 13, 479, 'Glenfiddich 16 Years', 'glenfiddich-grand-cortes-xxii-2', '', 320000.00, NULL, 0, 1, '2026-04-25 13:08:27', '2026-04-25 13:08:27'),
(3939, 13, 461, 'Ribeye Steak', 'r', '', 62000.00, NULL, 0, 1, '2026-04-25 13:15:18', '2026-04-25 13:15:18'),
(3940, 13, 461, 'T bone Steak', 't', '', 65000.00, NULL, 0, 1, '2026-04-25 13:16:23', '2026-04-25 13:16:23'),
(3941, 13, 491, 'Veuve Clicquot Rich Rose', 'v', '', 450000.00, NULL, 8, 1, '2026-04-28 10:45:52', '2026-04-28 10:46:39'),
(3942, 13, 484, 'Remy Martin VS', 'r', 'Tot  10,000', 160000.00, NULL, 0, 1, '2026-04-28 11:34:16', '2026-04-28 11:34:16'),
(3943, 13, 479, 'Glenfiddich 18 year Limited Edition', 'glenfiddich-21-year', 'Tot 31,000', 390000.00, NULL, 0, 1, '2026-04-28 11:56:53', '2026-04-28 11:56:53'),
(3944, 13, 481, 'Wild Turkey 101', 'w', 'Tot 9,000', 90000.00, NULL, 0, 1, '2026-04-28 12:17:38', '2026-04-28 12:17:38'),
(3945, 13, 496, 'Margarita', 'm-2', '', 18500.00, NULL, 0, 1, '2026-05-02 20:14:11', '2026-05-02 20:14:11'),
(3946, 13, 488, 'Friends and Family white wine', 'f', '', 35000.00, NULL, 0, 1, '2026-05-02 20:20:05', '2026-05-02 20:20:05'),
(3947, 13, 488, 'Chateau Vartely muscat', 'c', '', 70000.00, NULL, 0, 1, '2026-05-02 20:22:59', '2026-05-02 20:22:59'),
(3948, 13, 496, 'Pornstar Martini', 'o', '', 18500.00, NULL, 0, 1, '2026-05-03 17:58:31', '2026-05-03 17:58:31'),
(3949, 13, 490, 'Friends and Family Rose wine', 'f', 'Glass = 10,000', 35000.00, NULL, 0, 1, '2026-05-04 11:39:46', '2026-05-04 11:39:46'),
(3950, 13, 490, 'Vartely Chateau Rose', 'v', 'Glass = 13,000', 55000.00, NULL, 0, 1, '2026-05-04 11:42:14', '2026-05-04 11:42:14'),
(3951, 25, 499, '6pcs wings', 'wm-6pcs', 'LORD OF THE WINGS! Choose your flavor & dip.', 12000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3952, 25, 499, '8pcs wings', 'wm-8pcs', 'LORD OF THE WINGS! Choose your flavor & dip.', 13500.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3953, 25, 499, '10pcs wings', 'wm-10pcs', 'LORD OF THE WINGS! Choose your 1 flavor & 1 dip.', 15000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3954, 25, 499, '15pcs wings', 'wm-15pcs', 'LORD OF THE WINGS! Choose up to 2 flavors & 1 dip.', 20500.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3955, 25, 499, '20pcs wings', 'wm-20pcs', 'LORD OF THE WINGS! Choose up to 2 flavors & 1 dip.', 22000.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3956, 25, 499, '30pcs wings', 'wm-30pcs', 'LORD OF THE WINGS! Choose up to 3 flavors & 1 dip.', 30000.00, NULL, 6, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3957, 25, 500, 'WAFFLE UP POWER UP!', 'wm-waffle-power', 'Waffles, chicken tenders in flavor of choice & cheesy Mac.', 12000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3958, 25, 500, 'DUNKED', 'wm-dunked', 'Waffles, chicken tenders in flavor of choice.', 10000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3959, 25, 500, 'HULK', 'wm-hulk', 'Waffles, chicken tenders in flavor of choice, classic French fries & ketchup.', 12000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3960, 25, 500, 'BIGGIE', 'wm-biggie', 'Waffles, chicken tenders in flavor of choice, classic French fries, chicken poppers, 6pcs wings in flavor of choice & ketchup.', 20000.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3961, 25, 500, 'CHAIRMAN', 'wm-chairman', 'Waffles, chicken tenders in flavor of choice, seasoned wedges, chicken poppers & ketchup.', 30000.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3962, 25, 500, 'CHICKUTERIE', 'wm-chickuterie', 'Waffles, chicken tenders in flavor of choice, seasoned wedges, chicken poppers, 8 wings in flavor of choice, coleslaw & ketchup.', 30000.00, NULL, 6, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3963, 25, 501, 'POP IT LIKE ITS HOT OG', 'wm-pop-og', '10pcs chicken poppers (choose your flavor & dip).', 8000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3964, 25, 501, 'RANGER', 'wm-ranger', 'Chicken poppers loaded fries with peri peri spice mix, cheese & ranch sauce.', 15000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3965, 25, 501, 'BOSSMAN', 'wm-bossman', '20pcs chicken poppers (choose your flavor & dip).', 12000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3966, 25, 501, 'THE SHAKER', 'wm-shaker', '25pcs chicken poppers with suya spice.', 9000.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3967, 25, 501, 'POP STARS', 'wm-pop-stars', '30pcs smothered hot chicken poppers, seasoned wedges & ketchup.', 13000.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3968, 25, 504, 'Coleslaw', 'wm-side-coleslaw', NULL, 5000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3969, 25, 504, 'Classic French fries', 'wm-side-fries', NULL, 6500.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3970, 25, 504, 'Seasoned Potato wedges', 'wm-side-wedges', NULL, 7500.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10');
INSERT INTO `menu_items` (`id`, `restaurant_id`, `category_id`, `name`, `slug`, `description`, `price`, `image`, `display_order`, `is_available`, `created_at`, `updated_at`) VALUES
(3971, 25, 504, 'Spicy suya fries', 'wm-side-suya', NULL, 8000.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3972, 25, 504, 'Cheesy mac', 'wm-side-mac', NULL, 7000.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3973, 25, 504, 'Cajun fried corn', 'wm-side-corn', NULL, 6000.00, NULL, 6, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3974, 25, 505, 'TORNADO', 'wm-combo-tornado', 'Mango habanero chicken tender sandwich, classic French fries & ketchup.', 12500.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3975, 25, 505, 'THE BIG BANG', 'wm-combo-big-bang', 'Cajun chicken tender double cheese burger, coleslaw, seasoned wedges & ketchup.', 16000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3976, 25, 505, 'TRAFFIC', 'wm-combo-traffic', '8pcs wings in flavor of choice, Cajun fried corn, French fries & ketchup.', 15000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3977, 25, 505, 'CITIZEN', 'wm-combo-citizen', 'Smothered hot chicken poppers, 8pcs wings in flavor of choice, spicy suya fries & ketchup.', 15000.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3978, 25, 505, 'SUPERBOWL', 'wm-combo-superbowl', 'Superbowl salad: lettuce, sweet corn, purple cabbage, tomatoes, cheese shavings, croutons, chopped sweet chili tenders & lemon honey vinaigrette.', 12500.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3979, 25, 505, 'EMPIRE', 'wm-combo-empire', 'Boneless jerk wing cheese burger, French fries & ketchup.', 15000.00, NULL, 6, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3980, 25, 505, 'SUB', 'wm-combo-sub', 'Boneless teriyaki wings wrap: tortilla, lettuce, cheese shavings, avocado lime sauce, boneless teriyaki wings, classic French fries & ketchup.', 12500.00, NULL, 7, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3981, 25, 506, 'SONIC', 'wm-kids-sonic', '4pcs hickory BBQ wings, kid fries, ketchup & chi smart malt drink.', 7000.00, '6a09dc98e4391.webp', 1, 1, '2026-05-14 22:48:10', '2026-05-17 15:19:52'),
(3982, 25, 506, 'PANDA', 'wm-kids-panda', 'Kid fries, 2pcs sweet chili tenders, ketchup, a pack of reel fruits & chi smart malt drink.', 8500.00, '6a09e173df801.webp', 2, 1, '2026-05-14 22:48:10', '2026-05-17 15:40:35'),
(3983, 25, 506, 'RUGRATS', 'wm-kids-rugrats', '8pcs sweet chili chicken poppers, cheesy mac & chi smart malt drink.', 8000.00, '6a09e195ddca0.webp', 3, 1, '2026-05-14 22:48:10', '2026-05-17 15:41:09'),
(3984, 25, 506, 'BUZZ', 'wm-kids-buzz', 'Waffles, 2pcs BBQ tenders, kid fries, ketchup, a pack of reel fruits & chi smart malt drink.', 12000.00, '6a09e1e402dd9.webp', 4, 1, '2026-05-14 22:48:10', '2026-05-17 15:42:28'),
(3985, 25, 507, 'Chocolate sundae', 'wm-sweet-sundae', NULL, 7500.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3986, 25, 507, 'Mini churros & chocolate dip', 'wm-sweet-churros', NULL, 5500.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3987, 25, 507, 'Apple pies', 'wm-sweet-apple-pies', NULL, 6000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3988, 25, 507, 'BLIZZARD', 'wm-sweet-blizzard', NULL, 8500.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3989, 25, 507, 'Soft ice-cream (₦1,500)', 'wm-sweet-ice-single', NULL, 1500.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3990, 25, 507, 'Soft ice-cream (₦5,000)', 'wm-sweet-ice-regular', NULL, 5000.00, NULL, 6, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3991, 25, 508, 'Wings on Fire challenge (rules)', 'wm-wof-concept', 'Customers order 20pcs of incredibly hot wings, to be consumed in 60 seconds without any liquid (monitored). If they finish in time: a special meal for free and signature on the illustrious winner wall.', 0.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3992, 25, 508, 'Wings on Fire challenge (order)', 'wm-wof-order', 'Challenge entry / pricing at venue.', 0.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3993, 25, 502, 'Mango habanero', 'wm-fl-1', 'Flavor option (no separate charge).', 0.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3994, 25, 502, 'Classic mild buffalo', 'wm-fl-2', 'Flavor option (no separate charge).', 0.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3995, 25, 502, 'Sweet chili', 'wm-fl-3', 'Flavor option (no separate charge).', 0.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3996, 25, 502, 'Cajun', 'wm-fl-4', 'Flavor option (no separate charge).', 0.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3997, 25, 502, 'Lemon pepper', 'wm-fl-5', 'Flavor option (no separate charge).', 0.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3998, 25, 502, 'Fire power', 'wm-fl-6', 'Flavor option (no separate charge).', 0.00, NULL, 6, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(3999, 25, 502, 'Hickory BBQ', 'wm-fl-7', 'Flavor option (no separate charge).', 0.00, NULL, 7, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4000, 25, 502, 'Teriyaki', 'wm-fl-8', 'Flavor option (no separate charge).', 0.00, NULL, 8, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4001, 25, 502, 'Jerk', 'wm-fl-9', 'Flavor option (no separate charge).', 0.00, NULL, 9, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4002, 25, 502, 'Lemon garlic', 'wm-fl-10', 'Flavor option (no separate charge).', 0.00, NULL, 10, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4003, 25, 503, 'Spicy honey mustard', 'wm-dip-1', 'Dip option.', 0.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4004, 25, 503, 'Bangbang', 'wm-dip-2', 'Dip option.', 0.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4005, 25, 503, 'Randy\'s ranch', 'wm-dip-3', 'Dip option.', 0.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4006, 25, 509, 'English Breakfast', 'mb-eng', 'A classic full English plate featuring: golden toast with fluffy scrambled eggs, grilled cherry tomatoes & sautéed mushrooms, juicy sausages & warm baked beans. A hearty, traditional breakfast to start your day right.', 25000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4007, 25, 509, 'American Breakfast', 'mb-usa', 'A rich, indulgent spread of: fluffy pancakes drizzled with maple syrup, tender beef steak with broccoli & potato sides, fresh farm eggs cooked to your style. A bold and satisfying all-American morning treat.', 30000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4008, 25, 510, 'Chicken Caesar Salad', 'mb-caesar', 'Classic Caesar with grilled chicken, parmesan, crunchy croutons, and creamy Greek yogurt dressing.', 20000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4009, 25, 510, 'Conch Salad', 'mb-conch', 'A refreshing mix of calamari, shrimps, bell peppers, pineapple, and Dijon mustard with a spicy habanero kick.', 25000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4010, 25, 510, 'Caprese Salad with flank steak', 'mb-caprese', 'Mozzarella, avocado, sweet basil and pickles, drizzled with olive oil and Dijon mustard, topped with juicy flank steak.', 25000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4011, 25, 511, 'Assorted Pepper Soup', 'mb-pepper', 'Traditional spiced broth with assorted meats, scent leaves, and peppers.', 17000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4012, 25, 511, 'Ginger & Carrot Soup', 'mb-ginger', 'A velvety blend of carrots, ginger, Irish potatoes, and cream.', 12000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4013, 25, 512, 'Creamy Jackpasta with Stuffed Chicken Breast', 'mb-jackpasta', 'Velvety jack-cheese pasta served with tender, herb-stuffed chicken breast.', 30000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4014, 25, 512, 'Butter Saffron Rice with Seafood Sauce & Asparagus', 'mb-saffron', 'Fragrant saffron basmati rice topped with juicy prawns and buttery seafood sauce, finished with crisp asparagus.', 40000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4015, 25, 512, 'Jamaican Oxtail Stew with Rice & Peas', 'mb-oxtail', 'Slow-braised oxtail in rich Caribbean spices, served with coconut rice and kidney beans.', 30000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4016, 25, 513, 'Veuve Clicquot Brut', 'mb-vcb', 'Bold and crisp, with notes of apple and brioche.', 350000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4017, 25, 513, 'Veuve Clicquot Rosé', 'mb-vcr', 'Vibrant and fruity, with red berry aromas.', 410000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4018, 25, 513, 'Moët & Chandon Brut', 'mb-mb', 'Classic champagne, fresh citrus and floral hints.', 386000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4019, 25, 513, 'Moët & Chandon Rosé', 'mb-mr', 'Elegant rosé with wild strawberry and raspberry notes.', 446000.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4020, 25, 513, 'Moët & Chandon Imperial Brut', 'mb-mi', 'Signature style, balanced with apple and citrus zest.', 398000.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4021, 25, 513, 'Amabile Red', 'mb-ar', 'Medium-bodied, soft berry flavour.', 45000.00, NULL, 6, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4022, 25, 513, 'Amabile Rosé', 'mb-aro', 'Semi-sweet, smooth, fruity finish.', 45000.00, NULL, 7, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4023, 25, 513, 'Carlo Rossi Red', 'mb-crr', 'Smooth, medium-bodied with ripe berry flavours and a soft finish.', 45000.00, NULL, 8, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4024, 25, 513, 'Carlo Rossi White', 'mb-crw', 'Light, crisp, and refreshing with fruity notes.', 45000.00, NULL, 9, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4025, 25, 514, 'MUNCHIEZ DIPERZ', 'hm-m1', 'Tortilla nachos & bang bang dip.', 10000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4026, 25, 514, 'AFRICAN GIANT PLATTER', 'hm-m2', 'Consisting of wings, chili beef chunks, peppered snails, puff puff, kelewele, prawn skewers, mosa, fried yam, goat chunks and pepper sauce.', 70000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4027, 25, 514, 'YING YANG', 'hm-m3', '2 flavor calamari. Pan chili calamari & deep fried calamari with garlic mayo.', 15000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4028, 25, 514, 'CAPRI', 'hm-m4', 'Deep fried goat chunks tossed in green chili with fried yam.', 17000.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4029, 25, 514, 'MEX', 'hm-m5', 'Mince and cheese taquitos with simple salsa.', 10500.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4030, 25, 514, 'DYNAMITEZ', 'hm-m6', 'Prawn dynamites.', 25000.00, NULL, 6, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4031, 25, 514, 'RELOAD ALOHA', 'hm-m7', 'Chicken Caesar salad and dressing.', 15000.00, NULL, 7, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4032, 25, 514, 'THAI TANIC', 'hm-m8', 'Thai fisherman soup with garlic bread.', 15000.00, NULL, 8, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4033, 25, 514, 'DUTCH', 'hm-m9', 'One skillet beef and broccoli with steamed rice.', 12500.00, NULL, 9, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4034, 25, 514, 'TUSCANY (chicken/beef)', 'hm-m10', 'An option of chicken or beef pasta.', 20500.00, NULL, 10, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4035, 25, 514, 'STIR IT UP', 'hm-m11', 'One spicy stir fried rice consisting of shredded beef, shredded chicken, broccoli, assorted bell peppers, spring onion, chili flakes and spices.', 20500.00, NULL, 11, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4036, 25, 514, 'WAIKIKI', 'hm-m12', 'Surf n turf steak, crushed herbed sweet potatoes, glazed marrow & creamy mushroom sauce.', 60000.00, NULL, 12, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4037, 25, 514, 'THE G.O.A.T', 'hm-m13', 'Spicy goat rice mix.', 25000.00, NULL, 13, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4038, 25, 514, 'CHOPPED', 'hm-m14', 'Succulent lamb chops with creamy mushroom sauce, seasoned wedges or Smokey Jollof and coleslaw.', 70000.00, NULL, 14, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4039, 25, 514, 'TUSCANY (Seafood)', 'hm-m15', 'Seafood pasta.', 22500.00, NULL, 15, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4040, 25, 515, 'FISH MONAY (Standard)', 'hm-f1', 'Grilled medium tilapia, expertly seasoned and served with rich, spicy pepper sauce.', 40000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4041, 25, 515, 'CATFISH SUPREME', 'hm-f2', 'Tender catfish in a flavorful pepper soup broth, served with white rice or grilled garlic bread. A perfect blend of spices and fresh herbs.', 25000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4042, 25, 515, 'THE POT', 'hm-f3', 'A hearty blend of sweet potato or yam, catfish, smoked fish and aromatic herbs, cooked in rich red oil for a satisfying flavorful meal. Pure comfort in every bite.', 25000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4043, 25, 515, 'Shepherd\'s Pie', 'hm-f4', 'A comforting dish made with creamy mashed Irish potatoes, savoury minced meat, and a blend of spices, topped with melted parmesan cheese for a perfect finish. A delicious, hearty meal.', 20000.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4044, 25, 515, 'FISH MONAY (Deluxe)', 'hm-f5', 'Full size tilapia, flamed grilled to perfection with bold spice and signature sauce.', 50000.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4045, 25, 515, 'THE KINGS CATCH', 'hm-f6', 'Whole grilled catfish: a majestic serving of whole catfish, marinated in bold spices and grilled to tender, smokey perfection. Packed with flavor and served with your preferred side. A true showstopper.', 60000.00, NULL, 6, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4046, 25, 515, 'Coconut Rice', 'hm-f7', 'Flavourful coconut-infused rice, served with well-seasoned turkey.', 35000.00, NULL, 7, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4047, 25, 515, 'Special Jollof Rice', 'hm-f8', 'Rich smoky jollof rice served with any protein of your choice (Chicken/Turkey/Fish). Additional charges may apply for premium proteins.', 35000.00, NULL, 8, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4048, 25, 515, 'Special Fried Rice', 'hm-f9', 'Savory fried rice with mixed veggies and spices, served with turkey.', 35000.00, NULL, 9, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4049, 25, 516, 'Smokey Jollof rice', 'hm-s1', NULL, 8000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4050, 25, 516, 'Fried yam', 'hm-s2', NULL, 7500.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4051, 25, 516, 'Steamed rice', 'hm-s3', NULL, 7500.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4052, 25, 516, 'Crushed sweet potatoes', 'hm-s4', NULL, 7500.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4053, 25, 517, 'PICCASSO', 'hm-sw1', 'French toast pudding, ice cream, syrup & berries.', 10000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4054, 25, 517, 'PIE-RATES', 'hm-sw2', 'Apple crumble & vanilla ice cream.', 12000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4055, 25, 517, 'SUNDAE', 'hm-sw3', 'Ice cream sundae (as listed on drink menu).', 10000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4056, 25, 518, 'Red Bull', 'shared-redbull', NULL, 5000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4057, 25, 518, 'Coke', 'shared-coke', NULL, 2000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4058, 25, 518, 'Pepsi', 'shared-pepsi', NULL, 2000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4059, 25, 518, 'Sprite', 'shared-sprite', NULL, 2000.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4060, 25, 518, 'Fanta', 'shared-fanta', NULL, 2000.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4061, 25, 518, '7up', 'shared-7up', NULL, 2000.00, NULL, 6, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4062, 25, 518, 'Pepsi Diet', 'shared-pepsi-diet', NULL, 2000.00, NULL, 7, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4063, 25, 518, 'Pepsi wingman', 'shared-pepsi-wingman', NULL, 2000.00, NULL, 8, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4064, 25, 518, 'Pepsi diet wingman', 'shared-pepsi-diet-wingman', NULL, 2000.00, NULL, 9, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4065, 25, 518, '7up Diet', 'shared-7up-diet', NULL, 2000.00, NULL, 10, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4066, 25, 518, 'Miranda', 'shared-miranda', NULL, 2000.00, NULL, 11, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4067, 25, 518, 'Soda water', 'shared-soda-water', NULL, 2000.00, NULL, 12, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4068, 25, 518, 'Tonic', 'shared-tonic', NULL, 2000.00, NULL, 13, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4069, 25, 518, 'Bitter lemon', 'shared-bitter-lemon', NULL, 2000.00, NULL, 14, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4070, 25, 519, 'Cranberry Juice (glass)', 'shared-cranberry-glass', NULL, 6000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4071, 25, 519, 'Cranberry Juice (pitcher)', 'shared-cranberry-pitcher', NULL, 15000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4072, 25, 519, 'Orange Juice (glass)', 'shared-orange-glass', NULL, 5000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4073, 25, 519, 'Orange Juice (pitcher)', 'shared-orange-pitcher', NULL, 12000.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4074, 25, 519, 'Pineapple Juice (glass)', 'shared-pineapple-glass', NULL, 5000.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4075, 25, 519, 'Pineapple Juice (pitcher)', 'shared-pineapple-pitcher', NULL, 12000.00, NULL, 6, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4076, 25, 519, 'Apple Juice (glass)', 'shared-apple-glass', NULL, 5000.00, NULL, 7, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4077, 25, 519, 'Apple Juice (pitcher)', 'shared-apple-pitcher', NULL, 12000.00, NULL, 8, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4078, 25, 519, 'Chivita Orange Juice', 'shared-chivita-orange', NULL, 12000.00, NULL, 9, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4079, 25, 519, 'Chivita Pineapple Juice', 'shared-chivita-pineapple', NULL, 12000.00, NULL, 10, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4080, 25, 519, 'Chivita Apple Juice', 'shared-chivita-apple', 'As listed on menu (Chivita Apple Juice…).', 12000.00, NULL, 11, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4081, 25, 519, 'Perrier', 'shared-perrier', NULL, 5000.00, NULL, 12, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4082, 25, 520, 'Strawberry milkshake', 'shared-milkshake-strawberry', NULL, 12500.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4083, 25, 520, 'Salted caramel milk shake', 'shared-milkshake-salted-caramel', NULL, 12500.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4084, 25, 520, 'S’mores chocolate milkshake', 'shared-milkshake-smores', NULL, 12500.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4085, 25, 520, 'Oreo cheesecake milkshake', 'shared-milkshake-oreo-cheesecake', NULL, 12500.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4086, 25, 521, 'Banana & Mango', 'shared-smoothie-banana-mango', NULL, 12500.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4087, 25, 521, 'Watermelon & Strawberry', 'shared-smoothie-watermelon-strawberry', NULL, 12500.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4088, 25, 521, 'BANANA&STRAWBERRY', 'shared-smoothie-banana-strawberry', NULL, 12500.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4089, 25, 521, 'KALE Green', 'shared-smoothie-kale-green', NULL, 12500.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4090, 25, 522, 'Veuve Clicquot Brut', 'hm-ch1', NULL, 350000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4091, 25, 522, 'Veuve Clicquot Rosé', 'hm-ch2', NULL, 410000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4092, 25, 522, 'Moët et Chandon Brut', 'hm-ch3', NULL, 386000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4093, 25, 522, 'Moët et Chandon Rosé', 'hm-ch4', NULL, 446000.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4094, 25, 522, 'Moët et Chandon Imperial Brut', 'hm-ch5', NULL, 398000.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4095, 25, 522, 'Dom Pérignon Brut', 'hm-ch6', NULL, 1250000.00, NULL, 6, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4096, 25, 522, 'Ace Of Spades', 'hm-ch7', NULL, 1250000.00, NULL, 7, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4097, 25, 522, 'Ace Of Spades Rosé', 'hm-ch8', NULL, 1850000.00, NULL, 8, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4098, 25, 522, 'LP Rosé', 'hm-ch9', NULL, 380000.00, NULL, 9, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4099, 25, 522, 'LP Brut', 'hm-ch10', NULL, 290000.00, NULL, 10, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4100, 25, 523, 'Johnnie Walker Black Label', 'hm-w1', 'Bottle ₦150,000. Glass/5cl ₦15,000.', 150000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4101, 25, 523, 'Johnnie Walker Blue Label', 'hm-w2', NULL, 600000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4102, 25, 523, 'Johnnie Walker Green Label', 'hm-w3', NULL, 300000.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4103, 25, 523, 'Johnnie Walker Gold Label', 'hm-w4', NULL, 250000.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4104, 25, 523, 'Jameson Irish Original', 'hm-w5', NULL, 195000.00, NULL, 6, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4105, 25, 523, 'Glenfiddich 12', 'hm-w6', 'Bottle ₦200,000. Glass/5cl ₦20,000.', 200000.00, NULL, 7, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4106, 25, 523, 'Glenfiddich 15', 'hm-w7', NULL, 370000.00, NULL, 8, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4107, 25, 523, 'Glenfiddich 18', 'hm-w8', NULL, 450000.00, NULL, 9, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4108, 25, 523, 'Glenfiddich 21', 'hm-w9', NULL, 750000.00, NULL, 10, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4109, 25, 523, 'Monkey Shoulder', 'hm-w10', 'Bottle ₦130,000. Glass/5cl ₦12,000.', 130000.00, NULL, 11, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4110, 25, 523, 'Macallan 12', 'hm-w11', 'Bottle ₦150,000. Glass/5cl ₦15,000.', 150000.00, NULL, 12, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4111, 25, 523, 'Macallan 15', 'hm-w12', NULL, 280000.00, NULL, 13, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4112, 25, 523, 'Macallan 18', 'hm-w13', NULL, 550000.00, NULL, 14, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4113, 25, 523, 'Jameson Black Barrel', 'hm-w14', NULL, 250000.00, NULL, 15, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4114, 25, 523, 'Balvenie 12', 'hm-w15', NULL, 221000.00, NULL, 16, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4115, 25, 523, 'Balvenie 14', 'hm-w16', NULL, 300000.00, NULL, 17, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4116, 25, 523, 'Jack Daniel\'s', 'hm-w17', NULL, 220000.00, NULL, 18, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4117, 25, 523, 'The Singleton', 'hm-w18', NULL, 225000.00, NULL, 19, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4118, 25, 523, 'The Pogues', 'hm-w19', NULL, 100000.00, NULL, 20, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4119, 25, 524, 'Hennessy VSOP', 'hm-cg1', NULL, 400000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4120, 25, 524, 'Hennessy VS', 'hm-cg2', 'Glass 5cl ₦20,000', 300000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4121, 25, 524, 'Martell Blue Swift', 'hm-cg3', NULL, 300000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4122, 25, 524, 'Martell XO', 'hm-cg4', NULL, 780000.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4123, 25, 524, 'Hennessy XO', 'hm-cg5', NULL, 800000.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4124, 25, 525, 'Hendrick\'s', 'hm-g1', 'Glass 5cl ₦18,000', 235000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4125, 25, 525, 'Gin Mare', 'hm-g2', 'Glass 5cl ₦9,000', 160000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4126, 25, 525, 'Tanqueray No. Ten', 'hm-g3', NULL, 195000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4127, 25, 525, 'Bombay Sapphire', 'hm-g4', 'Glass 5cl ₦13,500', 167000.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4128, 25, 525, 'Monkey 47', 'hm-g5', NULL, 150000.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4129, 25, 525, 'Cape Town', 'hm-g6', 'Glass 5cl ₦11,500', 180000.00, NULL, 6, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4130, 25, 526, 'Belvedere', 'hm-v1', NULL, 200000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4131, 25, 526, 'Grey Goose', 'hm-v2', NULL, 150000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4132, 25, 526, 'Absolut', 'hm-v3', 'Glass 5cl ₦10,000', 155000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4133, 25, 526, 'Cîroc', 'hm-v4', NULL, 150000.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4134, 25, 527, 'Jose Cuervo (premium)', 'hm-t1', 'Shot ₦12,000', 160000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4135, 25, 527, 'Jose Cuervo (standard)', 'hm-t2', 'Shot ₦7,000', 110000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4136, 25, 527, 'Casamigos Añejo', 'hm-t3', NULL, 550000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4137, 25, 527, 'Casamigos Reposado', 'hm-t4', NULL, 520000.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4138, 25, 527, 'Don Julio 1942', 'hm-t5', NULL, 900000.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4139, 25, 527, 'Don Julio Reposado', 'hm-t6', NULL, 550000.00, NULL, 6, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4140, 25, 527, 'Patrón Blanco', 'hm-t7', 'Glass 5cl ₦15,000', 200000.00, NULL, 7, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4141, 25, 527, 'Patrón Reposado', 'hm-t8', NULL, 200000.00, NULL, 8, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4142, 25, 527, 'Patrón Añejo', 'hm-t9', NULL, 350000.00, NULL, 9, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4143, 25, 527, 'Clase Azul Reposado', 'hm-t10', NULL, 950000.00, NULL, 10, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4144, 25, 527, 'Clase Azul Añejo', 'hm-t11', NULL, 2500000.00, NULL, 11, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4145, 25, 527, 'Vivir Blanco', 'hm-t12', NULL, 270000.00, NULL, 12, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4146, 25, 527, 'Vivir Reposado', 'hm-t13', NULL, 350000.00, NULL, 13, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4147, 25, 527, 'Teremana Reposado', 'hm-t14', NULL, 500000.00, NULL, 14, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4148, 25, 528, 'Heineken Draft', 'hm-b1', NULL, 5000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4149, 25, 528, 'Guinness / Legend', 'hm-b2', NULL, 5000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4150, 25, 528, 'Tiger', 'hm-b3', NULL, 5000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4151, 25, 528, 'Star Radler Can', 'hm-b4', NULL, 4000.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4152, 25, 529, 'DC Sweet (white, bottle)', 'hm-ww1', 'Bottle ₦45,000; glass ₦20,000 (per menu).', 45000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4153, 25, 529, 'DC Dry Chenin (white, bottle)', 'hm-ww2', 'Bottle ₦45,000; glass ₦20,000.', 45000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4154, 25, 529, 'DC Dry Sweet (white, bottle)', 'hm-ww-drysweet', 'Menu listing (Dc SDc Dryweet). Bottle ₦45,000; glass ₦20,000.', 45000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4155, 25, 529, 'Sungoddess (white)', 'hm-ww3', NULL, 100000.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4156, 25, 529, 'Santa Rita 120 Chardonnay', 'hm-ww4', NULL, 100000.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4157, 25, 529, 'Amabile Sweet (white)', 'hm-ww5', 'Juicy Italian white wine.', 45000.00, NULL, 6, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4158, 25, 530, 'Sungoddess Pinot Grigio', 'hm-rw1', NULL, 100000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4159, 25, 530, 'Ermelinda Tulipa Rosé', 'hm-rw2', NULL, 50000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4160, 25, 530, 'Amabili Di Rosa', 'hm-rw3', 'Juicy Italian wine.', 40000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4161, 25, 531, 'DC Sweet Red (bottle)', 'hm-rd1', 'Glass ₦16,000.', 40000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4162, 25, 531, 'Amabile Di Rosa (red)', 'hm-rd2', 'Glass ₦16,000.', 40000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4163, 25, 531, 'DC Sweet Red (sweet & smooth)', 'hm-rd-sweet', 'Sweet & smooth with fruits & flowers. Glass ₦16,000.', 40000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4164, 25, 531, 'DC Dry Red', 'hm-rd3', 'Glass ₦16,000.', 40000.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4165, 25, 531, 'Bla Bla', 'hm-rd4', NULL, 60000.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4166, 25, 531, 'Escudo Rojo', 'hm-rd5', 'Glass ₦18,000.', 60000.00, NULL, 6, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4167, 25, 531, 'Carlo Rossi (red)', 'hm-rd6', NULL, 40000.00, NULL, 7, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4168, 25, 531, '4 Cousins', 'hm-rd7', NULL, 40000.00, NULL, 8, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4169, 25, 531, 'Prosecco Rosario', 'hm-rd8', 'Glass 5cl ₦9,000.', 42000.00, NULL, 9, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4170, 25, 531, 'Sungoddess (red)', 'hm-rd9', NULL, 65000.00, NULL, 10, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4171, 25, 532, 'Mr Flinstone', 'hm-ck1', 'Gin, pineapple, amaro, yellow chartreuse, honey/ginger syrup, lemon juice & aromatic bitters.', 22500.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4172, 25, 532, 'Roller coaster', 'hm-ck2', 'Vodka, Midori, Cointreau, lemon juice, egg white & aquafaba.', 22500.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4173, 25, 532, 'Orange & Basil', 'hm-ck3', 'Gin, orange & basil cordial, lime cordial.', 22500.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4174, 25, 532, 'Peer pressure', 'hm-ck4', 'Apple cider vinegar, celery, honey syrup, bitters, lemon juice, pear juice, soda, aged rum & Grand Marnier.', 22500.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4175, 25, 532, 'Colder club', 'hm-ck5', 'Fresh fig, fresh raspberries, almond syrup, gin, lemon juice (optional), egg white, aquafaba.', 22500.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4176, 25, 532, 'The bullshort', 'hm-ck6', 'Vodka, grapefruit juice, red vermouth, elderflower liqueur.', 22500.00, NULL, 6, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4177, 25, 532, 'Borrowed Time', 'hm-ck7', 'Whiskey, triple sec, grapefruit juice, thyme syrup.', 22500.00, NULL, 7, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4178, 25, 532, 'Sunny Spritzer', 'hm-ck8', 'Limoncello, lemon soda, Prosecco.', 22500.00, NULL, 8, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4179, 25, 532, 'Berry Whipped', 'hm-ck9', 'Lemon juice, cranberry juice, raspberry liqueur, white rum, aquafaba.', 22500.00, NULL, 9, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4180, 25, 532, 'Frame Up', 'hm-ck10', 'Pineapple juice, Midori, coconut rum, vodka.', 22500.00, NULL, 10, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4181, 25, 532, 'Bloody Mary', 'hm-ck11', 'Classic cocktail.', 22500.00, NULL, 11, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4182, 25, 532, 'Long Island Iced Tea', 'hm-ck12', 'Vodka, tequila, gin, triple sec, lemon juice, cola & rum.', 22500.00, NULL, 12, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4183, 25, 532, 'Wild Sex', 'hm-ck13', 'Rum, juice, triple sec, vodka, coconut & rum.', 20000.00, NULL, 13, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4184, 25, 532, 'Cosmopolitan', 'hm-ck14', 'Vodka, triple sec, juice & lime juice.', 20000.00, NULL, 14, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4185, 25, 532, 'Chapman', 'hm-ck15', NULL, 20000.00, NULL, 15, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4186, 25, 532, 'Mai Tai', 'hm-ck16', 'Rum, triple sec, gold rum, lime juice & almond syrup.', 20000.00, NULL, 16, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4187, 25, 532, 'Sex On The Beach', 'hm-ck17', 'Vodka, peach liqueur, juice & grenadine.', 20000.00, NULL, 17, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4188, 25, 532, 'Gin Basil', 'hm-ck18', 'Gin, simple syrup, basil leaf & lemon juice.', 20000.00, NULL, 18, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4189, 25, 532, 'Porn Star Martini', 'hm-ck19', 'Vanilla syrup, vodka, passion fruit liqueur, lime juice & sparkling wine.', 20000.00, NULL, 19, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4190, 25, 532, 'Strawberry Daiquiri', 'hm-ck20', 'Rum, lime juice & syrup.', 20000.00, NULL, 20, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4191, 25, 532, 'Whiskey Sour', 'hm-ck21', 'Lemon juice, simple syrup, bitters & egg.', 20000.00, NULL, 21, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4192, 25, 532, 'Margarita', 'hm-ck22', 'Classic.', 22500.00, NULL, 22, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4193, 25, 533, 'Mango Favez', 'hm-mk1', 'Mango puree, fresh mint leaf, lime juice, mango soda.', 18000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4194, 25, 533, 'Sunny Breeze', 'hm-mk2', 'Coconut cordial, grapefruit juice, 7up.', 18000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4195, 25, 533, 'Passion Rise', 'hm-mk3', 'Fresh passion fruit, grenadine syrup, orange juice, lime juice.', 18000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4196, 25, 533, 'Goodluck Charm', 'hm-mk4', 'Grapefruit juice, guava juice, strawberry puree, cranberry juice.', 18000.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4197, 25, 533, 'Peach & Thyme Fizz', 'hm-mk5', 'Peach syrup, lemon juice, soda water.', 18000.00, NULL, 5, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4198, 25, 533, 'Strawberry margarita', 'hm-mk6', NULL, 22500.00, NULL, 6, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4199, 25, 534, 'Banana & Mango smoothie', 'hm-sm1', NULL, 18000.00, NULL, 1, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4200, 25, 534, 'Watermelon & Strawberry smoothie', 'hm-sm2', NULL, 18000.00, NULL, 2, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4201, 25, 534, 'Banana & Strawberry smoothie', 'hm-sm-bs', NULL, 18000.00, NULL, 3, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4202, 25, 534, 'KALE Green smoothie', 'hm-sm3', NULL, 18000.00, NULL, 4, 1, '2026-05-14 22:48:10', '2026-05-14 22:48:10'),
(4203, 3, 535, 'Cranberry Juice', 'cranberry-juice', '', 6000.00, NULL, 1, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4204, 3, 535, 'Juice Pack', 'juice-pack', '', 9840.00, NULL, 2, 1, '2026-02-13 09:52:41', '2026-05-07 12:39:33'),
(4205, 3, 535, 'Malt Drink', 'malt-drink', '', 1500.00, NULL, 3, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4206, 3, 535, 'Energy Drink', 'energy-drink', '', 5000.00, NULL, 4, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4207, 3, 535, 'Water (Small)', 'water-small', '', 1000.00, NULL, 5, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4208, 3, 535, 'Soft Drinks (Coke, Fanta, Sprite, etc.)', 'soft-drinks', 'Coke, Fanta, Sprite and more', 1000.00, NULL, 6, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4209, 3, 535, 'Red Bull / Power Horse', 'red-bull-power-horse', '', 5000.00, NULL, 7, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4210, 3, 536, '33 Lager', '33-lager', '', 3820.39, NULL, 1, 1, '2026-02-13 09:52:41', '2026-05-07 11:58:42'),
(4211, 3, 536, 'Smirnoff Ice', 'smirnoff-ice', '', 4305.00, NULL, 2, 1, '2026-02-13 09:52:41', '2026-05-07 12:42:24'),
(4212, 3, 536, 'Star Draft (Big)', 'star-draft-big', '', 2000.00, NULL, 3, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4213, 3, 536, 'Star Draft (Small)', 'star-draft-small', '', 1000.00, NULL, 4, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4214, 3, 536, 'Star Radler', 'star-radler', '', 3000.00, NULL, 5, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4215, 3, 536, 'Budweiser (Big)', 'budweiser-big', '', 3500.00, NULL, 6, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4216, 3, 536, 'Heineken', 'heineken', '', 3500.00, NULL, 7, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4217, 3, 536, 'Heineken Draft (Big)', 'heineken-draft-big', '', 3500.00, NULL, 8, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4218, 3, 536, 'Heineken Draft (Small)', 'heineken-draft-small', '', 1500.00, NULL, 9, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4219, 3, 536, 'Flying Fish', 'flying-fish', '', 3000.00, NULL, 10, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4220, 3, 536, 'Desperados', 'desperados', '', 3000.00, NULL, 11, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4221, 3, 536, 'Guinness Stout (Big)', 'guinness-stout-big', '', 3500.00, NULL, 12, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4222, 3, 536, 'Guinness Stout (Small)', 'guinness-stout-small', '', 3000.00, NULL, 13, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4223, 3, 536, 'Guinness Extra Smooth', 'guinness-extra-smooth', '', 3000.00, NULL, 14, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4224, 3, 536, 'Gulder', 'gulder', '', 3000.00, NULL, 15, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4225, 3, 536, 'Star', 'star', '', 3000.00, NULL, 16, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4226, 3, 536, 'Trophy', 'trophy', '', 3000.00, NULL, 17, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4227, 3, 536, 'Goldberg', 'goldberg', '', 3000.00, NULL, 18, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4228, 3, 536, 'Harp', 'harp', '', 3000.00, NULL, 19, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4229, 3, 537, 'Rémy Martin XO', 'remy-martin-xo', 'Bottle', 230000.00, NULL, 1, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4230, 3, 537, 'Hennessy XO', 'hennessy-xo', 'Bottle', 575000.00, NULL, 2, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4231, 3, 537, 'Hennessy VSOP (Bottle)', 'hennessy-vsop-bottle', 'Bottle', 130000.00, NULL, 3, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4232, 3, 537, 'Hennessy VSOP (Shot)', 'hennessy-vsop-shot', 'Per shot', 9000.00, NULL, 4, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4233, 3, 537, 'Rémy Martin VSOP (Bottle)', 'remy-martin-vsop-bottle', 'Bottle', 90000.00, NULL, 5, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4234, 3, 537, 'Rémy Martin VSOP (Shot)', 'remy-martin-vsop-shot', 'Per shot', 6500.00, NULL, 6, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4235, 3, 537, 'Martell Blue Swift (Bottle)', 'martell-blue-swift-bottle', 'Bottle', 80000.00, NULL, 7, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4236, 3, 537, 'Martell Blue Swift (Shot)', 'martell-blue-swift-shot', 'Per shot', 6000.00, NULL, 8, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4237, 3, 537, 'Hennessy VS (Bottle)', 'hennessy-vs-bottle', 'Bottle', 80000.00, NULL, 9, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4238, 3, 537, 'Hennessy VS (Shot)', 'hennessy-vs-shot', 'Per shot', 7000.00, NULL, 10, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4239, 3, 538, 'Glenfiddich 18 Years', 'glenfiddich-18-years', 'Bottle', 180000.00, NULL, 1, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4240, 3, 538, 'Glenfiddich 15 Years (Bottle)', 'glenfiddich-15-years-bottle', 'Bottle', 120000.00, NULL, 2, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4241, 3, 538, 'Glenfiddich 15 Years (Shot)', 'glenfiddich-15-years-shot', 'Per shot', 8000.00, NULL, 3, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4242, 3, 538, 'Glenfiddich 12 Years (Bottle)', 'glenfiddich-12-years-bottle', 'Bottle', 90000.00, NULL, 4, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4243, 3, 538, 'Glenfiddich 12 Years (Shot)', 'glenfiddich-12-years-shot', 'Per shot', 5000.00, NULL, 5, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4244, 3, 538, 'Jameson Black Barrel (Bottle)', 'jameson-black-barrel-bottle', 'Bottle', 55000.00, NULL, 6, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4245, 3, 538, 'Jameson Black Barrel (Shot)', 'jameson-black-barrel-shot', 'Per shot', 3000.00, NULL, 7, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4246, 3, 538, 'Jameson (Big Bottle)', 'jameson-big-bottle', 'Bottle', 47000.00, NULL, 8, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4247, 3, 538, 'Jameson (Shot)', 'jameson-shot', 'Per shot', 5000.00, NULL, 9, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4248, 3, 538, 'Jameson Miniature', 'jameson-miniature', '', 18500.00, NULL, 10, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4249, 3, 538, 'Johnnie Walker Black Label (Bottle)', 'johnnie-walker-black-label-bottle', 'Bottle', 45000.00, NULL, 11, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4250, 3, 538, 'Johnnie Walker Black Label (Shot)', 'johnnie-walker-black-label-shot', 'Per shot', 5000.00, NULL, 12, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4251, 3, 538, 'Johnnie Walker Red Label (Bottle)', 'johnnie-walker-red-label-bottle', 'Bottle', 27000.00, NULL, 13, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4252, 3, 538, 'Johnnie Walker Red Label (Shot)', 'johnnie-walker-red-label-shot', 'Per shot', 3000.00, NULL, 14, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4253, 3, 538, 'Johnnie Walker Blue Label', 'johnnie-walker-blue-label', 'Bottle', 90000.00, NULL, 15, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4254, 3, 538, 'Jack Daniel\'s (Bottle)', 'jack-daniels-bottle', 'Bottle', 48000.00, NULL, 16, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4255, 3, 538, 'Jack Daniel\'s (Shot)', 'jack-daniels-shot', 'Per shot', 4000.00, NULL, 17, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4256, 3, 538, 'Chivas Regal (Bottle)', 'chivas-regal-bottle', 'Bottle', 25000.00, NULL, 18, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4257, 3, 538, 'Chivas Regal (Shot)', 'chivas-regal-shot', 'Per shot', 2500.00, NULL, 19, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4258, 3, 539, 'Bacardi White (Bottle)', 'bacardi-white-bottle', 'Bottle', 35000.00, NULL, 1, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4259, 3, 539, 'Bacardi White (Shot)', 'bacardi-white-shot', 'Per shot', 3690.00, NULL, 2, 1, '2026-02-13 09:52:41', '2026-05-07 12:18:20'),
(4260, 3, 539, 'Bacardi Gold (Bottle)', 'bacardi-gold-bottle', 'Bottle', 35000.00, NULL, 3, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4261, 3, 539, 'Bacardi Gold (Shot)', 'bacardi-gold-shot', 'Per shot', 2000.00, NULL, 4, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4262, 3, 539, 'Malibu (Bottle)', 'malibu-bottle', 'Bottle', 28000.00, NULL, 5, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4263, 3, 539, 'Malibu (Shot)', 'malibu-shot', 'Per shot', 4000.00, NULL, 6, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4264, 3, 540, 'Ciroc', 'ciroc', 'Bottle', 62000.00, NULL, 1, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4265, 3, 540, 'Absolut Vodka (Bottle)', 'absolut-vodka-bottle', 'Bottle', 73800.00, NULL, 2, 1, '2026-02-13 09:52:41', '2026-05-07 12:17:11'),
(4266, 3, 540, 'Absolut Vodka (Shot)', 'absolut-vodka-shot', 'Per shot', 3000.00, NULL, 3, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4267, 3, 540, 'Smirnoff (Bottle)', 'smirnoff-bottle', 'Bottle', 22000.00, NULL, 4, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4268, 3, 540, 'Smirnoff (Shot)', 'smirnoff-shot', 'Per shot', 2500.00, NULL, 5, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4269, 3, 540, 'Grey Goose (Bottle)', 'grey-goose-bottle', 'Bottle', 45000.00, NULL, 6, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4270, 3, 540, 'Grey Goose (Shot)', 'grey-goose-shot', 'Per shot', 2500.00, NULL, 7, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4271, 3, 541, 'Gin Mare (Bottle)', 'gin-mare-bottle', 'Bottle', 40000.00, NULL, 1, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4272, 3, 541, 'Gin Mare (Shot)', 'gin-mare-shot', 'Per shot', 3000.00, NULL, 2, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4273, 3, 541, 'Hendrick\'s (Bottle)', 'hendricks-bottle', 'Bottle', 73000.00, NULL, 3, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4274, 3, 541, 'Hendrick\'s (Shot)', 'hendricks-shot', 'Per shot', 4000.00, NULL, 4, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4275, 3, 541, 'Hendrick\'s Alt Bottle', 'hendricks-alt-bottle', 'Alternative bottle', 50000.00, NULL, 5, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4276, 3, 541, 'Hendrick\'s Alt Bottle (Shot)', 'hendricks-alt-bottle-shot', 'Per shot', 2500.00, NULL, 6, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4277, 3, 541, 'Bombay Sapphire (Bottle)', 'bombay-sapphire-bottle', 'Bottle', 50000.00, NULL, 7, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4278, 3, 541, 'Bombay Sapphire (Shot)', 'bombay-sapphire-shot', 'Per shot', 4000.00, NULL, 8, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4279, 3, 542, 'Olmeca White (Bottle)', 'olmeca-white-bottle', 'Bottle', 67650.00, NULL, 1, 1, '2026-02-13 09:52:41', '2026-05-07 12:12:07'),
(4280, 3, 543, 'Baileys (Bottle)', 'baileys-bottle', 'Bottle', 30000.00, NULL, 1, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4281, 3, 543, 'Baileys (Shot)', 'baileys-shot', 'Per shot', 3690.00, NULL, 2, 1, '2026-02-13 09:52:41', '2026-05-07 12:19:22'),
(4282, 3, 543, 'Kahlua (Bottle)', 'kahlua-bottle', 'Bottle', 23000.00, NULL, 3, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4283, 3, 543, 'Kahlua (Shot)', 'kahlua-shot', 'Per shot', 2000.00, NULL, 4, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4284, 3, 543, 'Cointreau', 'cointreau', 'Per shot', 2000.00, NULL, 5, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4285, 3, 543, 'Triple Sec', 'triple-sec', 'Per shot', 2000.00, NULL, 6, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4286, 3, 544, 'Campari', 'campari', 'Bottle', 30750.00, NULL, 1, 1, '2026-02-13 09:52:41', '2026-05-07 11:59:42'),
(4287, 3, 544, 'Origin Bitters (Big)', 'origin-bitters-big', 'Bottle', 6237.00, NULL, 2, 1, '2026-02-13 09:52:41', '2026-05-07 12:40:31'),
(4288, 3, 544, 'Origin Bitters (Mini)', 'origin-bitters-mini', '', 2500.00, NULL, 3, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4289, 3, 544, 'Palm Spirit (Aphro / Moor Rum)', 'palm-spirit', 'Bottle', 25000.00, NULL, 4, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4290, 3, 545, 'Moët Nectar Rosé', 'moet-nectar-rose', 'Bottle', 176000.00, NULL, 1, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4291, 3, 545, 'Veuve Clicquot Brut', 'veuve-clicquot-brut', 'Bottle', 170000.00, NULL, 2, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4292, 3, 545, 'Moët Imperial Brut', 'moet-imperial-brut', 'Bottle', 130000.00, NULL, 3, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4293, 3, 546, 'Virgin Colada', 'virgin-colada', '', 12474.00, NULL, 1, 1, '2026-02-13 09:52:41', '2026-05-07 12:16:14'),
(4294, 3, 546, 'Virgin Margarita', 'virgin-margarita', '', 12447.00, NULL, 2, 1, '2026-02-13 09:52:41', '2026-05-07 12:44:46'),
(4295, 3, 546, 'Chapman', 'chapman', '', 9840.00, NULL, 3, 1, '2026-02-13 09:52:41', '2026-05-07 12:46:04'),
(4296, 3, 547, 'Long Island Iced Tea', 'long-island-iced-tea', '', 18711.00, NULL, 1, 1, '2026-02-13 09:52:41', '2026-05-07 12:07:28'),
(4297, 3, 547, 'Daiquiri', 'daiquiri', '', 14968.00, NULL, 2, 1, '2026-02-13 09:52:41', '2026-05-07 12:31:30'),
(4298, 3, 547, 'Moscow Mule', 'moscow-mule', '', 6000.00, NULL, 3, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4299, 3, 547, 'Cosmopolitan', 'cosmopolitan', '', 6000.00, NULL, 4, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4300, 3, 547, 'Margarita', 'margarita', '', 5000.00, NULL, 5, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4301, 3, 547, 'Mojito', 'mojito', '', 7500.00, NULL, 6, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4302, 3, 547, 'Sex on the Beach', 'sex-on-the-beach', '', 5000.00, NULL, 7, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4303, 3, 547, 'Piña Colada', 'pina-colada', '', 6000.00, NULL, 8, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4304, 3, 547, 'Tequila Sunrise', 'tequila-sunrise', '', 4000.00, NULL, 9, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4305, 3, 547, 'Mai Tai', 'mai-tai', '', 6000.00, NULL, 10, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4306, 3, 547, 'Whiskey Sour', 'whiskey-sour', '', 6000.00, NULL, 11, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4307, 3, 547, 'Screaming Orgasm', 'screaming-orgasm', '', 8500.00, NULL, 12, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4308, 3, 547, 'The Boss', 'the-boss', '', 5000.00, NULL, 13, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4309, 3, 547, 'D\'View Cocktail', 'dview-cocktail', 'Signature cocktail', 5000.00, NULL, 14, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4310, 3, 548, 'Nederburg Sauvignon Blanc', 'nederburg-sauvignon-blanc', 'Bottle', 51143.40, NULL, 1, 1, '2026-02-13 09:52:41', '2026-05-07 12:09:10'),
(4311, 3, 548, 'Nederburg Late Harvest', 'nederburg-late-harvest', 'Bottle', 36000.00, NULL, 2, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4312, 3, 548, 'Nederburg Chardonnay', 'nederburg-chardonnay', 'Bottle', 36000.00, NULL, 3, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41');
INSERT INTO `menu_items` (`id`, `restaurant_id`, `category_id`, `name`, `slug`, `description`, `price`, `image`, `display_order`, `is_available`, `created_at`, `updated_at`) VALUES
(4313, 3, 548, 'Mapu Sauvignon Blanc', 'mapu-sauvignon-blanc', 'Bottle', 19000.00, NULL, 4, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4314, 3, 548, 'Four Cousins', 'four-cousins-white', 'Bottle', 19000.00, NULL, 5, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4315, 3, 548, 'Frontera Moscato', 'frontera-moscato', 'Bottle', 16000.00, NULL, 6, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4316, 3, 548, 'Viala Moscato', 'viala-moscato', 'Bottle', 12000.00, NULL, 7, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4317, 3, 549, 'Nederburg Merlot', 'nederburg-merlot', 'Bottle', 48648.60, NULL, 1, 1, '2026-02-13 09:52:41', '2026-05-07 12:08:32'),
(4318, 3, 549, 'Nederburg Cabernet Sauvignon', 'nederburg-cabernet-sauvignon', 'Bottle', 36000.00, NULL, 2, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4319, 3, 549, 'Escudo Rojo', 'escudo-rojo', 'Bottle', 32000.00, NULL, 3, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4320, 3, 549, 'Mapu Cabernet Sauvignon', 'mapu-cabernet-sauvignon', 'Bottle', 19000.00, NULL, 4, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4321, 3, 549, 'Four Cousins', 'four-cousins-red', 'Bottle', 18000.00, NULL, 5, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4322, 3, 549, 'Carlo Rossi', 'carlo-rossi', 'Bottle', 12000.00, NULL, 6, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4323, 3, 549, 'Drostdy-Hof', 'drostdy-hof', 'Bottle', 12000.00, NULL, 7, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4324, 3, 549, '4th Street Red', '4th-street-red', 'Bottle', 12000.00, NULL, 8, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4325, 3, 549, 'Asara', 'asara', 'Bottle', 12000.00, NULL, 9, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4326, 3, 549, 'Bolzano', 'bolzano', 'Bottle', 12000.00, NULL, 10, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4327, 3, 549, 'Châteauneuf-du-Pape', 'chateauneuf-du-pape', 'Bottle', 20000.00, NULL, 11, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4328, 3, 550, 'Cappuccino', 'cappuccino', '', 2000.00, NULL, 1, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4329, 3, 550, 'Double Espresso', 'double-espresso', '', 1500.00, NULL, 3, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4330, 3, 550, 'Single Espresso', 'single-espresso', '', 1000.00, NULL, 4, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4331, 3, 552, 'Fresh Orange Juice', 'fresh-orange-juice', '', 9979.20, NULL, 1, 1, '2026-02-13 09:52:41', '2026-05-07 12:01:00'),
(4332, 3, 552, 'Fresh Pineapple Juice', 'fresh-pineapple-juice', '', 9840.00, NULL, 2, 1, '2026-02-13 09:52:41', '2026-05-07 12:35:02'),
(4333, 3, 552, 'Fresh Watermelon Juice', 'fresh-watermelon-juice', '', 4000.00, NULL, 3, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4334, 3, 552, 'Sweet Zobo Drink', 'sweet-zobo-drink', '', 2000.00, NULL, 4, 1, '2026-02-13 09:52:41', '2026-02-13 09:52:41'),
(4335, 3, 553, 'Premium Tray', 'premium-tray', 'Miniature wine bottle, juice pack, lemonade bottle, biscuits, wafers, coconut flakes, yoghurt cups, almonds, mug with assorted hot beverages, fresh bread rolls with butter, jam & cheese, club sandwich, cakes & croissants, plantain skewers, grapes & kiwi, English breakfast with lamb sausage, French toast, pancakes', 60000.00, NULL, 1, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4336, 3, 553, 'Deluxe Tray', 'deluxe-tray', 'Mug with assorted hot beverages, fresh bread rolls with butter, jam & cheese, club sandwich, biscuit pack, juice pack, yoghurt cups, grapes, apples, English breakfast with lamb sausage', 60000.00, NULL, 2, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4337, 3, 554, 'Breakfast Burger', 'breakfast-burger', 'With tea or coffee', 14999.00, NULL, 1, 0, '2026-02-13 10:57:53', '2026-05-20 10:09:17'),
(4338, 3, 554, 'English breakfast', 'classic-english-breakfast', 'Sausages, bread, eggs, baked beans, butter, toast', 15000.00, NULL, 3, 1, '2026-02-13 10:57:53', '2026-05-20 10:10:47'),
(4339, 3, 554, 'African Breakfast', 'african-breakfast', 'Boiled or fried yam or plantain, egg sauce', 12300.00, NULL, 4, 1, '2026-02-13 10:57:53', '2026-05-20 10:11:42'),
(4340, 3, 554, 'French breakfast', 'naija-special', 'freshly baked, croisant, and baguette with sliced cheese fresh fruits, cup cakes with honey jam and butter. A delightful blend of savory and flavor', 15000.00, NULL, 5, 1, '2026-02-13 10:57:53', '2026-05-20 10:18:32'),
(4341, 3, 555, 'The view special salad', 'chefs-salad', 'Grilled chicken breast, cherry tomato, ice berg lettuce, sliced avocado with tortilla chips, side with sauce.', 25000.00, NULL, 1, 1, '2026-02-13 10:57:53', '2026-05-20 10:27:17'),
(4342, 3, 555, 'Chicken Caesar Salad', 'chicken-caesar-salad', 'Creamy potato based salad, made with boiled vegetables, eggs, pickles, peas and mayonnaise.', 18450.00, NULL, 2, 1, '2026-02-13 10:57:53', '2026-05-20 10:29:57'),
(4343, 3, 555, 'Russian Salad', 'russian-salad', 'Chicken breast, carrot, Irish potatoes, sauce', 15000.00, NULL, 3, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4344, 3, 556, 'Fresh Croaker Fish (Whole)', 'fresh-croaker-fish-whole', 'Served with fresh bread rolls', 36900.00, NULL, 1, 1, '2026-02-13 10:57:53', '2026-05-20 10:04:34'),
(4345, 3, 556, 'Catfish (Whole)', 'catfish-whole', 'A spicy delicacy made with catfish, native spices, herbs. its warming and Aromatic', 36899.95, NULL, 10, 1, '2026-02-13 10:57:53', '2026-05-07 12:24:52'),
(4346, 3, 556, 'Fresh Croaker Fish (Portion)', 'fresh-croaker-fish-portion', 'paired with potato or yam', 18450.00, NULL, 3, 1, '2026-02-13 10:57:53', '2026-05-20 10:32:13'),
(4347, 3, 556, 'Catfish (Portion)', 'catfish-portion', 'paired with potato or yam', 18450.00, NULL, 4, 1, '2026-02-13 10:57:53', '2026-05-20 10:33:18'),
(4348, 3, 556, 'Goat Meat Pepper Soup', 'goat-meat-pepper-soup', 'paired with potato or yam', 18450.00, NULL, 5, 1, '2026-02-13 10:57:53', '2026-05-20 10:43:16'),
(4349, 3, 556, 'Chicken Pepper Soup', 'chicken-pepper-soup', 'paired with potatos or yam', 18450.00, NULL, 6, 1, '2026-02-13 10:57:53', '2026-05-20 10:46:30'),
(4350, 3, 556, 'Cream of chicken soup', 'chinese-noodle-soup-shrimp-chicken', 'blended stock with cream or milk, enriched with chicken chunks alongside proper seasoning.', 18450.00, NULL, 7, 1, '2026-02-13 10:57:53', '2026-05-20 10:55:21'),
(4351, 3, 556, 'Asian chicken noodle soup', 'creamy-italian-seafood-soup', 'Tender chicken, and noodles with fragrant herbs and spices', 27000.00, NULL, 8, 1, '2026-02-13 10:57:53', '2026-05-20 11:01:59'),
(4352, 3, 556, 'French Onion Soup', 'french-onion-soup', 'Served with fresh bread rolls', 10000.00, NULL, 10, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4353, 3, 557, 'Nick Nack Combo Board', 'nick-nack-combo-board', '', 10500.00, NULL, 1, 0, '2026-02-13 10:57:53', '2026-05-20 13:47:51'),
(4354, 3, 557, 'Spicy Snails', 'spicy-snails', '', 24860.00, NULL, 2, 1, '2026-02-13 10:57:53', '2026-05-07 12:43:01'),
(4355, 3, 557, 'Spicy Goat Dodo', 'spicy-goat-dodo', 'Tender gizzards with diced plantain mixed in vegetables.', 27500.00, NULL, 3, 1, '2026-02-13 10:57:53', '2026-05-20 11:20:15'),
(4356, 3, 557, 'Peppered Goat Meat', 'peppered-goat-meat', 'Deep fried goat meat in peppered sauce', 18450.00, NULL, 4, 1, '2026-02-13 10:57:53', '2026-05-20 11:22:05'),
(4357, 3, 557, 'Gizzdodo', 'gizzdodo', '', 27500.00, NULL, 6, 1, '2026-02-13 10:57:53', '2026-05-20 11:23:13'),
(4358, 3, 557, 'Smokey Chicken Wings', 'smokey-chicken-wings', '', 18450.00, NULL, 7, 1, '2026-02-13 10:57:53', '2026-05-20 11:24:08'),
(4359, 3, 557, 'Hot Chicken Wings', 'hot-chicken-wings', '', 18450.00, NULL, 8, 1, '2026-02-13 10:57:53', '2026-05-20 13:44:51'),
(4360, 3, 557, 'Buffalo Wings', 'buffalo-wings', '', 18450.00, NULL, 10, 1, '2026-02-13 10:57:53', '2026-05-20 13:48:55'),
(4361, 3, 557, 'Nkwobi', 'nkwobi', '', 18450.00, NULL, 11, 1, '2026-02-13 10:57:53', '2026-05-20 13:45:34'),
(4362, 3, 557, 'Peppered Gizzard', 'peppered-gizzard', '', 18450.00, NULL, 12, 1, '2026-02-13 10:57:53', '2026-05-20 13:53:03'),
(4363, 3, 557, 'Shrimp Rolls (4 pcs)', 'shrimp-rolls-4pcs', '', 15000.00, NULL, 14, 0, '2026-02-13 10:57:53', '2026-05-20 13:49:56'),
(4364, 3, 557, 'Pepper Beef', 'pepper-beef', '', 18450.00, NULL, 15, 1, '2026-02-13 10:57:53', '2026-05-20 13:53:48'),
(4365, 3, 557, 'Pepper Chicken', 'pepper-chicken', '', 18450.00, NULL, 16, 1, '2026-02-13 10:57:53', '2026-05-20 13:55:07'),
(4366, 3, 557, 'Pepper Turkey', 'pepper-turkey', '', 18450.00, NULL, 17, 1, '2026-02-13 10:57:53', '2026-05-20 13:56:10'),
(4367, 3, 558, 'Tuna sandwish', 'dview-club-sandwich', 'triple decker tuna sandwich with vegetables', 18500.00, NULL, 3, 1, '2026-02-13 10:57:53', '2026-05-20 14:26:27'),
(4368, 3, 558, 'Club sandwich', 'chunky-tuna-sandwich', 'Triple decker bread with shredded chicken, cheese, bacon and vegetables', 18450.00, NULL, 5, 1, '2026-02-13 10:57:53', '2026-05-20 14:29:01'),
(4369, 3, 560, 'Grilled Salmon', 'grilled-salmon', '', 43659.00, NULL, 1, 1, '2026-02-13 10:57:53', '2026-05-07 12:01:51'),
(4370, 3, 560, 'Grilled Croaker Fish', 'grilled-croaker-fish', '', 37422.00, NULL, 2, 1, '2026-02-13 10:57:53', '2026-05-07 12:38:23'),
(4371, 3, 560, 'Grilled Catfish', 'grilled-catfish', '', 36900.00, NULL, 3, 1, '2026-02-13 10:57:53', '2026-05-20 14:52:38'),
(4372, 3, 560, 'Grilled fillet steak', 'grilled-jumbo-prawns', 'A perfectly grilled fillet steak with mashed potato or steamed vegetable.', 55500.00, NULL, 4, 1, '2026-02-13 10:57:53', '2026-05-20 14:59:55'),
(4373, 3, 560, 'T. bone steak', 'lobster-thermidor', 'An iconic cuts of beef, prized for its tenderness and flavor seasoned and grilled to perfection.', 46200.00, NULL, 6, 1, '2026-02-13 10:57:53', '2026-05-20 15:02:36'),
(4374, 3, 560, 'Lamb Chops', 'golden-tilapia', 'A carefully grilled to choice, juicy and tenderness', 46200.00, NULL, 7, 1, '2026-02-13 10:57:53', '2026-05-20 15:04:43'),
(4375, 3, 561, 'T-Bone', 't-bone', 'South African cuts — served with side of choice', 46153.00, NULL, 1, 1, '2026-02-13 10:57:53', '2026-05-07 12:15:21'),
(4376, 3, 561, 'Rib-Eye', 'rib-eye', 'South African cuts — served with side of choice', 46153.00, NULL, 2, 1, '2026-02-13 10:57:53', '2026-05-07 12:41:50'),
(4377, 3, 561, 'Lamb Chops', 'lamb-chops', 'South African cuts — served with side of choice', 30000.00, NULL, 3, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4378, 3, 561, 'Beef Ribs', 'beef-ribs', 'South African cuts — served with side of choice', 22000.00, NULL, 4, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4379, 3, 561, 'Oxtail', 'oxtail', 'South African cuts — served with side of choice', 6000.00, NULL, 5, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4380, 3, 562, 'Mixed Grill Special', 'mixed-grill-special', '', 13300.00, NULL, 1, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4381, 3, 563, 'Pacific Platter', 'pacific-platter', '', 38000.00, NULL, 2, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4382, 3, 563, 'D\'View Special Platter', 'dview-special-platter', '', 25000.00, NULL, 3, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4383, 3, 563, 'Ogazi Platter', 'ogazi-platter', '', 25000.00, NULL, 4, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4384, 3, 564, 'Spaghetti Bolognaise', 'spaghetti-prawn-marinara', 'Traditionally slow simered spaghetti, served with meat sauce', 30750.00, NULL, 1, 1, '2026-02-13 10:57:53', '2026-05-20 10:07:56'),
(4385, 3, 564, 'Seafood Pasta', 'seafood-pasta', '', 15000.00, NULL, 3, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4386, 3, 564, 'Spaghetti & Meatballs', 'spaghetti-meatballs', '', 8000.00, NULL, 4, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4387, 3, 564, 'Fettuccine Alfredo', 'fettuccine-alfredo', '', 16000.00, NULL, 5, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4388, 3, 564, 'Chicken Pesto Penne', 'chicken-pesto-penne', '', 13000.00, NULL, 6, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4389, 3, 564, 'Spaghetti Bolognese', 'spaghetti-bolognese', '', 15000.00, NULL, 7, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4390, 3, 564, 'Spaghetti Aglio Olio', 'spaghetti-aglio-olio', '', 6000.00, NULL, 8, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4391, 3, 564, 'Fettuccine Prawn Grill', 'fettuccine-prawn-grill', '', 7000.00, NULL, 9, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4392, 3, 565, 'Okro (Seafood)', 'okro-seafood', 'Served with semovita, eba, or pounded yam', 43659.00, NULL, 1, 1, '2026-02-13 10:57:53', '2026-05-07 12:09:58'),
(4393, 3, 565, 'Eforiro (Seafood)', 'eforiro-seafood', 'Served with semovita, eba, or pounded yam', 43659.00, NULL, 2, 1, '2026-02-13 10:57:53', '2026-05-07 12:33:10'),
(4394, 3, 565, 'Edikaikong (Seafood)', 'edikaikong-seafood', 'Served with semovita, eba, or pounded yam', 30000.00, NULL, 3, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4395, 3, 565, 'Egusi (Seafood)', 'egusi-seafood', 'Served with semovita, eba, or pounded yam', 30000.00, NULL, 4, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4396, 3, 565, 'Fisherman Soup (Croaker / Catfish)', 'fisherman-soup', 'Served with semovita, eba, or pounded yam', 30000.00, NULL, 5, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4397, 3, 565, 'Edikaikong (Regular)', 'edikaikong-regular', 'Served with semovita, eba, or pounded yam', 18000.00, NULL, 6, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4398, 3, 565, 'Eforiro (Regular)', 'eforiro-regular', 'Served with semovita, eba, or pounded yam', 18000.00, NULL, 7, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4399, 3, 565, 'Afang', 'afang', 'Served with semovita, eba, or pounded yam', 18000.00, NULL, 8, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4400, 3, 565, 'Ogbono', 'ogbono', 'Served with semovita, eba, or pounded yam', 18000.00, NULL, 9, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4401, 3, 566, 'Seafood Jollof Rice', 'seafood-jollof-rice', '', 43650.00, NULL, 1, 1, '2026-02-13 10:57:53', '2026-05-07 12:13:05'),
(4402, 3, 566, 'D\'View Special Fried Rice', 'dview-special-fried-rice', '', 22457.00, NULL, 2, 1, '2026-02-13 10:57:53', '2026-05-07 12:30:24'),
(4403, 3, 566, 'Jollof Rice Fiesta', 'jollof-rice-fiesta', '', 16000.00, NULL, 3, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4404, 3, 566, 'Isi Ewu', 'isi-ewu', '', 20000.00, NULL, 4, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4405, 3, 566, 'Yam Pottage', 'yam-pottage', '', 15000.00, NULL, 5, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4406, 3, 567, 'Jollof Rice', 'jollof-rice-side', 'freshly cooked smokey Nigerian jollof rice', 8610.00, NULL, 1, 1, '2026-02-13 10:57:53', '2026-05-07 12:04:10'),
(4407, 3, 567, 'Fried Rice', 'fried-rice', '', 8610.00, NULL, 2, 1, '2026-02-13 10:57:53', '2026-05-07 12:37:06'),
(4408, 3, 567, 'Fried Plantain', 'fried-plantain', '', 7000.00, NULL, 3, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4409, 3, 567, 'Yam Chips', 'yam-chips', '', 7000.00, NULL, 4, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4410, 3, 567, 'French Fries', 'french-fries', '', 5000.00, NULL, 5, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4411, 3, 567, 'Sweet Potato Fries', 'sweet-potato-fries', '', 5000.00, NULL, 6, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4412, 3, 567, 'Steamed Rice', 'steamed-rice', '', 5000.00, NULL, 7, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4413, 3, 567, 'Bread Rolls (2 pcs)', 'bread-rolls-2pcs', '', 1000.00, NULL, 8, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4414, 3, 567, 'Eggs (2)', 'eggs-2', '', 5000.00, NULL, 9, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4415, 3, 567, 'Ogbono Extra', 'ogbono-extra', '', 7000.00, NULL, 10, 1, '2026-02-13 10:57:53', '2026-02-13 10:57:53'),
(4416, 3, 556, 'Thai chicken bites', 't', 'Tahi chicken bites, served with sweet chilli sauce', 18500.00, NULL, 0, 1, '2026-05-20 11:05:57', '2026-05-20 11:05:57'),
(4417, 3, 556, 'Chicken Escalope', 'a', 'Flattened chicken breast into thin cutlets, coated with crumbs fried till golden and crisp.', 20000.00, NULL, 0, 1, '2026-05-20 11:10:23', '2026-05-20 11:10:23'),
(4418, 3, 556, 'Chicken pesto penny', 'c', 'Marinated chicken breast sliced in lemon and onion coated in crumbs, fried until golden brown', 33210.00, NULL, 0, 1, '2026-05-20 11:12:46', '2026-05-20 11:12:46'),
(4419, 3, 558, 'Beef burger', 'b', 'Made of a ground beef patty, placed inside a bun, often accompanied by cheese, lettuce, tomato onions pickles and condiments like ketchup, mustard or mayonnaise.', 12300.00, NULL, 0, 1, '2026-05-20 14:33:25', '2026-05-20 14:33:25'),
(4420, 3, 558, 'Chicken burger', 'c', 'Made with chicken fillet, served inside a bun, often with lettuce, tomato, cheese and sauces.', 12300.00, NULL, 0, 1, '2026-05-20 14:39:01', '2026-05-20 14:39:01'),
(4421, 3, 562, 'Seafood okro', 's', 'okra in mix of prawns, calamaris, fish and snails', 43050.00, NULL, 0, 1, '2026-05-20 15:07:48', '2026-05-20 15:07:48'),
(4422, 3, 562, 'Fishermans soup', 'f', 'A traditional seafood delicacy made of prawns, snails, calamari, and crabs.', 43000.00, NULL, 0, 1, '2026-05-20 15:10:22', '2026-05-20 15:10:22'),
(4423, 3, 562, 'Seafood Efo riro', 's-2', 'A nigerian delicacy with combination of fried seafood like snails, fish, calamari and dry fish', 43659.00, NULL, 0, 1, '2026-05-20 15:12:28', '2026-05-20 15:12:28'),
(4424, 27, 568, 'Thai Coconut Seafood Soup', 'mm-soups-thai-coconut-seafood-soup', 'Red curry creamy broth, calamari, octopus, shrimp', 16400.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4425, 27, 568, 'Meat On Bread', 'mm-soups-meat-on-bread-2', 'Minced marinated lamb meat, mixed cheese, roasted pine nuts, crispy caramelized onion and pomegranate sauce.', 12000.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4426, 27, 569, 'Jumbo Shrimps Cocktail', 'mm-appetizers-jumbo-shrimps-cocktail', 'Boiled jumbo-size shrimp, crispy romaine lettuce, and homemade cocktail sauce.', 19000.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4427, 27, 569, 'Fresh Vietnamese Summer Roll', 'mm-appetizers-fresh-vietnamese-summer-roll-2', 'Rolled in rice paper, shrimp, rice vermicelli, carrot, cucumber, mint, basil, and lettuce, served with homemade Vietnamese dip.', 16000.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4428, 27, 569, 'Imperial Fried Spring Rolls', 'mm-appetizers-imperial-fried-spring-rolls-3', 'Rolled in rice paper, deep fried, pork minced meat, rice vermicelli, carrot, and tacky mushrooms, served with homemade Asian dip.', 15000.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4429, 27, 569, 'Chicken Tender Italian Style', 'mm-appetizers-chicken-tender-italian-style-4', 'Breaded tenderloin chicken deep fried, parmesan cheese, mixed Italian herbs, served with honey mustard dip.', 12500.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4430, 27, 569, 'Coconut Crispy Calamari', 'mm-appetizers-coconut-crispy-calamari-5', 'Deep fried calamari marinated in coconut milk served with tartar and roasted bell pepper carrot dip.', 14000.00, NULL, 5, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4431, 27, 569, 'Crispy Chicken Wings', 'mm-appetizers-crispy-chicken-wings-6', 'Deep-fried marinated chicken wings, served with BBQ and sweet chili dip.', 11500.00, NULL, 6, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4432, 27, 569, 'Flame Kissed Octopus', 'mm-appetizers-flame-kissed-octopus-7', 'Marinated seared octopus, caramelized onions, pomegranate sauce.', 15500.00, NULL, 7, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4433, 27, 569, 'Fried Butterfly Shrimps', 'mm-appetizers-fried-butterfly-shrimps-8', 'Breaded deep-fried shrimp served with homemade cocktail dip.', 15500.00, NULL, 8, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4434, 27, 569, 'Appetizer Platter', 'mm-appetizers-appetizer-platter-9', '(Serve up to 4) Chicken tender, fried calamari, chicken wings, wedges potato, fried rolls, fried shrimp, served with tartar, cocktail, sweet chili, Thai dips.', 32500.00, NULL, 9, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4435, 27, 569, 'Dynamite Chicken', 'mm-appetizers-dynamite-chicken-10', 'Crispy, golden brown-fried chicken served with the dynamite sauce.', 11500.00, NULL, 10, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4436, 27, 569, 'Dynamite Shrimp', 'mm-appetizers-dynamite-shrimp-11', 'Crispy, fried shrimp coated in a spicy mayonnaise dressing.', 12500.00, NULL, 11, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4437, 27, 569, 'Nigerian Platter', 'mm-appetizers-nigerian-platter-12', 'Spicy gizzards & snails, chicken suya, yam fingers & plantain.', 20800.00, NULL, 12, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4438, 27, 569, 'Spicy Chicken Wings', 'mm-appetizers-spicy-chicken-wings-13', 'Deep-fried chicken wings toasted in homemade chili pepper sauce or in BBQ sauce', 15500.00, NULL, 13, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4439, 27, 570, 'Classic Niçoise', 'mm-salads-classic-ni-oise', 'Crispy lettuce, tomato, boiled potato, boiled egg, green beans, white tuna, black olives, and anchovies, served with balsamic vinegar sauce.', 15500.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4440, 27, 570, 'Exotic Seafood', 'mm-salads-exotic-seafood-2', 'Crispy lettuce, tomato, fresh pineapple, avocado, sweet corn, carrot, calamari, crab, shrimp served with sweet lemon sauce.', 20500.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4441, 27, 570, 'Tropical Chicken', 'mm-salads-tropical-chicken-3', 'Crispy lettuce, tomato, grilled chicken, parmesan cheese, marinated croutons, grilled chicken breast served with tropical homemade sauce.', 16000.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4442, 27, 570, 'Smoked Salmon Salad', 'mm-salads-smoked-salmon-salad-4', 'Crispy lettuce, smoked salmon, avocado, capers, red onions, served with lemon oil sauce.', 22000.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4443, 27, 570, 'Add Grilled Chicken Breast To Your Salad', 'mm-salads-add-grilled-chicken-breast-to-your-salad-5', NULL, 5200.00, NULL, 5, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4444, 27, 571, 'Vendome Shrimp Tagliatelle', 'mm-pasta-vendome-shrimp-tagliatelle', 'Cream homemade sauce, shrimp.', 15500.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4445, 27, 571, 'Spaghetti Mare', 'mm-pasta-spaghetti-mare-2', 'Marinated shrimp and calamari, white or red sauce of your choice.', 16000.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4446, 27, 571, 'Carbonara', 'mm-pasta-carbonara-3', 'Linguini pasta, creamy sauce, your choice of bacon or smoked turkey', 14500.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4447, 27, 571, 'Braised Rigatoni Ragout', 'mm-pasta-braised-rigatoni-ragout-4', 'Tozo beef with tomato red wine sauce.', 13500.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4448, 27, 571, 'Chicken Alfredo', 'mm-pasta-chicken-alfredo-5', 'Fettuccini, creamy sauce, chicken breast, mixed mushrooms, parmesan cheese', 15500.00, NULL, 5, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4449, 27, 572, 'Seared Salmon', 'mm-fish-and-seafood-seared-salmon', 'Seared salmon filet, served with sautéed vegetables and creamy homemade sauce.', 33000.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4450, 27, 572, 'Grilled Marinated Tiger Prawns', 'mm-fish-and-seafood-grilled-marinated-tiger-prawns-2', 'Grilled marinated tiger prawns, grilled vegetables, and baked potato served with tartar and cocktail sauce.', 33500.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4451, 27, 572, 'Fish And Chips', 'mm-fish-and-seafood-fish-and-chips-3', 'Buttered deep-fried fish filet and French fries served with tartar sauce.', 16000.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4452, 27, 572, 'Coconut Shrimps', 'mm-fish-and-seafood-coconut-shrimps-4', 'Your choice of grilled or breaded deep-fried shrimp, steamed rice, or grilled pineapple served with coconut curry sauce', 20500.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4453, 27, 572, 'Grilled Whole Fish African Style', 'mm-fish-and-seafood-grilled-whole-fish-african-style-5', 'Grilled whole fish marinated in African sauce, sauteed vegetables served with fried yam and plantain.', 13500.00, NULL, 5, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4454, 27, 572, 'Traditional Prawns', 'mm-fish-and-seafood-traditional-prawns-6', 'Marinated grilled prawns, pineapple fried rice, mushrooms, green pies, and sweet corn, served with coriander in tomato sauce.', 19500.00, NULL, 6, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4455, 27, 572, 'Seafood Platter', 'mm-fish-and-seafood-seafood-platter-7', 'Stir-fried calamari, coconut shrimp, Deep-fried white fish fillet, Grilled octopus, served with cocktail sauce &Tartar sauce.', 25000.00, NULL, 7, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4456, 27, 572, 'Prawns Suya', 'mm-fish-and-seafood-prawns-suya-8', 'Marinated Grilled prawns, in suya spice served with French fries', 25000.00, NULL, 8, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4457, 27, 573, 'Grilled T-Bone Steak', 'mm-meat-grilled-t-bone-steak', '350 gr of grilled T-bone steak, grilled vegetables, wedges potatoes served with mushroom sauce.', 30500.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4458, 27, 573, 'Lamb Shank', 'mm-meat-lamb-shank-2', 'South African lamb chunk slow-cooked with herb oil, mashed potato, and sauteed vegetables served with spicy homemade sauce.', 25500.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4459, 27, 573, 'Mongolian Beef', 'mm-meat-mongolian-beef-3', 'Sliced imported beef filet, sauteed in a homemade Mongolian sauce served with Singaporean fried rice.', 30500.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4460, 27, 573, 'Chateau Brilliant', 'mm-meat-chateau-brilliant-4', '250g of Grilled imported beef filet, sauteed vegetables, French fries served with your choice of peppercorn or mushroom sauce', 30500.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4461, 27, 573, 'Rib Eye Steak', 'mm-meat-rib-eye-steak-5', '250g of Grilled imported beef filet, sauteed vegetables, French fries served with your choice of peppercorn or mushroom sauce', 28500.00, NULL, 5, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4462, 27, 573, 'Grilled Tozo', 'mm-meat-grilled-tozo-6', 'Marinated smoked beef cut served with leeks garlic creamy sauce, served with mashed potato', 17000.00, NULL, 6, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4463, 27, 573, 'Garlic Butter Lamb Chops', 'mm-meat-garlic-butter-lamb-chops-7', '4 pieces grilled imported lamb chops topped with garlic herbs sauce, grilled vegetables, wedges potato.', 32000.00, NULL, 7, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4464, 27, 574, 'Chicken Parmesan', 'mm-chicken-chicken-parmesan', 'Deep-fried breaded chicken breast topped with tomato sauce and parmesan cheese served with tomato basil spaghetti.', 15000.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4465, 27, 574, 'Grilled Chicken African Style', 'mm-chicken-grilled-chicken-african-style-2', 'African style marinated half chicken, fried plantain rice, spicy tomato sauce African way.', 16500.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4466, 27, 574, 'Vendome Touch Chicken', 'mm-chicken-vendome-touch-chicken-3', 'Marinated grilled chicken breast, and grilled vegetables, served with Vendome touch rice.', 15000.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4467, 27, 574, 'Braised Chicken Lap', 'mm-chicken-braised-chicken-lap-4', 'Season grilled chicken lap, grilled vegetable, potato wedges, served with garlic mayo sauce & spicy tomato sauce.', 14500.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4468, 27, 574, 'Creamy Chicken Supreme', 'mm-chicken-creamy-chicken-supreme-5', 'Grilled chicken breast toasted in a mushroom creamy sauce served with steamed rice.', 18500.00, NULL, 5, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4469, 27, 574, 'Grilled Chicken Wings', 'mm-chicken-grilled-chicken-wings-6', 'Marinated grilled chicken wings served with French fries and ketchup BBQ sauce.', 13900.00, NULL, 6, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4470, 27, 574, 'Oriental Grill', 'mm-chicken-oriental-grill-7', 'Grilled chicken skewers served with Soft tortilla bread, French fries or fried yam fingers, garlic mayo sauce.', 16500.00, NULL, 7, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4471, 27, 574, 'Chicken Suya', 'mm-chicken-chicken-suya-8', 'Marinated chicken breast, in suya spice', 16500.00, NULL, 8, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4472, 27, 574, 'Imported Beef Filet', 'mm-chicken-imported-beef-filet-9', NULL, 36500.00, NULL, 9, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4473, 27, 574, 'Chicken Breast', 'mm-chicken-chicken-breast-10', 'Stir fry Mexican style, salsa, guacamole, sour cream, and mixed cheese served with hot tortilla bread.', 25100.00, NULL, 10, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4474, 27, 574, 'South American Style Grilled Platter', 'mm-chicken-south-american-style-grilled-platter-11', '(Serve up to 4) 250 gr rib eye, 200 gr lamb chops, 280 gr duck breast, 230 gr chicken breast, and grilled vegetables served with chimichurri, teriyaki, and BBQ sauces.', 73900.00, NULL, 11, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4475, 27, 574, 'South American Style Grilled Platter', 'mm-chicken-south-american-style-grilled-platter-12', '(Serve up to 4) 250 gr rib eye, 200 gr lamb chops, 280 gr duck breast, 230 gr chicken breast, and grilled vegetables served with chimichurri, teriyaki, and BBQ sauces.', 25100.00, NULL, 12, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4476, 27, 574, '(Serve up to 2)', 'mm-chicken-serve-up-to-2-13', NULL, 16200.00, NULL, 13, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4477, 27, 574, 'Serve up to 4)', 'mm-chicken-serve-up-to-4-14', 'Grilled whole fish marinated in African sauce, sauteed vegetables served with fried yam and plantain.', 31700.00, NULL, 14, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4478, 27, 575, 'Mini Slider', 'mm-burger-mini-slider', 'Three flavors of mini burgers, beef, chicken, fried mozzarella', 15500.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4479, 27, 575, 'Vendome Beef Burger', 'mm-burger-vendome-beef-burger-2', '170 gr beef patty, cheddar cheese, mushrooms, caramelized onions, crispy bacon, lettuce, tomato, homemade sauce, BBQ sauce served with French fries and a side salad.', 16500.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4480, 27, 575, 'Cajun Chicken Burger', 'mm-burger-cajun-chicken-burger-3', 'Choice of grilled or fried chicken breast, lettuce, tomato, onion, jalapeño, cheddar cheese, homemade sauce, or BBQ sauce, served with French fries and a side salad.', 16500.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4481, 27, 575, 'Fish Burger', 'mm-burger-fish-burger-4', 'Soft bun, crispy fried white fish fillet, cheddar cheese, tartar sauce, sliced tomato, crispy lettuce.', 16500.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4482, 27, 576, 'Margarita', 'mm-pizza-margarita', 'Homemade tomato sauce topped with mixed cheese.', 10500.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4483, 27, 576, 'Chicken Pizza', 'mm-pizza-chicken-pizza-2', 'Homemade tomato sauce topped with mixed mushrooms and cheese, grilled chicken breast, and truffle oil.', 14500.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4484, 27, 576, 'Veggie Pizza', 'mm-pizza-veggie-pizza-3', 'Homemade tomato sauce, eggplant, green pepper, onion, tomato, and black olives topped with mixed cheese.', 14000.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4485, 27, 576, 'Hawaiian Seafood', 'mm-pizza-hawaiian-seafood-4', 'BBQ sauce, calamari, shrimp,0ctupus, fresh pineapple topped with mixed cheese', 17500.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4486, 27, 576, 'Pepperoni Pizza', 'mm-pizza-pepperoni-pizza-5', 'Homemade tomato sauce, beef pepperoni topped with mixed cheese', 15000.00, NULL, 5, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4487, 27, 576, 'Grilled Or Sauteed Vegetables', 'mm-pizza-grilled-or-sauteed-vegetables-6', NULL, 4500.00, NULL, 6, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4488, 27, 576, 'Mashed Potatoes', 'mm-pizza-mashed-potatoes-7', NULL, 5500.00, NULL, 7, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4489, 27, 576, 'French Fries', 'mm-pizza-french-fries-8', NULL, 5000.00, NULL, 8, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4490, 27, 576, 'Wedges Potatoes', 'mm-pizza-wedges-potatoes-9', NULL, 4000.00, NULL, 9, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4491, 27, 576, 'Singaporean Fried Rice', 'mm-pizza-singaporean-fried-rice-10', NULL, 5500.00, NULL, 10, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4492, 27, 576, 'Steamed Rice', 'mm-pizza-steamed-rice-11', NULL, 5000.00, NULL, 11, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4493, 27, 576, 'Spanish Rice', 'mm-pizza-spanish-rice-12', NULL, 5500.00, NULL, 12, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4494, 27, 576, 'Lyonnaise Potatoes', 'mm-pizza-lyonnaise-potatoes-13', NULL, 8000.00, NULL, 13, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4495, 27, 576, 'Fried Yam', 'mm-pizza-fried-yam-14', NULL, 4500.00, NULL, 14, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4496, 27, 576, 'Fried Plantain', 'mm-pizza-fried-plantain-15', NULL, 4500.00, NULL, 15, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4497, 27, 577, 'Exotic Fruit Tart', 'mm-dessert-exotic-fruit-tart', NULL, 9000.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4498, 27, 577, 'Fondant Chocolate', 'mm-dessert-fondant-chocolate-2', 'Served with vanilla ice cream', 10000.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4499, 27, 577, 'Ginger Cake', 'mm-dessert-ginger-cake-3', 'Served with vanilla ice cream and apple caramel.', 8000.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4500, 27, 577, 'Ice Cream Scoop', 'mm-dessert-ice-cream-scoop-4', 'Chocolate vanilla and strawberry.', 8000.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4501, 27, 578, 'English breakfast', 'ac-breakfast-english-breakfast', 'Eggs (scrambled, fried, boiled, or omelette), sausage, bacon, hash brown potato, Baked beans served with sliced bread and butter.', 11500.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4502, 27, 578, 'Nigerian breakfast', 'ac-breakfast-nigerian-breakfast-2', 'Egg stew, sausage, boiled yam, and beans served with slice bread and butter.', 10500.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4503, 27, 578, 'Healthy Yogurt Bowl', 'ac-breakfast-healthy-yogurt-bowl-3', 'Fresh yogurt, an assortment of berries fruit, banana, and dried almonds served with honey.', 10500.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4504, 27, 578, 'Maple syrup', 'ac-breakfast-maple-syrup-4', '3 buttermilk pancakes, maple syrup, fruits', 7000.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4505, 27, 578, 'Waffle', 'ac-breakfast-waffle-5', 'Homemade crispy waffles, and assorted berries served with a scoop of vanilla ice cream and chocolate sauce.', 7000.00, NULL, 5, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4506, 27, 578, 'Peppered Soups', 'ac-breakfast-peppered-soups-6', 'From West Africa, chili peppers and calabash nutmeg have a spicy watery texture. A choice between chicken, goat meat fish, and Oxtail.', 7500.00, NULL, 6, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4507, 27, 579, 'Chicken Egusi', 'ac-soups-chicken-egusi', 'Creamy Egusi soup with egusi seeds, dried fish, smoked fish, kpomo, crayfish, ugu leaves served with roasted chicken.', 8000.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4508, 27, 579, 'Seafood Okro', 'ac-soups-seafood-okro-2', 'Lady finger Okro, crayfish, prawns, squid, and fish with your choice of swallow.', 13500.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4509, 27, 579, 'Beef Efo riro', 'ac-soups-beef-efo-riro-3', 'Assortment of Nigerian vegetables, crayfish, dry fish, stock fish, and kpomo served with beef meat and your choice of swallow or steamed rice.', 12000.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4510, 27, 579, 'THE SHAKER', 'ac-soups-the-shaker-4', '25pcs chicken poppers with suya spice', 8500.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4511, 27, 579, 'POP STARS', 'ac-soups-pop-stars-5', '30pcs smothered hot chicken poppers, seasoned wedges & ketchup', 9000.00, NULL, 5, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4512, 27, 580, 'Groundnut stew with beef', 'ac-beef-dishes-groundnut-stew-with-beef', 'Grilled chicken breast in African homemade spicy peanut stew served with steamed rice', 9000.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4513, 27, 580, 'Nigerian Lamb chops', 'ac-beef-dishes-nigerian-lamb-chops-2', 'Spicy grilled marinated local lamb chops served with fried plantain and yam and grilled vegetables.', 16000.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4514, 27, 580, 'Ofada rice with spicy Ofada sauce', 'ac-beef-dishes-ofada-rice-with-spicy-ofada-sauce-3', 'Shaki, kpomo, black dry fish, boiled egg served with African ofada rice, and fried plantain.', 10500.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4515, 27, 580, 'African Goat Stew', 'ac-beef-dishes-african-goat-stew-4', 'Nigerian goat meat cooked till perfection sautéed in an African spicy tomato stew served with steamed rice and fried plantain.', 10500.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4516, 27, 581, 'Poisson Braise', 'ac-seafood-dishes-poisson-braise', '(African grilled fish) Grilled fresh whole Tilapia fish marinated in our special homemade sauce African style served with fried plantain, yam, and sweet potato.', 19500.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4517, 27, 581, 'African Grilled Tiger prawns', 'ac-seafood-dishes-african-grilled-tiger-prawns-2', 'Flame-grilled Tiger prawns, marinated in spicy homemade pepper sauce served with coconut rice and yam chips.', 19500.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4518, 27, 581, 'Spicy fish African way', 'ac-seafood-dishes-spicy-fish-african-way-3', 'Fried or grilled fresh fish toasted in spicy tomato sauce served over Jolof rice, moi-moi, and fried plantain.', 8000.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4519, 27, 581, 'Jolof spaghetti with shrimp', 'ac-seafood-dishes-jolof-spaghetti-with-shrimp-4', 'Spaghetti pasta toasted in jolof spicy sauce and sautéed shrimp.', 8000.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4520, 27, 582, 'Spicy Noodles with Chicken', 'ac-chicken-dishes-spicy-noodles-with-chicken', 'Stir-fried noodles with vegetables served with roasted chicken legs', 6500.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4521, 27, 582, 'Chicken Yassa', 'ac-chicken-dishes-chicken-yassa-2', 'Chicken legs, onion, and homemade sauce served with steamed rice.', 7500.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4522, 27, 582, 'Spicy Grilled whole chicken', 'ac-chicken-dishes-spicy-grilled-whole-chicken-3', 'Grilled spicy marinated whole chicken served with Jolof rice, Nigerian fried rice, fried plantain, moi-moi, and coleslaw salad.', 18500.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4523, 27, 582, 'Turkey wings stew', 'ac-chicken-dishes-turkey-wings-stew-4', 'Fried Turkey wings toasted with homemade spicy tomato stew served with Jolof rice, fried plantain, and moi-moi.', 9500.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4524, 27, 583, 'African Burger', 'ac-sandwiches-african-burger', 'Burger bun, spicy meat patty, mayo chili sauce, tomato, onion, coleslaw served with French fries and coleslaw salad.', 8000.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4525, 27, 583, 'Vendome club', 'ac-sandwiches-vendome-club-2', 'Toasted slice American bread, choice of ham or turkey, Swiss cheese, chicken mayo mix, crispy bacon, boiled egg served with coleslaw and French fries', 11000.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4526, 27, 583, 'Suya sandwich', 'ac-sandwiches-suya-sandwich-3', 'White soft bread, beef or chicken suya slice, tomato, onion, suya powder, mayo chili sauce served with coleslaw and French fries.', 7500.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4527, 27, 583, 'Spicy steak sandwich', 'ac-sandwiches-spicy-steak-sandwich-4', 'White soft bread, sautéed beef filet, green pepper, onion, mayo sauce, mixed cheese, served with French fries.', 8000.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4528, 27, 583, 'Chili Hot Dog', 'ac-sandwiches-chili-hot-dog-5', 'Soft bread, chicken frankfurter, chili con carne, jalapeno topped with cheddar cheese', 9800.00, NULL, 5, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4529, 27, 584, 'Jolof rice', 'ac-side-orders-jolof-rice', NULL, 4000.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4530, 27, 584, 'Coconut rice', 'ac-side-orders-coconut-rice-2', NULL, 4000.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4531, 27, 584, 'Nigerian fried rice', 'ac-side-orders-nigerian-fried-rice-3', NULL, 4000.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4532, 27, 584, 'Steamed rice', 'ac-side-orders-steamed-rice-4', NULL, 4000.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4533, 27, 584, 'Fried yam', 'ac-side-orders-fried-yam-5', NULL, 4000.00, NULL, 5, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4534, 27, 584, 'Fried plantain', 'ac-side-orders-fried-plantain-6', NULL, 4000.00, NULL, 6, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4535, 27, 584, 'Moi-Moi', 'ac-side-orders-moi-moi-7', NULL, 4000.00, NULL, 7, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4536, 27, 584, 'French fries', 'ac-side-orders-french-fries-8', NULL, 4000.00, NULL, 8, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4537, 27, 584, 'Coleslaw salad', 'ac-side-orders-coleslaw-salad-9', NULL, 4000.00, NULL, 9, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4538, 27, 584, 'Fried rice', 'ac-side-orders-fried-rice-10', NULL, 4000.00, NULL, 10, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4539, 27, 584, 'Mashed potato', 'ac-side-orders-mashed-potato-11', NULL, 4000.00, NULL, 11, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4540, 27, 584, 'Sautéed potato', 'ac-side-orders-saut-ed-potato-12', NULL, 4000.00, NULL, 12, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4541, 27, 585, 'Eba', 'ac-swallows-eba', NULL, 800.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4542, 27, 585, 'Pounded yam', 'ac-swallows-pounded-yam-2', NULL, 900.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4543, 27, 585, 'Semolina', 'ac-swallows-semolina-3', NULL, 800.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4544, 27, 585, 'Wheat', 'ac-swallows-wheat-4', NULL, 800.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4545, 27, 585, 'EGGS', 'ac-swallows-eggs-5', 'Eggs are served with fresh vegetables bread and butter', 5500.00, NULL, 5, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4546, 27, 585, 'Classic Benedict', 'ac-swallows-classic-benedict-6', 'Seved with hollandaise sauce Just omelets Sunnyside Scrambled', 1500.00, NULL, 6, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4547, 27, 586, 'Mable syrup', 'ac-pancakes-mable-syrup', '3 buttermilk pancakes, maple syrup, fruits', 6500.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4548, 27, 586, 'Chocolate banana', 'ac-pancakes-chocolate-banana-2', '3 buttermilk pancakes, Nutella chocolate spread, banana, roasted hazelnuts', 6500.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4549, 27, 586, 'Breakfast sandwich', 'ac-pancakes-breakfast-sandwich-3', '3 buttermilk pancake, sausage, cheddar cheese, bacon, scrambled eggs', 7000.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4550, 27, 586, 'Oreo cheesecake milkshake', 'ac-pancakes-oreo-cheesecake-milkshake-4', NULL, 9500.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4551, 27, 587, 'Plain', 'ac-waffles-plain', 'Crispy waffle dusted with icing sugar', 3900.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4552, 27, 587, 'Berries and vanilla ice cream', 'ac-waffles-berries-and-vanilla-ice-cream-2', 'Homemade crispy waffles, and assorted berries served with a scoop of vanilla ice cream and chocolate sauce', 7000.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4553, 27, 587, 'Chocolate banana', 'ac-waffles-chocolate-banana-3', 'Homemade crispy waffle, banana, Nutella chocolate, wept cream', 7000.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4554, 27, 587, 'Chicken and waffle sandwich', 'ac-waffles-chicken-and-waffle-sandwich-4', 'Homemade crispy waffles topped with crispy fried chicken breast served with our secret sauce', 7500.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4555, 27, 587, 'Waffle and egg', 'ac-waffles-waffle-and-egg-5', 'Homemade crispy waffle topped with scrambled eggs', 5500.00, NULL, 5, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4556, 27, 588, 'Meat pie', 'ac-nigerian-pastries-meat-pie', NULL, 2500.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4557, 27, 588, 'Chicken pie', 'ac-nigerian-pastries-chicken-pie-2', NULL, 2500.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4558, 27, 588, 'Sausage rolls', 'ac-nigerian-pastries-sausage-rolls-3', NULL, 2500.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4559, 27, 589, 'Cold chicken and mayo', 'ac-cold-sandwiches-cold-chicken-and-mayo', 'Soft white bread, chicken mayo mix, tomato, crispy lettuce, homemade pickles', 6000.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4560, 27, 589, 'Tuna salad', 'ac-cold-sandwiches-tuna-salad-2', 'Soft white bread, tuna mix, sweet corn, crispy lettuce, tomato, homemade pickles', 7500.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4561, 27, 589, 'Sausage rolls', 'ac-cold-sandwiches-sausage-rolls-3', NULL, 2500.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4562, 27, 590, 'Ham and Swiss cheese', 'ac-panini-ham-and-swiss-cheese', 'White soft bread, butter, ham slice, Swiss cheese', 8000.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4563, 27, 590, 'Turkey bacon and Swiss cheese', 'ac-panini-turkey-bacon-and-swiss-cheese-2', 'White soft bread, turkey slice, Swiss cheese, mayo sauce, tomato, crispy lettuce, homemade pickles', 8000.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4564, 27, 590, 'Grilled chicken mozzarella', 'ac-panini-grilled-chicken-mozzarella-3', 'Soft white bread, grilled marinated chicken breast, pesto sauce, mozzarella cheese, tomato', 8000.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4565, 27, 590, 'Vegetarian', 'ac-panini-vegetarian-4', 'Soft white bread, eggplant, zucchini, carrot, tomato, onion, mushrooms topped with balsamic vinegar dressing', 8000.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4566, 27, 591, 'Fajita wraps', 'ac-hot-sandwiches-fajita-wraps', 'Tortilla bread, chicken slice marinated, green pepper, onion, mixed cheese, guacamole served with salsa sauce and French fries', 8500.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4567, 27, 591, 'Crispy chicken wrap', 'ac-hot-sandwiches-crispy-chicken-wrap-2', 'Homemade bread wrap, crispy fried chicken bread, tomato, red onion, tomato sauce, blue cheese, ranch sauce, served with French fries', 9500.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4568, 27, 591, 'Grilled Chicken', 'ac-hot-sandwiches-grilled-chicken-3', 'Soft bun, grilled chicken breast, cheddar cheese, garlic mayo, BBQ sauce, sliced tomato, sliced red onion, homemade pickles, crispy lettuce', 9500.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4569, 27, 591, 'Classic Cheese', 'ac-hot-sandwiches-classic-cheese-4', 'Soft bun, two 170 gr of beef patty, double cheddar cheese, special sauce, ketchup, slice tomato, slice red onion, homemade pickles, crispy lettuce', 9500.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4570, 27, 591, 'Crispy Fish', 'ac-hot-sandwiches-crispy-fish-5', 'Soft bun, crispy fried white fish filet, cheddar cheese, tartar sauce, slice of tomato, crispy lettuce', 14000.00, NULL, 5, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4571, 27, 591, 'After midnight', 'ac-hot-sandwiches-after-midnight-6', 'Soft bun, 170 gr of beef patty, cheddar cheese, fried mozzarella cheese, crispy bacon, special sauce, slice tomato, slice red onion, crispy lettuce', 15000.00, NULL, 6, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4572, 27, 591, 'Bacon', 'ac-hot-sandwiches-bacon-7', NULL, 2500.00, NULL, 7, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4573, 27, 591, 'Cheddar cheese', 'ac-hot-sandwiches-cheddar-cheese-8', 'Homemade bread wrap, crispy fried chicken bread, tomato, red onion, tomato sauce, blue cheese, ranch sauce, served with French fries', 9500.00, NULL, 8, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4574, 27, 591, 'Grilled Chicken', 'ac-hot-sandwiches-grilled-chicken-9', 'Soft bun, grilled chicken breast, cheddar cheese, garlic mayo, BBQ sauce, sliced tomato, sliced red onion, homemade pickles, crispy lettuce', 9500.00, NULL, 9, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05');
INSERT INTO `menu_items` (`id`, `restaurant_id`, `category_id`, `name`, `slug`, `description`, `price`, `image`, `display_order`, `is_available`, `created_at`, `updated_at`) VALUES
(4575, 27, 591, 'Classic Cheese', 'ac-hot-sandwiches-classic-cheese-10', 'Soft bun, two 170 gr of beef patty, double cheddar cheese, special sauce, ketchup, slice tomato, slice red onion, homemade pickles, crispy lettuce', 9500.00, NULL, 10, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4576, 27, 591, 'Crispy Fish', 'ac-hot-sandwiches-crispy-fish-11', 'Soft bun, crispy fried white fish filet, cheddar cheese, tartar sauce, slice of tomato, crispy lettuce', 14000.00, NULL, 11, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4577, 27, 591, 'After midnight', 'ac-hot-sandwiches-after-midnight-12', 'Soft bun, 170 gr of beef patty, cheddar cheese, fried mozzarella cheese, crispy bacon, special sauce, slice tomato, slice red onion, crispy lettuce', 15000.00, NULL, 12, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4578, 27, 591, 'Bacon', 'ac-hot-sandwiches-bacon-13', NULL, 2500.00, NULL, 13, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4579, 27, 591, 'Cheddar cheese', 'ac-hot-sandwiches-cheddar-cheese-14', NULL, 1000.00, NULL, 14, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4580, 27, 591, 'Fried egg', 'ac-hot-sandwiches-fried-egg-15', NULL, 1000.00, NULL, 15, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4581, 27, 591, 'Ham or turkey slice', 'ac-hot-sandwiches-ham-or-turkey-slice-16', NULL, 1000.00, NULL, 16, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4582, 27, 592, 'Nigerian peppered snails', 'ac-appetizers-nigerian-peppered-snails', 'Tender African snails toasted with homemade chili pepper sauce.', 11000.00, NULL, 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4583, 27, 592, 'Assortment of peppered meat', 'ac-appetizers-assortment-of-peppered-meat-2', 'Gizzard, chicken, beef, snails, and kpomo toasted with homemade chili sauce served with fresh tomato and red onion.', 15500.00, NULL, 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4584, 27, 592, 'Peppered Shrimps', 'ac-appetizers-peppered-shrimps-3', 'Shrimp toasted with homemade chili pepper sauce.', 11500.00, NULL, 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4585, 27, 592, 'Peppered Kpomo', 'ac-appetizers-peppered-kpomo-4', 'Cow skin mixed with hot and spicy peppers.', 4000.00, NULL, 4, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4586, 27, 592, 'Fried peppered chicken tender', 'ac-appetizers-fried-peppered-chicken-tender-5', 'Deep-fried breaded chicken tender, toasted with African spicy sauce.', 8500.00, NULL, 5, 1, '2026-05-19 13:18:05', '2026-05-19 13:18:05'),
(4587, 27, 592, 'Chicken Nigerian spring rolls', 'ac-appetizers-chicken-nigerian-spring-rolls-6', '4 deep-fried spring rolls filled with shrimp and vegetables Nigerian style.', 8000.00, NULL, 6, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4588, 27, 592, 'Peppered fried chicken wings', 'ac-appetizers-peppered-fried-chicken-wings-7', 'Deep-fried chicken wings toasted in homemade chili pepper sauce', 7500.00, NULL, 7, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4589, 27, 592, 'Popcorn Gizzard', 'ac-appetizers-popcorn-gizzard-8', 'Deep-fried breaded Gizzard served with homemade chili sauce', 7500.00, NULL, 8, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4590, 27, 592, 'Fried spring rolls', 'ac-appetizers-fried-spring-rolls-9', '4 Deep fried chicken rolls, served with sweet chili sauce', 8000.00, NULL, 9, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4591, 27, 592, 'Garlic lemon Octopus', 'ac-appetizers-garlic-lemon-octopus-10', 'Sauteed octopus in a garlic coriander sauce, topped with fresh lemon juice, served with bread', 9500.00, NULL, 10, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4592, 27, 592, 'Chicken or beef quesadillas', 'ac-appetizers-chicken-or-beef-quesadillas-11', 'Toasted tortilla bread filled with your choice of chicken or beef, green pepper, onion, mixed cheese, served with, guacamole, salsa, and sour cream', 10000.00, NULL, 11, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4593, 27, 592, 'Fried chicken wings', 'ac-appetizers-fried-chicken-wings-12', 'Deep-fried chicken wings served with homemade hot sauce', 8000.00, NULL, 12, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4594, 27, 592, 'Mozzarella sticks', 'ac-appetizers-mozzarella-sticks-13', '5 Breaded mozzarella cheese, deep fried served with tomato sauce', 7500.00, NULL, 13, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4595, 27, 592, 'Popcorn shrimps', 'ac-appetizers-popcorn-shrimps-14', 'Breaded shrimp, deep fried served with tartar sauce', 7500.00, NULL, 14, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4596, 27, 592, 'Loaded French fries', 'ac-appetizers-loaded-french-fries-15', 'French fries topped with chili con carne, cheddar cheese, jalapeño served with sour cream', 7500.00, NULL, 15, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4597, 27, 592, 'Hummus', 'ac-appetizers-hummus-16', 'Purred chickpeas in tahini sauce, and olive oil, served with fresh vegetables and pita bread', 7000.00, NULL, 16, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4598, 27, 593, 'Chicken Macaroni', 'ac-salads-chicken-macaroni', 'Macaroni elbow, carrots, green pepper, tatashe, and red onion toasted with mayonnaise sauce topped with grilled chicken breast and boiled eggs.', 7000.00, NULL, 1, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4599, 27, 593, 'Classic Chicken Caesar', 'ac-salads-classic-chicken-caesar-2', 'Romain lettuce, parmesan cheese, garlic crouton, grilled chicken breast toasted in our homemade Caesar dressing', 9500.00, NULL, 2, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4600, 27, 593, 'Spaghetti Bolognese', 'ac-salads-spaghetti-bolognese-3', 'Spaghetti pasta, Bolognese sauce, served with parmesan cheese and bread', 9500.00, NULL, 3, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4601, 27, 593, 'Penne al pesto', 'ac-salads-penne-al-pesto-4', 'Penne pasta toasted in homemade pesto sauce served with parmesan cheese and bread', 9500.00, NULL, 4, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4602, 27, 593, 'Chicken escalope', 'ac-salads-chicken-escalope-5', 'Deep-fried breaded chicken breast and sweet corn served with French fries coleslaw salad, and honey mustard sauce', 9000.00, NULL, 5, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4603, 27, 593, 'Steak frites', 'ac-salads-steak-frites-6', 'Grilled Imported beef filet served with French fries and sautéed vegetables', 18800.00, NULL, 6, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4604, 27, 593, 'Fisher main dish', 'ac-salads-fisher-main-dish-7', 'Fried fish filet, grilled prawns, grilled calamari, and sautéed octopus served with French fries coleslaw salad, and tartar sauce', 19500.00, NULL, 7, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4605, 27, 593, 'Oriental Grilled platter', 'ac-salads-oriental-grilled-platter-8', 'Grilled skewers of chicken kebab beef kebab served with grilled onion, grilled tomato spicy pita bread, garlic sauce, and hummus dip', 15000.00, NULL, 8, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4606, 27, 593, 'Red Curry with prawns', 'ac-salads-red-curry-with-prawns-9', 'Homemade Red curry sauce, prawns, green beans, potato, eggplant, served with steamed rice', 12500.00, NULL, 9, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4607, 27, 594, 'Ice cream scoop', 'ac-dessert-ice-cream-scoop', NULL, 3000.00, NULL, 1, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4608, 27, 594, 'Tropical fruit salad', 'ac-dessert-tropical-fruit-salad-2', NULL, 2500.00, NULL, 2, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4609, 27, 595, 'Bille cart blanc de blanc', 'tb-champagne-bille-cart-blanc-de-blanc', NULL, 200000.00, NULL, 1, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4610, 27, 595, 'Bille cart salmon brut rose', 'tb-champagne-bille-cart-salmon-brut-rose-2', NULL, 280000.00, NULL, 2, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4611, 27, 595, 'Bille cart salmon demi sec', 'tb-champagne-bille-cart-salmon-demi-sec-3', NULL, 175000.00, NULL, 3, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4612, 27, 595, 'Dom Perignon Brut', 'tb-champagne-dom-perignon-brut-4', NULL, 800000.00, NULL, 4, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4613, 27, 595, 'Dom Perignon rose', 'tb-champagne-dom-perignon-rose-5', NULL, 1150000.00, NULL, 5, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4614, 27, 595, 'Laurent Perrier brut', 'tb-champagne-laurent-perrier-brut-6', NULL, 180000.00, NULL, 6, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4615, 27, 595, 'Laurent Perrier Cuvee rose', 'tb-champagne-laurent-perrier-cuvee-rose-7', NULL, 280000.00, NULL, 7, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4616, 27, 595, 'Moet brut', 'tb-champagne-moet-brut-8', NULL, 240000.00, NULL, 8, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4617, 27, 595, 'Moet chandon imperial rose', 'tb-champagne-moet-chandon-imperial-rose-9', NULL, 260000.00, NULL, 9, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4618, 27, 595, 'Moet imperial ice', 'tb-champagne-moet-imperial-ice-10', NULL, 300000.00, NULL, 10, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4619, 27, 595, 'Moet nectar rose', 'tb-champagne-moet-nectar-rose-11', NULL, 280000.00, NULL, 11, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4620, 27, 595, 'Ruinart blanc de blanc', 'tb-champagne-ruinart-blanc-de-blanc-12', NULL, 320000.00, NULL, 12, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4621, 27, 595, 'Ruinart brut', 'tb-champagne-ruinart-brut-13', NULL, 210000.00, NULL, 13, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4622, 27, 595, 'Veuve Cliquot Brut', 'tb-champagne-veuve-cliquot-brut-14', NULL, 250000.00, NULL, 14, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4623, 27, 595, 'Veuve Cliquot Rich', 'tb-champagne-veuve-cliquot-rich-15', NULL, 300000.00, NULL, 15, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4624, 27, 596, 'Casamigo', 'tb-tequila-casamigo', NULL, 300000.00, NULL, 1, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4625, 27, 596, 'Clase azul rapasado', 'tb-tequila-clase-azul-rapasado-2', NULL, 720000.00, NULL, 2, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4626, 27, 596, 'Claze azul plata jalisco', 'tb-tequila-claze-azul-plata-jalisco-3', NULL, 420000.00, NULL, 3, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4627, 27, 596, 'Don Julio 1942', 'tb-tequila-don-julio-1942-4', NULL, 720000.00, NULL, 4, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4628, 27, 596, 'Don Julio Reposado', 'tb-tequila-don-julio-reposado-5', NULL, 450000.00, NULL, 5, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4629, 27, 596, 'Volcan anejo', 'tb-tequila-volcan-anejo-6', NULL, 330000.00, NULL, 6, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4630, 27, 596, 'Vivir tequila Reposado', 'tb-tequila-vivir-tequila-reposado-7', 'spicy goat rice mix', 190000.00, NULL, 7, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4631, 27, 596, 'Vivir Tequila Blanco', 'tb-tequila-vivir-tequila-blanco-8', NULL, 170000.00, NULL, 8, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4632, 27, 597, 'Hennessy VSOP', 'tb-cognac-hennessy-vsop', NULL, 270000.00, NULL, 1, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4633, 27, 597, 'Hennessy X.O', 'tb-cognac-hennessy-x-o-2', NULL, 700000.00, NULL, 2, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4634, 27, 597, 'Martel Bleu Swift', 'tb-cognac-martel-bleu-swift-3', NULL, 230000.00, NULL, 3, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4635, 27, 597, 'Remy Martin', 'tb-cognac-remy-martin-4', NULL, 195000.00, NULL, 4, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4636, 27, 597, 'Tesseron xo tradition', 'tb-cognac-tesseron-xo-tradition-5', NULL, 320000.00, NULL, 5, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4637, 27, 597, 'Tesseron xo ovation', 'tb-cognac-tesseron-xo-ovation-6', NULL, 420000.00, NULL, 6, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4638, 27, 597, 'Tesseron xo perfection', 'tb-cognac-tesseron-xo-perfection-7', NULL, 580000.00, NULL, 7, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4639, 27, 597, 'Sainte croix xo', 'tb-cognac-sainte-croix-xo-8', NULL, 280000.00, NULL, 8, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4640, 27, 598, 'Beluga gold line', 'tb-vodka-beluga-gold-line', NULL, 100000.00, NULL, 1, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4641, 27, 598, 'Belverde', 'tb-vodka-belverde-2', NULL, 150000.00, NULL, 2, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4642, 27, 598, 'Grey Goose', 'tb-vodka-grey-goose-3', NULL, 130000.00, NULL, 3, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4643, 27, 599, 'Gin mare', 'tb-gin-gin-mare', NULL, 115000.00, NULL, 1, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4644, 27, 599, 'Hendricks', 'tb-gin-hendricks-2', NULL, 120000.00, NULL, 2, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4645, 27, 600, 'Baccardi White', 'tb-rum-baccardi-white', NULL, 60000.00, NULL, 1, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4646, 27, 600, 'Hendricks', 'tb-rum-hendricks-2', NULL, 800000.00, NULL, 2, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4647, 27, 601, 'Chivas 15', 'tb-whisky-chivas-15', NULL, 125000.00, NULL, 1, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4648, 27, 601, 'Chivas 18', 'tb-whisky-chivas-18-2', NULL, 170000.00, NULL, 2, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4649, 27, 601, 'Glenfiddich 12', 'tb-whisky-glenfiddich-12-3', NULL, 140000.00, NULL, 3, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4650, 27, 601, 'Glenfiddich 15', 'tb-whisky-glenfiddich-15-4', NULL, 200000.00, NULL, 4, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4651, 27, 601, 'Glenfiddich 18', 'tb-whisky-glenfiddich-18-5', NULL, 290000.00, NULL, 5, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4652, 27, 601, 'Glenfiddich 21', 'tb-whisky-glenfiddich-21-6', NULL, 600000.00, NULL, 6, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4653, 27, 601, 'Glenmorangie 10 original', 'tb-whisky-glenmorangie-10-original-7', NULL, 150000.00, NULL, 7, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4654, 27, 601, 'Glenmorangie 18 yrs. extreme', 'tb-whisky-glenmorangie-18-yrs-extreme-8', NULL, 300000.00, NULL, 8, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4655, 27, 601, 'Glenmorangie Signet', 'tb-whisky-glenmorangie-signet-9', NULL, 600000.00, NULL, 9, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4656, 27, 601, 'Jameson Black Barre', 'tb-whisky-jameson-black-barre-10', NULL, 130000.00, NULL, 10, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4657, 27, 601, 'Johnny walker blue label', 'tb-whisky-johnny-walker-blue-label-11', NULL, 560000.00, NULL, 11, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4658, 27, 601, 'Macallan double', 'tb-whisky-macallan-double-12', NULL, 495000.00, NULL, 12, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4659, 27, 601, 'Macallan rare cast', 'tb-whisky-macallan-rare-cast-13', NULL, 410000.00, NULL, 13, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4660, 27, 602, 'Heineken', 'tb-beer-heineken', NULL, 5000.00, NULL, 1, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4661, 27, 602, 'Guinness', 'tb-beer-guinness-2', NULL, 5000.00, NULL, 2, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4662, 27, 603, 'Water Large', 'tb-non-alcohol-drinks-water-large', NULL, 3500.00, NULL, 1, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4663, 27, 603, 'Water small', 'tb-non-alcohol-drinks-water-small-2', NULL, 1600.00, NULL, 2, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4664, 27, 603, 'Coke', 'tb-non-alcohol-drinks-coke-3', NULL, 1800.00, NULL, 3, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4665, 27, 603, 'Fanta', 'tb-non-alcohol-drinks-fanta-4', NULL, 1800.00, NULL, 4, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4666, 27, 603, 'Sprite', 'tb-non-alcohol-drinks-sprite-5', NULL, 1800.00, NULL, 5, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4667, 27, 603, 'Ginger ale', 'tb-non-alcohol-drinks-ginger-ale-6', NULL, 4500.00, NULL, 6, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4668, 27, 603, 'Perrier', 'tb-non-alcohol-drinks-perrier-7', NULL, 4500.00, NULL, 7, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4669, 27, 603, 'Power Horse', 'tb-non-alcohol-drinks-power-horse-8', NULL, 4800.00, NULL, 8, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4670, 27, 603, 'Red bull', 'tb-non-alcohol-drinks-red-bull-9', NULL, 5000.00, NULL, 9, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4671, 27, 603, 'Soda', 'tb-non-alcohol-drinks-soda-10', NULL, 2000.00, NULL, 10, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4672, 27, 603, 'Tonic', 'tb-non-alcohol-drinks-tonic-11', NULL, 2000.00, NULL, 11, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4673, 27, 604, 'Cranberry Picher', 'tb-juice-pitcher-cranberry-picher', NULL, 18000.00, NULL, 1, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4674, 27, 604, 'Orange', 'tb-juice-pitcher-orange-2', NULL, 18000.00, NULL, 2, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4675, 27, 604, 'Apple', 'tb-juice-pitcher-apple-3', NULL, 8500.00, NULL, 3, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4676, 27, 604, 'Pineapple', 'tb-juice-pitcher-pineapple-4', NULL, 8500.00, NULL, 4, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4677, 27, 605, 'Single espresso', 'tb-hot-beverages-single-espresso', NULL, 4500.00, NULL, 1, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4678, 27, 605, 'Double espresso', 'tb-hot-beverages-double-espresso-2', NULL, 5500.00, NULL, 2, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4679, 27, 605, 'Tea selection', 'tb-hot-beverages-tea-selection-3', NULL, 4600.00, NULL, 3, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4680, 27, 605, 'Cappuccino', 'tb-hot-beverages-cappuccino-4', NULL, 5400.00, NULL, 4, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4681, 27, 605, 'Americano', 'tb-hot-beverages-americano-5', NULL, 5600.00, NULL, 5, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4682, 27, 605, 'Café late', 'tb-hot-beverages-caf-late-6', NULL, 4600.00, NULL, 6, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4683, 27, 606, 'Mojito', 'tb-classic-cocktails-mojito', NULL, 11500.00, NULL, 1, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4684, 27, 606, 'Flavored Mojito', 'tb-classic-cocktails-flavored-mojito-2', NULL, 11500.00, NULL, 2, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4685, 27, 606, 'Tequila sunrise', 'tb-classic-cocktails-tequila-sunrise-3', NULL, 11500.00, NULL, 3, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4686, 27, 606, 'Whisky sour', 'tb-classic-cocktails-whisky-sour-4', NULL, 11500.00, NULL, 4, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4687, 27, 606, 'Strawberry Daiquiri', 'tb-classic-cocktails-strawberry-daiquiri-5', NULL, 11500.00, NULL, 5, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4688, 27, 606, 'Gin Tonic', 'tb-classic-cocktails-gin-tonic-6', NULL, 11500.00, NULL, 6, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4689, 27, 606, 'Moscow mule', 'tb-classic-cocktails-moscow-mule-7', NULL, 11500.00, NULL, 7, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4690, 27, 606, 'Basilicum', 'tb-classic-cocktails-basilicum-8', NULL, 11500.00, NULL, 8, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4691, 27, 606, 'Long island', 'tb-classic-cocktails-long-island-9', NULL, 11500.00, NULL, 9, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4692, 27, 606, 'Pinacolada', 'tb-classic-cocktails-pinacolada-10', NULL, 11500.00, NULL, 10, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4693, 27, 606, 'Alexander', 'tb-classic-cocktails-alexander-11', NULL, 11500.00, NULL, 11, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4694, 27, 606, 'Amaretto whiskey sour', 'tb-classic-cocktails-amaretto-whiskey-sour-12', NULL, 11500.00, NULL, 12, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4695, 27, 606, 'Cosmopolitan', 'tb-classic-cocktails-cosmopolitan-13', NULL, 11500.00, NULL, 13, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4696, 27, 606, 'Manhattan', 'tb-classic-cocktails-manhattan-14', NULL, 11500.00, NULL, 14, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4697, 27, 606, 'Margarita', 'tb-classic-cocktails-margarita-15', NULL, 11500.00, NULL, 15, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4698, 27, 606, 'Flavored Margarita', 'tb-classic-cocktails-flavored-margarita-16', NULL, 11500.00, NULL, 16, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4699, 27, 606, 'Sex on the beach', 'tb-classic-cocktails-sex-on-the-beach-17', NULL, 11500.00, NULL, 17, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4700, 27, 606, 'Porn star Martini', 'tb-classic-cocktails-porn-star-martini-18', NULL, 11500.00, NULL, 18, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4701, 27, 606, 'Vodka sour', 'tb-classic-cocktails-vodka-sour-19', NULL, 11500.00, NULL, 19, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4702, 27, 606, 'Stinger', 'tb-classic-cocktails-stinger-20', NULL, 11500.00, NULL, 20, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4703, 27, 607, '1840 Original', 'tb-signature-cocktails-1840-original', NULL, 12500.00, NULL, 1, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4704, 27, 607, 'Lichee Martini', 'tb-signature-cocktails-lichee-martini-2', NULL, 12500.00, NULL, 2, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4705, 27, 607, 'Vendome Caiparian', 'tb-signature-cocktails-vendome-caiparian-3', NULL, 12500.00, NULL, 3, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4706, 27, 607, 'Vendome pepper dem', 'tb-signature-cocktails-vendome-pepper-dem-4', NULL, 12500.00, NULL, 4, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4707, 27, 607, 'Ice fruity', 'tb-signature-cocktails-ice-fruity-5', NULL, 12500.00, NULL, 5, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4708, 27, 607, 'Peach and horny Rita', 'tb-signature-cocktails-peach-and-horny-rita-6', NULL, 12500.00, NULL, 6, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4709, 27, 607, 'Fallen olde fashioned', 'tb-signature-cocktails-fallen-olde-fashioned-7', NULL, 12500.00, NULL, 7, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4710, 27, 607, 'Green Latern', 'tb-signature-cocktails-green-latern-8', NULL, 12500.00, NULL, 8, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4711, 27, 607, 'Londinum', 'tb-signature-cocktails-londinum-9', NULL, 12500.00, NULL, 9, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4712, 27, 607, 'Ocean wave', 'tb-signature-cocktails-ocean-wave-10', NULL, 12500.00, NULL, 10, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4713, 27, 608, 'Tequila', 'tb-shots-tequila', NULL, 4000.00, NULL, 1, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4714, 27, 608, 'Tequila Gold', 'tb-shots-tequila-gold-2', NULL, 4500.00, NULL, 2, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4715, 27, 608, 'Vodka', 'tb-shots-vodka-3', NULL, 4000.00, NULL, 3, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4716, 27, 608, 'Gin', 'tb-shots-gin-4', NULL, 4000.00, NULL, 4, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4717, 27, 608, 'Whiskey', 'tb-shots-whiskey-5', NULL, 4000.00, NULL, 5, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4718, 27, 608, 'Hennessey VSOP', 'tb-shots-hennessey-vsop-6', NULL, 5000.00, NULL, 6, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4719, 27, 608, 'Rum', 'tb-shots-rum-7', NULL, 4000.00, NULL, 7, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4720, 27, 608, 'Jägermeister', 'tb-shots-j-germeister-8', NULL, 4000.00, NULL, 8, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4721, 27, 608, 'Londinum', 'tb-shots-londinum-9', NULL, 12500.00, NULL, 9, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4722, 27, 608, 'Baileys', 'tb-shots-baileys-10', NULL, 3500.00, NULL, 10, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4723, 27, 608, 'Campari', 'tb-shots-campari-11', NULL, 3500.00, NULL, 11, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4724, 27, 608, 'Aperol', 'tb-shots-aperol-12', NULL, 3500.00, NULL, 12, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4725, 27, 609, 'Brain hemorrhage', 'tb-special-shots-brain-hemorrhage', NULL, 5600.00, NULL, 1, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4726, 27, 609, 'Condom shot', 'tb-special-shots-condom-shot-2', NULL, 5600.00, NULL, 2, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4727, 27, 609, 'Death of jellyfish', 'tb-special-shots-death-of-jellyfish-3', NULL, 5600.00, NULL, 3, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4728, 27, 609, 'Doo Doo', 'tb-special-shots-doo-doo-4', NULL, 5600.00, NULL, 4, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4729, 27, 609, 'Liquid cocaine', 'tb-special-shots-liquid-cocaine-5', NULL, 5600.00, NULL, 5, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4730, 27, 609, 'Absent without leave', 'tb-special-shots-absent-without-leave-6', NULL, 5600.00, NULL, 6, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4731, 27, 609, 'Fire shots', 'tb-special-shots-fire-shots-7', NULL, 5600.00, NULL, 7, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4732, 27, 609, 'Vendome shot', 'tb-special-shots-vendome-shot-8', NULL, 5600.00, NULL, 8, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4733, 27, 609, 'Blowjob', 'tb-special-shots-blowjob-9', NULL, 5600.00, NULL, 9, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4734, 27, 609, 'Vendome tower fire show', 'tb-special-shots-vendome-tower-fire-show-10', NULL, 25000.00, NULL, 10, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4735, 27, 609, 'Iced Gum', 'tb-special-shots-iced-gum-11', NULL, 19000.00, NULL, 11, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4736, 27, 609, 'Magic Love', 'tb-special-shots-magic-love-12', NULL, 19000.00, NULL, 12, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4737, 27, 609, 'Love', 'tb-special-shots-love-13', NULL, 19000.00, NULL, 13, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4738, 27, 609, 'Strawberry', 'tb-special-shots-strawberry-14', NULL, 19000.00, NULL, 14, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4739, 27, 609, 'Strawberry and Mint', 'tb-special-shots-strawberry-and-mint-15', NULL, 19000.00, NULL, 15, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4740, 27, 609, 'Mixed Fruit', 'tb-special-shots-mixed-fruit-16', NULL, 19000.00, NULL, 16, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4741, 27, 609, 'Gum and Mint', 'tb-special-shots-gum-and-mint-17', NULL, 19000.00, NULL, 17, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4742, 27, 609, 'Gum', 'tb-special-shots-gum-18', NULL, 19000.00, NULL, 18, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4743, 27, 609, 'Lemon and Mint', 'tb-special-shots-lemon-and-mint-19', NULL, 19000.00, NULL, 19, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4744, 27, 609, 'Mint and Cream', 'tb-special-shots-mint-and-cream-20', NULL, 19000.00, NULL, 20, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4745, 27, 609, 'Grape and Mint', 'tb-special-shots-grape-and-mint-21', NULL, 19000.00, NULL, 21, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4746, 27, 609, 'Grape', 'tb-special-shots-grape-22', NULL, 19000.00, NULL, 22, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4747, 27, 609, 'Two Apple', 'tb-special-shots-two-apple-23', NULL, 19000.00, NULL, 23, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4748, 27, 609, 'Mint', 'tb-special-shots-mint-24', NULL, 19000.00, NULL, 24, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4749, 27, 609, 'Peach', 'tb-special-shots-peach-25', NULL, 19000.00, NULL, 25, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4750, 27, 609, 'Blueberry', 'tb-special-shots-blueberry-26', NULL, 19000.00, NULL, 26, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4751, 27, 609, 'Blueberry and Mint', 'tb-special-shots-blueberry-and-mint-27', NULL, 19000.00, NULL, 27, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4752, 27, 609, 'Mango', 'tb-special-shots-mango-28', NULL, 19000.00, NULL, 28, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4753, 27, 609, 'Watermelon', 'tb-special-shots-watermelon-29', NULL, 19000.00, NULL, 29, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4754, 27, 609, 'Watermelon and Mint', 'tb-special-shots-watermelon-and-mint-30', NULL, 19000.00, NULL, 30, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4755, 27, 609, 'Lady Killer', 'tb-special-shots-lady-killer-31', NULL, 19000.00, NULL, 31, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4756, 27, 609, 'Two Apple', 'tb-special-shots-two-apple-32', NULL, 19000.00, NULL, 32, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4757, 27, 609, 'Apple', 'tb-special-shots-apple-33', NULL, 19000.00, NULL, 33, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4758, 27, 609, 'Iced Gum', 'tb-special-shots-iced-gum-34', NULL, 19000.00, NULL, 34, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4759, 27, 609, 'Magic Love', 'tb-special-shots-magic-love-35', NULL, 19000.00, NULL, 35, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4760, 27, 609, 'Love', 'tb-special-shots-love-36', NULL, 19000.00, NULL, 36, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4761, 27, 609, 'Strawberry', 'tb-special-shots-strawberry-37', NULL, 19000.00, NULL, 37, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4762, 27, 609, 'Strawberry and Mint', 'tb-special-shots-strawberry-and-mint-38', NULL, 19000.00, NULL, 38, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4763, 27, 609, 'Mixed Fruit', 'tb-special-shots-mixed-fruit-39', NULL, 19000.00, NULL, 39, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4764, 27, 609, 'Gum and Mint', 'tb-special-shots-gum-and-mint-40', NULL, 19000.00, NULL, 40, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4765, 27, 609, 'Gum', 'tb-special-shots-gum-41', NULL, 19000.00, NULL, 41, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4766, 27, 609, 'Lemon and Mint', 'tb-special-shots-lemon-and-mint-42', NULL, 19000.00, NULL, 42, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4767, 27, 609, 'Mint and Cream', 'tb-special-shots-mint-and-cream-43', NULL, 19000.00, NULL, 43, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4768, 27, 609, 'Grape and Mint', 'tb-special-shots-grape-and-mint-44', NULL, 19000.00, NULL, 44, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4769, 27, 609, 'Grape', 'tb-special-shots-grape-45', NULL, 19000.00, NULL, 45, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4770, 27, 609, 'Two Apple', 'tb-special-shots-two-apple-46', NULL, 19000.00, NULL, 46, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4771, 27, 609, 'Mint', 'tb-special-shots-mint-47', NULL, 19000.00, NULL, 47, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4772, 27, 609, 'Peach', 'tb-special-shots-peach-48', NULL, 19000.00, NULL, 48, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4773, 27, 609, 'Blueberry', 'tb-special-shots-blueberry-49', NULL, 19000.00, NULL, 49, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4774, 27, 609, 'Blueberry and Mint', 'tb-special-shots-blueberry-and-mint-50', NULL, 19000.00, NULL, 50, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4775, 27, 609, 'Mango', 'tb-special-shots-mango-51', NULL, 19000.00, NULL, 51, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4776, 27, 609, 'Watermelon', 'tb-special-shots-watermelon-52', NULL, 19000.00, NULL, 52, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4777, 27, 609, 'Watermelon and Mint', 'tb-special-shots-watermelon-and-mint-53', NULL, 19000.00, NULL, 53, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4778, 27, 609, 'Lady Killer', 'tb-special-shots-lady-killer-54', NULL, 19000.00, NULL, 54, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4779, 27, 609, 'Two Apple', 'tb-special-shots-two-apple-55', NULL, 19000.00, NULL, 55, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4780, 27, 609, 'Apple', 'tb-special-shots-apple-56', NULL, 19000.00, NULL, 56, 1, '2026-05-19 13:18:06', '2026-05-19 13:18:06'),
(4781, 4, 348, 'Pineapple Juice (Pitcher)', 'pineapple-juice-pitcher', NULL, 12000.00, NULL, 9, 1, '2026-07-01 21:03:56', '2026-07-10 21:51:58'),
(4782, 21, 319, 'Snail Pepper Soup', 'snail-pepper-soup', NULL, 15000.00, NULL, 7, 1, '2026-07-18 15:57:53', '2026-07-29 23:20:25'),
(4783, 21, 319, 'Peppered Gizzard', 'peppered-gizzard', NULL, 8000.00, NULL, 8, 1, '2026-07-18 15:59:46', '2026-07-29 23:20:42'),
(4784, 21, 319, 'GizDodo', 'gizdodo', NULL, 10000.00, NULL, 9, 1, '2026-07-18 16:00:38', '2026-07-18 16:00:38'),
(4785, 21, 319, 'Turkey Pepper Soup', 'turkey-pepper-soup', NULL, 10000.00, NULL, 10, 1, '2026-07-18 16:01:48', '2026-08-09 01:41:01'),
(4786, 21, 610, 'Extra Yam', 'extra-yam', NULL, 3000.00, NULL, 1, 1, '2026-07-18 16:31:36', '2026-07-18 16:31:36'),
(4787, 21, 610, 'Extra Plantain', 'extra-plantain', NULL, 3000.00, NULL, 2, 1, '2026-07-18 16:32:14', '2026-07-18 16:32:14'),
(4788, 21, 610, 'Extra French Fries', 'extra-french-fries', NULL, 3000.00, NULL, 3, 1, '2026-07-18 16:32:48', '2026-07-18 16:32:48'),
(4789, 21, 610, 'Extra Potato', 'extra-potato', NULL, 3000.00, NULL, 4, 1, '2026-07-18 16:33:24', '2026-07-18 16:33:24'),
(4790, 21, 611, 'Ellipse Special Noodles & Chicken', 'ellipse-special-noodles-chicken', NULL, 14000.00, NULL, 1, 1, '2026-07-18 16:35:57', '2026-07-30 00:39:52'),
(4791, 21, 611, 'Ellipse Special Noodles & Turkey', 'ellipse-special-noodles-turkey', NULL, 14000.00, NULL, 2, 1, '2026-07-18 16:36:33', '2026-07-30 00:40:21'),
(4792, 21, 611, 'Ellipse Special Noodles', 'ellipse-special-noodles', 'Noodles, Omelette, Sausage roll, and Plantain', 6000.00, NULL, 3, 1, '2026-07-18 16:39:03', '2026-07-30 00:38:14'),
(4793, 21, 611, 'Singaporean Noodles', 'singaporean-noodles', 'Singaporean stir-fried with chicken breast, shrimps, scrabbed egg and seasonal vegetables', 15000.00, NULL, 4, 1, '2026-07-18 16:42:43', '2026-07-30 00:41:45'),
(4794, 21, 612, 'Ellipse Special Chicken Shawarma & Chips', 'ellipse-special-chicken-shawarma-chips', NULL, 10000.00, NULL, 1, 1, '2026-07-18 16:51:45', '2026-08-06 15:17:36'),
(4795, 21, 612, 'Ellipse Special Chicken or Beef Shawarma', 'ellipse-special-chicken-or-beef-shawarma', NULL, 6000.00, NULL, 2, 1, '2026-07-18 16:53:16', '2026-08-06 15:14:28'),
(4796, 21, 613, 'Ellipse Seafood Platter', 'ellipse-seafood-platter', 'Prawns, Croaker Fish, Shrimps, Calamaries, Plantain, Fries, Pineapple, Red grape, Lettuce, and Lemon', 120000.00, NULL, 1, 1, '2026-07-18 16:57:35', '2026-08-09 01:32:50'),
(4797, 21, 613, 'Ellipse Luxury Combo Platter', 'ellipse-luxury-combo-platter', 'Full Croaker, Tiger Prawns, Snail, Crabs, Chicken, Goat meat, French Fries, Plantain, Vegetables, and Coleslaw', 110000.00, NULL, 2, 1, '2026-07-18 16:58:18', '2026-08-09 01:33:13'),
(4798, 21, 613, 'Native Platter', 'native-platter', 'Isiewu, Nkwobi, Abacha, Ugba, Yam chips, serve with pepper sauce,', 45000.00, NULL, 3, 1, '2026-07-18 16:59:01', '2026-08-09 01:33:31'),
(4799, 30, 614, 'Chicken burger', 'chicken-burger', 'Crispy Chicken Burger\r\nTender chicken filet, crisp lettuce, cheddar cheese, pickles, and signature sauce in a toasted brioche bun.', 0.00, NULL, 1, 1, '2026-07-28 21:42:56', '2026-07-28 21:42:56'),
(4800, 21, 616, 'Full English Breakfast', 'full-english-breakfast', 'Your choice of Eggs (Spanish Omelette, Fried Egg, Poached Egg, Scrabble Egg,  or Boiled Egg) serve with crispy Bacon, Brown Sausage, Mushroom, Baked Beans, Bread (White or Toast) accompanied with Tea, Coffee, or Juice', 10000.00, NULL, 1, 1, '2026-07-29 19:15:10', '2026-07-29 19:15:10'),
(4801, 21, 616, 'American breakfast', 'american-breakfast', 'Your choice (Spanish omelette, fried egg, poached egg, Scrabble egg or boiled egg) serve with crispy bacon, Brown sausage, sauteed vegetables, pancake or waffle, baked beans, accompanied with Tea or Coffee', 10000.00, NULL, 2, 1, '2026-07-29 19:25:07', '2026-07-29 19:25:07'),
(4802, 21, 616, 'American breakfast', 'american-breakfast-V1qn', 'Your choice (Spanish omelette, fried egg, poached egg, Scrabble egg or boiled egg) serve with crispy bacon, Brown sausage, sauteed vegetables, pancake or waffle, baked beans, accompanied with Tea or Coffee', 10000.00, NULL, 2, 1, '2026-07-29 19:25:07', '2026-07-29 19:27:19'),
(4803, 21, 616, 'Nigerian breakfast', 'nigerian-breakfast', 'Pap or custard, akara, plantain, moi-moi, fried fish, and milk OR boiled yam, plantain (boiled or fried) all serve with vegetable sauce, fish sauce, egg sauce, or corn beef sauce, accompanied with tea or coffee', 10000.00, NULL, 3, 1, '2026-07-29 19:34:30', '2026-07-29 19:34:30'),
(4804, 21, 616, 'Breakfast Extra', 'breakfast-extra', 'Eggs (guest choice) oatmeal, custard, pap, pancake, bread (rolls or slice) fried or boiled plantain, sausage, bacon or ham.', 10000.00, NULL, 4, 1, '2026-07-29 19:39:53', '2026-07-29 19:39:53'),
(4805, 21, 617, 'Oatmeal Porridge', 'oatmeal-porridge', 'Oatmeal serves with choice of egg (Spanish omelette, fried egg, boiled egg, egg sauce, and bread (toast or white)', 10000.00, NULL, 1, 1, '2026-07-29 19:45:49', '2026-07-29 19:45:49'),
(4806, 21, 617, 'Ellipse Custard', 'ellipse-custard', 'Gourmet made creamy custard serves with choice of eggs (Spanish omelette, fried or Boiled, bread (white or toast)', 6000.00, NULL, 2, 1, '2026-07-29 19:48:36', '2026-07-29 19:48:36'),
(4807, 21, 617, 'Cereal', 'cereal', 'Cornflakes golden morn Coco Pop, rice crispy or fruits serves with hot or cold milk.', 10000.00, NULL, 3, 1, '2026-07-29 19:55:02', '2026-07-29 19:55:02'),
(4808, 21, 618, 'Vegetable Spring Roll', 'vegetable-spring-roll', '5 fingers of golden deep fry spring roll serve with sweet chili sauce', 5000.00, NULL, 1, 1, '2026-07-29 20:09:33', '2026-07-29 20:09:33'),
(4809, 21, 618, 'Samosa', 'samosa', '5 fingers of golden deep fry samosa with minced beef fillings serves with sweet chilli sauce', 5000.00, NULL, 2, 1, '2026-07-29 20:13:17', '2026-07-29 20:13:17'),
(4810, 21, 618, 'Ellipse Fish in Batter', 'ellipse-fish-in-batter', 'Battered crispy deep fry fingers serve with tartar sauce', 16000.00, NULL, 3, 1, '2026-07-29 20:15:51', '2026-07-29 20:15:51'),
(4811, 21, 618, 'Buffalo Chicken Wings', 'buffalo-chicken-wings', 'Chef special marinated chicken wings deep fried serve with chilli sauce', 8000.00, NULL, 4, 1, '2026-07-29 20:18:46', '2026-07-29 20:18:46'),
(4812, 21, 618, 'Peppered Gizzard', 'peppered-gizzard-0GY6', 'Sauteed in spicy Nigeria sauce served on a bed of vegetables', 8000.00, NULL, 5, 1, '2026-07-29 20:21:30', '2026-07-29 20:21:30'),
(4813, 21, 618, 'Peppered Turkey Gizzard', 'peppered-turkey-gizzard', 'Sauteed in spicy Nigeria sauce served on a bed of vegetables', 8000.00, NULL, 6, 1, '2026-07-29 20:22:40', '2026-07-29 20:22:40'),
(4814, 21, 618, 'Peppered Goatmeat', 'peppered-goatmeat', 'Sauteed in spicy Nigeria sauce served on a bed of vegetables', 10000.00, NULL, 7, 1, '2026-07-29 20:23:48', '2026-08-12 20:01:12'),
(4815, 21, 618, 'Peppered Snail', 'peppered-snail', 'Sauteed in spicy Nigeria sauce served on a bed of vegetables', 15000.00, NULL, 8, 1, '2026-07-29 20:25:59', '2026-07-29 20:25:59'),
(4816, 21, 618, 'Peppered Chicken', 'peppered-chicken', 'Sauteed in spicy Nigeria sauce served on a bed of vegetables', 8000.00, NULL, 9, 1, '2026-07-29 20:30:02', '2026-07-29 20:30:02'),
(4817, 21, 618, 'Peppered Turkey', 'peppered-turkey', 'Sauteed in spicy Nigeria sauce served on a bed of vegetables', 8000.00, NULL, 10, 1, '2026-07-29 20:31:29', '2026-07-29 20:31:29'),
(4818, 21, 618, 'Peppered Fish (Titus or Croaker)', 'peppered-fish-titus-or-croaker', 'Sauteed in spicy Nigeria sauce served on a bed of vegetables', 8000.00, NULL, 11, 1, '2026-07-29 20:33:13', '2026-07-29 20:33:13'),
(4819, 21, 618, 'Peppered Beef', 'peppered-beef', 'Sauteed in spicy Nigeria sauce served on a bed of vegetables', 8000.00, NULL, 12, 1, '2026-07-29 20:33:52', '2026-07-29 20:33:52'),
(4820, 21, 618, 'Isiewu', 'isiewu', 'Goat head toasted in our specially made ugo (locally gravy for isiewu) garnished with Ugba (African oil beans seed) onion and local herbs', 15000.00, NULL, 13, 1, '2026-07-29 20:45:24', '2026-07-29 20:45:24'),
(4821, 21, 618, 'Nkwobi', 'nkwobi', 'Goat head toasted with in our specially made ugo (locally gravy for Nkwobi) garnished with Ugba (African oil beans seed) onion and local herbs', 8000.00, NULL, 14, 1, '2026-07-29 21:06:02', '2026-07-29 21:06:02'),
(4822, 21, 619, 'Goat Meat Pepper Soup', 'goat-meat-pepper-soup', NULL, 10000.00, NULL, 1, 1, '2026-07-29 21:09:24', '2026-08-09 01:34:53'),
(4823, 21, 619, 'Chicken Pepper Soup', 'chicken-pepper-soup', NULL, 10000.00, NULL, 2, 1, '2026-07-29 21:10:12', '2026-08-09 01:35:06'),
(4824, 21, 619, 'Turkey Pepper Soup', 'turkey-pepper-soup-1fUx', NULL, 10000.00, NULL, 3, 1, '2026-07-29 21:11:01', '2026-08-09 01:35:23'),
(4825, 21, 619, 'Assorted Pepper Soup', 'assorted-pepper-soup', NULL, 10000.00, NULL, 4, 1, '2026-07-29 21:12:11', '2026-08-09 01:36:12'),
(4826, 21, 619, 'Croaker Fish Cut Pepper Soup', 'croaker-fish-cut-pepper-soup', NULL, 12000.00, NULL, 5, 1, '2026-07-29 21:13:17', '2026-08-09 01:36:55'),
(4827, 21, 619, 'Whole Catfish Pepper Soup', 'whole-catfish-pepper-soup', NULL, 20000.00, NULL, 6, 1, '2026-07-29 22:31:44', '2026-07-29 22:31:44'),
(4828, 21, 620, 'Fried Rice', 'fried-rice', 'Serve with chicken, turkey, cow leg, assorted, beef,gizzard, titus, and croaker.', 15000.00, NULL, 1, 1, '2026-07-29 22:41:27', '2026-07-29 22:41:27'),
(4829, 21, 620, 'Fried Rice', 'fried-rice-zBk0', 'Served with Snail', 21000.00, NULL, 1, 1, '2026-07-29 23:39:02', '2026-08-09 02:01:48'),
(4830, 21, 620, 'Jollof Rice', 'jollof-rice', 'Served with Chicken, Turkey, Cow Leg, Assorted, Beef, Gizzard, Chicken Titus Fish', 15000.00, NULL, 2, 1, '2026-07-29 23:40:56', '2026-08-09 01:57:52'),
(4831, 21, 620, 'Jollof Rice', 'jollof-rice-DS7h', 'Serve with Snail', 21000.00, NULL, 2, 1, '2026-07-29 23:42:00', '2026-08-09 02:02:06'),
(4832, 21, 620, 'White Rice with Stew/Ofe Akwu', 'white-rice-with-stewofe-akwu', 'Served with Chicken, Turkey, Cow Leg, Assorted, Beef, Gizzard, Chicken Titus Fish.', 15000.00, NULL, 3, 1, '2026-07-29 23:44:27', '2026-08-09 02:01:18'),
(4833, 21, 620, 'White Rice with Stew/Ofe Akwu', 'white-rice-with-stewofe-akwu-ooQ9', 'Serve with Snail', 21000.00, NULL, 3, 1, '2026-07-29 23:46:42', '2026-08-09 02:02:49'),
(4834, 21, 620, 'Chinese Rice', 'chinese-rice', 'Served with Chicken, Turkey, Cow Leg, Assorted, Beef, Gizzard, Chicken Titus Fish.', 20000.00, NULL, 4, 1, '2026-07-29 23:48:00', '2026-08-09 02:04:03'),
(4835, 21, 620, 'Chinese Rice', 'chinese-rice-E5G1', 'Serve with Snail', 25000.00, NULL, 4, 1, '2026-07-29 23:49:07', '2026-07-29 23:49:07'),
(4836, 21, 620, 'Pineapple Rice', 'pineapple-rice', 'Served with Chicken, Turkey, Cow Leg, Assorted, Beef, Gizzard, Chicken Titus Fish.', 20000.00, NULL, 5, 1, '2026-07-29 23:50:09', '2026-08-09 02:05:33'),
(4837, 21, 620, 'Pineapple Rice', 'pineapple-rice-PcLC', 'Serve with Snail', 25000.00, NULL, 5, 1, '2026-07-29 23:51:19', '2026-07-29 23:51:19'),
(4838, 21, 620, 'oriental Rice', 'oriental-rice', 'Served with Chicken, Turkey, Cow Leg, Assorted, Beef, Gizzard, Chicken Titus Fish.', 20000.00, NULL, 6, 1, '2026-07-29 23:52:01', '2026-08-09 02:08:51'),
(4839, 21, 620, 'Oriental Rice', 'oriental-rice-ll53', 'Serve with Snail', 25000.00, NULL, 6, 1, '2026-07-29 23:52:45', '2026-07-29 23:52:45'),
(4840, 21, 620, 'Native Rice', 'native-rice', 'Served with Chicken, Turkey, Cow Leg, Assorted, Beef, Gizzard, Chicken Titus Fish.', 20000.00, NULL, 7, 1, '2026-07-29 23:53:19', '2026-08-09 02:11:31'),
(4841, 21, 620, 'Native Rice', 'native-rice-uPjs', 'Serve with Snail', 25000.00, NULL, 7, 1, '2026-07-29 23:54:03', '2026-07-29 23:54:03'),
(4842, 21, 620, 'Coconut Rice', 'coconut-rice', 'Served with Chicken, Turkey, Cow Leg, Assorted, Beef, Gizzard, Chicken Titus Fish.', 20000.00, NULL, 8, 1, '2026-07-29 23:54:50', '2026-08-09 02:10:11'),
(4843, 21, 620, 'Coconut Rice', 'coconut-rice-xIeO', 'Serve with Snail', 25000.00, NULL, 8, 1, '2026-07-29 23:55:32', '2026-07-29 23:56:41'),
(4844, 21, 620, 'Special Fried Rice', 'special-fried-rice', 'Served with Chicken, Turkey, Cow Leg, Assorted, Beef, Gizzard, Chicken Titus Fish.', 20000.00, NULL, 9, 1, '2026-07-29 23:57:44', '2026-08-09 02:12:35'),
(4845, 21, 620, 'Special Fried Rice', 'special-fried-rice-nVFA', 'Serve with Snail', 25000.00, NULL, 9, 1, '2026-07-29 23:58:19', '2026-07-29 23:58:19'),
(4846, 21, 620, 'Jamalaya Rice', 'jamalaya-rice', 'Served with Chicken, Turkey, Cow Leg, Assorted, Beef, Gizzard, Chicken Titus Fish.', 20000.00, NULL, 10, 1, '2026-07-29 23:59:14', '2026-08-09 02:13:48'),
(4847, 21, 620, 'Jamalaya Rice', 'jamalaya-rice-BZjV', 'Serve with Snail', 25000.00, NULL, 10, 1, '2026-07-29 23:59:48', '2026-07-30 00:00:04'),
(4848, 21, 620, 'Ofada Rice and Sauce', 'ofada-rice-and-sauce', '(Ofada Rice, Kpomo, Dry fish, Eggs, and Shredded Meat) \r\nServed with Chicken, Turkey, Cow Leg, Assorted, Beef, Gizzard, Titus Fish, Chicken', 23000.00, NULL, 11, 1, '2026-07-30 00:03:15', '2026-08-09 01:59:50'),
(4849, 21, 620, 'Ofada Rice and Sauce', 'ofada-rice-and-sauce-9MgF', '(Ofada Rice, Kpomo, Dry fish, Eggs, and Shredded Meat) \r\nServed with Snail', 27000.00, NULL, 11, 1, '2026-07-30 00:04:24', '2026-07-30 00:05:18'),
(4850, 21, 621, 'Afang Soup', 'afang-soup', 'Served with Chicken, Turkey, Cow Leg, Assorted, Beef, Gizzard, Titus Fish, Croaker Fish, Chicken\r\n\r\nServe with choice of swallow (Eba, Semo, Poundo, Oatmeal)', 15000.00, NULL, 1, 1, '2026-07-30 00:08:55', '2026-07-30 00:08:55'),
(4851, 21, 621, 'Afang Soup', 'afang-soup-ynee', 'Serve with Snail', 21000.00, NULL, 1, 1, '2026-07-30 00:11:47', '2026-08-09 01:18:52'),
(4852, 21, 621, 'Egusi Soup', 'egusi-soup', 'Served with Chicken, Turkey, Cow Leg, Assorted, Beef, Gizzard, Titus Fish, Croaker Fish, Chicken\r\n\r\nServe with choice of swallow (Eba, Semo, Poundo, Oatmeal)', 15000.00, NULL, 2, 1, '2026-07-30 00:12:33', '2026-07-30 00:12:33'),
(4853, 21, 621, 'Egusi Soup', 'egusi-soup-L5PS', 'Serve with Snail', 21000.00, NULL, 2, 1, '2026-07-30 00:13:03', '2026-08-09 01:20:28'),
(4854, 21, 621, 'Oha Soup', 'oha-soup', 'Served with Chicken, Turkey, Cow Leg, Assorted, Beef, Gizzard, Titus Fish, Croaker Fish, Chicken\r\n\r\nServe with choice of swallow (Eba, Semo, Poundo, Oatmeal)', 15000.00, NULL, 3, 1, '2026-07-30 00:13:33', '2026-07-30 00:13:33'),
(4855, 21, 621, 'Oha Soup', 'oha-soup-OWdZ', 'Serve with Snail', 21000.00, NULL, 3, 1, '2026-07-30 00:14:00', '2026-08-09 01:21:43'),
(4856, 21, 621, 'Bitter Leaf Soup', 'bitter-leaf-soup', 'Served with Chicken, Turkey, Cow Leg, Assorted, Beef, Gizzard, Titus Fish, Croaker Fish, Chicken\r\n\r\nServe with choice of swallow (Eba, Semo, Poundo, Oatmeal)', 15000.00, NULL, 4, 1, '2026-07-30 00:14:55', '2026-07-30 00:14:55'),
(4857, 21, 621, 'Bitter Leaf Soup', 'bitter-leaf-soup-Vn0u', 'Serve with Snail', 21000.00, NULL, 4, 1, '2026-07-30 00:15:24', '2026-08-09 01:23:55'),
(4858, 21, 621, 'Vegetable Soup', 'vegetable-soup', 'Served with Chicken, Turkey, Cow Leg, Assorted, Beef, Gizzard, Titus Fish, Croaker Fish, Chicken\r\n\r\nServe with choice of swallow (Eba, Semo, Poundo, Oatmeal)', 15000.00, NULL, 5, 1, '2026-07-30 00:16:25', '2026-07-30 00:16:25'),
(4859, 21, 621, 'Vegetable Soup', 'vegetable-soup-NnHF', 'Serve with Snail', 21000.00, NULL, 5, 1, '2026-07-30 00:16:53', '2026-08-09 01:25:26'),
(4860, 21, 621, 'White Soup', 'white-soup', 'Served with Chicken, Turkey, Cow Leg, Assorted, Beef, Gizzard, Titus Fish, Croaker Fish, Chicken\r\n\r\nServe with choice of swallow (Eba, Semo, Poundo, Oatmeal)', 15000.00, NULL, 6, 1, '2026-07-30 00:17:45', '2026-07-30 00:17:45'),
(4861, 21, 621, 'White Soup', 'white-soup-GCyi', 'Serve with Snail', 21000.00, NULL, 6, 1, '2026-07-30 00:18:16', '2026-08-09 01:27:19'),
(4862, 21, 621, 'Ogbono Soup', 'ogbono-soup', 'Served with Chicken, Turkey, Cow Leg, Assorted, Beef, Gizzard, Titus Fish, Croaker Fish, Chicken\r\n\r\nServe with choice of swallow (Eba, Semo, Poundo, Oatmeal)', 15000.00, NULL, 7, 1, '2026-07-30 00:19:53', '2026-07-30 00:19:53'),
(4863, 21, 621, 'Ogbono Soup', 'ogbono-soup-TIx2', 'Serve with Snail', 19000.00, NULL, 7, 1, '2026-07-30 00:20:27', '2026-07-30 00:20:27'),
(4864, 21, 621, 'Seafood Okra Soup', 'seafood-okra-soup', 'Crab, Shrimps, Prawn, croaker fish, calamaries, periwinkle, sea snail. \r\nServe with choice of Swallow (Eba, Semo, Poundo, Oatmeal)', 35000.00, NULL, 8, 1, '2026-07-30 00:23:40', '2026-08-09 01:28:37'),
(4865, 21, 621, 'Fisherman Soup', 'fisherman-soup', 'Crab, Shrimps, Prawn, croaker fish, calamaries, periwinkle, ngolo, sea snail. \r\nServe with choice of Swallow (Eba, Semo, Poundo, Oatmeal)', 35000.00, NULL, 9, 1, '2026-07-30 00:25:18', '2026-08-09 01:28:53'),
(4866, 21, 622, 'Unripe Plantain Porridge', 'unripe-plantain-porridge', NULL, 8000.00, NULL, 1, 1, '2026-07-30 00:28:12', '2026-07-30 00:29:30'),
(4867, 21, 622, 'Yam Porridge', 'yam-porridge', NULL, 8000.00, NULL, 2, 1, '2026-07-30 00:29:09', '2026-07-30 00:29:09'),
(4868, 21, 622, 'Beans and Plantain', 'beans-and-plantain', NULL, 8000.00, NULL, 3, 1, '2026-07-30 00:30:59', '2026-07-30 00:30:59'),
(4869, 21, 622, 'Ellipse Abacha', 'ellipse-abacha', NULL, 8000.00, NULL, 4, 1, '2026-07-30 00:31:46', '2026-07-30 00:31:58'),
(4870, 21, 622, 'Ukwa (Breadfruit)', 'ukwa-breadfruit', 'Serve with Fish', 25000.00, NULL, 5, 1, '2026-07-30 00:32:57', '2026-08-09 01:34:16'),
(4871, 21, 622, 'Fried/Boiled Yam and Egg Sauce', 'friedboiled-yam-and-egg-sauce', NULL, 7000.00, NULL, 6, 1, '2026-07-30 00:34:18', '2026-07-30 00:34:18'),
(4872, 21, 611, 'Chicken Alfredo Pasta', 'chicken-alfredo-pasta', 'Penny pasta prepared with chicken breast and mushroom in a creamy base garnished with paresan cheese and parsley.', 15000.00, NULL, 5, 1, '2026-07-30 00:43:16', '2026-07-30 00:43:16'),
(4873, 21, 611, 'Spaghetti Bolognaise', 'spaghetti-bolognaise', 'Minced meat in a tomato base on bed of spaghetti serve with cheese', 15000.00, NULL, 6, 1, '2026-07-30 00:44:26', '2026-07-30 00:44:26'),
(4874, 21, 611, 'Stir-fry Spaghetti', 'stir-fry-spaghetti', 'Well stir-fry spaghetti sauteed with vegetables in sauce \r\nChoice of protein: chicken, beef, turkey, assorted, fish titus/croaker, cow leg, goatmeat.', 15000.00, NULL, 7, 1, '2026-07-30 00:48:10', '2026-07-30 00:48:10');
INSERT INTO `menu_items` (`id`, `restaurant_id`, `category_id`, `name`, `slug`, `description`, `price`, `image`, `display_order`, `is_available`, `created_at`, `updated_at`) VALUES
(4875, 21, 622, 'Stir-fry Spaghetti', 'stir-fry-spaghetti-jrAs', 'Well stir-fry spaghetti sauteed with vegetables in sauce \r\n\r\nserve with Snail', 19000.00, NULL, 7, 1, '2026-07-30 00:49:39', '2026-07-30 00:54:29'),
(4876, 21, 611, 'Stir-fry Spaghetti', 'stir-fry-spaghetti-wtsZ', 'Well stir-fry spaghetti sauteed with vegetables in sauce \r\n\r\nServe with Snail', 19000.00, NULL, 7, 1, '2026-07-30 00:53:28', '2026-07-30 00:53:28'),
(4877, 21, 611, 'Seafood Pasta', 'seafood-pasta', 'Penny pasta prepared with seafood, mushroom in a creamy base garnished with parmesan cheese and parsley', 25000.00, NULL, 9, 1, '2026-07-30 00:56:06', '2026-07-30 00:56:06'),
(4878, 21, 611, 'Ellipse Jollof Pasta', 'ellipse-jollof-pasta', 'Served with Chicken, Turkey, Cow Leg, Assorted, Beef, Gizzard, Chicken Titus Fish. Croaker.', 15000.00, NULL, 8, 1, '2026-07-30 00:58:00', '2026-07-30 00:58:00'),
(4879, 21, 611, 'Ellipse Jollof Pasta', 'ellipse-jollof-pasta-z7rX', 'Serve with Snail', 19000.00, NULL, 8, 1, '2026-07-30 00:59:04', '2026-07-30 00:59:26'),
(4880, 21, 623, 'Ceaser Salad', 'ceaser-salad', 'Chicken breast, lettuce, tomato, golden croutons, cheese flakes serve with Ceaser dressing', 15000.00, NULL, 1, 1, '2026-07-30 01:13:06', '2026-07-30 01:13:06'),
(4881, 21, 623, 'Ellipse Grill Chicken Salad', 'ellipse-grill-chicken-salad', 'Stripes of grill chicken, Lettuce, tomato, cucumber, green and black olive, carrot, cabbage, boiled egg, serve with sweet dressing', 20000.00, NULL, 2, 1, '2026-07-30 01:14:17', '2026-07-30 01:14:17'),
(4882, 21, 623, 'Pasta Salad', 'pasta-salad', 'Macroni, carrot, cucumber, bell peppers, tomato, mixed with a sweet dressing', 10000.00, NULL, 3, 1, '2026-07-30 01:15:43', '2026-07-30 01:15:43'),
(4883, 21, 623, 'Club Sandwich', 'club-sandwich', 'Toast white bread, shredded chicken breast, fried egg, sliced tomato, and lettuce filling serve with ham.', 12000.00, NULL, 4, 1, '2026-07-30 01:17:31', '2026-07-30 01:17:31'),
(4884, 21, 623, 'Ellipse Bugger', 'ellipse-bugger', NULL, 12000.00, NULL, 5, 1, '2026-07-30 01:18:12', '2026-07-30 01:18:29'),
(4885, 21, 623, 'Ellipse Premium Pizza', 'ellipse-premium-pizza', NULL, 15000.00, NULL, 6, 1, '2026-07-30 01:19:22', '2026-07-30 01:21:16'),
(4886, 21, 623, 'Small chops, Samosa, spring roll', 'small-chops-samosa-spring-roll', '2 finger each', 6000.00, NULL, 7, 1, '2026-07-30 01:20:57', '2026-07-30 01:20:57'),
(4887, 21, 613, 'Chef Special Platter', 'chef-special-platter', 'Goatmeat, Samosa, Spring roll, pepper chicken, and pepper Gizzard', 75000.00, NULL, 4, 1, '2026-07-30 01:31:35', '2026-08-09 01:32:20'),
(4888, 21, 303, 'Hennessy XO', 'hennessy-xo', NULL, 700000.00, NULL, 7, 1, '2026-07-30 01:35:50', '2026-08-09 01:08:37'),
(4889, 21, 304, 'Glenfiddich 21', 'glenfiddich-21', NULL, 550000.00, NULL, 13, 1, '2026-07-30 01:38:37', '2026-08-09 01:13:07'),
(4890, 21, 305, 'Crystal Champagne', 'crystal-champagne', NULL, 800000.00, NULL, 6, 0, '2026-07-30 01:42:17', '2026-08-17 16:00:40'),
(4891, 21, 305, 'Laurent –Perrier', 'laurent-perrier', NULL, 120000.00, NULL, 7, 1, '2026-07-30 01:44:45', '2026-07-30 01:44:45'),
(4892, 21, 310, 'Martinelli\'s', 'martinellis', NULL, 20000.00, NULL, 14, 1, '2026-07-30 01:46:40', '2026-07-30 01:46:40'),
(4893, 21, 610, 'Extra Semo', 'extra-semo', NULL, 4000.00, NULL, 6, 1, '2026-07-30 01:53:22', '2026-07-30 01:53:22'),
(4894, 21, 610, 'Extra Eba', 'extra-eba', NULL, 4000.00, NULL, 7, 1, '2026-07-30 01:54:16', '2026-07-30 01:54:16'),
(4895, 21, 610, 'Extra Poundo', 'extra-poundo', NULL, 4000.00, NULL, 8, 1, '2026-07-30 01:55:00', '2026-07-30 01:55:00'),
(4896, 21, 610, 'Extra Oatmeal', 'extra-oatmeal', NULL, 4000.00, NULL, 5, 1, '2026-07-30 01:55:58', '2026-07-30 01:55:58'),
(4897, 21, 621, 'Afang Soup', 'afang-soup-4uD7', 'Serve with Croaker fish or Goatmeat', 17000.00, NULL, 1, 1, '2026-08-09 01:20:10', '2026-08-09 01:20:10'),
(4898, 21, 621, 'Egusi Soup', 'egusi-soup-buoh', 'Serve with Croaker or Goatmeat', 17000.00, NULL, 2, 1, '2026-08-09 01:21:22', '2026-08-09 01:21:22'),
(4899, 21, 621, 'Oha Soup', 'oha-soup-IgG4', 'serve with Croaker or Goatmeat', 17000.00, NULL, 3, 1, '2026-08-09 01:22:38', '2026-08-09 01:23:29'),
(4900, 21, 621, 'Vegetable Soup', 'vegetable-soup-mDIu', 'serve with Croaker or Goatmeat', 17000.00, NULL, 5, 1, '2026-08-09 01:25:00', '2026-08-09 01:25:52'),
(4901, 21, 621, 'Bitter Leaf Soup', 'bitter-leaf-soup-J9fY', 'serve with Croaker or Goatmeat', 17000.00, NULL, 4, 1, '2026-08-09 01:26:49', '2026-08-09 01:26:49'),
(4902, 21, 621, 'White Soup', 'white-soup-EeZW', 'serve with Croaker or Goatmeat', 17000.00, NULL, 6, 1, '2026-08-09 01:28:08', '2026-08-09 01:28:08'),
(4903, 21, 621, 'Ogbono Soup', 'ogbono-soup-yHVG', 'serve with Croaker or Goatmeat', 17000.00, NULL, 7, 1, '2026-08-09 01:29:58', '2026-08-09 01:29:58'),
(4904, 21, 621, 'Ofe-Owerri', 'ofe-owerri', NULL, 35000.00, NULL, 10, 1, '2026-08-09 01:30:55', '2026-08-09 01:30:55'),
(4905, 21, 610, 'Extra Jollof Rice', 'extra-jollof-rice', NULL, 4000.00, NULL, 9, 1, '2026-08-09 01:44:25', '2026-08-09 01:44:42'),
(4906, 21, 610, 'Extra Fried Rice', 'extra-fried-rice', NULL, 4000.00, NULL, 10, 1, '2026-08-09 01:45:20', '2026-08-09 01:45:20'),
(4907, 21, 610, 'Extra White Rice', 'extra-white-rice', NULL, 4000.00, NULL, 11, 1, '2026-08-09 01:45:47', '2026-08-09 01:45:47'),
(4908, 21, 610, 'Extra Asun Rice', 'extra-asun-rice', NULL, 10000.00, NULL, 12, 1, '2026-08-09 01:46:16', '2026-08-09 01:46:16'),
(4909, 21, 610, 'Extra Chinese Rice', 'extra-chinese-rice', NULL, 7000.00, NULL, 13, 1, '2026-08-09 01:48:19', '2026-08-09 01:48:19'),
(4910, 21, 610, 'Extra Special Fried Rice', 'extra-special-fried-rice', NULL, 7000.00, NULL, 14, 1, '2026-08-09 01:49:12', '2026-08-09 01:49:12'),
(4911, 21, 610, 'Extra Coconut Rice', 'extra-coconut-rice', NULL, 7000.00, NULL, 15, 1, '2026-08-09 01:49:44', '2026-08-09 01:49:44'),
(4912, 21, 610, 'Extra Jamalaya Rice', 'extra-jamalaya-rice', NULL, 7000.00, NULL, 16, 1, '2026-08-09 01:50:26', '2026-08-09 01:50:26'),
(4913, 21, 610, 'Extra Pineapple Rice', 'extra-pineapple-rice', NULL, 7000.00, NULL, 17, 1, '2026-08-09 01:51:12', '2026-08-09 01:51:12'),
(4914, 21, 610, 'Extra Basmati Jollof Rice', 'extra-basmati-jollof-rice', NULL, 7000.00, NULL, 18, 1, '2026-08-09 01:51:55', '2026-08-09 01:51:55'),
(4915, 21, 610, 'Extra Carribean Rice', 'extra-carribean-rice', NULL, 7000.00, NULL, 19, 1, '2026-08-09 01:52:49', '2026-08-09 01:53:46'),
(4916, 21, 620, 'Basmati Jollof Rice', 'basmati-jollof-rice', 'Serve with Snail', 23000.00, NULL, 12, 1, '2026-08-09 01:55:20', '2026-08-09 01:55:20'),
(4917, 21, 620, 'Basmati Jollof Rice', 'basmati-jollof-rice-acaL', 'Serve with Croaker or Goatmeat', 18000.00, NULL, 12, 1, '2026-08-09 01:56:08', '2026-08-09 01:56:08'),
(4918, 21, 620, 'Basmati Jollof Rice', 'basmati-jollof-rice-kCDm', 'Serve with Chicken, Turkey, Beef, Cow Leg, OR Assorted', 16000.00, NULL, 12, 1, '2026-08-09 01:57:31', '2026-08-09 01:57:31'),
(4919, 21, 620, 'Fried Rice', 'fried-rice-iD3W', 'Serve with Croaker or Goatmeat', 17000.00, NULL, 1, 1, '2026-08-09 01:58:56', '2026-08-09 01:58:56'),
(4920, 21, 620, 'Jollof Rice', 'jollof-rice-04m9', 'Serve with Croaker or Goatmeat', 17000.00, NULL, 2, 1, '2026-08-09 02:00:51', '2026-08-09 02:00:51'),
(4921, 21, 620, 'White Rice with Stew/Ofe Akwu', 'white-rice-with-stewofe-akwu-jOys', 'Serve with Croaker or Goatmeat', 17000.00, NULL, 3, 1, '2026-08-09 02:03:41', '2026-08-09 02:03:41'),
(4922, 21, 620, 'Chinese Rice', 'chinese-rice-p9Ud', 'Serve with Croaker or Goatmeat', 22000.00, NULL, 4, 1, '2026-08-09 02:05:04', '2026-08-09 02:05:04'),
(4923, 21, 620, 'Pineapple Rice', 'pineapple-rice-ykI6', 'Serve with Croaker Or Goatmeat', 22000.00, NULL, 5, 1, '2026-08-09 02:07:53', '2026-08-09 02:08:15'),
(4924, 21, 620, 'oriental Rice', 'oriental-rice-FPJ2', 'Serve with Croaker or Goatmeat', 22000.00, NULL, 6, 1, '2026-08-09 02:09:52', '2026-08-09 02:11:15'),
(4925, 21, 620, 'Coconut Rice', 'coconut-rice-kneo', 'Serve with Croaker or Goatmeat', 22000.00, NULL, 8, 1, '2026-08-09 02:10:48', '2026-08-09 02:10:48'),
(4926, 21, 620, 'Native Rice', 'native-rice-E1gU', 'Serve with Croaker or Goatmeat', 22000.00, NULL, 7, 1, '2026-08-09 02:12:06', '2026-08-09 02:12:06'),
(4927, 21, 620, 'Special Fried Rice', 'special-fried-rice-WsD0', 'Serve with Croaker or Goatmeat', 22000.00, NULL, 9, 1, '2026-08-09 02:13:22', '2026-08-09 02:13:22'),
(4928, 21, 620, 'Jamalaya Rice', 'jamalaya-rice-MT2Z', 'Serve with croaker or goatmeat', 22000.00, NULL, 10, 1, '2026-08-09 02:14:30', '2026-08-09 02:14:30'),
(4929, 21, 620, 'Ofada Rice and Sauce', 'ofada-rice-and-sauce-ruch', 'Serve with Croaker or Goatmeat', 25000.00, NULL, 11, 1, '2026-08-09 02:16:11', '2026-08-09 02:16:11'),
(4930, 21, 620, 'Asun Rice', 'asun-rice', 'Serve with Snail', 25000.00, NULL, 13, 1, '2026-08-09 02:17:26', '2026-08-09 02:17:26'),
(4931, 21, 620, 'Asun Rice', 'asun-rice-PEbu', 'Serve with Croaker or Goatmeat', 23000.00, NULL, 13, 1, '2026-08-09 02:18:21', '2026-08-09 02:18:21'),
(4932, 21, 620, 'Asun Rice', 'asun-rice-GAWk', 'Serve with Croaker or Goatmeat', 23000.00, NULL, 13, 1, '2026-08-09 02:18:21', '2026-08-09 02:18:21'),
(4933, 21, 620, 'Asun Rice', 'asun-rice-pkDV', 'Serve with Chicken, Turkey, Cow Leg, Assorted, or Beef', 20000.00, NULL, 13, 1, '2026-08-09 02:20:28', '2026-08-09 02:20:28'),
(4934, 21, 305, 'Andre Rose', 'andre-rose', NULL, 28000.00, NULL, 8, 1, '2026-08-12 19:38:49', '2026-08-12 19:38:49'),
(4935, 21, 312, 'Superior Cranberry', 'superior-cranberry', NULL, 12000.00, NULL, 7, 1, '2026-08-12 23:55:03', '2026-08-12 23:55:03'),
(4936, 21, 623, 'Coleslaw & Boiled Egg', 'coleslaw-boiled-egg', NULL, 5000.00, NULL, 8, 1, '2026-08-13 00:40:25', '2026-08-13 00:40:25'),
(4937, 32, 624, 'Odogwu Moinmoin', 'odogwu-moinmoin', '(corned beef, full egg, Liver & fish)', 4000.00, 'PS8JzvPnW2dS.jpg', 1, 1, '2026-08-14 16:56:07', '2026-08-14 17:37:54'),
(4938, 32, 624, 'Achalugo Moinmoin', 'achalugo-moinmoin', '( full egg & fish)', 2500.00, '5XXcByGgKKcf.jpg', 2, 1, '2026-08-14 16:56:36', '2026-08-14 17:38:29'),
(4939, 32, 624, 'Omoluabi Moinmoin', 'omoluabi-moinmoin', '(corned beef, full egg, Liver & fish)', 2000.00, 'zOSsRrSM7AYa.jpg', 3, 1, '2026-08-14 17:02:21', '2026-08-14 17:38:53'),
(4940, 32, 624, 'Pap (Milk+ Sugar)', 'pap-milk-sugar', NULL, 2500.00, 'FFeDwkwMkisP.jpg', 3, 1, '2026-08-14 17:03:27', '2026-08-14 17:39:20'),
(4941, 32, 624, 'Custard', 'custard', NULL, 2500.00, 'EE8j3soAP8NQ.jpg', 5, 1, '2026-08-14 17:04:05', '2026-08-14 17:39:38'),
(4942, 32, 624, 'Big Bread', 'big-bread', NULL, 1000.00, 'DL3RKUtI9gz4.jpg', 7, 1, '2026-08-14 17:05:09', '2026-08-14 17:39:53'),
(4943, 32, 624, 'Original Ewa Agoyin', 'original-ewa-agoyin', '( Eja Kika, ponmo, plantain)', 6000.00, 'xAwkiszV0Twe.jpg', 8, 1, '2026-08-14 17:05:48', '2026-08-14 17:40:12'),
(4944, 32, 624, 'Original Ewa Agoyin & Bread', 'original-ewa-agoyin-bread', '( Eja Kika, ponmo,)', 6000.00, 'x7N7yeBO5EWb.jpg', 9, 1, '2026-08-14 17:06:17', '2026-08-14 17:40:41'),
(4945, 32, 624, 'Tuwo, Gbegiri Ewedu + assorted meat', 'tuwo-gbegiri-ewedu-assorted-meat', NULL, 5000.00, 'cruEqZKamIgj.jpg', 10, 1, '2026-08-14 17:06:51', '2026-08-14 17:41:01'),
(4946, 34, 625, 'Stir-Fry rice and Grilled chicken', 'stir-fry-rice-and-grilled-chicken', NULL, 4999.00, NULL, 1, 1, '2026-08-20 17:08:41', '2026-08-20 17:08:41'),
(4947, 34, 625, 'Stir-Fry Pasta and Grilled chicken', 'stir-fry-pasta-and-grilled-chicken', NULL, 4999.00, NULL, 2, 1, '2026-08-20 17:09:53', '2026-08-20 17:10:08'),
(4948, 21, 623, 'Cake', 'cake', NULL, 4000.00, NULL, 9, 1, '2026-08-21 19:10:17', '2026-08-21 19:10:17'),
(4949, 21, 623, 'Ellipse Super Roll', 'ellipse-super-roll', NULL, 3000.00, NULL, 10, 1, '2026-08-21 19:10:51', '2026-08-21 19:10:51'),
(4950, 21, 623, 'Chicken Pie', 'chicken-pie', NULL, 2000.00, NULL, 11, 1, '2026-08-21 19:11:13', '2026-08-21 19:11:13'),
(4951, 21, 623, 'Meat Pie', 'meat-pie', NULL, 2000.00, NULL, 12, 1, '2026-08-21 19:11:28', '2026-08-21 19:11:28'),
(4952, 21, 623, 'Egg Roll', 'egg-roll', NULL, 1000.00, NULL, 13, 1, '2026-08-21 19:12:08', '2026-08-21 19:12:08'),
(4953, 21, 623, 'Ellipse Special Burger', 'ellipse-special-burger', NULL, 15000.00, NULL, 14, 1, '2026-08-21 19:12:58', '2026-08-21 19:12:58'),
(4954, 4, 328, 'Mocqueca Prawn Bisque', 'mocqueca-prawn-bisque', 'Silky coconut, tomato & Brazilian-spiced bisque with succulent prawns.', 27500.00, NULL, 11, 1, '2026-08-25 17:42:10', '2026-08-25 17:42:10'),
(4955, 4, 328, 'Prawn Anise Salad', 'prawn-anise-salad', 'Succulent prawns, fennel, mixed greens, citrus & aromatic anise dressing', 21500.00, NULL, 12, 1, '2026-08-25 17:47:42', '2026-08-25 17:47:42'),
(4956, 4, 328, 'Seafood Coconut Pepper Soup', 'seafood-coconut-pepper-soup', 'coconut-infused pepper broth with fresh prawns, fish and calamari, finished with aromatic herbs and spices', 21500.00, NULL, 13, 1, '2026-08-25 17:53:28', '2026-08-25 17:53:28'),
(4957, 4, 328, 'Chicken Mozzarella Croquettes', 'chicken-mozzarella-croquettes', 'Crisp golden croquettes of seasoned chicken and molten mozzarella, finished with a silky house-made dipping sauce.', 14900.00, NULL, 14, 1, '2026-08-25 18:00:39', '2026-08-25 18:00:39'),
(4958, 4, 328, 'Creamy Shrimp Tacos', 'creamy-shrimp-tacos', 'Succulent spiced shrimp, fresh slaw and vibrant toppings nestled in warm tortillas, finished with our signature creamy Sauce', 19900.00, NULL, 15, 1, '2026-08-25 18:07:28', '2026-08-25 18:07:28'),
(4959, 4, 328, 'Spicy Chicken Tacos', 'spicy-chicken-tacos', 'Charred spiced chicken, crisp slaw and fresh toppings in warm tortillas, finished with a vibrant house made chilli sauce.', 16900.00, NULL, 16, 1, '2026-08-25 18:08:54', '2026-08-25 18:08:54'),
(4960, 4, 328, 'Compound Butter Elote (Corn)', 'compound-butter-elote-corn', 'Fire-charred sweet corn, glazed with herb-infused compound butter, finished with creamy cheese, fresh herbs and cream', 14900.00, NULL, 17, 1, '2026-08-25 18:10:43', '2026-08-25 18:10:43'),
(4961, 4, 332, 'Sambal Rice With Sunny Side Egg', 'sambal-rice-with-sunny-side-egg', 'Wok-tossed fragrant rice with aromatic sambal, topped with a golden sunny-side egg and finished with fresh herbs.', 8500.00, NULL, 9, 1, '2026-08-25 18:30:33', '2026-08-25 18:41:18'),
(4962, 4, 332, 'Yam Fries', 'yam-fries', NULL, 5500.00, NULL, 10, 1, '2026-08-25 18:37:28', '2026-08-25 18:37:28');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_05_19_000001_create_admins_table', 1),
(2, '2026_05_19_000002_create_categories_table', 1),
(3, '2026_05_19_000003_create_category_secondary_sections_table', 1),
(4, '2026_05_19_000004_create_customization_settings_table', 1),
(5, '2026_05_19_000005_create_email_delivery_suppressions_table', 1),
(6, '2026_05_19_000006_create_login_attempts_table', 1),
(7, '2026_05_19_000007_create_managers_table', 1),
(8, '2026_05_19_000008_create_menu_items_table', 1),
(9, '2026_05_19_000009_create_orders_table', 1),
(10, '2026_05_19_000010_create_order_items_table', 1),
(11, '2026_05_19_000011_create_password_reset_tokens_table', 1),
(12, '2026_05_19_000012_create_payments_table', 1),
(13, '2026_05_19_000013_create_payment_settings_table', 1),
(14, '2026_05_19_000014_create_pending_bank_transfers_table', 1),
(15, '2026_05_19_000015_create_pending_online_payments_table', 1),
(16, '2026_05_19_000016_create_public_api_rate_events_table', 1),
(17, '2026_05_19_000017_create_qr_code_scans_table', 1),
(18, '2026_05_19_000018_create_qr_templates_table', 1),
(19, '2026_05_19_000019_create_restaurants_table', 1),
(20, '2026_05_19_000020_create_restaurant_payment_settings_table', 1),
(21, '2026_05_19_000021_create_restaurant_qr_codes_table', 1),
(22, '2026_05_19_000022_create_restaurant_reservation_settings_table', 1),
(23, '2026_05_19_000023_create_sections_table', 1),
(24, '2026_05_19_000024_create_site_settings_table', 1),
(25, '2026_05_19_000025_create_subscriptions_table', 1),
(26, '2026_05_19_000026_create_subscription_change_requests_table', 1),
(27, '2026_05_19_000027_create_subscription_emails_table', 1),
(28, '2026_05_19_000028_create_subscription_plans_table', 1),
(29, '2026_05_19_000029_create_table_inventory_daily_table', 1),
(30, '2026_05_19_000030_create_table_reservations_table', 1),
(31, '2026_05_19_000031_create_templates_table', 1),
(32, '2026_05_19_000032_create_template_customizations_table', 1),
(33, '2026_05_19_000033_create_template_plans_table', 1),
(34, '2026_05_19_000034_create_template_restaurants_table', 1),
(35, '2026_05_19_000035_baseline_indexes', 1),
(36, '2026_05_19_000036_baseline_auto_increment', 1),
(37, '2026_05_19_000037_baseline_foreign_keys', 1),
(38, 'README', 1),
(39, '0001_01_01_000001_create_cache_table', 2),
(40, '0001_01_01_000002_create_jobs_table', 2),
(41, '2026_05_29_000001_create_subscription_email_log_table', 3),
(42, '2026_05_30_000001_add_payment_intent_columns_to_payments', 4),
(43, '2026_05_30_000002_add_bank_transfer_approval_columns', 4),
(44, '2026_05_30_000003_create_activity_logs_table', 4),
(45, '2026_05_30_000004_create_payment_fulfillment_receipts_table', 4),
(46, '2026_05_30_000005_add_email_suppression_unique_index', 4);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(10) DEFAULT NULL,
  `restaurant_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(50) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `delivery_address` text NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending' COMMENT 'pending, confirmed, on_hold, cancelled, completed',
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `delivery_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `restaurant_id`, `customer_name`, `customer_phone`, `customer_email`, `delivery_address`, `payment_method`, `status`, `subtotal`, `delivery_fee`, `tax`, `total`, `created_at`, `updated_at`) VALUES
(14, NULL, 2, 'carter tech', '91347593', 'mr.carter.tech07@gmail.com', 'Cf', NULL, 'pending', 42000.00, 0.00, 0.00, 42000.00, '2026-02-09 16:34:30', '2026-02-09 16:34:30'),
(15, NULL, 2, 'Abdulrahman Shittu', '08032336586', 'mr.carter.tech07@gmail.com', 'gdd', 'bank_transfer', 'pending', 42000.00, 0.00, 0.00, 42000.00, '2026-02-10 16:19:58', '2026-02-10 16:19:58'),
(16, NULL, 2, 'Abdulrahman Shittu', '08032336586', 'mr.carter.tech07@gmail.com', 'fsdg', 'bank_transfer', 'confirmed', 19000.00, 0.00, 0.00, 19000.00, '2026-02-10 16:36:59', '2026-02-10 17:03:22'),
(17, NULL, 2, 'Abdulrahman Shittu', '08032336586', 'mr.carter.tech07@gmail.com', 'jhh', 'bank_transfer', 'pending', 85000.00, 0.00, 0.00, 85000.00, '2026-02-10 16:51:05', '2026-02-10 16:51:05'),
(18, NULL, 2, 'Abdulrahman Shittu', '08032336586', 'mr.carter.tech07@gmail.com', 'dgddg', 'bank_transfer', 'pending', 230000.00, 0.00, 0.00, 230000.00, '2026-02-10 17:26:01', '2026-02-10 17:26:01'),
(19, NULL, 2, 'Abdulrahman Shittu', '08032336586', 'mr.carter.tech07@gmail.com', 'dgtggdg', 'paystack', 'pending', 44000.00, 0.00, 0.00, 44000.00, '2026-02-10 17:42:17', '2026-02-10 17:42:17'),
(20, NULL, 2, 'Abdulrahman Shittu', '08032336586', 'mr.carter.tech07@gmail.com', 'ffdfdgdgd', 'paystack', 'pending', 44000.00, 0.00, 0.00, 44000.00, '2026-02-10 18:09:27', '2026-02-10 18:09:27'),
(21, NULL, 2, 'Abdulrahman Shittu', '08032336586', 'mr.carter.tech07@gmail.com', 'ffdfdgdgd', 'bank_transfer', 'pending', 44000.00, 0.00, 0.00, 44000.00, '2026-02-10 18:10:09', '2026-02-10 18:10:09'),
(22, 'KMK9M2QM', 2, 'Abdulrahman Shittu', '08032336586', 'sigsol2024@gmail.com', 'aafs', 'bank_transfer', 'confirmed', 100.00, 0.00, 0.00, 100.00, '2026-02-11 13:13:02', '2026-02-11 13:13:15'),
(23, 'MYC6DN8Z', 2, 'Abdulrahman Shittu', '08032336586', 'sigsol2024@gmail.com', 'sfsf', 'paystack', 'pending', 100.00, 0.00, 0.00, 100.00, '2026-02-11 13:15:40', '2026-02-11 13:15:40'),
(24, 'BYRO5UG8', 2, 'Abdulrahman Shittu', '08032336586', 'sigsol2024@gmail.com', 'sfsfsf', 'paystack', 'confirmed', 100.00, 0.00, 0.00, 100.00, '2026-02-11 13:25:23', '2026-02-11 13:25:23'),
(25, '0NQ0334D', 2, 'Abdulrahman Shittu', '08032336586', 'mr.carter.tech07@gmail.com', 'fsfs', 'bank_transfer', 'confirmed', 100.00, 0.00, 0.00, 100.00, '2026-02-12 23:30:50', '2026-02-12 23:31:55'),
(26, 'VNW31EG9', 3, 'Abdulrahman Shittu', '08032336586', 'mr.carter.tech07@gmail.com', 'wrrr', 'bank_transfer', 'confirmed', 60000.00, 0.00, 0.00, 60000.00, '2026-02-17 14:48:59', '2026-02-17 14:50:08');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_item_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `menu_item_id`, `name`, `price`, `quantity`, `created_at`) VALUES
(20, 14, 2536, 'Chicken Spring Rolls', 19000.00, 1, '2026-02-09 16:34:30'),
(21, 14, 2537, 'Grilled Chicken Wings', 23000.00, 1, '2026-02-09 16:34:30'),
(22, 15, 2536, 'Chicken Spring Rolls', 19000.00, 1, '2026-02-10 16:19:58'),
(23, 15, 2537, 'Grilled Chicken Wings', 23000.00, 1, '2026-02-10 16:19:58'),
(24, 16, 2536, 'Chicken Spring Rolls', 19000.00, 1, '2026-02-10 16:36:59'),
(25, 17, 2540, 'Grilled Pettit Prawns', 35000.00, 1, '2026-02-10 16:51:05'),
(26, 17, 2541, 'Dynamite Shrimp', 25000.00, 2, '2026-02-10 16:51:05'),
(27, 18, 2537, 'Grilled Chicken Wings', 23000.00, 10, '2026-02-10 17:26:01'),
(28, 19, 2538, 'Lollipop Chicken', 25000.00, 1, '2026-02-10 17:42:17'),
(29, 19, 2539, 'Caesar Chicken Sliders', 19000.00, 1, '2026-02-10 17:42:17'),
(30, 20, 2538, 'Lollipop Chicken', 25000.00, 1, '2026-02-10 18:09:27'),
(31, 20, 2539, 'Caesar Chicken Sliders', 19000.00, 1, '2026-02-10 18:09:27'),
(32, 21, 2538, 'Lollipop Chicken', 25000.00, 1, '2026-02-10 18:10:09'),
(33, 21, 2539, 'Caesar Chicken Sliders', 19000.00, 1, '2026-02-10 18:10:09'),
(34, 22, 2536, 'Chicken Spring Rolls', 100.00, 1, '2026-02-11 13:13:02'),
(35, 23, 2536, 'Chicken Spring Rolls', 100.00, 1, '2026-02-11 13:15:40'),
(36, 24, 2536, 'Chicken Spring Rolls', 100.00, 1, '2026-02-11 13:25:23'),
(37, 25, 2536, 'Chicken Spring Rolls', 100.00, 1, '2026-02-12 23:30:50'),
(38, 26, 4335, 'Premium Tray', 60000.00, 1, '2026-02-17 14:48:59');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL,
  `user_type` enum('admin','manager') NOT NULL,
  `user_id` int(11) NOT NULL,
  `identifier` varchar(191) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token_hash` char(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `request_ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`id`, `user_type`, `user_id`, `identifier`, `email`, `token_hash`, `expires_at`, `used_at`, `request_ip`, `user_agent`, `created_at`) VALUES
(1, 'manager', 12, 'abrobiz@gmail.com', 'abrobiz@gmail.com', '6d181ecb6e1d7415f25b2ce33d0b68222db7a53efef84c6c49479571a143f022', '2026-03-13 09:00:13', '2026-03-13 08:00:51', '102.207.247.18', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-13 08:00:13');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `billing_cycle` varchar(20) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'NGN',
  `payment_gateway` enum('paystack','flutterwave','manual') NOT NULL,
  `transaction_reference` varchar(100) DEFAULT NULL COMMENT 'Gateway reference',
  `gateway_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Full gateway response',
  `status` enum('pending','success','failed','refunded') NOT NULL DEFAULT 'pending',
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `restaurant_id`, `subscription_id`, `plan_id`, `billing_cycle`, `amount`, `currency`, `payment_gateway`, `transaction_reference`, `gateway_response`, `status`, `paid_at`, `created_at`) VALUES
(10, 2, 28, NULL, NULL, 50.00, 'NGN', 'paystack', NULL, '{\"reason\":\"stale_pending_timeout\",\"failed_at\":\"2026-06-02T00:01:50+00:00\",\"threshold_hours\":6}', 'failed', NULL, '2025-12-24 03:13:58'),
(11, 2, 28, NULL, NULL, 50.00, 'NGN', 'paystack', 'PS_1766546254_d79a5efb6231a96e', '{\"reason\":\"stale_pending_timeout\",\"failed_at\":\"2026-06-02T00:01:50+00:00\",\"threshold_hours\":6}', 'failed', NULL, '2025-12-24 03:17:34'),
(12, 2, 28, NULL, NULL, 100.00, 'NGN', 'paystack', 'PS_1766546331_90dc48f6b6afa69e', NULL, 'success', '2025-12-24 17:08:48', '2025-12-24 03:18:51'),
(13, 2, 28, NULL, NULL, 200.00, 'NGN', 'paystack', 'PS_1766624250_59406dae47c1dd0f', '{\"reason\":\"stale_pending_timeout\",\"failed_at\":\"2026-06-02T00:01:50+00:00\",\"threshold_hours\":6}', 'failed', NULL, '2025-12-25 00:57:30'),
(14, 2, 28, NULL, NULL, 220.00, 'NGN', 'paystack', 'PS_1766624771_b04c1caf52ed9745', '{\"reason\":\"stale_pending_timeout\",\"failed_at\":\"2026-06-02T00:01:50+00:00\",\"threshold_hours\":6}', 'failed', NULL, '2025-12-25 01:06:11'),
(15, 19, 30, NULL, NULL, 120900.00, 'NGN', 'paystack', 'PS_1775494894_361b2945d74f30c2', '{\"id\":6010814859,\"domain\":\"live\",\"status\":\"success\",\"reference\":\"PS_1775494894_361b2945d74f30c2\",\"receipt_number\":null,\"amount\":12090000,\"message\":null,\"gateway_response\":\"Approved\",\"paid_at\":\"2026-04-06T17:17:46.000Z\",\"created_at\":\"2026-04-06T17:01:35.000Z\",\"channel\":\"bank_transfer\",\"currency\":\"NGN\",\"ip_address\":\"102.88.55.173\",\"metadata\":{\"payment_id\":\"9\",\"subscription_id\":\"18\",\"restaurant_id\":\"19\",\"plan_id\":\"2\",\"billing_cycle\":\"annual\",\"referrer\":\"https:\\/\\/our-menu.online\\/\"},\"log\":{\"start_time\":1775494899,\"time_spent\":967,\"attempts\":0,\"errors\":0,\"success\":true,\"mobile\":false,\"input\":[],\"history\":[{\"type\":\"pending\",\"message\":\"Payment in progress with bank\",\"time\":1},{\"type\":\"action\",\"message\":\"Set payment method to: bank\",\"time\":8},{\"type\":\"action\",\"message\":\"Set payment method to: bank_transfer\",\"time\":18},{\"type\":\"success\",\"message\":\"Successfully paid with bank_transfer\",\"time\":967}]},\"fees\":191350,\"fees_split\":null,\"authorization\":{\"authorization_code\":\"AUTH_jk11d3w884\",\"bin\":\"540XXX\",\"last4\":\"X718\",\"exp_month\":\"04\",\"exp_year\":\"2026\",\"channel\":\"bank_transfer\",\"card_type\":\"transfer\",\"bank\":\"Providus Bank\",\"country_code\":\"NG\",\"brand\":\"Managed Account\",\"reusable\":false,\"signature\":null,\"account_name\":null,\"sender_bank\":\"Providus Bank\",\"sender_country\":\"NG\",\"sender_bank_account_number\":\"XXXXXXX718\",\"sender_name\":\"VENDOME ENT. (PETTY CASH A\\/C)\",\"narration\":\"To TITAN-PAYSTACK | PAYSTACK CHECKOUT Opal qr code\",\"receiver_bank_account_number\":null,\"receiver_bank\":null},\"customer\":{\"id\":353433914,\"first_name\":null,\"last_name\":null,\"email\":\"opallagos1@gmail.com\",\"customer_code\":\"CUS_5k609h7ceyoi1oe\",\"phone\":null,\"metadata\":null,\"risk_action\":\"default\",\"international_format_phone\":null},\"plan\":null,\"split\":[],\"order_id\":null,\"paidAt\":\"2026-04-06T17:17:46.000Z\",\"createdAt\":\"2026-04-06T17:01:35.000Z\",\"requested_amount\":12090000,\"pos_transaction_data\":null,\"source\":null,\"fees_breakdown\":[{\"amount\":191350,\"formula\":null,\"type\":\"paystack\"}],\"connect\":null,\"transaction_date\":\"2026-04-06T17:01:35.000Z\",\"plan_object\":[],\"subaccount\":[]}', 'success', '2026-04-06 17:18:09', '2026-04-06 17:01:34'),
(16, 20, 32, NULL, NULL, 120900.00, 'NGN', 'paystack', 'PS_1779295550_ef75f7070b164a97', NULL, 'failed', NULL, '2026-05-20 16:45:50'),
(17, 20, 32, NULL, NULL, 120900.00, 'NGN', 'paystack', 'PS_1779295844_d4de8f84e8555aef', NULL, 'failed', NULL, '2026-05-20 16:50:44'),
(18, 27, 36, 1, 'monthly', 150.00, 'NGN', 'paystack', 'PS_1780332328_tzltjbyd', '{\"reason\":\"stale_pending_timeout\",\"failed_at\":\"2026-06-02T00:01:50+00:00\",\"threshold_hours\":6}', 'failed', NULL, '2026-06-01 16:45:28'),
(19, 27, 36, 1, 'monthly', 150.00, 'NGN', 'paystack', 'PS_1780332889_movnbiim', '{\"reason\":\"stale_pending_timeout\",\"failed_at\":\"2026-06-02T00:01:50+00:00\",\"threshold_hours\":6}', 'failed', NULL, '2026-06-01 16:54:49'),
(20, 27, 36, 1, 'monthly', 150.00, 'NGN', 'paystack', 'PS_1780333607_qifzv4qt', '{\"id\":6211423374,\"domain\":\"live\",\"status\":\"success\",\"reference\":\"PS_1780333607_qifzv4qt\",\"receipt_number\":null,\"amount\":15000,\"message\":null,\"gateway_response\":\"Approved\",\"response_code\":null,\"paid_at\":\"2026-06-01T17:08:23.000Z\",\"created_at\":\"2026-06-01T17:06:48.000Z\",\"channel\":\"bank_transfer\",\"currency\":\"NGN\",\"ip_address\":\"98.97.76.189\",\"metadata\":{\"payment_id\":\"20\",\"subscription_id\":\"36\",\"restaurant_id\":\"27\",\"plan_id\":\"1\",\"billing_cycle\":\"monthly\",\"referrer\":\"https:\\/\\/laravel.our-menu.online\\/\"},\"log\":{\"start_time\":1780333614,\"time_spent\":89,\"attempts\":0,\"errors\":0,\"success\":true,\"mobile\":false,\"input\":[],\"history\":[{\"type\":\"pending\",\"message\":\"Payment in progress with bank\",\"time\":1},{\"type\":\"action\",\"message\":\"Set payment method to: bank_transfer\",\"time\":5},{\"type\":\"success\",\"message\":\"Successfully paid with bank_transfer\",\"time\":89}]},\"fees\":225,\"fees_split\":null,\"authorization\":{\"authorization_code\":\"AUTH_f4cb3p5xjn\",\"bin\":\"664XXX\",\"last4\":\"X069\",\"exp_month\":\"06\",\"exp_year\":\"2026\",\"channel\":\"bank_transfer\",\"card_type\":\"transfer\",\"bank\":\"Moniepoint MFB\",\"country_code\":\"NG\",\"brand\":\"Managed Account\",\"reusable\":false,\"signature\":null,\"account_name\":null,\"sender_bank\":\"Moniepoint MFB\",\"sender_country\":\"NG\",\"sender_bank_account_number\":\"XXXXXXX069\",\"sender_name\":\"7th trade hub\",\"narration\":\"Transfer from 7th trade hub\\/AT68_TRF2MPTja2fc2061495048218910720\",\"receiver_bank_account_number\":null,\"receiver_bank\":null},\"customer\":{\"id\":371401321,\"first_name\":null,\"last_name\":null,\"email\":\"admin@vendomecafe.our-menu.online\",\"customer_code\":\"CUS_1z4rcgh8s7mb7k4\",\"phone\":null,\"metadata\":null,\"risk_action\":\"default\",\"international_format_phone\":null},\"plan\":null,\"split\":[],\"order_id\":null,\"paidAt\":\"2026-06-01T17:08:23.000Z\",\"createdAt\":\"2026-06-01T17:06:48.000Z\",\"requested_amount\":15000,\"pos_transaction_data\":null,\"source\":null,\"fees_breakdown\":[{\"amount\":225,\"formula\":null,\"type\":\"paystack\"}],\"connect\":null,\"transaction_date\":\"2026-06-01T17:06:48.000Z\",\"plan_object\":[],\"subaccount\":[]}', 'success', '2026-06-01 17:08:27', '2026-06-01 17:06:47'),
(21, 27, 36, 1, 'monthly', 150.00, 'NGN', 'paystack', 'PS_1780358634_6b4c7545', '{\"id\":6212687653,\"domain\":\"live\",\"status\":\"success\",\"reference\":\"PS_1780358634_6b4c7545\",\"receipt_number\":null,\"amount\":15000,\"message\":null,\"gateway_response\":\"Approved\",\"response_code\":null,\"paid_at\":\"2026-06-02T00:05:06.000Z\",\"created_at\":\"2026-06-02T00:03:54.000Z\",\"channel\":\"bank_transfer\",\"currency\":\"NGN\",\"ip_address\":\"102.93.9.83\",\"metadata\":{\"payment_id\":\"21\",\"subscription_id\":\"36\",\"restaurant_id\":\"27\",\"plan_id\":\"1\",\"billing_cycle\":\"monthly\",\"referrer\":\"https:\\/\\/laravel.our-menu.online\\/\"},\"log\":{\"start_time\":1780358637,\"time_spent\":69,\"attempts\":0,\"errors\":0,\"success\":true,\"mobile\":false,\"input\":[],\"history\":[{\"type\":\"success\",\"message\":\"Successfully paid with bank_transfer\",\"time\":69}]},\"fees\":225,\"fees_split\":null,\"authorization\":{\"authorization_code\":\"AUTH_566420a7jt\",\"bin\":\"664XXX\",\"last4\":\"X069\",\"exp_month\":\"06\",\"exp_year\":\"2026\",\"channel\":\"bank_transfer\",\"card_type\":\"transfer\",\"bank\":\"Moniepoint MFB\",\"country_code\":\"NG\",\"brand\":\"Managed Account\",\"reusable\":false,\"signature\":null,\"account_name\":null,\"sender_bank\":\"Moniepoint MFB\",\"sender_country\":\"NG\",\"sender_bank_account_number\":\"XXXXXXX069\",\"sender_name\":\"7th trade hub\",\"narration\":\"Transfer from 7th trade hub\\/AT68_TRF2MPTja2fc2061599921942925312\",\"receiver_bank_account_number\":null,\"receiver_bank\":null},\"customer\":{\"id\":371401321,\"first_name\":null,\"last_name\":null,\"email\":\"admin@vendomecafe.our-menu.online\",\"customer_code\":\"CUS_1z4rcgh8s7mb7k4\",\"phone\":null,\"metadata\":null,\"risk_action\":\"default\",\"international_format_phone\":null},\"plan\":null,\"split\":[],\"order_id\":null,\"paidAt\":\"2026-06-02T00:05:06.000Z\",\"createdAt\":\"2026-06-02T00:03:54.000Z\",\"requested_amount\":15000,\"pos_transaction_data\":null,\"source\":null,\"fees_breakdown\":[{\"amount\":225,\"formula\":null,\"type\":\"paystack\"}],\"connect\":null,\"transaction_date\":\"2026-06-02T00:03:54.000Z\",\"plan_object\":[],\"subaccount\":[]}', 'success', '2026-06-02 00:05:12', '2026-06-01 23:47:46'),
(22, 4, 29, 3, 'annual', 200460.00, 'NGN', 'paystack', 'PS_1781271046_04c4c00f', '{\"reason\":\"stale_pending_timeout\",\"failed_at\":\"2026-06-12T20:00:04+00:00\",\"threshold_hours\":6}', 'failed', NULL, '2026-06-12 13:30:46'),
(23, 4, 29, 2, 'annual', 120900.00, 'NGN', 'paystack', 'PS_1781271075_019ea0d4', '{\"reason\":\"stale_pending_timeout\",\"failed_at\":\"2026-06-12T20:00:04+00:00\",\"threshold_hours\":6}', 'failed', NULL, '2026-06-12 13:31:15'),
(24, 4, 29, 3, 'monthly', 25700.00, 'NGN', 'paystack', 'PS_1781628526_0dafa059', NULL, 'failed', NULL, '2026-06-16 16:48:46'),
(25, 4, 29, 2, 'annual', 120900.00, 'NGN', 'paystack', 'PS_1781869139_5fadfd2b', NULL, 'success', '2026-08-19 15:00:30', '2026-06-19 11:38:59'),
(26, 25, 34, 2, 'annual', 120900.00, 'NGN', 'paystack', 'PS_1782938732_2a3cbec1', NULL, 'success', '2026-07-01 21:14:03', '2026-07-01 20:45:32'),
(27, 31, 40, 1, 'annual', 62400.00, 'NGN', 'paystack', 'PS_1786484827_b942c722', NULL, 'failed', NULL, '2026-08-11 21:47:06'),
(28, 31, 40, 3, 'annual', 200460.00, 'NGN', 'paystack', 'PS_1786484829_6710d58e', NULL, 'failed', NULL, '2026-08-11 21:47:09');

-- --------------------------------------------------------

--
-- Table structure for table `payment_fulfillment_receipts`
--

CREATE TABLE `payment_fulfillment_receipts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gateway` varchar(32) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `restaurant_id` int(11) DEFAULT NULL,
  `fulfillment_type` varchar(32) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_settings`
--

CREATE TABLE `payment_settings` (
  `id` int(11) NOT NULL,
  `gateway` varchar(50) NOT NULL COMMENT 'paystack or flutterwave',
  `is_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Enable/disable gateway',
  `test_mode` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Use test or live keys',
  `public_key_live` varchar(255) DEFAULT NULL COMMENT 'Live public key',
  `secret_key_live` text DEFAULT NULL COMMENT 'Live secret key (encrypted)',
  `webhook_secret_live` varchar(255) DEFAULT NULL COMMENT 'Live webhook secret',
  `public_key_test` varchar(255) DEFAULT NULL COMMENT 'Test public key',
  `secret_key_test` text DEFAULT NULL COMMENT 'Test secret key (encrypted)',
  `webhook_secret_test` varchar(255) DEFAULT NULL COMMENT 'Test webhook secret',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_settings`
--

INSERT INTO `payment_settings` (`id`, `gateway`, `is_active`, `test_mode`, `public_key_live`, `secret_key_live`, `webhook_secret_live`, `public_key_test`, `secret_key_test`, `webhook_secret_test`, `created_at`, `updated_at`) VALUES
(1, 'paystack', 1, 0, 'pk_live_a7cb9e736edaa1f9d08cbaac4202c7d4a3b3d84c', 'yfV/kyKrqFN5tPmedeJuCTo6eVZOazQyd2hEQXhldjlJNkxhOXl3OHoydFBBbVQ0VkpOakJDVTRIV2kwUndCT0s0aE1IQThRK2FCTkF0NHNmTlBwN2tnbWZvZ0xCcnBCcDlpS0tad1E9PQ==', NULL, 'sigsol2024', '1aMJkPa6LL4sKIi8NwaMkjo6MEc3OHEycTR4L2ZrUFUwSFFlMXBkNnFFN2FFekNhWHhWd3UydWd1WGE5QT0=', NULL, '2025-12-24 02:38:31', '2026-06-01 17:06:22'),
(2, 'flutterwave', 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 02:38:31', '2025-12-24 02:38:31');

-- --------------------------------------------------------

--
-- Table structure for table `pending_bank_transfers`
--

CREATE TABLE `pending_bank_transfers` (
  `id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `payment_type` varchar(20) NOT NULL DEFAULT 'order',
  `reservation_id` int(11) DEFAULT NULL,
  `cart_json` text NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(50) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `delivery_address` text NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `delivery_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','customer_claimed','approved','expired','cancelled') NOT NULL DEFAULT 'pending',
  `customer_claimed_at` datetime DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `approved_by_manager_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pending_online_payments`
--

CREATE TABLE `pending_online_payments` (
  `id` int(11) NOT NULL,
  `reference` varchar(80) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `payment_type` varchar(20) NOT NULL DEFAULT 'order',
  `reservation_id` int(11) DEFAULT NULL,
  `gateway` varchar(50) NOT NULL,
  `cart_json` text NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(50) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `delivery_address` text NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `delivery_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `public_api_rate_events`
--

CREATE TABLE `public_api_rate_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(64) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qr_code_scans`
--

CREATE TABLE `qr_code_scans` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `device_type` varchar(50) DEFAULT NULL,
  `browser` varchar(100) DEFAULT NULL,
  `os` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `scanned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `qr_code_scans`
--

INSERT INTO `qr_code_scans` (`id`, `restaurant_id`, `ip_address`, `user_agent`, `device_type`, `browser`, `os`, `country`, `city`, `latitude`, `longitude`, `scanned_at`) VALUES
(1, 2, '197.211.59.73', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', 'Mobile', 'Chrome', 'Linux', 'Nigeria', 'Lagos', 6.44740000, 3.39030000, '2025-12-24 00:07:30'),
(2, 2, '197.211.59.73', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', 'Mobile', 'Chrome', 'Linux', 'Nigeria', 'Lagos', 6.44740000, 3.39030000, '2025-12-24 00:11:57'),
(3, 3, '102.89.83.48', 'QR Scanner Android', 'Mobile', 'Unknown', 'Android', 'Nigeria', 'Lagos', 6.44740000, 3.39030000, '2026-03-10 14:41:01'),
(4, 3, '102.89.83.48', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', 'Mobile', 'Chrome', 'Linux', 'Nigeria', 'Lagos', 6.44740000, 3.39030000, '2026-03-10 14:41:07'),
(5, 3, '102.89.83.48', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', 'Mobile', 'Chrome', 'Linux', 'Nigeria', 'Lagos', 6.44740000, 3.39030000, '2026-03-10 16:04:10'),
(6, 2, '102.89.82.149', 'QR Scanner Android', 'Mobile', 'Unknown', 'Android', 'Nigeria', 'Lagos', 6.44740000, 3.39030000, '2026-03-11 14:09:27'),
(7, 2, '102.89.82.149', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', 'Mobile', 'Chrome', 'Linux', 'Nigeria', 'Lagos', 6.44740000, 3.39030000, '2026-03-11 14:09:29'),
(10, 2, '102.88.112.82', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', 'Mobile', 'Chrome', 'Linux', 'Nigeria', 'Lagos', 6.44740000, 3.39030000, '2026-03-20 16:33:40'),
(11, 2, '102.88.112.82', 'QR Scanner Android', 'Mobile', 'Unknown', 'Android', 'Nigeria', 'Lagos', 6.44740000, 3.39030000, '2026-03-20 16:33:43'),
(12, 13, '190.2.149.91', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'Desktop', 'Chrome', 'Windows', 'Netherlands', 'Naaldwijk', 51.99680000, 4.20570000, '2026-03-20 17:03:54'),
(13, 13, '190.2.149.91', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'Desktop', 'Chrome', 'Windows', 'Netherlands', 'Naaldwijk', 51.99680000, 4.20570000, '2026-03-20 17:04:12'),
(14, 13, '102.88.112.82', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', 'Mobile', 'Chrome', 'Linux', 'Nigeria', 'Lagos', 6.44740000, 3.39030000, '2026-03-20 17:09:35'),
(15, 13, '102.91.93.192', 'Mozilla/5.0 (Linux; U; Android 12; TECNO BF6 Build/SP1A.210812.001; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/145.0.7632.162 Mobile Safari/537.36 OPR/96.0.2254.79777', 'Mobile', 'Chrome', 'Linux', 'Nigeria', 'Funtua', 11.52350000, 7.31174000, '2026-03-21 13:17:09'),
(16, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-02 23:04:07'),
(17, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-02 23:04:20'),
(18, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-02 23:04:35'),
(19, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-02 23:05:15'),
(20, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-02 23:06:51'),
(21, 19, '102.88.112.57', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-02 23:14:10'),
(22, 19, '102.88.112.57', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-02 23:14:34'),
(23, 19, '146.75.146.0', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-02 23:18:42'),
(24, 19, '176.4.197.193', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/148.0.7778.166 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-02 23:19:11'),
(25, 19, '176.4.197.193', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_4_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/148.0.7778.166 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-02 23:19:17'),
(26, 19, '197.211.59.200', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-03 00:26:30'),
(27, 19, '102.89.84.87', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-03 00:31:22'),
(28, 19, '102.89.68.170', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-03 00:40:13'),
(29, 19, '197.211.59.200', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-03 00:43:35'),
(30, 19, '105.112.26.241', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-03 00:53:23'),
(31, 19, '105.113.114.140', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-03 01:18:44'),
(32, 25, '197.211.59.64', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-03 12:40:35'),
(33, 19, '129.205.124.246', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-03 20:20:24'),
(34, 19, '102.89.68.76', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-03 21:16:09'),
(35, 19, '102.89.69.27', 'Mozilla/5.0 (Linux; Android 16; SM-A546E Build/BP2A.250605.031.A3; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/148.0.7778.178 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-03 22:09:40'),
(36, 19, '102.89.84.163', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-03 22:48:27'),
(37, 19, '197.211.59.126', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-03 23:22:14'),
(38, 19, '102.89.82.96', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-03 23:41:35'),
(39, 19, '102.89.83.245', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-04 00:46:04'),
(40, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-04 00:56:17'),
(41, 21, '135.129.124.220', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-05 13:37:10'),
(42, 19, '105.113.114.150', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-05 23:13:01'),
(43, 19, '105.112.178.28', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-05 23:22:13'),
(44, 19, '41.190.14.185', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-06 00:36:45'),
(45, 19, '166.198.157.3', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-06 00:38:40'),
(46, 19, '185.99.26.76', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-06 00:44:51'),
(47, 19, '102.88.113.173', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-06 01:19:12'),
(48, 25, '105.112.190.148', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148', 'mobile', 'unknown', 'mac', NULL, NULL, NULL, NULL, '2026-06-06 01:47:37'),
(49, 25, '129.205.124.229', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/148.0.7778.166 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-06 09:17:43'),
(50, 25, '102.89.69.192', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-07 15:52:18'),
(51, 25, '102.88.110.138', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-07 18:25:22'),
(52, 25, '102.89.68.242', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-07 21:11:15'),
(53, 25, '105.113.95.245', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-07 21:23:32'),
(54, 25, '41.76.83.222', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-07 21:23:35'),
(55, 25, '105.112.176.159', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-08 19:57:34'),
(56, 25, '105.112.73.185', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-08 19:57:56'),
(57, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-09 18:09:54'),
(58, 19, '105.112.76.246', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-09 19:38:52'),
(59, 19, '102.89.76.168', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.45 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-09 19:39:35'),
(60, 19, '41.190.12.15', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-09 22:09:34'),
(61, 19, '105.112.31.128', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-09 22:11:36'),
(62, 25, '102.93.7.126', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-09 22:16:16'),
(63, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-09 23:20:37'),
(64, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-09 23:29:14'),
(65, 19, '102.88.110.102', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-09 23:29:56'),
(66, 19, '197.211.59.95', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-09 23:45:35'),
(67, 19, '105.113.99.159', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-09 23:51:54'),
(68, 19, '102.89.83.238', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-09 23:52:16'),
(69, 19, '102.89.83.178', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-10 01:22:38'),
(70, 19, '102.88.109.10', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-10 02:42:17'),
(71, 19, '158.173.67.98', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_2_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/148.0.7778.166 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-10 02:58:28'),
(72, 25, '102.88.108.18', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-10 15:13:20'),
(73, 25, '102.88.111.156', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-10 15:13:32'),
(74, 25, '102.93.12.245', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_6_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.45 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-10 17:14:10'),
(75, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-10 17:35:08'),
(76, 19, '107.127.28.115', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/601.2.4 (KHTML, like Gecko) Version/9.0.1 Safari/601.2.4 facebookexternalhit/1.1 Facebot Twitterbot/1.0', 'desktop', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-10 17:45:32'),
(77, 19, '107.127.28.115', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-10 17:45:35'),
(78, 19, '107.127.28.81', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-10 18:21:00'),
(79, 19, '105.113.81.137', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-11 00:39:14'),
(80, 19, '105.112.176.3', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-11 01:18:33'),
(81, 19, '102.89.76.80', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-11 01:22:33'),
(82, 19, '102.88.114.252', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-11 01:59:11'),
(83, 19, '102.88.115.113', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-11 02:13:23'),
(84, 25, '105.115.11.101', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-11 15:07:03'),
(85, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-11 18:00:30'),
(86, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-11 19:48:54'),
(87, 25, '197.210.71.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-12 13:01:56'),
(88, 25, '102.89.82.146', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-12 13:18:16'),
(89, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-12 13:48:22'),
(90, 19, '105.112.176.43', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-12 18:14:25'),
(91, 19, '102.89.82.117', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-12 18:14:37'),
(92, 19, '105.112.176.43', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-12 18:27:06'),
(93, 19, '197.149.86.246', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-06-12 18:49:51'),
(94, 19, '197.149.86.246', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-12 18:50:02'),
(95, 19, '197.149.86.246', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-06-12 18:54:00'),
(96, 19, '197.149.86.246', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-12 18:54:04'),
(97, 19, '105.112.176.43', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-12 19:21:03'),
(98, 19, '102.88.108.22', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-12 23:57:51'),
(99, 19, '102.89.82.152', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-13 00:41:28'),
(100, 19, '197.149.86.246', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-13 17:59:58'),
(101, 19, '197.149.86.246', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-13 18:00:00'),
(102, 25, '105.112.203.231', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-13 20:06:27'),
(103, 25, '102.88.114.239', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-13 20:54:53'),
(104, 19, '102.91.4.179', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-13 22:16:38'),
(105, 19, '102.88.112.231', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-13 22:17:37'),
(106, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-13 22:28:33'),
(107, 25, '41.76.83.222', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-13 22:35:36'),
(108, 25, '105.115.6.195', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-13 22:35:48'),
(109, 19, '102.88.113.72', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-13 22:36:19'),
(110, 19, '102.88.111.236', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-13 23:11:12'),
(111, 19, '102.89.82.215', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-14 00:04:03'),
(112, 19, '102.89.75.28', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-14 00:05:30'),
(113, 19, '102.89.75.28', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-14 00:23:22'),
(114, 19, '31.171.130.138', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-14 00:35:40'),
(115, 19, '102.93.9.241', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-14 00:35:51'),
(116, 19, '104.28.87.74', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-14 00:39:13'),
(117, 19, '102.88.113.254', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-14 01:25:55'),
(118, 19, '197.149.86.246', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-06-14 01:34:06'),
(119, 19, '197.149.86.246', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-06-14 01:34:11'),
(120, 19, '197.149.86.246', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-14 01:34:18'),
(121, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-14 16:02:04'),
(122, 21, '98.97.79.147', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.7 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-14 16:03:42'),
(123, 21, '98.97.79.147', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.7 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-14 16:04:24'),
(124, 19, '197.149.86.246', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-14 18:25:58'),
(125, 19, '197.149.86.246', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-06-15 07:26:00'),
(126, 25, '105.112.201.252', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-15 17:29:33'),
(127, 25, '102.89.83.11', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-15 20:38:01'),
(128, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-16 09:34:24'),
(129, 19, '102.93.7.175', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-16 17:15:33'),
(130, 25, '102.22.221.106', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-16 21:19:38'),
(131, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-16 21:49:13'),
(132, 19, '102.89.69.111', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-16 22:59:56'),
(133, 19, '102.89.84.116', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-16 23:58:28'),
(134, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-17 02:34:57'),
(135, 19, '105.113.116.151', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-17 02:36:02'),
(136, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-17 02:45:35'),
(137, 19, '102.89.76.146', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-17 02:56:09'),
(138, 25, '102.89.84.216', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-17 15:43:04'),
(139, 25, '105.112.24.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-17 17:54:32'),
(140, 19, '105.112.178.252', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-18 00:21:15'),
(141, 19, '102.88.111.163', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-18 00:53:30'),
(142, 19, '197.211.52.244', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-18 00:54:06'),
(143, 19, '102.89.83.233', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-18 01:14:28'),
(144, 19, '105.113.94.239', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-18 01:15:12'),
(145, 19, '130.117.88.77', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-18 01:49:24'),
(146, 19, '105.112.20.86', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-18 02:06:36'),
(147, 19, '102.89.83.18', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/144.0.7559.95 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-18 02:12:12'),
(148, 19, '102.88.109.75', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.137 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-18 02:12:22'),
(149, 19, '102.88.113.54', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-18 02:12:31'),
(150, 21, '197.210.71.102', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.7 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-18 12:42:33'),
(151, 25, '105.113.99.122', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-18 18:35:47'),
(152, 25, '129.205.124.216', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_2_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/137.0.7151.107 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-19 12:12:51'),
(153, 25, '98.97.79.161', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-19 16:08:05'),
(154, 19, '102.90.101.222', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.137 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-19 16:27:12'),
(155, 19, '102.89.84.139', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-19 16:36:34'),
(156, 25, '102.90.102.32', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_3_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.3.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-19 18:49:23'),
(157, 25, '129.205.114.5', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-19 19:09:36'),
(158, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-19 20:41:08'),
(159, 19, '105.113.128.90', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-19 23:40:35'),
(160, 19, '105.113.128.90', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-19 23:46:30'),
(161, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-20 00:59:53'),
(162, 19, '105.112.20.124', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-20 01:17:47'),
(163, 19, '105.112.176.157', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-20 03:10:13'),
(164, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-20 10:51:16'),
(165, 25, '102.88.114.94', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-20 15:24:51'),
(166, 25, '102.93.10.227', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-20 15:39:47'),
(167, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-20 21:26:04'),
(168, 25, '102.88.110.50', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-20 22:13:22'),
(169, 25, '105.112.178.17', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-20 22:37:22'),
(170, 25, '102.89.75.75', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-20 22:38:07'),
(171, 19, '102.88.114.36', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-20 23:31:20'),
(172, 19, '102.88.108.136', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-20 23:36:30'),
(173, 19, '102.89.82.158', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-20 23:36:47'),
(174, 19, '104.28.60.153', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-21 00:00:27'),
(175, 19, '102.88.111.45', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-21 00:23:49'),
(176, 19, '102.88.114.55', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-21 01:03:57'),
(177, 19, '102.89.83.192', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-21 01:34:03'),
(178, 19, '197.149.86.246', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-06-21 03:55:31'),
(179, 19, '197.149.86.246', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-21 03:55:32'),
(180, 25, '102.222.96.165', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-21 18:42:15'),
(181, 25, '105.112.23.17', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2.1 Mobile/15E148 Safari/604.1 Brave', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-21 18:42:52'),
(182, 25, '102.88.113.112', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-21 18:43:04'),
(183, 25, '102.222.96.165', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-21 18:43:45'),
(184, 25, '102.88.54.243', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-21 20:00:00'),
(185, 25, '102.88.54.243', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-21 20:00:32'),
(186, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-23 17:29:06'),
(187, 25, '102.88.109.251', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-23 17:46:01'),
(188, 25, '41.76.83.222', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-23 19:35:59'),
(189, 19, '102.89.69.49', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-23 22:27:45'),
(190, 19, '102.88.114.45', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-23 23:07:42'),
(191, 19, '102.88.113.95', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-23 23:53:19'),
(192, 19, '102.89.84.128', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-24 00:59:59'),
(193, 19, '102.93.8.22', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-24 01:04:33'),
(194, 19, '102.91.102.116', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-24 04:51:21'),
(195, 19, '102.91.102.116', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-24 04:51:25'),
(196, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-25 12:05:46'),
(197, 25, '5.38.23.64', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-25 16:50:34'),
(198, 25, '102.88.110.152', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.45 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-26 17:53:18'),
(199, 25, '102.88.108.253', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-26 17:56:16'),
(200, 25, '102.88.112.7', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.137 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-26 18:32:28'),
(201, 25, '105.113.58.120', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-26 19:08:32'),
(202, 25, '105.112.176.200', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-26 19:08:39'),
(203, 25, '172.225.243.60', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-26 19:23:19'),
(204, 25, '102.90.102.212', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.137 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-26 19:24:07'),
(205, 25, '102.90.101.48', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-26 19:30:29'),
(206, 25, '102.89.76.84', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-26 20:16:43'),
(207, 25, '197.211.59.73', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-26 20:18:10'),
(208, 25, '105.112.23.63', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-26 20:20:06'),
(209, 25, '102.88.113.42', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-26 20:32:24'),
(210, 25, '102.88.114.230', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-26 20:42:14'),
(211, 25, '102.88.115.118', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-26 20:42:55'),
(212, 25, '102.88.114.220', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-26 22:08:29'),
(213, 25, '142.169.77.122', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-26 22:08:54'),
(214, 19, '65.43.210.48', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-26 23:42:48'),
(215, 19, '104.28.96.118', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-26 23:43:27');
INSERT INTO `qr_code_scans` (`id`, `restaurant_id`, `ip_address`, `user_agent`, `device_type`, `browser`, `os`, `country`, `city`, `latitude`, `longitude`, `scanned_at`) VALUES
(216, 19, '102.93.11.210', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-26 23:54:49'),
(217, 19, '102.89.69.56', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-27 00:17:54'),
(218, 19, '102.89.76.153', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-27 00:18:35'),
(219, 19, '102.89.76.153', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-27 00:29:19'),
(220, 19, '102.89.69.117', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-27 00:34:27'),
(221, 19, '102.89.68.23', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-27 00:52:16'),
(222, 19, '102.90.103.40', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-27 01:49:03'),
(223, 19, '105.112.71.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-27 03:43:33'),
(224, 25, '102.89.76.119', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-27 16:44:02'),
(225, 25, '105.112.23.98', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-27 19:45:24'),
(226, 19, '102.93.7.169', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-27 22:16:27'),
(227, 19, '102.89.69.212', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.137 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-28 00:00:43'),
(228, 19, '185.111.108.135', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-28 00:43:37'),
(229, 19, '102.89.69.212', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.137 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-28 00:44:16'),
(230, 19, '102.89.69.212', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_4_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.137 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-28 01:08:08'),
(231, 19, '102.93.7.180', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-28 01:44:14'),
(232, 19, '102.89.75.87', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-28 02:03:27'),
(233, 19, '105.112.179.114', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-28 02:11:44'),
(234, 19, '105.112.176.96', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-28 03:03:09'),
(235, 25, '102.89.69.169', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-28 15:43:22'),
(236, 25, '102.88.112.97', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-28 15:47:59'),
(237, 25, '197.211.59.85', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-28 16:15:54'),
(238, 25, '102.88.108.201', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-28 16:36:08'),
(239, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-28 16:38:24'),
(240, 25, '102.89.82.175', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-06-28 18:32:07'),
(241, 25, '129.222.206.44', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-28 21:46:26'),
(242, 25, '102.90.98.2', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-29 13:50:44'),
(243, 25, '105.112.190.240', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-06-29 17:09:01'),
(244, 19, '166.196.54.132', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-01 00:24:04'),
(245, 19, '102.88.112.156', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-01 00:34:26'),
(246, 19, '197.211.52.197', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-01 00:46:10'),
(247, 25, '102.89.82.149', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-01 09:37:44'),
(248, 25, '197.210.71.226', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-01 12:49:21'),
(249, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-01 13:04:18'),
(250, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-01 17:10:38'),
(251, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-01 20:36:12'),
(252, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-01 21:25:21'),
(253, 19, '102.88.113.241', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-01 22:55:14'),
(254, 19, '102.89.69.54', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-01 23:59:22'),
(255, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-02 00:07:08'),
(256, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-02 17:24:23'),
(257, 25, '102.89.68.25', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-02 20:09:29'),
(258, 19, '102.93.10.57', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-03 23:01:41'),
(259, 19, '105.112.76.171', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-03 23:17:19'),
(260, 19, '102.89.82.85', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-07-03 23:22:31'),
(261, 19, '102.89.82.85', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-03 23:22:32'),
(262, 19, '102.89.82.28', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-03 23:35:22'),
(263, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-03 23:46:07'),
(264, 19, '197.211.59.68', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-03 23:51:14'),
(265, 19, '102.89.84.25', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-03 23:59:30'),
(266, 19, '197.149.86.246', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-07-04 01:27:27'),
(267, 19, '197.149.86.246', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-04 01:27:35'),
(268, 25, '102.89.69.102', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-04 15:23:15'),
(269, 19, '102.90.97.129', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-05 01:22:36'),
(270, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-05 02:46:13'),
(271, 25, '197.211.59.92', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-05 14:18:47'),
(272, 25, '102.89.68.103', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-05 14:21:13'),
(273, 25, '102.90.99.197', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-05 14:23:08'),
(274, 25, '197.211.59.92', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-05 14:26:23'),
(275, 25, '197.211.59.101', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-05 17:59:19'),
(276, 25, '197.210.70.191', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-05 18:35:03'),
(277, 25, '102.89.82.96', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-05 20:03:26'),
(278, 25, '102.90.99.197', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-05 20:05:02'),
(279, 25, '102.89.76.49', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_4_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.45 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-05 20:30:41'),
(280, 25, '174.208.227.81', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-05 21:33:46'),
(281, 25, '102.88.114.138', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-05 21:34:30'),
(282, 25, '146.75.164.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-05 21:36:47'),
(283, 19, '102.93.7.58', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-05 22:12:28'),
(284, 19, '105.113.98.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-05 22:15:25'),
(285, 19, '102.89.84.59', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-05 22:43:26'),
(286, 19, '102.88.113.125', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.137 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-06 00:08:11'),
(287, 25, '102.89.69.24', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-06 18:50:12'),
(288, 25, '105.112.179.78', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.137 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-06 19:08:11'),
(289, 25, '102.88.108.85', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-06 19:28:45'),
(290, 25, '105.115.9.190', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-07 15:17:31'),
(291, 25, '102.93.14.61', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-07 15:48:38'),
(292, 25, '105.115.9.190', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-07 16:16:14'),
(293, 19, '107.127.28.2', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-07 23:04:47'),
(294, 19, '102.93.13.227', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-07 23:26:16'),
(295, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-07 23:51:52'),
(296, 19, '102.89.23.162', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-07 23:55:19'),
(297, 19, '174.208.231.103', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-07 23:58:44'),
(298, 19, '105.112.73.38', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Snapchat/13.91.1.0 (like Safari/8623.1.14.10.9, panda)', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-08 00:01:23'),
(299, 19, '102.88.114.185', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-08 00:04:24'),
(300, 19, '105.112.29.175', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-08 00:12:57'),
(301, 19, '129.205.124.229', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-08 00:13:13'),
(302, 19, '105.113.116.233', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-08 00:24:38'),
(303, 19, '102.88.111.94', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-08 00:46:36'),
(304, 19, '105.113.57.101', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-08 01:03:33'),
(305, 19, '197.211.59.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-08 01:07:27'),
(306, 19, '197.211.59.117', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-08 01:40:21'),
(307, 19, '105.112.199.79', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-08 01:43:56'),
(308, 19, '102.90.102.92', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-08 01:49:59'),
(309, 19, '197.210.71.43', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-08 02:27:02'),
(310, 25, '102.89.82.207', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_2_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-08 14:24:14'),
(311, 19, '102.88.114.104', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-08 19:39:04'),
(312, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-08 20:56:08'),
(313, 25, '197.211.59.67', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-08 20:56:55'),
(314, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-08 22:20:52'),
(315, 25, '173.239.254.189', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-08 22:20:54'),
(316, 19, '102.89.82.46', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-08 23:29:36'),
(317, 19, '102.93.7.170', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-08 23:45:22'),
(318, 19, '102.89.83.48', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-09 00:57:27'),
(319, 19, '102.88.108.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/150.0.7871.51 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-09 01:22:54'),
(320, 25, '102.89.84.81', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-09 16:08:47'),
(321, 25, '102.89.85.172', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-09 18:30:17'),
(322, 25, '172.225.243.60', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-09 18:31:37'),
(323, 25, '102.88.113.118', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-09 19:42:17'),
(324, 25, '102.93.8.131', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-09 20:22:53'),
(325, 25, '102.90.99.98', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-09 20:23:11'),
(326, 25, '129.205.124.220', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-09 21:38:47'),
(327, 19, '102.88.114.157', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-10 22:08:23'),
(328, 19, '102.88.114.157', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-10 22:09:59'),
(329, 19, '102.89.69.134', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-10 23:51:51'),
(330, 19, '197.149.86.246', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-11 00:30:02'),
(331, 19, '102.88.110.20', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-11 00:35:21'),
(332, 19, '102.88.112.225', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-11 00:36:24'),
(333, 19, '104.28.88.88', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-11 01:09:51'),
(334, 19, '197.210.71.249', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-11 01:36:15'),
(335, 19, '102.89.69.70', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-11 01:53:25'),
(336, 25, '102.89.84.58', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-11 13:30:51'),
(337, 25, '102.89.82.178', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/150.0.7871.51 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-11 14:39:52'),
(338, 25, '196.1.176.190', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-11 15:31:38'),
(339, 25, '102.88.109.196', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-11 15:55:59'),
(340, 25, '41.76.83.222', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-11 16:01:58'),
(341, 25, '105.112.176.236', 'Mozilla/5.0 (Linux; U; Android 15; en-us; TECNO CL6k Build/AP3A.240905.015.A2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.6367.179 Mobile Safari/537.36 PHX/21.7', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-11 19:01:09'),
(342, 25, '102.89.69.234', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-11 19:01:37'),
(343, 25, '102.88.115.197', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-11 20:43:42'),
(344, 19, '102.88.110.40', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-12 07:41:25'),
(345, 25, '102.89.76.244', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-12 18:46:25'),
(346, 25, '129.205.124.222', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-12 20:30:58'),
(347, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-14 19:49:43'),
(348, 25, '102.88.114.65', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-14 20:01:23'),
(349, 19, '102.89.83.29', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-14 23:24:02'),
(350, 19, '102.89.84.114', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-15 00:21:15'),
(351, 19, '105.112.76.157', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-07-15 00:24:18'),
(352, 19, '105.112.76.157', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-15 00:24:18'),
(353, 19, '105.112.76.157', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-07-15 00:32:34'),
(354, 19, '105.112.76.157', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-07-15 00:34:45'),
(355, 19, '102.89.83.216', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 00:38:37'),
(356, 19, '102.89.83.216', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 00:39:01'),
(357, 19, '105.112.203.85', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 01:03:17'),
(358, 19, '102.89.82.250', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-15 01:25:26'),
(359, 19, '102.89.68.94', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 01:39:05'),
(360, 19, '102.89.82.141', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/141.0.7390.41 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 01:39:21'),
(361, 19, '102.89.68.94', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 01:42:32'),
(362, 19, '102.89.68.94', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 02:20:51'),
(363, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 04:10:58'),
(364, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 04:11:21'),
(365, 19, '197.149.86.246', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-07-15 07:39:29'),
(366, 19, '197.149.86.246', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-15 07:39:33'),
(367, 19, '102.89.69.104', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-15 15:42:32'),
(368, 25, '41.190.14.115', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 19:03:32'),
(369, 25, '102.89.76.40', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 19:10:13'),
(370, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-15 19:14:19'),
(371, 25, '104.28.60.153', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 19:15:12'),
(372, 25, '104.28.87.72', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 19:15:35'),
(373, 25, '102.88.111.164', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 19:26:39'),
(374, 25, '102.89.69.104', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 19:39:49'),
(375, 25, '197.211.59.193', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 19:39:53'),
(376, 25, '105.112.199.44', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 20:38:00'),
(377, 25, '102.89.69.251', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 22:05:33'),
(378, 25, '102.88.109.187', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 22:18:16'),
(379, 25, '105.112.183.180', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 22:19:38'),
(380, 25, '102.89.68.120', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 22:21:44'),
(381, 19, '102.88.114.173', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-15 22:27:01'),
(382, 19, '197.211.59.188', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 22:27:52'),
(383, 19, '197.211.59.127', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 22:53:57'),
(384, 19, '102.89.68.85', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 23:50:32'),
(385, 19, '102.89.76.180', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1 Brave', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 23:55:36'),
(386, 19, '174.198.10.213', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/150.0.7871.51 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-15 23:58:13'),
(387, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-16 00:17:06'),
(388, 19, '102.89.85.34', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-16 00:28:19'),
(389, 19, '102.89.83.165', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/150.0.7871.113 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-16 00:49:22'),
(390, 25, '104.28.88.91', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-16 19:52:47'),
(391, 25, '197.211.59.68', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-17 15:50:07'),
(392, 25, '102.88.109.162', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-17 18:33:23'),
(393, 25, '102.88.109.162', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-17 18:33:31'),
(394, 25, '102.88.111.219', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-17 22:26:25'),
(395, 25, '102.88.111.219', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-17 22:29:56'),
(396, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-17 22:30:16'),
(397, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-17 22:30:52'),
(398, 19, '197.149.86.246', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-17 22:31:03'),
(399, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-17 22:37:15'),
(400, 19, '194.124.76.98', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.0 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-17 23:55:00'),
(401, 19, '197.149.86.246', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-17 23:55:58'),
(402, 19, '194.124.76.98', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.0 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-17 23:56:04'),
(403, 19, '172.225.243.60', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-17 23:59:01'),
(404, 19, '102.88.113.139', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-18 00:38:47'),
(405, 19, '172.225.212.226', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-18 00:47:37'),
(406, 19, '102.89.68.68', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-18 01:07:25'),
(407, 19, '102.89.83.143', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-18 01:53:35'),
(408, 19, '102.88.108.198', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-18 01:55:25'),
(409, 19, '102.89.75.76', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-18 01:56:22'),
(410, 25, '105.115.6.23', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-18 13:04:23'),
(411, 21, '98.97.79.15', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-18 14:26:10'),
(412, 21, '98.97.79.15', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_8 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.8 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-18 14:55:50'),
(413, 21, '98.97.79.15', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_8 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.8 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-18 17:31:55'),
(414, 21, '105.113.118.157', 'Mozilla/5.0 (Linux; U; Android 15; en-us; SM-G998U1 Build/AP3A.240905.015.A2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.6723.58 Mobile Safari/537.36 PHX/21.7', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-18 17:45:28'),
(415, 21, '98.97.79.15', 'Mozilla/5.0 (Linux; U; Android 15; en-us; SM-G998U1 Build/AP3A.240905.015.A2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.6723.58 Mobile Safari/537.36 PHX/21.7', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-18 17:45:29'),
(416, 25, '197.211.59.89', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-18 18:40:13'),
(417, 25, '172.225.99.225', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-18 19:13:22'),
(418, 21, '98.97.79.15', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-18 19:22:39'),
(419, 21, '105.113.84.200', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-18 19:24:50'),
(420, 25, '102.88.109.205', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/150.0.7871.51 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-18 20:44:15'),
(421, 21, '98.97.79.15', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-18 21:27:55'),
(422, 19, '102.93.10.159', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_2_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.45 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-18 23:27:18'),
(423, 19, '104.28.88.91', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 01:39:01'),
(424, 19, '104.28.96.122', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_1_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.1.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 01:39:15'),
(425, 19, '105.113.75.216', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 01:40:45'),
(426, 19, '102.88.108.137', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36 OPR/100.0.0.0', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-19 02:00:00'),
(427, 19, '105.113.117.9', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-19 02:02:16'),
(435, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-19 14:59:45');
INSERT INTO `qr_code_scans` (`id`, `restaurant_id`, `ip_address`, `user_agent`, `device_type`, `browser`, `os`, `country`, `city`, `latitude`, `longitude`, `scanned_at`) VALUES
(436, 21, '98.97.79.15', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_8 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.8 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 15:11:36'),
(437, 25, '102.89.83.130', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 15:33:43'),
(438, 25, '102.88.109.168', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 16:45:33'),
(439, 25, '105.112.24.50', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 17:01:09'),
(440, 25, '197.253.58.226', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-19 17:03:49'),
(441, 25, '105.112.67.109', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 17:06:33'),
(442, 25, '102.88.108.116', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-19 17:27:31'),
(443, 25, '104.28.34.128', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 17:45:39'),
(444, 25, '102.89.46.247', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:16:16'),
(445, 25, '102.89.46.247', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:16:23'),
(446, 25, '102.89.46.247', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:16:33'),
(447, 25, '102.89.76.147', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:17:18'),
(448, 25, '102.89.68.219', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:17:26'),
(449, 25, '102.89.75.80', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/150.0.7871.113 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:23:28'),
(450, 25, '102.89.75.191', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:23:30'),
(451, 25, '197.211.59.112', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:23:47'),
(452, 25, '104.28.87.74', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:25:50'),
(453, 25, '102.88.113.162', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:27:13'),
(454, 25, '104.28.34.127', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:31:03'),
(455, 25, '102.89.69.9', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:31:22'),
(456, 25, '102.89.82.112', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:31:31'),
(457, 25, '102.88.109.66', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:32:05'),
(458, 25, '92.119.18.171', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:32:14'),
(459, 25, '102.89.75.165', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:32:15'),
(460, 25, '129.205.124.248', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:32:31'),
(461, 25, '102.88.108.225', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:35:55'),
(462, 25, '16.56.128.252', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:36:25'),
(463, 25, '102.89.69.201', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:36:42'),
(464, 25, '102.89.68.169', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:36:59'),
(465, 25, '102.93.10.210', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:45:38'),
(466, 25, '102.89.85.22', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:49:09'),
(467, 25, '102.88.108.242', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:49:39'),
(468, 25, '15.181.145.249', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:49:48'),
(469, 25, '102.89.82.5', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:51:34'),
(470, 25, '102.88.111.236', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-19 18:51:56'),
(471, 25, '102.89.68.176', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:52:27'),
(472, 25, '102.88.108.225', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:52:31'),
(473, 25, '105.112.190.238', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-19 18:52:58'),
(474, 25, '102.89.76.146', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:54:48'),
(475, 25, '102.89.82.63', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:56:11'),
(476, 25, '102.89.85.97', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Safari/605.1.15', 'desktop', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:57:16'),
(477, 25, '102.89.85.97', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'desktop', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 18:57:17'),
(478, 25, '102.129.152.59', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 19:01:58'),
(479, 25, '102.89.75.204', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 19:02:10'),
(480, 25, '197.211.59.114', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 19:02:22'),
(481, 25, '102.88.111.76', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 19:02:31'),
(482, 25, '102.89.69.39', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 19:02:53'),
(483, 25, '102.88.113.105', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 19:03:29'),
(484, 25, '172.225.179.87', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 19:04:52'),
(485, 25, '102.89.75.114', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 19:14:05'),
(486, 25, '105.112.30.69', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 19:14:15'),
(487, 25, '89.35.25.45', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 19:19:37'),
(488, 25, '105.115.3.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 19:31:24'),
(489, 25, '102.89.83.151', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/151.0.7922.25 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 19:33:57'),
(490, 25, '102.88.108.47', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 19:33:58'),
(491, 25, '102.89.83.255', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_2_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/151.0.7922.25 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 19:34:30'),
(492, 25, '102.88.110.112', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 19:37:39'),
(493, 25, '102.89.83.125', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 19:41:00'),
(494, 25, '102.88.108.57', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 19:45:57'),
(495, 25, '102.89.85.233', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 19:46:24'),
(496, 25, '102.89.83.137', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_0_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/150.0.7871.113 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 19:46:31'),
(497, 25, '102.88.109.143', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 19:55:47'),
(498, 25, '102.88.110.75', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 20:02:33'),
(499, 25, '102.88.108.147', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_4_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 20:06:38'),
(500, 25, '102.89.76.233', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 20:08:43'),
(501, 25, '197.211.59.200', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 20:17:45'),
(502, 25, '102.89.76.39', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 20:26:20'),
(503, 25, '102.90.97.21', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 20:42:09'),
(504, 25, '105.115.1.87', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 20:46:44'),
(505, 25, '102.88.111.107', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 20:47:04'),
(506, 25, '102.89.83.250', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 20:47:27'),
(507, 25, '105.112.203.196', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 20:59:18'),
(508, 25, '191.96.227.97', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_3_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/150.0.7871.51 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 21:24:46'),
(509, 25, '105.112.176.79', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 21:25:03'),
(510, 25, '102.89.85.202', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 21:25:09'),
(511, 25, '158.173.25.144', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-19 21:25:28'),
(512, 25, '41.76.83.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-19 23:31:41'),
(513, 21, '102.93.8.17', 'Mozilla/5.0 (Linux; U; Android 9; en-us; SM-N950N Build/PPR1.180610.011) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.5735.196 Mobile Safari/537.36 PHX/21.7', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-20 15:09:45'),
(514, 19, '172.225.99.232', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-21 14:13:09'),
(515, 21, '197.211.59.71', 'Mozilla/5.0 (Linux; U; Android 15; en-us; SM-G998U1 Build/AP3A.240905.015.A2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.6723.58 Mobile Safari/537.36 PHX/21.7', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-21 15:49:26'),
(516, 19, '197.211.59.80', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-21 21:07:00'),
(517, 19, '41.190.12.59', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-21 22:13:57'),
(518, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-21 22:38:24'),
(519, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-21 22:39:10'),
(520, 19, '172.225.243.51', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-21 22:47:41'),
(521, 19, '105.112.23.249', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-21 22:51:11'),
(522, 19, '102.90.101.191', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-21 23:18:00'),
(523, 19, '102.93.10.93', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-21 23:50:14'),
(524, 19, '15.181.145.249', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-22 00:58:18'),
(525, 19, '102.89.75.11', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-22 01:00:25'),
(526, 19, '102.89.75.26', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-22 01:13:51'),
(527, 19, '102.88.108.188', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-22 01:28:21'),
(528, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-22 01:39:09'),
(529, 19, '107.127.28.4', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-22 01:49:57'),
(530, 19, '136.144.17.186', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-22 01:59:30'),
(531, 25, '197.149.95.142', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-23 09:16:12'),
(532, 21, '143.105.174.155', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_8 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.8 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-23 20:13:15'),
(533, 19, '102.89.82.166', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-24 23:13:19'),
(534, 19, '105.113.110.237', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-25 00:16:16'),
(535, 19, '104.28.87.51', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-25 01:17:05'),
(536, 19, '102.89.75.226', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-25 01:55:56'),
(537, 19, '105.112.23.178', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-25 17:03:28'),
(538, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-25 19:17:15'),
(539, 19, '105.113.82.104', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-25 23:55:43'),
(540, 19, '102.89.83.21', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-26 00:52:17'),
(541, 19, '102.89.69.38', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-26 01:02:48'),
(542, 19, '102.89.69.38', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-26 01:06:19'),
(543, 21, '98.97.76.0', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_8 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.8 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-26 16:45:31'),
(544, 21, '135.129.124.14', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_8 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.8 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-27 14:43:25'),
(545, 21, '197.210.8.15', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-28 14:34:04'),
(546, 21, '102.89.83.22', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-28 15:35:06'),
(547, 21, '98.97.76.72', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-28 17:17:22'),
(548, 21, '102.88.109.106', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-28 20:14:53'),
(549, 19, '102.89.76.207', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-28 22:09:42'),
(550, 19, '172.225.243.63', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-28 22:51:10'),
(551, 19, '172.225.243.60', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-28 22:51:12'),
(552, 19, '102.93.11.78', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/150.0.7871.113 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-28 23:34:16'),
(553, 19, '102.93.11.78', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/150.0.7871.113 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-28 23:35:25'),
(554, 19, '102.93.8.96', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-28 23:36:25'),
(555, 19, '102.89.82.178', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-29 00:09:40'),
(556, 19, '79.127.149.154', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-29 00:42:42'),
(557, 19, '105.112.204.5', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-29 00:42:48'),
(558, 19, '102.89.83.130', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-29 01:09:16'),
(559, 19, '105.115.5.245', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-29 01:19:11'),
(560, 19, '102.88.113.69', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-29 02:00:35'),
(561, 19, '102.89.83.53', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-29 02:06:08'),
(562, 21, '98.97.77.195', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_1_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.137 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-29 13:10:00'),
(563, 19, '102.90.98.133', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-29 14:33:57'),
(564, 19, '197.149.86.246', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-07-29 14:34:36'),
(565, 19, '185.26.181.88', 'Mozilla/5.0 (Linux; U; Android 14; SM-A155M Build/UP1A.231005.007; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/150.0.7871.181 Mobile Safari/537.36 OPR/99.4.2254.1608', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-29 14:34:39'),
(566, 19, '105.115.9.74', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_2_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/147.0.7727.99 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-29 23:46:51'),
(567, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-29 23:58:42'),
(568, 19, '105.112.183.36', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-29 23:59:01'),
(569, 19, '112.119.143.242', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1 Brave', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-30 00:08:26'),
(570, 19, '102.93.11.214', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-30 00:44:05'),
(571, 19, '102.88.114.196', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-30 01:16:40'),
(572, 19, '102.89.82.61', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-30 01:42:54'),
(573, 19, '197.149.86.246', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-30 02:07:01'),
(574, 19, '105.112.71.29', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-30 02:29:39'),
(575, 21, '197.210.29.123', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-30 15:33:41'),
(576, 21, '102.89.44.2', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_6_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-31 15:43:21'),
(577, 21, '102.89.82.130', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 16:10:01'),
(578, 21, '102.89.46.62', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 16:12:16'),
(579, 21, '105.113.100.222', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 16:12:25'),
(580, 21, '105.113.96.74', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 16:38:37'),
(581, 21, '102.88.112.63', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-31 16:46:35'),
(582, 21, '102.89.22.80', 'Mozilla/5.0 (iPhone; CPU iPhone OS 19_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-31 16:48:33'),
(583, 21, '102.89.23.103', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 17:10:34'),
(584, 21, '102.89.46.125', 'Mozilla/5.0 (X11; Linux x86_64; rv:32.0) Gecko/20100101 Firefox/32.0', 'desktop', 'firefox', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 17:13:08'),
(585, 21, '102.89.46.125', 'Mozilla/5.0 (Linux; Android 15; TECNO LI6 Build/AP3A.240905.015.A2; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/150.0.7871.181 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 17:13:10'),
(586, 21, '105.113.78.176', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 17:27:53'),
(587, 21, '102.88.115.243', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 17:29:04'),
(588, 21, '102.88.115.223', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 18:48:30'),
(589, 21, '197.210.53.244', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-31 18:59:50'),
(590, 21, '102.90.45.228', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 19:07:45'),
(591, 21, '102.88.115.243', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 19:23:58'),
(592, 21, '102.89.47.204', 'Mozilla/5.0 (Linux; Android 15; TECNO LI6 Build/AP3A.240905.015.A2; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/150.0.7871.181 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 19:25:15'),
(593, 21, '102.89.47.204', 'Mozilla/5.0 (Linux; Android 15; TECNO LI6 Build/AP3A.240905.015.A2; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/150.0.7871.181 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 19:26:59'),
(594, 21, '105.113.95.140', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 19:28:49'),
(595, 21, '102.89.22.216', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-31 19:42:51'),
(596, 21, '105.113.90.197', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/420.4.909430193 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-31 19:49:22'),
(597, 21, '105.112.199.120', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 20:32:26'),
(598, 21, '102.90.102.15', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-31 21:11:18'),
(599, 21, '102.89.47.220', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-31 21:11:33'),
(600, 21, '102.88.112.79', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/150.0.7871.113 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-31 22:00:05'),
(601, 21, '102.88.111.208', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 22:10:02'),
(602, 21, '102.88.111.208', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 22:22:08'),
(603, 21, '102.90.98.13', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 22:33:13'),
(604, 21, '102.89.46.18', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 22:36:23'),
(605, 19, '105.112.24.38', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 22:39:13'),
(606, 21, '102.89.46.18', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-07-31 22:49:17'),
(607, 19, '105.115.6.202', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-31 23:02:54'),
(608, 19, '102.90.102.225', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-31 23:31:22'),
(609, 19, '105.112.190.24', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-07-31 23:46:31'),
(610, 19, '102.89.40.107', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-01 00:03:22'),
(611, 19, '102.89.76.137', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-01 00:08:53'),
(612, 19, '105.115.10.124', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/150.0.7871.113 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-01 00:11:35'),
(613, 19, '102.89.83.227', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-01 00:13:57'),
(614, 19, '102.89.68.178', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-01 00:16:23'),
(615, 19, '104.28.87.71', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-01 00:20:33'),
(616, 19, '102.88.110.33', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-01 00:23:37'),
(617, 21, '102.89.34.87', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-01 01:16:15'),
(618, 21, '105.113.116.193', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-08-01 02:19:44'),
(619, 21, '105.113.116.193', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/26.0 Chrome/122.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-01 02:19:49'),
(620, 19, '102.89.47.211', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-01 02:21:16'),
(621, 21, '197.210.28.114', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_1_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.137 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-01 02:23:14'),
(622, 21, '102.89.32.82', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-01 11:36:01'),
(623, 21, '102.89.32.82', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-01 11:39:43'),
(624, 21, '102.89.32.82', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-01 11:40:19'),
(625, 21, '102.88.108.227', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-01 11:41:34'),
(626, 21, '105.113.107.76', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_3_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/150.0.7871.113 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-01 18:54:43'),
(627, 21, '105.112.29.206', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-01 18:56:57'),
(628, 21, '102.93.10.45', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-01 19:12:04'),
(629, 21, '102.93.10.45', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-01 19:17:28'),
(630, 21, '102.89.22.175', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-01 21:20:56'),
(631, 19, '102.90.96.86', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-01 23:17:11'),
(632, 19, '102.88.110.66', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_6_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/150.0.7871.113 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-01 23:17:14'),
(633, 19, '102.89.75.220', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-08-01 23:34:45'),
(634, 19, '102.89.75.220', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-01 23:34:49'),
(635, 19, '102.89.84.220', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 MicroMessenger/8.0.73(0x18004939) NetType/4G Language/en', 'mobile', 'unknown', 'mac', NULL, NULL, NULL, NULL, '2026-08-01 23:35:26'),
(636, 19, '102.89.84.220', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 MicroMessenger/8.0.73(0x18004939) NetType/4G Language/en', 'mobile', 'unknown', 'mac', NULL, NULL, NULL, NULL, '2026-08-01 23:38:34'),
(637, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-02 00:35:06'),
(638, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-02 01:34:59'),
(639, 19, '102.89.82.77', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-02 02:34:19'),
(640, 19, '102.90.101.214', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-02 02:35:07'),
(641, 21, '102.89.47.78', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_4_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/148.0.7778.100 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-02 17:42:44'),
(642, 21, '102.89.47.32', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/148.0.7778.166 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-02 17:55:34'),
(643, 21, '102.89.45.221', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-02 18:14:46'),
(644, 21, '197.211.59.97', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-02 18:36:23'),
(645, 21, '102.89.47.61', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-02 18:41:41'),
(646, 21, '102.89.43.16', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-02 18:43:06');
INSERT INTO `qr_code_scans` (`id`, `restaurant_id`, `ip_address`, `user_agent`, `device_type`, `browser`, `os`, `country`, `city`, `latitude`, `longitude`, `scanned_at`) VALUES
(647, 21, '102.89.40.185', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-02 18:43:10'),
(648, 21, '102.89.32.118', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-02 18:47:10'),
(649, 21, '102.89.47.228', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-08-02 18:48:35'),
(650, 21, '102.89.47.228', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-08-02 18:48:53'),
(651, 21, '102.89.47.228', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-08-02 18:48:53'),
(652, 21, '102.89.23.185', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-02 18:49:27'),
(653, 21, '102.89.40.60', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-02 18:53:04'),
(654, 21, '102.88.114.208', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-02 18:55:06'),
(655, 21, '154.120.79.6', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-02 19:05:24'),
(656, 21, '197.210.28.41', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-02 19:05:49'),
(657, 21, '102.90.98.27', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-02 19:17:08'),
(658, 21, '105.112.39.116', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-02 19:28:38'),
(659, 21, '102.89.47.229', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/27.0 Chrome/125.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-02 19:45:41'),
(660, 21, '102.89.47.190', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-02 20:09:44'),
(661, 21, '129.205.124.248', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-02 20:23:16'),
(662, 21, '102.88.111.11', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-02 20:42:19'),
(663, 21, '102.88.111.11', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-02 20:43:18'),
(664, 21, '102.89.46.132', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-03 08:54:08'),
(665, 21, '102.88.169.186', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-03 11:28:55'),
(666, 21, '197.210.29.95', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-03 14:56:08'),
(667, 21, '102.88.111.17', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-03 16:11:13'),
(668, 21, '105.113.70.240', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-03 16:13:46'),
(669, 21, '105.113.70.237', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-03 16:14:28'),
(670, 21, '105.113.58.33', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-03 16:16:50'),
(671, 21, '105.113.107.5', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-03 16:26:24'),
(672, 21, '172.225.243.52', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-03 18:10:46'),
(673, 21, '102.90.103.94', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-03 18:33:29'),
(674, 21, '102.89.46.127', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-03 19:01:55'),
(675, 21, '102.88.113.159', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-03 20:43:39'),
(676, 21, '102.88.113.159', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-03 20:44:57'),
(677, 21, '105.113.58.36', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-04 07:37:18'),
(678, 21, '129.222.206.251', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_8 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.8 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-04 11:53:06'),
(679, 21, '102.89.41.128', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-04 13:28:33'),
(680, 21, '102.89.22.22', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-04 15:31:37'),
(681, 21, '105.113.103.148', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_1_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-04 18:06:34'),
(682, 21, '197.211.59.105', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-04 18:18:31'),
(683, 21, '105.113.100.20', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) EdgiOS/150.0.4078.96 Version/26.0 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-04 18:31:27'),
(684, 21, '102.89.68.197', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-04 18:41:28'),
(685, 19, '102.93.8.122', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-04 19:19:26'),
(686, 21, '102.88.112.92', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-04 19:19:51'),
(687, 21, '105.113.100.20', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) EdgiOS/150.0.4078.96 Version/26.0 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-04 20:13:50'),
(688, 21, '38.91.100.21', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Safari/605.1.15', 'desktop', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-04 20:14:17'),
(689, 21, '38.91.100.21', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) FxiOS/146.1  Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-04 20:14:57'),
(690, 21, '129.222.206.251', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-04 20:22:25'),
(691, 21, '129.222.206.251', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-04 20:22:47'),
(692, 19, '104.28.88.83', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-04 23:22:43'),
(693, 19, '102.90.100.55', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-04 23:23:39'),
(694, 19, '102.89.68.45', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-04 23:39:10'),
(695, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-04 23:41:28'),
(696, 19, '45.130.83.6', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-04 23:43:09'),
(697, 19, '197.149.86.246', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-04 23:51:11'),
(698, 19, '102.89.83.242', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-04 23:51:25'),
(699, 19, '102.88.108.111', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-04 23:52:41'),
(700, 19, '102.88.108.111', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-04 23:59:43'),
(701, 19, '102.89.68.121', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-05 00:00:30'),
(702, 19, '102.89.69.180', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-05 00:08:20'),
(703, 19, '197.211.52.236', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_1_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-05 00:09:43'),
(704, 19, '102.88.109.17', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/151.0.7922.57 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-05 00:24:21'),
(705, 19, '102.88.113.180', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-05 01:03:10'),
(706, 21, '102.89.34.142', 'Mozilla/5.0 (iPhone; CPU iPhone OS 27_0_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/151.0.7922.57 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-05 18:57:51'),
(707, 21, '105.112.73.98', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-05 20:29:22'),
(708, 21, '105.112.73.98', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-05 20:29:41'),
(709, 21, '129.222.206.81', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-05 22:14:29'),
(710, 19, '102.89.68.178', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-06 01:15:57'),
(711, 19, '102.88.112.122', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-06 01:16:40'),
(712, 21, '38.91.100.21', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) FxiOS/146.1  Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-06 03:13:42'),
(713, 21, '38.91.100.21', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Safari/605.1.15', 'desktop', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-06 03:13:42'),
(714, 21, '102.208.145.34', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_1_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/150.0.7871.34 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-06 15:56:28'),
(715, 21, '102.89.46.195', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-06 15:56:28'),
(716, 21, '102.89.23.216', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 MicroMessenger/8.0.75(0x18004b54) NetType/4G Language/en', 'mobile', 'unknown', 'mac', NULL, NULL, NULL, NULL, '2026-08-06 16:43:51'),
(717, 21, '102.89.23.216', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 MicroMessenger/8.0.75(0x18004b54) NetType/4G Language/en', 'mobile', 'unknown', 'mac', NULL, NULL, NULL, NULL, '2026-08-06 16:44:32'),
(718, 21, '102.89.23.216', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/432.8.954074404 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-06 16:44:39'),
(719, 21, '66.249.93.231', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-06 16:44:53'),
(720, 21, '102.91.4.68', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-08-06 18:42:05'),
(721, 21, '102.91.4.68', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-06 18:42:15'),
(722, 21, '102.91.4.68', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-06 18:48:59'),
(723, 21, '102.89.47.110', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-08-06 18:52:36'),
(724, 21, '102.89.47.110', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-06 18:52:42'),
(725, 21, '129.222.206.81', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/150.0.7871.113 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-06 20:15:55'),
(726, 21, '102.89.46.23', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-06 21:41:42'),
(727, 21, '102.89.47.159', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-06 22:39:27'),
(728, 21, '102.89.45.14', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-07 09:15:21'),
(729, 21, '102.89.45.14', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-07 09:15:22'),
(730, 21, '102.89.45.14', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-07 09:15:22'),
(731, 21, '102.89.45.14', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-07 09:15:22'),
(732, 21, '102.89.45.14', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-07 09:17:09'),
(733, 21, '102.89.46.199', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-07 10:19:08'),
(734, 21, '172.225.179.95', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-07 14:10:19'),
(735, 21, '172.225.243.49', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-07 14:39:18'),
(736, 21, '102.89.33.29', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-07 15:47:59'),
(737, 21, '197.210.8.171', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-07 17:03:31'),
(738, 21, '102.89.82.238', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-07 18:17:26'),
(739, 21, '143.105.174.139', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-07 18:17:44'),
(740, 21, '105.113.83.136', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-07 19:20:23'),
(741, 21, '216.73.160.190', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-07 19:22:18'),
(742, 21, '102.90.97.162', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-07 19:58:39'),
(743, 21, '105.113.80.38', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-07 20:06:09'),
(744, 21, '102.88.110.83', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-07 20:19:52'),
(745, 21, '102.89.47.214', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-07 20:55:58'),
(746, 21, '38.91.100.190', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-07 21:22:05'),
(747, 19, '102.88.115.206', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-07 22:11:55'),
(748, 19, '102.88.108.57', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-08 00:05:08'),
(749, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-08 00:52:37'),
(750, 21, '102.88.111.131', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-08 08:02:55'),
(751, 21, '129.222.206.189', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-08 09:30:01'),
(752, 21, '102.89.46.168', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/151.0.7922.57 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-08 11:58:31'),
(753, 21, '129.222.206.189', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_8 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.8 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-08 13:10:37'),
(754, 21, '129.222.206.189', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-08 13:19:47'),
(755, 21, '197.210.28.101', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-08 19:14:59'),
(756, 21, '129.222.206.189', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-08 21:10:53'),
(757, 21, '129.222.206.189', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_8 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.8 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-09 13:39:10'),
(758, 21, '102.89.46.38', 'Mozilla/5.0 (Linux; U; Android 15; TECNO KM6 Build/AP3A.240905.015.A2; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/151.0.7922.83 Mobile Safari/537.36 OPR/99.4.2254.1608', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-09 17:06:48'),
(759, 21, '102.88.110.198', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-09 17:59:04'),
(760, 21, '105.112.205.41', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-08-09 18:13:15'),
(761, 21, '105.112.205.41', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-09 18:13:26'),
(762, 21, '105.112.205.41', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-09 18:13:33'),
(763, 21, '105.113.98.45', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-09 18:39:43'),
(764, 21, '102.89.46.182', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-08-09 18:47:09'),
(765, 21, '102.89.46.182', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-08-09 18:50:40'),
(766, 21, '102.89.46.182', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-09 18:52:20'),
(767, 21, '102.89.83.167', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-09 19:14:02'),
(768, 21, '105.113.60.150', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-09 19:56:38'),
(769, 21, '105.113.95.31', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-09 20:53:19'),
(770, 21, '105.113.95.31', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-09 20:55:52'),
(771, 21, '105.113.60.69', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-09 21:52:07'),
(772, 21, '102.88.108.90', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-09 22:11:01'),
(773, 21, '102.88.114.106', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-10 14:13:39'),
(774, 25, '105.112.176.16', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-10 15:40:56'),
(775, 25, '105.112.176.16', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-10 15:50:28'),
(776, 21, '102.89.23.205', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-10 19:15:41'),
(777, 21, '98.97.79.27', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-10 19:32:14'),
(778, 21, '102.89.23.205', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-10 19:33:59'),
(779, 21, '102.89.23.205', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-10 19:35:01'),
(780, 21, '105.113.83.139', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-10 21:12:11'),
(781, 21, '158.173.166.3', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-11 13:51:15'),
(782, 21, '197.211.59.185', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-11 21:15:25'),
(783, 21, '105.112.30.71', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-11 21:24:40'),
(784, 19, '102.89.68.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-11 22:15:59'),
(785, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-12 00:00:47'),
(786, 19, '197.149.86.246', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-08-12 00:28:53'),
(787, 19, '197.149.86.246', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-12 00:28:57'),
(788, 21, '98.97.79.137', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-12 13:17:14'),
(789, 21, '102.88.109.22', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_16 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-12 14:50:37'),
(790, 21, '102.88.112.126', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-12 15:12:43'),
(791, 21, '102.91.92.147', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-12 19:30:02'),
(792, 21, '102.89.45.167', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-12 19:54:28'),
(793, 21, '197.210.70.35', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-12 20:49:50'),
(794, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-13 00:48:40'),
(795, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-13 23:15:37'),
(796, 19, '105.115.6.149', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-13 23:16:20'),
(797, 19, '105.115.6.149', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-13 23:21:25'),
(798, 19, '102.93.14.133', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-13 23:22:14'),
(799, 19, '102.89.83.180', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-13 23:36:44'),
(800, 19, '102.88.111.115', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-13 23:36:48'),
(801, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-14 00:21:22'),
(802, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-14 00:27:52'),
(803, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-14 00:52:16'),
(804, 19, '102.90.102.215', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_6_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/151.0.7922.112 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-14 01:22:54'),
(805, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-14 02:52:49'),
(806, 21, '102.88.115.218', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-14 19:51:48'),
(807, 21, '135.129.124.114', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-14 20:29:12'),
(808, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-15 00:00:36'),
(809, 19, '105.112.17.21', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-15 00:37:32'),
(810, 19, '102.89.85.248', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-15 00:48:31'),
(811, 19, '102.89.84.247', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-15 01:02:36'),
(812, 21, '135.129.124.114', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-15 16:30:31'),
(813, 21, '105.113.58.99', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-15 19:59:34'),
(814, 21, '102.88.104.176', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1 Brave', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-15 20:00:23'),
(815, 21, '102.89.23.171', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-15 21:18:10'),
(816, 21, '135.129.124.114', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-15 22:25:40'),
(817, 21, '216.73.161.114', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/151.0.7922.112 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-16 09:08:12'),
(818, 21, '187.14.55.61', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-16 14:02:50'),
(819, 21, '102.91.71.96', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-16 14:20:54'),
(820, 21, '102.89.23.92', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-16 14:39:25'),
(821, 21, '102.88.111.124', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-16 18:05:41'),
(822, 21, '197.211.59.124', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-16 18:13:56'),
(823, 21, '197.211.59.124', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-16 18:14:34'),
(824, 21, '105.113.70.111', 'Mozilla/5.0 (Android 14; Mobile; rv:153.0) Gecko/153.0 Firefox/153.0', 'mobile', 'firefox', 'android', NULL, NULL, NULL, NULL, '2026-08-16 18:15:27'),
(825, 21, '197.210.28.54', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-16 18:19:04'),
(826, 21, '105.113.114.182', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-16 18:27:11'),
(827, 21, '102.89.22.97', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-16 18:34:37'),
(828, 21, '102.91.72.163', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-16 18:36:25'),
(829, 21, '102.91.72.163', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-16 18:44:15'),
(830, 21, '102.88.113.61', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-16 18:51:18'),
(831, 21, '197.211.59.124', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-16 19:15:37'),
(832, 21, '105.113.83.165', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-16 19:19:39'),
(833, 21, '102.89.47.136', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-16 19:21:24'),
(834, 21, '102.93.13.66', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-16 19:21:28'),
(835, 21, '197.210.71.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/28.0 Chrome/130.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-16 22:25:44'),
(836, 21, '102.89.85.3', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-17 17:25:44'),
(837, 21, '102.89.22.146', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_1_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/140.0.7339.122 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-17 18:05:45'),
(838, 21, '135.129.124.124', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-18 18:58:10'),
(839, 21, '135.129.124.124', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-18 18:58:19'),
(840, 21, '135.129.124.124', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-18 18:58:51'),
(841, 21, '102.89.45.180', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-18 19:14:20'),
(842, 21, '105.113.102.13', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-18 19:55:16'),
(843, 19, '102.89.47.107', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-18 22:00:49'),
(844, 19, '102.89.22.160', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-18 22:26:50'),
(845, 19, '102.88.105.81', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-18 22:26:57'),
(846, 19, '102.89.22.160', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-18 22:31:47'),
(847, 19, '102.89.69.61', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-19 00:41:51'),
(848, 21, '102.89.82.6', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-19 12:31:13'),
(849, 21, '102.89.46.225', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-19 13:23:53'),
(850, 13, '102.89.69.6', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-19 15:03:59'),
(851, 13, '104.28.96.122', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-19 15:05:19'),
(852, 21, '98.97.76.140', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-19 15:40:06'),
(853, 21, '102.88.109.34', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-19 16:45:33'),
(854, 21, '102.88.109.34', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-19 17:45:46'),
(855, 21, '102.90.96.191', 'Mozilla/5.0 (Linux; U; Android 16; en-US; SM-F956U1 Build/BP2A.250605.031.A3) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/123.0.6312.80 UCBrowser/15.2.2.1398 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-19 20:07:05'),
(856, 21, '168.235.203.247', 'Mozilla/5.0 (Linux; U; Android 16; en-US; SM-F956U1 Build/BP2A.250605.031.A3) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/123.0.6312.80 UCBrowser/15.2.2.1398 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-19 20:20:20'),
(857, 21, '102.88.110.185', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-19 20:21:20'),
(858, 21, '102.89.22.134', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-19 20:27:17'),
(859, 21, '102.89.22.134', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-19 22:22:27'),
(860, 21, '16.56.22.225', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-19 22:25:19'),
(861, 21, '102.88.113.210', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-20 21:25:20');
INSERT INTO `qr_code_scans` (`id`, `restaurant_id`, `ip_address`, `user_agent`, `device_type`, `browser`, `os`, `country`, `city`, `latitude`, `longitude`, `scanned_at`) VALUES
(862, 21, '102.88.113.210', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-20 21:29:55'),
(863, 21, '197.211.59.83', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-21 10:00:37'),
(864, 21, '98.97.79.151', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-21 16:29:02'),
(865, 21, '102.89.23.166', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-21 21:54:12'),
(866, 19, '197.149.86.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-22 00:08:09'),
(867, 19, '102.89.22.227', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-22 00:08:31'),
(868, 19, '197.211.59.117', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-22 00:08:48'),
(869, 19, '102.89.83.130', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-22 10:45:38'),
(870, 19, '102.89.44.162', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/29.0 Chrome/136.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-22 10:45:57'),
(871, 19, '102.89.83.130', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-22 10:47:28'),
(872, 19, '102.89.83.130', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-22 10:50:05'),
(873, 19, '197.211.59.110', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-22 10:50:23'),
(874, 21, '102.88.108.60', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-08-22 17:01:41'),
(875, 21, '102.88.108.60', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-22 17:01:45'),
(876, 19, '197.211.59.117', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-22 17:18:51'),
(877, 21, '102.88.104.83', 'QR Scanner Android', 'mobile', 'unknown', 'android', NULL, NULL, NULL, NULL, '2026-08-22 19:12:45'),
(878, 21, '102.88.104.83', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36 EdgA/151.0.0.0', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-22 19:12:50'),
(879, 21, '102.88.108.101', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-22 19:33:04'),
(880, 21, '102.89.47.154', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-22 19:59:41'),
(881, 21, '102.89.46.236', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-22 20:39:49'),
(882, 21, '102.88.108.241', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/434.2.961627397 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-22 22:44:01'),
(883, 21, '98.97.79.151', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_2_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/151.0.7922.112 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-23 07:31:46'),
(884, 21, '98.97.79.151', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_2_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/151.0.7922.112 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-23 07:33:42'),
(885, 21, '98.97.79.151', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-23 19:50:45'),
(886, 21, '102.89.68.168', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-23 19:51:47'),
(887, 21, '67.220.94.22', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-23 19:53:54'),
(888, 21, '102.88.114.241', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-23 20:07:05'),
(889, 21, '102.89.22.176', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_6_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/434.2.961627397 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-23 20:18:25'),
(890, 21, '102.89.22.176', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_6_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/434.2.961627397 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-23 20:57:18'),
(891, 21, '98.97.79.151', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-24 11:11:48'),
(892, 21, '102.89.47.200', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/143.0.7499.151 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-24 18:39:50'),
(893, 21, '102.88.108.155', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-24 20:47:16'),
(894, 21, '102.88.109.48', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-25 10:48:29'),
(895, 21, '102.88.109.131', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-25 12:38:22'),
(896, 21, '197.211.59.95', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_1_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.1.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-25 12:41:30'),
(897, 21, '104.28.96.121', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-25 14:16:24'),
(898, 21, '15.181.3.44', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_6_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/151.0.7922.112 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-25 19:54:23'),
(899, 21, '15.181.3.44', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_6_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/151.0.7922.112 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-25 20:36:34'),
(900, 19, '104.28.88.91', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-25 23:24:30'),
(901, 19, '104.28.88.89', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-25 23:24:35'),
(902, 19, '102.88.107.48', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_6_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/151.0.7922.112 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-25 23:27:16'),
(903, 19, '102.88.115.71', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-26 00:50:41'),
(904, 19, '197.211.59.117', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-26 01:04:50'),
(905, 19, '102.89.22.36', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-26 01:34:24'),
(906, 19, '102.89.41.219', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-26 01:34:41'),
(907, 19, '197.211.52.220', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-26 01:52:15'),
(908, 19, '104.28.96.121', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_6_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-26 01:52:51'),
(909, 21, '98.97.77.201', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_8_8 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.8 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-26 18:18:08'),
(910, 19, '197.211.53.88', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-27 01:12:59'),
(911, 21, '105.113.68.37', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-27 16:54:24'),
(912, 21, '197.211.59.196', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/151.0.7922.112 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-27 18:06:41'),
(913, 21, '102.88.109.160', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-27 20:50:56'),
(914, 21, '135.129.124.2', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-27 22:30:49'),
(915, 21, '102.89.82.209', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-28 11:45:06'),
(916, 21, '102.89.84.228', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-28 16:36:57'),
(917, 21, '102.90.101.159', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-28 21:11:10'),
(918, 21, '197.210.8.172', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-28 21:19:27'),
(919, 19, '102.89.22.31', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/151.0.7922.112 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-29 00:00:06'),
(920, 21, '135.129.124.2', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_2_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/151.0.7922.112 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-29 15:45:35'),
(921, 21, '102.90.97.76', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-29 17:12:54'),
(922, 21, '135.129.124.2', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-29 17:20:39'),
(923, 21, '45.8.17.58', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-29 18:56:08'),
(924, 21, '135.129.124.2', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_4_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/152.0.7977.64 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-29 19:25:47'),
(925, 21, '135.129.124.2', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_4_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/152.0.7977.64 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-29 19:25:47'),
(926, 21, '102.89.32.140', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-29 21:06:59'),
(927, 21, '102.89.32.140', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-29 21:37:12'),
(928, 21, '102.89.42.206', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-29 22:58:08'),
(929, 21, '102.88.115.181', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 12:52:30'),
(930, 21, '102.88.108.42', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_6_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/152.0.7977.64 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 13:14:03'),
(931, 21, '66.249.93.102', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 (compatible; Google-Read-Aloud; +https://support.google.com/webmasters/answer/1061943)', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-30 16:24:22'),
(932, 21, '102.88.111.71', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_4_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/425.6.927981711 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 16:24:22'),
(933, 21, '102.88.110.244', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 16:44:06'),
(934, 21, '102.88.113.184', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 16:59:12'),
(935, 21, '135.129.124.2', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 17:02:46'),
(936, 21, '102.88.113.47', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-30 17:22:20'),
(937, 21, '102.89.47.139', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 17:25:14'),
(938, 21, '102.89.46.97', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 18:15:13'),
(939, 21, '102.88.110.239', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 18:16:34'),
(940, 21, '102.89.22.216', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 18:30:11'),
(941, 21, '102.89.22.157', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 18:41:25'),
(942, 21, '102.88.113.10', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 18:44:37'),
(943, 21, '102.90.103.196', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 18:51:37'),
(944, 21, '197.210.227.174', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_4_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/425.6.927981711 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 18:52:03'),
(945, 21, '102.89.46.198', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 18:52:11'),
(946, 21, '105.113.99.11', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-30 18:52:39'),
(947, 21, '105.113.99.11', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-30 19:01:33'),
(948, 21, '197.211.59.94', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 19:21:49'),
(949, 21, '102.88.109.81', 'Mozilla/5.0 (Linux; U; Android 16; en-US; SM-F956U1 Build/BP2A.250605.031.A3) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/123.0.6312.80 UCBrowser/15.2.2.1398 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-30 19:26:03'),
(950, 21, '105.112.39.92', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-30 19:28:46'),
(951, 21, '102.89.41.242', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_1_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.1.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 19:31:33'),
(952, 21, '105.113.75.151', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-30 19:35:19'),
(953, 21, '102.89.22.222', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 19:50:42'),
(954, 21, '102.89.22.141', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 19:51:17'),
(955, 21, '135.129.124.2', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 20:18:50'),
(956, 21, '102.89.22.88', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-30 22:49:51'),
(957, 21, '102.88.108.152', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-31 18:53:53'),
(958, 21, '102.89.33.251', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-08-31 18:58:59'),
(959, 21, '102.88.110.211', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-08-31 19:18:22'),
(960, 21, '38.91.101.88', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-01 13:26:38'),
(961, 21, '102.88.106.153', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-01 13:26:44'),
(962, 21, '161.178.132.111', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-01 13:26:44'),
(963, 21, '197.211.53.92', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) FxiOS/152.1.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-01 14:15:25'),
(964, 21, '102.88.110.25', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-01 14:54:03'),
(965, 21, '197.210.8.2', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-01 15:28:58'),
(966, 19, '102.89.85.219', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/30.0 Chrome/143.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-09-01 22:38:34'),
(967, 19, '102.88.108.33', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/152.0.7977.64 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-01 23:03:00'),
(968, 19, '102.89.69.230', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-03 00:20:45'),
(969, 19, '105.112.23.166', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-03 01:34:50'),
(970, 21, '102.89.75.180', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-03 12:43:09'),
(971, 21, '197.210.8.247', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) EdgiOS/151.0.4129.96 Version/26.0 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-03 16:05:04'),
(972, 21, '102.88.108.59', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-09-03 17:33:16'),
(973, 21, '102.88.111.142', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-03 17:47:48'),
(974, 21, '102.88.112.149', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-03 17:47:48'),
(975, 21, '102.88.112.120', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-03 18:07:43'),
(976, 21, '102.88.112.120', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-03 18:09:31'),
(977, 21, '23.27.16.11', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-03 18:13:58'),
(978, 21, '105.113.67.139', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-03 18:56:30'),
(979, 21, '102.88.108.59', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-09-03 19:02:27'),
(980, 21, '143.105.174.137', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-09-04 20:35:59'),
(981, 21, '197.210.28.98', 'Mozilla/5.0 (Linux; U; Android 16; SM-S906U1 Build/BP2A.250605.031.A3; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/151.0.7922.199 Mobile Safari/537.36 OPR/99.5.2254.2012', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-09-04 21:37:33'),
(982, 21, '102.88.106.179', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-04 22:10:46'),
(983, 21, '105.113.79.47', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-04 22:11:22'),
(984, 21, '98.97.79.45', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-05 13:58:28'),
(985, 21, '197.211.59.56', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7_10 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/152.0.7977.64 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-05 16:57:03'),
(986, 21, '102.89.40.251', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_3_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.3.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-05 16:57:50'),
(987, 21, '98.97.79.45', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-05 17:53:02'),
(988, 21, '102.88.167.13', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-05 18:29:39'),
(989, 21, '102.93.10.7', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-09-05 18:38:17'),
(990, 21, '197.211.59.192', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-09-05 19:23:28'),
(991, 21, '98.97.79.45', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-05 22:01:38'),
(992, 21, '135.129.124.6', 'Mozilla/5.0 (Linux; Android 14; TECNO KL7 Build/UP1A.231005.007; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/151.0.7922.199 Mobile Safari/537.36 GolemAppBrowser', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-09-06 13:11:21'),
(993, 21, '102.88.105.125', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-09-06 15:06:59'),
(994, 21, '102.88.112.32', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-06 15:10:05'),
(995, 21, '102.88.114.87', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-06 15:17:56'),
(996, 21, '102.88.105.140', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-06 16:43:47'),
(997, 21, '102.89.46.99', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-06 16:53:52'),
(998, 21, '102.88.110.112', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-06 17:39:21'),
(999, 21, '102.88.109.142', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-06 17:40:17'),
(1000, 21, '105.113.103.146', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-09-06 17:52:51'),
(1001, 21, '102.89.42.247', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-09-06 17:53:16'),
(1002, 21, '197.211.59.66', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-06 17:55:18'),
(1003, 21, '102.89.47.72', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-09-06 17:55:53'),
(1004, 21, '102.88.106.148', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-06 18:18:12'),
(1005, 21, '102.88.112.61', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-06 18:37:29'),
(1006, 21, '105.113.129.82', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-09-06 19:35:29'),
(1007, 21, '197.211.59.104', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.6 Mobile/15E148 Safari/604.1', 'mobile', 'safari', 'mac', NULL, NULL, NULL, NULL, '2026-09-06 20:17:11'),
(1008, 21, '102.88.114.44', 'Mozilla/5.0 (Linux; U; Android 13; TECNO CK7n Build/TP1A.220624.014; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/151.0.7922.199 Mobile Safari/537.36 OPR/99.5.2254.2012', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-09-06 20:25:02'),
(1009, 21, '98.97.79.237', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36', 'mobile', 'chrome', 'linux', NULL, NULL, NULL, NULL, '2026-09-07 08:32:35');

-- --------------------------------------------------------

--
-- Table structure for table `qr_templates`
--

CREATE TABLE `qr_templates` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `preview_image` varchar(255) DEFAULT NULL,
  `has_text` tinyint(1) DEFAULT 0,
  `config_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`config_json`)),
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `qr_templates`
--

INSERT INTO `qr_templates` (`id`, `name`, `description`, `preview_image`, `has_text`, `config_json`, `is_active`, `created_at`, `updated_at`) VALUES
(18, 'SG QR NO LOGO', '', '18.svg', 1, '{\"pattern\":\"square\",\"eyes\":\"rounded\",\"frame\":{\"type\":\"rounded\",\"text\":\"SCAN ME\",\"color\":\"#051357\",\"text_color\":\"#ffffff\",\"text_size\":14,\"bg_enabled\":true,\"bg_color\":\"#252154\"},\"colors\":{\"foreground\":\"#f7f7f7\",\"background\":\"#2f326f\"},\"logo\":{\"enabled\":false,\"size\":0.200000000000000011102230246251565404236316680908203125,\"center_only\":true}}', 1, '2026-03-10 14:44:24', '2026-03-10 15:20:59'),
(19, 'SG QR WITH LOGO', 'SG QR WITH LOGO', '19.svg', 1, '{\"pattern\":\"square\",\"eyes\":\"square\",\"frame\":{\"type\":\"none\",\"text\":\"SCAN ME\",\"color\":\"#000000\",\"text_color\":\"#ffffff\",\"text_size\":14,\"bg_enabled\":true,\"bg_color\":\"#000000\"},\"colors\":{\"foreground\":\"#000000\",\"background\":\"#ffffff\"},\"logo\":{\"enabled\":false,\"size\":0.200000000000000011102230246251565404236316680908203125,\"center_only\":true}}', 1, '2026-03-20 17:08:41', '2026-03-20 17:08:41');

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `hero_image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `whatsapp_link` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `enable_food_ordering` tinyint(1) NOT NULL DEFAULT 1,
  `enable_table_reservations` tinyint(1) NOT NULL DEFAULT 1,
  `map_latitude` decimal(10,8) DEFAULT NULL,
  `map_longitude` decimal(11,8) DEFAULT NULL,
  `header_menu_items` text DEFAULT NULL,
  `footer_content` text DEFAULT NULL,
  `manager_email` varchar(255) DEFAULT NULL,
  `google_rating` decimal(3,1) DEFAULT 4.5,
  `rating_source` varchar(50) DEFAULT 'Google',
  `template_id` int(11) DEFAULT 1,
  `is_active` tinyint(1) DEFAULT 1,
  `available_items_count` int(11) DEFAULT 0,
  `unavailable_items_count` int(11) DEFAULT 0,
  `subscription_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`id`, `name`, `slug`, `logo`, `hero_image`, `description`, `phone`, `email`, `address`, `website`, `whatsapp_link`, `instagram_url`, `facebook_url`, `twitter_url`, `enable_food_ordering`, `enable_table_reservations`, `map_latitude`, `map_longitude`, `header_menu_items`, `footer_content`, `manager_email`, `google_rating`, `rating_source`, `template_id`, `is_active`, `available_items_count`, `unavailable_items_count`, `subscription_id`, `created_at`, `updated_at`) VALUES
(2, 'LAVA', 'lava', '69459eb555362.jpg', '69459edb896e3.png', 'Premium dining experience with exquisite cuisine and fine beverages', '+234 800 000 0000', 'info@lava.com', 'LAVA., 5 Adetokunbu Ademola Street, Victoria Island', NULL, '', '', 'https://web.facebook.com/theviewhotellekki', '', 0, 0, NULL, NULL, '[\r\n  {\"label\": \"Menu\", \"url\": \"#menu\"},\r\n  {\"label\": \"Drinks\", \"url\": \"#drinks\"}\r\n]', 'af', 'jamesamaila07@gmail.com', 4.5, 'Google', 3, 1, 65, 0, 28, '2025-12-19 18:43:07', '2026-05-30 16:12:41'),
(3, 'Theview Hotel Lekki', 'theview-hotel', '698ee78360beb.jpg', '698ee783613af.jpg', 'Our restaurant offers the best platters like our very popular Ogazi Platter (Guinea fowl platter), D View Special Platter and Pacific Platter.', '+23490 9091 3608', 'reservations@theviewlekki.com', '1, Godwin Omene Street, Chief Collins Uchidiuno, Off Fola Osibo, Lekki Phase 1, Lagos, Nigeria', NULL, 'https://wa.link/g1n8bq', 'https://www.instagram.com/theviewlekki/', 'https://web.facebook.com/theviewhotellekki', '', 1, 1, NULL, NULL, NULL, 'Our restaurant offers the best platters like our very popular Ogazi Platter (Guinea fowl platter), D View Special Platter and Pacific Platter.', 'reservations@theviewlekki.com', 4.5, 'Google', 4, 1, 218, 3, 35, '2026-02-13 08:57:39', '2026-06-09 19:10:31'),
(4, 'NOSTALGIA', 'nostalgia-menu', '6a03d7489f311.png', '6a045d414dc91.jpg', '', '+234 911 311 9337', 'info@nostalgialagos.com', '88 Hakeem Dickson Road, Lekki Phase 1', NULL, '', 'https://www.instagram.com/vcphotels/?hl=en', '', '', 0, 0, NULL, NULL, NULL, 'A Fusion of Culture, Style & Vibrant Nights.  \r\n  Offers both Dine-in and Take-out options.  \r\n    \r\n  ## Operating Hours  \r\n     Open: Tuesdays through Sundays, from 12:00 PM (noon) to 3:00 AM.  \r\n     Closed: Mondays.  \r\n     Sunday Brunch: Available from 12:00 PM (noon) to 4:30 PM.  \r\n\r\n    \r\n  ## Contact & Location   \r\n     Address: 88 Hakeem Dickson Road, Lekki Phase 1, Lagos, Nigeria 105102.', 'admin@nostalgia.our-menu.online', 4.5, 'Google', 18, 1, 167, 0, 29, '2026-03-03 23:30:50', '2026-05-29 18:11:09'),
(13, 'Café De Bourgeois', 'the-lusso-restaurant', '69b4163236007.jpg', 'YrJheDAs5Ppx.jpg', 'The lusso hotel abuja', '+2348106490399', 'restaurant@lussohotelsabuja.com', '33 Usuma St, Maitama, Abuja 904101, Federal Capital Territory', NULL, 'https://wa.me/message/KPLQMQD6LLECD1', 'https://www.instagram.com/thelusso_hotel', 'https://www.instagram.com/thelusso_hotel', 'https://x.com/thelussohotel', 1, 1, NULL, NULL, NULL, 'The Lusso Hotels & Suites delivers an unparalleled guest experience.', 'restaurant@lussohotelsabuja.com', 4.5, 'Google', 6, 1, 307, 0, 33, '2026-03-12 23:11:27', '2026-06-21 00:59:26'),
(19, 'OPAL LOUNGE & CAFE MENU', 'opal-cafe-menu', '6a0ba93b65798.webp', '6a0baa10d3d27.jpg', 'Cafe', '2349024262089', 'opallagos1@gmail.com', 'No 5 Adetukunbo Ademola Victoria Island, Lagos', NULL, '', '', '', '', 0, 0, NULL, NULL, NULL, '', 'opallagos1@gmail.com', 4.5, 'Google', 4, 1, 315, 0, 30, '2026-04-06 17:01:14', '2026-05-29 18:13:47'),
(20, 'Swiss The Vistana', 'swiss-the-vistana', '6a0c50796a8ff.png', '6a0c50148e149.webp', '', '09168340156', 'it.vistana@swissinternationalhotels.com', '', NULL, '', 'https://l.instagram.com/', '', '', 0, 0, NULL, NULL, NULL, '', 'it.vistana@swissinternationalhotels.com', 5.0, 'Google', 4, 1, 372, 0, 32, '2026-04-28 20:07:22', '2026-05-29 18:20:31'),
(21, 'Ellipse Hotels', 'ellipse-hotels', '6a06e916909bc.jpg', 'hNKU5qXhYIoc.jpg', 'Luxury and Comfort Redefined', '08109453960', 'ellipsehotelslagos@gmail.com', 'N0 31 Shola Adewumi Street, Bucknor Ejigbo Lagos State', NULL, NULL, NULL, NULL, NULL, 0, 1, NULL, NULL, NULL, NULL, 'ellipsehotelslagos@gmail.com', 4.5, 'Google', 7, 1, 131, 0, 27, '2026-05-09 17:25:57', '2026-09-07 01:48:47'),
(25, 'The Mania House', 'the-mania-house', '6a0a2610c2af8.png', '6a06635db76a9.jpg', 'Victoria Island Lagos', '08144258984', 'admin@maniahouse.our-menu.online', '25a Gafari Animashaun St, Victoria Island, Lagos 101241, Lagos', NULL, '', 'https://www.instagram.com/themaniahouse/?hl=en', '', '', 1, 0, NULL, NULL, NULL, '', 'admin@maniahouse.our-menu.online', 4.5, 'Google', 16, 1, 252, 0, 34, '2026-05-14 14:20:15', '2026-05-29 18:22:48'),
(26, 'Salt And Social', 'salt-and-social', '6a0a3848c49e1.webp', NULL, 'Our menu is a celebration of classic favorites, from juicy burgers and sizzling steaks to mouthwatering pizzas', '09083338888', 'admin@saltandsocial.our-menu.online', '2a Admiralty Wy, Lekki Phase 1, Lagos 105102, Lagos', NULL, '', 'https://www.instagram.com/s.a.l.t.s.o.c.i.a.l/', '', '', 0, 0, NULL, NULL, NULL, '', 'admin@saltandsocial.our-menu.online', 4.5, 'Google', 10, 1, 189, 0, 31, '2026-05-17 21:04:00', '2026-05-29 18:18:27'),
(27, 'Vendome Cafe\'s Menu', 'vendome-cafe-s-menu', '6a0c6356014b3.png', '6a0c69b6487ff.jpg', '', '', 'admin@vendomecafe.our-menu.online', '', NULL, '', '', '', '', 0, 0, NULL, NULL, NULL, '', 'admin@vendomecafe.our-menu.online', 4.5, 'Google', 6, 1, 357, 0, 36, '2026-05-19 12:18:11', '2026-06-20 21:22:05'),
(28, 'Alo Alo Restaurant', 'alo-alo-restaurant', NULL, NULL, NULL, NULL, 'reservations@vcphotels.com', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 4.5, 'Google', 4, 1, 0, 0, NULL, '2026-06-08 12:22:12', '2026-06-08 12:22:12'),
(29, 'Hello Store', 'hello-store', NULL, NULL, NULL, NULL, 'marketingbyjoshua@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 4.5, 'Google', 4, 1, 0, 0, NULL, '2026-06-23 04:52:31', '2026-06-23 04:52:31'),
(30, 'Faisalea chef', 'faisalea-chef', NULL, NULL, NULL, NULL, 'hmdfamily96@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 4.5, 'Google', 14, 1, 0, 0, NULL, '2026-07-28 20:30:53', '2026-07-28 20:32:00'),
(31, 'Funbitesbyore', 'funbitesbyore', NULL, NULL, NULL, NULL, 'funbites.byore@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 4.5, 'Google', 4, 1, 0, 0, NULL, '2026-08-11 21:46:20', '2026-08-11 21:46:20'),
(32, 'Ibile Moinmoin and Akara limited', 'ibile-moinmoin-and-akara-limited', NULL, NULL, NULL, NULL, 'IbileMoinmoin@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 4.5, 'Google', 9, 1, 0, 0, NULL, '2026-08-14 15:00:05', '2026-08-14 17:43:47'),
(33, 'NZ CORNER CAFETERIA', 'nz-corner-cafeteria', NULL, NULL, NULL, NULL, 'zaqzaq244341@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 4.5, 'Google', 4, 1, 0, 0, NULL, '2026-08-16 06:27:55', '2026-08-16 06:27:55'),
(34, 'ON THE GO food hub', 'on-the-go-food-hub', NULL, NULL, NULL, NULL, 'eddyjohnny007@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 4.5, 'Google', 9, 1, 0, 0, NULL, '2026-08-17 14:03:42', '2026-08-17 14:06:22'),
(35, 'KayLex Spot', 'kaylex-spot', NULL, NULL, NULL, NULL, 'kaylexspot1@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 4.5, 'Google', 4, 1, 0, 0, NULL, '2026-08-19 02:10:12', '2026-08-19 02:10:12'),
(36, 'Ayo bistro', 'ayo-bistro', NULL, NULL, NULL, NULL, 'ayodhee61@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 4.5, 'Google', 4, 1, 0, 0, NULL, '2026-09-06 04:45:55', '2026-09-06 04:45:55'),
(37, 'Mr Gibbs', 'mr-gibbs', NULL, NULL, NULL, NULL, 'gibsonemordi2020@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 4.5, 'Google', 4, 1, 0, 0, NULL, '2026-09-07 09:53:50', '2026-09-07 09:53:50');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_payment_settings`
--

CREATE TABLE `restaurant_payment_settings` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `gateway` varchar(50) NOT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `test_mode` tinyint(1) DEFAULT 1,
  `public_key_test` varchar(255) DEFAULT NULL,
  `secret_key_test` text DEFAULT NULL,
  `webhook_secret_test` varchar(255) DEFAULT NULL,
  `public_key_live` varchar(255) DEFAULT NULL,
  `secret_key_live` text DEFAULT NULL,
  `webhook_secret_live` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(100) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `restaurant_payment_settings`
--

INSERT INTO `restaurant_payment_settings` (`id`, `restaurant_id`, `gateway`, `is_active`, `test_mode`, `public_key_test`, `secret_key_test`, `webhook_secret_test`, `public_key_live`, `secret_key_live`, `webhook_secret_live`, `bank_name`, `account_number`, `account_name`, `created_at`, `updated_at`) VALUES
(5, 2, 'bank_transfer', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'access bank', '82478658248475', 'Lava Fly', '2026-02-09 17:46:32', '2026-03-12 19:59:01'),
(6, 2, 'paystack', 1, 0, '', NULL, '', '', '2wFU5zuDj4i0Tppls2j1fjo6K2w2YUhLNXBvS2YyVzdIdFJlTHE3ZXRjMEV2R2RTZDhqVzk2eUtoNHl2aHRRZGRaZTVLR1JrbHJiTlVtVVE3ZXNSMkZzZ3lWdTh5aVppWUc3aGJmWUE9PQ==', '', NULL, NULL, NULL, '2026-02-10 17:30:59', '2026-02-14 10:23:24'),
(7, 3, 'bank_transfer', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'Monie point', '5834952438', 'Balcony regency suite', '2026-02-13 11:26:32', '2026-02-13 11:26:32'),
(8, 36, 'bank_transfer', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'Opay', '8107207225', 'Ayodotun', '2026-09-06 04:58:11', '2026-09-06 04:58:11');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_qr_codes`
--

CREATE TABLE `restaurant_qr_codes` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `qr_template_id` int(11) DEFAULT 1,
  `override_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`override_json`)),
  `final_config_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`final_config_json`)),
  `background_color` varchar(7) DEFAULT '#FFFFFF',
  `qr_color` varchar(7) DEFAULT '#000000',
  `text_content` text DEFAULT NULL,
  `text_color` varchar(7) DEFAULT '#000000',
  `text_size` int(11) DEFAULT 16,
  `text_font` varchar(100) DEFAULT 'Arial',
  `qr_size` int(11) DEFAULT 300,
  `margin` int(11) DEFAULT 20,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `restaurant_qr_codes`
--

INSERT INTO `restaurant_qr_codes` (`id`, `restaurant_id`, `qr_template_id`, `override_json`, `final_config_json`, `background_color`, `qr_color`, `text_content`, `text_color`, `text_size`, `text_font`, `qr_size`, `margin`, `is_active`, `created_at`, `updated_at`) VALUES
(48, 21, 19, NULL, '{\"pattern\":\"square\",\"eyes\":\"square\",\"frame\":{\"type\":\"none\",\"text\":\"SCAN ME\",\"color\":\"#000000\",\"text_color\":\"#ffffff\",\"text_size\":14,\"bg_enabled\":true,\"bg_color\":\"#000000\"},\"colors\":{\"foreground\":\"#000000\",\"background\":\"#ffffff\"},\"logo\":{\"enabled\":false,\"size\":0.200000000000000011102230246251565404236316680908203125,\"center_only\":true}}', '#FFFFFF', '#000000', 'Scan to view menu', '#000000', 16, 'Arial', 300, 20, 1, '2026-05-10 15:07:51', '2026-06-14 16:00:56'),
(49, 2, 18, '{\"colors\":{\"foreground\":\"#000000\",\"background\":\"#ffffff\"},\"text_content\":\"\",\"text_color\":\"#000000\",\"text_size\":16}', '{\"pattern\":\"square\",\"eyes\":\"rounded\",\"frame\":{\"type\":\"rounded\",\"text\":\"SCAN ME\",\"color\":\"#051357\",\"text_color\":\"#ffffff\",\"text_size\":14,\"bg_enabled\":true,\"bg_color\":\"#252154\"},\"colors\":{\"foreground\":\"#f7f7f7\",\"background\":\"#2f326f\"},\"logo\":{\"enabled\":false,\"size\":0.200000000000000011102230246251565404236316680908203125,\"center_only\":true}}', '#ffffff', '#000000', '', '#000000', 16, 'Arial', 300, 20, 1, '2025-12-22 18:18:47', '2026-05-27 03:28:16'),
(50, 19, 19, NULL, '{\"pattern\":\"square\",\"eyes\":\"square\",\"frame\":{\"type\":\"none\",\"text\":\"SCAN ME\",\"color\":\"#000000\",\"text_color\":\"#ffffff\",\"text_size\":14,\"bg_enabled\":true,\"bg_color\":\"#000000\"},\"colors\":{\"foreground\":\"#000000\",\"background\":\"#ffffff\"},\"logo\":{\"enabled\":false,\"size\":0.200000000000000011102230246251565404236316680908203125,\"center_only\":true}}', '#FFFFFF', '#000000', 'Scan to view menu', '#000000', 16, 'Arial', 300, 20, 1, '2026-04-06 17:20:24', '2026-05-27 19:51:07'),
(51, 20, NULL, NULL, NULL, '#FFFFFF', '#000000', 'Scan to view menu', '#000000', 16, 'Arial', 300, 20, 1, '2026-04-28 20:08:30', '2026-04-28 20:08:30'),
(52, 13, 19, NULL, '{\"pattern\":\"square\",\"eyes\":\"square\",\"frame\":{\"type\":\"none\",\"text\":\"SCAN ME\",\"color\":\"#000000\",\"text_color\":\"#ffffff\",\"text_size\":14,\"bg_enabled\":true,\"bg_color\":\"#000000\"},\"colors\":{\"foreground\":\"#000000\",\"background\":\"#ffffff\"},\"logo\":{\"enabled\":false,\"size\":0.200000000000000011102230246251565404236316680908203125,\"center_only\":true}}', '#FFFFFF', '#000000', 'Scan to view menu', '#000000', 16, 'Arial', 300, 20, 1, '2026-03-12 23:11:55', '2026-03-20 17:08:59'),
(53, 25, 19, NULL, '{\"pattern\":\"square\",\"eyes\":\"square\",\"frame\":{\"type\":\"none\",\"text\":\"SCAN ME\",\"color\":\"#000000\",\"text_color\":\"#ffffff\",\"text_size\":14,\"bg_enabled\":true,\"bg_color\":\"#000000\"},\"colors\":{\"foreground\":\"#000000\",\"background\":\"#ffffff\"},\"logo\":{\"enabled\":false,\"size\":0.200000000000000011102230246251565404236316680908203125,\"center_only\":true}}', '#FFFFFF', '#000000', 'Scan to view menu', '#000000', 16, 'Arial', 300, 20, 1, '2026-05-27 14:05:22', '2026-05-28 16:43:17'),
(54, 3, 18, NULL, '{\"pattern\":\"square\",\"eyes\":\"rounded\",\"frame\":{\"type\":\"rounded\",\"text\":\"SCAN ME\",\"color\":\"#051357\",\"text_color\":\"#ffffff\",\"text_size\":14,\"bg_enabled\":true,\"bg_color\":\"#252154\"},\"colors\":{\"foreground\":\"#f7f7f7\",\"background\":\"#2f326f\"},\"logo\":{\"enabled\":false,\"size\":0.200000000000000011102230246251565404236316680908203125,\"center_only\":true}}', '#FFFFFF', '#000000', 'Scan to view menu', '#000000', 16, 'Arial', 300, 20, 1, '2026-03-10 14:39:22', '2026-03-10 16:03:43'),
(55, 27, NULL, NULL, NULL, '#FFFFFF', '#000000', 'Scan to view menu', '#000000', 16, 'Arial', 300, 20, 1, '2026-05-19 13:18:44', '2026-05-19 13:18:44');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_reservation_settings`
--

CREATE TABLE `restaurant_reservation_settings` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `deposit_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `restaurant_reservation_settings`
--

INSERT INTO `restaurant_reservation_settings` (`id`, `restaurant_id`, `deposit_amount`, `created_at`, `updated_at`) VALUES
(1, 2, 25000.00, '2026-02-12 04:52:44', '2026-03-12 17:26:28'),
(2, 3, 2000.00, '2026-02-13 11:34:30', '2026-03-12 14:44:23'),
(6, 13, 5000.00, '2026-03-14 18:25:58', '2026-03-14 18:25:58');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `restaurant_id`, `name`, `slug`, `image`, `display_order`, `is_active`, `created_at`, `updated_at`) VALUES
(35, 21, 'Food Menu', 'food-menu', NULL, 1, 1, '2026-05-13 18:58:05', '2026-06-05 21:46:28'),
(36, 21, 'Drink Menu', 'drink-menu', NULL, 2, 1, '2026-05-13 18:59:00', '2026-06-05 21:46:38'),
(37, 21, 'Pastry', 'pastry', NULL, 3, 1, '2026-05-19 21:40:37', '2026-07-18 16:48:58'),
(38, 2, 'General', 'general', NULL, 1, 1, '2026-03-13 01:58:22', '2026-03-13 01:58:22'),
(39, 4, 'Food Menu', 'food-menu', '6a03d58aa458b.webp', 1, 1, '2026-05-13 01:27:51', '2026-05-13 01:36:10'),
(40, 4, 'Drink Menu', 'drink-menu', '6a045f3fc2b51.webp', 2, 1, '2026-05-13 01:27:51', '2026-05-13 11:23:43'),
(41, 4, 'Brunch Menu', 'brunch-menu', NULL, 3, 0, '2026-05-13 01:27:51', '2026-08-25 18:11:14'),
(42, 4, 'Shisha Menu', 'shisha-menu', NULL, 4, 1, '2026-05-13 01:27:51', '2026-05-13 01:27:51'),
(43, 19, 'Food Menu', 'food-menu', '6a0baf965f3c8.jpg', 2, 1, '2026-05-19 00:02:52', '2026-05-27 19:47:07'),
(44, 19, 'Drinks Menu', 'drink-menu', '6a0baf9fcc23d.jpg', 4, 1, '2026-05-19 00:02:52', '2026-05-27 19:47:07'),
(45, 19, 'OPAL CAFE MENU', 'opal-cafe-menu', NULL, 1, 1, '2026-05-27 19:47:07', '2026-05-27 19:47:07'),
(46, 26, 'Food Menu', 'food-menu', '6a0a38be1043e.webp', 1, 1, '2026-05-17 21:36:14', '2026-05-17 21:53:02'),
(47, 26, 'Drinks Menu', 'drink-menu', '6a0a38d0255ef.webp', 2, 1, '2026-05-17 21:36:14', '2026-05-17 21:53:20'),
(48, 20, 'Swiss Flavour', 'food-menu', NULL, 1, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(49, 20, 'Ted & Co', 'drink-menu', NULL, 2, 1, '2026-05-19 01:47:10', '2026-05-19 01:47:10'),
(50, 13, 'In-Room Dinning', 'in-room-dinning', 'ILyWPscEMxYh.jpg', 2, 1, '2026-03-13 09:42:21', '2026-06-21 01:11:02'),
(51, 13, 'À La Carte Menu', 'a-la-carte-menu', 'QQvkluPMkYYw.jpg', 1, 1, '2026-03-13 10:13:02', '2026-06-21 01:11:17'),
(52, 13, 'Drinks', 'drinks', 'l1k8CSVnV5KT.png', 3, 1, '2026-03-14 17:42:54', '2026-06-21 00:59:54'),
(53, 25, 'WING MANIA', 'wing-mania', '6a0661d54bc83.webp', 1, 1, '2026-05-14 22:48:10', '2026-05-14 23:59:17'),
(54, 25, 'MANIA BRUNCH', 'mania-brunch', '6a066233d7d61.webp', 2, 1, '2026-05-14 22:48:10', '2026-05-15 00:00:51'),
(55, 25, 'HOOKAH MANIA', 'hookah-mania', '6a0666ea82b91.webp', 3, 1, '2026-05-14 22:48:10', '2026-05-15 00:20:58'),
(56, 3, 'General', 'general', NULL, 5, 1, '2026-03-13 01:58:22', '2026-03-13 02:20:56'),
(57, 3, 'FOOD', 'food', NULL, 1, 1, '2026-03-13 02:20:39', '2026-03-13 02:34:20'),
(58, 3, 'DRINKS', 'drinks', NULL, 2, 1, '2026-03-13 02:20:56', '2026-03-13 02:34:20'),
(59, 27, 'Main Menu', 'main-menu', '6a0c6a15941be.webp', 1, 1, '2026-05-19 13:18:05', '2026-05-19 13:48:05'),
(60, 27, 'Afro & Continental Menu', 'food-menu', '6a0c6a251fed9.webp', 2, 1, '2026-05-19 13:18:05', '2026-05-19 13:48:21'),
(61, 27, 'Tacos Bar Menu', 'drink-menu', '6a0c6a3239758.webp', 3, 1, '2026-05-19 13:18:05', '2026-05-19 13:48:34'),
(62, 30, 'Burger', 'burger', NULL, 1, 1, '2026-07-28 20:50:09', '2026-07-28 20:50:09'),
(63, 32, 'food', 'food', NULL, 1, 1, '2026-08-14 16:54:20', '2026-08-14 16:54:20'),
(64, 32, 'drinks', 'drinks', NULL, 2, 1, '2026-08-14 16:54:32', '2026-08-14 16:54:47'),
(65, 34, 'Grills and sides', 'grills-and-sides', 'mrQ0fgHvQhcx.jpg', 1, 1, '2026-08-17 14:19:45', '2026-08-20 16:58:14'),
(66, 34, 'Stir-Fry Pasta and Grilled chicken', 'stir-fry-pasta-and-grilled-chicken', NULL, 2, 1, '2026-08-20 17:02:52', '2026-08-20 17:02:52'),
(67, 36, 'Food', 'food', NULL, 1, 1, '2026-09-06 05:06:34', '2026-09-06 05:06:34');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(255) NOT NULL DEFAULT 'Resmenu',
  `site_logo` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `contact_sales_email` varchar(255) DEFAULT NULL,
  `contact_sales_phone` varchar(50) DEFAULT NULL,
  `contact_support_email` varchar(255) DEFAULT NULL,
  `contact_support_phone` varchar(50) DEFAULT NULL,
  `contact_partners_email` varchar(255) DEFAULT NULL,
  `contact_form_recipient` varchar(255) DEFAULT NULL,
  `contact_hq_title` varchar(255) DEFAULT NULL,
  `contact_hq_address` text DEFAULT NULL,
  `contact_map_embed` text DEFAULT NULL,
  `contact_social_facebook` varchar(255) DEFAULT NULL,
  `contact_social_twitter` varchar(255) DEFAULT NULL,
  `contact_social_instagram` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `site_name`, `site_logo`, `favicon`, `contact_sales_email`, `contact_sales_phone`, `contact_support_email`, `contact_support_phone`, `contact_partners_email`, `contact_form_recipient`, `contact_hq_title`, `contact_hq_address`, `contact_map_embed`, `contact_social_facebook`, `contact_social_twitter`, `contact_social_instagram`, `created_at`, `updated_at`) VALUES
(1, 'Resmenu', '69a96b49c1b02.png', '69a96b49c1e53.jpg', 'sales@resmenu.net', '+234 RES MENU', 'support@resmenu.net', '+2340946434664', 'partners@resmenu.net', 'info@resmenu.net', 'Laagos HQ', 'Ogombo Road\r\nCitadel view Estate along Ogumbo Road Off Abraham Adesayan', NULL, 'https://our-menu.online/admin/settings.php', 'https://our-menu.online/admin/settings.php', 'https://our-menu.online/admin/settings.php', '2026-02-12 23:18:09', '2026-05-29 23:40:40');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `billing_cycle` enum('monthly','annual') NOT NULL DEFAULT 'monthly',
  `status` enum('trial','active','expired','cancelled','pending') NOT NULL DEFAULT 'trial',
  `trial_ends_at` datetime DEFAULT NULL COMMENT '7 days from creation',
  `current_period_start` datetime DEFAULT NULL,
  `current_period_end` datetime DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `restaurant_id`, `plan_id`, `billing_cycle`, `status`, `trial_ends_at`, `current_period_start`, `current_period_end`, `cancelled_at`, `created_at`, `updated_at`) VALUES
(27, 21, 3, 'monthly', 'trial', '2027-07-13 17:58:59', NULL, NULL, NULL, '2026-05-09 17:25:57', '2026-07-13 17:58:59'),
(28, 2, 3, 'monthly', 'expired', NULL, '2026-03-08 20:39:58', '2026-06-18 14:38:03', NULL, '2025-12-24 03:13:58', '2026-06-19 00:05:22'),
(29, 4, 3, 'monthly', 'active', NULL, '2026-08-19 15:00:30', '2026-09-19 15:00:30', NULL, '2026-03-03 23:30:50', '2026-08-19 15:00:30'),
(30, 19, 2, 'annual', 'active', '2026-04-13 17:01:14', '2026-04-06 17:18:09', '2027-04-06 17:18:09', NULL, '2026-04-06 17:01:14', '2026-04-06 17:18:09'),
(31, 26, 3, 'monthly', 'expired', '2026-06-23 21:04:00', NULL, NULL, NULL, '2026-05-17 21:04:00', '2026-06-26 11:13:14'),
(32, 20, 3, 'monthly', 'expired', '2026-06-18 01:48:53', NULL, NULL, NULL, '2026-04-28 20:07:22', '2026-06-08 13:43:31'),
(33, 13, 3, 'monthly', 'active', '2026-05-18 23:11:27', NULL, '2027-08-12 13:29:58', NULL, '2026-03-12 23:11:27', '2026-06-17 14:45:03'),
(34, 25, 3, 'monthly', 'active', NULL, '2026-07-01 21:14:03', '2027-08-17 15:46:27', NULL, '2026-05-14 14:20:15', '2026-08-10 15:48:52'),
(35, 3, 3, 'monthly', 'expired', '2026-02-20 08:57:39', '2026-03-07 20:46:55', '2026-07-20 14:54:00', NULL, '2026-02-13 08:57:39', '2026-08-10 15:46:09'),
(36, 27, 3, 'monthly', 'expired', NULL, '2026-06-02 00:05:12', '2026-07-02 00:05:12', NULL, '2026-05-19 12:18:11', '2026-07-02 12:29:14'),
(37, 28, 2, 'monthly', 'expired', '2026-06-15 12:22:12', NULL, NULL, NULL, '2026-06-08 12:22:12', '2026-06-16 00:15:03'),
(38, 29, 2, 'monthly', 'expired', '2026-06-30 04:52:31', NULL, NULL, NULL, '2026-06-23 04:52:31', '2026-07-01 21:06:24'),
(39, 30, 2, 'monthly', 'expired', '2026-08-04 20:30:53', NULL, NULL, NULL, '2026-07-28 20:30:53', '2026-08-10 15:46:09'),
(40, 31, 2, 'monthly', 'expired', '2026-08-18 21:46:21', NULL, NULL, NULL, '2026-08-11 21:46:21', '2026-08-19 14:58:09'),
(41, 32, 1, 'monthly', 'trial', '2026-08-21 16:53:31', NULL, NULL, NULL, '2026-08-14 16:53:31', '2026-08-14 16:53:31'),
(42, 33, 2, 'monthly', 'trial', '2026-08-23 06:27:56', NULL, NULL, NULL, '2026-08-16 06:27:56', '2026-08-16 06:27:56'),
(43, 34, 2, 'monthly', 'trial', '2026-08-24 14:03:42', NULL, NULL, NULL, '2026-08-17 14:03:42', '2026-08-17 14:03:42'),
(44, 35, 2, 'monthly', 'trial', '2026-08-26 02:10:12', NULL, NULL, NULL, '2026-08-19 02:10:12', '2026-08-19 02:10:12'),
(45, 36, 2, 'monthly', 'trial', '2026-09-13 04:45:56', NULL, NULL, NULL, '2026-09-06 04:45:56', '2026-09-06 04:45:56'),
(46, 37, 2, 'monthly', 'trial', '2026-09-14 09:53:51', NULL, NULL, NULL, '2026-09-07 09:53:51', '2026-09-07 09:53:51');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_change_requests`
--

CREATE TABLE `subscription_change_requests` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `from_plan_id` int(11) NOT NULL,
  `to_plan_id` int(11) NOT NULL,
  `from_billing_cycle` varchar(20) NOT NULL,
  `to_billing_cycle` varchar(20) NOT NULL,
  `change_type` varchar(50) NOT NULL,
  `effective_at` datetime NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `requested_by` varchar(20) DEFAULT 'manager',
  `applied_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_emails`
--

CREATE TABLE `subscription_emails` (
  `id` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `email_type` varchar(50) NOT NULL COMMENT 'trial_ending, payment_reminder, payment_success, expired',
  `days_before` int(11) DEFAULT NULL COMMENT '30, 15, 7, 3, 1 for reminders',
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_email_log`
--

CREATE TABLE `subscription_email_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subscription_id` bigint(20) UNSIGNED NOT NULL,
  `email_type` varchar(50) NOT NULL,
  `days_before` int(11) NOT NULL DEFAULT 0,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_email_log`
--

INSERT INTO `subscription_email_log` (`id`, `subscription_id`, `email_type`, `days_before`, `sent_at`) VALUES
(1, 29, 'trial_ending', 7, '2026-06-05 09:00:04'),
(2, 27, 'trial_ending', 7, '2026-06-11 09:00:04'),
(3, 29, 'trial_ending', 1, '2026-06-11 09:00:04'),
(4, 34, 'trial_ending', 7, '2026-06-14 09:00:04'),
(5, 37, 'trial_ending', 1, '2026-06-14 09:00:04'),
(6, 31, 'trial_ending', 7, '2026-06-16 09:00:04');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT 'Basic, Professional, Enterprise',
  `slug` varchar(50) NOT NULL COMMENT 'basic, professional, enterprise',
  `description` text DEFAULT NULL,
  `monthly_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Price in NGN',
  `annual_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Price in NGN (20% discount)',
  `yearly_discount_percent` decimal(5,2) NOT NULL DEFAULT 20.00 COMMENT 'Discount % applied to yearly plan (annual price = monthly*12*(1 - this/100))',
  `max_categories` int(11) NOT NULL DEFAULT 5 COMMENT '-1 for unlimited',
  `max_menu_items` int(11) NOT NULL DEFAULT 50 COMMENT '-1 for unlimited',
  `max_qr_styles` int(11) NOT NULL DEFAULT 3 COMMENT '-1 for unlimited',
  `max_templates` int(11) NOT NULL DEFAULT 3 COMMENT '-1 for unlimited',
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Additional features as JSON',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`id`, `name`, `slug`, `description`, `monthly_price`, `annual_price`, `yearly_discount_percent`, `max_categories`, `max_menu_items`, `max_qr_styles`, `max_templates`, `features`, `is_active`, `display_order`, `created_at`, `updated_at`) VALUES
(1, 'Basic', 'basic', 'Perfect for small restaurants just getting started with digital menus.', 8000.00, 62400.00, 35.00, 20, 150, 5, 5, '{\"priority_support\":false,\"custom_domain\":false,\"analytics_advanced\":false,\"food_ordering\":false,\"table_reservations\":false}', 1, 1, '2025-12-24 02:38:31', '2026-06-12 13:30:48'),
(2, 'Professional', 'professional', 'Ideal for growing restaurants with multiple menu categories.', 15500.00, 120900.00, 35.00, 50, 400, 7, 7, '{\"priority_support\":true,\"custom_domain\":false,\"analytics_advanced\":true,\"food_ordering\":true,\"table_reservations\":true}', 1, 2, '2025-12-24 02:38:31', '2026-05-20 16:47:54'),
(3, 'Enterprise', 'enterprise', 'Full-featured solution for large restaurants and chains.', 25700.00, 200460.00, 35.00, -1, -1, -1, -1, '{\"priority_support\": true, \"custom_domain\": true, \"analytics_advanced\": true, \"food_ordering\": true, \"table_reservations\": true}', 1, 3, '2025-12-24 02:38:31', '2026-03-13 01:58:22');

-- --------------------------------------------------------

--
-- Table structure for table `table_inventory_daily`
--

CREATE TABLE `table_inventory_daily` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `inventory_date` date NOT NULL,
  `total_tables` int(11) NOT NULL DEFAULT 10,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `table_inventory_daily`
--

INSERT INTO `table_inventory_daily` (`id`, `restaurant_id`, `inventory_date`, `total_tables`, `created_at`, `updated_at`) VALUES
(1, 2, '2026-02-25', 10, '2026-02-12 22:10:57', '2026-02-12 22:49:21'),
(3, 2, '2026-02-19', 59, '2026-02-12 22:55:24', '2026-02-12 22:55:24'),
(4, 2, '2026-02-20', 59, '2026-02-12 22:55:24', '2026-02-12 22:55:24'),
(5, 2, '2026-02-21', 59, '2026-02-12 22:55:24', '2026-02-12 22:55:24'),
(6, 2, '2026-02-22', 59, '2026-02-12 22:55:24', '2026-02-12 22:55:24'),
(7, 2, '2026-03-01', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(8, 2, '2026-03-02', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(9, 2, '2026-03-03', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(10, 2, '2026-03-04', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(11, 2, '2026-03-05', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(12, 2, '2026-03-06', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(13, 2, '2026-03-07', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(14, 2, '2026-03-08', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(15, 2, '2026-03-09', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(16, 2, '2026-03-10', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(17, 2, '2026-03-11', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(18, 2, '2026-03-12', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(19, 2, '2026-03-13', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(20, 2, '2026-03-14', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(21, 2, '2026-03-15', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(22, 2, '2026-03-16', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(23, 2, '2026-03-17', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(24, 2, '2026-03-18', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(25, 2, '2026-03-19', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(26, 2, '2026-03-20', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(27, 2, '2026-03-21', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(28, 2, '2026-03-22', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(29, 2, '2026-03-23', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(30, 2, '2026-03-24', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(31, 2, '2026-03-25', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(32, 2, '2026-03-26', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(33, 2, '2026-03-27', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(34, 2, '2026-03-28', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(35, 2, '2026-03-29', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(36, 2, '2026-03-30', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(37, 2, '2026-03-31', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(38, 2, '2026-04-01', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(39, 2, '2026-04-02', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(40, 2, '2026-04-03', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(41, 2, '2026-04-04', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(42, 2, '2026-04-05', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(43, 2, '2026-04-06', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(44, 2, '2026-04-07', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(45, 2, '2026-04-08', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(46, 2, '2026-04-09', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(47, 2, '2026-04-10', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(48, 2, '2026-04-11', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(49, 2, '2026-04-12', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(50, 2, '2026-04-13', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(51, 2, '2026-04-14', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(52, 2, '2026-04-15', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(53, 2, '2026-04-16', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(54, 2, '2026-04-17', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(55, 2, '2026-04-18', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(56, 2, '2026-04-19', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(57, 2, '2026-04-20', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(58, 2, '2026-04-21', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(59, 2, '2026-04-22', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(60, 2, '2026-04-23', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(61, 2, '2026-04-24', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(62, 2, '2026-04-25', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(63, 2, '2026-04-26', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(64, 2, '2026-04-27', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(65, 2, '2026-04-28', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(66, 2, '2026-04-29', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(67, 2, '2026-04-30', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(68, 2, '2026-05-01', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(69, 2, '2026-05-02', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(70, 2, '2026-05-03', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(71, 2, '2026-05-04', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(72, 2, '2026-05-05', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(73, 2, '2026-05-06', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(74, 2, '2026-05-07', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(75, 2, '2026-05-08', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(76, 2, '2026-05-09', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(77, 2, '2026-05-10', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(78, 2, '2026-05-11', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(79, 2, '2026-05-12', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(80, 2, '2026-05-13', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(81, 2, '2026-05-14', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(82, 2, '2026-05-15', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(83, 2, '2026-05-16', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(84, 2, '2026-05-17', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(85, 2, '2026-05-18', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(86, 2, '2026-05-19', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(87, 2, '2026-05-20', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(88, 2, '2026-05-21', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(89, 2, '2026-05-22', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(90, 2, '2026-05-23', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(91, 2, '2026-05-24', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(92, 2, '2026-05-25', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(93, 2, '2026-05-26', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(94, 2, '2026-05-27', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(95, 2, '2026-05-28', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(96, 2, '2026-05-29', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(97, 2, '2026-05-30', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(98, 2, '2026-05-31', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(99, 2, '2026-06-01', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(100, 2, '2026-06-02', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(101, 2, '2026-06-03', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(102, 2, '2026-06-04', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(103, 2, '2026-06-05', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(104, 2, '2026-06-06', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(105, 2, '2026-06-07', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(106, 2, '2026-06-08', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(107, 2, '2026-06-09', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(108, 2, '2026-06-10', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(109, 2, '2026-06-11', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(110, 2, '2026-06-12', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(111, 2, '2026-06-13', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(112, 2, '2026-06-14', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(113, 2, '2026-06-15', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(114, 2, '2026-06-16', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(115, 2, '2026-06-17', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(116, 2, '2026-06-18', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(117, 2, '2026-06-19', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(118, 2, '2026-06-20', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(119, 2, '2026-06-21', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(120, 2, '2026-06-22', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(121, 2, '2026-06-23', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(122, 2, '2026-06-24', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(123, 2, '2026-06-25', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(124, 2, '2026-06-26', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(125, 2, '2026-06-27', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(126, 2, '2026-06-28', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(127, 2, '2026-06-29', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(128, 2, '2026-06-30', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(129, 2, '2026-07-01', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(130, 2, '2026-07-02', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(131, 2, '2026-07-03', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(132, 2, '2026-07-04', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(133, 2, '2026-07-05', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(134, 2, '2026-07-06', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(135, 2, '2026-07-07', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(136, 2, '2026-07-08', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(137, 2, '2026-07-09', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(138, 2, '2026-07-10', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(139, 2, '2026-07-11', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(140, 2, '2026-07-12', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(141, 2, '2026-07-13', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(142, 2, '2026-07-14', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(143, 2, '2026-07-15', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(144, 2, '2026-07-16', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(145, 2, '2026-07-17', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(146, 2, '2026-07-18', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(147, 2, '2026-07-19', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(148, 2, '2026-07-20', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(149, 2, '2026-07-21', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(150, 2, '2026-07-22', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(151, 2, '2026-07-23', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(152, 2, '2026-07-24', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(153, 2, '2026-07-25', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(154, 2, '2026-07-26', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(155, 2, '2026-07-27', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(156, 2, '2026-07-28', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(157, 2, '2026-07-29', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(158, 2, '2026-07-30', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(159, 2, '2026-07-31', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(160, 2, '2026-08-01', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(161, 2, '2026-08-02', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(162, 2, '2026-08-03', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(163, 2, '2026-08-04', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(164, 2, '2026-08-05', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(165, 2, '2026-08-06', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(166, 2, '2026-08-07', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(167, 2, '2026-08-08', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(168, 2, '2026-08-09', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(169, 2, '2026-08-10', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(170, 2, '2026-08-11', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(171, 2, '2026-08-12', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(172, 2, '2026-08-13', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(173, 2, '2026-08-14', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(174, 2, '2026-08-15', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(175, 2, '2026-08-16', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(176, 2, '2026-08-17', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(177, 2, '2026-08-18', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(178, 2, '2026-08-19', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(179, 2, '2026-08-20', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(180, 2, '2026-08-21', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(181, 2, '2026-08-22', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(182, 2, '2026-08-23', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(183, 2, '2026-08-24', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(184, 2, '2026-08-25', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(185, 2, '2026-08-26', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(186, 2, '2026-08-27', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(187, 2, '2026-08-28', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(188, 2, '2026-08-29', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(189, 2, '2026-08-30', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(190, 2, '2026-08-31', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(191, 2, '2026-09-01', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(192, 2, '2026-09-02', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(193, 2, '2026-09-03', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(194, 2, '2026-09-04', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(195, 2, '2026-09-05', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(196, 2, '2026-09-06', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(197, 2, '2026-09-07', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(198, 2, '2026-09-08', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(199, 2, '2026-09-09', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(200, 2, '2026-09-10', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(201, 2, '2026-09-11', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(202, 2, '2026-09-12', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(203, 2, '2026-09-13', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(204, 2, '2026-09-14', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(205, 2, '2026-09-15', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(206, 2, '2026-09-16', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(207, 2, '2026-09-17', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(208, 2, '2026-09-18', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(209, 2, '2026-09-19', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(210, 2, '2026-09-20', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(211, 2, '2026-09-21', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(212, 2, '2026-09-22', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(213, 2, '2026-09-23', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(214, 2, '2026-09-24', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(215, 2, '2026-09-25', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(216, 2, '2026-09-26', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(217, 2, '2026-09-27', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(218, 2, '2026-09-28', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(219, 2, '2026-09-29', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(220, 2, '2026-09-30', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(221, 2, '2026-10-01', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(222, 2, '2026-10-02', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(223, 2, '2026-10-03', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(224, 2, '2026-10-04', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(225, 2, '2026-10-05', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(226, 2, '2026-10-06', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(227, 2, '2026-10-07', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(228, 2, '2026-10-08', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(229, 2, '2026-10-09', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(230, 2, '2026-10-10', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(231, 2, '2026-10-11', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(232, 2, '2026-10-12', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(233, 2, '2026-10-13', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(234, 2, '2026-10-14', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(235, 2, '2026-10-15', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(236, 2, '2026-10-16', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(237, 2, '2026-10-17', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(238, 2, '2026-10-18', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(239, 2, '2026-10-19', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(240, 2, '2026-10-20', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(241, 2, '2026-10-21', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(242, 2, '2026-10-22', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(243, 2, '2026-10-23', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(244, 2, '2026-10-24', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(245, 2, '2026-10-25', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(246, 2, '2026-10-26', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(247, 2, '2026-10-27', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(248, 2, '2026-10-28', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(249, 2, '2026-10-29', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(250, 2, '2026-10-30', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(251, 2, '2026-10-31', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(252, 2, '2026-11-01', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(253, 2, '2026-11-02', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(254, 2, '2026-11-03', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(255, 2, '2026-11-04', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(256, 2, '2026-11-05', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(257, 2, '2026-11-06', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(258, 2, '2026-11-07', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(259, 2, '2026-11-08', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(260, 2, '2026-11-09', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(261, 2, '2026-11-10', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(262, 2, '2026-11-11', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(263, 2, '2026-11-12', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(264, 2, '2026-11-13', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(265, 2, '2026-11-14', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(266, 2, '2026-11-15', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(267, 2, '2026-11-16', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(268, 2, '2026-11-17', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(269, 2, '2026-11-18', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(270, 2, '2026-11-19', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(271, 2, '2026-11-20', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(272, 2, '2026-11-21', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(273, 2, '2026-11-22', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(274, 2, '2026-11-23', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(275, 2, '2026-11-24', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(276, 2, '2026-11-25', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(277, 2, '2026-11-26', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(278, 2, '2026-11-27', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(279, 2, '2026-11-28', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(280, 2, '2026-11-29', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(281, 2, '2026-11-30', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(282, 2, '2026-12-01', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(283, 2, '2026-12-02', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(284, 2, '2026-12-03', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(285, 2, '2026-12-04', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(286, 2, '2026-12-05', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(287, 2, '2026-12-06', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(288, 2, '2026-12-07', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(289, 2, '2026-12-08', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(290, 2, '2026-12-09', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(291, 2, '2026-12-10', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(292, 2, '2026-12-11', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(293, 2, '2026-12-12', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(294, 2, '2026-12-13', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(295, 2, '2026-12-14', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(296, 2, '2026-12-15', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(297, 2, '2026-12-16', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(298, 2, '2026-12-17', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(299, 2, '2026-12-18', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(300, 2, '2026-12-19', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(301, 2, '2026-12-20', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(302, 2, '2026-12-21', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(303, 2, '2026-12-22', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(304, 2, '2026-12-23', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(305, 2, '2026-12-24', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(306, 2, '2026-12-25', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(307, 2, '2026-12-26', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(308, 2, '2026-12-27', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(309, 2, '2026-12-28', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(310, 2, '2026-12-29', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(311, 2, '2026-12-30', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20'),
(312, 2, '2026-12-31', 10, '2026-03-12 18:32:20', '2026-03-12 18:32:20');

-- --------------------------------------------------------

--
-- Table structure for table `table_reservations`
--

CREATE TABLE `table_reservations` (
  `id` int(11) NOT NULL,
  `reservation_number` varchar(10) DEFAULT NULL,
  `restaurant_id` int(11) NOT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `party_size` int(11) NOT NULL DEFAULT 1,
  `guest_name` varchar(255) NOT NULL,
  `guest_email` varchar(255) NOT NULL,
  `guest_phone` varchar(50) NOT NULL,
  `special_occasion` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `deposit_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `deposit_paid` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(50) NOT NULL DEFAULT 'pending' COMMENT 'pending, confirmed, cancelled, completed',
  `is_walkin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `table_reservations`
--

INSERT INTO `table_reservations` (`id`, `reservation_number`, `restaurant_id`, `reservation_date`, `reservation_time`, `party_size`, `guest_name`, `guest_email`, `guest_phone`, `special_occasion`, `notes`, `deposit_amount`, `deposit_paid`, `status`, `is_walkin`, `created_at`, `updated_at`) VALUES
(23, NULL, 2, '2026-02-13', '19:00:00', 3, 'carter tech', 'mr.carter.tech07@gmail.com', '91347593', 'ANNIVERSARY', 'Vvghh', 0.00, 0, 'confirmed', 0, '2026-02-12 02:03:26', '2026-02-12 04:53:03'),
(24, NULL, 2, '2026-02-13', '18:30:00', 3, 'Abdulrahman Shittu', 'mr.carter.tech07@gmail.com', '08032336586', 'BIRTHDAY', 'sfsfs', 0.00, 0, 'pending', 0, '2026-02-12 04:20:31', '2026-02-12 04:20:31'),
(25, NULL, 2, '2026-02-13', '19:00:00', 2, 'Abdulrahman Shittu', 'mr.carter.tech07@gmail.com', '08032336586', 'ANNIVERSARY', 'xg', 599.00, 1, 'pending', 0, '2026-02-12 04:53:31', '2026-02-12 04:53:48'),
(26, NULL, 2, '2026-02-13', '18:00:00', 2, 'Abdulrahman Shittu', 'mr.carter.tech07@gmail.com', '08032336586', 'ANNIVERSARY', 'sfs', 100.00, 1, 'pending', 0, '2026-02-12 15:44:37', '2026-02-12 15:46:13'),
(27, NULL, 2, '2026-02-13', '21:30:00', 2, 'BOULEVARD INTEGRATED SERVICES LIMITED', 'mr.carter.tech07@gmail.com', '08032336586', NULL, 'zs', 150.00, 1, 'pending', 0, '2026-02-12 15:48:50', '2026-02-12 15:49:40'),
(28, NULL, 2, '2026-02-19', '18:30:00', 1, 'dada', 'ad@fj.com', '2224244255', 'ANNIVERSARY', 'sfsf', 150.00, 1, 'confirmed', 0, '2026-02-12 22:08:54', '2026-02-12 22:09:53'),
(29, '4GLULEF9', 2, '2026-02-13', '18:30:00', 1, 'Abdulrahman Shittu', 'mr.carter.tech07@gmail.com', '08032336586', NULL, 'z', 150.00, 1, 'confirmed', 0, '2026-02-12 23:32:46', '2026-02-12 23:32:54'),
(30, '1FC2W3SY', 2, '2026-03-20', '17:30:00', 5, 'Abdulrahman Shittu', 'sigsol2024@gmail.com', '08032336586', 'BUSINESS', 'fssfssfs', 150.00, 0, 'pending', 0, '2026-02-23 11:10:42', '2026-02-23 11:10:42'),
(31, 'QYLCXOT6', 2, '2026-03-09', '19:30:00', 2, 'carter tech', 'mr.carter.tech07@gmail.com', '0946434664', 'BIRTHDAY', NULL, 150.00, 0, 'pending', 0, '2026-03-09 16:38:16', '2026-03-09 16:38:16'),
(32, 'CS4JZZO9', 2, '2026-03-13', '18:30:00', 2, 'Abdulrahman Shittu', 'mr.carter.tech07@gmail.com', '08032336586', NULL, NULL, 25000.00, 0, 'pending', 0, '2026-03-12 19:47:50', '2026-03-12 19:47:50'),
(33, 'BC43GX2E', 13, '2026-04-16', '18:00:00', 4, 'Peculiar', 'pecu15@yahoo.com', '08697029779', 'DATE_NIGHT', NULL, 5000.00, 0, 'pending', 0, '2026-04-15 04:42:36', '2026-04-15 04:42:36'),
(34, 'QMC9VC7M', 13, '2026-04-18', '18:00:00', 2, 'Ayo', 'aayinde@domeoresources.org', '08072785537', NULL, NULL, 5000.00, 0, 'pending', 0, '2026-04-17 08:03:04', '2026-04-17 08:03:04'),
(35, 'BQFTAX7H', 13, '2026-06-30', '20:00:00', 2, 'Aishatu mesh', 'aishatumesh@gmail.com', '07032536086', NULL, 'Bauchi Bauchi sister', 5000.00, 0, 'pending', 0, '2026-05-05 02:58:01', '2026-05-05 02:58:01'),
(36, 'NU5R6NXR', 3, '2026-02-18', '17:30:00', 2, 'carter tech', 'billyfredrickgibbons@gmail.com', '913475935555', 'BIRTHDAY', 'Xx', 0.00, 0, 'pending', 0, '2026-02-13 11:25:27', '2026-02-13 11:25:27'),
(37, 'V6R0W5A2', 3, '2026-02-21', '19:00:00', 3, 'carter tech', 'mr.carter.tech07@gmail.com', '913475938888', NULL, NULL, 0.00, 0, 'pending', 0, '2026-02-13 11:26:55', '2026-02-13 11:26:55'),
(38, 'FE29QJVJ', 3, '2026-02-20', '19:00:00', 1, 'Abdulrahman Shittu', 'mr.carter.tech07@gmail.com', '08032336586', NULL, NULL, 0.00, 0, 'pending', 0, '2026-02-13 11:27:21', '2026-02-13 11:27:21'),
(39, '2HIMIZ0Z', 3, '2026-02-19', '19:00:00', 2, 'Carter', 'mr.carter.tech07@gmail.com', '8855566556625', NULL, NULL, 25000.00, 1, 'confirmed', 0, '2026-02-13 11:35:23', '2026-02-13 11:35:35'),
(40, '63GJP5G6', 3, '2026-02-18', '18:00:00', 2, 'Abdulrahman Shittu', 'mr.carter.tech07@gmail.com', '08032336586', NULL, 'ddd', 25000.00, 0, 'pending', 0, '2026-02-17 14:42:38', '2026-02-17 14:42:38'),
(41, 'A9RPZ59S', 13, '2026-06-21', '19:00:00', 2, 'Mr Peter Okoh', 'okohopeter@gmail.com', '08104060717', 'BIRTHDAY', 'None', 5000.00, 0, 'pending', 0, '2026-06-20 22:03:30', '2026-06-20 22:03:30'),
(42, 'KV33TU6D', 13, '2026-06-22', '18:00:00', 4, 'Abdulrahman Shittu', 'sigsol2024@gmail.com', '08032336586', 'ANNIVERSARY', NULL, 5000.00, 0, 'pending', 0, '2026-06-20 23:25:00', '2026-06-20 23:25:00'),
(43, 'OF9IGI6Q', 13, '2026-07-07', '19:00:00', 2, 'Emmanuel Undiandeye', 'emmanuelundiandeye@gmail.com', '08167628844', 'BIRTHDAY', NULL, 5000.00, 0, 'pending', 0, '2026-07-03 13:58:38', '2026-07-03 13:58:38'),
(44, 'S0D2ZZ3C', 13, '2026-07-21', '22:30:00', 1, 'Ogbonnaya Ngozi', 'amandaastonished@gmail.com', '+2348035340119', NULL, NULL, 5000.00, 0, 'pending', 0, '2026-07-21 15:26:51', '2026-07-21 15:26:51'),
(45, '71U1ZACZ', 13, '2026-07-21', '22:30:00', 1, 'Ogbonnaya Ngozi', 'amandaastonished@gmail.com', '+2348035340119', NULL, NULL, 5000.00, 0, 'pending', 0, '2026-07-21 15:26:52', '2026-07-21 15:26:52'),
(46, 'ECBFIHGD', 13, '2026-07-21', '22:30:00', 1, 'Ogbonnaya Ngozi', 'amandaastonished@gmail.com', '+2348035340119', NULL, NULL, 5000.00, 0, 'pending', 0, '2026-07-21 15:26:52', '2026-07-21 15:26:52'),
(47, '238N2TLS', 13, '2026-07-21', '22:30:00', 1, 'Ogbonnaya Ngozi', 'amandaastonished@gmail.com', '+2348035340119', NULL, NULL, 5000.00, 0, 'pending', 0, '2026-07-21 15:26:53', '2026-07-21 15:26:53');

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `preview_image` varchar(255) DEFAULT NULL,
  `listing_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_private` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`id`, `name`, `slug`, `description`, `preview_image`, `listing_image`, `is_active`, `is_private`, `created_at`, `updated_at`) VALUES
(1, 'Double Kitchen', 'template1', 'Elegant and sophisticated fine dining style with clean typography and alternating layout. Perfect for bistros, full-service restaurants, and venues that want a classic yet modern menu presentation.', '69a9cd81e4873.jpg', '69ab40d07e069.png', 1, 0, '2025-12-19 18:43:07', '2026-05-18 14:02:31'),
(2, 'Natures Table', 'template2', 'Modern restaurant template with hero sections and featured items. Ideal for casual dining, cafes, and bars. Tailwind-based design with a fresh, approachable look.', '69a9cd5c97088.jpg', NULL, 1, 0, '2025-12-19 18:43:07', '2026-05-18 14:01:41'),
(3, 'Dark Navy Gradient', 'template3', 'Dark navy gradient background with bold typography and white cards. Great for lounges, cocktail bars, and upscale venues that want a striking, premium feel.', '69a9ce38d1547.jpg', NULL, 1, 0, '2025-12-19 18:43:07', '2026-03-08 18:19:35'),
(4, 'The Gourmet Grill', 'template4', 'Premium dark-themed design with warm accents and rustic charm. Ideal for steakhouses, grills, and traditional pubs. Features reservation integration and a distinctive atmosphere.', '69a9ce4b7a8f5.jpg', NULL, 1, 0, '2026-02-09 12:24:42', '2026-03-13 09:42:21'),
(5, 'The Prime Cut', 'template5', 'Premium steakhouse menu design with burgundy and gold.', NULL, NULL, 1, 0, '2026-03-08 18:19:35', '2026-05-18 15:09:00'),
(6, 'The Garden Bistro', 'template6', 'Garden bistro style menu template.', NULL, NULL, 1, 0, '2026-03-08 18:19:35', '2026-05-18 15:09:00'),
(7, 'The Art Fusion', 'template7', 'Art fusion restaurant menu design.', NULL, NULL, 1, 0, '2026-03-08 18:19:35', '2026-05-18 15:09:00'),
(8, 'Sweet Delight', 'template8', 'Playful dessert parlour style menu.', NULL, NULL, 1, 0, '2026-03-08 18:19:35', '2026-05-18 15:09:00'),
(9, 'Street Food Hub', 'template9', 'Street food hub menu template.', NULL, NULL, 1, 0, '2026-03-08 18:19:35', '2026-05-18 15:09:00'),
(10, 'Salt N Socials', 'template10', 'Salt N Socials white variant.', NULL, NULL, 1, 1, '2026-03-08 18:19:35', '2026-05-18 15:40:52'),
(11, 'White Party', 'template11', 'Salt N Socials colored variant.', NULL, NULL, 1, 0, '2026-03-08 18:19:35', '2026-05-18 15:41:28'),
(12, 'Mediterranean Fresh', 'template12', 'Mediterranean fresh menu design.', NULL, NULL, 1, 0, '2026-03-08 18:19:35', '2026-05-18 15:09:00'),
(13, 'Forged In Spirit', 'template13', 'Forged In Spirit design.', NULL, NULL, 1, 0, '2026-03-08 18:19:35', '2026-05-18 15:09:00'),
(14, 'Eart Kitchen', 'template14', 'Eart Kitchen menu template.', NULL, NULL, 1, 0, '2026-03-08 18:19:35', '2026-05-18 15:09:00'),
(15, 'Bold Flavours', 'template15', 'Bold flavours menu design.', NULL, NULL, 1, 0, '2026-03-08 18:19:35', '2026-05-18 15:09:00'),
(16, 'Neo Mex Cantina', 'template16', 'Neo Mex Cantina style menu.', NULL, NULL, 1, 0, '2026-03-08 18:19:35', '2026-05-18 15:40:16'),
(17, 'Night Enthusiast', 'template17', 'Nostalgia front page design.', NULL, NULL, 1, 0, '2026-03-08 18:19:35', '2026-05-18 15:29:40'),
(18, 'Nostalgia Food Menu', 'template18', 'Nostalgia food menu design.', NULL, NULL, 1, 1, '2026-03-08 18:19:35', '2026-05-18 15:30:04');

-- --------------------------------------------------------

--
-- Table structure for table `template_customizations`
--

CREATE TABLE `template_customizations` (
  `id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `menu_title_color` varchar(7) DEFAULT '#000000',
  `menu_title_size` int(11) DEFAULT 24,
  `menu_title_font` varchar(50) DEFAULT 'Inter',
  `price_color` varchar(7) DEFAULT '#000000',
  `price_size` int(11) DEFAULT 18,
  `price_font` varchar(50) DEFAULT 'Inter',
  `description_color` varchar(7) DEFAULT '#666666',
  `description_size` int(11) DEFAULT 14,
  `description_font` varchar(50) DEFAULT 'Inter',
  `category_title_color` varchar(7) DEFAULT '#000000',
  `category_title_size` int(11) DEFAULT 20,
  `category_title_font` varchar(50) DEFAULT 'Inter',
  `background_color` varchar(7) DEFAULT '#fffffc',
  `header_background_color` varchar(7) DEFAULT '#fffffc',
  `primary_color` varchar(7) DEFAULT '#111111',
  `secondary_color` varchar(7) DEFAULT '#FFFFFF',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `template_customizations`
--

INSERT INTO `template_customizations` (`id`, `template_id`, `menu_title_color`, `menu_title_size`, `menu_title_font`, `price_color`, `price_size`, `price_font`, `description_color`, `description_size`, `description_font`, `category_title_color`, `category_title_size`, `category_title_font`, `background_color`, `header_background_color`, `primary_color`, `secondary_color`, `created_at`, `updated_at`) VALUES
(1, 4, '#121212', 24, 'Epilogue', '#f20d0d', 18, 'Epilogue', '#666666', 14, 'Epilogue', '#121212', 20, 'Epilogue', '#f8f5f5', '#121212', '#f20d0d', '#FFFFFF', '2026-02-09 12:24:42', '2026-03-13 09:42:21'),
(20, 1, '#1A1A1A', 24, 'Inter', '#1A1A1A', 18, 'Inter', '#666666', 14, 'Inter', '#1A1A1A', 20, 'Inter', '#FFFFFF', '#FFFFFF', '#1A1A1A', '#FAF3E6', '2026-02-13 09:22:33', '2026-03-13 09:42:21'),
(21, 2, '#1A1A1A', 24, 'Inter', '#ea2a33', 18, 'Inter', '#666666', 14, 'Inter', '#1A1A1A', 20, 'Inter', '#f8f6f6', '#f8f6f6', '#ea2a33', '#FFFFFF', '2026-02-13 09:22:33', '2026-03-13 09:42:21'),
(22, 3, '#1A1A1A', 24, 'Inter', '#ea2a33', 18, 'Inter', '#666666', 14, 'Inter', '#1A1A1A', 20, 'Inter', '#f8f6f6', '#f8f6f6', '#ea2a33', '#FFFFFF', '2026-02-13 09:22:33', '2026-03-13 09:42:21');

-- --------------------------------------------------------

--
-- Table structure for table `template_plans`
--

CREATE TABLE `template_plans` (
  `template_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `template_plans`
--

INSERT INTO `template_plans` (`template_id`, `plan_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(3, 1),
(3, 2),
(3, 3),
(4, 1),
(4, 2),
(4, 3),
(5, 1),
(5, 2),
(5, 3),
(6, 1),
(6, 2),
(6, 3),
(7, 1),
(7, 2),
(7, 3),
(8, 1),
(8, 2),
(8, 3),
(9, 1),
(9, 2),
(9, 3),
(10, 1),
(10, 2),
(10, 3),
(11, 1),
(11, 2),
(11, 3),
(12, 1),
(12, 2),
(12, 3),
(13, 1),
(13, 2),
(13, 3),
(14, 1),
(14, 2),
(14, 3),
(15, 1),
(15, 2),
(15, 3),
(16, 2),
(16, 3),
(17, 1),
(17, 2),
(17, 3),
(18, 1),
(18, 2),
(18, 3);

-- --------------------------------------------------------

--
-- Table structure for table `template_restaurants`
--

CREATE TABLE `template_restaurants` (
  `template_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `template_restaurants`
--

INSERT INTO `template_restaurants` (`template_id`, `restaurant_id`) VALUES
(10, 26),
(17, 4),
(18, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_restaurant_id_index` (`restaurant_id`),
  ADD KEY `activity_logs_action_index` (`action`),
  ADD KEY `activity_logs_created_at_index` (`created_at`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_restaurant_slug` (`restaurant_id`,`slug`),
  ADD KEY `idx_restaurant_id` (`restaurant_id`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_display_order` (`display_order`),
  ADD KEY `idx_section_id` (`section_id`);

--
-- Indexes for table `category_secondary_sections`
--
ALTER TABLE `category_secondary_sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_category_secondary` (`category_id`,`section_id`),
  ADD KEY `idx_secondary_section` (`section_id`);

--
-- Indexes for table `customization_settings`
--
ALTER TABLE `customization_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `restaurant_template` (`restaurant_id`,`template_id`);

--
-- Indexes for table `email_delivery_suppressions`
--
ALTER TABLE `email_delivery_suppressions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_email_sha256` (`email_sha256`),
  ADD KEY `idx_created` (`created_at`);

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
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_login_attempts_ip_time` (`ip_address`,`attempted_at`),
  ADD KEY `idx_login_attempts_identifier_time` (`identifier`(191),`attempted_at`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_restaurant_id` (`restaurant_id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_restaurant_category_slug` (`restaurant_id`,`category_id`,`slug`),
  ADD KEY `idx_restaurant_id` (`restaurant_id`),
  ADD KEY `idx_category_id` (`category_id`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_display_order` (`display_order`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `status` (`status`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `menu_item_id` (`menu_item_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_token_hash` (`token_hash`),
  ADD KEY `idx_user_active` (`user_type`,`user_id`,`used_at`,`expires_at`),
  ADD KEY `idx_identifier_created` (`identifier`,`created_at`),
  ADD KEY `idx_ip_created` (`request_ip`,`created_at`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `subscription_id` (`subscription_id`),
  ADD KEY `status` (`status`),
  ADD KEY `transaction_reference` (`transaction_reference`);

--
-- Indexes for table `payment_fulfillment_receipts`
--
ALTER TABLE `payment_fulfillment_receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_fulfillment_receipts_gateway_reference_unique` (`gateway`,`reference`);

--
-- Indexes for table `payment_settings`
--
ALTER TABLE `payment_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gateway` (`gateway`);

--
-- Indexes for table `pending_bank_transfers`
--
ALTER TABLE `pending_bank_transfers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `pending_online_payments`
--
ALTER TABLE `pending_online_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference` (`reference`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `public_api_rate_events`
--
ALTER TABLE `public_api_rate_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_action_ip_time` (`action`,`ip_address`,`created_at`);

--
-- Indexes for table `qr_code_scans`
--
ALTER TABLE `qr_code_scans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `scanned_at` (`scanned_at`);

--
-- Indexes for table `qr_templates`
--
ALTER TABLE `qr_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `subscription_id` (`subscription_id`);

--
-- Indexes for table `restaurant_payment_settings`
--
ALTER TABLE `restaurant_payment_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `restaurant_id_gateway` (`restaurant_id`,`gateway`);

--
-- Indexes for table `restaurant_qr_codes`
--
ALTER TABLE `restaurant_qr_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `qr_template_id` (`qr_template_id`);

--
-- Indexes for table `restaurant_reservation_settings`
--
ALTER TABLE `restaurant_reservation_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `restaurant_id` (`restaurant_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_restaurant_section_slug` (`restaurant_id`,`slug`),
  ADD KEY `idx_sections_restaurant` (`restaurant_id`),
  ADD KEY `idx_sections_display_order` (`display_order`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `plan_id` (`plan_id`),
  ADD KEY `status` (`status`),
  ADD KEY `current_period_end` (`current_period_end`);

--
-- Indexes for table `subscription_change_requests`
--
ALTER TABLE `subscription_change_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_subscription_pending` (`subscription_id`,`status`),
  ADD KEY `idx_effective_pending` (`effective_at`,`status`),
  ADD KEY `idx_restaurant_pending` (`restaurant_id`,`status`),
  ADD KEY `subscription_change_requests_ibfk_3` (`from_plan_id`),
  ADD KEY `subscription_change_requests_ibfk_4` (`to_plan_id`);

--
-- Indexes for table `subscription_emails`
--
ALTER TABLE `subscription_emails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_id` (`subscription_id`),
  ADD KEY `idx_subscription_email_lookup` (`subscription_id`,`email_type`,`days_before`,`sent_at`);

--
-- Indexes for table `subscription_email_log`
--
ALTER TABLE `subscription_email_log`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sub_email_unique` (`subscription_id`,`email_type`,`days_before`);

--
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `table_inventory_daily`
--
ALTER TABLE `table_inventory_daily`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `restaurant_date` (`restaurant_id`,`inventory_date`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `inventory_date` (`inventory_date`);

--
-- Indexes for table `table_reservations`
--
ALTER TABLE `table_reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `reservation_date` (`reservation_date`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- Indexes for table `template_customizations`
--
ALTER TABLE `template_customizations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `template_id` (`template_id`);

--
-- Indexes for table `template_plans`
--
ALTER TABLE `template_plans`
  ADD PRIMARY KEY (`template_id`,`plan_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indexes for table `template_restaurants`
--
ALTER TABLE `template_restaurants`
  ADD PRIMARY KEY (`template_id`,`restaurant_id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=627;

--
-- AUTO_INCREMENT for table `category_secondary_sections`
--
ALTER TABLE `category_secondary_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `customization_settings`
--
ALTER TABLE `customization_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `email_delivery_suppressions`
--
ALTER TABLE `email_delivery_suppressions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4963;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `payment_fulfillment_receipts`
--
ALTER TABLE `payment_fulfillment_receipts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_settings`
--
ALTER TABLE `payment_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pending_bank_transfers`
--
ALTER TABLE `pending_bank_transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pending_online_payments`
--
ALTER TABLE `pending_online_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `public_api_rate_events`
--
ALTER TABLE `public_api_rate_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qr_code_scans`
--
ALTER TABLE `qr_code_scans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1010;

--
-- AUTO_INCREMENT for table `qr_templates`
--
ALTER TABLE `qr_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `restaurant_payment_settings`
--
ALTER TABLE `restaurant_payment_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `restaurant_qr_codes`
--
ALTER TABLE `restaurant_qr_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `restaurant_reservation_settings`
--
ALTER TABLE `restaurant_reservation_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `subscription_change_requests`
--
ALTER TABLE `subscription_change_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_emails`
--
ALTER TABLE `subscription_emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_email_log`
--
ALTER TABLE `subscription_email_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `table_inventory_daily`
--
ALTER TABLE `table_inventory_daily`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=375;

--
-- AUTO_INCREMENT for table `table_reservations`
--
ALTER TABLE `table_reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `template_customizations`
--
ALTER TABLE `template_customizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `categories_section_fk` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`);

--
-- Constraints for table `category_secondary_sections`
--
ALTER TABLE `category_secondary_sections`
  ADD CONSTRAINT `fk_css_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_css_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customization_settings`
--
ALTER TABLE `customization_settings`
  ADD CONSTRAINT `customization_settings_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `managers`
--
ALTER TABLE `managers`
  ADD CONSTRAINT `managers_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_items_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`);

--
-- Constraints for table `pending_bank_transfers`
--
ALTER TABLE `pending_bank_transfers`
  ADD CONSTRAINT `pending_bank_transfers_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pending_online_payments`
--
ALTER TABLE `pending_online_payments`
  ADD CONSTRAINT `pending_online_payments_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `qr_code_scans`
--
ALTER TABLE `qr_code_scans`
  ADD CONSTRAINT `qr_code_scans_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD CONSTRAINT `restaurants_subscription_fk` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `restaurant_payment_settings`
--
ALTER TABLE `restaurant_payment_settings`
  ADD CONSTRAINT `restaurant_payment_settings_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `restaurant_qr_codes`
--
ALTER TABLE `restaurant_qr_codes`
  ADD CONSTRAINT `restaurant_qr_codes_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `restaurant_qr_codes_ibfk_2` FOREIGN KEY (`qr_template_id`) REFERENCES `qr_templates` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `restaurant_reservation_settings`
--
ALTER TABLE `restaurant_reservation_settings`
  ADD CONSTRAINT `restaurant_reservation_settings_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`id`);

--
-- Constraints for table `subscription_change_requests`
--
ALTER TABLE `subscription_change_requests`
  ADD CONSTRAINT `subscription_change_requests_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscription_change_requests_ibfk_2` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscription_change_requests_ibfk_3` FOREIGN KEY (`from_plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscription_change_requests_ibfk_4` FOREIGN KEY (`to_plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscription_emails`
--
ALTER TABLE `subscription_emails`
  ADD CONSTRAINT `subscription_emails_ibfk_1` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `table_inventory_daily`
--
ALTER TABLE `table_inventory_daily`
  ADD CONSTRAINT `table_inventory_daily_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `table_reservations`
--
ALTER TABLE `table_reservations`
  ADD CONSTRAINT `table_reservations_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `template_customizations`
--
ALTER TABLE `template_customizations`
  ADD CONSTRAINT `template_customizations_ibfk_1` FOREIGN KEY (`template_id`) REFERENCES `templates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `template_plans`
--
ALTER TABLE `template_plans`
  ADD CONSTRAINT `template_plans_ibfk_1` FOREIGN KEY (`template_id`) REFERENCES `templates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `template_plans_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `template_restaurants`
--
ALTER TABLE `template_restaurants`
  ADD CONSTRAINT `template_restaurants_ibfk_1` FOREIGN KEY (`template_id`) REFERENCES `templates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `template_restaurants_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
