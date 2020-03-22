<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Traits
 *
 * Note:  This isn't singular since 'Trait' is a PHP reserved keyword
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property bool $isNegative
 * @property bool|null $isForMorph
 * @property int|null $cpCost
 * @property int $level
 * @property string $JustFor
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Traits whereCpCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Traits whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Traits whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Traits whereJustFor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Traits whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Traits whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Traits whereOnwhat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Traits whereSide($value)
 * @mixin \Eloquent
 */
class Traits extends Model
{
    protected $casts = [
//        'isNegative' => 'boolean', //Disabled since this does not work with "false"
    ];

    /**
     * Fix for SQLite note supporting booleans properly.
     * WARNING:  This does not affect json_encode!
     * @param $value
     * @return bool
     */
    public function getIsNegativeAttribute($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Fix for SQLite note supporting booleans properly.
     * Also handles fact this may be null!
     * WARNING:  This does not affect json_encode!
     * @param $value
     * @return bool
     */
    public function getIsForMorphAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
