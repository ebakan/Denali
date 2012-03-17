<?php
print("hi");
require_once("config.php");
$system = new system();
var_dump($system->getValidEvents());
var_dump($system->getValidEventsByTimeslot(2));
var_dump($system->getAllEvents());
var_dump($system->getAllEventsByTimeslot(1));
var_dump($system->getStudentRegistrations(212014));
var_dump($system->registerStudent(212019, 4, 5, 6, 7, 8));
var_dump($system->getRegisteredStudents(4));
?>
