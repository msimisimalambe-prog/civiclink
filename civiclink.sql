CREATE DATABASE IF NOT EXISTS civiclink
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE civiclink;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE system_settings (
    setting_key VARCHAR(80) PRIMARY KEY,
    setting_value VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

INSERT INTO system_settings (setting_key, setting_value) VALUES
    ('identity_mode', 'demo'),
    ('account_prefix', 'CLB');

CREATE TABLE bank_accounts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    account_number VARCHAR(40) NOT NULL UNIQUE,
    account_type ENUM('Savings', 'Current') NOT NULL,
    status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_bank_user FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE identity_checks (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    national_id VARCHAR(40) NOT NULL,
    status VARCHAR(40) NOT NULL,
    checked_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_identity_user FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE applications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    reference_no VARCHAR(40) NOT NULL UNIQUE,
    service_type VARCHAR(80) NOT NULL,
    details_json JSON NOT NULL,
    status VARCHAR(60) NOT NULL DEFAULT 'Submitted',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_application_user FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,
    INDEX idx_application_reference (reference_no),
    INDEX idx_application_status (status)
) ENGINE=InnoDB;

CREATE TABLE application_history (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    application_id INT UNSIGNED NOT NULL,
    old_status VARCHAR(60) NULL,
    new_status VARCHAR(60) NOT NULL,
    remark VARCHAR(500) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_history_application FOREIGN KEY (application_id)
        REFERENCES applications(id) ON DELETE CASCADE
) ENGINE=InnoDB;
