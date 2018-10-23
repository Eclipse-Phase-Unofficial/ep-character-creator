<?php
require_once '../../../php/EPCharacterCreator.php';
require_once './_headers.php';
session_start();

$result = [
	'cost' => $_SESSION['cc']->getAptitudePoint() > 0 ? 1 : 10,
	'aptitudes' => $_SESSION['cc']->getAptitudes()
];
echo json_encode($result);
?>
