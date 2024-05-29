<?php
require_once dirname(__DIR__) . '/models/db.php'; 
require_once dirname(__DIR__) . '/models/matches.php'; 
require_once dirname(__DIR__) . '/models/playersForTournament.php'; 


class Tournament extends DB {
    public $gender;
    public $quantityPlayers;
    public $winner;
    public $dateStart;
    public $players;

    public function __construct($gender=null, $quantityPlayers=null, $players=null) {
        $this->gender = $gender;
        $this->quantityPlayers = $quantityPlayers;
        $this->dateStart = date('Y-m-d'); 
        $this->winner = null;
        $this->players = $players;
    }

    public function setWinner($idWinner, $idTournament) {

        $this->connect();
        $query = "UPDATE tournament SET winner = $idWinner WHERE id = $idTournament;";
        $result = $this->query($query);
        $response = true;

        if (!$result) {
            $response = false;
        }

        $this->close();

        return $response;
    }

    public function createTournament() {
        $playerIdsArray = explode(",", $this->players);
    
        $this->connect();
        $query = "INSERT INTO tournament ( gender, quantityPlayers, dateStart) VALUES ('$this->gender', '$this->quantityPlayers', '$this->dateStart')";
        $result = $this->query($query);
        $lastId = $this->last_id();
        
        if (!$result) {
            return false;
        }

        $this->close();

        return $lastId;
    }

    function getNextTournamentNumber() {
        $directoryPath = __DIR__ . '/../db/tournaments/';
    
        if (!is_dir($directoryPath)) {
            return 1;
        }
    
        $files = scandir($directoryPath);
    
        $highestIndex = 0;
    
        foreach ($files as $file) {
            if (preg_match('/(\d+)\.json$/', $file, $matches)) {
                $index = (int) $matches[1];
                if ($index > $highestIndex) {
                    $highestIndex = $index;
                }
            }
        }
    
        return $highestIndex + 1;
    }
     
}

?>
