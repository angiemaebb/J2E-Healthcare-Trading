<?php
require_once '../config/db.php';
require_once '../config/session_check.php';

// Check if invoice ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: invoice.php");
    exit();
}

$invoice_id = $_GET['id'];
$username = $_SESSION['username'];

// Fetch invoice data
$invoice_query = "SELECT * FROM invoices WHERE invoice_id = ?";
$stmt = $conn->prepare($invoice_query);
$stmt->bind_param("i", $invoice_id);
$stmt->execute();
$invoice_result = $stmt->get_result();

if ($invoice_result->num_rows === 0) {
    header("Location: invoice.php");
    exit();
}

$invoice = $invoice_result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $invoice_number = trim($_POST['invoice_number']);
    $customer_name = trim($_POST['customer_name']);
    $customer_contact = trim($_POST['customer_contact']);
    $invoice_date = $_POST['invoice_date'];
    $due_date = $_POST['due_date'];
    $total_amount = floatval($_POST['total_amount']);
    $status = $_POST['status'];
    $notes = trim($_POST['notes']);

    // Update invoice
    $update_query = "UPDATE invoices SET 
                    invoice_number = ?,
                    customer_name = ?,
                    customer_contact = ?,
                    invoice_date = ?,
                    total_amount = ?,
                    status = ?,
                    notes = ?,
                    updated_at = NOW()
                    WHERE invoice_id = ?";
    
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssdssi", 
        $invoice_number,
        $customer_name,
        $customer_contact,
        $invoice_date,
        $total_amount,
        $status,
        $notes,
        $invoice_id
    );
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Invoice updated successfully!";
        header("Location: invoice.php");
        exit();
    } else {
        $error_message = "Error updating invoice: " . $conn->error;
    }
}

