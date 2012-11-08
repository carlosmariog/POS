-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Giu 14, 2012 alle 15:21
-- Versione del server: 5.5.20
-- Versione PHP: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pos`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_app_config`
--

CREATE TABLE IF NOT EXISTS `ospos_app_config` (
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ospos_app_config`
--

INSERT INTO `ospos_app_config` (`key`, `value`) VALUES
('address', '123 Nowhere street'),
('company', 'Open Source Point of Sale'),
('currency_symbol', ''),
('default_tax_1_name', 'Sales Tax'),
('default_tax_1_rate', ''),
('default_tax_2_name', 'Sales Tax 2'),
('default_tax_2_rate', ''),
('default_tax_rate', '8'),
('email', 'admin@pappastech.com'),
('fax', ''),
('language', 'english'),
('phone', '555-555-5555'),
('print_after_sale', 'print_after_sale'),
('return_policy', 'Test'),
('timezone', 'America/New_York'),
('website', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_customers`
--

CREATE TABLE IF NOT EXISTS `ospos_customers` (
  `person_id` int(10) NOT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `taxable` int(1) NOT NULL DEFAULT '1',
  `deleted` int(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `account_number` (`account_number`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ospos_customers`
--

INSERT INTO `ospos_customers` (`person_id`, `account_number`, `taxable`, `deleted`) VALUES
(3, NULL, 1, 1),
(4, NULL, 1, 1),
(5, NULL, 1, 1),
(6, NULL, 1, 1),
(7, NULL, 1, 1),
(8, NULL, 1, 1),
(9, NULL, 1, 1),
(10, NULL, 1, 1),
(11, NULL, 1, 1),
(12, NULL, 1, 1),
(13, NULL, 1, 1),
(14, NULL, 1, 1),
(15, NULL, 1, 1),
(16, NULL, 1, 1),
(17, NULL, 1, 1),
(18, NULL, 1, 1),
(19, NULL, 1, 1),
(20, NULL, 1, 1),
(21, NULL, 1, 1),
(26, NULL, 1, 0),
(27, NULL, 1, 0),
(28, NULL, 1, 0),
(29, NULL, 1, 0),
(30, NULL, 1, 0),
(31, NULL, 1, 0),
(32, NULL, 1, 0),
(33, NULL, 1, 0),
(34, NULL, 1, 0),
(35, NULL, 1, 0),
(36, NULL, 1, 0),
(37, NULL, 1, 0),
(38, NULL, 1, 0),
(39, NULL, 1, 0),
(40, NULL, 1, 0),
(41, NULL, 1, 0),
(46, NULL, 1, 0),
(47, NULL, 0, 1),
(48, NULL, 1, 0),
(49, NULL, 1, 0),
(50, NULL, 1, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_employees`
--

CREATE TABLE IF NOT EXISTS `ospos_employees` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `person_id` int(10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `username` (`username`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ospos_employees`
--

INSERT INTO `ospos_employees` (`username`, `password`, `person_id`, `deleted`) VALUES
('admin', '439a6de57d475c1a0ba9bcb1c39f0af6', 1, 0),
('andres123', 'b573232aef1e979284f4e145c8ba767b', 24, 0),
('john123', 'cb6f3b8fa1aa7248aee240f594448a39', 22, 0),
('juan123', 'c7f626ad40317f4dc7b295c6f04c850d', 23, 0),
('proview24', '2525cb7a8b75936e80b2f2fca9ab68b7', 2, 1),
('sebastian123', 'b90c834c896ee15650e6ecaa2b4668cd', 25, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_giftcards`
--

CREATE TABLE IF NOT EXISTS `ospos_giftcards` (
  `giftcard_id` int(11) NOT NULL AUTO_INCREMENT,
  `giftcard_number` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `value` double(15,2) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`giftcard_id`),
  UNIQUE KEY `giftcard_number` (`giftcard_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_inventory`
--

CREATE TABLE IF NOT EXISTS `ospos_inventory` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_items` int(11) NOT NULL DEFAULT '0',
  `trans_user` int(11) NOT NULL DEFAULT '0',
  `trans_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trans_comment` text NOT NULL,
  `trans_inventory` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`trans_id`),
  KEY `ospos_inventory_ibfk_1` (`trans_items`),
  KEY `ospos_inventory_ibfk_2` (`trans_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dump dei dati per la tabella `ospos_inventory`
--

INSERT INTO `ospos_inventory` (`trans_id`, `trans_items`, `trans_user`, `trans_date`, `trans_comment`, `trans_inventory`) VALUES
(1, 1, 1, '2012-06-01 04:15:14', 'Manual Edit of Quantity', 20),
(2, 2, 1, '2012-06-06 22:01:01', 'Manual Edit of Quantity', 30),
(3, 2, 1, '2012-06-06 22:02:57', 'POS 1', -1),
(4, 2, 1, '2012-06-06 22:04:34', 'POS 2', -1),
(5, 3, 1, '2012-06-07 12:59:41', 'Qty CSV Imported', 10),
(6, 4, 1, '2012-06-07 12:59:41', 'Qty CSV Imported', 10),
(7, 5, 1, '2012-06-07 12:59:41', 'Qty CSV Imported', 10),
(8, 6, 1, '2012-06-07 12:59:41', 'Qty CSV Imported', 10),
(9, 7, 1, '2012-06-07 12:59:41', 'Qty CSV Imported', 10),
(10, 8, 1, '2012-06-07 12:59:41', 'Qty CSV Imported', 10),
(11, 14, 1, '2012-06-07 16:24:59', 'Manual Edit of Quantity', 40),
(12, 14, 1, '2012-06-07 16:25:12', 'Manual Edit of Quantity', 0),
(13, 15, 1, '2012-06-07 16:25:50', 'Manual Edit of Quantity', 30),
(14, 16, 1, '2012-06-07 16:26:47', 'Manual Edit of Quantity', 10),
(15, 16, 1, '2012-06-08 20:35:35', 'POS 3', -1),
(16, 15, 1, '2012-06-08 21:33:23', 'POS 4', -1),
(17, 16, 1, '2012-06-08 21:33:23', 'POS 4', -1),
(18, 15, 1, '2012-06-10 02:02:36', 'POS 5', -2),
(19, 14, 1, '2012-06-10 02:02:36', 'POS 5', -1),
(20, 16, 1, '2012-06-10 02:02:36', 'POS 5', -1),
(21, 2, 1, '2012-06-13 20:56:33', 'POS 6', 1),
(22, 15, 1, '2012-06-13 22:13:31', 'POS 7', 1),
(23, 16, 1, '2012-06-13 22:13:31', 'POS 7', 1),
(24, 16, 1, '2012-06-13 22:38:34', 'POS 8', 1),
(25, 16, 1, '2012-06-13 22:39:09', 'POS 9', 1),
(26, 16, 1, '2012-06-13 22:43:28', 'POS 10', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_items`
--

CREATE TABLE IF NOT EXISTS `ospos_items` (
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `item_number` varchar(255) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `cost_price` double(15,2) NOT NULL,
  `unit_price` double(15,2) NOT NULL,
  `quantity` double(15,0) NOT NULL DEFAULT '0',
  `reorder_level` double(15,2) NOT NULL DEFAULT '0.00',
  `location` varchar(255) NOT NULL,
  `item_id` int(10) NOT NULL AUTO_INCREMENT,
  `allow_alt_description` tinyint(1) NOT NULL,
  `is_serialized` tinyint(1) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `item_number` (`item_number`),
  KEY `ospos_items_ibfk_1` (`supplier_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dump dei dati per la tabella `ospos_items`
--

INSERT INTO `ospos_items` (`name`, `category`, `supplier_id`, `item_number`, `description`, `cost_price`, `unit_price`, `quantity`, `reorder_level`, `location`, `item_id`, `allow_alt_description`, `is_serialized`, `deleted`) VALUES
('Computer', 'Electronics', NULL, NULL, '', 1500000.00, 2500000.00, 20, 1.00, '', 1, 0, 1, 1),
('Computer Screen', 'Electronics', NULL, NULL, '', 200.00, 300.00, 29, 10.00, '', 2, 1, 1, 1),
('Apple iMac', 'Computers', NULL, '11111111', 'Best Computer ever', 800.00, 1200.00, 10, 1.00, 'Earth', 3, 1, 0, 1),
('Apple iMac', 'Computers', NULL, '22222222', 'Best Computer ever', 800.00, 1200.00, 10, 1.00, 'Earth', 4, 1, 0, 1),
('Apple iMac', 'Computers', NULL, '33333333', 'Best Computer ever', 800.00, 1200.00, 10, 1.00, 'Earth', 5, 1, 0, 1),
('Apple iMac', 'Computers', NULL, '44444444', 'Best Computer ever', 800.00, 1200.00, 10, 1.00, 'Earth', 6, 1, 0, 1),
('Apple iMac', 'Computers', NULL, '55555555', 'Best Computer ever', 800.00, 1200.00, 10, 1.00, 'Earth', 7, 1, 0, 1),
('Apple iMac', 'Computers', NULL, '66666666', 'Best Computer ever', 800.00, 1200.00, 10, 1.00, 'Earth', 8, 1, 0, 1),
('Netbook', 'Electronics', 45, '1', '', 200.00, 300.00, 39, 1.00, '', 14, 0, 1, 0),
('Notebook', 'Electronics', 45, '2', '', 210.00, 320.00, 28, 2.00, '', 15, 0, 0, 0),
('Blackberry', 'Electronics', NULL, '3', '', 300.00, 600.00, 11, 3.00, '', 16, 0, 0, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_items_taxes`
--

CREATE TABLE IF NOT EXISTS `ospos_items_taxes` (
  `item_id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `percent` double(15,3) NOT NULL,
  PRIMARY KEY (`item_id`,`name`,`percent`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ospos_items_taxes`
--

INSERT INTO `ospos_items_taxes` (`item_id`, `name`, `percent`) VALUES
(3, 'Tax 1', 8.000),
(3, 'Tax 2', 10.000),
(4, 'Tax 1', 8.000),
(4, 'Tax 2', 10.000),
(5, 'Tax 1', 8.000),
(5, 'Tax 2', 10.000),
(6, 'Tax 1', 8.000),
(6, 'Tax 2', 10.000),
(7, 'Tax 1', 8.000),
(7, 'Tax 2', 10.000),
(8, 'Tax 1', 8.000),
(8, 'Tax 2', 10.000);

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_item_kits`
--

CREATE TABLE IF NOT EXISTS `ospos_item_kits` (
  `item_kit_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`item_kit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `ospos_item_kits`
--

INSERT INTO `ospos_item_kits` (`item_kit_id`, `name`, `description`) VALUES
(1, 'Netbook+Notebook+Blackberry', 'Netbook+Notebook+Blackberry');

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_item_kit_items`
--

CREATE TABLE IF NOT EXISTS `ospos_item_kit_items` (
  `item_kit_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` double(15,2) NOT NULL,
  PRIMARY KEY (`item_kit_id`,`item_id`,`quantity`),
  KEY `ospos_item_kit_items_ibfk_2` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ospos_item_kit_items`
--

INSERT INTO `ospos_item_kit_items` (`item_kit_id`, `item_id`, `quantity`) VALUES
(1, 14, 1.00),
(1, 15, 1.00),
(1, 16, 1.00);

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_modules`
--

CREATE TABLE IF NOT EXISTS `ospos_modules` (
  `name_lang_key` varchar(255) NOT NULL,
  `desc_lang_key` varchar(255) NOT NULL,
  `sort` int(10) NOT NULL,
  `module_id` varchar(255) NOT NULL,
  PRIMARY KEY (`module_id`),
  UNIQUE KEY `desc_lang_key` (`desc_lang_key`),
  UNIQUE KEY `name_lang_key` (`name_lang_key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ospos_modules`
--

INSERT INTO `ospos_modules` (`name_lang_key`, `desc_lang_key`, `sort`, `module_id`) VALUES
('module_config', 'module_config_desc', 100, 'config'),
('module_customers', 'module_customers_desc', 10, 'customers'),
('module_employees', 'module_employees_desc', 80, 'employees'),
('module_giftcards', 'module_giftcards_desc', 90, 'giftcards'),
('module_items', 'module_items_desc', 20, 'items'),
('module_item_kits', 'module_item_kits_desc', 30, 'item_kits'),
('module_receivings', 'module_receivings_desc', 60, 'receivings'),
('module_repair', 'module_repair_desc', 120, 'repair'),
('module_reports', 'module_reports_desc', 50, 'reports'),
('module_sales', 'module_sales_desc', 70, 'sales'),
('module_suppliers', 'module_suppliers_desc', 40, 'suppliers');

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_people`
--

CREATE TABLE IF NOT EXISTS `ospos_people` (
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `identification_type` varchar(255) NOT NULL,
  `identification` int(10) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address_1` varchar(255) NOT NULL,
  `address_2` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `comments` text NOT NULL,
  `person_id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`person_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

--
-- Dump dei dati per la tabella `ospos_people`
--

INSERT INTO `ospos_people` (`first_name`, `last_name`, `identification_type`, `identification`, `phone_number`, `email`, `address_1`, `address_2`, `city`, `state`, `zip`, `country`, `comments`, `person_id`) VALUES
('Manuel', 'Chacon', '', 0, '555-555-5555', 'manny.c@paradisosolutins.com', 'Calle 25A #23-34', '', 'Medellin', '', '', 'Colombia', '', 1),
('Carlos Mario', 'Gonzalez Ramirez', '', 0, '', 'proview24@gmail.com', '', '', '', '', '', '', '', 2),
('Bob', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 3),
('Bob', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 4),
('John', 'Smit', '', 0, '', '', '', '', '', '', '', '', '', 5),
('Bob', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 6),
('Juan', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 7),
('Andres', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 8),
('Pedro', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 9),
('John', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 10),
('Diego', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 11),
('Alejandro', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 12),
('Santiago', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 13),
('Samanta', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 14),
('Sergio', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 15),
('David', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 16),
('Manuel', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 17),
('Alejo', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 18),
('Wanda', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 19),
('Cosmo', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 20),
('Marisol', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 21),
('John', 'Smith', '', 0, '', 'john@replayd.com', '', '', '', '', '', '', '', 22),
('Juan', 'Felipe', '', 0, '222-22-22', 'juan@replayd.com', '', '', '', '', '', '', '', 23),
('Andres', 'Camilo', '', 0, '333-33-33', 'andres@replayd.com', '', '', '', '', '', '', '', 24),
('Sebastian', 'Ortega', '', 0, '', 'sebastian@replayd.com', '', '', '', '', '', '', '', 25),
('Bob', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 26),
('Juan', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 27),
('Andres', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 28),
('Pedro', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 29),
('John', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 30),
('Diego', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 31),
('Alejandro', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 32),
('Santiago', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 33),
('Samanta', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 34),
('Sergio', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 35),
('David', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 36),
('Manuel', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 37),
('Alejo', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 38),
('Wanda', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 39),
('Cosmo', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 40),
('Marisol', 'Smith', '', 0, '585-555-1111', 'bsmith@nowhere.com', '123 Nowhere Street', 'Apt 4', 'Awesome', 'NY', '11111', 'USA', 'Awesome guy', 41),
('Juliana', 'Jaramillo', '', 0, '', '', '', '', '', '', '', '', '', 42),
('Andrea', 'Hernandez', '', 0, '', '', '', '', '', '', '', '', '', 43),
('Luis', 'Fernando', '', 0, '', '', '', '', '', '', '', '', '', 44),
('Carlos', 'Andres', '', 0, '', '', '', '', '', '', '', '', '', 45),
('Santiago', 'rodriguez', '', 0, '', 'santi@gmail.com', '', '', '', '', '', '', '', 46),
('0', '0', '', 0, '0', '0', '0', '0', '0', '0', '0', '0', '0', 47),
('Leonardo', 'Cardona', '0', 1128404566, '', 'leo@gmail.com', '', '', '', '', '', '', '', 48),
('Alejandro', 'parra', 'driver', 2223344, '444-32-23', 'alejo@gmail.com', '', '', '', '', '', '', '', 49),
('Juan Camilo', 'Ortega', 'Driver Licence', 111204444, '', '', '', '', '', '', '', '', '', 50);

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_permissions`
--

CREATE TABLE IF NOT EXISTS `ospos_permissions` (
  `module_id` varchar(255) NOT NULL,
  `person_id` int(10) NOT NULL,
  PRIMARY KEY (`module_id`,`person_id`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ospos_permissions`
--

INSERT INTO `ospos_permissions` (`module_id`, `person_id`) VALUES
('config', 1),
('customers', 1),
('employees', 1),
('giftcards', 1),
('items', 1),
('item_kits', 1),
('receivings', 1),
('repair', 1),
('reports', 1),
('sales', 1),
('suppliers', 1),
('sales', 22),
('customers', 23),
('items', 23),
('item_kits', 23),
('customers', 24),
('receivings', 24),
('employees', 25);

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_receivings`
--

CREATE TABLE IF NOT EXISTS `ospos_receivings` (
  `receiving_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `supplier_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `receiving_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`receiving_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_receivings_items`
--

CREATE TABLE IF NOT EXISTS `ospos_receivings_items` (
  `receiving_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(30) DEFAULT NULL,
  `serialnumber` varchar(30) DEFAULT NULL,
  `line` int(3) NOT NULL,
  `quantity_purchased` int(10) NOT NULL DEFAULT '0',
  `item_cost_price` decimal(15,2) NOT NULL,
  `item_unit_price` double(15,2) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`receiving_id`,`item_id`,`line`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_sales`
--

CREATE TABLE IF NOT EXISTS `ospos_sales` (
  `sale_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `sale_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`sale_id`),
  KEY `customer_id` (`customer_id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dump dei dati per la tabella `ospos_sales`
--

INSERT INTO `ospos_sales` (`sale_time`, `customer_id`, `employee_id`, `comment`, `sale_id`, `payment_type`) VALUES
('2012-06-06 22:02:57', 5, 1, '0', 1, 'Cash: $300.00<br />'),
('2012-06-06 22:04:34', 5, 1, '0', 2, 'Cash: $300.00<br />'),
('2012-06-08 20:35:35', 49, 1, 'Nothing', 3, 'Debit Card: $600.00<br />'),
('2012-06-08 21:33:23', 28, 1, 'Nothing', 4, 'Cash: $920.00<br />'),
('2012-06-10 02:02:36', 50, 1, '0', 5, 'Cash: $1540.00<br />'),
('2012-06-13 20:56:33', 5, 1, '0', 6, 'Cash: -$300.00<br />'),
('2012-06-13 22:13:31', NULL, 1, '0', 7, 'Cash: -$920.00<br />'),
('2012-06-13 22:38:34', NULL, 1, '0', 8, 'Cash: -$600.00<br />'),
('2012-06-13 22:39:09', NULL, 1, '0', 9, 'Cash: -$600.00<br />'),
('2012-06-13 22:43:28', NULL, 1, '0', 10, 'Cash: -$600.00<br />');

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_sales_items`
--

CREATE TABLE IF NOT EXISTS `ospos_sales_items` (
  `sale_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(30) DEFAULT NULL,
  `serialnumber` varchar(30) DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `quantity_purchased` double(15,0) NOT NULL DEFAULT '0',
  `item_cost_price` decimal(15,2) NOT NULL,
  `item_unit_price` double(15,2) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`,`item_id`,`line`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ospos_sales_items`
--

INSERT INTO `ospos_sales_items` (`sale_id`, `item_id`, `description`, `serialnumber`, `line`, `quantity_purchased`, `item_cost_price`, `item_unit_price`, `discount_percent`) VALUES
(1, 2, '', '', 1, 1, '200.00', 300.00, 0),
(2, 2, '', '', 1, 1, '200.00', 300.00, 0),
(3, 16, '', '', 1, 1, '300.00', 600.00, 0),
(4, 15, '', '', 1, 1, '210.00', 320.00, 0),
(4, 16, '', '', 2, 1, '300.00', 600.00, 0),
(5, 14, '', '', 2, 1, '200.00', 300.00, 0),
(5, 15, '', '', 1, 2, '210.00', 320.00, 0),
(5, 16, '', '', 3, 1, '300.00', 600.00, 0),
(6, 2, '', '', 1, -1, '200.00', 300.00, 0),
(7, 15, '', '', 1, -1, '210.00', 320.00, 0),
(7, 16, '', '', 2, -1, '300.00', 600.00, 0),
(8, 16, '', '', 1, -1, '300.00', 600.00, 0),
(9, 16, '', '', 1, -1, '300.00', 600.00, 0),
(10, 16, '', '', 1, -1, '300.00', 600.00, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_sales_items_taxes`
--

CREATE TABLE IF NOT EXISTS `ospos_sales_items_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `percent` double(15,3) NOT NULL,
  PRIMARY KEY (`sale_id`,`item_id`,`line`,`name`,`percent`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_sales_payments`
--

CREATE TABLE IF NOT EXISTS `ospos_sales_payments` (
  `sale_id` int(10) NOT NULL,
  `payment_type` varchar(40) NOT NULL,
  `payment_amount` decimal(15,2) NOT NULL,
  PRIMARY KEY (`sale_id`,`payment_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ospos_sales_payments`
--

INSERT INTO `ospos_sales_payments` (`sale_id`, `payment_type`, `payment_amount`) VALUES
(1, 'Cash', '300.00'),
(2, 'Cash', '300.00'),
(3, 'Debit Card', '600.00'),
(4, 'Cash', '920.00'),
(5, 'Cash', '1540.00'),
(6, 'Cash', '-300.00'),
(7, 'Cash', '-920.00'),
(8, 'Cash', '-600.00'),
(9, 'Cash', '-600.00'),
(10, 'Cash', '-600.00');

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_sales_suspended`
--

CREATE TABLE IF NOT EXISTS `ospos_sales_suspended` (
  `sale_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `sale_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`sale_id`),
  KEY `customer_id` (`customer_id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_sales_suspended_items`
--

CREATE TABLE IF NOT EXISTS `ospos_sales_suspended_items` (
  `sale_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(30) DEFAULT NULL,
  `serialnumber` varchar(30) DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `quantity_purchased` double(15,2) NOT NULL DEFAULT '0.00',
  `item_cost_price` decimal(15,2) NOT NULL,
  `item_unit_price` double(15,2) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`,`item_id`,`line`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_sales_suspended_items_taxes`
--

CREATE TABLE IF NOT EXISTS `ospos_sales_suspended_items_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `percent` double(15,3) NOT NULL,
  PRIMARY KEY (`sale_id`,`item_id`,`line`,`name`,`percent`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_sales_suspended_payments`
--

CREATE TABLE IF NOT EXISTS `ospos_sales_suspended_payments` (
  `sale_id` int(10) NOT NULL,
  `payment_type` varchar(40) NOT NULL,
  `payment_amount` decimal(15,2) NOT NULL,
  PRIMARY KEY (`sale_id`,`payment_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_sessions`
--

CREATE TABLE IF NOT EXISTS `ospos_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_suppliers`
--

CREATE TABLE IF NOT EXISTS `ospos_suppliers` (
  `person_id` int(10) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `account_number` (`account_number`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ospos_suppliers`
--

INSERT INTO `ospos_suppliers` (`person_id`, `company_name`, `account_number`, `deleted`) VALUES
(42, 'IBM', NULL, 0),
(43, 'HP', NULL, 0),
(44, 'ASUS', NULL, 0),
(45, 'ACER', NULL, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `ospos_used_items`
--

CREATE TABLE IF NOT EXISTS `ospos_used_items` (
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `item_number` varchar(255) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `cost_price` double(15,2) NOT NULL,
  `unit_price` double(15,2) NOT NULL,
  `quantity` double(15,2) NOT NULL DEFAULT '0.00',
  `average` double(15,2) NOT NULL,
  `reorder_level` double(15,2) NOT NULL DEFAULT '0.00',
  `location` varchar(255) NOT NULL,
  `item_id` int(10) NOT NULL AUTO_INCREMENT,
  `allow_alt_description` tinyint(1) NOT NULL,
  `is_serialized` tinyint(1) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `item_number` (`item_number`),
  KEY `ospos_items_ibfk_1` (`supplier_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `ospos_customers`
--
ALTER TABLE `ospos_customers`
  ADD CONSTRAINT `ospos_customers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `ospos_people` (`person_id`);

--
-- Limiti per la tabella `ospos_employees`
--
ALTER TABLE `ospos_employees`
  ADD CONSTRAINT `ospos_employees_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `ospos_people` (`person_id`);

--
-- Limiti per la tabella `ospos_inventory`
--
ALTER TABLE `ospos_inventory`
  ADD CONSTRAINT `ospos_inventory_ibfk_1` FOREIGN KEY (`trans_items`) REFERENCES `ospos_items` (`item_id`),
  ADD CONSTRAINT `ospos_inventory_ibfk_2` FOREIGN KEY (`trans_user`) REFERENCES `ospos_employees` (`person_id`);

--
-- Limiti per la tabella `ospos_items`
--
ALTER TABLE `ospos_items`
  ADD CONSTRAINT `ospos_items_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `ospos_suppliers` (`person_id`);

--
-- Limiti per la tabella `ospos_items_taxes`
--
ALTER TABLE `ospos_items_taxes`
  ADD CONSTRAINT `ospos_items_taxes_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `ospos_item_kit_items`
--
ALTER TABLE `ospos_item_kit_items`
  ADD CONSTRAINT `ospos_item_kit_items_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `ospos_item_kits` (`item_kit_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ospos_item_kit_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `ospos_permissions`
--
ALTER TABLE `ospos_permissions`
  ADD CONSTRAINT `ospos_permissions_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `ospos_employees` (`person_id`),
  ADD CONSTRAINT `ospos_permissions_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `ospos_modules` (`module_id`);

--
-- Limiti per la tabella `ospos_receivings`
--
ALTER TABLE `ospos_receivings`
  ADD CONSTRAINT `ospos_receivings_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `ospos_employees` (`person_id`),
  ADD CONSTRAINT `ospos_receivings_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `ospos_suppliers` (`person_id`);

--
-- Limiti per la tabella `ospos_receivings_items`
--
ALTER TABLE `ospos_receivings_items`
  ADD CONSTRAINT `ospos_receivings_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`),
  ADD CONSTRAINT `ospos_receivings_items_ibfk_2` FOREIGN KEY (`receiving_id`) REFERENCES `ospos_receivings` (`receiving_id`);

--
-- Limiti per la tabella `ospos_sales`
--
ALTER TABLE `ospos_sales`
  ADD CONSTRAINT `ospos_sales_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `ospos_employees` (`person_id`),
  ADD CONSTRAINT `ospos_sales_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `ospos_customers` (`person_id`);

--
-- Limiti per la tabella `ospos_sales_items`
--
ALTER TABLE `ospos_sales_items`
  ADD CONSTRAINT `ospos_sales_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`),
  ADD CONSTRAINT `ospos_sales_items_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `ospos_sales` (`sale_id`);

--
-- Limiti per la tabella `ospos_sales_items_taxes`
--
ALTER TABLE `ospos_sales_items_taxes`
  ADD CONSTRAINT `ospos_sales_items_taxes_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `ospos_sales_items` (`sale_id`),
  ADD CONSTRAINT `ospos_sales_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`);

--
-- Limiti per la tabella `ospos_sales_payments`
--
ALTER TABLE `ospos_sales_payments`
  ADD CONSTRAINT `ospos_sales_payments_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `ospos_sales` (`sale_id`);

--
-- Limiti per la tabella `ospos_sales_suspended`
--
ALTER TABLE `ospos_sales_suspended`
  ADD CONSTRAINT `ospos_sales_suspended_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `ospos_employees` (`person_id`),
  ADD CONSTRAINT `ospos_sales_suspended_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `ospos_customers` (`person_id`);

--
-- Limiti per la tabella `ospos_sales_suspended_items`
--
ALTER TABLE `ospos_sales_suspended_items`
  ADD CONSTRAINT `ospos_sales_suspended_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`),
  ADD CONSTRAINT `ospos_sales_suspended_items_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `ospos_sales_suspended` (`sale_id`);

--
-- Limiti per la tabella `ospos_sales_suspended_items_taxes`
--
ALTER TABLE `ospos_sales_suspended_items_taxes`
  ADD CONSTRAINT `ospos_sales_suspended_items_taxes_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `ospos_sales_suspended_items` (`sale_id`),
  ADD CONSTRAINT `ospos_sales_suspended_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `ospos_items` (`item_id`);

--
-- Limiti per la tabella `ospos_sales_suspended_payments`
--
ALTER TABLE `ospos_sales_suspended_payments`
  ADD CONSTRAINT `ospos_sales_suspended_payments_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `ospos_sales_suspended` (`sale_id`);

--
-- Limiti per la tabella `ospos_suppliers`
--
ALTER TABLE `ospos_suppliers`
  ADD CONSTRAINT `ospos_suppliers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `ospos_people` (`person_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
