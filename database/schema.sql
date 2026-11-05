-- OM Trip and Travels Database Schema
-- Charset: utf8mb4_unicode_ci
-- Engine: InnoDB

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- For local CLI creation (leave commented for Hostinger phpMyAdmin where database is already selected):
-- CREATE DATABASE IF NOT EXISTS `omtrip_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE `omtrip_db`;

-- admins table
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('super_admin','staff') NOT NULL DEFAULT 'staff',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `last_login` DATETIME NULL,
  `remember_token` VARCHAR(100) NULL,
  `password_reset_token` VARCHAR(100) NULL,
  `password_reset_expires` DATETIME NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- settings table
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(100) UNIQUE NOT NULL,
  `value` TEXT NULL,
  `type` ENUM('text','textarea','image','boolean','json') NOT NULL DEFAULT 'text',
  `group` VARCHAR(50) NOT NULL DEFAULT 'general',
  `label` VARCHAR(150) NOT NULL,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- vehicle_categories table
CREATE TABLE IF NOT EXISTS `vehicle_categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) UNIQUE NOT NULL,
  `description` TEXT NULL,
  `icon` VARCHAR(100) NULL,
  `image` VARCHAR(255) NULL,
  `sort_order` SMALLINT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- vehicles table
CREATE TABLE IF NOT EXISTS `vehicles` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(180) UNIQUE NOT NULL,
  `short_description` VARCHAR(500) NULL,
  `description` LONGTEXT NULL,
  `seats` TINYINT UNSIGNED NOT NULL DEFAULT 4,
  `luggage_capacity` VARCHAR(50) NULL,
  `has_ac` TINYINT(1) NOT NULL DEFAULT 1,
  `features` JSON NULL COMMENT 'Array of feature strings',
  `ideal_for` JSON NULL COMMENT 'Array of use-case strings',
  `starting_price` DECIMAL(10,2) NULL COMMENT 'NULL means On Request',
  `price_note` VARCHAR(255) NULL,
  `primary_image` VARCHAR(255) NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` SMALLINT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `meta_title` VARCHAR(255) NULL,
  `meta_description` VARCHAR(500) NULL,
  `og_image` VARCHAR(255) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_vehicles_category` FOREIGN KEY (`category_id`) REFERENCES `vehicle_categories`(`id`) ON DELETE RESTRICT,
  INDEX `idx_vehicles_category` (`category_id`),
  INDEX `idx_vehicles_featured` (`is_featured`),
  INDEX `idx_vehicles_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- vehicle_images table
CREATE TABLE IF NOT EXISTS `vehicle_images` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `vehicle_id` INT UNSIGNED NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `alt_text` VARCHAR(255) NULL,
  `sort_order` SMALLINT NOT NULL DEFAULT 0,
  `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_vehicle_images_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles`(`id`) ON DELETE CASCADE,
  INDEX `idx_vehicle_images_vehicle` (`vehicle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- tour_packages table
CREATE TABLE IF NOT EXISTS `tour_packages` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(220) UNIQUE NOT NULL,
  `short_description` VARCHAR(500) NULL,
  `description` LONGTEXT NULL,
  `itinerary` LONGTEXT NULL,
  `inclusions` TEXT NULL,
  `exclusions` TEXT NULL,
  `duration_days` TINYINT UNSIGNED NULL,
  `duration_nights` TINYINT UNSIGNED NULL,
  `route` VARCHAR(500) NULL,
  `origin` VARCHAR(100) NULL DEFAULT 'Indore',
  `destination` VARCHAR(100) NULL,
  `starting_price` DECIMAL(10,2) NULL,
  `price_note` VARCHAR(255) NULL,
  `primary_image` VARCHAR(255) NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` SMALLINT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `meta_title` VARCHAR(255) NULL,
  `meta_description` VARCHAR(500) NULL,
  `og_image` VARCHAR(255) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_packages_featured` (`is_featured`),
  INDEX `idx_packages_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- package_vehicles pivot table
CREATE TABLE IF NOT EXISTS `package_vehicles` (
  `package_id` INT UNSIGNED NOT NULL,
  `vehicle_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`package_id`, `vehicle_id`),
  CONSTRAINT `fk_pv_package` FOREIGN KEY (`package_id`) REFERENCES `tour_packages`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pv_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- blog_categories table
CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) UNIQUE NOT NULL,
  `description` VARCHAR(500) NULL,
  `sort_order` SMALLINT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- blog_posts table
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT UNSIGNED NULL,
  `author_id` INT UNSIGNED NULL,
  `title` VARCHAR(300) NOT NULL,
  `slug` VARCHAR(320) UNIQUE NOT NULL,
  `excerpt` VARCHAR(500) NULL,
  `content` LONGTEXT NOT NULL,
  `featured_image` VARCHAR(255) NULL,
  `og_image` VARCHAR(255) NULL,
  `status` ENUM('draft','scheduled','published') NOT NULL DEFAULT 'draft',
  `published_at` DATETIME NULL,
  `meta_title` VARCHAR(255) NULL,
  `meta_description` VARCHAR(500) NULL,
  `views` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_posts_category` FOREIGN KEY (`category_id`) REFERENCES `blog_categories`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_posts_author` FOREIGN KEY (`author_id`) REFERENCES `admins`(`id`) ON DELETE SET NULL,
  INDEX `idx_posts_status` (`status`),
  INDEX `idx_posts_published` (`published_at`),
  INDEX `idx_posts_category` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- blog_tags table
