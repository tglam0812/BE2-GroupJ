<?php
class Order extends Db
{
    public function getAdminOrders($status = '', $limit = 10, $offset = 0)
    {
        $sql = "SELECT o.*, u.name AS customer_name 
                FROM orders o
                JOIN users u ON o.user_id = u.id
                WHERE 1=1";

        if (!empty($status)) {
            $sql .= " AND o.status = ?";
        }

        $sql .= " ORDER BY o.order_date DESC LIMIT ? OFFSET ?";

        $stmt = self::$connection->prepare($sql);

        if (!empty($status)) {
            $stmt->bind_param("sii", $status, $limit, $offset);
        } else {
            $stmt->bind_param("ii", $limit, $offset);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotalAdminOrders($status = '')
    {
        $sql = "SELECT COUNT(*) AS total FROM orders WHERE 1=1";

        if (!empty($status)) {
            $sql .= " AND status = ?";
        }

        $stmt = self::$connection->prepare($sql);

        if (!empty($status)) {
            $stmt->bind_param("s", $status);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total'];
    }

    public function getOrderDetailsByAdmin($orderId)
    {
        $sql = "SELECT o.*, u.name AS customer_name, u.email AS customer_email
                FROM orders o
                JOIN users u ON o.user_id = u.id
                WHERE o.id = ?";

        $stmt = self::$connection->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateOrderStatus($orderId, $status)
    {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = self::$connection->prepare($sql);

        if ($stmt === false) {
            error_log("Prepare failed: " . self::$connection->error);
            return false;
        }

        $stmt->bind_param("si", $status, $orderId);

        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Execute failed: " . $stmt->error);
            return false;
        }
    }

    public function getOrderDetailByOrderId($orderId)
    {
        $sql = "SELECT 
                od.product_id,
                p.product_name AS product_name,
                p.image AS product_image,
                od.quantity,
                od.price
            FROM 
                order_details od
            JOIN 
                products p ON od.product_id = p.id
            WHERE 
                od.order_id = ?";

        $stmt = self::$connection->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();

        $orderDetails = [];
        while ($row = $result->fetch_assoc()) {
            $orderDetails[] = $row;
        }

        return $orderDetails;
    }
}
