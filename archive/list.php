<!doctype html>


<html>
    
    <head>
        <title>Wasserturmplan - Archivliste</title>
        <meta charset="utf-8">
    </head>
    <style>
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            padding-left: 0;
        }
        li::before {
            content: "📄 ";
        }
    </style>
    <body onload="order(0)">
        <script>
            function format(pDate) {
                var datum = pDate.split("_")[0].split("-");
                var stand = pDate.split("_")[2].split("-");
                return datum[2] + datum[1] + datum[0] + stand[2] + stand[1] + stand[0] + stand[3] + stand[4];
            }
            function order(pBy) {
                var i, x, y, els;
                var switching = true;
                while(switching) {
                    switching = false;
                    els = document.getElementsByTagName("li");
                    for (i = 0; i < (els.length - 1); i++) {
                        shouldSwitch = false;
                        x = format(els[i + pBy].innerText);
                        y = format(els[i + 1 - pBy].innerText);
                        if (x < y) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                    if (shouldSwitch) {
                        els[i].parentNode.insertBefore(els[i + 1], els[i]);
                        switching = true;
                    }
                }
            }
            var ordered = 0;
            function reorder() {
                if(ordered == 0) {
                    ordered = 1;
                    document.getElementById("reorder").innerHTML = "Neueste zuerst";
                } else {
                    ordered = 0;
                    document.getElementById("reorder").innerHTML = "Älteste zuerst";
                }
                order(ordered);
            }
        </script>
        <button id="reorder" onclick="reorder()">Älteste zuerst</button>
        <ul id="links">
            <?php
                $files = scandir(dirname(__FILE__), SCANDIR_SORT_ASCENDING);
                for($i = 0; $i < count($files); $i++) {
                    if($files[$i] != "list.php" && $files[$i] != "." && $files[$i] != "..") {
                        echo "<li><a href='https://wasserturmplan.steffenmolitor.de/archive/" . $files[$i] . "'>" . $files[$i] . "</a></li>";
                    }
                }
            ?>
        </ul>
    </body>
</html>