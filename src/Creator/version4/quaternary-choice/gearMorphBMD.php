<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use EclipsePhaseCharacterCreator\Backend\EPAtom;
use EclipsePhaseCharacterCreator\Site\other\Panel;
use EclipsePhaseCharacterCreator\Site\other\Helpers;

session_start();

// $hint = "--dubug:morph";
$morphGears = $_SESSION['cc']->getGearForMorphName($_SESSION['currentMorph']);
$currentGear = EPAtom::getAtomByName($morphGears,$_SESSION['currentMorphGearName']);
if($currentGear == null){
    $currentGear =  $_SESSION['cc']->getGearByName($_SESSION['currentMorphGearName']);
    // $hint = "--debug:general";
}

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentGear->name);
$myPanel->addBuySell($currentGear,"MORPH");
$myPanel->addDescription($currentGear->description);
$myPanel->addArmor($currentGear);
$myPanel->addRawHtml( Helpers::getBMHtml($currentGear->bonusMalus,$currentGear->name,'morphGear') );
echo $myPanel->getHtml();
echo Panel::endPanel();
