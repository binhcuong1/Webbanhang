<?php
require_once 'app/config/Database.php';

class RoleLogModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Ghi log thay đổi vai trò
    public function logRoleChange($adminId, $userId, $oldRole, $newRole) {
        $query = "INSERT INTO role_logs (admin_id, user_id, old_role, new_role) VALUES (:admin_id, :user_id, :old_role, :new_role)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':admin_id', $adminId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':old_role', $oldRole);
        $stmt->bindParam(':new_role', $newRole);
        $stmt->execute();
    }

    // Lấy lịch sử thay đổi vai trò
    public function getRoleLogs() {
        $query = "SELECT rl.*, u1.username as admin_name, u2.username as user_name 
                  FROM role_logs rl 
                  JOIN users u1 ON rl.admin_id = u1.id 
                  JOIN users u2 ON rl.user_id = u2.id 
                  ORDER BY rl.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}