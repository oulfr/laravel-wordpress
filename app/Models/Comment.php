<?php

namespace App\Models;

use App\Concerns\HasMeta;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class Comment
 * @package App\Models
 */
class Comment extends Model
{
    use HasMeta;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'comment_date';

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
    protected $table = 'comments';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'comment_ID';

    /**
     * Get the comment content.
     *
     * @return string
     */
    public function getContentAttribute(): string
    {
        return $this->comment_content;
    }

    /**
     * Get the comment parent ID.
     *
     * @return integer
     */
    public function getParentAttribute(): int
    {
        return $this->comment_parent;
    }

    /**
     * Get the created at date.
     *
     * @return Carbon\Carbon
     */
    public function getCreatedAtAttribute(): Carbon
    {
        return $this->comment_date;
    }

    /**
     * Scope the Comment query to approved posts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('comment_approved', 1);
    }

    /**
     * Get the Comment post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function post(): Relation
    {
        return $this->belongsTo(Post::class, 'comment_post_ID');
    }

    /**
     * Get the Comment author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function author(): Relation
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the Comment author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function replies(): Relation
    {
        return $this->hasMany(Comment::class, 'comment_parent');
    }
}
