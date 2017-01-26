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

        function printGear($gears,$morph,$gearType,$sectionName){
            //Generate a HTML valid Id from the section name
            $id = preg_replace("/[^A-z]/","",$sectionName);

            $listFiltered = array();
            foreach($gears as $m){
                if($m->gearType == $gearType){
                    array_push($listFiltered, $m);
                }
            }
            $formatedHtml = getFormatedGearList($listFiltered,$morph,'addSelMorphGearIcon');

            echo "<li class='foldingListSection' id='".$id."'>";
            echo $sectionName;
            echo "</li>";
            echo "<ul class='mainlist foldingList ".$id."''>";
            echo $formatedHtml;
            echo "</ul>";
        }

        printGear($gears,$morph,EPGear::$WEAPON_KINETIC_GEAR,"Kinetic Weapons");
        printGear($gears,$morph,EPGear::$WEAPON_ENERGY_GEAR,"Energy Weapons");
        printGear($gears,$morph,EPGear::$WEAPON_SPRAY_GEAR,"Spray Weapons");
        printGear($gears,$morph,EPGear::$WEAPON_SEEKER_GEAR,"Seeker Weapons");
        printGear($gears,$morph,EPGear::$WEAPON_MELEE_GEAR,"Melee Weapons");
        printGear($gears,$morph,EPGear::$WEAPON_AMMUNITION,"Ammunition");
        printGear($gears,$morph,EPGear::$WEAPON_EXPLOSIVE_GEAR,"Grenades and Missiles");
        printGear($gears,$morph,EPGear::$WEAPON_ACCESSORY,"Weapon Accessories");
        printGear($gears,$morph,EPGear::$ARMOR_GEAR,"Armor");
        printGear($gears,$morph,EPGear::$DRUG_GEAR,"Drugs");
        printGear($gears,$morph,EPGear::$POISON_GEAR,"Poisons");
        printGear($gears,$morph,EPGear::$CHEMICALS_GEAR,"Chemicals");
        printGear($gears,$morph,EPGear::$PET_GEAR,"Pets");
        printGear($gears,$morph,EPGear::$VEHICLES_GEAR,"Vehicles");
        printGear($gears,$morph,EPGear::$ROBOT_GEAR,"Robots");
        printGear($gears,$morph,EPGear::$STANDARD_GEAR,"Misc.");

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
