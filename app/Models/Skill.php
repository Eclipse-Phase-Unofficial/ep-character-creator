<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Skill
 *
 * @property int                   $id
 * @property string                $name
 * @property string                $description
 * @property string                $aptitude_abbreviation
 * @property string|null           $prefix_name
 * @property bool                  $isActive              If it's an Active skill or a Knowledge skill
 * @property bool                  $isDefaultable         Non defaultable skills require the player to have at least one skill point in that skill to use them.
 * @property-read Aptitude         $aptitude
 * @property-read SkillPrefix|null $prefix
 * @method static Builder|Skill newModelQuery()
 * @method static Builder|Skill newQuery()
 * @method static Builder|Skill query()
 * @method static Builder|Skill whereAptitudeAbbreviation($value)
 * @method static Builder|Skill whereDescription($value)
 * @method static Builder|Skill whereId($value)
 * @method static Builder|Skill whereIsActive($value)
 * @method static Builder|Skill whereIsDefaultable($value)
 * @method static Builder|Skill whereName($value)
 * @method static Builder|Skill wherePrefixName($value)
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

    /**
     * Determines the base skill level before bonusMalus, Language bonus, or skill points are taken into account
     * @return BelongsTo
     */
    public function aptitude()
    {
        return $this->belongsTo(Aptitude::class, 'aptitude_abbreviation', 'abbreviation');
    }

    /**
     * @return BelongsTo
     */
    public function prefix()
    {
        return $this->belongsTo(SkillPrefix::class, 'prefix_name', 'name');
    }
}
