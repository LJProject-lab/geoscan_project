-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 28, 2024 at 08:59 AM
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
-- Database: `pnc`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_timelogs`
--

CREATE TABLE `tbl_timelogs` (
  `id` int(11) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `longitude` float NOT NULL,
  `latitude` float NOT NULL,
  `time_in` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_timelogs`
--

INSERT INTO `tbl_timelogs` (`id`, `student_id`, `email`, `longitude`, `latitude`, `time_in`) VALUES
(12, 'SY2004-1001', 'jimscams6@gmail.com', 121.095, 14.3123, '2024-07-28 03:23:47');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `displayName` varchar(100) DEFAULT NULL,
  `credentialId` varbinary(255) NOT NULL,
  `publicKey` varbinary(1024) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `student_id`, `email`, `password`, `displayName`, `credentialId`, `publicKey`, `registered_at`) VALUES
(28, 'SY2004-1001', 'jimscams6@gmail.com', NULL, 'SY2004-1001', 0x01df08f4721a923db8a0badc4d9364cfb6a70f5ca6e7c1528322d1d0efe8b1259a4c02aa789e36f7ac1be4088acb448ef763751f3bde95efd6e792537988bce193, 0xa363666d74646e6f6e656761747453746d74a068617574684461746158c549960de5880e8c687434170f6476605b8fe4aeb9a28632c7995cf3ba831d9763450000000000000000000000000000000000000000004101df08f4721a923db8a0badc4d9364cfb6a70f5ca6e7c1528322d1d0efe8b1259a4c02aa789e36f7ac1be4088acb448ef763751f3bde95efd6e792537988bce193a501020326200121582007d755cca48863a38d4a0640349d09ca739b0b2fcefd460fb5a978cd09f683fa225820729e179eb511533b4c036e87a9035fb44eff4baf59a7a3abf408631f9dc8bbf8, '2024-07-28 03:19:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `credential_id` text NOT NULL,
  `attestation_object` text NOT NULL,
  `client_data_json` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `student_id`, `credential_id`, `attestation_object`, `client_data_json`) VALUES
(6, 'SY2024-1001', 'ASRhDdnZkrGn4rzt5HHpkfr/AMmkUenlYPUKzfIjyUWKWvZvni4zyjRJviakTL0EujOVFp//s0bmC5PP3BNzcjA=', 'o2NmbXRkbm9uZWdhdHRTdG10oGhhdXRoRGF0YVjFSZYN5YgOjGh0NBcPZHZgW4/krrmihjLHmVzzuoMdl2NFAAAAAAAAAAAAAAAAAAAAAAAAAAAAQQEkYQ3Z2ZKxp+K87eRx6ZH6/wDJpFHp5WD1Cs3yI8lFilr2b54uM8o0Sb4mpEy9BLozlRaf/7NG5guTz9wTc3IwpQECAyYgASFYIMFuij/1GnvShJrz85knk6OcweQrvZvzAMhZigPzKQPKIlggKaLN/BxvJ+RByDjuoWEMyFi2/6IDv7Jy4XebYW6OCwI=', 'eyJ0eXBlIjoid2ViYXV0aG4uY3JlYXRlIiwiY2hhbGxlbmdlIjoic3Zoc1VNYkh4Z1d3Q0tkNVNKamRzdTdnTGFnYTJOMTRBSm9EVkRxMnlWQSIsIm9yaWdpbiI6Imh0dHA6Ly9sb2NhbGhvc3QiLCJjcm9zc09yaWdpbiI6ZmFsc2V9'),
(7, 'SY2024-1002', 'AfIkOKb3JIlpg7CCOYmNdHv4tDcUE7ZMjOBG9MxhJofCrocm2EOxXFg9+IBMxtQNwNyaweYWw8pYENrCEJDZDQs=', 'o2NmbXRkbm9uZWdhdHRTdG10oGhhdXRoRGF0YVjFSZYN5YgOjGh0NBcPZHZgW4/krrmihjLHmVzzuoMdl2NFAAAAAAAAAAAAAAAAAAAAAAAAAAAAQQHyJDim9ySJaYOwgjmJjXR7+LQ3FBO2TIzgRvTMYSaHwq6HJthDsVxYPfiATMbUDcDcmsHmFsPKWBDawhCQ2Q0LpQECAyYgASFYINWaCaibO1/Ybq3Svyv8fO2vNPT+1GM3io2l7Ud+sLNOIlggku5myKpA3VH0jivcfw5hXSMnzrj21YGgZhrTPtBV6mI=', 'eyJ0eXBlIjoid2ViYXV0aG4uY3JlYXRlIiwiY2hhbGxlbmdlIjoiaEJtYXoxNVlkRjFPYXgyNVQ1UVRTMEpnbHpOTFBicHcyNS1hOUtqOHpGayIsIm9yaWdpbiI6Imh0dHA6Ly9sb2NhbGhvc3QiLCJjcm9zc09yaWdpbiI6ZmFsc2V9'),
(8, 'SY2024-1003', 'AUAIdkSgcappGiRoyyXg0AJXbsL4g06fpVMbGpELLrazmKd0eaQYimw0ZPDmBCvCZKHYUXaHGd37hnYKRhKZuWI=', 'o2NmbXRkbm9uZWdhdHRTdG10oGhhdXRoRGF0YVjFSZYN5YgOjGh0NBcPZHZgW4/krrmihjLHmVzzuoMdl2NFAAAAAAAAAAAAAAAAAAAAAAAAAAAAQQFACHZEoHGqaRokaMsl4NACV27C+INOn6VTGxqRCy62s5indHmkGIpsNGTw5gQrwmSh2FF2hxnd+4Z2CkYSmblipQECAyYgASFYIM7Whjlh4Z6MjusHRRaZEs0uZJeXWGO01towONhQvdIqIlggUQkjN2zOD41MszSVc4hcENuN3YJKK+uEzg0Vd7rXrnI=', 'eyJ0eXBlIjoid2ViYXV0aG4uY3JlYXRlIiwiY2hhbGxlbmdlIjoiLUNCNE9tMGs2blpwdkc2NWl1WGxGaXdtbmJKYW5takcxdVpDTW1LeTl4ZyIsIm9yaWdpbiI6Imh0dHA6Ly9sb2NhbGhvc3QiLCJjcm9zc09yaWdpbiI6ZmFsc2UsIm90aGVyX2tleXNfY2FuX2JlX2FkZGVkX2hlcmUiOiJkbyBub3QgY29tcGFyZSBjbGllbnREYXRhSlNPTiBhZ2FpbnN0IGEgdGVtcGxhdGUuIFNlZSBodHRwczovL2dvby5nbC95YWJQZXgifQ==');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_timelogs`
--
ALTER TABLE `tbl_timelogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`student_id`),
  ADD KEY `time_in` (`time_in`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_timelogs`
--
ALTER TABLE `tbl_timelogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
