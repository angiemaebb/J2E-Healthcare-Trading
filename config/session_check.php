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

// --- Role-based access control functions ---

/**
 * Require a specific role to access the page.
 * @param string $role_name
 */
function requireRole($role_name) {
    if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] !== $role_name) {
        header("Location: ../authenticate/login.php");
        exit();
    }
}

/**
 * Require one of several roles to access the page.
 * @param array $role_names
 */
function requireRoles($role_names = []) {
    if (!isset($_SESSION['role_name']) || !in_array($_SESSION['role_name'], $role_names)) {
        header("Location: ../authenticate/login.php");
        exit();
    }
}
?> 