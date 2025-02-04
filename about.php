<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillDrop - About</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Modern CSS Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Base Styles */
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: #263238;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Container */
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Header Styles */
        header {
            background-color: #2E7D32;
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
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

        .nav-list {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s ease;
        }

        .nav-link:hover {
            opacity: 0.8;
        }

        /* Main Content */
        main {
            padding: 4rem 0;
            flex: 1;
        }

        .section {
            margin-bottom: 3rem;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        h2 {
            color: #2E7D32;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            line-height: 1.2;
        }

        h3 {
            color: #2E7D32;
            margin-bottom: 1rem;
            font-size: 1.75rem;
            line-height: 1.3;
        }

        p {
            margin-bottom: 1rem;
            font-size: 1.125rem;
            color: #455A64;
        }

        /* Footer */
        footer {
            background-color: #2E7D32;
            color: white;
            padding: 1.5rem 0;
            margin-top: auto;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
        }

        .footer-link {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s ease;
        }

        .footer-link:hover {
            opacity: 0.8;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-list {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }

            h2 {
                font-size: 1.75rem;
            }

            h3 {
                font-size: 1.5rem;
            }

            p {
                font-size: 1rem;
            }

            .section {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo-container">
                    <img src="logo.png" alt="SkillDrop Logo" class="logo">
                    <h1>SkillDrop</h1>
                </div>
                <nav>
                    <ul class="nav-list">
                        <li><a href="index.php" class="nav-link">Home</a></li>
                        <li><a href="about.php" class="nav-link">About</a></li>
                        
                        <li><a href="contact.php" class="nav-link">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <section class="section">
                <h2>About Our Service</h2>
                <p>Our platform connects you with skilled professionals in your area. Whether you need a plumber, a tutor, or a babysitter, we make it easy to find trusted individuals for your everyday needs.</p>
            </section>

            <section class="section">
                <h3>Mission</h3>
                <p>Our mission is to seamlessly connect individuals with skilled professionals in their local communities, making it easy to find reliable help for everyday tasks. We strive to create a trusted platform where users can quickly access services, empower skilled workers, and foster meaningful professional relationships, all while saving time and improving lives.</p>
            </section>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="footer-links">
                <a href="index.php" class="footer-link">Home</a>
                <a href="about.php" class="footer-link">About</a>
                
                <a href="contact.php" class="footer-link">Contact Us</a>
            </div>
        </div>
    </footer>
</body>
</html>


