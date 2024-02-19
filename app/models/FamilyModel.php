<?php 
namespace app\models;
use app\database\Connect;
use PDOException;
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
        $selectFamilies = ("SELECT id, full_name, address, qtde_childs, contact, created_at, updated_at 
        FROM {$this->table}
        WHERE id > 0 $sql
        ORDER BY id LIMIT $inicio, $itensPorPagina");
        
        try {
            $stmt = $this->connection->query($selectFamilies);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);

            $countFamilies = $this->connection->query("SELECT COUNT(*) as total FROM {$this->table}");
            $totalRegistros = $countFamilies->fetch(PDO::FETCH_ASSOC)['total'];

        } catch (PDOException $e) {
            error_log('Erro ao buscar famílias '. $e->getMessage());
            $result = [];
        }

        return array(
            'data'=> $result,
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

    public function checkFamilyExist($contact): bool
    {
        $familyExist = ("SELECT id FROM $this->table WHERE contact = :contact");
        $stmt = $this->connection->prepare($familyExist);
        $stmt->bindParam(":contact", $contact);

        try {
            $stmt->execute();
            if($stmt->fetchColumn()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }




} 