<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Gear
 *
 * @property int         $id
 * @property string      $name
 * @property string      $description
 * @property string      $type
 * @property int         $cost
 * @property int         $armorKinetic
 * @property int         $armorEnergy
 * @property string|null $damage
 * @property int|null    $armorPenetration
 * @property string      $JustFor
 * @property bool        $isUnique
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gear whereArmorEnergy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gear whereArmorKinetic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gear whereArmorPene($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gear whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gear whereDegat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gear whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gear whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gear whereJustFor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gear whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gear whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gear whereUnique($value)
 * @mixin \Eloquent
 */
class Gear extends Model
{
    protected $table = 'gear';

    protected $casts = [
        'armorPenetration' => 'integer',
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
}
