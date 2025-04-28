<?php
require 'vendor/autoload.php';
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Initialize logger
$log = new Logger('task_completion');
$log->pushHandler(new StreamHandler('logs/task_completion.log', Logger::INFO));

// Get the log file contents
$logFile = 'logs/task_completion.log';
if (!file_exists($logFile)) {
    die("Log file not found\n");
}

$successCount = 0;
$failureCount = 0;

// Read and analyze the log file
$lines = file($logFile);
foreach ($lines as $line) {
    if (strpos($line, 'SUCCESS') !== false) {
        $successCount++;
    } elseif (strpos($line, 'FAILURE') !== false) {
        $failureCount++;
    }
}

// Calculate completion rate
$totalAttempts = $successCount + $failureCount;
$completionRate = $totalAttempts > 0 ? ($successCount / $totalAttempts) * 100 : 0;

// Output results
echo "Task Completion Analysis:\n";
echo "Success Count: $successCount\n";
echo "Failure Count: $failureCount\n";
echo "Completion Rate: " . number_format($completionRate, 2) . "%\n";