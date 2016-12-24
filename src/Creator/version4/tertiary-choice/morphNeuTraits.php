<?php
require_once '../../../php/EPCharacterCreator.php';
require_once('../other/traitLayer.php');
session_start();
$currentMorph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
?>
<label class="descriptionTitle"><?php echo $currentMorph->name; ?></label>
<ul class="mainlist" id="morphNegtraits">
    <li><label class='foldingListSection'>Morph Neutral Traits</label></li>
    <?php
        $currentTraits = $_SESSION['cc']->getCurrentMorphTraits($_SESSION['currentMorph']);
        $defaultTraits = $_SESSION['cc']->getCurrentDefaultMorphTraits($currentMorph);
        foreach($_SESSION['cc']->getTraits() as $m){
            if($m->isMorph()  &&
               isTraitLegal($currentMorph,$m) &&
               $m->cpCost == 0){
                getDynamicTraitLi($m,$currentTraits,$defaultTraits,'morphNeuTrait','addSelMorphNeuTraitIcon');
            }
         }
    ?>
</ul>
