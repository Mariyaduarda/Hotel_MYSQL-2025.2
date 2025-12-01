<?php

namespace Router;

require_once __DIR__ . '/../model/Hospede.php';   
require_once __DIR__ . '/../model/Pessoa.php';
require_once __DIR__ . '/../model/Endereco.php';
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../utils/Validacoes.php';

use Utils\Validacoes;
use database\Database;
use Router\Hospede;
use Router\Pessoa;
use Router\Endereco;
$db = new Database(); 

class HospedeController {
    private $db;
    private $hospede;
    private $pessoa;
    private $endereco;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->hospede = \new Hospede($this->db);
        $this->pessoa = \new Pessoa($this->db);
        $this->endereco = \new Endereco($this->db);
    }

    public function criar(array $dados): array {

        $erros = [];

    if (!Validacoes::validarNome($dados['nome'] ?? '')) {
        $erros[] = "Nome inválido.";
    }

    if (!empty($dados['email']) && !Validacoes::validarEmail($dados['email'])) {
        $erros[] = "Email inválido.";
    }

    if (!empty($dados['telefone']) && !Validacoes::validarTelefone($dados['telefone'])) {
        $erros[] = "Telefone inválido.";
    }

    if (!empty($dados['data_nascimento']) && !Validacoes::validarDataNascimento($dados['data_nascimento'])) {
        $erros[] = "Data de nascimento inválida (mínimo 18 anos).";
    }

    if (!empty($dados['documento']) && !Validacoes::validarCPF($dados['documento'])) {
        $erros[] = "CPF inválido.";
    }

    if (!empty($dados['cidade']) && !Validacoes::validarTexto($dados['cidade'])) {
        $erros[] = "Cidade inválida.";
    }

    if (!empty($dados['estado']) && !Validacoes::validarTexto($dados['estado'], 2, 2)) {
        $erros[] = "Estado deve ter exatamente 2 letras.";
    }

    if (!empty($dados['cep']) && strlen(preg_replace('/\D/', '', $dados['cep'])) !== 8) {
        $erros[] = "CEP inválido.";
    }

    // Retornar caso existam erros
    if (!empty($erros)) {
        return ['sucesso' => false, 'erros' => $erros];
    }

        try {
            $this->db->beginTransaction();

            // Criar endereço
            $this->endereco->setLogradouro($dados['logradouro'] ?? null);
            $this->endereco->setNumero($dados['numero'] ?? null);
            $this->endereco->setBairro($dados['bairro'] ?? null);
            $this->endereco->setCidade($dados['cidade']);
            $this->endereco->setEstado($dados['estado']);
            $this->endereco->setPais($dados['pais'] ?? 'Brasil');
            $this->endereco->setCep($dados['cep'] ?? null);

            if (!$this->endereco->create()) {
                $this->db->rollBack();
                return ['sucesso' => false, 'erros' => ['Erro ao criar endereço.']];
            }

            // Criar pessoa
            $this->pessoa->setNome($dados['nome']);
            $this->pessoa->setSexo($dados['sexo'] ?? null);
            $this->pessoa->setDataNascimento($dados['data_nascimento'] ?? null);
            $this->pessoa->setDocumento($dados['documento'] ?? null);
            $this->pessoa->setTelefone($dados['telefone'] ?? null);
            $this->pessoa->setEmail($dados['email'] ?? null);
            $this->pessoa->setTipoPessoa('hospede');
            $this->pessoa->setEnderecoId($this->endereco->getId());

            if (!$this->pessoa->create()) {
                $this->db->rollBack();
                return ['sucesso' => false, 'erros' => ['Erro ao criar pessoa.']];
            }

            // Criar hóspede
            $this->hospede->setIdPessoa($this->pessoa->getId());
            $this->hospede->setPreferencias($dados['preferencias'] ?? null);
            $this->hospede->setHistorico($dados['historico'] ?? null);

            if (!$this->hospede->create()) {
                $this->db->rollBack();
                return ['sucesso' => false, 'erros' => ['Erro ao criar hóspede.']];
            }

            $this->db->commit();
            return [
                'sucesso' => true, 
                'mensagem' => 'Hóspede criado com sucesso!',
                'id' => $this->pessoa->getId()
            ];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['sucesso' => false, 'erros' => ['Erro: ' . $e->getMessage()]];
        }
    }

    public function listar(): array {
        try {
            $stmt = $this->hospede->read();
            $hospedes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ['sucesso' => true, 'dados' => $hospedes];
        } catch (Exception $e) {
            return ['sucesso' => false, 'erros' => ['Erro: ' . $e->getMessage()]];
        }
    }

    public function buscarPorId(int $id): array {
        try {
            $this->hospede->setIdPessoa($id);
            $dados = $this->hospede->readComplete();
            
            if ($dados) {
                return ['sucesso' => true, 'dados' => $dados];
            }
            return ['sucesso' => false, 'erros' => ['Hóspede não encontrado.']];
        } catch (Exception $e) {
            return ['sucesso' => false, 'erros' => ['Erro: ' . $e->getMessage()]];
        }
    }

    public function atualizar(int $id, array $dados): array {
        try {
            $this->db->beginTransaction();

            // Atualizar pessoa
            $this->pessoa->setId($id);
            if (!$this->pessoa->readOne()) {
                $this->db->rollBack();
                return ['sucesso' => false, 'erros' => ['Pessoa não encontrada.']];
            }

            $this->pessoa->setNome($dados['nome']);
            $this->pessoa->setSexo($dados['sexo'] ?? null);
            $this->pessoa->setDataNascimento($dados['data_nascimento'] ?? null);
            $this->pessoa->setDocumento($dados['documento'] ?? null);
            $this->pessoa->setTelefone($dados['telefone'] ?? null);
            $this->pessoa->setEmail($dados['email'] ?? null);

            if (!$this->pessoa->update()) {
                $this->db->rollBack();
                return ['sucesso' => false, 'erros' => ['Erro ao atualizar pessoa.']];
            }

            // Atualizar hóspede
            $this->hospede->setIdPessoa($id);
            $this->hospede->setPreferencias($dados['preferencias'] ?? null);
            $this->hospede->setHistorico($dados['historico'] ?? null);

            if (!$this->hospede->update()) {
                $this->db->rollBack();
                return ['sucesso' => false, 'erros' => ['Erro ao atualizar hóspede.']];
            }

            $this->db->commit();
            return ['sucesso' => true, 'mensagem' => 'Hóspede atualizado!'];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['sucesso' => false, 'erros' => ['Erro: ' . $e->getMessage()]];
        }
    }

    public function deletar(int $id): array {
        try {
            $this->db->beginTransaction();

            $this->hospede->setIdPessoa($id);
            if (!$this->hospede->delete()) {
                $this->db->rollBack();
                return ['sucesso' => false, 'erros' => ['Erro ao excluir hóspede.']];
            }

            $this->pessoa->setId($id);
            if (!$this->pessoa->delete()) {
                $this->db->rollBack();
                return ['sucesso' => false, 'erros' => ['Erro ao excluir pessoa.']];
            }

            $this->db->commit();
            return ['sucesso' => true, 'mensagem' => 'Hóspede excluído!'];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['sucesso' => false, 'erros' => ['Erro: ' . $e->getMessage()]];
        }
    }
}
?>