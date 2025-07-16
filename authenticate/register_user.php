<?php
require_once '../config/db.php'; // This sets up $pdo

// --- User 1: Owner ---
$username1 = 'owner2';
$password1 = password_hash('ownerpassword', PASSWORD_DEFAULT); // Change to your desired password
$role_id1 = 1; // 1 = owner
$status_id1 = 1; // 1 = active

// --- User 2: Admin ---
$username2 = 'admin2';
$password2 = password_hash('adminpassword', PASSWORD_DEFAULT); // Change to your desired password
$role_id2 = 2; // 2 = admin
$status_id2 = 1; // 1 = active

try {
    // Insert Owner
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role_id, status_id) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$username1, $password1, $role_id1, $status_id1])) {
        echo "Owner user created!<br>";
    } else {
        echo "Error creating owner.<br>";
    }

    // Insert Admin
    if ($stmt->execute([$username2, $password2, $role_id2, $status_id2])) {
        echo "Admin user created!<br>";
    } else {
        echo "Error creating admin.<br>";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>