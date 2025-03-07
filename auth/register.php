<?php 
// register.php
include '../includes/header.php';
include '../includes/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];
    $location = trim($_POST['location']);
    
    if ($password !== $confirmpassword) {
        $message = "Passwords do not match!";
    } else {
        // Check if the email is already registered
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $message = "Email already registered!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, password, location) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $fullname, $email, $phone, $hashed_password, $location);
            if ($stmt->execute()) {
                $message = "Registration successful! Please log in.";
            } else {
                $message = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<main class="main container">
    <h2>Register</h2>
    <?php if ($message): ?>
        <p class="message <?php echo (strpos($message, 'successful') !== false ? 'success' : 'error-message'); ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>
    <form action="register.php" method="post" class="form">
        <div class="form-group">
            <label for="fullname" class="form-label">Full Name:</label>
            <input type="text" name="fullname" id="fullname" class="form-input" required>
        </div>
        <div class="form-group">
            <label for="email" class="form-label">Email Address:</label>
            <input type="email" name="email" id="email" class="form-input" required>
        </div>
        <div class="form-group">
            <label for="phone" class="form-label">Phone Number:</label>
            <input type="tel" name="phone" id="phone" class="form-input">
        </div>
        <div class="form-group">
            <label for="password" class="form-label">Password:</label>
            <input type="password" name="password" id="password" class="form-input" required>
        </div>
        <div class="form-group">
            <label for="confirmpassword" class="form-label">Confirm Password:</label>
            <input type="password" name="confirmpassword" id="confirmpassword" class="form-input" required>
        </div>
        <div class="form-group">
            <label for="location" class="form-label">Location:</label>
            <input type="text" name="location" id="location" class="form-input">
        </div>
        <button type="submit" class="submit-button">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Log In here</a></p>
</main>

<?php include '../includes/footer.php'; ?>
