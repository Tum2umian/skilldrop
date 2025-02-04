<?php
session_start();
include 'db.php';

$professional_id = $_GET['id'] ?? null;
$review_message = '';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch professional details
$profile_query = $conn->prepare("SELECT u.full_name, u.email, u.phone, u.location, u.profile_image, 
                                GROUP_CONCAT(s.skill_name SEPARATOR ', ') AS skills 
                                FROM users u 
                                INNER JOIN professionals p ON u.id = p.user_id 
                                INNER JOIN skills s ON p.skill_id = s.id 
                                WHERE u.id = ? 
                                GROUP BY u.id");
$profile_query->bind_param("i", $professional_id);
$profile_query->execute();
$profile = $profile_query->get_result()->fetch_assoc();
$profile_picture = !empty($profile['profile_image']) ? $profile['profile_image'] : 'uploads/profile_images/default.svg';


// Handle review submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['review'])) {
    $review_text = $_POST['review'];
    $rating = (int)$_POST['rating'];
    
    $review_stmt = $conn->prepare("INSERT INTO reviews (professional_id, user_id, review_text, rating) VALUES (?, ?, ?, ?)");
    $review_stmt->bind_param("iisi", $professional_id, $_SESSION['user_id'], $review_text, $rating);
    $review_stmt->execute();
    $review_message = 'Review submitted successfully.';
}

