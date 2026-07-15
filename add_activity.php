<?php
include "config.php";
checkLogin();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $activity_type = $_POST['activity_type'];
    $value = $_POST['value'];
    $unit = $_POST['unit'];
    $notes = $_POST['notes'];
    $date = $_POST['date'];
    
    $sql = "INSERT INTO health_logs (activity_type, value, unit, notes, date) 
            VALUES ('$activity_type', '$value', '$unit', '$notes', '$date')";
    
    if(mysqli_query($conn, $sql)) {
        header("Location: activities.php?message=تم إضافة النشاط بنجاح");
        exit();
    } else {
        header("Location: activities.php?error=حدث خطأ أثناء إضافة النشاط");
        exit();
    }
}
?>