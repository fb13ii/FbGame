<?php
session_start();
require_once 'db.php';

$total_players = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'")->fetch_assoc()['total'];
$total_scores = $conn->query("SELECT SUM(score) as total FROM users")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub | منصة الألعاب التفاعلية</title>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #0d0e12; color: #ffffff; padding-top: 80px; overflow-x: hidden; }
        nav { background: #12131a; border-bottom: 2px solid #6c5ce7; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; position: fixed; width: 100%; top: 0; z-index: 1000; }
        .logo { font-size: 24px; font-weight: bold; color: #00cecb; text-decoration: none; }
        .logo span { color: #6c5ce7; }
        .nav-links { display: flex; list-style: none; gap: 30px; }
        .nav-links a { color: #a4b0be; text-decoration: none; cursor: pointer; transition: 0.3s; }
        .nav-links a:hover, .nav-links a.active { color: #00cecb; }
        .auth-buttons { display: flex; gap: 15px; align-items: center; }
        .btn { padding: 8px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; transition: 0.3s; border: none; text-decoration: none; display: inline-block; }
        .btn-signup { background: #00cecb; color: #0d0e12; }
        .btn-signup:hover { background: #00b8b5; transform: translateY(-2px); }
        .btn-logout { padding: 8px 15px; background: #ff4757; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .hero { padding: 60px 50px; text-align: center; background: radial-gradient(circle, rgba(108,92,231,0.1) 0%, rgba(13,14,18,1) 70%); }
        .hero h1 { font-size: 40px; margin-bottom: 20px; }
        .hero h1 span { color: #00cecb; }
        .hero p { color: #a4b0be; font-size: 18px; max-width: 600px; margin: 0 auto 30px auto; }
        .stats-grid { display: flex; justify-content: center; gap: 30px; margin-top: 40px; flex-wrap: wrap; }
        .stat-card { background: #181922; padding: 20px 40px; border-radius: 10px; border: 1px solid #2f3542; text-align: center; min-width: 180px; border-bottom: 3px solid #6c5ce7; }
        .stat-card i { font-size: 30px; color: #00cecb; margin-bottom: 10px; }
        .stat-card h3 { font-size: 28px; color: #fff; }
        .stat-card p { color: #747d8c; font-size: 14px; }
        .games-section { padding: 50px; max-width: 1200px; margin: 0 auto; }
        .games-section h2 { font-size: 28px; margin-bottom: 30px; border-right: 4px solid #6c5ce7; padding-right: 15px; color: #00cecb; }
        .games-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; justify-content: center; }
        .game-card { background: #181922; border-radius: 12px; overflow: hidden; border: 1px solid #2f3542; transition: 0.3s; display: flex; flex-direction: column; }
        .game-card:hover { transform: translateY(-5px); border-color: #00cecb; box-shadow: 0 5px 20px rgba(0,206,203,0.2); }
        .game-img { height: 160px; display: flex; align-items: center; justify-content: center; font-size: 60px; color: #fff; }
        .game-info { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; }
        .game-info h3 { margin-bottom: 10px; font-size: 20px; color: #fff; }
        .game-info p { color: #747d8c; font-size: 14px; margin-bottom: 20px; line-height: 1.5; height: 65px; }
        footer { text-align: center; padding: 30px; color: #747d8c; font-size: 14px; border-top: 1px solid #2f3542; margin-top: 40px; background: #12131a; }
    </style>
</head>
<body>

    <nav>
        <a href="index.php" class="logo">Game<span>Hub</span></a>
        <ul class="nav-links">
            <li><a href="index.php" class="active">الرئيسية</a></li>
            <li><a href="leaderboard.php">جدول الصدارة</a></li>
            <li><a href="profile.php">لوحة التحكم</a></li>
            <li><a href="admin.php">الإدارة</a></li>
        </ul>
        <div class="auth-buttons">
            <?php if(isset($_SESSION['user_id'])): ?>
                <span style="color:#00cecb; font-weight:bold; margin-left:10px;"><i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" class="btn-logout">خروج</a>
            <?php else: ?>
                <a href="login.php" class="btn">دخول</a>
                <a href="register.php" class="btn btn-signup">تسجيل جديد</a>
            <?php endif; ?>
        </div>
    </nav>

    <section class="hero">
        <h1>مرحباً بك في ساحة النيون <span>GameHub</span></h1>
        <p>العب، اجمع النقاط في حسابك الشخصي وتصدر قائمة الترتيب في أقوى منصة تفاعلية.</p>
        <div class="stats-grid">
            <div class="stat-card"><i class="fas fa-user-astronaut"></i><h3><?php echo $total_players; ?></h3><p>لاعب مسجل</p></div>
            <div class="stat-card"><i class="fas fa-trophy"></i><h3><?php echo $total_scores; ?></h3><p>نقاط المنصة</p></div>
            <div class="stat-card"><i class="fas fa-server"></i><h3>المحلية</h3><p>بيئة XAMPP نشطة</p></div>
        </div>
    </section>

    <section class="games-section">
        <h2>الألعاب المتاحة للعب الآن</h2>
        <div class="games-grid">
            
            <!-- اللعبة الأولى: تفادي العقبات المركبة -->
            <div class="game-card">
                <div class="game-img" style="background: linear-gradient(135deg, #6c5ce7, #a29bfe);"><i class="fas fa-space-shuttle"></i></div>
                <div class="game-info">
                    <div>
                        <h3>تحدي النيون السيبراني (2D)</h3>
                        <p>حلق بمركبتك الفضائية، تفادى ليزر الطاقة الأحمر المباغت بأعلى سرعة ممكنة واجمع سكور ضخم لحسابك.</p>
                    </div>
                    <a href="game.php" class="btn btn-signup" style="width: 100%; text-align:center;">العب الآن <i class="fas fa-play"></i></a>
                </div>
            </div>

            <!-- اللعبة الثانية: الثعبان النيون -->
            <div class="game-card">
                <div class="game-img" style="background: linear-gradient(135deg, #00cecb, #00b8b5); color: #0d0e12;"><i class="fas fa-snake"></i></div>
                <div class="game-info">
                    <div>
                        <h3>الثعبان النيون المطور</h3>
                        <p>تحكم بالثعبان السيبراني المضيء بأزرار WASD واكتشف ثغرة الخلود السرية (حرف G)، واحصد +5 نقاط لكل مكعب طاقة!</p>
                    </div>
                    <a href="snake.php" class="btn btn-signup" style="width: 100%; text-align:center;">العب الآن <i class="fas fa-play"></i></a>
                </div>
            </div>

            <!-- اللعبة الثالثة: سباق السيارات 3D المصلح -->
            <div class="game-card">
                <div class="game-img" style="background: linear-gradient(135deg, #ff4757, #ff7675);"><i class="fas fa-car"></i></div>
                <div class="game-info">
                    <div>
                        <h3>سباق النيون ثلاثي الأبعاد (3D)</h3>
                        <p>انطلق بسيارة ثلاثية الأبعاد وسط طريق متدفق، تفادى الحواجز الحمراء القادمة بمهارة واجمع سكور حقيقي لحسابك!</p>
                    </div>
                    <a href="racer3d.php" class="btn btn-signup" style="width: 100%; text-align:center;">العب الآن 3D <i class="fas fa-play"></i></a>
                </div>
            </div>

        </div>
    </section>

    <footer>
        <p>&copy; 2026 GameHub. جميع الحقوق محفوظة لشبكتنا البرمجية المشتركة.</p>
    </footer>

</body>
</html>
