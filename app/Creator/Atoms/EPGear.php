<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

use App\Models\Gear;

/**
 * The character's gear.
 *
 * This includes morph implants, soft-gear used by the ego, and any additional item on anything.
 *
 * TODO: Subclass this to handle those cases separately.  Armor, weapons, misc physical goods, and soft gear are all extremely different things
 * Note: Implants are often just regular gear, so probably worth converting to an 'isImplant' bool
 *
 * @author reinhardt
 */
class EPGear extends EPAtom{

    static $SOFT_GEAR = "SOF";
    static $STANDARD_GEAR = "STD";
    static $WEAPON_MELEE_GEAR = "WMG";
    static $WEAPON_ENERGY_GEAR = "WEG";
    static $WEAPON_KINETIC_GEAR = "WKG";
    static $WEAPON_SPRAY_GEAR = "WSG";
    static $WEAPON_EXPLOSIVE_GEAR = "WXG";
    static $WEAPON_SEEKER_GEAR = "WSE";
    static $WEAPON_AMMUNITION = "WAM";
    static $WEAPON_ACCESSORY = "WAC";
    static $ARMOR_GEAR = "ARM";
    static $IMPLANT_GEAR = "IMG";
    static $DRUG_GEAR = "DRG";
    static $CHEMICALS_GEAR = "CHG";
    static $POISON_GEAR = "POG";
    static $PET_GEAR = "PEG";
    static $VEHICLES_GEAR = "VEG";
    static $ROBOT_GEAR = "ROG";

    //not used on the database
    static $FREE_GEAR = "FRE";

    private $armorEnergy;
    private $armorKinetic;

    /**
     * The amount of damage a weapon/ammo does.
     * Non weapons, and things which don't deal damage have this set to null!
     * Note: Used to be referred to as "degat".  French for damage.
     * @var string|null
     */
    private $damage;
    /**
     * How much armor the weapon/ammo can go through.
     * May be negative.
     * If $damage is null, then this should always be null!  Otherwise it should be an int.
     * @var int|null
     */
    private $armorPenetration;

    /**
     * An Enum of most static/const values, except the $CAN_USE ones
     * @var string
     */
    private $gearType;

    /**
     * An Enum of the Gear::ALLOWED_ static/const values
     * @var string
     */
    private $gearRestriction;

    public $armorPenetrationMorphMod;
    public $degatMorphMod;
    public $armorEnergyMorphMod;
    public $armorKineticMorphMod;

    public $armorPenetrationTraitMod;
    public $degatTraitMod;
    public $armorEnergyTraitMod;
    public $armorKineticTraitMod;

    public $armorPenetrationBackgroundMod;
    public $degatBackgroundMod;
    public $armorEnergyBackgroundMod;
    public $armorKineticBackgroundMod;

    public $armorPenetrationFactionMod;
    public $degatFactionMod;
    public $armorEnergyFactionMod;
    public $armorKineticFactionMod;

    public $armorPenetrationSoftgearMod;
    public $degatSoftgearMod;
    public $armorEnergySoftgearMod;
    public $armorKineticSoftgearMod;

    public $armorPenetrationPsyMod;
    public $degatPsyMod;
    public $armorEnergyPsyMod;
    public $armorKineticPsyMod;

    /**
     * If the player can own more than one (NOTE:  Even if they can, it's just incrementing the "occurrence" variable)
     * @var bool
     */
    private $unique;

    /**
     * @var int The number of this item the player owns.
     * This is used instead of creating multiple copies of the same gear (per morph).
     * So, if a single morph owns 2 bullets, then this would be 2.
     * However, if two morphs each own one bullet, then there would be two instances of the "bullet" EPGear.  One per morph.
     */
    private $occurrence = 1;

    /**
     * @var EPBonusMalus[]
     */
    public $bonusMalus;

