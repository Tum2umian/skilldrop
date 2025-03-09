<?php
header("Content-Type: application/json");
include 'includes/db.php'; // Include database connection

// Get JSON data from frontend
$data = json_decode(file_get_contents("php://input"), true);
$page = $data['page'] ?? 'unknown';
$timeSpent = $data['timeSpent'] ?? 0;

// Insert into database
$stmt = $conn->prepare("INSERT INTO session_logs (page, time_spent, timestamp) VALUES (?, ?, NOW())");
$stmt->bind_param("sd", $page, $timeSpent);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Session logged", "timeSpent" => $timeSpent]);
} else {
    echo json_encode(["status" => "error", "message" => "SQL Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
