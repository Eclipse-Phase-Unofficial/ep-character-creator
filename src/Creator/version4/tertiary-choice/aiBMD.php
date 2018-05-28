<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use EclipsePhaseCharacterCreator\Site\other\Helpers;
use EclipsePhaseCharacterCreator\Site\other\Panel;

session_start();
$currentAi = $_SESSION['cc']->getAisByName($_SESSION['currentAiName']);

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentAi->name);
$myPanel->addBuySell($currentAi,"AI");
$myPanel->addDescription($currentAi->description);
$myPanel->addAi($currentAi);
$myPanel->addRawHtml( Helpers::getBMHtml($currentAi->bonusMalus,$currentAi->name,'ai') ); // 'ai' is not a valid value, so any choose options WILL FAIL
echo $myPanel->getHtml();
echo Panel::endPanel();
?>
