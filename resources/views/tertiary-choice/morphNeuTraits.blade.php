<?php
declare(strict_types=1);

use App\Creator\DisplayHelpers\Helpers;

$currentMorph = creator()->getCurrentMorphsByName((string) session('currentMorph'));
?>
<label class="descriptionTitle"><?php echo $currentMorph->getName(); ?></label>
<ul class="mainlist" id="morphNegtraits">
    <li class='foldingListSection'>Morph Neutral Traits</li>
    <?php
        $currentTraits = creator()->getCurrentMorphTraits((string) session('currentMorph'));
        $defaultTraits = creator()->getCurrentDefaultMorphTraits($currentMorph);
        foreach(EpDatabase()->getTraits() as $m){
            if($m->isNeutral() && $m->isMorph() && Helpers::isTraitLegal($currentMorph,$m)) {
                echo Helpers::getDynamicTraitLi($m,$currentTraits,$defaultTraits,'morphNeuTrait','addSelMorphNeuTraitIcon');
            }
         }
    ?>
</ul>
