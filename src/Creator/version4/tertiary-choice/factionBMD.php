<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
include('../other/bonusMalusLayer.php');
require_once('../other/traitLayer.php');
require_once('../other/panelHelper.php');

session_start();

$currentFaction = $_SESSION['cc']->getCurrentFaction();

echo startDescriptivePanel($currentFaction->name);
echo descriptionLi($currentFaction->description);
echo getStaticTraitHtml($currentFaction->traits);
getBMHtml($currentFaction->bonusMalus,$currentFaction->name,'faction');
echo endPanel();
?>
