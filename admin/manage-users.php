<?php
include '../includes/header.php';
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$logged_in_admin_id = $_SESSION['user_id']; // Prevent self-deletion

// Handle User Actions (Approve, Suspend, Unsuspend, Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['user_id'])) {
    $action = $_POST['action'];
    $target_user_id = (int) $_POST['user_id'];
    $log_action = "";
    $log_description = "";
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    // Prevent self-deletion
    if ($action === 'delete' && $target_user_id === $logged_in_admin_id) {
        header("Location: manage-users.php?error=self-delete");
        exit;
    }

    if ($action === 'approve') {
        $stmt = $conn->prepare("UPDATE users SET status = 'active' WHERE id = ?");
        $stmt->bind_param("i", $target_user_id);
        $stmt->execute();
        $log_action = "approve_user";
        $log_description = "Approved user with ID $target_user_id";
    } elseif ($action === 'suspend') {
        $stmt = $conn->prepare("UPDATE users SET status = 'suspended' WHERE id = ?");
        $stmt->bind_param("i", $target_user_id);
        $stmt->execute();
        $log_action = "suspend_user";
        $log_description = "Suspended user with ID $target_user_id";
    } elseif ($action === 'unsuspend') {
        $stmt = $conn->prepare("UPDATE users SET status = 'active' WHERE id = ?");
        $stmt->bind_param("i", $target_user_id);
        $stmt->execute();
        $log_action = "unsuspend_user";
        $log_description = "Unsuspended user with ID $target_user_id";
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $target_user_id);
        $stmt->execute();
        $log_action = "delete_user";
        $log_description = "Deleted user with ID $target_user_id";
    }

    // Log the action
    if (!empty($log_action)) {
        $log_stmt = $conn->prepare("INSERT INTO logs (user_id, action, description, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
        $log_stmt->bind_param("issss", $_SESSION['user_id'], $log_action, $log_description, $ip_address, $user_agent);
        $log_stmt->execute();
    }

    // Refresh the page after action
    header("Location: manage-users.php");
    exit;
}

// Fetch Users
$users_query = $conn->query("SELECT id, full_name, email, role, status FROM users ORDER BY created_at DESC");
$total_users = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$active_users = $conn->query("SELECT COUNT(*) as total FROM users WHERE status = 'active'")->fetch_assoc()['total'];
$suspended_users = $conn->query("SELECT COUNT(*) as total FROM users WHERE status = 'suspended'")->fetch_assoc()['total'];
$pending_users = $conn->query("SELECT COUNT(*) as total FROM users WHERE status = 'pending'")->fetch_assoc()['total'];
?>

<main class="main">
    <h2>Manage Users</h2>

    <?php if (isset($_GET['error']) && $_GET['error'] == 'self-delete'): ?>
        <p class="error-message">You cannot delete your own admin account.</p>
    <?php endif; ?>

    <div class="dashboard-grid">
        <div class="dashboard-card"><h3>Total Users</h3><p><?php echo $total_users; ?></p></div>
        <div class="dashboard-card"><h3>Active Users</h3><p><?php echo $active_users; ?></p></div>
        <div class="dashboard-card"><h3>Suspended Users</h3><p><?php echo $suspended_users; ?></p></div>
        <div class="dashboard-card"><h3>Pending Approvals</h3><p><?php echo $pending_users; ?></p></div>
    </div>

    <section class="user-management">
        <h3>User List</h3>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users_query->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($user['role'])); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($user['status'])); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <?php if ($user['status'] === 'pending'): ?>
                                <button type="submit" name="action" value="approve" class="button primary">Approve</button>
                            <?php elseif ($user['status'] === 'active'): ?>
                                <button type="submit" name="action" value="suspend" class="button secondary">Suspend</button>
                            <?php elseif ($user['status'] === 'suspended'): ?>
                                <button type="submit" name="action" value="unsuspend" class="button secondary">Unsuspend</button>
                            <?php endif; ?>

                            <?php if ($user['id'] !== $logged_in_admin_id): ?> <!-- Prevent Self-Deletion -->
                                <button type="submit" name="action" value="delete" class="button danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                            <?php endif; ?>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
