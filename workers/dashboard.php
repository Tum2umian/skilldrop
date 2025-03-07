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

$profile_image = !empty($user['profile_image']) ? "../" . $user['profile_image'] : '../uploads/profile_images/default.svg';

// Fetch worker's services
$services_stmt = $conn->prepare("SELECT service_name, status FROM service_requests WHERE professional_id = ? ORDER BY created_at DESC");
$services_stmt->bind_param("i", $user_id);
$services_stmt->execute();
$services_result = $services_stmt->get_result();

// Fetch worker's reviews
$reviews_stmt = $conn->prepare("SELECT review_text, rating FROM reviews WHERE professional_id = ? ORDER BY created_at DESC");
$reviews_stmt->bind_param("i", $user_id);
$reviews_stmt->execute();
$reviews_result = $reviews_stmt->get_result();
?>

<main class="main">
    <h2>Worker Dashboard</h2>

    <!-- Top Navigation -->
    <nav class="worker-top-nav">
        <div class="worker-profile">
            <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image" class="worker-profile-icon">
            <span><?php echo htmlspecialchars($user_name); ?></span>
        </div>
        <div class="worker-top-nav-links">
            
            <a href="edit-profile.php" class="nav-link"><i class="fas fa-user-edit"></i> Edit Profile</a>
            
        </div>
    </nav>

    <!-- Lower Navigation -->
  <br>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-tasks"></i> Pending Requests</h3>
            <ul class="worker-ul">
                <?php
                $pending_requests_stmt = $conn->prepare("SELECT service_name FROM service_requests WHERE professional_id = ? AND status = 'pending'");
                $pending_requests_stmt->bind_param("i", $user_id);
                $pending_requests_stmt->execute();
                $pending_requests_result = $pending_requests_stmt->get_result();

                if ($pending_requests_result->num_rows > 0) {
                    while ($request = $pending_requests_result->fetch_assoc()) {
                        echo "<li>" . htmlspecialchars($request['service_name']) . "</li>";
                    }
                } else {
                    echo "<li>No pending requests</li>";
                }
                ?>
            </ul>
        </div>

        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-check-circle"></i> Completed Requests</h3>
            <ul class="worker-ul">
                <?php
                $completed_requests_stmt = $conn->prepare("SELECT service_name FROM service_requests WHERE professional_id = ? AND status = 'completed'");
                $completed_requests_stmt->bind_param("i", $user_id);
                $completed_requests_stmt->execute();
                $completed_requests_result = $completed_requests_stmt->get_result();

                if ($completed_requests_result->num_rows > 0) {
                    while ($request = $completed_requests_result->fetch_assoc()) {
                        echo "<li>" . htmlspecialchars($request['service_name']) . "</li>";
                    }
                } else {
                    echo "<li>No completed requests</li>";
                }
                ?>
            </ul>
        </div>

        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-tools"></i> My Services</h3>
            <a href="manage-services.php" class="button">Manage Services</a>
        </div>
    </div>

    <section class="reviews-section">
        <h3 class="card-title"><i class="fas fa-star"></i> Client Reviews</h3>
        <ul class="worker-ul">
            <?php
            if ($reviews_result->num_rows > 0) {
                while ($review = $reviews_result->fetch_assoc()) {
                    echo "<li>" . htmlspecialchars($review['review_text']) . " - Rating: " . htmlspecialchars($review['rating']) . "/5</li>";
                }
            } else {
                echo "<li>No reviews yet</li>";
            }
            ?>
        </ul>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
