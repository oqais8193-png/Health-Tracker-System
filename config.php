<?php
// بدء الجلسة فقط إذا لم تكن قد بدأت بالفعل
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// إعدادات الاتصال بقاعدة البيانات
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'health_habits_db');

// محاولة الاتصال بقاعدة البيانات
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// التحقق من الاتصال
if($conn === false){
    die("خطأ في الاتصال: " . mysqli_connect_error());
}

// تعيين الترميز إلى UTF-8 للدعم العربي
mysqli_set_charset($conn, "utf8");

// التحقق من تسجيل الدخول
function checkLogin() {
    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
        header("location: login.php");
        exit;
    }
}

// دالة تسجيل الدخول المبسطة
function login($username, $password) {
    global $conn;
    
    // استخدام كلمة مرور بسيطة للتحقق
    if($username === "health" && $password === "health123"){
        $_SESSION["loggedin"] = true;
        $_SESSION["id"] = 1;
        $_SESSION["username"] = $username;
        return true;
    } else {
        return false;
    }
}

// دالة للحصول على إحصائيات النظام
function getSystemStats() {
    global $conn;
    
    $stats = array();
    
    // إجمالي السجلات
    $sql = "SELECT COUNT(*) as total FROM health_logs";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $stats['total_logs'] = $row['total'];
    
    // إجمالي أيام المتابعة
    $sql = "SELECT COUNT(DISTINCT DATE(date)) as total FROM health_logs";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $stats['total_days'] = $row['total'];
    
    // إجمالي الأنشطة
    $sql = "SELECT COUNT(DISTINCT activity_type) as total FROM health_logs";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $stats['total_activities'] = $row['total'];
    
    // أفضل نشاط
    $sql = "SELECT activity_type, COUNT(*) as count FROM health_logs GROUP BY activity_type ORDER BY count DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $stats['top_activity'] = $row['activity_type'] ?? 'لا يوجد';
    $stats['top_activity_count'] = $row['count'] ?? 0;
    
    return $stats;
}

// دالة للحصول على الإحصائيات الأسبوعية
function getWeeklyStats() {
    global $conn;
    
    $stats = array();
    
    // الأنشطة لهذا الأسبوع
    $sql = "SELECT activity_type, COUNT(*) as count 
            FROM health_logs 
            WHERE YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1) 
            GROUP BY activity_type 
            ORDER BY count DESC";
    $result = mysqli_query($conn, $sql);
    $stats['weekly_activities'] = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    // عدد الأيام النشطة هذا الأسبوع
    $sql = "SELECT COUNT(DISTINCT DATE(date)) as days 
            FROM health_logs 
            WHERE YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $stats['active_days'] = $row['days'];
    
    return $stats;
}
?>