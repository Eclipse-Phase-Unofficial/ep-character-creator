<?php
require_once '../../../php/EPCharacterCreator.php';
require_once '../other/panelHelper.php';
require_once '../other/bookPageLayer.php';
require_once './_headers.php';

session_start();

$result = [];
foreach($_SESSION['cc']->getBackgrounds() as $m){
    if($m->backgroundType == EPBackground::$ORIGIN){
        $m->isSelected = $currentBck;
        $m->atomUid = $m->getUid();
        $book = new EPBook($m->name);
        $m->book = $book->getAbbreviation();
        $m->findMore = $book->getPrintableNameL();
        array_push($result, $m);
    }
}

echo json_encode($result);
?>
