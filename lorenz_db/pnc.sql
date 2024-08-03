-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2024 at 01:47 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `pin` char(4) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_id`, `pin`, `first_name`, `last_name`, `email`, `phone`, `address`) VALUES
(1, '22-2452', '1234', ' Lorenz', ' Villalobos', 'lorenz@gmail.com', '1234567', '#121132 CSRL');

-- --------------------------------------------------------

--
-- Table structure for table `time_logs`
--

CREATE TABLE `time_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `action` enum('time_in','time_out') NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_records`
--

CREATE TABLE `time_records` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `pin` char(4) NOT NULL,
  `type` enum('time_in','time_out') NOT NULL,
  `timestamp` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `photo` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `displayName` varchar(100) DEFAULT NULL,
  `credentialId` varbinary(255) NOT NULL,
  `publicKey` varbinary(1024) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `displayName`, `credentialId`, `publicKey`, `registered_at`) VALUES
(4, 'Lorenz', 'love.lavi0815@gmail.com', 'Lorenz', 0x01843d099a836d0defff6bf9a8e2e638e740c0b8fc32a337b45307931d33645359ab66ee4cdc15ae7066a08d9299b02d155300dfb3b535f100a787f3f1aef28460, 0xa363666d74646e6f6e656761747453746d74a068617574684461746158c549960de5880e8c687434170f6476605b8fe4aeb9a28632c7995cf3ba831d9763450000000000000000000000000000000000000000004101843d099a836d0defff6bf9a8e2e638e740c0b8fc32a337b45307931d33645359ab66ee4cdc15ae7066a08d9299b02d155300dfb3b535f100a787f3f1aef28460a5010203262001215820d21d557151a40accb084079295c57c3817b96a50c493635b37a94815a535397a22582037a9ef270f95a52794ab412377585de8d4556a37ce207824759f3b1ea8d4c67b, '2024-07-25 11:43:00'),
(5, 'Admin', 'lorenz@gmail.com', 'Admin', 0x0185938091d9c6aab12fa5eab5c3106b7d1da610d600438c3541345a2fd93b9d7ea1e0b4f964d8b3780134f022d52af6700eda392db0c840072746df7befe22f94, 0xa363666d74646e6f6e656761747453746d74a068617574684461746158c549960de5880e8c687434170f6476605b8fe4aeb9a28632c7995cf3ba831d976345000000000000000000000000000000000000000000410185938091d9c6aab12fa5eab5c3106b7d1da610d600438c3541345a2fd93b9d7ea1e0b4f964d8b3780134f022d52af6700eda392db0c840072746df7befe22f94a50102032620012158205654fa25aa3e09e3adf82e4275e06c17a4e46dc82b218a1991d2611c20920ab6225820145f85a11ee0583c5e92a0f03f6eb2abec95e269dcad3ecda6a342602cb7ef3b, '2024-07-25 13:53:57'),
(6, 'Clinic', 'Quin@example.com', 'Clinic', 0x01dc360fd8accbf8c76fbf40890848a07a0fc83a327dcfc21160ceacfb2d94f4895623a03119f1fc0c1cefe33fa84fcbc5a5441ede10cecd7701f5d63e34ce9305, 0xa363666d74646e6f6e656761747453746d74a068617574684461746158c549960de5880e8c687434170f6476605b8fe4aeb9a28632c7995cf3ba831d9763450000000000000000000000000000000000000000004101dc360fd8accbf8c76fbf40890848a07a0fc83a327dcfc21160ceacfb2d94f4895623a03119f1fc0c1cefe33fa84fcbc5a5441ede10cecd7701f5d63e34ce9305a5010203262001215820dc9535e759ca780014c5ab625162056135fd342c3663025cb08ec36ee9b2b9f52258204fb0425bd35a9a8fd28d9d20b21f86e80fe7c986500e3b0f83db117abb0c241f, '2024-07-26 11:31:27');

-- --------------------------------------------------------

--
-- Table structure for table `users_faces`
--

CREATE TABLE `users_faces` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `face_descriptor` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_scan`
--

CREATE TABLE `users_scan` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fingerprint1` blob NOT NULL,
  `fingerprint2` blob NOT NULL,
  `fingerprint3` blob NOT NULL,
  `fingerprint4` blob NOT NULL,
  `fingerprint5` blob NOT NULL,
  `fingerprint6` blob NOT NULL,
  `credentialId` text NOT NULL,
  `publicKey` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_scan`
--

