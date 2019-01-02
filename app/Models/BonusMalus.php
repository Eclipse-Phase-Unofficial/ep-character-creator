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
 * @property string $tragetForCh
 * @property string $typeTarget
 * @property string $onCost
 * @property string $multiOccur
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
}
