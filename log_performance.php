<?php
header("Content-Type: application/json");
include 'includes/db.php'; // Include database connection

// Get JSON data from frontend
$data = json_decode(file_get_contents("php://input"), true);
$page = $data['page'] ?? 'unknown';
$loadTime = $data['loadTime'] ?? 0;

// Insert into database
$stmt = $conn->prepare("INSERT INTO performance_logs (page, load_time, timestamp) VALUES (?, ?, NOW())");
$stmt->bind_param("sd", $page, $loadTime);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Performance logged", "loadTime" => $loadTime]);
} else {
    echo json_encode(["status" => "error", "message" => "SQL Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
