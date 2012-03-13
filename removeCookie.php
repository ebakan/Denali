<?php
    if(isset($_COOKIE["user"]))
    {
        setcookie("user","",time()-3600);
	    echo "<script type=\"text/javascript\">\n";
	    echo "alert('Logged out.');\n";
        echo "window.location = 'login.php'";
	    echo "</script>";
        
    }else
        echo "no cookie set";
?>
