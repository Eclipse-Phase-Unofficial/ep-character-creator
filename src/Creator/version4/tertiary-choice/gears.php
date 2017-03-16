<?php
require_once '../../../php/EPCharacterCreator.php';
require_once '../other/gearHelper.php';

session_start();

$currentMorph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
$gears = $_SESSION['cc']->getGears();
?>
<label class="descriptionTitle"><?php echo $currentMorph->name; ?></label>
<ul class="mainlist" id="gears">
    <?php

        echo getGearSection($gears,$currentMorph,EPGear::$WEAPON_KINETIC_GEAR,"Kinetic Weapons");
        echo getGearSection($gears,$currentMorph,EPGear::$WEAPON_ENERGY_GEAR,"Energy Weapons");
        echo getGearSection($gears,$currentMorph,EPGear::$WEAPON_SPRAY_GEAR,"Spray Weapons");
        echo getGearSection($gears,$currentMorph,EPGear::$WEAPON_SEEKER_GEAR,"Seeker Weapons");
        echo getGearSection($gears,$currentMorph,EPGear::$WEAPON_MELEE_GEAR,"Melee Weapons");
        echo getGearSection($gears,$currentMorph,EPGear::$WEAPON_AMMUNITION,"Ammunition");
        echo getGearSection($gears,$currentMorph,EPGear::$WEAPON_EXPLOSIVE_GEAR,"Grenades and Missiles");
        echo getGearSection($gears,$currentMorph,EPGear::$WEAPON_ACCESSORY,"Weapon Accessories");
        echo getGearSection($gears,$currentMorph,EPGear::$ARMOR_GEAR,"Armor");
        echo getGearSection($gears,$currentMorph,EPGear::$DRUG_GEAR,"Drugs");
        echo getGearSection($gears,$currentMorph,EPGear::$POISON_GEAR,"Poisons");
        echo getGearSection($gears,$currentMorph,EPGear::$CHEMICALS_GEAR,"Chemicals");
        echo getGearSection($gears,$currentMorph,EPGear::$PET_GEAR,"Pets");
        echo getGearSection($gears,$currentMorph,EPGear::$VEHICLES_GEAR,"Vehicles");
        echo getGearSection($gears,$currentMorph,EPGear::$ROBOT_GEAR,"Robots");
        echo getGearSection($gears,$currentMorph,EPGear::$STANDARD_GEAR,"Misc.");

        //FREE GEAR SECTION
        echo getFreeGear($_SESSION['cc']->getCurrentMorphGears($currentMorph->name),False);
    ?>
</ul>
