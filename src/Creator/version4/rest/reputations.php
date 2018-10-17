<?php
require_once '../../../php/EPCharacterCreator.php';
session_start();

header('Content-type: application/json');
$result = array(
	'cost' => $_SESSION['cc']->getReputationPoints() > 0 ? 1 : 10,
	'reputations' => $_SESSION['cc']->getReputations()
);
echo json_encode($result);
?>
