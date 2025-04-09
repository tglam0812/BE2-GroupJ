<?php
session_start();
include '../Config/config.php';
include '../Admin/Models/db.php';
include '../Admin/Models/product.php';

$product = new Product();

$name = $_POST['name'];
$cate = $_POST['cate'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$sold = $_POST['sold'] ?: 0;
$description = $_POST['desc'];
$image = $_FILES['fileUpload']['name'];
$view = $_POST['view'] ?: 0;
$featured = $_POST['featured'];
$sale = $_POST['sale'];

$error = [];

if (empty($name)) {
    $error[] = "Tên sản phẩm là bắt buộc.";
}
if (empty($cate)) {
    $error[] = "Danh mục sản phẩm là bắt buộc.";
}
if (empty($price)) {
    $error[] = "Giá sản phẩm là bắt buộc.";
} elseif (!is_numeric($price)) {
    $error[] = "Giá sản phẩm phải là một số.";
}
if (empty($stock)) {
    $error[] = "Số lượng sản phẩm là bắt buộc.";
}
if (empty($image)) {
    $error[] = "Ảnh sản phẩm là bắt buộc.";
}
if (!is_numeric($sale)) {
    $error[] = "Giảm giá phải là một số hợp lệ.";
}

$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
$image_extension = pathinfo($image, PATHINFO_EXTENSION);
if (!in_array(strtolower($image_extension), $allowed_extensions)) {
    $error[] = "Vui lòng tải lên một file hình ảnh hợp lệ (jpg, jpeg, png, gif).";
}

$maxFileSize = 2 * 1024 * 1024; // 2MB
$fileSize = $_FILES['fileUpload']['size'];
if ($fileSize > $maxFileSize) {
    $error[] = "Kích thước tệp không được vượt quá 2MB.";
}

if (!empty($error)) {
    $_SESSION['error'] = $error;
    header('Location: product-add.php');
    exit;
}

$product->addProduct($name, $cate, $price, $stock, $sold, $description, $image, $view, $featured, $sale);

$target_dir = "../img/product/";
$target_file = $target_dir . basename($_FILES["fileUpload"]["name"]);
if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file)) {
    $_SESSION['success'] = "Sản phẩm đã được thêm thành công!";
} else {
    $_SESSION['error'] = ["Có lỗi xảy ra khi tải hình ảnh lên."];
}

header('Location: product-list.php');
exit;
