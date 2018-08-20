<?php
declare(strict_types=1);

use App\Creator\Atoms\EPGear;
use App\Creator\DisplayHelpers\Helpers;

$currentMorph = creator()->getCurrentMorphsByName($_SESSION['currentMorph']);
$gears = EpDatabase()->getGears();
?>
<label class="descriptionTitle"><?php echo $currentMorph->name; ?></label>
<ul class="mainlist" id="gears">
    <?php
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, EPGear::$WEAPON_KINETIC_GEAR, "Kinetic Weapons");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, EPGear::$WEAPON_ENERGY_GEAR, "Energy Weapons");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, EPGear::$WEAPON_SPRAY_GEAR, "Spray Weapons");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, EPGear::$WEAPON_SEEKER_GEAR, "Seeker Weapons");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, EPGear::$WEAPON_MELEE_GEAR, "Melee Weapons");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, EPGear::$WEAPON_AMMUNITION, "Ammunition");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, EPGear::$WEAPON_EXPLOSIVE_GEAR, "Grenades and Missiles");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, EPGear::$WEAPON_ACCESSORY, "Weapon Accessories");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, EPGear::$ARMOR_GEAR, "Armor");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, EPGear::$DRUG_GEAR, "Drugs");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, EPGear::$POISON_GEAR, "Poisons");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, EPGear::$CHEMICALS_GEAR, "Chemicals");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, EPGear::$PET_GEAR, "Pets");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, EPGear::$VEHICLES_GEAR, "Vehicles");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, EPGear::$ROBOT_GEAR, "Robots");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, EPGear::$STANDARD_GEAR, "Misc.");

    //FREE GEAR SECTION
    echo Helpers::getFreeGear(creator()->getCurrentMorphGears($currentMorph->name), false);
    ?>
</ul>
