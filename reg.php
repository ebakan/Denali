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
        //$eid1 = 

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
<h2 id="reg">Registration</h2>

<div class="main" >
        <div id="header">
            <img src="http://www.adobe.com/showcase/casestudies/bellarmine/cover.jpg" alt="Bellarmine Immigration Summit" />
            <span id="info" style="display:none;">You don't have to fill the form, really. Just click on Next and Back to see the demo.</span></p>
        </div>
        <form name="myform" id="SignupForm" action="">
            <fieldset>
                <legend>FRAME 1</legend>
                <div id="id1"></div>
                <div id="title1"></div>
                <div id="speaker1"></div>
                <div id="description1"></div>
                <div id="timeslot1"></div>
                <div id="length1"></div>
                <div id="location1"></div>
                <div id="capacity1"></div>
                <div id="email1"></div>
                <div id="count1"></div>
            </fieldset>
            <fieldset>
                <legend>FRAME 2</legend>
                <div id="id2"></div>
                <div id="title2"></div>
                <div id="speaker2"></div>
                <div id="description2"></div>
                <div id="timeslot2"></div>
                <div id="length2"></div>
                <div id="location2"></div>
                <div id="capacity2"></div>
                <div id="email2"></div>
                <div id="count2"></div>
            </fieldset>
            <fieldset>
                <legend>FRAME 3</legend>
                <div id="id3"></div>
                <div id="title3"></div>
                <div id="speaker3"></div>
                <div id="description3"></div>
                <div id="timeslot3"></div>
                <div id="length3"></div>
                <div id="location3"></div>
                <div id="capacity3"></div>
                <div id="email3"></div>
                <div id="count3"></div>
            </fieldset>
            <fieldset>
                <legend>FRAME 4</legend>
                <div id="id4"></div>
                <div id="title4"></div>
                <div id="speaker4"></div>
                <div id="description4"></div>
                <div id="timeslot4"></div>
                <div id="length4"></div>
                <div id="location4"></div>
                <div id="capacity4"></div>
                <div id="email4"></div>
                <div id="count4"></div>
            </fieldset>
            <input type="hidden" name="eid1" />
            <input type="hidden" name="eid2" />
            <input type="hidden" name="eid3" />
            <input type="hidden" name="eid4" />
            <p>
                <input name="Submit" id="SaveAccount" type="button" value="Submit form" />
            </p>
        </form>

        <div id="message_display"></div>



</div>

    <select id="mySelect" size="15"></select>
    <div id="spacer"></div>

    <!--
        Fills in mySelect with events and displays event
        info on hover.
    -->
    <script type="text/javascript">
    var data = $.parseJSON(<?php print json_encode(json_encode($data)); ?>);

 //   document.writeln(data[1][0].title);

    function count(obj) {
      var c = 0;
      for (var key in obj) {
        if (obj.hasOwnProperty(key)) ++c;
      }
      return c;
    }

    function getTitles(timeslot)
    {   
        var titles = new Object;
        for(var x = 0; x < count(data[timeslot]); x++)
        {
            var key = data[timeslot][x].id
            titles[key] = data[timeslot][x].title;
        }

        return titles;
    }

    var titles = new Object();
    var ids = new Object();
    var speakers = new Object();
    var descriptions = new Object();
    var timeslots = new Object();
    var lengths = new Object();
    var locations = new Object();
    var capacities = new Object();
    var emails = new Object();
    var counts = new Object();
    
    //fill data
    var i = 0;
    for(var x = 1; x <= count(data); x++)
        {
            for(var y = 0; y < count(data[x]); y++)
                {
                   key = data[x][y].id;
                   titles[key] = data[x][y].title;  
                   ids[key] = data[x][y].id;  
                   speakers[key] = data[x][y].speaker;  
                   descriptions[key] = data[x][y].description;  
                   timeslots[key] = data[x][y].timeslot;  
                   lengths[key] = data[x][y].length;  
                   locations[key] = data[x][y].location;  
                   titles[capacities] = data[x][y].capacity;  
                   emails[key] = data[x][y].email;  
                   counts[key] = data[x][y].count;  
                    
                } 

        }
    
    var firstTimeSlotTitles = getTitles(1);
    var secondTimeSlotTitles = getTitles(2);
    var thirdTimeSlotTitles = getTitles(3);
    var fourthTimeSlotTitles = getTitles(4);
    //for(var x = 0; x < firstTimeSlotTitles.length; x++) document.writeln(firstTimeSlotTitles[x]);

    </script>

    <script type="text/javascript">                                         
       /* $.each(titles, function(key, value) {
            $('#mySelect').append(
                $('<option></option>').text(value)
            );
        });
*/

        $("#mySelect").change(function () {
            var str = "";
            $("select#mySelect option:selected").each(function () {
                str += $(this).attr("id"); /* + " " + $(this).attr('id');*/
                fillData($(this).attr("id"));
                //sayHi();
            });
            $("#message_display").text(str);
        })
        .trigger('change');

        function fillData(id) { 
           
            if($('#stepDesc0').hasClass('current')){
               $("#id1").html(ids[id]);
               $("#title1").html(titles[id]);
               $("#speaker1").html(speakers[id]);
               $("#description1").html(descriptions[id]);
               $("#timeslot1").html(timeslots[id]);
               $("#length1").html(lengths[id]);
               $("#location1").html(locations[id]);
               $("#capacity1").html(capacities[id]);
               $("#email1").html(emails[id]);
               $("#count1").html(counts[id]);
                 
            } else if($('#stepDesc1').hasClass('current')){
               $("#id2").html(ids[id]);
               $("#title2").html(titles[id]);
               $("#speaker2").html(speakers[id]);
               $("#description2").html(descriptions[id]);
               $("#timeslot2").html(timeslots[id]);
               $("#length2").html(lengths[id]);
               $("#location2").html(locations[id]);
               $("#capacity2").html(capacities[id]);
               $("#email2").html(emails[id]);
               $("#count2").html(counts[id]);
            } else if($('#stepDesc2').hasClass('current')){
               $("#id3").html(ids[id]);
               $("#title3").html(titles[id]);
               $("#speaker3").html(speakers[id]);
               $("#description3").html(descriptions[id]);
               $("#timeslot3").html(timeslots[id]);
               $("#length3").html(lengths[id]);
               $("#location3").html(locations[id]);
               $("#capacity3").html(capacities[id]);
               $("#email3").html(emails[id]);
               $("#count3").html(counts[id]);
            } else if($('#stepDesc3').hasClass('current')){
               $("#id4").html(ids[id]);
               $("#title4").html(titles[id]);
               $("#speaker4").html(speakers[id]);
               $("#description4").html(descriptions[id]);
               $("#timeslot4").html(timeslots[id]);
               $("#length4").html(lengths[id]);
               $("#location4").html(locations[id]);
               $("#capacity4").html(capacities[id]);
               $("#email4").html(emails[id]);
               $("#count4").html(counts[id]);
            }
        }
        


    </script>


</body>
</html>