CREATE TABLE IF NOT EXISTS `blog_tags` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(80) NOT NULL,
  `slug` VARCHAR(100) UNIQUE NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- blog_post_tags pivot
CREATE TABLE IF NOT EXISTS `blog_post_tags` (
  `post_id` INT UNSIGNED NOT NULL,
  `tag_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`post_id`, `tag_id`),
  CONSTRAINT `fk_pt_post` FOREIGN KEY (`post_id`) REFERENCES `blog_posts`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pt_tag` FOREIGN KEY (`tag_id`) REFERENCES `blog_tags`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- testimonials table
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `designation` VARCHAR(150) NULL,
  `location` VARCHAR(100) NULL,
  `photo` VARCHAR(255) NULL,
  `rating` TINYINT UNSIGNED NOT NULL DEFAULT 5,
  `review` TEXT NOT NULL,
  `vehicle_booked` VARCHAR(150) NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` SMALLINT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- gallery_items table
CREATE TABLE IF NOT EXISTS `gallery_items` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(200) NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `alt_text` VARCHAR(255) NULL,
  `category` VARCHAR(80) NULL,
  `sort_order` SMALLINT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- faqs table
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `question` VARCHAR(500) NOT NULL,
  `answer` TEXT NOT NULL,
  `category` VARCHAR(80) NULL DEFAULT 'General',
  `sort_order` SMALLINT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- banners table
CREATE TABLE IF NOT EXISTS `banners` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(200) NULL,
  `subtitle` VARCHAR(500) NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `mobile_image_path` VARCHAR(255) NULL,
  `cta_text` VARCHAR(80) NULL,
  `cta_url` VARCHAR(500) NULL,
  `cta_type` ENUM('call','whatsapp','link','none') NOT NULL DEFAULT 'link',
  `sort_order` SMALLINT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- enquiries table
CREATE TABLE IF NOT EXISTS `enquiries` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `type` ENUM('contact','booking') NOT NULL DEFAULT 'contact',
  `name` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `email` VARCHAR(150) NULL,
  `pickup_location` VARCHAR(300) NULL,
  `drop_location` VARCHAR(300) NULL,
  `travel_date` DATE NULL,
  `return_date` DATE NULL,
  `passengers` TINYINT UNSIGNED NULL,
  `vehicle_id` INT UNSIGNED NULL,
  `package_id` INT UNSIGNED NULL,
  `message` TEXT NULL,
  `status` ENUM('new','contacted','followup','confirmed','closed','spam') NOT NULL DEFAULT 'new',
  `source_page` VARCHAR(500) NULL,
  `ip` VARCHAR(45) NULL,
  `user_agent` VARCHAR(500) NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_enquiries_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_enquiries_package` FOREIGN KEY (`package_id`) REFERENCES `tour_packages`(`id`) ON DELETE SET NULL,
  INDEX `idx_enquiries_status` (`status`),
  INDEX `idx_enquiries_type` (`type`),
  INDEX `idx_enquiries_created` (`created_at`),
  INDEX `idx_enquiries_read` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- enquiry_notes table
CREATE TABLE IF NOT EXISTS `enquiry_notes` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `enquiry_id` INT UNSIGNED NOT NULL,
  `admin_id` INT UNSIGNED NOT NULL,
  `note` TEXT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_notes_enquiry` FOREIGN KEY (`enquiry_id`) REFERENCES `enquiries`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_notes_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins`(`id`) ON DELETE CASCADE,
  INDEX `idx_notes_enquiry` (`enquiry_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- click_logs table
CREATE TABLE IF NOT EXISTS `click_logs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `type` ENUM('call','whatsapp') NOT NULL,
  `vehicle_id` INT UNSIGNED NULL,
  `package_id` INT UNSIGNED NULL,
  `page` VARCHAR(500) NULL,
  `ip` VARCHAR(45) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_clicks_type` (`type`),
  INDEX `idx_clicks_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- activity_logs table
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `admin_id` INT UNSIGNED NULL,
  `action` VARCHAR(200) NOT NULL,
  `entity_type` VARCHAR(80) NULL,
  `entity_id` INT UNSIGNED NULL,
  `description` TEXT NULL,
  `ip` VARCHAR(45) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_activity_admin` (`admin_id`),
  INDEX `idx_activity_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- login_attempts table
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `ip` VARCHAR(45) NOT NULL,
  `email` VARCHAR(150) NULL,
  `attempts` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `locked_until` DATETIME NULL,
  `last_attempt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_login_ip` (`ip`),
  INDEX `idx_login_locked` (`locked_until`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- pages_seo table
CREATE TABLE IF NOT EXISTS `pages_seo` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `page_key` VARCHAR(100) UNIQUE NOT NULL,
  `title` VARCHAR(255) NULL,
  `meta_description` VARCHAR(500) NULL,
  `canonical` VARCHAR(500) NULL,
  `og_title` VARCHAR(255) NULL,
  `og_description` VARCHAR(500) NULL,
  `og_image` VARCHAR(255) NULL,
  `schema_json` LONGTEXT NULL,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- package_images table
CREATE TABLE IF NOT EXISTS `package_images` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `package_id` INT UNSIGNED NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `alt_text` VARCHAR(255) NULL,
  `sort_order` SMALLINT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_pkg_images_package` FOREIGN KEY (`package_id`) REFERENCES `tour_packages`(`id`) ON DELETE CASCADE,
  INDEX `idx_pkg_images_package` (`package_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
