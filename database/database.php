<?php

class Database {
    private $host = "",
    private $db_name = "",
    private $username = "",
    private $password = "",
    private $conn;
    
    public function getConnection(){
        $this->conn = null;

        try {
            $this->conn = new PDO
        }
    }
}
?>