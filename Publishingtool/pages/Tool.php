<?php

$query = "SELECT articleid, title, summary, content, images, created, published, username FROM articles JOIN users ON articles.authorid=users.userid ORDER BY articleid DESC";
$statement = $db->query($query);
$articles = $statement->fetch_all(MYSQLI_ASSOC);
$error = "";

function uploadImage() {
    $target_dir = "files/";
    $target_file = $target_dir . basename($_FILES["article-image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["article-image"]["tmp_name"]);
    if(!$check) {
        $uploadOk = false;
        return ["error" => "Filen var ikke et bilde", "uploadOk" => $uploadOk];
    }
    if(file_exists($target_file)) {
        $uploadOk = true;
        return ["error" => "", "uploadOk" => $uploadOk, "path" => $target_file];
    }
    if($_FILES["article-image"]["size"] > 5000000) {
        $uploadOk = false;
        return ["error" => "Filen er for stor (maks 5MB)", "uploadOk" => $uploadOk];
    }
    if($imageFileType !== "jpg" && $imageFileType !== "png" && $imageFileType !== "jpeg" && $imageFileType !== "gif") {
        $uploadOk = false;
        return ["error" => "Beklager, bare JPG, JPEG, PNG og GIF bilder er lov", "uploadOk" => $uploadOk];
    }
    if(move_uploaded_file($_FILES["article-image"]["tmp_name"], $target_file)) {
        $uploadOk = true;
        return ["error" => "", "uploadOk" => $uploadOk, "path" => $target_file];
    } else {
        $uploadOk = false;
        return ["error" => "Noe gikk galt under opplastingen av filen, vennligst prøv igjen", "uploadOk" => $uploadOk];
    }
}

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
        $editArticle = true;
        $article = $articles[$articleIndex];
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
    $image = json_encode([]);
    $published = isset($_POST["article-published"]) ? 1 : 0;

    if(!empty($_FILES["article-image"]["name"])) {
        $result = uploadImage();
        if(!$result["uploadOk"]) {
            $check = false;
            $error = $result["error"];
        } else {
            $image = json_encode(["http://localhost:8001/" . $result["path"]]);
        }
    }

    if((isset($check) && $check) || !isset($check)) {
        if (isset($_GET["editArticle"]) && isset($editArticle)) {
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
        <button id="show-form-button" class="<?=isset($editArticle) || !empty($error) ? 'hidden' : ''?>" onclick="showForm()">Ny artikkel</button>
        <div id="article-form-container" class="article-form-container <?=isset($editArticle) || !empty($error) ? '' : 'hidden'?>">
            <form method="POST" enctype="multipart/form-data">
                <p class="form-error"><?=$error?></p>
                <label for="article-title">Tittel</label>
                <input type="text" value="<?=$article["title"]?>" name="article-title" id="article-title" required maxlength="60"/>

                <label for="article-summary">Ingress</label>
                <textarea name="article-summary" id="article-summary" maxlength="255"><?=$article["summary"]?></textarea>

                <label for="article-content">Innhold</label>
                <textarea name="article-content" id="article-content" maxlength="65000"><?=$article["content"]?></textarea>

                <label for="article-image">Bilde</label>
                <input type="file" name="article-image" id="article-image"/>

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
