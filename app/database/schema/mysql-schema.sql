
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `order_id` int(11) NOT NULL,
  `txnID` varchar(25) NOT NULL DEFAULT '',
  `full_name` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `number` varchar(10) NOT NULL DEFAULT '',
  `zip` varchar(5) NOT NULL DEFAULT '',
  `city` varchar(255) NOT NULL,
  `region` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(25) NOT NULL DEFAULT 'Italia',
  `mail` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `phone` varchar(30) NOT NULL DEFAULT '',
  `security` varchar(10) DEFAULT NULL,
  `exchange` tinyint(1) NOT NULL DEFAULT 0,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `cart_id_index` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_categories` (
  `id` bigint(20) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(15) NOT NULL,
  `for_supervisors` tinyint(1) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `resource` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL DEFAULT 'admin',
  `hidden` tinyint(1) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `for_supervisors` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `change_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_order_id` int(11) NOT NULL,
  `new_order_id` int(11) DEFAULT NULL,
  `step` int(11) NOT NULL DEFAULT 1,
  `token` varchar(255) DEFAULT NULL,
  `payed` tinyint(1) NOT NULL DEFAULT 0,
  `processed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `changes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_order_item_id` int(11) NOT NULL,
  `new_order_item_id` int(11) DEFAULT NULL,
  `change_request_id` int(11) NOT NULL,
  `old_size_id` int(11) DEFAULT NULL,
  `new_size_id` int(11) DEFAULT NULL,
  `is_change` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `collections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `position` int(11) NOT NULL DEFAULT 0,
  `cover` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `value` varchar(500) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_index` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `design_id` int(11) NOT NULL DEFAULT 0,
  `seller_id` bigint(20) unsigned DEFAULT NULL,
  `mod_us` int(11) NOT NULL DEFAULT 0,
  `mod_them` int(11) NOT NULL DEFAULT 0,
  `mod_us2` int(11) NOT NULL DEFAULT 0,
  `mod_them2` int(11) NOT NULL DEFAULT 0,
  `mod_us3` int(11) NOT NULL DEFAULT 0,
  `mod_them3` int(11) NOT NULL DEFAULT 0,
  `flat_us` float NOT NULL DEFAULT 0,
  `flat_them` float NOT NULL DEFAULT 0,
  `flat_us2` float NOT NULL DEFAULT 0,
  `flat_them2` float NOT NULL DEFAULT 0,
  `flat_us3` float NOT NULL DEFAULT 0,
  `flat_them3` float NOT NULL DEFAULT 0,
  `type_id` int(11) NOT NULL DEFAULT 0,
  `color_id` int(11) NOT NULL DEFAULT 0,
  `only_one` tinyint(1) NOT NULL DEFAULT 0,
  `one_per_acccount` tinyint(1) NOT NULL DEFAULT 0,
  `permanent` tinyint(1) NOT NULL DEFAULT 0,
  `zero` tinyint(1) NOT NULL DEFAULT 0,
  `nogain` tinyint(1) NOT NULL DEFAULT 0,
  `shipping` tinyint(1) NOT NULL DEFAULT 0,
  `time_start` timestamp NOT NULL DEFAULT current_timestamp(),
  `time_end` timestamp NOT NULL DEFAULT current_timestamp(),
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `design_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `design_id` bigint(20) NOT NULL,
  `tags` varchar(230) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `design_tags_design_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `designs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(150) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL,
  `color` varchar(7) NOT NULL DEFAULT '#FFFFFF',
  `default_color_id` bigint(20) unsigned NOT NULL DEFAULT 1,
  `collection` int(11) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 0,
  `ratio` float NOT NULL DEFAULT 1,
  `logo_group_id` int(11) NOT NULL DEFAULT 0,
  `sales` int(11) NOT NULL DEFAULT 0,
  `recent_sales` int(11) NOT NULL DEFAULT 0,
  `custom` tinyint(1) NOT NULL DEFAULT 0,
  `hidden` tinyint(1) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `designs_default_color_id_index` (`default_color_id`),
  KEY `designs_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `feed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `source_id` int(11) NOT NULL DEFAULT 0,
  `target_id` int(11) NOT NULL DEFAULT 0,
  `type` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `followers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `home_pics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `design_id` int(11) NOT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `type_id` bigint(20) unsigned DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `design_id` bigint(20) unsigned DEFAULT NULL,
  `type_id` bigint(20) unsigned DEFAULT NULL,
  `color_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL DEFAULT 0,
  `logo_id` int(11) NOT NULL DEFAULT 0,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `product_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `items_genders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(3) NOT NULL,
  `name` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `items_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) NOT NULL,
  `description` text DEFAULT NULL,
  `details` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `items_mockups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL DEFAULT 1,
  `pos_x_min` int(11) NOT NULL DEFAULT 35,
  `pos_x_def` int(11) NOT NULL DEFAULT 50,
  `pos_y_min` int(11) NOT NULL DEFAULT 18,
  `pos_y_def` int(11) NOT NULL DEFAULT 25,
  `siz_x_max` int(11) NOT NULL DEFAULT 30,
  `siz_x_def` int(11) NOT NULL DEFAULT 90,
  `siz_y_max` int(11) NOT NULL DEFAULT 50,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_id_postition_id_index` (`type_id`,`position_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `items_previews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `size` int(11) NOT NULL DEFAULT 90,
  `pos_x` int(11) NOT NULL DEFAULT 50,
  `pos_y` int(11) NOT NULL DEFAULT 50,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_id_postition_id_index` (`product_id`,`position_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `items_prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) NOT NULL,
  `price` float NOT NULL,
  `gain_w` float NOT NULL DEFAULT 0,
  `gain_c` float NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `items_sizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(20) NOT NULL,
  `name` varchar(15) NOT NULL,
  `price` float NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `items_types_measures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `size` varchar(255) NOT NULL,
  `val` varchar(40) NOT NULL DEFAULT '0,0',
  `width` double NOT NULL,
  `height` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `color_id` bigint(20) unsigned DEFAULT NULL,
  `position_id` bigint(20) unsigned DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_design_id_index` (`user_id`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `logo_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `forced` tinyint(1) NOT NULL DEFAULT 0,
  `preview` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `logos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL,
  `logo_group_id` int(11) NOT NULL,
  `price` float NOT NULL DEFAULT 0,
  `owner` tinyint(1) NOT NULL DEFAULT 0,
  `position` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) DEFAULT NULL,
  `batch` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `order_id` int(11) NOT NULL DEFAULT 0,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `seller_id` int(11) NOT NULL DEFAULT 0,
  `design_id` bigint(20) unsigned DEFAULT NULL,
  `type_id` int(11) NOT NULL DEFAULT 0,
  `color_id` int(11) NOT NULL DEFAULT 0,
  `size_id` bigint(20) unsigned DEFAULT NULL,
  `new_id` int(11) NOT NULL DEFAULT 0,
  `gender_id` bigint(20) unsigned DEFAULT NULL,
  `position_id` bigint(20) unsigned DEFAULT NULL,
  `logo_id` bigint(20) unsigned DEFAULT NULL,
  `unit_amount_before_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount_before_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_amount_after_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount_after_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_amount_to_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount_to_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_seller_earnings_before_reduction` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_seller_earnings_before_reduction` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_seller_earnings_after_reduction` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_seller_earnings_after_reduction` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_seller_reduction` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_seller_reduction` decimal(10,2) NOT NULL DEFAULT 0.00,
  `credit` tinyint(1) NOT NULL DEFAULT 0,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `inventory` int(11) NOT NULL DEFAULT 0,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) DEFAULT 0,
  `is_printed` tinyint(1) NOT NULL DEFAULT 0,
  `is_in_cart` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `cart_id_index` (`order_id`),
  KEY `orders_user_id_index` (`user_id`),
  KEY `orders_product_id_index` (`product_id`),
  KEY `orders_seller_id_index` (`seller_id`),
  KEY `orders_design_id_index` (`design_id`),
  KEY `orders_type_id_index` (`type_id`),
  KEY `orders_color_id_index` (`color_id`),
  KEY `orders_size_id_index` (`size_id`),
  KEY `orders_new_id_index` (`new_id`),
  KEY `orders_gender_id_index` (`gender_id`),
  KEY `orders_position_id_index` (`position_id`),
  KEY `orders_logo_id_index` (`logo_id`),
  KEY `order_items_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(12) DEFAULT NULL,
  `shipping_method_id` int(11) NOT NULL DEFAULT 2,
  `country_id` int(11) NOT NULL DEFAULT 1,
  `payment_method_id` int(11) NOT NULL DEFAULT 1,
  `code` varchar(50) DEFAULT NULL,
  `promotion` varchar(11) NOT NULL DEFAULT '',
  `tot` float NOT NULL DEFAULT 0,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `quantity` int(4) NOT NULL DEFAULT 0,
  `credit` decimal(8,2) NOT NULL DEFAULT 0.00,
  `credit_ship` tinyint(1) NOT NULL DEFAULT 0,
  `shipping` decimal(8,2) NOT NULL DEFAULT 0.00,
  `tracking` varchar(255) DEFAULT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `step` int(11) NOT NULL DEFAULT 1,
  `token` varchar(255) DEFAULT NULL,
  `error` int(11) NOT NULL DEFAULT 0,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `courier_notes` varchar(255) DEFAULT NULL,
  `printing_notes` varchar(255) DEFAULT NULL,
  `has_errors` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `exchange` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_id_status_index` (`user_id`,`status`),
  KEY `user_id_index` (`user_id`),
  KEY `carts_code_index` (`code`),
  KEY `orders_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(3) NOT NULL,
  `name` varchar(50) NOT NULL,
  `desc` varchar(150) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `birthdate` date NOT NULL DEFAULT '1900-01-01',
  `birthplace` varchar(50) NOT NULL,
  `birthprovince` varchar(2) NOT NULL,
  `cf` varchar(50) NOT NULL,
  `national_id` varchar(50) NOT NULL,
  `address` varchar(200) NOT NULL,
  `city` varchar(50) NOT NULL,
  `province` varchar(2) NOT NULL,
  `zip` varchar(5) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `paypal` varchar(100) NOT NULL DEFAULT '',
  `total` float NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `prices_default` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `price` float NOT NULL DEFAULT 0,
  `gain_w` float NOT NULL DEFAULT 0,
  `gain_c` float NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `prices_sellers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `price` float NOT NULL,
  `gain_w` float NOT NULL,
  `gain_c` float NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seller_id_type_id_index` (`seller_id`,`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_color_product_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_color_id` bigint(20) unsigned NOT NULL,
  `product_type_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_colors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(100) NOT NULL,
  `primary_color_hex` varchar(255) NOT NULL,
  `secondary_color_hex` varchar(255) DEFAULT NULL,
  `name` varchar(20) NOT NULL,
  `type` varchar(1) NOT NULL DEFAULT 'c',
  `dark` tinyint(1) NOT NULL DEFAULT 0,
  `reorder` int(11) NOT NULL DEFAULT 0,
  `preorder` tinyint(1) NOT NULL DEFAULT 1,
  `hidden` tinyint(1) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag_index` (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_position_product_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_type_id` bigint(20) unsigned NOT NULL,
  `product_position_id` bigint(20) unsigned NOT NULL,
  `printable` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `info` varchar(500) NOT NULL DEFAULT '',
  `editable` tinyint(1) NOT NULL DEFAULT 1,
  `depending_on` int(11) NOT NULL DEFAULT 0,
  `base_of` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_product_color` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `product_color_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_product_color_product_id_product_color_id_unique` (`product_id`,`product_color_id`),
  KEY `product_color_product_index` (`product_color_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_size_product_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_size_id` bigint(20) unsigned NOT NULL,
  `product_type_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_type_infos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_type_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `names` varchar(50) NOT NULL,
  `gender_id` bigint(20) unsigned DEFAULT NULL,
  `sibling_id` int(11) NOT NULL DEFAULT 0,
  `same_as_type_id` int(11) NOT NULL DEFAULT 0,
  `variant_of` bigint(20) unsigned DEFAULT NULL,
  `category` varchar(1) NOT NULL DEFAULT 'c',
  `custom` tinyint(1) NOT NULL DEFAULT 0,
  `blank_product_id` int(11) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 0,
  `preorder` tinyint(1) NOT NULL DEFAULT 0,
  `hidden` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `design_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL DEFAULT 0,
  `positions` varchar(10) NOT NULL DEFAULT '',
  `sales` int(11) NOT NULL DEFAULT 0,
  `recent_sales` int(11) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `design_id_status_index` (`design_id`,`status`),
  KEY `seller_id_status` (`user_id`,`status`),
  KEY `design_id_type_id_index` (`design_id`,`type_id`) USING BTREE,
  KEY `products_created_at_index` (`created_at`),
  KEY `products_type_id_index` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `provinces` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `tag` varchar(2) NOT NULL,
  `region_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `provinces_region_id_foreign` (`region_id`),
  CONSTRAINT `provinces_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `regions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `seller_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL,
  `value` float NOT NULL,
  `other` tinyint(1) NOT NULL DEFAULT 0,
  `description` varchar(255) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sellers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `sales` int(11) NOT NULL DEFAULT 0,
  `recent_sales` int(11) NOT NULL DEFAULT 0,
  `total` float NOT NULL DEFAULT 0,
  `earned` float NOT NULL DEFAULT 0,
  `payed` float NOT NULL DEFAULT 0,
  `other_transfers` float NOT NULL DEFAULT 0,
  `hidden` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `activation_request_sent` tinyint(1) NOT NULL DEFAULT 0,
  `credit` float NOT NULL DEFAULT 0,
  `request` tinyint(1) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 1,
  `custom` tinyint(1) NOT NULL DEFAULT 0,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stores_user_id_unique` (`user_id`),
  KEY `stores_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sent_emails` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `zone` varchar(11) NOT NULL DEFAULT 'wid',
  `hidden` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_shipping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(3) NOT NULL,
  `shortName` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `desc` varchar(200) NOT NULL,
  `prc_base` float NOT NULL DEFAULT 0,
  `prc_more` float NOT NULL DEFAULT 0,
  `prc_max` float NOT NULL DEFAULT 99,
  `days_min` int(11) NOT NULL,
  `days_max` int(11) NOT NULL,
  `tier` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  `count` int(11) NOT NULL DEFAULT 0,
  `sales` int(11) NOT NULL DEFAULT 0,
  `hide` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `used_coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_contacts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `bluesky` varchar(255) DEFAULT NULL,
  `tiktok` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_resets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `security_code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `guest` tinyint(1) NOT NULL DEFAULT 0,
  `password` varchar(128) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(2550) DEFAULT NULL,
  `mail` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT 0,
  `admin` int(11) NOT NULL DEFAULT 0,
  `fbID` varchar(100) NOT NULL DEFAULT '0',
  `googleID` varchar(100) NOT NULL DEFAULT '',
  `privacy` tinyint(1) NOT NULL DEFAULT 0,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `remember_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_level` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `full_name` varchar(255) GENERATED ALWAYS AS (concat(`first_name`,' ',`last_name`)) VIRTUAL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_group_id_index` (`id`,`level`),
  UNIQUE KEY `users_google_id_unique` (`google_id`),
  KEY `users_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `warnings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0000_01_01_0000_create_migrations',1,'2023-12-13 04:18:48','2023-12-13 04:18:48');
INSERT INTO `migrations` VALUES (2,'2023_12_12_2322_alter_likes',1,'2023-12-13 04:18:48','2023-12-13 04:18:48');
INSERT INTO `migrations` VALUES (3,'2023_12_13_0055_alter_carts',1,'2023-12-13 04:18:48','2023-12-13 04:18:48');
INSERT INTO `migrations` VALUES (4,'2014_10_12_000000_create_users_table',2,'2024-04-06 20:54:16','2024-04-06 20:54:16');
INSERT INTO `migrations` VALUES (5,'2014_10_12_100000_create_password_reset_tokens_table',2,'2024-04-06 20:54:16','2024-04-06 20:54:16');
INSERT INTO `migrations` VALUES (6,'2019_08_19_000000_create_failed_jobs_table',2,'2024-04-06 20:54:16','2024-04-06 20:54:16');
INSERT INTO `migrations` VALUES (7,'2019_12_14_000001_create_personal_access_tokens_table',2,'2024-04-06 20:54:16','2024-04-06 20:54:16');
INSERT INTO `migrations` VALUES (8,'2024_04_04_043854_create_user_resets',2,'2024-04-06 20:54:16','2024-04-06 20:54:16');
INSERT INTO `migrations` VALUES (9,'2024_04_06_215037_create_password_reset_tokens_table',3,'2024-04-06 22:03:10','2024-04-06 22:03:10');
INSERT INTO `migrations` VALUES (10,'2024_04_10_172723_update_admin_categories_table',4,'2024-04-10 19:38:51','2024-04-10 19:38:51');
INSERT INTO `migrations` VALUES (11,'2024_04_10_173615_update_admin_sections_table',4,'2024-04-10 19:38:51','2024-04-10 19:38:51');
INSERT INTO `migrations` VALUES (12,'2024_04_27_220636_add_index_to_stores',5,'2024-04-27 20:50:59','2024-04-27 20:50:59');
INSERT INTO `migrations` VALUES (13,'2024_04_27_220951_add_indexes_to_orders',5,'2024-04-27 20:51:00','2024-04-27 20:51:00');
INSERT INTO `migrations` VALUES (14,'2024_04_27_224009_add_indexes_to_carts',5,'2024-04-27 20:51:00','2024-04-27 20:51:00');
INSERT INTO `migrations` VALUES (15,'2024_05_01_063025_rename_items_types_to_product_types',6,'2024-05-01 20:06:35','2024-05-01 20:06:35');
INSERT INTO `migrations` VALUES (16,'2024_05_01_063224_rename_items_positions_product_positions',6,'2024-05-01 20:06:35','2024-05-01 20:06:35');
INSERT INTO `migrations` VALUES (17,'2024_05_01_063356_create_product_type_position_table',7,'2024-05-01 20:09:24','2024-05-01 20:09:24');
INSERT INTO `migrations` VALUES (18,'2024_05_01_073706_add_price_to_carts_table',7,'2024-05-01 20:09:46','2024-05-01 20:09:46');
INSERT INTO `migrations` VALUES (19,'2024_05_02_055156_change_columns_from_orders_table',8,'2024-05-03 09:15:51','2024-05-03 09:15:51');
INSERT INTO `migrations` VALUES (20,'2024_05_03_072255_create_product_size_product_type_table',8,'2024-05-03 09:15:51','2024-05-03 09:15:51');
INSERT INTO `migrations` VALUES (21,'2024_05_03_073019_rename_orders_table_to_order_items_table',8,'2024-05-03 09:15:51','2024-05-03 09:15:51');
INSERT INTO `migrations` VALUES (22,'2024_05_03_074126_rename_carts_table_to_orders_table',8,'2024-05-03 09:15:51','2024-05-03 09:15:51');
INSERT INTO `migrations` VALUES (23,'2024_05_03_084048_change_id_column_from_designs_table',8,'2024-05-03 09:15:51','2024-05-03 09:15:51');
INSERT INTO `migrations` VALUES (24,'2024_05_03_084957_drop_positions_from_items_types_table',8,'2024-05-03 09:15:51','2024-05-03 09:15:51');
INSERT INTO `migrations` VALUES (25,'2024_05_03_092754_modify_trigger',8,'2024-05-03 09:15:51','2024-05-03 09:15:51');
INSERT INTO `migrations` VALUES (26,'2024_05_03_093919_rename_cart_id_column_from_addresses_table',8,'2024-05-03 09:15:51','2024-05-03 09:15:51');
INSERT INTO `migrations` VALUES (27,'2024_05_03_094031_add_token_column_to_orders_table',8,'2024-05-03 09:15:51','2024-05-03 09:15:51');
INSERT INTO `migrations` VALUES (28,'2024_05_03_094235_rename_time_column_from_news_table',8,'2024-05-03 09:15:51','2024-05-03 09:15:51');
INSERT INTO `migrations` VALUES (29,'2024_05_03_101357_rename_column_from_ecommerce_change_requests_table',8,'2024-05-03 09:15:51','2024-05-03 09:15:51');
INSERT INTO `migrations` VALUES (30,'2024_05_03_101627_update_admin_sections_table',8,'2024-05-03 09:15:51','2024-05-03 09:15:51');
INSERT INTO `migrations` VALUES (31,'2024_05_03_103127_rename_table_designs_tags',8,'2024-05-03 09:15:51','2024-05-03 09:15:51');
INSERT INTO `migrations` VALUES (32,'2024_05_03_112529_update_carts_table',8,'2024-05-03 09:15:51','2024-05-03 09:15:51');
INSERT INTO `migrations` VALUES (33,'2024_05_03_162657_remove_sizes_column_from_items_table',9,'2024-05-05 15:46:00','2024-05-05 15:46:00');
INSERT INTO `migrations` VALUES (34,'2024_05_03_195534_rename_column_from_product_types_table',9,'2024-05-05 15:46:00','2024-05-05 15:46:00');
INSERT INTO `migrations` VALUES (35,'2024_05_03_200802_rename_extras_folder_table',9,'2024-05-05 15:46:00','2024-05-05 15:46:00');
INSERT INTO `migrations` VALUES (36,'2024_05_03_201346_rename_items_table_to_products_table',9,'2024-05-05 15:46:00','2024-05-05 15:46:00');
INSERT INTO `migrations` VALUES (37,'2024_05_04_074146_remane_items_colors_table',9,'2024-05-05 15:46:00','2024-05-05 15:46:00');
INSERT INTO `migrations` VALUES (38,'2024_05_04_091615_add_gain_to_order_items_table',9,'2024-05-05 15:46:00','2024-05-05 15:46:00');
INSERT INTO `migrations` VALUES (39,'2024_05_04_094434_add_earned_column_to_stores_table',9,'2024-05-05 15:46:01','2024-05-05 15:46:01');
INSERT INTO `migrations` VALUES (40,'2024_05_08_010745_add_timestamps_to_payment_requests_table',10,'2024-05-07 23:54:31','2024-05-07 23:54:31');
INSERT INTO `migrations` VALUES (41,'2024_05_08_023421_rename_flat_column_from_stores_table',11,'2024-05-08 01:42:02','2024-05-08 01:42:02');
INSERT INTO `migrations` VALUES (42,'2024_05_09_030749_add_timestamps_to_sys_tags_table',12,'2024-05-09 02:18:00','2024-05-09 02:18:00');
INSERT INTO `migrations` VALUES (43,'2024_05_09_033315_create_product_color_product_type_table',12,'2024-05-09 02:18:00','2024-05-09 02:18:00');
INSERT INTO `migrations` VALUES (44,'2024_05_09_035639_add_default_color_id_to_designs_table',12,'2024-05-09 02:18:01','2024-05-09 02:18:01');
INSERT INTO `migrations` VALUES (45,'2024_05_10_181321_add_soft_deletes_to_stores_table',13,'2024-05-10 16:18:47','2024-05-10 16:18:47');
INSERT INTO `migrations` VALUES (46,'2024_05_11_023343_add_timestamps_to_items_previews_table',14,'2024-05-11 01:18:45','2024-05-11 01:18:45');
INSERT INTO `migrations` VALUES (47,'2024_05_24_060517_create_timestamps_indexes',15,'2024-05-24 06:25:17','2024-05-24 06:25:17');
INSERT INTO `migrations` VALUES (48,'2024_05_27_200429_add_admin_level_column_to_users_table',16,'2024-05-27 18:25:36','2024-05-27 18:25:36');
INSERT INTO `migrations` VALUES (49,'2024_05_27_203737_add_name_column_to_users',17,'2024-05-27 18:40:13','2024-05-27 18:40:13');
INSERT INTO `migrations` VALUES (50,'2024_06_08_014639_add_timestamps_to_stores_payments',18,'2024-06-08 01:06:22','2024-06-08 01:06:22');
INSERT INTO `migrations` VALUES (51,'2024_06_28_120037_add_image_to_product_colors_table',19,'2024-06-28 10:52:42','2024-06-28 10:52:42');
INSERT INTO `migrations` VALUES (52,'2024_06_29_115145_update_product_colors',20,'2024-07-05 13:47:47','2024-07-05 13:47:47');
INSERT INTO `migrations` VALUES (53,'2024_07_05_162845_add_timestamps_to_items_info',21,'2024-07-05 14:28:50','2024-07-05 14:28:50');
INSERT INTO `migrations` VALUES (54,'2024_07_12_134355_add_product_id_to_inventory_table',22,'2024-07-13 14:04:09','2024-07-13 14:04:09');
INSERT INTO `migrations` VALUES (55,'2024_07_12_193401_add_timestamps_to_codes',22,'2024-07-13 14:04:09','2024-07-13 14:04:09');
INSERT INTO `migrations` VALUES (56,'2024_07_24_233100_move_exchange_from_address_to_orders',23,'2024-07-24 21:40:15','2024-07-24 21:40:15');
INSERT INTO `migrations` VALUES (57,'2024_10_01_034536_create_regions_table',24,'2024-10-02 02:45:38','2024-10-02 02:45:38');
INSERT INTO `migrations` VALUES (58,'2024_10_02_033704_create_provinces_table',24,'2024-10-02 02:45:38','2024-10-02 02:45:38');
INSERT INTO `migrations` VALUES (59,'2024_10_04_012729_add_soft_deletes_to_users_table',25,'2024-10-03 23:32:51','2024-10-03 23:32:51');
INSERT INTO `migrations` VALUES (60,'2024_10_04_015838_delete_unassociated_sellers',26,'2024-10-04 00:11:22','2024-10-04 00:11:22');
INSERT INTO `migrations` VALUES (61,'2024_10_04_021008_delete_previews_of_seller_7579',26,'2024-10-04 00:11:26','2024-10-04 00:11:26');
INSERT INTO `migrations` VALUES (67,'2024_10_08_085256_increase_prices',27,'2024-10-08 11:30:06','2024-10-08 11:30:06');
INSERT INTO `migrations` VALUES (68,'2024_10_08_130858_remove_unassociated_sellers',28,'2024-10-08 11:31:12','2024-10-08 11:31:12');
INSERT INTO `migrations` VALUES (69,'2024_11_13_134020_change_type_of_address_column_in_addresses_table',29,'2024-11-13 12:41:40','2024-11-13 12:41:40');
INSERT INTO `migrations` VALUES (70,'2024_12_04_124943_make_seller_id_nullable_in_codes_table',30,'2024-12-04 11:56:22','2024-12-04 11:56:22');
INSERT INTO `migrations` VALUES (71,'2024_12_10_191807_add_column_to_product_position_product_type',31,'2024-12-10 18:30:46','2024-12-10 18:30:46');
INSERT INTO `migrations` VALUES (72,'2024_12_10_191957_add_position_to_product_type',31,'2024-12-10 18:30:46','2024-12-10 18:30:46');
INSERT INTO `migrations` VALUES (73,'2025_02_06_155734_alter_product_types_table',32,'2025-02-18 14:57:47','2025-02-18 14:57:47');
INSERT INTO `migrations` VALUES (74,'2025_05_27_144102_add_columns_to_items_types_measures_table',33,'2025-05-27 12:54:58','2025-05-27 12:54:58');
INSERT INTO `migrations` VALUES (75,'2025_05_27_160139_add_timestamps_to_prices_stores_table',34,'2025-05-27 14:05:29','2025-05-27 14:05:29');
INSERT INTO `migrations` VALUES (76,'2025_06_01_213311_add_timestamps_to_product_positions_table',35,'2025-06-01 19:54:39','2025-06-01 19:54:39');
INSERT INTO `migrations` VALUES (77,'2025_06_02_220345_alter_links_table',36,'2025-06-02 20:58:55','2025-06-02 20:58:55');
INSERT INTO `migrations` VALUES (78,'2025_06_03_155336_alter_shops_table',37,'2025-06-03 14:24:12','2025-06-03 14:24:12');
INSERT INTO `migrations` VALUES (79,'2025_06_06_235717_alter_sys_payment_table_name',38,'2025-06-06 21:58:51','2025-06-06 21:58:51');
INSERT INTO `migrations` VALUES (80,'2025_06_07_004619_alter_ecommerce_change_requests_table',39,'2025-06-07 15:23:05','2025-06-07 15:23:05');
INSERT INTO `migrations` VALUES (81,'2025_06_10_121846_create_sent_emails_table',40,'2025-06-10 10:26:29','2025-06-10 10:26:29');
INSERT INTO `migrations` VALUES (82,'2025_06_12_203441_alter_codes_table',41,'2025-06-12 19:34:32','2025-06-12 19:34:32');
INSERT INTO `migrations` VALUES (83,'2025_06_12_210640_alter_codes_used_table',41,'2025-06-12 19:34:32','2025-06-12 19:34:32');
INSERT INTO `migrations` VALUES (84,'2025_06_16_150507_alter_stores_payments_table',42,'2025-06-16 15:14:41','2025-06-16 15:14:41');
INSERT INTO `migrations` VALUES (85,'2025_06_16_150544_move_bonus_to_credit',42,'2025-06-16 15:14:41','2025-06-16 15:14:41');
INSERT INTO `migrations` VALUES (86,'2025_06_28_153350_add_variant_of_column_to_product_types',43,'2025-06-28 17:22:48','2025-06-28 17:22:48');
INSERT INTO `migrations` VALUES (87,'2025_06_29_123727_delete_products_with_deleted_design',44,'2025-06-29 10:54:26','2025-06-29 10:54:26');
INSERT INTO `migrations` VALUES (88,'2025_08_10_174946_make_warnings_table',45,'2025-08-10 16:12:15','2025-08-10 16:12:15');
INSERT INTO `migrations` VALUES (90,'2025_08_11_152845_fix_missing_seller_orders',46,'2025-08-11 13:36:39','2025-08-11 13:36:39');
INSERT INTO `migrations` VALUES (91,'2025_09_15_230545_add_columns_to_order_items',47,'2025-09-16 12:05:47','2025-09-16 12:05:47');
INSERT INTO `migrations` VALUES (92,'2025_09_18_140242_rename_ecommerce_changes_table',48,'2025-09-18 12:17:28','2025-09-18 12:17:28');
INSERT INTO `migrations` VALUES (93,'2025_09_18_170804_add_columns_to_change_requests_table',49,'2025-09-18 15:38:36','2025-09-18 15:38:36');
INSERT INTO `migrations` VALUES (94,'2025_09_19_163703_update_home_pics_table',50,'2025-09-19 17:58:57','2025-09-19 17:58:57');
INSERT INTO `migrations` VALUES (95,'2025_10_17_233119_alter_items_info_table',51,'2025-10-17 21:57:55','2025-10-17 21:57:55');
INSERT INTO `migrations` VALUES (96,'2025_12_17_171712_fix_changed_order_items',52,'2025-12-17 17:02:17','2025-12-17 17:02:17');
INSERT INTO `migrations` VALUES (97,'2025_12_17_184100_alter_inventory_table',53,'2025-12-17 18:25:25','2025-12-17 18:25:25');
INSERT INTO `migrations` VALUES (98,'2026_01_17_192324_alter_order_items_table',54,'2026-01-17 18:28:56','2026-01-17 18:28:56');
INSERT INTO `migrations` VALUES (99,'2026_01_24_182901_add_google_fields_to_users_table',55,'2026-01-24 19:19:58','2026-01-24 19:19:58');
INSERT INTO `migrations` VALUES (100,'2026_01_24_184459_alter_users_table',55,'2026-01-24 19:19:58','2026-01-24 19:19:58');
INSERT INTO `migrations` VALUES (101,'2026_01_24_191551_alter_users_table',55,'2026-01-24 19:19:58','2026-01-24 19:19:58');
INSERT INTO `migrations` VALUES (102,'2026_01_24_192225_update_users_name',55,'2026-01-24 19:19:58','2026-01-24 19:19:58');
INSERT INTO `migrations` VALUES (103,'2026_01_24_202825_alter_likes_table',56,'2026-01-24 23:53:28','2026-01-24 23:53:28');
INSERT INTO `migrations` VALUES (104,'2026_01_25_001643_alter_likes_table',56,'2026-01-24 23:53:28','2026-01-24 23:53:28');
INSERT INTO `migrations` VALUES (105,'2026_01_25_152039_fix_color_id_from_products',57,'2026-01-25 14:26:02','2026-01-25 14:26:02');
INSERT INTO `migrations` VALUES (106,'2026_01_31_184916_alter_stores_table',58,'2026-01-31 22:01:36','2026-01-31 22:01:36');
INSERT INTO `migrations` VALUES (107,'2026_05_07_004719_create_sessions_table',59,'2026-05-06 22:48:23','2026-05-06 22:48:23');
INSERT INTO `migrations` VALUES (108,'2026_06_05_163558_fix_sellers_balance',60,'2026-06-05 16:18:36','2026-06-05 16:18:36');
INSERT INTO `migrations` VALUES (109,'2026_06_05_183917_fix_sellers_balance',61,'2026-06-05 16:41:28','2026-06-05 16:41:28');
INSERT INTO `migrations` VALUES (110,'2026_06_11_121637_alter_order_items_table',62,'2026-06-11 11:36:02','2026-06-11 11:36:02');
INSERT INTO `migrations` VALUES (113,'2026_06_18_172037_alter_order_items_table',63,'2026-06-18 17:10:45','2026-06-18 17:10:45');
INSERT INTO `migrations` VALUES (114,'2026_06_18_182617_update_seller_balance',63,'2026-06-18 17:10:46','2026-06-18 17:10:46');
INSERT INTO `migrations` VALUES (115,'2026_06_19_190731_alter_producsts_table',64,'2026-06-20 19:01:39','2026-06-20 19:01:39');
INSERT INTO `migrations` VALUES (116,'2026_06_28_164923_alter_order_items_table',65,'2026-06-28 20:39:28','2026-06-28 20:39:28');
INSERT INTO `migrations` VALUES (117,'2026_07_03_011552_add_timestamps_to_collections_table',66,'2026-07-02 23:47:47','2026-07-02 23:47:47');
INSERT INTO `migrations` VALUES (118,'2026_07_10_132025_fix_products_with_missing_colors',67,'2026-07-10 11:27:58','2026-07-10 11:27:58');
INSERT INTO `migrations` VALUES (119,'2026_07_20_142552_fix_order_items_with_missing_genders',68,'2026-07-20 12:56:16','2026-07-20 12:56:16');
INSERT INTO `migrations` VALUES (120,'2026_07_28_152143_add_indexes',69,'2026-07-28 13:25:40','2026-07-28 13:25:40');
INSERT INTO `migrations` VALUES (121,'2026_09_09_151031_add_soft_delete_to_product_types_table',70,'2026-09-09 13:11:23','2026-09-09 13:11:23');
INSERT INTO `migrations` VALUES (122,'2026_10_01_144215_alter_order_items_table',71,'2026-10-01 12:52:36','2026-10-01 12:52:36');
INSERT INTO `migrations` VALUES (123,'2026_10_22_160314_alter_product_types_table',72,'2026-10-25 23:12:00','2026-10-25 23:12:00');
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

SET FOREIGN_KEY_CHECKS=0;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `product_types` WRITE;
/*!40000 ALTER TABLE `product_types` DISABLE KEYS */;
INSERT INTO `product_types` VALUES (1,'tshm','T-Shirt Uomo','T-Shirt Uomo',1,2,0,NULL,'c',0,75378,2,0,0,'2024-03-13 23:47:51','2025-06-18 13:34:52',NULL);
INSERT INTO `product_types` VALUES (2,'tshf','T-Shirt Donna','T-Shirt Donna',2,1,0,NULL,'c',0,75379,2,0,0,'2024-03-13 23:47:51','2025-06-18 13:34:52',NULL);
INSERT INTO `product_types` VALUES (3,'vts','V-Neck','V-Neck',2,0,0,NULL,'c',0,75381,2,0,0,'2024-03-13 23:47:51','2025-05-27 15:15:10',NULL);
INSERT INTO `product_types` VALUES (4,'tnkm','Canotta Uomo','Canotte Uomo',1,5,0,NULL,'c',0,75383,3,0,0,'2024-03-13 23:47:51','2025-05-27 15:18:00',NULL);
INSERT INTO `product_types` VALUES (5,'tnkf','Canotta Donna','Canotte Donna',2,4,0,NULL,'c',0,75384,3,0,0,'2024-03-13 23:47:51','2025-05-27 15:18:42',NULL);
INSERT INTO `product_types` VALUES (6,'swtm','Felpa','Felpe',3,7,7,NULL,'c',0,75386,5,0,0,'2024-03-13 23:47:51','2026-10-25 23:12:00',NULL);
INSERT INTO `product_types` VALUES (8,'hodm','Hoodie','Hoodie',3,9,9,NULL,'c',0,75389,6,0,0,'2024-03-13 23:47:51','2026-10-25 23:12:00',NULL);
INSERT INTO `product_types` VALUES (10,'zipm','Zip Hoodie','Zip Hoodie',3,11,11,NULL,'c',0,75392,8,0,0,'2024-03-13 23:47:51','2026-10-25 23:12:00',NULL);
INSERT INTO `product_types` VALUES (12,'mas','Maschera','Maschere',3,0,0,NULL,'c',0,75395,10,0,0,'2024-03-13 23:47:51','2024-03-13 23:47:51',NULL);
INSERT INTO `product_types` VALUES (17,'cho','Felpa con cappuccio a contrasto','Felpe con cappuccio a contrasto',3,0,0,NULL,'c',0,75397,7,0,0,'2024-03-13 23:47:51','2024-03-13 23:47:51',NULL);
INSERT INTO `product_types` VALUES (105,'bag','Sacca','Sacche',0,0,0,NULL,'m',0,75401,3,0,0,'2024-03-13 23:47:51','2025-06-22 23:32:35',NULL);
INSERT INTO `product_types` VALUES (106,'jut','Sacca Juta','Sacche Juta',0,0,0,NULL,'m',0,75403,3,0,0,'2024-03-13 23:47:51','2025-06-22 23:28:49',NULL);
INSERT INTO `product_types` VALUES (107,'cas','Astuccio','Astucci',0,0,0,NULL,'m',0,75405,3,0,0,'2024-03-13 23:47:51','2025-06-22 23:28:53',NULL);
INSERT INTO `product_types` VALUES (108,'pad','Mouse Pad','Mouse Pad',0,0,0,NULL,'m',0,75409,3,0,0,'2024-03-13 23:47:51','2025-06-22 23:32:53',NULL);
INSERT INTO `product_types` VALUES (109,'epk','Zaino','Zaini',0,0,0,NULL,'m',0,75407,2,0,0,'2024-03-13 23:47:51','2025-06-18 13:34:52',NULL);
INSERT INTO `product_types` VALUES (113,'lss','Maglia a maniche lunghe','Maglie a maniche lunghe',3,0,0,NULL,'c',0,75399,4,0,0,'2024-03-13 23:47:51','2024-03-13 23:47:51',NULL);
INSERT INTO `product_types` VALUES (114,'mund','Boxer','Boxer',1,0,0,NULL,'c',0,0,9,1,0,'2024-03-13 23:47:51','2024-03-13 23:47:51',NULL);
INSERT INTO `product_types` VALUES (115,'und','Tanga','Tanga',2,0,0,NULL,'c',0,0,9,0,0,'2024-03-13 23:47:51','2024-03-23 20:25:50',NULL);
INSERT INTO `product_types` VALUES (116,'shopper','Shopper','Shopper',0,0,0,NULL,'m',0,0,3,0,0,'2024-03-26 19:49:28','2025-06-22 23:32:44',NULL);
INSERT INTO `product_types` VALUES (117,'organic-shopper','Shopper Organica','Shopper Organica',3,0,0,NULL,'m',0,0,11,1,0,'2025-02-18 16:10:08','2025-03-09 18:58:16',NULL);
INSERT INTO `product_types` VALUES (118,'organic-tshirt-m','T-Shirt Organica Uomo','T-Shirt Organiche Uomo',1,0,0,NULL,'c',0,0,12,1,0,'2025-03-09 18:25:27','2025-03-10 18:44:53',NULL);
INSERT INTO `product_types` VALUES (119,'organic-tshirt-f','T-Shirt Organica Donna','T-Shirt Organiche Donna',2,0,0,NULL,'c',0,0,13,1,0,'2025-03-09 18:45:04','2025-03-10 18:45:40',NULL);
INSERT INTO `product_types` VALUES (120,'hat','Cappellino + Toppa','Cappellini',3,0,0,NULL,'m',0,0,1,0,0,'2025-06-01 22:03:32','2025-06-30 15:35:03',NULL);
INSERT INTO `product_types` VALUES (121,'patch','Solo Toppa','Toppe cappellino',3,0,0,120,'m',0,0,2,0,0,'2025-06-23 01:14:30','2025-06-30 15:06:15',NULL);
/*!40000 ALTER TABLE `product_types` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `product_colors` WRITE;
/*!40000 ALTER TABLE `product_colors` DISABLE KEYS */;
INSERT INTO `product_colors` VALUES (1,'wht','#FFFFFF','','Bianco','w',0,10,0,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (2,'blk','#000000','','Nero','c',1,20,0,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (3,'mel','#949494','','Melange','c',0,31,0,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (4,'yel','#FFFD16','','Giallo','c',0,41,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (5,'ora','#FF9600','','Arancione','c',0,44,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (6,'gol','#FFD700','','Oro','c',0,42,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (7,'pin','#FF69B4','','Rosa','c',0,73,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (8,'blu','#0000FF','','Blu','c',1,96,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (9,'red','#FF0000','','Rosso','c',1,62,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (10,'pur','#58438c','','Viola','c',1,84,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (11,'gre','#1b9b3c','','Verde','c',1,207,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (12,'lpu','#ff52f9','','Viola chiaro','c',0,80,1,1,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (13,'dpu','#79086B','','Viola scuro','c',1,83,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (14,'dbl','#0e0d2d','','Blu scuro','c',1,98,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (15,'bei','#E9E5DA','','Beige','c',0,11,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (16,'gry','#777','','Grigio','c',1,32,1,1,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (17,'dor','#CB7700','','Arancione scuro','c',1,45,1,1,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (18,'lbl','#7BA3FF','','Blu chiaro','c',0,93,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (19,'bor','#53292a','','Bordeaux','c',1,65,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (20,'bro','#964B00','','Marrone','c',1,50,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (21,'lge','#a3d1b4','','Verde chiaro','c',0,203,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (22,'dgr','#444','','Grigio scuro','c',1,34,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (23,'dge','#1d2e1b','','Verde scuro','c',1,209,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (24,'wbl','#325c72','','Blu acqua','c',1,101,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (25,'hon','#c59a64','','Miele','c',0,51,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (26,'mag','#902882','','Magenta','c',1,82,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (28,'lpi','#f3bbd1','','Rosa chiaro','c',0,71,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (29,'api','#e66182','','Rosa antico','c',0,72,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (30,'fuc','#ff3f99','','Fuchsia','c',0,74,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (31,'oli','#646253','','Oliva','c',1,210,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (33,'sap','#307bbf','','Zaffiro','c',1,95,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (34,'sbl','#9bc4e1','','Celeste','c',0,91,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (35,'bmg','#000','999','Nero e grigio','c',1,1100,0,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (36,'gmb','#999','000','Grigio e nero','c',1,1100,0,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (37,'nat','#e9d7c0','','Naturale','w',0,14,0,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (38,'cranberyy','#9e003a','','Cranberry','c',1,63,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (39,'apple-green','#5ea636','','Verde mela','c',0,206,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (40,'bottle-green','#096755','','Verde bottiglia','c',1,208,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (41,'light-red','#ff4848','','Rosso chiaro','c',0,61,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (42,'royal-blue','#234da1','','Royal blu','c',1,95,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (43,'burgundy','#7f4053','','Burgundy','c',1,66,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (44,'caramel','#b18562','','Caramello','c',0,52,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (45,'chocolate','#4b3537','','Cioccolato','c',1,53,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (46,'cornflower-blue','#5590ca','','Blu fiordaliso','c',0,94,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (47,'coral','#f56d5d','','Corallo','c',0,60,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (48,'emerald','#009fa0','','Smeraldo','c',0,200,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (49,'navy','#2b395d','','Blu navy','c',1,97,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (50,'kelly-green','#017e4b','','Verde foglia','c',1,207,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (51,'kiwi','#abc375','','Kiwi','c',0,204,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (52,'grafite','#676871',NULL,'Grigio grafite','c',1,33,1,0,NULL,NULL,'2025-06-01 21:55:09');
INSERT INTO `product_colors` VALUES (53,'lavander','#a494bd','','Lavanda','c',0,85,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (54,'lemon','#efe589','','Limone','c',0,40,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (55,'light-grey','#c7c7c7','','Grigio chiaro','c',0,30,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (56,'lime','#9ac842','','Verde lime','c',0,205,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (57,'mint','#76c6ab','','Menta','c',0,201,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (58,'mustard','#e49f24','','Mostarda','c',0,43,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (59,'petrol','#27465b','','Blu petrolio','c',1,100,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (60,'plum','#5d2f4a','','Prugna','c',1,67,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (61,'pastel-blue','#c4d9e4','','Blu pastello','c',0,90,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (62,'pastel-green','#cde2db','','Verde pastello','c',0,203,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (63,'pastel-pink','#f8e5e7','','Rosa pastello','c',0,70,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (64,'raspberry','#da6077','','Lampone','c',0,72,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (65,'sand','#e2d8cc','','Sabbia','c',0,13,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (66,'sky-blue','#8ec3ea','','Blu cielo','c',0,92,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (67,'sunflower','#ffcc05','','Girasole','c',0,42,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (68,'surf-blue','#2bc0e4','','Surf blu','c',0,102,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (69,'violet','#7f6da5','','Violetto','c',1,84,1,0,NULL,NULL,'2024-07-05 15:47:47');
INSERT INTO `product_colors` VALUES (70,'curcuma','#e6941d',NULL,'Curcuma','c',0,45,1,0,NULL,'2025-02-21 14:53:20','2025-02-21 14:53:20');
INSERT INTO `product_colors` VALUES (71,'metal','#8e8787',NULL,'Grigio metallo','c',0,31,1,0,NULL,'2025-02-21 14:54:53','2025-02-21 14:54:53');
INSERT INTO `product_colors` VALUES (72,'military-green','#5a604a',NULL,'Verde militare','c',1,208,1,0,NULL,'2025-04-14 22:01:00','2025-06-03 16:24:43');
INSERT INTO `product_colors` VALUES (73,'forest-green','#1c3827',NULL,'Verde foresta','c',1,209,1,0,NULL,'2025-04-14 22:01:42','2025-04-14 22:01:42');
/*!40000 ALTER TABLE `product_colors` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `product_color_product_type` WRITE;
/*!40000 ALTER TABLE `product_color_product_type` DISABLE KEYS */;
INSERT INTO `product_color_product_type` VALUES (1,1,1);
INSERT INTO `product_color_product_type` VALUES (2,2,1);
INSERT INTO `product_color_product_type` VALUES (3,3,1);
INSERT INTO `product_color_product_type` VALUES (4,4,1);
INSERT INTO `product_color_product_type` VALUES (5,5,1);
INSERT INTO `product_color_product_type` VALUES (6,7,1);
INSERT INTO `product_color_product_type` VALUES (7,8,1);
INSERT INTO `product_color_product_type` VALUES (8,9,1);
INSERT INTO `product_color_product_type` VALUES (9,14,1);
INSERT INTO `product_color_product_type` VALUES (10,15,1);
INSERT INTO `product_color_product_type` VALUES (12,18,1);
INSERT INTO `product_color_product_type` VALUES (13,21,1);
INSERT INTO `product_color_product_type` VALUES (14,22,1);
INSERT INTO `product_color_product_type` VALUES (15,2,3);
INSERT INTO `product_color_product_type` VALUES (16,8,3);
INSERT INTO `product_color_product_type` VALUES (17,14,3);
INSERT INTO `product_color_product_type` VALUES (18,22,3);
INSERT INTO `product_color_product_type` VALUES (19,11,3);
INSERT INTO `product_color_product_type` VALUES (20,3,3);
INSERT INTO `product_color_product_type` VALUES (21,7,3);
INSERT INTO `product_color_product_type` VALUES (22,9,3);
INSERT INTO `product_color_product_type` VALUES (23,1,3);
INSERT INTO `product_color_product_type` VALUES (24,1,6);
INSERT INTO `product_color_product_type` VALUES (25,2,6);
INSERT INTO `product_color_product_type` VALUES (26,3,6);
INSERT INTO `product_color_product_type` VALUES (27,5,6);
INSERT INTO `product_color_product_type` VALUES (28,6,6);
INSERT INTO `product_color_product_type` VALUES (29,7,6);
INSERT INTO `product_color_product_type` VALUES (30,8,6);
INSERT INTO `product_color_product_type` VALUES (31,9,6);
INSERT INTO `product_color_product_type` VALUES (32,10,6);
INSERT INTO `product_color_product_type` VALUES (33,14,6);
INSERT INTO `product_color_product_type` VALUES (34,15,6);
INSERT INTO `product_color_product_type` VALUES (35,18,6);
INSERT INTO `product_color_product_type` VALUES (36,22,6);
INSERT INTO `product_color_product_type` VALUES (37,2,8);
INSERT INTO `product_color_product_type` VALUES (38,8,8);
INSERT INTO `product_color_product_type` VALUES (39,14,8);
INSERT INTO `product_color_product_type` VALUES (40,22,8);
INSERT INTO `product_color_product_type` VALUES (41,6,8);
INSERT INTO `product_color_product_type` VALUES (42,18,8);
INSERT INTO `product_color_product_type` VALUES (43,21,8);
INSERT INTO `product_color_product_type` VALUES (44,3,8);
INSERT INTO `product_color_product_type` VALUES (45,5,8);
INSERT INTO `product_color_product_type` VALUES (46,7,8);
INSERT INTO `product_color_product_type` VALUES (47,9,8);
INSERT INTO `product_color_product_type` VALUES (48,1,8);
INSERT INTO `product_color_product_type` VALUES (49,2,105);
INSERT INTO `product_color_product_type` VALUES (51,22,105);
INSERT INTO `product_color_product_type` VALUES (52,5,105);
INSERT INTO `product_color_product_type` VALUES (53,7,105);
INSERT INTO `product_color_product_type` VALUES (54,1,105);
INSERT INTO `product_color_product_type` VALUES (55,4,105);
INSERT INTO `product_color_product_type` VALUES (56,1,107);
INSERT INTO `product_color_product_type` VALUES (57,1,108);
INSERT INTO `product_color_product_type` VALUES (58,2,108);
INSERT INTO `product_color_product_type` VALUES (59,4,108);
INSERT INTO `product_color_product_type` VALUES (60,5,108);
INSERT INTO `product_color_product_type` VALUES (61,6,108);
INSERT INTO `product_color_product_type` VALUES (62,7,108);
INSERT INTO `product_color_product_type` VALUES (63,8,108);
INSERT INTO `product_color_product_type` VALUES (64,9,108);
INSERT INTO `product_color_product_type` VALUES (65,11,108);
INSERT INTO `product_color_product_type` VALUES (66,14,108);
INSERT INTO `product_color_product_type` VALUES (67,15,108);
INSERT INTO `product_color_product_type` VALUES (68,18,108);
INSERT INTO `product_color_product_type` VALUES (69,19,108);
INSERT INTO `product_color_product_type` VALUES (70,21,108);
INSERT INTO `product_color_product_type` VALUES (71,22,108);
INSERT INTO `product_color_product_type` VALUES (72,23,108);
INSERT INTO `product_color_product_type` VALUES (73,37,106);
INSERT INTO `product_color_product_type` VALUES (74,1,109);
INSERT INTO `product_color_product_type` VALUES (75,2,109);
INSERT INTO `product_color_product_type` VALUES (76,4,109);
INSERT INTO `product_color_product_type` VALUES (77,5,109);
INSERT INTO `product_color_product_type` VALUES (78,6,109);
INSERT INTO `product_color_product_type` VALUES (79,7,109);
INSERT INTO `product_color_product_type` VALUES (80,8,109);
INSERT INTO `product_color_product_type` VALUES (81,9,109);
INSERT INTO `product_color_product_type` VALUES (82,10,109);
INSERT INTO `product_color_product_type` VALUES (83,11,109);
INSERT INTO `product_color_product_type` VALUES (84,13,109);
INSERT INTO `product_color_product_type` VALUES (85,14,109);
INSERT INTO `product_color_product_type` VALUES (86,18,109);
INSERT INTO `product_color_product_type` VALUES (87,19,109);
INSERT INTO `product_color_product_type` VALUES (88,21,109);
INSERT INTO `product_color_product_type` VALUES (89,24,109);
INSERT INTO `product_color_product_type` VALUES (90,23,109);
INSERT INTO `product_color_product_type` VALUES (91,25,109);
INSERT INTO `product_color_product_type` VALUES (92,26,109);
INSERT INTO `product_color_product_type` VALUES (93,29,109);
INSERT INTO `product_color_product_type` VALUES (94,28,109);
INSERT INTO `product_color_product_type` VALUES (95,30,109);
INSERT INTO `product_color_product_type` VALUES (96,31,109);
INSERT INTO `product_color_product_type` VALUES (97,32,109);
INSERT INTO `product_color_product_type` VALUES (98,33,109);
INSERT INTO `product_color_product_type` VALUES (99,34,109);
INSERT INTO `product_color_product_type` VALUES (100,2,4);
INSERT INTO `product_color_product_type` VALUES (101,14,4);
INSERT INTO `product_color_product_type` VALUES (102,22,4);
INSERT INTO `product_color_product_type` VALUES (103,3,4);
INSERT INTO `product_color_product_type` VALUES (104,9,4);
INSERT INTO `product_color_product_type` VALUES (105,1,4);
INSERT INTO `product_color_product_type` VALUES (106,1,111);
INSERT INTO `product_color_product_type` VALUES (107,2,111);
INSERT INTO `product_color_product_type` VALUES (108,3,111);
INSERT INTO `product_color_product_type` VALUES (109,5,111);
INSERT INTO `product_color_product_type` VALUES (110,7,111);
INSERT INTO `product_color_product_type` VALUES (111,8,111);
INSERT INTO `product_color_product_type` VALUES (112,9,111);
INSERT INTO `product_color_product_type` VALUES (113,10,111);
INSERT INTO `product_color_product_type` VALUES (114,11,111);
INSERT INTO `product_color_product_type` VALUES (115,12,111);
INSERT INTO `product_color_product_type` VALUES (116,14,111);
INSERT INTO `product_color_product_type` VALUES (117,15,111);
INSERT INTO `product_color_product_type` VALUES (118,16,111);
INSERT INTO `product_color_product_type` VALUES (119,18,111);
INSERT INTO `product_color_product_type` VALUES (120,19,111);
INSERT INTO `product_color_product_type` VALUES (121,20,111);
INSERT INTO `product_color_product_type` VALUES (122,21,111);
INSERT INTO `product_color_product_type` VALUES (123,26,111);
INSERT INTO `product_color_product_type` VALUES (124,28,111);
INSERT INTO `product_color_product_type` VALUES (125,30,111);
INSERT INTO `product_color_product_type` VALUES (126,2,10);
INSERT INTO `product_color_product_type` VALUES (127,19,10);
INSERT INTO `product_color_product_type` VALUES (128,14,10);
INSERT INTO `product_color_product_type` VALUES (129,23,10);
INSERT INTO `product_color_product_type` VALUES (130,11,10);
INSERT INTO `product_color_product_type` VALUES (131,18,10);
INSERT INTO `product_color_product_type` VALUES (132,3,10);
INSERT INTO `product_color_product_type` VALUES (133,9,10);
INSERT INTO `product_color_product_type` VALUES (134,1,10);
INSERT INTO `product_color_product_type` VALUES (135,2,12);
INSERT INTO `product_color_product_type` VALUES (136,2,9);
INSERT INTO `product_color_product_type` VALUES (137,8,9);
INSERT INTO `product_color_product_type` VALUES (138,14,9);
INSERT INTO `product_color_product_type` VALUES (139,22,9);
INSERT INTO `product_color_product_type` VALUES (140,6,9);
INSERT INTO `product_color_product_type` VALUES (141,18,9);
INSERT INTO `product_color_product_type` VALUES (142,21,9);
INSERT INTO `product_color_product_type` VALUES (143,3,9);
INSERT INTO `product_color_product_type` VALUES (144,5,9);
INSERT INTO `product_color_product_type` VALUES (145,7,9);
INSERT INTO `product_color_product_type` VALUES (146,9,9);
INSERT INTO `product_color_product_type` VALUES (147,1,9);
INSERT INTO `product_color_product_type` VALUES (148,1,2);
INSERT INTO `product_color_product_type` VALUES (149,2,2);
INSERT INTO `product_color_product_type` VALUES (150,3,2);
INSERT INTO `product_color_product_type` VALUES (153,7,2);
INSERT INTO `product_color_product_type` VALUES (154,8,2);
INSERT INTO `product_color_product_type` VALUES (155,9,2);
INSERT INTO `product_color_product_type` VALUES (156,14,2);
INSERT INTO `product_color_product_type` VALUES (157,15,2);
INSERT INTO `product_color_product_type` VALUES (158,16,2);
INSERT INTO `product_color_product_type` VALUES (159,18,2);
INSERT INTO `product_color_product_type` VALUES (161,22,2);
INSERT INTO `product_color_product_type` VALUES (162,15,7);
INSERT INTO `product_color_product_type` VALUES (163,2,7);
INSERT INTO `product_color_product_type` VALUES (164,8,7);
INSERT INTO `product_color_product_type` VALUES (165,14,7);
INSERT INTO `product_color_product_type` VALUES (166,22,7);
INSERT INTO `product_color_product_type` VALUES (167,6,7);
INSERT INTO `product_color_product_type` VALUES (168,18,7);
INSERT INTO `product_color_product_type` VALUES (169,3,7);
INSERT INTO `product_color_product_type` VALUES (170,5,7);
INSERT INTO `product_color_product_type` VALUES (171,7,7);
INSERT INTO `product_color_product_type` VALUES (172,9,7);
INSERT INTO `product_color_product_type` VALUES (173,1,7);
INSERT INTO `product_color_product_type` VALUES (174,2,5);
INSERT INTO `product_color_product_type` VALUES (175,14,5);
INSERT INTO `product_color_product_type` VALUES (176,3,5);
INSERT INTO `product_color_product_type` VALUES (177,7,5);
INSERT INTO `product_color_product_type` VALUES (178,9,5);
INSERT INTO `product_color_product_type` VALUES (179,1,5);
INSERT INTO `product_color_product_type` VALUES (180,2,11);
INSERT INTO `product_color_product_type` VALUES (181,19,11);
INSERT INTO `product_color_product_type` VALUES (182,14,11);
INSERT INTO `product_color_product_type` VALUES (183,23,11);
INSERT INTO `product_color_product_type` VALUES (184,11,11);
INSERT INTO `product_color_product_type` VALUES (185,18,11);
INSERT INTO `product_color_product_type` VALUES (186,3,11);
INSERT INTO `product_color_product_type` VALUES (187,9,11);
INSERT INTO `product_color_product_type` VALUES (188,1,11);
INSERT INTO `product_color_product_type` VALUES (189,2,113);
INSERT INTO `product_color_product_type` VALUES (190,1,113);
INSERT INTO `product_color_product_type` VALUES (191,35,17);
INSERT INTO `product_color_product_type` VALUES (192,36,17);
INSERT INTO `product_color_product_type` VALUES (193,2,114);
INSERT INTO `product_color_product_type` VALUES (197,2,115);
INSERT INTO `product_color_product_type` VALUES (198,1,115);
INSERT INTO `product_color_product_type` VALUES (199,1,116);
INSERT INTO `product_color_product_type` VALUES (200,2,116);
INSERT INTO `product_color_product_type` VALUES (201,4,116);
INSERT INTO `product_color_product_type` VALUES (202,5,116);
INSERT INTO `product_color_product_type` VALUES (204,9,116);
INSERT INTO `product_color_product_type` VALUES (205,10,116);
INSERT INTO `product_color_product_type` VALUES (210,30,116);
INSERT INTO `product_color_product_type` VALUES (217,40,116);
INSERT INTO `product_color_product_type` VALUES (219,42,116);
INSERT INTO `product_color_product_type` VALUES (226,49,116);
INSERT INTO `product_color_product_type` VALUES (232,55,116);
INSERT INTO `product_color_product_type` VALUES (246,14,116);
INSERT INTO `product_color_product_type` VALUES (247,15,116);
INSERT INTO `product_color_product_type` VALUES (248,16,116);
INSERT INTO `product_color_product_type` VALUES (249,18,116);
INSERT INTO `product_color_product_type` VALUES (250,6,116);
INSERT INTO `product_color_product_type` VALUES (251,37,116);
INSERT INTO `product_color_product_type` VALUES (252,26,116);
INSERT INTO `product_color_product_type` VALUES (253,29,116);
INSERT INTO `product_color_product_type` VALUES (254,38,116);
INSERT INTO `product_color_product_type` VALUES (255,39,116);
INSERT INTO `product_color_product_type` VALUES (256,41,116);
INSERT INTO `product_color_product_type` VALUES (257,43,116);
INSERT INTO `product_color_product_type` VALUES (258,44,116);
INSERT INTO `product_color_product_type` VALUES (259,45,116);
INSERT INTO `product_color_product_type` VALUES (260,46,116);
INSERT INTO `product_color_product_type` VALUES (261,50,116);
INSERT INTO `product_color_product_type` VALUES (262,51,116);
INSERT INTO `product_color_product_type` VALUES (263,53,116);
INSERT INTO `product_color_product_type` VALUES (264,54,116);
INSERT INTO `product_color_product_type` VALUES (265,56,116);
INSERT INTO `product_color_product_type` VALUES (266,57,116);
INSERT INTO `product_color_product_type` VALUES (267,58,116);
INSERT INTO `product_color_product_type` VALUES (268,7,116);
INSERT INTO `product_color_product_type` VALUES (269,24,116);
INSERT INTO `product_color_product_type` VALUES (270,31,116);
INSERT INTO `product_color_product_type` VALUES (271,32,116);
INSERT INTO `product_color_product_type` VALUES (272,33,116);
INSERT INTO `product_color_product_type` VALUES (273,34,116);
INSERT INTO `product_color_product_type` VALUES (274,59,116);
INSERT INTO `product_color_product_type` VALUES (275,60,116);
INSERT INTO `product_color_product_type` VALUES (276,62,116);
INSERT INTO `product_color_product_type` VALUES (277,63,116);
INSERT INTO `product_color_product_type` VALUES (278,64,116);
INSERT INTO `product_color_product_type` VALUES (279,65,116);
INSERT INTO `product_color_product_type` VALUES (280,66,116);
INSERT INTO `product_color_product_type` VALUES (281,67,116);
INSERT INTO `product_color_product_type` VALUES (282,68,116);
INSERT INTO `product_color_product_type` VALUES (288,37,117);
INSERT INTO `product_color_product_type` VALUES (289,2,117);
INSERT INTO `product_color_product_type` VALUES (295,49,117);
INSERT INTO `product_color_product_type` VALUES (296,70,117);
INSERT INTO `product_color_product_type` VALUES (297,71,117);
INSERT INTO `product_color_product_type` VALUES (298,1,118);
INSERT INTO `product_color_product_type` VALUES (299,49,118);
INSERT INTO `product_color_product_type` VALUES (300,3,118);
INSERT INTO `product_color_product_type` VALUES (301,2,118);
INSERT INTO `product_color_product_type` VALUES (302,42,118);
INSERT INTO `product_color_product_type` VALUES (303,9,118);
INSERT INTO `product_color_product_type` VALUES (304,1,119);
INSERT INTO `product_color_product_type` VALUES (305,2,119);
INSERT INTO `product_color_product_type` VALUES (306,49,119);
INSERT INTO `product_color_product_type` VALUES (307,42,119);
INSERT INTO `product_color_product_type` VALUES (308,9,119);
INSERT INTO `product_color_product_type` VALUES (309,3,119);
INSERT INTO `product_color_product_type` VALUES (310,72,1);
INSERT INTO `product_color_product_type` VALUES (311,73,1);
INSERT INTO `product_color_product_type` VALUES (312,2,120);
INSERT INTO `product_color_product_type` VALUES (313,72,120);
INSERT INTO `product_color_product_type` VALUES (314,52,120);
INSERT INTO `product_color_product_type` VALUES (315,65,120);
INSERT INTO `product_color_product_type` VALUES (316,49,120);
INSERT INTO `product_color_product_type` VALUES (317,52,116);
INSERT INTO `product_color_product_type` VALUES (318,2,121);
INSERT INTO `product_color_product_type` VALUES (319,65,121);
INSERT INTO `product_color_product_type` VALUES (320,72,121);
INSERT INTO `product_color_product_type` VALUES (321,49,121);
INSERT INTO `product_color_product_type` VALUES (322,52,121);
/*!40000 ALTER TABLE `product_color_product_type` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `product_position_product_type` WRITE;
/*!40000 ALTER TABLE `product_position_product_type` DISABLE KEYS */;
INSERT INTO `product_position_product_type` VALUES (1,1,1,1);
INSERT INTO `product_position_product_type` VALUES (2,1,2,1);
INSERT INTO `product_position_product_type` VALUES (3,1,5,1);
INSERT INTO `product_position_product_type` VALUES (4,3,1,1);
INSERT INTO `product_position_product_type` VALUES (5,3,2,1);
INSERT INTO `product_position_product_type` VALUES (6,3,5,1);
INSERT INTO `product_position_product_type` VALUES (7,6,1,1);
INSERT INTO `product_position_product_type` VALUES (8,6,2,1);
INSERT INTO `product_position_product_type` VALUES (9,6,5,1);
INSERT INTO `product_position_product_type` VALUES (10,8,1,1);
INSERT INTO `product_position_product_type` VALUES (11,8,2,1);
INSERT INTO `product_position_product_type` VALUES (12,8,5,1);
INSERT INTO `product_position_product_type` VALUES (13,105,1,1);
INSERT INTO `product_position_product_type` VALUES (14,107,1,1);
INSERT INTO `product_position_product_type` VALUES (15,108,1,1);
INSERT INTO `product_position_product_type` VALUES (16,106,1,1);
INSERT INTO `product_position_product_type` VALUES (17,109,1,1);
INSERT INTO `product_position_product_type` VALUES (18,4,1,1);
INSERT INTO `product_position_product_type` VALUES (19,4,2,1);
INSERT INTO `product_position_product_type` VALUES (20,4,5,1);
INSERT INTO `product_position_product_type` VALUES (21,111,1,1);
INSERT INTO `product_position_product_type` VALUES (22,10,2,1);
INSERT INTO `product_position_product_type` VALUES (23,10,5,1);
INSERT INTO `product_position_product_type` VALUES (24,12,3,1);
INSERT INTO `product_position_product_type` VALUES (25,12,4,1);
INSERT INTO `product_position_product_type` VALUES (26,9,1,1);
INSERT INTO `product_position_product_type` VALUES (27,9,2,1);
INSERT INTO `product_position_product_type` VALUES (28,9,5,1);
INSERT INTO `product_position_product_type` VALUES (29,2,1,1);
INSERT INTO `product_position_product_type` VALUES (30,2,2,1);
INSERT INTO `product_position_product_type` VALUES (31,2,5,1);
INSERT INTO `product_position_product_type` VALUES (32,7,1,1);
INSERT INTO `product_position_product_type` VALUES (33,7,2,1);
INSERT INTO `product_position_product_type` VALUES (34,7,5,1);
INSERT INTO `product_position_product_type` VALUES (35,5,1,1);
INSERT INTO `product_position_product_type` VALUES (36,5,2,1);
INSERT INTO `product_position_product_type` VALUES (37,5,5,1);
INSERT INTO `product_position_product_type` VALUES (38,11,2,1);
INSERT INTO `product_position_product_type` VALUES (39,11,5,1);
INSERT INTO `product_position_product_type` VALUES (40,113,1,1);
INSERT INTO `product_position_product_type` VALUES (41,113,2,1);
INSERT INTO `product_position_product_type` VALUES (42,113,5,1);
INSERT INTO `product_position_product_type` VALUES (43,17,1,1);
INSERT INTO `product_position_product_type` VALUES (44,17,2,1);
INSERT INTO `product_position_product_type` VALUES (45,114,3,1);
INSERT INTO `product_position_product_type` VALUES (46,114,4,1);
INSERT INTO `product_position_product_type` VALUES (47,115,1,1);
INSERT INTO `product_position_product_type` VALUES (48,116,1,1);
INSERT INTO `product_position_product_type` VALUES (49,115,5,0);
INSERT INTO `product_position_product_type` VALUES (50,117,1,1);
INSERT INTO `product_position_product_type` VALUES (51,118,1,1);
INSERT INTO `product_position_product_type` VALUES (52,118,2,1);
INSERT INTO `product_position_product_type` VALUES (53,118,5,1);
INSERT INTO `product_position_product_type` VALUES (54,119,1,1);
INSERT INTO `product_position_product_type` VALUES (55,119,2,1);
INSERT INTO `product_position_product_type` VALUES (56,119,5,1);
INSERT INTO `product_position_product_type` VALUES (57,120,1,1);
INSERT INTO `product_position_product_type` VALUES (58,120,6,0);
INSERT INTO `product_position_product_type` VALUES (59,121,1,1);
INSERT INTO `product_position_product_type` VALUES (62,120,7,0);
INSERT INTO `product_position_product_type` VALUES (63,121,7,0);
/*!40000 ALTER TABLE `product_position_product_type` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `product_positions` WRITE;
/*!40000 ALTER TABLE `product_positions` DISABLE KEYS */;
INSERT INTO `product_positions` VALUES (1,'Frontale','def','Qui puoi scegliere la posizione e dimensione del design quando stampato frontalmente.',1,0,0,NULL,NULL);
INSERT INTO `product_positions` VALUES (2,'Scudetto','scu','Se abilitato, i tuoi clienti potranno scegliere se comprare questo design stampato grande al centro, oppure più piccolo in posizione laterale (scudetto).',1,0,1,NULL,NULL);
INSERT INTO `product_positions` VALUES (3,'Destra','rig','',1,0,0,NULL,NULL);
INSERT INTO `product_positions` VALUES (4,'Sinistra','lef','',1,0,0,NULL,NULL);
INSERT INTO `product_positions` VALUES (5,'Retro','bck','Qui puoi scegliere la posizione e dimensione del design quando stampato sul retro.',1,0,0,NULL,NULL);
INSERT INTO `product_positions` VALUES (7,'Lato','side','',0,0,0,'2025-06-23 01:16:43','2025-06-23 01:16:43');
/*!40000 ALTER TABLE `product_positions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `product_size_product_type` WRITE;
/*!40000 ALTER TABLE `product_size_product_type` DISABLE KEYS */;
INSERT INTO `product_size_product_type` VALUES (1,1,1);
INSERT INTO `product_size_product_type` VALUES (2,2,1);
INSERT INTO `product_size_product_type` VALUES (3,3,1);
INSERT INTO `product_size_product_type` VALUES (4,4,1);
INSERT INTO `product_size_product_type` VALUES (5,5,1);
INSERT INTO `product_size_product_type` VALUES (6,1,3);
INSERT INTO `product_size_product_type` VALUES (7,2,3);
INSERT INTO `product_size_product_type` VALUES (8,3,3);
INSERT INTO `product_size_product_type` VALUES (9,4,3);
INSERT INTO `product_size_product_type` VALUES (10,5,3);
INSERT INTO `product_size_product_type` VALUES (11,1,6);
INSERT INTO `product_size_product_type` VALUES (12,2,6);
INSERT INTO `product_size_product_type` VALUES (13,3,6);
INSERT INTO `product_size_product_type` VALUES (14,4,6);
INSERT INTO `product_size_product_type` VALUES (15,5,6);
INSERT INTO `product_size_product_type` VALUES (16,1,8);
INSERT INTO `product_size_product_type` VALUES (17,2,8);
INSERT INTO `product_size_product_type` VALUES (18,3,8);
INSERT INTO `product_size_product_type` VALUES (19,4,8);
INSERT INTO `product_size_product_type` VALUES (20,5,8);
INSERT INTO `product_size_product_type` VALUES (21,7,105);
INSERT INTO `product_size_product_type` VALUES (22,8,107);
INSERT INTO `product_size_product_type` VALUES (23,12,107);
INSERT INTO `product_size_product_type` VALUES (24,9,108);
INSERT INTO `product_size_product_type` VALUES (25,10,106);
INSERT INTO `product_size_product_type` VALUES (26,11,109);
INSERT INTO `product_size_product_type` VALUES (27,1,4);
INSERT INTO `product_size_product_type` VALUES (28,2,4);
INSERT INTO `product_size_product_type` VALUES (29,3,4);
INSERT INTO `product_size_product_type` VALUES (30,4,4);
INSERT INTO `product_size_product_type` VALUES (31,5,4);
INSERT INTO `product_size_product_type` VALUES (32,13,111);
INSERT INTO `product_size_product_type` VALUES (33,1,10);
INSERT INTO `product_size_product_type` VALUES (34,2,10);
INSERT INTO `product_size_product_type` VALUES (35,3,10);
INSERT INTO `product_size_product_type` VALUES (36,4,10);
INSERT INTO `product_size_product_type` VALUES (37,5,10);
INSERT INTO `product_size_product_type` VALUES (38,13,12);
INSERT INTO `product_size_product_type` VALUES (39,1,9);
INSERT INTO `product_size_product_type` VALUES (40,2,9);
INSERT INTO `product_size_product_type` VALUES (41,3,9);
INSERT INTO `product_size_product_type` VALUES (42,4,9);
INSERT INTO `product_size_product_type` VALUES (43,5,9);
INSERT INTO `product_size_product_type` VALUES (44,1,2);
INSERT INTO `product_size_product_type` VALUES (45,2,2);
INSERT INTO `product_size_product_type` VALUES (46,3,2);
INSERT INTO `product_size_product_type` VALUES (47,4,2);
INSERT INTO `product_size_product_type` VALUES (48,5,2);
INSERT INTO `product_size_product_type` VALUES (49,1,7);
INSERT INTO `product_size_product_type` VALUES (50,2,7);
INSERT INTO `product_size_product_type` VALUES (51,3,7);
INSERT INTO `product_size_product_type` VALUES (52,4,7);
INSERT INTO `product_size_product_type` VALUES (53,5,7);
INSERT INTO `product_size_product_type` VALUES (54,1,5);
INSERT INTO `product_size_product_type` VALUES (55,2,5);
INSERT INTO `product_size_product_type` VALUES (56,3,5);
INSERT INTO `product_size_product_type` VALUES (57,4,5);
INSERT INTO `product_size_product_type` VALUES (58,5,5);
INSERT INTO `product_size_product_type` VALUES (59,1,11);
INSERT INTO `product_size_product_type` VALUES (60,2,11);
INSERT INTO `product_size_product_type` VALUES (61,3,11);
INSERT INTO `product_size_product_type` VALUES (62,4,11);
INSERT INTO `product_size_product_type` VALUES (63,5,11);
INSERT INTO `product_size_product_type` VALUES (64,1,113);
INSERT INTO `product_size_product_type` VALUES (65,2,113);
INSERT INTO `product_size_product_type` VALUES (66,3,113);
INSERT INTO `product_size_product_type` VALUES (67,4,113);
INSERT INTO `product_size_product_type` VALUES (68,5,113);
INSERT INTO `product_size_product_type` VALUES (69,1,17);
INSERT INTO `product_size_product_type` VALUES (70,2,17);
INSERT INTO `product_size_product_type` VALUES (71,3,17);
INSERT INTO `product_size_product_type` VALUES (72,4,17);
INSERT INTO `product_size_product_type` VALUES (73,5,17);
INSERT INTO `product_size_product_type` VALUES (74,1,114);
INSERT INTO `product_size_product_type` VALUES (75,2,114);
INSERT INTO `product_size_product_type` VALUES (76,3,114);
INSERT INTO `product_size_product_type` VALUES (77,4,114);
INSERT INTO `product_size_product_type` VALUES (78,5,114);
INSERT INTO `product_size_product_type` VALUES (79,13,115);
INSERT INTO `product_size_product_type` VALUES (80,14,116);
INSERT INTO `product_size_product_type` VALUES (81,14,117);
INSERT INTO `product_size_product_type` VALUES (82,1,118);
INSERT INTO `product_size_product_type` VALUES (83,2,118);
INSERT INTO `product_size_product_type` VALUES (84,3,118);
INSERT INTO `product_size_product_type` VALUES (85,4,118);
INSERT INTO `product_size_product_type` VALUES (86,5,118);
INSERT INTO `product_size_product_type` VALUES (87,1,119);
INSERT INTO `product_size_product_type` VALUES (88,2,119);
INSERT INTO `product_size_product_type` VALUES (89,3,119);
INSERT INTO `product_size_product_type` VALUES (90,4,119);
INSERT INTO `product_size_product_type` VALUES (91,5,119);
INSERT INTO `product_size_product_type` VALUES (92,13,120);
INSERT INTO `product_size_product_type` VALUES (93,13,121);
/*!40000 ALTER TABLE `product_size_product_type` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `items_sizes` WRITE;
/*!40000 ALTER TABLE `items_sizes` DISABLE KEYS */;
INSERT INTO `items_sizes` VALUES (1,'s','S',0);
INSERT INTO `items_sizes` VALUES (2,'m','M',0);
INSERT INTO `items_sizes` VALUES (3,'l','L',0);
INSERT INTO `items_sizes` VALUES (4,'xl','XL',0);
INSERT INTO `items_sizes` VALUES (5,'xxl','XXL',0);
INSERT INTO `items_sizes` VALUES (7,'bag','34 x 45 cm',0);
INSERT INTO `items_sizes` VALUES (8,'casS','20 x 12 cm',0);
INSERT INTO `items_sizes` VALUES (9,'pad','24,5 x 19 cm',0);
INSERT INTO `items_sizes` VALUES (10,'jut','30 x 45 cm',0);
INSERT INTO `items_sizes` VALUES (11,'epk','43 x 34 cm',0);
INSERT INTO `items_sizes` VALUES (12,'casL','28 x 20 cm',5);
INSERT INTO `items_sizes` VALUES (13,'uni','Taglia unica',0);
INSERT INTO `items_sizes` VALUES (14,'38x42cm','38 x 42 cm',0);
/*!40000 ALTER TABLE `items_sizes` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `items_mockups` WRITE;
/*!40000 ALTER TABLE `items_mockups` DISABLE KEYS */;
INSERT INTO `items_mockups` VALUES (1,1,1,35,50,18,25,30,90,50,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (3,6,1,34,50,19,25,32,90,54,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (4,8,1,28,50,20,25,44,66,38,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (5,105,1,32,50,16,50,37,90,64,'2024-03-13 23:53:04','2024-03-19 00:22:28');
INSERT INTO `items_mockups` VALUES (6,108,1,3,50,9,50,75,75,81,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (7,106,1,23,50,13,85,54,90,70,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (8,107,1,15,50,30,50,70,90,38,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (9,109,1,23,50,30,50,51,90,30,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (10,4,1,34,50,20,10,32,90,45,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (11,111,1,0,50,22,0,50,90,56,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (12,10,2,50,50,26,10,17,90,30,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (14,12,3,38,50,18,50,36,90,20,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (15,1,2,56,50,20,20,10,90,30,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (16,3,2,54,50,24,5,10,90,30,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (17,6,2,55,50,18,10,10,90,30,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (18,8,2,58,50,28,15,9,90,16,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (19,4,2,52,50,16,10,10,90,30,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (20,5,1,37,50,26,10,26,90,45,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (21,5,2,53,50,20,10,10,90,30,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (22,2,1,36,50,20,17,28,90,60,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (23,2,2,56,50,19,20,10,90,30,'2024-03-13 23:53:04','2026-06-24 23:38:18');
INSERT INTO `items_mockups` VALUES (24,3,1,35,50,26,5,29,90,55,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (25,7,1,34,50,17,25,30,90,54,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (26,7,2,55,50,18,10,10,90,30,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (27,9,1,35,50,20,25,34,85,38,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (28,9,2,58,50,28,15,9,90,16,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (29,11,2,53,50,26,10,19,90,30,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (30,12,4,26,50,18,50,36,90,20,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (31,1,5,33,50,8,10,34,90,70,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (32,2,5,33,50,8,10,34,90,70,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (33,3,5,33,50,8,10,34,90,70,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (34,4,5,34,50,8,10,33,90,70,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (35,5,5,36,50,12,10,26,90,50,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (36,6,5,34,50,8,10,33,90,60,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (37,7,5,33,50,10,10,35,90,70,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (38,8,5,34,50,20,10,33,90,50,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (39,9,5,31,50,20,10,37,90,60,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (40,10,5,34,50,20,10,33,90,50,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (41,11,5,31,50,20,10,37,90,60,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (42,113,1,33,50,18,25,32,90,50,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (43,113,2,55,50,20,20,12,90,30,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (44,113,5,34,50,13,25,34,90,65,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (45,17,5,34,50,20,10,33,90,50,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (46,17,1,28,50,20,25,44,66,38,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (47,17,2,58,50,28,15,9,90,16,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (48,114,1,15,50,26,50,70,90,41,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (49,114,5,14,50,24,50,70,90,46,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (50,114,3,11,50,48,50,20,90,27,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (51,114,4,69,50,48,50,20,90,27,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (52,115,1,20,50,46,40,55,35,36,'2024-03-13 23:53:04','2024-03-19 19:35:08');
INSERT INTO `items_mockups` VALUES (53,116,1,24,50,25,50,50,90,55,'2024-03-26 19:49:28','2024-04-19 05:25:32');
INSERT INTO `items_mockups` VALUES (54,117,1,20,50,21,50,58,90,63,'2024-03-26 19:49:28','2025-02-20 17:41:28');
INSERT INTO `items_mockups` VALUES (55,118,1,35,50,18,25,30,90,50,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (56,118,2,56,50,20,20,10,90,30,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (57,118,5,33,50,8,10,34,90,70,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (58,119,1,36,50,20,17,28,90,60,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (59,119,2,56,50,19,20,8,90,30,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (60,119,5,33,50,8,10,34,90,70,'2024-03-13 23:53:04','2024-03-13 23:53:04');
INSERT INTO `items_mockups` VALUES (61,120,1,14,50,17,50,70,40,60,'2025-06-01 22:05:00','2025-06-01 22:05:00');
INSERT INTO `items_mockups` VALUES (62,121,1,26,50,34,50,48,90,32,'2025-06-23 01:17:44','2025-06-23 15:10:31');
/*!40000 ALTER TABLE `items_mockups` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `prices_default` WRITE;
/*!40000 ALTER TABLE `prices_default` DISABLE KEYS */;
INSERT INTO `prices_default` VALUES (1,1,15.49,3.5,2.5,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (2,2,15.49,3.5,2.5,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (3,3,15.99,3.95,2.9,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (4,4,16.49,3.5,2.5,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (5,5,16.49,3.5,2.5,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (6,6,26.99,5,4,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (7,7,26.99,5,4,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (8,8,32.49,5.75,4.75,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (9,9,32.49,5.75,4.75,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (10,10,35.49,5,4,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (11,11,35.49,5,4,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (12,12,9.49,0.8,0.8,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (13,13,8.99,0.8,0.8,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (17,17,35.49,5.75,4.75,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (105,105,15.49,2.5,2.5,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (106,106,17.99,2,2,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (107,107,12.49,0.8,0.8,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (108,108,12.49,1.2,1.2,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (109,109,30.49,3.5,3.5,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (111,111,1,0,0,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (113,113,19.49,3.5,2.5,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (114,114,23.9,5.25,5.25,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (115,115,13.99,2.8,2.8,'2024-03-13 23:49:08','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (116,116,13.99,2.8,2.8,'2024-03-26 19:49:28','2024-10-08 11:30:06');
INSERT INTO `prices_default` VALUES (117,117,18.99,2.8,2.8,'2025-02-18 16:10:08','2025-02-18 16:10:08');
INSERT INTO `prices_default` VALUES (118,118,26.49,3.5,2.5,'2025-03-09 18:25:27','2025-03-09 18:25:27');
INSERT INTO `prices_default` VALUES (119,119,26.49,3.5,2.5,'2025-03-09 18:45:04','2025-03-09 18:45:04');
INSERT INTO `prices_default` VALUES (120,120,19.99,4.5,4.5,'2025-06-01 22:03:32','2025-06-30 15:35:03');
INSERT INTO `prices_default` VALUES (121,121,8.99,3,3,'2025-06-23 01:14:30','2025-06-29 19:57:12');
/*!40000 ALTER TABLE `prices_default` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `items_prices` WRITE;
/*!40000 ALTER TABLE `items_prices` DISABLE KEYS */;
INSERT INTO `items_prices` VALUES (72423,72423,48.9,21.43,20.43,'2024-03-07 21:08:24','2024-10-08 11:30:06');
INSERT INTO `items_prices` VALUES (72429,72429,48.9,21.43,20.43,'2024-03-07 21:08:24','2024-10-08 11:30:06');
INSERT INTO `items_prices` VALUES (72432,72432,21.9,8.32,7.32,'2024-03-07 21:08:24','2024-10-08 11:30:06');
INSERT INTO `items_prices` VALUES (72445,72445,21.9,8.32,7.32,'2024-03-07 21:08:24','2024-10-08 11:30:06');
INSERT INTO `items_prices` VALUES (72451,72451,21.9,8.32,7.32,'2024-03-07 21:08:24','2024-10-08 11:30:06');
INSERT INTO `items_prices` VALUES (72464,72464,21.9,8.32,7.32,'2024-03-07 21:08:24','2024-10-08 11:30:06');
INSERT INTO `items_prices` VALUES (72470,72470,21.9,8.32,7.32,'2024-03-07 21:08:24','2024-10-08 11:30:06');
INSERT INTO `items_prices` VALUES (72483,72483,21.9,8.32,7.32,'2024-03-07 21:08:24','2024-10-08 11:30:06');
INSERT INTO `items_prices` VALUES (75401,75401,12.16,0,0,'2024-03-07 21:08:24','2024-10-08 11:30:06');
INSERT INTO `items_prices` VALUES (75403,75403,15.32,0,0,'2024-03-07 21:08:24','2024-10-08 11:30:06');
INSERT INTO `items_prices` VALUES (75581,75581,18.99,0,0,'2024-03-07 21:08:24','2024-10-08 11:30:06');
INSERT INTO `items_prices` VALUES (75905,75905,19,4.35,4.35,'2024-03-07 21:08:24','2024-10-08 11:30:06');
INSERT INTO `items_prices` VALUES (75925,75925,26,5.94,5.94,'2024-03-07 21:08:24','2024-10-08 11:30:06');
INSERT INTO `items_prices` VALUES (79510,79510,26,0,0,'2024-03-07 21:08:24','2024-10-08 11:30:06');
INSERT INTO `items_prices` VALUES (96708,96708,10.82,0,-1,'2024-03-07 22:11:49','2024-10-08 11:30:06');
INSERT INTO `items_prices` VALUES (96709,96709,20.32,0,-1,'2024-03-07 22:17:34','2024-10-08 11:30:06');
INSERT INTO `items_prices` VALUES (96710,96710,10.82,0,-1,'2024-03-07 22:24:01','2024-10-08 11:30:06');
INSERT INTO `items_prices` VALUES (96711,96711,20.32,0,-1,'2024-03-07 22:27:22','2024-10-08 11:30:06');
INSERT INTO `items_prices` VALUES (96712,106317,52.49,20.75,19.75,'2024-07-05 16:30:29','2024-10-08 11:30:06');
INSERT INTO `items_prices` VALUES (96713,142479,30,3.5,2.5,'2025-10-18 00:02:59','2025-10-18 00:02:59');
INSERT INTO `items_prices` VALUES (96714,142480,0,4.5,4.5,'2025-10-18 00:03:50','2025-10-18 00:03:50');
INSERT INTO `items_prices` VALUES (96715,142482,30,4.2,4.2,'2025-10-18 00:09:52','2025-10-21 15:31:58');
INSERT INTO `items_prices` VALUES (96716,145171,18,5.38,4.38,'2026-06-25 01:32:46','2026-06-25 19:46:51');
INSERT INTO `items_prices` VALUES (96717,145172,18,5.38,4.38,'2026-06-25 01:42:35','2026-06-25 18:14:59');
INSERT INTO `items_prices` VALUES (96719,145174,10,3.76,3.76,'2026-06-25 02:01:15','2026-06-25 22:30:13');
INSERT INTO `items_prices` VALUES (96720,145173,25,8.26,8.26,'2026-06-25 20:29:38','2026-06-25 20:29:59');
/*!40000 ALTER TABLE `items_prices` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `items_genders` WRITE;
/*!40000 ALTER TABLE `items_genders` DISABLE KEYS */;
INSERT INTO `items_genders` VALUES (1,'m','Uomo');
INSERT INTO `items_genders` VALUES (2,'f','Donna');
INSERT INTO `items_genders` VALUES (3,'u','Unisex');
/*!40000 ALTER TABLE `items_genders` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `items_info` WRITE;
/*!40000 ALTER TABLE `items_info` DISABLE KEYS */;
INSERT INTO `items_info` VALUES (27254,27254,'La nostra morf multiuso è resistente, traspirante ed estremamente confortevole grazie all\'assenza di cuciture. La colorazione vivace e look elegante la rendono perfetta per tutte le occasioni.','- 100% poliestere (microfibra)\r\n- traspirante\r\n- multiuso\r\n- senza cuciture per un comfort maggiore\r\n- lavabile in lavatrice',NULL,NULL);
INSERT INTO `items_info` VALUES (27255,27255,'Il nostro set invernale, composto da un caldo berretto e una comoda sciarpa, è perfetto per camminare all\'aria aperta nelle giornate più fredde. Non uscire senza!','- 100% acrilico\r\n- traspirante\r\n- set composto da sciarpa e berretto\r\n- dimensioni sciarpa: 20 x 180 cm',NULL,NULL);
INSERT INTO `items_info` VALUES (75401,75401,'','',NULL,NULL);
INSERT INTO `items_info` VALUES (75403,75403,'','',NULL,NULL);
INSERT INTO `items_info` VALUES (75581,75581,'Il nostro berretto Apres è perfetto per la stagione invernale. È molto caldo, grazie alla sua maglia pesante multi filo, e confortevole, ideale per una camminata all\'aria aperta nelle giornate più fredde.','- 100% poliacrilico\r\n- Maglia pesante multi filo\r\n- Fascia termica in shearling per calore extra',NULL,NULL);
INSERT INTO `items_info` VALUES (75772,75772,'','',NULL,NULL);
INSERT INTO `items_info` VALUES (79510,79510,'','',NULL,NULL);
INSERT INTO `items_info` VALUES (81751,81751,'','',NULL,NULL);
INSERT INTO `items_info` VALUES (83872,83872,'','',NULL,NULL);
INSERT INTO `items_info` VALUES (96706,96706,'',NULL,NULL,NULL);
INSERT INTO `items_info` VALUES (96707,96707,'',NULL,NULL,NULL);
INSERT INTO `items_info` VALUES (96708,96708,'',NULL,NULL,NULL);
INSERT INTO `items_info` VALUES (96709,96709,'',NULL,NULL,NULL);
INSERT INTO `items_info` VALUES (96710,96710,'',NULL,NULL,NULL);
INSERT INTO `items_info` VALUES (96711,96711,'',NULL,NULL,NULL);
INSERT INTO `items_info` VALUES (96712,106317,'',NULL,'2024-07-05 16:30:29','2024-07-05 16:30:29');
INSERT INTO `items_info` VALUES (96713,142477,NULL,NULL,'2025-10-17 23:59:36','2025-10-17 23:59:36');
INSERT INTO `items_info` VALUES (96714,142478,NULL,NULL,'2025-10-18 00:02:02','2025-10-18 00:02:02');
INSERT INTO `items_info` VALUES (96715,142479,NULL,NULL,'2025-10-18 00:02:59','2025-10-18 00:02:59');
INSERT INTO `items_info` VALUES (96716,142480,NULL,NULL,'2025-10-18 00:03:50','2025-10-18 00:03:50');
INSERT INTO `items_info` VALUES (96717,142481,NULL,NULL,'2025-10-18 00:09:12','2025-10-18 00:09:12');
INSERT INTO `items_info` VALUES (96718,142482,NULL,NULL,'2025-10-18 00:09:52','2025-10-18 00:09:52');
INSERT INTO `items_info` VALUES (96719,145171,NULL,NULL,'2026-06-25 01:32:46','2026-06-25 01:32:46');
INSERT INTO `items_info` VALUES (96720,145172,NULL,NULL,'2026-06-25 01:42:35','2026-06-25 01:42:35');
INSERT INTO `items_info` VALUES (96721,145173,NULL,NULL,'2026-06-25 01:51:58','2026-06-25 01:51:58');
INSERT INTO `items_info` VALUES (96722,145174,NULL,NULL,'2026-06-25 02:01:15','2026-06-25 02:01:15');
/*!40000 ALTER TABLE `items_info` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `items_types_measures` WRITE;
/*!40000 ALTER TABLE `items_types_measures` DISABLE KEYS */;
INSERT INTO `items_types_measures` VALUES (1,1,'s','43,69',45,71,'2024-03-13 23:50:06','2025-05-27 15:07:54');
INSERT INTO `items_types_measures` VALUES (2,1,'m','48,71',50,73,'2024-03-13 23:50:06','2025-05-27 15:08:17');
INSERT INTO `items_types_measures` VALUES (3,1,'l','54,77',55,76,'2024-03-13 23:50:06','2025-05-27 15:08:27');
INSERT INTO `items_types_measures` VALUES (4,1,'xl','59,79',60,78,'2024-03-13 23:50:06','2025-05-27 15:08:36');
INSERT INTO `items_types_measures` VALUES (5,1,'xxl','65,84',66,81,'2024-03-13 23:50:06','2025-05-27 15:08:50');
INSERT INTO `items_types_measures` VALUES (6,2,'s','36,64',43,63,'2024-03-13 23:50:06','2025-05-27 15:09:35');
INSERT INTO `items_types_measures` VALUES (7,2,'m','39,66',45,66,'2024-03-13 23:50:06','2025-05-27 15:09:41');
INSERT INTO `items_types_measures` VALUES (8,2,'l','40,69',48,67,'2024-03-13 23:50:06','2025-05-27 15:09:48');
INSERT INTO `items_types_measures` VALUES (9,2,'xl','46,70',50,68,'2024-03-13 23:50:06','2025-05-27 15:09:55');
INSERT INTO `items_types_measures` VALUES (10,2,'xxl','51,71',54,71,'2024-03-13 23:50:06','2025-05-27 15:10:02');
INSERT INTO `items_types_measures` VALUES (11,6,'s','51,69',51,69,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (15,6,'xxl','71,79',71,79,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (18,3,'s','36,62',43,63,'2024-03-13 23:50:06','2025-05-27 15:10:41');
INSERT INTO `items_types_measures` VALUES (19,3,'m','38,66',45,66,'2024-03-13 23:50:06','2025-05-27 15:10:48');
INSERT INTO `items_types_measures` VALUES (20,3,'l','40,67',48,67,'2024-03-13 23:50:06','2025-05-27 15:11:00');
INSERT INTO `items_types_measures` VALUES (21,3,'xl','45,70',50,68,'2024-03-13 23:50:06','2025-05-27 15:11:12');
INSERT INTO `items_types_measures` VALUES (22,3,'xxl','48,72',54,71,'2024-03-13 23:50:06','2025-05-27 15:11:21');
INSERT INTO `items_types_measures` VALUES (24,4,'s','46,71',45,71,'2024-03-13 23:50:06','2025-05-27 15:17:34');
INSERT INTO `items_types_measures` VALUES (25,4,'m','49,75',50,73,'2024-03-13 23:50:06','2025-05-27 15:17:40');
INSERT INTO `items_types_measures` VALUES (26,4,'l','54,75',55,76,'2024-03-13 23:50:06','2025-05-27 15:17:46');
INSERT INTO `items_types_measures` VALUES (27,4,'xl','60,78',60,78,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (28,4,'xxl','63,79',66,81,'2024-03-13 23:50:06','2025-05-27 15:17:56');
INSERT INTO `items_types_measures` VALUES (30,5,'s','36,62',43,63,'2024-03-13 23:50:06','2025-05-27 15:18:19');
INSERT INTO `items_types_measures` VALUES (31,5,'m','39,66',45,66,'2024-03-13 23:50:06','2025-05-27 15:18:24');
INSERT INTO `items_types_measures` VALUES (32,5,'l','43,67',48,67,'2024-03-13 23:50:06','2025-05-27 15:18:29');
INSERT INTO `items_types_measures` VALUES (33,5,'xl','47,70',50,68,'2024-03-13 23:50:06','2025-05-27 15:18:35');
INSERT INTO `items_types_measures` VALUES (34,5,'xxl','52,72',54,71,'2024-03-13 23:50:06','2025-05-27 15:18:39');
INSERT INTO `items_types_measures` VALUES (37,6,'m','55,68',55,68,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (38,7,'l','58,70',58,70,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (39,7,'xl','65,74',65,74,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (42,17,'s','51,69',51,69,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (43,17,'m','56,71',56,71,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (44,17,'l','61,74',61,74,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (45,17,'xl','66,76',66,76,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (46,17,'xxl','71,79',71,79,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (48,11,'s','47,67',47,67,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (49,11,'m','53,71',53,71,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (50,11,'l','57,73',57,73,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (51,11,'xl','64,75',64,75,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (52,11,'xxl','67,77',67,77,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (55,9,'xxl','71,79',71,79,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (56,9,'xl','66,76',66,76,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (57,9,'l','61,74',61,74,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (58,9,'s','51,69',51,69,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (59,9,'m','56,71',56,71,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (62,7,'m','55,68',55,68,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (63,6,'l','58,70',58,70,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (64,6,'xl','65,74',65,74,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (65,7,'xxl','71,79',71,79,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (66,7,'s','51,69',51,69,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (68,10,'xxl','67,77',67,77,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (69,10,'xl','64,75',64,75,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (70,10,'l','57,73',57,73,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (71,10,'m','53,71',53,71,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (72,10,'s','47,67',47,67,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (73,113,'s','51,70',51,70,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (74,113,'m','54,72',54,72,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (75,113,'l','57,74',57,74,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (76,113,'xl','60,76',60,76,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (77,113,'xxl','63,78',63,78,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (78,8,'s','51,69',51,69,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (79,8,'m','56,71',56,71,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (80,8,'l','61,74',61,74,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (81,8,'xl','66,76',66,76,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (82,8,'xxl','71,79',71,79,'2024-03-13 23:50:06','2025-05-27 14:54:58');
INSERT INTO `items_types_measures` VALUES (84,116,'uni','0,0',0,0,'2024-03-26 19:49:28','2024-03-26 19:49:28');
/*!40000 ALTER TABLE `items_types_measures` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `product_type_infos` WRITE;
/*!40000 ALTER TABLE `product_type_infos` DISABLE KEYS */;
INSERT INTO `product_type_infos` VALUES (1,116,'La nostra shopper in puro cotone è l\'accessorio ideale per chi cerca praticità senza rinunciare allo stile. Realizzata con materiali di alta qualità, questa borsa offre spazio sufficiente per le tue necessità quotidiane, mentre il suo design semplice ma dinamico si adatta perfettamente a qualsiasi occasione.','Tessuto: 100% cotone;Capacità: 10 litri;Bretelle da 67cm;Tantissimi colori','2024-03-31 00:06:11','2024-12-13 11:03:03');
INSERT INTO `product_type_infos` VALUES (2,115,'Tanga in morbido cotone bielastico e bordi extracomfort ','Comfort superiore: i bordi extracomfort di vita e gambe abbracciano delicatamente il tuo corpo, sostituendo gli elastici tradizionali e riducendo il rischio di irritazioni e sfregamenti; Qualità superiore: grazie al tessuto bielastico, il capo si adatta perfettamente al corpo e mantiene la sua forma anche dopo numerosi lavaggi, assicurando al prodotto una lunga durata; Design essenziale: il design essenziale e la qualità pregiata del nostro filato di cotone trasformano un semplice capo in un abbraccio di puro benessere','2024-12-13 10:53:02','2024-12-13 11:02:40');
INSERT INTO `product_type_infos` VALUES (3,117,'La nostra shopper in puro cotone biologico è l\'accessorio ideale per chi\ncerca praticità con un\'attenzione in più all\'ambiente. Realizzata con\nmateriali di alta qualità, questa borsa offre spazio sufficiente per le tue\nnecessità quotidiane, mentre il suo design semplice ma dinamico si\nadatta perfettamente a qualsiasi occasione.','Tessuto: 100% cotone biologico\nGrammatura: 310 g/m²\nCapacità: 13 litri\nBretelle da 70cm','2025-02-21 13:29:32','2025-02-21 14:02:06');
INSERT INTO `product_type_infos` VALUES (4,2,NULL,NULL,'2025-03-08 20:29:41','2025-03-08 20:29:41');
INSERT INTO `product_type_infos` VALUES (5,118,'Le nostre t-shirt in puro cotone sono estremamente morbide e confortevoli grazie all\'alta densità del tessuto. I capi sono già stati pre-lavati per evitare che si restringano dopo il primo lavaggio. 100% cotone pettinato. Tessuto jersey. Taglio aderente. Girocollo con nastrino di rinforzo tono su tono. Rinforzo della cucitura sulla spalla. Finitura fondo manica e fondo capo con orlo a doppia sovracucitura. Prodotto in Portogallo.','Tessuto: 100% cotone jersey\nDensità: 145 g/m² bianco e 155 g/m² colorati\nStampa DTF molto resistente ai lavaggi\nVestibilità UNISEX classic fit','2025-03-09 18:49:25','2025-03-10 18:44:53');
INSERT INTO `product_type_infos` VALUES (6,119,'Le nostre t-shirt in puro cotone sono estremamente morbide e confortevoli grazie all\'alta densità del tessuto. I capi sono già stati pre-lavati per evitare che si restringano dopo il primo lavaggio. 100% cotone pettinato. Tessuto jersey. Taglio aderente. Girocollo con nastrino di rinforzo tono su tono. Rinforzo della cucitura sulla spalla. Finitura fondo manica e fondo capo con orlo a doppia sovracucitura. Prodotto in Portogallo.','Tessuto: 100% cotone jersey\nDensità: 145 g/m² bianco e 155 g/m² colorati\nStampa DTF molto resistente ai lavaggi\nVestibilità UNISEX classic fit','2025-03-09 18:49:32','2025-03-10 18:45:40');
INSERT INTO `product_type_infos` VALUES (7,1,'&middot; Tessuto: 100% cotone jersey\n&middot; Densità: 175 g/m² bianco e 185 g/m² colorati\n&middot; Stampa molto resistente ai lavaggi\n&middot; Vestibilità normale',NULL,'2025-03-10 10:13:28','2025-05-24 16:13:26');
INSERT INTO `product_type_infos` VALUES (8,3,NULL,NULL,'2025-05-27 15:15:10','2025-05-27 15:15:10');
INSERT INTO `product_type_infos` VALUES (9,4,NULL,NULL,'2025-05-27 15:18:00','2025-05-27 15:18:00');
INSERT INTO `product_type_infos` VALUES (10,5,NULL,NULL,'2025-05-27 15:18:42','2025-05-27 15:18:42');
INSERT INTO `product_type_infos` VALUES (11,120,'Cappellino in rete con patch frontale intercambiabile e chiusura regolabile Snapback. Versatile e personalizzabile grazie alla toppa removibile in velcro, ideale per creare look unici e dinamici.','Materiale:\nPannello frontale e visiera: 100% cotone\nPannelli posteriori in mesh: 100% poliestere\nPatch Rip-Strip™ intercambiabile\n\nStruttura:\n5 pannelli\nVisiera precurvata\nRete traspirante sui pannelli posteriori\n\nVestibilità:\nTaglia unica (ONE SIZE)\nRegolabile con linguetta in plastica vintage Snapback\n\nPatch:\nPatch frontale removibile in velcro\nPersonalizzabile e sostituibile a piacere','2025-06-03 15:04:07','2025-06-03 15:04:07');
INSERT INTO `product_type_infos` VALUES (12,121,'\nToppa frontale con retro in velcro, compatibile con cappellini dotati di patch intercambiabile. Un accessorio pensato per chi desidera variare stile con facilità: basta un solo cappellino per applicare grafiche diverse a seconda dell’occasione, del mood o del messaggio da comunicare. Facile da sostituire e comoda da collezionare, è l’ideale per personalizzare il proprio look in modo dinamico e versatile.\n\n','Patch removibile con retro in velcro\nCompatibile con i nostri cappellini, dotati di area frontale in velcro.\nFacile da applicare, rimuovere e sostituire\nIdeale per variare lo stile senza acquistare più cappellini\nMisura standard\nSi consiglia il lavaggio a mano per una maggiore durata del prodotto','2025-06-23 01:15:42','2025-06-30 15:06:04');
INSERT INTO `product_type_infos` VALUES (13,6,NULL,NULL,'2026-09-02 17:50:28','2026-09-02 17:50:28');
INSERT INTO `product_type_infos` VALUES (14,8,NULL,NULL,'2026-09-02 17:50:35','2026-09-02 17:50:35');
INSERT INTO `product_type_infos` VALUES (15,10,NULL,NULL,'2026-09-02 17:50:43','2026-09-02 17:50:43');
/*!40000 ALTER TABLE `product_type_infos` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `payment_methods` WRITE;
/*!40000 ALTER TABLE `payment_methods` DISABLE KEYS */;
INSERT INTO `payment_methods` VALUES (1,'ppl','PayPal','Puoi pagare senza effettuare nessuna registrazione.',1);
INSERT INTO `payment_methods` VALUES (2,'car','Carta di Credito','Paga con una delle principali carte di credito.',0);
INSERT INTO `payment_methods` VALUES (3,'del','Pagamento al momento del ritiro','Paga al momento del ritiro in negozio presso la sede <strong>Gi&Ba in Piazza Scopoli, 9, 38033 Cavalese (TN).</strong>',0);
INSERT INTO `payment_methods` VALUES (4,'pro','Offerta promozionale','Nessun pagamento richiesto.',0);
/*!40000 ALTER TABLE `payment_methods` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sys_country` WRITE;
/*!40000 ALTER TABLE `sys_country` DISABLE KEYS */;
INSERT INTO `sys_country` VALUES (1,'Italia','sta,pre,rit',0);
INSERT INTO `sys_country` VALUES (2,'Francia','eu1',1);
INSERT INTO `sys_country` VALUES (4,'Romania','eu3',1);
INSERT INTO `sys_country` VALUES (5,'Germania','eu1',1);
INSERT INTO `sys_country` VALUES (6,'Irlanda','eu4',1);
INSERT INTO `sys_country` VALUES (7,'Paesi Bassi','eu2',1);
INSERT INTO `sys_country` VALUES (8,'Regno Unito','eu6',1);
INSERT INTO `sys_country` VALUES (9,'Ucraina','eu5',1);
INSERT INTO `sys_country` VALUES (10,'Portogallo','eu2',1);
INSERT INTO `sys_country` VALUES (11,'Spagna','eu2',1);
INSERT INTO `sys_country` VALUES (12,'Danimarca','eu4',1);
INSERT INTO `sys_country` VALUES (13,'Austria','eu1',1);
INSERT INTO `sys_country` VALUES (14,'Belgio','eu2',1);
INSERT INTO `sys_country` VALUES (15,'Bulgaria','eu3',1);
INSERT INTO `sys_country` VALUES (16,'Albania','eu5',1);
INSERT INTO `sys_country` VALUES (17,'Andorra','eu5',1);
INSERT INTO `sys_country` VALUES (18,'Bielorussia','eu5',1);
INSERT INTO `sys_country` VALUES (19,'Bosnia & Herzegovina','eu5',1);
INSERT INTO `sys_country` VALUES (20,'Cipro','eu5',1);
INSERT INTO `sys_country` VALUES (21,'Croazia','eu4',1);
INSERT INTO `sys_country` VALUES (22,'Estonia','eu4',1);
INSERT INTO `sys_country` VALUES (23,'Finlandia','eu4',1);
INSERT INTO `sys_country` VALUES (24,'Gibilterra','eu5',1);
INSERT INTO `sys_country` VALUES (25,'Islanda','eu5',1);
INSERT INTO `sys_country` VALUES (26,'Isole Canarie','eu5',1);
INSERT INTO `sys_country` VALUES (27,'Kosovo','eu5',1);
INSERT INTO `sys_country` VALUES (28,'Lettonia','eu4',1);
INSERT INTO `sys_country` VALUES (29,'Liechtenstein','eu6',1);
INSERT INTO `sys_country` VALUES (30,'Lituania','eu4',1);
INSERT INTO `sys_country` VALUES (31,'Lussemburgo','eu2',1);
INSERT INTO `sys_country` VALUES (32,'Macedonia del Nord','eu5',1);
INSERT INTO `sys_country` VALUES (33,'Malta','eu5',1);
INSERT INTO `sys_country` VALUES (34,'Monaco','eu1',1);
INSERT INTO `sys_country` VALUES (35,'Moldavia','eu5',1);
INSERT INTO `sys_country` VALUES (36,'Montenegro','eu5',1);
INSERT INTO `sys_country` VALUES (37,'Norvegia','eu5',1);
INSERT INTO `sys_country` VALUES (38,'Polonia','eu3',1);
INSERT INTO `sys_country` VALUES (39,'Repubblica Ceca','eu3',1);
INSERT INTO `sys_country` VALUES (40,'Serbia','eu5',1);
INSERT INTO `sys_country` VALUES (41,'Slovacchia','eu5',1);
INSERT INTO `sys_country` VALUES (42,'Slovenia','eu1',1);
INSERT INTO `sys_country` VALUES (43,'Svezia','eu4',1);
INSERT INTO `sys_country` VALUES (44,'Turchia','eu5',1);
INSERT INTO `sys_country` VALUES (45,'Ungheria','eu3',1);
/*!40000 ALTER TABLE `sys_country` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `provinces` WRITE;
/*!40000 ALTER TABLE `provinces` DISABLE KEYS */;
INSERT INTO `provinces` VALUES (1,'Agrigento','AG',1,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (2,'Alessandria','AL',2,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (3,'Ancona','AN',3,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (4,'Aosta','AO',4,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (5,'Ascoli-Piceno','AP',3,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (6,'L’Aquila','AQ',6,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (7,'Arezzo','AR',7,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (8,'Asti','AT',2,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (9,'Avellino','AV',9,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (10,'Bari','BA',10,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (11,'Bergamo','BG',11,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (12,'Biella','BI',2,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (13,'Belluno','BL',13,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (14,'Benevento','BN',9,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (15,'Bologna','BO',15,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (16,'Brindisi','BR',10,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (17,'Brescia','BS',11,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (18,'Barletta-Andria-Trani','BT',10,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (19,'Bolzano','BZ',19,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (20,'Cagliari','CA',20,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (21,'Campobasso','CB',21,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (22,'Caserta','CE',9,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (23,'Chieti','CH',6,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (24,'Carbonia Iglesias','CI',20,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (25,'Caltanissetta','CL',1,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (26,'Cuneo','CN',2,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (27,'Como','CO',11,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (28,'Cremona','CR',11,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (29,'Cosenza','CS',29,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (30,'Catania','CT',1,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (31,'Catanzaro','CZ',29,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (32,'Enna','EN',1,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (33,'Forli-Cesena','FC',15,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (34,'Ferrara','FE',15,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (35,'Foggia','FG',10,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (36,'Firenze','FI',7,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (37,'Fermo','FM',3,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (38,'Frosinone','FR',38,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (39,'Genova','GE',39,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (40,'Gorizia','GO',40,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (41,'Grosseto','GR',7,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (42,'Imperia','IM',39,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (43,'Isernia','IS',21,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (44,'Crotone','KR',29,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (45,'Lecco','LC',11,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (46,'Lecce','LE',10,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (47,'Livorno','LI',7,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (48,'Lodi','LO',11,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (49,'Latina','LT',38,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (50,'Lucca','LU',7,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (51,'Monza-Brianza','MB',11,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (52,'Macerata','MC',3,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (53,'Messina','ME',1,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (54,'Milano','MI',11,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (55,'Mantova','MN',11,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (56,'Modena','MO',15,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (57,'Massa-Carrara','MS',7,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (58,'Matera','MT',58,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (59,'Napoli','NA',9,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (60,'Novara','NO',2,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (61,'Nuoro','NU',20,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (62,'Ogliastra','OG',20,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (63,'Oristano','OR',20,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (64,'Olbia Tempio','OT',20,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (65,'Palermo','PA',1,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (66,'Piacenza','PC',15,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (67,'Padova','PD',13,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (68,'Pescara','PE',6,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (69,'Perugia','PG',69,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (70,'Pisa','PI',7,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (71,'Pordenone','PN',40,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (72,'Prato','PO',7,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (73,'Parma','PR',15,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (74,'Pistoia','PT',7,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (75,'Pesaro-Urbino','PU',3,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (76,'Pavia','PV',11,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (77,'Potenza','PZ',58,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (78,'Ravenna','RA',15,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (79,'Reggio-Calabria','RC',29,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (80,'Reggio-Emilia','RE',15,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (81,'Ragusa','RG',1,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (82,'Rieti','RI',38,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (83,'Rimini','RN',15,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (84,'Rovigo','RO',13,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (85,'Roma','RM',38,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (86,'Salerno','SA',9,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (87,'Siena','SI',7,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (88,'Sondrio','SO',11,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (89,'La-Spezia','SP',39,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (90,'Siracusa','SR',1,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (91,'Sassari','SS',20,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (92,'Savona','SV',39,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (93,'Taranto','TA',10,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (94,'Teramo','TE',6,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (95,'Trento','TN',19,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (96,'Torino','TO',2,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (97,'Trapani','TP',1,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (98,'Terni','TR',69,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (99,'Trieste','TS',40,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (100,'Treviso','TV',13,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (101,'Udine','UD',40,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (102,'Varese','VA',11,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (103,'Verbania','VB',2,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (104,'Vercelli','VC',2,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (105,'Venezia','VE',13,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (106,'Vicenza','VI',13,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (107,'Verona','VR',13,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (108,'Medio Campidano','VS',20,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (109,'Viterbo','VT',38,'2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `provinces` VALUES (110,'Vibo-Valentia','VV',29,'2024-10-02 04:45:38','2024-10-02 04:45:38');
/*!40000 ALTER TABLE `provinces` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `regions` WRITE;
/*!40000 ALTER TABLE `regions` DISABLE KEYS */;
INSERT INTO `regions` VALUES (1,'Sicilia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (2,'Piemonte','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (3,'Marche','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (4,'Valle d’Aosta','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (5,'Marche','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (6,'Abruzzo','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (7,'Toscana','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (8,'Piemonte','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (9,'Campania','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (10,'Puglia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (11,'Lombardia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (12,'Piemonte','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (13,'Veneto','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (14,'Campania','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (15,'Emilia Romagna','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (16,'Puglia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (17,'Lombardia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (18,'Puglia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (19,'Trentino Alto Adige','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (20,'Sardegna','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (21,'Molise','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (22,'Campania','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (23,'Abruzzo','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (24,'Sardegna','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (25,'Sicilia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (26,'Piemonte','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (27,'Lombardia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (28,'Lombardia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (29,'Calabria','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (30,'Sicilia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (31,'Calabria','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (32,'Sicilia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (33,'Emilia Romagna','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (34,'Emilia Romagna','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (35,'Puglia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (36,'Toscana','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (37,'Marche','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (38,'Lazio','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (39,'Liguria','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (40,'Friuli Venezia Giulia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (41,'Toscana','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (42,'Liguria','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (43,'Molise','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (44,'Calabria','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (45,'Lombardia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (46,'Puglia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (47,'Toscana','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (48,'Lombardia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (49,'Lazio','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (50,'Toscana','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (51,'Lombardia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (52,'Marche','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (53,'Sicilia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (54,'Lombardia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (55,'Lombardia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (56,'Emilia Romagna','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (57,'Toscana','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (58,'Basilicata','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (59,'Campania','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (60,'Piemonte','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (61,'Sardegna','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (62,'Sardegna','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (63,'Sardegna','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (64,'Sardegna','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (65,'Sicilia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (66,'Emilia Romagna','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (67,'Veneto','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (68,'Abruzzo','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (69,'Umbria','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (70,'Toscana','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (71,'Friuli Venezia Giulia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (72,'Toscana','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (73,'Emilia Romagna','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (74,'Toscana','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (75,'Marche','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (76,'Lombardia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (77,'Basilicata','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (78,'Emilia Romagna','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (79,'Calabria','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (80,'Emilia Romagna','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (81,'Sicilia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (82,'Lazio','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (83,'Emilia Romagna','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (84,'Veneto','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (85,'Lazio','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (86,'Campania','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (87,'Toscana','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (88,'Lombardia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (89,'Liguria','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (90,'Sicilia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (91,'Sardegna','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (92,'Liguria','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (93,'Puglia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (94,'Abruzzo','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (95,'Trentino Alto Adige','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (96,'Piemonte','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (97,'Sicilia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (98,'Umbria','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (99,'Friuli Venezia Giulia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (100,'Veneto','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (101,'Friuli Venezia Giulia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (102,'Lombardia','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (103,'Piemonte','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (104,'Piemonte','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (105,'Veneto','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (106,'Veneto','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (107,'Veneto','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (108,'Sardegna','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (109,'Lazio','2024-10-02 04:45:38','2024-10-02 04:45:38');
INSERT INTO `regions` VALUES (110,'Calabria','2024-10-02 04:45:38','2024-10-02 04:45:38');
/*!40000 ALTER TABLE `regions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sys_shipping` WRITE;
/*!40000 ALTER TABLE `sys_shipping` DISABLE KEYS */;
INSERT INTO `sys_shipping` VALUES (1,'fre','Gratuita','Spedizione gratuita','tracciabile, assicurata e rimborsabile',0,0,0,1,2,0);
INSERT INTO `sys_shipping` VALUES (2,'pre','Corriere','Spedizione con corriere GLS','tracciabile, assicurata e rimborsabile',5.9,2.3,8.2,1,2,1);
INSERT INTO `sys_shipping` VALUES (11,'rit','Sede','Ritiro in sede','l\'ordine sarà ritirato in sede presso Gi&Ba in Piazza Scopoli, 9, 38033 Cavalese (TN)',0,0,0,0,1,0);
/*!40000 ALTER TABLE `sys_shipping` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

SET FOREIGN_KEY_CHECKS=1;
