<?php

require_once(__DIR__ . '/../bootstrap.php');

header('Content-Type: application/json; charset=utf-8');

/**
 * GET /
 * Called by the game engine to check connectivity and get snake details.
 * This is the Battlesnake Details endpoint.
 */

try {
    // Return snake customization
    http_response_code(200);
    echo json_encode([
        'apiversion' => '1',
        'author' => 'Your Name',
        'color' => '#888888',
        'head' => 'default',
        'tail' => 'default',
        'version' => '1.0.0'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>