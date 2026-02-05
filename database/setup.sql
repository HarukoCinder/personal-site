-- ================================================
-- VT.Domains 個人サイト Database Setup
-- MySQL/MariaDB Schema
-- ================================================

-- Create database (run this in phpMyAdmin or via cPanel)
-- CREATE DATABASE IF NOT EXISTS your_database_name;
-- USE your_database_name;

-- ================================================
-- カウンター (Visitor Counter) Table
-- ================================================
CREATE TABLE IF NOT EXISTS visitor_counter (
    id INT PRIMARY KEY AUTO_INCREMENT,
    total_count INT UNSIGNED NOT NULL DEFAULT 0,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Initialize counter with 0
INSERT INTO visitor_counter (id, total_count) VALUES (1, 0)
ON DUPLICATE KEY UPDATE id = id;

-- ================================================
-- キリ番 (Kiriban Milestones) Table
-- ================================================
CREATE TABLE IF NOT EXISTS kiriban (
    id INT PRIMARY KEY AUTO_INCREMENT,
    milestone_number INT UNSIGNED NOT NULL,
    visitor_name VARCHAR(100) DEFAULT NULL,
    visitor_message TEXT DEFAULT NULL,
    achieved_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    ip_hash VARCHAR(64) DEFAULT NULL,
    UNIQUE KEY unique_milestone (milestone_number)
);

-- Pre-define milestones you want to celebrate
INSERT INTO kiriban (milestone_number) VALUES 
    (100), (500), (777), (1000), (1111), (2000), (2222), 
    (3000), (3333), (5000), (7777), (10000)
ON DUPLICATE KEY UPDATE milestone_number = milestone_number;

-- ================================================
-- コメント (Guestbook/Comments) Table
-- ================================================
CREATE TABLE IF NOT EXISTS guestbook (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    website VARCHAR(255) DEFAULT NULL,
    message TEXT NOT NULL,
    icon_id TINYINT UNSIGNED DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    ip_hash VARCHAR(64) NOT NULL,
    is_approved TINYINT(1) DEFAULT 1,
    is_reply TINYINT(1) DEFAULT 0,
    reply_to INT DEFAULT NULL,
    INDEX idx_created (created_at),
    INDEX idx_approved (is_approved)
);

-- ================================================
-- ギャラリー (Photo Gallery) Table
-- ================================================
CREATE TABLE IF NOT EXISTS gallery (
    id INT PRIMARY KEY AUTO_INCREMENT,
    filename VARCHAR(255) NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    title VARCHAR(200) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    category VARCHAR(100) DEFAULT 'uncategorized',
    taken_date DATE DEFAULT NULL,
    camera_info VARCHAR(255) DEFAULT NULL,
    file_size INT UNSIGNED DEFAULT 0,
    width INT UNSIGNED DEFAULT 0,
    height INT UNSIGNED DEFAULT 0,
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    view_count INT UNSIGNED DEFAULT 0,
    is_visible TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    INDEX idx_category (category),
    INDEX idx_visible (is_visible),
    INDEX idx_date (taken_date)
);

-- ================================================
-- Gallery Categories
-- ================================================
CREATE TABLE IF NOT EXISTS gallery_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    slug VARCHAR(100) NOT NULL UNIQUE,
    name_ja VARCHAR(100) NOT NULL,
    name_en VARCHAR(100) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    sort_order INT DEFAULT 0
);

INSERT INTO gallery_categories (slug, name_ja, name_en, sort_order) VALUES
    ('landscape', '風景', 'Landscape', 1),
    ('portrait', 'ポートレート', 'Portrait', 2),
    ('street', 'ストリート', 'Street', 3),
    ('nature', '自然', 'Nature', 4),
    ('travel', '旅行', 'Travel', 5),
    ('daily', '日常', 'Daily Life', 6)
ON DUPLICATE KEY UPDATE slug = slug;

-- ================================================
-- Visitor Log (for analytics, optional)
-- ================================================
CREATE TABLE IF NOT EXISTS visitor_log (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ip_hash VARCHAR(64) NOT NULL,
    page_visited VARCHAR(255) DEFAULT '/',
    user_agent VARCHAR(500) DEFAULT NULL,
    referer VARCHAR(500) DEFAULT NULL,
    visited_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_date (visited_at),
    INDEX idx_ip (ip_hash)
);

-- ================================================
-- Site Settings (for customization)
-- ================================================
CREATE TABLE IF NOT EXISTS site_settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO site_settings (setting_key, setting_value) VALUES
    ('site_title', 'Ryu\'s Personal Site'),
    ('site_subtitle', '写真とプログラミングの記録'),
    ('owner_name', 'Ryu'),
    ('established_date', '2026'),
    ('current_status', '通常営業中'),
    ('last_update', '2026.01.22'),
    ('kiriban_enabled', '1'),
    ('guestbook_enabled', '1')
ON DUPLICATE KEY UPDATE setting_key = setting_key;
