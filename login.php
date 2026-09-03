<?php
// بدء الجلسة لحفظ بيانات المستخدم بعد تسجيل الدخول
session_start();
require_once 'db.php';

$error = "";

// التحقق من إرسال البيانات عبر الفورم
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    // البحث عن المستخدم بالإيميل وكلمة المرور مباشرة
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // حفظ بيانات المستخدم في الجلسة (Session)
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['score'] = $user['score'];

        // التوجيه بناءً على الرتبة (أدمن أو لاعب)
        if ($user['role'] == 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: profile.php");
        }
        exit();
    } else {
        $error = "البريد الإلكتروني أو كلمة المرور غير صحيحة!";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول | GameHub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #0d0e12; color: #ffffff; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-container { background: #12131a; padding: 40px; border-radius: 12px; border: 2px solid #6c5ce7; width: 100%; max-width: 400px; box-shadow: 0 5px 25px rgba(108,92,231,0.2); }
        .login-container h2 { text-align: center; margin-bottom: 25px; color: #00cecb; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #a4b0be; }
        .form-group input { width: 100%; padding: 12px; border-radius: 6px; border: 1px solid #2f3542; background: #181922; color: #fff; font-size: 16px; outline: none; }
        .form-group input:focus { border-color: #00cecb; }
        .btn-submit { width: 100%; padding: 12px; background: #6c5ce7; border: none; border-radius: 6px; color: #fff; font-weight: bold; font-size: 16px; cursor: pointer; transition: 0.3s; }
        .btn-submit:hover { background: #5b4cc4; }
        .error-msg { background: #ff7675; color: #fff; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 20px; font-size: 14px; }
        .footer-links { text-align: center; margin-top: 20px; font-size: 14px; }
        .footer-links a { color: #00cecb; text-decoration: none; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>تسجيل الدخول</h2>
    
    <?php if(!empty($error)): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" required placeholder="example@domain.com">
        </div>
        <div class="form-group">
            <label>كلمة المرور</label>
            <input type="password" name="password" required placeholder="••••••••">
        </div>
        <button type="submit" class="btn-submit">دخول</button>
    </form>

    <div class="footer-links">
        <p style="color: #747d8c;">ليس لديك حساب؟ <a href="register.php">سجل الآن</a></p>
        <p style="margin-top: 10px;"><a href="index.php" style="color: #a4b0be;"> العودة للرئيسية</a></p>
    </div>
</div>

</body>
</html>
