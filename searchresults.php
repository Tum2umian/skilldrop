<?php
include 'db.php';

$search_term = $_GET['search'] ?? '';

// Fetch matching professionals with DISTINCT skills
$stmt = $conn->prepare("
    SELECT 
        u.id as user_id, 
        u.full_name, 
        u.location, 
        u.email,
        u.phone,
        GROUP_CONCAT(DISTINCT s.skill_name SEPARATOR ', ') as skills,
        AVG(r.rating) as avg_rating,
        COUNT(r.id) as review_count
    FROM users u 
    INNER JOIN professionals p ON u.id = p.user_id 
    INNER JOIN skills s ON p.skill_id = s.id
    LEFT JOIN reviews r ON u.id = r.professional_id
    WHERE s.skill_name LIKE ? OR u.full_name LIKE ?
    GROUP BY u.id
    ORDER BY avg_rating DESC, review_count DESC
");

$search_pattern = '%' . $search_term . '%';
$stmt->bind_param("ss", $search_pattern, $search_pattern);
$stmt->execute();
$results = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillDrop - Search Results</title>
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
            --star-color: #ffc107;
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
            justify-content: space-between;
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

        .search-container {
            background: var(--surface);
            padding: 1.5rem;
            margin: 2rem auto;
            max-width: 1200px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .search-form {
            display: flex;
            gap: 1rem;
        }

        .search-input {
            flex: 1;
            padding: 0.75rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
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

        .main {
            flex: 1;
            padding: 0 2rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .results-count {
            margin-bottom: 1.5rem;
            color: var(--text);
            font-weight: 500;
        }

        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .professional-card {
            background: var(--surface);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .professional-card:hover {
            transform: translateY(-4px);
        }

        .card-header {
            background-color: var(--primary);
            color: var(--text-light);
            padding: 1rem;
        }

        .card-content {
            padding: 1.5rem;
        }

        .professional-name {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: var(--text-light);
        }

        .rating-stars {
            color: var(--star-color);
            margin-bottom: 0.5rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .view-profile-button {
            display: inline-block;
            background-color: var(--primary);
            color: var(--text-light);
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius);
            text-decoration: none;
            margin-top: 1rem;
            transition: background-color 0.3s ease;
        }

        .view-profile-button:hover {
            background-color: var(--primary-dark);
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
                gap: 1rem;
            }

            .search-form {
                flex-direction: column;
            }

            .search-container {
                margin: 1rem;
            }

            .main {
                padding: 0 1rem 1rem;
            }

            .results-grid {
                grid-template-columns: 1fr;
            }
        }

        .no-results {
            text-align: center;
            padding: 2rem;
            background: var(--surface);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo-container">
                <img src="logo.png" alt="SkillDrop Logo" class="logo">
                <h1>Search Results</h1>
            </div>
        </div>
    </header>

    <div class="search-container">
        <form class="search-form" action="searchresults.php" method="get">
            <input type="text" 
                   name="search" 
                   class="search-input" 
                   placeholder="Search for skills or professionals..."
                   value="<?php echo htmlspecialchars($search_term); ?>">
            <button type="submit" class="search-button">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>

    <main class="main">
        <?php if ($results->num_rows > 0): ?>
            <div class="results-count">
                Found <?php echo $results->num_rows; ?> professional<?php echo $results->num_rows > 1 ? 's' : ''; ?>
            </div>
            
            <div class="results-grid">
                <?php while ($row = $results->fetch_assoc()): ?>
                    <div class="professional-card">
                        <div class="card-header">
                            <h2 class="professional-name">
                                <i class="fas fa-user"></i> 
                                <?php echo htmlspecialchars($row['full_name']); ?>
                            </h2>
                            <div class="rating-stars">
                                <?php
                                $rating = round($row['avg_rating']);
                                for ($i = 0; $i < 5; $i++) {
                                    echo $i < $rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                }
                                echo " (" . $row['review_count'] . " reviews)";
                                ?>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo htmlspecialchars($row['location']); ?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-tools"></i>
                                <span><?php echo htmlspecialchars($row['skills']); ?></span>
                            </div>
                            <a href="professionalprofile.php?id=<?php echo $row['user_id']; ?>" 
                               class="view-profile-button">
                                <i class="fas fa-user-circle"></i> View Profile
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-results">
                <i class="fas fa-search fa-3x" style="color: var(--gray-300); margin-bottom: 1rem;"></i>
                <h2>No professionals found</h2>
                <p>Try different search terms or browse all available services.</p>
            </div>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <a href="dashboard.php" class="footer-link">Dashboard</a>
        <a href="contact.php" class="footer-link">Contact Us</a>
    </footer>
</body>
</html>


