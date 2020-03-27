<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\PsySleight
 *
 * @property int                          $id
 * @property string                       $name
 * @property string                       $description
 * @property string                       $type
 * @property string                       $range
 * @property string                       $duration
 * @property string                       $action
 * @property string                       $level
 * @property string                       $strainMod
 * @property string|null                  $skill_name
 * @property-read Collection|BonusMalus[] $bonusMalus
 * @property-read Skill|null              $skill
 * @method static Builder|PsySleight newModelQuery()
 * @method static Builder|PsySleight newQuery()
 * @method static Builder|PsySleight query()
 * @method static Builder|PsySleight whereAction($value)
 * @method static Builder|PsySleight whereDescription($value)
 * @method static Builder|PsySleight whereDuration($value)
 * @method static Builder|PsySleight whereId($value)
 * @method static Builder|PsySleight whereLevel($value)
 * @method static Builder|PsySleight whereName($value)
 * @method static Builder|PsySleight whereRange($value)
 * @method static Builder|PsySleight whereSkillName($value)
 * @method static Builder|PsySleight whereStrainMod($value)
 * @method static Builder|PsySleight whereType($value)
 * @mixin \Eloquent
 */
class PsySleight extends Model
{
    protected $table = 'psySleights';

    /**
     * Some Sleights require tests.  This is the skill used for that test.  The Ego MUST have that skill to use the sleight!
     * TODO:  Ensure that this dependency is in the software!
     * @return BelongsTo
     */
    public function skill()
    {
        return $this->belongsTo(Skill::class, 'skill_name', 'name');
    }

    /**
     * WARNING: Make sure to look at the "pivot" property
     *          It's possible to have duplicate's and that's tallied rather than having duplicate entries.
     * @return BelongsToMany
     */
    public function bonusMalus()
    {
        return $this->belongsToMany(BonusMalus::class,
            'bonusMalus_psySleight',
            'psySleight_name',
            'bonusMalus_name',
            'name',
            'name'
        )->withPivot('occurrence');
    }
}
