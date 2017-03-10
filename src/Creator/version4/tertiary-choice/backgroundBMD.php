<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
include('../other/bonusMalusLayer.php');
require_once('../other/panelHelper.php');

session_start();

$currentBck = $_SESSION['cc']->getCurrentBackground();

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentBck->name);
$myPanel->addDescription($currentBck->description);
$myPanel->addTraits($currentBck->traits);
$myPanel->addRawHtml( getBMHtml($currentBck->bonusMalus,$currentBck->name,'origine') );
echo $myPanel->getHtml();
echo endPanel();
?>
