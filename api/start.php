<?php

/**
 * Utility class for logging
 */
class Logger
{
    /**
     * Write data to log file
     * @param string $data Data to log
     * @param string $file Log file path
     */
    public static function log(string $data, string $file = 'logs.txt'): void
    {
        file_put_contents($file, $data . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    /**
     * Write JSON data to log file
     * @param mixed $data Data to serialize
     * @param string $file Log file path
     */
    public static function logJson($data, string $file = 'logs.json'): void
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        file_put_contents($file, $json . PHP_EOL . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}

/**
 * POST /start
 * Called when the game begins. Return response is ignored.
 */

try {
    // Parse JSON request
    $request = json_decode(file_get_contents('php://input'), true);

    if ($request === null) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON']);
        exit;
    }

    // Create snake instance
    $snake = new Snake($request['you'] ?? []);
    $board = new Board($request['board'] ?? []);
    $game = new Game($request['game'] ?? []);

    // Log the game start
    Logger::logJson([
        'event' => 'game_start',
        'game_id' => $game->getId(),
        'snake_id' => $snake->getHead(),
        'board_size' => [
            'width' => $board->getWidth(),
            'height' => $board->getHeight()
        ]
    ]);

    // Response data (this is actually returned from GET /, but we acknowledge the start)
    http_response_code(200);
    echo json_encode([]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    Logger::log('START Error: ' . $e->getMessage());
}
?>