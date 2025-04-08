<?php
class Category extends Db
{
    //Hàm lấy tất cả danh mục
    public function getAllCates()
    {
        $sql = self::$connection->prepare("SELECT * FROM categories");
        $sql->execute();
        $items = array();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items;
    }
    //Hàm lấy tên cate theo id
    public function getNameByID($id)
    {
        $sql = self::$connection->prepare("SELECT * FROM categories WHERE id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();
        $items = array();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items;
    }
    //Lọc product theo id
    public function getFilterByID($id, $start, $limit)
    {
        $sql = self::$connection->prepare("SELECT * FROM products WHERE category_id = ? LIMIT ?, ?");
        $sql->bind_param("iii", $id, $start, $limit);
        $sql->execute();
        $items = array();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items;
    }
}
