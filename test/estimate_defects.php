<?php
// Directory: /test/estimate_defects.php
include '../includes/db.php';

// Helper function to count lines
function scanPHPFiles($dir) {
    $files_data = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir)
    );

    foreach ($iterator as $file) {
        if ($file->isFile() && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $lines = file($file->getRealPath());
            $loc = 0;
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line !== '' && strpos($line, '//') !== 0 && strpos($line, '/*') !== 0 && strpos($line, '*') !== 0) {
                    $loc++;
                }
            }
            $relative_path = str_replace(realpath(__DIR__ . '/../') . DIRECTORY_SEPARATOR, '', $file->getRealPath());
            $files_data[$relative_path] = $loc;
        }
    }
    return $files_data;
}

// Get project LOC
$project_dir = realpath(__DIR__ . '/../');
$files_loc = scanPHPFiles($project_dir);

// Calculate totals
$total_lines = array_sum($files_loc);
$defect_density = 0.8;
$expected_defects = ($total_lines / 1000) * $defect_density;
$threshold_defects = 5;
$result = ($expected_defects <= $threshold_defects) ? "PASS" : "FAIL";
$badge_color = ($result === "PASS") ? "badge-pass" : "badge-fail";

// Save to log
$log_dir = "logs/";
$log_file = $log_dir . "estimate_defects_log.txt";
if (!is_dir($log_dir)) {
    mkdir($log_dir, 0777, true);
}
file_put_contents($log_file, json_encode([
    "timestamp" => date("Y-m-d H:i:s"),
    "total_lines" => $total_lines,
    "expected_defects" => $expected_defects,
    "result" => $result
]) . PHP_EOL, FILE_APPEND);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Skill Drop Testing Dashboard | Estimate Defects</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: #f8f9fa; font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
        .header { background-color: #2E7D32; color: white; padding: 1rem; text-align: center; }
        .header-nav { margin-top: 0.5rem; }
        .header-nav a { color: white; margin: 0 1rem; text-decoration: none; font-weight: bold; }
        .main-wrapper { display: flex; flex-wrap: wrap; justify-content: center; gap: 2rem; padding: 2rem; }
        .report, .details { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .report { flex: 1 1 400px; }
        .details { flex: 1 1 300px; max-height: 600px; overflow-y: auto; }
        .badge-pass { background-color: #28a745; color: white; padding: 5px 12px; border-radius: 15px; }
        .badge-fail { background-color: #dc3545; color: white; padding: 5px 12px; border-radius: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 10px; border: 1px solid #dee2e6; text-align: left; }
        th { background: #2E7D32; color: white; }
        tr:nth-child(even) { background: #f8f9fa; }
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
    <section class="report">
        <h2>🧮 Defect Estimation Report</h2>
        <span class="<?php echo $badge_color; ?>"><?php echo $result; ?></span>

        <table>
            <tr><th>Metric</th><th>Value</th></tr>
            <tr><td>Total Lines of Code (LOC)</td><td><?php echo number_format($total_lines); ?> lines</td></tr>
            <tr><td>Defect Density</td><td><?php echo $defect_density; ?> defects per 1000 LOC</td></tr>
            <tr><td>Expected Defects</td><td><?php echo round($expected_defects, 2); ?> defects</td></tr>
            <tr><td>Pass Threshold</td><td><?php echo $threshold_defects; ?> defects</td></tr>
        </table>

        <p style="margin-top: 1.5rem;">
            <strong>Conclusion:</strong> The SkillDrop project <strong><?php echo ($result === "PASS") ? "is within" : "exceeds"; ?></strong> acceptable defect levels.
        </p>
    </section>

    <section class="details">
        <h3>📄 Lines per File</h3>
        <table>
            <tr><th>File</th><th>LOC</th></tr>
            <?php foreach ($files_loc as $file => $loc): ?>
                <tr>
                    <td><?php echo htmlspecialchars($file); ?></td>
                    <td><?php echo number_format($loc); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>

<footer class="footer">
    © Skill Drop Project 2025
</footer>

</body>
</html>
