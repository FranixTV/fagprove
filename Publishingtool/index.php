<?php
require 'vendor/autoload.php';

use Src\Database;
use Src\User;

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$db = (new Database())->connect();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = "";
if(isset($_POST) && !empty($_POST)) {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $user = new User($db);
    $verified = $user->verify($username, $password);

    if($verified) {
        header("Refresh:0");
        $_SESSION["loggedin"] = true;
        $_SESSION["userid"] = $user->getId();
    } else {
        $error = "Feil brukernavn/passord.";
    }
}

if(isset($_GET["logout"])) {
    session_destroy();
    header("Refresh:0; url=/");
}

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]) {
    include "pages/Tool.php";
} else {
    include "pages/Login.php";
}