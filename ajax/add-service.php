<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'worker') {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
    $service_name = trim($_POST['service_name']);
    $description = trim($_POST['description']);

    if (empty($service_name) || empty($description)) {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit;
    }

    if ($service_id > 0) {
        // Update existing service
        $stmt = $conn->prepare("UPDATE services SET service_name = ?, description = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssii", $service_name, $description, $service_id, $user_id);
    } else {
        // Add new service
        $stmt = $conn->prepare("INSERT INTO services (user_id, service_name, description) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $service_name, $description);
    }

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Service saved successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error"]);
    }

    $stmt->close();
    exit;
}
?>
