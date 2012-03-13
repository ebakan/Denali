<?php
require_once("config.php");

db_login();
$system = new system();
$system->getInstance();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Login Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css">
html{text-align:center;}
.form{margin-left:auto; margin-right:auto; width:420px; margin-top:100px;}
.label{float:left; width:200px; text-align:left !important;}
.input{float:left; width:200px;}
#evLu{width: 400px; margin-top:40px;}
#spacer{margin-top:10px;}
#message{margin-top:20px;}
#mySelect { margin-left:auto; margin-top:10px; width:300px; }
#log {float:left;}
br {clear:both;}

</style>

<script type="text/javascript" src="jquery.js"></script>          
</head>

<body>
<a id="log" href="login.php">Back to Login</a>
<h2 id="message"> Look Up </h2>
<form class="form" method="post" action="#">
<div class ="label">Look up events by studentID: </div><div class="input"><input name="stid" type="text" id="stid" size="15" maxlength="30" /><div>

<input type="submit" id="spacer" name="Submit" value="Submit" />
</form>
<div id="message">
<?php
if(isset($_POST['Submit']))
    {
        $success = true;
        $studentid = $_POST['stid'];
        $studentid = htmlentities($studentid);
        $studentid = mysql_real_escape_string($studentid);

       $arr = $system->displayByID($studentid); 
       if(!($arr))
           { echo "No Results Found."; $success = false; }

            $evs = explode("|", $arr);

        if($success && strcmp($evs[0], "") != 0){
            //get name of student
            $namesql = "SELECT * from people WHERE studentID=".$studentid;
            if(!($result = mysql_query($namesql)))
                header("Location: login.php?error=2");
            $namearr = mysql_fetch_array($result);
            $studname = $namearr['name'];

            //get name of events 
            echo "Events for ".$studname.": <br />";
            for($i = 0; $i < sizeof($evs); $i++){
                $evsql = "SELECT * from events WHERE id=".$evs[$i];
                if(!($result = mysql_query($evsql)))
                    header("Location: login.php?error=2");
                while($row = mysql_fetch_array($result))
                    echo $row['name']."<br />";
            }      
        }else if($success)
            echo "No Results Found.";
    }
?>
</div>


<div class ="label" id="evLu">Look up students in each event: </div>
<select id="mySelect" multiple="multiple"></select>
<div id="message_display"></div>

    <script type="text/javascript">                                         

//Gets users as Value and name of event as Text in array
var myOptions = {

    <?php
        db_login();
        $sql = "SELECT * from events";
        if(!($result = mysql_query($sql)))
            header("Location: login.php?error=2");
        
        
        while($row = mysql_fetch_array($result))
        {
            $ppl = $system->displayByEvent($row['id']);
            $ppls = explode("|", $ppl);
            //echo "'test' : '".$row['name']."', ";
           if(sizeof($ppls) > 0)
            {
                echo "'";    
                for($i = 0; $i < sizeof($ppls); $i++)
                {
                    if($i === sizeof($ppls)-1)
                        echo $ppls[$i]."' : '".$row['name']."', ";
                    else
                        echo $ppls[$i].", ";

                }
            }

        }

    ?>

};

//Fills mySelect with options
$.each(myOptions, function(val, text) {
    $('#mySelect').append(
        $('<option></option>').val(val).html(text)
    );
});

//Displays value of each event (people) when the option
//is selected
$("#mySelect").change(function () {
    var str = "";
    $("select#mySelect option:selected").each(function () {
        str += $(this).val();
    });
    $("#message_display").text(str);
})
.trigger('change');
            
</script>
</body>
</html>
