<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Morph
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $type
 * @property string $gender
 * @property int $age
 * @property int $maxApptitude
 * @property int $durablility
 * @property int $cpCost
 * @property int $creditCost
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Morph whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Morph whereCpCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Morph whereCreditCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Morph whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Morph whereDurablility($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Morph whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Morph whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Morph whereMaxApptitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Morph whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Morph whereType($value)
 * @mixin \Eloquent
 */
class Morph extends Model
{
    protected $casts = [
        'creditCost' => 'integer',
    ];
}
