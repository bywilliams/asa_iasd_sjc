<?php

namespace app\models;
use app\database\Connect;
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

    private $table;
    
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
            error_log('Erro ao encontrar alimento: ' . $e->getMessage());
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
        $insertFoodStock = ("INSERT INTO {$this->table}
        (qtde, basic_basket, user_id, food_id, created_at)
        VALUES(
            :qtde, :basic_basket, :user_id, :food_id, :created_at
        )");

        $stmt = $this->connection->prepare($insertFoodStock);
        
        try {
            $stmt->bindParam(':qtde', $request->qtde, PDO::PARAM_INT);
            $stmt->bindParam(':basic_basket', $request->basic_basket, PDO::PARAM_STR_CHAR);
            $stmt->bindParam(':user_id', $request->user_id, PDO::PARAM_INT);
            $stmt->bindParam(':food_id', $request->food_id, PDO::PARAM_INT);
            $stmt->bindParam(':created_at', $request->created_at, PDO::PARAM_STR);
            
            return $stmt->execute();    

        } catch (PDOException $e) {
            error_log('Erro ao inserir estoque '. $e->getMessage());
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
        
        // Pega a quantidade atual do alimento no estoque
        $qtdeFood = ("SELECT qtde FROM {$this->table} WHERE food_id = :food_id");
        $stmt = $this->connection->prepare($qtdeFood);
        $stmt->bindParam(":food_id", $request->food_id, PDO::PARAM_INT);
        $stmt->execute();
        $resultQtde = $stmt->fetch();
       
        $currentQty = $resultQtde->qtde;
        $aditionalQty = $request->qtde;
        $finalQty = $currentQty + $aditionalQty;
        
        if ($resultQtde) { 

            $updateFoodStock = ("UPDATE {$this->table} SET
            qtde = :qtde, updated_at = NOW() WHERE food_id = :food_id");
            $stmt = $this->connection->prepare($updateFoodStock);
    
            try {
                $stmt->bindParam(":qtde", $finalQty, PDO::PARAM_INT);
                $stmt->bindParam(":food_id", $request->food_id, PDO::PARAM_INT);
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                error_log("Erro ao atualizar quantidade do alimento". $e->getMessage());
                return false;
            }
        }


    }

    /**
     * Método latestStockFoods(): traz um últimos alimentos cadastrados em estoque
     * @return array o array com os registros encontrados no BD
     */
    public function latestStockFoods(): array
    {
        $findLatestFood = ("SELECT fs.id, f.name, fs.qtde, fs.basic_basket, fs.created_at, usr.name as 'author'
        FROM $this->table fs
        INNER JOIN users usr ON fs.user_id = usr.id
        INNER JOIN foods f ON fs.food_id = f.id
        ORDER BY id ASC LIMIT 12
        ");

        try {
            $stmt = $this->connection->query($findLatestFood);
            $stmt->execute();
            $listFood = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log('Erro ao buscar os útlimos alimentos '. $e->getMessage());
            $listFood= [];
        }

        return $listFood;

    }

    /**
     * Método calculateBasicBaskets()
     */
    public function calculateBasicBaskets(): int
    {
        $basicBasketRequirements = [
            'Arroz' => ['id' => 1, 'minQtde' => 1],
            'Feijão' => ['id' => 2, 'minQtde' => 3],
            'Macarrão' => ['id' => 3, 'minQtde' => 2],
            'Molho tomate' => ['id' => 4, 'minQtde' => 2],
            'Óleo' => ['id' => 5, 'minQtde' => 2],
            'Açucar' => ['id' => 6, 'minQtde' => 3],
            'Sal' => ['id' => 7, 'minQtde' => 1],
            'Farinha de trigo' => ['id' => 8, 'minQtde' => 1],
            'Flocão' => ['id' => 9, 'minQtde' => 1],
            'Farinha mandioca' => ['id' => 10, 'minQtde' => 1],
            'Fuba' => ['id' => 11, 'minQtde' => 1],
            'Bolacha doce' => ['id' => 12, 'minQtde' => 1],
            'Bolacha Sal' => ['id' => 13, 'minQtde' => 1],
            'Creme dental' => ['id' => 14, 'minQtde' => 2],
            'Sabonete' => ['id' => 15, 'minQtde' => 3],
            'Papel Higiênico' => ['id' => 16, 'minQtde' => 1],
            'Esponja' => ['id' => 17, 'minQtde' => 1],
            'Detergentes' => ['id' => 18, 'minQtde' => 2]
        ];

        $availableBaskets = PHP_INT_MAX; // Começamos assumindo que temos um número ilimitado de cestas

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

        //echo $availableBaskets;
    
        // Se o número de cestas disponíveis é infinito (o que significa que não há alimentos suficientes), retorna  0
        return $availableBaskets == PHP_INT_MAX ?  0 : $availableBaskets;
        
    }


}