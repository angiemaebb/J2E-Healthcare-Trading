<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../authenticate/login.php');
    exit();
}

// Get the contents of dashboard.html
include('dashboard.html');
?> 