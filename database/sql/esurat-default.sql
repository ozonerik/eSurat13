-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20250718.d42db65a1e
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 10, 2026 at 12:58 PM
-- Server version: 8.4.3
-- PHP Version: 8.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `esurat`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `surat_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `logged_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('esurat13-cache-livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6', 'i:1;', 1778417843),
('esurat13-cache-livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6:timer', 'i:1778417843;', 1778417843),
('esurat13-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:50:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:14:\"audit-log.read\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:17:\"telegram-log.read\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:12:\"sekolah.read\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:14:\"sekolah.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:19:\"kepala-sekolah.read\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:21:\"kepala-sekolah.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:21:\"kategori-surat.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:19:\"kategori-surat.read\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:21:\"kategori-surat.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:21:\"kategori-surat.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:18:\"jenis-surat.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:5;i:2;i:6;i:3;i:7;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:20:\"jenis-surat.read.all\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:20:\"jenis-surat.read.own\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:6;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:22:\"jenis-surat.update.all\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:22:\"jenis-surat.update.own\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:6;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:22:\"jenis-surat.delete.all\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:22:\"jenis-surat.delete.own\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:5;i:2;i:6;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:20:\"counter-surat.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:18:\"counter-surat.read\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:20:\"counter-surat.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:20:\"counter-surat.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:12:\"surat.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:7:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:20:\"surat.draft.read.all\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:20:\"surat.draft.read.own\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:22:\"surat.dikirim.read.all\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:22:\"surat.dikirim.read.own\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:24:\"surat.disetujui.read.all\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:7;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:24:\"surat.disetujui.read.own\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:3;i:2;i:4;i:3;i:5;i:4;i:6;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:22:\"surat.ditolak.read.all\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:7;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:22:\"surat.ditolak.read.own\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:3;i:2;i:4;i:3;i:5;i:4;i:6;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:22:\"surat.expired.read.all\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:7;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:22:\"surat.expired.read.own\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:3;i:2;i:4;i:3;i:5;i:4;i:6;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:21:\"surat.review.read.all\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:26:\"surat.review.read.assigned\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:23:\"surat.review.update.all\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:28:\"surat.review.update.assigned\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:28:\"surat.delete.null-number.own\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:28:\"surat.delete.null-number.all\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:7;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:11:\"user.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:9:\"user.read\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:11:\"user.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:41;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:11:\"user.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:11:\"role.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:43;a:4:{s:1:\"a\";i:44;s:1:\"b\";s:9:\"role.read\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:44;a:4:{s:1:\"a\";i:45;s:1:\"b\";s:11:\"role.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:45;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:11:\"role.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:46;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:17:\"permission.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:47;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:15:\"permission.read\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:48;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:17:\"permission.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:49;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:17:\"permission.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:7:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:14:\"Kepala Sekolah\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:2:\"TU\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:7;s:1:\"b\";s:15:\"Pengelola Surat\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:6:\"Kaprog\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:20:\"Wakil Kepala Sekolah\";s:1:\"c\";s:3:\"web\";}i:6;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:4:\"Guru\";s:1:\"c\";s:3:\"web\";}}}', 1778504186);

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
-- Table structure for table `counter_surats`
--

