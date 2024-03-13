<?php

namespace app\models;

use app\database\Connect;
use app\traits\SessionMessageTrait;
use PDO;
use PDOException;

/**
 * Classe CategoriesModel
 * 
 * Está classe é responsavel por efetuar operações consultas na table 'categories'
 */
class CategoriesModel extends Connect
{

    use SessionMessageTrait;

    private $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'categories';
    }

    /**
     * Método store()
     * 
     * Este método inseri categorias na tabela 'categories'
     *
     * @param [type] $request OS dados do request
     * @return boolean O retorno da operação
     */
    public function store($request): bool
    {
        $queryInsert = ("INSERT INTO {$this->table}
        (name, type, created_at) 
        VALUES (:name, :type, NOW())
        ");

        $stmt = $this->connection->prepare($queryInsert);
        $success = false;
        
        try {
            $stmt->bindParam(":name", $request->category_name, PDO::PARAM_STR);
            $stmt->bindParam(":type", $request->category_type, PDO::PARAM_INT);
            $stmt->execute();
            $success = true;
        } catch (PDOException $e) {
            $this->log_error("Erro ao inserir categoria: " . $e->getMessage());
            $success = false;
        }

        return $success;
    }
}
