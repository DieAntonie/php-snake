/**
 * simulator.js
 * A simple simulator for testing your Battlesnake against a static board.
 * This is not an official Battlesnake product.
 * @see https://docs.battlesnake.com/references/api/simulator
 */

/**
 * A tile on the game board.
 * @typedef {Object} Tile
 * @property {number} x - The x coordinate.
 * @property {number} y - The y coordinate.
 */
class Tile {
    /**
     * The x coordinate of the tile.
     * @type {number} 
     */
    x;

    /**
     * The y coordinate of the tile.
     * @type {number} 
     */
    y;

    /**
     * Creates a new tile.
     * @param {number} x The x coordinate of the tile.
     * @param {number} y The y coordinate of the tile.
     */
    constructor(x, y) {
        this.x = x;
        this.y = y;
    }
}

/**
 * A snake in the game.
 * @typedef {Object} Snake
 * @property {string} id - The id of the snake.
 * @property {string} name - The name of the snake.
 * @property {number} health - The health of the snake.
 * @property {Tile[]} body - The body of the snake.
 * @property {Tile} head - The head of the snake.
 * @property {number} length - The length of the snake.
 */
class Snake {
    /**
     * The id of the snake.
     * @type {string} 
     */
    id;
    /**
     * The name of the snake.
     * @type {string} 
     */
    name;
    /**
     * The health of the snake.
     * @type {number} 
     */
    health;
    /**
     * The body of the snake.
     * @type {Tile[]} 
     */
    body;
    /**
     * The head of the snake.
     * @type {Tile} 
     */
    head;
    /**
     * The length of the snake.
     * @type {number} 
     */
    length;

    /**
     * Creates a new snake.
     * @param {string} id The id of the snake.
     * @param {string} name The name of the snake.
     * @param {number} health The health of the snake.
     * @param {Tile[]} body The body of the snake.
     */
    constructor(id, name, health, body) {
        this.id = id;
        this.name = name;
        this.health = health;
        this.body = body;
        this.head = body[0];
        this.length = body.length;
    }
}

/**
 * A game board for the simulator.
 * @typedef {Object} Board
 * @property {number} width - The width of the board.
 * @property {number} height - The height of the board.
 * @property {Tile[]} food - The food tiles on the board.
 * @property {Tile[]} hazards - The hazard tiles on the board.
 * @property {Snake[]} snakes - The snakes on the board.
 */
class Board {
    /**
     * The width of the board.
     * @type {number} 
     */
    width;
    /**
     * The height of the board.
     * @type {number}
     */
    height;
    /**
     * The food tiles on the board.
     * @property {Tile[]} food
     */
    food;
    /**
     * The hazard tiles on the board.
     * @property {Tile[]} hazards
     */
    hazards;
    /**
     * The snakes on the board.
     * @property {Snake[]} snakes
     */
    snakes;

    /**
     * Creates a new board.
     * @param {number} width The width of the board.
     * @param {number} height The height of the board.
     * @param {Tile[]} food The food tiles on the board.
     * @param {Tile[]} hazards The hazard tiles on the board.
     * @param {Snake[]} snakes The snakes on the board.
     */
    constructor(width, height, food, hazards, snakes) {
        this.width = width;
        this.height = height;
        this.food = food;
        this.hazards = hazards;
        this.snakes = snakes;
    }
}

/**
 * A game for the simulator.
 * @typedef {Object} Game
 * @property {string} id - The id of the game.
 * @property {Object} ruleset - The ruleset of the game.
 * @property {string} map - The map of the game.
 * @property {string} source - The source of the game.
 * @property {number} timeout - The timeout of the game.
 */
class Game {
    /**
     * The id of the game.
     * @property {string} id
     */
    id;
    /**
     * The ruleset of the game.
     * @property {Object} ruleset
     */
    ruleset;
    /**
     * The map of the game.
     * @property {string} map
     */
    map;
    /**
     * The source of the game.
     * @property {string} source
     */
    source;
    /**
     * The timeout of the game.
     * @property {number} timeout
     */
    timeout;

    /**
     * Creates a new game.
     * @param {string} id The id of the game.
     * @param {Object} ruleset The ruleset of the game.
     * @param {string} map The map of the game.
     * @param {string} source The source of the game.
     * @param {number} timeout The timeout of the game.
     */
    constructor(id, ruleset, map, source, timeout) {
        this.id = id;
        this.ruleset = ruleset;
        this.map = map;
        this.source = source;
        this.timeout = timeout;
    }
}

