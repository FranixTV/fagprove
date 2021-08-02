<?php


namespace Src;


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

    public function getId() {
        return $this->userId;
    }

    public function createUser($username, $password) {
        try {
            $hash = $this->hashPassword($password);
            $statement = $this->db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $statement->bind_param('ss', $username, $hash);
            $statement->execute();

            return $statement->insert_id;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function userExists($username) {
        try {
            $statement = $this->db->prepare("SELECT * FROM users WHERE username=?");
            $statement->bind_param('s', $username);
            $statement->execute();
            $result = $statement->get_result();

            if($result->num_rows > 0) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
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

    private function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}