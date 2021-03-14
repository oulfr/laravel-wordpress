<?php

namespace App\Models\Metas;

use App\Models\Comment;
use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class CommentMeta
 * @package App\Models
 */
class CommentMeta extends Model
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
    protected $table = 'commentmeta';

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
    public function comment(): Relation
    {
        return $this->belongsTo(Comment::class, 'comment_id', 'comment_ID');
    }
}
