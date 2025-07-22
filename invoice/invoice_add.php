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
    $conn->begin_transaction();
    
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
        
        $stmt = $conn->prepare($invoice_query);
        $stmt->bind_param(
            "ssssdssi", 
            $invoice_number,
            $customer_name,
            $customer_contact,
            $invoice_date,
            $total_amount,
            $status,
            $notes,
            $created_by
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Error creating invoice: " . $stmt->error);
        }
        
        $invoice_id = $conn->insert_id;
        
        // Validate inventory quantities
        foreach ($_POST['items'] as $item) {
            $product_name = $item['product_name'];
            $quantity = floatval($item['quantity']);
            
            $check_query = "SELECT pi.quantity 
                           FROM product_inventory pi
                           JOIN products p ON pi.product_id = p.product_id
                           WHERE p.product_name = ?";
            $check_stmt = $conn->prepare($check_query);
            $check_stmt->bind_param("s", $product_name);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows > 0) {
                $inventory = $check_result->fetch_assoc();
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
            
            $stmt = $conn->prepare($item_query);
            
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
                    $product_stmt = $conn->prepare($product_query);
                    $product_stmt->bind_param("s", $product_name);
                    $product_stmt->execute();
                    $product_result = $product_stmt->get_result();
                    if ($product_result->num_rows > 0) {
                        $product_id = $product_result->fetch_assoc()['product_id'];
                    }
                }
                
                $stmt->bind_param(
                    "iisisddd",
                    $invoice_id,
                    $product_id,
                    $product_name,
                    $quantity,
                    $unit,
                    $unit_price,
                    $discount,
                    $subtotal
                );
                
                if (!$stmt->execute()) {
                    throw new Exception("Error adding invoice item: " . $stmt->error);
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
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ds", $quantity, $product_name);
            $update_stmt->execute();
            
            if ($update_stmt->affected_rows === 0) {
                error_log("Inventory not updated for product: " . $product_name);
            }
        }
        
        // Commit transaction
        $conn->commit();
        
        $_SESSION['success_message'] = "Invoice created successfully!";
        header("Location: invoice.php");
        exit();
        
    } catch (Exception $e) {
        $conn->rollback();
        $error_message = $e->getMessage();
    }
}

// Fetch products with inventory data - ENHANCED QUERY
$products = [];
$product_query = "SELECT p.product_id, p.product_name, pi.unit_price, pi.quantity as stock_quantity 
                 FROM products p
                 JOIN product_inventory pi ON p.product_id = pi.product_id
                 WHERE p.product_name IS NOT NULL AND p.product_name != ''
                 ORDER BY p.product_name ASC
                 LIMIT 100";
