<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
include('../other/bonusMalusLayer.php');
require_once('../other/traitLayer.php');
require_once('../other/panelHelper.php');

session_start();

$currentBck = $_SESSION['cc']->getCurrentBackground();

echo startDescriptivePanel($currentBck->name);
echo descriptionLi($currentBck->description);
echo getStaticTraitHtml($currentBck->traits);
getBMHtml($currentBck->bonusMalus,$currentBck->name,'origine');
echo endPanel();
?>
