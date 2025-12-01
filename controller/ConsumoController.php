<?php 

namespace Router;

require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../model/Consumo.php';
use database\Database;

class ConsumoController{
    private $consumo;

    public function __construct() {
        $database = new \Database\Database();
        $db = $database->getConnection();
        $this->consumo = new \Model\Consumo($db);
    }

    // Listar consumos
    public function listar() {
        try {
            $stmt = $this->consumo->read();
            include '../view/consumo/listar_consumos.php';
        } catch(Exception $e) {
            $_SESSION['erro'] = "Erro: " . $e->getMessage();
            header("Location: ../view/consumo/listar_consumos.php");
        }
    }
}

?>