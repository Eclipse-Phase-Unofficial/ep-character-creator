<?php
require_once '../../../php/EPListProvider.php';
require_once '../../../php/EPCharacterCreator.php';
require_once './_headers.php';

session_start();

$result = [];
foreach($_SESSION['cc']->character->ego->getActiveSkills() as $m){
    $m->uid = $m->getUid();
    $m->printableName = $m->getPrintableName();
    array_push($result, $m);
}

echo json_encode($result);
?>