<?php
require_once '../../../php/EPCharacterCreator.php';
session_start();

header('Content-type: application/json');
$result = [
	'cost' => $_SESSION['cc']->getAptitudePoint() > 0 ? 1 : 10,
	'aptitudes' => $_SESSION['cc']->getAptitudes()
];
echo json_encode($result);
?>
