const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");
const scoreDisplay = document.getElementById("score");
const gameOverScreen = document.getElementById("game-over-screen");
const finalScoreText = document.getElementById("final-score-text");

// 1. تحميل صورة سيارتك الحقيقية فقط من مجلد المشروع
const carImg = new Image();
carImg.src = 'player_car.png';

let isCarImageLoaded = false;
carImg.onload = function() {
    isCarImageLoaded = true;
    resetGame(); // تشغيل اللعبة فور جاهزية صورة السيّارة
};

// إذا واجه المتصفح مشكلة في تحميل صورتك، يشغل اللعبة احتياطياً خلال ثانيتين لمنع الشاشة السوداء
setTimeout(() => {
    if (!isCarImageLoaded) {
        resetGame();
    }
}, 2000);

let carX = 350; 
const carY = 340; 
let carWidth = 85;   // عرض صورة سيارتك الرياضية
let carHeight = 65;  // ارتفاع صورة سيارتك الرياضية

let obstacles = [];
let score = 0;
let isAlive = true;
let gameSpeed = 7;
let spawnTimer = 0;
let roadPos = 0; 
let keys = { left: false, right: false };
let animationFrameId;

document.addEventListener("keydown", (e) => {
    const key = e.key.toLowerCase();
    if (key === "a" || key === "arrowleft") keys.left = true;
    if (key === "d" || key === "arrowright") keys.right = true;
});
document.addEventListener("keyup", (e) => {
    const key = e.key.toLowerCase();
    if (key === "a" || key === "arrowleft") keys.left = false;
    if (key === "d" || key === "arrowright") keys.right = false;
});

function spawnObstacle() {
    obstacles.push({
        x: Math.random() * 120 + 290, 
        y: 180,                       // خط الأفق لعمق الطرق السريعة
        w: 8,                         
        h: 4,
        speedX: (Math.random() - 0.5) * 5 
    });
}

function resetGame() {
    cancelAnimationFrame(animationFrameId);
    carX = 350;
    obstacles = [];
    score = 0;
    gameSpeed = 7;
    spawnTimer = 0;
    roadPos = 0;
    isAlive = true;
    scoreDisplay.innerText = score;
    gameOverScreen.style.display = "none";
    gameLoop();
}

