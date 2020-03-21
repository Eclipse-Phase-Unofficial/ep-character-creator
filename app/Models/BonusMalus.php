<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BonusMalus
 *
 * @property int $id
 * @property string $name
 * @property string $desc
 * @property string $type
 * @property string $target
 * @property float $value
 * @property string $targetForChoice
 * @property string $typeTarget
 * @property bool $isCostModifier
 * @property int $requiredSelections
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusMalus whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusMalus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusMalus whereMultiOccur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusMalus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusMalus whereOnCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusMalus whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusMalus whereTragetForCh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusMalus whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusMalus whereTypeTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusMalus whereValue($value)
 * @mixin \Eloquent
 */
class BonusMalus extends Model
{
    protected $table = 'bonusMalus';

    protected $casts = [
//        'isCostModifier' => 'boolean',  //Disabled since this does not work with "false"
        'requiredSelections' => 'integer',
    ];

    /**
     * Fix for SQLite note supporting booleans properly.
     * WARNING:  This does not affect json_encode!
     * @param $value
     * @return bool
     */
    public function getIsCostModifierAttribute($value){
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
