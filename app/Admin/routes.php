<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->resource('orders', OrderController::class);
    $router->resource('procs', WorkProcedureController::class);
    $router->get('orders/start/{orderid}', 'OrderController@start')->name('orders.start');
    $router->get('orders/urgent/{orderid}', 'OrderController@urgent')->name('orders.urgent');
    $router->get('orders/cancel/{orderid}', 'OrderController@cancel')->name('orders.cancel');
});
