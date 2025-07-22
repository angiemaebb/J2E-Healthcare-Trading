<?php
require_once 'db.php';

// Function to display test results in a nice format
function displayTestResult($testName, $passed, $message = '') {
    $status = $passed ? '✅ PASSED' : '❌ FAILED';
    $color = $passed ? '#4CAF50' : '#f44336';
    echo "<div style='margin: 10px 0; padding: 10px; border-radius: 5px; border: 1px solid $color'>";
    echo "<strong style='color: $color'>$status</strong> - $testName";
    if ($message) {
        echo "<br><small style='color: #666'>$message</small>";
    }
    echo "</div>";
}

// Set proper content type for HTML output
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Security Test Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Database Security Test Results</h1>
    <?php

    // Test 1: Basic Connection
    try {
        $pdo->query("SELECT 1");
        displayTestResult(
            "Database Connection", 
            true, 
            "Successfully connected with restricted user"
        );
    } catch (PDOException $e) {
        displayTestResult(
            "Database Connection", 
            false, 
            "Connection failed"
        );
    }

    // Test 2: Try to drop a table (should fail)
    try {
        $pdo->exec("DROP TABLE IF EXISTS test_table");
        displayTestResult(
            "DROP TABLE Prevention", 
            false, 
            "Security Issue: DROP TABLE operation was permitted"
        );
    } catch (PDOException $e) {
        displayTestResult(
            "DROP TABLE Prevention", 
            true, 
            "Properly prevented DROP TABLE operation"
        );
    }

    // Test 3: Try to create a table (should fail)
    try {
        $pdo->exec("CREATE TABLE test_table (id INT)");
        displayTestResult(
            "CREATE TABLE Prevention", 
            false, 
            "Security Issue: CREATE TABLE operation was permitted"
        );
    } catch (PDOException $e) {
        displayTestResult(
            "CREATE TABLE Prevention", 
            true, 
            "Properly prevented CREATE TABLE operation"
        );
    }

    // Test 4: Test SQL injection prevention
    try {
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE category_name = ?");
        $stmt->execute(["' OR '1'='1"]);
        $result = $stmt->fetchAll();
        displayTestResult(
            "SQL Injection Prevention", 
            count($result) === 0, 
            "Attempted SQL injection was properly handled"
        );
    } catch (PDOException $e) {
        displayTestResult(
            "SQL Injection Prevention", 
            true, 
            "Query was properly prepared and secured"
        );
    }

    // Test 5: Test basic CRUD operations
    try {
        // Test SELECT
        $stmt = $pdo->query("SELECT * FROM categories LIMIT 1");
        $canSelect = (bool)$stmt->fetch();

        // Test INSERT
        $stmt = $pdo->prepare("INSERT INTO categories (category_name) VALUES (?)");
        $canInsert = $stmt->execute(['Security Test Category']);
        $lastId = $pdo->lastInsertId();

        // Test UPDATE
        $stmt = $pdo->prepare("UPDATE categories SET category_name = ? WHERE category_id = ?");
        $canUpdate = $stmt->execute(['Updated Security Test Category', $lastId]);

        // Test DELETE
        $stmt = $pdo->prepare("DELETE FROM categories WHERE category_id = ?");
        $canDelete = $stmt->execute([$lastId]);

        $allPassed = $canSelect && $canInsert && $canUpdate && $canDelete;
        displayTestResult(
            "CRUD Operations", 
            $allPassed, 
            "SELECT, INSERT, UPDATE, and DELETE operations working as expected"
        );
    } catch (PDOException $e) {
        displayTestResult(
            "CRUD Operations", 
            false, 
            "Some operations failed: " . $e->getMessage()
        );
    }
    ?>
</body>
</html>