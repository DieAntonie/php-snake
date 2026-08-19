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
 * POST /end
 * Called when the game ends. Return response is ignored.
 */

try {
    // Parse JSON request
    $request = json_decode(file_get_contents('php://input'), true);

    if ($request === null) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON']);
        exit;
    }

    // Create game entities
    $snake = new Snake($request['you'] ?? []);
    $board = new Board($request['board'] ?? []);
    $game = new Game($request['game'] ?? []);
    $turn = $request['turn'] ?? 0;

    // Log the game end
    Logger::logJson([
        'event' => 'game_end',
        'game_id' => $game->getId(),
        'final_turn' => $turn,
        'final_health' => $snake->getHealth(),
        'body_length' => count($snake->getBody())
    ]);

    // Return empty response (response is ignored)
    http_response_code(200);
    echo json_encode([]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    Logger::log('END Error: ' . $e->getMessage());
}
?>