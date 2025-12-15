<?php 
$server_start = microtime(true);
include 'menu.php';

$x_text = "Лабораторна 5"; 
$y_text = "Lab 5";

$block2_text = "<p>“I feel it coming, I feel it coming, babe…” — The Weeknd</p>";
$block3_text = "<p>“Save your tears for another day…” — The Weeknd</p>";
$block4_text = "<p>“In your eyes, I see there's something burning inside you…” — The Weeknd</p>";
$block5_text = "<p>“And I said, ooh, I'm blinded by the lights…” — The Weeknd</p>";
$block6_text = "<p>“Call out my name, and I'll be on my way…” — The Weeknd</p>";
$block7_text = "<p>“I can't feel my face when I'm with you, but I love it…” — The Weeknd</p>";

$block2_img = "<img src='images/weeknd_concert1.jpg' alt='Weeknd 1' style='width:100%;border-radius:8px;'>";
$block5_img = "<img src='images/weeknd_concert2.png' alt='Weeknd 2' style='width:100%;border-radius:8px;'>";
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>IS32_LemaevaSasha_Lab5</title>
    <link rel="stylesheet" href="style.css">
    <style>
        #btn-play { 
            display: block; 
            margin: 10px auto; 
            padding: 8px 16px; 
            background: #3bffbaff; 
            color: #333; 
            border: 2px solid #333; 
            font-weight: bold; 
            cursor: pointer; 
            border-radius: 4px; 
        }

        #work { 
            display: none; 
            position: fixed; 
            top: 5%; 
            left: 5%; 
            width: 90%; 
            height: 90%; 
            background: rgba(255, 255, 255, 0.98); 
            border: 2px solid #000; 
            z-index: 9999; 
            box-shadow: 0 0 50px rgba(0,0,0,0.5); 
        }

        #controls { 
            position: absolute; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 50px; 
            background: #eee; 
            border-bottom: 1px solid #ccc; 
            padding: 5px 10px; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            box-sizing: border-box; 
        }

        #anim { 
            position: absolute; 
            bottom: 0; 
            left: 0; 
            width: calc(100% - 10px); 
            height: calc(100% - 50px); 
            margin: 0 5px; 
            border: 5px solid yellow; 
            background: url('lab5_texture_gen.php') repeat; 
            overflow: hidden; 
            box-sizing: border-box; 
        }

        .square { 
            position: absolute; 
            width: 15px; 
            height: 15px; 
            box-shadow: 1px 1px 2px rgba(0,0,0,0.5); 
        }

        #sq1 { background: blue; } 
        #sq2 { background: orange; }

        .btn-lab { 
            padding: 5px 15px; 
            cursor: pointer; 
            font-weight: bold; 
            border: 1px solid #999; 
            border-radius: 3px; 
            margin-right: 5px; 
        }

        #msg-log { 
            font-family: monospace; 
            font-size: 11px; 
            margin-left: 10px; 
            color: #555; 
        }

        /* Стилі для таблиці результатів */
        .results-table { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 10px; 
            background: white; 
        }

        .results-table th, .results-table td { 
            border: 1px solid #ccc; 
            padding: 3px; 
            text-align: left; 
        }

        .results-table th { background: #e0e0e0; }

        /* Стиль для скрол-контейнера */
        .scroll-container {
            max-height: 250px; 
            overflow-y: auto; 
            border: 1px solid #999;
            background: #fff;
        }

        #perf-badge { 
            position: fixed; 
            right: 12px; 
            bottom: 12px; 
            background: rgba(17,24,39,.9); 
            color: #fff; 
            padding: 8px 10px; 
            border-radius: 8px; 
            font-size: 12px; 
            z-index: 9998; 
            font-family: sans-serif; 
        }
    </style>
</head>
<body>

<div class="container">
    <header class="block1">
        <nav>
            <ul>
                <?php foreach($menu as $l=>$n): ?>
                    <li><a href="<?= $l ?>"><?= $n ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <div class="blockX"><?= $x_text ?></div>
    </header>

    <div class="block2"><?= $block2_text ?><?= $block2_img ?></div>
    <div class="block3"><?= $block3_text ?></div>
    <div class="block4"><?= $block4_text ?></div>
    <div class="block5" id="block5-content"><?= $block5_text ?><?= $block5_img ?></div>
    
    <div class="block6">
        <?= $block6_text ?>
        <button id="btn-play">PLAY ANIMATION</button>
    </div>

    <footer class="block7">
        <?= $block7_text ?>
        <div class="blockY"><?= $y_text ?></div>
    </footer>
</div>

