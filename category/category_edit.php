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
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <link rel="icon" href="../images/J2E logo favicon.png" type="image/x-icon">
    <title>Edit Category - J2E Healthcare</title>
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
            z-index: 1001;
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

        .layout-container {
            display: flex;
            gap: 30px;
        }

        .existing-categories {
            flex: 1;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            min-width: 350px;
        }

        .new-category {
            flex: 2;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .section-header {
            color: var(--main-color);
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--main-color);
        }

        .categories-box {
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            padding: 20px;
        }

        .categories-list {
            list-style: none;
        }

        .category-item {
            padding: 15px 0;
            border-bottom: 1px solid var(--light-gray);
        }

        .category-item:last-child {
            border-bottom: none;
        }

        .category-name {
            font-size: 16px;
            color: var(--dark-gray);
            margin-bottom: 8px;
            transition: all 0.2s ease;
        }

        .category-item:hover .category-name {
            font-weight: 600;
            color: var(--main-color);
        }

        .category-description {
            font-size: 14px;
            color: var(--medium-gray);
            margin-bottom: 10px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 400;
            line-height: 1.4;
        }

        .category-stats {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: var(--medium-gray);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s;
            font-family: 'Montserrat';
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: var(--main-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(219, 44, 36, 0.2);
        }

        .required {
            color: var(--main-color);
        }

        .items-section {
            margin-top: 30px;
        }

        .items-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .section-label {
            color: var(--main-color);
            font-size: 18px;
            font-weight: 600;
        }

        .search-box {
            position: relative;
            width: 300px;
        }

        .search-box input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid var(--light-gray);
            background: var(--light-gray);
            border-radius: 6px;
            font-size: 14px;
            color: var(--dark-gray);
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--medium-gray);
        }

        .items-container {
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .items-grid {
            display: grid;
            grid-template-columns: 1fr 100px;
            gap: 10px;
        }

        .item-header {
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--light-gray);
            margin-bottom: 10px;
            color: var(--medium-gray);
        }

        .items-list {
            display: contents;
        }

        .item-name-col {
            grid-column: 1;
        }

        .item-sku-col {
            grid-column: 2;
            text-align: right;
        }

        .item-row {
            display: contents;
        }

        .item-name, .item-sku {
            padding: 12px 0;
            border-bottom: 1px solid var(--light-gray);
            transition: background-color 0.2s;
        }

        .item-name {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .item-sku {
            font-family: monospace;
            font-weight: 500;
            color: var(--medium-gray);
        }

        .item-row:hover .item-name,
        .item-row:hover .item-sku {
            background-color: rgba(219, 44, 36, 0.05);
        }

        .btn-container {
            width: 100%;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-top: 30px;
        }

        .btn-save {
            background-color: var(--main-color);
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background-color 0.3s;
        }

        .btn-save:hover {
            background-color: #c0251e;
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
            .layout-container {
                flex-direction: column;
            }
            
            .existing-categories, .new-category {
                width: 100%;
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

            .page-title {
                font-size: 28px;
            }

            .search-box {
                width: 100%;
            }
        }

        /*menu things*/
        /* Settings and Help Modal Styles */
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
            background-color: var(--main-color);
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
            color: var(--main-color);
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
            color: var(--main-color);
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
            color: var(--main-color);
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
            color: var(--main-color);
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
            color: var(--main-color);
        }
    </style>
</head>
<body>
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
                <li><a href="../category/category_add.php" class="active"><i class="fas fa-tags"></i> Category</a></li>
                <li><a href="../user/user_management.php"><i class="fas fa-user"></i> User</a></li>
                <li><a href="../invoice/invoice.php"><i class="fas fa-file-invoice"></i> Invoice</a></li>
            </ul>
        </div>

        <div class="nav-right">
            <div class="user-info">
                <img src="https://via.placeholder.com/30x30?text=U" alt="User Profile" class="user-profile">
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

    <div class="content">
        <div class="page-header">
            <h1 class="page-title">Category Management</h1>
        </div>

        <div class="layout-container">
            <!-- Existing Categories Section -->
            <div class="existing-categories">
                <h2 class="section-header">Existing Categories</h2>
                
                <div class="categories-box">
                    <ul class="categories-list">
                        <?php
                        // Database connection
                        $servername = "localhost";
                        $username = "username";
                        $password = "password";
                        $dbname = "inventory_db";
                        
                        try {
                            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                            // Fetch categories from database
                            $stmt = $conn->prepare("SELECT c.id, c.name, c.description, COUNT(i.id) as item_count, 
                                                   MAX(i.updated_at) as last_updated 
                                                   FROM categories c 
                                                   LEFT JOIN items i ON c.id = i.category_id 
                                                   GROUP BY c.id");
                            $stmt->execute();
                            
                            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            if (empty($categories)) {
                                echo '<li class="category-item">No categories found</li>';
                            } else {
                                foreach ($categories as $category) {
                                    echo '<li class="category-item">';
                                    echo '<div class="category-name">' . htmlspecialchars($category['name']) . '</div>';
                                    echo '<div class="category-description">' . 
                                         ($category['description'] ? htmlspecialchars($category['description']) : 'No description provided') . 
                                         '</div>';
                                    echo '<div class="category-stats">';
                                    echo '<span>' . $category['item_count'] . ' items</span>';
                                    echo '<span>Last updated: ' . ($category['last_updated'] ? date('Y-m-d', strtotime($category['last_updated'])) : 'N/A') . '</span>';
                                    echo '</div>';
                                    echo '</li>';
                                }
                            }
                        } catch(PDOException $e) {
                            echo '<li class="category-item">Error loading categories: ' . htmlspecialchars($e->getMessage()) . '</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <!-- Add New Category Section -->
            <div class="new-category">
                <h2 class="section-header">Edit Category</h2>
                
                <form id="categoryForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="categoryName">Category Name <span class="required">*</span></label>
                        <input type="text" id="categoryName" name="categoryName" placeholder="Enter category name" required>
                    </div>

                    <div class="form-group">
                        <label for="categoryDescription">Description</label>
                        <textarea id="categoryDescription" name="description" placeholder="Enter category description" rows="3"></textarea>
                    </div>

                    <div class="items-section">
                        <div class="items-header">
                            <div class="section-label">Items under this category</div>
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Search items..." id="itemSearch">
                            </div>
                        </div>
                        
                        <div class="items-container">
                            <div class="items-grid">
                                <div class="item-header item-name-col">ITEM NAME</div>
                                <div class="item-header item-sku-col">SKU</div>
                                
                                <div class="items-list">
                                    <?php
                                    try {
                                        if (!isset($conn)) {
                                            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                        }
                                        
                                        // Fetch items not assigned to any category
                                        $stmt = $conn->prepare("SELECT id, name, sku FROM items WHERE category_id IS NULL OR category_id = ''");
                                        $stmt->execute();
                                        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        
                                        if (empty($items)) {
                                            echo '<div class="item-row">';
                                            echo '<div class="item-name item-name-col" style="grid-column: 1 / -1;">No unassigned items found</div>';
                                            echo '</div>';
                                        } else {
                                            foreach ($items as $item) {
                                                echo '<div class="item-row">';
                                                echo '<div class="item-name item-name-col">';
                                                echo '<input type="checkbox" id="item' . $item['id'] . '" name="items[]" value="' . $item['id'] . '">';
                                                echo '<label for="item' . $item['id'] . '">' . htmlspecialchars($item['name']) . '</label>';
                                                echo '</div>';
                                                echo '<div class="item-sku item-sku-col">' . htmlspecialchars($item['sku']) . '</div>';
                                                echo '</div>';
                                            }
                                        }
                                    } catch(PDOException $e) {
                                        echo '<div class="item-row">';
                                        echo '<div class="item-name item-name-col" style="grid-column: 1 / -1;">Error loading items: ' . 
                                             htmlspecialchars($e->getMessage()) . '</div>';
                                        echo '</div>';
                                    }
                                    
                                    // Close connection if it was opened
                                    if (isset($conn)) {
                                        $conn = null;
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="btn-container">
                        <button type="submit" class="btn-save" id="saveCategory">
                            <i class="fas fa-save"></i> Update Category
                        </button>
                    </div>
                </form>
                
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    try {
                        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        
                        // Get form data
                        $categoryName = $_POST['categoryName'] ?? '';
                        $description = $_POST['description'] ?? '';
                        $selectedItems = $_POST['items'] ?? [];
                        
                        // Validate
                        if (empty($categoryName)) {
                            throw new Exception("Category name is required");
                        }
                        
                        // Begin transaction
                        $conn->beginTransaction();
                        
                        // Insert new category
                        $stmt = $conn->prepare("INSERT INTO categories (name, description) VALUES (:name, :description)");
                        $stmt->bindParam(':name', $categoryName);
                        $stmt->bindParam(':description', $description);
                        $stmt->execute();
                        
                        $categoryId = $conn->lastInsertId();
                        
                        // Update selected items with the new category ID
                        if (!empty($selectedItems)) {
                            $placeholders = implode(',', array_fill(0, count($selectedItems), '?'));
                            $stmt = $conn->prepare("UPDATE items SET category_id = ? WHERE id IN ($placeholders)");
                            $stmt->execute(array_merge([$categoryId], $selectedItems));
                        }
                        
                        // Commit transaction
                        $conn->commit();
                        
                        // Show success message
                        echo '<script>
                            showToast("New category \"' . addslashes($categoryName) . '\" created successfully!", "success");
                            setTimeout(() => {
                                window.location.href = "category_add.php";
                            }, 3000);
                        </script>';
                    } catch (Exception $e) {
                        // Rollback transaction on error
                        if (isset($conn) && $conn->inTransaction()) {
                            $conn->rollBack();
                        }
                        
                        // Show error message
                        echo '<script>
                            showToast("Error: ' . addslashes($e->getMessage()) . '", "error");
                        </script>';
                    } finally {
                        if (isset($conn)) {
                            $conn = null;
                        }
                    }
                }
                ?>
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
    // Dropdown and Modal functionality
    function closeAllDropdowns(exceptElement) {
        if (!exceptElement) {
            document.getElementById('userDropdown').classList.remove('show');
        }
    }

    // Menu dropdown toggle
    document.getElementById('menuDropdown').addEventListener('click', function(e) {
        e.stopPropagation();
        const userDropdown = document.getElementById('userDropdown');
        const wasOpen = userDropdown.classList.contains('show');

        closeAllDropdowns();
        if (!wasOpen) {
            userDropdown.classList.add('show');
        }
    });

    // Close dropdowns when clicking elsewhere
    document.addEventListener('click', function(e) {
        closeAllDropdowns(e.target);
    });

    // Logout functionality with fetch API
    document.getElementById('logoutBtn').addEventListener('click', function(e) {
        e.preventDefault();
        
        // Show loading toast
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.innerHTML = `
            <i class="fas fa-sign-out-alt"></i>
            <span>Logging out...</span>
        `;
        document.body.appendChild(toast);
        
        fetch('../authenticate/logout.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toast.innerHTML = `
                        <i class="fas fa-check-circle"></i>
                        <span>Logged out successfully!</span>
                    `;
                    setTimeout(() => {
                        window.location.href = '../authenticate/login.php';
                    }, 1500);
                } else {
                    toast.innerHTML = `
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Logout failed. Please try again.</span>
                    `;
                    setTimeout(() => toast.remove(), 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toast.innerHTML = `
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Error during logout. Please try again.</span>
                `;
                setTimeout(() => toast.remove(), 3000);
            });
    });

    // Search functionality
    const searchInput = document.querySelector('.search-input') || document.getElementById('itemSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr, .item-row');
            
            rows.forEach(row => {
                let itemName, description, sku;
                
                // Handle both table and grid layouts
                if (row.querySelector('.item-name')) {
                    itemName = row.querySelector('.item-name').textContent.toLowerCase();
                    description = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
                    sku = row.querySelector('td:nth-child(5)')?.textContent.toLowerCase() || 
                          row.querySelector('.item-sku')?.textContent.toLowerCase() || '';
                }
                
                if ((itemName && itemName.includes(searchTerm)) || 
                    (description && description.includes(searchTerm)) || 
                    (sku && sku.includes(searchTerm))) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Toast notification system
    function showToast(message, type = "success") {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <i class="fas ${type === "success" ? "fa-check-circle" : "fa-exclamation-circle"}></i>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    // Main DOM ready handler for modals
    document.addEventListener('DOMContentLoaded', function() {
        // Get all modal elements
        const modals = {
            privacy: document.getElementById('privacy-policy-modal'),
            terms: document.getElementById('terms-service-modal'),
            settings: document.getElementById('settings-modal'),
            help: document.getElementById('help-modal')
        };

        // Get all modal triggers
        const modalTriggers = {
            privacy: document.getElementById('privacy-policy-link'),
            terms: document.getElementById('terms-service-link'),
            settings: document.querySelector('.user-dropdown a[href="#settings"]'),
            help: document.querySelector('.user-dropdown a[href="#help"]')
        };

        // Close buttons
        const closeButtons = document.querySelectorAll('.close-modal');

        // Function to close all modals
        function closeAllModals() {
            Object.values(modals).forEach(modal => {
                if (modal) modal.style.display = 'none';
            });
        }

        // Set up event listeners for modal triggers
        Object.entries(modalTriggers).forEach(([key, trigger]) => {
            if (trigger) {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeAllDropdowns();
                    closeAllModals();
                    if (modals[key]) modals[key].style.display = 'block';
                });
            }
        });

        // Set up event listeners for close buttons
        closeButtons.forEach(button => {
            button.addEventListener('click', closeAllModals);
        });

        // Close modals when clicking outside
        window.addEventListener('click', function(e) {
            Object.values(modals).forEach(modal => {
                if (modal && e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });

        // Form submission for category management
        const categoryForm = document.getElementById('categoryForm');
        if (categoryForm) {
            categoryForm.onsubmit = function(e) {
                e.preventDefault();
                
                // Get form values
                const categoryName = document.getElementById('categoryName').value.trim();
                const description = document.getElementById('categoryDescription')?.value.trim() || '';
                
                // Validate required fields
                if (!categoryName) {
                    showToast('Please enter a category name', 'error');
                    return;
                }
                
                // Show success toast
                showToast(`New category "${categoryName}" created successfully!`);
                
                // Reset form
                this.reset();
                
                // Uncheck all items
                document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
                
                // Clear search if exists
                if (searchInput) {
                    searchInput.value = '';
                    document.querySelectorAll('.item-row, tbody tr').forEach(row => {
                        row.style.display = '';
                    });
                }
            };
        }
    });
</script>
</body>
</html>
