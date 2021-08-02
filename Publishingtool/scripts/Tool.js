function deleteArticle(articleId, articleTitle) {
    if(confirm("Er du sikker på at du vil slette artikkelen: " + articleTitle + "?")) {
        window.location.href = "/?deleteArticle=" + articleId;
    }
}

function deleteUser(userId, username) {
    if(confirm("Er du sikker på at du vil slette brukeren: " + username + "?\nOBS: Dette vil fjerne alle artiklene brukeren har publisert")) {
        window.location.href = "/?deleteUser=" + userId;
    }
}

function deleteMyUser(userId) {
    if(confirm("Er du sikker på at du vil slette brukeren din? Dette kan ikke reverseres!")) {
        window.location.href = "/?deleteMyUser=" + userId;
    }
}

function toggleEdit(article) {
    let articleId = document.getElementById("editArticle");
    let title = document.getElementById("article-title");
    let summary = document.getElementById("article-summary");
    let content = tinymce.get('article-content');
    let checkbox = document.getElementById("article-published");

    articleId.value = article.articleid;
    title.value = article.title;
    summary.value = article.summary;
    content.setContent(article.content);
    checkbox.checked = article.published === "1";
    showForm();
}

function showForm() {
    let formContainer = document.getElementById("article-form-container");
    let showButton = document.getElementById("show-form-button");
    let closeButton = document.getElementById("close-form-button");
    formContainer.classList.remove("hidden");
    closeButton.classList.remove("hidden");
    showButton.classList.add("hidden");
}

function closeForm() {
    let formContainer = document.getElementById("article-form-container");
    let showButton = document.getElementById("show-form-button");
    let closeButton = document.getElementById("close-form-button");
    let articleId = document.getElementById("editArticle");
    let title = document.getElementById("article-title");
    let summary = document.getElementById("article-summary");
    let content = tinymce.get('article-content');
    let checkbox = document.getElementById("article-published");

    articleId.value = 0;
    title.value = "";
    summary.value = "";
    content.setContent("");
    checkbox.checked = false;

    formContainer.classList.add("hidden");
    closeButton.classList.add("hidden");
    showButton.classList.remove("hidden");
}

function toggleUserForm() {
    let formContainer = document.getElementById("user-form-container");
    let showButton = document.getElementById("show-user-form");
    let closeButton = document.getElementById("close-user-form");
    formContainer.classList.toggle("hidden");
    showButton.classList.toggle("hidden");
    closeButton.classList.toggle("hidden");
}