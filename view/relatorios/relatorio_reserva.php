<?php
// ============================================================================
// RELATÓRIO DE RESERVAS - Lista e Resumo de Todas as Reservas
// ============================================================================

// Importa o controlador de relatórios que fornece dados do banco de dados
require_once __DIR__ . '/../../controller/RelatorioController.php';
// Importa a classe utilitária para formatação de dados (datas, valores, etc)
require_once __DIR__ . '/../utils/Formatter.php';

// Define uso do namespace para o controlador
use Controller\RelatorioController;

// ============================================================================
// BUSCAR DADOS DO BANCO DE DADOS - LISTA DE RESERVAS
// ============================================================================

// Instancia o controlador de relatórios de forma segura, verificando se a classe existe
$controller = class_exists('\Controller\\RelatorioController') ? new RelatorioController() : null;

// Array que armazenará a lista de reservas
$reservas = [];

// Verifica se o controlador foi instanciado e se possui o método desejado
if ($controller && method_exists($controller, 'reservasResumo')) {
    // Chama o método para buscar resumo das últimas 100 reservas
    $res = $controller->reservasResumo();
    // Extrai os dados se a chamada foi bem-sucedida
    $reservas = $res['sucesso'] ? $res['dados'] : [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Configurações de Codificação e Viewport -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Reservas</title>
    <!-- Bootstrap CSS para estilização responsiva -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Ícones do Bootstrap Icons para elementos visuais -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- CSS Customizado -->
    <style>
        /* Estilo para os cards de cabeçalho */
        .header-section {
            margin-bottom: 2rem;
        }
        /* Destaque para linhas da tabela ao passar mouse -->
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.1);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- ====================================================================== -->
        <!-- CABEÇALHO COM TÍTULO E BOTÕES DE AÇÃO -->
        <!-- ====================================================================== -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-journal-check"></i> Relatório de Reservas</h2>
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
        <!-- SEÇÃO DE LISTAGEM DE RESERVAS -->
        <!-- ====================================================================== -->
        <div class="card">
            <!-- Cabeçalho do Card com Ícone -->
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-list-check"></i> Lista de Reservas Recentes
                </h5>
            </div>
            <!-- Corpo do Card com Tabela ou Mensagem -->
            <div class="card-body">
                <?php if (empty($reservas)): ?>
                    <!-- Mensagem quando não há reservas para exibir -->
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle"></i> 
                        Nenhuma reserva disponível para exibir no momento.
                    </div>
                <?php else: ?>
                    <!-- Tabela responsiva com as reservas -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <!-- Cabeçalho da tabela com nomes das colunas -->
                            <thead class="table-light">
                                <tr>
                                    <th><i class="bi bi-hash"></i> ID</th>
                                    <th><i class="bi bi-person"></i> Hóspede</th>
                                    <th><i class="bi bi-door-closed"></i> Quarto</th>
                                    <th><i class="bi bi-calendar-event"></i> Check-in</th>
                                    <th><i class="bi bi-calendar-x"></i> Check-out</th>
                                    <th class="text-end"><i class="bi bi-currency-dollar"></i> Valor</th>
                                </tr>
                            </thead>
                            <!-- Linhas da tabela com dados das reservas -->
                            <tbody>
                                <?php foreach ($reservas as $r): ?>
                                    <tr>
                                        <!-- ID da Reserva -->
                                        <td>
                                            <strong><?= $r['idreserva'] ?? '-' ?></strong>
                                        </td>
                                        <!-- Nome do Hóspede -->
                                        <td>
                                            <?= htmlspecialchars($r['nome'] ?? '-') ?>
                                        </td>
                                        <!-- Número do Quarto em Badge -->
                                        <td>
                                            <span class="badge bg-info">
                                                Quarto <?= $r['numero_quarto'] ?? '-' ?>
                                            </span>
                                        </td>
                                        <!-- Data de Check-in Formatada -->
                                        <td>
                                            <?= Formatter::formatarData($r['data_checkin_previsto'] ?? null) ?>
                                        </td>
                                        <!-- Data de Check-out Formatada -->
                                        <td>
                                            <?= Formatter::formatarData($r['data_checkout_previsto'] ?? null) ?>
                                        </td>
                                        <!-- Valor da Reserva em Real Formatado -->
                                        <td class="text-end">
                                            <strong class="text-success">
                                                R$ <?= number_format($r['valor_reserva'] ?? 0, 2, ',', '.') ?>
                                            </strong>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Nota informando quantidade de registros exibidos -->
                    <div class="mt-3 text-muted">
                        <small>
                            <i class="bi bi-info-circle"></i> 
                            Exibindo <?= count($reservas) ?> reserva(s) recente(s).
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
