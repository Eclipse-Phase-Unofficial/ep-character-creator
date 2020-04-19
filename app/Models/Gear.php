<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\Gear
 * TODO:  Replace all the enums possible in the code with "is..." checks instead.
 *
 * @property int                          $id
 * @property string                       $name
 * @property string                       $description
 * @property string                       $type
 * @property int                          $cost
 * @property int                          $armorKinetic
 * @property int                          $armorEnergy
 * @property string|null                  $damage           The amount of damage a weapon/ammo does.
 *                                                          Non weapons, and things which don't deal damage have this set to null!
 *                                                          Note: Used to be referred to as "degat".  French for damage.
 * @property int|null                     $armorPenetration How much armor the weapon/ammo can go through. (May be negative)
 *                                                          If $damage is null, then this should always be null!  Otherwise it should be an int.
 * @property string                       $allowedMorphType The types of morphs that can use this particular piece of gear.  Special handling is given to Software.
 * @property bool                         $isUnique         If the player can own more than one per Morph.
 * @property-read Collection|BonusMalus[] $bonusMalus
 * @method static Builder|Gear newModelQuery()
 * @method static Builder|Gear newQuery()
 * @method static Builder|Gear query()
 * @method static Builder|Gear whereAllowedMorphType($value)
 * @method static Builder|Gear whereArmorEnergy($value)
 * @method static Builder|Gear whereArmorKinetic($value)
 * @method static Builder|Gear whereArmorPenetration($value)
 * @method static Builder|Gear whereCost($value)
 * @method static Builder|Gear whereDamage($value)
 * @method static Builder|Gear whereDescription($value)
 * @method static Builder|Gear whereId($value)
 * @method static Builder|Gear whereIsUnique($value)
 * @method static Builder|Gear whereName($value)
 * @method static Builder|Gear whereType($value)
 * @mixin \Eloquent
 */
class Gear extends Model
{
    //The Enums for $type
    const TYPE_ARMOR             = "ARM";
    const TYPE_CHEMICAL          = "CHG";
    const TYPE_DRUG              = "DRG";
    const TYPE_IMPLANT           = "IMG";
    const TYPE_PET               = "PEG";
    const TYPE_POISON            = "POG";
    const TYPE_SOFTWARE          = "SOF";
    const TYPE_STANDARD          = "STD";
    const TYPE_ROBOT             = "ROG";
    const TYPE_USER_CREATED      = "FRE"; // Should never be in the database! Formerly known as "Free Gear"
    const TYPE_VEHICLE           = "VEG";
    const TYPE_WEAPON_ACCESSORY  = "WAC";  // Not actually a weapon, and does not NORMALLY* deal damage. *(one exception exists)
    const TYPE_WEAPON_AMMUNITION = "WAM";
    const TYPE_WEAPON_ENERGY     = "WEG";
    const TYPE_WEAPON_EXPLOSIVE  = "WXG";
    const TYPE_WEAPON_KINETIC    = "WKG";
    const TYPE_WEAPON_MELEE      = "WMG";
    const TYPE_WEAPON_SEEKER     = "WSE";
    const TYPE_WEAPON_SPRAY      = "WSG";

    //The Enums for $allowedMorphType
    const ALLOWED_BIOMORPH                = "BIO";
    const ALLOWED_BIOMORPH_AND_PODMORPH   = "BIOPOD";
    const ALLOWED_CREATION_ONLY           = "CREATION"; //For gear that the morph comes with, but a user CAN NOT add later. (Not used in database)
    const ALLOWED_EVERYBODY               = "EVERY";
    const ALLOWED_PODMORPH                = "POD";
    const ALLOWED_SYNTHMORPH              = "SYNTH";
    const ALLOWED_SYNTHMORPH_AND_PODMORPH = "SYNTHPOD";

    protected $table = 'gear';

    protected $casts = [
        'cost'             => 'integer',
        'armorKinetic'     => 'integer',
        'armorEnergy'      => 'integer',
        'armorPenetration' => 'integer', //Can also be null
//        'isUnique' => 'boolean', //Disabled since this does not work with "false"
    ];