$result = $conn->query($product_query);
if ($result) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
    // Debug output
    error_log("Products fetched: " . count($products));
} else {
    error_log("Product query error: " . $conn->error);
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
    <link rel="icon" href="/images/J2E logo favicon.png" type="image/x-icon">
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
                <li><a href="../category/viewCategories.php"><i class="fas fa-tags"></i> Category</a></li>
                <li><a href="../user/user_management.php"><i class="fas fa-solid fa-user"></i> User</a></li>
                <li><a href="../invoice/invoice.php" class="active"><i class="fas fa-file-invoice"></i> Invoice</a></li>
            </ul>
        </div>

        <div class="nav-right">
            <div class="user-info">
                <img src="../images/sample user profile pic.jpg" alt="User Profile" class="user-profile">
                <span class="username"><?php echo htmlspecialchars($username); ?></span>
                <button class="hamburger" id="menuDropdown">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="user-dropdown" id="userDropdown">
                    <a href="../menu/settings.html"><i class="fas fa-cog"></i> Settings</a>
                    <a href="../menu/help.html"><i class="fas fa-question-circle"></i> Help</a>
                    <a href="#" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
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
                    <input type="text" name="customer_name" placeholder="Enter client name" required>
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
                                <input type="number" name="items[0][quantity]" class="quantity" placeholder="Qty" min="1" step="1" value="1" required>
                            </td>
                            <td>
                                <input type="text" name="items[0][unit]" class="unit" placeholder="Unit" value="pcs">
                            </td>
                            <td>
                                <div class="amount-input">
                                    <input type="number" name="items[0][unit_price]" class="unit-price" placeholder="0.00" min="0" step="0.01" required>
                                </div>
                            </td>
                            <td>
                                <div class="amount-input">
                                    <input type="number" name="items[0][discount]" class="discount" placeholder="0.00" min="0" step="0.01" value="0">
                                </div>
                            </td>
                            <td>
                                <div class="amount-input">
                                    <input type="number" class="amount" placeholder="0.00" readonly>
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn-remove-item" disabled>
                                    <i class="material-icons">delete</i>
                                </button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Product suggestions data
            const products = <?php echo json_encode($products); ?>;
            console.log("Products loaded:", products); // Debug output
            
            // Add new item row
            let itemCount = 1;
            document.getElementById('add-item').addEventListener('click', function() {
                const container = document.getElementById('items-container');
                const newRow = document.createElement('tr');
                newRow.className = 'item-row';
                newRow.innerHTML = `
                    <td class="product-autocomplete">
                        <input type="text" name="items[${itemCount}][product_name]" class="product-name" placeholder="Product name" required>
                        <div class="product-suggestions"></div>
                    </td>
                    <td>
                        <input type="number" name="items[${itemCount}][quantity]" class="quantity" placeholder="Qty" min="1" step="1" value="1" required>
                    </td>
                    <td>
                        <input type="text" name="items[${itemCount}][unit]" class="unit" placeholder="Unit" value="pcs">
                    </td>
                    <td>
                        <div class="amount-input">
                            <input type="number" name="items[${itemCount}][unit_price]" class="unit-price" placeholder="0.00" min="0" step="0.01" required>
                        </div>
                    </td>
                    <td>
                        <div class="amount-input">
                            <input type="number" name="items[${itemCount}][discount]" class="discount" placeholder="0.00" min="0" step="0.01" value="0">
                        </div>
                    </td>
                    <td>
                        <div class="amount-input">
                            <input type="number" class="amount" placeholder="0.00" readonly>
                        </div>
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
                
                quantityInput.addEventListener('change', calculateAmount);
                unitPriceInput.addEventListener('change', calculateAmount);
                discountInput.addEventListener('change', calculateAmount);
                
                // Product autocomplete
                productInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    suggestionsDiv.innerHTML = '<div class="loading">Loading...</div>';
                    suggestionsDiv.style.display = 'block';
                    
                    const filteredProducts = products.filter(product => 
                        product.product_name && product.product_name.toLowerCase().includes(searchTerm)
                    );
                    
                    showSuggestions(filteredProducts, suggestionsDiv, productInput, unitPriceInput, unitInput);
                });
                
                productInput.addEventListener('focus', function() {
                    const searchTerm = this.value.toLowerCase();
                    const filteredProducts = products.filter(product => 
                        product.product_name && product.product_name.toLowerCase().includes(searchTerm)
                    );
                    
                    showSuggestions(filteredProducts, suggestionsDiv, productInput, unitPriceInput, unitInput);
                });
                
                productInput.addEventListener('blur', function() {
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
                        
                        // Auto-fill unit if empty
                        if (unitInput && !unitInput.value) {
                            unitInput.value = 'pcs'; // Default unit
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
                
                document.getElementById('subtotal').value = subtotal.toFixed(2);
                document.getElementById('total-discount').value = totalDiscount.toFixed(2);
                document.getElementById('total-amount').value = (subtotal - totalDiscount).toFixed(2);
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
            document.querySelector('input[name="invoice_date"]').value = today;
            
            // Set due date to 30 days from today
            const dueDate = new Date();
            dueDate.setDate(dueDate.getDate() + 30);
            document.querySelector('input[name="due_date"]').value = dueDate.toISOString().split('T')[0];
        });
        
        // Dropdown and logout functionality
        const userDropdown = document.getElementById('userDropdown');
        document.getElementById('menuDropdown').onclick = (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
        };
        
        document.addEventListener('click', () => userDropdown.classList.remove('show'));

        document.getElementById('logoutBtn').addEventListener('click', function(e) {
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
    </script>
</body>
</html>