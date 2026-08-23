<?php

require_once(__DIR__ . '/../bootstrap.php');

class TestRunner
{
    private $passed = 0;
    private $failed = 0;

    public function assertSame($expected, $actual, string $message): void
    {
        if ($expected === $actual) {
            $this->passed++;
            echo "PASS: {$message}\n";
            return;
        }

        $this->failed++;
        echo "FAIL: {$message}\n";
        echo '  Expected: ' . var_export($expected, true) . "\n";
        echo '  Actual:   ' . var_export($actual, true) . "\n";
    }

    public function report(): int
    {
        $total = $this->passed + $this->failed;
        echo "\n{$this->passed} passed, {$this->failed} failed ({$total} total).\n";
        return $this->failed === 0 ? 0 : 1;
    }

    public function merge(TestRunner $other): void
    {
        $this->passed += $other->passed;
        $this->failed += $other->failed;
    }
}