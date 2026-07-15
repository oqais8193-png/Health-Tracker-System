<?php
include "config.php";

// مسح جميع متغيرات الجلسة
$_SESSION = array();

// تدمير الجلسة
session_destroy();

// التوجيه إلى صفحة تسجيل الدخول
header("location: login.php");
exit;
?>