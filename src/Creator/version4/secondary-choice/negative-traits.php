<?php
require_once '../../../php/EPCharacterCreator.php';
require_once('../other/traitLayer.php');
session_start();
?>
<ul class="mainlist" id="negtraits">
    <?php
        $currentTraits = $_SESSION['cc']->getCurrentTraits();
        $defaultTraits = $_SESSION['cc']->getCurrentDefaultEgoTraits();
         foreach($_SESSION['cc']->getTraits() as $m){
            if($m->isNegative() &&
               $m->isEgo() && $m->cpCost > 0){
                getDynamicTraitLi($m,$currentTraits,$defaultTraits,'negTrait','addSelNegTraitIcon');
            }
         }
    ?>
</ul>









