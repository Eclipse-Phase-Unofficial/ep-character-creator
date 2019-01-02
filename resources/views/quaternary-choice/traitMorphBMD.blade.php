<?php
declare(strict_types=1);

use App\Creator\Atoms\EPAtom;
use App\Creator\DisplayHelpers\Panel;
use App\Creator\DisplayHelpers\Helpers;

$currentMorphTraits = creator()->getCurrentMorphTraits((string) session('currentMorph'));
$currentTrait = EPAtom::getAtomByName($currentMorphTraits, (string) session('currentMorphTraitName'));
if($currentTrait == null){
    $currentTrait =  EpDatabase()->getTraitByName((string) session('currentMorphTraitName'));
}

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentTrait->getName());
$myPanel->addDescription($currentTrait->getDescription());
$myPanel->addRawHtml( Helpers::getBMHtml(creator(), $currentTrait->bonusMalus,$currentTrait->getName(),'morphTrait') );
echo $myPanel->getHtml();
echo Panel::endPanel();
