<?php
include '../includes/header.php';
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    header("Location: ../auth/login.php");
    exit;
}

$search_query = "";
$workers = null; // Ensure it starts as NULL

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $search_query = trim($_GET['search']);

    // Query using professionals & skills table
    $stmt = $conn->prepare("
        SELECT u.id, u.full_name, u.location, u.profile_image, p.rating, GROUP_CONCAT(s.skill_name SEPARATOR ', ') AS skills
        FROM professionals p
        JOIN users u ON p.user_id = u.id
        JOIN skills s ON p.skill_id = s.id
        WHERE (u.full_name LIKE ? OR s.skill_name LIKE ? OR u.location LIKE ?)
        AND u.role = 'worker'
        GROUP BY u.id
        ORDER BY p.rating DESC
    ");

    $param = "%{$search_query}%";
    $stmt->bind_param("sss", $param, $param, $param);
    $stmt->execute();
    $result = $stmt->get_result(); // Fetch result
    $workers = ($result->num_rows > 0) ? $result : null; // Ensure correct type
    $stmt->close();
}
?>

<main class="main">
    <!-- Navigation Links -->
    <nav class="nav-links">
        <div class="nav-links-container">
            <a href="post-job.php" class="nav-link"><i class="fas fa-plus-circle"></i> Post a Job</a>
            <a href="manage-jobs.php" class="nav-link"><i class="fas fa-briefcase"></i> Manage Jobs</a>
            <a href="search-workers.php" class="nav-link"><i class="fas fa-users"></i> Find Workers</a>
           
        </div>
    </nav>
    
    <h2 class="page-title">Search Skilled Workers</h2>

    <!-- Search Form -->
    <form method="GET" action="search-workers.php" class="search-form">
        <input type="text" name="search" class="search-input" placeholder="Search by name, skill, or location" value="<?php echo htmlspecialchars($search_query); ?>">
        <button type="submit" class="button green"><i class="fas fa-search"></i> Search</button>
    </form>

    <?php if ($workers !== null): ?>
        <div class="worker-grid">
            <?php while ($worker = $workers->fetch_assoc()): ?>
                <div class="worker-card">
                    <?php 
                        // Ensure correct profile image path
                        $profile_image = !empty($worker['profile_image']) ? $worker['profile_image'] : 'uploads/profile_images/default.svg';
                        if (strpos($profile_image, 'http') !== 0 && strpos($profile_image, '../') !== 0) {
                            $profile_image = "../" . $profile_image;
                        }
                    ?>

                    <img src="<?php echo htmlspecialchars($profile_image); ?>" 
                         alt="Worker Profile" class="worker-image">

                    <h3><?php echo htmlspecialchars($worker['full_name']); ?></h3>
                    <p><strong>Skills:</strong> <?php echo htmlspecialchars($worker['skills']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($worker['location']); ?></p>
                    <p><strong>Rating:</strong> ⭐ <?php echo number_format($worker['rating'], 1); ?>/5</p>

                    <div class="worker-actions">
                        <a href="worker-profile.php?worker_id=<?php echo $worker['id']; ?>" class="button secondary"><i class="fas fa-user"></i> View Profile</a>
                        <a href="invite-worker.php?worker_id=<?php echo $worker['id']; ?>" class="button green"><i class="fas fa-envelope"></i> Invite to Apply</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="no-results"><i class="fas fa-exclamation-circle"></i> No workers found. Try a different search.</p>
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>
