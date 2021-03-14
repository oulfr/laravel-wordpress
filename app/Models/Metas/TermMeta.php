<?php

namespace App\Models\Metas;

use App\Models\Model;
use App\Models\Term;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class TermMeta
 * @package App\Models
 */
class TermMeta extends Model
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
    protected $table = 'termmeta';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'meta_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meta_key',
        'meta_value',
    ];

    /**
     * Get the meta parent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function term(): Relation
    {
        return $this->belongsTo(Term::class, 'term_id', 'term_id');
    }
}
