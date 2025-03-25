<?php

class ProductModel {
    private $conn;
    private $table_name = "product";

    public function __construct($db) {
        $this->conn = $db;
    }
    
    #region CRUD
    public function getProducts() {
        $query = "
            SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name
            FROM " . $this->table_name . " p
            LEFT JOIN category c ON c.id = p.category_id
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $result;
    }

    public function getProductByID($id) {
        $query = "
            SELECT p.*, c.name as category_name
            FROM ". $this->table_name ." p
            LEFT JOIN category c ON c.id = p.category_id
            WHERE p.id = :id 
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        return $result;
    }

    public function addProduct($name, $description, $price, $category_id, $image) {
        $error = [];
        $isPrice = !is_numeric($price) || $price <= 0;

        if (empty($name)) $error['name'] = 'Tên sản phẩm không được để trống';
        if (empty($description)) $error['description'] = 'Mô tả không được để trống';
        if ($isPrice) $error['price'] = 'Giá sản phẩm không hợp lệ';

        if (count($error) > 0)
            return $error;

        $query = "
            INSERT INTO ". $this->table_name ." (name, description, price, category_id, image)
            VALUES (:name, :description, :price, :category_id, :image);
        ";
        $stmt = $this->conn->prepare($query);
        
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));
        $image = htmlspecialchars(strip_tags($image));

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':image', $image);
        
        if ($stmt->execute()) return true;

        return false;
    }

    public function updateProduct($id, $name, $description, $price, $category_id, $image) {
        $query = "
            UPDATE " . $this->table_name . " 
            SET name = :name, description = :description, price = :price, category_id = :category_id, image=:image
            WHERE id = :id
            ";

        $stmt = $this->conn->prepare($query);

        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));
        $image = htmlspecialchars(strip_tags($image));

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':image', $image);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteProduct($id) {
        $query = "
            DELETE FROM ". $this->table_name ."
            WHERE id = :id
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        if ($stmt->execute()) return true;
        
        return false;
    }

    public function searchProducts($keyword) {
        $query = "
            SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name
            FROM " . $this->table_name . " p
            LEFT JOIN category c ON c.id = p.category_id
            WHERE p.name LIKE :keyword OR p.description LIKE :keyword
        ";
        $stmt = $this->conn->prepare($query);
        $keyword = "%" . $keyword . "%";
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $result;
    }
    #endregion

    public function getProductsPaginated($page = 1, $perPage = 5) {
        $offset = ($page - 1) * $perPage;
        $query = "
            SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name
            FROM " . $this->table_name . " p
            LEFT JOIN category c ON c.id = p.category_id
            LIMIT :limit OFFSET :offset
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getTotalProducts() {
        $query = "SELECT COUNT(*) FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    public function countSearchProducts($keyword) {
        $query = "
            SELECT COUNT(*) 
            FROM " . $this->table_name . " p
            WHERE p.name LIKE :keyword OR p.description LIKE :keyword
        ";
        $stmt = $this->conn->prepare($query);
        $keyword = "%" . $keyword . "%";
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    public function searchProductsPaginated($keyword, $page = 1, $perPage = 5) {
        $offset = ($page - 1) * $perPage;
        $query = "
            SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name
            FROM " . $this->table_name . " p
            LEFT JOIN category c ON c.id = p.category_id
            WHERE p.name LIKE :keyword OR p.description LIKE :keyword
            LIMIT :limit OFFSET :offset
        ";
        $stmt = $this->conn->prepare($query);
        $keyword = "%" . $keyword . "%";
        $stmt->bindParam(':keyword', $keyword);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Lấy sản phẩm liên quan (cùng danh mục, trừ sản phẩm hiện tại)
    public function getRelatedProducts($category_id, $exclude_id, $limit = 4) {
        $query = "SELECT p.*, c.name as category_name 
                  FROM product p 
                  LEFT JOIN category c ON p.category_id = c.id 
                  WHERE p.category_id = :category_id AND p.id != :exclude_id 
                  LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':exclude_id', $exclude_id, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}

?>