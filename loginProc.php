<?php
require("config.php");


$name = $_POST['name'];
$id = $_POST['id'];
db_login();
$system = new system();

//Screens html/sql injections
$name = htmlentities($name);
$name = mysql_real_escape_string($name);
$id = htmlentities($id);
$id = mysql_real_escape_string($id);

$ldap = new LdapUser();

echo $ldap->auth($name, $id);

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