CREATE TABLE `counter_surats` (
  `id` bigint UNSIGNED NOT NULL,
  `kategori_surat_id` bigint UNSIGNED DEFAULT NULL,
  `jenis_surat_id` bigint UNSIGNED DEFAULT NULL,
  `tahun` smallint UNSIGNED DEFAULT NULL,
  `last_number` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
-- Table structure for table `jenis_surats`
--

CREATE TABLE `jenis_surats` (
  `id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `kategori_surat_id` bigint UNSIGNED DEFAULT NULL,
  `kode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `template_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `requires_approval` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
-- Table structure for table `kategori_surats`
--

CREATE TABLE `kategori_surats` (
  `id` bigint UNSIGNED NOT NULL,
  `kode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori_surats`
--

INSERT INTO `kategori_surats` (`id`, `kode`, `nama`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '000', 'Umum', 1, '2026-05-10 12:55:44', '2026-05-10 12:55:44'),
(2, '421', 'Urusan Sekolah (Umum)', 1, '2026-05-10 12:55:44', '2026-05-10 12:55:44'),
(3, '421.3', 'Administrasi SMA', 1, '2026-05-10 12:55:44', '2026-05-10 12:55:44'),
(4, '421.5', 'Administrasi SMK', 1, '2026-05-10 12:55:44', '2026-05-10 12:55:44'),
(5, '421.6', 'Kegiatan Sekolah Kenaikan Kelas, Kelulusan, Dies Natalis', 1, '2026-05-10 12:55:44', '2026-05-10 12:55:44'),
(6, '421.7', 'Kegiatan Belajar Mengajar Kurikulum dan Kesiswaan', 1, '2026-05-10 12:55:44', '2026-05-10 12:55:44'),
(7, '422.7', 'Mutasi atau Keterangan Pindah Peserta Didik', 1, '2026-05-10 12:55:44', '2026-05-10 12:55:44'),
(8, '422.6', 'Penghargaan Prestasi dan Sertifikasi Siswa', 1, '2026-05-10 12:55:44', '2026-05-10 12:55:44'),
(9, '800', 'Urusan Kepegawaian Guru dan Tenaga Kependidikan (GTK)', 1, '2026-05-10 12:55:44', '2026-05-10 12:55:44'),
(10, '820', 'Mutasi, Pengangkatan, Perpindahan Tugas Pegawai', 1, '2026-05-10 12:55:44', '2026-05-10 12:55:44'),
(11, '830', 'Kedudukan Hukum Cuti dan Izin Belajar Guru', 1, '2026-05-10 12:55:44', '2026-05-10 12:55:44'),
(12, '900', 'Urusan Keuangan Sekolah Anggaran, Otorisasi, Verifikasi', 1, '2026-05-10 12:55:44', '2026-05-10 12:55:44'),
(13, '005', 'Surat Undangan Rapat Dinas atau Acara Sekolah', 1, '2026-05-10 12:55:44', '2026-05-10 12:55:44'),
(14, '045', 'Urusan Kearsipan Penataan Berkas Aktif dan Inaktif', 1, '2026-05-10 12:55:44', '2026-05-10 12:55:44'),
(15, '027', 'Pengadaan Barang dan Jasa Kontrak dan Inventarisasi', 1, '2026-05-10 12:55:44', '2026-05-10 12:55:44');

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
(4, '2026_04_03_160821_create_permission_tables', 1),
(5, '2026_04_03_231509_create_jenis_surats_table', 1),
(6, '2026_04_03_231512_create_counter_surats_table', 1),
(7, '2026_04_03_231514_create_surats_table', 1),
(8, '2026_04_03_231516_create_audit_logs_table', 1),
(9, '2026_04_03_231519_create_telegram_logs_table', 1),
(10, '2026_04_03_231521_add_telegram_chat_id_to_users_table', 1),
(11, '2026_04_03_233123_create_kategori_surats_table', 1),
(12, '2026_04_03_233126_add_kategori_surat_id_to_jenis_surats_table', 1),
(13, '2026_04_03_234821_add_kategori_surat_id_to_counter_surats_table', 1),
(14, '2026_04_03_235238_make_jenis_surat_id_nullable_on_counter_surats_table', 1),
(15, '2026_04_04_000001_create_sekolahs_table', 1),
(16, '2026_04_04_120100_make_tahun_nullable_on_counter_surats_table', 1),
(17, '2026_04_05_130000_add_created_by_to_jenis_surats_table', 1),
(18, '2026_04_06_000001_add_expired_at_to_surats_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 4),
(5, 'App\\Models\\User', 5),
(6, 'App\\Models\\User', 6),
(7, 'App\\Models\\User', 7);

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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'audit-log.read', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(2, 'telegram-log.read', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(3, 'sekolah.read', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(4, 'sekolah.update', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(5, 'kepala-sekolah.read', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(6, 'kepala-sekolah.update', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(7, 'kategori-surat.create', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(8, 'kategori-surat.read', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(9, 'kategori-surat.update', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(10, 'kategori-surat.delete', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(11, 'jenis-surat.create', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(12, 'jenis-surat.read.all', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(13, 'jenis-surat.read.own', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(14, 'jenis-surat.update.all', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(15, 'jenis-surat.update.own', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(16, 'jenis-surat.delete.all', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(17, 'jenis-surat.delete.own', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(18, 'counter-surat.create', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(19, 'counter-surat.read', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(20, 'counter-surat.update', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(21, 'counter-surat.delete', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(22, 'surat.create', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(23, 'surat.draft.read.all', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(24, 'surat.draft.read.own', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(25, 'surat.dikirim.read.all', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(26, 'surat.dikirim.read.own', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(27, 'surat.disetujui.read.all', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(28, 'surat.disetujui.read.own', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(29, 'surat.ditolak.read.all', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(30, 'surat.ditolak.read.own', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(31, 'surat.expired.read.all', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(32, 'surat.expired.read.own', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(33, 'surat.review.read.all', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(34, 'surat.review.read.assigned', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(35, 'surat.review.update.all', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(36, 'surat.review.update.assigned', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(37, 'surat.delete.null-number.own', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(38, 'surat.delete.null-number.all', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(39, 'user.create', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(40, 'user.read', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(41, 'user.update', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(42, 'user.delete', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(43, 'role.create', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(44, 'role.read', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(45, 'role.update', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(46, 'role.delete', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(47, 'permission.create', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(48, 'permission.read', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(49, 'permission.update', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(50, 'permission.delete', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(2, 'Kepala Sekolah', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(3, 'Guru', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(4, 'TU', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(5, 'Kaprog', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(6, 'Wakil Kepala Sekolah', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42'),
(7, 'Pengelola Surat', 'web', '2026-05-10 12:55:42', '2026-05-10 12:55:42');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(22, 2),
(24, 2),
(26, 2),
(27, 2),
(29, 2),
(31, 2),
(34, 2),
(36, 2),
(37, 2),
(22, 3),
(24, 3),
(26, 3),
(28, 3),
(30, 3),
(32, 3),
(37, 3),
(3, 4),
(4, 4),
(5, 4),
(6, 4),
(22, 4),
(24, 4),
(26, 4),
(28, 4),
(30, 4),
(32, 4),
(37, 4),
(11, 5),
(13, 5),
(15, 5),
(17, 5),
(22, 5),
(24, 5),
(26, 5),
(28, 5),
(30, 5),
(32, 5),
(37, 5),
(11, 6),
(13, 6),
(15, 6),
(17, 6),
(22, 6),
(24, 6),
(26, 6),
(28, 6),
(30, 6),
(32, 6),
(37, 6),
(7, 7),
(8, 7),
(9, 7),
(10, 7),
(11, 7),
(12, 7),
(14, 7),
(16, 7),
(18, 7),
(19, 7),
(20, 7),
(21, 7),
(22, 7),
(23, 7),
(25, 7),
(27, 7),
(29, 7),
(31, 7),
(33, 7),
(38, 7);

-- --------------------------------------------------------

--
-- Table structure for table `sekolahs`
--

CREATE TABLE `sekolahs` (
  `id` bigint UNSIGNED NOT NULL,
  `npsn` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nss` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_surat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_sekolah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visi_sekolah` text COLLATE utf8mb4_unicode_ci,
  `alamat_sekolah` text COLLATE utf8mb4_unicode_ci,
  `kota_kab` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provinsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kcd_wilayah` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telp` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kodepos` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `akreditasi` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_sekolah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_provinsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stamp_sekolah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `show_stamp` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sekolahs`
--

INSERT INTO `sekolahs` (`id`, `npsn`, `nss`, `kode_surat`, `nama_sekolah`, `visi_sekolah`, `alamat_sekolah`, `kota_kab`, `provinsi`, `kcd_wilayah`, `website`, `email`, `telp`, `kodepos`, `akreditasi`, `logo_sekolah`, `logo_provinsi`, `stamp_sekolah`, `show_stamp`, `created_at`, `updated_at`) VALUES
(1, '20241355', '321021809001', 'SMKN1-Krangkeng', 'SMKN 1 Krangkeng', 'Mencetak lulusan yang CENDEKIA (Cerdas, Normatif, Dedikatif, Kompeten, Iman dan Taqwa)', 'Jl. Raya Singakerta Kec. Krangkeng', 'Kab. Indramayu', 'Jawa Barat', 'IX', 'http://www.smkn1krangkeng.sch.id', 'admin@smkn1krangkeng.sch.id', '(0234) 7136113', '45284', 'A', NULL, NULL, NULL, 1, '2026-05-10 12:55:44', '2026-05-10 12:55:44');

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
('AgCNntJuYp8whKK8pSxvzAwnSJmeMEfECm6nETeT', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJZcEhFMk13UTg4am5EWWJpZUJYd2xKNWI2a2dLcEdHdkdYaHVuMTJMIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxLCJwYXNzd29yZF9oYXNoX3dlYiI6ImEzOTU2ZWIzZjgzNDJkYmU3YzIxNTYzM2FhZGEzYzRhNWU4MjA2OWY1NmU3YjlhY2U5YTRiNTg3ZTViYTY3YTQiLCJ0YWJsZXMiOnsiYTg4Nzg5NTA1ODQ5MGNjOGM4MDlhZDI2NGY4NTJlN2VfY29sdW1ucyI6W3sidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJub19zdXJhdCIsImxhYmVsIjoiTm8gc3VyYXQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiamVuaXNTdXJhdC5uYW1hIiwibGFiZWwiOiJKZW5pcyBTdXJhdCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJwZXJpaGFsIiwibGFiZWwiOiJQZXJpaGFsIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InBlbWJ1YXQubmFtZSIsImxhYmVsIjoiUGVtYnVhdCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJhcHByb3Zlci5uYW1lIiwibGFiZWwiOiJBcHByb3ZlciIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJzdGF0dXMiLCJsYWJlbCI6IlN0YXR1cyIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJ0YW5nZ2FsX3N1cmF0IiwibGFiZWwiOiJUYW5nZ2FsIHN1cmF0IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImNyZWF0ZWRfYXQiLCJsYWJlbCI6IkNyZWF0ZWQgYXQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiZXhwaXJlZF9hdCIsImxhYmVsIjoiRXhwaXJlZCBBdCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJ1cGRhdGVkX2F0IiwibGFiZWwiOiJVcGRhdGVkIGF0IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH1dLCJlZDg5M2IyNTFjM2NkMjBkM2QyOThlZmNjOWZmMmI4MV9jb2x1bW5zIjpbeyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6Im5vX3N1cmF0IiwibGFiZWwiOiJObyBzdXJhdCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJqZW5pc1N1cmF0Lm5hbWEiLCJsYWJlbCI6IkplbmlzIFN1cmF0IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InBlcmloYWwiLCJsYWJlbCI6IlBlcmloYWwiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoicGVtYnVhdC5uYW1lIiwibGFiZWwiOiJQZW1idWF0IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImFwcHJvdmVyLm5hbWUiLCJsYWJlbCI6IkFwcHJvdmVyIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InN0YXR1cyIsImxhYmVsIjoiU3RhdHVzIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InRhbmdnYWxfc3VyYXQiLCJsYWJlbCI6IlRhbmdnYWwgc3VyYXQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiY3JlYXRlZF9hdCIsImxhYmVsIjoiQ3JlYXRlZCBhdCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJleHBpcmVkX2F0IiwibGFiZWwiOiJFeHBpcmVkIEF0IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InVwZGF0ZWRfYXQiLCJsYWJlbCI6IlVwZGF0ZWQgYXQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfV0sImM5Njc3MzUwOWRlNWE3ZGQxOWIyNTg5ZTUxMjBlODgxX2NvbHVtbnMiOlt7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoibnBzbiIsImxhYmVsIjoiTnBzbiIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJuc3MiLCJsYWJlbCI6Ik5zcyIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJrb2RlX3N1cmF0IiwibGFiZWwiOiJLb2RlIFN1cmF0IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6Im5hbWFfc2Vrb2xhaCIsImxhYmVsIjoiTmFtYSBzZWtvbGFoIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImtvdGFfa2FiIiwibGFiZWwiOiJLb3RhXC9LYWJ1cGF0ZW4iLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoicHJvdmluc2kiLCJsYWJlbCI6IlByb3ZpbnNpIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImFrcmVkaXRhc2kiLCJsYWJlbCI6IkFrcmVkaXRhc2kiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoic2hvd19zdGFtcCIsImxhYmVsIjoiU2hvdyBTdGFtcCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJ1cGRhdGVkX2F0IiwibGFiZWwiOiJVcGRhdGVkIGF0IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH1dLCIxZWQ4ZTliNmUyNTFmZWFjNWI4OWM4NDZjZDQ4NGU2OV9jb2x1bW5zIjpbeyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImthdGVnb3JpU3VyYXQubmFtYSIsImxhYmVsIjoiS2F0ZWdvcmkiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoia29kZSIsImxhYmVsIjoiS29kZSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJuYW1hIiwibGFiZWwiOiJOYW1hIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InJlcXVpcmVzX2FwcHJvdmFsIiwibGFiZWwiOiJBcHByb3ZhbCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJpc19hY3RpdmUiLCJsYWJlbCI6IkFrdGlmIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InVwZGF0ZWRfYXQiLCJsYWJlbCI6IlVwZGF0ZWQgYXQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfV0sImM4ZmJkMjU4Yjk4YWU3YjUwYjFjNmI3NDA2YjIxMDgyX2NvbHVtbnMiOlt7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoia29kZSIsImxhYmVsIjoiS29kZSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJuYW1hIiwibGFiZWwiOiJOYW1hIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImlzX2FjdGl2ZSIsImxhYmVsIjoiQWt0aWYiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoidXBkYXRlZF9hdCIsImxhYmVsIjoiVXBkYXRlZCBhdCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9XX19', 1778417870);

-- --------------------------------------------------------

--
-- Table structure for table `surats`
--

CREATE TABLE `surats` (
  `id` bigint UNSIGNED NOT NULL,
  `jenis_surat_id` bigint UNSIGNED NOT NULL,
  `pembuat_id` bigint UNSIGNED NOT NULL,
  `approver_id` bigint UNSIGNED NOT NULL,
  `no_surat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `perihal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_surat` date DEFAULT NULL,
  `status` enum('draft','booked','menunggu_persetujuan','disetujui','ditolak','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `surat_file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `released_no_surat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verification_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rejection_note` text COLLATE utf8mb4_unicode_ci,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `telegram_logs`
--

CREATE TABLE `telegram_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `surat_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `chat_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','sent','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `retry_count` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `sent_at` timestamp NULL DEFAULT NULL,
  `failed_at` timestamp NULL DEFAULT NULL,
  `response_body` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pangkat_golongan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanda_tangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telegram_chat_id` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `nip`, `pangkat_golongan`, `telp`, `tanda_tangan`, `is_active`, `email`, `telegram_chat_id`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator eSurat', NULL, NULL, NULL, NULL, 1, 'admin@test.id', NULL, '2026-05-10 12:55:43', '$2y$12$lDfCsfWNWiOGKYnHkyzWROWd4bBpPokfJ7tJMVx.niYzMzf8w2uPC', NULL, '2026-05-10 12:55:43', '2026-05-10 12:55:43'),
(2, 'Febi Kuswandari', '451995891290054219', NULL, NULL, NULL, 1, 'kepsek@test.id', NULL, '2026-05-10 12:55:43', '$2y$12$YUTuO5GSC4M7rTbaFdnmK.QHBgjN8//xU6SkLugWkbCe9qxIjRxQy', 'Pt8u0ikKQB', '2026-05-10 12:55:43', '2026-05-10 12:55:43'),
(3, 'Hilda Andriani', NULL, NULL, NULL, NULL, 1, 'guru@test.id', NULL, '2026-05-10 12:55:43', '$2y$12$.3dPyhhAb8DGPvAoX1WbseiTQYRdthpJ3UfdPlLhjsAE1aNKSKM.K', 't4mT8nz4fL', '2026-05-10 12:55:43', '2026-05-10 12:55:43'),
(4, 'Salimah Hartati', NULL, NULL, NULL, NULL, 1, 'tu@test.id', NULL, '2026-05-10 12:55:43', '$2y$12$52aEOlLKhnwEh.2K1pj8xuDQzN90FxYyHBcTKWe5eK43qgdqsgY0e', 'gJoBZUGrqq', '2026-05-10 12:55:43', '2026-05-10 12:55:43'),
(5, 'Belinda Rahmawati', NULL, NULL, '0414 2804 7267', NULL, 1, 'kaprog@test.id', NULL, '2026-05-10 12:55:43', '$2y$12$eosoBMk8l0KR7Cu9UqmeOOsfU7pFpcF24msdiqbDJlvPjEhUKWlAG', 'NxlIH2r3gz', '2026-05-10 12:55:44', '2026-05-10 12:55:44'),
(6, 'Harja Pranowo', '942393299288649348', NULL, NULL, NULL, 1, 'wakasek@test.id', NULL, '2026-05-10 12:55:44', '$2y$12$x5sW4aWUNUbeawO6LPSzfuEhTf9NzQzrt0hHwPRjrJf0JTj1n2WPW', 'uZ9RkrhRIZ', '2026-05-10 12:55:44', '2026-05-10 12:55:44'),
(7, 'Michelle Lailasari', NULL, NULL, '(+62) 872 7965 6066', NULL, 1, 'pengelola@test.id', NULL, '2026-05-10 12:55:44', '$2y$12$3Xz0mM4jqxxEDQO0xefVg.jv2w7TsEml6kFummK9wygJp1.jUVLVS', 'L5mUYQpcJp', '2026-05-10 12:55:44', '2026-05-10 12:55:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_surat_id_foreign` (`surat_id`),
  ADD KEY `audit_logs_user_id_foreign` (`user_id`);

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
-- Indexes for table `counter_surats`
--
ALTER TABLE `counter_surats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `counter_surats_jenis_surat_id_tahun_unique` (`jenis_surat_id`,`tahun`),
  ADD UNIQUE KEY `counter_surats_kategori_surat_id_tahun_unique` (`kategori_surat_id`,`tahun`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jenis_surats`
--
ALTER TABLE `jenis_surats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jenis_surats_kode_unique` (`kode`),
  ADD KEY `jenis_surats_kategori_surat_id_foreign` (`kategori_surat_id`),
  ADD KEY `jenis_surats_created_by_foreign` (`created_by`);

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
-- Indexes for table `kategori_surats`
--
ALTER TABLE `kategori_surats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kategori_surats_kode_unique` (`kode`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sekolahs`
--
ALTER TABLE `sekolahs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sekolahs_npsn_unique` (`npsn`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `surats`
--
ALTER TABLE `surats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `surats_no_surat_unique` (`no_surat`),
  ADD UNIQUE KEY `surats_released_no_surat_unique` (`released_no_surat`),
  ADD UNIQUE KEY `surats_verification_token_unique` (`verification_token`),
  ADD KEY `surats_jenis_surat_id_foreign` (`jenis_surat_id`),
  ADD KEY `surats_pembuat_id_foreign` (`pembuat_id`),
  ADD KEY `surats_approver_id_foreign` (`approver_id`),
  ADD KEY `surats_expired_at_index` (`expired_at`);

--
-- Indexes for table `telegram_logs`
--
ALTER TABLE `telegram_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `telegram_logs_surat_id_foreign` (`surat_id`),
  ADD KEY `telegram_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_nip_unique` (`nip`),
  ADD UNIQUE KEY `users_telegram_chat_id_unique` (`telegram_chat_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `counter_surats`
--
ALTER TABLE `counter_surats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_surats`
--
ALTER TABLE `jenis_surats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori_surats`
--
ALTER TABLE `kategori_surats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sekolahs`
--
ALTER TABLE `sekolahs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `surats`
--
ALTER TABLE `surats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `telegram_logs`
--
ALTER TABLE `telegram_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_surat_id_foreign` FOREIGN KEY (`surat_id`) REFERENCES `surats` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `counter_surats`
--
ALTER TABLE `counter_surats`
  ADD CONSTRAINT `counter_surats_jenis_surat_id_foreign` FOREIGN KEY (`jenis_surat_id`) REFERENCES `jenis_surats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `counter_surats_kategori_surat_id_foreign` FOREIGN KEY (`kategori_surat_id`) REFERENCES `kategori_surats` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `jenis_surats`
--
ALTER TABLE `jenis_surats`
  ADD CONSTRAINT `jenis_surats_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `jenis_surats_kategori_surat_id_foreign` FOREIGN KEY (`kategori_surat_id`) REFERENCES `kategori_surats` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `surats`
--
ALTER TABLE `surats`
  ADD CONSTRAINT `surats_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `surats_jenis_surat_id_foreign` FOREIGN KEY (`jenis_surat_id`) REFERENCES `jenis_surats` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `surats_pembuat_id_foreign` FOREIGN KEY (`pembuat_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `telegram_logs`
--
ALTER TABLE `telegram_logs`
  ADD CONSTRAINT `telegram_logs_surat_id_foreign` FOREIGN KEY (`surat_id`) REFERENCES `surats` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `telegram_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
