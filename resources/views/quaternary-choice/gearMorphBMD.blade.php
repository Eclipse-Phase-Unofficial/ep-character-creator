<?php
declare(strict_types=1);

use App\Creator\Atoms\EPAtom;
use App\Creator\DisplayHelpers\Panel;
use App\Creator\DisplayHelpers\Helpers;

$currentMorphGearName = (string) session('currentMorphGearName');
$morph = creator()->getCurrentMorphsByName(session('currentMorph'));
$currentGear = EPAtom::getAtomByName($morph->getGear(), $currentMorphGearName);
if($currentGear == null){
    $currentGear =  EpDatabase()->getGearByName($currentMorphGearName);
}

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentGear->getName());
$myPanel->addBuySell($currentGear,"MORPH");
$myPanel->addDescription($currentGear->getDescription());
$myPanel->addArmor($currentGear);
$myPanel->addRawHtml( Helpers::getBMHtml(creator(), $currentGear->bonusMalus,$currentGear->getName(),'morphGear') );
echo $myPanel->getHtml();
echo Panel::endPanel();
