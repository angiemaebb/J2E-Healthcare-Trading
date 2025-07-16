<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J2E Healthcare Trading - Add New Invoice</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="/images/J2E logo favicon.png" type="image/x-icon">
    <style>
        :root {
            --primary-color: #db2c24;
            --secondary-color: #ff914d;
            --text-color: #333;
            --border-color: #E0E0E0;
            --background-color: #e7e6e6;
            /* Changed to light pink */
            --nav-text-color: #db2c24;
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

        h1 {
            color: var(--primary-color);
            margin-bottom: 20px;
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

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--background-color);
            color: var(--text-color);
        }

        .add-invoice-container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-title {
            color: var(--primary-color);
            margin: 0 0 1.5rem 0;
            border-bottom: 2px solid var(--primary-color);
        }

        .management-button {
            margin-bottom: 2rem;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: white;

            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            background-color: var(--primary-color);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .btn-back:hover {
            background-color: var(--secondary-color);
            font-weight: bold;
        }

        .invoice-form {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .form-header {
            display: grid;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            font-weight: 500;
            color: var(--text-color);
        }

        .form-group input,
        .form-group select {
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 1rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .date-input {
            position: relative;
            display: flex;
            align-items: center;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            overflow: hidden;
        }

        .calendar-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f0f0;
            padding: 0.75rem;
        }

        .calendar-icon i {
            color: #666;
            font-size: 20px;
        }

        .date-input input[type="date"] {
            border: none;
            padding: 0.75rem;
            width: 100%;
            -webkit-calendar-picker-indicator: none;
        }

        .date-input input[type="date"]::-webkit-calendar-picker-indicator {
            display: none;
            -webkit-appearance: none;
        }

        .date-input input[type="date"]::-webkit-inner-spin-button,
        .date-input input[type="date"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .header-amount {
            border: 1px solid var(--border-color);
            border-radius: 4px;
            overflow: hidden;
        }

        .header-amount input {
            border: none;
            padding: 0.75rem;
        }

        .amount-input {
            position: relative;
            display: flex;
            align-items: center;
        }

        .amount-input input {
            width: 100%;
            padding: 0.75rem 0.75rem 0.75rem 2rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
        }

        .currency-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f0f0;
            padding: 0.75rem;
            width: 20px;
        }

        .currency {
            color: #666;
            font-weight: 500;
        }

        .amount-input:not(.header-amount) .currency {
            position: absolute;
            left: 0.75rem;
        }

        .invoice-items {
            margin-top: 2rem;
            overflow-x: auto;
        }

        .invoice-items table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        .invoice-items th {
            background-color: #f5f5f5;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--text-color);
            border-bottom: 1px solid var(--border-color);
        }

        .invoice-items td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .invoice-items input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .invoice-items .amount-input {
            width: 100%;
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
                <img src="../images/J2E-logo2.png" alt="J2E Healthcare Trading Logo">
            </div>
        </div>

        <div class="nav-center">
            <ul class="nav-menu">
                <li><a href="/home/dashboard.html"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="/inventory/inventory.html"><i class="fas fa-boxes"></i> Inventory</a></li>
                <li><a href="/category/category_edit.php"><i class="fas fa-tags"></i> Category</a></li>
                <li><a href="/user/user_management.html"><i class="fas fa-solid fa-user"></i> User</a></li>
                <li><a href="/invoice/invoice.html" class="active"><i class="fas fa-file-invoice"></i> Invoice</a></li>
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
                    <a href="/menu/settings.html"><i class="fas fa-cog"></i> Settings</a>
                    <a href="/menu/help.html"><i class="fas fa-question-circle"></i> Help</a>
                    <a id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="add-invoice-container">
        <div class="page-title">
            <h1>Add New Invoice</h1>
        </div>

        <!-- Invoice Management Button -->
        <div class="management-button">
            <a href="/invoice/invoice.html" class="btn-back">
                <i class="material-icons">keyboard_backspace</i>
                Invoice Management
            </a>
        </div>

        <!-- Invoice Form -->
        <div class="invoice-form">
            <!-- Invoice Header Information -->
            <div class="form-header">
                <div class="form-group">
                    <label>Invoice No.</label>
                    <input type="text" placeholder="Enter invoice number">
                </div>
                <div class="form-group">
                    <label>Client Name</label>
                    <input type="text" placeholder="Enter client name">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Date</label>
                        <div class="date-input">
                            <div class="calendar-icon">
                                <i class="material-icons">calendar_today</i>
                            </div>
                            <input type="date" placeholder="00/00/0000">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Total Due</label>
                        <div class="amount-input header-amount">
                            <div class="currency-icon">
                                <span class="currency">₱</span>
                            </div>
                            <input type="text" placeholder="00.00">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Due Date</label>
                        <div class="date-input">
                            <div class="calendar-icon">
                                <i class="material-icons">calendar_today</i>
                            </div>
                            <input type="date" placeholder="00/00/0000">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Balance</label>
                        <div class="amount-input header-amount">
                            <div class="currency-icon">
                                <span class="currency">₱</span>
                            </div>
                            <input type="text" placeholder="00.00">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select>
                        <option value="" disabled selected>- Select -</option>
                        <option value="draft">Draft</option>
                        <option value="partial">Partial Payment</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
            </div>

            <!-- Invoice Items Table -->
            <div class="invoice-items">
                <table>
                    <thead>
                        <tr>
                            <th>QUANTITY</th>
                            <th>UNIT</th>
                            <th>ARTICLES</th>
                            <th>UNIT PRICE</th>
                            <th>AMOUNT</th>
                            <th>TOTAL SALES</th>
                            <th>LESS: SC/PWD-DISCOUNT</th>
                            <th>TOTAL DUE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" class="quantity" placeholder="Quantity"></td>
                            <td><input type="text" class="unit" placeholder="uUnit"></td>
                            <td><input type="text" class="article" placeholder="Article 1"></td>
                            <td>
                                <div class="amount-input">
                                    <input type="text" placeholder="Unit price">
                                </div>
                            </td>
                            <td>
                                <div class="amount-input">
                                    <input type="text" placeholder="Amount">
                                </div>
                            </td>
                            <td>
                                <div class="amount-input">
                                    <input type="text" placeholder="Total sales">
                                </div>
                            </td>
                            <td>
                                <div class="amount-input">
                                    <input type="text" placeholder="Discount">
                                </div>
                            </td>
                            <td>
                                <div class="amount-input">
                                    <input type="text"  placeholder="Total due">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Save Button -->
            <div class="form-actions">
                <button class="btn-save">
                    <i class="material-icons">save</i>
                    Save New Invoice
                </button>
            </div>
        </div>
    </div>
    <script>
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
    </script>
</body>

</html>