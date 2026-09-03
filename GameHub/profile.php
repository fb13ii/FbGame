<?php
session_start();
// التحقق مما إذا كان المستخدم مسجلاً للدخول بالفعل، وإذا لم يكن، يتم توجيهه لصفحة الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// إذا كان الداخل هو الأدمن، نوجهه للوحة الإدارة الخاصة به تلقائياً
if ($_SESSION['role'] == 'admin') {
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم | GameHub</title>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #0d0e12; color: #ffffff; padding-top: 100px; }
        
        /* الهيدر */
        nav { background: #12131a; border-bottom: 2px solid #6c5ce7; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; position: fixed; width: 100%; top: 0; z-index: 1000; }
        .logo { font-size: 24px; font-weight: bold; color: #00cecb; }
        .logo span { color: #6c5ce7; }
        .nav-links { display: flex; list-style: none; gap: 30px; }
        .nav-links a { color: #a4b0be; text-decoration: none; transition: 0.3s; }
        .nav-links a.active { color: #00cecb; }
        
        /* الكرت الشخصي */
        .profile-container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .profile-card { background: #12131a; border-radius: 12px; border: 2px solid #6c5ce7; padding: 30px; display: flex; align-items: center; gap: 30px; box-shadow: 0 5px 25px rgba(108,92,231,0.1); }
        .avatar { width: 100px; height: 100px; background: #6c5ce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; color: #fff; border: 3px solid #00cecb; }
        .user-info h2 { color: #00cecb; margin-bottom: 5px; }
        .user-info p { color: #a4b0be; font-size: 14px; }
        
        /* رصيد النقاط */
        .score-box { background: #181922; border: 1px solid #2f3542; padding: 15px 30px; border-radius: 8px; text-align: center; margin-right: auto; }
        .score-box h3 { font-size: 32px; color: #00cecb; }
        .score-box p { color: #747d8c; font-size: 14px; }
        
        .btn-logout { padding: 8px 20px; background: #ff7675; color: #fff; border-radius: 5px; text-decoration: none; font-weight: bold; transition: 0.3s; }
        .btn-logout:hover { background: #e74c3c; }
        
        .welcome-msg { margin-top: 40px; text-align: center; background: #12131a; padding: 40px; border-radius: 12px; border: 1px solid #2f3542; }
        .welcome-msg h3 { margin-bottom: 15px; }
        .btn-play { display: inline-block; padding: 12px 30px; background: #6c5ce7; color: #fff; border-radius: 6px; font-weight: bold; text-decoration: none; transition: 0.3s; margin-top: 15px; }
        .btn-play:hover { background: #5b4cc4; box-shadow: 0 0 15px rgba(108,92,231,0.5); }
    </style>
</head>
<body>

    <nav>
        <div class="logo">Game<span>Hub</span></div>
        <ul class="nav-links">
            <li><a href="index.php">الرئيسية</a></li>
            <li><a href="leaderboard.php">جدول الصدارة</a></li>
            <li><a href="profile.php" class="active">لوحة التحكم</a></li>
        </ul>
        <div>
            <a href="logout.php" class="btn-logout">تسجيل الخروج</a>
        </div>
    </nav>

    <div class="profile-container">
        <!-- الكرت الشخصي للاعب يسحب البيانات ديناميكياً من السيرفر -->
        <div class="profile-card">
            <div class="avatar"><i class="fas fa-user-astronaut"></i></div>
            <div class="user-info">
                <h2><?php echo htmlspecialchars($_SESSION['username']); ?></h2>
                <p>مرحباً بك مجدداً في حسابك الشخصي</p>
            </div>
            <div class="score-box">
                <h3><?php echo $_SESSION['score']; ?></h3>
                <p>إجمالي النقاط 🏆</p>
            </div>
        </div>

        <div class="welcome-msg">
            <h3>جاهز للتحدي الجديد؟ 🎮</h3>
            <p>نقاطك الحالية تؤهلك للمنافسة في جدول الصدارة العالمي. اذهب والعب الآن لتجميع المزيد من السكور!</p>
            <a href="index.php" class="btn-play">استكشف الألعاب المتاحة</a>
        </div>
    </div>

</body>
</html>
