<?php
include '../includes/header.php';
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// Fetch user details
$stmt = $conn->prepare("SELECT full_name, email, phone, location, profile_image, password FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$profile_image = !empty($user['profile_image']) ? "../" . $user['profile_image'] : '../uploads/profile_images/default.svg';
$message = "";
$updates = []; // Store changes for logging

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $location = trim($_POST['location']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Check and compare values for logging
    if ($full_name !== $user['full_name']) {
        $updates[] = "Full Name changed from '{$user['full_name']}' to '$full_name'";
    }
    if ($email !== $user['email']) {
        $updates[] = "Email changed from '{$user['email']}' to '$email'";
    }
    if ($phone !== $user['phone']) {
        $updates[] = "Phone changed from '{$user['phone']}' to '$phone'";
    }
    if ($location !== $user['location']) {
        $updates[] = "Location changed from '{$user['location']}' to '$location'";
    }

    // Validate password change
    $update_password = false;
    if (!empty($password)) {
        if ($password !== $confirm_password) {
            $message = "Passwords do not match!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            if (!password_verify($password, $user['password'])) {
                $updates[] = "Password was updated";
                $update_password = true;
            }
        }
    }

    // Perform update if there are changes
    if (!empty($updates)) {
        if ($update_password) {
            $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, location = ?, password = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $full_name, $email, $phone, $location, $hashed_password, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, location = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $full_name, $email, $phone, $location, $user_id);
        }

        if ($stmt->execute()) {
            $_SESSION['user_name'] = $full_name;
            $message = "Profile updated successfully!";

            // Log updates
            $log_description = implode(", ", $updates);
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $log_stmt = $conn->prepare("INSERT INTO logs (user_id, action, description, ip_address, user_agent) VALUES (?, 'profile_update', ?, ?, ?)");
            $log_stmt->bind_param("isss", $user_id, $log_description, $ip_address, $user_agent);
            $log_stmt->execute();
        } else {
            $message = "Error updating profile!";
        }
        $stmt->close();
    }

    // Handle Profile Picture Upload
    if (!empty($_FILES['profile_image']['name'])) {
        $target_dir = "../uploads/profile_images/";
        $file_name = basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . time() . "_" . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png", "gif"];

        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $profile_update = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
                $profile_update->bind_param("si", $target_file, $user_id);
                if ($profile_update->execute()) {
                    $_SESSION['profile_image'] = $target_file;
                    $profile_image = "../" . $target_file;
                    $message = "Profile picture updated!";
                    
                    // Log profile image change
                    $log_description = "Profile picture updated";
                    $log_stmt = $conn->prepare("INSERT INTO logs (user_id, action, description, ip_address, user_agent) VALUES (?, 'profile_update', ?, ?, ?)");
                    $log_stmt->bind_param("isss", $user_id, $log_description, $ip_address, $user_agent);
                    $log_stmt->execute();
                }
                $profile_update->close();
            }
        } else {
            $message = "Invalid file format! Only JPG, JPEG, PNG, GIF allowed.";
        }
    }
}
?>

<main class="main container">
    <h2>Edit Profile</h2>
    
    <?php if (!empty($message)): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    
    <form action="edit-profile.php" method="POST" enctype="multipart/form-data" class="form">
        
        <!-- Profile Picture -->
        <div class="form-group profile-picture-container">
            <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image" class="profile-picture-large">
            <input type="file" name="profile_image" id="profileImageInput">
        </div>

        <!-- Full Name -->
        <div class="form-group">
            <label for="full_name" class="form-label">Full Name:</label>
            <input type="text" name="full_name" id="full_name" class="form-input" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email" class="form-input" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <!-- Phone -->
        <div class="form-group">
            <label for="phone" class="form-label">Phone Number:</label>
            <input type="text" name="phone" id="phone" class="form-input" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
        </div>

        <!-- Location -->
        <div class="form-group">
            <label for="location" class="form-label">Location:</label>
            <input type="text" name="location" id="location" class="form-input" value="<?php echo htmlspecialchars($user['location']); ?>" required>
        </div>

        <!-- Password (Optional) -->
        <div class="form-group">
            <label for="password" class="form-label">New Password (Leave blank to keep current):</label>
            <input type="password" name="password" id="password" class="form-input">
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="confirm_password" class="form-label">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-input">
        </div>

        <button type="submit" class="submit-button">Update Profile</button>
    </form>
</main>

<?php include '../includes/footer.php'; ?>
