<?php
require_once '../../../php/EPCharacterCreator.php';
require_once './_headers.php';
session_start();

$result = [
	'currentMoxie' =>  $_SESSION['cc']->getStatByAbbreviation(EPStat::$MOXIE)->getValue()
];

echo json_encode($result);
?>
