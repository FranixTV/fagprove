function deleteArticle(articleId) {
    if(confirm("Er du sikker på at du vil slette denne artikkelen?")) {
        window.location.href = "/?deleteArticle=" + articleId;
    }
}

function toggleEdit(articleId) {
    window.location.href = "/?editArticle=" + articleId;
}

function showForm() {
    let formContainer = document.getElementById("article-form-container");
    let button = document.getElementById("show-form-button");
    formContainer.classList.remove("hidden");
    button.classList.add("hidden");
}