<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Relation;

class Attachment extends Post
{
    /**
     * The post type for the model.
     *
     * @var array|string
     */
    public $postType = 'attachment';

    /**
     * Get the Attachment parent post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function post(): Relation
    {
        return $this->belongsTo(Post::class, 'post_parent');
    }
}
