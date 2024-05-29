<?php

class DB {
    private $host = 'localhost';
    private $username = 'root';
    private $password = 'root';
    private $database = 'geopagos';
    private $connection;

    public function connect() {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->connection->connect_error) {
            die("Error de conexión: " . $this->connection->connect_error);
        }
    }

    public function query($sql) {
        return $this->connection->query($sql);
    }

    public function fetch($result) {
        return $result->fetch_assoc();
    }

    public function last_id() {
        return $this->connection->insert_id;
    }

    public function close() {
        $this->connection->close();
    }
}
?>
