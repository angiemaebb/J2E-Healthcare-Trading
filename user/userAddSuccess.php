<?php
require_once '../config/session_check.php';

// Check if we have the password from the creation process
if (!isset($_SESSION['new_user_password'])) {
    header("Location: user_management.php");
    exit();
}

// Get the stored values
$password = $_SESSION['new_user_password'];
$username = $_SESSION['new_user_username'];

// Clear the session variables immediately after retrieving
unset($_SESSION['new_user_password']);
unset($_SESSION['new_user_username']);

// Get current username for the header
$current_username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Created Successfully</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Add your existing styles here */
        .password-display {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            max-width: 600px;
            margin: 2rem auto;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .password-display h3 {
            color: #4CAF50;
            margin-top: 0;
        }
        .password-field {
            background: #f5f5f5;
            padding: 1rem;
            border-radius: 4px;
            font-family: monospace;
            font-size: 1.2rem;
            margin: 1rem 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .copy-btn {
            background: #2196F3;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }
        .copy-btn:hover {
            background: #0b7dda;
        }
        .warning {
            color: #f44336;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Include your existing navigation header -->
    <nav class="top-nav">
        <!-- Your existing nav code -->
    </nav>

    <div class="user-management">
        <div class="header">
            <h1>User Created Successfully</h1>
        </div>
        
        <div class="password-display">
            <h3><i class="fas fa-check-circle"></i> New User Account Created</h3>
            
            <div class="form-group">
                <label>Username:</label>
                <div><?php echo htmlspecialchars($username); ?></div>
            </div>
            
            <div class="form-group">
                <label>Temporary Password:</label>
                <div class="password-field">
                    <span id="temp-pwd"><?php echo htmlspecialchars($password); ?></span>
                    <button class="copy-btn" onclick="copyPassword()">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </div>
            </div>
            
            <p class="warning">
                <i class="fas fa-exclamation-triangle"></i> Please provide this password to the user securely!
            </p>
            
            <div class="form-actions">
                <a href="user_management.php" class="btn-save">
                    <i class="material-icons">arrow_back</i>
                    Back to User Management
                </a>
            </div>
        </div>
    </div>

    <script>
        function copyPassword() {
            const pwd = document.getElementById('temp-pwd').textContent;
            navigator.clipboard.writeText(pwd).then(() => {
                alert('Password copied to clipboard!');
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }
    </script>
</body>
</html>