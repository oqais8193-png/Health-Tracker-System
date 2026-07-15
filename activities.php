<?php
include "config.php";
checkLogin();

// معالجة عمليات الحذف
if(isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM health_logs WHERE id = $id";
    if(mysqli_query($conn, $sql)) {
        header("Location: activities.php?message=تم حذف السجل بنجاح");
        exit();
    }
}

// جلب جميع السجلات
$sql = "SELECT * FROM health_logs ORDER BY date DESC";
$result = mysqli_query($conn, $sql);
$activities = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الأنشطة - متتبع الصحة والعادات</title>
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

        .btn i {
            margin-left: 5px;
        }

        /* الجدول */
        .table-container {
            background-color: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            overflow-x: auto;
            animation: fadeIn 0.8s ease 0.6s both;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: right;
            border-bottom: 1px solid #f1f1f1;
        }

        th {
            font-weight: 600;
            color: #495057;
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: var(--light);
        }

        .badge {
            padding: 0.35rem 0.65rem;
            border-radius: 50rem;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .badge-walk { background-color: rgba(236, 72, 153, 0.15); color: var(--primary); }
        .badge-run { background-color: rgba(16, 185, 129, 0.15); color: var(--success); }
        .badge-gym { background-color: rgba(139, 92, 246, 0.15); color: var(--info); }
        .badge-yoga { background-color: rgba(245, 158, 11, 0.15); color: var(--warning); }
        .badge-meditation { background-color: rgba(59, 130, 246, 0.15); color: #3B82F6; }
        .badge-water { background-color: rgba(6, 182, 212, 0.15); color: #06B6D4; }
        .badge-sleep { background-color: rgba(139, 92, 246, 0.15); color: var(--info); }
        .badge-weight { background-color: rgba(16, 185, 129, 0.15); color: var(--success); }
        .badge-reading { background-color: rgba(236, 72, 153, 0.15); color: var(--primary); }
        .badge-learning { background-color: rgba(245, 158, 11, 0.15); color: var(--warning); }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-sm {
            padding: 0.4rem 0.7rem;
            font-size: 0.8rem;
            border-radius: 30px;
        }

        .btn-info {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            color: white;
        }

        .btn-info:hover {
            transform: translateY(-2px);
        }

        .btn-danger {
            background: linear-gradient(45deg, #EF4444, #DC2626);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
        }

        /* نموذج الإضافة */
        .form-container {
            background-color: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            animation: fadeIn 0.8s ease 0.8s both;
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
            
            .action-buttons {
                flex-wrap: wrap;
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
            <h1 class="page-title"><i class="fas fa-heartbeat"></i> إدارة الأنشطة</h1>
            <button onclick="showAddForm()" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة نشاط جديد</button>
        </div>

        <!-- نموذج إضافة نشاط -->
        <div class="form-container" id="addForm" style="display: none;">
            <h2 style="margin-bottom: 1.5rem;">إضافة نشاط جديد</h2>
            <form action="add_activity.php" method="post">
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="activity_type">نوع النشاط</label>
                            <select class="form-control" id="activity_type" name="activity_type" required>
                                <option value="walk">مشي</option>
                                <option value="run">جري</option>
                                <option value="gym">نادي رياضي</option>
                                <option value="yoga">يوجا</option>
                                <option value="meditation">تأمل</option>
                                <option value="water">ماء</option>
                                <option value="sleep">نوم</option>
                                <option value="weight">وزن</option>
                                <option value="reading">قراءة</option>
                                <option value="learning">تعلم</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="value">القيمة</label>
                            <input type="number" step="0.01" class="form-control" id="value" name="value" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="unit">الوحدة</label>
                            <select class="form-control" id="unit" name="unit" required>
                                <option value="minutes">دقائق</option>
                                <option value="hours">ساعات</option>
                                <option value="km">كم</option>
                                <option value="cups">أكواب</option>
                                <option value="kg">كجم</option>
                                <option value="pages">صفحات</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">ملاحظات</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="date">التاريخ والوقت</label>
                    <input type="datetime-local" class="form-control" id="date" name="date" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ النشاط</button>
                    <button type="button" onclick="hideAddForm()" class="btn" style="background: #6c757d; color: white;"><i class="fas fa-times"></i> إلغاء</button>
                </div>
            </form>
        </div>

        <!-- جدول الأنشطة -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>نوع النشاط</th>
                        <th>القيمة</th>
                        <th>الملاحظات</th>
                        <th>التاريخ</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($activities) > 0): ?>
                        <?php foreach($activities as $activity): ?>
                            <?php
                            // تحديد اسم النشاط ورمز البادج
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
                            
                            $activity_class = 'badge-' . $activity['activity_type'];
                            $activity_text = $activity_names[$activity['activity_type']] ?? $activity['activity_type'];
                            ?>
                            <tr>
                                <td><span class="badge <?php echo $activity_class; ?>"><?php echo $activity_text; ?></span></td>
                                <td><?php echo $activity['value'] . ' ' . $activity['unit']; ?></td>
                                <td><?php echo $activity['notes']; ?></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($activity['date'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit_activity.php?id=<?php echo $activity['id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-edit"></i></a>
                                        <a href="activities.php?delete_id=<?php echo $activity['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من حذف هذا السجل؟')"><i class="fas fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">لا توجد أنشطة مسجلة</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function showAddForm() {
            document.getElementById('addForm').style.display = 'block';
            // تعيين التاريخ الحالي كقيمة افتراضية
            document.getElementById('date').value = new Date().toISOString().slice(0, 16);
        }

        function hideAddForm() {
            document.getElementById('addForm').style.display = 'none';
        }
    </script>
</body>
</html>