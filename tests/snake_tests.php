<?php

require_once(__DIR__ . '/test_case.php');

function runSnakeTests(TestRunner $tests): void
{
    $board = new Board([
        'width' => 5,
        'height' => 4,
        'food' => [['x' => 3, 'y' => 1]],
        'hazards' => [['x' => 1, 'y' => 0]]
    ]);
    $snake = new Snake([
        'id' => 'you',
        'head' => ['x' => 1, 'y' => 1],
        'body' => [['x' => 1, 'y' => 1]]
    ]);

    $tests->assertSame('right', $snake->calculateMove($board)['move'], 'moves towards the nearest food');

    $safeBoard = new Board([
        'width' => 3,
        'height' => 3,
        'food' => [['x' => 2, 'y' => 1]],
        'hazards' => [['x' => 2, 'y' => 1]]
    ]);
    $safeSnake = new Snake([
        'id' => 'you',
        'head' => ['x' => 1, 'y' => 1],
        'body' => [['x' => 1, 'y' => 1]]
    ]);

    $tests->assertSame('up', $safeSnake->calculateMove($safeBoard)['move'], 'prefers a safe move over a hazardous food route');

    $blockedBoard = new Board([
        'width' => 3,
        'height' => 3,
        'food' => [],
        'snakes' => [
            ['id' => 'opponent', 'body' => [['x' => 2, 'y' => 1]]]
        ]
    ]);
    $blockedSnake = new Snake([
        'id' => 'you',
        'head' => ['x' => 1, 'y' => 1],
        'body' => [['x' => 1, 'y' => 1]]
    ]);

    $tests->assertSame('up', $blockedSnake->calculateMove($blockedBoard)['move'], 'avoids an opponent body when choosing a move');
}

if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    $tests = new TestRunner();
    runSnakeTests($tests);
    exit($tests->report());
}