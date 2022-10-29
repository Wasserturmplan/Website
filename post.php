<!doctype html>


<html>
    
    <head>
        <title>Stundenplan - Post</title>
        <meta charset="utf-8">
        <link rel="stylesheet" src="style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body>
        <?php
            $days = ["Montag","Dienstag","Mittwoch","Donnerstag","Freitag"];
            for($i = 0; $i <= 4; $i++) {
                $wt = date("N") - 1;
                $expire = filemtime("plan/" . $days[$i] . ".vp") - time() + 120000;
                if($wt > $i && !($wt >= 4 && $i == 0) || $expire <= 0) {
                    unlink("plan/" . $days[$i] . ".vp");
                }
                echo $expire . "<BR>";
            }
            if($_GET['plan'] != null) {
                $plan = $_GET['plan'];
                $planParts = explode("_", $plan);
                $tag = $planParts[0];
                foreach($days as $day) {
                    if($day == $tag) {
                        $planParts = array_slice($planParts,1);
                        $content = implode("_",$planParts);
                        $content = str_replace("E1","Entfall",$content);
                        $content = str_replace("EV1","EVA",$content);
                        $content = str_replace("R1","Raumwechsel",$content);
                        $content = str_replace("F1","Freisetzung",$content);
                        $content = str_replace("V1","Vertretung",$content);
                        $content = str_replace("T1","Trotz-Absenz",$content);
                        file_put_contents("plan/" . $tag . ".vp", $content);
                        echo $tag . " " . $content;
                    }
                }
            }
        ?>
    </body>

</html>
        