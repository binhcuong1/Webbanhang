<?php
class OrderModel {
    private $conn;
    private $table_name = "orders";

    public function __construct($db) {
        $this->conn = $db;
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
}