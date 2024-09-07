-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 07, 2024 at 07:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pnc`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_actionlogs`
--

CREATE TABLE `tbl_actionlogs` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `action_id` int(11) NOT NULL,
  `action_desc` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_actionlogs`
--

INSERT INTO `tbl_actionlogs` (`id`, `user_id`, `action_id`, `action_desc`, `created_at`) VALUES
(17, '1', 7, 'Company Test created', '2024-09-02 15:30:09'),
(18, '1', 9, 'Deleted company Test', '2024-09-02 15:30:12'),
(19, '1', 3, 'Deleted coordinator ', '2024-09-02 15:32:31'),
(20, '1', 1, 'Coordinator account created for test', '2024-09-02 15:32:51'),
(21, '1', 3, 'Deleted coordinator test', '2024-09-02 15:32:54'),
(22, '1', 4, 'Program Test created', '2024-09-02 15:34:39'),
(23, '1', 6, 'Deleted program Test', '2024-09-02 15:34:42'),
(24, '1', 10, 'Deleted student JobertCadiz', '2024-09-02 15:36:38'),
(25, '1', 10, 'Deleted student John Doe', '2024-09-02 15:37:01'),
(26, '1', 4, 'Program Test created', '2024-09-07 00:42:19'),
(27, '1', 6, 'Deleted program Test', '2024-09-07 00:42:40'),
(28, '1', 17, 'Status Set Active for student ID 1234567', '2024-09-07 02:07:13'),
(61, '10750', 18, 'Requirement PNC:AA-FO-20 Set Cancelled for Student Jimmy Camangon', '2024-09-07 08:37:06'),
(62, '10750', 18, 'Requirement PNC:AA-FO-21 Set Approved for Student Jimmy Camangon', '2024-09-07 08:37:17'),
(63, '46918', 18, 'Requirement PNC:AA-FO-22 Set Approved for Student Juan Tamad', '2024-09-07 08:49:41');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_adjustments`
--

