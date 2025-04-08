<?php
class Product extends Db
{
    //Hàm lấy tất cả sản phẩm
    public function getProductsByPage($limit, $offset)
    {
        $sql = self::$connection->prepare("SELECT `products`.`id`, `product_name`, `categories`.`category_name` AS cateName, `price`, `stock_quantity`, `sold_quantity`, `description`, `products`.`image`, `views`, `is_featured`, `products`.`created_at`, `sale`
                                       FROM `products`, `categories`
                                       WHERE `products`.`category_id` = `categories`.`id`
                                       ORDER BY `products`.`created_at` DESC
                                       LIMIT ? OFFSET ?");
        $sql->bind_param("ii", $limit, $offset);
        $sql->execute();
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotalProducts()
    {
        $sql = self::$connection->prepare("SELECT COUNT(*) AS total FROM `products`");
        $sql->execute();
        return $sql->get_result()->fetch_assoc()['total'];
    }

    function truncateByCharacters($text, $maxLength)
    {
        if (strlen($text) > $maxLength) {
            return substr($text, 0, $maxLength) . "...";
        }
        return $text;
    }
    // Hàm thêm sản phẩm
    function addProduct($name, $cate, $price, $stock, $sold, $description, $image, $view, $featured, $sale)
    {
        $sql = self::$connection->prepare("INSERT INTO `products`(`product_name`, `category_id`, `price`, `stock_quantity`, `sold_quantity`, `description`, `image`, `views`, `is_featured`, `sale`)
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $sql->bind_param('siidissdii', $name, $cate, $price, $stock, $sold, $description, $image, $view, $featured, $sale);
        return $sql->execute();
    }
    //Hàm xóa sản phẩm
    function delete($id)
    {
        $sql = self::$connection->prepare("DELETE FROM `products` WHERE `id`=?");
        $sql->bind_param("i", $id);
        return $sql->execute();
    }
    //Hàm lấy sản phẩm theo id
    function getProductById($id)
    {
        $sql = self::$connection->prepare("SELECT * FROM products WHERE id = ?");
        $sql->bind_param('i', $id);
        $sql->execute();
        $item = $sql->get_result();
        return $item->fetch_assoc();
    }
    //Hàm update sản phẩm
    function updateProduct($name, $cate, $price, $stock, $sold, $description, $image, $view, $featured, $sale, $id)
    {
        if ($image != "") {
            $sql = self::$connection->prepare("UPDATE `products` 
                                            SET `product_name`=?,`category_id`=?,`price`=?,`stock_quantity`=?,`sold_quantity`=?,`description`=?,`image`=?,`views`=?,`is_featured`=?,`sale`=?
                                            WHERE `id`=?");
            $sql->bind_param('siidissdiii', $name, $cate, $price, $stock, $sold, $description, $image, $view, $featured, $sale, $id);
        } else {
            $sql = self::$connection->prepare("UPDATE `products` 
                                            SET `product_name`=?,`category_id`=?,`price`=?,`stock_quantity`=?,`sold_quantity`=?,`description`=?,`views`=?,`is_featured`=?,`sale`=?
                                            WHERE `id`=?");
            $sql->bind_param('siidisdiii', $name, $cate, $price, $stock, $sold, $description, $view, $featured, $sale, $id);
        }
        return $sql->execute();
    }
    // Tìm kiếm sản phẩm theo tên
    public function searchProducts($searchTerm, $limit, $offset)
    {
        $sql = self::$connection->prepare("SELECT `products`.`id`, `product_name`, `categories`.`category_name` AS cateName, `price`, `stock_quantity`, `sold_quantity`, `description`, `products`.`image`, `views`, `is_featured`, `products`.`created_at`, `sale`
                                           FROM `products`, `categories`
                                           WHERE `products`.`category_id` = `categories`.`id`
                                           AND `product_name` LIKE ? 
                                           ORDER BY `products`.`created_at` DESC
                                           LIMIT ? OFFSET ?");
        $searchTerm = "%" . $searchTerm . "%";
        $sql->bind_param("sii", $searchTerm, $limit, $offset);
        $sql->execute();
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy tổng số sản phẩm tìm kiếm
    public function getTotalSearchProducts($searchTerm)
    {
        $sql = self::$connection->prepare("SELECT COUNT(*) AS total 
                                           FROM `products`
                                           WHERE `product_name` LIKE ?");
        $searchTerm = "%" . $searchTerm . "%";
        $sql->bind_param("s", $searchTerm);
        $sql->execute();
        $result = $sql->get_result();
        return $result->fetch_assoc()['total'];
    }
}
