<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
include('../other/bonusMalusLayer.php');
require_once('../other/panelHelper.php');

session_start();
$currentAi = $_SESSION['cc']->getAisByName($_SESSION['currentAiName']);

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentAi->name);
$myPanel->addBuySell($currentAi,"AI");
$myPanel->addDescription($currentAi->description);
$myPanel->addAi($currentAi);
echo $myPanel->getHtml();
getBMHtml($currentAi->bonusMalus,$currentAi->name,'ai'); // 'ai' is not a valid value, so any choose options WILL FAIL
echo endPanel();
?>
