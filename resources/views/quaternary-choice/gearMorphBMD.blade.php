<?php
declare(strict_types=1);

use App\Creator\Atoms\EPAtom;
use App\Creator\DisplayHelpers\Panel;
use App\Creator\DisplayHelpers\Helpers;

// $hint = "--dubug:morph";
$morphGears = EpDatabase()->getGearForMorphName((string) session('currentMorph'));
$currentGear = EPAtom::getAtomByName($morphGears, (string) session('currentMorphGearName');
if($currentGear == null){
    $currentGear =  EpDatabase()->getGearByName((string) session('currentMorphGearName'));
    // $hint = "--debug:general";
}

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentGear->getName());
$myPanel->addBuySell($currentGear,"MORPH");
$myPanel->addDescription($currentGear->getDescription());
$myPanel->addArmor($currentGear);
$myPanel->addRawHtml( Helpers::getBMHtml(creator(), $currentGear->bonusMalus,$currentGear->getName(),'morphGear') );
echo $myPanel->getHtml();
echo Panel::endPanel();
