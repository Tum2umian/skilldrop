<?php
include '../includes/header.php';
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'worker') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Fetch worker details
$stmt = $conn->prepare("SELECT full_name, email, phone, profile_image FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$profile_image = !empty($user['profile_image']) ? "" . $user['profile_image'] : '../uploads/profile_images/default.svg';
?>

<main class="main">
    <h2>Manage My Services</h2>

    <nav class="worker-top-nav">
        <div class="worker-profile">
            <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image" class="worker-profile-icon">
            <span><?php echo htmlspecialchars($user_name); ?></span>
        </div>

        <div class="worker-top-nav-links">
        <a href="manage-services.php" class="nav-link">Manage Services</a>
    <a href="respond-invitation.php" class="nav-link">Respond to Invitations</a>
    <a href="edit-profile.php" class="button secondary">Edit Profile</a>
        </div>
    </nav>

    <br>

    <div class="dashboard-grid">
        <!-- Service List -->
        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-tools"></i> My Services</h3>
            <ul id="servicesList" class="worker-ul">
                <!-- Services will be loaded dynamically -->
            </ul>
        </div>

        <!-- Add New Service -->
        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-plus-circle"></i> Add New Service</h3>
            <div id="serviceMessage"></div> 
            <form id="addServiceForm" class="form">
                <input type="hidden" id="service_id" name="service_id">
                <div class="form-group">
                    <label for="service_name" class="form-label">Service Name:</label>
                    <input type="text" name="service_name" id="service_name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="description" class="form-label">Description:</label>
                    <textarea name="description" id="description" rows="3" class="form-input" required></textarea>
                </div>
                <button type="submit" class="submit-button">Save Service</button>
            </form>
        </div>
    </div>
</main>

<script src="../assets/js/services.js"></script>
<?php include '../includes/footer.php'; ?>