/**
 * State of the simulator.
 */
const state = {
    game: null,
    board: null,
    you: null,
    turn: 0,
    maxTurns: 30,
    running: false,
    ended: false
};

/**
 * Directions for moving the snake.
 * @property {Tile} up - Move up.
 * @property {Tile} right - Move right.
 * @property {Tile} down - Move down.
 * @property {Tile} left - Move left.
 */
const directions = {
    up: new Tile(0, 1),
    right: new Tile(1, 0),
    down: new Tile(0, -1),
    left: new Tile(-1, 0)
};

/**
 * API paths for the simulator.
 */
const apiPaths = {
    start: '../api/start.php',
    move: '../api/move.php',
    end: '../api/end.php'
};

/**
 * DOM elements for the simulator.
 */
const elements = {
    board: document.getElementById('board'),
    events: document.getElementById('events'),
    fixture: document.getElementById('fixture'),
    height: document.getElementById('height'),
    maxTurns: document.getElementById('maxTurns'),
    play: document.getElementById('play'),
    reset: document.getElementById('reset'),
    status: document.getElementById('status'),
    step: document.getElementById('step'),
    turn: document.getElementById('turn'),
    width: document.getElementById('width')
};

/**
 * Check if two tiles are the same.
 * @param {Tile} a - The first tile.
 * @param {Tile} b - The second tile.
 * @return {boolean} True if the tiles are the same, false otherwise.
 */
const same = (a, b) => a.x === b.x && a.y === b.y;

/**
 * Create the game board.
 * @return {Board} The game board.
 */
function createBoard() {
    const width = Number(elements.width.value),
        height = Number(elements.height.value),
        fixture = elements.fixture.value;
    const food = fixture === 'hazard' ? [{
        x: width - 2,
        y: 1
    }] : [{
        x: Math.min(5, width - 2),
        y: 1
    }];
    const hazards = fixture === 'hazard' ? [{
        x: Math.min(5, width - 2),
        y: 1
    }] : [];
    const opponent = fixture === 'blocked' ? [{
        id: 'opponent',
        name: 'Obstacle',
        health: 100,
        body: [{
            x: 2,
            y: 1
        }],
        head: {
            x: 2,
            y: 1
        },
        length: 1
    }] : [];
    return {
        width,
        height,
        food,
        hazards,
        snakes: opponent
    };
}

/**
 * Create a new game.
 * @return {Game} The new game.
 */
function createGame() {
    return {
        id: `sim-${Date.now()}`,
        ruleset: {
            name: 'standard',
            version: 'v1.1.0'
        },
        map: 'standard',
        source: 'simulator',
        timeout: 500
    };
}

/**
 * Create a new snake.
 * @return {Snake} The new snake.
 */
function createSnake() {
    return {
        id: 'you',
        name: 'PHP Snake',
        health: 100,
        body: [{
            x: 1,
            y: 1
        }],
        head: {
            x: 1,
            y: 1
        },
        length: 1,
        latency: '0',
        shout: '',
        customizations: {
            color: '#197a5b',
            head: 'default',
            tail: 'default'
        }
    };
}

/**
 * Create the payload for API requests.
 * @return {Object} The API payload.
 */
function payload() {
    return {
        game: state.game,
        turn: state.turn,
        board: state.board,
        you: state.you
    };
}

/**
 * Log a message to the events panel.
 * @param {string} message - The message to log.
 * @param {Object} data - Optional data to include in the log.
 */
function log(message, data) {
    const row = document.createElement('div');
    row.className = 'event';
    row.innerHTML = `<b>${message}</b>${data ? '<br>' + JSON.stringify(data) : ''}`;
    elements.events.prepend(row);
}

/**
 * Make an API request.
 * @param {string} path - The API endpoint.
 * @param {Object} body - The request body.
 * @return {Promise<Object>} The API response.
 */
async function api(path, body) {
    const response = await fetch(path, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(body)
    });
    const data = await response.json();
    if (!response.ok) throw new Error(data.error || `HTTP ${response.status}`);
    return data;
}

/**
 * Render the game board.
 */
