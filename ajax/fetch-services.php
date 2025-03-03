<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'worker') {
    echo "<li>Unauthorized</li>";
    exit;
}

$user_id = $_SESSION['user_id'];

$services_stmt = $conn->prepare("SELECT id, service_name, description FROM services WHERE user_id = ? ORDER BY created_at DESC");
$services_stmt->bind_param("i", $user_id);
$services_stmt->execute();
$services_result = $services_stmt->get_result();

if ($services_result->num_rows > 0) {
    while ($service = $services_result->fetch_assoc()) {
        echo "<li id='service-{$service['id']}' class='service-item'>
                <div class='service-info'>
                    <strong>" . htmlspecialchars($service['service_name']) . "</strong>
                    <p>" . htmlspecialchars($service['description']) . "</p>
                </div>
                <div class='service-actions'>
                    <button class='edit-button' onclick='editService(" . $service['id'] . ", `" . addslashes($service['service_name']) . "`, `" . addslashes($service['description']) . "`)'>Edit</button>
                    <button class='delete-button' onclick='deleteService(" . $service['id'] . ")'>Delete</button>
                </div>
              </li>";
    }
} else {
    echo "<li>No services listed yet</li>";
}

$services_stmt->close();
?>
