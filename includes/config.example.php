<?php
/**
 * ================================================
 * サイト設定 / Site Configuration
 * ================================================
 * VT.Domains MySQL Connection
 * 
 * 【設定方法】
 * 1. cPanel → MySQL Databases でデータベースを作成
 * 2. 同じくユーザーを作成してデータベースに権限を付与
 * 3. 下記の情報を入力
 */

// ========== DATABASE SETTINGS ==========
// Get these from cPanel → MySQL Databases
define('DB_HOST', 'localhost');
define('DB_NAME', 'grayvtdo_site');  // Example: jsmith_mysite
define('DB_USER', 'grayvtdo_admin');  // Example: jsmith_admin
define('DB_PASS', 'YOUR_PASSWORD_HERE');
define('DB_CHARSET', 'utf8mb4');

// ========== SITE SETTINGS ==========
define('SITE_URL', 'https://gray.vt.domains');  // No trailing slash
define('SITE_TITLE', 'Ryu\'s Room');
define('SITE_SUBTITLE', '写真とコードの記録');
define('OWNER_NAME', 'Ryu');
define('ESTABLISHED_YEAR', '2026');

// ========== PATH SETTINGS ==========
define('ROOT_PATH', dirname(__FILE__) . '/..');
define('UPLOAD_PATH', ROOT_PATH . '/uploads');
define('GALLERY_PATH', ROOT_PATH . '/uploads/gallery');
define('THUMB_PATH', ROOT_PATH . '/uploads/thumbnails');

// ========== GALLERY SETTINGS ==========
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024);  // 10MB max
define('THUMB_WIDTH', 300);
define('THUMB_HEIGHT', 300);
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// ========== SECURITY SETTINGS ==========
define('ADMIN_PASSWORD_HASH', '');  // Set with password_hash('yourpassword', PASSWORD_DEFAULT)
define('SPAM_COOLDOWN', 60);  // Seconds between guestbook posts from same IP

// ========== COUNTER SETTINGS ==========
define('COUNT_UNIQUE_ONLY', true);  // Count only unique visitors per day
define('KIRIBAN_NUMBERS', [100, 500, 777, 1000, 1111, 2000, 2222, 3333, 5000, 7777, 10000]);

// ========== DATABASE CONNECTION ==========
function getDB() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Don't expose database errors in production
            error_log('Database connection failed: ' . $e->getMessage());
            die('データベース接続エラー / Database connection error');
        }
    }
    
    return $pdo;
}

// ========== HELPER FUNCTIONS ==========

/**
 * Hash IP address for privacy
 */
function hashIP($ip) {
    return hash('sha256', $ip . 'your_secret_salt_change_this');
}

/**
 * Sanitize output for HTML
 */
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * Format date Japanese style
 */
function formatDateJa($datetime) {
    $d = new DateTime($datetime);
    return $d->format('Y年n月j日');
}

/**
 * Format date short
 */
function formatDateShort($datetime) {
    $d = new DateTime($datetime);
    return $d->format('Y.m.d');
}

/**
 * Get visitor count and increment
 */
function getAndIncrementCounter() {
    $db = getDB();
    $ip_hash = hashIP($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
    $today = date('Y-m-d');
    
    // Check if this IP already visited today (if unique counting enabled)
    if (COUNT_UNIQUE_ONLY) {
        $stmt = $db->prepare("SELECT id FROM visitor_log WHERE ip_hash = ? AND DATE(visited_at) = ?");
        $stmt->execute([$ip_hash, $today]);
        $already_counted = $stmt->fetch();
    }
    
    // Log visit
    $stmt = $db->prepare("INSERT INTO visitor_log (ip_hash, page_visited, user_agent, referer) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $ip_hash,
        $_SERVER['REQUEST_URI'] ?? '/',
        substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
        substr($_SERVER['HTTP_REFERER'] ?? '', 0, 500)
    ]);
    
    // Increment counter if new visitor (or not unique counting)
    if (!COUNT_UNIQUE_ONLY || !$already_counted) {
        $db->exec("UPDATE visitor_counter SET total_count = total_count + 1 WHERE id = 1");
    }
    
    // Get current count
    $stmt = $db->query("SELECT total_count FROM visitor_counter WHERE id = 1");
    $row = $stmt->fetch();
    
    return (int)($row['total_count'] ?? 0);
}

/**
 * Check if current count is a kiriban
 */
function checkKiriban($count) {
    return in_array($count, KIRIBAN_NUMBERS);
}

/**
 * Get unclaimed kiriban milestones
 */
function getUnclaimedKiriban() {
    $db = getDB();
    $stmt = $db->query("SELECT milestone_number FROM kiriban WHERE visitor_name IS NULL ORDER BY milestone_number ASC");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// ========== ERROR HANDLING ==========
// Set timezone
date_default_timezone_set('America/New_York');  // Virginia Tech timezone

// Enable error reporting for development (disable in production)
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
