<?php
require_once '../config/db.php';
require_once '../config/session_check.php';

// Get username from session
$username = $_SESSION['username'];

// Check if category ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid category ID";
    header("Location: viewCategories.php");
    exit();
}

$category_id = $_GET['id'];

// Fetch category details
$category_query = "SELECT category_name, description FROM categories WHERE category_id = ?";
$stmt = $pdo->prepare($category_query);
$stmt->execute([$category_id]);
$category = $stmt->fetch();

if (!$category) {
    $_SESSION['error'] = "Category not found";
    header("Location: viewCategories.php");
    exit();
}

// Fetch products in this category with their inventory details
$products_query = "SELECT 
    p.product_id,
    p.product_name,
    p.sku,
    p.description,
    p.image_path,
    ps.status_name,
    u.unit_name,
    pi.quantity,
    pi.unit_price
FROM products p
LEFT JOIN product_inventory pi ON p.product_id = pi.product_id
LEFT JOIN product_status ps ON p.status_id = ps.status_id
LEFT JOIN units u ON p.unit_id = u.unit_id
WHERE p.category_id = ?
ORDER BY p.product_name";

$stmt = $pdo->prepare($products_query);
$stmt->execute([$category_id]);
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <link rel="icon" href="../images/J2E logo favicon.png" type="image/x-icon">
    <title><?php echo htmlspecialchars($category['category_name']); ?> Products - J2E Healthcare</title>
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

        .category-description {
            margin-top: 20px;
            color: var(--medium-gray);
            font-size: 16px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .product-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid var(--light-gray);
        }

        .product-details {
            padding: 20px;
        }

        .product-name {
            font-size: 18px;
            font-weight: 600;
            color: var(--main-color);
            margin-bottom: 10px;
        }

        .product-info {
            font-size: 14px;
            color: var(--medium-gray);
            margin-bottom: 5px;
        }

        .product-status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }

        .status-in-stock {
            background-color: #4CAF50;
            color: white;
        }

        .status-low-stock {
            background-color: #FFC107;
            color: #333;
        }

        .status-out-of-stock {
            background-color: #F44336;
            color: white;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: none;
            border: none;
            color: var(--main-color);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 20px;
            text-decoration: none;
        }

        .back-button:hover {
            color: var(--secondary-color);
        }

        .no-products {
            grid-column: 1 / -1;
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .no-products h3 {
            color: var(--main-color);
            margin-bottom: 10px;
        }

        .btn-add-product {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: var(--main-color);
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 15px;
        }

        .btn-add-product:hover {
            background-color: var(--secondary-color);
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

            .products-grid {
                grid-template-columns: 1fr;
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
            </div>
        </div>
    </nav>

    <div class="content">
        <a href="viewCategories.php" class="back-button">
            <i class="fas fa-arrow-left"></i> Back to Categories
        </a>

        <div class="page-header">
            <h1 class="page-title"><?php echo htmlspecialchars($category['category_name']); ?></h1>
            <div class="category-description">
                <?php echo htmlspecialchars($category['description'] ?: 'No description provided'); ?>
            </div>
        </div>

        <div class="products-grid">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img 
                            src="<?php echo htmlspecialchars($product['image_path'] ? '../' . $product['image_path'] : '../images/items/default-product.png'); ?>" 
                            alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                            class="product-image"
                        >
                        <div class="product-details">
                            <h3 class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                            <p class="product-info"><strong>SKU:</strong> <?php echo htmlspecialchars($product['sku']); ?></p>
                            <p class="product-info"><strong>Unit:</strong> <?php echo htmlspecialchars($product['unit_name']); ?></p>
                            <p class="product-info"><strong>Price:</strong> ₱<?php echo number_format($product['unit_price'], 2); ?></p>
                            <p class="product-info"><strong>Quantity:</strong> <?php echo $product['quantity']; ?></p>
                            <?php
                            $status_class = '';
                            if ($product['quantity'] > 10) {
                                $status_class = 'status-in-stock';
                            } elseif ($product['quantity'] > 0) {
                                $status_class = 'status-low-stock';
                            } else {
                                $status_class = 'status-out-of-stock';
                            }
                            ?>
                            <span class="product-status <?php echo $status_class; ?>">
                                <?php echo htmlspecialchars($product['status_name']); ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-products">
                    <h3>No products found in this category</h3>
                    <p>Add products to this category to see them listed here.</p>
                    <a href="../inventory/product_add.php" class="btn-add-product">
                        <i class="fas fa-plus"></i> Add New Product
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
