<?php
require_once '../config/db.php';
require_once '../config/session_check.php';

// Check if invoice ID is provided and valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid invoice ID";
    header("Location: invoice.php");
    exit();
}

$invoice_id = $_GET['id'];

// Start transaction
$conn->begin_transaction();

try {
    // First delete all related invoice items
    $delete_items_query = "DELETE FROM invoice_items WHERE invoice_id = ?";
    $stmt = $conn->prepare($delete_items_query);
    $stmt->bind_param("i", $invoice_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Error deleting invoice items: " . $stmt->error);
    }

    // Then delete the invoice
    $delete_invoice_query = "DELETE FROM invoices WHERE invoice_id = ?";
    $stmt = $conn->prepare($delete_invoice_query);
    $stmt->bind_param("i", $invoice_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Error deleting invoice: " . $stmt->error);
    }

    // Commit transaction if both deletions succeeded
    $conn->commit();
    
    $_SESSION['success_message'] = "Invoice deleted successfully";
    
} catch (Exception $e) {
    // Rollback transaction if any error occurs
    $conn->rollback();
    $_SESSION['error_message'] = $e->getMessage();
}

// Redirect back to invoice list
header("Location: invoice.php");
exit();
?>