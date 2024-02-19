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
class FoodModel extends Connect
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
        $this->table = 'foods';
    }

    /**
     * Método findAllFoods()
     * 
     * Este método traz uma lista de alimentos para cadastro em estoque posteriormente
     *
     * @return array $listFood O array de alimentos vindos do banco de dados 
     */
    public function findAllFoods(): array
    {
        $findLatestFood = ("SELECT f.id, f.name, f.unit, f.created_at, usr.name as 'author'
        FROM $this->table f
        INNER JOIN users usr ON f.user_id = usr.id
        ORDER BY id ASC
        ");
        $stmt = $this->connection->query($findLatestFood);

        try {
            $stmt->execute();
            $listFood = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo $e->getMessage();
            die;
        }

        return $listFood;

    }

    public function store($request)
    {
        $insertFood = ("INSERT INTO alimentos
        (name, qtde, user_id, created_at)
        VALUES(
            :name, :qtde, :user_id, :created_at
        )");

        $stmt = $this->connection->prepare($insertFood);
        
        try {
            $stmt->execute([
                'name' => $request->name,
                'qtde' => $request->qtde,
                'user_id' => $request->user_id,
                'created_at' => $request->created_at
            ]);
            return true;        
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }


}