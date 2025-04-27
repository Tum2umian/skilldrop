<?php
// run_analysis.php

// Read the configuration file
$config_file = 'analysis_config.json';
if (!file_exists($config_file)) {
    die("Configuration file not found: $config_file\n");
}
$config = json_decode(file_get_contents($config_file), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die("Error parsing JSON configuration: " . json_last_error_msg() . "\n");
}

// Create the reports directory
$reports_dir = 'reports';
if (!is_dir($reports_dir)) {
    mkdir($reports_dir, 0777, true);
}

// Initialize HTML report content
$html_report = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{$config['html_report']['title']}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        h2 { color: #555; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 5px; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .error { color: #d9534f; }
    </style>
</head>
<body>
    <h1>{$config['html_report']['title']}</h1>
HTML;

// Function to run a command and capture output
function run_command($command) {
    exec($command . ' 2>&1', $output, $return_var);
    return ['output' => implode("\n", $output), 'return_var' => $return_var];
}

// Run analysis for each directory and tool
foreach ($config['directories'] as $dir) {
    $html_report .= "<h2>Analysis for Directory: $dir</h2>\n";

    // PHPMD
    $phpmd_config = $config['tools']['phpmd'];
    $phpmd_cmd = "vendor\\bin\\phpmd $dir {$phpmd_config['output_format']} {$phpmd_config['ruleset']} --reportfile {$phpmd_config['output_file']} --verbose";
    $phpmd_result = run_command($phpmd_cmd);
    $html_report .= "<h3>PHPMD Report</h3>\n";
    if ($phpmd_result['return_var'] === 0) {
        $phpmd_output = file_get_contents($phpmd_config['output_file']);
        $html_report .= "<div>$phpmd_output</div>\n";
    } else {
        $html_report .= "<p class=\"error\">PHPMD failed with exit code {$phpmd_result['return_var']}: " . htmlspecialchars($phpmd_result['output']) . "</p>\n";
    }

    // PHP_CodeSniffer
    $phpcs_config = $config['tools']['phpcs'];
    $phpcs_cmd = "vendor\\bin\\phpcs $dir --standard={$phpcs_config['standard']} --report={$phpcs_config['output_format']} > {$phpcs_config['output_file']}";
    $phpcs_result = run_command($phpcs_cmd);
    $html_report .= "<h3>PHP_CodeSniffer Report</h3>\n";
    $phpcs_json_exists = file_exists($phpcs_config['output_file']) && filesize($phpcs_config['output_file']) > 0;
    $phpcs_data = $phpcs_json_exists ? json_decode(file_get_contents($phpcs_config['output_file']), true) : null;
    if ($phpcs_json_exists && $phpcs_data !== null) {
        if (isset($phpcs_data['files'])) {
            $html_report .= "<table>\n<tr><th>File</th><th>Line</th><th>Error</th></tr>\n";
            foreach ($phpcs_data['files'] as $file => $data) {
                foreach ($data['messages'] as $message) {
                    $html_report .= "<tr><td>$file</td><td>{$message['line']}</td><td>" . htmlspecialchars($message['message']) . "</td></tr>\n";
                }
            }
            $html_report .= "</table>\n";
        }
    } else {
        $html_report .= "<p class=\"error\">PHP_CodeSniffer failed: " . htmlspecialchars($phpcs_result['output']) . "</p>\n";
    }

    // PHPLoc
    $phploc_config = $config['tools']['phploc'];
    $phploc_cmd = "vendor\\bin\\phploc $dir --log-json {$phploc_config['output_file']}";
    $phploc_result = run_command($phploc_cmd);
    $html_report .= "<h3>PHPLoc Report</h3>\n";
    if ($phploc_result['return_var'] === 0) {
        $phploc_data = json_decode(file_get_contents($phploc_config['output_file']), true);
        $php_files = glob(rtrim($dir, '/') . '/*.php');
        $file_count = count($php_files);
        $ccn = isset($phploc_data['ccn']) ? $phploc_data['ccn'] : 0;
        $avg_ccn = $file_count > 0 ? round($ccn / $file_count, 2) : 'N/A';
        $html_report .= "<pre>\n";
        $html_report .= "Files Analyzed: $file_count\n";
        $html_report .= "Total Cyclomatic Complexity: $ccn\n";
        $html_report .= "Cyclomatic Complexity (avg per file): $avg_ccn\n";
        $html_report .= "Lines of Code (LOC): " . ($phploc_data['loc'] ?? 'N/A') . "\n";
        $html_report .= "Non-Comment Lines of Code (NCLOC): " . ($phploc_data['ncloc'] ?? 'N/A') . "\n";
        $html_report .= "</pre>\n";
    } else {
        $html_report .= "<p class=\"error\">PHPLoc failed: " . htmlspecialchars($phploc_result['output']) . "</p>\n";
    }

    // PHPStan
    $phpstan_config = $config['tools']['phpstan'];
    $phpstan_cmd = "vendor\\bin\\phpstan analyse $dir --level={$phpstan_config['level']} --error-format=json --no-progress > {$phpstan_config['output_file']}";
    $phpstan_result = run_command($phpstan_cmd);
    $html_report .= "<h3>PHPStan Report</h3>\n";
    $phpstan_json_exists = file_exists($phpstan_config['output_file']) && filesize($phpstan_config['output_file']) > 0;
    $phpstan_data = null;
    if ($phpstan_json_exists) {
        $phpstan_content = file_get_contents($phpstan_config['output_file']);
        $json_start = strpos($phpstan_content, '{');
        if ($json_start !== false) {
            $phpstan_content = substr($phpstan_content, $json_start);
        }
        $phpstan_data = json_decode($phpstan_content, true);
        $sanitized_dir = str_replace('/', '_', rtrim($dir, '/'));
        file_put_contents("reports/phpstan_{$sanitized_dir}_debug.json", $phpstan_content);
    }
    if ($phpstan_json_exists && $phpstan_data !== null) {
        if (isset($phpstan_data['files'])) {
            $html_report .= "<table>\n<tr><th>File</th><th>Line</th><th>Error</th></tr>\n";
            foreach ($phpstan_data['files'] as $file => $data) {
                foreach ($data['messages'] as $message) {
                    $html_report .= "<tr><td>$file</td><td>" . (isset($message['line']) ? $message['line'] : 'N/A') . "</td><td>" . htmlspecialchars($message['message']) . "</td></tr>\n";
                }
            }
            $html_report .= "</table>\n";
        }
    } else {
        $html_report .= "<p class=\"error\">PHPStan failed: " . htmlspecialchars($phpstan_result['output']) . "</p>\n";
    }
}

// Close HTML report
$html_report .= "</body>\n</html>";

// Write the HTML report
file_put_contents($config['html_report']['output_file'], $html_report);

echo "Analysis complete! HTML report generated at: {$config['html_report']['output_file']}\n";
?>