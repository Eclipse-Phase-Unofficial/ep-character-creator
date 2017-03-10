<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
require_once '../../../php/EPAtom.php';
include('../other/bonusMalusLayer.php');
require_once('../other/panelHelper.php');

session_start();
$currentMorphsList = $_SESSION['cc']->getCurrentMorphs();
$currentMorph = getAtomByName($currentMorphsList,$_SESSION['currentMorph']);
if($currentMorph == null){
    $currentMorph = $_SESSION['cc']->getMorphByName($_SESSION['currentMorph']);
}

$traits = $_SESSION['cc']->getCurrentMorphTraits($currentMorph->name);

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
$myPanel->addRawHtml( getBMHtml($currentMorph->bonusMalus,$currentMorph->name,'morph') );
echo $myPanel->getHtml();
echo endPanel();
?>
