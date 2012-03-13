<?php
require_once("config.php");
require_once("cookie.php");

$cookie = new cookie();

if(!$cookie->verifyCookie())
    header("Location: login.php");
else{
    db_login();
    $system = system::getInstance();

    session_start();
    $name = $_SESSION['uname'];

    $user = new User($name);

    //Store events in array
    $events = array(
        '0' => $_POST['dd1'],
        '1' => $_POST['dd2'],
        '2' => $_POST['dd3'],
        '3' => $_POST['dd4'],
    );


    //get id's based on name from db
    $ids = array();
    
    for($i = 0; $i < sizeof($events); $i++){;
        $sql = "SELECT * FROM events WHERE name='".$events[$i]."'";
        if(!($result = mysql_query($sql)))
            header("Location: login.php?error=2");
        while($row = mysql_fetch_array($result))
        {
           $ids[$i] = $row['id'];
        }

    }
    echo $system->getValidEvents();

    //select events by ids
    for($x = 0; $x < sizeof($ids); $x++)
    { $user->select($ids[$x], $x); }


/* //TEST TO SEE IF IDS AND EVS ARE SET UP PROPERLY
    $evs = $user->GetEvents();
    if(sizeof($evs) <= 0)
        echo "failed gettin events";
    else{
        for($i = 0; $i < sizeof($evs); $i++){
            echo $evs[$i];
            echo $ids[$i];
        }
    }
*/

    $system->register($user);

}

?>