    function getSavePack(): array
    {
        $savePack = parent::getSavePack();

        $savePack['armorEnergy'] =  $this->armorEnergy;
        $savePack['armorKinetic'] =  $this->armorKinetic;
        $savePack['degat'] =  $this->damage;
        $savePack['armorPenetration'] =  $this->armorPenetration;
        $savePack['gearType'] =  $this->getType();
        $savePack['gearRestriction'] =  $this->gearRestriction;
        $savePack['armorPenetrationMorphMod'] =  $this->armorPenetrationMorphMod;
        $savePack['degatMorphMod'] =  $this->degatMorphMod;
        $savePack['armorEnergyMorphMod'] =  $this->armorEnergyMorphMod;
        $savePack['armorKineticMorphMod'] =  $this->armorKineticMorphMod;
        $savePack['armorPenetrationTraitMod'] =  $this->armorPenetrationTraitMod;
        $savePack['degatTraitMod'] =  $this->degatTraitMod;
        $savePack['armorEnergyTraitMod'] =  $this->armorEnergyTraitMod;
        $savePack['armorKineticTraitMod'] =  $this->armorKineticTraitMod;
        $savePack['armorPenetrationBackgroundMod'] =  $this->armorPenetrationBackgroundMod;
        $savePack['degatBackgroundMod'] =  $this->degatBackgroundMod;
        $savePack['armorEnergyBackgroundMod'] =  $this->armorEnergyBackgroundMod;
        $savePack['armorKineticBackgroundMod'] =  $this->armorKineticBackgroundMod;
        $savePack['armorPenetrationFactionMod'] =  $this->armorPenetrationFactionMod;
        $savePack['degatFactionMod'] =  $this->degatFactionMod;
        $savePack['armorEnergyFactionMod'] =  $this->armorEnergyFactionMod;
        $savePack['armorKineticFactionMod'] =  $this->armorKineticFactionMod;
        $savePack['armorPenetrationSoftgearMod'] =  $this->armorPenetrationSoftgearMod;
        $savePack['degatSoftgearMod'] =  $this->degatSoftgearMod;
        $savePack['armorEnergySoftgearMod'] =  $this->armorEnergySoftgearMod;
        $savePack['armorKineticSoftgearMod'] =  $this->armorKineticSoftgearMod;
        $savePack['armorPenetrationPsyMod'] =  $this->armorPenetrationPsyMod;
        $savePack['degatPsyMod'] =  $this->degatPsyMod;
        $savePack['armorEnergyPsyMod'] =  $this->armorEnergyPsyMod;
        $savePack['armorKineticPsyMod'] =  $this->armorKineticPsyMod;
        $savePack['unique'] = $this->unique;
        $savePack['occurrence'] = $this->occurrence;
        $bmSavePacks = array();
        foreach($this->bonusMalus as $m){
            array_push($bmSavePacks	, $m->getSavePack());
        }
        $savePack['bmSavePacks'] = $bmSavePacks;

        return $savePack;
    }

