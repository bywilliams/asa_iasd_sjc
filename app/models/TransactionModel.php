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
     * Método getRevenueCategories()
     * 
     * Este Método traz as categorias de transação para receitas
     *
     * @return object $resultCategories objetos de dados das categorias 
     */
    public function getRevenueCategories(): array 
    {
        $categoriesQuery = ("SELECT id, name FROM categories WHERE type = '1' ORDER BY id ASC");
        $stmt = $this->connection->query($categoriesQuery);

        try {
            $stmt->execute();
            $resultCategories = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Erro ao trazer categorias";
            error_log('Erro ao trazer categorias '. $e->getMessage());
        }

        return $resultCategories;

    }

     /**
     * Método getExpenseCategories()
     * 
     * Este Método traz as categorias de transação para despesas
     *
     * @return object $resultCategories objetos de dados das categorias 
     */
    public function getExpenseCategories() 
    {
        $categoriesQuery = ("SELECT id, name FROM categories WHERE type = '2' ORDER BY id ASC");
        $stmt = $this->connection->query($categoriesQuery);

        try {
            $stmt->execute();
            $resultCategories = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Erro ao trazer categorias";
            error_log('Erro ao trazer categorias '. $e->getMessage());
        }

        return $resultCategories;

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
            echo "Erro ao inserir transação";;
            // error_log($e->getMessage());
            return false;
        }
    }

}