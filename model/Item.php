
<?php

require_once __DIR__ . '/../utils/Validacoes.php';
require_once __DIR__ . '/../utils/Formatter.php';
require_once __DIR__ . '/../database/Database.php';   

class Item {
    private $conn;
    private $table_name = "item";

    // Propriedades
    public $id_item;
    public $nome;
    public $valor;
    public $descricao;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // ========================================
    // MÉTODOS CRUD
    // ========================================

    // CREATE - Criar novo item
    public function criar() {
        $query = "INSERT INTO {$this->table_name} 
                (nome, valor, descricao)
                VALUES 
                (:nome, :valor, :descricao)";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->valor = htmlspecialchars(strip_tags($this->valor));
        $this->descricao = htmlspecialchars(strip_tags($this->descricao));
        
        // Bind
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':valor', $this->valor);
        $stmt->bindParam(':descricao', $this->descricao);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // READ - Listar todos os itens
    public function listar() {
        $query = "SELECT * FROM {$this->table_name} ORDER BY nome";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // READ - Buscar item por ID
    public function buscarPorId($id) {
        $query = "SELECT * FROM {$this->table_name} WHERE id_item = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->id_item = $row['id_item'];
            $this->nome = $row['nome'];
            $this->valor = $row['valor'];
            $this->descricao = $row['descricao'];
            return true;
        }
        return false;
    }

    // UPDATE - Atualizar item
    public function atualizar() {
        $query = "UPDATE {$this->table_name} SET 
                nome = :nome,
                valor = :valor,
                descricao = :descricao
                WHERE id_item = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar
        $this->id_item = htmlspecialchars(strip_tags($this->id_item));
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->valor = htmlspecialchars(strip_tags($this->valor));
        $this->descricao = htmlspecialchars(strip_tags($this->descricao));
        
        // Bind
        $stmt->bindParam(':id', $this->id_item);
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':valor', $this->valor);
        $stmt->bindParam(':descricao', $this->descricao);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE - Deletar item
    public function deletar() {
        $query = "DELETE FROM {$this->table_name} WHERE id_item = :id";
        $stmt = $this->conn->prepare($query);
        
        $this->id_item = htmlspecialchars(strip_tags($this->id_item));
        $stmt->bindParam(':id', $this->id_item);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // ========================================
    // MÉTODOS DE RELATÓRIOS
    // ========================================

    // CONSULTA 12: RELATÓRIO DE ITENS MAIS CONSUMIDOS (SUBQUERY)
    // Lista itens com consumo acima da média, mostrando quantidade total vendida
    public function getItensMaisConsumidos() {
        $query = "SELECT 
                    i.nome,
                    i.valor,
                    i.descricao,
                    (SELECT SUM(ic.quantidade) 
                     FROM item_has_consumo ic 
                     WHERE ic.item_id_item = i.id_item) AS quantidade_total_vendida,
                    (SELECT COUNT(DISTINCT ic.consumo_id_consumo) 
                     FROM item_has_consumo ic 
                     WHERE ic.item_id_item = i.id_item) AS numero_vendas,
                    (SELECT SUM(ic.quantidade * i.valor) 
                     FROM item_has_consumo ic 
                     WHERE ic.item_id_item = i.id_item) AS faturamento_total_item
                FROM {$this->table_name} i
                WHERE (SELECT SUM(ic.quantidade) 
                       FROM item_has_consumo ic 
                       WHERE ic.item_id_item = i.id_item) > 
                      (SELECT AVG(total_item) FROM 
                          (SELECT SUM(quantidade) AS total_item 
                           FROM item_has_consumo 
                           GROUP BY item_id_item) AS totais)
                ORDER BY quantidade_total_vendida DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

     // Relatório de todos os itens com estatísticas de venda
    public function getRelatorioVendas() {
        $query = "SELECT 
                    i.*,
                    COALESCE(SUM(ic.quantidade), 0) as total_vendido,
                    COALESCE(COUNT(DISTINCT ic.consumo_id_consumo), 0) as total_vendas,
                    COALESCE(SUM(ic.quantidade * i.valor), 0) as faturamento_total
                FROM {$this->table_name} i
                LEFT JOIN item_has_consumo ic ON i.id_item = ic.item_id_item
                GROUP BY i.id_item
                ORDER BY faturamento_total DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>