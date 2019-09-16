-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 17, 2019 at 05:09 PM
-- Server version: 5.7.17-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(6) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `parent` int(11) NOT NULL,
  `Ordering` int(11) DEFAULT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `parent`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(7, 'Hand Made', 'Hand Made Items', 0, 1, 0, 0, 0),
(8, 'Computers', 'Computers Items', 0, 2, 1, 1, 1),
(9, 'Cell Phones', 'Cell Phones items', 0, 3, 0, 0, 0),
(10, 'Clothing', 'Clothing and fashion', 0, 4, 0, 0, 0),
(11, 'Tools', 'home Tools', 0, 5, 0, 0, 0),
(12, 'Nokia', 'Nokia Phones', 9, 2, 0, 0, 0),
(14, 'Laptops', 'Laptops Very Nice ', 8, 1, 0, 0, 0),
(15, 'Hammers', 'Hammers Desc', 11, 1, 0, 0, 0),
(16, 'PCs', 'personal computers description', 8, 2, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `c_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `comment_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`c_id`, `comment`, `status`, `comment_date`, `item_id`, `user_id`) VALUES
(7, 'من رأي المنتج كويس جدا ربنا يزيدكم ', 1, '2019-03-04', 9, 24),
(8, 'Very good Item', 1, '2019-03-05', 12, 24),
(13, 'yeah it&#39;s very nice ', 1, '2019-03-14', 13, 24),
(14, 'انها رائعه :D ', 1, '2019-03-14', 13, 24),
(15, 'yeah i love you yeah i love you yeah i love you yeah i love you yeah i love you yeah i love you yeah i love youyeah i love you yeah i love you yeah i love you yeah i love you', 1, '2019-03-14', 8, 5),
(16, 'i love you my baby :) ', 1, '2019-03-14', 8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `Item_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Add_Date` date NOT NULL,
  `Country_Made` varchar(255) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Status` tinyint(11) NOT NULL,
  `Rating` smallint(11) NOT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT '0',
  `Cat_ID` int(11) NOT NULL,
  `Member_ID` int(11) NOT NULL,
  `tags` varchar(255) CHARACTER SET utf8 COLLATE utf8_german2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`Item_ID`, `Name`, `Description`, `Price`, `Add_Date`, `Country_Made`, `Image`, `Status`, `Rating`, `Approve`, `Cat_ID`, `Member_ID`, `tags`) VALUES
(8, 'Speaker  ', 'Very good Speaker  ', '10  ', '2019-03-09', 'China  ', '3842163_Speaker.jpg', 1, 0, 1, 8, 5, 'GAME , RPG, Multiplayer '),
(9, 'Yeti Blue Mic   ', 'Very good mic it\'s clear and very useful . you can use it to song or anything you need   ', '50   ', '2019-03-09', 'USA   ', '62677002_Yeti Blue Mic.jpg', 1, 0, 1, 8, 21, 'GAME , RPG , Online  '),
(10, 'Galaxy S10+  ', 'Samasung Phone  ', '2600  ', '2019-03-09', 'USA  ', '92047120_Galaxy S10+.jpg', 2, 0, 1, 9, 18, ' Online , Multiplayer , Mobail'),
(11, 'Magic Mouse  ', 'Apple Magic Mouse  ', '15  ', '2019-03-10', 'USA  ', '39221191_Magic Mouse.jpg', 1, 0, 1, 8, 24, 'Mouse , Technology , Computer'),
(12, 'Keyboard   ', 'awsome keyboard TO use   ', '100   ', '2019-03-10', 'USA   ', '81533814_keyboard.jpg', 2, 0, 1, 8, 24, 'GAME , Multiplayer  ,Computer'),
(13, 'T-Shirt  ', 'this is fashion te shirt , it\'s awsome  ', '5  ', '2019-03-14', 'Egypt  ', '8901977_t shirt.jpg', 2, 0, 1, 10, 24, ' Mohamed  , Discount  '),
(16, 'Suit ', 'nice suit and foshinable ', '25 ', '2019-03-14', 'USA ', '89880371_Suit.jpg', 2, 0, 1, 10, 24, ' clothing , suit , nice'),
(17, 'Hounds   ', 'bad game had been ever seen  ', '2  ', '2019-03-14', 'Turkish  ', '48675537_Hounds.jpg', 1, 0, 1, 8, 24, 'Aboelkassem , Discount  '),
(18, 'Abo elkassem ', 'Abo elkassem Abo elkassem Abo elkassem ', '1100 ', '2019-03-15', 'Egypt ', '', 2, 0, 1, 11, 24, ' Mohamed , Aboelkassem'),
(19, 'Router  ', 'a very nice router for wirless  ', '52  ', '2019-03-15', 'USA  ', '11129760_Router.jpg', 1, 0, 1, 8, 24, ' Discount  '),
(20, 'New Item     ', 'new description      ', '1     ', '2019-03-15', 'USA     ', '', 3, 0, 0, 7, 24, ' test , tagetest , testing'),
(21, 'Wooden Game ', 'a good wooden game ', '10 ', '2019-03-16', 'Egypt ', '66629028_wooden.jpg', 1, 0, 1, 7, 24, 'Mohamed , Aboelkassem , Discount '),
(22, 'Assassin Creed Unity ', 'Very bad game to play it ', '10 ', '2019-03-16', 'USA ', '20599365_Assassin creed.jpg', 1, 0, 1, 8, 24, 'GAME , RPG , Online , Multiplayer '),
(23, 'Wolf Team  ', 'Ps good game  ', '1  ', '2019-03-16', 'Turkish  ', '68435669_Wolf team.jpg', 1, 0, 1, 8, 24, 'GAME , RPG , Online , Multiplayer  '),
(28, 'Chair', 'Chair very simple', '50', '2019-03-16', 'Egypt', '58880615_product 1.jpg', 4, 0, 1, 7, 24, ' Online , Multiplayer , Mobail');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'To Identify User',
  `Username` varchar(255) NOT NULL COMMENT 'Username to login',
  `Password` varchar(255) NOT NULL COMMENT 'Password to login',
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `GroupID` int(11) NOT NULL DEFAULT '0' COMMENT 'Identify user permission 0 if user 1 if admin',
  `TrustStatus` int(11) NOT NULL DEFAULT '0' COMMENT 'Seller Rank if the seller is good make him trusted seller not thief',
  `RegStatus` int(11) NOT NULL DEFAULT '0' COMMENT 'User Approval 0 if not login or active in site',
  `Date` date NOT NULL,
  `avatar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `FullName`, `GroupID`, `TrustStatus`, `RegStatus`, `Date`, `avatar`) VALUES
