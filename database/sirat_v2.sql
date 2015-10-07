-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Inang: 127.0.0.1
-- Waktu pembuatan: 07 Okt 2015 pada 22.30
-- Versi Server: 5.5.34
-- Versi PHP: 5.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Basis data: `sirat_v2`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `email`
--

CREATE TABLE IF NOT EXISTS `email` (
  `no` int(4) NOT NULL,
  `subyek` varchar(100) NOT NULL,
  `attacment` text NOT NULL,
  `isi` varchar(255) NOT NULL,
  `id_surat` int(4) NOT NULL,
  `penerima` int(4) NOT NULL,
  PRIMARY KEY (`no`),
  KEY `penerima` (`penerima`),
  KEY `id_surat` (`id_surat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `gcm_users`
--

CREATE TABLE IF NOT EXISTS `gcm_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gcm_regid` text NOT NULL,
  `account` varchar(255) NOT NULL,
  `id_institusi` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_institusi` (`id_institusi`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=72 ;

--
-- Dumping data untuk tabel `gcm_users`
--

INSERT INTO `gcm_users` (`id`, `gcm_regid`, `account`, `id_institusi`, `created_at`) VALUES
(71, 'APA91bHBCWBjYWAQrwTVHeNN2Uhr_gjopSns5XukJw3x_qTvUOfnGVoxf0jOfJsHZfHL0hDyUThsWO6r-4BiCu2PFt5HWKWT23gSzUEilymYyYPFUm61D8oM3RpDoyyQS_Ys5HiVnCX5', 'hamidillah_ajie', 0, '2015-09-19 07:43:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `instansi`
--

CREATE TABLE IF NOT EXISTS `instansi` (
  `id_instansi` varchar(9) NOT NULL,
  `nama_instansi` varchar(255) NOT NULL,
  PRIMARY KEY (`id_instansi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `instansi`
--

INSERT INTO `instansi` (`id_instansi`, `nama_instansi`) VALUES
('000', 'None'),
('001', 'Rektorat'),
('002', 'Fakultas Teknik'),
('003', 'Unit Pelayanan Teknis');

-- --------------------------------------------------------

--
-- Struktur dari tabel `institusi`
--

CREATE TABLE IF NOT EXISTS `institusi` (
  `id_institusi` varchar(6) NOT NULL,
  `nama_institusi` varchar(255) NOT NULL,
  `id_instansi` varchar(6) NOT NULL,
  PRIMARY KEY (`id_institusi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `institusi`
--

INSERT INTO `institusi` (`id_institusi`, `nama_institusi`, `id_instansi`) VALUES
('000000', 'None', '000'),
('001000', 'Rektor', '001'),
('001001', 'WR 1', '001'),
('002000', 'Teknik', '002'),
('002001', 'Mesin', '002'),
('002002', 'Sipil', '002'),
('002003', 'IKK', '002'),
('002004', 'Tata Busana', '002'),
('002005', 'lemlit', '002'),
('003000', 'PUSTIKOM', '003'),
('003001', 'Perpustakaan', '003'),
('003002', 'OIE', '003'),
('003003', 'Lemlit', '003');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatan`
--

CREATE TABLE IF NOT EXISTS `jabatan` (
  `id_jabatan` varchar(9) NOT NULL,
  `id_institusi` varchar(9) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  PRIMARY KEY (`id_jabatan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `id_institusi`, `jabatan`) VALUES
('000000000', '000000', 'None'),
('001000000', '001000', 'Rektor'),
('001001000', '001001', 'WR 1'),
('002000000', '002000', 'Kepala Jurusan Teknik Elektro'),
('002000001', '002000', 'Operator Jurusan Teknik Elektro'),
('002001000', '002001', 'Kepala Fakultas Teknik'),
('003000001', '003000', 'Sekretaris Umum Pustikom'),
('003000002', '003000', 'Kabid. Sistem Informasi Pustikom'),
('003000003', '003000', 'Operator Pustikom'),
('003002000', '003002', 'operator_oie'),
('18', '003000', 'Kepala UPT Pusat Komputer');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_kirim`
--

CREATE TABLE IF NOT EXISTS `log_kirim` (
  `no` int(4) NOT NULL AUTO_INCREMENT,
  `id_surat` varchar(10) NOT NULL,
  `jenis_penerima` varchar(255) NOT NULL COMMENT 'SMS, ANDRO, EMAIL',
  `tujuan` varchar(255) NOT NULL,
  `waktu_kirim` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_respond`
--

CREATE TABLE IF NOT EXISTS `log_respond` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `pesan_respond` varchar(255) NOT NULL,
  `waktu` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `operator`
--

CREATE TABLE IF NOT EXISTS `operator` (
  `id_operator` int(4) NOT NULL AUTO_INCREMENT,
  `account_op` varchar(255) NOT NULL,
  `pass_op` varchar(255) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `id_institusi` int(4) NOT NULL,
  `alamat_kantor` varchar(50) NOT NULL,
  PRIMARY KEY (`id_operator`),
  KEY `id_institusi` (`id_institusi`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data untuk tabel `operator`
--

INSERT INTO `operator` (`id_operator`, `account_op`, `pass_op`, `nama`, `email`, `id_institusi`, `alamat_kantor`) VALUES
(1, 'operator', '1234', 'Firdaus', 'firdausibnuu@gmail.com', 3000, 'PUSTIKOM GEDUNG D'),
(2, 'operator2', '1234', 'Pak Joni', 'pak_joni@gmail.com', 2000, 'TEKNIK ELEKTRO L2');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pejabat`
--

CREATE TABLE IF NOT EXISTS `pejabat` (
  `id_pejabat` int(4) NOT NULL AUTO_INCREMENT,
  `account` varchar(255) NOT NULL,
  `id_jabatan` varchar(9) NOT NULL,
  PRIMARY KEY (`id_pejabat`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data untuk tabel `pejabat`
--

INSERT INTO `pejabat` (`id_pejabat`, `account`, `id_jabatan`) VALUES
(3, 'hamidillah_adjie', '003000001'),
(4, 'ficky_duskarnaen', '18'),
(5, 'firdaus_ibnu', '003000003'),
(6, 'wisnu_dj', '002000000'),
(7, 'prasetyo_wy', '002000003');

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat`
--

CREATE TABLE IF NOT EXISTS `surat` (
  `id_surat` int(4) NOT NULL AUTO_INCREMENT,
  `subject_surat` varchar(250) NOT NULL,
  `nama_surat` varchar(255) NOT NULL,
  `no_surat` varchar(30) NOT NULL,
  `jenis` varchar(30) NOT NULL,
  `hal` varchar(50) NOT NULL,
  `isi` longtext NOT NULL,
  `kode_hal` varchar(4) NOT NULL,
  `kode_lembaga_pengirim` varchar(10) NOT NULL,
  `penandatangan` varchar(30) NOT NULL,
  `tujuan` longtext NOT NULL,
  `lampiran` int(4) NOT NULL,
  `tembusan` longtext NOT NULL,
  `tanggal_surat` date NOT NULL,
  `file_surat` longblob NOT NULL,
  `file_lampiran` varchar(30) NOT NULL,
  `id_operator` int(4) NOT NULL,
  `pesan_android` varchar(160) NOT NULL,
  `pesan_email` varchar(160) NOT NULL,
  `pesan_sms` varchar(160) NOT NULL,
  `ditandatangani` tinyint(4) NOT NULL,
  `is_uploaded` varchar(5) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_surat`),
  KEY `kode_unit` (`file_lampiran`),
  KEY `pembuat` (`lampiran`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=117 ;

--
-- Dumping data untuk tabel `surat`
--

INSERT INTO `surat` (`id_surat`, `subject_surat`, `nama_surat`, `no_surat`, `jenis`, `hal`, `isi`, `kode_hal`, `kode_lembaga_pengirim`, `penandatangan`, `tujuan`, `lampiran`, `tembusan`, `tanggal_surat`, `file_surat`, `file_lampiran`, `id_operator`, `pesan_android`, `pesan_email`, `pesan_sms`, `ditandatangani`, `is_uploaded`, `created`) VALUES
(35, 'Test Subject', '', '35/UN39.18/AK/15', '', '', '', 'AK', '003000', '003000003', 'pak_joni@+id/', 1, 'pak_joni', '2015-10-04', '', '', 0, '', '', '', 0, 'true', '2015-10-04 14:57:15'),
(36, 'Test Subject', '', '36/UN39.18/AK/15', '', '', '', 'AK', '003000', '003000003', 'pak_joni@+id/', 1, 'pak_joni', '2015-10-04', '', '', 0, '', '', '', 0, 'true', '2015-10-04 14:57:47'),
(68, 'DAUS', '', '999/UN.39.18/TU/15', '', '', 'TES', 'KP', '003000', '003000001', 'hamidillah_ajie@+id/', 2, 'hamidillah_ajie', '2015-09-14', '', '', 0, '', '', '', 1, '', '2015-09-19 07:27:45'),
(69, 'Test Subject', '', '999/UN.39.18/TU/15', '', '', 'asdf', 'KP', '003000', '003000000', 'hamidillah_ajie@+id/', 2, '002000000', '2015-09-16', '', '', 0, '', '', '', 0, '', '2015-09-16 17:00:14'),
(70, 'Test Subject', '', '999/UN.39.18/TU/15', '', '', 'asdf', 'KP', '003000', '003000000', 'hamidillah_ajie@+id/', 2, '002000000', '2015-09-16', '', '', 0, '', '', '', 0, '', '2015-09-16 17:00:14'),
(71, 'Test Subject', '', '999/UN.39.18/TU/15', '', '', 'asdf', 'KP', '003000', '003000000', 'hamidillah_ajie@+id/', 2, '002000000', '2015-09-16', '', '', 0, '', '', '', 0, '', '2015-09-16 17:00:16'),
(72, 'Test Subject', '', '999/UN.39.18/TU/15', '', '', 'aa', 'KP', '003000', '003000000', 'wisnu_dj@+id/', 2, '', '2015-09-17', '', '', 0, '', '', '', 0, '', '2015-09-19 08:17:22'),
(73, 'Test Subject', '', '999/UN.39.18/TU/15', '', '', 'asdf', 'KP', '003000', '003000002', 'hamidillah_ajie@+id/', 2, 'ficky_duskarnaen', '2015-09-17', '', '', 0, '', '', '', 0, '', '2015-09-19 08:17:29'),
(74, 'Test Subject', '', '999/UN.39.18/TU/15', '', '', 'tanpa tembusan', 'KP', '003000', '003000002', 'hamidillah_ajie@+id/', 2, '', '2015-09-17', '', '', 0, '', '', '', 0, '', '2015-09-19 08:17:36'),
(75, 'Test Subject', '', '999/UN.39.18/TU/15', '', '', 'a', 'KP', '003000', '003000000', 'hamidillah_ajie@+id/', 2, 'wisnu_dj', '2015-09-17', '', '', 0, '', '', '', 0, '', '2015-09-19 08:17:42'),
(80, 'Test Subject', '', '2/UN39.18/KR/15', '', '', 'test baru', 'KR', '003000', '18', 'hamidillah_ajie@+id/', 2, 'ficky_duskarnaen', '2015-09-17', '', '', 0, '', '', '', 0, '', '2015-09-19 08:18:19'),
(83, 'Undangan Rapat', '', '5/UN39.18/KM/15', '', '', '<p>TEST SURAT DEMO</p>', 'KM', '003000', '003000001', 'wisnu_dj@+id/', 2, 'prasetyo_wp@+id/ficky_duskarnaen', '2015-09-24', '', '', 0, '', '', '', 1, '', '2015-09-19 10:17:43'),
(85, 'Test Subject', '', '7/UN39.18/AK/15', '', '', '<p>TEST MALEM ~</p>', 'AK', '003000', '003000001', 'ficky_duskarnaen@+id/', 2, '002000000', '2015-09-19', '', '', 0, '', '', '', 2, '', '2015-09-19 16:35:17'),
(86, 'Test Subject', '', '8/UN39.18/AK/15', '', '', '<p>tes</p>', 'AK', '003000', '18', 'hamidillah_ajie@+id/', 3, '', '2015-09-26', '', '', 0, '', '', '', 0, '', '2015-09-26 16:50:31'),
(87, 'Test Subject', '', '9/UN39.18/BH/15', '', '', '<p>asad</p>', 'BH', '003000', '18', 'hamidillah_ajie@+id/', 2, '', '2015-09-27', '', '', 0, '', '', '', 0, '', '2015-09-26 17:51:16'),
(88, 'Test Subject', '', '22/UN39.18/AK/15', '', '', '<p>asdfdf</p>', 'AK', '003000', '18', 'hamidillah_ajie@+id/', 1, 'hamidillah_ajie', '2015-10-04', '', '', 0, '', '', '', 0, 'tr', '2015-10-04 14:39:49'),
(89, 'Test Subject', '', '23/UN39.18/AK/15', '', '', '<p>asdfdf</p>', 'AK', '003000', '18', 'hamidillah_ajie@+id/', 2, 'hamidillah_ajie', '2015-10-04', '', '', 0, '', '', '', 0, 'tr', '2015-10-04 14:40:36'),
(90, 'Test Subject', '', '24/UN39.18/AK/15', '', '', '<p>asdfdf</p>', 'AK', '003000', '18', 'hamidillah_ajie@+id/', 2, 'hamidillah_ajie', '2015-10-04', '', '', 0, '', '', '', 0, 'tr', '2015-10-04 14:41:44'),
(91, 'Test Subject', '', '25/UN39.18/AK/15', '', '', '<p>asdfdf</p>', 'AK', '003000', '18', 'hamidillah_ajie@+id/', 2, 'hamidillah_ajie', '2015-10-04', '', '', 0, '', '', '', 0, 'tr', '2015-10-04 14:41:58'),
(92, 'Test Subject', '', '26/UN39.18/AK/15', '', '', '<p>asdfdf</p>', 'AK', '003000', '18', 'hamidillah_ajie@+id/', 2, 'hamidillah_ajie', '2015-10-04', '', '', 0, '', '', '', 0, 'tr', '2015-10-04 14:43:31'),
(93, 'Test Subject', '', '27/UN39.18/AK/15', '', '', '<p>asdfdf</p>', 'AK', '003000', '18', 'hamidillah_ajie@+id/', 3, 'hamidillah_ajie', '2015-10-04', '', '', 0, '', '', '', 0, 'tr', '2015-10-04 14:44:28'),
(94, 'Test Subject', '', '28/UN39.18/AK/15', '', '', '<p>asdfdf</p>', 'AK', '003000', '18', 'hamidillah_ajie@+id/', 3, 'hamidillah_ajie', '2015-10-04', '', '', 0, '', '', '', 0, 'tr', '2015-10-04 14:45:40'),
(95, 'Test Subject', '', '29/UN39.18/AK/15', '', '', '', 'AK', '003000', '003000003', 'pak_joni@+id/', 1, 'pak_joni', '2015-10-04', '', '', 0, '', '', '', 0, 'tr', '2015-10-04 14:46:16'),
(96, 'Test Subject', '', '30/UN39.18/AK/15', '', '', '', 'AK', '003000', '003000003', 'pak_joni@+id/', 2, 'pak_joni', '2015-10-04', '', '', 0, '', '', '', 0, 'tr', '2015-10-04 14:46:39'),
(97, 'Test Subject', '', '31/UN39.18/AK/15', '', '', '', 'AK', '003000', '003000003', 'hamidillah_ajie@+id/', 2, 'pak_joni', '2015-10-04', '', '', 0, '', '', '', 0, 'tr', '2015-10-04 14:47:48'),
(98, 'Test Subject', '', '32/UN39.18/AK/15', '', '', '', 'AK', '003000', '003000003', 'hamidillah_ajie@+id/', 3, 'pak_joni', '2015-10-04', '', '', 0, '', '', '', 0, 'tr', '2015-10-04 14:48:10'),
(99, 'Test Subject', '', '33/UN39.18/AK/15', '', '', '', 'AK', '003000', '003000003', 'pak_joni@+id/', 1, 'pak_joni', '2015-10-04', '', '', 0, '', '', '', 0, 'tr', '2015-10-04 14:51:56'),
(100, 'Test Subject', '', '34/UN39.18/AK/15', '', '', '', 'AK', '003000', '003000003', 'pak_joni@+id/', 1, 'pak_joni', '2015-10-04', '', '', 0, '', '', '', 0, 'true', '2015-10-04 14:53:07'),
(101, 'Test Subject', '', '37/UN39.18/AK/15', '', '', '', 'AK', '003000', '003000003', 'pak_joni@+id/', 1, 'pak_joni', '2015-10-04', '', '', 0, '', '', '', 0, 'true', '2015-10-04 15:04:31'),
(102, 'Test Subject', '', '38/UN39.18/AK/15', '', '', '', 'AK', '003000', '003000003', 'pak_joni@+id/', 1, 'pak_joni', '2015-10-04', '', '', 0, '', '', '', 0, 'true', '2015-10-04 15:05:01'),
(103, 'Test Subject', '', '39/UN39.18/AK/15', '', '', '', 'AK', '003000', '003000003', 'pak_joni@+id/', 1, 'pak_joni', '2015-10-04', '', '', 0, '', '', '', 0, 'true', '2015-10-04 15:05:45'),
(104, 'Test Subject', '', '40/UN39.18/AK/15', '', '', '', 'AK', '003000', '003000003', 'pak_joni@+id/', 3, 'pak_joni', '2015-10-04', '', '', 0, '', '', '', 0, 'true', '2015-10-04 15:06:32'),
(105, 'Test Subject', '', '41/UN39.18/AK/15', '', '', '', 'AK', '003000', '003000003', 'pak_joni@+id/', 2, 'pak_joni', '2015-10-04', '', '', 0, '', '', '', 0, 'true', '2015-10-04 15:07:57'),
(106, 'Test Subject', '', '42/UN39.18/AK/15', '', '', '', 'AK', '003000', '003000003', 'pak_joni@+id/', 2, 'pak_joni', '2015-10-04', '', '', 0, '', '', '', 0, 'true', '2015-10-04 15:12:42'),
(107, 'Test Subject', '', '43/UN39.18/AK/15', '', '', '', 'AK', '003000', '003000003', 'pak_joni@+id/', 2, 'pak_joni', '2015-10-04', '', '', 0, '', '', '', 0, 'true', '2015-10-04 15:13:39'),
(108, 'Test Subject', '', '44/UN39.18/AK/15', '', '', '<p>asdfasd</p>', 'AK', '003000', '003000001', 'hamidillah_ajie@+id/', 1, 'wisnu_dj', '2015-10-07', '', '', 0, '', '', '', 0, 'false', '2015-10-07 07:33:18'),
(109, 'TES SORE', '', '45/UN39.18/DK/15', '', '', '<p>Tes Surat Sore</p><p>aaaaaaaaa</p><p>aaaaaaaaaaaaaaaaaa</p><p>aaaaaaaaaaaaaaaaaaaaaaaaa</p>', 'DK', '003000', '18', '18@+id/', 0, '', '2015-10-08', '', '', 0, '', '', '', 1, 'false', '2015-10-07 10:42:38'),
(110, 'TES SORE', '', '46/UN39.18/BS/15', '', '', '<p>Tes Surat Sore</p><p>aaaaaaaaa</p><p>aaaaaaaaaaaaaaaaaa</p><p>aaaaaaaaaaaaaaaaaaaaaaaaa</p>', 'BS', '003000', '18', 'hamidillah_ajie@+id/', 1, 'pak_joni', '2015-10-08', '', '', 0, '', '', '', 1, 'false', '2015-10-07 10:48:26'),
(111, 'TES SORE', '', '47/UN39.18/BS/15', '', '', '<p>Tes Surat Sore</p><p>aaaaaaaaa</p><p>aaaaaaaaaaaaaaaaaa</p><p>aaaaaaaaaaaaaaaaaaaaaaaaa</p>', 'BS', '003000', '18', 'hamidillah_ajie@+id/', 1, 'pak_joni', '2015-10-08', '', '', 0, '', '', '', 0, 'false', '2015-10-07 11:02:37'),
(112, 'TES SORE', '', '48/UN39.18/BS/15', '', '', '<p>Tes Surat Sore</p><p>aaaaaaaaa</p><p>aaaaaaaaaaaaaaaaaa</p><p>aaaaaaaaaaaaaaaaaaaaaaaaa</p>', 'BS', '003000', '18', 'hamidillah_ajie@+id/', 1, 'pak_joni', '2015-10-08', '', '', 0, '', '', '', 0, 'false', '2015-10-07 11:02:46'),
(113, 'TES SORE', '', '49/UN39.18/BS/15', '', '', '<p>nggak ada lampiran</p>', 'BS', '003000', '18', 'hamidillah_ajie@+id/', 0, 'pak_joni', '2015-10-08', '', '', 0, '', '', '', 0, 'false', '2015-10-07 11:08:10'),
(114, 'TES PAKE LAMPIRAN', '', '50/UN39.18/BS/15', '', '', '<p>kalo sekarang, ada lampiran</p>', 'BS', '003000', '18', 'hamidillah_ajie@+id/', 1, 'pak_joni', '2015-10-08', '', '', 0, '', '', '', 0, 'false', '2015-10-07 11:10:51'),
(115, 'TES PAKE LAMPIRAN', '', '51/UN39.18/BS/15', '', '', '<p>kalo sekarang, ada lampiran</p>', 'BS', '003000', '18', 'hamidillah_ajie@+id/', 1, 'pak_joni', '2015-10-08', '', '', 0, '', '', '', 0, 'false', '2015-10-07 11:16:40'),
(116, 'PERMOHONAN MAAF', '', '52/UN39.18/AK/15', '', '', '<p><b>Tes</b> Surat Mohon Maaf</p>', 'AK', '003000', '18', 'hamidillah_ajie@+id/', 1, '', '2015-10-07', '', '', 0, '', '', '', 1, 'false', '2015-10-07 12:19:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat_counter`
--

CREATE TABLE IF NOT EXISTS `surat_counter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_institusi` varchar(6) NOT NULL,
  `counter` int(11) NOT NULL,
  `year` varchar(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data untuk tabel `surat_counter`
--

INSERT INTO `surat_counter` (`id`, `id_institusi`, `counter`, `year`) VALUES
(3, '003000', 52, '2015');

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat_isi`
--

CREATE TABLE IF NOT EXISTS `surat_isi` (
  `id_surat` varchar(10) NOT NULL,
  `no_surat` varchar(30) NOT NULL,
  `lampiran` int(4) NOT NULL,
  `hal` varchar(50) NOT NULL,
  `isi` longtext NOT NULL,
  `nama_jabatan` varchar(50) NOT NULL,
  `nama_penjabat` varchar(50) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `tembusan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `surat_isi`
--

INSERT INTO `surat_isi` (`id_surat`, `no_surat`, `lampiran`, `hal`, `isi`, `nama_jabatan`, `nama_penjabat`, `nip`, `tembusan`) VALUES
('3', '99/999/999/999', 4, 'Permohonan Surat', '<p>Dengan Hormat,</p>\r\n<p>Bersama ini kami mengajukan proposal pengembangan TIK universitas dengan tema proposal\r\nPengembangan dan Revitalisasi Sistem dan TIK universitas untuk meningkatkan pelayanan\r\nTridarma di Universitas Negeri Jakarta.</p>\r\n<p>Kegiatan Tersebut ditujukan untuk membangun sistem TIK di universitas negeri jakarta yang\r\nditunjukan untuk meningkatkan pelayanan Tridama bagi Seluruh pemangku kepentingan di\r\nUniversitas Negeri Jakarta.</p>\r\n<p>Demikian pengajuan proposal pengembangan TIK Universitas ini kami sampaikan, atas\r\nperhatiannya kami ucapkan terima kasih.</p>\r\n', 'Ka. Pustikom', 'M. Ficky Duskarnaen', '197408242005011', ''),
('3', '99/999/999/999', 4, 'Permohonan Surat', '<p>Dengan Hormat,</p>\r\n<p>Bersama ini kami mengajukan proposal pengembangan TIK universitas dengan tema proposal\r\nPengembangan dan Revitalisasi Sistem dan TIK universitas untuk meningkatkan pelayanan\r\nTridarma di Universitas Negeri Jakarta.</p>\r\n<p>Kegiatan Tersebut ditujukan untuk membangun sistem TIK di universitas negeri jakarta yang\r\nditunjukan untuk meningkatkan pelayanan Tridama bagi Seluruh pemangku kepentingan di\r\nUniversitas Negeri Jakarta.</p>\r\n<p>Demikian pengajuan proposal pengembangan TIK Universitas ini kami sampaikan, atas\r\nperhatiannya kami ucapkan terima kasih.</p>\r\n', 'Ka. Pustikom', 'M. Ficky Duskarnaen', '197408242005011001', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat_kode_hal`
--

CREATE TABLE IF NOT EXISTS `surat_kode_hal` (
  `kode_hal` varchar(10) NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  PRIMARY KEY (`kode_hal`),
  KEY `kode_hal` (`kode_hal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `surat_kode_hal`
--

INSERT INTO `surat_kode_hal` (`kode_hal`, `deskripsi`) VALUES
('AJ', 'Analisis Jabatan'),
('AK', 'Akreditasi'),
('BH', 'Bantuan Hukum'),
('BS', 'Kebahasaan'),
('DK', 'Pendidikan Khusus'),
('DL', 'Pendidikan dan Pelatihan'),
('DM', 'Pendidikan Menengah'),
('DN', 'Kerja Sama Dalam Negeri'),
('DO', 'Dokumentasi'),
('DR', 'Pendidikan Kesetaraan'),
('DS', 'Pendidikan Dasar'),
('DT', 'Pendidikan Tinggi'),
('DU', 'Pendidikan Anak Usia Dini'),
('EP', 'Evaluasi Pendidikan'),
('HK', 'Hukum'),
('HM', 'Hubungan Masyarakat'),
('KL', 'Kelembagaan'),
('KM', 'Kemahasiswaan'),
('KP', 'Kepegawaian'),
('KR', 'Kurikulum'),
('KS', 'Kursus'),
('KU', 'Keuangan'),
('LK', 'Perlengkapan'),
('LL', 'Lain-lain'),
('LN', 'Kerja Sama Luar Negeri'),
('LT', 'Penelitian'),
('MI', 'Media Informasi'),
('MK', 'Media Kreatif'),
('MS', 'Pendidikan Masyarakat'),
('OT', 'Organisasi dan Tata Kerja'),
('PB', 'Perbukuan'),
('PD', 'Peserta Didik'),
('PG', 'Pengembangan'),
('PK', 'Perpustakaan'),
('PL', 'Pelaporan'),
('PM', 'Pengabdian kepada Masyarakat'),
('PP', 'Peraturan Perundang-undangan'),
('PR', 'Perencanaan'),
('PT', 'Pendidikan dan Tenaga Kependidikan'),
('RT', 'Kerumahtanggaan'),
('SP', 'Sarana Pendidikan'),
('TI', 'Teknologi Informasi'),
('TL', 'Tata Laksana'),
('TU', 'Ketatausahaan'),
('WS', 'Pengawasan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat_kode_unit`
--

CREATE TABLE IF NOT EXISTS `surat_kode_unit` (
  `kode_unit` varchar(10) NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `id_institusi` varchar(6) NOT NULL,
  PRIMARY KEY (`kode_unit`),
  KEY `kode_unit` (`kode_unit`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `surat_kode_unit`
--

INSERT INTO `surat_kode_unit` (`kode_unit`, `deskripsi`, `id_institusi`) VALUES
('1', 'Pembantu Rektor I', ''),
('10', 'Ketua Lembaga Pengabdian Kepada Masyarakat (LPM)', ''),
('11', 'Kepala Biro Administrasi Umum dan Keuangan (BAUK)', ''),
('11.1', 'Kepala Bagian UHT dan Perlengkapan BAUK', ''),
('11.2', 'Kepala Bagian Kepegawaian BAUK', ''),
('11.3', 'Kepala Bagian Keuangan BAUK', ''),
('12', 'Kepala Biro Administrasi Akademik dan Kemahasiswaan (BAAK)', ''),
('12.1', 'Kepala Bagian Akademik (BAAK)', ''),
('12.2', 'Kabag Kemahasiswaan (BAAK)', ''),
('13', 'Kepala Biro Administrasi dan Perencanaan dan Sistem Informasi (BAPSI)', ''),
('13.1', 'Kepala Bagian Perencanaan BAPSI', ''),
('13.2', 'Kepala Bagian Sistem Informasi BAPSI', ''),
('14', 'Kepala UPT Perpustakaan', ''),
('15', 'Kepala UPT Program Pengalaman Lapangan (PPL)', ''),
('16', 'Kepala UPT Pusat Sumber Belajar (PSB)', ''),
('17', 'Kepala UPT Pelayanan Bahasa', ''),
('18', 'Kepala UPT Pusat Komputer (PUSKOM)', '003000'),
('19', 'Kepala UPT Pembinaan dan Pengembangan Pendidikan P2P', ''),
('2', 'Pembantu Rektor II', ''),
('20', 'Kepala UPT Hubungan Masyarakat (HUMAS)', ''),
('21', 'Kepala UPT Keamanan Ketertiban Keindahan dan Perpakiran Kampus (K3P)', ''),
('22', 'Kepala UPT Urusan Pendidikan International (Office of International Education)', ''),
('23', 'Ketua Lembaga Pengembangan Pendidikan (LPP)', ''),
('24', 'Ketua Lembaga Penjaminan Mutu (LPjM)', ''),
('25', 'Ketua Unit Layanan Bimbingan Konseling (ULBK)', ''),
('26', 'Kepala Poliklinik', ''),
('27', 'Direktur Pusat Pengembangan Teknologi Informasi (PPTI)', ''),
('3', 'Pembantu Rektor III', ''),
('4', 'Pembantu Rektor IV', ''),
('5.FBS', 'Dekan FBS', ''),
('5.FE', 'Dekan FE', ''),
('5.FIK', 'Dekan FIK', ''),
('5.FIP', 'Dekan FIP', ''),
('5.FIS', 'Dekan FIS', ''),
('5.FMIPA', 'Dekan FMIPA', ''),
('5.FT', 'Dekan FT', ''),
('5.PPs', 'Direktur PPs', ''),
('6.FBS', 'Pembantu Dekan I FBS', ''),
('6.FE', 'Pembantu Dekan I FE', ''),
('6.FIK', 'Pembantu Dekan I FIK', ''),
('6.FIP', 'Pembantu Dekan I FIP', ''),
('6.FIS', 'Pembantu Dekan I FIS', ''),
('6.FMIPA', 'Pembantu Dekan I FMIPA', ''),
('6.FT', 'Pembantu Dekan I FT', ''),
('6.PPs', 'Asdir I PPs', ''),
('7.FBS', 'Pembantu Dekan II FBS', ''),
('7.FE', 'Pembantu Dekan II FE', ''),
('7.FIK', 'Pembantu Dekan II FIK', ''),
('7.FIP', 'Pembantu Dekan II FIP', ''),
('7.FIS', 'Pembantu Dekan II FIS', ''),
('7.FMIPA', 'Pembantu Dekan II FMIPA', ''),
('7.FT', 'Pembantu Dekan II FT', ''),
('7.PPs', 'Asdir II PPs', ''),
('8.FBS', 'Pembantu Dekan III FBS', ''),
('8.FE', 'Pembantu Dekan III FE', ''),
('8.FIK', 'Pembantu Dekan III FIK', ''),
('8.FIP', 'Pembantu Dekan III FIP', ''),
('8.FIS', 'Pembantu Dekan III FIS', ''),
('8.FMIPA', 'Pembantu Dekan III FMIPA', ''),
('8.FT', 'Pembantu Dekan III FT', ''),
('8.PPs', 'Asdir III PPs', ''),
('9', 'Ketua Lembaga Penelitian (LEMLIT)', ''),
('a', 'a', '003002'),
('UN39', 'Universitas Negeri Jakarta / REKTOR', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat_lampiran`
--

CREATE TABLE IF NOT EXISTS `surat_lampiran` (
  `no_surat` varchar(30) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  PRIMARY KEY (`no_surat`),
  UNIQUE KEY `no_surat` (`no_surat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `surat_lampiran`
--

INSERT INTO `surat_lampiran` (`no_surat`, `file_path`) VALUES
('42/UN39.18/AK/15', 'assets/uploaded/response.pdf'),
('43/UN39.18/AK/15', 'assets/uploaded/response.pdf'),
('44/UN39.18/AK/15', 'assets/uploaded/response.pdf'),
('50/UN39.18/BS/15', 'assets/attachments/MEMBANDINGKAN-ANALISA-DATA-SET-MENGGUNAKAN-APLIKASI-WEKA-DENGAN-ALGORITMA-ID3-DAN-NAÃVE-BAYES-SECARA-MANUAL.pdf'),
('51/UN39.18/BS/15', 'assets/attachments/response.pdf'),
('52/UN39.18/AK/15', 'assets/attachments/T2-5235134408.pdf');

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat_terdistribusi`
--

CREATE TABLE IF NOT EXISTS `surat_terdistribusi` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `id_surat` int(4) NOT NULL,
  `penerima` varchar(255) NOT NULL,
  `notif_web` int(4) NOT NULL,
  `notif_app` int(4) NOT NULL,
  `status` varchar(30) NOT NULL,
  `isFavorite` tinyint(4) NOT NULL,
  `isUnread` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_surat` (`id_surat`),
  KEY `penerima` (`penerima`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=108 ;

--
-- Dumping data untuk tabel `surat_terdistribusi`
--

INSERT INTO `surat_terdistribusi` (`id`, `id_surat`, `penerima`, `notif_web`, `notif_app`, `status`, `isFavorite`, `isUnread`) VALUES
(103, 68, 'hamidillah_ajie', 0, 0, '', 0, 1),
(104, 83, 'wisnu_dj', 0, 0, '', 0, 1),
(105, 109, '18', 0, 0, '', 0, 1),
(106, 110, 'hamidillah_ajie', 0, 0, '', 0, 1),
(107, 116, 'hamidillah_ajie', 0, 0, '', 0, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat_tujuan`
--

CREATE TABLE IF NOT EXISTS `surat_tujuan` (
  `id` int(4) NOT NULL,
  `id_surat` int(4) NOT NULL,
  `id_user` int(4) NOT NULL,
  `jenis_penerima` varchar(10) NOT NULL,
  `id_pengirim` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat_uploaded`
--

CREATE TABLE IF NOT EXISTS `surat_uploaded` (
  `no_surat` varchar(30) NOT NULL,
  `file_path` text NOT NULL,
  PRIMARY KEY (`no_surat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `surat_uploaded`
--

INSERT INTO `surat_uploaded` (`no_surat`, `file_path`) VALUES
('39/UN39.18/AK/15', 'assets/uploaded/response.pdf'),
('40/UN39.18/AK/15', 'assets/uploaded/response.pdf'),
('41/UN39.18/AK/15', 'assets/uploaded/response.pdf'),
('42/UN39.18/AK/15', 'assets/uploaded/response.pdf'),
('43/UN39.18/AK/15', 'assets/uploaded/response.pdf');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(4) NOT NULL AUTO_INCREMENT,
  `account` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nip` varchar(15) NOT NULL,
  `email1` varchar(255) NOT NULL,
  `email2` varchar(255) NOT NULL,
  `nohp1` varchar(15) NOT NULL,
  `nohp2` varchar(15) NOT NULL,
  `gender` varchar(2) NOT NULL,
  `id_institusi` varchar(6) NOT NULL,
  `id_jabatan` varchar(9) NOT NULL,
  `alamat_kantor` varchar(50) NOT NULL,
  `jenis_user` varchar(3) NOT NULL,
  `gcm_regid` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`user_id`, `account`, `password`, `nama`, `nip`, `email1`, `email2`, `nohp1`, `nohp2`, `gender`, `id_institusi`, `id_jabatan`, `alamat_kantor`, `jenis_user`, `gcm_regid`, `created`) VALUES
(1, 'hamidillah_ajie', 'asdfasdf', 'Hamidillah Ajie', '123456789', 'havid@yahoo.com', 'havid@hotmail.com', '1234', '56789', '1', '003000', '003000001', '', '3', '', '2015-09-27 16:23:08'),
(2, 'ficky_duskarnaen', 'asdfasdf', 'M. Ficky Duskarnaen', '123456789', 'ficky_duskarnaen@hotmail.com', 'ficky_duskarnaen2@hotmail.com', '', '', '1', '003000', '18', '', '3', '', '2015-09-27 16:23:10'),
(4, 'wisnu_dj', 'asdfasdf', 'Wisnu Djatmiko', '123456789', 'wisnu_dj@gmail.com', 'w_djatmikoy@hotmail.com', '', '', '1', '002000', '002000000', '', '3', '', '2015-09-27 16:23:12'),
(5, 'pak_joni', 'asdfasdf', 'Pak Joni', '123456789', 'pak_joni@gmail.com', 'pak_joni@hotmail.com', '', '', '1', '002000', '002000001', '', '2', '', '2015-09-27 06:41:48'),
(6, 'widodo', 'asdfasdf', 'Widodo', '123456789', 'widodo@gmail.com', 'widodo@hotmail.com', '', '', '1', '000000', '000000000', '', '3', '', '2015-09-27 16:23:14'),
(7, 'prasetyo_wp', 'asdfasdf', 'Prasetyo Wibowo Yunanto', '123456789', 'prasetyo_wp@gmail.com', 'prasetyo@yahoo.com', '', '', '1', '002000', '002000003', '', '3', '', '2015-09-27 16:23:16'),
(8, 'firdaus_ibnu', 'asdfasdf', 'Firdaus Ibnu', '123456789', 'firdausibnuu@gmail.com', 'firdausibnu@hotmail.com', '083891915007', '', 'L', '003000', '003000003', '', '2', '', '2015-10-03 17:22:37'),
(10, 'operator_oie', 'asdfasdf', '', '', '', '', '', '', '', '003002', '003002000', '', '2', '', '2015-10-03 17:29:51');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
