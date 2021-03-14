<?php

namespace App\Listeners;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Events\RefreshTokenCreated;

class QueryExecutedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param RefreshTokenCreated $event
     * @return void
     */
    public function handle(QueryExecuted $query)
    {
        Log::debug(__METHOD__, ['SQL' => $query->sql]);
        Log::debug(__METHOD__, ['bindings' => $query->bindings]);
        Log::debug(__METHOD__, ['time' => $query->time]);
    }
}
