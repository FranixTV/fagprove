<?php
use Src\User;
if($_SESSION["userid"]) {

$query = "SELECT articleid, title, summary, content, images, created, published, username FROM articles JOIN users ON articles.authorid=users.userid ORDER BY articleid DESC";
$statement = $db->query($query);
$articles = $statement->fetch_all(MYSQLI_ASSOC);

$query = "SELECT userid, username FROM users ORDER BY userid DESC";
$statement = $db->query($query);
$users = $statement->fetch_all(MYSQLI_ASSOC);
$error = "";
$loginError = "";

function uploadImage() {
    $target_dir = "files/";
    $target_file = $target_dir . basename($_FILES["article-image"]["name"]);
    $spaPath = "//" . $_SERVER["HTTP_HOST"] . '/files/' . basename($_FILES["article-image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    if (!file_exists('files')) {
        mkdir('files', 0777, true);
    }
    if($_FILES["article-image"]["error"] > 0) {
        $uploadOk = false;
        return ["error" => "En feil oppsto, prøv med et annet bilde", "uploadOk" => $uploadOk];
    }
    if(!getimagesize($_FILES["article-image"]["tmp_name"])) {
        $uploadOk = false;
        return ["error" => "Filen var ikke et bilde", "uploadOk" => $uploadOk];
    }
    if(file_exists($target_file)) {
        $uploadOk = true;
        return ["error" => "", "uploadOk" => $uploadOk, "path" => $spaPath];
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
        return ["error" => "", "uploadOk" => $uploadOk, "path" => $spaPath];
    } else {
        $uploadOk = false;
        return ["error" => "Noe gikk galt under opplastingen av filen, vennligst prøv igjen", "uploadOk" => $uploadOk];
    }
}

if(isset($_GET["deleteArticle"])) {
    $articleId = $_GET["deleteArticle"];

    $statement = $db->prepare("DELETE FROM articles WHERE articleid=?");
    $statement->bind_param('i', $articleId);

    $statement->execute();

    header("Refresh:0; url=/");
}

if(isset($_POST["submitArticle"])) {
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
            $image = json_encode([$result["path"]]);
        }
    }

    if((isset($check) && $check) || !isset($check)) {
        if (isset($_POST["editArticle"]) && (int)$_POST["editArticle"] !== 0) {
            $articleId = $_POST["editArticle"];

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

if(isset($_POST["submitUser"])) {
    $username = strtolower($_POST["username"]);
    $password = $_POST["password"];

    if(preg_match('/^\w{5,}$/', $username)) {
        $newUser = new User($db);
        if(!$newUser->userExists($username)) {
            $newUser->createUser($username, $password);
            header("Refresh:0; url=/");
        } else {
            $loginError = "Brukeren eksisterer allerede";

        }
    } else {
        $loginError = "Ikke gyldig brukernavn";
    }
}

if(isset($_GET["deleteUser"])) {
    $userId = (int)$_GET["deleteUser"];
    $statement = $db->prepare("DELETE FROM users WHERE userid=?");
    $statement->bind_param('i', $userId);

    $statement->execute();

    if ((int)$_SESSION["userid"] === $userId) {
        session_destroy();
    }

    header("Refresh:0; url=/");
}


?>

<!DOCTYPE HTML>
<html lang="no">
<head>
    <link rel="stylesheet" href="../assets/tool.css">
    <meta charset="UTF-8">
    <title>Publiseringsverktøy</title>
    <script src="https://cdn.tiny.cloud/1/s3yoe29u687t3mhi0j1qnhm2mts2m030eg329ltivg4ad4o4/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
</head>
<body>
    <a class="logout-button" href="?logout=1">Logg ut</a>
    <div class="tool-container">
        <button id="show-form-button" class="<?=(isset($editArticle) || !empty($error)) ? 'hidden' : ''?>" onclick="showForm()">Ny artikkel</button>
        <div id="article-form-container" class="article-form-container <?=isset($editArticle) || !empty($error) ? '' : 'hidden'?>">
            <form method="POST" id="article-form" enctype="multipart/form-data">
                <input type="number" value="0" class="hidden" name="editArticle" id="editArticle"/>

                <p class="form-error"><?=$error?></p>
                <label for="article-title">Tittel</label>
                <input type="text" name="article-title" id="article-title" required maxlength="60"/>

                <label for="article-summary">Ingress</label>
                <textarea name="article-summary" id="article-summary" maxlength="255"></textarea>

                <label for="article-content">Innhold</label>
                <textarea name="article-content" id="article-content" maxlength="65000"></textarea>

                <label for="article-image">Bilde</label>
                <input type="file" name="article-image" id="article-image"/>

                <label for="article-published">Publisert</label>
                <input type="checkbox" name="article-published" id="article-published"/>

                <button type="submit" name="submitArticle">Lagre</button>
            </form>
        </div>
        <button id="close-form-button" class="<?=(isset($editArticle) || !empty($error)) ? '' : 'hidden'?>" onclick="closeForm()">Lukk</button>
        <div class="articles">
            <div class="article-header">
                <p class="header-title">Tittel</p>
                <p class="header-author">Forfatter</p>
                <p class="header-date">Dato</p>
                <p class="spacer"></p>
                <p class="spacer"></p>
            </div>
        <?php
            foreach($articles as $article) {
                ?>
                <div class="article">
                    <p class="article-title"><?=$article["title"]?></p>
                    <p class="article-author"><?=$article["username"]?></p>
                    <span class="article-date"><?=$article["created"]?></span>
                    <span tabindex="0" aria-label="Rediger <?=$article["title"]?>" class="edit-article" onclick="toggleEdit(<?= htmlspecialchars(json_encode($article)) ?>)">Rediger</span>
                    <span tabindex="0" aria-label="Slett <?=$article["title"]?>" class="delete-article" onclick="deleteArticle(<?=$article["articleid"]?>, '<?=$article["title"]?>')">Slett</span>
                </div>
                <?php
            }
        ?>
        </div>
        <div class="user-form-outer">
            <h2>Brukerstrying</h2>
            <button id="show-user-form" class="<?= !empty($loginError) ? "hidden" : "" ?>" onclick="toggleUserForm()">Ny bruker</button>
            <div class="user-form-container <?= !empty($loginError) ? "" : "hidden" ?>" id="user-form-container">
                <form method="POST">
                    <p class="form-error"><?=$loginError?></p>
                    <label for="username">Brukernavn</label>
                    <input id="username" maxlength="45" type="text" name="username"/>

                    <label for="password">Passord</label>
                    <input id="password" maxlength="72" type="password" name="password"/>

                    <button type="submit" name="submitUser">Lagre</button>
                </form>
            </div>
            <button id="close-user-form" class="<?=!empty($loginError) ? "" : "hidden"?>" onclick="toggleUserForm()">Lukk</button>
        </div>
        <div class="users">
            <?php
                foreach ($users as $user) {
                    ?>
                    <div class="user">
                        <p class="user-username"><?=$user["username"]?></p>
                        <?php
                        if((int)$user["userid"] !== $_SESSION["userid"]) {
                        ?>
                        <span tabindex="0" aria-label="Slett bruker <?=$user["username"]?>" class="delete-user" onclick="deleteUser(<?=$user["userid"]?>, '<?=$user["username"]?>')">Slett</span>
                        <?php } else { ?>
                        <span tabindex="0" aria-label="Slett brukeren din" class="delete-user" onclick="deleteUser(<?=$user["userid"]?>, '<?=$user["username"]?>')">Slett</span>
                        <?php } ?>
                    </div>
            <?php
                }
            ?>
        </>
        </div>
    </div>
    <script>
        tinymce.init({
            selector: '#article-content',
            plugins: 'a11ychecker advcode casechange formatpainter linkchecker autolink lists media mediaembed pageembed permanentpen powerpaste advtable',
            toolbar: 'a11ycheck  casechange checklist code formatpainter pageembed permanentpen ',
            height: 400
        });
    </script>
    <script src="../scripts/Tool.js"></script>
</body>
</html>
<?php
} else {
    header("Refresh:0; url=/");
}