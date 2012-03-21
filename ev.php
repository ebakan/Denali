<?php
require("config.php");
db_login();

$system = new system();
$validEvents1 = $system->getValidEventsByTimeslot("1");
$validEvents2 = $system->getValidEventsByTimeslot("2");
$validEvents3 = $system->getValidEventsByTimeslot("3");
$validEvents4 = $system->getValidEventsByTimeslot("4");

$id1 =  $_GET['ev1'];
$id2 =  $_GET['ev2'];
$id3 =  $_GET['ev3'];
$id4 =  $_GET['ev4'];

$arr1 = array();
$arr2 = array();
$arr3 = array();
$arr4 = array();

$errs = " ";

//echo "<html>";
for($x = 0; $x < count($validEvents1); $x++)
{   
    $arr1[$x] = $validEvents1[$x]['id'];
}

for($x = 0; $x < count($validEvents2); $x++)
{   
    $arr2[$x] = $validEvents2[$x]['id'];
}

for($x = 0; $x < count($validEvents3); $x++)
{   
    $arr3[$x] = $validEvents3[$x]['id'];
}

for($x = 0; $x < count($validEvents4); $x++)
{   
    $arr4[$x] = $validEvents4[$x]['id'];
}
//echo "</html>";


if(!in_array($id1, $arr1))
    if($errs != " ")
        $errs .= ",1";
    else
        $errs = "1";
if(!in_array($id2, $arr2))
    if($errs != " ")
        $errs .= ",2";
    else
        $errs = "2";
if(!in_array($id3, $arr3))
    if($errs != " ")
        $errs .= ",3";
    else
        $errs = "3";
if(!in_array($id4, $arr4))
    if($errs != " ")
        $errs .= ",4";
    else
        $errs = "4";

echo $errs;




?>
