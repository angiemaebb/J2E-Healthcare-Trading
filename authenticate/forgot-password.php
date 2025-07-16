<?php
session_start();
require_once '../config/db.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (isset($data['step']) && $data['step'] === 'check_email') {
        // Step 1: Check if email exists
        if (!empty($data['email'])) {
            $email = trim($data['email']);
            try {
                $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();
                if ($user) {
                    $response['success'] = true;
                    $response['message'] = 'Email found. You may reset your password.';
                } else {
                    $response['message'] = 'Email not found.';
                }
            } catch (PDOException $e) {
                $response['message'] = 'An error occurred. Please try again.';
            }
        } else {
            $response['message'] = 'Please enter your email address.';
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } elseif (isset($data['step']) && $data['step'] === 'reset_password') {
        // Step 2: Reset password
        if (!empty($data['email']) && !empty($data['new_password']) && !empty($data['confirm_password'])) {
            $email = trim($data['email']);
            $new_password = $data['new_password'];
            $confirm_password = $data['confirm_password'];
            if ($new_password !== $confirm_password) {
                $response['message'] = 'Passwords do not match.';
            } elseif (strlen($new_password) < 6) {
                $response['message'] = 'Password must be at least 6 characters.';
            } else {
                try {
                    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
                    $stmt->execute([$email]);
                    $user = $stmt->fetch();
                    if ($user) {
                        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                        $update = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
                        if ($update->execute([$password_hash, $email])) {
                            $response['success'] = true;
                            $response['message'] = 'Password updated successfully! You can now log in.';
                        } else {
                            $response['message'] = 'Failed to update password.';
                        }
                    } else {
                        $response['message'] = 'Email not found.';
                    }
                } catch (PDOException $e) {
                    $response['message'] = 'An error occurred. Please try again.';
                }
            }
        } else {
            $response['message'] = 'Please fill in all fields.';
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="../images/J2E logo favicon.png" type="image/x-icon">
    <title>Forgot Password - J2E Healthcare Trading</title>
    <style>
        :root {
            --main-color: #db2c24;
            --secondary-color: #ff914d;
        }
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background: #fff;
        }
        .container {
            display: flex;
            height: 100vh;
            width: 100vw;
            background-image: url('../images/login-background.png');
            background-size: cover;
            background-position: center;
        }
        .center-box {
            margin: auto;
            background: white;
            padding: 2rem 2.5rem;
            border-radius: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            max-width: 400px;
            width: 100%;
        }
        h2 {
            color: var(--main-color);
            margin-bottom: 1.5rem;
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
            width: 100%;
            margin-bottom: 1rem;
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
            width: 100%;
            transition: all 0.3s ease;
        }
        button:hover {
            background-color: var(--secondary-color);
            font-weight: bold;
        }
        .message {
            margin-top: 1rem;
            text-align: center;
            color: var(--main-color);
            font-size: 1rem;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="center-box">
            <h2>Forgot Password</h2>
            <form id="forgot-form">
                <div id="step1">
                    <label for="email">Enter your email address</label>
                    <input type="email" id="email" name="email" required autocomplete="email" />
                    <button type="submit">Next</button>
                </div>
                <div id="step2" style="display:none;">
                    <label for="new-password">New Password</label>
                    <input type="password" id="new-password" name="new-password" minlength="6" disabled />
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" minlength="6" disabled />
                    <button type="submit">Change Password</button>
                </div>
                <div id="message" class="message"></div>
            </form>
            <div style="text-align:center; margin-top:1rem;">
                <a href="login.php" style="color:var(--main-color);text-decoration:none;">Back to Login</a>
            </div>
        </div>
    </div>
    <script>
        let foundEmail = '';
        const forgotForm = document.getElementById('forgot-form');
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const messageDiv = document.getElementById('message');
        const newPasswordInput = document.getElementById('new-password');
        const confirmPasswordInput = document.getElementById('confirm-password');

        forgotForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            messageDiv.style.display = 'none';
            if (step1.style.display !== 'none') {
                // Disable and remove required from password fields for step 1
                newPasswordInput.disabled = true;
                confirmPasswordInput.disabled = true;
                newPasswordInput.removeAttribute('required');
                confirmPasswordInput.removeAttribute('required');

                // Step 1: Check email
                const email = document.getElementById('email').value;
                const response = await fetch('forgot-password.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ step: 'check_email', email })
                });
                const data = await response.json();
                messageDiv.textContent = data.message;
                messageDiv.style.display = 'block';
                if (data.success) {
                    foundEmail = email;
                    step1.style.display = 'none';
                    step2.style.display = 'block';
                    // Enable and add required to password fields for step 2
                    newPasswordInput.disabled = false;
                    confirmPasswordInput.disabled = false;
                    newPasswordInput.setAttribute('required', 'required');
                    confirmPasswordInput.setAttribute('required', 'required');
                    messageDiv.textContent = '';
                }
            } else {
                // Step 2: Reset password
                const newPassword = newPasswordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                const response = await fetch('forgot-password.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ step: 'reset_password', email: foundEmail, new_password: newPassword, confirm_password: confirmPassword })
                });
                const data = await response.json();
                messageDiv.textContent = data.message;
                messageDiv.style.display = 'block';
                if (data.success) {
                    messageDiv.style.color = 'green';
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 2000);
                } else {
                    messageDiv.style.color = 'var(--main-color)';
                }
            }
        });
    </script>
</body>
</html> 