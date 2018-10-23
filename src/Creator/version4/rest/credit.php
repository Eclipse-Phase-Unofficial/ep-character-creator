<?php
require_once '../../../php/EPCharacterCreator.php';
require_once './_headers.php';
session_start();

$result = [
	"currentCredit" => $_SESSION['cc']->getCredit()
];
echo json_encode($result);
?>
