<?php
require_once '../config/db.php';
require_once '../config/session_check.php';
require_once '../config/deleteUser.php';

// Get username from session
$username = $_SESSION['username'];

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $result = deleteUser($pdo, $delete_id, $_SESSION['user_id']);
    
    if ($result['success']) {
        $_SESSION['success_message'] = $result['message'];
    } else {
        $_SESSION['error_message'] = $result['message'];
    }
    
    header("Location: user_management.php");
    exit();
}

// Fetch all users with their role names
$users_query = "SELECT u.user_id, u.username, u.email, u.password_hash, r.role_name, 
                u.status_id, u.last_login, u.created_at 
                FROM users u
                JOIN roles r ON u.role_id = r.role_id
                ORDER BY u.created_at DESC";
$users_result = $pdo->query($users_query);
$users = $users_result->fetchAll(PDO::FETCH_ASSOC);

// Count users by status
$count_query = "SELECT 
                COUNT(*) as total_users,
                SUM(CASE WHEN status_id = 1 THEN 1 ELSE 0 END) as active_users
                FROM users";
$count_result = $pdo->query($count_query);
$counts = $count_result->fetch(PDO::FETCH_ASSOC);

// Display success/error messages
$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
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
        .alert-message {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
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

        .role-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.9rem;
            display: inline-block;
        }

        .role-owner {
            background-color: #9C27B0;
            color: white;
        }

        .role-admin {
            background-color: #2196F3;
            color: white;
        }

        .role-employee {
            background-color: #FF9800;
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
    /* Modal styles */
        #deleteModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .modal-cancel {
            padding: 0.5rem 1rem;
            background: #e0e0e0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .modal-cancel:hover {
            background: #d0d0d0;
        }
        
        .modal-confirm {
            padding: 0.5rem 1rem;
            background: #f44336;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        
        .modal-confirm:hover {
            background: #d32f2f;
        }
        
        /* Alert messages */
        .alert-message {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
                <li><a href="../user/user_management.php" class="active"><i class="fas fa-solid fa-user"></i> User</a></li>
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
            <div class="header-actions">
                <a href="../user/user_add.php" class="btn"><i class="material-icons">add</i> Add New User</a>
            </div>
        </div>

        <!-- Display success/error messages -->
        <?php if ($success_message): ?>
            <div class="alert-message alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="alert-message alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Action Bar -->
        <div class="action-bar">
            <div class="action-controls">
                <div class="btn-group">
                    <button type="button" class="btn-role active" data-role="All">All</button>
                    <button type="button" class="btn-role" data-role="Owner">Owners</button>
                    <button type="button" class="btn-role" data-role="Admin">Admins</button>
                    <button type="button" class="btn-role" data-role="Employee">Employees</button>
                </div>
                <button class="btn-outline"><i class="fas fa-download"></i> Export List</button>
            </div>
            <div class="count-display">
                <span>Total Users: <span id="countTotal" class="count-value"><?php echo $counts['total_users']; ?></span></span>
                <span>Active Users: <span id="countActive" class="count-value"><?php echo $counts['active_users']; ?></span></span>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="search-bar">
            <div class="search-input">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search username or email..." id="userSearchInput">
            </div>
        </div>

        <!-- User Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>PHOTO</th>
                        <th>USERNAME</th>
                        <th>EMAIL</th>
                        <th>ROLE</th>
                        <th>STATUS</th>
                        <th>LAST LOGIN</th>
                        <th>OPERATION</th>
                    </tr>
                </thead>
                <tbody id="employeeTable">
                    <?php foreach ($users as $user): 
                        $status_class = $user['status_id'] == 1 ? 'status-active' : 'status-inactive';
                        $status_text = $user['status_id'] == 1 ? 'Active' : 'Inactive';
                        $role_class = 'role-' . strtolower($user['role_name']);
                    ?>
                    <tr data-role="<?php echo htmlspecialchars($user['role_name']); ?>" data-status="<?php echo $status_text; ?>">
                        <td><input type="checkbox" class="user-checkbox" data-user-id="<?php echo $user['user_id']; ?>"></td>
                        <td><img src="../images/sample user profile pic.jpg" class="avatar" alt="User" /></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><span class="role-badge <?php echo $role_class; ?>"><?php echo htmlspecialchars($user['role_name']); ?></span></td>
                        <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                        <td><?php echo $user['last_login'] ? date('M d, Y H:i', strtotime($user['last_login'])) : 'Never'; ?></td>
                        <td class="operations">
                            <a href="user_edit.php?id=<?php echo $user['user_id']; ?>" class="icon-button" title="Edit"><i class="material-icons">edit</i></a>
                            <a href="#" onclick="return confirmDelete(<?php echo $user['user_id']; ?>)" class="icon-button" title="Delete"><i class="material-icons">delete</i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="deleteModal">
        <div class="modal-content">
            <h3>Confirm Delete</h3>
            <p>Are you sure you want to delete this user? This action cannot be undone.</p>
            <div class="modal-actions">
                <button class="modal-cancel" onclick="closeModal()">Cancel</button>
                <a id="confirmDeleteBtn" href="#" class="modal-confirm">Delete</a>
            </div>
        </div>
    </div>

    <script>
        // Delete confirmation function
        function confirmDelete(userId) {
            const modal = document.getElementById('deleteModal');
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            
            // Set the delete link
            confirmBtn.href = `user_management.php?delete_id=${userId}`;
            
            // Show the modal
            modal.style.display = 'flex';
            
            // Prevent default anchor behavior
            return false;
        }
        
        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Initialize counts
        function updateCounts() {
            const visibleRows = Array.from(document.querySelectorAll('#employeeTable tr')).filter(row => row.style.display !== 'none');
            const activeRows = visibleRows.filter(row => row.getAttribute('data-status') === 'Active');
            document.getElementById('countTotal').textContent = visibleRows.length;
            document.getElementById('countActive').textContent = activeRows.length;
        }

        // Filter functionality
        document.querySelectorAll('.btn-role').forEach(button => {
            button.addEventListener('click', function() {
                const role = this.getAttribute('data-role');
                
                // Update active button
                document.querySelectorAll('.btn-role').forEach(btn => {
                    btn.classList.remove('active');
                });
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

        // Search functionality
        document.getElementById('userSearchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            document.querySelectorAll('#employeeTable tr').forEach(row => {
                const username = row.cells[2].textContent.toLowerCase();
                const email = row.cells[3].textContent.toLowerCase();
                if (username.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            updateCounts();
        });

        // Select all checkbox
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // User dropdown functionality
        document.getElementById('menuDropdown').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('userDropdown').classList.toggle('show');
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function() {
            document.getElementById('userDropdown').classList.remove('show');
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
        document.addEventListener('DOMContentLoaded', function() {
            updateCounts();
        });
    </script>
</body>
</html>