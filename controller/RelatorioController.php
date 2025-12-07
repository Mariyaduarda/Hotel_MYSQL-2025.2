<?php
namespace Controller;

require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../model/Reserva.php';
require_once __DIR__ . '/../model/Pessoa.php';
require_once __DIR__ . '/../model/Hospede.php';
require_once __DIR__ . '/../model/Quarto.php';
require_once __DIR__ . '/../model/Funcionario.php';
require_once __DIR__ . '/../model/Pagamento.php';
require_once __DIR__ . '/../model/Consumo.php';

class RelatorioController {
    private $db;

    public function __construct() {
        $database = new \Database\Database();
        $this->db = $database->getConnection();
    }

    // Dashboard de hóspedes e estatísticas gerais
    public function dashboard(): array {
        try {
            // Total de hóspedes
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM hospede");
            $total_hospedes = (int)($stmt->fetch()['total'] ?? 0);

            // Total reservas
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM reserva");
            $total_reservas = (int)($stmt->fetch()['total'] ?? 0);

            // Receita total (pagamentos)
            $stmt = $this->db->query("SELECT COALESCE(SUM(valor_total),0) as total FROM pagamento");
            $receita_total = (float)($stmt->fetch()['total'] ?? 0);

            // Hóspedes com check-in ativo (hoje)
            $today = date('Y-m-d');
            $sql = "SELECT r.idreserva, p.nome, q.numero as numero_quarto, r.data_checkin_previsto as data_checkin, r.data_checkout_previsto as data_checkout,
                           DATEDIFF(r.data_checkout_previsto, :today) as dias_restantes, p.telefone
                    FROM reserva r
                    LEFT JOIN hospede h ON r.id_hospede = h.id_pessoa
                    LEFT JOIN pessoa p ON h.id_pessoa = p.id_pessoa
                    LEFT JOIN quarto q ON r.id_quarto = q.id_quarto
                    WHERE :today BETWEEN r.data_checkin_previsto AND r.data_checkout_previsto
                          AND r.status <> 'cancelada'";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':today', $today);
            $stmt->execute();
            $hospedes_ativos = $stmt->fetchAll();

            // Ticket médio geral (por reserva)
            $ticket_medio = $total_reservas > 0 ? $receita_total / $total_reservas : 0;

            $estatisticas = [
                'total_hospedes' => $total_hospedes,
                'hospedes_ativos' => count($hospedes_ativos),
                'total_reservas' => $total_reservas,
                'receita_total' => $receita_total,
                'ticket_medio_geral' => $ticket_medio
            ];

            return [
                'sucesso' => true,
                'estatisticas' => $estatisticas,
                'hospedes_ativos' => $hospedes_ativos
            ];
        } catch (\Exception $e) {
            return ['sucesso' => false, 'erros' => ['Erro dashboard: ' . $e->getMessage()]];
        }
    }

    // Top N hóspedes por número de reservas
    public function hospedesMaisFrequentes(int $limit = 10): array {
        try {
            $sql = "SELECT p.id_pessoa, p.nome, p.email, COUNT(r.idreserva) as total_reservas,
                           COALESCE(SUM(pg.valor_total),0) as valor_total_gasto,
                           MAX(r.data_checkin_previsto) as ultima_visita,
                           MIN(r.data_reserva) as primeira_visita
                    FROM pessoa p
                    INNER JOIN hospede h ON p.id_pessoa = h.id_pessoa
                    LEFT JOIN reserva r ON r.id_hospede = h.id_pessoa
                    LEFT JOIN pagamento pg ON pg.reserva_idreserva = r.idreserva
                    GROUP BY p.id_pessoa
                    ORDER BY total_reservas DESC
                    LIMIT :limit";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll();

            // Calcular ticket medio por hóspede de forma segura
            foreach ($rows as &$r) {
                $r['ticket_medio'] = ($r['total_reservas'] > 0) ? ($r['valor_total_gasto'] / $r['total_reservas']) : 0;
            }

            return ['sucesso' => true, 'dados' => $rows];
        } catch (\Exception $e) {
            return ['sucesso' => false, 'erros' => ['Erro top hospedes: ' . $e->getMessage()]];
        }
    }

