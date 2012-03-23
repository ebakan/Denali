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
    $regs = $system->getStudentRegistrations($_GET['id']);
    if($regs) {
        for($i=1;$i<=4;$i++) {
            if(!isset($regs[$i])) {
                $regs[$i]=array('title' => 'None',
                                'speaker' => 'None',
                                'description' => 'None',
                                'length' => 'None',
                                'location' => 'None',
                                'email' => 'None');
            }

        }
    }
    echo json_encode($regs);
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
