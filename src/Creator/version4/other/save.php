<?php

require_once '../../../php/EPCharacterCreator.php';
session_start();

$filename = $_POST["saveName"];

// make filename safe for file systems.
$filename = preg_replace('/[^a-zA-Z0-9_.-]/', '', $filename);

// append .json extension if it is missing
if('.json' !== substr($filename, -5, 5)) {
    $filename .= '.json';
}

header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="' . $filename . '"');
echo json_encode($_SESSION['cc']->getSavePack());