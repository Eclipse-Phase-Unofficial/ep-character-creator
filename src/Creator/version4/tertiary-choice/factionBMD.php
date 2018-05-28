<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use EclipsePhaseCharacterCreator\Site\other\Helpers;
use EclipsePhaseCharacterCreator\Site\other\Panel;

session_start();

$currentFaction = $_SESSION['cc']->getCurrentFaction();

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentFaction->name);
$myPanel->addDescription($currentFaction->description);
$myPanel->addTraits($currentFaction->traits);
$myPanel->addRawHtml( Helpers::getBMHtml($currentFaction->bonusMalus,$currentFaction->name,'faction') );
echo $myPanel->getHtml();
echo Panel::endPanel();
?>
