<?php
declare(strict_types=1);

use App\Creator\Atoms\EPBackground;
use App\Creator\DisplayHelpers\Li;

?>
<ul class="mainlist" id="backgrounds">
    <?php
        $currentBck = creator()->getCurrentBackground();

         foreach(EpDatabase()->getBackgrounds() as $m){
            if($m->backgroundType == EPBackground::$ORIGIN){
                $li = new Li($m->getName(),'bck');
                $li->addBookIcon($m->getName());
                $li->addCheckedBlank("",isset($currentBck) && $currentBck->getName() == $m->getName());
                echo $li->getHtml();
            }
         }
    ?>
</ul>
