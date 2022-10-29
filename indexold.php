<!doctype html>


<html>
    
    <head>
        <title>Vertretungsplan - Anmeldung</title>
        <meta charset="utf-8">
        <link rel="stylesheet" src="style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            function submit() {
                var bn = document.getElementById("bn").value;
                var pw = document.getElementById("pw").value;
            }
        </script>
        
    </head>
    <body>
        <titel><h1>IServ Anmeldung</h1></titel>
        <form method="post">
            <input id="bn" name="bn" placeholder="Benutzername">
            <input id="pw" name="pw" placeholder="Passwort" type="password">
            <input id="bestätigen" name="submit" type="submit">Anmelden</input>
        </form>
    </body>
    
</html>