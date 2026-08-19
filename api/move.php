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
 * POST /move
 * Called every turn. Returns the snake's move decision.
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

    // Calculate the move
    $moveResponse = $snake->calculateMove($board);

    // Ensure move is valid
    $validMoves = ['up', 'down', 'left', 'right'];
    if (!in_array($moveResponse['move'], $validMoves)) {
        $moveResponse['move'] = 'down'; // Default to down if invalid
        $moveResponse['shout'] = 'Invalid move! Defaulting to down.';
    }

    // Log the move
    Logger::logJson([
        'event' => 'move',
        'game_id' => $game->getId(),
        'turn' => $turn,
        'move' => $moveResponse['move'],
        'health' => $snake->getHealth()
    ]);

    // Return the move response
    http_response_code(200);
    echo json_encode([
        'move' => $moveResponse['move'],
        'shout' => $moveResponse['shout'] ?? ''
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    Logger::log('MOVE Error: ' . $e->getMessage());
}
?>