<?php

require_once(__DIR__ . '/../bootstrap.php');

header('Content-Type: application/json; charset=utf-8');

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