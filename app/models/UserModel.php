<?php
require_once 'app/config/Database.php';

class UserModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Lấy tất cả người dùng
    public function getAllUsers() {
        $query = "SELECT * FROM users";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getTotalUsers() {
        $query = "SELECT COUNT(*) FROM users";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Lấy thông tin người dùng theo ID
    public function getUserById($userId) {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Cập nhật vai trò của người dùng
    public function updateRole($userId, $newRole) {
        $query = "UPDATE users SET role = :role WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':role', $newRole);
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }
}