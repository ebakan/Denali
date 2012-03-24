<?php
    if(isset($_COOKIE["user"]))
    {
        setcookie("user","",time()-3600);
        header("Location: index.php");
    }

        header("Location: index.php");
?>
