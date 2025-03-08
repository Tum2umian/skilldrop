<?php
include '../includes/header.php';
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'worker') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";

// Fetch pending invitations
$invitations_stmt = $conn->prepare("
    SELECT i.id, i.job_id, j.job_title, u.full_name AS employer_name, i.status
    FROM invitations i
    JOIN job_posts j ON i.job_id = j.id
    JOIN users u ON j.employer_id = u.id
    WHERE i.worker_id = ? AND i.status = 'pending'
    ORDER BY i.created_at DESC
");
$invitations_stmt->bind_param("i", $user_id);
$invitations_stmt->execute();
$invitations_result = $invitations_stmt->get_result();

// Handle response
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['invitation_id'], $_POST['response'])) {
    $invitation_id = intval($_POST['invitation_id']);
    $response = ($_POST['response'] === 'accept') ? 'accepted' : 'declined';

    // Update invitation status
    $update_stmt = $conn->prepare("UPDATE invitations SET status = ? WHERE id = ? AND worker_id = ?");
    $update_stmt->bind_param("sii", $response, $invitation_id, $user_id);
    
    if ($update_stmt->execute()) {
        $message = "Invitation has been " . htmlspecialchars($response) . ".";
    } else {
        $message = "Error updating invitation status.";
    }
    
    $update_stmt->close();
    
    // Refresh invitation list
    header("Location: respond-invitation.php");
    exit;
}

$invitations_stmt->close();
?>

<main class="main">
    <h2 class="page-title">Respond to Job Invitations</h2>

    <?php if (!empty($message)): ?>
        <p class="alertsuccess"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-envelope"></i> Pending Invitations</h3>
            <ul class="dashboard-list">
                <?php if ($invitations_result->num_rows > 0): ?>
                    <?php while ($invitation = $invitations_result->fetch_assoc()): ?>
                        <li class="dashboard-item">
                            <strong><?php echo htmlspecialchars($invitation['job_title']); ?></strong>
                            <br> From: <span class="employer-name"><?php echo htmlspecialchars($invitation['employer_name']); ?></span>
                            <form method="POST" class="invitation-actions">
                                <input type="hidden" name="invitation_id" value="<?php echo $invitation['id']; ?>">
                                <button type="submit" name="response" value="accept" class="button green"><i class="fas fa-check"></i> Accept</button>
                                <button type="submit" name="response" value="decline" class="button red"><i class="fas fa-times"></i> Decline</button>
                            </form>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li class="dashboard-item no-data">No pending invitations</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
