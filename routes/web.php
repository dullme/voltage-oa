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

    $a = \App\Models\PurchaseOrder::with('receiptBatches', 'paymentBatches')->get();
    $res = $a->map(function ($item){
        $purchaseOrder = \App\Models\PurchaseOrder::find($item->id);
        $receiptBatches = $item->receiptBatches->sum('amount');
        $paymentBatches = $item->paymentBatches->sum('amount');
        $purchaseOrder->received_amount = $receiptBatches;
        $purchaseOrder->paid_amount = $paymentBatches;

        if($purchaseOrder->amount == 0){
            $purchaseOrder->is_received = false;
            $purchaseOrder->is_paid = false;
        }else{
            $purchaseOrder->is_received = $receiptBatches >= $purchaseOrder->amount;
            $purchaseOrder->is_paid = $paymentBatches >= $purchaseOrder->amount;
        }

        $purchaseOrder->save();

        return $item;
    });

    return view('links');
});

Route::get('/clock', 'HomeController@clock');
Route::get('/download', 'HomeController@downloadSoftware');
Route::get('/links', 'HomeController@links');
Route::get('/cn/signature', 'HomeController@cnSignature');
Route::get('/en/signature', 'HomeController@enSignature');
