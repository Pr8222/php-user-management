<?php
session_start();

use Repository\UserRepository;

require_once(__DIR__ . "/../Repository/IUserRepository.php");
require_once(__DIR__ . "/../Repository/UserRepository.php");

$context = new UserRepository();

if (!isset($_SESSION['username'])) {
    header("Location: ../Auth/login.php");
    exit();
}

if (!isset($_SESSION['error-message'])) { $_SESSION['error-message'] = ""; }
if (!isset($_SESSION['success-message'])) { $_SESSION['success-message'] = ""; }

$user = $context->getUserByUsername($_SESSION['username']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['error-message'] = "";
    $_SESSION['success-message'] = "";

    if (!$user || !isset($user['id'])) {
        $_SESSION['error-message'] = "کاربر یافت نشد.";
    } else {
        $deleted = $context->deleteUser($user['id']);
        if ($deleted) {
            // end session and redirect to login
            session_unset();
            session_destroy();
            header("Location: ../Auth/login.php");
            exit();
        } else {
            $_SESSION['error-message'] = "حذف کاربر با مشکل مواجه شد.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حذف کاربر | سامانه مدیریت کاربران</title>
    <link rel="stylesheet" href="../lib/bootstrap.rtl.min.css">
</head>

<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-2 vh-100 bg-primary p-3">
            <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
                <button onclick="location.href='../User/panel.php'" class="nav-link bg-white mb-2">اطلاعات کاربر</button>
                <button onclick="location.href='../User/editInfo.php'" class="nav-link bg-white hover-bg-primary mb-2">تغییر اطلاعات کاربر</button>
                <button onclick="location.href='../User/deleteUser.php'" class="nav-link active mb-2">حذف کاربر</button>
                <button onclick="location.href='../Auth/logout.php'" class="nav-link bg-white">خروج</button>
            </div>
        </div>

        <div class="col-10 p-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h4 class="mb-0">حذف حساب کاربری</h4>
                </div>
                <div class="card-body">
                    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['error-message'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($_SESSION['error-message']); ?>
                        </div>
                    <?php endif; ?>

                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <div>
                            حذف حساب کاربری غیرقابل بازگشت است. آیا از حذف حساب خود مطمئن هستید؟
                        </div>
                    </div>

                    <form method="post" class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger">بله، حذف حساب</button>
                        <a href="panel.php" class="btn btn-outline-secondary">خیر، بازگشت</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../lib/bootstrap.bundle.min.js"></script>
</body>
</html>