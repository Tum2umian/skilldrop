<?php
include '../includes/header.php';
include '../includes/db.php';

// Ensure only employers can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    header("Location: ../auth/login.php");
    exit;
}

// Check if worker_id is provided in the URL
if (!isset($_GET['worker_id']) || empty($_GET['worker_id'])) {
    header("Location: search-workers.php"); // Redirect if no worker ID is provided
    exit;
}

$worker_id = intval($_GET['worker_id']);

// Fetch worker details
$stmt = $conn->prepare("
    SELECT u.id, u.full_name, u.location, u.profile_image, p.rating, 
           GROUP_CONCAT(s.skill_name SEPARATOR ', ') AS skills
    FROM professionals p
    JOIN users u ON p.user_id = u.id
    JOIN skills s ON p.skill_id = s.id
    WHERE u.id = ? AND u.role = 'worker'
    GROUP BY u.id
");
$stmt->bind_param("i", $worker_id);
$stmt->execute();
$worker = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Redirect if worker not found
if (!$worker) {
    header("Location: search-workers.php");
    exit;
}

// Ensure correct profile image path
$profile_image = !empty($worker['profile_image']) ? $worker['profile_image'] : 'uploads/profile_images/default.svg';
if (strpos($profile_image, 'http') !== 0 && strpos($profile_image, '../') !== 0) {
    $profile_image = "../" . $profile_image;
}

// Fetch reviews for the worker
$reviews_stmt = $conn->prepare("
    SELECT r.review_text, r.rating, u.full_name AS reviewer_name, r.created_at 
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    WHERE r.professional_id = ?
    ORDER BY r.created_at DESC
");
$reviews_stmt->bind_param("i", $worker_id);
$reviews_stmt->execute();
$reviews = $reviews_stmt->get_result();
$reviews_stmt->close();
?>

<main class="main container">
    <h2 class="page-title">Worker Profile</h2>

    <div class="profile-container">
        <div class="profile-header">
            <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image" class="profile-image">
            <h2><?php echo htmlspecialchars($worker['full_name']); ?></h2>
            <p><strong>Skills:</strong> <?php echo htmlspecialchars($worker['skills']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($worker['location']); ?></p>
            <p><strong>Rating:</strong> ⭐ <?php echo number_format($worker['rating'], 1); ?>/5</p>

            <div class="worker-actions">
                <a href="invite-worker.php?worker_id=<?php echo $worker['id']; ?>" class="button green">
                    <i class="fas fa-envelope"></i> Invite to Apply
                </a>
                <a href="search-workers.php" class="button secondary">
                    <i class="fas fa-arrow-left"></i> Back to Search
                </a>
            </div>
        </div>

        <!-- Reviews Section -->
        <section class="reviews-section">
            <h3 class="card-title"><i class="fas fa-star"></i> Worker Reviews</h3>
            <ul class="review-list">
                <?php if ($reviews->num_rows > 0): ?>
                    <?php while ($review = $reviews->fetch_assoc()): ?>
                        <li class="review-item">
                            <strong><?php echo htmlspecialchars($review['reviewer_name']); ?></strong>
                            <span class="rating">⭐ <?php echo htmlspecialchars($review['rating']); ?>/5</span>
                            <p><?php echo htmlspecialchars($review['review_text']); ?></p>
                            <small>Reviewed on <?php echo date("F j, Y", strtotime($review['created_at'])); ?></small>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li class="no-reviews"><i class="fas fa-exclamation-circle"></i> No reviews yet.</li>
                <?php endif; ?>
            </ul>
        </section>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
