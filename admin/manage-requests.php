<?php
include '../includes/header.php';
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Fetch service requests
$requests = $conn->query("SELECT sr.*, u.full_name AS user_name, p.full_name AS professional_name 
                          FROM service_requests sr
                          JOIN users u ON sr.user_id = u.id
                          JOIN users p ON sr.professional_id = p.id
                          ORDER BY sr.created_at DESC");
?>

<main class="main">
    <h2>Manage Service Requests</h2>

    <nav class="nav-links" id="adminlinks">
        <div class="nav-links-container">
            <a href="manage-users.php" class="nav-link"><i class="fas fa-users"></i> Manage Users</a>
            <a href="manage-requests.php" class="nav-link active"><i class="fas fa-tasks"></i> Service Requests</a>
            <a href="reports.php" class="nav-link"><i class="fas fa-chart-line"></i> Reports</a>
            <a href="settings.php" class="nav-link"><i class="fas fa-cog"></i> Settings</a>
        </div>
    </nav>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-tasks"></i> Total Requests</h3>
            <p><?php echo $requests->num_rows; ?></p>
        </div>
    </div>

    <section class="admin-table-section">
        <h3 class="card-title"><i class="fas fa-list"></i> Service Requests</h3>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Professional</th>
                    <th>Service Name</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($request = $requests->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $request['id']; ?></td>
                        <td><?php echo htmlspecialchars($request['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($request['professional_name']); ?></td>
                        <td><?php echo htmlspecialchars($request['service_name']); ?></td>
                        <td><?php echo ucfirst($request['status']); ?></td>
                        <td><?php echo $request['created_at']; ?></td>
                        <td>
                            <a href="approve-request.php?id=<?php echo $request['id']; ?>" class="button primary">Approve</a>
                            <a href="reject-request.php?id=<?php echo $request['id']; ?>" class="button secondary">Reject</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
