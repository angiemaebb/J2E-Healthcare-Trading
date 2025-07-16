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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <link rel="icon" href="/images/J2E logo favicon.png" type="image/x-icon">
    <title>Edit Product - J2E Healthcare</title>
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
                <li><a href="../inventory/inventory.php" class="active"><i class="fas fa-boxes"></i> Inventory</a></li>
                <li><a href="../category/category_edit.php"><i class="fas fa-tags"></i> Category</a></li>
                <li><a href="../user/user_management.php"><i class="fas fa-solid fa-user"></i> User</a></li>
                <li><a href="../invoice/invoice.php"><i class="fas fa-file-invoice"></i> Invoice</a></li>
            </ul>
        </div>

        <div class="nav-right">
            <div class="user-info">
                <img src="../images/sample user profile pic.jpg" alt="User Profile" class="user-profile">
                <span class="username">Username</span>
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
        <h1 class="page-title">Edit Product</h1>
        </div>

        <form id="productForm" class="form-container" method="POST" action="update_product.php" enctype="multipart/form-data">
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
                <option value="equipment">Equipment</option>
                <option value="supply">Supply</option>
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
            <div class="preview-container" id="previewContainer" style="display:none;">
            <img id="imagePreview" src="" alt="Preview">
            <div class="remove-image" id="removeImage">Remove Image</div>
            </div>
        </div>

        <div class="btn-container">
            <button type="submit" class="btn-save" id="saveProduct">
            <i class="fas fa-save"></i> Update Product
            </button>
        </div>
        </form>
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
    // Enhanced Dropdown functionality
    function closeAllDropdowns(exceptElement) {
        if (!exceptElement) {
            document.getElementById('userDropdown').classList.remove('show');
        }
    }

    document.getElementById('menuDropdown').addEventListener('click', function(e) {
        e.stopPropagation();
        const userDropdown = document.getElementById('userDropdown');
        const wasOpen = userDropdown.classList.contains('show');

        closeAllDropdowns();
        if (!wasOpen) {
            userDropdown.classList.add('show');
        }
    });

    document.addEventListener('click', function(e) {
        closeAllDropdowns(e.target);
    });

    // Enhanced logout functionality (keeping your fetch version)
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

    // Modal functionality (from reference)
    document.addEventListener('DOMContentLoaded', function() {
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
                trigger.addEventListener('click', function(e) {
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

        window.addEventListener('click', function(e) {
            Object.values(modals).forEach(modal => {
                if (modal && e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    });

    // Your existing image upload functionality (unchanged)
    const fileInput = document.getElementById('fileInput');
    const imageUpload = document.getElementById('imageUpload');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const removeImage = document.getElementById('removeImage');

    if (imageUpload && fileInput) {
        imageUpload.onclick = () => fileInput.click();
        fileInput.onchange = function() {
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
    }

    if (removeImage) {
        removeImage.onclick = () => {
            fileInput.value = '';
            imagePreview.src = '';
            previewContainer.style.display = 'none';
            imageUpload.style.display = 'flex';
        };
    }
</script>
    </body>
</html>
