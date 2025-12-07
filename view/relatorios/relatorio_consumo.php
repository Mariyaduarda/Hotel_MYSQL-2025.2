<?php
// ============================================================================
// RELATÓRIO DE CONSUMOS - Itens Consumidos por Hóspedes/Reservas
// ============================================================================

// Importa o controlador de relatórios que fornece dados do banco de dados
require_once __DIR__ . '/../../controller/RelatorioController.php';
// Importa a classe utilitária para formatação de dados (datas, valores, etc)
require_once __DIR__ . '/../utils/Formatter.php';

// Define uso do namespace para o controlador
use Controller\RelatorioController;

// ============================================================================
// BUSCAR DADOS DO BANCO DE DADOS - LISTA DE CONSUMOS RECENTES
// ============================================================================

// Instancia o controlador de relatórios de forma segura, verificando se a classe existe
$controller = class_exists('\Controller\\RelatorioController') ? new RelatorioController() : null;

// Array que armazenará a lista de consumos
$consumos = [];
// Variável para armazenar o total de consumos
$total_consumos = 0;

// Verifica se o controlador foi instanciado e se possui o método desejado
if ($controller && method_exists($controller, 'consumosRecentes')) {
    // Chama o método para buscar consumos recentes (últimos 100)
    $res = $controller->consumosRecentes();
    // Extrai os dados se a chamada foi bem-sucedida
    $consumos = $res['sucesso'] ? $res['dados'] : [];
    
    // Calcula o total de consumos (soma de todos os valores)
    foreach ($consumos as $c) {
        $total_consumos += ($c['valor_consumacao'] ?? 0);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Configurações de Codificação e Viewport -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Consumos</title>
    <!-- Bootstrap CSS para estilização responsiva -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Ícones do Bootstrap Icons para elementos visuais -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- CSS Customizado -->
    <style>
        /* Estilo para os cards de resumo -->
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .stat-card-primary { border-color: #0d6efd; }
        .stat-card-success { border-color: #198754; }
        .stat-card-info { border-color: #0dcaf0; }
        
        /* Destaque para linhas da tabela */
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- ====================================================================== -->
        <!-- CABEÇALHO COM TÍTULO E BOTÕES DE AÇÃO -->
        <!-- ====================================================================== -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-basket3"></i> Relatório de Consumos</h2>
            <div>
                <!-- Botão para imprimir a página atual -->
                <button onclick="window.print()" class="btn btn-outline-primary">
                    <i class="bi bi-printer"></i> Imprimir
                </button>
                <!-- Link para voltar ao índice de relatórios -->
                <a href="index.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
        </div>

        <!-- ====================================================================== -->
        <!-- CARDS DE RESUMO - TOTAIS E ESTATÍSTICAS -->
        <!-- ====================================================================== -->
        <div class="row mb-4">
            <!-- Card: Total de Consumos Registrados -->
            <div class="col-md-6 mb-3">
                <div class="card stat-card stat-card-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Total de Consumos</p>
                                <h3 class="mb-0"><?= count($consumos) ?></h3>
                            </div>
                            <div class="text-primary">
                                <i class="bi bi-list-check" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Valor Total de Consumos -->
            <div class="col-md-6 mb-3">
                <div class="card stat-card stat-card-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Valor Total Consumido</p>
                                <h3 class="mb-0">R$ <?= number_format($total_consumos, 2, ',', '.') ?></h3>
                            </div>
                            <div class="text-success">
                                <i class="bi bi-currency-dollar" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ====================================================================== -->
        <!-- SEÇÃO DE LISTAGEM DE CONSUMOS RECENTES -->
        <!-- ====================================================================== -->
        <div class="card">
            <!-- Cabeçalho do Card -->
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-basket3-fill"></i> Consumos Recentes
                </h5>
            </div>
            <!-- Corpo do Card -->
            <div class="card-body">
                <?php if (empty($consumos)): ?>
                    <!-- Mensagem quando não há consumos registrados -->
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle"></i> 
                        Nenhum consumo registrado recentemente.
                    </div>
                <?php else: ?>
                    <!-- Tabela responsiva com consumos -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <!-- Cabeçalho da tabela -->
                            <thead class="table-light">
                                <tr>
                                    <th><i class="bi bi-hash"></i> ID Consumo</th>
                                    <th><i class="bi bi-file-earmark"></i> Reserva ID</th>
                                    <th><i class="bi bi-calendar-event"></i> Data do Consumo</th>
                                    <th class="text-end"><i class="bi bi-currency-dollar"></i> Valor</th>
                                </tr>
                            </thead>
                            <!-- Linhas da tabela com dados dos consumos -->
                            <tbody>
                                <?php foreach ($consumos as $c): ?>
                                    <tr>
                                        <!-- ID do Consumo -->
                                        <td>
                                            <strong><?= $c['id_consumo'] ?? '-' ?></strong>
                                        </td>
                                        <!-- ID da Reserva Associada -->
                                        <td>
                                            <span class="badge bg-info">
                                                <?= $c['idreserva'] ?? '-' ?>
                                            </span>
                                        </td>
                                        <!-- Data do Consumo Formatada -->
                                        <td>
                                            <?= Formatter::formatarData($c['data_consumo'] ?? null) ?>
                                        </td>
                                        <!-- Valor do Consumo Formatado em Real -->
                                        <td class="text-end">
                                            <strong class="text-success">
                                                R$ <?= number_format($c['valor_consumacao'] ?? 0, 2, ',', '.') ?>
                                            </strong>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Nota informando quantidade de registros e total -->
                    <div class="mt-3 text-muted">
                        <small>
                            <i class="bi bi-info-circle"></i> 
                            Exibindo <?= count($consumos) ?> consumo(s). 
                            Valor total: R$ <?= number_format($total_consumos, 2, ',', '.') ?>
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Importa Bootstrap JavaScript para interatividade de componentes -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Estilos CSS específicos para impressão -->
    <style media="print">
        /* Garante que cores de fundo sejam impressas corretamente -->
        .btn, .card-header {
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }
        /* Esconde elementos que não devem aparecer na impressão -->
        .no-print {
            display: none !important;
        }
    </style>
</body>
</html>
