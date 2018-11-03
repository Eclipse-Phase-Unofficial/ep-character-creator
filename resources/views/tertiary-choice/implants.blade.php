<?php
declare(strict_types=1);

use App\Creator\Atoms\EPGear;
use App\Creator\DisplayHelpers\Helpers;

$currentMorph = creator()->getCurrentMorphsByName($_SESSION['currentMorph']);
?>
<label class="descriptionTitle"><?php echo $currentMorph->getName(); ?></label>
<ul class="mainlist" id="implants">
    <li class='foldingListSection'>Implants</li>
    <?php
        $listFiltered = array();
        foreach(EpDatabase()->getGears() as $m){
            if($m->gearType == EPGear::$IMPLANT_GEAR){
                array_push($listFiltered, $m);
            }
        }
        $formatedHtml = Helpers::getFormatedMorphGearList(creator(), $listFiltered,$currentMorph,'addSelMorphImplantIcon');
        echo $formatedHtml;
    ?>
</ul>
