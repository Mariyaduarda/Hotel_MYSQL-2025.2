<?php
// ============================================================================
// RELATÓRIO DE QUARTOS - Estatísticas de Ocupação e Disponibilidade
// ============================================================================

// Importa o controlador de relatórios que fornece dados do banco de dados
require_once __DIR__ . '/../../controller/RelatorioController.php';
// Importa a classe utilitária para formatação de dados (datas, telefones, etc)
require_once __DIR__ . '/../utils/Formatter.php';

// Define uso do namespace para o controlador
use Controller\RelatorioController;

// ============================================================================
// BUSCAR DADOS DO BANCO DE DADOS - ESTATÍSTICAS DE QUARTOS
// ============================================================================

// Instancia o controlador de relatórios de forma segura, verificando se a classe existe
$controller = class_exists('\Controller\\RelatorioController') ? new RelatorioController() : null;

// Array que armazenará as estatísticas: total_quartos, ocupados, disponiveis
$stats = [];

// Verifica se o controlador foi instanciado e se possui o método desejado
if ($controller && method_exists($controller, 'quartosEstatisticas')) {
    // Chama o método para buscar estatísticas dos quartos (ocupação de hoje)
    $result = $controller->quartosEstatisticas();
    // Extrai os dados se a chamada foi bem-sucedida
    $stats = $result['sucesso'] ? $result['dados'] : [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Configurações de Codificação e Viewport -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Quartos</title>
    <!-- Bootstrap CSS para estilização responsiva -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Ícones do Bootstrap Icons para elementos visuais -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- CSS Customizado -->
    <style>
        /* Estilo para os cards de estatísticas */
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
        .stat-card-info { border-color: #0dcaf0; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- ====================================================================== -->
        <!-- CABEÇALHO COM TÍTULO E BOTÕES DE AÇÃO -->
        <!-- ====================================================================== -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-door-closed"></i> Relatório de Quartos</h2>
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
        <!-- CARDS DE ESTATÍSTICAS - RESUMO VISUAL -->
        <!-- ====================================================================== -->
        <div class="row mb-4">
            <!-- Card: Total de Quartos no Hotel -->
            <div class="col-md-4 mb-3">
                <div class="card stat-card stat-card-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Total de Quartos</p>
                                <h3 class="mb-0"><?= $stats['total_quartos'] ?? 0 ?></h3>
                            </div>
                            <div class="text-primary">
                                <i class="bi bi-door-closed" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Quartos Ocupados Hoje -->
            <div class="col-md-4 mb-3">
                <div class="card stat-card stat-card-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Quartos Ocupados</p>
                                <h3 class="mb-0"><?= $stats['ocupados'] ?? 0 ?></h3>
                            </div>
                            <div class="text-warning">
                                <i class="bi bi-lock" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Quartos Disponíveis Hoje -->
            <div class="col-md-4 mb-3">
                <div class="card stat-card stat-card-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Quartos Disponíveis</p>
                                <h3 class="mb-0"><?= $stats['disponiveis'] ?? 0 ?></h3>
                            </div>
                            <div class="text-success">
                                <i class="bi bi-unlock" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ====================================================================== -->
        <!-- RESUMO COM TAXAS CALCULADAS -->
        <!-- ====================================================================== -->
        <div class="card mb-5">
            <div class="card-body bg-light">
                <div class="row text-center">
                    <!-- Taxa de Ocupação em Percentual -->
                    <div class="col-md-6">
                        <h5 class="text-muted">Taxa de Ocupação</h5>
                        <h3 class="text-warning">
                            <?php 
                            // Calcula percentual de quartos ocupados em relação ao total
                            $taxa = ($stats['total_quartos'] ?? 0) > 0 
                                ? (($stats['ocupados'] ?? 0) / $stats['total_quartos']) * 100 
                                : 0;
                            // Exibe com 1 casa decimal
                            echo number_format($taxa, 1, ',', '.') . '%';
                            ?>
                        </h3>
                    </div>
                    <!-- Taxa de Disponibilidade em Percentual -->
                    <div class="col-md-6">
                        <h5 class="text-muted">Taxa de Disponibilidade</h5>
                        <h3 class="text-success">
                            <?php 
                            // Calcula percentual de quartos disponíveis em relação ao total
                            $taxa_disp = ($stats['total_quartos'] ?? 0) > 0 
                                ? (($stats['disponiveis'] ?? 0) / $stats['total_quartos']) * 100 
                                : 0;
                            // Exibe com 1 casa decimal
                            echo number_format($taxa_disp, 1, ',', '.') . '%';
                            ?>
                        </h3>
                    </div>
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
