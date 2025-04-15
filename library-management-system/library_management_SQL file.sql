-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 14, 2025 at 04:35 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE IF NOT EXISTS `books` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `isbn` (`isbn`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `isbn`, `genre`, `quantity`, `created_at`) VALUES
(1, 'The Great Gatsby', 'F. Scott Fitzgerald', '978-074327300009', 'Technology', 11, '2025-02-02 03:44:25'),
(2, 'To Kill a Mockingbird', 'Harper Lee', '978-0061120084', 'Fiction', 2, '2025-02-02 03:44:25'),
(3, '1985', 'George Orwell', '978-0451524935', 'Dystopian', 7, '2025-02-02 03:44:25'),
(4, 'Pride and Prejudice', 'Jane Austen', '978-0141439518', 'Romance', 8, '2025-02-02 03:44:25'),
(5, 'The Catcher in the Rye', 'J.D. Salinger', '978-0316769488', 'Classic', 3, '2025-02-02 03:44:25'),
(6, 'Physics Book', 'PN Okeke', '	978-0743273566', 'Pysics', 1, '2025-02-02 04:37:39'),
(8, 'security operations', 'felix', '098732456', 'technology', 4, '2025-03-02 18:21:38'),
(9, 'Macmalian Commic', 'Macjobbs', '12345678', 'Fictions', 12, '2025-03-03 10:29:15'),
(10, 'Visual Studio Analysis', 'Mac and James', '22788999990000', 'technology', 5, '2025-04-10 10:24:10'),
(11, 'Fiction of the Night', 'McAnthony Pauline', '1009958774', 'Fictions', 9, '2025-04-10 11:44:47'),
(13, 'horseman', 'Kisinger blackman', '9988456730098', 'Classic', 7, '2025-04-10 16:58:53');

-- --------------------------------------------------------

--
-- Table structure for table `book_loans`
--

DROP TABLE IF EXISTS `book_loans`;
CREATE TABLE IF NOT EXISTS `book_loans` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `book_id` int NOT NULL,
  `borrow_date` date NOT NULL,
  `due_date` date NOT NULL,
  `returned` tinyint(1) DEFAULT '0',
  `return_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `book_id` (`book_id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `book_loans`
--

