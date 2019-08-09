-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2019 at 03:59 AM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `t2orderfood`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bill`
--

CREATE TABLE `tbl_bill` (
  `bill_id` int(11) NOT NULL,
  `table_no` varchar(50) CHARACTER SET utf8 NOT NULL,
  `order_list` text CHARACTER SET utf8 NOT NULL,
  `total_price` double NOT NULL,
  `discount` double NOT NULL,
  `total_amount` double NOT NULL,
  `bill_cash` double NOT NULL,
  `bill_change` double NOT NULL,
  `bill_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_bill`
--

INSERT INTO `tbl_bill` (`bill_id`, `table_no`, `order_list`, `total_price`, `discount`, `total_amount`, `bill_cash`, `bill_change`, `bill_time`) VALUES
(79, '9', '2  Chicken Nuggets    ฿158.00 ,\n', 158, 7.9, 150.1, 200, 49.9, '2019-04-17 14:55:28'),
(80, '3', '1  Carbonara Spaghetti   ฿139.00 ,\n', 139, 6.95, 132.05, 150, 17.95, '2019-04-17 14:59:11'),
(81, '3', '4  Korean Style Chicken Wings    ฿356.00 ,\n', 356, 0, 356, 500, 144, '2019-04-19 01:11:26'),
(82, '1', '5  Double Cheese   ฿1495.00 ,\n', 1495, 0, 1495, 2000, 505, '2019-04-20 23:46:50'),
(86, '6', '5  Double Cheese   ฿1495.00 ,\n7  Italian Seafood Spaghetti   ฿1183.00 ,\n', 2678, 0, 2678, 3000, 322, '2019-04-21 23:58:35'),
(87, '9', '2  Pepsi 1.5 L   ฿70.00 ,\n', 70, 0, 70, 80, 10, '2019-04-22 23:58:46'),
(88, '3', '1  Italian Sausage   ฿319.00 ,\n', 319, 0, 319, 500, 181, '2019-04-23 23:59:04'),
(89, '5', '2  Hawaiian   ฿638.00 ,\n1  Pepperoni   ฿299.00 ,\n', 937, 93.7, 843.3, 1000, 156.7, '2019-04-23 23:38:54'),
(90, '1', '1  Italian Sausage   ฿319.00 ,\n', 319, 31.9, 287.1, 500, 212.9, '2019-05-01 22:23:57'),
(91, '2', '1  Double Cheese   ฿299.00 ,\n1  Italian Sausage   ฿319.00 ,\n', 618, 0, 618, 700, 82, '2019-05-02 01:19:04'),
(92, '15', '1  Veggie   ฿299.00 ,\n', 299, 0, 299, 300, 1, '2019-05-02 20:09:31'),
(93, '15', '1  Seafood Deluxe   ฿379.00 ,\n', 379, 0, 379, 400, 21, '2019-05-02 21:08:04'),
(94, '5', '1  Shrimp Pesto Spaghetti   ฿129.00 ,\n1  Pepsi 550 ml   ฿25.00 ,\n1  Korean Style Chicken Wings    ฿89.00 ,\n', 243, 0, 243, 300, 57, '2019-05-02 21:08:23'),
(95, '3', '1  BBQ Chicken Wings   ฿89.00 ,\n', 89, 4.45, 84.55, 90, 5.45, '2019-05-07 09:15:41'),
(96, '8', '1  Veggie   ฿299.00 ,\n', 299, 14.95, 284.05, 300, 15.95, '2019-05-07 09:17:45'),
(97, '9', '2  Double Cheese   ฿598.00 ,\n1  Italian Sausage   ฿319.00 ,\n4  Chicken Nuggets    ฿316.00 ,\n', 1233, 123.3, 1109.7, 1500, 390.3, '2019-05-07 10:04:15'),
(98, '2', '4  Chicken Nuggets    ฿316.00 ,\n', 316, 31.6, 284.4, 500, 215.6, '2019-05-13 04:40:18'),
(99, '8', '1  Hawaiian   ฿319.00 ,\n2  Double Cheese   ฿598.00 ,\n', 917, 45.85, 871.15, 1000, 128.85, '2019-05-13 07:09:24');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(20) NOT NULL,
  `category_image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`category_id`, `category_name`, `category_image`) VALUES
