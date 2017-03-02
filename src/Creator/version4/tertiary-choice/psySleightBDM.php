<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
include('../other/bonusMalusLayer.php');
require_once('../other/panelHelper.php');

session_start();
$currentPsiS = $_SESSION['cc']->getPsySleightsByName($_SESSION['currentPsiSName']);

echo startPanel($currentPsiS->name,"bmdList");
echo descriptionLi($currentPsiS->description);
getBMHtml($currentPsiS->bonusMalus,$currentPsiS->name,'psi');
echo endPanel();
?>
