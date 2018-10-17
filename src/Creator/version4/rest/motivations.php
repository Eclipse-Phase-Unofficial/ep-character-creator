<?php
require_once '../../../php/EPCharacterCreator.php';
session_start();

$result = array();

if($_SESSION['cc']->getMotivations() != null){
    $result = $_SESSION['cc']->getMotivations();
}

echo json_encode($result);
?>
