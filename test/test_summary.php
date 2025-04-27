<?php
// Directory: /test/test_summary.php
include '../includes/db.php';

// Load logs
function loadLogs($log_file) {
    $logs = [];
    if (file_exists($log_file)) {
        $lines = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $logs[] = json_decode($line, true);
        }
    }
    return $logs;
}

// Define log files
$defects_log = "logs/estimate_defects_log.txt";
$pages_log = "logs/test_pages_log.txt";
$workflows_log = "logs/test_workflows_log.txt";

// Load all
$defects_logs = loadLogs($defects_log);
$pages_logs = loadLogs($pages_log);
$workflows_logs = loadLogs($workflows_log);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Skill Drop Testing Dashboard | Test Summary</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: #f8f9fa; font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
        .header { background-color: #2E7D32; color: white; padding: 1rem; text-align: center; }
        .header-nav { margin-top: 0.5rem; }
        .header-nav a { color: white; margin: 0 1rem; text-decoration: none; font-weight: bold; }
        .main-wrapper { display: flex; flex-direction: column; align-items: center; padding: 2rem; }
        .report { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); width: 100%; max-width: 900px; }
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
    <section class="report">
        <h2>📋 Test Summary Report</h2>

        <!-- Defect Estimation Logs -->
        <h3>🧮 Defect Estimation</h3>
        <table>
            <tr><th>Timestamp</th><th>Expected Defects</th><th>Status</th></tr>
            <?php if (!empty($defects_logs)): ?>
                <?php foreach ($defects_logs as $log): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($log['timestamp']); ?></td>
                        <td><?php echo htmlspecialchars($log['expected_defects']); ?></td>
                        <td><span class="<?php echo ($log['result'] === 'PASS') ? 'badge-pass' : 'badge-fail'; ?>"><?php echo htmlspecialchars($log['result']); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">No defect logs yet.</td></tr>
            <?php endif; ?>
        </table>

        <!-- Pages Tested Logs -->
        <h3>📄 Pages Scanned</h3>
        <table>
            <tr><th>Timestamp</th><th>Pages Found</th></tr>
            <?php if (!empty($pages_logs)): ?>
                <?php foreach ($pages_logs as $log): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($log['timestamp']); ?></td>
                        <td><?php echo htmlspecialchars($log['pages_scanned']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="2">No page logs yet.</td></tr>
            <?php endif; ?>
        </table>

        <!-- Workflows Tested Logs -->
        <h3>🔄 Workflow Tests</h3>
        <table>
            <tr><th>Timestamp</th><th>Tests Passed</th><th>Tests Failed</th></tr>
            <?php if (!empty($workflows_logs)): ?>
                <?php foreach ($workflows_logs as $log): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($log['timestamp']); ?></td>
                        <td><?php echo htmlspecialchars($log['tests_passed']); ?></td>
                        <td><?php echo htmlspecialchars($log['tests_failed']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">No workflow logs yet.</td></tr>
            <?php endif; ?>
        </table>

    </section>
</main>

<footer class="footer">
    © Skill Drop Project 2025
</footer>

</body>
</html>
