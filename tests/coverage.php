<?php

if (!function_exists('xdebug_start_code_coverage')) {
    fwrite(STDERR, "Xdebug is required to collect code coverage.\n");
    exit(1);
}

xdebug_start_code_coverage(XDEBUG_CC_UNUSED);

require_once(__DIR__ . '/test_case.php');
require_once(__DIR__ . '/board_tests.php');
require_once(__DIR__ . '/snake_tests.php');
require_once(__DIR__ . '/game_tests.php');

$tests = new TestRunner();
runBoardTests($tests);
runSnakeTests($tests);
runGameTests($tests);
$testExitCode = $tests->report();

$coverage = xdebug_get_code_coverage();
$sourceFiles = [
    realpath(__DIR__ . '/../board.php'),
    realpath(__DIR__ . '/../snake.php'),
    realpath(__DIR__ . '/../moveResponse.php')
];
$executableLines = 0;
$coveredLines = 0;

foreach ($sourceFiles as $sourceFile) {
    foreach ($coverage[$sourceFile] ?? [] as $line => $status) {
        $executableLines++;
        if ($status === 1) {
            $coveredLines++;
        }
    }
}

$coveragePercent = $executableLines === 0 ? 0 : ($coveredLines / $executableLines) * 100;
$minimumCoverage = 70;

printf("Code coverage: %.2f%% (%d/%d executable lines).\n", $coveragePercent, $coveredLines, $executableLines);
printf("Required coverage: %.2f%%.\n", $minimumCoverage);

xdebug_stop_code_coverage();

if ($testExitCode !== 0 || $coveragePercent < $minimumCoverage) {
    exit(1);
}

exit(0);