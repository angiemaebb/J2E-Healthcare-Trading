<?php
require_once '../config/db.php';
require_once '../config/session_check.php';

// Get the current user's information from the session
$username = $_SESSION['username'];

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../authenticate/login.php");
    exit();
}
// Query to fetch invoices
$query = "SELECT 
            i.invoice_id,
            i.invoice_number,
            i.invoice_date,
            i.customer_name,
            i.customer_contact,
            i.total_amount,
            i.status,
            i.notes,
            i.created_at,
            i.updated_at
          FROM invoices i
          ORDER BY i.invoice_date DESC";

try {
    $stmt = $pdo->query($query);
    $invoices = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching invoices: " . $e->getMessage());
    $invoices = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J2E Healthcare Trading - Invoice Management</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="../images/J2E logo favicon.png" type="image/x-icon">
    <style>
        :root {
            --primary-color: #db2c24;
            --secondary-color: #ff914d;
            --text-color: #333;
            --border-color: #E0E0E0;
            --background-color: #e7e6e6;
            --nav-text-color: #db2c24;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            background-color: var(--background-color);
            color: var(--dark-gray);
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
        }

        .nav-left,
        .nav-right {
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

        .invoice-management {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-bottom: 2px solid var(--primary-color);
        }

        .header h1 {
            color: var(--primary-color);
            margin: 0;
            margin-bottom: 20px;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            margin-bottom: 20px;
        }

        .header-actions a {
            text-decoration: none;
        }

        /* Button Styles */
        .btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn:hover {
            font-weight: bold;
            background: var(--secondary-color);
        }

        .btn-outline {
            background: white;
            color: var(--text-color);
            border: 1px solid var(--border-color);
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-outline:hover {
            background-color: grey;
            color: white;
            border: none;
        }

        .filters-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .filters-card h2 {
            margin-top: 0;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .filters {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-group label {
            font-size: 0.9rem;
            color: #666;
        }

        .filter-group input,
        .filter-group select {
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            min-width: 200px;
        }

        .search-bar {
            margin-bottom: 2rem;
            max-width: 30%;
        }

        .search-input {
            display: flex;
            align-items: center;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 0.5rem 1rem;
            gap: 0.5rem;
        }

        .search-input input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 1rem;
        }

        .search-input i {
            color: #666;
        }

        .invoice-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background-color: white;
            font-weight: 600;
            color: #666;
        }

        td {
            color: var(--text-color);
        }

        .status-draft {
            background-color: #FFB74D;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .status-partial {
            background-color: #42A5F5;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .status-paid {
            background-color: #66BB6A;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .operations {
            display: flex;
            gap: 0.5rem;
        }

        .icon-button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.25rem;
            color: #666;
        }

        .icon-button:hover {
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
    <div class="invoice-management">
        <!-- Page Header -->
        <div class="header">
            <h1>Invoice Management</h1>
            <div class="header-actions">
                <a href="../invoice/invoice_add.php"><button class="btn"><i class="material-icons">add</i> Add New
                        Invoice</button></a>
                <a href="../user/user_add.php"><button class="btn"><i class="material-icons">person_add</i> Add New Client</button></a>
                <button class="btn-outline"><i class="fas fa-download"></i> Export List</button>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filters-card">
            <h2>All Invoices</h2>
            <div class="filters">
                <div class="filter-group">
                    <label for="begin-date">Begin Date</label>
                    <input type="date" id="begin-date" placeholder="00/00/0000">
                </div>
                <div class="filter-group">
                    <label for="end-date">End Date</label>
                    <input type="date" id="end-date" placeholder="00/00/0000">
                </div>
                <div class="filter-group">
                    <label for="status">Status</label>
                    <select id="status">
                        <option value="any">Any</option>
                        <option value="draft">Draft</option>
                        <option value="partial">Partial Payment</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="client">Client</label>
                    <select id="client">
                        <option value="any">Any</option>
                        <option value="client1">Client 1</option>
                        <option value="client2">Client 2</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="search-bar">
            <div class="search-input">
                <input type="text" placeholder="Search client or invoice id...">
            </div>
        </div>

       <!-- Invoices Table -->
<div class="invoice-table">
    <table>
        <thead>
            <tr>
                <th>SELECT</th>
                <th>INVOICE NO.</th>
                <th>CLIENT NAME</th>
                <th>DATE</th>
                <th>DUE DATE</th>
                <th>TOTAL DUE</th>
                <th>BALANCE</th>
                <th>STATUS</th>
                <th>OPERATION</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invoices as $invoice): 
                // Calculate due date (30 days after invoice date)
                $dueDate = date('Y-m-d', strtotime($invoice['invoice_date'] . ' + 30 days'));
                // Calculate balance based on status
                $balance = ($invoice['status'] === 'paid') ? 0 : $invoice['total_amount'];
                if ($invoice['status'] === 'partial') {
                    $balance = $invoice['total_amount'] * 0.5; // Assuming half paid for partial
                }
            ?>
            <tr>
                <td><input type="checkbox"></td>
                <td><?php echo htmlspecialchars($invoice['invoice_number']); ?></td>
                <td><?php echo htmlspecialchars($invoice['customer_name']); ?> <i class="material-icons">link</i></td>
                <td><?php echo date('m/d/Y', strtotime($invoice['invoice_date'])); ?></td>
                <td><?php echo date('m/d/Y', strtotime($dueDate)); ?></td>
                <td>₱<?php echo number_format($invoice['total_amount'], 2); ?></td>
                <td>₱<?php echo number_format($balance, 2); ?></td>
                <td>
                    <?php if ($invoice['status'] === 'paid'): ?>
                        <span class="status-paid">Paid</span>
                    <?php elseif ($invoice['status'] === 'partial'): ?>
                        <span class="status-partial">Partial Payment</span>
                    <?php else: ?>
                        <span class="status-draft"><?php echo ucfirst($invoice['status']); ?></span>
                    <?php endif; ?>
                </td>
                <td class="operations">
                    <a href="editInvoice.php?id=<?php echo $invoice['invoice_id']; ?>">
                        <button class="icon-button"><i class="material-icons">edit</i></button>
                    </a>
                    <a href="deleteInvoice.php?id=<?php echo $invoice['invoice_id']; ?>" onclick="return confirm('Are you sure you want to delete this invoice?');">
                        <button class="icon-button"><i class="material-icons">delete</i></button>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

    <script>
        function closeAllDropdowns(exceptElement) {
            if (!exceptElement) {
                document.getElementById('userDropdown').classList.remove('show');
            }
        }

        document.getElementById('menuDropdown').addEventListener('click', function (e) {
            e.stopPropagation();
            const userDropdown = document.getElementById('userDropdown');
            const wasOpen = userDropdown.classList.contains('show');

            closeAllDropdowns();
            if (!wasOpen) {
                userDropdown.classList.add('show');
            }
        });

        document.addEventListener('click', function (e) {
            closeAllDropdowns(e.target);
        });

        // Logout functionality
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