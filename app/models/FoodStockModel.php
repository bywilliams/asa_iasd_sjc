<?php

namespace app\models;

use app\database\Connect;
use app\traits\SessionMessageTrait;
use Exception;
use PDO;
use PDOException;

/**
 * Classe FoodModel
 * 
 * Está classe é responsável por efetuar operações de CRUD na tabela 'alimentos'
 * 
 * A Classe herda a conexão com o banco de dados da classe Connect
 */
class FoodStockModel extends Connect
{
    use SessionMessageTrait;

    private $table;
    private $basicBasketRequirements = [
        'Arroz' => ['id' => 1, 'minQtde' => 1],
        'Feijão' => ['id' => 2, 'minQtde' => 3],
        'Macarrão' => ['id' => 3, 'minQtde' => 2],
        'Molho tomate' => ['id' => 4, 'minQtde' => 2],
        'Óleo' => ['id' => 5, 'minQtde' => 2],
        'Açucar' => ['id' => 6, 'minQtde' => 2],
        'Sal' => ['id' => 7, 'minQtde' => 1],
        'Farinha de trigo' => ['id' => 8, 'minQtde' => 1],
        'Flocão' => ['id' => 9, 'minQtde' => 1],
        'Farinha mandioca' => ['id' => 10, 'minQtde' => 1],
        'Fuba' => ['id' => 11, 'minQtde' => 1],
        'Bolacha doce' => ['id' => 12, 'minQtde' => 1],
        'Bolacha Sal' => ['id' => 13, 'minQtde' => 1],
    ];

    /**
     * Construtor da classe FoodModel
     * 
     * Inicializa a classe FoodModel, permite criar uma instância do construtor da classe Connect
     * 
     * Define o nome da tabela a qual a classe fará operações
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'foods_stock';
    }

    /**
     * Método index()
     * 
     * Este método traz todos os  alimentos cadastrados no sistema por padrão ou por paginação
     *
     * @param [type] $inicio
     * @param [type] $itensPorPagina
     * @param [type] $sql
     * @return array|null
     */
    public function index($inicio, $itensPorPagina, $sql): ?array
    {
        $selectStockFoods = ("SELECT fs.id, fs.qtde, fs.user_id, fs.created_at, fs.updated_at, f.name, CONCAT(usr.name , ' ', usr.lastname) as author   
        FROM {$this->table} fs
        INNER JOIN foods f ON fs.food_id = f.id
        INNER JOIN users usr ON fs.user_id = usr.id
        WHERE fs.id > 0 $sql
        ORDER BY id LIMIT $inicio, $itensPorPagina");
        $totalRegistros = 0;
        try {
            $stmt = $this->connection->query($selectStockFoods);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);

            $countRows = $this->connection->query("SELECT COUNT(*) as total FROM {$this->table} fs WHERE fs.id > 0 $sql");
            $totalRegistros = $countRows->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            $this->log_error('Erro ao buscar famílias ' . $e->getMessage());
            $result = [];
        }

        return array(
            'data' => $result,
            'totalRegistros' => $totalRegistros
        );
    }

    /**
     * Método findFoodById
     * 
     * Este método busca se um determinado alimento já existe na tabela 'foods_stock' através do id
     *
     * @param object $request Os dados do request que inclui junto o food_id
     * @return object $food o objeto alimento em si
     */
    public function findFoodById(object $request): ?object
    {
        $selectFood = ("SELECT * FROM {$this->table} WHERE food_id = :food_id");
        $stmt = $this->connection->prepare($selectFood);
        $stmt->bindParam(":food_id", $request->food_id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            $food = $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            $this->log_error('Erro ao encontrar alimento: ' . $e->getMessage());
            return null;
        }

        return $food ?: null;
    }


