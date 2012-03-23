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

    
    $events = $system->getStudentRegistrations($id);

    if($events) {
        header("Location: reviewEvents.php");
    }

    if(!isset($_SESSION['uid']))
        header("Location: login.php");

if(isset($_GET['error']) && $_GET['error'] == 3)
    echo "Event: ".$_GET['event']." is full. Please register again.";


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Registration</title>
    <link rel="stylesheet" type="text/css" href="style.css" />

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
        <div id="info">
        <p> Below you will find various presentations for the justice summit that you can sign up for. Every class has one required event(which you will choose), 
        and if you know you will be absent on these days, please choose Planned Absence at the bottom of the list. Go bells!
        </p>
        </div>
        <br />
        <form name="myform" id="SignupForm" action="">
            <fieldset>
                <legend>March 30, 10:20 – 11:10 AM</legend><br />
                <div class="label">Title </div><div id="title1" style="text-align:center"></div><br />
                <div class="label">Speaker </div><div id="speaker1" style="text-align:center"></div><br />
                <div class="label">Description </div><div id="description1" style="text-align:center"></div><br />
                <div class="label">Length(Mins) </div><div id="length1" style="text-align:center"></div><br />
                <div class="label">Room </div><div id="location1" style="text-align:center"></div><br />
                <div class="label">Email </div><div id="email1" style="text-align:center"></div><br />
                <div class="label">Spots Available </div><div id="spots1"  style="text-align:center"></div><br />
                <div class="hidden" id="id1" style="visibility:hidden"></div>
                <div class="hidden" id="timeslot1" style="visibility:hidden;"></div>
                <div class="hidden" id="capacity1" style="visibility: hidden;"></div>
                <div class="hidden" id="count1" style="visibility:hidden;"></div>
            </fieldset>
            <fieldset>
                <legend>March 30, 11:20 – 12:10 AM</legend><br />
                <div class="label">Title </div><div id="title2" style="text-align:center"></div><br />
                <div class="label">Speaker </div><div id="speaker2" style="text-align:center"></div><br />
                <div class="label">Description </div><div id="description2" style="text-align:center"></div><br />
                <div class="label">Length(Mins) </div><div id="length2" style="text-align:center"></div><br />
                <div class="label">Room </div><div id="location2" style="text-align:center"></div><br />
                <div class="label">Email </div><div id="email2" style="text-align:center"></div><br />
                <div class="label">Spots Available </div><div id="spots2"  style="text-align:center"></div><br />
                <div class="hidden" id="id2" style="visibility:hidden"></div>
                <div class="hidden" id="timeslot2" style="visibility:hidden;"></div>
                <div class="hidden" id="capacity2" style="visibility: hidden;"></div>
                <div class="hidden" id="count2" style="visibility:hidden;"></div>

            </fieldset>
            <fieldset>
                <legend>April 2, 10:20 – 11:10 AM</legend><br />
                <div class="label">Title </div><div id="title3" style="text-align:center"></div><br />
                <div class="label">Speaker </div><div id="speaker3" style="text-align:center"></div><br />
                <div class="label">Description </div><div id="description3" style="text-align:center"></div><br />
                <div class="label">Length(Mins) </div><div id="length3" style="text-align:center"></div><br />
                <div class="label">Room </div><div id="location3" style="text-align:center"></div><br />
                <div class="label">Email </div><div id="email3" style="text-align:center"></div><br />
                <div class="label">Spots Available </div><div id="spots3"  style="text-align:center"></div><br />
                <div class="hidden" id="id3" style="visibility:hidden"></div>
                <div class="hidden" id="timeslot3" style="visibility:hidden;"></div>
                <div class="hidden" id="capacity3" style="visibility: hidden;"></div>
                <div class="hidden" id="count3" style="visibility:hidden;"></div>
            </fieldset>
            <fieldset>
                <legend>April 2, 11:20 – 12:10 AM</legend><br />
                <div class="label">Title </div><div id="title4" style="text-align:center"></div><br />
                <div class="label">Speaker </div><div id="speaker4" style="text-align:center"></div><br />
                <div class="label">Description </div><div id="description4" style="text-align:center"></div><br />
                <div class="label">Length(Mins) </div><div id="length4" style="text-align:center"></div><br />
                <div class="label">Room </div><div id="location4" style="text-align:center"></div><br />
                <div class="label">Email </div><div id="email4" style="text-align:center"></div><br />
                <div class="label">Spots Available </div><div id="spots4"  style="text-align:center"></div><br />
                <div class="hidden" id="id4" style="visibility:hidden"></div>
                <div class="hidden" id="timeslot4" style="visibility:hidden;"></div>
                <div class="hidden" id="capacity4" style="visibility: hidden;"></div>
                <div class="hidden" id="count4" style="visibility:hidden;"></div>
            </fieldset>
            <fieldset>
                <div class="ev"> First Event</div>
                <br /> 
                <div class="labelc">
                    <div class="label">Title </div><div id="title51" style="text-align:center"></div><br />
                    <div class="label">Speaker </div><div id="speaker51" style="text-align:center"></div><br />
                    <div class="label">Room </div><div id="location51" style="text-align:center"></div><br />
                </div>
                <br />
                <div class="ev"> Second Event </div> 
                <br /> 
                <div class="labelc">
                    <div class="label">Title </div><div id="title52" style="text-align:center"></div><br />
                    <div class="label">Speaker </div><div id="speaker52" style="text-align:center"></div><br />
                    <div class="label">Room </div><div id="location52" style="text-align:center"></div><br />
                </div>
                <br />
                <div class="ev"> Third Event </div>
                <br /> 
                <div class="labelc">
                    <div class="label">Title </div><div id="title53" style="text-align:center"></div><br />
                    <div class="label">Speaker </div><div id="speaker53" style="text-align:center"></div><br />
                    <div class="label">Room </div><div id="location53" style="text-align:center"></div><br />
                </div>
                <br />
                <div class="ev"> Fourth Event </div>
                <br /> <br />
                <div class="labelc">
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
