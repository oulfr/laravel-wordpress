<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class Taxonomy
 * @package App\Models
 */
class Taxonomy extends Model
{
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
    protected $table = 'term_taxonomy';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'term_taxonomy_id';

    /**
     * Get the Taxonomy name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->taxonomy;
    }

    /**
     * Scope the Taxonomy to categories.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCategory(Builder $query): Builder
    {
        return $query->where('taxonomy', 'category');
    }

    /**
     * Scope the Taxonomy to tags.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTag(Builder $query): Builder
    {
        return $query->where('taxonomy', 'post_tag');
    }

    /**
     * Get the Taxonomy terms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function terms(): Relation
    {
        return $this->hasMany(Term::class, 'term_id', 'term_id');
    }
}