    /**
     * @param array $an_array
     * @return EPGear
     */
    public static function __set_state(array $an_array)
    {
        $object = new self((string)$an_array['name'], '', '', 0);
        parent::set_state_helper($object, $an_array);

        $object->armorEnergy                   = (int)$an_array['armorEnergy'];
        $object->armorKinetic                  = (int)$an_array['armorKinetic'];
        $object->damage                        = $an_array['degat'];
        $object->armorPenetration              = (int)$an_array['armorPenetration'];
        $object->gearType                      = (string)$an_array['gearType'];
        $object->gearRestriction               = (string)$an_array['gearRestriction'];
        $object->armorPenetrationMorphMod      = $an_array['armorPenetrationMorphMod'];
        $object->degatMorphMod                 = $an_array['degatMorphMod'];
        $object->armorEnergyMorphMod           = $an_array['armorEnergyMorphMod'];
        $object->armorKineticMorphMod          = $an_array['armorKineticMorphMod'];
        $object->armorPenetrationTraitMod      = $an_array['armorPenetrationTraitMod'];
        $object->degatTraitMod                 = $an_array['degatTraitMod'];
        $object->armorEnergyTraitMod           = $an_array['armorEnergyTraitMod'];
        $object->armorKineticTraitMod          = $an_array['armorKineticTraitMod'];
        $object->armorPenetrationBackgroundMod = $an_array['armorPenetrationBackgroundMod'];
        $object->degatBackgroundMod            = $an_array['degatBackgroundMod'];
        $object->armorEnergyBackgroundMod      = $an_array['armorEnergyBackgroundMod'];
        $object->armorKineticBackgroundMod     = $an_array['armorKineticBackgroundMod'];
        $object->armorPenetrationFactionMod    = $an_array['armorPenetrationFactionMod'];
        $object->degatFactionMod               = $an_array['degatFactionMod'];
        $object->armorEnergyFactionMod         = $an_array['armorEnergyFactionMod'];
        $object->armorKineticFactionMod        = $an_array['armorKineticFactionMod'];
        $object->armorPenetrationSoftgearMod   = $an_array['armorPenetrationSoftgearMod'];
        $object->degatSoftgearMod              = $an_array['degatSoftgearMod'];
        $object->armorEnergySoftgearMod        = $an_array['armorEnergySoftgearMod'];
        $object->armorKineticSoftgearMod       = $an_array['armorKineticSoftgearMod'];
        $object->armorPenetrationPsyMod        = $an_array['armorPenetrationPsyMod'];
        $object->degatPsyMod                   = $an_array['degatPsyMod'];
        $object->armorEnergyPsyMod             = $an_array['armorEnergyPsyMod'];
        $object->armorKineticPsyMod            = $an_array['armorKineticPsyMod'];
        $object->unique                        = (bool)$an_array['unique'];
        foreach ($an_array['bmSavePacks'] as $m) {
            array_push($object->bonusMalus, EPBonusMalus::__set_state($m));
        }

        //This is for backwards compatibility with older saves that may not have all the data
        $object->occurrence = $an_array['occurrence'] ?? $an_array['occurence'] ?? 1;

        return $object;
    }

    /**
     * EPGear constructor.
     * @param string         $name
     * @param string         $description
     * @param string         $gearType
     * @param int            $cost
     * @param int            $armorKinetic
     * @param int            $armorEnergy
     * @param string|null    $damage
     * @param int            $armorPenetration
     * @param EPBonusMalus[] $bonusmalus
     * @param string         $gearRestriction
     * @param bool           $isUnique
     */
    function __construct(
        string $name,
        string $description,
        string $gearType,
        int $cost,
        int $armorKinetic = 0,
        int $armorEnergy = 0,
        ?string $damage = null,
        int $armorPenetration = 0,
        array $bonusmalus = array(),
        string $gearRestriction = 'EVERY',
        bool $isUnique = true
    ) {
        parent::__construct($name, $description);
        $this->gearType = $gearType;
        $this->armorKinetic = $armorKinetic;
        $this->armorEnergy = $armorEnergy;
        $this->damage = $damage;
        $this->armorPenetration = $armorPenetration;
        $this->cost = $cost;
        $this->bonusMalus = $bonusmalus;
        $this->gearRestriction = $gearRestriction;
        $this->unique = $isUnique;
        $this->armorPenetrationMorphMod = 0;
        $this->degatMorphMod = 0;
        $this->armorEnergyMorphMod = 0;
        $this->armorKineticMorphMod = 0;
        $this->armorPenetrationTraitMod = 0;
        $this->degatTraitMod = 0;
        $this->armorEnergyTraitMod = 0;
        $this->armorKineticTraitMod = 0;
        $this->armorPenetrationBackgroundMod = 0;
        $this->degatBackgroundMod = 0;
        $this->armorEnergyBackgroundMod = 0;
        $this->armorKineticBackgroundMod = 0;
        $this->armorPenetrationFactionMod = 0;
        $this->degatFactionMod = 0;
        $this->armorEnergyFactionMod = 0;
        $this->armorKineticFactionMod = 0;
        $this->armorPenetrationSoftgearMod = 0;
        $this->degatSoftgearMod = 0;
        $this->armorEnergySoftgearMod = 0;
        $this->armorKineticSoftgearMod = 0;
        $this->armorPenetrationPsyMod = 0;
        $this->degatPsyMod = 0;
        $this->armorEnergyPsyMod = 0;
        $this->armorKineticPsyMod = 0;
    }

