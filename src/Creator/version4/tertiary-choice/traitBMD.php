<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use App\Creator\Atoms\EPAtom;
use App\Creator\DisplayHelpers\Panel;
use App\Creator\DisplayHelpers\Helpers;

session_start();

$currentTraitsList = creator()->getCurrentTraits();
$currentTrait = EPAtom::getAtomByName($currentTraitsList,$_SESSION['currentTraitName']);
if($currentTrait == null){
    $currentTrait = EpDatabase()->getTraitByName($_SESSION['currentTraitName']);
}

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentTrait->name);
$myPanel->addDescription($currentTrait->description);
$myPanel->addRawHtml( Helpers::getBMHtml(creator(), $currentTrait->bonusMalus,$currentTrait->name,'trait') );
echo $myPanel->getHtml();
echo Panel::endPanel();
