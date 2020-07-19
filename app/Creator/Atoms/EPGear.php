<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

use App\Models\Gear;

/**
 * The character's gear.
 *
 * This includes morph implants, soft-gear used by the ego, and any additional item on anything.
 *
 * @author reinhardt
 * @author EmperorArthur
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

    /*
     * If this was created by a user, then a temporary model is created and used.
     * @var Gear
     */
    protected $model;

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

    /**
     * Internal function to create a temporary Gear Model that represents something player created.
     * @param string $name
     * @param int    $cost
     * @return Gear
     */
    private static function createUserGearModel(string $name, int $cost): Gear
    {
        if (empty($name)) {
            throw new \InvalidArgumentException("Name may not be empty");
        }
        $gear                   = new Gear();
        $gear->name             = $name;
        $gear->description      = "Added by the player";
        $gear->type             = Gear::TYPE_USER_CREATED;
        $gear->cost             = $cost;
        $gear->armorKinetic     = 0;
        $gear->armorEnergy      = 0;
        $gear->allowedMorphType = Gear::ALLOWED_EVERYBODY;
        $gear->isUnique         = false;
        //Hack to allow getKey() to work
        $gear->id = -crc32($name) - $cost;
        return $gear;
    }

    function getSavePack(): array
    {
        $savePack = parent::getSavePack();

        $savePack['gearType'] =  $this->getType();
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
        $model = null;
        if ((string)$an_array['gearType'] != Gear::TYPE_USER_CREATED) {
            $model = Gear::whereName((string)$an_array['name'])->first();
        }
        $object = new self($model, (string)$an_array['name'], (int)$an_array['cost']);
        parent::set_state_helper($object, $an_array);

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
        foreach ($an_array['bmSavePacks'] as $m) {
            array_push($object->bonusMalus, EPBonusMalus::__set_state($m));
        }

        //This is for backwards compatibility with older saves that may not have all the data
        $object->occurrence = $an_array['occurrence'] ?? $an_array['occurence'] ?? 1;

        return $object;
    }

    /**
     * EPGear constructor.
     * @param Gear|null $model Set to null to indicate user created gear
     * @param string    $name Only used if $model is null
     * @param int       $cost Only used if $model is null
     */
    function __construct(
        ?Gear $model,
        string $name = "",
        int $cost = 0
    ) {
        //If this is a user created gear
        if (is_null($model)) {
            $this->model = self::createUserGearModel($name, $cost);
        } else {
            $this->model = $model;
        }
        parent::__construct("Unused", "");
        $this->bonusMalus = array();

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

        foreach ($this->model->bonusMalus as $bonusMalus) {
            $this->bonusMalus [] = new EPBonusMalus($bonusMalus);
        }
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
        $armorEnergy = $this->model->armorEnergy ?? 0;
        return $armorEnergy + $this->armorEnergyMorphMod + $this->armorEnergyTraitMod + $this->armorEnergyBackgroundMod + $this->armorEnergyFactionMod + $this->armorEnergySoftgearMod + $this->armorEnergyPsyMod;
    }
    function getArmorKinetic(): int
    {
        $armorKinetic = $this->model->armorKinetic ?? 0;
        return $armorKinetic + $this->armorKineticMorphMod + $this->armorKineticTraitMod + $this->armorKineticBackgroundMod + $this->armorKineticFactionMod + $this->armorKineticSoftgearMod + $this->armorKineticPsyMod;
    }
    function getArmorPenetration(): int
    {
        $armorPenetration = $this->model->armorPenetration ?? 0;
        return $armorPenetration + $this->armorPenetrationMorphMod + $this->armorPenetrationTraitMod + $this->armorPenetrationBackgroundMod + $this->armorPenetrationFactionMod + $this->armorPenetrationSoftgearMod + $this->armorPenetrationPsyMod;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->model->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->model->description;
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->model->cost;
    }

    /**
     * If the player can purchase more than one copy of the gear
     * @return bool
     */
    public function isUnique(): bool
    {
        return $this->model->isUnique;
    }

    /**
     * @return string|null
     */
    public function getDamage(): ?string
    {
        return $this->model->damage;
    }

    /********** Type Checks ****************/
    /**
     * Please avoid using this function.  Use one of the `is...()` function calls instead if possible
     * @return string One of the Gear::TYPE_... enums
     */
    public function getType(): string
    {
        return $this->model->type;
    }
    /********** END Type Checks ****************/

    /********** Determine Morph Restrictions ****************/
    /**
     * If a Biomorph can purchase / use this gear
     * @return bool
     */
    public function isAllowedBiomorph(): bool
    {
        return $this->model->isAllowedBiomorph();
    }

    /**
     * If a Podmorph can purchase / use this gear
     * @return bool
     */
    public function isAllowedPodmorph(): bool
    {
        return $this->model->isAllowedPodmorph();
    }

    /**
     * If a Synthmorph can purchase / use this gear
     * @return bool
     */
    public function isAllowedSynthmorph(): bool
    {
        return $this->model->isAllowedSynthmorph();
    }
    /********** END Determine Morph Restrictions ****************/

    /**
     * Match identical gear, even if atom Uids differ
     *
     * Match by model keys.
     * @param EPGear $gear
     * @return bool
     */
    public function match($gear): bool
    {
        return $this->model->getKey() === $gear->model->getKey();
    }
}
