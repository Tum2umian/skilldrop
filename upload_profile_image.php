<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];

    // Handle file upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/profile_images/';
        $file_name = $user_id . '_' . basename($_FILES['profile_image']['name']);
        $file_path = $upload_dir . $file_name;

        // Create directory if not exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $file_path)) {
            // Save path to the database
            $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
            $stmt->bind_param("si", $file_path, $user_id);
            $stmt->execute();

            $_SESSION['success'] = "Profile image updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to upload profile image.";
        }
    } else {
        $_SESSION['error'] = "No image uploaded or an error occurred.";
    }

    header("Location: dashboard.php");
    exit;
}
?>
