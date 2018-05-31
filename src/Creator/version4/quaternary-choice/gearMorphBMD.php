<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use App\Creator\Atoms\EPAtom;
use App\Creator\DisplayHelpers\Panel;
use App\Creator\DisplayHelpers\Helpers;

session_start();

// $hint = "--dubug:morph";
$morphGears = creator()->getGearForMorphName($_SESSION['currentMorph']);
$currentGear = EPAtom::getAtomByName($morphGears,$_SESSION['currentMorphGearName']);
if($currentGear == null){
    $currentGear =  creator()->getGearByName($_SESSION['currentMorphGearName']);
    // $hint = "--debug:general";
}

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentGear->name);
$myPanel->addBuySell($currentGear,"MORPH");
$myPanel->addDescription($currentGear->description);
$myPanel->addArmor($currentGear);
$myPanel->addRawHtml( Helpers::getBMHtml(creator(), $currentGear->bonusMalus,$currentGear->name,'morphGear') );
echo $myPanel->getHtml();
echo Panel::endPanel();
