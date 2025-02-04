<?php
include 'db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];
    $location = $_POST['location'];
    $skills = $_POST['skills'];

    if ($password !== $confirmpassword) {
        $message = 'Passwords do not match!';
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $check_email = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $result = $check_email->get_result();

        if ($result->num_rows > 0) {
            $message = 'Email already registered!';
        } else {
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, password, location) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $fullname, $email, $phone, $hashed_password, $location);
            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;

                if (!empty($skills)) {
                    $skill_list = explode(",", $skills);
                    foreach ($skill_list as $skill) {
                        $skill = trim($skill);

                        $skill_check = $conn->prepare("SELECT id FROM skills WHERE skill_name = ?");
                        $skill_check->bind_param("s", $skill);
                        $skill_check->execute();
                        $skill_result = $skill_check->get_result();

                        if ($skill_result->num_rows == 0) {
                            $add_skill = $conn->prepare("INSERT INTO skills (skill_name) VALUES (?)");
                            $add_skill->bind_param("s", $skill);
                            $add_skill->execute();
                            $skill_id = $add_skill->insert_id;
                        } else {
                            $skill_id = $skill_result->fetch_assoc()['id'];
                        }

                        $link_professional = $conn->prepare("INSERT INTO professionals (user_id, skill_id) VALUES (?, ?)");
                        $link_professional->bind_param("ii", $user_id, $skill_id);
                        $link_professional->execute();
                    }
                }

                
               
                $message = 'Registration successful!';
            } else {
                $message = 'Registration failed. Try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillDrop - Sign Up</title>
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
            --success: #28a745;
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
            padding: 2rem;
        }

        .signup-container {
            background: var(--surface);
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            max-width: 600px;
            margin: 0 auto;
        }

        .form-title {
            color: var(--primary);
            margin-bottom: 1.5rem;
            font-size: 1.75rem;
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

        .form-input::placeholder {
            color: #999;
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
            margin-top: 1rem;
        }

        .submit-button:hover {
            background-color: var(--primary-dark);
        }

        .message {
            text-align: center;
            margin: 1rem 0;
            padding: 0.75rem;
            border-radius: var(--radius);
        }

        .message.error {
            background-color: var(--error);
            color: var(--text-light);
        }

        .message.success {
            background-color: var(--success);
            color: var(--text-light);
        }

        .login-link {
            text-align: center;
            margin-top: 1rem;
        }

        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: var(--primary-dark);
        }

        .footer {
            background-color: var(--primary);
            color: var(--text-light);
            padding: 1rem 0;
            text-align: center;
            margin-top: 2rem;
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

            .signup-container {
                padding: 1.5rem;
            }

            .form-title {
                font-size: 1.5rem;
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
        <div class="signup-container">
            <h2 class="form-title">Create Your Account</h2>
            
            <?php if ($message): ?>
                <div class="message <?php echo strpos($message, 'successful') !== false ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form action="signup.php" method="post">
                <div class="form-group">
                    <label class="form-label" for="fullname">
                        <i class="fas fa-user"></i> Full Name
                    </label>
                    <input type="text" id="fullname" name="fullname" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <input type="email" id="email" name="email" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="phone">
                        <i class="fas fa-phone"></i> Phone Number
                    </label>
                    <input type="tel" id="phone" name="phone" class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type="password" id="password" name="password" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="confirmpassword">
                        <i class="fas fa-lock"></i> Confirm Password
                    </label>
                    <input type="password" id="confirmpassword" name="confirmpassword" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="location">
                        <i class="fas fa-map-marker-alt"></i> Location
                    </label>
                    <input type="text" id="location" name="location" class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label" for="skills">
                        <i class="fas fa-tools"></i> Skills (Optional for professionals)
                    </label>
                    <input type="text" id="skills" name="skills" class="form-input" 
                           placeholder="e.g., Plumber, Electrician, Tutor">
                </div>

                <button type="submit" class="submit-button">
                    <i class="fas fa-user-plus"></i> Sign Up
                </button>
            </form>

            <div class="login-link">
                <p>Already have an account? <a href="login.php">Log In</a></p>
            </div>
        </div>
    </main>

    <footer class="footer">
        <a href="contact.php" class="footer-link">Contact Us</a>
    </footer>
</body>
</html>


