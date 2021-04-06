<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->resource('invoices', InvoiceController::class);
    $router->resource('projects', ProjectController::class);
    $router->resource('orders', OrderController::class);
    $router->resource('shipping-invoices', ShippingInvoiceController::class);
});
