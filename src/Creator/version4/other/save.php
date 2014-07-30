<?php

require_once '../../../php/EPCharacterCreator.php';
require_once '../../../php/EPFileUtility.php';
session_start();

$filename = $_POST["saveName"];

$file_util = new EPFileUtility($_SESSION['cc']->character);
$filename = $file_util->sanitizeFilename($filename);

// append .json extension if it is missing
if('.json' !== substr($filename, -5, 5)) {
    $filename .= '.json';
}

header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="' . $filename . '"');
echo json_encode($_SESSION['cc']->getSavePack());