<?php
require_once '../../../php/EPCharacterCreator.php';
require_once('../other/panelHelper.php');
require_once('../other/bookPageLayer.php');

session_start();

$result = array();

foreach($_SESSION['cc']->getBackgrounds() as $m){
    if($m->backgroundType == EPBackground::$ORIGIN){
        $m->isSelected = $currentBck;
        array_push($result, $m);
    }
 }
header('Content-type: application/json');
echo json_encode($result);
?>
