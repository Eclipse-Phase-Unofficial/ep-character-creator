<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
include('../other/bonusMalusLayer.php');
require_once('../other/panelHelper.php');
session_start();
$currentGear = $_SESSION['cc']->getGearByName($_SESSION['currentSoftName']);

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentGear->name);
$myPanel->addDescription($currentGear->description);
$myPanel->addArmor($currentGear);
echo $myPanel->getHtml();
getBMHtml($currentGear->bonusMalus,$currentGear->name,'soft');
echo endPanel();
?>