function gameLoop() {
    if (!isAlive) return;

    // تحريك سيارة اللاعب بالأسهم أو WASD
    if (keys.left && carX > 165) carX -= 7;
    if (keys.right && carX < 535) carX += 7;

    // تدفق الإسفلت السريع
    roadPos += gameSpeed;
    if (roadPos > 40) roadPos = 0;

    spawnTimer++;
    if (spawnTimer > 28) {
        spawnObstacle();
        spawnTimer = 0;
    }

    // 2. رسم السماء الليلية والأفق المظلم برمجياً وثابت
    ctx.fillStyle = "#0b131a"; 
    ctx.fillRect(0, 0, canvas.width, 180);

    // رسم الجبال البعيدة بدقة عالية لتعطي طابعاً واقعياً للخلفية
    ctx.fillStyle = "#1c2833";
    ctx.beginPath();
    ctx.moveTo(0, 180); ctx.lineTo(150, 140); ctx.lineTo(300, 180);
    ctx.lineTo(450, 130); ctx.lineTo(600, 180); ctx.lineTo(700, 180);
    ctx.fill();

    // خط الأفق الهندسي الفاصل
    ctx.strokeStyle = "#2c3e50";
    ctx.lineWidth = 3;
    ctx.beginPath(); ctx.moveTo(0, 180); ctx.lineTo(canvas.width, 180); ctx.stroke();

    // المناطق الجانبية (العشب التبادلي لمحاكاة الحركة السريعة)
    ctx.fillStyle = (Math.floor(roadPos / 20) % 2 === 0) ? "#196f3d" : "#229954";
    ctx.fillRect(0, 180, canvas.width, canvas.height - 180);

    // 3. رسم الشارع المنظوري الاحترافي المظلم والإسفلت الواقعي
    ctx.fillStyle = "#282c34"; 
    ctx.beginPath();
    ctx.moveTo(320, 180); ctx.lineTo(380, 180);
    ctx.lineTo(660, canvas.height); ctx.lineTo(40, canvas.height);
    ctx.fill();

    // خطوط الأمان الجانبية الحمراء والبيضاء المتدفقة
    let isRed = (Math.floor(roadPos / 20) % 2 === 0);
    ctx.strokeStyle = isRed ? "#cb4335" : "#f2f3f4";
    ctx.lineWidth = 6;
    ctx.beginPath();
    ctx.moveTo(320, 180); ctx.lineTo(40, canvas.height);
    ctx.moveTo(380, 180); ctx.lineTo(660, canvas.height);
    ctx.stroke();

    // الخطوط البيضاء المتقطعة في منتصف الشارع لإحساس السرعة الفائق
    ctx.strokeStyle = "#ffffff";
    ctx.lineWidth = 4;
    ctx.setLineDash([20, 20]);
    ctx.lineDashOffset = -roadPos * 1.5;
    ctx.beginPath();
    ctx.moveTo(350, 180); ctx.lineTo(350, canvas.height);
    ctx.stroke();
    ctx.setLineDash([]); 

    // 4. تحريك ورسم الحواجز التحذيرية المظللة بالذكاء (تتكبر تدريجياً)
    for (let i = obstacles.length - 1; i >= 0; i--) {
        let obs = obstacles[i];
        
        obs.y += gameSpeed * (obs.y / 160); 
        obs.x += obs.speedX * (obs.y / 180);
        obs.w += 2.2;
        obs.h += 1.1;

        let obsX = obs.x - obs.w / 2;
        
        // رسم العائق الإسمنتي ثلاثي الأبعاد المظلل برمجياً بدون ملف خارجي
        ctx.fillStyle = "#7f8c8d";
        ctx.fillRect(obsX, obs.y, obs.w, obs.h);
        ctx.fillStyle = "#616a6b";
        ctx.fillRect(obsX, obs.y, obs.w, obs.h * 0.3);
        ctx.fillStyle = "#f1c40f"; // الخطوط التحذيرية الصفراء
        ctx.fillRect(obsX + obs.w * 0.2, obs.y + obs.h * 0.4, obs.w * 0.2, obs.h * 0.4);
        ctx.fillRect(obsX + obs.w * 0.6, obs.y + obs.h * 0.4, obs.w * 0.2, obs.h * 0.4);

        // فحص تصادم هندسي دقيق مع أبعاد سيارتك المسحوبة
        if (obs.y > carY - 15 && obs.y < carY + carHeight) {
            let obsLeft = obs.x - obs.w / 2;
            let obsRight = obs.x + obs.w / 2;
            let carLeft = carX - carWidth / 2;
            let carRight = carX + carWidth / 2;

            if (obsRight > carLeft && obsLeft < carRight) {
                isAlive = false;
                endGame();
                return;
            }
        }

        if (obs.y > canvas.height) {
            obstacles.splice(i, 1);
            score += 10;
            scoreDisplay.innerText = score;
            if (score % 70 === 0 && gameSpeed < 14) gameSpeed += 0.7; 
        }
    }

    // 5. رسم صورة سيارتك الواقعية الفخمة التي قمت بتحميلها يدوياً
    if (isCarImageLoaded) {
        ctx.drawImage(carImg, carX - carWidth / 2, carY, carWidth, carHeight);
    } else {
        // رسم سيارة احترافية بديلة بالـ Canvas كخطة احتياطية إذا تعطل الملف لحين إصلاحه
        let cL = carX - carWidth / 2;
        ctx.fillStyle = "#c0392b";
        ctx.fillRect(cL, carY, carWidth, carHeight - 10);
        ctx.fillStyle = "#2c3e50";
        ctx.fillRect(cL + 10, carY, carWidth - 20, 15);
        ctx.fillStyle = "#e74c3c";
        ctx.fillRect(cL + 5, carY + carHeight - 16, 12, 5);
        ctx.fillRect(cL + carWidth - 17, carY + carHeight - 16, 12, 5);
    }

    animationFrameId = requestAnimationFrame(gameLoop);
}

function endGame() {
    isAlive = false;
    cancelAnimationFrame(animationFrameId);
    gameOverScreen.style.display = "flex";
    finalScoreText.innerText = `النقاط التي جمعتها في السباق: ${score} نقطة`;

    fetch('update_score.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'score=' + score
    });
}
