<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
include('../other/bonusMalusLayer.php');
include('../other/aILayer.php');
include('../other/occurencesLayer.php');
require_once('../other/panelHelper.php');

session_start();
$currentAi = $_SESSION['cc']->getAisByName($_SESSION['currentAiName']);

echo startDescriptivePanel($currentAi->name);
getOccurenceHtml($currentAi,"AI");
getAIHtml($currentAi);
echo descriptionLi($currentAi->description);
echo endPanel();
?>
