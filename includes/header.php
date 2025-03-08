<?php
session_start();

// Detect the directory depth dynamically
$is_auth = strpos($_SERVER['SCRIPT_NAME'], '/auth/') !== false;
$is_admin = strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false;
$is_worker = strpos($_SERVER['SCRIPT_NAME'], '/workers/') !== false;
$is_employer = strpos($_SERVER['SCRIPT_NAME'], '/employers/') !== false;

// Determine base URL dynamically
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$base_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';

// Adjust base path correctly
if ($is_auth || $is_admin || $is_worker || $is_employer) {
    $base_path = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/') . '/';
}

$base_url = $protocol . $host . $base_path;

// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$user_role = $is_logged_in ? $_SESSION['user_role'] : null;

// Define correct dashboard and edit profile links
$dashboard_link = $base_url . 'dashboard.php';
$edit_profile_link = $base_url . 'edit-profile.php';

if ($is_logged_in) {
    if ($user_role === 'admin') {
        $dashboard_link = $base_url . "admin/dashboard.php";
        $edit_profile_link = $base_url . "admin/edit-profile.php";
    } elseif ($user_role === 'worker') {
        $dashboard_link = $base_url . "workers/dashboard.php";
        $edit_profile_link = $base_url . "workers/edit-profile.php";
    } elseif ($user_role === 'employer') {
        $dashboard_link = $base_url . "employers/dashboard.php";
        $edit_profile_link = $base_url . "employers/edit-profile.php";
    }
}

// Set logout URL dynamically based on directory
$logout_url = $base_url . "auth/logout.php";

// Ensure profile image path is properly formatted
if ($is_logged_in && !empty($_SESSION['profile_image'])) {
  $profile_image_path = $_SESSION['profile_image'];

  // If the path is already a full URL, use it directly
  if (filter_var($profile_image_path, FILTER_VALIDATE_URL)) {
      $profile_image = $profile_image_path;
  } else {
      // Normalize path to remove ../ and extra slashes
      $normalized_path = str_replace(['../', '//'], '/', $profile_image_path);
      
      // Ensure no double slashes when appending base_url
      $profile_image = rtrim($base_url, '/') . '/' . ltrim($normalized_path, '/');
  }
} else {
  // Use default profile image
  $profile_image = rtrim($base_url, '/') . '/uploads/profile_images/default.svg';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillDrop</title>

    <!-- External CSS Files -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/main.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/forms.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/dashboard.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<header class="header">
    <div class="header-content container">
        <div class="logo-container">
            <a href="<?php echo $base_url; ?>index.php">
                <img src="<?php echo $base_url; ?>assets/images/logo.png" alt="SkillDrop Logo" class="logo">
            </a>
            <h1>SkillDrop</h1>
        </div>

        <nav class="nav">
            <ul class="nav-list">
                <li><a href="<?php echo $base_url; ?>index.php" class="nav-link">Home</a></li>
                <li><a href="<?php echo $base_url; ?>about-us.php" class="nav-link">About</a></li>
                <li><a href="<?php echo $base_url; ?>contact-us.php" class="nav-link">Contact</a></li>
                
                <?php if ($is_logged_in): ?>
                    <!-- User Profile Dropdown -->
                    <li class="user-profile">
                        <img src="<?php echo htmlspecialchars($profile_image); ?>" 
                            alt="Profile Picture" 
                            class="profile-icon" 
                            id="profileIcon" 
                            onclick="toggleProfileMenu()">

                        <div class="profile-dropdown" id="profileDropdown">
                            <p>Logged in as: <strong><?php echo ucfirst($user_role); ?></strong></p>

                            <a href="<?php echo $dashboard_link; ?>" class="dropdown-link">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                            <a href="<?php echo $edit_profile_link; ?>" class="dropdown-link">
                                <i class="fas fa-user-edit"></i> Edit Profile
                            </a>
                            <a href="<?php echo $logout_url; ?>" class="dropdown-link">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </li>
                <?php else: ?>
                    <li><a href="<?php echo $base_url; ?>auth/login.php" class="nav-link">Login</a></li>
                    <li><a href="<?php echo $base_url; ?>auth/register.php" class="nav-link">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<!-- Hidden Profile Image Upload Form -->
<?php if ($is_logged_in): ?>
    <form id="imageUploadForm" action="<?php echo $base_url; ?>upload_profile_image.php" method="post" 
          enctype="multipart/form-data" style="display: none;">
        <input type="file" name="profile_image" id="profileImageInput" accept="image/*" 
               onchange="submitImageForm()">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
    </form>

    <script>
        function toggleProfileMenu() {
            document.getElementById("profileDropdown").classList.toggle("show");
        }

        function openImageUpload() {
            document.getElementById('profileImageInput').click();
        }

        function submitImageForm() {
            document.getElementById('imageUploadForm').submit();
        }

        // Close dropdown if clicked outside
        $(document).click(function(event) {
            if (!$(event.target).closest("#profileIcon, #profileDropdown").length) {
                $("#profileDropdown").removeClass("show");
            }
        });
    </script>
<?php endif; ?>