    /**
     * If the player can purchase more than one copy of the gear
     * @return bool
     */
    public function isUnique(): bool
    {
        return $this->unique;
    }

    /**
     * @return int
     */
    public function getOccurrence(): int
    {
        return $this->occurrence;
    }

    /**
     * @param int $occurrence
     */
    public function setOccurrence(int $occurrence): void
    {
        $this->occurrence = $occurrence;
    }

    /**
     * If the gear is something implanted in a morph
     *
     * That means it's been surgically added in the case of biomorphs/podmorphs, or bolted on in the case of synthmorphs.
     * It can't be easily added or removed without a specialist.
     * @return bool
     */
    public function isImplant(): bool
    {
        return $this->getType() === EPGear::$IMPLANT_GEAR;
    }

    function getArmorEnergy(): int
    {
        return $this->armorEnergy + $this->armorEnergyMorphMod + $this->armorEnergyTraitMod + $this->armorEnergyBackgroundMod + $this->armorEnergyFactionMod + $this->armorEnergySoftgearMod + $this->armorEnergyPsyMod;
    }
    function getArmorKinetic(): int
    {
        return $this->armorKinetic + $this->armorKineticMorphMod + $this->armorKineticTraitMod + $this->armorKineticBackgroundMod + $this->armorKineticFactionMod + $this->armorKineticSoftgearMod + $this->armorKineticPsyMod;
    }
    function getArmorPenetration(): int
    {
        $armorPenetration = $this->armorPenetration ?? 0;
        return $armorPenetration + $this->armorPenetrationMorphMod + $this->armorPenetrationTraitMod + $this->armorPenetrationBackgroundMod + $this->armorPenetrationFactionMod + $this->armorPenetrationSoftgearMod + $this->armorPenetrationPsyMod;
    }

    /**
     * @return string|null
     */
    public function getDamage(): ?string
    {
        return $this->damage;
    }

    /**
     * Please avoid using this function.  Use one of the `is...()` function calls instead if possible
     * @return string One of the Gear::TYPE_... enums
     */
    public function getType(): string
    {
        return $this->gearType;
    }

    /********** Determine Morph Restrictions ****************/
    /**
     * If a Biomorph can purchase / use this gear
     * @return bool
     */
    public function isAllowedBiomorph(): bool
    {
        return ($this->gearRestriction === Gear::ALLOWED_EVERYBODY ||
            $this->gearRestriction === Gear::ALLOWED_BIOMORPH ||
            $this->gearRestriction === Gear::ALLOWED_BIOMORPH_AND_PODMORPH);
    }

    /**
     * If a Podmorph can purchase / use this gear
     * @return bool
     */
    public function isAllowedPodmorph(): bool
    {
        return ($this->gearRestriction === Gear::ALLOWED_EVERYBODY ||
            $this->gearRestriction === Gear::ALLOWED_PODMORPH ||
            $this->gearRestriction === Gear::ALLOWED_BIOMORPH_AND_PODMORPH);
    }

    /**
     * If a Synthmorph can purchase / use this gear
     * @return bool
     */
    public function isAllowedSynthmorph(): bool
    {
        return ($this->gearRestriction === Gear::ALLOWED_EVERYBODY ||
            $this->gearRestriction === Gear::ALLOWED_SYNTHMORPH ||
            $this->gearRestriction === Gear::ALLOWED_SYNTHMORPH_AND_PODMORPH);
    }
    /********** END Determine Morph Restrictions ****************/

    /**
     * Match identical gear, even if atom Uids differ
     *
     * Gear is unique by name, gearType, and gearRestriction.
     * This is more expensive than EPAtom's version, but catches duplicate gear with different Uids.
     * @param EPGear $gear
     * @return bool
     */
    public function match($gear): bool{
        if (strcasecmp($gear->getName(),$this->getName()) == 0 &&
            $gear->getType()===$this->getType() &&
            $gear->gearRestriction===$this->gearRestriction){
                return true;
        }
        return false;
    }
}
