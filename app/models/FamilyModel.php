<?php 
namespace app\models;
use app\database\Connect;
use PDOException;

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




} 