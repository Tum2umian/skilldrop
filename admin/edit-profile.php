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
$stmt = $conn->prepare("SELECT full_name, email, phone, profile_image FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$profile_image = !empty($user['profile_image']) ? "../" . $user['profile_image'] : '../uploads/profile_images/default.svg';
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Check if passwords match
    if (!empty($password) && $password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        // Update user details
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, password = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $full_name, $email, $phone, $hashed_password, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, phone = ? WHERE id = ?");
            $stmt->bind_param("sssi", $full_name, $email, $phone, $user_id);
        }
        
        if ($stmt->execute()) {
            $_SESSION['user_name'] = $full_name;
            $message = "Profile updated successfully!";
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
