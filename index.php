<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillDrop - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Modern CSS Reset and Variables */
        :root {
            --primary: #2E7D32;
            --primary-dark: #1B5E20;
            --primary-light: #4CAF50;
            --secondary: #1976D2;
            --background: #f8f9fa;
            --surface: #ffffff;
            --text: #333333;
            --text-light: #ffffff;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --shadow: 0 2px 4px rgba(0,0,0,0.1);
            --radius: 8px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Base Styles */
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: var(--text);
            background-color: var(--background);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Styles */
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
            justify-content: space-between;
            align-items: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            height: 50px;
            width: auto;
        }

        /* Navigation */
        .nav {
            display: flex;
            gap: 2rem;
        }

        .nav-link {
            color: var(--text-light);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            transition: background-color 0.3s ease;
        }

        .nav-link:hover {
            background-color: var(--primary-dark);
        }

        /* Hero Section */
        .hero {
            text-align: center;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                url('uploads/backgrounds/hero-background.jpg') no-repeat center center;
            background-attachment: fixed;
            color: var(--text-light);
            padding: 6rem 2rem;
        }

        .hero h2 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
        }

        .hero-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .hero-button {
            padding: 1rem 2rem;
            font-size: 1rem;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .hero-button-primary {
            background-color: var(--secondary);
            color: var(--text-light);
        }

        .hero-button-primary:hover {
            background-color: var(--primary-light);
        }

        .hero-button-secondary {
            background-color: var(--surface);
            color: var(--primary);
        }

        .hero-button-secondary:hover {
            background-color: var(--gray-200);
        }

        /* How it Works Section */
        .how-it-works {
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .how-it-works h3 {
            text-align: center;
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 2rem;
        }

        .steps {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .step-icon {
            font-size: 2rem;
            color: var(--primary);
        }

        .step-description {
            font-size: 1.2rem;
        }

        /* Footer */
        .footer {
            background-color: var(--primary);
            color: var(--text-light);
            padding: 2rem;
            text-align: center;
        }

        .footer-link {
            color: var(--text-light);
            text-decoration: none;
            margin: 0 0.5rem;
            transition: opacity 0.3s ease;
        }

        .footer-link:hover {
            opacity: 0.8;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero h2 {
                font-size: 2.5rem;
            }

            .steps {
                flex-direction: column;
                gap: 1.5rem;
            }

            .step {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo-container">
                <img src="assets/images/logo.png" alt="SkillDrop Logo" class="logo">
                <h1>SkillDrop</h1>
            </div>
            <nav class="nav">
                <a href="auth/login.php" class="nav-link">
                    <i class="fas fa-sign-in-alt"></i> Log In
                </a>
                <a href="auth/signup.php" class="nav-link">
                    <i class="fas fa-user-plus"></i> Sign Up
                </a>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <h2>Find Skilled Professionals Nearby</h2>
            <p>Connect with trusted experts for any task, from repairs to lessons.</p>
            <div class="hero-buttons">
                <button class="hero-button hero-button-primary">Find a Professional</button>
                <button class="hero-button hero-button-secondary">Post a Job</button>
            </div>
        </section>

        <section class="how-it-works">
            <h3>How it Works</h3>
            <div class="steps">
                <div class="step">
                    <i class="fas fa-search step-icon"></i>
                    <p class="step-description">Search for a skill or professional in your area</p>
                </div>
                <div class="step">
                    <i class="fas fa-user step-icon"></i>
                    <p class="step-description">Compare professionals and view their profiles</p>
                </div>
                <div class="step">
                    <i class="fas fa-handshake step-icon"></i>
                    <p class="step-description">Contact the professional and get the job done</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <a href="about.php" class="footer-link">About</a> | 
        <a href="contact.php" class="footer-link">Contact Us</a>
    </footer>
</body>
</html>
