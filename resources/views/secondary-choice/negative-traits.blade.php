<?php
declare(strict_types=1);

use App\Creator\DisplayHelpers\Helpers;
?>
<ul class="mainlist" id="negtraits">
    <?php
        $currentTraits = creator()->getCurrentTraits();
        $defaultTraits = creator()->getCurrentDefaultEgoTraits();
         foreach(EpDatabase()->getTraits() as $m){
            if($m->isNegative() && $m->isEgo()) {
                echo Helpers::getDynamicTraitLi($m,$currentTraits,$defaultTraits,'negTrait','addSelNegTraitIcon');
            }
         }
    ?>
</ul>









