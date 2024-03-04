<?php

namespace app\models;
use app\traits\SessionMessageTrait;
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
    use SessionMessageTrait;

    private $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'transactions';
    }

    /**
     * Método getExpenseCategories()
     * 
     * Este Método traz as categorias de transação para despesas
     *
     * @return array $resultCategories objetos de dados das categorias 
     */
    public function getExpenseCategories(): array 
    {
        $categoriesQuery = ("SELECT id, name FROM categories WHERE type = '2' ORDER BY id ASC");
        $stmt = $this->connection->query($categoriesQuery);

        try {
            $stmt->execute();
            $resultCategories = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            $this->log_error('Erro ao trazer categorias' . $e->getMessage());
        }

        return $resultCategories;

    }

    /**
     * Método getRevenueCategories()
     * 
     * Este Método traz as categorias de transação para receitas
     *
     * @return array $resultCategories objetos de dados das categorias 
     */
    public function getRevenueCategories(): array 
    {
        $categoriesQuery = ("SELECT id, name FROM categories WHERE type = '1' ORDER BY id ASC");
        $stmt = $this->connection->query($categoriesQuery);

        try {
            $stmt->execute();
            $resultCategories = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            $this->log_error("Erro ao trazer categorias" . $e->getMessage());
        }

        return $resultCategories;

    }

     /**
     * Método getTotalTransactions()
     * 
     * Este método traz o total de receitas ou de despesas da aplicação de acordo com o type(enum) usado
     *
     * @return string|null $row->total O total de receitas|despesas ou null
     */
    public function getTotalTransactionsByType($type) : ?string
    {
        $sql = ("SELECT SUM(value) as 'total' FROM {$this->table} WHERE type = :type");
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(":type", $type);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        return number_format($row->total ?? 0, 2,",",".");
    }

    public function getTotalBalance(): ?string
    {
        $sql = ("SELECT COALESCE(
            (SELECT SUM(value) as 'total' FROM {$this->table} 
            WHERE type = 'receita'), 0)
            -
            COALESCE(
            (SELECT SUM(value) as 'total' FROM {$this->table} 
            WHERE type = 'despesa'), 0)
            AS 'total_balance';
        ");
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        return number_format($row->total_balance ?? 0,2,",",".");
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

        $value = $value = str_replace([".", ","], "", $request->value);
        $finalValue = substr_replace($value, ".", -2, 0); // Insere o ponto na posição correta para as casas decimais

        try{
            $stmt->bindParam(':title', $request->title, PDO::PARAM_STR);
            $stmt->bindParam(':description', $request->description, PDO::PARAM_STR);
            $stmt->bindParam(':value', $finalValue, PDO::PARAM_INT);
            $stmt->bindParam(':type', $request->type, PDO::PARAM_INT);
            $stmt->bindParam(':category_id', $request->category_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $request->user_id, PDO::PARAM_INT);
            $stmt->bindParam(':created_at', $request->created_at, PDO::PARAM_STR);
            
            return $stmt->execute();
            
        } catch(PDOException $e) {
            $this->log_error("Erro ao inserir transação" . $e->getMessage());
            return false;
        }
    }

}