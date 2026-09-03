<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "gamehub_db";

// إنشاء الاتصال بقاعدة البيانات
$conn = new mysqli($host, $user, $pass, $dbname);

// التحقق من نجاح الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// ضبط الترميز ليدعم اللغة العربية بشكل صحيح
$conn->set_charset("utf8mb4");
?>

