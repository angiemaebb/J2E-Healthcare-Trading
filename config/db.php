<?php
// Disable error reporting in production
if ($_SERVER['SERVER_NAME'] !== 'localhost') {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Store credentials in environment-like constants
define('DB_HOST', 'localhost');
define('DB_NAME', 'j2e_inventory');
define('DB_USER', 'j2e_user');
define('DB_PASS', 'J2E#secure$2023');

try {
    // Create PDO connection
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
        // Add these security options
        PDO::ATTR_PERSISTENT => false,
        PDO::MYSQL_ATTR_FOUND_ROWS => true
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

    // Log connection only in development
    if ($_SERVER['SERVER_NAME'] === 'localhost') {
        error_log("Database connection established successfully");
    }

} catch (PDOException $e) {
    // Log the error but don't expose details in production
    error_log("Database Connection Error: " . $e->getMessage());
    
    // Generic error message for production
    die("A database error occurred. Please contact the administrator.");
}

// Verify tables exist (optional debugging)
try {
    $tables = $pdo->query("SHOW TABLES")->fetchAll();
    if (empty($tables)) {
        error_log("Database is empty - no tables found");
    }
} catch (PDOException $e) {
    error_log("Table check failed: " . $e->getMessage());
}