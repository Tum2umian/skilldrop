<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$user_id = isset($data['user_id']) ? (int) $data['user_id'] : null;
$action = isset($data['action']) ? $data['action'] : null;

if (!$user_id || !in_array($action, ['approve', 'suspend', 'unsuspend', 'delete'])) {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit;
}

// Approve user
if ($action === 'approve') {
    $stmt = $conn->prepare("UPDATE users SET status = 'active' WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    echo json_encode(["status" => "success"]);
}
?>
