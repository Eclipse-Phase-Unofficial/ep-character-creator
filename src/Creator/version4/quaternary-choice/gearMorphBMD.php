<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
require_once '../../../php/EPAtom.php';
include('../other/bonusMalusLayer.php');
require_once('../other/panelHelper.php');

session_start();

// $hint = "--dubug:morph";
$morphGears = $_SESSION['cc']->getGearForMorphName($_SESSION['currentMorph']);
$currentGear = getAtomByName($morphGears,$_SESSION['currentMorphGearName']);
if($currentGear == null){
    $currentGear =  $_SESSION['cc']->getGearByName($_SESSION['currentMorphGearName']);
    // $hint = "--debug:general";
}

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentGear->name);
$myPanel->addBuySell($currentGear,"MORPH");
$myPanel->addDescription($currentGear->description);
$myPanel->addArmor($currentGear);
$myPanel->addRawHtml( getBMHtml($currentGear->bonusMalus,$currentGear->name,'morphGear') );
echo $myPanel->getHtml();
echo endPanel();
?>
