<?php
print("hi");
require_once("config.php");
$system = new system();
//var_dump($system->getValidEvents());
$data = $system->getValidEvents(212014);

//var_dump($data[1][0]);



//var_dump($system->getValidEventsByTimeslot(2));
//var_dump($system->getAllEvents());
//var_dump($system->getAllEventsByTimeslot(1));
//var_dump($system->getStudentRegistrations(212014));
//var_dump($system->registerStudent(212019, 4, 5, 6, 7, 8));
//var_dump($system->getRegisteredStudents(4));
?>




<html>
<head> <title> TEST </title>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<script type="text/javascript">

var data = $.parseJSON(<?php print json_encode(json_encode($data)); ?>);

//document.writeln(data[1][0].title);

function count(obj) {
  var c = 0;
  for (var key in obj) {
    if (obj.hasOwnProperty(key)) ++c;
  }
  return c;
}

var titles = new Array();
document.writeln(count(data));
document.writeln(count(data[1]));

var i = 0;
for(var x = 1; x <= count(data); x++)
    {
        for(var y = 0; y < count(data[x]); y++)
            {
               titles[i] = data[x][y].title; i++; 
            } 

    }
//console.log(data);

</script>
</head>
<body>
<div class="id"></div>
<div class="title"></div>
<div class="speaker"></div>
<div class="description"></div>
<div class="timeslot"></div>
<div class="length"></div>
<div class="location"></div>
<div class="capacity"></div>
<div class="email"></div>
<div class="count"></div>




</body>



</html>
