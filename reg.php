<?php
require_once("cookie.php");
require_once("config.php");
db_login();
$cookie = new cookie();
/*if($cookie->verifyCookie())
    echo "Cookie verified";
else
    echo "Cookie fail";
    //header("Location: login.html");
*/
//if(!$cookie->verifyCookie())
  //  header("Location: login.php");


$system = new system;;
session_start();
$name = $_SESSION['uname'];
$user = new User($name);
$_SESSION['id'] = $user->GetID();

if(isset($_GET['error']) && $_GET['error'] == 3)
    echo "Event: ".$_GET['event']." is full. Please register again.";

if (getStudentRegistrations($_SESSION['id']))
    ;//reroute to confirmation page




//AFTER SUBMISSION
else if(isset($_POST['Submit']))
    {


    }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Registration</title>

    <style type="text/css">
        body { font-family:Lucida Sans, Arial, Helvetica, Sans-Serif; font-size:13px; margin:20px;}
        #main { position: absolute; width:960px; margin: 0px auto; border:solid 1px #b2b3b5; -moz-border-radius:10px; padding:20px; background-color:#f6f6f6;}
        #header { text-align:center; border-bottom:solid 1px #b2b3b5; margin: 0 0 20px 0; }
        #SignupForm { position: absolute; top: 360px; right: 200px; }
        fieldset { border:none; width:320px;}
        legend { font-size:18px; margin:0px; padding:10px 0px; color:#0033CC; font-weight:bold;}
        label { display:block; margin:15px 0 5px;}
        input[type=text], input[type=password] { width:300px; padding:5px; border:solid 1px #000;}
        .prev, .next { background-color:#0033CC; padding:5px 10px; color:#fff; text-decoration:none;}
        .prev:hover, .next:hover { background-color:#000; text-decoration:none;}
        .prev { float:left;}
        .next { float:right;}
        #steps { float: right; margin-right: 150px; list-style:none; overflow:hidden; padding:0px;}
        #steps li {font-size:24px; float:left; padding:10px; color:#b0b1b3;}
        #steps li span {font-size:11px; display:block;}
        #steps li.current { color:#000;}
        #makeWizard { background-color:#b0232a; color:#fff; padding:5px 10px; text-decoration:none; font-size:18px;}
        #makeWizard:hover { background-color:#000;}

        /*mine*/
        h2#reg { margin-top:50px; text-align: center;}
#mySelect { margin-left:95px; margin-top:150px; width:300px; }
#message_display { margin-top: 30px;  }
#lo { float:right; }
        div#spacer { margin-top: 10px; }
        br {clear:both;}

        .sub { clear:left; }

    </style>
    <script type="text/javascript">

    </script>

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
    <script type="text/javascript" src="formToWizard.js"></script>

<script language="javascript" type="text/javascript">
</script>
</head>

<body>
<a href="removeCookie.php" id="lo">Logout</a>
<h2 id="reg">Registration</h2>

<div class="main" >
        <div id="header">
            <img src="http://www.adobe.com/showcase/casestudies/bellarmine/cover.jpg" alt="Bellarmine Immigration Summit" />
            <span id="info" style="display:none;">You don't have to fill the form, really. Just click on Next and Back to see the demo.</span></p>
        </div>
        <form id="SignupForm" action="">
            <fieldset>
                <legend>FRAME 1</legend>
                <label for="Name">Name</label>
                <input id="Name" type="text" />
                <label for="Email">Email</label>
                <input id="Email" type="text" />
                <label for="Password">Password</label>
                <input id="Password" type="password" />
            </fieldset>
            <fieldset>
                <legend>FRAME 2</legend>
                <label for="CompanyName">Company Name</label>
                <input id="CompanyName" type="text" />
                <label for="Website">Website</label>
                <input id="Website" type="text" />
                <label for="CompanyEmail">CompanyEmail</label>
                <input id="CompanyEmail" type="text" />
            </fieldset>
            <fieldset>
                <legend>FRAME 3</legend>
                <label for="CompanyName">Company Name</label>
                <input id="CompanyName" type="text" />
                <label for="Website">Website</label>
                <input id="Website" type="text" />
                <label for="CompanyEmail">CompanyEmail</label>
                <input id="CompanyEmail" type="text" />
            </fieldset>
            <fieldset>
                <legend>FRAME 4</legend>
                <label for="CompanyName">Company Name</label>
                <input id="CompanyName" type="text" />
                <label for="Website">Website</label>
                <input id="Website" type="text" />
                <label for="CompanyEmail">CompanyEmail</label>
                <input id="CompanyEmail" type="text" />
            </fieldset>
            <p>
                <input id="SaveAccount" type="button" value="Submit form" />
            </p>
        </form>

        <div id="message_display"></div>



</div>

    <select id="mySelect" multiple="multiple"></select>
    <div id="spacer"></div>

    <!--
        Fills in mySelect with events and displays event
        info on hover.
    -->
    <script type="text/javascript">                                         
        //Fills mySelect with options
        $.each(firstTimeSlotEvs, function(val, text) {
            if($('#stepDesc0').hasClass('current')){
            $('#mySelect').append(
                $('<option></option>').val(val).html(text)
            );
            } else { document.write('TEST'); }
        });
*/
        //Displays value of each event (description) when the option
        //is selected

        $("#mySelect").change(function () {
            var str = "";
            $("select#mySelect option:selected").each(function () {
                str += $(this).val(); /* + " " + $(this).attr('id');*/
                //sayHi();
            });
            $("#message_display").text(str);
        })
        .trigger('change');

        //function sayHi() { alert("hi"); }
                    
    </script>


</body>
</html>
