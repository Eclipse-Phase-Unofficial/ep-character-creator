<?php
declare(strict_types=1);

use App\Models\Gear
use App\Creator\DisplayHelpers\Helpers;

$currentMorph = creator()->getCurrentMorphsByName((string) session('currentMorph'));
$gears = EpDatabase()->getGears();
?>
<label class="descriptionTitle"><?php echo $currentMorph->getName(); ?></label>
<ul class="mainlist" id="gears">
    <?php
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, Gear::TYPE_WEAPON_KINETIC, "Kinetic Weapons");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, Gear::TYPE_WEAPON_ENERGY, "Energy Weapons");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, Gear::TYPE_WEAPON_SPRAY, "Spray Weapons");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, Gear::TYPE_WEAPON_SEEKER, "Seeker Weapons");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, Gear::TYPE_WEAPON_MELEE, "Melee Weapons");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, Gear::TYPE_WEAPON_AMMUNITION, "Ammunition");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, Gear::TYPE_WEAPON_EXPLOSIVE, "Grenades and Missiles");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, Gear::TYPE_WEAPON_ACCESSORY, "Weapon Accessories");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, Gear::TYPE_ARMOR, "Armor");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, Gear::TYPE_DRUG, "Drugs");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, Gear::TYPE_POISON, "Poisons");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, Gear::TYPE_CHEMICAL, "Chemicals");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, Gear::TYPE_PET, "Pets");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, Gear::TYPE_VEHICLE, "Vehicles");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, Gear::TYPE_ROBOT, "Robots");
    echo Helpers::getGearSection(creator(), $gears, $currentMorph, Gear::TYPE_STANDARD, "Misc.");

    //FREE GEAR SECTION
    echo Helpers::getFreeGear(creator()->getCurrentMorphGears($currentMorph->getName()), false);
    ?>
</ul>
