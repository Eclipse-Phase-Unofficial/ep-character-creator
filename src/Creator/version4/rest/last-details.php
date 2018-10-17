<?php
require_once '../../../php/EPCharacterCreator.php';
session_start();

$result = array(
	"playerName" => $_SESSION['cc']->character->playerName,
	"characterName" => $_SESSION['cc']->character->charName,
	"realAge" => $_SESSION['cc']->character->realAge,
	"birthGender" => $_SESSION['cc']->character->birthGender,
	"Notes" => $_SESSION['cc']->character->note
);
header('Content-type: application/json');
echo json_encode($result);
?>
