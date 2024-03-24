<?php

namespace app\database;

use PDO;
use PDOException;
use app\traits\SessionMessageTrait;

error_reporting(E_ALL);
ini_set('display_errors', '1');


define('HOST', 'mysql');
define('USER', 'wil');
define('DBNAME', 'asa_sjc');
define('PASSWORD', 'root');


/**
 * Classe Connect
 * 
 * Está classe é responsável pela conexão com o banco de dados 
 */
class Connect
{
    use SessionMessageTrait;

    protected $connection;

    public function __construct()
    {
        $this->connectDatabase();
    }

    /**
     * Método connectDatabase
     * 
     * Este método executa a conexão com o banco de dados
     *
     * @return void
     */
    public function connectDatabase()
    {
        date_default_timezone_set('America/Sao_Paulo');
        
        try {
            $conn = 'mysql:host=' . HOST . ';port=3306;dbname=' . DBNAME; // Alterado para porta 8002
            $this->connection = new PDO($conn, USER, PASSWORD, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->log_error("Erro ao efetuar conexão com banco de dados: " . $e->getMessage());
            die();
        }
    }

}
