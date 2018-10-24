<?php
require_once '../../../php/EPCharacterCreator.php';
require_once('../other/traitLayer.php');
require_once './_headers.php';
session_start();

$currentTraits = $_SESSION['cc']->getCurrentTraits();
$defaultTraits = $_SESSION['cc']->getCurrentDefaultEgoTraits();
$result = [];
foreach($_SESSION['cc']->getTraits() as $m){
    if($m->isEgo() && $m->cpCost == 0){
        array_push($result, getDynamicTrait($m,$currentTraits,$defaultTraits,'negTrait','addSelNegTraitIcon'));
    }
}

echo json_encode($result);
?>










