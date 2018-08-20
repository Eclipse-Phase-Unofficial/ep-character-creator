<?php
declare(strict_types=1);

use App\Creator\DisplayHelpers\Helpers;
use App\Creator\DisplayHelpers\Panel;

$currentGear = EpDatabase()->getGearByName($_SESSION['currentSoftName']);

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentGear->name);
$myPanel->addDescription($currentGear->description);
$myPanel->addArmor($currentGear);
$myPanel->addRawHtml(Helpers::getBMHtml(creator(), $currentGear->bonusMalus,$currentGear->name,'soft') );
echo $myPanel->getHtml();
echo Panel::endPanel();
