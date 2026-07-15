
<?php
include "config.php";
checkLogin();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - متتبع الصحة والعادات</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #EC4899;
            --secondary: #DB2777;
            --accent: #F472B6;
            --success: #10B981;
            --info: #8B5CF6;
            --warning: #F59E0B;
            --light: #FDF2F8;
            --dark: #701A75;
            --background: #FCE7F3;
            --card-shadow: 0 4px 20px rgba(236, 72, 153, 0.15);
            --hover-shadow: 0 10px 25px rgba(236, 72, 153, 0.25);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tajawal', sans-serif;
        }

        body {
            background-color: var(--background);
            color: #333;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* الشريط العلوي */
        .topbar {
            background: linear-gradient(120deg, var(--primary), var(--secondary));
            border-radius: 16px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--card-shadow);
            color: white;
            animation: fadeInDown 0.8s ease;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info .avatar {
            width: 45px;
            height: 45px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            margin-left: 10px;
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .logout-btn {
            background-color: white;
            color: var(--primary);
            padding: 0.6rem 1.2rem;
            border-radius: 30px;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            font-weight: 600;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .logout-btn:hover {
            background-color: var(--light);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .logout-btn i {
            margin-left: 5px;
        }

        /* القائمة */
        .nav {
            display: flex;
            background: white;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            animation: fadeIn 0.8s ease 0.2s both;
        }

        .nav a {
            padding: 1rem 1.5rem;
            text-decoration: none;
            color: #6B7280;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .nav a:before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: var(--primary);
            transition: all 0.3s;
        }

        .nav a:hover, .nav a.active {
            color: var(--primary);
            background-color: var(--light);
        }

        .nav a:hover:before, .nav a.active:before {
            width: 100%;
        }

        .nav a i {
            margin-left: 8px;
            font-size: 1.1rem;
        }

        /* قسم الترحيب */
        .welcome-section {
            background: linear-gradient(120deg, var(--primary), var(--secondary));
            color: white;
            padding: 2.5rem 2rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            box-shadow: var(--card-shadow);
            animation: fadeIn 0.8s ease 0.4s both;
            position: relative;
            overflow: hidden;
        }

        .welcome-section:after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(255,255,255,0.05)"/></svg>');
            background-size: 100% 100%;
            transform: rotate(15deg);
            z-index: 0;
        }

        .welcome-section h2 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .welcome-section p {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
            max-width: 600px;
        }

        /* إحصائيات */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            animation: fadeIn 0.8s ease 0.6s both;
        }

        .stat-card {
            background-color: white;
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            box-shadow: var(--card-shadow);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary), var(--secondary));
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            font-size: 1.8rem;
            color: white;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
        }

        .stat-info h3 {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .stat-info p {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark);
        }

        /* الأنشطة الأسبوعية */
        .weekly-activities {
            background-color: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            animation: fadeIn 0.8s ease 0.8s both;
        }

        .weekly-activities h3 {
            font-size: 1.3rem;
            color: var(--dark);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--light);
            display: flex;
            align-items: center;
        }

        .weekly-activities h3 i {
            margin-left: 8px;
            color: var(--primary);
        }

        .activity-list {
            list-style: none;
        }

        .activity-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid #f1f1f1;
            transition: all 0.3s;
        }

        .activity-item:hover {
            background-color: var(--light);
            padding-left: 10px;
            padding-right: 10px;
            border-radius: 8px;
        }

        .activity-name {
            display: flex;
            align-items: center;
            font-weight: 500;
        }

        .activity-name i {
            margin-left: 8px;
            color: var(--primary);
        }

        .activity-count {
            background-color: var(--light);
            color: var(--primary);
            padding: 0.2rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* رسوم متحركة */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* التجاوب مع الشاشات المختلفة */
        @media (max-width: 768px) {
            .stats {
                grid-template-columns: 1fr;
            }
            
            .nav {
                flex-direction: column;
            }
            
            .topbar {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .welcome-section h2 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- الشريط العلوي -->
        <div class="topbar">
            <div class="user-info">
                <div class="avatar"><?php echo substr($_SESSION["username"], 0, 1); ?></div>
                <span><?php echo $_SESSION["username"]; ?></span>
            </div>
            <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
        </div>

        <!-- القائمة -->
        <div class="nav">
            <a href="index.php" class="active"><i class="fas fa-home"></i> الرئيسية</a>
            <a href="activities.php"><i class="fas fa-heartbeat"></i> الأنشطة</a>
        </div>

        <!-- قسم الترحيب -->
        <div class="welcome-section">
            <h2>مرحباً <?php echo $_SESSION["username"]; ?>! 👋</h2>
            <p>تابع عاداتك الصحية وحافظ على نمط حياة متوازن مع نظام متتبع الصحة والعادات.</p>
        </div>
        
        <!-- الإحصائيات -->
        <div class="stats">
            <?php
            $stats = getSystemStats();
            ?>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-history"></i>
                </div>
                <div class="stat-info">
                    <h3>إجمالي السجلات</h3>
                    <p><?php echo $stats['total_logs']; ?></p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <h3>أيام المتابعة</h3>
                    <p><?php echo $stats['total_days']; ?></p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-walking"></i>
                </div>
                <div class="stat-info">
                    <h3>الأنشطة المتابعة</h3>
                    <p><?php echo $stats['total_activities']; ?></p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stat-info">
                    <h3>أفضل نشاط</h3>
                    <p><?php echo $stats['top_activity']; ?> (<?php echo $stats['top_activity_count']; ?>)</p>
                </div>
            </div>
        </div>
        
        <!-- الأنشطة الأسبوعية -->
        <div class="weekly-activities">
            <h3><i class="fas fa-chart-pie"></i> أنشطة هذا الأسبوع</h3>
            <?php
            $weekly_stats = getWeeklyStats();
            if (count($weekly_stats['weekly_activities']) > 0): ?>
                <ul class="activity-list">
                    <?php foreach($weekly_stats['weekly_activities'] as $activity): ?>
                        <li class="activity-item">
                            <span class="activity-name">
                                <?php 
                                $icons = [
                                    'walk' => 'fas fa-walking',
                                    'run' => 'fas fa-running',
                                    'gym' => 'fas fa-dumbbell',
                                    'yoga' => 'fas fa-spa',
                                    'meditation' => 'fas fa-brain',
                                    'water' => 'fas fa-tint',
                                    'sleep' => 'fas fa-bed',
                                    'weight' => 'fas fa-weight',
                                    'reading' => 'fas fa-book',
                                    'learning' => 'fas fa-graduation-cap'
                                ];
                                $icon = $icons[$activity['activity_type']] ?? 'fas fa-circle';
                                ?>
                                <i class="<?php echo $icon; ?>"></i>
                                <?php 
                                $activity_names = [
                                    'walk' => 'مشي',
                                    'run' => 'جري',
                                    'gym' => 'نادي رياضي',
                                    'yoga' => 'يوجا',
                                    'meditation' => 'تأمل',
                                    'water' => 'ماء',
                                    'sleep' => 'نوم',
                                    'weight' => 'وزن',
                                    'reading' => 'قراءة',
                                    'learning' => 'تعلم'
                                ];
                                echo $activity_names[$activity['activity_type']] ?? $activity['activity_type'];
                                ?>
                            </span>
                            <span class="activity-count"><?php echo $activity['count']; ?> مرات</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>لا توجد أنشطة مسجلة هذا الأسبوع.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>