INSERT INTO `users_scan` (`id`, `username`, `email`, `fingerprint1`, `fingerprint2`, `fingerprint3`, `fingerprint4`, `fingerprint5`, `fingerprint6`, `credentialId`, `publicKey`) VALUES
(1, 'Admin', 'lorenz@gmail.com', 0x01d3f27a55ab3eee251d1bc2de579245bcaf1fc1c82816ecc7b859074475af92d71b85c32a5dc3d7f3d209322a13ced54819fb0027bc712fe89ec0820304fd8ea0, 0x01b4f54fe46cbec396b6e61047cb2cbd1675e0ecf4181769c4c6cb9f072c1923a39ce31c8e7dd8d29ad28aed0949b4acb65ce035d615d2f4de17d3780be8e47ec9, 0x01a49ae50ff2ad8db1dc970a8cdb44157f97bac5b5d6cbf7b7777fa0a61b73ad9bfb196b20b0f0a0e8397c832a5d9f2cb5ed7c7202e957af45b7d37afa696270fd, 0x013bc23a0a3ae1676ce4c377d4d77c625356c8cc4d07f3929afa3b358f50d10611c5d04b3cf663c7589d44fdd7501234bd75fd88a18f052d1d2bc3bbd31ea4464d, 0x01a93dbbefcae9092bc5855058ae616475bd7f5e0a26b923eeeb909072d8f63329a56e5679d9db54472a88da7a8af3a953313825219fe86bbe4658e40e16681865, 0x01cb922498e4439b94260b6adb4ad55a5b06fbba64b97be761924b4ebdaf56468f52f65b3e4ffc26638e5fb546effa763e720a1cd86ebcd321f0dcf8bd0ff39ef7, '??zU?>?%??W?E????(?ǸYDu?????*]????	2*??H?\0\'?q/???????,??O?l?Ö??G?,?u???i??˟,#????}?ҚҊ?	I???\\?5?????x??~?,???򭍱ܗ\n??D??ŵ????w??s???k ????9|?*]?,??|r?W?E??z?ibp?,;?:\n:?gl??w??|bSV??M????;5?P???K<?c?X?D??P4?u????-+û??FM,?=????	+ŅPX?adu?^\n&?#?됐r??3)?nVy??TG*??z???S18%!??k?FX?he,˒$??C??&j?J?Z[??d?{?a?KN??VF?R?[>O?&c?_?F??v>r\n?n??!???????', '?cfmtdnonegattStmt?hauthDataX?I?\r???ht4dv`[?䮹??2Ǚ\\????cE\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0A??zU?>?%??W?E????(?ǸYDu?????*]????	2*??H?\0\'?q/????????& !X ?`?̎???2KL?>???k?_cK?N??k??i\"X ?I?????̙?례?U????{???4Y5??A?,?cfmtdnonegattStmt?hauthDataX?I?\r???ht4dv`[?䮹??2Ǚ\\????cE\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0A??O?l?Ö??G?,?u???i??˟,#????}?ҚҊ?	I???\\?5?????x??~ɥ& !X x?^??d\0y>hi?քq+ڜ\r[f?/b??8t\"X ((Y?ſ?\0??%B????N?^p?sd?\\???b\r??,?cfmtdnonegattStmt?hauthDataX?I?\r???ht4dv`[?䮹??2Ǚ\\????cE\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0A???򭍱ܗ\n??D??ŵ????w??s???k ????9|?*]?,??|r?W?E??z?ibp??& !X 2?$I???c??:?me????r?e?Y??\"X ?B?????j??j&1PCU???bMF4?????,?cfmtdnonegattStmt?hauthDataX?I?\r???ht4dv`[?䮹??2Ǚ\\????cE\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0A;?:\n:?gl??w??|bSV??M????;5?P???K<?c?X?D??P4?u????-+û??FM?& !X ???Qܟ???k?![?q?????l? ??&??\"X ???????^<?ҡwH?KӉO??3;,?\n????,?cfmtdnonegattStmt?hauthDataX?I?\r???ht4dv`[?䮹??2Ǚ\\????cE\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0A?=????	+ŅPX?adu?^\n&?#?됐r??3)?nVy??TG*??z???S18%!??k?FX?he?& !X p;f???Ԝ??䠯?soq&?~/j????d?du?\"X ??(????=6??i7hй?\0?? ???v??} ?,?cfmtdnonegattStmt?hauthDataX?I?\r???ht4dv`[?䮹??2Ǚ\\????cE\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0A˒$??C??&j?J?Z[??d?{?a?KN??VF?R?[>O?&c?_?F??v>r\n?n??!????????& !X ????̀??M?	?Y?t?jDeS??˒i???\"X ??;sV4W?u\'?&???VN?h?CR????p?U?'),
(2, 'Clinic', 'Quin@example.com', 0x01d13e52839da3b8e4c263f700c74163a0b1d58343adb7a480db3df81aab2df7f8581984bc8933e6ecdb6eca6fac79047ec045f619ddbce4098379ff4d3d023ef6, 0x01f50c850b777698e6d2ee90543a20f10a4b90c459aba3fed735542d586691a79846ffb970a6ae1d8f23f5c4777a6b71fcb74feeb034605b4774fa45eef39f20ad, 0x0185b7892609aaabde043c67e84c9c157db162d75af2beb3104aaac183de8d990af66d22164285b8c03c7eecc6d626d9f0323dcdd5bc59797363c8448a64a4c89f, 0x01676ec3271d65c2cdca100620838705d1d398251e830658efe5f5342514430a4e7bddd0766efd2500e079332429e42742acb78c141b38f9b13bab5dcb28eab717, 0x0197d76dda2f8c52bb2a7a568f0d779ba838b23d3ec6b7eb9cb0fb7476b50dd665f4ee9aa6943ab0a744df1d2eacf4a4249073cbbc8ec3e00eaf5f3ae7a90db574, 0x012c63d16329ed7fda56ab0d8bd20764f40c2172d86f06e36dbe9c9b647060ad2eafd3fabda2144cddc740badcd9413670e850f482d26a5d65c675a041215ef2b0, '?>R??????c?\0?Ac??ՃC?????=?\Z?-??X???3???n?o?y~?E?ݼ?	?y?M=>?,??wv?????T: ?\nK??Y????5T-Xf???F??p???#??wzkq??O??4`[Gt?E??? ?,???&	???<g?L?}?b?Z???J???ލ?\n?m\"B???<~???&??2=?ռYysc?D?d?ȟ,gn?\'e??? ???Ә%?X???4%C\nN{??vn?%\0?y3$)?\'B???8??;?]?(??,??m?/?R?*zV?\nw??8?=>Ʒ뜰?tv?\n?e??:??D?.???$?s˼????_:??\n?t,,c?c)??V?\n??d?!r?o?m???dp`?.?????L??@???A6p?P???j]e?u?A!^??', '?cfmtdnonegattStmt?hauthDataX?I?\r???ht4dv`[?䮹??2Ǚ\\????cE\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0A?>R??????c?\0?Ac??ՃC?????=?\Z?-??X???3???n?o?y~?E?ݼ?	?y?M=>??& !X p?o\\?n?u*?e8}??zOl?s?v??n)???9\"X ?\"?w??B???8nRj?S?v?{?#N?$?\0$ ?,?cfmtdnonegattStmt?hauthDataX?I?\r???ht4dv`[?䮹??2Ǚ\\????cE\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0A??wv?????T: ?\nK??Y????5T-Xf???F??p???#??wzkq??O??4`[Gt?E??? ??& !X ????k?:z?tT?m??N?$.P?B?]mӅ??h\"X d???M??h????zdɪ?yxǅB?ׂ?m?b,?cfmtdnonegattStmt?hauthDataX?I?\r???ht4dv`[?䮹??2Ǚ\\????cE\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0A???&	???<g?L?}?b?Z???J???ލ?\n?m\"B???<~???&??2=?ռYysc?D?d?ȟ?& !X ?Ĺ,?Y~*??h?\'??f??<?#?qch??\"X ????3?\rp1E`????)??9??? ?W?q,?cfmtdnonegattStmt?hauthDataX?I?\r???ht4dv`[?䮹??2Ǚ\\????cE\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0Agn?\'e??? ???Ә%?X???4%C\nN{??vn?%\0?y3$)?\'B???8??;?]?(???& !X ?gY\"?y???;????b)<.g??ύ!?\"X \Z?e2???1?t???/?^Pr????r?0u/?h?,?cfmtdnonegattStmt?hauthDataX?I?\r???ht4dv`[?䮹??2Ǚ\\????cE\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0A??m?/?R?*zV?\rw??8?=>Ʒ뜰?tv?\r?e??:??D?.???$?s˼????_:??\r?t?& !X J&?\'h??\n??<??}X???????Rl6??L04\"X |??#????D[PmĆkY|[Go?}\"??\ru?,?cfmtdnonegattStmt?hauthDataX?I?\r???ht4dv`[?䮹??2Ǚ\\????cE\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0A,c?c)??V?\r??d?!r?o?m???dp`?.?????L??@???A6p?P???j]e?u?A!^???& !X ?Z?:????n??=Jzr?????^?Y???2\"X ?????2?%g??????2?t\ZJA\r?l7y?y');

-- --------------------------------------------------------

--
-- Table structure for table `user_time_tracking`
--

CREATE TABLE `user_time_tracking` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `action` enum('time_in','time_out') NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `time_logs`
--
ALTER TABLE `time_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `time_records`
--
ALTER TABLE `time_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users_faces`
--
ALTER TABLE `users_faces`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_scan`
--
ALTER TABLE `users_scan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user` (`username`,`email`);

--
-- Indexes for table `user_time_tracking`
--
ALTER TABLE `user_time_tracking`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `time_logs`
--
ALTER TABLE `time_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time_records`
--
ALTER TABLE `time_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users_faces`
--
ALTER TABLE `users_faces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_scan`
--
ALTER TABLE `users_scan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_time_tracking`
--
ALTER TABLE `user_time_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `time_logs`
--
ALTER TABLE `time_logs`
  ADD CONSTRAINT `time_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `time_records`
--
ALTER TABLE `time_records`
  ADD CONSTRAINT `time_records_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
