<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SkillPrefix
 *
 * @property int $id
 * @property string $prefix
 * @property string $linkedAptitude
 * @property string $skillType
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SkillPrefix whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SkillPrefix whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SkillPrefix whereLinkedApt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SkillPrefix wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SkillPrefix whereSkillType($value)
 * @mixin \Eloquent
 */
class SkillPrefix extends Model
{
    protected $table = 'skillPrefixes';
}
