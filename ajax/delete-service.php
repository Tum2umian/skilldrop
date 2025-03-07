<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'worker') {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['service_id'])) {
    $service_id = intval($_POST['service_id']);

    // Ensure the service belongs to the logged-in worker
    $delete_stmt = $conn->prepare("DELETE FROM services WHERE id = ? AND user_id = ?");
    $delete_stmt->bind_param("ii", $service_id, $user_id);

    if ($delete_stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Service deleted"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete service"]);
    }

    $delete_stmt->close();
    exit;
}
?>
