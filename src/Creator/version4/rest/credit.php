<?php
require_once '../../../php/EPCharacterCreator.php';
session_start();

$result = array(
	"currentCredit" => $_SESSION['cc']->getCredit()
);
header('Content-type: application/json');
echo json_encode($result);
?>
