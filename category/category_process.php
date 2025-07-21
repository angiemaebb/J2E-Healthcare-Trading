<?php
require_once '../config/db.php';
require_once '../config/session_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $categoryName = $_POST['categoryName'] ?? '';
    $description = $_POST['description'] ?? '';
    $createdBy = $_SESSION['user_id']; // Assuming user_id is stored in session

    // Validate input
    if (empty($categoryName)) {
        $_SESSION['error'] = 'Category name is required';
        header('Location: category_add.php');
        exit();
    }

    try {
        // Prepare SQL statement
        $stmt = $pdo->prepare("INSERT INTO categories (category_name, description, created_by) 
                              VALUES (:name, :description, :created_by)");
        
        // Bind parameters
        $stmt->bindParam(':name', $categoryName);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':created_by', $createdBy);
        
        // Execute query
        $stmt->execute();

        // Success - redirect with success message
        $_SESSION['success'] = 'Category added successfully!';
        header('Location: category_add.php');
        exit();
    } catch (PDOException $e) {
        // Handle database errors
        $_SESSION['error'] = 'Database error: ' . $e->getMessage();
        header('Location: category_add.php');
        exit();
    }
} else {
    // Not a POST request - redirect
    header('Location: category_add.php');
    exit();
}