<?php
require_once dirname(__DIR__) . '/models/tournament.php'; 
require_once dirname(__DIR__) . '/models/player.php'; 
require_once dirname(__DIR__) . '/models/matches.php'; 

class TournamentController {
    public function handleRequest() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $action = $_POST['action'];
            $quantityPlayers = $_POST['cantidadJugadores'];
            $gender = $_POST['genero'];
            $playersId = $_POST['playersId'];

            switch($action){
                case 'createTournament':
                    $idTournament = $this->createTournament($gender, $quantityPlayers, $playersId);
                    $this->playersForTournament($idTournament, $playersId);
                    $championMessage = $this->createMatches($idTournament, $playersId, $gender);

                    header('Content-Type: application/json');
                    echo json_encode(['message' => $championMessage]);
                    exit();
            }
        }
    }

    public function createTournament($gender, $quantityPlayers, $playersId) {
        $tournament = new Tournament($gender, $quantityPlayers, $playersId);
        return $tournament->createTournament();
    }

    public function playersForTournament($idTournament, $playersId){
        $playerModel = new Player();
        $players = [];
        $playersId = json_decode($playersId);
        foreach ($playersId as $playerId) {
            $players[] = $playerModel->getPlayerById($playerId);
        }

        foreach($players as $player){
            $playersForTournament = new PlayerForTournament($idTournament, $player['id']);
            $response = $playersForTournament->insert();
        }

        return $response;
    }

    public function createMatches($idTournament, $playersId, $gender){
        $playersId = json_decode($playersId);
        $round = 1;

        while (count($playersId) > 1) {
            shuffle($playersId);

            $pairs = array_chunk($playersId, 2);

            $winners = [];

            foreach ($pairs as $pair) {
                if (count($pair) == 2) { 
                    $winnerIndex = array_rand($pair);

                    $winnerId = $this->determineWinner($pair[0], $pair[1], $gender);
                    $winners[] = $winnerId;

                    $match = new Matches($idTournament, $pair[0], $pair[1], $winnerId, $round);
                    $response = $match->insert();
                } else {
                    // Si es impar, agrega el ultimo jugador directamente a la proxima ronda
                    $winners[] = $pair[0];
                }
            }

            $playersId = $winners;

            $round++;
        }

        if (count($playersId) == 1) {
            $championId = $playersId[0];
            $player = new Player();
            $winner = $player->getPlayerById($championId);

            $tournament = new Tournament();
            $tournament->setWinner($winner['id'], $idTournament);

            return $winner['name'];
        }
    }

    public function determineWinner($idP1, $idP2, $gender) {

        $player = new Player();
        $player1 = $player->getPlayerById($idP1);
        $player2 = $player->getPlayerById($idP2);
        $luck1 = rand(0, 100);
        $luck2 = rand(0, 100);

        
        if($gender == 'Masculino'){
            $params1 = ['strength' => $player1['strength'], 'speed' => $player1['speed']];
            $params2 = ['strength' => $player2['strength'], 'speed' => $player2['speed']];

            $pointsPlayer1 = 0;
            $pointsPlayer2 = 0;

            $aspects = ['strength', 'speed'];
            foreach ($aspects as $aspect) {
                if ($params1[$aspect] > $params2[$aspect]) {
                    $pointsPlayer1++;
                } elseif ($params1[$aspect] < $params2[$aspect]) {
                    $pointsPlayer2++;
                }
            }

            if ($luck1 > $luck2) {
                $pointsPlayer1++;
            } elseif ($luck1 < $luck2) {
                $pointsPlayer2++;
            }

            if ($pointsPlayer1 == $pointsPlayer2) {
                $finalLuck = rand(0, 1);
                if ($finalLuck == 0) {
                    $pointsPlayer1++;
                } else {
                    $pointsPlayer2++;
                }
            }

            if ($pointsPlayer1 > $pointsPlayer2) {
                $winner = $idP1;
            } else {
                $winner = $idP2;
            }

        }else{
            $params1 = ['reaction' => 80];
            $params2 = ['reaction' => 85];
            
            $pointsPlayer1 = 0;
            $pointsPlayer2 = 0;
            
            if ($params1['reaction'] > $params2['reaction']) {
                $pointsPlayer1++;
            } elseif ($params1['reaction'] < $params2['reaction']) {
                $pointsPlayer2++;
            }
            
            if ($luck1 > $luck2) {
                $pointsPlayer1++;
            } elseif ($luck1 < $luck2) {
                $pointsPlayer2++;
            }
            
            if ($pointsPlayer1 == $pointsPlayer2) {
                $finalLuck = rand(0, 1);
                if ($finalLuck == 0) {
                    $pointsPlayer1++;
                } else {
                    $pointsPlayer2++;
                }
            }
            
            if ($pointsPlayer1 > $pointsPlayer2) {
                $winner = $idP1;
            } else {
                $winner = $idP2;
            }
        }

        return  $winner;
    }
}

$controller = new TournamentController();
$controller->handleRequest();
?>
