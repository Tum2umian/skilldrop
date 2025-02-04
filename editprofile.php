<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

// Fetch current user data
$stmt = $conn->prepare("SELECT full_name, email, phone, location FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Fetch current skills to display in the form
$skills_query = $conn->prepare("SELECT s.skill_name FROM skills s INNER JOIN professionals p ON s.id = p.skill_id WHERE p.user_id = ?");
$skills_query->bind_param("i", $user_id);
$skills_query->execute();
$current_skills = [];
$skills_result = $skills_query->get_result();
while ($skill = $skills_result->fetch_assoc()) {
    $current_skills[] = $skill['skill_name'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];
    $skills = $_POST['skills'];

    // Update user info in the users table
    $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, location = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $fullname, $email, $phone, $location, $user_id);
    $stmt->execute();

    // Update skills: first, delete existing skills for the user
    $delete_skills = $conn->prepare("DELETE FROM professionals WHERE user_id = ?");
    $delete_skills->bind_param("i", $user_id);
    $delete_skills->execute();

    if (!empty($skills)) {
        $skill_list = explode(",", $skills);
        foreach ($skill_list as $skill_name) {
            $skill_name = trim($skill_name);

            // Check if the skill exists in the skills table
            $skill_check = $conn->prepare("SELECT id FROM skills WHERE skill_name = ?");
            $skill_check->bind_param("s", $skill_name);
            $skill_check->execute();
            $skill_result = $skill_check->get_result();

            if ($skill_result->num_rows == 0) {
                // Insert the skill if it doesn't exist
                $insert_skill = $conn->prepare("INSERT INTO skills (skill_name) VALUES (?)");
                $insert_skill->bind_param("s", $skill_name);
                $insert_skill->execute();
                $skill_id = $insert_skill->insert_id;
            } else {
                $skill_id = $skill_result->fetch_assoc()['id'];
            }

            // Link the skill to the user in the professionals table
            $link_skill = $conn->prepare("INSERT INTO professionals (user_id, skill_id) VALUES (?, ?)");
            $link_skill->bind_param("ii", $user_id, $skill_id);
            $link_skill->execute();
        }
    }

    $message = 'Profile and skills updated successfully.';
    sleep(2);
    header("location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillDrop - Edit Profile</title>
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
            --success: #28a745;
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

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            height: 50px;
            width: auto;
        }

        .main {
            flex: 1;
            padding: 2rem;
            max-width: 800px;
            margin: 2rem auto;
            background: var(--surface);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .section-title {
            color: var(--primary);
            margin-bottom: 1.5rem;
            font-size: 1.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
        }
        .buttons {
            display: flex;
           gap: 1.5rem;
        }

        .submit-button {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary);
            color: var(--text-light);
            border: none;
            border-radius: var(--radius);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .submit-button:hover {
            background-color: var(--primary-dark);
        }

        .cancel-button {
            width: 100%;
            padding: 0.75rem;
            background-color: red;
            color: var(--text-light);
            border: none;
            border-radius: var(--radius);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .cancel-button a {
            color: var(--text-light);
            text-decoration: none;
        }
        .cancel-button:hover {
            background-color: maroon;
        }

        .success-message {
            background-color: var(--success);
            color: var(--text-light);
            padding: 1rem;
            border-radius: var(--radius);
            margin-top: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
            margin: 0 1rem;
            transition: opacity 0.3s ease;
        }

        .footer-link:hover {
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .main {
                margin: 1rem;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <img src="logo.png" alt="SkillDrop Logo" class="logo">
            <h1>Edit Profile</h1>
        </div>
    </header>

    <main class="main">
        <h2 class="section-title">
            <i class="fas fa-user-edit"></i> Update Your Information
        </h2>

        <form action="editprofile.php" method="post">
            <div class="form-group">
                <label class="form-label" for="fullname">
                    <i class="fas fa-user"></i> Full Name
                </label>
                <input type="text" 
                       id="fullname" 
                       name="fullname" 
                       class="form-input"
                       value="<?php echo htmlspecialchars($user['full_name']); ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="email">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-input"
                       value="<?php echo htmlspecialchars($user['email']); ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="phone">
                    <i class="fas fa-phone"></i> Phone Number
                </label>
                <input type="tel" 
                       id="phone" 
                       name="phone" 
                       class="form-input"
                       value="<?php echo htmlspecialchars($user['phone']); ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="location">
                    <i class="fas fa-map-marker-alt"></i> Location
                </label>
                <input type="text" 
                       id="location" 
                       name="location" 
                       class="form-input"
                       value="<?php echo htmlspecialchars($user['location']); ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="skills">
                    <i class="fas fa-tools"></i> Skills
                </label>
                <input type="text" 
                       id="skills" 
                       name="skills" 
                       class="form-input"
                       placeholder="e.g., Plumber, Electrician" 
                       value="<?php echo htmlspecialchars(implode(', ', $current_skills)); ?>">
            </div>
            <div class="buttons">
            <button type="cancel" class="cancel-button">
               <a href="dasboard.php"><i class="fas fa-cancel"></i> Cancel</a> 
            </button>
            <button type="submit" class="submit-button">
                <i class="fas fa-save"></i> Save
            </button>
            </div>

        </form>

        <?php if ($message): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <a href="dashboard.php" class="footer-link">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="contact.php" class="footer-link">
            <i class="fas fa-envelope"></i> Contact Us
        </a>
    </footer>
</body>
</html>


