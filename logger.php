<?php
class Logger {
    private $logFile;

    public function __construct($filePath = 'logs/system.log') {
        $this->logFile = $filePath;
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

// Usage example
$logger = new Logger();
$logger->logFailure('Database connection failed', ['operation' => 'fetchUser', 'userId' => 123]);
?>