<?php
session_start();

use Repository\UserRepository;

require_once(__DIR__ . "/../Repository/IUserRepository.php");
require_once(__DIR__ . "/../Repository/UserRepository.php");

$context = new UserRepository();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$data = $context->getUserByUsername($_SESSION['username']);
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
                <button onclick="location.href='../User/panel.php'" class="nav-link active mb-2">اطلاعات کاربر</button>
                <button onclick="location.href='../User/editInfo.php'" class="nav-link bg-white hover-bg-primary mb-2">تغییر اطلاعات کاربر</button>
                <button onclick="location.href='../User/deleteUser.php'" class="nav-link bg-white mb-2">حذف کاربر</button>
                <button onclick="location.href='../Auth/logout.php'" class="nav-link bg-white">خروج</button>
            </div>
        </div>

        <div class="col-10 p-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h4 class="mb-0">اطلاعات کاربری شما</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tbody>
                        <?php
                        if ($data) {
                            foreach ($data as $key => $value) {
                                if($key == "id" or $key == "password") {
                                    continue;
                                }
                                echo "<tr><th scope='row'>{$key}</th><td>{$value}</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='2' class='text-center text-muted'>اطلاعاتی یافت نشد.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../lib/bootstrap.bundle.min.js"></script>
</body>
</html>
