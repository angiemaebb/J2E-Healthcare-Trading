<?php
session_start();
require_once '../config/db.php';

// Initialize response array
$response = array('success' => false, 'message' => '');

// Check if form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON data from request
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data) {
        $username = trim($data['username']);
        $password = trim($data['password']);

        if (empty($username) || empty($password)) {
            $response['message'] = 'Please enter both username and password';
        } else {
            try {
                $stmt = $pdo->prepare("SELECT user_id, username, password_hash, role_id, status_id FROM users WHERE username = ?");
                $stmt->execute([$username]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password_hash'])) {
                    if ($user['status_id'] == 1) { // Check if user is active
                        // Set session variables
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['role_id'] = $user['role_id'];
                        $_SESSION['last_activity'] = time();

                        // Update last login
                        $updateStmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
                        $updateStmt->execute([$user['user_id']]);

                        $response['success'] = true;
                        $response['message'] = 'Login successful';
                    } else {
                        $response['message'] = 'Account is inactive. Please contact administrator.';
                    }
                } else {
                    $response['message'] = 'Invalid username or password';
                }
            } catch (PDOException $e) {
                $response['message'] = 'Login failed. Please try again.';
                error_log("Login error: " . $e->getMessage());
            }
        }
    } else {
        $response['message'] = 'Invalid request';
    }

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="../images/J2E logo favicon.png" type="image/x-icon">
    <title>J2E Healthcare Trading - Log In</title>
    <style>
        :root {
            --main-color: #db2c24;
            --secondary-color: #ff914d;
            --shadow: #8d8d8d;
            --text-color: #666;
        }

        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background: #fff;
            overflow: hidden;
        }

        .container {
            display: flex;
            height: 100vh;
            width: 100vw;
            background-image: url('../images/login-background.png');
            background-size: cover;
            background-position: center;
        }

        .left-side {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 0;
        }

        .logo-container {
            width: auto;
            max-width: 600px;
            margin-left: -7%;
            z-index: 1;
            padding: 10%;
        }

        .logo-container img {
            width: 100%;
            height: auto;
        }

        .right-side {
            width: 55%;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            border-top-left-radius: 3rem;
            border-bottom-left-radius: 3rem;
            box-shadow: -15px 0 60px -15px var(--shadow);
            z-index: 1;
            margin-left: -5%;
        }

        form {
            max-width: 500px;
            width: 80%;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            position: relative;
        }

        h2 {
            margin: 0 0 1rem 0;
            font-size: 2.5rem;
            color: var(--main-color);
        }

        label {
            font-weight: bold;
            color: var(--main-color);
            margin-bottom: 0.5rem;
        }

        input {
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            font-size: 1rem;
        }

        input:focus {
            outline: none;
            border-color: var(--main-color);
        }

        button {
            background-color: var(--main-color);
            color: white;
            padding: 0.8rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: var(--secondary-color);
            font-weight: bold;
        }

        .footer-links {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
        }

        .footer-text {
            text-align: center;
            color: var(--text-color);
            margin-top: 2rem;
            font-size: 0.9rem;
        }

        .link {
            color: var(--main-color);
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
            font-weight: bold;
        }

        .error-message {
            color: var(--main-color);
            font-size: 0.8rem;
            margin-top: 0.5rem;
            display: none;
        }

        input.error {
            border-color: var(--main-color);
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--main-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            display: none;
        }

        .alert-error {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }

        .alert-success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left-side">
            <div class="logo-container"><img src="../images/J2E-logo2.png" alt="J2E Logo"></div>
        </div>
        <div class="right-side">
            <form id="login-form">
                <h2>Welcome back!</h2>
                
                <div id="alert" class="alert"></div>
                
                <div class="form-group">
                    <label for="login-username">Username</label>
                    <input type="text" id="login-username" name="username" placeholder="Enter your Username" required autocomplete="username" />
                    <div id="username-error" class="error-message">Please enter your username</div>
                </div>

                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="password" placeholder="Enter your Password" required autocomplete="current-password" />
                    <div id="password-error" class="error-message">Please enter your password</div>
                </div>

                <button type="submit" aria-label="Login">
                    <span id="button-text">Login</span>
                    <div id="loading-spinner" class="loading-spinner"></div>
                </button>
                
                <div class="footer-links">
                    <a href="../authenticate/signup.html" class="link">Create account</a>
                    <a href="../authenticate/forgot-password.html" class="link">Forgot password?</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('login-form');
            const usernameInput = document.getElementById('login-username');
            const passwordInput = document.getElementById('login-password');
            const usernameError = document.getElementById('username-error');
            const passwordError = document.getElementById('password-error');
            const buttonText = document.getElementById('button-text');
            const loadingSpinner = document.getElementById('loading-spinner');
            const alertDiv = document.getElementById('alert');

            function showAlert(message, type) {
                alertDiv.textContent = message;
                alertDiv.className = `alert alert-${type}`;
                alertDiv.style.display = 'block';
                setTimeout(() => {
                    alertDiv.style.display = 'none';
                }, 5000);
            }

            function setLoading(isLoading) {
                buttonText.style.display = isLoading ? 'none' : 'block';
                loadingSpinner.style.display = isLoading ? 'block' : 'none';
                form.querySelector('button').disabled = isLoading;
            }

            function validateUsername() {
                const isValid = usernameInput.value.trim() !== '';
                usernameInput.classList.toggle('error', !isValid);
                usernameError.style.display = isValid ? 'none' : 'block';
                return isValid;
            }

            function validatePassword() {
                const isValid = passwordInput.value.trim() !== '';
                passwordInput.classList.toggle('error', !isValid);
                passwordError.style.display = isValid ? 'none' : 'block';
                return isValid;
            }

            usernameInput.addEventListener('input', validateUsername);
            passwordInput.addEventListener('input', validatePassword);

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const isUsernameValid = validateUsername();
                const isPasswordValid = validatePassword();
                
                if (isUsernameValid && isPasswordValid) {
                    setLoading(true);
                    
                    fetch('login.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            username: usernameInput.value,
                            password: passwordInput.value
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        setLoading(false);
                        if (data.success) {
                            showAlert('Login successful! Redirecting...', 'success');
                            setTimeout(() => {
                                window.location.href = '../home/dashboard.php';
                            }, 1000);
                        } else {
                            showAlert(data.message || 'Login failed. Please try again.', 'error');
                        }
                    })
                    .catch(error => {
                        setLoading(false);
                        console.error('Error:', error);
                        showAlert('An error occurred. Please try again.', 'error');
                    });
                }
            });
        });
    </script>
</body>

</html>
