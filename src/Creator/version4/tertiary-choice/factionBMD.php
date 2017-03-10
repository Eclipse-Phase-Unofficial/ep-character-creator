<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
include('../other/bonusMalusLayer.php');
require_once('../other/panelHelper.php');

session_start();

$currentFaction = $_SESSION['cc']->getCurrentFaction();

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentFaction->name);
$myPanel->addDescription($currentFaction->description);
$myPanel->addTraits($currentFaction->traits);
$myPanel->addRawHtml( getBMHtml($currentFaction->bonusMalus,$currentFaction->name,'faction') );
echo $myPanel->getHtml();
echo endPanel();
?>
