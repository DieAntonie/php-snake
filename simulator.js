const state = {
    game: null,
    board: null,
    you: null,
    turn: 0,
    maxTurns: 30,
    running: false,
    ended: false
};
const directions = {
    up: {
        x: 0,
        y: 1
    },
    right: {
        x: 1,
        y: 0
    },
    down: {
        x: 0,
        y: -1
    },
    left: {
        x: -1,
        y: 0
    }
};
const apiPaths = {
    start: '../api/start.php',
    move: '../api/move.php',
    end: '../api/end.php'
};
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
const same = (a, b) => a.x === b.x && a.y === b.y;

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

function payload() {
    return {
        game: state.game,
        turn: state.turn,
        board: state.board,
        you: state.you
    };
}

function log(message, data) {
    const row = document.createElement('div');
    row.className = 'event';
    row.innerHTML = `<b>${message}</b>${data ? '<br>' + JSON.stringify(data) : ''}`;
    elements.events.prepend(row);
}
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

function render() {
    elements.board.innerHTML = '';
    elements.board.style.gridTemplateColumns = `repeat(${state.board.width}, var(--cell))`;
    for (let y = state.board.height - 1; y >= 0; y--)
        for (let x = 0; x < state.board.width; x++) {
            const cell = document.createElement('div'),
                point = {
                    x,
                    y
                };
            cell.className = 'cell';
            if (state.board.hazards.some((item) => same(item, point))) cell.classList.add('hazard');
            if (state.board.food.some((item) => same(item, point))) cell.classList.add('food');
            if (state.board.snakes.some((snake) => snake.body.some((item) => same(item, point)))) cell.classList.add('opponent');
            if (state.you.body.some((item) => same(item, point))) cell.classList.add('you');
            if (same(state.you.head, point)) cell.classList.add('head');
            elements.board.appendChild(cell);
        }
    elements.turn.textContent = `TURN ${state.turn} / ${state.maxTurns}`;
}

function setStatus(message, over = false) {
    elements.status.innerHTML = `<span>API status</span><strong>${message}</strong>`;
    elements.status.classList.toggle('over', over);
}

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

function collision(point) {
    return point.x < 0 || point.y < 0 || point.x >= state.board.width || point.y >= state.board.height || state.you.body.some((item) => same(item, point)) || state.board.snakes.some((snake) => snake.body.some((item) => same(item, point)));
}
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
