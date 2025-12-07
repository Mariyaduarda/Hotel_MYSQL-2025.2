<?php
// ============================================================================
// RELATÓRIO DE FUNCIONÁRIOS - Lista e Folha de Pessoal
// ============================================================================

// Importa o controlador de relatórios que fornece dados do banco de dados
require_once __DIR__ . '/../../controller/RelatorioController.php';
// Importa a classe utilitária para formatação de dados
require_once __DIR__ . '/../utils/Formatter.php';

// Define uso do namespace para o controlador
use Controller\RelatorioController;

// ============================================================================
// BUSCAR DADOS DO BANCO DE DADOS - LISTA DE FUNCIONÁRIOS
// ============================================================================

// Instancia o controlador de relatórios de forma segura, verificando se a classe existe
$controller = class_exists('\Controller\\RelatorioController') ? new RelatorioController() : null;

// Array que armazenará a lista de funcionários ativos
$funcionarios = [];
// Variável para armazenar o total de folha de pagamento
$total_folha = 0;

// Verifica se o controlador foi instanciado e se possui o método desejado
if ($controller && method_exists($controller, 'funcionariosAtivos')) {
    // Chama o método para buscar todos os funcionários com seus dados
    $res = $controller->funcionariosAtivos();
    // Extrai os dados se a chamada foi bem-sucedida
    $funcionarios = $res['sucesso'] ? $res['dados'] : [];
    
    // Calcula o total de salários (folha de pagamento)
    foreach ($funcionarios as $f) {
        $total_folha += ($f['salario'] ?? 0);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Configurações de Codificação e Viewport -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Funcionários</title>
    <!-- Bootstrap CSS para estilização responsiva -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Ícones do Bootstrap Icons para elementos visuais -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- CSS Customizado -->
    <style>
        /* Estilo para items na lista de funcionários -->
        .list-group-item {
            border-left: 4px solid #0d6efd;
            transition: all 0.3s ease;
        }
        .list-group-item:hover {
            background-color: rgba(13, 110, 253, 0.05);
            transform: translateX(5px);
        }
        /* Destaque para salários na tabela -->
        .salary-highlight {
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
            <h2><i class="bi bi-people-fill"></i> Relatório de Funcionários</h2>
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
        <!-- SEÇÃO DE RESUMO - TOTAL DE FUNCIONÁRIOS E FOLHA -->
        <!-- ====================================================================== -->
        <div class="row mb-4">
            <!-- Card: Total de Funcionários -->
            <div class="col-md-6 mb-3">
                <div class="card border-left-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Total de Funcionários</p>
                                <h3 class="mb-0"><?= count($funcionarios) ?></h3>
                            </div>
                            <div class="text-primary">
                                <i class="bi bi-people-fill" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Total da Folha de Pagamento -->
            <div class="col-md-6 mb-3">
                <div class="card border-left-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Total da Folha (Mensal)</p>
                                <h3 class="mb-0 salary-highlight">R$ <?= number_format($total_folha, 2, ',', '.') ?></h3>
                            </div>
                            <div class="text-success">
                                <i class="bi bi-cash-stack" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ====================================================================== -->
        <!-- SEÇÃO DE LISTAGEM DE FUNCIONÁRIOS -->
        <!-- ====================================================================== -->
        <div class="card">
            <!-- Cabeçalho do Card -->
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-person-badge"></i> Lista de Funcionários Ativos
                </h5>
            </div>
            <!-- Corpo do Card -->
            <div class="card-body">
                <?php if (empty($funcionarios)): ?>
                    <!-- Mensagem quando não há funcionários -->
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle"></i> 
                        Nenhum funcionário listado no momento.
                    </div>
                <?php else: ?>
                    <!-- Lista de funcionários usando componente list-group do Bootstrap -->
                    <ul class="list-group list-group-flush">
                        <?php foreach ($funcionarios as $f): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <!-- Informações do Funcionário -->
                                <div>
                                    <!-- Nome em negrito -->
                                    <strong><?= htmlspecialchars($f['nome'] ?? '-') ?></strong>
                                    <!-- Detalhes adicionais em linha menor -->
                                    <div class="small text-muted">
                                        <!-- Cargo do Funcionário -->
                                        <i class="bi bi-briefcase"></i> 
                                        <?= htmlspecialchars($f['cargo'] ?? '-') ?>
                                        <!-- Turno de Trabalho -->
                                        &nbsp;&nbsp;
                                        <i class="bi bi-clock"></i> 
                                        <?= htmlspecialchars($f['turno'] ?? '-') ?>
                                    </div>
                                </div>
                                <!-- Salário em Badge de Destaque -->
                                <span class="badge bg-success rounded-pill">
                                    R$ <?= number_format($f['salario'] ?? 0, 2, ',', '.') ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <!-- Nota informando quantidade de funcionários -->
                    <div class="mt-3 text-muted">
                        <small>
                            <i class="bi bi-info-circle"></i> 
                            Total de <?= count($funcionarios) ?> funcionário(s) ativo(s).
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Importa Bootstrap JavaScript para interatividade de componentes -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Estilos CSS adicionados para cards de resumo -->
    <style>
        /* Borda esquerda para cards de resumo -->
        .border-left-primary { border-left: 4px solid #0d6efd; }
        .border-left-success { border-left: 4px solid #198754; }
    </style>

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
