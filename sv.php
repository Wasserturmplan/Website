<?php
            if(!session_start()) {
                echo "<p class='output errorout' onload='destroy(this)'><img class='errimg' src='images/error.svg'>Session konnte nicht gestartet werden</p>";
                die();
            } else {
                header("Cache-control: private");
                file_put_contents("aufruflistesv.txt", file_get_contents("aufruflistesv.txt") . date("Y.m.d.H.i.s") . "\n");
            }
            $version = "a_1";
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
        <?php
            echo '<script>console.log("' . $version . '")</script>';
        ?>
    </head>
    <?php
        if((!isset($_SESSION['user']) || $_SESSION['user'] == "" || empty($_SESSION['user'])) && !isset($_POST['login']) && !isset($_POST['reg'])) {
            $auth = false;
            $showForm = true;
            echo '<body id="body" onload="/*fachklassen();*/ stoploadslow();">';
        } else {
            echo '<body id="body" onload="/*fachklassen();*/ stopload();">';
        }
    ?>
    <div id="loadani" style="display: flex; justify-content: center; align-items: center; height: 100vh; width: 100vw; background: transparent; position: fixed; left: 0; top: 0; z-index: 99999;">
        <img id="loadanimation" src="images/load4.gif" style="display: inline-block; height: 300px; width: 300px; z-index: 99999;">
    </div>
    <!-- Vom Server verarbeiteter und ausgegebener Bereich !-->
    <?php
            $style .= '<link rel="stylesheet" href="ff_0.css">';
            $color_scheme = isset($_COOKIE["color_scheme"]) ? $_COOKIE["color_scheme"] : false;
            if ($color_scheme === false) $color_scheme = 'dark';
            if ($color_scheme == 'dark') {
                $style .= '<link rel="stylesheet" href="theme_dark.css">';
            } else {
                $style .= '<link rel="stylesheet" href="theme_light.css">';
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
                return file_get_contents("plan/" . getTag(date("N")) . ".txt");
            }
            function getVp2() {
                return file_get_contents("plan/" . getTag(date("N") + 1) . ".txt");
            }
            echo '
                <p class="titlesch showtitle">Vertretungsplan</p>
                <div id="spd">
                <table id="stundenplan">
                    <tr class="sprow">
                        <th>Klasse(n)</th><th>Stunde(n)</th><th>Fach</th><th>Raum</th><th>Art</th><th>Zusatz</th>
                    </tr>
                    <tr class="sprow">
                        <td>Q1</td><td>1-2</td><td>IF L1</td><td>A201</td><td>EVA</td><td></td>
                    </tr>
                    <tr class="sprow">
                        <td>Q1</td><td>1-2</td><td>IF L1</td><td>A201</td><td>EVA</td><td></td>
                    </tr>
                    <tr class="sprow">
                        <td>Q1</td><td>1-2</td><td>IF L1</td><td>A201</td><td>EVA</td><td></td>
                    </tr>
                    <tr class="sprow">
                        <td>Q1</td><td>1-2</td><td>IF L1</td><td>A201</td><td>EVA</td><td></td>
                    </tr>
                    <tr class="sprow">
                        <td>Q1</td><td>1-2</td><td>IF L1</td><td>A201</td><td>EVA</td><td></td>
                    </tr>
                    <tr class="sprow">
                        <td>Q1</td><td>1-2</td><td>IF L1</td><td>A201</td><td>EVA</td><td></td>
                    </tr>
                    <tr class="sprow">
                        <td>Q1</td><td>1-2</td><td>IF L1</td><td>A201</td><td>EVA</td><td></td>
                    </tr>
                    <tr class="sprow">
                        <td>Q1</td><td>1-2</td><td>IF L1</td><td>A201</td><td>EVA</td><td></td>
                    </tr>
                    <tr class="sprow">
                        <td>Q1</td><td>1-2</td><td>IF L1</td><td>A201</td><td>EVA</td><td></td>
                    </tr>
                    <tr class="sprow">
                        <td>Q1</td><td>1-2</td><td>IF L1</td><td>A201</td><td>EVA</td><td></td>
                    </tr>
                    <tr class="sprow">
                        <td>Q1</td><td>1-2</td><td>IF L1</td><td>A201</td><td>EVA</td><td></td>
                    </tr>
                    <tr class="sprow">
                        <td>Q1</td><td>1-2</td><td>IF L1</td><td>A201</td><td>EVA</td><td></td>
                    </tr>
                    <tr class="sprow">
                        <td>Q1</td><td>1-2</td><td>IF L1</td><td>A201</td><td>EVA</td><td></td>
                    </tr>
                    <tr class="sprow">
                        <td>Q1</td><td>1-2</td><td>IF L1</td><td>A201</td><td>EVA</td><td></td>
                    </tr>
                    <tr class="sprow">
                        <td>Q1</td><td>1-2</td><td>IF L1</td><td>A201</td><td>EVA</td><td></td>
                    </tr>
                    <tr class="sprow">
                        <td>Q1</td><td>1-2</td><td>IF L1</td><td>A201</td><td>EVA</td><td></td>
                    </tr>
                    <tr class="sprow">
                        <td>Q1</td><td>1-2</td><td>IF L1</td><td>A201</td><td>EVA</td><td></td>
                    </tr>
                    <tr class="sprow">
                        <td>Q1</td><td>1-2</td><td>IF L1</td><td>A201</td><td>EVA</td><td></td>
                    </tr>
                    
                </table>
                <p id="akt">Letzte Aktualisierung: <BR>' . date('d.m.Y', filemtime("plan/" . getTag(date("N")) . ".txt")) . ' <BR>' . date('G:i:s', filemtime("plan/" . getTag(date("N")) . ".txt")) . ' Uhr | Stand: ' . file_get_contents("plan/STAND.txt") . '</p><p id="pentf">Entfall</p><p id="peva">EVA</p><p id="praumw">Raumwechsel</p><p id="pfreis">Freisetzung</p><p id="pvertr">Vertretung</p>
                <div class="navbar"><form class="barform" method="post"><input type="submit" onclick="window.location.reload()" value="Neuladen" id="bearb"><p id="titlenav">Vertretungsplan</p></div>
            ';
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
            //Funktion um Fächerklassen für Farben und Vertretungsklassen für Badges zuzuweisen
            function fachklassen() {
                var tage = ["mo", "di", "mi", "do", "fr"];
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
        </script>
    </body>
    
</html>