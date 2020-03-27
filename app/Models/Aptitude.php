<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Aptitude
 *
 * @property int    $id
 * @property string $name
 * @property string $description
 * @property string $abbreviation
 * @method static Builder|Aptitude newModelQuery()
 * @method static Builder|Aptitude newQuery()
 * @method static Builder|Aptitude query()
 * @method static Builder|Aptitude whereAbbreviation($value)
 * @method static Builder|Aptitude whereDescription($value)
 * @method static Builder|Aptitude whereId($value)
 * @method static Builder|Aptitude whereName($value)
 * @mixin \Eloquent
 */
class Aptitude extends Model
{
    //
}