// Fetch professional reviews
$reviews_query = $conn->prepare("SELECT r.review_text, r.rating, r.created_at, u.full_name as reviewer_name 
                                FROM reviews r 
                                INNER JOIN users u ON r.user_id = u.id 
                                WHERE r.professional_id = ? 
                                ORDER BY r.created_at DESC");
$reviews_query->bind_param("i", $professional_id);
$reviews_query->execute();
$reviews = $reviews_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillDrop - Professional Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDDOFf3CEvnjrcTpXIa2lLV6sRuV3GpUoI&libraries=places"></script>

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
            gap: 1rem;
        }

        .logo {
            height: 50px;
            width: auto;
        }

        .main {
            flex: 1;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .profile-card {
            background: var(--surface);
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            background-color: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-size: 2rem;
        }

        .profile-info {
            flex: 1;
        }

        .profile-name {
            font-size: 1.5rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .profile-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .map-container {
            height: 300px;
            margin-top: 1rem;
            border-radius: var(--radius);
            overflow: hidden;
        }

        #map {
            width: 100%;
            height: 100%;
        }

        .location-details {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
        }

        .distance-info {
            color: var(--text);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .reviews-section {
            margin-top: 2rem;
        }

        .section-title {
            color: var(--primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .review-card {
            background: var(--surface);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 1rem;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .reviewer-name {
            font-weight: 500;
        }

        .rating {
            color: var(--star-color);
        }

        .review-form {
            background: var(--surface);
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-top: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .rating-input {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .submit-button {
            background-color: var(--primary);
            color: var(--text-light);
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--radius);
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .submit-button:hover {
            background-color: var(--primary-dark);
        }

        .success-message {
            background-color: var(--primary-light);
            color: var(--text-light);
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
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
            margin: 0 0.5rem;
            transition: opacity 0.3s ease;
        }

        .footer-link:hover {
            opacity: 0.8;
        }
        .profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--gray-200);
    box-shadow: var(--shadow);
}

.profile-avatar img.profile-picture {
    width: 100%;
    height: 100%;
    object-fit: cover;
}


        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .profile-details {
                grid-template-columns: 1fr;
            }

            .main {
                padding: 1rem;
            }

            .map-container {
                height: 250px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
           <a href="dashboard.php"> <img src="logo.png" alt="SkillDrop Logo" class="logo"> </a>
            <h1>Professional Profile</h1>
        </div>
    </header>

    <main class="main">
        <?php if ($profile): ?>
            <div class="profile-card">
                <div class="profile-header">
                <div class="profile-avatar">
    <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-picture">
</div>

                    <div class="profile-info">
                        <h2 class="profile-name"><?php echo htmlspecialchars($profile['full_name']); ?></h2>
                        <div class="profile-details">
                            <div class="detail-item">
                                <i class="fas fa-envelope"></i>
                                <span><?php echo htmlspecialchars($profile['email']); ?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-phone"></i>
                                <span><?php echo htmlspecialchars($profile['phone']); ?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo htmlspecialchars($profile['location']); ?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-tools"></i>
                                <span><?php echo htmlspecialchars($profile['skills']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="map-container">
                    <div id="map"></div>
                </div>
                <div class="location-details">
                    <div class="distance-info">
                        <i class="fas fa-route"></i>
                        <span id="distance">Calculating distance...</span>
                    </div>
                </div>
            </div>

            <div class="reviews-section">
                <h3 class="section-title">
                    <i class="fas fa-star"></i> Reviews
                </h3>
                <?php while ($review = $reviews->fetch_assoc()): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <span class="reviewer-name"><?php echo htmlspecialchars($review['reviewer_name']); ?></span>
                            <span class="rating">
                                <?php for ($i = 0; $i < $review['rating']; $i++): ?>
                                    <i class="fas fa-star"></i>
                                <?php endfor; ?>
                            </span>
                        </div>
                        <p><?php echo htmlspecialchars($review['review_text']); ?></p>
                        <small><?php echo date('F j, Y', strtotime($review['created_at'])); ?></small>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="review-form">
                <h3 class="section-title">
                    <i class="fas fa-pen"></i> Write a Review
                </h3>
                
                <?php if ($review_message): ?>
                    <div class="success-message">
                        <?php echo htmlspecialchars($review_message); ?>
                    </div>
                <?php endif; ?>

                <form action="professionalprofile.php?id=<?php echo $professional_id; ?>" method="post">
                    <div class="form-group">
                        <label class="form-label" for="rating">Rating</label>
                        <div class="rating-input">
                            <input type="number" id="rating" name="rating" min="1" max="5" required>
                            <span>(1-5 stars)</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="review">Your Review</label>
                        <textarea id="review" name="review" class="form-input" rows="4" required></textarea>
                    </div>

                    <button type="submit" class="submit-button">
                        <i class="fas fa-paper-plane"></i> Submit Review
                    </button>
                </form>
            </div>
        <?php else: ?>
            <div class="profile-card">
                <p>Professional not found.</p>
            </div>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <a href="dashboard.php" class="footer-link">Dashboard</a> | 
        <a href="contact.php" class="footer-link">Contact Us</a>
    </footer>

    <script>
        let map;
        let userLocation;
        let professionalLocation;

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: { lat: 0, lng: 0 },
                styles: [
                    {
                        featureType: "poi",
                        elementType: "labels",
                        stylers: [{ visibility: "off" }]
                    }
                ]
            });

            const geocoder = new google.maps.Geocoder();
            const professionalAddress = "<?php echo htmlspecialchars($profile['location']); ?>";
            
            geocoder.geocode({ address: professionalAddress }, (results, status) => {
                if (status === 'OK') {
                    professionalLocation = results[0].geometry.location;
                    
                    new google.maps.Marker({
                        map: map,
                        position: professionalLocation,
                        title: "<?php echo htmlspecialchars($profile['full_name']); ?>",
                        icon: {
                            url: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
                        }
                    });

                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                userLocation = {
                                    lat: position.coords.latitude,
                                    lng: position.coords.longitude
                                };

                                new google.maps.Marker({
                                    map: map,
                                    position: userLocation,
                                    title: "Your Location",
                                    icon: {
                                        url: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
                                    }
                                });

                                const bounds = new google.maps.LatLngBounds();
                                bounds.extend(professionalLocation);
                                bounds.extend(userLocation);
                                map.fitBounds(bounds);

                                calculateDistance(userLocation, professionalLocation);
                            },
                            (error) => {
                                console.error("Error getting user location:", error);
                                map.setCenter(professionalLocation);
                            }
                        );
                    } else {
                        map.setCenter(professionalLocation);
                    }
                } else {
                    console.error('Geocode was not successful:', status);
                }
            });
        }

        function calculateDistance(from, to) {
            const service = new google.maps.DistanceMatrixService();
            service.getDistanceMatrix(
                {
                    origins: [from],
                    destinations: [to],
                    travelMode: google.maps.TravelMode.DRIVING,
                    unitSystem: google.maps.UnitSystem.METRIC
                },
                (response, status) => {
                    if (status === 'OK') {
                        const distance = response.rows[0].elements[0].distance.text;
                        const duration = response.rows[0].elements[0].duration.text;
                        document.getElementById('distance').innerHTML = 
                            `${distance} away (${duration} by car)`;
                    }
                }
            );
        }

        window.onload = initMap;
    </script>
</body>
</html>
