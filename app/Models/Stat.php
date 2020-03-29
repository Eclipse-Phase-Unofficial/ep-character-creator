<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Stat
 *
 * @property int    $id
 * @property string $name
 * @property string $description
 * @property string $abbreviation An enum of all the static/const values in EPStat
 * @method static Builder|Stat newModelQuery()
 * @method static Builder|Stat newQuery()
 * @method static Builder|Stat query()
 * @method static Builder|Stat whereAbbreviation($value)
 * @method static Builder|Stat whereDescription($value)
 * @method static Builder|Stat whereId($value)
 * @method static Builder|Stat whereName($value)
 * @mixin \Eloquent
 */
class Stat extends Model
{
    //
}
