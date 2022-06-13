<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    protected $fillable = [
        'year',
        'dir',
        'path',
        'buu',
        'remarks',
        'details',
        'matched',
        'line_goods_value_amount',
        'line_duty_amount',
        'line_mpf_amount',
        'line_hmf_amount',
    ];
}
