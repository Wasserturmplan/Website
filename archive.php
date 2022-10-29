<!doctype html>


<html>
    
    <head>
        <title>Wasserturmplan - Archivliste</title>
        <meta charset="utf-8">
    </head>
    <body>
        <?php
            $files = scandir(dirname(__FILE__). "/archive", SCANDIR_SORT_DESCENDING);
            for($i = 0; $i < count($files); $i++) {
                if($files[$i] != "list.php") {
                    echo "<a href='https://wasserturmplan.steffenmolitor.de/archive/" . $files[$i] . "'>" . $files[$i] . "</a><br>";
                }
            }
        ?>
    </body>
</html>