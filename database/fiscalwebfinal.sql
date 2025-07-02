-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2025 at 01:22 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fiscalwebfinal`
--

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
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `body` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `ticket_id`, `user_id`, `body`, `created_at`, `updated_at`) VALUES
(1, 11, 3, 'Issue has been resolved, they must stop doing credit notes that exceed the original invoice value', '2025-05-19 07:36:14', '2025-05-19 07:36:14'),
(2, 24, 2, 'why is this view now weird', '2025-05-19 08:41:01', '2025-05-19 08:41:01'),
(3, 27, 4, 'They have already registered, we are still waiting for the keys from Zimra', '2025-05-19 20:42:13', '2025-05-19 20:42:13'),
(4, 28, 4, 'this is the first time.', '2025-05-19 21:10:33', '2025-05-19 21:10:33'),
(5, 29, 4, 'job finished', '2025-05-19 21:14:13', '2025-05-19 21:14:13'),
(6, 26, 2, 'Job Completed', '2025-05-20 11:17:42', '2025-05-20 11:17:42');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(8, '0001_01_01_000002_create_jobs_table', 2),
(9, '2025_05_04_195206_create_tickets_table', 2),
(10, '2025_05_17_194919_add_priority_to_tickets_table', 3),
(11, '2025_05_17_225137_add_role_to_users_table', 4),
(12, '2025_05_19_090406_create_comments_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('AN7wHJJx3UMmQ2KdbDiPBkZNK0x7OpTy82nUkxWJ', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoid3R1OEVSSW5BN2ltcFRha2VMNW0za1B1bFIxVXBOWmt5QXdZMFFuUyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3RpY2tldHMvb3BlbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI5OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvQ29udGVudCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1749137451),
('PPnhcJ1tFondfBq4I1SExncXxF6yc0ZVZYk3MPCh', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib2Y1Y1pVU05scTl4bWhGWndOYjZVc1YwN2R2U0ZGVDBEVzA4aGRjeSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jb250YWN0LXVzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1749112476),
('yuoq6fWVQGn5sdNrnyfcuvRyuOxJUcNGed4DOYNj', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSE5nOEx6STZ5TFhmSnNKVnQwWTlDaUJpTHpUdGJwYmsyT2VYTTJVWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1749221626);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `contact_details` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `service` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `priority` varchar(255) DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `company_name`, `contact_details`, `email`, `subject`, `message`, `service`, `status`, `priority`, `assigned_to`, `attachment`, `created_at`, `updated_at`) VALUES
(1, 'Two Glan Eagles', '0786500769', 'sireforextrader@gmail.com', 'Technical Support', 'I have invoices That are reporting not found when validated. Kindly assist with this issue.', 'Technical Support', 'pending', NULL, NULL, NULL, '2025-05-05 13:25:02', '2025-05-05 13:25:02'),
(2, 'Fiscal Support Services', '0786500769', 'Supporthre@fiscalsupportservices.com', 'Technical Support', 'Okay, so with the software we are getting the bad certificate signature almost all the time, this has become an issue and we are not fond of. Kindly assist with this as soon as possible', 'Technical Support', 'pending', NULL, NULL, NULL, '2025-05-05 13:46:34', '2025-05-05 13:46:34'),
(3, 'Simbarashe Kowo', '0786500769', 'kowosimba08@gmail.com', 'Billing Inquiry', 'cewgfhjk,l.;/\'/;.,hgmfndbvsacsdvbfdgnfhmg,jkh', 'Billing Inquiry', 'pending', NULL, NULL, 'attachments/1746460421_Fiscal Device Gateway API v7.2 - clients.pdf', '2025-05-05 13:53:41', '2025-05-05 13:53:41'),
(4, 'Simbarashe Kowo', '0786500769', 'kowosimba08@gmail.com', 'Billing Inquiry', 'xdwce2wcdwqecxecsarwdcq gstku  tuejmiukj', 'Billing Inquiry', 'pending', NULL, NULL, 'attachments/1746460644_1ARnj0YLKrZFvRsXLi29hz-xbbYR2jHcuf9W6rRezX24YC7CNwJ6BOWZXVsTs2mZ4_Wr9WN8_dl0H0882AyGx3WcmTjA6h6gIiXI8lQDEMgE.pdf', '2025-05-05 13:57:24', '2025-05-05 13:57:24'),
(5, 'Simbarashe Kowo', '0786500769', 'kowosimba08@gmail.com', 'Billing Inquiry', 'defvdgfhgn ektydgbeiu ceYDFV qekOdic ;sdjn', 'Billing Inquiry', 'pending', NULL, NULL, 'attachments/1746461091_XBxXufDMvGhqU-o6BBFQU97klOQK0mGQBEG0w4uxrg3xVjrNs_ekKrC3Jkoz52fRSiU--VocaPj08qEhPY7xUVBT4QomljKR4uH7uLjZA0s9fwLOQ08xibzMv9QuF-JpMze40DL2HbJ7liZesQfXIbSjCZMADQ0Y4sPzTgroyzzBTkcTs4iOB13YSFrRPs92KNVbSbAlhQaXguhRq2S4o2U.pdf', '2025-05-05 14:04:51', '2025-05-05 14:04:51'),
(6, 'Simbarashe', '0786500769', 'kowosimbae@gmail.com', 'Technical Support', 'ewnsm,kl.g;/\"OJO{h/p.ouytreaVBTNSYM,UIY.U/OIp{o/.O,UMYNTBSVACSBNYM,./H\r\nK;\';J,MHGDBAS', 'Technical Support', 'pending', NULL, NULL, NULL, '2025-05-05 14:16:33', '2025-05-05 14:16:33'),
(7, 'Simbarashe Kowo', '0786500769', 'kowosimba08@gmail.com', 'Billing Inquiry', 'xdwce2wcdwqecxecsarwdcq gstku  tuejmiukj', 'Billing Inquiry', 'pending', NULL, NULL, NULL, '2025-05-05 14:17:42', '2025-05-05 14:17:42'),
(8, 'Simbarashe Kowo', '0786500769', 'kowosimba08@gmail.com', 'Billing Inquiry', 'xdwce2wcdwqecxecsarwdcq gstku  tuejmiukj', 'Billing Inquiry', 'pending', NULL, NULL, NULL, '2025-05-05 14:18:16', '2025-05-05 14:18:16'),
(9, 'Simbarashe Kowo', '0786500769', 'kowosimba08@gmail.com', 'Billing Inquiry', 'ytfhklohpiolkjhgrefwahjgkughliiytr', 'Billing Inquiry', 'pending', NULL, NULL, 'attachments/1746462499_133880476180490491.jpg', '2025-05-05 14:28:19', '2025-05-05 14:28:19'),
(10, 'Chirdmac Investments', '0786500769', 'Accounts@chirdmarc.com', 'Technical Support', 'I need support regarding my invoices saying not found when validated on the Zimra portal, Assist as soon as possible', 'Technical Support', 'pending', NULL, NULL, NULL, '2025-05-11 19:57:19', '2025-05-11 19:57:19'),
(11, 'Chirdmac Investments', '0786500769', 'Accounts@chirdmarc.com', 'Billing Inquiry', 'iiiii makunyanya', 'Billing Inquiry', 'pending', NULL, 1, NULL, '2025-05-12 17:43:11', '2025-05-19 19:50:46'),
(12, 'Simbarashe Kowo', '0786500769', 'johndoe@helpdesk.com', 'Software Update', 'haaa finish hmmmmm', 'Software Update', 'resolved', 'medium', 2, NULL, '2025-05-17 20:32:38', '2025-05-19 08:44:00'),
(13, 'tooglan eagles', '0786500769', 'sireforextrader@gmail.com', 'Invoices not found', 'hmmmmmm', 'Billing Inquiry', 'pending', 'medium', 1, NULL, '2025-05-18 17:55:37', '2025-05-18 17:55:37'),
(14, 'tooglan eagles', '0786500769', 'sireforextrader@gmail.com', 'Invoices not found', 'hmmmmmm', 'Billing Inquiry', 'pending', 'medium', 1, NULL, '2025-05-18 18:01:23', '2025-05-18 18:01:23'),
(15, 'tooglan eagles', '0786500769', 'sireforextrader@gmail.com', 'Invoices not found', 'invoices not found', 'Technical Support', 'pending', 'high', 1, 'attachments/Wn0Hs9eZnABWwZDMIZZv1iOzL4oPgXjDTM7gP6fx.pdf', '2025-05-18 20:27:53', '2025-05-18 20:27:53'),
(16, 'tooglan eagles', '0786500769', 'sireforextrader@gmail.com', 'Invoices not found', 'invoices not found', 'Billing Inquiry', 'pending', 'medium', 1, 'attachments/3R8O9PZF6fMTwj1PqQMX0QzAPcpaljpRss332L3z.pdf', '2025-05-18 20:28:19', '2025-05-18 20:28:19'),
(17, 'tooglan eagles', '0786500769', 'sireforextrader@gmail.com', 'Invoices not found', 'invoices not found', 'Billing Inquiry', 'pending', 'medium', 1, 'attachments/z0YR8rNiZ1W7aFvUyjr4se8vXgzswFJlpJQiuLtw.pdf', '2025-05-18 20:28:26', '2025-05-18 20:28:26'),
(18, 'tooglan eagles', '0786500769', 'sireforextrader@gmail.com', 'Invoices not found', 'invoices not found', 'Billing Inquiry', 'pending', 'medium', 1, 'attachments/PQPM7dKYcXBtl5gddC88aF495RhA9bjX1oL4ZxaF.pdf', '2025-05-18 20:28:31', '2025-05-18 20:28:31'),
(19, 'tooglan eagles', '0786500769', 'sireforextrader@gmail.com', 'Invoices not found', 'invoices not found', 'Billing Inquiry', 'pending', 'medium', 1, 'attachments/s0B6BOEUuIP0D788tDrguQRVUsNVMYce3Gi6zWbF.pdf', '2025-05-18 20:28:35', '2025-05-18 20:28:35'),
(20, 'tooglan eagles', '0786500769', 'sireforextrader@gmail.com', 'Invoices not found', 'invoices not found', 'Billing Inquiry', 'pending', 'medium', 1, 'attachments/yTwtDTsFUa0NVIjtOpi6T2SZzMcxzRcjY7XMqruJ.pdf', '2025-05-18 20:28:38', '2025-05-18 20:28:38'),
(24, 'tooglan eagles', '0786500769', 'sireforextrader@gmail.com', 'Invoices not found', 'doest matter', 'Technical Support', 'in_progress', 'high', 2, NULL, '2025-05-18 21:02:03', '2025-05-19 19:21:55'),
(25, 'Fiscal support', '0786500769', 'sales@fiscalsupportservices', 'Technical Support', 'We have invoices that when validated on the zimra portal they reporting not found.', 'Technical Support', 'resolved', 'low', 2, 'attachments/1747684985_Final one.pdf', '2025-05-19 18:03:05', '2025-05-20 11:19:09'),
(26, 'Fiscal support', '0786500769', 'sales@fiscalsupportservices', 'Technical Support', 'invoices not found', 'Technical Support', 'resolved', 'low', 2, 'attachments/1747693675_Final one.pdf', '2025-05-19 20:27:55', '2025-05-20 11:19:23'),
(27, 'Bethchad', '0786500769', 'bethchad@gmail.com', 'Fiscal Device Setup', 'We need to register a new fiscal device for a virtual solution, please send us the onboarding form.', 'Fiscal Device Setup', 'pending', 'low', 1, 'attachments/1747694125_2025051411235514_05_2025 11_23_55 AM.pdf', '2025-05-19 20:35:25', '2025-05-19 21:17:02'),
(28, 'Fiscal support services', '0786500769', 'sales@fiscalsupportservices', 'Technical Support', 'invoices not found', 'Technical Support', 'pending', 'low', 4, 'attachments/1747696038_Final one.pdf', '2025-05-19 21:07:18', '2025-05-19 21:16:52'),
(29, 'fiscal harmony', '0786500769', 'supporthre2@fiscalsupportservices.com', 'Migration to virtual', 'invoices not found', 'Technical Support', 'resolved', 'high', 4, NULL, '2025-05-19 21:13:13', '2025-05-19 21:14:34'),
(30, 'Fiscal support services', '0786500769', 'sales@fiscalsupportservices', 'Technical Support', 'invoices not found', 'Technical Support', 'pending', 'low', NULL, NULL, '2025-05-21 04:38:10', '2025-05-21 04:38:10');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_comments`
--

