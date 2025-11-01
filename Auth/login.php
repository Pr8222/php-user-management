<?php

use Repository\UserRepository;

SESSION_START();
$savedUsername = $_COOKIE["username"] ?? "";
    require_once("../Repository/IUserRepository.php");
    require_once("../Repository/UserRepository.php");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $context = new UserRepository();
        $data = $context->getUserByUsername($_POST['username']);
        if(empty($data)){
            $_SESSION['error-message'] = "کاربر یافت نشد!";
        }
        if(password_verify($_POST['password'], $data['password'])){
            $_SESSION['username'] = $_POST['username'];
            setcookie("username", $_POST['username'], time() + 3600);
            header("location: ../User/panel.php");
            exit;
        }
        else{
            $_SESSION['error-message'] = "رمز عبور اشتباه است!";
        }
    }
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت کاربران</title>
</head>
<body>
    <form method="post">
        <label for="username">نام کاربری</label>
        <input id="username" type="text" name="username" value="<?php echo htmlspecialchars($savedUsername)?>" required> <br>
        <label for="password">رمز عبور</label>
        <input id="password" type="password"  name="password" required> <br>
        <button type="submit">ورود</button>
        <p>کاربر جدید هستید: <a href="register.php">اینجا کلیک کنید.</a> </p>
    </form>
</body>
</html>
