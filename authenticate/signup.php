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
        $email = trim($data['email']);
        $password = trim($data['password']);
        $confirmPassword = trim($data['confirmPassword']);

        // Validate input
        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            $response['message'] = 'All fields are required';
        } elseif (strlen($username) < 4) {
            $response['message'] = 'Username must be at least 4 characters long';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Please enter a valid email address';
        } elseif (strlen($password) < 6) {
            $response['message'] = 'Password must be at least 6 characters long';
        } elseif ($password !== $confirmPassword) {
            $response['message'] = 'Passwords do not match';
        } else {
            try {
                // Check if username already exists
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
                $stmt->execute([$username]);
                if ($stmt->fetchColumn() > 0) {
                    $response['message'] = 'Username already exists';
                } else {
                    // Check if email already exists
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
                    $stmt->execute([$email]);
                    if ($stmt->fetchColumn() > 0) {
                        $response['message'] = 'Email already exists';
                    } else {
                        // Hash password
                        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

                        // Insert new user
                        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role_id, status_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                        try {
                            if ($stmt->execute([$username, $email, $passwordHash, 2, 1])) {
                                $response['success'] = true;
                                $response['message'] = 'Registration successful';
                            } else {
                                $error = $stmt->errorInfo();
                                error_log("SQL Error: " . print_r($error, true));
                                $response['message'] = 'Registration failed. Database error: ' . $error[2];
                            }
                        } catch (PDOException $e) {
                            error_log("Insert Error: " . $e->getMessage());
                            $response['message'] = 'Registration failed. Error: ' . $e->getMessage();
                        }
                    }
                }
            } catch (PDOException $e) {
                $response['message'] = 'Registration failed. Please try again.';
                error_log("Registration error: " . $e->getMessage());
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
    <title>J2E Healthcare Trading - Sign Up</title>
    <style>
        :root {
            --main-color: #db2c24;
            --secondary-color: #ff914d;
            --error-color: #ff3333;
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
            background-image: url('../images/signup-background2.png');
            background-size: cover;
            background-position: relative;
        }

        .left-side {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
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
            overflow-y: auto;
            border-top-left-radius: 3rem;
            border-bottom-left-radius: 3rem;
            box-shadow: -15px 0 60px -15px #636363;
            z-index: 1;
            margin-left: -5%;
        }

        form {
            max-width: 500px;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            position: relative;
            margin-bottom: 0;
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
            margin-bottom: 0.5rem;
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
            margin-top: 0.5rem;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: var(--secondary-color);
            font-weight: bold;
        }

        .bottom-text {
            text-align: center;
            color: #666;
            margin-top: 1rem;
        }

        #switch-to-login {
            color: var(--main-color);
            cursor: pointer;
            font-weight: 500;
        }

        #switch-to-login:hover {
            text-decoration: underline;
            font-weight: bold;
        }

        .error-message {
            color: var(--error-color);
            font-size: 0.8rem;
            position: absolute;
            bottom: -1.2rem;
            left: 0;
            display: none;
            padding: 0.5rem 0.5rem;
            border-radius: 0.25rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 2;
        }

        .success-message {
            color: green;
            text-align: center;
            margin-top: 1rem;
            display: none;
        }

        input.error {
            border-color: var(--error-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-side">
            <div class="logo-container"><img src="../images/J2E-logo2.png" alt="J2E Logo"></div>
        </div>
        <div class="right-side">
            <form id="signup-form">
                <h2>Create an account</h2>
                
                <div class="form-group">
                    <label for="reg-username">Username</label>
                    <input type="text" id="reg-username" name="reg-username" placeholder="Enter your Username" required autocomplete="username" aria-required="true" />
                    <div id="username-error" class="error-message">Username must be at least 4 characters</div>
                </div>

                <div class="form-group">
                    <label for="reg-email">Email</label>
                    <input type="email" id="reg-email" name="reg-email" placeholder="Enter your Email" required autocomplete="email" aria-required="true" />
                    <div id="email-error" class="error-message">Please enter a valid email address</div>
                </div>

                <div class="form-group">
                    <label for="reg-password">Password</label>
                    <input type="password" id="reg-password" name="reg-password" placeholder="Enter your Password" required autocomplete="new-password" aria-required="true" minlength="6" />
                    <div id="password-error" class="error-message">Password must be at least 6 characters</div>
                </div>

                <div class="form-group">
                    <label for="reg-confirm-password">Confirm Password</label>
                    <input type="password" id="reg-confirm-password" name="reg-confirm-password" placeholder="Re-enter your Password" required aria-required="true" minlength="6" />
                    <div id="confirm-password-error" class="error-message">Passwords do not match</div>
                </div>

                <button type="submit" aria-label="Sign Up">Sign Up</button>
                
                <div id="success-message" class="success-message">Account created successfully! Redirecting to login page...</div>
                
                <p class="bottom-text">
                    Already have an account? <a href="login.php" id="switch-to-login" class="link">Login</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('signup-form');
            const usernameInput = document.getElementById('reg-username');
            const emailInput = document.getElementById('reg-email');
            const passwordInput = document.getElementById('reg-password');
            const confirmPasswordInput = document.getElementById('reg-confirm-password');
            const usernameError = document.getElementById('username-error');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');
            const confirmPasswordError = document.getElementById('confirm-password-error');
            const successMessage = document.getElementById('success-message');

            function validateUsername() {
                const isValid = usernameInput.value.length >= 4;
                usernameInput.classList.toggle('error', !isValid);
                usernameError.style.display = isValid ? 'none' : 'block';
                return isValid;
            }

            function validateEmail() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const isValid = emailRegex.test(emailInput.value);
                emailInput.classList.toggle('error', !isValid);
                emailError.style.display = isValid ? 'none' : 'block';
                return isValid;
            }

            function validatePassword() {
                const isValid = passwordInput.value.length >= 6;
                passwordInput.classList.toggle('error', !isValid);
                passwordError.style.display = isValid ? 'none' : 'block';
                return isValid;
            }

            function validateConfirmPassword() {
                const isValid = confirmPasswordInput.value === passwordInput.value;
                confirmPasswordInput.classList.toggle('error', !isValid);
                confirmPasswordError.style.display = isValid ? 'none' : 'block';
                return isValid;
            }

            // Add event listeners for real-time validation
            usernameInput.addEventListener('input', validateUsername);
            emailInput.addEventListener('input', validateEmail);
            passwordInput.addEventListener('input', function() {
                validatePassword();
                if (confirmPasswordInput.value) validateConfirmPassword();
            });
            confirmPasswordInput.addEventListener('input', validateConfirmPassword);

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Validate all fields
                const isUsernameValid = validateUsername();
                const isEmailValid = validateEmail();
                const isPasswordValid = validatePassword();
                const isConfirmPasswordValid = validateConfirmPassword();

                if (!isUsernameValid || !isEmailValid || !isPasswordValid || !isConfirmPasswordValid) {
                    return;
                }

                try {
                    const response = await fetch('signup.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            username: usernameInput.value,
                            email: emailInput.value,
                            password: passwordInput.value,
                            confirmPassword: confirmPasswordInput.value
                        })
                    });

                    const data = await response.json();
                    
                    if (data.success) {
                        successMessage.style.display = 'block';
                        successMessage.textContent = data.message + ' Redirecting to login page...';
                        
                        // Clear form
                        form.reset();
                        
                        // Redirect to login page after 2 seconds
                        setTimeout(() => {
                            window.location.href = 'login.php';
                        }, 2000);
                    } else {
                        // Show error in the confirm password error element
                        confirmPasswordError.textContent = data.message;
                        confirmPasswordError.style.display = 'block';
                    }
                } catch (error) {
                    console.error('Error:', error);
                    confirmPasswordError.textContent = 'An error occurred. Please try again.';
                    confirmPasswordError.style.display = 'block';
                }
            });
        });
    </script>
</body>
</html>
