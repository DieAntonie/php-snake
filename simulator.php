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
    <script src="simulator.js"></script>
</body>

</html>