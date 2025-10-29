<?php

use Repository\UserRepository;

SESSION_START();
require_once("../Repository/IUserRepository.php");
require_once("../Repository/UserRepository.php");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $userdata = ["username" => $_POST['username'], "email" => $_POST['email'], "password" => $_POST['password']];
    $context = new UserRepository();
    if(!$userdata["username"] == $context->getUserByUsername($userdata["username"])){
        if ($context->addNewUser($userdata)) {
            header("location: User/panel.php");
        }
        else {
            echo "An error occurred while adding new user";
        }
    }
    else {
        echo "Username already taken";
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
        <input id="username" type="text" name="username" required><br>
        <label for="email">پست الکترونیک</label>
        <input id="email" type="email" name="email" required><br>
        <label id="password">رمزعبور</label>
        <input id="password" type="password" name="password" required><br>
        <label for="confirm-password">تکرار رمزعبور</label>
        <input id="confirm-password" type="password" required> <br>
        <button type="submit">ثبت نام</button>
    </form>
    <a href="login.php">ورود</a>
</body>
</html>