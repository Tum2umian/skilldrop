<?php
// Directory: /test/test_pages.php
include '../includes/db.php';

// Helper function to scan PHP files and count how many pages exist
function scanTestedPages($dir) {
    $pages = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir)
    );

    foreach ($iterator as $file) {
        if ($file->isFile() && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $relative_path = str_replace(realpath(__DIR__ . '/../') . DIRECTORY_SEPARATOR, '', $file->getRealPath());
            $pages[] = $relative_path;
        }
    }
    return $pages;
}

// Define where we scan pages
$project_dir = realpath(__DIR__ . '/../');
$pages = scanTestedPages($project_dir);

// Log file
$log_file = "logs/test_pages_log.txt";

// Log this run
if (!file_exists('logs')) {
    mkdir('logs', 0777, true);
}

$log_data = [
    "timestamp" => date('Y-m-d H:i:s'),
    "pages_scanned" => count($pages),
];

file_put_contents($log_file, json_encode($log_data) . PHP_EOL, FILE_APPEND);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Skill Drop Testing Dashboard | Test Pages</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: #f8f9fa; font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
        .header { background-color: #2E7D32; color: white; padding: 1rem; text-align: center; }
        .header-nav { margin-top: 0.5rem; }
        .header-nav a { color: white; margin: 0 1rem; text-decoration: none; font-weight: bold; }
        .main-wrapper { display: flex; flex-direction: column; align-items: center; padding: 2rem; }
        .report { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); width: 100%; max-width: 800px; }
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
        <h2>Test Pages Report</h2>

        <table>
            <tr>
                <th>#</th>
                <th>Page (Relative Path)</th>
            </tr>
            <?php foreach ($pages as $index => $page): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($page); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p style="margin-top: 1.5rem;">
            <strong>Total PHP Pages Found:</strong> <?php echo count($pages); ?>
        </p>
    </section>
</main>

<footer class="footer">
    © Skill Drop Project 2025
</footer>

</body>
</html>
