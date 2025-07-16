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
    <title>Add Product - J2E Healthcare</title>
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

        .form-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
        }

        .form-section {
        flex: 3;
        min-width: 300px;
        }

        .form-group {
        display: flex;
        align-items: center;
        margin-bottom: 35px;
        gap: 20px;
        }

        .form-group label {
        width: 130px;
        font-weight: 600;
        font-size: 15px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s;
            font-family: 'Montserrat';
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
        border-color: var(--main-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(219, 44, 36, 0.2);
        }

        .required {
        color: var(--main-color);
        }

        .image-upload-section {
        flex: 1;
        min-width: 300px;
        }

        .image-upload-container {
        border: 2px dashed var(--medium-gray);
        border-radius: 10px;
        padding: 20px;
        height: 200px;
        text-align: center;
        background-color: #fafafa;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        }

        .image-upload-container span {
        font-weight: 500;
        font-size: 14px;
        color: var(--medium-gray);
        }

        .image-upload-container:hover {
        border-color: var(--main-color);
        background-color: rgba(219, 44, 36, 0.05);
        }

        .upload-icon {
        font-size: 30px;
        color: var(--medium-gray);
        margin-bottom: 10px;
        }

        .upload-text {
        color: var(--medium-gray);
        margin-bottom: 5px;
        font-weight: 600;
        font-size: 14px;
        }

        .upload-subtext {
        color: var(--medium-gray);
        font-size: 12px;
        margin-bottom: 10px;
        }

        .upload-button {
        background-color: var(--main-color);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 30px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: 0.3s;
        }

        .upload-button:hover {
        background-color: #db2c24;
        }

        .preview-container {
        display: none;
        margin-top: 25px;
        text-align: center;
        }

        .preview-container img {
        max-width: 100%;
        max-height: 270px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .remove-image {
        margin-top: 15px;
        color: var(--main-color);
        cursor: pointer;
        font-weight: 600;
        font-size: 16px;
        }

        .btn-container {
        width: 100%;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-top: -5px;
        margin-bottom: 0;
        }

        .btn-save {
        background-color: var(--main-color);
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 6px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        }

        .btn-save:hover {
        background-color: var(--dark-gray);
        }

        @media (max-width: 768px) {
            .form-container {
            flex-direction: column;
            }

            .form-group {
            flex-direction: column;
            align-items: stretch;
            }

            .form-group label {
            width: 100%;
            }

            .btn-container {
            justify-content: center;
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
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 28px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 2000;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: fadeInOut 3s forwards;
        }

        .toast.success {
            background: #4CAF50;
            color: white;
        }

        .toast.error {
            background: #f44336;
            color: white;
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
                <li><a href="../inventory/inventory.php" class="active"><i class="fas fa-boxes"></i> Inventory</a></li>
                <li><a href="../category/category_edit.php"><i class="fas fa-tags"></i> Category</a></li>
                <li><a href="../user/user_management.php"><i class="fas fa-user"></i> User</a></li>
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
                    <a href="../menu/settings.html"><i class="fas fa-cog"></i> Settings</a>
                    <a href="../menu/help.html"><i class="fas fa-question-circle"></i> Help</a>
                    <a href="#" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="content">
        <div class="page-header">
            <h1 class="page-title">Add Product</h1>
        </div>

        <form id="productForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" class="form-container">
            <div class="form-section">
                <div class="form-group">
                    <label for="productName">Product Name <span class="required">*</span></label>
                    <input type="text" id="productName" name="productName" required>
                </div>

                <div class="form-group">
                    <label for="sku">SKU <span class="required">*</span></label>
                    <input type="text" id="sku" name="sku" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"></textarea>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity <span class="required">*</span></label>
                    <input type="number" id="quantity" name="quantity" min="0" required>
                </div>

                <div class="form-group">
                    <label for="unitPrice">Unit Price (₱) <span class="required">*</span></label>
                    <input type="number" id="unitPrice" name="unitPrice" min="0" step="0.01" required>
                </div>
                
                <div class="form-group">
                    <label for="category">Category <span class="required">*</span></label>
                    <select id="category" name="category" required>
                        <option value="">Select a category</option>
                        <?php
                        try {
                            $stmt = $pdo->query("SELECT id, name FROM categories ORDER BY name");
                            while ($row = $stmt->fetch()) {
                                echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>';
                            }
                        } catch (PDOException $e) {
                            // Log error and show generic message
                            error_log("Error fetching categories: " . $e->getMessage());
                            echo '<option value="">Error loading categories</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="image-upload-section">
                <div class="image-upload-container" id="imageUpload" tabindex="0" role="button">
                    <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="upload-text">Upload Product Image</div>
                    <div class="upload-subtext">PNG, JPG, or GIF up to 5MB</div>
                    <button type="button" class="upload-button">Choose File</button>
                    <input type="file" id="fileInput" name="productImage" accept="image/*" style="display: none;">
                </div>
                <div class="preview-container" id="previewContainer">
                    <img id="imagePreview" src="" alt="Preview">
                    <div class="remove-image" id="removeImage">Remove Image</div>
                </div>
            </div>

            <div class="btn-container">
                <button type="submit" class="btn-save" id="saveProduct">
                    <i class="fas fa-save"></i> Save New Product
                </button>
            </div>
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            // Validate required fields
            $required_fields = ['productName', 'sku', 'quantity', 'unitPrice', 'category'];
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("All required fields must be filled out");
                }
            }

            // Validate numeric fields
            if (!is_numeric($_POST['quantity']) || $_POST['quantity'] < 0) {
                throw new Exception("Invalid quantity value");
            }
            if (!is_numeric($_POST['unitPrice']) || $_POST['unitPrice'] < 0) {
                throw new Exception("Invalid unit price value");
            }

            // Handle image upload if present
            $image_path = null;
            if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $file_type = $_FILES['productImage']['type'];
                
                if (!in_array($file_type, $allowed_types)) {
                    throw new Exception("Invalid file type. Only JPG, PNG, and GIF are allowed.");
                }
                
                $max_size = 5 * 1024 * 1024; // 5MB
                if ($_FILES['productImage']['size'] > $max_size) {
                    throw new Exception("File size too large. Maximum size is 5MB.");
                }
                
                $upload_dir = '../uploads/products/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_extension = pathinfo($_FILES['productImage']['name'], PATHINFO_EXTENSION);
                $file_name = uniqid() . '.' . $file_extension;
                $target_path = $upload_dir . $file_name;
                
                if (!move_uploaded_file($_FILES['productImage']['tmp_name'], $target_path)) {
                    throw new Exception("Failed to upload image");
                }
                
                $image_path = 'uploads/products/' . $file_name;
            }

            // Begin transaction
            $pdo->beginTransaction();

            // Insert product
            $stmt = $pdo->prepare("INSERT INTO products (name, sku, description, quantity, unit_price, category_id, image_path, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            
            $stmt->execute([
                $_POST['productName'],
                $_POST['sku'],
                $_POST['description'] ?? null,
                $_POST['quantity'],
                $_POST['unitPrice'],
                $_POST['category'],
                $image_path,
                $_SESSION['user_id']
            ]);

            // Commit transaction
            $pdo->commit();

            // Show success message
            echo '<script>
                showToast("Product added successfully!", "success");
                setTimeout(() => {
                    window.location.href = "inventory.php";
                }, 2000);
            </script>';

        } catch (Exception $e) {
            // Rollback transaction on error
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }

            // Delete uploaded image if exists
            if (isset($target_path) && file_exists($target_path)) {
                unlink($target_path);
            }

            // Show error message
            echo '<script>
                showToast("Error: ' . addslashes($e->getMessage()) . '", "error");
            </script>';
        }
    }
    ?>

    <script>
        // Dropdown functionality
        const userDropdown = document.getElementById('userDropdown');
        document.getElementById('menuDropdown').onclick = (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
        };
        
        document.addEventListener('click', () => userDropdown.classList.remove('show'));

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

        // Image Upload
        const fileInput = document.getElementById('fileInput');
        const imageUpload = document.getElementById('imageUpload');
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const removeImage = document.getElementById('removeImage');

        imageUpload.onclick = () => fileInput.click();
        fileInput.onchange = function () {
            if (this.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    imagePreview.src = e.target.result;
                    previewContainer.style.display = 'block';
                    imageUpload.style.display = 'none';
                };
                reader.readAsDataURL(this.files[0]);
            }
        };
        removeImage.onclick = () => {
            fileInput.value = '';
            previewContainer.style.display = 'none';
            imageUpload.style.display = 'flex';
        };

        // Toast notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                <span>${message}</span>
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>
</body>
</html>
