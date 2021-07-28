<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

function response404() {
    header("HTTP/1.1 404 Not Found");
    $body = [
        "error" => "The requested resource was not found."
    ];
    echo json_encode($body);
    die();
}

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$uri = explode( "/", $uri );

if(!isset($uri[2]) || empty($uri[2])) {
    response404();
}

if($uri[2] !== "articles" && $uri[2] !== "article") {
    response404();
}

if($uri[2] === "articles" && isset($uri[3])) {
    response404();
}

if($uri[2] === "article" && (!isset($uri[3]) || empty($uri[3]))) {
    response404();
}

require '../vendor/autoload.php';

use Src\Database;
use Src\Article;

$dotenv = \Dotenv\Dotenv::createImmutable("../");
$dotenv->load();

$dbConnection = (new Database())->connect();

$articleId = null;
if(isset($uri[3])) {
    $articleId = (int) $uri[3];
}

$controller = new Article($dbConnection, $articleId);
$controller->processRequest();
