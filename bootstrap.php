<?php

/**
 * Shared application bootstrap for API endpoints.
 */

class Logger
{
	public static function log(string $data, string $file = 'logs.txt'): void
	{
		file_put_contents($file, $data . PHP_EOL, FILE_APPEND | LOCK_EX);
	}

	public static function logJson($data, string $file = 'logs.json'): void
	{
		$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		file_put_contents($file, $json . PHP_EOL . PHP_EOL, FILE_APPEND | LOCK_EX);
	}
}

require_once(__DIR__ . '/snake.php');
require_once(__DIR__ . '/board.php');
require_once(__DIR__ . '/moveResponse.php');
