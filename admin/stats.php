<?php
            if(!session_start()) {
                die();
            } else {
                header("Cache-control: private");
            }
            error_reporting(E_ERROR | E_PARSE);
?>
<!doctype html>


<html>
    
    <head>
        <title>Wasserturmplan - Statistiken</title>
        <meta charset="utf-8">
        <link rel="stylesheet" src="style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            function orderfach(tablep) {
                var table, rows, switching, i, x, y, shouldSwitch;
                table = document.getElementById(tablep);
                switching = true;
                while (switching) {
                    switching = false;
                    rows = table.rows;
                    for (i = 1; i < (rows.length - 1); i++) {
                        shouldSwitch = false;
                        x = rows[i].getElementsByTagName("td")[0];
                        y = rows[i + 1].getElementsByTagName("td")[0];
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                    }
                }
                document.getElementById(tablep + "graph").innerHTML = "";
            }
            function orderanzahl(tablep) {
                orderfach(tablep);
                var table, rows, switching, i, x, y, shouldSwitch;
                table = document.getElementById(tablep);
                switching = true;
                while (switching) {
                    switching = false;
                    rows = table.rows;
                    for (i = 1; i < (rows.length - 1); i++) {
                        shouldSwitch = false;
                        x = rows[i].getElementsByTagName("td")[1];
                        y = rows[i + 1].getElementsByTagName("td")[1];
                        if (parseFloat(x.innerHTML) < parseFloat(y.innerHTML)) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                    }
                }
                showgraph(1,tablep);
            }
            function orderanzahls() {
                orderfach("statst");
                var table, rows, switching, i, x, y, shouldSwitch;
                table = document.getElementById("statst");
                switching = true;
                while (switching) {
                    switching = false;
                    rows = table.rows;
                    for (i = 1; i < (rows.length - 1); i++) {
                        shouldSwitch = false;
                        x = rows[i].getElementsByTagName("td")[1];
                        y = rows[i + 1].getElementsByTagName("td")[1];
                        if (parseFloat(x.innerHTML) < parseFloat(y.innerHTML)) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                    }
                }
                showgraphs(1);
            }
            function orderbenutzer(tablep) {
                orderfach(tablep);
                var table, rows, switching, i, x, y, shouldSwitch;
                table = document.getElementById(tablep);
                switching = true;
                while (switching) {
                    switching = false;
                    rows = table.rows;
                    for (i = 1; i < (rows.length - 1); i++) {
                        shouldSwitch = false;
                        x = rows[i].getElementsByTagName("td")[2];
                        y = rows[i + 1].getElementsByTagName("td")[2];
                        if (parseFloat(x.innerHTML) < parseFloat(y.innerHTML)) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                    }
                }
                showgraph(2,tablep);
            }
            function orderdswa(tablep) {
                orderfach(tablep);
                var table, rows, switching, i, x, y, shouldSwitch;
                table = document.getElementById(tablep);
                switching = true;
                while (switching) {
                    switching = false;
                    rows = table.rows;
                    for (i = 1; i < (rows.length - 1); i++) {
                        shouldSwitch = false;
                        x = rows[i].getElementsByTagName("td")[3];
                        y = rows[i + 1].getElementsByTagName("td")[3];
                        if (parseFloat(x.innerHTML) < parseFloat(y.innerHTML)) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                    }
                }
                showgraph(3,tablep);
            }
            function orderdsw(tablep) {
                orderfach(tablep);
                var table, rows, switching, i, x, y, shouldSwitch;
                table = document.getElementById(tablep);
                switching = true;
                while (switching) {
                    switching = false;
                    rows = table.rows;
                    for (i = 1; i < (rows.length - 1); i++) {
                        shouldSwitch = false;
                        x = rows[i].getElementsByTagName("td")[4];
                        y = rows[i + 1].getElementsByTagName("td")[4];
                        if (parseFloat(x.innerHTML) < parseFloat(y.innerHTML)) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                    }
                }
                showgraph(4,tablep);
            }
            function orderprozent(tablep) {
                orderfach(tablep);
                var table, rows, switching, i, x, y, shouldSwitch;
                table = document.getElementById(tablep);
                switching = true;
                while (switching) {
                    switching = false;
                    rows = table.rows;
                    for (i = 1; i < (rows.length - 1); i++) {
                        shouldSwitch = false;
                        x = rows[i].getElementsByTagName("td")[5];
                        y = rows[i + 1].getElementsByTagName("td")[5];
                        if (parseFloat(x.innerHTML) < parseFloat(y.innerHTML)) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                    }
                }
                showgraph(5,tablep);
            }
            function orderprozents() {
                orderfach("statst");
                var table, rows, switching, i, x, y, shouldSwitch;
                table = document.getElementById("statst");
                switching = true;
                while (switching) {
                    switching = false;
                    rows = table.rows;
                    for (i = 1; i < (rows.length - 1); i++) {
                        shouldSwitch = false;
                        x = rows[i].getElementsByTagName("td")[2];
                        y = rows[i + 1].getElementsByTagName("td")[2];
                        if (parseFloat(x.innerHTML) < parseFloat(y.innerHTML)) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                    }
                }
                showgraphs(2);
            }
            function orderbn() {
                var table, rows, switching, i, x, y, shouldSwitch;
                table = document.getElementById("statsb");
                switching = true;
                while (switching) {
                    switching = false;
                    rows = table.rows;
                    for (i = 1; i < (rows.length - 1); i++) {
                        shouldSwitch = false;
                        x = rows[i].getElementsByTagName("td")[0];
                        y = rows[i + 1].getElementsByTagName("td")[0];
                        if (x.innerHTML > y.innerHTML) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                    }
                }
            }
            function ordered() {
                orderbn();
                var table, rows, switching, i, x, y, shouldSwitch;
                table = document.getElementById("statsb");
                switching = true;
                while (switching) {
                    switching = false;
                    rows = table.rows;
                    for (i = 1; i < (rows.length - 1); i++) {
                        shouldSwitch = false;
                        x = rows[i].getElementsByTagName("td")[3];
                        y = rows[i + 1].getElementsByTagName("td")[3];
                        if (x.innerHTML > y.innerHTML) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                    }
                }
            }
            function orderlc() {
                orderbn();
                var table, rows, switching, i, x, y, shouldSwitch;
                table = document.getElementById("statsb");
                switching = true;
                while (switching) {
                    switching = false;
                    rows = table.rows;
                    for (i = 1; i < (rows.length - 1); i++) {
                        shouldSwitch = false;
                        x = rows[i].getElementsByTagName("td")[4];
                        y = rows[i + 1].getElementsByTagName("td")[4];
                        if (parseFloat(x.innerHTML) < parseFloat(y.innerHTML)) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                    }
                }
            }
            function orderstp() {
                orderbn();
                var table, rows, switching, i, x, y, shouldSwitch;
                table = document.getElementById("statsb");
                switching = true;
                while (switching) {
                    switching = false;
                    rows = table.rows;
                    for (i = 1; i < (rows.length - 1); i++) {
                        shouldSwitch = false;
                        x = rows[i].getElementsByTagName("td")[5];
                        y = rows[i + 1].getElementsByTagName("td")[5];
                        if (x.innerHTML > y.innerHTML) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                    }
                }
            }
            function orderth() {
                orderbn();
                var table, rows, switching, i, x, y, shouldSwitch;
                table = document.getElementById("statsb");
                switching = true;
                while (switching) {
                    switching = false;
                    rows = table.rows;
                    for (i = 1; i < (rows.length - 1); i++) {
                        shouldSwitch = false;
                        x = rows[i].getElementsByTagName("td")[1].innerHTML;
                        y = rows[i + 1].getElementsByTagName("td")[1].innerHTML;
                        if(x == "auto") {
                            x = 1;
                        } else if(x == "d") {
                            x = 0;
                        } else {
                            x = 2;
                        }
                        if(y == "auto") {
                            y = 1;
                        } else if(y == "d") {
                            y = 0;
                        } else {
                            y = 2;
                        }
                        if(x > y) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                    }
                }
            }
            function orderff() {
                orderbn();
                var table, rows, switching, i, x, y, shouldSwitch;
                table = document.getElementById("statsb");
                switching = true;
                while (switching) {
                    switching = false;
                    rows = table.rows;
                    for (i = 1; i < (rows.length - 1); i++) {
                        shouldSwitch = false;
                        x = rows[i].getElementsByTagName("td")[2].innerHTML;
                        y = rows[i + 1].getElementsByTagName("td")[2].innerHTML;
                        if(parseFloat(x) < parseFloat(y)) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                    }
                }
            }
            function showgraph(stelle,tablep) {
                table = document.getElementById(tablep);
                rows = table.rows;
                highest = parseFloat(rows[2].getElementsByTagName("td")[stelle].innerHTML);
                document.getElementById(tablep + "graph").innerHTML = "";
                for (i = 2; i < rows.length; i++) {
                    fachname = rows[i].getElementsByTagName("td")[0].innerHTML;
                    fachwert = rows[i].getElementsByTagName("td")[stelle].innerHTML;
                    document.getElementById(tablep + "graph").innerHTML += '<div class="' + fachname.replace("Regen-","Regen") + '"style="width: calc(96vw / ' + highest + ' * ' + parseFloat(fachwert) + '); margin: 1vw; padding: 0; border: 1px white solid; height: 1.5vw;font-size: 1vw;line-height: 1.5vw;"> ' + fachname + '</div><p style="font-size: 1vw; margin-top: -1vw; margin-left: 1vw;">' + fachwert + '</p>';
                }
                
            }
            function showgraphs(stelle) {
                table = document.getElementById("statst");
                rows = table.rows;
                highest = parseFloat(rows[1].getElementsByTagName("td")[stelle].innerHTML);
                document.getElementById("statstgraph").innerHTML = "";
                for (i = 1; i < rows.length; i++) {
                    fachname = rows[i].getElementsByTagName("td")[0].innerHTML;
                    fachwert = rows[i].getElementsByTagName("td")[stelle].innerHTML;
                    document.getElementById("statstgraph").innerHTML += '<div class="' + fachname.replace + '"style="width: calc(96vw / ' + highest + ' * ' + parseFloat(fachwert) + '); margin: 1vw; padding: 0; border: 1px white solid; height: 1.5vw;font-size: 1vw;line-height: 1.5vw;"> ' + fachname + '</div><p style="font-size: 1vw; margin-top: -1vw; margin-left: 1vw;">' + fachwert + '</p>';
                }
                
            }
            function refreshPage() {
                var page_y = document.getElementsByTagName("html")[0].scrollTop;
                window.location.href = window.location.href.split('?')[0] + '?page_y=' + page_y;
            }
            function yset() {
                if ( window.location.href.indexOf('page_y') != -1 ) {
                    var match = window.location.href.split('?')[1].split("&")[0].split("=");
                    document.getElementsByTagName("html")[0].scrollTop = match[1];
                }
            }
            window.addEventListener("scroll", function(){
                if(window.scrollY < -350){
                    window.location.href = window.location.href.split('?')[0] + '?page_y=0';
                    document.getElementsByTagName("html")[0].scrollTop = 0;
                    document.location.reload();
                }
            });
            function graphstunde() {
                var graph = document.getElementById("actgraph");
                graph.innerHTML = "";
                for(i = 0; i < 60; i++) {
                     graph.innerHTML += "<div id='graphH" + i + "'style='background-color: blue; height: 30vh; width: calc((96vw - 10px) / 60 - 0.2vw); margin-left: 0.1vw; margin-right: 0.1vw; float: left; text-align: center;'>" + i + "</div>";
                }
                var counter = [];
                for(i = 0; i < 60; i++) {
                    counter[i] = 0;
                }
                var dates = document.getElementById("rawact").innerHTML.split("_");
                var date = document.getElementById("datenow").innerHTML;
                for(i = 0; i < dates.length; i++) {
                    if(dates[i].split(".")[0] == date.split(".")[0] && dates[i].split(".")[1] == date.split(".")[1] && dates[i].split(".")[2] == date.split(".")[2] && dates[i].split(".")[3] == date.split(".")[3]) {
                        counter[parseInt(dates[i].split(".")[4], 10)]++;
                    }
                }
                var maxnum = Math.max(...counter);
                for(i = 0; i < 60; i++) {
                    document.getElementById("graphH" + i).style.minHeight = "2px";
                    document.getElementById("graphH" + i).style.height = "calc(30vh / " + maxnum + " * " + counter[i] + ")";
                    if(counter[i] == 0) {
                        document.getElementById("graphH" + i).style.height = "2px";
                    }
                    document.getElementById("graphH" + i).style.marginTop = "calc(30vh - " + document.getElementById("graphH" + i).style.height + ")";
                    if(counter[i] > 1) {
                        //document.getElementById("graphH" + i).innerHTML += "<BR>(" + counter[i] + ")";
                    }
                }
            }
            function graphtag() {
                var graph = document.getElementById("actgraph");
                graph.innerHTML = "";
                for(i = 0; i < 24; i++) {
                     graph.innerHTML += "<div id='graphH" + i + "'style='background-color: blue; height: 30vh; width: calc((96vw - 10px) / 24 - 0.2vw); margin-left: 0.1vw; margin-right: 0.1vw; float: left; text-align: center;'>" + i + "</div>";
                }
                var counter = [];
                for(i = 0; i < 24; i++) {
                    counter[i] = 0;
                }
                var dates = document.getElementById("rawact").innerHTML.split("_");
                var date = document.getElementById("datenow").innerHTML;
                for(i = 0; i < dates.length; i++) {
                    if(dates[i].split(".")[0] == date.split(".")[0] && dates[i].split(".")[1] == date.split(".")[1] && dates[i].split(".")[2] == date.split(".")[2]) {
                        counter[parseInt(dates[i].split(".")[3], 10)]++;
                    }
                }
                var maxnum = Math.max(...counter);
                for(i = 0; i < 24; i++) {
                    document.getElementById("graphH" + i).style.minHeight = "2px";
                    document.getElementById("graphH" + i).style.height = "calc(30vh / " + maxnum + " * " + counter[i] + ")";
                    if(counter[i] == 0) {
                        document.getElementById("graphH" + i).style.height = "2px";
                    }
                    document.getElementById("graphH" + i).style.marginTop = "calc(30vh - " + document.getElementById("graphH" + i).style.height + ")";
                    if(counter[i] > 1) {
                        //document.getElementById("graphH" + i).innerHTML += "<BR>(" + counter[i] + ")";
                    }
                }
            }
            function graphmonat() {
                var graph = document.getElementById("actgraph");
                graph.innerHTML = "";
                for(i = 0; i < 31; i++) {
                     graph.innerHTML += "<div id='graphH" + i + "'style='background-color: blue; height: 30vh; width: calc((96vw - 10px) / 31 - 0.2vw); margin-left: 0.1vw; margin-right: 0.1vw; float: left; text-align: center;'>" + (i + 1) + "</div>";
                }
                var counter = [];
                for(i = 0; i < 31; i++) {
                    counter[i] = 0;
                }
                var dates = document.getElementById("rawact").innerHTML.split("_");
                var date = document.getElementById("datenow").innerHTML;
                for(i = 0; i < dates.length; i++) {
                    if(dates[i].split(".")[0] == date.split(".")[0] && dates[i].split(".")[1] == date.split(".")[1]) {
                        counter[parseInt(dates[i].split(".")[2], 10) - 1]++;
                    }
                }
                var maxnum = Math.max(...counter);
                for(i = 0; i < 31; i++) {
                    document.getElementById("graphH" + i).style.minHeight = "2px";
                    document.getElementById("graphH" + i).style.height = "calc(30vh / " + maxnum + " * " + counter[i] + ")";
                    if(counter[i] == 0) {
                        document.getElementById("graphH" + i).style.height = "2px";
                    }
                    document.getElementById("graphH" + i).style.marginTop = "calc(30vh - " + document.getElementById("graphH" + i).style.height + ")";
                    if(counter[i] > 1) {
                        //document.getElementById("graphH" + i).innerHTML += "<BR>(" + counter[i] + ")";
                    }
                }
            }
            function graphjahr() {
                var graph = document.getElementById("actgraph");
                graph.innerHTML = "";
                for(i = 0; i < 12; i++) {
                     graph.innerHTML += "<div id='graphH" + i + "'style='background-color: blue; height: 30vh; width: calc((96vw - 10px) / 12 - 0.2vw); margin-left: 0.1vw; margin-right: 0.1vw; float: left; text-align: center;'>" + (i + 1) + "</div>";
                }
                var counter = [];
                for(i = 0; i < 31; i++) {
                    counter[i] = 0;
                }
                var dates = document.getElementById("rawact").innerHTML.split("_");
                var date = document.getElementById("datenow").innerHTML;
                for(i = 0; i < dates.length; i++) {
                    if(dates[i].split(".")[0] == date.split(".")[0]) {
                        counter[parseInt(dates[i].split(".")[1], 10) - 1]++;
                    }
                }
                var maxnum = Math.max(...counter);
                for(i = 0; i < 12; i++) {
                    document.getElementById("graphH" + i).style.minHeight = "2px";
                    document.getElementById("graphH" + i).style.height = "calc(30vh / " + maxnum + " * " + counter[i] + ")";
                    if(counter[i] == 0) {
                        document.getElementById("graphH" + i).style.height = "2px";
                    }
                    document.getElementById("graphH" + i).style.marginTop = "calc(30vh - " + document.getElementById("graphH" + i).style.height + ")";
                    if(counter[i] > 1) {
                        //document.getElementById("graphH" + i).innerHTML += "<BR>(" + counter[i] + ")";
                    }
                }
            }
        </script>
    </head>
    <body style="font-size: 1vw;" onload="yset()">
        <?php
        $blocked = ["demo"/*weitere Nutzer*/];
            $style = '<link rel="stylesheet" href="../theme_dark.css"><link rel="stylesheet" href="ff.css">';
            if(isset($_POST['submit']) && isset($_POST['pw'])) {
                $pw = $_POST['pw'];
                $pw = hash("sha512", $pw, false);
                if($pw == "PasswordHash") {
                    $_SESSION["loggedinstats"] = true;
                }
            }
            if($_SESSION["loggedinstats"] == true) {
                echo "Seitenaufrufe: " . file_get_contents("../aufrufe.txt") . '<button onclick="refreshPage()" style="position: fixed; top: 5px; right: 5px;">Neuladen</button>';
                echo "<BR>Aktivität:<BR><div style='width: 96vw; height: 40vh;' id='activity'>
                            <p id='rawact' style='display: none'>" . str_replace("\n","_",file_get_contents("../aufrufliste.txt")) . "</p>
                            <p id='datenow' style='display: none'>" . date("Y.m.d.H.i.s") . "</p>
                            <table border='1' style='width: 96vw; margin: 1vw;'>
                                <tr><th onclick='graphstunde()'>Stunde</th><th onclick='graphtag()'>Tag</th><th onclick='graphmonat()'>Monat</th><th onclick='graphjahr()'>Jahr</th></tr>
                                <tr><td colspan=4 id='actgraph'>

                                </td></tr>
                            </table>
                        </div>
                        <script>graphstunde()</script>";
                $vpmo = file_get_contents("../plan/Montag.vp");
                $vpdi = file_get_contents("../plan/Dienstag.vp");
                $vpmi = file_get_contents("../plan/Mittwoch.vp");
                $vpdo = file_get_contents("../plan/Donnerstag.vp");
                $vpfr = file_get_contents("../plan/Freitag.vp");
                echo "<BR><BR>Vertretungsstunden:";
                echo "<table id='statsv' border='1' style='width: 96vw; margin: 1vw;'>
                        <tr><th>Tag</th><th>Entfall</th><th>EVA</th><th>Raumwechsel</th><th>Freisetzung</th><th>Vertretung</th></tr>
                        <tr><td>Montag</td><td>" . substr_count($vpmo,'Entfall') . "</td><td>" . substr_count($vpmo,'EVA') . "</td><td>" . substr_count($vpmo,'Raumwechsel') . "</td><td>" . substr_count($vpmo,'Freisetzung') . "</td><td>" . substr_count($vpmo,'Vertretung') . "</td></tr>
                        <tr><td>Dienstag</td><td>" . substr_count($vpdi,'Entfall') . "</td><td>" . substr_count($vpdi,'EVA') . "</td><td>" . substr_count($vpdi,'Raumwechsel') . "</td><td>" . substr_count($vpdi,'Freisetzung') . "</td><td>" . substr_count($vpdi,'Vertretung') . "</td></tr>
                        <tr><td>Mittwoch</td><td>" . substr_count($vpmi,'Entfall') . "</td><td>" . substr_count($vpmi,'EVA') . "</td><td>" . substr_count($vpmi,'Raumwechsel') . "</td><td>" . substr_count($vpmi,'Freisetzung') . "</td><td>" . substr_count($vpmi,'Vertretung') . "</td></tr>
                        <tr><td>Donnerstag</td><td>" . substr_count($vpdo,'Entfall') . "</td><td>" . substr_count($vpdo,'EVA') . "</td><td>" . substr_count($vpdo,'Raumwechsel') . "</td><td>" . substr_count($vpdo,'Freisetzung') . "</td><td>" . substr_count($vpdo,'Vertretung') . "</td></tr>
                        <tr><td>Freitag</td><td>" . substr_count($vpfr,'Entfall') . "</td><td>" . substr_count($vpfr,'EVA') . "</td><td>" . substr_count($vpfr,'Raumwechsel') . "</td><td>" . substr_count($vpfr,'Freisetzung') . "</td><td>" . substr_count($vpfr,'Vertretung') . "</td></tr>
                    </table>";
                $db = new PDO("mysql:host=mysql.server.url;dbname=databasename","username","PaSswoRt123");
                if ($db->connect_error) {
                    die("Verbindung fehlgeschlagen");
                }
                $allbn = $db->prepare("SELECT bn,es,ed,lc FROM `benutzer`");
                $allbn->execute();
                $result = $allbn->fetchAll(PDO::FETCH_ASSOC);
                $benutzer = [];
                $benutzera = [];
                $einstellungen = [];
                $eds = [];
                $lcs = [];
                $stp = [];
                foreach($result as $data) {
                    if(!in_array($data['bn'],$blocked)) {
                        $benutzera[count($benutzera)] = $data['bn'];
                        $einstellungen[count($einstellungen)] = $data["es"];
                        $eds[count($eds)] = $data['ed'];
                        $lcs[count($lcs)] = $data['lc'];
                        if(file_get_contents("../sp/" . $data['bn'] . ".txt") == file_get_contents("../sp/template.txt")) {
                            $stp[count($stp)] = "Nein";
                        } else {
                            $stp[count($stp)] = "Ja";
                            $benutzer[count($benutzer)] = $data['bn'];
                        }
                    }
                }
                $db = null;
                echo "<BR><BR>Benutzer (" . count($benutzera) . "):";
                echo "<table id='statsb' border='1' style='width: 96vw; margin: 1vw;'>
                        <tr><th onclick='orderbn()'>Benutzer</th><th onclick='orderth()'>Theme</th><th onclick='orderff()'>FF</th><th onclick='ordered()'>Erstellungsdatum</th><th onclick='orderlc()'>Logins</th><th onclick='orderstp()'>Stundenplan</th></tr>";
                for($i = 0; $i < count($benutzera); $i++) {
                    echo "<tr class='dataset'><td class='$benutzera[$i]'>" . $benutzera[$i] . "</td><td class='$benutzera[$i]'>" . explode(';',$einstellungen[$i])[0] . "</td><td class='$benutzera[$i]'>" . explode(';',$einstellungen[$i])[1] . "</td><td class='$benutzera[$i]'>" . $eds[$i] . "</td><td class='$benutzera[$i]'>" . $lcs[$i] . "</td><td class='$benutzera[$i]'>" . $stp[$i] . "</td></tr>";
                }
                
                echo '</table>
                <div id="statsbgraph"></div>
                <script>orderbn()</script>';
                
                for($i = 0; $i < count($benutzer); $i++) {
                    $sptmp = file_get_contents("../sp/" . $benutzer[$i] . ".txt");
                    $sp[$benutzer[$i]] = explode("_",$sptmp);
                }
                $stufenliste = [];
                for($i = 0; $i < count($benutzer); $i++) {
                     $sptmp = $sp[$benutzer[$i]][count($sp[$benutzer[$i]]) - 1];
                     $found = false;
                     for($k = 0; $k < count($stufenliste); $k++) {
                            if($stufenliste[$k][0] == $sptmp) {
                                $found = true;
                                $stufenliste[$k][1]++;
                            }
                     }
                     if($found == false) {
		                $stufenliste[count($stufenliste)] = [$sptmp,1];
                     }
                }
                echo "<BR><BR>Klassen/Stufen (" . count($stufenliste) . "):";
                echo "<table id='statst' border='1' style='width: 96vw; margin: 1vw;'>
                        <tr><th onclick='orderfach(\"statst\")'>Klasse/Stufe</th><th onclick='orderanzahls()'>Anzahl</th><th onclick='orderprozents()'>% der Benutzer</th></tr>";
                foreach($stufenliste as $data) {
                    echo "<tr class='dataset'><td class='$data[0]'>" . $data[0] . "</td><td class='$data[0]'>" . $data[1] . "</td><td class='$data[0]'>" . $data[1] / count($benutzer) * 100 . "%</td></tr>";
                }
                echo '</table>
                <div id="statstgraph"></div>
                <script>orderfach("statst")</script>';
                $fachliste = [];
                for($i = 0; $i < count($benutzer); $i++) {
                    $benutzerfachl = [];
                    for($j = 0; $j < 40; $j++) {
                        $found = false;
                        $blfound = false;
                        for($k = 0; $k < count($fachliste); $k++) {
                            if($fachliste[$k][0] == explode("<BR>",$sp[$benutzer[$i]][$j])[0]) {
                                $found = true;
                                $fachliste[$k][1]++;
                                $stelle = $k;
                                for($l = 0; $l < count($benutzerfachl); $l++) {
                                    if($benutzerfachl[$l] == explode("<BR>",$sp[$benutzer[$i]][$j])[0]) {
                                        $blfound = true;
                                    }
                                }
                            }
                        }
                        if($found == false) {
                            $fachliste[count($fachliste)] = [explode("<BR>",$sp[$benutzer[$i]][$j])[0],1,1];
                            $benutzerfachl[count($benutzerfachl)] = explode("<BR>",$sp[$benutzer[$i]][$j])[0];
                        } else if($found == true && $blfound == false) {
                            $fachliste[$stelle][2]++;
                            $benutzerfachl[count($benutzerfachl)] = explode("<BR>",$sp[$benutzer[$i]][$j])[0];
                        }
                    }
                }
                echo "<BR><BR>Fächer (" . count($fachliste) . "):";
                echo "<table id='stats' border='1' style='width: 96vw; margin: 1vw;'>
                        <tr><th onclick='orderfach(\"stats\")'>Fach</th><th onclick='orderanzahl(\"stats\")'>Anzahl</th><th onclick='orderbenutzer(\"stats\")'>Benutzer</th><th onclick='orderdswa(\"stats\")'>Durchschnitt Stunden/Woche (Alle)</th><th onclick='orderdsw(\"stats\")'>Durchschnitt Stunden/Woche</th><th onclick='orderprozent(\"stats\")'>% der Benutzer</th></tr>";
                foreach($fachliste as $data) {
                    $klasse = str_replace("Regen-","Regen",$data[0]);
                    echo "<tr class='dataset'><td class='$klasse'>" . $data[0] . "</td><td class='$klasse'>" . $data[1] . "</td><td class='$klasse'>" . $data[2] . "</td><td class='$klasse'>" . $data[1] / count($benutzer) . "</td><td class='$klasse'>" . $data[1] / $data[2] . "</td><td class='$klasse'>" . $data[2] / count($benutzer) * 100 . "%</td></tr>";
                }
                echo '</table>
                <div id="statsgraph"></div>
                <script>orderfach("stats")</script>';
                $lehrerliste = [];
                for($i = 0; $i < count($benutzer); $i++) {
                    if($sp[$benutzer[$i]][40] == "EF" || $sp[$benutzer[$i]][40] == "Q1" || $sp[$benutzer[$i]][40] == "Q2") {
                        $x = 2;
                    } else {
                        $x = 1;
                    }
                    $benutzerlehrerl = [];
                    for($j = 0; $j < 40; $j++) {
                        $found = false;
                        $blfound = false;
                        for($k = 0; $k < count($lehrerliste); $k++) {
                            if($lehrerliste[$k][0] == explode("<BR>",$sp[$benutzer[$i]][$j])[$x]) {
                                $found = true;
                                $lehrerliste[$k][1]++;
                                $stelle = $k;
                                for($l = 0; $l < count($benutzerlehrerl); $l++) {
                                    if($benutzerlehrerl[$l] == explode("<BR>",$sp[$benutzer[$i]][$j])[$x]) {
                                        $blfound = true;
                                    }
                                }
                            }
                        }
                        if($found == false) {
                            $lehrerliste[count($lehrerliste)] = [explode("<BR>",$sp[$benutzer[$i]][$j])[$x],1,1];
                            $benutzerlehrerl[count($benutzerlehrerl)] = explode("<BR>",$sp[$benutzer[$i]][$j])[$x];
                        } else if($found == true && $blfound == false) {
                            $lehrerliste[$stelle][2]++;
                            $benutzerlehrerl[count($benutzerlehrerl)] = explode("<BR>",$sp[$benutzer[$i]][$j])[$x];
                        }
                    }
                }
                echo "<BR><BR>Lehrer (" . count($lehrerliste) . "):";
                echo "<table id='statsl' border='1' style='width: 96vw; margin: 1vw;'>
                        <tr><th onclick='orderfach(\"statsl\")'>Lehrer</th><th onclick='orderanzahl(\"statsl\")'>Anzahl</th><th onclick='orderbenutzer(\"statsl\")'>Benutzer</th><th onclick='orderdswa(\"statsl\")'>Durchschnitt Stunden/Woche (Alle)</th><th onclick='orderdsw(\"statsl\")'>Durchschnitt Stunden/Woche</th><th onclick='orderprozent(\"statsl\")'>% der Benutzer</th></tr>";
                foreach($lehrerliste as $data) {
                    echo "<tr class='dataset'><td class='$data[0]'>" . $data[0] . "</td><td class='$data[0]'>" . $data[1] . "</td><td class='$data[0]'>" . $data[2] . "</td><td class='$data[0]'>" . $data[1] / count($benutzer) . "</td><td class='$data[0]'>" . $data[1] / $data[2] . "</td><td class='$data[0]'>" . $data[2] / count($benutzer) * 100 . "%</td></tr>";
                }
                echo '</table>
                <div id="statslgraph"></div>
                <script>orderfach("statsl")</script>';
                $raumliste = [];
                for($i = 0; $i < count($benutzer); $i++) {
                    if($sp[$benutzer[$i]][40] == "EF" || $sp[$benutzer[$i]][40] == "Q1" || $sp[$benutzer[$i]][40] == "Q2") {
                        $x = 3;
                    } else {
                        $x = 2;
                    }
                    $benutzerrauml = [];
                    for($j = 0; $j < 40; $j++) {
                        $found = false;
                        $blfound = false;
                        for($k = 0; $k < count($raumliste); $k++) {
                            if($raumliste[$k][0] == explode("<BR>",$sp[$benutzer[$i]][$j])[$x]) {
                                $found = true;
                                $raumliste[$k][1]++;
                                $stelle = $k;
                                for($l = 0; $l < count($benutzerrauml); $l++) {
                                    if($benutzerrauml[$l] == explode("<BR>",$sp[$benutzer[$i]][$j])[$x]) {
                                        $blfound = true;
                                    }
                                }
                            }
                        }
                        if($found == false) {
                            $raumliste[count($raumliste)] = [explode("<BR>",$sp[$benutzer[$i]][$j])[$x],1,1];
                            $benutzerrauml[count($benutzerrauml)] = explode("<BR>",$sp[$benutzer[$i]][$j])[$x];
                        } else if($found == true && $blfound == false) {
                            $raumliste[$stelle][2]++;
                            $benutzerrauml[count($benutzerrauml)] = explode("<BR>",$sp[$benutzer[$i]][$j])[$x];
                        }
                    }
                }
                echo "<BR><BR>Räume (" . count($raumliste) . "):";
                echo "<table id='statsr' border='1' style='width: 96vw; margin: 1vw;'>
                        <tr><th onclick='orderfach(\"statsr\")'>Raum</th><th onclick='orderanzahl(\"statsr\")'>Anzahl</th><th onclick='orderbenutzer(\"statsr\")'>Benutzer</th><th onclick='orderdswa(\"statsr\")'>Durchschnitt Stunden/Woche (Alle)</th><th onclick='orderdsw(\"statsr\")'>Durchschnitt Stunden/Woche</th><th onclick='orderprozent(\"statsr\")'>% der Benutzer</th></tr>";
                foreach($raumliste as $data) {
                    echo "<tr class='dataset'><td class='$data[0]'>" . $data[0] . "</td><td class='$data[0]'>" . $data[1] . "</td><td class='$data[0]'>" . $data[2] . "</td><td class='$data[0]'>" . $data[1] / count($benutzer) . "</td><td class='$data[0]'>" . $data[1] / $data[2] . "</td><td class='$data[0]'>" . $data[2] / count($benutzer) * 100 . "%</td></tr>";
                }
                echo '</table>
                <div id="statsrgraph"></div>
                <script>orderfach("statsr")</script>';
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