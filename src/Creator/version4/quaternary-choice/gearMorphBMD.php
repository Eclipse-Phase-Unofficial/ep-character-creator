<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
require_once '../../../php/EPAtom.php';
include('../other/bonusMalusLayer.php');
include('../other/armorDegatsLayer.php');
require_once('../other/panelHelper.php');
include('../other/occurencesLayer.php');

session_start();

// $hint = "--dubug:morph";
$morphGears = $_SESSION['cc']->getGearForMorphName($_SESSION['currentMorph']);
$currentGear = getAtomByName($morphGears,$_SESSION['currentMorphGearName']);
if($currentGear == null){
    $currentGear =  $_SESSION['cc']->getGearByName($_SESSION['currentMorphGearName']);
    // $hint = "--debug:general";
}


echo startPanel($currentGear->name,"bmdList");
getOccurenceHtml($currentGear,"MORPH");
echo descriptionLi($currentGear->description);
getBMHtml($currentGear->bonusMalus,$currentGear->name,'morphGear');
getADHtml($currentGear);
echo endPanel();
?>
