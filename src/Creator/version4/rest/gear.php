<?php
require_once '../../../php/EPCharacterCreator.php';
require_once('../other/panelHelper.php');
require_once('../other/gearHelper.php');
require_once('../other/bookPageLayer.php');
require_once './_headers.php';

session_start();

$allAis = $_SESSION['cc']->getAis();
$currentAis = $_SESSION['cc']->getEgoAi();
$defaultAi = $_SESSION['cc']->getDefaultEgoAi();
foreach ($allAis as $m) {
  $m->book = getBookAbbreviation($m->name);
  $m->isOwned = $m->isInArray($defaultAi) || $m->isInArray($currentAis);
} 

$gears = $_SESSION['cc']->getGears();
$softGear = [];
$hardGear = [];
foreach($gears as $m){
  $m->book = getBookAbbreviation($m->name);
  if($m->gearType == EPGear::$SOFT_GEAR){
    $m->isOwned = $m->isInArray($currentSoftGear);
    array_push($softGear, $m);
  } else {
    array_push($hardGear, $m);
  }
}

$freeGear=$_SESSION['cc']->getEgoSoftGears();
$result = [
  'ai' => $allAis,
  'freeGear'=> $freeGear,
  'hardGear'=> $hardGear,
  'softGear'=> $softGear
];

echo json_encode($result);
?>
