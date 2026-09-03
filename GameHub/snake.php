<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الثعبان السلس | GameHub</title>
    <link rel="stylesheet" href="https://cloudflare.com">
    <link rel="stylesheet" href="snake-style.css">
    <style>
        /* إضافة ستايل للنص المضيء الخاص بالثغرة */
        #godModeStatus {
            display: none;
            color: #1dd1a1;
            font-size: 18px;
            margin-top: 5px;
            font-weight: bold;
            text-shadow: 0 0 10px #1dd1a1;
        }
    </style>
</head>
<body>

    <nav>
        <a href="index.php" class="logo">Game<span>Hub</span></a>
        <div><a href="profile.php" class="btn-back">العودة للملف الشخصي</a></div>
    </nav>

    <h1>الثعبان النيون السلس 🐍</h1>
    <p class="instructions">تحكم باستخدام أزرار <span>W, A, S, D</span>. اضغط حرف <span>G</span> لتفعيل/إلغاء وضع الخلود السري! 🌟</p>
    
    <div class="score-board">السكور الحالي: <span id="score">0</span></div>
    <!-- نص حالة الثغرة -->
    <div id="godModeStatus">⚡ وضع الخلود نشط (لا يموت) ⚡</div>

    <canvas id="snakeCanvas" width="600" height="400"></canvas>
    
    <div id="game-over-screen">
        <h2 style="color: #ff4757; font-size: 38px; text-shadow: 0 0 20px #ff4757;">انتهت اللعبة! 💀</h2>
        <p id="final-score-text" style="font-size: 24px; margin-top: 15px; font-weight: bold; color: #fff;"></p>
        <p style="color: #747d8c; font-size: 15px; margin-top: 5px;">تم حفظ النقاط تلقائياً في السيرفر.</p>
        <button class="btn-restart" onclick="resetGame()">إعادة المحاولة 🔄</button>
    </div>

    <script>
        const canvas = document.getElementById("snakeCanvas");
        const ctx = canvas.getContext("2d");
        const scoreDisplay = document.getElementById("score");
        const gameOverScreen = document.getElementById("game-over-screen");
        const finalScoreText = document.getElementById("final-score-text");
        const godStatusDisplay = document.getElementById("godModeStatus");

        let snake = [];
        let food = { x: 0, y: 0 };
        
        let dx = 4; 
        let dy = 0;
        let score = 0;
        let isAlive = true;
        let godMode = false; // متغير لتتبع حالة الثغرة السرية
        let animationFrameId;

        // تتبع الضغط على أزرار التحكم واختصار الثغرة
        document.addEventListener("keydown", function(event) {
            const key = event.key.toLowerCase();
            
            // تفعيل أو إيقاف الثغرة عند الضغط على حرف G
            if (key === "g") {
                godMode = !godMode;
                if (godMode) {
                    godStatusDisplay.style.display = "block";
                } else {
                    godStatusDisplay.style.display = "none";
                }
                event.preventDefault();
            }
            
            if ((key === "arrowleft" || key === "a") && dx === 0) { dx = -4; dy = 0; event.preventDefault(); }
            if ((key === "arrowup" || key === "w") && dy === 0) { dx = 0; dy = -4; event.preventDefault(); }
            if ((key === "arrowright" || key === "d") && dx === 0) { dx = 4; dy = 0; event.preventDefault(); }
            if ((key === "arrowdown" || key === "s") && dy === 0) { dx = 0; dy = 4; event.preventDefault(); }
        });

        function generateFood() {
            food.x = Math.floor(Math.random() * (canvas.width - 30)) + 15;
            food.y = Math.floor(Math.random() * (canvas.height - 30)) + 15;
        }

        function resetGame() {
            cancelAnimationFrame(animationFrameId);
            isAlive = true;
            score = 0;
            dx = 4;
            dy = 0;
            scoreDisplay.innerText = score;
            gameOverScreen.style.display = "none";
            
            snake = [];
            for (let i = 0; i < 25; i++) {
                snake.push({ x: 150 - (i * 2), y: 200 });
            }
            
            generateFood();
            gameLoop();
        }

        function gameLoop() {
            if (!isAlive) return;

            let head = { x: snake[0].x + dx, y: snake[0].y + dy };
            snake.unshift(head);

            let dist = Math.hypot(head.x - food.x, head.y - food.y);
            if (dist < 18) {
                score += 5;
                scoreDisplay.innerText = score;
                generateFood();
            } else {
                snake.pop();
            }

            // فحص التصادم بالجدران: إذا كانت الثغرة (godMode) غير نشطة، يموت الثعبان طبيعياً
            if (head.x < 0 || head.x > canvas.width || head.y < 0 || head.y > canvas.height) {
                if (!godMode) {
                    endGame();
                    return;
                } else {
                    // إذا كانت الثغرة نشطة وصدم الجدار، ينفذ من الجدار الآخر ليعود للساحة!
                    if (head.x < 0) head.x = canvas.width;
                    if (head.x > canvas.width) head.x = 0;
                    if (head.y < 0) head.y = canvas.height;
                    if (head.y > canvas.height) head.y = 0;
                }
            }

            ctx.fillStyle = "#0c0e17";
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            ctx.beginPath();
            ctx.arc(food.x, food.y, 8, 0, Math.PI * 2);
            ctx.fillStyle = "#ff4757";
            ctx.shadowBlur = 10;
            ctx.shadowColor = "#ff4757";
            ctx.fill();
            ctx.closePath();

            for (let i = 0; i < snake.length; i++) {
                ctx.beginPath();
                ctx.arc(snake[i].x, snake[i].y, 8, 0, Math.PI * 2);
                
                // إذا كان وضع الغش نشطاً، يتغير لون جسم الثعبان إلى الأخضر النيون المضيء للتنبيه
                if (godMode) {
                    ctx.fillStyle = (i === 0) ? "#1dd1a1" : "#10ac84";
                    ctx.shadowBlur = (i === 0) ? 12 : 0;
                    ctx.shadowColor = "#1dd1a1";
                } else {
                    ctx.fillStyle = (i === 0) ? "#00cecb" : "#6c5ce7";
                    ctx.shadowBlur = (i === 0) ? 10 : 0;
                    ctx.shadowColor = "#00cecb";
                }
                
                ctx.fill();
                ctx.closePath();
            }
            ctx.shadowBlur = 0;

            animationFrameId = requestAnimationFrame(gameLoop);
        }

        function endGame() {
            isAlive = false;
            cancelAnimationFrame(animationFrameId);
            gameOverScreen.style.display = "flex";
            finalScoreText.innerText = `النقاط التي جمعتها: ${score} نقطة`;

            fetch('update_score.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'score=' + score
            });
        }

        window.addEventListener('load', function() {
            resetGame();
        });
    </script>
</body>
</html>
