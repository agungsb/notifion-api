-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2015 at 05:28 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sirat_v2`
--

-- --------------------------------------------------------

--
-- Table structure for table `email`
--

CREATE TABLE IF NOT EXISTS `email` (
  `no` int(4) NOT NULL,
  `subyek` varchar(100) NOT NULL,
  `attacment` text NOT NULL,
  `isi` varchar(255) NOT NULL,
  `id_surat` int(4) NOT NULL,
  `penerima` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gcm_users`
--

CREATE TABLE IF NOT EXISTS `gcm_users` (
`id` int(11) NOT NULL,
  `gcm_regid` text NOT NULL,
  `account` varchar(255) NOT NULL,
  `id_institusi` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gcm_users`
--

INSERT INTO `gcm_users` (`id`, `gcm_regid`, `account`, `id_institusi`, `created_at`) VALUES
(71, 'APA91bHBCWBjYWAQrwTVHeNN2Uhr_gjopSns5XukJw3x_qTvUOfnGVoxf0jOfJsHZfHL0hDyUThsWO6r-4BiCu2PFt5HWKWT23gSzUEilymYyYPFUm61D8oM3RpDoyyQS_Ys5HiVnCX5', 'hamidillah_ajie', 0, '2015-09-19 07:43:33');

-- --------------------------------------------------------

--
-- Table structure for table `instansi`
--

CREATE TABLE IF NOT EXISTS `instansi` (
  `id_instansi` varchar(9) NOT NULL,
  `nama_instansi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `instansi`
--

INSERT INTO `instansi` (`id_instansi`, `nama_instansi`) VALUES
('000', 'None'),
('001', 'Rektorat'),
('002', 'Fakultas'),
('003', 'Unit Pelayanan Teknis'),
('004', 'Prodi'),
('005', 'Biro'),
('006', '-');

-- --------------------------------------------------------

--
-- Table structure for table `institusi`
--

CREATE TABLE IF NOT EXISTS `institusi` (
  `id_institusi` varchar(6) NOT NULL,
  `nama_institusi` varchar(255) NOT NULL,
  `id_instansi` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `institusi`
--

INSERT INTO `institusi` (`id_institusi`, `nama_institusi`, `id_instansi`) VALUES
('000000', 'None', '000'),
('001000', 'Rektor', '001'),
('002000', 'Teknik', '002'),
('003000', 'PUSTIKOM', '003'),
('004000', 'Elektro', '004'),
('005000', 'BAUK', '005'),
('006000', 'SPI', '006');

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE IF NOT EXISTS `jabatan` (
  `id_jabatan` varchar(9) NOT NULL,
  `id_institusi` varchar(9) NOT NULL,
  `jabatan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `id_institusi`, `jabatan`) VALUES
('000000000', '000000', 'None'),
('001000000', '001000', 'operator_rektor'),
('001000001', '001000', 'REKTOR'),
('001000002', '001000', 'PEMBANTU REKTOR I'),
('001000003', '001000', 'PEMBANTU REKTOR II'),
('001000004', '001000', 'PEMBANTU REKTOR III'),
('001000005', '001000', 'PEMBANTU REKTOR IV'),
('003000000', '003000', 'operator_pustikom'),
('003000001', '003000', 'Kepala UPT Pusat Komputer'),
('003000002', '003000', 'Sekretaris Umum Pustikom'),
('003000003', '003000', 'Kabid. Sistem Informasi Pustikom'),
('004000000', '004000', 'operator_elektro');

-- --------------------------------------------------------

--
-- Table structure for table `log_kirim`
--

CREATE TABLE IF NOT EXISTS `log_kirim` (
`no` int(4) NOT NULL,
  `id_surat` varchar(10) NOT NULL,
  `jenis_penerima` varchar(255) NOT NULL COMMENT 'SMS, ANDRO, EMAIL',
  `tujuan` varchar(255) NOT NULL,
  `waktu_kirim` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `log_respond`
--

CREATE TABLE IF NOT EXISTS `log_respond` (
`id` int(4) NOT NULL,
  `pesan_respond` varchar(255) NOT NULL,
  `waktu` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `operator`
--

CREATE TABLE IF NOT EXISTS `operator` (
`id_operator` int(4) NOT NULL,
  `account_op` varchar(255) NOT NULL,
  `pass_op` varchar(255) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `id_institusi` int(4) NOT NULL,
  `alamat_kantor` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `operator`
--

INSERT INTO `operator` (`id_operator`, `account_op`, `pass_op`, `nama`, `email`, `id_institusi`, `alamat_kantor`) VALUES
(1, 'operator', '1234', 'Firdaus', 'firdausibnuu@gmail.com', 3000, 'PUSTIKOM GEDUNG D'),
(2, 'operator2', '1234', 'Pak Joni', 'pak_joni@gmail.com', 2000, 'TEKNIK ELEKTRO L2');

-- --------------------------------------------------------

--
-- Table structure for table `pejabat`
--

CREATE TABLE IF NOT EXISTS `pejabat` (
`id_pejabat` int(4) NOT NULL,
  `account` varchar(255) NOT NULL,
  `id_jabatan` varchar(9) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pejabat`
--

INSERT INTO `pejabat` (`id_pejabat`, `account`, `id_jabatan`) VALUES
(4, 'ficky_duskarnaen', '003000001'),
(5, 'operator_pustikom', '003000000'),
(18, 'operator_rektor', '001000000'),
(19, 'djali', '001000001'),
(20, 'muchlis', '001000002'),
(21, 'komarudin', '001000003'),
(22, 'achmad_ridwan', '001000005'),
(23, 'ahmad_sofyan', '001000004'),
(24, 'operator_elektro', '004000000'),
(25, 'hamidillah_ajie', '003000002'),
(26, 'med_irzal', '003000003');

-- --------------------------------------------------------

--
-- Table structure for table `surat`
--

CREATE TABLE IF NOT EXISTS `surat` (
`id_surat` int(4) NOT NULL,
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
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `surat`
--

INSERT INTO `surat` (`id_surat`, `subject_surat`, `nama_surat`, `no_surat`, `jenis`, `hal`, `isi`, `kode_hal`, `kode_lembaga_pengirim`, `penandatangan`, `tujuan`, `lampiran`, `tembusan`, `tanggal_surat`, `file_surat`, `file_lampiran`, `id_operator`, `pesan_android`, `pesan_email`, `pesan_sms`, `ditandatangani`, `is_uploaded`, `created`) VALUES
(1, 'Surat 1 ada 2 lampiran', '', '1/UN39.18/KM/15', '', '', '<p>Surat 1, 2 Lampiran</p>', 'KM', '003000', '003000001', '001000001@+id/', 2, 'ficky_duskarnaen', '2015-10-25', '', '', 0, '', '', '', 1, 'false', '2015-10-26 18:50:45'),
(2, 'Test Subject 2 lampiran', '', '2/UN39.18/AK/15', '', '', '<p>2 lampiran pak ficky tembusannya</p>', 'AK', '003000', '003000001', '001000001@+id/', 2, 'ficky_duskarnaen', '2015-10-25', '', '', 0, '', '', '', 0, 'false', '2015-10-25 06:36:58'),
(3, 'Test Subject 2 lampiran', '', '3/UN39.18/AK/15', '', '', '<p>Ada 2 tembusan (operator_rektor, operator_pustikom)</p>', 'AK', '003000', '003000001', '001000001@+id/', 4, '001000000@+id/003000000', '2015-10-25', '', '', 0, '', '', '', 0, 'false', '2015-10-27 04:27:50'),
(4, 'Test Subject 2 lampiran', '', '4/UN39.18/AK/15', '', '', '<p>2 lampiran pak widodo tembusannya</p>', 'AK', '003000', '003000001', '001000001@+id/', 2, '003000001', '2015-10-25', '', '', 0, '', '', '', 0, 'false', '2015-10-25 06:45:22'),
(5, 'mau test tembusan', '', '5/UN39.18/AK/15', '', '', '<p>tembusannya apa</p>', 'AK', '003000', '003000001', 'widodo@+id/', 0, '003000001', '2015-10-27', '', '', 0, '', '', '', 0, 'false', '2015-10-26 19:09:41'),
(6, 'tes 2 tembusan', '', '6/UN39.18/AK/15', '', '', '<p>1 rektor, 1 jali</p>', 'AK', '003000', '003000001', '003000001@+id/djali', 0, '003000001@+id/djali', '2015-10-27', '', '', 0, '', '', '', 0, 'false', '2015-10-26 22:27:05');

-- --------------------------------------------------------

--
-- Table structure for table `surat_counter`
--

CREATE TABLE IF NOT EXISTS `surat_counter` (
`id` int(11) NOT NULL,
  `id_institusi` varchar(6) NOT NULL,
  `counter` int(11) NOT NULL,
  `year` varchar(6) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `surat_counter`
--

INSERT INTO `surat_counter` (`id`, `id_institusi`, `counter`, `year`) VALUES
(4, '003000', 6, '2015');

-- --------------------------------------------------------

--
-- Table structure for table `surat_isi`
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
-- Dumping data for table `surat_isi`
--

INSERT INTO `surat_isi` (`id_surat`, `no_surat`, `lampiran`, `hal`, `isi`, `nama_jabatan`, `nama_penjabat`, `nip`, `tembusan`) VALUES
('3', '99/999/999/999', 4, 'Permohonan Surat', '<p>Dengan Hormat,</p>\r\n<p>Bersama ini kami mengajukan proposal pengembangan TIK universitas dengan tema proposal\r\nPengembangan dan Revitalisasi Sistem dan TIK universitas untuk meningkatkan pelayanan\r\nTridarma di Universitas Negeri Jakarta.</p>\r\n<p>Kegiatan Tersebut ditujukan untuk membangun sistem TIK di universitas negeri jakarta yang\r\nditunjukan untuk meningkatkan pelayanan Tridama bagi Seluruh pemangku kepentingan di\r\nUniversitas Negeri Jakarta.</p>\r\n<p>Demikian pengajuan proposal pengembangan TIK Universitas ini kami sampaikan, atas\r\nperhatiannya kami ucapkan terima kasih.</p>\r\n', 'Ka. Pustikom', 'M. Ficky Duskarnaen', '197408242005011', ''),
('3', '99/999/999/999', 4, 'Permohonan Surat', '<p>Dengan Hormat,</p>\r\n<p>Bersama ini kami mengajukan proposal pengembangan TIK universitas dengan tema proposal\r\nPengembangan dan Revitalisasi Sistem dan TIK universitas untuk meningkatkan pelayanan\r\nTridarma di Universitas Negeri Jakarta.</p>\r\n<p>Kegiatan Tersebut ditujukan untuk membangun sistem TIK di universitas negeri jakarta yang\r\nditunjukan untuk meningkatkan pelayanan Tridama bagi Seluruh pemangku kepentingan di\r\nUniversitas Negeri Jakarta.</p>\r\n<p>Demikian pengajuan proposal pengembangan TIK Universitas ini kami sampaikan, atas\r\nperhatiannya kami ucapkan terima kasih.</p>\r\n', 'Ka. Pustikom', 'M. Ficky Duskarnaen', '197408242005011001', '');

-- --------------------------------------------------------

--
-- Table structure for table `surat_kode_hal`
--

CREATE TABLE IF NOT EXISTS `surat_kode_hal` (
  `kode_hal` varchar(10) NOT NULL,
  `deskripsi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `surat_kode_hal`
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
-- Table structure for table `surat_kode_unit`
--

CREATE TABLE IF NOT EXISTS `surat_kode_unit` (
  `kode_unit` varchar(10) NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `id_institusi` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `surat_kode_unit`
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
('UN39', 'Universitas Negeri Jakarta / REKTOR', '');

-- --------------------------------------------------------

--
-- Table structure for table `surat_koreksi`
--

CREATE TABLE IF NOT EXISTS `surat_koreksi` (
  `id_koreksi` int(4) NOT NULL,
  `no_surat` varchar(30) NOT NULL,
  `koreksi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `surat_lampiran`
--

CREATE TABLE IF NOT EXISTS `surat_lampiran` (
`id_lampiran` int(11) NOT NULL,
  `no_surat` varchar(30) NOT NULL,
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `surat_lampiran`
--

INSERT INTO `surat_lampiran` (`id_lampiran`, `no_surat`, `file_path`) VALUES
(1, '1/UN39.18/KM/15', 'assets/attachments/T2-pdf-security-4-5235134408.pdf'),
(2, '1/UN39.18/KM/15', 'assets/attachments/T2-pdf-security-5-5235134408.pdf'),
(3, '2/UN39.18/AK/15', 'assets/attachments/1A-Pengumuman Kelulusan.pdf'),
(4, '2/UN39.18/AK/15', 'assets/attachments/07 Desain Software.pdf'),
(5, '3/UN39.18/AK/15', 'assets/attachments/1A-Pengumuman Kelulusan.pdf'),
(6, '3/UN39.18/AK/15', 'assets/attachments/07 Desain Software.pdf'),
(7, '4/UN39.18/AK/15', 'assets/attachments/1A-Pengumuman Kelulusan.pdf'),
(8, '4/UN39.18/AK/15', 'assets/attachments/07 Desain Software.pdf'),
(11, '3/UN39.18/AK/15', 'assets/attachments/824cedd6-4137-4237-b4c1-ed4423f8b6a7.pdf'),
(12, '3/UN39.18/AK/15', 'assets/attachments/response.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `surat_terdistribusi`
--

CREATE TABLE IF NOT EXISTS `surat_terdistribusi` (
`id` int(4) NOT NULL,
  `id_surat` int(4) NOT NULL,
  `penerima` varchar(255) NOT NULL,
  `notif_web` int(4) NOT NULL,
  `notif_app` int(4) NOT NULL,
  `status` varchar(30) NOT NULL,
  `isFavorite` tinyint(4) NOT NULL,
  `isUnread` tinyint(4) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `surat_terdistribusi`
--

INSERT INTO `surat_terdistribusi` (`id`, `id_surat`, `penerima`, `notif_web`, `notif_app`, `status`, `isFavorite`, `isUnread`) VALUES
(1, 1, 'widodo', 0, 0, '', 0, 1),
(2, 1, '001000001', 0, 0, '', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `surat_tujuan`
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
-- Table structure for table `surat_uploaded`
--

CREATE TABLE IF NOT EXISTS `surat_uploaded` (
  `no_surat` varchar(30) NOT NULL,
  `file_path` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `surat_uploaded`
--

INSERT INTO `surat_uploaded` (`no_surat`, `file_path`) VALUES
('67/UN39.18/BH/15', 'assets/uploaded/T2-pdf-5235134408.pdf'),
('68/UN39.18/BH/15', 'assets/uploaded/T2-pdf-5235134408.pdf'),
('69/UN39.18/BH/15', 'assets/uploaded/T2-pdf-5235134408.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`user_id` int(4) NOT NULL,
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
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `account`, `password`, `nama`, `nip`, `email1`, `email2`, `nohp1`, `nohp2`, `gender`, `id_institusi`, `id_jabatan`, `alamat_kantor`, `jenis_user`, `gcm_regid`, `created`) VALUES
(2, 'ficky_duskarnaen', 'asdfasdf', 'M. Ficky Duskarnaen', '0123', 'ficky_duskarnaen@hotmail.com', 'ficky_duskarnaen2@hotmail.com', '', '', '1', '003000', '003000001', '', '3', '', '2015-10-26 18:20:04'),
(8, 'operator_pustikom', 'asdfasdf', 'Firdaus Ibnu', '123456789', 'firdausibnuu@gmail.com', 'firdausibnu@hotmail.com', '083891915007', '083891915007', '1', '003000', '003000000', '', '2', '', '2015-10-24 08:01:23'),
(12, 'firdaus_ibnu', 'asdfasdf', 'Super Admin', '5235117148', 'firdausibnu@hotmail.com', 'firdausibnu@gmail.com', '083891915007', '083891915009', '1', '000000', '000000000', '', '1', '', '2015-10-17 10:02:31'),
(18, 'widodo', 'asdfasdf', 'widodo', '12415', '', '', '', '', '1', '003000', '000000000', '', '4', '', '2015-10-24 18:13:10'),
(23, 'djali', 'asdfasdf', 'Djali', '', '', '', '', '', '', '001000', '001000001', '', '3', '', '2015-10-25 04:57:39'),
(24, 'muchlis', 'asdfasdf', '', '', '', '', '', '', '', '001000', '001000002', '', '3', '', '2015-10-24 19:39:08'),
(26, 'komarudin', 'asdfasdf', '', '', '', '', '', '', '', '001000', '001000003', '', '3', '', '2015-10-24 19:39:22'),
(27, 'achmad_ridwan', 'asdfasdf', '', '', '', '', '', '', '', '001000', '001000005', '', '3', '', '2015-10-24 19:40:11'),
(28, 'operator_rektor', 'asdfasdf', 'Operator Rektor', '12345', 'a@a.com', 'a@a.com', '12345', '12345', '1', '001000', '001000000', '', '2', '', '2015-10-24 18:44:39'),
(29, 'ahmad_sofyan', 'asdfasdf', '', '', '', '', '', '', '', '001000', '001000004', '', '3', '', '2015-10-24 19:42:00'),
(30, 'operator_elektro', 'asdfasdf', 'Opeator Elektro', '', '', '', '', '', '', '004000', '004000000', '', '2', '', '2015-10-27 04:16:14'),
(31, 'hamidillah_ajie', 'asdfasdf', '', '', '', '', '', '', '', '003000', '003000002', '', '3', '', '2015-10-25 04:15:14'),
(32, 'med_irzal', 'asdfasdf', '', '', '', '', '', '', '', '003000', '003000003', '', '3', '', '2015-10-25 04:15:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `email`
--
ALTER TABLE `email`
 ADD PRIMARY KEY (`no`), ADD KEY `penerima` (`penerima`), ADD KEY `id_surat` (`id_surat`);

--
-- Indexes for table `gcm_users`
--
ALTER TABLE `gcm_users`
 ADD PRIMARY KEY (`id`), ADD KEY `id_institusi` (`id_institusi`);

--
-- Indexes for table `instansi`
--
ALTER TABLE `instansi`
 ADD PRIMARY KEY (`id_instansi`);

--
-- Indexes for table `institusi`
--
ALTER TABLE `institusi`
 ADD PRIMARY KEY (`id_institusi`);

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
 ADD PRIMARY KEY (`id_jabatan`);

--
-- Indexes for table `log_kirim`
--
ALTER TABLE `log_kirim`
 ADD PRIMARY KEY (`no`);

--
-- Indexes for table `log_respond`
--
ALTER TABLE `log_respond`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `operator`
--
ALTER TABLE `operator`
 ADD PRIMARY KEY (`id_operator`), ADD KEY `id_institusi` (`id_institusi`);

--
-- Indexes for table `pejabat`
--
ALTER TABLE `pejabat`
 ADD PRIMARY KEY (`id_pejabat`);

--
-- Indexes for table `surat`
--
ALTER TABLE `surat`
 ADD PRIMARY KEY (`id_surat`), ADD KEY `kode_unit` (`file_lampiran`), ADD KEY `pembuat` (`lampiran`);

--
-- Indexes for table `surat_counter`
--
ALTER TABLE `surat_counter`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `surat_kode_hal`
--
ALTER TABLE `surat_kode_hal`
 ADD PRIMARY KEY (`kode_hal`), ADD KEY `kode_hal` (`kode_hal`);

--
-- Indexes for table `surat_kode_unit`
--
ALTER TABLE `surat_kode_unit`
 ADD PRIMARY KEY (`kode_unit`), ADD KEY `kode_unit` (`kode_unit`);

--
-- Indexes for table `surat_koreksi`
--
ALTER TABLE `surat_koreksi`
 ADD PRIMARY KEY (`id_koreksi`);

--
-- Indexes for table `surat_lampiran`
--
ALTER TABLE `surat_lampiran`
 ADD PRIMARY KEY (`id_lampiran`);

--
-- Indexes for table `surat_terdistribusi`
--
ALTER TABLE `surat_terdistribusi`
 ADD PRIMARY KEY (`id`), ADD KEY `id_surat` (`id_surat`), ADD KEY `penerima` (`penerima`);

--
-- Indexes for table `surat_uploaded`
--
ALTER TABLE `surat_uploaded`
 ADD PRIMARY KEY (`no_surat`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gcm_users`
--
ALTER TABLE `gcm_users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=72;
--
-- AUTO_INCREMENT for table `log_kirim`
--
ALTER TABLE `log_kirim`
MODIFY `no` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `log_respond`
--
ALTER TABLE `log_respond`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `operator`
--
ALTER TABLE `operator`
MODIFY `id_operator` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `pejabat`
--
ALTER TABLE `pejabat`
MODIFY `id_pejabat` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `surat`
--
ALTER TABLE `surat`
MODIFY `id_surat` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `surat_counter`
--
ALTER TABLE `surat_counter`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `surat_lampiran`
--
ALTER TABLE `surat_lampiran`
MODIFY `id_lampiran` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `surat_terdistribusi`
--
ALTER TABLE `surat_terdistribusi`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `user_id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
