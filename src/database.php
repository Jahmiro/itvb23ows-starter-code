<?php

class Database {
    private $db;

    public function __construct() {
        $this->db = new mysqli('db', 'root', '', 'hive');

        if ($this->db->connect_error) {
            die('Databaseverbinding mislukt: ' . $this->db->connect_error);
        }
    }

    public function getDBConnection() {
        return $this->db;
    }

    public function insertGame() {
        $query = "INSERT INTO games VALUES ()";
        $result = $this->db->query($query);

        if (!$result) {
            die('Fout bij het toevoegen van het spel: ' . $this->db->error);
        }
    }

    public function getLastInsertId() {
        return $this->db->insert_id;
    }

    public function prepare($query) {
        return $this->db->prepare($query);
    }

    public function query($query) {
        return $this->db->query($query);
    }

    public function close() {
        $this->db->close();
    }
}
?>
