<?php

namespace App\Models;

use App\Concerns\HasAliases;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * Class Model
 * @package App\Models
 */
class Model extends EloquentModel
{
    use HasAliases;

    /**
     * @var array
     */
    protected static $aliases = [];
}
