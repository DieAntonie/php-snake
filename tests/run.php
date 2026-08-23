<?php

require_once(__DIR__ . '/test_case.php');
require_once(__DIR__ . '/board_tests.php');
require_once(__DIR__ . '/snake_tests.php');
require_once(__DIR__ . '/game_tests.php');

$tests = new TestRunner();
runBoardTests($tests);
runSnakeTests($tests);
runGameTests($tests);
exit($tests->report());