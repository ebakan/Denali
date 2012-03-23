<?php
require("config.php");
require("LdapUser.php");


$name = $_POST['name'];
$pass = $_POST['pass'];
db_login();
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


if(!($id = $ldap->auth($name, $pass)))
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

    header("Location: reg.php");

}





?>
