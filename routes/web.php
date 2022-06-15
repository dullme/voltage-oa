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

    $ent = \App\Models\EntrySummaryLine::get();

    $ent->map(function ($item){
        $Test = \App\Models\TestModel::where('buu', $item->entry_summary_number)->first();
        if($Test){
            $details = str_replace([PHP_EOL, ' ', ','],'',  $Test->details);
            if($item->line_goods_value_amount2){
                if(strpos($details, $item->line_goods_value_amount2)){
                    $item->check = true;
                    $item->save();
                }
            }


        }
    });
    

//    $excel = \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\ExtenderTemplateImport(), public_path('EVR Extender标签 90% V2 04292022 - rev1.xlsx'));
//    $excel = \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\WhipTemplateImport(), public_path('EVR Whip标签 90% V2 04292022 - rev1.xlsx'));


//    $etsl = \App\Models\TestModel::all();
//
//    $etsl->map(function ($item){
//        if($item->path){
//            if(File::exists(public_path('/files/'.$item->path)))
//            File::copy(public_path('/files/'.$item->path), public_path('/t_pdfs/'.$item->year . '/'.$item->id.'.pdf'));
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

//    $en = \App\Models\EntrySummaryLine::all();
//
//   $res =  $en->map(function ($item){
//        return substr($item->entry_summary_number, 0 ,3);
//    })->unique();

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
