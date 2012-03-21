<?php
require("config.php");
db_login();

$system = new system();
$errs = "";
for($i=1; $i<=4; $i++) {
    if(!system->isEventValid($_GET['ev'.$i])) {
        $errs .= ",$i";
    }
}
echo substr($errs, 1) or "";
?>
