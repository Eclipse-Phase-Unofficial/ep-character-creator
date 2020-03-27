<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Gear
 *
 * @property int                          $id
 * @property string                       $name
 * @property string                       $description
 * @property string                       $type
 * @property int                          $cost
 * @property int                          $armorKinetic
 * @property int                          $armorEnergy
 * @property string|null                  $damage
 * @property int|null                     $armorPenetration
 * @property string                       $allowedMorphType
 * @property bool                         $isUnique
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
    protected $table = 'gear';

    protected $casts = [
        'cost'         => 'integer',
        'armorKinetic' => 'integer',
        'armorEnergy'  => 'integer',
//        'damage' => 'integer', //This can also be null
//        'armorPenetration' => 'integer', //This can also be null
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
}