    // Estatísticas de quartos
    public function quartosEstatisticas(): array {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM quarto");
            $total = (int)($stmt->fetch()['total'] ?? 0);

            $today = date('Y-m-d');
            $sql = "SELECT COUNT(DISTINCT r.id_quarto) as ocupados
                    FROM reserva r
                    WHERE :today BETWEEN r.data_checkin_previsto AND r.data_checkout_previsto
                          AND r.status <> 'cancelada'";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':today', $today);
            $stmt->execute();
            $ocupados = (int)($stmt->fetch()['ocupados'] ?? 0);

            $disponiveis = max(0, $total - $ocupados);

            return ['sucesso' => true, 'dados' => ['total_quartos' => $total, 'ocupados' => $ocupados, 'disponiveis' => $disponiveis]];
        } catch (\Exception $e) {
            return ['sucesso' => false, 'erros' => ['Erro quartos: ' . $e->getMessage()]];
        }
    }

    // Resumo/lista de reservas
    public function reservasResumo(): array {
        try {
            $sql = "SELECT r.idreserva, p_hosp.nome, q.numero as numero_quarto, r.data_checkin_previsto, r.data_checkout_previsto, r.valor_reserva
                    FROM reserva r
                    LEFT JOIN hospede h ON r.id_hospede = h.id_pessoa
                    LEFT JOIN pessoa p_hosp ON h.id_pessoa = p_hosp.id_pessoa
                    LEFT JOIN quarto q ON r.id_quarto = q.id_quarto
                    ORDER BY r.data_reserva DESC
                    LIMIT 100";

            $stmt = $this->db->query($sql);
            $dados = $stmt->fetchAll();
            return ['sucesso' => true, 'dados' => $dados];
        } catch (\Exception $e) {
            return ['sucesso' => false, 'erros' => ['Erro reservasResumo: ' . $e->getMessage()]];
        }
    }

    // Funcionários ativos
    public function funcionariosAtivos(): array {
        try {
            $sql = "SELECT f.*, p.nome, p.email
                    FROM funcionario f
                    LEFT JOIN pessoa p ON f.id_pessoa = p.id_pessoa
                    ORDER BY p.nome ASC";

            $stmt = $this->db->query($sql);
            $dados = $stmt->fetchAll();
            return ['sucesso' => true, 'dados' => $dados];
        } catch (\Exception $e) {
            return ['sucesso' => false, 'erros' => ['Erro funcionariosAtivos: ' . $e->getMessage()]];
        }
    }

    // Consumos recentes
    public function consumosRecentes(): array {
        try {
            $sql = "SELECT c.*, r.idreserva
                    FROM consumo c
                    LEFT JOIN reserva r ON c.reserva_idreserva = r.idreserva
                    ORDER BY c.data_consumo DESC
                    LIMIT 100";

            $stmt = $this->db->query($sql);
            $dados = $stmt->fetchAll();
            return ['sucesso' => true, 'dados' => $dados];
        } catch (\Exception $e) {
            return ['sucesso' => false, 'erros' => ['Erro consumosRecentes: ' . $e->getMessage()]];
        }
    }

    // Resumo financeiro
    public function financeiroResumo(): array {
        try {
            $stmt = $this->db->query("SELECT COALESCE(SUM(valor_total),0) as receita_total FROM pagamento");
            $receita_total = (float)($stmt->fetch()['receita_total'] ?? 0);

            $sql = "SELECT COALESCE(SUM(valor_total),0) as receita_periodo FROM pagamento WHERE data_pagamento BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()";
            $stmt = $this->db->query($sql);
            $receita_periodo = (float)($stmt->fetch()['receita_periodo'] ?? 0);

            return ['sucesso' => true, 'dados' => ['receita_total' => $receita_total, 'receita_periodo' => $receita_periodo]];
        } catch (\Exception $e) {
            return ['sucesso' => false, 'erros' => ['Erro financeiroResumo: ' . $e->getMessage()]];
        }
    }
}

?>
