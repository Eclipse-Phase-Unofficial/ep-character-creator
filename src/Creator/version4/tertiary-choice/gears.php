<?php
require_once '../../../php/EPCharacterCreator.php';
include('../other/bookPageLayer.php');
session_start();
$currentMorph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
?>
<label class="descriptionTitle"><?php echo $currentMorph->name; ?></label>
<ul class="mainlist" id="gears">
    <?php
        $morph = $currentMorph;
        $gears = $_SESSION['cc']->getGears();
        
        
        //WEAPON KINETIC SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='kinW'>Kinetic Weapon</label>";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$WEAPON_KINETIC_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		$formatedHtml = getFormatedGearList($listFiltered,$morph);
 		echo "<ul class='mainlist foldingList kinW'>";
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		//WEAPON ENERGY SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='engW'>Energy Weapon</label>";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$WEAPON_ENERGY_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList engW'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph);
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		//WEAPON SPRAY SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='sprW'>Spray Weapon</label>";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$WEAPON_SPRAY_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList sprW'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph);
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		//WEAPON SEEKER SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='seeW'>Seeker Weapon</label>";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$WEAPON_SEEKER_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList seeW'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph);
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		//WEAPON MELEE SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='meeW'>Melee Weapon</label>";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$WEAPON_MELEE_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList meeW'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph);
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		
 		//AMMUNITION SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='ammo'>Ammunition</label>";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$WEAPON_AMMUNITION){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList ammo'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph);
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		//WEAPON EXPLOSIVE SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='expW'>Grenades and missiles</label>";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$WEAPON_EXPLOSIVE_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList expW'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph);
 		echo $formatedHtml;
 		echo "</ul>";

 		//WEAPON ACCESSORY SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='accW'>Weapon Accessories</label>";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 			if($m->gearType == EPGear::$WEAPON_ACCESSORY){
 				array_push($listFiltered, $m);
 			}
 		}
 		echo "<ul class='mainlist foldingList accW'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph);
 		echo $formatedHtml;
 		echo "</ul>";
 		
        
        //ARMOR SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='armor'>Armor</label>";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$ARMOR_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList armor'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph);
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		
 		//DRUG SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='drug'>Drug</label>";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$DRUG_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList drug'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph);
 		echo $formatedHtml;
 		echo "</ul>";
 		

 		//POISON SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='poison'>Poison</label>";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$POISON_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList poison'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph);
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		
 		//CHEMICALS SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='chem'>Chemicals</label>";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$CHEMICALS_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList chem'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph);
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		
 		//PET SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='pets'>Pets</label>";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$PET_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList pets'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph);
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		
 		//VEHICLES SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='vehi'>Vehicles</label>";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$VEHICLES_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList vehi'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph);
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		
 		//ROBOTS SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='rob'>Robots</label>";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$ROBOT_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList rob'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph);
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		
 		//MISC. SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='misc'>Misc.</label>";
 		echo "</li>";
 		$listFiltered = array();
 		foreach($gears as $m){
 		 	if($m->gearType == EPGear::$STANDARD_GEAR){
     		 	array_push($listFiltered, $m);
 		 	}
 		}
 		echo "<ul class='mainlist foldingList misc'>";
 		$formatedHtml = getFormatedGearList($listFiltered,$morph);
 		echo $formatedHtml;
 		echo "</ul>";
 		
 		//FREE GEAR SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='free'>Free gear</label>";
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
		echo "			<span class='icone' id='addFreeMorphGear' data-icon='&#x3a;'></span>";
		echo "	</li>";
		$freeGear = $_SESSION['cc']->getCurrentMorphGears($morph->name);
		foreach($freeGear as $m){
			
 		 	if($m->gearType == EPGear::$FREE_GEAR){
     		 	echo "<li>";
                echo "		<label class='morphFreeGear remFreeGear' id='".$m->name."'>".$m->name."</label><label class='costInfo'>(".$m->getCost()." credits)</label><span class='selectedicone remFGear' data-icon='&#x39;'></span>";
				echo "</li>"; 		
 		 	}
 		}
 		echo "</ul>";	
               
        function getFormatedGearList($listFiltered,$morph){
        	 $htmlResult = "";
	         foreach($listFiltered as $m){
	            if(isGearLegal($morph,$m)){
		            $htmlResult .= "<li>";
					if(isset($morph) && $_SESSION['cc']->haveGearOnMorph($m,$morph)){
		            	if ($_SESSION['cc']->haveAdditionalGear($m,$morph)){
		                    $htmlResult .= "		<label class='morphGear selGear' id='".$m->name."'>".$m->name.getListStampHtml($m->name)."</label><label class='costInfo'>(".$m->getCost()." credits)</label><span class='selectedicone selGear selMorphGearIcon' id='".$m->name."' data-icon='&#x2b;'></span>";
		                }else{
		                    $htmlResult .= "		<label class='morphGear selGear' id='".$m->name."'>".$m->name.getListStampHtml($m->name)."</label><label class='costInfo'>(base gear)</label><span class='selectedicone selGear selMorphGearIcon' id='".$m->name."' data-icon='&#x2b;'></span>";
		                }	
	            	}else{
	                    $htmlResult .= "		<label class='morphGear' id='".$m->name."'>".$m->name.getListStampHtml($m->name)."</label><label class='costInfo'>(".$m->getCost()." credits)</label><span class='addIcon addMorphGearIcon' id='".$m->name."' data-icon='&#x3a;'></span>";
	            	}
	            	$htmlResult .= "</li>";
            	}
            }
            return $htmlResult;
        }
        
        function isGearLegal($morph,$gear){
        	//if($morph->morphType == EPMorph::$INFOMORPH) return false;
        	//Removed so infomorphs can buy gear
	        if($gear->gearRestriction == EPGear::$CAN_USE_EVERYBODY) return true;
	        else if($gear->gearRestriction == EPGear::$CAN_USE_CREATE_ONLY) return false;//this check hides gear that you want to exist, but not render on the list
	        else if($gear->gearRestriction == EPGear::$CAN_USE_BIO){
		        if($morph->morphType == EPMorph::$BIOMORPH) return true;
		        else return false;
	        }
	        else if($gear->gearRestriction == EPGear::$CAN_USE_SYNTH){
		        if($morph->morphType == EPMorph::$SYNTHMORPH) return true;
		        else return false;
	        }
	        else if($gear->gearRestriction == EPGear::$CAN_USE_POD){
		        if($morph->morphType == EPMorph::$PODMORPH) return true;
		        else return false;
	        }
	        else if($gear->gearRestriction == EPGear::$CAN_USE_BIO_POD){
		        if($morph->morphType == EPMorph::$BIOMORPH || $morph->morphType == EPMorph::$PODMORPH) return true;
		        else return false;
	        }
	        else if($gear->gearRestriction == EPGear::$CAN_USE_SYNTH_POD){
		        if($morph->morphType == EPMorph::$SYNTHMORPH || $morph->morphType == EPMorph::$PODMORPH) return true;
		        else return false;
	        }
	        return false;
	    }
	?>
</ul>
