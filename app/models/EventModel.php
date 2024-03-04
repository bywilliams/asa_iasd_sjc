<?php

namespace app\models;

use app\database\Connect;
use app\traits\SessionMessageTrait;
use PDO;
use Exception;
use PDOException;
use DateTime;

class EventModel extends Connect
{
    use SessionMessageTrait;
    private $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'events';
    }

    public function store($request): bool
    {
        $insertEvent = ("INSERT INTO {$this->table}
        (name, place, description, event_date, user_id, created_at)
        VALUES (
            :name, :place, :description, :event_date, :user_id, NOW()
        )");

        // Analisa a string da data
        $date = DateTime::createFromFormat('d/m/Y H:i:s', $request->event_date);

        // Checa se a analise foi concluída com sucesso
        if ($date === false) {
            throw new Exception("Failed to parse date string.");
        } else {
            $formatedDate = $date->format('Y-m-d H:i:s');
        }

        $stmt = $this->connection->prepare($insertEvent);

        try {
            $stmt->execute([
                ":name" => $request->name,
                ":place" => $request->place,
                ":description" => $request->description,
                ":event_date" => $formatedDate,
                ":user_id" => $request->user_id
            ]);
            return true;
        } catch (PDOException $e) {
            $this->log_error("Erro ao cadastrar evento: " . $e->getMessage());
            return false;
        }
    }

    public function latestEvents()
    {
        $sql = "SELECT id, name, event_date FROM {$this->table} ORDER BY event_date LIMIT 4";
        $stmt = $this->connection->query($sql);
        try {
            $stmt->execute();
            $listEvents = $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            $this->log_error("Erro ao trazer últimos eventos cadastrados: " . $e->getMessage());
            $listEvents = [];
        }

        return $listEvents;
    }
}
