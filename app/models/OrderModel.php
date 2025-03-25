<?php
class OrderModel {
    private $conn;
    private $table_name = "orders";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả đơn hàng (cho admin)
    public function getAllOrders($phone = '', $date = '') {
        $query = "
            SELECT o.id, o.name, o.phone, o.address, o.created_at, o.user_id,
                   SUM(od.quantity * od.price) as total_amount, 
                   COALESCE(u.username, 'Không xác định') as username
            FROM " . $this->table_name . " o
            LEFT JOIN order_details od ON o.id = od.order_id
            LEFT JOIN users u ON u.id = o.user_id
            WHERE 1=1
        ";
        $params = [];
    
        // Lọc theo số điện thoại
        if (!empty($phone)) {
            $query .= " AND o.phone LIKE :phone";
            $params[':phone'] = "%$phone%";
        }
    
        // Lọc theo ngày mua hàng
        if (!empty($date)) {
            $query .= " AND DATE(o.created_at) = :date";
            $params[':date'] = $date;
        }
    
        $query .= " GROUP BY o.id ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($query);
    
        // Bind các tham số
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getOrderHistory($user_id) {
        $query = "
            SELECT o.id, o.name, o.phone, o.address, o.created_at,
                   SUM(od.quantity * od.price) as total_amount
            FROM " . $this->table_name . " o
            LEFT JOIN order_details od ON o.id = od.order_id
            WHERE o.user_id = :user_id
            GROUP BY o.id
            ORDER BY o.created_at DESC
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getOrderDetails($order_id) {
        $query = "
            SELECT od.product_id, p.name, od.quantity, od.price
            FROM order_details od
            JOIN product p ON od.product_id = p.id
            WHERE od.order_id = :order_id
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Phương thức mới: Lấy đơn hàng ngẫu nhiên của người dùng khác
    public function getRandomOrders($exclude_user_id, $limit = 3) {
        $query = "
            SELECT o.id, o.name, o.phone, o.address, o.created_at,
                   SUM(od.quantity * od.price) as total_amount
            FROM " . $this->table_name . " o
            LEFT JOIN order_details od ON o.id = od.order_id
            WHERE o.user_id != :exclude_user_id
            GROUP BY o.id
            ORDER BY RAND()
            LIMIT :limit
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':exclude_user_id', $exclude_user_id, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}