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
//    $excel = \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\WhipTemplateImport(), public_path('AMD Whip - V1 05242022.xlsx'));


//    $importData = \Maatwebsite\Excel\Facades\Excel::toCollection(new \App\Imports\TemplateImport(), public_path('/files/ES-002_Entry_Summary_Line_Details.xlsx'))[0];
//    foreach ($importData as $key => $item) {
//        if ($key > 0) {
//
//            $testModel = \App\Models\TestModel::where('buu', $item[0])->first();
//            $data = [];
//            if($testModel){
//                $data = [
//                    'year'  => $testModel->year,
//                    'dir'   => $testModel->dir,
//                    'path'  => $testModel->path,
//                    'buu'   => $testModel->buu,
//                    'matched'   => true,
//                ];
//            }
//
//            $data = array_merge($data, [
//                'entry_summary_number'        => $item[0],
//                'entry_type_code'             => $item[1],
//                'entry_summary_line_number'   => $item[2],
//                'review_team_number'          => $item[3],
//                'country_of_origin_code'      => $item[4],
//                'country_of_export_code'      => $item[5],
//                'manufacturer_id'             => $item[6],
//                'manufacturer_name'           => $item[7],
//                'foreign_exporter_id'         => $item[8],
//                'foreign_exporter_name'       => $item[9],
//                'line_spi_code'               => $item[10],
//                'line_spi'                    => $item[11],
//                'reconciliation_fta_status'   => $item[12],
//                'reconciliation_other_status' => $item[13],
//                'line_goods_value_amount'     => $item[14],
//                'line_duty_amount'            => $item[15],
//                'line_mpf_amount'             => $item[16],
//                'line_hmf_amount'             => $item[17],
//            ]);
//
//
//            \App\Models\EntrySummaryLine::create($data);

//            \App\Models\TestModel::where('buu', $item[0])->update([
//                'matched'                 => true,
//                'line_goods_value_amount' => $item[14],
//                'line_duty_amount'        => $item[15],
//                'line_mpf_amount'         => $item[16],
//                'line_hmf_amount'         => $item[17],
//            ]);
//        }

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
