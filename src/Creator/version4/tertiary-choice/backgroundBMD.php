<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use App\Creator\DisplayHelpers\Helpers;
use App\Creator\DisplayHelpers\Panel;

session_start();

$currentBck = $_SESSION['cc']->getCurrentBackground();

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentBck->name);
$myPanel->addDescription($currentBck->description);
$myPanel->addTraits($currentBck->traits);
$myPanel->addRawHtml( Helpers::getBMHtml($currentBck->bonusMalus,$currentBck->name,'origine') );
echo $myPanel->getHtml();
echo Panel::endPanel();
?>
