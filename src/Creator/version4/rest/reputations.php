<?php
require_once '../../../php/EPCharacterCreator.php';
require_once './_headers.php';
session_start();

$result = [
	'cost' => $_SESSION['cc']->getReputationPoints() > 0 ? 1 : 10,
	'reputations' => $_SESSION['cc']->getReputations()
];
echo json_encode($result);
?>