(1, 'Pizza', 'upload/images/5405-2019-02-18.jpg'),
(2, 'Pasta', 'upload/images/6889-2019-02-18.jpg'),
(3, 'Side Dish', 'upload/images/5008-2019-02-18.jpg'),
(4, 'Drink', 'upload/images/6237-2019-02-18.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_gallery`
--

CREATE TABLE `tbl_gallery` (
  `gid` int(11) NOT NULL,
  `gallery_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `cat_id` int(11) NOT NULL DEFAULT '1',
  `gallery_image` varchar(255) NOT NULL,
  `gallery_description` text CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_gallery`
--

INSERT INTO `tbl_gallery` (`gid`, `gallery_name`, `cat_id`, `gallery_image`, `gallery_description`) VALUES
(16, 'Lorem ipsum', 1, '1702-2016-10-04.jpg', 'Lorem ipsum dolor sit amet'),
(17, 'Lorem Ipsum', 1, '1829-2016-10-04.jpg', 'Lorem ipsum dolor sit amet'),
(18, 'Lorem ipsum', 1, '1369-2016-10-04.jpg', 'Lorem ipsum dolor sit amet'),
(19, 'Lorem Ipsum', 1, '5845-2016-10-04.jpg', 'Lorem ipsum dolor sit amet'),
(20, 'Lorem ipsum', 1, '0946-2016-10-04.jpg', 'Lorem ipsum dolor sit amet'),
(21, 'Lorem ipsum', 1, '0875-2016-10-04.jpg', 'Lorem ipsum dolor sit amet');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu`
--

CREATE TABLE `tbl_menu` (
  `menu_id` int(11) NOT NULL,
  `menu_name` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  `price` double NOT NULL,
  `menu_status` varchar(45) NOT NULL,
  `menu_image` text NOT NULL,
  `menu_description` text NOT NULL,
  `serve_for` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_menu`
--

INSERT INTO `tbl_menu` (`menu_id`, `menu_name`, `category_id`, `price`, `menu_status`, `menu_image`, `menu_description`, `serve_for`) VALUES
(31, 'Hawaiian', 1, 319, 'Available', 'upload/images/0042-2019-02-18.png', '<p>Pineapple, Ham Slice, Bacon Slice, Mozzarella Cheese, Pizza Sauce</p>\r\n', 4),
(32, 'Seafood Deluxe', 1, 379, 'Available', 'upload/images/3937-2019-02-18.png', '<p>Onion, Capsicum, Seafood Mixed, Prawn, Mozzarella Cheese, Marinara Sauce</p>\r\n', 4),
(33, 'Meat Deluxe', 1, 319, 'Available', 'upload/images/5170-2019-02-18.png', '<p>Pepperoni, Ham Slice, Bacon Slice, Smoked Pork Sausage, Italian Sausage, Bacon Bit, Mozzarella Cheese, Pizza Sauce</p>\r\n', 4),
(34, 'Pepperoni', 1, 299, 'Available', 'upload/images/1452-2019-02-18.png', '<p>Pepperoni, Mozzarella Cheese, Pizza Sauce</p>\r\n', 4),
(35, 'Chicken Trio', 1, 319, 'Available', 'upload/images/7085-2019-02-18.png', '<p>Mushroom, Roasted Chicken, Garlic Butter Chicken, BBQ Chicken, Red&amp;Green Chili, Mozzarella Cheese, Pizza Sauce</p>\r\n', 4),
(36, 'Italian Sausage', 1, 319, 'Available', 'upload/images/0338-2019-02-18.png', '<p>Pepperoni, Mushroom, Onion, Tomato, Italian Sausage, Mozzarella Cheese, Pizza Sauce</p>\r\n', 4),
(37, 'Veggie', 1, 299, 'Available', 'upload/images/7414-2019-02-18.png', '<p>Spinach, Tomato, Onion, Mushroom, Red &amp; Green Chili, Gouda Cheese, Mozzarella Cheese, Pizza Sauce</p>\r\n', 4),
(38, 'Double Cheese', 1, 299, 'Available', 'upload/images/6384-2019-02-18.png', '<p>Mozzarella Cheese, Cheese, Pizza Sauce</p>\r\n', 4),
(39, 'Carbonara Spaghetti', 2, 139, 'Available', 'upload/images/0895-2019-02-18.jpg', '<p>Mellow spaghetti with ham &amp; mushroom in fabulous white cream sauce.</p>\r\n', 1),
(40, 'Bolognese Spaghetti', 2, 89, 'Available', 'upload/images/8712-2019-02-18.jpg', '<p>Spaghetti served with a sauce of minced beef, tomato, onion, and herbs.</p>\r\n', 1),
(41, 'Spicy Bacon Spaghetti', 2, 109, 'Available', 'upload/images/6441-2019-02-18.jpg', '<p>A pasta creation full of aroma, taste and a hint of spiciness. One of our highly recommended dishes!</p>\r\n', 1),
(42, 'Italian Seafood Spaghetti', 2, 169, 'Available', 'upload/images/3178-2019-02-18.jpg', '<p>An original Italian spaghetti mixed with chunky seafood and tomato sauce.</p>\r\n', 1),
(43, 'Kheemao Seafood Spaghetti', 2, 139, 'Available', 'upload/images/0369-2019-02-18.jpg', '<p>Selected fresh seafood cooked with the authentic spice Thai Kheemao recipe and Italian style spaghetti. Full of flavors, try now!</p>\r\n', 1),
(44, 'Shrimp Pesto Spaghetti', 2, 129, 'Available', 'upload/images/5561-2019-02-18.jpg', '<p>This creamy pesto spaghetti with garlic butter shrimp.&nbsp;<br />\r\n&nbsp;</p>\r\n', 1),
(45, 'BBQ Chicken Wings', 3, 89, 'Available', 'upload/images/0149-2019-02-18.jpg', '<p>Super delicious BBQ chicken wings served with intense BBQ sauce.</p>\r\n', 1),
(46, 'Korean Style Chicken Wings ', 3, 89, 'Available', 'upload/images/6708-2019-02-18.jpg', '<p>&quot;Korean Style Chicken Wings&quot; marinated with seasoning, mixed with &nbsp;juicy delicious sauce.</p>\r\n', 1),
(47, 'Garlic Bread', 3, 49, 'Available', 'upload/images/1272-2019-02-18.jpg', '<p>Our fresh baguette, slathered with garlic butter, baked until hot and super aromatic full of that tasty garlic bread. Yummy!</p>\r\n', 1),
(48, 'Chicken Nuggets ', 3, 79, 'Available', 'upload/images/5168-2019-02-18.jpg', '<p>Our tender and juicy chicken nuggets are cooked and marinated in delicious spices and fried to perfection. Enjoy with ketchup.</p>\r\n', 1),
(49, 'Pepsi 1.5 L', 4, 35, 'Available', 'upload/images/4273-2019-02-18.png', '<p>Bottle of Pepsi.</p>\r\n', 2),
(50, 'Pepsi 550 ml', 4, 25, 'Available', 'upload/images/4874-2019-02-18.png', '<p>Bottle of Pepsi.<br />\r\n&nbsp;</p>\r\n', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_news`
--

CREATE TABLE `tbl_news` (
  `nid` int(11) NOT NULL,
  `news_heading` varchar(500) NOT NULL,
  `cat_id` int(11) NOT NULL DEFAULT '1',
  `news_date` varchar(255) NOT NULL,
  `news_image` text NOT NULL,
  `news_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_news`
--

INSERT INTO `tbl_news` (`nid`, `news_heading`, `cat_id`, `news_date`, `news_image`, `news_description`) VALUES
(17, '5 types of anti-cancer food for your Diet Menu', 1, '22 July, 2018', '6292-2016-10-04.jpg', '<p>No one who wants to get cancer. A disease that spreads very quickly and become life-threatening is indeed a threat to everyone. However, cancer can be prevented started early with a healthy lifestyle habit, one of them through food.</p>\r\n'),
(18, 'Soldiers Need to Eat More Fish To Focus When Served', 1, '22 July, 2018', '1364-2016-10-04.jpg', '<p>Soldiers need power of concentration while fighting. Therefore they need intake of omega 3 from fish.</p>\r\n\r\n<p>Low concentration of fish oil in the blood as well as lack of physical activity can contribute to the high depression or stress. One of the jobs, who has a high risk of stress is the army.</p>\r\n\r\n<p>Launched in Boldsky (27/09), fish oil content is very important for soldiers. This is because the consistent training and physical regiment conducted during combat natural risk of traumatic brain injury.</p>\r\n\r\n<p><br />\r\nThe study was published in the Journal of Military Medicine, which examined 100 soldiers to identify the factors that affect the mood of people back in the battle.</p>\r\n\r\n<p>&quot;We see how the level of physical activity and performance measures related to mood. What we found was a decrease in physical activity and concentrations of fish oil and omega 3 in the blood were all associated with mood,&quot; said Richard Kreider, a researcher at Texas A and M University ,</p>\r\n\r\n<p>This research comes from studies that examined the levels of omega 3 fatty acids of the soldiers who committed suicide and compared to control (non-suicidal). The results found a low omega-3 in the blood was associated with an increased risk of suicide were in the group.</p>\r\n\r\n<p>According to the researchers, these findings do to overcome some of the problems faced by the soldiers. &quot;The mental health of soldiers is a serious concern and interest to be considered, that a proper diet and exercise might have a direct impact on improving the resilience,&quot; explains Nicholas Barringer, researchers at Texas A and M University.</p>\r\n'),
(19, 'It\'s Not Unusual Cake, Red Velvet Cake Tomato Sauce Wear Now So Viral', 1, '22 July, 2018', '0871-2016-10-04.jpg', '<p>Red Velvet Cake is known for velvety dark red color. It was sweet with the savory cream cheese spreads gently. Red Velvet was first known at the time of the second world war period in the United States. At that time, known as the red velvet cake soldiers. Any striking red color, instead of food coloring, but instead of bits.</p>\r\n\r\n<p>It&#39;s Not Unusual Cake, Red Velvet Cake Tomato Sauce Wear Now So Viral</p>\r\n\r\n<p>Different from other types of cakes that use egg whites or white butter as a spread cream. Red velvet using cream cheese combined with vanilla and a bit of powdered sugar. Therefore it tastes sour, sweet and savory.</p>\r\n\r\n<p>Recently appeared Red Velvet Tomato Sauce that was warmly discussed. Because the well-known manufacturer of American sauce, Heinz, has just put the recipe on the back of bottles with the name Great Canadian Ketchup Cake. People in Canada came across this recipe. The recipe is no different than the Red Velvet Cake just mixed with tomato sauce and nutmeg. Giving rise to taste sour and sweet blend.</p>\r\n'),
(20, 'Turns Eating Chocolate Can Make Smart!', 1, '22 July, 2018', '3814-2016-10-04.jpg', '<p>The results of the research will please everyone who does not like to drink milk, because it turned out that consumption of chocolate was also found to improve cognitive function of the brain. Which of course, a great many people would agree that taste better than milk chocolate.</p>\r\n\r\n<p>This study can be read in the journal Appetite, which measures the relationship between eating chocolate, the brain&#39;s cognitive abilities, and the risk of heart disease than many 968 people with an age range of 23 to 98 years. The result was surprising that the more often a person ate the chocolate, the better the brain&#39;s cognitive development.</p>\r\n\r\n<p>Brown has a positive effect on cognitive function of the brain because it has a flavanol content. In addition to making us smarter, flavanols in chocolate also can inhibit the decline in the ability of the brain over the age, such as dementia. Most flavanol content of chocolate that is processed is of dark chocolate; whereas, the content of at least found in milk chocolate or white chocolate.</p>\r\n'),
(36, 'Do not Fear Fat, Fat Consumption It is precisely Healthy', 1, '22 July, 2018', '2571-2016-10-04.jpg', '<p>Fat intake has always been associated with obesity although it takes for an energy source. Not all fats are bad effect on health.</p>\r\n\r\n<p>For people on a diet, especially saturated fats always be avoided because they trigger weight gain. However, there are some types of fat are consumed as nutritious for health.</p>\r\n'),
(37, 'Eating Together Can Make Children More Heartily eat and Know Better Food', 1, '22 July, 2018', '7308-2016-10-04.jpg', '<p>Enjoy the food does not always have to be by force. There are many ways to make your child likes healthy food.</p>\r\n\r\n<p>Your child&#39;s favorite foods if you can become healthier. Of course by inviting the child to prepare the meal together to cook together. This can help your child learn and know more groceries.</p>\r\n\r\n<p>Research shows that a healthy diet associated with positive health outcomes. To present the child healthy food of course you can give a different way of presenting the food.</p>\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order`
--

CREATE TABLE `tbl_order` (
  `ID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `table_no` varchar(50) NOT NULL,
  `date_time` datetime NOT NULL,
  `order_list` text NOT NULL,
  `total_price` double NOT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'PENDING',
  `comment` text NOT NULL,
  `bill_status` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_order`
--

INSERT INTO `tbl_order` (`ID`, `name`, `table_no`, `date_time`, `order_list`, `total_price`, `status`, `comment`, `bill_status`) VALUES
(187, 'Mint', '2', '2019-05-13 04:13:00', '4  Chicken Nuggets    ฿316.00 ,\n', 316, 'COMPLETED', 'no spicy\n', 1),
(188, '', '6', '2019-05-13 03:00:00', '1  Korean Style Chicken Wings    ฿89.00 ,\n', 89, 'COMPLETED', '', 0),
(189, '', '8', '2019-05-13 07:06:00', '1  Hawaiian   ฿319.00 ,\n2  Double Cheese   ฿598.00 ,\n', 917, 'COMPLETED', 'no spicy', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_setting`
--

CREATE TABLE `tbl_setting` (
  `Variable` varchar(20) NOT NULL,
  `Value` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_setting`
--

INSERT INTO `tbl_setting` (`Variable`, `Value`) VALUES
('Tax', '7'),
('Currency', 'THB');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_table`
--

CREATE TABLE `tbl_table` (
  `table_id` int(11) NOT NULL,
  `table_no` varchar(50) CHARACTER SET utf8 NOT NULL,
  `table_status` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT 'OFFLINE'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_table`
--

INSERT INTO `tbl_table` (`table_id`, `table_no`, `table_status`) VALUES
(1, '1', 'OFFLINE'),
(2, '2', 'OFFLINE'),
(3, '3', 'OFFLINE'),
(4, '4', 'OFFLINE'),
(5, '5', 'OFFLINE'),
(6, '6', 'ONLINE'),
(7, '7', 'OFFLINE'),
(8, '8', 'OFFLINE'),
(9, '9', 'OFFLINE'),
(10, '10', 'OFFLINE'),
(11, '11', 'OFFLINE'),
(12, '12', 'OFFLINE'),
(13, '13', 'OFFLINE'),
(14, '14', 'OFFLINE'),
(15, '15', 'OFFLINE');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `ID` int(11) NOT NULL,
  `Username` varchar(15) NOT NULL,
  `Password` text NOT NULL,
  `staff_name` varchar(30) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `tel` varchar(10) NOT NULL,
  `address` text NOT NULL,
  `position` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`ID`, `Username`, `Password`, `staff_name`, `Email`, `tel`, `address`, `position`) VALUES
(1, 'admin', 'd82494f05d6917ba02f7aaa29689ccb444bb73f20380876cb05d1f37537b7892', 'Arpawadee', '5735512068@psu.ac.th', '0855863902', 'Phuket', 'Manager'),
(2, 'doaqdoa', '9d9d76446b0a68736cafe8fe6752c583d898c09f085fd494215464d2692c0a97', 'Nattawat', 'doaqdoa@hotmail.com', '0943169051', 'Sadao', 'Cashier'),
(5, 'redmaple', '48278522c6ef549abbcc3d936f72852afc2ab3d6c69ef684aa836e31c9c8bca2', 'Arpa', 'mintza-007@hotmail.com', '0874769220', 'Hatyai', 'Cashier');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_bill`
--
ALTER TABLE `tbl_bill`
  ADD PRIMARY KEY (`bill_id`);

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `tbl_gallery`
--
ALTER TABLE `tbl_gallery`
  ADD PRIMARY KEY (`gid`);

--
-- Indexes for table `tbl_menu`
--
ALTER TABLE `tbl_menu`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `tbl_news`
--
ALTER TABLE `tbl_news`
  ADD PRIMARY KEY (`nid`);

--
-- Indexes for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_table`
--
ALTER TABLE `tbl_table`
  ADD PRIMARY KEY (`table_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_bill`
--
ALTER TABLE `tbl_bill`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;
--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tbl_gallery`
--
ALTER TABLE `tbl_gallery`
  MODIFY `gid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `tbl_menu`
--
ALTER TABLE `tbl_menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `tbl_news`
--
ALTER TABLE `tbl_news`
  MODIFY `nid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `tbl_order`
--
ALTER TABLE `tbl_order`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;
--
-- AUTO_INCREMENT for table `tbl_table`
--
ALTER TABLE `tbl_table`
  MODIFY `table_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
