<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
require_once '../../../php/EPAtom.php';
include('../other/bonusMalusLayer.php');
require_once('../other/panelHelper.php');
require_once('../other/traitLayer.php');

session_start();
$currentMorphsList = $_SESSION['cc']->getCurrentMorphs();
$currentMorph = getAtomByName($currentMorphsList,$_SESSION['currentMorph']);
if($currentMorph == null){
    $currentMorph = $_SESSION['cc']->getMorphByName($_SESSION['currentMorph']);
}

$traits = $_SESSION['cc']->getCurrentMorphTraits($currentMorph->name);

echo startPanel($currentMorph->name,"bmdList");
echo descriptionLi($currentMorph->description);
getBMHtml($currentMorph->bonusMalus,$currentMorph->name,'morph');
echo getStaticTraitHtml($traits);
if(!empty($currentMorph->gears)){
    echo "<li class='listSection'>";
    echo "Implants";
    echo "</li>";
    foreach($currentMorph->gears as $g){
        echo "<li>";
        echo "		<label class='bmGranted'>".$g->name."</label>";
        echo "</li>";
    }
}
echo endPanel();
?>
