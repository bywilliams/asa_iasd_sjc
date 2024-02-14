<?php

namespace app\models;

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
     * Método checkUserExist
     * 
     * Este método busca no BD o user que está tentando efetuar login
     * @param mixed $email Email vindo do form para checar usuário no BD
     * @return array $userData retorna um array com email e senha do usuário encontrado 
     */
    function checkUserExist($email) : object
    {
        $sqlUser = $this->connection->prepare("SELECT id, name, lastname, email, password, access_level_id FROM users WHERE email = :email");
        try {
            $sqlUser->execute([
                'email' => $email
            ]);
            $userData = $sqlUser->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo $e->getMessage();
            die;
        }
        
        return $userData;
    }
}
