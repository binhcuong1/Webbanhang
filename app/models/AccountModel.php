<?php
class AccountModel {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAccountByUsername($username) {
        $query = "SELECT * 
                  FROM " . $this->table_name . " WHERE username = :username";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }   

    public function getAccountById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    function save($username, $name, $password) {
        $query = "INSERT INTO " . $this->table_name . "(username, fullname, password, role)
                  VALUES (:username, :fullname, :password, 'customer')";
        $stmt = $this->conn->prepare($query);

        $name = htmlspecialchars(strip_tags($name));
        $username = htmlspecialchars(strip_tags($username));

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':fullname', $name);
        $stmt->bindParam(':password', $password);

        return $stmt->execute();
    }

    public function updateAccount($id, $data) {
        $setClause = [];
        $params = [':id' => $id];

        if (isset($data['fullname'])) {
            $setClause[] = "fullname = :fullname";
            $params[':fullname'] = htmlspecialchars(strip_tags($data['fullname']));
        }
        if (isset($data['password'])) {
            $setClause[] = "password = :password";
            $params[':password'] = $data['password'];
        }

        if (empty($setClause)) return false;

        $query = "UPDATE " . $this->table_name . " SET " . implode(', ', $setClause) . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        return $stmt->execute();
    }
}