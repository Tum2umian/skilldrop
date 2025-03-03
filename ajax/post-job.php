<?php
// post-job.php
header('Content-Type: application/json');
session_start();
include '../includes/db.php'; // Adjust path as necessary

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Check if the user is logged in (and optionally verify employer role)
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

// Retrieve POST data
$title       = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$location    = $_POST['location'] ?? '';
$employer_id = $_SESSION['user_id']; // Assuming employer's ID is stored here

if (empty($title) || empty($description) || empty($location)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

// Insert the new job (assuming a table "jobs" exists)
$stmt = $conn->prepare("INSERT INTO jobs (employer_id, title, description, location, posted_at) VALUES (?, ?, ?, ?, NOW())");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error: '.$conn->error]);
    exit;
}
$stmt->bind_param("isss", $employer_id, $title, $description, $location);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Job posted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to post job.']);
}
$stmt->close();
$conn->close();
?>
