<?php
require_once(__DIR__ . '/bootstrap.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Snake - Battlesnake AI Server</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="overview-page">
    <div class="container">
        <header>
            <h1>🐍 PHP Snake</h1>
            <p class="subtitle">A Battlesnake AI Server Implementation</p>
        </header>
        
        <div class="content">
            <div class="card">
                <h2>About</h2>
                <p>PHP Snake is a modernized backend server implementing the Battlesnake game protocol (API v1). It provides AI decision-making for a snake to compete on a shared game board against other players.</p>
                <p>Recently updated to support the latest Battlesnake features including hazards, ruleset information, and proper snake customizations.</p>
            </div>
            
            <div class="card">
                <h2>Project Stats</h2>
                <div class="stats">
                    <div class="stat">
                        <div class="stat-number">4</div>
                        <div class="stat-label">Core Classes</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">4</div>
                        <div class="stat-label">API Endpoints</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">✅</div>
                        <div class="stat-label">Modernized</div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <h2>Core Components</h2>
                <ul class="core-list">
                    <li><strong>Snake</strong> - AI logic and movement decision making</li>
                    <li><strong>Board</strong> - Game board state with hazards support</li>
                    <li><strong>Game</strong> - Game metadata and ruleset information</li>
                    <li><strong>Logger</strong> - JSON and text logging utilities</li>
                </ul>
            </div>
            
            <div class="card">
                <h2>Tech Stack</h2>
                <div class="tech-stack">
                    <span class="badge">PHP 7.0+</span>
                    <span class="badge">Battlesnake API v1</span>
                    <span class="badge">JSON</span>
                    <span class="badge">Type Hints</span>
                </div>
            </div>
        </div>
        
        <div class="endpoints">
            <h2>API Endpoints (Battlesnake API v1)</h2>

            <div class="endpoint">
                <span class="endpoint-method">GET</span>
                <span class="endpoint-path">/simulator.php</span>
                <div class="endpoint-desc"><strong>Game Simulator:</strong> Run the API through repeatable scenarios, inspect each move response, and watch the board state advance.</div>
            </div>
            
            <div class="endpoint">
                <span class="endpoint-method">GET</span>
                <span class="endpoint-path">/info.php</span>
                <div class="endpoint-desc"><strong>Battlesnake Details:</strong> Returns snake customization (color, head, tail, version). Called by game engine to verify connectivity.</div>
            </div>
            
            <div class="endpoint">
                <span class="endpoint-method">POST</span>
                <span class="endpoint-path">/api/start.php</span>
                <div class="endpoint-desc"><strong>Game Started:</strong> Called when a new game begins. Receives initial board state, game metadata, and your snake info. Response is ignored.</div>
            </div>
            
            <div class="endpoint">
                <span class="endpoint-method">POST</span>
                <span class="endpoint-path">/api/move.php</span>
                <div class="endpoint-desc"><strong>Main Game Loop:</strong> Called every turn. Receives current board state and returns your snake's move direction (up/down/left/right) plus optional shout message.</div>
            </div>
            
            <div class="endpoint">
                <span class="endpoint-method">POST</span>
                <span class="endpoint-path">/api/end.php</span>
                <div class="endpoint-desc"><strong>Game Over:</strong> Called when the game ends. Use to log results and cleanup resources. Response is ignored.</div>
            </div>
        </div>
        
        <footer>
            <p>PHP Snake • Battlesnake AI Server • <?php echo date('Y'); ?></p>
        </footer>
    </div>
</body>
</html>