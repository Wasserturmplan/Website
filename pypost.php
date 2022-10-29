<?php
    session_start();
?>
<!doctype html>


<html>
    
    <head>
        <title>Wasserturmplan - Python-Upload Schnittstelle</title>
        <meta charset="utf-8">
    </head>
    <script>
        function getVps() {
            var heute = document.getElementById("heute").innerText;
			var heuteZeilen = heute.split("\n");
			var heuteTag = heuteZeilen[6].split(" ")[1];
			var heutetxt = heuteTag;
			for(i = 8; i < heuteZeilen.length - 4; i++) {
				heutetxt += "_" + heuteZeilen[i].replaceAll("\n","_").replaceAll("→","-").replaceAll("→","-").replaceAll(" ","-").replaceAll(/\t/g,"_").replaceAll(", ","-");
			}
			heutetxt = heutetxt.replaceAll(/[\u00A0\u1680​\u180e\u2000-\u2009\u200a​\u200b​\u202f\u205f​\u3000]/g,'');
			heutetxt = heutetxt.replaceAll("__","_");
			heutetxt = heutetxt.slice(0, -1);
            document.getElementById("heutet").value = heutetxt;
            document.getElementById("vpstand").value = heuteZeilen[4].split("    ")[1].split(": ")[1];

            var morgen = document.getElementById("morgen").innerText;
			var morgenZeilen = morgen.split("\n");
			var morgenTag = morgenZeilen[6].split(" ")[1];
			var morgentxt = morgenTag;
			for(i = 8; i < morgenZeilen.length - 4; i++) {
				morgentxt += "_" + morgenZeilen[i].replaceAll("\n","_").replaceAll("→","-").replaceAll("→","-").replaceAll(" ","-").replaceAll(/\t/g,"_").replaceAll(", ","-");
			}
			morgentxt = morgentxt.replaceAll(/[\u00A0\u1680​\u180e\u2000-\u2009\u200a​\u200b​\u202f\u205f​\u3000]/g,'');
			morgentxt = morgentxt.replaceAll("__","_");
			morgentxt = morgentxt.slice(0, -1);
            document.getElementById("morgent").value = morgentxt;

            document.getElementById("jssub").submit();
        }
    </script>
    <body style="margin: 0; padding: 0; background-color: black; color: white;">
        <form enctype="multipart/form-data" name="fileuploads" method="post">
            <input name="heute" type="file" style="width: 80vw;">
            <input name="morgen" type="file" style="width: 80vw;">
            <input name="go" type="submit" style="width: 80vw;" value="go">
        </form>
        <form enctype="multipart/form-data" name="jsi" id="jssub" method="post">
            <input name="vpheute" id="heutet" type="text" style="width: 80vw;">
            <input name="vpmorgen" id="morgent" type="text" style="width: 80vw;">
            <input name="vpstand" id="vpstand" type="text" style="width: 10vw;">
        </form>
        <?php
            global $heute;
            global $morgen;
            function uploadToDB($var, $tag) {
                //atal error: Uncaught TypeError: PDOStatement::execute(): Argument #1 ($params) must be of type ?array, string given in /homepages/4/d4295028593/htdocs/stundenplan.steffenmolitor.de/pypost.php:74 Stack trace: #0 /homepages/4/d4295028593/htdocs/stundenplan.steffenmolitor.de/pypost.php(74): PDOStatement->execute('\r\n<tr class='li...') #1 /homepages/4/d4295028593/htdocs/stundenplan.steffenmolitor.de/pypost.php(168): uploadToDB(Array, 'Freitag') #2 {main} thrown in /homepages/4/d4295028593/htdocs/stundenplan.steffenmolitor.de/pypost.php on line 74
                //VERBINDEN
                $db = new PDO("mysql:host=mysql.server.url;dbname=databasename","username","PaSswoRt123");
                if ($db->connect_error) {
                    die("Verbindung fehlgeschlagen");
                }
                //CLEAR
                $tables = ["Montag","Dienstag","Mittwoch","Donnerstag","Freitag"];
                for($i = 0; $i < count($tables); $i++) {
                    $tableN = $tables[$i];
                    $clearA = $db->prepare("TRUNCATE $tableN");
                    $clearA->execute();
                }
                //UPLOAD
                $columns = ["Klassen","Stunden","Fach","Raum","Art","Text"];
                $addRow = $db->prepare("INSERT INTO $tag (`Zeile`, `Klassen`, `Stunden`, `Fach`, `Raum`, `Art`, `Text`) VALUES (?,?,?,?,?,?)");
                for($i = 0; $i < count($var[0]); $i++) {
                    $addRow->execute($var[0][$i][0]);
                }
                //LÖSCHEN
                $db = null;

                //------------------

                /*$getes = $db->prepare("SELECT es FROM `benutzer` WHERE bn = ?");
                $getes->execute([$_SESSION["user"]]);
                $resultes = $getes->fetch()["es"];
                $einst = explode(";", $resultes);
                $einst[1] = $_POST["ffw"];
                $einstellungen = implode(";",$einst);
                $setes = $db->prepare("UPDATE benutzer SET es = ? WHERE bn = ?;");
                $setes->execute([$einstellungen,$_SESSION["user"]]);
                $db = null;*/
            }
            if(isset($_POST['go']) && $_FILES['heute']['name'] == "heute.html" && $_FILES['morgen']['name'] == "morgen.html") {
                $heute = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is','',preg_replace('#<?php(.*?)?>#is','',preg_replace('#<script(.*?)>(.*?)</script>#is','',str_replace('"',"'",file_get_contents($_FILES['heute']['tmp_name'])))));
                $morgen = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is','',preg_replace('#<?php(.*?)?>#is','',preg_replace('#<script(.*?)>(.*?)</script>#is','',str_replace('"',"'",file_get_contents($_FILES['morgen']['tmp_name'])))));
                echo '
                        <div id="heute" style="margin: 0; padding: 0; width: 45vw; float: left; display: inline;">' .
                            $heute . '
                        </div>
                        <div id="morgen" style="margin: 0; padding: 0; width: 45vw; float: right; display: inline;">' .
                            $morgen . '
                        </div>
                        <script>
                            getVps();
                        </script>
                     ';
                preg_match("/(?<=\<table class=\"mon_list\" \>)[\s\S]+(?=\<\/table\>)/mi",file_get_contents($_FILES['heute']['tmp_name']),$_SESSION["heuteDB"]);
                preg_match("/(?<=\<table class=\"mon_list\" \>)[\s\S]+(?=\<\/table\>)/mi",file_get_contents($_FILES['morgen']['tmp_name']),$_SESSION["morgenDB"]);
                $_SESSION["heuteDB"] = str_replace("  "," ",str_replace(' style="background-color: #FFFFFF" ',"",str_replace("&nbsp;","",str_replace("→","->",str_replace("<b>","",str_replace("</b>","",$_SESSION["heuteDB"][0]))))));
                $_SESSION["heuteDB"] = str_replace("  "," ",str_replace(' style="background-color: #FFFFFF" ',"",str_replace("&nbsp;","",str_replace("→","->",str_replace("<b>","",str_replace("</b>","",$_SESSION["morgenDB"][0]))))));
                preg_match_all("/(?<!\<tr class=\'list )((?<=odd\'\>)|(?<=even\'\>))[\s\S]+(?=\<\/tr\>)/miUg",$_SESSION["heuteDB"],$_SESSION["heuteDB"],PREG_PATTERN_ORDER);
                for($i = 0; $i < count($_SESSION["heuteDB"][0]); $i++) {
                    preg_match_all("/(?<=\<td class=\"list\" align=\"center\"\>)[\s\S]+(?=\<\/td\>)/miUg",$_SESSION["heuteDB"][0][$i],$_SESSION["heuteDB"][0][$i],PREG_PATTERN_ORDER);
                }
                preg_match_all("/(?<!\<tr class=\'list )((?<=odd\'\>)|(?<=even\'\>))[\s\S]+(?=\<\/tr\>)/miUg",$_SESSION["heuteDB"],$_SESSION["morgenDB"],PREG_PATTERN_ORDER);
                for($i = 0; $i < count($_SESSION["morgenDB"][0]); $i++) {
                    preg_match_all("/(?<=\<td class=\"list\" align=\"center\"\>)[\s\S]+(?=\<\/td\>)/miUg",$_SESSION["morgenDB"][0][$i],$_SESSION["morgenDB"][0][$i],PREG_PATTERN_ORDER);
                }
                $_SESSION["heute"] = $heute;
                $_SESSION["morgen"] = $morgen;
            }
            function getNext($pWt) {
                $wte = ["Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "", ""];
                $curT = time() - 24 * 60 * 60 * 3;
                while($wte[date('N', $curT) - 1] != $pWt) {
                    $curT += 24 * 60 * 60;
                }
                return date("d-m-Y", $curT);
            }
            if(!empty($_POST["vpheute"]) && !empty($_POST["vpmorgen"])) {
                $plan = $_POST['vpheute'];
                $planParts = explode("_", $plan);
                $tag = $planParts[0];
                $planParts = array_slice($planParts,1);
                $content = implode("_",$planParts);
                file_put_contents("plan/" . $tag . ".vp", $content);
                echo $plan;

                $plan = $_POST['vpmorgen'];
                $planParts = explode("_", $plan);
                $tag = $planParts[0];
                $planParts = array_slice($planParts,1);
                $content = implode("_",$planParts);
                file_put_contents("plan/" . $tag . ".vp", $content);
                echo $plan;

                if(file_get_contents("plan/STAND.vp") != $_POST["vpstand"]) {
                    file_put_contents("plan/STAND.vp", $_POST["vpstand"]);
                    $akt_old = file_get_contents("aktualisierungen.txt");
                    file_put_contents("aktualisierungen.txt", $akt_old . $_POST["vpstand"] . "\n");
                }
                $days = ["Montag","Dienstag","Mittwoch","Donnerstag","Freitag"];
                for($i = 0; $i < 5; $i++) {
                    $expire = filemtime("plan/" . $days[$i] . ".vp") - time() + 108000;
                    if($expire <= 0 && file_get_contents("plan/" . $days[$i] . ".vp") != "") {
                        file_put_contents("plan/" . $days[$i] . ".vp", "");
                    }
                }
                
                $tag1 = explode("_", $_POST['vpheute'])[0];
                $tag2 = explode("_", $_POST['vpmorgen'])[0];
                $fn1 = getNext($tag1) . "_" . $tag1 . "_" . str_replace(".", "-", str_replace(" ", "-", str_replace(":", "-", $_POST["vpstand"]))) . ".html";
                $fn2 = getNext($tag2) . "_" . $tag2 . "_" . str_replace(".", "-", str_replace(" ", "-", str_replace(":", "-", $_POST["vpstand"]))) . ".html";
                file_put_contents("archive/" . $fn1, $_SESSION["heute"]);
                file_put_contents("archive/" . $fn2, $_SESSION["morgen"]);

                var_dump($_SESSION["heuteDB"]);

                $plan = $_POST['vpheute'];
                $planParts = explode("_", $plan);
                $tag = $planParts[0];
                uploadToDB($_SESSION["heuteDB"],$tag);

                $plan = $_POST['vpmorgen'];
                $planParts = explode("_", $plan);
                $tag = $planParts[0];
                uploadToDB($_SESSION["morgenDB"],$tag);
            }
        ?>
    </body>

</html>
