<?php

namespace Router;

require_once __DIR__ . '/../utils/Validacoes.php';
require_once __DIR__ . '/../utils/Formatter.php';
require_once __DIR__ . '/../database/Database.php';   


class ItemConsumido {
    private $conn;
    private $table_name = "item_has_consumo";

    // Propriedades
    public $item_id_item;
    public $consumo_id_consumo;
    public $quantidade;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // ========================================
    // MÉTODOS CRUD
    // ========================================

    // CREATE - Adicionar item ao consumo
    public function criar() {
        $query = "INSERT INTO {$this->table_name} 
                (item_id_item, consumo_id_consumo, quantidade)
                VALUES 
                (:item_id_item, :consumo_id_consumo, :quantidade)";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar
        $this->item_id_item = htmlspecialchars(strip_tags($this->item_id_item));
        $this->consumo_id_consumo = htmlspecialchars(strip_tags($this->consumo_id_consumo));
        $this->quantidade = htmlspecialchars(strip_tags($this->quantidade));
        
        // Bind
        $stmt->bindParam(':item_id_item', $this->item_id_item);
        $stmt->bindParam(':consumo_id_consumo', $this->consumo_id_consumo);
        $stmt->bindParam(':quantidade', $this->quantidade);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // READ - Listar itens de um consumo específico
    public function listarPorConsumo($consumo_id) {
        $query = "SELECT 
                    ic.*,
                    i.nome,
                    i.valor,
                    i.descricao,
                    (ic.quantidade * i.valor) as valor_total
                FROM {$this->table_name} ic
                INNER JOIN item i ON ic.item_id_item = i.id_item
                WHERE ic.consumo_id_consumo = :consumo_id
                ORDER BY i.nome";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':consumo_id', $consumo_id);
        $stmt->execute();
        return $stmt;
    }

    // READ - Buscar item consumido específico
    public function buscar($item_id, $consumo_id) {
        $query = "SELECT * FROM {$this->table_name} 
                WHERE item_id_item = :item_id 
                AND consumo_id_consumo = :consumo_id 
                LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->bindParam(':consumo_id', $consumo_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->item_id_item = $row['item_id_item'];
            $this->consumo_id_consumo = $row['consumo_id_consumo'];
            $this->quantidade = $row['quantidade'];
            return true;
        }
        return false;
    }

    // UPDATE - Atualizar quantidade
    public function atualizar() {
        $query = "UPDATE {$this->table_name} SET 
                quantidade = :quantidade
                WHERE item_id_item = :item_id 
                AND consumo_id_consumo = :consumo_id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar
        $this->item_id_item = htmlspecialchars(strip_tags($this->item_id_item));
        $this->consumo_id_consumo = htmlspecialchars(strip_tags($this->consumo_id_consumo));
        $this->quantidade = htmlspecialchars(strip_tags($this->quantidade));
        
        // Bind
        $stmt->bindParam(':item_id', $this->item_id_item);
        $stmt->bindParam(':consumo_id', $this->consumo_id_consumo);
        $stmt->bindParam(':quantidade', $this->quantidade);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE - Remover item do consumo
    public function deletar() {
        $query = "DELETE FROM {$this->table_name} 
                WHERE item_id_item = :item_id 
                AND consumo_id_consumo = :consumo_id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->item_id_item = htmlspecialchars(strip_tags($this->item_id_item));
        $this->consumo_id_consumo = htmlspecialchars(strip_tags($this->consumo_id_consumo));
        
        $stmt->bindParam(':item_id', $this->item_id_item);
        $stmt->bindParam(':consumo_id', $this->consumo_id_consumo);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE - Remover todos os itens de um consumo
    public function deletarPorConsumo($consumo_id) {
        $query = "DELETE FROM {$this->table_name} WHERE consumo_id_consumo = :consumo_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':consumo_id', $consumo_id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>