// Calculate due date if not set
$due_date = date('Y-m-d', strtotime($invoice['invoice_date'] . ' + 30 days'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J2E Healthcare Trading - Edit Invoice</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        :root {
            --primary-color: #E53935;
            --secondary-color: #FF7043;
            --text-color: #333;
            --border-color: #E0E0E0;
            --background-color: #F5F5F5;
            --nav-text-color: #E53935;
            --success-color: #4CAF50;
            --error-color: #F44336;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--background-color);
            color: var(--text-color);
        }

        .main-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 2rem;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            height: 40px;
        }

        .company-name {
            font-weight: bold;
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .nav-center {
            display: flex;
            gap: 2rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: var(--nav-text-color);
            padding: 0.5rem;
            font-weight: 500;
        }

        .nav-item i {
            font-size: 24px;
        }

        .nav-item.active {
            color: var(--primary-color);
            position: relative;
        }

        .nav-item.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--primary-color);
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--nav-text-color);
        }

        .user-info i {
            font-size: 24px;
        }

        .username {
            font-weight: 500;
        }

        .menu-button {
            background: none;
            border: none;
            color: var(--nav-text-color);
            cursor: pointer;
            padding: 0.5rem;
            display: flex;
            align-items: center;
        }

        .menu-button i {
            font-size: 24px;
        }

        .edit-invoice-container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-title {
            color: var(--primary-color);
            margin: 0 0 1.5rem 0;
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
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .invoice-form {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .form-header {
            display: grid;
            gap: 1rem;
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
            background: white;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-row .form-group[style*="flex: 1"] {
            grid-column: 1 / -1;
        }

        .date-input {
            position: relative;
            display: flex;
            align-items: center;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            overflow: hidden;
            background: white;
        }

        .calendar-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f0f0;
            padding: 0.75rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .calendar-icon:hover {
            background-color: #e0e0e0;
        }

        .calendar-icon i {
            color: #666;
            font-size: 20px;
        }

        .date-input input[type="date"] {
            border: none;
            padding: 0.75rem;
            width: 100%;
            background: white;
            -webkit-calendar-picker-indicator: none;
            -webkit-appearance: none;
        }

        .amount-input {
            position: relative;
            display: flex;
            align-items: center;
        }

        .currency-icon {
            position: absolute;
            left: 0.75rem;
            color: #666;
        }

        .amount-input input {
            width: 100%;
            padding: 0.75rem 0.75rem 0.75rem 2rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            background: white;
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
            font-weight: 500;
            color: var(--text-color);
            border-bottom: 2px solid var(--border-color);
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
        }

        .form-actions {
            margin-top: 2rem;
            display: flex;
            justify-content: flex-end;
        }

        .btn-save {
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
            transition: background-color 0.2s;
        }

        .btn-save:hover {
            background-color: #d32f2f;
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: white;
            color: var(--text-color);
            border: 1px solid var(--border-color);
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-outline:hover {
            background-color: #f0f0f0;
        }

        .icon-button {
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-button:hover {
            color: var(--primary-color);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: capitalize;
            display: inline-block;
        }
        
        .status-paid {
            background-color: var(--success-color);
            color: white;
        }
        
        .status-pending {
            background-color: #FFC107;
            color: #333;
        }
        
        .status-partial {
            background-color: #2196F3;
            color: white;
        }
        
        .status-draft {
            background-color: #9E9E9E;
            color: white;
        }

        .notes-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            min-height: 100px;
            resize: vertical;
            font-family: inherit;
        }

        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-success {
            background-color: #E8F5E9;
            color: var(--success-color);
            border: 1px solid #C8E6C9;
        }

        .alert-error {
            background-color: #FFEBEE;
            color: var(--error-color);
            border: 1px solid #FFCDD2;
        }

        @media (max-width: 768px) {
            .edit-invoice-container {
                padding: 1rem;
            }
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
    <nav class="main-nav">
        <div class="nav-left">
            <img src="../images/J2E logo favicon.png" alt="J2E Healthcare Trading" class="logo">
            <span class="company-name">J2E Healthcare Trading</span>
        </div>
        <div class="nav-center">
            <a href="../home/dashboard.php" class="nav-item"><i class="material-icons">home</i> Home</a>
            <a href="../inventory/inventory.php" class="nav-item"><i class="material-icons">inventory_2</i> Inventory</a>
            <a href="../category/viewCategories.php" class="nav-item"><i class="material-icons">category</i> Category</a>
            <a href="../user/user_management.php" class="nav-item"><i class="material-icons">person</i> User</a>
            <a href="../invoice/invoice.php" class="nav-item active"><i class="material-icons">receipt</i> Invoice</a>
        </div>
        <div class="nav-right">
            <div class="user-info">
                <i class="material-icons">account_circle</i>
                <span class="username"><?php echo htmlspecialchars($username); ?></span>
            </div>
            <button class="menu-button"><i class="material-icons">menu</i></button>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="edit-invoice-container">
        <h1 class="page-title">Edit Invoice</h1>
        
        <!-- Invoice Management Button -->
        <div class="management-button">
            <a href="invoice.php" class="btn-back">
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
        <form class="invoice-form" method="POST" action="edit_invoice.php?id=<?php echo $invoice_id; ?>">
            <input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>">
            
            <!-- Invoice Header Information -->
            <div class="form-header">
                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label>Invoice No.</label>
                        <input type="text" name="invoice_number" value="<?php echo htmlspecialchars($invoice['invoice_number']); ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label>Client Name</label>
                        <input type="text" name="customer_name" value="<?php echo htmlspecialchars($invoice['customer_name']); ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label>Client Contact</label>
                        <input type="text" name="customer_contact" value="<?php echo htmlspecialchars($invoice['customer_contact']); ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Date</label>
                        <div class="date-input">
                            <div class="calendar-icon">
                                <i class="material-icons">calendar_today</i>
                            </div>
                            <input type="date" name="invoice_date" value="<?php echo date('Y-m-d', strtotime($invoice['invoice_date'])); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Total Amount</label>
                        <div class="amount-input">
                            <div class="currency-icon">₱</div>
                            <input type="number" step="0.01" name="total_amount" value="<?php echo number_format($invoice['total_amount'], 2, '.', ''); ?>" required>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Due Date</label>
                        <div class="date-input">
                            <div class="calendar-icon">
                                <i class="material-icons">calendar_today</i>
                            </div>
                            <input type="date" name="due_date" value="<?php echo date('Y-m-d', strtotime($due_date)); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" required>
                            <option value="pending" <?php echo $invoice['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="partial" <?php echo $invoice['status'] === 'partial' ? 'selected' : ''; ?>>Partial Payment</option>
                            <option value="paid" <?php echo $invoice['status'] === 'paid' ? 'selected' : ''; ?>>Paid</option>
                            <option value="draft" <?php echo $invoice['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label>Notes</label>
                        <textarea class="notes-textarea" name="notes"><?php echo htmlspecialchars($invoice['notes']); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="material-icons">save</i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Calendar icon functionality
            const calendarIcons = document.querySelectorAll('.calendar-icon');
            calendarIcons.forEach(icon => {
                icon.addEventListener('click', function(e) {
                    e.preventDefault();
                    const dateInput = this.parentElement.querySelector('input[type="date"]');
                    if (dateInput && typeof dateInput.showPicker === 'function') {
                        dateInput.showPicker();
                    } else {
                        dateInput.focus();
                    }
                });
            });

            // Form validation
            const form = document.querySelector('.invoice-form');
            form.addEventListener('submit', function(e) {
                let valid = true;
                
                // Validate required fields
                const requiredFields = form.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        valid = false;
                        field.style.borderColor = 'var(--error-color)';
                    } else {
                        field.style.borderColor = '';
                    }
                });

                // Validate dates
                const invoiceDate = new Date(form.querySelector('[name="invoice_date"]').value);
                const dueDate = new Date(form.querySelector('[name="due_date"]').value);
                
                if (dueDate < invoiceDate) {
                    alert('Due date cannot be before invoice date');
                    valid = false;
                }

                if (!valid) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>