<?php

namespace Router;

require_once __DIR__ . '/../model/Pagamento.php';
require_once __DIR__ . '/../database/Database.php';

use database\Database;
use Router\Pagamento;
$db = new Database(); 


class PagamentoController {
    private $pagamento;

    public function __construct() {
        $this->pagamento = \new Pagamento();
    }

    // Listar todos os pagamentos
    public function listar() {
        try {
            $resultado = $this->pagamento->listar();
            $pagamentos = $resultado->fetchAll(PDO::FETCH_ASSOC);
            
            include '../view/pagamento/listar_pagamentos.php';
        } catch(Exception $e) {
            $_SESSION['erro'] = "Erro ao listar pagamentos: " . $e->getMessage();
            header("Location: ../view/pagamento/listar_pagamentos.php");
        }
    }

    // Exibir formulário de cadastro
    public function criar() {
        include '../view/pagamento/cadastrar_pagamento.php';
    }

    // Processar cadastro
    public function salvar() {
        try {
            $this->pagamento->data_pagamento = $_POST['data_pagamento'];
            $this->pagamento->valor_total = $_POST['valor_total'];
            $this->pagamento->metodo_pagamento = $_POST['metodo_pagamento'];
            $this->pagamento->reserva_idreserva = $_POST['reserva_idreserva'];
            $this->pagamento->endereco = $_POST['endereco'] ?? null;
            $this->pagamento->data_nascimento = $_POST['data_nascimento'] ?? null;

            if($this->pagamento->criar()) {
                $_SESSION['sucesso'] = "Pagamento cadastrado com sucesso!";
                header("Location: ../view/pagamento/listar_pagamentos.php");
            } else {
                $_SESSION['erro'] = "Erro ao cadastrar pagamento.";
                header("Location: ../view/pagamento/cadastrar_pagamento.php");
            }
        } catch(Exception $e) {
            $_SESSION['erro'] = "Erro: " . $e->getMessage();
            header("Location: ../view/pagamento/cadastrar_pagamento.php");
        }
    }

    // Exibir formulário de edição
    public function editar($id) {
        try {
            if($this->pagamento->buscarPorId($id)) {
                include '../view/pagamento/editar_pagamento.php';
            } else {
                $_SESSION['erro'] = "Pagamento não encontrado.";
                header("Location: ../view/pagamento/listar_pagamentos.php");
            }
        } catch(Exception $e) {
            $_SESSION['erro'] = "Erro: " . $e->getMessage();
            header("Location: ../view/pagamento/listar_pagamentos.php");
        }
    }

    // Processar atualização
    public function atualizar() {
        try {
            $this->pagamento->id_pagamento = $_POST['id_pagamento'];
            $this->pagamento->data_pagamento = $_POST['data_pagamento'];
            $this->pagamento->valor_total = $_POST['valor_total'];
            $this->pagamento->metodo_pagamento = $_POST['metodo_pagamento'];
            $this->pagamento->endereco = $_POST['endereco'] ?? null;
            $this->pagamento->data_nascimento = $_POST['data_nascimento'] ?? null;

            if($this->pagamento->atualizar()) {
                $_SESSION['sucesso'] = "Pagamento atualizado com sucesso!";
                header("Location: ../view/pagamento/listar_pagamentos.php");
            } else {
                $_SESSION['erro'] = "Erro ao atualizar pagamento.";
                header("Location: ../view/pagamento/editar_pagamento.php?id=" . $_POST['id_pagamento']);
            }
        } catch(Exception $e) {
            $_SESSION['erro'] = "Erro: " . $e->getMessage();
            header("Location: ../view/pagamento/editar_pagamento.php?id=" . $_POST['id_pagamento']);
        }
    }

    // Deletar pagamento
    public function deletar($id) {
        try {
            $this->pagamento->id_pagamento = $id;
            
            if($this->pagamento->deletar()) {
                $_SESSION['sucesso'] = "Pagamento deletado com sucesso!";
            } else {
                $_SESSION['erro'] = "Erro ao deletar pagamento.";
            }
            header("Location: ../view/pagamento/listar_pagamentos.php");
        } catch(Exception $e) {
            $_SESSION['erro'] = "Erro: " . $e->getMessage();
            header("Location: ../view/pagamento/listar_pagamentos.php");
        }
    }

    // Relatório por método de pagamento
    public function relatorioMetodoPagamento() {
        try {
            $resultado = $this->pagamento->getRelatorioMetodoPagamento();
            $dados = $resultado->fetchAll(PDO::FETCH_ASSOC);
            
            include '../view/relatorios/metodo_pagamento.php';
        } catch(Exception $e) {
            $_SESSION['erro'] = "Erro ao gerar relatório: " . $e->getMessage();
            header("Location: ../view/relatorios/index.php");
        }
    }

    // Relatório por período
    public function relatorioPorPeriodo() {
        try {
            $data_inicio = $_GET['data_inicio'] ?? date('Y-m-01');
            $data_fim = $_GET['data_fim'] ?? date('Y-m-d');
            
            $resultado = $this->pagamento->getRelatorioPorPeriodo($data_inicio, $data_fim);
            $dados = $resultado->fetchAll(PDO::FETCH_ASSOC);
            
            include '../view/relatorios/pagamento_periodo.php';
        } catch(Exception $e) {
            $_SESSION['erro'] = "Erro ao gerar relatório: " . $e->getMessage();
            header("Location: ../view/relatorios/index.php");
        }
    }
}
?>