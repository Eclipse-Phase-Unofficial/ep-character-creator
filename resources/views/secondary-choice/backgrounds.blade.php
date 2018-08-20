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
                $li = new Li($m->name,'bck');
                $li->addBookIcon($m->name);
                $li->addCheckedBlank("",isset($currentBck) && $currentBck->name == $m->name);
                echo $li->getHtml();
            }
         }
    ?>
</ul>
