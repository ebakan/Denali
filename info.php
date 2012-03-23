<?php
require_once("config.php");
if(!isset($_GET['type'])) {
    die();
}
$system = new system();
switch($_GET['type']) {
case 'student':
    if(!(isset($_GET['id']))) {
        die();
    }
    echo json_encode($system->getStudentInfo($_GET['id']));
    break;
case 'registrations':
    if(!(isset($_GET['id']))) {
        die();
    }
    echo json_encode($system->getStudentRegistrations($_GET['id']));
    break;
case 'event':
    if(!(isset($_GET['id']))) {
        die();
    }
    echo json_encode($system->getRegisteredStudents($_GET['id']));
    break;
default:
    break;
}
?>
