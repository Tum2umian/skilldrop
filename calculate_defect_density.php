<?php
// Target specific files with correct paths
$filesToCheck = [
    '/var/www/html/skilldrop/skilldrop/index.php',
    '/var/www/html/skilldrop/skilldrop/auth/login.php',
    '/var/www/html/skilldrop/skilldrop/auth/register.php',
    '/var/www/html/skilldrop/skilldrop/dashboard.php',
    '/var/www/html/skilldrop/skilldrop/editprofile.php'
];
$fileList = implode(' ', $filesToCheck);

// Run PHPStan to get number of defects
$phpstanOutput = [];
exec("vendor/bin/phpstan analyse $fileList --error-format=raw", $phpstanOutput);
$defects = count($phpstanOutput);

// Run cloc to get lines of code
$clocOutput = [];
exec('cloc --json ' . $fileList, $clocOutput);
$clocData = json_decode(implode('', $clocOutput), true);

// Extract PHP code lines
$phpLoc = $clocData['PHP']['code'] ?? 0;

// Convert lines of code to KLOC (thousands of lines)
$kloc = $phpLoc / 1000;

// Calculate defect density
$defectDensity = $kloc > 0 ? $defects / $kloc : 0;

// Print results
echo "Defects: $defects\n";
echo "KLOC: $kloc\n";
echo "Defect Density: $defectDensity\n";

// Ensure the metrics.csv file exists or create it with headers
if (!file_exists('metrics.csv')) {
    file_put_contents('metrics.csv', "Date,Defects,KLOC,DefectDensity\n");
}

// Save to metrics.csv
$date = date('Y-m-d');
$csvLine = "$date,$defects,$kloc,$defectDensity\n";
file_put_contents('metrics.csv', $csvLine, FILE_APPEND);

?>

