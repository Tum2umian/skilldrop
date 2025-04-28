<?php
class Logger {
    private $logFile;

    public function __construct($filePath = 'logs/system.log') {
        $this->logFile = $filePath;
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true); // Ensure the logs directory exists
        }
        if (!file_exists($this->logFile)) {
            file_put_contents($this->logFile, '');
        }
    }

    public function logFailure($message, $context = []) {
        $timestamp = date('Y-m-d H:i:s');
        $contextString = json_encode($context);
        $logMessage = "[{$timestamp}] FAILURE: {$message} | Context: {$contextString}\n";
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }
}

// Database connection example
try {
    $pdo = new PDO('mysql:host=localhost;dbname=skilldrop', 'username', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Example query
    $stmt = $pdo->query('SELECT * FROM users');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $logger = new Logger();
    $logger->logFailure('Database connection error', ['error' => $e->getMessage()]);
}
?>