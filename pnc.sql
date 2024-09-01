-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2024 at 03:10 AM
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
  `longitude` decimal(9,6) NOT NULL,
  `latitude` decimal(9,6) NOT NULL,
  `time_in` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `credential_id` text NOT NULL,
  `attestation_object` text NOT NULL,
  `client_data_json` text NOT NULL,
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
(6, 'SY2024-1001', '4EoMYK2Qyt7INHtFEdV6JDVXBV6DG/mnMh/PpBXdM+o=', 'o2NmbXRmcGFja2VkZ2F0dFN0bXSiY2FsZzkBAGNzaWdZAQCpG0PkVO2XY/z/yBnJ9QKVNIWbmms6DaM5ZVSvqDcaNGBoxL4AoP7bjZgGlWqwikbHc9n7aYNSg4sL6gdjtCFHCm0MIYcKwkzLl8BJUI0HWW4y0FHNPzFmKvSAhfYx/kH6/EQJTZnprRZTY2pK7lhjmqM7Zk1SRZL059f2BARvDRzZmUol18AcuI58D+UInD5GP0aKFOVXNVEsdlCWmAKzLq1U3sDfOougSlZiwBseMoh6y9wDMrhXTeD34OdkzbCXbJdjPGPHmGM9RZi9hA1GYb7Mc7QWxNlUAgwmpBa8P68+xK3mpOfpxW+a2YaF/KUOxsOmy9EXBHBxLrU8sirDaGF1dGhEYXRhWQFnSZYN5YgOjGh0NBcPZHZgW4/krrmihjLHmVzzuoMdl2NFAAAAAGAosBex1EwCtLOvza/Ja7IAIOBKDGCtkMreyDR7RRHVeiQ1VwVegxv5pzIfz6QV3TPqpAEDAzkBACBZAQCuk2KRdnnuIGOdww98xxnhYJPtnNidijSo+MVsN2imugdm+8+8CpY+zzllWEXUoNGRNXTLKMt2BO53mBiVHqu9S6MXqF66iDzn+hUioQHd6FgUABNgELmuJHraVk5g9xLqrI9roehfZx3IVgxsPi234eFnkwTl04XwAHImIRI6HHeQidEcYYc/RIKdyAp7H2W50sEJCxTkSbPnyRhM8zUonkUCLeYPqVoe9KPK8jTaaoysp44nU8I5BuHb0IgonYghi037edCmVzKGIfxCnGPSl9yTq835rj8bgFgP4LPlh6Yf9c2+IyqZKLydXIktlzyB4S3tOKFtF5yQhDZHfxOZIUMBAAE=', 'eyJ0eXBlIjoid2ViYXV0aG4uY3JlYXRlIiwiY2hhbGxlbmdlIjoiT2RfZlAzRWFRVlAxN2hKdnhUMWhFa3EwZlJCdFdXSjJRc1BQY0dHX2lrWSIsIm9yaWdpbiI6Imh0dHA6Ly9sb2NhbGhvc3QiLCJjcm9zc09yaWdpbiI6ZmFsc2UsIm90aGVyX2tleXNfY2FuX2JlX2FkZGVkX2hlcmUiOiJkbyBub3QgY29tcGFyZSBjbGllbnREYXRhSlNPTiBhZ2FpbnN0IGEgdGVtcGxhdGUuIFNlZSBodHRwczovL2dvby5nbC95YWJQZXgifQ==', '1234', 'Jimmy', 'Camangon', 'jimmycamangon7@gmail.com', '09365220532', 'sa bahay mo'),
(7, 'SY2024-1002', 'AWU9DpXxbpZEsoTQuNtO62oCnhzoZude5EcinWdEmVij67rTZ+uSGSh6ZIcCGSEyBCWOapPTNSeuOvEW2bKNPRc=', 'o2NmbXRkbm9uZWdhdHRTdG10oGhhdXRoRGF0YVjFSZYN5YgOjGh0NBcPZHZgW4/krrmihjLHmVzzuoMdl2NFAAAAAAAAAAAAAAAAAAAAAAAAAAAAQQFlPQ6V8W6WRLKE0LjbTutqAp4c6GbnXuRHIp1nRJlYo+u602frkhkoemSHAhkhMgQljmqT0zUnrjrxFtmyjT0XpQECAyYgASFYIAxjq4Ll5tCIY75aa+93Cs8fdhSvVBr3wevvJKh29F5xIlggSl2jgAckZ9LjVDnesB4W+SHaz9xKzYbvD+4Y/tKhYCk=', 'eyJ0eXBlIjoid2ViYXV0aG4uY3JlYXRlIiwiY2hhbGxlbmdlIjoiZlE4UHRjZ3NJWDVOWjFOWG0wRWNnTUpSTmJHakx6Ny1qMVowUnctdGZQdyIsIm9yaWdpbiI6Imh0dHA6Ly9sb2NhbGhvc3QiLCJjcm9zc09yaWdpbiI6ZmFsc2V9', '1234', 'Jimmy', 'Camangon', 'jimmycamangon7@gmail.com', '09365220532', 'sa bahay mo');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
