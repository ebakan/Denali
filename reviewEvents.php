<?php
require("config.php");
require("LdapUser.php");

if(!isset($_POST['name']) || !isset($_POST['pass']))
    header("Location: login.php");

$name = $_POST['name'];
$pass = $_POST['pass'];
$system = new system();

//Screens html/sql injections
$name = htmlentities($name);
$name = mysql_real_escape_string($name);
$pass = htmlentities($pass);
$pass = mysql_real_escape_string($pass);

if(strcmp(substr($name, -8), "@bcp.org") == 0)
{
    $name = substr($name, 0, -8);
}

$ldap = new LdapUser();

$events = "";


if(!($id = $ldap->auth($name, $pass)))
//    if(!$cookie->verifyCookie())
        header("Location: login.php?error=0");
else{
    $hashkey = $name;
    $hashkey .= SALT;

    //Creates cookie
    require_once("cookie.php");
    $cookie = new cookie();
    $cookie->setCookie( $hashkey, false);
    
    //Session data to get username and id
    session_start();
    $_SESSION['uname'] = $name;
    $_SESSION['upass'] = $pass;
    $_SESSION['uid'] = $id;

    $events = $system->getStudentRegistrations($id);

    if(!$events) {
        if(isset($_SESSION['uid']))
        header("Location: reg.php");
    }

    for($i=1;$i<=4;$i++) {
        if(!isset($events[$i])) {
            $events[$i]=array('title' => 'None',
                              'speaker' => 'None',
                              'description' => 'None',
                              'length' => 'None',
                              'location' => 'None',
                              'email' => 'None');
        }

    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Login Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="style.css" />
<style type="text/css">
html{text-align:center;}
</style>
</head>

<body>
<a href="removeCookie.php" id="lo">Logout</a>
<h2 id="reg">Immigration Summit Registration</h2>
<div id="header">
<img src="bell.png" alt="Bellarmine Immigration Summit" style="height:150px; width:150px;"/>
<span id="info" style="display:none;">You don't have to fill the form, really. Just click on Next and Back to see the demo.</span></p>
</div>
<div id="info">
<p>Thank you for signing up! Your registrations are displayed below. For assistance please contact <a href="mailto:blindemann@bcp.org">Brad Lindemann</a>.</p>
</div>
<div id="review">
    <fieldset>
        <div class="ev evtop"><h2>First Event:</h2>
            <div class="label">Title </div><div id="title51" style="text-align:center"><?php echo $events[1]['title']?></div><br />
            <div class="label">Speaker </div><div id="speaker51" style="text-align:center"><?php echo $events[1]['speaker']?></div><br />
            <div class="label">Description </div><div id="description51" style="text-align:center"><?php echo $events[1]['description']?></div><br />
            <div class="label">Length (Mins)</div><div id="description51" style="text-align:center"><?php echo $events[1]['length']?></div><br />
            <div class="label">Room </div><div id="location51" style="text-align:center"><?php echo $events[1]['location']?></div><br />
            <div class="label">Email </div><div id="location51" style="text-align:center"><?php echo $events[1]['email']?></div><br />
        </div>
        <div class="ev evtop"><h2>Second Event:</h2>
            <div class="label">Title </div><div id="title52" style="text-align:center"><?php echo $events[2]['title']?></div><br />
            <div class="label">Speaker </div><div id="speaker52" style="text-align:center"><?php echo $events[2]['speaker']?></div><br />
            <div class="label">Description </div><div id="description52" style="text-align:center"><?php echo $events[2]['description']?></div><br />
            <div class="label">Length (Mins)</div><div id="description52" style="text-align:center"><?php echo $events[2]['length']?></div><br />
            <div class="label">Room </div><div id="location52" style="text-align:center"><?php echo $events[2]['location']?></div><br />
            <div class="label">Email </div><div id="location52" style="text-align:center"><?php echo $events[2]['email']?></div><br />
        </div>
        <div class="ev evtop"><h2>Third Event:</h2>
            <div class="label">Title </div><div id="title53" style="text-align:center"><?php echo $events[3]['title']?></div><br />
            <div class="label">Speaker </div><div id="speaker53" style="text-align:center"><?php echo $events[3]['speaker']?></div><br />
            <div class="label">Description </div><div id="description53" style="text-align:center"><?php echo $events[3]['description']?></div><br />
            <div class="label">Length (Mins)</div><div id="description53" style="text-align:center"><?php echo $events[3]['length']?></div><br />
            <div class="label">Room </div><div id="location53" style="text-align:center"><?php echo $events[3]['location']?></div><br />
            <div class="label">Email </div><div id="location53" style="text-align:center"><?php echo $events[3]['email']?></div><br />
        </div>
        <div class="ev"><h2>Fourth Event:</h2>
            <div class="label">Title </div><div id="title54" style="text-align:center"><?php echo $events[4]['title']?></div><br />
            <div class="label">Speaker </div><div id="speaker54" style="text-align:center"><?php echo $events[4]['speaker']?></div><br />
            <div class="label">Description </div><div id="description54" style="text-align:center"><?php echo $events[4]['description']?></div><br />
            <div class="label">Length (Mins)</div><div id="description54" style="text-align:center"><?php echo $events[4]['length']?></div><br />
            <div class="label">Room </div><div id="location54" style="text-align:center"><?php echo $events[4]['location']?></div><br />
            <div class="label">Email </div><div id="location54" style="text-align:center"><?php echo $events[4]['email']?></div><br />
        </div>

    </fieldset>
</div>

</body>
</html>
