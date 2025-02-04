<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Fetch user info
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Profile image
$profile_image = !empty($user['profile_image']) ? $user['profile_image'] : 'uploads/profile_images/default.svg';

// Fetch user skills
$skills_query = $conn->prepare("SELECT s.skill_name FROM skills s INNER JOIN professionals p ON s.id = p.skill_id WHERE p.user_id = ?");
$skills_query->bind_param("i", $user_id);
$skills_query->execute();
$skills_result = $skills_query->get_result();

// Fetch reviews
$reviews_query = $conn->prepare("SELECT review_text, rating FROM reviews WHERE professional_id = ?");
$reviews_query->bind_param("i", $user_id);
$reviews_query->execute();
$reviews_result = $reviews_query->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillDrop - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2E7D32;
            --primary-dark: #1B5E20;
            --primary-light: #4CAF50;
            --secondary: #1976D2;
            --background: #f8f9fa;
            --surface: #ffffff;
            --text: #333333;
            --text-light: #ffffff;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --shadow: 0 2px 4px rgba(0,0,0,0.1);
            --radius: 8px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: var(--text);
            background-color: var(--background);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background-color: var(--primary);
            color: var(--text-light);
            padding: 1rem 0;
            box-shadow: var(--shadow);
        }

        .user-welcome {
    display: flex;
    align-items: center; /* Centers items vertically */
    gap: 0.5rem; /* Adds space between the image and the text */
}

.profile-icon {
    width: 40px; /* Adjust size as needed */
    height: 40px; /* Maintain a square aspect ratio */
    border-radius: 50%; /* Makes the image circular */
    object-fit: cover; /* Ensures the image scales to fit within the circle */
    border: 2px solid var(--primary-light); /* Optional border for aesthetics */
    cursor: pointer; /* Indicates it's clickable */
}


        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            height: 50px;
            width: auto;
        }

        .nav-links {
            background-color: var(--primary-dark);
            padding: 0.75rem 0;
        }

        .nav-links-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            gap: 2rem;
            justify-content: center;
        }

        .nav-link {
            color: var(--text-light);
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s ease;
        }

        .nav-link:hover {
            opacity: 0.8;
        }

        .main {
            flex: 1;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .dashboard-card {
            background: var(--surface);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .card-title {
            color: var(--primary);
            margin-bottom: 1rem;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .search-form {
            background: var(--surface);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius);
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .search-button {
            background-color: var(--primary);
            color: var(--text-light);
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .search-button:hover {
            background-color: var(--primary-dark);
        }

        .reviews-section {
            background: var(--surface);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .review-item {
            padding: 1rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .review-item:last-child {
            border-bottom: none;
        }

        .rating {
            color: #ffc107;
            margin-left: 0.5rem;
        }

        .footer {
            background-color: var(--primary);
            color: var(--text-light);
            padding: 1rem 0;
            margin-top: 2rem;
            text-align: center;
        }

        .footer-link {
            color: var(--text-light);
            text-decoration: none;
            transition: opacity 0.3s ease;
        }

        .footer-link:hover {
            opacity: 0.8;
        }
        .profile-picture-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.profile-picture-large {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--primary-light);
    box-shadow: var(--shadow);
}

.upload-button {
    background-color: var(--primary);
    color: var(--text-light);
    padding: 0.5rem 1rem;
    border: none;
    border-radius: var(--radius);
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.3s ease;
}

.upload-button:hover {
    background-color: var(--primary-dark);
}

/* Responsive Design */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 1rem;
    }

    .nav-links-container {
        flex-direction: column;
        gap: 1rem;
    }

    .dashboard-grid {
        grid-template-columns: 1fr; /* Single-column layout on small screens */
    }

    .profile-picture-large {
        width: 120px; /* Smaller profile picture on small screens */
        height: 120px;
    }

    .upload-button {
        width: 100%; /* Button stretches to fit container width */
        text-align: center;
    }
}

        @media (max-width: 768px) {
            .header-content {
        flex-direction: column;
        text-align: center;
    }

    .profile-picture-large {
        width: 100px; /* Smaller profile picture on extra small screens */
        height: 100px;
    }

            .nav-links-container {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<header class="header">
    <div class="header-content">
        <div class="logo-container">
            <img src="logo.png" alt="SkillDrop Logo" class="logo">
            <h1>SkillDrop</h1>
        </div>
        <div class="user-welcome">
            <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Icon" 
                 class="profile-icon" id="profileIcon" onclick="openImageUpload()">
            Welcome, <?php echo htmlspecialchars($user_name); ?>!
        </div>
    </div>
</header>

<!-- Hidden Form for Image Upload -->
<form id="imageUploadForm" action="upload_profile_image.php" method="post" enctype="multipart/form-data" style="display: none;">
    <input type="file" name="profile_image" id="profileImageInput" accept="image/*" onchange="submitImageForm()">
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
</form>

<script>
function openImageUpload() {
    document.getElementById('profileImageInput').click();
}

function submitImageForm() {
    document.getElementById('imageUploadForm').submit();
}
</script>

    <nav class="nav-links">
        <div class="nav-links-container">
            <a href="editprofile.php" class="nav-link">
                <i class="fas fa-user-edit"></i> Edit Profile
            </a>
            <a href="logout.php" class="nav-link">
                <i class="fas fa-sign-out-alt"></i> Log out
            </a>
        </div>
    </nav>

    <main class="main">
        <form class="search-form" action="searchresults.php" method="get">
            <input type="text" name="search" class="search-input" 
                   placeholder="Search for a service (e.g., Plumber, Electrician)">
            <button type="submit" class="search-button">
                <i class="fas fa-search"></i> Search
            </button>
        </form>

        <div class="dashboard-grid">
    <!-- Profile Picture Card -->
    <div class="dashboard-card profile-picture-card">
        <h3 class="card-title">
            <i class="fas fa-image"></i> Profile Picture
        </h3>
        <div class="profile-picture-container">
            <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture" 
                 class="profile-picture-large" id="profilePicture">
            <button class="upload-button" onclick="openImageUpload()">Change Picture</button>
        </div>
    </div>

    <!-- Your Info Card -->
    <div class="dashboard-card">
        <h3 class="card-title">
            <i class="fas fa-user"></i> Your Info
        </h3>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($user['location']); ?></p>
    </div>

    <!-- Skills Card -->
    <div class="dashboard-card">
        <h3 class="card-title">
            <i class="fas fa-tools"></i> Skills
        </h3>
        <p>
            <?php 
            $skills = [];
            while ($skill = $skills_result->fetch_assoc()) {
                $skills[] = htmlspecialchars($skill['skill_name']);
            }
            echo implode(', ', $skills);
            ?>
        </p>
    </div>
</div>


        <div class="reviews-section">
            <h3 class="card-title">
                <i class="fas fa-star"></i> Reviews
            </h3>
            <?php while ($review = $reviews_result->fetch_assoc()): ?>
                <div class="review-item">
                    <p><?php echo htmlspecialchars($review['review_text']); ?>
                        <span class="rating">
                            <?php
                            for ($i = 0; $i < $review['rating']; $i++) {
                                echo '<i class="fas fa-star"></i>';
                            }
                            ?>
                        </span>
                    </p>
                </div>
            <?php endwhile; ?>
        </div>
    </main>

    <footer class="footer">
        <a href="contact.php" class="footer-link">Contact Us</a>
    </footer>
</body>
</html>


