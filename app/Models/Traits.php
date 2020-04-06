<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Traits
 *
 * Note:  This isn't singular since 'Trait' is a PHP reserved keyword
 *
 * @property int                          $id
 * @property string                       $name
 * @property string                       $description
 * @property bool                         $isForMorph  Determines if this is for an Ego, Morph or both. Traits for both are null!
 * @property int|null                     $cpCost      null cpCost means the player can not add it. Negative means it gives points (negative traits). 0 means Neutral. Positive means positive trait.
 * @property int                          $level
 * @property string                       $JustFor     Some traits have Morph restrictions, those are set here. An enum value of EPTrait::[$CAN_USE_EVERYBODY, $CAN_USE_BIO, $CAN_USE_SYNTH, $CAN_USE_POD]
 * @property-read Collection|BonusMalus[] $bonusMalus
 * @method static Builder|Traits newModelQuery()
 * @method static Builder|Traits newQuery()
 * @method static Builder|Traits query()
 * @method static Builder|Traits whereCpCost($value)
 * @method static Builder|Traits whereDescription($value)
 * @method static Builder|Traits whereId($value)
 * @method static Builder|Traits whereIsForMorph($value)
 * @method static Builder|Traits whereJustFor($value)
 * @method static Builder|Traits whereLevel($value)
 * @method static Builder|Traits whereName($value)
 * @mixin \Eloquent
 */
class Traits extends Model
{
    protected $casts = [
//        'isForMorph' => 'boolean', //Disabled since this does not work with "false"
        'cpCost' => 'integer', //Can also be null
        'level'  => 'integer',
    ];

    /**
     * Fix for SQLite note supporting booleans properly.
     * Also handles fact this may be null!
     * WARNING:  This does not affect json_encode!
     * @param $value
     * @return bool
     */
    public function getIsForMorphAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * WARNING: Make sure to look at the "pivot" property
     *          It's possible to have duplicate's and that's tallied rather than having duplicate entries.
     * @return BelongsToMany
     */
    public function bonusMalus()
    {
        return $this->belongsToMany(BonusMalus::class,
            'bonusMalus_trait',
            'trait_name',
            'bonusMalus_name',
            'name',
            'name'
        )->withPivot('occurrence');
    }
}
