<?php

require_once(__DIR__ . '/test_case.php');
require_once(__DIR__ . '/board_tests.php');
require_once(__DIR__ . '/snake_tests.php');
require_once(__DIR__ . '/game_tests.php');

function runAllTests(TestRunner $tests): void
{
	runBoardTests($tests);
	runSnakeTests($tests);
	runGameTests($tests);
}

$tests = new TestRunner();
runAllTests($tests);
exit($tests->report());