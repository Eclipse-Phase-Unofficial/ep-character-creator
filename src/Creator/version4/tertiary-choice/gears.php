<?php
require_once '../../../php/EPCharacterCreator.php';
require_once '../other/gearHelper.php';
session_start();
$currentMorph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
?>
<label class="descriptionTitle"><?php echo $currentMorph->name; ?></label>
<ul class="mainlist" id="gears">
    <?php
        $morph = $currentMorph;
        $gears = $_SESSION['cc']->getGears();
        
        
        //WEAPON KINETIC SECTION
 		echo "<li class='foldingListSection' id='kinW'>";
 		echo "Kinetic Weapons";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$WEAPON_KINETIC_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		$formatedHtml = getFormatedGearList($listFiltered,$morph,'addSelMorphGearIcon');
 		echo "<ul class='mainlist foldingList kinW'>";
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		//WEAPON ENERGY SECTION
 		echo "<li class='foldingListSection' id='engW'>";
 		echo "Energy Weapons";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$WEAPON_ENERGY_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList engW'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph,'addSelMorphGearIcon');
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		//WEAPON SPRAY SECTION
 		echo "<li class='foldingListSection' id='sprW'>";
 		echo "Spray Weapons";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$WEAPON_SPRAY_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList sprW'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph,'addSelMorphGearIcon');
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		//WEAPON SEEKER SECTION
 		echo "<li class='foldingListSection' id='seeW'>";
 		echo "Seeker Weapons";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$WEAPON_SEEKER_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList seeW'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph,'addSelMorphGearIcon');
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		//WEAPON MELEE SECTION
 		echo "<li class='foldingListSection' id='meeW'>";
 		echo "Melee Weapons";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$WEAPON_MELEE_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList meeW'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph,'addSelMorphGearIcon');
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		
 		//AMMUNITION SECTION
 		echo "<li class='foldingListSection' id='ammo'>";
 		echo "Ammunition";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$WEAPON_AMMUNITION){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList ammo'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph,'addSelMorphGearIcon');
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		//WEAPON EXPLOSIVE SECTION
 		echo "<li class='foldingListSection' id='expW'>";
 		echo "Grenades and missiles";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$WEAPON_EXPLOSIVE_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList expW'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph,'addSelMorphGearIcon');
 		echo $formatedHtml;
 		echo "</ul>";

 		//WEAPON ACCESSORY SECTION
 		echo "<li class='foldingListSection' id='accW'>";
 		echo "Weapon Accessories";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 			if($m->gearType == EPGear::$WEAPON_ACCESSORY){
 				array_push($listFiltered, $m);
 			}
 		}
 		echo "<ul class='mainlist foldingList accW'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph,'addSelMorphGearIcon');
 		echo $formatedHtml;
 		echo "</ul>";
 		
        
        //ARMOR SECTION
 		echo "<li class='foldingListSection' id='armor'>";
 		echo "Armor";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$ARMOR_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList armor'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph,'addSelMorphGearIcon');
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		
 		//DRUG SECTION
 		echo "<li class='foldingListSection' id='drug'>";
 		echo "Drugs";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$DRUG_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList drug'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph,'addSelMorphGearIcon');
 		echo $formatedHtml;
 		echo "</ul>";
 		

 		//POISON SECTION
 		echo "<li class='foldingListSection' id='poison'>";
 		echo "Poisons";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$POISON_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList poison'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph,'addSelMorphGearIcon');
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		
 		//CHEMICALS SECTION
 		echo "<li class='foldingListSection' id='chem'>";
 		echo "Chemicals";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$CHEMICALS_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList chem'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph,'addSelMorphGearIcon');
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		
 		//PET SECTION
 		echo "<li class='foldingListSection' id='pets'>";
 		echo "Pets";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$PET_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList pets'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph,'addSelMorphGearIcon');
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		
 		//VEHICLES SECTION
 		echo "<li class='foldingListSection' id='vehi'>";
 		echo "Vehicles";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$VEHICLES_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList vehi'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph,'addSelMorphGearIcon');
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		
 		//ROBOTS SECTION
 		echo "<li class='foldingListSection' id='rob'>";
 		echo "Robots";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$ROBOT_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList rob'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph,'addSelMorphGearIcon');
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		
 		//MISC. SECTION
 		echo "<li class='foldingListSection' id='misc'>";
 		echo "Misc.";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$STANDARD_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList misc'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph,'addSelMorphGearIcon');
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		//FREE GEAR SECTION
 		echo "<li class='foldingListSection' id='free'>";
 		echo "Free Gear";
 		echo "</li>";
 		echo "<ul class='mainlist foldingList free' id='freeGear'>";
 		echo "	<li>";
 		echo "			<input type='text' id='freeMorphGearToAdd' placeholder='Gear Name'/>";
 		echo "			<select id='freeMorphGearPrice'>";
 		echo "					<option value=".EPCreditCost::$LOW.">".EPCreditCost::$LOW."</option>";
 		echo "					<option value=".EPCreditCost::$MODERATE.">".EPCreditCost::$MODERATE."</option>";
 		echo "					<option value=".EPCreditCost::$HIGH.">".EPCreditCost::$HIGH."</option>";
 		echo "					<option value=".EPCreditCost::$EXPENSIVE.">".EPCreditCost::$EXPENSIVE."</option>";
 		echo "					<option value=".EPCreditCost::$VERY_EXPENSIVE.">".EPCreditCost::$VERY_EXPENSIVE."</option>";
 		echo "					<option value=".EPCreditCost::$EXTREMELY_EXPENSIVE.">".EPCreditCost::$EXTREMELY_EXPENSIVE."</option>";
 		echo "			</select>";
		echo "			<span class='addOrSelectedIcon' id='addFreeMorphGear' data-icon='&#x3a;'></span>";
		echo "	</li>";
		$freeGear = $_SESSION['cc']->getCurrentMorphGears($morph->name);
		foreach($freeGear as $m){
			
 		 	if($m->gearType == EPGear::$FREE_GEAR){
     		 	echo "<li>";
                echo "		<label class='morphFreeGear remFreeGear' id='".$m->name."'>".$m->name."</label><label class='costInfo'>(".$m->getCost()." credits)</label><span class='addOrSelectedIcon remFGear' data-icon='&#x39;'></span>";
				echo "</li>"; 		
 		 	}
 		}
 		echo "</ul>";
	?>
</ul>
