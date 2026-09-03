<?php
session_start();
require_once 'db.php';

// حماية الصفحة: التأكد من رتبة الأدمن الصارمة
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// 1. معالجة التحديثات القادمة من الأزرار (تعديل السكور والرتبة)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $target_id = intval($_POST['user_id']);
    
    if ($_POST['action'] === 'update_user') {
        $new_score = intval($_POST['score']);
        $new_role = $conn->real_escape_string($_POST['role']);
        
        $conn->query("UPDATE users SET score = $new_score, role = '$new_role' WHERE id = $target_id AND username != 'admin'");
    }
    header("Location: admin.php");
    exit();
}

// 2. معالجة حذف اللاعبين
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM users WHERE id = $delete_id AND role != 'admin'");
    header("Location: admin.php");
    exit();
}

// 3. حساب الإحصائيات العامة الحية للصناديق العلوية
$total_players = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'")->fetch_assoc()['total'];
$total_scores = $conn->query("SELECT SUM(score) as total FROM users")->fetch_assoc()['total'] ?? 0;
$highest_score = $conn->query("SELECT MAX(score) as max_score FROM users WHERE role = 'user'")->fetch_assoc()['max_score'] ?? 0;

// 4. سحب قائمة جميع المستخدمين للعرض في الجدول
$users_result = $conn->query("SELECT id, username, email, score, role, created_at FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم الفائقة للمسؤول | GameHub</title>
    <link rel="stylesheet" href="https://cloudflare.com">
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>

    <nav>
        <a href="index.php" class="logo">Game<span>Hub Control</span></a>
        <div><a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> خروج آمن</a></div>
    </nav>

    <div class="admin-container">
        <div class="admin-header">
            <h1>لوحة القيادة والتحكم الفائقة 🛠️</h1>
            <p>إدارة الحسابات، موازنة النقاط، ومراقبة أداء السيرفر في الوقت الفعلي.</p>
        </div>

        <!-- كروت الإحصائيات الحية العلوية -->
        <div class="stats-row">
            <div class="stat-box">
                <i class="fas fa-users-cog"></i>
                <div class="stat-box-info">
                    <h3><?php echo $total_players; ?></h3>
                    <p>إجمالي اللاعبين النشطين</p>
                </div>
            </div>
            <div class="stat-box green">
                <i class="fas fa-trophy"></i>
                <div class="stat-box-info">
                    <h3><?php echo $total_scores; ?></h3>
                    <p>مجموع النقاط بالمنصة</p>
                </div>
            </div>
            <div class="stat-box cyan">
                <i class="fas fa-crown"></i>
                <div class="stat-box-info">
                    <h3><?php echo $highest_score; ?> 👑</h3>
                    <p>أعلى سكور مسجل حالياً</p>
                </div>
            </div>
        </div>

        <!-- شريط البحث السريع عن اللاعبين -->
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="ابحث عن لاعب باسمه أو بريده...">
        </div>

        <!-- الجدول المتطور لإدارة الحسابات -->
        <table class="management-table" id="usersTable">
            <thead>
                <tr>
                    <th>المعرف ID</th>
                    <th>اسم المستخدم</th>
                    <th>البريد الإلكتروني</th>
                    <th>النقاط (السكور)</th>
                    <th>الرتبة في الموقع</th>
                    <th>تاريخ الإنضمام</th>
                    <th>إجراءات الإدارة</th>
                </tr>
            </thead>
            <tbody>
                <?php while($user = $users_result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $user['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        
                        <!-- فورم مباشر لتعديل بيانات اللاعب فوراً -->
                        <form action="admin.php" method="POST">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <input type="hidden" name="action" value="update_user">
                            
                            <td>
                                <?php if($user['username'] === 'admin'): ?>
                                    <span style="color:#747d8c">غير قابل للتعديل</span>
                                <?php else: ?>
                                    <input type="number" name="score" class="score-input" value="<?php echo $user['score']; ?>">
                                <?php endif; ?>
                            </td>
                            
                            <td>
                                <?php if($user['username'] === 'admin'): ?>
                                    <span style="color:#6c5ce7; font-weight:bold;">مدير النظام</span>
                                <?php else: ?>
                                    <select name="role" class="role-select">
                                        <option value="user" <?php if($user['role'] === 'user') echo 'selected'; ?>>لاعب عادي</option>
                                        <option value="admin" <?php if($user['role'] === 'admin') echo 'selected'; ?>>مسؤول (Admin)</option>
                                    </select>
                                <?php endif; ?>
                            </td>
                            
                            <td><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                            
                            <td>
                                <?php if($user['username'] === 'admin'): ?>
                                    <span style="color:#747d8c">-</span>
                                <?php else: ?>
                                    <button type="submit" class="btn-action btn-save"><i class="fas fa-save"></i> حفظ</button>
                                    <a href="admin.php?delete_id=<?php echo $user['id']; ?>" class="btn-action btn-delete" onclick="return confirm('هل أنت متأكد من حذف هذا الحساب نهائياً من قاعدة البيانات؟')"><i class="fas fa-trash"></i> حذف</a>
                                <?php endif; ?>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- سكريبت جافا سكريبت للفلترة والبحث الفوري الفائق في الجدول -->
    <script>
        function filterTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toLowerCase();
            const table = document.getElementById("usersTable");
            const tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                let nameTd = tr[i].getElementsByTagName("td")[1];
                let emailTd = tr[i].getElementsByTagName("td")[2];
                if (nameTd || emailTd) {
                    let nameText = nameTd.textContent || nameTd.innerText;
                    let emailText = emailTd.textContent || emailTd.innerText;
                    if (nameText.toLowerCase().indexOf(filter) > -1 || emailText.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
</body>
</html>
