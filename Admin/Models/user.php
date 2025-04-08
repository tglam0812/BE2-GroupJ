<?php
class User extends Db
{
    // Hàm lấy danh sách người dùng với phân trang
    public function getUsersByPage($limit, $offset)
    {
        $sql = self::$connection->prepare("SELECT `id`, `name`, `email`, `role`, `created_at` 
                                           FROM `users` 
                                           ORDER BY `id` DESC 
                                           LIMIT ? OFFSET ?");
        $sql->bind_param("ii", $limit, $offset);
        $sql->execute();
        $result = $sql->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Hàm lấy tổng số người dùng
    public function getTotalUsers()
    {
        $sql = self::$connection->prepare("SELECT COUNT(*) AS total FROM `users`");
        $sql->execute();
        $result = $sql->get_result();
        return $result->fetch_assoc()['total'];
    }

    // Hàm thêm người dùng mới
    public function addUser($name, $email, $password, $role)
    {
        // Mã hóa mật khẩu
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = self::$connection->prepare("INSERT INTO `users`(`name`, `email`, `password`, `role`)
                                           VALUES (?, ?, ?, ?)");
        $sql->bind_param('ssss', $name, $email, $hashedPassword, $role);
        return $sql->execute();
    }

    // Hàm xóa người dùng theo ID
    public function deleteUser($id)
    {
        $sql = self::$connection->prepare("DELETE FROM `users` WHERE `id`=?");
        $sql->bind_param("i", $id);
        return $sql->execute();
    }

    // Hàm lấy thông tin người dùng theo ID
    public function getUserById($id)
    {
        $sql = self::$connection->prepare("SELECT * FROM `users` WHERE `id` = ?");
        $sql->bind_param('i', $id);
        $sql->execute();
        return $sql->get_result()->fetch_assoc();
    }

    // Hàm cập nhật thông tin người dùng
    public function updateUser($id, $name, $email, $password = null, $role)
    {
        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = self::$connection->prepare("UPDATE `users` 
                                               SET `name`=?, `email`=?, `password`=?, `role`=?
                                               WHERE `id`=?");
            $sql->bind_param('ssssi', $name, $email, $hashedPassword, $role, $id);
        } else {
            $sql = self::$connection->prepare("UPDATE `users` 
                                               SET `name`=?, `email`=?, `role`=?
                                               WHERE `id`=?");
            $sql->bind_param('sssi', $name, $email, $role, $id);
        }
        return $sql->execute();
    }

    // Hàm kiểm tra email đã tồn tại chưa
    public function emailExists($email)
    {
        $sql = self::$connection->prepare("SELECT COUNT(*) AS total FROM `users` WHERE `email` = ?");
        $sql->bind_param('s', $email);
        $sql->execute();
        $result = $sql->get_result();
        return $result->fetch_assoc()['total'] > 0;
    }

    // Hàm tìm kiếm người dùng
    public function searchUsers($searchTerm, $limit, $offset)
    {
        $sql = self::$connection->prepare("SELECT `id`, `name`, `email`, `role`, `created_at` 
                                            FROM `users` 
                                            WHERE `name` LIKE ? OR `email` LIKE ? OR `role` LIKE ? 
                                            ORDER BY `id` DESC 
                                            LIMIT ? OFFSET ?");
        $searchTermLike = "%" . $searchTerm . "%";
        $sql->bind_param("ssssi", $searchTermLike, $searchTermLike, $searchTermLike, $limit, $offset);
        $sql->execute();
        $result = $sql->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Hàm lấy tổng số người dùng tìm kiếm
    public function getTotalSearchUsers($searchTerm)
    {
        $sql = self::$connection->prepare("SELECT COUNT(*) AS total FROM `users` 
                                            WHERE `name` LIKE ? OR `email` LIKE ? OR `role` LIKE ?");
        $searchTermLike = "%" . $searchTerm . "%";
        $sql->bind_param("sss", $searchTermLike, $searchTermLike, $searchTermLike);
        $sql->execute();
        $result = $sql->get_result();
        return $result->fetch_assoc()['total'];
    }
}
