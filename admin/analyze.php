<?php
require '../vendor/autoload.php';
include '../includes/db.php';
use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;

class MetricsVisitor extends NodeVisitorAbstract {
    private $cyclomaticComplexity = 1; // Start with 1 for the main path
    private $maxNestingDepth = 0;
    private $currentNestingDepth = 0;
    private $integers = 0;
    private $strings = 0;
    private $arrays = 0;
    private $arraySizes = [];
    private $fanIn = 0;
    private $fanOut = 0;

    public function enterNode(Node $node) {
        // Cyclomatic Complexity: Increment for control structures
        if ($node instanceof Node\Stmt\If_ ||
            $node instanceof Node\Stmt\For_ ||
            $node instanceof Node\Stmt\Foreach_ ||
            $node instanceof Node\Stmt\While_ ||
            $node instanceof Node\Stmt\Do_ ||
            $node instanceof Node\Stmt\Switch_) {
            $this->cyclomaticComplexity++;
        }

        // Depth of Nesting
        if ($node instanceof Node\Stmt\If_ ||
            $node instanceof Node\Stmt\For_ ||
            $node instanceof Node\Stmt\Foreach_ ||
            $node instanceof Node\Stmt\While_ ||
            $node instanceof Node\Stmt\Do_) {
            $this->currentNestingDepth++;
            $this->maxNestingDepth = max($this->maxNestingDepth, $this->currentNestingDepth);
        }

        // Data Structure Complexity
        if ($node instanceof Node\Expr\Variable && isset($node->getAttributes()['type']) && $node->getAttributes()['type'] === 'int') {
            $this->integers++;
        } elseif ($node instanceof Node\Scalar\String_) {
            $this->strings++;
        } elseif ($node instanceof Node\Expr\Array_) {
            $this->arrays++;
            $this->arraySizes[] = count($node->items);
        }

        // Information Flow: Fan-in and Fan-out (simplified)
        if ($node instanceof Node\Expr\FuncCall) {
            $this->fanOut++; // Function call increases fan-out
        }
        if ($node instanceof Node\Param) {
            $this->fanIn++; // Parameters increase fan-in
        }
    }

    public function leaveNode(Node $node) {
        if ($node instanceof Node\Stmt\If_ ||
            $node instanceof Node\Stmt\For_ ||
            $node instanceof Node\Stmt\Foreach_ ||
            $node instanceof Node\Stmt\While_ ||
            $node instanceof Node\Stmt\Do_) {
            $this->currentNestingDepth--;
        }
    }

    public function getMetrics() {
        $dataStructureComplexity = ($this->integers * 1) +
                                  ($this->strings * 2) +
                                  array_sum(array_map(fn($size) => 2 * $size, $this->arraySizes));
        $infoFlowComplexity = pow($this->fanIn * $this->fanOut, 2);
        return [
            'cyclomatic_complexity' => $this->cyclomaticComplexity,
            'depth_of_nesting' => $this->maxNestingDepth,
            'info_flow_complexity' => $infoFlowComplexity,
            'data_structure_complexity' => $dataStructureComplexity
        ];
    }
}

$admin_files = [
    'reports.php',
    'dashboard.php',
    'settings.php',
    'manage-users.php',
    'manage-requests.php',
    'edit-profile.php'
];

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
$traverser = new NodeTraverser();
$visitor = new MetricsVisitor();
$traverser->addVisitor($visitor);

foreach ($admin_files as $file) {
    $file_path = __DIR__ . '/' . $file;
    if (!file_exists($file_path)) {
        error_log("File not found: $file_path");
        continue;
    }

    $code = file_get_contents($file_path);
    try {
        $stmts = $parser->parse($code);
        if ($stmts === null) {
            error_log("Failed to parse file: $file");
            continue;
        }
        $traverser->traverse($stmts);
        $metrics = $visitor->getMetrics();

        // Store metrics in database
        $stmt = $conn->prepare("INSERT INTO code_metrics (file_name, cyclomatic_complexity, depth_of_nesting, info_flow_complexity, data_structure_complexity) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siidi", $file, $metrics['cyclomatic_complexity'], $metrics['depth_of_nesting'], $metrics['info_flow_complexity'], $metrics['data_structure_complexity']);
        $stmt->execute();
        $stmt->close();

        // Reset visitor for next file
        $traverser->removeVisitor($visitor);
        $visitor = new MetricsVisitor();
        $traverser->addVisitor($visitor);
    } catch (Exception $e) {
        error_log("Error parsing $file: " . $e->getMessage());
    }
}

$conn->close();
echo "Metrics analysis completed.";
?>