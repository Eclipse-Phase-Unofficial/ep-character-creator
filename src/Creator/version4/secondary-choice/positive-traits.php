<?php
require_once '../../../php/EPCharacterCreator.php';
require_once('../other/traitLayer.php');
session_start();
?>
<ul class="mainlist" id="postraits">
    <?php
        $currentTraits = $_SESSION['cc']->getCurrentTraits();
        $defaultTraits = $_SESSION['cc']->getCurrentDefaultEgoTraits();
         foreach($_SESSION['cc']->getTraits() as $m){
            if($m->isPositive() &&
               $m->isEgo()  && $m->cpCost > 0){
                getDynamicTraitLi($m,$currentTraits,$defaultTraits,'posTrait','addSelPosTraitIcon');
            }
         }
    ?>
</ul>
