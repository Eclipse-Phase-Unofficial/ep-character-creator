<?php
declare(strict_types=1);

use App\Creator\Atoms\EPAtom;
use App\Creator\DisplayHelpers\Helpers;
use App\Creator\DisplayHelpers\Panel;

$currentSoftGearList = creator()->getEgoSoftGears();
$currentGear         = EPAtom::getAtomByName($currentSoftGearList, (string)session('currentSoftName'));
if ($currentGear == null) {
    $currentGear = EpDatabase()->getGearByName((string)session('currentSoftName'));
}

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentGear->getName());
$myPanel->addDescription($currentGear->getDescription());
$myPanel->addArmor($currentGear);
$myPanel->addRawHtml(Helpers::getBMHtml(creator(), $currentGear->bonusMalus, $currentGear->getName(), 'soft'));
echo $myPanel->getHtml();
echo Panel::endPanel();
