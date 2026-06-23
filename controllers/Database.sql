-- 1. Base Table Declarations

CREATE TABLE `store` (
    `store_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `store_name` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50) NOT NULL,
    `address` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE `admins` (
    `admin_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `fullname` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE `products` (
    `product_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_name` VARCHAR(255) NOT NULL,
    `price` DECIMAL(10, 2) NOT NULL,
    `stock` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE `transactions` (
    `transaction_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `store_id` INT UNSIGNED NOT NULL,
    `admin_id` INT UNSIGNED NOT NULL,
    `transaction_date` DATETIME NOT NULL DEFAULT NOW(),
    `payment_type` ENUM('Cash', 'Card') NOT NULL,
    `sub_total` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE `transaction_details` (
    `detail_id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `transaction_id` INT UNSIGNED NOT NULL,
    `product_id` INT UNSIGNED NOT NULL,
    `quantity` INT NOT NULL,
    `base_price` DECIMAL(10, 2) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL
);

-- 2. Constraint Applications via ALTER TABLE

-- Table: transactions
ALTER TABLE `transactions`
    ADD CONSTRAINT `fk_transactions_store` 
    FOREIGN KEY (`store_id`) REFERENCES `store` (`store_id`) 
    ON DELETE RESTRICT ON UPDATE CASCADE,
    
    ADD CONSTRAINT `fk_transactions_admin` 
    FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`) 
    ON DELETE RESTRICT ON UPDATE CASCADE;

-- Table: transaction_details
ALTER TABLE `transaction_details`
    ADD CONSTRAINT `fk_details_transaction` 
    FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`) 
    ON DELETE CASCADE ON UPDATE CASCADE,
    
    ADD CONSTRAINT `fk_details_product` 
    FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) 
    ON DELETE RESTRICT ON UPDATE CASCADE;