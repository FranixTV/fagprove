<?php

namespace Src;

class Database
{

    private $dbConnection;

    public function __construct() {
        $host = $_ENV["DB_HOST"];
        $username = $_ENV["DB_USERNAME"];
        $pass = $_ENV["DB_PASSWORD"];
        $db = $_ENV["DB_DATABASE"];
        $port = $_ENV["DB_PORT"];

        try {
            $this->dbConnection = new \mysqli($host, $username, $pass, $db, $port);

            if($this->dbConnection->connect_errno) {
                throw new \Exception("Could not connect to database");
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function connect() {
        return $this->dbConnection;
    }
}