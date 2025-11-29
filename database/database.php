<?php

// classe de conexão com o banco de dados
class Database {
    private $host = "localhost";
    private $db_name = "hotel";
    private $username = "admin";
    private $password = "bd2025";
    private $conn;
    
    public function getConnection(){
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $excep){
            echo "Erro ao conectar: " . $excep->getMessage();
        }
        return $this->conn;
    }
}
?>