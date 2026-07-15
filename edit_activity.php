<?php
include "config.php";
checkLogin();

if(!isset($_GET['id'])) {
    header("Location: activities.php");
    exit();
}

$id = $_GET['id'];

// جلب بيانات النشاط
$sql = "SELECT * FROM health_logs WHERE id = $id";
$result = mysqli_query($conn, $sql);
$activity = mysqli_fetch_assoc($result);

if(!$activity) {
    header("Location: activities.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $activity_type = $_POST['activity_type'];
    $value = $_POST['value'];
    $unit = $_POST['unit'];
    $notes = $_POST['notes'];
    $date = $_POST['date'];
    
    $sql = "UPDATE health_logs SET 
            activity_type = '$activity_type', 
            value = '$value', 
            unit = '$unit', 
            notes = '$notes',
            date = '$date'
            WHERE id = $id";
    
    if(mysqli_query($conn, $sql)) {
        header("Location: activities.php?message=تم تحديث النشاط بنجاح");
        exit();
    } else {
        header("Location: activities.php?error=حدث خطأ أثناء تحديث النشاط");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل النشاط - متتبع الصحة والعادات</title>
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
            max-width: 800px;
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

        /* رأس الصفحة */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            animation: fadeIn 0.8s ease 0.4s both;
        }

        .page-title {
            font-size: 1.8rem;
            color: var(--dark);
            display: flex;
            align-items: center;
        }

        .page-title i {
            margin-left: 10px;
            color: var(--primary);
        }

        .btn {
            padding: 0.7rem 1.2rem;
            border-radius: 30px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s;
            font-weight: 500;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn i {
            margin-left: 5px;
        }

        /* نموذج التعديل */
        .form-container {
            background-color: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            animation: fadeIn 0.8s ease 0.6s both;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #444;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -10px;
            margin-left: -10px;
        }

        .form-col {
            flex: 1 0 0%;
            padding: 0 10px;
        }

        select.form-control {
            height: 45px;
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
            .form-col {
                flex: 0 0 100%;
                margin-bottom: 1rem;
            }
            
            .form-row {
                margin-bottom: -1rem;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
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
            <a href="index.php"><i class="fas fa-home"></i> الرئيسية</a>
            <a href="activities.php" class="active"><i class="fas fa-heartbeat"></i> الأنشطة</a>
        </div>

        <!-- رأس الصفحة -->
        <div class="page-header">
            <h1 class="page-title"><i class="fas fa-edit"></i> تعديل النشاط</h1>
            <a href="activities.php" class="btn btn-secondary"><i class="fas fa-arrow-right"></i> العودة إلى القائمة</a>
        </div>

        <!-- نموذج تعديل النشاط -->
        <div class="form-container">
            <form method="post">
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="activity_type">نوع النشاط</label>
                            <select class="form-control" id="activity_type" name="activity_type" required>
                                <option value="walk" <?php echo $activity['activity_type'] == 'walk' ? 'selected' : ''; ?>>مشي</option>
                                <option value="run" <?php echo $activity['activity_type'] == 'run' ? 'selected' : ''; ?>>جري</option>
                                <option value="gym" <?php echo $activity['activity_type'] == 'gym' ? 'selected' : ''; ?>>نادي رياضي</option>
                                <option value="yoga" <?php echo $activity['activity_type'] == 'yoga' ? 'selected' : ''; ?>>يوجا</option>
                                <option value="meditation" <?php echo $activity['activity_type'] == 'meditation' ? 'selected' : ''; ?>>تأمل</option>
                                <option value="water" <?php echo $activity['activity_type'] == 'water' ? 'selected' : ''; ?>>ماء</option>
                                <option value="sleep" <?php echo $activity['activity_type'] == 'sleep' ? 'selected' : ''; ?>>نوم</option>
                                <option value="weight" <?php echo $activity['activity_type'] == 'weight' ? 'selected' : ''; ?>>وزن</option>
                                <option value="reading" <?php echo $activity['activity_type'] == 'reading' ? 'selected' : ''; ?>>قراءة</option>
                                <option value="learning" <?php echo $activity['activity_type'] == 'learning' ? 'selected' : ''; ?>>تعلم</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="value">القيمة</label>
                            <input type="number" step="0.01" class="form-control" id="value" name="value" value="<?php echo $activity['value']; ?>" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="unit">الوحدة</label>
                            <select class="form-control" id="unit" name="unit" required>
                                <option value="minutes" <?php echo $activity['unit'] == 'minutes' ? 'selected' : ''; ?>>دقائق</option>
                                <option value="hours" <?php echo $activity['unit'] == 'hours' ? 'selected' : ''; ?>>ساعات</option>
                                <option value="km" <?php echo $activity['unit'] == 'km' ? 'selected' : ''; ?>>كم</option>
                                <option value="cups" <?php echo $activity['unit'] == 'cups' ? 'selected' : ''; ?>>أكواب</option>
                                <option value="kg" <?php echo $activity['unit'] == 'kg' ? 'selected' : ''; ?>>كجم</option>
                                <option value="pages" <?php echo $activity['unit'] == 'pages' ? 'selected' : ''; ?>>صفحات</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">ملاحظات</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"><?php echo $activity['notes']; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="date">التاريخ والوقت</label>
                    <input type="datetime-local" class="form-control" id="date" name="date" value="<?php echo date('Y-m-d\TH:i', strtotime($activity['date'])); ?>" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ التعديلات</button>
                    <a href="activities.php" class="btn btn-secondary"><i class="fas fa-times"></i> إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>