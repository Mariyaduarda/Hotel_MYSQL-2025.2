<?php
// Página índice dos relatórios
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Relatórios - Índice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .report-card { transition: transform .15s ease; }
        .report-card:hover { transform: translateY(-6px); box-shadow: 0 8px 18px rgba(0,0,0,0.08); }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-file-earmark-text"></i> Relatórios</h2>
            <a href="../index.php" class="btn btn-secondary"><i class="bi bi-house"></i> Menu</a>
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <a href="relatorio_hospede.php" class="text-decoration-none text-dark">
                    <div class="card report-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 display-6 text-primary"><i class="bi bi-people"></i></div>
                            <div>
                                <h5 class="card-title mb-0">Hóspedes</h5>
                                <small class="text-muted">Visão geral, top clientes e ativos</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="relatorio_quarto.php" class="text-decoration-none text-dark">
                    <div class="card report-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 display-6 text-success"><i class="bi bi-door-closed"></i></div>
                            <div>
                                <h5 class="card-title mb-0">Quartos</h5>
                                <small class="text-muted">Ocupação e disponibilidade</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="relatorio_reserva.php" class="text-decoration-none text-dark">
                    <div class="card report-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 display-6 text-warning"><i class="bi bi-journal-check"></i></div>
                            <div>
                                <h5 class="card-title mb-0">Reservas</h5>
                                <small class="text-muted">Resumo e lista de reservas</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="relatorio_funcionario.php" class="text-decoration-none text-dark">
                    <div class="card report-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 display-6 text-info"><i class="bi bi-people-fill"></i></div>
                            <div>
                                <h5 class="card-title mb-0">Funcionários</h5>
                                <small class="text-muted">Listagem e folha (resumida)</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="relatorio_consumo.php" class="text-decoration-none text-dark">
                    <div class="card report-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 display-6 text-secondary"><i class="bi bi-basket3"></i></div>
                            <div>
                                <h5 class="card-title mb-0">Consumos</h5>
                                <small class="text-muted">Itens consumidos e valores</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="relatorio_financeiro.php" class="text-decoration-none text-dark">
                    <div class="card report-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3 display-6 text-danger"><i class="bi bi-cash-stack"></i></div>
                            <div>
                                <h5 class="card-title mb-0">Financeiro</h5>
                                <small class="text-muted">Receitas e resumo financeiro</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
