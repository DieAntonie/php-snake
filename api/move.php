<?php
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
    $json = json_encode([
        'event' => 'move',
        'game_id' => $game->getId(),
        'turn' => $turn,
        'move' => $moveResponse['move'],
        'health' => $snake->getHealth()
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    file_put_contents('logs.txt', $json . PHP_EOL . PHP_EOL, FILE_APPEND | LOCK_EX);

    // Return the move response
    http_response_code(200);
    echo json_encode([
        'move' => $moveResponse['move'],
        'shout' => $moveResponse['shout'] ?? ''
    ]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
    file_put_contents('logs.txt', 'MOVE Error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND | LOCK_EX);
    http_response_code(505);
}
?>