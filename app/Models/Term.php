<?php

namespace App\Models;

use App\Concerns\HasMeta;
use Illuminate\Database\Eloquent\Relations\Relation;
use Staudenmeir\EloquentHasManyDeep\HasRelationships as HasManyDeep;

/**
 * Class Term
 * @package App\Models
 */
class Term extends Model
{
    use HasMeta;
    use HasManyDeep;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'terms';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'term_id';

    /**
     * Get the Term posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function posts(): Relation
    {
        return $this->hasManyDeep(
            Post::class,
            [Taxonomy::class, TermRelationship::class],
            [
                'term_id',
                'term_taxonomy_id',
                'ID',
            ],
            [
                'term_id',
                'term_taxonomy_id',
                'object_id',
            ]
        );
    }
}
