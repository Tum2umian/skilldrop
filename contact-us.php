<?php 
include 'includes/header.php';
include 'includes/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name         = trim($_POST['name']);
    $email        = trim($_POST['email']);
    $message_text = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($message_text)) {
        $message = "Please fill in all fields.";
    } else {
        // Insert message into database (assumes a table "messages")
        $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message_text);
        if ($stmt->execute()) {
            $message = "Thank you for reaching out! We will get back to you soon.";
        } else {
            $message = "An error occurred. Please try again.";
        }
        $stmt->close();
    }
}
?>

<main class="main container contact-us">
    <h2>Contact Us</h2>
    
    <?php if ($message): ?>
        <p class="message <?php echo (strpos($message, 'Thank you') !== false ? 'success' : 'error-message'); ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>
    
    <form action="contact-us.php" method="post" class="form">
        <div class="form-group">
            <label for="name" class="form-label">Name:</label>
            <input atype="text" name="name" id="name" class="form-input" required>
        </div>
        
        <div class="form-group">
            <label for="email" class="form-label">Email Address:</label>
            <input type="email" name="email" id="email" class="form-input" required>
        </div>
        
        <div class="form-group">
            <label for="message" class="form-label">Message:</label>
            <textarea name="message" id="message" rows="5" class="form-input" required></textarea>
        </div>
        
        <button type="submit" class="submit-button">Send Message</button>
    </form>
</main>

<?php 
include 'includes/footer.php';
?>
