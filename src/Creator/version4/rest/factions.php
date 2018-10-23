<?php
require_once '../../../php/EPCharacterCreator.php';
require_once('../other/panelHelper.php');
require_once './_headers.php';

session_start();

$result = [];

foreach($_SESSION['cc']->getBackgrounds() as $m){
    if($m->backgroundType == EPBackground::$FACTION){
        $m->isSelected = $currentBck;
        array_push($result, $m);
    }
 }
$payload =  ['test' => 'foo'];
echo json_encode($result);
?>