    /**
     * Método store(): Insere uma quantidade de um determinado alimento no estoque
     * 
     * @param object $request objeto contendo os dados para inserir
     * @return bool True em caso de sucesso, false em caso de erro 
     */
    public function store(object $request): bool
    {
        $queryInsert = ("INSERT INTO {$this->table}
        (qtde, basic_basket, user_id, food_id, created_at)
        VALUES(
            :qtde, :basic_basket, :user_id, :food_id, :created_at
        )");

        $stmt = $this->connection->prepare($queryInsert);

        try {
            $stmt->bindParam(':qtde', $request->qtde, PDO::PARAM_INT);
            $stmt->bindParam(':basic_basket', $request->basic_basket, PDO::PARAM_STR_CHAR);
            $stmt->bindParam(':user_id', $request->user_id, PDO::PARAM_INT);
            $stmt->bindParam(':food_id', $request->food_id, PDO::PARAM_INT);
            $stmt->bindParam(':created_at', $request->created_at, PDO::PARAM_STR);

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            $this->log_error('Erro ao inserir estoque ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Método udpateFoodStock()
     * 
     * Este método atuializa a quandidade de um alimento na tabela 'foods_stock'
     *
     * @param object $request O request de dados vindos do Form
     * @return boolean|null retorna um boolean de true or false para sucesso ou erro ou null
     */
    public function updateFoodStock(object $request): ?bool
    {
        try {
            // Pega a quantidade atual do alimento no estoque
            $query = ("SELECT qtde FROM {$this->table} WHERE food_id = :food_id");
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(":food_id", $request->food_id, PDO::PARAM_INT);
            $stmt->execute();
            $currentQty = $stmt->fetchColumn();
            
            if ($currentQty >= 0) {
                $aditionalQquantity = $request->qtde;
                $finalQty = $currentQty + $aditionalQquantity;

                $updateQuery = ("UPDATE {$this->table} SET
                qtde = :qtde, updated_at = NOW() WHERE food_id = :food_id");
                $updateStmt = $this->connection->prepare($updateQuery);
                $updateStmt->bindParam(":qtde", $finalQty, PDO::PARAM_INT);
                $updateStmt->bindParam(":food_id", $request->food_id, PDO::PARAM_INT);
                
                if($updateStmt->execute()) {
                    return true;
                } else {
                    $this->log_error("Erro ao atualizar quantidade do alimento" . implode(", ", $updateStmt->errorInfo()));
                    return false;
                }
                
            } else {
                $this->log_error("Alimento não encontrado no estoque");
                return false;
            }

        } catch (PDOException $e) {
            $this->log_error("Database error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Método latestStockFoods(): traz um últimos alimentos cadastrados em estoque
     * @return array o array com os registros encontrados no BD
     */
    public function latestStockFoods(): array
    {
        $findLatestFood = ("SELECT fs.id, f.name, fs.qtde, fs.basic_basket, fs.food_id, fs.created_at, fs.updated_at, usr.name as 'author'
        FROM $this->table fs
        INNER JOIN users usr ON fs.user_id = usr.id
        INNER JOIN foods f ON fs.food_id = f.id
       
        ORDER BY id ASC LIMIT 21
        ");
        $stmt = $this->connection->query($findLatestFood);

        try {
            $stmt->execute();
            $listFood = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            $this->log_error('Erro ao buscar os útlimos alimentos ' . $e->getMessage());
            $listFood = [];
        }

        return $listFood;
    }

    /**
     * Método calculateBasicBaskets()
     */
    public function calculateBasicBaskets(): int
    {

        $availableBaskets = PHP_INT_MAX; // Assumimos que temos um número ilimitado de cestas

        $basicBasketRequirements = $this->basicBasketRequirements;

        foreach ($basicBasketRequirements as $foodName => $requirement) {
            $stmt = $this->connection->prepare("SELECT SUM(qtde) AS totalQuantity FROM {$this->table} WHERE food_id = :foodId AND basic_basket = 'S'");
            $stmt->bindValue(':foodId', $requirement['id']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            $totalQuantity = $result->totalQuantity ??  0;

            // Calcular quantas vezes a quantidade total pode ser dividida pela quantidade mínima necessária
            $timesMinQuantityCanBeDivided = floor($totalQuantity / $requirement['minQtde']);

            // Atualizar o número de cestas disponíveis com o menor número encontrado
            $availableBaskets = min($availableBaskets, $timesMinQuantityCanBeDivided);
        }

        // Se o número de cestas disponíveis é infinito (o que significa que não há alimentos suficientes), retorna  0
        return $availableBaskets == PHP_INT_MAX ?  0 : $availableBaskets;
    }

    public function donatedBasket($family_id): bool
    {
       
        $foodStock = $this->latestStockFoods(); // Estoque de alimentos atual
        $success = false; // Controla o sucesso da operação

        foreach ($this->basicBasketRequirements as $foodRequirement) {
           
            foreach ($foodStock as $stockItem) {

                if ($foodRequirement['id'] == $stockItem->food_id) {
                    
                    $currentQty = $stockItem->qtde;
                    $valueToSubtracted = $foodRequirement['minQtde'];

                    $newQty = $currentQty - $valueToSubtracted;

                    $updateFoodStock = "UPDATE {$this->table} SET qtde = :qtde WHERE food_id = :food_id";

                    $stmt = $this->connection->prepare($updateFoodStock);
                    
                    try {
                        $stmt->bindParam(":qtde", $newQty, PDO::PARAM_INT);
                        $stmt->bindParam(":food_id", $stockItem->food_id, PDO::PARAM_INT);
                        $stmt->execute();
                        $success = true;
                    } catch (PDOException $e) {
                        $this->log_error('Erro ao doar cesta: ' . $e->getMessage());
                        $success = false;
                    }
                }
            }
        }

        // registra para quem a cesta foi doada na tabela donated_basket 
        $this->registerBasketBeneficiary($family_id);

        return $success;
    }

    /**
     * Método registerBasketBeneficiary()
     * 
     * Este método insere no banco de dados um registro com id da família que recebeu a cesta juntamente com a data da entrega
     *
     * @param [type] $family_id O id da família beneficiaria
     * @return boolean true or false em caso de sucesso ou erro
     */
    public function registerBasketBeneficiary($family_id): bool 
    {
        $queryRegister = "INSERT INTO donated_baskets (family_id, donation_date) VALUES (:family_id, NOW())";
        $stmt = $this->connection->prepare($queryRegister);
        $stmt->bindParam(":family_id", $family_id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            $this->log_error("Erro ao registrar histórico de cesta básica doada. ". $e->getMessage());
            return false;
        }
    }

    /**
     * Método getTotalFoods()
     * 
     * Este método traz o total de alimentos em estoque
     * 
     * retunr int O total de alimentos no estoque
     */
    public function getTotalFoods(): int
    {
        $sql = ("SELECT SUM(qtde) as total FROM {$this->table}");
        $stmt = $this->connection->prepare($sql);

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            return (int) ($result->total ?? 0);
        } else {
            throw new Exception('Failed to execute query: ' . implode(', ', $stmt->errorInfo()));
        }
    }
}
