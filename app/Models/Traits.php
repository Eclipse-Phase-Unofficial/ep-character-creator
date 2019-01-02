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
 * @property string $desc
 * @property string|null $side
 * @property string|null $onwhat
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
    //
}
