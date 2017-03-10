<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
include('../other/bonusMalusLayer.php');
require_once('../other/panelHelper.php');

session_start();
$currentPsiS = $_SESSION['cc']->getPsySleightsByName($_SESSION['currentPsiSName']);

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentPsiS->name);
$myPanel->addDescription($currentPsiS->description);
$myPanel->addRawHtml( getBMHtml($currentPsiS->bonusMalus,$currentPsiS->name,'psi') );
echo $myPanel->getHtml();
echo endPanel();
?>
