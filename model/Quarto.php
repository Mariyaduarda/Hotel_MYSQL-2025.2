<?php

// classe quarto
class Quarto {
    private $conn;
    private string $table_name = "quartos";

    private int    $id;
    private string $numero_quarto;
    private string $tipo;
    private float  $preco_diaria;
    private int    $capacidade;
    private string $descricao;
    private string $status;
    private string $data_criacao;

    public function __construct($db){
        $this->conn = $db;
    }

    // cria novo quarto
    public function create(){
        $query = "INSERT INTO " . $this->table_name . " 
                  (numero_quarto, tipo, preco_diaria, capacidade, descricao, status, data_criacao) 
                  VALUES (:numero_quarto, :tipo, :preco_diaria, :capacidade, :descricao, :status, :data_criacao)";

        $stmt = $this->conn->prepare($query);

        // inicializa os parâmetros para previnir lixo de dados
        $stmt->numero_quarto = htmlspecialchars(strip_tags($this->numero_quarto));
        $stmt->tipo = htmlspecialchars(strip_tags($this->tipo));   
        $stmt->preco_diaria = htmlspecialchars(strip_tags($this->preco_diaria));
        $stmt->capacidade = htmlspecialchars(strip_tags($this->capacidade));
        $stmt->descricao = htmlspecialchars(strip_tags($this->descricao));
        $stmt->status = htmlspecialchars(strip_tags($this->status));
        $stmt->data_criacao = htmlspecialchars(strip_tags($this->data_criacao));
    
        // blind os valores - vincula os variáveis aos parâmetros   
        $stmt->bindParam(":numero_quarto", $this->numero_quarto);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":preco_diaria", $this->preco_diaria);
        $stmt->bindParam(":capacidade", $this->capacidade);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":data_criacao", $this->data_criacao);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
    // le todos os quartos
    public function read(){
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY numero_quarto DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
    // le um unico quarto
    public function readOne(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row){
            $this->numero_quarto = $row['numero_quarto'];
            $this->tipo = $row['tipo'];
            $this->preco_diaria = $row['preco_diaria'];
            $this->capacidade = $row['capacidade'];
            $this->descricao = $row['descricao'];
            $this->status = $row['status'];
            $this->data_criacao = $row['data_criacao'];
            return true;
        }
        return false;
    }

    // atualiza um quarto
    public function update(){
        $query = "UPDATE " . $this->table_name . "
                  SET numero_quarto = :numero_quarto,
                      tipo = :tipo,
                      preco_diaria = :preco_diaria,
                      capacidade = :capacidade,
                      descricao = :descricao,
                      status = :status
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        // inicializa os parâmetros para previnir lixo de dados
        $stmt->numero_quarto = htmlspecialchars(strip_tags($this->numero_quarto));
        $stmt->tipo = htmlspecialchars(strip_tags($this->tipo));
        $stmt->preco_diaria = htmlspecialchars(strip_tags($this->preco_diaria));
        $stmt->capacidade = htmlspecialchars(strip_tags($this->capacidade));
        $stmt->descricao = htmlspecialchars(strip_tags($this->descricao));
        $stmt->status = htmlspecialchars(strip_tags($this->status));
        $stmt->id = htmlspecialchars(strip_tags($this->id));

        // blind os valores - vincula os variáveis aos parâmetros
        $stmt->bindParam(":numero_quarto", $this->numero_quarto);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":preco_diaria", $this->preco_diaria);
        $stmt->bindParam(":capacidade", $this->capacidade);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    // deleta um quarto verificando se sta disponivel
    public function delete(){
         if($this->verificarDisponibilidade()){
            return false;
         }
         $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
         $stmt = $this->conn->prepare($query);
         $this->id = htmlspecialchars(strip_tags($this->id));
         $stmt->bindParam(":id", $this->id);

         if($stmt->execute()){
            return true;
         }
         return false;
    }

    // verifica se o quarto está reservado
    private function veritificarDisponibilidade(){
    
        $query = "SELECT COUNT(*) as total 
        FROM reserva 
        WHERE quarto_id = :id 
        AND (status = 'reservado' OR status = 'pendente');
        AND data_checkout >= CURDATE()";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row['total'] > 0){
            return true;
        }
        return false;
    }

    public function listarDisponiveisPorPeriodo($data_inicio, $data_fim) {
        $query = "SELECT q.* FROM " . $this->table_name . " q 
                  WHERE q.status = 'disponivel' AND q.id NOT IN (
                      SELECT quarto_id FROM reservas 
                      WHERE status != 'cancelada' 
                      AND (data_checkin <= :data_fim AND data_checkout >= :data_inicio)
                  )";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>