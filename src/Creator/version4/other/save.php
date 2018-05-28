<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use EclipsePhaseCharacterCreator\Backend\EPFileUtility;

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
