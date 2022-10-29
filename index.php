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
            $version = "final_1.0";
            error_reporting(E_ERROR | E_PARSE);
?>
<!doctype html>


<html style="background-color: black;">
    
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="styleschlicht.css">
        <link rel="icon" href="images/icon.png"> 
        <title>Wasserturmplan</title>

        <meta name="description" content="Übersicht der Vertretungsstunden auf dem eigenen Stundenplan">
        <meta property="og:title" content="Wasserturmplan">
        <meta property="og:url" content="https://wasserturmplan.steffenmolitor.de">
        <meta property="og:image" content="/images/wasserturmplan_600x600_dark.png">

        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="apple-touch-icon" href="images/wasserturmplan_iOS.png">
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
        <script>
            //Stundenplanupload HTML zu Text (für Weiterverarbeitung auf dem Server) Funktion
            function getStunden() {
                var tbodys = document.querySelectorAll("td > table > tbody");
                var cont = [];
                var rheco = 0;
                var lico = 0;
                for(i = 1; i < tbodys.length; i++){
                    if(tbodys[i].innerText != null && tbodys[i].innerText.includes("*") && !tbodys[i].innerText.includes("RHE") && !tbodys[i].innerText.includes("LI")) {
                        stelle = cont.length;
                        for(j = 0; j < tbodys[i].childNodes.length; j++){
                            if(j == 0 && tbodys[i].childNodes[j].innerText != undefined){
                                cont[stelle] = tbodys[i].childNodes[j].innerText.replace(/\s/g, '').replace("*", '');
                            } else if(tbodys[i].childNodes[j].innerText != undefined) {
                                cont[stelle] += "%" + tbodys[i].childNodes[j].innerText.replace(/\s/g, '');
                            }
                        }
                        cont[stelle] += "%" + tbodys[i].parentElement.parentElement.rowSpan;
                        cont[stelle] += "%" + tbodys[i].parentElement.parentElement.colSpan;
                    } else if(tbodys[i].innerText.includes("RHE")) {
                        if(rheco % 2 == 0) {
                            cont[cont.length] = "RHEG1%GEL%?%Hj1%4%6";
                            cont[cont.length] = "RHEG1%GEL%?%Hj2%5%6";
                        }
                        rheco++;
                    } else if(tbodys[i].innerText.includes("LI")) {
                        if(lico % 2 == 0) {
                            cont[cont.length] = "LIG1%?%?%Hj1%4%6";
                            cont[cont.length] = "LIG1%?%?%Hj2%5%6";
                        }
                        rheco++;
                    } else if(tbodys[i].childNodes[0] != null && tbodys[i].childNodes[0].childNodes[0].innerHTML == "") {
                        cont[cont.length] = "-";
                    }
                }
                var templateText = "&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&_&";
                var templateTAr = templateText.split("_");
                var stelle = 0;
                var outputText;
                for(i = 0; i < cont.length; i++) {
                    if(i != 0) {
                        stundeXm1 = cont[i-1].split("%");
                    }
                    stundeX = cont[i].split("%");
                    if(i < cont.length - 1) {
                        stundeXp1 = cont[i+1].split("%");
                    }
                    while(templateTAr[stelle] != "&") {
                        stelle++;
                    }
                    if(stundeX.length == 5) {
                        if(stundeX[0].length >= 3 && (stundeX[0].slice(-2,-1).includes("L") || stundeX[0].slice(-2,-1).includes("G")) && !isNaN(stundeX[0].slice(-1))) {
                            templateTAr[stelle] = stundeX[0].substr(0,stundeX[0].length - 2).replace("IF8","IF").replace("IF0","IF").replace("E5","E").replace("S8","S").replace("S0","S");
                            templateTAr[stelle] += "<BR>" + stundeX[0].substr(stundeX[0].length - 2,2);
                            templateTAr[stelle] += "<BR>" + stundeX[1] + "<BR>" + stundeX[2];
                        } else {
                            templateTAr[stelle] = stundeX[0].replace("IF8","IF").replace("IF0","IF").replace("E5","E").replace("S8","S").replace("S0","S") + "<BR>?";
                            templateTAr[stelle] += "<BR>" + stundeX[1] + "<BR>" + stundeX[2];
                        }
                        if(stundeX[3] == "3" || stundeX[3] == "4") {
                            templateTAr[stelle + 5] = templateTAr[stelle];
                        } else if(stundeX[3] == "5" || stundeX[4] == "6") {
                            templateTAr[stelle + 5] = templateTAr[stelle];
                            templateTAr[stelle + 10] = templateTAr[stelle];
                        }
                    } else if(stundeX.length == 6) {
                        if(stundeX[3] == document.getElementById("pHj").innerText.replace(/\s/g, '') && stundeX[5] == "12") {
                            if(stundeX[0].length >= 3 && (stundeX[0].slice(-2,-1).includes("L") || stundeX[0].slice(-2,-1).includes("G")) && !isNaN(stundeX[0].slice(-1))) {
                                templateTAr[stelle] = stundeX[0].substr(0,stundeX[0].length - 2).replace("IF8","IF").replace("IF0","IF").replace("E5","E").replace("S8","S").replace("S0","S");
                                templateTAr[stelle] += "<BR>" + stundeX[0].substr(stundeX[0].length - 2,2);
                                templateTAr[stelle] += "<BR>" + stundeX[1] + "<BR>" + stundeX[2];
                            } else {
                                templateTAr[stelle] = stundeX[0].replace("IF8","IF").replace("IF0","IF").replace("E5","E").replace("S8","S").replace("S0","S") + "<BR>?";
                                templateTAr[stelle] += "<BR>" + stundeX[1] + "<BR>" + stundeX[2];
                            }
                            if(stundeX[4] == "3" || stundeX[4] == "4") {
                                templateTAr[stelle + 5] = templateTAr[stelle];
                            } else if(stundeX[4] == "5" || stundeX[4] == "6") {
                                templateTAr[stelle + 5] = templateTAr[stelle];
                                templateTAr[stelle + 10] = templateTAr[stelle];
                            }
                        } else if(stundeX[3] != document.getElementById("pHj").innerText.replace(/\s/g, '') && stundeX[5] == "12") {
                            templateTAr[stelle] = "-";
                        } else if(stundeX[3] == document.getElementById("pHj").innerText.replace(/\s/g, '') && stundeX[5] == "6") {
                            if(stundeX[0].length >= 3 && (stundeX[0].slice(-2,-1).includes("L") || stundeX[0].slice(-2,-1).includes("G")) && !isNaN(stundeX[0].slice(-1))) {
                                templateTAr[stelle] = stundeX[0].substr(0,stundeX[0].length - 2).replace("IF8","IF").replace("IF0","IF").replace("E5","E").replace("S8","S").replace("S0","S");
                                templateTAr[stelle] += "<BR>" + stundeX[0].substr(stundeX[0].length - 2,2);
                                templateTAr[stelle] += "<BR>" + stundeX[1] + "<BR>" + stundeX[2];
                            } else {
                                templateTAr[stelle] = stundeX[0].replace("IF8","IF").replace("IF0","IF").replace("E5","E").replace("S8","S").replace("S0","S") + "<BR>?";
                                templateTAr[stelle] += "<BR>" + stundeX[1] + "<BR>" + stundeX[2];
                            } 
                            if(stundeX[4] == "3" || stundeX[4] == "4") {
                                templateTAr[stelle + 5] = templateTAr[stelle];
                            } else if(stundeX[4] == "5" || stundeX[4] == "6") {
                                templateTAr[stelle + 5] = templateTAr[stelle];
                                templateTAr[stelle + 10] = templateTAr[stelle];
                            }
                        } else if(stundeX[3] != document.getElementById("pHj").innerText.replace(/\s/g, '')) {
                            /*if(pHj = "Hj1" && stundeX[5] == "6" && stundeXm1[0] != "-") {
                                stelle--;
                            }
                            if(pHj = "Hj2" && stundeX[5] == "6" && stundeXp1[0] != "-") {*/
                                stelle--;
                            //}
                        }
                    } else if(stundeX[0] == "-") {
                        templateTAr[stelle] = "-";
                    }
                    stelle++;
                }
                templateTAr[templateTAr.length - 1] = document.getElementById("pStufe").innerText;
                outputText = templateTAr.join("_");
                document.getElementById("spwin").outerHTML = "";
                document.getElementById("sptext").value = outputText;
                document.getElementById("spsub").submit();
            }
        </script>
        <?php
            echo '<script>console.log("' . $version . '")</script>';
        ?>
    </head>
    <?php
        if((!isset($_SESSION['user']) || $_SESSION['user'] == "" || empty($_SESSION['user'])) && !isset($_POST['login']) && !isset($_POST['reg'])) {
            $auth = false;
            $showForm = true;
            echo '<body id="body" onload="fachklassen(); stoploadslow();">';
        } else {
            echo '<body id="body" onload="fachklassen(); stopload();">';
        }
        
        if(isset($_COOKIE['reload'])) {
            echo '<div id="rlbar" style="width: 0vw; opacity: 1;"></div>';
        }
    ?>
    <div id="loadani" style="display: flex; justify-content: center; align-items: center; height: 100vh; width: 100vw; background: transparent; position: fixed; left: 0; top: 0; z-index: 99999;">
        <img id="loadanimation" src="images/load4.gif" style="display: inline-block; height: 300px; width: 300px; z-index: 99999;">
    </div>
    <form method="post" id="spsub" style="display:none">
        <input type="text" name="sptext" id="sptext">
    </form>
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
                    if(isset($_POST['ab'])) {
                        $key = bin2hex(random_bytes(128));
                        echo '<script>Cookies.set("ab", "' . $bn . '-' . $key . '", { expires: 30 });</script>';
                        $getab = $db->prepare("SELECT ab FROM `benutzer` WHERE bn = ?");
                        $getab->execute([$bn]);
                        $resultab = $getab->fetch()["ab"];
                        if($resultab == "" || $resultab == null) {
                            $nab = $key;
                        } else {
                            $nab = $resultab . "," . $key;
                        }
                        $setab = $db->prepare("UPDATE benutzer SET ab = ? WHERE bn = ?;");
                        $setab->execute([$nab,$bn]);
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
            $style = '<link rel="stylesheet" href="styleschlicht.css">';
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
            if(($_SESSION["user"] == "" || $_SESSION["user"] == null) && isset($_COOKIE["ab"])) {
                $bn = str_replace(" ","",strtolower(explode("-",$_COOKIE['ab'])[0]));
                $pw = explode("-",$_COOKIE['ab'])[1];

                $db = new PDO("mysql:host=mysql.server.url;dbname=databasename","username","PaSswoRt123");
                if ($db->connect_error) {
                    die("Verbindung fehlgeschlagen");
                }
                $getpw = $db->prepare("SELECT ab FROM `benutzer` WHERE bn = ?");
                $getpw->execute([$bn]);
                $result = $getpw->fetch()["ab"];
                if(in_array($pw, explode(",", $result))) {
                    //echo "<p id='output' style='color: green;'>Passwort richtig!</p><BR><p>Eingeloggter Nutzer: $bn</p>";
                    $_SESSION["user"] = $bn;
                    $getlc = $db->prepare("SELECT lc FROM `benutzer` WHERE bn = ?");
                    $getlc->execute([$bn]);
                    $resultlc = $getlc->fetch()["lc"];
                    $resultlc++;
                    $setlc = $db->prepare("UPDATE benutzer SET lc = ? WHERE bn = ?;");
                    $setlc->execute([$resultlc,$_SESSION["user"]]);
                    $auth = true;
                    $showForm = false;
                    if(file_exists("mt/" . $_SESSION['user'] . ".txt")) {
                        $showMt = true;
                    }
                } else {
                    echo '<script>Cookies.remove("ab");</script>';
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

            $ff = 2;
            if(isset($_POST['ffs'])) {
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
                    $einst[1] = $_POST["ffw"];
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

            if($ff == "personxyff") {
                $style .= '<link rel="stylesheet" href="personxyff.css">';
            } else if($ff == 1) {
                $style .= '<link rel="stylesheet" href="ff_1.css">';
            } else if($ff == 2) {
                $style .= '<link rel="stylesheet" href="ff_2.css">';
            } else {
                $style .= '<link rel="stylesheet" href="ff_0.css">';
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
                    $style .= '<link rel="stylesheet" href="theme_dark.css">';
                } else {
                    $style .= '<link rel="stylesheet" href="theme_light.css">';
                }
            } else {
                if($_SESSION["darkmode"]) {
                    $style .= '<link rel="stylesheet" href="theme_dark.css">';
                } else {
                    $style .= '<link rel="stylesheet" href="theme_light.css">';
                }
            }
            if(isset($_POST['lo'])) {
                $db = new PDO("mysql:host=mysql.server.url;dbname=databasename","username","PaSswoRt123");
                if ($db->connect_error) {
                    die("Verbindung fehlgeschlagen");
                }
                $getab = $db->prepare("SELECT ab FROM `benutzer` WHERE bn = ?");
                $getab->execute([$bn]);
                $resultab = $getab->fetch()["ab"];
                $abarray = explode(",", $resultab);
                if (($key = array_search(explode("-", $_COOKIE['ab'])[1], $abarray)) !== false) {
                    unset($abarray[$key]);
                }
                $nab = implode(",", $abarray);
                $setab = $db->prepare("UPDATE benutzer SET ab = ? WHERE bn = ?;");
                $setab->execute([$nab,$bn]);
                echo '<script>Cookies.remove("ab");</script>';
                $auth = false;
                $showForm = true;
                session_unset();
                session_destroy();
                $db = null;
            }
            if(isset($_POST['ao'])) {
                $bn = $_SESSION["user"];
                $showOptions = true;
                $showForm = false;
                $auth = false;
            }
            if(isset($_POST['sh'])) {
                $bn = $_SESSION["user"];
                $showUpload = true;
                $showForm = false;
                $auth = false;
                $showedit = true;
            }
            if(isset($_POST['uf']) && $_POST['stufe'] != null && !empty($_FILES['file']['tmp_name']) && file_exists($_FILES['file']['tmp_name']) && $_POST['Hj'] != null) {
                $sp_cont = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is','',preg_replace('#<?php(.*?)?>#is','',preg_replace('#<script(.*?)>(.*?)</script>#is','',str_replace('"',"'",file_get_contents($_FILES['file']['tmp_name'])))));
                echo "<p id='pStufe' style='display: none'>" . $_POST['stufe'] . "</p><p id='pHj' style='display: none'>" . $_POST['Hj'] . "</p><div id='spwin' style='display: none'>$sp_cont</div><script>getStunden()</script>";
                $showUpload = false;
                $showOptions = false;
                $auth = false;
                $showedit = true;
                $showForm = false;
                $bn = $_SESSION["user"];
            } else if(isset($_POST['uf'])) {
                $showUpload = true;
                $showOptions = false;
                $showForm = false;
                $auth = false;
                $showedit = true;
                $bn = $_SESSION["user"];
            }
            if(!empty($_POST["sptext"])) {
                $bn = $_SESSION["user"];
                file_put_contents("sp/" . $bn . ".txt",$_POST["sptext"]);
                $showUpload = false;
                $showOptions = false;
                $showForm = false;
                $auth = true;
            }
            if(isset($_POST['sb'])) {
                $showUpload = false;
                $showOptions = false;
                $showForm = false;
                $auth = false;
                $showedit = true;
                $bn = $_SESSION["user"];
            }
            if(isset($_POST['cpws'])) {
                $db = new PDO("mysql:host=mysql.server.url;dbname=databasename","username","PaSswoRt123");
                if ($db->connect_error) {
                    die("Verbindung fehlgeschlagen");
                }
                $getaktpw = $db->prepare("SELECT pw FROM `benutzer` WHERE bn = ?");
                $getaktpw->execute([$_SESSION["user"]]);
                $aktpw = $getaktpw->fetch()["pw"];
                if(hash("sha512", $_POST["akpw"], false) == $aktpw && $_POST["nepw"] == $_POST["nepww"] && $_SESSION["user"] != "demo") {
                    $setnnpw = $db->prepare("UPDATE benutzer SET pw = ? WHERE bn = ?;");
                    $newpassword = hash("sha512", $_POST["nepw"], false);
                    $setnnpw->execute([$newpassword,$_SESSION["user"]]);
                } else {
                    echo "<p class='output errorout' onload='destroy(this)'><img class='errimg' src='images/error.svg'>Daten falsch</p>";
                }
                $db = null;
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
            
            function getStundenDaten($stundestelle) {
                global $showUpload;
                global $showOptions;
                global $showForm;
                global $auth;
                global $showedit;
                global $bn;
                $showUpload = false;
                $showOptions = false;
                $showForm = false;
                $auth = false;
                $showedit = true;
                $bn = $_SESSION["user"];
                $_SESSION["stdstelle"] = $stundestelle;
            }
            function getTag($n) {
                $n -= 1;
                if($n == 0 || $n >= 5) {
                    return "Montag";
                } else if($n == 1) {
                    return "Dienstag";
                } else if($n == 2) {
                    return "Mittwoch";
                } else if($n == 3) {
                    return "Donnerstag";
                } else if($n == 4) {
                    return "Freitag";
                }
            }
            function getVp1() {
                return file_get_contents("plan/" . getTag(date("N")) . ".vp");
            }
            function getVp2() {
                return file_get_contents("plan/" . getTag(date("N") + 1) . ".vp");
            }
            if(isset($_POST["spaes"])) {
                $sptxt = file_get_contents("sp/" . $_SESSION["user"] . ".txt", false);
                $sptxtar = explode("_", $sptxt);
                if($_POST["fachname"] == "-") {
                    $sptxtar[$_SESSION["stdstelle"]] = "-";
                } else if($_SESSION["stufeni"] == "EF" || $_SESSION["stufeni"] == "Q1" || $_SESSION["stufeni"] == "Q2" || $_SESSION["stufeni"] == null) {
                    $sptxtar[$_SESSION["stdstelle"]] = $_POST["fachname"] . "<BR>" . $_POST["fachkurs"] . "<BR>" . $_POST["fachlehrer"] . "<BR>" . $_POST["fachraum"];
                } else {
                    $sptxtar[$_SESSION["stdstelle"]] = $_POST["fachname"] . "<BR>" . $_POST["fachlehrer"] . "<BR>" . $_POST["fachraum"];
                }
                if($_SESSION["stufeni"] != "?") {
                    $sptxtar[40] = $_SESSION["stufeni"];
                }
                $sptxt = implode("_",$sptxtar);
                file_put_contents("sp/" . $_SESSION["user"] . ".txt", $sptxt);
                $showUpload = false;
                $showOptions = false;
                $showForm = false;
                $auth = false;
                $showedit = true;
                $bn = $_SESSION["user"];
                $_SESSION["stdstelle"] = $stundestelle;
                getStundenDaten($_SESSION["stdstelle"]);
            }
            if(isset($_POST['aml'])) {
                $showMt = false;
                unlink("mt/" . $_SESSION['user'] . ".txt");
            }
            if(isset($_POST['exit'])) {
                $showUpload = false;
                $showOptions = false;
                $showForm = false;
                $showedit = false;
                $auth = true;
                $bn = $_SESSION["user"];
            }
            if(isset($_POST['exitu'])) {
                $showUpload = false;
                $showOptions = false;
                $showForm = false;
                $showedit = true;
                $auth = false;
                $bn = $_SESSION["user"];
            }
            if(isset($_POST['cellfrei']) && !empty($_POST['sellist'])) {
                $selection = explode(",",$_POST['sellist']);
                $selection = str_replace("mo","0",$selection);
                $selection = str_replace("di","1",$selection);
                $selection = str_replace("mi","2",$selection);
                $selection = str_replace("do","3",$selection);
                $selection = str_replace("fr","4",$selection);
                $sptxt = file_get_contents("sp/" . $_SESSION["user"] . ".txt", false);
                $sptxtar = explode("_", $sptxt);
                for($i = 0; $i < count($selection); $i++) {
                    $stelle = substr($selection[$i],1,1);
                    $stelle -= 1;
                    $stelle *= 5;
                    $stelle += substr($selection[$i],0,1);
                    $sptxtar[$stelle] = "-";
                }
                $sptxt = implode("_",$sptxtar);
                file_put_contents("sp/" . $_SESSION["user"] . ".txt", $sptxt);
                $showUpload = false;
                $showOptions = false;
                $showForm = false;
                $showedit = true;
                $auth = false;
                $bn = $_SESSION["user"];
            }
            if(isset($_POST['cellbearb']) && !empty($_POST['sellist']) || isset($_POST['cellstufe'])) {
                $showUpload = false;
                $showOptions = false;
                $showForm = false;
                $showedit = true;
                $auth = false;
                $bn = $_SESSION["user"];
            }
            if(isset($_POST['savechanges'])) {
                $selection = explode(",",$_POST['sellist']);
                $selection = str_replace("mo","0",$selection);
                $selection = str_replace("di","1",$selection);
                $selection = str_replace("mi","2",$selection);
                $selection = str_replace("do","3",$selection);
                $selection = str_replace("fr","4",$selection);
                $sptxt = file_get_contents("sp/" . $_SESSION["user"] . ".txt", false);
                $sptxtar = explode("_", $sptxt);
                for($i = 0; $i < count($selection); $i++) {
                    $stelle = intval(substr($selection[$i],1,1),10);
                    $stelle -= 1;
                    $stelle *= 5;
                    $stelle += intval(substr($selection[$i],0,1),10);
                    $allao = $_POST['fachname'] . $_POST['fachkurs'] . $_POST['fachlehrer'] . $_POST['fachraum'];
                    if(!str_contains($allao," ") && !str_contains($allao,"_") && !str_contains($allao,"<BR>") && strlen($_POST['fachname']) > 0 && strlen($_POST['fachname']) < 15 && strlen($_POST['fachkurs']) < 4 && strlen($_POST['fachlehrer']) > 0 && strlen($_POST['fachlehrer']) < 4 && strlen($_POST['fachraum']) > 0 && strlen($_POST['fachraum']) < 5) {
                        if($sptxtar[40] == "EF" || $sptxtar[40] == "Q1" || $sptxtar[40] == "Q2" || $sptxtar[40] == "STUFE") {
                            $sptxtar[$stelle] = $_POST['fachname'] . "<BR>" . $_POST['fachkurs'] . "<BR>" . $_POST['fachlehrer'] . "<BR>" . $_POST['fachraum'];
                        } else {
                            $sptxtar[$stelle] = $_POST['fachname'] . "<BR>" . $_POST['fachlehrer'] . "<BR>" . $_POST['fachraum'];
                        }
                    } else {
                        echo "<p class='output errorout' onload='destroy(this)'><img class='errimg' src='images/error.svg'>Falsche Eingabe</p>";
                    }
                }
                $sptxt = implode("_",$sptxtar);
                file_put_contents("sp/" . $_SESSION["user"] . ".txt", $sptxt);
                $showUpload = false;
                $showOptions = false;
                $showForm = false;
                $showedit = true;
                $auth = false;
                $bn = $_SESSION["user"];
            }
            if(isset($_POST['savestufe']) && !empty($_POST['setstufe'])) {
                $sptxt = file_get_contents("sp/" . $_SESSION["user"] . ".txt", false);
                $sptxtar = explode("_", $sptxt);
                $sptxtar[40] = $_POST['setstufe'];
                $sptxt = implode("_",$sptxtar);
                file_put_contents("sp/" . $_SESSION["user"] . ".txt", $sptxt);
                $showUpload = false;
                $showOptions = false;
                $showForm = false;
                $showedit = true;
                $auth = false;
                $bn = $_SESSION["user"];
            }
            if($showForm) {
                echo "<p id='version'>Version: " . $version . "</p>
                    <p class='titlesch showtitle'>Anmeldung</p>";
            }
            $abtxt = '  <div id="kl" name="kl" method="post" class="ab">
                            <p class="setoption"></p>
                            <p id="ab_text">Angemeldet bleiben</p><div id="ab_box"><input onclick="checkcheckbox()" name="ab" type="checkbox" id="ab_check" value="true"></div>
                        </div>
                        <p id="akt">Diese Funktion ist abhängig von Cookies</p>
                        ';
            if($showForm) {
                echo "<h2 style='display: block !important; position: absolute; bottom: 20vh; left: calc(20vw - 20pt); width: 60vw; z-index: 999999; background-color: #111; border-radius: 20pt; padding: 20pt; text-align: center;'>Wegen der jetzt zugänglichen Möglichkeit einer offiziellen digitalen Stundenplan-Vertretungsplan Kombination werde ich den Wasserturmplan nicht mehr aktuell halten. Die Links zu Untis: <a href='https://itunes.apple.com/at/app/untis-mobile/id926186904' style='display: inline-block !important; color: #ff6033; text-decoration: underline; cursor: pointer;'>iOS</a>, <a href='https://play.google.com/store/apps/details?id=com.grupet.web.app' style='display: inline-block !important; color: #ff6033; text-decoration: underline; cursor: pointer;'>Google Play</a> (Untis Mobile) und <a href='https://herakles.webuntis.com/WebUntis/?school=wh-gym-münster#/basic/login' style='display: inline-block !important; color: #ff6033; text-decoration: underline; cursor: pointer;'>Web-Interface</a> (WebUntis). Wer dennoch einmal reingucken will kann das auch ohne Account mit dem öffentlichen Demo Account (Benutzername: demo, Passwort: demo). Wer seinen Account gelöscht haben möchte, meldet sich über die angegeben Kontaktmöglichkeiten.</h2>";
            }
            if(isset($_POST['login']) && $showForm) {
                echo '<div class="logind"><img draggable="false" id="titleimg" src="images/wasserturmplan_600x600.png"><form id="choose" method="post"><input class="clickable cur" type="submit" name="login" value="Anmelden" id="login"><input type="submit" class="clickable" name="reg" id="reg" value="Registrieren"></form><form method="post" style="display:block; padding: 0; margin: 0;"><div class="login" id="loginform"><input class="login" id="bn" name="bn" placeholder="Benutzername" autocomplete="off"><hr class="sep"><input class="login" id="pw" name="pw" placeholder="Passwort" type="password"><hr class="sep"><input class="login clickable" id="bestaetigen" name="submit" type="submit" value="Anmelden"></div>' . $abtxt . '</form></div>
                <div id="links"><a id="datenschutz" href="/datenschutz">Datenschutz</a><a id="impressum" href="/impressum">Impressum und Kontakt</a><a id="disclaimer" href="/disclaimer">Disclaimer</a><a id="readme" href="/README.md">README</a></div><div id="safeo"></div>';
            } else if(isset($_POST['reg']) && $showForm) {
                echo '<div class="logind"><img draggable="false" id="titleimg" src="images/wasserturmplan_600x600.png"><form id="choose" method="post"><input class="clickable" type="submit" name="login" value="Anmelden" id="login"><input type="submit" class="clickable cur" name="reg" id="reg" value="Registrieren"></form><form method="post" style="display:block; padding: 0; margin: 0;"><div class="login" id="loginform"><input class="login" id="nbn" name="nbn" placeholder="Benutzername (vorname.nachname)" autocomplete="off"><hr class="sep"><input class="login" id="npw" name="npw" placeholder="Passwort" type="password"><hr class="sep"><input class="login" id="rnpw" name="rnpw" placeholder="Passwort wiederh." type="password"><hr class="sep"><input class="login clickable" id="nbestaetigen" name="nsubmit" type="submit" value="Registrieren"></div>' . $abtxt . '</div></div>
                <div id="links"><a id="datenschutz" href="/datenschutz">Datenschutz</a><a id="impressum" href="/impressum">Impressum und Kontakt</a><a id="disclaimer" href="/disclaimer">Disclaimer</a><a id="readme" href="/README.md">README</a></div><div id="safeo"></div>';
            } else if($showForm){
                echo '<div class="logind"><img draggable="false" id="titleimg" src="images/wasserturmplan_600x600.png"><form id="choose" method="post"><input class="clickable cur" type="submit" name="login" value="Anmelden" id="login"><input type="submit" class="clickable" name="reg" id="reg" value="Registrieren"></form><form method="post" style="display:block; padding: 0; margin: 0;"><div class="login" id="loginform"><input class="login" id="bn" name="bn" placeholder="Benutzername" autocomplete="off"><hr class="sep"><input class="login" id="pw" name="pw" placeholder="Passwort" type="password"><hr class="sep"><input class="login clickable" id="bestaetigen" name="submit" type="submit" value="Anmelden"></div>' . $abtxt . '</form></div>
                <div id="links"><a id="datenschutz" href="/datenschutz">Datenschutz</a><a id="impressum" href="/impressum">Impressum und Kontakt</a><a id="disclaimer" href="/disclaimer">Disclaimer</a><a id="readme" href="/README.md">README</a></div><div id="safeo"></div>';
            } else if($auth) {
                $file = file_get_contents('sp/' . $bn . '.txt');
                $stunden = explode("_", $file);
                $mo = [null,null,null,null,null,null,null,null];
                $di = [null,null,null,null,null,null,null,null];
                $mi = [null,null,null,null,null,null,null,null];
                $do = [null,null,null,null,null,null,null,null];
                $fr = [null,null,null,null,null,null,null,null];
                for($i = 0; $i < 8; $i++) {
                    for($j = 0; $j < 5; $j++) {
                        if($stunden[$j + $i * 5] != null && $j == 0) {$mo[ceil($i)] = $stunden[$j + $i * 5];}
                        if($stunden[$j + $i * 5] != null && $j == 1) {$di[ceil($i)] = $stunden[$j + $i * 5];}
                        if($stunden[$j + $i * 5] != null && $j == 2) {$mi[ceil($i)] = $stunden[$j + $i * 5];}
                        if($stunden[$j + $i * 5] != null && $j == 3) {$do[ceil($i)] = $stunden[$j + $i * 5];}
                        if($stunden[$j + $i * 5] != null && $j == 4) {$fr[ceil($i)] = $stunden[$j + $i * 5];}
                    }
                }
                echo '
                    <button id="edittable" onclick="sp()">Bearbeiten</button>
                    <button id="profile" onclick="profile()">' . $bn . '</button>
                    <p class="titlesch showtitle">Stundenplan</p>
                    <div id="spd">
                    <table id="stundenplan">
                        <tr class="sprow">
                            <th>Stunde</th><th>Montag</th><th>Dienstag</th><th>Mittwoch</th><th>Donnerstag</th><th>Freitag</th>
                        </tr>
                        <tr class="sprow">
                            <td class="std">1.<BR>(7:45 - 8:45)</td><td id="mo1">' . $mo[0] . '</td><td id="di1">' . $di[0] . '</td><td id="mi1">' . $mi[0] . '</td><td id="do1">' . $do[0] . '</td><td id="fr1">' . $fr[0] . '</td>
                        </tr class="sprow">
                        <tr class="sprow">
                            <td class="std">2.<BR>(8:50 - 9:50)</td><td id="mo2">' . $mo[1] . '</td><td id="di2">' . $di[1] . '</td><td id="mi2">' . $mi[1] . '</td><td id="do2">' . $do[1] . '</td><td id="fr2">' . $fr[1] . '</td>
                        </tr>
                        <tr class="sprow">
                            <td colspan="6" class="gp">1. große Pause</td>
                        </tr>
                        <tr class="sprow">
                            <td class="std">3.<BR>(10:15 - 11:15)</td><td id="mo3">' . $mo[2] . '</td><td id="di3">' . $di[2] . '</td><td id="mi3">' . $mi[2] . '</td><td id="do3">' . $do[2] . '</td><td id="fr3">' . $fr[2] . '</td>
                        </tr>
                        <tr class="sprow">
                            <td class="std">4.<BR>(11:20 - 12:20)</td><td id="mo4">' . $mo[3] . '</td><td id="di4">' . $di[3] . '</td><td id="mi4">' . $mi[3] . '</td><td id="do4">' . $do[3] . '</td><td id="fr4">' . $fr[3] . '</td>
                        </tr>
                        <tr class="sprow">
                            <td colspan="6" class="gp">2. große Pause</td>
                        </tr>
                        <tr class="sprow">
                            <td class="std">5.<BR>(12:45 - 13:45)</td><td id="mo5">' . $mo[4] . '</td><td id="di5">' . $di[4] . '</td><td id="mi5">' . $mi[4] . '</td><td id="do5">' . $do[4] . '</td><td id="fr5">' . $fr[4] . '</td>
                        </tr>
                        <tr class="sprow">
                            <td colspan="6" class="gp">Mittagspause</td>
                        </tr>
                        <tr class="sprow">
                            <td class="std">6.<BR>(14:15 - 15:15)</td><td id="mo6">' . $mo[5] . '</td><td id="di6">' . $di[5] . '</td><td id="mi6">' . $mi[5] . '</td><td id="do6">' . $do[5] . '</td><td id="fr6">' . $fr[5] . '</td>
                        </tr>
                        <tr class="sprow">
                            <td class="std">7.<BR>(15:20 - 16:20)</td><td id="mo7">' . $mo[6] . '</td><td id="di7">' . $di[6] . '</td><td id="mi7">' . $mi[6] . '</td><td id="do7">' . $do[6] . '</td><td id="fr7">' . $fr[6] . '</td>
                        </tr>
                        <tr class="sprow">
                            <td class="std">8.<BR>(16:25 - 17:25)</td><td id="mo8">' . $mo[7] . '</td><td id="di8">' . $di[7] . '</td><td id="mi8">' . $mi[7] . '</td><td id="do8">' . $do[7] . '</td><td id="fr8">' . $fr[7] . '</td>
                        </tr>
                    </table>
                    <p id="akt">Der Wasserturmplan wird nicht mehr aktualisiert.</p>';/*Letzte Aktualisierung: <BR><span id="AKTDATE">';
                    if(time() - filemtime("plan/" . getTag(date("N")) . ".vp") < 15) {
                        echo 'Gerade';
                    } else if(time() - filemtime("plan/" . getTag(date("N")) . ".vp") < 60) {
                        echo 'Vor '. time() - filemtime("plan/" . getTag(date("N")) . ".vp") . ' Sekunden';
                    } else if((int) ((time() - filemtime("plan/" . getTag(date("N")) . ".vp")) / 60) == 1) {
                        echo 'Vor einer Minute';
                    } else if((int) ((time() - filemtime("plan/" . getTag(date("N")) . ".vp")) / 60) < 15) {
                        echo 'Vor '. (int) ((time() - filemtime("plan/" . getTag(date("N")) . ".vp")) / 60) . ' Minuten';
                    } else {
                        echo date('d.m.Y', filemtime("plan/" . getTag(date("N")) . ".vp")) . ' <BR>' . date('G:i:s', filemtime("plan/" . getTag(date("N")) . ".vp")) . ' Uhr';
                    }*/
                    echo /*'</span> | Stand: <span id="STANDDATE">' . file_get_contents("plan/STAND.vp") . '</span></p>*/'<p id="pentf">Entfall</p><p id="peva">EVA</p><p id="praumw">Raumwechsel</p><p id="pfreis">Freisetzung</p><p id="pvertr">Vertretung</p>
                    <p id="kurs" style="display: none;">' . $stunden[40] . '</p>
                    <p id="vp1" style="display: none;">' . getVp1() . '</p>
                    <p id="vp2" style="display: none;">' . getVp2() . '</p>
                    </div>
                    <div style="display: none;" id="po" class="win" onclick="pohide()"><form id="profileoptions" method="post"><input type="submit" id="ao" name="ao" value="Einstellungen-teilweise"><input type="submit" id="lo" name="lo" value="Abmelden"></form></div>
                    <div style="display: none;" id="spo" class="win" onclick="spohide()"><form id="spoptions" method="post"><input type="submit" id="sh" name="sh" value="Stundenplan hochladen"><input type="submit" id="sb" name="sb" value="Bearbeiten"></form></div>
                    <div id="safeo"></div>
                    <div class="navbar"><form class="barform" method="post"><input onclick="Cookies.set(\'reload\', \'true\');" type="submit" value="Neuladen" id="bearb"><p id="titlenav">Stundenplan</p><input name="lo" type="submit" value="" id="leave"></form></div>
                    <div class="tabbar"><form class="barform" method="post"><input type="submit" name="sb" value="" id="StpbT"><label for="StpbT">Bearbeiten</label><input type="submit" value="" id="HomeT" class="curtab"><label class="curtab" for="HomeT">Stundenplan</label><input name="ao" type="submit" value="" id="EinstT"><label for="EinstT">Einstellungen</label></form></div>
                ';
            } else if($showOptions) {
                $db = new PDO("mysql:host=mysql.server.url;dbname=databasename","username","PaSswoRt123");
                if ($db->connect_error) {
                    die("Verbindung fehlgeschlagen");
                }
                $getes = $db->prepare("SELECT es FROM `benutzer` WHERE bn = ?");
                $getes->execute([$_SESSION["user"]]);
                $resultest = $getes->fetch()["es"];
                $einstellungenfo = explode(";",$resultest);
                $db = null;
                if($einstellungenfo[0] == "auto") {
                    $thmoptions = '<option value="auto">Automatisch</option><option value="d">Dunkel</option><option value="l">Hell</option>';
                } else if($einstellungenfo[0] == "d") {
                    $thmoptions = '<option value="d">Dunkel</option><option value="auto">Automatisch</option><option value="l">Hell</option>';
                } else if($einstellungenfo[0] == "l") {
                    $thmoptions = '<option value="l">Hell</option><option value="auto">Automatisch</option><option value="d">Dunkel</option>';
                } else {
                    $thmoptions = '<option value="auto">Automatisch</option><option value="d">Dunkel</option><option value="l">Hell</option>';
                }
                if($einstellungenfo[1] == "0") {
                    $ffoptions = '<option value="0">Blasse Farben</option><option value="1">Leuchtende Farben</option><option value="2">Keine Farben</option>';
                } else if($einstellungenfo[1] == "1") {
                    $ffoptions = '<option value="1">Leuchtende Farben</option><option value="0">Blasse Farben</option><option value="2">Keine Farben</option>';
                } else if($einstellungenfo[1] == "2") {
                    $ffoptions = '<option value="2">Keine Farben</option><option value="0">Blasse Farben</option><option value="1">Leuchtende Farben</option>';
                } else {
                    $ffoptions = '<option value="0">Blasse Farben</option><option value="1">Leuchtende Farben</option><option value="2">Keine Farben</option>';
                }
                echo '<img draggable="false" id="titleimg" src="images/wasserturmplan_600x600.png">   
                        <p id="settitle" class="showtitle">Einstellungen</p><form method="post"><input name="exit" type="submit" id="exit" value=""></form>
                        <div id="settingscont">
                            <form id="cun" name="cun" method="post">
                                <p class="setoption">--bald--</p>
                                <input autocomplete="off" class="i1" id="akbn" name="akbn" type="text" placeholder="Aktu. Benutzername"><hr class="sep"><input autocomplete="off" class="i2" type="text" id="nebn" name="nebn" placeholder="Neuer Benutzername"><hr class="sep"><input autocomplete="off" class="i3" type="password" id="pwbbn" name="pwbbn" placeholder="Passwort"><hr class="sep"><input class="asub" name="cbns" type="submit" value="Ändern">
                            </form>
                            <form id="cpw" name="cpw" method="post">
                                <p class="setoption">Passwort ändern</p>
                                <input autocomplete="off" class="i1" type="password" id="akpw" name="akpw" placeholder="Aktu. Passwort"><hr class="sep"><input autocomplete="off" class="i2" id="nepw" name="nepw" type="password" placeholder="Neues Passwort"><hr class="sep"><input autocomplete="off" class="i3" type="password" id="nepww" name="nepww" placeholder="N. Passwort wiederh."><hr class="sep"><input class="asub" name="cpws" type="submit" value="Ändern">
                            </form>
                            <form id="thm" name="thm" method="post">
                                <p class="setoption">Theme</p>
                                <p class="seltext">Ausgewählt</p><select id="dmlm" name="dmlm" size="1">' . $thmoptions . '</select><hr class="sep"><input class="speichersub" name="thms" type="submit" value="Speichern">
                            </form>
                            <form id="ff" name="ff" method="post">
                                <p class="setoption">Fächerfarben</p>
                                <p class="seltext">Ausgewählt</p><select id="ffw" name="ffw">' . $ffoptions . '</select><hr class="sep"><input class="speichersub" name="ffs" type="submit" value="Speichern">
                            </form>
                            <form id="kl" name="kl" method="post">
                                <p class="setoption"></p>
                                <input id="kld" name="kld" type="submit" value="--bald--">
                            </form>
                            <div id="ph"></div>
                        </div>
                        <div id="safeo"></div>
                    <div class="navbar"><form class="barform" method="post"><input type="submit" name="nas" value="--bald--" id="bearb" style="cursor: help;"><p id="titlenav">Einstellungen</p><input name="lo" type="submit" value="" id="leave"></form></div>
                    <div class="tabbar"><form class="barform" method="post"><input type="submit" name="sb" value="" id="StpbT"><label for="StpbT">Bearbeiten</label><input type="submit" value="" id="HomeT"><label for="HomeT">Stundenplan</label><input class="curtab" name="ao" type="submit" value="" id="EinstT"><label class="curtab" for="EinstT">Einstellungen</label></form></div>';
            } else if($showedit) {
                $file = file_get_contents('sp/' . $bn . '.txt');
                $stunden = explode("_", $file);
                $mo = [null,null,null,null,null,null,null,null];
                $di = [null,null,null,null,null,null,null,null];
                $mi = [null,null,null,null,null,null,null,null];
                $do = [null,null,null,null,null,null,null,null];
                $fr = [null,null,null,null,null,null,null,null];
                for($i = 0; $i < 8; $i++) {
                    for($j = 0; $j < 5; $j++) {
                        if($stunden[$j + $i * 5] != null && $j == 0) {$mo[ceil($i)] = $stunden[$j + $i * 5];}
                        if($stunden[$j + $i * 5] != null && $j == 1) {$di[ceil($i)] = $stunden[$j + $i * 5];}
                        if($stunden[$j + $i * 5] != null && $j == 2) {$mi[ceil($i)] = $stunden[$j + $i * 5];}
                        if($stunden[$j + $i * 5] != null && $j == 3) {$do[ceil($i)] = $stunden[$j + $i * 5];}
                        if($stunden[$j + $i * 5] != null && $j == 4) {$fr[ceil($i)] = $stunden[$j + $i * 5];}
                    }
                }
                /*$inputa = '<form class="selfachform" method="post"><input type="submit" name="selfach" class="inputfach" value="';
                $inpute = '"></form>';
                for($i = 0; $i < 8; $i++) {
                    $mo[$i] = $inputa . $mo[$i] . '" id="emo'. $i + 1 . $inpute;
                }
                for($i = 0; $i < 8; $i++) {
                    $di[$i] = $inputa . $di[$i] . '" id="edi'. $i + 1 . $inpute;
                }
                for($i = 0; $i < 8; $i++) {
                    $mi[$i] = $inputa . $mi[$i] . '" id="emi'. $i + 1 . $inpute;
                }
                for($i = 0; $i < 8; $i++) {
                    $do[$i] = $inputa . $do[$i] . '" id="edo'. $i + 1 . $inpute;
                }
                for($i = 0; $i <form 8; $i++) {
                    $fr[$i] = $inputa . $fr[$i] . '" id="efr'. $i + 1 . $inpute;
                }*/
                $tagnummer = ["Montag" => 0, "Dienstag" => 1, "Mittwoch" => 2, "Donnerstag" => 3, "Freitag" => 4];
                echo '
                <p class="titlesch showtitle">Bearbeiten</p>
                <div id="spd">
                <table id="stundenplan">
                    <tr class="sprow">
                        <th>Stunde</th><th>Montag</th><th>Dienstag</th><th>Mittwoch</th><th>Donnerstag</th><th>Freitag</th>
                    </tr>
                    <tr class="sprow">
                        <td class="std">1.</td><td id="mo1" onclick="sel(this)">' . $mo[0] . '</td><td id="di1" onclick="sel(this)">' . $di[0] . '</td><td id="mi1" onclick="sel(this)">' . $mi[0] . '</td><td id="do1" onclick="sel(this)">' . $do[0] . '</td><td id="fr1" onclick="sel(this)">' . $fr[0] . '</td>
                    </tr class="sprow">
                    <tr class="sprow">
                        <td class="std">2.</td><td id="mo2" onclick="sel(this)">' . $mo[1] . '</td><td id="di2" onclick="sel(this)">' . $di[1] . '</td><td id="mi2" onclick="sel(this)">' . $mi[1] . '</td><td id="do2" onclick="sel(this)">' . $do[1] . '</td><td id="fr2" onclick="sel(this)">' . $fr[1] . '</td>
                    </tr>
                    <tr class="sprow">
                        <td colspan="6" class="gp">1. große Pause</td>
                    </tr>
                    <tr class="sprow">
                        <td class="std">3.</td><td id="mo3" onclick="sel(this)">' . $mo[2] . '</td><td id="di3" onclick="sel(this)">' . $di[2] . '</td><td id="mi3" onclick="sel(this)">' . $mi[2] . '</td><td id="do3" onclick="sel(this)">' . $do[2] . '</td><td id="fr3" onclick="sel(this)">' . $fr[2] . '</td>
                    </tr>
                    <tr class="sprow">
                        <td class="std">4.</td><td id="mo4" onclick="sel(this)">' . $mo[3] . '</td><td id="di4" onclick="sel(this)">' . $di[3] . '</td><td id="mi4" onclick="sel(this)">' . $mi[3] . '</td><td id="do4" onclick="sel(this)">' . $do[3] . '</td><td id="fr4" onclick="sel(this)">' . $fr[3] . '</td>
                    </tr>
                    <tr class="sprow">
                        <td colspan="6" class="gp">2. große Pause</td>
                    </tr>
                    <tr class="sprow">
                        <td class="std">5.</td><td id="mo5" onclick="sel(this)">' . $mo[4] . '</td><td id="di5" onclick="sel(this)">' . $di[4] . '</td><td id="mi5" onclick="sel(this)">' . $mi[4] . '</td><td id="do5" onclick="sel(this)">' . $do[4] . '</td><td id="fr5" onclick="sel(this)">' . $fr[4] . '</td>
                    </tr>
                    <tr class="sprow">
                        <td colspan="6" class="gp">Mittagspause</td>
                    </tr>
                    <tr class="sprow">
                        <td class="std">6.</td><td id="mo6" onclick="sel(this)">' . $mo[5] . '</td><td id="di6" onclick="sel(this)">' . $di[5] . '</td><td id="mi6" onclick="sel(this)">' . $mi[5] . '</td><td id="do6" onclick="sel(this)">' . $do[5] . '</td><td id="fr6" onclick="sel(this)">' . $fr[5] . '</td>
                    </tr>
                    <tr class="sprow">
                        <td class="std">7.</td><td id="mo7" onclick="sel(this)">' . $mo[6] . '</td><td id="di7" onclick="sel(this)">' . $di[6] . '</td><td id="mi7" onclick="sel(this)">' . $mi[6] . '</td><td id="do7" onclick="sel(this)">' . $do[6] . '</td><td id="fr7" onclick="sel(this)">' . $fr[6] . '</td>
                    </tr>
                    <tr class="sprow">
                        <td class="std">8.</td><td id="mo8" onclick="sel(this)">' . $mo[7] . '</td><td id="di8" onclick="sel(this)">' . $di[7] . '</td><td id="mi8" onclick="sel(this)">' . $mi[7] . '</td><td id="do8" onclick="sel(this)">' . $do[7] . '</td><td id="fr8" onclick="sel(this)">' . $fr[7] . '</td>
                    </tr>
                </table>
                <p id="akt">Zum Bearbeiten die jeweiligen Stunden auswählen</p>
                <form method="post" id="bearbopt"><input type="text" name="sellist" id="sellist" style="display: none;" value=""><input class="bearboption gray" id="cellbearb" type="submit" name="cellbearb" value="" disabled><input class="bearboption" id="cellstufe" type="submit" name="cellstufe" value=""><input class="bearboption gray" id="cellfrei" type="submit" name="cellfrei" value="" disabled></form>
                <p id="kurs" style="display: none;">' . $stunden[40] . '</p>
                
                </div>';
                    echo '<div id="safeo"></div>
                    <div class="navbar"><form class="barform" method="post"><input type="submit" name="sh" value="Hochladen" id="bearb"><p id="titlenav">Bearbeiten</p><input name="lo" type="submit" value="" id="leave"></form></div>
                    <div class="tabbar"><form class="barform" method="post"><input type="submit" name="sb" value="" class="curtab" id="StpbT"><label class="curtab" for="StpbT">Bearbeiten</label><input type="submit" value="" id="HomeT"><label for="HomeT">Stundenplan</label><input name="ao" type="submit" value="" id="EinstT"><label for="EinstT">Einstellungen</label></form></div>';
            }
            $filetext = "Datei auswählen";
            if($showUpload && $showedit) {
                echo '  <div class="win">
                        <form enctype="multipart/form-data" method="post" name="spupload" id="spu"><p id="utitle">Stundenplan hochladen</p>
                            <input type="file" accept=".htm,.html" name="file" id="file" class="clickable" onchange="filefunc()"><label for="file" id="filel">' . $filetext . '</label>
                            <hr class="sep2">
                            <select id="Hj" name="Hj" class="clickable">
                                <option value="Hj1">1. Halbjahr</option>
                                <option value="Hj2">2. Halbjahr</option>
                            </select>
                            <hr class="sep2">
                            <input type="text" id="stufe" name="stufe" placeholder="Klasse/Stufe">
                            <input type="submit" name="uf" id="uf" value="Hochladen" class="clickable">
                        </form><form id="spue" method="post"><input name="exitu" type="submit" id="exit" value="Abbrechen"></form></div><link rel="stylesheet" href="styleschlicht.css">';
            }
            if($showMt) {
                $mtcont = file_get_contents("mt/" . $_SESSION['user'] . ".txt");
                echo '<div class="win">
                        <div id="econtent" class="mt"><p id="settitle" class="mttitle">Mitteilungen</p><p id="edescr" class="mtcont">' . $mtcont . '</p>
                        <form id="ml" name="kl" method="post">
                                <input id="mlk" name="aml" type="submit" value="Alle löschen"><input name="exit" type="submit" id="exitmt" value="OK">
                        </form></div></div>';
            }
            if(isset($_POST['cellbearb']) && !empty($_POST['sellist'])) {
                $showUpload = false;
                $showOptions = false;
                $showForm = false;
                $showedit = true;
                $auth = false;
                $bn = $_SESSION["user"];
                $selection = explode(",",$_POST['sellist']);
                $selection = str_replace("mo","0",$selection);
                $selection = str_replace("di","1",$selection);
                $selection = str_replace("mi","2",$selection);
                $selection = str_replace("do","3",$selection);
                $selection = str_replace("fr","4",$selection);
                if(count($selection) == 1) {
                    $stelle = substr($selection[0],1,1);
                    $stelle -= 1;
                    $stelle *= 5;
                    $stelle += substr($selection[0],0,1);
                    $sptxt = file_get_contents("sp/" . $_SESSION["user"] . ".txt", false);
                    $sptxtar = explode("_", $sptxt);
                    $stunde = explode("<BR>",$sptxtar[$stelle]);
                    echo '<div class="win">
                        <div id="econtent" class="mt bearb"><p id="settitle" class="mttitle bearb">Bearbeiten</p>
                        <form method="post" id="edescr" class="mtcont bearb">
                            <input type="text" name="sellist" id="sellist" style="display: none;" value="' . $_POST["sellist"] .'">
                            <input type="text" name="fachname" class="bearbinput" placeholder="Fachkürzel" value="' . $stunde[0] . '">';
                            if($sptxtar[40] == "EF" || $sptxtar[40] == "Q1" || $sptxtar[40] == "Q2" || $sptxtar[40] == "STUFE" || count($stunde) == 4) {
                                echo '<input type="text" name="fachkurs" class="bearbinput" placeholder="Kursbezeichnung" value="' . $stunde[1] . '">
                                <input type="text" name="fachlehrer" class="bearbinput" placeholder="Lehrerkürzel" value="' . $stunde[2] . '">
                                <input type="text" name="fachraum" class="bearbinput" placeholder="Raum" value="' . $stunde[3] . '">';
                            } else {
                                echo '<input type="text" name="fachlehrer" class="bearbinput" placeholder="Lehrer" value="' . $stunde[1] . '">
                                <input type="text" name="fachraum" class="bearbinput" placeholder="Raum" value="' . $stunde[2] . '">';
                            }
                        echo '
                        <div id="ml" name="kl" class="bearb" method="post">
                                <input class="bearb" id="mlk" name="exitu" type="submit" value="Abbrechen"><input name="savechanges" type="submit" id="exitmt" value="Speichern">
                        </div></form></div></div>';
                } else {
                    $isEqual = [true,true,true,true];
                    $sptxt = file_get_contents("sp/" . $_SESSION["user"] . ".txt", false);
                    $sptxtar = explode("_", $sptxt);
                    $stelle = substr($selection[0],1,1);
                    $stelle -= 1;
                    $stelle *= 5;
                    $stelle += substr($selection[0],0,1);
                    $refStunde = $sptxtar[$stelle];
                    for($i = 1; $i < count($selection); $i++) {
                        $stelle = substr($selection[$i],1,1);
                        $stelle -= 1;
                        $stelle *= 5;
                        $stelle += substr($selection[$i],0,1);
                        if(explode("<BR>",$sptxtar[$stelle])[0] != explode("<BR>",$refStunde)[0]) {
                            $isEqual[0] = false;
                        }
                        if(count(explode("<BR>",$refStunde)) == 4) {
                            if(explode("<BR>",$sptxtar[$stelle])[1] != explode("<BR>",$refStunde)[1]) {
                                $isEqual[1] = false;
                            }
                            if(explode("<BR>",$sptxtar[$stelle])[2] != explode("<BR>",$refStunde)[2]) {
                                $isEqual[2] = false;
                            }
                            if(explode("<BR>",$sptxtar[$stelle])[3] != explode("<BR>",$refStunde)[3]) {
                                $isEqual[3] = false;
                            }
                        } else {
                            if(explode("<BR>",$sptxtar[$stelle])[1] != explode("<BR>",$refStunde)[1]) {
                                $isEqual[1] = false;
                            }
                            if(explode("<BR>",$sptxtar[$stelle])[2] != explode("<BR>",$refStunde)[2]) {
                                $isEqual[2] = false;
                            }
                        }
                    }
                    $stunde = explode("<BR>",$refStunde);
                    echo '<div class="win">
                    <div id="econtent" class="mt bearb"><p id="settitle" class="mttitle bearb">Bearbeiten</p>
                    <form method="post" id="edescr" class="mtcont bearb">
                        <input type="text" name="sellist" id="sellist" style="display: none;" value="' . $_POST["sellist"] .'">
                        <input type="text" name="fachname" class="bearbinput" placeholder="Fachkürzel" value="'; 
                        if($isEqual[0]) echo $stunde[0];
                        echo '">';
                        if($sptxtar[40] == "EF" || $sptxtar[40] == "Q1" || $sptxtar[40] == "Q2" || $sptxtar[40] == "STUFE" || count($stunde) == 4) {
                            echo '<input type="text" name="fachkurs" class="bearbinput" placeholder="Kursbezeichnung" value="'; 
                            if($isEqual[1]) echo $stunde[1];
                            echo '">
                            <input type="text" name="fachlehrer" class="bearbinput" placeholder="Lehererkürzel" value="'; 
                            if($isEqual[2]) echo $stunde[2];
                            echo '">
                            <input type="text" name="fachraum" class="bearbinput" placeholder="Raum" value="'; 
                            if($isEqual[3]) echo $stunde[3];
                            echo '">';
                        } else {
                            echo '<input type="text" name="fachlehrer" class="bearbinput" placeholder="Lehererkürzel" value="'; 
                            if($isEqual[1]) echo $stunde[1];
                            echo '">
                            <input type="text" name="fachraum" class="bearbinput" placeholder="Raum" value="';
                            if($isEqual[2]) echo $stunde[2];
                            echo '">';
                        }
                    echo '
                    <div id="ml" name="kl" class="bearb" method="post">
                            <input class="bearb" id="mlk" name="exitu" type="submit" value="Abbrechen"><input name="savechanges" type="submit" id="exitmt" value="Speichern">
                    </div></form></div></div>';
                }
            }
            if(isset($_POST['cellstufe'])) {
                $showUpload = false;
                $showOptions = false;
                $showForm = false;
                $showedit = true;
                $auth = false;
                $bn = $_SESSION["user"];
                $sptxt = file_get_contents("sp/" . $_SESSION["user"] . ".txt", false);
                $sptxtar = explode("_", $sptxt);
                echo '<div class="win">
                <div id="econtent" class="mt bearb"><p id="settitle" class="mttitle bearb">Klasse oder Stufe setzen</p>
                <form method="post" id="edescr" class="mtcont bearb">
                    <input type="text" name="setstufe" class="bearbinput" id="setstufe" placeholder="Stufe" value="' . $sptxtar[40] . '">
                    <div id="ml" name="kl" class="bearb" method="post">
                            <input class="bearb" id="mlk" name="exitu" type="submit" value="Abbrechen"><input name="savestufe" type="submit" id="exitmt" value="Speichern">
                </div></form></div></div>';
            }
            echo $style;
        ?>
        <!-- Altes "Scroll-to-Reload"-Bild img src="images/reload.svg" id="reloadimg" !-->
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
            var raumReg = /(->)?((A|B|C|AK|CK)[1-9][0-9]{0,2})|(SH|TH|GY)/;
            //Funktion um Fächerklassen für Farben und Vertretungsklassen für Badges zuzuweisen
            function fachklassen() {
                var tage = ["mo", "di", "mi", "do", "fr"];
                if(document.getElementById("stundenplan") != null) {
                    var stds = [];
                    for(i = 1; i <= 8; i++) {
                        for(j = 0; j < 5; j++) {
                            stelle = j + (i - 1) * 5;
                            stds[stelle] = tage[j] + i;
                        }
                    }
                    for(i = 0; i < 40; i++) {
                        fach = document.getElementById(stds[i]).innerHTML.split("<br>")[0].replace(/(\r\n|\n|\r)/gm, "");
                        if(fach != "-" && fach != "Regen-") {
                            document.getElementById(stds[i]).classList.add(fach);
                        } else if(fach == "Regen-") {
                            document.getElementById(stds[i]).classList.add("Regen");
                        } else {
                            document.getElementById(stds[i]).classList.add("none");
                        }
                        if((document.getElementById("kurs").innerHTML == "EF" || document.getElementById("kurs").innerHTML == "Q1" || document.getElementById("kurs").innerHTML == "Q2") && document.getElementById(stds[i]).innerHTML.split("<br>")[1] != null) {
                            kurs = document.getElementById(stds[i]).innerHTML.split("<br>")[1].replace(/(\r\n|\n|\r)/gm, "");
                            document.getElementById(stds[i]).classList.add(fach + "-" + kurs);
                        }
                    }
                    if(document.getElementById("vp1") != null && document.getElementById("vp2") != null) {
                        var tvp1 = document.getElementById("vp1").innerHTML.replace('"',"").split("_");
                        var tvp2 = document.getElementById("vp2").innerHTML.replace('"',"").split("_");
                        var vp1 = [];
                        var vp2 = [];
                        var n = 0;
                        for(i = 0; i < tvp1.length / 5; i++) {
                            temp = tvp1[i * 5 + n] + "." + tvp1[i * 5 + 1 + n] + "." + tvp1[i * 5 + 2 + n] + "." + tvp1[i * 5 + 3 + n] + "." + tvp1[i * 5 + 4 + n];
                            if(tvp1[i * 5 + 5 + n] != null && !tvp1[i * 5 + 5 + n].includes("05") && !tvp1[i * 5 + 5 + n].includes("06") && !tvp1[i * 5 + 5 + n].includes("07") && !tvp1[i * 5 + 5 + n].includes("08") && !tvp1[i * 5 + 5 + n].includes("09") && tvp1[i * 5 + 5 + n] != "EF" && tvp1[i * 5 + 5 + n] != "Q1" && tvp1[i * 5 + 5 + n] != "Q2") {
                                temp+= "." + tvp1[i * 5 + 5 + n];
                                n++;
                            }
                            vp1[i] = temp;
                        }
                        n = 0;
                        for(i = 0; i < tvp2.length / 5; i++) {
                            temp = tvp2[i * 5 + n] + "." + tvp2[i * 5 + 1 + n] + "." + tvp2[i * 5 + 2 + n] + "." + tvp2[i * 5 + 3 + n] + "." + tvp2[i * 5 + 4 + n];
                            if(tvp2[i * 5 + 5 + n] != null && !tvp2[i * 5 + 5 + n].includes("05") && !tvp2[i * 5 + 5 + n].includes("06") && !tvp2[i * 5 + 5 + n].includes("07") && !tvp2[i * 5 + 5 + n].includes("08") && !tvp2[i * 5 + 5 + n].includes("09") && tvp2[i * 5 + 5 + n] != "EF" && tvp2[i * 5 + 5 + n] != "Q1" && tvp2[i * 5 + 5 + n] != "Q2") {
                                temp+= "." + tvp2[i * 5 + 5 + n];
                                n++;
                            }
                            vp2[i] = temp;
                        }
                        for(i = 0; i < vp1.length; i++) {
                            if(vp1[i].split(".")[0].includes(document.getElementById("kurs").innerHTML)) {
                                if(vp1[i].split(".")[1].includes("-")) {
                                    ug = vp1[i].split(".")[1].split("---")[0];
                                    og = vp1[i].split(".")[1].split("---")[1];
                                } else {
                                    ug = vp1[i].split(".")[1];
                                    og = vp1[i].split(".")[1]
                                }
                                for(j = ug; j <= og; j++) {
                                    var nl = 0;
                                    if(document.getElementById(getDay(0) + j) != null && document.getElementById(getDay(0) + j).classList.contains(vp1[i].split(".")[2]) || vp1[i].split(".")[2] == "---" && vp1[i].split(".")[3] == "---") {
                                        document.getElementById(getDay(0) + j).classList.add(vp1[i].split(".")[4]);
                                        if(vp1[i].split(".")[3].includes("-")) {
                                            document.getElementById(getDay(0) + j).innerHTML = document.getElementById(getDay(0) + j).innerHTML.replace(raumReg,"->" + vp1[i].split(".")[3].split("-")[1]);
                                        } else if(!document.getElementById(getDay(0) + j).innerHTML.includes(vp1[i].split(".")[3])) {
                                            document.getElementById(getDay(0) + j).innerHTML = document.getElementById(getDay(0) + j).innerHTML.replace(raumReg,"->" + vp1[i].split(".")[3]);
                                        }
                                        if(vp1[i].split(".")[5] != null) {
                                            celltext = document.getElementById(getDay(0) + j).innerHTML;
                                            celltextar = celltext.split("<br>");
                                            celltextar[4] = "<span style='overflow: auto; display: block; font-size: auto; white-space: nowrap;'>" + vp1[i].split(".")[5] + "</span>";
                                            celltext = celltextar.join("<BR>");
                                            document.getElementById(getDay(0) + j).innerHTML = celltext;
                                            nl = 1;
                                        }
                                        if(vp1[i].split(".")[4] != "Entfall" && vp1[i].split(".")[4] != "EVA" && vp1[i].split(".")[4] != "Raumwechsel" && vp1[i].split(".")[4] != "Freisetzung" && vp1[i].split(".")[4] != "Vertretung") {
                                            var lns = document.getElementById(getDay(0) + j).innerHTML.split("<br>");
                                            lns[4 + nl] = vp1[i].split(".")[4];
                                            document.getElementById(getDay(0) + j).innerHTML = lns.join("<br>");
                                            document.getElementById(getDay(0) + j).innerHTML = document.getElementById(getDay(0) + j).innerHTML.replace("</span><br>", "</span>");
                                        }
                                    }
                                }
                            }
                        }
                        for(i = 0; i < vp2.length; i++) {
                            if(vp2[i].split(".")[0].includes(document.getElementById("kurs").innerHTML)) {
                                if(vp2[i].split(".")[1].includes("-")) {
                                    ug = vp2[i].split(".")[1].split("---")[0];
                                    og = vp2[i].split(".")[1].split("---")[1];
                                } else {
                                    ug = vp2[i].split(".")[1];
                                    og = vp2[i].split(".")[1]
                                }
                                for(j = ug; j <= og; j++) {
                                    var nl = 0; 
                                    if(document.getElementById(getDay(1) + j) != null && document.getElementById(getDay(1) + j).classList.contains(vp2[i].split(".")[2]) || vp2[i].split(".")[2] == "---" && vp2[i].split(".")[3] == "---") {
                                        document.getElementById(getDay(1) + j).classList.add(vp2[i].split(".")[4]);
                                        if(vp2[i].split(".")[3].includes("-")) {
                                            document.getElementById(getDay(1) + j).innerHTML = document.getElementById(getDay(1) + j).innerHTML.replace(raumReg,"->" + vp2[i].split(".")[3].split("-")[1]);
                                        } else if(!document.getElementById(getDay(1) + j).innerText.includes(vp2[i].split(".")[3])) {
                                            document.getElementById(getDay(1) + j).innerHTML = document.getElementById(getDay(1) + j).innerHTML.replace(raumReg,"->" + vp2[i].split(".")[3]);
                                        }
                                        if(vp2[i].split(".")[5] != null) {
                                            celltext = document.getElementById(getDay(1) + j).innerHTML;
                                            celltextar = celltext.split("<br>");
                                            celltextar[4] = "<span style='overflow: auto; display: block; font-size: auto; white-space: nowrap;'>" + vp2[i].split(".")[5] + "</span>";
                                            celltext = celltextar.join("<BR>");
                                            document.getElementById(getDay(1) + j).innerHTML = celltext;
                                            nl = 1;
                                        }
                                        if(vp2[i].split(".")[4] != "Entfall" && vp2[i].split(".")[4] != "EVA" && vp2[i].split(".")[4] != "Raumwechsel" && vp2[i].split(".")[4] != "Freisetzung" && vp2[i].split(".")[4] != "Vertretung") {
                                            var lns = document.getElementById(getDay(1) + j).innerHTML.split("<br>");
                                            lns[4 + nl] = vp2[i].split(".")[4];
                                            document.getElementById(getDay(1) + j).innerHTML = lns.join("<br>");
                                            document.getElementById(getDay(1) + j).innerHTML = document.getElementById(getDay(1) + j).innerHTML.replace("</span><br>", "</span>");
                                        }
                                    }
                                }
                            } 
                        }
                    }
                }
            }
        </script>
        <script>
            //Ladeanimation stoppen
            var loadInt;
            function stopload() {
                document.getElementById("loadani").outerHTML = "";
                rb();
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
                rb();
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

            //Ehemalige "Scroll-to-Reload"-Funktion weil idk vllt iwann mal wieder
            /*var lss;
            var sreload = false;
            window.addEventListener("scroll", function(){
                if(window.scrollY < -350) {
                    sreload = true;
                }
            });
            window.addEventListener("scroll", function(){
                if(window.scrollY <= -350) {
                    document.getElementById("reloadimg").style.opacity = 1;
                    window.navigator.vibrate(200);
                } else if(window.scrollY < -200) {
                    document.getElementById("reloadimg").style.display = "block";
                    document.getElementById("reloadimg").style.opacity = (window.scrollY * (-1) - 200) / 150;
                    if(document.getElementById("reloadimg").style.opacity > 0.75) {
                        document.getElementById("reloadimg").style.opacity = 0.75;
                    }
                } else if(window.scrollY > -10){
                    document.getElementById("reloadimg").style.opacity = 0;
                    document.getElementById("reloadimg").style.display = "none";
                } else {
                    document.getElementById("reloadimg").style.opacity = 0;
                }
            });
            window.addEventListener("scroll", function(){
                if(window.scrollY > -200 && sreload) {
                    document.location.reload();
                    sreload = false;
                }
                lss = window.scrollY;
            });*/
            //Tag(Heute) + Tage (für Vertretungsplanabruf)
            function getDay(n) {
                var today = new Date();
                var day = today.getDay() + n;
                if(day == 6 || day == 0 || day == 1) {
                    return "mo";
                } else if(day == 2) {
                    return "di";
                } else if(day == 3) {
                    return "mi";
                } else if(day == 4) {
                    return "do";
                } else if(day == 5) {
                    return "fr";
                }
            }

            //Ehemalige Menüaufruf-Funktionen
            /*function profile() {
                document.getElementById("po").style.display = "block";
            }
            function pohide() {
                document.getElementById("po").style.display = "none";
            }
            function sp() {
                document.getElementById("spo").style.display = "block";
            }
            function spohide() {
                document.getElementById("spo").style.display = "none";
            }*/
        </script>
        <script>
            var a=document.getElementsByTagName("a");
            for(var i=0;i<a.length;i++)
            {
                a[i].onclick=function()
                {
                    window.location=this.getAttribute("href");
                    return false
                }
            }
            var rl;
            var rlbar = document.getElementById("rlbar");
            var wi = 0;
            function rb() {
                if(rlbar != null) {
                    Cookies.remove("reload");
                    rl = setInterval(rbprog,2);
                }
            }
            function rbprog() {
                wi += 1.5;
                rlbar.style.width = wi + "vw";
                if(wi >= 85 && wi < 100) {
                    clearInterval(rl);
                    rl = setInterval(rbprog,1);
                } else if(wi > 100) {
                    clearInterval(rl);
                    rl = setInterval(rbop,3);
                }
            }
            function rbop() {
                if(rlbar.style.opacity > 0) {
                    rlbar.style.opacity -= 0.01;
                } else if(rlbar.style.opacity <= 0) {
                    clearInterval(rl);
                    rlbar.outerHTML = "";
                }
            }

            function checkcheckbox() {
                let cb = document.getElementById("ab_check");
                let cbb = document.getElementById("ab_box");
                if(cb.checked) {
                    cbb.classList.add("abc");
                } else {
                    cbb.classList.remove("abc");
                }
            }
        </script>
        <script>
            var raumReg = /(->)?((A|B|C|AK|CK)[1-9][0-9]{0,2})|(SH|TH|GY)/;
            function getTag(n) {
                n--;
                switch(n) {
                    case 5:
                    case 6:
                    case 7:
                    case 0:
                        return "Montag";
                        break;
                    case 1:
                        return "Dienstag";
                        break;
                    case 2:
                        return "Mittwoch";
                        break;
                    case 3: 
                        return "Donnerstag";
                        break;
                    case 4:
                        return "Freitag";
                        break;
                }
            }
            function refreshData() {
                if(document.getElementById("stundenplan") != null) {
                    var vp1 = "";
                    var vp2 = "";
                    var date = new Date();
                    var day = date.getDay();
                    fetch('https://wasserturmplan.steffenmolitor.de/plan/' + getTag(day) + '.vp')
                        .then(response => response.text())
                        .then((data) => {
                            vp1 = data;
                            fetch('https://wasserturmplan.steffenmolitor.de/plan/' + getTag(day + 1) + '.vp')
                                .then(response => response.text())
                                .then((data) => {
                                    vp2 = data;
                                    document.getElementById("vp1").innerHTML = vp1.replace("style", "ignore");
                                    document.getElementById("vp2").innerHTML = vp2.replace("style", "ignore");
                                    vps();
                                })
                        })
                    var modTime = 0;
                    var modDate = ""
                    fetch('https://wasserturmplan.steffenmolitor.de/plan/' + getTag(day) + '.vp')
                        .then(r => {
                            modTime = Math.floor((new Date().getTime() - new Date(r.headers.get('Last-Modified')).getTime()) / 1000);
                            modDate = r.headers.get('Last-Modified');
                            var timeStr = "";
                            if(modTime < 15) {
                                timeStr = 'Gerade';
                            } else if(modTime < 60) {
                                timeStr = 'Vor ' + modTime + ' Sekunden';
                            } else if(parseInt(modTime / 60) == 1) {
                                timeStr = 'Vor einer Minute';
                            } else if(parseInt(modTime / 60) < 15) {
                                timeStr = 'Vor ' + parseInt(modTime / 60) + ' Minuten';
                            } else {
                                const options = { day: '2-digit', month: '2-digit', year: 'numeric', hour: 'numeric', minute: '2-digit', second: '2-digit' }
                                timeStr = new Date(modDate).toLocaleDateString('de-DE',options) + ' Uhr';
                                // 00.00.0000 0-23:00:00
                            }
                            document.getElementById("AKTDATE").innerHTML = timeStr;
                        })
                    fetch('https://wasserturmplan.steffenmolitor.de/plan/STAND.vp')
                        .then(response => response.text())
                        .then((data) => {
                            document.getElementById("STANDDATE").innerHTML = data;
                        })
                }
            }
            function vps() {
                document.getElementById("Stundenplan").innerHTML = document.getElementById("Stundenplan").innerHTML.replace("->","");
                var tage = ["mo", "di", "mi", "do", "fr"];
                
                var stds = [];
                for(i = 1; i <= 8; i++) {
                    for(j = 0; j < 5; j++) {
                        var el = document.getElementById(tage[j] + i);
                        var lines = el.innerHTML.split("<BR>");
                        el.classList.remove("Entfall");
                        el.classList.remove("EVA");
                        el.classList.remove("Raumwechsel");
                        el.classList.remove("Freisetzung");
                        el.classList.remove("Vertretung");
                    }
                }
                
                var tvp1 = document.getElementById("vp1").innerHTML.replace('"',"").split("_");
                var tvp2 = document.getElementById("vp2").innerHTML.replace('"',"").split("_");
                var vp1 = [];
                var vp2 = [];
                var n = 0;
                for(i = 0; i < tvp1.length / 5; i++) {
                    temp = tvp1[i * 5 + n] + "." + tvp1[i * 5 + 1 + n] + "." + tvp1[i * 5 + 2 + n] + "." + tvp1[i * 5 + 3 + n] + "." + tvp1[i * 5 + 4 + n];
                    if(tvp1[i * 5 + 5 + n] != null && !tvp1[i * 5 + 5 + n].includes("05") && !tvp1[i * 5 + 5 + n].includes("06") && !tvp1[i * 5 + 5 + n].includes("07") && !tvp1[i * 5 + 5 + n].includes("08") && !tvp1[i * 5 + 5 + n].includes("09") && tvp1[i * 5 + 5 + n] != "EF" && tvp1[i * 5 + 5 + n] != "Q1" && tvp1[i * 5 + 5 + n] != "Q2") {
                        temp+= "." + tvp1[i * 5 + 5 + n];
                        n++;
                    }
                    vp1[i] = temp;
                }
                n = 0;
                for(i = 0; i < tvp2.length / 5; i++) {
                    temp = tvp2[i * 5 + n] + "." + tvp2[i * 5 + 1 + n] + "." + tvp2[i * 5 + 2 + n] + "." + tvp2[i * 5 + 3 + n] + "." + tvp2[i * 5 + 4 + n];
                    if(tvp2[i * 5 + 5 + n] != null && !tvp2[i * 5 + 5 + n].includes("05") && !tvp2[i * 5 + 5 + n].includes("06") && !tvp2[i * 5 + 5 + n].includes("07") && !tvp2[i * 5 + 5 + n].includes("08") && !tvp2[i * 5 + 5 + n].includes("09") && tvp2[i * 5 + 5 + n] != "EF" && tvp2[i * 5 + 5 + n] != "Q1" && tvp2[i * 5 + 5 + n] != "Q2") {
                        temp+= "." + tvp2[i * 5 + 5 + n];
                        n++;
                    }
                    vp2[i] = temp;
                }
                for(i = 0; i < vp1.length; i++) {
                    if(vp1[i].split(".")[0].includes(document.getElementById("kurs").innerHTML)) {
                        if(vp1[i].split(".")[1].includes("-")) {
                            ug = vp1[i].split(".")[1].split("---")[0];
                            og = vp1[i].split(".")[1].split("---")[1];
                        } else {
                            ug = vp1[i].split(".")[1];
                            og = vp1[i].split(".")[1]
                        }
                        for(j = ug; j <= og; j++) {
                            var nl = 0;
                            if(document.getElementById(getDay(0) + j) != null && document.getElementById(getDay(0) + j).classList.contains(vp1[i].split(".")[2]) || vp1[i].split(".")[2] == "---" && vp1[i].split(".")[3] == "---") {
                                document.getElementById(getDay(0) + j).classList.add(vp1[i].split(".")[4]);
                                if(vp1[i].split(".")[3].includes("-")) {
                                    document.getElementById(getDay(0) + j).innerHTML = document.getElementById(getDay(0) + j).innerHTML.replace(raumReg,"->" + vp1[i].split(".")[3].split("-")[1]);
                                } else if(!document.getElementById(getDay(0) + j).innerHTML.includes(vp1[i].split(".")[3])) {
                                    document.getElementById(getDay(0) + j).innerHTML = document.getElementById(getDay(0) + j).innerHTML.replace(raumReg,"->" + vp1[i].split(".")[3]);
                                }
                                if(vp1[i].split(".")[5] != null) {
                                    celltext = document.getElementById(getDay(0) + j).innerHTML;
                                    celltextar = celltext.split("<br>");
                                    celltextar[4] = "<span style='overflow: auto; display: block; font-size: auto; white-space: nowrap;'>" + vp1[i].split(".")[5] + "</span>";
                                    celltext = celltextar.join("<BR>");
                                    document.getElementById(getDay(0) + j).innerHTML = celltext;
                                    nl = 1;
                                }
                                if(vp1[i].split(".")[4] != "Entfall" && vp1[i].split(".")[4] != "EVA" && vp1[i].split(".")[4] != "Raumwechsel" && vp1[i].split(".")[4] != "Freisetzung" && vp1[i].split(".")[4] != "Vertretung") {
                                    var lns = document.getElementById(getDay(0) + j).innerHTML.split("<br>");
                                    lns[4 + nl] = vp1[i].split(".")[4];
                                    document.getElementById(getDay(0) + j).innerHTML = lns.join("<br>");
                                    document.getElementById(getDay(0) + j).innerHTML = document.getElementById(getDay(0) + j).innerHTML.replace("</span><br>", "</span>");
                                }
                            }
                        }
                    }
                }
                for(i = 0; i < vp2.length; i++) {
                    if(vp2[i].split(".")[0].includes(document.getElementById("kurs").innerHTML)) {
                        if(vp2[i].split(".")[1].includes("-")) {
                            ug = vp2[i].split(".")[1].split("---")[0];
                            og = vp2[i].split(".")[1].split("---")[1];
                        } else {
                            ug = vp2[i].split(".")[1];
                            og = vp2[i].split(".")[1]
                        }
                        for(j = ug; j <= og; j++) {
                            var nl = 0; 
                            if(document.getElementById(getDay(1) + j) != null && document.getElementById(getDay(1) + j).classList.contains(vp2[i].split(".")[2]) || vp2[i].split(".")[2] == "---" && vp2[i].split(".")[3] == "---") {
                                document.getElementById(getDay(1) + j).classList.add(vp2[i].split(".")[4]);
                                if(vp2[i].split(".")[3].includes("-")) {
                                    document.getElementById(getDay(1) + j).innerHTML = document.getElementById(getDay(1) + j).innerHTML.replace(raumReg,"->" + vp2[i].split(".")[3].split("-")[1]);
                                } else if(!document.getElementById(getDay(1) + j).innerText.includes(vp2[i].split(".")[3])) {
                                    document.getElementById(getDay(1) + j).innerHTML = document.getElementById(getDay(1) + j).innerHTML.replace(raumReg,"->" + vp2[i].split(".")[3]);
                                }
                                if(vp2[i].split(".")[5] != null) {
                                    celltext = document.getElementById(getDay(1) + j).innerHTML;
                                    celltextar = celltext.split("<br>");
                                    celltextar[4] = "<span style='overflow: auto; display: block; font-size: auto; white-space: nowrap;'>" + vp2[i].split(".")[5] + "</span>";
                                    celltext = celltextar.join("<BR>");
                                    document.getElementById(getDay(1) + j).innerHTML = celltext;
                                    nl = 1;
                                }
                                if(vp2[i].split(".")[4] != "Entfall" && vp2[i].split(".")[4] != "EVA" && vp2[i].split(".")[4] != "Raumwechsel" && vp2[i].split(".")[4] != "Freisetzung" && vp2[i].split(".")[4] != "Vertretung") {
                                    var lns = document.getElementById(getDay(1) + j).innerHTML.split("<br>");
                                    lns[4 + nl] = vp2[i].split(".")[4];
                                    document.getElementById(getDay(1) + j).innerHTML = lns.join("<br>");
                                    document.getElementById(getDay(1) + j).innerHTML = document.getElementById(getDay(1) + j).innerHTML.replace("</span><br>", "</span>");
                                }
                            }
                        }
                    } 
                }
            }
        </script>
        <script>
            var refInt = setInterval(refreshData, 500);
        </script>
    </body>
    
</html>