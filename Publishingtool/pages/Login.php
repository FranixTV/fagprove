<html lang="no">
    <head>
        <link rel="stylesheet" href="../assets/login.css">
        <meta charset="UTF-8">
        <title>Logg inn</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="login-container">
            <h1>Logg inn</h1>
            <form method="POST">
                <p id="form_error"><?=$error?></p>

                <label for="username">Brukernavn</label>
                <input aria-label="Brukernavn" type="text" id="username" name="username" required/>

                <label for="password">Passord</label>
                <input aria-label="Passord" type="password" id="password" name="password" required/>

                <button type="submit" name="submitUserLogin">Send</button>
            </form>
        </div>
    </body>
</html>
