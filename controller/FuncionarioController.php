<?php

namespace Router;

require_once __DIR__ . '/../model/Funcionario.php';
require_once __DIR__ . '/../model/Pessoa.php';
require_once __DIR__ . '/../model/Endereco.php';
require_once __DIR__ . '/../database/Database.php';

class FuncionarioController {
    private $db;
    private $funcionario;
    private $pessoa;
    private $endereco;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->funcionario = new Funcionario($this->db);
        $this->pessoa = new Pessoa($this->db);
        $this->endereco = new Endereco($this->db);
    }

    public function criar(array $dados): array {
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
            $this->pessoa->setTipoPessoa('funcionario');
            $this->pessoa->setEnderecoId($this->endereco->getId());

            if (!$this->pessoa->create()) {
                $this->db->rollBack();
                return ['sucesso' => false, 'erros' => ['Erro ao criar pessoa.']];
            }

            // Criar funcionário
            $this->funcionario->setIdPessoa($this->pessoa->getId());
            $this->funcionario->setCargo($dados['cargo'] ?? null);
            $this->funcionario->setSalario($dados['salario'] ?? null);
            $this->funcionario->setDataContratacao($dados['data_contratacao'] ?? date('Y-m-d'));
            $this->funcionario->setNumeroCtps($dados['numero_ctps'] ?? null);
            $this->funcionario->setTurno($dados['turno'] ?? null);

            $erros = $this->funcionario->validar();
            if (!empty($erros)) {
                $this->db->rollBack();
                return ['sucesso' => false, 'erros' => $erros];
            }

            if (!$this->funcionario->create()) {
                $this->db->rollBack();
                return ['sucesso' => false, 'erros' => ['Erro ao criar funcionário.']];
            }

            $this->db->commit();
            return [
                'sucesso' => true, 
                'mensagem' => 'Funcionário criado com sucesso!',
                'id' => $this->pessoa->getId()
            ];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['sucesso' => false, 'erros' => ['Erro: ' . $e->getMessage()]];
        }
    }

    public function listar(): array {
        try {
            $stmt = $this->funcionario->read();
            $funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ['sucesso' => true, 'dados' => $funcionarios];
        } catch (Exception $e) {
            return ['sucesso' => false, 'erros' => ['Erro: ' . $e->getMessage()]];
        }
    }

    public function buscarPorId(int $id): array {
        try {
            $this->funcionario->setIdPessoa($id);
            $dados = $this->funcionario->readComplete();
            
            if ($dados) {
                return ['sucesso' => true, 'dados' => $dados];
            }
            return ['sucesso' => false, 'erros' => ['Funcionário não encontrado.']];
        } catch (Exception $e) {
            return ['sucesso' => false, 'erros' => ['Erro: ' . $e->getMessage()]];
        }
    }

    public function atualizar(int $id, array $dados): array {
        try {
            $this->db->beginTransaction();

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

            $this->funcionario->setIdPessoa($id);
            $this->funcionario->setCargo($dados['cargo'] ?? null);
            $this->funcionario->setSalario($dados['salario'] ?? null);
            $this->funcionario->setDataContratacao($dados['data_contratacao']);
            $this->funcionario->setNumeroCtps($dados['numero_ctps'] ?? null);
            $this->funcionario->setTurno($dados['turno'] ?? null);

            if (!$this->funcionario->update()) {
                $this->db->rollBack();
                return ['sucesso' => false, 'erros' => ['Erro ao atualizar funcionário.']];
            }

            $this->db->commit();
            return ['sucesso' => true, 'mensagem' => 'Funcionário atualizado!'];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['sucesso' => false, 'erros' => ['Erro: ' . $e->getMessage()]];
        }
    }

    public function deletar(int $id): array {
        try {
            $this->db->beginTransaction();

            $this->funcionario->setIdPessoa($id);
            if (!$this->funcionario->delete()) {
                $this->db->rollBack();
                return ['sucesso' => false, 'erros' => ['Erro ao excluir funcionário.']];
            }

            $this->pessoa->setId($id);
            if (!$this->pessoa->delete()) {
                $this->db->rollBack();
                return ['sucesso' => false, 'erros' => ['Erro ao excluir pessoa.']];
            }

            $this->db->commit();
            return ['sucesso' => true, 'mensagem' => 'Funcionário excluído!'];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['sucesso' => false, 'erros' => ['Erro: ' . $e->getMessage()]];
        }
    }
}
?>