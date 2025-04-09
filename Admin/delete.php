<?php
session_start();
include '../Config/config.php';
include '../Admin/Models/db.php';
include '../Admin/Models/user.php';


$user = new User();

$current_user_id = $_SESSION['user_id'];

if  (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    if ($user_id == $current_user_id) {
        $_SESSION['error'] = "Bạn không thể xóa tài khoản của chính mình!";
        header('Location: user-list.php');
        exit;
    }

    if ($user->deleteUser($user_id)) {
        $_SESSION['success'] = "User đã được xóa thành công!";
    } else {
        $_SESSION['error'] = "Có lỗi xảy ra khi xóa user.";
    }

    header('Location: user-list.php');
    exit;
}