<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'worker') {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$log_description = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $location = trim($_POST['location']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;

    // ✅ Prepare Update Statement
    $update_stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, location = ?" . ($password ? ", password = ?" : "") . " WHERE id = ?");
    
    if ($password) {
        $update_stmt->bind_param("sssssi", $full_name, $email, $phone, $location, $password, $user_id);
    } else {
        $update_stmt->bind_param("ssssi", $full_name, $email, $phone, $location, $user_id);
    }

    if ($update_stmt->execute()) {
        $_SESSION['user_name'] = $full_name;
        $log_description = "Profile updated: Name, Email, Phone, Location" . ($password ? ", Password changed" : "");
    }

    // ✅ Handle Profile Image Upload
    if (!empty($_FILES['profile_image']['name'])) {
        $target_dir = "../uploads/profile_images/";
        $file_name = basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . time() . "_" . $file_name;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $file_path = str_replace("../", "", $target_file);
            $conn->query("UPDATE users SET profile_image = '$file_path' WHERE id = $user_id");
            $log_description .= ", Profile Picture Updated";
        }
    }

    // ✅ Log the changes
    if (!empty($log_description)) {
        $ip_address = $_SERVER['REMOTE_ADDR']; // Capture IP Address
        $user_agent = $_SERVER['HTTP_USER_AGENT']; // Capture User-Agent

        $log_stmt = $conn->prepare("INSERT INTO logs (user_id, action, description, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
        $action = "Profile Update";
        $log_stmt->bind_param("issss", $user_id, $action, $log_description, $ip_address, $user_agent);
        $log_stmt->execute();
    }

    echo json_encode(["status" => "success", "message" => "Profile updated successfully"]);
    exit;
}
?>
