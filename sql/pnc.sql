-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 01, 2024 at 03:40 AM
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
(64, '1234567', '', 'time_out', '2024-09-01 13:58:14', 121.143504, 14.273903, ''),
(65, '1234567', '', 'time_in', '2024-09-02 05:57:29', 121.143504, 14.273903, ''),
(66, '1234567', '', 'time_in', '2024-09-03 05:57:29', 121.143504, 14.273903, ''),
(67, '1234567', '', 'time_out', '2024-09-03 09:58:14', 121.143504, 14.273903, ''),
(69, '1234567', '', 'time_out', '2024-09-02 13:58:14', 121.143504, 14.273903, ''),
(70, '1234566', '', 'time_in', '2024-08-31 07:35:51', 121.143504, 14.273903, ''),
(71, '1234566', '', 'time_out', '2024-08-31 13:36:05', 121.143504, 14.273903, ''),
(72, '1231234', '', 'time_in', '2024-08-31 07:38:01', 121.143504, 14.273903, ''),
(73, '1231234', '', 'time_out', '2024-08-31 15:38:11', 121.143504, 14.273903, ''),
(74, '1234545', '', 'time_in', '2024-08-31 10:01:11', 121.143504, 14.273903, ''),
(75, '1234545', '', 'time_out', '2024-08-31 12:01:19', 121.143504, 14.273903, ''),
(76, '1234567', '', 'time_out', '2024-09-04 09:58:14', 121.143504, 14.273903, ''),
(77, '1234567', '', 'time_out', '2024-09-04 13:58:14', 121.143504, 14.273903, ''),
(78, '1234567', '', 'time_out', '2024-09-05 09:58:14', 121.143504, 14.273903, ''),
(79, '1234567', '', 'time_out', '2024-09-05 13:58:14', 121.143504, 14.273903, ''),
(80, '1231234', '', 'time_in', '2024-09-01 01:33:34', 121.143504, 14.273903, ''),
(81, '1231234', '', 'time_out', '2024-09-01 01:33:50', 121.143504, 14.273903, '');

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
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `student_id`, `credential_id`, `attestation_object`, `client_data_json`, `pin`, `firstname`, `lastname`, `email`, `phone`, `address`, `program_id`, `coordinator_id`, `createdAt`) VALUES
(17, '1234567', 'x02NSE73OU/QCt9+PyW6Qz4ro+oCPc6Sd+6PVkW3yoU=', 'o2NmbXRmcGFja2VkZ2F0dFN0bXSiY2FsZzkBAGNzaWdZAQCqeMGOup0vuILjr8oz/cC2WTOG5XNVwgITNXlB6uD35F5AQcUw0kBBlot8hD6TH7dPffgnPZgQ8R1JoKmBFI6VOH0lV3jYlNlp9ryrxwQogMOLc6mjlhJiK+RtYTNidAGFwHn/8qr7Wy+00dI5U0y8zrNyO8SanwsV/HsQ9Z97I37/slgGz6cOwSiEdUpSjxrZVZdN2tWm4+movRJNhe4b+40EXjz710H5CWeczKY0la/FmX3cwggb7TVDtxsd2J24/QJXonYVHtCV+aUX+/CF2uXNdG2IaUfK+mlA4Ar798b6GXFzpAy+Xx1KWO8rpS7BymnoR3pioI9Af1isnq1DaGF1dGhEYXRhWQFnSZYN5YgOjGh0NBcPZHZgW4/krrmihjLHmVzzuoMdl2NFAAAAAGAosBex1EwCtLOvza/Ja7IAIMdNjUhO9zlP0Arffj8lukM+K6PqAj3Oknfuj1ZFt8qFpAEDAzkBACBZAQDpCwXlI4Keo/ox75/2K+ov1igwmto6Vey5lZHpqDGYZh6SLUjmCVxAD6wRU6c/p8c/tHc9VhJo/LF6KjWglT8G79vqmT+l5TZHXnZHp55kmiSYz67glNWKmwo9mUrWd0SPiPYbGP0NR24BHdPUlHg+AshXuwBa3O6f+NZsFdfBsyAl6Jqks+DorgYhM5mUMjMZXPnWBXpP51G0+uCS5yEgrehcoJ5ZUnJLtIyZN9538pRYqo3kgm29EIyBQWslxFhdXsx7pSsUIpRsqu/nQ/Va71OQOCuGW+02/RRh1Clr/wtfhVjzj8VSHuHr3T8cOWZvj0jmYtqcMyr8PhYAgmUlIUMBAAE=', 'eyJ0eXBlIjoid2ViYXV0aG4uY3JlYXRlIiwiY2hhbGxlbmdlIjoiLWFiR0h0YWE4d19wNDIyWTVrM3N6eWozd01fV1NaYjY1VmlZdnJJN3IxdyIsIm9yaWdpbiI6Imh0dHA6Ly9sb2NhbGhvc3QiLCJjcm9zc09yaWdpbiI6ZmFsc2V9', '$2y$10$E5C5eRWIN.ZAWIEv07G5..rie7g2etoeTd5NZsZ22oqZdJz2vp3jC', 'Jimmy', 'Camangon', 'jimmycamangon7@gmail.com', '09365220532', 'Caingin', 95742, 10750, '2024-08-31 05:57:18'),
(18, '1231234', 'DIohkvA87HO+iRzh0lYLTj/XWtO1xhlD0FrQiMJFYgM=', 'o2NmbXRmcGFja2VkZ2F0dFN0bXSiY2FsZzkBAGNzaWdZAQBNOR/8gQK/UGmV/eGwg4o7IJpwwO+4XdHZ5r3aFDtCNJMQtCPKZAM3hDigmiNhWs+XVdHu4CxtsSBO1TaOJ7c45HcsUjfy0ygW76shJ0PUcTDNQOh4587JI4wCyGILRkTbxg/f4cQ1r7UMhE4LMJsjJWI9OcNSZJ6kJazaXpUNMD9g6yqXxPvfwoWfCiVz2o+24mfKf+slAsHMwfPD7asUOjpf28jvIlJSWhUkYpvZvhmSdGquhdJ2Rj4fUDVlfjVfLlfjoGGqYwfpUTZit9fEbHTWTd4JJ/EQHQEcbx7NWK0D4pTOuzZmnBdxxxSHAKHe9NptsXx4UQMnA0dzzRIqaGF1dGhEYXRhWQFnSZYN5YgOjGh0NBcPZHZgW4/krrmihjLHmVzzuoMdl2NFAAAAAGAosBex1EwCtLOvza/Ja7IAIAyKIZLwPOxzvokc4dJWC04/11rTtcYZQ9Ba0IjCRWIDpAEDAzkBACBZAQDH3i/JW/8bIseurp9N9DA63fyZht/RuYD8W6nuP3DnVtojm0x4C25nGKaoBV+vKQis7FiXp9CcR0lpaG4J5hamiCVcKN2rsB6JviALM79zNmlu663dZfzxx09w8OVaqBbt99jNPyerr38uUakkXoeoR0NtgyG1H6ByupG4ATKEHL+lIQo7edE/RmzevqYJXLleEtboty/AnrXtgm9p1w5MEhdgq+zqqQVtEfmjqinUFtA8JlgixISY7yRh2wb2wDSeP5vExSju+a5p+t7n/GJggYaIc1fpUA/kpSybtfnlqImes7omH5hf5vLjpzBvkUWpAQ3vEkUvHSWVA1tJEc7pIUMBAAE=', 'eyJ0eXBlIjoid2ViYXV0aG4uY3JlYXRlIiwiY2hhbGxlbmdlIjoiRVFmMVBXaW0tUDM1QTdLRUstRHpuOUxwWllFanZBR1ZRaHFMeHhQM2p3RSIsIm9yaWdpbiI6Imh0dHA6Ly9sb2NhbGhvc3QiLCJjcm9zc09yaWdpbiI6ZmFsc2V9', '$2y$10$NXtHSw2puC5gAbZYzxA7ru7h.sItMj26De6K2nwlTFmLu2KmtqmZK', 'John', 'Doe', 'john@gmail.com', '09365220532', 'Caingin', 44423, 10750, '2024-08-31 07:37:49'),
(19, '1234566', 'YqXEdjAdIGva9ks8nS417NzNn4Xua38sSVOWap8kcZ8=', 'o2NmbXRmcGFja2VkZ2F0dFN0bXSiY2FsZzkBAGNzaWdZAQCdmz4K6nKm12dwaMgkJKues18DA/HwiggGvmNhamwfTb6IDxjIjRyZwpGzgXV1JyrvA+Esff31/4gXLUhb47zQh/718pptPX7YtRjGmbWD0i0uIsPHUu07SBYEE8MEUpARxZkktorykc3CaFWrhxF7Qg9EWL62Qk3EEAh4V5MvpHI4fu0MMSYK0uo1H5pFd3/U4hu2BsyyCS+D09SYROBYfNTL/iEtx2OBCBAs2WDfw0MKWqtFo6+g4r04IXqZnSYlq8usUECVnDb8lzHOcT46m2fEnESfd1FSXFbRNpVDK8OWhm7sQfoYJPxJTxdF6BYT21bxLwfVk5kGIV1ZG0xIaGF1dGhEYXRhWQFnSZYN5YgOjGh0NBcPZHZgW4/krrmihjLHmVzzuoMdl2NFAAAAAGAosBex1EwCtLOvza/Ja7IAIGKlxHYwHSBr2vZLPJ0uNezczZ+F7mt/LElTlmqfJHGfpAEDAzkBACBZAQCruWrz6URR9ItrI8I+pSAIrc6M+gPrDlIhVGXI0bgzLR08ydL+COH83l44tbnxv6QNogGZEyMCQ5mN8QFN9hUMC8R1bR7fgnOWpo9owSECwToCpxtz3/VUFCsivyas35TvXyoJXMyK7j830hhi8d2txHDj3auKTxS52y7jeUJv611M3mNuMW1C2Dk5rKR/X/95LRpfHS2sy21ltB9uaHVlZsi18n6yYf3msmKIr1h0F1cUOh7C3HYzMCl1KtjgylCKFpFSnMLc1S/ZXEHn2C3LS9Is3G0lyCtR+AgF1MPlUG/cXeszof1FgUwFNwjfCZKWhuTLAL8JwPpXrNVrZTjZIUMBAAE=', 'eyJ0eXBlIjoid2ViYXV0aG4uY3JlYXRlIiwiY2hhbGxlbmdlIjoieTZTR2pITS1Vam9OdkpVNlg0TlM4Y3phMUhHeExqSk8xNGVDSVE1Z0U1RSIsIm9yaWdpbiI6Imh0dHA6Ly9sb2NhbGhvc3QiLCJjcm9zc09yaWdpbiI6ZmFsc2V9', '$2y$10$.J2.cVygYZyYga0Ga5PFIOOI8jOkwbnDRM7pvAAK8EjFW44oPpphy', 'Jobert', 'Cadiz', 'jobertcadiz14@gmail.com', '09365220532', 'Caingin', 95742, 46918, '2024-08-31 07:35:30'),
(20, '1234545', 'ID2rHuQ68D1uNS5E8tfyYJ2BgZN4UMpGoHUTGYD6pHw=', 'o2NmbXRmcGFja2VkZ2F0dFN0bXSiY2FsZzkBAGNzaWdZAQAZvVCgWhHrQ071Z25WONHyNJwLMgic0O+ewA6hXwnDZ/bLDG69zOM3URWHoFvt1t5Lvj0/1qK7480WDPDA5VbQlPavVED943u/hEeMHFEk51ZOPE3FtSpTlFFKDVs/oiI5BJDlw461P4rAHLPThjGmDtQeKyKhQn7/abKOWf07JUpI3OsslDlb68K+DvTH1FGL/LXNTpvlScZ8d4rb8XJ47wUo/rsZVJdLXn6/LhE0DmYC9InQXpontlIYEaSBbSUpbCfwIKZsT8lP0oZp04sBWoFYjK66DhZw/ASHaKSGyJGrVeWVHL/ajHzv0CXisGVOjT1DFW8GkUaQJFfKjqgTaGF1dGhEYXRhWQFnSZYN5YgOjGh0NBcPZHZgW4/krrmihjLHmVzzuoMdl2NFAAAAAGAosBex1EwCtLOvza/Ja7IAICA9qx7kOvA9bjUuRPLX8mCdgYGTeFDKRqB1ExmA+qR8pAEDAzkBACBZAQDGSQ7+YZacKjLaX6diNGgl5sB73PUeFr0aA8MoW/RNB+km7j3bY7vnVKEKTgFnVr7XS40JpWsEpllSuYLSpS1uzwLG9n+Qgw4hUOoeBtXaCv7cuyuJHgW7miBglydBkQM2ND5Jw07+D4DCZXTAoRF/ClBVJ3HQL1PYjxTgkXZYGM52A/STupa3hqrmqZe9IRe5iQTHaZ63NBUiTS6WfbcljDeRYmLzJTdfUt5+ItNaMI3MonPRd7AswJqDmT+6pJ5YZauyOzn4SzthoHo72FNsas6lPWSRH5kmJpTZVt4w0Ijr0DF2Y3frTutZAjsKgPlPI8EojkOzyg8UC24tUkLBIUMBAAE=', 'eyJ0eXBlIjoid2ViYXV0aG4uY3JlYXRlIiwiY2hhbGxlbmdlIjoiSlIzU1BneEZtVHF5bVNqM09GTHUzQW8yVGs5ZnVhQy1JdjBDcERMNVdXOCIsIm9yaWdpbiI6Imh0dHA6Ly9sb2NhbGhvc3QiLCJjcm9zc09yaWdpbiI6ZmFsc2V9', '$2y$10$aLVyVdeWX8mOFXYlTC3xZehbY2gXaLj9/LIpZzBP1/iOEmtr865wC', 'Juan', 'Tamad', 'elisam@gmail.com', '09365220532', 'Caingin', 44423, 10750, '2024-08-31 10:01:01');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_companies`
--
ALTER TABLE `tbl_companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_coordinators`
--
ALTER TABLE `tbl_coordinators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_programs`
--
ALTER TABLE `tbl_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_requirements`
--
ALTER TABLE `tbl_requirements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_timelogs`
--
ALTER TABLE `tbl_timelogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
