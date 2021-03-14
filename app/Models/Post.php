<?php

namespace App\Models;

use App\Concerns\HasMeta;
use App\Scopes\PostTypeScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Staudenmeir\EloquentHasManyDeep\HasRelationships as HasManyDeep;

/**
 * Class Post
 * @package App\Models
 */
class Post extends Model
{
    use HasMeta, HasManyDeep;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'post_date';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'post_modified';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * The post type for the model.
     *
     * @var array|string
     */
    public $postType = 'post';

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new PostTypeScope());
    }

    /**
     * Get the Post title.
     *
     * @return string
     */
    public function getTitleAttribute(): string
    {
        return $this->post_title;
    }

    /**
     * Get the Post content. Strip HTML comments (from the block editor).
     *
     * @return string
     */
    public function getContentAttribute(): string
    {
        return preg_replace('/<!--.*?-->/', '', $this->post_content);
    }

    /**
     * Get the Post status.
     *
     * @return string
     */
    public function getStatusAttribute(): string
    {
        return $this->post_status;
    }

    /**
     * Get the Post type.
     *
     * @return string
     */
    public function getTypeAttribute(): string
    {
        return $this->post_type;
    }

    /**
     * Get the Post slug.
     *
     * @return string
     */
    public function getSlugAttribute(): string
    {
        return $this->post_name;
    }

    /**
     * Get the created at date.
     *
     * @return Carbon\Carbon
     */
    public function getCreatedAtAttribute(): Carbon
    {
        return $this->post_date;
    }

    /**
     * Get the updated at date.
     *
     * @return Carbon\Carbon
     */
    public function getUpdatedAtAttribute(): Carbon
    {
        return $this->post_modified;
    }

    /**
     * Return the featured image HTML.
     *
     * @param string|array $size
     * @param string|array $attr
     * @return string
     */
    public function featuredImage($size = 'post-thumbnail', $attr = ''): string
    {
        //return wp_get_attachment_image($this->getMeta('_thumbnail_id'), $size, false, $attr);
    }

    /**
     * Scope the Post query to published posts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('post_status', 'publish');
    }

    /**
     * Get the Post author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function author(): Relation
    {
        return $this->belongsTo(User::class, 'post_author');
    }

    /**
     * Get the Post comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function comments(): Relation
    {
        return $this->hasMany(Comment::class, 'comment_post_ID');
    }

    /**
     * Get the Post attachments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function attachments(): Relation
    {
        return $this->hasMany(Attachment::class, 'post_parent');
    }

    /**
     * Get the Post terms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function terms(): Relation
    {
        return $this->hasManyDeep(
            Term::class,
            [TermRelationship::class, Taxonomy::class],
            [
                'object_id',
                'term_taxonomy_id',
                'term_id',
            ],
            [
                'ID',
                'term_taxonomy_id',
                'term_id',
            ]
        );
    }

    /**
     * Get the Post categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function categories(): Relation
    {
        return $this->terms()->where('taxonomy', 'category');
    }

    /**
     * Get the Post tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function tags(): Relation
    {
        return $this->terms()->where('taxonomy', 'post_tag');
    }
}
