<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\SkillPrefix
 *
 * @property int           $id
 * @property string        $name
 * @property string        $aptitude_abbreviation
 * @property bool          $isActive              If it's an Active skill or a Knowledge skill
 * @property string        $description
 * @property-read Aptitude $aptitude
 * @method static Builder|SkillPrefix newModelQuery()
 * @method static Builder|SkillPrefix newQuery()
 * @method static Builder|SkillPrefix query()
 * @method static Builder|SkillPrefix whereAptitudeAbbreviation($value)
 * @method static Builder|SkillPrefix whereDescription($value)
 * @method static Builder|SkillPrefix whereId($value)
 * @method static Builder|SkillPrefix whereIsActive($value)
 * @method static Builder|SkillPrefix whereName($value)
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

    /**
     * Determines the base skill level before bonusMalus, Language bonus, or skill points are taken into account
     *
     * Used here because all skills (including user created ones) inherit this.
     *
     * @return BelongsTo
     */
    public function aptitude()
    {
        return $this->belongsTo(Aptitude::class, 'aptitude_abbreviation', 'abbreviation');
    }
}
