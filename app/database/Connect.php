<?php

namespace app\database;

use PDO;
use PDOException;

define('HOST', 'localhost');
define('USER', 'root');
define('DBNAME', 'asa_sjc');
define('PASSWORD', '');

class Connect
{
    protected $connection;

    public function __construct()
    {
        $this->connectDatabase();
    }

    public function connectDatabase()
    {

        date_default_timezone_set('America/Sao_Paulo');
        
        try {
            $conn = 'mysql:unix_socket=/opt/lampp/var/mysql/mysql.sock;hosd=' . HOST . ';dbname=' . DBNAME;
            $this->connection = new PDO($conn, USER, PASSWORD, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            die();
        }

        $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }
}
