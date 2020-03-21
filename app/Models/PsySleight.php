<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PsySleight
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $type
 * @property string $range
 * @property string $duration
 * @property string $action
 * @property string $level
 * @property string $strainMod
 * @property string $skillNeeded
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PsySleight whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PsySleight whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PsySleight whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PsySleight whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PsySleight whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PsySleight whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PsySleight whereRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PsySleight whereSkillNeeded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PsySleight whereStrainMod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PsySleight whereType($value)
 * @mixin \Eloquent
 */
class PsySleight extends Model
{
    protected $table = 'psySleights';
}
