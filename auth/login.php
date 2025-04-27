<?php 
// login.php
include '../includes/header.php';
include '../includes/db.php';
//session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emailphone = trim($_POST['emailphone']);
    $password = $_POST['password'];
    
    // Determine whether input is an email or phone number
    $field = filter_var($emailphone, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
    
    $stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE $field = ?");
    $stmt->bind_param("s", $emailphone);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role']; // Store user role in session

        // Redirect based on user role
        switch ($user['role']) {
            case 'admin':
                header("Location: ../admin/dashboard.php");
                break;
            case 'employer':
                header("Location: ../employers/dashboard.php");
                break;
            case 'worker':
                header("Location: ../workers/dashboard.php");
                break;
            default:
                header("Location: ../pending-confirmation.php"); // Default dashboard if role is undefined
        }
        exit;
    } else {
        $message = "Invalid login credentials!";
    }
}
?>

<main class="main container">
    <h2>Log In</h2>
    <?php if ($message): ?>
        <p class="error-message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form action="auth/login.php" method="post" id="loginForm" class="form">
        <div class="form-group">
            <label for="emailphone" class="form-label">Email/Phone:</label>
            <input type="text" name="emailphone" id="emailphone" class="form-input" required>
        </div>
        <div class="form-group">
            <label for="password" class="form-label">Password:</label>
            <input type="password" name="password" id="password" class="form-input" required>
        </div>
        <button type="submit" class="submit-button">Log In</button>
    </form>
    <p><a href="auth/forgot-password.php">Forgot Password?</a></p>
    <p>Don't have an account? <a href="auth/register.php">Register here</a></p>
</main>

<?php include '../includes/footer.php'; ?>
