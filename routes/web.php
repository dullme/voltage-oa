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

//    $excel = \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\ExtenderTemplateImport(), public_path('AMD Extender - V1 05242022.xlsx'));
//    $excel = \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\WhipTemplateImport(), public_path('BFP Whip标签 IFC - V3 05302022.xlsx'));

//
//    $etsl = \App\Models\EntrySummaryLine::all();
//
//    $etsl->map(function ($item){
//        if($item->path){
//            File::copy(public_path('/files/'.$item->path), public_path('/pdfs/'.$item->year . '/'.$item->id.'.pdf'));
//        }
//    });



//    $importData = \Maatwebsite\Excel\Facades\Excel::toCollection(new \App\Imports\TemplateImport(), public_path('/files/NIK（6.6）.xls'))[0];
//
//    foreach ($importData as $key => $item) {
//        if ($key > 0) {
//
//            if($item[0] != '' && $item[16] != ''){
//                $EntrySummaryLine = \App\Models\EntrySummaryLine::where('entry_summary_number', $item[0])->first();
//                if($EntrySummaryLine){
//                    $EntrySummaryLine->line_goods_value_amount2 = $item[16];
//                    $EntrySummaryLine->save();
//                }
//            }
//        }
//
//    }

    return view('links');
});

Route::get('/clock', 'HomeController@clock');
Route::get('/download', 'HomeController@downloadSoftware');
Route::get('/links', 'HomeController@links');
Route::get('/cn/signature', 'HomeController@cnSignature');
Route::get('/en/signature', 'HomeController@enSignature');
Route::get('/create/no', 'HomeController@OrderNo');
Route::get('/create-no', 'HomeController@OrderNoCreate');
Route::get('/asd/{idcard}', 'HomeController@asd');
Route::get('/random', 'HomeController@random');
