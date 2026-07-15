<?php
include "config.php";

// إذا كان المستخدم مسجلاً بالفعل، إعادة توجيهه إلى الصفحة الرئيسية
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}

// معالجة بيانات تسجيل الدخول
$username = $password = "";
$login_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    
    if(login($username, $password)){
        header("location: index.php");
        exit();
    } else {
        $login_err = "اسم المستخدم أو كلمة المرور غير صحيحة.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - متتبع الصحة والعادات</title>
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
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tajawal', sans-serif;
        }

        body {
            background: linear-gradient(120deg, var(--primary), var(--secondary));
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-container {
            background-color: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            animation: fadeIn 0.8s ease;
        }

        .login-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .login-header h2 {
            color: var(--primary);
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #6c757d;
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

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .input-icon .form-control {
            padding-right: 45px;
        }

        .btn {
            width: 100%;
            padding: 0.8rem;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
        }

        .btn i {
            margin-left: 8px;
        }

        .alert {
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .alert-danger {
            background-color: rgba(247, 37, 133, 0.15);
            color: #F72585;
            border: 1px solid rgba(247, 37, 133, 0.2);
        }

        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .login-footer a {
            color: var(--primary);
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2><i class="fas fa-heartbeat"></i> متتبع الصحة والعادات</h2>
            <p>يرجى تسجيل الدخول للمتابعة</p>
        </div>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">اسم المستخدم</label>
                <div class="input-icon">
                    <input type="text" id="username" name="username" class="form-control" value="<?php echo $username; ?>" required>
                    <i class="fas fa-user"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <div class="input-icon">
                    <input type="password" id="password" name="password" class="form-control" required>
                    <i class="fas fa-lock"></i>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn"><i class="fas fa-sign-in-alt"></i> تسجيل الدخول</button>
            </div>
        </form>

        <div class="login-footer">
            <p>بيانات الدخول الافتراضية: health / health123</p>
        </div>
    </div>
</body>
</html>