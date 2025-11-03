<?php

use Repository\UserRepository;

SESSION_START();
require_once("../Repository/IUserRepository.php");
require_once("../Repository/UserRepository.php");

$_SESSION["error-message"] = "";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $userdata = ["username" => $_POST['username'], "email" => $_POST['email'], "password" => $password];
    $context = new UserRepository();
    if(!$context->getUserByUsername($userdata["username"])){
        if ($context->addNewUser($userdata)) {
            $_SESSION["username"] = $userdata["username"];
            header("location: ../User/panel.php");
            exit();
        }
        else {
            $_SESSION["error-message"] = "عملیات ثبت نام با مشکل روبرو شد.";
        }
    }
    else {
        $_SESSION["error-message"] = "نام کاربری وجود دارد!";
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../lib/bootstrap.rtl.min.css">
    <title>ثبت نام | سامانه مدیریت کاربران</title>
    <style>
        body { min-height: 100vh; }
    </style>
</head>
<body class="bg-light d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-8 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">ایجاد حساب کاربری</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($_SESSION["error-message"])): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($_SESSION["error-message"]); ?>
                            </div>
                        <?php endif; ?>
                        <form method="post" class="row g-3">
                            <div class="col-12">
                                <label for="username" class="form-label">نام کاربری</label>
                                <input id="username" type="text" name="username" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label for="email" class="form-label">پست الکترونیک</label>
                                <input id="email" type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label for="password" class="form-label">رمز عبور</label>
                                <input id="password" type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label for="confirm-password" class="form-label">تکرار رمز عبور</label>
                                <input id="confirm-password" type="password" class="form-control" required>
                            </div>
                            <div class="col-12 d-grid">
                                <button type="submit" class="btn btn-primary">ثبت نام</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bg-white text-center">
                        <small>حساب دارید؟ <a href="../Auth/login.php" class="text-decoration-none">وارد شوید</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../lib/bootstrap.bundle.min.js"></script>
</body>
</html>