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

//    $ent = \App\Models\EntrySummaryLine::get();
//
//    $ent->map(function ($item){
//        $Test = \App\Models\TestModel::where('buu', $item->entry_summary_number)->first();
//        if($Test){
//            $details = str_replace([PHP_EOL, ' ', ','],'',  $Test->details);
//            $amount = bigNumber($item->line_duty_amount2)->add($item->line_mpf_amount2)->add($item->line_hmf_amount2)->getValue();
//            if($amount > 0 && strpos($details, $amount)){
//                $item->check = true;
//                $item->save();
//            }else{
//                $item->check = false;
//                $item->save();
//            }
//        }
//    });


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


    //表格一
    $importData = \Maatwebsite\Excel\Facades\Excel::toCollection(new \App\Imports\TemplateImport(), public_path('表格1(1).xlsx'))[0];

    $buu = $importData->map(function ($item){
        return [
            'buu' => str_replace('-', '', $item[4])
        ];
    });

    if($buu->where('buu', '!=', '')->pluck('buu')->count() != $buu->where('buu', '!=', '')->pluck('buu')->unique()->count()){
        dd('有重复值');
    }


    $res = $importData->map(function ($item, $key){
        $buu = str_replace('-', '', $item[4]);
        $b_l = rtrim(ltrim($item[5]));

        if($key > 3){
            if($item[4]){ //如果存在BUU
                \App\Models\EntrySummaryLine::updateOrCreate([
                    'buu' => $buu,
                ], [
                    'b_l' => $b_l,
                    'kcsj' => $item[6],
                    'hyf' => $item[9],
                    'gs' => $item[10],
                    'yjfksj' => $item[11],
                    'source' => '表格一【系统中不存在BUU】'
                ]);

            }else if($item[5]){
                \App\Models\EntrySummaryLine::updateOrCreate([
                    'b_l' => $b_l,
                ], [
                    'buu' => $buu,
                    'kcsj' => $item[6],
                    'hyf' => $item[9],
                    'gs' => $item[10],
                    'yjfksj' => $item[11],
                    'source' => '表格一【系统中不存在B/L】'
                ]);
            }
        }

    });


    //表格二
    $importData = \Maatwebsite\Excel\Facades\Excel::toCollection(new \App\Imports\TemplateImport(), public_path('表格2.xlsx'))[0];
    $res = $importData->map(function ($item, $key){
        $data = [
            'b_l' => rtrim(ltrim($item[0])),
            'nlyf' => $item[1]
        ];
        if($key > 0){
            \App\Models\EntrySummaryLine::updateOrCreate([
                'b_l' => $data['b_l'],
            ], [
                'nlyf' => $data['nlyf'],
                'source' => '表格二【系统中不存在B/L】'
            ]);
        }


       return $data;
    });

    //表格三
    $importData = \Maatwebsite\Excel\Facades\Excel::toCollection(new \App\Imports\TemplateImport(), public_path('表格3.xlsx'))[0];

    $res = $importData->map(function ($item, $key){
        $data = [
            'b_l' => rtrim(ltrim($item[1])),
            'buu' => str_replace('-', '', $item[2])
        ];
        if($key > 0){
            \App\Models\EntrySummaryLine::where('buu', $data['buu'])->update([
                'sfxyts' => true
            ]);
        }


       return $data;
    });


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
