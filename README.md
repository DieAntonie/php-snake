# PHP Snake - Modernized Battlesnake AI Server

[![Tests](https://github.com/DieAntonie/php-snake/actions/workflows/tests.yml/badge.svg?branch=master)](https://github.com/DieAntonie/php-snake/actions/workflows/tests.yml)

A modernized PHP implementation of the [Battlesnake](https://www.battlesnake.com/) game API (v1). This project has been updated to be fully compatible with the current Battlesnake specification.

## 🎯 Overview

PHP Snake is a backend server that implements AI logic for the Battlesnake game protocol. Your snake competes on a shared game board against other players in real-time.

## 🚀 What's New (Modernization)

This project has been updated from a 2019 implementation to be fully compatible with Battlesnake API v1:

### Breaking Changes Fixed:
- ✅ **New Request/Response Structure** - Updated to match current Battlesnake API format
- ✅ **Game Object** - Now includes ruleset, map, source, and timeout information
- ✅ **Board Hazards** - Added support for hazardous tiles on the board
- ✅ **Snake Customizations** - Proper handling of color, head, and tail customizations
- ✅ **Move Response** - Updated to include optional "shout" messages
- ✅ **Modern PHP** - Type hints, access modifiers, and improved OOP practices

### New Features:
- 📝 **Structured Logging** - JSON logging for debugging and analytics
- 🎮 **Improved AI** - Collision- and hazard-aware food targeting
- 🛡️ **Error Handling** - Comprehensive exception handling in all endpoints
- 📚 **PHPDoc Comments** - Full documentation for all methods

## 📋 Project Structure

```
php-snake/
├── index.php              # Core utilities (Logger class)
├── snake.php              # Snake entity and AI logic
├── board.php              # Board state management
├── moveResponse.php       # Game state class
├── info.php               # GET / endpoint (Battlesnake Details)
├── api/
│   ├── start.php         # POST /start (Game Started)
│   ├── move.php          # POST /move (Main Game Loop)
│   ├── end.php           # POST /end (Game Over)
│   └── ping.php          # Alternate Battlesnake Details endpoint
└── README.md
```

## 🔌 API Endpoints

All endpoints accept JSON POST requests and return JSON responses.

### GET / (or /info.php)
**Battlesnake Details** - Called to retrieve snake customization and verify connectivity.

**Response:**
```json
{
  "apiversion": "1",
  "author": "Your Name",
  "color": "#888888",
  "head": "default",
  "tail": "default",
  "version": "1.0.0"
}
```

### POST /start (or /api/start.php)
**Game Started** - Called when a new game begins.

**Request:**
```json
{
  "game": {
    "id": "game-id",
    "ruleset": { "name": "standard", "version": "v1.1.0" },
    "map": "standard",
    "source": "league",
    "timeout": 500
  },
  "turn": 0,
  "board": { ... },
  "you": { ... }
}
```

**Response:** Empty JSON object (response is ignored)

### POST /move (or /api/move.php)
**Main Game Loop** - Called every turn to request your snake's move.

**Request:**
```json
{
  "game": { ... },
  "turn": 14,
  "board": {
    "height": 11,
    "width": 11,
    "food": [{"x": 5, "y": 5}],
    "hazards": [{"x": 3, "y": 2}],
    "snakes": [ ... ]
  },
  "you": {
    "id": "snake-id",
    "name": "Your Snake",
    "health": 54,
    "body": [{"x": 0, "y": 0}, {"x": 1, "y": 0}],
    "latency": "111",
    "head": {"x": 0, "y": 0},
    "length": 3,
    "shout": "message",
    "customizations": { "color": "#FF0000", "head": "pixel", "tail": "pixel" }
  }
}
```

**Response:**
```json
{
  "move": "up",
  "shout": "I'm moving up!"
}
```

Valid moves: `"up"`, `"down"`, `"left"`, `"right"`

### POST /end (or /api/end.php)
**Game Over** - Called when the game ends.

**Request:** Same structure as /move endpoint

**Response:** Empty JSON object (response is ignored)

## 🤖 AI Logic

The default AI uses a **local safety-first algorithm**:

1. Find the nearest food on the board
2. Reject moves outside the board or into this snake's body
3. Reject moves into other snakes' bodies
4. Prefer non-hazardous moves
5. Move towards the nearest food among the remaining candidates

This is a one-step safety strategy rather than full pathfinding. You can improve it by:
- Implementing path finding (A*, Dijkstra)
- Predicting opponent movements
- Managing health and timing

## 🛠️ Installation & Usage

### Requirements
- PHP 7.0+
- Web server (Apache, Nginx, or PHP built-in server)

### Local Development

```bash
# Navigate to project directory
cd php-snake

# Start PHP built-in server
php -S localhost:8000

# Test the info endpoint
curl http://localhost:8000/info.php
```

### Tests

The project includes a dependency-free CLI test suite organized by domain:

```bash
# Run all tests
php tests/run.php

# Run one domain independently
php tests/board_tests.php
php tests/snake_tests.php
php tests/game_tests.php

# Run the coverage check locally
php -d xdebug.mode=coverage tests/coverage.php
```

The suite checks board boundaries, hazards, food targeting, hazard avoidance, opponent collision avoidance, and game metadata. The coverage check requires Xdebug with coverage mode enabled. Endpoint behavior can be exercised separately through the PHP built-in server and HTTP requests.

GitHub Actions also runs the suite with Xdebug and requires at least 70% line coverage for the core classes.

### Deployment

Configure your Battlesnake server URL to point to your deployed instance:
- Info endpoint: `http://your-domain.com/info.php`
- Start endpoint: `http://your-domain.com/api/start.php`
- Move endpoint: `http://your-domain.com/api/move.php`
- End endpoint: `http://your-domain.com/api/end.php`

Or restructure to use routing at the root:
- `GET /` → info.php
- `POST /start` → api/start.php
- `POST /move` → api/move.php
- `POST /end` → api/end.php

## 📊 Logging

The system logs events to:
- **logs.txt** - Text logs with general information
- **logs.json** - JSON-formatted logs for analysis

Each turn's move decision and game start/end events are logged.

## 🧩 Key Classes

### Snake
Represents your snake with methods for:
- `getHead()` - Get current head position
- `getBody()` - Get all body segments
- `getHealth()` - Get current health
- `calculateMove(Board $board)` - Determine next move

### Board
Represents the game board with:
- `getHeight()` / `getWidth()` - Board dimensions
- `getFood()` - All food positions
- `getSnakes()` - All snakes in play
- `getHazards()` - Hazardous tiles
- `isValidCoordinate()` - Check if position is in bounds
- `isHazard()` - Check if position is a hazard

### Game
Represents game metadata:
- `getId()` - Unique game identifier
- `getRuleset()` - Game rules and settings
- `getTimeout()` - Response timeout in ms

### Logger
Utility for logging:
- `Logger::log($message)` - Write text log
- `Logger::logJson($data)` - Write JSON log

## 📖 Resources

- [Battlesnake Official Docs](https://docs.battlesnake.com/)
- [Battlesnake API Reference](https://docs.battlesnake.com/api)
- [Customization Guide](https://docs.battlesnake.com/guides/customizations)
- [Play Battlesnake](https://play.battlesnake.com/)

## 🎮 Next Steps

1. Customize your snake's appearance (color, head, tail) in the info endpoint
2. Improve the AI algorithm in `Snake::calculateMove()`
3. Add validation for board boundaries and collision detection
4. Implement advanced pathfinding algorithms
5. Deploy to a hosting platform and register your snake

## 📝 License

MIT License - Feel free to use and modify as needed.

---

**Last Updated:** 2026-08-18  
**Battlesnake API Version:** 1  
**PHP Version Required:** 7.0+
