<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->get('/groups/category', 'GroupsController@category')->name('groups.category');
    $router->resource('users', UserController::class);
    $router->resource('groups', GroupsController::class);
    $router->resource('feeds', FeedsController::class);
    $router->resource('categories', CategoryController::class);


});
