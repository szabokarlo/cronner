DROP DATABASE IF EXISTS imported;
CREATE DATABASE imported;
USE imported;

CREATE TABLE `partner` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `country_code` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_partner_name_country_code` (`name`,`country_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `product` (
    `id` int NOT NULL AUTO_INCREMENT,
    `partner_id` int NOT NULL,
    `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `category` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `net_price` int unsigned NOT NULL,
    `price` int unsigned NOT NULL,
    `delivery_time` int unsigned NOT NULL,
    `delivery_cost` int unsigned NOT NULL,
    `description` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_product_partner_id_idx` (`partner_id`),
    CONSTRAINT `fk_product_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partner` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
