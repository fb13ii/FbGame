<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['user_id']) && isset($_POST['score'])) {
    $user_id = $_SESSION['user_id'];
    $new_score = intval($_POST['score']);

    // نقوم بزيادة السكور الجديد فوق سكور اللاعب الحالي في قاعدة البيانات
    $sql = "UPDATE users SET score = score + $new_score WHERE id = $user_id";
    if ($conn->query($sql)) {
        // تحديث السكور في الجلسة الحالية أيضاً ليعرض فوراً في الصفحة الشخصية
        $_SESSION['score'] += $new_score;
        echo "success";
    }
}
?>
