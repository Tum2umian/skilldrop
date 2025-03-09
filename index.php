<?php 
// index.php

include 'includes/header.php'; 
?>
<link rel="stylesheet" href="assets/css/main.css">
<?php if (isset($_GET['logout']) && $_GET['logout'] == "success"): ?>
    <div class="alertsuccess">You have logged out successfully!</div>
<?php endif; ?>

<main class="main container">
    <!-- Hero Section -->
    <section class="hero">
        <h2>Find Skilled Professionals Nearby</h2>
        <p>Connect with trusted experts for any task, from repairs to lessons.</p>
        <div class="hero-buttons">
            <a href="dashboard.php">
                <button class="hero-button hero-button-primary">Find a Professional</button>
            </a>
            <a href="post-job.php">
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
