<?php
require_once '../../../php/EPCharacterCreator.php';
require_once('../other/panelHelper.php');
require_once('../other/gearHelper.php');

session_start();
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
            $li = new li($m->name,'ai');
            $li->addCost($m->getCost(),$m->isInArray($defaultAi));
            $li->addBookIcon($m->name);
            $li->addPlusChecked('addSelAiIcon',$m->isInArray($defaultAi) || $m->isInArray($currentAis));
            echo $li->getHtml();
          }
          echo "</ul>";
          
         //SOFT GEAR 
         $currentSoftGear = $_SESSION['cc']->getEgoSoftGears();
		 echo "<li class='foldingListSection' id='softLst'>";
 		 echo "Software";
 		 echo "</li>";
 		 echo "<ul class='mainlist foldingList softLst'>";
         foreach($_SESSION['cc']->getGears() as $m){
         		if($m->gearType == EPGear::$SOFT_GEAR){
                    $li = new li($m->name,'softG');
                    $li->addCost($m->getCost());
                    $li->addBookIcon($m->name);
                    $li->addPlusChecked('addSelSoftGearIcon',$m->isInArray($currentSoftGear));
                    echo $li->getHtml();
            	}
          }
          echo "</ul>";

        //FREE GEAR SECTION
        echo getFreeGear($_SESSION['cc']->getEgoSoftGears());
	?>
</ul>
