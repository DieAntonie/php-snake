<?php
    function logger($file = 'logs.txt', $data)
    {
        file_put_contents($file, $data.PHP_EOL , FILE_APPEND | LOCK_EX);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Snake - Battlesnake AI Server</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        header {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
            text-align: center;
        }
        
        h1 {
            color: #667eea;
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .subtitle {
            color: #666;
            font-size: 1.1em;
        }
        
        .content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .card h2 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.5em;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        
        .card p {
            color: #555;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        
        .endpoints {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            grid-column: 1 / -1;
        }
        
        .endpoints h2 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 1.5em;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        
        .endpoint {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
            border-radius: 5px;
        }
        
        .endpoint-method {
            display: inline-block;
            padding: 3px 10px;
            background: #667eea;
            color: white;
            border-radius: 3px;
            font-weight: bold;
            margin-right: 10px;
            font-family: 'Courier New', monospace;
        }
        
        .endpoint-path {
            font-family: 'Courier New', monospace;
            color: #764ba2;
            font-weight: bold;
        }
        
        .endpoint-desc {
            color: #666;
            margin-top: 8px;
            font-size: 0.95em;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        
        .stat {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 1.8em;
            font-weight: bold;
            color: #667eea;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9em;
            margin-top: 5px;
        }
        
        .tech-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        
        .badge {
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
        }
        
        footer {
            text-align: center;
            color: white;
            margin-top: 30px;
            font-size: 0.9em;
        }
        
        @media (max-width: 768px) {
            .content {
                grid-template-columns: 1fr;
            }
            
            .stats {
                grid-template-columns: 1fr;
            }
            
            h1 {
                font-size: 1.8em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🐍 PHP Snake</h1>
            <p class="subtitle">A Battlesnake AI Server Implementation</p>
        </header>
        
        <div class="content">
            <div class="card">
                <h2>About</h2>
                <p>PHP Snake is a backend server implementing the Battlesnake game protocol. It provides AI decision-making for a snake to compete on a shared game board against other players.</p>
                <p>This project demonstrates game AI logic, API design, and real-time game state management in PHP.</p>
            </div>
            
            <div class="card">
                <h2>Project Stats</h2>
                <div class="stats">
                    <div class="stat">
                        <div class="stat-number">5</div>
                        <div class="stat-label">PHP Classes</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">4</div>
                        <div class="stat-label">API Endpoints</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">15×15</div>
                        <div class="stat-label">Board Size</div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <h2>Core Components</h2>
                <ul style="margin-left: 20px; color: #555; line-height: 1.8;">
                    <li><strong>Snake Class</strong> - Represents a player snake with movement logic</li>
                    <li><strong>Board Class</strong> - Models the game board state</li>
                    <li><strong>MoveResponse</strong> - Encapsulates movement decisions</li>
                    <li><strong>Logger</strong> - Utility for debugging and logging</li>
                </ul>
            </div>
            
            <div class="card">
                <h2>Tech Stack</h2>
                <div class="tech-stack">
                    <span class="badge">PHP 7.0+</span>
                    <span class="badge">JSON</span>
                    <span class="badge">REST API</span>
                    <span class="badge">Battlesnake</span>
                </div>
            </div>
        </div>
        
        <div class="endpoints">
            <h2>API Endpoints</h2>
            
            <div class="endpoint">
                <span class="endpoint-method">POST</span>
                <span class="endpoint-path">/api/start.php</span>
                <div class="endpoint-desc">Initialize the snake. Returns snake appearance configuration (color, head type, tail type).</div>
            </div>
            
            <div class="endpoint">
                <span class="endpoint-method">POST</span>
                <span class="endpoint-path">/api/move.php</span>
                <div class="endpoint-desc">Main game loop. Receives board state and current snake position. Returns the next move direction (up, down, left, right).</div>
            </div>
            
            <div class="endpoint">
                <span class="endpoint-method">POST</span>
                <span class="endpoint-path">/api/ping.php</span>
                <div class="endpoint-desc">Health check endpoint. Called to verify the server is responsive and operational.</div>
            </div>
            
            <div class="endpoint">
                <span class="endpoint-method">POST</span>
                <span class="endpoint-path">/api/end.php</span>
                <div class="endpoint-desc">Game termination handler. Called when the game ends to cleanup and log results.</div>
            </div>
        </div>
        
        <footer>
            <p>PHP Snake • Battlesnake AI Server • <?php echo date('Y'); ?></p>
        </footer>
    </div>
</body>
</html>