<?php
class Category extends Db
{
    //Hàm lấy tất cả danh mục
    public function getAllCate()
    {
        $sql = self::$connection->prepare("SELECT * FROM categories");
        $sql->execute();
        $items = array();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items;
    }
    // Lấy danh sách theo phân trang
    public function getCategoriesByPage($limit, $offset)
    {
        $sql = self::$connection->prepare("SELECT `id`, `category_name`, `image`
                                           FROM `categories`
                                           ORDER BY `created_at` DESC
                                           LIMIT ? OFFSET ?");
        $sql->bind_param("ii", $limit, $offset);
        $sql->execute();
        $result = $sql->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Phương thúc tính tổng cate
    public function getTotalCategories()
    {
        $sql = self::$connection->prepare("SELECT COUNT(*) AS total FROM `categories`");
        $sql->execute();
        $result = $sql->get_result();
        return $result->fetch_assoc()['total'];
    }

    //Phương thức thêm cate
    public function addCategory($category_name, $image = null)
    {
        $sql = self::$connection->prepare("INSERT INTO `categories`(`category_name`, `image`) 
                                           VALUES (?, ?)");
        $sql->bind_param('ss', $category_name, $image);
        return $sql->execute();
    }

    //Phương thúc xóa cate
    public function deleteCategory($id)
    {
        $sql = self::$connection->prepare("DELETE FROM `categories` WHERE `id`=?");
        $sql->bind_param("i", $id);
        return $sql->execute();
    }

    // Lấy cate theo id
    public function getCategoryById($id)
    {
        $sql = self::$connection->prepare("SELECT * FROM `categories` WHERE `id` = ?");
        $sql->bind_param('i', $id);
        $sql->execute();
        return $sql->get_result()->fetch_assoc();
    }

    //Phương thức cập nhật cate
    public function updateCategory($id, $category_name, $image = null)
    {
        if ($image) {
            $sql = self::$connection->prepare("UPDATE `categories`
                                               SET `category_name`=?, `image`=?
                                               WHERE `id`=?");
            $sql->bind_param('ssi', $category_name, $image, $id);
        } else {
            $sql = self::$connection->prepare("UPDATE `categories`
                                               SET `category_name`=?
                                               WHERE `id`=?");
            $sql->bind_param('si', $category_name, $id);
        }
        return $sql->execute();
    }

    public function hasProducts($cate_id)
    {
        $sql = self::$connection->prepare("SELECT COUNT(*) AS product_count FROM `products` WHERE `category_id` = ?");
        $sql->bind_param('i', $cate_id);
        $sql->execute();
        $result = $sql->get_result();
        $row = $result->fetch_assoc();
        return $row['product_count'] > 0;
    }

    public function searchCategories($searchTerm, $limit, $offset)
    {
        $sql = self::$connection->prepare("SELECT `id`, `category_name`, `image`
                                       FROM `categories`
                                       WHERE `category_name` LIKE ? 
                                       ORDER BY `created_at` DESC
                                       LIMIT ? OFFSET ?");
        $searchTerm = "%" . $searchTerm . "%";
        $sql->bind_param("sii", $searchTerm, $limit, $offset);
        $sql->execute();
        $result = $sql->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotalSearchCategories($searchTerm)
    {
        $sql = self::$connection->prepare("SELECT COUNT(*) AS total FROM `categories` WHERE `category_name` LIKE ?");
        $searchTerm = "%" . $searchTerm . "%";
        $sql->bind_param("s", $searchTerm);
        $sql->execute();
        $result = $sql->get_result();
        return $result->fetch_assoc()['total'];
    }
}
