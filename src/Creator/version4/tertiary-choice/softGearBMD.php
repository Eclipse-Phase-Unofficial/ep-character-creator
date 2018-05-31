<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use App\Creator\DisplayHelpers\Helpers;
use App\Creator\DisplayHelpers\Panel;

session_start();
$currentGear = EpDatabase()->getGearByName($_SESSION['currentSoftName']);

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentGear->name);
$myPanel->addDescription($currentGear->description);
$myPanel->addArmor($currentGear);
$myPanel->addRawHtml(Helpers::getBMHtml(creator(), $currentGear->bonusMalus,$currentGear->name,'soft') );
echo $myPanel->getHtml();
echo Panel::endPanel();
