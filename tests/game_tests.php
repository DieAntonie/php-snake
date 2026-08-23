<?php

require_once(__DIR__ . '/test_case.php');

function runGameTests(TestRunner $tests): void
{
    $game = new Game([
        'id' => 'game-123',
        'ruleset' => ['name' => 'standard', 'version' => 'v1.1.0'],
        'timeout' => 500
    ]);

    $tests->assertSame('game-123', $game->getId(), 'returns the game ID');
    $tests->assertSame(['name' => 'standard', 'version' => 'v1.1.0'], $game->getRuleset(), 'returns the game ruleset');
    $tests->assertSame(500, $game->getTimeout(), 'returns the response timeout');
}

if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    $tests = new TestRunner();
    runGameTests($tests);
    exit($tests->report());
}