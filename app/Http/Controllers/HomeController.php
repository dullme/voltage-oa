<?php

namespace App\Http\Controllers;

use App\Models\Clock;
use App\Models\OrderNoCreate;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function random()
    {
        return view('random');
    }

    public function clock()
    {
        $clocks = Clock::where('star', true)->get();
        return view('clock', compact('clocks'));
    }

    public function downloadSoftware(){
        return view('software');
    }

    public function links(){
        return view('links');
    }

    public function cnSignature()
    {
        return view('cn_signature');
    }

    public function enSignature()
    {
        return view('en_signature');
    }

    public function OrderNo()
    {
        $order_nos = OrderNoCreate::orderBy('created_at', 'DESC')->paginate(10);

        return view('order_no', compact('order_nos'));
    }

    public function OrderNoCreate()
    {
        $data['author'] = 'FT';
        $data['year'] = Carbon::now()->year;

        $order_nos = OrderNoCreate::where('year', $data['year'])->get();

        if($order_nos->count() == 0){
            $data['no'] = 1;
        }else{
            $order_no = OrderNoCreate::where('year', $data['year'])->orderBy('no', 'DESC')->first();
            $data['no'] = $order_no['no'] + 1;
        }
        $data['order_no'] = 'FT'.substr($data['year'], 2, 2).'.' . sprintf('%05d', $data['no']);
        $order = OrderNoCreate::create($data);

        return response()->json($order);
    }


    public function asd($idcard)
    {
        $a = random_int(100000, 999999);
        $originalData = $idcard.$a;
        $fp = fopen(asset('public.key'), 'r');
        $publicKey=fread($fp,8192);
        fclose($fp);

        $encryptData = '';
        openssl_public_encrypt($originalData, $encryptData, $publicKey);
        $base_url = 'https://m.client.10010.com/mobileService/login/getBroadBandInfo.htm?version=android@8.0201&idCard='.urlencode(base64_encode($encryptData)).'&cityCode=190&deviceId=359250053525746&deviceCode=359250053533745&pip=192.168.31.104&desmobile=';


        $client = new Client();

        $str = $client->get($base_url)->getBody()->getContents();
//        $str = str_replace(PHP_EOL, '', $str);

        $patt1 = '/toClient\(([\w\W]*?)\)">进入/';
        $patt2 = '/vCode=\'([\w\W]*?)\'/';
        $patt3 = '/cityCode":"([\w\W]*?)"/';

        preg_match_all($patt1, $str, $rs); //匹配accountID
        preg_match_all($patt2, $str, $vcode); //匹配vCode
        preg_match_all($patt3, $str, $city); //匹配cityCode

        $res = $rs[1];
        if($res){
            $data = [];
            foreach($res as $key=>$item){
                $button_info = explode(',', $item);
                $data[$key] = [
                    'dataId' => $button_info[0],
                    'mobile' => $button_info[1], //accountId
                    'addressType' => $button_info[2],
                    'broadType' => $button_info[3],
                    'password' => $vcode[1][0], //vcode
                    'cityCode' => $city[1][0],
                    'timestamp' => date('YmdHis', time()),
                    'pip' => '192.168.31.'.random_int(0, 254),
                ];
            }

            dd($str,$data);
        }else{
            echo '该用户当日受限制';
        }


    }
}
