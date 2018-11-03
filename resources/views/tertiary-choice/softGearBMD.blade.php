<?php
declare(strict_types=1);

use App\Creator\DisplayHelpers\Helpers;
use App\Creator\DisplayHelpers\Panel;

$currentGear = EpDatabase()->getGearByName($_SESSION['currentSoftName']);

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentGear->getName());
$myPanel->addDescription($currentGear->getDescription());
$myPanel->addArmor($currentGear);
$myPanel->addRawHtml(Helpers::getBMHtml(creator(), $currentGear->bonusMalus,$currentGear->getName(),'soft') );
echo $myPanel->getHtml();
echo Panel::endPanel();
