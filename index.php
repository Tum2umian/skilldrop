<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillDrop | Find Skilled Professionals</title>
    <?php include 'includes/header.php'; 
    error_reporting(0); // Disable all error reporting
    ?>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/forms.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
    <?php if (isset($_GET['logout']) && $_GET['logout'] == "success"): ?>
        <div class="alert alert-success">You have logged out successfully!</div>
    <?php endif; ?>



    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Find Skilled Professionals Nearby</h1>
                <p class="lead">Connect with trusted experts for any task, from home repairs to personal lessons.</p>
                <div class="cta-buttons">
                    <a href="dashboard.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-search"></i> Find a Professional
                    </a>
                    <a href="post-job.php" class="btn btn-secondary btn-lg">
                        <i class="fas fa-bullhorn"></i> Post a Job
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="section how-it-works">
        <div class="container">
            <h2 class="section-title">How It Works</h2>
            <div class="steps-container">
                <div class="step">
                    <div class="step-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Search</h3>
                    <p>Find skilled professionals in your area by category or specific service.</p>
                </div>
                <div class="step">
                    <div class="step-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3>Compare</h3>
                    <p>View profiles, ratings, and reviews to choose the right professional.</p>
                </div>
                <div class="step">
                    <div class="step-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Connect</h3>
                    <p>Message directly and hire with secure payment options.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Services -->
    <section class="section popular-services">
        <div class="container">
            <h2 class="section-title">Popular Services</h2>
            <div class="services-grid">
                <a href="dashboard.php?category=plumbing" class="service-card">
                    <i class="fas fa-faucet"></i>
                    <h3>Plumbing</h3>
                </a>
                <a href="dashboard.php?category=electrical" class="service-card">
                    <i class="fas fa-bolt"></i>
                    <h3>Electrical</h3>
                </a>
                <a href="dashboard.php?category=tutoring" class="service-card">
                    <i class="fas fa-book"></i>
                    <h3>Tutoring</h3>
                </a>
                <a href="dashboard.php?category=cleaning" class="service-card">
                    <i class="fas fa-broom"></i>
                    <h3>Cleaning</h3>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer loaded from footer.php -->
    <?php include 'includes/footer.php'; ?>

    <!-- External JS Files -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/auth.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</body>
</html>