<?php

class Database {
    private $host = 'localhost';
    private $db_name = 'bancosnct';
    private $username = 'root';
    private $password = '';
    private $connection;

    public function connect() {
        $this->connection = null;

        try {
            $this->connection = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->db_name
            );

            if ($this->connection->connect_error) {
                throw new Exception("Erro na conexão: " . $this->connection->connect_error);
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }

        return $this->connection;
    }

    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
?>