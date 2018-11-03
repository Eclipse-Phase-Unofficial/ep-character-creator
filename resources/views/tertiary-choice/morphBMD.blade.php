<?php
declare(strict_types=1);

use App\Creator\Atoms\EPAtom;
use App\Creator\Atoms\EPGear;
use App\Creator\DisplayHelpers\Panel;
use App\Creator\DisplayHelpers\Helpers;

$currentMorphsList = creator()->getCurrentMorphs();
$currentMorph = EPAtom::getAtomByName($currentMorphsList,$_SESSION['currentMorph']);
if($currentMorph == null){
    $currentMorph = EpDatabase()->getMorphByName($_SESSION['currentMorph']);
}

$traits = creator()->getCurrentMorphTraits($currentMorph->getName());

/**
 * @param EPGear[] $implants
 * @return string
 */
function getImplantHtml(array $implants){
    $output = "";
    if(!empty($implants)){
        $output .= "<li class='listSection'>";
        $output .= "Implants";
        $output .= "</li>";
        foreach($implants as $g){
            $output .= "<li>";
            $output .= "		<label class='bmGranted'>".$g->getName()."</label>";
            $output .= "</li>";
        }
    }
    return $output;
}


$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentMorph->getName());
$myPanel->addDescription($currentMorph->getDescription());
$myPanel->addTraits($traits);
$myPanel->addRawHtml( getImplantHtml($currentMorph->gears) );
$myPanel->addRawHtml( Helpers::getBMHtml(creator(), $currentMorph->bonusMalus,$currentMorph->getName(),'morph') );
echo $myPanel->getHtml();
echo Panel::endPanel();
