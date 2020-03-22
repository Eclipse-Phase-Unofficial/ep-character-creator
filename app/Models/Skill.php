<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Skill
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $linkedAptitude
 * @property string $prefix
 * @property string $skillType
 * @property string $defaultable
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Skill whereDefaultable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Skill whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Skill whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Skill whereLinkedApt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Skill whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Skill wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Skill whereSkillType($value)
 * @mixin \Eloquent
 */
class Skill extends Model
{
    //
}
