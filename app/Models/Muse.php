<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

//use Illuminate\Database\Eloquent\Relations\BelongsToMany;


/**
 * App\Models\Muse
 *
 * @property int    $id
 * @property string $name
 * @property string $description
 * @property int    $cost
 * @method static Builder|Muse newModelQuery()
 * @method static Builder|Muse newQuery()
 * @method static Builder|Muse query()
 * @method static Builder|Muse whereCost($value)
 * @method static Builder|Muse whereDescription($value)
 * @method static Builder|Muse whereId($value)
 * @method static Builder|Muse whereName($value)
 * @mixin \Eloquent
 */
class Muse extends Model
{
    protected $casts = [
        'cost' => 'integer',
    ];
//    /**
//     * TODO: Implement this.  Laravel does not like compound keys!
//     * Muses have set skills, with unchangeable pre-set values.
//     *
//     * No duplicates for these.
//     * @return BelongsToMany
//     */
//    public function skills()
//    {
//        return $this->belongsToMany(Skill::class,
//            'muse_skill',
//            'muse_name',
//            '...',
//            'name',
//            '...'
//        )->withPivot('value');
//    }
}
