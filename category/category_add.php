<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <link rel="icon" href="/images/J2E logo favicon.png" type="image/x-icon">
    <title>Add Category - J2E Healthcare</title>
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
            font-family: 'Montserrat', sans-serif; /* Add this */
            font-weight: 400; /* Add this */
            line-height: 1.4; /* Add this */
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
                <img src="/images/J2E-logo2.png" alt="J2E Healthcare Trading Logo">
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
                <div class="/menu/user-dropdown" id="userDropdown">
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
                        <li class="category-item">
                            <div class="category-name">Supplies</div>
                            <div class="category-description">
                                Medical supplies including consumables, disposables, and daily use items.
                            </div>
                            <div class="category-stats">
                                <span>15 items</span>
                                <span>Last updated: 2023-11-15</span>
                            </div>
                        </li>
                        
                        <li class="category-item">
                            <div class="category-name">Equipment</div>
                            <div class="category-description">
                                Medical equipment and devices for diagnostics and treatment.
                            </div>
                            <div class="category-stats">
                                <span>28 items</span>
                                <span>Last updated: 2023-11-18</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Add New Category Section -->
            <div class="new-category">
                <h2 class="section-header">Add New Category</h2>
                
                <form id="categoryForm">
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
                                <input type="text" placeholder="Search items...">
                            </div>
                        </div>
                        
                        <div class="items-container">
                            <div class="items-grid">
                                <div class="item-header item-name-col">ITEM NAME</div>
                                <div class="item-header item-sku-col">SKU</div>
                                
                                <div class="items-list">
                                    <div class="item-row">
                                        <div class="item-name item-name-col">
                                            <input type="checkbox" id="item1" name="items[]" value="1">
                                            <label for="item1">Hydrocollator Moist Tank</label>
                                        </div>
                                        <div class="item-sku item-sku-col">0001</div>
                                    </div>
                                    
                                    <div class="item-row">
                                        <div class="item-name item-name-col">
                                            <input type="checkbox" id="item2" name="items[]" value="2">
                                            <label for="item2">Plinths (Wooden or Metal)</label>
                                        </div>
                                        <div class="item-sku item-sku-col">0002</div>
                                    </div>
                                    
                                    <div class="item-row">
                                        <div class="item-name item-name-col">
                                            <input type="checkbox" id="item3" name="items[]" value="3">
                                            <label for="item3">Paraffin Wax Bath</label>
                                        </div>
                                        <div class="item-sku item-sku-col">0003</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="btn-container">
                        <button type="submit" class="btn-save" id="saveCategory">
                            <i class="fas fa-save"></i> Save New Category
                        </button>
                    </div>
                </form>
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
        const searchInput = document.querySelector('.search-box input');
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

        // Form submission
        document.getElementById('categoryForm').onsubmit = function (e) {
            e.preventDefault();
            
            // Get form values
            const categoryName = document.getElementById('categoryName').value.trim();
            const description = document.getElementById('categoryDescription').value.trim();
            
            // Validate required fields
            if (!categoryName) {
                alert('Please enter a category name');
                return;
            }
            
            // Show success toast
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.innerHTML = `
                <i class="fas fa-check-circle"></i>
                <span>New category "${categoryName}" created successfully!</span>
            `;
            document.body.appendChild(toast);
            
            // Remove toast after animation
            setTimeout(() => {
                toast.remove();
            }, 3000);
            
            // Reset form
            this.reset();
            
            // Uncheck all items
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = false;
            });
            
            // Clear search
            searchInput.value = '';
            document.querySelectorAll('.item-row').forEach(row => {
                row.style.display = 'contents';
            });
        };
    </script>
</body>
</html>
