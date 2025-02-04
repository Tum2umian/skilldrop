<?php
include 'db.php';

$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message_text = $_POST['message'];

    // Validate form fields
    if (empty($name) || empty($email) || empty($message_text)) {
        $message = 'Please fill in all fields.';
    } else {
        // Prepare and bind parameters to insert the message into the database
        $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message_text);

        if ($stmt->execute()) {
            $message = 'Thank you for reaching out! We will get back to you soon.';
        } else {
            $message = 'An error occurred. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillDrop - Contact Us</title>
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
            --success: #28a745;
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
            padding: 3rem 1rem;
        }

        .contact-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: var(--surface);
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .section-title {
            color: var(--primary);
            margin-bottom: 1.5rem;
            font-size: 1.75rem;
            text-align: center;
        }

        .message {
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 1rem;
            border-radius: var(--radius);
            background-color: var(--success);
            color: var(--text-light);
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
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
        }

        textarea.form-input {
            min-height: 150px;
            resize: vertical;
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

        .footer {
            background-color: var(--primary);
            color: var(--text-light);
            padding: 1rem 0;
            margin-top: 2rem;
            text-align: center;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
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

            .contact-container {
                padding: 1.5rem;
            }

            .section-title {
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
        <div class="contact-container">
            <h2 class="section-title">Contact Us</h2>

            <!-- Display success or error message -->
            <?php if (!empty($message)): ?>
                <div class="message">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form action="contact.php" method="post">
                <div class="form-group">
                    <label class="form-label" for="name">
                        <i class="fas fa-user"></i> Name
                    </label>
                    <input type="text" id="name" name="name" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <input type="email" id="email" name="email" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="message">
                        <i class="fas fa-comment"></i> Message
                    </label>
                    <textarea id="message" name="message" class="form-input" required></textarea>
                </div>

                <button type="submit" class="submit-button">
                    <i class="fas fa-paper-plane"></i> Send Message
                </button>
            </form>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-links">
            <a href="about.php" class="footer-link">About</a>
            <a href="index.php" class="footer-link">Home</a>
        </div>
    </footer>
</body>
</html>


