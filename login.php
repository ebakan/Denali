<?php
require_once("cookie.php");

$cookie = new cookie();

//If there are no get variables and there is a cookie - reroute to reg
if($cookie->verifyCookie() && !(isset($_GET['error'])) && !(isset($_GET['success'])))
{
    session_start();
    if(isset($_SESSION['uid']))
        header("Location: reg.php");
    }

//If the error is 1 or the user is successfull - remove cokie
if(isset($_GET['error']) || isset($_GET['success']))
    if(isset($_COOKIE["user"]))
        setcookie("user","",time()-3600);

    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Login Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src='jquery.hint-with-password.js' type='text/javascript'></script>
<script type="text/javascript" charset="utf-8">
    $(function(){ 
        $('input[title!=""]').hint();
    });
</script>

<style type="text/css">
<?php

if(isset($_GET['error'])){
    $errorid = $_GET['error'];
    echo "#name, #id { background:red; }";
    echo ".error { color:red; }";
}else if (isset($_GET['success']))
echo ".error { color:green;}";
?>
html{text-align:center;}

</style>
</head>

<body>
<h2 id="reg">Immigration Summit Registration</h2>
<div id="header">
<img src="bell.png" alt="Bellarmine Immigration Summit" style="height:150px; width:150px;"/>
<span id="info" style="display:none;">You don't have to fill the form, really. Just click on Next and Back to see the demo.</span></p>
</div>

<form class="form" method="post" action="reviewEvents.php">
<h2>Login</h2>
<div id="loginstructions">Once you've registered, log in again to view your events.</div>
<input name="name" type="text" id="name" title="BCP Login" />
<input name="pass" type="password" id="id" title="Password" />
<div class="error" id="spacer">
<?php
//Error handling
if(isset($_GET['success']))
    if($_GET['success'] == 1)
        echo "You successfully registered.";
if(isset($_GET['error']))
    switch($_GET['error']){
    case 0:
        echo "Please e-enter your email address and password.";
        break;
    case 1:
        echo "You have already registered. You have been signed out.";
        break;
    case 2:
        echo "There has been an error with our database. Please try again later.";
        break;
    case 3:
        echo "One or more of the events you chose is full. Please try again.";
    }

?>
</div>
<input type="submit" id="spacer" value="Submit" />
<br />
<br />
<a id="spacer" href="checkEvents.php">Current Registrations</a>
</form>
<p id="writtenby">Written by Jonathon Elfar and Eric Bakan</p>
</body>
</html>
