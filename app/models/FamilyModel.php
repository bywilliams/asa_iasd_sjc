<?php

namespace app\models;

use app\database\Connect;
use PDOException;
use Exception;
use PDO;

/**
 * Classe FamilyModel 
 * 
 * Está classe é responsável por efetuar operações de banco na tabela 'familias'
 * 
 * A classe herda a conexão com o banco de dados da classe Connect
 */
class FamilyModel extends Connect
{

    private $table;

    /**
     * Construtor da classe FamilyModel
     * 
     * Inicializa a classe FamilyModel, permite criar uma instância do construtor da classe Connect
     * 
     * Define o nome da tabela a qual a classe fará operações
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'families';
    }

    /**
     * Método index()
     * 
     * Este método traz todas as famílias cadastradas no sistema
     *
     * @return array|null Retorno pode ser um array ou null
     */
    public function index($inicio, $itensPorPagina, $sql): ?array
    {
        $selectFamilies = ("SELECT f.id, f.full_name, f.address,f.gender, f.age, f.criteria_id, f.obs, f.job, f.qtde_childs, f.contact, f.created_at, f.updated_at, sf.name AS 'situacao'  
        FROM {$this->table} f 
        INNER JOIN sits_family sf ON f.sits_family_id = sf.id 
        WHERE f.id > 0 $sql
        ORDER BY f.id LIMIT $inicio, $itensPorPagina");
        $totalRegistros = 0;

        //echo $selectFamilies; die;
        try {
            $stmt = $this->connection->query($selectFamilies);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);

            $countFamilies = $this->connection->query("SELECT COUNT(*) as total FROM {$this->table} f WHERE f.id > 0 $sql");
            $totalRegistros = $countFamilies->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            error_log('Erro ao buscar famílias ' . $e->getMessage());
            $result = [];
        }

        return array(
            'data' => $result,
            'totalRegistros' => $totalRegistros
        );
    }

    /**
     * Método store(): Insere uma familia na tabela 'family'
     * 
     * @param object $request objeto contendo os dados para inserir
     * @return bool True em caso de sucesso, false em caso de erro 
     */
    public function store($request): bool
    {

        $insertFamily = ("INSERT INTO {$this->table}
        (full_name, address, qtde_childs, gender, contact, job, sits_family_id, age, obs, criteria_id, user_id, created_at)
        VALUES(
            :full_name, :address, :qtde_childs, :gender, :contact, :job, 1, :age, :obs, :criteria_id, :user_id, NOW()
        )");

        $stmt = $this->connection->prepare($insertFamily);

        try {
            $stmt->execute([
                ":full_name" => $request->fullname,
                ":address" => $request->address,
                ":qtde_childs" => $request->qtde_childs,
                ":gender" => $request->gender,
                ":contact" => $request->contact,
                ":job" => $request->job,
                ":age" => $request->age,
                ":obs" => $request->schedule,
                ":criteria_id" => $request->criteria_id,
                ":user_id" => $request->user_id,
            ]);

            return true;
        } catch (PDOException $e) {
            error_log('Erro ao inserir na tabela familias: ' . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data)
    {
        // Prepare the SELECT statement to check if famly exists
        $selectedfamily = $this->connection->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $selectedfamily->bindParam(":id", $id, PDO::PARAM_INT);
        $selectedfamily->execute();

        if ($selectedfamily->rowCount() > 0) {
            $familyData = $selectedfamily->fetch(PDO::FETCH_OBJ);

            // Prepare the update statment
            $updateStatement = "UPDATE {$this->table} SET ";
            $setPart = [];
            $params = [];
            foreach ($data as $key => $value) {
                if ($key != "csrf_token"  && $key != "_METHOD") {
                    $setPart[] = "$key = :$key";
                    $params[":$key"] = $value;
                }
            }
            $updateStatement .= implode(", ", $setPart) . ", updated_at = NOW() WHERE id = :id";

            // Prepare and Execute the UPDATE statement
            $updateFamily = $this->connection->prepare($updateStatement);
            $params[':id'] = $id;

            foreach ($params as $param => $value) {
                $updateFamily->bindValue($param, $value);
            }
            $updateSuccess = $updateFamily->execute();

            if ($updateSuccess) {
                return true;
            } else {
                error_log("Falha ao atualizar família com ID $id");
                return false;
            }
        } else {
            error_log("Não foi possível achar esta família.");
            return false;
        }
    }

    /**
     * Método checkFamilyExist()
     * 
     * Este método checa se uma família já existe no banco de dados antes de ser inserida pelo método store
     *
     * @param [type] $contact o telefone de contato informado
     * @return boolean true or false caso já exista ou não
     */
    public function checkFamilyExist($contact): bool
    {
        $familyExist = ("SELECT id FROM $this->table WHERE contact = :contact");
        $stmt = $this->connection->prepare($familyExist);
        $stmt->bindParam(":contact", $contact);

        try {

            $stmt->execute();
            if ($stmt->fetchColumn()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Método getTotalActiveFamilies()
     * 
     * Este método traz o total de familias cadastradas e ativas
     *
     * @return int O total de famíalias ativas
     */
    public function getTotalActiveFamilies(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->connection->prepare($sql);

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            return (int)($result->total ?? 0);
        } else {
            throw new Exception('Failed to execute query: ' . implode(', ', $stmt->errorInfo()));
        }
    }
}
