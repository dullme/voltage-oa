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
//    //计算退税金额
//    $ent = \App\Models\EntrySummaryLine::get();
//    foreach ($ent as $item){
//        if($item->line_goods_value_amount2){
//            $item->tsje = bigNumber($item->line_goods_value_amount2)->multiply(0.25)->getValue();
//            $item->save();
//        }
//    }



//          //根据开头三位编码匹配代理
//        $ent = \App\Models\EntrySummaryLine::get();
//
//        foreach ($ent as $item){
//
//            if($item->entry_summary_number){
//                $sub_res = substr($item->entry_summary_number, 0, 3);
//
//                if($sub_res == 'BUU'){
//                    $item->hy_daili = 'PNL';
//                    $item->qg_daili = 'PNL';
//                    $item->save();
//                }
//
//                if($sub_res == 'NIK' || $sub_res == 'ATN' || $sub_res == '86P' || $sub_res == '96U'){
//                    $item->hy_daili = 'Safround';
//                    $item->qg_daili = 'Safround';
//                    $item->save();
//                }
//
//                if($sub_res == 'E4Y'){
//                    $item->hy_daili = 'Taggart';
//                    $item->qg_daili = 'Taggart';
//                    $item->save();
//                }
//
//                if($sub_res == 'DZ1'){
//                    $item->hy_daili = 'APEX';
//                    $item->qg_daili = 'APEX';
//                    $item->save();
//                }
//
//            }
//        }



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


//    $excel = \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\ExtenderTemplateImport(), public_path('AMD Extender - V1 05242022(3).xlsx'));
//    $excel = \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\WhipTemplateImport(), public_path('EVR Whip标签 90% V2 04292022 - rev1.xlsx'));


//    $etsl = \App\Models\TestModel::all();
//
//    $etsl->map(function ($item){
//        if($item->path){
//            if(File::exists(public_path('/files/'.$item->path)))
//            File::copy(public_path('/files/'.$item->path), public_path('/t_pdfs/'.$item->year . '/'.$item->id.'.pdf'));
//        }
//    });


//    //比对海关数据是否齐全
//    $importData = \Maatwebsite\Excel\Facades\Excel::toCollection(new \App\Imports\TemplateImport(), public_path('ES-002_Entry_Summary_Line_Details (2)_010116-060122888888888888.xls'))[0];
//
//    $res = $importData->map(function ($item){
//        return [
//            'buu' => $item[0]
//        ];
//    });
//
//    $rrr = \App\Models\EntrySummaryLine::pluck('entry_summary_number')->toArray();
//dd(array_diff($res->pluck('buu')->toArray(), $rrr));


//    //buu 对应 BL 数据匹配
//    $importData = \Maatwebsite\Excel\Facades\Excel::toCollection(new \App\Imports\TemplateImport(), public_path('从物流网站invoice 导出的BUU对于BL数据 含金额.xlsx'))[0];
//
//    $importData->map(function ($item, $key){
//        if($key > 0){
//            $buu = str_replace('-', '', rtrim(ltrim($item[0])));
//            $hyf = $item[1];
//            $gs = $item[2];
//
//            $encount = \App\Models\EntrySummaryLine::where('entry_summary_number', $buu)->get();
//            $encount->map(function ($entry) use($buu, $hyf, $gs){
//
//                if($entry->entry_summary_number == $buu){
//
//                    if($entry->hyf){
//                        $entry->hyf = $hyf;
//                    }
//
//                    if($entry->gs){
//                        $entry->gs = $gs;
//                    }
//
//                    if($entry->hyf || $entry->gs){
//                        $entry->save();
//                    }
//
//                }
//
//            });
//        }
//    });
//



//    //表格一
//    $importData = \Maatwebsite\Excel\Facades\Excel::toCollection(new \App\Imports\TemplateImport(), public_path('表格1(1).xlsx'))[0];
//
//    $buu = $importData->map(function ($item){
//        return [
//            'buu' => str_replace('-', '', $item[4])
//        ];
//    });
//
//    if($buu->where('buu', '!=', '')->pluck('buu')->count() != $buu->where('buu', '!=', '')->pluck('buu')->unique()->count()){
//        dd('有重复值');
//    }
//
//
//    $res = $importData->map(function ($item, $key){
//        $buu = str_replace('-', '', $item[4]);
//        $b_l = rtrim(ltrim($item[5]));
//
//        if($key > 3){
//            if($item[4]){ //如果存在BUU
//                \App\Models\EntrySummaryLine::updateOrCreate([
//                    'buu' => $buu,
//                ], [
//                    'b_l' => $b_l,
//                    'kcsj' => $item[6],
//                    'hyf' => $item[9],
//                    'gs' => $item[10],
//                    'yjfksj' => $item[11],
//                    'source' => '表格一【系统中不存在BUU】'
//                ]);
//
//            }else if($item[5]){
//                \App\Models\EntrySummaryLine::updateOrCreate([
//                    'b_l' => $b_l,
//                ], [
//                    'buu' => $buu,
//                    'kcsj' => $item[6],
//                    'hyf' => $item[9],
//                    'gs' => $item[10],
//                    'yjfksj' => $item[11],
//                    'source' => '表格一【系统中不存在B/L】'
//                ]);
//            }
//        }
//
//    });
//
//
//    //表格二
//    $importData = \Maatwebsite\Excel\Facades\Excel::toCollection(new \App\Imports\TemplateImport(), public_path('表格2.xlsx'))[0];
//    $res = $importData->map(function ($item, $key){
//        $data = [
//            'b_l' => rtrim(ltrim($item[0])),
//            'nlyf' => $item[1]
//        ];
//        if($key > 0){
//            \App\Models\EntrySummaryLine::updateOrCreate([
//                'b_l' => $data['b_l'],
//            ], [
//                'nlyf' => $data['nlyf'],
//                'source' => '表格二【系统中不存在B/L】'
//            ]);
//        }
//
//
//       return $data;
//    });
//
//    //表格三
//    $importData = \Maatwebsite\Excel\Facades\Excel::toCollection(new \App\Imports\TemplateImport(), public_path('表格3.xlsx'))[0];
//
//    $res = $importData->map(function ($item, $key){
//        $data = [
//            'b_l' => rtrim(ltrim($item[1])),
//            'buu' => str_replace('-', '', $item[2])
//        ];
//        if($key > 0){
//            \App\Models\EntrySummaryLine::where('buu', $data['buu'])->update([
//                'sfxyts' => true
//            ]);
//        }
//
//
//       return $data;
//    });


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
