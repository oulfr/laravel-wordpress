<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Roles;
use App\Transformers\ProductTransformer;
use Flugg\Responder\Contracts\Transformers\TransformerResolver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    public function boot()
    {

        //Register all transformer here
        $this->app->make(TransformerResolver::class)->bind([
            Product::class => ProductTransformer::class,
        ]);

        //register
        $this->app->singleton('roles', function () {
            return new Roles();
        });
    }
}
