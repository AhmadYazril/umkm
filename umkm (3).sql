-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 24, 2026 at 01:41 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `umkm`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `media_type` enum('image','video') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image',
  `media_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `layout_mode` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'poster',
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `button_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Pesan Sekarang',
  `button_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '#menu',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` text,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Dessert', 'dessert', NULL, 1, 1, '2026-10-09 08:01:58', '2026-10-09 08:01:58'),
(2, 'Makanan', 'makanan', NULL, 2, 1, '2026-10-09 08:01:58', '2026-10-09 08:01:58'),
(3, 'Snack', 'snack', NULL, 3, 1, '2026-10-09 08:01:58', '2026-10-09 08:01:58'),
(4, 'Kopi', 'kopi', NULL, 4, 1, '2026-10-09 08:01:58', '2026-10-09 08:01:58'),
(5, 'Non-Kopi', 'non-kopi', NULL, 5, 1, '2026-10-09 08:01:58', '2026-10-09 08:01:58');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `date`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Grand Opening Nucomu Cafe', 'Rayakan bersama kami pembukaan resmi Nucomu Cafe! Dapatkan promo spesial dan berbagai diskon menarik di hari pertama kami hadir untuk kalian.', '2026-10-16 15:15:05', NULL, 1, '2026-10-09 08:02:01', '2026-10-09 08:15:05'),
(2, 'Nucomu Dessert Weekend', 'Setiap akhir pekan, nikmati promo spesial dessert pilihan dengan harga lebih terjangkau. Ajak teman-teman dan rasakan kelezatan bersama!', '2026-10-23 15:15:05', NULL, 1, '2026-10-09 08:02:01', '2026-10-09 08:15:05');

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` bigint UNSIGNED NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tab_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `badge` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'zap',
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `features` json DEFAULT NULL,
  `photos` json DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `slug`, `tab_name`, `title`, `badge`, `subtitle`, `icon`, `label`, `image`, `description`, `features`, `photos`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 'musholla', 'MUSHOLLA', 'Musala Bersih & Terpisah', '03 / KENYAMANAN IBADAH', 'Kenyamanan Ibadah', 'moon', 'Mushola', 'images/nucomu/nucomu_story_1.png', 'Mushola bersih dan tenang lengkap dengan sarana wudhu terpisah, sajadah, mukena, dan pendingin ruangan AC.', '[\"Sarana Wudhu Bersih\", \"Mukena & Sajadah\", \"Sejuk & Tenang\"]', '[{\"alt\": \"Mushola Nucomu Cafe — Bersih & Tenang\", \"image\": \"images/nucomu/nucomu_story_1.png\", \"caption\": \"Area Ibadah Bersih & Tenang\"}]', 3, 1, '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(8, 'indoor', 'INDOOR', 'Indoor Lounge Work Space', '01 / WORKSPACE & LOUNGE', 'Workspace & Lounge', 'zap', 'Indoor Lounge Work Space', NULL, 'Area dalam ruangan ber-AC yang tenang dengan meja ergonomis, stopkontak melimpah di setiap sudut, serta koneksi internet cepat. Ideal untuk WFC (Work From Cafe) maupun rapat santai.', '[\"Suhu ruangan sejuk & sirkulasi udara bersih\", \"Pencahayaan hangat bebas silau untuk kenyamanan mata\", \"Stopkontak di setiap meja & Wi-Fi berkecepatan tinggi\"]', '[{\"alt\": \"Indoor Lounge Nucomu Cafe — Meja Ergonomis & Monstera\", \"image\": \"images/nucomu/nucomu_story_2.png\", \"caption\": \"Meja Ergonomis & Suasana Asri\"}, {\"alt\": \"Espresso Bar Nucomu Cafe — Counter Seduh & Mesin Kopi\", \"image\": \"images/nucomu/nucomu_story_1.png\", \"caption\": \"Espresso Bar & Counter Seduh\"}, {\"alt\": \"Sudut Apparel Nucomu Cafe — Merchandise & Cermin Estetis\", \"image\": \"images/nucomu/nucomu_story_4.png\", \"caption\": \"Sudut Apparel & Cermin Estetis\"}]', 1, 1, '2026-11-04 06:09:31', '2026-11-04 06:09:31'),
(9, 'outdoor', 'OUTDOOR', 'Outdoor Garden Terrace', '02 / OUTDOOR TERRACE', 'Garden Terrace', 'zap', 'Outdoor Garden Terrace', NULL, 'Taman terbuka bersuasana asri dengan naungan pepohonan rindang dan pencahayaan malam yang temaram romantis. Tempat favorit untuk menikmati senja, angin sepoi-sepoi, dan obrolan santai tanpa sekat kaku.', '[\"Smoking area yang lega dan asri\", \"Suasana hijau teduh dengan bangku taman nyaman\", \"Atmosfer santai untuk bercengkerama sore hingga malam\"]', '[{\"alt\": \"Outdoor Garden Terrace Nucomu Cafe — Kursi Kayu Alami & Lampu Hangat\", \"image\": \"images/nucomu/nucomu_story_3.png\", \"caption\": \"Bangku Kayu & Lampu Temaram\"}, {\"alt\": \"Outdoor Garden Nucomu Cafe — Area Terbuka Hijau & Sejuk\", \"image\": \"images/nucomu/nucomu_outdoor_2.png\", \"caption\": \"Area Terbuka Hijau & Sejuk\"}, {\"alt\": \"Outdoor Nucomu Cafe — Sudut Santai Sore Hari\", \"image\": \"images/nucomu/nucomu_outdoor_3.png\", \"caption\": \"Sudut Santai Sore Hari\"}]', 2, 1, '2026-11-04 06:09:31', '2026-11-04 06:09:31');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery_items`
--

