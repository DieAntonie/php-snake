<?php

require_once(__DIR__ . '/test_case.php');

function runBoardTests(TestRunner $tests): void
{
    $board = new Board([
        'width' => 5,
        'height' => 4,
        'food' => [['x' => 3, 'y' => 1]],
        'hazards' => [['x' => 1, 'y' => 0]]
    ]);

    $tests->assertSame(true, $board->isValidCoordinate(['x' => 4, 'y' => 3]), 'accepts coordinates inside the board');
    $tests->assertSame(false, $board->isValidCoordinate(['x' => 5, 'y' => 3]), 'rejects coordinates outside the board');
    $tests->assertSame(true, $board->isHazard(['x' => 1, 'y' => 0]), 'identifies hazard coordinates');
    $tests->assertSame(false, $board->isHazard(['x' => 0, 'y' => 0]), 'rejects non-hazard coordinates');
}

if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    $tests = new TestRunner();
    runBoardTests($tests);
    exit($tests->report());
}