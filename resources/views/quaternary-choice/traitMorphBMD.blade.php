<?php
declare(strict_types=1);

use App\Creator\Atoms\EPAtom;
use App\Creator\DisplayHelpers\Panel;
use App\Creator\DisplayHelpers\Helpers;

$currentMorphTraits = creator()->getCurrentMorphTraits($_SESSION['currentMorph']);
$currentTrait = EPAtom::getAtomByName($currentMorphTraits,$_SESSION['currentMorphTraitName']);
if($currentTrait == null){
    $currentTrait =  EpDatabase()->getTraitByName($_SESSION['currentMorphTraitName']);
}

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentTrait->name);
$myPanel->addDescription($currentTrait->description);
$myPanel->addRawHtml( Helpers::getBMHtml(creator(), $currentTrait->bonusMalus,$currentTrait->name,'morphTrait') );
echo $myPanel->getHtml();
echo Panel::endPanel();
