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
 * @property bool $isActive
 * @property bool $isDefaultable
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
    protected $casts = [
//        'isActive' => 'boolean', //Disabled since this does not work with "false"
//        'isDefaultable' => 'boolean', //Disabled since this does not work with "false"
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

    /**
     * Fix for SQLite note supporting booleans properly.
     * WARNING:  This does not affect json_encode!
     * @param $value
     * @return bool
     */
    public function getIsDefaultableAttribute($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function aptitude()
    {
        return $this->belongsTo(Aptitude::class, 'linkedAptitude', 'abbreviation');
    }
}
