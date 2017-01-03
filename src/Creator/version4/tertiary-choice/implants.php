<?php
require_once '../../../php/EPCharacterCreator.php';
require_once '../other/gearHelper.php';
include('../other/bookPageLayer.php');
session_start();
$currentMorph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
?>
<label class="descriptionTitle"><?php echo $currentMorph->name; ?></label>
<ul class="mainlist" id="implants">
    <li><label class='foldingListSection'>Implants</label></li>
    <?php
        $listFiltered = array();
        foreach($_SESSION['cc']->getGears() as $m){
            if($m->gearType == EPGear::$IMPLANT_GEAR){
                array_push($listFiltered, $m);
            }
        }
        $formatedHtml = getFormatedGearList($listFiltered,$currentMorph,'addSelMorphImplantIcon');
        echo $formatedHtml;
    ?>
</ul>
