<?php

// Correct paths to the `includes` directory
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/db.php';

// Ensure database connection is established
if (!isset($conn)) {
    die("Database connection failed.");
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user's approval status
$stmt = $conn->prepare("SELECT status FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$status = $user['status'] ?? 'pending';

// If user is approved, redirect them to their dashboard
if ($status === 'approved') {
    header("Location: dashboard.php");
    exit;
}
?>

<main class="main">
    <section class="confirmation-section">
        <h2>Your Account is Pending Approval</h2>
        <p>Your account is currently under review by the admin. Please check back later.</p>
        <img src="assets/images/pending-confirmation.svg" alt="Pending Confirmation" class="pending-image">
        
        <div class="confirmation-actions">
            <a href="auth/logout.php" class="button button-secondary">Log Out</a>
            <a href="contact-us.php" class="button button-primary">Contact Support</a>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
