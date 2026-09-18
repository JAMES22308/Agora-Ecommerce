<?php

class Database
{
    private string $host = "localhost";
    private string $username = "root";
    private string $password = "";
    private string $database = "agora";

    public mysqli $connection;

    public $testConnection;

    public function __construct()
    {
        $this->connection = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );

        if ($this->connection->connect_error) {
            die("Database connection failed: " . $this->connection->connect_error);
        }

        $this->testConnection = true;

        $this->connection->set_charset("utf8mb4");
    }
}