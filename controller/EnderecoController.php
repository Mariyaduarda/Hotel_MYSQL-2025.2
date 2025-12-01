<?php

namespace Controller;

require_once __DIR__ . '/../model/Endereco.php';
require_once __DIR__ . '/../database/Database.php';

use database\Database;
use model\Endereco;
$db = new Database(); 

class EnderecoController {
    private $db;
    private $endereco;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->endereco = new Endereco($this->db);
    }

    public function criar(array $dados): array {
        try {
            $this->endereco->setLogradouro($dados['logradouro'] ?? null);
            $this->endereco->setNumero($dados['numero'] ?? null);
            $this->endereco->setBairro($dados['bairro'] ?? null);
            $this->endereco->setCidade($dados['cidade']);
            $this->endereco->setEstado($dados['estado']);
            $this->endereco->setPais($dados['pais'] ?? 'Brasil');
            $this->endereco->setCep($dados['cep'] ?? null);

            $erros = $this->endereco->validar();
            if (!empty($erros)) {
                return ['sucesso' => false, 'erros' => $erros];
            }

            if ($this->endereco->create()) {
                return [
                    'sucesso' => true, 
                    'mensagem' => 'Endereço criado com sucesso!',
                    'id' => $this->endereco->getId()
                ];
            }

            return ['sucesso' => false, 'erros' => ['Erro ao criar endereço.']];
        } catch (Exception $e) {
            return ['sucesso' => false, 'erros' => ['Erro: ' . $e->getMessage()]];
        }
    }

    public function listar(): array {
        try {
            $stmt = $this->endereco->read();
            $enderecos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ['sucesso' => true, 'dados' => $enderecos];
        } catch (Exception $e) {
            return ['sucesso' => false, 'erros' => ['Erro: ' . $e->getMessage()]];
        }
    }

    public function buscarPorId(int $id): array {
        try {
            $this->endereco->setId($id);
            if ($this->endereco->readOne()) {
                return ['sucesso' => true, 'dados' => $this->endereco->toArray()];
            }
            return ['sucesso' => false, 'erros' => ['Endereço não encontrado.']];
        } catch (Exception $e) {
            return ['sucesso' => false, 'erros' => ['Erro: ' . $e->getMessage()]];
        }
    }

    public function atualizar(int $id, array $dados): array {
        try {
            $this->endereco->setId($id);
            if (!$this->endereco->readOne()) {
                return ['sucesso' => false, 'erros' => ['Endereço não encontrado.']];
            }

            $this->endereco->setLogradouro($dados['logradouro'] ?? null);
            $this->endereco->setNumero($dados['numero'] ?? null);
            $this->endereco->setBairro($dados['bairro'] ?? null);
            $this->endereco->setCidade($dados['cidade']);
            $this->endereco->setEstado($dados['estado']);
            $this->endereco->setPais($dados['pais'] ?? 'Brasil');
            $this->endereco->setCep($dados['cep'] ?? null);

            $erros = $this->endereco->validar();
            if (!empty($erros)) {
                return ['sucesso' => false, 'erros' => $erros];
            }

            if ($this->endereco->update()) {
                return ['sucesso' => true, 'mensagem' => 'Endereço atualizado!'];
            }

            return ['sucesso' => false, 'erros' => ['Erro ao atualizar endereço.']];
        } catch (Exception $e) {
            return ['sucesso' => false, 'erros' => ['Erro: ' . $e->getMessage()]];
        }
    }

    public function deletar(int $id): array {
        try {
            $this->endereco->setId($id);
            if (!$this->endereco->readOne()) {
                return ['sucesso' => false, 'erros' => ['Endereço não encontrado.']];
            }

            if ($this->endereco->delete()) {
                return ['sucesso' => true, 'mensagem' => 'Endereço excluído!'];
            }

            return ['sucesso' => false, 'erros' => ['Erro ao excluir endereço.']];
        } catch (Exception $e) {
            return ['sucesso' => false, 'erros' => ['Erro: ' . $e->getMessage()]];
        }
    }
}
?>