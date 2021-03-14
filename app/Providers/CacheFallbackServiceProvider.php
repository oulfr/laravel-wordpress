<?php

namespace App\Providers;

use App\Cache\CacheFallback;
use Illuminate\Support\ServiceProvider;

/**
 * Provide cache fallback singletons.
 */
class CacheFallbackServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function register(): void
    {
        // We replace the cache manager by our implementation
        $this->app->extend('cache', function ($app) {
            return new CacheFallback($this->app);
        });

        $this->app->extend('cache.store', function ($app) {
            return $this->app['cache']->driver();
        });
    }
}

