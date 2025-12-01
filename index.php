<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gerenciamento Hoteleiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .menu-card {
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .menu-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .header-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .relatorio-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <!-- CABEÇALHO -->
        <div class="header-card p-4 mb-5 text-center">
            <h1 class="display-4 fw-bold text-primary">
                <i class="bi bi-building"></i> Sistema de Gerenciamento Hoteleiro
            </h1>
            <p class="lead text-muted">Gerencie hóspedes, funcionários, quartos, reservas e relatórios</p>
        </div>

        <!-- SEÇÃO 1: GESTÃO PRINCIPAL -->
        <h3 class="text-white mb-4"><i class="bi bi-gear"></i> Gestão Principal</h3>
        <div class="row g-4 mb-5">
            <!-- HÓSPEDES -->
            <div class="col-md-6 col-lg-3">
                <div class="card menu-card border-0 shadow">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-person-circle text-primary menu-icon"></i>
                        <h4 class="card-title">Hóspedes</h4>
                        <p class="card-text text-muted">Gerencie cadastro de hóspedes</p>
                        <div class="d-grid gap-2">
                            <a href="view/cadastrar_hospede.php" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Cadastrar
                            </a>
                            <a href="view/listar_hospedes.php" class="btn btn-outline-primary">
                                <i class="bi bi-list-ul"></i> Listar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FUNCIONÁRIOS -->
            <div class="col-md-6 col-lg-3">
                <div class="card menu-card border-0 shadow">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-person-badge text-success menu-icon"></i>
                        <h4 class="card-title">Funcionários</h4>
                        <p class="card-text text-muted">Gerencie equipe do hotel</p>
                        <div class="d-grid gap-2">
                            <a href="view/cadastrar_funcionario.php" class="btn btn-success">
                                <i class="bi bi-plus-circle"></i> Cadastrar
                            </a>
                            <a href="view/listar_funcionarios.php" class="btn btn-outline-success">
                                <i class="bi bi-list-ul"></i> Listar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QUARTOS -->
            <div class="col-md-6 col-lg-3">
                <div class="card menu-card border-0 shadow">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-door-open text-warning menu-icon"></i>
                        <h4 class="card-title">Quartos</h4>
                        <p class="card-text text-muted">Gerencie quartos disponíveis</p>
                        <div class="d-grid gap-2">
                            <a href="view/cadastrar_quarto.php" class="btn btn-warning">
                                <i class="bi bi-plus-circle"></i> Cadastrar
                            </a>
                            <a href="view/listar_quartos.php" class="btn btn-outline-warning">
                                <i class="bi bi-list-ul"></i> Listar
                            </a>
                            <a href="view/editar_quartos.php" class="btn btn-outline-warning">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <a href="view/deletar_quarto.php" class="btn btn-outline-danger">
                                <i class="bi bi-trash"></i> Deletar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RESERVAS -->
            <div class="col-md-6 col-lg-3">
                <div class="card menu-card border-0 shadow">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-calendar-check text-danger menu-icon"></i>
                        <h4 class="card-title">Reservas</h4>
                        <p class="card-text text-muted">Gerencie reservas do hotel</p>
                        <div class="d-grid gap-2">
                            <a href="view/criar_reserva.php" class="btn btn-danger">
                                <i class="bi bi-plus-circle"></i> Nova Reserva
                            </a>
                            <a href="view/listar_reservas.php" class="btn btn-outline-danger">
                                <i class="bi bi-list-ul"></i> Listar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEÇÃO 2: OPERAÇÕES FINANCEIRAS -->
        <h3 class="text-white mb-4"><i class="bi bi-cash-stack"></i> Operações Financeiras</h3>
        <div class="row g-4 mb-5">
            <!-- PAGAMENTOS -->
            <div class="col-md-6 col-lg-4">
                <div class="card menu-card border-0 shadow">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-credit-card text-info menu-icon"></i>
                        <h4 class="card-title">Pagamentos</h4>
                        <p class="card-text text-muted">Gerencie pagamentos</p>
                        <div class="d-grid gap-2">
                            <a href="utils/router.php?controller=pagamento&action=criar" class="btn btn-info text-white">
                                <i class="bi bi-plus-circle"></i> Cadastrar
                            </a>
                            <a href="utils/router.php?controller=pagamento&action=listar" class="btn btn-outline-info">
                                <i class="bi bi-list-ul"></i> Listar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CONSUMOS -->
            <div class="col-md-6 col-lg-4">
                <div class="card menu-card border-0 shadow">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-cart3 text-purple menu-icon" style="color: #6f42c1;"></i>
                        <h4 class="card-title">Consumos</h4>
                        <p class="card-text text-muted">Registre consumos</p>
                        <div class="d-grid gap-2">
                            <a href="utils/router.php?controller=consumo&action=criar" class="btn text-white" style="background-color: #6f42c1;">
                                <i class="bi bi-plus-circle"></i> Cadastrar
                            </a>
                            <a href="utils/router.php?controller=consumo&action=listar" class="btn" style="color: #6f42c1; border-color: #6f42c1;">
                                <i class="bi bi-list-ul"></i> Listar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ITENS -->
            <div class="col-md-6 col-lg-4">
                <div class="card menu-card border-0 shadow">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-box-seam text-secondary menu-icon"></i>
                        <h4 class="card-title">Itens</h4>
                        <p class="card-text text-muted">Cardápio e produtos</p>
                        <div class="d-grid gap-2">
                            <a href="utils/router.php?controller=item&action=criar" class="btn btn-secondary">
                                <i class="bi bi-plus-circle"></i> Cadastrar
                            </a>
                            <a href="utils/router.php?controller=item&action=listar" class="btn btn-outline-secondary">
                                <i class="bi bi-list-ul"></i> Listar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEÇÃO 3: RELATÓRIOS -->
        <h3 class="text-white mb-4"><i class="bi bi-file-earmark-bar-graph"></i> Relatórios e Análises</h3>
        <div class="relatorio-section p-4 mb-5">
            <div class="row g-3">
                <!-- RELATÓRIOS DE RESERVAS -->
                <div class="col-md-6 col-lg-3">
                    <div class="card border-primary h-100">
                        <div class="card-header bg-primary text-white">
                            <i class="bi bi-calendar-event"></i> Reservas
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="view/relatorios/reservas_completas.php" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-file-text"></i> Completas
                                </a>
                                <a href="view/relatorios/reservas_periodo.php" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-calendar-range"></i> Por Período
                                </a>
                                <a href="view/relatorios/tempo_permanencia.php" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-clock-history"></i> Permanência
                                </a>
                                <a href="view/relatorios/reservas_funcionario.php" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-person-badge"></i> Por Funcionário
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RELATÓRIOS DE QUARTOS -->
                <div class="col-md-6 col-lg-3">
                    <div class="card border-warning h-100">
                        <div class="card-header bg-warning text-dark">
                            <i class="bi bi-door-open"></i> Quartos
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="view/relatorios/faturamento_tipo_quarto.php" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-cash-stack"></i> Faturamento
                                </a>
                                <a href="view/relatorios/quartos_mais_reservados.php" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-star"></i> Mais Reservados
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RELATÓRIOS DE HÓSPEDES -->
                <div class="col-md-6 col-lg-3">
                    <div class="card border-success h-100">
                        <div class="card-header bg-success text-white">
                            <i class="bi bi-people"></i> Hóspedes
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="view/relatorios/hospedes_endereco.php" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-geo-alt"></i> Com Endereço
                                </a>
                                <a href="view/relatorios/consumo_medio_hospede.php" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-graph-up"></i> Consumo Médio
                                </a>
                                <a href="view/relatorios/hospedes_vip.php" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-award"></i> Hóspedes VIP
                                </a>
                                <a href="view/relatorios/aniversariantes.php" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-cake2"></i> Aniversariantes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RELATÓRIOS FINANCEIROS -->
                <div class="col-md-6 col-lg-3">
                    <div class="card border-info h-100">
                        <div class="card-header bg-info text-white">
                            <i class="bi bi-cash-coin"></i> Financeiro
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="utils/router.php?controller=pagamento&action=relatorio_metodo_pagamento" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-credit-card"></i> Por Método
                                </a>
                                <a href="utils/router.php?controller=consumo&action=relatorio_consumo_reserva" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-receipt"></i> Consumos
                                </a>
                                <a href="utils/router.php?controller=item&action=relatorio_itens_consumidos" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-box-seam"></i> Itens Populares
                                </a>
                                <a href="utils/router.php?controller=item&action=relatorio_vendas" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-graph-up-arrow"></i> Vendas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- INFORMAÇÕES DO SISTEMA -->
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-info-circle text-info"></i> Informações do Sistema
                        </h5>
                        <div class="row">
                            <div class="col-md-3 text-center p-3">
                                <i class="bi bi-shield-check text-success" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2"><strong>Sistema Seguro</strong></p>
                                <small class="text-muted">Validações completas</small>
                            </div>
                            <div class="col-md-3 text-center p-3">
                                <i class="bi bi-database text-primary" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2"><strong>Banco de Dados</strong></p>
                                <small class="text-muted">PDO + MySQL</small>
                            </div>
                            <div class="col-md-3 text-center p-3">
                                <i class="bi bi-code-slash text-warning" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2"><strong>Arquitetura MVC</strong></p>
                                <small class="text-muted">Código organizado</small>
                            </div>
                            <div class="col-md-3 text-center p-3">
                                <i class="bi bi-palette text-danger" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2"><strong>Interface Moderna</strong></p>
                                <small class="text-muted">Bootstrap 5</small>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-3">
                            <div class="col-md-4 text-center">
                                <i class="bi bi-file-earmark-code text-primary" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2"><strong>12 Consultas SQL</strong></p>
                                <small class="text-muted">JOINs, GROUP BY, Subqueries</small>
                            </div>
                            <div class="col-md-4 text-center">
                                <i class="bi bi-diagram-3 text-success" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2"><strong>7 Controllers</strong></p>
                                <small class="text-muted">Gestão completa</small>
                            </div>
                            <div class="col-md-4 text-center">
                                <i class="bi bi-graph-up text-info" style="font-size: 2rem;"></i>
                                <p class="mb-0 mt-2"><strong>16 Relatórios</strong></p>
                                <small class="text-muted">Análises gerenciais</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>