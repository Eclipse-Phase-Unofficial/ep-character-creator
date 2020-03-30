<?php

namespace App\Models;

use Exception;
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
 * @property bool                         $isActive   Active Sleights require conscious activation.  Passive give bonuses all the time.  WARNING: Not active when not in a biological brain!
 * @property string                       $range      An Enum of the RANGE_... const values
 * @property string                       $duration   An Enum of the DURATION... const values
 * @property string                       $action     An Enum of the EPPsySleight::ACTION_... static/const values
 * @property bool                         $isPsyGamma Psy Sleights can be either Chi (lower level) or Gamma (higher level)
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
    const RANGE_SELF = 'SELF';
    const RANGE_TOUCH = 'TOUCH';
    const RANGE_CLOSE = 'CLOSE';
    const RANGE_PSY = 'PSY';

    const DURATION_CONSTANT = 'constant';
    const DURATION_INSTANT = 'instant';
    const DURATION_TEMPORARY = 'temporary';
    const DURATION_SUSTAINED = 'sustained';

    protected $table = 'psySleights';

    protected $casts = [
//        'isActive' => 'boolean' //Disabled since this does not work with "false"
//        'isPsyGamma' => 'boolean' //Disabled since this does not work with "false"
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
    public function getIsPsyGammaAttribute($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

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

    /**
     * Make sure the data isn't doing something stupid.
     * @throws Exception
     */
    public function sanityCheck()
    {
        //'MUL' is EPBonusMalus::$MULTIPLE
        if ($this->isActive && is_null($this->skill)) {
            throw new Exception("Active Psy Sleights must have an associated skill!");
        }
    }
}
