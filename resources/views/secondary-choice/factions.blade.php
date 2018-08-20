<?php
declare(strict_types=1);

use App\Creator\Atoms\EPBackground;
use App\Creator\DisplayHelpers\Li;

?>
<ul class="mainlist" id="factions">
    <?php
        $currentFac = creator()->getCurrentFaction();

         foreach(EpDatabase()->getBackgrounds() as $m){
            if($m->backgroundType == EPBackground::$FACTION){
                $li = new Li($m->name,'fac');
                $li->addBookIcon($m->name);
                $li->addCheckedBlank("",isset($currentFac) && $currentFac->name == $m->name);
                echo $li->getHtml();
            }
         }
    ?>
</ul>