CREATE TABLE `tbl_adjustments` (
  `id` int(11) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `records` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_adjustments`
--

INSERT INTO `tbl_adjustments` (`id`, `student_id`, `records`, `reason`, `status`, `createdAt`) VALUES
(6, '1234567', '2024-09-01,2024-09-02', 'Nakalimutan ko po sorry po.', 'Pending', '2024-09-07 17:03:03'),
(7, '1234566', '2024-08-31', 'Nakalimutan ko lang din po sorry po :3', 'Pending', '2024-09-07 17:08:38');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`admin_id`, `username`, `password`, `createdAt`) VALUES
(1, 'admin', '$2y$10$xcqil52Qo1zETHWAbfBrYeBkNnuCiIYhZFUYunfEfWcNDS/SPTxDi', '2024-04-06 02:38:26');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_companies`
--

CREATE TABLE `tbl_companies` (
  `id` int(11) NOT NULL,
  `company_id` varchar(255) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_coordinators`
--

CREATE TABLE `tbl_coordinators` (
  `id` int(11) NOT NULL,
  `coordinator_id` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_coordinators`
--

INSERT INTO `tbl_coordinators` (`id`, `coordinator_id`, `username`, `password`, `firstname`, `lastname`, `email`, `createdAt`) VALUES
(7, '10750', 'jims', '$2y$10$bafoxK.Rf19YCHNcQ1/uBuTALtgbwSRRd79.N1aIwPNL3KnnNeq8i', 'Jimmy', 'Camangon', 'jimmycamangon7@gmail.com', '2024-08-31 05:40:47'),
(8, '46918', 'lorenz', '$2y$10$dOxMe3YXxSK/dpZ.eHKR7ee5ASLEyCxvLAwC8YA/sZP9Lx8Kp17lS', 'Lorenz', 'Villalobos', 'lorenz@gmail.com', '2024-08-31 07:25:14');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_programs`
--

CREATE TABLE `tbl_programs` (
  `id` int(11) NOT NULL,
  `program_id` varchar(255) NOT NULL,
  `program_name` varchar(100) NOT NULL,
  `program_hour` int(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_programs`
--

INSERT INTO `tbl_programs` (`id`, `program_id`, `program_name`, `program_hour`, `createdAt`) VALUES
(10, '95742', 'Bachelor of Science in Information Technology', 500, '2024-08-31 05:42:05'),
(11, '44423', 'Bachelor of Science in Nursing', 2000, '2024-08-31 07:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reference`
--

CREATE TABLE `tbl_reference` (
  `action_id` int(11) NOT NULL,
  `action_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_reference`
--

INSERT INTO `tbl_reference` (`action_id`, `action_name`) VALUES
(1, 'Created a coordinator'),
(2, 'Updated a coordinator'),
(3, 'Deleted a coordinator'),
(4, 'Created a program'),
(5, 'Updated a program'),
(6, 'Deleted a program'),
(7, 'Created a company'),
(8, 'Updated a company'),
(9, 'Deleted a company'),
(10, 'Deleted a student'),
(11, 'Logged in'),
(12, 'Logged out'),
(13, 'Exported data'),
(14, 'Imported data'),
(15, 'Changed system settings'),
(16, 'Performed a security audit'),
(17, 'Set Status Student'),
(18, 'Set Requirement Status Intern');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_requirements`
--

CREATE TABLE `tbl_requirements` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `form_type` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_requirements`
--

INSERT INTO `tbl_requirements` (`id`, `student_id`, `form_type`, `file_name`, `file_path`, `status`, `uploaded_at`) VALUES
(11, '1234567', 'PNC:AA-FO-20', 'UC-PnC-Internship-Manual-1.pdf', 'requirements/UC-PnC-Internship-Manual-1.pdf', 'Cancelled', '2024-09-07 00:29:29'),
(12, '1234567', 'PNC:AA-FO-21', 'UC-PnC-Internship-Manual-1.pdf', 'requirements/UC-PnC-Internship-Manual-1.pdf', 'Approved', '2024-09-07 08:01:20'),
(13, '1234566', 'PNC:AA-FO-22', 'UC-PnC-Internship-Manual-1.pdf', 'requirements/UC-PnC-Internship-Manual-1.pdf', 'Approved', '2024-09-07 08:44:50');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_timelogs`
--

CREATE TABLE `tbl_timelogs` (
  `id` int(11) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `pin` char(4) NOT NULL,
  `type` enum('time_in','time_out') NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `longitude` decimal(9,6) NOT NULL,
  `latitude` decimal(9,6) NOT NULL,
  `photo` mediumblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_timelogs`
--

INSERT INTO `tbl_timelogs` (`id`, `student_id`, `pin`, `type`, `timestamp`, `longitude`, `latitude`, `photo`) VALUES
(61, '1234567', '', 'time_in', '2024-08-31 05:57:29', 121.143504, 14.273903, ''),
(62, '1234567', '', 'time_out', '2024-08-31 13:58:14', 121.143504, 14.273903, ''),
(63, '1234567', '', 'time_in', '2024-09-01 05:57:29', 121.143504, 14.273903, ''),
(65, '1234567', '', 'time_in', '2024-09-02 05:57:29', 121.143504, 14.273903, ''),
(66, '1234567', '', 'time_in', '2024-09-03 05:57:29', 121.143504, 14.273903, ''),
(67, '1234567', '', 'time_out', '2024-09-03 09:58:14', 121.143504, 14.273903, ''),
(70, '1234566', '', 'time_in', '2024-08-31 07:35:51', 121.143504, 14.273903, '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `credential_id` text DEFAULT NULL,
  `attestation_object` text DEFAULT NULL,
  `client_data_json` text DEFAULT NULL,
  `pin` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `program_id` int(11) NOT NULL,
  `coordinator_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `student_id`, `credential_id`, `attestation_object`, `client_data_json`, `pin`, `firstname`, `lastname`, `email`, `phone`, `address`, `program_id`, `coordinator_id`, `status`, `createdAt`) VALUES
(17, '1234567', 'x02NSE73OU/QCt9+PyW6Qz4ro+oCPc6Sd+6PVkW3yoU=', 'o2NmbXRmcGFja2VkZ2F0dFN0bXSiY2FsZzkBAGNzaWdZAQCqeMGOup0vuILjr8oz/cC2WTOG5XNVwgITNXlB6uD35F5AQcUw0kBBlot8hD6TH7dPffgnPZgQ8R1JoKmBFI6VOH0lV3jYlNlp9ryrxwQogMOLc6mjlhJiK+RtYTNidAGFwHn/8qr7Wy+00dI5U0y8zrNyO8SanwsV/HsQ9Z97I37/slgGz6cOwSiEdUpSjxrZVZdN2tWm4+movRJNhe4b+40EXjz710H5CWeczKY0la/FmX3cwggb7TVDtxsd2J24/QJXonYVHtCV+aUX+/CF2uXNdG2IaUfK+mlA4Ar798b6GXFzpAy+Xx1KWO8rpS7BymnoR3pioI9Af1isnq1DaGF1dGhEYXRhWQFnSZYN5YgOjGh0NBcPZHZgW4/krrmihjLHmVzzuoMdl2NFAAAAAGAosBex1EwCtLOvza/Ja7IAIMdNjUhO9zlP0Arffj8lukM+K6PqAj3Oknfuj1ZFt8qFpAEDAzkBACBZAQDpCwXlI4Keo/ox75/2K+ov1igwmto6Vey5lZHpqDGYZh6SLUjmCVxAD6wRU6c/p8c/tHc9VhJo/LF6KjWglT8G79vqmT+l5TZHXnZHp55kmiSYz67glNWKmwo9mUrWd0SPiPYbGP0NR24BHdPUlHg+AshXuwBa3O6f+NZsFdfBsyAl6Jqks+DorgYhM5mUMjMZXPnWBXpP51G0+uCS5yEgrehcoJ5ZUnJLtIyZN9538pRYqo3kgm29EIyBQWslxFhdXsx7pSsUIpRsqu/nQ/Va71OQOCuGW+02/RRh1Clr/wtfhVjzj8VSHuHr3T8cOWZvj0jmYtqcMyr8PhYAgmUlIUMBAAE=', 'eyJ0eXBlIjoid2ViYXV0aG4uY3JlYXRlIiwiY2hhbGxlbmdlIjoiLWFiR0h0YWE4d19wNDIyWTVrM3N6eWozd01fV1NaYjY1VmlZdnJJN3IxdyIsIm9yaWdpbiI6Imh0dHA6Ly9sb2NhbGhvc3QiLCJjcm9zc09yaWdpbiI6ZmFsc2V9', '$2y$10$E5C5eRWIN.ZAWIEv07G5..rie7g2etoeTd5NZsZ22oqZdJz2vp3jC', 'Jimmy', 'Camangon', 'jimmycamangon7@gmail.com', '09365220532', 'Caingin', 95742, 10750, 'Active', '2024-09-07 02:12:29'),
(21, '1234566', '5a/vHoKyxBFUV0LMUXpMhfyyowW2TNPAEK9kbLA1Lfs=', 'o2NmbXRmcGFja2VkZ2F0dFN0bXSiY2FsZzkBAGNzaWdZAQCLk1g4aW8zUqLyNjQizsUi3ZFJOCIfRsQKZggbWhihlaz2+Bq5+b0QlZlE1cQuLGZwDDOWwCnHQGxZ0mbKV/UOQg7cTq37LIDLrzC2F/0Dqo4DvZGBifZgLHDYpSlKoTzf+Aor6Vrwz0mrcOmjVXwfkNZ/7kNv0TxZs+RIVvT6eeeEjuw2cE5lKXaUdpc43tmzcbAOZR1dxhNjavJZE3GBuwtDu5lqdwP0FzVXHMBR4clDTiLJ9AXkUnClOydpTq7RhHHQM6mhVfThOHZVbgGdkPZoDmZI9/EwFP/9RJu9kYwqTDQEvKZSB+TLn23AE/7zONhF7+QSSq0x71wgiqquaGF1dGhEYXRhWQFnSZYN5YgOjGh0NBcPZHZgW4/krrmihjLHmVzzuoMdl2NFAAAAAGAosBex1EwCtLOvza/Ja7IAIOWv7x6CssQRVFdCzFF6TIX8sqMFtkzTwBCvZGywNS37pAEDAzkBACBZAQCyqROVt4ASXc1sMS/rI7U+Nk1BSYxwk50SPiEfwI1h1Ks5b58gYzZH/JeUsB52Umdz9FOLqvNhitFoe9kta24ZDloT+30MvxKvCFPRx6E7FhCX1Ux6sHqE85YYTJonLhTlb5rdtsrg7GS/U77nVwtvjhnQsQRrBNQfslHrCPCDxG6UBkYoQ6wA92g9Q2D5dCuNejHQr4xzup/Fqf3hiSLa+D8CvQtqJEoijfYUhq/Am037SrsxKFwpZGKvG7vghRa1PVRxpilJcrqccJzQ8FCmCmEPYwxfm5e5XJy3iwOftEaCECulFpM/237I8OV/DBOt96ZGBzpOeXs0x0v9NYZBIUMBAAE=', 'eyJ0eXBlIjoid2ViYXV0aG4uY3JlYXRlIiwiY2hhbGxlbmdlIjoiQmNSSlJFTF9UWDJZVUVycFlDbmxQakhtYjUzckI3VTRLaFRKYkpuN2luTSIsIm9yaWdpbiI6Imh0dHA6Ly9sb2NhbGhvc3QiLCJjcm9zc09yaWdpbiI6ZmFsc2V9', '$2y$10$z5xH.Uhy.DAr6bytgQkGGOcl4LE0tkXx/OFVDOY.MjBjNDAWIdIT.', 'Juan', 'Tamad', 'juantamad@gmail.com', '09365220532', 'Caingin', 95742, 46918, '', '2024-09-07 13:14:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_actionlogs`
--
ALTER TABLE `tbl_actionlogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_adjustments`
--
ALTER TABLE `tbl_adjustments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `tbl_companies`
--
ALTER TABLE `tbl_companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_coordinators`
--
ALTER TABLE `tbl_coordinators`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_programs`
--
ALTER TABLE `tbl_programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_reference`
--
ALTER TABLE `tbl_reference`
  ADD PRIMARY KEY (`action_id`);

--
-- Indexes for table `tbl_requirements`
--
ALTER TABLE `tbl_requirements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_timelogs`
--
ALTER TABLE `tbl_timelogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_actionlogs`
--
ALTER TABLE `tbl_actionlogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `tbl_adjustments`
--
ALTER TABLE `tbl_adjustments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_companies`
--
ALTER TABLE `tbl_companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_coordinators`
--
ALTER TABLE `tbl_coordinators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_programs`
--
ALTER TABLE `tbl_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_requirements`
--
ALTER TABLE `tbl_requirements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_timelogs`
--
ALTER TABLE `tbl_timelogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
