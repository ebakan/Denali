<?php
    if(isset($_COOKIE["user"]))
    {
        setcookie("user","",time()-3600);
        header("Location: index2.php");
    }

        header("Location: index2.php");
?>
