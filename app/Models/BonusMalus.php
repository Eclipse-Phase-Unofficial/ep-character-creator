<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BonusMalus
 *
 * @property int    $id
 * @property string $name
 * @property string $description
 * @property string $type
 * @property string $target
 * @property int    $value
 * @property string $targetForChoice
 * @property string $typeTarget
 * @property bool   $isCostModifier
 * @property int    $requiredSelections
 * @method static Builder|BonusMalus newModelQuery()
 * @method static Builder|BonusMalus newQuery()
 * @method static Builder|BonusMalus query()
 * @method static Builder|BonusMalus whereDescription($value)
 * @method static Builder|BonusMalus whereId($value)
 * @method static Builder|BonusMalus whereIsCostModifier($value)
 * @method static Builder|BonusMalus whereName($value)
 * @method static Builder|BonusMalus whereRequiredSelections($value)
 * @method static Builder|BonusMalus whereTarget($value)
 * @method static Builder|BonusMalus whereTargetForChoice($value)
 * @method static Builder|BonusMalus whereType($value)
 * @method static Builder|BonusMalus whereTypeTarget($value)
 * @method static Builder|BonusMalus whereValue($value)
 * @mixin \Eloquent
 */
class BonusMalus extends Model
{
    protected $table = 'bonusMalus';

    protected $casts = [
        'value'              => 'integer',
//        'isCostModifier' => 'boolean',  //Disabled since this does not work with "false"
        'requiredSelections' => 'integer',
    ];

    /**
     * Fix for SQLite note supporting booleans properly.
     * WARNING:  This does not affect json_encode!
     * @param $value
     * @return bool
     */
    public function getIsCostModifierAttribute($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
