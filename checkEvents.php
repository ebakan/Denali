<?php
require_once("config.php");

db_login();
$system = new system();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Login Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src='jquery.hint-with-password.js' type='text/javascript'></script>
<script type="text/javascript" charset="utf-8">
    $(function(){ 
        $('input[title!=""]').hint();
    });
    $(document).ready(function() {
        $("#peoplesubmit").click(function(e) {
            var val = $("#peopleinput").val();
            $("#data").empty();
            $.get("info.php","type=student&id="+val, function(data) {
                data = $.parseJSON(data);
                if(!data) {
                    $("#data").append("<p>Uh oh, that ID number doesn't exist.</p>");
                } else {
                    $("#data").append("<p>Student ID: "+data.BCPStudID+"</p>"+
                                      "<p>Name: "+data.StudFirstName+" "+data.StudLastName+"</p>");
                    $.get("info.php","type=registrations&id="+val, function(data) {
                        data = $.parseJSON(data);
                        if(!data || data.length<4) {
                            $("#data").append("<p>Uh oh, that student hasn't registered yet.</p>");
                        } else {
                            $("#data").append('<div class="ev evtop"><h2>First Event:</h2> \
                                                   <div class="label">Title </div><div id="title51" style="text-align:center">'+data[1].title+'</div><br /> \
                                                   <div class="label">Speaker </div><div id="speaker51" style="text-align:center">'+data[1].speaker+'</div><br /> \
                                                   <div class="label">Description </div><div id="description51" style="text-align:center">'+data[1].description+'</div><br /> \
                                                   <div class="label">Length (Mins)</div><div id="description51" style="text-align:center">'+data[1].length+'</div><br /> \
                                                   <div class="label">Room </div><div id="location51" style="text-align:center">'+data[1].location+'</div><br /> \
                                                   <div class="label">Email </div><div id="location51" style="text-align:center">'+data[1].email+'</div><br /> \
                                               </div> \
                                               <div class="ev evtop"><h2>Second Event:</h2> \
                                                   <div class="label">Title </div><div id="title52" style="text-align:center">'+data[2].title+'</div><br /> \
                                                   <div class="label">Speaker </div><div id="speaker52" style="text-align:center">'+data[2].speaker+'</div><br /> \
                                                   <div class="label">Description </div><div id="description52" style="text-align:center">'+data[2].description+'</div><br /> \
                                                   <div class="label">Length (Mins)</div><div id="description52" style="text-align:center">'+data[2].length+'</div><br /> \
                                                   <div class="label">Room </div><div id="location52" style="text-align:center">'+data[2].location+'</div><br /> \
                                                   <div class="label">Email </div><div id="location52" style="text-align:center">'+data[2].email+'</div><br /> \
                                               </div> \
                                               <div class="ev evtop"><h2>Third Event:</h2> \
                                                   <div class="label">Title </div><div id="title53" style="text-align:center">'+data[3].title+'</div><br /> \
                                                   <div class="label">Speaker </div><div id="speaker53" style="text-align:center">'+data[3].speaker+'</div><br /> \
                                                   <div class="label">Description </div><div id="description53" style="text-align:center">'+data[3].description+'</div><br /> \
                                                   <div class="label">Length (Mins)</div><div id="description53" style="text-align:center">'+data[3].length+'</div><br /> \
                                                   <div class="label">Room </div><div id="location53" style="text-align:center">'+data[3].location+'</div><br /> \
                                                   <div class="label">Email </div><div id="location53" style="text-align:center">'+data[3].email+'</div><br /> \
                                               </div> \
                                               <div class="ev"><h2>Fourth Event:</h2> \
                                                   <div class="label">Title </div><div id="title54" style="text-align:center">'+data[4].title+'</div><br /> \
                                                   <div class="label">Speaker </div><div id="speaker54" style="text-align:center">'+data[4].speaker+'</div><br /> \
                                                   <div class="label">Description </div><div id="description54" style="text-align:center">'+data[4].description+'</div><br /> \
                                                   <div class="label">Length (Mins)</div><div id="description54" style="text-align:center">'+data[4].length+'</div><br /> \
                                                   <div class="label">Room </div><div id="location54" style="text-align:center">'+data[4].location+'</div><br /> \
                                                   <div class="label">Email </div><div id="location54" style="text-align:center">'+data[4].email+'</div><br /> \
                                               </div> \
                                               </fieldset>'
                        );
                        }
                    });
                }
        });
    });
        $("#peopleinput").keypress(function(e) {
            if(e.which == 13) {
                $("#peoplesubmit").click();
            }
        });
        $("#eventselect").change(function() {
            var val = $("#eventselect").val();
            $("#data").empty();
            $.get("info.php","type=event&id="+val, function(data) {
                data = $.parseJSON(data);
                var num = data.length;
                if(data<1) {
                    $("#data").append("<p>No signups for this event yet!</p>");
                } else {
                   outVal="<p>"+num+" Total Signups</p><table id='datalist'><tr><th>Student ID</th><th>Name</th></tr>";
                    $(data).each( function(i) {
                        var d = data[i];
                        outVal+="<tr><td>"+d.BCPStudID+"</td><td>"+d.StudFirstName+" "+d.StudLastName+"</td></tr>";
                    });
                   outVal+="</table>";
                    $("#data").append(outVal);
                }
            });
        });
    });
</script>
<link rel="stylesheet" type="text/css" href="style.css" />
<style type="text/css">
html{text-align:center;}
#eventselect {
    width: 300px;
}
</style>
</head>

<body>
<a href="removeCookie.php" id="lo">Logout</a>
<h2 id="reg">Immigration Summit Registration</h2>
<div id="header">
<img src="bell.png" alt="Bellarmine Immigration Summit" style="height:150px; width:150px;"/>
<span id="info" style="display:none;">You don't have to fill the form, really. Just click on Next and Back to see the demo.</span></p>
</div>
<div id="review">
<h2>Check out who's going to an event!</h2>
<select id="eventselect">
<?php
$events = $system->getAllEvents();
for($i=1; $i<=4; $i++) {
    foreach($events[$i] as $event) {
        echo "<option value='".$event['id']."'>Sess. $i: ".$event['title']."</option>\n";
    }
}
?>
</select>
<h2>Check out what events people are going to!</h2>
<input name="people" type="text" id="peopleinput" title="Student ID Number">
<input type="submit" id="peoplesubmit" value="Submit" />
<div id="data">
</div>
</div>

</body>
</html>
