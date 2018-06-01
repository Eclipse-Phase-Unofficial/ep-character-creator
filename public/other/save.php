<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../vendor/autoload.php');

use App\Creator\EPFileUtility;

session_start();

$filename = $_POST["saveName"];

$filename = EPFileUtility::sanitizeFilename($filename);

// append .json extension if it is missing
if('.json' !== substr($filename, -5, 5)) {
    $filename .= '.json';
}

header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="' . $filename . '"');
echo json_encode(creator()->getSavePack());
