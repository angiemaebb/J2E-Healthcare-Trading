<?php
require_once '../config/db.php';
require_once '../config/session_check.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get username from session
$username = $_SESSION['username'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $invoice_number = $_POST['invoice_number'];
    $customer_name = $_POST['customer_name'];
    $customer_contact = $_POST['customer_contact'];
    $invoice_date = $_POST['invoice_date'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];
    $notes = $_POST['notes'];
    $created_by = $_SESSION['user_id'];
    
    // Calculate total amount from items
    $total_amount = 0;
    if (isset($_POST['items'])) {
        foreach ($_POST['items'] as $item) {
            $total_amount += floatval($item['quantity']) * floatval($item['unit_price']);
        }
    }
    
    // Start transaction
    $pdo->beginTransaction();
    
    try {
        // Insert invoice
        $invoice_query = "INSERT INTO invoices (
            invoice_number, 
            customer_name, 
            customer_contact, 
            invoice_date, 
            total_amount, 
            status, 
            notes, 
            created_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($invoice_query);
        $stmt->execute([
            $invoice_number,
            $customer_name,
            $customer_contact,
            $invoice_date,
            $total_amount,
            $status,
            $notes,
            $created_by
        ]);
        
        $invoice_id = $pdo->lastInsertId();
        
        // Validate inventory quantities
        foreach ($_POST['items'] as $item) {
            $product_name = $item['product_name'];
            $quantity = floatval($item['quantity']);
            
            $check_query = "SELECT pi.quantity 
                           FROM product_inventory pi
                           JOIN products p ON pi.product_id = p.product_id
                           WHERE p.product_name = ?";
            $check_stmt = $pdo->prepare($check_query);
            $check_stmt->execute([$product_name]);
            $inventory = $check_stmt->fetch();
            
            if ($inventory) {
                if ($inventory['quantity'] < $quantity) {
                    throw new Exception("Not enough inventory for product: " . htmlspecialchars($product_name) . 
                                      " (Available: " . $inventory['quantity'] . ")");
                }
            } else {
                throw new Exception("Product not found in inventory: " . htmlspecialchars($product_name));
            }
        }

        // Insert invoice items
        if (isset($_POST['items'])) {
            $item_query = "INSERT INTO invoice_items (
                invoice_id,
                product_id,
                product_name,
                quantity,
                unit,
                unit_price,
                discount,
                subtotal
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($item_query);
            
            foreach ($_POST['items'] as $item) {
                $product_name = $item['product_name'];
                $quantity = floatval($item['quantity']);
                $unit = $item['unit'];
                $unit_price = floatval($item['unit_price']);
                $discount = isset($item['discount']) ? floatval($item['discount']) : 0;
                $subtotal = ($quantity * $unit_price) - $discount;
                
                // Get product_id
                $product_id = null;
                if (!empty($product_name)) {
                    $product_query = "SELECT product_id FROM products WHERE product_name = ? LIMIT 1";
                    $product_stmt = $pdo->prepare($product_query);
                    $product_stmt->execute([$product_name]);
                    $product_result = $product_stmt->fetch();
                    if ($product_result) {
                        $product_id = $product_result['product_id'];
                    }
                }
                
                if (!$stmt->execute([
                    $invoice_id,
                    $product_id,
                    $product_name,
                    $quantity,
                    $unit,
                    $unit_price,
                    $discount,
                    $subtotal
                ])) {
                    throw new Exception("Error adding invoice item");
                }
            }
        }
        
        // Update inventory quantities
        foreach ($_POST['items'] as $item) {
            $product_name = $item['product_name'];
            $quantity = floatval($item['quantity']);
            
            $update_query = "UPDATE product_inventory 
                            SET quantity = quantity - ?,
                                updated_at = NOW()
                            WHERE product_id IN (
                                SELECT product_id FROM products WHERE product_name = ?
                            )";
            $update_stmt = $pdo->prepare($update_query);
            $update_stmt->execute([$quantity, $product_name]);
            
            if ($update_stmt->rowCount() === 0) {
                error_log("Inventory not updated for product: " . $product_name);
            }
        }
        
        // Commit transaction
        $pdo->commit();
        
        $_SESSION['success_message'] = "Invoice created successfully!";
        header("Location: invoice.php");
        exit();
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $error_message = $e->getMessage();
    }
}

