<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use App\Creator\Atoms\EPAtom;
use App\Creator\DisplayHelpers\Panel;
use App\Creator\DisplayHelpers\Helpers;

session_start();
$currentMorphsList = creator()->getCurrentMorphs();
$currentMorph = EPAtom::getAtomByName($currentMorphsList,$_SESSION['currentMorph']);
if($currentMorph == null){
    $currentMorph = creator()->getMorphByName($_SESSION['currentMorph']);
}

$traits = creator()->getCurrentMorphTraits($currentMorph->name);

function getImplantHtml($implants){
    $output = "";
    if(!empty($implants)){
        $output .= "<li class='listSection'>";
        $output .= "Implants";
        $output .= "</li>";
        foreach($implants as $g){
            $output .= "<li>";
            $output .= "		<label class='bmGranted'>".$g->name."</label>";
            $output .= "</li>";
        }
    }
    return $output;
}


$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentMorph->name);
$myPanel->addDescription($currentMorph->description);
$myPanel->addTraits($traits);
$myPanel->addRawHtml( getImplantHtml($currentMorph->gears) );
$myPanel->addRawHtml( Helpers::getBMHtml(creator(), $currentMorph->bonusMalus,$currentMorph->name,'morph') );
echo $myPanel->getHtml();
echo Panel::endPanel();
