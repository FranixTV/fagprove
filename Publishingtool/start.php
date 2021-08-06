<?php
require("vendor/autoload.php");

use Src\Database;
use Src\User;

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$host = $_ENV["DB_HOST"];
$username = $_ENV["DB_USERNAME"];
$pass = $_ENV["DB_PASSWORD"];
$dbName = $_ENV["DB_DATABASE"];
$port = $_ENV["DB_PORT"];

$db = new \mysqli($host, $username, $pass, null, $port);


$query = "CREATE SCHEMA IF NOT EXISTS `$dbName`";
if($db->query($query)) {
    if($db->select_db($dbName)) {
        $query = "CREATE TABLE IF NOT EXISTS `$dbName`.`users` (
                    `userid` INT NOT NULL AUTO_INCREMENT,
                    `username` VARCHAR(45) NOT NULL,
                    `password` VARCHAR(255) NOT NULL,
                    PRIMARY KEY (`userid`))";

        if($db->query($query)) {
            echo "Users table created successfully\n";
        }

        $query = "CREATE TABLE IF NOT EXISTS `$dbName`.`articles` (
                    `articleid` INT NOT NULL AUTO_INCREMENT,
                    `title` VARCHAR(45) NOT NULL,
                    `summary` VARCHAR(255) NULL,
                    `content` TEXT NULL,
                    `images` TEXT NULL,
                    `created` DATETIME NOT NULL,
                    `published` TINYINT NOT NULL,
                    `authorid` INT NOT NULL,
                    PRIMARY KEY (`articleid`),
                    INDEX `fk_articles_users_idx` (`authorid` ASC) VISIBLE,
                    CONSTRAINT `fk_articles_users`
                        FOREIGN KEY (`authorid`)
                        REFERENCES `fagprove`.`users` (`userid`)
                        ON DELETE CASCADE
                        ON UPDATE NO ACTION)";

        if($db->query($query)) {
            echo "Articles table created successfully";
        }

        $user = new User($db);
        if(!$user->userExists("admin")) {
            $user->createUser("admin", "admin");
        }
    }
}
