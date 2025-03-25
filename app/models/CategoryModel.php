<?php 
class CategoryModel {
    private $conn;
    private $table_name = "category";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCategories() {
        $query = "
            SELECT *
            FROM ". $this->table_name ." c
            WHERE description NOT LIKE 'deleted'
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $result;
    }

    public function getCategoryByID($id) {
        $query = "
            SELECT *
            FROM ". $this->table_name ." c
            WHERE c.id = :id 
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        return $result;
    }

    public function getTotalCategories() {
        $query = "
            SELECT COUNT(*) 
            FROM category
            WHERE description NOT LIKE 'deleted'
            ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function addCategory($name, $description) {
        $error = [];

        if (empty($name)) $error['name'] = 'Tên danh mục không được để trống';
        if (empty($description)) $error['description'] = 'Mô tả không được để trống';

        $query = "
            INSERT INTO ". $this->table_name ." (name, description)
            VALUES (:name, :description);
        ";
        $stmt = $this->conn->prepare($query);
        
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        
        if ($stmt->execute()) return true;

        return false;
    }

    public function updateCategory($id, $name, $description) {
        $query = "
            UPDATE " . $this->table_name . " 
            SET name = :name, description = :description
            WHERE id = :id
            ";

        $stmt = $this->conn->prepare($query);

        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteCategory($id) {
        $query = "
            UPDATE " . $this->table_name . " 
            SET description = 'deleted'
            WHERE id = :id
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        if ($stmt->execute()) return true;
        
        return false;
    }
}
?>