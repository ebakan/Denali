<?php
require_once("cookie.php");

$cookie = new cookie();

//If there are no get variables and there is a cookie - reroute to reg
if($cookie->verifyCookie() && !(isset($_GET['error'])) && !(isset($_GET['success'])))
    header("Location: registration.php");

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
.form{margin-left:auto; margin-right:auto; width:300px;}
.label{float:left; width:120px; text-align:left !important;}
.input{float:left; width:120px;}
#spacer{margin-top:10px;}
br {clear:both;}

</style>
</head>

<body>
<h2>Login</h2>
<form class="form" method="post" action="loginProc.php">
<div class="label">Name: </div><div class="input"><input name="name" type="text" id="name" size="15" maxlength="40" /></div>
<br />
<div class="label">Password:  </div><div class="input"><input name="id" type="password" id="id" size="15" maxlength="15" /></div>
<br />
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
<a id="spacer" href="checkEvents.php">Check Events</a>
</form>
</body>
</html>
