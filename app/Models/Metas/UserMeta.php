<?php

namespace App\Models\Metas;

use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class UserMeta
 * @package App\Models
 */
class UserMeta extends Model
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
    protected $table = 'usermeta';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'umeta_id';

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
    public function user(): Relation
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
