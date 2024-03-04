<?php

namespace app\models;
use app\traits\SessionMessageTrait;
use app\database\Connect;
use PDO;
use PDOException;

/**
 * Class UserModel
 * 
 * Esta classe é responsável por efetuar as operações de banco na tabela 'users'
 * 
 * Está classe herda a conexão com o banco de dados da classe Connect
 */
class UserModel extends Connect
{
    use SessionMessageTrait;

    private $table;

    /**
     * Construtor da classe UserModel
     * 
     * Inicializa a classe UserModel, permite criar uma instância do construtor da classe Connect
     * 
     * Define o nome da tabela a qual a classe fará operações
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'users';
    }

    /**
     * Método index()
     * 
     * Este métodos traz todos os usuários do sistema
     *
     * @return array $userData retorna o array de dados
     */
    public function index(): array
    {
        $sqlUsers = ("SELECT id, CONCAT(name, ' ', lastname) as 'nome' FROM {$this->table}");
        $stmt = $this->connection->prepare($sqlUsers);

        try {
            $stmt->execute();
            $userData = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            $this->log_error('Erro ao buscar usuários: ' . $e->getMessage());
        }

        return $userData;

    }

    /**
     * Método checkUserExist
     * 
     * Este método busca no BD o user que está tentando efetuar login
     * @param mixed $email Email vindo do form para checar usuário no BD
     * @return object|null $userData retorna um objeto com email e senha do usuário encontrado ou null  
     */
    public function checkUserExist($email): ?object
    {   
        $sqlUser = ("SELECT * FROM users WHERE email = :email");
        $stmt = $this->connection->prepare($sqlUser);
        try {
            $stmt->execute([
                'email' => $email
            ]);
            $userData = $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            $this->log_error("Erro ao checar existência de usuário: $email" . $e->getMessage());
        }

        return $userData;

    }
}
