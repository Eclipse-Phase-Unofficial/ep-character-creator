<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use EclipsePhaseCharacterCreator\Site\other\Helpers;

session_start();
?>
<ul class="mainlist" id="negtraits">
    <?php
        $currentTraits = $_SESSION['cc']->getCurrentTraits();
        $defaultTraits = $_SESSION['cc']->getCurrentDefaultEgoTraits();
         foreach($_SESSION['cc']->getTraits() as $m){
            if($m->isNegative() &&
               $m->isEgo() && $m->cpCost > 0){
                echo Helpers::getDynamicTraitLi($m,$currentTraits,$defaultTraits,'negTrait','addSelNegTraitIcon');
            }
         }
    ?>
</ul>









