<?php
require_once dirname(__DIR__) . '/models/db.php'; 

class Player extends DB {
    public $id;
    public $name;
    public $birth_date;
    public $gender;
    public $ability;
    public $strength;
    public $speed;
    public $reaction;
    public $country;
    
    public function __construct($id=null, $name=null, $birth_date=null, $gender=null, $ability=null, $strength=null, $speed=null, $reaction=null, $country=null) {
        $this->id = $id;
        $this->name = $name;
        $this->birth_date = $birth_date;
        $this->gender = $gender;
        $this->ability = $ability;
        $this->strength = $strength;
        $this->speed = $speed;
        $this->reaction = $reaction;
        $this->country = $country;
    }

    public function getPlayersByGender($gender) {
        $db = new DB();
        $db->connect();

        $sql = "SELECT * FROM players                 
                WHERE gender = '" . $gender . "'";
        $result = $db->query($sql);

        $players = [];
        while ($row = $db->fetch($result)) {
            $players[] = new Player(
                $row['id'],
                $row['name'],
                $row['birth_date'],
                $row['gender'],
                $row['ability'],
                $row['strength'],
                $row['speed'],
                $row['reaction']
            );
        }

        $db->close();

        return $players;
    }

    public function getPlayerById($id){
        $db = new DB();
        $db->connect();

        $sql = "SELECT * FROM players WHERE id =  $id ";
        $result = $db->query($sql);
        $players = [];

        $row = $db->fetch($result);
        $db->close();

        return $row;
    }
}
?>
