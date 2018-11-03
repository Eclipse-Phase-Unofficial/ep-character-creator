<?php
declare(strict_types=1);

use App\Creator\DisplayHelpers\Helpers;

$currentMorph = creator()->getCurrentMorphsByName($_SESSION['currentMorph']);
?>
<label class="descriptionTitle"><?php echo $currentMorph->getName(); ?></label>
<ul class="mainlist" id="morphPostraits">
    <li class='foldingListSection'>Morph Pos. Traits</li>
    <?php
        $currentTraits = creator()->getCurrentMorphTraits($_SESSION['currentMorph']);
        $defaultTraits = creator()->getCurrentDefaultMorphTraits($currentMorph);
        foreach(EpDatabase()->getTraits() as $m){
            if($m->isPositive() &&
               $m->isMorph()  &&
                Helpers::isTraitLegal($currentMorph,$m) &&
               $m->cpCost > 0){
                echo Helpers::getDynamicTraitLi($m,$currentTraits,$defaultTraits,'morphPosTrait','addSelMorphPosTraitIcon');
            }
         }

    ?>
</ul>
