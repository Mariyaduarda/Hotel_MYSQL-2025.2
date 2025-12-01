<?php
require_once __DIR__ . '/../controller/FuncionarioController.php';
require_once __DIR__ . '/../utils/Formatter.php';

use Controller\FuncionarioController;

session_start();

$controller = new FuncionarioController();
$resultado = $controller->listar();

// pega a lista ou array vazio
$funcionarios = $resultado['sucesso'] ? $resultado['dados'] : [];

// Captura mensagens da sessão
$mensagem_sucesso = $_SESSION['mensagem_sucesso'] ?? '';
$mensagem_erro = $_SESSION['mensagem_erro'] ?? '';

// Limpa as mensagens da sessão
unset($_SESSION['mensagem_sucesso']);
unset($_SESSION['mensagem_erro']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Funcionários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-people"></i> Lista de Funcionários</h2>
            <div>
                <a href="cadastrar_funcionario.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Novo Funcionário
                </a>
                <a href="../index.php" class="btn btn-outline-secondary">
                    <i class="bi bi-house"></i> Menu
                </a>
            </div>
        </div>

        <?php if ($mensagem_sucesso): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> <?= htmlspecialchars($mensagem_sucesso) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($mensagem_erro): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i> <?= $mensagem_erro ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($funcionarios)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Nenhum funcionário cadastrado ainda.
            </div>

        <?php else: ?>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Cargo</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Cidade</th>
                            <th>Estado</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($funcionarios as $f): ?>
                            <tr>
                                <td><?= htmlspecialchars($f['id']) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($f['nome']) ?></strong>
                                </td>
                                <td><?= Formatter::formatarCPF($f['cpf']) ?></td>
                                <td>
                                    <?php if (!empty($f['cargo'])): ?>
                                        <span class="badge bg-primary">
                                            <?= htmlspecialchars($f['cargo']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($f['email'])): ?>
                                        <a href="mailto:<?= htmlspecialchars($f['email']) ?>">
                                            <?= htmlspecialchars($f['email']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= Formatter::formatarTelefone($f['telefone']) ?></td>
                                <td><?= htmlspecialchars($f['cidade'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($f['estado'] ?? '-') ?></td>

                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="editar_funcionario.php?id=<?= $f['id'] ?>" 
                                           class="btn btn-warning" 
                                           title="Editar"
                                           data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <button onclick="confirmarExclusao(<?= $f['id'] ?>, '<?= htmlspecialchars($f['nome']) ?>')" 
                                                class="btn btn-danger" 
                                                title="Excluir"
                                                data-bs-toggle="tooltip">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="alert alert-light d-flex justify-content-between align-items-center">
                <span>
                    <i class="bi bi-info-circle"></i> 
                    <strong>Total:</strong> <?= count($funcionarios) ?> funcionário(s) cadastrado(s)
                </span>
                <span class="text-muted">
                    <i class="bi bi-clock"></i> Atualizado em <?= date('d/m/Y H:i') ?>
                </span>
            </div>

        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Inicializa tooltips do Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        function confirmarExclusao(id, nome) {
            if (confirm('Tem certeza que deseja excluir o funcionário:\n\n"' + nome + '"\n\nEsta ação NÃO pode ser desfeita!')) {
                window.location.href = 'deletar_funcionario.php?id=' + id;
            }
        }
    </script>
</body>
</html>