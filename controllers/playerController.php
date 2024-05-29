<?php
require_once dirname(__DIR__) . '/models/player.php'; // Asegúrate de especificar la ruta correcta

class PlayerController {

    public function handleRequest() {
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'getPlayersByGender':
                    $data = json_decode(file_get_contents('php://input'), true);

                    if (isset($data['genero'])) {
                        $gender = $data['genero'];
                        $players = $this->getPlayersByGender($gender);
                        echo json_encode($players);
                    } else {
                        echo json_encode(['error' => "Error: Falta el parámetro 'genero'."]);
                    }
                    break;
                default:
                    echo json_encode(['error' => "Error: Acción desconocida."]);
                    break;
            }
        } else {
            echo json_encode(['error' => "Error: No se ha especificado ninguna acción."]);
        }
    }

    public function getPlayersByGender($gender) {
        $player = new Player();
        return $player->getPlayersByGender($gender);
    }
}

$controller = new PlayerController();
$controller->handleRequest();
?>
