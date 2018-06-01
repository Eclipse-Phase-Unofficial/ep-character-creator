<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../vendor/autoload.php');

use App\Creator\DisplayHelpers\Helpers;
use App\Creator\DisplayHelpers\Panel;

session_start();

$currentFaction = creator()->getCurrentFaction();

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentFaction->name);
$myPanel->addDescription($currentFaction->description);
$myPanel->addTraits($currentFaction->traits);
$myPanel->addRawHtml( Helpers::getBMHtml(creator(), $currentFaction->bonusMalus,$currentFaction->name,'faction') );
echo $myPanel->getHtml();
echo Panel::endPanel();
?>
