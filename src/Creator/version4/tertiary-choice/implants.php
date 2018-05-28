<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use EclipsePhaseCharacterCreator\Backend\EPGear;
use EclipsePhaseCharacterCreator\Site\other\Helpers;

session_start();
$currentMorph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
?>
<label class="descriptionTitle"><?php echo $currentMorph->name; ?></label>
<ul class="mainlist" id="implants">
    <li class='foldingListSection'>Implants</li>
    <?php
        $listFiltered = array();
        foreach($_SESSION['cc']->getGears() as $m){
            if($m->gearType == EPGear::$IMPLANT_GEAR){
                array_push($listFiltered, $m);
            }
        }
        $formatedHtml = Helpers::getFormatedMorphGearList($listFiltered,$currentMorph,'addSelMorphImplantIcon');
        echo $formatedHtml;
    ?>
</ul>
