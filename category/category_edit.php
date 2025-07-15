<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <link rel="icon" href="/images/J2E logo favicon.png" type="image/x-icon">
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
                <li><a href="/home/dashboard.html"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="/inventory/inventory.html"><i class="fas fa-boxes"></i> Inventory</a></li>
                <li><a href="/category/category_add.php" class="active"><i class="fas fa-tags"></i> Category</a></li>
                <li><a href="/user/user-management.html"><i class="fas fa-user"></i> User</a></li>
                <li><a href="/invoice/invoice.html"><i class="fas fa-file-invoice"></i> Invoice</a></li>
            </ul>
        </div>

        <div class="nav-right">
            <div class="user-info">
                <img src="https://via.placeholder.com/30x30?text=U" alt="User Profile" class="user-profile">
                <span class="username">Admin User</span>
                <button class="hamburger" id="menuDropdown">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="user-dropdown" id="userDropdown">
                    <a href="/menu/settings.html"><i class="fas fa-cog"></i> Settings</a>
                    <a href="/menu/help.html"><i class="fas fa-question-circle"></i> Help</a>
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

    <script>
        // Dropdown functionality
        const userDropdown = document.getElementById('userDropdown');
        document.getElementById('menuDropdown').onclick = (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
        };
        
        document.addEventListener('click', () => userDropdown.classList.remove('show'));
        
        document.getElementById('logoutBtn').onclick = () => {
            alert('Logging out...');
            location.href = '/authenticate/login.html';
        };

        // Search functionality
        const searchInput = document.getElementById('itemSearch');
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const items = document.querySelectorAll('.item-name label');
            
            items.forEach(item => {
                const itemText = item.textContent.toLowerCase();
                const itemRow = item.closest('.item-row');
                if (itemText.includes(searchTerm)) {
                    itemRow.style.display = 'contents';
                } else {
                    itemRow.style.display = 'none';
                }
            });
        });

        // Toast notification
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

        // Form submission
        document.getElementById('categoryForm').onsubmit = function (e) {
            e.preventDefault();
            
            // Get form values
            const categoryName = document.getElementById('categoryName').value.trim();
            
            // Validate required fields
            if (!categoryName) {
                showToast('Please enter a category name', 'error');
                return;
            }
            
            // Submit form programmatically
            this.submit();
        };
    </script>
</body>
</html>
