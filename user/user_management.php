<?php
require_once '../config/db.php';
require_once '../config/session_check.php';

// Get username from session
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>J2E Healthcare Trading - User Management</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="/images/J2E logo favicon.png" type="image/x-icon">
    <style>
        :root {
            --primary-color: #db2c24;
            --secondary-color: #ff914d;
            --light-gray: #e7e6e6;
            --dark-gray: #333;
            --medium-gray: #777;
            --header-gray: #f5f5f5;
            --border-color: #E0E0E0;
            --background-color: #e7e6e6;
            --nav-text-color: #db2c24;
        }

        a {
            text-decoration: none;
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
            box-sizing: border-box;
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

        /* Main content styles */
        .user-management {
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

        /* Button styles */
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
            color: var(--dark-gray);
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

        .btn-group {
            display: flex;
        }

        .btn-role {
            background-color: white;
            color: var(--dark-gray);
            border: 1px solid var(--border-color);
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-role:first-child {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .btn-role:last-child {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-left: none;
        }

        .btn-role.active {
            background-color: var(--primary-color);
            color: white;
            font-weight: bold;
            border-color: var(--primary-color);
        }

        .btn-role:hover:not(.active) {
            background-color: var(--light-gray);
        }

        /* Action bar styles */
        .action-bar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .action-controls {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .count-display {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            min-width: 110px;
        }

        .count-display span {
            font-size: 0.95em;
            color: #888;
            line-height: 1.1;
        }

        .count-display span:last-child {
            margin-top: 0.25rem;
        }

        .count-value {
            font-size: 1em;
            font-weight: 600;
            color: var(--primary-color);
        }

        /* Search bar styles */
        .search-bar {
            margin-bottom: 2rem;
            max-width: 30%;
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

        /* Table styles */
        .table-container {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1rem;
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
            vertical-align: middle;
        }

        .avatar {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.9rem;
            display: inline-block;
        }

        .status-active {
            background-color: #66BB6A;
            color: white;
        }

        .status-inactive {
            background-color: #666;
            color: white;
        }

        .operations {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            align-items: center;
            height: auto;
            min-height: 100px;
        }

        .icon-button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
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
                <li><a href="../category/category_edit.php"><i class="fas fa-tags"></i> Category</a></li>
                <li><a href="../user/user_management.php"class="active"><i class="fas fa-solid fa-user"></i> User</a></li>
                <li><a href="../invoice/invoice.php"><i class="fas fa-file-invoice"></i> Invoice</a></li>
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
                    <a href="#"><i class="fas fa-cog"></i> Settings</a>
                    <a href="#"><i class="fas fa-question-circle"></i> Help</a>
                    <a href="#" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="user-management">
        <!-- Page Header -->
        <div class="header">
            <h1>User Management</h1>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <div class="action-controls">
                <div class="btn-group">
                    <button type="button" class="btn-role" data-role="Employees">Employees</button>
                    <button type="button" class="btn-role" data-role="Admin">Admins</button>
                </div>
                <a href="/user/user_add.html"><button class="btn" id="addUserBtn" style="padding: 12px;"><i
                            class="fas fa-plus"></i> Add New User</button></a>
                <button class="btn-outline"><i class="fas fa-download"></i> Export List</button>
            </div>
            <div class="count-display">
                <span>Total Employees: <span id="countTotal" class="count-value">0</span></span>
                <span>Current Active: <span id="countActive" class="count-value">0</span></span>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="search-bar">
            <div class="search-input">
                <input type="text" placeholder="Search username..." id="userSearchInput">
            </div>
        </div>

        <!-- User Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>SELECT</th>
                        <th>PHOTO</th>
                        <th>USERNAME</th>
                        <th>EMAIL</th>
                        <th>M0BILE</th>
                        <th>ROLE</th>
                        <th>STATUS</th>
                        <th>OPERATION</th>
                    </tr>
                </thead>
                <tbody id="employeeTable">
                    <tr data-role="Admin" data-status="Active">
                        <td><input type="checkbox"></td>
                        <td><img src="../images/sample user profile pic.jpg" class="avatar" alt="Employee" /></td>
                        <td>Admin 1</td>
                        <td>admin1@j2e.ph</td>
                        <td>+63 917 123 4567</td>
                        <td>Admin</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td class="operations">
                            <a href="/user/user_edit.html" class="icon-button"><i class="material-icons">edit</i></a>
                            <button class="icon-button"><i class="material-icons">delete</i></button>
                        </td>
                    </tr>
                    <tr data-role="Employees" data-status="Active">
                        <td><input type="checkbox"></td>
                        <td><img src="../images/sample user profile pic.jpg" class="avatar" alt="Employee" /></td>
                        <td>Employee 2</td>
                        <td>employee2@j2e.ph</td>
                        <td>+63 943 154 8693</td>
                        <td>Employee</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td class="operations">
                            <a href="/user/user_edit.html" class="icon-button"><i class="material-icons">edit</i></a>
                            <button class="icon-button"><i class="material-icons">delete</i></button>
                        </td>
                    </tr>
                    <tr data-role="Employees" data-status="Inactive">
                        <td><input type="checkbox"></td>
                        <td><img src="../images/sample user profile pic.jpg" class="avatar" alt="Employee" /></td>
                        <td>Employee 3</td>
                        <td>employee3@j2e.ph</td>
                        <td>+63 952 135 9344</td>
                        <td>Employee</td>
                        <td><span class="status-badge status-inactive">Inactive</span></td>
                        <td class="operations">
                            <a href="/user/user_edit.html" class="icon-button"><i class="material-icons">edit</i></a>
                            <button class="icon-button"><i class="material-icons">delete</i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <script>
            // Initialize counts
            function updateCounts() {
                const visibleRows = Array.from(document.querySelectorAll('#employeeTable tr')).filter(row => row.style.display !== 'none');
                const activeRows = visibleRows.filter(row => row.getAttribute('data-status') === 'Active');
                document.getElementById('countTotal').textContent = visibleRows.length;
                document.getElementById('countActive').textContent = activeRows.length;
            }

            // Filter functionality
            document.querySelectorAll('[data-role]').forEach(button => {
                button.addEventListener('click', function () {
                    const role = this.getAttribute('data-role');

                    // Update active button - remove active class from all buttons first
                    document.querySelectorAll('[data-role]').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    // Then add active class to the clicked button
                    this.classList.add('active');

                    // Filter table rows
                    document.querySelectorAll('#employeeTable tr').forEach(row => {
                        if (role === 'All' || row.getAttribute('data-role') === role) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    updateCounts();
                });
            });

            // Show/hide admin-only buttons based on filter

            // User dropdown functionality
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

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function () {
                updateCounts();

                // Set Employees button as active by default
                const defaultActiveBtn = document.querySelector('[data-role="Employees"]');
                if (defaultActiveBtn) {
                    defaultActiveBtn.classList.add('active');
                }

                // Trigger the filter for the default active button
                defaultActiveBtn.click();
            });
        </script>
</body>

</html>