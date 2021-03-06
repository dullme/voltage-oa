<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
    $router->resource('auth/users', UserController::class);

    $router->get('/', 'HomeController@index')->name('home');

    $router->resource('invoices', InvoiceController::class);
    $router->get('print/invoices/{id}', 'InvoiceController@printDetail'); //打印
    $router->resource('projects', ProjectController::class);
    $router->resource('purchase-orders', PurchaseOrderController::class);
    $router->resource('shipping-invoices', ShippingInvoiceController::class);
    $router->get('print/shipping-invoices/{id}', 'ShippingInvoiceController@printDetail'); //打印
    $router->resource('sales-orders', SalesOrderController::class);
    $router->get('get-sales-orders', 'SalesOrderController@getSalesOrders');
    $router->resource('vendors', VendorController::class);
    $router->resource('receipt-batches', ReceiptBatchController::class);
    $router->resource('sales-order-batches', SalesOrderBatchController::class);//发货批次
    $router->resource('receive-payment-batches', ReceivePaymentBatchController::class);//收款批次

    $router->get('associated-invoice/{id}', 'InvoiceController@associatedInvoice');//关联发票
    $router->get('associated-invoice/payment/{id}', 'InvoiceController@associatedPaymentInvoice');//关联付款发票
    $router->post('associated-invoice/{id}', 'InvoiceController@saveMatchAmount');//保存关联发票
    $router->post('associated-invoice/payment/{id}', 'InvoiceController@savePaymentMatchAmount');//保存付款关联发票
    $router->post('associated-invoice/delete/{id}', 'InvoiceController@deleteMatchAmount');//撤销关联发票
    $router->post('associated-invoice/payment/delete/{id}', 'InvoiceController@deletePaymentMatchAmount');//撤销付款关联发票

    $router->resource('payment-batches', PaymentBatchController::class);
    $router->resource('delivery-batches', DeliveryBatchController::class);//交货批次

    $router->resource('clocks', ClockController::class);
    $router->resource('customers', CustomerController::class);
    $router->resource('receives', ReceiveController::class);


    $router->get('associated-receive/{id}', 'ReceiveController@associatedReceive');//关联水单
    $router->post('associated-receive/{id}', 'ReceiveController@saveAssociatedReceive');//保存关联水单
    $router->post('associated-receive/delete/{id}', 'ReceiveController@deleteAssociatedReceive');//撤销关联收款水单

    $router->get('associated-receive/water-bill/{id}', 'ReceiveController@associatedWaterBill');//关联收款水单
    $router->post('associated-receive/water-bill/{id}', 'ReceiveController@saveAssociatedWaterBill');//保存关联收款水单
    $router->post('associated-receive/water-bill/delete/{id}', 'ReceiveController@deleteAssociatedWaterBill');//撤销关联收款水单

    $router->resource('test-models', TestModelController::class);
    $router->resource('entry-summary-lines', EntrySummaryLineController::class);
});