<div id="work">
    <div id="controls">
        <div>
            <button id="btn-close" class="btn-lab" style="background:#ffcccc;">Close</button>
            <button id="btn-action" class="btn-lab" style="background:#ccffcc;">Start</button>
        </div>
        <div>
            <span>Last Event:</span>
            <span id="msg-log">Ready</span>
        </div>
    </div>
    <div id="anim">
        <div id="sq1" class="square" style="display:none;"></div>
        <div id="sq2" class="square" style="display:none;"></div>
    </div>
</div>

<script>
    window.__serverGenMs = <?php echo round((microtime(true) - $server_start) * 1000, 1); ?>;

    document.addEventListener('DOMContentLoaded', () => {
        const badge = document.createElement('div'); 
        badge.id = 'perf-badge'; 
        badge.innerHTML = `Server gen: ${window.__serverGenMs} ms`; 
        document.body.appendChild(badge);

        const workLayer = document.getElementById('work'), 
              animArea = document.getElementById('anim'), 
              msgLog = document.getElementById('msg-log'), 
              block5 = document.getElementById('block5-content');
        
        const sq1 = document.getElementById('sq1'), 
              sq2 = document.getElementById('sq2');
        
        const btnPlay = document.getElementById('btn-play'), 
              btnClose = document.getElementById('btn-close'), 
              btnAction = document.getElementById('btn-action');

        let isAnimating = false, 
            eventCounter = 0, 
            timerId = null, 
            gameState = 'ready', 
            localStorageLogs = [];
        
        let pos1 = {x:0,y:0}, vel1 = {x:0,y:0}, 
            pos2 = {x:0,y:0}, vel2 = {x:0,y:0}; 
        
        const size = 15;

        btnPlay.addEventListener('click', (e) => {
            e.stopPropagation(); 
            workLayer.style.display = 'block'; 
            resetGame();
            fetch('my_api.php?action=clear').catch(e => console.error("Clear error", e));
        });

        btnClose.addEventListener('click', () => { 
            stopAnimation(); 
            workLayer.style.display = 'none'; 
            finishSessionAndShowResults(); 
        });

        btnAction.addEventListener('click', () => {
            if (gameState === 'ready' || gameState === 'collided') {
                startGame();
            } else if (gameState === 'running') {
                stopGameByUser();
            } else if (gameState === 'stopped') {
                startGame();
            }
        });

        function updateActionButton() {
            if (gameState === 'ready') btnAction.textContent = 'Start';
            else if (gameState === 'running') btnAction.textContent = 'Stop';
            else if (gameState === 'stopped') btnAction.textContent = 'Start';
            else if (gameState === 'collided') btnAction.textContent = 'Reload';
        }

        function resetGame() { 
            gameState = 'ready'; 
            updateActionButton(); 
            sq1.style.display = 'none'; 
            sq2.style.display = 'none'; 
            eventCounter = 0; 
            localStorageLogs = []; 
            msgLog.textContent = "Ready"; 
        }
    
        function initPositions() {
            const W = animArea.clientWidth, H = animArea.clientHeight;
            
            pos1.x = W - size; 
            pos1.y = Math.floor(Math.random() * (H - size));
            
            pos2.x = Math.floor(Math.random() * (W - size)); 
            pos2.y = H - size;
            
            vel1.x = -(2 + Math.random() * 3); 
            vel1.y = (Math.random() > .5 ? 1 : -1) * (2 + Math.random() * 3);
            
            vel2.x = (Math.random() > .5 ? 1 : -1) * (2 + Math.random() * 3); 
            vel2.y = -(2 + Math.random() * 3);
            
            updateSquaresDOM(); 
            sq1.style.display='block'; 
            sq2.style.display='block'; 
            logEvent("Start positions set");
        }

        function startGame() { 
            if(gameState === 'ready' || gameState === 'collided') initPositions(); 
            gameState = 'running'; 
            updateActionButton(); 
            isAnimating = true; 
            timerId = setInterval(gameLoop, 40); 
        }

        function stopGameByUser() { 
            isAnimating = false; 
            clearInterval(timerId); 
            gameState = 'stopped'; 
            updateActionButton(); 
            logEvent("Stopped by user"); 
        }

        function stopAnimation() { 
            if(timerId) clearInterval(timerId); 
            isAnimating = false; 
        }

        function gameLoop() {
            if (!isAnimating) return;
            const W = animArea.clientWidth, H = animArea.clientHeight;
            
            pos1.x += vel1.x; pos1.y += vel1.y; 
            pos2.x += vel2.x; pos2.y += vel2.y;
            
            let evt = false;

            if(pos1.x <= 0){ pos1.x=0; vel1.x*=-1; logEvent("Blue Hit Left"); evt=true; }
            if(pos1.x >= W-size){ pos1.x=W-size; vel1.x*=-1; logEvent("Blue Hit Right"); evt=true; }
            if(pos1.y <= 0){ pos1.y=0; vel1.y*=-1; logEvent("Blue Hit Top"); evt=true; }
            if(pos1.y >= H-size){ pos1.y=H-size; vel1.y*=-1; logEvent("Blue Hit Bottom"); evt=true; }
            
            if(pos2.x <= 0){ pos2.x=0; vel2.x*=-1; logEvent("Orange Hit Left"); evt=true; }
            if(pos2.x >= W-size){ pos2.x=W-size; vel2.x*=-1; logEvent("Orange Hit Right"); evt=true; }
            if(pos2.y <= 0){ pos2.y=0; vel2.y*=-1; logEvent("Orange Hit Top"); evt=true; }
            if(pos2.y >= H-size){ pos2.y=H-size; vel2.y*=-1; logEvent("Orange Hit Bottom"); evt=true; }

            if (checkCollision(pos1, pos2)) { 
                isAnimating = false; 
                clearInterval(timerId); 
                gameState = 'collided'; 
                updateActionButton(); 
                logEvent("COLLISION!"); 
                return; 
            }

            if (!evt) logEvent("Move");
            updateSquaresDOM();
        }

        function checkCollision(p1, p2) { 
            return (p1.x < p2.x + size && p1.x + size > p2.x && p1.y < p2.y + size && p1.y + size > p2.y); 
        }

        function updateSquaresDOM() { 
            sq1.style.left = pos1.x + 'px'; 
            sq1.style.top = pos1.y + 'px'; 
            sq2.style.left = pos2.x + 'px'; 
            sq2.style.top = pos2.y + 'px'; 
        }

        function logEvent(msg) {
            eventCounter++; 
            msgLog.textContent = `[${eventCounter}] ${msg}`;
            
            const now = new Date(); 
            const timeString = now.toLocaleTimeString() + '.' + now.getMilliseconds();
            const data = { id: eventCounter, msg: msg, time: timeString };
            
            fetch('my_api.php?action=immediate', {
                method: 'POST', 
                headers: {'Content-Type': 'application/json'}, 
                body: JSON.stringify(data)
            }).catch(err => console.warn("Immediate save failed:", err));

            data.lsTime = new Date().toLocaleTimeString() + '.' + new Date().getMilliseconds();
            localStorageLogs.push(data);
            localStorage.setItem('lab5_logs', JSON.stringify(localStorageLogs));
        }

        async function finishSessionAndShowResults() {
            block5.innerHTML = "<h3>Завантаження звіту...</h3>";
            
            try {
                const storedData = JSON.parse(localStorage.getItem('lab5_logs') || '[]');
                if (storedData.length > 0) {
                    const batchRes = await fetch('my_api.php?action=batch', {
                        method: 'POST', 
                        headers: {'Content-Type': 'application/json'}, 
                        body: JSON.stringify(storedData)
                    });
                    if (!batchRes.ok) throw new Error("Batch Save Error: " + batchRes.status);
                }

                const res = await fetch('my_api.php?action=get_results');
                if (!res.ok) throw new Error("Get Results Error: " + res.status);
                
                const text = await res.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    throw new Error("Сервер повернув не JSON! <br> Відповідь сервера: " + text.substring(0, 200) + "...");
                }

                if (data.error) {
                    throw new Error("Помилка від сервера БД: " + data.error);
                }

                // Використовуємо контейнери зі скролом для таблиць
                let html = `<h3>Звіт (Сервер: ${data.server_now})</h3>
                <div style="display:flex; gap:10px;">
                    <div style="flex:1">
                        <h4>Спосіб 1 (Миттєво)</h4>
                        <div class="scroll-container">
                            <table class="results-table">
                                <tr><th>ID</th><th>Клієнт</th><th>Сервер</th><th>Подія</th></tr>
                                ${data.immediate.map(r => `<tr><td>${r.event_id}</td><td>${r.client_time}</td><td>${r.server_time.split(' ')[1]}</td><td>${r.message}</td></tr>`).join('')}
                            </table>
                        </div>
                    </div>
                    <div style="flex:1">
                        <h4>Спосіб 2 (Пакетно)</h4>
                        <div class="scroll-container">
                            <table class="results-table">
                                <tr><th>ID</th><th>Клієнт(LS)</th><th>Сервер</th><th>Подія</th></tr>
                                ${data.batch.map(r => `<tr><td>${r.event_id}</td><td>${r.ls_save_time}</td><td>${r.server_time.split(' ')[1]}</td><td>${r.message}</td></tr>`).join('')}
                            </table>
                        </div>
                    </div>
                </div>`;
                
                block5.innerHTML = html;

            } catch (err) {
                block5.innerHTML = `<h3 style="color:red">ПОМИЛКА:</h3><p>${err.message}</p>`;
                console.error(err);
            }
        }
    });
</script>
</body>
</html>