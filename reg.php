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
if(!$cookie->verifyCookie())
    header("Location: login.php");


$system = new system();
session_start();
$name = $_SESSION['uname'];
$pass = $_SESSION['upass'];
$id = $_SESSION['uid'];

$data = $system->getValidEvents($id);

if(isset($_GET['error']) && $_GET['error'] == 3)
    echo "Event: ".$_GET['event']." is full. Please register again.";

if ($system->getStudentRegistrations($id))
    ;//reroute to confirmation page




//AFTER SUBMISSION
else if(isset($_POST['Submit']))
    {
        $eid1 = $_POST['eid1']; 
        $eid2 = $_POST['eid2']; 
        $eid3 = $_POST['eid3']; 
        $eid4 = $_POST['eid4']; 
        echo $eid1." ".$eid2." ".$eid3." ".$eid4;




    }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Registration</title>
<<<<<<< Updated upstream
    <link rel="stylesheet" type="text/css" href="style.css" />
=======

    <style type="text/css">
        body {
        /*background-color:#0033CC; */
        font-family:Lucida Sans, Arial, Helvetica, Sans-Serif; 
        font-size:13px; 
        margin:20px;
        background-image:url('vertical_cloth.png');
        background-repeat:repeat;
        }
        #main { position: absolute; width:960px; margin: 0px auto; border:solid 1px #b2b3b5; -moz-border-radius:10px; padding:20px; background-color:#f6f6f6;}
        #header { text-align:center; border-bottom:solid 1px #b2b3b5; margin: 0 0 20px 0; }
        #header img{ margin-right: 51px; }
        #SignupForm { position: absolute; top: 360px; right: 200px; }
        fieldset { border:none; width:320px;}
        legend { font-size:18px; margin:0px; padding:10px 0px; color:#0033CC; font-weight:bold;}
        label { display:block; margin:15px 0 5px;}
        input[type=text], input[type=password] { width:300px; padding:5px; border:solid 1px #000;}
        .prev, .next { background-color:#0033CC; padding:5px 10px; color:#fff; text-decoration:none;}
        .prev:hover, .next:hover { background-color:#000; text-decoration:none;}
        .prev { float:left;}
        .next { float:right;}
        #steps { float: right; margin-right: 20%; list-style:none; overflow:hidden; padding:0px;}
        #steps li {font-size:24px; float:left; padding:10px; color:#b0b1b3;}
        #steps li span {font-size:11px; display:block;}
        #steps li.current { color:#0033CC;}
        #makeWizard { background-color:#b0232a; color:#fff; padding:5px 10px; text-decoration:none; font-size:18px;}
        #makeWizard:hover { background-color:#000;}

        /*mine*/
        h2#reg { color: white; margin-top:14px; text-align: center;}
#mySelect { margin-left:95px; margin-top:150px; width:300px; border-style:solid; }
        form { border-style:solid; background-color: white; }
#message_display { margin-top: 30px;  }
#lo { float:right; }
        #footer { height: 250px; }
        div#spacer { margin-top: 10px; }
        br {clear:both;}
        .label { font-weight: bold; text-decoration:underline; text-align: center; }

        .sub { clear:left; }

    </style>

>>>>>>> Stashed changes
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="formToWizard.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $("#SignupForm").formToWizard({ submitButton: 'SaveAccount' })
        });
    </script>
</head>

<body>
<a href="removeCookie.php" id="lo">Logout</a>
<h2 id="reg">Immigration Summit Registration</h2>

<div class="main" >
        <div id="header">
            <img src="bell.png" alt="Bellarmine Immigration Summit" style="height:150px; width:150px;"/>
            <span id="info" style="display:none;">You don't have to fill the form, really. Just click on Next and Back to see the demo.</span></p>
        </div>
        <form name="myform" id="SignupForm" action="">
            <fieldset>
                <div id="id1" style="visibility:hidden"></div>
                <div class="label">Title </div><div id="title1" style="text-align:center"></div><br />
                <div class="label">Speaker </div><div id="speaker1" style="text-align:center"></div><br />
                <div class="label">Description </div><div id="description1" style="text-align:center"></div><br />
                <div id="timeslot1" style="visibility:hidden"></div>
                <div class="label">Length(Mins) </div><div id="length1" style="text-align:center"></div><br />
                <div class="label">Room </div><div id="location1" style="text-align:center"></div><br />
                <div class="label">Capacity </div><div id="capacity1" style="text-align:center"></div><br />
                <div class="label">Email </div><div id="email1" style="text-align:center"></div><br />
                <div id="count1" style="visibility:hidden"></div>
            </fieldset>
            <fieldset>
                <div id="id1" style="visibility:hidden"></div>
                <div class="label">Title </div><div id="title2" style="text-align:center"></div><br />
                <div class="label">Speaker </div><div id="speaker2" style="text-align:center"></div><br />
                <div class="label">Description </div><div id="description2" style="text-align:center"></div><br />
                <div id="timeslot2" style="visibility:hidden"></div>
                <div class="label">Length(Mins) </div><div id="length2" style="text-align:center"></div><br />
                <div class="label">Room </div><div id="location2" style="text-align:center"></div><br />
                <div class="label">Capacity </div><div id="capacity2" style="text-align:center"></div><br />
                <div class="label">Email </div><div id="email2" style="text-align:center"></div><br />
                <div id="count2" style="visibility:hidden"></div>
            </fieldset>
            <fieldset>
                <div id="id3" style="visibility:hidden"></div>
                <div class="label">Title </div><div id="title3" style="text-align:center"></div><br />
                <div class="label">Speaker </div><div id="speaker3" style="text-align:center"></div><br />
                <div class="label">Description </div><div id="description3" style="text-align:center"></div><br />
                <div id="timeslot3" style="visibility:hidden"></div>
                <div class="label">Length(Mins) </div><div id="length3" style="text-align:center"></div><br />
                <div class="label">Room </div><div id="location3" style="text-align:center"></div><br />
                <div class="label">Capacity </div><div id="capacity3" style="text-align:center"></div><br />
                <div class="label">Email </div><div id="email3" style="text-align:center"></div><br />
                <div id="count3" style="visibility:hidden"></div>
            </fieldset>
            <fieldset>
                <div id="id4" style="visibility:hidden"></div>
                <div class="label">Title </div><div id="title4" style="text-align:center"></div><br />
                <div class="label">Speaker </div><div id="speaker4" style="text-align:center"></div><br />
                <div class="label">Description </div><div id="description4" style="text-align:center"></div><br />
                <div id="timeslot4" style="visibility:hidden"></div>
                <div class="label">Length(Mins) </div><div id="length4" style="text-align:center"></div><br />
                <div class="label">Room </div><div id="location4" style="text-align:center"></div><br />
                <div class="label">Capacity </div><div id="capacity4" style="text-align:center"></div><br />
                <div class="label">Email </div><div id="email4" style="text-align:center"></div><br />
                <div id="count4" style="visibility:hidden"></div>
            </fieldset>
            <fieldset>
                <div class="ev"> First Event:<br /> <br />
                    <div class="label">Title </div><div id="title51" style="text-align:center"></div><br />
                    <div class="label">Speaker </div><div id="speaker51" style="text-align:center"></div><br />
                    <div class="label">Room </div><div id="location51" style="text-align:center"></div><br />
                </div>
                <div class="ev"> Second Event:<br /> <br />
                    <div class="label">Title </div><div id="title52" style="text-align:center"></div><br />
                    <div class="label">Speaker </div><div id="speaker52" style="text-align:center"></div><br />
                    <div class="label">Room </div><div id="location52" style="text-align:center"></div><br />
                </div>
                <div class="ev"> Third Event: <br /><br />
                    <div class="label">Title </div><div id="title53" style="text-align:center"></div><br />
                    <div class="label">Speaker </div><div id="speaker53" style="text-align:center"></div><br />
                    <div class="label">Room </div><div id="location53" style="text-align:center"></div><br />
                </div>
                <div class="ev"> Fourth Event: <br /><br />
                    <div class="label">Title </div><div id="title54" style="text-align:center"></div><br />
                    <div class="label">Speaker </div><div id="speaker54" style="text-align:center"></div><br />
                    <div class="label">Room </div><div id="location54" style="text-align:center"></div><br />
                </div>

            </fieldset>
            <input type="hidden" name="eid1" />
            <input type="hidden" name="eid2" />
            <input type="hidden" name="eid3" />
            <input type="hidden" name="eid4" />
            <p>
                <input name="Submit" id="SaveAccount" type="button" value="Go!" />
            </p>
        </form>

        <div id="message_display"></div>



</div>

    <select id="mySelect" size="15"></select>
   <!-- <div id="spacer"></div> -->
    <div id="footer"></div>

    <!--
        Fills in mySelect with events and displays event
        info on hover.
    -->
    <script type="text/javascript">
    var data = $.parseJSON(<?php print json_encode(json_encode($data)); ?>);
    var uid = <?php echo $_SESSION['uid']; ?>;
    </script>

    <script type="text/javascript" src="filldata.js"></script>
    <script type="text/javascript">

    </script>



</body>
</html>
