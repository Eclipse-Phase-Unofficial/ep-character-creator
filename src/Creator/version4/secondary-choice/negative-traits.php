<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use App\Creator\DisplayHelpers\Helpers;

session_start();
?>
<ul class="mainlist" id="negtraits">
    <?php
        $currentTraits = creator()->getCurrentTraits();
        $defaultTraits = creator()->getCurrentDefaultEgoTraits();
         foreach(EpDatabase()->getTraits() as $m){
            if($m->isNegative() &&
               $m->isEgo() && $m->cpCost > 0){
                echo Helpers::getDynamicTraitLi($m,$currentTraits,$defaultTraits,'negTrait','addSelNegTraitIcon');
            }
         }
    ?>
</ul>









