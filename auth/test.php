<?php
function highComplexityFunction($a, $b, $c, $d, $e) {
    if ($a > 0) { // +1
        if ($b > 0) { // +1
            if ($c > 0) { // +1
                if ($d > 0) { // +1
                    if ($e > 0) { // +1
                        return true;
                    } elseif ($e < -10) { // +1
                        return false;
                    }
                }
            } elseif ($c < -10) { // +1
                return false;
            }
        } elseif ($b < -10) { // +1
            return false;
        }
    } elseif ($a < -10) { // +1
        return false;
    }
    return null; // Total complexity: 10 (9 decision points + 1 for the function)
}