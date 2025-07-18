<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../authenticate/login.php');
    exit;
}

// Get username from session
$username = $_SESSION['username'];

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="../images/J2E logo favicon.png" type="image/x-icon">
    <title>J2E Healthcare Trading - Home</title>
    <style>
        :root {
            --main-color: #db2c24;
            --secondary-color: #ff914d;
            --light-gray: #e7e6e6;
            --dark-gray: #333;
            --medium-gray: #777;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            background-color: var(--light-gray);
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
            color: var(--main-color);
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
            color: var(--main-color);
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
            color: var(--main-color);
            font-size: 0.8rem;
        }

        .user-dropdown a:hover {
            font-weight: bold;
        }

        .user-dropdown a i {
            width: 20px;
            text-align: center;
        }

        .main-content {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .video-background {
            position: relative;
            width: 100vw;
            height: 60vh;
            overflow: hidden;
        }

        .video-background video {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: 0;
            object-fit: cover;
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .stats-fullwidth {
            width: 100vw;
            position: relative;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            background-color: var(--main-color);
            padding: 40px 0;
            color: white;
            margin-top: -25px;
            margin-bottom: 30px;
        }

        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .stat-card {
            border: 2px solid white;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
        }

        .stat-card h3 {
            margin-top: 0;
            color: white;
            font-size: 1rem;
        }

        .stat-card .value {
            font-size: 2.3rem;
            font-weight: bold;
            margin: 15px 0;
            color: white;
        }

        .stat-card .change {
            color: white;
            font-size: 0.8rem;
        }

        .three-column-layout {
            display: grid;
            grid-template-columns: 2fr 1.2fr 0.8fr;
            gap: 20px;
            margin-bottom: 20px;
            align-items: start;
        }

        .card-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            min-height: 400px;
            overflow: hidden;
        }

        .sales-report,
        .stock-status,
        .quick-actions {
            display: flex;
            flex-direction: column;
        }

        .sales-report {
            overflow: visible;
        }

        .top-selling {
            width: 100vw;
            position: relative;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            background-color: var(--main-color);
            padding: 40px 0;
            color: white;
        }

        .top-selling-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-header-top {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-header-top h2 {
            margin: 10px;
            color: white;
            font-size: 2.5rem;
        }

        .top-products-row {
            display: flex;
            gap: 15px;
            justify-content: space-between;
            padding-bottom: 10px;
        }

        .product-card {
            width: 225px;
            padding: 15px;
            border-radius: 8px;
            background-color: var(--light-gray);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .product-image-container {
            width: 200px;
            height: 200px;
            border-radius: 8px;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            border: 1px solid #eee;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background-color: #f5f5f5;
        }

        .product-info {
            text-align: left;
            width: 100%;
        }

        .product-name {
            margin: 0 0 5px 0;
            color: var(--main-color);
            font-size: 1rem;
            font-weight: 600;
        }

        .product-category {
            margin: 0 0 10px 0;
            color: var(--medium-gray);
            font-size: 0.8rem;
        }

        .sales-count {
            font-weight: bold;
            color: var(--dark-gray);
            font-size: 0.9rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-header h2 {
            margin: 0;
            color: var(--main-color);
            font-size: 1.2rem;
            border-bottom: 2px solid var(--main-color);
            padding-bottom: 0.5em;
            display: inline-block;
        }

        .filter-dropdown {
            position: relative;
        }

        .filter-btn {
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            color: var(--main-color);
        }

        .filter-btn:hover {
            font-weight: bold;
        }

        .filter-btn i {
            font-size: 0.5rem;
        }

        .filter-options {
            position: absolute;
            right: 0;
            top: 40px;
            background-color: white;
            min-width: 150px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            border-radius: 5px;
            padding: 10px 0;
            z-index: 1000;
            display: none;
        }

        .filter-options.show {
            display: block;
        }

        .filter-options a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            text-decoration: none;
            color: var(--main-color);
            font-size: 0.8rem;
            position: relative;
        }

        .filter-options a:hover .searchable-dropdown {
            display: block;
        }

        .filter-options a:hover {
            font-weight: bold;
        }

        .searchable-dropdown {
            display: none;
            position: absolute;
            left: 100%;
            top: 0;
            width: 300px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            padding: 10px;
            margin-left: 5px;
        }

        .search-input {
            width: 90%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid var(--medium-gray);
            border-radius: 4px;
        }

        .search-results {
            max-height: 200px;
            overflow-y: auto;
        }

        .search-result-item {
            padding: 8px;
            cursor: pointer;
            position: relative;
            color: var(--medium-gray);
            font-weight: 400;
        }

        .search-result-item:hover {
            font-weight: bold;
            color: var(--main-color);
        }

        .chart-placeholder {
            height: 280px;
            border: 1px solid var(--medium-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--medium-gray);
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .stock-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--light-gray);
        }

        .stock-item:last-child {
            border-bottom: none;
        }

        .stock-name {
            font-weight: bold;
        }

        .stock-amount {
            color: var(--medium-gray);
            font-size: 0.8rem;
        }

        .stock-amount.low {
            color: var(--main-color);
            font-weight: bold;
        }

        .action-btns {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .action-btns a {
            text-decoration: none;
        }

        .action-btn {
            background-color: var(--light-gray);
            min-height: 50px;
            color: var(--dark-gray);
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
        }

        .action-btn:hover {
            background-color: var(--main-color);
            font-weight: bold;
            color: white;
        }

        .top-product {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid var(--light-gray);
        }

        .top-product:last-child {
            border-bottom: none;
        }

        .product-info h4 {
            margin: 0 0 5px 0;
            color: var(--main-color);
            font-size: 1rem;
        }

        .product-info p {
            margin: 0;
            color: var(--medium-gray);
            font-size: 0.8rem;
        }

        .product-sales {
            font-weight: bold;
            white-space: nowrap;
            margin-left: 15px;
        }

/*menu things*/
        /* Settings and Help Modal Styles */
	.menu-btns {
    background-color: var(--main-color);
    min-height: 40px;
    border: none;
    padding: 12px;
    border-radius: 5px;
    cursor: pointer;
    text-align: center;
    transition: background-color 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    color: white;
    font-weight: bold;
    white-space: nowrap;
}

.menu-btns:hover {
    background-color: var(--secondary-color);
}

.menu-btns i {
    font-size: 14px;
}

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
            height: 350px;
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
            <a href="#" class="logo">
                <img src="../images/J2E-logo2.png" alt="J2E Logo">
            </a>
        </div>

        <div class="nav-center">
            <ul class="nav-menu">
                <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="../inventory/inventory.php"><i class="fas fa-boxes"></i> Inventory</a></li>
                <li><a href="../category/category_edit.php"><i class="fas fa-tags"></i> Category</a></li>
                <?php if ($_SESSION['role_name'] === 'owner' || $_SESSION['role_name'] === 'admin'): ?>
                    <li><a href="../user/user_management.php"><i class="fas fa-solid fa-user"></i> User</a></li>
                <?php endif; ?>
                <li><a href="../invoice/invoice.php"><i class="fas fa-file-invoice"></i> Invoice</a></li>
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

    <div class="video-background">
        <video autoplay muted loop playsinline poster="../images/video-fallback.png">
            <source src="../images/background.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="video-overlay"></div>
    </div>


    <div class="main-content">
        <div class="stats-fullwidth">
            <div class="stats-container">
                <div class="stat-card">
                    <h3>Total Products</h3>
                    <div class="value">150</div>
                    <div class="change">12% from last week</div>
                </div>
                <div class="stat-card">
                    <h3>Today's Sales</h3>
                    <div class="value">5,000</div>
                    <div class="change">In P</div>
                </div>
                <div class="stat-card">
                    <h3>Pending Invoices</h3>
                    <div class="value">8</div>
                    <div class="change">draft</div>
                </div>
            </div>
        </div>

        <div class="three-column-layout">
            <div class="card-container sales-report">
                <div class="section-header">
                    <h2>Sales Report</h2>
                    <div style="display: flex; flex-direction: row;">
                        <div class="filter-dropdown">
                            <button class="filter-btn" id="salesFilterBtn">
                                Daily <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="filter-options" id="salesFilterOptions">
                                <a href="#"><i class="fas fa-calendar-day"></i> Daily</a>
                                <a href="#"><i class="fas fa-calendar-week"></i> Weekly</a>
                                <a href="#"><i class="fas fa-calendar-alt"></i> Monthly</a>
                            </div>
                        </div>
                        <div class="filter-dropdown">
                            <button class="filter-btn" id="categoryFilterBtn">
                                By Category <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="filter-options" id="categoryFilterOptions">
                                <a class="filter-option-item">
                                    <i class="fas fa-tags"></i> By Category
                                    <div class="searchable-dropdown">
                                        <input type="text" class="search-input" placeholder="Search categories...">
                                        <div class="search-results">
                                            <div class="search-result-item">Physical Therapy</div>
                                            <div class="search-result-item">Occupational Therapy</div>
                                            <div class="search-result-item">Diagnostic Equipment</div>
                                            <div class="search-result-item">Consumables</div>

                                        </div>
                                    </div>
                                </a>
                                <a class="filter-option-item">
                                    <i class="fas fa-box"></i> By Product
                                    <div class="searchable-dropdown">
                                        <input type="text" class="search-input" placeholder="Search products...">
                                        <div class="search-results">
                                            <div class="search-result-item">ECG Paper</div>
                                            <div class="search-result-item">Treadmill Belts</div>
                                            <div class="search-result-item">Ultrasound Gel</div>
                                            <div class="search-result-item">Hydrocollator Packheater</div>

                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chart-placeholder">
                    [Sales Chart Placeholder]
                </div>

                <p id="period-report" style="text-align: center;"><strong>Sales in July</strong></p>
            </div>

            <div class="card-container stock-status">
                <div class="section-header">
                    <h2>Stock Status</h2>
                </div>
                <div class="stock-item">
                    <span class="stock-name">ECG Paper</span>
                    <span class="stock-amount low">0 left</span>
                </div>
                <div class="stock-item">
                    <span class="stock-name">Treadmill Belts</span>
                    <span class="stock-amount">2 left</span>
                </div>
                <div class="stock-item">
                    <span class="stock-name">Ultrasound Gel</span>
                    <span class="stock-amount">12/100 left</span>
                </div>
                <div class="stock-item">
                    <span class="stock-name">Hydrocollator Packheater</span>
                    <span class="stock-amount">10/50 left</span>
                </div>
            </div>

            <div class="card-container quick-actions">
                <div class="section-header">
                    <h2>Quick Actions</h2>
                </div>
                <div class="action-btns">
                    <a href="../inventory/product_add.php"><button class="action-btn"><i class="fas fa-plus"></i> Add New Product</button></a>
                    <a href="../category/category_add.php"><button class="action-btn"><i class="fas fa-tag"></i> Add New Category</button></a>
                    <a href="../invoice/invoice_add.php"><button class="action-btn"><i class="fas fa-file-invoice"></i> Place New Invoice</button></a>
                </div>
            </div>
        </div>

        <div class="top-selling">
            <div class="top-selling-container">
                <div class="section-header-top">
                    <h2>Top Selling This Week</h2>
                </div>
                <div class="top-products-row">
                    <div class="product-card">
                        <div class="product-image-container">
                            <img src="" alt="Hydrocollator Moist Tank" class="product-image">
                        </div>
                        <div class="product-info">
                            <h4 class="product-name">Hydrocollator Moist Tank</h4>
                            <p class="product-category">Equipment</p>
                            <div class="sales-count">20 units sold</div>
                        </div>
                    </div>

                    <div class="product-card">
                        <div class="product-image-container">
                            <img src="" alt="Therapeutic Ultrasound Machine" class="product-image">
                        </div>
                        <div class="product-info">
                            <h4 class="product-name">Therapeutic Ultrasound Machine</h4>
                            <p class="product-category">Equipment</p>
                            <div class="sales-count">32 units sold</div>
                        </div>
                    </div>

                    <div class="product-card">
                        <div class="product-image-container">
                            <img src="" alt="Pediatric Gait Treadmill" class="product-image">
                        </div>
                        <div class="product-info">
                            <h4 class="product-name">Pediatric Gait Treadmill</h4>
                            <p class="product-category">Equipment</p>
                            <div class="sales-count">8 units sold</div>
                        </div>
                    </div>

                    <div class="product-card">
                        <div class="product-image-container">
                            <img src="" alt="Hydrocollator Packheater" class="product-image">
                        </div>
                        <div class="product-info">
                            <h4 class="product-name">Hydrocollator Packheater</h4>
                            <p class="product-category">Equipment</p>
                            <div class="sales-count">25 units sold</div>
                        </div>
                    </div>
                </div>
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
        // Dropdown functionality
        function closeAllDropdowns(exceptElement) {
            if (!exceptElement || !exceptElement.closest('.searchable-dropdown')) {
                document.getElementById('salesFilterOptions')?.classList.remove('show');
                document.getElementById('categoryFilterOptions')?.classList.remove('show');
                document.getElementById('userDropdown')?.classList.remove('show');
            }
        }

        // Filter dropdown toggles
        document.getElementById('salesFilterBtn')?.addEventListener('click', function (e) {
            e.stopPropagation();
            const salesFilter = document.getElementById('salesFilterOptions');
            const wasOpen = salesFilter?.classList.contains('show');

            closeAllDropdowns();
            if (salesFilter && !wasOpen) {
                salesFilter.classList.add('show');
            }
        });

        document.getElementById('categoryFilterBtn')?.addEventListener('click', function (e) {
            e.stopPropagation();
            const categoryFilter = document.getElementById('categoryFilterOptions');
            const wasOpen = categoryFilter?.classList.contains('show');

            closeAllDropdowns();
            if (categoryFilter && !wasOpen) {
                categoryFilter.classList.add('show');
            }
        });

        // User dropdown toggle
        document.getElementById('menuDropdown')?.addEventListener('click', function (e) {
            e.stopPropagation();
            const userDropdown = document.getElementById('userDropdown');
            const wasOpen = userDropdown?.classList.contains('show');

            closeAllDropdowns();
            if (userDropdown && !wasOpen) {
                userDropdown.classList.add('show');
            }
        });

        // Close dropdowns when clicking elsewhere
        document.addEventListener('click', function (e) {
            closeAllDropdowns(e.target);
        });

        // Prevent dropdown close when clicking inside dropdown
        document.querySelectorAll('.searchable-dropdown').forEach(dropdown => {
            dropdown.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        });

        // Search functionality for dropdowns
        document.querySelectorAll('.search-input').forEach(input => {
            input.addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase();
                const resultsContainer = this.nextElementSibling;
                const items = resultsContainer.querySelectorAll('.search-result-item');

                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(searchTerm) ? 'block' : 'none';
                });
            });
        });

        // Enhanced logout functionality
        document.getElementById('logoutBtn')?.addEventListener('click', function (e) {
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

        // Modal functionality (added from reference)
        document.addEventListener('DOMContentLoaded', function () {
            // Video background handling (your existing code)
            const video = document.querySelector('.video-background video');
            if (video) {
                video.play().catch(e => {
                    console.log("Video autoplay prevented, showing fallback");
                    video.style.display = 'none';
                    document.querySelector('.video-background').style.backgroundImage = 'url(../images/video-fallback.jpg)';
                });
            }

            // Modal handling (from reference)
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
                if (trigger && modals[key]) {
                    trigger.addEventListener('click', function (e) {
                        e.preventDefault();
                        closeAllDropdowns();
                        closeAllModals();
                        modals[key].style.display = 'block';
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
