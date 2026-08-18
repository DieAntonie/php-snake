<?php

require_once(__DIR__ . '/../index.php');

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