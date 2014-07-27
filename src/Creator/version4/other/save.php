<?php

require_once '../../../php/EPCharacterCreator.php';
session_start();

$filename = $_POST["saveName"];

// make filename safe for file systems.
$filename = preg_replace('/[^a-zA-Z0-9_.-]/', '', $filename);

header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="' . $filename . '.json"');
echo json_encode($_SESSION['cc']->getSavePack());