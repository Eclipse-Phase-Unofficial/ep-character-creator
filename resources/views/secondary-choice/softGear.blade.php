<?php
declare(strict_types=1);

use App\Creator\Atoms\EPGear;
use App\Creator\DisplayHelpers\Helpers;
use App\Creator\DisplayHelpers\Li;

$gears = EpDatabase()->getGears();
$currentSoftGear = creator()->getEgoSoftGears();
?>
<ul class="mainlist" id="soft">
	<?php

		 //AI GEAR
		 $currentAis = creator()->getEgoAi();
		 $defaultAi = creator()->getDefaultEgoAi();
		 echo "<li class='foldingListSection' id='ai'>";
 		 echo "Ai's and Muses";
 		 echo "</li>";
 		 echo "<ul class='mainlist foldingList ai'>";
         foreach(EpDatabase()->getAis() as $m){
            $li = new Li($m->getName(),'ai');
            $li->addCost($m->getCost(),$m->isInArray($defaultAi));
            $li->addBookIcon($m->getName());
            $li->addPlusChecked('addSelAiIcon',$m->isInArray($defaultAi) || $m->isInArray($currentAis));
            echo $li->getHtml();
          }
          echo "</ul>";

         //SOFT GEAR
		 echo "<li class='foldingListSection' id='softLst'>";
 		 echo "Software";
 		 echo "</li>";
 		 echo "<ul class='mainlist foldingList softLst'>";
         foreach($gears as $m){
            if($m->getType() == EPGear::$SOFT_GEAR){
                $li = new Li($m->getName(),'softG');
                $li->addCost($m->getCost());
                $li->addBookIcon($m->getName());
                $li->addPlusChecked('addSelSoftGearIcon',$m->isInArray($currentSoftGear));
                echo $li->getHtml();
            }
          }
          echo "</ul>";

        //FREE GEAR SECTION
        echo Helpers::getFreeGear(creator()->getEgoSoftGears());
	?>
</ul>
