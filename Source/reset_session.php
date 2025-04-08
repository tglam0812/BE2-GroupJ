<?php
// session_start();

// // Kiểm tra nếu session có lưu thời gian tạo
// if (!isset($_SESSION['session_created'])) {
//     $_SESSION['session_created'] = time(); // Lưu thời gian tạo session lần đầu tiên
// }

// // Định nghĩa thời gian giới hạn (Ví dụ: ngày 16/03/2025 lúc 19:00)
// $cutoffTime = strtotime("2025-03-16 19:00:00");

// // Nếu session được tạo trước thời gian giới hạn, reset nó
// if ($_SESSION['session_created'] < $cutoffTime) {
//     session_unset();  // Xóa tất cả biến trong session
//     session_destroy(); // Hủy session cũ
//     session_start();   // Bắt đầu session mới
//     $_SESSION['session_created'] = time(); // Lưu thời gian tạo session mới
// }

// header("Location: index.php"); // Chuyển hướng về trang chủ hoặc trang mong muốn
// exit();