CREATE TABLE `gallery_items` (
  `id` bigint UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_badge` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ambience',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery_items`
--

INSERT INTO `gallery_items` (`id`, `image`, `caption`, `author`, `time_label`, `category_badge`, `avatar_url`, `description`, `category`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'images/placeholder/gallery-1.jpg', 'Interior Nucomu Cafe — suasana monokrom elegan', NULL, NULL, NULL, NULL, NULL, 'ambience', 1, 1, '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(2, 'images/placeholder/gallery-2.jpg', 'Area duduk nyaman dengan banyak colokan untuk WFC', NULL, NULL, NULL, NULL, NULL, 'ambience', 2, 1, '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(3, 'images/placeholder/gallery-3.jpg', 'Banoffee — dessert ikonik Nucomu', NULL, NULL, NULL, NULL, NULL, 'menu', 3, 1, '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(4, 'images/placeholder/gallery-4.jpg', 'Cheesecake creamy favorit pelanggan', NULL, NULL, NULL, NULL, NULL, 'menu', 4, 1, '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(5, 'images/placeholder/gallery-5.jpg', 'Nucomu Coffee — signature blend spesial', NULL, NULL, NULL, NULL, NULL, 'menu', 5, 1, '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(6, 'images/placeholder/gallery-6.jpg', 'Donut Choco Pistachio yang menggoda', NULL, NULL, NULL, NULL, NULL, 'menu', 6, 1, '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(7, 'images/placeholder/gallery-7.jpg', 'Sudut foto estetik di Nucomu Cafe', NULL, NULL, NULL, NULL, NULL, 'ambience', 7, 1, '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(8, 'images/placeholder/gallery-8.jpg', 'Nasi Cakalang Suwir — makanan berat pilihan', NULL, NULL, NULL, NULL, NULL, 'menu', 8, 1, '2026-10-09 08:02:01', '2026-10-09 08:02:01');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
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
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` int UNSIGNED NOT NULL,
  `stock` int NOT NULL DEFAULT '25',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_best_seller` tinyint(1) NOT NULL DEFAULT '0',
  `is_sample` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `stock`, `image`, `is_available`, `is_featured`, `is_best_seller`, `is_sample`, `sort_order`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Banoffee', 'banoffee', 'Dessert ikonik Nucomu — perpaduan karamel, pisang, dan krim lembut di atas base biskuit renyah. Citarasa otentik yang jadi asal-mula lahirnya Nucomu Cafe.', 28000, 25, 'https://images.unsplash.com/photo-1587314168485-3236d6710814?auto=format&fit=crop&w=600&q=80', 1, 1, 1, 0, 1, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(2, 1, 'Cheesecake', 'cheesecake', 'Cheesecake creamy dengan tekstur lembut berpadu rasa keju yang kaya. Satu gigitan langsung bikin nagih.', 27000, 25, 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?auto=format&fit=crop&w=600&q=80', 1, 1, 1, 0, 2, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(3, 1, 'Tiramisu', 'tiramisu', 'Tiramisu klasik dengan lapisan mascarpone tebal, taburan bubuk cokelat, dan aroma kopi yang menggoda.', 27000, 25, 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?auto=format&fit=crop&w=600&q=80', 1, 1, 0, 0, 3, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(4, 1, 'Donut Choco Pistachio', 'donut-choco-pistachio', 'Donut lembut berlapis cokelat gelap dengan taburan pistachio renyah. Kombinasi sempurna antara manis, gurih, dan crunch.', 22000, 25, 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&q=80&w=800', 1, 1, 0, 0, 4, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(5, 1, 'Brownies Fudge', 'brownies-fudge', 'Brownies padat dengan cokelat premium, lembut di dalam dan sedikit renyah di luar.', 20000, 25, 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?auto=format&fit=crop&w=600&q=80', 1, 0, 0, 1, 5, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(6, 1, 'Pudding Matcha', 'pudding-matcha', 'Pudding lembut berbahan matcha premium Jepang, disajikan dingin dengan saus karamel tipis.', 18000, 25, 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=600&q=80', 1, 0, 0, 1, 6, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(7, 1, 'Crepe Cake Berry', 'crepe-cake-berry', 'Lapisan crepe tipis dengan krim vanila lembut dan potongan buah berry segar.', 32000, 25, 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?auto=format&fit=crop&w=600&q=80', 1, 0, 0, 1, 7, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(8, 2, 'Nasi Cakalang Suwir', 'nasi-cakalang-suwir', 'Nasi putih pulen disajikan bersama cakalang suwir pedas gurih khas Manado, dilengkapi lauk pelengkap.', 35000, 25, 'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=600&q=80', 1, 1, 1, 0, 1, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(9, 2, 'Rice Bowl Ayam Cabe Garam', 'rice-bowl-ayam-cabe-garam', 'Rice bowl dengan potongan ayam crispy bumbu cabe garam yang gurih dan sedikit pedas menggoda.', 32000, 25, 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80', 1, 1, 0, 0, 2, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(10, 2, 'Nasi Goreng Kampung', 'nasi-goreng-kampung', 'Nasi goreng dengan bumbu tradisional, dilengkapi telur dan kerupuk renyah.', 28000, 25, 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?auto=format&fit=crop&w=600&q=80', 1, 0, 0, 1, 3, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(11, 2, 'Sandwich Club', 'sandwich-club', 'Sandwich dengan isian ayam, selada segar, tomat, dan saus spesial diapit roti gandum panggang.', 30000, 25, 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=600&q=80', 1, 0, 0, 1, 4, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(12, 2, 'Pasta Aglio Olio', 'pasta-aglio-olio', 'Pasta spaghetti dengan minyak zaitun, bawang putih, cabai, dan taburan keju parmesan.', 33000, 25, 'https://images.unsplash.com/photo-1551183053-bf91a1d81141?auto=format&fit=crop&w=600&q=80', 1, 0, 0, 1, 5, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(13, 3, 'Kentang Goreng Crispy', 'kentang-goreng-crispy', 'Kentang goreng renyah dengan bumbu balado atau original, disajikan dengan saus keju leleh.', 18000, 25, 'https://images.unsplash.com/photo-1576107232684-1279f3908594?auto=format&fit=crop&w=600&q=80', 1, 0, 0, 1, 1, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(14, 3, 'Roti Bakar Cokelat Keju', 'roti-bakar-cokelat-keju', 'Roti tawar panggang dengan olesan cokelat dan lelehan keju melimpah di atasnya.', 15000, 25, 'https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=600&q=80', 1, 0, 0, 1, 2, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(15, 3, 'Pisang Goreng Kremes', 'pisang-goreng-kremes', 'Pisang kepok yang digoreng dengan kremes crispy dan taburan bubuk kayu manis, disajikan hangat.', 13000, 25, 'https://images.unsplash.com/photo-1621996346565-e3d5d6281298?auto=format&fit=crop&w=600&q=80', 1, 0, 0, 1, 3, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(16, 3, 'Chicken Nugget', 'chicken-nugget', 'Nugget ayam crispy dengan saus cabai manis dan saus keju, cocok sebagai teman ngopi.', 20000, 25, 'https://images.unsplash.com/photo-1562967914-608f82629710?auto=format&fit=crop&w=600&q=80', 1, 0, 0, 1, 4, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(17, 4, 'Nucomu Coffee', 'nucomu-coffee', 'Kopi signature Nucomu dengan blend spesial yang memberikan cita rasa seimbang antara asam dan pahit, cocok dinikmati kapan saja.', 25000, 25, 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=600&q=80', 1, 0, 1, 0, 1, NULL, '2026-10-09 08:01:58', '2026-11-17 00:04:10'),
(18, 4, 'Butterscloud', 'butterscloud', 'Minuman kopi dengan foam butter lembut di atas, perpaduan antara espresso kuat dan cloud foam manis yang dreamy.', 28000, 25, 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=600&q=80', 1, 1, 0, 0, 2, NULL, '2026-10-09 08:01:58', '2026-11-03 08:46:22'),
(19, 4, 'Americano', 'americano', 'Espresso double shot dengan air panas, menghasilkan kopi hitam yang kuat dan refreshing.', 20000, 25, 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&w=600&q=80', 1, 0, 0, 1, 3, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(20, 4, 'Cappuccino', 'cappuccino', 'Espresso dengan susu panas dan foam susu tebal, klasik dan selalu memuaskan.', 22000, 25, 'https://images.unsplash.com/photo-1534778101976-62847782c213?auto=format&fit=crop&w=600&q=80', 1, 0, 0, 1, 4, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(21, 4, 'Caramel Latte', 'caramel-latte', 'Latte espresso dengan sirup karamel manis dan susu lembut, ditutup drizzle karamel di atasnya.', 26000, 25, 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?auto=format&fit=crop&w=600&q=80', 1, 0, 0, 1, 5, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(22, 4, 'Cold Brew', 'cold-brew', 'Kopi cold brew 12 jam dengan rasa smooth dan rendah asam, disajikan dingin tanpa es.', 27000, 25, 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?auto=format&fit=crop&w=600&q=80', 1, 0, 0, 1, 6, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(23, 5, 'Lychee Tea', 'lychee-tea', 'Teh hitam premium dengan infus buah lychee segar, segar dan manis, cocok untuk bersantai.', 20000, 25, 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=600&q=80', 1, 0, 0, 0, 1, NULL, '2026-10-09 08:01:58', '2026-11-03 08:47:42'),
(24, 5, 'Matcha', 'matcha', 'Matcha premium grade ceremonial dengan susu lembut, rasa earthy yang khas dan menyegarkan.', 25000, 24, 'https://images.unsplash.com/photo-1515823064-d6e0c04616a7?auto=format&fit=crop&w=600&q=80', 1, 1, 0, 0, 2, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(25, 5, 'Chocolate Milk', 'chocolate-milk', 'Susu segar dengan cokelat premium belgi, creamy dan kaya rasa untuk semua usia.', 22000, 25, 'https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?auto=format&fit=crop&w=600&q=80', 1, 0, 0, 1, 3, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(26, 5, 'Strawberry Smoothie', 'strawberry-smoothie', 'Smoothie buah stroberi segar yang diblender dengan yogurt, menyegarkan dan kaya vitamin.', 24000, 25, 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?auto=format&fit=crop&w=600&q=80', 1, 0, 0, 1, 4, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(27, 5, 'Taro Milk', 'taro-milk', 'Minuman berbahan talas ungu dengan susu lembut, warna cantik dan rasa yang unik.', 23000, 25, 'https://images.unsplash.com/photo-1558857563-b371033873b8?auto=format&fit=crop&w=600&q=80', 1, 0, 0, 1, 5, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19'),
(28, 5, 'Brown Sugar Milk', 'brown-sugar-milk', 'Susu segar dengan gula aren asli yang memberikan rasa karamel alami yang khas.', 22000, 25, 'https://images.unsplash.com/photo-1541658016709-82535e94bc69?auto=format&fit=crop&w=600&q=80', 1, 0, 0, 1, 6, NULL, '2026-10-09 08:01:58', '2026-11-01 20:10:19');

-- --------------------------------------------------------

--
-- Table structure for table `menu_options`
--

CREATE TABLE `menu_options` (
  `id` bigint UNSIGNED NOT NULL,
  `menu_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int UNSIGNED NOT NULL DEFAULT '0',
  `type` enum('variant','addon') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'variant',
  `is_sample` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_options`
--

INSERT INTO `menu_options` (`id`, `menu_id`, `name`, `price`, `type`, `is_sample`, `created_at`, `updated_at`) VALUES
(1, 17, 'Hot', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(2, 17, 'Iced', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(3, 18, 'Hot', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(4, 18, 'Iced', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(5, 19, 'Hot', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(6, 19, 'Iced', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(7, 20, 'Hot', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(8, 20, 'Iced', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(9, 21, 'Hot', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(10, 21, 'Iced', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(11, 22, 'Hot', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(12, 22, 'Iced', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(13, 23, 'Hot', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(14, 23, 'Iced', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(15, 24, 'Hot', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(16, 24, 'Iced', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(17, 25, 'Hot', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(18, 25, 'Iced', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(19, 26, 'Hot', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(20, 26, 'Iced', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(21, 27, 'Hot', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(22, 27, 'Iced', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(23, 28, 'Hot', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(24, 28, 'Iced', 0, 'variant', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(25, 17, '0% (Tanpa Gula)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(26, 17, '25% (Sedikit Manis)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(27, 17, '50% (Sedang)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(28, 17, '75% (Manis)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(29, 17, '100% (Penuh)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(30, 18, '0% (Tanpa Gula)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(31, 18, '25% (Sedikit Manis)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(32, 18, '50% (Sedang)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(33, 18, '75% (Manis)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(34, 18, '100% (Penuh)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(35, 19, '0% (Tanpa Gula)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(36, 19, '25% (Sedikit Manis)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(37, 19, '50% (Sedang)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(38, 19, '75% (Manis)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(39, 19, '100% (Penuh)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(40, 20, '0% (Tanpa Gula)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(41, 20, '25% (Sedikit Manis)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(42, 20, '50% (Sedang)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(43, 20, '75% (Manis)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(44, 20, '100% (Penuh)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(45, 21, '0% (Tanpa Gula)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(46, 21, '25% (Sedikit Manis)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(47, 21, '50% (Sedang)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(48, 21, '75% (Manis)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(49, 21, '100% (Penuh)', 0, 'addon', 0, '2026-10-09 08:01:59', '2026-10-09 08:01:59'),
(50, 22, '0% (Tanpa Gula)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(51, 22, '25% (Sedikit Manis)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(52, 22, '50% (Sedang)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(53, 22, '75% (Manis)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(54, 22, '100% (Penuh)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(55, 23, '0% (Tanpa Gula)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(56, 23, '25% (Sedikit Manis)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(57, 23, '50% (Sedang)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(58, 23, '75% (Manis)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(59, 23, '100% (Penuh)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(60, 24, '0% (Tanpa Gula)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(61, 24, '25% (Sedikit Manis)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(62, 24, '50% (Sedang)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(63, 24, '75% (Manis)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(64, 24, '100% (Penuh)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(65, 25, '0% (Tanpa Gula)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(66, 25, '25% (Sedikit Manis)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(67, 25, '50% (Sedang)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(68, 25, '75% (Manis)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(69, 25, '100% (Penuh)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(70, 26, '0% (Tanpa Gula)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(71, 26, '25% (Sedikit Manis)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(72, 26, '50% (Sedang)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(73, 26, '75% (Manis)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(74, 26, '100% (Penuh)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(75, 27, '0% (Tanpa Gula)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(76, 27, '25% (Sedikit Manis)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(77, 27, '50% (Sedang)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(78, 27, '75% (Manis)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(79, 27, '100% (Penuh)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(80, 28, '0% (Tanpa Gula)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(81, 28, '25% (Sedikit Manis)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(82, 28, '50% (Sedang)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(83, 28, '75% (Manis)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(84, 28, '100% (Penuh)', 0, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(85, 17, 'Extra Shot', 5000, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(86, 18, 'Extra Shot', 5000, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(87, 19, 'Extra Shot', 5000, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(88, 20, 'Extra Shot', 5000, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(89, 21, 'Extra Shot', 5000, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(90, 22, 'Extra Shot', 5000, 'addon', 0, '2026-10-09 08:02:00', '2026-10-09 08:02:00');

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
(4, '2026_10_09_000001_create_categories_table', 1),
(5, '2026_10_09_000002_create_menus_table', 1),
(6, '2026_10_09_000003_create_menu_options_table', 1),
(7, '2026_10_09_000004_create_tables_table', 1),
(8, '2026_10_09_000005_create_orders_table', 1),
(9, '2026_10_09_000006_create_order_items_table', 1),
(10, '2026_10_09_000007_create_reservations_table', 1),
(11, '2026_10_09_000008_create_settings_table', 1),
(12, '2026_10_09_000009_create_operating_hours_table', 1),
(13, '2026_10_09_000010_create_facilities_table', 1),
(14, '2026_10_09_000011_create_gallery_items_table', 1),
(15, '2026_10_09_000012_create_testimonials_table', 1),
(16, '2026_10_09_000013_create_events_table', 1),
(17, '2026_10_09_000014_alter_categories_add_slug_columns', 2),
(18, '2026_10_21_091910_alter_gallery_items_add_nucomu_fields', 3),
(19, '2026_10_21_091910_alter_testimonials_add_nucomu_fields', 3),
(20, '2026_10_25_133754_add_source_to_testimonials_table', 3),
(21, '2026_10_25_181624_create_zendo_deliveries_table', 3),
(22, '2026_11_02_035907_create_banners_table', 4),
(23, '2026_11_02_050005_alter_facilities_add_image_and_description_columns', 4),
(24, '2026_11_02_064358_update_facilities_for_carousel_slides', 4),
(25, '2026_11_02_072456_alter_gallery_items_add_is_active_column', 4),
(26, '2026_11_02_074222_alter_testimonials_add_is_pinned_column', 4),
(27, '2026_11_02_075352_create_reservation_fields_table', 4),
(28, '2026_11_02_075359_alter_reservations_add_form_data_column', 4),
(29, '2026_11_02_122004_add_avatar_to_users_table', 4),
(30, '2026_11_02_173900_alter_orders_add_delivery_and_form_data_columns', 4),
(31, '2026_11_03_192500_add_layout_mode_to_banners_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `operating_hours`
--

CREATE TABLE `operating_hours` (
  `id` bigint UNSIGNED NOT NULL,
  `day_of_week` tinyint NOT NULL,
  `day_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `open_time` time DEFAULT NULL,
  `close_time` time DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `operating_hours`
--

INSERT INTO `operating_hours` (`id`, `day_of_week`, `day_name`, `open_time`, `close_time`, `is_closed`, `created_at`, `updated_at`) VALUES
(1, 0, 'Minggu', '11:00:00', '21:30:00', 0, '2026-10-09 08:02:01', '2026-10-09 08:15:04'),
(2, 1, 'Senin', NULL, NULL, 1, '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(3, 2, 'Selasa', '11:00:00', '20:30:00', 0, '2026-10-09 08:02:01', '2026-10-09 08:15:04'),
(4, 3, 'Rabu', '11:00:00', '20:30:00', 0, '2026-10-09 08:02:01', '2026-10-09 08:15:05'),
(5, 4, 'Kamis', '11:00:00', '20:30:00', 0, '2026-10-09 08:02:01', '2026-10-09 08:15:05'),
(6, 5, 'Jumat', '11:00:00', '20:30:00', 0, '2026-10-09 08:02:01', '2026-10-09 08:15:05'),
(7, 6, 'Sabtu', '11:00:00', '21:30:00', 0, '2026-10-09 08:02:01', '2026-10-09 08:15:05');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_type` enum('dine_in','take_away','pre_order') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dine_in',
  `table_id` bigint UNSIGNED DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `delivery_address` text COLLATE utf8mb4_unicode_ci,
  `form_data` json DEFAULT NULL,
  `subtotal` int UNSIGNED NOT NULL,
  `delivery_fee` int UNSIGNED NOT NULL DEFAULT '0',
  `total` int UNSIGNED NOT NULL,
  `payment_method` enum('cash','qris','transfer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `payment_status` enum('unpaid','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `status` enum('pending','processing','ready','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `pickup_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `code`, `customer_name`, `customer_phone`, `order_type`, `table_id`, `notes`, `delivery_address`, `form_data`, `subtotal`, `delivery_fee`, `total`, `payment_method`, `payment_status`, `status`, `pickup_at`, `created_at`, `updated_at`) VALUES
(1, 'NCM-20261021-001', 'p', '-', 'dine_in', NULL, NULL, NULL, NULL, 471000, 0, 471000, 'cash', 'unpaid', 'pending', NULL, '2026-10-21 03:04:44', '2026-10-21 03:04:44'),
(2, 'NCM-20261021-002', 'al', '-', 'dine_in', NULL, NULL, NULL, NULL, 44000, 0, 44000, 'cash', 'unpaid', 'pending', NULL, '2026-10-21 03:08:48', '2026-10-21 03:08:48'),
(3, 'NCM-20261021-003', 'al', '-', 'dine_in', NULL, NULL, NULL, NULL, 151000, 0, 151000, 'cash', 'paid', 'completed', NULL, '2026-10-21 03:18:13', '2026-10-21 04:00:09'),
(4, 'NCM-20261021-004', 'al', '-', 'dine_in', NULL, NULL, NULL, NULL, 28000, 0, 28000, 'cash', 'unpaid', 'pending', NULL, '2026-10-21 03:26:35', '2026-10-21 03:26:35'),
(5, 'NCM-20261025-001', 'krucel', 'Via Zendo WA', 'take_away', NULL, '[KURIR ZENDO] Alamat: boyolangu', NULL, NULL, 63000, 0, 63000, 'cash', 'unpaid', 'pending', NULL, '2026-10-25 09:56:51', '2026-10-25 09:56:51'),
(6, 'NCM-20261025-002', 'krucel', 'Via Zendo WA', 'take_away', NULL, '[KURIR ZENDO] Alamat: Kaliwungu, Tulungagung, Jawa Timur, 66155, Indonesia | Jarak: 13.0 km | Ongkir: Rp 33.000 | Pin: -8.104108,112.029296', NULL, NULL, 22000, 0, 55000, 'cash', 'unpaid', 'pending', NULL, '2026-10-25 10:22:29', '2026-10-25 10:22:29'),
(7, 'NCM-20261025-003', 'krucel', 'Via Zendo WA', 'take_away', NULL, '[KURIR ZENDO] Kurir: Kurir Zendo 1 (#1) | HP_Kurir: 6285707300240 | Tahap_Zendo: delivering | Alamat: Jepun, Karangrejo, Tulungagung, Jawa Timur, 66234, Indonesia | Jarak: 5.0 km | Ongkir: Rp 15.000 | Pin: -8.099729,111.917169', NULL, NULL, 18000, 0, 33000, 'cash', 'unpaid', 'processing', NULL, '2026-10-25 11:36:13', '2026-10-25 11:36:13'),
(8, 'NCM-20261025-004', 'al', 'Via Zendo WA', 'take_away', NULL, '[KURIR ZENDO] Kurir: Kurir Zendo 1 (#1) | HP_Kurir: 6285707300240 | Tahap_Zendo: delivering | Alamat: Kepatihan, Ringinpitu, Tulungagung, Jawa Timur, 66223, Indonesia | Jarak: 3.7 km | Ongkir: Rp 12.000 | Pin: -8.058657,111.929398', NULL, NULL, 18000, 0, 30000, 'cash', 'unpaid', 'processing', NULL, '2026-10-25 11:55:47', '2026-10-25 11:55:47'),
(9, 'NCM-20261026-001', 'P', 'Via Zendo WA', 'take_away', NULL, '[KURIR ZENDO] Kurir: Kurir Zendo 1 (#1) | HP_Kurir: 6285707300240 | Tahap_Zendo: delivering | Alamat: Kepatihan, Ringinpitu, Tulungagung, Jawa Timur, 66223, Indonesia | Jarak: 3.6 km | Ongkir: Rp 12.000 | Pin: -8.05915,111.929608', NULL, NULL, 27000, 0, 39000, 'cash', 'unpaid', 'processing', NULL, '2026-10-25 20:18:07', '2026-10-25 20:18:07'),
(10, 'NCM-20261026-002', 'P', 'Via Zendo WA', 'take_away', NULL, '[KURIR ZENDO] Kurir: Kurir Zendo 1 (#1) | HP_Kurir: 6285707300240 | Tahap_Zendo: delivering | Alamat: Bago, Tulungagung, Jawa Timur, 66218, Indonesia | Jarak: 1.3 km | Ongkir: Rp 10.000 | Pin: -8.068839,111.924777', NULL, NULL, 27000, 0, 37000, 'cash', 'unpaid', 'processing', NULL, '2026-10-25 20:37:18', '2026-10-25 20:37:18'),
(11, 'NCM-20261026-003', 'P', 'Via Zendo WA', 'take_away', NULL, '[KURIR ZENDO] Kurir: Kurir Zendo 1 (#1) | HP_Kurir: 6285707300240 | Tahap_Zendo: delivering | Alamat: Kepatihan, Tunggulsari, Tulungagung, Jawa Timur, 66223, Indonesia | Jarak: 3.2 km | Ongkir: Rp 12.000 | Pin: -8.06904,111.926258', NULL, NULL, 22000, 0, 34000, 'cash', 'unpaid', 'processing', NULL, '2026-10-25 20:50:34', '2026-10-25 20:50:34'),
(12, 'NCM-20261026-004', 'P', 'Via Zendo WA', 'take_away', NULL, '[KURIR ZENDO] Kurir: Kurir Zendo 1 (#1) | HP_Kurir: 6285707300240 | Tahap_Zendo: delivering | Alamat: Kepatihan, Ringinpitu, Tulungagung, Jawa Timur, 66223, Indonesia | Jarak: 3.7 km | Ongkir: Rp 12.000 | Pin: -8.058644,111.929294', NULL, NULL, 22000, 0, 34000, 'cash', 'unpaid', 'processing', NULL, '2026-10-25 21:17:30', '2026-10-25 21:17:30'),
(13, 'NCM-20261026-005', 'Azamaira', '085649496023', 'take_away', NULL, '[KURIR ZENDO] Kurir: Kurir Zendo 1 (#1) | HP_Kurir: 6285707300240 | Tahap_Zendo: delivering | Alamat: Plosokandang, Kedungwaru, Tulungagung | Jarak: 0.7 km | Ongkir: Rp 10.000 | Pin: -8.0748,111.9225', NULL, NULL, 55000, 0, 65000, 'cash', 'unpaid', 'processing', NULL, '2026-10-25 22:36:57', '2026-10-25 22:36:57'),
(14, 'NCM-20261026-006', 'a', 'Via Zendo WA', 'take_away', NULL, '[KURIR ZENDO] Kurir: Kurir Zendo 1 (#1) | HP_Kurir: 6285707300240 | Tahap_Zendo: delivering | Alamat: Kepatihan, Ringinpitu, Tulungagung, Jawa Timur, 66223, Indonesia | Jarak: 3.7 km | Ongkir: Rp 12.000 | Pin: -8.058642,111.929319', NULL, NULL, 50000, 0, 62000, 'cash', 'unpaid', 'processing', NULL, '2026-10-25 22:40:55', '2026-10-25 22:40:55'),
(15, 'NCM-20261026-007', 'Azamaira', '085649496023', 'take_away', NULL, '[KURIR ZENDO] Kurir: Kurir Zendo 1 (#1) | HP_Kurir: 6285707300240 | Tahap_Zendo: delivering | Alamat: Karangrejo, Tulungagung, Jawa Timur, 66234, Indonesia | Jarak: 4.9 km | Ongkir: Rp 15.000 | Pin: -8.098999,111.915326', NULL, NULL, 35000, 0, 50000, 'cash', 'unpaid', 'processing', NULL, '2026-10-25 22:43:31', '2026-10-25 22:43:31'),
(16, 'NCM-20261026-008', 'al', 'Via Zendo WA', 'take_away', NULL, '[KURIR ZENDO] Kurir: Kurir Zendo 1 (#1) | HP_Kurir: 6285707300240 | Tahap_Zendo: delivering | Alamat: Kepatihan, Ringinpitu, Tulungagung, Jawa Timur, 66223, Indonesia | Jarak: 3.6 km | Ongkir: Rp 12.000 | Pin: -8.059102,111.929454', NULL, NULL, 27000, 0, 39000, 'cash', 'unpaid', 'processing', NULL, '2026-10-25 22:58:39', '2026-10-25 22:58:39'),
(17, 'NCM-20261029-001', 'alma', '081235220103', 'take_away', NULL, '[KURIR ZENDO] Kurir: Kurir Zendo 1 (#1) | HP_Kurir: 6285707300240 | Tahap_Zendo: delivering | Alamat: Doroampel, Tulungagung, Jawa Timur, 66291, Indonesia | Jarak: 5.9 km | Ongkir: Rp 17.000 | Pin: -8.110539,111.93849', NULL, NULL, 27000, 0, 44000, 'cash', 'unpaid', 'processing', NULL, '2026-10-28 22:07:52', '2026-10-28 22:07:52'),
(18, 'NCM-20261102-001', 'Pelanggan Kasir', '-', 'dine_in', NULL, NULL, NULL, NULL, 53000, 0, 53000, 'cash', 'paid', 'processing', NULL, '2026-11-01 19:43:49', '2026-11-01 19:43:49'),
(19, 'NCM-20261102-002', 'Pelanggan Kasir', '-', 'dine_in', NULL, NULL, NULL, NULL, 45000, 0, 45000, 'cash', 'paid', 'processing', NULL, '2026-11-01 19:47:31', '2026-11-01 19:47:31');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `menu_id` bigint UNSIGNED DEFAULT NULL,
  `menu_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` int UNSIGNED NOT NULL,
  `qty` int NOT NULL,
  `options` json DEFAULT NULL,
  `line_total` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `menu_id`, `menu_name`, `unit_price`, `qty`, `options`, `line_total`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Banoffee', 28000, 6, NULL, 168000, '2026-10-21 03:04:44', '2026-10-21 03:04:44'),
(2, 1, 8, 'Nasi Cakalang Suwir', 35000, 6, NULL, 210000, '2026-10-21 03:04:44', '2026-10-21 03:04:44'),
(3, 1, 13, 'Kentang Goreng Crispy', 18000, 1, NULL, 18000, '2026-10-21 03:04:44', '2026-10-21 03:04:44'),
(4, 1, 17, 'Nucomu Coffee', 25000, 3, NULL, 75000, '2026-10-21 03:04:44', '2026-10-21 03:04:44'),
(5, 2, 4, 'Donut Choco Pistachio', 22000, 1, NULL, 22000, '2026-10-21 03:08:48', '2026-10-21 03:08:48'),
(6, 2, 25, 'Chocolate Milk', 22000, 1, NULL, 22000, '2026-10-21 03:08:48', '2026-10-21 03:08:48'),
(7, 3, 1, 'Banoffee', 28000, 1, NULL, 28000, '2026-10-21 03:18:13', '2026-10-21 03:18:13'),
(8, 3, 8, 'Nasi Cakalang Suwir', 35000, 3, NULL, 105000, '2026-10-21 03:18:13', '2026-10-21 03:18:13'),
(9, 3, 13, 'Kentang Goreng Crispy', 18000, 1, NULL, 18000, '2026-10-21 03:18:13', '2026-10-21 03:18:13'),
(10, 4, 1, 'Banoffee', 28000, 1, NULL, 28000, '2026-10-21 03:26:35', '2026-10-21 03:26:35'),
(11, 5, 1, 'Banoffee', 28000, 1, NULL, 28000, '2026-10-25 09:56:51', '2026-10-25 09:56:51'),
(12, 5, 8, 'Nasi Cakalang Suwir', 35000, 1, NULL, 35000, '2026-10-25 09:56:51', '2026-10-25 09:56:51'),
(13, 6, 28, 'Brown Sugar Milk', 22000, 1, NULL, 22000, '2026-10-25 10:22:29', '2026-10-25 10:22:29'),
(14, 7, 13, 'Kentang Goreng Crispy', 18000, 1, NULL, 18000, '2026-10-25 11:36:13', '2026-10-25 11:36:13'),
(15, 8, 13, 'Kentang Goreng Crispy', 18000, 1, NULL, 18000, '2026-10-25 11:55:47', '2026-10-25 11:55:47'),
(16, 9, 2, 'Cheesecake', 27000, 1, NULL, 27000, '2026-10-25 20:18:07', '2026-10-25 20:18:07'),
(17, 10, 2, 'Cheesecake', 27000, 1, NULL, 27000, '2026-10-25 20:37:18', '2026-10-25 20:37:18'),
(18, 11, 4, 'Donut Choco Pistachio', 22000, 1, NULL, 22000, '2026-10-25 20:50:34', '2026-10-25 20:50:34'),
(19, 12, 4, 'Donut Choco Pistachio', 22000, 1, NULL, 22000, '2026-10-25 21:17:30', '2026-10-25 21:17:30'),
(20, 13, 1, 'Banoffee', 28000, 1, NULL, 28000, '2026-10-25 22:36:57', '2026-10-25 22:36:57'),
(21, 13, 2, 'Cheesecake', 27000, 1, NULL, 27000, '2026-10-25 22:36:57', '2026-10-25 22:36:57'),
(22, 14, 1, 'Banoffee', 28000, 1, NULL, 28000, '2026-10-25 22:40:55', '2026-10-25 22:40:55'),
(23, 14, 4, 'Donut Choco Pistachio', 22000, 1, NULL, 22000, '2026-10-25 22:40:55', '2026-10-25 22:40:55'),
(24, 15, 8, 'Nasi Cakalang Suwir', 35000, 1, NULL, 35000, '2026-10-25 22:43:31', '2026-10-25 22:43:31'),
(25, 16, 2, 'Cheesecake', 27000, 1, NULL, 27000, '2026-10-25 22:58:39', '2026-10-25 22:58:39'),
(26, 17, 2, 'Cheesecake', 27000, 1, NULL, 27000, '2026-10-28 22:07:52', '2026-10-28 22:07:52'),
(27, 18, 18, 'Butterscloud', 28000, 1, NULL, 28000, '2026-11-01 19:43:49', '2026-11-01 19:43:49'),
(28, 18, 24, 'Matcha', 25000, 1, NULL, 25000, '2026-11-01 19:43:49', '2026-11-01 19:43:49'),
(29, 19, 17, 'Nucomu Coffee', 25000, 1, NULL, 25000, '2026-11-01 19:47:31', '2026-11-01 19:47:31'),
(30, 19, 23, 'Lychee Tea', 20000, 1, NULL, 20000, '2026-11-01 19:47:31', '2026-11-01 19:47:31');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `description` text,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `stock` int UNSIGNED NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guests` int NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `form_data` json DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `name`, `phone`, `date`, `time`, `guests`, `notes`, `form_data`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Budi Santoso', '081298765432', '2026-10-25', '16:00', 4, 'Layanan: Birthday Treats | Area: Indoor (AC & Working Space) | Catatan: Tolong siapkan meja dekat stopkontak', NULL, 'cancelled', '2026-10-18 07:21:48', '2026-10-31 22:52:16');

-- --------------------------------------------------------

--
-- Table structure for table `reservation_fields`
--

CREATE TABLE `reservation_fields` (
  `id` bigint UNSIGNED NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `placeholder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `options` json DEFAULT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservation_fields`
--

INSERT INTO `reservation_fields` (`id`, `label`, `field_key`, `type`, `placeholder`, `options`, `is_required`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Nama Lengkap', 'name', 'text', 'Khanzahamida', NULL, 1, 1, 1, '2026-11-04 06:09:31', '2026-11-04 06:09:31'),
(2, 'Nomor WhatsApp', 'phone', 'tel', '08xxxxxxxxxx', NULL, 1, 2, 1, '2026-11-04 06:09:31', '2026-11-04 06:09:31'),
(3, 'Tanggal Kedatangan', 'date', 'date', NULL, NULL, 1, 3, 1, '2026-11-04 06:09:31', '2026-11-04 06:09:31'),
(4, 'Jam Kedatangan', 'time', 'time', '16:00', NULL, 1, 4, 1, '2026-11-04 06:09:31', '2026-11-04 06:09:31'),
(5, 'Jumlah Tamu', 'guests', 'number', 'Contoh: 4', NULL, 1, 5, 1, '2026-11-04 06:09:31', '2026-11-04 06:09:31'),
(6, 'Catatan Tambahan', 'notes', 'textarea', 'Tolong siapkan meja dekat colokan / dekorasi santai', NULL, 0, 6, 1, '2026-11-04 06:09:31', '2026-11-04 06:09:31');

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
('4nIr6QITyISkKZbplHDYz8i7lOfCkIxGyEmN1AeY', NULL, '192.168.18.5', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Mobile Safari/537.36', 'eyJfdG9rZW4iOiJuanV6bVJzRDdyQ0ZhejJXMEdSMFdIMEhWdjdCeVpvSnJkc2ZDQWJHIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzE5Mi4xNjguMTguMjUyOjgwMDAiLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795340476),
('7yRdIJpKHaq1gNQu9AxQKa7oMxnEuDABOaoA4eHU', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJCelRLdTcyUUhHZFkycmJBNXBUa3NyVUNsMkFCZVEyTUdXVjd1T1N5IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL3Vta20udGVzdCIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1795337657),
('aMG80FdVqdlFxowkJdm5fOqEWMmejaVppIggA4FZ', NULL, '192.168.18.5', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Mobile Safari/537.36', 'eyJfdG9rZW4iOiIxRlJKVENwNXlVWmVRbEw2MmtwdFcxaWwyMVRiRHYwS2g0QW8yY281IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzE5Mi4xNjguMTguMjUyOjgwMDAiLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795340410),
('fUzHUwfeS3ri8EYCg7cPIxSMQKabbC6FFmvUosdi', NULL, '172.27.27.116', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Mobile Safari/537.36', 'eyJfdG9rZW4iOiI2bjMwSkFSWUtDNEVwV1d5d3VFbGdpQjU4Z3BCblFxWnNEVTE2UTIzIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzE3Mi4yNy4yNy4xMTY6ODAwMCIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1794900302),
('IS5UwYP1OUgnk1nieNNGLrerNExLfqTOxjFBymf4', NULL, '192.168.18.5', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Mobile Safari/537.36', 'eyJfdG9rZW4iOiJWeXp0M1NBNHpyeHVxa0ZXMEtRUE5sdUIyWVpKSFR6bjgwc3Y4UzVnIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzE5Mi4xNjguMTguMjUyOjgwMDAiLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795340376),
('ms7SXevDelIx0WBm7Q6wyUI3qEuEz9pRJVA6J1xd', NULL, '192.168.18.5', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Mobile Safari/537.36', 'eyJfdG9rZW4iOiJLdmVFRTBjdmMwc0Q2a2kzMTh1SVZYNDVIeGZNRUNlOTE5emJ5bzJZIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzE5Mi4xNjguMTguMjUyOjgwMDAiLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795340482),
('nhrfx04uy4qW4yCI0zsbj6UZfeUy1A3OtjkjrKLV', NULL, '192.168.18.5', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Mobile Safari/537.36', 'eyJfdG9rZW4iOiJmMmpvQzh6NDBJR0NhTTh0eFNYSzFDMFFXd1k5QURoZ3Bub0RMbW5lIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzE5Mi4xNjguMTguMjUyOjgwMDAiLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795340480),
('x5ZSZSEX5AbXpX87DUmkXQjx3DzYE4IPq8tCgTh6', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJlWkxaeWdyOERHcWVlUFlGUWMzNVFFa0RlT1pMaDJYdjNXRDNKR01GIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL3Vta20udGVzdFwva2FzaXIiLCJyb3V0ZSI6Imthc2lyLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsInBhc3N3b3JkX2hhc2hfd2ViIjoiNjQ5ZjRlMjgyMjE0ZDQyYmFlMGIzOGNiZDNiMGExYjVjZTE2YWNhMGU5NjNlMjRiMDhhYmY2YjYwMGQ4NDZmZCIsImthc2lyX2F1dGhlbnRpY2F0ZWQiOnRydWV9', 1794900393),
('xqVGN2XzPAf7dXdOJ1qqWGJraZWOnqPpo41S49Ky', NULL, '172.27.27.116', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Mobile Safari/537.36', 'eyJfdG9rZW4iOiJOa0k0NE0xYzNDdjcwY0JmRUtKelVDazd0MUMxb1c0Y1NhRWF6NGN6IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzE3Mi4yNy4yNy4xMTY6ODAwMCIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1794899273);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'cafe_name', 'Nucomu Cafe', '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(2, 'cafe_tagline', 'New, Unforgettable, Comfy, Musings', '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(3, 'cafe_subtitle', 'Coffee & Dessert', '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(4, 'cafe_story', 'Nucomu Cafe hadir sebagai tempat yang nyaman untuk menikmati dessert, makanan, snack, kopi, dan minuman lainnya. Dengan konsep monokrom elegan — perpaduan hitam, putih, dan abu-abu — kami ingin setiap sudut cafe terasa seperti rumah kedua. Banyak dipilih pelanggan sebagai tempat favorit untuk WFC (Work From Cafe).', '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(5, 'cafe_history', 'Nucomu lahir dari \"rindu rasa\" pelanggan setia yang dulu mengenal kami sebagai Banoffea, bisnis dessert kecil dengan sistem pre-order sejak 2020. Setelah sempat berhenti di 2024–2025, kami kembali hadir dengan wajah baru — Nucomu Cafe — agar pelanggan lama dan baru bisa datang langsung menikmati dessert otentik kami dengan suasana yang nyaman.', '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(6, 'cafe_concept', 'Monokrom minimalis — putih, hitam, abu-abu. Elegan, bersih, nyaman. Dirancang agar setiap pengunjung merasa betah untuk berlama-lama, entah untuk bekerja, bersantai, atau sekadar menikmati momen.', '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(7, 'cafe_address', '[ISI: alamat lengkap Nucomu Cafe, Tulungagung]', '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(8, 'cafe_maps_url', '[ISI: link Google Maps]', '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(9, 'cafe_whatsapp', '[ISI: nomor WhatsApp, contoh: 6281234567890]', '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(10, 'cafe_email', '[ISI: email cafe]', '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(11, 'cafe_instagram', '[ISI: link Instagram]', '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(12, 'cafe_tiktok', '[ISI: link TikTok]', '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(13, 'transaction_mode', 'kasir', '2026-10-25 09:55:24', '2026-11-16 23:20:19'),
(14, 'zendo_whatsapp', '085707300240', '2026-10-25 09:55:24', '2026-10-25 09:55:24'),
(15, 'zendo_courier_1_status', 'idle', '2026-10-25 11:07:27', '2026-10-25 11:07:40'),
(16, 'zendo_courier_1_last_order_at', '2026-10-29 05:07:52', '2026-10-25 11:36:13', '2026-10-28 22:07:52'),
(19, 'zendo_last_ping_6285707300240', '2026-10-29 05:11:00', '2026-10-25 11:54:33', '2026-10-28 22:11:00'),
(20, 'zendo_device_pin_8oonBZ4N8Qpb8OqENg65powbEAAo9MLVPdfmKEufehroolxn', 'deb1a82682441ba2374fc218457b0152ef9ee156d28e985b059b51becf325da3', '2026-10-25 23:38:55', '2026-10-25 23:38:55'),
(21, 'zendo_device_pin_at_8oonBZ4N8Qpb8OqENg65powbEAAo9MLVPdfmKEufehroolxn', '2026-10-26 06:38:55', '2026-10-25 23:38:55', '2026-10-25 23:38:55'),
(22, 'zendo_courier_pin', '9788872', '2026-10-25 23:39:58', '2026-10-30 07:14:50'),
(23, 'zendo_courier_1_name', 'Kurir Zendo 1', '2026-10-25 23:39:58', '2026-10-25 23:39:58'),
(24, 'zendo_courier_1_phone', '085707300240', '2026-10-25 23:39:58', '2026-10-25 23:39:58'),
(25, 'zendo_courier_count', '1', '2026-10-25 23:39:58', '2026-10-25 23:39:58'),
(26, 'zendo_couriers', '[{\"index\":1,\"name\":\"Kurir Zendo 1\",\"phone\":\"085707300240\",\"clean_phone\":\"6285707300240\",\"status\":\"idle\"}]', '2026-10-25 23:39:58', '2026-10-25 23:39:58'),
(28, 'zendo_revoked_3abb0153503ef18cf1d1821d6d17f2cf0a91684c721f6224ea9d9b49dd6a826d', '2026-10-26 07:14:32', '2026-10-26 00:14:32', '2026-10-26 00:14:32'),
(29, 'zendo_connected_devices', '[]', '2026-10-26 00:14:32', '2026-10-30 07:07:12'),
(32, 'zendo_device_pin_VNnUPnzL5esRjf2Z1OahJSRuhUJxh7bKLcXzF72zN0UkjGMu', '05a30fa23a237540e77fb40b17b7c3ad4b45d39fe55539a7445f5afaf902baaa', '2026-10-26 22:49:16', '2026-10-26 22:49:16'),
(33, 'zendo_device_pin_at_VNnUPnzL5esRjf2Z1OahJSRuhUJxh7bKLcXzF72zN0UkjGMu', '2026-10-27 05:49:16', '2026-10-26 22:49:16', '2026-10-26 22:49:16'),
(34, 'zendo_revoked_05a30fa23a237540e77fb40b17b7c3ad4b45d39fe55539a7445f5afaf902baaa', '2026-10-27 05:49:25', '2026-10-26 22:49:25', '2026-10-26 22:49:25'),
(38, 'zendo_revoked_36152f0712f291ad513c567c3851ea6e4e1f0a95feab07f4c3fd0ffb3ba58668', '2026-10-27 05:49:37', '2026-10-26 22:49:37', '2026-10-26 22:49:37'),
(41, 'zendo_revoked_8799b1b6610b03f2570b5e062815387d6a67bb8dde1a503807b04fe7d669f015', '2026-10-27 06:05:05', '2026-10-26 23:05:05', '2026-10-26 23:05:05'),
(44, 'zendo_device_pin_ZGgzUdCE9lQ1ug0kHfqV3uQBw4trjOYd5liXnEul4TKci9WM', '3a8e2d0dccaaab7fd5898633277cf61104bfd368442beac60074fe16b8c548d7', '2026-10-26 23:05:44', '2026-10-26 23:05:44'),
(45, 'zendo_device_pin_at_ZGgzUdCE9lQ1ug0kHfqV3uQBw4trjOYd5liXnEul4TKci9WM', '2026-10-30 14:03:55', '2026-10-26 23:05:44', '2026-10-30 07:03:55'),
(46, 'zendo_courier_session_epoch', '1793084240', '2026-10-26 23:06:01', '2026-10-26 23:57:20'),
(69, 'zendo_device_pin_B1oYn6daJswf27udWbBjxfN7yIb3X1nCBtLP7LCOj6YasWyi', 'd0bb06255c233b1116f764e6b4d4c2827cf941f71b6759ee9605320f8962077a', '2026-10-28 22:10:24', '2026-10-28 22:10:24'),
(70, 'zendo_device_pin_at_B1oYn6daJswf27udWbBjxfN7yIb3X1nCBtLP7LCOj6YasWyi', '2026-10-29 05:10:24', '2026-10-28 22:10:24', '2026-10-28 22:10:24'),
(74, 'zendo_revoked_3a8e2d0dccaaab7fd5898633277cf61104bfd368442beac60074fe16b8c548d7', '2026-10-30 14:06:49', '2026-10-30 07:06:49', '2026-10-30 07:06:49'),
(77, 'zendo_revoked_1e423dd1dd18dd491e37b85d6975f2e67b23cfe77f5efff02066bcfc0153c82c', '2026-10-30 14:07:12', '2026-10-30 07:07:12', '2026-10-30 07:07:12'),
(78, 'hide_live_tracking', '1', '2026-10-31 06:37:51', '2026-11-04 06:31:40'),
(79, 'kasir_menu_stocks', '{\"18\":25,\"24\":24,\"17\":25,\"23\":25,\"1\":25,\"2\":25,\"3\":25,\"4\":25,\"5\":25,\"6\":25,\"7\":25,\"8\":25,\"9\":25,\"10\":25,\"11\":25,\"12\":25,\"13\":25,\"14\":25,\"15\":25,\"16\":25,\"19\":25,\"20\":25,\"21\":25,\"22\":25,\"25\":25,\"26\":25,\"27\":25,\"28\":25}', '2026-11-01 19:43:49', '2026-11-17 00:04:10'),
(80, 'about_label', 'OUR STORY / TENTANG KAMI', '2026-11-04 08:06:41', '2026-11-04 08:06:41'),
(81, 'cafe_story_headline', 'Berawal dari Rasa, Kembali karena Rindu.', '2026-11-04 08:06:41', '2026-11-04 08:06:41'),
(82, 'cafe_story_p1', 'Nucomu Cafe menghadirkan ruang yang tenang dan nyaman di Tulungagung untuk menikmati kopi, dessert, makanan, serta waktu bersama orang-orang terdekat.', '2026-11-04 08:06:41', '2026-11-04 08:06:41'),
(83, 'cafe_story_p2', 'Dengan paduan estetika monokrom hangat dan material alami, setiap sudut dirancang agar Anda dapat beristirahat sejenak dari kesibukan harian dan menikmati obrolan yang mengalir perlahan.', '2026-11-04 08:06:41', '2026-11-04 08:06:41'),
(84, 'about_quote', 'Ruang ramah di Tulungagung di mana aroma kopi yang baru diseduh menyapa hangat, dan setiap cangkir disajikan dengan ketulusan tangan.', '2026-11-04 08:06:41', '2026-11-04 08:06:41'),
(85, 'about_button_text', 'Jelajahi Pilihan Menu', '2026-11-04 08:06:41', '2026-11-04 08:06:41'),
(86, 'about_button_link', '#menu', '2026-11-04 08:06:41', '2026-11-04 08:06:41'),
(87, 'about_media_type', 'video', '2026-11-04 08:06:41', '2026-11-04 08:06:41'),
(88, 'about_media_path', 'about_1793804801_521491a2.mp4', '2026-11-04 08:06:41', '2026-11-04 08:06:41'),
(89, 'hide_dashboard_chart', '1', '2026-11-04 18:40:48', '2026-11-04 18:40:48'),
(90, 'show_dashboard_chart', '0', '2026-11-04 18:40:48', '2026-11-04 18:40:48'),
(91, 'order_mode_dine_in', '1', '2026-11-16 23:20:19', '2026-11-16 23:20:19'),
(92, 'order_mode_zendo', '0', '2026-11-16 23:20:19', '2026-11-16 23:20:19'),
(93, 'order_mode_zendo_tracking', '0', '2026-11-16 23:20:19', '2026-11-16 23:20:19'),
(94, 'order_mode_zendo_chat', '0', '2026-11-16 23:20:19', '2026-11-16 23:20:19');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` bigint UNSIGNED NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int NOT NULL DEFAULT '2',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `number`, `capacity`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'A1', 2, 1, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(2, 'A2', 2, 1, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(3, 'A3', 2, 1, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(4, 'B1', 4, 1, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(5, 'B2', 4, 1, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(6, 'B3', 4, 1, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(7, 'C1', 6, 1, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(8, 'C2', 6, 1, '2026-10-09 08:02:00', '2026-10-09 08:02:00'),
(9, 'VIP', 8, 1, '2026-10-09 08:02:00', '2026-10-09 08:02:00');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `handle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_tag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `review_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint UNSIGNED NOT NULL DEFAULT '5',
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'website',
  `is_sample` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_pinned` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `handle`, `order_tag`, `avatar_url`, `review_image`, `content`, `rating`, `source`, `is_sample`, `is_active`, `is_pinned`, `created_at`, `updated_at`) VALUES
(1, 'Rina S.', NULL, NULL, NULL, NULL, 'Tempatnya nyaman banget, cocok buat nugas sambil ngopi! Interior monokromnya estetik banget.', 5, 'website', 1, 1, 1, '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(2, 'Bagas P.', NULL, NULL, NULL, NULL, 'Dessert-nya enak, apalagi banoffee-nya, langsung bikin kangen. Rasa otentiknya beda dari yang lain!', 5, 'website', 1, 1, 1, '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(3, 'Dewi A.', NULL, NULL, NULL, NULL, 'WiFi kencang, colokan banyak, recommended banget buat WFC! Betah kerja di sini seharian.', 5, 'website', 1, 1, 1, '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(4, 'Fajar M.', NULL, NULL, NULL, NULL, 'Harganya terjangkau untuk kafe sekeren ini. Cheesecake-nya creamy, tiramisu-nya top!', 5, 'website', 1, 1, 0, '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(5, 'Siti N.', NULL, NULL, NULL, NULL, 'Nucomu Coffee signature-nya unik, ada rasa khas yang bikin beda dari kafe lain di Tulungagung.', 5, 'website', 1, 1, 1, '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(6, 'Reza K.', NULL, NULL, NULL, NULL, 'Butterscloud-nya worth it banget, jadi minuman favoritku sekarang! Harus cobain kalau mampir.', 5, 'website', 1, 1, 0, '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(7, 'Anisa T.', NULL, NULL, NULL, NULL, 'Suasana tenang dan elegan, cocok buat kerja maupun sekadar bersantai. Pasti balik lagi!', 5, 'website', 1, 1, 0, '2026-10-09 08:02:01', '2026-10-09 08:02:01'),
(8, 'Budi R.', NULL, NULL, NULL, NULL, 'Pelayanannya ramah, tempatnya bersih dan rapi. Donut Choco Pistachio-nya surga banget!', 4, 'website', 1, 1, 0, '2026-10-09 08:02:01', '2026-10-09 08:02:01');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `customer_name` varchar(150) DEFAULT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `transaction_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `product_name` varchar(150) NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(30) NOT NULL DEFAULT 'admin',
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'Admin Nucomu', 'admin@nucomu.com', '$2y$12$.bDfms3tU7m4/jvvJQQNEuXgOu7tOPifYnACKa9YU8UNlh3Y3zGNq', 'admin', NULL, '2026-10-09 07:59:33', '2026-10-09 08:15:04'),
(2, 'Kasir Nucomu', 'kasir@nucomu.com', '$2y$12$yi.sIqw8uKoo.C1ti1vR.e5IuXzuwr.lkV7OQSU5fT7OjLXGLexsK', 'kasir', NULL, '2026-11-01 19:20:55', '2026-11-01 19:20:55');

-- --------------------------------------------------------

--
-- Table structure for table `zendo_deliveries`
--

CREATE TABLE `zendo_deliveries` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `order_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `courier_index` smallint UNSIGNED NOT NULL DEFAULT '1',
  `courier_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `courier_phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tracking_token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `courier_token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'assigned',
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_address` text COLLATE utf8mb4_unicode_ci,
  `destination_lat` decimal(10,7) DEFAULT NULL,
  `destination_lng` decimal(10,7) DEFAULT NULL,
  `courier_lat` decimal(10,7) DEFAULT NULL,
  `courier_lng` decimal(10,7) DEFAULT NULL,
  `courier_accuracy` double DEFAULT NULL,
  `courier_heading` double DEFAULT NULL,
  `courier_speed` double DEFAULT NULL,
  `location_updated_at` timestamp NULL DEFAULT NULL,
  `route_trail` json DEFAULT NULL,
  `gps_suspicious_count` int UNSIGNED NOT NULL DEFAULT '0',
  `last_gps_warning` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gps_locked_until` timestamp NULL DEFAULT NULL,
  `courier_verified_at` timestamp NULL DEFAULT NULL,
  `picked_up_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zendo_deliveries`
--

INSERT INTO `zendo_deliveries` (`id`, `order_id`, `order_code`, `courier_index`, `courier_name`, `courier_phone`, `tracking_token`, `courier_token`, `status`, `customer_name`, `delivery_address`, `destination_lat`, `destination_lng`, `courier_lat`, `courier_lng`, `courier_accuracy`, `courier_heading`, `courier_speed`, `location_updated_at`, `route_trail`, `gps_suspicious_count`, `last_gps_warning`, `gps_locked_until`, `courier_verified_at`, `picked_up_at`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 7, 'NCM-20261025-003', 1, 'Kurir Zendo 1', '6285707300240', '1cZlmLmliSvyR2hEXhETYBzLoVYiOACAYOspFmLS', 'jK57LWWEiWtuNanxMNUTmwHOl2AG5AskeSKA9jBF', 'picked_up', 'krucel', 'Jepun, Karangrejo, Tulungagung, Jawa Timur, 66234, Indonesia', -8.0997290, 111.9171690, -8.0763599, 111.9188620, 8, 45, 6.5, '2026-10-25 11:54:33', '[[-8.0763599, 111.918862, 1792954473]]', 2, 'Lokasi melompat 2,6 km dalam 32.083679 detik — indikasi teleport / Fake GPS.', NULL, '2026-10-25 11:37:33', '2026-10-25 11:54:36', NULL, '2026-10-25 11:36:13', '2026-10-25 11:55:05'),
(2, 8, 'NCM-20261025-004', 1, 'Kurir Zendo 1', '6285707300240', 'QhJ5FSnrUQtS08aJAZ2OguHx21LxR1oZnEmFnflk', '4Gjxx5PtbtoeNBJu5Ua7m5g3nQsFkUOj31fYVXc7', 'picked_up', 'al', 'Kepatihan, Ringinpitu, Tulungagung, Jawa Timur, 66223, Indonesia', -8.0586570, 111.9293980, -8.0675835, 111.9240550, 8, 45, 6.5, '2026-10-25 11:57:31', '[[-8.0763599, 111.918862, 1792954610], [-8.0675835, 111.924055, 1792954651]]', 0, NULL, NULL, '2026-10-25 11:56:24', '2026-10-25 11:56:51', NULL, '2026-10-25 11:55:47', '2026-10-25 11:57:31'),
(3, 9, 'NCM-20261026-001', 1, 'Kurir Zendo 1', '6285707300240', 'uGb2t0IrUI2JWtlIwduwAss0ogvoWm7Z7xmBiqex', 'ii9HBRMNDjsj8qsEWB3fadNeZ1JDgJJ5eWzSruG8', 'assigned', 'P', 'Kepatihan, Ringinpitu, Tulungagung, Jawa Timur, 66223, Indonesia', -8.0591500, 111.9296080, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, '2026-10-25 20:18:07', '2026-10-25 20:18:07'),
(4, 10, 'NCM-20261026-002', 1, 'Kurir Zendo 1', '6285707300240', 'hpqM05sU88aCUy2xINkG5DVMUmHB117rqvmHmexJ', 'R5TK1FaMi8edCY5iIjBWIictdQBGd6wOzC5oBYZv', 'assigned', 'P', 'Bago, Tulungagung, Jawa Timur, 66218, Indonesia', -8.0688390, 111.9247770, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, '2026-10-25 20:37:18', '2026-10-25 20:37:18'),
(5, 11, 'NCM-20261026-003', 1, 'Kurir Zendo 1', '6285707300240', '9V6IZIJNdqiBhe85qApFqFxbEX9ClunqhkuDtRRR', 'eFmTCl5AaekMHJvS5IFG26eDpr9LLmS6UQkaDh4e', 'assigned', 'P', 'Kepatihan, Tunggulsari, Tulungagung, Jawa Timur, 66223, Indonesia', -8.0690400, 111.9262580, -8.0586565, 111.9293030, 4.0240001678467, 295.77478027344, 0.11815293133259, '2026-10-25 20:53:12', '[[-8.0586245, 111.9292928, 1792986682], [-8.0587417, 111.9293318, 1792986687], [-8.0586323, 111.9293048, 1792986689], [-8.0586701, 111.9291844, 1792986698], [-8.0583278, 111.9301745, 1792986710], [-8.0585392, 111.9295862, 1792986713], [-8.0586642, 111.9294041, 1792986715], [-8.0586576, 111.9293126, 1792986781]]', 0, NULL, NULL, '2026-10-25 20:51:15', NULL, NULL, '2026-10-25 20:50:34', '2026-10-25 20:53:12'),
(6, 12, 'NCM-20261026-004', 1, 'Kurir Zendo 1', '6285707300240', 'vLcCkK7fhFDnxxAykU6gD1ZoljQVVHBbFCSUBFB9', 'z8RIhyj3lDjhxqkFgfXA2fHjPLWiNiD9N3CnU5b3', 'assigned', 'P', 'Kepatihan, Ringinpitu, Tulungagung, Jawa Timur, 66223, Indonesia', -8.0586440, 111.9292940, -8.0586434, 111.9293978, 26.650999069214, 85.64079284668, 2.2013957500458, '2026-10-25 21:19:43', '[[-8.0586388, 111.9292995, 1792988262], [-8.0586434, 111.9293978, 1792988383]]', 0, NULL, NULL, '2026-10-25 21:18:32', NULL, NULL, '2026-10-25 21:17:30', '2026-10-25 21:19:43'),
(7, 13, 'NCM-20261026-005', 1, 'Kurir Zendo 1', '6285707300240', 'fmX9x7CMz0gUBDpuiwm3SMxej7W6Gxvle6YmtaXx', 'iGXBgqMgg0oaDlCtcQqmp4xRkUPNMYeHNXIJTVBZ', 'assigned', 'Azamaira', 'Plosokandang, Kedungwaru, Tulungagung', -8.0748000, 111.9225000, -8.0586076, 111.9292320, 6.0840001106262, 331.46301269531, 0.17311060428619, '2026-10-25 22:40:13', '[[-8.0586259, 111.9292939, 1792993180]]', 0, NULL, NULL, '2026-10-25 22:44:36', NULL, NULL, '2026-10-25 22:36:57', '2026-10-25 22:44:36'),
(8, 14, 'NCM-20261026-006', 1, 'Kurir Zendo 1', '6285707300240', 'TmyN8goH3n9tljQKEOC1pSmQ6LNI4As43OH6FNUt', '2iRXCx9HS1GsZDL0l1q4Xc8vGMUjczONKpjDSM7H', 'assigned', 'a', 'Kepatihan, Ringinpitu, Tulungagung, Jawa Timur, 66223, Indonesia', -8.0586420, 111.9293190, -8.0586549, 111.9294652, 31.388999938965, 92.593406677246, 4.9907383918762, '2026-10-25 22:41:10', '[[-8.0586581, 111.9293292, 1792993265], [-8.0586549, 111.9294652, 1792993270]]', 0, NULL, NULL, '2026-10-25 22:42:50', NULL, NULL, '2026-10-25 22:40:55', '2026-10-25 22:42:50'),
(9, 15, 'NCM-20261026-007', 1, 'Kurir Zendo 1', '6285707300240', 'S19CXEzN7CFp5KqfXrh9txkFQMbeovsDvhO3snWL', 'NuzEogIklESiHq0xpx0oRGYMZATKfuyCK42hUmVp', 'assigned', 'Azamaira', 'Karangrejo, Tulungagung, Jawa Timur, 66234, Indonesia', -8.0989990, 111.9153260, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-10-25 22:44:55', NULL, NULL, '2026-10-25 22:43:31', '2026-10-25 22:44:55'),
(10, 16, 'NCM-20261026-008', 1, 'Kurir Zendo 1', '6285707300240', 'upno7AvNzlk8OAeL0YdSOsZJWQJZLJhHmW2zslqk', 'iSPchznQHujIvcB02eX4g9bDsiitaRpEAYAA486n', 'assigned', 'al', 'Kepatihan, Ringinpitu, Tulungagung, Jawa Timur, 66223, Indonesia', -8.0591020, 111.9294540, -8.0590552, 111.9292149, 118, NULL, NULL, '2026-10-25 23:41:15', '[[-8.0588965, 111.9292251, 1792994557], [-8.0590742, 111.9292697, 1792996811], [-8.0592542, 111.9293749, 1792996823], [-8.0590498, 111.9294822, 1792996836], [-8.059066, 111.929271, 1792996862]]', 0, NULL, NULL, '2026-10-27 00:04:44', NULL, NULL, '2026-10-25 22:58:39', '2026-10-27 00:04:44'),
(11, 17, 'NCM-20261029-001', 1, 'Kurir Zendo 1', '6285707300240', 'YfTxBuwZQwYnvmebKEZVFiG1WOjAb2CpZ574M19i', '0NznHSacxiCTNd4ycNAgcqvDvgETe2BYtqVCbFYV', 'assigned', 'alma', 'Doroampel, Tulungagung, Jawa Timur, 66291, Indonesia', -8.1105390, 111.9384900, -8.0586239, 111.9293518, 22.320999145508, 50.451705932617, 0.4433037340641, '2026-10-28 22:11:01', '[[-8.0586502, 111.9293348, 1793250637]]', 0, NULL, NULL, '2026-10-30 07:06:50', NULL, NULL, '2026-10-28 22:07:52', '2026-10-30 07:06:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `gallery_items`
--
ALTER TABLE `gallery_items`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menus_slug_unique` (`slug`),
  ADD KEY `menus_category_id_foreign` (`category_id`);

--
-- Indexes for table `menu_options`
--
ALTER TABLE `menu_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_options_menu_id_foreign` (`menu_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `operating_hours`
--
ALTER TABLE `operating_hours`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_code_unique` (`code`),
  ADD KEY `orders_table_id_foreign` (`table_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_menu_id_foreign` (`menu_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservation_fields`
--
ALTER TABLE `reservation_fields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reservation_fields_field_key_unique` (`field_key`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tables_number_unique` (`number`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `zendo_deliveries`
--
ALTER TABLE `zendo_deliveries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `zendo_deliveries_tracking_token_unique` (`tracking_token`),
  ADD UNIQUE KEY `zendo_deliveries_courier_token_unique` (`courier_token`),
  ADD KEY `zendo_deliveries_order_id_foreign` (`order_id`),
  ADD KEY `zendo_deliveries_order_code_index` (`order_code`),
  ADD KEY `zendo_deliveries_courier_phone_index` (`courier_phone`),
  ADD KEY `zendo_deliveries_status_index` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery_items`
--
ALTER TABLE `gallery_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `menu_options`
--
ALTER TABLE `menu_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `operating_hours`
--
ALTER TABLE `operating_hours`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reservation_fields`
--
ALTER TABLE `reservation_fields`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `zendo_deliveries`
--
ALTER TABLE `zendo_deliveries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu_options`
--
ALTER TABLE `menu_options`
  ADD CONSTRAINT `menu_options_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD CONSTRAINT `transaction_details_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `zendo_deliveries`
--
ALTER TABLE `zendo_deliveries`
  ADD CONSTRAINT `zendo_deliveries_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
