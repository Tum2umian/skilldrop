<?php
header("Content-Type: application/json");
include 'includes/db.php'; // Include database connection

// Get JSON data from frontend
$data = json_decode(file_get_contents("php://input"), true);
$button = $data['button'] ?? 'Unknown';

// Insert into database
$stmt = $conn->prepare("INSERT INTO button_clicks (button_name, timestamp) VALUES (?, NOW())");
$stmt->bind_param("s", $button);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Click logged", "button" => $button]);
} else {
    echo json_encode(["status" => "error", "message" => "SQL Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
