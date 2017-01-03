<?php
require_once '../../../php/EPCharacterCreator.php';
require_once('../other/bookPageLayer.php');
require_once('../other/gearHelper.php');

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
            	echo "<li class='ai' id='".$m->name."'>";
            	echo "<span>".$m->name.getListStampHtml($m->name)."</span>";
            	echo getCostHtml($m->getCost(), $m->isInArray($defaultAi));
            	if($m->isInArray($defaultAi) || $m->isInArray($currentAis)){
            		echo "<span class='addOrSelectedIcon addSelAiIcon' id='".$m->name."' data-icon='&#x2b;'></span>";
            	}else{
            		echo "<span class='addOrSelectedIcon addSelAiIcon' id='".$m->name."' data-icon='&#x3a;'></span>";
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
	            	echo "<li class='softG' id='".$m->name."'>";
	            	echo "<span>".$m->name.getListStampHtml($m->name)."</span>";
	            	echo getCostHtml($m->getCost(), false);
	            	if($m->isInArray($currentSoftGear)){
	            		echo "<span class='addOrSelectedIcon addSelSoftGearIcon' id='".$m->name."' data-icon='&#x2b;'></span>";
	            	}
	            	else{
	            		echo "<span class='addOrSelectedIcon addSelSoftGearIcon' id='".$m->name."' data-icon='&#x3a;'></span>";
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
	?>
</ul>
