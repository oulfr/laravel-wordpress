<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class Page
 * @package App\Models
 */
class Page extends Post
{
    /**
     * The post type for the model.
     *
     * @var array|string
     */
    public $postType = 'page';

    /**
     * Get the Page parent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function parent(): Relation
    {
        return $this->belongTo(Page::class, 'post_parent');
    }

    /**
     * Get the page children.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function children(): Relation
    {
        return $this->hasMany(Page::class, 'post_parent');
    }
}
