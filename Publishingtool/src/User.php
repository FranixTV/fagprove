<?php


namespace Src;

use Src\Database;


class User
{
    private $userId;
    private $username;
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function verify($username, $password) {
        $userData = $this->getUserData($username);

        if(!$userData) {
            return false;
        }

        $hash = $userData["password"];

        if(password_verify($password, $hash)) {
            $this->userId = $userData["userid"];
            $this->username = $userData["username"];

            return true;
        }

        return false;
    }

    private function getUserData($username) {
        $statement = $this->db->prepare("SELECT * FROM users WHERE username=?");
        $statement->bind_param('s', $username);
        $statement->execute();

        $result = $statement->get_result();
        $result = $result->fetch_assoc();

        if(!$result) {
            return false;
        }

        return $result;
    }
}