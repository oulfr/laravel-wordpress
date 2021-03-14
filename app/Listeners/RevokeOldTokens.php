<?php

namespace App\Listeners;

use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

class RevokeOldTokens
{

    /**
     * RevokeOldTokens constructor.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param AccessTokenCreated $event
     * @return void
     */
    public function handle(AccessTokenCreated $event)
    {
        Token::where([
            ['id', '<>', $event->tokenId],
            ['user_id', $event->userId],
            ['client_id', $event->clientId],
        ])->orwhere('revoked', true)->each(function ($token, $key) {
            $token->delete();
            RefreshToken::where([
                ['access_token_id', '=', $token->id]
            ])->delete();
        });
    }
}