(1, 'root', '4d9b8d479cfa2c2c4e31a07634f3a50e18212390', 'mohamed.abdelrahman9996@yahoo.com', 'Mohamed Abdelrahman Abo Elkassem', 1, 0, 1, '0000-00-00', ''),
(5, 'Shimaa', '4d9b8d479cfa2c2c4e31a07634f3a50e18212390', 'shimaa@mylove.com', 'Shimaa Mostafa', 0, 0, 1, '2019-02-01', '42205810_shika 4.jpg'),
(16, 'shosho', '9f2feb0f1ef425b292f2f94bc8482494df430413', 'shosho7@mohamed.com', 'Shosho Mostafa', 0, 0, 1, '2019-02-28', '96432496_shika.JPG'),
(18, 'Ahmed', '1436e28c07e79c44c7939b0d8c2e931107ba53cc', 'Ahmed@mohamed.com', 'ahmed abdelrahman', 0, 0, 1, '2019-02-28', ''),
(19, 'shymaa', '33f7795e9f3cffa064d0c7dcb01f778f46b5c246', 'shymaa@shymaa.com', 'Shimaa Sayed', 0, 0, 1, '2019-02-28', '94436646_shika 2.jpg'),
(20, 'Shimaa 7.5', 'e24a011fa1bd05e1d4753d2245b6dd84b610316e', 'shymaa@shymaa.com', 'Shimaa Milf', 0, 0, 0, '2019-02-28', ''),
(21, 'Amira', '831ac9f096134d8f841b63bb5e80bdaac975979f', 'amira@hassan.com', 'Amira Hassan', 0, 0, 0, '2019-02-28', ''),
(24, 'Mohamed', 'd1943bb3e102845442da1ed6ede29f53db8694d2', '1231@123123', '1231213', 0, 0, 0, '2019-03-10', '76782227_pp.jpg'),
(25, 'mostafa', '4755bfab4052cc27342fd251db714407b842eef3', 'mostafa@mostafa', 'mostafa mostafa', 0, 0, 0, '0000-00-00', ''),
(26, 'alsalt', '8118fc7fee701d8845dee613431f4a7e6c660205', 'alsalt@alsalt.com', '', 0, 0, 0, '2019-03-12', ''),
(27, 'Abdo', '7c222fb2927d828af22f592134e8932480637c0d', 'Abdo@Abdo.com', '', 0, 0, 0, '2019-03-12', ''),
(28, '123456789', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', '123456789@123456789.com', '', 0, 0, 0, '2019-03-12', ''),
(29, 'mohamed011', 'fb0c6c4554cce940a4e7faf3b70eb5108fbd742a', 'mohamed011@mohamed011.com', '', 0, 0, 0, '2019-03-14', ''),
(31, 'anamohamed0', '4d9b8d479cfa2c2c4e31a07634f3a50e18212390', 'anamohamed0@yahoo.com', 'Mohamed Abdelrahman', 0, 0, 1, '2019-03-16', '24273681_pp.jpg'),
(32, 'mohamed01111', 'fb0c6c4554cce940a4e7faf3b70eb5108fbd742a', 'mohamed011@mohamed011', 'Mohamed Abdelrahman', 0, 0, 1, '2019-03-16', '75839234_pp.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `items_comments` (`item_id`),
  ADD KEY `users_comments` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `member_1` (`Member_ID`),
  ADD KEY `cat_1` (`Cat_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To Identify User', AUTO_INCREMENT=33;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `items_comments` FOREIGN KEY (`item_id`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_comments` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `cat_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member_1` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
