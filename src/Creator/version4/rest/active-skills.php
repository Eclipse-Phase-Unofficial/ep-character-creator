<?php
require_once '../../../php/EPListProvider.php';
require_once '../../../php/EPCharacterCreator.php';

session_start();

$result = [];
foreach($_SESSION['cc']->character->ego->getActiveSkills() as $m){
    $m->uid = $m->getUid();
    $m->printableName = $m->getPrintableName();
    array_push($result, $m);
}

header('Content-type: application/json');
echo json_encode($result);
?>