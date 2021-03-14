<?php

namespace App\Models;

use App\Concerns\HasMeta;
use App\Concerns\HasRoles;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Relations\Relation;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @package App\Models
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasApiTokens, HasMeta, HasRoles;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'user_registered';

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
    protected $table = 'users';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * @var array
     */
    protected $hidden = ['user_pass'];

    /**
     * @var array
     */
    protected $dates = ['user_registered'];

    /**
     * @var array
     */
    protected static $aliases = [
        'login' => 'user_login',
        'email' => 'user_email',
        'slug' => 'user_nicename',
        'url' => 'user_url',
        'nickname' => ['meta' => 'nickname'],
        'first_name' => ['meta' => 'first_name'],
        'last_name' => ['meta' => 'last_name'],
        'description' => ['meta' => 'description'],
        'created_at' => 'user_registered',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'login',
        'email',
        'slug',
        'url',
        'nickname',
        'first_name',
        'last_name',
        'avatar',
        'created_at',
    ];

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return $this->primaryKey;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->attributes[$this->primaryKey];
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->user_pass;
    }

    /**
     * Find the user instance for the given username.
     *
     * @param string $username
     * @return \App\Models\User
     */
    public function findForPassport($username)
    {
        return $this->where('user_login', $username)->first();
    }

    /**
     * Get the User posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function posts(): Relation
    {
        return $this->hasMany(Post::class, 'post_author');
    }

    /**
     * Get the User comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function comments(): Relation
    {
        return $this->hasMany(Comment::class, 'user_id');
    }
}