function render() {
    elements.board.innerHTML = '';
    elements.board.style.gridTemplateColumns = `repeat(${state.board.width}, var(--cell))`;
    for (let y = state.board.height - 1; y >= 0; y--)
        for (let x = 0; x < state.board.width; x++) {
            const cell = document.createElement('div'),
                tile = {
                    x,
                    y
                };
            cell.className = 'cell';
            if (state.board.hazards.some((item) => same(item, tile))) cell.classList.add('hazard');
            if (state.board.food.some((item) => same(item, tile))) cell.classList.add('food');
            if (state.board.snakes.some((snake) => snake.body.some((item) => same(item, tile)))) cell.classList.add('opponent');
            if (state.you.body.some((item) => same(item, tile))) cell.classList.add('you');
            if (same(state.you.head, tile)) cell.classList.add('head');
            elements.board.appendChild(cell);
        }
    elements.turn.textContent = `TURN ${state.turn} / ${state.maxTurns}`;
}

/**
 * Set the status message for the simulator.
 * @param {string} message - The status message.
 * @param {boolean} over - Whether the game is over.
 */
function setStatus(message, over = false) {
    elements.status.innerHTML = `<span>API status</span><strong>${message}</strong>`;
    elements.status.classList.toggle('over', over);
}

/**
 * Finish the game.
 * @param {string} message - The finish message.
 */
function finish(message) {
    state.ended = true;
    state.running = false;
    elements.play.textContent = 'Auto-play';
    elements.step.disabled = true;
    setStatus(message, true);
    api(apiPaths.end, payload()).then(() => log('END', {
        turn: state.turn
    })).catch((error) => log('END ERROR', error.message));
}

/**
 * Check if a tile collides with the game board or any snake.
 * @param {Tile} tile - The tile to check.
 * @return {boolean} True if the tile collides, false otherwise.
 */
function collision(tile) {
    return tile.x < 0 || tile.y < 0 || tile.x >= state.board.width || tile.y >= state.board.height || state.you.body.some((item) => same(item, tile)) || state.board.snakes.some((snake) => snake.body.some((item) => same(item, tile)));
}

/**
 * Take a step in the game.
 * @return {Promise<void>} A promise that resolves when the step is complete.
 */
async function step() {
    if (state.ended) return;
    setStatus('requesting');
    try {
        const response = await api(apiPaths.move, payload());
        log(`MOVE ${state.turn}`, response);
        const delta = directions[response.move];
        if (!delta) throw new Error(`Invalid move: ${response.move}`);
        const next = {
            x: state.you.head.x + delta.x,
            y: state.you.head.y + delta.y
        };
        if (collision(next)) {
            render();
            finish(`collision (${response.move})`);
            return;
        }
        const eating = state.board.food.some((item) => same(item, next));
        state.you.head = next;
        state.you.body.unshift(next);
        if (!eating) state.you.body.pop();
        else state.you.length += 1;
        state.you.health = eating ? 100 : state.you.health - 1;
        state.board.food = state.board.food.filter((item) => !same(item, next));
        state.turn += 1;
        render();
        if (state.you.health <= 0) finish('health depleted');
        else if (state.turn >= state.maxTurns) finish('turn limit reached');
        else {
            setStatus('ready');
        }
    } catch (error) {
        state.running = false;
        setStatus('request failed', true);
        log('ERROR', error.message);
    }
}

/**
 * Play or pause the game.
 */
async function play() {
    if (state.running) {
        state.running = false;
        elements.play.textContent = 'Auto-play';
        return;
    }
    state.running = true;
    elements.play.textContent = 'Pause';
    while (state.running && !state.ended) {
        await step();
        await new Promise((resolve) => setTimeout(resolve, 450));
    }
}

/**
 * Reset the game to its initial state.
 */
async function reset() {
    state.game = createGame();
    state.board = createBoard();
    state.turn = 0;
    state.maxTurns = Number(elements.maxTurns.value);
    state.ended = false;
    state.running = false;
    state.you = createSnake();
    elements.step.disabled = false;
    elements.play.textContent = 'Auto-play';
    elements.events.innerHTML = '';
    render();
    setStatus('starting');
    try {
        await api(apiPaths.start, payload());
        log('START', {
            game: state.game.id,
            fixture: elements.fixture.value
        });
        setStatus('ready');
    } catch (error) {
        setStatus('start failed', true);
        log('START ERROR', error.message);
    }
}

elements.reset.addEventListener('click', reset);
elements.step.addEventListener('click', step);
elements.play.addEventListener('click', play);
elements.fixture.addEventListener('change', reset);
window.addEventListener('load', reset);
