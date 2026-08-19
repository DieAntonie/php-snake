<?php
/**
 * GET /
 * Battlesnake Details endpoint
 * Called by the game engine to check connectivity and get snake details.
 */

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    // Return snake customization details
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode([
        "apiVersion" => "1",
        "author" => "Chris Antonie Pieterse",
        "color" => "#ff00ff",
        "headType" => "bendr",
        "tailType" => "pixel"
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>