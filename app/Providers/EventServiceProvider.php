<?php

namespace App\Providers;

use App\Listeners\PruneOldTokens;
use App\Listeners\QueryExecutedListener;
use App\Listeners\RevokeOldTokens;
use Illuminate\Database\Events\QueryExecuted;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;
use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\Events\RefreshTokenCreated;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AccessTokenCreated::class => [
            RevokeOldTokens::class
        ],
        RefreshTokenCreated::class => [
            PruneOldTokens::class
        ],
        QueryExecuted::class => [
            QueryExecutedListener::class
        ]
    ];
}
