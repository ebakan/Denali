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


$system = new system();
$data = $system->getValidEvents();
session_start();
$name = $_SESSION['uname'];
$id = $_SESSION['uid'];

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

    <style type="text/css">
        body { background-color:#0033CC; font-family:Lucida Sans, Arial, Helvetica, Sans-Serif; font-size:13px; margin:20px;}
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
        #steps { float: right; margin-right: 150px; list-style:none; overflow:hidden; padding:0px;}
        #steps li {font-size:24px; float:left; padding:10px; color:#b0b1b3;}
        #steps li span {font-size:11px; display:block;}
        #steps li.current { color:#000;}
        #makeWizard { background-color:#b0232a; color:#fff; padding:5px 10px; text-decoration:none; font-size:18px;}
        #makeWizard:hover { background-color:#000;}

        /*mine*/
        h2#reg { margin-top:50px; text-align: center;}
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
            <img src="http://www.adobe.com/showcase/casestudies/bellarmine/cover.jpg" alt="Bellarmine Immigration Summit" />
            <span id="info" style="display:none;">You don't have to fill the form, really. Just click on Next and Back to see the demo.</span></p>
        </div>
        <form name="myform" id="SignupForm" action="">
            <fieldset>
                <legend>FRAME 1</legend>
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
                <legend>FRAME 2</legend>
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
                <legend>FRAME 3</legend>
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
                <legend>FRAME 4</legend>
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
    <div id="spacer"></div>
    <div id="footer"></div>

    <!--
        Fills in mySelect with events and displays event
        info on hover.
    -->
    <script type="text/javascript">
    var data = $.parseJSON(<?php print json_encode(json_encode($data)); ?>);
    </script>

    <script type="text/javascript" src="filldata.js"></script>
    <script type="text/javascript">
        /**
         * Checks to see if event is filled up. Puts events in hidden inputs to be submitted.
         */
        $("#SaveAccount").click(function() {
            var evid1 = $('#id1').text();
            var evid2 = $('#id2').text();
            var evid3 = $('#id3').text();
            var evid4 = $('#id4').text();
            var info = "ev1=" + evid1 + "&ev2="+evid2+"&ev3="+evid3+"&ev4="+evid4;
           $.get(
               "ev.php",
               info,
               function(data) {
                   var errs = data.split(',');
                   if(data != "") {
                       var first = errs[0]-1;

                       alert("Sorry! One or more of the events ran out of space. Please choose another.");
                       for(var x = 0; x < errs.length; x ++)
                       {
                            $("#step" + (errs[x]-1) + " fieldset div").html('');
                       }

                        $(".next").hide();
                        $(submmitButtonName).hide();

                       $("#step0, #step1, #step2, #step3").hide();
                       $("#step" + first).show();
                       selectStep(first);
                   } else {
                       $.get(
                            "regstuds.php",
                          //TEST STILL  info + "&id=" + <?php echo $_SESSION['uid'] ?>,
                          info,
                            function(data) {
                                alert(data);

                            },
                            "text");
                   }

               },
               "text"
           );
           document.myform.eid1.value = evid1; 
           document.myform.eid2.value = evid2; 
           document.myform.eid3.value = evid3; 
           document.myform.eid4.value = evid4;; 
        
        });

    </script>



</body>
</html>
