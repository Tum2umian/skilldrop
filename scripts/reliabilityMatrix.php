<?php
class ReliabilityMetrics {
    private $failures = [];
    private $repairTimes = [];

    public function addFailure($time) {
        $this->failures[] = $time;
    }

    public function addRepairTime($time) {
        $this->repairTimes[] = $time;
    }

    public function calculateMTTF() {
        if (count($this->failures) < 2) return null;
        $totalTime = 0;
        for ($i = 1; $i < count($this->failures); $i++) {
            $totalTime += $this->failures[$i] - $this->failures[$i - 1];
        }
        return $totalTime / (count($this->failures) - 1);
    }

    public function calculateMTTR() {
        if (empty($this->repairTimes)) return null;
        return array_sum($this->repairTimes) / count($this->repairTimes);
    }

    public function calculateMTBF() {
        $mttf = $this->calculateMTTF();
        $mttr = $this->calculateMTTR();
        if ($mttf === null || $mttr === null) return null;
        return $mttf + $mttr;
    }
}

// Usage example
$metrics = new ReliabilityMetrics();
$metrics->addFailure(10); // Failure at 10 seconds
$metrics->addFailure(50); // Failure at 50 seconds
$metrics->addRepairTime(5); // Repair took 5 seconds
echo "MTTF: " . $metrics->calculateMTTF() . "\n";
echo "MTTR: " . $metrics->calculateMTTR() . "\n";
echo "MTBF: " . $metrics->calculateMTBF() . "\n";
?>