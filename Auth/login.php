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
    <title>ورود | سامانه مدیریت کاربران</title>
    <link rel="stylesheet" href="../lib/bootstrap.rtl.min.css">
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
                        <h5 class="mb-0">ورود به حساب کاربری</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($_SESSION['error-message'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($_SESSION['error-message']); ?>
                            </div>
                        <?php endif; ?>
                        <form method="post" class="row g-3">
                            <div class="col-12">
                                <label for="username" class="form-label">نام کاربری</label>
                                <input id="username" type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($savedUsername)?>" required>
                            </div>
                            <div class="col-12">
                                <label for="password" class="form-label">رمز عبور</label>
                                <input id="password" type="password"  name="password" class="form-control" required>
                            </div>
                            <div class="col-12 d-grid">
                                <button type="submit" class="btn btn-primary">ورود</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bg-white text-center">
                        <small>کاربر جدید هستید؟ <a href="register.php" class="text-decoration-none">ثبت نام کنید</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../lib/bootstrap.bundle.min.js"></script>
</body>
</html>
