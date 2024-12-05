-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2024 at 09:13 PM
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
-- Database: `despised`
--

-- --------------------------------------------------------

--
-- Table structure for table `beats`
--

CREATE TABLE `beats` (
  `beat_id` int(11) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `audio_path` varchar(255) NOT NULL,
  `beat_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beats`
--

INSERT INTO `beats` (`beat_id`, `rate`, `image_path`, `type`, `audio_path`, `beat_name`) VALUES
(1, 19.06, 'images/1.jpg', 'Romantic', 'beats/1.mp3', 'Vintage Loops'),
(2, 8.86, 'images/2.jpg', 'Romantic', 'beats/2.mp3', 'Dreamy Bassline'),
(3, 10.40, 'images/3.jpg', 'Sad', 'beats/3.mp3', 'Boom Bap Vibes'),
(4, 12.40, 'images/4.jpg', 'Hip Hop', 'beats/4.mp3', 'Melancholy Tunes'),
(5, 11.80, 'images/5.jpg', 'Hip Hop', 'beats/5.mp3', 'Hip Hop Hustle'),
(6, 13.75, 'images/6.jpg', 'Romantic', 'beats/6.mp3', 'Chill Hop Mix'),
(7, 16.10, 'images/7.jpg', 'Hip Hop', 'beats/7.mp3', 'Tranquil Beats'),
(8, 15.89, 'images/8.jpg', 'Hip Hop', 'beats/8.mp3', 'Urban Symphony'),
(9, 14.54, 'images/9.jpg', 'Hip Hop', 'beats/9.mp3', 'Sad Strings'),
(10, 16.84, 'images/10.jpg', 'Romantic', 'beats/10.mp3', 'Romantic Groove'),
(11, 69.00, 'images/download.png', 'Romantic', 'beats/WhatsApp Audio 2024-12-04 at 00.47.10.mp3', 'Cute Volume 1');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_record`
--

CREATE TABLE `purchase_record` (
  `purchase_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `beat_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `purchased_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_record`
--

INSERT INTO `purchase_record` (`purchase_id`, `user_id`, `beat_id`, `quantity`, `total_price`, `purchased_at`) VALUES
(1, 1, 11, 2, 138.00, '2024-12-04 01:06:58'),
(2, 1, 1, 1, 19.06, '2024-12-04 10:17:13');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `beat_id` int(11) NOT NULL,
  `review_text` text NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `beat_id`, `review_text`, `rating`, `created_at`) VALUES
(2, 2, 7, 'Very soothing and tranquil beats as the name suggests', 5, '2024-12-04 10:27:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `registered_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `registered_at`) VALUES
(1, 'sharmapranav5788@gmail.com', '$2y$10$nzrc/GyOqg9/GxHJLa9xeuGI5W207fO.sW5U9WYBSbjXJlFf2GYjS', '2024-12-04 00:18:20'),
(2, 'rvirdi202@gmail.com', '$2y$10$0q3dqZegzkS8pv92jrQ/5OsZaQDkBrU4QXQVfYx82IdoMS0QkQ3hO', '2024-12-04 10:26:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `beats`
--
ALTER TABLE `beats`
  ADD PRIMARY KEY (`beat_id`);

--
-- Indexes for table `purchase_record`
--
ALTER TABLE `purchase_record`
  ADD PRIMARY KEY (`purchase_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `beat_id` (`beat_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `beat_id` (`beat_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `beats`
--
ALTER TABLE `beats`
  MODIFY `beat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `purchase_record`
--
ALTER TABLE `purchase_record`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `purchase_record`
--
ALTER TABLE `purchase_record`
  ADD CONSTRAINT `purchase_record_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_record_ibfk_2` FOREIGN KEY (`beat_id`) REFERENCES `beats` (`beat_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`beat_id`) REFERENCES `beats` (`beat_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
