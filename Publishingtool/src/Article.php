<?php


namespace Src;


class Article
{
    private $db;
    private $articleId;

    public function __construct($db, $articleId) {
        $this->db = $db;
        $this->articleId = $articleId;
    }

    public function processRequest() {
        if($this->articleId) {
            $response = $this->getArticle($this->articleId);
        } else {
            $response = $this->getArticles();
        }

        header($response["status_code_header"]);
        if($response["body"]) {
            echo $response["body"];
        }
    }

    private function getArticles() {
        try {
            $query = "SELECT articleid, title, summary, content, images, created, username FROM articles JOIN users ON articles.authorid=users.userid WHERE published=1";
            $statement = $this->db->query($query);
            $result = $statement->fetch_all(MYSQLI_ASSOC);

            $response["status_code_header"] = "HTTP/1.1 200 OK";
            $response["body"] = json_encode($result);
            return $response;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function getArticle($articleId) {
        try {
            $statement = $this->db->prepare("SELECT articleid, title, summary, content, images, created, published, username FROM articles JOIN users ON articles.authorid=users.userid WHERE published=1 AND articleid=?");
            $statement->bind_param('i', $articleId);

            $statement->execute();
            $result = $statement->get_result();
            $result = $result->fetch_assoc();

            if(!$result) {
                return $this->notFoundResponse();
            }

            $response["status_code_header"] = "HTTP/1.1 200 OK";
            $response["body"] = json_encode($result);
            return $response;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function notFoundResponse() {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $body = [
            "error" => "The requested resource was not found."
        ];
        $response['body'] = json_encode($body);
        return $response;
    }

}