// Fetch products with inventory data - ENHANCED QUERY
try {
    $product_query = "SELECT p.product_id, p.product_name, pi.unit_price, pi.quantity as stock_quantity, u.unit_name 
                 FROM products p
                 JOIN product_inventory pi ON p.product_id = pi.product_id
                 LEFT JOIN units u ON p.unit_id = u.unit_id
                 WHERE p.product_name IS NOT NULL AND p.product_name != ''
                 ORDER BY p.product_name ASC";
    $stmt = $pdo->query($product_query);
    $products = $stmt->fetchAll();
    
    // Debug output
    error_log("Products fetched: " . count($products));
} catch (PDOException $e) {
    error_log("Product query error: " . $e->getMessage());
    $products = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J2E Healthcare Trading - Add New Invoice</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="../images/J2E logo favicon.png" type="image/x-icon">
    <style>
        :root {
            --primary-color: #db2c24;
            --secondary-color: #ff914d;
            --text-color: #333;
            --border-color: #E0E0E0;
            --background-color: #e7e6e6;
            --nav-text-color: #db2c24;
            --success-color: #4CAF50;
            --error-color: #f44336;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            background-color: var(--background-color);
            color: var(--text-color);
        }

        html {
            margin: 0;
            padding: 0;
            width: 100vw;
            overflow-x: hidden;
        }

        * {
            font-family: 'Montserrat', sans-serif;
        }

        .top-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
        }

        h1 {
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .nav-left, .nav-right {
            display: flex;
            align-items: center;
            margin-right: 20px;
            margin-left: 20px;
        }

        .nav-center {
            flex-grow: 1;
            display: flex;
            justify-content: center;
        }

        .logo img {
            max-height: 50px;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 20px;
        }

        .nav-menu a {
            text-decoration: none;
            color: var(--primary-color);
            padding: 8px 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-menu a.active {
            font-weight: bold;
        }

        .nav-menu a:hover {
            font-weight: bold;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-right: 20px;
            position: relative;
        }

        .user-profile {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
        }

        .username {
            font-weight: bold;
            color: var(--primary-color);
            white-space: nowrap;
        }

        .hamburger {
            display: block;
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 5px 10px;
            z-index: 1001;
            color: var(--primary-color);
        }

        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            min-width: 180px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            border-radius: 5px;
            padding: 10px 0;
            z-index: 1000;
            display: none;
        }

        .user-dropdown.show {
            display: block;
        }

        .user-dropdown a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            text-decoration: none;
            color: var(--primary-color);
            font-size: 0.8rem;
        }

        .user-dropdown a:hover {
            font-weight: bold;
        }

        .user-dropdown a i {
            width: 20px;
            text-align: center;
        }

        .add-invoice-container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-title {
            color: var(--primary-color);
            margin: 0 0 1.5rem 0;
            border-bottom: 2px solid var(--primary-color);
        }

        .management-button {
            margin-bottom: 2rem;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: white;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            background-color: var(--primary-color);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .btn-back:hover {
            background-color: var(--secondary-color);
            font-weight: bold;
        }

        .invoice-form {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .form-header {
            display: grid;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            font-weight: 500;
            color: var(--text-color);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 1rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .date-input {
            position: relative;
            display: flex;
            align-items: center;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            overflow: hidden;
        }

        .calendar-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f0f0;
            padding: 0.75rem;
        }

        .calendar-icon i {
            color: #666;
            font-size: 20px;
        }

        .date-input input[type="date"] {
            border: none;
            padding: 0.75rem;
            width: 100%;
            -webkit-calendar-picker-indicator: none;
        }

        .header-amount {
            border: 1px solid var(--border-color);
            border-radius: 4px;
            overflow: hidden;
        }

        .header-amount input {
            border: none;
            padding: 0.75rem;
        }

        .amount-input {
            position: relative;
            display: flex;
            align-items: center;
        }

        .amount-input input {
            width: 100%;
            padding: 0.75rem 0.75rem 0.75rem 2rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
        }

        .currency-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f0f0;
            padding: 0.75rem;
            width: 20px;
        }

        .currency {
            color: #666;
            font-weight: 500;
        }

        .invoice-items {
            margin-top: 2rem;
            overflow-x: auto;
        }

        .invoice-items table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        .invoice-items th {
            background-color: #f5f5f5;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--text-color);
            border-bottom: 1px solid var(--border-color);
        }

        .invoice-items td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .invoice-items input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .invoice-items .amount-input {
            width: 100%;
        }

        .form-actions {
            margin-top: 2rem;
            display: flex;
            justify-content: flex-end;
        }

        .btn-save {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-save:hover {
            background-color: var(--secondary-color);
            font-weight: bold;
        }

        /* New styles for item management */
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .alert-success {
            background-color: #e8f5e9;
            color: var(--success-color);
            border: 1px solid #c8e6c9;
        }
        
        .alert-error {
            background-color: #ffebee;
            color: var(--error-color);
            border: 1px solid #ffcdd2;
        }
        
        .btn-add-item {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 1rem;
        }
        
        .btn-add-item:hover {
            background-color: var(--secondary-color);
        }
        
        .btn-remove-item {
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 0.5rem;
        }
        
        .btn-remove-item:hover {
            color: var(--primary-color);
        }
        
        .product-autocomplete {
            position: relative;
        }
        
        .product-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 0 0 4px 4px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 100;
            display: none;
        }
        
        .product-suggestion {
            padding: 0.5rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .product-suggestion:hover {
            background-color: #f5f5f5;
        }

        .loading {
            padding: 0.5rem;
            color: var(--medium-gray);
            font-style: italic;
        }
        
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
            position: relative;
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 20px;
        }
        
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        
        #clientForm .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }
        
        #clientForm .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        #clientForm .form-group label {
            font-weight: 500;
            color: var(--text-color);
            font-size: 0.95rem;
        }
        
        #clientForm input,
        #clientForm textarea {
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 1rem;
            width: 100%;
        }
        
        #clientForm textarea {
            min-height: 80px;
            resize: vertical;
        }
        
        #clientForm .form-actions {
            margin-top: 1.5rem;
            display: flex;
            justify-content: flex-end;
        }
        
        #clientForm .btn-save {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
        }
        
        #clientForm .btn-save:hover {
            background-color: var(--secondary-color);
        }
        
        .client-name-group {
            display: flex;
            gap: 10px;
        }
        
        .client-name-group input {
            flex: 1;
        }
        
        .add-client-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            padding: 0 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .add-client-btn:hover {
            background: var(--secondary-color);
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            .nav-center {
                display: none;
            }
            .invoice-items {
                margin: 1rem -1rem;
            }
        }

        @media (max-width: 480px) {
            .company-name {
                display: none;
            }
            .nav-right .username {
                display: none;
            }
        }

        /*menu things*/
        /* Settings and Help Modal Styles */
	.menu-btns {
    background-color: var(--primary-color);
    min-height: 40px;
    border: none;
    padding: 12px;
    border-radius: 5px;
    cursor: pointer;
    text-align: center;
    transition: background-color 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    color: white;
    font-weight: bold;
    white-space: nowrap;
}

