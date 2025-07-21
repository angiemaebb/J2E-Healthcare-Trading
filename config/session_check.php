<?php
session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Store the requested URL for redirect after login
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    
    // Redirect to login page
    header('Location: ../authenticate/login.php');
    exit;
}

// Get username from session for display in pages
$username = $_SESSION['username'];
?> 