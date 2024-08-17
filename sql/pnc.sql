-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2024 at 09:25 PM
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
(17, 'SY2024-1003', '', 'time_in', '2024-08-17 18:53:26', 121.030600, 14.406900, '');

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
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `student_id`, `credential_id`, `attestation_object`, `client_data_json`, `pin`, `firstname`, `lastname`, `email`, `phone`, `address`) VALUES
(4, 'SY2024-1001', 'AU0mS/POYqn/ov3XmuzI1Tbn1PpOMl9ghT7GAFrxjB5DyUuaOaUUVd7iwVCZL9fL5C9njYcXOIc4HC4HXbLEB+8=', 'o2NmbXRkbm9uZWdhdHRTdG10oGhhdXRoRGF0YVjFSZYN5YgOjGh0NBcPZHZgW4/krrmihjLHmVzzuoMdl2NFAAAAAAAAAAAAAAAAAAAAAAAAAAAAQQFNJkvzzmKp/6L915rsyNU259T6TjJfYIU+xgBa8YweQ8lLmjmlFFXe4sFQmS/Xy+QvZ42HFziHOBwuB12yxAfvpQECAyYgASFYIHrsbAdB8/XEB/BV8Zy9p6oj/hUsZSN3uaMX+DPlniJsIlgge9XJICBOYyls5BAd2a2pr48Q7Zm5DLjAhRJ2Z4k/FlQ=', 'eyJ0eXBlIjoid2ViYXV0aG4uY3JlYXRlIiwiY2hhbGxlbmdlIjoiWFduLVZ1MzFHb0N3NWNTY2JDSC1hWF9kRnRCLWI5NVFfVGZDclFhR3dMVSIsIm9yaWdpbiI6Imh0dHA6Ly9sb2NhbGhvc3QiLCJjcm9zc09yaWdpbiI6ZmFsc2V9', '1234', 'Jimmy', 'Camangon', 'jimmycamangon7@gmail.com', '09365220532', 'sa bahay mo'),
(5, 'SY2024-1002', NULL, NULL, NULL, '1234', 'Jimmy', 'Camangon', 'jimmycamangon30@gmail.com', '09365220532', 'sa bahay mo'),
(6, 'SY2024-1003', 'QJKr1O9Gdygpm7Q/j8zgZ6nSH30UeEujrStjGx3pQ40=', 'o2NmbXRmcGFja2VkZ2F0dFN0bXSiY2FsZzkBAGNzaWdZAQCcYKggZvt/dVs9TKNyHU56eyfOp9Lb/1jpBTDZQzR341rKb4biSZdb7+beSgiPCTeHTh/h+W5ysnhA7IDVWA8km7uyF6Nv27kdZp8dEeo6/3irHqt28m1DtWsgoCRrE2Gyo0KoqKZv1D70tf8AqCTN/G3MPchvz30sLPdQZY52X/VUvxbzQU6qcN4uASjasxNLm65IaFyY6KXP/RtUEEzLT+rJTgyMOaBnPAcC4ZIruySI0moQYBBCHBVSJfZCrlGD8oOBFQmh8bdbDKH5b0rweHNcggHOrW35emZk5EjWqmwOW1d8bVorHgq4RBP1/yTmtm6PCFQaT6OgXutDoXfuaGF1dGhEYXRhWQFnSZYN5YgOjGh0NBcPZHZgW4/krrmihjLHmVzzuoMdl2NFAAAAAGAosBex1EwCtLOvza/Ja7IAIECSq9TvRncoKZu0P4/M4Gep0h99FHhLo60rYxsd6UONpAEDAzkBACBZAQDnzWrVcGcbvKdvoqDiLeCkDcfXkHhzXhQibPjke3KEIvE0TbfIdd0iIpYAKzCGqe150z3iMoRwilS/Y3OeJyFqHrodoV5q4jBgQwnUV/niga9vlCLobCYB53c9Mc+SvqZR5DeQdsu8fFn/rBkO6q5TcuTTogUAjqtKzWR/q56riiu6Xubwb70Nq2hUYuCV7hcbJWGMwAfrwSzk7hhyY6GBUm/2RcOaprFmWc5a6uEUQKO1SxHhh2j4APrUY7mZGxQ8w5I8d/abOIg7ghdNX/RgoZ5sf7eyzD6hllm0EEpTvB36sOuZGSBbVcYnIUWfJ8lL8jcjPJRrqh4Lb3wO1nDJIUMBAAE=', 'eyJ0eXBlIjoid2ViYXV0aG4uY3JlYXRlIiwiY2hhbGxlbmdlIjoiamdGT3ZWOUFRalBQTkxKUDJSQjQ5UGlvT2dSd1hGcHpMNkphTmM3S1RXNCIsIm9yaWdpbiI6Imh0dHA6Ly9sb2NhbGhvc3QiLCJjcm9zc09yaWdpbiI6ZmFsc2V9', '$2y$10$YUphQbwvLSeUC832PI.nMOQTEl2CPsUqCXvdF6VJCp1dQ5943dQwi', 'Jimmy', 'Camangon', 'jimscams6@gmail.com', '09365220532', 'Test');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `tbl_timelogs`
--
ALTER TABLE `tbl_timelogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
