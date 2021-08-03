<?php

namespace App\Http\Controllers;

use App\Models\Clock;
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
}
