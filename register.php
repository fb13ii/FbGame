<?php
session_start();
require_once 'db.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string(trim($_POST['username']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $conn->real_escape_string($_POST['password']);

    // 1. التحقق من أن اسم المستخدم أو الإيميل غير مكررين
    $check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email' LIMIT 1";
    $result = $conn->query($check_query);
    
    if ($result->num_rows > 0) {
        $existing_user = $result->fetch_assoc();
        if ($existing_user['username'] === $username) {
            $error = "اسم المستخدم مأخوذ بالفعل، اختر اسماً آخر!";
        } else {
            $error = "البريد الإلكتروني مسجل بحساب آخر بالفعل!";
        }
    } else {
        // 2. إدخال المستخدم الجديد بكلمة مرور عادية (بدون تشفير لتتوافق مع نظام الدخول البسيط الحالي)
        $insert_query = "INSERT INTO users (username, email, password, role, score) VALUES ('$username', '$email', '$password', 'user', 0)";
        
        if ($conn->query($insert_query)) {
            $success = "تم إنشاء الحساب بنجاح! جاري توجيهك لصفحة تسجيل الدخول...";
            // توجيه تلقائي لصفحة الدخول بعد 2 ثانية
            header("refresh:2;url=login.php");
        } else {
            $error = "حدث خطأ أثناء التسجيل، حاول مجدداً!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل حساب جديد | GameHub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #0d0e12; color: #ffffff; display: flex; justify-content: center; align-items: center; height: 100vh; }
        
        .register-container { background: #12131a; padding: 40px; border-radius: 12px; border: 2px solid #6c5ce7; width: 100%; max-width: 400px; box-shadow: 0 5px 25px rgba(108,92,231,0.2); }
        .register-container h2 { text-align: center; margin-bottom: 25px; color: #00cecb; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #a4b0be; }
        .form-group input { width: 100%; padding: 12px; border-radius: 6px; border: 1px solid #2f3542; background: #181922; color: #fff; font-size: 16px; outline: none; }
        .form-group input:focus { border-color: #00cecb; }
        
        .btn-submit { width: 100%; padding: 12px; background: #00cecb; border: none; border-radius: 6px; color: #0d0e12; font-weight: bold; font-size: 16px; cursor: pointer; transition: 0.3s; }
        .btn-submit:hover { background: #00b8b5; transform: translateY(-2px); }
        
        .error-msg { background: #ff7675; color: #fff; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 20px; font-size: 14px; }
        .success-msg { background: #1dd1a1; color: #fff; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 20px; font-size: 14px; }
        .footer-links { text-align: center; margin-top: 20px; font-size: 14px; }
        .footer-links a { color: #6c5ce7; text-decoration: none; }
    </style>
</head>
<body>

<div class="register-container">
    <h2>إنشاء حساب لاعب</h2>
    
    <?php if(!empty($error)): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if(!empty($success)): ?>
        <div class="success-msg"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div class="form-group">
            <label>اسم اللاعب (Username)</label>
            <input type="text" name="username" required placeholder="مثال: gamer_99" autocomplete="off">
        </div>
        <div class="form-group">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" required placeholder="player@domain.com">
        </div>
        <div class="form-group">
            <label>كلمة المرور</label>
            <input type="password" name="password" required placeholder="••••••••">
        </div>
        <button type="submit" class="btn-submit">تسجيل حساب جديد 🚀</button>
    </form>

    <div class="footer-links">
        <p style="color: #747d8c;">لديك حساب بالفعل؟ <a href="login.php">سجل دخولك</a></p>
        <p style="margin-top: 10px;"><a href="index.php" style="color: #a4b0be;">العودة للرئيسية</a></p>
    </div>
</div>

</body>
</html>
