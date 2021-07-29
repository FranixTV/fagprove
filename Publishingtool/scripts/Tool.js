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
    let showButton = document.getElementById("show-form-button");
    let closeButton = document.getElementById("close-form-button");
    formContainer.classList.remove("hidden");
    closeButton.classList.remove("hidden");
    showButton.classList.add("hidden");
}

function closeForm() {
    window.location.href = "/";
}