CREATE TABLE `ticket_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `ticket_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Simbarashe Kowo', 'supporthre2@fiscalsupportservices.com', 'technician', NULL, '$2y$12$1s/4ap/Zib.AizxufetBneYeo1EjSlz7fVi9vWierfxyKI7LBgSYy', NULL, '2025-05-08 18:17:25', '2025-05-18 15:55:35'),
(2, 'Sim', 'kowosimba08@gmail.com', 'technician', NULL, '$2y$12$WiQ/PojTDBIgg656Kbn.eetBkfK3MJyNFxVID47/aHkm7BHiXXLna', NULL, '2025-05-14 17:07:52', '2025-05-18 21:00:39'),
(3, 'Blessing Mutanda', 'sales2@fiscalsupportservices.com', 'manager', NULL, '$2y$12$dSD1Fq8ekTr0hBflLBW.e.M1N9t.1GESlPJQPTD9aHzVd44lOqW6m', NULL, '2025-05-18 15:33:06', '2025-05-18 15:54:54'),
(4, 'Malvine Chikochi', 'supporthre@fiscalsupportservices.com', 'technician', NULL, '$2y$12$JLwnrwH6Ex6gKuDFbu489uC4bhTSQBpW0gsNVmWHfSHY0Z5AN7rI2', NULL, '2025-05-19 20:29:15', '2025-05-19 20:40:54');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_ticket_id_foreign` (`ticket_id`),
  ADD KEY `comments_user_id_foreign` (`user_id`);

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
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tickets_assigned_to_index` (`assigned_to`);

--
-- Indexes for table `ticket_comments`
--
ALTER TABLE `ticket_comments`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `ticket_comments`
--
ALTER TABLE `ticket_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
