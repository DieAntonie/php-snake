<?php

require_once(__DIR__ . '/../bootstrap.php');

header('Content-Type: application/json; charset=utf-8');

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
        $moveResponse['move'] = 'up';
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