.menu-btns:hover {
    background-color: var(--secondary-color);
}

.menu-btns i {
    font-size: 14px;
}

        .settings-section,
        .help-section {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--light-gray);
        }

        .setting-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
            padding: 8px 0;
        }

        .setting-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }

        .setting-btn:hover {
            background-color: var(--secondary-color);
        }

        .setting-select,
        .setting-input {
            padding: 5px;
            border: 1px solid var(--light-gray);
            border-radius: 3px;
            width: 150px;
        }

        .settings-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .save-btn {
            background-color: #28a745;
        }

        .cancel-btn {
            background-color: var(--medium-gray);
        }

        .help-list {
            list-style: none;
            padding: 0;
        }

        .help-list li {
            margin: 8px 0;
        }

        .help-list a {
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .help-list a:hover {
            text-decoration: underline;
        }

        .contact-info {
            margin-left: 10px;
        }

        .contact-info p {
            margin: 8px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .issue-form .form-group {
            margin-bottom: 15px;
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid var(--light-gray);
            border-radius: 4px;
        }

        .form-textarea {
            resize: vertical;
        }

/*FOOTER CSS starts here*/
        .footer-fullwidth {
            width: 100vw;
            position: relative;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            background-image: url('../images/footerBackground.png');
            background-position: 60%;
            background-size: cover;
            height: 400px;
            padding: 40px 0;
            margin-top: 0px;
            margin-bottom: -50px;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer-content {
            display: flex;
            justify-content: flex-end;
        }

        .footer-content h4 {
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .footer-left {
            margin-top: 15px;
            margin-right: 100px;
            justify-content: flex-end;
        }

        .footer-left img {
            height: 150px;
        }

        .footer-right {
            display: flex;
            gap: 60px;
            justify-content: flex-end;
        }

        .footer-right ul {
            list-style: none;
            padding: 0;
            margin: 0;
            line-height: 1.5rem;
        }

        .footer-right a {
            text-decoration: none;
            transition: color 0.2s;
            color: var(--dark-gray);
        }

        .footer-right a:hover {
            color: var(--primary-color);
            font-weight: bold;
            text-decoration: none;
        }

        .footer-info {
            max-width: 800px;
            margin: 30px auto 0;
            padding: 0 20px;
            font-size: 0.8rem;
            text-align: center;
            color: var(--dark-gray);
        }


        .footer-legal {
            text-align: center;
            margin-top: 20px;
            padding: 10px 0;
            font-size: 0.8rem;
            color: var(--dark-gray);
        }

        .footer-legal a {
            color: var(--dark-gray);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-legal a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }


        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            border-radius: 5px;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            top: 40%;
            transform: translateY(-50%);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .close-modal {
            position: absolute;
            right: 15px;
            top: 5px;
            font-size: 24px;
            cursor: pointer;
            color: var(--dark-gray);
        }

        .close-modal:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <!-- Navigation Header -->
    <nav class="top-nav">
        <div class="nav-left">
            <div class="logo">
                <img src="../images/J2E-logo2.png" alt="J2E Healthcare Trading Logo">
            </div>
        </div>

        <div class="nav-center">
            <ul class="nav-menu">
                <li><a href="../home/dashboard.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="../inventory/inventory.php"><i class="fas fa-boxes"></i> Inventory</a></li>
                <li><a href="../category/category_edit.php"><i class="fas fa-tags"></i> Category</a></li>
                <li><a href="../user/user_management.php"><i class="fas fa-solid fa-user"></i> User</a></li>
                <li><a href="../invoice/invoice.php" class="active"><i class="fas fa-file-invoice"></i> Invoice</a></li>
            </ul>
        </div>

        <div class="nav-right">
            <div class="user-info">
<img src="<?php 
                if (!empty($_SESSION['user_image'])) {
                    echo (strpos($_SESSION['user_image'], '/') === 0 ? '' : '../') . htmlspecialchars($_SESSION['user_image']);
                } else {
                    echo '../images/sample user profile pic.jpg';
                }
            ?>" alt="User Profile" class="user-profile">
            <span class="username"><?php echo htmlspecialchars($username); ?></span>
                <button class="hamburger" id="menuDropdown">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="user-dropdown" id="userDropdown">
                    <a href="#settings"><i class="fas fa-cog"></i> Settings</a>
                    <a href="#help"><i class="fas fa-question-circle"></i> Help</a>
                    <a id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="add-invoice-container">
        <div class="page-title">
            <h1>Add New Invoice</h1>
        </div>

        <!-- Invoice Management Button -->
        <div class="management-button">
            <a href="../invoice/invoice.php" class="btn-back">
                <i class="material-icons">keyboard_backspace</i>
                Invoice Management
            </a>
        </div>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-error">
                <i class="material-icons">error</i>
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Invoice Form -->
        <form class="invoice-form" method="POST" action="invoice_add.php">
            <!-- Invoice Header Information -->
            <div class="form-header">
                <div class="form-group">
                    <label>Invoice No.</label>
                    <input type="text" name="invoice_number" placeholder="Enter invoice number" required>
                </div>
                <div class="form-group">
                    <label>Client Name</label>
                    <div class="client-name-group">
                        <input type="text" name="customer_name" id="customerName" placeholder="Enter client name" required>
                        <button type="button" id="addClientBtn" class="add-client-btn">
                            <i class="material-icons">person_add</i>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label>Client Contact</label>
                    <input type="text" name="customer_contact" placeholder="Enter client contact">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Date</label>
                        <div class="date-input">
                            <div class="calendar-icon">
                                <i class="material-icons">calendar_today</i>
                            </div>
                            <input type="date" name="invoice_date" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Due Date</label>
                        <div class="date-input">
                            <div class="calendar-icon">
                                <i class="material-icons">calendar_today</i>
                            </div>
                            <input type="date" name="due_date" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="draft">Draft</option>
                        <option value="pending">Pending</option>
                        <option value="partial">Partial Payment</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea name="notes" placeholder="Additional notes"></textarea>
                </div>
            </div>

            <!-- Invoice Items Table -->
            <div class="invoice-items">
                <table>
                    <thead>
                        <tr>
                            <th>PRODUCT</th>
                            <th>QUANTITY</th>
                            <th>UNIT</th>
                            <th>UNIT PRICE</th>
                            <th>DISCOUNT</th>
                            <th>AMOUNT</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody id="items-container">
                        <!-- Initial empty row -->
                        <tr class="item-row">
                            <td class="product-autocomplete">
                                <input type="text" name="items[0][product_name]" class="product-name" placeholder="Product name" required>
                                <div class="product-suggestions"></div>
                            </td>
                            <td>
                                <input type="number" name="items[0][quantity]" class="quantity" placeholder="Quantity" min="1" required>
                            </td>
                            <td>
                                <input type="text" name="items[0][unit]" class="unit" placeholder="Unit" readonly>
                            </td>
                            <td>
                                <input type="number" name="items[0][unit_price]" class="unit-price" placeholder="Unit Price" step="0.01" required readonly>
                            </td>
                            <td>
                                <input type="number" name="items[0][discount]" class="discount" placeholder="Discount" value="0" min="0" step="0.01">
                            </td>
                            <td>
                                <input type="number" name="items[0][amount]" class="amount" placeholder="Amount" readonly>
                            </td>
                            <td>
                                <button type="button" class="btn-remove-item"><i class="material-icons">delete</i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <button type="button" id="add-item" class="btn-add-item">
                    <i class="material-icons">add</i> Add Item
                </button>
                
                <div class="form-row" style="margin-top: 2rem;">
                    <div class="form-group" style="flex: 1;"></div>
                    <div class="form-group">
                        <label>Subtotal</label>
                        <div class="amount-input header-amount">
                            <div class="currency-icon">
                                <span class="currency">₱</span>
                            </div>
                            <input type="number" id="subtotal" placeholder="0.00" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group" style="flex: 1;"></div>
                    <div class="form-group">
                        <label>Total Discount</label>
                        <div class="amount-input header-amount">
                            <div class="currency-icon">
                                <span class="currency">₱</span>
                            </div>
                            <input type="number" id="total-discount" placeholder="0.00" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group" style="flex: 1;"></div>
                    <div class="form-group">
                        <label>Total Amount</label>
                        <div class="amount-input header-amount">
                            <div class="currency-icon">
                                <span class="currency">₱</span>
                            </div>
                            <input type="number" name="total_amount" id="total-amount" placeholder="0.00" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="material-icons">save</i>
                    Save New Invoice
                </button>
            </div>
        </form>
    </div>

    <!-- Add Client Modal -->
    <div id="addClientModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add New Client Information</h2>
            <form id="clientForm">
                <div class="form-row">
                    <div class="form-group">
                        <label>OSCA/PWD ID No.</label>
                        <input type="text" name="osca_id">
                    </div>
                    <div class="form-group">
                        <label>TIN</label>
                        <input type="text" name="tin">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" rows="3"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Client Name</label>
                        <input type="text" name="client_name" id="modalClientName">
                    </div>
                    <div class="form-group">
                        <label>SC/PWD Signature</label>
                        <input type="text" name="signature">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Cashier/Authorized Representative</label>
                        <input type="text" name="representative">
                    </div>
                    <div class="form-group">
                        <label>Business Style</label>
                        <input type="text" name="business_style">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-save">Save New Client</button>
                </div>
            </form>
        </div>
    </div>

    <!--Menu things-->
    <!-- Settings Modal -->
    <div id="settings-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h3><i class="fas fa-cog"></i> System Settings</h3>

            <div class="settings-section">
                <h4><i class="fas fa-user-cog"></i> Account Settings</h4>
                <div class="setting-item">
                    <label>Change Password</label>
                    <button class="setting-btn">Update</button>
                </div>
                <div class="setting-item">
                    <label>Notification Preferences</label>
                    <button class="setting-btn">Configure</button>
                </div>
            </div>

            <div class="settings-section">
                <h4><i class="fas fa-sliders-h"></i> System Preferences</h4>
                <div class="setting-item">
                    <label>Theme Color</label>
                    <select class="setting-select">
                        <option>Red (Default)</option>
                        <option>Blue</option>
                        <option>Green</option>
                    </select>
                </div>
                <div class="setting-item">
                    <label>Items Per Page</label>
                    <input type="number" class="setting-input" value="25" min="10" max="100">
                </div>
            </div>

            <div class="settings-section">
                <h4><i class="fas fa-database"></i> Data Management</h4>
                <div class="setting-item">
                    <label>Export Inventory Data</label>
                    <button class="setting-btn">CSV Export</button>
                </div>
                <div class="setting-item">
                    <label>Backup System</label>
                    <button class="setting-btn">Create Backup</button>
                </div>
            </div>

            <div class="settings-actions">
                <button class="menu-btns save-btn"><i class="fas fa-save"></i> Save Changes</button>
                <button class="menu-btns cancel-btn"><i class="fas fa-times"></i> Cancel</button>
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div id="help-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h3><i class="fas fa-question-circle"></i> Help Center</h3>

            <div class="help-section">
                <h4><i class="fas fa-book"></i> Documentation</h4>
                <ul class="help-list">
                    <li><a href="#"><i class="fas fa-file-alt"></i> User Manual</a></li>
                    <li><a href="#"><i class="fas fa-video"></i> Video Tutorials</a></li>
                    <li><a href="#"><i class="fas fa-chart-bar"></i> Inventory Management Guide</a></li>
                </ul>
            </div>

            <div class="help-section">
                <h4><i class="fas fa-headset"></i> Support</h4>
                <div class="contact-info">
                    <p><i class="fas fa-envelope"></i> Email: support@j2ehealthcare.com</p>
                    <p><i class="fas fa-phone"></i> Phone: (02) 8123-4567</p>
                    <p><i class="fas fa-clock"></i> Hours: Mon-Fri, 9AM-5PM</p>
                </div>
            </div>

            <div class="help-section">
                <h4><i class="fas fa-bug"></i> Report an Issue</h4>
                <form class="issue-form">
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" class="form-input">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-textarea" rows="4"></textarea>
                    </div>
                    <button type="submit" class="menu-btns"><i class="fas fa-paper-plane"></i> Submit</button>
                </form>
            </div>
        </div>
    </div>

    <!--footer thingies-->
    <div class="footer-fullwidth">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-left">
                    <img src="../images/J2E-logo3.png">
                </div>
                <div class="footer-right">
                    <div>
                        <h4>Products</h4>
                        <ul>
                            <li>Supply</li>
                            <li>Equipment</li>
                        </ul>
                    </div>
                    <div>
                        <h4>Navigation</h4>
                        <ul>
                            <li><a href="../home/dashboard.php" class="active"> Home</a></li>
                            <li><a href="../inventory/inventory.php"> Inventory</a></li>
                            <li><a href="../category/category_add.php"> Category</a></li>
                            <li><a href="../user/user_management.php"> User</a></li>
                            <li><a href="../invoice/invoice.php"> Invoice</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-info">
                <p>J2E Healthcare Trading was established on September 30, 2020, and is registered with the
                    Department of Trade and Industry (DTI) under BNN 2155601. It specializes in physical and
                    occupational therapy supplies and equipment with a national scope.</p>
            </div>

            <div class="footer-legal">
                <p>
                    © 2024 J2E Healthcare Trading. All Rights Reserved. |
                    <a href="#" class="legal-link" id="privacy-policy-link">Privacy Policy</a> |
                    <a href="#" class="legal-link" id="terms-service-link">Terms of Service</a>
                </p>
            </div>
        </div>
    </div>

    <div id="privacy-policy-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h3>Privacy Policy</h3>
            <p><em>Last Updated: July 17, 2025</em></p>

            <p>This Privacy Policy governs how J2E Healthcare Trading ("we," "us") collects, uses, and protects your
                data in our inventory management system.</p>

            <h4>2. Data We Collect</h4>
            <ul>
                <li><strong>Account Information:</strong> Names, emails, usernames, passwords.</li>
                <li><strong>Inventory Data:</strong> Product details, supplier info, transaction records.</li>
                <li><strong>Automated Data:</strong> IP addresses, cookies (if used for analytics).</li>
            </ul>

            <h4>3. How We Use Data</h4>
            <ul>
                <li>To manage user access and system functionality.</li>
                <li>To track inventory, sales, and business operations.</li>
                <li>To comply with legal obligations (e.g., tax records).</li>
            </ul>

            <h4>4. Data Protection</h4>
            <p>We implement security measures like encryption (SSL), access controls, and regular audits to protect your
                data.</p>

            <h4>5. Third-Party Sharing</h4>
            <p>Data is only shared with essential service providers (e.g., hosting). We never sell your information.</p>

            <h4>6. Your Rights</h4>
            <p>You may request access, correction, or deletion of your personal data by contacting us at [Your Email].
            </p>

            <h4>7. Policy Updates</h4>
            <p>Changes will be posted here. Continued use of the system constitutes acceptance.</p>

            <p><strong>Contact Us:</strong> For questions, email j2e_admin@gmail.com or call 09452222222.</p>
        </div>
    </div>

    <div id="terms-service-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h3>Terms of Service</h3>
            <p><em>Last Updated: July 17, 2025</em></p>

            <h4>1. Acceptance</h4>
            <p>By accessing our inventory management system, you agree to these Terms.</p>

            <h4>2. User Responsibilities</h4>
            <ul>
                <li>Keep login credentials secure.</li>
                <li>Enter accurate inventory/sales data.</li>
                <li>Do not share accounts or misuse the system.</li>
            </ul>

            <h4>3. Prohibited Actions</h4>
            <ul>
                <li>Reverse-engineering or hacking the software.</li>
                <li>Uploading false/misleading data.</li>
                <li>Using the system for illegal activities.</li>
            </ul>

            <h4>4. Intellectual Property</h4>
            <p>The software, logos, and content are owned by J2E Healthcare Trading. Unauthorized use is prohibited.</p>

            <h4>5. Limitation of Liability</h4>
            <p>We are not liable for:</p>
            <ul>
                <li>Data loss due to user error.</li>
                <li>System downtime beyond our control.</li>
            </ul>

            <h4>6. Termination</h4>
            <p>We may suspend accounts for violations of these Terms.</p>

            <h4>7. Governing Law</h4>
            <p>These Terms are governed by the laws of the Philippines.</p>

            <p><strong>Contact Us:</strong> For disputes or questions, email j2e_admin@gmail.com.</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Store products data from PHP
            const products = <?php echo json_encode($products); ?>;
            console.log("Products loaded:", products); // Debug logging
            
            // Function to handle product selection and auto-populate fields
            function handleProductSelection(input, product) {
                const row = input.closest('tr');
                
                // Set product name
                input.value = product.product_name;
                
                // Auto-populate unit
                const unitInput = row.querySelector('input[name$="[unit]"]');
                if (unitInput) {
                    unitInput.value = product.unit_name || '';
                }
                
                // Auto-populate unit price
                const priceInput = row.querySelector('input[name$="[unit_price]"]');
                if (priceInput) {
                    priceInput.value = product.unit_price || '';
                }
                
                // Update amount calculation
                calculateRowAmount(row);
            }

            // Function to calculate row amount
            function calculateRowAmount(row) {
                const quantity = parseFloat(row.querySelector('input[name$="[quantity]"]').value) || 0;
                const unitPrice = parseFloat(row.querySelector('input[name$="[unit_price]"]').value) || 0;
                const discount = parseFloat(row.querySelector('input[name$="[discount]"]').value) || 0;
                const amount = (quantity * unitPrice) - discount;
                
                row.querySelector('input[name$="[amount]"]').value = amount.toFixed(2);
                calculateTotals();
            }

            // Product name input event handler
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('product-name')) {
                    const input = e.target;
                    const searchTerm = input.value.toLowerCase();
                    const suggestionsDiv = input.nextElementSibling;
                    
                    // Clear previous suggestions
                    suggestionsDiv.innerHTML = '';
                    
                    if (searchTerm.length < 2) {
                        suggestionsDiv.style.display = 'none';
                        return;
                    }
                    
                    // Filter products based on search term
                    const matches = products.filter(p => 
                        p.product_name.toLowerCase().includes(searchTerm)
                    );
                    
                    if (matches.length > 0) {
                        suggestionsDiv.style.display = 'block';
                        matches.forEach(product => {
                            const div = document.createElement('div');
                            div.className = 'product-suggestion';
                            div.textContent = product.product_name;
                            div.addEventListener('click', () => {
                                handleProductSelection(input, product);
                                suggestionsDiv.style.display = 'none';
                            });
                            suggestionsDiv.appendChild(div);
                        });
                    } else {
                        suggestionsDiv.style.display = 'none';
                    }
                }
            });

            // Enhanced Dropdown functionality
            function closeAllDropdowns(exceptElement) {
                if (!exceptElement || !exceptElement.closest('.searchable-dropdown')) {
                    document.getElementById('salesFilterOptions')?.classList.remove('show');
                    document.getElementById('categoryFilterOptions')?.classList.remove('show');
                    document.getElementById('userDropdown')?.classList.remove('show');
                }
            }

            // Enhanced User dropdown functionality
            const menuDropdown = document.getElementById('menuDropdown');
            const userDropdown = document.getElementById('userDropdown');

            if (menuDropdown && userDropdown) {
                menuDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const wasOpen = userDropdown.classList.contains('show');
                    closeAllDropdowns();
                    if (!wasOpen) {
                        userDropdown.classList.add('show');
                    }
                });
            }

            // Close dropdowns when clicking elsewhere
            document.addEventListener('click', function(e) {
                closeAllDropdowns(e.target);
            });

            // Prevent dropdown close when clicking inside dropdown
            document.querySelectorAll('.searchable-dropdown').forEach(dropdown => {
                dropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });

            // Enhanced Logout functionality
            document.getElementById('logoutBtn')?.addEventListener('click', function(e) {
                e.preventDefault();
                fetch('../authenticate/logout.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = '../authenticate/login.php';
                        } else {
                            alert('Logout failed. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred during logout. Please try again.');
                    });
            });

            // Original Invoice Form Functionality
            // Add new item row
            let itemCount = 1;
            document.getElementById('add-item')?.addEventListener('click', function() {
                const container = document.getElementById('items-container');
                const newRow = document.createElement('tr');
                newRow.className = 'item-row';
                newRow.innerHTML = `
                    <td class="product-autocomplete">
                        <input type="text" name="items[${itemCount}][product_name]" class="product-name" placeholder="Product name" required>
                        <div class="product-suggestions"></div>
                    </td>
                    <td>
                        <input type="number" name="items[${itemCount}][quantity]" class="quantity" placeholder="Quantity" min="1" required>
                    </td>
                    <td>
                        <input type="text" name="items[${itemCount}][unit]" class="unit" placeholder="Unit" readonly>
                    </td>
                    <td>
                        <input type="number" name="items[${itemCount}][unit_price]" class="unit-price" placeholder="Unit Price" step="0.01" required readonly>
                    </td>
                    <td>
                        <input type="number" name="items[${itemCount}][discount]" class="discount" placeholder="Discount" value="0" min="0" step="0.01">
                    </td>
                    <td>
                        <input type="number" name="items[${itemCount}][amount]" class="amount" placeholder="Amount" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn-remove-item">
                            <i class="material-icons">delete</i>
                        </button>
                    </td>
                `;
                container.appendChild(newRow);
                itemCount++;
                
                // Initialize event listeners for the new row
                initRowEvents(newRow);
                
                // Enable remove buttons if there's more than one row
                updateRemoveButtons();
            });
            
            // Initialize event listeners for existing rows
            document.querySelectorAll('.item-row').forEach(row => {
                initRowEvents(row);
            });
            
            // Update remove buttons initially
            updateRemoveButtons();
            
            // Function to initialize event listeners for a row
            function initRowEvents(row) {
                const productInput = row.querySelector('.product-name');
                const quantityInput = row.querySelector('.quantity');
                const unitPriceInput = row.querySelector('.unit-price');
                const discountInput = row.querySelector('.discount');
                const amountInput = row.querySelector('.amount');
                const suggestionsDiv = row.querySelector('.product-suggestions');
                const unitInput = row.querySelector('.unit');
                
                // Calculate amount when quantity or price changes
                function calculateAmount() {
                    const quantity = parseFloat(quantityInput.value) || 0;
                    const unitPrice = parseFloat(unitPriceInput.value) || 0;
                    const discount = parseFloat(discountInput.value) || 0;
                    const amount = (quantity * unitPrice) - discount;
                    
                    amountInput.value = amount.toFixed(2);
                    calculateTotals();
                }
                
                quantityInput?.addEventListener('change', calculateAmount);
                unitPriceInput?.addEventListener('change', calculateAmount);
                discountInput?.addEventListener('change', calculateAmount);
                
                // Product autocomplete
                productInput?.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    suggestionsDiv.innerHTML = '<div class="loading">Loading...</div>';
                    suggestionsDiv.style.display = 'block';
                    
                    const filteredProducts = products.filter(product => 
                        product.product_name && product.product_name.toLowerCase().includes(searchTerm)
                    );
                    
                    showSuggestions(filteredProducts, suggestionsDiv, productInput, unitPriceInput, unitInput);
                });
                
                productInput?.addEventListener('focus', function() {
                    const searchTerm = this.value.toLowerCase();
                    const filteredProducts = products.filter(product => 
                        product.product_name && product.product_name.toLowerCase().includes(searchTerm)
                    );
                    
                    showSuggestions(filteredProducts, suggestionsDiv, productInput, unitPriceInput, unitInput);
                });
                
                productInput?.addEventListener('blur', function() {
                    setTimeout(() => {
                        suggestionsDiv.style.display = 'none';
                    }, 200);
                });
            }
            
            // Enhanced showSuggestions function
            function showSuggestions(filteredProducts, suggestionsDiv, productInput, unitPriceInput, unitInput) {
                suggestionsDiv.innerHTML = '';
                
                if (!filteredProducts || filteredProducts.length === 0) {
                    suggestionsDiv.innerHTML = '<div class="loading">No products found</div>';
                    return;
                }
                
                filteredProducts.forEach(product => {
                    // Skip products without a name
                    if (!product.product_name || product.product_name.trim() === '') return;
                    
                    const suggestion = document.createElement('div');
                    suggestion.className = 'product-suggestion';
                    
                    // Format price and stock display
                    const price = product.unit_price ? parseFloat(product.unit_price).toFixed(2) : '0.00';
                    const stock = product.stock_quantity || 0;
                    
                    suggestion.innerHTML = `
                        <div style="font-weight:bold;">${product.product_name}</div>
                        <div style="font-size:0.8em;color:#666;">
                            ₱${price} | Stock: ${stock}
                        </div>
                    `;
                    
                    suggestion.addEventListener('mousedown', function(e) {
                        e.preventDefault();
                        productInput.value = product.product_name;
                        unitPriceInput.value = price;
                        
                        // Auto-fill unit with unit_symbol or unit_name from the product
                        if (unitInput) {
                            unitInput.value = product.unit_symbol || product.unit_name || '';
                        }
                        
                        suggestionsDiv.style.display = 'none';
                        
                        // Show stock warnings
                        if (stock < 1) {
                            alert(`Warning: ${product.product_name} is out of stock!`);
                        } else if (stock < 5) {
                            alert(`Warning: Low stock for ${product.product_name} (${stock} remaining)`);
                        }
                        
                        // Trigger calculations
                        const event = new Event('change');
                        unitPriceInput.dispatchEvent(event);
                        productInput.closest('.item-row').querySelector('.quantity').focus();
                    });
                    
                    suggestionsDiv.appendChild(suggestion);
                });
                
                suggestionsDiv.style.display = 'block';
            }
            
            // Remove item row
            document.addEventListener('click', function(e) {
                if (e.target.closest('.btn-remove-item')) {
                    const row = e.target.closest('.item-row');
                    row.remove();
                    calculateTotals();
                    updateRemoveButtons();
                }
            });
            
            // Calculate all totals
            function calculateTotals() {
                let subtotal = 0;
                let totalDiscount = 0;
                
                document.querySelectorAll('.item-row').forEach(row => {
                    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                    const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
                    const discount = parseFloat(row.querySelector('.discount').value) || 0;
                    
                    subtotal += quantity * unitPrice;
                    totalDiscount += discount;
                });
                
                const subtotalElement = document.getElementById('subtotal');
                const totalDiscountElement = document.getElementById('total-discount');
                const totalAmountElement = document.getElementById('total-amount');
                
                if (subtotalElement) subtotalElement.value = subtotal.toFixed(2);
                if (totalDiscountElement) totalDiscountElement.value = totalDiscount.toFixed(2);
                if (totalAmountElement) totalAmountElement.value = (subtotal - totalDiscount).toFixed(2);
            }
            
            // Enable/disable remove buttons based on row count
            function updateRemoveButtons() {
                const rows = document.querySelectorAll('.item-row');
                const removeButtons = document.querySelectorAll('.btn-remove-item');
                
                if (rows.length <= 1) {
                    removeButtons.forEach(btn => btn.disabled = true);
                } else {
                    removeButtons.forEach(btn => btn.disabled = false);
                }
            }
            
            // Set today's date as default for invoice date
            const today = new Date().toISOString().split('T')[0];
            const invoiceDateInput = document.querySelector('input[name="invoice_date"]');
            if (invoiceDateInput) invoiceDateInput.value = today;
            
            // Set due date to 30 days from today
            const dueDate = new Date();
            dueDate.setDate(dueDate.getDate() + 30);
            const dueDateInput = document.querySelector('input[name="due_date"]');
            if (dueDateInput) dueDateInput.value = dueDate.toISOString().split('T')[0];
            
            // Modal functionality for client form
            const modal = document.getElementById('addClientModal');
            const closeBtn = document.querySelector('.close');
            const addClientBtn = document.getElementById('addClientBtn');
            
            if (addClientBtn && modal) {
                addClientBtn.addEventListener('click', function() {
                    const clientName = document.getElementById('customerName').value;
                    document.getElementById('modalClientName').value = clientName;
                    modal.style.display = 'block';
                });
            }
            
            if (closeBtn && modal) {
                closeBtn.onclick = function() {
                    modal.style.display = 'none';
                }
            }
            
            window.onclick = function(event) {
                if (modal && event.target == modal) {
                    modal.style.display = 'none';
                }
            }
            
            const clientForm = document.getElementById('clientForm');
            if (clientForm) {
                clientForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    // Here you would typically send the form data to the server
                    alert('Client saved successfully!');
                    modal.style.display = 'none';
                    this.reset();
                });
            }

            // Footer Modal functionality
            const modals = {
                privacy: document.getElementById('privacy-policy-modal'),
                terms: document.getElementById('terms-service-modal'),
                settings: document.getElementById('settings-modal'),
                help: document.getElementById('help-modal')
            };

            const modalTriggers = {
                privacy: document.getElementById('privacy-policy-link'),
                terms: document.getElementById('terms-service-link'),
                settings: document.querySelector('.user-dropdown a[href="#settings"]'),
                help: document.querySelector('.user-dropdown a[href="#help"]')
            };

            const closeButtons = document.querySelectorAll('.close-modal');

            function closeAllModals() {
                Object.values(modals).forEach(modal => {
                    if (modal) modal.style.display = 'none';
                });
            }

            Object.entries(modalTriggers).forEach(([key, trigger]) => {
                if (trigger && modals[key]) {
                    trigger.addEventListener('click', function(e) {
                        e.preventDefault();
                        closeAllDropdowns();
                        closeAllModals();
                        modals[key].style.display = 'block';
                    });
                }
            });

            closeButtons.forEach(button => {
                button.addEventListener('click', closeAllModals);
            });

            window.addEventListener('click', function(e) {
                Object.values(modals).forEach(modal => {
                    if (modal && e.target === modal) {
                        modal.style.display = 'none';
                    }
                });
            });

            // Video background handling
            const video = document.querySelector('.video-background video');
            if (video) {
                video.play().catch(e => {
                    console.log("Video autoplay prevented, showing fallback");
                    video.style.display = 'none';
                    document.querySelector('.video-background').style.backgroundImage = 'url(../images/video-fallback.jpg)';
                });
            }

            // Search functionality for dropdowns
            document.querySelectorAll('.search-input').forEach(input => {
                input.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const resultsContainer = this.nextElementSibling;
                    const items = resultsContainer?.querySelectorAll('.search-result-item');

                    items?.forEach(item => {
                        const text = item.textContent.toLowerCase();
                        item.style.display = text.includes(searchTerm) ? 'block' : 'none';
                    });
                });
            });
        });
    </script>
</body>
</html>