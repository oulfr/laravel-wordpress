<?php

namespace App\Models\Metas;

use App\Models\Model;
use App\Models\Post;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class PostMeta
 * @package App\Models
 */
class PostMeta extends Model
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
    protected $table = 'postmeta';

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
    public function post(): Relation
    {
        return $this->belongsTo(Post::class, 'post_id', 'ID');
    }
}
