<?php
require_once dirname(__DIR__) . '/models/db.php'; 

class PlayerForTournament extends DB {
    private $tournament_id;
    private $player_id;

    public function __construct($tournament_id, $player_id) {
        $this->tournament_id = $tournament_id;
        $this->player_id = $player_id;
        $this->connect();
    }

    public function insert() {
        $query = "INSERT INTO playerForTournament (tournament_id, player_id) VALUES ('$this->tournament_id', '$this->player_id')";
        $result = $this->query($query);
        $response = true;

        if (!$result) {
            $response = false;
        }
        $this->close();
        return $response;
    }
}
?>
