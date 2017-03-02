<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
require_once '../../../php/EPAtom.php';
include('../other/bonusMalusLayer.php');
require_once('../other/panelHelper.php');

session_start();

$currentMorphTraits = $_SESSION['cc']->getCurrentMorphTraits($_SESSION['currentMorph']);
$currentTrait = getAtomByName($currentMorphTraits,$_SESSION['currentMorphTraitName']);
if($currentTrait == null){
    $currentTrait =  $_SESSION['cc']->getTraitByName($_SESSION['currentMorphTraitName']);
}

echo startDescriptivePanel($currentTrait->name);
echo descriptionLi($currentTrait->description);
getBMHtml($currentTrait->bonusMalus,$currentTrait->name,'morphTrait');
echo endPanel();
?>
