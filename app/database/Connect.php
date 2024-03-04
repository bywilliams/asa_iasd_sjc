<?php

namespace app\database;

use PDO;
use PDOException;

error_reporting(E_ALL);
ini_set('display_errors', '1');


define('HOST', 'localhost');
define('USER', 'root');
define('DBNAME', 'asa_sjc');
define('PASSWORD', '');

/**
 * Classe Connect
 * 
 * Está classe é responsável pela conexão com o banco de dados 
 */
class Connect
{
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
            $conn = 'mysql:unix_socket=/opt/lampp/var/mysql/mysql.sock;host=' . HOST . ';dbname=' . DBNAME;
            $this->connection = new PDO($conn, USER, PASSWORD, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            die();
        }
    }
}
