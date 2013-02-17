DROP TABLE IF EXISTS `sessions`;

-- COMMAND BREAK --

CREATE TABLE `sessions` (
`session_id` VARCHAR(40) DEFAULT '0' NOT NULL,
`ip_address` VARCHAR(16) DEFAULT '0' NOT NULL,
`user_agent` VARCHAR(200) NOT NULL,
`last_activity` INT(10) UNSIGNED DEFAULT 0 NOT NULL,
`user_data` TEXT NOT NULL,
PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Sessions';

-- COMMAND BREAK --

DROP TABLE IF EXISTS `accounts`;

-- COMMAND BREAK --

CREATE TABLE `accounts` (
`account_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`account_confirmed` TINYINT(1) NOT NULL DEFAULT '0',
`account_personalised` TINYINT(1) NOT NULL DEFAULT '0',
`account_active` TINYINT(1) NOT NULL DEFAULT '0',
`account_name` VARCHAR(64) NOT NULL DEFAULT '',
`account_slug` VARCHAR(100) NOT NULL DEFAULT '',
`account_email` VARCHAR(100) NOT NULL DEFAULT '',
`account_phone` VARCHAR(100) NULL,
`account_website` VARCHAR(100) NULL,
`account_description` TEXT NULL,
`account_confirmation_code` VARCHAR(100) NOT NULL DEFAULT '',
`account_updated_at` TIMESTAMP NOT NULL,
`account_created_at` DATETIME NOT NULL,
`account_activated_at` DATETIME NOT NULL,
`account_deleted_at` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Accounts';

-- COMMAND BREAK --

DROP TABLE IF EXISTS `users`;

-- COMMAND BREAK --

CREATE TABLE `users` (
`user_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`user_account_id` INT(11) NOT NULL DEFAULT '0',
`user_username` VARCHAR(64) NULL,
`user_email` VARCHAR(100) NULL,
`user_password` VARCHAR(100) NOT NULL DEFAULT '',
`user_firstname` VARCHAR(64) NULL,
`user_lastname` VARCHAR(64) NULL,
`user_is_admin` TINYINT(1) NOT NULL DEFAULT '0',
`user_updated_at` TIMESTAMP NOT NULL,
`user_created_at` DATETIME NOT NULL,
`user_deleted_at` DATETIME NOT NULL,
`user_password_reset` VARCHAR(100) NOT NULL DEFAULT '',
`user_password_reset_expires` DATETIME NOT NULL,
UNIQUE KEY `account_username` (`user_account_id`, `user_username`),
UNIQUE KEY `account_email` (`user_account_id`, `user_email`),
INDEX (`user_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Users';

-- COMMAND BREAK --

DROP TABLE IF EXISTS `logins`;

-- COMMAND BREAK --

CREATE TABLE `logins` (
`login_user_id` INT(11) NOT NULL DEFAULT '0',
`login_at` TIMESTAMP NOT NULL,
INDEX (`login_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Logins';

-- COMMAND BREAK --

DROP TABLE IF EXISTS `settings`;

-- COMMAND BREAK --

CREATE TABLE `settings` (
`setting_account_id` INT(11) NOT NULL DEFAULT '0',
`setting_key` VARCHAR(64) NOT NULL DEFAULT '',
`setting_value` TEXT NULL,
INDEX (`setting_account_id`),
INDEX (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Settings';

-- COMMAND BREAK --

DROP TABLE IF EXISTS `seasons`;

-- COMMAND BREAK --

CREATE TABLE `seasons` (
`season_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`season_account_id` INT(11) NOT NULL DEFAULT '0',
`season_sort_order` INT(11) NOT NULL DEFAULT '0',
`season_title` VARCHAR(100) NOT NULL DEFAULT '',
`season_start_at` DATETIME NOT NULL,
`season_end_at` DATETIME NOT NULL,
INDEX (`season_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Seasons';

-- COMMAND BREAK --

DROP TABLE IF EXISTS `resources`;

-- COMMAND BREAK --

CREATE TABLE `resources` (
`resource_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`resource_account_id` INT(11) NOT NULL DEFAULT '0',
`resource_title` VARCHAR(100) NOT NULL DEFAULT '',
`resource_reference` VARCHAR(64) NOT NULL DEFAULT '',
`resource_default_release` INT(6) NOT NULL DEFAULT '1',
`resource_booking_footprint` INT(6) NOT NULL DEFAULT '1',
`resource_priced_per` VARCHAR(10) NOT NULL DEFAULT 'bed', 
`resource_active` TINYINT(1) NOT NULL DEFAULT '1',
`resource_updated_at` TIMESTAMP NOT NULL,
`resource_created_at` DATETIME NOT NULL,
`resource_deleted_at` DATETIME NOT NULL,
INDEX (`resource_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Resources';

-- COMMAND BREAK --

DROP TABLE IF EXISTS `prices`;

-- COMMAND BREAK --

CREATE TABLE `prices` (
`price_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`price_resource_id` INT(11) NOT NULL DEFAULT '0',
`price_season_id` INT(11) NOT NULL DEFAULT '0',
`price_day_id` INT(1) NOT NULL DEFAULT '0',
`price_price` DOUBLE NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Prices';

-- COMMAND BREAK --

DROP TABLE IF EXISTS `releases`;

-- COMMAND BREAK --

CREATE TABLE `releases` (
`release_resource_id` INT(11) NOT NULL DEFAULT '0',
`release_date` DATETIME NOT NULL,
`release_amount` INT(6) NULL,
`release_price` DOUBLE NULL,
INDEX (`release_resource_id`),
INDEX (`release_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Releases';

-- COMMAND BREAK --

DROP TABLE IF EXISTS `bookings`;

-- COMMAND BREAK --

CREATE TABLE `bookings` (
`booking_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`booking_account_id` INT(11) NOT NULL DEFAULT '0',
`booking_user_id` INT(11) NOT NULL DEFAULT '0',
`booking_customer_id` INT(11) NOT NULL DEFAULT '0',
`booking_reference` VARCHAR(100) NOT NULL DEFAULT '',
`booking_guests` INT(6) NOT NULL DEFAULT '1',
`booking_price` FLOAT NOT NULL DEFAULT '0',
`booking_deposit` FLOAT NOT NULL DEFAULT '0',
`booking_acknowledged` TINYINT(1) NOT NULL DEFAULT '0',
`booking_completed` TINYINT(1) NOT NULL DEFAULT '0',
`booking_failed` TINYINT(1) NOT NULL DEFAULT '0',
`booking_aborted` TINYINT(1) NOT NULL DEFAULT '0',
`booking_session_id` VARCHAR(40) DEFAULT '0' NOT NULL,
`booking_session_dump` TEXT NULL,
`booking_billing_data` TEXT NULL,
`booking_gateway_data` TEXT NULL,
`booking_ip_address` VARCHAR(16) DEFAULT '0' NOT NULL,
`booking_user_agent` VARCHAR(200) NOT NULL,
`booking_updated_at` TIMESTAMP NOT NULL,
`booking_created_at` DATETIME NOT NULL,
`booking_deleted_at` DATETIME NOT NULL,
INDEX (`booking_account_id`),
INDEX (`booking_customer_id`),
INDEX `account_reference` (`booking_account_id`, `booking_reference`),
INDEX `account_acknowledged` (`booking_account_id`, `booking_acknowledged`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Bookings';

-- COMMAND BREAK --

DROP TABLE IF EXISTS `reservations`;

-- COMMAND BREAK --

CREATE TABLE `reservations` (
`reservation_booking_id` INT(11) NOT NULL DEFAULT '0',
`reservation_resource_id` INT(11) NOT NULL DEFAULT '0',
`reservation_footprint` INT(6) NOT NULL DEFAULT '1',
`reservation_start_at` DATETIME NOT NULL,
`reservation_duration` INT(6) NOT NULL DEFAULT '1',
`reservation_checked_in` TINYINT(1) NOT NULL DEFAULT '0',
INDEX (`reservation_booking_id`),
INDEX (`reservation_resource_id`),
INDEX (`reservation_start_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Reservations';

-- COMMAND BREAK --

DROP TABLE IF EXISTS `customers`;

-- COMMAND BREAK --

CREATE TABLE `customers` (
`customer_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`customer_account_id` INT(11) NOT NULL DEFAULT '0',
`customer_firstname` VARCHAR(64) NULL,
`customer_lastname` VARCHAR(64) NULL,
`customer_email` VARCHAR(100) NULL,
`customer_phone` VARCHAR(64) NULL,
`customer_accepts_marketing` TINYINT(1) NOT NULL DEFAULT '0',
`customer_updated_at` TIMESTAMP NOT NULL,
`customer_created_at` DATETIME NOT NULL,
`customer_deleted_at` DATETIME NOT NULL,
INDEX (`customer_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Customers';
