<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group([
    'prefix' => config('api.version'),
    'namespace' => config('api.namespace'),
    'middleware' => ['throttle:' . config('api.throttle')]], function () use ($router) {

    //guest

    //auth
    $router->group([
        'middleware' => ['auth:api','has_cap:customer|shop_manager|New_Roles']], function () use ($router) {

        //Product endpoints
        $router->post('products', [
            'uses' => 'ProductController@store'
        ]);
        $router->get('products', [
            'uses' => 'ProductController@index'
        ]);
        $router->get('products/{id}', [
            'uses' => 'ProductController@show'
        ]);
        $router->put('products/{id}', [
            'uses' => 'ProductController@update'
        ]);
        $router->delete('products/{id}', [
            'uses' => 'ProductController@destroy'
        ]);


    });

});
