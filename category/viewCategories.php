<?php
require_once '../config/db.php';
require_once '../config/session_check.php';

// Get username from session
$username = $_SESSION['username'];

// Fetch all categories with product counts
$query = "SELECT c.*, COUNT(p.product_id) as product_count 
          FROM categories c
          LEFT JOIN products p ON c.category_id = p.category_id
          GROUP BY c.category_id
          ORDER BY c.category_name";
$stmt = $pdo->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll();

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../authenticate/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <link rel="icon" href="../images/J2E logo favicon.png" type="image/x-icon">
    <title>View Categories - J2E Healthcare</title>
    <style>
        :root {
            --main-color: #db2c24;
            --secondary-color: #ff914d;
            --light-gray: #e7e6e6;
            --dark-gray: #333;
            --medium-gray: #777;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f8f9fa;
            color: var(--dark-gray);
            line-height: 1.6;
            min-height: 100vh;
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
            gap: 20px;
        }

        .nav-menu a {
            text-decoration: none;
            color: var(--main-color);
            padding: 8px 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-menu a.active {
            font-weight: bold;
            background-color: rgba(219, 44, 36, 0.1);
            border-radius: 4px;
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
            color: var(--main-color);
            white-space: nowrap;
        }

        .hamburger {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 5px 10px;
            color: var(--main-color);
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
            color: var(--main-color);
            font-size: 0.8rem;
        }

        .content {
            width: 100%;
            max-width: 2000px;
            margin: 40px auto;
            padding: 0 40px;
        }

        .page-header {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            color: var(--main-color);
            font-size: 36px;
            font-weight: 700;
            position: relative;
            padding-bottom: 15px;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            max-width: 100%;
            height: 4px;
            background: var(--main-color);
            border-radius: 2px;
        }

        .btn-add {
            background-color: var(--main-color);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        .btn-add:hover {
            background-color: #c0251e;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .category-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s;
        }

        .category-card:hover {
            transform: translateY(-5px);
        }

        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--light-gray);
        }

        .category-name {
            font-size: 20px;
            font-weight: 600;
            color: var(--main-color);
            margin: 0;
        }

        .product-count {
            background-color: var(--light-gray);
            color: var(--dark-gray);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .category-description {
            color: var(--medium-gray);
            margin-bottom: 15px;
            font-size: 14px;
            line-height: 1.5;
        }

        .category-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: var(--medium-gray);
        }

        .category-actions {
            display: flex;
            gap: 10px;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-edit {
            background-color: var(--main-color);
            color: white;
            border: 1px solid var(--main-color);
        }

        .btn-edit:hover {
            background-color: #c0251e;
            border-color: #c0251e;
        }

        .btn-view {
            background-color: white;
            color: var(--main-color);
            border: 1px solid var(--main-color);
        }

        .btn-view:hover {
            background-color: rgba(219, 44, 36, 0.1);
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4CAF50;
            color: white;
            padding: 16px 28px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 2000;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: fadeInOut 3s forwards;
        }

        .toast.error {
            background: #f44336;
        }

        .toast i {
            font-size: 22px;
        }

        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(-20px); }
            10% { opacity: 1; transform: translateY(0); }
            90% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-20px); }
        }

        @media (max-width: 1200px) {
            .categories-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .nav-center {
                display: none;
            }

            .username {
                display: none;
            }

            .content {
                margin: 20px auto;
                padding: 0 15px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .page-title {
                font-size: 28px;
            }

            .categories-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="toast" style="display: block;">
            <i class="fas fa-check-circle"></i>
            <span><?php echo htmlspecialchars($_SESSION['success']); ?></span>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="toast error" style="display: block;">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo htmlspecialchars($_SESSION['error']); ?></span>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

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
                <li><a href="../category/viewCategories.php" class="active"><i class="fas fa-tags"></i> Category</a></li>
                <li><a href="../user/user_management.php"><i class="fas fa-user"></i> User</a></li>
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
                    <a href="../menu/settings.php"><i class="fas fa-cog"></i> Settings</a>
                    <a href="../menu/help.php"><i class="fas fa-question-circle"></i> Help</a>
                    <form method="POST" style="margin: 0;">
                        <button type="submit" name="logout" style="background: none; border: none; width: 100%; text-align: left; padding: 10px 15px; color: var(--main-color); font-size: 0.8rem; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="content">
        <div class="page-header">
            <h1 class="page-title">Categories</h1>
            <a href="category_add.php" class="btn-add">
                <i class="fas fa-plus"></i> Add New Category
            </a>
        </div>

        <div class="categories-grid">
            <?php if (count($categories) > 0): ?>
                <?php foreach ($categories as $category): ?>
                    <div class="category-card">
                        <div class="category-header">
                            <h3 class="category-name"><?php echo htmlspecialchars($category['category_name']); ?></h3>
                            <span class="product-count"><?php echo $category['product_count']; ?> items</span>
                        </div>
                        <div class="category-description">
                            <?php echo htmlspecialchars($category['description'] ?: 'No description provided'); ?>
                        </div>
                        <div class="category-footer">
                            <span>ID: <?php echo $category['category_id']; ?></span>
                            <div class="category-actions">
                                <a href="category_edit.php?id=<?php echo $category['category_id']; ?>" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="categoryList.php?id=<?php echo $category['category_id']; ?>" class="btn-action btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="category-card" style="grid-column: 1 / -1; text-align: center;">
                    <h3>No categories found</h3>
                    <p>You haven't created any categories yet.</p>
                    <a href="category_add.php" class="btn-add" style="display: inline-flex; margin-top: 15px;">
                        <i class="fas fa-plus"></i> Create Your First Category
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Dropdown functionality
        const userDropdown = document.getElementById('userDropdown');
        document.getElementById('menuDropdown').onclick = (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
        };
        
        document.addEventListener('click', () => userDropdown.classList.remove('show'));

        // Toast auto-hide
        const toasts = document.querySelectorAll('.toast');
        toasts.forEach(toast => {
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        });
    </script>
</body>
</html>