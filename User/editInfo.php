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

$data = $context->getUserByUsername($_SESSION['username']);

if (!isset($_SESSION['error-message'])) { $_SESSION['error-message'] = ""; }
if (!isset($_SESSION['success-message'])) { $_SESSION['success-message'] = ""; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['error-message'] = "";
    $_SESSION['success-message'] = "";

    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $newPassword = isset($_POST['password']) ? trim($_POST['password']) : '';
    $confirmPassword = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error-message'] = "ایمیل معتبر نیست.";
    } else if ($newPassword !== '' && $newPassword !== $confirmPassword) {
        $_SESSION['error-message'] = "تکرار رمز عبور با رمز عبور همخوانی ندارد.";
    } else {
        $updatePayload = [
            'username' => $data['username'],
            'email' => $email,
            'password' => $newPassword !== '' ? password_hash($newPassword, PASSWORD_DEFAULT) : $data['password']
        ];

        $updated = $context->updateUser($data['id'], $updatePayload);
        if ($updated) {
            $_SESSION['success-message'] = "تغییرات با موفقیت ذخیره شد.";
            // refresh local data for rendering
            $data = $context->getUserByUsername($_SESSION['username']);
            // override email in case username-based fetch has delay
            $data['email'] = $email;
        } else {
            $_SESSION['error-message'] = "در ذخیره تغییرات خطایی رخ داد.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سامانه صدور کارت شناسایی - پنل کاربر</title>
    <link rel="stylesheet" href="../lib/bootstrap.rtl.min.css">
</head>

<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-2 vh-100 bg-primary p-3">
            <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
                <button onclick="location.href='../User/panel.php'" class="nav-link bg-white mb-2">اطلاعات کاربر</button>
                <button onclick="location.href='../User/editInfo.php'" class="nav-link  hover-bg-primary mb-2 active">تغییر اطلاعات کاربر</button>
                <button onclick="location.href='../User/deleteUser.php'" class="nav-link bg-white mb-2">حذف کاربر</button>
                <button onclick="location.href='../Auth/logout.php'" class="nav-link bg-white">خروج</button>
            </div>
        </div>

        <div class="col-10 p-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h4 class="mb-0">تغییر اطلاعات</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($_SESSION['error-message'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($_SESSION['error-message']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['success-message'])): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo htmlspecialchars($_SESSION['success-message']); ?>
                        </div>
                    <?php endif; ?>
                    <form method="post" action="editInfo.php" class="row g-3">
                        <div class="col-md-6">
                            <label for="username" class="form-label">نام کاربری</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($data['username']) ? htmlspecialchars($data['username']) : ''; ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">پست الکترونیک</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($data['email']) ? htmlspecialchars($data['email']) : ''; ?>" required>
                        </div>

                        <div class="col-12">
                            <hr>
                            <h6 class="text-muted mb-3">تغییر رمز عبور (اختیاری)</h6>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">رمز عبور جدید</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="در صورت عدم تغییر، خالی بگذارید">
                        </div>
                        <div class="col-md-6">
                            <label for="confirm_password" class="form-label">تکرار رمز عبور جدید</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="تکرار رمز عبور">
                        </div>

                        <div class="col-12 d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-outline-secondary" onclick="location.href='panel.php'">بازگشت</button>
                            <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../lib/bootstrap.bundle.min.js"></script>
</body>
</html>
