<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use App\Creator\Atoms\EPGear;
use App\Creator\DisplayHelpers\Helpers;
use App\Creator\DisplayHelpers\Li;

session_start();

$gears = $_SESSION['cc']->getGears();
$currentSoftGear = $_SESSION['cc']->getEgoSoftGears();
?>
<ul class="mainlist" id="soft">
	<?php
		 
		 //AI GEAR
		 $currentAis = $_SESSION['cc']->getEgoAi();
		 $defaultAi = $_SESSION['cc']->getDefaultEgoAi();
		 echo "<li class='foldingListSection' id='ai'>";
 		 echo "Ai's and Muses";
 		 echo "</li>";
 		 echo "<ul class='mainlist foldingList ai'>";
         foreach($_SESSION['cc']->getAis() as $m){
            $li = new Li($m->name,'ai');
            $li->addCost($m->getCost(),$m->isInArray($defaultAi));
            $li->addBookIcon($m->name);
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
            if($m->gearType == EPGear::$SOFT_GEAR){
                $li = new Li($m->name,'softG');
                $li->addCost($m->getCost());
                $li->addBookIcon($m->name);
                $li->addPlusChecked('addSelSoftGearIcon',$m->isInArray($currentSoftGear));
                echo $li->getHtml();
            }
          }
          echo "</ul>";

        //FREE GEAR SECTION
        echo Helpers::getFreeGear($_SESSION['cc']->getEgoSoftGears());
	?>
</ul>
