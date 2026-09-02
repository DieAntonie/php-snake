<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Snake Simulator</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body class="simulator-page">
    <main class="wrap">
        <header>
            <div>
                <p class="kicker">API logic workbench</p>
                <h1>Snake<br>simulator</h1>
            </div>
            <p class="intro">Drive a deterministic game through the same start, move, and end endpoints used by Battlesnake.</p>
        </header>
        <div class="layout">
            <section class="panel board-panel">
                <div class="board-top">
                    <h2>Live board</h2><span class="turn" id="turn">TURN 0 / 30</span>
                </div>
                <div id="board" aria-label="Snake game board"></div>
                <div class="legend"><span><i class="swatch"></i>you</span><span><i class="swatch food"></i>food</span><span><i class="swatch hazard"></i>hazard</span><span><i class="swatch opponent"></i>opponent</span></div>
            </section>
            <div class="side">
                <section class="panel controls">
                    <h2>Scenario</h2>
                    <div class="fields">
                        <div><label for="width">Width</label><input id="width" type="number" min="3" max="20" value="8"></div>
                        <div><label for="height">Height</label><input id="height" type="number" min="3" max="20" value="8"></div>
                    </div>
                    <label for="maxTurns">Turn limit</label><input id="maxTurns" type="number" min="1" max="500" value="30">
                    <label for="fixture">Fixture</label><select id="fixture">
                        <option value="food">Food route</option>
                        <option value="hazard">Hazard avoidance</option>
                        <option value="blocked">Opponent obstacle</option>
                    </select>
                    <div class="toolbar"><button id="reset">Reset</button><button id="step">Step</button><button id="play">Auto-play</button></div>
                    <div class="status" id="status"><span>API status</span><strong>ready</strong></div>
                </section>
                <section class="panel events">
                    <h2>Request / response</h2>
                    <div id="events" aria-live="polite"></div>
                </section>
            </div>
        </div>
    </main>
    <script>
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
        const $ = (id) => document.getElementById(id);
        const same = (a, b) => a.x === b.x && a.y === b.y;

        function fixtureData() {
            const width = Number($('width').value),
                height = Number($('height').value),
                fixture = $('fixture').value;
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
            $('events').prepend(row);
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
            const board = $('board');
            board.innerHTML = '';
            board.style.gridTemplateColumns = `repeat(${state.board.width}, var(--cell))`;
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
                    board.appendChild(cell);
                }
            $('turn').textContent = `TURN ${state.turn} / ${state.maxTurns}`;
        }

        function setStatus(message, over = false) {
            $('status').innerHTML = `<span>API status</span><strong>${message}</strong>`;
            $('status').classList.toggle('over', over);
        }

        function finish(message) {
            state.ended = true;
            state.running = false;
            $('play').textContent = 'Auto-play';
            $('step').disabled = true;
            setStatus(message, true);
            api('../api/end.php', payload()).then(() => log('END', {
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
                const response = await api('../api/move.php', payload());
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
                $('play').textContent = 'Auto-play';
                return;
            }
            state.running = true;
            $('play').textContent = 'Pause';
            while (state.running && !state.ended) {
                await step();
                await new Promise((resolve) => setTimeout(resolve, 450));
            }
        }
        async function reset() {
            state.game = {
                id: `sim-${Date.now()}`,
                ruleset: {
                    name: 'standard',
                    version: 'v1.1.0'
                },
                map: 'standard',
                source: 'simulator',
                timeout: 500
            };
            state.board = fixtureData();
            state.turn = 0;
            state.maxTurns = Number($('maxTurns').value);
            state.ended = false;
            state.running = false;
            state.you = {
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
            $('step').disabled = false;
            $('play').textContent = 'Auto-play';
            $('events').innerHTML = '';
            render();
            setStatus('starting');
            try {
                await api('../api/start.php', payload());
                log('START', {
                    game: state.game.id,
                    fixture: $('fixture').value
                });
                setStatus('ready');
            } catch (error) {
                setStatus('start failed', true);
                log('START ERROR', error.message);
            }
        }
        $('reset').addEventListener('click', reset);
        $('step').addEventListener('click', step);
        $('play').addEventListener('click', play);
        $('fixture').addEventListener('change', reset);
        window.addEventListener('load', reset);
    </script>
</body>

</html>