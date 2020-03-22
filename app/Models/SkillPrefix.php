<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SkillPrefix
 *
 * @property int $id
 * @property string $name
 * @property string $linkedAptitude
 * @property bool $isActive
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

    protected $casts = [
//        'isActive' => 'boolean', //Disabled since this does not work with "false"
    ];

    /**
     * Fix for SQLite note supporting booleans properly.
     * WARNING:  This does not affect json_encode!
     * @param $value
     * @return bool
     */
    public function getIsActiveAttribute($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
