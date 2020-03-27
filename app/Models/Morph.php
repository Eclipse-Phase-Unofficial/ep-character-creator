<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Morph
 *
 * @property int                          $id
 * @property string                       $name
 * @property string                       $description
 * @property string                       $type
 * @property int                          $maxAptitude
 * @property int                          $durability
 * @property int                          $cpCost
 * @property int                          $creditCost
 * @property-read Collection|BonusMalus[] $bonusMalus
 * @property-read Collection|BonusMalus[] $gear
 * @property-read Collection|BonusMalus[] $traits
 * @method static Builder|Morph newModelQuery()
 * @method static Builder|Morph newQuery()
 * @method static Builder|Morph query()
 * @method static Builder|Morph whereCpCost($value)
 * @method static Builder|Morph whereCreditCost($value)
 * @method static Builder|Morph whereDescription($value)
 * @method static Builder|Morph whereDurability($value)
 * @method static Builder|Morph whereId($value)
 * @method static Builder|Morph whereMaxAptitude($value)
 * @method static Builder|Morph whereName($value)
 * @method static Builder|Morph whereType($value)
 * @mixin \Eloquent
 */
class Morph extends Model
{
    protected $casts = [
        'maxAptitude' => 'integer',
        'durability'   => 'integer',
        'cpCost'       => 'integer',
        'creditCost'   => 'integer',
    ];

    /**
     * WARNING: Make sure to look at the "pivot" property
     *          It's possible to have duplicate's and that's tallied rather than having duplicate entries.
     * @return BelongsToMany
     */
    public function bonusMalus()
    {
        return $this->belongsToMany(BonusMalus::class,
            'bonusMalus_morph',
            'morph_name',
            'bonusMalus_name',
            'name',
            'name'
        )->withPivot('occurrence');
    }

    /**
     * Built in Gear.  These can NOT be removed.
     *
     * WARNING: Make sure to look at the "pivot" property
     *          It's possible to have duplicate's and that's tallied rather than having duplicate entries.
     * @return BelongsToMany
     */
    public function gear()
    {
        return $this->belongsToMany(Gear::class,
            'gear_morph',
            'morph_name',
            'gear_name',
            'name',
            'name'
        )->withPivot('occurrence');
    }

    /**
     * Fundamental Traits.  These can NOT be removed.
     *
     * No duplicates for these.
     * @return BelongsToMany
     */
    public function traits()
    {
        return $this->belongsToMany(Traits::class,
            'morph_trait',
            'morph_name',
            'trait_name',
            'name',
            'name'
        );
    }
}
