<?php
require_once 'db.php';
// config/delete_user.php

/**
 * Handles safe deletion of users by managing all foreign key constraints
 */

function deleteUser(PDO $pdo, int $userId, int $currentUserId): array
{
    // Prevent deleting own account
    if ($userId === $currentUserId) {
        return ['success' => false, 'message' => 'You cannot delete your own account'];
    }

    try {
        $pdo->beginTransaction();

        // 1. First verify user exists
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        
        if ($stmt->rowCount() === 0) {
            return ['success' => false, 'message' => 'User not found'];
        }

        // 2. Handle all foreign key references (set to NULL or alternative user)
        $tablesToUpdate = [
            'categories' => 'created_by',
            'products' => 'created_by',
            // Add other tables that reference users here
        ];

        foreach ($tablesToUpdate as $table => $column) {
            $pdo->prepare("UPDATE $table SET $column = NULL WHERE $column = ?")
               ->execute([$userId]);
        }

        // 3. Delete the user
        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);

        $pdo->commit();
        
        return ['success' => true, 'message' => 'User deleted successfully'];
    } catch (PDOException $e) {
        $pdo->rollBack();
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}