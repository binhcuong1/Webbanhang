<?php

class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=my_store",
                                   $this->username,
                                   $this->password);
            $this->conn->exec("set names utf8");
        } 
        catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }

        return $this->conn;
    }
}

?>