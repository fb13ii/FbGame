<?php
session_start();
require_once 'db.php';

// جلب أفضل 10 لاعبين في الموقع مرتبين تنازلياً حسب الأعلى سكور
$sql = "SELECT username, score FROM users WHERE role = 'user' ORDER BY score DESC LIMIT 10";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جدول الصدارة | GameHub</title>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #0d0e12; color: #ffffff; padding-top: 100px; }
        
        nav { background: #12131a; border-bottom: 2px solid #6c5ce7; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center; position: fixed; width: 100%; top: 0; z-index: 1000; }
        .logo { font-size: 24px; font-weight: bold; color: #00cecb; text-decoration: none; }
        .logo span { color: #6c5ce7; }
        .nav-links { display: flex; list-style: none; gap: 30px; }
        .nav-links a { color: #a4b0be; text-decoration: none; transition: 0.3s; }
        .nav-links a.active { color: #00cecb; }
        
        .leaderboard-container { max-width: 700px; margin: 0 auto; padding: 20px; }
        .leaderboard-header { text-align: center; margin-bottom: 30px; }
        .leaderboard-header h1 { color: #00cecb; font-size: 36px; text-shadow: 0 0 10px rgba(0,206,203,0.3); }
        .leaderboard-header p { color: #747d8c; margin-top: 5px; }

        /* ستايل جدول الترتيب */
        .leaderboard-table { width: 100%; border-collapse: collapse; background: #12131a; border-radius: 12px; overflow: hidden; border: 2px solid #6c5ce7; box-shadow: 0 5px 25px rgba(108,92,231,0.1); }
        .leaderboard-table th, .leaderboard-table td { padding: 18px; text-align: right; border-bottom: 1px solid #2f3542; }
        .leaderboard-table th { background: #181922; color: #6c5ce7; font-weight: bold; font-size: 18px; }
        .leaderboard-table tr:hover { background: rgba(108,92,231,0.05); }
        
        /* تلوين المراتب الثلاثة الأولى */
        .rank-1 { color: #ffd700; font-weight: bold; font-size: 20px; } /* ذهبي */
        .rank-2 { color: #c0c0c0; font-weight: bold; font-size: 18px; } /* فضي */
        .rank-3 { color: #cd7f32; font-weight: bold; font-size: 18px; } /* برونزي */
        
        .score-highlight { color: #00cecb; font-weight: bold; }
        .btn-back { padding: 8px 15px; background: #6c5ce7; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>

    <nav>
        <a href="index.php" class="logo">Game<span>Hub</span></a>
        <ul class="nav-links">
            <li><a href="index.php">الرئيسية</a></li>
            <li><a href="leaderboard.php" class="active">جدول الصدارة</a></li>
            <li><a href="profile.php">لوحة التحكم</a></li>
        </ul>
        <?php if(isset($_SESSION['user_id'])): ?>
            <div><a href="profile.php" class="btn-back">حسابي</a></div>
        <?php else: ?>
            <div><a href="login.php" class="btn-back">دخول</a></div>
        <?php endif; ?>
    </nav>

    <div class="leaderboard-container">
        <div class="leaderboard-header">
            <h1>قاعة المشاهير 🏆</h1>
            <p>أفضل 10 لاعبين حققوا أعلى نقاط على مستوى المنصة</p>
        </div>

        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th>الترتيب</th>
                    <th>اسم اللاعب</th>
                    <th>إجمالي النقاط</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $rank = 1;
                if ($result->num_rows > 0):
                    while($row = $result->fetch_assoc()): 
                        // تحديد الكلاس المناسب لأول ثلاثة مراكز
                        $rank_class = "";
                        if ($rank == 1) $rank_class = "rank-1";
                        elseif ($rank == 2) $rank_class = "rank-2";
                        elseif ($rank == 3) $rank_class = "rank-3";
                ?>
                        <tr>
                            <td class="<?php echo $rank_class; ?>">
                                <?php 
                                if ($rank == 1) echo "🥇 الأول";
                                elseif ($rank == 2) echo "🥈 الثاني";
                                elseif ($rank == 3) echo "🥉 الثالث";
                                else echo $rank;
                                ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                            <td class="score-highlight"><?php echo $row['score']; ?> 🏆</td>
                        </tr>
                <?php 
                        $rank++;
                    endwhile; 
                else:
                ?>
                    <tr>
                        <td colspan="3" style="text-align: center; color: #747d8c;">لا توجد سجلات نقاط حالياً.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
