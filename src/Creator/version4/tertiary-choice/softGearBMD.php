<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
include('../other/bonusMalusLayer.php');
include('../other/armorDegatsLayer.php');
require_once('../other/panelHelper.php');
include('../other/occurencesLayer.php');
session_start();
$currentGear = $_SESSION['cc']->getGearByName($_SESSION['currentSoftName']);

echo startPanel($currentGear->name,"bmdList");
echo descriptionLi($currentGear->description);
getBMHtml($currentGear->bonusMalus,$currentGear->name,'soft');
getADHtml($currentGear);
echo endPanel();
?>
