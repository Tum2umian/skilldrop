<?php
include '../includes/header.php';
include '../includes/db.php';


// Ensure only admins can access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$logged_in_admin_id = $_SESSION['user_id']; // Prevent self-deletion

// Handle User Actions (Approve, Suspend, Unsuspend, Delete, Change Role)
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

    if ($action === 'approve' && isset($_POST['role'])) {
        $new_role = $_POST['role'];
        $stmt = $conn->prepare("UPDATE users SET status = 'active', role = ? WHERE id = ?");
        $stmt->bind_param("si", $new_role, $target_user_id);
        $stmt->execute();
        $log_action = "approve_user";
        $log_description = "Approved user ID $target_user_id as $new_role";
    } elseif ($action === 'suspend') {
        $stmt = $conn->prepare("UPDATE users SET status = 'suspended' WHERE id = ?");
        $stmt->bind_param("i", $target_user_id);
        $stmt->execute();
        $log_action = "suspend_user";
        $log_description = "Suspended user ID $target_user_id";
    } elseif ($action === 'unsuspend') {
        $stmt = $conn->prepare("UPDATE users SET status = 'active' WHERE id = ?");
        $stmt->bind_param("i", $target_user_id);
        $stmt->execute();
        $log_action = "unsuspend_user";
        $log_description = "Unsuspended user ID $target_user_id";
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $target_user_id);
        $stmt->execute();
        $log_action = "delete_user";
        $log_description = "Deleted user ID $target_user_id";
    } elseif ($action === 'change_role' && isset($_POST['new_role'])) {
        $new_role = $_POST['new_role'];

        // Prevent self role change to avoid locking admin out
        if ($target_user_id === $logged_in_admin_id) {
            header("Location: manage-users.php?error=self-role-change");
            exit;
        }

        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $new_role, $target_user_id);
        $stmt->execute();
        $log_action = "change_role";
        $log_description = "Changed role of user ID $target_user_id to $new_role";
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
?>

<main class="main">
    <h2>Manage Users</h2>

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
                                <select name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="worker">Worker</option>
                                    <option value="employer">Employer</option>
                                    <option value="admin">Admin</option>
                                </select>
                                <button type="submit" name="action" value="approve" class="button primary">Approve</button>
                            <?php elseif ($user['status'] === 'active'): ?>
                                <button type="submit" name="action" value="suspend" class="button secondary">Suspend</button>
                            <?php elseif ($user['status'] === 'suspended'): ?>
                                <button type="submit" name="action" value="unsuspend" class="button secondary">Unsuspend</button>
                            <?php endif; ?>

                            <button type="button" class="role-change-btn button" data-user-id="<?php echo $user['id']; ?>" data-current-role="<?php echo $user['role']; ?>">Change Role</button>

                            <?php if ($user['id'] !== $logged_in_admin_id): ?>
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

<!-- Role Change Modal -->
<div id="roleModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3>Change User Role</h3>
        <form id="roleChangeForm" method="POST">
            <input type="hidden" name="user_id" id="modalUserId">
            <label for="newRole">Select New Role:</label>
            <select name="new_role" id="newRole">
                <option value="worker">Worker</option>
                <option value="employer">Employer</option>
                <option value="admin">Admin</option>
                <option value="">Pending (No Role)</option>
            </select>
            <button type="submit" name="action" value="change_role" class="button primary">Update Role</button>
        </form>
    </div>
</div>

<script src="../assets/js/change-role.js"></script>


<?php include '../includes/footer.php'; ?>
