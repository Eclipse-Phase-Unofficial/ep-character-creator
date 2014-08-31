<?php
require_once '../../../php/EPCharacterCreator.php';
include('../other/bookPageLayer.php');
session_start();
?>
<ul class="mainlist" id="soft">
	<?php
		 
		 //AI GEAR
		 $currentAis = $_SESSION['cc']->getEgoAi();
		 $defaultAi = $_SESSION['cc']->getDefaultEgoAi();
		 echo "<li>";
 		 echo "		<label class='foldingListSection' id='ai'>Ai's and Muses</label>";
 		 echo "</li>";
 		 echo "<ul class='mainlist foldingList ai'>";
         foreach($_SESSION['cc']->getAis() as $m){
            	echo "<li>";
            	if(isOnlist($defaultAi,$m)){
            		echo "		<label class='ai' id='".$m->name."'>".$m->name.getListStampHtml($m->name)."</label><label class='costInfo'>(Granted)</label><span class='selectedicone selAi selAiIcon' id='".$m->name."' data-icon='&#x2b;'></span>";
            	}
            	else if(isOnlist($currentAis,$m)){
            		echo "		<label class='ai' id='".$m->name."'>".$m->name.getListStampHtml($m->name)."</label><label class='costInfo'>(".$m->getCost()." credits)</label><span class='selectedicone selAi selAiIcon' id='".$m->name."' data-icon='&#x2b;'></span>";
            	}
            	else{
            		echo "		<label class='ai' id='".$m->name."'>".$m->name.getListStampHtml($m->name)."</label><label class='costInfo'>(".$m->getCost()." credits)</label><span class='addIcon addAiIcon' id='".$m->name."' data-icon='&#x3a;'></span>";
            	}
            	
            	echo "</li>";
          }
          echo "</ul>";
          
         //SOFT GEAR 
         $currentSoftGear = $_SESSION['cc']->getEgoSoftGears();
		 echo "<li>";
 		 echo "		<label class='foldingListSection' id='softLst'>Software</label>";
 		 echo "</li>";
 		 echo "<ul class='mainlist foldingList softLst'>";
         foreach($_SESSION['cc']->getGears() as $m){
         		if($m->gearType == EPGear::$SOFT_GEAR){
	            	echo "<li>";
	            	if(isOnlist($currentSoftGear,$m)){
	            		echo "		<label class='softG' id='".$m->name."'>".$m->name.getListStampHtml($m->name)."</label><label class='costInfo'>(".$m->getCost()." credits)</label><span class='selectedicone selSoftG selSoftGearIcon' id='".$m->name."' data-icon='&#x2b;'></span>";
	            	}
	            	else{
	            		echo "		<label class='softG' id='".$m->name."'>".$m->name.getListStampHtml($m->name)."</label><label class='costInfo'>(".$m->getCost()." credits)</label><span class='addIcon addSoftGearIcon' id='".$m->name."' data-icon='&#x3a;'></span>";
	            	}
	            	
	            	echo "</li>";
            	}
          }
          echo "</ul>";
         
         //FREE GEAR SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='free'>Free gear</label>";
 		echo "</li>";
 		echo "<ul class='mainlist foldingList free' id='freeGear'>";
 		echo "	<li>";
 		echo "			<input type='text' id='freeEgoGearToAdd' placeholder='Gear Name'/>";
 		echo "			<select id='freeEgoGearPrice'>";
 		echo "					<option value=".EPCreditCost::$LOW.">".EPCreditCost::$LOW."</option>";
 		echo "					<option value=".EPCreditCost::$MODERATE.">".EPCreditCost::$MODERATE."</option>";
 		echo "					<option value=".EPCreditCost::$HIGH.">".EPCreditCost::$HIGH."</option>";
 		echo "					<option value=".EPCreditCost::$EXPENSIVE.">".EPCreditCost::$EXPENSIVE."</option>";
 		echo "					<option value=".EPCreditCost::$VERY_EXPENSIVE.">".EPCreditCost::$VERY_EXPENSIVE."</option>";
 		echo "					<option value=".EPCreditCost::$EXTREMELY_EXPENSIVE.">".EPCreditCost::$EXTREMELY_EXPENSIVE."</option>";
 		echo "			</select>";
		echo "			<span class='icone' id='addFreeEgoGear' data-icon='&#x3a;'></span>";
		echo "	</li>";
		$freeGear = $_SESSION['cc']->getEgoSoftGears();
		foreach($freeGear as $m){
			
 		 	if($m->gearType == EPGear::$FREE_GEAR){
     		 	echo "<li>";
                echo "		<label class='egoFreeGear remFreeEgoGear' id='".$m->name."'>".$m->name."</label><label class='costInfo'>(".$m->getCost()." credits)</label><span class='selectedicone remFGear' data-icon='&#x39;'></span>";
				echo "</li>"; 		
 		 	}
 		}
 		echo "</ul>";

         
         
         
         function isOnlist($list,$item){
	         foreach($list as $m){
	         	if($m->name == $item->name) return true;
	         	
	         }
	         return false;
         }
	?>
</ul>









