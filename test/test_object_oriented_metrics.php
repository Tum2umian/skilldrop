<?php
// Directory: /test/test_object_oriented_metrics.php

include '../includes/db.php';

$project_dir = realpath(__DIR__ . '/../');

// Initialize results
$metrics = [];

// Helper: Scan PHP files
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($project_dir)
);

foreach ($iterator as $file) {
    if ($file->isFile() && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $content = file_get_contents($file->getRealPath());

        // Match class names
        if (preg_match_all('/class\s+(\w+)(\s+extends\s+(\w+))?/', $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $class_name = $match[1];
                $parent_class = isset($match[3]) ? $match[3] : null;

                // Count methods inside class
                $method_count = preg_match_all('/function\s+\w+\s*\(/', $content);

                // Detect object coupling (new instances of other classes)
                preg_match_all('/new\s+(\w+)/', $content, $coupled_classes);
                $coupled_classes = isset($coupled_classes[1]) ? array_unique($coupled_classes[1]) : [];
                $coupling_count = count($coupled_classes);

                // Approximate cohesion: methods vs properties usage (basic estimation)
                $property_usage = preg_match_all('/\$this->\w+/', $content);
                $total_elements = $method_count + $property_usage;
                $lcom = ($total_elements > 0) ? round(($property_usage / $total_elements), 2) : 0;

                $metrics[$class_name] = [
                    'parent' => $parent_class,
                    'methods' => $method_count,
                    'coupling' => $coupling_count,
                    'lcom' => $lcom
                ];
            }
        }
    }
}

// Calculate Depth of Inheritance Tree (DIT)
function calculateDIT($class, $metrics) {
    $depth = 0;
    while (isset($metrics[$class]) && $metrics[$class]['parent']) {
        $depth++;
        $class = $metrics[$class]['parent'];
    }
    return $depth;
}

foreach ($metrics as $class => &$data) {
    $data['dit'] = calculateDIT($class, $metrics);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Skill Drop Testing Dashboard | OO Metrics</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: #f8f9fa; font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
        .legend-box {
    margin-top: 2rem;
    padding: 1.5rem;
    background: #ffffff;
    border: 1px solid #2E7D32;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.legend-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
    margin-bottom: 1.5rem;
}

.legend-table th, .legend-table td {
    border: 1px solid #dee2e6;
    padding: 10px;
    text-align: left;
}

.legend-table th {
    background-color: #2E7D32;
    color: #ffffff;
}

.color-legend {
    list-style: none;
    padding: 0;
}

.color-legend li {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.color {
    display: inline-block;
    width: 20px;
    height: 20px;
    border-radius: 4px;
    margin-right: 10px;
}

.color.green {
    background-color: #28a745;
}

.color.orange {
    background-color: #ffc107;
}

.color.red {
    background-color: #dc3545;
}

        .header { background-color: #2E7D32; color: white; padding: 1rem; text-align: center; }
        .header-nav { margin-top: 0.5rem; }
        .header-nav a { color: white; margin: 0 1rem; text-decoration: none; font-weight: bold; }
        .main-wrapper { padding: 2rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 10px; border: 1px solid #dee2e6; text-align: left; }
        th { background: #2E7D32; color: white; }
        tr:nth-child(even) { background: #f8f9fa; }
        .badge-pass { background-color: #28a745; color: white; padding: 5px 12px; border-radius: 15px; }
        .badge-warn { background-color: #ffc107; color: black; padding: 5px 12px; border-radius: 15px; }
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
        <a href="test_object_oriented_metrics.php">⚙️ OO Metrics</a>
    </div>
</header>

<main class="main-wrapper">
    <h2>⚙️ Object-Oriented Metrics Report</h2>
    <section class="legend-box">
    <h3>🗝️ Metric Key (Legend)</h3>
    <table class="legend-table">
        <tr>
            <th>Metric</th>
            <th>Meaning</th>
        </tr>
        <tr>
            <td><strong>WMC</strong></td>
            <td>Weighted Methods per Class — Measures complexity inside classes.</td>
        </tr>
        <tr>
            <td><strong>DIT</strong></td>
            <td>Depth of Inheritance Tree — How deep a class is in the inheritance chain.</td>
        </tr>
        <tr>
            <td><strong>CBO</strong></td>
            <td>Coupling Between Objects — How much classes are dependent on others.</td>
        </tr>
        <tr>
            <td><strong>LCOM</strong></td>
            <td>Lack of Cohesion of Methods — Measures how unrelated methods are within a class.</td>
        </tr>
    </table>

    <h4>🎨 Color Meanings</h4>
    <ul class="color-legend">
        <li><span class="color green"></span> <strong>Green:</strong> Good / Acceptable</li>
        <li><span class="color orange"></span> <strong>Orange:</strong> Warning (Needs Attention)</li>
        <li><span class="color red"></span> <strong>Red:</strong> Critical (Needs Immediate Improvement)</li>
    </ul>
</section>

    <table>
        <tr>
            <th>Class</th>
            <th>WMC</th>
            <th>DIT</th>
            <th>CBO</th>
            <th>LCOM</th>
        </tr>

        <?php foreach ($metrics as $class => $data): ?>
            <tr>
                <td><?php echo htmlspecialchars($class); ?></td>
                <td><span class="<?php echo ($data['methods'] > 20) ? 'badge-fail' : 'badge-pass'; ?>">
                    <?php echo $data['methods']; ?>
                </span></td>
                <td><span class="<?php echo ($data['dit'] > 3) ? 'badge-warn' : 'badge-pass'; ?>">
                    <?php echo $data['dit']; ?>
                </span></td>
                <td><span class="<?php echo ($data['coupling'] > 5) ? 'badge-fail' : 'badge-pass'; ?>">
                    <?php echo $data['coupling']; ?>
                </span></td>
                <td><span class="<?php echo ($data['lcom'] < 0.5) ? 'badge-fail' : 'badge-pass'; ?>">
                    <?php echo $data['lcom']; ?>
                </span></td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>

<footer class="footer">
    © Skill Drop Project 2025
</footer>

</body>
</html>
