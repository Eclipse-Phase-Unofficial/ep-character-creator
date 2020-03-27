<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Reputation
 *
 * @property int    $id
 * @property string $name
 * @property string $description
 * @method static Builder|Reputation newModelQuery()
 * @method static Builder|Reputation newQuery()
 * @method static Builder|Reputation query()
 * @method static Builder|Reputation whereDescription($value)
 * @method static Builder|Reputation whereId($value)
 * @method static Builder|Reputation whereName($value)
 * @mixin \Eloquent
 */
class Reputation extends Model
{
    //
}
