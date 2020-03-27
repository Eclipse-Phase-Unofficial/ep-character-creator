<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Background
 *
 * @property int                          $id
 * @property string                       $name
 * @property string                       $description
 * @property string                       $type
 * @property-read Collection|BonusMalus[] $bonusMalus
 * @method static Builder|Background newModelQuery()
 * @method static Builder|Background newQuery()
 * @method static Builder|Background query()
 * @method static Builder|Background whereDescription($value)
 * @method static Builder|Background whereId($value)
 * @method static Builder|Background whereName($value)
 * @method static Builder|Background whereType($value)
 * @mixin \Eloquent
 */
class Background extends Model
{
    /**
     * WARNING: Make sure to look at the "pivot" property
     *          It's possible to have duplicate's and that's tallied rather than having duplicate entries.
     * @return BelongsToMany
     */
    public function bonusMalus()
    {
        return $this->belongsToMany(BonusMalus::class,
            'background_bonusMalus',
            'background_name',
            'bonusMalus_name',
            'name',
            'name'
        )->withPivot('occurrence');
    }
}
