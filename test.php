<?php
print("hi");
require_once("config.php");
$system = new system();
//var_dump($system->getValidEvents());
$data = $system->getValidEvents();

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

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>

<script type="text/javascript">

var data = $.parseJSON(<?php print json_encode(json_encode($data)); ?>);

for(var x in data[0][1].title)
{
    alert(x);
}

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
