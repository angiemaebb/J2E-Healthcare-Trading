<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Database configuration
$host = 'localhost';
$dbname = 'j2e_inventory';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ];

    $pdo = new PDO($dsn, $username, $password, $options);

    // Log successful connection
    error_log("Database connection established successfully");

} catch (PDOException $e) {
    // Log the error
    error_log("Database Connection Error: " . $e->getMessage());
    
    // Show user-friendly error message
    die("Could not connect to the database. Please check your configuration.");
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