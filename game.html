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
    <title>تحدي النيون | GameHub</title>
    <!-- استدعاء ملف التصميم الخارجي والخطوط -->
    <link rel="stylesheet" href="https://cloudflare.com">
    <link rel="stylesheet" href="game-style.css">
</head>
<body>

    <nav>
        <a href="index.php" class="logo">Game<span>Hub</span></a>
        <div><a href="profile.php" class="btn-back">العودة للملف الشخصي</a></div>
    </nav>

    <h1>تحدي النيون السيبراني ⚡</h1>
    <p class="instructions">اضغط على زر <span>المسافة (Spacebar)</span> أو <span>اضغط بالماوس داخل الصندوق</span> للتحليق!</p>
    
    <div class="score-board">السكور: <span id="score">0</span></div>

    <div id="game-container" onclick="jump(event)">
        <div id="dino"></div>
        <div id="cactus"></div>
        <div class="floor"></div>
        
        <div id="game-over-screen">
            <h2 style="color: #ff4757; font-size: 38px; text-shadow: 0 0 20px #ff4757;">تحطمت المركبة! 💥</h2>
            <p id="final-score-text" style="font-size: 24px; margin-top: 15px; font-weight: bold; color: #fff;"></p>
            <p style="color: #747d8c; font-size: 15px; margin-top: 5px;">تم حفظ السكور تلقائياً في السيرفر.</p>
            <button class="btn-restart" onclick="resetGame(event)">إعادة المحاولة 🔄</button>
        </div>
    </div>

    <script>
        const dino = document.getElementById("dino");
        const cactus = document.getElementById("cactus");
        const scoreDisplay = document.getElementById("score");
        const gameOverScreen = document.getElementById("game-over-screen");
        const finalScoreText = document.getElementById("final-score-text");
        const floor = document.querySelector(".floor");

        let score = 0;
        let isAlive = true;
        let gameSpeed = 7;
        let cactusLeft = 760;
        let animationFrameId;

        document.addEventListener("keydown", function(event) {
            if (event.code === "Space" || event.key === " ") {
                event.preventDefault();
                jump();
            }
        });

        function jump(event) {
            if (event && event.target.classList.contains('btn-restart')) return;
            if (!dino.classList.contains("jump") && isAlive) {
                dino.classList.add("jump");
                setTimeout(function() {
                    dino.classList.remove("jump");
                }, 650);
            }
        }

        function gameLoop() {
            if (!isAlive) return;

            cactusLeft -= gameSpeed;
            if (cactusLeft < -30) {
                cactusLeft = 760;
                score += 10;
                scoreDisplay.innerText = score;
                if (score % 50 === 0 && gameSpeed < 16) {
                    gameSpeed += 1.2;
                }
            }
            cactus.style.left = cactusLeft + "px";

            let dinoBottom = parseInt(window.getComputedStyle(dino).getPropertyValue("bottom"));
            if (cactusLeft > 60 && cactusLeft < 105 && dinoBottom <= 100) {
                isAlive = false;
                endGame();
                return;
            }
            animationFrameId = requestAnimationFrame(gameLoop);
        }

        function endGame() {
            cancelAnimationFrame(animationFrameId);
            floor.style.animationPlayState = 'paused';
            gameOverScreen.style.display = "flex";
            finalScoreText.innerText = "رصيدك في هذه الجولة: " + score + " نقطة";
            
            fetch('update_score.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'score=' + score
            });
        }

        function resetGame(event) {
            if (event) {
                event.stopPropagation();
                event.preventDefault();
            }
            cancelAnimationFrame(animationFrameId);
            dino.classList.remove("jump");
            score = 0;
            gameSpeed = 7;
            cactusLeft = 760;
            isAlive = true;
            scoreDisplay.innerText = score;
            cactus.style.left = cactusLeft + "px";
            floor.style.animationPlayState = 'running';
            gameOverScreen.style.display = "none";
            gameLoop();
        }

        window.addEventListener('load', function() {
            resetGame();
        });
    </script>
</body>
</html>
