<?php 
// forgot-password.php
include 'includes/header.php';
include 'includes/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    if (empty($email)) {
        $message = "Please enter your email.";
    } else {
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            // Here you would normally generate a reset token, store it, and send an email.
            $message = "Password reset instructions have been sent to your email.";
        } else {
            $message = "Email not found.";
        }
    }
}
?>

<main class="main container">
    <h2>Forgot Password</h2>
    <?php if ($message): ?>
        <p class="message <?php echo (strpos($message, 'sent') !== false ? 'success' : 'error-message'); ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>
    <form action="forgot-password.php" method="post" class="form">
        <div class="form-group">
            <label for="email" class="form-label">Enter your registered Email Address:</label>
            <input type="email" name="email" id="email" class="form-input" required>
        </div>
        <button type="submit" class="submit-button">Reset Password</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>
