<?php
require_once '../../../php/EPCharacterCreator.php';
session_start();

$result = array(
	'currentMoxie' =>  $_SESSION['cc']->getStatByAbbreviation(EPStat::$MOXIE)->getValue()
);

header('Content-type: application/json');
echo json_encode($result);
?>
