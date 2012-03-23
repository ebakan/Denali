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

/*if(test_login($name, $id))
{
    $hashkey = $name;
    $hashkey .= SALT;

    //Creates cookie
    require_once("cookie.php");
    $cookie = new cookie();
    $cookie->setCookie( $hashkey, false);
    
    //Session data to get username and id
    session_start();
    $_SESSION['uname'] = $name;
    $_SESSION['uid'] = $id;
    header("Location: registration.php");
}else{
    header("Location: login.php?error=0");
}
*/




   /* 

        $ip = $_SERVER['REMOTE_ADDR'];
        $browser = $_SERVER['HTTP_USER_AGENT'];
        $sqlUser = "SELECT id FROM jelogins WHERE username = \"".$name."\"";
        $result = mysql_query($sqlUser);
        $resArr = mysql_fetch_array($result);
        $id = $resArr[0];
       

        $sqlData = "INSERT INTO jelogins_data (userid, date, ip, browser)
            VALUES('".$id."', NOW(), '".$ip."', '".$browser."')";

        $result = mysql_query($sqlData) or die(mysql_error());
        
        //cookies
        require_once("cookie.php");
        $cookie = new cookie();
        $cookie->setCookie( $id, false);

        //check session
        session_start();
        if(isset($_SESSION['url']))
        $location2 = $_SESSION['url'];
        
        echo "<script type=\"text/javascript\">\n";
        echo "alert('Successful!');\n";
        echo "window.location = ('$location2');\n";
        echo "</script>";
    }
}
*/
?>
