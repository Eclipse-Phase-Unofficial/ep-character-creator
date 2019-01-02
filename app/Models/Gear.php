<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Gear
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $type
 * @property int $cost
 * @property int $armorKinetic
 * @property int $armorEnergy
 * @property string $degat
 * @property int $armorPene
 * @property string $JustFor
 * @property string $unique
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
}
