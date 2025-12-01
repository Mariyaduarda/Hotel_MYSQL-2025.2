<?php 
namespace Router;

require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../model/Item.php';
require_once __DIR__ . '/../model/Endereco.php';
require_once __DIR__ . '/../model/Hospede.php';
require_once __DIR__ . '/../model/Reserva.php';
require_once __DIR__ . '/../controller/ItemController.php';
require_once __DIR__ . '/../controller/PagamentoController.php';
require_once __DIR__ . '/../controller/EnderecoController.php';
require_once __DIR__ . '/../controller/HospedeController.php';
require_once __DIR__ . '/../controller/ReservaController.php';
require_once __DIR__ . '/../controller/ConsumoController.php'; 
require_once __DIR__ . '/../controller/ItemConsumidoController.php';

// iniciar sessao para mensagens
session_start();

// pegar a acao e o controller da URL
$action = $_GET['action'] ?? 'listar';
$controller = $_GET['controller'] ?? 'item';

// instanciar o controller apropriado
switch($controller) {
    case 'item':
        $ctrl = new ItemController();
        break;
    case 'pagamento':
        $ctrl = new PagamentoController();
        break;
    case 'consumo':
        $ctrl = new ConsumoController();
        break;
    case 'hospede':
        $ctrl = new HospedeController();
        break;
    case 'reserva':
        $ctrl = new ReservaController();
        break;
    default:
        $_SESSION['erro'] = "Controller inválido.";
        header("Location: index.php");
        exit();
}

// chamar a ação apropriada
switch($action) {
    case 'listar':
        $ctrl->listar();
        break;
    case 'criar':
        $ctrl->criar();
        break;
    case 'salvar':
        $ctrl->salvar();
        break;
    case 'editar':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $ctrl->editar($id);
        } else {
            $_SESSION['erro'] = "ID inválido para edição.";
            header("Location: index.php?controller=$controller&action=listar");
        }
        break;
    case 'atualizar':
        $ctrl->atualizar();
        break;
    case 'deletar':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $ctrl->deletar($id);
        } else {
            $_SESSION['erro'] = "ID inválido para exclusão.";
            header("Location: index.php?controller=$controller&action=listar");
        }
        break;
    // acoes especificas de relatorios
    case 'relatorio_metodo_pagamento':
        $ctrl->relatorioMetodoPagamento();
        break;
    case 'relatorio_periodo':
        $ctrl->relatorioPorPeriodo();
        break;
    case 'relatorio_consumo_reserva':
        $ctrl->relatorioConsumoPorReserva();
        break;
    case 'relatorio_itens_consumidos':
        $ctrl->relatorioItensMaisConsumidos();
        break;
    case 'relatorio_vendas':
        $ctrl->relatorioVendas();
        break;
    default:
        $_SESSION['erro'] = "Ação não encontrada.";
        header("Location: index.php?controller=$controller&action=listar");
}

?>