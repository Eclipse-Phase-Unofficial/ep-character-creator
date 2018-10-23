<?php
require_once '../../../php/EPCharacterCreator.php';
require_once './_headers.php';
session_start();

$result = [
	"playerName" => $_SESSION['cc']->character->playerName,
	"characterName" => $_SESSION['cc']->character->charName,
	"realAge" => $_SESSION['cc']->character->realAge,
	"birthGender" => $_SESSION['cc']->character->birthGender,
	"Notes" => $_SESSION['cc']->character->note
];
echo json_encode($result);
?>
