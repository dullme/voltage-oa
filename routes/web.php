<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    $a = \App\Models\SalesOrder::with('salesOrderBatches', 'receivePaymentBatches')->get();
    $res = $a->map(function ($item){
        $salesOrder = \App\Models\SalesOrder::find($item->id);
        $shipment_amount = $item->salesOrderBatches->sum('amount');
        $received_amount = $item->receivePaymentBatches->sum('amount');
        $salesOrder->shipment_amount = $shipment_amount;
        $salesOrder->received_amount = $received_amount;

        if($salesOrder->amount == 0){
            $salesOrder->is_shipment = false;
            $salesOrder->is_received = false;
        }else{
            $salesOrder->is_shipment = $shipment_amount >= $salesOrder->amount;
            $salesOrder->is_received = $received_amount >= $salesOrder->amount;
        }
        
        $salesOrder->save();

        return $item;
    });

    return view('links');
});

Route::get('/clock', 'HomeController@clock');
Route::get('/download', 'HomeController@downloadSoftware');
Route::get('/links', 'HomeController@links');
Route::get('/cn/signature', 'HomeController@cnSignature');
Route::get('/en/signature', 'HomeController@enSignature');
