<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\BonusMalus
 *
 * @property int    $id
 * @property string $name
 * @property string $description
 * @property string $type               An enum for about half the $ON_... static/const values in EPBonusMalus
 * @property string $target             The item the target is affecting
 *                                      TODO:  Rename this to `target_name`, make it nullable, and set is as a laravel polymorphic relationship.
 * @property int    $value
 * @property string $targetForChoice    An enum for the other half the $ON_... static/const values ($ON_SKILL, $ON_ARMOR, etc.)
 *                                      TODO:  This is a specific for the $target polymorphic relationship, eg. $ON_ARMOR means a subtype of Gear.
 * @property string $typeTarget         A skill's Prefix.  Used in combination with $target for skills.  Otherwise unused.
 *                                      TODO:  Rename this to `skill_prefix`, make this nullable, and set the foreign key relationship in the database
 * @property bool   $isCostModifier     If this modifies the target's cost instead of it's attribute.
 * @property int    $requiredSelections How many $bonusMalusTypes the user must select
 * @method static Builder|BonusMalus newModelQuery()
 * @method static Builder|BonusMalus newQuery()
 * @method static Builder|BonusMalus query()
 * @method static Builder|BonusMalus whereDescription($value)
 * @method static Builder|BonusMalus whereId($value)
 * @method static Builder|BonusMalus whereIsCostModifier($value)
 * @method static Builder|BonusMalus whereName($value)
 * @method static Builder|BonusMalus whereRequiredSelections($value)
 * @method static Builder|BonusMalus whereTarget($value)
 * @method static Builder|BonusMalus whereTargetForChoice($value)
 * @method static Builder|BonusMalus whereType($value)
 * @method static Builder|BonusMalus whereTypeTarget($value)
 * @method static Builder|BonusMalus whereValue($value)
 * @mixin \Eloquent
 */
class BonusMalus extends Model
{
    protected $table = 'bonusMalus';

    protected $casts = [
        'value'              => 'integer',
//        'isCostModifier' => 'boolean',  //Disabled since this does not work with "false"
        'requiredSelections' => 'integer',
    ];

    /**
     * Fix for SQLite note supporting booleans properly.
     * WARNING:  This does not affect json_encode!
     * @param $value
     * @return bool
     */
    public function getIsCostModifierAttribute($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Make sure the data isn't doing something stupid.
     * @throws Exception
     */
    public function sanityCheck()
    {
        //Items which require selections must have at least one choice
        //'MUL' is EPBonusMalus::$MULTIPLE
        if ($this->targetForChoice == 'MUL' && $this->requiredSelections <= 0) {
            throw new Exception("Object Should not ask for user choice when there are no selections allowed!");
        }
    }

    /**
     * Some BMs require choices or have Sub BMs.  This is the link to those.
     * @return BelongsToMany
     */
    public function bonusMalusTypes()
    {
        return $this->belongsToMany(BonusMalus::class,
            'bonusMalus_bonusMalus',
            'bonusMalus_name',
            'bonusMalusChoice_name',
            'name',
            'name'
        );
    }

    /**
     * Hack to support functionality that doesn't even work correctly.
     *
     * Specifically, this should be used so that the Infolife background is restricted from taking the Psi Traits!
     *
     * TODO:  This might be better served using the "BackgroundLimitations" table directly!
     *
     * @return string[]
     */
    public function groups(): array
    {
        if( $this->name == "Psi trait prohibited" ) {
            return [
                "Psi I",
                "Psi II",
                "Psi II (Lost)",
            ];
        }
        return [];
    }
}
