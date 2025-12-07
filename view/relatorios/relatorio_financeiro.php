<?php
// ============================================================================
// RELATÓRIO FINANCEIRO - Resumo de Receitas e Dados Financeiros
// ============================================================================

// Importa o controlador de relatórios que fornece dados do banco de dados
require_once __DIR__ . '/../../controller/RelatorioController.php';
// Importa a classe utilitária para formatação de dados
require_once __DIR__ . '/../utils/Formatter.php';

// Define uso do namespace para o controlador
use Controller\RelatorioController;

// ============================================================================
// BUSCAR DADOS DO BANCO DE DADOS - RESUMO FINANCEIRO
// ============================================================================

// Instancia o controlador de relatórios de forma segura, verificando se a classe existe
$controller = class_exists('\Controller\\RelatorioController') ? new RelatorioController() : null;

// Array que armazenará os dados financeiros (receita_total, receita_periodo)
$finance = [];

// Verifica se o controlador foi instanciado e se possui o método desejado
if ($controller && method_exists($controller, 'financeiroResumo')) {
    // Chama o método para buscar resumo financeiro da empresa
    $res = $controller->financeiroResumo();
    // Extrai os dados se a chamada foi bem-sucedida
    $finance = $res['sucesso'] ? $res['dados'] : [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Configurações de Codificação e Viewport -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Financeiro</title>
    <!-- Bootstrap CSS para estilização responsiva -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Ícones do Bootstrap Icons para elementos visuais -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- CSS Customizado -->
    <style>
        /* Estilo para os cards de estatísticas financeiras */
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
        .stat-card-warning { border-color: #ffc107; }
        .stat-card-danger { border-color: #dc3545; }
        
        /* Destaque para valores financeiros */
        .financial-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #198754;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- ====================================================================== -->
        <!-- CABEÇALHO COM TÍTULO E BOTÕES DE AÇÃO -->
        <!-- ====================================================================== -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-cash-stack"></i> Relatório Financeiro</h2>
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
        <!-- CARDS DE RECEITA - RESUMO VISUAL -->
        <!-- ====================================================================== -->
        <div class="row mb-4">
            <!-- Card: Receita Total (Histórico Completo) -->
            <div class="col-md-6 mb-3">
                <div class="card stat-card stat-card-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Receita Total</p>
                                <p class="financial-value">R$ <?= number_format($finance['receita_total'] ?? 0, 2, ',', '.') ?></p>
                                <small class="text-muted">Histórico completo</small>
                            </div>
                            <div class="text-success">
                                <i class="bi bi-graph-up" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Receita do Período (Últimos 30 dias) -->
            <div class="col-md-6 mb-3">
                <div class="card stat-card stat-card-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Receita do Período</p>
                                <p class="financial-value" style="color: #ffc107;">R$ <?= number_format($finance['receita_periodo'] ?? 0, 2, ',', '.') ?></p>
                                <small class="text-muted">Últimos 30 dias</small>
                            </div>
                            <div class="text-warning">
                                <i class="bi bi-calendar-range" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ====================================================================== -->
        <!-- RESUMO ANALÍTICO -->
        <!-- ====================================================================== -->
        <div class="card">
            <!-- Cabeçalho do Card -->
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up-arrow"></i> Análise Financeira
                </h5>
            </div>
            <!-- Corpo do Card -->
            <div class="card-body">
                <!-- Tabela comparativa de receitas -->
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <!-- Cabeçalho da tabela -->
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50%;">Descrição</th>
                                <th class="text-end" style="width: 50%;">Valor</th>
                            </tr>
                        </thead>
                        <!-- Linhas da tabela -->
                        <tbody>
                            <!-- Linha: Receita Total -->
                            <tr class="border-top">
                                <td>
                                    <strong>Receita Total (Histórico)</strong>
                                    <div class="small text-muted">Somando todos os pagamentos registrados</div>
                                </td>
                                <td class="text-end">
                                    <strong class="financial-value">
                                        R$ <?= number_format($finance['receita_total'] ?? 0, 2, ',', '.') ?>
                                    </strong>
                                </td>
                            </tr>
                            <!-- Linha: Receita do Período (30 dias) -->
                            <tr>
                                <td>
                                    <strong>Receita do Período (30 dias)</strong>
                                    <div class="small text-muted">Pagamentos dos últimos 30 dias</div>
                                </td>
                                <td class="text-end">
                                    <strong class="financial-value" style="color: #ffc107;">
                                        R$ <?= number_format($finance['receita_periodo'] ?? 0, 2, ',', '.') ?>
                                    </strong>
                                </td>
                            </tr>
                            <!-- Linha: Média Diária -->
                            <tr class="border-bottom">
                                <td>
                                    <strong>Média Diária (30 dias)</strong>
                                    <div class="small text-muted">Receita do período ÷ 30 dias</div>
                                </td>
                                <td class="text-end">
                                    <strong class="financial-value" style="color: #0dcaf0;">
                                        R$ <?= number_format(($finance['receita_periodo'] ?? 0) / 30, 2, ',', '.') ?>
                                    </strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Nota informativa -->
                <div class="mt-4 p-3 bg-light rounded">
                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i> 
                        <strong>Observação:</strong> Este relatório apresenta um resumo das receitas registradas no sistema. 
                        Para análises mais detalhadas (por período, por tipo de serviço, etc.), 
                        consulte o departamento financeiro ou use ferramentas de BI avançadas.
                    </small>
                </div>
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
