<?php

namespace App\Listeners;

use Laravel\Passport\Events\RefreshTokenCreated;
use Laravel\Passport\RefreshToken;

class PruneOldTokens
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
    public function handle(RefreshTokenCreated $event)
    {
        RefreshToken::where([
            ['access_token_id', '<>', $event->accessTokenId],
            ['revoked', true]
        ])->delete();
    }
}
