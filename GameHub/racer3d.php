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
    <title>سباق السيارات الواقعي | GameHub</title>
    <link rel="stylesheet" href="https://cloudflare.com">
    <link rel="stylesheet" href="racer-style.css">
    <style>
        #gameCanvas {
            background-color: #050508;
            border: 4px solid #2c3e50;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
            display: block;
            margin: 20px auto;
        }
    </style>
</head>
<body>

    <nav>
        <a href="index.php" class="logo">Game<span>Hub Racer</span></a>
        <div><a href="profile.php" class="btn-back">العودة للملف الشخصي</a></div>
    </nav>

    <div class="ui-layer" style="position: static; margin-top: 20px;">
        <h1>سباق الطرق السريعة الواقعي 🏎️</h1>
        <div class="score-board">السكور: <span id="score">0</span></div>
        <p class="instructions">تحكم بالسيارة يميناً ويساراً باستخدام أزرار <span>A / D</span> أو <span>الأسهم</span> لتفادي الحواجز!</p>
    </div>

    <!-- كانفاس محاكاة المنظور الواقعي المتطور -->
    <canvas id="gameCanvas" width="700" height="450"></canvas>
    
    <div id="game-over-screen">
        <h2 style="color: #ff4757; font-size: 38px; text-shadow: 0 0 20px #ff4757;">تحطمت السيارة! 💥</h2>
        <p id="final-score-text" style="font-size: 24px; margin-top: 15px; font-weight: bold; color: #fff;"></p>
        <p style="color: #747d8c; font-size: 15px; margin-top: 5px;">تم حفظ النقاط تلقائياً في السيرفر المحلي.</p>
        <button class="btn-restart" onclick="resetGame()">إعادة المحاولة 🔄</button>
    </div>

    <!-- استدعاء ملف البرمجة الخارجي المكتمل والمحمي من القص -->
    <script src="racer-game.js"></script>
</body>
</html>
