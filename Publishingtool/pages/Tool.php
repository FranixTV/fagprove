<?php

$query = "SELECT articleid, title, summary, content, images, created, published, username FROM articles JOIN users ON articles.authorid=users.userid";
$statement = $db->query($query);
$articles = $statement->fetch_all(MYSQLI_ASSOC);

$article = [
    "articleid" => 0,
    "title" => "",
    "summary" => "",
    "content" => "",
    "images" => "",
    "created" => "",
    "published" => 0,
    "authorid" => 0
];

if(isset($_GET["editArticle"])) {
    $articleId = $_GET["editArticle"];
    $articleIndex = array_search($articleId, array_column($articles, 'articleid'));
    if($articleIndex) {
        $article = $articles[$articleIndex];

        $images = json_decode($article["images"], true);
        $article["images"] = $images[0];
    }
}

if(isset($_GET["deleteArticle"])) {
    $articleId = $_GET["deleteArticle"];

    $statement = $db->prepare("DELETE FROM articles WHERE articleid=?");
    $statement->bind_param('i', $articleId);

    $statement->execute();

    header("Refresh:0; url=/");
}

if(isset($_POST["submit"])) {
    $title = $_POST["article-title"];
    $summary = $_POST["article-summary"];
    $content = $_POST["article-content"];
    $image = json_encode([$_POST["article-image"]]);
    $published = isset($_POST["article-published"]) ? 1 : 0;

    if(isset($_GET["editArticle"])) {
        $articleId = $_GET["editArticle"];

        $statement = $db->prepare("UPDATE articles SET title=?, summary=?, content=?, images=?, published=? WHERE articleid=?");
        $statement->bind_param('ssssii', $title, $summary, $content, $image, $published, $articleId);
        $statement->execute();

        header("Refresh:0; url=/");
    } else {
        $now = date("Y-m-d H:i:s");
        $userId = (int)$_SESSION["userid"];

        $statement = $db->prepare("INSERT INTO articles (title, summary, content, images, created, published, authorid) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $statement->bind_param('sssssii', $title, $summary, $content, $image, $now, $published, $userId);

        $statement->execute();

        header("Refresh:0; url=/");
    }
}


?>

<html lang="no">
<head>
    <link rel="stylesheet" href="../assets/tool.css">
    <meta charset="UTF-8">
    <title>Publiseringsverktøy</title>
</head>
<body>
    <a href="?logout=1">Logg ut</a>
    <div class="tool-container">
        <button id="show-form-button" class="<?=isset($articleId) ? 'hidden' : ''?>" onclick="showForm()">Ny artikkel</button>
        <div id="article-form-container" class="article-form-container <?=isset($articleId) ? '' : 'hidden'?>">
            <form method="POST">
                <label for="article-title">Tittel</label>
                <input type="text" value="<?=$article["title"]?>" name="article-title" id="article-title"/>

                <label for="article-summary">Ingress</label>
                <textarea name="article-summary" id="article-summary" maxlength="255"><?=$article["summary"]?></textarea>

                <label for="article-content">Innhold</label>
                <textarea name="article-content" id="article-content" maxlength="65000"><?=$article["content"]?></textarea>

                <label for="article-image">Bilde</label>
                <input type="text" value="<?=$article["images"]?>" name="article-image" id="article-image"/>

                <label for="article-published">Publisert</label>
                <input type="checkbox" <?=($article["published"] == 1 ? 'checked' : '')?> name="article-published" id="article-published"/>

                <button type="submit" name="submit">Lagre</button>
            </form>
        </div>
        <div class="articles">
        <?php
            foreach($articles as $article) {
                ?>
                <div class="article">
                    <p class="article-title"><?=$article["title"]?></p>
                    <span class="edit-article" onclick="toggleEdit(<?=$article["articleid"]?>)">Edit</span>
                    <span class="delete-article" onclick="deleteArticle(<?=$article["articleid"]?>)">Delete</span>
                </div>
                <?php
            }
        ?>
        </div>
    </div>
    <script src="../scripts/Tool.js"></script>
</body>
</html>
