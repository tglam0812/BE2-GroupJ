<?php
class Product extends Db
{
    //Hàm lấy tất cả sản phẩm
    public function getAllProducts()
    {
        $sql = self::$connection->prepare("SELECT * FROM products ORDER BY created_at DESC");
        $sql->execute();
        $items = array();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items;
    }
    // Hàm lấy sản phẩm với phân trang
    public function getProductsByPage($page, $limit)
    {
        $offset = ($page - 1) * $limit;
        $sql = self::$connection->prepare("SELECT * FROM products ORDER BY created_at DESC LIMIT ?, ?");
        $sql->bind_param("ii", $offset, $limit);
        $sql->execute();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items;
    }
    // Hàm lấy tổng số sản phẩm
    public function getTotalProducts()
    {
        $sql = self::$connection->prepare("SELECT COUNT(*) AS total FROM products");
        $sql->execute();
        $result = $sql->get_result()->fetch_assoc();
        return $result['total'];
    }
    //Hàm lấy sản phẩm hàng đầu
    public function getTopProducts($limit = 6)
    {
        $sql = self::$connection->prepare("SELECT * FROM products ORDER BY sold_quantity DESC LIMIT ?");
        $sql->bind_param("i", $limit);
        $sql->execute();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items;
    }
    //Hàm lấy tất cả sản phẩm nổi bật
    public function getFeaturedPagination($page = 1, $limit = 4)
    {
        $offset = ($page - 1) * $limit;
        $sql = self::$connection->prepare("SELECT * FROM products WHERE is_featured = 1 ORDER BY created_at DESC LIMIT ?, ?");
        $sql->bind_param("ii", $offset, $limit);
        $sql->execute();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items;
    }
    // Hàm hiển thị sản phẩm mới nhất
    public function getNewProducts($limit = 3)
    {
        $sql = self::$connection->prepare("SELECT * FROM products ORDER BY created_at DESC LIMIT ?");
        $sql->bind_param("i", $limit);
        $sql->execute();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items;
    }
    public function getSaleProducts()
    {
        $sql = self::$connection->prepare("SELECT * FROM products WHERE sale > 0 ORDER BY created_at DESC");
        $sql->execute();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items;
    }

    public function getTotalFeaturedProducts()
    {
        $sql = self::$connection->prepare("SELECT COUNT(*) AS total FROM products WHERE is_featured = 1");
        $sql->execute();
        $result = $sql->get_result()->fetch_assoc();
        return $result['total'];
    }
    public function Paginate($base_url, $total_items, $items_per_page)
    {
        $total_pages = ceil($total_items / $items_per_page);
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $pagination = '<nav aria-label="Page navigation example"><ul class="pagination">';
        if ($current_page > 1) {
            $previous_page = $current_page - 1;
            $pagination .= '<li class="page-item"><a class="page-link" href="' . $base_url . $previous_page . '">Previous</a></li>';
        }
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $current_page) {
                $pagination .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
            } else {
                $pagination .= '<li class="page-item"><a class="page-link" href="' . $base_url . $i . '">' . $i . '</a></li>';
            }
        }
        if ($current_page < $total_pages) {
            $next_page = $current_page + 1;
            $pagination .= '<li class="page-item"><a class="page-link" href="' . $base_url . $next_page . '">Next</a></li>';
        }

        $pagination .= '</ul></nav>';
        return $pagination;
    }
    public function searchProducts($keyword)
    {
        $sql = self::$connection->prepare('SELECT * FROM `products` WHERE `product_name` LIKE ?');
        $keyword = "%$keyword%";
        $sql->bind_param("s", $keyword);
        $sql->execute();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items;
    }

    public function getTotalProductsByCategory($category)
    {
        $sql = self::$connection->prepare('SELECT COUNT(*) AS total FROM `products` WHERE `category_id` = ?');
        $sql->bind_param("i", $category);
        $sql->execute();
        $result = $sql->get_result()->fetch_assoc();
        return $result['total'];
    }
    public function getProductDetails($id)
    {
        $sql = self::$connection->prepare("SELECT * FROM products WHERE id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();
        $result = $sql->get_result()->fetch_assoc();
        return $result;
    }
    // Hàm hiển thị sản phẩm liên quan
    public function getRelatedProducts($category_id, $exclude_id, $limit = 4)
    {
        $sql = self::$connection->prepare("
            SELECT * 
            FROM products 
            WHERE category_id = ? AND id != ? 
            LIMIT ?
        ");
        $sql->bind_param("iii", $category_id, $exclude_id, $limit);
        $sql->execute();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items;
    }
    public function getProductReviews($product_id)
    {
        $sql = self::$connection->prepare("
            SELECT * 
            FROM reviews 
            WHERE product_id = ?
            ORDER BY created_at DESC
        ");
        $sql->bind_param("i", $product_id);
        $sql->execute();
        $reviews = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $reviews;
    }
    public function addProductReview($product_id, $user_id, $user_name, $rating, $comment)
    {
        $user_name = isset($user_name) && !empty($user_name) ? $user_name : 'Người dùng không xác định';
        $comment = isset($comment) ? $comment : '';
        $sql = self::$connection->prepare("
            INSERT INTO reviews (product_id, user_id, user_name, rating, comment, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $sql->bind_param("iisss", $product_id, $user_id, $user_name, $rating, $comment);
        $result = $sql->execute();
        return $result;
    }

    public function getFeaturedByCategory($categoryId, $page, $limit)
    {
        $start = ($page - 1) * $limit;
        $sql = self::$connection->prepare("SELECT * FROM products WHERE category_id = ? LIMIT ?, ?");
        $sql->bind_param("sii", $categoryId, $start, $limit);
        $sql->execute();
        $result = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        $sql->close();
        return $result;
    }public function getProductsByCategory($categoryId)
    {
        $sql = self::$connection->prepare("SELECT * FROM products WHERE category_id = ?");
        $sql->bind_param("i", $categoryId);
        $sql->execute();
        $items = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
        return $items;
    }
    
}
