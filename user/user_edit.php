<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>J2E Healthcare Trading - Edit User</title>
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
        .btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            text-decoration: none;
        }
        .btn:hover {
            font-weight: bold;
            background: var(--secondary-color);
        }
        .add-user-form-wrapper {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 2rem;
            align-items: flex-start;
        }
        .image-upload-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            width: 180px;
        }
        .image-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 150px;
            height: 150px;
            border: 2px dashed #bbb;
            border-radius: 8px;
            cursor: pointer;
            background: #fafafa;
            margin-bottom: 1rem;
            position: relative;
        }
        .image-upload-label input[type="file"] {
            display: none;
        }
        .image-upload-label .material-icons {
            font-size: 48px;
            color: #888;
        }
        .image-upload-label span {
            color: #888;
            font-size: 1rem;
            margin-top: 0.5rem;
        }
        .image-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
            display: none;
        }
        .user-form-fields {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .form-group {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        .form-group label {
            width: 120px;
            font-weight: 500;
            color: var(--dark-gray);
        }
        .form-group input,
        .form-group select {
            flex: 1;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 1rem;
        }
        .mobile-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .country-code {
            background: #f0f0f0;
            border: 1px solid var(--border-color);
            border-radius: 4px 0 0 4px;
            padding: 0.75rem 0.75rem;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        .form-actions {
            margin-top: 2rem;
            display: flex;
            justify-content: flex-end;
        }
        .btn-save {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn-save:hover {
            background-color: var(--secondary-color);
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Navigation Header -->
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
                <li><a href="/category/category_edit.php"><i class="fas fa-tags"></i> Category</a></li>
                <li><a href="/user/user_management.html" class="active"><i class="fas fa-solid fa-user"></i> User</a></li>
                <li><a href="/invoice/invoice.html"><i class="fas fa-file-invoice"></i> Invoice</a></li>
            </ul>
        </div>
        <div class="nav-right">
            <div class="user-info">
                <img src="/images/sample user profile pic.jpg" alt="User Profile" class="user-profile">
                <span class="username">Username</span>
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
    <!-- Main Content -->
    <div class="user-management">
        <!-- Page Header -->
        <div class="header">
            <h1>Edit User</h1>
        </div>
        <div style="margin-bottom: 2rem;">
            <a href="/user/user_management.html" class="btn" style="background: var(--primary-color); color: white; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; font-weight: 500; padding: 0.5rem 1rem; border-radius: 4px;">
                <span class="material-icons">keyboard_backspace</span>
                User Management
            </a>
        </div>
        <div class="add-user-form-wrapper">
            <div class="image-upload-section">
                <label class="image-upload-label" for="userImage">
                    <img id="imagePreview" class="image-preview" src="#" alt="Preview">
                    <span class="material-icons">add_circle</span>
                    <span>Add Image</span>
                    <input type="file" id="userImage" name="userImage" accept="image/*">
                </label>
            </div>
            <form class="user-form-fields" id="editUserForm" autocomplete="off">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="Employee 1" required style="border: 2px solid #a259ff;">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="employee1@j2e.ph" required>
                </div>
                <div class="form-group">
                    <label for="mobile">Mobile</label>
                    <div class="mobile-group">
                        <span class="country-code"><span class="material-icons" style="font-size:18px;vertical-align:middle;">flag</span>+63</span>
                        <input type="tel" id="mobile" name="mobile" value="917 123 4567" pattern="[0-9 ]{11,12}" maxlength="12" required style="border-radius:0 4px 4px 0;">
                    </div>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="employee" selected>Employee</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-save" style="background-color: var(--primary-color); color: white;">
                        <i class="material-icons">save</i>
                        Save Edit
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
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
        document.getElementById('logoutBtn').addEventListener('click', function (e) {
            e.preventDefault();
            alert('Logging out....');
            window.location.href = '/authenticate/login.html';
        });
        // Image upload preview
        const userImageInput = document.getElementById('userImage');
        const imagePreview = document.getElementById('imagePreview');
        userImageInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (ev) {
                    imagePreview.src = ev.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '#';
                imagePreview.style.display = 'none';
            }
        });
        // Optional: Prevent form submission for demo
        document.getElementById('editUserForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('User edit saved! (Demo)');
        });
    </script>
</body>
</html> 