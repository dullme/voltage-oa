<?php

namespace App\Http\Controllers;

use App\Models\Clock;
use App\Models\OrderNoCreate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{

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
        $order_nos = OrderNoCreate::orderBy('created_at', 'DESC')->get();

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
}
