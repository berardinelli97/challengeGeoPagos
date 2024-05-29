<?php
require_once dirname(__DIR__) . '/models/db.php'; 

class Matches extends DB {
    public $id;
    public $tournament_id;
    public $player1_id;
    public $player2_id;
    public $winner_id;
    public $round;

    public function __construct($tournament_id, $player1_id, $player2_id, $winner_id = null, $round = null) {
        $this->tournament_id = $tournament_id;
        $this->player1_id = $player1_id;
        $this->player2_id = $player2_id;
        $this->winner_id = $winner_id;
        $this->round = $round;
        $this->connect();
    }

    public function insert() {
        $query = "INSERT INTO matches (tournament_id, player1_id, player2_id, winner_id, round) VALUES ('$this->tournament_id', '$this->player1_id', '$this->player2_id', '$this->winner_id', '$this->round')";

        $result = $this->query($query);
        $response = true;

        if (!$result) {
            $response = false;
        }

        $this->close();

        return $response;
    }

    public function getMatchesByTournament($tournament_id) {
        $this->connect();
        $sql = "SELECT * FROM matches WHERE tournament_id = " . $this->connection->real_escape_string($tournament_id);
        $result = $this->query($sql);
        $matches = [];
        while ($row = $this->fetch($result)) {
            // Creamos un nuevo objeto Match con los datos de la fila y lo agregamos al array $matches
            $matches[] = new Matches($row['id'], $row['tournament_id'], $row['player1_id'], $row['player2_id'], $row['winner_id'], $row['round']);
        }
        // Retornamos el array de partidos
        return $matches;
    }

    public function saveResult($match_id, $winner_id) {
        $this->connect();
        $sql = "UPDATE matches SET winner_id = " . $this->connection->real_escape_string($winner_id) . " WHERE id = " . $this->connection->real_escape_string($match_id);
        $this->query($sql);
        // Verificamos si hubo algún error al ejecutar la consulta
        if ($this->connection->errno) {
            return false; // Hubo un error
        } else {
            return true; // Éxito
        }
    }
}

?>
