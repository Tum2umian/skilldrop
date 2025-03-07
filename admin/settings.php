<?php
include '../includes/header.php';
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$message = '';

// Handle settings update (Example: Changing Site Name)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $site_name = trim($_POST['site_name']);
    $site_email = trim($_POST['site_email']);
    $maintenance_mode = isset($_POST['maintenance_mode']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE settings SET site_name = ?, site_email = ?, maintenance_mode = ? WHERE id = 1");
    $stmt->bind_param("ssi", $site_name, $site_email, $maintenance_mode);
    
    if ($stmt->execute()) {
        $message = "Settings updated successfully!";
    } else {
        $message = "Error updating settings. Please try again.";
    }
}

// Fetch current settings
$settings_result = $conn->query("SELECT * FROM settings WHERE id = 1");
$settings = $settings_result->fetch_assoc();
?>

<main class="main">
    <h2>System Settings</h2>

    <nav class="nav-links" id="adminlinks">
        <div class="nav-links-container">
            <a href="manage-users.php" class="nav-link">
                <i class="fas fa-users"></i> Manage Users
            </a>
            <a href="manage-requests.php" class="nav-link">
                <i class="fas fa-tasks"></i> Service Requests
            </a>
            <a href="reports.php" class="nav-link">
                <i class="fas fa-chart-line"></i> Reports
            </a>
            <a href="settings.php" class="nav-link active">
                <i class="fas fa-cog"></i> Settings
            </a>
        </div>
    </nav>

    <?php if ($message): ?>
        <p class="message success"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-cog"></i> General Settings</h3>
            <form action="settings.php" method="POST" class="form">
                <div class="form-group">
                    <label for="site_name" class="form-label">Site Name:</label>
                    <input type="text" name="site_name" id="site_name" value="<?php echo htmlspecialchars($settings['site_name']); ?>" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="site_email" class="form-label">Admin Email:</label>
                    <input type="email" name="site_email" id="site_email" value="<?php echo htmlspecialchars($settings['site_email']); ?>" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="maintenance_mode" class="form-label">
                        <input type="checkbox" name="maintenance_mode" id="maintenance_mode" <?php echo $settings['maintenance_mode'] ? 'checked' : ''; ?>>
                        Enable Maintenance Mode
                    </label>
                </div>

                <button type="submit" class="button primary">Save Changes</button>
            </form>
        </div>

        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-key"></i> Change Password</h3>
            <form action="change-password.php" method="POST" class="form">
                <div class="form-group">
                    <label for="current_password" class="form-label">Current Password:</label>
                    <input type="password" name="current_password" id="current_password" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="new_password" class="form-label">New Password:</label>
                    <input type="password" name="new_password" id="new_password" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirm Password:</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-input" required>
                </div>

                <button type="submit" class="button secondary">Update Password</button>
            </form>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
