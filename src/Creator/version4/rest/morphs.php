<?php
require_once '../../../php/EPCharacterCreator.php';
require_once('../other/panelHelper.php');
require_once('../other/bookPageLayer.php');
require_once './_headers.php';

session_start();

$listMorphs = $_SESSION['cc']->getMorphs();
$currentMorphs = $_SESSION['cc']->getCurrentMorphs(); 

$result = [];
foreach($listMorphs as $m){
    $m->isOwned = $m->isInArray($currentMorphs);
    $m->book = getBookAbbreviation($m->name);
}

echo json_encode($listMorphs);
?>