    /**
     * Fix for SQLite note supporting booleans properly.
     * WARNING:  This does not affect json_encode!
     * @param $value
     * @return bool
     */
    public function getIsUniqueAttribute($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * WARNING: Make sure to look at the "pivot" property
     *          It's possible to have duplicate's and that's tallied rather than having duplicate entries.
     * @return BelongsToMany
     */
    public function bonusMalus()
    {
        return $this->belongsToMany(BonusMalus::class,
            'bonusMalus_gear',
            'gear_name',
            'bonusMalus_name',
            'name',
            'name'
        )->withPivot('occurrence');
    }

    /**
     * Set sanity check to run every time a model is retrieved from the database.
     */
    public static function boot()
    {
        parent::boot();
        self::retrieved(function (self $model) {
            try {
                $model->sanityCheck();
            } catch (Exception $exception) {
                Log::error($exception->getMessage());
            }
        });
    }

    /**
     * Make sure the underlying data meets certain criteria
     * @throws Exception
     */
    public function sanityCheck()
    {
        //If $damage is not null, then $armorPenetration must NOT be null
        if (!is_null($this->damage) && is_null($this->armorPenetration)) {
            throw new Exception("Gear " . $this->name . "(" . $this->id . ") has a damage value, but armorPenetration is null!");
        }
        //If $armorPenetration is not null, then $damage must NOT be null
        if (!is_null($this->armorPenetration) && is_null($this->damage)) {
            throw new Exception("Gear " . $this->name . "(" . $this->id . ") has a armorPenetration value, but damage is null!");
        }

        //Check the type to make sure that All Weapons have damage associated with them!
        //Note:  Things other than physical weapons also have damage.
        if ($this->isWeaponType()) {
            if (is_null($this->armorPenetration)) {
                throw new Exception("Gear " . $this->name . "(" . $this->id . ") is a weapon, but damage is null!");
            }
        }

        //There should never be anything user created in the database
        if ($this->isUserCreated())
        {
            throw new Exception("Gear " . $this->name . "(" . $this->id . ") is listed as user created!");
        }
    }

    //************************************************************
    // Begin $type Checks
    //************************************************************

    /**
     * If the Gear provides protection (including implants, but excluding Vehicles)
     *
     * Vehicles are a a special case, which is why they are excluded (for now).
     *
     * This is a departure from old behavior, if you would like that functionality, please use `isArmorType()`
     * NOTE:  Consider re-naming this to "providesProtection"
     * @return bool
     */
    public function isArmor(): bool
    {
        if ($this->isVehicle()) {
            return false;
        }
        return $this->armorKinetic > 0 || $this->armorEnergy > 0;
    }

    /**
     * If the Gear is a piece of armor.
     *
     * Since this is used for categorization, Implants are currently not considered Armor!
     * @return bool
     */
    public function isArmorType(): bool
    {
        return $this->type === Gear::TYPE_ARMOR;
    }

    public function isChemical(): bool
    {
        return $this->type === Gear::TYPE_CHEMICAL;
    }

    public function isDrug(): bool
    {
        return $this->type === Gear::TYPE_DRUG;
    }

    /**
     * If the gear is something implanted in a morph
     *
     * That means it's been surgically added in the case of biomorphs/podmorphs, or bolted on in the case of synthmorphs.
     * It can't be easily added or removed without a specialist.
     *
     * Note:  Implants can be weapons or armor, but are not something that can be easily added or removed.
     * @return bool
     */
    public function isImplant(): bool
    {
        return $this->type === Gear::TYPE_IMPLANT;
    }

    public function isPet(): bool
    {
        return $this->type === Gear::TYPE_PET;
    }

    public function isPoison(): bool
    {
        return $this->type === Gear::TYPE_POISON;
    }

    /**
     * If the gear is purely digital.
     *
     * This means that it is attached to the Ego instead of the Morph.
     * Note: Software can deal damage, so may not be a physical weapon, but **may** be a weapon.
     * See `isWeapon()` vs `isWeaponType()`.
     * @return bool
     */
    public function isSoftware(): bool
    {
        return $this->type === Gear::TYPE_SOFTWARE;
    }

    public function isStandard(): bool
    {
        return $this->type === Gear::TYPE_STANDARD;
    }

    public function isRobot(): bool
    {
        return $this->type === Gear::TYPE_ROBOT;
    }

    /**
     * This should never be true, but is used by the sanity check.
     * @return bool
     */
    public function isUserCreated(): bool
    {
        return $this->type === Gear::TYPE_USER_CREATED;
    }

    /**
     * If the "Gear" is really a vehicle of some sort.
     *
     * This is important since vehicles can do damage, and have armor.
     * However, they are NOT weapons or armor.
     * @return bool
     */
    public function isVehicle(): bool
    {
        return $this->type === Gear::TYPE_VEHICLE;
    }

    /**
     * If the Gear is a weapon/ammo or not.
     *
     * Vehicles are NOT weapons, but may still do damage.
     * Cyber (non physical) weapons do exist!
     * In addition Implants, Vehicles, Soft Gear, Poisons, and even Drugs can also have damage, and so are counted as weapons.
     *
     * This is a departure from old behavior, if you would like that functionality, please use `isWeaponType()`
     * NOTE:  Consider re-naming this to "dealsDamage"
     * @return bool
     */
    public function isWeapon(): bool
    {
        if ($this->isVehicle()) {
            return false;
        }
        return !is_null($this->damage);
    }

    /**
     * If the Gear is an actual physical weapon (or ammunition)
     *
     * This does NOT include Cyber weapons, Poisons, Implanted Weapons, etc...
     * @return bool
     */
    public function isWeaponType(): bool
    {
        return in_array($this->type, [
            Gear::TYPE_WEAPON_AMMUNITION,
            Gear::TYPE_WEAPON_ENERGY,
            Gear::TYPE_WEAPON_EXPLOSIVE,
            Gear::TYPE_WEAPON_KINETIC,
            Gear::TYPE_WEAPON_MELEE,
            Gear::TYPE_WEAPON_SPRAY,
            Gear::TYPE_WEAPON_SEEKER,
        ]);
    }

    public function isWeaponAccessory(): bool
    {
        return $this->type === Gear::TYPE_WEAPON_ACCESSORY;
    }

    public function isWeaponAmmunition(): bool
    {
        return $this->type === Gear::TYPE_WEAPON_AMMUNITION;
    }

    public function isWeaponEnergy(): bool
    {
        return $this->type === Gear::TYPE_WEAPON_ENERGY;
    }

    public function isWeaponExplosive(): bool
    {
        return $this->type === Gear::TYPE_WEAPON_EXPLOSIVE;
    }

    public function isWeaponKinetic(): bool
    {
        return $this->type === Gear::TYPE_WEAPON_KINETIC;
    }

    public function isWeaponMelee(): bool
    {
        return $this->type === Gear::TYPE_WEAPON_MELEE;
    }

    public function isWeaponSeeker(): bool
    {
        return $this->type === Gear::TYPE_WEAPON_SEEKER;
    }

    public function isWeaponSpray(): bool
    {
        return $this->type === Gear::TYPE_WEAPON_SPRAY;
    }

    //************************************************************
    // End $type Checks
    // Begin $allowedMorphType Checks
    //************************************************************

    /**
     * If a Biomorph can purchase / use this gear
     * @return bool
     */
    public function isAllowedBiomorph(): bool
    {
        return ($this->allowedMorphType === Gear::ALLOWED_EVERYBODY ||
            $this->allowedMorphType === Gear::ALLOWED_BIOMORPH ||
            $this->allowedMorphType === Gear::ALLOWED_BIOMORPH_AND_PODMORPH);
    }

    /**
     * If a Podmorph can purchase / use this gear
     * @return bool
     */
    public function isAllowedPodmorph(): bool
    {
        return ($this->allowedMorphType === Gear::ALLOWED_EVERYBODY ||
            $this->allowedMorphType === Gear::ALLOWED_PODMORPH ||
            $this->allowedMorphType === Gear::ALLOWED_BIOMORPH_AND_PODMORPH);
    }

    /**
     * If a Synthmorph can purchase / use this gear
     * @return bool
     */
    public function isAllowedSynthmorph(): bool
    {
        return ($this->allowedMorphType === Gear::ALLOWED_EVERYBODY ||
            $this->allowedMorphType === Gear::ALLOWED_SYNTHMORPH ||
            $this->allowedMorphType === Gear::ALLOWED_SYNTHMORPH_AND_PODMORPH);
    }
}