INSERT INTO `book_loans` (`id`, `user_id`, `book_id`, `borrow_date`, `due_date`, `returned`, `return_date`) VALUES
(1, 2, 1, '2023-10-01', '2023-10-08', 0, NULL),
(2, 2, 3, '2023-10-02', '2023-10-09', 1, '2023-10-05'),
(3, 4, 6, '2025-02-02', '2025-02-09', 1, '2025-02-03'),
(4, 4, 1, '2025-02-02', '2025-02-09', 1, '2025-02-03'),
(5, 4, 4, '2025-02-02', '2025-02-09', 1, '2025-02-03'),
(6, 4, 5, '2025-02-02', '2025-02-09', 1, '2025-02-03'),
(7, 4, 2, '2025-02-02', '2025-02-09', 1, '2025-02-03'),
(8, 4, 5, '2025-02-03', '2025-02-10', 1, '2025-02-23'),
(9, 4, 6, '2025-02-05', '2025-02-12', 1, '2025-02-23'),
(10, 4, 5, '2025-02-23', '2025-03-02', 0, NULL),
(11, 6, 1, '2025-03-02', '2025-03-09', 1, '2025-03-02'),
(12, 6, 4, '2025-03-02', '2025-03-09', 1, '2025-04-10'),
(13, 6, 6, '2025-03-02', '2025-03-09', 1, '2025-04-10'),
(14, 12, 8, '2025-03-03', '2025-03-10', 1, '2025-03-03'),
(15, 12, 8, '2025-03-03', '2025-03-10', 1, '2025-03-03'),
(16, 12, 1, '2025-03-03', '2025-03-10', 1, '2025-03-03'),
(17, 12, 6, '2025-03-03', '2025-03-10', 1, '2025-03-03'),
(18, 12, 1, '2025-03-03', '2025-03-10', 1, '2025-03-03'),
(19, 12, 6, '2025-03-03', '2025-03-10', 1, '2025-03-03'),
(20, 6, 9, '2025-04-10', '2025-04-17', 1, '2025-04-10'),
(21, 6, 5, '2025-04-10', '2025-04-17', 1, '2025-04-10'),
(22, 6, 3, '2025-04-10', '2025-04-17', 1, '2025-04-10'),
(23, 6, 10, '2025-04-10', '2025-04-17', 1, '2025-04-10'),
(24, 6, 8, '2025-04-10', '2025-04-17', 1, '2025-04-10'),
(25, 6, 11, '2025-04-10', '2025-04-17', 1, '2025-04-10'),
(26, 6, 10, '2025-04-10', '2025-04-17', 1, '2025-04-10'),
(27, 6, 9, '2025-04-10', '2025-04-17', 1, '2025-04-12'),
(28, 6, 10, '2025-04-10', '2025-04-17', 1, '2025-04-10'),
(29, 18, 4, '2025-04-10', '2025-04-17', 0, NULL),
(30, 6, 5, '2025-04-10', '2025-04-17', 1, '2025-04-12'),
(31, 6, 11, '2025-04-10', '2025-04-17', 1, '2025-04-14'),
(32, 6, 10, '2025-04-12', '2025-04-19', 0, NULL),
(33, 6, 1, '2025-04-12', '2025-04-19', 1, '2025-04-14'),
(34, 6, 1, '2025-04-14', '2025-04-21', 1, '2025-04-14'),
(35, 6, 1, '2025-04-14', '2025-04-21', 1, '2025-04-14'),
(36, 6, 2, '2025-04-14', '2025-04-21', 1, '2025-04-14'),
(37, 6, 2, '2025-04-14', '2025-04-21', 0, NULL),
(38, 6, 4, '2025-04-14', '2025-04-21', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Power Admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-02-02 03:44:25'),
(2, 'Regular User', 'user@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '2025-02-02 03:44:25'),
(3, 'Paul Okereke', 'paulokereke@outlook.com', '$2y$10$bZFoOzHcIGZROg2L9JmRR.xDlemosEMktYuYLlSQHTgEGNN/eUXmu', 'admin', '2025-02-02 04:06:27'),
(4, 'Seun Adebayo', 'seun@yahoo.com', '$2y$10$1r52M1FE1pyQtaM4mwQHJOX44mKTVAvia1qZ7Km6qAy6jb4Uqmq6.', 'user', '2025-02-02 05:08:37'),
(5, 'Tolu Adeogun', 'tolu@yahoo.com', '$2y$10$U0NTe9PMuUoJ9EMrfhRglO4oHAPjltaPs.wyrAp7ssBoib8Ra05wW', 'user', '2025-02-02 05:51:55'),
(6, 'Femi', 'femi@yahoo.com', '$2y$10$.zPnVUxZ1slPKNW/f5CdwO2.9iBBHWJZsqbBXyk08M40IJGJlABTu', 'user', '2025-03-02 17:39:12'),
(7, 'Seyi', 'seyi@yahoo.com', '$2y$10$3suiaIqPhDcu0/PGzRX7dOmWQvAijW462g9UhQHlEuNDI5wJT7DQ.', 'admin', '2025-03-02 17:44:52'),
(8, 'osa oghonna', 'osas@hotmail.com', 'Treasure321%', 'admin', '2025-03-02 18:30:08'),
(10, 'Treasure Uwagboi', 'Tuwagboi@yahoo.com', '$2y$10$M8IF9Uqy6/ukl6AL42BYq.ZJPDaaQT8ZqXVyCGLws9wJ0hyUrw8Kq', 'admin', '2025-03-03 10:17:46'),
(13, 'Felix Agbor', 'felixagbor123@gmail.com', 'Treasure321%', 'admin', '2025-04-10 11:51:05'),
(12, 'Gerald Nmoye', 'geraldn@yahoo.com', '$2y$10$591XEcIjEaH3w4Szw2TFvuQOldfw7J1OGgLvJgA0uW3r6olok9e3C', 'user', '2025-03-03 11:19:12'),
(14, 'Omunuwa Isekii', 'omowaeche@hotmail.com', 'omowaecheYz9$%', 'user', '2025-04-10 11:56:18'),
(15, 'Festus John', 'festusjohn456@outlook.com', 'femolike905', 'user', '2025-04-10 12:03:59'),
(16, 'OMOBODE AGBONIFO', 'omobodebasme@gmail.com', '9982%$axb', 'user', '2025-04-10 15:19:10'),
(17, 'HELENJOHN', 'branch7649@hotmail.com', 'hbfhdfdhhkdfbdh', 'user', '2025-04-10 17:04:32'),
(20, 'Felix Agbor', 'felixagbor@gmail.com', '123', 'admin', '2025-04-14 14:12:50'),
(21, 'Felix Agbor', 'felixagbor111@gmail.com', '$2y$10$3z8EniAQ1ZTCT99yns4vHeddhBrnZWII8nILhUN.ziXI6JLXVxE.6', 'admin', '2025-04-14 14:16:25'),
(19, 'fredrick okonuwa', 'lagboja@hotmail.com', '$2y$10$7PH4Gunue2ze2TnisO5C/.QJa3rZYO3rA0GMpD4nDwc3fnPoSUaUe', 'user', '2025-04-12 10:05:21'),
(22, 'Segun Alabi', 'segun@gmail.com', '$2y$10$3a06f8prGkXN5WVouJ4dIepnirnfVWnKj4SDq/dhE4yifQVenyklu', 'admin', '2025-04-14 16:31:19');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
