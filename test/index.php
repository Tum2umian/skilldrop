<?php
// Directory: /test/index.php

// Define all log files
$log_files = [
    "Estimate Defects" => "logs/estimate_defects_log.txt",
    "Test Pages" => "logs/test_pages_log.txt",
    "Test Workflows" => "logs/test_workflows_log.txt",
    "Test Summary" => "logs/test_summary_log.txt",
    "Object Oriented Metrics" => "logs/test_object_oriented_metrics_log.txt", // ✅ NEW
];

// Helper to read last log
function read_last_log($file_path) {
    if (!file_exists($file_path)) {
        return "No test run yet.";
    }

    $lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!$lines) {
        return "No test run yet.";
    }
    $last = json_decode(end($lines), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return "Invalid log data.";
    }

    $summary = "";
    foreach ($last as $key => $value) {
        $summary .= "<strong>" . ucfirst(str_replace("_", " ", $key)) . ":</strong> " . htmlspecialchars($value) . "<br>";
    }
    return $summary;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Skill Drop Testing Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: #f8f9fa; font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
        .header { background-color: #2E7D32; color: white; padding: 1rem; text-align: center; }
        .header-nav { margin-top: 0.5rem; }
        .header-nav a { color: white; margin: 0 1rem; text-decoration: none; font-weight: bold; }
        .main-wrapper { display: flex; flex-wrap: wrap; justify-content: center; gap: 2rem; padding: 2rem; }
        .test-card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); flex: 1 1 300px; position: relative; }
        .test-card h3 { margin-bottom: 1rem; }
        .test-card p { font-size: 0.9rem; color: #555; margin-bottom: 1rem; }
        .test-actions { display: flex; gap: 1rem; flex-wrap: wrap; }
        .button { padding: 0.6rem 1.2rem; border-radius: 5px; text-decoration: none; text-align: center; display: inline-block; font-weight: 600; }
        .button.green { background: #28a745; color: white; }
        .button.red { background: #dc3545; color: white; }
        .button.secondary { background: white; color: #2E7D32; border: 1px solid #2E7D32; }
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
        <a href="test_object_oriented_metrics.php">⚙️ OO Metrics</a> <!-- ✅ NEW -->
    </div>
</header>

<main class="main-wrapper">

<?php foreach ($log_files as $test_name => $file_path): ?>
    <div class="test-card">
        <h3><?php echo htmlspecialchars($test_name); ?></h3>
        <p><?php echo read_last_log($file_path); ?></p>
        <div class="test-actions">
            <?php
            // Correct file link
            $file_link = strtolower(str_replace([" ", "-"], ["_", ""], $test_name)) . ".php";

            // Special fix for Object Oriented Metrics file name
            if ($test_name === "Object Oriented Metrics") {
                $file_link = "test_object_oriented_metrics.php";
            }
            ?>
            <a href="<?php echo $file_link; ?>" class="button green">Run Test</a>
            <a href="reset_log.php?file=<?php echo urlencode($file_path); ?>" class="button red" onclick="return confirm('Are you sure you want to reset <?php echo htmlspecialchars($test_name); ?> log?');">Reset Log</a>
        </div>
    </div>
<?php endforeach; ?>

</main>

<footer class="footer">
    © Skill Drop Project 2025
</footer>

</body>
</html>
