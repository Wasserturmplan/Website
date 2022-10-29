<?php
            if(!session_start()) {
                echo "<p class='output errorout' onload='destroy(this)'><img class='errimg' src='images/error.svg'>Session konnte nicht gestartet werden</p>";
                die();
            } else {
                header("Cache-control: private");
                file_put_contents("aufrufe.txt", file_get_contents("aufrufe.txt") + 1);
                file_put_contents("aufrufliste.txt", file_get_contents("aufrufliste.txt") . date("Y.m.d.H.i.s") . "\n");
            }
            $showUpload = false;
            $dmauto = true;
            $version = "b_2.2";
            $chatVersion = "a_0.1";
            error_reporting(E_ERROR | E_PARSE);
?>
<!doctype html>


<html style="background-color: black;">
    
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../styleschlicht.css">
        <link rel="icon" href="../images/icon.png"> 
        <title>Messenger - Wasserturmplan</title>

        <meta name="description" content="Übersicht der Vertretungsstunden auf dem eigenen Stundenplan">
        <meta property="og:title" content="Wasserturmplan">
        <meta property="og:url" content="https://wasserturmplan.steffenmolitor.de">
        <meta property="og:image" content="../images/wasserturmplan_600x600_dark.png">

        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="apple-touch-icon" href="../images/wasserturmplan_iOS.png">
        <meta name="apple-mobile-web-app-title" content="Wasserturmplan">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta http-equiv="ScreenOrientation" content="autoRotate:disabled">
        <!-- Skripte für automatische Farbschema-Erkennung aus dem Internet!-->
        <script src="https://cdn.jsdelivr.net/npm/js-cookie/dist/js.cookie.min.js"></script>
        <script>
            var $color_scheme = Cookies.get("color_scheme");
            function get_color_scheme() {
            return (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) ? "dark" : "light";
            }
            function update_color_scheme() {
            Cookies.set("color_scheme", get_color_scheme());
            }
            if ((typeof $color_scheme === "undefined") || (get_color_scheme() != $color_scheme))
            update_color_scheme();
            if (window.matchMedia)
            window.matchMedia("(prefers-color-scheme: dark)").addListener( update_color_scheme );
        </script>
        <?php
            echo '<script>console.log("' . $version . ' - Chat Version: ' . $chatVersion . '")</script>';
        ?>
    </head>
    <?php
        if((!isset($_SESSION['user']) || $_SESSION['user'] == "" || empty($_SESSION['user'])) && !isset($_POST['login']) && !isset($_POST['reg'])) {
            $auth = false;
            $showForm = true;
            echo '<body id="body" onload="stoploadslow();">';
        } else {
            echo '<body id="body" onload="stopload();">';
        }
    ?>
    <div id="loadani" style="display: flex; justify-content: center; align-items: center; height: 100vh; width: 100vw; background: transparent; position: fixed; left: 0; top: 0; z-index: 99999;">
        <img id="loadanimation" src="../images/load4.gif" style="display: inline-block; height: 300px; width: 300px; z-index: 99999;">
    </div>
    <!-- Vom Server verarbeiteter und ausgegebener Bereich !-->
    <?php
            $bn = null;
            $showForm = true;
            $showMt = false;
            if(isset($_POST['submit']) && isset($_POST['bn']) && isset($_POST['pw'])) {
                $bn = str_replace(" ","",strtolower($_POST['bn']));
                $pw = $_POST['pw'];
                $pw = hash("sha512", $pw, false);
                //$remember = $_POST['remember'];

                $db = new PDO("mysql:host=mysql.server.url;dbname=databasename","username","PaSswoRt123");
                if ($db->connect_error) {
                    die("Verbindung fehlgeschlagen");
                }
                $getpw = $db->prepare("SELECT pw FROM `benutzer` WHERE bn = ?");
                $getpw->execute([$bn]);
                $result = $getpw->fetch()["pw"];
                if($result == $pw) {
                    //echo "<p id='output' style='color: green;'>Passwort richtig!</p><BR><p>Eingeloggter Nutzer: $bn</p>";
                    $_SESSION["user"] = $bn;
                    $getlc = $db->prepare("SELECT lc FROM `benutzer` WHERE bn = ?");
                    $getlc->execute([$bn]);
                    $resultlc = $getlc->fetch()["lc"];
                    $resultlc++;
                    $setlc = $db->prepare("UPDATE benutzer SET lc = ? WHERE bn = ?;");
                    $setlc->execute([$resultlc,$_SESSION["user"]]);
                    $auth = true;
                    /*if($remember != null) {
                        $expire = time() + 30 * 24 * 3600;
                        setcookie("bn", $bn, $expire, "/", "stundenplan.steffenmolitor.de", true);
                        echo $_COOKIE["bn"] . "got";
                    }*/
                    $showForm = false;
                    if(file_exists("mt/" . $_SESSION['user'] . ".txt")) {
                        $showMt = true;
                    }
                } else {
                    echo "<p class='output errorout'><img class='errimg' src='images/error.svg'>Anmeldedaten falsch</p>";
                }
                $db = null;
            }
            if(isset($_POST['nsubmit']) && isset($_POST['nbn']) && isset($_POST['npw']) && $_POST['nbn'] != null && $_POST['npw'] != null && $_POST['npw'] == $_POST['rnpw'] && substr_count($_POST['nbn'],".") == 1 && !preg_match('~[0-9]+~', $_POST['nbn'])) {
                $bn = str_replace(" ","",strtolower($_POST['nbn']));
                if(!preg_match("/[^a-zä-ü]/",str_replace(".","",$bn))) {
                    $pw = $_POST['npw'];
                    $pw = hash("sha512", $pw, false);

                    $db = new PDO("mysql:host=mysql.server.url;dbname=databasename","username","PaSswoRt123");
                    if ($db->connect_error) {
                        die("Verbindung fehlgeschlagen: " . mysqli_connect_error());
                    }
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
                    $reg = $db->prepare("INSERT INTO benutzer (bn,pw) VALUES (?,?)");
                    if($reg->execute([$bn,$pw])) {
                        //echo "Nutzer $bn erstellt!";
                        file_put_contents("sp/" . $bn . ".txt",file_get_contents("sp/template.txt"));
                        file_put_contents("mt/" . $bn . ".txt",file_get_contents("mt/template.txt"));
                        $showMt = true;
                        $_SESSION["user"] = $bn;
                        $auth = true;
                        $showForm = false;
                    } else {
                        echo "<p class='output errorout'><img class='errimg' src='images/error.svg'>Nutzer existiert bereits</p>";
                        $_POST['reg'] = true;
                    }
                    $db = null;
                } else {
                    echo "<p class='output errorout'><img class='errimg' src='images/error.svg'><img class='errimg' src='images/error.svg'>Benutzernamen dürfen keine Sonderzeichen, oder Zahlen enthalten</p>";
                    $_POST['reg'] = true;
                }
            } else if(isset($_POST['nsubmit'])){
                echo "<p class='output errorout'><img class='errimg' src='images/error.svg'>Bitte Daten korrekt eingeben</p>";
                $_POST['reg'] = true;
            }
            $style = '<link rel="stylesheet" href="../styleschlicht.css">';
            if($_SESSION["user"] != null) {
                $db = new PDO("mysql:host=mysql.server.url;dbname=databasename","username","PaSswoRt123");
                if ($db->connect_error) {
                    die("Verbindung fehlgeschlagen");
                }
                $getes = $db->prepare("SELECT bn FROM `benutzer` WHERE bn = ?");
                if($getes->execute([$_SESSION["user"]])) {
                    $showForm = false;
                    $auth = true;
                    $bn = $_SESSION["user"];
                } else {
                    $bn = null;
                    session_unset();
                    session_destroy();
                    $showForm = true;
                    $auth = false;
                }
                $db = null;
            }
            if(isset($_POST['thms'])) {
                $bn = $_SESSION["user"];
                $showUpload = false;
                $showForm = false;
                $auth = false;
                $showOptions = true;
                if($_SESSION["user"] != null) {
                    $db = new PDO("mysql:host=mysql.server.url;dbname=databasename","username","PaSswoRt123");
                    if ($db->connect_error) {
                        die("Verbindung fehlgeschlagen");
                    }
                    $getes = $db->prepare("SELECT es FROM `benutzer` WHERE bn = ?");
                    $getes->execute([$_SESSION["user"]]);
                    $resultes = $getes->fetch()["es"];
                    $einst = explode(";", $resultes);
                    $einst[0] = $_POST["dmlm"];
                    $einstellungen = implode(";",$einst);
                    $setes = $db->prepare("UPDATE benutzer SET es = ? WHERE bn = ?;");
                    $setes->execute([$einstellungen,$_SESSION["user"]]);
                    $db = null;
                }
            }
            if($_SESSION["user"] != null) {
                $db = new PDO("mysql:host=mysql.server.url;dbname=databasename","username","PaSswoRt123");
                if ($db->connect_error) {
                    die("Verbindung fehlgeschlagen");
                }
                $getes = $db->prepare("SELECT es FROM `benutzer` WHERE bn = ?");
                $getes->execute([$_SESSION["user"]]);
                $resultes = $getes->fetch()["es"];
                $einst = explode(";", $resultes);
                $dmlm = $einst[0];
                $ff = $einst[1];
                $db = null;
                if(!$getes->execute([$_SESSION["user"]])) {
                    $bn = null;
                    session_unset();
                    session_destroy();
                    $showForm = true;
                    $auth = false;
                }
            }
            if($einst[0] == "auto") {
                $dmauto = true;
            } else if($einst[0] == "d") {
                $dmauto = false;
                $_SESSION["darkmode"] = true;
            } else if($einst[0] == "l") {
                $dmauto = false;
                $_SESSION["darkmode"] = false;
            }
            if($dmauto) {
                $color_scheme = isset($_COOKIE["color_scheme"]) ? $_COOKIE["color_scheme"] : false;
                if ($color_scheme === false) $color_scheme = 'dark';
                if ($color_scheme == 'dark') {
                    $style .= '<link rel="stylesheet" href="../theme_dark.css">';
                } else {
                    $style .= '<link rel="stylesheet" href="../theme_light.css">';
                }
            } else {
                if($_SESSION["darkmode"]) {
                    $style .= '<link rel="stylesheet" href="../theme_dark.css">';
                } else {
                    $style .= '<link rel="stylesheet" href="../theme_light.css">';
                }
            }
            if(isset($_POST['lo'])) {
                $auth = false;
                $showForm = true;
                session_unset();
                session_destroy();
            }
            if(isset($_POST['ao'])) {
                $bn = $_SESSION["user"];
                $showOptions = true;
                $showForm = false;
                $auth = false;
            }
            if($_SESSION["user"] == "") {
                $showForm = true;
                $showUpload = false;
                $showOptions = false;
                $auth = false;
            }
            if($showForm) {
                echo "<p id='version'>Version: " . $version . "</p>
                <BR><p id='version'>Chat-Version: " . $chatVersion . "</p>
                    <p class='titlesch showtitle'>Anmeldung</p>";
            }
            if(isset($_POST['login']) && $showForm) {
                echo '<div class="logind"><img draggable="false" id="titleimg" src="../images/wasserturmplan_600x600.png"><form id="choose" method="post"><input class="clickable cur" type="submit" name="login" value="Anmelden" id="login"><input type="submit" class="clickable" name="reg" id="reg" value="Registrieren"></form><form class="login" method="post" id="loginform"><input class="login" id="bn" name="bn" placeholder="Benutzername" autocomplete="off"><hr class="sep"><input class="login" id="pw" name="pw" placeholder="Passwort" type="password"><hr class="sep"><input class="login clickable" id="bestaetigen" name="submit" type="submit" value="Anmelden"></form></div>
                <div id="links"><a id="datenschutz" href="../datenschutz">Datenschutz</a><a id="impressum" href="../impressum">Impressum und Kontakt</a><a id="readme" href="../README.md">README</a></div><div id="safeo"></div>';
            } else if(isset($_POST['reg']) && $showForm) {
                echo '<div class="logind"><img draggable="false" id="titleimg" src="../images/wasserturmplan_600x600.png"><form id="choose" method="post"><input class="clickable" type="submit" name="login" value="Anmelden" id="login"><input type="submit" class="clickable cur" name="reg" id="reg" value="Registrieren"></form><form class="login" method="post" id="loginform"><input class="login" id="nbn" name="nbn" placeholder="Benutzername (vorname.nachname)" autocomplete="off"><hr class="sep"><input class="login" id="npw" name="npw" placeholder="Passwort" type="password"><hr class="sep"><input class="login" id="rnpw" name="rnpw" placeholder="Passwort wiederh." type="password"><hr class="sep"><input class="login clickable" id="nbestaetigen" name="nsubmit" type="submit" value="Registrieren"></form></div>
                <div id="links"><a id="datenschutz" href="../datenschutz">Datenschutz</a><a id="impressum" href="../impressum">Impressum und Kontakt</a><a id="readme" href="../README.md">README</a></div><div id="safeo"></div>';
            } else if($showForm){
                echo '<div class="logind"><img draggable="false" id="titleimg" src="../images/wasserturmplan_600x600.png"><form id="choose" method="post"><input class="clickable cur" type="submit" name="login" value="Anmelden" id="login"><input type="submit" class="clickable" name="reg" id="reg" value="Registrieren"></form><form class="login" method="post" id="loginform"><input class="login" id="bn" name="bn" placeholder="Benutzername" autocomplete="off"><hr class="sep"><input class="login" id="pw" name="pw" placeholder="Passwort" type="password"><hr class="sep"><input class="login clickable" id="bestaetigen" name="submit" type="submit" value="Anmelden"></form></div>
                <div id="links"><a id="datenschutz" href="../datenschutz">Datenschutz</a><a id="impressum" href="../impressum">Impressum und Kontakt</a><a id="readme" href="../README.md">README</a></div><div id="safeo"></div>';
            } else if($auth) {
                echo '  <div id="userview">

                        </div>
                        <div id="chatview"></div>';
            }
            echo $style;
        ?>
        <div id="safeo"></div>
        <script>
            //Bitte bring mich dafür nicht um :'(
            if(document.getElementsByClassName("titlesch")[0] != null || document.getElementById("settitle") != null) {
                setInterval(titlecheck,50);
            }
            function titlecheck() {
                if(document.getElementsByClassName("titlesch")[0] != null) {
                    if(document.getElementsByClassName("navbar")[0] != null && document.getElementById("body").scrollTop >= document.getElementsByClassName("navbar")[0].clientHeight / 3 * 2) {
                        document.getElementsByClassName("titlesch")[0].classList.remove("showtitle");
                        document.getElementsByClassName("navbar")[0].classList.add("showtitle");
                    } else if(document.getElementsByClassName("navbar")[0] != null) {
                        document.getElementsByClassName("navbar")[0].classList.remove("showtitle");
                        document.getElementsByClassName("titlesch")[0].classList.add("showtitle");
                    }
                } else if(document.getElementById("settitle") != null) {
                    if(document.getElementsByClassName("navbar")[0] != null && document.getElementById("body").scrollTop >= document.getElementsByClassName("navbar")[0].clientHeight / 3 * 2) {
                        document.getElementById("settitle").classList.remove("showtitle");
                        document.getElementsByClassName("navbar")[0].classList.add("showtitle");
                    } else if(document.getElementsByClassName("navbar")[0] != null) {
                        document.getElementsByClassName("navbar")[0].classList.remove("showtitle");
                        document.getElementById("settitle").classList.add("showtitle");
                    }
                }
            }
        </script>
        <script>
            //Ladeanimation stoppen
            var loadInt;
            function stopload() {
                document.getElementById("loadani").outerHTML = "";
            }
            function loadop() {
                if(document.getElementById("loadani").style.opacity > 0) {
                    document.getElementById("loadani").style.opacity -= 0.01;
                } else {
                    document.getElementById("loadani").style.display = "none";
                    document.getElementById("loadani").outerHTML = "";
                    clearInterval(loadInt);
                }
            }
            function stoploadslow() {
                document.getElementById("loadani").style.backgroundColor = "black";
                document.getElementById("loadani").style.opacity = 1;
                setTimeout(function () {loadInt = setInterval(loadop,10);},700);
            }
            //Funktion zum Anzeigen des Dateinamen beim Upload
            function filefunc() {
                if(document.getElementById("file").value != "") {
                    var filen = document.getElementById("file").value;
                    document.getElementById("filel").innerHTML = filen.replace("C:\\fakepath\\","");
                } else {
                    document.getElementById("filel").innerHTML = "Datei auswählen";
                }
            }

            //Funktion um Output-Elemente zu löschen (Geht nicht joa fuck)
            function destroy(elem) {
                function elemdel(elem) {
                    elem.outerHTML = "";
                }
                setTimeout(elemdel(elem), 5000);
            }
            function sel(elem) {
                if(elem.classList.contains("selected")) {
                    elem.classList.remove("selected");
                    values = document.getElementById("sellist").value.split(",");
                    values.splice(values.indexOf(elem.id), 1);
                    document.getElementById("sellist").value = values.join(",");
                    if(values.length <= 0) {
                        document.getElementById("cellbearb").classList.add("gray");
                        document.getElementById("cellfrei").classList.add("gray");
                        document.getElementById("cellbearb").disabled = true;
                        document.getElementById("cellfrei").disabled = true;
                    }
                } else {
                    elem.classList.add("selected");
                    if(document.getElementById("sellist").value == "") {
                        document.getElementById("sellist").value = elem.id;
                    } else {
                        document.getElementById("sellist").value += "," + elem.id;
                    }
                    document.getElementById("cellbearb").classList.remove("gray");
                    document.getElementById("cellfrei").classList.remove("gray");
                    document.getElementById("cellbearb").disabled = false;
                    document.getElementById("cellfrei").disabled = false;
                }
            }
        </script>
    </body>
    
</html>