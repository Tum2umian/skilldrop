<?php
// Directory: /test/test_workflows.php
include '../includes/db.php';

// Load test workflow results
$log_file = "logs/test_workflows_log.txt";
$test_results = [];

if (file_exists($log_file)) {
    $lines = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $test_results[] = json_decode($line, true);
    }
}

// Workflows to display
$workflows = [
    "User Registration" => "User fills signup form, submits, and is saved in DB with unique email.",
    "User Login" => "User enters correct credentials and is redirected to their dashboard.",
    "Post Job" => "Employer fills the job posting form and submits successfully.",
    "Apply to Job" => "Worker clicks apply on a posted job and application is recorded.",
    "Invite Worker" => "Employer sends an invite to worker to apply for a job.",
    "Edit Profile" => "Worker updates profile info including uploading a new profile picture."
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Skill Drop Testing Dashboard | Test Workflows</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: #f8f9fa; font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
        .header { background-color: #2E7D32; color: white; padding: 1rem; text-align: center; }
        .header-nav { margin-top: 0.5rem; }
        .header-nav a { color: white; margin: 0 1rem; text-decoration: none; font-weight: bold; }
        .main-wrapper { padding: 2rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 10px; border: 1px solid #dee2e6; text-align: left; }
        th { background: #2E7D32; color: white; }
        tr:nth-child(even) { background: #f8f9fa; }
        .badge-pass { background-color: #28a745; color: white; padding: 5px 12px; border-radius: 15px; }
        .badge-fail { background-color: #dc3545; color: white; padding: 5px 12px; border-radius: 15px; }
        .footer { background: #2E7D32; color: white; text-align: center; padding: 1rem; margin-top: 2rem; }
    </style>
</head>
<body>

<header class="header">
    <h1>Skill Drop Testing Dashboard</h1>
    <div class="header-nav">
        <a href="index.php">🏠 Dashboard</a>
        <a href="estimate_defects.php">🧮 Estimate Defects</a>
        <a href="test_pages.php">📄 Test Pages</a>
        <a href="test_summary.php">🧾 Summary Report</a>
        <a href="test_workflows.php">🔄 Test Workflows</a>
    </div>
</header>

<main class="main-wrapper">
    <h2>🔄 Test Workflows Overview</h2>

    <table>
        <tr>
            <th>Workflow</th>
            <th>Description</th>
            <th>Last Test Result</th>
        </tr>
        <?php foreach ($workflows as $workflow => $description): ?>
            <?php 
            // Check latest result
            $latest_result = "Not Tested";
            $badge_class = "";
            foreach (array_reverse($test_results) as $test) {
                if ($test['workflow'] === $workflow) {
                    $latest_result = $test['result'];
                    $badge_class = ($latest_result === "PASS") ? "badge-pass" : "badge-fail";
                    break;
                }
            }
            ?>
            <tr>
                <td><?php echo htmlspecialchars($workflow); ?></td>
                <td><?php echo htmlspecialchars($description); ?></td>
                <td><span class="<?php echo $badge_class; ?>"><?php echo htmlspecialchars($latest_result); ?></span></td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>

<footer class="footer">
    © Skill Drop Project 2025
</footer>

</body>
</html>
