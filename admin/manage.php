<?php
            if(!session_start()) {
                die();
            } else {
                header("Cache-control: private");
            }
?>
<!doctype html>


<html>
    
    <head>
        <title>Wasserturmplan - Manager</title>
        <meta charset="utf-8">
        <link rel="stylesheet" src="style.css">
        <script>
            function refreshPage() {
                window.location.reload;
            }
        </script>
    </head>
    <body style="font-size: 1vw;">
        <?php
        $blocked = ["demo"/*weitere Nutzer*/];
            $style = '<link rel="stylesheet" href="../theme_dark.css"><link rel="stylesheet" href="ff.css">';
            if(isset($_POST['submit']) && isset($_POST['pw'])) {
                $pw = $_POST['pw'];
                $pw = hash("sha512", $pw, false);
                if($pw == "PasswordHash") {
                    $_SESSION["loggedinman"] = true;
                }
            }
            if(isset($_POST['ams']) && $_SESSION["loggedinman"] == true) {
                for($i = 0; $i < count($_POST["selecteduser"]); $i++) {
                    file_put_contents("../mt/" . $_POST["selecteduser"][$i] . ".txt",$_POST['mt'] . file_get_contents("../mt/" . $_POST["selecteduser"][$i]));
                }
            }
            if(isset($_POST['adl']) && $_SESSION["loggedinman"] == true) {
                $_SESSION['deluser'] = $_POST["selecteduser"];
                $showDel = true;
            }
            if(isset($_POST['spb']) && $_SESSION["loggedinman"] == true) {
                if(count($_POST["selecteduser"]) == 1) {
                    $_SESSION['spbuser'] = $_POST["selecteduser"][0];
                    $showSpb = true;
                }
            }
            if(isset($_POST['delsub']) && $_SESSION["loggedinman"] == true) {
                $db = new PDO("mysql:host=mysql.server.url;dbname=databasename","username","PaSswoRt123");
                if ($db->connect_error) {
                    die("Verbindung fehlgeschlagen");
                }
                $deluser = $db->prepare("DELETE FROM `benutzer` WHERE `benutzer`.`bn` = ?;");
                for($i = 0; $i < count($_SESSION['deluser']); $i++) {
                    $deluser->execute([$_SESSION['deluser'][$i]]);
                    unlink("../sp/" . $_SESSION['deluser'][$i] . ".txt");
                    unlink("../mt/" . $_SESSION['deluser'][$i] . ".txt");
                }
                $_SESSION['deluser'] = null;
                $showDel = false;
            }
            if(isset($_POST['spsub']) && $_SESSION["loggedinman"] == true) {
                file_put_contents("../sp/" . $_SESSION['spbuser'] . ".txt",$_POST["spin"]);
                $_SESSION['spbuser'] = null;
                $showSpb = false;
            }
            if($_SESSION["loggedinman"] == true) {
                $db = new PDO("mysql:host=mysql.server.url;dbname=databasename","username","PaSswoRt123");
                if ($db->connect_error) {
                    die("Verbindung fehlgeschlagen");
                }
                $allbn = $db->prepare("SELECT bn,es,ed,lc FROM `benutzer`");
                $allbn->execute();
                $result = $allbn->fetchAll(PDO::FETCH_ASSOC);
                $benutzer = [];
                foreach($result as $data) {
                    $benutzer[count($benutzer)] = $data['bn'];
                }
                $db = null;
                echo "<BR><BR><p style='font-size: 2vh; line-height: 4vh; height: 4vh; margin: 0; padding: 0;'>Benutzer (" . count($benutzer) . "):</p>" . '<button onclick="refreshPage()" style="position: fixed; top: 5px; right: 5px; font-size: 2vh; line-height: 4vh; height: 4vh; margin: 0; padding: 0;">Neuladen</button>';
                echo "<form method='post'><table style='width: 96vw; margin: 1vw; border: none; border-collapse: collapse;'>
                <tr class='dataset' style='background-color: #121212; border: none; border-collapse: collapse; border-bottom: 4px solid #202020; height: 4vh;'><td><input id='alle' type='checkbox' onchange='selalle()' style='height: 4vh; width: 4vh;'><label style='font-size: 2vh; line-height: 4vh; height: 4vh; margin: 0; padding: 0; margin-left: 10px;'><b>Alle auswählen</b></label></input><input id='aller' type='checkbox' onchange='selaller()' style='height: 4vh; width: 4vh;'><label style='font-size: 2vh; line-height: 4vh; height: 4vh; margin: 0; padding: 0; margin-left: 10px;'><b>Alle aktiven auswählen</b></label></input></td></tr>";
                for($i = 0; $i < count($benutzer); $i++) {
                    echo "<tr class='dataset' style='background-color: #151515; border: none; border-collapse: collapse; border-bottom: 1px solid #111; height: 4vh;'><td class='$benutzer[$i]' style='height: 4vh;'><input class='selu' name='selecteduser[]' type='checkbox' value='$benutzer[$i]' style='height: 4vh; width: 4vh;'><label style='font-size: 2vh; line-height: 4vh; height: 4vh; margin: 0; padding: 0; margin-left: 10px; position: absolute;'>$benutzer[$i]</label></input></td></tr>";
                }
                echo '</table><input name="mt" style="font-size: 2vh; line-height: 4vh; height: 4vh; margin: 0; padding: 0;"><input type="submit" value="Mitteilung hinzufügen" name="ams" style="font-size: 2vh; line-height: 4vh; height: 4vh; margin: 0; padding: 0;"><input type="submit" value="Benutzernamen bearbeiten" name="bnb" style="font-size: 2vh; line-height: 4vh; height: 4vh; margin: 0; padding: 0;"><input name="npw" style="font-size: 2vh; line-height: 4vh; height: 4vh; margin: 0; padding: 0;"><input type="submit" value="Passwort setzen" name="pws" style="font-size: 2vh; line-height: 4vh; height: 4vh; margin: 0; padding: 0;"><input type="submit" value="Stundenplan bearbeiten" name="spb" style="font-size: 2vh; line-height: 4vh; height: 4vh; margin: 0; padding: 0;"><input type="submit" value="Alle Daten löschen" name="adl" style="font-size: 2vh; line-height: 4vh; height: 4vh; margin: 0; padding: 0; border: 1px solid red; color: red;"></form>
                <script>
                    function selalle() {
                        var elems = document.getElementsByClassName("selu");
                        if(document.getElementById("alle").checked == true) {
                            for(i = 0; i < elems.length; i++) {
                                elems[i].checked = true;
                            }
                        } else {
                            for(i = 0; i < elems.length; i++) {
                                elems[i].checked = false;
                            }
                        }
                    }
                    function selaller() {
                        var elems = document.getElementsByClassName("selu");
                        if(document.getElementById("aller").checked == true) {
                            for(i = 0; i < elems.length; i++) {
                                if(elems[i].value != "fremder.benutzer" && elems[i].value != "demo") {
                                    elems[i].checked = true;
                                }
                            }
                        } else {
                            for(i = 0; i < elems.length; i++) {
                                if(elems[i].value != "fremder.benutzer" && elems[i].value != "demo") {
                                    elems[i].checked = false;
                                }
                            }
                        }
                    }
                </script>';
                if($showDel) {
                    echo '<form method="post"><input name="delsub" type="submit" value="Bestätigen" style="font-size: 2vh; line-height: 4vh; height: 4vh; margin: 0; padding: 0; border: 1px solid red; color: red;"></form>';
                    var_dump($_SESSION['deluser']);
                }
                if($showSpb) {
                    $spcont = file_get_contents("../sp/" . $_SESSION['spbuser'] . ".txt");
                    echo '<form method="post"><textarea name="spin" style="font-size: 1vw; line-height: 1vw; height: 5vw; width: 98vw; margin: 0; padding: 0; word-wrap: break-word;">' . $spcont . '</textarea><input name="spsub" type="submit" value="Speichern" style="font-size: 2vh; line-height: 4vh; height: 4vh; margin: 0; padding: 0; border: 1px solid green; color: green;"><input type="submit" value="Abbrechen" style="font-size: 2vh; line-height: 4vh; height: 4vh; margin: 0; padding: 0; border: 1px solid orange; color: orange;"></form>';
                    echo $_SESSION['spbuser'];
                }
                echo $style;
            } else {
                echo '  <form class="login" method="post" id="loginform">
                            <input class="login" id="pw" name="pw" placeholder="Passwort" type="password">
                            <input class="login clickable" id="bestaetigen" name="submit" type="submit" value="Anmelden">
                        </form>';
            }
        ?>
    </body>

</html>