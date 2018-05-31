<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use App\Creator\Atoms\EPAtom;
use App\Creator\DisplayHelpers\Panel;
use App\Creator\DisplayHelpers\Helpers;

session_start();

$currentMorphTraits = $_SESSION['cc']->getCurrentMorphTraits($_SESSION['currentMorph']);
$currentTrait = EPAtom::getAtomByName($currentMorphTraits,$_SESSION['currentMorphTraitName']);
if($currentTrait == null){
    $currentTrait =  $_SESSION['cc']->getTraitByName($_SESSION['currentMorphTraitName']);
}

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentTrait->name);
$myPanel->addDescription($currentTrait->description);
$myPanel->addRawHtml( Helpers::getBMHtml($currentTrait->bonusMalus,$currentTrait->name,'morphTrait') );
echo $myPanel->getHtml();
echo Panel::endPanel();
