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
}
