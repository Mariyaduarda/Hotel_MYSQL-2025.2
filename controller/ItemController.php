<?php 
namespace Router;

require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../model/Item.php';

use database\Database;

class ItemController {
    private $item;
    private $db;

    public function __construct() {
        // Inicia a sessão se ainda não foi iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->db = new Database();
        $this->item = new \Item(); // Use \ para classe sem namespace
    }

    // Listar todos os itens
    public function listar() {
        try {
            $resultado = $this->item->listar();
            $itens = $resultado->fetchAll(\PDO::FETCH_ASSOC);
            include '../view/item/listar_itens.php';
        } catch(\Exception $e) {
            $_SESSION['erro'] = "Erro ao listar itens: " . $e->getMessage();
            header("Location: ../view/index.php");
            exit();
        }
    }

    // Mostrar formulário de criação
    public function criar() {
        include '../view/item/criar_item.php';
    }

    // Salvar novo item
    public function salvar() {
        try {
            $this->item->nome = $_POST['nome'];
            $this->item->descricao = $_POST['descricao'];
            $this->item->preco = $_POST['preco'];
            $this->item->tipo = $_POST['tipo'];

            if($this->item->criar()) {
                $_SESSION['sucesso'] = "Item criado com sucesso!";
            } else {
                $_SESSION['erro'] = "Erro ao criar item.";
            }
            
            header("Location: ../utils/router.php?controller=item&action=listar");
            exit();
        } catch(\Exception $e) {
            $_SESSION['erro'] = "Erro: " . $e->getMessage();
            header("Location: ../utils/router.php?controller=item&action=criar");
            exit();
        }
    }

    // Mostrar formulário de edição
    public function editar($id) {
        try {
            $this->item->id_item = $id;
            $resultado = $this->item->lerPorId();
            $item = $resultado->fetch(\PDO::FETCH_ASSOC);
            
            if($item) {
                include '../view/item/editar_item.php';
            } else {
                $_SESSION['erro'] = "Item não encontrado.";
                header("Location: ../utils/router.php?controller=item&action=listar");
                exit();
            }
        } catch(\Exception $e) {
            $_SESSION['erro'] = "Erro: " . $e->getMessage();
            header("Location: ../utils/router.php?controller=item&action=listar");
            exit();
        }
    }

    // Atualizar item
    public function atualizar() {
        try {
            $this->item->id_item = $_POST['id_item'];
            $this->item->nome = $_POST['nome'];
            $this->item->descricao = $_POST['descricao'];
            $this->item->preco = $_POST['preco'];
            $this->item->tipo = $_POST['tipo'];

            if($this->item->atualizar()) {
                $_SESSION['sucesso'] = "Item atualizado com sucesso!";
            } else {
                $_SESSION['erro'] = "Erro ao atualizar item.";
            }
            
            header("Location: ../utils/router.php?controller=item&action=listar");
            exit();
        } catch(\Exception $e) {
            $_SESSION['erro'] = "Erro: " . $e->getMessage();
            header("Location: ../utils/router.php?controller=item&action=editar&id=" . $_POST['id_item']);
            exit();
        }
    }

    // Deletar item
    public function deletar($id) {
        try {
            $this->item->id_item = $id;

            if($this->item->deletar()) {
                $_SESSION['sucesso'] = "Item deletado com sucesso!";
            } else {
                $_SESSION['erro'] = "Erro ao deletar item.";
            }
            
            header("Location: ../utils/router.php?controller=item&action=listar");
            exit();
        } catch(\Exception $e) {
            $_SESSION['erro'] = "Erro: " . $e->getMessage();
            header("Location: ../utils/router.php?controller=item&action=listar");
            exit();
        }
    }

    // Buscar itens por tipo
    public function buscarPorTipo($tipo) {
        try {
            $resultado = $this->item->buscarPorTipo($tipo);
            return $resultado->fetchAll(\PDO::FETCH_ASSOC);
        } catch(\Exception $e) {
            $_SESSION['erro'] = "Erro ao buscar itens: " . $e->getMessage();
            return [];
        }
    }
}
?>