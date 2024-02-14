<?php

namespace app\models;
use app\database\Connect;
use PDO;
use PDOException;

/**
 * Classe TransactionModel
 * 
 * Esta classe (Model) é responsável por realizar operações CRUD na tabela 'transactions'
 * 
 * A Classe herda a conexão com o banco de dados da classe Connect
 */
class TransactionModel extends Connect
{

    private $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'transactions';
    }

    /**
     * Método store()
     * 
     * Este método é responsável por inserir registros de transações (Receita ou Despesa)
     * 
     * @param object $request objeto contem os dados a inserir
     * @return bool True em caso de sucesso e False em caso de erro
     */
    public function store(object $request): bool
    {

        // var_dump($request); die;
        $insertTransaction = ("INSERT INTO {$this->table}
            (title, description, value, type, category_id, user_id, created_at)
        VALUES (
            :title, :description, :value, :type, :category_id, :user_id, :created_at    
        )");
        $stmt = $this->connection->prepare($insertTransaction);

        try{
            $stmt->bindParam(':title', $request->title, PDO::PARAM_STR);
            $stmt->bindParam(':description', $request->description, PDO::PARAM_STR);
            $stmt->bindParam(':value', $request->value, PDO::PARAM_INT);
            $stmt->bindParam(':type', $request->type, PDO::PARAM_INT);
            $stmt->bindParam(':category_id', $request->category_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $request->user_id, PDO::PARAM_INT);
            $stmt->bindParam(':created_at', $request->created_at, PDO::PARAM_STR);
            
            return $stmt->execute();
            
        } catch(PDOException $e) {
            echo $e->getMessage();
            // error_log($e->getMessage());
            return false;
        }
    }

}