<?php
require_once '../config/db.php';
require_once '../config/session_check.php';
requireRoles(['owner', 'admin', 'employee']);
// Get username from session
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J2E Healthcare Trading - Edit Invoice</title>
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
            --medium-gray: #777;
        }

        html {
            margin: 0;
            padding: 0;
            width: 100vw;
            overflow-x: hidden;
        }

        body {
            font-family: 'Montserrat', 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            background-color: var(--background-color);
            color: var(--text-color);
        }

        * {
            font-family: 'Montserrat', sans-serif;
        }

        h1 {
            color: var(--primary-color);
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
            border-bottom: 2px solid var(--primary-color);
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

        /* Main Content Styles */
        .invoice-management,
        .edit-invoice-container {
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

        .page-title,
        .header h1 {
            color: var(--primary-color);
            margin: 0 0 1.5rem 0;
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
        .btn,
        .btn-back,
        .btn-save,
        .menu-btns {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.2s;
            max-width: 250px;
            margin-bottom: 20px;
        }

        .btn:hover,
        .btn-back:hover,
        .btn-save:hover,
        .menu-btns:hover {
            background: var(--secondary-color);
            font-weight: bold;
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

        /* Form Styles */
        .invoice-form,
        .filters-card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .form-header {
            display: grid;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .form-group,
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label,
        .filter-group label {
            font-weight: 500;
            color: var(--text-color);
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group select,
        .filter-group input,
        .filter-group select {
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

        .filters {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        /* Date Input Styles */
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
        }

        .date-input input[type="date"]::-webkit-calendar-picker-indicator {
            display: none;
            -webkit-appearance: none;
        }

        .date-input input[type="date"]::-webkit-inner-spin-button,
        .date-input input[type="date"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Amount Input Styles */
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

        /* Invoice Items Table */
        .invoice-items {
            margin-top: 2rem;
            overflow-x: auto;
        }

        .invoice-items table,
        .invoice-table table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        .invoice-items th,
        .invoice-table th {
            background-color: #f5f5f5;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #666;
            border-bottom: 2px solid var(--border-color);
        }

        .invoice-items td,
        .invoice-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-color);
        }

        .invoice-items input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
        }

        /* Status Badges */
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

        /* Operations Buttons */
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

        /* Form Actions */
        .form-actions {
            margin-top: 2rem;
            display: flex;
            justify-content: flex-end;
        }

        /* Search Bar */
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

        /* Modal Styles */
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

        /* Settings and Help Modal Content */
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
        .setting-input,
        .form-input,
        .form-textarea {
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

        .form-textarea {
            resize: vertical;
            width: 100%;
        }

        /* Footer Styles */
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

        /* Responsive Styles */
        @media (max-width: 768px) {

            .edit-invoice-container,
            .invoice-management {
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

            .search-bar {
                max-width: 100%;
            }
        }

        @media (max-width: 480px) {
            .company-name {
                display: none;
            }

            .nav-right .username {
                display: none;
            }

            .footer-right {
                flex-direction: column;
                gap: 20px;
            }

            .footer-left {
                margin-right: 0;
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
                <li><a href="../category/category_edit.php"><i class="fas fa-tags"></i> Category</a></li>
                <li><a href="../user/user_management.php"><i class="fas fa-solid fa-user"></i> User</a></li>
                <li><a href="../invoice/invoice.php" class="active"><i class="fas fa-file-invoice"></i> Invoice</a></li>
            </ul>
        </div>

        <div class="nav-right">
            <div class="user-info">
                <img src="../images/sample user profile pic.jpg" alt="User Profile" class="user-profile">
                <span class="username"><?php echo $username; ?></span>
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
    <div class="edit-invoice-container">
        <h1 class="page-title">Edit Invoice</h1>

        <!-- Invoice Management Button -->
        <div class="management-button">
            <a href="invoice.html" class="btn-back">
                <i class="material-icons">keyboard_backspace</i>
                Invoice Management
            </a>
        </div>

        <!-- Invoice Form -->
        <div class="invoice-form">
            <!-- Invoice Header Information -->
            <div class="form-header">
                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label>Invoice No.</label>
                        <input type="text" value="1" style="background: white;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label>Client Name</label>
                        <input type="text" value="Client 1" style="background: white;">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Date</label>
                        <div class="date-input">
                            <div class="calendar-icon">
                                <i class="material-icons">calendar_today</i>
                            </div>
                            <input type="date" placeholder="00/00/0000" style="background: white;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Total Due</label>
                        <div class="amount-input">
                            <div class="currency-icon">₱</div>
                            <input type="text" placeholder="00.00" style="background: white;">
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
                            <input type="date" placeholder="00/00/0000" style="background: white;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Balance</label>
                        <div class="amount-input">
                            <div class="currency-icon">₱</div>
                            <input type="text" placeholder="00.00" style="background: white;">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label>Status</label>
                        <input type="text" value="Paid" style="background: white;">
                    </div>
                </div>
            </div>

            <!-- Invoice Items Table -->
            <div class="invoice-items">
                <table>
                    <thead>
                        <tr>
                            <th>QUANTITY</th>
                            <th>UNIT</th>
                            <th>ARTICLES</th>
                            <th>UNIT PRICE</th>
                            <th>AMOUNT</th>
                            <th>TOTAL SALES</th>
                            <th>LESS: SC/PWD-DISCOUNT</th>
                            <th>TOTAL DUE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2</td>
                            <td>pcs.</td>
                            <td>NS704 Hinged Knee</td>
                            <td>₱1,900.00</td>
                            <td>₱3,800.00</td>
                            <td>₱3,800.00</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>pcs.</td>
                            <td>NB504 Neoprene Back Support</td>
                            <td>₱1,900.00</td>
                            <td>₱5,700.00</td>
                            <td>₱11,525.00</td>
                            <td>₱00.00</td>
                            <td>₱11,525.00</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>pcs.</td>
                            <td>WH301 Wrist Brace</td>
                            <td>₱675.00</td>
                            <td>₱2,025.00</td>
                            <td>₱2,025.00</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Save Button -->
            <div class="form-actions">
                <button class="btn-save">
                    <i class="material-icons">save</i>
                    Save Edit
                </button>
            </div>
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
                            <li><a href="/home/dashboard.html">Home</a></li>
                            <li><a href="/inventory/inventory.html">Inventory</a></li>
                            <li><a href="/category/category.html">Category</a></li>
                            <li><a href="/user/user-management.html">User</a></li>
                            <li><a href="/invoice/invoice.html">Invoice</a></li>
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
        // Calendar icon functionality
        document.addEventListener('DOMContentLoaded', function () {
            const calendarIcons = document.querySelectorAll('.calendar-icon');
            calendarIcons.forEach(icon => {
                icon.addEventListener('click', function (e) {
                    e.preventDefault();
                    const dateInput = this.parentElement.querySelector('input[type="date"]');
                    if (dateInput && typeof dateInput.showPicker === 'function') {
                        dateInput.showPicker();
                    } else {
                        dateInput.focus();
                    }
                });
            });

            // Dropdown and modal functionality
            function closeAllDropdowns(exceptElement) {
                if (!exceptElement) {
                    document.getElementById('userDropdown').classList.remove('show');
                }
            }

            const menuDropdown = document.getElementById('menuDropdown');
            if (menuDropdown) {
                menuDropdown.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const userDropdown = document.getElementById('userDropdown');
                    const wasOpen = userDropdown.classList.contains('show');

                    closeAllDropdowns();
                    if (!wasOpen) {
                        userDropdown.classList.add('show');
                    }
                });
            }

            document.addEventListener('click', function (e) {
                closeAllDropdowns(e.target);
            });

            const logoutBtn = document.getElementById('logoutBtn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    alert('Logging out....');
                    window.location.href = '/authenticate/login.html';
                });
            }

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
                if (trigger) {
                    trigger.addEventListener('click', function (e) {
                        e.preventDefault();
                        closeAllDropdowns();
                        closeAllModals();
                        if (modals[key]) modals[key].style.display = 'block';
                    });
                }
            });

            closeButtons.forEach(button => {
                button.addEventListener('click', closeAllModals);
            });

            window.addEventListener('click', function (e) {
                Object.values(modals).forEach(modal => {
                    if (modal && e.target === modal) {
                        modal.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>

</html>
