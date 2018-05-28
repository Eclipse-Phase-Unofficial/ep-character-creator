<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use EclipsePhaseCharacterCreator\Site\other\Helpers;
use EclipsePhaseCharacterCreator\Site\other\Panel;

session_start();
$currentGear = $_SESSION['cc']->getGearByName($_SESSION['currentSoftName']);

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentGear->name);
$myPanel->addDescription($currentGear->description);
$myPanel->addArmor($currentGear);
$myPanel->addRawHtml(Helpers::getBMHtml($currentGear->bonusMalus,$currentGear->name,'soft') );
echo $myPanel->getHtml();
echo Panel::endPanel();
