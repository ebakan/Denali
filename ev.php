<?php
require("config.php");

$system = new system();
$errs = "";
for($i=1; $i<=4; $i++) {
    if(!$system->isEventValid($_GET["ev$i"])) {
        $errs .= ",$i";
    }
}

$str = substr($errs, 1);
if($str != "")
    echo substr($errs, 1);
//echo $err;
?>
