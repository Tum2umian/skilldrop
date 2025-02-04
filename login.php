<?php
include 'db.php';
session_start();
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emailphone = $_POST['emailphone'];
    $password = $_POST['password'];

    $field = filter_var($emailphone, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
    $stmt = $conn->prepare("SELECT * FROM users WHERE $field = ?");
    $stmt->bind_param("s", $emailphone);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        header("Location: dashboard.php");
        exit;
    } else {
        $message = 'Invalid login credentials!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillDrop - Log In</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2E7D32;
            --primary-dark: #1B5E20;
            --primary-light: #4CAF50;
            --secondary: #1976D2;
            --background: #f8f9fa;
            --surface: #ffffff;
            --text: #333333;
            --text-light: #ffffff;
            --error: #dc3545;
            --gray-200: #e9ecef;
            --shadow: 0 2px 4px rgba(0,0,0,0.1);
            --radius: 8px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: var(--text);
            background-color: var(--background);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background-color: var(--primary);
            color: var(--text-light);
            padding: 1rem 0;
            box-shadow: var(--shadow);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            height: 50px;
            width: auto;
        }

        .main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-container {
            background: var(--surface);
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            width: 100%;
            max-width: 400px;
        }

        .form-title {
            color: var(--primary);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text);
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius);
            transition: border-color 0.3s ease;
            font-size: 1rem;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .submit-button {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary);
            color: var(--text-light);
            border: none;
            border-radius: var(--radius);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-button:hover {
            background-color: var(--primary-dark);
        }

        .error-message {
            color: var(--error);
            text-align: center;
            margin-bottom: 1rem;
        }

        .signup-link {
            text-align: center;
            margin-top: 1rem;
        }

        .signup-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .footer {
            background-color: var(--primary);
            color: var(--text-light);
            padding: 1rem 0;
            text-align: center;
            margin-top: auto;
        }

        .footer-link {
            color: var(--text-light);
            text-decoration: none;
            transition: opacity 0.3s ease;
        }

        .footer-link:hover {
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .login-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <img src="logo.png" alt="SkillDrop Logo" class="logo">
            <h1>SkillDrop</h1>
        </div>
    </header>

    <main class="main">
        <div class="login-container">
            <h2 class="form-title">Log In to Your Account</h2>
            
            <?php if ($message): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="post">
                <div class="form-group">
                    <label class="form-label" for="emailphone">
                        <i class="fas fa-user"></i> Email/Phone Number
                    </label>
                    <input type="text" id="emailphone" name="emailphone" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type="password" id="password" name="password" class="form-input" required>
                </div>

                <button type="submit" class="submit-button">
                    <i class="fas fa-sign-in-alt"></i> Log In
                </button>
            </form>

            <div class="signup-link">
                <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
            </div>
        </div>
    </main>

    <footer class="footer">
        <a href="contact.php" class="footer-link">Contact Us</a>
    </footer>
</body>
</html>


